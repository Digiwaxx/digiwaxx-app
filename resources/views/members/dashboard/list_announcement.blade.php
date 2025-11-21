 <?php 
//  echo "<pre>";
// print_r($list_mem_announ);
// echo '</pre>';
// die();
?>
@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Announcements</h2>
              </div>
              <div class="tabs-section">
				<div class="msg-blk f-block">
					
				  <div class="hidden-lg d-none" style="">Internal chat with users</div>
				   <div class="nav nav-tabs">
						<a href="javascript:void(0)" class="ats pull-left nav-link active">ALL ANNOUNCEMENTS (<?php echo $row_count; ?>)</a>
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
					 if(!empty($row_count) && $row_count > 0){ 
						foreach($list_mem_announ as $key=>$value) { 
						    
						?>
						<div class="msg-item unread">
							<div class="row">
								<div class="col-lg-2 col-md-1 col-sm-1 col-xs-3">
									<div class="avatar">
                                         <?php echo $value->ma_title; ?>
									</div>
								</div>
								
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<div class="msg-frm"> Published On-  <?php echo $value->ma_created_on; ?></div>
								</div>
								
								<div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
									<div class="msg-frm">
										
									    <?php 	$minText = explode(".", $value->ma_description);?>
										<a href="{{route("member-single-announcement",['id'=>$value->ID])}}"><?php echo $minText[0] ;?> ......(READ MORE)
										</a>
									</div>
								</div>
							</div><!-- eof row -->
						</div><!-- eof msg-item -->
						<?php 
						}
						}else{
						?>
						<h6>No announcements available</h6>
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