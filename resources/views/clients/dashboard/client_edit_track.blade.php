@extends('layouts.client_dashboard')
@section('content')
<style>
    .nopadding {
        padding: 0px !important;
    }

    .amrFile {
        display: none !important;
    }

    .form-group {
        margin-top: 8px;
    }
</style>
<section class="main-dash">
    <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
    <div class="dash-container">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-12">
                    <div class="dash-heading">
                        <h2>My Dashboard</h2>
                        <?php if ($reviewsCount > 0) { ?>
                            <a target="_blank" href="<?php echo url("Client_track_review?tId=" . $_GET['tId']); ?>">
                                <button type="button" class="prvw_trck_btn">View Reviews</button>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="tabs-section">
                        <div class="col-lg-12 col-md-12">
                            <?php if (isset($alert_class)) { ?>
                                <div class="<?php echo $alert_class; ?>">
                                    <p><?php echo $alert_message; ?></p>
                                </div>
                            <?php } ?>

                            <?php $releaseDate = explode('-', $track['data'][0]->release_date); ?>
                            <form action="" method="post" enctype="multipart/form-data" id="addTrack">
                                @csrf
                                <div class="sat-blk f-block">
                                    <h1>EDIT TRACK</h1>

                                    <div class="" style="">
                                        <div class="row">
                                            <div class="col-lg-7 col-md-9 col-sm-8">
                                                <div class="form-group">
                                                    <input name="artist" id="artist" class="form-control input" size="20" placeholder="Artist Name & Features" type="text" value="<?php echo urldecode($track['data'][0]->artist); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <input name="title" id="title" class="form-control input" size="20" placeholder="Title" type="text" value="<?php echo urldecode($track['data'][0]->title); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <input name="producer" id="producer" class="form-control input" size="20" placeholder="Producer/Production Company" type="text" value="<?php echo urldecode($track['data'][0]->producer); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <input name="writer" id="writer" class="form-control input" size="20" placeholder="Writer" type="text" value="<?php echo urldecode($track['data'][0]->writer); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <input name="songkey" id="songkey" class="form-control input" size="20" placeholder="Song Key" type="text" value="<?php echo urldecode($track['data'][0]->songkey); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <input name="contact_email" id="contact_email" class="form-control input" size="20" placeholder="Contact email" type="email" value="<?php echo urldecode($track['data'][0]->contact_email); ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-5 col-md-3 col-sm-4">
                                                <div class="form-group">
                                                    <label class="btn" style="padding:0;">
                                                        <span class="img_text_client">Click on the image to update.</span>
                                                        <?php
                                                        if (!empty($track['data'][0]->pCloudFileID)) {
                                                            $artWork = url('/pCloudImgDownload.php?fileID=' . $track['data'][0]->pCloudFileID);
                                                        } else if (!empty($track['data'][0]->imgpage)  && strlen($track['data'][0]->imgpage) > 4) {
                                                            $artWork_get =  asset('ImagesUp/' . $track['data'][0]->imgpage);
                                                            if (file_exists(base_path('ImagesUp/' . $track['data'][0]->imgpage))) {
                                                                $artWork = $artWork_get;
                                                            } else {
                                                                $artWork = 'assets/img/upload-artwork.jpg';
                                                            }
                                                        } else {
                                                            $artWork = 'assets/img/upload-artwork.jpg';
                                                        } ?>

                                                        <img src="<?php echo $artWork; ?>" class="img-responsive up-ar-img" id="previewImg"> <input id="artWork" name="artWork" style="display: none;" accept="image/png, image/gif, image/jpeg" type="file">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <input name="trackTime" id="trackTime" class="form-control input" size="20" placeholder="Track Time (in minutes)" type="text" value="<?php echo utf8_decode(urldecode($track['data'][0]->time)); ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" name="bpm" id="bpm" class="form-control" placeholder="BPM (Beats Per Minute)" value="<?php echo $track['data'][0]->bpm; ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <input name="album" id="album" class="form-control input" size="20" placeholder="Album Name" type="text" value="<?php echo urldecode($track['data'][0]->album); ?>">
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label>Album Release Date</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <select name="month" class=" form-control">
                                                        <option value="0">Month</option>

                                                        <option <?php if (strcmp($releaseDate[1], '01') == 0) { ?> selected="selected" <?php } ?> value="01">January</option>

                                                        <option <?php if (strcmp($releaseDate[1], '02') == 0) { ?> selected="selected" <?php } ?> value="02">February</option>

                                                        <option <?php if (strcmp($releaseDate[1], '03') == 0) { ?> selected="selected" <?php } ?> value="03">March</option>

                                                        <option <?php if (strcmp($releaseDate[1], '04') == 0) { ?> selected="selected" <?php } ?> value="04">April</option>

                                                        <option <?php if (strcmp($releaseDate[1], '05') == 0) { ?> selected="selected" <?php } ?> value="05">May</option>

                                                        <option <?php if (strcmp($releaseDate[1], '06') == 0) { ?> selected="selected" <?php } ?> value="06">June</option>

                                                        <option <?php if (strcmp($releaseDate[1], '07') == 0) { ?> selected="selected" <?php } ?> value="07">July</option>

                                                        <option <?php if (strcmp($releaseDate[1], '08') == 0) { ?> selected="selected" <?php } ?> value="08">August</option>

                                                        <option <?php if (strcmp($releaseDate[1], '09') == 0) { ?> selected="selected" <?php } ?> value="09">September</option>

                                                        <option <?php if (strcmp($releaseDate[1], '10') == 0) { ?> selected="selected" <?php } ?> value="10">October</option>

                                                        <option <?php if (strcmp($releaseDate[1], '11') == 0) { ?> selected="selected" <?php } ?> value="11">November</option>

                                                        <option <?php if (strcmp($releaseDate[1], '12') == 0) { ?> selected="selected" <?php } ?> value="12">December</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <select name="day" class=" form-control">
                                                        <option value="0">Day</option>

                                                        <option <?php if ($releaseDate[2] == '01') { ?> selected="selected" <?php } ?> value="01">1</option>

                                                        <option <?php if ($releaseDate[2] == '02') { ?> selected="selected" <?php } ?> value="02">2</option>

                                                        <option <?php if ($releaseDate[2] == '03') { ?> selected="selected" <?php } ?> value="03">3</option>

                                                        <option <?php if ($releaseDate[2] == '04') { ?> selected="selected" <?php } ?> value="04">4</option>

                                                        <option <?php if ($releaseDate[2] == '05') { ?> selected="selected" <?php } ?> value="05">5</option>

                                                        <option <?php if ($releaseDate[2] == '06') { ?> selected="selected" <?php } ?> value="06">6</option>

                                                        <option <?php if ($releaseDate[2] == '07') { ?> selected="selected" <?php } ?> value="07">7</option>

                                                        <option <?php if ($releaseDate[2] == '08') { ?> selected="selected" <?php } ?> value="08">8</option>

                                                        <option <?php if ($releaseDate[2] == '09') { ?> selected="selected" <?php } ?> value="09">9</option>

                                                        <option <?php if ($releaseDate[2] == '10') { ?> selected="selected" <?php } ?> value="10">10</option>

                                                        <option <?php if ($releaseDate[2] == '11') { ?> selected="selected" <?php } ?> value="11">11</option>

                                                        <option <?php if ($releaseDate[2] == '12') { ?> selected="selected" <?php } ?> value="12">12</option>

                                                        <option <?php if ($releaseDate[2] == '13') { ?> selected="selected" <?php } ?> value="13">13</option>

                                                        <option <?php if ($releaseDate[2] == '14') { ?> selected="selected" <?php } ?> value="14">14</option>

                                                        <option <?php if ($releaseDate[2] == '15') { ?> selected="selected" <?php } ?> value="15">15</option>

                                                        <option <?php if ($releaseDate[2] == '16') { ?> selected="selected" <?php } ?> value="16">16</option>

                                                        <option <?php if ($releaseDate[2] == '17') { ?> selected="selected" <?php } ?> value="17">17</option>

                                                        <option <?php if ($releaseDate[2] == '18') { ?> selected="selected" <?php } ?> value="18">18</option>

                                                        <option <?php if ($releaseDate[2] == '19') { ?> selected="selected" <?php } ?> value="19">19</option>

                                                        <option <?php if ($releaseDate[2] == '20') { ?> selected="selected" <?php } ?> value="20">20</option>

                                                        <option <?php if ($releaseDate[2] == '21') { ?> selected="selected" <?php } ?> value="21">21</option>

                                                        <option <?php if ($releaseDate[2] == '22') { ?> selected="selected" <?php } ?> value="22">22</option>

                                                        <option <?php if ($releaseDate[2] == '23') { ?> selected="selected" <?php } ?> value="23">23</option>

                                                        <option <?php if ($releaseDate[2] == '24') { ?> selected="selected" <?php } ?> value="24">24</option>

                                                        <option <?php if ($releaseDate[2] == '25') { ?> selected="selected" <?php } ?> value="25">25</option>

                                                        <option <?php if ($releaseDate[2] == '26') { ?> selected="selected" <?php } ?> value="26">26</option>

                                                        <option <?php if ($releaseDate[2] == '27') { ?> selected="selected" <?php } ?> value="27">27</option>

                                                        <option <?php if ($releaseDate[2] == '28') { ?> selected="selected" <?php } ?> value="28">28</option>

                                                        <option <?php if ($releaseDate[2] == '29') { ?> selected="selected" <?php } ?> value="29">29</option>

                                                        <option <?php if ($releaseDate[2] == '30') { ?> selected="selected" <?php } ?> value="30">30</option>

                                                        <option <?php if ($releaseDate[2] == '31') { ?> selected="selected" <?php } ?> value="31">31</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <select name="year" class=" form-control">
                                                        <option value="0">Year</option>
                                                        <?php
                                                        $current_year = date('Y');
                                                        for ($year = 2000; $year <= $current_year; $year++) { ?>
                                                            <option <?php if ($releaseDate[0] == $year) { ?> selected="selected" <?php } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <select name="genre" id="genre" class=" form-control" onchange="change_genre(this.value)">
                                                        <option value="">Genre</option>
                                                        <?php if ($genres['numRows'] > 0) {
                                                            foreach ($genres['data'] as $genre) { ?>
                                                                <option <?php if ($track['data'][0]->genreId == $genre->genreId) { ?> selected <?php } ?> value="<?php echo $genre->genreId; ?>"><?php echo $genre->genre; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <select name="subGenre" id="subGenre" class=" form-control">
                                                        <option value="">Sub Genre</option>

                                                        <?php if (!empty($subGenres['numRows']) && $subGenres['numRows'] > 0) {
                                                            foreach ($subGenres['data'] as $genre) { ?>
                                                                <option <?php if ($track['data'][0]->subGenreId == $genre->subGenreId) { ?> selected <?php } ?> value="<?php echo $genre->subGenreId; ?>"><?php echo $genre->subGenre; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                            <?php
                                             //echo '<pre>';print_r($subGenres);die();
                                            ?>                                        
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="header smaller lighter">
                                                    Contact Details
                                                </h3>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group"><span class="man"></span>
                                                    <input required type="text" id="contact_name" name="contact_name" class="form-control" value="<?php echo $track['data'][0]->contact_name; ?>" placeholder="Contact Name">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group"><span class="man"></span>
                                                    <input required name="contact_email" id="contact_email" class="form-control input" size="20" value="<?php echo $track['data'][0]->contact_email; ?>" placeholder="Contact email" type="email">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="email" id="contact_email_2" name="contact_email_2" class="form-control" value="<?php echo $track['data'][0]->contact_email_2; ?>" placeholder=" Second Contact Email">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="email" id="contact_email_3" name="contact_email_3" class="form-control" value="<?php echo $track['data'][0]->contact_email_3; ?>" placeholder=" Third Contact Email">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="email" id="contact_email_4" name="contact_email_4" class="form-control" value="<?php echo $track['data'][0]->contact_email_4; ?>" placeholder=" Fourth Contact Email">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo $track['data'][0]->contact_phone; ?>" placeholder="Contact Phone">
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="text" id="relationship_to_artist" name="relationship_to_artist" class="form-control" value="<?php echo $track['data'][0]->relationship_to_artist; ?>" placeholder="Relationship to Artist">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="header smaller lighter">
                                                    Social Media Links
                                                </h3>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input name="website" id="website" class="form-control input" size="20" placeholder="Website Link" type="text" value="<?php echo urldecode($track['data'][0]->link); ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="facebookLink" id="facebookLink" class="form-control input" size="20" placeholder="Facebook Link" type="text" value="<?php echo urldecode($track['data'][0]->facebookLink); ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="twitterLink" id="twitterLink" class="form-control input" size="20" placeholder="Twitter Link" type="text" value="<?php echo urldecode($track['data'][0]->twitterLink); ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="instagramLink" id="instagramLink" class="form-control input" size="20" placeholder="Instagram Link" type="text" value="<?php echo urldecode($track['data'][0]->instagramLink); ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="snapchatLink" id="snapchatLink" class="form-control input" value="<?php echo $track['data'][0]->snapchatLink; ?>" size="20" placeholder="Snapchat Link" type="text">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="tiktokLink" id="tiktokLink" class="form-control input" value="<?php echo $track['data'][0]->tiktokLink; ?>" size="20" placeholder="TikTok Link" type="text">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="otherLink" id="otherLink" class="form-control input" value="<?php echo $track['data'][0]->otherLink; ?>" size="20" placeholder="Other Link" type="text">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="applemusicLink" id="applemusicLink" class="form-control input" value="<?php echo $track['data'][0]->applemusicLink; ?>" size="20" placeholder="Apple Music URL" type="url">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="amazonLink" id="amazonLink" class="form-control input" value="<?php echo $track['data'][0]->amazonLink; ?>" size="20" placeholder="Amazon URL" type="url">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="spotifyLink" id="spotifyLink" class="form-control input" value="<?php echo $track['data'][0]->spotifyLink; ?>" size="20" placeholder="Spotify URL" type="url">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input name="videoURL" id="videoURL" class="form-control input" size="20" placeholder="Video URL" type="text" value="<?php echo urldecode($track['data'][0]->videoURL); ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <textarea name="embedvideoURL" id="embedvideoURL" class="form-control" placeholder="Video Embed URL(Youtube/Vimeo)"><?php echo urldecode(trim($track['data'][0]->embedvideoURL)); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <textarea name="trackInfo" id="trackInfo" class="form-control" placeholder="Bonus Track Information" rows="5"><?php echo urldecode($track['data'][0]->moreinfo); ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <div class="dash-heading">
                                                    <h2>Audio Files</h2>
                                                </div>
                                                <?php
                                                $audio_main = (object) $track['data'];

                                                $audioSubmittedVersions = $track_submitted_versions['data'];

                                                //echo count($audioSubmittedVersions);die;
                                                $i = 1;
                                                ?>

                                                <table id="simple-table" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="center" width="100">S. No</th>
                                                            <th class="center">Track</th>
                                                            <th class="center" style="width:10%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $key = "location";
                                                        foreach ($audio_main as $audio) {
                                                            if (!empty($audio->$key)) { ?>
                                                                <tr>
                                                                    <td class="center"><?php echo $i; ?></td>
                                                                    <td>
                                                                        <audio controls style="width:100%;">
                                                                            <?php if (strpos($audio->$key, '.mp3') !== false) { ?>
                                                                                <source src="<?php echo asset('AUDIO/' . $audio->$key); ?>" type="audio/mp3">
                                                                            <?php } else {
                                                                                $fileid = (int) $audio->$key;
                                                                                $getlink = !empty($fileid) ? url('download.php?fileID=' . $fileid) : ''; ?>
                                                                                <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                                                            <?php } ?>
                                                                            Your browser does not support the audio element.
                                                                        </audio>
                                                                    </td>
                                                                    <td class="center">
                                                                        <button type="button" class="btn btn-danger delete-track-edit" data-fileid="{{ $audio->$key }}">
                                                                            <i class="ace-icon fa fa-trash-o"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                        <?php }
                                                            $i++;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php $audio = (object) $track['data'][0];
                                        $audioSubmittedVersions = $track_submitted_versions['data'];
                                        if (!empty($audioSubmittedVersions) && count($audioSubmittedVersions) > 0) {
                                        ?>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="dash-heading">
                                                        <h2>Track Versions Pending Approval</h2>
                                                    </div>
                                                    <?php
                                                    //echo count($audioSubmittedVersions);die;
                                                    $i = 1;
                                                    ?>

                                                    <table id="simple-table" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="center" width="100">Version</th>
                                                                <th class="center">Track</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (!empty($audioSubmittedVersions) && count($audioSubmittedVersions) > 0) {
                                                                $j = $i + 1;
                                                                foreach ($audioSubmittedVersions as $audio) {


                                                                    $key = 'pcloud_fileId';

                                                                    if (!empty($audio->$key)) { ?>
                                                                        <tr>
                                                                            <td class="center">
                                                                                <p class="center"><?php echo urldecode($audio->version_name); ?></p>
                                                                            </td>
                                                                            <td>

                                                                                <audio controls style="width:100%;">
                                                                                    <?php if (strpos($audio->$key, '.mp3') !== false) { ?>
                                                                                        <source src="<?php echo asset('AUDIO/' . $audio->$key); ?>" type="audio/mp3">
                                                                                    <?php } else {
                                                                                        $fileid = (int) $audio->$key;
                                                                                        $getlink = !empty($fileid) ? url('download.php?fileID=' . $fileid) : ''; ?>
                                                                                        <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                                                                    <?php } ?>
                                                                                    Your browser does not support the audio element.
                                                                                </audio>
                                                                            </td>
                                                                        </tr>
                                                            <?php }

                                                                    $j++;
                                                                }
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="row">
                                            <div class="dash-heading">
                                                <h2>Add More Audio Files & Versions</h2>
                                            </div>
                                            <input type="hidden" id="divId" name="divId" value="0">
                                            <div id="audioFiles">
                                                <div id="audioHtml1">
                                                    <div class="col-sm-4 form-group versionDiv">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Version </label>

                                                        <div class="col-sm-9 track-group">
                                                            <select name="version1" id="version1" class="form-control version">
                                                                <option value="">Version</option>
                                                                <option value="Acapella">Acapella</option>
                                                                <option value="Clean">Clean</option>
                                                                <option value="Clean Accapella">Clean Accapella</option>
                                                                <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)</option>
                                                                <option value="Dirty">Dirty</option>
                                                                <option value="Dirty Accapella">Dirty Accapella</option>
                                                                <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)</option>
                                                                <option value="Instrumental">Instrumental</option>
                                                                <option value="Main">Main</option>
                                                                <option value="TV Track">TV Track</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 form-group versionDiv track-group">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Other Version </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="otherVersion1" name="otherVersion1" class="col-xs-10 col-sm-10">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 form-group versionDiv track-group">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> File </label>
                                                        <div class="col-sm-9">
                                                            <input required type="file" id="audio1" name="audio1" class="col-xs-10 col-sm-10" required accept=".mp3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="add_remove_track_btn">
                                                <a href="javascript:void()" id="addVersionBtn" class="addRemoveLinks mr-3"><i class="fa fa-plus-circle"></i>
                                                    <span>Add More Version</span>
                                                </a>
                                                <a href="javascript:void()" class="addRemoveLinks" id="removeVersionBtn"><i class="fa fa-minus-circle"></i>
                                                    <span>Remove Version</span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="pre-btn">
                                                    <input type="submit" name="updateTrack" value="Update Track" class="add_track_button">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- eof middle block -->
                    </div>
                </div>
                <div class="col-xl-3 col-12">
                    @include('clients.dashboard.includes.my-tracks')
                </div>
            </div>
        </div>
    </div>
</section>
<!-- <section>
   <div class="test">
      <pre>
      <?php // print_r($track['data'][0]); 
        ?>
      </pre>
   </div>
</section> -->

<script>
    function change_genre(genreId) {
        $.ajax({
            url: "Client_submit_track?getSubGenres=1&genreId=" + genreId,
            success: function(result) {

                var obj = JSON.parse(result);

                var count = obj.length;

                var liList = '';

                var optionList = '';

                for (var i = 0; i < count; i++)

                {
                    liList += '<li data-original-index="' + i + '"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">' + obj[i].name + '</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';

                    optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';

                }

                document.getElementsByClassName('filter-option pull-left')[4].innerHTML = 'Sub Genre';

                document.getElementsByClassName('dropdown-menu inner')[4].innerHTML = liList;

                document.getElementById('subGenre').innerHTML = optionList;
            }
        });
    }

    function removePhone() {
        var numVersion = document.getElementById('numVersion').value;
        var id = parseInt(numVersion) - 1;
        if (numVersion > 1) {
            document.getElementById('uptrk').removeChild(document.getElementById('versionDiv' + id));
            document.getElementById('uptrk').removeChild(document.getElementById('imgDiv' + id));
            document.getElementById('numVersion').value = id;
        }
    }

    function addPhone(filePath) {

        var numVersion = document.getElementById('numVersion').value;
        var newVersion = parseInt(numVersion) + 1;
        if (numVersion < 4) {
            var clearDiv = document.createElement("div");
            clearDiv.setAttribute("class", "clearfix");
            var div = document.createElement("div");
            div.setAttribute("class", "form-group col-sm-5 nopadding");
            div.setAttribute("id", "versionDiv" + numVersion);
            var phoneInput = document.createElement("select");
            phoneInput.setAttribute("name", "version" + newVersion);
            phoneInput.setAttribute("id", "version" + newVersion);
            phoneInput.setAttribute("class", "form-control");
            var option1 = document.createElement("option");
            option1.setAttribute("value", "Clean");
            var text = document.createTextNode("Clean");
            option1.appendChild(text);
            phoneInput.appendChild(option1);
            var option1 = document.createElement("option");
            option1.setAttribute("value", "Instrumental");
            var text = document.createTextNode("Instrumental");
            option1.appendChild(text);
            phoneInput.appendChild(option1);
            var option1 = document.createElement("option");
            option1.setAttribute("value", "Acapella");
            var text = document.createTextNode("Acapella");
            option1.appendChild(text);
            phoneInput.appendChild(option1);
            var option1 = document.createElement("option");
            option1.setAttribute("value", "Dirty");
            var text = document.createTextNode("Dirty");
            option1.appendChild(text);
            phoneInput.appendChild(option1);
            document.getElementById('uptrk').appendChild(clearDiv);
            div.appendChild(phoneInput);
            var imgDiv = document.createElement("div");
            imgDiv.setAttribute("class", "form-group col-sm-7");
            imgDiv.setAttribute("id", "imgDiv" + numVersion);
            var imgLabel = document.createElement("label");
            imgLabel.setAttribute("class", "btn nopadding");
            var img = document.createElement("img");
            img.setAttribute("class", "img-responsive");
            img.setAttribute("src", filePath);
            var fileInput = document.createElement("input");
            fileInput.setAttribute("type", "file");
            fileInput.setAttribute("name", "amr" + newVersion);
            fileInput.setAttribute("class", "amrFile");
            imgLabel.appendChild(img);
            imgLabel.appendChild(fileInput);
            imgDiv.appendChild(imgLabel);
            document.getElementById('uptrk').appendChild(div);
            document.getElementById('uptrk').appendChild(imgDiv);
            document.getElementById('numVersion').value = parseInt(numVersion) + 1;
        }
    }

    $(function() {
        $("#addTrack").validate();
        $("#artWork").rules("add", {
            required: true,
            messages: {
                required: "Please enter website"
            }
        });

        $("#artist").rules("add", {
            required: true,
            messages: {
                required: "Please enter artist name"
            }
        });

        $("#title").rules("add", {
            required: true,
            messages: {
                required: "Please enter title"
            }
        });

        $("#producer").rules("add", {
            required: true,
            messages: {
                required: "Please enter producer"
            }
        });

        $("#trackTime").rules("add", {
            required: true,
            messages: {
                required: "Please enter track time"
            }
        });

        $("#bpm").rules("add", {
            required: true,
            messages: {
                required: "Please enter bpm"
            }
        });

        $("#album").rules("add", {
            required: true,
            messages: {
                required: "Please enter album"
            }
        });

        $("#website").rules("add", {
            required: true,
            messages: {
                required: "Please enter website"
            }
        });

        $("#agree").rules("add", {
            required: true,
            messages: {
                required: "Please agree"
            }
        });
    });
</script>

<script>
    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').style.width = '199px';
                document.getElementById('previewImg').style.height = '199px';
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#artWork").change(function() {
        filePreview(this);
    });
</script>
<script>
    $(document).ready(function() {
        $(".delete-track-edit").on("click", function() {
            var fileId = $(this).data("fileid");

            if (!confirm("Are you sure you want to delete this track?")) {
                return;
            }

            $('.processing_loader_gif').show();
            $.ajax({
                url: "{{ route('delete.client.track') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    file_id: fileId
                },
                success: function(response) {
                    $('.processing_loader_gif').hide();
                    location.reload();
                },
                error: function(xhr) {
                    $('.processing_loader_gif').hide();
                    alert("Error deleting track. Please try again.");
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        let versionCount = 0;

        // Hide the original template
        const $template = $('#audioFiles').hide();

        // Hide remove button initially
        $('#removeVersionBtn').hide();

        // Add More Version
        $('#addVersionBtn').click(function() {
            versionCount++;

            // Clone the hidden template
            const $newBlock = $template.clone().show();

            // Give the outer wrapper a unique ID
            $newBlock.attr('id', 'audioFiles' + versionCount);

            // Update all internal field IDs and names
            $newBlock.find('[id^=audioHtml]').attr('id', 'audioHtml' + versionCount);
            $newBlock.find('[id^=version]').attr({
                id: 'version' + versionCount,
                name: 'version' + versionCount
            });
            $newBlock.find('[id^=otherVersion]').attr({
                id: 'otherVersion' + versionCount,
                name: 'otherVersion' + versionCount
            });
            $newBlock.find('[id^=audio]').attr({
                id: 'audio' + versionCount,
                name: 'audio' + versionCount
            });

            // Append after the last version block
            $template.parent().append($newBlock);

            var divId = document.getElementById('divId').value;
            var divIdPlus = parseInt(divId) + 1;
            document.getElementById('divId').value = divIdPlus;

            // Show remove button
            $('#removeVersionBtn').show();
        });

        // Remove Last Version
        $('#removeVersionBtn').click(function() {
            if (versionCount > 0) {
                // Remove the last added version block
                $('#audioFiles' + versionCount).remove();

                versionCount--;

                // Hide remove button if no versions left
                if (versionCount === 0) {
                    $('#removeVersionBtn').hide();
                }
                var divId = document.getElementById('divId').value;

                if (divId > 0) {
                    var divIdMinus = parseInt(divId) - 1;
                    document.getElementById('divId').value = divIdMinus;

                }

            }
        });
    });
</script>



@endsection