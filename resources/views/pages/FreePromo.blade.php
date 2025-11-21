@extends('layouts.app')

@section('content')



<section class="top-banner banner-contact">
    <div class="container">
      <div class="row">
        <div class="col-12">
         <div class="banner-text">
            <h2>Subscribe to our mailing list</h2>
          </div>
       </div>
      </div>
    </div>     
   </section>


 <!---content-section-->
   <section class="content-area">
     <div class="container">
      <div class="contact-section">
        <div class="row">
          <div class="col-md-8 col-sm-12 mx-auto">
            <div class="subscribe-form">
              <h3 class="text-md-center">Fill the form for Subscribe</h3>
              
			<form action="https://theblastbydigiwaxx.us1.list-manage.com/subscribe/post?u=e325f3a65ed75749cc95845d3&amp;id=bf21d55a8b" id="mc-embedded-subscribe-form" method="post" name="mc-embedded-subscribe-form" target="_blank">
				<div class="indicates-required">&nbsp;</div>

				<div class="indicates-required">&nbsp;</div>
                <div class="form-group">
                  <input name="MMERGE3" type="text" value="" class="form-control" placeholder="Artist Name" required>
                </div>
                <div class="row">
                	<div class="col-md-6 col-sm-12">
                		<div class="form-group">
		                  <input name="FNAME" type="text" class="form-control" value="" placeholder="First Name">
		                </div>
                	</div>
                	<div class="col-md-6 col-sm-12">
                		<div class="form-group">
		                	<input name="LNAME" type="text" class="form-control" value="" placeholder="Last Name">
		                </div>
                	</div>
                </div>               
                
                <div class="form-group">
                	<input name="EMAIL" type="email" class="form-control" value="" placeholder="Email Address">
                </div>
                <div class="form-group select-field">
                	<label class="cust-label">Select Your Music Genre</label><br>
                  <div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][2]" type="checkbox" id="2" value="2">
					  <label class="form-check-label" for="2">Hip Hop</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][4]" type="checkbox" id="4" value="4">
					  <label class="form-check-label" for="4">Afro Beats</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][8]" type="checkbox" id="8" value="8">
					  <label class="form-check-label" for="8">R&B</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][16]" type="checkbox" id="16" value="16">
					  <label class="form-check-label" for="16">Reggae</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][32]" type="checkbox" id="32" value="32">
					  <label class="form-check-label" for="32">Dance Hall</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][64]" type="checkbox" id="64" value="64">
					  <label class="form-check-label" for="64">Pop</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][128]" type="checkbox" id="128" value="128">
					  <label class="form-check-label" for="128">Dance</label>
					</div>
					<div class="form-check form-check-inline">
					  <input class="form-check-input" name="group[33][256]" type="checkbox" id="256" value="256">
					  <label class="form-check-label" for="256">EDM</label>
					</div>
                </div>
                <div class="clear" id="mce-responses">
					<div class="response" id="mce-error-response" style="display:none">&nbsp;</div>

					<div class="response" id="mce-success-response" style="display:none">&nbsp;</div>
					</div>
                <div class="btn-submit text-center">
                <button class="btn btn-theme btn-gradient" name="subscribe" type="submit" value="Subscribe">Subscribe</button>
              </div>
              </form>
                        
              
            </div>
          </div>                  
        </div>
      </div>

      
       
     </div> 
       
   </section>


<!-- <div class="digi-radio-block" style="height:1000px;">

	<div class="col-sm-offset-2 col-sm-8  page_content">
		<div id="mc_embed_signup">
			<form action="https://theblastbydigiwaxx.us1.list-manage.com/subscribe/post?u=e325f3a65ed75749cc95845d3&amp;id=bf21d55a8b" id="mc-embedded-subscribe-form" method="post" name="mc-embedded-subscribe-form" target="_blank">
			<div id="mc_embed_signup_scroll">
			<h1>&nbsp;</h1>

			<h1>Subscribe to our mailing list</h1>

			<div class="indicates-required">&nbsp;</div>

			<div class="indicates-required">&nbsp;</div>

			<div class="mc-field-group"><span style="font-size:18px">Artist Name&nbsp;&nbsp;<input name="MMERGE3" type="text" value="" required>&nbsp;(required)&nbsp;</span></div>

			<div class="mc-field-group">&nbsp;</div>

			<div class="mc-field-group"><span style="font-size:18px">First Name <input name="FNAME" type="text" value=""></span></div>

			<div class="mc-field-group">&nbsp;</div>

			<div class="mc-field-group"><span style="font-size:18px">Last Name <input name="LNAME" type="text" value=""></span></div>

			<div class="mc-field-group">&nbsp;</div>

			<div class="mc-field-group"><span style="font-size:18px">Email Address&nbsp;<input name="EMAIL" type="email" value=""></span>&nbsp;&nbsp;<span style="font-size:18px">(required)&nbsp;</span></div>

			<div class="mc-field-group">&nbsp;</div>

			<div class="input-group mc-field-group"><span style="font-size:18px"><strong>Select Your Music Genre </strong></span>

			<ul>
			<li><span style="font-size:18px"><input name="group[33][2]" type="checkbox" value="2">Hip Hop</span></li>
			<li><span style="font-size:18px"><input name="group[33][4]" type="checkbox" value="4">Afro Beats</span></li>
			<li><span style="font-size:18px"><input name="group[33][8]" type="checkbox" value="8">R&amp;B</span></li>
			<li><span style="font-size:18px"><input name="group[33][16]" type="checkbox" value="16">Reggae</span></li>
			<li><span style="font-size:18px"><input name="group[33][32]" type="checkbox" value="32">Dance Hall</span></li>
			<li><span style="font-size:18px"><input name="group[33][64]" type="checkbox" value="64">Pop</span></li>
			<li><span style="font-size:18px"><input name="group[33][128]" type="checkbox" value="128">Dance</span></li>
			<li><span style="font-size:18px"><input name="group[33][256]" type="checkbox" value="256">EDM</span></li>
			</ul>

			<p>&nbsp;</p>
			</div>

			<div class="clear" id="mce-responses">
			<div class="response" id="mce-error-response" style="display:none">&nbsp;</div>

			<div class="response" id="mce-success-response" style="display:none">&nbsp;</div>
			</div>
			<span style="font-size:18px"> --><!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups--><!-- </span>

			<div style="left:-5000px; position:absolute"><span style="font-size:18px"><input name="b_e325f3a65ed75749cc95845d3_bf21d55a8b" type="text" value=""></span></div>

			<div class="clear"><span style="font-size:18px"><span style="color:purple"><input name="subscribe" type="submit" value="Subscribe"></span></span></div>

			<div class="clear">&nbsp;</div>

			<div class="clear">&nbsp;</div>
			</div>
			</form>
		</div>
	</div>
</div> -->
@endsection