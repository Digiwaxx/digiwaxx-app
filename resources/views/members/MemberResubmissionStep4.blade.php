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
            <h2 class="text-center" style="margin:50px 20px;">MY PREFERENCES</h2>
            <form action="" method="post" id="registrationForm">
			@csrf
                <!--<div class="form-group">-->
                <!--    <label style="margin-right:10px;">Audio files quality?</label>-->
                <!--    <div class="radio dja">-->
                <!--        <label>-->
                <!--            <input type="radio" name="audioQuality" id="optionsRadios1" value="192" <?php if (strcmp('192', $memberInfo['data'][0]->audioQuality) == 0) { ?> checked="checked" <?php } ?>>-->
                <!--            192-->
                <!--        </label>-->
                <!--    </div>-->
                <!--    <div class="radio dja">-->
                <!--        <label>-->
                <!--            <input type="radio" name="audioQuality" id="optionsRadios2" value="256" <?php if (strcmp('256', $memberInfo['data'][0]->audioQuality) == 0) { ?> checked="checked" <?php } ?>>-->
                <!--            256-->
                <!--        </label>-->
                <!--    </div>-->
                <!--    <div class="radio dja">-->
                <!--        <label>-->
                <!--            <input type="radio" name="audioQuality" id="optionsRadios1" value="320" <?php if (strcmp('320', $memberInfo['data'][0]->audioQuality) == 0) { ?> checked="checked" <?php } ?>>-->
                <!--            320-->
                <!--        </label>-->
                <!--    </div>-->
                <!--    <div class="radio dja">-->
                <!--        <label>-->
                <!--            <input type="radio" name="audioQuality" id="optionsRadios2" <?php if (strcmp('wave', $memberInfo['data'][0]->audioQuality) == 0) { ?> checked="checked" <?php } ?> value="wave">-->
                <!--            WAVE-->
                <!--        </label>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<div class="form-group djf">-->
                <!--    <label>What format do you use to play records? Check all that applies:</label>-->
                <!--    <div class="checkbox">-->
                <!--        <label>-->
                <!--            <input type="checkbox" value="1" name="mp3">-->
                <!--            MP3/Digital-->
                <!--        </label>-->
                <!--    </div>-->
                <!--    <div class="checkbox">-->
                <!--        <label>-->
                <!--            <input type="checkbox" value="1" name="cd">-->
                <!--            CD-->
                <!--        </label>-->
                <!--    </div>-->
                <!--    <div class="checkbox">-->
                <!--        <label>-->
                <!--            <input type="checkbox" value="1" name="vinyl">-->
                <!--            Vinyl-->
                <!--        </label>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<div class="row">-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                            <!--   <input type="text"  class="form-control" id="computerType" name="computerType" placeholder="Computer type">-->
                <!--            <select name="computerType" size="1" id="computerType" class="form-control input selectpicker">-->
                <!--                <option <?php if (strcmp('PC', urldecode($memberInfo['data'][0]->computer)) == 0) { ?> selected="selected" <?php } ?> value="PC">PC</option>-->
                <!--                <option <?php if (strcmp('Mac', urldecode($memberInfo['data'][0]->computer)) == 0) { ?> selected="selected" <?php } ?> value="Mac">Mac</option>-->
                <!--            </select>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" class="form-control" name="turntablesType" id="turntablesType" placeholder="Turntables type" value="<?php echo $memberInfo['data'][0]->turntables_type; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div style="clear:both;"></div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" id="mixerType" name="mixerType" class="form-control" placeholder="Mixer type" value="<?php echo $memberInfo['data'][0]->mixer_type; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" name="needlesType" id="needlesType" class="form-control" placeholder="Needles type" value="<?php echo $memberInfo['data'][0]->needles_type; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div style="clear:both;"></div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" id="headphones" name="headphones" placeholder="Headphones" class="form-control" value="<?php echo $memberInfo['data'][0]->headphones; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                            <!--    <input type="text" id="mp3Player" name="mp3Player" placeholder="mp3 player" class="form-control">-->
                <!--            <select name="mp3Player" size="1" id="mp3Player" class="form-control input selectpicker">-->
                <!--                <option <?php if (strcmp('Windows Media Player', urldecode($memberInfo['data'][0]->player)) == 0) { ?> selected="selected" <?php } ?> value="Windows Media Player">Windows Media Player</option>-->
                <!--                <option <?php if (strcmp('Real Player', urldecode($memberInfo['data'][0]->player)) == 0) { ?> selected="selected" <?php } ?> value="Real Player">Real Player</option>-->
                <!--                <option <?php if (strcmp('Quicktime', urldecode($memberInfo['data'][0]->player)) == 0) { ?> selected="selected" <?php } ?> value="Quicktime">Quicktime</option>-->
                <!--            </select>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div style="clear:both;"></div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" name="gameSystem" id="gameSystem" placeholder="Game system" class="form-control" value="<?php echo $memberInfo['data'][0]->game_system; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" name="cellPhone" id="cellPhone" placeholder="Cell phone" class="form-control" value="<?php echo $memberInfo['data'][0]->cell_phone; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div style="clear:both;"></div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" name="hatSize" id="hatSize" placeholder="Hat size" class="form-control" value="<?php echo $memberInfo['data'][0]->hat_size; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" name="shirtSize" id="shirtSize" placeholder="Shirt size" class="form-control" value="<?php echo $memberInfo['data'][0]->shirt_size; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div style="clear:both;"></div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" id="pantsSize" name="pantsSize" placeholder="Pants size" class="form-control" value="<?php echo $memberInfo['data'][0]->pants_size; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="col-sm-6">-->
                <!--        <div class="form-group">-->
                <!--            <input type="text" name="shoeSize" id="shoeSize" placeholder="Shoe size" class="form-control" value="<?php echo $memberInfo['data'][0]->shoe_size; ?>">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->              
                <div class="btn-center">
                    <input name="addMember4" class="login_btn btn btn-theme btn-gradient" value="Continue" type="submit">
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