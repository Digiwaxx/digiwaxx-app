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
                    <h2 class="text-center">{{ __('Create a Client Account') }}</h2>
                    <p class="text-center areg">{{ __('Already registered?') }} <a href="{{ url('login') }}">{{ __('Click here to log in') }}</a>  </p>
                </div>

              </div>

              

              <div class="modal-body">

              	<div class="desc">

                	<!--<p>

                THIS APPLICATION IS EXCLUSIVELY FOR INDIVIDUALS OR BUSINESSES THAT ARE INTERESTED IN <strong>SENDING</strong> MUSIC THROUGH THE DIGITAL WAXX SERVICE. THERE IS A FEE FOR THIS SERVICE. IF YOU ARE A DJ/PD/MD OR OTHER INDUSTRY TASTEMAKER INTERESTED IN <strong>RECEIVING</strong> MUSIC TO REVIEW AND DOWNLOAD, PLEASE REGISTER FOR MEMBER ACCOUNT BY <a href="<?php // echo base_url('Member_registration_step1'); ?>">CLICKING HERE</a>.

                	</p>-->

                </div>


		<form action="" method="post" id="registrationForm" autocomplete="off">
			@csrf
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group"> <span class="man"></span>

                        <input name="company" id="company"  class="form-control input"  size="20" placeholder="{{ __('Name/Company name') }}" type="text" value="{{ Session::get('sess-client-company') }}">

                    </div>
                </div>
                 <div class="col-md-6 col-sm-12">
                     <div class="form-group"> <span class="man"></span>

                        <input name="name" id="name"  class="form-control input"  size="20" placeholder="{{ __('Billing contact (First/Last name)') }}" type="text" value="{{ Session::get('sess-client-name') }}">

                    </div>
                 </div>
            </div>
                
                

				
				<div class="form-group mb-4"> <span class="man"></span>

				           <select id="continent" name="continent" onchange="getCountries(this.value)" class="selectpicker form-control">
							
					
                           <option value="">{{ __('What continent do you live in?') }}</option>
							@foreach($continents['data'] as $continent)
							
							<option value="{{ $continent->continentId }}">{{ $continent->continent }}</option>
							@endforeach
							</select>

                </div>
				
				 <div class="form-group mb-4"> <span class="man"></span>
				  <select id="country" name="country" onchange="getStates(this.value)" class="selectpicker form-control">
                        <option value="">{{ __('What country do you live in?') }}</option>
				  </select>
                </div>
               

              <div class="form-group mb-4"> <span class="man"></span>			
              <select id="state" name="state" class="selectpicker form-control">
                    <option value="">{{ __('What state/providence do you live in?') }}</option>
			  </select>
			  </div>
				
<!--
				<div id="locationField" class="form-group">

      <input id="autocomplete" class="form-control input" placeholder="Enter your address"   onFocus="geolocate()" type="text" />

	</div>-->

                

              

                

               <!-- <div class="form-group"> <span class="man"></span>
				<input class="form-control input"  id="country" name="country" placeholder="Country" />
                </div>

                <div class="form-group"> <span class="man"></span>
            <input class="form-control input"  id="administrative_area_level_1" name="state" placeholder="What state/providence do you live in"  />	
                </div>
-->
             <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-4"> <span class="man"></span>
                        <input name="city" id="city"  class="form-control input"  size="20" placeholder="{{ __('City') }}" type="text" value="{{ Session::get('sess-client-city') }}">
                    </div>
                </div>
                 <div class="col-md-6 col-sm-12">
                      <div class="form-group"> <span class="man"></span>
                        <input name="zip" id="postal_code"  class="form-control input"  size="20" placeholder="{{ __('Zip') }}" type="text" value="{{ Session::get('sess-client-zip') }}">
                    </div>
                 </div>
            </div>
  <div class="form-group"> 

                    <input name="address1" id="street_number"  class="form-control input"  size="20" placeholder="{{ __('Address') }}" type="text" value="{{ Session::get('sess-client-address1') }}">

                </div>

                

          <div class="form-group">

                    <input name="address2" id="route"  class="form-control input"  size="20" placeholder="{{ __('Address Line 2') }}" type="text" value="{{ Session::get('sess-client-address2') }}">

                </div>
                

                

                <div class="btn-center">

                    <input name="addClient" class="login_btn btn btn-theme btn-gradient" value="{{ __('Continue') }}" type="submit">

                </div>

                </form>

                

              </div>

              

            </div>

            <!-- /.modal-content --> 

        

      </div>
</div>
</div>
</div>
        

        

     

        

     <!-- Register Block Ends -->       

	 

	 

	    <script>

		
function getCountries(continentId)

		{

		//$('#country').selectpicker('refresh');

		//document.getElementById('country').innerHTML = '<option value="">What country do you live in?</option>';

		//document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = '';

		

		document.getElementsByClassName('filter-option')[1].innerHTML = 'What country do you live in?';
		document.getElementsByClassName('filter-option')[2].innerHTML = 'What State do you live in?';

		// document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = liList;

		 

		 

		 $.ajax({url: "Client_registration_step1/getCountries?continentId="+continentId, success: function(result){

      

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

	}

	

	

	

	

		

		

	function getStates(country)

		{
			document.getElementsByClassName('filter-option')[2].innerHTML = 'What State do you live in?';

		//$('#country').selectpicker('refresh');

		//document.getElementById('country').innerHTML = '<option value="">What country do you live in?</option>';

		//document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = '';

		

		document.getElementsByClassName('filter-option')[1].innerHTML = 'What state/providence do you live in';

		// document.getElementsByClassName('dropdown-menu inner')[1].innerHTML = liList;

		 

		 

		 $.ajax({url: "Client_registration_step1/getStatesById?country="+country, success: function(result){

      

		 var obj = JSON.parse(result);

		 var count = obj.length; 

	     var liList = '';

		 var optionList = ''; //'<option value="">What country do you live in</option>';

		for (var i=0;i<count;i++) 

		 {

		 

		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].state+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';

		  

		   optionList += '<option value="'+obj[i].id+'">'+obj[i].state+'</option>';

		 

		 }

		

		 document.getElementsByClassName('dropdown-menu inner')[2].innerHTML = liList;

		 document.getElementById('state').innerHTML = optionList;

		 

    }});

	}

	

 // Wait for the DOM to be ready

$(function() {



 $("#registrationForm").validate();
 
 $.validator.addMethod("alphabetsnspace", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    });

 

 

 

  $("#company").rules("add", {

         required:true,

         messages: {

                required: "Please enter company."

         }

      });

 

 

 $("#name").rules("add", {

         required:true,

         messages: {

                required: "Please enter name."

         }

      });

	  
 $("#continent").rules("add", {

         required:true,

         messages: {

                required: "Please enter continent."

         }

      });

 /*$("#address1").rules("add", {

         required:true,

         messages: {

                required: "Please enter address line1."

         }

      });

*/



$("#country").rules("add", {

         required:true,

         messages: {

                required: "Please enter country."

         }

      });
	  
$("#state").rules("add", {

         required:true,

         messages: {

                required: "Please enter state."

         }

      });
      
      
      $("#street_number").rules("add", {

         required:true,

         messages: {

                required: "Please enter Address."

         }

      });

	  

	  $("#city").rules("add", {

         required:true,
		 alphabetsnspace:true,

         messages: {

                required: "Please enter city.",
				alphabetsnspace: "Enter only alphabets"

         }

      });

	  

	  $("#administrative_area_level_1").rules("add", {

         required:true,

         messages: {

                required: "Please enter state."

         }

      });

	  $("#postal_code").rules("add", {

         required:true,

		 minlength:5,

		 maxlength:6,

		// number: true,

         messages: {

                required: "Please enter zipcode."

         }

      });

	

});

	

	</script> 

	

	<script>

      // This example displays an address form, using the autocomplete feature

      // of the Google Places API to help users fill in the information.



      // This example requires the Places library. Include the libraries=places

      // parameter when you first load the API. For example:

      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">



      var placeSearch, autocomplete;

      var componentForm = {

        street_number: 'short_name',

        // route: 'long_name',

        locality: 'long_name',

        administrative_area_level_1: 'short_name',

        country: 'long_name',

        postal_code: 'short_name'

      };



      function initAutocomplete() {

        // Create the autocomplete object, restricting the search to geographical

        // location types.

        autocomplete = new google.maps.places.Autocomplete(

            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),

            {types: ['geocode']});



        // When the user selects an address from the dropdown, populate the address

        // fields in the form.

        autocomplete.addListener('place_changed', fillInAddress);

      }



      function fillInAddress() {

        // Get the place details from the autocomplete object.

        var place = autocomplete.getPlace();



        for (var component in componentForm) {

          document.getElementById(component).value = '';

          document.getElementById(component).disabled = false;

        }



        // Get each component of the address from the place details

        // and fill the corresponding field on the form.

        for (var i = 0; i < place.address_components.length; i++) {

          var addressType = place.address_components[i].types[0];

          if (componentForm[addressType]) {

            var val = place.address_components[i][componentForm[addressType]];

            document.getElementById(addressType).value = val;

          }

        }

      }



      // Bias the autocomplete object to the user's geographical location,

      // as supplied by the browser's 'navigator.geolocation' object.

      function geolocate() {

        if (navigator.geolocation) {

          navigator.geolocation.getCurrentPosition(function(position) {

            var geolocation = {

              lat: position.coords.latitude,

              lng: position.coords.longitude

            };

            var circle = new google.maps.Circle({

              center: geolocation,

              radius: position.coords.accuracy

            });

            autocomplete.setBounds(circle.getBounds());

          });

        }

      }
      
      $(document).on('change', '.selectpicker', function() {
        
        //   $(".form-control").each(function( index ) {
              console.log("hello");
              var aa =$(this).parent();
              console.log(aa);
              
              
            //   if((aa).find('.open')){
                 $(".form-control").removeClass('open');
            //   }
            // });
          
      });

    </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY_SLy0uErJx20SdOf_ExP87XZZGvN7bw&libraries=places&callback=initAutocomplete"

        async defer></script>
@endsection