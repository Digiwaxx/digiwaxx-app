

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>My Dashboard</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  <div class="mlr-blk f-block">
                     <h5>MY LABEL REPS</h5>
                     <h6 class="mb-4">LABEL REP CONTACT INFORMATION</h6>
                     <?php if(isset($alert_class)) 
                        { ?>
                     <div class="<?php echo $alert_class; ?>">
                        <p><?php echo $alert_message; ?></p>
                     </div>
                     <?php } ?>
                     <form action="" method="post" id="addRep" autocomplete="off">
                        @csrf
                        <div class="form-group">
                           <input name="title" id="title" class="form-control input"  size="20" placeholder="Title" type="text">
                        </div>
                        <div class="form-group">
                           <input name="name" id="name" class="form-control input"  size="20" placeholder="Name" type="text">
                        </div>
                        <div class="form-group" id="emailDiv">
                           <input name="email[]" id="email" class="form-control input emailInput"  size="20" placeholder="Email" type="text">
                        </div>
                        <div class="eadd" id="eadd">
                           <!-- For Additional Email Address So Dont Remove This Div -->
                        </div>
                        <input type="text" id="numEmail" value="1" style="display:none;" size="4"   />
                        <a href="javascript:void()" class="addRemoveLinks" onclick="addEmail()"><i class="fa fa-plus-circle"></i>
                        <span>Add</span>
                        </a>
                        <a href="javascript:void()" class="addRemoveLinks" onclick="removeEmail()"><i class="fa fa-minus-circle"></i>
                        <span>Remove </span>
                        </a>
                        <!--
                           <div class="blink"><a href="#emailDiv"><i class="fa fa-plus-circle"></i></a> add another E-mail address</div>
                           
                           -->
                        <div class="row mt-3">
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="form-group">
                                 <input name="phone[]" id="phone"   class="form-control input"  size="20" placeholder="Phone" type="text">
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="form-group">
                                 <input name="mobile" id="mobile"  class="form-control input"  size="20" placeholder="Mobile" type="text">
                              </div>
                           </div>
                        </div>
                        <div class="padd" id="padd">
                           <!-- For Additional Phone Number So Dont Remove This Div -->
                        </div>
                        <!--
                           <div class="plink"><a href="#"><i class="fa fa-plus-circle"></i></a> add another phone number</div>
                           
                           -->
                        <input type="text" id="numPhone" value="1" style="display:none;" size="4"   />
                        <a href="javascript:void()" class="addRemoveLinks" onclick="addPhone()"><i class="fa fa-plus-circle"></i>
                        <span>Add</span>
                        </a>
                        <a href="javascript:void()" class="addRemoveLinks" onclick="removePhone()"><i class="fa fa-minus-circle"></i>
                        <span>Remove </span>
                        </a>
                        <div class="form-group mt-3">
                           <input name="cancel" onclick="cancelForm()" class="btn btn-alt btn pull-left bsp" value="CANCEL" type="button">
                           <input name="addLabelRep" class="btn btn-alt btn pull-right bsp" value="SUBMIT" type="submit">
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-12">
               @include('clients.dashboard.includes.my-tracks')
            </div>
         </div>
      </div>
   </div>
</section>
<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
   var googletag = googletag || {};
   googletag.cmd = googletag.cmd || [];
</script>
<script>
   googletag.cmd.push(function() {
     googletag.defineSlot('/21741445840/336x280', [240, 133], 'div-gpt-ad-1539597853871-0').addService(googletag.pubads());
     googletag.pubads().enableSingleRequest();
     googletag.enableServices();
   });
</script>
<!-- /21741445840/336x280 -->
<!-- <div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div> -->


<script>
   function removeEmail()
   
   {
   
   
     var numEmail = document.getElementById('numEmail').value;   
     var id = parseInt(numEmail)-1;           
     if(numEmail>1)
   
     {
      document.getElementById('eadd').removeChild(document.getElementById('emailDiv'+id));
   
   document.getElementById('numEmail').value = id;
   
     }  
   
   }
   function removePhone()
   
   {
     var numPhone = document.getElementById('numPhone').value;   
     var id = parseInt(numPhone)-1; 
        
     if(numPhone>1)
   
     {
   
      document.getElementById('padd').removeChild(document.getElementById('phoneDiv'+id));
   
   document.getElementById('numPhone').value = id;
     }     
   }
   
   function addEmail()
   
   {
   
      var numEmail = document.getElementById('numEmail').value;   
   if(numEmail<3)
   
   {
   var div = document.createElement("div");   
       div.setAttribute("class", "form-group");   
    div.setAttribute("id", "emailDiv"+numEmail);     
   var emailInput = document.createElement("input");   
    emailInput.setAttribute("type", "text");   
    emailInput.setAttribute("name", "email[]");   
    emailInput.setAttribute("id", "email"+numEmail);   
    emailInput.setAttribute("class", "form-control emailInput");   
    emailInput.setAttribute("placeholder", "Email");   
   
   div.appendChild(emailInput);         
   document.getElementById('eadd').appendChild(div);   
   document.getElementById('numEmail').value = parseInt(numEmail)+1;
   
     }
   }
   
   function addPhone()
   
   {
var numPhone = document.getElementById('numPhone').value;   
   if(numPhone<3)   
   {
   
   var div = document.createElement("div");   
       div.setAttribute("class", "form-group");   
    div.setAttribute("id", "phoneDiv"+numPhone);   
   var phoneInput = document.createElement("input");   
    phoneInput.setAttribute("type", "text");
    phoneInput.setAttribute("name", "phone[]");   
    phoneInput.setAttribute("id", "phone"+numPhone);   
    phoneInput.setAttribute("class", "form-control");   
    phoneInput.setAttribute("placeholder", "Phone");
   
   
   div.appendChild(phoneInput);         
   document.getElementById('padd').appendChild(div);         
      document.getElementById('numPhone').value = parseInt(numPhone)+1;   
     }
   }
   
   function cancelForm()   
   {   
    document.getElementById("addRep").reset();   
    window.location = 'Client_label_reps';
      }
        
</script>
<script>
   // Wait for the DOM to be ready
   $(function() {  
      
   $("#addRep").validate();     
   
   $("#title").rules("add", {   
          required:true,   
          messages: {
   
                 required: "Please enter title."   
          }   
       }); 
   
   
   $("#name").rules("add", {   
          required:true,   
          messages: {   
                 required: "Please enter name"   
          }
    });      
      
   $("#email").rules("add", {   
          required:true,   
     email: true,   
          messages: {
   
                 required: "Please enter a valid email id"   
          }   
       });       
   
    $('.emailInput').each(function () {   
     $(this).rules("add", {   
         required: true,   
   email: true,   
   messages: {   
                 required: "Please enter a valid email id ram"   
          }   
     });   
   });       
   
   /*  $("#email1").rules("add", {   
          required:true,   
     email: true,   
          messages: {   
                 required: "Please enter a valid email id"   
          }   
       });
   
   $("#email2").rules("add", {
             required:true,   
     email: true,   
          messages: {   
                 required: "Please enter a valid email id"   
          }   
       });  
   
   minlength:10,
    maxlength:10,   
    number: true,   
    */  
   
   $("#phone").rules("add", {   
          required:true,   
     messages: {   
                 required: "Please enter phone number"   
          }   
       });
   
   
   
   $("#phone1").rules("add", {   
          required:true,   
    messages: {   
                 required: "Please enter phone number"   
          }   
       });
   
   $("#phone2").rules("add", {   
          required:true,   
    messages: {   
                 required: "Please enter phone number"   
          }   
       });   
     
   $("#mobile").rules("add", {   
          required:true,   
    messages: {   
                 required: "Please enter mobile number"   
          }   
       });
   
   
    /*  // Initialize form validation on the registration form.
   
   // It has the name attribute "registration"
   
   $("form[id='registration']").validate({
   
     // Specify validation rules
   
   
   
   
   
     rules: {
   
       // The key name on the left side is the name attribute
   
       // of an input field. Validation rules are defined
   
       // on the right side
   
       field: "required",
   
       lastname: "required",
   
       email: {
   
         required: true,
   
         // Specify that email should be validated
   
         // by the built-in "email" rule
   
         email: true
   
       },
   
       password: {
   
         required: true,
   
         minlength: 5
   
       }
   
     },
   
     // Specify validation error messages
   
     messages: {
   
       title: "Please enter title",
   
       lastname: "Please enter your lastname",
   
       password: {
   
         required: "Please provide a password",
   
         minlength: "Your password must be at least 5 characters long"
   
       },
   
       email: "Please enter a valid email address"
   
     },
   
     // Make sure the form is submitted to the destination defined
   
     // in the "action" attribute of the form when valid
   
     submitHandler: function(form) {
   
       form.submit();
   
     }
   
   });*/
   
   });
   
   
   
</script>
@endsection