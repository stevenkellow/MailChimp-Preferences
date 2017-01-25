<?php
/*
*   Form for users to register on the site with
*
*/
function mc_pref_form_register(){

    ob_start();
    ?>

    <form id="mailchimp-pref-registration-form" action="<?php echo get_the_permalink();?>" method="post" parsley-validate>
    <h3><?php _e( 'Register for our site', 'mailchimp-prefs' ); ?></h3>
    <label for="email">E-mail address:</label>
    <input type="email" name="email" data-parsley-trigger="change" parsley-required="true"><br/>

    <label for="fname">First name:</label>
    <input type="text" name="fname"><br/>

    <label for="lname">Last name:</label>
    <input type="text" name="lname"><br/>

    <label for="username">Username:</label>
    <input type="text" name="username" parsley-required="true"><br/>

    <label for="password">Password:</label>
    <input type="password" name="password" id="mailchimp_pref_password"><br/>
        
    <label for="password-verify">Confirm password:</label>
    <input type="password" name="password-verify" data-parsley-trigger="change" parsley-equalto="#mailchimp_pref_password" parsley-required="true"><br/>

    <label for="mailchimp">Tick the box to be signed up to our mailing list</label>
    <input type="checkbox" name="mailchimp"><br/>


    <input type="submit" name="register" value="Register">


    </form>
    <?php

    $registration_form = ob_get_clean();
    
    return $registration_form;
    
}