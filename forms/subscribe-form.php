<?php
/*
*   Form for users to sign up to MailChimp with
*
*/
function mc_pref_form_subscribe( $mailchimp_auth, $userdata ){

    ob_start();


    // If user is logged in then set default values if possible
    if( is_user_logged_in() ){ ?>
    <form id="mailchimp-pref-subscribe-form" action="<?php echo get_the_permalink(); ?>" method="post" class="mailchimp-pref-form">
    <h3><?php _e( 'Subscribe to our newsletter', 'mailchimp-prefs' ); ?></h3>
    <label for="email"><?php _e( 'Email:', 'mailchimp-prefs'); ?></label>
    <input type="email" name="email" <?php if( is_user_logged_in() ){ echo 'value="' . $userdata->user_email . '"'; } ?> data-parsley-trigger="change" required><br/>

    <label for="fname"><?php _e( 'First name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="fname" <?php if( is_user_logged_in() ){ echo 'value="' . $userdata->first_name . '"'; } ?>><br/>

    <label for="lname"><?php _e( 'Last name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="lname" <?php if( is_user_logged_in() ){ echo 'value="' . $userdata->last_name . '"'; } ?>><br/>


    <input type="submit" name="subscribe" value="<?php _e( 'Subscribe', 'mailchimp-prefs'); ?>">


    </form>

    <?php
    }


    $subscribe_form = ob_get_clean();
    
    return $subscribe_form;
    
}