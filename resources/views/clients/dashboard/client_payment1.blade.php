@extends('layouts.client_dashboard')

@section('content')
<style>
    #client-onetime .dso-item h3 {
    	font-size: 18px;
    }
    #client-onetime .dso-item .price .amt{
        font-style:normal;
        background: #9f1f63;
        color: #fff;
        margin-left: -15px;
        margin-right: -15px;
        padding: 15px 0;
    }
    #client-onetime .dso-item .price{
        margin-left:0;
    }
    #client-onetime .dso-item ul li, #artist-subscription .dso-item ul li{
    	font-size: 13px;
    	margin-bottom: 8px;
    	line-height: 18px;
    	background-size:7px;
    }
    #client-onetime .dso-item, #artist-subscription .dso-item{
    	margin-left: 0;
    	height: 100%;
    	display: flex;
    	flex-direction: column;
    	border-radius:10px;
    	background: #fff;
    }
    #client-onetime, #artist-subscription{
        padding: 0 15px;
        display: flex;
    }
    .dso-sec #client-onetime .sub-btn{
        margin-top: auto;
    }
    .dso-sec  #artist-subscription .sub-btn{
        margin-top: auto;
        margin-bottom:20px;
    }
    #client-onetime .dso-item .sub-btn input, #artist-subscription .dso-item .sub-btn input{
        background: #fff;
        border: 1px solid #9f1f63;
        color: #9f1f63;
    }
    #client-onetime .dso-item .sub-btn input:hover,
    #client-onetime .dso-item .sub-btn input:focus,
    #artist-subscription .dso-item .sub-btn input:hover,
    #artist-subscription .dso-item .sub-btn input:focus{
        background: #9f1f63;
        border: 1px solid #9f1f63;
        color: #fff;
    }
    .dso-sec-alt{
        background: transparent;
    }
    #artist-subscription .badge{
        padding: 10px 20px;
    }
    #artist-subscription .badge-silver{
        background: #e0e0e0;
        color: #000;
    }
    #artist-subscription .badge-gold{
        background: #f8de76;
        color: #915c08;
    }
    #artist-subscription .badge-platinum{
         background: #f9ad77;
        color: #892607;
    }
    #artist-subscription .badge-purple{
         background: #804296;
        color: #fff;
    }
</style>

 <section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		 <div class="container">
			<div class="row">
			<div class="col-12">
            <div class="dash-heading">
                <h2>My Dashboard</h2>
              </div>
            <div class="tabs-section">

            <!-- START MIDDLE BLOCK -->

            <div class="dso-blk">

    <div class="container">
        <h1>DIGITAL WAXX <span>SERVICE OPTIONS</span></h1>
        <div class="dso-sec center-block dso-sec-alt">
            <h2 class="text-white">Onetime Payment Option</h2>
            <div class="row" id="client-onetime">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="dso-item">
                        <h3>DIGITAL WAXX SERVICE (REGULAR)</h3>
                        <h5>Packages</h5>
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
    						<?php
    						if ($packageId == 1)
    						{ ?>
                                <input type="submit" name="basic" value="BUY" disabled="disabled"
                                       style="background:#ebebeb !important"/> <?php
    
    						} else {
    							?>
                                <form action="" method="post">
                                    @csrf
                                    <input type="submit" name="basic" value="BUY"/>
                                </form>
    						<?php } ?>
                        </div>
                    </div><!-- dso-item -->

                   
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="dso-item bg">
                        <h3>DIGITAL WAXX SERVICE (BASIC)</h3>
                        <h5>Packages</h5>
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
                            <form action="" method="post">
                            @csrf
                                <input type="submit" name="regular" value="BUY"/>
                            </form>
                        </div>
                    </div><!-- dso-item -->

                    
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="dso-item">
                        <h3>DIGITAL WAXX SERVICE (ADVANCE) Regular</h3>
                        <h5> Packages</h5>
                        <div class="price clearfix">
                            <div class="amt">$750</div>
                        </div><!-- eof price -->

                        <ul>
                            <li>Digiwaxx digital record pool submission for infinite access and downloads to our members.</li>
                            <li>Digitally distribute via e-mail one promo single to our subscriber list of 50,000+ members</li>
                            <li>Include client access to <b>DJ Feedback</b> and <b>Member Contact info</b> and <b>track analytics (i.e. DJ Profile of spin location, format and geographic location of mixer or programmer, ratings and comments)</b></li>
                            <li>Release Alert posted on Social Media Accounts <b>(Twitter, Facebook, Instagram)</b></li>
                        </ul>
                         <div class="sub-btn">
                            <form action="" method="post">
                            @csrf
                                <input type="submit" name="standard" value="BUY"/>
                            </form>
                        </div>
                    </div><!-- dso-item -->

                   
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="dso-item bg">
                        <h3>DIGITAL WAXX SERVICE (ADVANCE PLUS)</h3>
                        <!--<h5>Packages</h5>-->
                        <div class="price clearfix">
                            <div class="amt">$1000</div>
                        </div><!-- eof price -->

                        <ul>
                            <li>Digiwaxx digital record pool submission for infinite access and downloads to our members.</li>
                            <li>Digitally distribute via e-mail one promo single to our subscriber list of 50,000+ members</li>
                            <li>Include client access to <b>DJ Feedback</b> and <b>Member Contact info</b> and <b>track analytics (i.e. DJ Profile of spin location, format and geographic location of mixer or 
programmer, ratings and comments)</b></li>
                            <li>Release Alert posted on Social Media Accounts <b>(Twitter, Facebook, Instagram)</b></li>
                            <li>3 Additional email blasts and social media posts exactly that will occur once every week from the initial email blast.</li>
                        </ul>
                        <div class="sub-btn">
                            <form action="" method="post">
                            @csrf
                                <input type="submit" name="advance" value="BUY"/>
                            </form>
                        </div>
                    </div><!-- dso-item -->

                    
                </div>

                <div style="clear:both;"></div>

            </div>
        </div>


    </div><!-- eof container -->
    <div class="container">
        <h1>DIGITAL WAXX <span>SERVICE OPTIONS FOR ARTISTS</span></h1>
        <div class="dso-sec center-block dso-sec-alt">
            <h2 class="text-white">Artist Subscription Membership</h2>
            <div class="row" id="artist-subscription">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="dso-item">
                        <h3 class="badge badge-silver">SILVER</h3>
                        <h5 style="color:#000;text-align:center;margin-left:0;">Packages</h5>
                        <div style="color:#000;text-align:center;margin-left:0;" class="price clearfix">
                                 <h4><i>$150/Month</i></h4>
                        </div><!-- eof price -->
                        <ul>
                            <li>1 project promotion (Added to Digiwaxx Pool, DJ feedback)</li>
                            <li>2 email blasts</li>
                            <li>2 posts on Social (Twitter, IG & Facebook)</li>
                    
                        </ul>
                        <div class="sub-btn">
    						<?php
    						if ($packageId == 1)
    						{ ?>
                                <input type="submit" name="basic" value="BUY" disabled="disabled"
                                       style="background:#ebebeb !important"/> <?php
    
    						} else {
    							?>
                                <form action="" method="post">
                                @csrf
                                    <input type="submit" name="silver-artist" value="BUY"/>
                                </form>
    						<?php } ?>
                        </div>
                    </div><!-- dso-item -->

                    
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="dso-item bg">
                        <h3 class="badge badge-gold">GOLD</h3>
                        <h5 style="color:#000;text-align:center;margin-left:0;">Packages</h5>
                        <div style="color:#000;text-align:center;margin-left:0;" class="price clearfix">
                            <h4><i>$325/Month</i></h4>
                        </div><!-- eof price -->

                        <ul>
                            <li>Up to 2 project promotion (Added to Digiwaxx Pool with Feedback from DJs)</li>
                            <li>2 email blasts per release</li>
                            <li>2 posts on Social (Twitter, IG & Facebook)</li>
                            <li>Blog post</li>
                        </ul>
                         <div class="sub-btn">
                            <form action="" method="post">
                            @csrf
                                <input type="submit" name="gold-artist" value="BUY"/>
                            </form>
                        </div>
                    </div><!-- dso-item -->

                   
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="dso-item">
                        <h3 class="badge badge-platinum">PLATINUM</h3>
                        <h5 style="color:#000;text-align:center;margin-left:0;">Packages</h5>
                        <div style="color:#000;text-align:center;margin-left:0;" class="price clearfix">
                            <h4><i>$450/Month</i></h4>
                        </div><!-- eof price -->

                        <ul>
                            <li>Up to 3 project promotion (Added to Digiwaxx Pool with Feedback)</li>
                            <li>2 email blasts per release</li>
                            <li>6 posts on Social (Twitter, IG & Facebook)</li>
                            <li>1 Blog post</li>
                            
                        </ul>
                        <div class="sub-btn">
                            <form action="" method="post">
                            @csrf
                                <input type="submit" name="purple-artist" value="BUY"/>
                            </form>
                        </div>
                    </div><!-- dso-item -->

                    
                </div>

                <div style="clear:both;"></div>

            </div>
        </div>
    </div>
    <div class="container">
        <h1>DIGITAL WAXX <span>SERVICE OPTIONS FOR LABELS</span></h1>
        <div class="dso-sec center-block dso-sec-alt">
            <h2 class="text-white">Record Label Subscription Membership</h2>
            <div class="row" id="artist-subscription">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="dso-item">
                        <h3 class="badge badge-silver">SILVER</h3>
                        <h5 style="color:black;text-align:center;">Packages</h5>
                        <div style="color:black;text-align:center;margin-left:0;" class="price clearfix">
                            <h4><i>$750/Month</i></h4>
                        </div><!-- eof price -->
                        <ul>
                            <!--<li>Up to 3 Singles (Added to Digiwaxx Pool)</li>-->
                            <li>Up to 3 project promotions (Added to Digiwaxx Pool)</li>
                            <li>2 email blasts per release</li>
                            <li>2 label email blasts (all your releases)</li>
                            <li>6 posts on Social (Twitter, IG & Facebook)</li>
                            
                        </ul>
                        <div class="sub-btn">
						<?php
						if ($packageId == 1)
						{ ?>
                            <input type="submit" name="basic" value="BUY" disabled="disabled"
                                   style="background:#ebebeb !important"/> <?php

						} else {
							?>
                            <form action="" method="post">
                            @csrf
                                <input type="submit" name="silver-label" value="BUY"/>
                            </form>
						<?php } ?>
                    </div>
                    </div><!-- dso-item -->

                    
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="dso-item bg">
                        <h3 class="badge badge-gold">GOLD</h3>
                        <h5 style="color:#000;text-align:center;">Packages</h5>
                        <div style="color:#000;text-align:center;margin-left:0;" class="price clearfix">
                            <h4><i>$1400/Month</i></h4>
                        </div><!-- eof price -->

                        <ul>
                            <!--<li>Up to 5 Singles (Added to Digiwaxx Pool)</li>-->
                            <li>Up to 5 project promotions (Added to Digiwaxx Pool)</li>
                            <li>2 email blasts per release</li>
                            <li>3 label email blasts (all your releases)</li>
                            <li>10 posts on Social (Twitter, IG & Facebook)</li>
                            <li>Blog post</li>
                        </ul>
                         <div class="sub-btn">
                        <form action="" method="post">
                        @csrf
                            <input type="submit" name="gold-label" value="BUY"/>
                        </form>
                    </div>
                    </div><!-- dso-item -->

                   
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="dso-item">
                        <h3 class="badge badge-purple">PURPLE</h3>
                        <h5 style="color:#000;text-align:center;margin-left:0;">Packages</h5>
                        <div style="color:#000;text-align:center;margin-left:0;" class="price clearfix">
                            <h4><i>$1750/Month</i></h4>
                        </div><!-- eof price -->

                        <ul>
                            <li>Up to 5 project promotions (Added to Digiwaxx Pool)</li>
                            <li>2 email blasts per release</li>
                            <li>3 label email blasts (all your releases)</li>
                            <li>10 posts on Social (Twitter, IG & Facebook)</li>
                            <li>Blog post</li>
                            <li>Facebook Ad campaign (retarget our DJs and influencers and more DJs)</li>
                            <li>Free phone one on one consultation</li>
                       
                        </ul>
                        <div class="sub-btn">
                        <form action="" method="post">
                        @csrf
                            <input type="submit" name="purple-label" value="BUY"/>
                        </form>
                    </div>
                    </div><!-- dso-item -->

                    
                </div>

                <div style="clear:both;"></div>

            </div>
        </div>
    </div>
</div><!-- eof dso-blk -->
<!-- DIGIWAXX SERVICE OPTIONS END -->

            <!-- eof middle block -->


				@include('clients.dashboard.includes.my-tracks')
				
			</div>
		</div>
	</div>
	</div>
	</div>
		
 </section>


@endsection