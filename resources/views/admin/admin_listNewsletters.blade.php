
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
			 <i class="ace-icon fa fa-list list-icon"></i>
			 Newsletters
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	   <div class="nav-search" id="nav-search">
		  <form class="form-search">
			 <input type="hidden" id="page" value="newsletters"  />
			 <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
			 <span class="input-icon">
				<select class="nav-search-input" id="sortBy">
				   <option <?php if(strcmp($sortBy,'newsletter_id')==0) { ?> selected="selected" <?php } ?> value="started">Latest</option>
				   <option <?php if(strcmp($sortBy,'subject')==0) { ?> selected="selected" <?php } ?> value="subject">Subject</option>
				</select>
			 </span>
			 <span class="input-icon">
				<label class="hidden-md hidden-sm hidden-xs">Order By</label>
				<select class="nav-search-input" id="sortOrder">
				   <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
				   <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
				</select>
			 </span>
			 <label class="hidden-md hidden-sm hidden-xs">	No. Records</label>
			 <span class="input-icon">
				<select class="nav-search-input" id="numRecords" onchange="changeNumRecords('<?php echo 'mails'; ?>','<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
				   <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
				   <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
				   <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
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
		  <div class="space-24"></div>
	   </div>
	   <!-- /.page-header -->
	   <div class="row">
		  <div class="col-xs-12">
			 <!-- PAGE CONTENT BEGINS -->
			 <div class="row">
				<div class="col-xs-12">
				   <?php if(isset($alertMessage)) { ?>
				   <div class="<?php echo $alertClass; ?>">
					  <?php echo $alertMessage; ?>
				   </div>
				   <?php } ?>
				   <table id="sample-table-1" class="table table-striped table-bordered table-hover">
					  <thead>
						 <tr>
							<th class="center" width="100">
							   S. No.
							</th>
							<th>Subject</th>
							<th width="110">Action</th>
						 </tr>
					  </thead>
					  <tbody>
						 <?php 
							$i = $start+1;
							
							foreach($newsletters['data'] as $letter)
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
							<td><?php echo ucfirst($letter->subject); ?></td>
							<td width="110">
							   <div class="btn-group">
								  <a href="<?php echo url("admin/newsletter_view?nlid=".$letter->newsletter_id); ?>" title="View Mail" class="btn btn-xs btn-success">
								  <i class="ace-icon fa fa-align-justify bigger-120"></i>
								  </a>
								  <button onclick="deleteRecord('<?php echo $currentPage; ?>','<?php echo $letter->newsletter_id; ?>','Confirm delete <?php echo ucfirst($letter->subject); ?> ')" class="btn btn-xs btn-danger"> 
								  <i class="ace-icon fa fa-trash-o bigger-120"></i>
								  </button>
							   </div>
							</td>
						 </tr>
						 <?php $i++; } ?>
						 <?php if($numPages>1) { ?>
						 <tr>
							<td colspan="8">
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
					  </tbody>
				   </table>
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