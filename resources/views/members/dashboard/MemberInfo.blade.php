@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
            <div class="dash-heading">
                <h2>My Info 
                    
                </h2>                
            </div>
          
              <div class="tabs-section">
                    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
					@csrf                     
                    
                    <div class="myinfo-block row mt-4">
                        <div class="col-12">
                            <div class="form-group">
                                <?php
                                $memberImage_get = Session::get('memberImage');
    				// 			$memberId = Session::get('memberId');
    							
    						    
    							if(is_numeric($memberImage_get)){
    							    $img= url('/pCloudImgDownload.php?fileID='.$memberImage_get);
    							}
                                else if (strlen(session()->get('memberImage')) > 4) {
                                    $img = url("member_images/" . session()->get('memberId') . "/" . session()->get('memberImage'));
                                } else {
                                    $img = 'assets/img/profile-pic.png';
                                }
                                ?>
                                <img src="<?php echo $img; ?>" class="pimg" style="">
                                <span class="btn-float-right">
                                    <a href="<?php echo url('Member_edit_profile'); ?>" class="btn-link btn"> Edit My Profile </a>
                                </span>
                            </div>
                        </div>
                        <!--<div class="row">-->
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group ">
                                 <label class="">User ID</label>
                                <input class="form-control input" disabled="disabled" value="<?php echo $memberInfo['data'][0]->uname; ?>" />
                            </div>
                        </div>
                       
                        
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="">First Name</label>
                                <input name="firstName" disabled="disabled" class="form-control input" size="20" placeholder="First Name" type="text" value="<?php echo $memberInfo['data'][0]->fname; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="">Last Name</label>
                                <input name="lastName" disabled="disabled" class="form-control input" size="20" placeholder="Last Name" type="text" value="<?php echo $memberInfo['data'][0]->lname; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="">Stage / DJ Name</label>
                                <input name="stageName" disabled="disabled" class="form-control input" size="20" placeholder="Stage Name" type="text" value="<?php echo urldecode($memberInfo['data'][0]->stagename); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="">E-mail Addrwess</label>
                                <input name="email" disabled="disabled" class="form-control input" size="20" placeholder="Email" type="text" value="<?php echo urldecode($memberInfo['data'][0]->email); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="">Address 1</label>
                                <input name="address1" disabled="disabled" class="form-control input" size="20" placeholder="Address" type="text" value="<?php echo $memberInfo['data'][0]->address1; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="">Address 2</label>
                                <input name="address2" disabled="disabled" class="form-control input" size="20" placeholder="Address" type="text" value="<?php echo $memberInfo['data'][0]->address2; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="">City</label>
                                <input name="city" disabled="disabled" class="form-control input" size="20" placeholder="City" type="text" value="<?php echo $memberInfo['data'][0]->city; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="">State</label>
                                <input name="state" disabled="disabled" class="form-control input" size="20" placeholder="State" type="text" value="<?php echo $memberInfo['data'][0]->state; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="">Country</label>
                                <input name="country" disabled="disabled" class="form-control input" size="20" placeholder="Country" type="text" value="<?php echo $memberInfo['data'][0]->country; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="">zip</label>
                                <input name="zip" disabled="disabled" class="form-control input" size="20" placeholder="Zipcode" type="text" value="<?php echo $memberInfo['data'][0]->zip; ?>">
                            </div>
                        </div>
                        <!--<div class="form-group">
             <label class="col-sm-3">age</label>
                 <div class="col-sm-9">
                    <input name="age" disabled="disabled" class="form-control input" size="20" placeholder="Age" type="text" value="<?php echo $memberInfo['data'][0]->age; ?>">
</div>
             </div>-->
                        <div class="col-lg-6 col-md-6 col-sm-6">                            
                            <div class="form-group">
                                <label class="">Gender</label>
                                <input name="reg" disabled="disabled" class="form-control input" size="20" placeholder="First Name" type="text" value="<?php echo $memberInfo['data'][0]->sex; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">                            
                            <div class="form-group">
                                <label class="">Phone No.</label>
                                <input name="phone" disabled="disabled" class="form-control input" size="20" placeholder="Phone" type="text" value="<?php echo $memberInfo['data'][0]->phone; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">                            
                            <div class="form-group">
                                <label class="">Website</label>
                                <input name="website" disabled="disabled" class="form-control input" size="20" placeholder="Website" type="text" value="<?php if(!empty($socialInfo['data'][0]->website)){ echo $memberInfo['data'][0]->website; } ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">                            
                            <div class="form-group">
                                <label class="">Facebook</label>
                                <input name="website" disabled="disabled" class="form-control input" size="20" placeholder="Facebook" type="text" value="<?php if(!empty($socialInfo['data'][0]->facebook)){ echo $socialInfo['data'][0]->facebook; } ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">                            
                            <div class="form-group">
                                <label class="">Twitter</label>
                                <input name="website" disabled="disabled" class="form-control input" size="20" placeholder="Twitter" type="text" value="<?php if(!empty($socialInfo['data'][0]->twitter)){  echo $socialInfo['data'][0]->twitter; } ?>">
                            </div>
                        </div>
                        <div class=" col-lg-6 col-md-6 col-sm-12">                            
                            <div class="form-group">
                                <label class="">Instagram</label>
                                <input name="website" disabled="disabled" class="form-control input" size="20" placeholder="Instagram" type="text" value="<?php if(!empty($socialInfo['data'][0]->instagram)){ echo $socialInfo['data'][0]->instagram; } ?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">                            
                            <div class="form-group">
                                <label class="">Linkedin</label>
                                <input name="website" disabled="disabled" class="form-control input" size="20" placeholder="Linkedin" type="text" value="<?php if(!empty($socialInfo['data'][0]->linkedin)){  echo $socialInfo['data'][0]->linkedin; } ?>">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">                            
                            <div class="form-group">
                                <label class="">What type of computer do you use?</label>
                                <select name="computer" disabled="disabled" size="1" id="computer" class="form-control input selectpicker">
                                    <option <?php if (isset($memberInfo['data'][0]->computer) && strcmp($memberInfo['data'][0]->computer, 'PC') == 0) { ?> selected="selected" <?php } ?> value="PC">PC</option>
                                    <option <?php if (isset($memberInfo['data'][0]->computer) && strcmp($memberInfo['data'][0]->computer, 'Mac') == 0) { ?> selected="selected" <?php } ?> value="Mac">Mac
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Preffered Player</label>
                            <div class="col-sm-9">
                                <select disabled="disabled" name="player" size="1" id="player" class="form-control input selectpicker">
                                    <option value="Windows Media Player" <?php if (isset($memberInfo['data'][0]->player) && strcmp(urldecode($memberInfo['data'][0]->player), 'Windows Media Player') == 0) { ?> selected="selected" <?php } ?>>Windows
                                        Media Player
                                    </option>
                                    <option value="Real Player" <?php if (isset($memberInfo['data'][0]->player) && strcmp(urldecode($memberInfo['data'][0]->player), 'Real Player') == 0) { ?> selected="selected" <?php } ?>>Real
                                        Player
                                    </option>
                                    <option value="Quicktime" <?php if (isset($memberInfo['data'][0]->player) && strcmp(urldecode($memberInfo['data'][0]->player), 'Quicktime') == 0) { ?> selected="selected" <?php } ?>>Quicktime
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Member Points</label>
                            <div class="col-sm-9">
                                <?php echo $memberInfo['data'][0]->reviewPts; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">What is your field?</label>
                            <div class="col-sm-9">
                                <?php
                                if ($memberInfo['data'][0]->dj_mixer == 1) {
                                    $djDisplay = 'block';
                                } else {
                                    $djDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->radio_station == 1) {
                                    $radioDisplay = 'block';
                                } else {
                                    $radioDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->mass_media == 1) {
                                    $massDisplay = 'block';
                                } else {
                                    $massDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->record_label == 1) {
                                    $recordDisplay = 'block';
                                } else {
                                    $recordDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->management == 1) {
                                    $managementDisplay = 'block';
                                } else {
                                    $managementDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->clothing_apparel == 1) {
                                    $clothingDisplay = 'block';
                                } else {
                                    $clothingDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->promoter == 1) {
                                    $promoterDisplay = 'block';
                                } else {
                                    $promoterDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->special_services == 1) {
                                    $specialDisplay = 'block';
                                } else {
                                    $specialDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->production_talent == 1) {
                                    $productionDisplay = 'block';
                                } else {
                                    $productionDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_commercialreporting == 1) {
                                    $commercialDisplay = 'block';
                                } else {
                                    $commercialDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_commercialnonreporting == 1) {
                                    $nonCommercialDisplay = 'block';
                                } else {
                                    $nonCommercialDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_club == 1) {
                                    $clubDisplay = 'block';
                                } else {
                                    $clubDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_mixtape == 1) {
                                    $mixtapeDisplay = 'block';
                                } else {
                                    $mixtapeDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_satellite == 1) {
                                    $satelliteDisplay = 'block';
                                } else {
                                    $satelliteDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_internet == 1) {
                                    $internetDisplay = 'block';
                                } else {
                                    $internetDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_college == 1) {
                                    $collegeDisplay = 'block';
                                } else {
                                    $collegeDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djtype_pirate == 1) {
                                    $pirateDisplay = 'block';
                                } else {
                                    $pirateDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djwith_mp3 == 1) {
                                    $mp3Display = 'block';
                                } else {
                                    $mp3Display = 'none';
                                }
                                if ($memberInfo['data'][0]->djwith_cd == 1) {
                                    $cdDisplay = 'block';
                                } else {
                                    $cdDisplay = 'none';
                                }
                                if ($memberInfo['data'][0]->djwith_vinyl == 1) {
                                    $vinylDisplay = 'block';
                                } else {
                                    $vinylDisplay = 'none';
                                }
                                ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="dj_mixer" id="dj_mixer" value="1" onclick="getDiv('djDiv',this.id)" class="ace" <?php if ($memberInfo['data'][0]->dj_mixer == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl"> DJ / Mixer</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="radio_station" id="radio_station" value="1" onclick="getDiv('radioDiv',this.id)" class="ace" <?php if ($memberInfo['data'][0]->radio_station == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl"> Radio Station (Non - DJ/Mixer)</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="mass_media" id="mass_media" value="1" onclick="getDiv('massDiv',this.id)" class="ace" <?php if ($memberInfo['data'][0]->mass_media == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl"> Mass Media</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="record_label" id="record_label" value="1" onclick="getDiv('recordDiv',this.id)" class="ace" <?php if ($memberInfo['data'][0]->record_label == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl">Record Label</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="management" id="management" value="1" onclick="getDiv('managementDiv',this.id)" class="ace" <?php if ($memberInfo['data'][0]->management == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl">Management</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="clothing_apparel" id="clothing_apparel" onclick="getDiv('clothingDiv',this.id)" value="1" class="ace" <?php if ($memberInfo['data'][0]->clothing_apparel == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl">Clothing/Apparel</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="promoter" id="promoter" onclick="getDiv('promoterDiv',this.id)" value="1" class="ace" <?php if ($memberInfo['data'][0]->promoter == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl">Promoter</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="special_services" id="special_services" onclick="getDiv('specialDiv',this.id)" value="1" class="ace" <?php if ($memberInfo['data'][0]->special_services == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl">Special Services</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled="disabled" name="production_talent" id="production_talent" value="1" onclick="getDiv('productionDiv',this.id)" class="ace" <?php if ($memberInfo['data'][0]->production_talent == 1) { ?> checked="checked" <?php } ?> />
                                        <span class="lbl">Production/Talent</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">How did you hear about Digiwaxx?</label>
                            <div class="col-sm-9">
                                <select class="selectpicker input form-control" disabled="disabled">
                                    <option value="">How did you hear about Digiwaxx?</option>
                                    <option <?php if (strcmp('Internet Search', urldecode($memberInfo['data'][0]->howheard)) == 0) { ?> selected="selected" <?php } ?> value="Internet Search">Internet Search</option>
                                    <option <?php if (strcmp('Magazine Article', urldecode($memberInfo['data'][0]->howheard)) == 0) { ?> selected="selected" <?php } ?> value="Magazine Article">Magazine Article</option>
                                    <option <?php if (strcmp('Record Pool', urldecode($memberInfo['data'][0]->howheard)) == 0) { ?> selected="selected" <?php } ?> value="Record Pool">Record Pool</option>
                                    <option <?php if (strcmp('DJ Crew', urldecode($memberInfo['data'][0]->howheard)) == 0) { ?> selected="selected" <?php } ?> value="DJ Crew">DJ Crew</option>
                                    <option <?php if (strcmp('A Current Member', urldecode($memberInfo['data'][0]->howheard)) == 0) { ?> selected="selected" <?php } ?> value="A Current Member">A Current Member</option>
                                </select>
                            </div>
                        </div>
                        <?php
                        $howDisplay = 'none';
                        if (strcmp('A Current Member', urldecode($memberInfo['data'][0]->howheard)) == 0 || strcmp('DJ Crew', urldecode($memberInfo['data'][0]->howheard)) == 0 || strcmp('Record Pool', urldecode($memberInfo['data'][0]->howheard)) == 0) {
                            $howDisplay = '';
                        } ?>
                        <div class="form-group" id="howHeardDiv" style="display:<?php echo $howDisplay; ?>">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9">
                                <input class="form-control" disabled="disabled" placeholder="Name" size="25" maxlength="255" value="<?php echo ($memberInfo['data'][0]->howheardvalue); ?>">
                            </div>
                        </div>
                        <!-- what type of dj -->
                        <div id="djDiv" style="display:<?php echo $djDisplay; ?>">
                            <h3>DJ/Mixer Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">What Type of DJ?</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_commercialreporting" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_commercialreporting == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Commercial/Mixshow DJ (Reporting)</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_commercialnonreporting" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_commercialnonreporting == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Commercial/Mixshow DJ (Non-Reporting)</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_club" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_club == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Club DJ</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_mixtape" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_mixtape == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Mix Tape DJ</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_satellite" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_satellite == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Satellite Radio DJ</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_internet" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_internet == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Internet Radio DJ</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_college" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_college == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">College Radio</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djtype_pirate" value="1" class="ace" <?php if ($memberInfo['data'][0]->djtype_pirate == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Pirate Radio</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--what do you use to play music -->
                            <div class="form-group">
                                <label class="col-sm-3">What Do You Use to Play Music?</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djwith_mp3" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_mp3 == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> MP3</span>
                                        </label>
                                    </div>
                                    <div id="mp3Div" style="display:<?php echo $mp3Display; ?>;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="djwith_mp3_serato" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_mp3_serato == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Serato Scratch</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="djwith_mp3_final" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_mp3_final == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Final Scratch Pro</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="djwith_mp3_pcdj" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_mp3_pcdj == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">PCDJ</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="djwith_mp3_ipod" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_mp3_ipod == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> I-Pod</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="djwith_mp3_other" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_mp3_other == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Other</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djwith_cd" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">CD</span>
                                        </label>
                                    </div>
                                    <div id="cdDiv" style="display:<?php echo $cdDisplay; ?>;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_stanton" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_stanton == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Stanton Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_numark" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_numark == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Numark Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_american" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_american == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> American Audio Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_vestax" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_vestax == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Vestax Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_technics" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_technics == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Technics Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_gemini" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_gemini == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Gemini Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_denon" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_denon == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Denon Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_gemsound" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_gemsound == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Gem Sound Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_pioneer" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_pioneer == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Pioneer Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_tascam" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_tascam == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Tascam Player</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_cd_other" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_cd_other == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Other</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="djwith_vinyl" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_vinyl == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Vinyl</span>
                                        </label>
                                    </div>
                                    <div id="vinylDiv" style="display:<?php echo $vinylDisplay; ?>;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_vinyl_12" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_vinyl_12 == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> 12"</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_vinyl_45" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_vinyl_45 == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> 45"</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="djwith_vinyl_78" value="1" class="ace" <?php if ($memberInfo['data'][0]->djwith_vinyl_78 == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> 78"</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Mix show DJ-->
                            <div id="mixShowDjDiv" style="display:<?php echo $commercialDisplay; ?>;">
                                <h3 class="col-sm-12">Commercial/ Mixshow DJ (Reporting)</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Call Letters</label>
                                <div class="col-sm-7 form-group">
                                    <input name="commercialdj_call" class="form-control input" size="20" placeholder="Station Call Letters" type="text" value="<?php echo $memberInfo['data'][0]->commercialdj_call; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="commercialdj_name" class="form-control input" size="20" placeholder="Station Name" type="text" value="<?php echo $memberInfo['data'][0]->commercialdj_name; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Frequency</label>
                                <div class="col-sm-7 form-group">
                                    <input name="commercialdj_frequency" class="form-control input" size="20" placeholder="Station Frequency" type="text" value="<?php echo $memberInfo['data'][0]->commercialdj_frequency; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Name/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="commercialdj_showname" class="form-control input" size="20" placeholder="Show Name/s" type="text" value="<?php echo $memberInfo['data'][0]->commercialdj_showname; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Type</label>
                                <div class="col-sm-7 form-group">
                                    <select name="commercialdj_showtype" id="comdj_showtype" class="form-control input selectpicker" size="20" type="text">
                                        <option value="morning_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'morning_mix') == 0) { ?> selected="selected" <?php } ?>>Morning Mix</option>
                                        <option value="morning_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'morning_show') == 0) { ?> selected="selected" <?php } ?>>Morning Show</option>
                                        <option value="midday_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'midday_mix') == 0) { ?> selected="selected" <?php } ?>>Midday Mix</option>
                                        <option value="midday_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'midday_show') == 0) { ?> selected="selected" <?php } ?>>Midday Show</option>
                                        <option value="lunch_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'lunch_mix') == 0) { ?> selected="selected" <?php } ?>>Lunch Mix</option>
                                        <option value="afternoon_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'afternoon_mix') == 0) { ?> selected="selected" <?php } ?>>Afternoon Mix</option>
                                        <option value="afternoon_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'afternoon_show') == 0) { ?> selected="selected" <?php } ?>>Afternoon Show</option>
                                        <option value="evening_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'evening_mix') == 0) { ?> selected="selected" <?php } ?>>Evening Mix</option>
                                        <option value="evening_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'evening_show') == 0) { ?> selected="selected" <?php } ?>>Evening Show</option>
                                        <option value="late_night_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'late_night_mix') == 0) { ?> selected="selected" <?php } ?>>Late Night Mix</option>
                                        <option value="late_night_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'late_night_show') == 0) { ?> selected="selected" <?php } ?>>Late Night Show</option>
                                        <option value="overnight_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'overnight_mix') == 0) { ?> selected="selected" <?php } ?>>Overnight Mix</option>
                                        <option value="overnight_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'overnight_show') == 0) { ?> selected="selected" <?php } ?>>Overnight Show</option>
                                        <option value="weekend_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'weekend_mix') == 0) { ?> selected="selected" <?php } ?>>Weekend Mix</option>
                                        <option value="weekend_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'weekend_show') == 0) { ?> selected="selected" <?php } ?>>Weekend Show</option>
                                        <option value="quick_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'quick_mix') == 0) { ?> selected="selected" <?php } ?>>Quick Mix</option>
                                        <option value="traffic_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'traffic_mix') == 0) { ?> selected="selected" <?php } ?>>Traffic Mix</option>
                                        <option value="specialty_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'specialty_show') == 0) { ?> selected="selected" <?php } ?>>Specialty Show</option>
                                        <option value="talk_show" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'talk_show') == 0) { ?> selected="selected" <?php } ?>>Talk Show</option>
                                        <option value="live_broadcast" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'live_broadcast') == 0) { ?> selected="selected" <?php } ?>>Live Broadcast</option>
                                        <option value="fill_in_mix" <?php if (isset($memberInfo['data'][0]->commercialdj_showtype) && strcmp($memberInfo['data'][0]->commercialdj_showtype, 'fill_in_mix') == 0) { ?> selected="selected" <?php } ?>>Fill In Mix</option>
                                    </select>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Days</label>
                                <div class="col-sm-7 form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_monday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Monday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Tuesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Wednesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Thursday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_friday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Friday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Saturday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sunday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="commercialdj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->commercialdj_varies == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Varies</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Time/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="commercialdj_showtime" class="form-control input" size="20" placeholder="Show Time/s" type="text" value="<?php echo $memberInfo['data'][0]->commercialdj_showtime; ?>">
                                </div>
                            </div>
                            <!--Mix show dj ends-->
                            <!--Mix show DJ non-->
                            <div id="mixShowDjNonDiv" style="display:<?php echo $nonCommercialDisplay; ?>;">
                                <h3 class="col-sm-12">Commercial/ Mixshow DJ (Non-Reporting)</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Call Letters</label>
                                <div class="col-sm-7 form-group">
                                    <input name="noncommercialdj_call" class="form-control input" size="20" placeholder="Station Call Letters" type="text" value="<?php echo $memberInfo['data'][0]->noncommercialdj_call; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="noncommercialdj_name" class="form-control input" size="20" placeholder="Station Name" type="text" value="<?php echo $memberInfo['data'][0]->noncommercialdj_name; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Frequency</label>
                                <div class="col-sm-7 form-group">
                                    <input name="noncommercialdj_frequency" class="form-control input" size="20" placeholder="Station Frequency" type="text" value="<?php echo $memberInfo['data'][0]->noncommercialdj_frequency; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Name/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="noncommercialdj_showname" class="form-control input" size="20" placeholder="Show Name/s" type="text" value="<?php echo $memberInfo['data'][0]->noncommercialdj_showname; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Type</label>
                                <div class="col-sm-7 form-group">
                                    <select name="noncommercialdj_showtype" class="form-control input selectpicker" size="20" type="text">
                                        <option value="morning_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'morning_mix') == 0) { ?> selected="selected" <?php } ?>>Morning Mix</option>
                                        <option value="morning_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'morning_show') == 0) { ?> selected="selected" <?php } ?>>Morning Show</option>
                                        <option value="midday_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'midday_mix') == 0) { ?> selected="selected" <?php } ?>>Midday Mix</option>
                                        <option value="midday_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'midday_show') == 0) { ?> selected="selected" <?php } ?>>Midday Show</option>
                                        <option value="lunch_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'lunch_mix') == 0) { ?> selected="selected" <?php } ?>>Lunch Mix</option>
                                        <option value="afternoon_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'afternoon_mix') == 0) { ?> selected="selected" <?php } ?>>Afternoon Mix</option>
                                        <option value="afternoon_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'afternoon_show') == 0) { ?> selected="selected" <?php } ?>>Afternoon Show</option>
                                        <option value="evening_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'evening_mix') == 0) { ?> selected="selected" <?php } ?>>Evening Mix</option>
                                        <option value="evening_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'evening_show') == 0) { ?> selected="selected" <?php } ?>>Evening Show</option>
                                        <option value="late_night_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'late_night_mix') == 0) { ?> selected="selected" <?php } ?>>Late Night Mix</option>
                                        <option value="late_night_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'late_night_show') == 0) { ?> selected="selected" <?php } ?>>Late Night Show</option>
                                        <option value="overnight_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'overnight_mix') == 0) { ?> selected="selected" <?php } ?>>Overnight Mix</option>
                                        <option value="overnight_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'overnight_show') == 0) { ?> selected="selected" <?php } ?>>Overnight Show</option>
                                        <option value="weekend_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'weekend_mix') == 0) { ?> selected="selected" <?php } ?>>Weekend Mix</option>
                                        <option value="weekend_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'weekend_show') == 0) { ?> selected="selected" <?php } ?>>Weekend Show</option>
                                        <option value="quick_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'quick_mix') == 0) { ?> selected="selected" <?php } ?>>Quick Mix</option>
                                        <option value="traffic_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'traffic_mix') == 0) { ?> selected="selected" <?php } ?>>Traffic Mix</option>
                                        <option value="specialty_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'specialty_show') == 0) { ?> selected="selected" <?php } ?>>Specialty Show</option>
                                        <option value="talk_show" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'talk_show') == 0) { ?> selected="selected" <?php } ?>>Talk Show</option>
                                        <option value="live_broadcast" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'live_broadcast') == 0) { ?> selected="selected" <?php } ?>>Live Broadcast</option>
                                        <option value="fill_in_mix" <?php if (isset($memberInfo['data'][0]->noncommercialdj_showtype) && strcmp($memberInfo['data'][0]->noncommercialdj_showtype, 'fill_in_mix') == 0) { ?> selected="selected" <?php } ?>>Fill In Mix</option>
                                    </select>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Days</label>
                                <div class="col-sm-7 form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_monday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Monday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Tuesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Wednesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Thursday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_friday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Friday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Saturday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sunday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="noncommercialdj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->noncommercialdj_varies == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Varies</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Time/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="noncommercialdj_showtime" class="form-control input" size="20" placeholder="Show Time/s" type="text" value="<?php echo $memberInfo['data'][0]->noncommercialdj_showtime; ?>">
                                </div>
                            </div>
                            <!--Mix show DJ non ends-->
                            <!--Club DJ-->
                            <div id="clubDjDiv" style="display:<?php echo $clubDisplay; ?>;">
                                <label class="col-sm-3">Club DJ</label>
                                <div class="col-sm-9">
                                    <label class="col-sm-3">Club Name</label>
                                    <div class="col-sm-9">
                                        <input disabled="disabled" name="clubdj_clubname" class="form-control input" size="20" placeholder="Club Name" type="text" value="<?php echo $memberInfo['data'][0]->clubdj_clubname; ?>">
                                    </div>
                                    <div style="clear:both;"></div>
                                    <label class="col-sm-3">Estimated Venue Capacity</label>
                                    <div class="col-sm-9">
                                        <input disabled="disabled" name="clubdj_capacity" class="form-control input" size="20" placeholder="Estimated Venue Capacity" type="text" value="<?php echo $memberInfo['data'][0]->clubdj_capacity; ?>">
                                    </div>
                                    <div style="clear:both;"></div>
                                    <label class="col-sm-3">Party Format</label>
                                    <div class="col-sm-9">
                                        <div class="checkbox">
                                            <label>
                                                <input disabled="disabled" type="checkbox" name="clubdj_hiphop" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_hiphop == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Hip Hop</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_rb" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_rb == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">R&B</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_pop" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_pop == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Pop/Top 40</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_reggae" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_reggae == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Reggae</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_house" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_house == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">House</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_calypso" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_calypso == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Calypso/Soca</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_rock" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_rock == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Rock</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_techno" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_techno == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl"> Techno</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_trance" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_trance == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Trance</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_afro" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_afro == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Afro-Beat</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_reggaeton" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_reggaeton == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Reggaeton</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_gogo" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_gogo == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Go-Go</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_neosoul" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_neosoul == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Neo-Soul</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_oldschool" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_oldschool == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Old School</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_electronic" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_electronic == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Electronic</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_latin" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_latin == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Latin Soul</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_dance" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_dance == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Electronica/Dance</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_jazz" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_jazz == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Jazz</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_country" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_country == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Country</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_world" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_world == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">World</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <label class="col-sm-3">Show Day/s</label>
                                    <div class="col-sm-9">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_monday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Monday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Tuesday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Wednesday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Thursday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_friday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Friday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Saturday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Sunday</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled="disabled" name="clubdj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->clubdj_varies == 1) { ?> checked="checked" <?php } ?> />
                                                <span class="lbl">Varies</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <label class="col-sm-3">City</label>
                                    <div class="col-sm-9">
                                        <input disabled="disabled" name="clubdj_city" class="form-control input" size="20" placeholder="City" type="text" <?php if (isset($memberInfo['data'][0]->clubdj_city)) { ?> value="<?php echo $memberInfo['data'][0]->clubdj_city; ?>" <?php } ?>>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <label class="col-sm-3">State</label>
                                    <div class="col-sm-9">
                                        <input disabled="disabled" name="clubdj_state" class="form-control input" size="20" placeholder="State" type="text" <?php if (isset($memberInfo['data'][0]->clubdj_state)) { ?> value="<?php echo $memberInfo['data'][0]->clubdj_state; ?>" <?php } ?>>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <label class="col-sm-3">Country</label>
                                    <div class="col-sm-9">
                                        <input disabled="disabled" name="clubdj_intcountry" class="form-control input" size="20" placeholder="Country Name" type="text" <?php if (isset($memberInfo['data'][0]->clubdj_intcountry)) { ?> value="<?php echo $memberInfo['data'][0]->clubdj_intcountry; ?>" <?php } ?>>
                                    </div>
                                </div>
                            </div>
                            <!--club dj ends-->
                            <!--Mix Tape DJ-->
                            <div id="mixTapeDjDiv" style="display:<?php echo $mixtapeDisplay; ?>">
                                <h3 class="col-sm-12">Mix Tape DJ</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Mixtape Series Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="mixtapedj_name" class="form-control input" size="20" placeholder="Mixtape Series Name" type="text" value="<?php echo $memberInfo['data'][0]->mixtapedj_name; ?>">
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Mixtape Type</label>
                                <div class="col-sm-7 form-group">
                                    <select name="mixtapedj_type" id="mixtapedj_type" class="form-control input selectpicker" size="20" type="text">
                                        <option value="hip_hop" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'hip_hop') == 0) { ?> selected="selected" <?php } ?>>Hip Hop</option>
                                        <option value="r_and_b" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'r_and_b') == 0) { ?> selected="selected" <?php } ?>>R&amp;B</option>
                                        <option value="old_school" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'old_school') == 0) { ?> selected="selected" <?php } ?>>Old School</option>
                                        <option value="neo_soul" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'neo_soul') == 0) { ?> selected="selected" <?php } ?>>Neo Soul</option>
                                        <option value="corporate_or_specialty" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'corporate_or_specialty') == 0) { ?> selected="selected" <?php } ?>>Corporate/Specialty</option>
                                        <option value="mainstream" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'mainstream') == 0) { ?> selected="selected" <?php } ?>>Mainstream</option>
                                        <option value="urban" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'urban') == 0) { ?> selected="selected" <?php } ?>>Urban</option>
                                        <option value="local" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'local') == 0) { ?> selected="selected" <?php } ?>>Local</option>
                                        <option value="upscale_or_trendy" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'upscale_or_trendy') == 0) { ?> selected="selected" <?php } ?>>Upscale/Trendy</option>
                                        <option value="eclectic" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'eclectic') == 0) { ?> selected="selected" <?php } ?>>Eclectic</option>
                                        <option value="lounge" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'lounge') == 0) { ?> selected="selected" <?php } ?>>Lounge</option>
                                        <option value="industry" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'industry') == 0) { ?> selected="selected" <?php } ?>>Industry</option>
                                        <option value="afterwork" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'afterwork') == 0) { ?> selected="selected" <?php } ?>>Afterwork</option>
                                        <option value="spoken_word" <?php if (isset($memberInfo['data'][0]->mixtapedj_type) && strcmp($memberInfo['data'][0]->mixtapedj_type, 'spoken_word') == 0) { ?> selected="selected" <?php } ?>>Spoken Word</option>
                                    </select>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Release Schedule</label>
                                <div class="col-sm-7 form-group">
                                    <select name="mixtapedj_schedule" id="mixtapedj_schedule" class="form-control input selectpicker" size="20" type="text">
                                        <option value="weekly" <?php if (isset($memberInfo['data'][0]->mixtapedj_schedule) && strcmp($memberInfo['data'][0]->mixtapedj_schedule, 'weekly') == 0) { ?> selected="selected" <?php } ?>>Weekly </option>
                                        <option value="bi-weekly" <?php if (isset($memberInfo['data'][0]->mixtapedj_schedule) && strcmp($memberInfo['data'][0]->mixtapedj_schedule, 'bi-weekly') == 0) { ?> selected="selected" <?php } ?>>Bi-Weekly</option>
                                        <option value="monthly" <?php if (isset($memberInfo['data'][0]->mixtapedj_schedule) && strcmp($memberInfo['data'][0]->mixtapedj_schedule, 'monthly') == 0) { ?> selected="selected" <?php } ?>>Monthly</option>
                                        <option value="bi-monthly" <?php if (isset($memberInfo['data'][0]->mixtapedj_schedule) && strcmp($memberInfo['data'][0]->mixtapedj_schedule, 'bi-monthly') == 0) { ?> selected="selected" <?php } ?>>Bi-Monthly</option>
                                        <option value="quartely" <?php if (isset($memberInfo['data'][0]->mixtapedj_schedule) && strcmp($memberInfo['data'][0]->mixtapedj_schedule, 'quartely') == 0) { ?> selected="selected" <?php } ?>>Quartely</option>
                                        <option value="bi-annual" <?php if (isset($memberInfo['data'][0]->mixtapedj_schedule) && strcmp($memberInfo['data'][0]->mixtapedj_schedule, 'bi-annual') == 0) { ?> selected="selected" <?php } ?>>Bi-Annual</option>
                                    </select>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Unit Distribution</label>
                                <div class="col-sm-7 form-group">
                                    <select name="mixtapedj_distribution" id="mixtapedj_distribution" class="form-control input selectpicker" size="20" type="text">
                                        <option value="(10_-_100)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(10_-_100)') == 0) { ?> selected="selected" <?php } ?>>(10 – 100)</option>
                                        <option value="(100_-_500)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(100_-_500)') == 0) { ?> selected="selected" <?php } ?>>(100 – 500)</option>
                                        <option value="(500_-_1000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(500_-_1000)') == 0) { ?> selected="selected" <?php } ?>>(500 – 1000)</option>
                                        <option value="(1000_-_5000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(1000_-_5000)') == 0) { ?> selected="selected" <?php } ?>>(1000 – 5000)</option>
                                        <option value="(5000_-_10000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(5000_-_10000)') == 0) { ?> selected="selected" <?php } ?>>(5000 – 10000)</option>
                                        <option value="(10,000_-_30,000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(10,000_-_30,000)') == 0) { ?> selected="selected" <?php } ?>>(10,000 – 30, 000)</option>
                                        <option value="(30,000_-_50,000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(30,000_-_50,000)') == 0) { ?> selected="selected" <?php } ?>>(30, 000 – 50,000)</option>
                                        <option value="(50,000_-_100,000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(50,000_-_100,000)') == 0) { ?> selected="selected" <?php } ?>>(50,000 – 100,000)</option>
                                        <option value="(100,000)" <?php if (isset($memberInfo['data'][0]->mixtapedj_distribution) && strcmp($memberInfo['data'][0]->mixtapedj_distribution, '(100,000)') == 0) { ?> selected="selected" <?php } ?>>(100,000+)</option>
                                    </select>
                                </div>
                            </div>
                            <!--Mix tape dj ends-->
                            <!--Satellite Radio DJ-->
                            <div id="satelliteRadioDjDiv" style="display:<?php echo $satelliteDisplay; ?>;">
                                <h3 class="col-sm-12">Satellite Radio DJ</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Network Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="satellitedj_stationname" class="form-control input" size="20" placeholder="Network Name" type="text" <?php if (isset($memberInfo['data'][0]->satellitedj_stationname)) { ?> value="<?php echo $memberInfo['data'][0]->satellitedj_stationname; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Channel Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="satellitedj_channelname" class="form-control input" size="20" placeholder="Show Channel Name" type="text" <?php if (isset($memberInfo['data'][0]->satellitedj_channelname)) { ?> value="<?php echo $memberInfo['data'][0]->satellitedj_channelname; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Channel Number</label>
                                <div class="col-sm-7 form-group">
                                    <input name="satellitedj_channelnumber" class="form-control input" size="20" placeholder="Show Channel Number" type="text" <?php if (isset($memberInfo['data'][0]->satellitedj_channelnumber)) { ?> value="<?php echo $memberInfo['data'][0]->satellitedj_channelnumber; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Name/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="satellitedj_showname" class="form-control input" size="20" placeholder="Show Name" type="text" <?php if (isset($memberInfo['data'][0]->satellitedj_showname)) { ?> value="<?php echo $memberInfo['data'][0]->satellitedj_showname; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Day/s</label>
                                <div class="col-sm-7" style="margin-bottom:10px;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_monday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Monday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Tuesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Wednesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Thursday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_friday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Friday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Saturday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sunday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="satellitedj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->satellitedj_varies == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Varies</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Time/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="satellitedj_showtime" class="form-control input" size="20" placeholder="Show Time" type="text" <?php if (isset($memberInfo['data'][0]->satellitedj_showtime)) { ?> value="<?php echo $memberInfo['data'][0]->satellitedj_showtime; ?>" <?php } ?>>
                                </div>
                            </div>
                            <!--Satellite Radio dj ends-->
                            <!--Internet Radio DJ-->
                            <div id="internetRadioDjDiv" style="display:<?php echo $internetDisplay; ?>;">
                                <h3 class="col-sm-12">Internet Radio DJ</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Website</label>
                                <div class="col-sm-7 form-group">
                                    <input name="internetdj_stationwebsite" class="form-control input" size="20" placeholder="Station Website" type="text" <?php if (isset($memberInfo['data'][0]->internetdj_stationwebsite)) { ?> value="<?php echo $memberInfo['data'][0]->internetdj_stationwebsite; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Type</label>
                                <div class="col-sm-7 form-group">
                                    <input name="internetdj_showtype" class="form-control input" size="20" placeholder="Show Type" type="text" <?php if (isset($memberInfo['data'][0]->internetdj_showtype)) { ?> value="<?php echo $memberInfo['data'][0]->internetdj_showtype; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="internetdj_showname" class="form-control input" size="20" placeholder="Show Name" type="text" <?php if (isset($memberInfo['data'][0]->internetdj_showname)) { ?> value="<?php echo $memberInfo['data'][0]->internetdj_showname; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Day/s</label>
                                <div class="col-sm-7" style="margin-bottom:10px;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_monday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Monday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Tuesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Wednesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Thursday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_friday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Friday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Saturday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sunday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="internetdj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->internetdj_varies == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Varies</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Time/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="internetdj_showtime" class="form-control input" size="20" placeholder="Show Time" type="text" <?php if (isset($memberInfo['data'][0]->internetdj_showtime)) { ?> value="<?php echo $memberInfo['data'][0]->internetdj_showtime; ?>" <?php } ?>>
                                </div>
                            </div>
                            <!--Internet Radio dj ends-->
                            <!--College Radio-->
                            <div id="collegeRadioDiv" style="display:<?php echo $collegeDisplay; ?>;">
                                <h3 class="col-sm-12">College Radio</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Call Letters</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_callletters" class="form-control input" size="20" placeholder="Call Letters" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_callletters)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_callletters; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">College Name</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_collegename" class="form-control input" size="20" placeholder="College Name" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_collegename)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_collegename; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Frequency</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_stationfrequency" class="form-control input" size="20" placeholder="Station Frequency" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_stationfrequency)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_stationfrequency; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Type</label>
                                <div class="col-sm-7 form-group">
                                    <select name="collegedj_showtype" class="form-control input selectpicker" size="20">
                                        <option value="morning_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'morning_mix') == 0) { ?> selected="selected" <?php } ?>>Morning Mix</option>
                                        <option value="morning_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'morning_show') == 0) { ?> selected="selected" <?php } ?>>Morning Show</option>
                                        <option value="midday_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'midday_mix') == 0) { ?> selected="selected" <?php } ?>>Midday Mix</option>
                                        <option value="midday_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'midday_show') == 0) { ?> selected="selected" <?php } ?>>Midday Show</option>
                                        <option value="lunch_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'lunch_mix') == 0) { ?> selected="selected" <?php } ?>>Lunch Mix</option>
                                        <option value="afternoon_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'afternoon_mix') == 0) { ?> selected="selected" <?php } ?>>Afternoon Mix</option>
                                        <option value="afternoon_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'afternoon_show') == 0) { ?> selected="selected" <?php } ?>>Afternoon Show</option>
                                        <option value="evening_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'evening_mix') == 0) { ?> selected="selected" <?php } ?>>Evening Mix</option>
                                        <option value="evening_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'evening_show') == 0) { ?> selected="selected" <?php } ?>>Evening Show</option>
                                        <option value="late_night_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'late_night_mix') == 0) { ?> selected="selected" <?php } ?>>Late Night Mix</option>
                                        <option value="late_night_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'late_night_show') == 0) { ?> selected="selected" <?php } ?>>Late Night Show</option>
                                        <option value="overnight_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'overnight_mix') == 0) { ?> selected="selected" <?php } ?>>Overnight Mix</option>
                                        <option value="overnight_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'overnight_show') == 0) { ?> selected="selected" <?php } ?>>Overnight Show</option>
                                        <option value="weekend_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'weekend_mix') == 0) { ?> selected="selected" <?php } ?>>Weekend Mix</option>
                                        <option value="weekend_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'weekend_show') == 0) { ?> selected="selected" <?php } ?>>Weekend Show</option>
                                        <option value="quick_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'quick_mix') == 0) { ?> selected="selected" <?php } ?>>Quick Mix</option>
                                        <option value="traffic_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'traffic_mix') == 0) { ?> selected="selected" <?php } ?>>Traffic Mix</option>
                                        <option value="specialty_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'specialty_show') == 0) { ?> selected="selected" <?php } ?>>Specialty Show</option>
                                        <option value="talk_show" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'talk_show') == 0) { ?> selected="selected" <?php } ?>>Talk Show</option>
                                        <option value="live_broadcast" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'live_broadcast') == 0) { ?> selected="selected" <?php } ?>>Live Broadcast</option>
                                        <option value="fill_in_mix" <?php if (isset($memberInfo['data'][0]->collegedj_showtype) && strcmp($memberInfo['data'][0]->collegedj_showtype, 'fill_in_mix') == 0) { ?> selected="selected" <?php } ?>>Fill In Mix</option>
                                    </select>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Name/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_showname" class="form-control input" size="20" placeholder="Show Name" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_showname)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_showname; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Day/s</label>
                                <div class="col-sm-7" style="margin-bottom:10px;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_monday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Monday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Tuesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Wednesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Thursday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_friday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Friday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Saturday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sunday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="collegedj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->collegedj_varies == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Varies</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Times</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_showtime" class="form-control input" size="20" placeholder="Show Times" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_showtime)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_showtime; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">City/Province/Town</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_city" class="form-control input" size="20" placeholder="City/Province/Town" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_city)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_city; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">State</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_state" class="form-control input" size="20" placeholder="State" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_state)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_state; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Country</label>
                                <div class="col-sm-7 form-group">
                                    <input name="collegedj_intcountry" class="form-control input" size="20" placeholder="Country" type="text" <?php if (isset($memberInfo['data'][0]->collegedj_intcountry)) { ?> value="<?php echo $memberInfo['data'][0]->collegedj_intcountry; ?>" <?php } ?>>
                                </div>
                            </div>
                            <!--College Radio ends-->
                            <!--Pirate Radio-->
                            <div id="pirateRadioDiv" style="display:<?php echo $pirateDisplay; ?>;">
                                <h3 class="col-sm-12">Pirate Radio</h3>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Station Frequency</label>
                                <div class="col-sm-7 form-group">
                                    <input name="piratedj_stationfrequency" class="form-control input" size="20" placeholder="Station Frequency" type="text" <?php if (isset($memberInfo['data'][0]->piratedj_stationfrequency)) { ?> value="<?php echo $memberInfo['data'][0]->piratedj_stationfrequency; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Name/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="piratedj_showname" class="form-control input" size="20" placeholder="Show Name" type="text" <?php if (isset($memberInfo['data'][0]->piratedj_showname)) { ?> value="<?php echo $memberInfo['data'][0]->piratedj_showname; ?>" <?php } ?>>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Day/s</label>
                                <div class="col-sm-7" style="margin-bottom:10px;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_monday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_monday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Monday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_tuesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Tuesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_wednesday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Wednesday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_thursday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_thursday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Thursday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_friday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_friday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Friday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_saturday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_saturday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Saturday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_sunday" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_sunday == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sunday</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="piratedj_varies" value="1" class="ace" <?php if ($memberInfo['data'][0]->piratedj_varies == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Varies</span>
                                        </label>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <label class="col-sm-5">Show Time/s</label>
                                <div class="col-sm-7 form-group">
                                    <input name="piratedj_showtime" class="form-control input" size="20" placeholder="Show Time" type="text" <?php if (isset($memberInfo['data'][0]->piratedj_showtime)) { ?> value="<?php echo $memberInfo['data'][0]->piratedj_showtime; ?>" <?php } ?>>
                                </div>
                            </div>
                            <!--Pirate Radio ends-->
                        </div>
                        <!--dj mixer ends-->
                        <div id="radioDiv" style="display:<?php echo $radioDisplay; ?>">
                            <h3>Radio Station Information (Non-DJ/Mixer)</h3>
                            <div class="form-group">
                                <label class="col-sm-3">What is your field?</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioMusic" id="radioMusic" value="1" onclick="getRadioInner('musicInnerDiv',this.id)" class="ace" <?php if (isset($radio['data'][0]->radiotype_musicdirector) && $radio['data'][0]->radiotype_musicdirector == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Music Director</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioProgram" id="radioProgram" value="1" onclick="getRadioInner('programInnerDiv',this.id)" class="ace" <?php if (isset($radio['data'][0]->radiotype_programdirector) && $radio['data'][0]->radiotype_programdirector == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Program Director</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioAir" id="radioAir" value="1" onclick="getRadioInner('airInnerDiv',this.id)" class="ace" <?php if (isset($radio['data'][0]->radiotype_jock) && $radio['data'][0]->radiotype_jock == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">On-Air Personality/Jock</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioPromotion" id="radioPromotion" value="1" class="ace" <?php if (isset($radio['data'][0]->radiotype_promotion) && $radio['data'][0]->radiotype_promotion == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Promotion</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioProduction" id="radioProduction" value="1" class="ace" <?php if (isset($radio['data'][0]->radiotype_production) && $radio['data'][0]->radiotype_production == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Production</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioSales" id="radioSales" value="1" class="ace" <?php if (isset($radio['data'][0]->radiotype_sales) && $radio['data'][0]->radiotype_sales == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sales</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="radioIt" id="radioIt" value="1" class="ace" <?php if (isset($radio['data'][0]->radiotype_tech) && $radio['data'][0]->radiotype_tech == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">I.T./Tech</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Station Call Letters</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="stationCall" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->stationcallletters)) { ?> value="<?php echo $radio['data'][0]->stationcallletters; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Station Name</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="stationName" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->stationname)) { ?> value="<?php echo $radio['data'][0]->stationname; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Station Frequency</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="stationFrequency" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->stationfrequency)) { ?> value="<?php echo $radio['data'][0]->stationfrequency; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div id="musicInnerDiv" style="display:none;">
                                <label class="col-sm-3">Music Director</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label>Music Call Times</label>
                                        <div>
                                            <input disabled="disabled" name="musicCall" id="musicCall" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->musicdirector_stationcallletters)) { ?> value="<?php echo $radio['data'][0]->musicdirector_stationcallletters; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Do You Host a Show?</label>
                                        <div>
                                            <input disabled="disabled" name="musicHost" id="musicHost" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->musicdirector_host)) { ?> value="<?php echo $radio['data'][0]->musicdirector_host; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Name</label>
                                        <div>
                                            <input disabled="disabled" name="musicName" id="musicName" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->musicdirector_showname)) { ?> value="<?php echo $radio['data'][0]->musicdirector_showname; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Days</label>
                                        <div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicMonday" id="musicMonday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_monday) && $radio['data'][0]->musicdirector_monday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Monday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicTuesday" id="musicTuesday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_tuesday) && $radio['data'][0]->musicdirector_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Tuesday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicWednesday" id="musicWednesday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_wednesday) && $radio['data'][0]->musicdirector_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Wednesday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicThursday" id="musicThursday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_thursday) && $radio['data'][0]->musicdirector_thursday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Thursday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicFriday" id="musicFriday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_friday) && $radio['data'][0]->musicdirector_friday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Friday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicSaturday" id="musicSaturday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_saturday) && $radio['data'][0]->musicdirector_saturday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Saturday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicSunday" id="musicSunday" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_sunday) && $radio['data'][0]->musicdirector_sunday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Sunday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="musicVaries" id="musicVaries" value="1" class="ace" <?php if (isset($radio['data'][0]->musicdirector_varies) && $radio['data'][0]->musicdirector_varies == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Varies</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Time/s</label>
                                        <div>
                                            <input disabled="disabled" name="musicTime" id="musicTime" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->musicdirector_showtime)) { ?> value="<?php echo $radio['data'][0]->musicdirector_showtime; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--music inner div ends-->
                            <div id="programInnerDiv" style="display:none;">
                                <label class="col-sm-3">Program Director</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label>Music Call Times</label>
                                        <div>
                                            <input disabled="disabled" name="programCall" id="programCall" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->programdirector_stationcallletters)) { ?> value="<?php echo $radio['data'][0]->programdirector_stationcallletters; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Do You Host a Show?</label>
                                        <div>
                                            <input disabled="disabled" name="programHost" id="programHost" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->programdirector_host)) { ?> value="<?php echo $radio['data'][0]->programdirector_host; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Name</label>
                                        <div>
                                            <input disabled="disabled" name="programName" id="programName" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->programdirector_showname)) { ?> value="<?php echo $radio['data'][0]->programdirector_showname; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Days</label>
                                        <div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programMonday" id="programMonday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_monday) && $radio['data'][0]->programdirector_monday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Monday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programTuesday" id="programTuesday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_tuesday) && $radio['data'][0]->programdirector_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Tuesday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programWednesday" id="programWednesday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_wednesday) && $radio['data'][0]->programdirector_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Wednesday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programThursday" id="programThursday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_thursday) && $radio['data'][0]->programdirector_thursday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Thursday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programFriday" id="programFriday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_friday) && $radio['data'][0]->programdirector_friday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Friday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programSaturday" id="programSaturday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_saturday) && $radio['data'][0]->programdirector_saturday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Saturday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programSunday" id="programSunday" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_sunday) && $radio['data'][0]->programdirector_sunday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Sunday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" disabled="disabled" name="programVaries" id="programVaries" value="1" class="ace" <?php if (isset($radio['data'][0]->programdirector_varies) && $radio['data'][0]->programdirector_varies == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Varies</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Time/s</label>
                                        <div>
                                            <input disabled="disabled" name="programTime" id="programTime" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->programdirector_showtime)) { ?> value="<?php echo $radio['data'][0]->programdirector_showtime; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--program div ends-->
                            <div id="airInnerDiv" style="display:none;">
                                <label class="col-sm-3">On-Air Personality/Jock</label>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label>Show Name</label>
                                        <div>
                                            <input disabled="disabled" name="airName" id="airName" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->onairpersonality_showname)) { ?> value="<?php echo $radio['data'][0]->onairpersonality_showname; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Days</label>
                                        <div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airMonday" disabled="disabled" id="airMonday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_monday) && $radio['data'][0]->onairpersonality_monday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Monday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airTuesday" disabled="disabled" id="airTuesday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_tuesday) && $radio['data'][0]->onairpersonality_tuesday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Tuesday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airWednesday" disabled="disabled" id="airWednesday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_wednesday) && $radio['data'][0]->onairpersonality_wednesday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Wednesday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airThursday" disabled="disabled" id="airThursday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_thursday) && $radio['data'][0]->onairpersonality_thursday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Thursday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airFriday" disabled="disabled" id="airFriday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_friday) && $radio['data'][0]->onairpersonality_friday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Friday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airSaturday" disabled="disabled" id="airSaturday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_saturday) && $radio['data'][0]->onairpersonality_saturday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Saturday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airSunday" disabled="disabled" id="airSunday" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_sunday) && $radio['data'][0]->onairpersonality_sunday == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Sunday</span>
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="airVaries" disabled="disabled" id="airVaries" value="1" class="ace" <?php if (isset($radio['data'][0]->onairpersonality_varies) && $radio['data'][0]->onairpersonality_varies == 1) { ?> checked="checked" <?php } ?> />
                                                    <span class="lbl">Varies</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Show Time/s</label>
                                        <div>
                                            <input name="airTime" disabled="disabled" id="airTime" class="form-control input" size="20" placeholder="First Name" type="text" <?php if (isset($radio['data'][0]->onairpersonality_showtime)) { ?> value="<?php echo $radio['data'][0]->onairpersonality_showtime; ?>" <?php } ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--air div ends-->
                        </div>
                        <!--radio div ends-->
                        <div id="massDiv" style="display:<?php echo $massDisplay; ?>">
                            <h3>Mass Media Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Company Name</label>
                                <div class="col-sm-9">
                                    <input name="massName" disabled="disabled" id="massName" class="form-control input" size="20" type="text" <?php if (isset($media['data'][0]->media_name)) { ?> value="<?php echo $media['data'][0]->media_name; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Type</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="massTv" id="massTv" disabled="disabled" value="1" class="ace" <?php if (isset($media['data'][0]->mediatype_tvfilm) && $media['data'][0]->mediatype_tvfilm == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> TV/Film</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="massPublication" id="massPublication" value="1" class="ace" <?php if (isset($media['data'][0]->mediatype_publication) && $media['data'][0]->mediatype_publication == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Publication</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="massDotcom" disabled="disabled" id="massDotcom" value="1" class="ace" <?php if (isset($media['data'][0]->mediatype_newmedia) && $media['data'][0]->mediatype_newmedia == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">New Media/Dotcom</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="massNewsletter" disabled="disabled" id="massNewsletter" value="1" class="ace" <?php if (isset($media['data'][0]->mediatype_newsletter) && $media['data'][0]->mediatype_newsletter == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Newsletter</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Department</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="massDepartment" id="massDepartment" class="form-control input" size="20" type="text" <?php if (isset($media['data'][0]->media_department)) { ?> value="<?php echo $media['data'][0]->media_department; ?>" <?php } ?>>
                                </div>
                            </div>
                            <?php /*if($media['numRows']>0) 
                                    {
                                    
                                    $media_website = $media['data'][0]->media_website;
                                    }
                                    else
                                    {
                                    $media_website = '';
                                    
                                    }*/
                            ?>
                            <div class="form-group">
                                <label class="col-sm-3">Company website</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="massWebsite" id="massWebsite" class="form-control input" size="20" type="text" <?php if (isset($media['data'][0]->media_website)) { ?> value="<?php echo $media['data'][0]->media_website; ?>" <?php  } ?>>
                                </div>
                            </div>
                        </div>
                        <!--mass div ends-->
                        <div id="recordDiv" style="display:<?php echo $recordDisplay; ?>">
                            <h3>Record Label Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Label Name</label>
                                <div class="col-sm-9">
                                    <input name="recordName" disabled="disabled" id="recordName" class="form-control input" size="20" type="text" <?php if (isset($record['data'][0]->label_name)) { ?> value="<?php echo $record['data'][0]->label_name; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Label Type</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="recordMajor" disabled="disabled" id="recordMajor" value="1" class="ace" <?php if (isset($record['data'][0]->labeltype_major) && $record['data'][0]->labeltype_major == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Major</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="recordIndy" disabled="disabled" id="recordIndy" value="1" class="ace" <?php if (isset($record['data'][0]->labeltype_indy) && $record['data'][0]->labeltype_indy == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Indy</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="recordDistribution" disabled="disabled" id="recordDistribution" value="1" class="ace" <?php if (isset($record['data'][0]->labeltype_distribution) && $record['data'][0]->labeltype_distribution == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Distribution</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Label Department</label>
                                <div class="col-sm-9">
                                    <input name="recordDepartment" disabled="disabled" id="recordDepartment" class="form-control input" size="20" type="text" <?php if (isset($record['data'][0]->label_department)) { ?> value="<?php echo $record['data'][0]->label_department; ?>" <?php } ?>>
                                </div>
                            </div>
                        </div>
                        <!--record div ends-->
                        <div id="managementDiv" style="display:<?php echo $managementDisplay; ?>">
                            <h3>Management</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Management Name</label>
                                <div class="col-sm-9">
                                    <input name="managementName" disabled="disabled" id="managementName" class="form-control input" size="20" type="text" <?php if (isset($management['data'][0]->management_name)) { ?> value="<?php echo $management['data'][0]->management_name; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Management Type</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="managementArtist" disabled="disabled" id="managementArtist" value="1" onclick="getManagementInner()" class="ace" <?php if (isset($management['data'][0]->managementtype_artist) && $management['data'][0]->managementtype_artist == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Artist</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="managementTour" disabled="disabled" id="managementTour" value="1" class="ace" <?php if (isset($management['data'][0]->managementtype_tour) && $management['data'][0]->managementtype_tour == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Tour</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="managementPersonal" disabled="disabled" id="managementPersonal" onclick="getManagementInner()" value="1" class="ace" <?php if (isset($management['data'][0]->managementtype_personal) && $management['data'][0]->managementtype_personal == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Personal</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="managementFinance" disabled="disabled" id="managementFinance" value="1" class="ace" <?php if (isset($management['data'][0]->managementtype_finance) && $management['data'][0]->managementtype_finance == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Finance/Accounting</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="managementInnerDiv" style="display:none;">
                                <div class="form-group" id="managementWhoDiv">
                                    <label class="col-sm-3">Who Do You Manage? <br />
                                        (Required for Artist)</label>
                                    <div class="col-sm-9">
                                        <input name="managementWho" disabled="disabled" id="managementWho" class="form-control input" size="20" type="text" <?php if (isset($management['data'][0]->management_who)) { ?> value="<?php echo $management['data'][0]->management_who; ?>" <?php } ?>>
                                    </div>
                                </div>
                                <div class="form-group" id="managementIndustryDiv">
                                    <label class="col-sm-3">What Industry?</label>
                                    <div class="col-sm-9">
                                        <input name="managementIndustry" disabled="disabled" id="managementIndustry" class="form-control input" size="20" type="text" <?php if (isset($management['data'][0]->management_industry)) { ?> value="<?php echo $management['data'][0]->management_industry; ?>" <?php } ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--managment div ends-->
                        <div id="clothingDiv" style="display:<?php echo $clothingDisplay; ?>">
                            <h3>Clothing/Apparel Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Clothing/Apparel Name</label>
                                <div class="col-sm-9">
                                    <input name="clothingName" disabled="disabled" id="clothingName" class="form-control input" size="20" type="text" <?php if (isset($clothing['data'][0]->clothing_name)) { ?> value="<?php echo $clothing['data'][0]->clothing_name; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group" id="managementWhoDiv">
                                <label class="col-sm-3">Clothing/Apparel Department</label>
                                <div class="col-sm-9">
                                    <select disabled="disabled" name="clothingDepartment" disabled="disabled" id="clothingDepartment" class="form-control input selectpicker" size="20" type="text">
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'executive') == 0) { ?> selected="selected" <?php } ?> value="executive">Executive</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'promotions') == 0) { ?> selected="selected" <?php } ?> value="promotions">Promotions</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'product_manager_or_marketing') == 0) { ?> selected="selected" <?php } ?> value="product_manager_or_marketing">Product Manager/Marketing</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'sales') == 0) { ?> selected="selected" <?php } ?> value="sales">Sales</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'publicity') == 0) { ?> selected="selected" <?php } ?> value="publicity">Publicity</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'new_media_or_internet') == 0) { ?> selected="selected" <?php } ?> value="new_media_or_internet">New Media/Internet</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'legal_business_or_affairs') == 0) { ?> selected="selected" <?php } ?> value="legal_business_or_affairs">Legal/Business Affairs</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'distribution') == 0) { ?> selected="selected" <?php } ?> value="distribution">Distribution</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'international') == 0) { ?> selected="selected" <?php } ?> value="international">International</option>
                                        <option <?php if (isset($clothing['data'][0]->clothing_department) && strcmp($clothing['data'][0]->clothing_department, 'i.t._or_tech') == 0) { ?> selected="selected" <?php } ?> value="">I.T./Tech</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--clothing div ends-->
                        <div id="promoterDiv" style="display:<?php echo $promoterDisplay; ?>">
                            <h3>Promoter Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Promotion Company Name</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="promoterName" id="promoterName" class="form-control input" size="20" type="text" <?php if (isset($promoter['data'][0]->promoter_name)) { ?> value="<?php echo $promoter['data'][0]->promoter_name; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Type</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="promoterIndy" id="promoterIndy" value="1" onclick="getManagementInner()" class="ace" <?php if (isset($promoter['data'][0]->promotertype_indy) && $promoter['data'][0]->promotertype_indy == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Indy Music Promotion</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="promoterClub" id="promoterClub" value="1" class="ace" <?php if (isset($promoter['data'][0]->promotertype_club) && $promoter['data'][0]->promotertype_club == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Club Promotion</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="promoterSpecial" id="promoterSpecial" onclick="getManagementInner()" value="1" class="ace" <?php if (isset($promoter['data'][0]->promotertype_event) && $promoter['data'][0]->promotertype_event == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Special Events</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="promoterStreet" id="promoterStreet" value="1" class="ace" <?php if (isset($promoter['data'][0]->promotertype_street) && $promoter['data'][0]->promotertype_street == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Street Promotion</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="managementWhoDiv">
                                <label class="col-sm-3">Department</label>
                                <div class="col-sm-9">
                                    <select name="promoterDepartment" id="promoterDepartment" disabled="disabled" class="form-control input selectpicker" size="20" type="text">
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'executive') == 0) { ?> selected="selected" <?php } ?> value="executive">Executive</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'promotions') == 0) { ?> selected="selected" <?php } ?> value="promotions">Promotions</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'product_manager_or_marketing') == 0) { ?> selected="selected" <?php } ?> value="product_manager_or_marketing">Product Manager/Marketing</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'sales') == 0) { ?> selected="selected" <?php } ?> value="sales">Sales</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'publicity') == 0) { ?> selected="selected" <?php } ?> value="publicity">Publicity</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'new_media_or_internet') == 0) { ?> selected="selected" <?php } ?> value="new_media_or_internet">New Media/Internet</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'legal_or_business_affairs') == 0) { ?> selected="selected" <?php } ?> value="legal_or_business_affairs">Legal/Business Affairs</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'distribution') == 0) { ?> selected="selected" <?php } ?> value="distribution">Distribution</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'international') == 0) { ?> selected="selected" <?php } ?> value="international">International</option>
                                        <option <?php if (isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department, 'i.t._or_tech') == 0) { ?> selected="selected" <?php } ?> value="i.t._or_tech">I.T./Tech</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Company Website</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="promoterWebsite" id="promoterWebsite" class="form-control input" size="20" type="text" <?php if (isset($promoter['data'][0]->promoter_website)) { ?> value="<?php echo $promoter['data'][0]->promoter_website; ?>" <?php } ?>>
                                </div>
                            </div>
                        </div>
                        <!--promoter div ends-->
                        <div id="specialDiv" style="display:<?php echo $specialDisplay; ?>">
                            <h3>Special Services Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Type</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="specialCorporate" id="specialCorporate" value="1" onclick="getManagementInner()" class="ace" <?php if (isset($special['data'][0]->servicestype_corporate) && $special['data'][0]->servicestype_corporate == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Corporate</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="specialGraphic" id="specialGraphic" value="1" class="ace" <?php if (isset($special['data'][0]->servicestype_graphicdesign) && $special['data'][0]->servicestype_graphicdesign == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Graphic Design</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="specialWeb" id="specialWeb" onclick="getManagementInner()" value="1" class="ace" <?php if (isset($special['data'][0]->servicestype_webdesign) && $special['data'][0]->servicestype_webdesign == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Web Design</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="specialOther" id="specialOther" value="1" class="ace" <?php if (isset($special['data'][0]->servicestype_other) && $special['data'][0]->servicestype_other == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Other</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Company Name</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="specialName" id="specialName" class="form-control input" size="20" type="text" <?php if (isset($special['data'][0]->services_name)) { ?> value="<?php echo $special['data'][0]->services_name; ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Company Website</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="specialWebsite" id="specialWebsite" class="form-control input" size="20" type="text" <?php if (isset($special['data'][0]->services_website)) { ?> value="<?php echo $special['data'][0]->services_website; ?>" <?php } ?>>
                                </div>
                            </div>
                        </div>
                        <!--special div ends-->
                        <?php // if($production['numRows']>0) { $production } 
                        ?>
                        <div id="productionDiv" style="display:<?php echo $productionDisplay; ?>">
                            <h3>Production/Talent Information</h3>
                            <div class="form-group">
                                <label class="col-sm-3">Type</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="productionArtist" id="productionArtist" value="1" onclick="getManagementInner()" class="ace" <?php if (isset($production['data'][0]->productiontype_artist) && $production['data'][0]->productiontype_artist == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Artist</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="productionProducer" id="productionProducer" value="1" class="ace" <?php if (isset($production['data'][0]->productiontype_producer) && $production['data'][0]->productiontype_producer == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl"> Producer</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="productionChoregrapher" id="productionChoregrapher" onclick="getManagementInner()" value="1" class="ace" <?php if (isset($production['data'][0]->productiontype_choreographer) && $production['data'][0]->productiontype_choreographer == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Choregrapher</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" disabled="disabled" name="productionSound" id="productionSound" value="1" class="ace" <?php if (isset($production['data'][0]->productiontype_sound) && $production['data'][0]->productiontype_sound == 1) { ?> checked="checked" <?php } ?> />
                                            <span class="lbl">Sound Engineer</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Company Name</label>
                                <div class="col-sm-9">
                                    <input disabled="disabled" name="productionName" id="productionName" class="form-control input" size="20" type="text" <?php if (isset($production['data'][0]->production_name)) { ?> value="<?php echo $production['data'][0]->production_name; ?>" <?php } ?>>
                                </div>
                            </div>
                        </div>
                        <!--production div ends-->
                    </div>
                </form>

              </div>
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
           </div>
         </div>
       </div>
     </div>
	 </section>
	 
@endsection