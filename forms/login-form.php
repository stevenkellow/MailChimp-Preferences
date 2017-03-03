<?php
/*
*   Log user in
*
*/
function mc_pref_form_login(){
    ob_start();
    ?>

    <form id="mailchimp-pref-login-form" action="<?php echo get_the_permalink();?>" method="post" parsely-validate>
    <h3><?php _e( 'Login', 'mailchimp-prefs' ); ?></h3>
    <label for="username"><?php _e( 'Username:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="username" parsley-required="true"><br/>

    <label for="password"><?php _e( 'Password:', 'mailchimp-prefs'); ?></label>
    <input type="password" name="password" parsley-required="true"><br/>


    <input type="submit" name="login" value="Log in">


    </form>
    <?php

    $login_form = ob_get_clean();
    
    return $login_form;
    
}