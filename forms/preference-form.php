<?php
/*
*   Form for users to manage their preferences
*
*/
function mc_pref_form_preferences( $mailchimp_auth, $userdata ){
   
    ob_start();
    ?>
    <form id="mailchimp-pref-preference-form" action="<?php echo get_the_permalink();?>" method="post" parsley-validate>
        <h3><?php _e( 'MailChimp Preferences', 'mailchimp-prefs' ); ?></h3>
        <label for="email"><?php _e( 'Email address: ', 'mailchimp-prefs' ); ?></label>
        <input type="text" name="email" value="<?php echo $userdata->user_email; ?>" parsley-trigger="change" parsley-required="true">
        <br/>
        <?php
        // Get the interests on this list
        $interests = get_site_option( $mailchimp_interests );
        // Get the user values so we can pre check
        $user_interests = get_user_meta( $userdata->id, $mailchimp_interests );

        if( $interests ){

            echo '<h5>' . __( 'Tick the box to receive emails about these topics:', 'mailchimp-prefs' ) . '</h5>';

            foreach( $interests as $interest ){

                // Echo an input box with label for the interest, and check it if it's been ticked in the user's info

                echo '<label for="' . $interest['id'] . '">' . $interest['name'] . ' : </label>';
                echo '<input type="checkbox" name="' . $interest['id'] . '" value="' . $interest['name'] . '" . ' . (in_array( $interest['id'], $user_interests )?'checked':'') . '><br/>';


            }
            
        }

        ?>
        <input type="submit" name="update" value="<?php _e( 'Change Preferences', 'mailchimp-prefs' ); ?>">
        <br/><br/>
        <h4><?php _e( 'Unsubscribe: ', 'mailchimp-prefs' ); ?></h4>
        <p><?php _e( 'Not want to receive newsletter emails anymore?  Click below to unsubscribe.', 'mailchimp-prefs' ); ?></p>
        <input type="submit" name="unsub" value="<?php _e( 'Unsubscribe', 'mailchimp-prefs' ); ?>">
    </form>
    <?php
    $preference_form = ob_get_clean();
	
	return $preference_form;

}