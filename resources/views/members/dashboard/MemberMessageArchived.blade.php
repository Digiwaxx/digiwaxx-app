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
    .fs-2{
        opacity:0.8;
        font-size: 1rem!important;
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

    color: rgb(82 208 248);
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
</style>
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
              <div class="tabs-section">
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
                      <div class="input-group">
                      <form class="form-inline" style="display: inline-flex;">
    
                          <!-- <input type="text" class="form-control rounded" placeholder="Search" name="searchKey" id="searchkey" aria-label="Search" aria-describedby="search-addon" value="<?php if(!empty($searchKey )) echo $searchKey; ?>" />

                          <button style="margin-left: 10px;height: 60px;" type="submit" name="search" class="btn btn-theme btn-gradient" style="">search</button> -->
                        </form>
                        </div>
                    </div>
                    <div class="list-pagination">
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <!-- <li class="page-item">
                            <a class="page-link" aria-label="Previous" href="javascript:void(0);" onclick="goToPage('<?php if(!empty($currentPage)) echo $currentPage; ?>','<?php if(!empty($currentPageNo)) echo $currentPageNo - 1; ?>','<?php if(!empty($urlString)) echo $urlString; ?>')">
                              <span aria-hidden="true">&laquo;</span>
                              <span class="sr-only">Previous</span>
                            </a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#"><?php if(!empty($currentPageNo)) echo $currentPageNo; ?></a></li>                         
                          <li class="page-item">
                            <a class="page-link" href="javascript:void(0);" onclick="goToPage('<?php if(!empty($currentPage)) echo $currentPage; ?>','<?php if(!empty($currentPageNo)) echo $currentPageNo + 1; ?>','<?php if(!empty($urlString)) echo $urlString; ?>')" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                              <span class="sr-only">Next</span>
                              
                            </a>
                          </li> -->
                        </ul>
                      </nav>
                    </div>
                  </div>
                  <div class="tab-pane fade show active" id="new-tracks" role="tabpanel" aria-labelledby="new-tracks-tab">

                      			<!-- START MIDDLE BLOCK -->
                
                                  <div class="col-lg-12 col-md-12">
                    	<div class="msg-con-blk f-block">
                        	
                            
                            <h1>MESSAGES</h1> 

                            <h2>YOUR CONVERSATION WITH <?php echo strtoupper($clientName); ?></h2>
                            
                            <div class="msg-con">
                            	<div class="row">
                                	<div class="col-lg-2 col-md-3 col-sm-3">
                                    	<div class="avatar">
                                    	  <?php  if(is_numeric($clientImage)){
									   
                                        $imgSrc=url('/pCloudImgDownload.php?fileID='.$clientImage);
                        
									}
									
									else{
									    $imgSrc = asset('public/images/profile-pic.png');
									}
									?>
                                        	<img src="<?php echo $imgSrc; ?>"> <br>
                                            <?php echo $clientName; ?>                        
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-10 col-md-9 col-sm-9">
                                    	<div class="smsg mCustomScrollbar">
											<div class="form-group clearfix">
				                                <textarea style="color:black;" class="form-control" name="message" id="message" placeholder="Enter message" rows="5"></textarea>
                           		              	<input  type="hidden" id="cid" value="<?php echo $cid; ?>">
												<input  type="button" onclick="sendMessage()" class="mt-0 login_btn btn ems btn-theme btn-gradient" value="Send Message">
                                    	    </div>
                                            <div id="displayMessages">
											<?php
			$mons = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');

											if(isset($conversation['numRows']) && $conversation['numRows']>0)
											{
											  foreach($conversation['data'] as $message)
											  {
										
			  		if(isset($archiveMsgs) && !(in_array($message->messageId,$archiveMsgs)))
					 {	  
											  
											  if($message->senderType==2 && $message->senderId==Session::get('memberId'))
											  {
											    $class = 'tmsg';
											  }
											  else
											  {
											    $class = 'fmsg';
											  }
											  ?>
											  
	   									  
											  <div class="<?php echo $class; ?>">
                                            	<p><?php echo $message->message; ?></p>
                                                
												
												<?php 
												$dateTime =  explode(' ',$message->dateTime); 
												$date =  explode('-',$dateTime[0]);
												$date1 = $mons[$date[1]].' ';
												$date2 = $date[2].', '.$date[0]; 
												$displayDate = $date1.$date2;
												
												
												?>
                                                <div class="dt"><?php echo $displayDate; ?> 
												
												<?php if(in_array($message->messageId,$starMsgs)) 
									 {
									    $starClass = 'goldStar';
									 }
									 else
									 {
									    $starClass = 'silverStar';
									 }
									 ?>
												
												<span style="float:right; margin-left:20px;">
	   <span id="star<?php echo $message->messageId; ?>" class="<?php echo $starClass; ?>"  onclick="markStar('<?php echo $message->messageId; ?>')">
	     <i class="fa fa-star" aria-hidden="true"></i>
	   </span>
				
		
		<span id="archive<?php echo $message->messageId; ?>" class="inactiveArchive" onclick="makeArchive('<?php echo $message->messageId; ?>')">		
				<i class="fa fa-archive" aria-hidden="true"></i>
		</span>							  
	</span>
												
												
												</div>
                                                <div class="shape"></div>
                                            </div>
											
											<?php 
											  
											    }
											  }
											
											}
											
											
											?>
											
											
                                            
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div><!-- eof msg-con -->    

                        </div>
                    </div><!-- eof middle block -->
                 
                    <!-- Middle block ends here  -->

                    <!-- <div class="album-d-more">

                             <span class="smore"><a href="javascript:void(0);" onclick="goToPage('<?php if(!empty($currentPage)) echo $currentPage; ?>','<?php if(!empty($currentPageNo)) echo $currentPageNo + 1; ?>','<?php if(!empty($urlString)) echo $urlString; ?>')">SEE MORE <i class="fa fa-caret-right"></i></a></span></div>

                  </div> -->
                  <div class="tab-pane fade" id="all-tracks" role="tabpanel" aria-labelledby="all-tracks-tab">content2</div>
                  <div class="tab-pane fade" id="priority-tracks" role="tabpanel" aria-labelledby="priority-tracks-tab">content3</div>
                  <div class="tab-pane fade" id="top-streaming" role="tabpanel" aria-labelledby="top-streaming-tab">content3</div>
                </div>

              </div>
              <!---tab section end--->
              <!--album-download-->
              <div class="album-d-sec">
                <div class="heading-border">
                  <h4>STAFF PICKS</h4>
                </div>
                
                <div class="row">
                        <div class="col-md-12-cols col-sm-6 col-12">
                          <!--download tracks-->

                          <?php if ($staffTracks['numRows'] > 0) { ?>
                          <div class="stpk-blk ntf-lst-blk" style="padding:10px;">
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
                                                  $var5 = asset("ImagesUp/" . $track->imgpage);
                                              } else {
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
                                      ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                  <div class="col-md-12-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <?php if ($youTracks['numRows'] > 0) { ?>
                        <div class="stpk-blk ntf-lst-blk" style="padding:10px;">
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
                                                $var5 = asset("ImagesUp/" . $track->imgpage);
                                            }
                                         } else {
                                                $var1 = urldecode($track->title);
                                                $var2 = urldecode($track->artist);
                                                $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';
                                                $var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';
                                            }
                                    ?>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="tdpic">
                                                    <a href="javascript:void(0);" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php echo $var5; ?>','<?php echo $track->id; ?>')">
                                                        <img src="<?php echo asset('ImagesUp/' . $track->imgpage); ?>" width="108" height="108" style="height:138px;" class="img-responsive">
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

<script>
		
		window.onload = function() {
		
		setInterval(getMsgs, 1000);
		}
		
		//setInterval(getMsgs()},3000);
		
		function getMsgs()
		{
		var cid = document.getElementById('cid').value;
    $.ajax({url: "Member_send_message?getConversation=1&cid="+cid, success: function(result){
        document.getElementById("displayMessages").innerHTML = result;
    }});
		
	}
		function makeArchive(messageId)
		{
		
		var className = document.getElementById('archive'+messageId).className;
		
		if(className==='activeArchive')
		{
		  document.getElementById('archive'+messageId).className = "inactiveArchive";
		}
		else
		{	
		  document.getElementById('archive'+messageId).className = "activeArchive";
		}
		
		 var cid = document.getElementById('cid').value;
		 $.ajax({url: "Member_send_message?messageId="+messageId+"&archive=1&cid="+cid, success: function(result){
                  
             }});
		
		}
		
		function markStar(messageId)
		{
		
		
		var className = document.getElementById('star'+messageId).className;
		
		if(className==='goldStar')
		{
		  document.getElementById('star'+messageId).className = "silverStar";
		}
		else
		{	
		  document.getElementById('star'+messageId).className = "goldStar";
		}
		 
		 var cid = document.getElementById('cid').value;
		 $.ajax({url: "Member_send_message?messageId="+messageId+"&star=1&cid="+cid, success: function(result){
                    
					
             }});
		}
		
		function sendMessage()
		{
		
		var message = document.getElementById('message').value;
		var cid = document.getElementById('cid').value;
		
		document.getElementById('message').value = '';
		document.getElementById('message').focus();
	
		if(message.length>0)
		{
		
    $.ajax({url: "Member_send_message?message="+message+"&cid="+cid, success: function(result){

        var obj = JSON.parse(result);
        //document.getElementById("demo").innerHTML = myobj.name;
		if(obj.response>0)
		{
		
		  
		  var msgDiv = document.createElement("div");
		  msgDiv.setAttribute("class", "tmsg");
		  
		  var dtDiv = document.createElement("div");
		  dtDiv.setAttribute("class", "dt");
		  
		  var shapeDiv = document.createElement("div");
		  shapeDiv.setAttribute("class", "shape");
		  
		  
		  var node = document.createElement("p");
		  var textnode = document.createTextNode(message);
		  node.appendChild(textnode);
		  msgDiv.appendChild(node);
		  
	      var textnode1 = document.createTextNode(obj.dt);
		  dtDiv.appendChild(textnode1);
		  msgDiv.appendChild(dtDiv);
		  
		  
		  msgDiv.appendChild(shapeDiv);
		  
		  $("#displayMessages").prepend(msgDiv);
		}
		else
		{
		
		}


    }});
		}
		
		}
		
		
		</script>
	 
@endsection