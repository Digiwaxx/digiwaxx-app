@extends('layouts.app')

@section('content')
<!-- Register Block Starts-->
<section class="content-area bg-login modal-custom">
     <div class="container">
      <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12 mx-auto">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <div class="top-modal">
                    <div class="music-icon">
                      <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
                    </div>
                    <h2 class="text-center">Create a Member Account</h2>
                    
                </div>
            
        </div>
        <div class="modal-body">
            <div class="rgt1">
                <p class="text-center" style="padding:70px 20px;">Thank you for submitting your profile! <br>
                		
              Please check your spam folder for digiwaxx notifications and whitelist our email address to activate your account.
                </p>
            </div>
            <div class="btn-center">
                
                <a href="{{ url('login') }}" class="login_btn btn-theme btn-gradient">Login</a>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
</div>
</div>
</div>
</section>
<!-- Register Block Ends -->
@endsection