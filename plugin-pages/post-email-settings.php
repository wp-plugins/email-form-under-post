<?php
  /*
  * Exit if accessed directly
  */
  if ( ! defined( 'ABSPATH' ) ) exit;
  
  $post_main = new Post_Main();
  
  /*
  * Save information
  */
  if (isset($_POST[ 'save_profile' ])) {
    $api_used =   isset($_POST['post_email_api_need']) ? 1 : 0;
    update_option( 'post_email_api_need',           $api_used);
    update_option( 'mandrill_emailer_api_key',      sanitize_text_field( $_POST['post_email_api'] ));
    update_option( 'mandrill_emailer_username',     sanitize_text_field( $_POST['mandrill_emailer_username'] ));
    update_option( 'mandrill_emailer_from_name',    sanitize_text_field( $_POST['mandrill_emailer_from_name'] ));
    update_option( 'mandrill_emailer_from_email',   sanitize_text_field( $_POST['mandrill_emailer_from_email'] ));
    update_option( 'post_email_template',           sanitize_text_field( $_POST['post_email_template'] ));
    update_option( 'mandrill_emailer_from_email',   sanitize_text_field( $_POST['mandrill_emailer_from_email'] ));
   
    $update_msg = '<p>Information has been Saved.</p>';

  }
  if (isset($_POST[ 'save_plugin' ])) {
    if(isset($_POST[ 'show_on' ])) {
      
      $show_on_posts   = ( $_POST[ 'show_on' ] );
    }
    else{
      
      $show_on_posts = array();
    }
    if(isset($_POST[ 'no-forms' ])){
      
      $no_posts = wp_parse_id_list( $_POST[ 'no-forms' ] );

    }
    else{
       
       $no_posts = array();
    }
    update_option( 'post_email_show_posts_form' , $show_on_posts );
    update_option( 'recaptcha_public',      sanitize_text_field( $_POST['recaptcha_public'] ));
    update_option( 'recaptcha_private',     sanitize_text_field( $_POST['recaptcha_private'] ));
    update_option( 'post_email_success',    sanitize_text_field( $_POST['post_email_success'] ));
    update_option( 'post_email_error',      sanitize_text_field( $_POST['post_email_error'] ));
    update_option( 'post-email-ex-posts', $no_posts);
    $update_msg = '<p>Information has been Saved.</p>';

  }
?>

<div class="wrap">

  <h2 class='opt-title' id="title">
    <span class='post-email-logo'>
    
      <img src="<?php echo plugins_url('images/post_img_large.png',dirname(__FILE__));;?>" alt="">

    </span>
    <span class="intro-text"><?php _e( 'Email Form Under every Post --- Settings', 'post-email' ); ?></span>
  </h2>

  <?php

  if ( isset( $update_msg ) )
  {
    echo '<div id="setting-error-settings_updated" class="updated below-h2"><strong>'.$update_msg.'</strong></div>';
  } 
  
  ?>    
    <ul class="nav pos-tabs nav-justified nav-tabs" role="tablist">
        <li class="active">
          <a href="#main" role="tab" data-toggle="tab">
            <i class="glyphicon glyphicon-envelope"></i>
            <?php  _e('Mandrill Setting','post-email');?>
          </a>
        </li>
        <li>
          <a href="#basic-settings" role="tab" data-toggle="tab">
              <i class="glyphicon glyphicon-cog"></i>
                <?php _e('Plugin Settings','post-email') ?>
          </a>
        </li>
        
    </ul>
  <!-- Tab panes -->
  <div class="tab-content pos-tab-pane">
    <div class="tab-pane active" id="main">
     <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
     <table width="100" class="form-table">
        <tbody>
          <tr>
            <td width="200"><?php _e( 'Do you want to use mandrill server?', 'post-email' ); ?></td>
              <td width="877">
                <input type="checkbox" name="post_email_api_need" id="post_email_api_need" value="1" <?php if( get_option( 'post_email_api_need' ) == 1 ) { echo 'checked'; } ?>>

              </td>
          </tr>
          
      </tbody>
    </table>
    
    <h4 class="mandrill-hide" <?php if( get_option( 'post_email_api_need' ) == 0 ) { ?>style="display:none;"<?php } ?>><?php _e( 'Write Your API key.', 'post-email' ); ?></h4>
    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
      <table width="100" class="form-table">
        <tbody>
          <tr class="mandrill-hide" <?php if( get_option( 'post_email_api_need' ) == 0 ) { ?>style="display:none;"<?php } ?>>
            <td width="200"><?php _e( 'Mandrill API KEY :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="post_email_api" value="<?php echo get_option('mandrill_emailer_api_key') ?>">

              </td>
          </tr>
          <tr class="mandrill-hide" <?php if( get_option( 'post_email_api_need' ) == 0 ) { ?>style="display:none;"<?php } ?>>
            <td width="200"><?php _e( 'Mandrill Email Template :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="post_email_template" value="<?php echo get_option('post_email_template') ?>">

              </td>
          </tr>
          <tr class="mandrill-hide" <?php if( get_option( 'post_email_api_need' ) == 0 ) { ?>style="display:none;"<?php } ?>>
            <td width="200"><?php _e( 'Mandrill User Name :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="mandrill_emailer_username" value="<?php echo get_option('mandrill_emailer_username') ?>">

              </td>
          </tr>

          <tr>
            <td width="115"><?php _e( 'From Name :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="mandrill_emailer_from_name" value="<?php echo get_option('mandrill_emailer_from_name') ?>">

              </td>
          </tr>
          <tr>
            <td width="115"><?php _e( 'From Email :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="mandrill_emailer_from_email" value="<?php echo get_option('mandrill_emailer_from_email') ?>">

              </td>
          </tr>
          <tr>
            <td>
              <p class="submit">
                <button type="submit" name="save_profile"  class="btn btn-filter-stats"> <?php _e('Save Changes','post-email')?> </button>
              </p>
            </td>
          </tr>

      </tbody>
    </table>
  </form>
  
    </div>
    <div class="tab-pane" id="basic-settings">
      <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
        <table width="100" class="form-table">
        <tbody>
          <tr>
            <td width="200"><?php _e( 'ReCAPTCHA Public KEY :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="recaptcha_public" value="<?php echo get_option('recaptcha_public') ?>">

              </td>
          </tr>
          <tr>
            <td width="200"><?php _e( 'ReCAPTCHA Private KEY :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="recaptcha_private" value="<?php echo get_option('recaptcha_private') ?>">

              </td>
          </tr>
          <tr>
            <td width="200"><?php _e( 'Success Message When email send  :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="post_email_success" value="<?php echo get_option('post_email_success') ?>">

              </td>
          </tr>
          <tr>
            <td width="200"><?php _e( 'Error message when email fail to send :', 'post-email' ); ?></td>
              <td width="877">
                <input type="text" style="width:600px;" name="post_email_error" value="<?php echo get_option('post_email_error') ?>">

              </td>
          </tr>
          <tr>
                    <td width="199"><?php _e( 'Form Under Posts ' ,'post-email'); ?></td>
                    <td>
                       <select class ="fancy-choose" name="show_on[]" multiple style="width:600px;">
                            <?php 
                                $post_types = get_post_types();
                                   foreach ( $post_types as $post_type ) {
                            ?>
                            <option value="<?php echo $post_type ?>" 
                                <?php if ( is_array( get_option( 'post_email_show_posts_form' ) ) ) 
                                      {
                                        selected(in_array($post_type, get_option('post_email_show_posts_form')));
                                      }  
                                ?>>
                                <?php echo $post_type; ?>
                            </option>
                          <?php } ?>
                       </select>
                    </td>
                  </tr>
          <tr>
                <td>Exclude Posts to not show Form </td>
                <td>
                  <input type="text" name="no-forms" <?php if(is_array(get_option('post-email-ex-posts'))){ ?> value="<?php echo implode( ',', get_option('post-email-ex-posts') ); ?>" <?php } ?> style="width:600px;">
                  <p class="description">Posts IDS separate with comma i-e (25,27)</p>
                </td>
          </tr>
          <tr>
            <td>
              <p class="submit">
                <button type="submit" name="save_plugin"  class="btn btn-filter-stats"> <?php _e('Save Changes','post-email')?> </button>
              </p>
            </td>
          </tr>
      </tbody>
    </table>
      </form>
    </div>
  </div>
</div>