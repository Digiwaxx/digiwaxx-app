<div class="row">
								<ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                                <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','1')"> << </a> </li>
                                <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                                <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                                <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                                <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
                              </ul>
							  </div>
								<?php $i = $start+1; $divCount = 1;
								foreach($comments['data'] as $review)
								{
								
								
								if($divCount==1)
								{  ?> <div class="row">  <?php }
								
								?> <div class="col-sm-6" style="float:left;"> <?php  
								  $stagename = urldecode($review->stagename); 
								  $city = urldecode($review->city); 
								  $state = urldecode($review->state); 
								  
								  $comment = urldecode($review->additionalcomments); ?>
								  
							
		<!-- Modal --> 
		<div id="reviews<?php echo $review->id; ?>" class="modal fade" role="dialog">
		  <div class="modal-dialog" style="width:80%;">
		
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo $stagename; ?> Review</h4>
			  </div>
			  <div class="modal-body mCustomScrollbar" id="reviewsBody<?php echo $review->id; ?>">
				<p>Some text in the modal.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>
		
		  </div>
		</div>
	  
    <?php echo $i; ?> <b><a href="Client_track_review_member?memberId=<?php echo $review->member; ?>" target="_blank" style="color:#FFF;"><?php echo $stagename; ?></a> <?php echo $city.', '.$state; ?></b><br>
								  
								 
								 <div style="margin-left:19px; color:#e43e99;" id="commentsDiv<?php echo $review->id; ?>">	
				<?php if(strlen($comment)<1)  { ?>			 							 
								 <i><a href="javascript:void()" style="color:#b32f85;" onclick="open_review('<?php echo $review->id; ?>')">"No Comments"</a></i>
								 <?php } else { ?>
				  <i><a href="javascript:void()" style="color:#b32f85;" onclick="open_review('<?php echo $review->id; ?>')">"<?php echo $comment; ?>"</a> 
                     <a href="javascript:void()" onclick="remove_comment('<?php echo $review->id; ?>','<?php echo $stagename; ?>')" title="Remove this comment">
								   <i style="padding-left:10px; color:#edd2e2;" class="fa fa-times" aria-hidden="true"></i>
					 </a>
								 </i>
								 <?php } ?>
								 
								 
								 <!--<a href="#"><i class="fa fa-times" aria-hidden="true"></i> this comment</a>-->
								 </div> <?php echo '<br><br>';
								  $i++;
								  
								  ?> </div>  <?php
								  
								  if($divCount==2)  { echo '</div>'; $divCount = 1; } else { $divCount++; }
								}
								
								?>