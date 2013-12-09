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

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Finder\Finder;
 
class CliApp
{
    protected function getPath($file)
    {
        if ($file[0] !== '/') {
            $file = getcwd() . '/' . $file;
        }
        return $file;
    }

    protected function getFinderObject($isLibrary, $dirs)
    {
        foreach ($dirs as $id => $dir) {
            $dirs[$id] = $this->getPath($dir);
        }

        $finder = new Finder();
        $finder->files()
            ->name("*.php")
            ->in($dirs);

        if ($isLibrary) {
            $relative = true;
            $include  = false;
            $finder->filter(function($file) {
                return preg_match("/test/i", $file) ? false : true;
            });
        }

        return $finder;
    }

    /**
     *  @Cli("generate", "Generate autoloader")
     *  @Option('library')
     *  @Option('relative')
     *  @Option('multi')
     *  @Option('enable-cache')
     *  @Option('include-psr-0', default=true)
     *  @Arg('output', REQUIRED)
     *  @Arg('dir', REQUIRED|IS_ARRAY)
     */
    public function generate(InputInterface $input, OutputInterface $output)
    {
        $dirs = array();
        $file = $input->getArgument('output');
        foreach ($input->getArgument('dir') as $dir) {
            $dirs[] = $this->getPath($dir);
        }

        $file = $this->getPath($input->getArgument('output'));
        $finder = $this->getFinderObject(
            $input->getOption('library'),
            $input->getArgument('dir')
        );

        $relative = $input->getOption('relative');
        $include  = $input->getOption('include-psr-0');
        $cache    = $input->getOption('enable-cache');
        $multi    = $input->getOption('multi');

        try {
            $generator = new Generator($finder);
            $generator->setStepCallback(function($file, $classes, $error = false) use ($output) {
                if ($error) {
                    $output->write("<error>failed: $file</error>\n");
                } else {
                    $output->write("<comment>scanning {$file}</comment>\n");
                }
            });
            $generator->relativePaths($relative);
            $generator->includePSR0Autoloader($include);
            if ($multi) {
                $generator->multipleFiles();
            } else {
                $generator->singleFile();
            }
            $generator->generate($file, $cache ? $file . '.cache' : NULL);
        } Catch(\exception $e) {
            $output->write("<error>Fatal error, stopping generator</error>\n");
        }
        $output->write("<info>{$file} was generated</info>\n");
    }
}

