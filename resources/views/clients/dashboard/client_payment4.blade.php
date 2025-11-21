@extends('layouts.client_dashboard')

@section('content')

 <section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		 <div class="container">
			<div class="row">
			<div class="col-12">
            <div class="dash-heading">
                <h2>My Dashboard</h2>
              </div>
            <div class="tabs-section">

            <!-- START MIDDLE BLOCK -->

               <!-- DIGIWAXX SERVICE OPTIONS START -->
		<div class="dso-blk">      
            <div class="container">
            	<h1>DIGITAL WAXX <span>SERVICE OPTIONS</span></h1>
                
                <div class="dso-sec center-block">
                	<h2>PAYMENT OPTIONS</h2>
                    
                    <p class="st2">YOU HAVE SELECTED: <?php echo $title; ?> SERVICE - <?php echo '$'.$displayCost; ?> <br> <span>NOT YOUR choice?
                     <a href="#" onclick="open_campaign_modal();">Go back to select a diferent package</a></span></p>
                    
                    <div class="ser1">
                    	<p class="clearfix"><label class="ser-t1">Order Summary:</label> 
						  <label class="ser-t2"><?php echo '$'.$displayCost; ?> (DIGITAL WAXX SERVICE: <?php echo $title; ?>)</label></p>
                    	<p class="clearfix"><label class="ser-t1">TOTAL:</label> <label class="ser-t2"><?php echo '$'.$displayCost; ?></label></p>
                    </div>
					
                    <div class="po-form">
                    	<div class="form-group">
<!--                            <div class="radio-inline rd1">-->
<?php //echo $cost = '100'; ?>
                              
<!--<form action="Stripe_payment/checkout" method="post">-->
<!--<input type="hidden" name="subscriptionId" value="<?php echo $subscriptionId; ?>" />-->
<!--<input type="hidden" name="amount" value="<?php echo $cost; ?>" />-->
<!--  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"-->
<!--          data-key="pk_live_5BBqzoPi5GoH5UYqZHQTwMHY"-->
<!--          data-name="Digiwaxx"-->
<!--		  data-description="<?php echo $title; ?>"-->
<!--		  data-image="https://digiwaxx.io/assets/img/logo-headphone.jpg"-->
<!--		  data-label="Credit Card"-->
<!--		  data-shipping-address="false"-->
<!--		  data-billing-address="false"-->
<!--		  data-amount="<?php echo $cost; ?>"-->
<!--          data-email="<?php echo $clientemail; ?>"-->
<!--          data-locale="auto"></script>-->
<!--</form>-->

<!--                            </div>-->
                            <?php if ($packtype == 'one-time') { ?>
                            <div class="radio-inline rd1 text-center">
                                 <a href="<?php echo url('/').'/Paypal/buy/1'; //.$subscriptionDetails['data'][0]->packageId; ?>"><img class="paypal-btn-img" src="{{ asset('assets/img/paypal.jpg') }}"></a>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="col-lg-2 back_link_div mt-3">
                            <?php
                            $clientId = Session::get('clientId');
                            if (isset($clientId)){
                                $back = 'Client_dashboard';
                            }else{
                                $back = 'Client_payment1';
                            }
                            ?>
						         <a href="<?php echo url($back); ?>">Back</a>
						     </div> 
                        
                    </div><!-- eof po-form -->
                </div><!-- eof dso-sec -->
                
            </div><!-- eof container -->
		</div><!-- eof dso-blk -->

      <!-- DIGIWAXX SERVICE OPTIONS END -->



            <!-- eof middle block -->


				@include('clients.dashboard.includes.my-tracks')
				
			</div>
		</div>
	</div>
	</div>
	</div>
		
 </section>


@endsection