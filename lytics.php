<?php

/**
* Plugin Name: Lytics
* Author: Russell Fair
* Version: 0.2
* Description: Adds Lytics to your WordPress site and connects to various web forms and third party plugin api's (e.g. Gravityforms, RCP and Jetpack)
* Plugin URI:        http://religionnews.com/rns-slingshot-plugin-uri/
* Description:       Creates beautiful HTML Emails of curated content.
* Author URI:        http://q21.co/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       lytics
* Domain Path:       /languages
*/

/** This action is documented in includes/class-rns-slingshot-plugin-activator.php */
register_activation_hook( __FILE__,  'lytics_plugin_activate' );

function lytics_plugin_activate(){
    // DO NOTHING
}

/** This action is documented in includes/class-rns-slingshot-plugin-deactivator.php */
register_deactivation_hook( __FILE__,  'lytics_plugin_deactivate' );

function lytics_plugin_deactivate(){
    // DO NOTHING
}

add_action('plugins_loaded', 'lytics_load_plugin');

/**
* Loads the Lytics Plugin Library
* @since 0.1
* @author Russell Fair
*/
function lytics_load_plugin(){
    require_once( 'lib/lytics-loader.php' );
    $lytics = new Lytics;
    $lytics->init();
}
