# PinkCrab Plugin Boilerplate || Seed Build 0.4.0 #

[Built from release 0.4.0](https://github.com/Pink-Crab/Framework_Plugin_Boilerplate/releases/tag/0.4.0) of the BoilerPlate.

> PLEASE ENSURE YOU READ THE FULL DOCUMENTATION BEFORE USING THIS BUILD

Unlike the regular release version of this Plugin Boilerplate, the *Seed Build* is geared up for using **PHP-Scoper**. A basic WordPress configuration and custom patcher provider is included. This prevents the prefixing of WordPress (plus WooCommerce and ACF) functions/classes/constants within the scoped dependencies. Its not perfect sadly, but close enough to be easily workable.

## Setup

Before you can run the customer build process, you will need to replace all placeholders which are used throughout the plugins files. You should be able to use find and replace to do this pretty easily.

**THESE ARE ALL REQUIRED**

| Placeholder      | Description |
| ----------- | ----------- |
| ##PLUGIN_NAME##      | The name of your plugin, used in plugin.php and composer.json    |
| ##PLUGIN_URL##      | URL to the plugin website or repo*    |
| ##PLUGIN_DESCRIPTION##      | The plugins description   |
| ##PLUGIN_VERSION##      | The plugins current/initial version number   |
| ##PLUGIN_TEXTDOMAIN##      | The plugins textdomain   |
| ##AUTHOR_NAME##      | The plugin authors name   |
| ##AUTHOR_URL##      | The plugin authors website*  |
| ##AUTHOR_EMAIL##      | The plugin authors email*  |
| ##NAMESPACE##      | The plugins namespapce (used in composer.json, so ensure you use **\\\\** )    |
| ##SCOPER_PREFIX##      | The custom prefix used on all namespaces found in *vendor*.     |
| ##PACKAGE_NAME##      | The package name used in composer.json (**achme/plugin-x**)    |
| ##DEV_AUTLOADER_PREFIX##      | The custom autoloader prefix for dev dependencies (must be valid php namespace like **achme_plugin_x_dev** )   |

***

> \* Composer validates email and urls, so ensure only valid email and url formats are used. 

### plugin.php

The app is bootstrapped from here and both the function_pollyfills.php and scoped vendors, autoloader are included. 

### function_pollyfills.php

When php-scoper is run, it does have a few issues when using function_exists('some_global_function'). They are namespaced only in the function_exists call. While you can write PollyFills in this file (just ensure you use the same namespace found in scoper.inc.php), it doesnt help for wp-functions which are loaded only when needed like get_current_screen. 

In the initial file produced, we have included esc_attr as this is used in one of our libs. If you wanted to check what functions might be affected, see scoper-autoload.php after running build. You will need to search for the functions inside your build/vendor directory to see if they exist, in your final code.

### composer.json
This is mostly as you would expect, with the one cavet where if you are using php-scoper (you should!!!!), you will need to set your local paths to reflect vendor no longer being in the root dir. 
```json
"autoload": {
    "psr-4": {
        "##NAMESPACE##": "src",
    },
    "files": []
},
```
Becomes
```json
"autoload": {
    "psr-4": {
        "##NAMESPACE##": "../src",
    },
    "files": []
},
```
To allows for 2 instaces of vendor (testing & production), we have to use custom **autoloader-suffix**. These are only used on the development version of vendor and should be set to reflect the project. If you are only ever going to have 1 plugin using this framework in your dev sandbox, you can leave it as it is.
```json
 "config": {
    "prepend-autoloader": true,
    "autoloader-suffix": "##DEV_AUTLOADER_PREFIX##"
}
```
### scoper.inc.php
This is the main settings file for the scoping process, you will a function called **$patcherProvider()**. This is where we call the patchers created during build. If you have changed the path in *build-tools/run.php*, ensure you change them in here too. 

You will need to set your prefix for all namespaces in here, you can add in custom functions too. 
```php
return array(
// Set your namespace prefix here
    'prefix' => ##SCOPER_PREFIX##,
    .....
);
```
You will also need to set all the namespaces for your plugin (as defined in whitelist settings.)
```php
return array(
    .....
    'whitelist' => array(
        'PHPUnit\Framework\*',
        'Composer\Autoload\ClassLoader',
        '##NAMESPACE##\*', <- Your namespaces here
    ),
    .....
);
```
You can also add in your extra functions/classes/traits/interfaces/constants to the *patchers* list.
```php
'patchers' => array(
    function ( $filePath, $prefix, $contents ) use ( $patcherProvider ) {
        ....
        // Add in any additional symbols to not prefix.
        $contents = str_replace( "\\$prefix\\my_global_function", '\\my_global_function', $contents );
        return $contents;
    },
),
```

### build.sh
This is the main bash file used to create your build, the autoloader-suffix above will need to be changed inside the build.sh file. You will reference to it twice, just update both.
```bash
composer config autoloader-suffix ##DEV_AUTLOADER_PREFIX##
```
Inside this file, you can set the build dir to be else where and generally make a few changes to how it builds. Also feel free to add in any NODE commands for building CSS & JS too.

### phpstan.neon.dist
This file holds all the phpstan rules. You will see there is an extra bootstrap file which is used to include your scoped vendor dir. If you move from *build/vendor* to another path, update this line to match
```yaml
    - %currentWorkingDirectory%/build/vendor/autoload.php
```

### config/settings.php
These are the settings used by the **App_Config** and **Config** classes. You will need to add in additional settings for all your cpt, taxonomy and cache/rest namespaces. If you would like to have your assets or view directories else where, you can change the path and urls accordingly.


### tests/wp-config.php
As with wordpress, the tests wp-config.php will need to be created. 
Currently it is set to work on both github cli and locally. You will need to adjust your settings in both if you plan to use github actions (see workflows for more details). As with all WP_PHPUnit setups, this will require a table for tests to be carried out with. NEVER USE A PRODUCTION DATABASE!!!!

### github/workflows/php.yaml
In here is all of your github workflow. This will run your tests against php 7.1, 7.2, 7.3 & 7.4. It will create a database for these tests and you can change the password and table name in here. Ensure these match the values in your tests/wp-config.php file.

## Running Build.sh
Whereas you usually would just run composer install to build your project, this requires a custom script to do so. You can choose to either run this for production or development. 

*Production Build*

```$ bash build.sh```

This will first run a development build of your plugin, it will compile all the patchers (from WP, WC & ACF stub libs), then clear the vendor folder and run the production version of composer install. After this it will start the scoper process of reparsing all your depenedencies with your prefix. After its finished, you should be able to run your plugin on a WordPress site.

*Development Build*

```$ bash build.sh --dev```

Runs the same process as above, but will rerun composer install for all your dev dependencies (in the root vendor dir). This will allow you to run all the test suites as your are developing and should be used in all workflows for github. One thing to note, when doing this you will find your IDE will suggest 2 versions of some classes to use. Always choose the one which has been prefixed with your custom namespace.
```php 

use Achme_Plugin\PinkCrab\Application\App; <-- choose me
- or
use PinkCrab\Application\App;
```

### Thank you
Thank you for looking into this plugin boilerplate, the main documentation should cover most of how the plugin framework works, this is more just a set up guide. If you find this hard to read, have suggestions or would like to contribute, please raise an issue or put in a pull request.