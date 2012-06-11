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
    Artifex;

require __DIR__ . "/../../vendor/autoload.php";

class Generator
{
    protected $path;

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

    public function getClasses($file)
    {
        if (!is_readable($file)) {
            throw new \RuntimeException("{$file} is not readable");
        }
        $content   = file_get_contents($file);
        $tokens    = token_get_all($content); 
        $classes   = array();
        $namespace = "";
        $allTokens = count($tokens);
        for ($i=0; $i < $allTokens; $i++) {
            $token = $tokens[$i];
            if (!is_array($token)) continue;
            switch ($token[0]) {
            case T_INTERFACE:
            case T_CLASS:
                while ($tokens[++$i][0] != T_STRING);
                $classes[] = $namespace . $tokens[$i][1];
                break;
            case T_NAMESPACE:
                while ($tokens[++$i][0] != T_STRING);
                $parts = array();
                while (true) {
                    $token = $tokens[$i++];
                    if (!is_array($token) || ($token[0] != T_STRING && $token[0] != T_NS_SEPARATOR)) {
                        break;
                    }
                    if ($token[0] == T_STRING) {
                        $parts[] = $token[1];
                    }
                }
                
                $namespace = implode("\\", $parts) . "\\";
                break;
            }
        }
        return $classes;
    }


    public function generate($output)
    {
        $dir = dirname($output);
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \RuntimeException("{$dir} is not writable");
        }

        $classes   = array();
        foreach ($this->path as $file) {
            $path = $file->getRealPath();
            foreach ($this->getClasses($path) as $class) {
                $classes[$class] = $path;
            }
        }
        $tpl  = file_get_contents(__DIR__ . "/autoloader.tpl.php");
        $code = Artifex::execute($tpl, compact('classes'));
        Artifex::save($output, $code);
    }
}

