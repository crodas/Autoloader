<?php
require "lib/Autoloader/Generator.php";

$generator = new Autoloader\Generator("vendor/");
$generator->generate("generated.php");
if (is_file("generated.php")) {
    echo "The autoloader is generated and saved in generated.php\n";
} else {
    echo "Failed\n";
}
