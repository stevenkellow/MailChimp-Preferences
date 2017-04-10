<?php
/*
*	Custom MailChimp API functions to perform back-end actions
*
*	Based around docs here (http://developer.mailchimp.com/documentation/mailchimp/reference/lists/)
*
*/

/* --------------------------------------------------------------------- */
function mc_get_lists( $mailchimp_auth ){
    
    // Get all lists from MailChimp

	$url = 'https://' . $server . '.api.mailchimp.com/3.0/lists/';
	$rest = 'GET';
    
    // Call in anything we have saved in WordPress about the lists
    $mailchimp_lists = get_option( 'mailchimp_pref_lists');

	$data = mailchimp_curl( $url, $user, $rest );
    
    if( $data !== 'error' ){

        // Get details about each list
        
        foreach( $data['lists'] as $list ){
            
            $list_id = $list['id'];
            $list_name = $list['name'];
            
            // If the list doesn't match what's stored in wordpress
            $current_list = $mailchimp_lists[$list_id];
            
            // If the list doesn't exist
            if( ! $current_list ){
                
                // Add the MailChimp List details to the array
                $mailchimp_lists[$list_id] = array( 'list_name' => $list_name, 'interests' => '' );
                
            } else {
                
                // In case we change the list name on the MailChimp side
                if( $list_name !== $mailchimp_lists[$list_id]['list_name'] ){
                    
                    $mailchimp_lists[$list_id]['list_name'] = $list_name;
                    
                }
                
                // Run something here to check interests on MailChimp side versus what's on the WordPress side
                
            }
            
            
            // Save MailChimp lists to the admin
            update_option( 'mailchimp_pref_lists', $mailchimp_lists );
            
        }
        
        return __('Lists successfully retrieved', 'mailchimp-prefs');
        
    } else {
        
        return __('CURL error', 'mailchimp-prefs');
        
    }
    
    
}
/* --------------------------------------------------------------------- */
function mc_get_list_details( $mailchimp_auth ){
    

	$url = 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $list_id;
	$rest = 'GET';

	$data = mailchimp_curl( $url, $user, $rest );
    
    if( $data !== 'error' ){

        // Get details about the list
        $mailchimp_interests = $data['interests'];
        
        // Get the lists saved in the site options
        get_option( 'mailchimp_pref_lists' );
        
        return 'List details successfully retrieved!';
        
    } else {
        
        return 'CURL error';
        
    }
 
    
}