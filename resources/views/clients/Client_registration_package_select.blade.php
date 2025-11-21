@extends('layouts.app')

@section('content')
<section class="content-area bg-login modal-custom">
    <!--<div class="container">-->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 mx-auto">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">

                            <div class="top-modal">
                                <div class="music-icon">
                                    <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
                                </div>
                                <!--<h2 class="text-center">Select a Package</h2>-->
                                <!--<p class="text-center areg">Already registered? <a href="{{ url('login') }}">Click here-->
                                <!--        to log in</a> </p>-->
                                <!--<div class="donate-icon">-->
                                <!--    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">-->
                                <!--        <input type="hidden" name="cmd" value="_donations" />-->
                                <!--        <input type="hidden" name="business" value="paypal@digiwaxx.com" />-->
                                <!--        <input type="hidden" name="item_name" value="Digiwaxx" />-->
                                <!--        <input type="hidden" name="currency_code" value="USD" />-->
                                <!--        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />-->
                                <!--        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />-->
                                <!--    </form>-->

                                <!--</div>-->
                            </div>
                        </div>

                        <div class="modal-body">


                            
                                <!--<h2 class="text-center" style="margin:30px 0px;">Select a Package</h2>-->
                                
                                <!---content-section-->
                                   <section class="content-area">
                                     <div class="container">
                                      <div class="plan-section">
                                        <div class="row">
                                            <?php
                                            $str='';
                                            foreach($packages as $package){
                                                if($package->package_type=='Yearly'){
                                                    $str="/ yearly";
                                                }
                                                 if($package->package_type=='Monthly'){
                                                    $str="/ monthly";
                                                }
                                                 if($package->package_type=='Half Yearly'){
                                                    $str="/ six months";
                                                }
                                                
                                            ?>
                                           
                                              <div class="col-md-auto">
                                               
                                                <div class="plan-box additional">
                                                    <form action="{{route('client_register')}}" method="get" name="myForm" id="package_select" autocomplete="off">
                                                   <!--@csrf-->
                                                       <input type="hidden" name="package_id" value="<?php echo $package->id;?>">
                                                          <!--<h3><?php //echo $package->package_name ?></h3>-->
                                                          <!--<h3><?php // echo $package->package_type ?></h3>-->

                                                          <ul>
                                                              <?php $arr=json_decode($package->features);?>
                                                              <?php foreach($arr as $feature){ ?>
                                                            <li><?php echo $feature; ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                          <div class="price clearfix">
                                                                <h4 class=price><?php if(!empty($package->package_price)){ echo '$'.$package->package_price." ".$str;} ?></h4>
                                                            </div>                                                          
                                                          <div class="btn-plan">
                                                            <button class="btn btn-theme btn-gradient" type="submit" >Continue</button>
                                                          </div>
                                                  </form>
                                                </div>
                                       
                                                
                                               </div>
                                      
                                          <?php } ?>
                                        </div>
                                      </div>
                                       
                                     </div> 
                                       
                                   </section>
                           


                        </div>

                    </div>
                    <!-- /.modal-content -->

                </div>
            </div>
        </div>
    <!--</div>-->
</section>
<script>
  
</script>


<!-- Register Block Ends -->

@endsection