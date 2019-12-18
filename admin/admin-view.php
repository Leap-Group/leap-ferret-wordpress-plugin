<?php

/**
 * Provide a admin area view for the plugin
 *
 * @link       https://wordpress.org/plugins/ferret
 * @since      1.0.0
 *
 * @package    Ferret
 * @subpackage Ferret/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form action="options.php" method="POST">
        <p><?php _e( 'The settings here allow you to set up your DSN and Project ID, as well as enable the Sentry 
        JavaScript SDK to capture JavaScript errors. Lastly, you can switch your environment from `Debug` to `Production`.' ); ?></p>

        <?php
        settings_fields( 'settingsPage' );
        do_settings_sections( 'settingsPage' );
        ?>

        <div class="control">
            <?php submit_button(
                __( 'Save all changes', FERRET_PLUGIN_NAME ),
                'primary',
                'submit',
                true,
                array(
                    'class' => 'button is-primary',
                ) ); ?>
        </div>
    </form>
</div>
