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
				    		<!-- DIGIWAXX SERVICE OPTIONS START -->
		<div class="dso-blk">      
            <div class="container">
            	<h1>DIGITAL WAXX <span>PAYPAL RESPONSE</span></h1>
                
                <div class="dso-sec center-block">
                    
                    <div class="tp">
						<div class="st3 row">
			<?php if(strcmp($status,'Completed')==0) { ?>		
			
				
    <span style="color: #646464;">Your payment was successful</span><br/>
	
    <div class="col-sm-6">Package : </div><div class="col-sm-6" style="color:#9f1f63;"><?php if($item_number==1) { echo 'BASIC'; } else if($item_number==2) { echo 'ADVANCED'; }  ?></div>	
	<div style="clear:both;"></div>
	<div class="col-sm-6">TXN ID :</div><div class="col-sm-6" style="color:#9f1f63;"><?php echo $txn_id; ?></div>
	<div style="clear:both;"></div>
	<div class="col-sm-6">Amount Paid :</div><div class="col-sm-6" style="color:#9f1f63;">$<?php echo $payment_amt.' '.$currency_code; ?></div>
	<div style="clear:both;"></div>
	<div class="col-sm-6">Payment Status :</div><div class="col-sm-6" style="color:#9f1f63;"><?php echo $status; ?></div>
	<div style="clear:both;"></div>
	
  	
	<?php } else { ?>
	<span style="color: #646464;">Error, occured please try again!</span><br/>
	<?php } ?>
	
</div>
						
						
						
                    </div>
                    
                    
                    
                    
                    
                        <div class="sub-btn" style="padding-bottom:120px;">
                            	<a href="<?php echo url('/'); ?>" style="width:240px; margin:0 auto;" class="ct">BACK TO WEBSITE</a>
                        </div>
                        
                        
                    
                    
                    
                </div><!-- eof dso-sec -->
                
                
            </div><!-- eof container -->
		</div><!-- eof dso-blk -->
      
      
      <!-- DIGIWAXX SERVICE OPTIONS END -->

				
			</div>
		</div>
	</div>
	</div>
	</div>
		
 </section>

 <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/21741445840/336x280', [240, 133], 'div-gpt-ad-1539597853871-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>


<!-- /21741445840/336x280 -->
<div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
<script>
googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
</script>
</div>

 <script>

function sortBy(type,id)

{

    var records = document.getElementById('records').value;		

    window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;

}



function changeNumRecords(type,id,records)

{

    window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;

}



</script>


@endsection