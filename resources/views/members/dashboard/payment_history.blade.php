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
                        <h2><?php echo $pageTitle;?></h2>
                    </div>
                  </div>
          </div>
                    <?php
                    //   echo '<pre>';
                    // print_r($alert_class);
                    // die;
    
                    ?>
                    
             <div class="container mt-5">
                <div class="table-responsive">
                <?php if(!empty($pay_detail)){?>  
                <div style="height:300px;overflow-y: auto;">
                    <table class="table table-striped table-dark text-white table-hover" id="history" >
                        <thead>
                            <tr>
                                <th class="text-center">S.no<?php $count=1;?></th>
                                <th colspan="2">SUBSCRIPTION</th>
                                <th>AMOUNT PAID</th>
                                <th>START DATE</th>
                                <th>EXPIRY DATE</th>
                                <th>STATUS</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pay_detail as $value) {?>   
                            <tr>
                                <td class="text-center"><?php echo $count; $count++;?></td>
                                <td colspan="2">
                                    <h6><?php echo $value->package_type;?></h6>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="ml-2"><?php if(!empty($value->payment_amount)){echo "$".$value->payment_amount;}else{echo "FREE";}?></span></div>
                                </td>
                                <td><?php if($value->package_start_date !='0000-00-00'){?>
                                                          <?php  $date = new DateTime($value->package_start_date);
                                                                $result = $date->format('d M Y');?>
                                                                <p class="start date"><b><?php echo $result ?></b></p>
                                                         <?php }?> </td>
                                <td class="font-weight-bold"><?php if($value->package_expiry_date !='0000-00-00'){?>
                                                         <?php  $date = new DateTime($value->package_expiry_date);
                                                             
                                                                $result1 = $date->format('d M Y');?>
                                                          <p class="end date"><b><?php echo $result1 ?></b></p>
                                                   <?php }?></td>
                                <td><?php if($value->package_active==1){echo '<mark>ACTIVE</mark>';}else{echo "EXPIRED" ;}?></td>
                                
                            </tr>
                       <?php }?>



                        </tbody>
                    </table>
                </div>    
                    <?php } else{echo "NO RECORDS FOUND";}?>
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