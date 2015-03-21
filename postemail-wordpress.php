<?php 
/*
Plugin Name: Mandrill For WordPress - Email form under Post
Plugin URI: http://khubbaib.com/
Description: Add Email form under every post and send email either mandrill or simply your own server.
Version: 1.0.2
Author: Web solution soft
Author URI:
Text Domain: post-email
Domain Path: lang
*/

/*
  *Exit if accessed directly
*/
if ( ! defined( 'ABSPATH' ) ) exit;
    
    /*
     *Define Constants 
    */

    if( ! defined( 'POST_EMAIL_PLUGIN_NAME' )){
        
        define( 'POST_EMAIL_PLUGIN_NAME', 'Mandrill For WordPress' );
    }
    if( ! defined( 'POST_EMAIL_ROOT_PATH' )){
        
        define( 'POST_EMAIL_ROOT_PATH', dirname(__FILE__) );
    }  
    if( ! defined( 'POST_EMAIL_VERSION' )){ 
        
        define( 'POST_EMAIL_VERSION', '1.0.2');
    }
    if( ! defined( 'POST_EMAIL_MINPHP_VERSION' )){ 
        
        define( 'POST_EMAIL_MINPHP_VERSION', '5.3.0' );
    }
    if( ! defined( 'POST_EMAIL_DIR_PATH' )){ 
        
        define( 'POST_EMAIL_DIR_PATH', plugin_dir_path( __FILE__ ) );
    }
    
    if( ! defined( 'POST_EMAIL_SRC_PATH' )){ 
        
        define( 'POST_EMAIL_SRC_PATH', dirname(__FILE__) . '/src/' );
    }
    
    if( ! defined( 'POST_EMAIL_API_KEY' )){ 
        
        define( 'POST_EMAIL_API_KEY', get_option( 'post_email_api_key' ) ); 
    } 
    define("PUB_KEY", get_option('recaptcha_public')); 
    define("PRIV_KEY", get_option('recaptcha_private')); 
    
    /*
     * Load the required classes 
    */
    include_once 'classes/post_main.php';
    require_once POST_EMAIL_SRC_PATH.'Mandrill.php';
    require POST_EMAIL_SRC_PATH.'recaptchalib.php';
    
    $post_main = new Post_Main();
    
    $post_main->post_email_add_alerts();
    
    register_activation_hook( __FILE__, array( $post_main, 'post_email_install_plugin' ) );
    register_deactivation_hook( __FILE__, array( $post_main, 'post_email_uninstall_plugin' ) );
?>