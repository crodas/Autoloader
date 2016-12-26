#!/bin/bash -x

rm -f autoloader.phar

php vendor/crodas/simple-view-engine/cli.php compile -N Autoloader lib/Autoloader/Templates lib/Autoloader/Templates.php
php cli.php generate --library lib/Autoloader/loader.php lib/ vendor/
phpunit

exit
php cli.php compile
