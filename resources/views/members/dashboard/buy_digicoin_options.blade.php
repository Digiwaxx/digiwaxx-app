

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
   .radio-inline {
   position: relative;
   display: inline-block;
   padding-left: 20px;
   margin-bottom: 0;
   font-weight: 400;
   vertical-align: middle;
   cursor: pointer;
   }
</style>
<section class="main-dash">
   
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
                        <h1>DIGITAL WAXX <span>PAYMENT OPTIONS</span></h1>
                        <div class="dso-sec center-block">
                           <h2>PAYMENT OPTIONS</h2>
                           <p class="st2">YOU HAVE SELECTED: <?php if(!empty($package )) echo $package; ?> PACKAGE
                              <br> <span>Not Your Choice?</span>
                              <a href="<?php echo url("Buy_digicoins"); ?>" class="link-active">Go back to select a diferent package</a>
                           </p>
                           <?php // print_r($_SESSION); ?> 
                           <div class="po-form">
                              <div class="form-group text-center">
                                 <div class="radio-inline rd1">
                                    <form action="Stripe_digicoins_payment/checkout" method="post">
                                       @csrf
                                       <input type="hidden" name="buyId" value="<?php  echo Session::get('buyId'); ?>" />
                                       <input type="hidden" name="amount" value="<?php  echo $digiCoinPrice; ?>" />
                                       <!-- pk_live_5BBqzoPi5GoH5UYqZHQTwMHY  -->
                                       <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                          data-key="pk_test_51JmHVaSFsniFu3P5aDzyOj62m7QM1XZWcbqWlJQPKCM2MH6hPFoW6Zj3GNr3dF9SSxIRkxY4tODUaMqivzRt1l8e00xKfv3mc0"
                                          data-name="Digiwaxx"
                                          data-description="<?php echo (!empty($package )) ? 'SELECTED PACKAGE - '.$package : 'Access for One Year'; ?>"
                                          data-image="{{ asset('public/images/logo2.png') }}"
                                          data-label="Credit Card"
                                          data-shipping-address="true"
                                          data-billing-address="true"
                                          data-amount="<?php echo $digiCoinPrice; ?>"
                                          data-locale="auto"></script>
                                    </form>
                                 </div>
                                 <div class="radio-inline rd1">
                                    <a href="<?php  echo url('Paypal/buy_digicoins/'.$userType.'/'); ?>"><img class="paypal-btn-img" src="assets/img/paypal.png"></a>
                                 </div>
                              </div>
                           </div>
                           <!-- eof po-form -->
                        </div>
                        <!-- eof dso-sec -->
                     </div>
                     <!-- eof container -->
                  </div>
                  <!-- eof dso-blk -->
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

