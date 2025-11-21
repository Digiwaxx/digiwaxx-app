@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>My Crate</h2>
              </div>
            <div class="tabs-section">
              
			    <div class="mem-dblk f-block">                    
                    <?php if (isset($welcomeMsg)) { ?>
                        <div class="alert alert-primary alert-dismissable">
                            <?php echo $welcomeMsg; ?>
                        </div>
                    <?php } ?>
                    <div class="sby-blk clearfix" style="display:none;">
                        <div>
                            <form class="form-inline">
                                <div class="form-group" style="width:78%">
                                    <input type="text" class="form-control" id="searchKey" name="searchKey" placeholder="Search" value="<?php echo $searchKey; ?>" style="height:32px; width:100%; font-size:14px;">
                                    <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
                                    <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
                                </div>
                                <button type="submit" name="search" class="btn btn-default" style="background:#392152; color:#FFF;">Search</button>
                            </form>
                        </div>
                        <div style="clear:both;"></div>
                        <?php
                        $sortClass = '';
                        $orderByAsc = 'inline';
                        $orderByDesc = 'none';
                        $orderById = 2;
                        if (strcmp($sortBy, 'song') == 0) {
                            $sortClass = 'active';
                            if ($sortOrder == 2) {
                                $orderByAsc = 'none';
                                $orderByDesc = 'inline';
                                $orderById = 1;
                            }
                        } ?>
                        <a href="javascript:void(0);" onclick="sortBy('<?php echo $currentPage; ?>','song','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">
                            SONG
                            <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
                            <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
                        </a>
                        <?php
                        $sortClass = '';
                        $orderByAsc = 'inline';
                        $orderByDesc = 'none';
                        $orderById = 2;
                        if (strcmp($sortBy, 'artist') == 0) {
                            $sortClass = 'active';
                            if ($sortOrder == 2) {
                                $orderByAsc = 'none';
                                $orderByDesc = 'inline';
                                $orderById = 1;
                            }
                        } ?>
                        <a href="javascript:void(0);" onclick="sortBy('<?php echo $currentPage; ?>','artist','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>"> ARTIST
                            <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
                            <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
                        </a>
                        <?php
                        $sortClass = '';
                        $orderByAsc = 'inline';
                        $orderByDesc = 'none';
                        $orderById = 2;
                        if (strcmp($sortBy, 'album') == 0) {
                            $sortClass = 'active';
                            if ($sortOrder == 2) {
                                $orderByAsc = 'none';
                                $orderByDesc = 'inline';
                                $orderById = 1;
                            }
                        } ?>
                        <a href="javascript:void(0);" onclick="sortBy('<?php echo $currentPage; ?>','album','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>"> ALBUM
                            <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
                            <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
                        </a>
                        <?php
                        $sortClass = '';
                        $orderByAsc = 'inline';
                        $orderByDesc = 'none';
                        $orderById = 2;
                        if (strcmp($sortBy, 'label') == 0) {
                            $sortClass = 'active';
                            if ($sortOrder == 2) {
                                $orderByAsc = 'none';
                                $orderByDesc = 'inline';
                                $orderById = 1;
                            }
                        } ?>
                        <a href="javascript:void(0);" onclick="sortBy('<?php echo $currentPage; ?>','label','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>"> LABEL
                            <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
                            <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
                        </a>
                        <?php
                        $sortClass = '';
                        $orderByAsc = 'inline';
                        $orderByDesc = 'none';
                        $orderById = 2;
                        if (strcmp($sortBy, 'date') == 0) {
                            $sortClass = 'active';
                            if ($sortOrder == 2) {
                                $orderByAsc = 'none';
                                $orderByDesc = 'inline';
                                $orderById = 1;
                            }
                        } ?>
                        <a href="javascript:void(0);" onclick="sortBy('<?php echo $currentPage; ?>','date','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>"> DATE
                            <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
                            <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
                        </a>
                        <?php
                        $sortClass = '';
                        $orderByAsc = 'inline';
                        $orderByDesc = 'none';
                        $orderById = 2;
                        if (strcmp($sortBy, 'bpm') == 0) {
                            $sortClass = 'active';
                            if ($sortOrder == 2) {
                                $orderByAsc = 'none';
                                $orderByDesc = 'inline';
                                $orderById = 1;
                            }
                        } ?>
                        <a href="javascript:void(0);" onclick="sortBy('<?php echo $currentPage; ?>','bpm','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>"> BPM
                            <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i>
                            <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
                        </a>
                    </div><!-- eof sby-blk -->
                    <div class="fby-blk clearfix" >
                        <div style="clear:both;"></div>
                        <div class="nop" style="display:none">
                            <select class="rfn-sb npg selectpicker" onchange="changeNumRecords('<?php echo $currentPage; ?>','<?php echo $urlRecordString; ?>',this.value)">
                                <option <?php if ($numRecords == 10) {  ?> selected="selected" <?php } ?> value="10">10 PER PAGE</option>
                                <option <?php if ($numRecords == 20) {  ?> selected="selected" <?php } ?> value="20">20 PER PAGE</option>
                                <option <?php if ($numRecords == 30) {  ?> selected="selected" <?php } ?> value="30">30 PER PAGE</option>
                                <option <?php if ($numRecords == 40) {  ?> selected="selected" <?php } ?> value="40">40 PER PAGE</option>
                                <option <?php if ($numRecords == 50) {  ?> selected="selected" <?php } ?> value="50">50 PER PAGE</option>
                            </select>
                        </div><!-- eof nop -->
                        <?php if ($numPages > 1) { ?>
                            <div class="pgm">
                                <?php echo $start + 1; ?> - <?php echo $start + $numRecords; ?> OF <?php echo $num_records; ?>
                            </div>
                            <div class="tnav clearfix">
                                <span><a href="javascript:void(0);" onclick="goToPage('<?php echo "Member_track_own_archives"; ?>','<?php echo $currentPageNo - 1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>
                                <span class="num"><?php echo $currentPageNo; ?></span>
                                <span><a href="javascript:void(0);" onclick="goToPage('<?php echo "Member_track_own_archives"; ?>','<?php echo $currentPageNo + 1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
                            </div>
                        <?php } ?>
                    </div><!-- eof fby-blk -->
                    <div class="item-heading">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4"></div>
                            <div class="col-lg-6 col-md-3 col-sm-5 col-xs-6">
                                <p>Track Info</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                <p>Label</p>
                            </div>
                            <div class="col-lg-2 col-md-1 col-sm-1 col-xs-6">
                                <p>Status</p>
                            </div>
                        </div>
                    </div>
                    <div class="mtop-list mCustomScrollbar">
                        <?php
                        if ($tracks['numRows'] > 0) {
                            foreach ($tracks['data'] as $track) { ?>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                            <p style="position:relative;">
                                                <?php
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
                                                    
                                                     if(!empty($track->pCloudFileID)){
                                                        $img= url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);
                                                      }                                
													 else if (!empty($track->imgpage) && file_exists(base_path("ImagesUp/".$track->imgpage))){
														$img = asset('ImagesUp/'.$track->imgpage);  
													 }else{
														$img = asset('public/images/noimage-avl.jpg');
													 }
                                                ?>
                                                    <a href="javascript:void(0);">
                                                        <img src="<?php echo $img; ?>" width="80" height="80" />
                                                        <img class="playButton" src="<?php echo asset('assets/img/play-btn.png'); ?>" width="80" height="80" onClick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','<?php echo $trackLink; ?>','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','<?php  echo $img; ?>','<?php echo $track->id; ?>')" />
                                                    </a>
                                                <?php
                                                } else { ?>
                                                    <a href="javascript:void(0);">
                                                        <img src="<?php echo asset('assets/img/play-btn.png'); ?>" onclick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png')" /></a>
                                                <?php } ?>
                                            </p>
                                        </div>
                                        <?php
                                        if ($downloads[$track->id] > 0) {
                                            $review_link =  url("Member_track_review_view?tid=" . $track->id);
                                            
                                            $href =  url("Member_track_download_front_end?tid=" . $track->id);
                                            $review_link=$href;
                                            $label = 'Reviewed';
                                        } else {
                                            $review_link =  url("Member_track_review?tid=" . $track->id);
                                            $href =  url("Member_track_review?tid=" . $track->id);
                                            $label = 'Not Reviewed';
                                        }
                                        ?>
                                        <div class="col-lg-6 col-md-3 col-sm-5 col-xs-6">
                                            <p><a style="color:#FFFFFF;" href="<?php echo $review_link; ?>"><?php echo urldecode($track->title); ?></a></p>
                                            <p>Artist: <a style="color:#FFFFFF;" href="<?php echo $review_link; ?>"><?php echo urldecode($track->artist); ?></a></p>
                                            <p>Album: <a style="color:#FFFFFF;" href="<?php echo $review_link; ?>"><?php echo urldecode($track->album); ?></a></p>
                                            <div class="social_media">
                                                <?php if ($social[$track->id]['numRows'] > 0) {
                                                    if (strlen($social[$track->id]['data'][0]->facebook) > 0) {
                                                ?><a class="twitter-follow-button" target="_blank" href="<?php echo $social[$track->id]['data'][0]->facebook;  ?>">
                                                            <img src="<?php echo asset('assets/images/facebook.png'); ?>" width="20" height="20" />
                                                        </a> <?php
                                                            }
                                                            if (strlen($social[$track->id]['data'][0]->twitter) > 0) {
                                                                ?><a class="twitter-follow-button" target="_blank" href="<?php echo $social[$track->id]['data'][0]->twitter;  ?>">
                                                            <img src="<?php echo asset('assets/images/twitter.png'); ?>" width="20" height="20" />
                                                        </a> <?php
                                                            }
                                                            if (strlen($social[$track->id]['data'][0]->instagram) > 0) {
                                                                ?><a class="twitter-follow-button" target="_blank" href="<?php echo $social[$track->id]['data'][0]->instagram;  ?>">
                                                            <img src="<?php echo asset('assets/images/instagram.png'); ?>" width="20" height="20" />
                                                        </a> <?php
                                                            }
                                                                ?>
                                                <?php }  ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 hidden-lg hidden-md hidden-sm"> <span style="padding-top:15px;"></span> </div>
                                        <div class="col-xs-4 hidden-lg hidden-md hidden-sm"></div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                            <p><a style="color:#FFFFFF;" href="Member_track_label?label=<?php echo $track->label; ?>"><?php echo urldecode($track->label); ?></a></p>
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
                                        <div class="col-xs-4 hidden-lg hidden-md hidden-sm"></div>
                                        <div class="col-lg-2 col-md-1 col-sm-1 col-xs-6">
                                            <div class="rew">
                                                <p style="color:#E5A4CE;"> <a href="<?php echo $href; ?>" style="color:#fff;"><?php echo $label; ?></a><br /></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 hidden-lg hidden-md hidden-sm"></div>
                                        <div class="col-lg-12 col-lg-offset-9 col-md-12 col-md-offset-9 col-sm-12 col-sm-offset-9 col-xs-6">
                                            <div class="rew">
                                                <p style="color:#E5A4CE;">
                                                    <?php if (!empty(Session::get('memberPackage') > 1)) { ?>
                                                        <a href="<?php echo url('Member_send_message?cid=' . $track->client); ?>">Send Message</a>
                                                    <?php } ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div><!-- eof row -->
                                </div><!-- eof item -->
                            <?php }
                        } else { ?>
                            <h2>No Data found.</h2>
                        <?php } ?>
                    </div><!-- eof mtop-list -->
                    <div class="fby-blk clearfix" style="display:none;">
                        <div style="clear:both;"></div>
                        <div class="nop">
                            <select class="rfn-sb npg selectpicker" onchange="changeNumRecords('<?php echo $currentPage; ?>','<?php echo $urlRecordString; ?>',this.value)">
                                <option <?php if ($numRecords == 10) {  ?> selected="selected" <?php } ?> value="10">10 PER PAGE</option>
                                <option <?php if ($numRecords == 20) {  ?> selected="selected" <?php } ?> value="20">20 PER PAGE</option>
                                <option <?php if ($numRecords == 30) {  ?> selected="selected" <?php } ?> value="30">30 PER PAGE</option>
                                <option <?php if ($numRecords == 40) {  ?> selected="selected" <?php } ?> value="40">40 PER PAGE</option>
                                <option <?php if ($numRecords == 50) {  ?> selected="selected" <?php } ?> value="50">50 PER PAGE</option>
                            </select>
                        </div><!-- eof nop -->
                        <?php if ($numPages > 1) { ?>
                            <div class="pgm">
                                <?php echo $start + 1; ?> - <?php echo $start + $numRecords; ?> OF <?php echo $num_records; ?>
                            </div>
                            <div class="tnav clearfix">
                                <span><a href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>
                                <span class="num"><?php echo $currentPageNo; ?></span>
                                <span><a href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
                            </div>
                        <?php } ?>
                    </div>
                </div><!-- eof mem-dblk -->
			  
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
     </div>
	 </section>
	 
@endsection