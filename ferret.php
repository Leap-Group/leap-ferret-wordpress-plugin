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
 * Version:           1.2.6
 * Author:            Aaron Arney
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
 * We want to use PHP 5.6 or greater. If the version isn't sufficient we show an error and then deactivate/prevent the
 * plugin from being activated.
 */
if ( ! version_compare( phpversion(), '5.6', '>=' ) ) {

    add_action( 'admin_init', function () {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    } );

    add_action( 'admin_notices', function () {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Ferret is not compatible with your version of PHP. You need at least PHP 5.6 or greater.',
                    'Ferret' ); ?></p>
        </div>
        <?php

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    } );

} else {

    require_once __DIR__ . '/vendor/autoload.php';

    define( 'FERRET_VERSION', '1.2.6' );

    function activate_wordpress_sentry() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-ferret-activator.php';
        Ferret_Activator::activate();
    }

    function deactivate_wordpress_sentry() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-ferret-deactivator.php';
        Ferret_Deactivator::deactivate();
    }

    register_activation_hook( __FILE__, 'activate_wordpress_sentry' );
    register_deactivation_hook( __FILE__, 'deactivate_wordpress_sentry' );

    require plugin_dir_path( __FILE__ ) . 'includes/class-ferret.php';

    /**
     * Begins execution of the plugin.
     *
     * @since    1.0.0
     */
    function run_ferret() {
        $plugin = new Ferret();
        $plugin->run();
    }

    run_ferret();
}
