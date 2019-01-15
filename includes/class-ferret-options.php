<?php

/**
 * The options interface
 *
 * @link        https://wordpress.org/plugins/ferret
 * @since      1.0.0
 *
 * @package    Ferret
 * @subpackage Ferret/public
 */

/**
 * The options interface
 *
 * Defines options names as well as getters and setters
 *
 * @package    Ferret
 * @subpackage Ferret/public
 * @author     Aaron Arney <aarney@leapsparkagency.com>
 */
final class Ferret_Options {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The ID of the dsn field.
     *
     * @since    1.0.0
     * @access   public
     * @var      string    $dsn_field_name    The ID  of the dsn field.
     */
    public $dsn_field_name = 'dsn';

    /**
     * The ID of the enable js field.
     *
     * @since    1.0.0
     * @access   public
     * @var      string    $dsn_field_name    The ID  of the enable js field.
     */
    public $enable_js_field_name = 'enable_js';

    /**
     * The ID of the sentry project field.
     *
     * @since    1.0.0
     * @access   public
     * @var      string    $project_field_name    The ID  of the sentry project.
     */
    public $project_field_name = 'project';

    /**
     * The ID of the environment field.
     *
     * @since 1.0.0
     * @access public
     * @var string $environment_field_name The ID of the environment.
     */
    public $environment_field_name = 'environment';

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   string  $plugin_name    The name of the plugin.
     */
    public function __construct( $plugin_name ) {
        $this->plugin_name = $plugin_name;
    }

    /**
     * Get a value from the options array
     *
     * @since   1.0.0
     * @param   string  $key    The key of the option.
     * @return  string | false
     */
    public function get( $key ) {
        $options = get_option( $this->plugin_name );

        return $options[ $key ] ? $options[ $key ] : false;
    }
}
