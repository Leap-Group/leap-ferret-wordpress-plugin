<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link        https://wordpress.org/plugins/ferret
 * @since      1.0.0
 *
 * @package    Ferret
 * @subpackage Ferret/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ferret
 * @subpackage Ferret/public
 * @author     Aaron Arney <aarney@leapsparkagency.com>
 */
class Ferret_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * The options instance
     *
     * @since    1.0.0
     * @access   private
     * @var      Ferret_Options $options An instance of the options class.
     */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 *
	 * @param   string        $plugin_name The name of the plugin.
	 * @param   string        $version     The version of this plugin.
     * @param  Ferret_Options $options     An instance of the options class
	 */
	public function __construct( $plugin_name, $version, $options ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = $options;
	}

    /**
     * Render the script initializer web page.
     *
     * @since 1.0.0
     */
	public function display() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/ferret-public-display.php';
    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	    if (
	        $this->options->get( $this->options->dsn_field_name )
            && $this->options->get( $this->options->enable_js_field_name )
            && $this->options->get( $this->options->project_field_name )
        ) {
            wp_enqueue_script( $this->plugin_name, 'https://browser.sentry-cdn.com/4.4.2/bundle.min.js',
                array(), $this->version, false );

            /**
             * We need to set the crossorigin and lazy-load attributes to the script tag. Unfortunately,
             * setting them via `wp_script_add_data` was not working. I have not figured out why, but
             * ideally would like to use that instead.
             */
            add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
                if ( $this->plugin_name === $handle ) {
                    $tag = '<script src=' . $src . '" crossorigin="anonymous"></script>';
                }

                return $tag;
            }, 10, 3 );

            $this->display();
        }
	}
}
