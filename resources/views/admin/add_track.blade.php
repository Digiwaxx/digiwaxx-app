@extends('admin.admin_dashboard_active_sidebar')
    @section('content')
<div class="main-content add-track-page">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {}
            </script>
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo url("admin/tracks"); ?>">
                        <i class="ace-icon fa fa-list list-icon"></i>
                        Tracks</a>
                </li>
                <li class="active">Add Track - Step 1</li>
            </ul><!-- /.breadcrumb -->
            <!-- /section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        
    
	<?php if(isset($file_error)){ 
		
			foreach($file_error as $file_errors){
					?>
				<div class="alert alert-danger">
					<a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
					<strong>Error!</strong> <?php echo $file_errors; ?>
				</div>
				<?php }
				} ?>		 
			<div class="alert alert-success" id="track-exist-check" style="display:none">
				<a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong><span class="show-error"></span> 
			</div>
			
        <div class="page-content">
            <div class="row">

            <?php if(isset($alert_class)) 
				    { ?>
			
			
                        <div class="<?php echo $alert_class; ?>">
                                    <button class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    <?php echo $alert_message; ?>
                                </div>
				        <?php } ?>
                        
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                            <form id="add_trackform" role="form" action="" name="addtrackform" method="post" enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off" style="color:white;">
                            @csrf
                                
                                    <h3 class="header smaller lighter">
                                        Track Information
                                    </h3>
                                    <div class="row">
                                    <input type="hidden" id="divId" name="divId" value="1" />
									<div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Artist Name</label>
                                        
                                            <input required type="text" id="artist" name="artist" class="form-control artist_title" value="<?php if(!empty($trackData->artist)) echo urldecode($trackData->artist); ?><?php if(empty($trackData->artist)) { echo set_value('artist');} ?>" placeholder="Enter Artist Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Song Title </label>
                                        
                                            <input required type="text" id="title" name="title" class="form-control artist_title" value="<?php if(!empty($trackData->title)) echo urldecode($trackData->title); ?><?php if(empty($trackData->title)) { echo set_value('title'); } ?>" placeholder="Enter Song Title">
                                        </div>
                                    </div>
                                    
                                    
								
									
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right " for="form-field-1"> Featured Artist-1 </label>
                                        
                                            <input type="text" id="featured_artist_1" name="featured_artist_1" class="form-control" value="<?php if(!empty($trackData->featured_artist_1)) echo urldecode($trackData->featured_artist_1); ?><?php if(empty($trackData->featured_artist_1)){ echo set_value('featured_artist_1'); } ?>" placeholder="Featured Artist-1">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Featured Artist-2 </label>
                                        
                                            <input type="text" id="featured_artist_2" name="featured_artist_2" class="form-control" value="<?php if(!empty($trackData->featured_artist_2)) echo urldecode($trackData->featured_artist_2); ?><?php if(empty($trackData->featured_artist_2)){ echo set_value('featured_artist_2'); } ?>" placeholder="Featured Artist-2">
                                        </div>
                                    </div>
									
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Type </label>
                                        
                                            <select required id="type" name="type" class="form-control">
                                                <option value="track" <?php if(!empty($trackData->type)) echo ($trackData->type=='track')?" selected=' selected'":""?>  <?php if(set_value('track') == 'track') { echo "selected"; } ?>>Track</option>
                                                <option value="product" <?php if(!empty($trackData->type)) echo ($trackData->type=='product')?" selected=' selected'":""?> <?php if(set_value('track') == 'product') { echo "selected"; } ?>>Product</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Client </label>
                                        
                                            <?php // foreach($clients as $client) {  echo $client->id;  }
                                            ?>
                                            <select id="client" name="client" class="form-control">
                                                <option value="">Select Client</option>
                                                <?php foreach ($clients as $client) { ?>
                                                    <option value="<?php echo $client->id;?>" <?php if(!empty($trackData->client)) echo ($trackData->client == $client->id)?" selected=' selected'":""?> <?php if(set_value('client') == $client->id) { echo "selected"; } ?>><?php echo urldecode($client->name); ?></option>
                                                <?php } ?>
                                            </select>

                                            <!-- <input type="text" id="client" name="client" />
                                            <input type="text" id="clientSearch" onkeyup="getList1(this.value)" class="form-control" onfocus="getList1(this.value)" onMouseOver="showList1()" onMouseOut="removeList1()" />
                                            <br />
                                            <div style="clear:both;"></div>
                                            <div style="position:relative;">
                                                <div onMouseOver="showList1()" onMouseOut="removeList1()" id="searchListDisplay1" class="form-control" style=" position:absolute; background:#E5E5E5; padding:10px; padding-right:0px; top:0px; z-index:100; display:none;">
                                                    <div style="max-height:200px; overflow-y:scroll;">
                                                        <ul id="searchList1" style="list-style:none; margin:0px;">
                                                            <li>Loading ...</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right " for="form-field-1"> Label/Company </label>
                                        
                                            <input type="text" id="company" placeholder="Label / Company" name="company" class="form-control" value="<?php if(!empty($trackData->label)) echo urldecode($trackData->label); ?><?php if(empty($trackData->label)) { echo set_value('company'); } ?>" placeholder="Enter Label/Company">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Producer(s) </label>
                                        
                                            <input type="text" id="producers" name="producers" class="form-control" value="<?php if(!empty($trackData->producer)) echo urldecode($trackData->producer); ?><?php if(empty($trackData->producer)) { echo set_value('producers'); } ?>" placeholder="Producer(s)">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Release Type </label>
                                        
                                            <select name="albumType" id="albumType" class="form-control">
                                                <!--<option value="">Release Type</option>-->
                                                  <?php foreach ($releasetypes as $release) { ?>
                                                    <option value="<?php echo $release->id;?>" <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], $release->id) == 0) { ?> selected <?php } ?> <?php if(set_value('albumType') == $release->id) { echo "selected"; } ?> <?php if(!empty($trackData->albumType)) echo ($trackData->albumType == $release->id)?" selected=' selected'":""?>><?php echo $release->release_name; ?></option>
                                                <?php } ?>
                                        
                                            </select>
                                        </div>
                                    </div>
									<div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Writer</label>
                                        
                                            <input type="text" id="writer" name="writer" class="form-control" value="<?php if(!empty($trackData->writer)) echo urldecode($trackData->writer); ?><?php if(empty($trackData->writer)) { echo set_value('writer'); } ?>" placeholder="Writer">
                                        </div>
                                    </div>
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Album </label>
                                        
                                            <input type="text" id="album" name="album" class="form-control" value="<?php if(!empty($trackData->album)) echo urldecode($trackData->album); ?><?php if(empty($trackData->album)) { echo set_value('album'); } ?>" placeholder="Album">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">BPM </label>
                                        
                                            <input type="text" id="bpm" name="bpm" class="form-control" value="<?php if(!empty($trackData->bpm)) echo urldecode($trackData->bpm); ?><?php if(empty($trackData->bpm)) { echo set_value('bpm'); } ?>" placeholder="BPM">
                                        </div>
                                    </div>
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Track Time </label>
                                            <div class="input-group">
                                                <input placeholder="0:00:00" required type="text" id="time" name="time" value="<?php if(!empty($trackData->time)) echo urldecode($trackData->time); ?><?php if(empty($trackData->time)){ echo set_value('time'); } ?>" class="form-control" pattern="[0-9]{1}:[0-9]{2}:[0-9]{2}">
                                                <span class="input-group-addon">0:00:00</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Genre </label>
                                        
                                            <select required name="genre" id="genre" class="form-control" onchange="change_genre(this.value)">
                                                <option value="">Genre</option>
                                                <?php if ($genres['numRows'] > 0) {
                                                    foreach ($genres['data'] as $genre) { ?>
                                                        <option value="<?php echo $genre->genreId; ?>" <?php if(!empty($trackData->genreId)) echo ($trackData->genreId == $genre->genreId)?" selected=' selected'":""?>  <?php if(set_value('genre') == $genre->genreId) { echo "selected"; } ?>><?php echo $genre->genre; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Sub Genre </label>
                                        
                                            <select name="subGenre" id="subGenre" class="form-control">
                                                <option value="">Sub Genre</option>
                                                <?php if ($subGenres['numRows'] > 0) {
                                                    foreach ($subGenres['data'] as $genre) { ?>
                                                        <option value="<?php echo $genre->subGenreId; ?>" <?php if(!empty($trackData->subGenreId)) echo ($trackData->subGenreId == $genre->subGenreId)?" selected=' selected'":""?>><?php echo $genre->subGenre; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="col-sm-6">
                                        <div class="form-group">
										<label class="control-label no-padding-right" for="form-field-1">Release Date </label>
										
											<input type="text" id="releaseDate" name="releaseDate" class="form-control">
										</div>
									</div>
									-->
									
									<div class="col-sm-6">
                                        <div class="form-group">
										<label class="control-label no-padding-right" for="form-field-1">Song key</label>
										
											<input type="text" id="songkey" name="songkey" class="form-control" placeholder="Song key">
										</div>
									</div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> More Info. </label>
                                        
                                            <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control"><?php if(!empty($trackData->moreinfo)) echo urldecode($trackData->moreinfo); ?><?php if(empty($trackData->moreinfo)) { echo set_value('moreInfo'); } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Notes </label>
                                        
                                            <textarea id="notes" placeholder="Track Notes" name="notes" class="form-control"><?php if(!empty($trackData->notes)) echo urldecode($trackData->notes); ?><?php if(empty($trackData->notes)) { echo set_value('notes'); } ?></textarea>
                                        </div>
                                    </div>
									<div style="clear:both;"></div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Status </label>
                                        
                                            <select required name="status" id="status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="draft" <?php if(!empty($trackData->status)) echo ($trackData->status == 'draft')?" selected=' selected'":""?> <?php if(set_value('status') == 'draft') { echo "selected"; } ?>>Draft</option>
                                                <option value="publish" <?php if(!empty($trackData->status)) echo ($trackData->status == 'publish')?" selected=' selected'":""?> <?php if(set_value('status') == 'publish') { echo "selected"; } ?>>Publish</option>
                                            </select>
                                        </div>
                                    </div>
									<div style="clear:both;"></div>
 
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Page Image (Artwork)</label>
                                        
                                        
                                            <input required type="file" id="pageImage" name="pageImage" class="form-control form-file" value="" accept="image/png, image/gif, image/jpeg">
                                            <!-- <span style="color:red;">Dimension:900x900 (png/jpg/jpeg/gif/tiff/svg)</span> -->
                                            
											<?php
                                                if (!empty($trackData->imgpage) && strlen($trackData->imgpage) > 4 && file_exists(base_path('ImagesUp/'.$trackData->imgpage))) { ?>
                                                  <img src="<?php if(!empty($trackData->imgpage)) echo asset('ImagesUp/' . $trackData->imgpage); ?>" width="50" height="50" />
												  <button type="button" class="btn btn-xs btn-danger" onclick="deletePageImage(<?php echo (int) $trackData->id; ?>, this);"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
												<?php } ?>
                                        </div>
                                    </div>       
                                    
                                     <div class="col-sm-6">
                                         <input type="hidden" id="coverimage" name="coverimage" class="form-control form-file" value="" accept="image/png, image/gif, image/jpeg">
                                        <!--div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Back Cover </label>
                                        
                                            <input type="file" id="coverimage" name="coverimage" class="form-control form-file" value="" accept="image/png, image/gif, image/jpeg">
											<?php
                                                //if (!empty($trackData->coverimage) && strlen($trackData->coverimage) > 4 && file_exists(base_path('ImagesUp/'.$trackData->coverimage))) { ?>
                                                 <img src="<?php //if(!empty($trackData->coverimage)) echo asset('ImagesUp/' . $trackData->coverimage); ?>" width="50" height="50" />
												
												<?php //} ?>
                                        </div-->
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Available to Members </label>
                                        
                                            <div class="radio">
                                                <label>
                                                    <input required name="availableMembers" type="radio" class="ace" value="1" <?php if(!empty($trackData->active)) echo ($trackData->active == '1')?" checked=' checked'":""?>>
                                                    <span class="lbl"> Yes </span>
                                                </label>
                                                <label>
                                                    <input required name="availableMembers" type="radio" class="ace" value="0" <?php if(!empty($trackData->active)) echo ($trackData->active == '0')?" checked=' checked'":""?>>
                                                    <span class="lbl"> No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Reviewable </label>
                                        
                                            <div class="radio">
                                                <label>
                                                    <input required name="reviewable" type="radio" class="ace" value="1" <?php if(!empty($trackData->review)) echo ($trackData->review == '1')?" checked=' checked'":""?>>
                                                    <span class="lbl"> Yes </span>
                                                </label>
                                                <label>
                                                    <input required name="reviewable" type="radio" class="ace" value="0" <?php if(!empty($trackData->review)) echo ($trackData->review == '0')?" checked=' checked'":""?>>
                                                    <span class="lbl"> No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php if(0){ ?>
                                    
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Graphics Complete </label>
                                        <div class="checkbox">
                                            <label>
                                                <input name="graphics" class="ace ace-checkbox-2" type="checkbox" value="1" <?php if(!empty($trackData->graphicscomplete)) echo ($trackData->graphicscomplete == '1')?" checked=' checked'":""?>>
                                                <span class="lbl"></span>
                                            </label>
                                        </div>
                                    </div>
                                    </div>
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">White Label Artwork </label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="whiteLabel" name="whiteLabel" value="1" class="ace ace-checkbox-2" <?php if(!empty($trackData->whitelabel)) echo ($trackData->whitelabel == '1')?" checked=' checked'":""?>>
                                                <span class="lbl"></span>
                                            </label>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Is this track a priority?</label>
                                        <div class="checkbox">
                                            <label>
                                                <input name="priorityType" class="ace ace-checkbox-2" type="checkbox" value="top-priority" <?php if(!empty($trackData->graphicscomplete)) echo ($trackData->graphicscomplete == 'top-priority')?" checked=' checked'":""?>>
                                                <span class="lbl"></span>
                                            </label>
                                        </div>
                                    </div>
                                    </div>
                                      
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Member Preview Available ?</label>
                                        <div class="checkbox">
                                            <label>
                                                <input name="memberPreviewAvailable" class="ace ace-checkbox-2" type="checkbox" value="yes" <?php if(!empty($trackData->memberPreviewAvailable)) echo ($trackData->memberPreviewAvailable == 'yes')?" checked=' checked'":""?>>
                                                <span class="lbl"></span>
                                            </label>
                                        </div>
                                    </div>
                                    </div>
                                    
                                <?php } ?>
                                    <div class="col-xs-12">
                                         <h3 class="header smaller lighter">
                                        Contact Details
                                        </h3>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Name </label>
                                            <input type="text" id="contact_name" name="contact_name" value="<?php if(!empty($trackData->contact_name)) echo urldecode($trackData->contact_name); ?><?php if(empty($trackData->contact_name)) { echo set_value('contact_name'); } ?>" class="form-control" placeholder="Contact Name">
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    	
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Email </label>
                                            <input type="email" id="contact_email" name="contact_email" value="<?php if(!empty($trackData->contact_email)) echo urldecode($trackData->contact_email); ?><?php if(empty($trackData->contact_email)) { echo set_value('contact_email'); } ?>" class="form-control" placeholder="Contact Email" required>
                                        </div>
                                        
                                    </div>
									<div class="col-sm-6"> 
										<div class="form-group"> 
											<label class="control-label no-padding-right " for="form-field-1">Second Email </label> 
											<input type="email" id="second_contact_email" name="second_contact_email" value="<?php if(!empty($trackData->second_contact_email)) echo urldecode($trackData->second_contact_email); ?><?php if(empty($trackData->second_contact_email)) { echo set_value('second_contact_email'); } ?>" class="form-control" placeholder="Contact Email">  
										</div>
                                    </div>  
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label no-padding-right " for="form-field-1">Third Email </label>
											<input type="email" id="third_contact_email" name="third_contact_email" value="<?php if(!empty($trackData->third_contact_email)) echo urldecode($trackData->third_contact_email); ?><?php if(empty($trackData->third_contact_email)) { echo set_value('third_contact_email'); } ?>" class="form-control" placeholder="Contact Email">
										</div>
									</div>
									
							 
									 <div class="col-sm-6"> 
										 <div class="form-group"> 
											 <label class="control-label no-padding-right" for="form-field-1">Fourth Email </label>
											 <input type="email" id="fourth_contact_email" name="fourth_contact_email" value="<?php if(!empty($trackData->fourth_contact_email)) echo urldecode($trackData->fourth_contact_email); ?><?php if(empty($trackData->fourth_contact_email)) { echo set_value('fourth_contact_email'); } ?>" class="form-control" placeholder="Contact Email">
										 </div>
									 </div>
									
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Phone</label>
                                            <input type="text" id="contact_phone" name="contact_phone" value="<?php if(!empty($trackData->contact_phone)) echo urldecode($trackData->contact_phone); ?><?php if(empty($trackData->contact_phone)) { echo set_value('contact_phone'); } ?>" class="form-control" placeholder="Contact Phone">
                                        </div>
                                        
                                    </div>
                                    
                                    
                                
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Relationship to Artist</label>
                                            <input type="text" id="relationship_to_artist" name="relationship_to_artist" value="<?php if(!empty($trackData->relationship_to_artist)) echo urldecode($trackData->relationship_to_artist); ?><?php if(empty($trackData->relationship_to_artist)) { echo set_value('relationship_to_artist'); } ?>" class="form-control" placeholder="Relationship to Artist">
                                        </div>
                                    </div>	
									
									 
									
									 
                                    
                                    <div class="col-xs-12">
                                        <div class="form-actions text-right">
                                            <input type="hidden" name="addTrack" value="addTrack">
                                            <button class="btn btn-info btn-sm" id="submitbutton" type="submit" name="addTrack">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Submit - Step 1
                                            </button>
                                            &nbsp; 
                                            <button class="btn btn-sm btn-reset" type="reset" onclick="addtrackform.reset();">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
                                        &nbsp; 
                                            <button class="btn btn-info btn-sm" type="button" onclick="savedraft();">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save Draft
                                            </button>    
                                        </div>
                                    </div>
                                </div>
                            </form>
                       
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->


        <?php

                function set_value(){
                  
                    
                }
                        ?>
        <script>
        
		
            function getLogos(listBy, orderBy, searchText) {
                if (listBy == 1 && orderBy == 1) {
                    document.getElementById('searchLogo').value = '';
                    document.getElementById("listAlpha").setAttribute("onclick", "getLogos('1','2','')");
                    document.getElementById("listAlpha").setAttribute("class", "activeLogoLink");
                    document.getElementById("listNewest").setAttribute("class", "inActiveLogoLink");
                } else if (listBy == 1 && orderBy == 2) {
                    document.getElementById('searchLogo').value = '';
                    document.getElementById("listAlpha").setAttribute("onclick", "getLogos('1','1','')");
                    document.getElementById("listAlpha").setAttribute("class", "activeLogoLink");
                    document.getElementById("listNewest").setAttribute("class", "inActiveLogoLink");
                } else if (listBy == 2 && orderBy == 1) {
                    document.getElementById('searchLogo').value = '';
                    document.getElementById("listNewest").setAttribute("onclick", "getLogos('2','2','')");
                    document.getElementById("listAlpha").setAttribute("class", "inActiveLogoLink");
                    document.getElementById("listNewest").setAttribute("class", "activeLogoLink");
                } else if (listBy == 2 && orderBy == 2) {
                    document.getElementById('searchLogo').value = '';
                    document.getElementById("listNewest").setAttribute("onclick", "getLogos('2','1','')");
                    document.getElementById("listAlpha").setAttribute("class", "inActiveLogoLink");
                    document.getElementById("listNewest").setAttribute("class", "activeLogoLink");
                } else if (listBy == 3) {
                    document.getElementById("listAlpha").setAttribute("onclick", "getLogos('1','1','')");
                    document.getElementById("listNewest").setAttribute("onclick", "getLogos('2','1','')");
                    document.getElementById("listAlpha").setAttribute("class", "inActiveLogoLink");
                    document.getElementById("listNewest").setAttribute("class", "inActiveLogoLink");
                }
                $.ajax({
                    url: "add_track?listBy=" + listBy + "&orderBy=" + orderBy + "&searchText=" + searchText,
                    success: function(result) {
                        document.getElementById('logos').innerHTML = result;
                    }
                });
            }

            function change_genre(genreId) {
                $.ajax({
                    url: "add_track?getSubGenres=1&genreId=" + genreId,
                    success: function(result) {
                        var obj = JSON.parse(result);
                        var count = obj.length;
                        var liList = '';
                        var optionList = ''; //'<option value="">What country do you live in</option>';
                        for (var i = 0; i < count; i++) {
                            //		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
                            optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                        }
                        //	 document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
                        document.getElementById('subGenre').innerHTML = optionList;
                    }
                });
            }

            function removeList1() {
                document.getElementById('searchListDisplay1').style.display = 'none';
            }

            function showList1() {
                document.getElementById('searchListDisplay1').style.display = 'block';
            }

            function selectItem1(id, title) {
                document.getElementById('client').value = id;
                document.getElementById('clientSearch').value = title;
                document.getElementById('searchListDisplay1').style.display = 'none';
            }

            function getList1(searchKey) {
                var output = '';
                $.ajax({
                    url: "add_track?searchKey=" + searchKey + "&clientSearch=1",
                    success: function(result) {
                        var json_obj = $.parseJSON(result);
                        for (var i in json_obj) {
                            var abc = "'" + json_obj[i].id + "','" + json_obj[i].name + "'";
                            output += '<li><a href="javascript:void()" onclick="selectItem1(' + abc + ')">' + json_obj[i].name + '</a></li>';
                        }
                        document.getElementById('searchList1').innerHTML = output;
                        document.getElementById('searchListDisplay1').style.display = 'block';
                    }
                });
            }

            function validate() {
                //	alert("asd");
                var client = document.getElementById('client');
                var company = document.getElementById('company');
                var linkk = document.getElementById('link');
                var moreInfo = document.getElementById('moreInfo');
                var emailImage = document.getElementById('emailImage');
                var pageImage = document.getElementById('pageImage');
                var artist = document.getElementById('artist');
                var title = document.getElementById('title');
                var album = document.getElementById('album');
                var time = document.getElementById('time');
                var link1 = document.getElementById('link1');
                var producers = document.getElementById('producers');
				var writer = document.getElementById('writer');
				var status = document.getElementById('status');
				// if ($('#producers').val() == '') {
    //                 alert("Please enter producer(s) name!");
    //                 producers.focus();
    //                 return false;
    //             }
				// if ($('#writer').val() == '') {
    //                 alert("Please enter writer name!");
    //                 writer.focus();
    //                 return false;
    //             }
				if ($.trim($('#artist').val()) == '') {
                    alert("Please enter artist name!");
                    artist.focus();
                    return false;
                }
                var title = document.getElementById('title');
                if ($.trim($('#title').val()) == '') {
                    alert("Please enter title!");
                    title.focus();
                    return false;
                }
 
              
                if ($.trim($('#time').val()) == '') {
                    alert("Please enter track time!");
                    time.focus();
                    return false;
                }
                
                 
                 if ($('#status').val() =='') {
                    alert("Please select track status!");
                    status.focus();
                    return false;
                }
			/*	var numericExp = /^[-+]?[0-9]+$/;
                var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (client.value.length < 1) {
                    alert("Please select client!");
                    client.focus();
                    return false;
                } */

                // if(company.value.length<1)
                // {
                //     alert("Please enter company!");
                //     company.focus();
                //     return false;
                // }

                // if(linkk.value.length<1)
                // {
                //     alert("Please enter link!");
                //     linkk.focus();
                //     return false;
                // }

                // var n = linkk.value.indexOf(".");
                // if(n<1)
                // {
                //     alert("Please enter link!");
                //     linkk.focus();
                //     return false;
                // }


                // if(moreInfo.value.length<1)
                // {
                //     alert("Please enter more info.!");
                //     moreInfo.focus();
                //     return false;
                // }

                if (artist.value.length < 1) {
                    alert("Please enter artist name!");
                    artist.focus();
                    return false;
                }
                if (title.value.length < 1) {
                    alert("Please enter title!");
                    title.focus();
                    return false;
                }
                if (time.value.length < 1) {
                    alert("Please enter time!");
                    time.focus();
                    return false;
                }
               
                
                

                // if(album.value.length<1)
                // {
                //   alert("Please enter album!");
                //   album.focus();
                //   return false;
                // }

                // if(time.value.length<1)
                // {
                //   alert("Please enter time!");
                //   time.focus();
                //   return false;
                // }

                // if(link1.value.length<1)
                // {
                //   alert("Please enter link!");
                //   link1.focus();
                //   return false;
                // }

                // var n = link1.value.indexOf(".");
                // if(n<1)
                // {
                //   alert("Please enter link!");
                //   link1.focus();
                //   return false;
                // }

                // if(producers.value.length<1)
                // {
                //   alert("Please enter producers!");
                //   producers.focus();
                //   return false;
                // }
            }

            function removeAudio() {
                var divId = document.getElementById('divId').value;
                if (divId > 1) {
                    var divIdMinus = parseInt(divId) - 1;
                    document.getElementById('divId').value = divIdMinus;
                    $("#html" + divId).remove();
                }
            }

            function moreAudio() {
                // var genresData = document.getElementById("genresData").innerHTML;
                // var obj = JSON.parse(genresData);
                // var count = Object.keys(obj).length;
                // alert(obj.length);
                // alert(obj.1);
                var divId = document.getElementById('divId').value;
                var divIdPlus = parseInt(divId) + 1;
                document.getElementById('divId').value = divIdPlus;
                var parentDiv = document.createElement("div");
                parentDiv.setAttribute('id', 'html' + divIdPlus);
                var smDiv1 = document.createElement("div");
                smDiv1.setAttribute('class', 'col-sm-4 form_group');
                var smDiv2 = document.createElement("div");
                smDiv2.setAttribute('class', 'col-sm-9');
                var label1 = document.createElement("label");
                label1.setAttribute('class', 'control-label no-padding-right');
                var textnode1 = document.createTextNode("Version");
                label1.appendChild(textnode1);
                var input1 = document.createElement("select");
                input1.setAttribute('name', 'version' + divIdPlus);
                input1.setAttribute('id', 'version' + divIdPlus);
                input1.setAttribute('class', 'form-control version');
                var option1 = document.createElement("option");
                option1.setAttribute('value', '');
                option1.text = "Version";
                input1.add(option1);
                /*for(var i=0;i<count;i++)
                 {

                  var option2 = document.createElement("option");
                	option2.setAttribute('value',obj[i]);
                	option2.text = obj[i];
                	input1.add(option2);

                 }*/
                // input1.add(genresArr[0]);
                var option2 = document.createElement("option");
                option2.setAttribute('value', 'Acapella');
                option2.text = "Acapella";
                input1.add(option2);
                var option3 = document.createElement("option");
                option3.setAttribute('value', 'Clean');
                option3.text = "Clean";
                input1.add(option3);
                var option4 = document.createElement("option");
                option4.setAttribute('value', 'Clean Accapella');
                option4.text = "Clean Accapella";
                input1.add(option4);
                var option5 = document.createElement("option");
                option5.setAttribute('value', 'Clean (16 Bar Intro)');
                option5.text = "Clean (16 Bar Intro)";
                input1.add(option5);
                var option6 = document.createElement("option");
                option6.setAttribute('value', 'Dirty');
                option6.text = "Dirty";
                input1.add(option6);
                var option7 = document.createElement("option");
                option7.setAttribute('value', 'Dirty Accapella');
                option7.text = "Dirty Accapella";
                input1.add(option7);
                var option8 = document.createElement("option");
                option8.setAttribute('value', 'Dirty (16 Bar Intro)');
                option8.text = "Dirty (16 Bar Intro)";
                input1.add(option8);
                var option9 = document.createElement("option");
                option9.setAttribute('value', 'Instrumental');
                option9.text = "Instrumental";
                input1.add(option9);
                var option10 = document.createElement("option");
                option10.setAttribute('value', 'Main');
                option10.text = "Main";
                input1.add(option10);
                var option11 = document.createElement("option");
                option11.setAttribute('value', 'TV Track');
                option11.text = "TV Track";
                input1.add(option11);
                smDiv2.appendChild(input1);
                smDiv1.appendChild(label1);
                smDiv1.appendChild(smDiv2);
                var otherDiv = document.createElement("div");
                otherDiv.setAttribute('class', 'col-sm-4 form_group');
                var otherDiv1 = document.createElement("div");
                otherDiv1.setAttribute('class', 'col-sm-9');
                var otherLabel = document.createElement("label");
                otherLabel.setAttribute('class', 'control-label no-padding-right');
                var otherTextnode = document.createTextNode("Other Version");
                otherLabel.appendChild(otherTextnode);
                var otherInput = document.createElement("input");
                otherInput.setAttribute('type', 'text');
                otherInput.setAttribute('name', 'otherVersion' + divIdPlus);
                otherInput.setAttribute('id', 'otherVersion' + divIdPlus);
                otherInput.setAttribute('class', 'col-xs-10 col-sm-10');
                otherDiv1.appendChild(otherInput);
                otherDiv.appendChild(otherLabel);
                otherDiv.appendChild(otherDiv1);
                var smDiv3 = document.createElement("div");
                smDiv3.setAttribute('class', 'col-sm-4 form_group');
                var smDiv4 = document.createElement("div");
                smDiv4.setAttribute('class', 'col-sm-9');
                var label2 = document.createElement("label");
                label2.setAttribute('class', 'control-label no-padding-right');
                var textnode2 = document.createTextNode("File");
                label2.appendChild(textnode2);
                var input2 = document.createElement("input");
                input2.setAttribute('type', 'file');
                input2.setAttribute('name', 'audio' + divIdPlus);
                input2.setAttribute('id', 'audio' + divIdPlus);
                input2.setAttribute('class', 'col-xs-10 col-sm-10');
                smDiv4.appendChild(input2);
                smDiv3.appendChild(label2);
                smDiv3.appendChild(smDiv4);
                parentDiv.appendChild(smDiv1);
                parentDiv.appendChild(otherDiv);
                parentDiv.appendChild(smDiv3);
                var clearboth = document.createElement("div");
                clearboth.setAttribute('class', 'clearDiv');
                document.getElementById('audioFiles').appendChild(clearboth);
                document.getElementById('audioFiles').appendChild(parentDiv);
            }
			
			function deleteEmailImage(trackId, $this) {
                $.ajax({
                     url: "track_edit?deleteEmailImage=1&tid=" + trackId,
                     type: 'POST',
                     success: function(result) {
                        $($this).siblings('img').remove();
                        $($this).remove();
                     }
                 });
                 return false;
             }
			 
			  function deletePageImage(trackId, $this) {
                 $.ajax({
                     url: "track_edit?deletePageImage=1&tid=" + trackId,
                     type: 'POST',
                     success: function(result) {
                        $($this).siblings('img').remove();
                        $($this).remove();
                     }
                 });
                 return false;
             }
			 
			 function savedraft(){
			    var client = document.getElementById('client');
                var company = document.getElementById('company');
                var linkk = document.getElementById('link');
                var moreInfo = document.getElementById('moreInfo');
                var emailImage = document.getElementById('emailImage');
                var pageImage = document.getElementById('pageImage');
                var artist = document.getElementById('artist');
                var title = document.getElementById('title');
                var album = document.getElementById('album');
                var time = document.getElementById('time');
                var link1 = document.getElementById('link1');
                var producers = document.getElementById('producers');
				var writer = document.getElementById('writer');
				var status = document.getElementById('status');
				// if ($('#producers').val() == '') {
    //                 alert("Please enter producer(s) name!");
    //                 producers.focus();
    //                 return false;
    //             }
				// if ($('#writer').val() == '') {
    //                 alert("Please enter writer name!");
    //                 writer.focus();
    //                 return false;
    //             }
				if ($.trim($('#artist').val()) == '') {
                    alert("Please enter artist name!");
                    artist.focus();
                    return false;
                }
                var title = document.getElementById('title');
                if ($.trim($('#title').val()) == '') {
                    alert("Please enter title!");
                    title.focus();
                    return false;
                }
 
              
                if ($.trim($('#time').val()) == '') {
                    alert("Please enter track time!");
                    time.focus();
                    return false;
                }
                
                 
			     $('#status').val('draft');
			    
			     $('#submitbutton').trigger('click');
			     
			 }
			
        </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
<script>
/* const el = document.querySelector("#contact_phone");

const littleClean = input => input.replace(/(\d)\D+|^[^\d+]/g, "$1")
                                  .slice(0, 12);
const bigClean = input => !input ? ""
                                 : input.replace(/^\+(27)?/, "")
                                        .padStart(10, "0")
                                        .slice(0, 10);
const format = clean => {
    const [i, j] = [el.selectionStart, el.selectionEnd].map(i => 
        clean(el.value.slice(0, i)).length
    );
    el.value = clean(el.value);
    el.setSelectionRange(i, j);
};
el.addEventListener("input", () => format(littleClean));
el.addEventListener("focus", () => format(bigClean));
el.addEventListener("blur", () => format(bigClean)); */

  $("#contact_phone").keypress(function(e){
    var keyCode = e.which;
    var currLen = $(this).val().trim().length;
    console.log(keyCode);
    /* 
    48-57 - (0-9)Numbers
    65-90 - (A-Z)
    97-122 - (a-z)
    8 - (backspace)
    32 - (space)
    */
    if(currLen > 16){
      return false;  
    }
    // Not allow special 
    if ( (keyCode != 8 ) && ((keyCode < 48 && keyCode != 45 && keyCode != 46) || keyCode > 57) && keyCode != 32 && keyCode != 40 && keyCode != 41 && keyCode != 190 && keyCode != 189) { 
      return false;
    }
  });
  
  $("#contact_phone").keyup(function(e){
    var currVal = $(this).val().trim().length;  
  });
  
	window.onload = function() {		
		change_genre(<?php if(!empty($trackData->genreId)) echo $trackData->genreId ?>);
		setTimeout(function(){ $('#subGenre').val('<?php if(!empty($trackData->subGenreId)) echo $trackData->subGenreId;?>')}, 700);
		
	};
	$(document).on('click','.close', function() {
		$(this).parent().hide();
	});
	$(document).on('focusout','.artist_title', function() {
		var trkTitle = $('#title').val();
		var artistName = $('#artist').val();
		
		
		var dataPost = {		   
		   "songArtist": artistName,
		   "trackTitle": trkTitle
		};
		var dataString = JSON.stringify(dataPost);
		
		if(trkTitle.length==0 || artistName.length==0 ){
		    return false;
		}
		
		$.ajax({
		   url: 'checkClientTrackExists',
		   data: {myData: dataString, _token: '{{csrf_token()}}'},
		   type: 'POST',
		   success: function(response) {
			   var returnedData = JSON.parse(response);
				//console.log(returnedData.data[0].id);			   
			  if(returnedData.status == 'exists'){
				  //console.log(returnedData);				  
				  var confDelete = confirm('Track Already Exists. Do you want to delete it?');
				  if(confDelete){
					/* for(let ctr=0; ctr < returnedData.numRows; ctr++){ */
						//console.log(confDelete);
						$.ajax({
						   url: 'tracks',
						   data: {delTrackId: returnedData.data[0].id, _token: '{{csrf_token()}}'},
						   type: 'POST',
						   success: function(response) {
							   var resData = JSON.parse(response);
							   if(resData.status == 'success'){
									$('.show-error').text(' Track deleted successfully.');
									  setTimeout(function() { 
											$('#track-exist-check').hide();
											$('#add_trackform').trigger("reset");
											$('#artist').focus();
									 }, 3000);  
									 $('#track-exist-check').show();
							   }
						   }
						});
					//}
				  }else{
					  console.log('No');
				  }
			  }
		   }
		});
		
	});
	$("#add_trackform").validate();
	$("#artist").rules("add", {
      required:true,
   	  messages: {
        required: "Please enter artist name!"
      }
    }); // [0-9]{1}:[0-9]{2}:[0-9]{2}
    
   var $theForm = $("#add_trackform");
   $theForm.submit(function () {
    if($theForm.valid()) {
        //console.log('addTrack_Validated');
        $('#submitbutton').attr('disabled','disabled');
        $('.processing_loader_gif').show();     

    }else{
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return false;        
    }
    
   });    
	</script>
    
   @endsection  