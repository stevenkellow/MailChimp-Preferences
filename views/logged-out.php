<?php
/*
*   View for users who are not logged in
*
*
*/
function mc_pref_view_logged_out( $mailchimp_auth, $userdata ){
    
    ob_start();
    
    if ( isset( $_POST['login'] ) ) {

        function mc_pref_login(){
            // Log user in
            $result = wp_signon( array( 'user_login' => $_POST['username'], 'user_password' => $_POST['password'] ) );

            if( is_wp_error( $result ) ){

                echo '<h1>NOOOO</h1>';

            } else {
                
                echo '<h1>' . $result . '</h1>';
                
            }
            
        }
        
        // Run before the headers and cookies are sent.
        add_action( 'after_setup_theme', 'mc_pref_login' );
    }

    if ( isset( $_POST['register'] ) ) {
        
         // Set up the userdata to create
        $userdata = array(
            'user_login'  =>  $_POST['username'],
            'user_email'  =>  $_POST['email'],
            'user_pass'   =>  $_POST['password'],  // When creating an user, `user_pass` is expected.
            'first_name'  =>  $_POST['fname'],
            'last_name'   =>  $_POST['lname']
        );

        $signup = $_POST['mailchimp'];
        
        // Run a resgister
        mc_register( $mailchimp_auth, $userdata, $signup ); // which will include a mc_subscribe()

        // mc_register( $username, $password, $email, $fname, $lname);
        echo __('Subscribed', 'mailchimp-prefs'); // $message;

    }

    if ( isset( $_POST['subscribe'] ) ) {

        // Run an update
        //$message = mc_subscribe();
        echo $message;

    }
    
    
    ?>
    <div class="mailchimp_dashboard"><?php

    /*
    Form

    login to site

    register to site

    sign-up without logging in or registering
    */


    // If logging in/registering
    include_once( MAILCHIMP_PREF_PATH . '/forms/login-form.php');
    echo mc_pref_form_login( $mailchimp_auth );

    // If registering call
    include_once( MAILCHIMP_PREF_PATH . '/forms/registration-form.php');
    echo mc_pref_form_register($mailchimp_auth );

    // If signing up to newsletter without account
    include_once( MAILCHIMP_PREF_PATH . '/forms/subscribe-form.php');
    echo mc_pref_form_subscribe( $mailchimp_auth );

    ?>
    </div>
    <?php

    $shortcode_output = ob_get_clean();
    
    
    return $shortcode_output;
    
    
}