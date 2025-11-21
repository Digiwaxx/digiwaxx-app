@extends('admin.admin_dashboard_active_sidebar')
    @section('content')
<div class="main-content">
<div class="main-content-inner">

<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
   <script type="text/javascript">
      try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
   </script>
 
   
   <ul class="breadcrumb">
      <li class="active"><i class="ace-icon fa fa-list list-icon"></i><?php echo $pageTitle;?></li>
   </ul>
    <!--/.breadcrumb -->
    <!--#section:basics/content.searchbox -->
   <div class="nav-search" id="nav-search">
      <form class="form-search">
         <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
            <span class="input-icon">
                <select class="nav-search-input" id="numRecords" onchange="get_selected_data1()">
                   
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
   <!--<div class="row">-->
   <!--   <div class="searchDiv">-->
   <!--      <form class="form-inline searchForm" id="searchForm" action="" method="get" autocomplete="off">-->
   <!--         <div class="col-sm-3">-->
   <!--         	<div class="input-group">-->
	  <!--             <label for="productName" class="input-group-addon">Product</label>-->
	  <!--             <input type="text" class="nav-search-input form-control" id="product" name="product" value=" " />-->
	  <!--         </div>-->
   <!--         </div>-->
   <!--         <div class="col-sm-3">-->
   <!--         	<div class="input-group">-->
	  <!--             <label for="productName" class="input-group-addon">Member</label>-->
	  <!--             <input type="text" class="nav-search-input form-control" id="member" name="member" value=" " />-->
	  <!--         </div>-->
   <!--         </div>-->
   <!--         <div class="col-sm-3">-->

   <!--            <input type="submit" value="Search" name="search" />-->
   <!--            <input type="button" value="Reset" onclick="searchReset()"  />-->
   <!--         </div>-->
   <!--      </form>-->
   <!--   </div>-->
   <!--   <div class="space-10"></div>-->
   <!--</div>-->
   <!-- /.page-header -->
         <!-- PAGE CONTENT BEGINS -->
         <div class="row">
            <div class="col-xs-12">
               <?php 
                  if(isset($alert_message))
                  {
                   ?>
               <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
               <?php 
                  }
                  
                  
                  ?>
                  <div class="table-responsive">
	               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
	                  <thead>
	                     <tr>
	                        <th class="center" width="100">
	                           S. No.
	                        </th>
	                        <th>Title</th>
	                        
	                        <th>Status</th>
	                        <th>Added</th>
	                        <th width="160">Action</th>
	                        <?php $count_news=1;?>
	                     </tr>
	                  </thead>
	                  <tbody>
	                      <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
	                      <?php 
	                      foreach ($get_news as $value){?>
	                     <tr>
	                       <td><?php echo $count_news; $count_news++; ?></td>
	                       <td><?php 
                        $str1=substr($value->title,0,15);
                                        
                                       
                                        $arr=explode(" ",$str1);
                                         if(count($arr)>3)
                                         {
                                        // pArr($arr);
                                        array_pop($arr);
                                         }
                                        
                                       
                                        echo implode(" ",$arr)  ?> </td>
	                       
	                       <td><?php if($value->approved == 1){echo 'Enabled';}else{ echo 'Disabled'; } ?></td>
	                       <td><?php  $date = new DateTime($value->added);
                                        $result = $date->format('d M Y');echo $result; ?></td>
	                       <td>
                           <div class="btn-group">
                              <a href="{{route("view_news",['id'=>$value->id])}}" title="Preview News" class="btn btn-xs btn-success">
                              <i class="ace-icon fa fa-eye bigger-120"></i>
                              </a>
                              <a href="{{route("edit_news",['id'=>$value->id])}}" title="Edit Submitted News" class="btn btn-xs btn-info">
                              <i class="ace-icon fa fa-pencil bigger-120"></i>
                              </a>
                              <?php 
                              if($value->approved == 0){
                                  $appr_route=route('approve_news');?>
                                  <input type="hidden" id="appr_url" value="<?php echo $appr_route;?>">
                                  <?php
                              echo '<button title="Enable News" onclick="approvenews(\''.$value->id.'\')" class="btn btn-xs btn-success">'; 
                              echo '<i class="ace-icon fa fa-check bigger-120"></i>';
                              echo '</button>';
                              }else{
                                  $dis_new=route('news_disable');?>
                               <input type="hidden" id="des_news" value="<?php echo $dis_new;?>">
                               <?php
                              echo '<button title="Disable News" onclick="disablenews(\''.$value->id.'\')" class="btn btn-xs btn-warning">';
                              echo '<i class="ace-icon fa fa-times bigger-120"></i>';
                              echo '</button>';   
                              }
                              
                              $del_news=route('delete_news');
                              ?>
                              <input type="hidden" id="del_news" value="<?php echo $del_news; ?>">
                              <?php echo '<button title="Delete News" onclick="deletenews(\''.$value->id.'\')" class="btn btn-xs btn-danger">'; ?>
                              <i class="ace-icon fa fa-trash-o bigger-120"></i>
                              </button>
                           </div>
                            </td>
	                
	                     </tr>
	                     <?php }?>
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
       <!--     	<?php //if($numPages>1) { ?>-->
						
					  <!-- <tr>-->
						 <!--<td colspan="6">-->
						
					  <!--   </td>-->
					  <!--</tr>-->
					  <!--<?php //} ?>-->
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
    function get_selected_data1() {
       
        let searchParams = new URLSearchParams(window.location.search);
        //  console.log(searchParams);
        if(searchParams.has('page')){
            var page = searchParams.get('page');
        }
        
       
        
        var numRecords = document.getElementById('numRecords').value;
        // var numRecords= jQuery("#numRecords").val();
        window.location ="news?page=" + page + "&numRecords=" + numRecords;
    }    

    // delete news
    function deletenews(id){
      if(confirm("Are you sure you want to delete this?")){
        var url =$('#del_news').val();
        var CSRF_TOKEN = $('#csrf-token').val();
        $.ajax({
        
        url:url,
        type: 'POST',
        data: {_token: CSRF_TOKEN,id:id},
        dataType: 'JSON',
        
        success: function (data) { 
            // $(".writeinfo").append(data.msg);
            if(data == 'success'){
                location.reload();
            }
        }
        });
      }else{
        return false;
     }
    }
    
// Enable news
    function approvenews(id){
        // alert('Do you really want to delete this list');
        var url=$('#appr_url').val();
        var CSRF_TOKEN = $('#csrf-token').val();
        $.ajax({
        
            url: url,
            type: 'POST',
            
            data: {_token: CSRF_TOKEN,id:id},
            dataType: 'JSON',
            
            success: function (data) { 
                // $(".writeinfo").append(data.msg);
                if(data == 'success'){
                    location.reload();
                }
            }
        }); 
    }
    
// Disable news
    function disablenews(id){
        var url=$('#des_news').val();
        var CSRF_TOKEN = $('#csrf-token').val();
        $.ajax({
        
            url: url,
            type: 'POST',
            
            data: {_token: CSRF_TOKEN,id:id},
            dataType: 'JSON',
            
            success: function (data) { 
                // $(".writeinfo").append(data.msg);
                if(data == 'success'){
                    location.reload();
                }
            }
        }); 
    }
</script>
@endsection

