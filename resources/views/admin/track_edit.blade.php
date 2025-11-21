@extends('admin.admin_dashboard_active_sidebar')
@section('content')

<div class="main-content edit-track-page">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {}
            </script>
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo url("admin/tracks"); ?>">
                        <i class="ace-icon fa fa-list list-icon"></i>
                        Tracks</a>
                </li>
                <li class="active">Edit Track</li>
            </ul><!-- /.breadcrumb -->

            <form method="POST" style="display: inline-block;" action="<?php echo url("Member_track_download?tid=" . $_GET['tid']); ?>" target="_blank">
                @csrf
                <input type="hidden" name="adminID" value="<?php echo $_COOKIE['adminId']; ?>">
                <input type="hidden" name="is_admin_view" value="yes">
                <input type="submit" value="FrontEnd Preview" class="btn btn-info btn-sm">
            </form>
            <!-- #section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
            <!-- /section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                            if (isset($alert_message)) {
                            ?>
                                <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
                            <?php
                            }
                            ?>
                            <?php $track = $tracks['data'][0];


                            ?>
                            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()" style="color:white;">
                                @csrf
                                <?php //pArr($track);
                                ?>
                                <div>
                                    <h3 class="header smaller lighter blue">
                                        Track Information
                                    </h3>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="artist">Artist </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="artist" name="artist" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->artist); ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 req-label control-label no-padding-right" for="form-field-1">
                                            Title </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="title" name="title" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->title); ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Featured Artist-1 </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="featured_artist_1" name="featured_artist_1" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->featured_artist_1); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Featured Artist-2 </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="featured_artist_2" name="featured_artist_2" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->featured_artist_2); ?>" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Type
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="col-xs-10 col-sm-10" name="type" value="<?php echo !empty($track->type) ? ucfirst($track->type) : 'Track'; ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Client
                                        </label>
                                        <div class="col-sm-9">
                                            <select id="client" name="client" class="col-xs-10 col-sm-10">
                                                <option value="">Select Client</option>
                                                <?php foreach ($clients as $client) { ?>
                                                    <option value="<?php echo $client->id; ?>" <?php if (!empty($track->client)) echo ($track->client == $client->id) ? " selected=' selected'" : "" ?>><?php echo urldecode($client->name); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Label / Company </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="company" placeholder="Label / Company" name="company" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->label); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Producer(s) </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="producers" name="producers" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->producer); ?>" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Release
                                            Type</label>
                                        <div class="col-sm-9">
                                            <select name="albumType" id="albumType" class="col-xs-10 col-sm-10">
                                                <option value="">Release Type</option>
                                                <option <?php if ($track->albumType == 1) { ?> selected <?php } ?> value="1">Single</option>
                                                <option <?php if ($track->albumType == 2) { ?> selected <?php } ?> value="2">Album</option>
                                                <option <?php if ($track->albumType == 3) { ?> selected <?php } ?> value="3">EP</option>
                                                <option <?php if ($track->albumType == 4) { ?> selected <?php } ?> value="4">Mixtape</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Writer </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="writer" name="writer" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->writer); ?>" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Album
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="album" name="album" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->album); ?>" />
                                        </div>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">BPM
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="bpm" name="bpm" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->bpm); ?>">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1"> Time
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="time" name="time" class="col-xs-10 col-sm-10" required value="<?php echo urldecode($track->time); ?>" />
                                        </div>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1">
                                            Genre </label>
                                        <div class="col-sm-9">
                                            <select name="genre" id="genre" class="col-xs-10 col-sm-10" required onchange="change_genre(this.value)">
                                                <option value="">Genre</option>
                                                <?php if ($genres['numRows'] > 0) {
                                                    foreach ($genres['data'] as $genre) { ?>
                                                        <option <?php if ($tracks['data'][0]->genreId == $genre->genreId) { ?> selected <?php } ?> value="<?php echo $genre->genreId; ?>">
                                                            <?php echo $genre->genre; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sub
                                            Genre </label>
                                        <div class="col-sm-9">
                                            <select name="subGenre" id="subGenre" class="col-xs-10 col-sm-10">
                                                <option value="">Sub Genre</option>
                                                <?php if ($subGenres['numRows'] > 0) {
                                                    foreach ($subGenres['data'] as $genre) { ?>
                                                        <option <?php if ($tracks['data'][0]->subGenreId == $genre->subGenreId) { ?> selected <?php } ?> value="<?php echo $genre->subGenreId; ?>">
                                                            <?php echo $genre->subGenre; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Song Key </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="songkey" name="songkey" value="<?php echo urldecode($track->songkey); ?>" class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> More
                                            Info. </label>
                                        <div class="col-sm-9">
                                            <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="col-xs-10 col-sm-10"><?php echo urldecode($track->moreinfo); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Caption</label>
                                        <div class="col-sm-9">
                                            <textarea id="notes" placeholder="Track Caption" name="notes" class="col-xs-10 col-sm-10"><?php echo urldecode($track->notes); ?></textarea>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1"> Status </label>
                                        <div class="col-sm-9">
                                            <select name="status" id="status" class="col-xs-10 col-sm-10" required>
                                                <option <?php if ($track->status === 'draft') { ?> selected <?php } ?> value="draft">Draft</option>
                                                <option <?php if ($track->status === 'publish') { ?> selected <?php } ?> value="publish">Publish</option>
                                                <option <?php if ($track->status === 'unpublish') { ?> selected <?php } ?> value="unpublish">Unpublish</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <!--       <div class="col-sm-6 form-group">
                                         <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                             E-mail Image </label>
                                         <div class="col-sm-9">
                                             <input type="file" id="emailImage" name="emailImage" class="col-xs-10 col-sm-10">
                                             <?php if (strlen($track->img) > 4) { ?>
                                                 <img src="<?php echo asset('ImagesUp/' . $track->img); ?>" width="50" height="50" />
                                                 <button type="button" class="btn btn-xs btn-danger" onclick="deleteEmailImage(<?php echo (int) $track->id; ?>, this);"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                                             <?php } ?>
                                         </div>
                                     </div>-->
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1"> Page Image (Artwork) </label>
                                        <div class="col-sm-9">
                                            <input type="file" id="pageImage" name="pageImage" class="col-xs-10 col-sm-10" accept="image/png, image/gif, image/jpeg">
                                            <?php
                                            if (!empty($track->pCloudFileID)) {
                                                $page_img = url('/pCloudImgDownload.php?fileID=' . $track->pCloudFileID); ?>
                                                <img src="<?php echo $page_img; ?>" width="50" height="50" />
                                                <button type="button" class="btn btn-xs btn-danger" onclick="deletePageImage(<?php echo (int) $track->id; ?>, this);"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                                            <?php  } else if (strlen($track->imgpage) > 4 && file_exists(base_path('ImagesUp/' . $track->imgpage)) && @is_array(getimagesize(base_path('ImagesUp/' . $track->imgpage)))) { ?>
                                                <img src="<?php echo asset('ImagesUp/' . $track->imgpage); ?>" width="50" height="50" />
                                                <button type="button" class="btn btn-xs btn-danger" onclick="deletePageImage(<?php echo (int) $track->id; ?>, this);"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <!--label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Back Cover </label>
                                         <div class="col-sm-9">
                                             <input type="file" id="coverimage" name="coverimage" class="col-xs-10 col-sm-10" accept="image/png, image/gif, image/jpeg">
                                          
                                            <?php
                                            if (!empty($track->pCloudFileID_cover)) {
                                                $page_img = url('/pCloudImgDownload.php?fileID=' . $track->pCloudFileID_cover); ?>
                                                   <img src="<?php echo $page_img; ?>" width="50" height="50" />
                                                  
                                         <?php  } else if (strlen($track->coverimage) > 4 && file_exists(base_path('ImagesUp/' . $track->coverimage)) && @is_array(getimagesize(base_path('ImagesUp/' . $track->coverimage)))) { ?>
                                                 <img src="<?php echo asset('ImagesUp/' . $track->coverimage); ?>" width="50" height="50" />
                                                
                                             <?php } ?>
                                         </div-->
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1">
                                            Available to Members </label>
                                        <div class="col-sm-9">
                                            <div class="radio">
                                                <label>
                                                    <input name="availableMembers" type="radio" class="ace" value="1" <?php if ($track->active == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl"> Yes </span>
                                                </label>
                                                <label>
                                                    <input name="availableMembers" type="radio" class="ace" value="0" <?php if ($track->active == 0) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl"> No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1">
                                            Reviewable </label>
                                        <div class="col-sm-9">
                                            <div class="radio">
                                                <label>
                                                    <input name="reviewable" type="radio" class="ace" value="1" <?php if ($track->review == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl"> Yes </span>
                                                </label>
                                                <label>
                                                    <input name="reviewable" type="radio" class="ace" value="0" <?php if ($track->review == 0) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl"> No</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>



                                    <?php if (0) { ?>

                                        <div style="clear:both;"></div>
                                        <div class="col-sm-6 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                                Graphics Complete </label>
                                            <div class="col-sm-9 checkbox">
                                                <label>
                                                    <input name="graphics" class="ace ace-checkbox-2" type="checkbox" value="1" <?php if ($track->graphicscomplete == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">White
                                                Label Artwork </label>
                                            <div class="col-sm-9 checkbox">
                                                <label>
                                                    <input type="checkbox" id="whiteLabel" name="whiteLabel" class="ace ace-checkbox-2" value="1" <?php if ($track->whitelabel == 1) { ?> checked="checked" <?php } ?> /> <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div style="clear:both;"></div>
                                        <div class="col-sm-6 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Member Preview Available ?</label>
                                            <div class="col-sm-9 checkbox">
                                                <label>
                                                    <input name="memberPreviewAvailable" class="ace ace-checkbox-2" type="checkbox" value="yes" <?php echo ($track->memberPreviewAvailable == 'yes') ? " checked=' checked'" : "" ?>>
                                                    <span class="lbl"></span>
                                                </label>
                                            </div>
                                        </div>

                                    <?php } ?>

                                    <div style="clear:both;"></div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter blue">Contact Details</h3>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Name </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="contact_name" name="contact_name" class="col-xs-8 col-sm-8" value="<?php echo urldecode($track->contact_name); ?> <?php if (empty($track->contact_name)) {
                                                                                                                                                                                        } ?>">

                                        </div>

                                    </div>


                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1"> Email </label>
                                        <div class="col-sm-9">
                                            <input type="email" id="contact_email" name="contact_email" class="col-xs-8 col-sm-8" required value="<?php echo urldecode($track->contact_email); ?> <?php if (empty($track->contact_email)) {
                                                                                                                                                                                                    } ?>">

                                        </div>

                                    </div>
                                    <div class="col-sm-6 form-group"> <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Second Email </label>
                                        <div class="col-sm-9"> <input type="email" id="second_contact_email" name="second_contact_email" class="col-xs-8 col-sm-8" value="<?php echo urldecode($track->feedback1_contact_email); ?> <?php if (empty($track->feedback1_contact_email)) {
                                                                                                                                                                                                                                    } ?>"> </div>
                                    </div>
                                    <div class="col-sm-6 form-group"> <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Third Email </label>
                                        <div class="col-sm-9"> <input type="email" id="third_contact_email" name="third_contact_email" class="col-xs-8 col-sm-8" value="<?php echo urldecode($track->feedback2_contact_email); ?> <?php if (empty($track->third_contact_email)) {
                                                                                                                                                                                                                                    } ?>"> </div>
                                    </div>
                                    <div class="col-sm-6 form-group"> <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Fourth Email </label>
                                        <div class="col-sm-9"> <input type="email" id="fourth_contact_email" name="fourth_contact_email" class="col-xs-8 col-sm-8" value="<?php echo urldecode($track->feedback3_contact_email); ?> <?php if (empty($track->fourth_contact_email)) {
                                                                                                                                                                                                                                    } ?>"> </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="contact_phone" name="contact_phone" class="col-xs-8 col-sm-8" value="<?php echo urldecode($track->contact_phone); ?> <?php if (empty($track->contact_phone)) {
                                                                                                                                                                                        } ?>">

                                        </div>

                                    </div>



                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Relationship to Artist</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="relationship_to_artist" name="relationship_to_artist" class="col-xs-8 col-sm-8" value="<?php echo urldecode($track->relationship_to_artist); ?> <?php if (empty($track->relationship_to_artist)) {
                                                                                                                                                                                                                    } ?>">

                                        </div>

                                    </div>

                                    <div style="clear:both;"></div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter blue">Logos</h3>
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 col-sm-offset-3" style="color:#428bca;">Select
                                            Logo</span>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                                Logos </label>
                                            <div class="col-sm-9">
                                                <?php
                                                //$logoIds = array();
                                                if (strpos($track->logos, ',') >= 0) {
                                                    $logoIds = explode(',', $track->logos);
                                                } else {
                                                    $logoIds[0] = $track->logos;
                                                }
                                                ?>


                                                <style>
                                                    .chosen-container {
                                                        width: 100% !important;
                                                    }
                                                </style>

                                                <select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select-logos col-xs-10 col-sm-10" name="logos[]" style="display:none !important;">
                                                    <?php foreach ($logos['data'] as $logoo) { ?>
                                                        <option <?php if (in_array($logoo->id, $logoIds)) { ?> selected="selected" <?php } ?> value="<?php echo $logoo->id;  ?>">
                                                            <?php echo urldecode($logoo->company); ?></option>
                                                    <?php } ?>
                                                </select>

                                                <?php if (0) { ?>

                                                    <select name="logos[]" size="5" multiple="" class="col-xs-10 col-sm-10" id="logos[]">
                                                        <?php foreach ($logos['data'] as $logoo) { ?>
                                                            <option <?php if (in_array($logoo->id, $logoIds)) { ?> selected="selected" <?php } ?> value="<?php echo $logoo->id;  ?>">
                                                                <?php echo urldecode($logoo->company); ?></option>
                                                        <?php } ?>
                                                    </select>

                                                <?php }

                                                echo "<div class='logos row'>";
                                                if (!empty($logos['data'])) { ?>
                                                    <?php
                                                    //   pArr($logos['data']);die();
                                                    foreach ($logos['data'] as $logoo) {

                                                        if (!in_array($logoo->id, $logoIds)) {
                                                            continue;
                                                        }
                                                    ?>
                                                        <div class="col-auto">
                                                            <div id="<?php echo $logoo->id; ?>">
                                                                <?php
                                                                if (!empty($logoo->product_name)) echo $logoo->product_name;

                                                                if (!empty($logoo->pCloudFileID_logo)) {
                                                                    $artWorkk = url('/pCloudImgDownload.php?fileID=' . $logoo->pCloudFileID_logo);
                                                                } else if (!empty($logoo->img)) {

                                                                    if (file_exists(base_path('ImagesUp/' . $logoo->img))) {

                                                                        $artWorkk = asset('/Logos/' . $logoo->img);
                                                                    } else {
                                                                        $artWorkk = asset('public/images/noimage-avl.jpg');
                                                                    }
                                                                } else {
                                                                    $artWorkk = asset('public/images/noimage-avl.jpg');
                                                                }

                                                                //  $artWorkk = asset('/Logos/'.$logoo->img);
                                                                ?>
                                                                <img src="<?php echo $artWorkk; ?>" width="50" height="56" />
                                                                <?php //echo urldecode($logoo->company);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                echo "</div>";
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 col-sm-offset-3" style="color:#428bca;">Add New</span>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Company </label>
                                            <div class="col-sm-9">
                                                <input type="text" id="logoCompany" name="logoCompany" class="col-xs-10 col-sm-10">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Link </label>
                                            <div class="col-sm-9">
                                                <input type="text" id="logoLink" name="logoLink" class="col-xs-10 col-sm-10">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Logo </label>
                                            <div class="col-sm-9">
                                                <input type="file" id="logoImage" name="logoImage" class="col-xs-10 col-sm-10">
                                            </div>
                                        </div>
                                    </div>

                                    <!--?php if(sizeof($logoIds)>1){ ?>
                                         <div class="col-lg-12 col-md-offset-5">
                                             <a target="_blank" href="<?php //echo url("admin/track_logo_order?tid=" . $track->id); 
                                                                        ?>">Change Logos Order</a>
                                         </div-->
                                    <!--?php } ?-->


                                    <div style="clear:both;"></div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter blue">Artist Information</h3>

                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Video
                                            Link </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="link1" name="video" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->videoURL); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Video Embed
                                            Link </label>
                                        <div class="col-sm-9">
                                            <textarea id="embedlink" placeholder="Video Embed Link (Youtube/Vimeo)" name="embedlink" class="col-xs-10 col-sm-10"><?php if (!empty($track->embedvideoURL)) {
                                                                                                                                                                        echo urldecode($track->embedvideoURL);
                                                                                                                                                                    } ?></textarea>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Website: </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="website" placeholder="Website" name="website" class="col-xs-10 col-sm-10" value="<?php echo urldecode($track->link); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website 1 </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="website1" name="website1" class="col-xs-10 col-sm-10" value="<?php echo $track->link1; ?>" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website 2 </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="website2" name="website2" class="col-xs-10 col-sm-10" value="<?php echo $track->link2; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Facebook </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="facebookLink" name="facebookLink" class="col-xs-10 col-sm-10" value="<?php echo $track->facebookLink; ?>" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Twitter </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="twitterLink" name="twitterLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->twitterLink)) {
                                                                                                                                            echo $track->twitterLink;
                                                                                                                                        } ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Instagram </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="instagramLink" name="instagramLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->instagramLink)) {
                                                                                                                                                echo $track->instagramLink;
                                                                                                                                            } ?>" />
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tik Tok </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="tiktokLink" name="tiktokLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->tiktokLink)) {
                                                                                                                                        echo $track->tiktokLink;
                                                                                                                                    } ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Snapchat </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="snapchatLink" name="snapchatLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->snapchatLink)) {
                                                                                                                                            echo $track->snapchatLink;
                                                                                                                                        } ?>">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Others </label>
                                        <div class="col-sm-9">
                                            <input type="text" id="othersLink" name="othersLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->othersLink)) {
                                                                                                                                        echo $track->othersLink;
                                                                                                                                    } ?>">
                                        </div>
                                    </div>
									
									<div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Apple Music URL </label>
                                        <div class="col-sm-9">
                                            <input type="url" id="applemusicLink" name="applemusicLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->applemusicLink)) { echo $track->applemusicLink; } ?>">
                                        </div>
                                    </div>
									 <div style="clear:both;"></div>
                                    <div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Amazon URL </label>
                                        <div class="col-sm-9">
                                            <input type="url" id="amazonLink" name="amazonLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->amazonLink)) { echo $track->amazonLink; } ?>">
                                        </div>
                                    </div>
									
									<div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Spotify URL </label>
                                        <div class="col-sm-9">
                                            <input type="url" id="spotifyLink" name="spotifyLink" class="col-xs-10 col-sm-10" value="<?php if (isset($track->spotifyLink)) { echo $track->spotifyLink; } ?>">
                                        </div>
                                    </div>
									
									
                                    <!-- <div class="space-24"></div>
                                     <h3 class="header smaller lighter blue">Label Reps</h3>
                                     <div class="col-sm-6">
                                         <table id="simple-table" class="table  table-bordered table-hover">
                                             <thead>
                                                 <tr>
                                                     <th class="center" width="100">
                                                         S. No
                                                     </th>
                                                     <th class="detail-col" width="150">Name</th>
                                                     <th>Email</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <?php
                                                    if (count($trackReps) > 0) {
                                                        $i = 1;
                                                        foreach ($trackReps as $trackRep) {
                                                    ?> <tr>
                                                             <td class="center">
                                                                 <?php echo $i; ?>
                                                             </td>
                                                             <td class="left"><?php echo urldecode($trackRep['name']); ?></td>
                                                             <td class="left"><?php echo urldecode($trackRep['email']); ?></td>
                                                         </tr>
                                                     <?php $i++;
                                                        }
                                                    } else { ?>
                                                     <tr>
                                                         <td colspan="3">No Label Reps</td>
                                                     </tr>
                                                 <?php
                                                    } ?>
                                             </tbody>
                                         </table>
                                     </div> -->
                                    <!-- <div style="clear:both;"></div>
                                     <a href="<?php echo url("tracks/track_label_reps_manage?tid=" . $track->id); ?>" class="btn btn-danger btn-sm"> Attach / Detach a Label Rep </a> -->
                                    <div style="clear:both;"></div>
                                    <div class="space-24"></div>
                                    <h3 class="header smaller lighter blue">Audio Files</h3>
                                    <table id="simple-table" class="table  table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center" width="100">
                                                    S. No
                                                </th>
                                                <th class="detail-col" width="150">Version</th>
                                                <th>Track</th>
                                                <th style="width:5%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            foreach ($audios['data'] as $audio) { ?>
                                                <tr>
                                                    <td class="center">
                                                        <?php echo $i; ?>
                                                    </td>
                                                    <td class="left">
                                                        <?php echo urldecode($audio->version); ?>
                                                    </td>
                                                    <td>
                                                        <audio controls="" style="width:100%;">
                                                            <?php if (strpos($audio->location, '.mp3') !== false) { ?>
                                                                <source src="<?php echo asset('AUDIO/' . $audio->location); ?>" type="audio/mp3">
                                                            <?php } else {
                                                                $fileid = (int) $audio->location;
                                                                $getlink = '';
                                                                if (!empty($fileid)) {
                                                                    $getlink = url('download.php?fileID=' . $fileid);
                                                                } ?>
                                                                <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                                            <?php } ?>
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void()" onclick="deleteRecord1('<?php echo $currentPage . '?tid=' . $_GET['tid'] . '&'; ?>','<?php echo $audio->id; ?>','Confirm delete <?php echo $audio->version; ?> ')" class="btn btn-xs btn-danger">
                                                            <button title="Delete Track" type="button" class="btn btn-xs btn-danger delete_track_edit" id="delete_track_edit" value="<?php //echo $audio->id; 
                                                                                                                                                                                        ?>">
                                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                            </button>
                                                        </a>
                                                        <!-- <a href="" title="Edit Track" class="btn btn-xs btn-info">-->
                                                        <!--<i class="ace-icon fa fa-pencil bigger-120"></i>-->
                                                        <!--</a>-->
                                                    </td>
                                                </tr>
                                            <?php $i++;
                                            } ?>
                                        </tbody>
                                    </table>
                                    <div id="audioFiles">
                                        <a class="btn btn-primary" href="<?php echo url("admin/track_manage_mp3?tid=" . $_GET['tid']); ?>" target="_blank">Add/Update Mp3 versions</a>
                                    </div>
                                    <div style="clear:both;"></div>

                                    <input type="hidden" id="divId" name="divId" value="1" />
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info btn-sm" type="submit" name="updateTrack">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Update Track
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn btn-sm" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.span -->
                    </div><!-- /.row -->
                    <div class="hr hr-18 dotted hr-double"></div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
        <script>
            function change_genre(genreId) {
                $.ajax({
                    url: "add_track?getSubGenres=1&genreId=" + genreId,
                    success: function(result) {
                        var obj = JSON.parse(result);
                        var count = obj.length;
                        var liList = '';
                        var optionList = ''; //'<option value="">What country do you live in</option>';
                        for (var i = 0; i < count; i++) {
                            //		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
                            optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                        }
                        //	 document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
                        document.getElementById('subGenre').innerHTML = optionList;
                    }
                });
            }

            function isValidURL(str) {
                var pattern = new RegExp('^((https?:)?\\/\\/)?' + // protocol
                    '(?:\\S+(?::\\S*)?@)?' + // authentication
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                    '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locater
                if (!pattern.test(str)) {
                    return false;
                } else {
                    return true;
                }
            }

            function validate() {
                var client = document.getElementById('client');
                var company = document.getElementById('company');
                var linkk = document.getElementById('link');
                var moreInfo = document.getElementById('moreInfo');
                var emailImage = document.getElementById('emailImage');
                var pageImage = document.getElementById('pageImage');
                var artist = document.getElementById('artist');
                var title = document.getElementById('title');
                var album = document.getElementById('album');
                var time = document.getElementById('time');
                var link1 = document.getElementById('link1');
                var embedlink = document.getElementById('embedlink');
                var website = document.getElementById('website');
                var website1 = document.getElementById('website1');
                var website2 = document.getElementById('website2');
                var facebook = document.getElementById('facebookLink');
                var twitter = document.getElementById('twitterLink');
                var instagram = document.getElementById('instagramLink');
                var producers = document.getElementById('producers');
                var writer = document.getElementById('writer');
                var numericExp = /^[-+]?[0-9]+$/;
                var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (client.value.length < 1) {
                    //  alert("Please select client!");
                    //  client.focus();
                    //  return false;
                }
                //  if (producers.value.length < 1) {
                //                  alert("Please enter producer(s) name!");
                //                  producers.focus();
                //                  return false;
                //              }
                //  if (writer.value.length < 1) {
                //                  alert("Please enter write name!");
                //                  writer.focus();
                //                  return false;
                //              }
                /*if(company.value.length<1)
                {
                  alert("Please enter company!");
                  company.focus();
                  return false;
                }


                if(linkk.value.length<1)
                {
                  alert("Please enter link!");
                  linkk.focus();
                  return false;
                }

                var n = linkk.value.indexOf(".");


                if(n<1)
                {
                  alert("Please enter link!");
                  linkk.focus();
                  return false;
                }


                if(moreInfo.value.length<1)
                {
                  alert("Please enter more info.!");
                  moreInfo.focus();
                  return false;
                }
                */
                if (artist.value.length < 1) {
                    alert("Please enter artist name!");
                    artist.focus();
                    return false;
                }
                if (title.value.length < 1) {
                    alert("Please enter title!");
                    title.focus();
                    return false;
                }
                if (link1.value.length >= 1) {
                    if (!(isValidURL(link1.value))) {
                        alert("Please enter a valid video link!");
                        link1.focus();
                        return false;
                    }
                }

                if (website.value.length >= 1) {
                    if (!(isValidURL(website.value))) {
                        alert("Please enter a valid website url!");
                        website.focus();
                        return false;
                    }
                }
                if (website1.value.length >= 1) {
                    if (!(isValidURL(website1.value))) {
                        alert("Please enter a valid website1 url!");
                        website1.focus();
                        return false;
                    }
                }
                if (website2.value.length >= 1) {
                    if (!(isValidURL(website2.value))) {
                        alert("Please enter a valid website2 url!");
                        website2.focus();
                        return false;
                    }
                }
                if (facebook.value.length >= 1) {
                    if (!(isValidURL(facebook.value))) {
                        alert("Please enter a valid facebook link!");
                        facebook.focus();
                        return false;
                    }
                }
                if (twitter.value.length >= 1) {
                    if (!(isValidURL(twitter.value))) {
                        alert("Please enter a valid twitter link!");
                        twitter.focus();
                        return false;
                    }
                }
                if (instagram.value.length >= 1) {
                    if (!(isValidURL(instagram.value))) {
                        alert("Please enter a valid instagram link!");
                        instagram.focus();
                        return false;
                    }
                }
                /*	if(album.value.length<1)
                	{
                	  alert("Please enter album!");
                	  album.focus();
                	  return false;
                	}

                	if(time.value.length<1)
                	{
                	  alert("Please enter time!");
                	  time.focus();
                	  return false;
                	}

                	if(link1.value.length<1)
                	{
                	  alert("Please enter link!");
                	  link1.focus();
                	  return false;
                	}


                	var n = link1.value.indexOf(".");


                	if(n<1)
                	{
                	  alert("Please enter link!");
                	  link1.focus();
                	  return false;
                	}

                	if(producers.value.length<1)
                	{
                	  alert("Please enter producers!");
                	  producers.focus();
                	  return false;
                	}
                */
            }

            function removeAudio() {
                var divId = document.getElementById('divId').value;
                if (divId > 1) {
                    var divIdMinus = parseInt(divId) - 1;
                    document.getElementById('divId').value = divIdMinus;
                    $("#html" + divId).remove();
                }
            }

            function deletePageImage(trackId, $this) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    url: "track_edit?deletePageImage=1&tid=" + trackId,
                    type: 'POST',
                    success: function(result) {
                        $($this).siblings('img').remove();
                        $($this).remove();
                    }
                });
                return false;
            }

            function deleteEmailImage(trackId, $this) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    url: "track_edit?deleteEmailImage=1&tid=" + trackId,
                    type: 'POST',
                    success: function(result) {
                        $($this).siblings('img').remove();
                        $($this).remove();
                    }
                });
                return false;
            }

            function moreAudio() {
                var divId = document.getElementById('divId').value;
                var divIdPlus = parseInt(divId) + 1;
                document.getElementById('divId').value = divIdPlus;
                var parentDiv = document.createElement("div");
                parentDiv.setAttribute('id', 'html' + divIdPlus);
                var smDiv1 = document.createElement("div");
                smDiv1.setAttribute('class', 'col-sm-4 form_group');
                var smDiv2 = document.createElement("div");
                smDiv2.setAttribute('class', 'col-sm-9');
                var label1 = document.createElement("label");
                label1.setAttribute('class', 'col-sm-3 control-label no-padding-right');
                var textnode1 = document.createTextNode("Version");
                label1.appendChild(textnode1);
                var input1 = document.createElement("select");
                //  input1.setAttribute('type','select');
                input1.setAttribute('name', 'version' + divIdPlus);
                input1.setAttribute('id', 'version' + divIdPlus);
                input1.setAttribute('class', 'form-control version');
                var option1 = document.createElement("option");
                option1.setAttribute('value', '');
                option1.text = "Version";
                input1.add(option1);
                var option2 = document.createElement("option");
                option2.setAttribute('value', 'Acapella');
                option2.text = "Acapella";
                input1.add(option2);
                var option3 = document.createElement("option");
                option3.setAttribute('value', 'Clean');
                option3.text = "Clean";
                input1.add(option3);
                var option4 = document.createElement("option");
                option4.setAttribute('value', 'Clean Accapella');
                option4.text = "Clean Accapella";
                input1.add(option4);
                var option5 = document.createElement("option");
                option5.setAttribute('value', 'Clean (16 Bar Intro)');
                option5.text = "Clean (16 Bar Intro)";
                input1.add(option5);
                var option6 = document.createElement("option");
                option6.setAttribute('value', 'Dirty');
                option6.text = "Dirty";
                input1.add(option6);
                var option7 = document.createElement("option");
                option7.setAttribute('value', 'Dirty Accapella');
                option7.text = "Dirty Accapella";
                input1.add(option7);
                var option8 = document.createElement("option");
                option8.setAttribute('value', 'Dirty (16 Bar Intro)');
                option8.text = "Dirty (16 Bar Intro)";
                input1.add(option8);
                var option9 = document.createElement("option");
                option9.setAttribute('value', 'Instrumental');
                option9.text = "Instrumental";
                input1.add(option9);
                var option10 = document.createElement("option");
                option10.setAttribute('value', 'Main');
                option10.text = "Main";
                input1.add(option10);
                var option11 = document.createElement("option");
                option11.setAttribute('value', 'TV Track');
                option11.text = "TV Track";
                input1.add(option11);
                smDiv2.appendChild(input1);
                smDiv1.appendChild(label1);
                smDiv1.appendChild(smDiv2);
                var otherDiv = document.createElement("div");
                otherDiv.setAttribute('class', 'col-sm-4 form_group');
                var otherDiv1 = document.createElement("div");
                otherDiv1.setAttribute('class', 'col-sm-9');
                var otherLabel = document.createElement("label");
                otherLabel.setAttribute('class', 'col-sm-3 control-label no-padding-right');
                var otherTextnode = document.createTextNode("Other Version");
                otherLabel.appendChild(otherTextnode);
                var otherInput = document.createElement("input");
                otherInput.setAttribute('type', 'text');
                otherInput.setAttribute('name', 'otherVersion' + divIdPlus);
                otherInput.setAttribute('id', 'otherVersion' + divIdPlus);
                otherInput.setAttribute('class', 'col-xs-10 col-sm-10');
                otherDiv1.appendChild(otherInput);
                otherDiv.appendChild(otherLabel);
                otherDiv.appendChild(otherDiv1);
                var smDiv3 = document.createElement("div");
                smDiv3.setAttribute('class', 'col-sm-4 form_group');
                var smDiv4 = document.createElement("div");
                smDiv4.setAttribute('class', 'col-sm-9');
                var label2 = document.createElement("label");
                label2.setAttribute('class', 'col-sm-3 control-label no-padding-right');
                var textnode2 = document.createTextNode("File");
                label2.appendChild(textnode2);
                var input2 = document.createElement("input");
                input2.setAttribute('type', 'file');
                input2.setAttribute('name', 'audio' + divIdPlus);
                input2.setAttribute('id', 'audio' + divIdPlus);
                input2.setAttribute('class', 'col-xs-10 col-sm-10');
                smDiv4.appendChild(input2);
                smDiv3.appendChild(label2);
                smDiv3.appendChild(smDiv4);
                parentDiv.appendChild(smDiv1);
                parentDiv.appendChild(otherDiv);
                parentDiv.appendChild(smDiv3);
                var clearboth = document.createElement("div");
                clearboth.setAttribute('class', 'clearDiv');
                document.getElementById('audioFiles').appendChild(clearboth);
                document.getElementById('audioFiles').appendChild(parentDiv);
            }
            // function delete_edit_track(){
            //      var delId = document.getElementById('delete_track_edit').value;
            //      alert('delete id = ' + delId);
            // }
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            jQuery(document).ready(function($) {

                $("#contact_name").val($("#contact_name").val().trim());
                $("#artist").val($("#artist").val().trim());
                $("#contact_email").val($("#contact_email").val().trim());
                $("#contact_phone").val($("#contact_phone").val().trim());
                $("#relationship_to_artist").val($("#relationship_to_artist").val().trim());
                $("#second_contact_email").val($("#second_contact_email").val().trim());
                $("#third_contact_email").val($("#third_contact_email").val().trim());
                $("#fourth_contact_email").val($("#fourth_contact_email").val().trim());
				$("#applemusicLink").val($("#applemusicLink").val().trim());
                $("#amazonLink").val($("#amazonLink").val().trim());
                $("#spotifyLink").val($("#spotifyLink").val().trim());

                $(".delete_track_edit").on("click", function() {
                    var delId = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: "/Client_submit_track/deleteEditTrack",
                        data: {
                            delId: delId
                        },
                        dataType: "json",
                        success: function(data) {
                            location.reload();
                            console.log(data);
                        }
                    });
                });
            });
        </script>



        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
        <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />
        <script>
            $(".chosen-select-logos").chosen({
                no_results_text: "Oops, nothing found!"
            })
        </script>

        @endsection