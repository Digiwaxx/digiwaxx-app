<?php
			$mons = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');

											if(isset($conversation['numRows']) && $conversation['numRows']>0)
											{
											  foreach($conversation['data'] as $message)
											  {
										
			  		if(isset($archiveMsgs) && !(in_array($message->messageId,$archiveMsgs)))
					 {	  
											  
											  if($message->senderType==2 && $message->senderId==Session::get('memberId'))
											  {
											    $class = 'tmsg';
											  }
											  else
											  {
											    $class = 'fmsg';
											  }
											  ?>
											  
	   									  
											  <div class="<?php echo $class; ?>">
                                            	<p><?php echo $message->message; ?></p>
                                                
												
												<?php 
												$dateTime =  explode(' ',$message->dateTime); 
												$date =  explode('-',$dateTime[0]);
												$date1 = $mons[$date[1]].' ';
												$date2 = $date[2].', '.$date[0]; 
												$displayDate = $date1.$date2;
												
												
												?>
                                                <div class="dt"><?php echo $displayDate; ?> 
												
												<?php if(in_array($message->messageId,$starMsgs)) 
									 {
									    $starClass = 'goldStar';
									 }
									 else
									 {
									    $starClass = 'silverStar';
									 }
									 ?>
												
												<span style="float:right; margin-left:20px;">
	   <span id="star<?php echo $message->messageId; ?>" class="<?php echo $starClass; ?>"  onclick="markStar('<?php echo $message->messageId; ?>')" title="Add to Favorite">
	     <i class="fa fa-star" aria-hidden="true"></i>
	   </span>
				
		
		<span id="archive<?php echo $message->messageId; ?>" class="inactiveArchive" onclick="makeArchive('<?php echo $message->messageId; ?>')" title="Add to Archive">		
				<i class="fa fa-archive" aria-hidden="true"></i>
		</span>							  
	</span>
												
												
												</div>
                                                <div class="shape"></div>
                                            </div>
											
											<?php 
											  
											    }
											  }
											
											}
											
											
											?>
											
											
                                            
                                        