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
class Ferret_Admin {

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
     * @since  1.0.0
     *
     * @param  string         $plugin_name The name of this plugin.
     * @param  string         $version     The version of this plugin.
     * @param  Ferret_Options $options     An instance of the options class
     */
    public function __construct( $plugin_name, $version, $options ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
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

        $valid[$this->options->dsn_field_name] = trim( esc_html(
                $data[$this->plugin_name][$this->options->dsn_field_name] ) );
        $valid[$this->options->project_field_name] = trim( esc_html( $data[$this->plugin_name][$this->options->project_field_name] ) );
        $valid[$this->options->enable_js_field_name] = (bool) $data[$this->plugin_name][$this->options->enable_js_field_name];
        $valid[$this->options->environment_field_name] = (bool) $data[$this->plugin_name][$this->options->environment_field_name];

        return $valid;
    }

    /**
     * Add the options page to the menu
     *
     * @since 1.0.0
     */
    public function add_admin_menu() {
        add_options_page(
            'Ferret Settings',
            'Ferret Settings',
            'manage_options',
            'Ferret',
            array( $this, 'options_page' )
        );
    }

    /**
     * Include the admin partial to display the settings form.
     *
     * @since 1.0.0
     */
    public function options_page() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ferret-admin-display.php';
    }

    /**
     * Register the settings and setting groups
     *
     * @since 1.0.0
     */
    public function settings_init() {
        register_setting(
            'settingsPage',
            $this->plugin_name,
            array(
                'sanitize_callback' => array( $this, 'validate_settings' ),
            )
        );

        add_settings_section(
            $this->plugin_name . '_settingsPage_section',
            __( 'General Options', 'Ferret' ),
            null,
            'settingsPage'
        );

        add_settings_field(
            $this->options->dsn_field_name,
            __( 'Sentry DSN', 'Ferret' ),
            array( $this, 'dsn_render' ),
            'settingsPage',
            $this->plugin_name . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->project_field_name,
            __( 'Sentry Project ID', 'Ferret' ),
            array( $this, 'project_render' ),
            'settingsPage',
            $this->plugin_name . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->enable_js_field_name,
            __( 'Enable JS Logging', 'Ferret' ),
            array( $this, 'enable_js_render' ),
            'settingsPage',
            $this->plugin_name . '_settingsPage_section'
        );

        add_settings_field(
            $this->options->environment_field_name,
            __( 'Debug Environment', 'Ferret' ),
            array( $this, 'environment_render' ),
            'settingsPage',
            $this->plugin_name . '_settingsPage_section'
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
                name="<?php echo $this->plugin_name . '[' . $this->options->dsn_field_name; ?>]"
                id="<?php echo $this->plugin_name . '-' . $this->options->dsn_field_name; ?>"
                type="text"
                value="<?php echo $value ?? ''; ?>"
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
                   name="<?php echo $this->plugin_name . '[' . $this->options->enable_js_field_name;?>]"
                   id="<?php echo $this->plugin_name . '-' .$this->options->enable_js_field_name;?>"
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
                   name="<?php echo $this->plugin_name . '[' . $this->options->environment_field_name;?>]"
                   id="<?php echo $this->plugin_name . '-' .$this->options->environment_field_name;?>"
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
                name="<?php echo $this->plugin_name . '[' . $this->options->project_field_name; ?>]"
                id="<?php echo $this->plugin_name . '-' . $this->options->project_field_name; ?>"
                type="text"
                value="<?php echo $value ?? ''; ?>"
            />
        </div>
        <?php
    }
}
