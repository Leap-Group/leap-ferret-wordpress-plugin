<?php

/**
 * @link              https://wordpress.org/plugins/ferret
 * @since             1.0.0
 * @package           Ferret
 *
 * @wordpress-plugin
 * Plugin Name:       Ferret
 * Plugin URI:        https://wordpress.org/plugins/ferret
 * Description:       Reports all errors to the Sentry error logging service automatically.
 * Version:           2.1.0
 * Author:            LEAP Spark
 * Author URI:        https://leapsparkagency.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ferret
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * == :: WHAT'S THIS? :: ==========
 * We want to use PHP 7.1 or greater. If the version isn't sufficient we show an error and then deactivate/prevent the
 * plugin from being activated.
 */
if ( ! version_compare( phpversion(), '7.1', '>=' ) ) {

    add_action( 'admin_init', function () {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    } );

    add_action( 'admin_notices', function () {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Ferret is not compatible with your version of PHP. You need at least PHP 7.1 or greater.',
                    FERRET_PLUGIN_NAME ); ?></p>
        </div>
        <?php

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    } );

} else {

    require_once __DIR__ . '/vendor/autoload.php';

    define( 'FERRET_VERSION', '2.1.0' );
    define( 'FERRET_PLUGIN_NAME', 'ferret' );
    define( 'FERRET_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

    require_once FERRET_PLUGIN_PATH . 'includes/functions.php';
    require_once FERRET_PLUGIN_PATH . 'includes/options.php';
    require_once FERRET_PLUGIN_PATH . 'includes/loader.php';
    require_once FERRET_PLUGIN_PATH . 'includes/sentry-adapter.php';
    require_once FERRET_PLUGIN_PATH . 'admin/admin.php';
    require_once FERRET_PLUGIN_PATH . 'public/client.php';
    require_once FERRET_PLUGIN_PATH . 'includes/ferret.php';

}
