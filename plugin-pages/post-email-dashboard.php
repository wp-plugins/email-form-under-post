<?php
  /*
  * Exit if accessed directly
  */
  if ( ! defined( 'ABSPATH' ) ) exit;
  $post_main = new Post_Main();
  if ( isset( $_POST['save_export'] ) ) {
   $email = $_POST['post_email_export_notify'];
   $from = $_POST['post_email_export_from'];
   $to = $_POST['post_email_export_to'];
   $export = $post_main->post_email_add_epxort( $email,$from,$to, array('sent') );
   $msg = 'Your export is in '.$export['state'].' state';

 }
 if( isset( $_POST['sent_newsletter'] ) ){
  $newsletter_emails = $_POST['newsletter_emails'];
  $newsletter_sender = $_POST['post_email_sender'];
  $newsletter_subject = $_POST['post_email_subject'];
  $message = $_POST['post_email_newsletter_message'];
  $subject = $_POST['post_email_subject'];

  $newsletter = $post_main->post_email_send_newsletter( $message ,$newsletter_sender,$newsletter_subject,$newsletter_emails);

  $msg = 'Your NewsLetter has been sent';
}
?>

<div class="wrap">

  <h2 class='opt-title' id="title">
    <span class='post-email-logo'>

      <img src="<?php echo plugins_url('images/post_img_large.png',dirname(__FILE__));;?>" alt="">

    </span>
    <span class="intro-text"><?php _e( 'Mandrill for WordPress', 'post-email' ); ?></span>
  </h2>   
  <?php 
  if (isset($msg)) {
    echo '<div id="setting-error-settings_updated" class="updated below-h2"><p>'. $msg.'</p></div>';
  } 
  ?>
  <ul class="nav pos-tabs nav-justified nav-tabs" role="tablist">
    <li class="active">
      <a href="#main" role="tab" data-toggle="tab">
        <i class="glyphicon glyphicon-tasks"></i>
        <?php  _e('Stats','post-email'); ?>
      </a>
    </li>
    <?php if( get_option( 'post_email_api_need' ) == 1 ) { ?>
    <li>
      <a href="#basic-settings" role="tab" data-toggle="tab">
        <i class="glyphicon glyphicon-send"></i>
        <?php _e('Senders','post-email') ?>
      </a>
    </li>
    <li>
      <a href="#tab-domains" role="tab" data-toggle="tab">
        <i class="glyphicon glyphicon-share-alt"></i>
        <?php _e('Posts link','post-email') ?>
      </a>
    </li>
    <li>
      <a href="#export" role="tab" data-toggle="tab">
        <i class="glyphicon glyphicon-arrow-down"></i>
        <?php _e('Exports','post-email') ?>
      </a>
    </li>
    <li>
      <a href="#email" role="tab" data-toggle="tab">
        <i class="glyphicon glyphicon-comment"></i>
        <?php _e('Send Email','post-email') ?>
      </a>
    </li>
    <li>
      <a href="#search-email" role="tab" data-toggle="tab">
        <i class="glyphicon glyphicon-filter"></i>
        <?php _e('Search Email','post-email') ?>
      </a>
    </li>
    <?php } ?>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content pos-tab-pane">
    <div class="tab-pane active" id="main">
     <?php if( get_option('mandrill_emailer_api_key') ){ ?>
     <h1><?php _e( 'Profile Info.', 'post-email' ); ?></h1>

     <?php $stat = $post_main->post_email_dashboard();  ?>
     <table class="table table-striped">
      <thead>
        <tr>
          <th><?php _e('User Name','post-email')?></th>
          <th><?php _e('Reputation','post-email')?></th>
          <th><?php _e('Hourly Quota','post-email')?></th>
        </tr>
      </thead>
      <tbody>

        <tr>
          <td><?php echo $stat['username'] ?></td>
          <td><?php echo $stat['reputation']; ?></td>
          <td><?php echo $stat['hourly_quota']; ?></td>
        </tr>

      </tbody>
    </table>
    <h1><?php _e('Email Sent Stats','post-email')?></h1>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"],'callback': drawChart});
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Today', 'Emails'],

          ['Today Sent',   <?php echo $stat['stats']['today']['sent']; ?>],
          ['Last 7 days',  <?php echo $stat['stats']['last_7_days']['sent']; ?>],
          ['Last 30 days', <?php echo $stat['stats']['last_30_days']['sent']; ?>],
          ['Last 60 days', <?php echo $stat['stats']['last_60_days']['sent']; ?>],
          ['Last 90 days', <?php echo $stat['stats']['last_90_days']['sent']; ?>],
          ['All Time',     <?php echo $stat['stats']['all_time']['sent']; ?>],

          ]);

        var options = {
          is3D: true,
          legend:true,

        };

        var chart = new google.visualization.PieChart(document.getElementById('sent-chart'));
        chart.draw(data, options);
      }
    </script>
    
    <div id="sent-chart" style="width:100%; height:308px;margin: 0 auto;"></div>
    <?php 
  }
  else{
   _e('No API key provided','post-email');
 }
 ?>
</div>
<?php if( get_option( 'post_email_api_need' ) == 1 ) { ?>
<div class="tab-pane" id="basic-settings">
  <?php if( get_option('mandrill_emailer_api_key') ){ 
    $senders_stats = $post_main->post_email_senders();
    ?>
    <script type="text/javascript">
     google.load("visualization", "1.1", {packages:["bar"]});
     google.setOnLoadCallback(drawChart);
     function drawChart() {

      var data = google.visualization.arrayToDataTable([
        ['Senders', 'Sent','Clicks'],
        <?php $i = 1;
        foreach ($senders_stats as $senders_stat) {
          if($senders_stat['sent']==0 && $i<=5){
            continue;
          }
          ?>
          ['<?php echo $senders_stat["address"]; ?>', <?php echo $senders_stat['sent']; ?>,<?php echo $senders_stat['clicks']; ?>],
          <?php } ?>
          ]);

      var options = {
        width: 900,
        title: 'Senders with email sents and clicks',
        legend: { position: 'right', maxLines: 3 },
        isStacked: true,
        axes: {
          x: {
                      0: { side: 'top', label: 'Senders with email sents and clicks'} // Top x-axis.
                    }
                  },
                  bar: { groupWidth: "50%" }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('senders'));

                chart.draw(data, google.charts.Bar.convertOptions(options));

              }
            </script> 


            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th><?php _e('Email Address','post-email')?></th>
                  <th><?php _e('Created At','post-email')?></th>
                  <th><?php _e('Sent','post-email')?></th>
                  <th><?php _e('Clicks','post-email')?></th>
                </tr>
              </thead>
              <tbody>
                <?php  

                $i = 1;
                foreach ($senders_stats as $senders_stat) {

                  ?>
                  <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $senders_stat['address'] ?></td>
                    <td><?php echo $senders_stat['created_at'] ?></td>
                    <td><?php echo $senders_stat['sent'] ?></td>
                    <td><?php echo $senders_stat['clicks'] ?></td>
                  </tr>
                  <?php
                  $i++;
                }
                ?>
              </tbody>
            </table>
            <div id="senders" style="width:900px; height:308px;margin: 0 auto;"></div>
            <?php 
          }
          else{
           _e('No API key provided','post-email');
         }
         ?>
       </div>
       <div class="tab-pane" id="tab-domains">
        <?php if( get_option('mandrill_emailer_api_key') ){ ?>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th><?php _e('Links','post-email')?></th>
              <th><?php _e('Sent','post-email')?></th>
              <th><?php _e('Clicks','post-email')?></th>
              <th><?php _e('Unique Clicks','post-email')?></th>
            </tr>
          </thead>
          <tbody>
            <?php  
            $domains_stats = $post_main->post_email_domains();
            $i = 1;
            foreach ($domains_stats as $domains_stat) {

              ?>
              <tr>
                <th scope="row"><?php echo $i; ?></th>
                <td><a href="<?php echo $domains_stat['url'] ?>" target="_blank"><?php echo $domains_stat['url'] ?></a></td>
                <td><?php echo $domains_stat['sent'] ?></td>
                <td><?php echo $domains_stat['clicks'] ?></td>
                <td><?php echo $domains_stat['unique_clicks'] ?></td>
              </tr>
              <?php
              $i++;
            }
            ?>
          </tbody>
        </table>
        <?php 
      }
      else{
       _e('No API key provided','post-email');
     }
     ?>
   </div>
   <div class="tab-pane" id="export">
    <h3><?php _e('All Export Jobs','post-email') ?></h3>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th><?php _e('State','post-email')?></th>
          <th><?php _e('Created At','post-email')?></th>
          <th><?php _e('Finished At','post-email')?></th>
          <th><?php _e('Download','post-email')?></th>
        </tr>
      </thead>
      <tbody>
        <?php  
        $all_exports = $post_main->post_email_epxort();
        $i = 1;
        foreach ($all_exports as $all_export) {

          ?>
          <tr>
            <th scope="row"><?php echo $i; ?></th>
            <td><?php echo ucfirst( $all_export['state'] ); ?></td>
            <td><?php echo $all_export['created_at']; ?></td>
            <td><?php echo $all_export['finished_at']; ?></td>
            <td><a href="<?php echo $all_export['result_url'] ?>">Download</a></td>
          </tr>
          <?php
          $i++;
        }
        ?>
      </tbody>
    </table>

    <h3><?php _e('Add New Export','post-email') ?></h3>
    <form action="<?php admin_url() ?>?page=post-email-dashboard&tab=export" method="post">
      <table width="100" class="form-table">
        <tbody>
          <tr>
            <td width="200"><?php _e( 'Notify Email :', 'post-email' ); ?></td>
            <td width="877">
              <input type="text" style="width:600px;" name="post_email_export_notify" required>

            </td>
          </tr>
          <tr>
            <td width="200"><?php _e( 'Date From :', 'post-email' ); ?></td>
            <td width="877">
              <input type="text" style="width:600px;" name="post_email_export_from" class="pick-date">
            </td>
          </tr>
          <tr>
            <td width="200"><?php _e( 'Date to :', 'post-email' ); ?></td>
            <td width="877">
              <input type="text" style="width:600px;" name="post_email_export_to" class="pick-date">
            </td>
          </tr>
          <tr>
            <td>
              <p class="submit">
                <button type="submit" name="save_export"  class="btn btn-post-email"> <?php _e('Save Export','post-email')?> </button>
              </p>
            </td>
          </tr>

        </tbody>
      </table>
    </form>
  </div>
  <div class="tab-pane" id="email">


   <h3><?php _e('Send message to any user','post-email') ?></h3>
   <form action="<?php admin_url() ?>?page=post-email-dashboard&tab=email" method="post">
    <table width="100" class="form-table">
      <tbody>
        <tr>
          <td width="200"><?php _e( 'Your Email :', 'post-email' ); ?></td>
          <td width="877">
            <input type="text" style="width:600px;" name="post_email_sender" required>

          </td>
        </tr>
        <tr>
          <td width="200"><?php _e( 'Subject :', 'post-email' ); ?></td>
          <td width="877">
            <input type="text" style="width:600px;" name="post_email_subject" required>

          </td>
        </tr>
        <tr>
          <td width="200"><?php _e( 'Sent to :', 'post-email' ); ?></td>
          <td width="877">
           <select class ="fancy-choose" name="newsletter_emails" style="width:600px;">
            <?php 
            $blogusers = get_users( 'orderby=nicename' );
            foreach ( $blogusers as $bloguser ) {
              ?>
              <option value="<?php echo esc_html( $bloguser->user_email ) ?>">
                <?php echo esc_html( $bloguser->user_email ) ?>
              </option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="200"><?php _e( 'Message :', 'post-email' ); ?></td>
          <td width="877">
            <?php wp_editor('','newsletter-content',array('media_buttons' => false,'textarea_name'=>'post_email_newsletter_message')) ?>
          </td>
        </tr>
        <tr>
          <td>
            <p class="submit">
              <button type="submit" name="sent_newsletter"  class="btn btn-post-email"> <?php _e('Sent Newsletter','post-email')?> </button>
            </p>
          </td>
        </tr>

      </tbody>
    </table>
  </form>

</div>
<div class="tab-pane" id="search-email">


 <h3><?php _e('Search Specific email','post-email') ?></h3>
 <?php 
 if(isset($_POST['search'])){

  $all_emails= $post_main->post_email_all_email($_POST['search-filter'],intval($_POST['limit']));
} 
else{
  $all_emails=$post_main->post_email_all_email('email:gmail.com',100);
}
?>
<div class="col-md-5">
 <form action="" method="post">
   <input type="text" name="search-filter">
   <select name="limit" class="fancy-choose" style="width:200px;position: relative;top: 8px;">
     <option value="25">25</option>
     <option value="50">50</option>
     <option value="100">100</option>
   </select>
   <input type="submit" name="search" class="btn btn-post-email">
 </form>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th><?php _e('Sender','post-email')?></th>
      <th><?php _e('subject','post-email')?></th>
      <th><?php _e('email','post-email')?></th>
      <th><?php _e('clicks','post-email')?></th>
    </tr>
  </thead>
  <tbody>
    <?php                
    $i = 1;
    foreach ($all_emails as $all_email) { ?>
    <tr>
      <th scope="row"><?php echo $i; ?></th>
      <td><?php echo ucfirst( $all_email['sender'] ); ?></td>
      <td><?php echo $all_email['subject']; ?></td>
      <td><?php echo $all_email['email']; ?></td>
      <td><?php echo $all_email['clicks']?></td>
    </tr>
    <?php
    $i++;
  }
  ?>
</tbody>
</table>

</div>
<?php } ?>
</div>
</div>