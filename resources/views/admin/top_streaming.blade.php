@extends('admin.admin_dashboard_active_sidebar')
    @section('content')
<div class="main-content">

				<div class="main-content-inner">

					<!-- #section:basics/content.breadcrumbs -->

					<div class="breadcrumbs" id="breadcrumbs">

						<script type="text/javascript">

							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}

						</script>



						<ul class="breadcrumb">

							<li class="active">

								<i class="ace-icon fa fa-list list-icon"></i>

								Tracks

							</li>

						</ul><!-- /.breadcrumb -->

					</div>



					<!-- /section:basics/content.breadcrumbs -->

					<div class="page-content">

						<div class="row">

							<div class="col-xs-12">

								<!-- PAGE CONTENT BEGINS -->

								<div class="row">

									<div class="col-xs-12">

									

									

									<?php 

									 if(isset($alert_message))

									 {

									  ?>

									  

									  

									  <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>

									  <?php 

									 

									 }

									

									

									?>

									

									<script src="/assets/js/drag/jquery-sg.js"></script>
                                      <script src="/assets/js/drag/jquery-ui.js"></script>
                                      <script>
                                      $( function() {
                                        $( "#sample-table-1 tbody" ).sortable();
                                        $( "#sample-table-1 tbody" ).disableSelection();
                                        
                                      } );
                                      
                                      function save_order(){
                                            var itemOrder=$('#sample-table-1 tbody').sortable("toArray");
                                            var my_order=[];
                                            console.log(my_order);
                                            for (var i = 0; i < itemOrder.length; i++) {
                                                my_order[i]=itemOrder[i];
                                            }
                                            
                                            $.ajax({url: "tracks?streaming_order="+my_order+"&setting_top_streaming_order=1", 
                                                beforeSend: function() {
                                                    // setting a loader
                                                    $('.processing_loader_gif').show();
                                                },
                        			    	    success: function(result){
                        			    	         $('.processing_loader_gif').hide();
                        			    	       alert("Top Streaming Order saved!")
                        			    	       }
                        			    	});
                                            
                                        }
                                      </script>

					                    <button onclick="save_order()" class="btn btn-primary" style="margin-bottom:10px">Save Top Streaming Tracks Order</button>

										<table id="sample-table-1" class="table table-striped table-bordered table-hover draggable-table">

											<thead>

												<tr>

													<th class="center" width="60">

														S. No.

													</th>

													<th>Artwork</th>

													<th class="hidden-xs">Artist</th>

													<th class="hidden-xs">Title</th>

													<th class="hidden-xs">Album</th>

													<th>User Type</th>
													<th>Submitted By</th>

													<!--<th class="hidden-md hidden-sm hidden-xs">Label</th>

													<th class="hidden-md hidden-sm hidden-xs">Time</th>

													--><th class="hidden-md hidden-sm hidden-xs">Added On</th>
													
													<th class="hidden-xs">Delete button</th>



													

												</tr>

											</thead>



											<tbody>

											

											<?php 

                                            if(!empty($start ))  {

                                                $start = $start ;
                                            } 
                                            else{
                                                $start = 0;
                                            }

                                            $i = $start+1;
                                        
                                        
										foreach($tracks as $track)

										{

										

										?>

												<tr id="<?php echo $track->id; ?>">

													<td class="center">

													<?php echo $i; ?>									

													</td>



													<td>

														<?php
                                                        
                                                        // echo $track->product_name;

													if(!empty($track->pCloudFileID)){
													    $artWork = url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);?>
													    <img src="<?php echo $artWork; ?>" width="50" height="56" />
										        	<?php }

													  else if(!empty($track->imgpage)){
														    $artWork = asset('ImagesUp/'.$track->imgpage); ?>
														    <img src="<?php echo $artWork; ?>" width="50" height="56" />
														    <?php
														}

														?>

														

													</td>

													<td class="hidden-xs"><?php echo urldecode($track->artist);  ?></td>

													<td class="hidden-xs"><?php echo urldecode($track->title); ?></td>

													

													<td class="hidden-xs">

														<?php echo ucfirst(urldecode($track->album));  ?>

													</td>

													<td><?php if(!empty($track->name)){echo "CLIENT";} if(!empty($track->fname)){echo "MEMBER";}?></td>
                                                     <td><?php if(!empty($track->name)){echo urldecode($track->name);} if(!empty($track->fname)){echo urldecode($track->fname);}?></td>


													

													<?php // echo urldecode($track->label);  ?>

													<?php  // echo ucfirst(urldecode($track->time)); ?>

													

													

													<td class="hidden-md hidden-sm hidden-xs"><?php $addedOn = $track->added;

                                                                echo $addedOn;

													        //   $addedOn = explode(' ',$track->added);

															//   $addedDate =  explode('-',$addedOn[0]);

															//   $addedDate = $addedDate[2].'-'.$addedDate[1].'-'.$addedDate[0];

															//   echo $addedDate;

													

													

													  ?></td>
													  
													  <td>
													        <a title="Delete Track" href="<?php echo url("admin/top_streaming")."?delete_streaming=".$track->id; ?>" class="btn btn-xs btn-danger">
                                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                            </a>
													  </td>

												</tr>

												

												<?php $i++; } 

                                                if(!empty($numPages)){

                                                    $numPages = $numPages;
                                                }
                                                else{

                                                    $numPages = 0;
                                                }
                                                
                                                
                                                
                                                if($numPages>1) { ?>

						

							<tr>

							  <td colspan="10">

												  <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">

                                <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>

                                <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>

                                <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>

                                <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>

                                <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>

                              </ul>

							   </td>

							</tr>

							<?php } ?>

                                    </tbody>

										</table>

									</div><!-- /.span -->

								</div><!-- /.row -->



								<div class="hr hr-18 dotted hr-double"></div>



							

								<!-- PAGE CONTENT ENDS -->

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div><!-- /.page-content -->

					

				<script>

				

				function get_selected_data()

					{

	 var sortBy = document.getElementById('sortBy').value;

	 var sortOrder = document.getElementById('sortOrder').value;

	 var numRecords = document.getElementById('numRecords').value;

	 

	 var artist = document.getElementById('artist').value;

	 var title = document.getElementById('title').value;

	 var label = document.getElementById('label').value;

	 var album = document.getElementById('album').value;

	 var producer = document.getElementById('producer').value;

	 var client = document.getElementById('client').value;

	 

	window.location = "tracks?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&artist="+artist+"&title="+title+"&label="+label+"&album="+album+"&producer="+producer+"&client="+client;

					}

				

				function selectStaff(trackId)

				{

				 

                        

						var status = 0;

						

						if(document.getElementById('staff'+trackId).checked == true)

						{

						  status = 1;

						}

						else

						{

						  status = 0;

						}

						

						

						$.ajax({url: "tracks?choosen=1&section=1&trackId="+trackId+"&status="+status, success: function(result){

						

							 row = JSON.parse(result);

							var responseMessage = '';

							var responseColor =  '';

						

							if(row.response==1)

							{

							responseColor = '#090';  						

                            }

							else

							{

	                        responseColor = '#FF0000';  						

							}

							

							document.getElementById('staffResponse'+trackId).style.color = responseColor;

							document.getElementById('staffResponse'+trackId).innerHTML = row.message;

							

                            }});

 				  

				

				}

				

				

				function selectYou(trackId)

				{

                        

						var status = 0;

						

						if(document.getElementById('you'+trackId).checked == true)

						{

						  status = 1;

						}

						else

						{

						  status = 0;

						}

						

						

						$.ajax({url: "tracks?choosen=1&section=2&trackId="+trackId+"&status="+status, success: function(result){

							 row = JSON.parse(result);
							var responseMessage = '';
							var responseColor =  '';

							if(row.response==1)
							{
							    responseColor = '#090';  						
                            }
							else
							{
	                        responseColor = '#FF0000';  						
							}
							document.getElementById('youResponse'+trackId).style.color = responseColor;

							document.getElementById('youResponse'+trackId).innerHTML = row.message;

							

                            }});

 				  

				

				}
				function top_streaming(trackId){
			    	$.ajax({url: "tracks?check="+document.getElementById('stream-'+trackId).checked+"&setting_top_streaming=1&trackId="+trackId, 
			    	    success: function(result){
			    	        console.log(result);
    				// 		row = JSON.parse(result);
    				// 		var responseMessage = '';
    				// 		var responseColor =  '';
    				// 		if(row.response==1)
    				// 		{
    				// 		    responseColor = '#090';  						
        //                     }
    				// 		else{
        //                     responseColor = '#FF0000';  						
    				// 		}
    				// 		document.getElementById('youResponse'+trackId).style.color = responseColor;
    
    				// 		document.getElementById('youResponse'+trackId).innerHTML = row.message;


                        }
			    	});
				}

				

				</script>
                

					
   @endsection  