
@extends('admin.admin_dashboard_active_sidebar')
    @section('content')
<div class="main-content">
    <style>
        .form_loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 10px solid #b32672;
            border-radius: 50%;
            border-top: 10px solid #000;
            width: 64px;
            height: 64px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            z-index: 9999;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .cus_none {
            pointer-events: none;
        }
    </style>
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
                <li class="active">Add Track - Step 2</li>
            </ul><!-- /.breadcrumb -->
            <!-- /section:basics/content.searchbox -->
            <!--<a class="btn btn-info btn-sm" href="<?php // echo url("admin/track_edit?tid=".$pass_track_id); ?>">Edit Previous step</a>-->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">

                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <?php
                        if ($track_exists) { ?>
                            <div class="alert alert-danger">Track Exists</div>
                        <?php    }
                        ?>
                        <div class="col-xs-12">
                            <form class="form-horizontal" id="submitTrackStep2" role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off" style="color:white;">
                            @csrf
                                <div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter blue">Logos</h3>
                                    <div class="col-sm-6">
                                        <!--    <span class="col-sm-4 col-sm-offset-3" style="color:#428bca;">Select Logo</span> -->
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Logos </label>
                                            <div class="col-sm-9">
                                                <p style="margin-bottom:10px;">
                                                    <a id="listAlpha" href="javascript:void()" onclick="getLogos('1','1','')" class="activeLogoLink">Alphabetic</a>
                                                    <a style="margin-left:8px;" id="listNewest" href="javascript:void()" onclick="getLogos('2','1','')" class="inActiveLogoLink">Newest</a>
                                                </p>
                                                <input type="text" id="searchLogo" placeholder="Search Logo" class="col-xs-10 col-sm-10" style="margin-bottom:8px;" onkeyup="getLogos('3','1',this.value)" />
                                                <select name="logos[]" size="5" multiple="" class="col-xs-10 col-sm-10" id="logos">
                                                    <?php foreach ($logos['data'] as $logo) { ?>
                                                        <option value="<?php echo $logo->id;  ?>"><?php echo urldecode($logo->company); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 col-sm-offset-3" style="color:#428bca;">Add New Logo</span>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Company </label>
                                            <div class="col-sm-9">
                                                <input type="text" id="logoCompany" name="logoCompany" class="col-xs-10 col-sm-10">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Link </label>
                                            <div class="col-sm-9">
                                                <input type="text" id="logoLink" name="logoLink" class="col-xs-10 col-sm-10">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Logo </label>
                                            <div class="col-sm-9">
                                                <input type="file" id="logoImage" name="logoImage" class="col-xs-10 col-sm-10">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter blue">Artist Information</h3>
                                    
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Video Link </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="link1" name="video" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Video Embed Link </label>
                                        <div class="col-sm-9">                                            
                                            <textarea id="embedlink" placeholder="Video Embed Link (Youtube/Vimeo)" name="embedlink" class="col-xs-10 col-sm-10"></textarea>
                                        </div>
                                    </div>                                    
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Website: </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="website" placeholder="Website" name="website" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website 1 </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="website1" name="website1" class="col-xs-10 col-sm-10" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website 2 </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="website2" name="website2" class="col-xs-10 col-sm-10" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Facebook </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="facebook" name="facebook" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Twitter </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="twitter" name="twitter" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Instagram </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="instagram" name="instagram" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tik Tok </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="tiktok" name="tiktok" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Snapchat </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="snapchat" name="snapchat" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Others </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="others" name="others" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>																											<div style="clear:both;"></div>                                    <div class="col-sm-6 form-group">                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Apple Music URL </label>                                        <div class="col-sm-9">                                            <input type="url" id="applemusicLink" name="applemusicLink" class="col-xs-10 col-sm-10">                                        </div>                                    </div>                                    <div class="col-sm-6 form-group">                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Amazon URL </label>                                        <div class="col-sm-9">                                            <input type="url" id="amazonLink" name="amazonLink" class="col-xs-10 col-sm-10">                                        </div>                                    </div>									<div style="clear:both;"></div>                                    <div class="col-sm-6 form-group">                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Spotify URL </label>                                        <div class="col-sm-9">                                            <input type="url" id="spotifyLink" name="spotifyLink" class="col-xs-10 col-sm-10">                                        </div>                                    </div>																		<div style="clear:both;"></div>
                                    <div class="form_loader" style="display:none;"></div>
                                    <div id="cus_loader">
                                        <h3 class="header smaller lighter blue">Audio Files</h3>
                                        <?php
                                        $genresArr = array();
                                        $i = 0;
                                        if ($genres['numRows'] > 0) {
                                            foreach ($genres['data'] as $genre) {
                                                $genresArr[$i] =  $genre->genre;
                                                $i++;
                                            }
                                        }
                                        $myJSON = json_encode($genresArr);
                                        ?>
                                        <div id="genresData" style="display:none;"><?php echo $myJSON; ?></div>
                                        <div id="audioFiles">
                                            <div id="audioHtml1">
                                                <div class="col-sm-4 form-group versionDiv">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Version </label>
                                                    <div class="col-sm-9">
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
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Other Version </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="otherVersion1" name="otherVersion1" class="col-xs-10 col-sm-10">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 form-group versionDiv">
                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> File </label>
                                                    <input type="hidden" name="bpm" id="bpm">
                                                    <input type="hidden" name="key" id="key">
                                                    <div class="col-sm-10">
                                                        <input type="file" id="audio1" name="audio1" class="col-xs-6 col-sm-6" required accept=".mp3">
                                                    <span class="col-xs-6 col-sm-6" style="color:red;text-align:center;">Size - kbps Format - MP3</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear:both;"></div>
                                        <a href="javascript:void()" onclick="moreAudio()" class="btn btn-success btn-sm">+</a>
                                        <a href="javascript:void()" onclick="removeAudio()" class="btn btn-danger btn-sm">-</a>
                                        <input type="hidden" id="divId" name="divId" value="1" />
                                    </div>
                                    <div class="space-24"></div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
											<a href="<?php echo url("admin/add_track?tid=".$pass_track_id);?>">
												<button class="btn btn-info btn-sm" type="submit" name="addback">
                                                <i class="ace-icon fa fa-long-arrow-left bigger-110"></i>
                                                Back
                                               </button>
											</a>
											
											 &nbsp; &nbsp; &nbsp;
											 <input type="hidden" name="addTrack" value="addTrack">
                                            <button class="btn btn-info btn-sm" type="submit" id="submitStep2-btn" name="addTrack">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Submit - Step 2
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn btn-sm" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

            function isValidURL(str) {
                var pattern = new RegExp('^((https?:)?\\/\\/)?' + // protocol
                    '(?:\\S+(?::\\S*)?@)?' + // authentication
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                    '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locater
                if (!pattern.test(str)) {
                    return false;
                } else {
                    return true;
                }
            }

            function validate() {
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
                var facebook = document.getElementById('facebook');
                var twitter = document.getElementById('twitter');
                var instagram = document.getElementById('instagram');
                var producers = document.getElementById('producers');
                var numericExp = /^[-+]?[0-9]+$/;
                var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if ($('#artist').val() == '') {
                    alert("Please enter artist name!");
                    artist.focus();
                    return false;
                }
                var title = document.getElementById('title');
                if ($('#title').val() == '') {
                    alert("Please enter title!");
                    title.focus();
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
                if (client.value.length < 1) {
                    alert("Please select client!");
                    client.focus();
                    return false;
                }
                /*	if(company.value.length<1)
                	{
                	  alert("Please enter company!");
                	  company.focus();
                	  return false;
                	}


                	if(linkk.value.length<1)
                	{
                	  alert("Please enter link!");
                	  linkk.focus();
                	  return false;
                	}

                	var n = linkk.value.indexOf(".");
                	if(n<1)
                	{
                	  alert("Please enter link!");
                	  linkk.focus();
                	  return false;
                	}


                	if(moreInfo.value.length<1)
                	{
                	  alert("Please enter more info.!");
                	  moreInfo.focus();
                	  return false;
                	}
                */

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

                /*if(album.value.length<1)
                {
                  alert("Please enter album!");
                  album.focus();
                  return false;
                }

                if(time.value.length<1)
                {
                  alert("Please enter time!");
                  time.focus();
                  return false;
                }

                if(link1.value.length<1)
                {
                  alert("Please enter link!");
                  link1.focus();
                  return false;
                }

                var n = link1.value.indexOf(".");
                if(n<1)
                {
                  alert("Please enter link!");
                  link1.focus();
                  return false;
                }

                if(producers.value.length<1)
                {
                  alert("Please enter producers!");
                  producers.focus();
                  return false;
                }*/
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
                label1.setAttribute('class', 'col-sm-3 control-label no-padding-right');
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
                otherLabel.setAttribute('class', 'col-sm-3 control-label no-padding-right');
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
                label2.setAttribute('class', 'col-sm-3 control-label no-padding-right');
                var textnode2 = document.createTextNode("File");
                label2.appendChild(textnode2);
                var input2 = document.createElement("input");
                input2.setAttribute('type', 'file');
                input2.setAttribute('name', 'audio' + divIdPlus);
                input2.setAttribute('id', 'audio' + divIdPlus);
                input2.setAttribute('class', 'col-xs-10 col-sm-10');
                input2.setAttribute('required','');
                input2.setAttribute('accept','.mp3');
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
            $("#submitTrackStep2").validate();
             var $theForm = $("#submitTrackStep2");
           $theForm.submit(function () {
            if($theForm.valid()) {
                //console.log('addTrack_Validated');
                $('#submitStep2-btn').attr('disabled','disabled');
                $('.processing_loader_gif').show();     
        
            }else{
              $("html, body").animate({ scrollTop: 0 }, "slow");
              return false;        
            }
            
           });          
        </script>

@endsection 