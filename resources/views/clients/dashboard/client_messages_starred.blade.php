

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
   <div class="container">
      <div class="row">
         <div class="col-xl-9 col-12">
            <div class="dash-heading">
               <h2>Messages</h2>
            </div>
            <div class="tabs-section">
               <!-- START MIDDLE BLOCK -->
               <div class="msg-blk f-block">                 
                  <div class="msg nav nav-tabs">
                     <a href="<?php echo url("Client_messages"); ?>" class="ats nav-link">ALL MESSAGES (<?php echo $numInboxMessages; ?>)</a>
                     <a href="<?php echo url("Client_messages_unread"); ?>" class="tit nav-link">UNREAD (<?php echo $numMessages; ?>)</a>
                     <a href="<?php echo url("Client_messages_starred"); ?>" class="dtu nav-link active">STARRED</a>
                     <a href="<?php echo url("Client_messages_archived"); ?>" class="dtu nav-link">ARCHIVED</a>
                     <a href="<?php echo url("Client_messages_members"); ?>" class="dtu nav-link">Members</a>
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
								<?php }
                        if ($messages['numRows'] > 0) {
                            foreach ($msgs as $message) {
                        ?>
                     <div class="msg-item unread">
                        <div class="row">
                           <div class="col-lg-2 col-md-1 col-sm-1 col-xs-3">
                              <div class="avatar">
                                 <?php 
                                 if(is_numeric($senderDetails[$message['messageId']]['image'])){
                                                            
					                  $imgSrc= url('/pCloudImgDownload.php?fileID='.$senderDetails[$message['messageId']]['image']);
					  
                                   }
                                 
                                   else if ($senderDetails[$message['messageId']]['image'] && file_exists(base_path('member_images/' . $senderDetails[$message['messageId']]['id'] . '/' . $senderDetails[$message['messageId']]['image']))) {
                                    $imgSrc = asset('member_images/' . $senderDetails[$message['messageId']]['id'] . '/' . $senderDetails[$message['messageId']]['image']);
                                    } else {
                                    $imgSrc = asset('public/images/profile-pic.png');
                                    }
                                    // date
                                    $dateTime =  explode(' ', $message['dateTime']);
                                    $date =  explode('-', $dateTime[0]);
                                    $date = $date[1] . '/' . $date[2] . '/' . $date[0];
                                    ?>
                                 <img src="<?php echo $imgSrc; ?>">
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                              <div class="msg-frm"><?php echo ucfirst($senderDetails[$message['messageId']]['name']); ?><br> <?php echo $date; ?> </div>
                           </div>
                           <div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
                              <div class="msg-txt">
                                 <a href="<?php echo url("Client_message_starred?mid=" . $senderDetails[$message['messageId']]['id']); ?>"><?php
                                    echo substr($message['message'], 0, 40); ?></a>
                              </div>
                           </div>
                        </div>
                        <!-- eof row -->
                     </div>
                     <!-- eof msg-item -->
                     <?php }
                        } else { ?>
                     <div class="msg-item unread">
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <p>No Messages.</p>
                           </div>
                        </div>
                        <!-- eof row -->
                     </div>
                     <?php } ?>
                  </div>
                  <!-- eof msgs-->
               </div>
            </div>
            </div>
            <div class="col-xl-3 col-12">
               @include('clients.dashboard.includes.my-tracks')
            </div>
         
      </div>
   </div>
</div>
</section>
<script>
        function goToPage(page, pid, urlString)
        {
			if(pid > 0){
				var param = '?';
				if (urlString.length > 3)
				{
					param = '&';
				}
				window.location = page + urlString + param + "page=" + pid;
			}
        }
</script>
@endsection

