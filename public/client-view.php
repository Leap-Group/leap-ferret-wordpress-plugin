<?php
$project   = $this->options->get( $this->options->project_field_name ) ?? '';
$dsn       = $this->options->get( $this->options->dsn_field_name ) ?? '';
$user      = wp_get_current_user();
?>

<script>
    window.onload = function () {
        try {
            Sentry.init({
                dsn: 'https://<?php echo $dsn; ?>@sentry.io/<?php echo $project; ?>'
            });

            <?php if ( $user ) : ?>
            Sentry.configureScope((scope) => {
                scope.setUser({
                    'id': <?php echo $user->ID; ?>,
                    'username': '<?php echo $user->user_login; ?>',
                    'email': '<?php echo $user->user_email; ?>',
                });
            });
            <?php endif; ?>
        } catch (error) {
            console.warn(error);
        }
    };
</script>

