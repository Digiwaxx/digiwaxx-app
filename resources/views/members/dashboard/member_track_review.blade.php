

@extends('layouts.member_dashboard')
@section('content')
<style>
   .download_link, .download_link:hover
   {
   color: #FFF;
   font-weight: bold;
   display: block;
   margin-top: 6px;
   /*width:111px;*/
   }
   #form-toggle
   {
   cursor: pointer;
   }
   h2.logo_headings {
   width: 100%;
   margin-bottom: 10px;
   }
   .col-auto {
   margin: 6px 10px;
   /*margin:2px;*/
   }
   .col-auto img {
   height: 55px;
   width: auto;
   }
   .logos {
   display: flex;
   flex-wrap: wrap;
   margin: 0;
   margin-bottom: 15px;
   justify-content: flex-start;
   }
   .br-theme-bars-square .br-widget a {
   display: block;
   width: 4px;
   height: 30px;
   background: url(../img/ratingw.png) no-repeat scroll 0 0 rgb(0 0 0 / 0%);
   background: rgb(237 210 226);
   float: left;
   margin: 0px;
   text-decoration: none;
   font-size: 14px;
   font-weight: 400;
   line-height: 2;
   text-align: center;
   color: rgb(187 206 251);
   font-weight: 600;
   }
   .br-theme-bars-square .br-widget a.br-active, .br-theme-bars-square .br-widget a.br-selected {
   /* background: url(../img/rating.png) no-repeat scroll 0 0 rgb(0 0 0 / 0%); */
   background: rgb(179 47 133);
   }
</style>
<section class="main-dash">
   @include('layouts.include.sidebar-left')
   <?php
      $link_text = 'UNLOCK DOWNLOAD';
      $submit_review_text = 'SUBMIT & UNLOCK DOWNLOAD';
      $member_session_pkg = Session::get('memberPackage');
      if(isset($member_session_pkg) && $member_session_pkg > 2)
      {
      $submit_review_text = 'SUBMIT REVIEW';
      $link_text = 'WRITE A REVIEW';
      }
      ?>
   <div class="dash-container">

      <div class="row">
         <div class="col-12">
            <div class="tabs-section">
               <!-- START MIDDLE BLOCK -->
               <title>Untitled Document</title>
               <div class="col-lg-12 col-md-12">
                  <div class="mem-dblk f-block" style="margin-bottom:40px;">
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                           <?php
                           if(!empty($tracks['data'][0]->coverimage)){
                              if(strlen($tracks['data'][0]->coverimage)>4 && 0)
                              
                                { 
                                 
                                 $img = asset('ImagesUp/'.$tracks['data'][0]->coverimage); ?>
                           <img src="<?php echo $img; ?>" class="" style="height:200px; width:557px !important;">
                           <?php  } }?>
                        </div>
                     </div>
                     <h1>REVIEW TRACK (AND UNLOCK DOWNLOAD)</h1>
                     <!--mCustomScrollbar-->
                     <div class="trk-info-blk" style="height:auto; overflow:hidden;">
                        <div class="row">
                           <div class="col-lg-5 col-md-5 col-sm-4 col-12">
                              <?php
                                 if(!empty($tracks['data'][0]->pCloudFileID)){
                                    $img_get= url('/pCloudImgDownload.php?fileID='.$tracks['data'][0]->pCloudFileID);
                                 }else{
                                    $img_get = asset('public/images/noimage-avl.jpg');
                                 }
                                 ?>
                              <img src="<?php echo $img_get; ?>" class="img-responsive">
                           </div>
                           <div class="col-lg-7 col-md-7 col-sm-8 col-12">
                              <div class="track-info-wrap">
                                 <h1>TRACK INFO</h1>
                                 <div class="trk-det">
                                     <?php
                                    
                                     if(trim($tracks['data'][0]->artist) !=""){ ?>
                                    <p class="t1"><label>Artist: </label> <span> <?php echo urldecode($tracks['data'][0]->artist); ?> </span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->title) !=""){ ?>
                                    <p class="t1"><label>Title: </label> <span><?php echo urldecode($tracks['data'][0]->title); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->producer) !=""){ ?>
                                    <p class="t1"><label>Producer: </label> <span><?php echo urldecode($tracks['data'][0]->producer); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->writer) !=""){ ?>
                                    <p class="t1"><label>Writer: </label> <span><?php echo urldecode($tracks['data'][0]->writer); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->time) !=""){
                                        $trkTime = trim(urldecode($tracks['data'][0]->time));
                                        $trkTimeArr = explode(':', $trkTime);
                                        
                                        $hrs = '';
                                        $mins = '';
                                        $secs = '';
                                        if(count($trkTimeArr) > 0 && count($trkTimeArr) <=2){
                                            if(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] > 1){
                                                $mins = (int)$trkTimeArr[0].'mins';
                                            }elseif(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] == 1){
                                                $mins = (int)$trkTimeArr[0].'min';
                                            }
                                            
                                            if(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] > 1){
                                                $secs = (int)$trkTimeArr[1].'secs';
                                            }elseif(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] == 1){
                                                $secs = (int)$trkTimeArr[1].'sec';
                                            }
                                        }elseif(count($trkTimeArr) > 0 && count($trkTimeArr) <= 3){
                                            if(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] > 1){
                                                $hrs = (int)$trkTimeArr[0].'hrs';
                                            }elseif(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] == 1){
                                                $hrs = (int)$trkTimeArr[0].'hr';
                                            }
                                            
                                            if(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] > 1){
                                                $mins = (int)$trkTimeArr[1].'mins';
                                            }elseif(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] == 1){
                                                $mins = (int)$trkTimeArr[1].'min';
                                            }
                                            
                                            if(isset($trkTimeArr[2]) && (int)$trkTimeArr[2] > 1){
                                                $secs = (int)$trkTimeArr[2].'secs';
                                            }elseif(isset($trkTimeArr[2]) && (int)$trkTimeArr[2] == 1){
                                                $secs = (int)$trkTimeArr[2].'sec';
                                            }                                          
                                        }
                                        
                                        $displayTrkTime = $hrs.' '.$mins.' '.$secs;
                                    ?>
                                    <p class="t1"><label>Time: </label> <span><?php echo $displayTrkTime; ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->label) !=""){ ?>
                                    <p class="t1"><label>Label </label> <span><?php echo urldecode($tracks['data'][0]->label); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->album) !=""){ ?>
                                    <p class="t1"><label>Album: </label> <span><?php echo urldecode($tracks['data'][0]->album); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->notes) !=""){ ?>
                                    <p class="t1"><label>Caption: </label> <span><?php echo urldecode($tracks['data'][0]->notes); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->link) !=""){ ?>
                                    <p class="t1"><label>Web Link: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->link); ?>"><?php echo urldecode($tracks['data'][0]->link); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->link1) !=""){ ?>
                                    <p class="t1"><label>Web Link1: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->link1); ?>"><?php echo urldecode($tracks['data'][0]->link1); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->link2) !=""){ ?>
                                    <p class="t1"><label>Web Link2: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->link2); ?>"><?php echo urldecode($tracks['data'][0]->link2); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->videoURL) !=""){ ?>
                                    <p class="t1"><label>Video: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->videoURL); ?>"><?php echo urldecode($tracks['data'][0]->videoURL); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->facebookLink) !=""){ ?>
                                    <p class="t1"><label>Facebook: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->facebookLink); ?>"><?php echo urldecode($tracks['data'][0]->facebookLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->twitterLink) !=""){ ?>
                                    <p class="t1"><label>Twitter: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->twitterLink); ?>"><?php echo urldecode($tracks['data'][0]->twitterLink); ?></a> </span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->instagramLink) !=""){ ?>
                                    <p class="t1"><label>Instagram: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->instagramLink); ?>"><?php echo urldecode($tracks['data'][0]->instagramLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->tiktokLink) !=""){ ?>
                                    <p class="t1"><label>Tik Tok: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->tiktokLink); ?>"><?php echo urldecode($tracks['data'][0]->tiktokLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->snapchatLink) !=""){ ?>
                                    <p class="t1"><label>Snapchat: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->snapchatLink); ?>"><?php echo urldecode($tracks['data'][0]->snapchatLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->othersLink) !=""){ ?>
                                    <p class="t1"><label>Others: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->othersLink); ?>"><?php echo urldecode($tracks['data'][0]->othersLink); ?></a></span></p>
                                    <?php } ?>																		<?php if(trim($tracks['data'][0]->applemusicLink) !=""){ ?>										<p class="t1"><label> Apple Music: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->applemusicLink); ?>"><?php echo urldecode($tracks['data'][0]->applemusicLink); ?></a></span></p>									<?php } ?>																		<?php if(trim($tracks['data'][0]->amazonLink) !=""){ ?>										<p class="t1"><label> Amazon: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->amazonLink); ?>"><?php echo urldecode($tracks['data'][0]->amazonLink); ?></a></span></p>									<?php } ?>									<?php if(trim($tracks['data'][0]->spotifyLink) !=""){ ?>										<p class="t1"><label> Spotify: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->spotifyLink); ?>"><?php echo urldecode($tracks['data'][0]->spotifyLink); ?></a></span></p>									<?php } ?>						
                                    <p class="t1"><label>Member Points: </label> <span>1 point</span></p>
                                 </div>
                                 <!-- trk-det -->
                              </div>
                           </div>
                        </div>
                        <?php $embedlink = $tracks['data'][0]->embedvideoURL; 
                        if(!empty($embedlink) && preg_match('/<iframe .*?>/', $embedlink)){
                        ?>
                        <div class="row">
                           <div class="col-12">
                              <div class="text-center mt-4">
                                  <?php echo $embedlink; ?>
                              </div>
                           </div>
                        </div>
                        <?php } ?>                        
                        <div class="rew-trks">
                           <?php
                              if(!empty($video)){
                              if(strlen($video)>10)
                              
                              {
                              
                              
                              
                              $str = strpos($video,"?v=");
                              
                              
                              
                              if($str>0)
                              
                              {
                              
                                $videoId = explode("?v=",$video);
                              
                                $videoId = $videoId[1];
                              
                              }
                              
                              else
                              
                              {
                              
                                $videoId = explode("/",$video);
                              
                                $num = count($videoId);
                              
                                $videoId = $videoId[$num-1];
                              
                              }
                              
                              
                              
                              ?>
                           <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $videoId; ?>" frameborder="0" allowfullscreen></iframe>
                           <?php }
                              }
                              
                              echo "<div class='logos row'>";
                                  if(!empty($logos['data'])){ ?>
                           <h2 class="logo_headings">Logos</h2>
                           <?php
                        //   pArr($logos['data']);die();
                              foreach($logos['data'] as $logoo){ ?>
                           <div class="col-auto">
                              <div id="<?php echo $logoo->id; ?>">
                                 <?php
                                    if(!empty($logoo->product_name)) echo $logoo->product_name;  
                                    
                                    if(!empty($logoo->pCloudFileID_logo)){
                                        $artWorkk= url('/pCloudImgDownload.php?fileID='.$logoo->pCloudFileID_logo);
                                    }
                                    else if(!empty($logoo->img)){
                                    
                                     if (file_exists(base_path('ImagesUp/'.$logoo->img))){
                                    
                                       $artWorkk = asset('/Logos/'.$logoo->img);  
                                     }
                                     else{
                                         $artWorkk = asset('public/images/noimage-avl.jpg');
                                     }
                                    
                                    }
                                    else{
                                     $artWorkk = asset('public/images/noimage-avl.jpg');
                                    }
                                    
                                    if(!empty($logoo->url)){ ?>
                                       <a href="<?= urldecode($logoo->url); ?>" target="_blank"> 
                                <?php
                                    }
                                ?>
                                 <img class="<?= urldecode($logoo->url); ?>" src="<?php echo $artWorkk; ?>" width="50" height="56" />
                                 <?php 
                                    if(!empty($logoo->url)){ ?>
                                        </a>
                                  <?php
                                    }
                                 ?>
                              </div>
                           </div>
                           <?php 
                              }
                              }
                              echo "</div>";
                              
                              
                              foreach($mp3s['data'] as $track)
                              {
                              // echo url('AUDIO/'.$track->location);
                              $member_session_pkg = Session::get('memberPackage');
                              if($member_session_pkg<2) {
                              
                              
                              
                              if(1) { $fileid = (int)$track->location; ?>
                              <?php //die();?>
                           <div class="rtrk-item">
                              <p>Artist: <?php echo urldecode($track->artist); ?>, Title: <?php echo urldecode($track->title); ?>, Version: <?php echo urldecode($track->version); ?></p>
                              <div class="media-wrapper">
                                 <audio id="player2" preload="none" controls  style="max-width:100%;">
                                    <?php if (strpos($track->location, '.mp3') !== false) { ?>
                                    <source src="<?php echo asset('AUDIO/'.$track->location); ?>" type="audio/mp3">
                                    <?php } else { $getlink = ''; if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                    <source src="<?php echo $getlink; //asset('AUDIO/'.$track->location); ?>" type="audio/mp3">
                                    <?php } ?>
                                 </audio>
                                 <?php
                                    $member_session_pkg = Session::get('memberPackage');
                                    if(isset($member_session_pkg) && $member_session_pkg > 2)
                                    {
                                    ?>
                                 <?php if (strpos($track->location, '.mp3') !== false) { ?>
                                 <a class="download_link" href="<?php echo url('Download_member_track?track='.$track->location.'&mp3Id='.$track->id.'&trackId='.$_GET['tid'].'&title='.$track->title); ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                 <?php } else { $getlink = ''; if(!empty($fileid)){ $getlink = url('Download_member_track?track='.$fileid.'&mp3Id='.$track->id.'&trackId='.$_GET['tid'].'&pcloud=true'); } ?>
                                 <a class="download_link" href="<?php echo $getlink; ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                 <?php } ?>
                                 <?php
                                    }
                                    ?>
                              </div>
                           </div>
                           <!-- eof rtrk-item -->
                           <?php } } else {
                               
                               
                              if($track->preview==1) { $autoplay = ''; } else { $autoplay = ''; } ?>
                           <div class="rtrk-item">
                              <p>Artist: <?php echo urldecode($track->artist); ?>, Title: <?php echo urldecode($track->title); ?>, Version: <?php echo urldecode($track->version); ?></p>
                              <div class="media-wrapper12">
                                 <audio id="player2" preload="none" controls <?php  echo $autoplay; ?> style="max-width:100%;">
                                    <?php if (strpos($track->location, '.mp3') !== false) { ?>
                                    <source src="<?php echo asset('AUDIO/'.$track->location); ?>" type="audio/mp3">
                                    <?php } else { $fileid = (int)$track->location;  $getlink = ''; if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                    <source src="<?php echo $getlink; //url('AUDIO/'.$track->location); ?>" type="audio/mp3">
                                    <?php } ?>
                                 </audio>
                                 <?php
                                    $member_session_pkg = Session::get('memberPackage');
                                    if(isset($member_session_pkg) && $member_session_pkg > 2)
                                    {
                                    ?>
                                 <?php if (strpos($track->location, '.mp3') !== false) { ?>
                                 <a class="download_link" href="<?php echo url('Download_member_track?track='.$track->location.'&mp3Id='.$track->id.'&trackId='.$_GET['tid'].'&title='.$track->title); ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                 <?php } else { $fileid = (int)$track->location; $getlink = ''; if(!empty($fileid)){ $getlink = url('Download_member_track?track='.$fileid.'&mp3Id='.$track->id.'&trackId='.$_GET['tid'].'&pcloud=true'); } ?>
                                 <a class="download_link" href="<?php echo $getlink; ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                 <?php } ?>
                                 <?php
                                    }
                                    ?>
                              </div>
                           </div>
                           <!-- eof rtrk-item -->
                           <?php } } ?>
                        </div>
                        <!-- eof rew-trks -->
                        <?php
                           $member_session_pkg = Session::get('memberPackage');
                           if(isset($member_session_pkg) && $member_session_pkg > 2)
                           {
                           ?>
                        <h1 id="form-toggle"><?php echo $link_text;?> +</h1>
                        <div class="rew-form togglethis" style="display:none;">
                           <?php
                              }
                              else
                                {
                                  ?>
                           <h1>UNLOCK DOWNLOAD</h1>
                           <div class="rew-form">
                              <?php
                                 }
                                 ?>
                              <p class="desc">
                                 <span>All questions and comments are required to un-lock mp3's below.</span>
                                 Before you begin, we want you to know that your honest review of this song is very much appreciated. This section provides a platform for you to give your honest opinion and review directly to the record labels, management companies, and artists associated to this project. What you feel and what you say is totally up to you. Remember All Reviews are Good Reviews.
                              </p>
                              <form action="" method="post" id="reviewForm">
                                 <?php /*
                                    <div class="q-item">
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">1. WHERE DID YOU HEAR THIS SONG FIRST? <span class="man"></span></p>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                            <div class="form-group">
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="streaming_platform" value="streaming_platform" type="radio">
                                                        Streaming   platform (apple music, tidal, spotify, pandora, etc)
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="digital_waxx_music_service" value="digital_waxx_music_service" type="radio">
                                                        Digital Waxx Music Service
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="commercial_radio" value="commercial_radio" type="radio">
                                                       Commercial Radio
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="satellite_radio" value="satellite_radio" type="radio">
                                                       Satellite Radio
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="college_radio" value="college_radio" type="radio">
                                                       College Radio
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="mixtape" value="mixtape" type="radio">
                                                       Mixtape
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="club" value="club" type="radio">
                                                       Club
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="internet" value="internet" type="radio">
                                                       Internet
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="video" value="video"  type="radio">
                                                       Music Video (vevo, YouTube, worldstar, etc)
                                                      </label>
                                                   </div>
                                                   <div class="radio dja">
                                                      <label>
                                                <input name="whereHeard" id="where_heard_other" value="other"  type="radio">
                                                       Other
                                                      </label>
                                                   </div>
                                            </div>
                                                   <!-- <select name="whereHeard" size="1" id="whereHeard" class="rt1 selectpicker">
                                    <option value="">Select An Option</option>
                                    <option value="digital_waxx_music_service">Digital Waxx Music Service
                                    </option><option value="commercial_radio">Commercial Radio
                                    </option><option value="satellite_radio">Satellite Radio
                                    </option><option value="college_radio">College Radio
                                    </option><option value="mixtape">Mixtape
                                    </option><option value="club">Club
                                    </option><option value="internet">Internet
                                    </option><option value="video">Video
                                    </option></select>-->
                                            </div>
                                        </div>
                                     </div> <!-- eof q-item --> */ ?>
                                 <div class="q-item">
                                    <div class="row">
                                       <div class="col-lg-7 col-md-7 col-sm-7">
                                          <p class="q1">1. WHAT DO YOU THINK ABOUT THIS SONG? <span class="man"></span></p>
                                          <ul class="pts">
                                             <li>1= WACK (WEAK, BASURA, TRASH): Not going to get spins.</li>
                                             <li>2= ALMOST WACK Not terrible, worth a few test spins in the club or mix.</li>
                                             <li>3= COOL Worthy of getting some spins, will see how it reacts.</li>
                                             <li>4= DOPE A strong record, will get behind this.</li>
                                             <li>5= CLASSIC Will support this heavy, could go the distance.</li>
                                          </ul>
                                       </div>
                                       <div class="col-lg-5 col-md-5 col-sm-5">
                                          <div class="rat">
                                             <!--
                                                <select id="example-square" name="whatRate" autocomplete="off" class="digi-rating">
                                                
                                                  <option value=""></option>
                                                
                                                  <option value="1">1</option>
                                                
                                                  <option value="2">2</option>
                                                
                                                  <option value="3">3</option>
                                                
                                                  <option value="4">4</option>
                                                
                                                  <option value="5">5</option>
                                                
                                                </select>-->
                                             <select id="example-square" name="whatRate" autocomplete="off" class="digi-rating">
                                                <option value="1.1">1.1</option>
                                                <option value="1.2">1.2</option>
                                                <option value="1.3">1.3</option>
                                                <option value="1.4">1.4</option>
                                                <option value="1.5">1.5</option>
                                                <option value="1.6">1.6</option>
                                                <option value="1.7">1.7</option>
                                                <option value="1.8">1.8</option>
                                                <option value="1.9">1.9</option>
                                                <option value="2">2</option>
                                                <option value="2.1">2.1</option>
                                                <option value="2.2">2.2</option>
                                                <option value="2.3">2.3</option>
                                                <option value="2.4">2.4</option>
                                                <option value="2.5">2.5</option>
                                                <option value="2.6">2.6</option>
                                                <option value="2.7">2.7</option>
                                                <option value="2.8">2.8</option>
                                                <option value="2.9">2.9</option>
                                                <option value="3">3</option>
                                                <option value="3.1">3.1</option>
                                                <option value="3.2">3.2</option>
                                                <option value="3.3">3.3</option>
                                                <option value="3.4">3.4</option>
                                                <option value="3.5">3.5</option>
                                                <option value="3.6">3.6</option>
                                                <option value="3.7">3.7</option>
                                                <option value="3.8">3.8</option>
                                                <option value="3.9">3.9</option>
                                                <option value="4">4</option>
                                                <option value="4.1">4.1</option>
                                                <option value="4.2">4.2</option>
                                                <option value="4.3">4.3</option>
                                                <option value="4.4">4.4</option>
                                                <option value="4.5">4.5</option>
                                                <option value="4.6">4.6</option>
                                                <option value="4.7">4.7</option>
                                                <option value="4.8">4.8</option>
                                                <option value="4.9">4.9</option>
                                                <option value="5">5</option>
                                             </select>
                                          </div>
                                          <div style="clear:both;"></div>
                                          <p class="rtf" style="padding-left:10px;">RATE FROM 1 TO 5 <i class="fa fa-caret-up"></i></p>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- eof q-item -->
                                 <?Php /* <div class="q-item">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">3. DO YOU THINK THIS RECORD WILL GET ANY SUPPORT? <span class="man"></span></p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                            <div class="form-group">
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="goDistance" id="optionsRadios1" value="yes" type="radio" onClick="getDistanceInput(1)">
                                                    YES <br> <span>THIS RECORD WILL GET SUPPORT</span>
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="goDistance" id="optionsRadios2" value="no" type="radio" onClick="getDistanceInput(0)">
                                                    NO <br> <span>THIS RECORD IS DEAD ON ARRIVAL!</span>
                                                  </label>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                    </div><!-- eof q-item -->
                                    <div class="q-item" id="goDistanceInput" style="display:none;">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">3.1. WHAT FORMAT DO YOU THINK WILL HELP BREAK THIS SONG? <span class="man"></span></p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                     <div class="form-group">
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="goDistanceYes" id="strictly_for_the_streets" value="strictly_for_the_streets" type="radio">
                                                   Strictly for the Streets
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="goDistanceYes" id="digital_underground" value="digital_underground" type="radio">
                                                   Digital Underground
                                                  </label>
                                                </div>
                                                 <div class="radio dja">
                                                  <label>
                                                    <input name="goDistanceYes" id="mixshow_club_banger" value="mixshow_club_banger" type="radio">
                                                   Mixshow/Club Banger
                                                  </label>
                                                </div>
                                                 <div class="radio dja">
                                                  <label>
                                                    <input name="goDistanceYes" id="something_for_the_radio" value="something_for_the_radio" type="radio">
                                                   Something for the Radio
                                                  </label>
                                                </div>
                                    </div>
                                    <!-- <select name="goDistanceYes" size="1" id="goDistanceYes" class="rt1 selectpicker" style="background: white;">
                                    <option value="" selected="">Select An Option</option>
                                    <option value="strictly_for_the_streets">Strictly for the Streets</option>
                                    <option value="digital_underground">Digital Underground</option>
                                    <option value="mixshow_club_banger">Mixshow/Club Banger</option>
                                    <option value="something_for_the_radio">Something for the Radio</option>
                                    </select>-->
                                        </div>
                                    </div>
                                    </div>
                                    <div class="q-item">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">4. HOW SHOULD THE LABEL BEST SUPPORT THIS PROJECT? <span class="man"></span></p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-group">
                                                <div class="radio dja">
                                                  <label>
                                    <input name="labelSupport" id="promote_to_playlists" value="promote_to_playlists" type="radio" onChange="changeLabelSupport(this.value)">
                                                   Promote to Playlists
                                                  </label>
                                                </div>
                                    <div class="radio dja">
                                                  <label>
                                    <input name="labelSupport" id="market_visits" value="market_visits" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Market Visits
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="labelSupport" id="more_street_marketing" value="more_street_marketing" type="radio" onChange="changeLabelSupport(this.value)">
                                                    More Street Marketing
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="labelSupport" id="interview_on_my_show_or_station" value="interview_on_my_show_or_station" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Local Station/Show Interviews
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="labelSupport" id="a_show_in_my_market" value="a_show_in_my_market" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Local Live Performance
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="labelSupport" id="service_the_record" value="service_the_record" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Service the Record (to commercial, streaming platforms and digital radio)
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                          <input name="labelSupport" id="scrap_the_project" value="scrap_the_project" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Scrap the Project
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="labelSupport" id="shoot_a_video" value="shoot_a_video" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Shoot a Video
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="labelSupport" id="nothing" value="nothing" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Nothing
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                                    <input name="labelSupport" id="other" value="other" type="radio" onChange="changeLabelSupport(this.value)">
                                                    Other
                                                  </label>
                                                </div>
                                                <div class="radio dja" id="labelSupportInput" style="display:none;">
                                                     <textarea class="form-control" placeholder="Please Explain" rows="5" name="labelSupportOther" id="labelSupportOther"></textarea>
                                                </div>
                                           </div>
                                    <!--
                                    <select name="labelSupport" size="1" id="labelSupport" class="rt1 selectpicker" onChange="javascript:labelSupportToggle();">
                                    <option value="" selected="">Select An Option</option>
                                    <option value="market_visits">Market Visits</option>
                                    <option value="more_street_marketing">More Street Marketing</option>
                                    <option value="interview_on_my_show_or_station">Local Station/Show Interviews</option>
                                    <option value="a_show_in_my_market">Local Live Performance
                                    </option><option value="service_the_record">Service the Record
                                    </option><option value="scrap_the_project">Scrap the Project
                                    </option><option value="shoot_a_video">Shoot a Video
                                    </option><option value="nothing">Nothing
                                    </option><option value="other">Other
                                    </option></select>-->
                                        </div>
                                    </div>
                                    </div><!-- eof q-item -->
                                    
                                    <div class="q-item">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">5. HOW WILL YOU SUPPORT THIS PROJECT? <span class="man"></span></p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-group">
                                                <div class="radio dja">
                                                  <label>
                                            <input name="howSupport" id="play_it" value="play_it" type="radio" onChange="changeHowSupport(this.value)">
                                                    Play It
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="howSupport" id="nothing_i_wont_support_it" value="nothing_i_wont_support_it" type="radio" onChange="changeHowSupport(this.value)">
                                                    Nothing, I won't support it
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="howSupport" id="remix_it" value="remix_it" type="radio" onChange="changeHowSupport(this.value)">
                                                    Remix It
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="howSupport" id="does_not_apply_to_me" value="does_not_apply_to_me" type="radio" onChange="changeHowSupport(this.value)">
                                                    Does Not Apply To Me
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                    <input name="howSupport" id="add_to_my_playlist" value="add_to_my_playlist" type="radio" onChange="changeHowSupport(this.value)">
                                                    add to my playlist or share record
                                                  </label>
                                                </div>
                                       </div>
                                          <!--       <select name="howSupport" size="1" id="howSupport" class="rt1 selectpicker" onChange="changeHowSupport(this.value)">
                                    <option value="" selected="">Select An Option</option>
                                    <option value="play_it">Play It</option>
                                    <option value="nothing_i_wont_support_it">Nothing, I won't support it</option>
                                    <option value="remix_it">Remix It</option>
                                    <option value="does_not_apply_to_me">Does Not Apply To Me</option>
                                    </select>-->
                                    
                                        </div>
                                    </div>
                                    </div><!-- eof q-item -->
                                    
                                    <div class="q-item" id="howSupportInput" style="display:none;">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">5.1. HOW SOON WILL YOU START PLAYING THIS SONG?</p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-group">
                                               <div class="radio dja">
                                                  <label>
                                            <input name="howSupportHowSoon" id="already_playing_it" value="already_playing_it" type="radio">
                                                    Already Playing It
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="howSupportHowSoon" id="immedietly" value="immedietly" type="radio">
                                                    Immediately
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="howSupportHowSoon" id="after_it_builds_more_buzz" value="after_it_builds_more_buzz" type="radio">
                                                    After It Builds More Buzz
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="howSupportHowSoon" id="when_i_feel_like_it" value="when_i_feel_like_it" type="radio">
                                                    When I Feel Like It
                                                  </label>
                                                </div>
                                        </div>
                                    
                                    <!--
                                    <select name="howSupportHowSoon" size="1" id="howSupportHowSoon" class="rt1 selectpicker" style="background: white;">
                                    <option value="" selected="">Select An Option</option>
                                    <option value="already_playing_it">Already Playing It
                                    </option><option value="immedietly">Immediately
                                    </option><option value="after_it_builds_more_buzz">After It Builds More Buzz
                                    </option><option value="when_i_feel_like_it">When I Feel Like It
                                    </option></select>-->
                                        </div>
                                    </div>
                                    </div><!-- eof q-item -->
                                    
                                    <div class="q-item">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">6. WHAT DO YOU LIKE MOST ABOUT THIS RECORD? <span class="man"></span></p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                        <div class="form-group">
                                               <div class="radio dja">
                                                  <label>
                                            <input name="likeRecord" id="flow" value="flow" type="radio">
                                                    The Flow
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="likeRecord" id="the_lyrics" value="the_lyrics" type="radio">
                                                    The Lyrics
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="likeRecord" id="production" value="production" type="radio">
                                                    Production
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="likeRecord" id="hook_or_chorus" value="hook_or_chorus" type="radio">
                                                    Hook/Chorus
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="likeRecord" id="overall_sound_or_style" value="overall_sound_or_style" type="radio">
                                                    Overall Sound/Style
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label>
                                            <input name="likeRecord" id="nada" value="nada" type="radio">
                                                    Nothing/Nada
                                                  </label>
                                                </div>
                                       </div>
                                              <!--  <select name="likeRecord" size="1" id="likeRecord" class="rt1 selectpicker">
                                    <option value="" selected="">Select An Option</option>
                                    <option value="flow">The Flow
                                    </option><option value="the_lyrics">The Lyrics
                                    </option><option value="production">Production
                                    </option><option value="hook_or_chorus">Hook/Chorus
                                    </option><option value="overall_sound_or_style">Overall Sound/Style
                                    </option><option value="nada">Nothing/Nada
                                    </option></select>-->
                                        </div>
                                    </div>
                                    </div><!-- eof q-item -->
                                    
                                    <div class="q-item">
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <p class="q1">7. DO YOU WANT THIS SONG IN ANOTHER FORMAT? <span class="man"></span></p>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                            <div class="form-group">
                                                <div class="radio dja">
                                                  <label style="padding-left:0px;" class="checkboxclass">
                                                    <input name="anotherFormat[]" id="optionsRadios1" value="no" type="checkbox">
                                                    NO
                                                  </label>
                                                </div>
                                     <div class="radio dja">
                                                  <label style="padding-left:0px;" class="checkboxclass">
                                                    <input name="anotherFormat[]" id="optionsRadios1" value="wave" type="checkbox">
                                                    WAVE
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label style="padding-left:0px;">
                                                    <input name="anotherFormat[]" id="optionsRadios2" value="cd" type="checkbox">
                                                    CD
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label style="padding-left:0px;">
                                                    <input name="anotherFormat[]" id="optionsRadios1" value="vinyl_or_12_inch" type="checkbox">
                                                    VINYL/12"
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label style="padding-left:0px;">
                                                    <input name="anotherFormat[]" id="optionsRadios2" value="higher_quality_file" type="checkbox">
                                                    HQ FILE
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label style="padding-left:0px;">
                                                    <input name="anotherFormat[]" id="optionsRadios1" value="does_not_apply_to_me" type="checkbox">
                                                    DOES NOT APPLY
                                                  </label>
                                                </div>
                                                <div class="radio dja">
                                                  <label style="padding-left:0px;" class="checkboxclass">
                                                    <input name="anotherFormat[]" id="optionsRadiosOther" value="other" type="checkbox" onChange="changeAnotherFormat(this.value)">
                                                    OTHER
                                                  </label>
                                                </div>
                                                <div class="radio dja" id="anotherFormatInput" style="display:none;">
                                                     <textarea class="form-control" placeholder="Please Explain" rows="5" name="anotherFormatOther" id="anotherFormatOther"></textarea>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                    </div><!-- eof q-item --> */ ?>
                                 <div class="q-item">
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <p class="q1">2. WHAT'S REALLY GOOD? (Give your additional comments 320 Character Limit) REQUIRED. </p>
                                          <div class="form-group">
                                             <textarea class="form-control" onkeyup="countComments(this.value)" required placeholder="" rows="5" name="comments" id="comments"></textarea>
                                             <span id="comment_length">320</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- eof q-item -->
                                 <div class="q-item">
                                    <div class="form-group">
                                       @csrf
                                       <input name="submitReview" class="login_btn btn bsp btn-alt" value="<?php echo $submit_review_text; ?>" type="submit">
                                    </div>
                                 </div>
                              </form>
                           </div>
                           <!-- eof rew-form -->
                        </div>
                        <!-- eof trk-info-blk -->
                     </div>
                     <!-- eof mem-dblk -->
                  </div>
                  <!-- eof middle block -->
               </div>
               <!---tab section end--->
               <!--album-download-->
           @include('layouts.include.content-footer') 
            </div>
         </div>
      </div>
</section>
<script>
   function getDistanceInput(id)
   
   {
   
   
   
    if(id==1)
   
    {
   
     document.getElementById('goDistanceInput').style.display = 'block';
   
    }
   
    else
   
    {
   
     document.getElementById('goDistanceInput').style.display = 'none';
   
    }
   
   
   
   }
   
   function changeHowSupport(val)
   
   {
   
   
   
   if(val==='play_it')
   
   {
   
    document.getElementById('howSupportInput').style.display = 'block';
   
   }
   
   else
   
   {
   
    document.getElementById('howSupportInput').style.display = 'none';
   
   }
   
   
   }
   
   
   function changeLabelSupport(val)
   {
   if(val==='other')
   {
    document.getElementById('labelSupportInput').style.display = 'block';
   }
   else
   {
    document.getElementById('labelSupportInput').style.display = 'none';
   }
   }
   
       function changeAnotherFormat()
   {
   var val = document.getElementById("optionsRadiosOther").checked;
   
   if(val===true)
   {
    document.getElementById('anotherFormatInput').style.display = 'block';
   }
   else
   {
    document.getElementById('anotherFormatInput').style.display = 'none';
   }
   }
   function countComments(comment)
   {
   var comment_length = parseInt(comment.length);
   
   var balance = 320-comment_length;
   document.getElementById('comment_length').innerHTML = balance;
   if(balance<1)
   
   {
   
     var resultText = comment.substr(0, 320);
   
     document.getElementById('comments').value = resultText;
   
   
   
        }
   
   }
   
   
   
   //       var validator =
   
   // Wait for the DOM to be ready
   
   $(function() {
   
   
   
   $("#reviewForm").validate({
   
    rules:{
   
    "whereHeard":{
   
             required:true,
   
        },
   
   "goDistance":{
   
             required:true,
   
        },
   
   "goDistanceYes":{
   
             required:true,
   
        },
   
   "labelSupport":{
   
             required:true,
   
        },
   
   "howSupport":{
   
             required:true,
   
        },
   
     "likeRecord":{
   
             required:true,
   
        },
   
   "anotherFormat[]":{
   
             required:true,
   
       },
   
   "anotherFormatOther":{
   
             required:true,
   
       }
       
       
   
   }
   
   });
   
   
   
   
   // $("#form-toggle").click(function()
   // {
   //   $(".togglethis").slideToggle();
   
   //     var link_text = '<?php echo $link_text;?>';
   
   //     if($(this).text() == link_text + ' +')
   //     {
   //         $(this).text(link_text + ' -');
   //     }
   //     else
   //     {
   //         $(this).text(link_text + ' +');
   //     }
   // });
   
   
   
   /*
   
   $("#reviewForm").validate();
   
   
   
   $("#whereHeard").rules("add", {
   
         required:true,
   
         messages: {
   
                required: "Please enter username or email."
   
         }
   
      });
   
   
   
   $("#password").rules("add", {
   
         required:true,
   
   minlength:8,
   
         messages: {
   
                required: "Please enter password."
   
         }
   
      });
   
   
   
   $("#password1").rules("add", {
   
         required:true,
   
   equalTo: "#password",
   
   messages: {
   
                required: "Please enter the same password again."
   
         }
   
      });
   
   */
   
   });
   
   
</script>
<script>
   $("#form-toggle").click(function()
   {
    
     $(".togglethis").slideToggle();
   
       var link_text = '<?php echo $link_text;?>';
   
       if($(this).text() == link_text + ' +')
       {
           $(this).text(link_text + ' -');
       }
       else
       {
           $(this).text(link_text + ' +');
       }
   });
    
</script>
@endsection

