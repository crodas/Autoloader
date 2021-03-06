<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2013 César Rodas                                                  |
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

use Symfony\Component\Finder\Finder;

require getcwd() . '/vendor/autoload.php';

class Composer
{
    protected static function getFilesToInclude($dir)
    {
        $files = array();
        foreach (glob("$dir/*/*/composer.json") as $json) {
            $content = json_decode(file_get_contents($json), true);
            if (!empty($content['autoload']) && !empty($content['autoload']['files'])) {
                $files[] = array_map(function($file) use ($json) {
                    return dirname($json) . '/' . $file;
                }, (array)$content['autoload']['files']);
            }
        }

        return call_user_func_array('array_merge', $files);
    }

    public static function generate()
    {
        $dir  = getcwd();
        $file = "$dir/vendor/autoload.php"; 

        $include = self::getFilesToinclude($dir . "/vendor/");

        $finder = new Finder();
        $finder->files()
            ->name("*.php")
            ->in($dir);

        $generator = new Generator($finder);
        $generator->relativePaths(true)
            ->includeFiles($include)
            ->includePSR0Autoloader(false)
            ->multipleFiles()
            ->generate($file, "$file.cache");

        return true;
    }
}
