# PinkCrab Perique Boilerplate || Seed Build 1.0.0 #

[Built from release 1.0.0](https://github.com/Pink-Crab/Perique-Boilerplate_basic/releases/tag/1.0.0) of the BoilerPlate.

> PLEASE ENSURE YOU READ THE FULL DOCUMENTATION BEFORE USING THIS BUILD

Unlike the regular release version of this Plugin Boilerplate, the *Seed Build* is geared up for using **PHP-Scoper**. A basic WordPress configuration and custom patcher provider is included. This prevents the prefixing of WordPress (plus WooCommerce and ACF) functions/classes/constants within the scoped dependencies. Its not perfect sadly, but close enough to be easily workable.

Welcome to the Perique Boilerplate. The Perique Framework give you all the basic tools needed to make a MVC style plugins for WordPress. Comes with a DI Container (DICE), custom Hook Loader and extendable registration process for interacting with WP apis.

## Automated Setup

If you would like to use our automated setup, please run the following command in the directory you wish to build the boilerplate.
> `$ wget https://bin.pinkcrab.co.uk/pinkcrab && pinkcrab`

This should download the latest builder from our server and run it automatically. Once the script is run, you will be asked a serious of questions, please fill these in and a fully populated boilerplate will be created.

> Rather than running composer install please run `$ composer build-dev` after adding in any dependencies you may need. Please read below for more details of how this works and unusual workflow.

## Manual Setup

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
| ##NAMESPACE##      | The plugins namespace (used in composer.json, so ensure you use **\\\\** )    |
| ##SCOPER_PREFIX##      | The custom prefix used on all namespaces found in *vendor*.     |
| ##PACKAGE_NAME##      | The package name used in composer.json (**achme/plugin-x**)    |
| ##DEV_AUTLOADER_PREFIX##      | The custom autoloader prefix for dev dependencies (must be valid php namespace like **achme_plugin_x_dev** )   |

***

> \* Composer validates email and urls, so ensure only valid email and url formats are used. 


The app is bootstrapped from here and both the function_pollyfills.php and scoped vendors, autoloader are included. 


**Please note the /tests directory assumes your namespace will be My\\Namespace\\Tests**

In the initial file produced, we have included esc_attr as this is used in one of our libs. If you wanted to check what functions might be affected, see scoper-autoload.php after running build. You will need to search for the functions inside your build/vendor directory to see if they exist, in your final code.

### composer.json
This is mostly as you would expect, with the one caveat where if you are using php-scoper (you should!!!!), you will need to set your local paths to reflect vendor no longer being in the root dir. 
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
To allows for 2 instances of vendor (testing & production), we have to use custom **autoloader-suffix**. These are only used on the development version of vendor and should be set to reflect the project. If you are only ever going to have 1 plugin using this framework in your dev sandbox, you can leave it as it is.
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

We have added `$ composer build` (production only) and `$ composer build-dev`. These should be used in place of 
* `composer install` or `composer update` (use `composer build-dev`)
* `composer install --no-dev` or `composer update --no-dev` (use `composer build`)  
> *Please note these do not follow the naming structure of native composer commands*  

These should be run after adding new dependency. NEVER RUN composer update or composer install directly (ONLY)

### Caveat with Scoper Build
This is not a perfect solution, you might find some dependency functionality causes problems. This can be caused by `function_exists('function_name)`, while you can use the `function_pollyfils.php` file, you can still hit problems with Functions wordpress calls in on JIT includes on when used (especially in wp-admin). 
As we use a customer prefixed autoloader, there are some times when we class can be found. When this happens you will need to dump the autoloader, to make this easier, we have a custom command `$ composer dump` which will explicitly dump the prefixed version.

## Tests

### config/settings.php
These are the settings used by the **App_Config** and **Config** classes. You will need to add in additional settings for all your cpt, taxonomy and cache/rest namespaces. If you would like to have your assets or view directories else where, you can change the path and urls accordingly.


## GitHub Actions

By default the a simple actions yml file is provided, this will run the full test cases before merging or generating a pull request to merge into master. You can change this in ```.github/workflows/php.yaml```. The action uses a mysql database to allow the full use of WPUnit, the mysql details can be found in ```tests/wp-config.php```

## WPUnit

As mentioned the Boilerplate comes with WPUnit included and will install a version of WordPress for running tests through. As with WPUnit, you will need to ensure you have a database ready to use. If you look in the ```tests/wp-config.php``` file, you can change the username, password, host and table name. You will notice there are 2 sets of credentials, the first set are for GitHub actions. If you wish to change these, you will need to edit the ```.github/workflows/php.yaml``` to reflect this.

## File Structure

This Boilerplate is fairly agnostic where and how you structure your code. Out of the box, this Boilerplate is set up with the following assumptions

```bash
| /.github           # Holds the github actions files, should not be moved!
| /assets            # All JS, CSS and Images should be housed in here.
| /config            # Holds the 3 main config files settings.php, dependencies.php and registration.php
| /src               # Place all of your code in here
| /tests             # Holds all PHPUnit and WP_Unit config files and tests themselves.
| /views             # Holds all template files
| composer.json      # The composer configuration file.
| phpcs.xml          # Defines the rule set and custom rules for PHP-CS
| phpstan.neon.dist  # PHPStan definitions and stub declarations.
| phpunit.xml.dist   # WP & PHPUnit configurations.
| plugin.php         # The WP Plugin definition and app bootstrapping.
| README.md          # You are here!

# Optional artifacts, all included in gitignore and generated for/during testing.
| /coverage-report   # The generated Coverage Report in HTML format.
| /wordpress         # The test instance of WP used for WPUnit tests
| /vendor            # Composer directory, should be uploaded with your code, but not needed in repo
| coverage.xml       # The PHPUnit coverage report used by services such as CodeCov
```

## Additional Docs

Runs the same process as above, but will rerun composer install for all your dev dependencies (in the root vendor dir). This will allow you to run all the test suites as your are developing and should be used in all workflows for github. One thing to note, when doing this you will find your IDE will suggest 2 versions of some classes to use. Always choose the one which has been prefixed with your custom namespace.
```php 

use Achme_Plugin\PinkCrab\Application\App; <-- choose me
- or
use PinkCrab\Application\App;
```

If you would like to contribute to the Perique Framework and/or any of its packages, please feel to reach out at glynn@pinkcrab.co.uk or generate an issue/pr over on github. This package is manually updated every time we make substantial changes to the Core package.