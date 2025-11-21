<div class="client-block">

       

       		       <!-- MY RECORD POOL MENU, NOTIFICATIONS AND MY TRACKS FOR SMALLER DEVICES -->

       

       		<div class="container">

            	<div class="row">

                	<div class="hidden-lg col-md-6 col-sm-6 col-xs-6">

                    	<div class="mrp-sec">MY RECORD POOL</div>

                        

                        <div class="mrp-con">

                               <?php
                                // include("member_left_menu_mobile.php");
                                 ?>
                                   @include('admin.includes.member_left_menu_mobile')



                        </div><!-- eof mrp-con -->

                    </div><!-- eof mrp col -->

                    

                    

                    

                    

                    <div class="hidden-lg col-md-6 col-sm-6 col-xs-6">

                    	<div class="ntf-blk" style="display:block;">

                            <div class="ntf-sec"><i class="fa fa-flag"></i>Just For You</div>

    

                            <div class="ntf-con">

							

									<div class="ntf-block jfy-sec" style="display:block;">

                            	<h1>STAFF PICKS</h1>

                             <!--   <div class="ntf-lst-blk">-->

								

								<?php if($staffTracks['numRows']>0) { ?>    

                             <div class="stpk-blk ntf-lst-blk" style="padding:10px;">

                             	

                                

                                <div class="stpk-con">

                                	<div class="row">

									

									  <?php 

							$i=1;

							foreach($staffTracks['data'] as $track) 

							 { 		

							 

							 if($i<3) {

										if($reviews[$track->id]>0)

										{

										 $href =  url("Member_track_download?tid=".$track->id);

										 $label = 'DOWNLOAD';

										 

										}

										else

										{

										 $href =  url("Member_track_review?tid=".$track->id);

										 $label = 'LEAVE REVIEW TO UNLOCK DOWNLOAD';

										}

										

										

										if($mp3s[$track->id]['numRows']>0)

										{

									    

										$var1 = urldecode($track->title);

										$var2 = urldecode($track->artist);

										$var3 = asset("AUDIO/".$mp3s[$track->id]['data'][0]->location);

										$var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

										$var5 = asset("ImagesUp/".$track->imgpage);

										

											

										} else { 

										

										$var1 = urldecode($track->title);

										$var2 = urldecode($track->artist);

										$var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';

										$var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

										$var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';

										

										

										

										

										

										}

										

										

										

										if(strlen($track->thumb)>4)

										{

										

										$src = asset('thumbs/'.$track->thumb);

										}

										else

										{

										 $src = asset('ImagesUp/'.$track->imgpage);

										 

										 }

										

										

										 ?>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                            <div class="tdpic">

                                                <a href="javascript:void()" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php  echo $var5; ?>','<?php echo $track->id; ?>')">

                                                    <img src="<?php echo $src; ?>"  style="height:120px;" class="img-responsive">

                                                    <span class="overlay"></span>

                                                    <span class="overlay-text">

                                                        <span class="album"><?php 

														$album = strtoupper(urldecode($track->album));  

														if(strlen($album)>13)

														{

														  $album = substr($album,0,13); 

														}

														echo $album;

														

														?></span> 

                                                        <span class="artist"><?php 

														$artist = strtoupper(urldecode($track->artist));  

														if(strlen($artist)>13)

														{

														  $artist = substr($artist,0,13); 

														}

														echo $artist;

														?></span> 

                                                        <span class="dloads"><?php echo $trackData[$track->id]['downloads']; ?></span> 

                                                        <span class="dloadst">dloads</span>

                                                    </span>

                                                </a>



                                                <a href="<?php echo $href; ?>" class="dwd"><i class="fa fa-cloud-download"></i></a>                                                

                                            </div>

                                        </div>

										<?php } $i++; } ?>

                                        

                                    </div><!-- eof row -->

                                </div><!-- eof stpk-con -->

                                

                                <div class="smore text-right"><a href="<?php echo url("member_staff_picks"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a></div>

                             </div><!-- eof stpk-blk -->

							 <?php } ?>



                         

                                    

                              <!--  </div>-->

                            </div><!-- eof ntf-block-->

							

									<div class="ntf-block jfy-sec" style="display:block; margin-top:-10px; margin-bottom:-10px;">

                            	<h1>SELECTED FOR YOU</h1>

                             <!--   <div class="ntf-lst-blk">-->

							 <?php if($youTracks['numRows']>0) { ?>    

						    <div class="stpk-blk ntf-lst-blk" style="padding:10px;">

                             

                                

                                <div class="stpk-con">

                                	<div class="row">

                                         <?php 

							$i=1;

							foreach($youTracks['data'] as $track) 

							 {  

							 if($i<3) {

							 if($reviews[$track->id]>0)

										{

										 $href =  url("Member_track_download?tid=".$track->id);

										 $label = 'DOWNLOAD';

										 

										}

										else

										{

										 $href =  url("Member_track_review?tid=".$track->id);

										 $label = 'LEAVE REVIEW TO UNLOCK DOWNLOAD';

										}

										
                                        if(!empty($mp3s[$track->id]['numRows'])){
										if($mp3s[$track->id]['numRows']>0)

										{

									    

										$var1 = urldecode($track->title);

										$var2 = urldecode($track->artist);

										$var3 = asset("AUDIO/".$mp3s[$track->id]['data'][0]->location);

										$var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

										$var5 = asset("ImagesUp/".$track->imgpage);

										

											

										} 
                                        
                                    }else { 

										

										$var1 = urldecode($track->title);

										$var2 = urldecode($track->artist);

										$var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';

										$var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

										$var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';

										}

										 ?>

                            

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                            <div class="tdpic">

                                                <a href="javascript:void()" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php  echo $var5; ?>','<?php echo $track->id; ?>')">

                                                    <img src="<?php echo asset('ImagesUp/'.$track->imgpage); ?>" width="108" height="108" style="height:138px;" class="img-responsive">

                                                    <span class="overlay"></span>

                                                    <span class="overlay-text">

                                                        <span class="album"><?php 

														$album = strtoupper(urldecode($track->album));  

														if(strlen($album)>13)

														{

														  $album = substr($album,0,13); 

														}

														echo $album;

														

														?></span> 

                                                        <span class="artist"><?php 

														$artist = strtoupper(urldecode($track->artist));  

														if(strlen($artist)>13)

														{

														  $artist = substr($artist,0,13); 

														}

														echo $artist;

														?></span> 

                                                        <span class="dloads"><?php echo $trackData[$track->id]['downloads']; ?></span> 

                                                        <span class="dloadst">dloads</span>

                                                    </span>

                                                </a>



                                                <a href="<?php echo $href; ?>" class="dwd"><i class="fa fa-cloud-download"></i></a>                                                

                                            </div>

                                        </div>

                                        <?php } $i++; } ?>

                                        

                                    </div><!-- eof row -->

                                </div><!-- eof stpk-con -->

                                

                                <div class="smore text-right"><a href="<?php echo url("member_selected_for_you"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a></div>

                             </div>

							 <?php } ?>

                             <!--  </div>-->

                             </div><!--right selected for you-->

    

                            

                            </div><!-- eof ntf-con -->

                        

                        </div><!-- eof ntf-blk -->



                    </div><!-- eof ntf col -->

                    

                    

                    

                    

                    

                    

                    <div class="hidden-lg col-md-6 col-sm-6 col-xs-6">

                    	<div class="mts-blk" style="display:none;">

                            <div class="mts-sec"><i class="fa fa-music"></i>MY TRACKS</div>

    

                            <div class="mts-con">

                            

                                    <div class="mts-lst-blk">

                                      <ul class="mts-list">

                                        <li class="trk">

                                        	<p class="atst">Puff Daddy & The Family ft. Pharrell</p>

                                            <p class="alb">Finna Get Loose</p>

                                            <p class="up-dt">2015/10/20</p>

                                            <p class="rlb">Bad Boy Records</p>

                                            <div class="clearfix st-blk">

                                            	<div class="st">

                                                	<i class="fa fa-star"></i>

                                                    <span>4</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-cloud-download"></i>

                                                    <span>21</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-comment"></i>

                                                    <span>2</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-play-circle"></i>

                                                    <span>31</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-share-alt"></i>

                                                    <span>3</span>

                                                </div>                                               

                                                

                                            </div><!-- eof st-blk -->

                                        </li><!-- eof trk -->

                                        

                                        

                                        

                                        <li class="trk">

                                        	<p class="atst">Chronixx</p>

                                            <p class="alb">Eternal Fire</p>

                                            <p class="up-dt">2015/10/20</p>

                                            <p class="rlb">Zinc Fence Records</p>

                                            <div class="clearfix st-blk">

                                            	<div class="st">

                                                	<i class="fa fa-star"></i>

                                                    <span>4</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-cloud-download"></i>

                                                    <span>21</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-comment"></i>

                                                    <span>2</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-play-circle"></i>

                                                    <span>31</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-share-alt"></i>

                                                    <span>3</span>

                                                </div>                                               

                                                

                                            </div><!-- eof st-blk -->

                                        </li><!-- eof trk -->

                                        

                                        

                                        

                                        <li class="trk">

                                        	<p class="atst">Curren$y feat. August Alsina & Lil� Wayne</p>

                                            <p class="alb">Bottom of the Bottle</p>

                                            <p class="up-dt">2015/10/20</p>

                                            <p class="rlb">Bad Boy Records</p>

                                            <div class="clearfix st-blk">

                                            	<div class="st">

                                                	<i class="fa fa-star"></i>

                                                    <span>4</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-cloud-download"></i>

                                                    <span>21</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-comment"></i>

                                                    <span>2</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-play-circle"></i>

                                                    <span>31</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-share-alt"></i>

                                                    <span>3</span>

                                                </div>                                               

                                                

                                            </div><!-- eof st-blk -->

                                        </li><!-- eof trk -->

                                        

                                        

                                        <li class="trk">

                                        	<p class="atst">Puff Daddy & The Family ft. Pharrell</p>

                                            <p class="alb">Finna Get Loose</p>

                                            <p class="up-dt">2015/10/20</p>

                                            <p class="rlb">Bad Boy Records</p>

                                            <div class="clearfix st-blk">

                                            	<div class="st">

                                                	<i class="fa fa-star"></i>

                                                    <span>4</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-cloud-download"></i>

                                                    <span>21</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-comment"></i>

                                                    <span>2</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-play-circle"></i>

                                                    <span>31</span>

                                                </div>

                                                

                                                <div class="st">

                                                	<i class="fa fa-share-alt"></i>

                                                    <span>3</span>

                                                </div>                                               

                                                

                                            </div><!-- eof st-blk -->

                                        </li><!-- eof trk -->

                                        

                                        

                                        

                                        

                                        

                                        

                                      </ul>

                                    </div>

    

                            

                            </div><!-- eof mts-con -->

                        

                        </div><!-- eof mts-blk -->



                    </div><!-- eof mts col -->

                    



                </div><!-- eof row -->

            </div><!-- eof container -->

       

       <!-- END OF MY RECORD POOL MENU, NOTIFICATIONS AND MY TRACKS FOR SMALLER DEVICES -->

       

<!-- eof container -->

       

       

       

       

			<div class="container">

            	<div class="row">

                    <div class="col-lg-3 hidden-md hidden-sm hidden-xs">

                    	<div class="client-lb">

                        	<div class="rp-block">

                                <h1>MY RECORD POOL</h1>

								<?php 
                                // include("member_left_menu.php");
                                 ?>
                                    @include('admin.includes.member_left_menu')

							</div><!-- eof rp-block -->

                            

                            <div class="s-menu">

                                <ul>

                                    <li><a href="#">HOME</a></li>

                                    <li><a href="<?php echo url('WhatIsDigiwaxx'); ?>">WHAT IS DIGIWAXX?</a></li>

                                    <li><a href="<?php echo url('PromoteYourProject'); ?>">PROMOTE YOUR PROJECT</a></li>

                                    <li><a href="<?php echo url('Charts'); ?>">CHARTS</a></li>

                                    <li><a href="<?php echo url('DigiwaxxRadio'); ?>">DIGIWAXX RADIO</a></li>

                                    <li><a href="<?php echo url('Contactus'); ?>">CONTACT US</a></li>                                    

                                </ul>

                            </div><!-- eof s-menu -->

                            

                            <div class="s-social">

                                <a href="#"><i class="fa fa-twitter"></i></a>

                                <a href="#"><i class="fa fa-facebook"></i></a>

                                <a href="#"><i class="fa fa-google-plus"></i></a>

                                <a href="#"><i class="fa fa-instagram"></i></a>

                                <a href="#"><i class="fa fa-linkedin"></i></a>

                            </div>



                            

                        </div><!-- eof client-lb -->

                    </div><!-- eof left block -->

    