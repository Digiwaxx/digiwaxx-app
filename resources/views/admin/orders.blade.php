

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
      <li class="active"><i class="ace-icon fa fa-list list-icon"></i> Orders</li>
   </ul>
   <!-- /.breadcrumb -->
   <!-- #section:basics/content.searchbox -->
   <div class="nav-search" id="nav-search">
      <form class="form-search">
         <input type="hidden" id="page" value="product_orders"  />
         <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
         <span class="input-icon">
            <select class="nav-search-input" id="sortBy">
               <option <?php if(strcmp($sortBy,'members.fname')==0) { ?> selected="selected" <?php } ?> value="members.fname">Member</option>
               <option <?php if(strcmp($sortBy,'product_orders.order_id')==0) { ?> selected="selected" <?php } ?> value="product_orders.order_id">Ordered</option>
               <option <?php if(strcmp($sortBy,'products.name')==0) { ?> selected="selected" <?php } ?> value="products.name">Product</option>
            </select>
         </span>
         <span class="input-icon">
            <label class="hidden-md hidden-sm hidden-xs">Order By</label>
            <select class="nav-search-input" id="sortOrder">
               <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
               <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
            </select>
         </span>
         <label class="hidden-md hidden-sm hidden-xs">No. Records</label>
         <span class="input-icon">
            <select class="nav-search-input" id="numRecords" onchange="changeNumRecords('<?php echo 'product_orders'; ?>','<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
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
	               <label for="productName" class="input-group-addon">Product</label>
	               <input type="text" class="nav-search-input form-control" id="product" name="product" value="<?php if(!empty($searchProduct)) echo $searchProduct; ?>" />
	           </div>
            </div>
            <div class="col-sm-3">
            	<div class="input-group">
	               <label for="productName" class="input-group-addon">Member</label>
	               <input type="text" class="nav-search-input form-control" id="member" name="member" value="<?php if(!empty($searchMember)) echo $searchMember; ?>" />
	           </div>
            </div>
            <div class="col-sm-3">

               <input type="submit" value="Search" name="search" />
               <input type="button" value="Reset" onclick="searchReset()"  />
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
	                        <th class="center" width="100">
	                           S. No.
	                        </th>
	                        <th>Product</th>
	                        <th>Name</th>
	                        <th>Digicoins</th>
	                        <th>Member</th>
	                        <th>Ordered On</th>
	                     </tr>
	                  </thead>
	                  <tbody>
	                     <?php 
	                        $i = $start+1;
	                        foreach($orders['data'] as $order)
	                        {
	                        		if(!empty($product)){
	                        
	                        			if(strlen($product->emailimg)>4)
	                        	  {
	                        	    $artWork = asset('product_images/'.$product->emailimg);
	                        	  }
	                        	  else
	                        	  { 
	                        	   $artWork = asset('product_images/default1.png');
	                        	  }
	                        
	                        		}
	                        		else{
	                        
	                        			$artWork = asset('product_images/default1.png');
	                        
	                        		}
	                        	  
	                        	   ?>
	                     <tr>
	                        <td class="center"><?php echo $i; ?></td>
	                        <td><img src="<?php echo $artWork; ?>" width="30" height="30" /></td>
	                        <td><?php echo urldecode($order->name); ?></td>
	                        <td><?php echo $order->order_fp; ?></td>
	                        <td><?php echo urldecode($order->fname); ?></td>
	                        <td><?php $dt  = $order->order_date_time;
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
	                           } ?></td>
	                     </tr>
	                     <?php $i++; } 
	                     ?>
	                    
	                  </tbody>
	               </table>
	           </div>
            	<?php if($numPages>1) { ?>
						
					   <tr>
						 <td colspan="6">
							   <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
			                   <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
			                   <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
			                   <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
			                   <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
			                   <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
			                   </ul>
					     </td>
					  </tr>
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

