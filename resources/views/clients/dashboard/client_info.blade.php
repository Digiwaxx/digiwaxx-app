

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>My Info</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  <div class="myinfo-block f-block">
                    <div class="row mb-3 align-items-center">
                        <div class="col-5">                        
                     </div>
                     <div class="col-7">
                         <a href="<?php echo url('Client_edit_profile'); ?>" class="btn btn-alt pull-right"> Edit My Profile </a>
                     </div>
                 </div>
                     <!--   <div class="myinfo-block f-block mCustomScrollbar" style="height:920px; overflow:auto;">
                        -->                         
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                 <?php
                                    $clientImage_get = Session::get('clientImage');
                                    if(is_numeric($clientImage_get)){
										     $imgSrc= url('/pCloudImgDownload.php?fileID='.$clientImage_get);
									 }
                                    else if($clientImage['numRows']>0)
                                    
                                    {
                                       $imgSrc = 'client_images/'.Session::get('clientId').'/'.$clientImage['data'][0]->image;
                                    
                                    }
                                    
                                    else
                                    
                                    {
                                       $imgSrc = 'assets/img/profile-pic.png';
                                    
                                    }
                                    ?>
                                 <img src="<?php echo $imgSrc; ?>" class="pimg mb-0">
                           </div>
                           <div class="col-lg-5 col-md-6 col-sm-5 col-auto">
                              <div class="form-group">
                                 <input name="reg"  class="form-control input" disabled="disabled"  size="20" placeholder="Username" type="text" value="<?php echo $clientInfo['data'][0]->uname; ?>" />
                              </div>
                           </div>
                           <div class="col-lg-4 col-md-3 col-sm-4">
                              <!-- <div class="form-group">
                                 <label class="btn pfile">
                                 
                                     Upload Profile Pic <input style="display: none;" type="file">
                                 
                                 </label>
                                 
                                 </div>-->
                           </div>
                           
                        </div>
                        <div class="form-group">
                           <input name="reg"  class="form-control input" disabled="disabled"  size="20" placeholder="Company Name" type="text" value="<?php echo urldecode($clientInfo['data'][0]->name); ?>">
                        </div>
                        <div class="form-group">
                           <input name="reg"  class="form-control input" disabled="disabled" size="20" placeholder="Company Billing Contact (First/Last Name)" type="text" value="<?php echo urldecode($clientInfo['data'][0]->ccontact); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled"  class="form-control input"  size="20" placeholder="Address (line 1)" type="text" value="<?php echo urldecode($clientInfo['data'][0]->address1); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled"  class="form-control input"  size="20" placeholder="Address (line 2)" type="text" value="<?php echo urldecode($clientInfo['data'][0]->address2); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled"  class="form-control input"  size="20" placeholder="City" type="text" value="<?php echo urldecode($clientInfo['data'][0]->city); ?>" />
                        </div>
                        <div class="row">
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="form-group">
                                 <input name="reg"  disabled="disabled" class="form-control input"  size="20" placeholder="State" type="text" value="<?php echo urldecode($clientInfo['data'][0]->state); ?>" />
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="form-group">
                                 <input name="reg"  disabled="disabled" class="form-control input"  size="20" placeholder="Zip" type="text" value="<?php echo urldecode($clientInfo['data'][0]->zip); ?>" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <input name="reg"  class="form-control input"  size="20" disabled="disabled" placeholder="Billing Contact E-mail Address" type="text" value="<?php echo urldecode($clientInfo['data'][0]->email); ?>" />
                        </div>
                        <div class="row">
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="form-group">
                                 <input name="reg"  class="form-control input"  size="20" disabled="disabled" placeholder="Phone" type="text" value="<?php echo urldecode($clientInfo['data'][0]->phone); ?>" />
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-6 col-sm-6">
                              <div class="form-group">
                                 <input name="reg"  disabled="disabled" class="form-control input"  size="20" placeholder="Mobile" type="text" value="<?php echo urldecode($clientInfo['data'][0]->mobile); ?>" />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled" class="form-control input"  size="20" placeholder="Company Website Address" type="text" value="<?php echo urldecode($clientInfo['data'][0]->website); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled" class="form-control input"  size="20" placeholder="Company Website Address" type="text" value="<?php if(!empty($socialInfo['data'][0]->facebook)) echo urldecode($socialInfo['data'][0]->facebook); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled" class="form-control input"  size="20" placeholder="Company Website Address" type="text" value="<?php if(!empty($socialInfo['data'][0]->twitter)) echo urldecode($socialInfo['data'][0]->twitter); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled" class="form-control input"  size="20" placeholder="Company Website Address" type="text" value="<?php if(!empty($socialInfo['data'][0]->instagram)) echo urldecode($socialInfo['data'][0]->instagram); ?>" />
                        </div>
                        <div class="form-group">
                           <input name="reg" disabled="disabled" class="form-control input"  size="20" placeholder="Company Website Address" type="text" value="<?php if(!empty($socialInfo['data'][0]->linkedin)) echo urldecode($socialInfo['data'][0]->linkedin); ?>" />
                        </div>
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
<!--script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
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
<!-- /21741445840/336x280 >
<div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div-->
@endsection

