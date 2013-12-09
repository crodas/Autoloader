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
                    if (empty($classes[$class[0]])) {
                        $classes[$class[0]] = $this->classes[$class[0]];
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
                    if (empty($classes[$class[0]])) {
                        $classes[$class[0]] = $this->classes[$class[0]];
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
            return $classes >= 5;
        });

    }

    protected function getParser()
    {
        return new \crodas\ClassInfo\ClassInfo;
    }

    protected function generateClassDependencyTree()
    {
        $buildDepTree = function($next, $class) use (&$loaded) {
            $deps = array();
            if (isset($loaded[$class->getName()])) {
                return array();
            }
            $loaded[$class->getName()] = true;
            $zdeps = array_merge($class->getInterfaces(), $class->getTraits());
            if ($parent = $class->getParent()) {
                $zdeps  = array_merge(array($class->getParent()), $zdeps);
            }
            if (count($zdeps) > 0) {
                foreach (array_reverse($zdeps) as $dep){
                    if (!$dep->isUserDefined()) continue;
                    $deps   = array_merge($deps, $next($next, $dep));
                    $deps[] = serialize(array(strtolower($dep->getName()), $dep->getType() . '_exists'));
                }
            }
            return $deps;
        };

        $types   = array();
        $classes = array();
        $deps    = array();
        foreach ($this->classes_obj as $id => $class) {
            if (!$class->isUserDefined()) {
                continue;
            }
            $types[$class->getType()] = 1;
            $loaded = array();
            $dep = $buildDepTree($buildDepTree, $class);
            if (count($dep) > 0) {
                $deps[$id] = array_unique($dep);
                $deps[$id] = array_map('unserialize', $deps[$id]);
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
            $this->currentFile = $path;
            if (!preg_match('/\s(class|interface|trait)\s/ismU', file_get_contents($path))) {
                /* no classes */
                continue;
            }
            try {
                $parser->parse($path);
                $callback($path, $parser->getClasses());
            } catch (\Exception $e) {
                $callback($path, $parser->getClasses(), $e);
            }
        }

        $this->classes_obj = $parser->getClasses();
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

