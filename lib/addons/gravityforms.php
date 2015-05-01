<?php

/**
* The Add on to support Gravity forms
*/

class LyticsAddonGravityforms{

    /**
    * Initializes the gravityforms addon
    * @since 0.1
    * @author Russell Fair
    */
    function init(){
	if( class_exists('RGForms') ){
	    add_filter('lytics-addons', array( $this, 'gravityform_email' ), 10, 1 );
	    add_filter('gform_field_css_class', array( $this, 'custom_classes' ), 10, 3);
	}
    }


    /**
    * Adds an extra css class to standard gravityforms field output to make email fields individually targetable.
    * @since 0.1
    * @author Russell Fair
    * @param string $classes the existing classes
    * @param string $field the field to alter
    * @param int $form the form id
    * @return string $classes the new classes
    */
    function custom_classes($classes, $field, $form){
	if($field["type"] == "email"){
	    $classes .= ' contaier_email';
	}
	return $classes;
    }

    /**
    * Adds Gravityforms form submit event to available lytics addons
    * @since 0.1
    * @author Russell Fair
    * @param array $addons other addons
    * @return array $addons the updated addons
    */
    function gravityform_email( $addons ){
	$form_submit = array(
	    'type' => 'event',
	    'selector' => '.gform_wrapper > form',
	    'event' => 'submit',
	    'key' => 'email',
	    'field' => '.contaier_email input',
	);
	$addons[__FUNCTION__] = $form_submit;

	return $addons;
    }
}
