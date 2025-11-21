@extends('layouts.client_dashboard')
@section('content')
<style>
   .nopadding {
      padding: 0px !important;
   }

   .amrFile {
      visibility: hidden !important;
      height: 0px !important;
   }

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

   .cus_modal_desg {
      position: absolute;
      right: 0;
      margin-top: 0;
      width: 400px;
   }

   .cus_modal_desg .modal-body p {
      font-size: 20px;
      font-weight: 600;
   }

   .cus_modal_desg .btn-default {
      background-color: #A02064;
      border: none;
      color: #fff;
      font-size: 15px;
      font-weight: 600;
      padding: 6px 50px;
   }

   .cus_modal_desg .modal-footer {
      padding-right: 25px;
      text-align: center;
   }

   .cus_modal_desg .modal-body {
      padding: 35px;
      text-align: center;
      padding-bottom: 15px;
   }

   .cus_modal_desg .modal-content {
      background-color: #9a9898;
   }
</style>
<section class="main-dash">

   <?php $bit_route = route('get_audio_bitrate'); ?>
   <input type="hidden" id="bit_route" value="<?php echo $bit_route; ?>">
   <meta name="csrf-token" content="{{ csrf_token() }}" />



   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>My Dashboard</h2>
               </div>

               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  <?php if (isset($alert_class)) { ?>
                     <div class="<?php echo $alert_class; ?>">
                        <p><?php echo $alert_message; ?></p>
                     </div>

                  <?php } // print_r($formData); 
                  ?>
                  <form action="" method="post" enctype="multipart/form-data" id="addTrack" autocomplete="off">
                     @csrf
                     <!--<input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />-->


                     <div class="sat-blk f-block">
                        <h1>UPLOAD YOUR MUSIC</h1>
                        <div class="mnm1" style="" id="customBar">
                           <div class="row">
                              <div class="col-lg-8 col-md-9 col-sm-8">
                                 <div class="form-group"><span class="man"></span>
                                    <input required name="artist" id="artist" class="form-control input artist_title" <?php if (isset($_GET['artist'])) { ?> value="<?php echo $_GET['artist']; ?>" <?php } ?> size="20" placeholder="Artist Name & Features" type="text">
                                 </div>
                                 <div class="form-group"><span class="man"></span>
                                    <input required name="title" id="title" class="form-control input artist_title" size="20" <?php if (isset($_GET['title'])) { ?> value="<?php echo $_GET['title']; ?>" <?php } ?> placeholder="Song Title" type="text">
                                 </div>
                                 <div class="form-group"><span class="man"></span>
                                    <input required name="producer" id="producer" class="form-control input" size="20" <?php if (isset($_GET['producer'])) { ?> value="<?php echo $_GET['producer']; ?>" <?php } ?> placeholder="Producer/Production Company" type="text">
                                 </div>
                                 <div class="form-group"><span class="man"></span>
                                    <input required type="text" id="writer" name="writer" class="form-control" <?php if (isset($_GET['writer'])) { ?> value="<?php echo $_GET['writer']; ?>" <?php } ?> placeholder="Writer">
                                 </div>
                                 <div class="form-group">
                                    <input type="text" id="songkey" name="songkey" class="form-control" <?php if (isset($_GET['songkey'])) { ?> value="<?php echo $_GET['songkey']; ?>" <?php } ?> placeholder="Song key">
                                 </div>
                                 <div class="form-group"><span class="man"></span>
                                    <input required name="trackTime" id="trackTime" class="form-control input" <?php if (isset($_GET['trackTime'])) { ?> value="<?php echo $_GET['trackTime']; ?>" <?php } ?> size="20" placeholder="Track Time (in minutes)" type="text">
                                 </div>
                                 <div class="form-group">
                                    <input type="text" name="bpm" id="bpm" <?php if (isset($_GET['bpm'])) { ?> value="<?php echo $_GET['bpm']; ?>" <?php } ?> class="form-control" placeholder="BPM (Beats Per Minute)" />
                                 </div>
                              </div>

                              <div class="col-lg-4 col-md-3 col-sm-4">
                                 <div class="form-group">
                                    <label class="btn p-0">
                                       <img src="assets/img/upload-artwork.jpg" id="previewImg" class="img-responsive up-ar-img">
                                       <input id="artWork" name="artWork" accept="image/png, image/gif, image/jpeg" style="visibility:hidden; height:5px !important;" type="file">
                                    </label>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                 <div class="form-group">
                                    <input name="album" id="album" class="form-control input" <?php if (isset($_GET['album'])) { ?> value="<?php echo $_GET['album']; ?>" <?php } ?> size="20" placeholder="Album Name" type="text">
                                 </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    <select name="albumType" id="albumType" class="form-control">
                                       <option value="">Music Type</option>
                                       <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '1') == 0) { ?> selected <?php } ?> value="1">Single</option>
                                       <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '2') == 0) { ?> selected <?php } ?> value="2">Album</option>
                                       <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '3') == 0) { ?> selected <?php } ?> value="3">EP</option>
                                       <option <?php if (isset($_GET['albumType']) && strcmp($_GET['albumType'], '4') == 0) { ?> selected <?php } ?> value="4">Mixtape</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    <select name="priorityType" id="priorityType" class="form-control">
                                       <option value="">Select Priority</option>
                                       <option <?php if (isset($_GET['priorityType']) && strcmp($_GET['priorityType'], 'top-priority') == 0) { ?> selected <?php } ?> value="top-priority">Top Priority</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <label>Album Release Date</label>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <select name="month" id="month" class="form-control">
                                       <option value="">Month</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '01') == 0) { ?> selected <?php } ?> value="01">January</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '02') == 0) { ?> selected <?php } ?> value="02">February</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '03') == 0) { ?> selected <?php } ?>
                                          value="03">March</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '04') == 0) { ?> selected <?php } ?>
                                          value="04">April</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '05') == 0) { ?> selected <?php } ?>
                                          value="05">May</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '06') == 0) { ?> selected <?php } ?>
                                          value="06">June</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '07') == 0) { ?> selected <?php } ?>
                                          value="07">July</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '08') == 0) { ?> selected <?php } ?>
                                          value="08">August</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '09') == 0) { ?> selected <?php } ?> value="09">September</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '10') == 0) { ?> selected <?php } ?> value="10">October</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '11') == 0) { ?> selected <?php } ?> value="11">November</option>
                                       <option <?php if (isset($_GET['month']) && strcmp($_GET['month'], '12') == 0) { ?> selected <?php } ?> value="12">December</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <select name="day" id="day" class="form-control">
                                       <option value="">Day</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '01') == 0) { ?> selected <?php } ?> value="01">1</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '02') == 0) { ?> selected <?php } ?> value="02">2</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '03') == 0) { ?> selected <?php } ?> value="03">3</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '04') == 0) { ?> selected <?php } ?> value="04">4</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '05') == 0) { ?> selected <?php } ?> value="05">5</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '06') == 0) { ?> selected <?php } ?> value="06">6</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '07') == 0) { ?> selected <?php } ?> value="07">7</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '08') == 0) { ?> selected <?php } ?> value="08">8</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '09') == 0) { ?> selected <?php } ?> value="09">9</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '10') == 0) { ?> selected <?php } ?> value="10">10</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '11') == 0) { ?> selected <?php } ?> value="11">11</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '12') == 0) { ?> selected <?php } ?> value="12">12</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '13') == 0) { ?> selected <?php } ?> value="13">13</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '14') == 0) { ?> selected <?php } ?> value="14">14</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '15') == 0) { ?> selected <?php } ?> value="15">15</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '16') == 0) { ?> selected <?php } ?> value="16">16</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '17') == 0) { ?> selected <?php } ?> value="17">17</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '18') == 0) { ?> selected <?php } ?> value="18">18</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '19') == 0) { ?> selected <?php } ?> value="19">19</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '20') == 0) { ?> selected <?php } ?> value="20">20</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '21') == 0) { ?> selected <?php } ?> value="21">21</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '22') == 0) { ?> selected <?php } ?> value="22">22</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '23') == 0) { ?> selected <?php } ?> value="23">23</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '24') == 0) { ?> selected <?php } ?> value="24">24</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '25') == 0) { ?> selected <?php } ?> value="25">25</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '26') == 0) { ?> selected <?php } ?> value="26">26</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '27') == 0) { ?> selected <?php } ?> value="27">27</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '28') == 0) { ?> selected <?php } ?> value="28">28</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '29') == 0) { ?> selected <?php } ?> value="29">29</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '30') == 0) { ?> selected <?php } ?> value="30">30</option>
                                       <option <?php if (isset($_GET['day']) && strcmp($_GET['day'], '31') == 0) { ?> selected <?php } ?> value="31">31</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <?php
                                    $currentYear = date('Y');
                                    ?>
                                    <select name="year" id="year" class="form-control">
                                       <option value="">Year</option>
                                       <?php for ($year = 2000; $year <= $currentYear; $year++) { ?>
                                          <option <?php if (isset($_GET['year']) && strcmp($_GET['year'], $year) == 0) { ?> selected <?php } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <span class="man"></span>
                                    <select required name="genre" id="genre" class="form-control" onchange="change_genre(this.value)">
                                       <option value="">Genre</option>
                                       <?php if ($genres['numRows'] > 0) {
                                          foreach ($genres['data'] as $genre) { ?>
                                             <option <?php if (isset($_GET['genre']) && ($_GET['genre'] == $genre->genreId)) { ?> selected <?php } ?> value="<?php echo $genre->genreId; ?>"><?php echo $genre->genre; ?></option>
                                       <?php }
                                       } ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group">
                                    <select name="subGenre" id="subGenre" class="form-control">
                                       <option value="">Sub Genre</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="form-group">
                                    <span class="man"></span>
                                    <select required name="version" id="version1" class="form-control version">
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
                           </div>
                           <div class="row">
                              <div class="col-lg-12">
                                 <h3 class="header smaller lighter">
                                    Contact Details
                                 </h3>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group"><span class="man"></span>
                                    <input required type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Contact Name">
                                 </div>
                              </div>

                              <div class="col-sm-6">
                                 <div class="form-group"><span class="man"></span>
                                    <input required name="contact_email" id="contact_email" class="form-control input" size="20" <?php if (isset($_GET['contact_email'])) { ?> value="<?php echo $_GET['contact_email']; ?>" <?php } ?> placeholder="Contact email" type="email">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input type="email" id="contact_email_2" name="contact_email_2" class="form-control" placeholder=" Second Contact Email">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input type="email" id="contact_email_3" name="contact_email_3" class="form-control" placeholder=" Third Contact Email">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input type="email" id="contact_email_4" name="contact_email_4" class="form-control" placeholder=" Fourth Contact Email">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" placeholder="Contact Phone">
                                 </div>
                              </div>

                              <div class="col-sm-12">
                                 <div class="form-group">
                                    <input type="text" id="relationship_to_artist" name="relationship_to_artist" class="form-control" placeholder="Relationship to Artist">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12">
                                 <h3 class="header smaller lighter">
                                    Social Media Links
                                 </h3>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <input name="website" id="website" <?php if (isset($_GET['website'])) { ?> value="<?php echo $_GET['website']; ?>" <?php } ?> class="form-control input" size="20" placeholder="Website Link" type="text">
                                 </div>
                                 <div class="uptrk" id="uptrk1">
                                    <?php $linkDivDisplay1 = 'none';
                                    $linkDivDisplay2 = 'none'; ?>
                                    <div class="form-group" id="linkDiv1" style="display:<?php echo $linkDivDisplay1; ?>;">
                                       <input name="website1" id="website1" <?php if (isset($_GET['website1'])) { ?> value="<?php echo $_GET['website2']; ?>" <?php } ?> class="form-control input" size="20" placeholder="Website Link" type="text">
                                    </div>
                                    <div class="form-group" id="linkDiv2" style="display:<?php echo $linkDivDisplay2; ?>;">
                                       <input name="website2" id="website2" <?php if (isset($_GET['website1'])) { ?> value="<?php echo $_GET['website2']; ?>" <?php } ?> class="form-control input" size="20" placeholder="Website Link" type="text">
                                    </div>
                                 </div>
                                 <div class="buttons" style="margin-bottom: 12px;">
                                    <input type="text" name="numLink" id="numLink" value="1" style="display:none;" size="4" />
                                    <a href="javascript:void()" class="addRemoveLinks mr-3" onclick="addLink()"><i class="fa fa-plus-circle"></i>
                                       <span>Add</span>
                                    </a>
                                    <a href="javascript:void()" class="addRemoveLinks" onclick="removeLink()"><i class="fa fa-minus-circle"></i>
                                       <span>Remove </span>
                                    </a>
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="facebookLink" id="facebookLink" class="form-control input" <?php if (isset($_GET['facebookLink'])) { ?> value="<?php echo $_GET['facebookLink']; ?>" <?php } ?> size="20" placeholder="Facebook Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="twitterLink" id="twitterLink" class="form-control input" <?php if (isset($_GET['twitterLink'])) { ?> value="<?php echo $_GET['twitterLink']; ?>" <?php } ?> size="20" placeholder="Twitter Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="instagramLink" id="instagramLink" class="form-control input" <?php if (isset($_GET['instagramLink'])) { ?> value="<?php echo $_GET['instagramLink']; ?>" <?php } ?> size="20" placeholder="Instagram Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="snapchatLink" id="snapchatLink" class="form-control input" <?php if (isset($_GET['snapchatLink'])) { ?> value="<?php echo $_GET['snapchatLink']; ?>" <?php } ?> size="20" placeholder="Snapchat Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="tiktokLink" id="tiktokLink" class="form-control input" <?php if (isset($_GET['tiktokLink'])) { ?> value="<?php echo $_GET['tiktokLink']; ?>" <?php } ?> size="20" placeholder="TikTok Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="otherLink" id="otherLink" class="form-control input" <?php if (isset($_GET['otherLink'])) { ?> value="<?php echo $_GET['otherLink']; ?>" <?php } ?> size="20" placeholder="Other Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="videoURL" id="videolink" class="form-control input" <?php if (isset($_GET['videoURL'])) { ?> value="<?php echo $_GET['videoURL']; ?>" <?php } ?> size="20" placeholder="Video Link" type="text">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="applemusicLink" id="applemusicLink" class="form-control input" <?php if (isset($_GET['applemusicLink'])) { ?> value="<?php echo $_GET['applemusicLink']; ?>" <?php } ?> size="20" placeholder="Apple Music URL" type="url">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="amazonLink" id="amazonLink" class="form-control input" <?php if (isset($_GET['amazonLink'])) { ?> value="<?php echo $_GET['amazonLink']; ?>" <?php } ?> size="20" placeholder="Amazon URL" type="url">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="form-group">
                                    <input name="spotifyLink" id="spotifyLink" class="form-control input" <?php if (isset($_GET['spotifyLink'])) { ?> value="<?php echo $_GET['spotifyLink']; ?>" <?php } ?> size="20" placeholder="Spotify URL" type="url">
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="form-group">
                                 <textarea id="embedvideolink" placeholder="Video Embed Link (Youtube/Vimeo)" name="embedvideoURL" class="form-control input"><?php if (isset($_GET['embedvideoURL']) && !empty($_GET['embedvideoURL'])) {
                                          echo urldecode($_GET['embedvideoURL']);} ?></textarea>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="form-group">
                                    <textarea id="trackInfo" placeholder="Bonus Track Information" name="trackInfo" rows="5" class="form-control input">
                                 <?php if (isset($_GET['trackInfo'])) {
                                          echo $_GET['trackInfo'];
                                          } ?>                                       
                                    </textarea>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12">
                                 <h3 class="header smaller lighter">
                                    Audio Files
                                 </h3>
                              </div>
                              <div class="mb-3">
                                 <div class="form-group">
                                    <input required name="track_title1" id="track_title1" class="form-control input" size="20" placeholder="Track Title" type="text" style="height:47px;">
                                 </div>
                                 <div class="form-group"><span class="man"></span>
                                    <label class="btn text-left" style="padding:0;">
                                       <img src="assets/img/uptrack.png" class="img-responsive1 mCS_img_loaded_st">
                                       <input required style="height:0px !important;" type="file" id="amr1" name="amr1" accept=".mp3">
                                       <span id="amr1_error"></span>
                                    </label>
                                 </div>
                                 <div style="clear:both;"></div>
                                 <div class="uptrk" id="uptrk"></div>
                                 <div style="clear:both;"></div>
                                 <input type="text" name="numVersion" id="numVersion" value="1" style="display:none;" size="4" />
                                 <a href="javascript:void()" class="addRemoveLinks mr-3" onclick="addPhone('<?php echo asset('assets/img/uptrack.png'); ?>')"><i class="fa fa-plus-circle"></i>
                                    <span>Add</span>
                                 </a>
                                 <a href="javascript:void()" class="addRemoveLinks" onclick="removePhone()"><i class="fa fa-minus-circle"></i>
                                    <span>Remove </span>
                                 </a>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                 <div class="form-group">
                                    <div class="checkbox">
                                       <label>
                                          <input required value="" type="checkbox" id="agree" name="agree">
                                          <!--	� �-->
                                          I have authorized promotional use of the above track(s) by Digiwaxx Media. <br> We hereby indemnify and hold harmless, Digiwaxx MediaTM LLC, its agents, successors and/or assigns from any liability arising from its use of the materials supplied herein.
                                       </label>
                                    </div>
                                 </div>
                                 <div class="pre-btn">
                                    <input type="hidden" name="addTrack" value="Add Track">
                                    <input type="submit" name="addTrackBtn" class="add_track_button">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>


               </div>
            </div>
            <div class="col-xl-3 col-12">
               @include('clients.dashboard.includes.my-tracks')
            </div>
         </div>
      </div>
</section>
<!-- Modal -->
<div class="modal fade" id="bpmModal" role="dialog">
   <div class="modal-dialog cus_modal_desg">
      <!-- Modal content-->
      <div class="modal-content">
         <!--- <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
            </div> --->
         <div class="modal-body">
            <p>File quality is below 320kbps.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<!---GET AUDIO BIT RATE AND BPM------------------>
<script src="{{ asset('/music_tempo/music-tempo.min.js') }}"></script>
<script>
   jQuery(document).ready(function() {
      var context = new AudioContext();
      jQuery("#amr1").change(function() {

         var get_url = jQuery('#bit_route').val();
         // var csrf_token = jQuery('#csrf-token-announ').val();

         var fileName = jQuery(this).val();
         var fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));
         if (fileExtension == 'mp3' || fileExtension == 'wav') {
            var name = document.getElementById("amr1").files[0].name;
            var form_data = new FormData();
            form_data.append("file", document.getElementById('amr1').files[0]);

            console.log(form_data);
            $.ajaxSetup({
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });

            jQuery.ajax({

               // point to server-side PHP script 
               url: get_url,
               data: form_data,
               type: 'post',
               // what to expect back from the PHP script, if anything
               dataType: 'json',
               cache: false,
               contentType: false,
               processData: false,


               beforeSend: function() {
                  jQuery(".processing_loader_gif").show();
               },
               success: function(php_script_response) {
                  console.log(php_script_response.bpm);
                  console.log(php_script_response.bitRate);
                  if (php_script_response.bitRate < 320) {
                     //alert('File quality is below 320kbps.');
                     jQuery("#bpmModal").modal('show');
                     jQuery("#bpm").val(0 + ' BPM');

                     jQuery(".processing_loader_gif").hide();
                     return false;
                  } else {
                     console.log(php_script_response);
                     jQuery("#bpm").val(php_script_response.bpm + ' BPM');
                     jQuery(".processing_loader_gif").hide();

                     // 		var files = amr1.files;
                     // 		if (files.length == 0) return;
                     // 		var reader = new FileReader();
                     // 		reader.onload = function(fileEvent) {
                     // 			context.decodeAudioData(fileEvent.target.result, calcTempo);
                     // 		}
                     // 		reader.readAsArrayBuffer(files[0]);
                  }
               }
            });
         } else {
            alert("Please select supported audio type(mp3 or wav)");
            jQuery("#bpm").val(0 + ' BPM');
            jQuery(".processing_loader_gif").hide();
         }
         var calcTempo = function(buffer) {
            var audioData = [];
            // Take the average of the two channels
            if (buffer.numberOfChannels == 2) {
               var channel1Data = buffer.getChannelData(0);
               var channel2Data = buffer.getChannelData(1);
               var length = channel1Data.length;
               for (var i = 0; i < length; i++) {
                  audioData[i] = (channel1Data[i] + channel2Data[i]) / 2;
               }
            } else {
               audioData = buffer.getChannelData(0);
            }
            var mt = new MusicTempo(audioData);

            console.log(mt.tempo);
            jQuery("#bpm").val(Math.round(mt.tempo) + ' BPM');
            jQuery(".processing_loader_gif").hide();
         }
      });
   });
</script>
<script>
   if (window.innerWidth < 400)

   {



      document.getElementById('customBar').classList.remove("mCustomScrollbar");

   }

   function change_genre(genreId) {
      $.ajax({
         url: "Client_submit_track?getSubGenres=1&genreId=" + genreId,
         success: function(result) {
            var obj = JSON.parse(result);
            var count = obj.length;
            var optionList = '';
            for (var i = 0; i < count; i++) {
               optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
            }
            $('#subGenre').html(optionList);
         }
      });
   }


   function removeLink()

   {

      var numLink = document.getElementById('numLink').value;

      var id = parseInt(numLink) - 1;



      if (numLink > 1)

      {

         document.getElementById('linkDiv' + id).style.display = 'none';

         document.getElementById('numLink').value = id;

      }

   }



   function addLink()

   {

      var numLink = document.getElementById('numLink').value;

      var newLink = parseInt(numLink) + 1;

      if (numLink < 3)

      {

         document.getElementById('numLink').value = parseInt(numLink) + 1;

         document.getElementById('linkDiv' + numLink).style.display = 'block';

      }

   }



   function removePhone()

   {



      var numVersion = document.getElementById('numVersion').value;

      var id = parseInt(numVersion) - 1;



      if (numVersion > 1)

      {



         document.getElementById('uptrk').removeChild(document.getElementById('versionDiv' + id));

         document.getElementById('uptrk').removeChild(document.getElementById('imgDiv' + id));

         document.getElementById('numVersion').value = id;

      }


   }



   function addPhone(filePath)

   {



      var numVersion = document.getElementById('numVersion').value;

      var newVersion = parseInt(numVersion) + 1;



      if (numVersion < 6)

      {



         var clearDiv = document.createElement("div");

         clearDiv.setAttribute("class", "clearfix");



         var div = document.createElement("div");
         div.setAttribute("class", "form-group");
         div.setAttribute("id", "versionDiv" + numVersion);


         var phoneInput = document.createElement("input");
         phoneInput.setAttribute("name", "track_title" + newVersion);
         phoneInput.setAttribute("id", "track_title" + newVersion);
         phoneInput.setAttribute("class", "form-control input");
         phoneInput.setAttribute("size", "20");
         phoneInput.setAttribute("placeholder", "Track Title");
         phoneInput.setAttribute("type", "text");
         phoneInput.setAttribute("style", "height:47px;");


         /*
   
   var phoneInput = document.createElement("select");
    phoneInput.setAttribute("name", "version"+newVersion);
    phoneInput.setAttribute("id", "version"+newVersion);
    phoneInput.setAttribute("class", "form-control version");
   
    var option2 = document.createElement("option");
   	 option2.setAttribute('value','Acapella');
   	 option2.text = "Acapella";
   	 phoneInput.add(option2);
   
       var option3 = document.createElement("option");
   	 option3.setAttribute('value','Clean');
   	 option3.text = "Clean";
   	 phoneInput.add(option3);
       
    var option4 = document.createElement("option");
   	 option4.setAttribute('value','Clean Accapella');
   	 option4.text = "Clean Accapella";
   	 phoneInput.add(option4);
   	 
    var option5 = document.createElement("option");
   	 option5.setAttribute('value','Clean (16 Bar Intro)');
   	 option5.text = "Clean (16 Bar Intro)";
   	 phoneInput.add(option5);
   
    var option6 = document.createElement("option");
   	 option6.setAttribute('value','Dirty');
   	 option6.text = "Dirty";
   	 phoneInput.add(option6);
   
    var option7 = document.createElement("option");
   	 option7.setAttribute('value','Dirty Accapella');
   	 option7.text = "Dirty Accapella";
   	 phoneInput.add(option7);
   	 
    var option8 = document.createElement("option");
   	 option8.setAttribute('value','Dirty (16 Bar Intro)');
   	 option8.text = "Dirty (16 Bar Intro)";
   	 phoneInput.add(option8);
   	 
    var option9 = document.createElement("option");
   	 option9.setAttribute('value','Instrumental');
   	 option9.text = "Instrumental";
   	 phoneInput.add(option9);
   
   var option10 = document.createElement("option");
   	option10.setAttribute('value','Main');
   	option10.text = "Main";
   	phoneInput.add(option10);
   
   var option11 = document.createElement("option");
   	option11.setAttribute('value','TV Track');
   	option11.text = "TV Track";
   	phoneInput.add(option11);
   	*/

         document.getElementById('uptrk').appendChild(clearDiv);



         div.appendChild(phoneInput);


         // image

         var imgDiv = document.createElement("div");

         imgDiv.setAttribute("class", "form-group");

         imgDiv.setAttribute("id", "imgDiv" + numVersion);



         var imgLabel = document.createElement("label");

         imgLabel.setAttribute("class", "btn nopadding text-left");



         var img = document.createElement("img");

         img.setAttribute("class", "img-responsive1 mCS_img_loaded_st");

         img.setAttribute("src", filePath);



         var amr1 = document.createElement("input");

         amr1.setAttribute("type", "file");

         amr1.setAttribute("name", "amr" + newVersion);

         amr1.setAttribute("id", "amr" + newVersion);

         amr1.setAttribute("class", "amrFile");
         amr1.setAttribute("required", '');
         amr1.setAttribute("accept", ".mp3");



         var errorSpan = document.createElement("span");

         errorSpan.setAttribute("id", "amr" + newVersion + "_error");



         imgLabel.appendChild(img);

         imgLabel.appendChild(amr1);

         imgLabel.appendChild(errorSpan);



         imgDiv.appendChild(imgLabel);





         document.getElementById('uptrk').appendChild(div);

         document.getElementById('uptrk').appendChild(imgDiv);



         document.getElementById('numVersion').value = parseInt(numVersion) + 1;

      }


   }


   // Wait for the DOM to be ready

   $(function() {



      $("#addTrack").validate();





      $('#artWork').bind('change', function() {



         //this.files[0].size gets the size of your file.



      });





      $("#version1").rules("add", {

         required: true,

         messages: {

            required: "Select version"

         }

      });





      $("#genre").rules("add", {

         //  ignore: [],

         required: true,

         // minlength:2,

         messages: {

            required: "Select genre"

         }

      });



      /*$("#month").rules("add", {
      
            //  ignore: [],
      
              required:true,
      
      	// minlength:2,
      
              messages: {
      
                     required: "Select a month"
      
              }
      
           });
      */




      /* $("#year").rules("add", {
      
            //  ignore: [],
      
              required:true,
      
      	// minlength:2,
      
              messages: {
      
                     required: "Select a year"
      
              }
      
           });*/





      /*$("#day").rules("add", {
      
            //  ignore: [],
      
              required:true,
      
      	// minlength:2,
      
              messages: {
      
                     required: "Select a date"
      
              }
      
           });*/



      $("#artWork").rules("add", {

         //  ignore: [],

         required: true,

         //	 minImageWidth: 1000,

         // minlength:200,

         messages: {

            required: "Upload artwork image"

         }

      });







      $("#artist").rules("add", {

         required: true,

         messages: {

            required: "Please enter artist name"

         }

      });



      $("#title").rules("add", {

         required: true,

         messages: {

            required: "Please enter title"

         }

      });





      $("#producer").rules("add", {

         required: true,



         messages: {

            required: "Please enter producer"

         }

      });



      $("#trackTime").rules("add", {

         required: true,

         messages: {

            required: "Please enter track time"

         }

      });



      /*$("#bpm").rules("add", {
      
              required:true,
      
      	 messages: {
      
                     required: "Please enter bpm"
      
              }
      
           });
      */


      /* $("#album").rules("add", {
   
           required:true,
   
   	 messages: {
   
                  required: "Please enter album"
   
           }
   
        });*/



      /*  $("#website").rules("add", {
      
              required:true,
      
      	 messages: {
      
                     required: "Please enter website"
      
              }
      
           });*/

      $("#agree").rules("add", {

         required: true,

         messages: {

            required: "Please agree"

         }

      });





      $("#amr1").rules("add", {

         required: true,

         // accept: "image/jpeg, image/pjpeg",

         messages: {

            required: "Upload track"

         }

      });

      $("#amr2").rules("add", {

         required: true,

         // accept: "image/jpeg, image/pjpeg",

         messages: {

            required: "Upload track"

         }

      });



      $("#amr3").rules("add", {

         required: true,

         // accept: "image/jpeg, image/pjpeg",

         messages: {

            required: "Upload track"

         }

      });



      $("#amr4").rules("add", {

         required: true,

         // accept: "image/jpeg, image/pjpeg",

         messages: {

            required: "Upload track"

         }

      });



      /*  $("#amr2").rules("add", {
      
              required:true,
      
      	 messages: {
      
                     required: "Please agree"
      
              }
      
           });*/



      // artWork





      //





      //   });

      //

      var $theForm = $("#addTrack");
      $theForm.submit(function() {
         if ($theForm.valid()) {
            //console.log('addTrack_Validated');
            $('.add_track_button').attr('disabled', 'disabled');
            $('.processing_loader_gif').show();
            /* setTimeout(function() {
                $('.add_track_button').removeAttr('disabled');
            }, 8000); */
         } else {
            $("html, body").animate({
               scrollTop: 0
            });
            return false;
         }

      });

   });
</script>
<script>
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

   $("#artWork").change(function() {

      filePreview(this);

   });



   $("#amr1").change(function() {

      var fileName = document.getElementById("amr1").value;

      // document.getElementById("amr1_error").innerHTML = document.getElementById("amr1").value;

   });



   $("#amr2").change(function() {

      alert("hello");

      var fileName = document.getElementById("amr2").value;

      document.getElementById("amr2_error").innerHTML = document.getElementById("amr2").value;

   });


   $("#amr3").change(function() {

      var fileName = document.getElementById("amr3").value;

      document.getElementById("amr3_error").innerHTML = document.getElementById("amr3").value;

   });

   $("#amr4").change(function() {

      var fileName = document.getElementById("amr4").value;

      document.getElementById("amr4_error").innerHTML = document.getElementById("amr4").value;

   });


   $(document).on('focusout', '.artist_title', function() {
      var trkTitle = $('#title').val();
      var artistName = $('#artist').val();


      var dataPost = {
         "songArtist": artistName,
         "trackTitle": trkTitle
      };
      var dataString = JSON.stringify(dataPost);
      //console.log(dataString+'--GSGSG---OUT');

      if (trkTitle.length == 0 || artistName.length == 0) {
         return false;
      }
      $.ajax({
         url: 'checkTrackExistsclient',
         data: {
            myData: dataString,
            _token: '{{csrf_token()}}'
         },
         type: 'POST',
         success: function(response) {
            console.log(response);
            var returnedData = JSON.parse(response);
            //console.log(returnedData.data[0].id);			   
            if (returnedData.status == 'exists') {
               alert('Track name already exists!');
            } else if (returnedData.status == 'exists both') {
               //console.log(returnedData);				  
               var confDelete = confirm('Track Already Exists. Do you want to delete it?');
               if (confDelete) {
                  /* for(let ctr=0; ctr < returnedData.numRows; ctr++){ */
                  //console.log(confDelete);
                  $.ajax({
                     url: 'delete_track_client',
                     data: {
                        delTrackId: returnedData.data1[0].id,
                        _token: '{{csrf_token()}}'
                     },
                     type: 'POST',
                     success: function(response) {
                        var resData = JSON.parse(response);
                        if (resData.status == 'success') {
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
               } else {
                  console.log('No');
               }
            } else {
               //   $('#already').hide();
            }
         }

      });

   });
</script>
@endsection