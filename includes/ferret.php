<?php

namespace Ferret;

if ( ! defined( 'WPINC' ) ) {
    die;
}

load_theme_textdomain( FERRET_PLUGIN_NAME, FERRET_PLUGIN_PATH . '/languages' );

$options = new Options();
$loader = new Loader();

if ( is_admin() ) {
    $plugin_admin = new Admin( $options );
    $loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
    $loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );
} else {
    $plugin_client = new Client( $options );
    $loader->add_action( 'wp_enqueue_scripts', $plugin_client, 'enqueue_scripts' );
}

$sentry = new SentryAdapter( $options );
$loader->run();
