<?php
add_action( 'admin_menu', 'mc_pref_add_admin_menu' );
add_action( 'admin_init', 'mc_pref_settings_init' );


function mc_pref_add_admin_menu() { 

	add_menu_page( 'MailChimp Preferences', 'MailChimp Preferences', 'manage_options', 'mailchimp_preferences', 'mc_pref_options_page' );

}


function mc_pref_settings_init() { 

	register_setting( 'mcPrefSettings', 'mc_pref_settings' );

	add_settings_section(
		'mc_pref_mcPrefSettings_section', 
		__( 'MailChimp account settings', 'mailchimp-preferences' ), 
		'mc_pref_settings_section_callback', 
		'mcPrefSettings'
	);

	add_settings_field( 
		'mc_pref_server', 
		__( 'Choose your MailChimp server type', 'mailchimp-preferences' ), 
		'mc_pref_server_render', 
		'mcPrefSettings', 
		'mc_pref_mcPrefSettings_section' 
	);

	add_settings_field( 
		'mc_pref_apikey', 
		__( 'Enter your MailChimp API Key', 'mailchimp-preferences' ), 
		'mc_pref_apikey_render', 
		'mcPrefSettings', 
		'mc_pref_mcPrefSettings_section' 
	);

	add_settings_field( 
		'mc_pref_list_id', 
		__( 'Choose the list you want to use', 'mailchimp-preferences' ), 
		'mc_pref_list_id_render', 
		'mcPrefSettings', 
		'mc_pref_mcPrefSettings_section' 
	);


}


function mc_pref_server_render() { 

	$options = get_option( 'mc_pref_settings' );
	ob_start();

    // For each server option check if it's selected and then pre-select it
    ?>
    <label for="mc_pref_settings[mc_pref_server]"><?php _e('Server:', 'mailchimp-prefs'); ?></label>
    <select name="mc_pref_settings[mc_pref_server]">
    <?php

    // Get the currently set server
    $value = $options['mc_pref_server'];

    for ($i = 1; $i <= 15; $i++) {
        
        $curr_server = 'us' . $i;

        if( $curr_server == $value ){

             echo '<option value="us' . $i . '" selected>us' . $i . '</option>' . PHP_EOL;

        } else {

             echo '<option value="us' . $i . '">us' . $i . '</option>' . PHP_EOL;

        }

    }

    ?>
    </select>

    <?php

    $server_option = ob_get_clean();

    echo $server_option;

}


function mc_pref_apikey_render() { 

	$options = get_option( 'mc_pref_settings' );
	?>
    <label for="mc_pref_settings[mc_pref_apikey]"><?php _e('API key:', 'mailchimp-prefs'); ?></label>
	<input type='text' name='mc_pref_settings[mc_pref_apikey]' id="apikey" value='<?php echo $options['mc_pref_apikey']; ?>'>
	<?php

}


function mc_pref_list_id_render() { 

	$options = get_option( 'mc_pref_settings' );
	?>
    <label for="mc_pref_settings[mc_pref_list_id]"><?php _e('List ID:', 'mailchimp-prefs'); ?></label>
	<input type='text' name='mc_pref_settings[mc_pref_list_id]' value='<?php echo $options['mc_pref_list_id']; ?>'>
	<?php

}


function mc_pref_settings_section_callback() { 

	echo __( 'Enter your API details to connect your site with MailChimp', 'mailchimp-preferences' );

}


function mc_pref_options_page() { 

	?>
    <div class="mailchimp_pref_wrap">
	<form action='options.php' method='post'>

		<h1><?php _e('MailChimp Preferences', 'mailchimp-prefs'); ?></h1>

		<?php
		settings_fields( 'mcPrefSettings' );
		do_settings_sections( 'mcPrefSettings' );
		submit_button();
		?>

	</form>
    </div>
	<?php

}

?>