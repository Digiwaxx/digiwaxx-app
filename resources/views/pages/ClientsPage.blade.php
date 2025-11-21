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
<!--section class="top-banner banner-help-page">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
         <div class="banner-text">
            <?php //echo stripslashes(urldecode($bannerText[0]->bannerText)); ?>
          </div>
       </div>
      </div>
    </div>     
   </section-->

<!-- <div class="hp header-bottom">
	<div class="container">
		<p>Terms and Conditions</p>		
	</div>
</div> -->

<section class="content-area">
     <div class="container">
      <div class="contact-section">
        <div class="row">
          <div class="col-md-10 col-sm-12 mx-auto">
			<?php echo stripslashes(urldecode($content[0]->page_content)); ?>
          	</div>
          </div>
      </div>
  </div>
</section>
<!-- </div> -->
@endsection