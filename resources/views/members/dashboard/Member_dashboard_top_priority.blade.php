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
    .rew a:hover, .rew a:focus{
        color: #e5a4ce;
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

    #priority_tracks_tab_mem_dash{

    /* color: rgb(82 208 248); */
    border-bottom: 1px solid rgb(82 208 248);

    }
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
	
	img.play_pause_btn {
		max-width: 35px;
		height: 15px;
		margin-left: -5px;
		display: none;
	}
	.mtop-list .item.track_active {
		background-color: #2b2b2b;
	}
	
</style>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v13.0" nonce="o81zYhen"></script>
<!--<script async src="https://telegram.org/js/telegram-widget.js?16" data-telegram-share-url="https://core.telegram.org/widgets/share"></script>-->
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>MY DASHBOARD</h2>
              </div>
              
              <div class="tabs-section">
                <div class="nav nav-tabs">
                               <a href="<?php echo url("Member_dashboard_newest_tracks"); ?>" class="pull-left nav-link" id="newest_tracks_tab_mem_dash">NEWEST TRACKS</a>
                                <a href="<?php echo url("Member_dashboard_all_tracks"); ?>" class="pull-left nav-link" id="all_tracks_tab_mem_dash">VIEW ALL TRACKS</a>
                                <a href="<?php echo url("Member_dashboard_top_priority"); ?>" id="priority_tracks_tab_mem_dash" class="pull-left nav-link <?php if(isset($sg_priority)){ echo "active"; } ?>  active">PRIORITY TRACKS</a>
                                <a href="<?php echo url("Member_dashboard_top_streaming"); ?>" id="streaming_tracks_tab_mem_dash" class="pull-left <?php if(isset($sg_topstrem)){ echo "active"; } ?> nav-link">TOP STREAMING</a>
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

                          <button style="margin-left: 10px;height: 46px;" type="submit" name="search" class="btn btn-theme btn-gradient" style="">search</button>
                      
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
                        if ($tracks['numRows'] > 0) {
                            foreach ($tracks['data'] as $track) {
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
                                <div class="item <?php echo $sgclass; ?>">
                                    <div class="row">                                        
                                        <?php
                                        if ($downloads[$track->id] > 0) {
                                            $review_link =  url("Member_track_review_view?tid=" . $track->id);
                                            $href =  url("Member_track_download_front_end?tid=" . $track->id);
                                            $review_link=$href;
                                            $label = 'DOWNLOAD';
                                        } else {
                                            $review_link =  url("Member_track_review?tid=" . $track->id);
                                            $href =  url("Member_track_review?tid=" . $track->id);
                                            //  $href =  url("Member_track_download_front_end?tid=" . $track->id);
                                            $label = 'LEAVE A REVIEW TO DOWNLOAD';
                                        }
                                        ?>
                                        <div class="col-sm-9 col-xs-9">
                                            
                                            <div class="img_div">
                                                <?php

                                               // dd(asset('ImagesUp/'.$track->imgpage));
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
                                                        $trackLink = url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                    } else if ((int) $fileid) {
                                                        $trackLink = url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                    } else {
                                                        $trackLink = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                    }
                                                ?>
                                                    <a href="javascript:void(0);">
                                                        <img src="<?php echo $img_get; ?>" width="80" height="80" />
                                                        <img class="playButton" src="<?php echo asset('assets/img/play-btn.png'); ?>" width="80" height="80" onclick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','<?php echo $trackLink; ?>','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','<?php echo $img_get; ?>','<?php echo $track->id; ?>')" />
                                                    </a>
                                                <?php
                                                } else { ?>
                                                    <a href="javascript:void(0);">
                                                        <img src="<?php echo asset('assets/img/play-btn.png'); ?>" onclick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png')" /></a>
                                                <?php } ?>
                                            </div>
                                            
                                            
                                                <?php if(!empty(trim(urldecode($track->title)))){ ?>
                                                    <p class="track--title">
														<strong>
															<a href="<?php echo $review_link; ?>"><?php echo urldecode($track->title); ?></a>
															<img src="<?php echo asset('assets/images/eqlzr.gif'); ?>"  class="play_pause_btn playingnow" />
														</strong>
													</p>
                                                    <?php 
                                                }
                                                if(!empty(trim(urldecode($track->artist)))){ ?>
                                                    <span class="text-dim">Artist: <a href="<?php echo $review_link; ?>"><?php echo urldecode($track->artist); ?></a><span class="divider">|</span></span>
                                                <?php 
                                                }
                                                if(!empty(trim(urldecode($track->album)))){ ?>
                                                    <span class="text-dim">Album: <a href="<?php echo $review_link; ?>"><?php echo urldecode($track->album); ?></a><span class="divider">|</span></span>
                                                <?php 
                                                }
                                                ?>
                                                
                                                
                                            <div class="social_media">
                                                <?php 

                                                if(!empty($social[$track->id]['numRows'])){
                                                
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
                                                <?php } 
                                                
                                                        }?>
                                            </div>
                                            <div class="share_buttons">
                                                        
                                                    <div class="fb-share-button" data-href="<?php url("Member_track_review_view?tid=" . $track->id) ?>" data-layout="button" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>
                                                        <?php if($member_package == 'Premium'){ 
                                                    $url = url("Member_track_review_view?tid=" . $track->id);
                                                    echo '<a href="https://telegram.me/share/url?url='.$url.'">';?> <img src="<?php echo asset('assets/images/telegram.png'); ?>" width="60" height="20" /><?php echo '</a>';
                                                     } ?>
                                            </div>
                                            
                                        </div>
                                        
                                        <!--   <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                            <p>-->
                                        <?php
                                        /* $addedOn = explode(' ',$track->added);
											         $addedDate = explode('-',$addedOn[0]);
													echo $addedDate = $addedDate[1].'/'.$addedDate[2].'/'.$addedDate[0];
											*/
                                        ?>
                                        <!-- </p>
                                        </div>-->
                                        <div class="col-lg-3">
                                            <a href="Member_track_label?label=<?php echo $track->label; ?>"><?php echo urldecode($track->label); ?></a>
                                            <?php 
                                            
                                            if (in_array($track->id, $tracks['downloaded'])){
                                            	echo "<p class='download-success'>Downloaded</p>";
                                            }

                                            ?>
                                            <p>
                                                <span class="mr-2 rew"><a class="link-active" href="<?php echo $href; ?>"><?php echo $label; ?></a></span>
                                                <span>
                                                        <?php if (Session::get('memberPackage') > 1) { ?>
                                                            <a class="link-active" href="<?php if(!empty($track->client)) echo url('Member_send_message?cid=' . $track->client); ?>">Send Message</a>
                                                        <?php } ?>
                                                </span>
                                            </p>
                                        </div>
                                        
                                        
                                    </div><!-- eof row -->
                                </div><!-- eof item -->
                            <?php }
                        } else { ?>
                                     <h2>No records found.</h2>
                        <?php } ?>
                    <!-- Priority Tracks ends here  -->

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


     <script>
    function searchTracks(page, urlString) {
        var param = '?';
        if (urlString.length > 3) {
            param = '&';
        }
        var bpm = document.getElementById('bpm').value;
        var version = document.getElementById('version').value;
        var genre = document.getElementById('genre').value;
        window.location = page + urlString + param + "bpm=" + bpm + "&version=" + version + "&genre=" + genre + "&search=yes";
    }
</script>
	 
@endsection