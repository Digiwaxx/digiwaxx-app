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
 
<div class="col-lg-12 col-md-12">
                    	<div class="mem-dblk f-block">
                        	 <h1>REVIEW TRACK (AND UNLOCK DOWNLOAD)</h1>

                             <div class="trk-info-blk-edit-view mCustomScrollbar">
                             	<div class="row">
                                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                    	<img src="<?php echo asset("ImagesUp/".$tracks['data'][0]->imgpage); ?>" class="img-responsive">
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
                                
                                 
                                 <div class="rew-form">
                                 	<h1>UNLOCK DOWNLOAD</h1>
                                    
                                    <p class="desc">
                                    	<span>All questions and comments are required to un-lock mp3's below.</span>
Before you begin, we want you to know that your honest review of this song is very much appreciated. This section provides a platform for you to give your honest opinion and review directly to the record labels, management companies, and artists associated to this project. What you feel and what you say is totally up to you. Remember All Reviews are Good Reviews.</p>


    <?php if(isset($alert_message)) { ?><div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div><?php  } ?>

      <form action="" method="post">
          @csrf
								<?Php /*	<div class="q-item"> 								
                                        <div class="row"> 
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">1. WHERE DID YOU HEAR THIS SONG FIRST? </p>
                                            </div>
    
	 
	                              
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                              
                    <select name="whereHeard" size="1" id="whereHeard" class="rt1 selectpicker">
                	<option value="">Select An Option</option>
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'digital_waxx_music_service')==0) { ?> selected="selected" <?php } ?> value="digital_waxx_music_service">Digital Waxx Music Service</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'commercial_radio')==0) { ?> selected="selected" <?php } ?> value="commercial_radio">Commercial Radio</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'satellite_radio')==0) { ?> selected="selected" <?php } ?> value="satellite_radio">Satellite Radio</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'college_radio')==0) { ?> selected="selected" <?php } ?> value="college_radio">College Radio</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'mixtape')==0) { ?> selected="selected" <?php } ?> value="mixtape">Mixtape</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'club')==0) { ?> selected="selected" <?php } ?> value="club">Club</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'internet')==0) { ?> selected="selected" <?php } ?> value="internet">Internet</option>
 
 <option <?php  if(strcmp($memberReview['data'][0]->whereheard,'video')==0) { ?> selected="selected" <?php } ?> value="video">Video</option>
 
 
 </select>
                                            </div>
                                        </div> 
                                     </div> <!-- eof q-item -->
                                    
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
												
											
									   
                                                    <select id="example-square" name="whatRate" autocomplete="off" class="digi-rating">
     <option  value=""></option>
     <option <?php if(strcmp($memberReview['data'][0]->whatrate,'1.0')==0) { ?> selected="selected" <?php }  ?> value="1">1</option>
     <option <?php if(strcmp($memberReview['data'][0]->whatrate,'2.0')==0) { ?> selected="selected" <?php }  ?> value="2">2</option>
     <option <?php if(strcmp($memberReview['data'][0]->whatrate,'3.0')==0) { ?> selected="selected" <?php }  ?> value="3">3</option>
     <option <?php if(strcmp($memberReview['data'][0]->whatrate,'4.0')==0) { ?> selected="selected" <?php }  ?> value="4">4</option>
     <option <?php if(strcmp($memberReview['data'][0]->whatrate,'5.0')==0) { ?> selected="selected" <?php }  ?> value="5">5</option>
                                                    </select>
                                                </div>
                                                
                                                <p class="rtf">RATE FROM 1 TO 5 <i class="fa fa-caret-up"></i></p>
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    <?php /*
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">3. DO YOU THINK THIS RECORD WILL GET ANY SUPPORT? </p>
                                            </div>
    
    								   
										   
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                               
                                                <div class="form-group">
                                                    <div class="radio dja">                                	
                                                      <label>
       <input <?php  if(strcmp($memberReview['data'][0]->godistance,'yes')==0) { ?> checked="checked" <?php }  ?> name="goDistance" id="optionsRadios1" value="yes" checked="" type="radio" onClick="getDistanceInput(1)">
                                                        YES <br> <span>THIS RECORD WILL GET SUPPORT</span>
                                                      </label>
                                                    </div>
                                                    
                                                    <div class="radio dja">
                                                      <label>
      <input <?php  if(strcmp($memberReview['data'][0]->godistance,'no')==0) { ?> checked="checked" <?php }  ?> name="goDistance" id="optionsRadios2" value="no" type="radio" onClick="getDistanceInput(0)">
                                                        NO <br> <span>THIS RECORD IS DEAD ON ARRIVAL!</span>
                                                      </label>
                                                    </div>                                                
                                               </div>
											   
                                                    
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
                                            
     <select name="goDistanceYes" size="1" id="goDistanceYes" class="rt1 selectpicker" style="background: white;">
                        <option value="" selected="">Select An Option</option>
<option <?php  if(strcmp($memberReview['data'][0]->godistanceyes,'strictly_for_the_streets')==0) { ?> selected="selected" <?php }  ?> value="strictly_for_the_streets">Strictly for the Streets</option>
						<option <?php  if(strcmp($memberReview['data'][0]->godistanceyes,'digital_underground')==0) { ?> selected="selected" <?php }  ?> value="digital_underground">Digital Underground</option>
						<option <?php  if(strcmp($memberReview['data'][0]->godistanceyes,'mixshow_club_banger')==0) { ?> selected="selected" <?php }  ?> value="mixshow_club_banger">Mixshow/Club Banger</option>
						<option <?php  if(strcmp($memberReview['data'][0]->godistanceyes,'something_for_the_radio')==0) { ?> selected="selected" <?php }  ?> value="something_for_the_radio">Something for the Radio</option>
				  </select>
												
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
                                            
      <select name="labelSupport" size="1" id="labelSupport" class="rt1 selectpicker" onChange="javascript:labelSupportToggle();">
      <option value="" selected="">Select An Option</option>
      <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'market_visits')==0) { ?> selected="selected" <?php } ?>  value="market_visits">Market Visits</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'more_street_marketing')==0) { ?> selected="selected" <?php } ?> value="more_street_marketing">More Street Marketing</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'interview_on_my_show_or_station')==0) { ?> selected="selected" <?php } ?> value="interview_on_my_show_or_station">Local Station/Show Interviews</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'a_show_in_my_market')==0) { ?> selected="selected" <?php } ?> value="a_show_in_my_market">Local Live Performance</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'service_the_record')==0) { ?> selected="selected" <?php } ?> value="service_the_record">Service the Record</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'scrap_the_project')==0) { ?> selected="selected" <?php } ?> value="scrap_the_project">Scrap the Project</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'shoot_a_video')==0) { ?> selected="selected" <?php } ?> value="shoot_a_video">Shoot a Video</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'nothing')==0) { ?> selected="selected" <?php } ?> value="nothing">Nothing</option>
	  
	  <option <?php if(strcmp($memberReview['data'][0]->labelsupport,'other')==0) { ?> selected="selected" <?php } ?> value="other">Other</option>
	  
	  </select>
												
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">5. HOW WILL YOU SUPPORT THIS PROJECT? </p>
                                            </div>
    
    
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                         										        
      <select name="howSupport" size="1" id="howSupport" class="rt1 selectpicker" onChange="changeHowSupport(this.value)">
      <option value="" selected="">Select An Option</option>    
      <option <?php if(strcmp($memberReview['data'][0]->howsupport,'play_it')==0) { ?> selected="selected" <?php } ?> value="play_it">Play It</option>
	  <option <?php if(strcmp($memberReview['data'][0]->howsupport,'nothing_i_wont_support_it')==0) { ?> selected="selected" <?php } ?> value="nothing_i_wont_support_it">Nothing, I won't support it</option>
	  <option <?php if(strcmp($memberReview['data'][0]->howsupport,'remix_it')==0) { ?> selected="selected" <?php } ?> value="remix_it">Remix It</option>
	  <option <?php if(strcmp($memberReview['data'][0]->howsupport,'does_not_apply_to_me')==0) { ?> selected="selected" <?php } ?> value="does_not_apply_to_me">Does Not Apply To Me</option>
	 </select>
	                                        </div>
                                        </div>
                                     </div><!-- eof q-item -->
									 
									<?php if(strcmp($memberReview['data'][0]->howsupport,'play_it')==0) { ?>	  
									 <div class="q-item" id="howSupportInput">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">5.1. HOW SOON WILL YOU START PLAYING THIS SONG?</p>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                             
              <select name="howSupportHowSoon" size="1" id="howSupportHowSoon" class="rt1 selectpicker" style="background: white;">
        <option value="" selected="">Select An Option</option>
        <option <?php if(strcmp($memberReview['data'][0]->howsupport_howsoon,'already_playing_it')==0) { ?> selected="selected" <?php }  ?> value="already_playing_it">Already Playing It</option>
		
		<option <?php if(strcmp($memberReview['data'][0]->howsupport_howsoon,'immedietly')==0) { ?> selected="selected" <?php }  ?> value="immedietly">Immediately</option>
		
		<option <?php if(strcmp($memberReview['data'][0]->howsupport_howsoon,'after_it_builds_more_buzz')==0) { ?> selected="selected" <?php }  ?> value="after_it_builds_more_buzz">After It Builds More Buzz</option>
		
		<option <?php if(strcmp($memberReview['data'][0]->howsupport_howsoon,'when_i_feel_like_it')==0) { ?> selected="selected" <?php }  ?> value="when_i_feel_like_it">When I Feel Like It</option>
		</select>
		
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
                                             
                        <select name="likeRecord" size="1" id="likeRecord" class="rt1 selectpicker">
                        <option value="" selected="">Select An Option</option>
         <option <?php if(strcmp($memberReview['data'][0]->likerecord,'flow')==0) { ?> selected="selected" <?php }  ?> value="flow">The Flow</option>
         <option <?php if(strcmp($memberReview['data'][0]->likerecord,'the_lyrics')==0) { ?> selected="selected" <?php }  ?> value="the_lyrics">The Lyrics </option>
         <option <?php if(strcmp($memberReview['data'][0]->likerecord,'production')==0) { ?> selected="selected" <?php }  ?> value="production">Production</option>
		<option <?php if(strcmp($memberReview['data'][0]->likerecord,'hook_or_chorus')==0) { ?> selected="selected" <?php }  ?> value="hook_or_chorus">Hook/Chorus</option>
		<option <?php if(strcmp($memberReview['data'][0]->likerecord,'overall_sound_or_style')==0) { ?> selected="selected" <?php }  ?> value="overall_sound_or_style">Overall Sound/Style</option>
		<option <?php if(strcmp($memberReview['data'][0]->likerecord,'nada')==0) { ?> selected="selected" <?php }  ?> value="nada">Nothing/Nada</option>
						</select>
                                            </div>
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    
									<div class="q-item">								
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-sm-7">
                                                <p class="q1">7. DO YOU WANT THIS SONG IN ANOTHER FORMAT? </p>
                                            </div>
    
     <?php	 $formats = explode(',',$memberReview['data'][0]->anotherformat); ?>
                                            <div class="col-lg-5 col-md-5 col-sm-5">
                                                <div class="form-group">
                                                    <div class="radio dja">                                	
                                                      <label>
                                                <input  <?php if(in_array("no",$formats)) { ?> checked="checked" <?php } ?> name="anotherFormat[]" id="optionsRadios1" value="no" type="checkbox">
                                                        NO 
                                                      </label>
                                                    </div>
                                                    
                                                    <div class="radio dja">
                                                      <label>
          <input  <?php if(in_array("cd",$formats)) { ?> checked="checked" <?php } ?> name="anotherFormat[]" id="optionsRadios2" value="cd" type="checkbox">
                                                        CD 
                                                      </label>
                                                    </div>                                                

                                                    <div class="radio dja">                                	
                                                      <label>
              <input  <?php if(in_array("vinyl_or_12_inch",$formats)) { ?> checked="checked" <?php } ?> name="anotherFormat[]" id="optionsRadios1" value="vinyl_or_12_inch" type="checkbox">
                                                        VINYL/12" 
                                                      </label>
                                                    </div>
                                                    
                                                    <div class="radio dja">
                                                      <label>
           <input  <?php if(in_array("higher_quality_file",$formats)) { ?> checked="checked" <?php } ?> name="anotherFormat[]" id="optionsRadios2" value="higher_quality_file" type="checkbox">
                                                        HQ FILE
                                                      </label>
                                                    </div>                                                

                                                    <div class="radio dja">                                	
                                                      <label>
        <input  <?php if(in_array("does_not_apply_to_me",$formats)) { ?> checked="checked" <?php } ?> name="anotherFormat[]" id="optionsRadios1" value="does_not_apply_to_me" type="checkbox">
                                                        DOES NOT APPLY
                                                      </label>
                                                    </div>
                                                    
                                                                                            
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
                 <textarea class="form-control" placeholder="" rows="5" name="comments" id="comments"><?php echo urldecode($memberReview['data'][0]->additionalcomments); ?></textarea>
                                               </div>
                                            </div>
    
    
                                        </div>
                                     </div><!-- eof q-item -->
                                    
                                    <div class="q-item">	
                                        <div class="form-group">
										  <input type="hidden" name="reviewId" value="<?php echo $memberReview['data'][0]->id; ?>" />
                                                <input name="updateReview" class="login_btn btn bsp" value="UPDATE REVIEW" type="submit">
                                        </div>
                                    </div>
                                    
									
									</form>
                                    
                                 </div><!-- eof rew-form -->

                             
                             </div><!-- eof trk-info-blk-edit-view -->
                             


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

@endsection

