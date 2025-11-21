

@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
<div class="main-content-inner">
<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
   <script type="text/javascript">
      try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
      
   </script>
   <ul class="breadcrumb">
      <li>
         <i class="ace-icon fa fa-users users-icon"></i>
         <a href="<?php echo url('admin/clients'); ?>">Clients</a>
      </li>
      <li class="active">Add Client</li>
   </ul>
   <!-- /.breadcrumb -->
</div>
<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
         <!-- PAGE CONTENT BEGINS -->
         <div class="row">
            <div class="col-xs-12">
               <?php if(!empty($alert_message))
                  {
                  
                  ?>
               <div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> <button class="close" data-dismiss="alert">
                  <i class="ace-icon fa fa-times"></i>
                  </button>
               </div>
               <?php 
                  } ?>									
               <form role="form" action="" method="post" onsubmit="return validate()" autocomplete="off">
                  @csrf
                  <div class="row">
                  	<div class="col-xs-12">
	                     <h3 class="header smaller lighter">
	                        Client Information
	                     </h3>
	                 </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Company Name </label>
                           <input type="text" class="form-control" id="companyName" placeholder="Company Name" name="companyName">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Company Billing Contact  (Name) </label>
                           <input type="text" class="form-control" id="name" placeholder="Name" name="name">
                        </div>
                     </div>
                     
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Billing Contact E-Mail Address </label>
                           <input type="text" class="form-control" id="email" placeholder="E-mail address" name="email" onkeyup="checkEmail(this.value)" onblur="checkEmail(this.value)">
                           <input type="hidden" id="verifyEmail" value="0" />
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Company Website Address </label>
                           <input type="text" class="form-control" id="website" placeholder="Website" name="website">
                        </div>
                     </div>
                     
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Phone No. </label>
                           <input type="text" class="form-control" id="phoneNo" placeholder="Phone No." name="phoneNo">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Mobile No. </label>
                           <input type="text" class="form-control" id="mobileNo" placeholder="Mobile No." name="mobileNo">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Address 1 </label>
                           <input type="text" class="form-control" id="address1" placeholder="Address" name="address1">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Address 2  </label>
                           <input type="text" class="form-control" id="address2" placeholder="Address" name="address2">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Country </label>
                        
                           <select  name="country" size="1" id="country" class="form-control input_normal" onchange="getStates(this.value)">
                              <option value="">Select Country</option>
                              <?php if($countries['numRows']>0) {
                                 foreach($countries['data'] as $country) { ?>
                              <option value="<?php echo $country->countryId; ?>"><?php echo $country->country; ?></option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> State </label>
                        
                           <select name="state" size="1" id="state" class="form-control input_normal" onchange="getCities(this.value)">
                              <option value="0">Select State</option>
                                 <?php if($states['numRows']>0) {
                                    foreach($states['data'] as $state) { ?>
                              <option value="<?php echo $state->stateId; ?>"><?php echo $state->name; ?></option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> City </label>
                           <input type="text" class="form-control" id="city" placeholder="City" name="city">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Zip </label>
                           <input type="text" class="form-control" id="zip" placeholder="Zip" name="zip">
                        </div>
                     </div>
                     <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> User Name </label>
                           <input type="text" class="form-control" id="username" placeholder="User Name" name="username" autocomplete="off">
                        </div>
                     </div>
                     <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Password </label>
                        
                           <input type="password" id="password" placeholder="Password" name="password" autocomplete="off" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Confirm Password </label>
                        
                           <input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> How did you hear about Digiwaxx? </label>
                        
                           <select name="howheard" id="howheard" onChange="javascript:howHeard();" class="form-control">
                              <option value="Internet Search">Internet Search</option>
                              <option value="Magazine Article">Magazine Article</option>
                              <option value="Record Pool">Record Pool</option>
                              <option value="DJ Crew">DJ Crew</option>
                              <option value="A Current Member">A Current Member</option>
                           </select>
                        </div>
                     </div>
                     
                     
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Facebook Profile </label>
                           <input type="text" class="form-control" id="facebook" placeholder="Facebook Profile" name="facebook">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Twitter Profile </label>
                           <input type="text" class="form-control" id="twitter" placeholder="Twitter Profile" name="twitter">
                        </div>
                     </div>
                     
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Instagram Profile </label>
                           <input type="text" class="form-control" id="instagram" placeholder="Instagram Profile" name="instagram">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Linkedin Profile </label>
                           <input type="text" class="form-control" id="linkedin" placeholder="Linkedin Profile" name="linkedin">
                        </div>
                     </div>
                     <div class="col-xs-12">
	                     <div class="form-actions text-right">
	                           <button class="btn btn-info btn-sm" type="submit" name="addClient">
	                           <i class="ace-icon fa fa-check bigger-110"></i>
	                           Add Client
	                           </button>
	                           &nbsp; 
	                           <button class="btn btn-reset btn-sm" type="reset">
	                           <i class="ace-icon fa fa-undo bigger-110"></i>
	                           Reset
                           </button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <!-- /.span -->
         </div>
         <!-- /.row -->
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</div>
</div>
</div>
<!-- /.page-content -->
<script>
   function checkEmail(email)
   
   {   
   
    $.ajax({url: "add_client?email="+email+"&verifyEmail=1", success: function(result){
   
   	 	 row = JSON.parse(result);
   					if(row.response==1)   
   					{
   
   					  document.getElementById('verifyEmail').value = 0;   
                             }   
   					else
   					{
                        document.getElementById('verifyEmail').value = 1;				
   
   					}
   
   					//  return false;
   
            }});
   
   }
   function validate()
   
   {
   
   var companyName = document.getElementById('companyName');
   
   var name = document.getElementById('name');   
   var email = document.getElementById('email');   
   var website = document.getElementById('website');   
   var phoneNo = document.getElementById('phoneNo');   
   var mobileNo = document.getElementById('mobileNo');   
   var address1 = document.getElementById('address1');   
   var address2 = document.getElementById('address2');   
   var country = document.getElementById('country');   
   var state = document.getElementById('state');   
     var city = document.getElementById('city');   
     var zip = document.getElementById('zip');   
   var username = document.getElementById('username');   
   var howheard = document.getElementById('howheard');   
   var password = document.getElementById('password');   
   var confirmPassword = document.getElementById('confirmPassword');   
   var verifyEmail = document.getElementById('verifyEmail');  
       var numericExp = /^[-+]?[0-9]+$/;;
   
   var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
   
   /*
     
    $.ajax({url: "add_client?email="+email.value+"&verifyEmail=1", success: function(result){
   
   	 	 row = JSON.parse(result);      					
      					if(row.response==1)   
   					{
   					  document.getElementById('verifyEmail').value = 0;   
                             }   
   					else   
   					{
                            document.getElementById('verifyEmail').value = 1;				
   
   					}
   
   					//  return false;
   
            }});*/
   
   
   
   if(companyName.value.length<1)
   
   {
   
    alert("Please enter company name!");
   
    companyName.focus();
   
    return false;
   
   }
   
   
   
   if(name.value.length<1)   
   {
    alert("Please enter name!");   
    name.focus();   
    return false;   
   }
   
      
   if(!(email.value.match(emailExp)))   
   {   
    alert("Please enter a valid email address!");   
    email.focus();   
    return false;
   
   }
   if(verifyEmail.value==0)   
   {   
    alert("Entered email already exists, try another one!");   
    email.focus();   
    return false;     
      }
   
   
   // 	if(website.value.length<1)   
   // 	{   
   // 	  alert("Please enter website!");   
   // 	  website.focus();   
   // 	  return false;   
   // 	}
   
   //if(!(phoneNo.value.match(numericExp)) || (phoneNo.value.length!=10))
   
   if(phoneNo.value.length<1)
   
   {
   
    alert("Please enter a valid 10 digit phone number!");
   
    phoneNo.focus();
   
    return false;
   
   }
   
   
   
   //if(!(mobileNo.value.match(numericExp)) || (mobileNo.value.length!=10))
   
   if(mobileNo.value.length<1)
   
   {
   
    alert("Please enter a valid 10 digit mobile number!");
   
    mobileNo.focus();
   
    return false;
   
   }
   
   
   
   if(address1.value.length<1)
   
   {
   
    alert("Please enter address 1!");   
    address1.focus();
       return false;
      }
      
   
   if(address2.value.length<1)
   
   {
   
    alert("Please enter address 2!");   
    address2.focus();   
    return false;   
   }   
   
   
   if(country.value.length<1)
   
   {
   
    alert("Please enter country!");   
    country.focus();
    return false;   
   }
     
   
   if(state.value.length<1)
   
   {
   
    alert("Please enter state!");   
    state.focus();   
    return false;   
   }
     
    if(city.value.length<1)
   
   {
   
    alert("Please enter city!");   
    city.focus();   
    return false;
    }   
      
   if(!(zip.value.match(numericExp)) || (zip.value.length!=6))
   
   {   
    alert("Please enter a valid 6 digit zip!");   
    zip.focus();   
    return false;   
   }   
      
   if(username.value.length<1)   
   {   
    alert("Please enter username!");   
    username.focus();   
    return false;   
   }         

   if(password.value.length<8)   
   {
   
    alert("Password shouldnt be less than 8 characters!");   
    password.focus();   
    return false;
   
   }  
   if(howheard.value.length<1)   
   {
   
    alert("Please select How did you hear about digiwaxx!");   
    howheard.focus();   
    return false;
   
   }
   
   if(!(password.value==confirmPassword.value))   
   {
   
    alert("Confirm password doesnt match!");   
    confirmPassword.focus();   
    return false;   
   }	
   
   }   
   
   function getStates(cid)
   {
   
   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
        var states_list = this.responseText;
     
     var obj = JSON.parse(states_list); 
     var display_states = '';
     
    for (var i = 0;  i < obj.length; i++) {
       
    display_states += "<option value='"+obj[i]['state_id']+"'>"+obj[i]['state']+"</option>";
    
   }
   document.getElementById('state').innerHTML = display_states;
   
     }
   };
   xhttp.open("GET", "add_client?gs=1&cid="+cid, true);
   xhttp.send();
   
   }
   
</script>
@endsection

