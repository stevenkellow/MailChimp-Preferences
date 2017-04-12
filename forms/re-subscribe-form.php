<?php
/*
*   Form for re-subscribing users
*
*/
function mc_pref_form_resubscribe( $mailchimp_auth, $userdata ){
   
    ob_start();
    ?>
    <form id="mailchimp-pref-resubscribe-form" action="<?php echo get_the_permalink();?>" method="post" class="mailchimp-pref-form">
    <h3><?php _e( 'Subscribe again', 'mailchimp-prefs' ); ?></h3>
    <label for="email"><?php _e( 'Email:', 'mailchimp-prefs'); ?></label>
    <input type="email" name="email" value="<?php echo $userdata->user_email; ?>" data-parsley-trigger="change" required><br/>

    <label for="fname"><?php _e( 'First name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="fname" value="<?php echo $userdata->first_name; ?>"><br/>

    <label for="lname"><?php _e( 'Last name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="lname" value="<?php echo $userdata->last_name; ?>"><br/>


    <input type="submit" name="subscribe" value="<?php _e('Re-subscribe', 'mailchimp-prefs'); ?>">


    </form>
    <?php

    $resubscribe_form = ob_get_clean();
    
    return $resubscribe_form;
    
}