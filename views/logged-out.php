<?php
/*
*   View for users who are not logged in
*
*
*/
function mc_pref_view_logged_out(){
    
    ob_start();
    ?>
    <div class="mailchimp_dashboard"><?php

    /*
    Form

    login to site

    register to site

    sign-up without logging in or registering
    */


    // If logging in/registering
    include(MAILCHIMP_PREF_PATH . '/forms/login-form.php');
    echo mc_pref_form_login();

    // If registering call
    include(MAILCHIMP_PREF_PATH . '/forms/registration-form.php');
    echo mc_pref_form_register();

    // If signing up to newsletter without account
    include(MAILCHIMP_PREF_PATH . '/forms/subscribe-form.php');
    echo mc_pref_form_subscribe();



    if ( isset( $_POST['login'] ) ) {

        // Log user in
        //mc_subscribe();

    }

    if ( isset( $_POST['register'] ) ) {

        // Run a resgister - mc_register() - which will include a mc_subscribe()

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $signup = $_POST['mailchimp'];

        // mc_register( $username, $password, $email, $fname, $lname);
        echo __('Subscribed', 'mailchimp-prefs'); // $message;

    }

    if ( isset( $_POST['subscribe'] ) ) {

        // Run an update
        //$message = mc_subscribe();
        echo $message;

    }
    ?>
    </div>
    <?php

    $shortcode_output = ob_get_clean();
    
    
    return $shortcode_output;
    
    
}