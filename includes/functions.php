<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

function ferret_doing_it_wrong( string $message ) : void {
    add_action( 'admin_notices', function () use ( $message ) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php echo $message; ?></p>
        </div>
        <?php
    } );
}

