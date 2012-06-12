<?php
$call = 0;
$load = 0;
spl_autoload_register(function ($class) use (&$call, &$load) {
    /*
        This array has a map of (class => file)
    */

    // classes {{{
    static $classes = array (
  'autoloader\\cliapp' => '/CliApp.php',
  'autoloader\\generator' => '/Generator.php',
  'autoloader\\classdef' => '/ClassDef.php',
  'symfony\\component\\console\\output\\nulloutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/NullOutput.php',
  'symfony\\component\\console\\output\\output' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/Output.php',
  'symfony\\component\\console\\output\\consoleoutputinterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/ConsoleOutputInterface.php',
  'symfony\\component\\console\\output\\outputinterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/OutputInterface.php',
  'symfony\\component\\console\\output\\streamoutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/StreamOutput.php',
  'symfony\\component\\console\\output\\consoleoutput' => '/../../vendor/symfony/console/Symfony/Component/Console/Output/ConsoleOutput.php',
  'symfony\\component\\console\\application' => '/../../vendor/symfony/console/Symfony/Component/Console/Application.php',
  'symfony\\component\\console\\helper\\formatterhelper' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/FormatterHelper.php',
  'symfony\\component\\console\\helper\\helper' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/Helper.php',
  'symfony\\component\\console\\helper\\helperinterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/HelperInterface.php',
  'symfony\\component\\console\\helper\\helperset' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/HelperSet.php',
  'symfony\\component\\console\\helper\\dialoghelper' => '/../../vendor/symfony/console/Symfony/Component/Console/Helper/DialogHelper.php',
  'symfony\\component\\console\\shell' => '/../../vendor/symfony/console/Symfony/Component/Console/Shell.php',
  'symfony\\component\\console\\formatter\\outputformatterstyleinterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterStyleInterface.php',
  'symfony\\component\\console\\formatter\\outputformatterinterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterInterface.php',
  'symfony\\component\\console\\formatter\\outputformatterstyle' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterStyle.php',
  'symfony\\component\\console\\formatter\\outputformatterstylestack' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatterStyleStack.php',
  'symfony\\component\\console\\formatter\\outputformatter' => '/../../vendor/symfony/console/Symfony/Component/Console/Formatter/OutputFormatter.php',
  'symfony\\component\\console\\input\\arrayinput' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/ArrayInput.php',
  'symfony\\component\\console\\input\\input' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/Input.php',
  'symfony\\component\\console\\input\\inputoption' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputOption.php',
  'symfony\\component\\console\\input\\inputinterface' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputInterface.php',
  'symfony\\component\\console\\input\\argvinput' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/ArgvInput.php',
  'symfony\\component\\console\\input\\inputargument' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputArgument.php',
  'symfony\\component\\console\\input\\stringinput' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/StringInput.php',
  'symfony\\component\\console\\input\\inputdefinition' => '/../../vendor/symfony/console/Symfony/Component/Console/Input/InputDefinition.php',
  'symfony\\component\\console\\command\\listcommand' => '/../../vendor/symfony/console/Symfony/Component/Console/Command/ListCommand.php',
  'symfony\\component\\console\\command\\command' => '/../../vendor/symfony/console/Symfony/Component/Console/Command/Command.php',
  'symfony\\component\\console\\command\\helpcommand' => '/../../vendor/symfony/console/Symfony/Component/Console/Command/HelpCommand.php',
  'symfony\\component\\finder\\comparator\\numbercomparator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Comparator/NumberComparator.php',
  'symfony\\component\\finder\\comparator\\comparator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Comparator/Comparator.php',
  'symfony\\component\\finder\\comparator\\datecomparator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Comparator/DateComparator.php',
  'symfony\\component\\finder\\glob' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Glob.php',
  'symfony\\component\\finder\\finder' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Finder.php',
  'symfony\\component\\finder\\iterator\\filetypefilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/FileTypeFilterIterator.php',
  'symfony\\component\\finder\\iterator\\customfilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/CustomFilterIterator.php',
  'symfony\\component\\finder\\iterator\\excludedirectoryfilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/ExcludeDirectoryFilterIterator.php',
  'symfony\\component\\finder\\iterator\\sizerangefilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/SizeRangeFilterIterator.php',
  'symfony\\component\\finder\\iterator\\multiplepcrefilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/MultiplePcreFilterIterator.php',
  'symfony\\component\\finder\\iterator\\daterangefilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/DateRangeFilterIterator.php',
  'symfony\\component\\finder\\iterator\\recursivedirectoryiterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/RecursiveDirectoryIterator.php',
  'symfony\\component\\finder\\iterator\\filecontentfilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/FilecontentFilterIterator.php',
  'symfony\\component\\finder\\iterator\\filenamefilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/FilenameFilterIterator.php',
  'symfony\\component\\finder\\iterator\\depthrangefilteriterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/DepthRangeFilterIterator.php',
  'symfony\\component\\finder\\iterator\\sortableiterator' => '/../../vendor/symfony/finder/Symfony/Component/Finder/Iterator/SortableIterator.php',
  'symfony\\component\\finder\\splfileinfo' => '/../../vendor/symfony/finder/Symfony/Component/Finder/SplFileInfo.php',
  'notoj\\notoj' => '/../../vendor/crodas/Notoj/lib/Notoj/Notoj.php',
  'notoj\\tokenizer' => '/../../vendor/crodas/Notoj/lib/Notoj/Tokenizer.php',
  'notoj_yytoken' => '/../../vendor/crodas/Notoj/lib/Notoj/Parser.php',
  'notoj_yystackentry' => '/../../vendor/crodas/Notoj/lib/Notoj/Parser.php',
  'notoj_parser' => '/../../vendor/crodas/Notoj/lib/Notoj/Parser.php',
  'notoj\\reflectionfunction' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionFunction.php',
  'notoj\\reflectionproperty' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionProperty.php',
  'notoj\\reflectionmethod' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionMethod.php',
  'notoj\\reflectionclass' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionClass.php',
  'notoj\\reflectionobject' => '/../../vendor/crodas/Notoj/lib/Notoj/ReflectionObject.php',
  'artifex' => '/../../vendor/crodas/Artifex/lib/Artifex.php',
  'artifex\\runtime\\base' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Base.php',
  'artifex\\runtime\\variable' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Variable.php',
  'artifex\\runtime\\exec' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Exec.php',
  'artifex\\runtime\\term' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Term.php',
  'artifex\\runtime\\rawstring' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/RawString.php',
  'artifex\\runtime\\expr' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Expr.php',
  'artifex\\runtime\\concat' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Concat.php',
  'artifex\\runtime\\assign' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Assign.php',
  'artifex\\runtime\\expr_if' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Expr/If.php',
  'artifex\\runtime\\expr_foreach' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime/Expr/Foreach.php',
  'artifex\\tokenizer' => '/../../vendor/crodas/Artifex/lib/Artifex/Tokenizer.php',
  'artifex_yytoken' => '/../../vendor/crodas/Artifex/lib/Artifex/Parser.php',
  'artifex_yystackentry' => '/../../vendor/crodas/Artifex/lib/Artifex/Parser.php',
  'artifex_parser' => '/../../vendor/crodas/Artifex/lib/Artifex/Parser.php',
  'artifex\\runtime' => '/../../vendor/crodas/Artifex/lib/Artifex/Runtime.php',
  'composer\\autoload\\classloader' => '/../../vendor/composer/ClassLoader.php',
);
    // }}}

    // deps {{{
    static $deps    = array (
  'symfony\\component\\console\\output\\nulloutput' => 
  array (
    0 => 'symfony\\component\\console\\output\\outputinterface',
    1 => 'symfony\\component\\console\\output\\output',
  ),
  'symfony\\component\\console\\output\\output' => 
  array (
    0 => 'symfony\\component\\console\\output\\outputinterface',
  ),
  'symfony\\component\\console\\output\\consoleoutputinterface' => 
  array (
    0 => 'symfony\\component\\console\\output\\outputinterface',
  ),
  'symfony\\component\\console\\output\\streamoutput' => 
  array (
    0 => 'symfony\\component\\console\\output\\outputinterface',
    1 => 'symfony\\component\\console\\output\\output',
  ),
  'symfony\\component\\console\\output\\consoleoutput' => 
  array (
    0 => 'symfony\\component\\console\\output\\outputinterface',
    1 => 'symfony\\component\\console\\output\\consoleoutputinterface',
    2 => 'symfony\\component\\console\\output\\outputinterface',
    3 => 'symfony\\component\\console\\output\\output',
    4 => 'symfony\\component\\console\\output\\streamoutput',
  ),
  'symfony\\component\\console\\helper\\formatterhelper' => 
  array (
    0 => 'symfony\\component\\console\\helper\\helperinterface',
    1 => 'symfony\\component\\console\\helper\\helper',
  ),
  'symfony\\component\\console\\helper\\helper' => 
  array (
    0 => 'symfony\\component\\console\\helper\\helperinterface',
  ),
  'symfony\\component\\console\\helper\\dialoghelper' => 
  array (
    0 => 'symfony\\component\\console\\helper\\helperinterface',
    1 => 'symfony\\component\\console\\helper\\helper',
  ),
  'symfony\\component\\console\\formatter\\outputformatterstyle' => 
  array (
    0 => 'symfony\\component\\console\\formatter\\outputformatterstyleinterface',
  ),
  'symfony\\component\\console\\formatter\\outputformatter' => 
  array (
    0 => 'symfony\\component\\console\\formatter\\outputformatterinterface',
  ),
  'symfony\\component\\console\\input\\arrayinput' => 
  array (
    0 => 'symfony\\component\\console\\input\\inputinterface',
    1 => 'symfony\\component\\console\\input\\input',
  ),
  'symfony\\component\\console\\input\\input' => 
  array (
    0 => 'symfony\\component\\console\\input\\inputinterface',
  ),
  'symfony\\component\\console\\input\\argvinput' => 
  array (
    0 => 'symfony\\component\\console\\input\\inputinterface',
    1 => 'symfony\\component\\console\\input\\input',
  ),
  'symfony\\component\\console\\input\\stringinput' => 
  array (
    0 => 'symfony\\component\\console\\input\\inputinterface',
    1 => 'symfony\\component\\console\\input\\input',
    2 => 'symfony\\component\\console\\input\\argvinput',
  ),
  'symfony\\component\\console\\command\\listcommand' => 
  array (
    0 => 'symfony\\component\\console\\command\\command',
  ),
  'symfony\\component\\console\\command\\helpcommand' => 
  array (
    0 => 'symfony\\component\\console\\command\\command',
  ),
  'symfony\\component\\finder\\comparator\\numbercomparator' => 
  array (
    0 => 'symfony\\component\\finder\\comparator\\comparator',
  ),
  'symfony\\component\\finder\\comparator\\datecomparator' => 
  array (
    0 => 'symfony\\component\\finder\\comparator\\comparator',
  ),
  'symfony\\component\\finder\\iterator\\filecontentfilteriterator' => 
  array (
    0 => 'symfony\\component\\finder\\iterator\\multiplepcrefilteriterator',
  ),
  'symfony\\component\\finder\\iterator\\filenamefilteriterator' => 
  array (
    0 => 'symfony\\component\\finder\\iterator\\multiplepcrefilteriterator',
  ),
  'artifex\\runtime\\variable' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\exec' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\term' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\rawstring' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\expr' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\concat' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\assign' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\expr_if' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
  'artifex\\runtime\\expr_foreach' => 
  array (
    0 => 'artifex\\runtime\\base',
  ),
);
    // }}}

    $class = strtolower($class);
    if (isset($classes[$class])) {
        $call++;
        $load++;
        if (!empty($deps[$class])) {
            foreach ($deps[$class] as $zclass) {
                if (!class_exists($zclass, false) && !interface_exists($zclass, false)) {
                    $load++;
                    require __DIR__  . $classes[$zclass];
                }
            }
        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            require __DIR__  . $classes[$class];
        }
        return true;
    }

    return false;
});


