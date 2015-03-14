  jQuery(document).ready(function ($) {
    
        $("#send_mail").click(function () {
          var post_id = $("#post_id").val();
          var user    = $("#user").val();
          var name    = $("#name").val();
          var email   = $("#email").val();
          var message   = $("#message").val();
          //var ed = tinyMCE.get('message');
         // var html = ed.getContent();
          //var message = html;
          var phone   = $("#phone").val();
          var recaptcha_response_field   = $("#g-recaptcha-response").val();
          //var recaptcha_challenge_field   = $("#recaptcha_challenge_field").val();
          $.ajax({
              type: 'POST',
              url: ajax_params.ajaxurl,
              data: 'action=sendemail_under_post&name=' + name + "&email=" + email+"&message="+message+"&phone="+phone+"&post_id="+post_id+"&user="+user+"&recaptcha_response_field="+recaptcha_response_field,
              beforeSend: function () {
                  $(".loading").css("display", "block").css("text-align", "center");
                                   
              },
              success: function (data, textStatus, XMLHttpRequest) {
                  $(".loading").css("display", "none");
                  $(".result").html(data).css("display", "block");
                   $("#user").val('');
                   $("#name").val('');
                   $("#email").val('');
                   $("#message").val('');
                   //ed.getContent('');
                   $("#phone").val('');
                  
              },
              error: function (MLHttpRequest, textStatus, errorThrown) {
                  console.log("Some Thing Wen wrong try again.....");
                  Recaptcha.reload(); 
              }
          });

      }); 
   
  });
  