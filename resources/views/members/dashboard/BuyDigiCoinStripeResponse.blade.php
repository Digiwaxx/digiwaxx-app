@extends('layouts.app')
@section('content')


<style>
.download_link, .download_link:hover
{
  color: #FFF;
  font-weight: bold;
  display: block;
  margin-top: 6px;
}
#form-toggle
{
  cursor: pointer;
}
h2.logo_headings {
    width: 100%;
    margin-bottom: 10px;
}

.col-auto {
    margin: 6px 10px;
    /*margin:2px;*/
}
.col-auto img {
    height: 55px;
    width: auto;
}


.logos {
	display: flex;
	flex-wrap: wrap;
	margin: 0;
	margin-bottom: 15px;
	justify-content: flex-start;
}
</style>

	<section class="main-dash">
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">            
              <div class="tabs-section">
				<!-- DIGIWAXX SERVICE OPTIONS START -->
				<div class="dso-blk">
				   <div class="container">
					  <h1>DIGITAL WAXX <span>SERVICE OPTIONS</span></h1>
					  <div class="dso-sec center-block">
						 <div class="tp">
							<div class="st3 row">
							   <?php if(isset($alertMessage)) { ?>		
							   <p style="text-align:center;"><?php echo $alertMessage; ?></p>
							   <?php
							   }
							   if(!empty($response_status)){ ?>
							   <div class="col-sm-6">Package : </div>
							   <div class="col-sm-6" style="color:#9f1f63;"><?php echo $response_tittle;  ?></div>
							   <div style="clear:both;"></div>
							   <div class="col-sm-6">Amount Paid :</div>
							   <div class="col-sm-6" style="color:#9f1f63;">$<?php echo $response_price; ?></div>
							   <div style="clear:both;"></div>
							   <div class="col-sm-6">Payment Status :</div>
							   <div class="col-sm-6" style="color:#9f1f63;"><?php echo $response_status; ?></div>
							   <?php }else{ ?>
							   <div style="clear:both;"></div>							   
							   <p style="text-align:center;">Error, occured please try again!</p>
							   <?php } ?>
							</div>
						 </div>
						 <div class="sub-btn" style="padding-bottom:120px;">
							<a href="<?php echo url('/'); ?>" style="width:240px; margin:0 auto;" class="ct">BACK TO WEBSITE</a>
						 </div>
					  </div>
					  <!-- eof dso-sec -->
				   </div>
				   <!-- eof container -->
				</div>
				<!-- eof dso-blk -->
     

              </div>                         
           </div>
         </div>
       </div>
     </div>
	 </section>
@endsection

