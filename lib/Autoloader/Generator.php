<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2012 César Rodas                                                  |
  +---------------------------------------------------------------------------------+
  | Redistribution and use in source and binary forms, with or without              |
  | modification, are permitted provided that the following conditions are met:     |
  | 1. Redistributions of source code must retain the above copyright               |
  |    notice, this list of conditions and the following disclaimer.                |
  |                                                                                 |
  | 2. Redistributions in binary form must reproduce the above copyright            |
  |    notice, this list of conditions and the following disclaimer in the          |
  |    documentation and/or other materials provided with the distribution.         |
  |                                                                                 |
  | 3. All advertising materials mentioning features or use of this software        |
  |    must display the following acknowledgement:                                  |
  |    This product includes software developed by César D. Rodas.                  |
  |                                                                                 |
  | 4. Neither the name of the César D. Rodas nor the                               |
  |    names of its contributors may be used to endorse or promote products         |
  |    derived from this software without specific prior written permission.        |
  |                                                                                 |
  | THIS SOFTWARE IS PROVIDED BY CÉSAR D. RODAS ''AS IS'' AND ANY                   |
  | EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED       |
  | WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE          |
  | DISCLAIMED. IN NO EVENT SHALL CÉSAR D. RODAS BE LIABLE FOR ANY                  |
  | DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES      |
  | (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;    |
  | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND     |
  | ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT      |
  | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS   |
  | SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE                     |
  +---------------------------------------------------------------------------------+
  | Authors: César Rodas <crodas@php.net>                                           |
  +---------------------------------------------------------------------------------+
*/

namespace Autoloader;

use Symfony\Component\Finder\Finder,
    Notoj\Notoj,
    Artifex\Util\PHPTokens,
    Artifex,
    crodas\Path;

class Generator
{
    protected $path;
    protected $stats;
    protected $callback;
    protected $callback_path;

    protected $namespace = "";
    protected $alias = array();
    protected $lastClass = NULL;

    protected $trait;

    protected $classes;
    protected $classes_obj;
    protected $hasTraits;
    protected $hasInterface;

    /** settings */

    protected $relative     = false;
    protected $include_psr0 = true;
    protected $singleFile   = true;


    public function __construct($dir = NULL)
    {
        if (!is_null($dir)) {
            $this->setScanPath($dir);
        }
        $this->trait = defined('T_TRAIT') ? T_TRAIT : "";
    }

    public function singleFile()
    {
        $this->singleFile = true;
        return $this;
    }

    public function multipleFiles()
    {
        $this->singleFile = false;
        return $this;
    }

    public function relativePaths ($rel = true)
    {
        $this->relative = $rel;
        return $this;
    }

    public function IncludePSR0Autoloader($psr0 = true)
    {
        $this->include_psr0 = $psr0;
        return $this;
    }

    public function setScanPath($dir) {
        if ($dir instanceof Finder || is_array($dir)) {
            $this->path = $dir;
            return true;
        }

        if (!is_dir($dir)) {
            throw new \RuntimeException("{$dir} doesn't exists");
        }
        if (!is_readable($dir)) {
            throw new \RuntimeException("{$dir} cannot be read");
        }
        $finder = new Finder;
        $this->path = $finder->files()
            ->name('*.php')
            ->in($dir);
        return true;
    }


    public function setStepCallback(\Closure $cbc)
    {
        $this->callback = $cbc;

        return $this;
    }

    public function setPathCallback(\Closure $cbc)
    {
        $this->callback_path = $cbc;
        return $this;
    }

    public function enableStats($name)
    {
        $this->stats = $name;
    }

    protected function reset()
    {
        $this->namespace = "";
        $this->alias     = array();
        $this->lastClass = NULL;
    }

    protected function getLastClass()
    {
        return $this->lastClass;
    }

    public function setLastClass($class)
    {
        $this->lastClass = $class;
    }

    protected function setNamespace($namespace)
    {
        $this->namespace = empty($namespace) ? "" : $namespace . "\\";
    }

    protected function setAlias($alias, $class)
    {
        $this->alias[$alias] = $class;
    }

    protected function getNamespace($php)
    {
        $php->move()
            ->moveWhile(array(T_WHITESPACE));
        $start = $php->getOffset();
        $php->moveWhile(array(T_WHITESPACE, T_STRING, T_NS_SEPARATOR));
        $tokens = array_map(function($token) {
            return $token[0] == T_WHITESPACE ? "" : $token[1];
        }, $php->getTokens($start, $php->getOffset() - $start));
        return implode("", $tokens);
    }


    protected function getClass($name)
    {
        if (empty($name)) {
            throw new \RuntimeException("Invalid class name");
        }
        if ($name[0] == "\\") {
            $className = substr($name, 1);
        } else if (isset($this->alias[$name])) {
            $className = $this->alias[$name];
        } else {
            $className = $this->namespace . $name;
        }
        $index = strtolower($className);
        if (!isset($this->classes_obj[$index])) {
            $this->classes_obj[$index] = new ClassDef($className);
        }
        return $this->classes_obj[$index];
    }

    public function parseClass(PHPTokens $php)
    {
        $type = $php->getToken();
        $token = $php->whileNot(array(T_STRING))
            ->getToken();
        $class = $this->getClass($token[1]);
        $class->isLocal(true);
        $class->SetType($type[0]);
        $class->setFile($this->currentFile);

        $this->setLastClass($class);

        $token = $php->move()
            ->whileNot(array(T_EXTENDS, T_IMPLEMENTS, '{'))
            ->getToken();
        while ($token != '{') {
            $parentClass = $this->getClass($this->getNamespace($php));
            $class->addDependency($parentClass);
            $token = $php->getToken();
        }
    }

    public function parseUse(PHPTokens $php)
    {
        $stack = $php->getStack();
        if (count($stack) == 0 || (count($stack) == 1 && $stack[0] == T_NAMESPACE)) {
            do {
                $import = $this->getNamespace($php);
                if (empty($import)) return;
                $next = $php->moveWhile(array(T_WHITESPACE))
                    ->getToken();
                $alias = substr($import, strrpos($import, "\\")+1);
                if ($next[0] == T_AS) {
                    $alias = $this->getNamespace($php);
                }
                $this->setAlias($alias, $import);
            } while ($php->getToken() == ',');
        } else if ($stack[count($stack)-1] == T_CLASS) {
            // traits
            do {
                $trait = $this->getNamespace($php);
                if (!$this->getLastClass()) {
                    // should never happen, unless the current
                    // file is broken
                    continue;
                }
                $this->getLastClass()->addDependency($this->getClass($trait));
            } while ($php->getToken() == ',');
        }
    }

    public function parseNamespace(PHPTokens $php)
    {
        $namespace = $this->getNamespace($php);
        $this->setNamespace($namespace);
    }

    public function parseAnnotations(PHPTokens $php)
    {
        $comment = $php->getToken();
        $annotations = Notoj::parseDocComment($comment[1]);
        if (empty($annotations)) {
            return;
        }
        $token = $php->move()
            ->moveWhile(array(
                T_WHITESPACE, T_PUBLIC, T_PRIVATE, T_PROTECTED, 
                T_STATIC, T_ABSTRACT, T_FINAL
            ))->getToken();

        $allow = array(T_CLASS, T_INTERFACE, $this->trait);
        if (!in_array($token[0], $allow)) {
            return;
        }

        $className = $php->moveWhileNot(array(T_STRING))
            ->getToken();
        $class = $this->getClass($className[1]);

        foreach ($annotations as $decorator) {
            if (strtolower($decorator['method']) != "autoload") {
                continue;
            }
            foreach ($decorator['args'] as $className) {
                $class->addDependency($this->getClass($className));
            }
        }
    }

    public function getNamespacefile($class, $prefix)
    {
        if ($this->callback_path) {
            $path = call_user_func($this->callback_path, $class, $prefix);
            if ($path) {
                return $path;
            }
        }

        $dir  = preg_replace("/.php$/", "/", $prefix) ;
        $file = 'loader:' . str_replace("\\", ".", $class) . ".php";

        if (!is_dir($dir)) {
            if (!mkdir($dir)) {
                throw new \RuntimeException("Cannot create directory {$dir}");
            }
        }

        return $dir . preg_replace("/(\.+|\.\_)/", ".", $file);
    }

    protected function renderMultiple($output, $namespaces)
    {
        // sort them by length
        uksort($namespaces, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        $prefix = $output;

        $filemap     = array();
        $extraLoader = false;
        $allClasses  = $this->classes;
        $allDeps     = $this->deps;

        foreach (array_keys($namespaces) as $namespace) {
            $classes = array();
            $deps    = array();
            foreach ($allClasses as $class => $file) {
                if (strpos($class, $namespace) !== 0) continue;
                $classes[$class] = $file;
                unset($allClasses[$class]);
                if (!empty($allDeps[$class])) {
                    $deps[$class] = $allDeps[$class];
                }
            }

            if (empty($classes)) continue;

            // If in our dependency tree we reference
            // to another class which handled in another file
            // we *must* duplicate that class definition in o
            // order to make autoloading simpler
            foreach ($deps as $dep) {
                foreach ($dep as $class) {
                    if (empty($classes[$class])) {
                        $classes[$class] = $this->classes[$class];
                    }
                }
            }


            $file = $this->getNamespacefile($namespace, $prefix);
            if ($this->relative) {
                $filemap[$namespace] = Path::getRelative($file, $output);
            } else {
                $filemap[$namespace] = $file;
            }


            $nargs = $this->getTemplateArgs($file, compact('classes', 'deps'));
            $code  = Artifex::load(__DIR__ . "/Template/namespace.loader.tpl.php", $nargs)->run();
            Artifex::save($file, $code);
        }
        
        if (count($allClasses) > 0) {
            $classes = array();
            $deps    = array();
            foreach ($allClasses as $class => $file) {
                $classes[$class] = $file;
                if (!empty($allDeps[$class])) {
                    $deps[$class] = $allDeps[$class];
                }
            }

            foreach ($deps as $dep) {
                foreach ($dep as $class) {
                    if (empty($classes[$class])) {
                        $classes[$class] = $this->classes[$class];
                    }
                }
            }


            $file = $this->getNamespacefile('-all', $prefix);
            if ($this->relative) {
                $filemap['-all'] = Path::getRelative($file, $output);
            } else {
                $filemap['-all'] = $file;
            }

            $nargs  = $this->getTemplateArgs($file, compact('classes', 'deps'));
            $code   = Artifex::load(__DIR__ . "/Template/namespace.loader.tpl.php", $nargs)->run();
            Artifex::save($file, $code);
        }

        $nargs = array_merge($this->getTemplateArgs(), compact('filemap', 'relative', 'extraLoader'));
        $code = Artifex::load(__DIR__ . "/Template/index.tpl.php", $nargs)->run();
        Artifex::save($output, $code);
    }
    
    protected function renderSingle($output)
    {
        $code = Artifex::load(__DIR__ . "/Template/autoloader.tpl.php", $this->getTemplateArgs($output))->run();
        Artifex::save($output, $code);
    }

    protected function checkIfShouldGroup($classes)
    {
        // group the classes in namespaces
        $namespaces = array();
        foreach ($classes as $class => $file) {
            $parts = explode("\\", $class);
            $len   = count($parts)-1;
            $sep   = "\\";

            if ($len == 0) {
                $parts = explode("_", $class);
                $len   = count($parts) - 1;
                $sep   = "_";
            }

            for ($i = 0; $i < $len; $i++) {
                $namespace = implode($sep, array_slice($parts, 0, $i+1)) . $sep;
                if (empty($namespaces[$namespace])) {
                    $namespaces[$namespace] = 0;
                }
                $namespaces[$namespace]++;
            }
        } 

        // return an array with the most common namespaces
        return array_filter($namespaces, function($classes) {
            return $classes >= 10;
        });

    }

    protected function getParser()
    {
        $php  = new \Artifex\Util\PHPTokens;

        $php->on(T_NAMESPACE, array($this, 'parseNamespace'));
        $php->on(T_DOC_COMMENT, array($this, 'parseAnnotations'));
        $php->on(T_USE, array($this, 'parseUse'));
        $php->on(T_CLASS, array($this, 'parseClass'));
        $php->on(T_INTERFACE, array($this, 'parseClass'));
        $php->on($this->trait, array($this, 'parseClass'));

        return $php;
    }

    protected function generateClassDependencyTree()
    {
        $buildDepTree = function($next, $class) use (&$loaded) {
            $deps = array();
            if (isset($loaded[$class . ''])) {
                return array();
            }
            $loaded[$class . ''] = true;
            if (count($class->getDependencies()) > 0) {
                foreach (array_reverse($class->getDependencies()) as $dep){
                    if (!$dep->isLocal()) continue;
                    $deps   = array_merge($deps, $next($next, $dep));
                    $deps[] = strtolower($dep);
                }
            }
            return $deps;
        };

        $types   = array();
        $classes = array();
        $deps    = array();
        foreach ($this->classes_obj as $id => $class) {
            if (!$class->isLocal()) {
                continue;
            }
            $types[$class->getType()] = 1;
            $loaded = array();
            $dep = $buildDepTree($buildDepTree, $class);
            if (count($dep) > 0) {
                $deps[$id] = array_unique($dep);
            }
            $classes[$id] = $class->getFile();
        }

        $this->hasTraits    = is_callable('trait_exists') && !empty($types[$this->trait]);
        $this->hasInterface = !empty($types[T_INTERFACE]);
        $this->classes      = $classes;
        $this->deps         = $deps;
    }

    protected function getTemplateArgs($file = "", $default = array())
    {
        $args  = array(
            'classes', 'relative', 'deps', 'include_psr0', 
            'stats', 'hasTraits', 'hasInterface'
        );

        $return = array();
        foreach ($args as $arg) {
            $return[$arg] = array_key_exists($arg, $default) ? $default[$arg] : $this->$arg;
        }

        if ($this->relative && $file) {
            foreach ($return['classes'] as $class => $fileClass) {
                $return['classes'][$class] = Path::getRelative($fileClass, $file);
            }
        }

        return $return;
    }

    public function generate($output, $cache = '')
    {
        $dir = realpath(dirname($output));
        if (!is_dir($dir)) {
            throw new \RuntimeException(dirname($dir) . " is not a directory");
        }

        if (!is_writable($dir)) {
            throw new \RuntimeException(dirname($dir) . " is not a writable");
        }

        if (file_exists($dir) && !is_dir($dir)) {
            throw new \RuntimeException("{$output} exists but it isn't a file");
        }

        $deps      = array();
        $relatives = array();
        $callback  = $this->callback;


        $this->classes_obj = array();
        if (!$callback) {
            $callback = function() {};
        }

        $parser = $this->getParser();
        $zfiles = array();
        $cached = array();
        $files  = array();
        $hit    = array();

        if ($cache && is_file($cache)) {
            $data = unserialize(file_get_contents($cache));
            if (is_array($data) && count($data) == 2) {
                $zfiles = $data[0];
                $cached = $data[1];
            }
        }

        foreach ($this->path as $file) {
            $path  = $file->getRealPath();
            if (!empty($zfiles[$path]) && filemtime($path) <= $zfiles[$path]) {
                $hit[$path] = 1;
                continue;
            }

            $files[$path] =  filemtime($path);
            $this->reset();
            $this->currentFile = $path;
            try {
                $parser->setFile($path);
                $callback($path, $this->classes_obj);
            } catch (\Exception $e) {
                $callback($path, $this->classes_obj, $e);
            }
        }

        foreach ($cached as $id => $class) {
            if (!empty($hit[$class->getFile()])) {
                $this->classes_obj[$id] = $class;
            }
        }

        if ($cache) {
            $tocache = array(array_merge($zfiles, $files), array_merge($cached, $this->classes_obj));
            file_put_contents($cache, serialize($tocache));
        }

        $this->generateClassDependencyTree();

        if (!$this->singleFile) {
            $namespaces = $this->checkIfShouldGroup($this->classes);
            if (count($namespaces) > 0) {
                return $this->renderMultiple($output, $namespaces);
            }
        }

        return $this->renderSingle($output);
    }
}

