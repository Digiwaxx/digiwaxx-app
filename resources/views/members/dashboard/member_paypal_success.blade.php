@extends('layouts.member_dashboard')
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
		<!-- @include('layouts.include.sidebar-left') -->

        <?php
            $link_text = 'UNLOCK DOWNLOAD';
            $member_session_pkg = Session::get('memberPackage');
            if(isset($member_session_pkg) && $member_session_pkg > 2)
            {
            $link_text = 'WRITE A REVIEW';
            }
            ?>
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            
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
	
    <div class="col-sm-6">Package : </div><div class="col-sm-6" style="color:#9f1f63;"><?php if($item_number==1) { echo 'SILVER'; } else if($item_number==2) { echo 'GOLD'; } else if($item_number==3) { echo 'PURPLE'; }  ?></div>	
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
              <!---tab section end--->
                         
           </div>
         </div>
       </div>
     </div>
	 </section>


         <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->

<script src="<?php echo url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>





 <script src="<?php echo url('assets/js/menu.js'); ?>"></script>



  

     <script src="<?php echo url('assets/js/TMSearch.js'); ?>"></script>

    

               <script src="<?php echo url('assets/js/bootstrap-select.js'); ?>"></script>





<!--<script>

    $(document).ready(function(){

        $(".menu-btn").click(function(){

                $(".menu-con").css("display","block");

                $(".menu-con").animate({right:'0px'});

        });						  

                    



        $(".menu-close").click(function(){

                $(".menu-con").css("display","none");

                $(".menu-con").animate({right:'-400px'});

          });						  

    });





</script>-->

@endsection

