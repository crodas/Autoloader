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
 
/**
 *  @autoloader("\Notoj\ReflectionMethod", "Generator")
 */
class CliApp extends \stdClass
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
                foreach ($conf['opt'] as $opt) {
                    if (array_key_exists('default', $opt)) {
                        $command->addOption($opt['name'], null, InputOption::VALUE_OPTIONAL, $opt['help'], $opt['default']);
                    } else {
                        $command->addOption($opt['name'], null, InputOption::VALUE_NONE, $opt['help']);
                    }
                }
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
     *  @help Replace composer autoloader
     */
    public function composer(InputInterface $input, OutputInterface $output)
    {
        $dir  = ".";
        $file = getcwd() . "/vendor/autoload.php"; 

        $finder = new Finder();
        $finder->files()
            ->name("*.php")
            ->in($dir);

        try {
            $generator = new Generator($finder);
            $generator->relativePaths(true)
                ->includePSR0Autoloader(false)
                ->multipleFiles()
                ->generate($file, "$file.cache");
        } catch (\Exception $e) {
            $output->write("<error>Fatal error, stopping generator</error>\n");
            exit(-1);
        }
        exit(0);
    }

    /**
     *  @cli
     *  @help Generate autoloader
     *  @opt(name='library', help="Generate the autoloader for a library (portability frendly)")
     *  @opt(name='relative', help="Save as relative path")
     *  @opt(name='multi', help="Split the autoloader in multiple files")
     *  @opt(name='enable-cache', help="Create a cache file to speed-up re-generation")
     *  @opt(name='include-psr-0', default=true, help="Include the PSR-0 autoloader")
     *  @arg(name='output', help="Output autoloader")
     *  @arg(name='dir', help="Directory to scan",array=true)
     */
    public function generate(InputInterface $input, OutputInterface $output)
    {
        $dirs = array();
        $file = $input->getArgument('output');
        foreach ($input->getArgument('dir') as $dir) {
            if ($dir[0] !== '/') {
                $dir = getcwd() . '/' . $dir;
            }
            $dirs[] = $dir;
        }

        if ($file[0] !== '/') {
            $file = getcwd() . '/' . $file;
        }

        $finder = new Finder();
        $finder->files()
            ->name("*.php")
            ->in($dirs);

        $relative = $input->getOption('relative');
        $include  = $input->getOption('include-psr-0');
        $cache    = $input->getOption('enable-cache');
        $multi    = $input->getOption('multi');

        if ($input->getOption('library')) {
            $relative = true;
            $include  = false;
            $finder->filter(function($file) {
                return preg_match("/test/i", $file) ? false : true;
            });
        }

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

