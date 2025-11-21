@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>My Digicoins</h2>
              </div>
            <div class="tabs-section">
				<div class="mem-dblk f-block">
				  
				   <div class="hidden-lg d-none" style="margin-top:-38px;">Digicoins are points, earned by giving feedback to clients or purchased</div>
				   <div class="fby-blk clearfix">
					  <div style="clear:both;"></div>
					  <div class="nop">
					  </div>
					  <!-- eof nop -->
					  <?php if($numPages>1) { ?>
					  <div class="pgm">
						 <?php echo $start+1; ?> - <?php echo $start+$numRecords; ?> OF <?php echo $num_records; ?>
					  </div>
					  <div class="tnav clearfix">
						 <span><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>
						 <span class="num"><?php echo $currentPageNo; ?></span>
						 <span><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
					  </div>
					  <?php } ?>
					  <span style="float:right;" class="badge btn-success">Available Digicoins : <?php echo $available_digicoins; ?></span>
				   </div>
				   <!-- eof fby-blk -->
				   <div class="mtop-list mCustomScrollbar">
					  <style>
						 th { background:#B32F85; } 
					  </style>
					  <table id="sample-table-1" class="table table-bordered my-digicoins">
						 <thead>
							<tr>
							   <th class="text-center" width="60">
								  S. No.
							   </th>
							   <th>Points</th>
							   <th>Type</th>
							   <th>Track</th>
							   <th class="hidden-md hidden-sm hidden-xs" width="100">Date</th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							   $i = $start+1;
							   $total_points = 0;
							   
							   if($digicoins['numRows']>0)	{
															 foreach($digicoins['data'] as $coin)
															 { ?>
							<tr>
							   <td class="text-center"><?php echo $i; ?></td>
							   <td><?php echo $coin->points; 
								  $total_points = $total_points+$coin->points;
								  ?></td>
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
							   <td><?php 
								  if($coin->type_id==6)
								  
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
								  
								  }
								  
								  
								  
								  
								  
								  ?></td>
							   <td class="hidden-md hidden-sm hidden-xs"><?php 											
								  $dt  = $coin->date_time;
								  
								  $yr=strval(substr($dt,0,4)); 
								  
										$mo=strval(substr($dt,5,2)); 
								  
										$da=strval(substr($dt,8,2)); 
								  
								  echo $mo.'-'.$da.'-'.$yr;
								  
								  ?></td>
							</tr>
							<?php $i++; } ?>
							<tr>
							   <td></td>
							   <td><?php echo $total_points; ?></td>
							   <td colspan="3"></td>
							</tr>
							<?php } else { ?>
							<tr>
							   <td colspan="5">No Data found. </td>
							</tr>
							<?php } ?>
						 </tbody>
					  </table>
				   </div>
				   <!-- eof mtop-list -->
				</div>
				<!-- eof mem-dblk -->
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
     </div>
	 </section>
	 
@endsection