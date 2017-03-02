<?php
/*
*   View for users who are logged in but not signed up via MailChimp
*
*
*/
function mc_pref_view_logged_in_not_registered(){

    ob_start();
    ?>
    <div class="mailchimp_dashboard"><?php
    // Call in the MailChimp registration form
    include(MAILCHIMP_PREF_PATH . 'forms/subscribe-form.php');

    echo mc_pref_form_subscribe( $mailchimp_auth, $userdata );




    if ( isset( $_POST['login'] ) ) {

        // Run a login

    }


    if ( isset( $_POST['subscribe'] ) ) {

        // Run a subscribe
        $message = 'Subscribed'; // mc_subscribe();
        echo $message;

    }
    ?>
    </div>
    <?php

    $shortcode_output = ob_get_clean();
    
    return $shortcode_output;

}