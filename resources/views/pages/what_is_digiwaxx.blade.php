
@include('admin.includes.header_front')
<!--<script src="<?php //echo base_url('assets/js/lazy-load.js'); ?>"></script>-->
<div class="wid header-bottom"
     style="background:url(<?php
    if(!empty($banner)){

        $banner_get = $banner[0]->banner_image;

    }
    else{

        $banner_get = '';
    }
     echo asset('assets/img/' . $banner_get); ?>) repeat scroll center top transparent">
    <div class="container">
		<?php 

            if(!empty($bannerText)){

                $banner_get_text = $bannerText[0]->bannerText;

            }
            else{

                $banner_get_text = '';
            }
        echo $banner_get_text; ?>
		
		  <div class="control-group text-center" style="align:center; padding-top:10px;">
                    
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_donations" />
                <input type="hidden" name="business" value="paypal@digiwaxx.com" />
                <input type="hidden" name="item_name" value="Digiwaxx" />
                <input type="hidden" name="currency_code" value="USD" />
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                </form>
                </div>
    </div>
</div><!-- eof header-bottom -->
</div><!-- eof header -->
<div class="con-block-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="head-block1">
                    <a href="<?php
                     if(!empty($pageLinks)){

                        $pagelinks_get = $pageLinks[2]->linkHref;
        
                    }
                    else{
        
                        $pagelinks_get = '';
                    }
                    echo $pagelinks_get; ?>">I'm a DJ
                        <br/> <span>I want to receive exclusive music and more</span></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="head-block1"><a href="<?php 
                 if(!empty($pageLinks)){

                    $pagelinks_get1 = $pageLinks[3]->linkHref;
    
                }
                else{
    
                    $pagelinks_get1 = '';
                }
                echo $pagelinks_get1; ?>">I'm an Artist
                        <br/> <span>I want to promote my music now</span></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="head-block1"><a href="<?php
                
                if(!empty($pageLinks)){

                    $pagelinks_get2 = $pageLinks[4]->linkHref;
    
                }
                else{
    
                    $pagelinks_get2 = '';
                }

                echo $pagelinks_get2; ?>">I'm a Brand
                        <br/> <span>I want to make my brand cooler</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(1){ ?>
<div class="con-block-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 youtubediv"> <!--youtubediv-->
                <div class="youtube-block youtube-block-sg top-downloads">
                    <h1>Featured Video</h1>
                    <!--<img src="https://img.youtube.com/vi/<?php //echo $youtube[0]->youtube; ?>/0.jpg" async class="play-youtube-video">-->
                    
                    <!--<img class="youtube-btn" src="<?php //echo base_url('assets/images/youtube-icon.png'); ?>">-->
                    
                    <iframe width="100%" height="315"
                            src="https://youtube.com/embed/<?php
                             if(!empty($youtube)){

                                $youtube_get = $youtube[0]->youtube;
                               
                    
                            }
                            else{
                    
                                $youtube_get = '';
                               
                            }    
                            echo $youtube_get; ?>"></iframe>
                            
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 tweetdiv">
                <div class="youtube-block top-downloads sg-tweetdiv">
                    <h1>Twitter Feeds</h1>
                    <a class="twitter-timeline" data-width="100%" data-height="315"
                       href="https://twitter.com/Digiwaxx?ref_src=twsrc%5Etfw">Tweets by Digiwaxx</a>
                    
                </div>
            </div>
        </div>
    </div>
</div><!-- eof con-block-1 -->
<?php } ?>




<div class="tcb-product-slider top-downloads" style="margin-bottom:30px;">

    <div class="container">
        <div class="row" style="margin-bottom:20px;">
            <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-xs-6 col-xs-offset-3"><h1>New
                    Releases</h1></div>
        </div>

        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="row">
						<?php

                        if(!empty($newest_tracks1)){


                            //echo '<pre/>';print_r($newest_tracks1['data']);die('##');
						foreach ($newest_tracks1['data'] as $track)
						{
							if (strlen($track->thumb) > 4)
							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)
							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else
							{
								$img = asset('assets/img/tdpic1.jpg');
							}
							?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <div class="tdpic">
                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">
                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">
                                        <span class="overlay"></span>
                                        <span class="overlay-text">
                                    <span class="album"><?php

										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>
                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php // echo $track->downloads; // print_r($downloads['downloadsData'][$track->mp3Id][0]->downloads);
										?></span>
                                    <span class="dloadst"></span>
                                </span>
                                    </a>
                                </div>
                            </div>
						<?php } 
              
                        }?>
						
                    </div>

                </div>

                <div class="item">

                    <div class="row">

						<?php
                         if(!empty($newest_tracks2)){
						foreach ($newest_tracks2['data'] as $track)
						{
							if (strlen($track->thumb) > 4)
							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)
							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else
							{
								$img = asset('assets/img/tdpic1.jpg');
							}
							?>


                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <div class="tdpic">
                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">

                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">
                                        <span class="overlay"></span>
                                        <span class="overlay-text">
                                    <span class="album"><?php

										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>
                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php // echo $track->downloads; // print_r($downloads['downloadsData'][$track->mp3Id][0]->downloads);
										?></span>

                                    <span class="dloadst"></span>
                                </span>
                                    </a>
                                </div>
                            </div>
						<?php }
                        } ?>

                    </div>

                </div>

                <div class="item">

                    <div class="row">

						<?php
                         if(!empty($newest_tracks3)){
						foreach ($newest_tracks3['data'] as $track)
						{

							if (strlen($track->thumb) > 4)

							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)

							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else

							{
								$img = asset('assets/img/tdpic1.jpg');
							}

							?>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <div class="tdpic">
                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">

                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">
                                        <span class="overlay"></span>
                                        <span class="overlay-text">
                                    <span class="album"><?php
										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>

                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php // echo $track->downloads; // print_r($downloads['downloadsData'][$track->mp3Id][0]->downloads);
										?></span>
                                    <span class="dloadst"></span>

                                </span>

                                    </a>
                                </div>
                            </div>
						<?php } 
                        } ?>
                    </div>
                </div>
            </div>

            <!-- Controls -->

            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<!-- top downloads of the week -->
<div class="tcb-product-slider top-downloads" style="margin-bottom:30px;">

    <div class="container">
        <div class="row" style="margin-bottom:20px;">
            <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-xs-6 col-xs-offset-3"><h1>This
                    week, Top Downloads</h1></div>
        </div>

        <div id="carousel-example-generic2" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">

                <div class="item active">
                    <div class="row">
						<?php
                         if(!empty($downloads1)){
						foreach ($downloads1['data'] as $track)
						{

							// print_r($track); echo '<br ><br >';

							if (strlen($track->thumb) > 4)
							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)
							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else
							{
								$img = asset('assets/img/tdpic1.jpg');
							}
							?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <div class="tdpic">
                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">
                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">
                                        <span class="overlay"></span>
                                        <span class="overlay-text">
                                    <span class="album"><?php
										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title;

										?></span>
                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php echo $track->trackdownloads; ?></span>
                                    <span class="dloadst"></span>
                                </span>
                                    </a>
                                </div>
                            </div>
						<?php } 
                    }?>
                    </div>

                </div>


				<?php 
                 if(!empty($downloads2)){
                if ($downloads2['numRows'] > 0) { ?>
                    <div class="item">

                        <div class="row">

							<?php
                             
							foreach ($downloads2['data'] as $track)
							{
								if (strlen($track->thumb) > 4)
								{
									$img = asset('thumbs/' . $track->thumb);
								} else if (strlen($track->imgpage) > 4)
								{
									$img = asset('ImagesUp/' . $track->imgpage);
								} else
								{
									$img = asset('assets/img/tdpic1.jpg');
								}
								?>


                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">

                                    <div class="tdpic">

                                        <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">

                                            <img src="<?php echo $img; ?>" class="img-responsive"
                                                 style="width:100%; height:100%; max-height:262.5px;">

                                            <span class="overlay"></span>

                                            <span class="overlay-text">

                                    <span class="album"><?php

										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>

                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>

                                    <span class="dloads"><?php echo $track->trackdownloads; ?></span>

                                    <span class="dloadst"></span>

                                </span>

                                        </a>

                                    </div>

                                </div>

							<?php } 
                            
                            ?>
                        </div>
                    </div>
				<?php }
                 }
                 ?>

				<?php
                  if(!empty($downloads3)){
                if ($downloads3['numRows'] > 0) { ?>
                    <div class="item">
                        <div class="row">
							<?php

                          
							foreach ($downloads3['data'] as $track)
							{
								if (strlen($track->thumb) > 4)
								{
									$img = asset('thumbs/' . $track->thumb);
								} else if (strlen($track->imgpage) > 4)
								{
									$img = asset('ImagesUp/' . $track->imgpage);
								} else
								{
									$img = asset('assets/img/tdpic1.jpg');
								}
								?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                    <div class="tdpic">
                                        <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">
                                            <img src="<?php echo $img; ?>" class="img-responsive"
                                                 style="width:100%; height:100%; max-height:262.5px;">
                                            <span class="overlay"></span>
                                            <span class="overlay-text">
                                    <span class="album"><?php
										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>
                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php echo $track->trackdownloads; ?></span>
                                    <span class="dloadst"></span>
                                </span>
                                        </a>
                                    </div>
                                </div>
							<?php }
                             ?>

                        </div>

                    </div>
				<?php } 
                } ?>

            </div>
            <!-- Controls -->
			<?php // if(isset($downloads3['numRows']) && $downloads3['numRows']>0) { ?>

            <a class="left carousel-control" href="#carousel-example-generic2" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="right carousel-control" href="#carousel-example-generic2" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
			<?php // } ?>

        </div>

    </div>

</div>

<!---->

<!-- top reviewed records -->
<div class="tcb-product-slider top-downloads" style="margin-bottom:30px;">

    <div class="container">
        <div class="row" style="margin-bottom:20px;">
            <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-xs-6 col-xs-offset-3"><h1>This
                    week, Top Reviews</h1></div>
        </div>

        <div id="carousel-example-generic3" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="row">
						<?php
                        if(!empty($newest_tracks1)){
						foreach ($newest_tracks1['data'] as $track)
						{
							if (strlen($track->thumb) > 4)
							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)
							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else
							{
								$img = asset('assets/img/tdpic1.jpg');
							}
							?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                <div class="tdpic">
                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">
                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">
                                        <span class="overlay"></span>
                                        <span class="overlay-text">
                                    <span class="album"><?php

										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>
                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php // echo $track->downloads; // print_r($downloads['downloadsData'][$track->mp3Id][0]->downloads);
										?></span>
                                    <span class="dloadst"></span>
                                </span>
                                    </a>
                                </div>
                            </div>
						<?php } 
                        }?>
                    </div>

                </div>

                <div class="item">

                    <div class="row">
						<?php
                         if(!empty($newest_tracks2)){
						foreach ($newest_tracks2['data'] as $track)
						{
							if (strlen($track->thumb) > 4)
							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)
							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else
							{
								$img = asset('assets/img/tdpic1.jpg');
							}
							?>


                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">

                                <div class="tdpic">

                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">

                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">

                                        <span class="overlay"></span>

                                        <span class="overlay-text">

                                    <span class="album"><?php

										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>

                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php // echo $track->downloads; // print_r($downloads['downloadsData'][$track->mp3Id][0]->downloads);
										?></span>
                                    <span class="dloadst"></span>

                                </span>
                                    </a>
                                </div>
                            </div>
						<?php } 
                        }?>

                    </div>

                </div>

                <div class="item">

                    <div class="row">

						<?php
                         if(!empty($newest_tracks3)){
						foreach ($newest_tracks3['data'] as $track)

						{
							if (strlen($track->thumb) > 4)

							{
								$img = asset('thumbs/' . $track->thumb);
							} else if (strlen($track->imgpage) > 4)

							{
								$img = asset('ImagesUp/' . $track->imgpage);
							} else

							{
								$img = asset('assets/img/tdpic1.jpg');
							}
							?>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">

                                <div class="tdpic">

                                    <a href="<?php echo url("Downloads?tid=" . $track->id); ?>">

                                        <img src="<?php echo $img; ?>" class="img-responsive"
                                             style="width:100%; height:100%; max-height:262.5px;">
                                        <span class="overlay"></span>
                                        <span class="overlay-text">
                                    <span class="album"><?php

										$title = strtoupper(str_replace(["\\", "\""], "", urldecode($track->title)));

										if (strlen($title) > 18)
										{
											$title = substr($title, 0, 18);
										}
										echo $title; ?></span>
                                    <span class="artist"><?php echo strtoupper(urldecode($track->artist)); ?></span>
                                    <span class="dloads"><?php // echo $track->downloads; // print_r($downloads['downloadsData'][$track->mp3Id][0]->downloads);
										?></span>
                                    <span class="dloadst"></span>
                                </span>
                                    </a>
                                </div>
                            </div>
						<?php } 
                        }?>
                    </div>
                </div>
            </div>
            <!-- Controls -->

            <a class="left carousel-control" href="#carousel-example-generic3" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic3" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<div class="wid rec-block">
    <div class="container">
        <h1>"Still breakin' boundaries."</h1>
    </div>
</div><!-- eof rec-block -->

<div class="con-block-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="con-block">
                    <!--<span class="pink-text">The DJ has always influenced culture. EDM, Rock, Country, Drum'N Bass, Hip-Hop, R&B, Reggae or Funk music, whatever the DJ spins captures the minds, hearts and bodies of the masses.</span> <br> Thus, building a network of DJs worldwide became an integral part of Digiwaxx's culture.--> <?php 
                    
                    if(!empty($bannerText)){

                        $banner_get_text11 = $bannerText[1]->bannerText;
        
                    }
                    else{
        
                        $banner_get_text11 = '';
                    }
                    echo $banner_get_text11; ?></div>
            </div>
        </div>
    </div>
</div><!-- eof con-block-1 -->

<div class="con-block-2">
    <h1>Connect with us.</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                <div class="head-block"><a
                            href="<?php 
                             if(!empty($pageLinks)){

                                $pagelinks_get6 = $pageLinks[0]->linkHref;
                                $pagelinks_linkLabel1 = $pageLinks[0]->linkLabel;
                
                            }
                            else{
                
                                $pagelinks_get6 = '';
                                $pagelinks_linkLabel1 = '';
                            }
                            
                            echo $pagelinks_get6; ?>"><?php echo $pagelinks_linkLabel1; ?></a>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                <div class="head-block"><a
                            href="<?php 

                             if(!empty($pageLinks)){

                                $pagelinks_get7 = $pageLinks[1]->linkHref;
                                $pagelinks_linkLabel2 = $pageLinks[1]->linkLabel;
                
                            }
                            else{
                
                                $pagelinks_get7 = '';
                                $pagelinks_linkLabel2 = '';
                            }

                            echo $pagelinks_get7 ?>"><?php echo $pagelinks_linkLabel2; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="con-block-2 dupdates">

    <div class="container mailchimp_div">
        <div class="row">
            <!--<div class="col-lg-4 col-lg-offset-2 col-md-4 col-md-offset-2 col-sm-5 col-sm-offset-1">-->
            <!--    <div class="d-form1">-->
            <!--        <label>DIGIWAXX UPDATES</label>-->
            <!--    </div>-->
            <!--</div>-->

            <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12">
                <div class="d-form2 psgy-5">
                    <label class="text-center">DIGIWAXX UPDATES</label>
                    <!--<input class="text" id="subscriberEmail" placeholder="Subscribe Newsletter"-->
                    <!--       onclick="changeSubscribeStaus()" type="text">-->

                    <!--<a href="javascript:void()" class="btn1" onclick="subscribeNewsletter()"><i-->
                    <!--            class="fa fa-envelope"></i></a>-->

                    <!--<div style="clear:both;"></div>-->
                    <!--<span id="subscribeResponse"></span>-->
                    
                    
                    <!-- Begin Mailchimp Signup Form -->
                    <!--<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">-->
                    <div id="mc_embed_signup">
                    <form action="https://digiwaxx.us1.list-manage.com/subscribe/post?u=e325f3a65ed75749cc95845d3&amp;id=4666fef1b4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                    	
                    <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
                    <div class="mc-field-group">
                    	<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
                    </label>
                    	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                    </div>
                    <div class="mc-field-group">
                    	<label for="mce-FNAME">Name  <span class="asterisk">*</span>
                    </label>
                    	<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
                    </div>
                    	<div id="mce-responses" class="clear">
                    		<div class="response" id="mce-error-response" style="display:none"></div>
                    		<div class="response" id="mce-success-response" style="display:none"></div>
                    	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_e325f3a65ed75749cc95845d3_4666fef1b4" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                        </div>
                    </form>
                    </div>
                    <!--<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>-->
                    <script type='text/javascript' src='<?php echo asset('assets/js/mc-validate.js'); ?>'></script>
                    <script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[10]='ALIAS';ftypes[10]='text';fnames[3]='COMPANY';ftypes[3]='text';fnames[4]='TWITTER';ftypes[4]='url';fnames[2]='MMERGE2';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                    <!--End mc_embed_signup-->
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="calendly">
        <p>IF YOU ARE SEEKING HONEST FEEDBACK, IDEAS ON HOW TO GAIN MORE FANS, A COURSE OF DIRECTION AND NEED A SOLID PLAN OF ACTION WE WILL HELP YOU. </p>
        <p><a href="https://calendly.com/digiwaxx" target="_blank">CLICK HERE TO SET UP A CONSULTATION WITH US NOW!</a></p>
    </div>

<script>
    $(document).ready(function () {
        $('#myCarousel').carousel({
            interval: 10000
        })

    });
    function changeSubscribeStaus() {
        document.getElementById('subscribeResponse').innerHTML = '';
    }

    function subscribeNewsletter() {
        var email = document.getElementById('subscriberEmail').value;
        var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;

        if (email.match(emailExp)) {
            $.ajax({
                url: "Subscribe?email=" + email, success: function (result) {
                    var json = $.parseJSON(result);

                    document.getElementById('subscriberEmail').value = '';
                    document.getElementById('subscribeResponse').innerHTML = json.message;

                }
            });
        } else {
            document.getElementById('subscribeResponse').innerHTML = 'Enter a valid email!';
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
        
        $('.play-youtube-video,.youtube-btn').on('click', function(){
            $('.play-youtube-video,.youtube-btn').hide();
        $('.youtube-block-sg').append('<iframe width="100%" height="315" src="https://youtube.com/embed/<?php
        
        if(!empty($youtube)){

            $youtube_get = $youtube[0]->youtube;
           

        }
        else{

            $youtube_get = '';
           
        }
        
        echo $youtube_get; ?>?autoplay=1"></iframe>');
        });
        setTimeout(function(){ $('.sg-tweetdiv').append('<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"><\/script>'); }, 1000);
    
    });
    


// 	$("img").lazyload({
// 	    effect : "fadeIn"
// 	});
</script>
@include('admin.includes.footer_front')