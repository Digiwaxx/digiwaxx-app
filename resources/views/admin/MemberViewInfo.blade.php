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

					<a href="<?php echo url("admin/members"); ?>">Members</a>

				</li>

				<li class="active">View Member</li>

			</ul><!-- /.breadcrumb -->
		</div>

		<div class="page-content">
	<?php if (!empty($members['data'][0])){?>	    
	<div class="row">
   <div class="col-xs-12">
      <!-- PAGE CONTENT BEGINS -->
      <div class="row">
         <div class="col-xs-12">
            <?php $member = $members['data'][0]; ?>
            <div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Username </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->uname); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Name </div>
                     <div class="profile-info-value">
                        <?php echo ucfirst(urldecode($member->fname)).' '.ucfirst(urldecode($member->lname)); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Stage/DJ Name </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->stagename); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Phone Number </div>
                     <div class="profile-info-value">
                        <a href="tel:<?php echo urldecode($member->phone); ?>"><?php echo urldecode($member->phone); ?></a>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Email </div>
                     <div class="profile-info-value">
                        <a href="mailto:<?php echo urldecode($member->email); ?>"><?php echo urldecode($member->email); ?></a>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Date of Birth </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->dob); ?>
                     </div>
                  </div>                  
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Address1 </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->address1); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Address2 </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->address2); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">City </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->city); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> State </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->state); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Country </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->country); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Zip </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->zip); ?>
                     </div>
                  </div>
                  <!--div class="profile-info-row">
                     <div class="profile-info-name"> Age </div>
                     <div class="profile-info-value">
                        <--?php echo urldecode($member->age); ?>
                     </div>
                  </div-->
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Gender </div>
                     <div class="profile-info-value">
                        <?php echo $member->sex; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Website </div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->website); ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> What is your field? </div>
                     <div class="profile-info-value">
                        <?php if($member->dj_mixer==1) { echo 'DJ / Mixer<br />'; } ?>	
                        <?php if($member->radio_station==1) { echo 'Radio Station<br />'; } ?>	
                        <?php if($member->mass_media==1) { echo 'Mass Media<br />'; } ?>	
                        <?php if($member->record_label==1) { echo 'Record Label<br />'; } ?>	
                        <?php if($member->management==1) { echo 'Management<br />'; } ?>	
                        <?php if($member->clothing_apparel==1) { echo 'Clothing/Apparel<br />'; } ?>		
                        <?php if($member->promoter==1) { echo 'Promoter<br />'; } ?>		
                        <?php if($member->special_services==1) { echo 'Special Services<br />'; } ?>		
                        <?php if($member->production_talent==1) { echo 'Prodution/Talent<br />'; } ?>													
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">How did you hear about Digiwaxx?</div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->howheard); ?>
                     </div>
                  </div>
                  <?php $howDisplay = 'none';
                     if(strcmp('A Current Member',urldecode($member->howheard))==0 || strcmp('DJ Crew',urldecode($member->howheard))==0 || strcmp('Record Pool',urldecode($member->howheard))==0)
                     
                      { $howDisplay = ''; } 
                     
                      
                     
                     ?>	
                  <div class="profile-info-row" style="display:<?php echo $howDisplay; ?>">
                     <div class="profile-info-name">Name</div>
                     <div class="profile-info-value">
                        <?php echo urldecode($member->howheardvalue); ?>
                     </div>
                  </div>
               </div>
               <div class="table-header">
                Package Details
                </div>
              <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Package Name </div>
                     <div class="profile-info-value">
                         <?php //dd($member_package_details); ?>
                        <?php if($member_package_details == 'Standard'){echo 'Standard';}else{ echo $member_package_details->package_name; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Package Type </div>
                     <div class="profile-info-value">
                        <?php if($member_package_details == 'Standard'){echo 'Lifetime';}else{ echo $member_package_details->package_type; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Package Amount </div>
                     <div class="profile-info-value">
                        <?php if($member_package_details == 'Standard'){echo 'Free';}else{ echo '$'.$member_package_details->payment_amount; } ?>
                     </div>
                  </div>
                  <?php if($package_count == 0 || ($package_count > 0 && $member_package_details->package_id == 7)){
                  }else{
                  ?>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Payment Method </div>
                     <div class="profile-info-value">
                        <?php if($member_package_details == 'Standard'){echo '-';}else{ echo $member_package_details->payment_method; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Payment Status </div>
                     <div class="profile-info-value">
                        <?php if($member_package_details == 'Standard'){echo '-';}else{ if($member_package_details->payment_status == 1){echo 'Paid';}else{echo 'Not Paid';} } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Payment Start Date </div>
                     <div class="profile-info-value">
                        <?php if($member_package_details == 'Standard'){echo '-';}else{ echo $member_package_details->package_start_date; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Payment Expiry Date </div>
                     <div class="profile-info-value">
                        <?php if($member_package_details == 'Standard'){echo '-';}else{ echo $member_package_details->package_expiry_date; } ?>
                     </div>
                  </div>
                  <?php } ?>
               </div>

               <div class="table-header">
                  Social Media
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Facebook </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->facebook)) { echo $social['data'][0]->facebook; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Twitter </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->twitter)) { echo $social['data'][0]->twitter; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Instagram </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->instagram)) { echo $social['data'][0]->instagram; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> snapchat </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->snapchat)) { echo $social['data'][0]->snapchat; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Tiktok </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->tiktok)) { echo $social['data'][0]->tiktok; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Triller </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->triller)) { echo $social['data'][0]->triller; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Twitch </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->twitch)) { echo $social['data'][0]->twitch; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Mixcloud </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->mixcloud)) { echo $social['data'][0]->mixcloud; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Reddit </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->reddit)) { echo $social['data'][0]->reddit; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Linkedin </div>
                     <div class="profile-info-value">
                        <?php if(isset($social['data'][0]->linkedin)) { echo $social['data'][0]->linkedin; } ?>
                     </div>
                  </div>
               </div>
               <div class="table-header">
                  MY PREFERENCES
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Audio files quality </div>
                     <div class="profile-info-value">
                        <?php if(isset($members['data'][0]->audioQuality) && strcmp($members['data'][0]->audioQuality,'192')==0) { echo '192'; } ?>
                        <?php if(isset($members['data'][0]->audioQuality) && strcmp($members['data'][0]->audioQuality,'256')==0) { echo '256'; } ?>
                        <?php if(isset($members['data'][0]->audioQuality) && strcmp($members['data'][0]->audioQuality,'320')==0) { echo '320'; } ?>
                        <?php if(isset($members['data'][0]->audioQuality) && strcmp($members['data'][0]->audioQuality,'WAVE')==0) { echo 'WAVE'; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> What type of computer do you use? </div>
                     <div class="profile-info-value">
                        <?php if(isset($members['data'][0]->computer) && strcmp($members['data'][0]->computer,'PC')==0) { echo 'PC'; } ?>
                        <?php if(isset($members['data'][0]->computer) && strcmp($members['data'][0]->computer,'Mac')==0) { echo 'Mac'; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Turntables  Type </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->turntables_type; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Mixer Type </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->mixer_type; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Needles Type </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->needles_type; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Headphones </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->headphones; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Preffered Player </div>
                     <div class="profile-info-value">
                        <?php if(isset($members['data'][0]->player) && strcmp(urldecode($members['data'][0]->player),'Windows Media Player')==0) { echo 'Windows Media Player'; } ?>
                        <?php if(isset($members['data'][0]->player) && strcmp(urldecode($members['data'][0]->player),'Real Player')==0) { echo 'Real Player'; } ?>
                        <?php if(isset($members['data'][0]->player) && strcmp(urldecode($members['data'][0]->player),'Quicktime')==0) { echo 'Quicktime'; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Game System </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->game_system; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Cell Phone</div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->cell_phone; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Hat Size</div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->hat_size; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Shirt  Size </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->shirt_size; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Pants Size </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->pants_size; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Shoe Size </div>
                     <div class="profile-info-value">
                        <?php echo $members['data'][0]->shoe_size; ?>
                     </div>
                  </div>
               </div>
               <div class="table-header">
                  DJ/Mixer Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> What Type of DJ? </div>
                     <div class="profile-info-value">
                        <?php if($member->djtype_commercialreporting==1) { echo 'Commercial/Mixshow DJ (Reporting)'; echo '<br />'; } ?>
                        <?php if($member->djtype_commercialnonreporting==1) { echo 'Commercial/Mixshow DJ (Non-Reporting)'; echo '<br />'; } ?>
                        <?php if($member->djtype_club==1) { echo 'Club DJ'; echo '<br />'; } ?>
                        <?php if($member->djtype_mixtape==1) { echo 'Mix Tape DJ'; echo '<br />'; } ?>
                        <?php if($member->djtype_satellite==1) { echo 'Satellite Radio DJ'; echo '<br />'; } ?>
                        <?php if($member->djtype_internet==1) { echo 'Internet Radio DJ'; echo '<br />'; } ?>
                        <?php if($member->djtype_college==1) { echo 'College Radio'; echo '<br />'; } ?>	
                        <?php if($member->djtype_pirate==1) { echo 'Pirate Radio'; echo '<br />'; } ?>	
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> What Do You Use to Play Music?</div>
                     <div class="profile-info-value">
                        <?php
                           $mp3s = array(); $mp3s_string = '&nbsp;&nbsp;&nbsp;';
                           
                           
                           
                           if($member->djwith_mp3==1) { 
                           
                           
                           
                           if($member->djwith_mp3_serato==1) { $mp3s[] = 'Serato Scratch'; echo 'ram'; } 
                           
                           if($member->djwith_mp3_final==1) { $mp3s[] = 'Final Scratch Pro';  } 
                           
                           if($member->djwith_mp3_pcdj==1) { $mp3s[] = 'PCDJ'; } 
                           
                           if($member->djwith_mp3_ipod==1) { $mp3s[] = 'I-Pod'; } 
                           
                           if($member->djwith_mp3_other==1) { $mp3s[] = 'Other'; } 
                           
                           
                           
                           if(count($mp3s)>1)
                           
                           {
                           
                             $mp3s_string .= '- '.implode(', ',$mp3s); 
                           
                             
                           
                           }
                           
                           else if(count($mp3s)==1)
                           
                           {
                           
                             $mp3s_string .= '- '.$mp3s[0]; 
                           
                           }
                           
                           
                           
                           echo 'MP3 '.$mp3s_string.'<br />'; } 
                           
                           
                           
                           $cds = array(); $cds_string = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                           
                           if($member->djwith_cd==1) { 
                           
                           
                           
                           if($member->djwith_cd_stanton==1) { $cds[] = 'Stanton Player'; }
                           
                           if($member->djwith_cd_numark==1) { $cds[] = 'Numark Player'; }
                           
                           if($member->djwith_cd_american==1) { $cds[] = 'American Audio Player'; }
                           
                           if($member->djwith_cd_vestax==1) { $cds[] = 'Vestax Player'; }
                           
                           if($member->djwith_cd_technics==1) { $cds[] = 'Technics Player'; }
                           
                           if($member->djwith_cd_gemini==1) { $cds[] = 'Gemini Player'; }
                           
                           if($member->djwith_cd_denon==1) { $cds[] = 'Denon Player';  }
                           
                           if($member->djwith_cd_gemsound==1) { $cds[] = 'GemSound Player'; }
                           
                           if($member->djwith_cd_pioneer==1) { $cds[] = 'Pioneer Player'; }
                           
                           if($member->djwith_cd_tascam==1) { $cds[] = 'Tascam Player'; }
                           
                           if($member->djwith_cd_other==1) { $cds[] = 'Other';  }
                           
                           
                           
                           if(count($cds)>1)
                           
                           {
                           
                             $cds_string .= '- '.implode(', ',$cds); 
                           
                           }
                           
                           else if(count($cds)==1)
                           
                           {
                           
                             $cds_string .= '- '.$cds[0]; 
                           
                           }
                           
                           
                           
                           echo 'CD '.$cds_string.'<br />'; } 
                           
                           
                           
                           $vinyls = array(); $vinyl_string = '&nbsp;&nbsp;&nbsp;';
                           
                           if($member->djwith_vinyl==1) { 
                           
                           
                           
                           if($member->djwith_vinyl_12==1) { $vinyls[] = '12"';  }
                           
                           if($member->djwith_vinyl_45==1) { $vinyls[] = '45"';  }
                           
                           if($member->djwith_vinyl_78==1) { $vinyls[] = '78"';  }
                           
                           
                           
                           if(count($vinyls)>1)
                           
                           { 
                           
                             $vinyl_string .= '- '.implode(', ',$vinyls); 
                           
                           }
                           
                           else if(count($vinyls)==1)
                           
                           {
                           
                             $vinyl_string .= '- '.$vinyls[0]; 
                           
                           }
                           
                           
                           
                           echo 'Vinyl '.$vinyl_string.'<br />'; } ?>	
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"></div>
                     <div class="profile-info-value">
                        <?php if($member->djtype_commercialreporting==1) { ?>			
                        <div class="table-header">
                           Commercial/Mixshow DJ (Reporting)
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Station Call Letters </div>
                              <div class="profile-info-value">
                                 <?php echo $member->commercialdj_call; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Station Name</div>
                              <div class="profile-info-value">
                                 <?php echo $member->commercialdj_name; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Station Frequency </div>
                              <div class="profile-info-value">
                                 <?php echo $member->commercialdj_frequency; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Name/s </div>
                              <div class="profile-info-value">
                                 <?php echo $member->commercialdj_showname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Type </div>
                              <div class="profile-info-value">
                                  
                                 <?php
                                    if(strcmp($member->commercialdj_showtype,'morning_mix')==0) { echo 'Morning Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'morning_show')==0) { echo 'Morning Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'midday_mix')==0) { echo 'Midday Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'mindday_show')==0) { echo 'Midday Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'lunch_mix')==0) { echo 'Lunch Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'afternoon_mix')==0) { echo 'Afternoon Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'afternoon_show')==0) { echo 'Afternoon Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'evening_mix')==0) { echo 'Evening Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'evening_show')==0) { echo 'Evening Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'latenight_mix')==0) { echo 'Late Night Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'latenight_show')==0) { echo 'Late Night Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'overnight_mix')==0) { echo 'Overnight Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'overnight_show')==0) { echo 'Overnight Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'weekend_mix')==0) { echo 'Weekend Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'weekend_show')==0) { echo 'Weekend Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'quick_mix')==0) { echo 'Quick Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'traffic_mix')==0) { echo 'Traffic Mix'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'speciality_show')==0) { echo 'Speciality Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'talk_show')==0) { echo 'Talk Show'; }
                                    
                                    if(strcmp($member->commercialdj_showtype,'live_broadcast')==0) { echo 'Live Broadcast'; }
                                    
                                    
                                    
                                    ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Days </div>
                              <div class="profile-info-value">
                                 <?php $commercialDays = array();
                                    if($member->commercialdj_monday==1) { $commercialDays[] = 'Monday'; }
                                    
                                    if($member->commercialdj_tuesday==1) { $commercialDays[] = 'Tuesday'; }
                                    
                                    if($member->commercialdj_wednesday==1) { $commercialDays[] = 'Wednesday'; }
                                    
                                    if($member->commercialdj_thursday==1) { $commercialDays[] = 'Thursday'; }
                                    
                                    if($member->commercialdj_friday==1) { $commercialDays[] = 'Friday'; }
                                    
                                    if($member->commercialdj_saturday==1) { $commercialDays[] = 'Saturday'; }
                                    
                                    if($member->commercialdj_sunday==1) { $commercialDays[] = 'Sunday'; }
                                    
                                    if($member->commercialdj_varies==1) { $commercialDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $commercial_days = '';
                                    
                                    if(count($commercialDays)>1)
                                    
                                    {
                                    
                                      $commercial_days = implode(', ',$commercialDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $commercial_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $commercial_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Times/s </div>
                              <div class="profile-info-value">
                                 <?php echo $member->commercialdj_showtime; ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_commercialnonreporting==1) { ?>			
                        <div class="table-header">
                           Commercial/Mixshow DJ (Non-Reporting)
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Station Call Letters </div>
                              <div class="profile-info-value">
                                 <?php echo $member->noncommercialdj_call; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Station Name</div>
                              <div class="profile-info-value">
                                 <?php echo $member->noncommercialdj_name; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Station Frequency </div>
                              <div class="profile-info-value">
                                 <?php echo $member->noncommercialdj_frequency; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Name/s </div>
                              <div class="profile-info-value">
                                 <?php echo $member->noncommercialdj_showname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Type </div>
                              <div class="profile-info-value">
                                 <?php
                                    if(strcmp($member->noncommercialdj_showtype,'morning_mix')==0) { echo 'Morning Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'morning_show')==0) { echo 'Morning Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'midday_mix')==0) { echo 'Midday Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'mindday_show')==0) { echo 'Midday Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'lunch_mix')==0) { echo 'Lunch Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'afternoon_mix')==0) { echo 'Afternoon Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'afternoon_show')==0) { echo 'Afternoon Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'evening_mix')==0) { echo 'Evening Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'evening_show')==0) { echo 'Evening Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'latenight_mix')==0) { echo 'Late Night Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'latenight_show')==0) { echo 'Late Night Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'overnight_mix')==0) { echo 'Overnight Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'overnight_show')==0) { echo 'Overnight Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'weekend_mix')==0) { echo 'Weekend Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'weekend_show')==0) { echo 'Weekend Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'quick_mix')==0) { echo 'Quick Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'traffic_mix')==0) { echo 'Traffic Mix'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'speciality_show')==0) { echo 'Speciality Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'talk_show')==0) { echo 'Talk Show'; }
                                    
                                    if(strcmp($member->noncommercialdj_showtype,'live_broadcast')==0) { echo 'Live Broadcast'; }
                                    
                                    
                                    
                                    ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Days </div>
                              <div class="profile-info-value">
                                 <?php $noncommercialDays = array();
                                    if($member->noncommercialdj_monday==1) { $noncommercialDays[] = 'Monday'; }
                                    
                                    if($member->noncommercialdj_tuesday==1) { $noncommercialDays[] = 'Tuesday'; }
                                    
                                    if($member->noncommercialdj_wednesday==1) { $noncommercialDays[] = 'Wednesday'; }
                                    
                                    if($member->noncommercialdj_thursday==1) { $noncommercialDays[] = 'Thursday'; }
                                    
                                    if($member->noncommercialdj_friday==1) { $noncommercialDays[] = 'Friday'; }
                                    
                                    if($member->noncommercialdj_saturday==1) { $noncommercialDays[] = 'Saturday'; }
                                    
                                    if($member->noncommercialdj_sunday==1) { $noncommercialDays[] = 'Sunday'; }
                                    
                                    if($member->noncommercialdj_varies==1) { $noncommercialDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $noncommercial_days = '';
                                    
                                    if(count($noncommercialDays)>1)
                                    
                                    {
                                    
                                      $noncommercial_days = implode(', ',$noncommercialDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $noncommercial_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $noncommercial_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name"> Show Times/s </div>
                              <div class="profile-info-value">
                                 <?php echo $member->noncommercialdj_showtime; ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_club==1) { ?>
                        <div class="table-header">
                           Club DJ
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Club Name </div>
                              <div class="profile-info-value">
                                 <?php echo $member->clubdj_clubname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Estimated Venue Capacity</div>
                              <div class="profile-info-value">
                                 <?php echo $member->clubdj_capacity; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Party Type</div>
                              <div class="profile-info-value">
                                 <?php if(strcmp($member->clubdj_partytype,'mainstream')==0) { echo 'Mainstream'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'urban')==0) { echo 'Urban'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'local')==0) { echo 'Local'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'upscale_or_trendy')==0) { echo 'Upscale / Trendy'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'eclectic')==0) { echo 'Eclectic'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'lounge')==0) { echo 'Lounge'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'industry')==0) { echo 'Industry'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'afterwork')==0) { echo 'Afterwork'; } ?>
                                 <?php if(strcmp($member->clubdj_partytype,'spoken_word')==0) { echo 'Spoken Word'; } ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Party Format</div>
                              <div class="profile-info-value">
                                 <?php if($member->clubdj_hiphop==1) { echo 'Hip Hop'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_rb==1) { echo 'R&B'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_pop==1) { echo 'Pop/Top 40'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_reggae==1) { echo 'Reggae'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_house==1) { echo 'House'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_calypso==1) { echo 'Calypso/Soca'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_rock==1) { echo 'Rock'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_techno==1) { echo 'Techno'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_trance==1) { echo 'Trance'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_afro==1) { echo 'Afro-Beat'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_reggaeton==1) { echo 'Reggaeton'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_gogo==1) { echo 'Go-Go'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_neosoul==1) { echo 'Neo-Soul'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_oldschool==1) { echo 'Old School'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_electronic==1) { echo 'Electronic'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_latin==1) { echo 'Latin Soul'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_dance==1) { echo 'Electronica/Dance'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_jazz==1) { echo 'Jazz'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_country==1) { echo 'Country'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_world==1) { echo 'World'; echo '<br />'; } ?>	
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Day/s</div>
                              <div class="profile-info-value">
                                 <?php if($member->clubdj_monday==1) { echo 'Monday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_tuesday==1) { echo 'Tuesday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_wednesday==1) { echo 'Wednesday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_thursday==1) { echo 'Thursday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_friday==1) { echo 'Friday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_saturday==1) { echo 'Saturday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_sunday==1) { echo 'Sunday'; echo '<br />'; } ?>	
                                 <?php if($member->clubdj_varies==1) { echo 'Varies'; echo '<br />'; } ?>	
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">City</div>
                              <div class="profile-info-value">
                                 <?php echo $member->clubdj_city; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">State</div>
                              <div class="profile-info-value">
                                 <?php echo $member->clubdj_state; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Country</div>
                              <div class="profile-info-value">
                                 <?php echo $member->clubdj_intcountry; ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_mixtape==1) { ?>				
                        <div class="table-header">
                           Mix Tape DJ
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Mixtape Series Name</div>
                              <div class="profile-info-value">
                                 <?php echo $member->mixtapedj_name; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Mixtape Type</div>
                              <div class="profile-info-value">
                                 <?php if(strcmp($member->mixtapedj_type,'hip_hop')==0) { echo 'Hip Hop'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'r_and_b')==0) { echo 'R&B'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'old_school')==0) { echo 'Old School'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'neo_school')==0) { echo 'Neo School'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'corporate_or_speciality')==0) { echo 'Corporate/Speciality'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'mainstream')==0) { echo 'Mainstream'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'urban')==0) { echo 'Urban'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'local')==0) { echo 'Local'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'upscale_or_trendy')==0) { echo 'Upscale/Trendy'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'eclectic')==0) { echo 'Eclectic'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'lounge')==0) { echo 'Lounge'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'industry')==0) { echo 'Industry'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'afterwork')==0) { echo 'Afterwork'; } ?>
                                 <?php if(strcmp($member->mixtapedj_type,'spoken_word')==0) { echo 'Spoken Word'; } ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Release Schedule</div>
                              <div class="profile-info-value">
                                 <?php if(strcmp($member->mixtapedj_schedule,'weekly')==0) { echo 'Weekly'; } ?>
                                 <?php if(strcmp($member->mixtapedj_schedule,'bi-weekly')==0) { echo 'Bi-Weekly'; } ?>
                                 <?php if(strcmp($member->mixtapedj_schedule,'monthly')==0) { echo 'Monthly'; } ?>
                                 <?php if(strcmp($member->mixtapedj_schedule,'bi-monthly')==0) { echo 'Bi-Monthly'; } ?>
                                 <?php if(strcmp($member->mixtapedj_schedule,'quartely')==0) { echo 'Quartely'; } ?>
                                 <?php if(strcmp($member->mixtapedj_schedule,'bi-annual')==0) { echo 'Bi-Annual'; } ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Unit Distribution</div>
                              <div class="profile-info-value">
                                 <?php if(strcmp($member->mixtapedj_distribution,'(10_-_100)')==0) { echo '(10 - 100)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(100_-_500)')==0) { echo '(100 - 500)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(500_-_1000)')==0) { echo '(500 - 1000)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(1000_-_5000)')==0) { echo '(1000 - 5000)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(5000_-_10000)')==0) { echo '(5000 - 10000)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(10,000_-_30,000)')==0) { echo '(10,000 - 30,000)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(30,000_-_50,000)')==0) { echo '(30,000 - 50,000)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(50,0000_-_100,000)')==0) { echo '(50,0000 - 100,000)'; } ?>
                                 <?php if(strcmp($member->mixtapedj_distribution,'(100,000+)')==0) { echo '(100,000+)'; } ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_satellite==1) { ?>				
                        <div class="table-header">
                           Satellite Radio DJ
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Network Name</div>
                              <div class="profile-info-value">
                                 <?php echo $member->satellitedj_stationname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Channel Name</div>
                              <div class="profile-info-value">
                                 <?php echo $member->satellitedj_showname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Channel Number</div>
                              <div class="profile-info-value">
                                 <?php echo $member->satellitedj_channelname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->satellitedj_channelnumber; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Day/s</div>
                              <div class="profile-info-value">
                                 <?php $satelliteDays = array();
                                    if($member->satellitedj_monday==1) { $satelliteDays[] = 'Monday'; }
                                    
                                    if($member->satellitedj_tuesday==1) { $satelliteDays[] = 'Tuesday'; }
                                    
                                    if($member->satellitedj_wednesday==1) { $satelliteDays[] = 'Wednesday'; }
                                    
                                    if($member->satellitedj_thursday==1) { $satelliteDays[] = 'Thursday'; }
                                    
                                    if($member->satellitedj_friday==1) { $satelliteDays[] = 'Friday'; }
                                    
                                    if($member->satellitedj_saturday==1) { $satelliteDays[] = 'Saturday'; }
                                    
                                    if($member->satellitedj_sunday==1) { $satelliteDays[] = 'Sunday'; }
                                    
                                    if($member->satellitedj_varies==1) { $satelliteDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $satellite_days = '';
                                    
                                    if(count($satelliteDays)>1)
                                    
                                    {
                                    
                                      $satellite_days = implode(', ',$satelliteDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $satellite_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $satellite_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Time/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->satellitedj_showtime; ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_internet==1) { ?>				
                        <div class="table-header">
                           Internet Radio DJ
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Station Website</div>
                              <div class="profile-info-value">
                                 <?php echo $member->internetdj_stationwebsite; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Type</div>
                              <div class="profile-info-value">
                                 <?php echo $member->internetdj_showtype; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->internetdj_showname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Day/s</div>
                              <div class="profile-info-value">
                                 <?php $internetDays = array();
                                    if($member->internetdj_monday==1) { $internetDays[] = 'Monday'; }
                                    
                                    if($member->internetdj_tuesday==1) { $internetDays[] = 'Tuesday'; }
                                    
                                    if($member->internetdj_wednesday==1) { $internetDays[] = 'Wednesday'; }
                                    
                                    if($member->internetdj_thursday==1) { $internetDays[] = 'Thursday'; }
                                    
                                    if($member->internetdj_friday==1) { $internetDays[] = 'Friday'; }
                                    
                                    if($member->internetdj_saturday==1) { $internetDays[] = 'Saturday'; }
                                    
                                    if($member->internetdj_sunday==1) { $internetDays[] = 'Sunday'; }
                                    
                                    if($member->internetdj_varies==1) { $internetDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $internet_days = '';
                                    
                                    if(count($internetDays)>1)
                                    
                                    {
                                    
                                      $internet_days = implode(', ',$internetDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $internet_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $internet_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Time/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->internetdj_showtime; ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_college==1) { ?>				
                        <div class="table-header">
                           College Radio
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Call Letters</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_callletters; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">College Name</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_collegename; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Station Frequency</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_stationfrequency; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Type</div>
                              <div class="profile-info-value">
                                 <?php 
                                    if(strcmp($member->collegedj_showtype,'morning_mix')==0) { echo 'Morning Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'morning_show')==0) { echo 'Morning Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'midday_mix')==0) { echo 'Midday Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'mindday_show')==0) { echo 'Midday Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'lunch_mix')==0) { echo 'Lunch Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'afternoon_mix')==0) { echo 'Afternoon Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'afternoon_show')==0) { echo 'Afternoon Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'evening_mix')==0) { echo 'Evening Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'evening_show')==0) { echo 'Evening Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'latenight_mix')==0) { echo 'Late Night Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'latenight_show')==0) { echo 'Late Night Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'overnight_mix')==0) { echo 'Overnight Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'overnight_show')==0) { echo 'Overnight Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'weekend_mix')==0) { echo 'Weekend Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'weekend_show')==0) { echo 'Weekend Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'quick_mix')==0) { echo 'Quick Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'traffic_mix')==0) { echo 'Traffic Mix'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'speciality_show')==0) { echo 'Speciality Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'talk_show')==0) { echo 'Talk Show'; }
                                    
                                    if(strcmp($member->collegedj_showtype,'live_broadcast')==0) { echo 'Live Broadcast'; }
                                    
                                    ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_showname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Day/s</div>
                              <div class="profile-info-value">
                                 <?php $collegeDays = array();
                                    if($member->collegedj_monday==1) { $collegeDays[] = 'Monday'; }
                                    
                                    if($member->collegedj_tuesday==1) { $collegeDays[] = 'Tuesday'; }
                                    
                                    if($member->collegedj_wednesday==1) { $collegeDays[] = 'Wednesday'; }
                                    
                                    if($member->collegedj_thursday==1) { $collegeDays[] = 'Thursday'; }
                                    
                                    if($member->collegedj_friday==1) { $collegeDays[] = 'Friday'; }
                                    
                                    if($member->collegedj_saturday==1) { $collegeDays[] = 'Saturday'; }
                                    
                                    if($member->collegedj_sunday==1) { $collegeDays[] = 'Sunday'; }
                                    
                                    if($member->collegedj_varies==1) { $collegeDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $college_days = '';
                                    
                                    if(count($collegeDays)>1)
                                    
                                    {
                                    
                                      $college_days = implode(', ',$collegeDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $college_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $college_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Times</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_city; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">City / Province / Town</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_city; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">State</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_state; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Country</div>
                              <div class="profile-info-value">
                                 <?php echo $member->collegedj_intcountry; ?>
                              </div>
                           </div>
                        </div>
                        <?php } if($member->djtype_pirate==1) { ?>
                        <div class="table-header">
                           Pirate Radio
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Station Frequency</div>
                              <div class="profile-info-value">
                                 <?php echo $member->piratedj_stationfrequency; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->piratedj_showname; ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Day/s</div>
                              <div class="profile-info-value">
                                 <?php $pirateDays = array();
                                    if($member->piratedj_monday==1) { $pirateDays[] = 'Monday'; }
                                    
                                    if($member->piratedj_tuesday==1) { $pirateDays[] = 'Tuesday'; }
                                    
                                    if($member->piratedj_wednesday==1) { $pirateDays[] = 'Wednesday'; }
                                    
                                    if($member->piratedj_thursday==1) { $pirateDays[] = 'Thursday'; }
                                    
                                    if($member->piratedj_friday==1) { $pirateDays[] = 'Friday'; }
                                    
                                    if($member->piratedj_saturday==1) { $pirateDays[] = 'Saturday'; }
                                    
                                    if($member->piratedj_sunday==1) { $pirateDays[] = 'Sunday'; }
                                    
                                    if($member->piratedj_varies==1) { $pirateDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $pirate_days = '';
                                    
                                    if(count($collegeDays)>1)
                                    
                                    {
                                    
                                      $pirate_days = implode(', ',$pirateDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $pirate_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $pirate_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Time/s</div>
                              <div class="profile-info-value">
                                 <?php echo $member->piratedj_showtime; ?>
                              </div>
                           </div>
                        </div>
                        <?php } ?>				  
                     </div>
                  </div>
               </div>
			   <?php if($radio['numRows'] > 0){ ?>
               <div class="table-header">
                  Radio Station Information (Non-DJ/Mixer) 
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> What is your field? </div>
                     <div class="profile-info-value">
                        <?php if(isset($radio['data'][0]->radiotype_musicdirector) && $radio['data'][0]->radiotype_musicdirector==1) { echo 'Music Director'; echo '<br />'; } ?> 
                        <?php if(isset($radio['data'][0]->radiotype_programdirector) && $radio['data'][0]->radiotype_programdirector==1) { echo 'Program Director'; echo '<br />'; } ?> 
                        <?php if(isset($radio['data'][0]->radiotype_jock) && $radio['data'][0]->radiotype_jock==1) { echo 'On-Air Personality/Jock'; echo '<br />'; } ?> 
                        <?php if(isset($radio['data'][0]->radiotype_promotion) && $radio['data'][0]->radiotype_promotion==1) { echo 'Promotion'; echo '<br />'; } ?> 
                        <?php if(isset($radio['data'][0]->radiotype_production) && $radio['data'][0]->radiotype_production==1) { echo 'Production'; echo '<br />'; } ?> 
                        <?php if(isset($radio['data'][0]->radiotype_sales) && $radio['data'][0]->radiotype_sales==1) { echo 'Sales'; echo '<br />'; } ?> 
                        <?php if(isset($radio['data'][0]->radiotype_tech) && $radio['data'][0]->radiotype_tech==1) { echo 'I.T./Tech'; echo '<br />'; } ?> 
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Station Call Letters</div>
                     <div class="profile-info-value">
                        <?php echo $radio['data'][0]->stationcallletters; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Station Name </div>
                     <div class="profile-info-value">
                        <?php echo $radio['data'][0]->stationname; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Station Frequency</div>
                     <div class="profile-info-value">
                        <?php echo $radio['data'][0]->stationfrequency; ?>
                     </div>
                  </div>
                  <?php if($radio['data'][0]->radiotype_musicdirector==1 || $radio['data'][0]->radiotype_programdirector==1 || $radio['data'][0]->radiotype_jock==1) {	?>		
                  <div class="profile-info-row">
                     <div class="profile-info-name"></div>
                     <div class="profile-info-value">
                        <?php if(isset($radio['data'][0]->radiotype_musicdirector) && $radio['data'][0]->radiotype_musicdirector==1) { ?>													    
                        <div class="table-header">Music Director</div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Music Call Times</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->musicdirector_musiccalltimes;  ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Do You Host a Show?</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->musicdirector_host;  ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->musicdirector_showname;  ?>
                              </div>
                           </div>
						   <?php if(count($media['data']) > 0){ ?>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Days</div>
                              <div class="profile-info-value">
                                 <?php if(isset($media['data'][0]->media_name)) {  echo $media['data'][0]->media_name;  } 
                                    $musicalDays = array();
                                    
                                    // pArr($radio);die();
                                    
                                    if($radio['data'][0]->musicdirector_monday==1) { $musicalDays[] = 'Monday'; }
                                    
                                    if($radio['data'][0]->musicdirector_tuesday==1) { $musicalDays[] = 'Tuesday'; }
                                    
                                    if($radio['data'][0]->musicdirector_wednesday==1) { $musicalDays[] = 'Wednesday'; }
                                    
                                    if($radio['data'][0]->musicdirector_thursday==1) { $musicalDays[] = 'Thursday'; }
                                    
                                    if($radio['data'][0]->musicdirector_friday==1) { $musicalDays[] = 'Friday'; }
                                    
                                    if($radio['data'][0]->musicdirector_saturday==1) { $musicalDays[] = 'Saturday'; }
                                    
                                    if($radio['data'][0]->musicdirector_sunday==1) { $musicalDays[] = 'Sunday'; }
                                    
                                    if($radio['data'][0]->musicdirector_varies==1) { $musicalDays[] = 'Varies'; }
                                    
                                  
                                    
                                    $musical_days = '';
                                    
                                    if(count($musicalDays)>1)
                                    
                                    {
                                    
                                    $musical_days = implode(', ',$musicalDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                    $musical_days = '';
                                    
                                    }
                                    
                                    
                                    
                                    echo $musical_days;
                                    
                                    
                                    
                                    ?>
                              </div>
                           </div>
						<?php } ?>
						
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Time/s</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->musicdirector_showtime;  ?>
                              </div>
                           </div>
                        </div>
                        <!--music director end-->
                        <?php } if(isset($radio['data'][0]->radiotype_programdirector) && $radio['data'][0]->radiotype_programdirector==1) { ?>			
                        <div class="table-header">Program Director</div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Music Call Times</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->programdirector_musiccalltimes;  ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Do You Host a Show?</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->programdirector_host;  ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->programdirector_showname;  ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Days</div>
                              <div class="profile-info-value">
                                 <?php  $programDays = array();
                                    if($radio['data'][0]->programdirector_monday==1) { $programDays[] = 'Monday'; }
                                    
                                    if($radio['data'][0]->programdirector_tuesday==1) { $programDays[] = 'Tuesday'; }
                                    
                                    if($radio['data'][0]->programdirector_wednesday==1) { $programDays[] = 'Wednesday'; }
                                    
                                    if($radio['data'][0]->programdirector_thursday==1) { $programDays[] = 'Thursday'; }
                                    
                                    if($radio['data'][0]->programdirector_friday==1) { $programDays[] = 'Friday'; }
                                    
                                    
                                    if($radio['data'][0]->programdirector_sunday==1) { $programDays[] = 'Sunday'; }
                                    
                                    if($radio['data'][0]->programdirector_varies==1) { $programDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $program_days = '';
                                    
                                    if(count($programDays)>1)
                                    
                                    {
                                    
                                      $program_days = implode(', ',$programDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $program_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $program_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Time/s</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->programdirector_showtime;  ?>
                              </div>
                           </div>
                        </div>
                        <!--program director end-->
                        <?php } if(isset($radio['data'][0]->radiotype_jock) && $radio['data'][0]->radiotype_jock==1) { ?>			
                        <div class="table-header">On-Air Personality/Jock</div>
                        <div class="profile-user-info profile-user-info-striped">
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Name</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->onairpersonality_showname;  ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Days</div>
                              <div class="profile-info-value">
                                 <?php  $personalityDays = array();
                                    if($radio['data'][0]->onairpersonality_monday==1) { $personalityDays[] = 'Monday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_tuesday==1) { $personalityDays[] = 'Tuesday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_wednesday==1) { $personalityDays[] = 'Wednesday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_thursday==1) { $personalityDays[] = 'Thursday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_friday==1) { $personalityDays[] = 'Friday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_saturday==1) { $personalityDays[] = 'Saturday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_sunday==1) { $personalityDays[] = 'Sunday'; }
                                    
                                    if($radio['data'][0]->onairpersonality_varies==1) { $personalityDays[] = 'Varies'; }
                                    
                                    
                                    
                                    $personality_days = '';
                                    
                                    if(count($personalityDays)>1)
                                    
                                    {
                                    
                                      $personality_days = implode(', ',$personalityDays);
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                    
                                      $personality_days = '';
                                    
                                    }
                                    
                                     
                                    
                                     echo $personality_days;
                                    
                                    
                                    
                                     ?>
                              </div>
                           </div>
                           <div class="profile-info-row">
                              <div class="profile-info-name">Show Time/s</div>
                              <div class="profile-info-value">
                                 <?php echo $radio['data'][0]->onairpersonality_showtime;  ?>
                              </div>
                           </div>
                        </div>
                        <!--on air director end-->
                        <?php } ?>               														
                     </div>
                  </div>
                  <?php } ?>
               </div>
			   <?php } if($media['numRows'] > 0){ ?>
               <div class="table-header">
                  Mass Media Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name"> Company Name</div>
                     <div class="profile-info-value">
                        <?php if(isset($media['data'][0]->media_name)) {  echo $media['data'][0]->media_name;  } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Type</div>
                     <div class="profile-info-value">
                        <?php if(isset($media['data'][0]->mediatype_tvfilm) && $media['data'][0]->mediatype_tvfilm==1) { echo 'TV/Film'; echo '<br />'; } ?>
                        <?php if(isset($media['data'][0]->mediatype_publication) && $media['data'][0]->mediatype_publication==1) { echo 'Publication'; echo '<br />'; } ?>
                        <?php if(isset($media['data'][0]->mediatype_newmedia) && $media['data'][0]->mediatype_newmedia==1) { echo 'New Media/Dotcom'; echo '<br />'; } ?>
                        <?php if(isset($media['data'][0]->mediatype_newsletter) && $media['data'][0]->mediatype_newsletter==1) { echo 'Newsletter'; echo '<br />'; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Department </div>
                     <div class="profile-info-value">
                        <?php if(isset($media['data'][0]->media_department)) { echo $media['data'][0]->media_department;  } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Company Website</div>
                     <div class="profile-info-value">
                        <?php echo (isset($media['data'][0]->media_website)) ? $media['data'][0]->media_website : ''; ?>
                     </div>
                  </div>
               </div>
			   <?php } if($record['numRows'] > 0){ ?>
               <div class="table-header">
                  Record Label Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name">Label Name</div>
                     <div class="profile-info-value">
                        <?php echo $record['data'][0]->label_name; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Label Type</div>
                     <div class="profile-info-value">
                        <?php if(isset($record['data'][0]->labeltype_major) && $record['data'][0]->labeltype_major==1) { echo 'Major'; echo '<br />'; } ?>
                        <?php if(isset($record['data'][0]->labeltype_indy) && $record['data'][0]->labeltype_indy==1) { echo 'Indy'; echo '<br />'; } ?>
                        <?php if(isset($record['data'][0]->labeltype_distribution) && $record['data'][0]->labeltype_distribution==1) { echo 'Distribution'; echo '<br />'; } ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Label Department </div>
                     <div class="profile-info-value">
                        <?php echo $record['data'][0]->label_department; ?>
                     </div>
                  </div>
               </div>
			   <?php 
			   
			   } 
			   
			   if($management['numRows'] > 0){ ?>
               <div class="table-header">
                  Management
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name">Management Name</div>
                     <div class="profile-info-value">
                        <?php echo (isset($management['data'][0]->management_name)) ? $management['data'][0]->management_name:''; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Management Type</div>
                     <div class="profile-info-value">
                        <?php if(isset($management['data'][0]->managementtype_artist) && $management['data'][0]->managementtype_artist==1) { echo 'Artist'; echo '<br />'; } ?>
                        <?php if(isset($management['data'][0]->managementtype_tour) && $management['data'][0]->managementtype_tour==1) { echo 'Tour'; echo '<br />'; } ?>
                        <?php if(isset($management['data'][0]->managementtype_personal) && $management['data'][0]->managementtype_personal==1) { echo 'Personal'; echo '<br />'; } ?>
                        <?php if(isset($management['data'][0]->managementtype_finance) && $management['data'][0]->managementtype_finance==1) { echo 'Finance/Accounting'; echo '<br />'; } ?>
                     </div>
                  </div>
               </div>
			   <?php
				}
			   if($clothing['numRows'] > 0){

			   ?>
               <div class="table-header">
                  Clothing/Apparel Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name">Clothing/Apparel Name</div>
                     <div class="profile-info-value">
                        <?php echo $clothing['data'][0]->clothing_name; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Clothing/Apparel Department</div>
                     <div class="profile-info-value">
                        <?php echo $clothing['data'][0]->clothing_department; ?>
                     </div>
                  </div>
               </div>
			   <?php
			   }
				if($promoter['numRows'] > 0){
			   ?>
               <div class="table-header">
                  Promoter Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name">Promotion Company Name</div>
                     <div class="profile-info-value">
                        <?php echo $promoter['data'][0]->promoter_name; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Type</div>
                     <div class="profile-info-value">
                        <?php if(isset($promoter['data'][0]->promotertype_indy) && $promoter['data'][0]->promotertype_indy==1) { echo 'Indy Music Promotion'; echo '<br />'; } ?> 
                        <?php if(isset($promoter['data'][0]->promotertype_club) && $promoter['data'][0]->promotertype_club==1) { echo 'Club Promotion'; echo '<br />'; } ?> 
                        <?php if(isset($promoter['data'][0]->promotertype_event) && $promoter['data'][0]->promotertype_event==1) { echo 'Special Events'; echo '<br />'; } ?> 
                        <?php if(isset($promoter['data'][0]->promotertype_street) && $promoter['data'][0]->promotertype_street==1) { echo 'Street Promotion'; echo '<br />'; } ?> 
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Department</div>
                     <div class="profile-info-value">
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'executive')==0) { echo 'Executive'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'promotions')==0) { echo 'Promotions'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'product_manager_or_marketing')==0) { echo 'Product Manager/Marketing'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'sales')==0) { echo 'Sales'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'publicity')==0) { echo 'Publicity'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'new_media_or_internet')==0) { echo 'New Media/Internet'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'legal_or_business_affairs')==0) { echo 'Legal/Business Affairs'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'distribution')==0) { echo 'Distribution'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'international')==0) { echo 'International'; } ?> 
                        <?php if(isset($promoter['data'][0]->promoter_department) && strcmp($promoter['data'][0]->promoter_department,'i.t._or_tech')==0) { echo 'I.T./Tech'; } ?> 
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Company Website</div>
                     <div class="profile-info-value">
                        <?php echo $promoter['data'][0]->promoter_website; ?>
                     </div>
                  </div>
               </div>
			   <?php
			   
				}
				if($special['numRows'] > 0){
			   ?>
               <div class="table-header">
                  Special Services Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name">Type</div>
                     <div class="profile-info-value">
                        <?php if(isset($special['data'][0]->servicestype_corporate) && $special['data'][0]->servicestype_corporate==1) { echo 'Corporate'; echo '<br />'; } ?> 
                        <?php if(isset($special['data'][0]->servicestype_graphicdesign) && $special['data'][0]->servicestype_graphicdesign==1) { echo 'Graphic Design';  echo '<br />'; } ?> 
                        <?php if(isset($special['data'][0]->servicestype_webdesign) && $special['data'][0]->servicestype_webdesign==1) { echo 'Web Design'; echo '<br />'; } ?> 
                        <?php if(isset($special['data'][0]->servicestype_other) && $special['data'][0]->servicestype_other==1) { echo 'Other'; echo '<br />'; } ?> 
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Company Name</div>
                     <div class="profile-info-value">
                        <?php echo $special['data'][0]->services_name; ?>
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Company Website</div>
                     <div class="profile-info-value">
                        <?php echo $special['data'][0]->services_website; ?>
                     </div>
                  </div>
               </div>
			   
			   <?php 
				}
				
				if($production['numRows'] > 0){
			   ?>
			   
               <div class="table-header">
                  Production/Talent Information
               </div>
               <div class="profile-user-info profile-user-info-striped">
                  <div class="profile-info-row">
                     <div class="profile-info-name">Type</div>
                     <div class="profile-info-value">
                        <?php if(isset($production['data'][0]->productiontype_artist) && $production['data'][0]->productiontype_artist==1) { echo 'Artist'; echo '<br />'; } ?> 
                        <?php if(isset($production['data'][0]->productiontype_producer) && $production['data'][0]->productiontype_producer==1) { echo 'Producer';  echo '<br />'; } ?> 
                        <?php if(isset($production['data'][0]->productiontype_choreographer) && $production['data'][0]->productiontype_choreographer==1) { echo 'Choregrapher'; echo '<br />'; } ?> 
                        <?php if(isset($production['data'][0]->productiontype_sound) && $production['data'][0]->productiontype_sound==1) { echo 'Sound Engineer'; echo '<br />'; } ?> 
                     </div>
                  </div>
                  <div class="profile-info-row">
                     <div class="profile-info-name">Company Name</div>
                     <div class="profile-info-value">
                        <?php echo $production['data'][0]->production_name; ?>
                     </div>
                  </div>
               </div>
				<?php } ?>
            </div>
            <!--final-->
         </div>
         <!-- /.span -->
      </div>
      <!-- /.row -->
      <div class="hr hr-18 dotted hr-double"></div>
      <!-- PAGE CONTENT ENDS -->
   </div>
   <!-- /.col -->
</div>
<!-- /.row -->	
    <?php }else{ echo "Member doesn't exists.";}?>
		</div><!-- /.page-content -->
	</div>
</div>

@endsection           
	