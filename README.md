# Framework_Plugin_Boilerplate
A plugin boilerplate making full use of the PinkCrab Framework

## Setup

Before using this boilerplate dont forget to change the values in both plugin.php and composer.json

Once they are both setup you can run the following.

````
git submodule update --recursive --remote
````

Then install composer (you can add more packages etc first)
````
composer install
````

The dev version of composer comes with symphony dumper and a wordpress compatiable version of PHPStan. Please note the WP Stubs file is a little old now and will give false positives. 
