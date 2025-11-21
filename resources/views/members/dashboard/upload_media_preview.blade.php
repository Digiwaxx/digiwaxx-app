@extends('layouts.member_dashboard')

@section('content')
<section class="main-dash">
    <aside>@include('layouts.include.sidebar-left')</aside>
    <div class="dash-container">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="dash-heading">
                        <h2>My Dashboard</h2>
                    </div>
                    <div class="tabs-section">

                        <!-- START MIDDLE BLOCK -->


                        <div class="col-lg-6 col-md-12">

                            <!--					satp-blk

-->
                            <div class=" f-block trk-info-blk" style="overflow:hidden; padding-top:30px;">

                                <h1>SUBMIT A TRACK - PREVIEW</h1>

                                <div class="row">

                                    <div class="col-lg-6 col-md-6 col-sm-6">

                                        <div>

                                            <?php
                                            // dd($track);

                                            if (strlen($track['data'][0]->imgpage) > 3) {



                                                $imgSrc = 'ImagesUp/' . $track['data'][0]->imgpage;
                                            } else {

                                                $imgSrc = 'assets/img/track-logo.png';
                                            }

                                            ?>

                                            <img src="<?php echo $imgSrc; ?>" class="img-responsive ar-fsize">

                                        </div>

                                        <div style="clear:both;"></div>

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">

                                        <h1 class="tinfo">TRACK INFO <a href="<?php echo url("upload_media_edit?tId=" . $_GET['tId']); ?>">(CLICK TO EDIT)</a></h1>


                                        <div class="trk-det">

                                            <p class="t1"><label>Artist: </label> <span> <?php echo urldecode($track['data'][0]->artist); ?></span></p>

                                            <p class="t1"><label>Title: </label> <span><?php echo urldecode($track['data'][0]->title); ?></span></p>

                                            <?php

                                            $albumType = '';

                                            if ($track['data'][0]->albumType == 1) {

                                                $albumType = 'Single';
                                            } else if ($track['data'][0]->albumType == 2) {

                                                $albumType = 'Album';
                                            } else if ($track['data'][0]->albumType == 3) {

                                                $albumType = 'EP';
                                            } else if ($track['data'][0]->albumType == 4) {

                                                $albumType = 'Mixtape';
                                            }
                                            ?>

                                            <p class="t1"><label>Album Type: </label> <span><?php echo $albumType; ?></span></p>

                                            <p class="t1"><label>Album: </label> <span><?php echo urldecode($track['data'][0]->album); ?></span></p>

                                            <p class="t1"><label>Time: </label> <span><?php echo $track['data'][0]->time; ?></span></p>

                                            <p class="t1"><label>BPM: </label> <span><?php echo $track['data'][0]->bpm; ?></span></p>

                                            <p class="t1"><label>Release Date: </label> <span> <?php $dt = explode(' ', $track['data'][0]->releasedate);

                                                                                                $date = explode('-', $dt[0]);

                                                                                                echo $date = $date[1] . '-' . $date[2] . '-' . $date[0];

                                                                                                ?></span></p>

                                            <p class="t1"><label>Label: </label> <span><?php echo urldecode($track['data'][0]->label); ?></span></p>

                                            <p class="t1"><label>Website: </label> <span> <?php echo urldecode($track['data'][0]->link); ?></span></p>

                                            <?php if (strlen($track['data'][0]->link1) > 0) { ?>

                                                <p class="t1"><label>Website1 : </label> <span> <?php echo urldecode($track['data'][0]->link1); ?></span></p>

                                            <?php }
                                            if (strlen($track['data'][0]->link2) > 0) { ?>

                                                <p class="t1"><label>Website2 : </label> <span> <?php echo urldecode($track['data'][0]->link2); ?></span></p>

                                            <?php } ?>

                                            <p class="t1"><label>Producer: </label> <span><?php echo urldecode($track['data'][0]->producers); ?></span></p>

                                            <p class="t1"><label>Genre: </label> <span><?php echo $track['data'][0]->genre; ?></span></p>

                                            <p class="t1"><label>Sub Genre: </label> <span><?php echo $track['data'][0]->subGenre; ?></span></p>

                                            <p class="t1"><label>Facebook: </label> <span><?php echo $track['data'][0]->facebookLink; ?></span></p>

                                            <p class="t1"><label>Twitter: </label> <span><?php echo $track['data'][0]->twitterLink; ?></span></p>

                                            <p class="t1"><label>Instagram: </label> <span><?php echo $track['data'][0]->instagramLink; ?></span></p>

                                            <p class="t1"><label>More information: </label> <span><?php echo urldecode($track['data'][0]->moreinfo); ?></span></p>

                                        </div>

                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12">



                                        <div style="margin:40px 20px;">



                                            <?php if (!empty($track['data'][0]->amr1)) {
                                                if (strlen($track['data'][0]->amr1) > 4) {  ?>

                                                    <div class="rtrk-item">

                                                        <p>Version: <?php echo $track['data'][0]->version1;  ?></p>

                                                        <div class="media-wrapper">

                                                            <audio id="player2" preload="none" controls style="max-width:100%;">

                                                                <source src="<?php echo asset("AUDIO/" . $track['data'][0]->amr1); ?>" type="audio/mp3">

                                                            </audio>

                                                        </div>

                                                    </div><!-- eof rtrk-item -->

                                            <?php }
                                            } ?>



                                            <?php if (!empty($track['data'][0]->amr2)) {
                                                if (strlen($track['data'][0]->amr2) > 4) {  ?>

                                                    <div class="rtrk-item">

                                                        <p>Version: <?php echo $track['data'][0]->version2;  ?></p>

                                                        <div class="media-wrapper">

                                                            <audio id="player2" preload="none" controls style="max-width:100%;">

                                                                <source src="<?php echo asset("AUDIO/" . $track['data'][0]->amr2); ?>" type="audio/mp3">

                                                            </audio>

                                                        </div>

                                                    </div><!-- eof rtrk-item -->

                                            <?php }
                                            } ?>



                                            <?php if (!empty($track['data'][0]->amr3)) {
                                                if (strlen($track['data'][0]->amr3) > 4) {  ?>

                                                    <div class="rtrk-item">

                                                        <p>Version: <?php echo $track['data'][0]->version3;  ?></p>



                                                        <div class="media-wrapper">

                                                            <audio id="player2" preload="none" controls style="max-width:100%;">

                                                                <source src="<?php echo asset("AUDIO/" . $track['data'][0]->amr3); ?>" type="audio/mp3">

                                                            </audio>

                                                        </div>

                                                    </div><!-- eof rtrk-item -->

                                            <?php }
                                            } ?>



                                            <?php
                                            if (!empty($track['data'][0]->amr4)) {
                                                if (strlen($track['data'][0]->amr4) > 4) {  ?>

                                                    <div class="rtrk-item">

                                                        <p>Version: <?php echo $track['data'][0]->version4;  ?></p>



                                                        <div class="media-wrapper">

                                                            <audio id="player2" preload="none" controls style="max-width:100%;">

                                                                <source src="<?php echo asset("AUDIO/" . $track['data'][0]->amr4); ?>" type="audio/mp3">

                                                            </audio>

                                                        </div>

                                                    </div><!-- eof rtrk-item -->

                                            <?php }
                                            } ?>

                                            <div style="clear:both;"></div>

                                            <div class="satp-blk" style="margin-top:20px;">

                                                <div class="help-text">

                                                    Happy with what you see? <br> Click on the submit button below!

                                                </div>

                                                <div class="form-group">

                                                    <form action="" method="post">
                                                        @csrf

                                                        <input name="confirmPreview" class="login_btn btn bsp" value="SUBMIT" type="submit">

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                            </div>

                        </div><!-- eof middle block -->

                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>



@endsection