<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php if (isset($meta_description)) {
                                            echo $meta_description;
                                        } ?>">
    <meta name="keywords" content="<?php if (isset($meta_keywords)) {
                                        echo $meta_keywords;
                                    } ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo ''; ?></title>
    <!--favicon-->
    <link rel="icon" href="<?php echo asset('assets/img/icon.png'); ?>" type="image/gif" sizes="16x16">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-127182764-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-127182764-1');
    </script>
    <meta name="google-site-verification" content="go1mjlFVP9f_RYRaIe08_tBmHen8NH3REPXiFPOT-SE" />
    <meta name="google-site-verification" content="WF8t_MBDxgNaPZAaVS7_NaLpw-IHhVHEWK4RfTy4x_4" />
    <meta name="msvalidate.01" content="43DD4B0E44446F913F26D23269EB99D2" />
    <!--<div id="google_translate_element"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
-->
    <!-- Bootstrap -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="<?php echo asset('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset('assets/css/style.css'); ?>" rel="stylesheet">
    <!-- jquery validation -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
    <script src="<?php echo asset('assets/js/jquery.min.js'); ?>"></script>
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

</head>
<body>
    <?php error_reporting(0); ?>
    <div class="wrapper sgt12">
        <div class="header">
            <div class="header-top">
                <div class="container-fluid">
                    <div class="container">
                        <div class="row">
                            <div class="logo pull-left col-sm-2">
                                <a href="<?php echo url('/'); ?>">
                                <img src="{{ asset('assets/img/logo.png')}}" />
                                </a>
                            </div>
                            <div class="col-sm-8 sg_left">
                                <?php
                                if (isset($_SESSION['memberId']) || isset($_SESSION['clientId']) ){
                                ?>
                                <a style="float:right;color: #fff;padding: 10px;margin: 15px 0px;font-size: 18px;font-weight: 600;text-transform: uppercase;" 
                                href="<?php echo url('/'); ?>Member_dashboard_newest_tracks">My dashboard</a>
                                <?php } ?>
                                <!--Banner Ad-->
                            </div>
                            <div class="topbar pull-right col-sm-2">
                                <ul>
                                    <?php if (isset($_SESSION['clientId'])) { ?>
                                        <li class="pro-btn">
                                            <a href="#" class="pr-link">
                                                
                                                <?php
                                                if (strlen($_SESSION['clientImage']) > 4) {
                                                    $img = asset("client_images/" . $_SESSION['clientId'] . "/" . $_SESSION['clientImage']);
                                                } else {
                                                    $img = 'assets/img/profile-pic.png';
                                                }
                                                ?>
                                                <img src="<?php echo $img; ?>">
                                                <span class="hidden-sm hidden-xs"><?php echo strtoupper($_SESSION['clientName']); ?></span>
                                            </a>
                                            <div class="profile-menu">
                                                <ul>
                                                    <?php // if($displayDashboard==1) { 
                                                    ?>
                                                    <li><a href="<?php echo url('Client_tracks'); ?>">MY TRACKS</a>
                                                    </li>
                                                    <?php // } else { 
                                                    ?>
                                                    <!-- <li><a href="javascript:void()" data-toggle="modal" data-target="#alertModal">MY DASHBOARD</a></li>-->
                                                    <?php // } 
                                                    ?>
                                                    <li><a href="<?php echo url('Client_edit_profile'); ?>">EDIT MY
                                                            PROFILE</a></li>
                                                    <li><a href="<?php echo url('Client_dashboard'); ?>">MY TOKENS</a>
                                                    </li>
                                                    <li><a href="<?php echo url('Client_change_password'); ?>">CHANGE
                                                            PASSWORD</a></li>
                                                    <li><a href="<?php echo url('Buy_digicoins'); ?>">BUY DIGICOINS</a>
                                                    </li>
                                                    <li><a href="<?php echo url('Help'); ?>">HELP</a></li>
                                                    <li><a href="<?php echo $logout_link; ?>">LOG OUT</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    <?php } else if (isset($_SESSION['memberId'])) { ?>
                                        <li class="pro-btn"><a href="#" class="pr-link">
                                            
                                                <?php


                                                if (strlen($_SESSION['memberImage']) > 4) {
                                                    $img = asset("member_images/" . $_SESSION['memberId'] . "/" . $_SESSION['memberImage']);
                                                } else {
                                                    $img = 'assets/img/profile-pic.png';
                                                }

                                                ?>
                                                <img src="<?php echo $img; ?>">
                                                <span class="hidden-sm hidden-xs"><?php echo strtoupper($_SESSION['memberName']); ?></span>
                                            </a>
                                            <div class="profile-menu">
                                                <ul>
                                                    <li><a href="<?php echo url('Member_dashboard_all_tracks'); ?>">MY
                                                            DASHBOARD</a></li>
                                                    <li><a href="<?php echo url('Member_edit_profile'); ?>">EDIT MY
                                                            PROFILE</a></li>
                                                    <li><a href="#">MY TOKENS</a></li>
                                                    <li><a href="<?php echo url('Member_change_password'); ?>">CHANGE
                                                            PASSWORD</a></li>
                                                    <?php if ($subscriptionStatus == 0) { ?>
                                                        <li><a href="<?php echo url('Member_subscriptions'); ?>">PAYMENT</a>
                                                        </li>
                                                    <?php } else { ?>
                                                        <li><a href="#"><?php echo strtoupper($package); ?></a><br />
                                                            <?php if ($packageId < 3) { ?> <a href="<?php echo url("Member_subscriptions"); ?>">Upgrade</a>
                                                            <?php } ?>
                                                        </li>
                                                    <?php } ?>
                                                    <li><a href="<?php echo url('Buy_digicoins'); ?>">BUY DIGICOINS</a>
                                                    </li>
                                                    <li><a href="<?php echo url('Help'); ?>">HELP</a></li>
                                                    <li><a href="<?php echo $logout_link; ?>">LOG OUT</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    <?php } else { ?>
                                        <li class="login-btn">
                                            <a href="<?php echo url('login'); ?>">
                                                <i class="fa fa-sign-in fa-2x hide visible-xs-inline" aria-hidden="true"></i>
                                                <span class="hidden-xs">LOG IN</span>
                                            </a>
                                        </li>
                                        <li class="hidden-xs login-btn">
                                            <a href="<?php echo url('Signup'); ?>">
                                                <i class="fa fa-user-plus fa-2x hide visible-xs-inline"></i>
                                                <span class="hidden-xs">SIGN UP</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="menu-btn"><a href="#"><i class="fa fa-bars fa-2x"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- eof header-top -->
            <script src="https://my.hellobar.com/1e3071cb5de48ba66d8585c1b6cd52c656dc170e.js" type="text/javascript" charset="utf-8" async="async"></script>