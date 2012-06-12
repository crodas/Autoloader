#!/bin/bash -x

php cli.php createPhar
./autoloader.phar generate --relative --include-psr-0=false lib/Autoloader/loader.php lib/ vendor/
