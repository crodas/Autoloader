<?php
/**
 *  Autoloader function generated by crodas/Autoloader
 *
 *  https://github.com/crodas/Autoloader
 *
 *  This is a generated file, do not modify it.
 */

$GLOBALS['call_caseInsentivestat'] = 0;
$GLOBALS['load_caseInsentivestat'] = 0;

spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'autoloader\\test\\caseinsentive\\bar' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/caseInsentive/Bar.php',
  'autoloader\\test\\caseinsentive\\foo' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/caseInsentive/Foo.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $GLOBALS['call_caseInsentivestat']++;
        $GLOBALS['load_caseInsentivestat']++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false) && !trait_exists($zclass, false) ) {
                    $GLOBALS['load_caseInsentivestat']++;
                    require $classes[$zclass];
                }
            }
        }

        if (!class_exists($class, false) && !interface_exists($class, false) && !trait_exists($class, false) ) {
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


function getcaseInsentivestat() {
    global $load_caseInsentivestat, $call_caseInsentivestat;
    return array('loaded' => $load_caseInsentivestat, 'calls' => $call_caseInsentivestat);
}
