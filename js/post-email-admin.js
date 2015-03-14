jQuery(document).ready(function($){

    $('#post_email_api_need').change(function(){

        if(this.checked){
        	console.log('hide');
            $('.mandrill-hide').fadeIn('slow');
        }
        else{
            $('.mandrill-hide').fadeOut('slow');
        }

    });
});