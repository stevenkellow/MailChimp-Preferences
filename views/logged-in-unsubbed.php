<?php
/*
*   View for users who are logged in and have unsubscribed from MailChimp
*
*
*/
function mc_pref_view_logged_in_unsubbed( $mailchimp_auth, $userdata ){
   
    ob_start();
    ?>
    <div class="mailchimp_dashboard"><?php

    // Include the resubscribe
    include_once( MAILCHIMP_PREF_PATH . '/forms/re-subscribe-form.php');

    echo mc_pref_form_resubscribe( $mailchimp_auth, $userdata );


    if ( isset( $_POST['subscribe'] ) ) {

        // Run an update
        //$message = mc_subscribe( $mailchimp_auth, $userdata );
        echo $message;

    }
    ?>
    </div>
    <?php

    $shortcode_output = ob_get_clean();

    
    return $shortcode_output;
}