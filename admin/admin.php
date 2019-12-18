<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link        https://wordpress.org/plugins/ferret
 * @since      1.0.0
 *
 * @package    Ferret
 * @subpackage Ferret/admin
 */

namespace Ferret;

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ferret
 * @subpackage Ferret/admin
 * @author     Aaron Arney <aarney@leapsparkagency.com>
 */
final class Admin {

    /**
     * @var Options
     */
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since  1.0.0
     *
     * @param  Options $options     An instance of the options class
     */
    public function __construct( Options $options ) {
        $this->options = $options;
    }

    /**
     * Validate all plugin settings.
     *
     * @since   1.0.0
     *
     * @return  array $valid The validated options array.
     */
    public function validate_settings() {
        $valid = array();
        $data = filter_var_array( $_POST, FILTER_SANITIZE_STRING );

        $project = $data[ FERRET_PLUGIN_NAME ][ $this->options->project_field_name ] ?? '';
        $dsn     = $data[ FERRET_PLUGIN_NAME ][ $this->options->dsn_field_name ] ?? '';

        $ignore_core       = $data[ FERRET_PLUGIN_NAME ][ $this->options->ignore_core_field_name ] ?? false;
        $enable_js_logging = $data[ FERRET_PLUGIN_NAME ][ $this->options->enable_js_field_name ] ?? false;
        $environment       = $data[ FERRET_PLUGIN_NAME ][ $this->options->environment_field_name ] ?? false;

        $valid[ $this->options->dsn_field_name ]         = trim( $dsn );
        $valid[ $this->options->project_field_name ]     = trim( $project );
        $valid[ $this->options->ignore_core_field_name ] = (bool) $ignore_core;
        $valid[ $this->options->enable_js_field_name ]   = (bool) $enable_js_logging;
        $valid[ $this->options->environment_field_name ] = (bool) $environment;

        return $valid;
    }

    /**
     * Add the options page to the menu
     *
     * @since 1.0.0
     */
    public function add_admin_menu() {
        add_options_page(
            __( 'Ferret Settings', FERRET_PLUGIN_NAME ),
            __( 'Ferret Settings', FERRET_PLUGIN_NAME ),
            'manage_options',
            FERRET_PLUGIN_NAME,
            array( $this, 'options_page' )
        );
    }

    /**
     * Include the admin partial to display the settings form.
     *
     * @since 1.0.0
     */
    public function options_page() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin-view.php';
    }

    /**
     * Register the settings and setting groups
     *
     * @since 1.0.0
     */
    public function settings_init() {
        register_setting(
            'settingsPage',
            FERRET_PLUGIN_NAME,
            array(
                'sanitize_callback' => array( $this, 'validate_settings' ),
            )
        );

        add_settings_section(
            FERRET_PLUGIN_NAME . '_settingsPage_section',
            __( 'General Options', FERRET_PLUGIN_NAME ),
            null,
            'settingsPage'
        );

        add_settings_field(
            $this->options->dsn_field_name,
            __( 'Sentry DSN', FERRET_PLUGIN_NAME ),
            array( $this, 'dsn_render' ),
            'settingsPage',
            FERRET_PLUGIN_NAME . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->project_field_name,
            __( 'Sentry Project ID', FERRET_PLUGIN_NAME ),
            array( $this, 'project_render' ),
            'settingsPage',
            FERRET_PLUGIN_NAME . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->ignore_core_field_name,
            __( 'Ignore WP Core Errors', FERRET_PLUGIN_NAME ),
            array( $this, 'ignore_core_render' ),
            'settingsPage',
            FERRET_PLUGIN_NAME . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->enable_js_field_name,
            __( 'Enable JS Logging', FERRET_PLUGIN_NAME ),
            array( $this, 'enable_js_render' ),
            'settingsPage',
            FERRET_PLUGIN_NAME . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->environment_field_name,
            __( 'Debug Environment', FERRET_PLUGIN_NAME ),
            array( $this, 'environment_render' ),
            'settingsPage',
            FERRET_PLUGIN_NAME . '_settingsPage_section'
        );
    }

    /**
     * Render the DSN field
     *
     * @since 1.0.0
     */
    public function dsn_render() {
        $value = esc_html( $this->options->get( $this->options->dsn_field_name ) );

        ?>
        <div class="control">
            <input
                class="input"
                name="<?php echo FERRET_PLUGIN_NAME . '[' . $this->options->dsn_field_name; ?>]"
                id="<?php echo FERRET_PLUGIN_NAME . '-' . $this->options->dsn_field_name; ?>"
                type="text"
                value="<?php echo isset( $value ) ? $value : ''; ?>"
            />
        </div>
        <?php
    }

    /**
     * Render the Enable JS field
     *
     * @since 1.0.0
     */
    public function enable_js_render() {
        $value = esc_html( $this->options->get( $this->options->enable_js_field_name ) );

        ?>
        <div class="control">
            <input type="checkbox"
                   name="<?php echo FERRET_PLUGIN_NAME . '[' . $this->options->enable_js_field_name;?>]"
                   id="<?php echo FERRET_PLUGIN_NAME . '-' .$this->options->enable_js_field_name;?>"
                   value="1"
                <?php checked( '1', $value ); ?>
            />
        </div>
        <?php
    }

    /**
     * Render the environment field
     *
     * @since 1.0.0
     */
    public function environment_render() {
        $value = esc_html( $this->options->get( $this->options->environment_field_name ) );

        ?>
        <div class="control">
            <input type="checkbox"
                   name="<?php echo FERRET_PLUGIN_NAME . '[' . $this->options->environment_field_name;?>]"
                   id="<?php echo FERRET_PLUGIN_NAME . '-' .$this->options->environment_field_name;?>"
                   value="1"
                <?php checked( '1', $value ); ?>
            />
        </div>
        <?php
    }

    /**
     * Render the environment field
     *
     * @since 1.0.0
     */
    public function ignore_core_render() {
        $value = esc_html( $this->options->get( $this->options->ignore_core_field_name ) );

        ?>
        <div class="control">
            <input type="checkbox"
                   name="<?php echo FERRET_PLUGIN_NAME . '[' . $this->options->ignore_core_field_name;?>]"
                   id="<?php echo FERRET_PLUGIN_NAME . '-' .$this->options->ignore_core_field_name;?>"
                   value="1"
                <?php checked( '1', $value ); ?>
            />
        </div>
        <?php
    }
    /**
     * Render the Project ID field.
     *
     * @since 1.0.0
     */
    public function project_render() {
        $value = esc_html( $this->options->get( $this->options->project_field_name ) );

        ?>
        <div class="control">
            <input
                class="input"
                name="<?php echo FERRET_PLUGIN_NAME . '[' . $this->options->project_field_name; ?>]"
                id="<?php echo FERRET_PLUGIN_NAME . '-' . $this->options->project_field_name; ?>"
                type="text"
                value="<?php echo isset( $value ) ? $value : ''; ?>"
            />
        </div>
        <?php
    }
}
