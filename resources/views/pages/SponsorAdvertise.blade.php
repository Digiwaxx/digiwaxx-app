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
	.banner-sponser-adv{
		    <?php if(is_numeric($banner[0]->pCloudFileID)){?>
                 background-image: url(<?php echo $ban_img;?>);
      <?php }else{ ?>
                    background-image: url(<?php echo $ban_img;?>);
         <?php }?>
		padding: 165px 0px;
	}
</style>
@section('content')
<section class="top-banner banner-sponser-adv">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
         <div class="banner-text">
            <?php if(!empty($bannerText[0]->bannerText)){ echo stripslashes(urldecode($bannerText[0]->bannerText)); } ?>
          </div>
       </div>
      </div>
    </div>     
   </section>

<section class="content-area">
     <div class="container">      
        <div class="row">
          <div class="col-md-6 col-sm-12">
			<?php echo stripslashes(urldecode($content[0]->page_content)); ?>
          <div class="col-md-6 col-sm-12 px-lg-5">
          	<h2 class="mb-5">Reach us with your Inquiries</h2>
          	   @if(!empty($alert_message))
                   <div class="{{ $alert_class }}">
                        <p>{{ $alert_message }}</p>
                    </div>
                @endif
          
    						
    						<form action="" method="post" id="sponsorForm" autocomplete="off">
							@csrf
                            <div class="form-block">
                            	<div class="form-group">
                            		<input type="text" name="email" id="email" class="form-control" placeholder="Your email">
                            	</div>
                            	<div class="form-group">
                            		<input type="text" name="subject" id="subject" class="form-control" placeholder="Subject">
                            	</div>
                            	<div class="form-group">
                            		<textarea class="form-control" name="message" id="message" placeholder="Message" rows="5"> </textarea>
                            	</div>
                            	<div class="btn-submit">
                            		<button type="submit" name="sendMessage" class="btn btn-theme btn-gradient" value="Send Message">Send Message</button>
                            	</div>
                            	                                                        
                            </div><!-- eof form-block -->
    						</form>
          </div>
          <div class="col-12 text-center mt-5">
          	<hr>
          	<h4>For questions and pricing contact us here: <a href="mailto:business@digiwaxx.com">business@digiwaxx.com</a></h4>
          	<hr>
          </div>

      </div>
  </div>
</section>



<!-- <div class="digi-radio-block">
	   
        	<div class="col-sm-offset-2 col-sm-8  page_content">
        	  <h1>&nbsp;</h1>

				<h1>&nbsp;</h1>

				<h1>REACH YOUR AUDIENCE WITH DIGIWAXX ADS</h1>

				<p>&nbsp;</p>

				<p><span style="font-size:16px">Whether you are interested in video, content or beautiful display ads, we present your brand in the most uncluttered, premium environment anywhere online. We reach nearly 100k unique visitors per month. Reach a broad audience on the homepage or strategically targeted audience of DJs and Industry influencers in our member's login homepage.</span></p>

				<p>&nbsp;</p>

				<h2>Website Advertising:</h2>

				<p><span style="font-size:16px">Display ads are an effective way to get your message across to potential customers. Get your display ads the attention they deserve. Display advertising allows you to connect with our members and keep your brand top of mind.</span></p>

				<p>&nbsp;</p>

				<h2>Email Campaigns:</h2>

				<p><span style="font-size:16px">We will promote your brand within our daily email outreach. Whether </span><span style="font-size:16px">you're&nbsp;</span><span style="font-size:16px">promoting a new service, product or advertising a special offer, we can reach your target audience. If can be a single email blast, daily or monthly campaign.</span></p>

				<p>&nbsp;</p>

				<h2>Sponsored Social Posts:</h2>

				<p><span style="font-size:16px">We post your sponsored message (of image or video) on Instagram, Facebook, Twitter or YouTube. Just send us your image/video for us to post. Include your username, hashtags an. Select a single post or multiple post campaign from Digiwaxx or our Digiwaxx network.</span></p>

				<p>&nbsp;</p>

				<p><u><strong><span style="font-size:16px">For questions and pricing contact us here: <a href="mailto:business@digiwaxx.com">business@digiwaxx.com</a></span></strong></u></p>

				<p>&nbsp;</p>

				<p>&nbsp;</p>
			</div>

        
            <div class="col-sm-offset-2 col-sm-8  page_content">
           		<div class="msg-block" style="margin: auto;text-align: center;max-width: 400px;">
                    		<h1>Reach us with your Inquiries</h1>
    						
    						
    						@if(!empty($output['alert_message']))
							   <div class="{{ $output['alert_class'] }}">
									<p>{{ $output['alert_message'] }}</p>
								</div>
							@endif
          
    						
    						<form action="" method="post" id="sponsorForm" autocomplete="off">
							@csrf
                            <div class="form-block">
                            	<p> <input type="text" name="email" id="email" class="form-control" placeholder="Your email"> </p>
                            	<p> <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject"> </p>
                            	<p> <textarea class="form-control" name="message" id="message" placeholder="Message" rows="5" style="height: unset;width: 400px;"> </textarea></p>
                                <p> <input type="submit" name="sendMessage" class="send-btn" value="Send Message" style="background:#db378f; font-weight:bold; border:none;"> </p>                                                        
                            </div> --><!-- eof form-block -->
    						<!-- </form>
    						
    						
    						
                    </div> --><!-- eof msg-block -->
                <!-- </div>

        </div> -->
		<script>
        // Wait for the DOM to be ready
        $(function(){
        
         $("#sponsorForm").validate();
         
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