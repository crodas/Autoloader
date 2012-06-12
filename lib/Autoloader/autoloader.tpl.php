<?php

spl_autoload_register(function ($class) {
    #* $classes = @$classes;
    /*
        This array has a map of (class => file)
    */
    static $classes = __classes__;

    if (isset($classes[$class])) {
        #* if ($relative)
        require __DIR__  . '/' . $classes[$class];
        #* else
        require $classes[$class];
        #* end
        return true;
    }

    /**
     * Autoloader that implements the PSR-0 spec for interoperability between
     * PHP software.
     *
     * kudos to @alganet for this autoloader script.
     * borrowed from https://github.com/Respect/Validation/blob/develop/tests/bootstrap.php
     */
    $fileParts = explode('\\', ltrim($class, '\\'));
    if (false !== strpos(end($fileParts), '_')) {
        array_splice($fileParts, -1, 1, explode('_', current($fileParts)));
    }
    $file = implode(DIRECTORY_SEPARATOR, $fileParts) . '.php';
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
        if (file_exists($path = $path . DIRECTORY_SEPARATOR . $file)) {
            return require $path;
        }
    }

}, true, true);
