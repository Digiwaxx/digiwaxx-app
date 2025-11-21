<div class="footer">
    <div class="container">
        <h1><img class="logo_img" src="<?php echo asset('assets/img/logo.png'); ?>"></h1>
        <p class="cap1">App coming soon</p>
        <p class="cap2">
            <a href="#"><i class="fa fa-apple"></i></a>
            <a href="#"><i class="fa fa-android"></i></a>
        </p>
        <!--<p class="menu"><a href="#">WHAT IS DIGIWAXX?</a> <a href="#">WHAT WE DO</a> <a href="#">PROMOTE YOUR PROJECT</a> 
                    <a href="#">CHARTS</a> <a href="#">DIGIWAXX RADIO</a> <a href="#">CONTACT US</a> </p>-->
        <!-- <div class="menu">
                	<div class="col-lg-2 col-md-4"> <a href="<?php // echo base_url(); 
                                                                ?>">HOME</a> </div>
					<div class="col-lg-2 col-md-4"> <a href="<?php // echo base_url('WhatIsDigiwaxx'); 
                                                                ?>">WHAT IS DIGIWAXX?</a> </div>
                    <div class="col-lg-2 col-md-4"> <a href="<?php // echo base_url('PromoteYourProject'); 
                                                                ?>">PROMOTE YOUR PROJECT</a> </div>
                    <div class="col-lg-2 col-md-4"> <a href="<?php // echo base_url('Charts'); 
                                                                ?>">CHARTS</a> </div>
                    <div class="col-lg-2 col-md-4"> <a href="<?php // echo base_url('DigiwaxxRadio'); 
                                                                ?>">DIGIWAXX RADIO</a> </div>
                    <div class="col-lg-2 col-md-4"> <a href="<?php // echo base_url('Contactus'); 
                                                                ?>">CONTACT US</a> </div>
                </div>
                -->
        <div class="menu">
            <a href="<?php echo url('/'); ?>">HOME</a>
            <?php // if($_SESSION['clientPackage']>1) { 
            ?>
            <a href="<?php echo url('Client_tracks'); ?>">MY TRACKS</a>
            <?php // } else { 
            ?>
            <!--<a href="javascript:void()" data-toggle="modal" data-target="#alertModal">MY DASHBOARD</a>-->
            <?php // } 
            ?>
            <a href="<?php echo url('PromoteYourProject'); ?>">PROMOTE YOUR PROJECT</a>
            <a href="<?php echo url('Charts'); ?>">CHARTS</a>
            <a href="<?php echo url('DigiwaxxRadio'); ?>">DIGIWAXX RADIO</a>
            <a href="<?php echo url('Contactus'); ?>">CONTACT US</a>
        </div>
        <p class="social">
            <a target="_blank" href="https://twitter.com/Digiwaxx"><i class="fa fa-twitter"></i></a>
            <a target="_blank" href="https://www.facebook.com/digiwaxx"><i class="fa fa-facebook"></i></a>
            <a target="_blank" href="<?php echo url('/'); ?>"><i class="fa fa-google-plus"></i></a>
            <a target="_blank" href="https://www.instagram.com/digiwaxx"><i class="fa fa-instagram"></i></a>
            <a target="_blank" href="https://www.linkedin.com/company/digiwaxx-media"><i class="fa fa-linkedin"></i></a>
        </p>
        <p class="copy"><a href="<?php echo url('Privacy_policy'); ?>">Privacy policy</a></p>
        <p class="copy">&copy; Digiwaxx, LLC.</p>
    </div>
</div><!-- eof footer -->
</div><!-- eof wrapper -->
<div class="menu-con">
    <a href="#" class="menu-close"><i class="fa fa-close"></i></a>
    <ul>
        <li><a href="<?php echo url('/'); ?>">HOME</a></li>
        <li><a href="<?php echo url('login'); ?>">LOG IN</a></li>
        <li><a href="<?php echo url('signup'); ?>">SIGN UP</a></li>
        <?php // if($_SESSION['clientPackage']>1) { 
        ?>
        <li><a href="<?php echo url('Client_tracks'); ?>">MY TRACKS</a></li>
        <?php // } else { 
        ?>
        <!-- <li><a href="javascript:void()" data-toggle="modal" data-target="#alertModal">MY DASHBOARD</a></li>-->
        <?php // } 
        ?>
        <li><a href="<?php echo url('PromoteYourProject'); ?>">PROMOTE YOUR PROJECT</a></li>
        <li><a href="<?php echo url('Charts'); ?>">CHARTS</a></li>
        <li><a href="<?php echo url('DigiwaxxRadio'); ?>">DIGIWAXX RADIO</a></li>
        <li><a href="<?php echo url('Contactus'); ?>">CONTACT US</a></li>
        <!--<li><a href="<?php //echo url('PressPage'); ?>">PRESS</a></li>-->
        <!--<li><a href="<?php //echo url('ClientsPage'); ?>">CLIENTS</a></li>-->
        <!--<li><a href="<?php //echo url('WallOfScratch'); ?>">WALL OF SCRATCH</a></li>-->
        <li><a href="<?php echo url('WhatWeDo'); ?>">WHAT WE DO</a></li>
        <li><a href="<?php echo url('FreePromo'); ?>">FREE PROMO</a></li>
        <!--<li><a href="<?php //echo url('Events'); ?>">EVENTS</a></li>-->
        <!--<li><a href="<?php //echo url('Testimonials'); ?>">TESTIMONALS</a></li>-->
        <li><a href="<?php echo url('WhyJoin'); ?>">WHY JOIN</a></li>
        <li><a href="<?php echo url('SponsorAdvertise'); ?>">SPONSOR ADVERTISE</a></li>
        <li><a href="<?php echo url('Help'); ?>">HELP</a></li>
        <!--<li class="visible-xs hidden-sm hidden-md hidden-lg"><a href="< ?php echo url('Help'); ?>"><span>HELP</span></a></li>-->
        <?php if (!(isset($_SESSION['clientId'])) && !(isset($_SESSION['memberId']))) { ?>
            <li class="visible-xs hidden-sm hidden-md hidden-lg"><a href="<?php echo url('Login'); ?>"><span>LOG IN</span></a></li>
            <li class="visible-xs hidden-sm hidden-md hidden-lg"><a href="<?php echo url('Signup'); ?>"><span>SIGN UP</span></a></li>
        <?php } ?>
    </ul>
</div>
<!--popup-->
<!-- Modal -->
<div id="alertModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#b32f85;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!--<h4 class="modal-title">Upgrade Subscription to access more features</h4>-->
            </div>
            <div class="modal-body" style="background:#FFFFFF; padding:15px !important;">
                <div class="row dso-sec" style="margin:0px; width:auto;">
                    <div class="dso-sec center-block">
                        <h2>What type of services do you want?</h2>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="dso-item">
                                    <div class="price clearfix">
                                        <div class="dlr pull-left">$</div>
                                        <div class="amt pull-left">500</div>
                                        <div class="txt pull-left">1-time fee /per track</div>
                                    </div><!-- eof price -->
                                    <h3>BASIC</h3>
                                    <ul>
                                        <li>Music/Content serviced to our Global Member Network (email & social blasts)</li>
                                        <li>Content posted on Digiwaxx Database</li>
                                        <li>Account Profile</li>
                                        <li class="dull">Analytics & Data Report Access </li>
                                        <li class="dull">Messenger Chat Communication</li>
                                    </ul>
                                </div><!-- dso-item -->
                                <div class="sub-btn">
                                    <?php


                                    if (Session::get('clientPackage') == 1) {
                                    ?>
                                        <input type="submit" name="basic" value="BUY" disabled="disabled" style="background:#ebebeb !important" /> <?php

                                                                                                                                                } else {
                                                                                                                                                    ?>
                                        <form action="<?php echo url("Client_payment1"); ?>" method="post">
                                            <input type="submit" name="basic" value="BUY" />
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="dso-item bg">
                                    <div class="price clearfix">
                                        <div class="dlr pull-left">$</div>
                                        <div class="amt pull-left">750</div>
                                        <div class="txt pull-left">1-time fee /per track</div>
                                    </div><!-- eof price -->
                                    <h3>ADVANCED</h3>
                                    <ul>
                                        <li>Music/Content serviced to our Global Member Network (email & social blasts)</li>
                                        <li>Content posted on Digiwaxx Database</li>
                                        <li>Account Profile</li>
                                        <li>Analytics & Data Report Access </li>
                                        <li>Messenger Chat Communication</li>
                                    </ul>
                                </div><!-- dso-item -->
                                <div class="sub-btn">
                                    <form action="<?php echo url("Client_payment1"); ?>" method="post">
                                        <input type="submit" name="advanced" value="BUY" />
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="dso-item">
                                    <h3>ADDITIONAL SERVICES</h3>
                                    <ul>
                                        <li>SPINWORLD Promotion</li>
                                    </ul>
                                    <p class="cnt">Contact <a href="#">business@digiwaxx.com</a> to inquire about our additional services</p>
                                </div><!-- dso-item -->
                                <div class="sub-btn">
                                    <a href="<?php echo 'http://www.digiwaxxmedia.com/contact'; ?>">CONTACT</a>
                                </div>
                            </div>
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
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<!-- Include all compiled plugins (below), or include individual files as needed -->

<link href="<?php echo asset('assets/css/search.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo asset('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap-select.css'); ?>">
<script src="<?php echo asset('assets/js/jquery.validate.min.js'); ?>"></script>
    
<script src="<?php echo asset('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo asset('assets/js/menu.js'); ?>"></script>
<script src="<?php echo asset('assets/js/TMSearch.js'); ?>"></script>
<script src="<?php echo asset('assets/js/bootstrap-select.js'); ?>"></script>
<!--<script>
		$(document).ready(function(){
			$(".menu-btn").click(function(){
					$(".menu-con").css("display","block");
					$(".menu-con").animate({right:'0px'});
			});						  
						
			$(".menu-close").click(function(){
					$(".menu-con").css("display","none");
					$(".menu-con").animate({right:'-400px'});
		  	});						  
		});
	</script>-->
<script>
    function getCharts(pid, type, divId) {
        $.ajax({
            url: "Charts?page=" + pid + "&type=" + type + "&divId=" + divId,
            success: function(result) {
                document.getElementById(divId).innerHTML = result;
            }
        });
    }
</script>
</body>
</html>
<!-- Google reCaptcha -->
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
<!--browser notifications-->
<script type="text/javascript">
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
    function sendNofitication() {
        /*$.ajax({url: "Notifications?tid=1", success: function(result){   
 
   var obj = JSON.parse(result);
   var len = obj.length;
   
   for(var i = 0; i < len; i++) {
	
	  var title=obj[i].title;
	  var desc=obj[i].artist;
	  var url='http://www.digiwaxx.io/assets/img/play-btn.png'; // obj[i].thumb;
	  var thumb='http://www.digiwaxx.io/assets/img/play-btn.png'; // obj[i].thumb;
	  
 
  notifyBrowser(title,desc,url,thumb);
  }
	  }});*/
    }
    document.addEventListener('DOMContentLoaded', function() {
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
        $.ajax({
            url: "Notifications?tid=1",
            success: function(result) {
                var obj = JSON.parse(result);
                var len = obj.length;
                for (var i = 0; i < len; i++) {
                    var title = obj[i].title;
                    var desc = obj[i].artist;
                    var url = 'http://www.digiwaxx.io/assets/img/play-btn.png'; // obj[i].thumb;
                    var thumb = 'http://www.digiwaxx.io/assets/img/play-btn.png'; // obj[i].thumb;
                    notifyBrowser(title, desc, url, thumb);
                }
            }
        });
        /*
        document.querySelector("#notificationButton").addEventListener("click", function(e)
        {
        var x = Math.floor((Math.random() * 10) + 1);
        var title=articles[x][0];
        var desc='Most popular article.';
        var url=articles[x][1];
        notifyBrowser(title,desc,url);
        e.preventDefault();
        });*/
    });
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
	
	var CaptchaCallback = function(){
    $('.g-recaptcha').each(function(index, el) {
        grecaptcha.render(el, {'sitekey' : '6LdheJIbAAAAAO2Cx77TBdguQpe-O6AsYLbj7rhd'});
        });
    };
</script>