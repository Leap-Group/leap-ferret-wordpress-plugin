=== Plugin Name ===
Contributors: leapspark
Tags: sentry, debugging, logging, errors, error handling
Requires at least: 3.0.1
Tested up to: 5.0.3
Stable tag: 1.1.4
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Ferret is a simple wrapper for the [Sentry](https://sentry.io) PHP and JavaScript SDKs. It will catch all PHP errors, as well as JavaScript errors if the option is switched on.

== Description ==

Ferret is a simple wrapper for the Sentry PHP and JavaScript SDKs. It will catch all PHP errors, as well as JavaScript errors if the option is switched on.

There are four settings that you can adjust.

- The `DSN` - Your DSN key, this is required
- `Project ID` - The project key, this is also required
- `Enable JavaScript Logging` - Enable logging of JavaScript errors
- `Debug Environment` - Switch the Sentry environent to `Debug`, turning this off puts the environment to `Production`

All three are accessible on the plugin settings page located in the `Settings -> Ferret Settings` page.


== Installation ==

1. Download the plugin
1. Upload the `ferret` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the settings page through `Settings -> Ferret Settings` and enter your Sentry DSN and Project ID

Alternatively, search for `ferret` in the plugin directory through the WordPress plugin admin dashboard.

== Changelog ==

= 1.0.0 =
* Initial commit
