

@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
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
         <a href="<?php echo url("admin/albums"); ?>">
         <i class="ace-icon fa fa-list list-icon"></i>
         Albums</a>
      </li>
      <li class="active">Edit Album</li>
   </ul>
   <!-- /.breadcrumb -->
   <!-- /section:basics/content.searchbox -->
</div>
<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
   <div class="row">
      <div class="col-xs-12">
         <!-- PAGE CONTENT BEGINS -->
               <?php
                  if (isset($alert_message)) {
                  ?>
               <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
               </div>
               <?php
                  }
                  ?>
               <?php
                  if(isset($tracks['data'][0])){
                    $track = $tracks['data'][0];
                  }else{
                    $track = '';
                  }
                  
                  ?>
               <form id="formAddAlbum" name="formAddAlbum" role="form" action="" method="post" enctype="multipart/form-data" style="color:white;">
                  @csrf
                  <div class="row">
                    <div class="col-xs-12">
                         <h3 class="header smaller lighter">
                            Album Information
                         </h3>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Type</label>
                           <input type="text" class="form-control" value="track" readonly>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Client </label>
                           <?php // foreach($clients as $client) {  echo $client->id;  } 
                              ?>
                           <select id="client" name="client" class="form-control">
                              <?php foreach ($clients as $client) { ?>
                              <option <?php if (isset($track->client) && ($client->id == $track->client)) { ?> selected="selected" <?php } ?> value="<?php echo $client->id;  ?>">
                                 <?php echo urldecode($client->name); ?>
                              </option>
                              <?php } ?>
                           </select>
                           <!-- <input type="text" id="client" name="client" />
                              <input type="text" id="clientSearch" onkeyup="getList1(this.value)" class="form-control" onfocus="getList1(this.value)" onMouseOver="showList1()" onMouseOut="removeList1()" />
                              <br />
                              
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
                           <label class="control-label" for="form-field-1"> Label / Company </label>
                           <input type="text" id="company" placeholder="Label / Company" name="company" class="form-control" value="<?php if(isset($track->label)) { echo urldecode($track->label); } ?>">
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Producer(s) </label>
                           <input type="text" id="producers" name="producers" class="form-control" value="<?php if(isset($track->producer)) { echo urldecode($track->producer); } ?>">
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1">Album Type </label>
                           <select required name="albumType" id="albumType" class="form-control">
                              <option value="">Album Type</option>
                              <option <?php if (isset($track->albumType) && $track->albumType == 1) { ?> selected <?php } ?> value="1">Single</option>
                              <option <?php if (isset($track->albumType) && $track->albumType == 2) { ?> selected <?php } ?> value="2">Album</option>
                              <option <?php if (isset($track->albumType) && $track->albumType == 3) { ?> selected <?php } ?> value="3">EP</option>
                              <option <?php if (isset($track->albumType) && $track->albumType == 4) { ?> selected <?php } ?> value="4">Mixtape</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Writer </label>
                           <input type="text" id="writer" name="writer" class="form-control" value="<?php if(isset($track->writer)){ echo urldecode($track->writer); } ?>">
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1">Album </label>
                           <input required type="text" id="album" name="album" class="form-control" value="<?php if(isset($track->album)){ echo urldecode($track->album); } ?>">
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">BPM </label>
                           <input type="text" id="bpm" name="bpm" class="form-control" value="<?php if(isset($track->bpm)){ echo urldecode($track->bpm); }?>">
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Time </label>
                           <input placeholder="MM:SS" type="text" id="time" name="time" class="form-control" value="<?php if(isset($track->time)){ echo urldecode($track->time); } ?>" pattern="[0-9]{2}:[0-9]{2}" />
							
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Genre </label>
                           <select required name="genre" id="genre" class="form-control" onchange="change_genre(this.value)">
                              <option value="">Genre</option>
                              <?php if ($genres['numRows'] > 0) {
                                 foreach ($genres['data'] as $genre) { ?>
                              <option <?php if (isset($tracks['data'][0]->genreId) && $tracks['data'][0]->genreId == $genre->genreId) { ?> selected <?php } ?> value="<?php echo $genre->genreId; ?>">
                                 <?php echo $genre->genre; ?>
                              </option>
                              <?php }
                                 } ?>
                           </select>
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Sub Genre </label>
                           <select name="subGenre" id="subGenre" class="form-control">
                              <option value="">Sub Genre</option>
                              <?php if(!empty($subGenres['numRows'])) {
                                 if (isset($subGenres) && $subGenres['numRows'] > 0) {
                                     foreach ($subGenres['data'] as $genre) { ?>
                              <option <?php if (isset($tracks['data'][0]->subGenreId) && $tracks['data'][0]->subGenreId == $genre->subGenreId) { ?> selected <?php } ?> value="<?php echo $genre->subGenreId; ?>">
                                 <?php echo $genre->subGenre; ?>
                              </option>
                              <?php }
                                 }
                                 } ?>
                           </select>
                        </div>
                     </div>
                     <!--<div class="col-sm-6">
                        <label class="control-label" for="form-field-1">Release Date </label>
                        <div class="col-sm-9">
                            <input type="text" id="releaseDate" name="releaseDate" class="form-control">
                        </div>
                        </div>
                        -->
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> More Info. </label>
                           <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control"><?php if(!empty($track->moreinfo)) echo urldecode($track->moreinfo); ?></textarea>
                        </div>
                     </div>
                     
                     <!--<div class="col-sm-6">-->
                     <!--   <div class="form-group">-->
                     <!--      <label class="control-label" for="form-field-1"> E-mail Image </label>-->
                     <!--      <input type="file" id="emailImage" name="emailImage" class="form-control form-file" accept="image/png, image/gif, image/jpeg">-->
                     <!--      <?php if(!empty($track->img)) if (strlen($track->img) > 4) { ?>-->
                     <!--      <img src="{{ asset('ImagesUp/' .$track->img)}}" width="50" height="50" />-->
                     <!--      <?php } ?>-->
                     <!--   </div>-->
                     <!--</div>-->
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Page Image (Artwork)</label>
                           <input type="file" id="pageImage" name="pageImage" class="form-control form-file" accept="image/png, image/gif, image/jpeg">
                           <?php 
                           if(!empty($track->pCloudFileID)){
                               $img=url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);?>
                                  <img src="{{ $img }}" width="50" height="50" />
                         <?php  }
                           else if(!empty($track->imgpage))
                              if (strlen($track->imgpage) > 4) { ?>
                           <img src="{{ asset('ImagesUp/' .$track->imgpage)}}" width="50" height="50" />
                           <?php } ?>
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Available to Members </label>
                           <div class="radio">
                              <label>
                              <input name="availableMembers" type="radio" class="ace" value="1" <?php if (isset($track->active) && $track->active == 1) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="availableMembers" type="radio" class="ace" value="0" <?php if (isset($track->active) && $track->active == 0) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Reviewable </label>
                           <div class="radio">
                              <label>
                              <input name="reviewable" type="radio" class="ace" value="1" <?php if (isset($track->review) && $track->review == 1) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="reviewable" type="radio" class="ace" value="0" <?php if (isset($track->review) && $track->review == 0) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <div class="checkbox">
                              <label>
                              <input name="graphics" class="ace ace-checkbox-2" type="checkbox" value="1" <?php if (isset($track->graphicscomplete) && $track->graphicscomplete == 1) { ?> checked="checked" <?php } ?>>
                              <span class="lbl"></span> Graphics Complete
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                          
                           <div class="checkbox">
                              <label>
                              <input type="checkbox" id="whiteLabel" name="whiteLabel" value="1" class="ace ace-checkbox-2" <?php if (isset($track->whitelabel) && $track->whitelabel == 1) { ?> checked="checked" <?php } ?>>
                              <span class="lbl"></span> White Label Artwork 
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="space-24"></div>
                     <div class="col-xs-12"><h3 class="header smaller lighter">Logos</h3></div>
                     <div class="col-sm-6">
                       <?php if(isset($logos['data']) && isset($track->logos)){ ?> 
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">Select Logo</label>
                           
                              <?php
                                 //$logoIds = array();
                                 if (strpos($track->logos, ',') >= 0) {
                                     $logoIds = explode(',', $track->logos);
                                 } else {
                                     $logoIds[0] = $track->logos;
                                 }
                                 ?>
                              <select name="logos[]" size="5" multiple="" class="form-control" id="logos[]">
                                 <?php foreach ($logos['data'] as $logoo) { ?>
                                 <option <?php if (in_array($logoo->id, $logoIds)) { ?> selected="selected" <?php } ?> value="<?php echo $logoo->id;  ?>">
                                    <?php echo urldecode($logoo->company); ?>
                                 </option>
                                 <?php } ?>
                              </select>
                           
                        </div>
						<?php } ?>
                     </div>
                     <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="form-field-1">Add New</label>
                                <input type="text" id="logoCompany" name="logoCompany" class="form-control" placeholder="Company">
                           </div>
                            <div class="form-group">
                                <input type="text" id="logoLink" name="logoLink" class="form-control" placeholder="Link">
                           </div>
                            <div class="form-group">
                                <label class="control-label" for="form-field-1">Logo </label>
                                <input type="file" id="logoImage" name="logoImage" class="form-control form-file" accept="image/png, image/gif, image/jpeg">
                           </div>
                        
                     </div>
                     <div class="space-24"></div>
                     <div class="col-xs-12"><h3 class="header smaller lighter">Artist Information</h3></div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1">Artist </label>                                        
                           <input type="text" id="artist" name="artist" class="form-control" value="<?php if(isset($track->artist)){ echo urldecode($track->artist); } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label class="control-label" for="form-field-1">Video Link </label>
                        <div class="form-group">
                           <input type="text" id="link1" name="video" class="form-control" value="<?php if(isset($track->videoURL)){ echo $track->videoURL; } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label class="control-label" for="form-field-1"> Website: </label>
                        <div class="form-group">
                           <input type="text" id="website" placeholder="Website" name="website" class="form-control" value="<?php if(isset($track->link)){ echo urldecode($track->link); } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">Website 1 </label>                                        
                           <input type="text" id="website1" name="website1" class="form-control" value="<?php if(isset($track->link1)){ echo $track->link1; } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">Website 2 </label>                                        
                           <input type="text" id="website2" name="website2" class="form-control" value="<?php if(isset($track->link2)){ echo $track->link2; } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">Facebook </label>                                        
                           <input type="text" id="facebookLink" name="facebookLink" class="form-control" value="<?php if(isset($track->facebookLink)){ echo $track->facebookLink; } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">Twitter </label>                                        
                           <input type="text" id="twitterLink" name="twitterLink" class="form-control" value="<?php if(isset($track->twitterLink)){ echo $track->twitterLink; } ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">Instagram </label>                                        
                           <input type="text" id="instagramLink" name="instagramLink" class="form-control" value="<?php if(isset($track->instagramLink)){ echo $track->instagramLink; } ?>" />
                        </div>
                     </div>
                     <div class="space-24"></div>
                     <div class="col-xs-12"><h3 class="header smaller lighter">Audio Files</h3></div>
                     <div class="col-xs-12">
                     <table id="simple-table" class="table table-bordered table-striped table-hover">
                        <thead>
                           <tr>
                              <th class="center">S. No</th>
                              <th colspan="3">Track</th>
                              <th class="text-center">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              foreach ($tracks['data'] as $i => $trackAudio) {
                                  // if (!empty($trackAudio->audios['data'])) {
                                      $audioCount = count($trackAudio->audios['data']) + 1;
                              ?>
                           <tr main>
                              <td rowspan="<?php echo $audioCount; ?>" class="text-center">
                                 <?php echo $i + 1; ?>
                              </td>
                              <td colspan="3"><?php echo urldecode($trackAudio->title); ?></td>
                              <td rowspan="<?php echo $audioCount; ?>" class="text-center">
                                 <button title="Delete Track" type="button" class="mt-1 btn btn-xs btn-danger delete_track_edit" id="delete_track_edit" value="<?php echo $trackAudio->id; ?>"><i class="ace-icon fa fa-trash-o bigger-120"></i> Delete Track</button>
                                 <button title="Add New Track" type="button" class="mt-1 btn btn-xs btn-primary add_track_version" id="add_track_version" value="<?php echo $trackAudio->id; ?>"><i class="ace-icon fa fa-plus bigger-120"></i> Add Version</button>
                              </td>
                           </tr>
                           <?php
                              foreach ($trackAudio->audios['data'] as $audio) {
								//if (file_exists(base_path('AUDIO/' . $audio->location))){
                              ?>
                           <tr>
                              <td width="25%" class="left">
                                 <?php echo empty($audio->version) ? 'Original' : urldecode($audio->version); ?>
                              </td>
                              <td>
                                 <audio controls="">
                                    <?php if (strpos($audio->location, '.mp3') !== false) { ?>
                                    <source src="<?php echo url('AUDIO/' . $audio->location); ?>" type="audio/mp3"></source>
                                    <?php } else { $fileid = (int)$audio->location; $getlink = ''; 
                                       if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                    <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                    <?php } ?>
                                    Your browser does not support the audio element.
                                 </audio>
                              </td>
                              <td style="vertical-align: middle;">
                                 <button title="Delete Version" type="button" class="btn btn-xs btn-danger delete_track_version_edit" id="delete_track_version_edit" value="<?php echo $audio->id; ?>"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                              </td>
                           </tr>
                           <?php
							 // }
                              }
                              }
                              // }
                              ?>
                        </tbody>
                     </table>
                     </div>
                        <div class="col-sm-12" id="audioFiles">
                           <div id="trackContainer1" class="panel panel-default" style="color:black;">
                              <div class="panel-body">
                                 <div class="col-sm-12 versionDiv">
                                    <div class="form-group">
                                       <label for="form-field-1 col-sm-2 form-group req-label" style="display: block;"> Title <span style="color: red">*</span></label>
                                       <input type="text" name="title[1]" class="col-sm-6">
                                    </div>
                                 </div>
                                 <div class="col-sm-12 trackVersions">
                                    <div class="row">
                                       <div class="col-sm-3 versionDiv">
                                          <div class="form-group">
                                             <label for="form-field-1"> File <span style="color: red">*</span></label>
                                             <input type="file" name="audio[1][1]" class="form-control form-file" accept="audio/*">
                                          </div>
                                       </div>
                                       <div class="col-sm-3 versionDiv">
                                          <div class="form-group">
                                             <label for="form-field-1">Version </label>
                                             <select name="version[1][1]" class="form-control version">
                                                <option value="">Version</option>
                                                <option value="Acapella">Acapella</option>
                                                <option value="Clean">Clean</option>
                                                <option value="Clean Accapella">Clean Accapella</option>
                                                <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)
                                                </option>
                                                <option value="Dirty">Dirty</option>
                                                <option value="Dirty Accapella">Dirty Accapella</option>
                                                <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)
                                                </option>
                                                <option value="Instrumental">Instrumental</option>
                                                <option value="Main">Main</option>
                                                <option value="TV Track">TV Track</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-sm-3 versionDiv">
                                          <div class="form-group">
                                             <label for="form-field-1"> Other Version </label>
                                             <input type="text" name="otherVersion[1][1]" class="form-control">
                                          </div>
                                       </div>
                                       <div class="col-sm-3 versionDiv">
                                          <div class="form-group text-right">
                                             <a href="javascript:void(0);" style="margin-top:25px;" onclick="addTrackVersion(this, 1)" class="btn btn-success btn-sm">+ Add Version</a>
                                             <a href="javascript:void(0);" style="margin-top:25px;" onclick="removeTrackVersion(this, 1)" class="btn btn-danger btn-sm">- Remove Version</a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                    
                     <div class="col-xs-12 text-right">
                        <div class="form-group">
                           <a href="javascript:void(0);" onclick="addTrack()" class="btn btn-success btn-sm">+ Add Track</a>
                           <a href="javascript:void(0);" onclick="removeTrack()" class="btn btn-danger btn-sm">- Remove Track</a>
                        </div>
                     </div>
                     
                     <input type="hidden" id="aid" name="aid" value="<?php echo $_GET['aid']; ?>" />
                     <div class="col-xs-12">
                         <div class="form-actions text-right">
                               <button class="btn btn-info btn-sm" type="submit" name="updateAlbum">
                               <i class="ace-icon fa fa-check bigger-110"></i>
                               Update Album
                               </button>
                         </div>
                     </div>
                  </div>
               </form>
            </div>
            <!-- /.span -->
         </div>
         <!-- /.row -->
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</div>
<!-- /.page-content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
   function isValidURL(str) {
    var pattern = new RegExp('^((https?:)?\\/\\/)?'+ // protocol
        '(?:\\S+(?::\\S*)?@)?' + // authentication
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locater
    if (!pattern.test(str)) {
        return false;
    } else {
        return true;
    }
   }
            jQuery(document).ready(function($) {
   
                audioCount = <?php echo (int) count($tracks['data']); ?>;
   
                $('#formAddAlbum').submit(function() {
                    var client = document.getElementById('client');
                    var company = document.getElementById('company');
                    var linkk = document.getElementById('link');
                    var moreInfo = document.getElementById('moreInfo');
                    var emailImage = document.getElementById('emailImage');
                    var pageImage = document.getElementById('pageImage');
                    var artist = document.getElementById('artist');
                    var album = document.getElementById('album');
                    var time = document.getElementById('time');
                    var link1 = document.getElementById('link1');
        var website = document.getElementById('website');
        var website1 = document.getElementById('website1');
        var website2 = document.getElementById('website2');
        var facebook = document.getElementById('facebookLink');
        var twitter = document.getElementById('twitterLink');
        var instagram = document.getElementById('instagramLink');
                    var producers = document.getElementById('producers');
        var writer = document.getElementById('writer');
                    var numericExp = /^[-+]?[0-9]+$/;
                    var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                    if (client.value.length < 1) {
                        alert("Please select client!");
                        client.focus();
                        return false;
                    }
   
                    // if (pageImage.value.length < 1) {
                    //     alert("Please select Page Image!");
                    //     pageImage.focus();
                    //     return false;
                    // }
   
                    // if (emailImage.value.length < 1) {
                    //     alert("Please select Email Image!");
                    //     emailImage.focus();
                    //     return false;
                    // }
   
    //  if (producers.value.length < 1) {
    //      alert("Please enter producer(s) name!");
    //      producers.focus();
    //      return false;
    //  }
    //  if (writer.value.length < 1) {
    //      alert("Please enter write name!");
    //      writer.focus();
    //      return false;
    //  }
                    if (album.value.length < 1) {
                        alert("Please enter album!");
                        album.focus();
                        return false;
                    }
                    if (artist.value.length < 1) {
                        alert("Please enter artist name!");
                        artist.focus();
                        return false;
                    }
        if (link1.value.length >= 1) {
            if (!(isValidURL(link1.value))) {
                alert("Please enter a valid video link!");
                link1.focus();
                return false;
            }
        }
        if (website.value.length >= 1) {
            if (!(isValidURL(website.value))) {
                alert("Please enter a valid website url!");
                website.focus();
                return false;
            }
        }
        if (website1.value.length >= 1) {
            if (!(isValidURL(website1.value))) {
                alert("Please enter a valid website1 url!");
                website1.focus();
                return false;
            }
        }
        if (website2.value.length >= 1) {
            if (!(isValidURL(website2.value))) {
                alert("Please enter a valid website2 url!");
                website2.focus();
                return false;
            }
        }
        if (facebook.value.length >= 1) {
            if (!(isValidURL(facebook.value))) {
                alert("Please enter a valid facebook link!");
                facebook.focus();
                return false;
            }
        }
        if (twitter.value.length >= 1) {
            if (!(isValidURL(twitter.value))) {
                alert("Please enter a valid twitter link!");
                twitter.focus();
                return false;
            }
        }
        if (instagram.value.length >= 1) {
            if (!(isValidURL(instagram.value))) {
                alert("Please enter a valid instagram link!");
                instagram.focus();
                return false;
            }
        }
   
                    tracksTitleError = 0;
                    $('[name^="title"]').each(function() {
                        if(audioCount == 0) {
                            if(!$(this).val()) {
                                alert("Please enter title!");
                                $(this).focus();
                                tracksTitleError += 1;
                            }
                        } else {
                            if (!$(this).val() && $(this).closest('.panel-body').find('[name^="audio"]').val()) {
                                alert("Please enter title!");
                                $(this).focus();
                                tracksTitleError += 1;
                            }
                        }
                    });
                    if (tracksTitleError > 0) {
                        return false;
                    }
                    
                    tracksMp3Error = 0;
                    $('[name^="audio"]').each(function() {
                        if(audioCount == 0) {
                            if(!$(this).val()) {
                                alert("Please upload audio!");
                                $(this).focus();
                                tracksMp3Error += 1;
                            }
                        } else {
                            if (!$(this).val() && $(this).closest('.panel-body').find('[name^="title"]').val()) {
                                alert("Please upload audio!");
                                $(this).focus();
                                tracksMp3Error += 1;
                            }
                        }
                    });
                    if (tracksMp3Error > 0) {
                        return false;
                    }
                });
   
                change_genre = function(genreId) {
                    $.ajax({
                        url: "album_add?getSubGenres=1&genreId=" + genreId,
                        success: function(result) {
                            var obj = JSON.parse(result);
                            var count = obj.length;
                            var liList = '';
                            var optionList = ''; //'<option value="">What country do you live in</option>';
                            for (var i = 0; i < count; i++) {
                                //        liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
                                optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                            }
                            //   document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
                            document.getElementById('subGenre').innerHTML = optionList;
                        }
                    });
                }
   
                removeTrack = function() {
                    if (parseInt($('[name^="title"').length) > 1) {
                        $('#audioFiles').find('.panel').last().remove();
                    }
                }
   
                $('.add_track_version').click(function() {
                    $versionTemplate = '<tr>\
                                        <td colspan="2">\
                                            <div class="row-fluid">\
                                                <div class="col-sm-4 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1"> File </label>\
                                                        <input type="file" name="tvAudio[]" class="form-control form-file" accept="audio/*">\
                                                    </div>\
                                                </div>\
                                                <div class="col-sm-4 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1">Version </label>\
                                                        <select name="tvVersion[]" class="form-control version">\
                                                            <option value="">Version</option>\
                                                            <option value="Acapella">Acapella</option>\
                                                            <option value="Clean">Clean</option>\
                                                            <option value="Clean Accapella">Clean Accapella</option>\
                                                            <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)</option>\
                                                            <option value="Dirty">Dirty</option>\
                                                            <option value="Dirty Accapella">Dirty Accapella</option>\
                                                            <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)</option>\
                                                            <option value="Instrumental">Instrumental</option>\
                                                            <option value="Main">Main</option>\
                                                            <option value="TV Track">TV Track</option>\
                                                        </select>\
                                                    </div>\
                                                </div>\
                                                <div class="col-sm-4 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1"> Other Version </label>\
                                                        <input type="text" name="tvOtherVersion[]" class="form-control">\
                                                        <input type="hidden" name="tvTrackId[]" value="'+$(this).val()+'">\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </td>\
                                        <td style="vertical-align: middle;">\
                                            <button title="Delete Version" type="button" class="btn btn-xs btn-danger delete_track_version_edit" id="delete_track_version_edit"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>\
                                        </td>\
                                    </tr>';
   
                    $trContainer = $(this).closest('tr');
                    count = $trContainer.children('td').attr('rowspan');
                    if(parseInt(count) > 1) {
                        $trContainer.nextAll('tr').each(function(i) {
                            if(i == (count-2)) {
                                $trContainer.children('td[rowspan="'+count+'"]').attr('rowspan', parseInt(count) + 1);
                                $(this).after($versionTemplate);
                            }
                        });
                    } else if (count == 1) {
                        $trContainer.children('td[rowspan="'+count+'"]').attr('rowspan', parseInt(count) + 1);
                        $trContainer.after($versionTemplate);
                    }
                });
   
                addTrack = function() {
                    $trackTemplate = '<div class="panel panel-default">\
                                    <div class="panel-body">\
                                        <div class="col-sm-12 versionDiv">\
                                            <div class="form-group">\
                                                <label for="form-field-1 col-sm-2 form-group req-label" style="display: block;"> Title </label>\
                                                <input type="text" name="title" class="col-sm-6">\
                                            </div>\
                                        </div>\
                                        <div class="col-sm-12 trackVersions">\
                                            <div class="row">\
                                                <div class="col-sm-3 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1"> File </label>\
                                                        <input type="file" name="audio" class="form-control form-file" accept="audio/*">\
                                                    </div>\
                                                </div>\
                                                <div class="col-sm-3 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1">Version </label>\
                                                        <select name="version" class="form-control version">\
                                                            <option value="">Version</option>\
                                                            <option value="Acapella">Acapella</option>\
                                                            <option value="Clean">Clean</option>\
                                                            <option value="Clean Accapella">Clean Accapella</option>\
                                                            <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)</option>\
                                                            <option value="Dirty">Dirty</option>\
                                                            <option value="Dirty Accapella">Dirty Accapella</option>\
                                                            <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)</option>\
                                                            <option value="Instrumental">Instrumental</option>\
                                                            <option value="Main">Main</option>\
                                                            <option value="TV Track">TV Track</option>\
                                                        </select>\
                                                    </div>\
                                                </div>\
                                                <div class="col-sm-3 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1"> Other Version </label>\
                                                        <input type="text" name="otherVersion" class="form-control">\
                                                    </div>\
                                                </div>\
                                                <div class="col-sm-3 versionDiv">\
                                                    <div class="form-group text-right">\
                                                        <a href="javascript:void(0);" style="margin-top:25px;" onclick="addTrackVersion(this)" class="btn btn-success btn-sm">+ Add Version</a>\
                                                        <a href="javascript:void(0);" style="margin-top:25px;" onclick="removeTrackVersion(this)" class="btn btn-danger btn-sm">- Remove Version</a>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>';
   
                    $newDiv = $trackTemplate;
                    $incValue = parseInt($('[name^="title"').length) + 1;
                    $('#audioFiles').append($newDiv);
                    $('#audioFiles').last().find('[name="title"]').attr('name', 'title[' + $incValue + ']');
                    $('#audioFiles').last().find('[name="audio"]').attr('name', 'audio[' + $incValue + '][1]');
                    $('#audioFiles').last().find('[name="version"]').attr('name', 'version[' + $incValue + '][1]');
                    $('#audioFiles').last().find('[name="otherVersion"]').attr('name', 'otherVersion[' + $incValue + '][1]');
                    $('#audioFiles').last().find('[onclick="addTrackVersion(this)"]').attr('onclick', 'addTrackVersion(this, ' + $incValue + ')');
                    $('#audioFiles').last().find('[onclick="removeTrackVersion(this)"]').attr('onclick', 'removeTrackVersion(this, ' + $incValue + ')');
                }
   
                addTrackVersion = function($this, $trackIndex) {
                    $trackVersionTemplate = '<div class="row">\
                                        <div class="col-sm-3 versionDiv">\
                                            <div class="form-group">\
                                                <label for="form-field-1"> File </label>\
                                                <input type="file" name="audio" class="form-control form-file" accept="audio/*">\
                                            </div>\
                                        </div>\
                                        <div class="col-sm-3 versionDiv">\
                                            <div class="form-group">\
                                                <label for="form-field-1">Version </label>\
                                                <select name="version" class="form-control version">\
                                                    <option value="">Version</option>\
                                                    <option value="Acapella">Acapella</option>\
                                                    <option value="Clean">Clean</option>\
                                                    <option value="Clean Accapella">Clean Accapella</option>\
                                                    <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)</option>\
                                                    <option value="Dirty">Dirty</option>\
                                                    <option value="Dirty Accapella">Dirty Accapella</option>\
                                                    <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)</option>\
                                                    <option value="Instrumental">Instrumental</option>\
                                                    <option value="Main">Main</option>\
                                                    <option value="TV Track">TV Track</option>\
                                                </select>\
                                            </div>\
                                        </div>\
                                        <div class="col-sm-3 versionDiv">\
                                            <div class="form-group">\
                                                <label for="form-field-1"> Other Version </label>\
                                                <input type="text" name="otherVersion" class="form-control">\
                                            </div>\
                                        </div>\
                                    </div>';
                    $newDiv = $trackVersionTemplate;
                    $incValue = parseInt($('[name^="audio[' + $trackIndex + ']"]').length) + 1;
                    $($this).closest('.trackVersions').append($newDiv);
                    $($this).closest('.trackVersions').last().find('[name="audio"]').attr('name', 'audio[' + $trackIndex + '][' + $incValue + ']');
                    $($this).closest('.trackVersions').last().find('[name="version"]').attr('name', 'version[' + $trackIndex + '][' + $incValue + ']');
                    $($this).closest('.trackVersions').last().find('[name="otherVersion"]').attr('name', 'otherVersion[' + $trackIndex + '][' + $incValue + ']');
                }
   
                removeTrackVersion = function($this, $trackIndex) {
                    if ($('[name^="audio[' + $trackIndex + ']"]').length > 1)
                        $($this).closest('.trackVersions').find('.row').last().remove();
                }
   
                $(".delete_track_edit").on("click", function() {
				 if (confirm("Are you sure?") == true) {
                    $ref = $(this);
                    $.ajax({
                        type: 'GET',
                        url: window.location.href,
                        data: {
                            delTrackId: $(this).val()
                        },
                        dataType: "json",
                        success: function(data) {
                            if(parseInt(String(data).trim()) == 1) {
                                audioCount = audioCount - 1;
                                $($ref).closest('tr').next('tr').remove();
                                $($ref).closest('tr').remove();
                            }
   
                            alert('Track Deleted');
                            var get_url = window.location.href='{{ route('admin_albums_listing') }}'
                            window.open(get_url, '_top');
                        }
                    });
				}
                });
                
                $(document).on('click', ".delete_track_version_edit", function() {
					if (confirm("Are you sure?") == true) {
							if($(this).val()) {
								$ref = $(this);
								$.ajax({
									type: 'GET',
									url: window.location.href,
									data: {
										delTrackVersionId: $(this).val()
									},
									dataType: "json",
									success: function(data) {
										if(parseInt(String(data).trim()) == 1) {
											count = $($ref).closest('tr').prevAll('tr[main]').first().find('td[rowspan]').attr('rowspan');
											if(parseInt(count) >= 0) {
												$($ref).closest('tr').prevAll('tr[main]').first().find('td[rowspan]').attr('rowspan', parseInt(count) - 1);
											}
											$($ref).closest('tr').remove();
										}
									}
								});
							} else {
								count = $(this).closest('tr').prevAll('tr[main]').first().find('td[rowspan]').attr('rowspan');
								if(parseInt(count) >= 0) {
									$(this).closest('tr').prevAll('tr[main]').first().find('td[rowspan]').attr('rowspan', parseInt(count) - 1);
								}
								$(this).closest('tr').remove();
							}
					}
                });
            });
        
</script>
@endsection

