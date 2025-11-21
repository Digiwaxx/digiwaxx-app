<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $pageTitle; ?></title>

    <!--favicon-->
    <link rel="icon" href="<?php echo asset('assets/img/icon.png'); ?>" type="image/gif" sizes="16x16">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- Bootstrap -->
    <link href="<?php echo asset('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="<?php echo asset('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('assets/css/search.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap-select.css'); ?>">
    <link href="<?php echo asset('assets/jplayer/css/jplayer.pink.flag.min.css'); ?>" rel="stylesheet"
          type="text/css" />
    <link href="<?php echo asset('assets/jplayer/css/jplayer-main.css'); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo asset('assets/scrollbar/jquery.mCustomScrollbar.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/ratings/bars-square.css'); ?>">

    <!-- jquery validation -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="<?php  echo asset('assets/js/jquery.validate.min.js'); ?>"></script>

    <!--audio css-->
    <link rel="stylesheet" href="<?php  echo asset('assets/sap/mediaelementplayer.min.css'); ?>">

    <!--Start of Zendesk Chat Script-->
    <!--<script type="text/javascript">-->
    <!--    window.$zopim || (function (d, s) {-->
    <!--        var z = $zopim = function (c) { z._.push(c) }, $ = z.s =-->
    <!--            d.createElement(s), e = d.getElementsByTagName(s)[0]; z.set = function (o) {-->
    <!--                z.set.-->
    <!--                _.push(o)-->
    <!--            }; z._ = []; z.set._ = []; $.async = !0; $.setAttribute("charset", "utf-8");-->
    <!--        $.src = "https://v2.zopim.com/?65Iyo2QqrvLLjKtuAj57VxlySVMCNAlA"; z.t = +new Date; $.-->
    <!--            type = "text/javascript"; e.parentNode.insertBefore($, e)-->
    <!--    })(document, "script");-->
    <!--</script>-->
    <!--End of Zendesk Chat Script-->
    <!-- Start of Async Drift Code -->
    <script>
        "use strict";
        ! function() {
            var t = window.driftt = window.drift = window.driftt || [];
            if (!t.init) {
                if (t.invoked) return void(window.console && console.error && console.error(
                    "Drift snippet included twice."));
                t.invoked = !0, t.methods = ["identify", "config", "track", "reset", "debug", "show", "ping", "page",
                        "hide", "off", "on"
                    ],
                    t.factory = function(e) {
                        return function() {
                            var n = Array.prototype.slice.call(arguments);
                            return n.unshift(e), t.push(n), t;
                        };
                    }, t.methods.forEach(function(e) {
                        t[e] = t.factory(e);
                    }), t.load = function(t) {
                        var e = 3e5,
                            n = Math.ceil(new Date() / e) * e,
                            o = document.createElement("script");
                        o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src =
                            "https://js.driftt.com/include/" + n + "/" + t + ".js";
                        var i = document.getElementsByTagName("script")[0];
                        i.parentNode.insertBefore(o, i);
                    };
            }
        }();
        drift.SNIPPET_VERSION = '0.3.1';
        drift.load('7nf4s92y2ndv');
    </script>
    <!-- End of Async Drift Code -->
    <style>
    
    .search-form_liveout .search_link{
        background: #8c8080;
    }
    .search-form_liveout {
        max-height: 200px;
        overflow-y: scroll;
    }
    .sg-search-results {
        /*display: none;*/
        background: #8c8080;
        float: left;
        max-height: 200px;
        overflow-y: scroll;
        width: 100%;
    }
    .sg-search-results a {
        float: left;
        padding: 3px 10px;
    }
    @media screen and (max-width: 480px) {
        .sg_left{
            float:left;
        }
    }
    
    </style>
</head>
<body>
    <?php 
    
    $wrapperClass  = '';
    
    if(isset($_COOKIE['memberId']))  { $wrapperClass = 'member'; } else if(isset($_COOKIE['tempMemberId'])) { $wrapperClass = ''; } ?>
    <div class="wrapper <?php echo $wrapperClass; ?>">
        <div class="header">
            <div class="header-top">
                <div class="container-fluid">
                    <div class="container">
                        <div class="logo pull-left col-sm-2">
                            <a href="#">
                            <img src="<?php echo url('assets/img/'.$logo[0]->logo); ?>" />
                            </a>
                        </div>
                        <div class="col-sm-8 sg_left"><a style="float:right;color: #fff;padding: 10px;margin: 15px 0px;font-size: 18px;font-weight: 600;" href="<?php echo url("Member_dashboard_newest_tracks"); ?>">My dashboard</a>
                            <!--Banner Ad-->
                            <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
                            <script>
                                var googletag = googletag || {};
                                googletag.cmd = googletag.cmd || [];
                            </script>
                            <!-- <script>
                            googletag.cmd.push(function() {
                                googletag.defineSlot('/21741445840/digi001', [728, 90], 'div-gpt-ad-1538134440558-0').addService(googletag.pubads());
                                googletag.pubads().enableSingleRequest();
                                googletag.enableServices();
                            });
                            </script> -->
                            <!-- /21741445840/digi001 -->
                            <!-- <div id='div-gpt-ad-1538134440558-0' style='height:90px; width:728px; margin:0px auto;'>-->
                            <script>
                            // googletag.cmd.push(function() { googletag.display('div-gpt-ad-1538134440558-0'); });
                            </script>
                        </div>
                        <div class="topbar pull-right col-sm-2">
                            <ul>
                                <li class="search-btn">
                                    <a href="#" class="search-form_toggle"><i class="fa fa-search fa-2x"></i></a>
                                    <form class="search-form" action="" method="GET" accept-charset="utf-8" >
                                        <label class="search-form_label" onblur="blur_fn()">
                                            <input class="search-form_input" id="search_input" type="text" name="s" autocomplete="on" onfocus="focus_check()"  onkeyup="search_ajax()" placeholder="What are you looking for?" />
                                            <div class="sg-search-results"></div>
                                            <span class="search-form_liveout"><!-- Display Search Results Here --> </span>
                                        </label>
                                        <button class="search-form_submit fa-search" type="submit"></button>
                                    </form>
                                </li>
                                <?php
                                    if(!empty($_COOKIE['memberPackage']))
                                    {

                                        $memberPackage = $_COOKIE['memberPackage'];

                                    }

                                    else{

                                        $memberPackage = '';
                                    }
                                if($memberPackage>1) { ?>
                                <li class="msg-btn">
                                    <a href="<?php echo url("Member_messages"); ?>"><i class="fa fa-envelope fa-2x hide visible-xs-inline"></i><span class="hidden-xs">MESSAGES</span>
                                    <?php if(isset($numMessages) && $numMessages>0) { ?> 
                                        <span class="bdg"><?php echo $numMessages; ?></span> 
                                    <?php } ?> 
                                    </a> 
                                </li>
                                <?php } ?>
                                <?php if(isset($_COOKIE['memberId'])) { ?>
                                <li class="pro-btn">
                                    <a href="#" class="pr-link">
                                        
                                        <?php if(strlen($_COOKIE['memberImage'])>4) { 
                                            $img = asset("member_images/".$_COOKIE['memberId']."/".$_COOKIE['memberImage']);
                                        }
                                        else
                                        { 
                                            $img =  asset("assets/img/profile-pic.png");
                                        } ?>
                                        <img src="<?php echo $img; ?>">
                                        <span class="hidden-sm hidden-xs"><?php echo strtoupper ($_COOKIE['memberName']); ?></span>
                                    </a>
                                    <div class="profile-menu">
                                        <ul>
                                            <li><a href="<?php echo url('Member_dashboard_all_tracks'); ?>">MY DASHBOARD</a></li>
                                            <li><a href="<?php echo url('Member_edit_profile'); ?>">EDIT MY PROFILE</a></li>
                                            <li><a href="#">MY TOKENS</a></li>
                                            <li><a href="<?php echo url('Member_change_password'); ?>">CHANGE PASSWORD</a></li>
                                            <?php if($subscriptionStatus==0) { ?>
                                            <li><a href="<?php echo url('Member_subscriptions'); ?>">PAYMENT</a></li>
                                            <?php } else { ?>
                                            <!--<li><a href="#"><?php //echo strtoupper($package); ?></a>-->
                                            <!--    <?php //if($packageId<3) { ?><br /> <a-->
                                            <!--    href="<?php //echo base_url("Member_subscriptions"); ?>">Upgrade</a>-->
                                            <!--    <?php //} ?>-->
                                            <!--</li>-->
                                            <!--<?php //} ?>-->
                                            
                                            <li><a href="#"><?php echo strtoupper($package); ?></a>
    				                            <?php if($packageId<3) { ?>
        				                        <br /> <a href="<?php echo url("Member_subscriptions"); ?>">Upgrade</a>
        				                        <?php } else if ($packageId == 3) { ?>
        				                        <br /> <a href="<?php echo url("Member_subscriptions"); ?>">Downgrade</a>
        				                        <?php } ?>
        			                        </li>
    									<?php } ?>
                                            <li><a href="<?php echo url('Buy_digicoins'); ?>">BUY DIGICOINS</a></li>
                                            <li><a href="<?php echo url('Help'); ?>">HELP</a></li>
                                            <li><a href="<?php echo $logout_link; ?>">LOG OUT</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <?php } else if(isset($_COOKIE['tempMemberId'])) { ?>
                                <li class="pro-btn">
                                    <a href="#" class="pr-link"><span class="hidden-sm hidden-xs"><?php echo strtoupper ($_COOKIE['memberName']); ?></span>
                                        <?php
                                            if(strlen($_COOKIE['memberImage'])>4) 
                                            { $img = asset("member_images/".$_COOKIE['tempMemberId']."/".$_COOKIE['memberImage']);    }
                                            else
                                            { $img = 'assets/img/profile-pic.png';  } 
                                        ?>
                                        <img src="<?php echo $img; ?>">
                                        <span class="hidden-sm hidden-xs"><?php echo strtoupper ($_COOKIE['memberName']); ?></span>
                                    </a>
                                    <div class="profile-menu">
                                        <ul>
                                            <li><a href="<?php echo $logout_link; ?>">LOG OUT</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <?php } ?>
                                <li class="menu-btn"><a href="#"><i class="fa fa-bars fa-2x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- eof header-top -->
        </div><!-- eof header -->
        
        <script>
            function search_ajax(){
                $(".search-form_liveout").html('');
                var key=document.getElementById('search_input').value;
                $.ajax({url: "Member_dashboard_all_tracks?key="+key+"&header_search_ajax=sg", 
			    	    success: function(result){
			    	        // $(".search-form_liveout").html(result);
			    	        $(".sg-search-results").html(result);
			    	        
			    	        console.log('changed');
                        }
			    	});
            }
            
            function blur_fn(){
                // $('#search_input').html('');
                if ($(".sg-search-results, .sg-search-results a").is(":focus")) {
                    
                }else{
                     $(".sg-search-results").html('');
                }
                
                // console.log("blur");
            }
            function focus_check(){
                if(document.getElementById('search_input').value==''){
                    $(".sg-search-results").html('');
                }
            }
            
        </script>