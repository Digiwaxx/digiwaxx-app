
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
				<i class="ace-icon fa fa-users users-icon"></i>
					DJ Tools
			</li>

		</ul><!-- /.breadcrumb -->
		<!-- #section:basics/content.searchbox -->
		<div class="nav-search" id="nav-search">
		   <form class="form-search">
			  <input type="hidden" id="page" value="<?php echo $currentPage; ?>"  />
			  <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
			  <span class="input-icon">
				 <select class="nav-search-input" id="sortBy">
					<option <?php if(strcmp($sortBy,'tittle')==0) { ?> selected="selected" <?php } ?> value="tittle">Tittle</option>
					<option <?php if(strcmp($sortBy,'added_on')==0) { ?> selected="selected" <?php } ?> value="added_on">Added On</option>
					<option <?php if(strcmp($sortBy,'added_by')==0) { ?> selected="selected" <?php } ?> value="added_by">Added By</option>
				 </select>
			  </span>
			  <span class="input-icon">
				 <label class="hidden-md hidden-sm hidden-xs">	Order By</label>
				 <select class="nav-search-input" id="sortOrder">
					<option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
					<option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
				 </select>
			  </span>
			  <label class="hidden-md hidden-sm hidden-xs">	No. Records</label>
			  <span class="input-icon">
				 <select class="nav-search-input" id="numRecords" onchange="changeNumRecords('<?php echo $currentPage; ?>','<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
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
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	   
		 <div class="searchDiv">
			 <form class="form-inline searchForm" id="searchForm" method="get" autocomplete="off">
			 	<div class="row">
				<div class="col-sm-4 col-md-4 col-xs-12">
					<div class="input-group">
					   <label class="input-group-addon">Title</label>
					   <input type="text" placeholder="Title" class="nav-search-input form-control" id="tittle" name="tittle" value="<?php echo $searchTittle; ?>" />
					</div>
				</div>
				<div class="col-sm-4 col-md-4 col-xs-12">
					<div class="input-group">
					   <label class="input-group-addon">Added By</label>
					   <input type="text" placeholder="Added By" class="nav-search-input form-control" id="name" name="name" value="<?php echo $searchName; ?>" />
					</div>
				</div>
				<div class="col-sm-4 col-md-4 col-xs-12">
				   <input type="submit" value="Search" name="search" />
					@if($searchTittle != '' || $searchName != '')
				   <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_listTools') }}'"  />
					@endif
				</div>
				 </div>
			 </form>
	   </div>
	   <!-- /.page-header -->
	   <div class="row">
		  <div class="col-xs-12">
			 <!-- PAGE CONTENT BEGINS -->
				   <?php 
					  if(isset($alert_message))
					  { ?>
				   <div class="<?php echo $alert_class; ?>">
					  <?php echo $alert_message; ?>
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				   </div>
				   <?php } ?>
				   <div class="table-responsive">
				   <table id="sample-table-1" class="table table-striped table-bordered table-hover">
					  <thead>
						 <tr>
							<th class="center" width="100">S. No.</th>
							<th>Tittle</th>
							<th>Added By</th>
							<th>Added On</th>
							<th width="110">Action</th>
						 </tr>
					  </thead>
					  <tbody>
						 <?php   $i = $start+1;
							if($tools['numRows']>0)
							{
							foreach($tools['data'] as $tool)
							{
							
							?>
						 <tr>
							<td class="center">
							   <?php echo $i; ?>
							</td>
							<td><?php echo $tool->tool_track_tittle;  ?></td>
							<td><?php echo urldecode($tool->name);  ?></td>
							<td><?php 
							   $dt  = $tool->added_on;
							   //	echo '<br />';
							   $yr=strval(substr($dt,0,4)); 
							   $mo=strval(substr($dt,5,2)); 
							   $da=strval(substr($dt,8,2)); 
							   $hr=strval(substr($dt,11,2)); 
							   $mi=strval(substr($dt,14,2)); 
							   $se=strval(substr($dt,17,2)); 
							   
							   echo date("l M/d/Y h:i A", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";
							   
							   
							   ?></td>
							<td>
							   <div class="btn-group">
								  <a href="<?php echo url("admin/tools/edit_tool?tid=".$tool->tool_track_id); ?>" title="Edit Client" class="btn btn-xs btn-info">
								  <i class="ace-icon fa fa-pencil bigger-120"></i>
								  </a>
								  <button title="Delete Client" onclick="deleteRecord('tools','<?php echo $tool->tool_track_id; ?>','Confirm delete <?php echo $tool->tool_track_tittle; ?> ')" class="btn btn-xs btn-danger">
								  <i class="ace-icon fa fa-trash-o bigger-120"></i>
								  </button>
							   </div>
							</td>
						 </tr>
						 <?php $i++; } ?>
						 <tr>
							<td colspan="5">
							   <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
								  <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
								  <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
								  <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
								  <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
								  <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
							   </ul>
							</td>
						 </tr>
						 <?php } else {
							?>
						 <tr>
							<td colspan="5">No Data found.</td>
						 </tr>
						 <?php
							} ?>
					  </tbody>
				   </table>
				</div>
				</div>
				<!-- /.span -->
			 </div>
			 <!-- /.row -->
			 <div class="hr hr-18 dotted hr-double"></div>
			 <!-- PAGE CONTENT ENDS -->
		  </div>
		  <!-- /.col -->
	   </div>
	   <!-- /.row -->
	</div>
	<!-- /.page-content -->
    @endsection       