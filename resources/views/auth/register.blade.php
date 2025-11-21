@extends('layouts.app')

@section('content')
<section class="content-area bg-login modal-custom">
     <div class="container">
      <div class="row">
        <div class="col-md-8 col-lg-6 col-sm-12 mx-auto">
<div class="modal-dialog login">
    <div class="modal-content">
        <div class="modal-header">
             <div class="top-modal">
                <div class="music-icon">
              <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
            </div>
            <div class="donate-icon">            
                    
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_donations" />
                <input type="hidden" name="business" value="paypal@digiwaxx.com" />
                <input type="hidden" name="item_name" value="Digiwaxx" />
                <input type="hidden" name="currency_code" value="USD" />
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                </form>
                
            </div>
            </div>
        </div>
        
        <div class="modal-body">
              <!-- <div class="control-group text-center" style="align:center; padding-top:20px;">
                    
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_donations" />
                <input type="hidden" name="business" value="paypal@digiwaxx.com" />
                <input type="hidden" name="item_name" value="Digiwaxx" />
                <input type="hidden" name="currency_code" value="USD" />
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                </form>
                </div> -->
            <h3 class="text-center">Please select account type</h3>
            <form action="" method="post" id="loginForm">


                <div class="form-check">
                        <input type="radio" name="type" id="optionsRadios2" onclick="signuppage('member_subscriptions')" class="form-check-input">
                    <!-- <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"> -->
                    <label class="form-check-label" for="flexRadioDefault1">
                      DJ's/Members
                    </label>
                  </div>
                  <div class="form-check">
                    <input type="radio" name="type" id="optionsRadios1" onclick="signuppage('client_subscriptions')" class="form-check-input">

                    <!-- <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked> -->
                    <label class="form-check-label" for="flexRadioDefault2">
                      Clients/Artists
                    </label>
                  </div>

                <!-- <div class="form-group text-center">
                    <div class="radio-inline ut1">
                        <label>
                            <input type="radio" name="type" id="optionsRadios2" onclick="signuppage('Member_subscriptions')">-->
                           <!-- <input type="radio" name="type" id="optionsRadios2" onclick="signuppage('Member_registration_step1')">
                            DJ's/Members
                        </label>
                    </div>

                    <div class="radio-inline ut1" style="margin-left:0px;">
                        <label>
                            <input type="radio" name="type" id="optionsRadios1" onclick="signuppage('Client_registration_step1')">
                            Clients/Artists
                        </label>
                    </div>
                </div> -->
            </form>
            <!--userForm-->
        </div>
    </div>
    <!-- /.modal-content -->
</div>
</div>
</div>
</div>
</section>
<script>
    function signuppage(page) {
        window.location = page;

    }
</script>
@endsection