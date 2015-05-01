<?php

/** The Add on to support Restrict Content Pro forms
*/

class LyticsAddonRCP{

    /**
    * Initializes our RCP addon
    * @since 0.1
    * @author Russell Fair
    */
    function init(){
	if( class_exists('RCP_Levels') ){
	    if ( ! is_user_logged_in() ) {
		add_filter('lytics-addons', array( $this, 'rcp_register' ), 10, 1 );
		add_filter('lytics-addons', array( $this, 'rcp_login' ), 10, 1 );
	    } else {
		add_filter('lytics-addons', array( $this, 'rcp_subscription_level' ), 10, 1 );
	    }
	}
    }

    /**
    * Adds RCP registration form to addon events
    * @since 0.1
    * @author Russell Fair
    * @param array $addons the existing addons
    * @return array $addons the new addons array
    */
    function rcp_register( $addons ){
	$form_submit = array(
	    'type' => 'event',
	    'selector' => '#rcp_registration_form',
	    'event' => 'submit',
	    'key' => 'email',
	    'field' => '#rcp_user_email',
	);
	$addons[__FUNCTION__] = $form_submit;

	return $addons;
    }

    /**
    * Adds RCP login to events list
    * @since 0.1
    * @author Russell Fair
    * @param array $addons the existing addons
    * @return array $addons the updated addons
    */
    function rcp_login( $addons ){
	$comment_reply = array(
	    'type' => 'event',
	    'selector' => '#rcp_login_form',
	    'event' => 'submit',
	    'key' => 'user_name',
	    'field' => '#rcp_user_login',
	);
	$addons[__FUNCTION__] = $comment_reply;
	return $addons;
    }

    /**
    * Adds the subscripton level to the static data sent to lytics
    * @since 0.1
    * @author Russell Fair
    * @param array $addons the existing addons
    * @return array $addons the updated addons
    */
    function rcp_subscription_level( $addons ){

	if ( ! function_exists( 'rcp_get_subscription' ) || ! is_user_logged_in() ){
	    return $addons;
	}

	global $current_user;
	$subscription = rcp_get_subscription( $current_user->ID );

	if ( ! $subscription ){
	    return $addons;
	}

	$user_updated = get_user_meta( $current_user->ID, 'lytics_rcp_user_updated', true);
	$time_since_update = ( ! $user_updated ) ? false : time() - $user_updated;

	if ( ! $user_updated || $time_since_update >= apply_filters( 'lytics_addon_rcp_user_timeout' , 60*60) ) {
	    $subscription_level = array(
		'type' => 'static',
		'data' => array(
		    'rpc_subscription_level' => rcp_get_subscription( $current_user->ID ),
		),
	    );

	    $addons[__FUNCTION__] = $subscription_level;

	    update_user_meta( $current_user->ID, 'lytics_rcp_user_updated', time() );
	}

	return $addons;

    }
}
