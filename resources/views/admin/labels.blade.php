 @extends('admin.admin_dashboard_active_sidebar')
    @section('content')

<div class="main-content">

<?php
echo "<pre style='display:none;' class='sgtech'>"; print_r($labels); echo "</pre>";
?>

<div class="main-content-inner">
<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
    <script type="text/javascript">
        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
    </script>

    <ul class="breadcrumb">
        <li class="active">
            <i class="ace-icon fa fa-list list-icon"></i>
            Labels
        </li>

    </ul><!-- /.breadcrumb -->
    
    
    
    <!-- #section:basics/content.searchbox -->
    <div class="nav-search" id="nav-search">
        <form class="form-search">
        
        <label class="hidden-md hidden-sm hidden-xs" style="display:none;">Sort By</label>
            <span class="input-icon" style="display:none;">
            <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
            <option <?php if(strcmp($sortBy,'tittle')==0) { ?> selected="selected" <?php } ?> value="tittle">Tittle</option>
            <option <?php if(strcmp($sortBy,'name')==0) { ?> selected="selected" <?php } ?> value="name">Name</option>
            <option <?php if(strcmp($sortBy,'email')==0) { ?> selected="selected" <?php } ?> value="email">Email</option>
            <option <?php if(strcmp($sortBy,'client')==0) { ?> selected="selected" <?php } ?> value="client">Client</option>
            <option <?php if(strcmp($sortBy,'added')==0) { ?> selected="selected" <?php } ?> value="added">Added</option>
                </select>
            
            </span>
            
            <span class="input-icon">
            <label class="hidden-md hidden-sm hidden-xs">Order By</label>
                <select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
                <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
                <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
                </select>
                
            </span>
        
            <span class="input-icon">
            <label class="hidden-md hidden-sm hidden-xs">No. Records</label>
            <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
            <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
            <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
            <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
            <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
            <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
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
                <!-- <label >Name</label> -->
                <input type="text" placeholder="Name" class="nav-search-input" id="name" name="name" value="<?php echo $name; ?>" />
            
                <!-- <label >Email</label>   --> 
                <input placeholder="Email" type="text" class="nav-search-input" id="email" name="email" value="<?php echo $email; ?>" />
            
                <!-- <label >Client</label>   -->   
                <input placeholder="Client" type="text" class="nav-search-input" id="client" name="client" value="<?php echo $client; ?>" />

                <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
                <input type="submit" value="Search" name="search" />
                <!-- <input type="button" value="Reset" onclick="searchReset()"  /> -->
                @if($name != '' || $email != '' || $client != '' )	
                                <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_labels_listing') }}'" />

                                @else

                                <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                                color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                               
                                @endif 
                </div>
                 </form>
        
        </div>
    <div class="space-10"></div>

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
         
                <?php 
                
                if(isset($alert_message)) 
                { 
                
                    ?>
                    <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    </div>
                    
                    <?php }
                
                ?>
                <div class="table-responsive">
                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="center" width="100">
                                    S. No.
                                </th>
                                <th>Title</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Mobile</th>
                                <th>Action</th>

                                
                            </tr>
                        </thead>

                        <tbody>
                        
                        <?php 
                    $i = $start+1;
                    
                    foreach($labels['data'] as $label)
                    {
                    if($label->email !=''){
                    ?>
                            <tr>
                                <td class="center">
                                
<div class="checkbox">
                                <label>
                                    <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
                                </label>
                            </div>
                                                                                    
                                </td>

                                <td><?php echo urldecode($label->title);  ?></td>
                                <td><?php echo urldecode($label->name);  ?></td>
                                <td><?php echo urldecode($labels['client'][$label->id][0]->name);  ?></td>
                                
                                <td><?php echo urldecode($label->email);  ?></td>
                                <td><?php echo urldecode($label->phone);  ?></td>
                                <td><?php echo urldecode($label->mobilePhone);  ?></td>
                                
                                

                                <td>
                                    
<div class="hidden-sm hidden-xs btn-group">
                                        
    <button title="Delete Label" onclick="deleteRecord('<?php echo $currentPage; ?>','<?php echo $label->id; ?>','Confirm delete <?php echo urldecode($label->name); ?> ')" class="btn btn-xs btn-danger">
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                        </button>

                                        
                                    </div>
                                    
                                </td>
                            </tr>
                            
                            <?php $i++; } }?>
    
       <!--  <tr>
            <td colspan="8">
                               
            </td>
        </tr> -->
                </tbody>
                    </table>
                </div>
                 <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                    <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')"><i class="fa fa-angle-double-left"></i></a></li>
                    <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"><i class="fa fa-angle-left"></i></a></li>
                    <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                    <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"><i class="fa fa-angle-right"></i></a></li>
                    <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')"><i class="fa fa-angle-double-right"></i></a></li>
                </ul>
                </div><!-- /.span -->
            </div><!-- /.row -->

            <div class="hr hr-18 dotted hr-double"></div>

        
            <!-- PAGE CONTENT ENDS -->
  
</div><!-- /.page-content -->


<script>

function get_selected_data() {
    var sortBy = document.getElementById('sortBy').value;
    var sortOrder = document.getElementById('sortOrder').value;
    var numRecords = document.getElementById('numRecords').value;
    // var tittle = document.getElementById('tittle').value;
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var client = document.getElementById('client').value;
    // var added = document.getElementById('added').value;

    window.location = "labels?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords + "&name=" + name + "&email=" + email + "&client=" + client;
    }

</script>


@endsection 