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

.login-pass-digi1{
    position:relative;
}

.login-pass-digi1 i{
   position: absolute;
  top: 45px;
  right: 20px;
  cursor: pointer;
}
}


</style>

	<section class="main-dash">
		@include('layouts.include.sidebar-left')

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

                                        <!-- START MIDDLE BLOCK -->

                                        <div class="col-md-12">
					
					<form action="" id="changePassword" method="post" enctype="multipart/form-data" >
                    @csrf
                    	<div class="myinfo-block f-block">
                          
                        	<h1>CHANGE PASSWORD</h1>
                            
							
							<?php if(isset($alert_class)) 
							{ ?>
						
						 <div class="<?php echo $alert_class; ?>" style="margin-bottom:40px;">
                            	<p><?php echo $alert_message; ?></p>
                            </div>
							<?php } ?>
							<div class="row">
							<div class="col-sm-6">
                            <div class="form-group">
                            <div class="login-pass-digi1">    
    							<label>Current Password</label>
                                <input name="currentPassword" id="currentPassword"    class="form-control input"  size="20" placeholder="Password" type="password"  /> 
                                <i class="far fa-eye" id="togglePassword"  onclick="myFunction('currentPassword','togglePassword')"></i>
                            </div>    

                            </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group">
                        <div class="login-pass-digi1">          
							<label>New Password</label>
                            							
                                  <input name="password" id="password"    class="form-control input"  size="20" placeholder="Confirm Password" type="password"  />
                                  <i class="far fa-eye" id="togglePassword1"  onclick="myFunction('password','togglePassword1')"></i>
                                  
                            </div>      
                                  
                            	  <a href="javascript:void()" onclick="randomPassword(8)" style="float:left;" class="gen-pass"> Generate Password </a>
                            	  <a href="javascript:void()" id="hideKey" onclick="hidePassword(1)" style="display:none; float:left; margin-left:20px;" class="gen-pass"> Hide </a>
 
                            </div>
                            </div>
							<div class="col-sm-12">
                            <div class="form-group">
                          <div class="login-pass-digi1"> 
							<label>Confirm Password</label>
                              <input name="password1" id="password1"    class="form-control input"  size="20" placeholder="Confirm Password" type="password"  />
                                 <i class="far fa-eye" id="togglePassword2"  onclick="myFunction('password1','togglePassword2')"></i>
                        </div>         
	
                            </div>
                            </div>
                            
                            <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <input name="changePassword" class="btn btn-alt pull-right" value="CHANGE PASSWORD" type="submit">
                            </div>
                            </div>
                            </div>
                            

                        </div>
						</form>
                    </div><!-- eof middle block -->
           

              </div>
              <!---tab section end--->
                         
           </div>
         </div>
       </div>
     </div>
	 </section>

 
     <script>
		
		
        // Wait for the DOM to be ready
      $(function() {
      
       $("#changePassword").validate();
       
       $("#currentPassword").rules("add", {
               required:true,
               messages: {
                      required: "Enter current password"
               }
            });
      
      $("#password").rules("add", {
               required:true,
               minlength: 8,
               messages: {
                      required: "Enter new password"
               }
            });
      
      
      $("#password1").rules("add", {
               required:true,
               minlength: 8,
               equalTo: "#password",
               messages: {
                      required: "Enter confirm password"
               }
            });
      
      });
      
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

