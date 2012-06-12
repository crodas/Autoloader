<?php

spl_autoload_register(function ($class) {
    /*
        This array has a map of (class => file)
    */
    static $classes = array (
  'Autoloader\\CliApp' => '/CliApp.php',
  'Autoloader\\Generator' => '/Generator.php',
  'Symfony\\Component\\Console\\Output\\NullOutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/NullOutput.php',
  'Symfony\\Component\\Console\\Output\\ConsoleOutputInterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/ConsoleOutputInterface.php',
  'Symfony\\Component\\Console\\Output\\StreamOutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/StreamOutput.php',
  'Symfony\\Component\\Console\\Output\\ConsoleOutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/ConsoleOutput.php',
  'Symfony\\Component\\Console\\Output\\OutputInterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/OutputInterface.php',
  'Symfony\\Component\\Console\\Output\\Output' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/Output.php',
  'Symfony\\Component\\Console\\Tester\\ApplicationTester' => '/../../vendor/symfony/console/Symfony/Component/Console/Tester/ApplicationTester.php',
  'Symfony\\Component\\Console\\Tester\\CommandTester' => '/../../vendor/symfony/console/Symfony/Component/Console/Tester/CommandTester.php',
  'Foo2Command' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Fixtures/Foo2Command.php',
  'TestCommand' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Fixtures/TestCommand.php',
  'FooCommand' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Fixtures/FooCommand.php',
  'Foo3Command' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Fixtures/Foo3Command.php',
  'Foo1Command' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Fixtures/Foo1Command.php',
  'Symfony\\Component\\Console\\Tests\\Output\\ConsoleOutputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Output/ConsoleOutputTest.php',
  'Symfony\\Component\\Console\\Tests\\Output\\StreamOutputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Output/StreamOutputTest.php',
  'Symfony\\Component\\Console\\Tests\\Output\\NullOutputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Output/NullOutputTest.php',
  'Symfony\\Component\\Console\\Tests\\Output\\OutputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Output/OutputTest.php',
  'Symfony\\Component\\Console\\Tests\\Output\\TestOutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Output/OutputTest.php',
  'Symfony\\Component\\Console\\Tests\\Tester\\CommandTesterTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Tester/CommandTesterTest.php',
  'Symfony\\Component\\Console\\Tests\\Tester\\ApplicationTesterTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Tester/ApplicationTesterTest.php',
  'Symfony\\Component\\Console\\Tests\\Helper\\DialogHelperTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Helper/DialogHelperTest.php',
  'Symfony\\Component\\Console\\Tests\\Helper\\FormatterHelperTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Helper/FormatterHelperTest.php',
  'Symfony\\Component\\Console\\Tests\\Formatter\\OutputFormatterStyleStackTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Formatter/OutputFormatterStyleStackTest.php',
  'Symfony\\Component\\Console\\Tests\\Formatter\\OutputFormatterStyleTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Formatter/OutputFormatterStyleTest.php',
  'Symfony\\Component\\Console\\Tests\\Formatter\\FormatterStyleTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Formatter/OutputFormatterTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\InputDefinitionTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/InputDefinitionTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\InputArgumentTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/InputArgumentTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\ArgvInputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/ArgvInputTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\InputOptionTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/InputOptionTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\InputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/InputTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\StringInputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/StringInputTest.php',
  'Symfony\\Component\\Console\\Tests\\Input\\ArrayInputTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Input/ArrayInputTest.php',
  'Symfony\\Component\\Console\\Tests\\Command\\CommandTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Command/CommandTest.php',
  'Symfony\\Component\\Console\\Tests\\Command\\ListCommandTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Command/ListCommandTest.php',
  'Symfony\\Component\\Console\\Tests\\Command\\HelpCommandTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/Command/HelpCommandTest.php',
  'Symfony\\Component\\Console\\Tests\\ApplicationTest' => '/../../vendor/symfony/console/Symfony/Component/Console/Tests/ApplicationTest.php',
  'Symfony\\Component\\Console\\Application' => '/../../vendor/symfony/console/Symfony/Component/Console/Application.php',
  'Symfony\\Component\\Console\\Helper\\FormatterHelper' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/FormatterHelper.php',
  'Symfony\\Component\\Console\\Helper\\Helper' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/Helper.php',
  'Symfony\\Component\\Console\\Helper\\HelperSet' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/HelperSet.php',
  'Symfony\\Component\\Console\\Helper\\DialogHelper' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/DialogHelper.php',
  'Symfony\\Component\\Console\\Helper\\HelperInterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/HelperInterface.php',
  'Symfony\\Component\\Console\\Shell' => '/../../vendor/symfony/console/Symfony/Component/Console/Shell.php',
  'Symfony\\Component\\Console\\Formatter\\OutputFormatterStyleInterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterStyleInterface.php',
  'Symfony\\Component\\Console\\Formatter\\OutputFormatterInterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterInterface.php',
  'Symfony\\Component\\Console\\Formatter\\OutputFormatterStyle' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterStyle.php',
  'Symfony\\Component\\Console\\Formatter\\OutputFormatterStyleStack' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterStyleStack.php',
  'Symfony\\Component\\Console\\Formatter\\OutputFormatter' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatter.php',
  'Symfony\\Component\\Console\\Input\\ArrayInput' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/ArrayInput.php',
  'Symfony\\Component\\Console\\Input\\InputOption' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputOption.php',
  'Symfony\\Component\\Console\\Input\\Input' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/Input.php',
  'Symfony\\Component\\Console\\Input\\ArgvInput' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/ArgvInput.php',
  'Symfony\\Component\\Console\\Input\\InputArgument' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputArgument.php',
  'Symfony\\Component\\Console\\Input\\InputInterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputInterface.php',
  'Symfony\\Component\\Console\\Input\\StringInput' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/StringInput.php',
  'Symfony\\Component\\Console\\Input\\InputDefinition' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputDefinition.php',
  'Symfony\\Component\\Console\\Command\\ListCommand' => '/../../vendor/symfony/console/Symfony/Component/Console/Command/ListCommand.php',
  'Symfony\\Component\\Console\\Command\\HelpCommand' => '/../../vendor/symfony/console/Symfony/Component/Console/Command/HelpCommand.php',
  'Symfony\\Component\\Console\\Command\\Command' => '/../../vendor/symfony/console/Symfony/Component/Console/Command/Command.php',
  'Symfony\\Component\\Finder\\Comparator\\NumberComparator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Comparator/NumberComparator.php',
  'Symfony\\Component\\Finder\\Comparator\\DateComparator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Comparator/DateComparator.php',
  'Symfony\\Component\\Finder\\Comparator\\Comparator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Comparator/Comparator.php',
  'Symfony\\Component\\Finder\\Tests\\GlobTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/GlobTest.php',
  'Symfony\\Component\\Finder\\Tests\\Comparator\\DateComparatorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Comparator/DateComparatorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Comparator\\ComparatorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Comparator/ComparatorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Comparator\\NumberComparatorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Comparator/NumberComparatorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\RealIteratorTestCase' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/RealIteratorTestCase.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\CustomFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/CustomFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\DateRangeFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/DateRangeFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\SortableIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/SortableIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\SizeRangeFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/SizeRangeFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\InnerSizeIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/SizeRangeFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\FilecontentFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/FilecontentFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\ContentInnerNameIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/FilecontentFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\DepthRangeFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/DepthRangeIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\MultiplePcreFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/MultiplePcreFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\TestMultiplePcreFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/MultiplePcreFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\Iterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/Iterator.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\FileTypeFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/FileTypeFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\InnerTypeIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/FileTypeFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\ExcludeDirectoryFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/ExcludeDirectoryFileIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\IteratorTestCase' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/IteratorTestCase.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\FilenameFilterIteratorTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/FilenameFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\Iterator\\InnerNameIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/Iterator/FilenameFilterIteratorTest.php',
  'Symfony\\Component\\Finder\\Tests\\FinderTest' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Tests/FinderTest.php',
  'Symfony\\Component\\Finder\\Glob' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Glob.php',
  'Symfony\\Component\\Finder\\Finder' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Finder.php',
  'Symfony\\Component\\Finder\\Iterator\\FileTypeFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/FileTypeFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\CustomFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/CustomFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\ExcludeDirectoryFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/ExcludeDirectoryFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\SizeRangeFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/SizeRangeFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\MultiplePcreFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/MultiplePcreFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\DateRangeFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/DateRangeFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\RecursiveDirectoryIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/RecursiveDirectoryIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\FilecontentFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/FilecontentFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\FilenameFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/FilenameFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\DepthRangeFilterIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/DepthRangeFilterIterator.php',
  'Symfony\\Component\\Finder\\Iterator\\SortableIterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/SortableIterator.php',
  'Symfony\\Component\\Finder\\SplFileInfo' => '/../../vendor/symfony/finder/Symfony/Component/Finder/SplFileInfo.php',
  'Notoj\\Notoj' => '/../../vendor/crodas/Notoj/lib/Notoj/Notoj.php',
  'Notoj\\Tokenizer' => '/../../vendor/crodas/Notoj/lib/Notoj/Tokenizer.php',
  'Notoj_yyToken' => '/../../vendor/crodas/Notoj/lib/Notoj/Parser.php',
  'Notoj_yyStackEntry' => '/../../vendor/crodas/Notoj/lib/Notoj/Parser.php',
  'Notoj_Parser' => '/../../vendor/crodas/Notoj/lib/Notoj/Parser.php',
  'Notoj\\ReflectionFunction' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionFunction.php',
  'Notoj\\ReflectionProperty' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionProperty.php',
  'Notoj\\ReflectionMethod' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionMethod.php',
  'Notoj\\ReflectionClass' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionClass.php',
  'Notoj\\ReflectionObject' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionObject.php',
  'CacheTest' => '/../../vendor/crodas/Notoj/tests/CacheTest.php',
  'simpletest' => '/../../vendor/crodas/Notoj/tests/SimpleTest.php',
  'ArgumentsTest' => '/../../vendor/crodas/Notoj/tests/ArgumentsTest.php',
  'Artifex' => '/../../vendor/crodas/Artifex/lib/Artifex.php',
  'Artifex\\Runtime\\Base' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Base.php',
  'Artifex\\Runtime\\Variable' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Variable.php',
  'Artifex\\Runtime\\Exec' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Exec.php',
  'Artifex\\Runtime\\Term' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Term.php',
  'Artifex\\Runtime\\RawString' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/RawString.php',
  'Artifex\\Runtime\\Expr' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Expr.php',
  'Artifex\\Runtime\\Concat' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Concat.php',
  'Artifex\\Runtime\\Assign' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Assign.php',
  'Artifex\\Runtime\\Expr_If' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Expr/If.php',
  'Artifex\\Runtime\\Expr_Foreach' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Expr/Foreach.php',
  'Artifex\\Tokenizer' => '/../../vendor/crodas/Artifex/lib/Artifex/Tokenizer.php',
  'Artifex_yyToken' => '/../../vendor/crodas/Artifex/lib/Artifex/Parser.php',
  'Artifex_yyStackEntry' => '/../../vendor/crodas/Artifex/lib/Artifex/Parser.php',
  'Artifex_Parser' => '/../../vendor/crodas/Artifex/lib/Artifex/Parser.php',
  'Artifex\\Runtime' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime.php',
  'BasicTest' => '/../../vendor/crodas/Artifex/tests/BasicTest.php',
  'Composer\\Autoload\\ClassLoader' => '/../../vendor/composer/ClassLoader.php',
);

    if (isset($classes[$class])) {
        require __DIR__  . $classes[$class];
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
