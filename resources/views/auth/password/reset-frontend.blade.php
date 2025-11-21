@extends('layouts.app')
@section('content')
<style>
    .login-pass-digi1{
    position:relative;
}

.login-pass-digi1 i{
   position: absolute;
  top: 18px;
  right: 20px;
  cursor: pointer;
}
</style>

<div class="container">
     <div class="row justify-content-center">

         <div class="col-md-8">
		 	<?php if(isset($alert_class)) { ?>
			
			 <div class="<?php echo $alert_class; ?>">
					<p><?php echo $alert_message; ?></p>
				</div>
			<?php } if($invalidCode == 1 ) { ?>
				<div class="text-center fyp"><a href="<?php echo url('forgot-password') ?>">Reset password?</a></div>
				
				<?php } ?>
				@if(session()->has('error'))
                   <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
				
				@if(session()->has('message'))
                   <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            <div class="modal-content">
			<?php if($invalidCode == 0 ) { // !(isset($_GET['reset'])) ?>
                 <div class="modal-header">Reset Password</div>
                      <div class="modal-body">
					  
                          <form method="POST" action="{{ route('password-update') }}">
                           @csrf
                           <input type="hidden" name="token" value="{{ $token }}">
                        <!--div class="form-group row">
                           
                          <div class="">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="E-Mail Address" autocomplete="email" autofocus required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div-->

                        <div class="form-group row">                            
                            <div class="">
                                <div >
                                <div class="login-pass-digi1">       
							    	<input name="password" id="password"  class="form-control input"  size="20" placeholder="Password" type="password">
							    	<i class="far fa-eye" id="togglePassword"  onclick="myFunction('password','togglePassword')"></i>
								</div>
								<a href="javascript:void()" onclick="randomPassword(8);" style="float:left;" class="gen-pass"> Generate Password </a>
								<a href="javascript:void()" id="hideKey" onclick="hidePassword(1);" style="display:none; float:left; margin-left:20px;" class="gen-pass"> Hide </a>
								</div>
                            </div>

                        </div>
                        <br>

                      <div class="form-group row">                            
                            <div class="login-pass-digi1">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">
                                <i class="far fa-eye" id="togglePassword1"  onclick="myFunction('password-confirm','togglePassword1')"></i>
                            </div>
                        </div>

                     <div class="form-group row mb-0">
                           <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-alt pull-righ" name="resetPassword">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
					
                </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
  // Wait for the DOM to be ready
$(function() {

 $("#loginForm").validate();
 
 $("#password").rules("add", {
         required:true,
		 minlength:8,
         messages: {
                required: "Please enter password"
         }
      });
	  
  $("#password-confirm").rules("add", {
        required:true,
        equalTo: "#password",
        messages: {
                required: "Confirm password"
         }
      });
	  
	  
	  
	  
	  
	 /* $( "#myform" ).validate({
  rules: {
    password: "required",
    password_again: {
      equalTo: "#password"
    }
  }
});*/


	
});


function hidePassword(id)
	 {
	 
	   if(id==1)
	   {
	    document.getElementById('password').setAttribute('type','password');
	    document.getElementById('password-confirm').setAttribute('type','password');	
		document.getElementById('hideKey').innerHTML = 'View';	
		document.getElementById('hideKey').setAttribute('onClick','hidePassword(2)');	
		}
		else if(id==2)
		{
	    document.getElementById('password').setAttribute('type','text');
	    document.getElementById('password-confirm').setAttribute('type','text');	
		document.getElementById('hideKey').innerHTML = 'Hide';	
		document.getElementById('hideKey').setAttribute('onClick','hidePassword(1)');		
		}
		
		
		
	
	 }
	 
	 function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }


    document.getElementById('password').setAttribute('type','text');
	document.getElementById('password-confirm').setAttribute('type','text');	
	 
	document.getElementById('password').value = pass;
	document.getElementById('password-confirm').value = pass;
	
	document.getElementById('hideKey').innerHTML = 'Hide';
	document.getElementById('hideKey').style.display = 'block';
	//document.getElementById('hideKey').setAttribute('onClick','hidePassword(1)');		
	document.getElementById('password').focus();
//	alert(pass);
   // return pass;
}

      function myFunction(id,i) {
          var x = document.getElementById(id);
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
          jQuery("#"+i).toggleClass("fa-eye fa-eye-slash");
        } 

</script>
@endsection