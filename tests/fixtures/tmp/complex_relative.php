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
    static $classes = array (
  'autoloader\\test\\complex\\xxxfoobar_rel' => 
  array (
    0 => '/../complex_relative/anotherInterface.php',
    1 => 'interface_exists',
  ),
  'simple_rel' => 
  array (
    0 => '/../complex_relative/Bar.php',
    1 => 'class_exists',
  ),
  'autoloader\\test\\complex\\complex_rel' => 
  array (
    0 => '/../complex_relative/Foo.php',
    1 => 'class_exists',
  ),
  'foointerface_rel' => 
  array (
    0 => '/../complex_relative/interface.php',
    1 => 'interface_exists',
  ),
  'barinterface_rel' => 
  array (
    0 => '/../complex_relative/interface1.php',
    1 => 'interface_exists',
  ),
  'xxxinterface_rel' => 
  array (
    0 => '/../complex_relative/interface2.php',
    1 => 'interface_exists',
  ),
);

    static $deps    = array (
  'autoloader\\test\\complex\\complex_rel' => 
  array (
    0 => 'autoloader\\test\\complex\\xxxfoobar_rel',
    1 => 'xxxinterface_rel',
    2 => 'barinterface_rel',
    3 => 'foointerface_rel',
    4 => 'simple_rel',
  ),
  'foointerface_rel' => 
  array (
    0 => 'xxxinterface_rel',
    1 => 'barinterface_rel',
  ),
  'barinterface_rel' => 
  array (
    0 => 'xxxinterface_rel',
  ),
);

$class = strtolower($class);
if (isset($classes[$class])) {
        $GLOBALS['call_complex_relativestat']++;
    if (!empty($deps[$class])) {
        foreach ($deps[$class] as $zclass) {
if (
    ! $classes[$zclass][1]( $zclass, false )
) {
    $GLOBALS['load_complex_relativestat']++;
    require __DIR__  . $classes[$zclass][0];
}
        }
    }
if (
    ! $classes[$class][1]( $class, false )
) {
    $GLOBALS['load_complex_relativestat']++;
    require __DIR__  . $classes[$class][0];
}
    return true;
}

    /**
     * Autoloader that implements the PSR-0 spec for interoperability between
     * PHP software.
     *
     * kudos to@alganet for this autoloader script.
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
} 
);

        require_once __DIR__ . '/../../generatorTest.php';

function getcomplex_relativestat() {
    global $load_complex_relativestat, $call_complex_relativestat;
    return array('loaded' => $load_complex_relativestat, 'calls' => $call_complex_relativestat);
}
