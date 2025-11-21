@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Member Orders</h2>
              </div>
            <div class="tabs-section">

				<div class="mem-dblk f-block">				  
				   <div class="hidden-lg d-none" style="margin-top:-38px;">Product orders purchased by digicoins</div>
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
				   </div>
				   <!-- eof fby-blk -->
				   <div class="mtop-list mCustomScrollbar">
					  <style>
						 th { background:#B32F85; } 
					  </style>
					  <table id="sample-table-1" class="table table-bordered">
						 <thead>
							<tr>
							   <th class="center" width="60">
								  S. No.
							   </th>
							   <th>Product</th>
							   <th>Digicoins</th>
							   <th class="hidden-md hidden-sm hidden-xs">Date</th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							   $i = $start+1;
							   if($orders['numRows']>0)
							   {
							   foreach($orders['data'] as $order)
							   {
							   
							   ?>
							<tr>
							   <td class="center">
								  <?php echo $i; ?>
							   </td>
							   <td><?php echo $order->name; ?></td>
							   <td><?php echo $order->order_fp; ?></td>
							   <td class="hidden-md hidden-sm hidden-xs"><?php 											
								  $dt  = $order->order_date_time;
								  $yr=strval(substr($dt,0,4)); 
										$mo=strval(substr($dt,5,2)); 
										$da=strval(substr($dt,8,2)); 
								  echo $mo.'-'.$da.'-'.$yr;
								  ?></td>
							</tr>
							<?php $i++; } } else { ?>
							<tr>
							   <td colspan="4">No Data found. </td>
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