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
	.track-name {
		display: inline-flex;
	}
	.play_pause_btn img {
		width: 35px;
		height: 15px;
		margin-top: 15px;
		display: none;
	}
	.play_pause_btn {
		margin: -10px 0 0 14px;
	}
	.track-list.track_active {
		background-color: #2b2b2b;
	}
    li.facebook_music a i.fa-facebook{
        color: #1877f2;
        font-size: 25px;
        position: relative;
        top: 15%;
    }  
    li.instagram_music a i.fa-instagram{
        color: #8a3ab9;
        font-size: 25px;
        position: relative;
        top: 15%;
    }  
    li.twitter_music a i.fa-twitter{
        color: #1DA1F2;
        font-size: 25px;
        position: relative;
        top: 15%;
    }
    li.youtube_music a i.fa-youtube{
        color: #FF0000;
        font-size: 25px;
        position: relative;
        top: 15%;
    }               
	li.apple_music a i.fa-itunes{
		color: #F6485A;
		font-size: 25px;
		position: relative;
		top: 15%;
	}
	li.spotify_music a i.fa-spotify{
		color: #1ED760;
		font-size: 25px;	
		position: relative;
		top: 15%;		
	}
	li.amazon_music a svg{
		width: 30px;
		height: 30px;
	}
	.track-details ul li {
		padding: 0px 10px !important;
	}
	
</style>
<script>
jQuery(document).ready(function ($) {
    $('.playButton').on('click', function () {
        const trackElement = $(this).closest('.track-list');
        const playPauseBtn = trackElement.find('.play_pause_btn img');
        $('.track-list .play_pause_btn img').not(playPauseBtn).hide();
		$('.tab-content .track-list').removeClass('track_active');
		$(this).parents('.track-list').addClass('track_active');
        playPauseBtn.show();
        const intervalId = setInterval(function () {
            if (!$('.header_sg1 .jp-video').hasClass('jp-state-playing')) {
                playPauseBtn.hide();
				$('.tab-content .track-list').removeClass('track_active');
                clearInterval(intervalId);
            }
        }, 1000);
    });
	 $('.jp-controls #playaudio, .jp-controls .jp-next, .jp-controls .jp-previous').on('click', function() {
        setTimeout(function () {
			const currentTitle = $('.jp-title').text().trim();
            $('.track-list .play_pause_btn img').hide();
            if ($('.header_sg1 .jp-video').hasClass('jp-state-playing')) {
                $('.track-list .track-name a').each(function() {
                    const trackTitle = $(this).text().trim();
                    if (trackTitle === currentTitle) {
                        $(this).closest('.track-list').find('.play_pause_btn img').show();
						$('.tab-content .track-list').removeClass('track_active');
						$(this).closest('.track-list').addClass('track_active');
                    }
                });
            }
        }, 1000);
        const intervalId = setInterval(function () {
            if (!$('.header_sg1 .jp-video').hasClass('jp-state-playing')) {
                $('.track-list .play_pause_btn img').hide();
				$('.tab-content .track-list').removeClass('track_active');
                clearInterval(intervalId);
            }
        }, 1000);
    });
});
</script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v13.0" nonce="o81zYhen"></script>
<!--<script async src="https://telegram.org/js/telegram-widget.js?16" data-telegram-share-url="https://core.telegram.org/widgets/share"></script>-->
  
 
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
            
            <!--<?php //if(!empty($result)){?>-->
            <!--    <div class="<?php //echo $class;?>" role="alert">-->
            <!--     <?php //echo $result;?>-->
            <!--    </div>-->
            <!--<?php //}?>    -->
                <h2>MY DASHBOARD</h2>
              </div>

                                            
              
              <div class="tabs-section">
                <div class="nav nav-tabs">
                               <a href="<?php echo url("Member_dashboard_newest_tracks"); ?>" class="pull-left nav-link active" id="newest_tracks_tab_mem_dash">NEWEST TRACKS</a>
                                <a href="<?php echo url("Member_dashboard_all_tracks"); ?>" class="pull-left nav-link" id="all_tracks_tab_mem_dash">VIEW ALL TRACKS</a>
                                <a href="<?php echo url("Member_dashboard_top_priority"); ?>" id="priority_tracks_tab_mem_dash" class="pull-left  nav-link <?php if(isset($sg_priority)){ echo "active"; } ?>">PRIORITY TRACKS</a>
                                <a href="<?php echo url("Member_dashboard_top_streaming"); ?>" id="streaming_tracks_tab_mem_dash" class="pull-left nav-link <?php if(isset($sg_topstrem)){ echo "active"; } ?>">TOP STREAMING</a>
                           </div>
                <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="new-tracks-tab" data-bs-toggle="tab" data-bs-target="#new-tracks" type="button" role="tab" aria-controls="new-tracks" aria-selected="true">Newest Tracks</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="all-tracks-tab" data-bs-toggle="tab" data-bs-target="#all-tracks" type="button" role="tab" aria-controls="all-tracks" aria-selected="false">All Tracks</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="priority-tracks-tab" data-bs-toggle="tab" data-bs-target="#priority-tracks" type="button" role="tab" aria-controls="priority-tracks" aria-selected="false">Priority Tracks</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="top-streaming-tab" data-bs-toggle="tab" data-bs-target="#top-streaming" type="button" role="tab" aria-controls="top-streaming" aria-selected="false">Top Streaming</button>
                  </li>
                </ul> -->
                <div class="tab-content" id="myTabContent">
                  <div class="tab-top-sec">
                    <div class="search-list">                      
                      <form class="">
                        <div class="input-group">
                          <input type="text" class="form-control rounded" placeholder="Search" name="searchKey" id="searchkey" aria-label="Search" aria-describedby="search-addon" value="<?php if(!empty($searchKey )) echo trim($searchKey); ?>" />

                          <button style="" type="submit" name="search" class="btn btn-theme btn-gradient" style="">search</button>
                          </div>
                        </form>
                        
                    </div>

                    <?php if ($numPages > 1) { ?>
                    <div class="list-pagination">
                    <div class="pgm">
                                <?php echo $start + 1; ?> - <?php echo $start + $numRecords; ?> OF <?php echo $num_records; ?>
                            </div>
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <li class="page-item">
                            <a class="page-link" aria-label="Previous" href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>','<?php echo $urlString; ?>')">
                              <span aria-hidden="true">&laquo;</span>
                              <span class="sr-only">Previous</span>
                            </a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#"><?php echo $currentPageNo; ?></a></li>                         
                          <li class="page-item">
                            <a class="page-link" href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>','<?php echo $urlString; ?>')" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                              <span class="sr-only">Next</span>
                              
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="tab-pane fade show active" id="new-tracks" role="tabpanel" aria-labelledby="new-tracks-tab">
                               <div class="mtop-list">
                                   <?php
                                //   pArr($tracks);
                                    if($tracks['numRows']==0){?>
                                      
                                       <h2>No records found.</h2>
                                    
                                        
                             <?php }
                                    $i = 1 + $start;
                                    foreach ($tracks['data'] as $track) { 
                                        // class variable to be added for distinguish between type of track
                                        $sgclass = '';
                                        if(isset($track->albumType)){
                                            if($track->albumType == 1){ 
                                                $sgclass = 'sg_track';
                                            }else if($track->albumType == 2){
                                                $sgclass = 'sg_album';
                                            }else if($track->albumType == 3){
                                                $sgclass = 'sg_ep';
                                            }else if($track->albumType == 4){
                                                $sgclass = 'sg_mixtape';
                                            } 
                                        }

                                        ?>
                                       <div class="track-list <?php echo $sgclass; ?>">
                                           
                                               
                                               <?php
                                                if ($downloads[$track->id] > 0) {
                                                    $review_link = url("Member_track_review_view?tid=" . $track->id);
                                                  
                                                    $href = url("Member_track_download_front_end?tid=" . $track->id);
                                                    $review_link=$href;
                                                    $label = 'DOWNLOAD';
                                                } else {
                                                    $review_link = url("Member_track_review?tid=" . $track->id);
                                                    $href = url("Member_track_review?tid=" . $track->id);
                                                    // $href = url("Member_track_download_front_end?tid=" . $track->id);
                                                    $label = 'LEAVE A REVIEW TO DOWNLOAD';
                                                }
                                                
                                                
                                                ?>                                               
                                                    <div class="tack-img">
                                                           <?php
                                                              if(!empty($track->pCloudFileID)){
                                                                  $img_get= url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);
                                                                  
                                                              }
                                                              else if(!empty($track->imgpage)){

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
                                                               

                                                            if ($mp3s[$track->id]['numRows'] > 0) {
                                                                $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                                $fileid = (int) $trackLocation;
                                                                if (strpos($trackLocation, '.mp3') !== false) {
                                                                    $trackLink =url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                                } else if ((int) $fileid) {
                                                                    $trackLink =url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                                }else {
                                                                    $trackLink = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                                }
                                                                // echo $trackLink;
                                                            ?>
                                                               <a href="javascript:void(0);" onclick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','<?php echo $trackLink; ?>','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','<?php echo $img_get; ?>','<?php echo $track->id; ?>')">
                                                                   <img src="<?php echo $img_get; ?>" width="80" height="80" />
                                                                   <img class="playButton" src="assets/img/play-btn.png" width="80" height="80" />
                                                               </a>
                                                           <?php
                                                            } else { ?>
                                                               <a href="javascript:void(0);" onclick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png','<?php echo $track->id; ?>')">

                                                               <img src="<?php echo $img_get; ?>" width="80" height="80" />
                                                                   <img class="playButton" src="assets/img/play-btn.png" width="80" height="80" />
                                                                   <!-- <img src="assets/img/play-btn.png"> -->
                                                               </a>
                                                           <?php } ?>
                                                           <p><small class="text-dim"><?php
                                                            $addedOn = explode(' ', $track->added);
                                                            $addedDate = explode('-', $addedOn[0]);
                                                            echo $addedDate = $addedDate[1] . '/' . $addedDate[2] . '/' . $addedDate[0];
                                                            ?></small></p>
                                                    </div> 
                                                   
                                                   
                                                   <div class="track-details">
                                                   
                                                   
                                                   <?php if(!empty(trim(urldecode($track->title)))){ ?>
                                                        <h5 class="track-name"><a href="<?php echo  $review_link; ?>"><?php echo urldecode($track->title); ?></a>
														<div class="play_pause_btn">
															<img src="<?php echo asset('assets/images/eqlzr.gif'); ?>" />
														</div>
                                                            <?php if($track->priority==1){ ?>
                                                                <span class="badge badge-custom priority" >Priority</span>
                                                        <?php
                                                        }
                                                        ?></h5>
                                                        <div class="track-info">
                                                            <ul>
                                                        <?php 
                                                    }
                                                    if(!empty(trim(urldecode($track->artist)))){ ?>
                                                        <li><span class="text-dim">Artist: <a href="<?php echo  $review_link; ?>"><?php echo urldecode($track->artist); ?></a></span></li>
                                                    <?php 
                                                    }
                                                    if(!empty(trim(urldecode($track->album)))){ ?>
                                                        <li><span class="text-dim">Album: <a href="<?php echo  $review_link; ?>"><?php echo urldecode($track->album); ?></a></span></li>
                                                    <?php 
                                                    }
                                                    if(!empty(trim(urldecode($track->producer)))){ ?>
                                                        <li><span class="text-dim">Producer: <a href="<?php echo  $review_link; ?>"><?php echo urldecode($track->producer); ?></a></span></li>
                                                    <?php 
                                                    }
                                                    ?>
                                                </ul>
                                                </div>
                                                <div class="track-info-2">
													<ul>
														<?php if(!empty($social[$track->id]['numRows'])){ ?>
														<li class="social_media">
																<?php 																
																if ($social[$track->id]['numRows'] > 0) {
																	if (strlen($social[$track->id]['data'][0]->facebook) > 0) {
																?><a class="twitter-follow-button" target="_blank" href="https://www.facebook.com/<?php echo $social[$track->id]['data'][0]->facebook;  ?>">
																			<img src="<?php echo asset('assets/images/facebook.png'); ?>" width="20" height="20" />
																		</a> <?php
																			}
																			if (strlen($social[$track->id]['data'][0]->twitter) > 0) {
																				?><a class="twitter-follow-button" target="_blank" href="https://www.twitter.com/<?php echo $social[$track->id]['data'][0]->twitter;  ?>">
																			<img src="<?php echo asset('assets/images/twitter.png'); ?>" width="20" height="20" />
																		</a> <?php
																			}
																			if (strlen($social[$track->id]['data'][0]->instagram) > 0) {
																				?><a class="twitter-follow-button" target="_blank" href="https://www.instagram.com/<?php echo $social[$track->id]['data'][0]->instagram;  ?>">
																			<img src="<?php echo asset('assets/images/instagram.png'); ?>" width="20" height="20" />
																		</a> <?php
																			}
																				?>
																<?php } ?>
															
															
                                                        </li><?php } ?>
														<li class="share_buttons">
															
														<div class="fb-share-button" data-href="<?php url("Member_track_review_view?tid=" . $track->id) ?>" data-layout="button" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>
														<?php if($member_package == 'Premium'){ 
														$url = url("Member_track_review_view?tid=" . $track->id);
														echo '<a href="https://telegram.me/share/url?url='.$url.'">';?> <img src="<?php echo asset('assets/images/telegram.png'); ?>" width="60" height="20" /><?php echo '</a>';
														 } ?>
														</li>
														<?php if($track->label){ ?><li><span><?php echo urldecode($track->label); ?></span>  </li><?php } ?>

                                                        <?php if($track->facebookLink && filter_var($track->facebookLink, FILTER_VALIDATE_URL) ){ ?><li class="facebook_music"><span><a href="<?php echo urldecode($track->facebookLink); ?>" target='_blank'><i class="fab fa-facebook"></i></a></span></li><?php } ?>	

                                                        <?php if($track->instagramLink && filter_var($track->instagramLink, FILTER_VALIDATE_URL) ){ ?><li class="instagram_music"><span><a href="<?php echo urldecode($track->instagramLink); ?>" target='_blank'><i class="fab fa-instagram"></i></a></span></li><?php } ?> 

                                                        <?php if($track->twitterLink && filter_var($track->twitterLink, FILTER_VALIDATE_URL) ){ ?><li class="twitter_music"><span><a href="<?php echo urldecode($track->twitterLink); ?>" target='_blank'><i class="fab fa-twitter"></i></a></span></li><?php } ?> 

														<?php if($track->applemusicLink && filter_var($track->applemusicLink, FILTER_VALIDATE_URL) ){ ?><li class="apple_music"><span><a href="<?php echo urldecode($track->applemusicLink); ?>" target='_blank'><i class="fab fa-itunes"></i></a></span></li><?php } ?>
														
														<?php if($track->amazonLink && filter_var($track->amazonLink, FILTER_VALIDATE_URL)){ ?>
															<li class="amazon_music"><span>
																<a href="<?php echo urldecode($track->amazonLink); ?>" target='_blank'>
																<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48"><path fill="#3cd0d9" fill-rule="evenodd" d="M42,15c0-4.967-4.033-9-9-9H15c-4.967,0-9,4.033-9,9v18	c0,4.967,4.033,9,9,9h18c4.967,0,9-4.033,9-9V15z" clip-rule="evenodd"></path><path fill="#fff" d="M35.982,28c-0.936,0-2.048,0.233-2.867,0.817c-0.234,0.175-0.234,0.408,0.059,0.408	c0.936-0.117,3.043-0.408,3.452,0.117c0.351,0.467-0.41,2.451-0.761,3.384c-0.117,0.292,0.117,0.35,0.351,0.175	c1.58-1.342,1.989-4.085,1.697-4.435C37.737,28.175,36.918,28,35.982,28z"></path><path fill="#fff" d="M10.15,28.8c1.81,1.133,3.64,2.215,5.561,3.032c1.914,0.815,3.922,1.398,5.94,1.406	c2.055,0.055,4.142-0.227,6.167-0.688c2.032-0.468,4.012-1.173,5.957-1.998c0.254-0.109,0.549,0.01,0.657,0.264	c0.1,0.235,0.006,0.505-0.21,0.631c-1.887,1.089-3.928,1.933-6.054,2.505c-2.132,0.572-4.334,0.822-6.563,0.786	c-2.276-0.119-4.455-0.812-6.405-1.821c-1.955-1.014-3.73-2.29-5.351-3.717c-0.104-0.092-0.114-0.25-0.023-0.354	C9.91,28.753,10.048,28.736,10.15,28.8z"></path><path fill="#fff" d="M22.5,26h-0.404C20.94,26,20,25.06,20,23.904V20h1v3.904C21,24.508,21.492,25,22.096,25H22.5	c0.603,0,1.5-0.897,1.5-1.5v-1h1v1C25,24.668,23.668,26,22.5,26z"></path><rect width="1" height="6" x="11" y="20" fill="#fff" fill-rule="evenodd" clip-rule="evenodd"></rect><path fill="#fff" d="M11.391,22.21c-0.021-0.487,0.147-0.891,0.372-1.257c0.117-0.183,0.246-0.358,0.428-0.523	c0.202-0.171,0.434-0.283,0.672-0.359c0.984-0.292,2.011,0.305,2.438,1.196c0.223,0.451,0.202,0.994,0.199,1.324V26h-1v-3.409	c0.002-0.421,0.008-0.664-0.108-0.906c-0.209-0.498-0.826-0.719-1.335-0.704c-0.446-0.019-1.043,0.69-1.167,1.229H11.391z"></path><path fill="#fff" d="M14.75,22.21c-0.021-0.487,0.147-0.891,0.372-1.257c0.117-0.183,0.246-0.358,0.428-0.523	c0.202-0.171,0.434-0.283,0.672-0.359c0.984-0.292,2.011,0.305,2.438,1.196c0.223,0.451,0.202,0.994,0.199,1.324V26h-1v-3.409	c0.002-0.421,0.008-0.664-0.108-0.906c-0.209-0.498-0.826-0.719-1.335-0.704c-0.446-0.019-1.043,0.69-1.167,1.229H14.75z"></path><rect width="1" height="6" x="24" y="20" fill="#fff" fill-rule="evenodd" clip-rule="evenodd"></rect><rect width="1" height="6" x="31" y="20" fill="#fff" fill-rule="evenodd" clip-rule="evenodd"></rect><circle cx="31.5" cy="18" r="1" fill="#fff" fill-rule="evenodd" clip-rule="evenodd"></circle><path fill="#fff" d="M34,22.62v0.76c0,0.89,0.73,1.62,1.62,1.62c0.43,0,0.84-0.17,1.15-0.47L37,24.3v1.3	c-0.41,0.26-0.88,0.4-1.38,0.4C34.18,26,33,24.82,33,23.38v-0.76c0-1.44,1.18-2.62,2.62-2.62c0.5,0,0.97,0.14,1.38,0.4v1.3	l-0.23-0.23c-0.31-0.3-0.72-0.47-1.15-0.47C34.73,21,34,21.73,34,22.62z"></path><path fill="#fff" d="M27.245,20.955C27.145,21.025,27,21.23,27,21.5c0,0.58,0.32,0.75,1.16,1.03	C28.94,22.78,30,23.14,30,24.5c0,0.52-0.2,0.95-0.59,1.25c-0.49,0.38-1.23,0.5-1.94,0.5c-0.54,0-1.08-0.07-1.47-0.14v-1.02	c0.94,0.19,2.28,0.28,2.79-0.13c0.1-0.07,0.21-0.19,0.21-0.46c0-0.58-0.32-0.75-1.16-1.03C27.06,23.22,26,22.86,26,21.5	c0-0.52,0.2-0.95,0.59-1.25c0.87-0.68,2.307-0.499,3.193-0.164v1.02C28.857,20.572,27.755,20.545,27.245,20.955z"></path></svg>
															</a>
															</span></li>
														<?php } ?>
														<?php if($track->spotifyLink && filter_var($track->spotifyLink, FILTER_VALIDATE_URL)){ ?><li class="spotify_music"><span><a href="<?php echo urldecode($track->spotifyLink); ?>" target='_blank'><i class="fab fa-spotify"></i></a></span></li><?php } ?>
                                                        <?php if($track->videoURL && filter_var($track->videoURL, FILTER_VALIDATE_URL)){ ?><li class="youtube_music"><span><a href="<?php echo urldecode($track->videoURL); ?>" target='_blank'><i class="fab fa-youtube"></i></a></span></li><?php } ?>                                                        
													</ul>
                                                    <div class="text-md-right rew">
                                                    <?php if (in_array($track->id, $tracks['downloaded'])){
															echo "<span class='download-success'>Downloaded</span>";
                                                        }
                                                    ?>
                                                    <a href="<?php echo $href; ?>">
													   <p style="color: rgb(82 208 248); font-size: 12px;"><?php echo $label; ?> <i class="fa fa-cloud-download"></i></p>
												   </a>
												    </div>
												</div>
												
											</div>
                                               
                                                   
                                                        
                                                      
                                              <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="text-md-right rew">
                                                       <a href="<?php echo $href; ?>">
                                                           <p style="color: rgb(82 208 248); font-size: 12px;"><?php echo $label; ?> <i class="fa fa-cloud-download"></i></p>
                                                       </a>
                                                   </div> 
                                               </div>-->
                                           
                                       </div><!-- eof item -->
                                   <?php $i++;
                                    } ?>
                                   <!--  <div class="smore text-right"><a href="#">SEE MORE <i class="fa fa-caret-right"></i></a></div>-->
                               </div><!-- eof mtop-list -->
                    <!-- Newest Tracks ends here  -->
                    <div class="album-d-more">
                    <span class="smore"><a href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>','<?php echo $urlString; ?>')">SEE MORE <i class="fa fa-caret-right"></i></a></span></div>

                  </div>
                  <div class="tab-pane fade" id="all-tracks" role="tabpanel" aria-labelledby="all-tracks-tab">content2</div>
                  <div class="tab-pane fade" id="priority-tracks" role="tabpanel" aria-labelledby="priority-tracks-tab">content3</div>
                  <div class="tab-pane fade" id="top-streaming" role="tabpanel" aria-labelledby="top-streaming-tab">content3</div>
                </div>

              </div>
              <!---tab section end--->
              <!--album-download-->
            @include('layouts.include.content-footer') 
                         
           </div>
         </div>
       </div>
     </div>
	 </section>
	 
@endsection