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
							<li>
								<a href="<?php echo url("admin/tracks"); ?>">
                         <i class="ace-icon fa fa-list list-icon"></i>
                         Tracks</a>
							</li>
							<li class="active">Manage Mp3</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
                            <?php
                            if (isset($alert_message)) {
                            ?>
                                <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
                                </div>
                            <?php
                            }
                            ?>
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-10">
									<?php $track = $tracks['data'][0]; 
									
									
									if(isset($_GET['delete']))
									{
									?>
									<div class="alert alert-success">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  Track deleted successfully !
</div>
									
									<?php 
									
									}
									
									?>
									
									<div>
									
										<h3 class="header smaller lighter blue">Add Audio Files</h3>	
										
										<form action="" method="post" enctype="multipart/form-data" id="manageTracksMp3" style="color:white;">
                                        @csrf
										<div id="audioFiles">
									<div id="audioHtml1">
									<div class="col-sm-4 form-group versionDiv">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Version </label>

										<div class="col-sm-9">
										<!--	<input type="text" id="version1" name="version1" class="col-xs-10 col-sm-10">-->
											
											 <select name="version1" id="version1" class="form-control version">
									  <option value="">Version</option>
									  <option value="Acapella">Acapella</option>
									  <option value="Clean">Clean</option>
									  <option value="Clean Accapella">Clean Accapella</option>
									  <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)</option>
									  <option value="Dirty">Dirty</option>
									  <option value="Dirty Accapella">Dirty Accapella</option>
									  <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)</option>
									  <option value="Instrumental">Instrumental</option>
									  <option value="Main">Main</option>
									  <option value="TV Track">TV Track</option>
									  </select>
									  
										</div>
									</div>
									
									<div class="col-sm-4 form-group versionDiv">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Other Version  </label>

										<div class="col-sm-9">
											<input type="text" id="otherVersion1"  name="otherVersion1" class="col-xs-10 col-sm-10">
										</div>
									</div>
									
									
									<div class="col-sm-4 form-group versionDiv">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> File  </label>

										<div class="col-sm-9">
											<input required type="file" id="audio1"  name="audio1" class="col-xs-10 col-sm-10" required accept=".mp3">
										</div>
									</div>
									
									</div>
									
									</div>
									
									
									<div style="clear:both;"></div>		
									
									<a href="javascript:void()" onclick="moreAudio()" class="btn btn-success btn-sm">+</a>
									
									<a href="javascript:void()" onclick="removeAudio()" class="btn btn-danger btn-sm">-</a>
									
									<input type="hidden" id="divId" name="divId" value="1" />
									
									
									
								<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
										    <input type="hidden" name="addFiles" value="addFiles">
											<button class="btn btn-info btn-sm" type="submit" id="manageTracksMp3-btn" name="addFiles"> 
												<i class="ace-icon fa fa-check bigger-110"></i>
												Add Files
											</button> 

											&nbsp; &nbsp; &nbsp;
											<button class="btn btn-sm" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>	
                                 </form>	
								 
								 
								 	<h3 class="header smaller lighter blue">Manage Audio Files</h3>
									
									<form action="" method="post">
                                    @csrf
									<table id="simple-table" class="table  table-bordered table-hover">
											<thead>
												<tr>
													<th class="center" width="100">
														S. No
													</th>
													<th class="detail-col" width="150">Version</th>
													<th class="detail-col" width="80">Preview</th>
													<th class="detail-col">Track</th>
													<th>Action</th>
												</tr>
											</thead>

											<tbody>
											<?php 
											if($audios['numRows']>0) {
											$i=1; foreach($audios['data'] as $audio) { ?>
												<tr>
													<td class="center"> 
													   <?php echo $i; ?>
													   
		<input type="hidden" value="<?php echo $audio->id; ?>" id="<?php echo 'mp'.$i; ?>" name="<?php echo 'mp'.$i; ?>" />
													</td>

													<td class="left">
			<input type="text" value="<?php echo urldecode($audio->version); ?>" id="<?php echo 'version'.$i; ?>" name="<?php echo 'version'.$i; ?>" />
			
			
													</td>
													
													<td class="left">
														
														
		<input type="radio" value="<?php echo $audio->id; ?>" <?php if($audio->preview==1) { ?> checked="checked" <?php } ?> id="<?php echo 'preview'.$i; ?>" name="<?php echo 'preview'; ?>" />												
													</td>
													<td>
													    
													     <audio controls="" style="width:100%;">
                                                             <?php if (strpos($audio->location, '.mp3') !== false) { ?>
                                                                 <source src="<?php echo asset('AUDIO/' . $audio->location); ?>" type="audio/mp3">
                                                             <?php } else {
                                                                    $fileid = (int) $audio->location;
                                                                    $getlink = '';
                                                                    if (!empty($fileid)) {
                                                                        $getlink = url('download.php?fileID=' . $fileid);
                                                                    } ?>
                                                                 <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                                             <?php } ?>
                                                             Your browser does not support the audio element.
                                                         </audio>
													    
													    
													    
													    
													    
													</td>

													<td>
													
															<a href="javascript:void()" onclick="deleteRecord1('<?php echo $currentPage.'?tid='.$_GET['tid'].'&'; ?>','<?php  echo $audio->id; ?>','Confirm delete <?php  echo $audio->version; ?> ')" class="btn btn-xs btn-danger">
																<i class="ace-icon fa fa-trash-o bigger-120"></i>
															</a>
													</td>
													
												</tr>
												<?php $i++; } ?>
												<tr>
												<td colspan="3">
												</td>
												<td>
												<input type="hidden" name="numMp3s" value="<?php echo $i; ?>" />
												<input type="submit" name="updateMp3s" value="Update MP3s" class="btn btn-sm btn-primary" />
												</td>
												</tr>
												<?php } else { ?>
												<tr><td colspan="4">No Data found.</td></tr>
												<?php } ?>
										   </tbody>
										</table>
										</form>
									</div>
									
									
										
										
										
										
									</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script> 					
					<script>
					
					function removeAudio()
					{
					
					 var divId = document.getElementById('divId').value;
					
					if(divId>1)
					{
					 var divIdMinus = parseInt(divId)-1;
					 document.getElementById('divId').value = divIdMinus;
					 $("#html"+divId).remove(); 
					 }
					  
					}
					
					function moreAudio()
					{
					
					
					
					 var divId = document.getElementById('divId').value;
					 var divIdPlus = parseInt(divId)+1;
					 document.getElementById('divId').value = divIdPlus;
					 
					  var parentDiv = document.createElement("div");
					  parentDiv.setAttribute('id','html'+divIdPlus);
					 
					  					
					  var smDiv1 = document.createElement("div");
					  smDiv1.setAttribute('class','col-sm-4 form_group');
					  
					   var smDiv2 = document.createElement("div");
					  smDiv2.setAttribute('class','col-sm-9');
					  
					  var label1 = document.createElement("label");
					  label1.setAttribute('class','col-sm-3 control-label no-padding-right');
					 
					   var textnode1 = document.createTextNode("Version");
					   label1.appendChild(textnode1);   
					   
					   
					  var input1 = document.createElement("select");
					  input1.setAttribute('type','text');
					  input1.setAttribute('name','version'+divIdPlus);
					  input1.setAttribute('id','version'+divIdPlus);
					  input1.setAttribute('class','form-control version');
					  
					  
					  var option1 = document.createElement("option");
						option1.setAttribute('value','');
						option1.text = "Version";
						input1.add(option1);
						
						var option2 = document.createElement("option");
						option2.setAttribute('value','Acapella');
						option2.text = "Acapella";
						input1.add(option2);
						
						
					    var option3 = document.createElement("option");
						option3.setAttribute('value','Clean');
						option3.text = "Clean";
						input1.add(option3);
						
						
						
						
						var option4 = document.createElement("option");
						option4.setAttribute('value','Clean Accapella');
						option4.text = "Clean Accapella";
						input1.add(option4);
						
						
						var option5 = document.createElement("option");
						option5.setAttribute('value','Clean (16 Bar Intro)');
						option5.text = "Clean (16 Bar Intro)";
						input1.add(option5);
						
						
						var option6 = document.createElement("option");
						option6.setAttribute('value','Dirty');
						option6.text = "Dirty";
						input1.add(option6);
						
						
						var option7 = document.createElement("option");
						option7.setAttribute('value','Dirty Accapella');
						option7.text = "Dirty Accapella";
						input1.add(option7);
						
						
						var option8 = document.createElement("option");
						option8.setAttribute('value','Dirty (16 Bar Intro)');
						option8.text = "Dirty (16 Bar Intro)";
						input1.add(option8);
						
						
						var option9 = document.createElement("option");
						option9.setAttribute('value','Instrumental');
						option9.text = "Instrumental";
						input1.add(option9);
						
						
						var option10 = document.createElement("option");
						option10.setAttribute('value','Main');
						option10.text = "Main";
						input1.add(option10);
						
						
						var option11 = document.createElement("option");
						option11.setAttribute('value','TV Track');
						option11.text = "TV Track";
						input1.add(option11);
						
					    
					  
					  smDiv2.appendChild(input1);   
					  smDiv1.appendChild(label1);    
					  smDiv1.appendChild(smDiv2);
					  
					  
					  var otherDiv = document.createElement("div");
					  otherDiv.setAttribute('class','col-sm-4 form_group');
					  
					  var otherDiv1 = document.createElement("div");
					  otherDiv1.setAttribute('class','col-sm-9');
					  
					  var otherLabel = document.createElement("label");
					  otherLabel.setAttribute('class','col-sm-3 control-label no-padding-right');
					 
					  var otherTextnode = document.createTextNode("Other Version");
					  otherLabel.appendChild(otherTextnode);  
					  
					  
					  var otherInput = document.createElement("input");
					  otherInput.setAttribute('type','text');
					  otherInput.setAttribute('name','otherVersion'+divIdPlus);
					  otherInput.setAttribute('id','otherVersion'+divIdPlus);
					  otherInput.setAttribute('class','col-xs-10 col-sm-10');
					   
					
					  otherDiv1.appendChild(otherInput);   
					  otherDiv.appendChild(otherLabel);    
					  otherDiv.appendChild(otherDiv1);  
					  
					  
					  
					  
					   var smDiv3 = document.createElement("div");
					  smDiv3.setAttribute('class','col-sm-4 form_group');
					  
					   var smDiv4 = document.createElement("div");
					  smDiv4.setAttribute('class','col-sm-9');
					  
					  var label2 = document.createElement("label");
					  label2.setAttribute('class','col-sm-3 control-label no-padding-right');
					 
					   var textnode2 = document.createTextNode("File");
					   label2.appendChild(textnode2);   
					   
					   
					  var input2 = document.createElement("input");
					  input2.setAttribute('type','file');
					  input2.setAttribute('name','audio'+divIdPlus);
					  input2.setAttribute('id','audio'+divIdPlus);
					  input2.setAttribute('class','col-xs-10 col-sm-10');
					  input2.setAttribute('required','');
					  input2.setAttribute('accept','.mp3');
					  
					  smDiv4.appendChild(input2);   
					  smDiv3.appendChild(label2);    
					  smDiv3.appendChild(smDiv4);  
					  
					  
					  
					 parentDiv.appendChild(smDiv1);  
					 parentDiv.appendChild(otherDiv);    
					 parentDiv.appendChild(smDiv3);   
					 
					 
					 
					  var clearboth = document.createElement("div");
					  clearboth.setAttribute('class','clearDiv');
					  document.getElementById('audioFiles').appendChild(clearboth);
					 
					 document.getElementById('audioFiles').appendChild(parentDiv);
					 
					 
					
					  
					   
					  }  
					  
					  
					  function abc14(page,did,msg)
					  {
					  
					    alert(page);
						
						alert(did);
						
						alert(msg);
						
						return false;
					  }
                    $("#manageTracksMp3").validate();
                     var $theForm = $("#manageTracksMp3");
                   $theForm.submit(function () {
                    if($theForm.valid()) {
                        //console.log('addTrack_Validated');
                        $('#manageTracksMp3-btn').attr('disabled','disabled');
                        $('.processing_loader_gif').show();     
                
                    }else{
                      $("html, body").animate({ scrollTop: 0 }, "slow");
                      return false;        
                    }
                    
                   });			
					
					</script>

@endsection