<?php

/**
* The addon class for WordPress Core
*/
class LyticsAddonWPCore{

    /**
    * Initializes the WordPress core support addon
    * @since 0.1
    * @author Russell Fair
    */
    function init(){
	if( ! is_user_logged_in() ){
	    add_filter('lytics-addons', array( $this, 'wp_comment' ), 10, 1 );
	} else { //this is a known user
	    add_filter('lytics-addons', array( $this, 'wp_user' ), 10, 1 );
	}
    }

    /**
    * Adds support for thw WordPress comment form
    * @since 0.1
    * @author Russell Fair
    * @param array $addons the addons array
    * @return array $addons the new addons
    */
    function wp_comment( $addons ){
	$comment = array(
	    'type' => 'event',
	    'selector' => '#commentform',
	    'event' => 'submit',
	    'key' => 'email',
	    'field' => '#email',
	);
	$addons[__FUNCTION__] = $comment;
	return $addons;
    }

    /**
    * Adds support for the WordPress user data for logged in users
    * @since 0.1
    * @author Russell Fair
    * @param array $addons the addons array
    * @return array $addons the new addons
    */
    function wp_user( $addons ){

	if ( ! is_user_logged_in() ){
	    return $addons;
	}

	global $current_user;
	$user_updated = get_user_meta( $current_user->ID, 'lytics_wp_user_updated', true);
	$time_since_update = ( ! $user_updated ) ? false : time() - $user_updated;

	if ( ! $user_updated || $time_since_update >= apply_filters( 'lytics_addon_wp_core_user_timeout' , 60*60) ) {
	    $user = array(
		'type' => 'static',
		'data' => array(
		    'email' => $current_user->data->user_email
		),
	    );

	    if( isset( $current_user->roles[0] ) ){
		$user['data']['role'] = $current_user->roles[0];
	    }

	    $addons[__FUNCTION__] = $user;

	    update_user_meta( $current_user->ID, 'lytics_wp_user_updated', time() );
	}

	return $addons;
    }

}
