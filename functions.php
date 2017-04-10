<?php
/*
*	Custom MailChimp API functions
*
*	Based around docs here (http://developer.mailchimp.com/documentation/mailchimp/reference/lists/)
*
*/

/* --------------------------------------------------------------------- */
function mc_subscribe( $mailchimp_auth, $userdata ){
// Subscribe

	$url = 'https://' . $mailchimp_auth->server . '.api.mailchimp.com/3.0/lists/' . $mailchimp_auth->list_id . '/members/';
	$rest = 'POST';

	$first_name = $userdata->first_name;
	$last_name = $userdata->last_name;
    $user_email = $userdata->user_email;

	// Get the 
	$content= array('email_address' => $user_email, 'status' => 'subscribed', 'merge_fields' => array( 'FNAME' => $first_name, 'LNAME' => $last_name ) );

	$input = json_encode( $content );

	// Sample data sent as $input
    // {"email_address": "urist.mcvankab@freddiesjokes.com","status": "subscribed","merge_fields": {"FNAME": "Urist","LNAME": "McVankab"}}

	$data = mailchimp_curl( $url, $mailchimp_auth->name, $rest, $input );
    
    if( $data !== 'error' ){

        // Get the customer's unique mailchimp ID and status
        $mailchimp_id = $data['id'];
        $mailchimp_email = $data['email_address'];
        $mailchimp_status = $data['status'];
        $mailchimp_interests = $data['interests']; // Creates an array of interests the users might have (set up in MailChimp)
        
         // Save last update time
        $time = intval( strtotime( current_time( 'mysql' ) ) );

        // Maybe save to WordPress?
        update_user_meta( $userdata->id, 'mailchimp_id', $mailchimp_id );
        update_user_meta( $userdata->id, 'mailchimp_email', $mailchimp_email ); // Cause sometimes users can be awkward
        update_user_meta( $userdata->id, 'mailchimp_status', $mailchimp_status );
        update_user_meta( $userdata->id, 'mailchimp_interests', $mailchimp_interests );
        update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
        
        // Run successful action -  subscribed
        do_action( 'mc_pref_subscribed' );
        
        return __('Successfully subscribed!', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }

}
/* ------------------------------------------------------------------------------------ */
function mc_unsub( $mailchimp_auth, $userdata ){
	// Unsub

	// Get the user's preferred MailChimp email
    if( $mailchimp_id = get_user_meta( $userdata->id, 'mailchimp_id', true ) ){
        
        $user_hash = $mailchimp_id;
        
    } elseif( $user_email = get_user_meta( $userdata->id, 'mailchimp_email', true ) ) {
       
        $user_hash = md5( strtolower( $user_email ) );
        
    } else {
        
        $user_email = get_user_meta( $userdata->id, $userdata->user_email, true );
        $user_hash = md5( strtolower( $user_email ) );
        
    }

	$url = 'https://' . $mailchimp_auth->server . '.api.mailchimp.com/3.0/lists/' . $mailchimp_auth->list_id . '/members/' . $user_hash;
	$rest = 'PATCH';

	// Tell MailChimp we're unsubbing this email address
	$content= array( 'email_address' => $user_email, 'status' => 'unsubscribed');

	$input = json_encode( $content ); // Make it so MailChimp understands

	// Run the request and get the response
	$data = mailchimp_curl( $url, $mailchimp_auth->name, $rest, $input );

    if( $data !== 'error' ){

        // Get the customer's new MailChimp status
        $mailchimp_status = $data['status'];

        // Save last update time
        $time = intval( strtotime( current_time( 'mysql' ) ) );
        
        // Maybe save to WordPress?
        update_user_meta( $userdata->id, 'mailchimp_status', $mailchimp_status );
        update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
        
        // Run successful action -  unsubscribed
        do_action( 'mc_pref_unsubscribed' );

        return __('Successfully unsubscribed', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }

}
/* ------------------------------------------------------------------------------------ */
function mc_update( $mailchimp_auth, $userdata ){
	// Add/Update interests

	// Get the user's preferred MailChimp email
    if( $mailchimp_id = get_user_meta( $userdata->id, 'mailchimp_id', true ) ){
        
        $user_hash = $mailchimp_id;
        
    } elseif( $user_email = get_user_meta( $userdata->id, 'mailchimp_email', true ) ) {
       
        $user_hash = md5( strtolower( $user_email ) );
        
    } else {
        
        $user_email = get_user_meta( $userdata->id, $userdata->user_email, true );
        $user_hash = md5( strtolower( $user_email ) );
        
    }

	$url = 'https://' . $mailchimp_auth->server . '.api.mailchimp.com/3.0/lists/' . $mailchimp_auth->list_id . '/members/' . $user_hash;
	$rest = 'PATCH';

    // Get user interests saved on WP side
	$interests = get_user_meta( $userdata->id, $mailchimp_interests, true );

	// Set up the response to send to MailChimp
	$content = array( 'email_address' => $user_email, 'status' => 'subscribed');

	$input = json_encode( $content ); // Make it so MailChimp understands
	
	// Run the request and get the response
	$data = mailchimp_curl( $url, $mailchimp_auth->name, $rest, $input );

    if( $data !== 'error'){
    
        // Get the customer's new MailChimp interests
        $mailchimp_interests = $data['interests'];

        // Save last update time
        $time = intval( strtotime( current_time( 'mysql' ) ) );

        // Maybe save to WordPress?
        update_user_meta( $userdata->id, 'mailchimp_interests', $mailchimp_interests );
        update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
        
        // Run successful action -  updated
        do_action( 'mc_pref_updated' );
        
        return __('Details successfully updated.', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }

}
/*-------------------------------------------------------------------------------*/
function mc_check( $mailchimp_auth, $userdata ){
	
	// Check subscriber status (in case someone unsubs)
    
    // Get original status
    $status = get_user_meta( $userdata->id, 'mailchimp_status', true );
    
    // Get current time and compare to anything saved
    $time = strtotime( current_time( 'mysql' ) );
    $last_check = get_user_meta( $userdata->id, 'mailchimp_update_time', true );
    
    $time_diff = $time - $last_check;
    
    // If we've not checked today then let's try it
    if( $time_diff > 86400 ){
    
        // Get the user's preferred MailChimp email
        if( $mailchimp_id = get_user_meta( $userdata->id, 'mailchimp_id', true ) ){

            $user_hash = $mailchimp_id;

        } elseif( $user_email = get_user_meta( $userdata->id, 'mailchimp_email', true ) ) {

            $user_hash = md5( strtolower( $user_email ) );

        } else {

            $user_email = get_user_meta( $userdata->id, $userdata->user_email, true );
            $user_hash = md5( strtolower( $user_email ) );

        }

        $url = 'https://' . $mailchimp_auth->server . '.api.mailchimp.com/3.0/lists/' . $mailchimp_auth->list_id . '/members/' . $user_hash;
        $rest = 'GET';

        // Run the checker
        $data = mailchimp_curl( $url, $mailchimp_auth->name, $rest );

        /*
        *
        *   Run the curl and get $data as a response
        */
        
        // If things have worked
        if( $data !== 'error'){
            
            $subscribed = $data['status'];
            
            if( $data['status'] == 'subscribed' ){
            
                // Get the customer's new MailChimp interests
                $mailchimp_interests = $data['interests'];


                // Maybe save to WordPress?
                update_user_meta( $userdata->id, 'mailchimp_status', 'subscribed' );
                update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
                update_user_meta( $userdata->id, 'mailchimp_interests', $mailchimp_interests );

                // Run successful action -  subscribed
                do_action( 'mc_pref_subscribed' );

                return __('Subscribed', 'mailchimp-prefs');
                
                
            } elseif ( $data['status'] == 'unsubscribed' ){
                
                update_user_meta( $userdata->id, 'mailchimp_status', 'unsubscribed' );
                
                // Run successful action -  registered
                do_action( 'mc_pref_unsubscribed' );
                
                return __('Unsubscribed', 'mailchimp-prefs');
                
            } else{
                
                return __('Request error', 'mailchimp-prefs');
                
            }
            
        } else {
            
            return __('CURL error', 'mailchimp-prefs');
            
        }
        
        
    } else {
        
        return $status;
        
    }
	
	
}
/*-------------------------------------------------------------------------------*/
function mc_register( $mailchimp_auth, $userdata, $signup ){
    
    // Check incase the user's email is already on file, and if it is then let's avoid the function
    if( ! get_user_by( 'email', $email ) ){

        // Create the user
        $user_id = wp_insert_user( $userdata ) ;
        
        // If user creation worked
        if( is_int( $user_id ) ){
            
            // If the user wanted to sign up to MailChimp
            if( $signup == 1 ){
                
                // Turn the $userdata array into an object so it appears like it would through WP
                $userdata = (object) $userdata;
                
                // Sign them up to MailChimp
                // $message = mc_subscribe( $mailchimp_auth, $userdata );
            
                if( $message !== 'error' ){
                    
                    
                    // Run successful action -  subscribed & registered
                    do_action( 'mc_pref_subscribed' );
                    do_action( 'mc_pref_registered' );

                    return __('Successfully registered and subscribed!', 'mailchimp-prefs');

                } else {

                    // Run successful action -  registered
                    do_action( 'mc_pref_registered' );
                    
                    return __('Successfully registered but subscription failed', 'mailchimp-prefs');

                }
                
                
            } else {
                
                // Run successful action -  registered
                do_action( 'mc_pref_registered' );
                
                // Successfully created site account but not MailChimp
                return __('Successfully registered', 'mailchimp-prefs');
                
                
            }
            
            
        } else {
            
            // User wasn't created
            return __('Registration failed', 'mailchimp-prefs');
            
        }
    
    
    } else {
        
        // User already exists
        return __('Already registered', 'mailchimp-prefs');
        
    }
    

    
}
/*-------------------------------------------------------------------------------*/
// Run before the headers and cookies are sent. 
add_action( 'after_setup_theme', 'mc_login' );
function mc_login(){
    
    // Log user in
    $result = wp_signon( array( 'user_login' => $_POST['username'], 'user_password' => $_POST['password'] ), true );

    // Handle the result
    if( is_wp_error( $result ) ){

        // Error, so turn back
        return false;

    } else {
        
        // Create the user so WordPress knows who's logged in
        wp_set_current_user( $result->id );

        // User logged in
        return true;


    }

}