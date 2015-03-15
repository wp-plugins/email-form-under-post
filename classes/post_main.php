<?php
/*
    Exit if accessed directly
*/
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Post_Main' ) ) {

class Post_Main{
    /**
     * Setup the plugin data
     *
     * @since 1.0.0
     */
    function __construct() {

        add_action( 'plugin_action_links', array( 
                    &$this,
                    'post_email_plugin_links'
                ),10,2);
        add_action( 'admin_menu', array(
                    &$this,
                    'post_email_admin_menus'
                ));
        add_action( 'admin_enqueue_scripts', array( 
                    &$this,
                    'post_email_admin_scripts'
                ));
        add_action( 'admin_enqueue_scripts', array( 
                    &$this,
                    'post_email_admin_styles'
                ));
        add_action( 'wp_enqueue_scripts', array( 
                    &$this,
                    'post_email_front_scripts'
                ));
        add_action( 'wp_loaded', array( 
                    &$this,
                    'post_email_front_reg_scripts'
                ));
        add_action( 'wp_enqueue_scripts', array( 
                    &$this,
                    'post_email_front_styles'
                ));
        add_action( 'wp_ajax_sendemail_under_post', array(
                    &$this,
                    'sendemail_under_post'
                ));
        add_action( 'wp_ajax_nopriv_sendemail_under_post', array(
                    &$this,
                    'sendemail_under_post'
                )); 
        if ( is_admin() ) {
                    add_action( 'load-post.php', array(
                    &$this,
                    'post_email_address'
                ));
        }
        if ( is_admin() ) {
                    add_action( 'load-post-new.php', array(
                    &$this,
                    'post_email_address'
                ));
        }         
        add_action( 'init', array(
                    &$this, 
                    'post_email_localizations'
                  ) );      
        
        add_action( 'phpmailer_init', array(
                    $this,
                    'mandrill_emailer_phpmailer_init' 
                    ));
        add_filter( 'the_content', array(
                        &$this,
                        'post_email_frontend'
                ));
        add_action( 'save_post', array(
                                        &$this,
                                        'save_email_address'
                                    ), 10, 2 );

        

        
    }

    public function save_email_address($post_id, $post)
    {
        $post_type = get_post_type_object( $post->post_type );

          /* Check if the current user has permission to edit the post. */
          if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
            return $post_id;

          /* Get the posted data and sanitize it for use as an HTML class. */
          $new_email_addr = ( isset( $_POST['email_addr'] ) ?  $_POST['email_addr']  : '' );

          /* Get the meta key. */
          $email_addr_key = 'email_addr_key';

          /* Get the meta value of the custom field key. */
          $email_addr = get_post_meta( $post_id, $email_addr_key, true );

          /* If a new meta value was added and there was no previous value, add it. */
          if ( $new_email_addr && '' == $email_addr )
            add_post_meta( $post_id, $email_addr_key, $new_email_addr, true );

          /* If the new meta value does not match the old value, update it. */
          elseif ( $new_email_addr && $new_email_addr != $email_addr )
            update_post_meta( $post_id, $email_addr_key, $new_email_addr );

          /* If there is no new meta value but an old value exists, delete it. */
          elseif ( '' == $new_email_addr && $email_addr )
            delete_post_meta( $post_id, $email_addr_key, $email_addr );
        
        /*Hidden Email*/
        $new_email_addr_hidden = ( isset( $_POST['email_addr_hidden'] ) ?  $_POST['email_addr_hidden']  : '' );

          /* Get the meta key. */
          $email_addr_hidden_key = 'email_addr_hidden_key';

          /* Get the meta value of the custom field key. */
          $email_addr_hidden = get_post_meta( $post_id, $email_addr_hidden_key, true );

          /* If a new meta value was added and there was no previous value, add it. */
          if ( $new_email_addr_hidden && '' == $email_addr_hidden )
            add_post_meta( $post_id, $email_addr_hidden_key, $new_email_addr_hidden, true );

          /* If the new meta value does not match the old value, update it. */
          elseif ( $new_email_addr_hidden && $new_email_addr_hidden != $email_addr_hidden )
            update_post_meta( $post_id, $email_addr_hidden_key, $new_email_addr_hidden );

          /* If there is no new meta value but an old value exists, delete it. */
          elseif ( '' == $new_email_addr_hidden && $email_addr_hidden )
            delete_post_meta( $post_id, $email_addr_hidden_key, $email_addr_hidden );

        /*Hidden Email*/
        $new_email_addr_subject = ( isset( $_POST['email_addr_subject'] ) ?  $_POST['email_addr_subject']  : '' );

          /* Get the meta key. */
          $email_addr_subject_key = 'email_addr_subject_key';

          /* Get the meta value of the custom field key. */
          $email_addr_subject = get_post_meta( $post_id, $email_addr_subject_key, true );

          /* If a new meta value was added and there was no previous value, add it. */
          if ( $new_email_addr_subject && '' == $email_addr_subject )
            add_post_meta( $post_id, $email_addr_subject_key, $new_email_addr_subject, true );

          /* If the new meta value does not match the old value, update it. */
          elseif ( $new_email_addr_subject && $new_email_addr_subject != $email_addr_subject )
            update_post_meta( $post_id, $email_addr_subject_key, $new_email_addr_subject );

          /* If there is no new meta value but an old value exists, delete it. */
          elseif ( '' == $new_email_addr_subject && $email_addr_subject )
            delete_post_meta( $post_id, $email_addr_subject_key, $email_addr_subject );


          /*Hidden Email*/
        $new_email_delay = ( isset( $_POST['email_delay'] ) ?  $_POST['email_delay']  : '' );

          /* Get the meta key. */
          $email_delay_key = 'email_delay_key';

          /* Get the meta value of the custom field key. */
          $email_delay = get_post_meta( $post_id, $email_delay_key, true );

          /* If a new meta value was added and there was no previous value, add it. */
          if ( $new_email_delay && '' == $email_delay )
            add_post_meta( $post_id, $email_delay_key, $new_email_delay, true );

          /* If the new meta value does not match the old value, update it. */
          elseif ( $new_email_delay && $new_email_delay != $email_delay )
            update_post_meta( $post_id, $email_delay_key, $new_email_delay );

          /* If there is no new meta value but an old value exists, delete it. */
          elseif ( '' == $new_email_delay && $email_delay )
            delete_post_meta( $post_id, $email_delay_key, $email_delay );
    }
    public function post_email_address( )
    {
        add_action( 'add_meta_boxes', array(
                    $this,
                    'post_email_metabox'
        ));
    }
    /**
     * Add meta box under post in edit screen
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_metabox() {
        $post_types = get_option( 'post_email_show_posts_form' );    
        if(!empty( $post_types )){
                foreach ( $post_types as $post_type ) {  
                    add_meta_box('post_email_add', 
                        'Post email Settings.', 
                        array(
                            'Post_Main',// class object
                            'post_email_new_email_address'//callback functions
                        ), 
                        $post_type,// selected posts types 
                        'normal',  
                        'high'      
                    ); 
          }
      }      
            
    }
    public static function post_email_new_email_address($object, $box)
    {
        ?>
        <p>
            <label for="email_addr"><?php _e( "Email Address", 'post-email' ); ?></label>
            <br />
            <input class="widefat" type="text" name="email_addr" id="email_addr" value="<?php echo esc_attr( get_post_meta( $object->ID, 'email_addr_key', true ) ); ?>" size="30" />
        </p>
        <p>
            <label for="email_addr_hidden"><?php _e( "Hidden Email Address", 'post-email' ); ?></label>
            <br />
            <input class="widefat" type="text" name="email_addr_hidden" id="email_addr_hidden" value="<?php echo esc_attr( get_post_meta( $object->ID, 'email_addr_hidden_key', true ) ); ?>" size="30" />
        </p>
        <p>
            <label for="email_addr_subject"><?php _e( "Email Subject", 'post-email' ); ?></label>
            <br />
            <input class="widefat" type="text" name="email_addr_subject" id="email_addr_subject" value="<?php echo esc_attr( get_post_meta( $object->ID, 'email_addr_subject_key', true ) ); ?>" size="30" />
        </p>
        <p>
            <label for="email_delay"><?php _e( "How many emails after send email to i-e 5", 'post-email' ); ?></label>
            <br />
            <input class="widefat" type="text" name="email_delay" id="email_delay" value="<?php echo esc_attr( get_post_meta( $object->ID, 'email_delay_key', true ) ); ?>" size="30" />
        </p>
        <?php
    }
    public function mandrill_emailer_phpmailer_init( $phpmailer ) {
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = "tls";
            
        $phpmailer->Host = "smtp.mandrillapp.com";
        $phpmailer->Port = "587";
     
        // Credentials for SMTP authentication
        $phpmailer->Username = get_option("mandrill_emailer_username");
        $phpmailer->Password = get_option("mandrill_emailer_api_key");
     
        // From email and name
        $from_name = get_option("mandrill_emailer_from_name");
        if ( !isset( $from_name ) ) {
            $from_name = 'WordPress';
        }

        $from_email = get_option("mandrill_emailer_from_email");        
        if ( !isset( $from_email ) ) {
            // Get the site domain and get rid of www.
            $sitename = strtolower( $_SERVER['SERVER_NAME'] );
            if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                $sitename = substr( $sitename, 4 );
            }

            $from_email = 'wordpress@' . $sitename;
        }
            
        $phpmailer->From = $from_email;
        $phpmailer->FromName = $from_name;
    }
    /**
     * Setup localization 
     * @access public
     * @param void
     * @since 1.0.0
     */
    public function post_email_localizations()
    {
        $plugin_dir = basename(dirname(dirname( __FILE__ )));
        load_plugin_textdomain( 'post-email', false , $plugin_dir . '/lang/');
    }
    public function sendemail_under_post()
    {
        global  $wpdb; 
        $error =array();
        $name           =    sanitize_text_field( $_POST['name'] );

        $email          =    sanitize_text_field( $_POST['email'] );

        $phone          =    sanitize_text_field( $_POST['phone'] );

        $message        =    str_replace( "\n", '<br />',$_POST['message'] );

        $post_id        =    sanitize_text_field( $_POST['post_id'] );

        $user_id        =    sanitize_text_field( $_POST['user'] );
        if( empty( $name ) ){
            $error[] = "Please write your Name";
        }
        if( empty( $email ) &&  is_email( $email )){
            $error[] = "Please write your email";
        }
        if( empty( $phone ) ){
            $error[] = "Please write your phone";
        }
        if( empty( $message ) ){
            $error[] = "Please write some message";
        }
        //$resp = recaptcha_check_answer (PRIV_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);  
        if (empty($_POST['recaptcha_response_field'])) {  
             //Captcha was entered incorrectly  
            $error[] = "Captcha was entered incorrectly";  
        }
        else{
          $captcha = $_POST['recaptcha_response_field'];
          $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".PRIV_KEY."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        //print_r($response);
          if($response.'success'==false){
            $error[] = 'Captcha was entered incorrectly';
          }
        }
        
        if(!empty($error)){
            echo '<div class="alert alert-danger" role="alert">';
                
                foreach ($error as $err) {
                   
                   echo $err . '<br/>';

                }
            echo '</div>';
        }
        else{

            $to             =    get_post_meta( $post_id , 'email_addr_key', true );
            $subject        =    get_post_meta( $post_id , 'email_addr_subject_key', true );
            $hidden_to      =    get_post_meta( $post_id , 'email_addr_hidden_key', true );
            $to_name        =    get_the_author_meta( 'user_nicename', $user_id);
            $postmails = $wpdb->prefix.'postmails';
            $data = array( 
                            'name'=>esc_sql( trim($name)), 
                            'subject'=>esc_sql( trim($subject)), 
                            'hidden_to'=>esc_sql( trim($hidden_to)), 
                            'email'=> esc_sql( trim($email) ), 
                            'phone'=> esc_sql( trim($phone) ), 
                            'message'=> esc_sql( $message ), 
                            'post_id'=> esc_sql( trim($post_id) ), 
                            'user_id' => esc_sql( trim($user_id) ),
                            'add_on' => date('Y-m-d H:i:s'),
                        );
            $format = array( '%s','%s','%s','%s','%s','%s','%d', '%d' );
            $data_query = "SELECT * FROM $postmails where post_id = '$post_id'";
            $rows = $wpdb->get_results( $data_query, OBJECT );
            $name = ''; 

            if(!$to){
             $to = get_the_author_meta('user_email',$user_id);
            }
            if(!$subject){
             $subject = 'Email from'.$email;
            }
            if(!get_post_meta( $post_id , 'email_delay_key', true )){
              $email_delay = 10;
            }
            else{

              $email_delay = get_post_meta( $post_id , 'email_delay_key', true );

            }

            if(count($rows) >= ( $email_delay ) ){
                    
                    $template       =    get_option( 'post_email_template' );
                    
                    $i = 1;
                    foreach($rows as $row) {

                        $contacts[]="<h4> Contact #".$i." <br/> Name : ".$row->name.' <br>Phone : '.$row->phone.' <br> Email :'.$row->email.'<br> Post :<a href='.get_permalink( $row->post_id ).'>'.get_the_title( $row->post_id ).'</a> </h4> <p>Message :</p><p> '.$row->message."</p>";
                        
                     $i++;   
                    } 
                    foreach ($contacts as $contact) {

                         $con.= $contact."<br/>";
                     } 
                    
                    $email_params   = array(
                                                array( 'name' => 'ALL_CONTACT','content' => $con ),
                                                
                                                
                                            );
                    if( get_option( 'post_email_api_need' ) == 1 ){

                        $mail = $this->mandrill_send_mail( $to, $to_name, $template, $subject, $email_params );
                        $mail = $this->mandrill_send_mail( $hidden_to, $to_name, $template, $subject, $email_params );
                    }
                    else if( get_option( 'post_email_api_need' ) == 0 ){
                      $headers ='';
                      $headers .= 'From: '.get_option( 'mandrill_emailer_from_name' ).' <'.get_option( 'mandrill_emailer_from_email' ).'>' . "\r\n";
                      $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
                      $mail = mail($to,$subject,$con, $headers);
                      $h1=1;
                    }
                    if($mail){
                       echo '<div class="alert alert-success" role="alert">'.get_option( 'post_email_success' ).'</div>';
                        $query = "DELETE FROM $postmails WHERE post_id = '$post_id'";
                        $wpdb->query($query);
                    }
                    else{
                      echo '<div class="alert alert-success" role="alert">'.get_option( 'post_email_error' ).'</div>';
                    }                    
                }
                else{

                    $wpdb->insert(

                                $postmails, 
                                $data, 
                                $format
                            );

                    echo '<div class="alert alert-success" role="alert">'.get_option( 'post_email_success' ).'</div>';
                }
        }
        die();
    }
    
    /**
     * Add links in plugin oage to perform direct action
     *
     * @access public
     * @param array $links , file $file for plugin reffernce
     * @return array $links
     * @since 1.0.0
     */
    public function post_email_plugin_links( $links, $file ) {
            
            static $cureent_plugin;
            
            if ( empty( $cureent_plugin ) ) 

                $cureent_plugin = 'post-email/postemail-wordpress.php';

            if ( $file == $cureent_plugin ) {

                $settings_link = '<a href="' . admin_url( 'admin.php?page=post-email-settings' ) . '">' . __( 'Settings', 'post-email' ) . '</a> | <a href="' . admin_url("admin.php?page=post-email-dashboard") . '">' . __( 'Dashboard', 'post-email' ) . '</a>';

                array_unshift( $links, $settings_link );
            }
            return $links;
    }
    /**
     * Add menus in admin menu
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public static function post_email_admin_menus() {
            
            add_menu_page( POST_EMAIL_PLUGIN_NAME, __( POST_EMAIL_PLUGIN_NAME , 'post-email' ), 'manage_options', 'post-email-dashboard', array(
                             __CLASS__,
                            'post_email_plugin_dashboard_page'

                ), plugins_url( 'images/post-email.png', dirname(__FILE__)));
            
            add_submenu_page( 'post-email-dashboard', POST_EMAIL_PLUGIN_NAME . ' Dashboard', __('Dashboard','post-email') , 'manage_options', 'post-email-dashboard', array(
                                __CLASS__,
                                'post_email_plugin_dashboard_page'

                ));
            add_submenu_page( 'post-email-dashboard', POST_EMAIL_PLUGIN_NAME . ' Settings', __('Settings','post-email') , 'manage_options', 'post-email-settings', array(
                                __CLASS__,
                                'post_email_plugin_settings_page'

                ));
            
    }
    /**
     * Show alerts for profile and authenticate process
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_add_alerts(){

            add_action( 'admin_footer', array( &$this, 'post_email_show_profile_alerts' ) );
    }
    
    
    /**
     * Add Settings Page
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public static function post_email_plugin_settings_page() {
       
       require_once POST_EMAIL_ROOT_PATH . '/plugin-pages/post-email-settings.php';
    }
    /**
     * Add dashboard Page
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public static function post_email_plugin_dashboard_page() {
       
       require_once POST_EMAIL_ROOT_PATH . '/plugin-pages/post-email-dashboard.php';
    }
    /**
     * Add stylesheets in admin head
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_admin_styles( ) {
            
            wp_enqueue_style( 'post-email-bootstrap', plugins_url( 'css/bootstrap.css', dirname(__FILE__) ),false, POST_EMAIL_VERSION);
            wp_enqueue_style( 'post-email-choosen', plugins_url( 'css/chosen.css', dirname(__FILE__) ),false, POST_EMAIL_VERSION);
            wp_enqueue_style( 'post-email-style', plugins_url( 'css/post-email-style.css', dirname(__FILE__)),false, POST_EMAIL_VERSION);
             
    }
    /**
     * Add Scripts in admin head
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_admin_scripts(  ) {
           
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'post_email_graphs', 'https://www.google.com/jsapi', false, POST_EMAIL_VERSION );
            wp_enqueue_script ( 'post_email_bootstrap', plugins_url('js/bootstrap.js', dirname(__FILE__)), false, POST_EMAIL_VERSION);
            wp_enqueue_script ( 'post_email_choosen', plugins_url('js/chosen.jquery.min.js', dirname(__FILE__)), false, POST_EMAIL_VERSION);
            wp_enqueue_script ( 'post_email_adminjs', plugins_url('js/post-email-admin.js', dirname(__FILE__)), false, POST_EMAIL_VERSION);

    }
    /**
     * Add Scripts in Front site
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_front_reg_scripts( ){
       $ajax_array = array(
                                        
                                'ajaxurl' => admin_url().'/admin-ajax.php'
                            );
        wp_register_script ( 'post_email_front_script', plugins_url('js/post-email.js', dirname(__FILE__)), false, POST_EMAIL_VERSION);
        wp_localize_script ( 'post_email_front_script','ajax_params', $ajax_array);
    }
    public function post_email_front_scripts( )
    {
       
        wp_enqueue_script ( 'jquery' );
        wp_enqueue_script ( 'post_email_front_script');
        wp_enqueue_script ( 'post_email_front_bootstrap', plugins_url('js/bootstrap.js', dirname(__FILE__)), false, POST_EMAIL_VERSION);
        wp_enqueue_script ( 'post_email_front_captcha', 'https://www.google.com/recaptcha/api.js', false, POST_EMAIL_VERSION);

    }
    
    /**
     * Add Stylesheets in Front site 
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_front_styles( )
    {
        wp_enqueue_style( 'post-email-front-bootstrap', plugins_url( 'css/bootstrap.css', dirname(__FILE__) ),false, POST_EMAIL_VERSION);
    }
    public static function post_email_dashboard_page() {
        
        require_once POST_EMAIL_ROOT_PATH . '/plugin-pages/analytics-wordpress-dashboard.php';
            
    }
    
    /**
     * show profile and authenticate Alerts
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_show_profile_alerts() {
                        
            if(! get_option( 'mandrill_emailer_api_key' ) && get_option( 'post_email_api_need' ) == 1){
                    
                    echo '<div class="error" style="background:#f2dede;""><p><strong>' . __( 'Please Set your Mandrill API ', 'post-email' ) . '</p></div>';
            }
            
    }
    public function post_email_dashboard(){
        
        try {   
            // Init Mandrill API
            $mandrill = new Mandrill( get_option( 'mandrill_emailer_api_key' ) );
             $result = $mandrill->users->info();
            return $result;
        }
        catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Invalid_Key - Invalid API key
            throw $e;
        }
      
      
    }
    public function post_email_senders(){
        try {   
            // Init Mandrill API
            $mandrill = new Mandrill( get_option( 'mandrill_emailer_api_key' ) );
             $result = $mandrill->users->senders();
            return $result;
        }
        catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Invalid_Key - Invalid API key
            throw $e;
        }
    }
    public function mandrill_send_mail( $to, $to_name, $template, $subject, $data ) {
        try {   
            // Init Mandrill API
            $mandrill = new Mandrill( get_option( 'mandrill_emailer_api_key' ) );

            // From email and name      
            $from_name = get_option( 'mandrill_emailer_from_name' );
            if ( !isset( $from_name ) ) {
                $from_name = 'WordPress';
            }
            
            $from_email = get_option( 'mandrill_emailer_from_email' );      
            if ( !isset( $from_email ) ) {
                // Get the site domain and get rid of www.
                $sitename = strtolower( $_SERVER['SERVER_NAME'] );
                if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                    $sitename = substr( $sitename, 4 );
                }

                $from_email = 'wordpress@' . $sitename;
            }
        
            // Message recipient and contents 
            $mandrill_to = array( array( 'email' => $to, 'name' => $to_name, 'type' => 'to' ) );
                
            $message = array(
                'subject' => $subject,
                'from_email' => apply_filters( 'wp_mail_from', $from_email ),
                'from_name' => apply_filters( 'wp_mail_from_name', $from_name ),
                'to' => $mandrill_to,
            
                // Pass the same parameters for merge vars and template params
                // to make them available in both variable passing methods
                'global_merge_vars' => $data        
            );
            
            $result = $mandrill->messages->sendTemplate( $template, $data, $message );

            return true;
        } catch(Mandrill_Error $e) {
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            return false;
        }
    }
    /**
     * show Stats in frontend
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public function post_email_frontend( $content ) {
        global $post;
        ob_start();

        if ( is_singular()) {
          $post_type = get_post_type( $post->ID );
           if( is_array( get_option( 'post_email_show_posts_form' )) and !in_array( $post_type, get_option( 'post_email_show_posts_form' ) ) ) {

                return $content;
            }

            if ( is_array( get_option( 'post-email-ex-posts' ) ) ) {
                    
                if ( in_array( $post->ID, get_option( 'post-email-ex-posts' ) ) ) {
                            
                    return $content;
                }
            }
        ?>

        <div class="col-md-12">
            <div class="well well-sm">
            <form name="postemail" id="postemail">
            <div class="result" style="display:none"></div>
                <div class="row">
                    <div class="col-md-12">
                    <div class="loading" style="display:none">
                        <img src="<?php echo plugins_url('images/loading.gif',dirname(__FILE__));;?>">
                    </div>
                    
                        <div class="form-group">
                            
                            <div class="input-group">
                                
                            <input type="text" class="form-control" id="name" placeholder="Enter name" required="required" />
                        </div>
                        </div>
                        <div class="form-group">
                            
                            <div class="input-group">
                                
                                <input type="email" class="form-control" id="email" placeholder="Enter email" required="required" /></div>
                        </div>
                        <div class="form-group">
                            
                            <div class="input-group">
                               
                                <input type="text" class="form-control" id="phone" placeholder="Enter Phone number" required="required" /></div>
                        </div>
                        <input type="hidden" id="post_id" value="<?php echo $post->ID; ?>">                    
                        <input type="hidden" id="user" value="<?php echo $post->post_author; ?>">                    
                        <div class="form-group">
                           <?php 
                           $settings = array(
                                            'media_buttons' => false,
                                            'quicktags'     => array("buttons"=>"strong"),
                                            'textarea_name' => "message",
                                            'tinymce'       => false,
                                        );
                                wp_editor( '', 'message', $settings);
                            ?>
                                <!-- <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                placeholder="Message"></textarea> -->
                            
                        </div> 
                        <div class="form-group">
                            <?php //echo recaptcha_get_html(PUB_KEY); ?>
                             <div class="g-recaptcha" data-sitekey="<?php echo PUB_KEY;?>"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary pull-right" id="send_mail">
                            Send Message
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <?php
        $content .= ob_get_contents();
        ob_get_clean();
    }
        return $content;
    }
    
    
    /**
     * Show ananlytics under post with ajax request
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public static function post_email_admin_single_analytics() {
            
    }
    /**
     * Save options after activate plugin
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */
    public static function post_email_install_plugin() {
      update_option( 'post_email_api_need',1 );
      update_option( 'post_email_success','You Email has been sent' );
      update_option( 'post_email_error','Email sending Fails' );
      update_option( 'post_email_template','khubbaib' );
      update_option( 'post-email-ex-posts','0' );
        global $wpdb;

        $table_name = $wpdb->prefix . "postmails";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id int(11) NOT NULL AUTO_INCREMENT,
          name      varchar(100)  NULL,
          hidden_to varchar(100)  NULL,
          subject   varchar(100)  NULL,
          email     varchar(100)  NULL,
          message   text DEFAULT ''  NULL,
          phone     varchar(100)  NULL,
          post_id   int(11)  NULL,
          user_id   int(11)  NULL,
          add_on    varchar(100)  NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql ); 
           
     }
    /**
     * Delete options after activate plugin
     *
     * @access public
     * @param void
     * @return void
     * @since 1.0.0
     */ 
    public static function post_email_uninstall_plugin() {
            
           
        }
    }
}

?>