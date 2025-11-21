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
                    <h2 class="text-center">{{ __('Update Your Details') }}</h2>
                    
                </div>
                   
                   
               </div>
               <div class="modal-body">
                   <form action="" method="post" id="registrationForm" autocomplete="off">
				   @csrf
                       <div class="form-group">
                           <input name="address1" id="address1" class="form-control input" size="20" placeholder="{{ __('Address') }}" type="text" value="{{ Session::get('sess-member-address1') }}">
                       </div>
                       <input type="hidden" name="continent" value="0">
                       <div class="form-group mb-4">
                           <!--<span class="man"></span>-->                         
                           <select id="country" name="country" class="selectpicker form-control">
                               <option value="">{{ __('What country do you live in?') }}</option>
                                @foreach($countries as $country)
								<option value="{{ $country->countryId }}">{{ $country->country }}</option>
								@endforeach
                           </select>
                       </div>
                       <div class="form-group">
                           <!--<span class="man"></span>-->
                           <input name="state" id="state" class="form-control input" size="20" placeholder="{{ __('What state/providence do you live in?') }}" type="text" value="{{ Session::get('sess-member-state') }}">
                       </div>
                       <div class="row">
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <!--<span class="man"></span>-->
                                   <input name="city" id="city" class="form-control input" size="20" placeholder="{{ __('What city/town do you live in?') }}" type="text" value="{{ Session::get('sess-member-city') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <!--<span class="man"></span>-->
                                   <input name="postalCode" id="postalCode" class="form-control input" size="20" placeholder="{{ __('Postal Code') }}" type="text" value="{{ Session::get('sess-member-postalCode') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                   <input name="facebook" id="facebook" class="form-control input" size="20" placeholder="{{ __('Facebook profile') }}" type="text" value="{{ Session::get('sess-member-facebook') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="twitter" id="twitter" class="form-control input" size="20" placeholder="{{ __('Twitter profile') }}" type="text" value="{{ Session::get('sess-member-twitter') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="instagram" id="instagram" class="form-control input" size="20" placeholder="{{ __('Instagram profile') }}" type="text" value="{{ Session::get('sess-member-instagram') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="linkedin" id="linkedin" class="form-control input" size="20" placeholder="{{ __('LinkedIn profile') }}" type="text" value="{{ Session::get('sess-member-linkedin') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="snapchat" id="snapchat" class="form-control input" size="20" placeholder="{{ __('Snapchat profile') }}" type="text" value="{{ Session::get('sess-member-snapchat') }}">
                               </div>
                           </div> 
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="tiktok" id="tiktok" class="form-control input" size="20" placeholder="{{ __('TikTok profile') }}" type="text" value="{{ Session::get('sess-member-tiktok') }}">
                               </div>
                           </div>   
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="mixcloud" id="mixcloud" class="form-control input" size="20" placeholder="{{ __('Mixcloud profile') }}" type="text" value="{{ Session::get('sess-member-mixcloud') }}">
                               </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="reddit" id="reddit" class="form-control input" size="20" placeholder="{{ __('Reddit profile') }}" type="text" value="{{ Session::get('sess-member-reddit') }}">
                               </div>
                           </div>                             
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="triller" id="triller" class="form-control input" size="20" placeholder="{{ __('Triller profile') }}" type="text" value="{{ Session::get('sess-member-triller') }}">
                               </div>
                           </div> 
                           <div class="col-md-6 col-sm-12">
                               <div class="form-group">
                                   <input name="twitch" id="twitch" class="form-control input" size="20" placeholder="{{ __('Twitch profile') }}" type="text" value="{{ Session::get('sess-member-twitch') }}">
                               </div>
                           </div>                                                                                                                                  
                       </div>
                       <div class="btn-center">
                           <input name="addMember2" class="login_btn btn btn-theme btn-gradient" value="{{ __('Continue') }}" type="submit">
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
       <script>
           /*function getCountries(continentId)
{
//$('#country').selectpicker('refresh');
//document.getElementById('country').innerHTML = '<option value="">What country do you live in?</option>';
//document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = '';
document.getElementsByClassName('filter-option')[1].innerHTML = 'What country do you live in?';
// document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = liList;
$.ajax({url: "Member_registration_step2/getCountries?continentId="+continentId, success: function(result){
var obj = JSON.parse(result);
var count = obj.length; 
var liList = '';
var optionList = ''; //'<option value="">What country do you live in</option>';
for (var i=0;i<count;i++) 
{
liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
optionList += '<option value="'+obj[i].id+'">'+obj[i].name+'</option>';
}
document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = liList;
document.getElementById('country').innerHTML = optionList;
}});
}*/
           // Wait for the DOM to be ready
           $(function() {
               $("#registrationForm").validate();
               $.validator.addMethod("alphabetsnspace", function(value, element) {
                   return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
               });
               $("#address1").rules("add", {
               required:true,
               messages: {
                   required: "Please add Address."
               }
               });
               $("#country").rules("add", {
                   required: true,
                   messages: {
                       required: "Please select country."
                   }
               });
                $("#state").rules("add", {
                        required:true,
                       messages: {
                                required: "Please enter state."
                        }
                });
               $("#city").rules("add", {
                   required: true,
                   alphabetsnspace: true,
                   messages: {
                       required: "Please enter city.",
                       alphabetsnspace: "Enter only alphabets"
                   }
               });
               $("#postalCode").rules("add", {
               required:true,
               minlength:5,
               maxlength:6,
               //number: true,
               messages: {
                   required: "Please enter zipcode."
               }
               });
           });
       </script>
@endsection