

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
                        <a href="<?php echo url("Client_messages"); ?>" class="dtu nav-link">ALL MESSAGES (<?php echo $messages['numRows']; ?>)</a>
                        <a href="<?php echo url("Client_messages_unread"); ?>" class="dtu nav-link">UNREAD (<?php echo $numMessages; ?>)</a>
                        <a href="<?php echo url("Client_messages_starred"); ?>" class="dtu nav-link">STARRED</a>
                        <a href="<?php echo url("Client_messages_archived"); ?>" class="dtu nav-link">ARCHIVED</a>
                        <a href="<?php echo url("Client_messages_members"); ?>" class="dtu nav-link active">Members</a>
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
					
                        <?php
                           // echo '<pre/>'; print_r($members); exit;
                           if ($members['numRows'] > 0) {
                               foreach ($members['data'] as $member) {
                           ?>
                        <div class="msg-item">
                           <div class="row">
                              <div class="col-lg-2 col-xs-2">
                                 <div class="avatar">
                                    <?php
								if(!empty($member->image)){
								    if(is_numeric($member->image)){
                                                            
								     $imgSrc= url('/pCloudImgDownload.php?fileID='.$member->image);
							       }
								  else {
                                       $imgSrc = 'assets/img/profile-pic.png';
                                       }
                                       // date
                                    //   $dateTime =  explode(' ', $message->dateTime);
                                    //   $date =  explode('-', $dateTime[0]);
                                    //   $date = $date[1] . '/' . $date[2] . '/' . $date[0];
                                     $date='';
								}else{
									$imgSrc = asset('public/images/profile-pic.png');
								}
                                       ?>
                                    <img src="<?php echo $imgSrc; ?>"> 
                                 </div>
                              </div>
                              <div class="col-lg-8 col-xs-8">
                                 <div class="msg-frm text-left"><?php echo ucfirst($member->fname) . ' ' . ucfirst($member->lname); ?><br><span class="text-primary">@<?php echo $member->uname; ?></span></div>
                              </div>
                              <div class="col-lg-2 col-xs-2 text-center">
                                 <a href="<?php echo url("Client_messages_conversation?mid=" . $member->id); ?>" class="mt-5 mb-5 d-block text-center text-white"><i class="fa fa-paper-plane fa-2x text-white" aria-hidden="true"></i></a>
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
                        <?php }  ?>
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

