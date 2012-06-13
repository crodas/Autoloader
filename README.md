Autoloader
==========

Autoloader generator for PHP.

Why?
----

Autoloaders are cool, but every project comes with their own autoloader. Your project ends up having more autoloader than proper code.

I'm a pragmatic developer, and I don't believe that's fair that we spend in our production environment, where files barely changes comparing to the number of executions, executing autoloaders over and over.

So as a proof of concept I started this project, that aims to generate an efficient autoloader. The generated autoloader has an array of classes and files. As an extra gift, the generated code will have a generic PSR-0 autoloader (borrowed from @alganet).

The autoloader now generates also loads all the class dependencies (parent classes, interfaces) in order to reduce the number of calls to the autoloaders.

How it works
------------

It was designed to be integrated in your deployment scripts. 
```php
require "lib/Autoloader/Generator.php";

$generator = new \Autoloader\Generator("vendors/");
$generator->generate("autoloader.php");
```

Or if you know what you're doing, you can use an instance of `Finder`.

```php
require "lib/Autoloader/Generator.php";
$finder = \Symfony\Component\Finder\Finder();
$finder->files()->name('*.php')->in("vendors/");

$generator = new \Autoloader\Generator($finder);
$generator->generate("autoloader.php");
```

How to install (for developers)
----------------------------

In order to install you should use `compose`.

```
php composer.phar install
```

How to install (final users)
----------------------------

If you're a final user you should only care about the `autoloader.phar` file.

```
wget https://github.com/crodas/Autoloader/blob/master/autoloader.phar
chmod +x autoloader.phar
```
The `cli` app is very simple to use

```
./autoloader generate autoloader.php dir1/ dir2/
```

By default it will generate absolute paths, which is amazing for performance but you loose portability. If you're writing a library, then you could use --library which generates paths relative to the generated autoloader location.

```
./autoloader generate  --library autoloader.php dir1/ dir2/
```

The output looks like this: https://github.com/crodas/Autoloader/blob/master/lib/Autoloader/loader.php
