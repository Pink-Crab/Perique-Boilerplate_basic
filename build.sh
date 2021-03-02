#!/usr/bin/env bash

instal_dev=${1:-false}

if [ $instal_dev = "--dev" ]
then
    echo "Will reinstall dev build on completition"
fi

# Install dev dependencies
echo "Installing dev dependencies"
rm -rf vendor
composer config autoloader-suffix ##DEV_AUTLOADER_PREFIX##
composer config prepend-autoloader true
composer install 

# Build all scoper patchers
echo "Building scope patchers"
php build-tools/run.php

# Run production build
echo "Building production"
composer config autoloader-suffix ""
rm -rf build 
rm -rf vendor
composer clear-cache
composer install --no-dev

echo "Running php-scoper"
php build-tools/scoper.phar add-prefix --output-dir=build --force scoper.inc.php

# Reset autoloader pefix & dump the autoloader to the new build path.
echo "Reset prefix for dev & rebuild autoloader in build"
composer config autoloader-suffix ##DEV_AUTLOADER_PREFIX##
composer dump-autoload --working-dir build --classmap-authoritative

if [ $instal_dev = "--dev" ]
then
    echo "Rebuilding dev dependencies"
    composer install 
fi


