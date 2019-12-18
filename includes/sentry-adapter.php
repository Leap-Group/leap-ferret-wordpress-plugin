<?php
/**
 *
 */
namespace Ferret;

if ( ! defined( 'WPINC' ) ) {
    die;
}

use function Sentry\init;
use Sentry\State\Scope;
use Sentry\Event;
use function Sentry\configureScope;
use function wp_get_current_user;

/**
 * Class SentryAdapter
 */
final class SentryAdapter {

    /**
     * @var Options
     */
    private $options;

    /**
     * SentryAdapter constructor.
     *
     * @param Options $options
     */
    public function __construct( Options $options ) {
        $this->options = $options;

        $this->init_sentry();
    }

    public function before_send_callback( Event $event ) : ?Event {
        $ignore_core_errors = $this->options->get( $this->options->ignore_core_field_name );

        if ( $ignore_core_errors && preg_match( '/(\/wp-admin\/)/', $event->getRequest()['url'] ) ) {
            return null;
        }

        if ( $ignore_core_errors && preg_match( '/(\/wp-includes\/)/', $event->getRequest()['url'] ) ) {
            return null;
        }

        $this->maybe_add_user_context( $event );

        return $event;
    }

    /**
     * Get the connection string.
     *
     * @return string|null
     */
    private function get_connection_string() : ?string {
        $dsn_id = $this->options->get( $this->options->dsn_field_name );
        $project = $this->options->get( $this->options->project_field_name );

        if ( ! $dsn_id || ! $project ) {
            ferret_doing_it_wrong( __( 'Ferret is not configured with a DSN key. Be aware that logging to Sentry is disabled until this key is added!', FERRET_PLUGIN_NAME ) );
            return null;
        }

        return sprintf( 'https://%s@sentry.io/%s', $dsn_id, $project );
    }

    /**
     * Initialize Sentry
     */
    private function init_sentry() : void {
        $connection_string = $this->get_connection_string();

        if ( is_null( $connection_string ) ) {
            return;
        }

        init( array(
            'dsn'         => $connection_string,
            'environment' => $this->get_environment(),
            'before_send' => array( $this, 'before_send_callback' ),
        ) );
    }

    /**
     * Get the environment.
     *
     * @return string
     */
    private function get_environment() : string {
        return $this->options->get( $this->options->environment_field_name )
            ? 'Debug'
            : 'Production';
    }

    /**
     * Add user context to the event if a user is logged in.
     *
     * @param Event $event
     *
     * @return Event
     */
    private function maybe_add_user_context( Event $event ) : Event {
        if ( ! wp_get_current_user() ) {
            return $event;
        }

        configureScope( function ( Scope $scope ) : void {
            $scope->setUser( array(
                'id'         => wp_get_current_user()->ID,
                'username'   => wp_get_current_user()->user_login,
                'email'      => wp_get_current_user()->user_email,
                'ip_address' => $this->get_user_ip(),
            ) );
        });

        return $event;
    }

    /**
     * Get an IP associated with the user, or fallback to the server IP.
     *
     * @return string
     */
    private function get_user_ip() : string {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}
