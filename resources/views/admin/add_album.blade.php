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
                    <a href="{{ route('admin_albums_listing') }}">
                        <i class="ace-icon fa fa-list list-icon"></i>
                        Albums</a>
                </li>
                <li class="active">Add Album</li>
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

                    <!-- PAGE CONTENT BEGINS -->
              
                            <?php
                            if (isset($alert_message)) {
                            ?>
                                <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
                            <?php
                            }
                            ?>
                            <?php $track = $tracks['data'][0]; ?>
                            <form id="formAddAlbum" name="formAddAlbum"  role="form" action="" method="post" enctype="multipart/form-data" style="color:white;">
                            @csrf
                             <h3 class="header smaller lighter">Album Information</h3>
                                <div class="row">
                                   
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="form-field-1"> Type</label>
                                            <input type="text" class="form-control" value="track" readonly>
                                        </div>
                                     </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1"> Client </label>
                                        
                                            <?php // foreach($clients as $client) {  echo $client->id;  } 
                                            ?>
                                            <select id="client" name="client" class="form-control">
                                                <?php foreach ($clients as $client) { ?>
                                                    <option value="<?php echo $client->id;  ?>"><?php echo urldecode($client->name); ?></option>
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
                                        <label for="form-field-1"> Label / Company </label>
                                        
                                            <input type="text" id="company" placeholder="Label / Company" name="company" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="form-field-1"> Producer(s) </label>
                                        
                                            <input type="text" id="producers" name="producers" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1">Album Type </label>
                                        
                                            <select required name="albumType" id="albumType" class="form-control">
                                                <option value="">Album Type</option>
                                                <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '1') == 0) { ?> selected <?php } ?> value="1">Single</option>
                                                <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '2') == 0) { ?> selected <?php } ?> value="2">Album</option>
                                                <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '3') == 0) { ?> selected <?php } ?> value="3">EP</option>
                                                <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '4') == 0) { ?> selected <?php } ?> value="4">Mixtape</option>
                                            </select>
                                        </div>
                                    </div>
									<div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="form-field-1"> Writer </label>
                                        
                                            <input type="text" id="writer" name="writer" class="form-control">
                                        </div>
                                    </div>
								
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1">Album </label>
                                        
                                            <input type="text" id="album" name="album" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="form-field-1">BPM </label>
                                        
                                            <input type="text" id="bpm" name="bpm" class="form-control">
                                        </div>
                                    </div>
								
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="form-field-1"> Time </label>
                                        
                                            <input placeholder="MM:SS" type="text" id="time" name="time" class="form-control" pattern="[0-9]{2}:[0-9]{2}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1"> Genre </label>
                                        
                                            <select required name="genre" id="genre" class="form-control" onchange="change_genre(this.value)">
                                                <option value="">Genre</option>
                                                <?php if ($genres['numRows'] > 0) {
                                                    foreach ($genres['data'] as $genre) { ?>
                                                        <option value="<?php echo $genre->genreId; ?>"><?php echo $genre->genre; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
								
									<div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="form-field-1"> Sub Genre </label>
                                        
                                            <select name="subGenre" id="subGenre" class="form-control">
                                                <option value="">Sub Genre</option>
                                                <?php if ($subGenres['numRows'] > 0) {
                                                    foreach ($subGenres['data'] as $genre) { ?>
                                                        <option value="<?php echo $genre->subGenreId; ?>"><?php echo $genre->subGenre; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                   
                                    <!--<div class="col-sm-6">
                                        <div class="form-group">
										<label for="form-field-1">Release Date </label>
										
											<input type="text" id="releaseDate" name="releaseDate" class="form-control">
										</div>
									</div>
									-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="form-field-1"> More Info. </label>
                                        
                                            <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="col-sm-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--    <label for="form-field-1"> E-mail Image </label>-->
                                        
                                    <!--        <input type="file" class="form-control form-file" id="emailImage" name="emailImage" accept="image/png, image/gif, image/jpeg" />-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1"> Page Image (Artwork)</label>
                                        
                                            <input type="file" class="form-control form-file" id="pageImage" name="pageImage" accept="image/png, image/gif, image/jpeg" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1"> Available to Members </label>
                                        
                                            <div class="radio">
                                                <label>
                                                    <input required name="availableMembers" type="radio" class="ace" value="1">
                                                    <span class="lbl"> Yes </span>
                                                </label>
                                                <label>
                                                    <input required name="availableMembers" type="radio" class="ace" value="0">
                                                    <span class="lbl"> No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="req-label" for="form-field-1"> Reviewable </label>
                                        
                                            <div class="radio">
                                                <label>
                                                    <input required name="reviewable" type="radio" class="ace" value="1">
                                                    <span class="lbl"> Yes </span>
                                                </label>
                                                <label>
                                                    <input required name="reviewable" type="radio" class="ace" value="0">
                                                    <span class="lbl"> No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="form-field-1"> Graphics Complete </label>
                                            <div class="checkbox">                                            
                                                <label>
                                                    <input name="graphics" class="ace ace-checkbox-2" type="checkbox" value="1">
                                                    <span class="lbl"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="form-field-1">White Label Artwork </label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="whiteLabel" name="whiteLabel" value="1" class="ace ace-checkbox-2">
                                                    <span class="lbl"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-24"></div>
                                      </div>
                                 
                                    <h3 class="header smaller lighter">Logos</h3>
                                    <div class="row">
                                    <div class="col-sm-6">
                                        <span style="color:#428bca;">Select
                                            Logo</span>
                                        <div class="form-group">
                                            <label for="form-field-1">
                                                Logos </label>
                                            
                                                <?php
                                                $logoIds = array();
                                                // if (strpos($track->logos, ',') >= 0) {
                                                //     $logoIds = explode(',', $track->logos);
                                                // } else {
                                                //     $logoIds[0] = $track->logos;
                                                // }
                                                ?>
                                                <select name="logos[]" size="5" multiple="" class="form-control" id="logos[]">
                                                    <?php foreach ($logos['data'] as $logoo) { ?>
                                                        <option <?php if (in_array($logoo->id, $logoIds)) { ?> selected="selected" <?php } ?> value="<?php echo $logoo->id;  ?>">
                                                            <?php echo urldecode($logoo->company); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                   </div>
                                    <span  style="color:#428bca;">Add New</span>
                                    <div class="row">
                                         <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Company </label>
                                            
                                                <input type="text" id="logoCompany" name="logoCompany" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Link </label>
                                            
                                                <input type="text" id="logoLink" name="logoLink" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="form-field-1">Logo </label>
                                            
                                                <input type="file" class="form-control form-file" id="logoImage" name="logoImage" accept="image/png, image/gif, image/jpeg" />
                                            </div>
                                        </div>
                                   </div>
                                    
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter">Artist Information</h3>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label class="req-label" for="form-field-1">Artist </label>
                                            
                                                <input type="text" id="artist" name="artist" class="form-control  nav-search-input ui-autocomplete-input" />
                                            </div>
                                        </div>
                                    
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Video
                                                Link </label>
                                            
                                                <input type="text" id="link1" name="video" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">
                                                Website: </label>
                                            
                                                <input type="text" id="website" placeholder="Website" name="website" class="form-control" />
                                            </div>
                                        </div>
                                    
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Website 1 </label>
                                            
                                                <input type="text" id="website1" name="website1" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Website 2 </label>
                                            
                                                <input type="text" id="website2" name="website2" class="form-control" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Facebook </label>
                                            
                                                <input type="text" id="facebookLink" name="facebookLink" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Twitter </label>
                                            
                                                <input type="text" id="twitterLink" name="twitterLink" class="form-control" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                            <label for="form-field-1">Instagram </label>
                                            
                                                <input type="text" id="instagramLink" name="instagramLink" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter">Audio Files</h3>
                                    <div class="row">
                                        <div class="col-sm-12" id="audioFiles">
                                            <div id="trackContainer1" class="panel panel-default" style="color:black;">
                                                <div class="panel-body">
                                                    <div class="col-sm-12 versionDiv">
                                                        <div class="form-group">
                                                            <label for="form-field-1 form-group req-label" style="display: block;"> Title <span style="color: red">*</span></label>
                                                            <input type="text" name="title[1]" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 trackVersions">
                                                        <div class="row">
                                                            <div class="col-sm-3 versionDiv">
                                                                <div class="form-group">
                                                                    <label for="form-field-1"> File <span style="color: red">*</span></label>
                                                                    <input type="file" name="audio[1][1]" class="form-control form-file" required>
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
                                    
                                    <div class="col-sm-12 text-right">
                                        <div class="form-group">
                                            <a href="javascript:void(0);" onclick="addTrack()" class="btn btn-success btn-sm">+ Add Track</a>
                                            <a href="javascript:void(0);" onclick="removeTrack()" class="btn btn-danger btn-sm">- Remove Track</a>
                                        </div>
                                    </div>
                                    </div>
                                    <input type="hidden" id="divId" name="divId" value="1" />
                                    <div class="clearfix form-actions">
                                        <div class="text-right">
                                            <button class="btn btn-info btn-sm" type="submit" id="submitbutton" name="saveAlbum">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save Album
                                            </button>
                                            &nbsp; 
                                            <button class="btn btn-sm btn-reset" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    
                    <!-- PAGE CONTENT ENDS -->
        </div><!-- /.page-content -->
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
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
				// 	if (producers.value.length < 1) {
				// 		alert("Please enter producer(s) name!");
				// 		producers.focus();
				// 		return false;
				// 	}
				// 	if (writer.value.length < 1) {
				// 		alert("Please enter writer name!");
				// 		writer.focus();
				// 		return false;
				// 	}
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
                        if (!$(this).val()) {
                            alert("Please enter title!");
                            $(this).focus();
                            tracksTitleError += 1;
                        }
                    });
                    if(tracksTitleError > 0) {
                        return false;
                    }
					
					

						tracksMp3Error = 0;
						$('[name^="audio"]').each(function() {
							if (!$(this).val()) {
								alert("Please upload audio!");
								$(this).focus();
								tracksMp3Error += 1;
							}
						});
						if(tracksMp3Error > 0) {
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
                                //		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
                                optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                            }
                            //	 document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
                            document.getElementById('subGenre').innerHTML = optionList;
                        }
                    });
                }

                removeTrack = function() {
                    if (parseInt($('[name^="title"').length) > 1) {
                        $('#audioFiles').find('.panel').last().remove();
                    }
                }

                addTrack = function() {
                    $trackTemplate = '<div class="panel panel-default">\
                                    <div class="panel-body">\
                                        <div class="col-sm-12 versionDiv">\
                                            <div class="form-group">\
                                                <label for="form-field-1 form-group req-label" style="display: block;"> Title <span style="color: red">*</span></label>\
                                                <input type="text" name="title" class="form-control" required >\
                                            </div>\
                                        </div>\
                                        <div class="col-sm-12 trackVersions">\
                                            <div class="row">\
                                                <div class="col-sm-3 versionDiv">\
                                                    <div class="form-group">\
                                                        <label for="form-field-1"> File <span style="color: red">*</span></label>\
                                                        <input type="file" name="audio" class="form-control form-file" required>\
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
                                                <label for="form-field-1"> File <span style="color: red">*</span></label>\
                                                <input type="file" name="audio" class="form-control form-file" required>\
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
            });
            
            
jQuery( "#artist" ).autocomplete({
      source: "<?php echo url("Member_autocomplete");?>",
      response: function(event, ui) {
           event.preventDefault();
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                // jQuery("#artist").val('');
            
            } else {
              
            }
        }
 }); 
 	$("#formAddAlbum").validate();
    
   var $theForm = $("#formAddAlbum");
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