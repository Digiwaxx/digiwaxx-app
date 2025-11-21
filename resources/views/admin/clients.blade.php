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
                    <i class="ace-icon fa fa-users users-icon"></i>
                    Clients

                </li>
            </ul><!-- /.breadcrumb -->
            <!-- #section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
                            <option <?php if (strcmp($sortBy, 'name') == 0) { ?> selected="selected" <?php } ?> value="name">Name</option>
                            <option <?php if (strcmp($sortBy, 'username') == 0) { ?> selected="selected" <?php } ?> value="username">Username</option>
                            <option <?php if (strcmp($sortBy, 'added') == 0) { ?> selected="selected" <?php } ?> value="added">Added</option>
                            <option <?php if (strcmp($sortBy, 'lastLogin') == 0) { ?> selected="selected" <?php } ?> value="lastLogin">Last Login</option>
                            <option <?php if (strcmp($sortBy, 'company') == 0) { ?> selected="selected" <?php } ?> value="company">Company</option>
                            <option <?php if (strcmp($sortBy, 'city') == 0) { ?> selected="selected" <?php } ?> value="city">City</option>
                            <option <?php if (strcmp($sortBy, 'state') == 0) { ?> selected="selected" <?php } ?> value="state">State</option>
                            <option <?php if (strcmp($sortBy, 'zip') == 0) { ?> selected="selected" <?php } ?> value="zip">Zip</option>
                            <option <?php if (strcmp($sortBy, 'email') == 0) { ?> selected="selected" <?php } ?> value="email">Email</option>
                            <option <?php if (strcmp($sortBy, 'phone') == 0) { ?> selected="selected" <?php } ?> value="phone">Phone</option>
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
                    <form class="form-inline searchForm" id="searchForm" method="get">
                        <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
                        <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
                        <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Company</label>
                                <input type="text" class="nav-search-input form-control" id="company" name="company" value="<?php echo $searchCompany; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Username</label>
                                <input type="text" class="nav-search-input form-control" id="username" name="username" value="<?php echo $searchUsername; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Name </label>
                                <input type="text" class="nav-search-input form-control" id="name" name="name" value="<?php echo $searchName; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Email</label>
                                <input type="text" class="nav-search-input form-control" id="email" name="email" value="<?php echo $searchEmail; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Phone</label>
                                <input type="text" class="nav-search-input form-control" id="phone" name="phone" value="<?php echo $searchPhone; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">City</label>
                                <input type="text" class="nav-search-input form-control" id="city" name="city" value="<?php echo $searchCity; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">State</label>
                                <input type="text" class="nav-search-input form-control" id="state" name="state" value="<?php echo $searchState; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Zip</label>
                                <input type="text" class="nav-search-input form-control" id="zip" name="zip" value="<?php echo $searchZip; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Track Name</label>
                                <input type="text" class="nav-search-input form-control" id="track_name" name="track_name" value="<?php echo $searchTrackName; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Label</label>
                                <input type="text" class="nav-search-input form-control" id="label" name="label" value="<?php echo $searchLabel; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Artist</label>
                                <input type="text" class="nav-search-input form-control" id="artist" name="artist" value="<?php echo $searchArtist; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                            <label class="input-group-addon">Genre</label>
                                <select class="nav-search-input form-control" id="genre" name="genre">
                                    <option value="0" <?php echo $searchGenre == 0 ? 'selected' : ''; ?>>Select</option>
                                    <?php 
                                        foreach($genres as $genre) {
                                            ?>
                                            <option value="<?php echo $genre->genreId ?>" <?php echo $searchGenre == $genre->genreId ? 'selected' : ''; ?>><?php echo $genre->genre; ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <input type="submit" value="Search" name="search" />
                            <!-- <input type="button" value="Reset" onclick="searchReset()" /> -->
                            @if($searchCompany != '' || $searchUsername != '' || $searchName != '' || $searchEmail != '' || $searchPhone != '' || $searchCity != '' || $searchState != '' || $searchArtist != '' || $searchLabel != '' || $searchTrackName != '' || $searchZip != '')	
                            <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_clients_listing') }}'" />

                            @else

                            <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                            color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                           
                            @endif 
                        </div>
                    </form>
                </div>
            </div><!-- /.page-header -->
            <div class="space-6"></div>
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                            if (isset($alert_message)) { ?>
                                <div class="<?php echo $alert_class; ?>">
                                    <?php echo $alert_message; ?>
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                </div>
                            <?php } ?>
                            <a href="{{ route('Exportclients_file') }}" class="btn btn-info btn-sm" style="margin-bottom:6px; float:right;">
                                <span class="glyphicon glyphicon-export"></span> Export
                            </a>
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center" width="80">
                                            S. No.
                                        </th>
                                        <th>User Name</th>
                                        <th>Company</th>
                                        <th>Package</th>
                                        <th class="hidden-xs">Contact Name</th>
                                        <th class="hidden-sm hidden-xs">Email</th>
                                        <th class="hidden-sm hidden-xs">Mobile</th>
                                        <th class="hidden-sm hidden-xs">City</th>
                                        <th class="hidden-sm hidden-xs">Country</th>
                                        <th class="hidden-md hidden-sm hidden-xs">Last Log On</th>
                                        <th width="130">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = $start + 1;
                                    if ($clients['numRows'] > 0) {
                                        foreach ($clients['data'] as $client) {
                                    ?>
                                            <tr>
                                                <td class="center">
                                                    <?php echo $i; ?>
                                                </td>
                                                <td>
                                                    <?php echo $client->uname;  
                                                    
                                                    $dt  = $client->added;
                                                        if (strcmp($dt, '0000-00-00 00:00:00') != 0) {
                                                            //	echo '<br />';
                                                            $yr = strval(substr($dt, 0, 4));
                                                            $mo = strval(substr($dt, 5, 2));
                                                            $da = strval(substr($dt, 8, 2));
                                                            $hr = strval(substr($dt, 11, 2));
                                                            $mi = strval(substr($dt, 14, 2));
                                                            $se = strval(substr($dt, 17, 2));
                                                            echo '<p style="margin: 0;font-size: 10px;color: #888;">Added: '.date("m/d/Y", mktime($hr, $mi, 0, $mo, $da, $yr)).'</p>';
                                                        }
                                                    
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo ucfirst(urldecode($client->name));  ?>
                                                </td>
                                                <td><?php echo $client->member_package; ?></td>	
                                                <td class="hidden-xs">
                                                    <?php echo ucfirst(urldecode($client->ccontact)); ?>
                                                </td>
                                                <td class="hidden-sm hidden-xs"><?php echo urldecode($client->email);  ?></td>
                                                <td class="hidden-sm hidden-xs"><?php echo urldecode($client->mobile);  ?></td>
                                                <td class="hidden-sm hidden-xs"><?php echo urldecode($client->city);  ?></td>
                                                <td class="hidden-sm hidden-xs"><?php if($client->country == 1) { } else { echo urldecode($client->country); }  ?></td>
                                                <td class="hidden-md hidden-sm hidden-xs"><?php
                                                        $dt  = $client->lastlogon;
                                                        if (strcmp($dt, '0000-00-00 00:00:00') != 0) {
                                                            //	echo '<br />';
                                                            $yr = strval(substr($dt, 0, 4));
                                                            $mo = strval(substr($dt, 5, 2));
                                                            $da = strval(substr($dt, 8, 2));
                                                            $hr = strval(substr($dt, 11, 2));
                                                            $mi = strval(substr($dt, 14, 2));
                                                            $se = strval(substr($dt, 17, 2));
                                                            echo date("l M/d/Y h:i A", mktime($hr, $mi, 0, $mo, $da, $yr)) . " EST";
                                                        }
                                                        ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="<?php echo url("admin/client_view?cid=" . $client->id); ?>" title="View Client" class="btn btn-xs btn-success">
                                                            <i class="ace-icon fa fa-align-justify bigger-120"></i>
                                                        </a>
                                                        <a href="<?php echo url("admin/client_edit?cid=" . $client->id); ?>" title="Edit Client" class="btn btn-xs btn-info">
                                                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                        </a>
                                                        <a href="<?php echo url("admin/client_change_password?cid=" . $client->id); ?>" title="Reset Password" class="btn btn-xs btn-info">
                                                            <i class="ace-icon fa fa-key bigger-120"></i>
                                                        </a>
                                                        <button title="Delete Client" onclick="deleteRecord('clients','<?php echo $client->id; ?>','Confirm delete <?php echo $client->uname; ?> ')" class="btn btn-xs btn-danger">
                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                        }
                                        if ($numPages > 1) { ?>
                                            <tr>
                                                <td colspan="7">
                                                    <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                                                        <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','1')">
                                                                << </a> </li> <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo - 1; ?>')">
                                                                        < </a> </li> <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
                                                        <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo + 1; ?>')"> > </a></li>
                                                        <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">No Data found.</td>
                                        </tr>
                                    <?php


                                    } ?>
                                </tbody>
                            </table>
                        </div><!-- /.span -->
                    </div><!-- /.row -->
                    <div class="hr hr-18 dotted hr-double"></div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
        <script>
            function get_selected_data() {
                var sortBy = document.getElementById('sortBy').value;
                var sortOrder = document.getElementById('sortOrder').value;
                var numRecords = document.getElementById('numRecords').value;
                var company = document.getElementById('company').value;
                var username = document.getElementById('username').value;
                var name = document.getElementById('name').value;
                var email = document.getElementById('email').value;
                var phone = document.getElementById('phone').value;
                var city = document.getElementById('city').value;
                var state = document.getElementById('state').value;
                var zip = document.getElementById('zip').value;
                window.location = "clients?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords + "&username=" + username + "&name=" + name + "&company=" + company + "&email=" + email + "&phone=" + phone + "&city=" + city + "&state=" + state + "&zip=" + zip;
            }
        </script>

@endsection  