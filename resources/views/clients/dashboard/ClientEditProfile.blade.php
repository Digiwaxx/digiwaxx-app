

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h5>MY INFO</h5>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  <div class="client-middle-block">
                     <form action="" id="editProfile" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="myinfo-block f-block">
                           <h1>MY INFO</h1>
                           <?php if(isset($alert_class)) 
                              { ?>
                           <div class="<?php echo $alert_class; ?>" style="margin-bottom:40px;">
                              <p><?php echo $alert_message; ?></p>
                           </div>
                           <?php } ?>
                           
                              <div class="row mb-3 align-items-center">
                                 <!--   <div class="col-lg-5 col-md-6 col-sm-5">
                                    <div class="form-group">
                                    <input name="reg"  class="form-control input"  size="20" placeholder="Username" type="text" value="<?php echo $clientInfo['data'][0]->uname; ?>" />
                                    </div>
                                    </div>-->
                                 
                                 <div class="col-auto">
                                       <?php
										  $sessClientID = Session::get('clientId');
										   $clientImage_get = Session::get('clientImage');
										  if(is_numeric($clientImage_get)){
										     $imgSrc= url('/pCloudImgDownload.php?fileID='.$clientImage_get);
										  }
						                  else if($clientImage['numRows']>0 && isset($clientImage['data'][0]->image) && file_exists(base_path('client_images/'.$sessClientID.'/'.$clientImage['data'][0]->image))){
                                             $imgSrc = asset('client_images/'.$sessClientID.'/'.$clientImage['data'][0]->image);                                          
                                          }else{                                          
                                             $imgSrc = asset('public/images/profile-pic.png');                                          
                                          }                                          
                                          ?>
                                       <img src="<?php echo $imgSrc; ?>" id="pimg" class="pimg mb-0">                                    
                                 </div>
                                 <div class="col-lg-9 col-md-3 col-sm-9 col-auto">
                                    <div class="form-group">
                                       <label class="btn pfile mb-0">
                                       Upload Profile Pic <input style="display: none;" type="file" name="profileImage" id="profileImage" />
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <input name="company" id="company"  class="form-control input"   size="20" placeholder="Company Name" type="text" value="<?php echo urldecode($clientInfo['data'][0]->name); ?>" required>
                              </div>
                              <div class="form-group">
                                 <input name="name" id="name"  class="form-control input"  size="20" placeholder="Company Billing Contact (First/Last Name)" type="text" 
                                    value="<?php echo urldecode($clientInfo['data'][0]->ccontact); ?>" required />
                              </div>
                              <div class="form-group">
                                 <input name="address1" id="address1"    class="form-control input"  size="20" placeholder="Address (line 1)" type="text" value="<?php echo urldecode($clientInfo['data'][0]->address1); ?>" required />
                              </div>
                              <div class="form-group">
                                 <input name="address2" id="address2" class="form-control input"  size="20" placeholder="Address (line 2)" type="text" value="<?php echo urldecode($clientInfo['data'][0]->address2); ?>" />
                              </div>
                              <div class="form-group">
                                 <input name="city"  id="city"  class="form-control input"  size="20" placeholder="City" type="text" value="<?php echo urldecode($clientInfo['data'][0]->city); ?>" />
                              </div>
                              <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       <input name="state" id="state"    class="form-control input"  size="20" placeholder="State" type="text" value="<?php echo urldecode($clientInfo['data'][0]->state); ?>" />
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       <input name="zip" id="zip"   class="form-control input"  size="20" placeholder="Zip" type="text" value="<?php echo urldecode($clientInfo['data'][0]->zip); ?>" />
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <input name="email" id="email"  class="form-control input"  size="20" placeholder="Billing Contact E-mail Address" type="text" value="<?php echo urldecode($clientInfo['data'][0]->email); ?>" />
                              </div>
                              <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       <input name="phone" id="phone"  class="form-control input"  size="20" placeholder="Phone" type="text" value="<?php echo urldecode($clientInfo['data'][0]->phone); ?>" />
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       <input name="mobile" id="mobile"  class="form-control input"  size="20" placeholder="Mobile" type="text" value="<?php echo urldecode($clientInfo['data'][0]->mobile); ?>" />
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <input name="website" id="website"   class="form-control input"  size="20" placeholder="Company Website Address" type="text" value="<?php echo (!empty($clientInfo['data'][0]->website)) ? urldecode($clientInfo['data'][0]->website):''; ?>" />
                              </div>
                              <div class="form-group">
                                 <input name="facebook" class="form-control input"  size="20" placeholder="Facebook" type="url" value="<?php echo (!empty($socialInfo['data'][0]->facebook)) ? urldecode($socialInfo['data'][0]->facebook):''; ?>" />
                              </div>
                              <div class="form-group">
                                 <input name="twitter" class="form-control input"  size="20" placeholder="Twitter" type="url" value="<?php echo (!empty($socialInfo['data'][0]->twitter)) ? urldecode($socialInfo['data'][0]->twitter):''; ?>" />
                              </div>
                              <div class="form-group">
                                 <input name="instagram" class="form-control input"  size="20" placeholder="Instagram" type="url" value="<?php echo (!empty($socialInfo['data'][0]->instagram)) ? urldecode($socialInfo['data'][0]->instagram):''; ?>" />
                              </div>
                              <div class="form-group">
                                 <input name="linkedin" class="form-control input"  size="20" placeholder="Linkedin" type="url" value="<?php echo (!empty($socialInfo['data'][0]->linkedin)) ? urldecode($socialInfo['data'][0]->linkedin):''; ?>" />
                              </div>
                              <div class="form-group">
                                    <div class="checkbox-track-feedback">
                                       <label>
                                          <input value="1" type="checkbox" id="activate_track_review_feedback" name="activate_track_review_feedback" <?php echo (!empty($clientInfo['data'][0]->trackReviewEmailsActivated) && $clientInfo['data'][0]->trackReviewEmailsActivated == 1) ? 'checked':''; ?> >
                                          Can receive track review feedback emails?</label>
                                    </div>
                              </div>                              
                              <div class="form-group clearfix">
                                 <input name="updateClient" class="btn-gradient btn-theme btn pull-right" value="SUBMIT" type="submit">
                              </div>
                           
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
<script>
       function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                //$('#artWork + img').remove();
                // $('#artWork').after('<img src="'+e.target.result+'" width="450" height="300"/>');
                document.getElementById('pimg').style.width = '60px';
                document.getElementById('pimg').style.height = '60px';
                document.getElementById('pimg').src = e.target.result;
                //$('#uploadForm + embed').remove();
                //$('#uploadForm').after('<embed src="'+e.target.result+'" width="450" height="300">');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#profileImage").change(function() {
        filePreview(this);
    });
</script>
@endsection

