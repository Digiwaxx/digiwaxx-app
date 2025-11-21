@extends('layouts.app')

  <?php 
  $ban_img='';
  if(is_numeric($banner[0]->pCloudFileID)){
      $ban_img= url('/pCloudImgDownload.php?fileID='.$banner[0]->pCloudFileID);
  }else{
      $ban_img=  url('public/images/'.$banner[0]->banner_image);
  }
  
  ?>

<?php if(!empty($ban_img)){ ?>
<style>
	.why-bg-login{
		    <?php if(is_numeric($banner[0]->pCloudFileID)){?>
                 background-image: url(<?php echo $ban_img;?>);
      <?php }else{ ?>
                    background-image: url(<?php echo $ban_img;?>);
         <?php }?>
	}
</style>
<?php } ?>
@section('content')

 <section class="content-area why-bg-login">
     <div class="container">
      <div class="member-area">
      	<h3>Why Join Us?</h3>
	  <?php echo stripslashes(urldecode($content[0]->page_content)); ?>
      </div> 
     </div> 
       
   </section>

<!-- <div class="digi-radio-block" style="height:1000px;">
	   
	<div class="col-sm-offset-2 col-sm-8  page_content">
	  <h1>&nbsp;</h1>

		<h1>WHY JOIN?</h1>

		<p>&nbsp;</p>

		<p><span style="font-size:16px">We are centered around a network of local relationships on a worldwide scale and our ability to activate these dynamic relationships with agility, efficiency and credibility knows no equal in the industry. Digiwaxx is an agency that converts years of experience at the forefront of urban creativity and lifestyle into a dynamic hub of information and resources. Our platforms and services are able to connect music, technology, people and culture in a way that is unique to the Digiwaxx experience.&nbsp;</span></p>

		<p>&nbsp;</p>

		<p><span style="font-size:16px">Digiwaxx members can download music files that take up no more space than a Word document.&nbsp;In addition, the service offers real-time feedback to featured artists, giving labels a chance to test material before it hits the mass market. The service currently reaches over 10,000 commercial mix shows, colleges, radio stations, satellite providers, and online DJs.</span><br>
		&nbsp;</p>
	</div>     		

</div> -->
@endsection