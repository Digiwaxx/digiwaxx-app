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

     <!-- Register Block Starts-->        

    <section class="content-area bg-login modal-custom">
     <div class="container">
      <div class="row">
        <div class="col-md-10 col-lg-10 col-sm-12 mx-auto">     

        <div class="modal-dialog">

            <div class="modal-content">

              <div class="modal-header">
                <div class="top-modal">
                    <div class="music-icon">
                      <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
                    </div>
                    <h2 class="text-center">{{ __('Create a Client Account') }}</h2>
                    <p class="text-center areg">{{ __('Already registered?') }} &nbsp; <a href="{{ url('Login') }}">{{ __('Click here to log in') }}</a>  </p>
                </div>               

              </div>

              

              <div class="modal-body">

			  @if(request()->get('emailExists'))

			  <div class="alert alert-danger">{{ __('Email already exists!') }}</div>

              @endif

        

		<form action="" method="post" id="registrationForm" autocomplete="off">
				@csrf
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> <span class="man"></span>

                        <input name="email" id="email"  class="form-control input"  size="20" placeholder="{{ __('Billing contact e-mail address') }}" type="text" value="{{ Session::get('sess-client-email') }}">

                    </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> <span class="man"></span>

                            <input name="phone" id="phone"  class="form-control input"  size="20" placeholder="{{ __('Phone') }}" type="text" value="{{ Session::get('sess-client-phone') }}">

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> <span class="man"></span>

                            <input name="mobile" id="mobile"  class="form-control input"  size="20" placeholder="{{ __('Mobile phone') }}" type="text" value="{{ Session::get('sess-client-mobile') }}">

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group"> <span class="man"></span>

                            <input name="website" id="website"  class="form-control input"  size="20" placeholder="{{ __('Company website') }}" type="text" value="{{ Session::get('sess-client-website') }}">

                        </div>
                    </div>
                </div>
               

                <div class="form-group"> <span class="man"></span>

                    <input name="username" id="username"  class="form-control input"  size="20" placeholder="{{ __('Username') }}" type="text" value="{{ Session::get('sess-client-username') }}">

                </div>

                

                <div class="form-group"> <span class="man"></span>
                     
                  <div class="login-pass-digi1"> 
                    <input name="password" id="password"  class="form-control input"  size="20" placeholder="{{ __('Password') }}" type="password" value="">
                    <i class="far fa-eye" id="togglePassword"  onclick="myFunction('password','togglePassword')"></i>
                  </div>    
                <div>
					<a href="javascript:void();" onclick="randomPassword(8)" style="float:left;"  class="btn btn-theme btn-gradient mb-4"> {{ __('Generate Password') }} </a>

					<a href="javascript:void();" id="hideKey" onclick="hidePassword(1)" style="display:none; float:left; margin-left:20px;" class="btn btn-theme btn-alt"> {{ __('Hide') }} </a>
                </div>
					

                </div>

                

				<div style="clear:both;"></div>

                <div class="form-group"> <span class="man"></span>
                   <div class="login-pass-digi1"> 
                     <input name="password1" id="password1"  class="form-control input"  size="20" placeholder="{{ __('Confirm Password') }}" type="password" value="">
                     <i class="far fa-eye" id="togglePassword1"  onclick="myFunction('password1','togglePassword1')"></i>
                  </div>

                </div>



                <div class="form-group mb-4"> <span class="man"></span>



                    <select class="selectpicker form-control" name="howHeard" id="howHeard">

                                <option value="">{{ __('How did you hear about Digiwaxx?') }}</option>

                                <option value="Internet Search">{{ __('Internet Search') }}</option>

                                <option value="Magazine Article">{{ __('Magazine Article') }}</option>

                                <option value="Record Pool">{{ __('Record Pool') }}</option>

                                <option value="Digiwaxx Crew">{{ __('Digiwaxx Crew') }}</option>

                                <option value="A Current Member">{{ __('A Current Member') }}</option>                              

                          </select>   



                </div>

                
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> 

                             <input name="facebook" id="facebook"  class="form-control input"  size="20" placeholder="{{ __('Facebook profile') }}" type="text" value="{{ Session::get('sess-client-facebook') }}">

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> 

                            <input name="twitter" id="twitter"  class="form-control input"  size="20" placeholder="{{ __('Twitter profile') }}" type="text" value="{{ Session::get('sess-client-twitter') }}">

                        </div>

                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> 

                          <input name="instagram" id="instagram"  class="form-control input"  size="20" placeholder="{{ __('Instagram profile') }}" type="text" value="{{ Session::get('sess-client-instagram') }}">

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group"> 

                          <input name="linkedin" id="linkedin"  class="form-control input"  size="20" placeholder="{{ __('LinkedIn profile') }}" type="text" value="{{ Session::get('sess-client-linkedin') }}">

                    </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <input name="snapchat" id="snapchat" class="form-control input" size="20" placeholder="{{ __('Snapchat profile') }}" type="text" value="{{ Session::get('sess-client-snapchat') }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                        <input name="tiktok" id="tiktok" class="form-control input" size="20" placeholder="{{ __('TikTok profile') }}" type="text" value="{{ Session::get('sess-client-tiktok') }}">
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        
                        <div class="form-group">
                            <input name="triller" id="triller" class="form-control input" size="20" placeholder="{{ __('Triller profile') }}" type="text" value="{{ Session::get('sess-client-triller') }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <input name="twitch" id="twitch" class="form-control input" size="20" placeholder="{{ __('Twitch profile') }}" type="text" value="{{ Session::get('sess-client-twitch') }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                         <div class="form-group">
                            <input name="mixcloud" id="mixcloud" class="form-control input" size="20" placeholder="{{ __('Mixcloud profile') }}" type="text" value="{{ Session::get('sess-client-mixcloud') }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                        <input name="reddit" id="reddit" class="form-control input" size="20" placeholder="{{ __('Reddit profile') }}" type="text" value="{{ Session::get('sess-client-reddit') }}">
                    </div>
                    </div>
                </div>
				

     <!--           <div class="rgt1">-->

					<!--<p class="text-center">-->
					<!-- A valid email address MUST be supplied to receive your client approval. <br>-->
					<!-- By clicking submit you are agreeing to our terms of service.<br>-->
					<!-- Please check your inbox and spam for confirmation email.-->
					<!-- </p>-->



     <!--           </div>-->

                

                

                

                <div class="btn-center">

                    <input name="addClient2" class="login_btn btn btn-theme btn-gradient" value="{{ __('Next') }}" type="submit">

                </div>

                

                </form>

              </div>

              

            </div>

            <!-- /.modal-content --> 

        

      </div>

    </div>
    </div>
    </div>
    </section>    

        

        

        

     <!-- Register Block Ends -->    

	 

	 <script>
     function myFunction(id,i) {
          var x = document.getElementById(id);
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
          jQuery("#"+i).toggleClass("fa-eye fa-eye-slash");
    } 
	 

	  function hidePassword(id)

	 {

	 

	   if(id==1)

	   {

	    document.getElementById('password').setAttribute('type','password');

	    document.getElementById('password1').setAttribute('type','password');	

		document.getElementById('hideKey').innerHTML = 'View';	

		document.getElementById('hideKey').setAttribute('onClick','hidePassword(2)');	

		}

		else if(id==2)

		{

	    document.getElementById('password').setAttribute('type','text');

	    document.getElementById('password1').setAttribute('type','text');	

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

	document.getElementById('password1').setAttribute('type','text');	

	 

	document.getElementById('password').value = pass;

	document.getElementById('password1').value = pass;

	

	document.getElementById('hideKey').innerHTML = 'Hide';

	document.getElementById('hideKey').style.display = 'block';

	

	document.getElementById('password').focus();

//	alert(pass);

   // return pass;

}





// Wait for the DOM to be ready

$(function() {



 $("#registrationForm").validate();
 
 $.validator.addMethod("alphabetsnspace", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    });
    
     $.validator.addMethod("numbers", function(value, element) {
        return this.optional(element) || /^[0-9\-]+$/.test(value);
    });
    
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
    });
    
     $.validator.addMethod("emailid", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
    });

    $.validator.addMethod("check_social", function(value, element) {
            
            if( $("#facebook").val().trim() ||
                    $("#twitter").val().trim() ||
                    $("#instagram").val().trim() ||
                    $("#linkedin").val().trim() ||
                    $("#snapchat").val().trim() ||
                    $("#tiktok").val().trim() ||
                    $("#triller").val().trim() ||
                    $("#twitch").val().trim() ||
                    $("#mixcloud").val().trim() ||
                    $("#reddit").val().trim()
                    ){
                    return true;
                }else{
                    return false;
                }
        });

 
 $("#reddit").rules("add", {
            check_social: true,
            messages: {
                check_social: "Please provide at least one social media profile."
            }
        }); 
 

  $("#email").rules("add", {

         required:true,

		 email: true,

         messages: {

                required: "Please enter email."

         }

      });

 

 

 $("#phone").rules("add", {
         required:true,
         numbers:true,        
         messages: {
                required: "Please enter phone.",
                numbers: "Enter only numbers"
         }
      });




//  $("#facebook").rules("add", {
//          required:true,   
//          messages: {
//                 required: "Please enter Facebook profile.",
//          }
//       });
      
//  $("#instagram").rules("add", {
//          required:true,   
//          messages: {
//                 required: "Please enter Instagram profile.",
//          }
//       });
 
	  

 $("#mobile").rules("add", {

         required:true,
		 numbers:true, 

		 messages: {

                required: "Please enter mobile number.",
				numbers: "Enter only numbers"

         }

      });



$("#website").rules("add", {

         required:true,

		 messages: {

                required: "Please enter website."

         }

      });



$("#username").rules("add", {

         required:true,

         messages: {

                required: "Please choose a username."

         }

      });

	  

	  $("#password").rules("add", {

         required:true,

         messages: {

                required: "Please enter password."

         }

      });

	  

	  $("#password1").rules("add", {

         required:true,

		 equalTo: "#password",

         messages: {

                required: "Please enter the same password again."

         }

      });

	  $("#howHeard").rules("add", {

         required:true,

         messages: {

                required: "Please select How did you hear."

         }

      });

	

});



 </script>
@endsection