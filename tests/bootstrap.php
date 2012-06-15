<?php

require __DIR__ . "/../lib/Autoloader/loader.php";

set_include_path(__DIR__ . "/fixtures/some_other_include_path/:" . get_include_path());

foreach(glob(__DIR__ . "/fixtures/*.php") as $file) {
    unlink($file);
}
