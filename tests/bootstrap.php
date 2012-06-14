<?php

require __DIR__ . "/../lib/Autoloader/loader.php";

foreach(glob(__DIR__ . "/fixtures/*.php") as $file) {
    unlink($file);
}
