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
								Client Payments
							</li>

						
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
							
							<input type="hidden" id="page" value="client_payments"  />
							<label class="hidden-md hidden-sm hidden-xs">Sort By</label>
								<span class="input-icon">
		<select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
		<option <?php if(strcmp($sortBy,'username')==0) { ?> selected="selected" <?php } ?> value="username">Client</option>
		<option <?php if(strcmp($sortBy,'package')==0) { ?> selected="selected" <?php } ?> value="package">Package</option>
		<option <?php if(strcmp($sortBy,'paidOn')==0) { ?> selected="selected" <?php } ?> value="paidOn">Paid On</option>
									</select>
								
								</span>
								
								<span class="input-icon">
								<label class="hidden-md hidden-sm hidden-xs">	Order By</label>
									<select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
									<option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
									<option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
									</select>
									
								</span>
								
								<label class="hidden-md hidden-sm hidden-xs">	No. Records</label> 
								<span class="input-icon">
<select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
				<option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
				<option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
				<option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="500">50</option>
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
						<div class="row">
						<div class="col-xs-12 searchDiv">
							
							
							
							  <form class="form-inline searchForm" id="searchForm" method="get">
							  
		<input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
		<input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
		<input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
		
							
							<div class="col-sm-3 col-xs-6">
							<label >Client</label>
								
					<input type="text" class="nav-search-input" id="username" name="username" placeholder="Client username" value="<?php echo $searchUsername; ?>" />
							
								</div>
								
								
								<div class="col-sm-3 col-xs-6">
								<label >Package</label>
								
		<select type="text" class="nav-search-input" id="package" name="package" value="<?php echo $searchPackage; ?>">
		<option value="0">Select</option>
		<option value="1" <?php if($searchPackage==1) { ?> selected="selected" <?php } ?>>Basic</option>
		<option value="2" <?php if($searchPackage==2) { ?> selected="selected" <?php } ?>>Advanced</option>
		</select>
		
								
								</div>
								
								
								
							   <div class="col-sm-3 col-xs-6">
								<label class="hidden-lg hidden-md hidden-xs"></label>
								<div style="clear:both;"></div>
								<input type="submit" value="Search" name="search" />
								
								<!-- <input type="button" value="Reset" onClick="searchReset()"  /> -->
                                @if($searchPackage != '' || $searchUsername != '')	
                            <input type="button" value="Reset" onclick="window.location.href='{{ route('client_payments') }}'" />

                            @else

                            <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                            color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
                           
                            @endif
							  </div>
								
								
								
								
							</form>
							
							</div>
							
							
							
							
							
						</div><!-- /.page-header -->
						<div class="space-6"></div>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
										<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="center" width="100">
														S. No.
													</th>
													<th>Client</th>
													<th>Package</th>
													<th class="hidden-md hidden-sm hidden-xs">Email</th>
													<th class="hidden-sm hidden-xs">Gateway</th>
													<th class="hidden-xs">Amount</th>
													<th class="hidden-sm hidden-xs">Status</th>
													<th class="hidden-md hidden-sm hidden-xs">Paid On</th>
													<th width="60">Action</th>

													
												</tr>
											</thead>

											<tbody>
											
											<?php 
										$i = $start+1;
										foreach($payments['data'] as $payment)
										{
									
										?>
												<tr>
													<td class="center">
													<?php echo $i; ?>
													</td>
													<td>
														<?php echo $payment->uname; ?>
													</td>
													<td>
														<?php 
														$package = ''; $amount = '';
														if($payment->packageId==1)
														{
														  $package = 'Basic'; $amount = '500 USD';
														}
														else
														if($payment->packageId==2)
														{
														  $package = 'Advanced'; $amount = '750 USD';
														}
														
														
														echo $package;  ?>
													</td>
													<td class="hidden-md hidden-sm hidden-xs"><?php echo urldecode($payment->email); ?></td>
													<td class="hidden-sm hidden-xs"><?php
													$paymentGateway = ''; 
													if($payment->paymentType==1)
													{
													  $paymentGateway = 'Stripe';
													}
													else if($payment->paymentType==2)
													{
													  $paymentGateway = 'Paypal';
													}
													echo $paymentGateway;
													
													 ?></td>
													 <td class="hidden-xs"><?php echo $amount; ?></td>
													<td class="hidden-sm hidden-xs"> 
													 <?php 
														$status = '';
														if($payment->status==1)
														{
														  $status = 'Completed';
														}
														echo $status;
														?></td>
													<td class="hidden-md hidden-sm hidden-xs"><?php 											
		    $dt  = $payment->subscribedDateTime;
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
															

															<a href="<?php echo url("admin/client_payment_view?pid=".$payment->subscriptionId); ?>" title="View Payment" class="btn btn-xs btn-success">
																<i class="ace-icon fa fa-align-justify bigger-120"></i>
															</a>
															
																
															
														</div>

														
													</td>
												</tr>
												
												<?php $i++; } ?>
												  <?php if($numPages>1) { ?>
						
							<tr>
							  <td colspan="9">
							  
							  
							
												  <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                            <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>
                            <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                           <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                           <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                           <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onClick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
                              </ul>
						
							   </td>
							</tr>	  
						<?php } ?>
                                    </tbody>
										</table>
									</div><!-- /.span -->
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
	 var package = document.getElementById('package').value;
	 var username = document.getElementById('username').value;
	 
	window.location = "client_payments?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&username="+username+"&package="+package;
					}
					
					
					</script>
										
					
                                        @endsection