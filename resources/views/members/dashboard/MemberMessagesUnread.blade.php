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
					
				  <div class="hidden-lg d-none" style="margin-top:-38px;">Internal chat with users</div>
				   <div class="nav nav-tabs">
						<a href="<?php echo url('Member_messages'); ?>" class="ats pull-left nav-link">ALL MESSAGES (<?php echo $numInboxMessages; ?>)</a>
						<a href="javascript:void(0)" class="tit pull-left active nav-link">UNREAD (<?php echo $numMessages; ?>)</a>
						<a href="<?php echo url('Member_messages_starred'); ?>" class="dtu pull-left nav-link">STARRED </a> <!--?= $starredMessg; ?>) -->
						<a href="<?php echo url('Member_messages_archived'); ?>" class="dtu pull-left nav-link">ARCHIVED </a>  <!--?= $archMessages; ?>) -->                            
					</div>
					
					<div class="msgs">
					
					<?php if ($numPages > 1) { ?>
                    <div class="list-pagination">
                            <div class="pgm">
                                        <?php echo $start + 1; ?> - <?php echo $start + $numRecords; ?> OF <?php echo $num_records; ?>
                            </div>
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <li class="page-item">
                            <a class="page-link" aria-label="Previous" href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>','<?php echo $urlString; ?>')">
                              <span aria-hidden="true">&laquo;</span>
                              <span class="sr-only">Previous</span>
                            </a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#"><?php echo $currentPageNo; ?></a></li>                         
                          <li class="page-item">
                            <a class="page-link" href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>','<?php echo $urlString; ?>')" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                              <span class="sr-only">Next</span>
                              
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                    <?php } ?>
					
					<?php if($numMessages > 0){ foreach($messages['data'] as $message) {  ?>
						<div class="msg-item unread">
							<div class="row">
								<div class="col-lg-2 col-md-1 col-sm-1 col-xs-3">
									<div class="avatar GSGSS">
									
									<?php 
									if(is_numeric($senderDetails[$message->messageId]['image'])){
									   
                                        $imgSrc=url('/pCloudImgDownload.php?fileID='.$senderDetails[$message->messageId]['image']);
                        
									}
								    else if(isset($senderDetails[$message->messageId]['image']) && strlen($senderDetails[$message->messageId]['image'])>4 && file_exists(base_path('client_images/'.$senderDetails[$message->messageId]['id'].'/'.$senderDetails[$message->messageId]['image'])) ) 
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
								
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<div class="msg-frm"><?php echo urldecode(ucfirst($senderDetails[$message->messageId]['name'])); ?><br> <?php echo $date; ?> </div>
								</div>
								
								<div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
									<div class="msg-txt">
										<a href="<?php echo url("Member_send_message?cid=".$senderDetails[$message->messageId]['id']); ?>"><?php echo substr($message->message, 0,30); ?></a>
									</div>
								</div>
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