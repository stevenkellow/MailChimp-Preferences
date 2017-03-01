<?php
/*
*   Form for re-subscribing users
*
*/
function mc_pref_form_resubscribe(){
   
    ob_start();
    ?>
    <form id="mailchimp-pref-resubscribe-form" action="<?php echo get_the_permalink();?>" method="post" parsley-validate>
    <h3><?php _e( 'Subscribe again', 'mailchimp-prefs' ); ?></h3>
    <label for="email"><?php _e( 'Email:', 'mailchimp-prefs'); ?></label>
    <input type="email" name="email" value="<?php echo $user_email; ?>" data-parsley-trigger="change" parsley-required="true"><br/>

    <label for="fname"><?php _e( 'First name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="fname" value="<?php echo $user_fname; ?>"><br/>

    <label for="lname"><?php _e( 'Last name:', 'mailchimp-prefs'); ?></label>
    <input type="text" name="lname" value="<?php echo $user_lname; ?>"><br/>


    <input type="submit" name="subscribe" value="<?php e_('Re-subscribe', 'mailchimp-prefs'); ?>">


    </form>
    <?php

    $resubscribe_form = ob_get_clean();
    
    return $resubscribe_form;
    
}