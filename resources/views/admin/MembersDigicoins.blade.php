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
				<li>

					<i class="ace-icon fa fa-users users-icon"></i>

					<a href="<?php echo url("admin/members"); ?>">Members</a>

				</li>

				<li class="active">Member Digicoins</li>
				
			</ul><!-- /.breadcrumb -->
			
			<div class="nav-search" id="nav-search">
			   <form class="form-search">
				  <input type="hidden" id="page" value="member_digicoins"  />
				  <span class="input-icon">
				  <input type="hidden" class="nav-search-input" value="1" id="sortBy">
				  
					 <label class="hidden-md hidden-sm hidden-xs">	Order By</label>
					 <select class="nav-search-input" id="sortOrder">
						<option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
						<option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
					 </select>
				  </span>
				  <label class="hidden-md hidden-sm hidden-xs">	No. Records</label> 
				  <span class="input-icon">
					 <select class="nav-search-input" id="numRecords" onChange="changeNumRecords('<?php echo 'member_digicoins'; ?>','<?php echo 'member_digicoins.member_digicoin_id&mid='.$memId; ?>','<?php echo $sortOrder; ?>',this.value)">
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

		<div class="page-content">
			<div class="row">
			   <div class="col-xs-12 searchDiv">
			   </div>
			</div>
			<!-- /.page-header -->
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
								 <th>Points</th>
								 <th>Type</th>
								 <th>Track</th>
								 <th>Date</th>
							  </tr>
						   </thead>
						   <tbody>
							  <?php 
								 $i = $start+1;
								 foreach($digicoins['data'] as $coin)
								 {
								 
								 ?>
							  <tr>
								 <td class="center">
									<?php echo $i; ?>
								 </td>
								 <td><?php echo $coin->points; ?></td>
								 <td>
									<?php 
									   if($coin->type_id==1)
									   {
										 echo 'Review';
									   }
									   else if($coin->type_id==2)
									   {
										 echo 'Download';
									   }
									   else if($coin->type_id==3)
									   {
										 echo 'Facebook Share';
									   }
									   else if($coin->type_id==4)
									   {
										 echo 'Twitter Share';
									   }
										else if($coin->type_id==5)
									   {
										 echo 'Tip';
									   }
									   else if(($coin->type_id==6) || ($coin->type_id==7))
									   {
										 echo 'Purchased';
									   }
									   ?>
								 </td>
								 <td><?php  if($coin->type_id==6)
									{
									
									 if($coin->points==50)
									 {
									echo 'Silver Pakcage | Stripe';
									 }
									 else if($coin->points==80)
									 {
									echo 'Gold Pakcage | Stripe';
									 }
									 else if($coin->points==100)
									 {
									echo 'Purple Pakcage | Stripe';
									 }
									
									}
									else if($coin->type_id==7)
									{
									
									 if($coin->points==50)
									 {
									echo 'Silver Pakcage | Paypal';
									 }
									 else if($coin->points==80)
									 {
									echo 'Gold Pakcage | Paypal';
									 }
									 else if($coin->points==100)
									 {
									echo 'Purple Pakcage | Paypal';
									 }
									
									}
									else
									{
									echo urldecode($coin->artist.' | '.$coin->title); 
									} ?>
								 </td>
								 <td class="hidden-md hidden-sm hidden-xs"><?php 											
									$dt  = $coin->date_time;
									
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
								 <td colspan="5">
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
		</div><!-- /.page-content -->
	</div>
</div>

@endsection           
	