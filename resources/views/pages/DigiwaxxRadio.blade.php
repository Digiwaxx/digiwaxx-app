@extends('layouts.app')

@section('content')

<section class="top-banner banner-radio">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
         <div class="banner-text">
            <?php echo stripslashes(urldecode($bannerText[0]->bannerText)); ?>
          </div>
       </div>
      </div>
    </div>     
   </section>
        
<section class="content-area">
	 <div class="container">
		  <div class="contact-section">
			<div class="row">
			  <div class="col-md-6 col-sm-12 text-center mx-auto">
				<h3>Under Construction</h3>
				<div class="construction">
					<img src="{{URL::asset('public/images/path/road-barrier.png')}}" alt="construction" class="img-fluid">
				</div>
			  </div>
		  </div>
	  </div>
	</div>
</section>
        
       <!-- <div class="digi-radio-block" style="height:1000px;">
       		<h1 style="margin-top:200px; font-size:60px; text-align:center; ">Under Construction</h1>
        </div> -->
@endsection