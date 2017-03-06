<?php
/*
*	Custom MailChimp API functions
*
*	Based around docs here (http://developer.mailchimp.com/documentation/mailchimp/reference/lists/)
*
*/

/* --------------------------------------------------------------------- */
if (!function_exists('mc_subscribe')) {
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
        $time = strtotime( current_time( 'mysql' ) );

        // Maybe save to WordPress?
        update_user_meta( $userdata->id, 'mailchimp_id', $mailchimp_id );
        update_user_meta( $userdata->id, 'mailchimp_email', $mailchimp_email ); // Cause sometimes users can be awkward
        update_user_meta( $userdata->id, 'mailchimp_status', $mailchimp_status );
        update_user_meta( $userdata->id, 'mailchimp_interests', $mailchimp_interests );
        update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
        
        return __('Successfully subscribed!', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }

}
}
/* ------------------------------------------------------------------------------------ */
if (!function_exists('mc_unsub')) {
function mc_unsub( $mailchimp_auth, $userdata ){
	// Unsub

	// Get the user's preferred MailChimp email
    if( $mailchimp_id = get_user_meta( $userdata->id, 'mailchimp_id' ) ){
        
        $user_hash = $mailchimp_id;
        
    } elseif( $user_email = get_user_meta( $userdata->id, 'mailchimp_email' ) ) {
       
        $user_hash = md5( strtolower( $user_email ) );
        
    } else {
        
        $user_email = get_user_meta( $userdata->user_email );
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
        $time = strtotime( current_time( 'mysql' ) );
        
        // Maybe save to WordPress?
        update_user_meta( $userdata->id, 'mailchimp_status', $mailchimp_status );
        update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
        

        return __('Successfully unsubscribed', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }

}
}
/* ------------------------------------------------------------------------------------ */
if (!function_exists('mc_update')) {
function mc_update( $mailchimp_auth, $userdata ){
	// Add/Update interests

	// Get the user's preferred MailChimp email
    if( $mailchimp_id = get_user_meta( $userdata->id, 'mailchimp_id' ) ){
        
        $user_hash = $mailchimp_id;
        
    } elseif( $user_email = get_user_meta( $userdata->id, 'mailchimp_email' ) ) {
       
        $user_hash = md5( strtolower( $user_email ) );
        
    } else {
        
        $user_email = get_user_meta( $userdata->user_email );
        $user_hash = md5( strtolower( $user_email ) );
        
    }

	$url = 'https://' . $mailchimp_auth->server . '.api.mailchimp.com/3.0/lists/' . $mailchimp_auth->list_id . '/members/' . $user_hash;
	$rest = 'PATCH';

	$interests = get_user_meta( $userdata->id, $mailchimp_interests );

	// Set up the response to tsend to MailChimp
	$content = array( 'email_address' => $user_email, 'interests' => $interests);

	$input = json_encode( $content ); // Make it so MailChimp understands
	
	// Run the request and get the response
	$data = mailchimp_curl( $url, $mailchimp_auth->name, $rest, $input );

    if( $data !== 'error'){
    
        // Get the customer's new MailChimp interests
        $mailchimp_interests = $data['interests'];

        // Save last update time
        $time = strtotime( current_time( 'mysql' ) );

        // Maybe save to WordPress?
        update_user_meta( $userdata->id, 'mailchimp_interests', $mailchimp_interests );
        update_user_meta( $userdata->id, 'mailchimp_update_time', $time ); // Store current timestamp in a user_meta as the last check
        
        return __('Details successfully updated.', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }

}
}
/*-------------------------------------------------------------------------------*/
if (!function_exists('mc_check')) {
function mc_check( $mailchimp_auth, $userdata ){
	
	// Check subscriber status (in case someone unsubs)
    
    // Get original status
    $status = get_user_meta( $userdata->id, 'mailchimp_status' );
    
    // Get current time and compare to anything saved
    $time = strtotime( current_time( 'mysql' ) );
    $last_check = strtotime( get_user_meta( $userdata->id, 'mailchimp_update_time' ) );
    
    $time_diff = $time - $last_check;
    
    // If we've not checked today then let's try it
    if( $time_diff > 86400 ){
    
        // Get the user's preferred MailChimp email
        if( $mailchimp_id = get_user_meta( $userdata->id, 'mailchimp_id' ) ){

            $user_hash = $mailchimp_id;

        } elseif( $user_email = get_user_meta( $userdata->id, 'mailchimp_email' ) ) {

            $user_hash = md5( strtolower( $user_email ) );

        } else {

            $user_email = get_user_meta( $userdata->user_email );
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
            
            return __('Subscribed', 'mailchimp-prefs');
                
                
            } elseif ( $data['status'] == 'unsubscribed' ){
                
                update_user_meta( $userdata->id, 'mailchimp_status', 'unsubscribed' );
                
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
}
/*-------------------------------------------------------------------------------*/
if (!function_exists('mc_register')) {
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

                    return __('Successfully registered and subscribed!', 'mailchimp-prefs');

                } else {

                    return __('Successfully registered but subscription failed', 'mailchimp-prefs');

                }
                
                
            } else {
                
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
}