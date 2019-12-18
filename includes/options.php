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

namespace Ferret;

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The options interface
 *
 * Defines options names as well as getters and setters
 *
 * @package    Ferret
 * @subpackage Ferret/public
 * @author     Aaron Arney <aarney@leapsparkagency.com>
 */
final class Options {

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
     * @since 2.0.0
     * @access public
     * @var string
     */
    public $ignore_core_field_name = 'ignore_core';

    /**
     * Get a value from the options array
     *
     * @since   1.0.0
     * @param   string  $key    The key of the option.
     * @return  string | null
     */
    public static function get( $key ) {
        $options = get_option( FERRET_PLUGIN_NAME );

        return $options[ $key ] ?? null;
    }
}
