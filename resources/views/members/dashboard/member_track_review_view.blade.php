@extends('layouts.member_dashboard')
@section('content')


<style>
.download_link, .download_link:hover
{
  color: #FFF;
  font-weight: bold;
  display: block;
  margin-top: 6px;
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
</style>

	<section class="main-dash">
		@include('layouts.include.sidebar-left')

        <?php
            $link_text = 'UNLOCK DOWNLOAD';
            $member_session_pkg = Session::get('memberPackage');
            if(isset($member_session_pkg) && $member_session_pkg > 2)
            {
            $link_text = 'WRITE A REVIEW';
            }
            ?>
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            
              <div class="tabs-section">

                    <!-- START MIDDLE BLOCK --><title>Untitled Document</title>

<div class="col-lg-12 col-md-12" style="padding:0 7px">
                    	<div class="mem-dblk f-block">
                        	 <h1>TRACK  REVIEW</h1>

                             <div class="trk-info-blk-view mCustomScrollbar GSGS">
                             	<div class="row">
                                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                        <?php
                                        // pArr($tracks['data'][0]);
                                        if(!empty($tracks['data'][0]->imgpage)){


                                            if (file_exists(base_path('ImagesUp/'.$tracks['data'][0]->imgpage))){
                                                                                                      
                                              $img_get = asset("ImagesUp/".$tracks['data'][0]->imgpage);  
                                        
                                            }
                                            else{
                                              $img_get = asset('public/images/noimage-avl.jpg'); 
                                            }
                                      
                                          }
                                      
                                          else{
                                      
                                            $img_get = asset('public/images/noimage-avl.jpg');
                                          }
                                        
                                        
                                        
                                        ?>
                                    	<img src="<?php echo $img_get; ?>" class="img-responsive">
                                    </div>
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8"> 
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
                                    <?php } ?>
                                    <p class="t1"><label>Member Points: </label> <span>1 point</span></p>
                                 </div>
                                 <!-- trk-det -->
                                        
                                    </div>
                                </div>
                                

                           
                                 
                                 <div class="rew-form trk-info-blk-view">
                                 	<!--<h1>UNLOCK DOWNLOAD</h1>-->
                                 	<?php  $myarray1 = array(); $myarray2 = array(); 
                                 	$count=0;
                                 	foreach($mp3s['data'] as $track){
                                 	    if(1==1) { $fileid = (int)$track->location; ?>
                                 	    <div class="rew-trks <?php echo "sg_track_".$count; ?>">
                                 	        <div class="rtrk-item">
                                              	<p>
                                              	    Artist: <?php echo urldecode($track->artist); ?>, Title: <?php echo urldecode($track->title); ?>, Version: <?php echo urldecode($track->version); ?>
                                              	    <?php if(isset($_SESSION['memberPackage'])) { ?>
                                                        <?php if (strpos($track->location, '.mp3') !== false) { ?>
                                                            <a class="download_link" href="<?php echo url('Download_member_track?track='.$track->location.'&mp3Id='.$track->id.'&trackId='.$_GET['tid'].'&title='.$track->title); ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                                        <?php $myarray1[] = $track->location; } else { $getlink = ''; if(!empty($fileid)){ $getlink = url('Download_member_track?track='.$fileid.'&mp3Id='.$track->id.'&trackId='.$_GET['tid'].'&pcloud=true'); } ?>
                                                            <a class="download_link" href="<?php echo $getlink; ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                                    <?php $myarray2[] = $track->location; } ?>
                                              	</p>
                                                <div class="media-wrapper12">
                                                <audio id="player2" preload="none" controls style="width:92%;">
                                                    <?php if (strpos($track->location, '.mp3') !== false) { ?>
                                                        <source src="<?php echo asset('AUDIO/'.$track->location); ?>" type="audio/mp3">
                                                    <?php } else { $getlink = ''; if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                                        <source src="<?php echo $getlink; //asset('AUDIO/'.$track->location); ?>" type="audio/mp3">
                                                    <?php } ?>
                                                    Your browser does not support the audio element.
                                                </audio>
                                    <?php } ?></div></div></div>
                                    <?php } 
                                 	    $count++;
                                 	}   
                                    $mstr1 = implode(',',$myarray1);
                                    $mstr2 = implode(',',$myarray2);
                                    ?>
                                    <div class="zipfiles" style="margin-bottom:20px;">
                                        <a style="background: #db378f;padding: 20px;width: 92%;text-align: center;font-size: 26px; font-family: 'Josefin Sans',sans-serif; color: #fff; font-weight: bold; line-height: 16px; text-transform: uppercase; margin-top: 10px; display: inline-block;" href="<?php echo url('zipdownload.php?in='.$mstr1.'&out='.$mstr2); ?>" class="download_zip">Download Zip <i class="fa fa-cloud-download"></i></a>
                                    </div>
                                    
                                    <p class="desc">
                                    	<span>All questions and comments are required to un-lock mp3's below.</span>
Before you begin, we want you to know that your honest review of this song is very much appreciated. This section provides a platform for you to give your honest opinion and review directly to the record labels, management companies, and artists associated to this project. What you feel and what you say is totally up to you. Remember All Reviews are Good Reviews.</p>



    <?php if(isset($alert_message)) { ?><div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div><?php  } ?>


                                <?Php /*
									<div class="q-item"> 								
                                        <div class="row"> 
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">1. WHERE DID YOU HEAR THIS SONG FIRST? </p>
                                            </div>
   
    
	                          
	                              
                                            <div class="col-lg-5 col-md-5 col-sm-5">
											
											
	<?php
	$whereHeard = '';
	 if(strcmp($memberReview['data'][0]->whereheard,'digital_waxx_music_service')==0) { $whereHeard = 'Digital Waxx Music Service'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'commercial_radio')==0) { $whereHeard = 'Commercial Radio'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'satellite_radio')==0) { $whereHeard = 'Satellite Radio'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'college_radio')==0) { $whereHeard = 'College Radio'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'mixtape')==0) { $whereHeard = 'Mixtape'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'club')==0) { $whereHeard = 'Club'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'internet')==0) { $whereHeard = 'Internet'; } 
	else if(strcmp($memberReview['data'][0]->whereheard,'video')==0) { $whereHeard = 'Video'; } 	
	
	
	?>
	
											
                                                <p class="a1"><?php echo $whereHeard; ?></p>
                                                    
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    */ ?>
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">1. WHAT DO YOU THINK ABOUT THIS SONG? </p>
                                                
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
												
										
									<?php		$whereRate = '';
									if(strcmp($memberReview['data'][0]->whatrate,'1.0')==0) { $whereRate = 1; } 
									else if(strcmp($memberReview['data'][0]->whatrate,'2.0')==0) { $whereRate = 2; } 
									else if(strcmp($memberReview['data'][0]->whatrate,'3.0')==0) { $whereRate = 3; } 
									else if(strcmp($memberReview['data'][0]->whatrate,'4.0')==0) { $whereRate = 4; } 
									else if(strcmp($memberReview['data'][0]->whatrate,'5.0')==0) { $whereRate = 5; } 	   ?>
												   
                                                    <select id="example-square" name="rating" autocomplete="off" class="digi-rating">
                                                      <option value=""></option>
                                                      <option value="1" <?php if($whereRate==1) { ?> selected="selected" <?php } ?>>1</option>
                                                      <option value="2" <?php if($whereRate==2) { ?> selected="selected" <?php } ?>>2</option>
                                                      <option value="3" <?php if($whereRate==3) { ?> selected="selected" <?php } ?>>3</option>
                                                      <option value="4" <?php if($whereRate==4) { ?> selected="selected" <?php } ?>>4</option>
                                                      <option value="5" <?php if($whereRate==5) { ?> selected="selected" <?php } ?>>5</option>
                                                    </select>
                                                </div>
                                                
                                                <p class="rtf">RATE FROM 1 TO 5 <i class="fa fa-caret-up"></i></p>
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    
									<?php /* <div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">3. DO YOU THINK THIS RECORD WILL GET ANY SUPPORT? </p>
                                            </div>
    
    
	
	
	<?php		$goDistance  = '';
	                           if(strcmp($memberReview['data'][0]->godistance,'yes')==0) { $goDistance = 'Yes'; } 
									else if(strcmp($memberReview['data'][0]->godistance,'no')==0) { $goDistance = 'No'; } 
										   ?>
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                               
											   <p class="a1"><?php echo $goDistance; ?></p>
                                                
											   
                                                    
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
							
							<?php if(strcmp($memberReview['data'][0]->godistance,'yes')==0) {  ?>		
									
									<div class="q-item" id="goDistanceInput">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">3.1. WHAT FORMAT DO YOU THINK WILL HELP BREAK THIS SONG? </p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
  <?php		    $goDistanceYes = '';
      if(strcmp($memberReview['data'][0]->godistanceyes,'strictly_for_the_streets')==0) { $goDistanceYes = 'Strictly for the Streets'; } 
				else if(strcmp($memberReview['data'][0]->godistanceyes,'digital_underground')==0) { $goDistanceYes = 'Digital Underground'; } 
				else if(strcmp($memberReview['data'][0]->godistanceyes,'mixshow_club_banger')==0) { $goDistanceYes = 'Mixshow/Club Banger'; } 
				else if(strcmp($memberReview['data'][0]->godistanceyes,'something_for_the_radio')==0) { $goDistanceYes = 'Something for the Radio'; } 
										   ?>                      
                         <p class="a1"><?php echo $goDistanceYes; ?></p>					
                                            </div>
                                        </div>
                                     </div>
									 <?php }  ?>
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">4. HOW SHOULD THE LABEL BEST SUPPORT THIS PROJECT? </p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
											
	<?php		$labelSupport = '';
	
	if(strcmp($memberReview['data'][0]->labelsupport,'more_street_marketing')==0) { $labelSupport = 'More Street Marketing'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'interview_on_my_show_or_station')==0) { $labelSupport = 'Local Station/Show Interviews'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'market_visits')==0) { $labelSupport = 'Market Visits'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'a_show_in_my_market')==0) { $labelSupport = 'Local Live Performance'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'service_the_record')==0) { $labelSupport = 'Service the Record'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'scrap_the_project')==0) { $labelSupport = 'Scrap the Project'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'shoot_a_video')==0) { $labelSupport = 'Shoot a Video'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'nothing')==0) { $labelSupport = 'Nothing'; } 
   else if(strcmp($memberReview['data'][0]->labelsupport,'other')==0) { $labelSupport = 'Other'; } 
										   ?>
                                            
        <p class="a1"><?php echo $labelSupport; ?></p>		
												
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">5. HOW WILL YOU SUPPORT THIS PROJECT? </p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                    <?php	$howSupport = '';
								if(strcmp($memberReview['data'][0]->howsupport,'play_it')==0) { $howSupport = 'Play It'; } 
   else if(strcmp($memberReview['data'][0]->howsupport,'nothing_i_wont_support_it')==0) { $howSupport = "Nothing, I won't support it"; } 
   else if(strcmp($memberReview['data'][0]->howsupport,'remix_it')==0) { $howSupport = 'Remix It'; } 
   else if(strcmp($memberReview['data'][0]->howsupport,'does_not_apply_to_me')==0) { $howSupport = 'Does Not Apply To Me'; } 
  
										   ?>
                                            
        <p class="a1"><?php echo $howSupport; ?></p>		
		          
                                                    
	                                        </div>
                                        </div>
                                     </div><!-- eof q-item -->
									 
								<?php		if(strcmp($memberReview['data'][0]->howsupport,'play_it')==0) { ?>	 
									 <div class="q-item" id="howSupportInput">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">5.1. HOW SOON WILL YOU START PLAYING THIS SONG?</p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                             
	<?php	$howSupportHowSoon = '';
	if(strcmp($memberReview['data'][0]->howsupport_howsoon,'already_playing_it')==0) { $howSupportHowSoon = 'Already Playing It'; } 
  else if(strcmp($memberReview['data'][0]->howsupport_howsoon,'immedietly')==0) { $howSupportHowSoon = "Immediately"; } 
  else if(strcmp($memberReview['data'][0]->howsupport_howsoon,'after_it_builds_more_buzz')==0) { $howSupportHowSoon = 'After It Builds More Buzz'; } 
  else if(strcmp($memberReview['data'][0]->howsupport_howsoon,'when_i_feel_like_it')==0) { $howSupportHowSoon = 'When I Feel Like It'; } 
  
										   ?>
                                            
        <p class="a1"><?php echo $howSupportHowSoon; ?></p>                
              
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    <?php } ?>
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">6. WHAT DO YOU LIKE MOST ABOUT THIS RECORD? </p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                     <?php $likeRecord = '';
				if(strcmp($memberReview['data'][0]->likerecord,'flow')==0) { $likeRecord = 'The Flow'; } 
  else if(strcmp($memberReview['data'][0]->likerecord,'the_lyrics')==0) { $likeRecord = 'The Lyrics'; } 
  else if(strcmp($memberReview['data'][0]->likerecord,'production')==0) { $likeRecord = 'Production'; } 
  else if(strcmp($memberReview['data'][0]->likerecord,'hook_or_chorus')==0) { $likeRecord = 'Hook/Chorus'; } 
  else if(strcmp($memberReview['data'][0]->likerecord,'overall_sound_or_style')==0) { $likeRecord = 'Overall Sound/Style'; } 
  else if(strcmp($memberReview['data'][0]->likerecord,'nada')==0) { $likeRecord = 'Nothing/Nada'; } 
  
   
  
										   ?>
                                            
        <p class="a1"><?php echo $likeRecord; ?></p>         
                                                   
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">7. DO YOU WANT THIS SONG IN ANOTHER FORMAT? </p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
			 	  <?php	          $formats = explode(',',$memberReview['data'][0]->anotherformat);    ?>
                                            
                                                <div class="form-group">
												 <?php if(in_array("no",$formats)) { ?> 
                                                    <div class="radio dja">                                	
                                                      <label>
                                                   
                                                        NO 
                                                      </label>
                                                    </div>
													  <?php } ?>
                                                   
												   <?php if(in_array("cd",$formats)) { ?> 
                                                    <div class="radio dja">
                                                      <label>
                                                     
                                                        CD 
                                                      </label>
                                                    </div>                                                
                                                    <?php } ?>
													
													<?php if(in_array("vinyl_or_12_inch",$formats)) { ?> 
                                                    <div class="radio dja">                                	
                                                      <label>
                                                       VINYL/12" 
                                                      </label>
                                                    </div>
													 <?php } ?>
                                                    <?php if(in_array("higher_quality_file",$formats)) { ?> 
                                                    <div class="radio dja">
                                                      <label>
                                                       
                                                        HQ FILE
                                                      </label>
                                                    </div>  
													 <?php } ?>     
												   <?php if(in_array("does_not_apply_to_me",$formats)) { ?> 
                                                    <div class="radio dja">                                	
                                                      <label>
                                                       
                                                        DOES NOT APPLY
                                                      </label>
                                                    </div>
													 <?php } ?>
                                                    
                                                                                            
                                               </div>     
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    */ ?>
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-12">
                                          <p class="q1">2. WHAT'S REALLY GOOD? (Give your additional comments 320 Character Limit) REQUIRED. </p>
                                                
                                                <div class="form-group">
                                                    <?php echo $memberReview['data'][0]->additionalcomments; ?>
                                               </div>
                                            </div>
    
    
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    <div class="q-item">	
                                        <div class="form-group">
                                          
	   <a href="Member_track_review_edit?tid=<?php echo $_GET['tid']; ?>" class="login_btn btn bsp">EDIT MY REVIEW</a>
                                        </div>
                                    </div>
                                    
									
								
                                    
                                 </div><!-- eof rew-form -->

                             
                             </div><!-- eof trk-info-blk-view -->
                             


                        </div><!-- eof mem-dblk -->
                    </div><!-- eof middle block -->

              </div>
              <!---tab section end--->
                         
           </div>
         </div>
       </div>
     </div>
	 </section>

 
     <script type="text/javascript">
//<![CDATA[
$(document).ready(function(){

	/*		mp3:"assets/jplayer/mp3/audio.mp3",*/



	new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
	}, [
	
	
	<?php

 foreach($mp3s['data'] as $track)
  {
  ?>
 
  {
			title:"<?php echo urldecode($track->title); ?>",
			artist:"<?php echo urldecode($track->artist); ?>",
			mp3:"<?php echo 'AUDIO/'.$track->location; ?>",
			oga:"",
			poster: "http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png"
		},
  
  <?php
  }


?>
		//	oga:"http://www.jplayer.org/audio/ogg/Miaow-02-Hidden.ogg",
		
			/*title:"Big Buck Bunny Trailer",
			artist:"Blender Foundation",
			m4v:"http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v",
			ogv:"http://www.jplayer.org/video/ogv/Big_Buck_Bunny_Trailer.ogv",
			webmv: "http://www.jplayer.org/video/webm/Big_Buck_Bunny_Trailer.webm",
			poster:"http://www.jplayer.org/video/poster/Big_Buck_Bunny_Trailer_480x270.png"*/
	
		
	], {
		swfPath: "../../dist/jplayer",
		supplied: "webmv, ogv, m4v, oga, mp3",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
		audioFullScreen: true
	});
});
//]]>
</script>
		
			
		<script>
		
 function goToPage(page,pid)
 {
    window.location = page+"?page="+pid;
 }
 
		
		function sortBy(page,type,id)
		{
		    var records = 10;		
			window.location = page+"?sortBy="+type+"&sortOrder="+id+"&records="+records;
		}
		
		function changeNumRecords(page,sortBy,sortOrder,records)
		{
			window.location = page+"?sortBy="+type+"&sortOrder="+id+"&records="+records;
		}
		
		function getDistanceInput(id)
		{
		  document.getElementById('goDistanceYes').value = ''; 
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
		
		
		
		 document.getElementById('howSupportHowSoon').value = ''; 
		 if(val==='play_it')
		 {
		  document.getElementById('howSupportInput').style.display = 'block';
		 }
		 else
		 {
		  document.getElementById('howSupportInput').style.display = 'none';
		 }
		   
		}
		</script>
		
		<script>
		$(document).ready(function(){
		    $('body').click();
		    setTimeout(function(){ console.log(11); $('.sg_track_0 .mejs__playpause-button button').click(); console.log(22);}, 4000);
        });
	
		</script>
		
<style>
.mejs__horizontal-volume-total{width:40px}

.trk-info-blk-view .rew-trks .rtrk-item p a {
	font-family: "Josefin Sans",sans-serif;
	font-size: 14px;
	color: #00dbff;
	font-weight: 700;
	line-height: 16px;
	padding-left: 20px;
}


</style>
@endsection

