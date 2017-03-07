<?php
/*
*   View for users who are logged in and are subscribed via MailChimp
*
*
*/
function mc_pref_view_logged_in_subbed( $mailchimp_auth, $userdata ){
   
    ob_start();
    ?>
    <div class="mailchimp_dashboard"><?php

    // Show the preference form
    include_once( MAILCHIMP_PREF_PATH . '/forms/preference-form.php');

    echo mc_pref_form_preferences( $mailchimp_auth, $userdata );





    if ( isset( $_POST['update'] ) ) {

        // Run an update
        //$message = mc_update();
        echo $message;

    }


    if ( isset( $_POST['unsub'] ) ) {

        // Run an unsubscribe :'(
        $message = mc_unsub( $mailchimp_auth, $userdata );
        echo $message;

    }
    ?>
    </div>
    <?php

    $shortcode_output = ob_get_clean();
    
 
    return $shortcode_output;
}