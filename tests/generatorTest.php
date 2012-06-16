<?php

class generatorTest extends \phpunit_framework_testcase
{
    public static function provider() {
        $tests =  array(
            array('basic', array('simple\Foo', 'simple\Bar')),
            array('caseInsentive', array('CaseInsentive\Foo', 'CaseInsentive\Bar')),
            array('complex', array('complex\Complex')),
            array('complex_relative', array('complex\Complex_rel')),
            array('namespaces', array('\yet_another_silly_class')),
        );
        if (PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION >= 4) {
            $tests[] = array('traits', array('complex\Complex_Traits'), array('traits.php'));
        }
        return $tests;
    }

    /**
     *  @dataProvider provider
     */
    public function testBasicGeneration($targetName, $classes, $load = array())
    {
        $target = __DIR__ . '/fixtures/' . $targetName . '.php';
        $relative = strpos($target, 'relative') > 0;
        if ($relative) {
            $target = __DIR__ . '/fixtures/tmp/' . $targetName . '.php';
        }
        if (is_file($target)) {
            unlink($target);
        }
        $this->assertFalse(is_file($target));

        $generator = new Autoloader\Generator(__DIR__ . '/fixtures/' . $targetName);
        $generator->enableStats($targetName . 'stat');
        $generator->setStepCallback(function($callback) {
        });
        $generator->generate($target, $relative);

        foreach ($classes as $class) {
            $class = $class[0] == '\\' ? $class : '\\autoloader\\test\\' . $class;
            $this->assertFalse(class_exists($class, false));
        }

        require $target;

        foreach ($load as $file) {
            require __DIR__ . '/fixtures/' . $targetName . '/' . $file;
        }

        foreach ($classes as $class) {
            $class = $class[0] == '\\' ? $class : '\\autoloader\\test\\' . $class;
            $this->assertTrue(class_exists($class));
        }
    }

    public function testInterfaces() {
        $dir  = __DIR__ . '/fixtures/';
        $complex = file_get_contents("{$dir}complex.php");
        $basic   = file_get_contents("{$dir}basic.php");
        $this->assertTrue(strpos($complex, 'interface_exists') > 0);
        $this->assertFalse(strpos($basic, 'interface_exists'));
    }

    /**
     *  @dataProvider provider
     */
    public function testStats($class) {
        $name = 'get' . $class . 'stat';
        $result = $name();
        $this->assertGreaterThanOrEqual(1, $result['calls']);
        $this->assertGreaterThanOrEqual($result['calls'], $result['loaded']);
    }

    /**
     *  @depends testBasicGeneration
     */
    public function testComplexLoading() {
        $output = getComplexStat();  
        $this->assertEquals($output['calls'], 1);
        $this->assertEquals($output['loaded'], 6);
        if (is_callable('getTraitsStat')) {
            $output = getTraitsStat();  
            $this->assertEquals($output['loaded'], 7);
            $this->assertEquals($output['calls'], 1);
        }
    }

    /**
     *  @expectedException \RuntimeException
     */ 
    public function testInvalidDirectory() {
        $path = __DIR__ . '/fixtures/invalid-path';
        $generator = new Autoloader\Generator($path);
    }

    public function testUnreadableDirectory() {
        $path = __DIR__ . '/fixtures/invalid-path';
        mkdir($path, 0);
        try {
            $generator = new autoloader\generator($path);
            $this->assertTrue(false);
        } catch (\RuntimeException $e) {
            $this->assertTrue(true);
        }
        rmdir($path);
    }

    /**
     *  @expectedException \RuntimeException
     */ 
    public function testInvalidOutputDir() {
        $generator = new autoloader\generator(__DIR__ . '/fixtures/basic');
        $generator->generate(__DIR__ . "/something/weird.php");
    }

    public function testInvalidOutputDirReadOnly() {
        $path = __DIR__ . "/fixtures/read-only";
        mkdir($path, 0);
        $generator = new autoloader\generator(__DIR__ . '/fixtures/basic');
        try {
            $generator->generate($path . '/foo.php');
            $this->assertTrue(false);
        } catch (\RuntimeException $e) {
            $this->assertTrue(true);
        }
        rmdir($path);
    }

    /**
     *  @depends testBasicGeneration
     */
    public function testInvalidOutputFile() {
        $path = __DIR__ . "/fixtures/basic.php";
        $generator = new autoloader\generator(__DIR__ . '/fixtures/basic');
        try {
            $generator->generate($path . '/foo.php');
            $this->assertTrue(false);
        } catch (\RuntimeException $e) {
            $this->assertTrue(true);
        }
    }

    public function testInvalidClassName() {
        try {
            new \Autoloader\ClassDef("\\foo\\bar\\");
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
}
