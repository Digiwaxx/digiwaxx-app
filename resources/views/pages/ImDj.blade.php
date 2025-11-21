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
          <div class="col-md-4">
            <div class="s-block">
						<h1 style="color:#db378f;">LOGIN</h1>
						<p>Login into your Member Account</p>
            <a href="{{ url('login') }}" class="more link-active" style="text-decoration: none;">Click here to login</a>
						</div>
          </div>
          <div class="col-md-4">
            <div class="s-block">
          	<h1 style="color:#db378f;">DIGIWAXX MUSIC SERVICE</h1>		
						<p>Download new music daily from major and indie record labels.</p> 
            <a href="{{ $pageLinks[0]->linkHref }}" target="_parent" class="more link-active" style="text-decoration: none;">Click here to Sign Up</a> 
          </div>
          </div>
          <div class="col-md-4">
            <div class="s-block">
          		<h1 style="color:#db378f;">DIGITAL RECORD POOL</h1>
							<p>A tool exclusively for the DJs allows users to browse through and download unlimited titles from our extensive catalogue of new and exclusive music.</p>
              <a href="javascript:void(0);" target="_blank" class="more link-active" style="text-decoration: none;">Click here for More Info</a>
            </div>
          </div>
          </div>
      </div>
  </div>
</section>
<!-- </div> -->
@endsection