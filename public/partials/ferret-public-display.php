<?php
$dsn = $this->options->get( $this->options->dsn_field_name );
$project = $this->options->get( $this->options->project_field_name );
?>

<script>
    window.onload = function () {
        try {
            Sentry.init({
                dsn: 'https://<?php echo $dsn; ?>@sentry.io/<?php echo $project; ?>'
            });
        } catch (error) {

        }
    };
</script>
