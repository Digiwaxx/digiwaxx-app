
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
								<a href="{{ route('products_lisitng') }}"><i class="ace-icon fa fa-list list-icon"></i> Products</a>
							</li>
                       	    <li class="active">Product Digicoins</li>
						</ul><!-- /.breadcrumb -->

					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<?php if(isset($alert_message)) {  ?>
									   <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
									<?php } $data = $product['data'][0];  ?>
								<div class="col-xs-6">
									
									
								<h3 class="header smaller lighter blue">
									   Digicoins
									</h3>
									
									
								<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="center" width="100">
														S. No.
													</th>
													<th>Digicoins/points</th>
													<th>Applies From</th>
													<th>Delete</th>
													
												</tr>
											</thead>

											<tbody>
											
											<?php 
										if($prices['numRows']>0)
										{
                                            if(!empty($start)){

                                                $start = $start;
                                            }
                                            else{

                                                $start = 0;
                                            }
										 $i = $start+1;
										foreach($prices['data'] as $price)
										{
										
										?>
										<tr><td><?php echo $i; ?></td><td><?php echo $price->digicoin_price; ?></td>
										<td><?php $date = explode('-',$price->applies_from);
										 echo $date = $date[2].'-'.$date[1].'-'.$date[0];
										
										 ?></td>
										 <td><?php
										 if($price->applies_from>$today)
										 {
										 
										  ?>
										  <button onclick="deleteRecord1('product_digicoins?pid=<?php echo $_GET['pid'] ?>&','<?php  echo $price->price_id; ?>&del=1','Confirm delete <?php  echo $price->digicoin_price; ?> ')" title="Delete Digicoin" class="btn btn-xs btn-danger">
	 	<i class="ace-icon fa fa-trash-o bigger-120"></i>
	</button>
										  <?php  } ?>
	</td>
										</tr>
										<?php $i++; } } else { ?>
										<tr><td colspan="4">No data found.</td></tr>
										<?php } ?>
										</tbody>
										</table>

								
								
									
									<form class="form-horizontal" role="form" action="" method="post" onsubmit="return validate()">	
                                    @csrf
									<div>
									
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product </label>
										<label class="col-sm-6 control-label no-padding-right" for="form-field-1" style="text-align:left;"><?php echo $data->name; ?></label>
			  					    </div>
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Digicoins </label>
										<div class="col-sm-9">
			  <input type="text" id="retailPrice" name="retailPrice" class="col-xs-10 col-sm-10" placeholder="Digicoins"  />
										</div>
									</div>
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Applies From </label>

										<div class="col-sm-9">
								<input type="date" id="appliesFrom" name="appliesFrom" class="col-xs-10 col-sm-10" placeholder="Applies From" />
										</div>
									</div>
									<div style="clear:both;"></div>
									
									
									
									
							<div style="clear:both;"></div>	
								<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info btn-sm" type="submit" name="addDigicoins"> 
												<i class="ace-icon fa fa-check bigger-110"></i>
												Add Digicoins
											</button> 

											&nbsp; &nbsp; &nbsp;
											<button class="btn btn-sm" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>	
									
									
									
									</div>
									
									</form>
										
										
										
										
									</div><!-- /.span -->
									
									
									<div class="col-xs-5 col-xs-offset-1">
									  <h3 class="header smaller lighter blue">
									   Discount
									</h3>
									
									<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="center" width="100">
														S. No.
													</th>
													<th>Discount Percentage</th>
													<th>Applies From</th>
													<th>Applies To</th>
													<th>Delete</th>
													
												</tr>
											</thead>

											<tbody>
											
											<?php 
										if($discounts['numRows']>0)
										{
										 $i = $start+1;
										foreach($discounts['data'] as $discount)
										{
										
										?>
										<tr><td><?php echo $i; ?></td><td><?php echo $discount->discount_percentage.' %'; ?></td>
										<td><?php $date = explode('-',$discount->applies_from);
										      echo $date = $date[2].'-'.$date[1].'-'.$date[0];	 ?></td>
										<td><?php $date = explode('-',$discount->applies_to);
										      echo $date = $date[2].'-'.$date[1].'-'.$date[0];	 ?></td>
											  
											  
											   <td><?php
										 if($discount->applies_from>$today)
										 {
										 
										  ?>
										  <button onclick="deleteRecord1('product_digicoins?pid=<?php echo $_GET['pid'] ?>&','<?php  echo $discount->discount_id; ?>&del=2','Confirm delete <?php  echo $discount->discount_percentage.' %'; ?> ')" title="Delete Digicoin" class="btn btn-xs btn-danger">
	 	<i class="ace-icon fa fa-trash-o bigger-120"></i>
	</button>
										  <?php  } ?>
	</td>
											  
											  
										</tr>
										<?php $i++; } } else { ?>
										<tr><td colspan="5">No data found.</td></tr>
										<?php } ?>
										</tbody>
										</table>
										
										
									<form class="form-horizontal" role="form" action="" method="post" onsubmit="return validate1()">
                                    @csrf	
									<div>
									
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product </label>
										<label class="col-sm-6 control-label no-padding-right" for="form-field-1" style="text-align:left;"><?php echo $data->name; ?></label>
			  					    </div>
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Discount </label>
										<div class="col-sm-9">
			  <input type="text" id="discount" name="discount" class="col-xs-10 col-sm-10" placeholder="Discount"  />
										</div>
									</div>
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Applies From </label>

										<div class="col-sm-9">
								<input type="date" id="appliesFrom1" name="appliesFrom" class="col-xs-10 col-sm-10" placeholder="Applies From" />
										</div>
									</div>
									
									<div class="col-sm-12 form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Applies To </label>

										<div class="col-sm-9">
								<input type="date" id="appliesTo1" name="appliesTo" class="col-xs-10 col-sm-10" placeholder="Applies To" />
										</div>
									</div>
									<div style="clear:both;"></div>
									
									
									
									
							<div style="clear:both;"></div>	
								<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info btn-sm" type="submit" name="addDiscount"> 
												<i class="ace-icon fa fa-check bigger-110"></i>
												Add Discount
											</button> 

											&nbsp; &nbsp; &nbsp;
											<button class="btn btn-sm" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>	
									
									
									
									</div>
									
									</form>

								
									  
									  
									  </div>
									
									
									
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
					
					
					<link href="<?php echo url('date_picker/jquery-ui.css'); ?>" rel="stylesheet">
	<script src="<?php echo url('date_picker/external/jquery/jquery.js'); ?>"></script>
    <script src="<?php echo url('date_picker/jquery-ui.js'); ?>"></script>
	<script>
	
	

$( "#appliesFrom" ).datepicker({
	inline: true,
	dateFormat: 'mm-dd-yy' 
});

$( "#appliesFrom1" ).datepicker({
	inline: true,
	dateFormat: 'mm-dd-yy' 
});

$( "#appliesTo1" ).datepicker({
	inline: true,
	dateFormat: 'mm-dd-yy' 
});
					
	
	function validate()
	{
			
	var retailPrice = document.getElementById('retailPrice');
	
	if(retailPrice.value.length<1)
	{
	  alert("Please enter Digicoin !");
	  retailPrice.focus();
	  return false;
	}
	
	}
	
	
	function validate1()
	{
			
	var discount = document.getElementById('discount');
	
	if(discount.value.length<1)
	{
	  alert("Please enter discount in percentage !");
	  discount.focus();
	  return false;
	}
	
	}
</script>

@endsection 