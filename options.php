<?php
add_action( 'admin_menu', 'mc_pref_add_admin_menu' );
add_action( 'admin_init', 'mc_pref_settings_init' );


function mc_pref_add_admin_menu(  ) { 

	add_menu_page( 'MailChimp Preferences', 'MailChimp Preferences', 'manage_options', 'mailchimp_preferences', 'mc_pref_options_page' );

}


function mc_pref_settings_init() { 

	register_setting( 'pluginPage', 'mc_pref_settings' );

	add_settings_section(
		'mc_pref_pluginPage_section', 
		__( 'Your section description', 'mailchimp-preferences' ), 
		'mc_pref_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'mc_pref_server', 
		__( 'Choose your MailChimp server type', 'mailchimp-preferences' ), 
		'mc_pref_server_render', 
		'pluginPage', 
		'mc_pref_pluginPage_section' 
	);

	add_settings_field( 
		'mc_pref_apikey', 
		__( 'Enter your MailChimp API Key', 'mailchimp-preferences' ), 
		'mc_pref_apikey_render', 
		'pluginPage', 
		'mc_pref_pluginPage_section' 
	);

	add_settings_field( 
		'mc_pref_list_id', 
		__( 'Choose the list you want to use', 'mailchimp-preferences' ), 
		'mc_pref_list_id_render', 
		'pluginPage', 
		'mc_pref_pluginPage_section' 
	);


}


function mc_pref_server_render() { 

	$options = get_option( 'mc_pref_settings' );
	ob_start();

    // For each server option check if it's selected and then pre-select it
    ?>
    <label for="server_dropdown">Server:</label>
    <select name="server_dropdown">
    <?php

    // Get the currently set server
    $value = $options['mc_pref_server'];

    for ($i = 1; $i <= 14; $i++) {

        if( $i == $value ){

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
    <label for="mc_pref_settings[mc_pref_apikey]">API Key:</label>
	<input type='text' name='mc_pref_settings[mc_pref_apikey]' value='<?php echo $options['mc_pref_apikey']; ?>'>
	<?php

}


function mc_pref_list_id_render() { 

	$options = get_option( 'mc_pref_settings' );
	?>
    <label for="mc_pref_settings[mc_pref_list_id]">List ID:</label>
	<input type='text' name='mc_pref_settings[mc_pref_list_id]' value='<?php echo $options['mc_pref_list_id']; ?>'>
	<?php

}


function mc_pref_settings_section_callback(  ) { 

	echo __( 'Enter your API details to connect your site with MailChimp', 'mailchimp-preferences' );

}


function mc_pref_options_page() { 

	?>
	<form action='options.php' method='post'>

		<h2>MailChimp Preferences</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>