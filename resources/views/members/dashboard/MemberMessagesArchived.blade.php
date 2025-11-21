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
						<a href="<?php echo url('Member_messages_starred'); ?>" class="dtu pull-left nav-link">STARRED </a> <!--?= $starredMessg; ?-->
						<a href="javascript:void(0)" class="dtu pull-left active nav-link">ARCHIVED </a>  <!--? = $messages['numRows']; ?-->                              
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
                    <?php 
					
					}
					
					 if(isset($messages['numRows']) && $messages['numRows'] > 0){
					foreach($messages['data'] as $message) {  ?>
						<div class="msg-item unread">
							<div class="row">
								<div class="col-lg-2 col-md-2 col-sm-2 col-auto">
									<div class="avatar">
									
									<?php if(isset($senderDetails[$message->messageId]['image']) && strlen($senderDetails[$message->messageId]['image'])>4 && file_exists(base_path('client_images/'.$senderDetails[$message->messageId]['id'].'/'.$senderDetails[$message->messageId]['image']))) 
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
								
								<div class="col-lg-3 col-md-3 col-sm-3 col-auto">
									<div class="msg-frm"><?php echo urldecode(ucfirst($senderDetails[$message->messageId]['name'])); ?><br> <?php echo $date; ?> </div>
								</div>
								
								<div class="col-lg-7 col-md-7 col-sm-7 col-12">
									<div class="msg-txt">
										<a href="<?php echo url("Member_message_archived?cid=".$senderDetails[$message->messageId]['id']); ?>"><?php echo substr($message->message, 0,30); ?></a>
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
	<script>
	
	function makeArchive(messageId)
	{
	 var cid = document.getElementById('cid').value;
	 $.ajax({url: "Member_send_message?messageId="+messageId+"&archive=1&cid="+cid, success: function(result){
			  
		 }});
	
	}
	
	function markStar(messageId)
	{
	 var cid = document.getElementById('cid').value;
	 $.ajax({url: "Member_send_message?messageId="+messageId+"&star=1&cid="+cid, success: function(result){
				
				
		 }});
	}
	
	function sendMessage()
	{
	
	var message = document.getElementById('message').value;
	var cid = document.getElementById('cid').value;
	
	document.getElementById('message').value = '';
	document.getElementById('message').focus();

	if(message.length>0)
	{
	
$.ajax({url: "Member_send_message?message="+message+"&cid="+cid, success: function(result){

	var obj = JSON.parse(result);
	//document.getElementById("demo").innerHTML = myobj.name;
	if(obj.response>0)
	{
	
	  
	  var msgDiv = document.createElement("div");
	  msgDiv.setAttribute("class", "tmsg");
	  
	  var dtDiv = document.createElement("div");
	  dtDiv.setAttribute("class", "dt");
	  
	  var shapeDiv = document.createElement("div");
	  shapeDiv.setAttribute("class", "shape");
	  
	  
	  var node = document.createElement("p");
	  var textnode = document.createTextNode(message);
	  node.appendChild(textnode);
	  msgDiv.appendChild(node);
	  
	  var textnode1 = document.createTextNode(obj.dt);
	  dtDiv.appendChild(textnode1);
	  msgDiv.appendChild(dtDiv);
	  
	  
	  msgDiv.appendChild(shapeDiv);
	  
	  $("#displayMessages").prepend(msgDiv);
	}
	else
	{
	
	}


}});
	}
	
	}
	
	
	</script> 
@endsection