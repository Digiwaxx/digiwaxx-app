@extends('layouts.client_dashboard')

@section('content')

<!-- <style>

 

.nopadding { padding:0px !important; }

.amrFile { display:none !important; }

.form-group { margin-bottom:30px; } 



</style>  -->
 <section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		 <div class="container">
			<div class="row">
			<div class="col-12">
            <div class="dash-heading">
                <h2>My Dashboard</h2>
              </div>
            <div class="tabs-section">

                
                  <!-- START MIDDLE BLOCK -->

                    <div class="col-lg-10 col-lg-offset-1  col-md-12">
                    		<h3>Track Review</h3>
				
				 <?php if($displayReviews==1)
							{
							if($reviews['numRows']>0) { ?>			
							
							<div class="row">
					<!--	<div class="col-lg-6 col-md-6 col-sm-6" style="height:800px; padding:0px;">-->
					
			
			      <?php
				  
				 
				  
				  
				  $numRating = 0;
									$whatYouThink = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
									$whatYouThinkAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
									
								//	print_r($reviews);
									foreach($reviews['data'] as $review)
									{
									 
									      if($review->whatrate=="5"){ $whatYouThink[5]++;  $numRating++; }
									else  if($review->whatrate=="4"){ $whatYouThink[4]++;  $numRating++; }
									else  if($review->whatrate=="3"){ $whatYouThink[3]++;  $numRating++; }
									else  if($review->whatrate=="2"){ $whatYouThink[2]++;  $numRating++; }
									else  if($review->whatrate=="1"){ $whatYouThink[1]++;  $numRating++; }
				                    
									
									}
									
									
									  $whatYouThinkAvg[1] = ($whatYouThink[1]/$numRating)*100; 
									  $whatYouThinkAvg[1] = number_format($whatYouThinkAvg[1], 2, '.', '');
									  
									  $whatYouThinkAvg[2] = ($whatYouThink[2]/$numRating)*100; 
									  $whatYouThinkAvg[2] = number_format($whatYouThinkAvg[2], 2, '.', '');
									  
									  $whatYouThinkAvg[3] = ($whatYouThink[3]/$numRating)*100; 
									  $whatYouThinkAvg[3] = number_format($whatYouThinkAvg[3], 2, '.', '');
									  
									  $whatYouThinkAvg[4] = ($whatYouThink[4]/$numRating)*100; 
									  $whatYouThinkAvg[4] = number_format($whatYouThinkAvg[4], 2, '.', '');
									  
									  $whatYouThinkAvg[5] = ($whatYouThink[5]/$numRating)*100;  
									  $whatYouThinkAvg[5] = number_format($whatYouThinkAvg[5], 2, '.', '');
									  
								
								
				  ?>			
				  <div class="col-lg-8 col-md-8 col-sm-8">
				     <div id="columnchart_values1" style="width: 100%; height: 500px;"></div>
				  </div>
				  <div class="col-lg-4 col-md-4 col-sm-4">
				  
				     <p>View Members who Chose: </p>
									   
     <p style="color:#337ab7;">
		5 (<span class="red"><?php echo $whatYouThink[5]; ?></span>)<br />
		4 (<span class="red"><?php echo $whatYouThink[4]; ?></span>)<br />
		3 (<span class="red"><?php echo $whatYouThink[3]; ?></span>)<br />
		2 (<span class="red"><?php echo $whatYouThink[2]; ?></span>)<br />
		1 (<span class="red"><?php echo $whatYouThink[1]; ?></span>)
   </p>
									
				    
				  </div>
				  <div style="clear:both;"></div>
				  
				  <!--graph -->
				  <?php		
												
									$numGoDistance = 0;
									$goDistance = array(1 => 0, 2 => 0);
									$goDistanceAvg = array(1 => 0, 2 => 0);
									foreach($reviews['data'] as $review)
									{
									 
								       if(strcmp($review->godistance,'yes')==0) { $goDistance[1]++;  $numGoDistance++;  }
								  else if(strcmp($review->godistance,'no')==0) { $goDistance[2]++;  $numGoDistance++;  }
								
								}


                                if(!empty($numGoDistance))	{		
									
							  $goDistanceAvg[1] = ($goDistance[1]/$numGoDistance)*100; 
							  $goDistanceAvg[1] = number_format($goDistanceAvg[1], 2, '.', '');
							  
							  $goDistanceAvg[2] = ($goDistance[2]/$numGoDistance)*100; 
							  $goDistanceAvg[2] = number_format($goDistanceAvg[2], 2, '.', '');

                                }

							  ?>
				  
				  <div class="col-lg-8 col-md-8 col-sm-8">
				     <div id="barchart_values" style="width: 100%; height:300px;"></div>
				  </div>
				  <div class="col-lg-4 col-md-4 col-sm-4">
				    <p>View Members who Chose: </p>
									   
   <p style="color:#337ab7;">
    Yes (<span class="red"><?php echo $goDistance[1]; ?></span>)<br />
    No (<span class="red"><?php echo $goDistance[2]; ?></span>)
   </p>
				  </div>
				  <div style="clear:both;"></div>
				  
				  <!--graph -->
				  <?php		
												
									$numHowSupport = 0;
									$howSupport = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
									$howSupportAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
									foreach($reviews['data'] as $review)
									{
									 
					       if(strcmp($review->howsupport,'play_it')==0) { $howSupport[1]++;  $numHowSupport++;  }
					  else if(strcmp($review->howsupport,'nothing_i_wont_support_it')==0) { $howSupport[2]++;  $numHowSupport++;  }
					  else if(strcmp($review->howsupport,'remix_it')==0) { $howSupport[3]++;  $numHowSupport++;  }
					  else if(strcmp($review->howsupport,'does_not_apply_to_me')==0) { $howSupport[4]++;  $numHowSupport++;  }
								}
									
									
						
							
				        	if(!empty($numHowSupport))	{
							  $howSupportAvg[1] = ($howSupport[1]/$numHowSupport)*100; 
							  $howSupportAvg[1] = number_format($howSupportAvg[1], 2, '.', '');
							  
							  $howSupportAvg[2] = ($howSupport[2]/$numHowSupport)*100; 
							  $howSupportAvg[2] = number_format($howSupportAvg[2], 2, '.', '');
							  
							  $howSupportAvg[3] = ($howSupport[3]/$numHowSupport)*100; 
							  $howSupportAvg[3] = number_format($howSupportAvg[3], 2, '.', '');
							  
							  $howSupportAvg[4] = ($howSupport[4]/$numHowSupport)*100; 
							  $howSupportAvg[4] = number_format($howSupportAvg[4], 2, '.', '');
                            }
							 
									?>
			 	  <div class="col-lg-8 col-md-8 col-sm-8">					
					 <div id="piechart_3d1" style="width:100%; height:400px;"></div>
				  </div>
				  <div class="col-lg-4 col-md-4 col-sm-4">
				  
				   <p>View Members who Chose: </p>
									   
   <p style="color:#337ab7;">
   Play it (<span class="red"><?php echo $howSupport[1]; ?></span>)<br />
   Nothing, I will not support it (<span class="red"><?php echo $howSupport[2]; ?></span>)<br />
   Remix it(<span class="red"><?php echo $howSupport[3]; ?></span>)<br />
   Does not apply to me (<span class="red"><?php echo $howSupport[4]; ?></span>)
   </p>
				  
				    
				  </div>
				 <div style="clear:both;"></div>	 
					 
					 <!--graph -->
					 <?php		
												
									$numLikeRecord = 0;
									$likeRecord = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
									$likeRecordAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
									foreach($reviews['data'] as $review)
									{
									 
					       if(strcmp($review->likerecord,'flow')==0) { $likeRecord[1]++;  $numLikeRecord++;  }
					  else if(strcmp($review->likerecord,'the_lyrics')==0) { $likeRecord[2]++;  $numLikeRecord++;  }
					  else if(strcmp($review->likerecord,'production')==0) { $likeRecord[3]++;  $likeRecord++;  }
					  else if(strcmp($review->likerecord,'hook_or_chorus')==0) { $likeRecord[4]++;  $numLikeRecord++;  }
					  else if(strcmp($review->likerecord,'overall_sound_or_style')==0) { $likeRecord[5]++;  $numLikeRecord++;  }
					  else if(strcmp($review->likerecord,'nada')==0) { $likeRecord[6]++;  $numLikeRecord++;  }
						
								}
									
									
						
							
                            if(!empty($numLikeRecord))	{
							  $likeRecordAvg[1] = ($likeRecord[1]/$numLikeRecord)*100; 
							  $likeRecordAvg[1] = number_format($likeRecordAvg[1], 2, '.', '');
							  
							  $likeRecordAvg[2] = ($likeRecord[2]/$numLikeRecord)*100; 
							  $likeRecordAvg[2] = number_format($likeRecordAvg[2], 2, '.', '');
							  
							  $likeRecordAvg[3] = ($likeRecord[3]/$numLikeRecord)*100; 
							  $likeRecordAvg[3] = number_format($likeRecordAvg[3], 2, '.', '');
							  
							  $likeRecordAvg[4] = ($likeRecord[4]/$numLikeRecord)*100; 
							  $likeRecordAvg[4] = number_format($likeRecordAvg[4], 2, '.', '');
							  
							  $likeRecordAvg[5] = ($likeRecord[5]/$numLikeRecord)*100; 
							  $likeRecordAvg[5] = number_format($likeRecordAvg[5], 2, '.', '');
							  
							  $likeRecordAvg[6] = ($likeRecord[6]/$numLikeRecord)*100; 
							  $likeRecordAvg[6] = number_format($likeRecordAvg[6], 2, '.', '');
                            }
							 
									?>
					
					<div class="col-lg-8 col-md-8 col-sm-8">				
					  <div id="piechart_3d2" style="width: 100%;  height:400px;"></div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4">
					
					 <p>View Members who Chose: </p>
									   
   <p style="color:#337ab7;">
   Flow (<span class="red"><?php echo $likeRecord[1]; ?></span>)<br />
   The lyrics (<span class="red"><?php echo $likeRecord[2]; ?></span>)<br />
   Production (<span class="red"><?php echo $likeRecord[3]; ?></span>)<br />
   Hook or chorus (<span class="red"><?php echo $likeRecord[4]; ?></span>)<br />
   Overall sound or style (<span class="red"><?php echo $likeRecord[5]; ?></span>)<br />
   Nada(<span class="red"><?php echo $likeRecord[6]; ?></span>)<br />
   </p>
					  
					</div>  
					 <div style="clear:both;"></div> 
					
					  
					  <!--
					</div>
						
						<div class="col-lg-6 col-md-6 col-sm-6" style="height:800px; padding:0px;">
                        	-->
						
							
							
							<!--GRAPH -->
								<?php		
												
									$numWhereHeard = 0;
									$whereHeard = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
									$whereHeardAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
									foreach($reviews['data'] as $review)
									{
										 
			if(strcmp($review->whereheard,"digital_waxx_music_service")==0 || strcmp($review->whereheard,"Digital Waxx")==0) 
			     { $whereHeard[1]++; $numWhereHeard++; }
			else if(strcmp($review->whereheard,"commercial_radio")==0 || strcmp($review->whereheard,"Radio")==0) 
			     { $whereHeard[2]++;  $numWhereHeard++; }
			else if(strcmp($review->whereheard,"satellite_radio")==0 || strcmp($review->whereheard,"Satellite Radio")==0) 
			     { $whereHeard[3]++;  $numWhereHeard++; }
			else if(strcmp($review->whereheard,"college_radio")==0 || strcmp($review->whereheard,"College Radio")==0)
			     { $whereHeard[4]++;  $numWhereHeard++; }
			else if(strcmp($review->whereheard,"mixtape")==0 || strcmp($review->whereheard,"Mixtape")==0)
			    { $whereHeard[5]++;  $numWhereHeard++; }
			else if(strcmp($review->whereheard,"club")==0 || strcmp($review->whereheard,"Club")==0)
			    { $whereHeard[6]++;  $numWhereHeard++; }
			else if(strcmp($review->whereheard,"internet")==0 || strcmp($review->whereheard,"Internet")==0) 
			    { $whereHeard[7]++;  $numWhereHeard++; }
			else if(strcmp($review->whereheard,"video")==0 || strcmp($review->whereheard,"Video")==0) 
			   { $whereHeard[8]++;  $numWhereHeard++;  }
			else if(strcmp($review->whereheard,"Other")==0)
			   { $whereHeard[9]++;  $numWhereHeard++; }
			   
									}
									
									/*$x=1;
									foreach($whereHeard as $wh)
									{
									
									  if($wh>1)
									  {
									  
									    $whereHeardPer[$x] = $wh;
									  }
									  else
									  {
									    $whereHeardPer[$x] = 1.5;
									  }
									$x++;
									}
									*/
									
								
									
								
									//  $numWhereHeard;
                                    if(!empty($numWhereHeard))	{
									  $whereHeardAvg[1] = ($whereHeard[1]/$numWhereHeard)*100; 
									  $whereHeardAvg[1] = number_format($whereHeardAvg[1], 2, '.', '');
									  
									  $whereHeardAvg[2] = ($whereHeard[2]/$numWhereHeard)*100; 
									  $whereHeardAvg[2] = number_format($whereHeardAvg[2], 2, '.', '');
									  
									  $whereHeardAvg[3] = ($whereHeard[3]/$numWhereHeard)*100; 
									  $whereHeardAvg[3] = number_format($whereHeardAvg[3], 2, '.', '');
									  
									  $whereHeardAvg[4] = ($whereHeard[4]/$numWhereHeard)*100; 
									  $whereHeardAvg[4] = number_format($whereHeardAvg[4], 2, '.', '');
									  
									  $whereHeardAvg[5] = ($whereHeard[5]/$numWhereHeard)*100; 
									  $whereHeardAvg[5] = number_format($whereHeardAvg[5], 2, '.', '');
									  
									  $whereHeardAvg[6] = ($whereHeard[6]/$numWhereHeard)*100; 
									  $whereHeardAvg[6] = number_format($whereHeardAvg[6], 2, '.', '');
									  
									  $whereHeardAvg[7] = ($whereHeard[7]/$numWhereHeard)*100; 
									  $whereHeardAvg[7] = number_format($whereHeardAvg[7], 2, '.', '');
									  
									 
									  $whereHeardAvg[8] = ($whereHeard[8]/$numWhereHeard)*100; 
									  $whereHeardAvg[8] = number_format($whereHeardAvg[8], 2, '.', '');
									  
									  $whereHeardAvg[9] = ($whereHeard[9]/$numWhereHeard)*100; 
								      $whereHeardAvg[9] = number_format($whereHeardAvg[9], 2, '.', '');
                                    }
									 
									  
									
								
								
									?>
								
					
					 <div class="col-lg-8 col-md-8 col-sm-8">
					   <div id="piechart_3d3" style="width: 100%; height:400px;"></div>
					 </div>
					 <div class="col-lg-4 col-md-4 col-sm-4">
					 
					  <p>View Members who Chose: </p>
									   
     <p style="color:#337ab7;">
Digital Waxx  (<span class="red"><?php echo $whereHeard[1]; ?></span>)<br />
Radio  (<span class="red"><?php echo $whereHeard[2]; ?></span>)<br />
Satellite Radio  (<span class="red"><?php echo $whereHeard[3]; ?></span>)<br />
College Radio  (<span class="red"><?php echo $whereHeard[4]; ?></span>)<br />
Mixtape  (<span class="red"><?php echo $whereHeard[5]; ?></span>)<br />
Club  (<span class="red"><?php echo $whereHeard[6]; ?></span>)<br />
Internet  (<span class="red"><?php echo $whereHeard[7]; ?></span>)<br />
Video  (<span class="red"><?php echo $whereHeard[8]; ?></span>)<br />
Other  (<span class="red"><?php echo $whereHeard[9]; ?></span>)<br />
   </p>
					   
					 </div>
					   <div style="clear:both;"></div>
					   
					    <!--GRAPH -->
						<?php		
												
									$numLabelSupport = 0;
									$labelSupport = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
									$labelSupportAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
									foreach($reviews['data'] as $review)
									{
									 
					       if(strcmp($review->labelsupport,'market_visits')==0) { $labelSupport[1]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'more_street_marketing')==0) { $labelSupport[2]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'interview_on_my_show_or_station')==0) { $labelSupport[3]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'a_show_in_my_market')==0) { $labelSupport[4]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'do_a_regional_remix')==0) { $labelSupport[5]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'other')==0) { $labelSupport[6]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'scrap_the_project')==0) { $labelSupport[7]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'shoot_a_video')==0) { $labelSupport[8]++;  $numLabelSupport++;  }
					  else if(strcmp($review->labelsupport,'nothing')==0) { $labelSupport[9]++;  $numLabelSupport++;  }
					  
					  
					 // print_r($reviews);
					//  echo $review->labelsupport; echo '<br />';
								  
								 
								
								}
									
									
						
							
							// $numLabelSupport;
                            if(!empty($numLabelSupport))	{			
							  $labelSupportAvg[1] = ($labelSupport[1]/$numLabelSupport)*100; 
							  $labelSupportAvg[1] = number_format($labelSupportAvg[1], 2, '.', '');
							  
							  $labelSupportAvg[2] = ($labelSupport[2]/$numLabelSupport)*100; 
							  $labelSupportAvg[2] = number_format($labelSupportAvg[2], 2, '.', '');
							  
							  $labelSupportAvg[3] = ($labelSupport[3]/$numLabelSupport)*100; 
							  $labelSupportAvg[3] = number_format($labelSupportAvg[3], 2, '.', '');
							  
							  $labelSupportAvg[4] = ($labelSupport[4]/$numLabelSupport)*100; 
							  $labelSupportAvg[4] = number_format($labelSupportAvg[4], 2, '.', '');
							  
							  $labelSupportAvg[5] = ($labelSupport[5]/$numLabelSupport)*100; 
							  $labelSupportAvg[5] = number_format($labelSupportAvg[5], 2, '.', '');
							  
							  $labelSupportAvg[6] = ($labelSupport[6]/$numLabelSupport)*100; 
							  $labelSupportAvg[6] = number_format($labelSupportAvg[6], 2, '.', '');
							  
							  $labelSupportAvg[7] = ($labelSupport[7]/$numLabelSupport)*100; 
							  $labelSupportAvg[7] = number_format($labelSupportAvg[7], 2, '.', '');
							  
							  $labelSupportAvg[8] = ($labelSupport[8]/$numLabelSupport)*100; 
							  $labelSupportAvg[8] = number_format($labelSupportAvg[8], 2, '.', '');
							  
							  $labelSupportAvg[9] = ($labelSupport[9]/$numLabelSupport)*100; 
							  $labelSupportAvg[9] = number_format($labelSupportAvg[9], 2, '.', '');
                            }
							  
							
									?>
						
						<div class="col-lg-8 col-md-8 col-sm-8">
					     <div id="piechart_3d4" style="width: 100%; height:400px;"></div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4">
						  
						     <p>View Members who Chose: </p>
									   
      <p style="color:#337ab7;">  

   Market visits (<span class="red"><?php echo $labelSupport[1]; ?></span>)<br />
   More street marketing (<span class="red"><?php echo $labelSupport[2]; ?></span>)<br />
   Interview on my show or station (<span class="red"><?php echo $labelSupport[3]; ?></span>)<br />
   Local live performance (<span class="red"><?php echo $labelSupport[4]; ?></span>)<br />
   Do a regional remix (<span class="red"><?php echo $labelSupport[5]; ?></span>)<br />
   Other (<span class="red"><?php echo $labelSupport[6]; ?></span>)<br />
   Scrap the project (<span class="red"><?php echo $labelSupport[7]; ?></span>)<br />
   Shoot a video (<span class="red"><?php echo $labelSupport[8]; ?></span>)<br />
   Nothing (<span class="red"><?php echo $labelSupport[9]; ?></span>)
   </p>
   
						</div> 
						<div style="clear:both;"></div>
						
						 <div style="width:100%;">
						 	<?php $i=1;
								foreach($reviews['data'] as $review)
								{
								  
								  
								  $stagename = urldecode($review->stagename); 
								  
								  if(strlen($review->labelsupport_other)>0)
								  {
								   $otherComment = urldecode($review->labelsupport_other); 
								   
								   
								   ?>
								   
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
		
		   <?php  echo $i; ?> <b> From 
		   <a href="javascript:void()" style="color:#FFF;" onclick="open_member('<?php echo $review->member; ?>')"><?php echo $stagename; ?></a> </b><br>
								 <div style="margin-left:19px; color:#e43e99;" id="otherCommentsDiv<?php echo $review->id; ?>">	
			<a href="javascript:void()" style="color:#b32f85;" onclick="open_review('<?php echo $review->id; ?>')"><i>"<?php echo $otherComment; ?>"</i></a>
			             </div>
								  
								 
								 
						 							 
								  <?php $i++;  } }  ?>
						 
						 </div>
					
						
						
						<!--graph -->
						<?php		
												
									$numAnotherFormat = 0;
									$anotherFormat = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
									$anotherFormatAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
									foreach($reviews['data'] as $review)
									{
									 									 
									 $format = explode(',',$review->anotherformat);
									 

					     /*  if(strcmp($review->anotherformat,'No')==0) { $anotherFormat[1]++;  $numAnotherFormat++;  }
					  else if(strcmp($review->anotherformat,'CD')==0) { $anotherFormat[2]++;  $numAnotherFormat++;  }
					  else if(strcmp($review->anotherformat,'Vinyl')==0) { $anotherFormat[3]++;  $numAnotherFormat++;  }
					  else if(strcmp($review->anotherformat,'Higher Quality File')==0) { $anotherFormat[4]++;  $numAnotherFormat++;  }
					  else if(strcmp($review->anotherformat,'0')==0) { $anotherFormat[5]++;  $numAnotherFormat++;  }
						*/
						
					   if(in_array('no',$format)) { $anotherFormat[1]++;  $numAnotherFormat++;  }
					   if(in_array('cd',$format)) { $anotherFormat[2]++;  $numAnotherFormat++;  }
					   if(in_array('vinyl_or_12_inch',$format)) { $anotherFormat[3]++;  $numAnotherFormat++;  }
					   if(in_array('higher_quality_file',$format)) { $anotherFormat[4]++;  $numAnotherFormat++;  }
					   if(in_array('does_not_apply_to_me',$format)) { $anotherFormat[5]++;  $numAnotherFormat++;  }
						
								}
									
									
                                if(!empty($numAnotherFormat))	{
							  $anotherFormatAvg[1] = ($anotherFormat[1]/$numAnotherFormat)*100; 
							  $anotherFormatAvg[1] = number_format($anotherFormatAvg[1], 2, '.', '');
							  
							  $anotherFormatAvg[2] = ($anotherFormat[2]/$numAnotherFormat)*100; 
							  $anotherFormatAvg[2] = number_format($anotherFormatAvg[2], 2, '.', '');
							  
							  $anotherFormatAvg[3] = ($anotherFormat[3]/$numAnotherFormat)*100; 
							  $anotherFormatAvg[3] = number_format($anotherFormatAvg[3], 2, '.', '');
							  
							  $anotherFormatAvg[4] = ($anotherFormat[4]/$numAnotherFormat)*100; 
							  $anotherFormatAvg[4] = number_format($anotherFormatAvg[4], 2, '.', '');
							  
							  $anotherFormatAvg[5] = ($anotherFormat[5]/$numAnotherFormat)*100; 
							  $anotherFormatAvg[5] = number_format($anotherFormatAvg[5], 2, '.', '');
                                }
							  
							
									?>
									
									<div class="col-lg-8 col-md-8 col-sm-8">
						  <div id="columnchart_values2" style="width: 100%; height: 300px;"></div>
						  </div>
						  <div class="col-lg-4 col-md-4 col-sm-4">
						     <p>View Members who Chose: </p>
									   
      <p style="color:#337ab7;">
   No (<span class="red"><?php echo $anotherFormat[1]; ?></span>)<br />
   CD (<span class="red"><?php echo $anotherFormat[2]; ?></span>)<br />
   Vinyl (<span class="red"><?php echo $anotherFormat[3]; ?></span>)<br />
   Higher quality file (<span class="red"><?php echo $anotherFormat[4]; ?></span>)<br />
   Does not apply to me (<span class="red"><?php echo $anotherFormat[5]; ?></span>)
   
   </p>
						  </div>
							<div style="clear:both;"></div>
							                    <!--   </div>-->

                                
								<h1>Comments</h1>
								<div id="commentsDiv">
								<?php $i=1; $divCount = 1;
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
	  
    <?php echo $i.'.'; ?> <b><?php echo $stagename; ?> <?php echo $city.', '.$state; ?></b><br>
								  
								 
								 <div style="margin-left:19px; color:#e43e99;" id="commentsDiv<?php echo $review->id; ?>">	
				<?php if(strlen($comment)<1)  { ?>			 							 
								 <i><a href="javascript:void()" style="color:#b32f85;" onclick="open_review('<?php echo $review->id; ?>')">"No Comments"</a></i>
								 <?php } else { ?>
				  <i style="color:#b32f85;">"<?php echo $comment; ?>"</i>
								 <?php } ?>
								 
								 
								 <!--<a href="#"><i class="fa fa-times" aria-hidden="true"></i> this comment</a>-->
								 </div> <?php echo '<br><br>';
								  $i++;
								  
								  ?> </div>  <?php
								  
								  if($divCount==2)  { echo '</div>'; $divCount = 1; } else { $divCount++; }
								}
								
								?>
								</div> <!--comments div-->
								
								</div>


								<?php } else { ?>
								
								<h4>No reviews posted yet!</h4>
								<?php } } else { ?>				   
								
								<h4>This feature ia available to ADVANCED subscription package.</h4>
								<?php } ?>
												   
												   
												   
                    </div>
                    <!-- eof middle block -->


				@include('clients.dashboard.includes.my-tracks')
				
			</div>
		</div>
	</div>
	</div>
	</div>
		
 </section>


 <div id="membersList" class="modal fade" tabindex="-1">
									<div class="modal-dialog" style="width:60%;">
										<div class="modal-content" id="membersListContent" style=" background:#FFF;">
											
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
        
		


   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	
	
	
    <script type="text/javascript">
       google.charts.load("current", {packages:["corechart"]});
       
	   google.charts.setOnLoadCallback(drawChart4);
	   google.charts.setOnLoadCallback(drawChart1);
	   
	   google.charts.setOnLoadCallback(drawChart2);
	   google.charts.setOnLoadCallback(drawChart3);
	   
	   google.charts.setOnLoadCallback(drawBarChart1);
	   google.charts.setOnLoadCallback(drawBarChart2);
	   
	   google.charts.setOnLoadCallback(drawChart);
      
	  
	  function drawChart4() {
	  
        
		
		var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Market visits',     <?php echo $labelSupportAvg[1]; ?>],
          ['More street marketing',      <?php echo $labelSupportAvg[2]; ?>],
          ['Interview',  <?php echo $labelSupportAvg[3]; ?>],
          ['Local live performance', <?php echo $labelSupportAvg[4]; ?>],
          ['Regional remix',    <?php echo $labelSupportAvg[5]; ?>],
		  ['Other',    <?php echo $labelSupportAvg[6]; ?>],
		  ['Scrao the project',    <?php echo $labelSupportAvg[7]; ?>],
		  ['Shoot a video',    <?php echo $labelSupportAvg[8]; ?>],
		  ['Nothing',    <?php echo $labelSupportAvg[9]; ?>]
        ]);

        var options = {
          title: 'How should the label support this project?',
          is3D: true,
		   backgroundColor: 'none',
		  titleTextStyle: { color: '#FFF' },
		  legendTextStyle: { color: '#FFF' },
		/*  tooltip: {
          textStyle: { fontSize:  9 }
      },*/
		  sliceVisibilityThreshold: .001,
		  //    fontSize: 9,

        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d4'));
        chart.draw(data, options);
      }
	  
	  
	    function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Play it',     <?php echo $howSupportAvg[1]; ?>],
          ['No support',  <?php echo $howSupportAvg[2]; ?>],
          ['Remix it',  <?php echo $howSupportAvg[3]; ?>],
          ['Does not  apply', <?php echo $howSupportAvg[4]; ?>]
        ]);

        var options = {
          title: 'How will you support this project?',
          is3D: true,
		   backgroundColor: 'none',
		  titleTextStyle: { color: '#FFF' },
		  legendTextStyle: { color: '#FFF' },
		  sliceVisibilityThreshold: .001,
		  // legend: { position: 'bottom', alignment: 'vertical' },
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d1'));
        chart.draw(data, options);
      }	  
	  
	  
	  function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['flow',     <?php echo $likeRecord[1]; ?>],
          ['Lyrics',      <?php echo $likeRecord[2]; ?>],
          ['Production',  <?php echo $likeRecord[3]; ?>],
          ['Hook/chorus', <?php echo $likeRecord[4]; ?>],
          ['Overall sound',    <?php echo $likeRecord[5]; ?>],
		  ['Nada',   <?php echo $likeRecord[6]; ?>]
		  
        ]);

        var options = {
	    title: 'What do you like most about this record?',
        is3D: true,
		colors: ['#e0440e', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6'],
		 backgroundColor: 'none',
		  titleTextStyle: { color: '#FFF' },
		  legendTextStyle: { color: '#FFF' },
		  sliceVisibilityThreshold: .001,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d2'));
        chart.draw(data, options);
      }	  
	  
	  
	  function drawChart3() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Digital waxx',     <?php  echo $whereHeard[1]; ?>],
          ['Radio',      <?php  echo $whereHeard[2]; ?>],
          ['Satellite radio',  <?php  echo $whereHeard[3]; ?>],
          ['College radio', <?php  echo $whereHeard[4]; ?>],
          ['Mixtape',    <?php  echo $whereHeard[5]; ?>],
		  ['Club',    <?php  echo $whereHeard[6]; ?>],
		  ['Internet',    <?php  echo $whereHeard[7]; ?>],
		  ['Video',    <?php  echo $whereHeard[8]; ?>],
		  ['Other',    <?php  echo $whereHeard[9]; ?>]
        ]);

        var options = {
          title: 'Where did you hear this song first?',
          is3D: true,
		   backgroundColor: 'none',
		  titleTextStyle: { color: '#FFF' },
		  legendTextStyle: { color: '#FFF' },
		  sliceVisibilityThreshold: .001,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d3'));
        chart.draw(data, options);
      }	  
	  
	  
	  function drawBarChart1() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["1*", <?php echo $whatYouThinkAvg[1]; ?>, "#8fb4f48fb4f4"],
        ["2*", <?php echo $whatYouThinkAvg[2]; ?>, "#4c83e5"],
		["3*", <?php echo $whatYouThinkAvg[3]; ?>, "color: #790999"],
        ["4*", <?php echo $whatYouThinkAvg[4]; ?>, "#d85b8e"],
       	["5*", <?php echo $whatYouThinkAvg[5]; ?>, "color: #eab2c9"]  
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "What do you think about this song?",
        bar: {groupWidth: "100%"},
        legend: { position: "none" },
		backgroundColor: 'none',
		titleTextStyle: { color: '#FFF' },
		legendTextStyle: { color: '#FFF' },
		sliceVisibilityThreshold: .001,
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
      chart.draw(view, options);
  }
  
  
   function drawBarChart2() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["No", <?php echo $anotherFormatAvg[1]; ?>, "#8fb4f48fb4f4"],
        ["CD", <?php echo $anotherFormatAvg[2]; ?>, "#4c83e5"],
		["Vinyl", <?php echo $anotherFormatAvg[3]; ?>, "color: #790999"],
        ["Higher Quality Life", <?php echo $anotherFormatAvg[4]; ?>, "#d85b8e"],
       	["Does not apply to me", <?php echo $anotherFormatAvg[5]; ?>, "color: #eab2c9"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Do you want this song in another format?",
        bar: {groupWidth: "100%"},
        legend: { position: "none" },
		backgroundColor: 'none',
		titleTextStyle: { color: '#FFF' },
		legendTextStyle: { color: '#FFF' },
		sliceVisibilityThreshold: .001,
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values2"));
      chart.draw(view, options);
    }
  
  
  /**/
 /* function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Play it',     <?php echo $howSupportAvg[1]; ?>],
          ['No support',  <?php echo $howSupportAvg[2]; ?>],
          ['Remix it',  <?php echo $howSupportAvg[3]; ?>],
          ['Does not  apply', <?php echo $howSupportAvg[4]; ?>]
        ]);

        var options = {
          title: 'How will you support this project?',
          is3D: true,
		   backgroundColor: 'none',
		  titleTextStyle: { color: '#FFF' },
		  legendTextStyle: { color: '#FFF' },
		  sliceVisibilityThreshold: .001,
		  // legend: { position: 'bottom', alignment: 'vertical' },
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d1'));
        chart.draw(data, options);
      }	
 */ 
  /**/
		  
   function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["Yes", <?php echo $goDistanceAvg[1]; ?>, "#c81eb3"],
        ["No", <?php echo $goDistanceAvg[2]; ?>, "#1e64c8"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Do you think this record will get any support?",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
		backgroundColor: 'none',
		titleTextStyle: { color: '#FFF' },
		legendTextStyle: { color: '#FFF' },
		is3D: true,
		sliceVisibilityThreshold: .001,
		  
		  
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
    }
	  
	  
	function remove_comment(commentId,stagename)
	{
	 
	  if(confirm("Do you want to remove "+stagename+" comment"))
	  {
	  
		 $.ajax({url: "Client_track_review?commentId="+commentId+"&removeComment=1", success: function(result){
      
		 var obj = JSON.parse(result);
		 var count = obj.length; 
		
		 
	     if(obj.status==1)
		 {
		  document.getElementById('commentsDiv'+commentId).innerHTML = '<i>"No Comments"</i>';
		 }
    }});
	  }
	  
	  
	
	} 
	
	function open_member(memberId)
	{
	    $.ajax({url: "Client_track_review?memberId="+memberId, success: function(result){
		  document.getElementById('membersBody'+memberId).innerHTML = result;
        }});
	    $('#members'+memberId).modal('show');
    }
	
	function open_review(reviewId)
	{
	    $.ajax({url: "Client_track_review?reviewId="+reviewId+"&popup=1", success: function(result){
		  document.getElementById('reviewsBody'+reviewId).innerHTML = result;
        }});
	    $('#reviews'+reviewId).modal('show');
    }
	
	 function showPopup(tid,graphId,val,label)
	   {
	   
	      
		  
		   $.ajax({url: "client_track_review/track_review_members_list?tid="+tid+"&graphId="+graphId+"&label="+label+"&val="+val, success: function(result){
              
			  $("#membersListContent").html(result);
			  $('#membersList').modal('show');
           }});
	   }
	   
	   function showMemberReview(rid,tid,graphId,val,label)
	   {
	 	   $.ajax({url: "track_member_review?rid="+rid+"&tid="+tid+"&graphId="+graphId+"&label="+label+"&val="+val, success: function(result){
              
			  $("#membersListContent").html(result);
			  $('#membersList').modal('show');
           }});
	   }
    </script>


@endsection