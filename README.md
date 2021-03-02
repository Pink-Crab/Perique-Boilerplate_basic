# PinkCrab Plugin Boilerplate #

Welcome to the PinkCrab Plugin Boilerplate. While this assumes you will be using the PinkCrab Plugin Framework to build your plugin, you can choose to just us this as a base for plugins with any composer libraries.

## Setup

Before you run build or composer install for the first time, you have a few things which need setting up.

### bootstrap.php

Your bootstrap file is the primary entrypoint for your plugin, this is mostly setup and ready to go. Although it does require you to set the **use** import statments at the top of the file. If you are planning to use PHP-Scoper (which we recommend), the classes will need to be imported with the correct namespace. If you set the namespace prefix to be **Ache_Plugin_B345_F**, you will need to add this to your namespaces

```php
use PinkCrab\Core\Application\App;
// Becomes
use Ache_Plugin_B345_F\PinkCrab\Core\Application\App;
```

You can also choose to have the View class accessible globally, by uncommenting out
```php
// Bind view to App
//$view = $app::make( View::class );
//$app->set( 'view', $view );
```
This will allow you to use ``` App::view()->render('template/path', ['key'=> 'value']) ``` anywhere in your code. While its nice and handly, passing View into a constructor is much cleaner.

### plugin.php

Obviously this is our plugins entrypoint with WordPress. Like any plugin, you will need to fill out the doc block at the top for WordPress to parse and the use statement for App will need to be changed to match you scoped namespace.

### function_pollyfills.php

When php-scoper is run, it does have a few issues when using function_exists('some_global_function'). They are namespaced only in the function_exists call. While you can write PollyFills in this file (just ensure you use the same namespace found in scoper.inc.php), it doesnt help for wp-functions which are loaded only when needed like get_current_screen. 

In the initial file produced, we have included esc_attr as this is used in one of our libs. If you wanted to check what functions might be affected, see scoper-autoload.php after running build. You will need to search for the functions inside your build/vendor directory to see if they exist, in your final code.

### composer.json
This is mostly as you would expect, with the one cavet where if you are using php-scoper (you should!!!!), you will need to set your local paths to reflect vendor no longer being in the root dir. 
```json
"autoload": {
    "psr-4": {
        "PinkCrab\\My_Plugin\\": "src",
        "PinkCrab\\WP\\My_Plugin\\": "wp"
    },
    "files": []
},
```
Becomes
```json
"autoload": {
    "psr-4": {
        "PinkCrab\\My_Plugin\\": "../src",
        "PinkCrab\\WP\\My_Plugin\\": "../wp"
    },
    "files": []
},
```
To allows for 2 instaces of vendor (testing & production), we have to use custom **autoloader-suffix**. These are only used on the development version of vendor and should be set to reflect the project. If you are only ever going to have 1 plugin using this framework in your dev sandbox, you can leave it as it is.
```json
 "config": {
    "prepend-autoloader": true,
    "autoloader-suffix": "ache_plugin_dev"
}
```
### scoper.inc.php
This is the main settings file for the scoping process, you will a function called **$patcherProvider()**. This is where we call the patchers created during build. If you have changed the path in *build-tools/run.php*, ensure you change them in here too. 

You will need to set your prefix for all namespaces in here, you can add in custom functions too. 
```php
return array(
// Set your namespace prefix here
    'prefix' => 'PcLocations_001',
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
        'Your\Plugins\Code\*', <- Your namespaces here
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
composer config autoloader-suffix pc_plugin_bp_dev
```
Inside this file, you can set the build dir to be else where and generally make a few changes to how it builds. Also feel free to add in any NODE commands for building CSS & JS too.

### phpstan.neon.dist
This file holds all the phpstan rules. You will see there is an extra bootstrap file which is used to include your scoped vendor dir. If you move from *build/vendor* to another path, update this line to match
```yaml
    - %currentWorkingDirectory%/build/vendor/autoload.php
```

### wp(dir)
This directory holds all the plugin activation/deactivation hooks. They are excluded from the rest of the codebase due to where they are called (in plugin.php). You will need to set the namespaces based on your setting in composer.json and also ensure all use App statements are using the namespace esacped ones.

### config/settings.php
These are the settings used by the **App_Config** and **Config** classes. You will need to add in additional settings for all your cpt, taxonomy and cache/rest namespaces. If you would like to have your assets or view directories else where, you can change the path and urls accordingly.

### config/dependencies.php
All commented out namepsaces will need replacing if you use php-scoper. For more information about how we use Dice for DI, please consult the main documentation.

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

Runs the same process as above, but will rerun composer install for all your dev dependencies (in the root vendor dir). This will allow you to run all the test suites as your are developing and should be used in all workflows for github. One thing to note, when doing this you will find your IDE will suggest 2 versions of some classes to use. Always choose the one whic has been escaped with your custom namespace.
```php 

use My_Ache_Plugin\PinkCrab\Application\App; <-- choose me
- or
use PinkCrab\Application\App;
```

### Thank you
Thank you for looking into this plugin boilerplate, the main documentation should cover most of how the plugin framework works, this is more just a set up guide. If you find this hard to read, have suggestions or would like to contribute, please raise an issue or put in a pull request.