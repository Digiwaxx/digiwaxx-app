
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
		  <li class="active">
			 <i class="ace-icon fa fa-users user-icon"></i>
			 Subscribers
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	   <div class="nav-search" id="nav-search">
		  <form class="form-search" autocomplete="off">
			 Display No. Records
			 <span class="input-icon">
				<select class="nav-search-input" id="numRecords" onchange="changeNumRecords('<?php echo 'subscribers'; ?>','<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
				   <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
				   <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
				   <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
				   <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
				   <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
				</select>
			 </span>
		  </form>
	   </div>
	   <!-- /.nav-search -->
	   <!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	   <div class="row">
		  <div class="searchDiv">
			 <form class="form-inline searchForm" id="searchForm" method="get" autocomplete="off">
				<div class="col-sm-6 col-md-4">
					<div class="input-group">
					   <label class="input-group-addon">Email</label>
					   <input type="text" class="nav-search-input form-control" id="email" name="email" value="<?php echo $searchEmail; ?>" />
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
				   <input type="submit" value="Search" name="search" />
				   <input type="button" value="Reset" onclick="searchReset()"  />
				</div>
			 </form>
		  </div>
	   </div>
	   <!-- /.page-header -->
	   <div class="space-6"></div>
	   <div class="row">
		  <div class="col-xs-12">
			 <!-- PAGE CONTENT BEGINS -->
		
				   <?php
					  if(isset($alertMessage))
					  {
					  
					  ?>
				   <div class="<?php echo $alertClass; ?>">
					  <?php echo $alertMessage; ?>
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				   </div>
				   <?php
					  }
					  
					  
					  ?>
				   <table id="sample-table-1" class="table table-striped table-bordered table-hover">
					  <thead>
						 <tr>
							<th class="center" width="100">
							   S. No.
							</th>
							<th>Subscriber</th>
							<th>Status</th>
							<th>Action</th>
						 </tr>
					  </thead>
					  <tbody>
						 <?php
							if($subscribers['numRows']>0)
							{ 
							$i = $start+1;
							
							foreach($subscribers['data'] as $subscriber)
							{
														
							?>
						 <tr>
							<td class="center">
							   <div class="checkbox">
								  <label>
								  <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
								  </label>
							   </div>
							</td>
							<td><?php echo $subscriber->email;  ?></td>
							<td><?php if($subscriber->status==1)
							   {
								 echo 'Active';
							   }  else { echo 'Inactive'; } ?></td>
							<td>
							   <div class="hidden-sm hidden-xs btn-group">
								  <button onclick="deleteRecord('subscribers','<?php echo $subscriber->subscriberId; ?>','Confirm delete <?php echo $subscriber->email; ?> ')" class="btn btn-xs btn-danger">
								  <i class="ace-icon fa fa-trash-o bigger-120"></i>
								  </button>
							   </div>
							</td>
						 </tr>
						 <?php $i++; } ?>
						 <?php if($numPages>1) { ?>
						 <tr>
							<td colspan="4">
							   <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
								  <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
								  <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
								  <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
								  <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
								  <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
							   </ul>
							</td>
						 </tr>
						 <?php } } else { ?>
						 <tr>
							<td colspan="4"> No data found. </td>
						 </tr>
						 <?php } ?>
					  </tbody>
				   </table>
				
			 <!-- PAGE CONTENT ENDS -->
		  </div>
		  <!-- /.col -->
	   </div>
	   <!-- /.row -->
	</div>
</div>
</div>
	<!-- /.page-content -->
    @endsection 