<?php
$GLOBALS['call_complexstat'] = 0;
$GLOBALS['load_complexstat'] = 0;

spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'autoloader\\test\\complex\\xxxfoobar' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex/anotherInterface.php',
  'barinterface' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex/interface1.php',
  'xxxinterface' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex/interface2.php',
  'simple' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex/Bar.php',
  'autoloader\\test\\complex\\complex' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex/Foo.php',
  'foointerface' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex/interface.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
  'barinterface' => 
  array (
    0 => 'xxxinterface',
  ),
  'autoloader\\test\\complex\\complex' => 
  array (
    0 => 'autoloader\\test\\complex\\xxxfoobar',
    1 => 'xxxinterface',
    2 => 'barinterface',
    3 => 'foointerface',
    4 => 'simple',
  ),
  'foointerface' => 
  array (
    0 => 'xxxinterface',
    1 => 'barinterface',
  ),
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $GLOBALS['call_complexstat']++;
        $GLOBALS['load_complexstat']++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false)) {
                    $GLOBALS['load_complexstat']++;
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


function getcomplexstat() {
    global $load_complexstat, $call_complexstat;
    return array('loaded' => $load_complexstat, 'calls' => $call_complexstat);
}
