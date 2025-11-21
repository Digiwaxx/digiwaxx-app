@extends('layouts.member_dashboard')
@section('content')

<section class="main-dash">
    @include('layouts.include.sidebar-left')
    <div class="dash-container">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="dash-heading">
                        <?php if (isset($alert_class)) { ?>
                            <div class="<?php echo $alert_class; ?>">
                                <p><?php echo $alert_message; ?></p>
                            </div>
                        <?php } ?>
                        <h2>MANAGE SUBSCRIPTIONS</h2>
                    </div>
                  </div>
          </div>
                    <?php
                    //   echo '<pre>';
                    // print_r($alert_class);
                    // die;
    
                    ?>
                    
                    <div class="plan-section">
                        <?php if(!empty($class)){?>
                            <div class=" <?php  echo $class;?>">
                                  <?php  echo $result;?>
                                </div>
                        <?php } ?>
                    </div>
                        <div class="row">
                            
                            
                             <?php
                                           foreach($package_details as $package){
                                            ?>
                                           
                                              <div class="col-lg-3 col-md-6 col-sm-6 pb-2">
                                               
                                                <div class="plan-box additional">
                                                    <form method="get" action={{route('upgrade_package')}}  name="myForm" id="package_select" autocomplete="off" onSubmit="if(!confirm('Do you want to purchase the subscription?')){return false;}">
                                                   <!--@csrf-->
                                                   <input type="hidden" name="package_id" value="<?php echo $package->id;?>">
                                                      <!--<h3><?php //echo $package->package_name ?></h3>-->
                                                      <h5 style="color:#52D0F8;font-family: 'Quicksand', sans-serif;font-weight: bold;text-align: center;"><?php echo $package->package_type; ?><?php if(!empty($active_id)){if ($package->id == $active_id[0]->package_id){?><sup style="color:green;font-size:15px"><i class="fas fa-check-circle"></i></sup><?php }}?></h4>
                                                      
                                                      <div class="price clearfix">
                                                            <h4 class=price><?php if(!empty($package->package_price)){ echo '$'.$package->package_price;} ?></h4>
                                                        </div>
                                                    <?php if(!empty($active_id) && $package->id == $active_id[0]->package_id){?>
                                                         <div class="package_date_details" style="font-size:10px">
                                                          
                                                          <?php if($active_id[0]->package_start_date !='0000-00-00'){?>
                                                          <?php  $date = new DateTime($active_id[0]->package_start_date);
                                                                $result = $date->format('d M Y');?>
                                                                <p class="start date"><mark><b>STARTED ON - <?php echo $result ?></mark></b></p>
                                                         <?php }?> 
                                                         <?php if($active_id[0]->package_expiry_date !='0000-00-00'){?>
                                                         <?php  $date = new DateTime($active_id[0]->package_expiry_date);
                                                             
                                                                $result1 = $date->format('d M Y');?>
                                                          <p class="end date"><mark><b>EXPIRY DATE - <?php echo $result1 ?></b></mark></p>
                                                   <?php }?>
                                                      </div>
                                                      <?php }?>
                                                      <ul>
                                                          <?php $arr=json_decode($package->package_features);?>
                                                          <?php foreach($arr as $feature){ ?>
                                                        <li><?php echo $feature; ?></li>
                                                        <?php } ?>
                                                      </ul>
                                                      <?php if(!empty($active_id)){
                                                                  if ($package->id != $active_id[0]->package_id && $package->id==7){?>
                                                                      <div class="btn-plan">
                                                                        <button class="btn btn-theme btn-gradient" type="submit"><?php echo $package->button;?></button>
                                                                      </div>
                                                          <?php  }}else{ ?>
                                                          <?php if( $package->id==7){?>
                                                             <div class="btn-plan">
                                                                    <button class="btn btn-theme btn-gradient" type="submit">BUY NOW</button>
                                                                  </div>
                                                         <?php }  }
                                                       ?>
                                                  </form>
                                                  <?php if(!empty($active_id)){
                                                      if ($package->id != $active_id[0]->package_id && $package->id!=7){?>
                                                             <form action="upgrade_package_payment_member/checkout" method="post">
                                                                               @csrf
                                                                               <input type="hidden" name="buyId" value="<?php echo $package->id;?>" />
                                                                               <input type="hidden" name="amount" value="<?php  echo $package->package_price; ?>" />
                                                                               <!-- pk_live_5BBqzoPi5GoH5UYqZHQTwMHY  -->
                                                                                 <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                                             data-key="pk_test_51JmHVaSFsniFu3P5aDzyOj62m7QM1XZWcbqWlJQPKCM2MH6hPFoW6Zj3GNr3dF9SSxIRkxY4tODUaMqivzRt1l8e00xKfv3mc0"
                                                                                             data-name="Digiwaxx"
                                                                                                data-email="<?php echo $email;?>"
                                                                                             data-image="{{ asset('public/images/logo2.png') }}"
                                                                                             data-label="<?php echo $package->button;?>"
                                                                                            
                                                                                             data-amount="<?php echo $package->package_price*100; ?>"
                                                                                             data-locale="auto">
                                                                                     
                                                                                 </script>
                                                                   </form>
                                                  <?php }}else{?>
                                                        <?php if($package->id!=7){?>
                                                             <form action="upgrade_package_payment_member/checkout" method="post">
                                                                           @csrf
                                                                           <input type="hidden" name="buyId" value="<?php echo $package->id;?>" />
                                                                           <input type="hidden" name="amount" value="<?php  echo $package->package_price; ?>" />
                                                                           <!-- pk_live_5BBqzoPi5GoH5UYqZHQTwMHY  -->
                                                                             <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                                         data-key="pk_test_51JmHVaSFsniFu3P5aDzyOj62m7QM1XZWcbqWlJQPKCM2MH6hPFoW6Zj3GNr3dF9SSxIRkxY4tODUaMqivzRt1l8e00xKfv3mc0"
                                                                                         data-name="Digiwaxx"
                                                                                            data-email="<?php echo $email;?>"
                                                                                         data-image="{{ asset('public/images/logo2.png') }}"
                                                                                         data-label="BUY NOW"
                                                                                        
                                                                                         data-amount="<?php echo $package->package_price*100; ?>"
                                                                                         data-locale="auto">
                                                                                 
                                                                             </script>
                                                               </form>
                                                      
                                                      
                                                      
                                         <?php       }  }?>
                                                  
                                                  
                                                </div>
                                                
                                               </div>
                                      
                                          <?php } ?>
                        </div>
                    </div>    
                 

           
                    <!---tab section end--->
                    <!--album-download-->
                    <div class="album-d-sec">
                        <div class="heading-border">
                            <h4>STAFF PICKS</h4>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <!--download tracks-->

                                <?php if ($staffTracks['numRows'] > 0) { ?>
                                    <div class="stpk-blk ntf-lst-blk">
                                        <div class="stpk-con">
                                            <div class="row">
                                                <?php
                                                $i = 1;
                                                foreach ($staffTracks['data'] as $track) {
                                                    if ($i < 3) {
                                                        // if ($reviews[$track->id] > 0) {
                                                        $href = url("Member_track_download_front_end?tid=" . $track->id);
                                                        $label = 'DOWNLOAD';
                                                        // } else {
                                                        //     $href = url("Member_track_review?tid=" . $track->id);
                                                        //     $label = 'LEAVE REVIEW TO UNLOCK DOWNLOAD';
                                                        // }
                                                        if ($mp3s[$track->id]['numRows'] > 0) {
                                                            $var1 = urldecode($track->title);
                                                            $var2 = urldecode($track->artist);
                                                            $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                            $fileid = (int) $trackLocation;
                                                            if (strpos($trackLocation, '.mp3') !== false) {
                                                                $var3 = url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                            } else if ((int) $fileid) {
                                                                $var3 = url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                            } else {
                                                                $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                            }
                                                            $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

                                                            if (!empty($track->imgpage)) {

                                                                if (file_exists(base_path('ImagesUp/' . $track->imgpage))) {

                                                                    $var5 = asset('ImagesUp/' . $track->imgpage);
                                                                } elseif (!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' . $track->album_page_image))) {
                                                                    $var5 = asset('ImagesUp/' . $track->album_page_image);
                                                                } else {
                                                                    $var5 = asset('public/images/noimage-avl.jpg');
                                                                }
                                                            } elseif (!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' . $track->album_page_image))) {
                                                                $var5 = asset('ImagesUp/' . $track->album_page_image);
                                                            } else {
                                                                $var5 = asset('public/images/noimage-avl.jpg');
                                                            }


                                                            // $var5 = asset("ImagesUp/" . $track->imgpage);

                                                        } else {
                                                            $var1 = urldecode($track->title);
                                                            $var2 = urldecode($track->artist);
                                                            $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                            $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';
                                                            $var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';
                                                        }
                                                        if (strlen($track->thumb) > 4) {
                                                            $src = asset('thumbs/' . $track->thumb);
                                                        } else {
                                                            $src = asset('ImagesUp/' . $track->imgpage);
                                                        }

                                                        // dd($src);

                                                        if (!empty($track->imgpage)) {


                                                            if (file_exists(base_path('ImagesUp/' . $track->imgpage))) {

                                                                $src = $src;
                                                            } else if (file_exists(base_path('thumbs/' . $track->imgpage))) {

                                                                $src = $src;
                                                            } else {
                                                                $src = asset('public/images/noimage-avl.jpg');
                                                            }
                                                        } else {

                                                            $src = asset('public/images/noimage-avl.jpg');
                                                        }

                                                ?>
                                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                            <div class="tdpic">
                                                                <a href="javascript:void(0);" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php echo $var5; ?>','<?php echo $track->id; ?>')">
                                                                    <img src="<?php echo $src; ?>" style="height:120px;" class="img-responsive">
                                                                    <span class="overlay"></span>
                                                                    <span class="overlay-text">
                                                                        <span class="album"><?php
                                                                                            $album = strtoupper(urldecode($track->album));
                                                                                            if (strlen($album) > 13) {
                                                                                                $album = substr($album, 0, 13);
                                                                                            }
                                                                                            echo $album;
                                                                                            ?></span>
                                                                        <span class="artist"><?php
                                                                                                $artist = strtoupper(urldecode($track->artist));
                                                                                                if (strlen($artist) > 13) {
                                                                                                    $artist = substr($artist, 0, 13);
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
                                                <?php }
                                                    $i++;
                                                } ?>
                                            </div><!-- eof row -->
                                        </div><!-- eof stpk-con -->
                                        <!-- <div class="smore text-right"><a href="<?php echo url("member_staff_picks"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a></div> -->
                                    </div><!-- eof stpk-blk -->
                                <?php } ?>

                            </div>


                        </div>
                        <div class="album-d-more">
                            <a href="<?php echo url("member_staff_picks"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a>
                        </div>
                    </div>
                    <!--album-download-->
                    <div class="album-d-sec">
                        <div class="heading-border">
                            <h4>Selected For You</h4>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <!--download tracks-->
                                <?php if ($youTracks['numRows'] > 0) { ?>
                                    <div class="stpk-blk ntf-lst-blk">
                                        <div class="stpk-con">
                                            <div class="row">
                                                <?php
                                                $i = 1;
                                                foreach ($youTracks['data'] as $track) {
                                                    if ($i < 3) {
                                                        // if ($reviews[$track->id] > 0) {
                                                        $href = url("Member_track_download_front_end?tid=" . $track->id);
                                                        $label = 'DOWNLOAD';
                                                        // } else {
                                                        //     $href = url("Member_track_review?tid=" . $track->id);
                                                        //     $label = 'LEAVE REVIEW TO UNLOCK DOWNLOAD';
                                                        // }

                                                        if (!empty($mp3s[$track->id]['numRows'])) {

                                                            if ($mp3s[$track->id]['numRows'] > 0) {
                                                                $var1 = urldecode($track->title);
                                                                $var2 = urldecode($track->artist);
                                                                $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                                $fileid = (int) $trackLocation;
                                                                if (strpos($trackLocation, '.mp3') !== false) {
                                                                    $var3 = url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                                } else if ((int) $fileid) {
                                                                    $var3 = url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                                } else {
                                                                    $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                                }
                                                                $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';

                                                                if (!empty($track->imgpage)) {

                                                                    if (file_exists(base_path('ImagesUp/' . $track->imgpage))) {

                                                                        $var5 = asset('ImagesUp/' . $track->imgpage);
                                                                    } elseif (!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' . $track->album_page_image))) {
                                                                        $var5 = asset('ImagesUp/' . $track->album_page_image);
                                                                    } else {
                                                                        $var5 = asset('public/images/noimage-avl.jpg');
                                                                    }
                                                                } elseif (!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' . $track->album_page_image))) {
                                                                    $var5 = asset('ImagesUp/' . $track->album_page_image);
                                                                } else {
                                                                    $var5 = asset('public/images/noimage-avl.jpg');
                                                                }

                                                                // $var5 = asset("ImagesUp/" . $track->imgpage);
                                                            }
                                                        } else {
                                                            $var1 = urldecode($track->title);
                                                            $var2 = urldecode($track->artist);
                                                            $var3 = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                            $var4 = 'http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg';
                                                            $var5 = 'http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png';
                                                        }


                                                ?>
                                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                            <div class="tdpic">
                                                                <a href="javascript:void(0);" onClick="changeTrack('<?php echo $var1; ?>','<?php echo $var2; ?>','<?php echo $var3; ?>','<?php echo $var4; ?>','<?php echo $var5; ?>','<?php echo $track->id; ?>')">
                                                                    <img src="<?php echo $var5; ?>" width="108" height="108" style="height:138px;" class="img-responsive">
                                                                    <span class="overlay"></span>
                                                                    <span class="overlay-text">
                                                                        <span class="album"><?php
                                                                                            $album = strtoupper(urldecode($track->album));
                                                                                            if (strlen($album) > 13) {
                                                                                                $album = substr($album, 0, 13);
                                                                                            }
                                                                                            echo $album;
                                                                                            ?></span>
                                                                        <span class="artist"><?php
                                                                                                $artist = strtoupper(urldecode($track->artist));
                                                                                                if (strlen($artist) > 13) {
                                                                                                    $artist = substr($artist, 0, 13);
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
                                                <?php }
                                                    $i++;
                                                } ?>
                                            </div><!-- eof row -->
                                        </div><!-- eof stpk-con -->
                                        <!-- <div class="smore text-right"><a href="<?php echo url("member_selected_for_you"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a></div> -->
                                    </div>
                                <?php } ?>
                            </div>


                        </div>
                        <div class="album-d-more">
                            <a href="<?php echo url("member_selected_for_you"); ?>">SEE MORE <i class="fa fa-caret-right"></i></a>
                        </div>
                    </div>

           
        </div>
    </div>
</section>

@endsection