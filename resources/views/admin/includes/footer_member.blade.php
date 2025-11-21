    <!--<div class="music-player"> Music Player          </div>-->
    <div class="music-player">
        <div class="container">
            <div id="jp_container_1" class="jp-video jp-video-270p" role="application" aria-label="media player">
                <div class="jp-type-playlist">
                    <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                    <div class="jp-gui">
                        <div class="jp-video-play" style="display:none;">
                            <button class="jp-video-play-icon" role="button" tabindex="0">play</button>
                        </div>
                        <div class="jp-interface">
                            <div class="jp-controls-holder">
                                <div class="jp-image" id="jp-image"><img id="jp-image-img" src="<?php echo asset("ImagesUp/" . $tracks_footer['data'][0]->imgpage); ?>"></div>
                                <div class="jp-details">
                                    <div class="jp-title" aria-label="title" id="jp-title"><?php echo urldecode($tracks_footer['data'][0]->title); ?></div>
                                </div>
                                <div class="jp-controls">
                                    <button class="jp-previous" role="button" tabindex="0">previous</button>
                                    <button class="jp-play" role="button" tabindex="0">play</button>
                                    <button class="jp-stop" role="button" tabindex="0">stop</button>
                                    <button class="jp-next" role="button" tabindex="0">next</button>
                                </div>
                                <div class="jp-timer jp1">
                                    <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                                    <div class="jp-progress">
                                        <div class="jp-seek-bar">
                                            <div class="jp-play-bar"></div>
                                        </div>
                                    </div>
                                    <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                                </div>
                                <div class="jp-toggles jp1">
                                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                                    <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                                    <button class="jp-full-screen" role="button" tabindex="0">full screen</button>
                                </div>
                                <div class="jp-volume-controls jp1">
                                    <button class="jp-mute" role="button" tabindex="0">mute</button>
                                    <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div><!-- eof jp-controls-holder -->
                            <div class="jp-btm clearfix">
                                <div class="jp-timer clearfix">
                                    <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                                    <div class="jp-progress">
                                        <div class="jp-seek-bar">
                                            <div class="jp-play-bar"></div>
                                        </div>
                                    </div>
                                    <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                                </div>
                                <div class="jp-toggles clearfix">
                                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                                    <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                                    <button class="jp-full-screen" role="button" tabindex="0">full screen</button>
                                </div>
                                <div class="jp-volume-controls clearfix">
                                    <button class="jp-mute" role="button" tabindex="0">mute</button>
                                    <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                </div>
                            </div><!-- eof jp-btm -->
                        </div>
                    </div>
                    <div class="jp-playlist">
                        <ul>
                            <!-- The method Playlist.displayPlaylist() uses this unordered list -->
                            <li></li>
                        </ul>
                    </div>
                    <div class="jp-no-solution">
                        <span>Update Required</span>
                        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                    </div>
                </div>
            </div><!-- eof jplayer -->
        </div><!-- eof container -->
    </div><!-- eof MUSIC PLAYER -->
    </div><!-- eof wrapper -->
    <div class="menu-con">
        <a href="#" class="menu-close"><i class="fa fa-close"></i></a>
        <ul>
            <li><a href="#">HOME</a></li>
            <li><a href="<?php echo url('PromoteYourProject'); ?>">PROMOTE YOUR PROJECT</a></li>
            <li><a href="<?php echo url('Charts'); ?>">CHARTS</a></li>
            <li><a href="<?php echo url('DigiwaxxRadio'); ?>">DIGIWAXX RADIO</a></li>
            <li><a href="<?php echo url('Contactus'); ?>">CONTACT US</a></li>
        </ul>
    </div>
    <!--popup-->
    <!-- Modal -->
    <div id="alertModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:60%;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background:#b32f85;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upgrade Subscription</h4>
                </div>
                <div class="modal-body" style="background:#FFFFFF; padding:15px !important;">
                    <div class="row dso-sec" style="margin:0px; width:auto;">
                        <?php // if($_SESSION['memberPackage']==2) { $display = 'none'; } else { $display = 'block'; }  
                        ?>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="dso-pay bg" style="margin-left:30px;">
                                <div class="price">
                                    <div class="pkg">Silver</div>
                                </div><!-- eof price -->
                                <h3>Free</h3>
                                <ul>
                                    <li><i class="fa fa-check"></i>Basic Access to <strong>Digital Waxx Service</strong> and music content (limited songs and versions)</li>
                                    <li><i class="fa fa-check"></i>Preview song version made available for download</li>
                                    <li><i class="fa fa-check"></i>Access to download preview song for the first 24-hours after release (After 24-hours member has to have silver or gold membership to access preview song)</li>
                                    <li><i class="fa fa-check"></i>Access to new song previews (30 sec) </li>
                                    <li><i class="fa fa-check"></i>Basic Member Account and Profile (includes basic DJ Info) </li>
                                </ul>
                            </div><!-- dso-item -->
                            <div class="sub-btn">
                                <?php
                                // if($packageId>1)
                                // {
                                ?>
                                <input type="submit" name="silver" value="CHOOSE" disabled="disabled" style="background:#ebebeb !important" /> <?php
                                                                                                                                                // 	} else {
                                                                                                                                                ?>
                                <!--
                            	<form action="" method="post">
                            	 <input type="submit" name="silver" value="CHOOSE" />
							   </form>-->
                                <?php // } 
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="dso-pay bg1" style="margin-right:30px; height:520px;">
                                <div class="price">
                                    <div class="pkg">Purple</div>
                                </div><!-- eof price -->
                                <h3>$19.99/mo<br>$119.99/per 6 months<br>$189.99/per 12 months</h3>
                                <ul>
                                    <li><i class="fa fa-check"></i>Full Access to <strong>Digital Waxx Service</strong> (All Song versions + Archives)</li>
                                    <li><i class="fa fa-check"></i>Full Member DJ Account (In Box Access, Label communication, Additional Profile Information) </li>
                                    <li><i class="fa fa-check"></i>DJ Tools + Additional Content (Artists/Talent Drops, sound-bytes, effects, instrumentals, etc.) </li>
                                    <li><i class="fa fa-check"></i>Additional Digicoins </li>
                                </ul>
                            </div><!-- dso-item -->
                            <div class="sub-btn">
                                <form action="<?php echo url("Member_subscriptions"); ?>" method="post">
                                    <input type="submit" name="purple" value="CHOOSE" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--row close-->
                    <div style="clear:both;"></div>
                </div>
                <div class="modal-footer" style="background:#b32f85;">
                </div>
            </div>
        </div>
    </div>
    <!--popup close-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/menu.js"></script>
    <script src="<?php echo asset('assets/js/TMSearch.js'); ?>"></script>
    <script src="assets/js/bootstrap-select.js"></script>
    <script src="assets/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="<?php echo asset('assets/jplayer/js/jquery.jplayer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo asset('assets/jplayer/js/jplayer.playlist.min.js'); ?>"></script>
    <script type="text/javascript">
        //<![CDATA[
        /*function playTrack(id)
  {
     
	  if(id>0)
	  {
	  $.ajax({url: "Member_dashboard_all_tracks?trackId="+id+"&recordPlay=1", success: function(result){
    }});
	
	}
  }*/
        function changeTrack(track_title, track_artist, track_mp, track_ogg, track_poster, trackId) {
            // console.log(s);
            if (trackId > 0) {
                $.ajax({
                    url: "Member_dashboard_all_tracks?trackId=" + trackId + "&recordPlay=1",
                    success: function(result) {}
                });
            }
            // track_mp = '../sample/AUDIO/18395_track2.mp3';
            //  alert(track_mp);
            // alert(track_mp);
            document.getElementById('jp-image-img').src = track_poster;
            document.getElementById('jp-title').innerHTML = track_title;
            // document.getElementById('jp-play').setAttribute("onclick", "playTrack('"+trackId+"')");
            $("#jquery_jplayer_1").jPlayer("setMedia", {
                mp3: track_mp
            }).jPlayer("play");
            // setTimeout(function(){  $("#jquery_jplayer").hide(); $("#jquery_jplayer_1").jPlayer("stop"); }, 3000);
            // document.getElementById('jp_audio_0').src = track_mp;
            /*new jPlayerPlaylist({
	
	//alert("hii");
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
	}, [
		{
			title:track_title,
			artist:track_artist,
			mp3:track_mp,
			oga:track_ogg,
			poster:track_poster
		}
	], {
		swfPath: "../../dist/jplayer",
		supplied: "webmv, ogv, m4v, oga, mp3",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
		audioFullScreen: true
	});
*/
        }
        $(document).ready(function() {
            // $tracks['data'][0]->location
            <?php

              if(!empty($_COOKIE['memberPackage'])){

                $get_member_pkg = $_COOKIE['memberPackage'];
            }
            else{
    
                $get_member_pkg = '';
    
            }
            
            if ($get_member_pkg < 2) {  ?>
                $('#jquery_jplayer_1').jPlayer({
                    timeupdate: function(event) { // 4Hz
                        // Restrict playback to first 60 seconds.
                        if (event.jPlayer.status.currentTime > 31) {
                            //$(this).jPlayer('stop');
                        }
                    }
                    // Then other options, such as: ready, swfPath, supplied and so on.
                });
            <?php } // else { echo 'timeupdate'; } 
            ?>
            new jPlayerPlaylist({
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            }, [
                <?php
                $count = $tracks_footer['numRows'];
                for ($i = 0; $i < $count; $i++) {
                    $getlink = '';
                    if (strpos($tracks_footer['data'][$i]->location, '.mp3') !== false) {
                        $getlink = asset('AUDIO/' . $tracks_footer['data'][$i]->location);
                    } else {
                        $fileid = (int) $tracks_footer['data'][$i]->location;
                        if (!empty($fileid)) {
                            $getlink = url('download.php?fileID=' . $fileid);
                        }
                    }
                ?> {
                        title: '<?php echo urldecode($tracks_footer['data'][$i]->title); ?>',
                        artist: "<?php echo urldecode($tracks_footer['data'][$i]->artist); ?>",
                        mp3: "<?php echo $getlink; //base_url("AUDIO/".$tracks['data'][$i]->location); 
                                ?>",
                        poster: "<?php echo asset("ImagesUp/" . $tracks_footer['data'][$i]->imgpage); ?>"
                    }
                <?php if ($i != $count - 1) {
                        echo ',';
                    }
                } ?>
            ], {
                swfPath: "../../dist/jplayer",
                supplied: "webmv, ogv, m4v, oga, mp3",
                useStateClassSkin: true,
                autoBlur: false,
                smoothPlayBar: true,
                keyEnabled: true,
                audioFullScreen: true
            });
            //	 $("#jquery_jplayer_1").jPlayer("pause", 15);
            //	$("#jquery_jplayer_1").jPlayer("mute", true);
            $('.jp-play').click(function() {
                if($('#jquery_jplayer_1').data().jPlayer.status.paused) {
                    $("#jquery_jplayer_1").jPlayer("play");
                } else {
                    $("#jquery_jplayer_1").jPlayer("pause");
                } 
            });
        });
        setTimeout(function() {
            start_audio();
        }, 2000);
        //setTimeout(function(){ disable_audio(); }, 10000);
        //]]>
    </script>
    <script>
        function start_audio() {
            $("#jquery_jplayer_1").jPlayer("play", 0);
            // 		  $("#jquery_jplayer_1").jPlayer("pause", 35);
        }
        /*function disable_audio()
        {
           $("#jquery_jplayer_1").jPlayer("pause", 35);
        }*/
        function goToPage(page, pid, urlString) {
            var param = '?';
            if (urlString.length > 3) {
                param = '&';
            }
            window.location = page + urlString + param + "page=" + pid;
        }
        function sortBy(page, type, id, urlString) {
            var param = '?';
            if (urlString.length > 3) {
                param = '&';
            }
            var records = 10;
            window.location = page + urlString + param + "sortBy=" + type + "&sortOrder=" + id + "&records=" + records;
        }
        function changeNumRecords(page, urlString, records) {
            var param = '?';
            if (urlString.length > 3) {
                param = '&';
            }
            window.location = page + urlString + param + "records=" + records;
        }
    </script>
    <script src="<?php echo asset('assets/ratings/jquery.barrating.js'); ?>"></script>
    <script src="<?php echo asset('assets/ratings/examples.js'); ?>"></script>
    <script src="<?php echo asset('assets/sap/mediaelement-and-player.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/sap/demo.js'); ?>"></script>
    </body>
    </html>