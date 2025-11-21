@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Messages</h2>
              </div>
              <div class="tabs-section">
				<div class="msg-blk f-block">
									 
				   <div class="nav nav-tabs">
						<a href="<?php echo url('Member_messages'); ?>" class="ats pull-left nav-link">ALL MESSAGES (<?php echo $numInboxMessages; ?>)</a>
						<a href="<?php echo url('Member_messages_unread'); ?>" class="tit pull-left nav-link">UNREAD (<?php echo $numMessages; ?>)</a>
						<a href="javascript:void(0)" class="dtu pull-left active nav-link">STARRED </a> <!--?= $messages['numRows']; ?-->
						<a href="<?php echo url('Member_messages_archived'); ?>" class="dtu pull-left nav-link">ARCHIVED </a> <!--?= $archMessages; ?-->                               
					</div>
					
					<div class="msgs">
					
					<?php
					if(isset($messages['numRows']) && $messages['numRows'] > 0){ 
					foreach($messages['data'] as $message) { 
						if(!isset($senderDetails[$message->messageId]['id'])){
							continue;
						}
					?>
						<div class="msg-item unread">
							<div class="row">
								<div class="col-lg-2 col-md-2 col-sm-2 col-auto">
									<div class="avatar">
									
									<?php 
									 if(is_numeric($senderDetails[$message->messageId]['image'])){
									   
                                        $imgSrc=url('/pCloudImgDownload.php?fileID='.$senderDetails[$message->messageId]['image']);
                        
									}
									
									  else if(isset($senderDetails[$message->messageId]['image']) && strlen($senderDetails[$message->messageId]['image'])>4 && file_exists(base_path('client_images/'.$senderDetails[$message->messageId]['id'].'/'.$senderDetails[$message->messageId]['image']))) 
									  {
									   $imgSrc = asset('client_images/'.$senderDetails[$message->messageId]['id'].'/'.$senderDetails[$message->messageId]['image']);
				
									  } 
									  else
									  {
										$imgSrc = asset('public/images/profile-pic.png');
									  }
									  
										// date
										$dateTime =  explode(' ',$message->dateTime); 
										$date =  explode('-',$dateTime[0]);
										$date = $date[1].'/'.$date[2].'/'.$date[0]; 
										
										
									   ?>
										<img src="<?php echo $imgSrc; ?>">
									</div>
								</div>
								<?php if(isset($senderDetails[$message->messageId]['name'])){ ?>
								<div class="col-lg-2 col-md-3 col-sm-3 col-auto">
									<div class="msg-frm"><?php echo urldecode(ucfirst($senderDetails[$message->messageId]['name'])); ?><br> <?php echo $date; ?> </div>
								</div>
								<?php 
								}
									if(isset($senderDetails[$message->messageId]['id'])){
								?>
								<div class="col-lg-8 col-md-7 col-sm-7 col-12">
									<div class="msg-txt">
										<a href="<?php echo url("Member_message_starred?cid=".$senderDetails[$message->messageId]['id']); ?>"><?php echo substr($message->message, 0,30); ?></a>
									</div>
								</div>
								<?php } ?>
							</div><!-- eof row -->
						</div><!-- eof msg-item -->
						<?php }
						}else{
						?>
						<h6>No messages available</h6>
						<?php } ?>
						
					</div><!-- eof msgs-->
				</div>   
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
	 </section>
	 
@endsection