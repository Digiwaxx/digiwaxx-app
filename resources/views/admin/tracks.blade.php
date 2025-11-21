@extends('admin.admin_dashboard_active_sidebar')
    @section('content')
 
<div class="main-content">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {}
            </script> 
            <ul class="breadcrumb">
                <li class="active">
                    <i class="ace-icon fa fa-list list-icon"></i>
                    Tracks
                </li>
            </ul><!-- /.breadcrumb --> 
            <!-- #section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
                            <option>Select Order</option>
                            <option <?php if (strcmp($sortBy, 'artist') == 0) { ?> selected="selected" <?php } ?> value="artist">Artist</option>
                            <option <?php if (strcmp($sortBy, 'title') == 0) { ?> selected="selected" <?php } ?> value="title">Title</option>
                            <option <?php if (strcmp($sortBy, 'label') == 0) { ?> selected="selected" <?php } ?> value="label">Label</option>
                            <option <?php if (strcmp($sortBy, 'added') == 0) { ?> selected="selected" <?php } ?> value="added">Added</option>
                            <option <?php if (strcmp($sortBy, 'album') == 0) { ?> selected="selected" <?php } ?> value="album">Album</option>
                            <option <?php if (strcmp($sortBy, 'custom') == 0) { ?> selected="selected" <?php } ?> value="custom">Custom Order(Drag)</option>
                            <option <?php if (strcmp($sortBy, 'trackLength') == 0) { ?> selected="selected" <?php } ?> value="trackLength">Track Length</option>
                            <option <?php if (strcmp($sortBy, 'producers') == 0) { ?> selected="selected" <?php } ?> value="producers">Producers</option>
                            <option <?php if (strcmp($sortBy, 'client') == 0) { ?> selected="selected" <?php } ?> value="client">Client</option>
                            <option <?php if (strcmp($sortBy, 'paid') == 0) { ?> selected="selected" <?php } ?> value="paid">Paid</option>
                            <option <?php if (strcmp($sortBy, 'invoiced') == 0) { ?> selected="selected" <?php } ?> value="invoiced">Invoiced</option>
                            <option <?php if (strcmp($sortBy, 'graphicsCompleted') == 0) { ?> selected="selected" <?php } ?> value="graphicsCompleted">Graphics Completed</option>
                        </select>
                    </span>
                    <span class="input-icon">
                        <label class="hidden-md hidden-sm hidden-xs"> Order By</label>
                        <select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
                            <option <?php if ($sortOrder == 1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
                            <option <?php if ($sortOrder == 2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
                        </select>
                    </span>
                    <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
                            <option <?php if ($numRecords == 10) { ?> selected="selected" <?php } ?> value="10">10</option>
                            <option <?php if ($numRecords == 30) { ?> selected="selected" <?php } ?> value="30">30</option>
                            <option <?php if ($numRecords == 50) { ?> selected="selected" <?php } ?> value="50">50</option>
                            <option <?php if ($numRecords == 100) { ?> selected="selected" <?php } ?> value="100">100</option>
                            <option <?php if ($numRecords == 500) { ?> selected="selected" <?php } ?> value="500">500</option>
                        </select>
                    </span>
                </form>
            </div><!-- /.nav-search -->
            <!-- /section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
            <div class="row">
                <div class="searchDiv">
                    <form class="form-inline searchForm" id="searchForm" >
                        <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
                        <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
                        <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Artist</label>
                                <input type="text" class="nav-search-input form-control ui-autocomplete-input" id="artist" name="artist" value="<?php echo $searchArtist; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Title</label>
                                <input type="text" class="nav-search-input form-control ui-autocomplete-input" id="title" name="title" value="<?php echo $searchTitle; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Label</label>
                                <input type="text" class="nav-search-input form-control ui-autocomplete-input" id="label" name="label" value="<?php echo $searchLabel; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Album</label>
                                <input type="text" class="nav-search-input form-control ui-autocomplete-input" id="album" name="album" value="<?php echo $searchAlbum; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Producer</label>
                                <input type="text" class="nav-search-input form-control ui-autocomplete-input" id="producer" name="producer" value="<?php echo $searchProducer; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Client</label>
                                <select id="client" name="client" class="form-control">
                                    <option value="0" <?php (int) $searchClient == 0 ? 'selected="selected"' : ''; ?>>---All Clients---</option>
                                    <?php
                                        foreach($clients['data'] as $client) {
                                            ?>
                                            <option value="<?php echo $client->id; ?>" <?php echo $client->id == $searchClient ? 'selected="selected"' : ''; ?>><?php echo urldecode($client->name); ?></option>
                                            <?php
                                        }
                                    ?>                                
                                    <option value="WY">Wyoming</option>
                                </select>
                                <!-- <input type="text" class="nav-search-input" id="client" name="client" value="<?php echo $searchClient; ?>" /> -->
                            </div>
                        </div>
                         <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Members</label>
                                <select id="member" name="member" class="form-control">
                                    <option value="0" <?php (int) $searchmember == 0 ? 'selected="selected"' : ''; ?>>---All Members---</option>
                                    <?php
                                        foreach($members['mem_data'] as $client) {
                                            ?>
                                            <option value="<?php echo $client->id; ?>" <?php echo $client->id == $searchmember ? 'selected="selected"' : ''; ?>><?php echo urldecode($client->fname); ?></option>
                                            <?php
                                        }
                                    ?>                                
                                    <option value="WY">Wyoming</option>
                                </select>
                                <!-- <input type="text" class="nav-search-input" id="client" name="client" value="<?php echo $searchClient; ?>" /> -->
                            </div>
                        </div>
                        
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                             <div class="input-group">
                                <label class="input-group-addon">Status</label>
                              <select name="status" id="status" class="form-control">
                                  <option value="">Status</option>
                                 <option <?php if($searchStatus =='draft') { echo "selected"; } ?> value="draft">Draft</option>
                                 <option <?php if($searchStatus =='publish') { echo "selected"; } ?> value="publish">Publish</option>
                                 <option <?php if($searchStatus =='unpublish') { echo "selected"; } ?> value="unpublish">Unpublish</option>
                              </select>
                              </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">

                            <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
                            <input type="submit" value="Search" name="search" />
                            
                            @if($searchStatus != '' || $searchClient != '' || $searchProducer != '' || $searchAlbum != '' || $searchLabel != '' || $searchTitle != '' || $searchArtist != '' )	
                            <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_tracks_listing') }}'" />

                            @else

                            <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                            color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                           
                            @endif 
                        </div>
                    </form>
                </div>
            </div><!-- /.page-header -->
            <div class="space-10"></div>
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <?php
                            if (isset($alert_message)) {
                            ?>
                                <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
                            <?php
                            }
                            ?>
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" width="60">S. No.</th>
                                        <th>Artwork</th>
                                        <th>Artist</th>
                                        <th>Title</th>
                                        <th>Album</th>
                                        <th>Usert Type</th>
                                        <th>Submitted By</th>
                                        <th>Label</th>
                                        <th>Producer</th>
                                        <!-- <th class="hidden-md hidden-sm hidden-xs">Time</th> -->
                                        <th>Added On</th>
                                        <th width="160">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <?php
                                    $i = $start + 1;
                                    
                                     //echo '<pre/>'; print_r($tracks); exit;    
                                    
                                    foreach ($tracks as $track) {
                                        // echo '<pre/>'; print_r($track); exit;
                                    ?>
                                        <tr data-id="<?php echo $track->id; ?>">
                                            <td class="center">
                                                <?php echo $i; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if(!empty($track->pCloudFileID)){
                                                    
                                                    $artWork = url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);
                                                }
												else if(!empty($track->imgpage) && file_exists(base_path('ImagesUp/' .$track->imgpage))){
													$artWork = asset('ImagesUp/' .$track->imgpage);
												}
                                                else if(!empty($track->album_page_image) && file_exists(base_path('ImagesUp/' .$track->album_page_image))){
													$artWork = asset('ImagesUp/' .$track->album_page_image);
												}
                                                else{
													$artWork = asset('public/images/noimage-avl.jpg');
												}
												//$artWork = asset('ImagesUp/' .$track->imgpage);
                                                ?>
                                                <a href="<?php echo url("admin/track_edit?tid=" . $track->id); ?>" data-updatedUri="{{route('edit_track', ['id'=>Crypt::encryptString($track->id)]) }}" title="Edit Track">
                                                    <img src="<?php echo $artWork; ?>" width="100" height="106" alt="<?php echo urldecode($track->title); ?>"/>
                                                </a>
                                            </td>
                                            <td><?php echo urldecode($track->artist);  ?></td>
                                            <td><?php echo urldecode($track->title); ?>
                                                <?php //$versions = array_filter(array_column($track->mp3s['data'], 'version'));
                                                //echo !empty($versions) ? '<br/>'.urldecode('[Versions: ' . implode(' | ', $versions) . ']') : ''; ?>
                                                <?php 
                                                    $versions = array_filter(array_column($track->mp3s['data'], 'version'));
                                                    if (!empty($versions)) {
                                                        echo '<br/>[Versions:<br/>';
                                                        foreach ($versions as $index => $version) {
                                                            echo ($index + 1) . '. ' . ($version) . '<br/>';
                                                        }
                                                        echo ']';
                                                    } 
                                                    ?>
                                            </td>
                                            <td>
                                                <?php echo stripslashes(ucfirst(urldecode($track->album)));  ?>
                                            </td>
                                            <td><?php if(!empty($track->name)){echo "CLIENT";} if(!empty($track->fname)){echo "MEMBER";}?></td>
                                            <td><?php if(!empty($track->name)){echo urldecode($track->name);} if(!empty($track->fname)){echo urldecode($track->fname);}?></td>
                                            <td><?php echo urldecode($track->label); ?></td>
                                            <td><?php echo stripslashes(urldecode($track->producer)); ?></td>
                                            <?php  // echo ucfirst(urldecode($track->time)); 
                                            ?>
                                            <td><?php $addedOn = $track->added;
                                                $addedOn = explode(' ', $track->added);
                                                $addedDate =  explode('-', $addedOn[0]);
                                                $addedDate = $addedDate[2] . '-' . $addedDate[1] . '-' . $addedDate[0];
                                                echo $addedDate;
                                                ?></td>
                                            <?php
                                            /*
                                                        $paidStatus = 'No';
                                                        if($track->paid==1)
                                                        {
                                                        $paidStatus = 'Yes';
                                                        }
                                                        echo $paidStatus;
                                                        $invoicedStatus = 'No';
                                                        if($track->invoiced==1)
                                                        {
                                                        $invoicedStatus = 'Yes';
                                                        }
                                                        echo $invoicedStatus;
                                                        
                                                        $graphicsCompleteStatus = 'No';
                                                        if($track->graphicscomplete==1)
                                                        {
                                                        $graphicsCompleteStatus = 'Yes';
                                                        }
                                                        echo $graphicsCompleteStatus;
                                                        */
                                            ?>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo url("admin/track_review?tid=" . $track->id); ?>" title="Track Review" class="btn btn-xs btn-success">
                                                        <i class="ace-icon fa fa-align-justify bigger-120"></i>
                                                    </a>
                                                    <a href="<?php echo url("admin/track_view?tid=" . $track->id); ?>" title="View Track" class="btn btn-xs btn-success">
                                                        <i class="fa fa-eye bigger-120" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="<?php echo url("admin/track_edit?tid=" . $track->id); ?>" data-updatedUri="{{route('edit_track', ['id'=>Crypt::encryptString($track->id)]) }}" title="Edit Track" class="btn btn-xs btn-info">
                                                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                    </a>
                                                    <button title="Delete Track" onclick="deleteRecord('<?php echo $currentPage; ?>','<?php echo $track->id; ?>','Are you sure you want to delete this Track? <?php // echo urldecode($track->title); 
                                                                                                                                                                                    ?> ')" class="btn btn-xs btn-danger">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    </button>
                                                    <button title="Settings" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editBox<?php echo $track->id; ?>">
                                                        <i class="ace-icon fa fa-cog bigger-120"></i>
                                                    </button>

                                                    <div class="top_streaming_div">
                                                        <input type="checkbox" id="stream-<?php echo $track->id; ?>" name="top_streaming" <?php if ($track->top_streaming) {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?> class="float-left stream-<?php echo $track->id; ?>" onchange="top_streaming(<?php echo $track->id; ?>);">
                                                        <label for="top_streaming">Add to Top Streaming</label>
                                                    </div>
                                                    
                                                    
                                                    <div class="hottest_div" style="clear: both;">
                                                        <input type="checkbox" id="hottest-<?php echo $track->id; ?>" name="hottest" 
                                                        <?php if ($track->hottest) { echo "checked"; } ?> 
                                                        class="float-left hottest-<?php echo $track->id; ?>" onchange="hottest(<?php echo $track->id; ?>);">
                                                        <label for="hottest">Add to Hottest tracks</label>
                                                    </div>
                                                    
                                                    <div class="priority_div" style="clear: both;">
                                                        <input type="checkbox" id="priority-<?php echo $track->id; ?>" name="priority" 
                                                        <?php if ($track->priority) { echo "checked"; } ?> 
                                                        class="float-left priority-<?php echo $track->id; ?>" onchange="priority(<?php echo $track->id; ?>);">
                                                        <label for="priority">Add to Priority tracks</label>
                                                    </div>
                                                    
                                                    

                                                </div>
                                                <div id="editBox<?php echo $track->id; ?>" class="modal fade in">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header no-padding">
                                                                <div class="table-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                    </button>
                                                                    Choose Section
                                                                </div>
                                                            </div>
                                                            <div class="modal-body no-padding">
                                                                <div class="space-10"></div>
                                                                <div class="row">
                                                                    <div class="col-sm-8 col-sm-offset-2">
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input name="form-field-checkbox" class="ace ace-checkbox-2" type="checkbox" id="staff<?php echo $track->id;  ?>" <?php if (in_array($track->id, $staffSelection)) { ?> checked="checked" <?php } ?> onclick="selectStaff('<?php echo $track->id;  ?>')">
                                                                                <span class="lbl"> STAFF PICKS </span>
                                                                            </label>
                                                                            <span id="staffResponse<?php echo $track->id;  ?>"></span>
                                                                        </div>
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input name="form-field-checkbox" class="ace ace-checkbox-2" type="checkbox" id="you<?php echo $track->id;  ?>" <?php if (in_array($track->id, $youSelection)) { ?> checked="checked" <?php } ?> onclick="selectYou('<?php echo $track->id;  ?>')">
                                                                                <span class="lbl"> SELECTED FOR YOU</span>
                                                                            </label>
                                                                            <span id="youResponse<?php echo $track->id;  ?>"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="space-10"></div>
                                                            </div>
                                                            <div class="modal-footer no-margin-top">
                                                                <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                                                                    <i class="ace-icon fa fa-times"></i>
                                                                    Close
                                                                </button>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $i++;
                                    }
                                    if ($numPages > 1) { ?>
                                        <tr>
                                            <td colspan="10">
                                                <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                                                    <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','1')">
                                                            << </a> </li> <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo - 1; ?>')">
                                                                    < </a> </li> <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
                                                    <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo + 1; ?>')"> > </a></li>
                                                    <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                            <?php if ($numPages < 1) {  echo "No Record Found!"; } ?> 
                        </div><!-- /.span -->
                    </div><!-- /.row -->
                    <div class="hr hr-18 dotted hr-double"></div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>        
        <script>
            // function get_selected_data() {
            //     var sortBy = document.getElementById('sortBy').value;
            //     var sortOrder = document.getElementById('sortOrder').value;
            //     var numRecords = document.getElementById('numRecords').value;
            //     var artist = document.getElementById('artist').value;
            //     var title = document.getElementById('title').value;
            //     var label = document.getElementById('label').value;
            //     var album = document.getElementById('album').value;
            //     var producer = document.getElementById('producer').value;
            //     var client = document.getElementById('client').value;
            //     var member=document.getElementById('member').value;
            //     window.location = "tracks?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords + "&artist=" + artist + "&title=" + title + "&label=" + label + "&album=" + album + "&producer=" + producer + "&client=" + client + "&member=" +member;
            // }
            function get_selected_data() {
                var params = [];

                var sortBy = document.getElementById('sortBy').value;
                if (sortBy && sortBy !== "Select Order") {
                    params.push("sortBy=" + encodeURIComponent(sortBy));
                }

                var sortOrder = document.getElementById('sortOrder').value;
                if (sortOrder) {
                    params.push("sortOrder=" + encodeURIComponent(sortOrder));
                }

                var numRecords = document.getElementById('numRecords').value;
                if (numRecords) {
                    params.push("numRecords=" + encodeURIComponent(numRecords));
                }

                var artist = document.getElementById('artist').value;
                if (artist) {
                    params.push("artist=" + encodeURIComponent(artist));
                }

                var title = document.getElementById('title').value;
                if (title) {
                    params.push("title=" + encodeURIComponent(title));
                }

                var label = document.getElementById('label').value;
                if (label) {
                    params.push("label=" + encodeURIComponent(label));
                }

                var album = document.getElementById('album').value;
                if (album) {
                    params.push("album=" + encodeURIComponent(album));
                }

                var producer = document.getElementById('producer').value;
                if (producer) {
                    params.push("producer=" + encodeURIComponent(producer));
                }

                var client = document.getElementById('client').value;
                if (client) {
                    params.push("client=" + encodeURIComponent(client));
                }

                var member = document.getElementById('member').value;
                if (member) {
                    params.push("member=" + encodeURIComponent(member));
                }

                var queryString = params.length > 0 ? "?" + params.join("&") : "";
                window.location = "tracks" + queryString;
            }

            function selectStaff(trackId) {
                var status = 0;
                if (document.getElementById('staff' + trackId).checked == true) {
                    status = 1;
                } else {
                    status = 0;
                }
                $.ajax({
                    url: "tracks?choosen=1&section=1&trackId=" + trackId + "&status=" + status,
                    beforeSend: function() {
                        // setting a loader
                        $('.processing_loader_gif').show();
                    },
                    success: function(result) {
                        $('.processing_loader_gif').hide();
                        row = JSON.parse(result);
                        var responseMessage = '';
                        var responseColor = '';
                        if (row.response == 1) {
                            responseColor = '#090';
                        } else {
                            responseColor = '#FF0000';
                        }
                        document.getElementById('staffResponse' + trackId).style.color = responseColor;
                        document.getElementById('staffResponse' + trackId).innerHTML = row.message;
                    }
                });
            }

            function selectYou(trackId) {
                var status = 0;
                if (document.getElementById('you' + trackId).checked == true) {
                    status = 1;
                } else {
                    status = 0;
                }
                $.ajax({
                    url: "tracks?choosen=1&section=2&trackId=" + trackId + "&status=" + status,
                    beforeSend: function() {
                        // setting a loader
                        $('.processing_loader_gif').show();
                    },
                    success: function(result) {
                        $('.processing_loader_gif').hide();
                        row = JSON.parse(result);
                        var responseMessage = '';
                        var responseColor = '';
                        if (row.response == 1) {
                            responseColor = '#090';
                        } else {
                            responseColor = '#FF0000';
                        }
                        document.getElementById('youResponse' + trackId).style.color = responseColor;
                        document.getElementById('youResponse' + trackId).innerHTML = row.message;
                    }
                });
            }

            function top_streaming(trackId) {
                $.ajax({
                    url: "tracks?check=" + document.getElementById('stream-' + trackId).checked + "&setting_top_streaming=1&trackId=" + trackId,
                    beforeSend: function() {
                        // setting a loader
                        $('.processing_loader_gif').show();
                    },
                    success: function(result) {
                        // console.log(result);
                        $('.processing_loader_gif').hide();
                    }
                });
            }
            
            function hottest(trackId) {
                $.ajax({
                    url: "tracks?check=" + document.getElementById('hottest-' + trackId).checked + "&setting_hottest=1&trackId=" + trackId,
                    beforeSend: function() {
                        // setting a loader
                        $('.processing_loader_gif').show();
                    },
                    success: function(result) {
                        // console.log(result);
                        $('.processing_loader_gif').hide();
                    }
                });
            }
            
            function priority(trackId) {
                $.ajax({
                    url: "tracks?check=" + document.getElementById('priority-' + trackId).checked + "&setting_priority=1&trackId=" + trackId,
                    beforeSend: function() {
                        // setting a loader
                        $('.processing_loader_gif').show();
                    },
                    success: function(result) {
                        // console.log(result);
                        $('.processing_loader_gif').hide();
                    }
                });
            }
            

            window.onload = function() {
                $('#client, #member').select2();
                $('#client , #member').change(function() {
                    $('#searchForm').submit()
                });
            };
            
   

jQuery( "#artist" ).autocomplete({
      source: "<?php echo url("Member_autocomplete");?>",
      response: function(event, ui) {
           event.preventDefault();
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                // jQuery("#artist").val('');
            
            } else {
              
            }
        }
 });  
 
 jQuery( "#label" ).autocomplete({
      source: "<?php echo url("Member_autocomplete_label");?>",
      response: function(event, ui) {
           event.preventDefault();
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                // jQuery("#label").val('');
            
            } else {
              
            }
        }
 }); 
 
  jQuery( "#title" ).autocomplete({
      source: "<?php echo url("Member_autocomplete_title");?>",
      response: function(event, ui) {
           event.preventDefault();
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                // jQuery("#title").val('');
            
            } else {
              
            }
        }
 }); 
 
 jQuery( "#album" ).autocomplete({
      source: "<?php echo url("Member_autocomplete_album");?>",
      response: function(event, ui) {
           event.preventDefault();
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                // jQuery("#album").val('');
            
            } else {
              
            }
        }
 }); 
 
 
 jQuery( "#producer" ).autocomplete({
      source: "<?php echo url("Member_autocomplete_producer");?>",
      response: function(event, ui) {
           event.preventDefault();
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                // jQuery("#producer").val('');
            
            } else {
              
            }
        }
 }); 
 
$("#sample-table-1 tbody").sortable({
    revert: true,
    start: function(event, ui) {
        var start_pos = ui.item.index();
        
        ui.item.data('start_pos', start_pos);
    },
    update: function (event, ui) {
        var start_pos = ui.item.data('start_pos');
        var end_pos = ui.item.index();
        // console.log(start_pos+1,end_pos+1);
        var attr_data_id = $(ui.item).attr('data-id');
        var from = start_pos;
        var to = end_pos;
        drag_order(from,to,attr_data_id);
    },
    stop: function(event, ui) {
        if(!ui.item.data('tag') && !ui.item.data('handle')) {
            ui.item.data('tag', true);
            // ui.item.fadeTo(400, 0.1);
        }
    }
});
function drag_order(from,to,attr_data_id){
    let searchParams = new URLSearchParams(window.location.search);
    if(searchParams.has('numRecords')){
        var limit = searchParams.get('numRecords');
    }else{
        var limit = 10;
    }
    var id_array = {};
    
    
    
    if(!searchParams.has('page')){
        page=1;
        var i=1;
        // console.log(attr,from,to);
        var off_from = from;
        var off_to = to;
        
        $('.ui-sortable tr').each(function(){
            var at = $(this).attr('data-id');
            id_array[i] = at;
            i++;
        })
        drag_ajax(id_array,off_from,off_to,attr_data_id,page,limit);
    }else{
        let page = searchParams.get('page');
        var offset = (page-1)*limit;
        var off_from = offset + from;
        var off_to = offset + to;
        // console.log(attr,offset + from,offset + to);
        // var off_from = from;
        // var off_to = to;
        var i=offset+1;
        $('.ui-sortable tr').each(function(){
            var at = $(this).attr('data-id');
            id_array[i] = at;
            i++;
        })
        // console.log(id_array);
        drag_ajax(id_array,off_from,off_to,attr_data_id,page,limit);
        
    }

}

function drag_ajax(id_array,off_from,off_to,attr_data_id,page,limit){
    
    var CSRF_TOKEN = $('#csrf-token').val();
    // console.log(CSRF_TOKEN);
    $.ajax({
        
        url: 'tracks_sort',
        type: 'POST',
        
        data: {_token: CSRF_TOKEN,array_id:id_array,dragged_from:off_from,dragged_to:off_to,dragged_data_id:attr_data_id,page:page,limit:limit},
        dataType: 'JSON',
        beforeSend: function() {
            // setting a loader
            $('.processing_loader_gif').show();
        },
        success: function (data) { 
            $('.processing_loader_gif').hide();
            console.log(data);
        }
    }); 
}
 </script>    

   @endsection       