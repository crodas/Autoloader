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
    Symfony\Component\Finder\Finder,
    Notoj\ReflectionMethod;

class CliApp
{
    public function __construct(Application $app)
    {
        $self = $this;
        foreach(get_class_methods($this) as $method) {
            if ($method == __FUNCTION__) continue;
            $conf = $this->getAnnotations($method);
            if (!array_key_exists('cli', $conf)) continue;
            $command = $app->register($method)
                ->setDescription($conf['help'][0])
                ->setCode(function(InputInterface $input, OutputInterface $output) use ($method, $self) {
                    $self->$method($input, $output);
                });
            if (!empty($conf['arg'])) {
                $args = array();
                foreach((array)$conf['arg'] as $arg) {
                    $opts = InputArgument::REQUIRED;
                    if (!empty($arg['array'])) {
                        $opts |= InputArgument::IS_ARRAY;
                    }
                    $args[] = new InputArgument($arg['name'], $opts , $arg['help']);
                }
                $command->setDefinition($args);
            }
            if (!empty($conf['opt'])) {
                $command->addOption($conf['opt']['name'], $conf['opt']['default']);
            }
        }
    }

    protected function getAnnotations($method)
    {
        $reflection  = new ReflectionMethod($this, $method);
        $annotations = array();
        $compounds   = array();
        foreach($reflection->getAnnotations() as $method) {
            $name = $method['method'];
            if (isset($annotations[$name])) {
                if (empty($compounds[$name])) {
                    $compounds[$name]   = TRUE;
                    $annotations[$name] = array($annotations[$name]);
                }
                $annotations[$name][] = $method['args'];
                continue;
            }
            $annotations[$name] = $method['args'];
        }
        return $annotations;
    }

    /**
     *  @cli
     *  @help Generate autoloader
     *  @opt(name='relative', default=false)
     *  @arg(name='output', help="Output autoloader")
     *  @arg(name='dir', help="Directory to scan",array=true)
     */
    public function generate(InputInterface $input, OutputInterface $output)
    {
        $file   = $input->getArgument('output');
        $finder = new Finder();
        $finder->files()
            ->name("*.php")
            ->in($input->getArgument('dir'));

        $generator = new Generator($finder);
        $generator->generate($file, $input->getOption('relative'));
        $output->write("<info>{$file} was generated</info>\n");
    }

    /**
     *  @cli
     *  @help Create a phar file for the autoloader generator
     */
    public function createPhar(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $dir    = dirname($_SERVER['PHP_SELF']);
        $finder->files()
            ->name('*.php')
            ->in($dir . '/lib')
            ->in($dir . '/vendor');

        $phar = new \Phar('autoloader.phar');
        foreach ($finder as $file) {
            $phar->addFile($file->getRealPath());
        }

        $phar->setStub("#!/usr/bin/env php\n"
            . $phar->createDefaultStub('index.php')
        );
        $phar->addFile($_SERVER["PHP_SELF"], 'index.php');
        chmod('autoloader.phar', 0755);
    }
}
