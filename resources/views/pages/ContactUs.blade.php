@extends('layouts.app')
  <?php 
  $ban_img='';
  if(is_numeric($banner[0]->pCloudFileID)){
      $ban_img= url('/pCloudImgDownload.php?fileID='.$banner[0]->pCloudFileID);
  }else{
      $ban_img=  url('public/images/'.$banner[0]->banner_image);
  }
  
  ?>
<style>
	.banner-contact-page{
		<?php if(is_numeric($banner[0]->pCloudFileID)){?>
                 background-image: url(<?php echo $ban_img;?>);
      <?php }else{ ?>
                    background-image: url(<?php echo $ban_img;?>);
         <?php }?>
		padding: 165px 0px;
	}
</style>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@section('content')
<!-- <div class="con-us header-bottom">
	<div class="container">                    
		<h1>Contact Us</h1>
	</div>
</div> --><!-- eof header-bottom -->

<section class="top-banner banner-contact-page">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
         <div class="banner-text">
            <?php echo stripslashes(urldecode($bannerText[0]->bannerText)); ?>
          </div>
       </div>
      </div>
    </div>     
   </section>

   <!---content-section-->
   <section class="content-area">
     <div class="container">
      <div class="contact-section">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="contact-form">
              <h2>Send us a message</h2>
              @if(!empty($alert_message))
                   <div class="{{ $alert_class }}">
                        <p>{{ $alert_message }}</p>
                    </div>
                @endif
              <form action="" method="post" id="contactusForm" autocomplete="off">
              	@csrf
                <div class="form-group" >
                  <input type="text" name="email" id="email" class="form-control" placeholder="Your email">
                </div>
                <div class="form-group">
                  <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject">
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" id="message" placeholder="Message" rows="5"> </textarea>
                </div>
                <div class="g-recaptcha" data-sitekey="6Lcz58IkAAAAAPpGChEJebu4NPUu4NhOvWk5so38"></div>
                <div class="btn-submit">
                <button class="btn btn-theme btn-gradient" type="submit" name="sendMessage" value="Send Message">Send message</button>
                
              </div>
              <span id="captcha" style="margin-left:100px;color:red" />
              </form>
             
              
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="contact-info">
              <h2>Get in touch</h2>
				<?php echo stripslashes(urldecode($getInTouchText[0]->meta_value)); ?>
            </div>
          </div>
          
        </div>
      </div>

      
       
     </div> 
       
   </section>
    <script>
       $(document).on("submit","#contactusForm", function(){
           var v = grecaptcha.getResponse();
            if(v.length == 0)
            {
                document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
                return false;
            }
            else
            {
                document.getElementById('captcha').innerHTML="Captcha completed";
                return true; 
            }
       })
   </script>

<!-- <div class="contactus-block">
	<div class="container">

		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="msg-block">
				<h1>Send us a message</h1>
				
				
				@if(!empty($output['alert_message']))
                   <div class="{{ $output['alert_class'] }}">
                        <p>{{ $output['alert_message'] }}</p>
                    </div>
                @endif

				
				<form action="" method="post" id="contactusForm" autocomplete="off">
				@csrf
				<div class="form-block">
					<p> <input type="text" name="email" id="email" class="form-control" placeholder="Your email"> </p>
					<p> <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject"> </p>
					<p> <textarea class="form-control" name="message" id="message" placeholder="Message" rows="5"> </textarea></p>
					<p> <input type="submit" name="sendMessage" class="send-btn" value="Send Message" style="background:#db378f; font-weight:bold; border:none;"> </p>                                                        
				</div>--><!-- eof form-block -->
				<!--</form>
				
				
				
			</div>--><!-- eof msg-block -->
		<!--</div>--><!-- eof col -->

		<!--<div class="col-lg-5 col-lg-offset-1 col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1">
			<div class="get-block">
				<h1 class="git">or Get in touch</h1>
				
				<div class="info-block">
					<p> <i class="fa fa-skype fa-lg"></i> digiwaxx </p>
					<p> <i class="fa fa-envelope"></i> business@digiwaxx.com </p>
					<p> <i class="fa fa-phone fa-lg"></i> (800) 665-1259 </p>
				</div>--><!-- eof info-block -->
				
				<!--<div class="need-help-block">
					<h2>Need Help?</h2>
					
					<div class="link">
						<p>Check Our</p>
						<p><a href="{{ url('faq') }}">F.A.Q. Page</a></p>
					</div>
					
				</div>
				
			</div> --><!-- eof get-block -->
		<!--</div>--><!-- eof col -->

	<!--</div>--><!-- eof container -->
<!--</div>--><!-- eof contactus-block -->
<script>
  // Wait for the DOM to be ready
$(function() {

 $("#contactusForm").validate();
 
 $("#email").rules("add", {
         required:true,
		 email: true,
         messages: {
                required: "Please enter email"
         }
      });

	$("#subject").rules("add", {
			 required:true,
			 messages: {
					required: "Please enter subject"
			 }
		  });
	});

</script>
@endsection