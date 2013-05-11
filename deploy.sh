#!/bin/bash -x

rm -f autoloader.phar
php cli.php generate --library lib/Autoloader/loader.php lib/ vendor/
php cli.php compile
