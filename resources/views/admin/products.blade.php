

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
      <li class="active"><i class="ace-icon fa fa-list list-icon"></i> Products</li>
   </ul>
   <!-- /.breadcrumb -->
   <!-- #section:basics/content.searchbox -->
   <div class="nav-search" id="nav-search">
      <form class="form-search">
         <input type="hidden" id="page" value="store"  />
         <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
         <span class="input-icon">
            <select class="nav-search-input" id="sortBy">
               <option <?php if(strcmp($sortBy,'products.name')==0) { ?> selected="selected" <?php } ?> value="products.name">Name</option>
               <option <?php if(strcmp($sortBy,'products.product_id')==0) { ?> selected="selected" <?php } ?> value="products.product_id">Added</option>
            </select>
         </span>
         <span class="input-icon">
            <label class="hidden-md hidden-sm hidden-xs">	Order By</label>
            <select class="nav-search-input" id="sortOrder">
               <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
               <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
            </select>
         </span>
         <label class="hidden-md hidden-sm hidden-xs">	 No. Records</label>
         <span class="input-icon">
            <select class="nav-search-input" id="numRecords" onchange="changeNumRecords('<?php echo 'store'; ?>','<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
               <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
               <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
               <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="500">50</option>
               <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
               <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
            </select>
         </span>
         <span class="input-icon">	<button type="button" value="Sort" onclick="sortData()">Sort</button> </span>
      </form>
   </div>
   <!-- /.nav-search -->
   <!-- /section:basics/content.searchbox -->
</div>
<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
   <div class="row">
      <div class="searchDiv">
         <form class="form-inline searchForm" id="searchForm" action="" method="get" autocomplete="off">
            <div class="col-sm-3">
            	<div class="input-group">
	               <label class="input-group-addon" for="productName">Product</label>
	               <input type="text" class="nav-search-input form-control" id="product" name="product" value="<?php if(!empty($searchProduct))echo $searchProduct; else '';?>" />
	           </div>
            </div>
            <div class="col-sm-3">
               <input type="submit" value="Search" name="search" />
               <!-- <input type="button" value="Reset" onclick="searchReset()"  /> -->
               <?php if(!empty($searchProduct))
                  {?>
               <input type="button" value="Reset" onclick="window.location.href='{{ route('products_lisitng') }}'" />
               <?php }
                  else {?> 
               <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                  color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
               <?php 
                  }	?>
            </div>
         </form>
      </div>
      <div class="space-10"></div>
   </div>
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
                        <th class="center" width="60">
                           S. No.
                        </th>
                        <th>Product</th>
                        <th width="140">Name</th>
                        <th width="80">Digicoins</th>
                        <th>Availability to Members</th>
                        <th>Merch by Digiwaxx</th>
                        <th>Added On</th>
                        <th width="160">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $i = $start+1;
                        foreach($products as $product)
                        {
                        
                        	  if(isset($product->emailimg) && file_exists(base_path('product_images/'.$product->emailimg)) && strlen($product->emailimg)>4 && @is_array(getimagesize('product_images/'.$product->emailimg)))
                        	  {
                        	    $artWork = asset('product_images/'.$product->emailimg);
                        	  }
                        	  else
                        	  { 
                        	   $artWork = asset('product_images/default1.png');
                        	  } ?>
                     <tr>
                        <td class="center"><?php echo $i; ?></td>
                        <td><img src="<?php echo $artWork; ?>" width="30" height="30" /></td>
                        <td><?php echo urldecode($product->name); ?></td>
                        <td><?php  if(!empty($digicoins[$product->product_id]['data'])) echo $digicoins[$product->product_id]['data'][0]->digicoin_price; else echo ''; ?></td>
                        <td><?php if($product->active==1) { echo 'Available'; } else { echo 'Not Available'; } ?></td>
                        <td><?php if(!empty($product->merch_status)){ if($product->merch_status == 1){echo 'Enabled'; } else {echo 'Disabled'; }} ?></td>
                        <td><?php 
                           $dt  = $product->added;
                           
                                 if(strcmp($dt,'0000-00-00 00:00:00')!=0)
                             {
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
                              <a href="<?php echo url("admin/store/product_digicoins?pid=".$product->product_id); ?>" title="Product Digicoins" class="btn btn-xs btn-warning">
                              <i class="ace-icon fa fa-money bigger-120" aria-hidden="true"></i>
                              </a>
                              <a href="<?php echo url("admin/store/product_review_options?pid=".$product->product_id); ?>" title="Product Review Options" class="btn btn-xs btn-primary">
                              <i class="fa fa-list bigger-120" aria-hidden="true"></i>
                              </a>
                              <a href="<?php echo url("admin/store/product_review_report?pid=".$product->product_id); ?>" title="Show Full Review Reports" class="btn btn-xs btn-success">
                              <i class="fa fa-eye bigger-120" aria-hidden="true"></i>
                              </a>
                              <a href="<?php echo url("admin/store/edit_product?pid=".$product->product_id); ?>" title="Edit Product" class="btn btn-xs btn-info">
                              <i class="ace-icon fa fa-pencil bigger-120"></i>
                              </a>
                              <button onclick="deleteRecord('store','<?php  echo $product->product_id; ?>','Confirm delete <?php  echo $product->name; ?> ')" title="Delete Product" class="btn btn-xs btn-danger">
                              <i class="ace-icon fa fa-trash-o bigger-120"></i>
                              </button> 
                           </div>
                        </td>
                     </tr>
                     <?php $i++; } ?>
                  </tbody>
               </table>
                
            </div>
            <?php  if($numPages>1) { ?>
                
                        <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                           <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
                           <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                           <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                           <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                           <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
                        </ul>
                    
                  <?php } ?>
            </div>
            <!-- /.span -->
         </div>
         <!-- /.row -->
         
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</div>
<!-- /.page-content -->
@endsection

