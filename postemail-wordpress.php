<?php 
/*
Plugin Name: Mandrill For WordPress - Email form under Post
Plugin URI: http://khubbaib.com/
Description: Add Email form under every post and send email either mandrill or simply your own server.
Version: 1.0.3
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
        
        define( 'POST_EMAIL_PLUGIN_NAME', 'Mandrill For WP' );
    }
    if( ! defined( 'POST_EMAIL_ROOT_PATH' )){
        
        define( 'POST_EMAIL_ROOT_PATH', dirname(__FILE__) );
    }  
    if( ! defined( 'POST_EMAIL_VERSION' )){ 
        
        define( 'POST_EMAIL_VERSION', '1.0.3');
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
    if( get_option( 'post_email_api_need' ) == 1 ){
        function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
            $post_main = new Post_Main();
            
            $user = get_userdata( $user_id );
         
            // The blogname option is escaped with esc_html on the way into 
            // the database in sanitize_option we want to reverse this for the plain 
            // text arena of emails.
            $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
         
            // To keep things simple, we still send the admin notification 
            // through wp_mail just as in the default version
            $message  = sprintf( __( 'New user registration on your site %s:' ), $blogname ) . "\r\n\r\n";
            $message .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
            $message .= sprintf( __( 'E-mail: %s' ), $user->user_email ) . "\r\n";
         
            @wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration' ), $blogname ), $message );
         
            // Send welcome e-mail to new user
            $email_params = array(
                array( 'name' => 'BLOGNAME', 'content' => $blogname ),
                array( 'name' => 'USER_NAME', 'content' => $user->user_login ),
                array( 'name' => 'EMAIL', 'content' => $user->user_email ),
                array( 'name' => 'FIRST_NAME', 'content' => $user->first_name ),
                array( 'name' => 'LAST_NAME', 'content' => $user->last_name ),
                array( 'name' => 'PASSWORD', 'content' => $plaintext_pass ),
                array( 'name' => 'LOGIN_URL', 'content' => wp_login_url() ),
            );
             
            $template = get_option( 'mandrill_emailer_new_user_template' );
            $subject = sprintf( __( '[%s] Your username and password' ), $blogname );
             
             $to_name = $user->first_name . " " . $user->last_name;
            $post_main->post_email_mandrill_send_mail( $user->user_email, $to_name, $template, $subject, $email_params );
        }
    }
    register_activation_hook( __FILE__, array( $post_main, 'post_email_install_plugin' ) );
    register_deactivation_hook( __FILE__, array( $post_main, 'post_email_uninstall_plugin' ) );
?>