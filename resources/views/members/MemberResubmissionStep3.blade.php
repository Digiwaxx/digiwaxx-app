@extends('layouts.app')

@section('content')
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
                    <h2 class="text-center">Create a Member Account</h2>
                    
                </div>
        </div>
        <div class="modal-body">
            <h3 class="text-center mb-4">My Information</h3>
            <form action="" method="post" id="registrationForm" autocomplete="off">   
				@csrf
                <div class="form-group"><span class="man"></span>
                    <input name="firstName" id="firstName" class="form-control input" size="20" placeholder="First name" type="text" value="<?php echo urldecode($memberInfo['data'][0]->fname); ?>">
                </div>
                <div class="form-group"><span class="man"></span>
                    <input name="lastName" id="lastName" class="form-control input" size="20" placeholder="Last name" type="text" value="<?php echo urldecode($memberInfo['data'][0]->lname); ?>">
                </div>

                <div class="form-group"><span class="man"></span>
                    <select name="sex" id="sex" class="selectpicker form-control">
                        <option value="">Sex</option>
                        <option <?php if (strcmp('male', urldecode($memberInfo['data'][0]->sex)) == 0) { ?> selected="selected" <?php } ?> value="male">Male</option>
                        <option <?php if (strcmp('female', urldecode($memberInfo['data'][0]->sex)) == 0) { ?> selected="selected" <?php } ?> value="female">Female</option>
                    </select>
                </div>
                <div class="form-group"><span class="man"></span>
                    <input name="stageName" id="stageName" class="form-control input" size="20" placeholder="Stage name/DJ name (proper spelling and caps)" type="text" value="<?php echo urldecode($memberInfo['data'][0]->stagename); ?>">
                </div>
                <div class="form-group"><span class="man"></span>
                    <input name="phone" id="phone" class="form-control input" size="20" placeholder="Phone Number" type="text" value="<?php echo urldecode($memberInfo['data'][0]->phone); ?>">
                </div>
                <div class="form-group"><span class="man"></span>
                    <input name="email" id="email" class="form-control input" size="20" placeholder="Alert Email Address" type="text" value="<?php echo urldecode($memberInfo['data'][0]->email); ?>">
                </div>

                <div class="form-group">
                    <label style="margin-right:10px;">Are you a playlist contributor?</label>
                    <div class="radio dja">
                        <label>
                            <input type="radio" name="playlist_contributor" id="playlist_contributor1" value="1" <?php if ($memberInfo['data'][0]->playlist_contributor == 1) { ?> checked <?php } ?> onclick="getContributor()">
                            Yes
                        </label>
                    </div>
                    <div class="radio dja">
                        <label>
                            <input type="radio" name="playlist_contributor" id="playlist_contributor2" value="0" <?php if ($memberInfo['data'][0]->playlist_contributor == 0) { ?> checked <?php } ?> onclick="getContributor()"> No
                        </label>
                    </div>
                </div>
                <?php if ($memberInfo['data'][0]->playlist_contributor == 1) {
                    $contributor_display = 'block';
                } else {
                    $contributor_display = 'none';
                } ?>
                <div class="form-group djf" id="contributorDiv" style="display:<?php echo $contributor_display; ?>">
                    <label>What music streaming services(s):</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="1" name="playlists[]" id="tidal">
                            Tidal
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="2" name="playlists[]" id="spotify">
                            Spotify
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="3" name="playlists[]" id="apple_music">
                            Apple Music
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="4" name="playlists[]" id="youtube">
                            YouTube
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="5" name="playlists[]" id="pandora">
                            Pandora
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label style="margin-right:10px;">Do you DJ for an artist?</label>
                    <div class="radio dja">
                        <label>
                            <input type="radio" name="artist" id="artist1" value="1" checked>
                            Yes
                        </label>
                    </div>
                    <div class="radio dja">
                        <label>
                            <input type="radio" name="artist" id="artist2" value="0">
                            No
                        </label>
                    </div>
                </div>
                
                <div class="btn-center">
                    <input name="addMember" class="login_btn btn btn-theme btn-gradient" value="Continue" type="submit">
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
<!-- <script src="<?php // echo base_url('assets/js/jquery.validate.min.js'); 
                    ?>"></script> -->
<script>
    function howHeard() {
        howheard_dropdown = document.getElementById('howheard');
        howheard_value = howheard_dropdown.options[howheard_dropdown.selectedIndex].value;
        howheard_referred = document.getElementById('howHeardDiv');
        howheardvalueinput = document.getElementById('howheardvalue');
        if (howheard_value == "Record Pool" || howheard_value == "A Current Member" || howheard_value == "DJ Crew") {
            howheard_referred.style.display = "";
            howheardvalueinput.style.display = "";
        } else {
            howheard_referred.style.display = "none";
            howheardvalueinput.style.display = "none";
            howheardvalueinput.value = "";
        }
    }
    // Wait for the DOM to be ready
    //$(function() {
    $(document).ready(function() {
        $("#registrationForm").validate();
        // $("#website").rules("add", {
        //          required:true,
        //          messages: {
        //                 required: "Please enter website."
        //          }
        //       });
        //  $("#age").rules("add", {
        //          required:true,
        //          messages: {
        //                 required: "Please enter Date of Birth."
        //          }
        //       });
        $("#sex").rules("add", {
            required: true,
            messages: {
                required: "Please select sex."
            }
        });
        $("#stageName").rules("add", {
            required: true,
            messages: {
                required: "Please enter stage name."
            }
        });
        
        $("#howheard").rules("add", {
            required: true,
            messages: {
                required: "Please tell us, how did you hear about us."
            }
        });
        
        
        $("#email").rules("add", {
            required: true,
            messages: {
                required: "Please enter email."
            }
        });
    });
    function getContributor() {
        if (document.getElementById('playlist_contributor1').checked == true) {
            document.getElementById('contributorDiv').style.display = 'block';
        } else {
            document.getElementById('contributorDiv').style.display = 'none';
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
</script>
@endsection