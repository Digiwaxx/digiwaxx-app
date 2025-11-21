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

                

                    <div class="col-lg-6 col-md-12">

					

					<?php if(isset($alert_class)) 

							{ ?>

						

						 <div class="<?php echo $alert_class; ?>">

                            	<p><?php echo $alert_message; ?></p>

                            </div>

							<?php } // print_r($formData); ?>

							

							

							

                    	<div class="mtk-blk f-block">

						

						

                        	<div class="stk-btn clearfix">

                                <h1 class="pull-left">VIDEO REVIEWS</h1> 

                            </div>



                            

							

							<div style="clear:both;"></div>							

                            <div class="mtk-list">

                            	<ul class="mCustomScrollbar" style="height:760px; overflow:auto;">

								

								

								

								

								

								

								

								

								

								<?php 

								if($videos['numRows']>0) {

								foreach($videos['data'] as $video)  { ?>

                                	<li class="clearfix">

                                    	<div class="pull-left img-blk">

										<?php 

										

										$ext = explode('.',$video->video_file);

										$countExt = count($ext);

										$ext = $ext[$countExt-1];

								/*		echo '<br />';

										echo base_url('VIDEO/'.$video->video_file); 

								*/		

																				

										if($tipData[$video->video_review_id]['numRows']>0)

										{										

										 $tip_form_display = 'none';

										 $tip_box_display = 'block';

										 $points = $tipData[$video->video_review_id]['data'][0]->points;

										}

										else

										{

										 $tip_form_display = 'block';

										 $tip_box_display = 'none';

										 $points = 0;

										}

										

										

										/*

									if(strlen($track->imgpage)>3)

									{

									

									   $imgSrc = 'ImagesUp/'.$track->imgpage;

									}

									else

									{

									$imgSrc = 'assets/img/track-logo.png';

									}	

									*/	?>

										

										<!--

                                        	<a href="<?php // echo base_url("Client_edit_track?tId=".$track->id); ?>">

											   <img src="<?php // echo $imgSrc; ?>" width="100" height="100">

											</a>   

                                            

                                           

                                            

                                        </div>-->

										<!-- eof img-blk -->

                                        

                                        

                                        

                                        <div class="pull-left det-blk">

										

										

                               <video width="320" height="240" controls>

										  <source src="<?php echo asset('VIDEO/'.$video->video_file); ?>" type="video/<?php echo $ext; ?>">

										  

										  Your browser does not support the video tag.

										</video>

										

        <p class="rlb" style="margin-top:10px;">DJ Member: <?php echo urldecode($video->fname); ?></p>

        <p class="rlb" style="display:<?php echo $tip_box_display; ?>;" id="tip_box_display_<?php echo $video->video_review_id; ?>">

		    Tip: <span id="tip_display_<?php echo $video->video_review_id; ?>"><?php echo $points; ?></span>

		</p>                                            

											

											

											<div>

											  <div style="float:left;">

											

											</div>

											<div style="float:left;">

											

											<form  class="form-horizontal" role="form" style="display:<?php echo $tip_form_display; ?>;" id="tip_form_display_<?php echo $video->video_review_id; ?>">

											<input type="text" id="tip_digicoin_<?php echo $video->video_review_id; ?>" class="form-control input" placeholder="TIP" />

											<input type="button" value="TIP DIGICOIN" class="add_track_button" onclick="tip_digicoin('<?php echo $video->video_review_id; ?>','<?php echo $video->track_id; ?>')" />

											</form>

										

											

											

											</div>

											</div>

                                        </div><!-- det-blk -->

                                        

                                    </li>

                                    

                                    <?php } } else { ?>

									<p>No reviews</p>

									<?php } ?>

                                      

                                </ul>

                            </div><!-- eof mtk-list -->

                            

                            

                            



                        </div>

                    </div><!-- eof middle block -->


				@include('clients.dashboard.includes.my-tracks')
				
			</div>
		</div>
	</div>
	</div>
	</div>
		
 </section>

 	<script>

		function tip_digicoin(video_id,tId)

		{

         var tip = document.getElementById('tip_digicoin_'+video_id).value;

		if(tip>0)

		{

		$.ajax({url: "client_track_feedback_video?tId="+tId+"&tip="+tip+"&video_id="+video_id+"&submit_tip=1", success: function(result){

					        

							row = JSON.parse(result);

							if(row.response==1)

							{

							 document.getElementById('tip_form_display_'+video_id).style.display = 'none';

							 document.getElementById('tip_box_display_'+video_id).style.display = 'block';

							 document.getElementById('tip_display_'+video_id).innerHTML = tip;

                            }

							else

							{

	                         document.getElementById('tip_form_display_'+video_id).style.display = 'block';

							 document.getElementById('tip_box_display_'+video_id).style.display = 'none';

							}

						 }});

						}

		}

		

		

		function sortBy(type,id)

		{

		 var records = document.getElementById('records').value;		

		 window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;

		}

		

		function changeNumRecords(type,id,records)

		{

		 window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;

		}

		</script>



@endsection