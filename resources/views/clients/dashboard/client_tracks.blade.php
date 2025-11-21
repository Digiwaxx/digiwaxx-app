@extends('layouts.client_dashboard')

@section('content')
 <section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		 <div class="container">
			<div class="row">
			<div class="col-xl-9 col-12">
            <div class="dash-heading">
                <h2>My Tracks</h2>
              </div>
            <div class="tabs-section">
			<!-- START MIDDLE BLOCK -->
                <?php if(isset($alert_class)) 

                { ?>
             <div class="<?php echo $alert_class; ?>">
                    <p><?php echo $alert_message; ?></p>
                </div>
                <?php } // print_r($formData); ?>
            <div class="mtk-blk f-block">
                <div class="stk-btn row">
                    <div class="col-4">                     
                    </div> 
                     <div class="col-8">
                        <a href="<?php echo url('Client_submit_track'); ?>" class="pull-right btn-sm mt-0"><i class="fa fa-cloud-upload"></i>Upload music</a>
                    </div>
                </div>
                <div class="mtf nav nav-tabs">       
                <?php 
                $sortClass = '';
                $orderByAsc = 'inline';
                $orderByDesc = 'none';
                $orderById = 2;
            if(strcmp($sortBy,'artist')==0)
            {
               $sortClass = 'active';
               if($sortOrder==2)
               {
                 $orderByAsc = 'none';
                 $orderByDesc = 'inline';
                 $orderById = 1; 
               }  
            } ?>        

            <a href="javascript:void()" onclick="sortBy('artist','<?php echo $orderById; ?>')" class="ats nav-link <?php echo $sortClass; ?>">
              ARTIST 
              <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
              <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
            </a>

        <?php 
        $sortClass = '';
        $orderByAsc = 'inline';
        $orderByDesc = 'none';
        $orderById = 2;    
        if(strcmp($sortBy,'title')==0)
        {
           $sortClass = 'active';
           if($sortOrder==2)
           {
             $orderByAsc = 'none';
             $orderByDesc = 'inline';
             $orderById = 1;
           }  
        } ?>
            <a href="javascript:void()" onclick="sortBy('title','<?php echo $orderById; ?>')" class="tit nav-link <?php echo $sortClass; ?>">
               TITLE 
               <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
               <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
            </a>
        <?php 
        $sortClass = '';
        $orderByAsc = 'inline';
        $orderByDesc = 'none';
        $orderById = 2;       

        if(strcmp($sortBy,'date')==0)
        {
           $sortClass = 'active';
           if($sortOrder==2)
           {
             $orderByAsc = 'none';
             $orderByDesc = 'inline';
             $orderById = 1;
           }  
        } ?>
        <a href="javascript:void()" onclick="sortBy('date','<?php echo $orderById; ?>')" class="dtu nav-link <?php echo $sortClass; ?>">
           DATE UPLOADED 
           <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
           <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
        </a>
        </div>        
        <div class="fby-blk" style="float:right;">
            <div class="nop">
                <select class="rfn-sb npg selectpicker" id="records" style="color:#000;" onchange="changeNumRecords('<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
                    <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10 PER PAGE</option>
                    <option <?php if($numRecords==20) { ?> selected="selected" <?php } ?> value="20">20 PER PAGE</option>
                    <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30 PER PAGE</option>
                </select> 
            </div><!-- eof nop -->

            <div class="tnav">
                <span><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>
                <span class="num"><?php echo $currentPageNo; ?></span>
                <span><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
            </div>

        </div>

        <div style="clear:both;"></div>
        <div class="mtk-list">
            <ul class="mCustomScrollbar">

            <?php
            // PArr($tracks['data']);
            // die();

            foreach($tracks['data'] as $track)  { ?>

                <li class="mtk-track-list">

                    <div class="img-blk">

                    <?php
                 if(!empty($track->pCloudFileID)){
                     $imgSrc= url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);
                 }    

               else  if(isset($track->imgpage) && strlen($track->imgpage)>3 && file_exists(base_path('ImagesUp/'.$track->imgpage)))

                {
                   $imgSrc = asset('ImagesUp/'.$track->imgpage);

                }

                else

                {

                $imgSrc = asset('assets/img/track-logo.png');

                }

                    ?>
                        <a href="<?php echo url("Client_edit_track?tId=".$track->id); ?>">

                           <img src="<?php echo $imgSrc; ?>" width="100" height="100">

                        </a>   

                    </div><!-- eof img-blk -->
                    <div class="det-blk">

                        <p class="atst h5"><a href="<?php echo url("Client_edit_track?tId=".$track->id); ?>"><?php echo $artist = urldecode($track->artist); ?></a></p>
                        <p class="alb"><?php echo $title =  urldecode($track->title); ?></p>
                        <p class="rlb text-dim mb-0">Album: <?php echo $album =  urldecode($track->album); ?> - Time: <?php echo $time =  urldecode($track->time); ?></p>
                        <p class="rlb text-dim mb-0">Label: <?php echo $title =  urldecode($track->label); ?></p>                                            
                        <p class="up-dt text-dim mb-0">Uploaded on <?php 

                        $added =  explode(' ',$track->added);

                        $added =  explode('-',$added[0]);

                        echo $added = $added[1].'/'.$added[2].'/'.$added[0]; ?></p>
                        <div class="row">
                          <div class="col-md-8">

                         <div class="clearfix st-blk">

                            <div class="st-social-media-1">
                                <?php 
                                    $fblink = urldecode($track->facebookLink);
                                    $twtlink = urldecode($track->twitterLink);
                                    $instalink = urldecode($track->instagramLink);
                                    $tiktoklink = urldecode($track->tiktokLink);
                                    $snapchatlink = urldecode($track->snapchatLink);
                                    $ytubelink = urldecode($track->youtube_link);
                                    $videoURL = urldecode($track->videoURL);
                                    $aplMusiclink = urldecode($track->applemusicLink);
                                    $amzlink = urldecode($track->amazonLink);
                                    $spotifylink = urldecode($track->spotifyLink);

                                    if(!empty($fblink) && filter_var($fblink, FILTER_VALIDATE_URL)){

                                    
                                ?>
                                <div class="st">
                                    <a href="<?= $fblink; ?>" target="_blank"> <i class="fab fa-facebook"></i> </a>

                                </div>
                                    <?php }
                                    if(!empty($instalink) && filter_var($instalink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $instalink; ?>" target="_blank"> <i class="fab fa-instagram"></i> </a>

                                    </div>
                                    <?php }
                                    if(!empty($twtlink) && filter_var($twtlink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $twtlink; ?>" target="_blank"> <i class="fab fa-twitter"></i> </a>

                                    </div>
                                    <?php } 

                                    if(!empty($snapchatlink) && filter_var($snapchatlink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $snapchatlink; ?>" target="_blank"> <i class="fab fa-snapchat"></i> </a>

                                    </div>
                                    <?php }

                                    if(!empty($tiktoklink) && filter_var($tiktoklink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $tiktoklink; ?>" target="_blank"> <i class="fab fa-tiktok"></i> </a>

                                    </div>
                                    <?php } 
                                    if(!empty($aplMusiclink) && filter_var($aplMusiclink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $aplMusiclink; ?>" target="_blank"> <i class="fab fa-itunes"></i> </a>
                                    </div>
                                    <?php }
                                    if(!empty($spotifylink) && filter_var($spotifylink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $spotifylink; ?>" target="_blank"> <i class="fab fa-spotify"></i> </a>
                                    </div>
                                    <?php }
                                    if(!empty($ytubelink) && filter_var($ytubelink, FILTER_VALIDATE_URL)){
                                     ?>
                                    <div class="st">
                                        <a href="<?= $ytubelink; ?>" target="_blank"> <i class="fab fa-youtube"></i> </a>
                                    </div>
                                    <?php }else if(!empty($videoURL) && filter_var($videoURL, FILTER_VALIDATE_URL)){ ?>
                                    <div class="st">
                                        <a href="<?= $videoURL; ?>" target="_blank"> <i class="fab fa-youtube"></i> </a>
                                    </div>
                                    <?php }
                                    ?>

                            </div>
                        </div>
                    </div>
                </div>
                        <div class="row">
                          <div class="col-md-8">

                         <div class="clearfix st--blk">                             
                            <div class="st-social-media-2">
                            <div class="st">

                                <i class="fa fa-star"></i>
                                <span><?php // echo $track->id.'------';

                                 if(isset($trackData[$track->id]['rating'])) { echo $trackData[$track->id]['rating']; } else { echo '0'; } ?></span>

                            </div>

                            <div class="st">
                                <i class="fa fa-cloud-download"></i>
                                <span><?php if(isset($trackData[$track->id]['downloads'])) { echo $trackData[$track->id]['downloads']; } else { echo '0'; } ?></span>

                            </div>

                            <div class="st">
                                <i class="fa fa-comment"></i>
                                <span>0</span>
                            </div>

                            <div class="st">
                                <i class="fa fa-play-circle"></i>
                                <span><?php if(isset($trackData[$track->id]['plays'])) { echo $trackData[$track->id]['plays']; } else { echo '0'; } ?></span>

                            </div>
                            <div class="st">
                                <i class="fa fa-share-alt"></i>
                                <span>0</span>

                            </div>
                            </div>       
                        </div><!-- st-blk -->
                        </div>

                        <div class="col-md-4">

                        <p class="vrw text-right" style="text-align:right; font-size:10px;">
                        <?php

                          $feedback_video_link = url("Client_track_feedback_video?tId=".$track->id);

                        ?>
                         <a style="margin-right:10px; font-size:14px;" href="<?php echo $feedback_video_link; ?>">Video</a>  
                         <a style="margin-right:10px; font-size:14px;" href="<?php echo url("Client_edit_track?tId=".$track->id); ?>">Edit</a>
                         <?php
                        //  dd($tracks['data']);
                        if($track->review_count > 0){
                            $url = url("Client_track_review?tId=".$track->id);
                            $text = 'View Reviews';
                        }else{
                            $url = '';
                            $text = 'No Reviews Yet!';
                        }
                         ?>
                         <a style="font-size:14px;" href="<?php echo $url ?>"><?php echo $text ?></a>
                        <?php // echo url("Client_submitted_track_edit?tId=".$track->id); ?>

                        </p>
                        </div>
                        </div>
                    </div><!-- det-blk -->
                </li>
                <?php } ?>
            </ul>

        </div><!-- eof mtk-list -->
    </div>
			</div>
		</div>
        <div class="col-xl-3 col-12">
            @include('clients.dashboard.includes.my-tracks')
        </div>
	</div>
	</div>
	</div>
		
 </section>

 <script>

function sortBy(type,id)

{

    var records = document.getElementById('records').value;		

    window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;

}



function changeNumRecords(type,id,records)

{

    window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;

}

function goToPage(page, pid, urlString)
        {
            var param = '?';
            if (urlString.length > 3)
            {
                param = '&';
            }
            window.location = page + urlString + param + "page=" + pid;
        }




</script>


@endsection