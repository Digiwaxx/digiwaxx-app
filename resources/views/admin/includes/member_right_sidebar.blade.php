<div class="ntf-block" style="display:block;">
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
                                                <a href="javascript:void()" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php  echo $var5; ?>','<?php  echo $track->id; ?>')">
                                                    <img src="<?php echo $src; ?>"  style="height:132px;" class="img-responsive">
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
                                                        <!--<span class="dloads"><?php //echo $trackData[$track->id]['downloads']; ?></span> -->
                                                        <!--<span class="dloadst">downloads</span>-->
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
							
							<div class="ntf-block" style="display:block; margin-top:-10px; margin-bottom:-10px;">
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
										
										if($versions[$track->id]['numRows']>0)
										{
									    
										$var1 = urldecode($track->title);
										$var2 = urldecode($track->artist);
										$var3 = asset("AUDIO/".$versions[$track->id]['data'][0]->location);
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
                                                <a href="javascript:void()" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php  echo $var5; ?>','<?php  echo $track->id; ?>')">
                                                    <img src="<?php echo $src; ?>" width="108" height="108" style="height:138px;" class="img-responsive">
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
                                                        <!--<span class="dloads"><?php //echo $trackData[$track->id]['downloads']; ?></span> -->
                                                        <!--<span class="dloadst">downloads</span>-->
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
                           