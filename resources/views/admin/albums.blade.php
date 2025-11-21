
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
            Albums
        </li>
    </ul><!-- /.breadcrumb -->
    <!-- #section:basics/content.searchbox -->
    <div class="nav-search" id="nav-search">
        <form class="form-search">
            <label class="hidden-md hidden-sm hidden-xs" style="display:none;">Sort By</label>
            <span class="input-icon" style="display:none;">
                <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
                    <option <?php if (strcmp($sortBy, 'artist') == 0) { ?> selected="selected" <?php } ?> value="artist">Artist</option>
                    <!-- <option <?php if (strcmp($sortBy, 'title') == 0) { ?> selected="selected" <?php } ?> value="title">Title</option> -->
                    <option <?php if (strcmp($sortBy, 'label') == 0) { ?> selected="selected" <?php } ?> value="label">Label</option>
                    <option <?php if (strcmp($sortBy, 'added') == 0) { ?> selected="selected" <?php } ?> value="added">Added</option>
                    <option <?php if (strcmp($sortBy, 'album') == 0) { ?> selected="selected" <?php } ?> value="album">Album</option>
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
                    <option <?php if ($numRecords == 50) { ?> selected="selected" <?php } ?> value="500">50</option>
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
        <div class="searchDiv">
            <form class="form-inline searchForm" id="searchForm" autocomplete="off">
                <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
                <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
                <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
                 <div class="search-filters">
                    <input placeholder="Artist" type="text" class="nav-search-input" id="artist" name="artist" value="<?php echo $searchArtist; ?>" />
                
                <!-- <div class="col-sm-3 col-xs-6">
                    <label>Title</label>
                    <input type="text" class="nav-search-input" id="title" name="title" value="<?php echo $searchTitle; ?>" />
                </div> -->
                <!-- <div class="col-sm-3 col-xs-6">
                    <label>Label</label>
                    <input type="text" class="nav-search-input" id="label" name="label" value="<?php echo $searchLabel; ?>" />
                </div> -->
                
                    <label></label>
                    <input placeholder="Album" type="text" class="nav-search-input" id="album" name="album" value="<?php echo $searchAlbum; ?>" />
               
                <!-- <div class="col-sm-3 col-xs-6">
                    <label>Producer</label>
                    <input type="text" class="nav-search-input" id="producer" name="producer" value="<?php echo $searchProducer; ?>" />
                </div> -->
                
                    <input placeholder="Client" type="text" class="nav-search-input" id="client" name="client" value="<?php echo $searchClient; ?>" />
                
                    <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
                    <input type="submit" value="Search" name="search" />
                    <!-- <input type="button" value="Reset" onclick="searchReset()" /> -->
                    @if($searchClient != '' || $searchAlbum != '' || $searchArtist != '' )	
                            <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_albums_listing') }}'" />

                            @else

                            <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                            color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                           
                            @endif 
                    </div>
            </form>
        </div>
    </div><!-- /.page-header -->

            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    if (isset($alert_message)) {
                    ?>
                        <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
                    <?php
                    }
                    ?>
                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center" width="60">
                                    S. No.
                                </th>
                                <th>Artwork</th>
                                <th class="hidden-xs">Artist</th>
                                <th class="hidden-xs">Album</th>
                                <th>Client</th>
                                <!--<th class="hidden-md hidden-sm hidden-xs">Label</th>
                                            <th class="hidden-md hidden-sm hidden-xs">Time</th>
                                            -->
                                <th class="hidden-md hidden-sm hidden-xs">Added On</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $start + 1;
                            foreach ($tracks as $track) {
                            ?>
                                <tr>
                                    <td class="center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $track->product_name;
                                        if(!empty($track->pCloudFileID)){
                                               $artWork=url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);
                                                 
                                         }
                						 else if (file_exists(base_path('ImagesUp/'.$track->imgpage)) && !empty($track->imgpage)){
											$artWork = asset('ImagesUp/' .$track->imgpage);
										}else{
											$artWork = asset('public/images/noimage-avl.jpg');
										}
                                        ?>
                                        <img src="<?php echo $artWork; ?>" width="50" height="56" /> 
                                    </td>
                                    <td class="hidden-xs"><?php echo urldecode($track->artist);  ?></td>
                                    <td class="hidden-xs">
                                        <?php echo ucfirst(urldecode($track->album));  ?>
                                    </td>
                                    <td><?php echo urldecode($track->uname);  ?></td>
                                    <?php // echo urldecode($track->label);  
                                    ?>
                                    <?php  // echo ucfirst(urldecode($track->time)); 
                                    ?>
                                    <td class="hidden-md hidden-sm hidden-xs"><?php $addedOn = $track->added;
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
                                            <!-- <a href="<?php echo url("admin/track_review?tid=" . $track->id); ?>" title="Track Review" class="btn btn-xs btn-success">
                                                <i class="ace-icon fa fa-align-justify bigger-120"></i>
                                            </a>
                                            <a href="<?php echo url("admin/track_view?tid=" . $track->id); ?>" title="View Track" class="btn btn-xs btn-success">
                                                <i class="fa fa-eye bigger-120" aria-hidden="true"></i>
                                            </a> -->
                                            <a href="<?php echo url("admin/album/edit?aid=" . $track->albumid); ?>" title="Edit Track" class="btn btn-xs btn-info">
                                                <i class="ace-icon fa fa-pencil bigger-120"></i>
                                            </a>
                                            <button title="Delete Track" onclick="deleteRecord('<?php echo ''; ?>','<?php echo $track->albumid; ?>','Are you sure to delete Album?')" class="btn btn-xs btn-danger">
                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                            </button>
                                            <!-- <button title="Settings" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editBox<?php echo $track->id; ?>">
                                                <i class="ace-icon fa fa-cog bigger-120"></i>
                                            </button> -->
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
                                                    <i class="fa fa-angle-double-left"></i></a> </li> <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo - 1; ?>')">
                                                            <i class="fa fa-angle-left"></i></a> </li> <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
                                            <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo + 1; ?>')"> <i class="fa fa-angle-right"></i></a></li>
                                            <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $numPages; ?>')"><i class="fa fa-angle-double-right"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.span -->
            </div><!-- /.row -->
            <div class="hr hr-18 dotted hr-double"></div>
            <!-- PAGE CONTENT ENDS -->s
</div><!-- /.page-content -->
<script>
    function get_selected_data() {
        var sortBy = document.getElementById('sortBy').value;
        var sortOrder = document.getElementById('sortOrder').value;
        var numRecords = document.getElementById('numRecords').value;
        var artist = document.getElementById('artist').value;
        // var title = document.getElementById('title').value;
        // var label = document.getElementById('label').value;
        // var album = document.getElementById('album').value;
        // var producer = document.getElementById('producer').value;
        var client = document.getElementById('client').value;
        window.location = "albums?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords + "&artist=" + artist + "&client=" + client;
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
            success: function(result) {
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
            success: function(result) {
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
</script>


@endsection   