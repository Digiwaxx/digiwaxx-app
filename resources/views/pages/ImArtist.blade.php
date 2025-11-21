@extends('layouts.app')
<?php if(!empty($banner[0]->banner_image)){ ?>
<style>
	.banner-help-page{
		background-image: url('<?php echo url('public/images/'.$banner[0]->banner_image); ?>');
		padding: 165px 0px;
	}
</style>
<?php } ?>
@section('content')

<section class="content-area">
     <div class="container">
      <div class="our-services">
        <div class="row">
          <div class="col-md-6 s-block mx-auto">
							<a href="{{ url('login') }}" class="more" style="text-decoration: none;"><h1 style="color:#db378f;">DIGITAL WAXX SERVICE For Artist/Clients</h1></a>
							<p>A purely web-based solution, the Digital Waxx Service allows us to service your music directly to DJs and obtain instantaneous feedback</p>	
          </div>
          <div class="col-md-6 s-block mx-auto">
          	<a href="https://www.digiwaxxmedia.com/artist-marketing-services" target="_parent" class="more" style="text-decoration: none;"><h1 style="color:#db378f;">Artist Marketing Services</h1></a>			
						<p>Digiwaxx is an agency that converts years of experience at the forefront of cultural creativity into a dynamic hub of information and resources.  <a href="http://www.digiwaxxmedia.com" target="_parent" class="more"> &gt; More info </a></p> 
          </div>
          </div>
      </div>
  </div>
</section>
<!-- </div> -->
@endsection