
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
				Logos
			</li>

		</ul><!-- /.breadcrumb -->
		
		
		
		<!-- #section:basics/content.searchbox -->
		<div class="nav-search" id="nav-search">
			<form class="form-search">
			
					
		<input type="hidden" id="page" value="logos"  />
			<label class="hidden-md hidden-sm hidden-xs">Sort By</label>
				<span class="input-icon">
				<select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
				<option <?php if(strcmp($sortBy,'company')==0) { ?> selected="selected" <?php } ?> value="company">Company</option>
				<option <?php if(strcmp($sortBy,'added')==0) { ?> selected="selected" <?php } ?> value="added">Added</option>
					</select>
				
				</span>
				<label class="hidden-md hidden-sm hidden-xs">Order By</label>
				
				<span class="input-icon">
					<select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
					<option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
					<option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
					</select>
					
				</span>
				
			
			 <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
				<span class="input-icon">
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
			<form class="form-inline searchForm" id="searchForm" action="" method="get" autocomplete="off">
				<input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
				<input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
				<input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
	 			<div class="search-filters">
					
					<input placeholder='Company' type="text" class="nav-search-input" id="company" name="company" value="<?php echo $searchCompany; ?>" required />
						<input type="submit" value="Search" name="search" />
						@if($searchCompany != '')
						<input type="button" value="Reset" onclick="window.location.href='{{ route('admin_listCompanyLogos') }}'"  />
						@endif
				</div>
			</form>

		</div>
		<div class="space-10"></div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
									
									
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
													<th>Company</th>
													<th width="120">Action</th>

													
												</tr>
											</thead>

											<tbody>
											
											<?php 
										$i = $start+1;
										
										if($logos['numRows']>0)
										{
										foreach($logos['data'] as $logo)
										{
										
										/* if(empty($logo->company)){
											continue;
										} */
										
										
										?>
												<tr>
													<td class="center">
													
<div class="checkbox">
													<label>
														<span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
													</label>
												</div>
																										
													</td>

													<td><?php echo urldecode($logo->company);  ?></td>
												
													

													<td>
														
<div class="btn-group">


															<a href="<?php echo url("admin/logo_view?lid=".$logo->id); ?>" title="View Logo" class="btn btn-xs btn-success">
																<i class="ace-icon fa fa-align-justify bigger-120"></i>
															</a>
															
															<a href="<?php echo url("admin/logo_edit?lid=".$logo->id); ?>" title="Edit Logo" class="btn btn-xs btn-info">
																<i class="ace-icon fa fa-pencil bigger-120"></i>
															</a>



															
						<button title="Delete Label" onclick="deleteRecord('<?php echo $currentPage; ?>','<?php echo $logo->id; ?>','Confirm delete <?php echo urldecode($logo->company); ?> ')" class="btn btn-xs btn-danger">
																<i class="ace-icon fa fa-trash-o bigger-120"></i>
															</button>

															
														</div>
														
													</td>
												</tr>
												<?php $i++; } if($numPages>1) { ?>
										
											<tr class="nav-pagination">
											  <td colspan="3">
																  <ul class="pager pager-rounded">
				                     <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"><i class="fa fa-angle-double-left"></i></a></li>
				                     <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"><i class="fa fa-angle-left"></i></a></li>
				                     <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
				                     <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"><i class="fa fa-angle-right"></i></a></li>
				                     <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')"><i class="fa fa-angle-double-right"></i></a></li>
				                              </ul>
											   </td>
											</tr>
											<?php } } else { ?>
											<tr><td colspan="3">No Data found.</td></tr>
											<?php } ?>
                                    </tbody>
										</table>

									</div>
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
    </div><!-- /.page-content -->
	<script>

		function get_selected_data()
		{
		var sortBy = document.getElementById('sortBy').value;
		var sortOrder = document.getElementById('sortOrder').value;
		var numRecords = document.getElementById('numRecords').value;
		var company = document.getElementById('company').value;

		window.location = "logos?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&company="+company;
		}


	</script>
    @endsection       