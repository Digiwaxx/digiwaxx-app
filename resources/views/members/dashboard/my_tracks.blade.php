@extends('layouts.member_dashboard')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500&display=swap');
    
    body .new_design, .new_design h1,.new_design h2, .new_design h3, .new_design h4,
    .new_design h5,.new_design h6,.new_design p, .new_design span,.new_design div,.new_design a,.menu-con a,a.pr-link span{
        font-family: 'Quicksand', sans-serif;
    }
    /* #searchKey {
        height: 32px;
        width: 350px;
        font-size: 14px;
    } */ 
    a.pr-link span{
        font-weight: 100;
    }
    img.playButton {
        left: 15px;
    }
    .mem-trks a {
        padding: 10px !important;
    }
    @-webkit-keyframes priority {
          from {opacity: 1.0;}
          to {opacity: 0.0;}
        }
        .priority{
                color: #E5A4CE;
        	-webkit-animation-name: priority;
        	-webkit-animation-duration: 0.6s;
        	-webkit-animation-iteration-count:infinite;
        	-webkit-animation-timing-function:ease-in-out;
        	-webkit-animation-direction: alternate;
        }
    .rp-block h1 {
        font-style: unset;
        text-align:left;
        font-weight:500;
        padding:25px 30px;
    }
    .ml-2{
        margin-left:5px;
    }
    #searchKey{
        height:32px; width:100%; font-size:14px;
    }
    .sby-blk .input-group{
        display: flex;
        align-items: center;
    }
    input::-webkit-input-placeholder {
    	font-family: 'Quicksand', sans-serif !important;
    }
    
    input::-ms-input-placeholder { /* Microsoft Edge */
       font-family: 'Quicksand', sans-serif !important;
    }
    
    input:-ms-input-placeholder {/* IE 10+ */
    	font-family: 'Quicksand', sans-serif !important;
    }
    #searchKey {
        height: 32px;
        width: 350px;
        font-size: 14px;
    }
    .search_submit{
        background: #392152;
        color: #FFF;
    }
    .jp-image {
        margin: 15px;
    }
    .jp-timer {
        margin: 25px 0;
    }
    .jp-video .jp-controls {
        margin: 20px;
    }
    .jp-video .jp-details {
        margin: 28px 20px !important;
    }
    .jp-video .jp-toggles {
        margin: 30px 25px;
    }
    .page_title {
        margin: 25px 0 10px!important;
        font-weight: 100 !important;
        font-size: 18px !important;
    }
    .mem-trks{
        display: flex;
        justify-content: space-between;
        flex-wrap:wrap;
        background: rgba(255, 255, 255, 0.1);
    }
    .mem-dblk .mem-trks a {
           padding: 10px !important;
            font-weight: 100 !important;
            flex: 1;
            border-bottom: unset;
            white-space:nowrap;
            text-align: center;
    }
    .mem-dblk .mem-trks a.active {
           font-weight: 900 !important;
    }
    
    
    .fby-blk {
        margin: 0;
        padding-top: 20px;
    }
    .fby-blk {
        margin: 0;
        padding-top: 20px;
        display: flex;
        justify-content: flex-end;
    }
    .img_div {
        display: inline-block;
        float: left;
        padding-right: 20px;
    }
    .divider {
        margin: 0 15px;
    }
    .client-block .full_width_container{
    	max-width: 1600px;
    	width: 100%;
    }
    .rp-block .rp-menu li a{
        font-weight: 500;
        font-size: 14px;
    }
    .rp-block .rp-menu li a i, .rp-block .rp-menu li span i{
        color: #fff;
    }
    .mtop-list {
        margin: 0;
    }
    .client-block .padding-l-r-0{
        padding-left:0px;
        padding-right:0px;
    }
    .track--title{
        font-weight: 500;
    }

    .row-flex{
        display: flex;
    }
    .col-sidebar{
        width: 300px;
    }
    .col-full{
        width: 100%;
        padding: 0 20px;
    }
    .rp-block .rp-menu, .s-menu, .s-social{
    	border-left: none;
    	border-right: none;
    	padding: 30px 25px;
    	height: auto;
    	background: rgba(255,255,255,0.2);
    }
    .s-menu{
        border-top:none;
        margin-top:3px;
    }
    .rp-block .rp-menu li {
    	padding: 8px 0px;
    }
    .rp-block .rp-menu li a, .s-menu ul li a{
        font-family: 'Quicksand', sans-serif;
        font-weight: 500;
        font-size: 14px;
    }
    .s-menu ul li{
        padding-left:0;
        padding-right:0;
    }
    .s-menu ul li a i{
        font-size: 20px;
        vertical-align: -2px;
        margin-right: 15px;
    }
    .rew a:hover, .rew a:focus{
        color: #e5a4ce;
    }
    .mtop-list .item{
        border-bottom: 1px solid rgba(255,255,255,0.15);
        padding-bottom:10px;
        margin-bottom:10px;
    }

   /* #newest_tracks_tab_mem_dash{

    color: rgb(82 208 248);
    border-bottom: 1px solid rgb(82 208 248);

    }*/
    /* @media screen and (max-width:576px){
        .col-full{
            padding-left:0;
            padding-right:0;
        }
    } */
    
    /* @media screen and (max-width:1024px){
        .topbar ul {
            display: flex;
        }
        .topbar li.pro-btn a img {
            float: left;
        }
        .topbar li.pro-btn .pr-link:after {
            top: 0;
        }
        .topbar {
            padding: 0;
        }
        .logo {
            padding: 10px 0;
        }
        .jp-video .jp-details {
            margin: 28px 5px !important;
        }
        .jp-video .jp-toggles {
            margin: 30px 0px 0px 15px;
        }
        div.jp-video .jp-progress {
            width: 100px;
        }
        .ntf-sec, .mrp-sec {
            padding: 10px;
        }
        
    }
     */
    /* @media screen and (max-width:668px){
        .jp-timer {
            display: none;
        }
    }
    
    @media screen and (max-width:480px){
        .jp-timer {
            display: none;
        }
        .jp-video .jp-toggles,.jp-video .jp-controls {
            margin: 0;
            margin: 20px 0 0px;
        }
        .logo img {
            max-width: 150px;
        }
        .jp-image {
            margin: 15px 0;
        }
        .jp-btm {
            display: none;
        }
    } */
</style>
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
            <?php if(isset($alert_class))
                  { ?>
                    <div class="<?php echo $alert_class; ?>">
                    <p><?php echo $alert_message; ?></p>
                    </div>
            <?php } ?>
                <h2>MY TRACKS</h2>
              </div>
              <?php 
            //   echo '<pre>';
            // print_r($alert_class);
            // die;
                                            ?>
              
              <div class="tabs-section">
                <div class="tab-content" id="myTabContent">
                  <div class="tab-top-sec">
                    <div class="search-list">                        
                    </div>

                  </div>
                  <div class="tab-pane fade show active" id="new-tracks" role="tabpanel" aria-labelledby="new-tracks-tab">
                               <div class="mtop-list">
                                   <?php
                                //   print_r($tracks);
                                if(isset($no_records_found) && $no_records_found == 1){
                                    echo '<h4>No Tracks Submitted!</h4>';
                                }else{
                                    $i = 1;
                                    foreach ($tracks['data'] as $track) { ?>
                                       <div class="item">
                                           <div class="row">
                                               
                                               <div class="col-12">
                                                    <div class="img_div">
                                                           <?php

                                                                if(!empty($track->imgpage)){

                                                                    if (file_exists(base_path('ImagesUp/'.$track->imgpage))){
                                                                
                                                                        $img_get = asset('ImagesUp/'.$track->imgpage);  
                                                                    }
                                                                    elseif(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
                                                                        $img_get = asset('ImagesUp/' .$track->album_page_image);
                                                                    }
                                                                    else{
                                                                        $img_get = asset('public/images/noimage-avl.jpg');
                                                                    }

                                                                }
                                                                elseif(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
                                                                    $img_get = asset('ImagesUp/' .$track->album_page_image);
                                                                }    
                                                                else{
                                                                    $img_get = asset('public/images/noimage-avl.jpg');
                                                                }
                                                               

                                                            // if ($mp3s[$track->id]['numRows'] > 0) {
                                                            //     $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                            //     $fileid = (int) $trackLocation;
                                                            //     if (strpos($trackLocation, '.mp3') !== false) {
                                                            //         $trackLink =url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                            //     } else if ((int) $fileid) {
                                                            //         $trackLink =url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                            //     }else {
                                                            //         $trackLink = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                            //     }
                                                            ?>
                                                            <a href="javascript:void(0);" >
                                                                   <img src="<?php echo $img_get; ?>" width="80" height="80" />
                                                                   <img class="playButton" src="assets/img/play-btn.png" width="80" height="80" />
                                                               </a>
                                                               
                                                    </div> 
                                                   
                                                   
                                                   
                                                   
                                                   
                                                   <?php if(!empty(trim(urldecode($track->title)))){ ?>
                                                        <p class="track--title"><strong><a href="<?php //echo $review_link; ?>"><?php echo urldecode($track->title); ?></a></strong></p>
                                                        <?php 
                                                    }
                                                    if(!empty(trim(urldecode($track->artist)))){ ?>
                                                        <span class="text-dim">Artist: <a href="<?php //echo $review_link; ?>"><?php echo urldecode($track->artist); ?></a><span class="divider">|</span></span>
                                                    <?php 
                                                    }
                                                    if(!empty(trim(urldecode($track->album)))){ ?>
                                                        <span class="text-dim">Album: <a href="<?php //echo $review_link; ?>"><?php echo urldecode($track->album); ?></a><span class="divider">|</span></span>
                                                    <?php 
                                                    }
                                                    if(!empty(trim(urldecode($track->producer)))){ ?>
                                                        <span class="text-dim">Producer: <a href="<?php //echo $review_link; ?>"><?php echo urldecode($track->producer); ?></a><span class="divider">|</span></span>
                                                    <?php 
                                                    }
                                                    ?>                                                    
                                                    
                                                    <?php 
                                                   
                                                    //   if (in_array($track->id, $tracks['downloaded'])){
                                                    //         echo "<span class='download-success'>Downloaded</span>";
                                                    //     }
                                                       
                                                       ?>
                                                       <span><?php echo urldecode($track->label); ?></span>
                                                       <p><small class="text-dim"><?php
                                                            $addedOn = explode(' ', $track->added);
                                                            $addedDate = explode('-', $addedOn[0]);
                                                            echo $addedDate = $addedDate[1] . '/' . $addedDate[2] . '/' . $addedDate[0];
                                                            ?></small></p>
                                                            
                                                        <?php if($track->priority==1){ ?>
                                                                <div class="priority" >Priority</div>
                                                        <?php
                                                        }
                                                        ?>
                                                    
                                                    
                                               </div>
                                               
                                                   
                                                        
                                                      
                                               <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                   <div class="text-md-right rew">
                                                       <?php $tid = $track->trackSubmittedId; ?>
                                                       <a href="<?php echo url("upload_media_edit?tId=" . $tid); ?>">
                                                           <p style="color: rgb(82 208 248); font-size: 14px;">Edit</p>
                                                       </a>
                                                       
                                                       <?php
                                                        if ($downloads[$track->id] > 0) {
                                                            $review_link = url("Member_track_review_view?tid=" . $track->id);
                                                            $text = 'View Reviews';
                                                        } else {
                                                            $review_link = '';
                                                            $text = 'No Reviews yet';
                                                        }
                                                        
                                                        
                                                        ?>
                                                       <a href="<?php echo $review_link ?>">
                                                           <p style="color: rgb(82 208 248); font-size: 14px;"><?php echo $text; ?></p>
                                                       </a>
                                                   </div>
                                               </div>
                                           </div><!-- eof row -->
                                       </div><!-- eof item -->
                                   <?php $i++;
                                    } 
                                }
                                    ?>
                                   <!--  <div class="smore text-right"><a href="#">SEE MORE <i class="fa fa-caret-right"></i></a></div>-->
                               </div><!-- eof mtop-list -->
                    <!-- Newest Tracks ends here  -->
                   </div>                  
                </div>

              </div>
              <!---tab section end--->
              <!--album-download-->
              <div class="album-d-sec">
                <div class="heading-border">
                  <h4>STAFF PICKS</h4>
                </div>
                
                <div class="row">
                        <div class="col-12">
                          <!--download tracks-->

                          <?php if ($staffTracks['numRows'] > 0) { ?>
                          <div class="stpk-blk ntf-lst-blk" >
                              <div class="stpk-con">
                                  <div class="row">
                                      <?php
                                      $i = 1;
                                      foreach ($staffTracks['data'] as $track) {
                                          if ($i < 3) {
                                              // if ($reviews[$track->id] > 0) {
                                              $href = url("Member_track_download_front_end?tid=" . $track->id);
                                              $label = 'DOWNLOAD';
                                              // } else {
                                              //     $href = url("Member_track_review?tid=" . $track->id);
                                              //     $label = 'LEAVE REVIEW TO UNLOCK DOWNLOAD';
                                              // }
                                              if ($mp3s[$track->id]['numRows'] > 0) {
                                                  $var1 = urldecode($track->title);
                                                  $var2 = urldecode($track->artist);
                                                  $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                  $fileid = (int) $trackLocation;
                                                  if (strpos($trackLocation, '.mp3') !== false) {
                                                      $var3 =url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                  } else if ((int) $fileid) {
                                                      $var3 =url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                  } else {
                                                      $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                  }
                                                  $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

                                                  if(!empty($track->imgpage)){

                                                    if (file_exists(base_path('ImagesUp/'.$track->imgpage))){
                                                
                                                        $var5 = asset('ImagesUp/'.$track->imgpage);  
                                                    }
                                                    elseif(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
                                                        $var5 = asset('ImagesUp/' .$track->album_page_image);
                                                    }
                                                    else{
                                                        $var5 = asset('public/images/noimage-avl.jpg');
                                                    }

                                                }
                                                elseif(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
                                                    $var5 = asset('ImagesUp/' .$track->album_page_image);
                                                }
                                                else{
                                                    $var5 = asset('public/images/noimage-avl.jpg');
                                                }


                                                 // $var5 = asset("ImagesUp/" . $track->imgpage);

                                              } 
                                              else {
                                                  $var1 = urldecode($track->title);
                                                  $var2 = urldecode($track->artist);
                                                  $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                  $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';
                                                  $var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';
                                              }
                                              if (strlen($track->thumb) > 4) {
                                                  $src = asset('thumbs/' . $track->thumb);
                                              } else {
                                                  $src = asset('ImagesUp/' . $track->imgpage);
                                              }

                                             // dd($src);

                                              if(!empty($track->imgpage)){


                                                if (file_exists(base_path('ImagesUp/'.$track->imgpage))){
                                                                                                          
                                                  $src = $src;  
                                            
                                                }
                                               else if (file_exists(base_path('thumbs/'.$track->imgpage))){
                                                                                                            
                                                  $src = $src;  
                                            
                                                }
                                                else{
                                                  $src = asset('public/images/noimage-avl.jpg'); 
                                                }
                                          
                                              }
                                          
                                              else{
                                          
                                                $src = asset('public/images/noimage-avl.jpg');
                                              }
                                             
                                      ?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                    <div class="tdpic">
                                        <a href="javascript:void(0);" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php echo $var5; ?>','<?php echo $track->id; ?>')">
                                            <img src="<?php echo $src; ?>" style="height:120px;" class="img-responsive">
                                            <span class="overlay"></span>
                                            <span class="overlay-text">
                                                <span class="album"><?php
                                                                    $album = strtoupper(urldecode($track->album));
                                                                    if (strlen($album) > 13) {
                                                                        $album = substr($album, 0, 13);
                                                                    }
                                                                    echo $album;
                                                                    ?></span>
                                                <span class="artist"><?php
                                                                    $artist = strtoupper(urldecode($track->artist));
                                                                    if (strlen($artist) > 13) {
                                                                        $artist = substr($artist, 0, 13);
                                                                    }
                                                                    echo $artist;
                                                                    ?></span>
                                                <span class="dloads"><?php echo $trackData[$track->id]['downloads']; ?></span>
                                                <span class="dloadst">dloads</span>
                                            </span>
                                        </a>
                                        <a href="<?php echo $href; ?>" class="dwd"><i class="fa fa-cloud-download"></i></a>
                                    </div>
                                </div>
                        <?php }
                            $i++;
                        } ?>
                                  </div><!-- eof row -->
                              </div><!-- eof stpk-con -->
                              <!-- <div class="smore text-right"><a href="<?php echo url("member_staff_picks"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a></div> -->
                          </div><!-- eof stpk-blk -->
                      <?php } ?>
                    
              </div>
        

                </div>
                <div class="album-d-more">
                <a href="<?php echo url("member_staff_picks"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a>
                </div>
              </div>
              <!--album-download-->
              <div class="album-d-sec">
                <div class="heading-border">
                  <h4>Selected For You</h4>
                </div>
                
                <div class="row">
                  <div class="col-12">
                    <!--download tracks-->
                    <?php if ($youTracks['numRows'] > 0) { ?>
                        <div class="stpk-blk ntf-lst-blk" >
                            <div class="stpk-con">
                                <div class="row">
                                    <?php
                                    $i = 1;
                                    foreach ($youTracks['data'] as $track) {
                                        if ($i < 3) {
                                            // if ($reviews[$track->id] > 0) {
                                            $href = url("Member_track_download_front_end?tid=" . $track->id);
                                            $label = 'DOWNLOAD';
                                            // } else {
                                            //     $href = url("Member_track_review?tid=" . $track->id);
                                            //     $label = 'LEAVE REVIEW TO UNLOCK DOWNLOAD';
                                            // }

                                            if(!empty($mp3s[$track->id]['numRows'])){

                                            if ($mp3s[$track->id]['numRows'] > 0) {
                                                $var1 = urldecode($track->title);
                                                $var2 = urldecode($track->artist);
                                                $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                $fileid = (int) $trackLocation;
                                                if (strpos($trackLocation, '.mp3') !== false) {
                                                    $var3 =url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                } else if ((int) $fileid) {
                                                    $var3 =url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                } else {
                                                    $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                }
                                                $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

                                                if(!empty($track->imgpage)){

                                                    if (file_exists(base_path('ImagesUp/'.$track->imgpage))){
                                                
                                                        $var5 = asset('ImagesUp/'.$track->imgpage);  
                                                    }
                                                    elseif(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
                                                        $var5 = asset('ImagesUp/' .$track->album_page_image);
                                                    }
                                                    else{
                                                        $var5 = asset('public/images/noimage-avl.jpg');
                                                    }

                                                }
                                                elseif(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
                                                    $var5 = asset('ImagesUp/' .$track->album_page_image);
                                                }
                                                else{
                                                    $var5 = asset('public/images/noimage-avl.jpg');
                                                }

                                               // $var5 = asset("ImagesUp/" . $track->imgpage);
                                            } 

                                        }
                                            
                                            
                                            else {
                                                $var1 = urldecode($track->title);
                                                $var2 = urldecode($track->artist);
                                                $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';
                                                $var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';
                                            }

                                    
                                    ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="tdpic">
                                                    <a href="javascript:void(0);" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php echo $var5; ?>','<?php echo $track->id; ?>')">
                                                        <img src="<?php echo $var5; ?>" width="108" height="108" style="height:138px;" class="img-responsive">
                                                        <span class="overlay"></span>
                                                        <span class="overlay-text">
                                                            <span class="album"><?php
                                                                                $album = strtoupper(urldecode($track->album));
                                                                                if (strlen($album) > 13) {
                                                                                    $album = substr($album, 0, 13);
                                                                                }
                                                                                echo $album;
                                                                                ?></span>
                                                            <span class="artist"><?php
                                                                                $artist = strtoupper(urldecode($track->artist));
                                                                                if (strlen($artist) > 13) {
                                                                                    $artist = substr($artist, 0, 13);
                                                                                }
                                                                                echo $artist;
                                                                                ?></span>
                                                            <span class="dloads"><?php echo $trackData[$track->id]['downloads']; ?></span>
                                                            <span class="dloadst">dloads</span>
                                                        </span>
                                                    </a>
                                                    <a href="<?php echo $href; ?>" class="dwd"><i class="fa fa-cloud-download"></i></a>
                                                </div>
                                            </div>
                                    <?php }
                                        $i++;
                                    } ?>
                                </div><!-- eof row -->
                            </div><!-- eof stpk-con -->
                            <!-- <div class="smore text-right"><a href="<?php echo url("member_selected_for_you"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a></div> -->
                        </div>
                    <?php } ?>
                  </div>
                 

                </div>
                <div class="album-d-more">
                <a href="<?php echo url("member_selected_for_you"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a>
                </div>
              </div>
                         
           </div>
         </div>
       </div>
     </div>
	 </section>
	 
@endsection