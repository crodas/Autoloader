<?php
/**
 *  Autoloader function generated by crodas/Autoloader
 *
 *  https://github.com/crodas/Autoloader
 *
 *  This is a generated file, do not modify it.
 */

$GLOBALS['call_complex_annotationsstat'] = 0;
$GLOBALS['load_complex_annotationsstat'] = 0;



spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'autoloader\\test\\complex\\xxxfoobar_ann' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex_annotations/anotherInterface.php',
  'barinterface_ann' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex_annotations/interface1.php',
  'xxxinterface_ann' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex_annotations/interface2.php',
  'simple_ann' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex_annotations/Bar.php',
  'autoloader\\test\\complex\\complex_ann' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex_annotations/Foo.php',
  'foointerface_ann' => '/home/crodas/projects/mongolico/Autoloader/tests/fixtures/complex_annotations/interface.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
  'barinterface_ann' => 
  array (
    0 => 'xxxinterface_ann',
  ),
  'autoloader\\test\\complex\\complex_ann' => 
  array (
    0 => 'autoloader\\test\\complex\\xxxfoobar_ann',
    1 => 'xxxinterface_ann',
    2 => 'barinterface_ann',
    3 => 'foointerface_ann',
    4 => 'simple_ann',
  ),
  'foointerface_ann' => 
  array (
    0 => 'xxxinterface_ann',
    1 => 'barinterface_ann',
  ),
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $GLOBALS['call_complex_annotationsstat']++;
        $GLOBALS['load_complex_annotationsstat']++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false)) {
                    $GLOBALS['load_complex_annotationsstat']++;
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
    $file = stream_resolve_include_path(implode(DIRECTORY_SEPARATOR, $fileParts) . '.php');
    if ($file) {
        return require $file;
    }
    return false;
}, true, true);


function getcomplex_annotationsstat() {
    global $load_complex_annotationsstat, $call_complex_annotationsstat;
    return array('loaded' => $load_complex_annotationsstat, 'calls' => $call_complex_annotationsstat);
}