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
                <li class="active"><i class="ace-icon fa fa-list list-icon"></i> USER PACKAGES</li>
            </ul>
            <!--/.breadcrumb -->
            <!--#section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search">
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
            </div>
            <!--/.nav-search -->
            <!--/section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
            <div class="row">
                <div class="searchDiv">
                    <form class="form-inline searchForm" id="searchForm" >
                        <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
                        <div class="col-sm-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon">Search by User</label>
                                <input type="text" class="nav-search-input form-control ui-autocomplete-input" id="user" name="user" value="<?php if(!empty($searchUser)){ echo $searchUser;} ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-3 col-xs-12">

                            <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
                            <input type="submit" value="Search" name="search" />

                           <?php  if(!empty($searchUser)){	?>
                            <input type="button" value="Reset" onclick="window.location.href='{{ route('user_packages_details') }}'" />

                            <?php }else{ ?>

                            <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                            color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                           
                            <?php } ?>
                           
                        </div>
                    </form>
                </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">

                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="100">
                                        S. No.
                                    </th>
                                    <th>User Name</th>
                                    <th>User Type</th>
                                    <th>Package Type</th>
                                    <th>Package Price</th>
                                    <th>Payment Status</th>
                                    
                                    <th>Package Start Date</th>
                                    <th>Package Expiry Date</th>
                                    <th width="160">Action</th>
                                    <?php $count_announ = 1; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                          <?php //echo '<pre>'; print_r($user_details); die; 
                                foreach ($user_details as $value) { 
                                    
                                    // if($value->package_name == 'Basic'){
                                ?>
                                    <tr>
                                        <td><?php echo $count_announ;
                                            $count_announ++; ?></td>
                                        <td><?php echo $value->user_name; ?> </td>
                                        <td><?php if($value->user_type == 1){echo 'Member';}else{echo 'Client';} ?></td>
                                        <td><?php echo $value->package_type; ?></td>
                                        <td><?php echo  '$'.$value->payment_amount; ?></td>
                                        <td><?php  if($value->payment_status == 0){echo 'Not Paid';}else{ echo 'Paid';} ?></td>
                                        
                                        <td><?php $date = new DateTime($value->package_start_date);
                                            $result = $date->format('d M Y');
                                        
                                        echo $result; ?></td>
                                        <td><?php $date = new DateTime($value->package_expiry_date);
                                            $result = $date->format('d M Y');
                                             echo $result; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a target="_blank" href="<?php echo url('admin/view_single_user_package?p_id='.$value->id) ?>" title="View Details" class="btn btn-xs btn-info">
                                                    <i class="fa fa-eye bigger-120" aria-hidden="true"></i>
                                                </a>
                                            
                                            </div>
                                        </td>

                                    </tr>
                                <?php }
                                // }
                                ?>

                                <tr>
                                    <td colspan="9">
                                        <?php //dd($firstPageLink) ?>
                                       <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                                          <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
                                          <li class="<?php echo $preLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                                          <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                                          <li class="<?php echo $nextLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                                          <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
                                       </ul>
            
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--     	<?php //if($numPages>1) { 
                                    ?>-->

                    <!--<?php //} 
                        ?>-->
                </div>
                <!-- /.span -->
            </div>
            <!-- /.row -->

            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
        <!-- /.page-content -->
    <script>
   // no of Records            
    function get_selected_data() {
        let searchParams = new URLSearchParams(window.location.search);
        if(searchParams.has('page')){
            var page = searchParams.get('page');
        }
        
        var user = $('#user').val();
        
        var numRecords = $('#numRecords').val();
        window.location ="user_packages_details?page=" + page + "&numRecords=" + numRecords + "&user=" + user;
    }
    </script>
@endsection