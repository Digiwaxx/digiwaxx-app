
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
			 Mails
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	   <div class="nav-search" id="nav-search">
		  <form class="form-search">
			 <input type="hidden" id="page" value="mails"  />
			 <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
			 <span class="input-icon">
				<select class="nav-search-input <?= $sortBy; ?>" id="sortBy" onchange="mailSortingFilter('<?php echo 'mails'; ?>')">
				   <option <?php if(strcmp($sortBy,'started')==0) { ?> selected="selected" <?php } ?> value="started">Started</option>
				   <option <?php if(strcmp($sortBy,'subject')==0) { ?> selected="selected" <?php } ?> value="subject">Subject</option>
				</select>
			 </span>
			 <span class="input-icon">
				<label class="hidden-md hidden-sm hidden-xs">Order By</label>
				<select class="nav-search-input" id="sortOrder" onchange="mailSortingFilter('<?php echo 'mails'; ?>')">
				   <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
				   <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
				</select>
			 </span>
			 <label class="hidden-md hidden-sm hidden-xs">	No. Records</label>
			 <span class="input-icon">
				<select class="nav-search-input" id="numRecords" onchange="mailSortingFilter('<?php echo 'mails'; ?>')">
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
		  <div class="space-24"></div>
	   </div>
	   <!-- /.page-header -->
			 <!-- PAGE CONTENT BEGINS -->
			 <div class="row">
				<div class="col-xs-12">
					<?php if(isset($alertMessage)) { ?>
				   <div class="<?php echo $alertClass; ?>">
					  <?php echo $alertMessage; ?>
				   </div>
				   <?php } ?>
					<div class="table-responsive">
				   <table id="sample-table-1" class="table table-striped table-bordered table-hover">
					  <thead>
						 <tr>
							<th class="center">
							   S. No.
							</th>
							<th>Subject</th>
							<th width="80">Action</th>
						 </tr>
					  </thead>
					  <tbody>
						 <?php 
							$i = $start+1;
							
							foreach($mails['data'] as $mail)
							{
							
							
							
							?>
						 <tr>
							<td>
							   <div class="checkbox">
								  <label>
								  <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
								  </label>
							   </div>
							</td>
							<td>
							   <?php echo urldecode($mail->subject);  ?>
							</td>
							<td width="80">
							   <div class="btn-group">
								  <a href="<?php echo url("admin/mail_view?mid=".$mail->id); ?>" title="View Mail" class="btn btn-xs btn-success">
								  <i class="ace-icon fa fa-align-justify bigger-120"></i>
								  </a>
								  <button onclick="deleteRecord('mails','<?php echo $mail->id; ?>','Confirm delete <?php echo urldecode($mail->subject); ?> ')" class="btn btn-xs btn-danger">
								  <i class="ace-icon fa fa-trash-o bigger-120"></i>
								  </button>
							   </div>
							</td>
						 </tr>
						 <?php $i++; } ?>
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
	<!-- /.page-content -->
	<script>
		function mailSortingFilter(mail){			
			var records = document.getElementById('numRecords').value;
			var sortBy = document.getElementById('sortBy').value;
			var sortOrder = document.getElementById('sortOrder').value;
			var page = document.getElementById('page').value;
			window.location = mail + "?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&records=" + records;
		}
	</script>
    @endsection 