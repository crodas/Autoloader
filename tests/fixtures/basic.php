<?php
$GLOBALS['call_basicstat'] = 0;
$GLOBALS['load_basicstat'] = 0;

spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'autoloader\\test\\simple\\bar' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/basic/Bar.php',
  'autoloader\\test\\simple\\foo' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/basic/Foo.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $GLOBALS['call_basicstat']++;
        $GLOBALS['load_basicstat']++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false)) {
                    $GLOBALS['load_basicstat']++;
                    require $classes[$zclass];
                }
            }
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            require $classes[$class];
        }
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
    return false;
}, true, true);


function getbasicstat() {
    global $load_basicstat, $call_basicstat;
    return array('loaded' => $load_basicstat, 'calls' => $call_basicstat);
}
