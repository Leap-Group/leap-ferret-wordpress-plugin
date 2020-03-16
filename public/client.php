<?php
/**
 *
 */

namespace Ferret;

/**
 * Class Client
 * @package Ferret
 */
final class Client {

    /**
     * @var Options
     */
    private $options;

    /**
     * Client constructor.
     *
     * @param Options $options
     */
    public function __construct( Options $options ) {
        $this->options = $options;
    }

    /**
     *
     */
    private function display() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/client-view.php';
    }

    /**
     *
     */
    public function enqueue_scripts() : void {
        $enable_js = $this->options->get( $this->options->enable_js_field_name );

        if ( ! $enable_js ) {
            return;
        }

        wp_enqueue_script( FERRET_PLUGIN_NAME, 'https://browser.sentry-cdn.com/5.14.1/bundle.min.js',
            array(), FERRET_VERSION, false );

        /**
         * We need to set the crossorigin and lazy-load attributes to the script tag. Unfortunately,
         * setting them via `wp_script_add_data` was not working. I have not figured out why, but
         * ideally would like to use that instead.
         */
        add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
            if ( FERRET_PLUGIN_NAME === $handle ) {
                $tag = '<script src=' . $src . '" crossorigin="anonymous"></script>';
            }
            return $tag;
        }, 10, 3 );

        $this->display();
    }
}
