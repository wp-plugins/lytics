<?php

/**
* lytics-loader.php loads the Lytics script into wp
 * @since 0.1
*/
class Lytics{

    public $version, $addons;

    function __construct(){
	$this->version = $this->set_version();
    }

    /**
    * Sets the plugin version number
    * @since 0.1
    * @author Russell Fair
    */
    function set_version(){
	$this->version = '0.2';
    }

    /**
    * Initializes the plugin
    * @since 0.1
    * @author Russell Fair
    */
    function init(){
	if( is_admin() ){
	    $this->load_admin();
	}
	else
	{
	    $this->load_frontend();
	}
	$this->load_addons();
	do_action('lytics_loaded');
    }
    /**
    * Loads the wp-admin specific classes
    * @since 0.1
    * @author Russell Fair
    */
    function load_admin(){
	require_once( 'lytics-settings.php' );
	$settings = new LyticsSettings;
	$settings->init();
    }

    /**
    * Loads the front end (non wp-admin) specific classes
    * @since 0.1
    * @author Russell Fair
    */
    function load_frontend(){
	add_action( 'init', array( $this, 'register_lytics_script' ) );
	add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_lytics_script' ) );
    }

    /**
    * Registers and loads the LyticsAddons classes, if available and enabled
    * @since 0.1
    * @author Russell Fair
    */
    function load_addons(){
	    require_once( 'lytics-addons.php' );
	    $addons = new LyticsAddons;
	    $addons->init( $this->version );
    }

    /**
    * Registers the core lytics script, if the account is configured
    * @since 0.1
    * @author Russell Fair
    */
    function register_lytics_script(){
	$lytics_id = $this->get_lytics_id();
	if( ! $lytics_id )
	{
	    return;
	}
	else
	{
	    $lytics_script_url = $this->get_lytics_script_url( array( 'id' => $lytics_id ) );
	    wp_register_script( 'lytics', $lytics_script_url, array(), $this->version, false);
	}
    }

    /**
    * Enqueues the Lytics script
    * @since 0.1
    * @author Russell Fair
    */
    function enqueue_lytics_script(){
	wp_enqueue_script('lytics');
    }

    /**
    * Gets the current version
    * @since 0.1
    * @author Russell Fair
    * @return float $version
    */
    function get_version(){
	return $this->version;
    }

    /**
    * Gets the Lytics account ID, if configured
    * @since 0.1
    * @author Russell Fair
    * @return string $id the id hash in the script url
    */
    function get_lytics_id(){
	$id = get_option( 'lytics_id_hash' , false );
	return $id;
    }

    /**
    * Gets the Lytics script src, with the hashed id
    * @since 0.1
    * @author Russell Fair
    * @param array $args the args array to be merged with the defaults
    * @return string $id the id hash in the script url
    */
    function get_lytics_script_url( $args = array() ){
	$defaults = array(
	    'protocol' => 'https://',
	    'base' => 'api.lytics.io/api/tag/',
	    'id' => 'ADD_YOUR_LYTICS_ID',
	    'tail' => '/lio.js',
	    'query_args' => array( 'wp_integration' => $this->get_version() ),
	);

	$args = wp_parse_args( $args, $defaults );

	// should match pattern below:
	// https://api.lytics.io/api/tag/52a6e47dd2e94abd7a1ad75cb089fa5b/lio.js
	$script_url = sprintf( '%s%s%s%s', $args['protocol'], $args['base'], $args['id'], $args['tail'] );
	return add_query_arg( $args['query_args'], $script_url );
    }

}
