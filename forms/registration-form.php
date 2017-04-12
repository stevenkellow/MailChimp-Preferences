<?php
/*
*   Form for users to register on the site with
*
*/
function mc_pref_form_register(){

    ob_start();
    ?>

    <form id="mailchimp-pref-registration-form" action="<?php echo get_the_permalink();?>" method="post" class="mailchimp-pref-form">
    <h3><?php _e( 'Register for our site', 'mailchimp-prefs' ); ?></h3>
    <label for="email"><?php _e( 'Email:', 'mailchimp-prefs'); ?></label>
    <input type="email" name="email" data-parsley-trigger="change" required><br/>

    <label for="fname"><?php _e( 'First name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="fname"><br/>

    <label for="lname"><?php _e( 'Last name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="lname"><br/>

    <label for="username"><?php _e( 'Username:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="username" required><br/>

    <label for="password"><?php _e( 'Password:', 'mailchimp-prefs'); ?></label>
    <input type="password" name="password" id="mailchimp_pref_password"><br/>
        
    <label for="password-verify"><?php _e( 'Confirm password:', 'mailchimp-prefs'); ?></label>
    <input type="password" name="password-verify" data-parsley-trigger="change" data-parsley-equalto="#mailchimp_pref_password" required><br/>

    <label for="mailchimp"><?php _e( 'Tick this box to be signed up to our mailing list:', 'mailchimp-prefs'); ?></label>
    <input type="checkbox" name="mailchimp"><br/>


    <input type="submit" name="register" value="<?php _e('Register', 'mailchimp-prefs'); ?>">


    </form>
    <?php

    $registration_form = ob_get_clean();
    
    return $registration_form;
    
}