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
								<a href="<?php echo url('admin/member_payments'); ?>">Member Payments</a>
							</li>
                            <li class="active">View Payment</li>
						</ul><!-- /.breadcrumb -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="space-10"></div>
					
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
									<div>
									<?php $payment = $payments['data'][0]; ?>
									<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Username </div>

													<div class="profile-info-value">
														<?php echo urldecode($payment->uname); ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Email </div>

													<div class="profile-info-value">
														<?php echo $payment->email; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Package </div>

													<div class="profile-info-value">
														<?php
														$duration = '';
														$amount = '';
														if($payment->package_Id==2)
														{
														  $package = 'Gold';
														  
														if($payment->duration_Id==1)
														{
														  $amount = '9.99 USD'; 
														  $duration = '1 Month'; 
														}
														else
														if($payment->duration_Id==2)
														{
														  $amount = '54.99 USD'; 
														  $duration = '6 Months'; 
														}
														else
														if($payment->duration_Id==3)
														{
														  $amount = '99.99 USD'; 
														  $duration = '12 Months'; 
														}
														}
														else if($payment->package_Id==3)
														{
														  $package = 'Purple';
														
														if($payment->duration_Id==1)
														{
														  $amount = '5.99 USD';
														  $duration = '1 Month';  
														}
														else
														if($payment->duration_Id==2)
														{
														  $amount = '119.99 USD'; 
														  $duration = '6 Months'; 
														}
														else
														if($payment->duration_Id==3)
														{
														  $amount = '189.99 USD'; 
														  $duration = '12 Months'; 
														}
														}
														echo $package; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Duration </div>

													<div class="profile-info-value">
														<?php echo $duration; ?>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Gateway </div>

													<div class="profile-info-value">
														<?php

                                                           $gateway = '';
                                                        
                                                        if($payment->paymentType==1)
														{
														  $gateway = 'Stripe';
														}
														else
														if($payment->paymentType==2)
														{
														  $gateway = 'Paypal';
														}
														echo $gateway; ?>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Amount </div>

													<div class="profile-info-value">
														<?php echo $amount; ?>
													</div>
												</div>
												
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Paid On </div>

													<div class="profile-info-value">
														<?php  
			$dt  = $payment->subscribed_date_time;
			if(strcmp($dt,'0000-00-00 00:00:00')!=0)
			{
			$yr=strval(substr($dt,0,4)); 
        	$mo=strval(substr($dt,5,2)); 
        	$da=strval(substr($dt,8,2)); 
        	$hr=strval(substr($dt,11,2)); 
        	$mi=strval(substr($dt,14,2)); 
        	$se=strval(substr($dt,17,2)); 

        	echo date("l M/d/Y h:i A", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";
			} ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Staus</div>

													<div class="profile-info-value">
														<?php 
														$status = '';
														if($payment->status==1)
														{
														  $status = 'Completed';
														}
														echo $status;
														?>
													</div>
												</div>
												
												 <?php if($payment->paymentType==2) { ?>
								   
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Payer email</div>

													<div class="profile-info-value">
														<?php echo $paypalData[0]->payer_email; ?>
													</div>
												</div>
									  <?php } ?>
								  			
								  </div>
								  
								 <?php if($payment->paymentType==1) {  ?>
								  <div class="table-header">
											Billing Address
										</div>
								  <div class="profile-user-info profile-user-info-striped">
								  
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Name</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->billingName; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Address line 1</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->billingAddressLine1; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> City</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->billingCity; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> State</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->billingState; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Country </div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->billingCountry; 
														 if(strlen($stripeData[0]->billingCountryCode)>0) { ?> ( <?php echo $stripeData[0]->billingCountryCode; ?> ) <?php } ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Zipcode</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->billingZip; ?>
													</div>
												</div>
												
										</div>
								  
								  <div class="table-header">
											Shipping Address
										</div>
								  <div class="profile-user-info profile-user-info-striped">		
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Name</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->shippingName; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Address line 1</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->shippingAddressLine1; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> City</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->shippingCity; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> State</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->shippingState; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Country </div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->shippingCountry; 
														  if(strlen($stripeData[0]->shippingCountryCode)>0) { ?> ( <?php echo $stripeData[0]->shippingCountryCode; ?> ) <?php } ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Zipcode</div>

													<div class="profile-info-value">
														<?php echo $stripeData[0]->shippingZip; ?>
													</div>
												</div>
								   </div>
								   <?php } ?>
							</div>	
								</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->

                    
@endsection 