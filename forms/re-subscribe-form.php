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
    <label for="email">Email address:</label>
    <input type="email" name="email" value="<?php echo $user_email; ?>" data-parsley-trigger="change" parsley-required="true"><br/>

    <label for="fname">First name:</label>
    <input type="text" name="fname" value="<?php echo $user_fname; ?>"><br/>

    <label for="lname">Last name:</label>
    <input type="text" name="lname" value="<?php echo $user_lname; ?>"><br/>


    <input type="submit" name="subscribe" value="Re-subscribe">


    </form>
    <?php

    $resubscribe_form = ob_get_clean();
    
    return $resubscribe_form;
    
}