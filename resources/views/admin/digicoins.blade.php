
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
								Digicoins
							</li>

						
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
							
							<input type="hidden" id="page" value="<?php echo $currentPage; ?>"  />
								<span class="input-icon">
								<label class="hidden-md hidden-sm hidden-xs">	Order By</label>
									<select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
									<option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
									<option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
									</select>
									
								</span>
								
								<label class="hidden-md hidden-sm hidden-xs">	No. Records</label> 
								<span class="input-icon">
<select class="nav-search-input" id="numRecords" onChange="changeNumRecords('<?php echo $currentPage; ?>','<?php echo 'buy_digicoins.buy_id'; ?>','<?php echo $sortOrder; ?>',this.value)">
				<option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
				<option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
				<option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
				<option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
				<option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
									</select>
								</span>
								
										<!-- <span class="input-icon">	<button type="button" value="Sort" onclick="sortData()">Sort</button> </span> -->

							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="row">
						<div class="col-xs-12 searchDiv">
							
							</div>
							
							
							
							
							
						</div><!-- /.page-header -->

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
													<th>User Type</th>
													<th>User Name</th>
													<th>Package</th>
													<th>Points</th>
													<th>Payment Gateway</th>
													<th>Date</th>
												

													
												</tr>
											</thead>

											<tbody>
											
											<?php 
										$i = $start+1;
										foreach($digicoins['data'] as $digicoin)
										{
									
										?>
												<tr>
													<td class="center">
													<?php echo $i; ?>
													</td>
													<td><?php  
													     
														 if($digicoin->user_type==1)
														  {
															$user_type = 'Client';
														  }
														  else if($digicoin->user_type==2)
														  {
															$user_type = 'Member';
														  } 
														  echo $user_type;
														 ?>
													</td>
													<td><?php $name = urldecode($user_details[$digicoin->buy_id]); 
													
													 echo ucfirst($name);
													?></td>
													<td><?php 
													   if($digicoin->package_id==1)
													     {  $points = 50; $package = 'Silver';  }
													   else if($digicoin->package_id==2)
													     {  $points = 80; $package = 'Gold'; }
													   else if($digicoin->package_id==3)
													     {  $points = 100; $package = 'Purple'; }
														
														echo $package;
														?></td>
														
													<td><?php echo $points; ?></td>
													<td><?php  
													     
														 if($digicoin->payment_type==1)
														  {
															$gateway = 'Stripe';
														  }
														  else if($digicoin->payment_type==2)
														  {
															$gateway = 'Paypal';
														  } 
														  echo $gateway;
														 ?>
													</td>
													<td class="hidden-md hidden-sm hidden-xs"><?php 											
		    $dt  = $digicoin->buy_date_time;
			
			if(strcmp($dt,'0000-00-00 00:00:00')!=0)
			{
			$yr=strval(substr($dt,0,4)); 
        	$mo=strval(substr($dt,5,2)); 
        	$da=strval(substr($dt,8,2)); 
        	$hr=strval(substr($dt,11,2)); 
        	$mi=strval(substr($dt,14,2)); 
        	$se=strval(substr($dt,17,2)); 

        	echo date("l M/d/Y h:i A", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";
			}
			
		 ?></td>
													
												</tr>
												
												<?php $i++; } ?>
												  <?php if($numPages>1) { ?>
						
							<tr>
							  <td colspan="7">
							  
							  
							
			<ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                  <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onClick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
                  <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                  <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                  <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                  <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
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

function get_selected_data() {
   
    var sortOrder = document.getElementById('sortOrder').value;

    window.location = "digicoins?sortOrder=" + sortOrder;
    }

</script>
					
@endsection 		
					