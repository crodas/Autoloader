<?php
/**
 *  Autoloader function generated by crodas/Autoloader
 *
 *  https://github.com/crodas/Autoloader
 *
 *  This is a generated file, do not modify it.
 */

$GLOBALS['call_complex_relativestat'] = 0;
$GLOBALS['load_complex_relativestat'] = 0;

spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'foointerface_rel' => '/../complex_relative/interface.php',
  'barinterface_rel' => '/../complex_relative/interface1.php',
  'xxxinterface_rel' => '/../complex_relative/interface2.php',
  'autoloader\\test\\complex\\xxxfoobar_rel' => '/../complex_relative/anotherInterface.php',
  'simple_rel' => '/../complex_relative/Bar.php',
  'autoloader\\test\\complex\\complex_rel' => '/../complex_relative/Foo.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
  'foointerface_rel' => 
  array (
    0 => 'xxxinterface_rel',
    1 => 'barinterface_rel',
  ),
  'barinterface_rel' => 
  array (
    0 => 'xxxinterface_rel',
  ),
  'autoloader\\test\\complex\\complex_rel' => 
  array (
    0 => 'autoloader\\test\\complex\\xxxfoobar_rel',
    1 => 'xxxinterface_rel',
    2 => 'barinterface_rel',
    3 => 'foointerface_rel',
    4 => 'simple_rel',
  ),
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $GLOBALS['call_complex_relativestat']++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false)) {
                    $GLOBALS['load_complex_relativestat']++;
                    require __DIR__  . $classes[$zclass];
                }
            }
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {

            $GLOBALS['load_complex_relativestat']++;

            require __DIR__  . $classes[$class];

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
    $file = stream_resolve_include_path(implode(DIRECTORY_SEPARATOR, $fileParts) . '.php');
    if ($file) {
        return require $file;
    }
    return false;
});


function getcomplex_relativestat() {
    global $load_complex_relativestat, $call_complex_relativestat;
    return array('loaded' => $load_complex_relativestat, 'calls' => $call_complex_relativestat);
}
