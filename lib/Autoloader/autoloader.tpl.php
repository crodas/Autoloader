<?php
#* if ($stats)
$GLOBALS['call___stats__'] = 0;
$GLOBALS['load___stats__'] = 0;
#* end

spl_autoload_register(function ($class) {
    #* $classes = @$classes;
    #* $deps    = @$deps;
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = __classes__;
    // }}}

    // deps {{{
    static $deps    = __deps__;
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        #* if ($stats) 
        $GLOBALS['call___stats__']++;
        $GLOBALS['load___stats__']++;
        #* end
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false)) {
                    #* if ($stats) 
                    $GLOBALS['load___stats__']++;
                    #* end
                    #* if ($relative)
                    require __DIR__  . $classes[$zclass];
                    #* else
                    require $classes[$zclass];
                    #* end
                }
            }
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            #* if ($relative)
            require __DIR__  . $classes[$class];
            #* else
            require $classes[$class];
            #* end
        }
        return true;
    }

    #* if ($include_psr0)
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
    #* end
    return false;
} #* if (!$relative)
, true, true #* end 
);


#* if ($stats)
function get__stats__() {
    global $load___stats__, $call___stats__;
    return array('loaded' => $load___stats__, 'calls' => $call___stats__);
}
#* end
