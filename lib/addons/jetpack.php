<?php

/**
* The Add on to support Jetpack
*/

class LyticsAddonJetpack{

    /**
    * Initializes the Jetpack addon
    * @since 0.1
    * @author Russell Fair
    */
    function init(){
	if( class_exists('Jetpack') ){
	    add_filter('lytics-addons', array( $this, 'jetpack_subscribe' ), 10, 1 );
	}
    }

    /**
    * Adds the jetpack subscribe widget to the Lytics events
    * @since 0.1
    * @author Russell Fair
    */
    function jetpack_subscribe( $addons ){
	$form_submit = array(
	    'type' => 'event',
	    'selector' => '.jetpack_subscription_widget > form',
	    'event' => 'submit',
	    'key' => 'email',
	    'field' => '#subscribe-field',
	);
	$addons[__FUNCTION__] = $form_submit;

	return $addons;
    }
}
