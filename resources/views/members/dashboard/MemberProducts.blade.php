@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Products</h2>
              </div>
            <div class="tabs-section">

				<div class="mem-dblk f-block">				   
				   <div class="hidden-lg d-none" style="margin-top:-38px;">Products to buy</div>
				   <?php if(isset($welcomeMsg)) { ?>
				   <div class="alert alert-primary alert-dismissable">
					  <?php echo $welcomeMsg; ?>
				   </div>
				   <?php } ?>
				   <!--<div class="sby-blk clearfix">
					  <div>
					  
					  
					   <form class="form-inline">
					  <div class="form-group" style="width:78%">
					  
					  <input type="text" class="form-control" id="searchKey" name="searchKey" placeholder="Search" value="<?php echo $searchKey; ?>" style="height:32px; width:100%; font-size:14px;">
					  
					  
					  </div>
					  <button type="submit" name="search" class="btn btn-default" style="background:#392152; color:#FFF;">Search</button>
					  </form>
						 
					  </div>
										   </div>-->
				   <!-- eof sby-blk -->
				   <div class="mb-2">
					  <?php if($numPages>1) {
						if($products['numRows'] >= 10){
						?>
					  <div class="pgm">
						 <?php echo $start+1; ?> - <?php echo $start+$numRecords; ?> OF <?php echo $num_records; ?>
					  </div>
						<?php } ?>
					  <div class="tnav clearfix">
						 <span><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>
						 <span class="num"><?php echo $currentPageNo; ?></span>
						 <span><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
					  </div>
					  <?php } ?>
					  <span class="badge btn-success">Available Digicoins : <span id="available_digicoins"><?php echo $available_digicoins; ?></span></span>
				   </div>
				   <!-- eof fby-blk -->
				   <div class="mProducts-list mCustomScrollbar row">
					  <?php
						//echo'<pre>';print_r($products);die();
						 if($products['numRows']>0)
						 {
						 
						 foreach($products['data'] as $product) 
						 { 
							if(!isset($product->product_id) || empty($product->product_id) || empty($price[$product->product_id]['data'])){
								continue;
							}
						 $reviewStatus = 'Review';
						 if($reviewed[$product->product_id]['options']['numRows']>0 || $reviewed[$product->product_id]['text']['numRows']>0)
						 {
						   $reviewStatus = 'Reviewed';
						 } 
						 
						 ?>
						 <div class="col-md-3 col-sm-4 col-6">
					  <div class="member-product">
							<div class="member-product-img">
							   <p style="position:relative;">
								  <?php 
								  if(!empty($product->emailimg)){
									 if(strlen($product->emailimg)>4)
									 {
										if (file_exists(base_path('ImagesUp/'.$product->emailimg))){
											$artWork = asset('product_images/'.$product->emailimg);
										}else{
											$artWork = asset('product_images/default1.png');
										}
									 }
									 else
									 { 
									  $artWork = asset('product_images/default1.png');
									 }
									 }else{

									  $artWork = asset('product_images/default1.png');
									}
									 ?>
								  <a href="product?pid=<?php echo $product->product_id; ?>"><img src="<?php echo $artWork; ?>" width="60" height="60" /></a>
							   </p>
							</div>
							<div class="" style="margin-top:10px;">
							<?php if( isset($product->name) && !empty($product->name)){ ?>
							   <h5><a href="product?pid=<?php echo $product->product_id; ?>"><?php $product_name = urldecode($product->name); 
								  echo substr($product_name,0,20) 
									?></a></h5>
							   <?php 
								}
								  $retail_price = $price[$product->product_id]['data'][0]->digicoin_price; 
								  
								  
								  if($discounts[$product->product_id]['numRows']>0)
								  {
								  
								   $percentage =  $discounts[$product->product_id]['data'][0]->discount_percentage;
								   $discount_amount = ($percentage*$retail_price)/100;
								   $final_price = $retail_price-$discount_amount;
								   
								   
								  ?> 
							   <p class="text-dim">Digicoins:  <?php echo $final_price; ?></p>
							   <?php
								  ?> 
							   <p>Discount:   <strike><?php echo $retail_price; ?></strike>, <?php echo $percentage.' % OFF'; ?></p>
							   <?php
								  }
								  else
								  {
								  $final_price = $retail_price;
								  $percentage = 0;
								  ?> 
							   <p class="text-dim">Digicoins:  <?php echo $final_price; ?></p>
							   <?php
								  }
								  
								  
								  
								  ?>
							   <a href="javascript:void()" class="btn btn-gradient" onclick="buy_product('<?php echo $product->product_id; ?>','<?php echo $retail_price; ?>','<?php echo $final_price; ?>','<?php echo $percentage; ?>');">Buy Now</a>
							   <span id="response_message_<?php echo $product->product_id; ?>"></span>
							</div>
							<!-- <div class=""> <span style="padding-top:15px;"></span> </div>
							<div class=""></div> -->
							   <p class="mb-0 mt-3"><a class="text-underline" href="product?pid=<?php echo $product->product_id; ?>"><?php echo $reviewStatus; ?></a></p>
					
						 <!-- eof row -->
					  </div>
					</div>
					  <!-- eof item -->
					  <?php } } else { ?>
					  <h2>No Data found.</h2>
					  <?php } ?>
				   </div>
				   <!-- eof mtop-list -->
				</div>
				<!-- eof mem-dblk -->
				<!-- eof mem-dblk -->
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
     </div>
	 </section>
<script>
		
function buy_product(product_id,retail_price,final_price,percentage)
{
	if (confirm("Are you sure?") == true) {
	 $.ajax({url: "Products?purchase=1&product_id="+product_id+"&retail_price="+retail_price+"&final_price="+final_price+"&percentage="+percentage, success: function(result){
						
						row = JSON.parse(result);
						document.getElementById('available_digicoins').innerHTML = row.balance;
						alert(row.message);
						/*var responseColor =  '#E7311F';
					   
						if(row.response==2)
						{
						  responseColor = '#8CCB61';  						
						}
						document.getElementById('response_message_'+product_id).style.color = responseColor;
						document.getElementById('response_message_'+product_id).innerHTML = row.message;*/
					}});
	}

}

</script>	 
@endsection