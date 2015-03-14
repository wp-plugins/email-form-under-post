<?php
  /*
  * Exit if accessed directly
  */
  if ( ! defined( 'ABSPATH' ) ) exit;
 $post_main = new Post_Main();
?>

<div class="wrap">

  <h2 class='opt-title' id="title">
    <span class='post-email-logo'>
    
      <img src="<?php echo plugins_url('images/post_img_large.png',dirname(__FILE__));;?>" alt="">

    </span>
    <span class="intro-text"><?php _e( 'Email Form Under every Post', 'post-email' ); ?></span>
  </h2>   
    <ul class="nav pos-tabs nav-justified nav-tabs" role="tablist">
        <li class="active">
          <a href="#main" role="tab" data-toggle="tab">
            <i class="glyphicon glyphicon-tasks"></i>
            <?php  _e('Stats','post-email'); ?>
          </a>
        </li>
        <li>
          <a href="#basic-settings" role="tab" data-toggle="tab">
              <i class="glyphicon glyphicon-cog"></i>
                <?php _e('Senders','post-email') ?>
          </a>
        </li>
        
    </ul>
  <!-- Tab panes -->
  <div class="tab-content pos-tab-pane">
    <div class="tab-pane active" id="main">
     <?php if( get_option('mandrill_emailer_api_key') ){ ?>
    <h4><?php _e( 'Profile Info.', 'post-email' ); ?></h4>
    
    <?php 

      $stat = $post_main->post_email_dashboard();
      //print_r($stat);
      //echo $stat['stats']['today']['sent'];
      echo "<b>User Name :</b> ".$stat['username'].'<br>';
      echo "<b>Reputation :</b> ".$stat['reputation'].'<br>';
      echo "<b>Hourly quota :</b> ".$stat['hourly_quota'].'<br>';
      
    ?>
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
    <div class="tab-pane" id="basic-settings">
    <?php if( get_option('mandrill_emailer_api_key') ){ ?>
      <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th><?php _e('Email Address','post-email')?></th>
          <th><?php _e('Created At','post-email')?></th>
          <th><?php _e('Sent','post-email')?></th>
        </tr>
      </thead>
      <tbody>
        <?php  
          $senders_stats = $post_main->post_email_senders();
          $i = 1;
          foreach ($senders_stats as $senders_stat) {
            
        ?>
        <tr>
          <th scope="row"><?php echo $i; ?></th>
          <td><?php echo $senders_stat['address'] ?></td>
          <td><?php echo $senders_stat['created_at'] ?></td>
          <td><?php echo $senders_stat['sent'] ?></td>
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
  </div>
</div>