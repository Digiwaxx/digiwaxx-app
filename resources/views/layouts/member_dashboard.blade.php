<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tagsssss -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--favicon-->
   <link rel="icon" href="{{ asset('public/images/icon.png') }}" type="image/gif" sizes="16x16">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
    <script>
        window.googletag = window.googletag || {
            cmd: []
        };
        googletag.cmd.push(function() {
            googletag.defineSlot('/21741445840/digi003', [728, 90], 'div-gpt-ad-1565445763601-0').addService(googletag.pubads());
            googletag.pubads().enableSingleRequest();
            googletag.enableServices();
        });
    </script>
    <!-- /21741445840/digi003 
    <div id='div-gpt-ad-1565445763601-0' style='width: 728px; height: 90px;margin:auto'>
        <script>
            googletag.cmd.push(function() {
                googletag.display('div-gpt-ad-1565445763601-0');
            });
        </script>
    </div-->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('public/plugins/bootstrap/bootstrap.min.css') }}">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/custom-style.css') }}">
    <link href="{{ asset('public/css/font-awesome.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('public/css/responsive.css') }}">
    <link href="{{ asset('public/css/search.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-select.css') }}">
    <link href="{{ asset('public/jplayer/css/jplayer.pink.flag.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/jplayer/css/jplayer-main.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('public/scrollbar/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
    <!-- jquery validation -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
     <script src="{{ asset('public/js/jquery-ui.js') }}"></script>
    <!--world map-->
    <link rel="stylesheet" href="{{ asset('public/jvectormap/jquery-jvectormap-2.0.3.css') }}" type="text/css" media="screen" />
    <!--audio css-->
    <link rel="stylesheet" href="{{ asset('public/sap/mediaelementplayer.min.css') }}">
    
    
    <link rel="stylesheet" href="{{ asset('public/dropzone/dropzone.min.css') }}">

    <script src="{{ asset('public/dropzone/dropzone.min.js') }}"></script>
    
    
    <!--Start of Zendesk Chat Script-->
    <!--<script type="text/javascript">-->
    <!--    window.$zopim || (function(d, s) {-->
    <!--        var z = $zopim = function(c) {-->
    <!--                z._.push(c)-->
    <!--            },-->
    <!--            $ = z.s =-->
    <!--            d.createElement(s),-->
    <!--            e = d.getElementsByTagName(s)[0];-->
    <!--        z.set = function(o) {-->
    <!--            z.set.-->
    <!--            _.push(o)-->
    <!--        };-->
    <!--        z._ = [];-->
    <!--        z.set._ = [];-->
    <!--        $.async = !0;-->
    <!--        $.setAttribute("charset", "utf-8");-->
    <!--        $.src = "https://v2.zopim.com/?65Iyo2QqrvLLjKtuAj57VxlySVMCNAlA";-->
    <!--        z.t = +new Date;-->
    <!--        $.-->
    <!--        type = "text/javascript";-->
    <!--        e.parentNode.insertBefore($, e)-->
    <!--    })(document, "script");-->
    <!--</script>-->
    <!--End of Zendesk Chat Script-->
		<!-- Start of Async Drift Code -->
		<script>
		"use strict";

		!function() {
		  var t = window.driftt = window.drift = window.driftt || [];
		  if (!t.init) {
			if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
			t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
			t.factory = function(e) {
			  return function() {
				var n = Array.prototype.slice.call(arguments);
				return n.unshift(e), t.push(n), t;
			  };
			}, t.methods.forEach(function(e) {
			  t[e] = t.factory(e);
			}), t.load = function(t) {
			  var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
			  o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
			  var i = document.getElementsByTagName("script")[0];
			  i.parentNode.insertBefore(o, i);
			};
		  }
		}();
		drift.SNIPPET_VERSION = '0.3.1';
		drift.load('b4wfxmvvesbf');
		</script>
		<!-- End of Async Drift Code -->
</head>
<body>
    <?php
    $v = Session::get('memberPackage');
    // dd($v);
    ?>
    <div id="app" class="wrapper">		

    <div class="music-player header_sg1">
            <div class="container">
                <div id="jp_container_1" class="jp-video jp-video-270p" role="application" aria-label="media player">
                    <div class="jp-type-playlist">
                        <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                        <div class="jp-gui">
                            <div class="jp-video-play">
                                <!--style="display:none;"-->
                                <button class="jp-video-play-icon" role="button" tabindex="0">play</button>
                            </div>
                            <div class="jp-interface">
                                <div class="jp-controls-holder">

                                <?php  if(!empty($tracks['data'][0]->pCloudFileID)){
                                         $img_get= url('/pCloudImgDownload.php?fileID='.$tracks['data'][0]->pCloudFileID);
                                        }
                                       else if(!empty($tracks['data'][0]->imgpage)){

                                            if (file_exists(base_path('ImagesUp/'.$tracks['data'][0]->imgpage))){
                                        
                                                $img_get = asset('ImagesUp/'.$tracks['data'][0]->imgpage);  
                                            }
                                            else{
                                                $img_get = asset('public/images/noimage-avl.jpg');
                                            }

                                        }
                                        else{
                                            $img_get = asset('public/images/noimage-avl.jpg');
                                        }
                                
                                ?>


                                    <div class="jp-image" id="jp-image"><img id="jp-image-img" src="<?php echo $img_get; ?>"></div>
                                    <div class="jp-details">
                                        <div class="jp-title" aria-label="title" id="jp-title"><?php  if(!empty($tracks['data'][0])) echo urldecode($tracks['data'][0]->title); ?></div>
                                    </div>
                                    <div class="jp-controls">
                                        <button class="jp-previous" role="button" tabindex="0">previous</button>
                                        <button id="playaudio" class="jp-play" role="button" tabindex="0">play</button>
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
			<header>
			  <div class="container"> 
				<div class="row align-items-center">
					  <div class="col-md-3 col-sm-4 col-7">
					      <?php 
					       $get_logo='';
					      
					         $logo_details = DB::table('website_logo')->where('logo_id',1)->first();
					         if(!empty($logo_details) && !empty($logo_details->pCloudFileID)){
					           $get_logo = $logo_details->pCloudFileID;
					         }
					         
					        ?> 
					  <div class="logo">
				  <?php if(!empty($get_logo)){?>
					       <a href="https://digiwaxx.com"><img src="<?php echo url('/pCloudImgDownload.php?fileID='.$get_logo); ?>"></a>
				<?php	 }else{?>
					       <a href="https://digiwaxx.com"><img src="{{ asset('public/images/logo.png') }}"></a>
				<?php    }?>
						
					  </div>
					</div>
					<div class="col-md-9 col-sm-8 col-5">
					  <div class="header-right">
						<div class="add-banner">
						    <!--Banner Ad-->
                            <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
							<script>
								window.googletag = window.googletag || {
									cmd: []
								};
								googletag.cmd.push(function() {
									googletag.defineSlot('/21741445840/digi003', [728, 90], 'div-gpt-ad-1565445763601-0').addService(googletag.pubads());
									googletag.pubads().enableSingleRequest();
									googletag.enableServices();
								});
							</script>
							<!-- /21741445840/digi003 -->
							<div id='div-gpt-ad-1565445763601-0' style='width: 728px; height: 90px;margin:auto'>
								<script>
									googletag.cmd.push(function() {
										googletag.display('div-gpt-ad-1565445763601-0');
									});
								</script>
							</div>
						</div>
						<div class="header-nav">						 
							<ul>	
								@if(Session::get('memberPackage') > 1)
								<li class="msg-btn">
									<a href="<?php echo url("Member_messages"); ?>"><i class="fa fa-envelope fa-2x hide visible-xs-inline"></i><span class="hidden-xs d-none">MESSAGES</span>
                                    <?php if(isset($numMessages) && $numMessages>0) { ?> 
                                        <span class="bdg"><?php echo $numMessages; ?></span> 
                                    <?php } ?> 
                                    </a> 
								</li>
								@endif
								
								@if(Session::has('memberId'))
								<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
							
								<?php 
								
									$memberImage_get = Session::get('memberImage');
									$memberId = Session::get('memberId');
									 
        							if(is_numeric($memberImage_get)){
        							    $img= url('/pCloudImgDownload.php?fileID='.$memberImage_get);
        							}
								    else if (strlen($memberImage_get) > 4 && file_exists(base_path("member_images/" . $memberId . "/" . $memberImage_get))) {
										$img = asset("member_images/" . $memberId . "/" . $memberImage_get);
									} else {
										$img = asset('public/images/profile-pic.png');
									}
								?>
                                    <img src="<?php echo $img; ?>" class="avatar img-fluid  me-1"> 
                                            <span class="d-none d-sm-inline-block">{{ urldecode(Session::get('memberName')) }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{ url('Member_dashboard_newest_tracks') }}" class="dropdown-item">My Dashboard</a>
										<a href="{{ url('Member_edit_profile') }}" class="dropdown-item">Edit Profile</a>
										<!--<a href="{{ url('member_manage_subscription') }}" class="dropdown-item">My Subscription</a>-->
										<a href="{{ url('Member_change_password') }}" class="dropdown-item">Change Password</a>
                                        <a href="{{ url('Buy_digicoins') }}" class="dropdown-item">Buy Digicoins</a>
                                        <a href="{{ url('/Help') }}" class="dropdown-item">Help</a>
                                        <a href="{{ url('logout') }}" class="dropdown-item">Log Out</a>
                                    </div>
                                   </li>
                                @endif
                                {{-- Language Switcher --}}
                                <li class="nav-item" style="margin-left: 10px;">
                                    <x-language-switcher />
                                </li>
							<li class="menu-bar menu-btn"><a href="#"><img src="{{ asset('public/images/path/bar.png') }}" alt="bar"></a></li>
						</ul>

						</div>
					  </div>          
				  </div>
				</div>
			</div>
			   <script src="https://my.hellobar.com/1e3071cb5de48ba66d8585c1b6cd52c656dc170e.js" type="text/javascript" charset="utf-8" async="async"></script> 
			</header>
		  <!---content-section-->		   			
            @yield('content')
			@include('layouts.include.site-footer')
		</div><!-- eof wrapper -->

<div class="menu-con">
    <a href="#" class="menu-close"><i class="fa fa-close"></i></a>
    <ul>
        <li><a href="https://digiwaxx.com">HOME</a></li>
        <li><a href="{{ url('/') }}">DigiWaxx Record Pool</a></li>
        <li><a href="{{ url('/WhatWeDo') }}">What We Do</a></li>
        <li><a href="{{ url('/PromoteYourProject') }}">PROMOTE YOUR PROJECT</a></li>
        <!--li><a href="{{ route('all_news') }}">NEWS</a></li>
        <li><a href="{{ route('list_forum') }}">FORUMS</a></li>
         <li><a href="{{ route('all_videos') }}">VIDEOS</a></li-->
        <li><a href="{{ url('/Charts') }}">CHARTS</a></li>
        <li><a href="{{ url('/ClientsPage') }}">Clients</a></li>
        <!--li><a href="{{ url('/PressPage') }}">Press</a></li>
        <li><a href="{{ url('/WallOfScratch') }}">Wall Of Scratch</a></li-->
        <li><a href="https://digiwaxxradio.com">Digiwaxx Radio</a></li>
        <li><a href="{{ url('/Contactus') }}">CONTACT US</a></li>
    </ul>
</div>

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
							
							<input type="submit" name="silver" value="CHOOSE" disabled="disabled" style="background:#ebebeb !important" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="dso-pay bg1" style="margin-right:30px; height:520px;">
							<div class="price">
								<div class="pkg">Purple</div>
							</div><!-- eof price -->
							<h3>$5.99/Month</h3>
							<ul>
								<li><i class="fa fa-check"></i>Full Access to <strong>Digital Waxx Service</strong> (All Song versions + Archives)</li>
								<li><i class="fa fa-check"></i>Full Member DJ Account (In Box Access, Label communication, Additional Profile Information) </li>
								<li><i class="fa fa-check"></i>DJ Tools + Additional Content (Artists/Talent Drops, sound-bytes, effects, instrumentals, etc.) </li>
								<li><i class="fa fa-check"></i>Additional Digicoins </li>
							</ul>
						</div><!-- dso-item -->
						<div class="sub-btn">
							<form action="{{ url('Member_subscriptions') }}" method="post">
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

<!-- Modal -->
<div id="alertModal1" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:60%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="background:#b32f85;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upgrade Subscription</h4>
			</div>
			<div class="modal-body" style="background:#FFFFFF; padding:15px !important;">
				<div class="row dso-sec" style="margin:0px; width:auto;">
					
					<p style='color: #333;
font-family: "Roboto",sans-serif; font-size: 18px; font-style: italic; margin-bottom: 20px; margin-top: 20px;'>Messaging within the site is for premium members only. Join as a Purple member to get the following....
						<a href="{{ url('Member_subscriptions') }}" class="login_btn" style="display:inline;">Upgrade</a> </p>
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

<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script-->
<script src="{{ asset('public/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('public/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/image-fallback.js') }}"></script>
<script src="{{ asset('public/js/menu.js') }}"></script>
<script src="{{ asset('public/js/TMSearch.js') }}"></script>
<script src="{{ asset('public/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" defer></script>
<script src="{{ asset('public/jvectormap/jquery-jvectormap-world-mill-en.js') }}" defer></script>
<script src="{{ asset('public/jplayer/js/jquery.jplayer.min.js') }}" defer></script>
<script src="{{ asset('public/jplayer/js/jplayer.playlist.min.js') }}" defer></script>
<script type="text/javascript">
        /*function playTrack(id)
{
    if(id>0)
    {
    $.ajax({url: "Member_dashboard_all_tracks?trackId="+id+"&recordPlay=1", success: function(result){
  }});
  
  }
}*/
        function changeTrack(track_title, track_artist, track_mp, track_ogg, track_poster, trackId)
        {
            console.log(track_poster);
            if (trackId > 0)
            {
                $.ajax({
                    url: "Member_dashboard_all_tracks?trackId=" + trackId + "&recordPlay=1",
                    success: function(result) {
                    }
                });
            }
            // track_mp = '../sample/AUDIO/18395_track2.mp3';
            //  alert(track_mp);
            // alert(track_mp);
            document.getElementById('jp-image-img').src = track_poster;
            document.getElementById('jp-title').innerHTML = track_title;
            // document.getElementById('jp-play').setAttribute("onclick", "playTrack('"+trackId+"')");
            // console.log(track_mp);
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
            
           /* $('.mtop-list .item .img_div a').on('click', function(){
                $('.mtop-list .item').removeClass('active');
                $('.mtop-list .item').css("background-color", "black");
                $('.playingnow').hide();
                var srcc = "{{ asset('public/images/eqlzr.gif') }}";
                $(this).parent().parent().find('.track--title strong').after('<img src="'+srcc+'" class="playingnow" />');
                $(this).parents(".item").addClass('active');
                $(this).parents(".item").css("background-color", "#2b2b2b");
            });*/
			
			$('.playButton').on('click', function () {
				const trackElement = $(this).closest('.mtop-list .item');
				const playPauseBtn = trackElement.find('.play_pause_btn.playingnow ');
				$('.mtop-list .item .play_pause_btn.playingnow').not(playPauseBtn).hide();
				$('.tab-content .mtop-list .item').removeClass('track_active');
				$(this).parents('.item').addClass('track_active');
				playPauseBtn.show();
				const intervalId = setInterval(function () {
					if (!$('.header_sg1 .jp-video').hasClass('jp-state-playing')) {
						playPauseBtn.hide();
						$('.tab-content .mtop-list .item').removeClass('track_active');
						clearInterval(intervalId);
					}
				}, 1000);
			});
			
			$('.jp-controls #playaudio, .jp-controls .jp-next, .jp-controls .jp-previous').on('click', function() {
				setTimeout(function () {
					const currentTitle = $('.jp-title').text().trim();
					$('.mtop-list .item .play_pause_btn.playingnow').hide();
					if ($('.header_sg1 .jp-video').hasClass('jp-state-playing')) {
						$('.mtop-list .item .track--title a').each(function() {
							const trackTitle = $(this).text().trim();
							if (trackTitle === currentTitle) {
								$(this).closest('.mtop-list .item').find('.play_pause_btn.playingnow').show();
								$('.tab-content .mtop-list .item').removeClass('track_active');
								$(this).closest('.mtop-list .item').addClass('track_active');
							}
						});
					}
				}, 1000);
				const intervalId = setInterval(function () {
					if (!$('.header_sg1 .jp-video').hasClass('jp-state-playing')) {
						$('.mtop-list .item .play_pause_btn.playingnow').hide();
						$('.tab-content .mtop-list .item').removeClass('track_active');
						clearInterval(intervalId);
					}
				}, 1000);
			});
			
			
            // $tracks['data'][0]->location
            
            <?php $member_session_pkg = Session::get('memberPackage'); 
            $mem_ID=Session::get('memberId');
            $query=DB::table('package_user_details')->where('user_id',$mem_ID)->where('package_active',1)->get();
            if(count($query)>0){
                
            $subs_id=$query[0]->package_id;
            
                if ($subs_id == 7) {  ?>
                $('#jquery_jplayer_1').jPlayer({
                    timeupdate: function(event) { // 4Hz
                        // alert(event.jPlayer.status);
                        // Restrict playback to first 60 seconds.
                        if (event.jPlayer.status.currentTime > 31) {
                            $(this).jPlayer('stop');
                        }
                    }
                    // Then other options, such as: ready, swfPath, supplied and so on.
                });
            <?php }
         }            
            // else { echo 'timeupdate'; } 
            ?>
            new jPlayerPlaylist({
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            }, [
                <?php
               

                if(!empty($tracks)){

                    $count = $tracks['numRows'];

                for ($i = 0; $i < $count; $i++) {
                    $getlink = '';
                    if(isset($tracks['data'][$i]->location) && !empty($tracks['data'][$i]->location)){

                      if (strpos($tracks['data'][$i]->location, '.mp3') !== false) {
                        $getlink = asset('AUDIO/' . $tracks['data'][$i]->location);
                    } else {
                        $fileid = (int) $tracks['data'][$i]->location;
                        if (!empty($fileid)) {
                            $getlink = url('download.php?fileID=' . $fileid);
                        }
                    }


                    }
                  
                ?>
                    {
                        title: "<?php echo addslashes(urldecode($tracks['data'][$i]->title)); ?>",
                        artist: "<?php echo addslashes(urldecode($tracks['data'][$i]->artist)); ?>",
                        mp3: "<?php echo $getlink; //url("AUDIO/".$tracks['data'][$i]->location); 
                                ?>",
                        poster: "<?php echo asset("ImagesUp/" . $tracks['data'][$i]->imgpage); ?>"
                    }
                <?php if ($i != $count - 1) {
                        echo ',';
                    }
                }
                
            }?>
            ], {
                swfPath: "../../dist/jplayer",
                supplied: "webmv, ogv, m4v, oga, mp3",
                useStateClassSkin: true,
                autoBlur: false,
                smoothPlayBar: true,
                keyEnabled: true,
                audioFullScreen: true,
                errorAlerts: true,
                warningAlerts: true
            });
            //	 $("#jquery_jplayer_1").jPlayer("pause", 15);
            //	$("#jquery_jplayer_1").jPlayer("mute", true);
            $('.jp-play').click(function() {
                // if ($('.mejs__playpause-button').hasClass('mejs__pause')){
                //     $('.mejs__playpause-button').trigger('click');
                // }
                jQuery("audio").not(this).each(function (index, audio) {
                    audio.pause();
                });
                    //console.log("ffccdhgsvjhbklmk-----------------------------------------");
                if($('#jquery_jplayer_1').data().jPlayer.status.paused) {
                    $("#jquery_jplayer_1").jPlayer("play");
                } else {
                    $("#jquery_jplayer_1").jPlayer("pause");
                } 
            });
            
            
           
            
            
            
        });
        
        
        
        
        
        
        // setTimeout(function() {
        //     start_audio();
        // }, 3000);
        //setTimeout(function(){ disable_audio(); }, 10000);
    </script>
    <script>
        function start_audio() {
            $("#jquery_jplayer_1").jPlayer("play", 0);
            
            //  $("#playaudio").attr("class","jp-pause");
            //  $("#jquery_jplayer_1").jPlayer("play",0);
            		  //$("#jquery_jplayer_1").jPlayer("pause", 35);
        }
        /*function disable_audio()
        {
           $("#jquery_jplayer_1").jPlayer("pause", 35);
        }*/
        function goToPage(page, pid, urlString)
        {
			if(pid > 0){
				var param = '?';
				if (urlString.length > 3)
				{
					param = '&';
				}
				window.location = page + urlString + param + "page=" + pid;
			}
        }
        function sortBy(page, type, id, urlString)
        {
            var param = '?';
            if (urlString.length > 3)
            {
                param = '&';
            }
            var records = 10;
            window.location = page + urlString + param + "sortBy=" + type + "&sortOrder=" + id + "&records=" + records;
        }
        function changeNumRecords(page, urlString, records)
        {
            var param = '?';
            if (urlString.length > 3)
            {
                param = '&';
            }
            window.location = page + urlString + param + "records=" + records;
        }
    </script>
    <script src="<?php echo url('assets/ratings/jquery.barrating.js'); ?>"></script>
    <script src="<?php echo url('assets/ratings/examples.js'); ?>"></script>
    <script src="<?php echo url('assets/sap/mediaelement-and-player.min.js'); ?>"></script>
    <script src="<?php echo url('assets/sap/demo.js'); ?>"></script>
    </body>
    </html>
    <!--browser notifications-->
    <script type="text/javascript" defer="defer">
        /*var articles = [
["1 Vanilla JS Browser Default Java Script.","http://www.9lessons.info/2015/11/customize-youtube-embed-player.html"],
["2 Facebook Style Background Image Upload and Position Adjustment.","http://www.9lessons.info/2015/02/facebook-style-background-image-upload.html"],
["3 Create a RESTful services using Slim PHP Framework","http://www.9lessons.info/2014/12/create-restful-services-using-slim-php.html"],
["4 Social Network Script","http://www.thewallscript.com"],
["5 9lesosns Demos","http://demos.9lessons.info"],
["6 Pagination with Jquery, PHP , Ajax and MySQL.","http://www.9lessons.info/2010/10/pagination-with-jquery-php-ajax-and.html"],
["7 Ajax Select and Upload Multiple Images with Jquery","http://www.9lessons.info/2013/09/multiple-ajax-image-upload-jquery.html"],
["8 Ajax PHP Login Page with Shake Animation Effect.","http://www.9lessons.info/2014/07/ajax-php-login-page.html"],
["9 Vanilla JS Browser Default Java Script.","http://www.9lessons.info/2015/11/customize-youtube-embed-player.html"],
["10 Vanilla JS Browser Default Java Script.","http://www.9lessons.info/2015/11/customize-youtube-embed-player.html"]
];*/
        //
        setTimeout(function() {
            sendNofitication();
        }, 3000);
        /*setInterval(function() {
          sendNofitication();
        }, 3000);
        */
        function sendNofitication() {
            $.ajax({
                url: "Notifications?tid=1",
                success: function(result) {
                    var obj = JSON.parse(result);
                    var len = obj.length;
                    for (var i = 0; i < len; i++) {
                        var title = obj[i].title;
                        var desc = obj[i].artist;
                        var url = 'Member_track_review?tid=' + obj[i].id;
                        var thumb = obj[i].thumb;
                        // alert("Do you want browser notifications");
                        notifyBrowser(title, desc, url, thumb);
                    }
                }
            });
        }
        //
        /*
        setTimeout(function(){ 
        var x = Math.floor((Math.random() * 10) + 1);
        var title=articles[x][0];
        var desc='Most popular article .';
        var url=articles[x][1];
        notifyBrowser(title,desc,url);
        }, 200);*/
        /*
        document.addEventListener('DOMContentLoaded', function () 
        {
          
        if (Notification.permission !== "granted")
        {
        Notification.requestPermission();
        }
        document.querySelector("#notificationButton").addEventListener("click", function(e)
        {
        var x = Math.floor((Math.random() * 10) + 1);
        var title=articles[x][0];
        var desc='Most popular article.';
        var url=articles[x][1];
        notifyBrowser(title,desc,url);
        e.preventDefault();
        });
        });
        */
        function notifyBrowser(title, desc, url, thumb) {
            if (!Notification) {
                console.log('Desktop notifications not available in your browser..');
                return;
            }
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            } else {
                var notification = new Notification(title, {
                    icon: thumb,
                    body: desc,
                });
                // Remove the notification from Notification Center when clicked.
                notification.onclick = function() {
                    window.open(url);
                };
                // Callback function when the notification is closed.
                notification.onclose = function() {
                    console.log('Notification closed');
                };
            }
        }
    </script>
<script>
    
           
</script>

</body>
</html>