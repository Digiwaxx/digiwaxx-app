@extends('layouts.app')

  <?php 
  $ban_img='';
  if(is_numeric($banner[0]->pCloudFileID)){
      $ban_img= url('/pCloudImgDownload.php?fileID='.$banner[0]->pCloudFileID);
  }else{
      $ban_img=  url('public/images/'.$banner[0]->banner_image);
  }
  
  ?>

<style>

.banner-promote{
        <?php if(is_numeric($banner[0]->pCloudFileID)){?>
                 background-image: url(<?php echo $ban_img;?>);
      <?php }else{ ?>
                    background-image: url(<?php echo $ban_img;?>);
         <?php }?>
}
</style>
@section('content')
<!-- <div class="pyp header-bottom">

	<div class="container">                    

		<h1><strong>Start promoting your project today with one of our premium packages.</strong></h1>

		<p>&nbsp;</p>

	</div>

</div> --><!-- eof header-bottom -->

<section class="top-banner banner-promote">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
          <div class="banner-text">
            <?php echo $bannerText[0]->bannerText; ?>
          </div>         
       </div>
      </div>
    </div>     
   </section>

<!---content-section-->
   <section class="content-area">
     <div class="container">
      <div class="plan-section">
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <div class="plan-box basic">
              <h3>Basic</h3>
              <ul>
                <li>Music/Content serviced to our Global Member Network (email &amp; social blasts)</li>
                <li>Content posted on Digiwaxx Database</li>
                <li>Account Profile</li>
              </ul>
              <div class="btn-plan">
                <button class="btn btn-theme btn-gradient" onclick="location.href='{{ url('Contactus')  }}'">Contact us</button>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="plan-box advanced">
              <h3>Advanced</h3>
              <ul>
                <li>Music/Content serviced to our Global Member Network (email &amp; social blasts)</li>
                <li>Content posted on Digiwaxx Database</li>
                <li>Account Profile</li>
                <li>Analytics &amp; Data Report Access</li>
                <li>Messenger Chat Communication</li>
              </ul>
              <div class="btn-plan">
                <button class="btn btn-theme btn-gradient" onclick="location.href='{{ url('Contactus')  }}'">Contact us</button>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="plan-box additional">
              <h3>Additional Services</h3>
              <ul>
                <li>SPINWORLD Promotion</li>                
              </ul>
              <div class="info-plan">
                <p>Contact</p>
                <a href="mailto:business@digiwaxx.com">business@digiwaxx.com</a>
                <p>to inquire about our additional services</p>
              </div>
              <div class="btn-plan">
                <button class="btn btn-theme btn-gradient" onclick="location.href='http://www.digiwaxxmedia.com'" >Contact us</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="plan-service">
        <h2>Our services include:</h2>
        <div class="row">
          <div class="col-md-4 col-sm-12 s-box">
            <div class="service-box">
              <?php echo stripslashes(urldecode($promote1[0]->bannerText)); ?>
           </div>
          </div>
          <div class="col-md-4 col-sm-12 s-box">
            <div class="service-box">
             <?php echo stripslashes(urldecode($promote2[0]->bannerText)); ?>
            </div>
          </div>
          <div class="col-md-4 col-sm-12 s-box">
            <div class="service-box">
             <?php echo stripslashes(urldecode($promote3[0]->bannerText)); ?>
            </div>
          </div>
        </div>
        <h5>Any questions? Go to our <a href="#">Help</a> page or <a href="{{ url('Contactus')  }}">connect with us here.</a></h5>
        
      </div>
       
     </div> 
       
   </section>


<!-- <div class="packages">

	<div class="container"> -->

		<!-- <div class="row"> -->

			<!-- <div class="col-lg-4 col-md-4 col-sm-4">

				<div class="tdpic dso-item">

						<img src="{{ asset('public/images/pkg-bg1.jpg') }}" class="img-responsive">

						<div class="overlay-text"> -->

							<!-- <h3>Basic</h3>

						<ul style="padding:10px 54px;">

							<li style="color:#FFF;">Music/Content serviced to our Global Member Network (email &amp; social blasts)</li>

							<li style="color:#FFF;">Content posted on Digiwaxx Database</li>

							<li style="color:#FFF;">Account Profile</li> -->

						   <!-- <li class="dull">Analytics & Data Report Access </li>

							<li class="dull">Messenger Chat Communication</li>-->

						<!-- </ul> -->

							

<!--                                     <p class="price" style="margin-top:20px;"> $500<span>/track</span> </p>
-->
							

							 <!--  <div class="sub-btn"> -->

							  <!--<form action="https://www.digiwaxx.com/client_payment1" method="post">

							   <input type="submit" name="basic" value="Contact Us"  />

							  </form>-->
							  <!-- <a href="{{ url('Contactus')  }}">Contact Us</a>

							  </div> -->

							

					<!-- 	</div>

				</div>

		  </div> -->

			

			<!-- <div class="col-lg-4 col-md-4 col-sm-4">

				<div class="tdpic dso-item">

						<img src="{{ asset('public/images/pkg-bg2.jpg') }}" class="img-responsive">

						<div class="overlay-text">
							<h3>ADVANCED</h3>

						  <ul style="padding:10px 54px;">

							<li style="color:#FFF;">Music/Content serviced to our Global Member Network (email &amp; social blasts)</li>

							<li style="color:#FFF;">Content posted on Digiwaxx Database</li>

							<li style="color:#FFF;">Account Profile</li>

							<li style="color:#FFF;">Analytics &amp; Data Report Access </li>

							<li style="color:#FFF;">Messenger Chat Communication</li>

						</ul>
					<div class="sub-btn"> -->

						<!--<form action="https://www.digiwaxx.com/client_payment1" method="post">

						 <input type="submit" name="advanced" value="BUY" />

					   </form>-->
						<!-- <a href="{{ url('Contactus')  }}">Contact Us</a>

					</div>
						</div>

				</div>

		  </div>       -->             

			<!-- <div class="col-lg-4 col-md-4 col-sm-4">

				<div class="tdpic dso-item">

						<img src="{{ asset('public/images/pkg-bg3.jpg') }}" class="img-responsive">

						<div class="overlay-text">

							<h3>ADDITIONAL SERVICES</h3> 

					   <ul style="padding:10px 54px;">

							<li style="color:#FFF;">SPINWORLD Promotion</li>

						</ul>

						

						<p class="cnt" style="color:#FFF;">Contact <a href="javascript:void()" style="color:#FFF;">business@digiwaxx.com</a> to inquire about our additional services</p>

						<div class="sub-btn">

						<a href="http://www.digiwaxxmedia.com">Contact Us</a>

					</div>
						</div>
				</div>
			</div>
		</div> --><!-- eof row -->
		<!-- <div class="row">

					<div class="cu1 col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

						<a href="{{ url('Contactus')  }}" target="_parent" class="more" style="text-decoration: none;"><h1>Contact Us</h1></a>

					</div>

				</div> -->
	<!-- </div>
</div> -->

@endsection