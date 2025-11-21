

@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
   <div class="main-content-inner">
      <!-- #section:basics/content.breadcrumbs -->
      <div class="breadcrumbs" id="breadcrumbs">
         <script type="text/javascript">
            try {
                ace.settings.check('breadcrumbs', 'fixed')
            } catch (e) {}
         </script>
         <ul class="breadcrumb">
            <li>
               <i class="ace-icon fa fa-users users-icon"></i>
               <a href="<?php echo url("admin/members"); ?>">Members</a>
            </li>
            <li class="active">Add Member</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
         <div class="row">

            <div class="col-xs-12">
               <!-- PAGE CONTENT BEGINS -->
               <?php if (isset($alert_message)) {
                  ?>
               <div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> <button class="close" data-dismiss="alert">
                  <i class="ace-icon fa fa-times"></i>
                  </button>
               </div>
               <?php
                  } ?>
               <style>
                  .form-group .req-label:after {
                  content: "*";
                  color: red;
                  }
               </style>
               <form role="form" action="" method="post" onsubmit="return validate()" autocomplete="off">
                  @csrf
                  <div class="row">
                    <div class="col-xs-12">
                         <h3 class="header smaller lighter">
                            Add Member
                         </h3>
                     </div>
                     <input type="hidden" id="package" name="package" value="2">
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> First Name </label>
                           <input type="text" id="firstName" placeholder="First Name" name="firstName" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Last Name </label>
                           <input type="text" id="lastName" placeholder="Last Name" name="lastName" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Phone Number </label>
                           <input type="number" id="phone" placeholder="Phone Number" name="phone" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> E-Mail Address </label>
                           <input type="text" id="email" placeholder="E-Mail Address" name="email" onkeyup="checkEmail(this.value)" onblur="checkEmail(this.value)" class="form-control" />
                           <input type="hidden" id="verifyEmail" value="0" />
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Stage/DJ Name </label>
                           <input type="text" id="stageName" placeholder="Stage/DJ Name" name="stageName" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> User Name </label>
                           <input type="text" id="member_username" placeholder="User Name" name="member_username" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Password </label>
                           <input type="password" id="member_password" placeholder="Password" name="member_password" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Confirm Password </label>
                           <input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Address 1 </label>
                           <input type="text" id="address1" placeholder="Address 1" name="address1" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Address 2 </label>
                           <input type="text" id="address2" placeholder="Address 2" name="address2" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Country </label>
                           <!--<input type="text" id="" placeholder="Country" name="country">-->
                           <select name="country" size="1" id="country" class="form-control input_normal" onchange="getStates(this.value)">
                              <?php if ($countries['numRows'] > 0) {
                                 foreach ($countries['data'] as $country) { ?>
                              <option value="<?php echo $country->countryId; ?>"><?php echo $country->country; ?></option>
                              <?php }
                                 } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> State </label>
                           <select name="state" size="1" id="state" class="form-control input_normal" onchange="getCities(this.value)">
                              <option value="0">Select State</option>
                              <?php if ($states['numRows'] > 0) {foreach ($states['data'] as $state) { ?>
                              <option value="<?php echo $state->stateId; ?>"><?php echo $state->name; ?></option>
                              <?php }
                                 } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> City </label>
                           <input type="text" id="city" placeholder="City" name="city" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Zip </label>
                           <input type="text" id="zip" placeholder="Zip" name="zip" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1"> Sex </label>
                           <div class="radio">
                              <label>
                              <input name="sex" type="radio" class="ace" value="male">
                              <span class="lbl"> Male</span>
                              </label>
                              <label>
                              <input name="sex" type="radio" class="ace" value="female">
                              <span class="lbl"> Female</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <!--<div style="clear:both;"></div>-->
                     <!--                                   <div class="col-sm-6 col-xs-12 form-group">-->
                     <!--                                       <label class="control-label" for="form-field-1"> What type of computer do you use? </label>-->
                     <? // if($computer=="PC"){ echo " selected"; } 
                        ?>
                     <!--                                       -->
                     <!--                               <select name="computer" size="1" id="computer" class=" input_normal">-->
                     <!--                <option value="PC">PC</option>-->
                     <!--                <option value="Mac">Mac</option>-->
                     <!--              </select>-->
                     <!--                                       </div>-->
                     <!--                                   </div>-->
                     <!--<div class="col-sm-6 col-xs-12 form-group">-->
                     <!--   <label class="control-label" for="form-field-1"> Website </label>-->
                     <!--   -->
                     <!--       <input type="text" id="website" placeholder="Website" name="website">-->
                     <!--   </div>-->
                     <!--</div>-->
                     <!--<div style="clear:both;"></div>-->
                     <!--<div class="col-sm-6 col-xs-12 form-group">-->
                     <!--   <label class="control-label" for="form-field-1"> Preferred Player </label>-->
                     <!--   -->
                     <!--       <select name="player" size="1" id="player" class="input_normal ">-->
                     <!--       <option value="Windows Media Player">Windows-->
                     <!--       Media Player</option>-->
                     <!--       <option value="Real Player">Real</option>-->
                     <!--       Player -->
                     <!--       <option value="Quicktime">Quicktime</option>-->
                     <!--   </select>-->
                     <!--   </div>-->
                     <!--</div>-->
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Member Points </label>                                        
                           <input type="text" id="memberPoints" placeholder="Member Points" name="memberPoints" class="form-control">
                        </div>
                     </div>
                     <div style="clear:both;"></div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Facebook Profile </label>
                           <input type="url" id="facebook" placeholder="Facebook Profile" name="facebook" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Instagram Profile </label>
                           <input type="url" id="instagram" placeholder="Instagram Profile" name="instagram" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Twitter Profile </label>
                           <input type="url" id="twitter" placeholder="Twitter Profile" name="twitter" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Linkedin Profile </label>
                           <input type="url" id="linkedin" placeholder="Linkedin Profile" name="linkedin" class="form-control">
                        </div>
                     </div>
                     <!--<div class="col-sm-6 col-xs-12 form-group">-->
                     <!-- <label class="control-label" for="form-field-1"> Linkedin Profile </label>-->
                     <!---->
                     <!--       <input type="text" id="linkedin" placeholder="Linkedin Profile" name="linkedin">-->
                     <!--   </div> -->
                     <!--</div>-->
                     <div class="col-xs-12">
                        <h3 class="header smaller lighter">
                           Profile
                        </h3>
                     </div>
                     <!--   <div class="col-sm-12 form-group">-->
                     <!--       <label class="control-label" for="form-field-1"> What Is your Field?<br />-->
                     <!--       (Please fill out and describe your profile and information with as much detail as possible)-->
                     <!--       </label>-->
                     <!--               <div class="col-sm-8">-->
                     <!--<div class="control-group">-->
                     <!-- #section:custom/checkbox -->
                     <!--               <div class="checkbox">-->
                     <!--                   <label>-->
                     <!--               <input type="checkbox" class="ace ace-checkbox-2" name="djMixer" id="djMixer" value="1" onclick="getDiv('djDiv',this.id)">-->
                     <!--                   <span class="lbl"> DJ/Mixer</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label>-->
                     <!--       <input type="checkbox" class="ace ace-checkbox-2" name="radioStation" id="radioStation" value="1" onclick="getDiv('radioDiv',this.id)">-->
                     <!--                       <span class="lbl"> Radio Station (Non-DJ/Mixer)</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label>-->
                     <!--                       <input class="ace ace-checkbox-2" type="checkbox" name="massMedia" id="massMedia" value="1" onclick="getDiv('massDiv',this.id)">-->
                     <!--                       <span class="lbl"> Mass Media</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label class="block">-->
                     <!--                       <input type="checkbox" class="ace ace-checkbox-2" name="recordLabel" id="recordLabel" value="1" onclick="getDiv('recordDiv',this.id)">-->
                     <!--                       <span class="lbl"> Record Label</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label class="block">-->
                     <!--                       <input type="checkbox" class="ace ace-checkbox-2" name="management" id="management" value="1"  onclick="getDiv('managementDiv',this.id)">-->
                     <!--                       <span class="lbl"> Management</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label class="block">-->
                     <!--                       <input type="checkbox" class="ace ace-checkbox-2" name="clothingApparel" id="clothingApparel" value="1" onclick="getDiv('clothingDiv',this.id)">-->
                     <!--                       <span class="lbl"> Clothing/Apparel</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label class="block">-->
                     <!--                       <input type="checkbox" class="ace ace-checkbox-2" name="promoter" id="promoter" value="1" onclick="getDiv('promoterDiv',this.id)">-->
                     <!--                       <span class="lbl"> Promoter</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label class="block">-->
                     <!--                       <input type="checkbox" class="ace ace-checkbox-2" name="specialServices" id="specialServices" value="1" onclick="getDiv('specialDiv',this.id)" >-->
                     <!--                       <span class="lbl"> Special Services</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!--               <div class="checkbox">-->
                     <!--                   <label class="block">-->
                     <!--                       <input type="checkbox" class="ace ace-checkbox-2" name="productionTalent" id="productionTalent" value="1" onclick="getDiv('productionDiv',this.id)" >-->
                     <!--                       <span class="lbl"> Production/Talent</span>-->
                     <!--                   </label>-->
                     <!--               </div>-->
                     <!-- /section:custom/checkbox -->
                     <!--           </div>-->
                     <!--       </div>-->
                     <!--   </div>-->
                     <div class="col-sm-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">
                           How did you hear about Digiwaxx?
                           </label>
                           <div class="control-group">
                              <select name="howheard" id="howheard" class="selectpicker input form-control" onchange="howHeard()">
                                 <option value="Internet Search">Internet Search</option>
                                 <option value="Magazine Article">Magazine Article</option>
                                 <option value="Record Pool">Record Pool</option>
                                 <option value="DJ Crew">DJ Crew</option>
                                 <option value="A Current Member">A Current Member</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12" id="howHeardDiv" style="display:none;">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1">
                           Name
                           </label>
                           <div class="control-group">
                              <input name="howheardvalue" class="form-control" id="howheardvalue" placeholder="Name" size="25" maxlength="255">
                           </div>
                        </div>
                     </div>
                     <div class="col-xs-12">
                        <div class="text-right form-actions">
                           <button class="btn btn-info btn-sm" type="submit" name="addMember">
                           <i class="ace-icon fa fa-check bigger-110"></i>
                           Add Member
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
      <!-- /.page-content -->
   </div>
   <!-- /.main-content-inner -->
</div>
<!-- /.main-content-->

<script>
   function howHeard() {
       howheard_dropdown = document.getElementById('howheard');
       howheard_value = howheard_dropdown.options[howheard_dropdown.selectedIndex].value;
       howheard_referred = document.getElementById('howHeardDiv');
       //howheardvalueinput = document.getElementById('howheardvalue');
       if (howheard_value == "Record Pool" || howheard_value == "A Current Member" || howheard_value == "DJ Crew") {
           howheard_referred.style.display = "";
           howheardvalueinput.style.display = "";
       } else {
           howheard_referred.style.display = "none";
           howheardvalueinput.style.display = "none";
           //howheardvalueinput.value="";
       }
   }
   
   function checkEmail(email) {
       $.ajax({
           url: "add_member?email=" +email+ "&verifyEmail=1",
           success: function(result) {
               row = JSON.parse(result);
               if (row.response == 1) {
                   document.getElementById('verifyEmail').value = 0;
               } else {
                   document.getElementById('verifyEmail').value = 1;
               }
               //  return false;
           }
       });
   }
   
   function validate() {
       var firstName = document.getElementById('firstName');
       var lastName = document.getElementById('lastName');
       var phone = document.getElementById('phone');
       var email = document.getElementById('email');
       var stageName = document.getElementById('stageName');
       var userName = document.getElementById('member_username');
       var password = document.getElementById('member_password');
       var confirmPassword = document.getElementById('confirmPassword');
       var city = document.getElementById('city');
       var country = document.getElementById('country');
       var gender = document.querySelector('[name="sex"]:checked');
       var verifyEmail = document.getElementById('verifyEmail');  
       
       var numericExp = /^[-+]?[0-9]+$/;
       var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
       if (firstName.value.length < 1) {
           alert("Please enter first name!");
           firstName.focus();
           return false;
       }
       if (lastName.value.length < 1) {
           alert("Please enter last name!");
           lastName.focus();
           return false;
       }
       if (phone.value.length < 1) {
           alert("Please enter a valid phone number!");
           phone.focus();
           return false;
       }
       if (!(email.value.match(emailExp))) {
           alert("Please enter a valid email address!");
           email.focus();
           return false;
       }
       if (stageName.value.length < 1) {
           alert("Please enter stage name!");
           stageName.focus();
           return false;
       }
       if (userName.value.length < 1) {
           alert("Please enter username!");
           userName.focus();
           return false;
       }
       if (password.value.length < 8) {
           alert("Password shouldnt be less than 8 characters!");
           password.focus();
           return false;
       }
       if (!(password.value == confirmPassword.value)) {
           alert("Confirm password doesnt match!");
           confirmPassword.focus();
           return false;
       }
       if (city.value.length < 1) {
           alert("Please enter city!");
           city.focus();
           return false;
       }
       if (country.value.length < 1) {
           alert("Please enter country!");
           country.focus();
           return false;
       }
       if (gender == null || !((gender.value == 'male') || (gender.value == 'female'))) {
           alert("Please enter a valid gender!");
           // gender.focus();//
           return false;
       }
   if(verifyEmail.value==0)   
    {   
        alert("Entered email already exists, try another one!");   
        email.focus();   
        return false;     
      }
   }
   
   function getDiv(divId, id) {
       if (document.getElementById(id).checked == true) {
           document.getElementById(divId).style.display = 'block';
       } else {
           document.getElementById(divId).style.display = 'none';
       }
   }
   
   function getRadioInner(divId, id) {
       if (document.getElementById(id).checked == true) {
           document.getElementById(divId).style.display = 'block';
       } else {
           document.getElementById(divId).style.display = 'none';
       }
   }
   
   function getManagementInner() {
       if (document.getElementById('managementArtist').checked == true || document.getElementById('managementPersonal').checked == true) {
           document.getElementById('managementInnerDiv').style.display = 'block';
       } else {
           document.getElementById('managementInnerDiv').style.display = 'none';
       }
   }
   
   function getStates(cid) {
       var xhttp = new XMLHttpRequest();
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               var states_list = this.responseText;
               var obj = JSON.parse(states_list);
               var display_states = '';
               for (var i = 0; i <= obj.length; i++) {
                   display_states += "<option value='" + obj[i]['state_id'] + "'>" + obj[i]['state'] + "</option>";
               }
               document.getElementById('state').innerHTML = display_states;
           }
       };
       xhttp.open("GET", "add_member?gs=1&cid=" + cid, true);
       xhttp.send();
   }
</script>
@endsection

