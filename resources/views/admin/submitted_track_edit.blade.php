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
               <a href="<?php echo url("admin/submitted_tracks"); ?>">
                  <i class="ace-icon fa fa-list list-icon"></i>
                  Submitted Tracks</a>
            </li>
            <li class="active">Edit Track</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
         <div class="row">
            <div class="col-xs-12">
               <h3 class="header smaller lighter">
                  Track Information
               </h3>
               <!-- PAGE CONTENT BEGINS -->
               <div class="row">
                  <div class="col-lg-2 col-sm-3 col-xs-12">

                     <?php // echo urldecode($result['data'][0]->artist); 
                     ?>
                     <?php
                     if (!empty($result['data'][0]->pCloudFileID)) {
                        $news_artwork = url('/pCloudImgDownload.php?fileID=' . $result['data'][0]->pCloudFileID); ?>
                        <img id="previewImg" src="<?php echo $news_artwork; ?>" width="200" height="200" class="img-responsive up-ar-img" />

                     <?php  } else if (isset($result['data'][0]->imgpage) && strlen($result['data'][0]->imgpage) > 4 && file_exists(base_path('ImagesUp/' . $result['data'][0]->imgpage))) { ?>
                        <img id="previewImg" src="<?php echo asset('ImagesUp/' . $result['data'][0]->imgpage); ?>" width="200" height="200" class="img-responsive up-ar-img" />
                     <?php } else { ?>
                        <img id="previewImg" src="<?php echo asset('public/images/noimage-avl.jpg'); ?>" width="200" height="200" class="img-responsive up-ar-img" />
                     <?php } ?>
                  </div>
                  <div class="col-lg-10 col-sm-9 col-xs-12">
                     <?php
                     if (isset($alert_message)) {
                     ?>
                        <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
                     <?php
                     }


                     ?>
                     <?php // $track = $tracks['data'][0]; 
                     ?>
                     <form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()">
                        @csrf

                        <div class="row">
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Client </label>
                                 <span class="readonly-field"><?php if (isset($result['data'][0]->uname) && !empty($result['data'][0]->uname)) {
                                                                  echo urldecode($result['data'][0]->uname);
                                                               } ?></span>
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Page Image </label>
                                 <input type="file" id="pageImage" name="pageImage" accept="image/png, image/gif, image/jpeg" class="form-file form-control" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Artist </label>
                                 <input type="text" id="artist" name="artist" class="form-control" value="<?php if (isset($result['data'][0]->artist) && !empty($result['data'][0]->artist)) {
                                                                                                               echo urldecode($result['data'][0]->artist);
                                                                                                            } ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Title </label>
                                 <input type="text" id="title" name="title" class="form-control" value="<?php if (isset($result['data'][0]->title) && !empty($result['data'][0]->title)) {
                                                                                                            echo urldecode($result['data'][0]->title);
                                                                                                         } ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Album Type </label>
                                 <select name="albumType" id="albumType" class="form-control">
                                    <option value="">Album Type</option>
                                    <option <?php if (isset($result['data'][0]->albumType) && $result['data'][0]->albumType == 1) { ?> selected <?php } ?> value="1">Single</option>
                                    <option <?php if (isset($result['data'][0]->albumType) && $result['data'][0]->albumType == 2) { ?> selected <?php } ?> value="2">Album</option>
                                    <option <?php if (isset($result['data'][0]->albumType) && $result['data'][0]->albumType == 3) { ?> selected <?php } ?> value="3">EP</option>
                                    <option <?php if (isset($result['data'][0]->albumType) && $result['data'][0]->albumType == 4) { ?> selected <?php } ?> value="4">Mixtape</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Album </label>
                                 <input type="text" id="album" name="album" class="form-control" value="<?php if (isset($result['data'][0]->album) && !empty($result['data'][0]->album)) echo urldecode($result['data'][0]->album); ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Label </label>
                                 <input type="text" id="company" placeholder="Label / Company" name="company" class="form-control" value="<?php echo urldecode($result['data'][0]->label); ?>">
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Time </label>
                                 <input type="text" id="time" name="time" class="form-control" value="<?php echo urldecode($result['data'][0]->time); ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Bpm </label>
                                 <input type="text" id="bpm" name="bpm" class="form-control" value="<?php echo urldecode($result['data'][0]->bpm); ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Contact EMail</label>
                                 <input type="text" id="contact_email" name="contact_email" class="form-control" value="<?php echo urldecode($result['data'][0]->contact_email); ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Website Link</label>
                                 <input type="text" id="website" name="website" class="form-control" value="<?php echo urldecode($result['data'][0]->link); ?>" />
                              </div>
                           </div>
                           <?php $linkDivDisplay1 = '';
                           $linkDivDisplay2 = '';
                           if (strlen($result['data'][0]->link1) > 1) {
                              $linkDivDisplay1 = '';
                           }
                           if (strlen($result['data'][0]->link2) > 1) {
                              $linkDivDisplay2 = '';
                           }

                           ?>
                           <div class="col-sm-6 col-xs-12" style="display:<?php echo $linkDivDisplay1; ?>;">
                              <div class="form-group">
                                 <label class="control-label">Website Link1</label>
                                 <input type="text" id="website1" name="website1" class="form-control" value="<?php echo $result['data'][0]->link1; ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12" style="display:<?php echo $linkDivDisplay2; ?>;">
                              <div class="form-group">
                                 <label class="control-label">Website Link2</label>
                                 <input type="text" id="website2" name="website2" class="form-control" value="<?php echo $result['data'][0]->link2; ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Facebook</label>
                                 <input type="text" id="facebookLink" name="facebookLink" class="form-control" value="<?php echo $result['data'][0]->facebookLink; ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Twitter</label>
                                 <input type="text" id="twitterLink" name="twitterLink" class="form-control" value="<?php echo $result['data'][0]->twitterLink; ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Instagram</label>
                                 <input type="text" id="instagramLink" name="instagramLink" class="form-control" value="<?php echo $result['data'][0]->instagramLink; ?>" />
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group"> <label class="control-label">Apple Music URL</label> <input type="url" id="applemusicLink" name="applemusicLink" class="form-control" value="<?php echo $result['data'][0]->applemusicLink; ?>" /> </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group"> <label class="control-label">Amazon URL</label> <input type="url" id="amazonLink" name="amazonLink" class="form-control" value="<?php echo $result['data'][0]->amazonLink; ?>" /> </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group"> <label class="control-label">Spotify URL</label> <input type="url" id="spotifyLink" name="spotifyLink" class="form-control" value="<?php echo $result['data'][0]->spotifyLink; ?>" /> </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Producers </label>
                                 <input type="text" id="producers" name="producers" class="form-control" value="<?php echo urldecode($result['data'][0]->producers); ?>" />
                              </div>
                           </div>
                           <div class="col-sm-12 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> More Info </label>
                                 <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control"><?php echo urldecode($result['data'][0]->moreinfo); ?></textarea>
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Genre</label>
                                 <select name="genre" id="genre" class="form-control" onchange="change_genre(this.value)">
                                    <option value="">Genre</option>
                                    <?php if ($genres['numRows'] > 0) {
                                       foreach ($genres['data'] as $genre) { ?>
                                          <option <?php if ($result['data'][0]->genreId == $genre->genreId) { ?> selected <?php } ?> value="<?php echo $genre->genreId; ?>"><?php echo $genre->genre; ?></option>
                                    <?php }
                                    } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label">Sub Genre </label>
                                 <select name="subGenre" id="subGenre" class="form-control">
                                    <option value="">Sub Genre</option>
                                    <?php if ($subGenres['numRows'] > 0) {
                                       foreach ($subGenres['data'] as $genre) { ?>
                                          <option <?php if ($result['data'][0]->subGenreId == $genre->subGenreId) { ?> selected <?php } ?> value="<?php echo $genre->subGenreId; ?>"><?php echo $genre->subGenre; ?></option>
                                    <?php }
                                    } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-6 col-xs-12">
                              <div class="form-group">
                                 <label class="control-label"> Release Date </label>
                                 <?php
                                 $releaseDate = explode(' ', $result['data'][0]->releasedate);
                                 $date = explode('-', $releaseDate[0]);
                                 $date = $date[1] . '-' . $date[2] . '-' . $date[0];



                                 ?>
                                 <input type="text" id="releaseDate" name="releaseDate" class="form-control" value="<?php echo $date; ?>" />
                              </div>
                           </div>
                        </div>

                        <div class="space-24"></div>
                        <h3 class="header smaller lighter">Audio Files</h3>
                        <?php $versionCount = 1; ?>
                        <table id="simple-table" class="table  table-bordered table-hover">
                           <thead>
                              <tr>
                                 <th class="center" width="100">
                                    S. No
                                 </th>
                                 <th class="detail-col" width="150">Version</th>
                                 <th>Track</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if (strlen($result['data'][0]->amr1) > 4) {
                                 $versionCount++; ?>
                                 <tr>
                                    <td class="center">1</td>
                                    <td class="left"><?php echo $result['data'][0]->version1;  ?></td>
                                    <?php // echo $result['data'][0]->amr1;  
                                    ?>
                                    <td>
                                       <audio controls="" style="width:100%;">
                                          <?php if (strpos($result['data'][0]->amr1, '.mp3') !== false) { ?>
                                             <source src="<?php e("AUDIO/" . $result['data'][0]->amr1); ?>">
                                          <?php } else {
                                             $fileid = (int)$result['data'][0]->amr1;
                                             $getlink = '';
                                             if (!empty($fileid)) {
                                                $getlink = url('download.php?fileID=' . $fileid);
                                             } ?>
                                             <source src="<?php echo $getlink; ?>">
                                          <?php } ?>
                                          Your browser does not support the audio element.
                                       </audio>
                                    </td>
                                 </tr>
                              <?php } ?>
                              <?php if (strlen($result['data'][0]->amr2) > 4) {
                                 $versionCount++; ?>
                                 <tr>
                                    <td class="center">2</td>
                                    <td class="left"><?php echo $result['data'][0]->version2;  ?></td>
                                    <?php // echo $result['data'][0]->amr2;  
                                    ?>
                                    <td>
                                       <audio controls="" style="width:100%;">
                                          <?php if (strpos($result['data'][0]->amr2, '.mp3') !== false) { ?>
                                             <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr2); ?>">
                                          <?php } else {
                                             $fileid = (int)$result['data'][0]->amr2;
                                             $getlink = '';
                                             if (!empty($fileid)) {
                                                $getlink = url('download.php?fileID=' . $fileid);
                                             } ?>
                                             <source src="<?php echo $getlink; ?>">
                                          <?php } ?>
                                          Your browser does not support the audio element.
                                       </audio>
                                    </td>
                                 </tr>
                              <?php } ?>
                              <?php if (strlen($result['data'][0]->amr3) > 4) {
                                 $versionCount++;  ?>
                                 <tr>
                                    <td class="center">3</td>
                                    <td class="left"><?php echo $result['data'][0]->version3;  ?></td>
                                    <?php // echo $result['data'][0]->amr3;  
                                    ?>
                                    <td>
                                       <audio controls="" style="width:100%;">
                                          <?php if (strpos($result['data'][0]->amr3, '.mp3') !== false) { ?>
                                             <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr3); ?>">
                                          <?php } else {
                                             $fileid = (int)$result['data'][0]->amr3;
                                             $getlink = '';
                                             if (!empty($fileid)) {
                                                $getlink = url('download.php?fileID=' . $fileid);
                                             } ?>
                                             <source src="<?php echo $getlink; ?>">
                                          <?php } ?>
                                          Your browser does not support the audio element.
                                       </audio>
                                    </td>
                                 </tr>
                              <?php } ?>
                              <?php if (strlen($result['data'][0]->amr4) > 4) {
                                 $versionCount++; ?>
                                 <tr>
                                    <td class="center">4</td>
                                    <td class="left"><?php echo $result['data'][0]->version4;  ?></td>
                                    <?php // echo $result['data'][0]->amr4;  
                                    ?>
                                    <td>
                                       <audio controls="" style="width:100%;">
                                          <?php if (strpos($result['data'][0]->amr4, '.mp3') !== false) { ?>
                                             <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr4); ?>">
                                          <?php } else {
                                             $fileid = (int)$result['data'][0]->amr4;
                                             $getlink = '';
                                             if (!empty($fileid)) {
                                                $getlink = url('download.php?fileID=' . $fileid);
                                             } ?>
                                             <source src="<?php echo $getlink; ?>">
                                          <?php } ?>
                                          Your browser does not support the audio element.
                                       </audio>
                                    </td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                        <?php if ($versionCount < 5) { ?>
                           <div id="audioFiles" class="row">

                              <div id="audioHtml1">
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label" for="form-field-1">Version </label>

                                       <!--	<input type="text"  name="version1" class="form-control">
                                    -->
                                       <select name="version<?php echo $versionCount; ?>" id="version<?php echo $versionCount; ?>" class="form-control version">
                                          <option value="">Version</option>
                                          <option value="Clean">Clean</option>
                                          <option value="Instrumental">Instrumental</option>
                                          <option value="Acapella">Acapella</option>
                                          <option value="Dirty">Dirty</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label" for="form-field-1"> File </label>

                                       <input type="file" id="audio<?php echo $versionCount; ?>" name="audio<?php echo $versionCount; ?>" class="form-control form-file">
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <a href="javascript:void()" onclick="moreAudio()" class="btn btn-success btn-sm">+</a>
                           <a href="javascript:void()" onclick="removeAudio()" class="btn btn-danger btn-sm">-</a>
                           <input type="hidden" id="divId" name="divId" value="<?php echo $versionCount; ?>" />
                        <?php } ?>
                        <div class="form-actions text-right">

                           <button class="btn btn-info btn-sm" type="submit" name="updateSubmittedTrack">
                              <i class="ace-icon fa fa-check bigger-110"></i>
                              Update Track
                           </button>
                           &nbsp; &nbsp; &nbsp;
                           <button class="btn btn-sm" type="reset">
                              <i class="ace-icon fa fa-undo bigger-110"></i>
                              Reset
                           </button>

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
   </div>
</div>
<!-- /.page-content -->
<script>
   function validate() {


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

      var numericExp = /^[-+]?[0-9]+$/;
      var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

      if (client.value.length < 1) {
         alert("Please select client!");
         client.focus();
         return false;
      }



      if (company.value.length < 1) {
         alert("Please enter company!");
         company.focus();
         return false;
      }


      if (linkk.value.length < 1) {
         alert("Please enter link!");
         linkk.focus();
         return false;
      }

      var n = linkk.value.indexOf(".");


      if (n < 1) {
         alert("Please enter link!");
         linkk.focus();
         return false;
      }


      if (moreInfo.value.length < 1) {
         alert("Please enter more info.!");
         moreInfo.focus();
         return false;
      }


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


      if (album.value.length < 1) {
         alert("Please enter album!");
         album.focus();
         return false;
      }

      if (time.value.length < 1) {
         alert("Please enter time!");
         time.focus();
         return false;
      }

      if (link1.value.length < 1) {
         alert("Please enter link!");
         link1.focus();
         return false;
      }


      var n = link1.value.indexOf(".");


      if (n < 1) {
         alert("Please enter link!");
         link1.focus();
         return false;
      }

      if (producers.value.length < 1) {
         alert("Please enter producers!");
         producers.focus();
         return false;
      }

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


      /*					   var numVersion = document.getElementById('numVersion').value;
      var newVersion = parseInt(numVersion)+1;
      
      if(numVersion<4)
      {*/
      var divId = document.getElementById('divId').value;
      var divIdPlus = parseInt(divId) + 1;

      if (divId < 4) {

         document.getElementById('divId').value = divIdPlus;

         var parentDiv = document.createElement("div");
         parentDiv.setAttribute('id', 'html' + divIdPlus);


         var smDiv1 = document.createElement("div");
         smDiv1.setAttribute('class', 'col-sm-6 form-group');

         var smDiv2 = document.createElement("div");
         smDiv2.setAttribute('class', '');

         var label1 = document.createElement("label");
         label1.setAttribute('class', 'control-label');

         var textnode1 = document.createTextNode("Version");
         label1.appendChild(textnode1);


         var input1 = document.createElement("select");
         //  input1.setAttribute('type','select');
         input1.setAttribute('name', 'version' + divIdPlus);
         input1.setAttribute('id', 'version' + divIdPlus);
         input1.setAttribute('class', 'form-control');

         var option1 = document.createElement("option");
         option1.setAttribute('value', '');
         option1.text = "Version";
         input1.add(option1);

         var option2 = document.createElement("option");
         option2.setAttribute('value', 'Clean');
         option2.text = "Clean";
         input1.add(option2);

         var option3 = document.createElement("option");
         option3.setAttribute('value', 'Instrumental');
         option3.text = "Instrumental";
         input1.add(option3);

         var option4 = document.createElement("option");
         option4.setAttribute('value', 'Acapella');
         option4.text = "Acapella";
         input1.add(option4);

         var option5 = document.createElement("option");
         option5.setAttribute('value', 'Dirty');
         option5.text = "Dirty";
         input1.add(option5);
         smDiv2.appendChild(input1);
         smDiv1.appendChild(label1);
         smDiv1.appendChild(smDiv2);
         var smDiv3 = document.createElement("div");
         smDiv3.setAttribute('class', 'col-sm-6 form-group');
         var smDiv4 = document.createElement("div");
         smDiv4.setAttribute('class', '');
         var label2 = document.createElement("label");
         label2.setAttribute('class', 'control-label');
         var textnode2 = document.createTextNode("File");
         label2.appendChild(textnode2);
         var input2 = document.createElement("input");
         input2.setAttribute('type', 'file');
         input2.setAttribute('name', 'audio' + divIdPlus);
         input2.setAttribute('id', 'audio' + divIdPlus);
         input2.setAttribute('class', 'form-control form-file');
         smDiv4.appendChild(input2);
         smDiv3.appendChild(label2);
         smDiv3.appendChild(smDiv4);
         parentDiv.appendChild(smDiv1);
         parentDiv.appendChild(smDiv3);
         var clearboth = document.createElement("div");
         clearboth.setAttribute('class', '');
         document.getElementById('audioFiles').appendChild(clearboth);
         document.getElementById('audioFiles').appendChild(parentDiv);
      }
   }

   // Wait for the DOM to be ready
   $(function() {
      $('#pageImage').bind('change', function() {

         //this.files[0].size gets the size of your file.

      });

   });
</script>
<script>
   function change_genre(genreId) {

      $.ajax({
         url: "../Client_submit_track?getSubGenres=1&genreId=" + genreId,
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


   function filePreview(input) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function(e) {
            //$('#artWork + img').remove();
            // $('#artWork').after('<img src="'+e.target.result+'" width="450" height="300"/>');

            document.getElementById('previewImg').style.width = '199px';
            document.getElementById('previewImg').style.height = '199px';
            document.getElementById('previewImg').src = e.target.result;
            //$('#uploadForm + embed').remove();
            //$('#uploadForm').after('<embed src="'+e.target.result+'" width="450" height="300">');
         }
         reader.readAsDataURL(input.files[0]);
      }
   }

   $("#pageImage").change(function() {
      filePreview(this);
   });
</script>
@endsection