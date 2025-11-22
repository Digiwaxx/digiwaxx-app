<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
	<!--favicon-->
	<link rel="icon" href="{{ asset('public/images/icon.png') }}" type="image/gif" sizes="16x16">

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}" defer></script>
    <!-- Styles -->
    <!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"-->
	 <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('public/plugins/bootstrap/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/font-awesome.css')}}" />
        <!--link rel="stylesheet" href="{{ asset('public/css/app.css') }}"-->
        <link rel="stylesheet" href="{{ asset('public/css/search.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/bootstrap-select.css') }}">
        <link rel="stylesheet" href="{{ asset('public/plugins/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
		<link rel="stylesheet" href="{{ asset('public/css/custom-style.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
        <!-- Scripts -->
		<script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/js/jquery-ui.js') }}"></script>
		<script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('public/js/app.js') }}" defer></script>
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
	<style>
		.or, .modal-footer .sip {text-align: center;}
		.modal-footer{display:block}
		.footer{
			margin-top: 0;
			//background: #000;
		}
    .modal-dialog {
        margin-bottom: 0px;
    }
	.login_btn.btn-block {
		width: 100%;
	}
    .login p.sip{
        font-family: 'Quicksand', sans-serif;
        font-size: 14px;
        font-weight: 500;
        margin: 0;
        margin-bottom: 5px;
        text-transform: capitalize !important;
    }
    .login h2{
        margin-top: 0;
        font-size: 25px;
        margin-bottom: 15px;
    }
    .login .form-control{
        font-size: 18px;
        height: 52px;
    }
    .field-icon{
        margin-top: -37px;
        font-size: 25px;
        margin-right: 10px;
        margin-left: 0px;
        position: relative;
        color: black;
        z-index: 2;
        float: right;
    }
    .login .rme label, .login .fyp a{
        font-size: 17px;
    }
    .login .rme input[type=checkbox]{
        margin-top: 5px;
    }
    .logo{
        padding:0;
    }
    .login .or{
        margin: 15px;
        font-size: 15px;
    }
    .login .login_btn{
        padding: 12px;
        font-size: 18px
    }
    .login .logo{
        width: 260px;
    }
    .login .form-control, .login h2, .login .rme label, .login .fyp a, .login .login_btn{
        font-family: 'Quicksand', sans-serif;
        font-weight: 500;
    }
    }
	</style>
</head>
<body>
    <div id="app" class="wrapper">		
		<!-- <div class="header">
            <div class="header-top">
                <div class="container-fluid">
                    <div class="container">
                        <div class="row">
                            <div class="logo pull-left col-sm-2">
                                <a href="{{ url('/') }}"><img src="{{ asset('public/images/logo.png') }}"></a>
                            </div>
                            <div class="col-sm-8 sg_left">
                                                                Banner Ad-->
                            <!--</div>
                            <div class="topbar pull-right col-sm-2">
                                <ul>
								
							@if(!Session::has('loggedin_user'))
                            <li class="nav-item login-btn">
                                <a class="nav-link" href="{{ route('login') }}"><span class="hidden-xs">{{ __('Login') }}</span></a>
                            </li>
							
                            <li class="nav-item login-btn">
                                    <a class="nav-link" href="{{ route('register') }}"><span class="hidden-xs">{{ __('Register') }}</span></a>
                            </li>
                            @else
                            <li class="nav-item dropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <span class="hidden-xs">{{ __('Logout') }}</span>
                                </a>
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
		</div> <!--eof header -->
        <!--header start-->
    <header>
      <div class="container">
        <div class="row">
              <div class="col-md-3 col-sm-4 col-7">
              <div class="logo">
                <a href="https://digiwaxx.com"><img src="{{ asset('public/images/logo.png') }}"></a>
              </div>
            </div>
            <div class="col-md-9 col-sm-8 col-5">
              <div class="header-right">
                <div class="add-banner"></div>
                <div class="header-nav">
                  <ul>
                    @if(Session::has('clientId') || Session::has('tempClientId') || Session::has('memberId') || Session::has('tempMemberId'))
					<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
						<?php

							$memberImage_get = Session::get('memberImage');
							$memberId = Session::get('memberId');

							$clientImage_get = Session::get('clientImage');
							$clientId=Session::get('clientId');

							if(!empty($memberId)){

    						    if (!empty($memberImage_get) && strlen($memberImage_get) > 4) {
    								$img = asset("member_images/" . $memberId . "/" . $memberImage_get);
    							} else {
    								$img = asset('public/images/path/avatar/avatar.jpg');
    							}
							}else{
							    if(!empty($clientId)){
    						        if (!empty($clientImage_get) && strlen($clientImage_get) > 4) {
        								$img = asset("client_images/" . $clientId . "/" . $clientImage_get);
        							} else {
        								$img = asset('public/images/path/avatar/avatar.jpg');
        							}
							    }
							}
    						?>
						<img src="{{ $img }}" class="avatar img-fluid  me-1">
						@if(Session::has('clientId'))
						<span class="d-none d-sm-inline-block">{{ urldecode(Session::get('clientName')) }}</span>
						@else
						<span class="d-none d-sm-inline-block">{{ urldecode(Session::get('memberName')) }}</span>
						@endif
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							@if(!Session::has('tempClientId') && !Session::has('tempMemberId'))
									   @if(Session::get('clientPackage') > 1)
											<a href="{{ url('Client_dashboard') }}" class="dropdown-item">MY DASHBOARD</a>								
										@elseif(Session::get('memberPackage') > 1)
											<a href="{{ route('member-newest-tracks') }}" class="dropdown-item">MY DASHBOARD</a>
										@else
											@if(Session::has('memberId'))
											<a href="{{ route('member-newest-tracks') }}" class="dropdown-item">MY DASHBOARD</a>
											@else
											<a href="{{ route('Client_dashboard') }}" class="dropdown-item">MY DASHBOARD</a>
											@endif
										@endif
										
									@if(Session::has('memberId'))
									<a href="{{ route('Member_edit_profile') }}" class="dropdown-item">EDIT MY PROFILE</a>
									@else
									<a href="{{ route('ClientEditProfile') }}" class="dropdown-item">EDIT MY PROFILE</a>
									@endif
									
									@if(Session::has('memberId'))
									<a href="{{ route('Member_change_password') }}" class="dropdown-item">CHANGE PASSWORD</a>
									@else
									<a href="{{ route('ClientChangePasssword') }}" class="dropdown-item">CHANGE PASSWORD</a>
									@endif
									<a href="{{ route('Buy_digicoins') }}" class="dropdown-item">BUY DIGICOINS</a>
									<a href="{{ url('Help') }}" class="dropdown-item">HELP</a>
								@endif
									<a href="{{ url('logout') }}" class="dropdown-item">LOG OUT</a>
								</div>
									</li>
                   
                    @else
						 <li class="login"><a class="nav-link hidden-xs" href="{{ route('login') }}">{{ __('Login') }}</a></li>
						<li class="signup"><a class="nav-link hidden-xs" href="{{ route('register') }}">{{ __('Register') }}</a></li>
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
       <script src="https://my.hellobar.com/1e3071cb5de48ba66d8585c1b6cd52c656dc170e.js" type="text/javascript" charset="utf-8" async="async"></script> 
    </header>
    <!--header end-->

        <main class="">
            @yield('content')
        </main>
	<!-- <div class="footer">
    <div class="container">
        <h1><img class="logo_img" src="{{ asset('public/images/logo.png') }}"></h1>
        <p class="cap1">App coming soon</p>
        <p class="cap2">
            <a href="#"><i class="fa fa-apple"></i></a>
            <a href="#"><i class="fa fa-android"></i></a>
        </p>
        <div class="menu">
            <a href="{{ url('/') }}">HOME</a>
            
            <a href="{{ url('Client_tracks') }}">MY TRACKS</a>
            
            <a href="{{ url('PromoteYourProject') }}">PROMOTE YOUR PROJECT</a>
            <a href="{{ url('Charts') }}">CHARTS</a>
            <a href="{{ url('DigiwaxxRadio') }}">DIGIWAXX RADIO</a>
            <a href="{{ url('Contactus') }}">CONTACT US</a>
        </div>
        <p class="social">
            <a target="_blank" href="https://twitter.com/Digiwaxx"><i class="fa fa-twitter"></i></a>
            <a target="_blank" href="https://www.facebook.com/digiwaxx"><i class="fa fa-facebook"></i></a>
            <a target="_blank" href="{{ url('/') }}"><i class="fa fa-google-plus"></i></a>
            <a target="_blank" href="https://www.instagram.com/digiwaxx"><i class="fa fa-instagram"></i></a>
            <a target="_blank" href="https://www.linkedin.com/company/digiwaxx-media"><i class="fa fa-linkedin"></i></a>
        </p>
        <p class="copy"><a href="{{ url('Privacy_policy') }}">Privacy policy</a></p>
        <p class="copy">&copy; Digiwaxx, LLC.</p>
    </div>
</div> --><!-- eof footer -->

@include('layouts.include.site-footer')


</div><!-- eof wrapper -->




<div class="menu-con">
    <a href="#" class="menu-close"><i class="fa fa-close"></i></a>
    <ul>
        <li><a href="https://digiwaxx.com">HOME</a></li>
		 @if(empty(Session::get('clientId')) && empty(Session::get('memberId')))
        <li><a href="{{ url('/login') }}">LOG IN</a></li>
        <li><a href="{{ url('/register') }}">SIGN UP</a></li>
		@endif
		<li><a href="{{ route('Client_tracks') }}">MY TRACKS</a></li>
		<!-- <li><a href="javascript:void()" data-toggle="modal" data-target="#alertModal">MY DASHBOARD</a></li>-->
		<li><a href="{{ url('/PromoteYourProject') }}">PROMOTE YOUR PROJECT</a></li>
        <li><a href="{{ url('Charts') }}">CHARTS</a></li>
        <!--li><a href="{{ route('all_news') }}">NEWS</a></li>
        <li><a href="{{ route('list_forum') }}">FORUMS</a></li>
         <li><a href="{{ route('all_videos') }}">VIDEOS</a></li-->
        <li><a href="https://digiwaxxradio.com/">DIGIWAXX RADIO</a></li>
        <li><a href="{{ url('/Contactus') }}">CONTACT US</a></li>
        <!--<li><a href="">PRESS</a></li>-->
        <!--<li><a href="">CLIENTS</a></li>-->
        <!--<li><a href="">WALL OF SCRATCH</a></li>-->
        <li><a href="{{ url('/WhatWeDo') }}">WHAT WE DO</a></li>
        <li><a href="{{ url('/FreePromo') }}">FREE PROMO</a></li>
        <!--<li><a href="">EVENTS</a></li>-->
        <!--<li><a href="">TESTIMONALS</a></li>-->
        <li><a href="{{ url('/WhyJoin') }}">WHY JOIN</a></li>
        <li><a href="{{ url('/SponsorAdvertise') }}">SPONSOR ADVERTISE</a></li>
        <li><a href="{{ url('/Help') }}">HELP</a></li>
        <!--<li class="visible-xs hidden-sm hidden-md hidden-lg"><a href="< ?php echo base_url('Help'); ?>"><span>HELP</span></a></li>-->
             <!--li class="visible-xs hidden-sm hidden-md hidden-lg"><a href="{{ url('/login') }}"><span>LOG IN</span></a></li>
            <li class="visible-xs hidden-sm hidden-md hidden-lg"><a href="{{ url('/register') }}"><span>SIGN UP</span></a></li-->
            </ul>
</div>
<script src="{{ asset('public/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('public/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/menu.js') }}"></script>
<script src="{{ asset('public/js/TMSearch.js') }}"></script>
<script src="{{ asset('public/js/bootstrap-select.js') }}" defer></script>
<script>
$(document).ready(function(){
	$('.selectpicker').selectpicker();
	
	$(".bootstrap-select").click(function () {
        $(".bootstrap-select").removeClass("open");
        $(this).addClass("open");
		/* $this = $(this);
		setTimeout(function() {
			$this.removeClass('open');
		}, 2000); */
      });
      $(document).click(function(){
        $(".bootstrap-select").removeClass("open");
      });
      $(".bootstrap-select").click(function(e){
        e.stopPropagation();
    });
	
});
</script>
</body>
</html>