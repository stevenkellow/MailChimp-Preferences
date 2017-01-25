<?php
/*
*   Form for users to sign up to MailChimp with
*
*/
function mc_pref_form_subscribe(){

    ob_start();


    // If user is logged in then set default values if possible
    if( is_user_logged_in() ){ ?>
    <form id="mailchimp-pref-subscribe-form" action="<?php echo get_the_permalink();?>" method="post" parsley-validate>
    <h3><?php _e( 'Subscribe to our newsletter', 'mailchimp-prefs' ); ?></h3>
    <label for="email">Email address:</label>
    <input type="email" name="email" value="<?php echo $user_email; ?>" data-parsley-trigger="change" parsley-required="true"><br/>

    <label for="fname">First name:</label>
    <input type="text" name="fname" value="<?php echo $user_fname; ?>"><br/>

    <label for="lname">Last name:</label>
    <input type="text" name="lname" value="<?php echo $user_lname; ?>"><br/>


    <input type="submit" name="subscribe" value="Subscribe">


    </form>


    <?php } else { ?>

    <form id="mailchimp-pref-subscribe-form" action="<?php echo get_the_permalink();?>" method="post" parsley-validate>
    <h3><?php _e( 'Subscribe to our newsletter', 'mailchimp-prefs' ); ?></h3>
    <label for="email">Email address:</label>
    <input type="email" name="email" data-parsley-trigger="change" parsley-required="true"><br/>

    <label for="fname">First name:</label>
    <input type="text" name="fname"><br/>

    <label for="lname">Last name:</label>
    <input type="text" name="lname"><br/>


    <input type="submit" name="subscribe" value="Subscribe">


    </form>

    <?php  
    }


    $subscribe_form = ob_get_clean();
    
    return $subscribe_form;
    
}