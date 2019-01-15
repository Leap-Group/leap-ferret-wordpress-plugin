<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link        https://wordpress.org/plugins/ferret
 * @since      1.0.0
 *
 * @package    Ferret
 * @subpackage Ferret/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ferret
 * @subpackage Ferret/includes
 * @author     Aaron Arney <aarney@leapsparkagency.com>
 */
class Ferret {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ferret_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * @since 1.0.0
     * @access protected
     * @var string $sentry The sentry instance.
     */
    protected $sentry;

    /**
     * @since 1.0.0
     * @access protected
     * @var Ferret_Options The Options class instance.
     */
    protected $options;

    /**
     * @since 1.2.0
     * @access private
     * @var string $exception Contains exception messages.
     */
    private $exception;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'FERRET_VERSION' ) ) {
            $this->version = FERRET_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'ferret';

        $this->load_dependencies();
        $this->init_sentry();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for encapsulating plugin option access.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ferret-options.php';
        $this->options = new Ferret_Options( $this->get_plugin_name() );

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ferret-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ferret-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ferret-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ferret-public.php';

        $this->loader = new Ferret_Loader();
    }

    /**
     * Initialize the Sentry client.
     *
     * @since 1.0.0
     */
    public function init_sentry() {
        $dsn_id = $this->options->get( $this->options->dsn_field_name );
        $project_id = $this->options->get( $this->options->project_field_name );

        if ( ! $dsn_id || ! $project_id ) {
            $this->loader->add_action( 'admin_notices', $this, 'display_admin_notice' );
        }

        $environment = $this->options->get( $this->options->environment_field_name )
            ? 'Debug'
            : 'Production';
        $connection_string = 'https://' . $dsn_id . '@sentry.io/' . $project_id;

        try {
            $this->sentry = new Raven_Client( $connection_string, array(
                'environment' => $environment,
            ) );

            $error_handler = new Raven_ErrorHandler( $this->sentry );
            $error_handler->registerExceptionHandler();
            $error_handler->registerErrorHandler();
            $error_handler->registerShutdownFunction();
        } catch (Exception $exception) {
            $this->exception = $exception->getMessage();
            $this->loader->add_action( 'admin_notices', $this, 'display_error_admin_notice' );
        }
    }

    /**
     * Display admin notice about instantiation.
     *
     * @since 1.2.0
     */
    public function display_error_admin_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Ferret could not be initialized. There was a problem activating the Sentry SDK. Reason: '. $this->exception ,
                    'ferret' ); ?></p>
        </div>
        <?php
    }

    /**
     * Display an admin notice.
     *
     * @since 1.0.0
     */
    public function display_admin_notice() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e( 'Ferret is not configured with a DSN key. Be aware that logging to Sentry is disabled until this key is added!', 'ferret' ); ?></p>
        </div>
        <?php
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Ferret_Admin( $this->get_plugin_name(), $this->get_version(),
            $this->options );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Ferret_Public( $this->get_plugin_name(), $this->get_version(), $this->options );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Ferret_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
