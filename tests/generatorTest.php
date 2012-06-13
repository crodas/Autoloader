<?php

class generatorTest extends \phpunit_framework_testcase
{
    public static function provider() {
        return array(
            array('basic', array('simple\Foo', 'simple\Bar')),
            array('caseInsentive', array('CaseInsentive\Foo', 'CaseInsentive\Bar')),
            array('complex', array('complex\Complex')),
            array('complex_relative', array('complex\Complex_rel')),
        );
    }

    /**
     *  @dataProvider provider
     */
    public function testBasicGeneration($targetName, $classes)
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
        $generator->generate($target, $relative);

        foreach ($classes as $class) {
            $class = '\\Autoloader\\test\\' . $class;
            $this->assertFalse(class_exists($class, false));
        }

        require $target;

        foreach ($classes as $class) {
            $class = '\\Autoloader\\test\\' . $class;
            $this->assertTrue(class_exists($class));
        }
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
}
