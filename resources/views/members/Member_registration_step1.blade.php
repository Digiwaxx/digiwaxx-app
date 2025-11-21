@extends('layouts.app')

<style>
    #ui-datepicker-div {
    z-index: 999!important;
}
</style>

@section('content')
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
                                    <h2 class="text-center">Create a Member Account</h2>
                                    <p class="text-center areg">Already registered? <a href="{{ url('login') }}">Click here
                                            to log in</a> </p>
                                    <div class="donate-icon">
                                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                            <input type="hidden" name="cmd" value="_donations" />
                                            <input type="hidden" name="business" value="paypal@digiwaxx.com" />
                                            <input type="hidden" name="item_name" value="Digiwaxx" />
                                            <input type="hidden" name="currency_code" value="USD" />
                                            <input type="image"
                                                src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif"
                                                border="0" name="submit"
                                                title="PayPal - The safer, easier way to pay online!"
                                                alt="Donate with PayPal button" />
                                            <img alt="" border="0"
                                                src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1"
                                                height="1" />
                                        </form>

                                    </div>
                                </div>



                            </div>

                            <div class="modal-body">
                                <div class="desc">
                                    <p>
                                        The <strong>Digital Waxx Service</strong> is exclusively for DJs, On-Air
                                        Personalities, Program Directors, Music Directors, Label Executives and music
                                        industry tastemakers. To ensure the integrity of the service, we strive to make sure
                                        that everyone who registers meets these requirements. Once you have completed your
                                        application, it will go through a verification process. </p>
                                    <p>Once approved, <strong>Digiwaxx</strong> will send you a confirmation e-mail
                                        regarding your approval and you'll be ready to experience the Digital Waxx
                                        difference!</p>

                                    <p>Please complete the application below to start your new account & <strong>download
                                            free new music!</strong></p>
                                    </p>
                                </div>



                                <?php if(isset($_GET['emailExists']))  { ?>
                                <div class="alert alert-danger">Email already exists!</div>
                                <?php } ?>




                                <form action="" method="post" name="myForm" id="registrationForm" autocomplete="off">
                                    @csrf
                                    <h2 class="text-center" style="margin:30px 0px;">Account Information</h2>

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group"> <span class="man"></span>
                                                <input name="firstName" id="firstName" class="form-control input"
                                                    size="20" placeholder="First name" type="text"
                                                    value="{{ Session::get('sess-member-firstName') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group"> <span class="man"></span>
                                                <input name="lastName" id="lastName" class="form-control input"
                                                    size="20" placeholder="Last name" type="text"
                                                    value="{{ Session::get('sess-member-lastName') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group"> <span class="man"></span>
                                                <input name="phone" id="phone" class="form-control input"
                                                    size="20" placeholder="Phone Number" type="text"
                                                    value="{{ Session::get('sess-member-phone') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group"> <span class="man"></span>
                                                <input name="email" id="email" class="form-control input"
                                                    size="20" placeholder="Alert Email Address" type="text"
                                                    value="{{ Session::get('sess-member-email') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group"> <span class="man"></span>
                                                <input name="dob" id="dob" class="form-control input"
                                                    placeholder="Date of Birth" type="text"
                                                    value="{{ Session::get('sess-member-dob') }}" required>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <span class="man"></span>
                                        <input name="username" id="username" class="form-control input"
                                            onchange="checkAvailability1(this.value)" size="20"
                                            placeholder="Username" type="text" required
                                            value="{{ Session::get('sess-member-username') }}">
                                    </div>




                                    <div class="form-group"> <span class="man"></span>
                                        <input name="password" id="password" class="form-control input" size="20"
                                            placeholder="Password" type="password" value="">
                                        <div>
                                            <a href="javascript:void()" onclick="randomPassword(8)" style="float:left;"
                                                class="btn btn-theme btn-gradient  mb-4"> Generate Password </a>
                                            <a href="javascript:void()" id="hideKey" onclick="hidePassword(1)"
                                                style="display:none; float:left; margin-left:20px;"
                                                class="btn btn-theme btn-alt"> Hide </a>
                                        </div>
                                    </div>

                                    <div style="clear:both;"></div>
                                    <div class="form-group"> <span class="man"></span>
                                        <input name="password1" id="password1" class="form-control input" size="20"
                                            placeholder="Confirm password" type="password" value="">
                                        <span class="help-text">(Username and password must have at least 8 characters and
                                            may only contain letters and numbers)</span>
                                    </div>



                                    <div>
                                        <input name="addMember" class="login_btn btn btn-theme btn-gradient"
                                            value="Continue" type="submit" />
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

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}



    <!-- Register Block Ends -->

    <script>
        $(function() {
            $("#dob").datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: 0, // Restrict selection to past dates only
            });
        });

        function checkAvailability(username) {

            alert("ok");
            // $.ajax({url: "Member_registration_step1/validate_username?username="+username, success: function(result){
            //var obj = JSON.parse(result);
            //  alert(result);
            //	    alert(obj.msg);
            /*
            		 var count = obj.length; 
            	     var liList = '';
            		 var optionList = '';
            		for (var i=0;i<count;i++) 
            		 {
            		 
            		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
            		  
            		   optionList += '<option value="'+obj[i].id+'">'+obj[i].name+'</option>';
            		 
            		 }
            		
            		 document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = liList;
            		 document.getElementById('country').innerHTML = optionList;*/

            // }});


        }


        function hidePassword(id) {

            if (id == 1) {
                document.getElementById('password').setAttribute('type', 'password');
                document.getElementById('password1').setAttribute('type', 'password');
                document.getElementById('hideKey').innerHTML = 'View';
                document.getElementById('hideKey').setAttribute('onClick', 'hidePassword(2)');
            } else if (id == 2) {
                document.getElementById('password').setAttribute('type', 'text');
                document.getElementById('password1').setAttribute('type', 'text');
                document.getElementById('hideKey').innerHTML = 'Hide';
                document.getElementById('hideKey').setAttribute('onClick', 'hidePassword(1)');
            }




        }

        function randomPassword(length) {
            var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }


            document.getElementById('password').setAttribute('type', 'text');
            document.getElementById('password1').setAttribute('type', 'text');

            document.getElementById('password').value = pass;
            document.getElementById('password1').value = pass;

            document.getElementById('hideKey').innerHTML = 'Hide';
            document.getElementById('hideKey').style.display = 'block';
            //document.getElementById('hideKey').setAttribute('onClick','hidePassword(1)');		
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
                return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(
                    value);
            });






            $("#firstName").rules("add", {
                required: true,
                alphabetsnspace: true,
                maxlength: 20,
                messages: {
                    required: "Please enter first name.",
                    alphabetsnspace: "Enter only alphabets",
                    maxlength: "Name cannot exceed 20 characters. "
                }
            });

            $("#lastName").rules("add", {
                required: true,
                alphabetsnspace: true,
                maxlength: 20,
                messages: {
                    required: "Please enter last name.",
                    alphabetsnspace: "Enter only alphabets",
                    maxlength: "Name cannot exceed 20 characters. "
                }
            });


            $("#phone").rules("add", {
                required: true,
                numbers: true,
                messages: {
                    required: "Please enter phone.",
                    numbers: "Enter only numbers"
                }
            });

            $("#email").rules("add", {
                required: true,
                emailid: true,
                messages: {
                    required: "Please enter email id.",
                    emailid: "Enter a valid email id"
                }
            });


            $("#username").rules("add", {
                required: true,
                alphanumeric: true,
                minlength: 8,
                maxlength: 20,
                messages: {
                    required: "Please enter Username.",
                    alphanumeric: "Username cannot contain special characters and space onl is not allowed",
                    minlength: "Username must have atleast 8 characters.",
                    maxlength: "Username cannot exceed 20 characters. "

                }
            });

            $("#password").rules("add", {
                required: true,
                minlength: 8,
                messages: {
                    required: "Please enter password."
                }
            });

            $("#password1").rules("add", {
                required: true,
                equalTo: "#password",
                messages: {
                    required: "Please enter the same password again."
                }
            });






        });
    </script>
@endsection
