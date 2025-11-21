
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
    Admins
    </li>
    </ul><!-- /.breadcrumb -->
    <!-- #section:basics/content.searchbox -->
    <div class="nav-search" id="nav-search">
    <form class="form-inline" autocomplete="off">
    <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
    <span class="input-icon">
        <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
            <option <?php if(strcmp($sortBy,'name')==0) { ?> selected="selected" <?php } ?>
                value="name">Name</option>
            <option <?php if(strcmp($sortBy,'username')==0) { ?> selected="selected" <?php } ?>
                value="username">Username</option>
            <option <?php if(strcmp($sortBy,'added')==0) { ?> selected="selected" <?php } ?>
                value="added">Added</option>
        </select>
    </span>
    <label class="hidden-md hidden-sm hidden-xs">Order By</label>
    <span class="input-icon">
        <select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
            <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
            <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
        </select>
    </span>
    <label class="hidden-md hidden-sm hidden-xs">No. Records</label>
    <span class="input-icon">
        <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
            <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
            <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
            <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
            <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100
            </option>
            <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500
            </option>
        </select>
    </span>
    </form>
    </div><!-- /.nav-search -->
    <!-- /section:basics/content.searchbox -->
    </div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
    <div class="searchDiv">
    <form class="form-inline searchForm" id="searchForm" action="" method="get" autocomplete="off">
        <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
        <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
        <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
        <div class="search-filters">
            
            <input type="text" placeholder="username" class="nav-search-input" id="username" name="username"
                value="<?php echo $searchUsername; ?>" />
            <input type="text" placeholder="name" class="nav-search-input" id="name" name="name"
                value="<?php echo $searchName; ?>" />
            <input type="submit" value="Search" name="search" />
            <!-- <input type="button" value="Reset" onclick="searchReset()" /> -->
            @if($searchUsername != '' || $searchName != '')	
                            <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_listing') }}'" />

                            @else

                            <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid #52d0f8; border-radius: 4px !important; background-color: #52d0f8; border-color: #52d0f8;
                            color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                           
                            @endif 
        </div>
    </form>
    </div>
    <div class="space-6"></div>

    <!-- PAGE CONTENT BEGINS -->
    <div class="row">
        <div class="col-xs-12">
            <?php if(isset($alertMessage)) { ?>
            <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
            <?php } ?>
             <div class="table-responsive">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" width="100">
                            S. No.
                        </th>
                        <th>User Name</th>
                        <th>Name</th>
                        <th class="hidden-sm hidden-xs">Email</th>
                        <th class="hidden-sm hidden-xs">Role</th>
                        <th class="hidden-md hidden-sm hidden-xs">Last Log On</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            if($members['numRows']>0)
                            { 
                        $i = $start+1;
                        foreach($members['data'] as $member)
                        {
                        
                    
                        
                        ?>
                    <tr>
                        <td class="center">
                            <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
                        </td>
                        <td>
                            <?php echo $member->uname;  ?>
                        </td>
                        <td id="displayName<?php echo $member->id; ?>">
                            <?php echo ucfirst(urldecode($member->name));  ?>
                        </td>
                        <td class="hidden-sm hidden-xs" id="displayEmail<?php echo $member->id; ?>">
                            <?php echo $member->email;  ?>
                        </td>
                        <td class="hidden-sm hidden-xs" id="displayRole<?php echo $member->id; ?>">
                            <?php if($member->user_role==1) { echo 'Super Admin'; } else { echo 'Admin'; }  ?>
                        </td>
                        <td class="hidden-md hidden-sm hidden-xs"><?php													
                            if(strcmp($member->lastlogon,'0000-00-00 00:00:00')!=0)
                            {

                               // echo $member->lastlogon;
                                                            
                            $dt  = $member->lastlogon;
                            //	echo '<br />';
                            $yr=strval(substr($dt,0,4)); 
                            $mo=strval(substr($dt,5,2)); 
                            $da=strval(substr($dt,8,2)); 
                            $hr=strval(substr($dt,11,2)); 
                            $mi=strval(substr($dt,14,2)); 
                            $se=strval(substr($dt,17,2)); 
                            echo date("l M/d/Y h:i A", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";
                            
                            }
                        ?></td>
                        <td>
                            <div class="btn-group">
							<?php if($user_role == 1) { ?>
                                <button title="Edit User" type="button" class="btn btn-xs btn-primary"
                                    data-toggle="modal"
                                    data-target="#editBox<?php echo $member->id; ?>"> <i
                                        class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                </button>
                                <button title="Change Password" type="button"
                                    class="btn btn-xs btn-success" data-toggle="modal"
                                    data-target="#passwordBox<?php echo $member->id; ?>"> <i
                                        class="ace-icon fa fa-key bigger-120"></i>
                                </button>
								<?php } if($user_role == 1) { ?>
                                <button title="Settings" type="button" class="btn btn-xs btn-primary <?php echo $user_role; ?>"
                                    data-toggle="modal"
                                    data-target="#settingsBox<?php echo $member->id; ?>">
                                    <i class="ace-icon fa fa-cog bigger-120"></i>
                                </button>
                                <?php
								}								
								if($user_role == 1) {
								?>
                                <button title="Delete User"
                                    onClick="deleteRecord('listing','<?php echo $member->id; ?>','Confirm delete <?php echo $member->uname; ?> ')"
                                    class="btn btn-xs btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </button>
								<?php } ?>
                            </div>
                            <!-- Modal -->
                            <div id="settingsBox<?php echo $member->id; ?>" class="modal fade in">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header no-padding">
                                            <div class="table-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">
                                                </button>
                                                <?php echo $member->uname;  ?> - Modules
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                        
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php
                                                        $selected_modules = array();
                                                        if(isset($modules[$member->id]))
                                                        {
                                                            foreach($modules[$member->id] as $module)
                                                            {
                                                            $selected_modules[] = $module->moduleId;
                                                            }
                                                        }
                                                    ?>
                                                    <div class="form-group" id="modulesDiv">
                                                        <label for="form-field-1"> Modules </label>
                                                        <div class="flex-list">
                                                            <div class="checkbox"
                                                                style="margin-top:0px;">
                                                                <label
                                                                    for="clients<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="1"
                                                                        id="clients<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(1,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Clients </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="members<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="2"
                                                                        id="members<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(2,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Members </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="tracks<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="3"
                                                                        id="tracks<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(3,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Tracks </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="dj_tools<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="4"
                                                                        id="dj_tools<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(4,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> DJ Tools </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="logos<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="5"
                                                                        id="logos<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(5,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Logos </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="labels<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="6"
                                                                        id="labels<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(6,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Labels </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="mails<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="7"
                                                                        id="mails<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(7,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Mails </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="genres<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="10"
                                                                        id="genres<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(10,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)">
                                                                    <span class="lbl"> Genres </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="subscribers<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="8"
                                                                        id="subscribers<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(8,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Subscribers
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="website_pages<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="9"
                                                                        id="website_pages<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(9,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Website Pages
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="products<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="11"
                                                                        id="products<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(11,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Products </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="countries_states<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="12"
                                                                        id="countries_states<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(12,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Countries &
                                                                        States </span>
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label
                                                                    for="staff_selection<?php echo $member->id;  ?>">
                                                                    <input name="modules[]"
                                                                        class="ace ace-checkbox-2"
                                                                        type="checkbox" value="13"
                                                                        id="staff_selection<?php echo $member->id;  ?>"
                                                                        <?php if(in_array(13,$selected_modules))  { ?>
                                                                        checked="checked" <?php } ?>
                                                                        onclick="change_module(this.id,'<?php echo $member->id; ?>',this.value)" />
                                                                    <span class="lbl"> Staff Selection
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-10"></div>
                                        </div>
                                        <div class="modal-footer no-margin-top">
                                            <button class="btn btn-sm btn-danger pull-left"
                                                data-dismiss="modal">
                                                <i class="ace-icon fa fa-times"></i>
                                                Close
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                            <!-- Modal -->
                            <div id="editBox<?php echo $member->id; ?>" class="modal fade in">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header no-padding">
                                            <div class="table-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">
                                                </button>
                                                <?php echo $member->uname;  ?> - Edit
                                            </div>
                                        </div>
                                        <div class="modal-body no-padding">
                                            <div class="space-10"></div>
                                            <div class="row">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                <span  id="editResponse<?php echo $member->id;  ?>"></span>
                                                    <div class="form-group">

                                                        <label for="exampleInputEmail1">Name</label>
                                                        <input type="text" class="form-control"
                                                            id="name<?php echo $member->id;  ?>"
                                                            placeholder="Name"
                                                            value="<?php echo urldecode($member->name);  ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Email</label>
                                                        <input type="text" class="form-control"
                                                            id="email<?php echo $member->id;  ?>"
                                                            placeholder="Email"
                                                            value="<?php echo urldecode($member->email);  ?>">
                            
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label no-padding-right" for="form-field-1"> Role </label>
                                                        <div class="radio">
                                                            <label for="admin<?php echo $member->id;  ?>">
                                                                <input name="role<?php echo $member->id;  ?>" class="ace" type="radio" value="1" id="admin<?php echo $member->id;  ?>" <?php echo $member->user_role == 1 ? 'checked' : '';  ?>/>
                                                                <span class="lbl"> Super Admin </span>
                                                            </label>
                                                            <label for="super_admin<?php echo $member->id;  ?>">
                                                                <input name="role<?php echo $member->id;  ?>" class="ace" type="radio" value="2" id="super_admin<?php echo $member->id;  ?>" <?php echo $member->user_role == 2 ? 'checked' : '';  ?>/>
                                                                <span class="lbl">Admin </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="editAdmin('<?php echo $member->id;  ?>')">Submit</button>
                                                </div>
                                            </div>
                                            <div class="space-10"></div>
                                        </div>
                                        <div class="modal-footer no-margin-top">
                                            <button class="btn btn-sm btn-danger pull-left"
                                                data-dismiss="modal">
                                                <i class="ace-icon fa fa-times"></i>
                                                Close
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                            <div id="passwordBox<?php echo $member->id; ?>" class="modal fade in">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header no-padding">
                                            <div class="table-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">
                                                </button>
                                                <?php echo $member->uname;  ?> - Change Password
                                            </div>
                                        </div>
                                        <div class="modal-body no-padding">
                                            <div class="space-10"></div>
                                            <div class="row">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Password</label>
                                                        <input type="password" class="form-control"
                                                            id="password1_<?php echo $member->id;  ?>"
                                                            placeholder="Password"><a
                                                            href="javascript:void()"
                                                            onclick="randomPassword('8','<?php echo $member->id;  ?>')">
                                                            Generate </a>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Confirm
                                                            Password</label>
                                                        <input type="password" class="form-control"
                                                            id="password2_<?php echo $member->id;  ?>"
                                                            placeholder="Password">
                                                        <span
                                                            id="passwordResponse<?php echo $member->id;  ?>"></span>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="changePassword('<?php echo $member->id;  ?>')">Submit</button>
                                                </div>
                                            </div>
                                            <div class="space-10"></div>
                                        </div>
                                        <div class="modal-footer no-margin-top">
                                            <button class="btn btn-sm btn-danger pull-left"
                                                data-dismiss="modal">
                                                <i class="ace-icon fa fa-times"></i>
                                                Close
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                        </td>
                    </tr>
                    <?php $i++; } if($numPages>1) { ?>
                    <tr>
                        <td colspan="7">
                            <ul class="pager pager-rounded"
                                style="float:right; margin-bottom:10px; margin-right:10px;">
                                <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()"
                                        onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')">
                                        <i class="fa fa-angle-double-left"></i></a> </li> <li class="<?php echo $preLink; ?>"><a
                                                href="javascript:void()"
                                                onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')">
                                                <i class="fa fa-angle-left"></i> </a> </li> <li> &nbsp; page
                                                    <?php echo $currentPageNo; ?> of
                                                    <?php echo $numPages; ?> &nbsp; </li>
                                <li class="<?php echo $nextLink; ?>"><a href="javascript:void()"
                                        onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')">
                                        <i class="fa fa-angle-right"></i></a></li>
                                <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()"
                                        onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')"><i class="fa fa-angle-double-right"></i></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <?php } } else { ?>
                    <tr>
                        <td colspan="6">No data found.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
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
    var username = document.getElementById('username').value;
    var name = document.getElementById('name').value;
    window.location = "listing?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords +
    "&username=" + username + "&name=" + name;
    }

    function change_module(id, memberId, value) {
    if (document.getElementById(id).checked == true) {
    var type = 1;
    var msg = 'Module Assigned Successfully';
    } else {
    var type = 0;
    var msg = 'Module Removed Successfully';
    }
    $(".processing_loader_gif").show();
    $.ajax({
    url: "listing?moduleId=" + value + "&memberId=" + memberId + "&type=" + type,
    success: function (result) {
         $(".processing_loader_gif").hide();
        alert(msg);
        // document.getElementById('subGenres'+id).innerHTML = result;
    }
    });
    }


    function editAdmin(aId) {
    var name = document.getElementById("name" + aId).value;
    var email = document.getElementById("email" + aId).value;
    var role = document.querySelector('[name="role'+aId+'"]:checked').value;
   // alert(role);
    if (name.length > 1) {
    $.ajax({
        url: "listing?edit=1&aId=" + aId + "&name=" + name + "&email=" + email + "&role=" + role,
        success: function (result) {
            row = JSON.parse(result);
            var responseMessage = '';
            var responseColor = '';
            if (row.response == 1) {
                responseColor = '#090';
                responseMessage = "Record updated successfully !";
                document.getElementById('displayName' + aId).innerHTML = name;
                document.getElementById('displayEmail' + aId).innerHTML = email;
                document.getElementById('displayRole' + aId).innerHTML = (role == 1) ? 'Super Admin' : 'Admin';
            } else {
                responseColor = '#FF0000';
                responseMessage = "Error occured, please try again !";
            }
            document.getElementById('editResponse' + aId).style.color = responseColor;
            document.getElementById('editResponse' + aId).innerHTML = responseMessage;
        }
    });
    } else {
    alert("Enter name");
    document.getElementById("name" + aId).focus();
    }
    }

    function changePassword(aId) {
    var access = 0;
    var password = document.getElementById("password1_" + aId).value;
    var confirmPassword = document.getElementById("password2_" + aId).value;
    if (password.length < 5) {
    alert("Password length should not be less than 6 characters.");
    document.getElementById("password1_" + aId).focus();
    } else if (password !== confirmPassword) {
    alert("Password and confirm password doesnt match.");
    document.getElementById("password1_" + aId).focus();
    } else {
    access = 1;
    }
    if (access == 1) {
    $.ajax({
        url: "listing?changePassword=1&aId=" + aId + "&password=" + password,
        success: function (result) {
            row = JSON.parse(result);
            var responseMessage = '';
            var responseColor = '';
            if (row.response == 1) {
                responseColor = '#090';
                responseMessage = "Password changed successfully !";
            } else {
                responseColor = '#FF0000';
                responseMessage = "Error occured, please try again !";
            }
            document.getElementById('password1_' + aId).value = '';
            document.getElementById('password2_' + aId).value = '';
            document.getElementById('passwordResponse' + aId).style.color = responseColor;
            document.getElementById('passwordResponse' + aId).innerHTML = responseMessage;
        }
    });
    }
    }

    function randomPassword(length, aId) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
    var i = Math.floor(Math.random() * chars.length);
    pass += chars.charAt(i);
    }
    document.getElementById('password1_' + aId).setAttribute('type', 'text');
    document.getElementById('password2_' + aId).setAttribute('type', 'text');
    document.getElementById('password1_' + aId).value = pass;
    document.getElementById('password2_' + aId).value = pass;
    document.getElementById('password1_' + aId).focus();
    //	alert(pass);
    // return pass;
    }
    </script>
    @endsection       