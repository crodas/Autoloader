Autoloader [![Build Status](https://secure.travis-ci.org/crodas/Autoloader.png?branch=master)](http://travis-ci.org/crodas/Autoloader) 
===============

Autoloader generator for PHP.

Why?
----

Autoloaders are cool, but every project comes with their own autoloader. Your project ends up having more autoloader than proper code.

I'm a pragmatic developer, and I don't believe that's fair that we spend in our production environment, where files barely changes comparing to the number of executions, executing autoloaders over and over.

So as a proof of concept I started this project, that aims to generate an efficient autoloader. The generated autoloader has an array of classes and files. As an extra gift, the generated code will have a generic PSR-0 autoloader (borrowed from @alganet).

The autoloader now generates also loads all the class dependencies (parent classes, interfaces) in order to reduce the number of calls to the autoloaders.

Some features
-------------

* No class to path transformation is done at run time. `Autoloader` creates a map of files and classes in a given directory.
* When a class is loaded, all its dependencies are loaded (parent classes, interfaces). The goal is to reduce the number of calls to the autloader.
* Always generate code that works with absolute paths (is `--library` is set, `__DIR__` is being used instead)
* Includes (except with the `--library`) a generic PSR-0 autoloader.

How it works
------------

It was designed to be integrated in your deployment scripts. 
```php
require "lib/Autoloader/loader.php";

$generator = new \Autoloader\Generator("vendors/");
$generator->generate("autoloader.php");
```

Or if you know what you're doing, you can use an instance of `Finder`.

```php
require "lib/Autoloader/loader.php";
$finder = \Symfony\Component\Finder\Finder();
$finder->files()->name('*.php')->in("vendors/");

$generator = new \Autoloader\Generator($finder);
$generator->generate("autoloader.php");
```

How to install (for developers)
----------------------------

In order to install you should use `composer`.

```
php composer.phar install
```

Using with composer
-------------------

[Composer](http://getcomposer.org) is a great dependency manager, however I believe there is a lot of room for optimization in terms of autoloader generation. If you wish to have a better autoloader, one that can scan all your dependencies and your project (Whether they have configure the `psr-0` or not). It will also add your local clases to the autoloader.

```json
{
  "require": {
    "crodas/autoloader":"*"
  },
  "minimum-stability": "dev",
  "scripts": {
    "post-autoload-dump": "Autoloader\\Composer::generate"
  }
}
```

By overriding the `post-autoload-dump` it will replace the generated autoloader file, it will be called automatically however if you want to re-run it (for instance when you add a new class in your project and wish to be autoloaded) just run ` composer dump-autoload`.
