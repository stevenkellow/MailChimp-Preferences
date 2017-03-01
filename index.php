<?php
/*
Plugin Name: MailChimp Preferences Dashboard
Plugin URI: http://www.stevenkellow.com/plugins/mailchimp/
Description: Custom functionality to check and update MailChimp subscriber settings on front end
Version: 0.1.0
Author: Steven Kellow
Author URI: http://www.stevenkellow.com
Text Domain: mailchimp-prefs
Domain Path: /languages
*/

// Define plugin path constant
define( 'MAILCHIMP_PREF_PATH', plugin_dir_path( __FILE__ ) );

// Load plugin textdomain for translations
add_action( 'init', 'mc_pref_textdomain' );
function mc_pref_textdomain() {
    load_plugin_textdomain( 'mailchimp-prefs', false, MAILCHIMP_PREF_PATH . '/lang' ); 
}

 if( is_admin() ){
	    
    // Call in the options page for the admin
    include(MAILCHIMP_PREF_PATH . 'options.php');

} else {


    // Call in the front end styles
    add_action( 'wp_enqueue_scripts', 'mc_pref_styles_scripts' );
    function mc_pref_styles_scripts() {
        $plugin_url = plugin_dir_url( __FILE__ );

        wp_enqueue_style( 'mailchimp-pref', $plugin_url . '/css/mailchimp_pref.min.css' ); // Main dashboard styles
        wp_enqueue_style( 'mailchimp-pref-tabs', $plugin_url . '/css/mailchimp-tabs.min.css' ); // Styles for tabbed pages
        
        wp_enqueue_script('mailchimp-pref-tabs', $plugin_url . '/js/mailchimp-tabs.min.js', array('jquery')); // Script for tabbed pages
        wp_enqueue_script('parsley', $plugin_url . '/js/parsley.min.js', array('jquery')); // Parsley for form validation
    }
        
}

// Wrap everything in a nice shortcode to make it easy to use
add_shortcode( 'mailchimp_dashboard', 'mailchimp_preferences' );
function mailchimp_preferences(){
	
    // $options = get_option( 'mc_pref_settings' );
    
	// Globals
    
    // Save the location of the plugin for use elsewhere
    if( ! defined( 'MAILCHIMP_PREF_PATH' ) ){
        define( 'MAILCHIMP_PREF_PATH', plugin_dir_path( __FILE__ ) );
    }
    
	$server = 'usxx'; // MailChimp server // $options['mc_pref_server'];
	$apikey = 'xxxxxxxxxxxxxxxxxxxxxx'; // API Key // $options['mc_pref_apikey'];
	$list_id = 'asdasd'; // List ID // $options['mc_pref_list_id']; // Maybe change this to a shortcode attribute?
	
	$user = 'user:' . $apikey;

	$user_id = get_current_user_id();
	$userdata = get_userdata( $user_id );
	$user_email = $userdata->user_email;
    $user_fname = $userdata->first_name;
    $user_lname = $userdata->last_name;
    
    /*---------------------------------------------*/
	
	// Call in the functions
	include(MAILCHIMP_PREF_PATH . 'functions.php');
    
	// Call in the curl shortcut
	include(MAILCHIMP_PREF_PATH . 'mailchimp_curl.php');
    
    /*---------------------------------------------*/
	
	/* -- FRONT-END FUNCTION -- */
    
    // Decide which view to launch
    
    if( ! is_user_logged_in() ){
        
        include(MAILCHIMP_PREF_PATH . '/views/logged-out.php');
        
        $shortcode_output = mc_pref_view_logged_out();
        
        
    } else {
        
        // If user's got a MailChimp ID saved then we're registered
        if( get_user_meta( $user_id, 'mailchimp_id' ) ){
            
            // Check whether the user is subscribed or not
            $status = mc_check();
            
            
            if( $status == 'subscribed'){
                
                // Subscribed and logged in - so show user preferences
                include(MAILCHIMP_PREF_PATH . '/views/logged-in-subbed.php');
                
                $shortcode_output = mc_pref_view_logged_in_subbed();
                
                
            } else {
                
                // Logged in but previously unsubscribed - so show resubscribe button
                include(MAILCHIMP_PREF_PATH . '/views/logged-in-unsubbed.php');
                
                $shortcode_output = mc_pref_view_logged_in_unsubbed();
                
            }
            
            
        } else {
            
            // Logged in but haven't signed up to MailChimp
            include(MAILCHIMP_PREF_PATH . '/views/logged-in-not-registered.php');
            
            $shortcode_output = mc_pref_view_logged_in_not_registered();
            
        }
           
    
    }
    
    
    // Let's output the content from the shortcode
    return $shortcode_output;
        
        
        
	// That's all folks!
	

}
