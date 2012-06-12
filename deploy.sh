#!/bin/bash -x

php cli.php generate --library lib/Autoloader/loader.php lib/ vendor/
php cli.php createPhar
