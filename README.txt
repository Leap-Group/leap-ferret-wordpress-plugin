=== Plugin Name ===
Contributors: leapspark
Tags: sentry, debugging, logging, errors, error handling
Requires at least: 3.0.1
Tested up to: 5.1.1
Stable tag: 1.2.4
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Ferret is a simple wrapper for the [Sentry](https://sentry.io) PHP and JavaScript SDKs. It will catch all PHP errors, as well as JavaScript errors if the option is switched on.

== Description ==

Ferret is a simple wrapper for the Sentry PHP and JavaScript SDKs. It will catch all PHP errors, as well as JavaScript errors if the option is switched on. It utilizes the legacy PHP library by Sentry intentionally, in an effort to maximize compatibility. The newest SDK requires PHP^7.1 whereas the legacy requires minimum of 5.6

There are four settings that you can adjust.

- The `DSN` - Your DSN key, this is required
- `Project ID` - The project key, this is also required
- `Enable JavaScript Logging` - Enable logging of JavaScript errors
- `Debug Environment` - Switch the Sentry environment to `Debug`, turning this off puts the environment to `Production`

All three are accessible on the plugin settings page located in the `Settings -> Ferret Settings` page.


== Installation ==

1. Upload `wordpress-sentry.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the settings page through `Settings -> Ferret Settings` and enter your Sentry DSN and Project ID

== Changelog ==

= 1.2.4 =
* Remove ?? operators
* Fix PHP version error notice to reflect 5.6 requirement
* Code formatting

= 1.2.3 =
* Fix the required PHP version
* Fix typos in README

= 1.2.0 =
* Documentation
* Remove PHP7 features, specifically null coalesce operators
* Downgrade minimum PHP version to 5.6
* Add error handling to Sentry initialization

= 1.1.6 =
* Documentation

= 1.1.5 =
* Fix some issues related to file naming
* Sort out some kinks with adjusting to svn workflow

= 1.0.0 =
* Initial commit
