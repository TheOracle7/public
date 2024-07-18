<div class="wrap" id='form-entry-container'>
	<h1><?php esc_html_e('Divi Forms Settings', 'forms-for-divi');?></h1>
	<?php
        // phpcs:ignore
        echo settings_errors();
    ?>
	<hr>
	<form method="post" action="options.php">

		<?php
            settings_fields('wpt_google_recaptcha_fields');
            do_settings_sections('wpt_google_recaptcha_fields');
            submit_button();
        ?>

	</form>
</div>