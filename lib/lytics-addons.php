<?php

/*
* The Addons class handles registering and initializing all addons
*/
class LyticsAddons{

    public $version, $addons, $addons_loaded, $addons_path;
    /**
    * Initializes the Addon class
    * @since 0.1
    * @author Russell Fair
    * @param float $version the plugin version
    */
    function init( $version ){
	$this->version = $version;
	add_action('init', array( $this, 'register_addon_base_script'), 10 );
	add_action( 'wp_enqueue_scripts', array( $this, 'load_addon_js' ), 10 );
	add_filter( 'lytics_addons' , array( $this, 'get_addons'), 10, 1 );

	$this->set_addons_path();
	$this->load_addons();
    }

    /**
    * Sets the addons directory path, relative to this file
    * @todo if you relocate /addons/ you will need to refactor this accordingly.
    * @since 0.1
    * @author Russell Fair
    */
    function set_addons_path(){
	$this->addons_path = dirname(__FILE__) . '/addons/';
    }

    /**
    * Loads the appropriate addons
    * @since 0.1
    * @author Russell Fair
    * @todo currently this only supports the addons built into the class, and enabled in options.
    * @todo making it challenging to add new addons as one-off plugins that exist outside of this file.
    */
    function load_addons(){
	$addons = apply_filters( 'lytics_addons', array() );
	$enabled_addons = get_option('lytics_addon_selection');

	foreach ($addons as $addon){
	    if( is_array( $enabled_addons) && in_array( $addon['id'] , $enabled_addons ) ){
		$addon = $this->load_addon( $addon );
		$this->addons[] = $addon;
	    }
	}

	do_action( 'lytics_addons_loaded' );
    }

    /**
    * Gets the addons that are currently included in the plugin.
    * @since 0.1
    * @author Russell Fair
    * @todo add support for ninjaforms and other form builders
    * @todo add support for popular mail services widgets like Mailchimp
    */
    static function get_addons( $addons = array() ){
	$addons = array(
	    array(
		'id' => 'wp_core',
		'name' => __('WordPress Core', 'lytics' ),
		'file' => 'wp-core.php',
		'classname' => 'LyticsAddonWPCore',
		),
	    array(
		'id' => 'gravityforms',
		'name' => __('Gravityforms', 'lytics' ),
		'file' => 'gravityforms.php',
		'classname' => 'LyticsAddonGravityforms',
	    ),
	    array(
		'id' => 'rcp',
		'name' => __('Restrict Content Pro', 'lytics' ),
		'file' => 'restrict-content-pro.php',
		'classname' => 'LyticsAddonRCP',
	    ),
	    array(
		'id' => 'jetpack',
		'name' => __('Jetpack', 'lytics' ),
		'file' => 'jetpack.php',
		'classname' => 'LyticsAddonJetpack',
	    ),
	);
	return $addons;
    }

    /**
    * Loads the addon classes
    * @since 0.1
    * @author Russell Fair
    * @todo limit these only to the active addons, similarly to the addon.js
    */
    function load_addon($addon){
	require_once( $this->addons_path . $addon['file'] );
	if( class_exists( $addon['classname'] ) ){
	    $addon = new $addon['classname'];
	    $addon->init();
	    return $addon;
	}

    }

    /**
    * Registers our addon script with WordPress
    * @since 0.1
    * @author Russell Fair
    * @todo figure out compatability issues with optomizely
    */
    function register_addon_base_script(){
	wp_register_script('lytics-addon', plugin_dir_url( __FILE__ ) . 'js/addons.js' , array('lytics', 'jquery'), $this->version, true );
    }

    /**
    * Enqueues our addons script (in the header no less).
    * @since 0.1
    * @author Russell Fair
    */
    function load_addon_js(){
	$addons = $this->get_loaded_addons();
	wp_enqueue_script('lytics-addon');
	wp_localize_script('lytics-addon', 'LyticsAddons', $addons);
    }

    /**
    * Gets all of the addons that have been loaded / registered.
    * @since 0.1
    * @author Russell Fair
    */
    function get_loaded_addons(){
	return apply_filters( 'lytics-addons', array() );
    }

}
