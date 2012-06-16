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
    Notoj\File as FileParser,
    Artifex;

if (!defined('T_TRAIT')) {
    define('T_TRAIT', 0xf0f0c0);
}

class Generator
{
    protected $path;
    protected $stats;
    protected $callback;

    public function __construct($dir = NULL)
    {
        if (!is_null($dir)) {
            $this->setScanPath($dir);
        }
    }

    public function setScanPath($dir) {
        if ($dir instanceof Finder) {
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

    public function getClasses($content, $file, &$classes)
    {
        $tokens    = token_get_all($content); 
        $namespace = "";
        $classMap  = array();
        $allTokens = count($tokens);

        /* for traits */
        $lastClass  = null;

        $readNamespace  = function() use ($tokens, &$i, $file) {
            while ($tokens[$i][0] != T_STRING && $tokens[$i][0] != T_NS_SEPARATOR) {
                if (empty($tokens[$i]) || !is_array($tokens[$i])) {
                    break;
                }
                ++$i;
            }
            if (!is_array($tokens)) {
                return false;
            }
            $parts = array();
            while (true) {
                if (empty($tokens[$i])) {
                    throw new \Exception("Failed at parsing {$file}");
                }
                $token = $tokens[$i];
                if (!is_array($token)) {
                    break;
                }
                if ($token[0] == T_STRING || $token[0] == T_NS_SEPARATOR) {
                    $parts[] = $token[1];
                } else if ($token[0] == T_WHITESPACE) {
                } else {
                    break;
                }
                $i++;
            }

            return $parts;
        };

        $level = 0;
        for ($i=0; $i < $allTokens; $i++) {
            $token = $tokens[$i];
            if (!is_array($token)) {
                switch ($token[0]) {
                case '{':
                    $level++;
                    break;
                case '}':
                    $level--;
                    break;
                }
            }
            switch ($token[0]) {
            case T_USE:
                read_class_alias:
                $parts = $readNamespace();
                if (empty($parts)) {
                    continue;
                }
                $tmpns = implode("", $parts);
                if ($level > 0) {

                    // class maps and namespaces {{{
                    if (isset($classMap[$tmpns])) {
                        $tmpns = $classMap[$tmpns];
                    } else {
                        if ($tmpns[0] != "\\") {
                            $tmpns = $namespace . $tmpns;
                        } else {
                            $tmpns = substr($tmpns, 1);
                        }
                    }
                    // }}}

                    if (!isset($classes[$tmpns])) {
                        $classes[$tmpns] = new classDef($tmpns);
                    }
                    $lastClass->addDependency($classes[$tmpns]);
                } else {
                    $alias = $parts[ count($parts) - 1];
                    while ($tokens[$i][0] == T_WHITESPACE) ++$i;
                    if ($tokens[$i][0] == T_AS) {
                        while ($tokens[$i][0] != T_STRING) ++$i;
                        $alias = $tokens[$i++][1];
                    }
                    $classMap[$alias] = $tmpns;
                }

                if ($tokens[$i] == ",") {
                    goto read_class_alias;
                }

                break;
            case T_INTERFACE:
            case T_CLASS:
            case T_TRAIT:
                $type = $token;
                while ($tokens[++$i][0] != T_STRING);
                $className = $namespace . $tokens[$i][1];
                if (!isset($classes[$className])) {
                    $classes[$className] = new classDef($className);
                }
                $class = $classes[$className];
                $class->setFile($file);
                $class->isLocal(true);
                $class->setType($type[0]);
                while ($tokens[$i] != "{") {
                    switch ($tokens[$i][0]) {
                    case T_EXTENDS:
                    case T_IMPLEMENTS:
                        read_class_name:
                        $parentClass = implode("", $readNamespace());

                        // class maps and namespaces {{{
                        if (isset($classMap[$parentClass])) {
                            $parentClass = $classMap[$parentClass];
                        } else {
                            if ($parentClass[0] != "\\") {
                                $parentClass = $namespace . $parentClass;
                            } else {
                                $parentClass = substr($parentClass, 1);
                            }
                        }
                        // }}}

                        if (!isset($classes[$parentClass])) {
                            $classes[$parentClass] = new classDef($parentClass);
                        }
                        $class->addDependency($classes[$parentClass]);
                        while ($tokens[$i][0] == T_WHITESPACE) ++$i;
                        if ($tokens[$i] == ',') {
                            $i++;
                            goto read_class_name;
                        }
                        break;
                    default:
                        $i++;
                    }
                }
                $lastClass = $class;
                $level++;
                break;
            case T_NAMESPACE:
                $nsparts   = $readNamespace();
                $namespace = count($nsparts) ? implode("", $nsparts) . "\\" : "";
                break;
            }
        }

        $parser = new FileParser($file);
        foreach ($parser->getAnnotations() as $annotation) {
            if (!isset($classes[$annotation['class']])) {
                throw new \RuntimeException("Missing class {$annotation['class']}");
            }
            $class = $classes[$annotation['class']];
            foreach ($annotation['annotations'] as $decorator) {
                if (strtolower($decorator['method']) != "autoload") {
                    continue;
                }
                foreach ($decorator['args'] as $parentClass) {
                    if (isset($classMap[$parentClass])) {
                        $parentClass = $classMap[$parentClass];
                    } else {
                        if ($parentClass[0] != "\\") {
                            $parentClass = $namespace . $parentClass;
                        } else {
                            $parentClass = substr($parentClass, 1);
                        }
                    }
                    if (!isset($classes[$parentClass])) {
                        $classes[$parentClass] = new classDef($parentClass);
                    }
                    $class->addDependency($classes[$parentClass]);
                }
            }
        }

        return $classes;
    }

    public function setStepCallback(\Closure $cbc)
    {
        $this->callback = $cbc;
    }

    public function getRelativePath($dir1, $dir2=NULL)
    {
        if (empty($dir2)) {
            $dir2 = getcwd();
        }
        $dir1 = trim(realpath($dir1),'/');
        $dir2 = trim(realpath($dir2),'/');
        $to   = explode('/', $dir1);
        $from = explode('/', $dir2);

        $realPath = $to;

        foreach ($from as $depth => $dir) {
            if(isset($to[$depth]) && $dir === $to[$depth]) {
                array_shift($realPath);
            } else {
                $remaining = count($from) - $depth;
                if($remaining) {
                    // add traversals up to first matching dir
                    $padLength = (count($realPath) + $remaining) * -1;
                    $realPath  = array_pad($realPath, $padLength, '..');
                    break;
                }
            }
        }

        return implode("/", $realPath);
    }

    public function enableStats($name)
    {
        $this->stats = $name;
    }

    public function generate($output, $relative = false, $include_psr0 = true)
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

        $zclasses  = array();
        $deps      = array();
        $relatives = array();
        $callback  = $this->callback;
        foreach ($this->path as $file) {
            $path  = $file->getRealPath();
            $rpath = $path;
            if ($relative) {
                $rpath = $this->getRelativePath(dirname($path), dirname($output)) . '/' . basename($path);
                if ($rpath[0] != '/') {
                    $rpath = "/" . $rpath;
                }
            }
            try {
                if ($callback) {
                    $callback($path, $zclasses);
                }
                $this->getClasses(file_get_contents($path), $rpath, $zclasses);
            } Catch (\Exception $e) { }
        }

        $buildDepTree = function($next, $class) {
            $deps = array();
            if (count($class->getDependencies()) > 0) {
                foreach (array_reverse($class->getDependencies()) as $dep){
                    if (!$dep->isLocal()) continue;
                    $deps   = array_merge($deps, $next($next, $dep));
                    $deps[] = strtolower($dep);
                }
            }
            return $deps;
        };

        $types = array();
        foreach ($zclasses as $id => $class) {
            if (!$class->isLocal()) {
                continue;
            }
            $types[$class->getType()] = 1;
            $dep = $buildDepTree($buildDepTree, $class);
            if (count($dep) > 0) {
                $deps[strtolower($class)] = array_unique($dep);
            }
            $classes[strtolower($class)] = $class->getFile();
        }

        $hasTraits    = is_callable('trait_exists') && !empty($types[T_TRAIT]);
        $hasInterface = !empty($types[T_INTERFACE]);
        $tpl   = file_get_contents(__DIR__ . "/autoloader.tpl.php");
        $stats = $this->stats;
        $args  = compact(
            'classes', 'relative', 'deps', 'include_psr0', 
            'stats', 'hasTraits', 'hasInterface'
        );
        $code  = Artifex::execute($tpl, $args);
        Artifex::save($output, $code);
    }
}

