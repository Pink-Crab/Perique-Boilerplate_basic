# PinkCrab Perique Boilerplate *V1.0.0 #


Welcome to the Perique Boilerplate. The Perique Framework give you all the basic tools needed to make a MVC style plugins for WordPress. Comes with a DI Container (DICE), custom Hook Loader and extendable registration process for interacting with WP apis.

## Setup

Just clone this repo into your wp-content/plugins directory, remove the .git folder and replace the placeholders found in composer.json, config/settings and the plugin.php file. Once they have been setup, its just a case of running ```composer install``` or ```composer install --no-dev``` once you are ready to release your code.

### Placeholders

These are the placeholders currently in place that will need changing before running composer install


| Placeholder | Description | Files |
| --- | ----------- | --- |
| ##PACKAGE_NAME## | Should be in valid composer format *achme/my-plugin*  | composer.json & settings.php |
| ##DESCRIPTION## | This is your plugins description  | composer.json & plugin.php |
| ##YOUR URL##  | Replace with your own homepage or github profile  | composer.json & plugin.php |
| ##AUTHOR## | The name of the primary developer, you can add more authors if you wish to composer.json | composer.json & plugin.php |
| ##YOUR EMAIL## | Contact email for the main developer  | composer.json |
| ##NAMESPACE## | The namespace used by composers autoloader, remeber to use \\\\ 👍  | composer.json |
| ##PLUGIN NAME## | PLugin name for WordPress  | plugin.php |
| ##VERSION## | The current plugin version, this is reflected in App_Config::class  | plugin.php |
| ##TEXT DOMAIN## | WordPress TextDomain  | plugin.php |
| ##DB_NAME## | Test Database name  | test/wp-config.php |
| ##DB_USER## | Test Database user  | test/wp-config.php |
| ##DB_PASSWORD## | Test Database users password  | test/wp-config.php |
| ##DB_HOST## | Test Database host  | test/wp-config.php |


**Please note the /tests directory assumes your namespace will be My\\Namespace\\Tests**

## Packages

Perique allows the use of composer packages, which can be added as normal. However care should be take to ensure that you only add dependencies if you have 100% control over the codebase. You might be using the latest and greatest version of a package and another plugin uses a older version. Before you know it you have some fun and games with dependency conflicts. We used to package a full build suite using PHP-Scoper to rename package namespaces, but we have removed it from this basic boilerplate.

## Tests

Out of the box, this boilerplate comes with PHPUnit, PHPStan and PHPCS. All 3 are come with pre-populated config files, allow you to hit the ground running. If you look into the composer.json file you will find we have a selection of commands you can run.

* **composer test** This will run phpunit on its own, generating the coverage report and giving the testdox output
* **composer coverage** This will run phpunit and generate a full HTML report of coverage in ```/coverage-report```
* **composer analyse** This will run PHPStan at lv8 over all code in the src/ directory. Package includes WordPress Core, WooCommerce and ACF stubs~
* **composer sniff** This run the the code found in /src through PHPCS using the ruleset defined in phpcs.xml
* **composer all** This will composer test then composer analyse and finally composer sniff. 

~ To use the WooCommerce and ACF stubs, please remove the # from ```phpstan.neon.dist``` 

## GitHub Actions

By default the a simple actions yml file is provided, this will run the full test cases before merging or generating a pull request to merge into master. You can change this in ```.github/workflows/php.yaml```. The action uses a mysql database to allow the full use of WPUnit, the mysql details can be found in ```tests/wp-config.php```

## WPUnit

As mentioned the Boilerplate comes with WPUnit included and will install a version of WordPress for running tests through. As with WPUnit, you will need to ensure you have a database ready to use. If you look in the ```tests/wp-config.php``` file, you can change the username, password, host and table name. You will notice there are 2 sets of credentials, the first set are for GitHub actions. If you wish to change these, you will need to edit the ```.github/workflows/php.yaml``` to reflect this.

## File Structure

This Boilerplate is faily agnostic where and how you structure your code. Out of the box, this Boilerplate is set up with the following assumptions

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

You can find out more information about the Plugin Framework on its own [repo](https://github.com/Pink-Crab/Perqiue-Framework) or by visiting the [gitbook docs](https://glynn-quelch.gitbook.io/pinkcrab/).

## Contributions

If you would like to contribute to the Perique Framework and/or any of its packages, please feel to reach out at glynn@pinkcrab.co.uk or generate an issue/pr over on github. This package is manually updated every time we make substantial changes to the Core package.