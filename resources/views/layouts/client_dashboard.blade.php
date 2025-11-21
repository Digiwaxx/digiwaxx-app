<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

<style>
        body{
            font-family: 'Open Sans' !important;
        }
		#alertModal_campaign .modal-dialog {
			max-width: 100%!important;
            width: 100%;
            margin-top: 0;
            margin-bottom: 0;
		}
		/* @media (min-width: 576px)
			.modal-dialog {
				max-width: 100%!important;
				margin: 1.75rem auto;
			} */
        .processing_loader_gif {
            display: none;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            bottom:0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,.3);
            z-index: 9999;
            text-align: center;
        }
        
        .processing_loader_gif img {
            max-width: 80px;
            margin: auto;
            top: 45%;
            position: fixed;
        }			
	</style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tagsssss -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('public/images/icon.png') }}" type="image/gif" sizes="16x16">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/bootstrap.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/font-awesome.css')}}" />
    <!-- page specific plugin styles -->
    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace-fonts.css')}}" />
    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
	<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/custom_admin.css')}}" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
    <script src="{{ asset('assets_admin/assets/js/ace-extra.js')}}"></script>

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
    <!--link rel="stylesheet" href="{{ asset('assets_admin/assets/css/bootstrap.css')}}" /-->
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
    <!-- jquery validation -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script> -->
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
                <!-- Scripts -->
                <script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/js/jquery-ui.js') }}"></script>
</head>
<body>
    

    <div id="app" class="wrapper client">	
    
    
       <?php
           if (!empty($tracks) && $tracks['numRows'] > 0) {
         ?>
         
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

                                <?php
                                        if(!empty($tracks['data'][0]->pCloudFileID)){
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
         
         
         <?php }?>
    
    
    
    
    
		<!-- <div class="header">
            <div class="header-top">
                <div class="container-fluid">
                    <div class="container">
                        <div class="row">
                            <div class="logo pull-left col-sm-2">
                                <a href="{{ url('/') }}"><img src="{{ asset('public/images/logo.png') }}"></a>
                            </div>
                            <div class="col-sm-8 sg_left"> -->
                                                                <!--Banner Ad-->
                            <!-- </div>
                            <div class="topbar pull-right col-sm-2">
                                <ul>
								
								@if(Session::has('clientId'))
								<li class="pro-btn"><a href="#" class="pr-link">
									<img src="{{ asset('public/images/profile-pic.png') }}">
                                            <span class="hidden-sm hidden-xs">TestUser</span>
                                    </a>
									<div class="profile-menu">
                                            <ul>
                                                <li>
                                                   @if(Session::get('clientPackage') > 1)
                                                        <a href="{{ url('Client_dashboard') }}">MY DASHBOARD</a>
                                                    @else
                                                        <a href="javascript:void()" data-toggle="modal" data-target="#alertModal">MY DASHBOARD</a>
                                                    @endif
                                                </li>
                                                <li><a href="{{ url('Client_edit_profile') }}">EDIT MY PROFILE</a></li>
                                                <li><a href="{{ url('Client_change_password') }}">CHANGE PASSWORD</a></li>
                                                <li><a href="{{ url('Buy_digicoins') }}">BUY DIGICOINS</a></li>
                                                <li><a href="{{ url('Help') }}">HELP</a></li>
                                                <li><a href="{{ url('logout') }}">LOG OUT</a></li>
                                            </ul>
                                        </div>
								</li>
								@endif
								<li class="menu-btn"><a href="#"><i class="fa fa-bars fa-2x"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --><!-- eof header-top -->
            <!-- <script src="https://my.hellobar.com/1e3071cb5de48ba66d8585c1b6cd52c656dc170e.js" type="text/javascript" charset="utf-8" async="async"></script> -->
		<!-- </div> --><!-- eof header -->  

             <header>
                 <?php
                //  if($_SERVER['REMOTE_ADDR'] = ' 223.178.208.79'){
                //     echo'<pre>';print_r($tracks);die();
                //   }
                 
                 
                 
                 ?>
                 
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
					       <a href="{{ url('/') }}"><img src="<?php echo url('/pCloudImgDownload.php?fileID='.$get_logo); ?>"></a>
				<?php	 }else{?>
					       <a href="{{ url('/') }}"><img src="{{ asset('public/images/logo.png') }}"></a>
				<?php    }?>
						
					 </div>
                        </div>
                        <div class="col-md-9 col-sm-8 col-5">
                          <div class="header-right">
                            <div class="add-banner">
                              <!--a href="#"><img src="{{ asset('public/images/path/add.png') }}" class="img-fluid"></a-->
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
                                @if(Session::has('clientId'))
                                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                       <?php
										  $sessClientID = Session::get('clientId');
										  
										    $query = DB::select("SELECT * FROM  client_images where clientId = '$sessClientID' order by imageId desc limit 1");

											$clientAvatar['numRows'] = count($query);
											$clientAvatar['data']  = $query;
										
										  if(!empty($clientAvatar['data'][0]->pCloudFileID_client_image) && is_numeric($clientAvatar['data'][0]->pCloudFileID_client_image)){
										      $imgSrc= url('/pCloudImgDownload.php?fileID='.$clientAvatar['data'][0]->pCloudFileID_client_image);
										  }
                                          else if($clientAvatar['numRows']>0 && isset($clientAvatar['data'][0]->image) && file_exists(base_path('client_images/'.$sessClientID.'/'.$clientAvatar['data'][0]->image))){
                                             $imgSrc = asset('client_images/'.$sessClientID.'/'.$clientAvatar['data'][0]->image);                                          
                                          }else{                                          
                                             $imgSrc = asset('public/images/profile-pic.png');                                          
                                          }                                          
                                          ?>
                                    <img src="<?php echo $imgSrc; ?>" class="avatar img-fluid  me-1"> 
                                            <span class="d-none d-sm-inline-block">{{ urldecode(Session::get('clientName')) }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">

                                                   @if(Session::get('clientPackage') > 1)
                                                        <a href="{{ url('Client_dashboard') }}" class="dropdown-item">MY DASHBOARD</a>
                                                    @else
                                                        <a href="javascript:void()" data-toggle="modal" data-target="#alertModal" class="dropdown-item">MY DASHBOARD</a>
                                                    @endif
                                                
                                                <a href="{{ route('ClientEditProfile') }}" class="dropdown-item">EDIT MY PROFILE</a>
                                                <!--<a href="{{ route('client_my_package') }}" class="dropdown-item">MY PACKAGE</a>-->
                                                <a href="{{ route('ClientChangePasssword') }}" class="dropdown-item">CHANGE PASSWORD</a>
                                                <a href="{{ route('Buy_digicoins') }}" class="dropdown-item">BUY DIGICOINS</a>
                                                <a href="{{ url('Help') }}" class="dropdown-item">HELP</a>
                                                <a href="{{ url('logout') }}" class="dropdown-item">LOG OUT</a>
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



				@yield('content')
				
				@include('layouts.include.site-footer')
</div><!-- eof wrapper -->

<div class="menu-con">
    <a href="#" class="menu-close"><i class="fa fa-close"></i></a>
    <ul>
        <li><a href="https://digiwaxx.com">HOME</a></li>        
        <li><a href="{{ url('/WhatWeDo') }}">What We Do</a></li>
        <li><a href="{{ url('/PromoteYourProject') }}">PROMOTE YOUR PROJECT</a></li>
        <li><a href="{{ url('/Charts') }}">CHARTS</a></li>
        <li><a href="https://digiwaxxradio.com">Digiwaxx Radio</a></li>
        <li><a href="{{ url('/Contactus') }}">CONTACT US</a></li>
    </ul>
</div>

<!-- Modal -->
<div id="alertModal_campaign" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header justify-content-end">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->

                <button type="button" class="btn btn-alt" onclick="close_campaign_modal();">&times;</button>
                <!--<h4 class="modal-title">Upgrade Subscription to access more features</h4>-->
            </div>
            <div class="modal-body px-md-5 px-3 dso-sec mt-0">
                <div class="row">

                    <div class="mt-0">
                        <h2>What type of services do you want?</h2>

                        <div class="row" id="client-onetime">
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-2">
                                <div class="dso-item">
                                    <h3>BASIC</h3>
                                    <!-- <h5>Packages</h5> -->
                                    <div class="price clearfix">
                                        <!--                            <div class="dlr">$</div>-->
                                        <div class="amt">$350</div>
                                        <!--                            <div class="txt pull-left">1-time fee /per track</div>-->
                                    </div><!-- eof price -->
                                    <ul>
                                        <li>Basic Music Submission</li>
                                        <li>Does not include <b>Feedback Reports Option</b></li>
                                        <li>Does not include <b>E-mail Blast</b></li>
                                        <li>Release Alert posted on Social Media Accounts <b>(Twitter, Facebook, Instagram)</b></li>
                                    </ul>
                                     <div class="sub-btn">
                                        @if(session()->get('clientPackage') >= 1 && session()->get('clientPackage') <= 4 )                                  
                                            <input type="submit" class="btn btn-gradient btn-theme" name="basic" value="BUY" disabled="disabled"
                                                   style="background:#ebebeb !important;color: #7b7b7b;"/> 
                                        @else
                                            <form action="{{ url('Client_payment1') }}" method="post">
                                            @csrf
                                                <input type="submit" class="btn btn-gradient btn-theme" name="basic" value="BUY"/>
                                            </form>
                                        @endif
                                    </div>
                                </div><!-- dso-item -->

                               
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-2">
                                <div class="dso-item">
                                    <h3>REGULAR</h3>
                                    <!-- <h5>Packages</h5> -->
                                    <div class="price clearfix">
                                        <div class="amt">$500</div>
                                    </div><!-- eof price -->

                                    <ul>
                                        <li>Digiwaxx digital record pool submission for infinite access and downloads to our members.</li>
                                        <li>1 Email Campaign (subscriber list of <b>50,000+ members</b>)</li>
                                        <li>Does not include <b>Feedback Reports Option</b></li>
                                        <li><b>1 Additional email blasts and social media posts</b> exactly that will occur once every week from the initial email blast.</li>
                                    </ul>
                                     <div class="sub-btn">
                                        @if(session()->get('clientPackage') >= 2 && session()->get('clientPackage') <= 4)                                   
                                        <input type="submit" class="btn btn-gradient btn-theme" name="regular" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                        @else                                   
                                        <form action="{{ url('Client_payment1') }}" method="post">
                                        @csrf
                                            <input type="submit" class="btn btn-gradient btn-theme" name="regular" value="BUY"/>
                                        </form>
                                        @endif
                                    </div>
                                </div><!-- dso-item -->

                               
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-2">
                                <div class="dso-item">
                                    <h3>STANDARD</h3>
                                    <!-- <h5>Packages</h5> -->
                                    <div class="price clearfix">
                                        <div class="amt">$750</div>
                                    </div><!-- eof price -->

                                    <ul>
                                        <li>Digiwaxx digital record pool submission for infinite access and downloads to our members.</li>
                                        <li>Digitally distribute via e-mail (2 blasts)</li>
                                        <li>Include client access to <b>DJ Feedback</b> and <b>Member Contact info</b> and <b>track analytics (i.e. DJ Profile of spin location, format and geographic location of mixer or programmer, ratings and comments)</b></li>
                                        <li>Release Alert posted on Social Media Accounts <b>(Twitter, Facebook, Instagram)</b></li>
                                    </ul>
                                    <div class="sub-btn">
                                        @if(session()->get('clientPackage') >= 3 && session()->get('clientPackage') <= 4)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="standard" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                        @else
                                        <form action="{{ url('Client_payment1') }}" method="post">
                                        @csrf
                                            <input type="submit" class="btn btn-gradient btn-theme" name="standard" value="BUY"/>
                                        </form>
                                        @endif
                                    </div>
                                </div><!-- dso-item -->
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6 pb-2">
                                <div class="dso-item">
                                    <h3>ADVANCE</h3>
                                    <!-- <h5>Packages</h5> -->
                                    <div class="price clearfix">
                                        <div class="amt">$1000</div>
                                    </div><!-- eof price -->

                                    <ul>
                                        <li>Digiwaxx digital record pool submission for infinite access and downloads to our members.</li>
                                        <li>Digitally distribute via e-mail (3 blasts)</li>
                                        <li>Include client access to <b>DJ Feedback</b> and <b>Member Contact info</b> and <b>track analytics</b></li>
                                        <li>Release Alert posted on Social Media Accounts <b>(Twitter, Facebook, Instagram)</b></li>
                                    </ul>
                                    <div class="sub-btn">
                                        @if(session()->get('clientPackage') >= 4 && session()->get('clientPackage') <= 4)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="advance" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;" />
                                        @else
                                        <form action="{{ url('Client_payment1') }}" method="post">
                                        @csrf
                                            <input type="submit" class="btn btn-gradient btn-theme" name="advance" value="BUY"/>
                                        </form>
                                        @endif
                                    </div>
                                </div><!-- dso-item -->
                            </div>
                        </div>
                    </div>

                </div> <!--row close-->
                <div class="dso-sec center-block">
                    <h2>Artist Membership</h2>
                    <div class="row" id="artist-subscription">
                        <div class="col-lg-4 col-md-4 col-sm-4 pb-2">
                            <div class="dso-item silver-plan">
                                <h3>SILVER</h3>
                                <!-- <h5>Packages</h5> -->
                                <div class="price clearfix">
                                    <h4><i>$249/Month</i></h4>
                                </div><!-- eof price -->
                                <ul>
                                    <li>1 project promotion (Added to Digiwaxx Pool with DJ feedback)</li>
                                    <li>2 email blasts</li>
                                    <li>2 posts on Social (Twitter, IG & Facebook)</li>
                                </ul>
                                 <div class="sub-btn">
                                    @if(session()->get('clientPackage') >= 5)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="silver-artist" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                    @else
                                        <form action="{{ url('Client_payment1') }}" method="post">
                                        @csrf
                                            <input type="submit" class="btn btn-gradient btn-theme" name="silver-artist" value="BUY"/>
                                        </form>
                                    @endif
                                </div>
                            </div><!-- dso-item -->

                           
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 pb-2">
                            <div class="dso-item gold-plan">
                                <h3>GOLD</h3>
                                <!-- <h5>Packages</h5> -->
                                <div class="price clearfix">
                                    <h4><i>$450/Month</i></h4>
                                </div><!-- eof price -->

                                <ul>
                                    <li>Up to 2 project promotion (Added to Digiwaxx Pool with Feedback from DJs)</li>
                                    <li>2 email blasts per release</li>
                                    <li>2 posts on Social (Twitter, IG & Facebook)</li>
                                    <li>1 Blog post</li>
                                </ul>
                                <div class="sub-btn">
                                    @if(session()->get('clientPackage') >= 6)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="gold-artist" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                    
                                    @else
                                    <form action="{{ url('Client_payment1') }}" method="post">
                                    @csrf
                                        <input type="submit" class="btn btn-gradient btn-theme" name="gold-artist" value="BUY"/>
                                    </form>
                                    @endif
                                </div>
                            </div><!-- dso-item -->

                            
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 pb-2">
                            <div class="dso-item purple-plan">
                                <h3>PURPLE</h3>
                                <!-- <h5>Packages</h5> -->
                                <div class="price clearfix">
                                    <h4><i>$750/Month</i></h4>
                                </div><!-- eof price -->

                                <ul>
                                    <li>Up to 3 project promotion (Added to Digiwaxx Pool with Feedback)</li>
                                    <li>2 email blasts per release</li>
                                    <li>6 posts on Social (Twitter, IG & Facebook)</li>
                                    <li> 1 Blog post</li>
                                </ul>
                                <div class="sub-btn">
                                    @if(session()->get('clientPackage') >= 7)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="purple-artist" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                    @else
                                    <form action="{{ url('Client_payment1') }}" method="post">
                                    @csrf
                                        <input type="submit" class="btn btn-gradient btn-theme" name="purple-artist" value="BUY"/>
                                    </form>
                                    @endif
                                </div>
                            </div><!-- dso-item -->

                            
                        </div>

                        <div style="clear:both;"></div>

                    </div>
                </div>
                <div class="dso-sec center-block">
                    <h2>Record Label Membership</h2>
                    <div class="row" id="artist-subscription">
                        <div class="col-lg-4 col-md-4 col-sm-4 pb-2">
                            <div class="dso-item silver-plan">
                                <h3>SILVER</h3>
                                <!-- <h5>Packages</h5> -->
                                <div class="price clearfix">
                                    <h4><i>$999/Month</i></h4>
                                </div><!-- eof price -->
                                <ul>
                                    <li>Up to 3 Singles (Added to Digiwaxx Pool)</li>
                                    <li>2 email blasts per release</li>
                                    <li>2 label email blasts (all your releases)</li>
                                    <li>6 posts on Social (Twitter, IG & Facebook)</li>
                                    <li>1 DJ Conference Call</li>
                                </ul>
                                 <div class="sub-btn">
                                    @if(session()->get('clientPackage') >= 8)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="silver-label" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                    
                                    @else                               
                                        <form action="{{ url('Client_payment1') }}" method="post">
                                        @csrf
                                            <input type="submit" class="btn btn-gradient btn-theme" name="silver-label" value="BUY"/>
                                        </form>
                                    @endif
                                </div>
                            </div><!-- dso-item -->

                           
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 pb-2">
                            <div class="dso-item gold-plan">
                                <h3>GOLD</h3>
                                <!-- <h5>Packages</h5> -->
                                <div class="price clearfix">
                                    <h4><i>$1650/Month</i></h4>
                                </div><!-- eof price -->

                                <ul>
                                    <li>Up to 5 Singles (Added to Digiwaxx Pool)</li>
                                    <li>2 email blasts per release</li>
                                    <li>3 label email blasts (all your releases)</li>
                                    <li>10 posts on Social (Twitter, IG & Facebook)</li>
                                    <li>1 DJ Conference Call</li>
                                    <li>Blog post</li>
                                </ul>
                                <div class="sub-btn">
                                    @if(session()->get('clientPackage') >= 9)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="gold-label" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                    @else
                                    <form action="{{ url('Client_payment1') }}" method="post">
                                    @csrf
                                        <input type="submit" class="btn btn-gradient btn-theme" name="gold-label" value="BUY"/>
                                    </form>
                                    @endif
                                </div>
                            </div><!-- dso-item -->

                            
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 pb-2">
                            <div class="dso-item purple-plan">
                                <h3>PURPLE</h3>
                                <!-- <h5>Packages</h5> -->
                                <div class="price clearfix">
                                    <h4><i>$1999/Month</i></h4>
                                </div><!-- eof price -->

                                <ul>
                                    <li>Up to 5 Singles (Added to Digiwaxx Pool)</li>
                                    <li>2 email blasts per release</li>
                                    <li>3 label email blasts (all your releases)</li>
                                    <li>10 posts on Social (Twitter, IG & Facebook)</li>
                                    <li>1 DJ Conference Call</li>
                                    <li>Blog post</li>
                                    <li>Facebook Ad campaign (retarget our DJs and influencers and more DJs)</li>
                                    <li>Free phone one on one consultation</li>
                                    <li>Video featured on homepage (1 day)</li>
                                    <li>Campaign featured on homepage slider (1 Day)</li>
                                </ul>
                                 <div class="sub-btn">
                                    @if(session()->get('clientPackage') >= 10)
                                        <input type="submit" class="btn btn-gradient btn-theme" name="purple-label" value="BUY" disabled="disabled" style="background:#ebebeb !important;color: #7b7b7b;"/>
                                    @else
                                    <form action="{{ url('Client_payment1') }}" method="post">
                                        @csrf
                                        <input type="submit" class="btn btn-gradient btn-theme" name="purple-label" value="BUY"/>
                                    </form>
                                    @endif
                                </div>
                            </div><!-- dso-item -->

                           
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<!--popup close-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
 <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('public/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('public/js/menu.js') }}"></script>
<script src="{{ asset('public/js/TMSearch.js') }}"></script>
<script src="{{ asset('public/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" defer></script>
<script src="{{ asset('public/jvectormap/jquery-jvectormap-world-mill-en.js') }}" defer></script>
<script src="{{ asset('public/jplayer/js/jquery.jplayer.min.js') }}" defer></script>
<script src="{{ asset('public/jplayer/js/jplayer.playlist.min.js') }}" defer></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script> -->
<?php 
if(!empty($tracks)){
    $count = $tracks['numRows'];
    if($count>0){?>
        <script>
        
        
        
        
         function changeTrack(track_title,track_artist,track_mp,track_ogg,track_poster)
        
          {
        
          
        
         document.getElementById('jp-image-img').src = track_poster;
        
         document.getElementById('jp-title').innerHTML = track_title;
        
         $("#jquery_jplayer_1").jPlayer("setMedia", {mp3: track_mp}).jPlayer("play");   
        
          }
        
          
        
          
        
          
        
          $(document).ready(function(){
        
        
        
        	new jPlayerPlaylist({
        
        		jPlayer: "#jquery_jplayer_1",
        
        		cssSelectorAncestor: "#jp_container_1"
        
        	}, [
        
        	
        
        	<?php   
        
        	   $count = $tracks['numRows'];
        
        	   for($i=0;$i<$count;$i++)
        
        	   {
                 $getlink = '';
        	  	 if (strpos($tracks['data'][$i]->location, '.mp3') !== false) {
        	  	     $getlink = asset('AUDIO/'.$tracks['data'][$i]->location);
        	  	 } else {
        	  	     $fileid = (int)$tracks['data'][$i]->location;
        	  	     if(!empty($fileid)){
        	  	         $getlink = url('download.php?fileID='.$fileid);
        	  	     }
        	  	 } 
        	  	?>
        
        		 {
        
        			title:"<?php echo urldecode($tracks['data'][$i]->title); ?>",
        
        			artist:"<?php echo urldecode($tracks['data'][$i]->artist); ?>",
        
        			mp3:"<?php echo $getlink; ?>",
        
        			poster:"<?php echo asset("ImagesUp/".$tracks['data'][$i]->imgpage); ?>"
        
        		}
        
        		<?php  if($i!=$count-1) { echo ','; } } ?>
        
        	], {
        
        		swfPath: "../../dist/jplayer",
        
        		supplied: "webmv, ogv, m4v, oga, mp3",
        
        		useStateClassSkin: true,
        
        		autoBlur: false,
        
        		smoothPlayBar: true,
        
        		keyEnabled: true,
        
        		audioFullScreen: true
        
        	});
        
        	$('.jp-play').click(function() {
                if($('#jquery_jplayer_1').data().jPlayer.status.paused) {
                    $("#jquery_jplayer_1").jPlayer("play");
                } else {
                    $("#jquery_jplayer_1").jPlayer("pause");
                } 
            });
        
        	//	 $("#jquery_jplayer_1").jPlayer("pause", 15);
        
        	
        
        //	$("#jquery_jplayer_1").jPlayer("mute", true);
        
        });
        </script>
<?php 
    }

  }?>



<script>



    // $(document).ready(function () {
    //     new jPlayerPlaylist({
    //         jPlayer: "#jquery_jplayer_1",
    //         cssSelectorAncestor: "#jp_container_1"
    //     }, [
    //         {
    //             title: "test",
    //             artist: "test",
    //             mp3: "{{ url('AUDIO/') }}",
				// poster: "{{ url('ImagesUp/') }}"
    //         }
    //     ], {
    //         swfPath: "../../dist/jplayer",
    //         supplied: "webmv, ogv, m4v, oga, mp3",
    //         useStateClassSkin: true,
    //         autoBlur: false,
    //         smoothPlayBar: true,
    //         keyEnabled: true,
    //         audioFullScreen: true
    //     });
    // });
    $(function () {
        $('#world-map-1').vectorMap({
            map: 'world_mill_en',
            backgroundColor: 'transparent',
            zoomOnScroll: false,
            zoomButtons: false,
            regionStyle: {
                initial: {
                    fill: '#fff',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0,
                    "stroke-opacity": 1
                },
                hover: {
                    fill: '#f98cd4'
                }
            },

            series: {
                regions: [{
                    attribute: 'fill'
                }]
            }
        });


        var map1 = $('#world-map-1').vectorMap('get', 'mapObject');

        map1.series.regions[0].setValues('Mohali');


    });


</script>
<div class="processing_loader_gif"><img src="{{ asset('public/images/loader.gif') }}"></div>
</body>
</html> 