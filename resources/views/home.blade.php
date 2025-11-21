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
.banner{
    <?php if(is_numeric($banner[0]->pCloudFileID)){?>
                 background-image: url(<?php echo $ban_img;?>);
      <?php }else{ ?>
                    background-image: url(<?php echo $ban_img;?>);
         <?php }?>
}
.news-name{
    text-align:center;
    
}
#my_video{
    background:transparent;
    padding:5px;
}
.video_button{
    float:right;
}

</style>

@section('content')

<!-- <div class="wid header-bottom" style="background:url(https://digiwaxx.com/assets/img/what-is-digiwaxx-bg1.jpg) repeat scroll center top transparent">
    <div class="container">
		<h1><span style="font-size:42px"><em>DIGIWAXX </em>&nbsp;is a free online digital music pool for DJs and a music marketing promotion agency for the music industry. For 20+ years we have dedicated ourselves to giving DJ’s premium music through out platform. We ask for donations to support our staff and movement.</span></h1>

		
		  <div class="control-group text-center" style="align:center; padding-top:10px;">
                    
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_donations">
                <input type="hidden" name="business" value="paypal@digiwaxx.com">
                <input type="hidden" name="item_name" value="Digiwaxx">
                <input type="hidden" name="currency_code" value="USD">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button">
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
         </div>
    </div>
</div> --><!-- eof header-bottom -->

<style type="text/css">

 
/*#owl-album2.owl-carousel{
  opacity: 1;
  display: block;
}*/
/*.carousel .carousel-item {
    transition-duration: 2s;
}*/

/* medium and up screens */
/*@media (min-width: 768px) {
    
    .carousel-inner .carousel-item-end.active,
    .carousel-inner .carousel-item-next {
      transform: translateX(25%);
    }
    
    .carousel-inner .carousel-item-start.active, 
    .carousel-inner .carousel-item-prev {
      transform: translateX(-25%);
    }
}

.carousel-inner .carousel-item-end,
.carousel-inner .carousel-item-start { 
  transform: translateX(0);
}*/


</style>
<?php
// echo '<pre>';
// print_r($new_music);
// echo '</pre>';
// die();


?>


<!--<link href="https://www.landmarkmlp.com/js-plugin/owl.carousel/owl-carousel/owl.carousel.css" rel="stylesheet">-->
    <!--<link href="https://www.landmarkmlp.com/js-plugin/owl.carousel/owl-carousel/owl.theme.css" rel="stylesheet">-->
    <link rel="stylesheet" href="{{ asset('public/owl-carousel/owl-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('public/owl-carousel/owl-theme.css') }}">
    
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css" integrity="sha512-RWhcC19d8A3vE7kpXq6Ze4GcPfGe3DQWuenhXAbcGiZOaqGojLtWwit1eeM9jLGHFv8hnwpX3blJKGjTsf2HxQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />-->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css" integrity="sha512-itF/9I/NigY9u4ukjw9s7/kG6SC7LJ5Q4pRNMnTbGZAsO4/RSUelfVuYBk8AkSk23qEcucIqdUlzzpy2qf7jGg==" crossorigin="anonymous" referrerpolicy="no-referrer" />-->
 <!--banner start--->
    <section class="banner">
      <div class="container">
        <div class="row">
          <div class="col-md-7 col-lg-5 col-sm-12">
            <div class="banner-caption">
            <?php echo stripslashes(urldecode($bannerText[0]->bannerText)); ?>
            </div>

          </div>
          <div class="col-md-5 col-lg-7 col-sm-12">
            
          </div>
        </div>
      </div>
    </section>

<section class="below-banner">
    <div class="container">
      <!--cat section-->
       <div class="cat-sec">
         <div class="row">
           <div class="col-md-4 col-sm-12 col-12">
             <div class="cat-box">
              <a href="<?php  echo $topLinks[0]->linkHref; ?>">
                <img src="{{ asset('public/images/path/dj-music.png') }}" alt="cat-img" class="img-fluid">
                 <h2>I'm a DJ</h2>
                 <p>I want to receive exclusive music and more</p>
               </a>
                <div class="col-md-12 text-center">
                     <button class="btn btn-theme btn-alt2"><?php  echo "Get Started"; ?></button>
              </div>

             </div>
           </div>
           <div class="col-md-4 col-sm-12 col-12">
             <div class="cat-box">
              <a href="<?php  echo $topLinks[1]->linkHref; ?>">
                <img src="{{ asset('public/images/path/music.png') }}" alt="cat-img" class="img-fluid">
                 <h2>I'm an Artist</h2>
                 <p>I want to promote my music now</p>
              </a>
              <div class="col-md-12 text-center">
                     <button class="btn btn-theme btn-alt2"><?php  echo "Get Started"; ?></button>
              </div>
             </div>
           </div>
           <div class="col-md-4 col-sm-12 col-12">
             <div class="cat-box">
              <a href="<?php  echo $topLinks[2]->linkHref; ?>">
                <img src="{{ asset('public/images/path/disc.png') }}" alt="cat-img" class="img-fluid">
                 <h2>I'm a Brand</h2>
                 <p>I want to make my brand cooler</p>
               </a>
               <div class="col-md-12 text-center">
                     <button class="btn btn-theme btn-alt2"><?php  echo "Get Started"; ?></button>
              </div>
             </div>
           </div>
         </div>
       </div>
       <!--social section-->
             
     </div>
</section>

 <!--news section-->
<?php if(!empty($max_news)){?> 
    <section class="news-slide">
      <div class="container-fluid">
        <h1 class="text-center">Latest News</h1>



        <div id="owl-news" class="owl-carousel owl-theme">
            <?php foreach($max_news as $key=>$value){?>
                <?php
                   	if(!empty($value->pCloudFileID)){
                   	    $news_artwork = url('/pCloudImgDownload.php?fileID='.$value->pCloudFileID);
                   	}else if(!empty($value->image) && file_exists(base_path('public/image_news/'.$value->image))){
                    	$news_artwork = asset('public/image_news/'.$value->image);
                	}else{
                		$news_artwork = asset('public/images/noimage-avl.jpg');
                	}
                ?>
                <div class="item">
                  <div class="card">
                    <div class="card-img">
                      <a href="{{route("display-news",['id'=>$value->id])}}">
                         <img src="<?php echo $news_artwork; ?>" class="img-fluid " alt="news">
                       </a>
                    </div>
                    <div class="card-text news-name">
                      <h6><a href="{{route("display-news",['id'=>$value->id])}}"><?php //echo $value->title;
                      
                        $str1=substr($value->title,0,15);
                                        
                                       
                                        $arr=explode(" ",$str1);
                                         if(count($arr)>3)
                                         {
                                        // pArr($arr);
                                        array_pop($arr);
                                         }
                                        
                                       
                                        echo implode(" ",$arr)
                      
                      
                      
                      ?></a></h6>             
                    </div>
                  </div>
                </div>
         <?php } ?>    
            
      </div>
      <div class="customNavigation">
        <a class="btn prev2"><i class="fas fa-chevron-left"></i></a>
        <a class="btn next2"><i class="fas fa-chevron-right"></i></a>        
      </div>


         
        </div>
    </section>
<?php } ?>    
    
    
<?php if(!empty($youtube)){?>    
    <div class="social-sec ">
      <div class="container">
            <h1 class="text-center">Latest Videos</h1>
        <div class="row">
            <?php foreach($youtube as $utube){?>
                   <div class="col-lg-6 col-sm-12">
                     <div class="youtube-video embed-responsive embed-responsive-16by9">
                        <?php 	$output1 = getYoutubeEmbedUrl($utube->youtube);?>
                          <iframe  src="<?php echo $output1; ?>" class="embed-responsive-item" title="YouTube video player"  allowfullscreen></iframe>
                     </div>
                 </div>
            <?php }?>
             
               <!--<div class="col-lg-6 col-sm-12">-->
               <!--  <div class="youtube-video embed-responsive embed-responsive-16by9">-->
                  <?php //	$output1 = getYoutubeEmbedUrl($youtube[0]->youtube);?>
                  <!-- <iframe  src="<?php //echo $output1; ?>" class="embed-responsive-item" title="YouTube video player"  allowfullscreen></iframe> -->!
               <!--  </div>-->
               <!--</div>-->
               <!--<div class="col-lg-6 col-sm-12">-->
                 <!--<div class="tweet-sec">-->
               <!--   <div class="youtube-video embed-responsive embed-responsive-16by9">-->
               <!--      <iframe  src="https://www.youtube.com/embed/iayg6IbGSoA" class="embed-responsive-item" title="YouTube video player"  allowfullscreen></iframe>-->
                   <!--  <a class="twitter-timeline" data-width="100%" data-height="315"  data-tweet-limit="3" href="https://twitter.com/Digiwaxx?ref_src=twsrc%5Etfw" >Tweets by Digiwaxx</a>-->
                   <!--<iframe src="https://twitter.com/Digiwaxx?ref_src=twsrc%5Etfw"  data-tweet-limit="3" id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" class="twitter-timeline twitter-timeline-rendered" style="position: static; visibility: visible; display: inline-block; width: 100%; height: 315px; padding: 0px; border: none; max-width: 100%; min-width: 180px; margin-top: 0px; margin-bottom: 0px; min-height: 200px;" data-widget-id="profile:Digiwaxx" title="Twitter Timeline"></iframe>-->
               <!--  </div>-->
               <!--</div>-->
        </div>
        
      </div>
             
    </div> 
<?php }?>




<div class="album-main">
     <div class="container-fluid">
       <!--album section-->
       <div class="album-sec">

         <div class="row">
         <div class="slider">
         <div class="slider-wrap">
		 <?php
		 if(isset($tracks['data']) && $tracks['numRows'] > 0){
			 $countF = 1;
			foreach($tracks['data'] as $key => $track){
				if($key%2 != 0){
					continue;
				}
				if($countF > 12){
					break;
				}
				
			  if(!empty($track->pCloudFileID)){
			      $artWork=  url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);
			  }	
			  else if(!empty($track->imgpage) && file_exists(base_path('ImagesUp/'.$track->imgpage))){ 
					$artWork = asset('ImagesUp/'.$track->imgpage);
				}else{
					$artWork = asset('public/images/noimage-avl.jpg');
				}
			 ?>
           <!-- <div class="col-md-5-cols col-sm-2 col-1" id="<?php //echo $track->id; ?>"> -->
            <div class="col-md-2 col-sm-2 col-1" id="<?php echo $track->id; ?>">
             <div class="album-box">
              <div class="album-img">
                <!--a href="#"-->
                 <img src="<?php echo $artWork; ?>" class="img-fluid" alt="<?php echo urldecode($track->title); ?>">
               <!--/a-->
               
              </div>              
             </div>
           </div>
		 <?php
			$countF++;
			}
		 }
			?>    
       </div>
      </div>
	  
  </div>
       <div class="row">
        <div class="slider">
         <div class="slider-wrap">
		 
		 <?php
		 if(isset($tracks['data']) && $tracks['numRows'] > 0){
			 $countS = 1;
			foreach($tracks['data'] as $key => $track){
				if($key%2 == 0){
					continue;
				}
				if($countS > 10){
					break;
				}
				if(!empty($track->imgpage) && file_exists(base_path('ImagesUp/'.$track->imgpage))){
					$artWork = asset('ImagesUp/'.$track->imgpage);
				}else{
					$artWork = asset('public/images/noimage-avl.jpg');
				}
			 ?>
           <!-- <div class="col-md-5-cols col-sm-2 col-1" id="<?php //echo $track->id; ?>"> -->
            <div class="col-md-2 col-sm-2 col-1" id="<?php echo $track->id; ?>">
             <div class="album-box">
              <div class="album-img">
                <!--a href="#"-->
                 <img src="<?php echo $artWork; ?>" class="img-fluid" alt="<?php echo urldecode($track->title); ?>">
               <!--/a-->
               
              </div>              
             </div>
           </div>
		 <?php
			$countS++;
			}
		 }
			?>
		   
           <!--div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album7.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album8.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album9.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album10.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div> 
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album6.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album7.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album8.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album9.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div>
           <div class="col-md-5-cols col-sm-2 col-1">
             <div class="album-box">
              <div class="album-img">
                <a href="#">
                 <img src="{{ asset('public/images/path/album10.jpg') }}" class="img-fluid" alt="album">
               </a>
               
              </div>              
             </div>
           </div-->        
             
           </div>
       </div>

      </div>
   
            <div class="all-tracks-heading">
               <h1>All of the Tracks You Need in One Place</h1>
             </div>
       </div>      
     </div>
     
   </div>

<!--album items-->
<?php if(!empty($new_music)){?>
    <section class="album-scroll albm-new">
      <div class="container-fluid">
        <div class="alb-top">
          <h4>New Releases</h4>
          <div class="customNavigation">
            <a class="btn prev"><i class="fas fa-chevron-left"></i></a>
            <a class="btn next"><i class="fas fa-chevron-right"></i></a>        
          </div>
        </div>
        
        <div id="owl-album1" class="owl-carousel owl-theme">
       <?php
       foreach ($new_music as $key=>$value){?>
           <?php
           if(!empty($value->pCloudFileID)){
               $new_music_artwork=url('/pCloudImgDownload.php?fileID='.$value->pCloudFileID);
           }
           else	if(!empty($value->imgpage) && file_exists(base_path('ImagesUp/'.$value->imgpage))){
            	$new_music_artwork = asset('ImagesUp/'.$value->imgpage);
        	}else{
        		$new_music_artwork = asset('public/images/noimage-avl.jpg');
        	}
        	?>
              <div class="item">
              <div class="album-block">
                <div class="al-img">
                   <a href="{{route("Member_track_review",['tid'=>$value->id])}}">
                     <img src="<?php echo $new_music_artwork; ?>" class="img-fluid" alt="album">
                   </a>
                </div>
                <div class="al-content">
                  <h2><a href="{{route("Member_track_review",['tid'=>$value->id])}}"><?php echo stripslashes(urldecode($value->title));?></a></h2>
                  <p><?php echo stripslashes(urldecode($value->artist));?></p>
                </div>
              </div>
            </div>
           
           
    <?php   }
       ?>
       
       
          </div>
           
          
      </div>
    </section>
<?php }?>

<!--album items-->
<?php  if(!empty($max_downloads)) { ?>
<section class="album-scroll">
  <div class="container-fluid">
    <div class="alb-top">
      <h4>This week, Top Downloads</h4>
      <div class="customNavigation">
        <a class="btn prev1"><i class="fas fa-chevron-left"></i></a>
        <a class="btn next1"><i class="fas fa-chevron-right"></i></a>        
      </div>
    </div>
    
    <div id="owl-album2" class="owl-carousel owl-theme">
        <?php foreach($max_downloads as $key=>$value){?>
          <?php
          if(!empty($value->pCloudFileID)){
               $latest_music_artwork=url('/pCloudImgDownload.php?fileID='.$value->pCloudFileID);
           }
           else	if(!empty($value->imgpage) && file_exists(base_path('ImagesUp/'.$value->imgpage))){
            	$latest_music_artwork = asset('ImagesUp/'.$value->imgpage);
        	}else{
        		$latest_music_artwork = asset('public/images/noimage-avl.jpg');
        	}
    	?>
        <div class="item">
          <div class="album-block">
            <div class="al-img">
              <a href="{{route("Member_track_review",['tid'=>$value->id])}}">
                 <img src="<?php echo $latest_music_artwork; ?>" class="img-fluid" alt="album">
               </a>
            </div>
            <div class="al-content">
              <h2><a href="{{route("Member_track_review",['tid'=>$value->id])}}"><?php echo stripslashes(urldecode($value->album)); ?></a></h2>
              <p><?php echo stripslashes(urldecode($value->title)); ?></p>
            </div>
          </div>
        </div>
        
   <?php     }?>
    
      </div>
       
      
  </div>
</section>
<?php } ?>

<!----latest review-------------------------------------------------------------------->
<?php  if(!empty($max_reviews)) { ?>
<section class="album-scroll">
  <div class="container-fluid">
    <div class="alb-top">
      <h4>This week, Top Reviews</h4>
      <div class="customNavigation">
        <a class="btn prev1"><i class="fas fa-chevron-left"></i></a>
        <a class="btn next1"><i class="fas fa-chevron-right"></i></a>        
      </div>
    </div>
    
    <div id="owl-album3" class="owl-carousel owl-theme">
        <?php foreach($max_reviews as $key=>$value){?>
          <?php
           	if(!empty($value->imgpage) && file_exists(base_path('ImagesUp/'.$value->imgpage))){
            	$latest_music_artwork = asset('ImagesUp/'.$value->imgpage);
        	}else{
        		$latest_music_artwork = asset('public/images/noimage-avl.jpg');
        	}
    	?>
        <div class="item">
          <div class="album-block">
            <div class="al-img">
              <a href="{{route("Member_track_review",['tid'=>$value->track])}}">
                 <img src="<?php echo $latest_music_artwork; ?>" class="img-fluid" alt="album">
               </a>
            </div>
            <div class="al-content">
              <h2><a href="{{route("Member_track_review",['tid'=>$value->track])}}"><?php echo urldecode($value->album); ?></a></h2>
              <p><?php echo urldecode($value->artist); ?></p>
              <br>
              -<?php echo urldecode($value->additionalcomments);?>
            </div>
          </div>
        </div>
        
   <?php     }?>
    
      </div>
       
      
  </div>
</section>
<?php }?>

<?php if(!empty($sneaker)){?>
    <section class="album-scroll">
       
          <div class="container-fluid">
           
        <div class="alb-top">   
            <h4 class="text-center">Apparel by Digiwaxx</h4> 
                <div class="customNavigation">
                <a class="btn prev3"><i class="fas fa-chevron-left"></i></a>
                <a class="btn next3"><i class="fas fa-chevron-right"></i></a>        
              </div>
        </div>      
            <div id="owl-sneaker" class="owl-carousel owl-theme">  
           <?php foreach ($sneaker as $value){?>
                    <div class="item">
                      <div class="col-md-3 col-lg-8 ">
                        <div class="shop-block">
                          <div class="product-img">
                            
                              <?php
    	                            $n_img=$value->img_path;
    	                            if(!empty($value->pCloudFileID)){
                                     $path = url('/pCloudImgDownload.php?fileID='.$value->pCloudFileID);
                                    }
    							    else if(!empty($n_img) && file_exists(base_path('public/image_sneaker/'.$n_img))){ ?>
    							    	<?php $path = asset('public/image_sneaker/'.$n_img);?>
    	    					<?php }else{ ?>
    								     <?php $path =  asset('public/images/noimage-avl.jpg');?>
    								
    								<?php } ?>
    								<img id="previewImg" src="<?php echo $path ?>" height="50" width="50" alt="album" >
                            
                          </div>
                          <div class="product-content">
                            <h4><?php echo $value->name;?></h4>
                            <div class="p-price"><p>$<?php echo $value->price;?></p></div>
                          </div>
                        </div>
                      </div>
                    </div> 
              <?php }?>
              
            </div>
         
            <!--<div class="col-md-12 text-center">-->
            <!--  <button class="btn btn-theme btn-alt2">Shop</button>-->
            <!--</div>-->
     </div>
     
    </section>
<?php }?>
<?php if(!empty($videos)){?>
<section class="social-sec-video">
          <div class="container-fluid">
                  <div class="alb-top">   
                        <h4 class="text-center">Newest Videos</h4> 
                         
                                <div class="customNavigation">
                                    <a class="btn prev"><i class="fas fa-chevron-left"></i></a>
                                    <a class="btn next"><i class="fas fa-chevron-right"></i></a>
                                </div>
                          
                    </div> 
               <div id="owl-video" class="owl-carousel owl-theme">
                        <?php foreach($videos as $key=>$value){?>
                            <div class="item">
                              <div class="card" id="my_video">
                                  
                                  
                                <div class="card-img" >
                                     <div class="youtube-video embed-responsive embed-responsive-16by9">
                                    
                              <?php 	$output1 = getYoutubeEmbedUrl($value->video_url);?>
							    <iframe width="100%" max-width="100%"  height="300" max-height="300" src="<?php echo $output1;?>" frameborder="0" allowfullscreen></iframe>
							    </div>
                                </div>
                                <div class="card-text news-name">
                                    <h6>
                                  <?php //echo $value->title;
                                  
                                    $str1=substr($value->title,0,15);
                                                    
                                                   
                                                    $arr=explode(" ",$str1);
                                                     if(count($arr)>3)
                                                     {
                                                    // pArr($arr);
                                                    array_pop($arr);
                                                     }
                                                    
                                                   
                                                    echo implode(" ",$arr)
                                  
                                  
                                  
                                  ?></h6>             
                                </div>
                              </div>
                            </div>
                     <?php } ?>    
                        
             </div>
    
            
          </div>
 </section>
 <?php } ?>



<div class="partner text-center">       
       <div class="container-fluid px-0">
         <h3>"Still breakin' boundaries."</h3>
         <img src="{{ asset('public/images/path/partner.jpg') }}" alt="partner" class="img-fluid">
       </div>
     </div>
    </div>
</div>

    <!-- <div class="calendly">
        <p>IF YOU ARE SEEKING HONEST FEEDBACK, IDEAS ON HOW TO GAIN MORE FANS, A COURSE OF DIRECTION AND NEED A SOLID PLAN OF ACTION WE WILL HELP YOU. </p>
        <p><a href="https://calendly.com/digiwaxx" target="_blank">CLICK HERE TO SET UP A CONSULTATION WITH US NOW!</a></p>
    </div> -->
	<?php
		//echo'<pre>';print_r($pageLinks);die();
	?>
<div class="join-sec">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 col-sm-12">
			<?php echo stripslashes(urldecode($bannerText[1]->bannerText)); ?>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="btn-follow text-center">
            <?php if(!empty($pageLinks[5]->linkHref)){?>  <a href="<?php  echo $pageLinks[5]->linkHref; ?>" class="btn-telegram" target="_blank"><i class="fab fa-telegram-plane"></i> <?php  echo $pageLinks[5]->linkLabel; ?></a><?php }?>
            <?php if(!empty($pageLinks[5]->linkHref)){?><a href="<?php  echo $pageLinks[6]->linkHref; ?>" class="btn-facebook" target="_blank"><i class="fa fa-facebook-f"></i> <?php  echo $pageLinks[6]->linkLabel; ?></a><?php }?>
              <!-- <button class="btn btn-gradient" onclick="window.open('<?php  //echo $pageLinks[0]->linkHref; ?>', '_blank')"><?php  //echo $pageLinks[0]->linkLabel; ?></button>
              <button class="btn btn-gradient" onclick="window.open('<?php  //echo $pageLinks[1]->linkHref; ?>', '_blank')"><?php  //echo $pageLinks[1]->linkLabel; ?></button> -->
            </div>
          </div>
          <div class="col-md-12">
            <div class="join-btn btn-join text-center">
              <button class="btn btn-gradient" onclick="window.open('<?php  echo $pageLinks[0]->linkHref; ?>', '_blank')"><?php  echo $pageLinks[0]->linkLabel; ?></button>
              <button class="btn btn-gradient" onclick="window.open('<?php  echo $pageLinks[1]->linkHref; ?>', '_blank')"><?php  echo $pageLinks[1]->linkLabel; ?></button>
            </div>
          </div>
        </div>
        
      </div>
       
     </div>

<section class="bottom-sec">
     <div class="container">
       <div class="connect">
        <h3 class="text-center">Connect with us.</h3>
        <div class="subscribe">
          <div class="subs-head">
            <h4>Join our email list</h4>
            <p>* indicates required</p>
          </div>
          <div>
          <form action="https://digiwaxx.us1.list-manage.com/subscribe/post?u=e325f3a65ed75749cc95845d3&amp;id=4666fef1b4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="novalidate">
                        <div id="mc_embed_signup_scroll">
          <div class="row">
           <div class="col-md-4 col-lg-5 col-sm-12">
              <div class="form-group">
                <input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL" aria-required="true" placeholder="Email address*">
              </div>
             </div>
           <div class="col-md-4 col-lg-5 col-sm-12">
             <div class="form-group">                
                <input type="text" value="" name="FNAME" class="required form form-control" id="mce-FNAME" aria-required="true" placeholder="Name*">
              </div>
           </div>
           <div class="col-md-4 col-lg-2 col-sm-12">
            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_e325f3a65ed75749cc95845d3_4666fef1b4" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-theme btn-subscribe btn-gradient"></div>
             <!-- <button type="submit" class="btn btn-theme btn-subscribe btn-gradient">Subscribe</button> -->
           </div>
           <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>



         </div>
          <script type="text/javascript" src="https://digiwaxx.com/assets/js/mc-validate.js"></script>
                    <script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[10]='ALIAS';ftypes[10]='text';fnames[3]='COMPANY';ftypes[3]='text';fnames[4]='TWITTER';ftypes[4]='url';fnames[2]='MMERGE2';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                    <!--End mc_embed_signup-->
                </div>
            </form>
        </div>
       </div>
       <p>IF YOU ARE SEEKING HONEST FEEDBACK, IDEAS ON HOW TO GAIN MORE FANS, A COURSE OF DIRECTION AND NEED A SOLID PLAN OF ACTION WE WILL HELP YOU. </p>
       <p><a href="https://calendly.com/digiwaxx">CLICK HERE TO SET UP A CONSULTATION WITH US NOW! </a></p>
        </div>
         
     </div>
   </section> 

<script type="text/javascript">
//   $(document).ready(function () {
//   $(".multi-item-carousel").on("slide.bs.carousel", function (e) {
//   let $e = $(e.relatedTarget),
//     itemsPerSlide = 3,
//     totalItems = $(".carousel-item", this).length,
//     $itemsContainer = $(".carousel-inner", this),
//     it = itemsPerSlide - (totalItems - $e.index());
//   if (it > 0) {
//     for (var i = 0; i < it; i++) {
//       $(".carousel-item", this)
//         .eq(e.direction == "left" ? i : 0)
//         // append slides to the end/beginning
//         .appendTo($itemsContainer);
//     }
//   }
// });
// });

//owl news
$(document).ready(function() {
 
  var owl = $("#owl-news");
 
  owl.owlCarousel({
      items : 4, //10 items above 1000px browser width
      itemsDesktop : [1199,4], //5 items between 1000px and 901px
      itemsDesktopSmall : [979,4], // betweem 900px and 601px
      itemsTablet: [768,2], //2 items between 600 and 0
      itemsMobile : [479,1], // itemsMobile disabled - inherit from itemsTablet option
      slideSpeed : 3500,
       autoPlay: 5000,
       pagination : false,
       stopOnHover : true
  });
 
  // Custom Navigation Events
  $(".next2").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev2").click(function(){
    owl.trigger('owl.prev');
  })
 
 
});


//video
$(document).ready(function() {
 
  var owl = $("#owl-video");
 
  owl.owlCarousel({
      items : 3, //10 items above 1000px browser width
      itemsDesktop : [1199,3], //5 items between 1000px and 901px
      itemsDesktopSmall : [979,3], // betweem 900px and 601px
      itemsTablet: [768,3], //2 items between 600 and 0
      itemsMobile : [479,1], // itemsMobile disabled - inherit from itemsTablet option
      slideSpeed : 3500,
       autoPlay: 5000,
       pagination : false,
       stopOnHover : true
  });
 
  // Custom Navigation Events
  $(".next").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev").click(function(){
    owl.trigger('owl.prev');
  })
 
 
});


$(document).ready(function() {
 
  var owl = $("#owl-sneaker");
 
  owl.owlCarousel({
      items : 4, //10 items above 1000px browser width
      itemsDesktop : [1199,4], //5 items between 1000px and 901px
      itemsDesktopSmall : [979,3], // betweem 900px and 601px
      itemsTablet: [768,2], //2 items between 600 and 0
      itemsMobile : [479,1], // itemsMobile disabled - inherit from itemsTablet option
      slideSpeed : 3500,
       autoPlay: 4000,
       pagination : false,
       stopOnHover : true
  });
 
  // Custom Navigation Events
  $(".next3").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev3").click(function(){
    owl.trigger('owl.prev');
  })
 
 
});

//owl 1
$(document).ready(function() {
 
  var owl = $("#owl-album1");
 
  owl.owlCarousel({
      items : 4, //10 items above 1000px browser width
      itemsDesktop : [1199,4], //5 items between 1000px and 901px
      itemsDesktopSmall : [979,3], // betweem 900px and 601px
      itemsTablet: [768,2], //2 items between 600 and 0
      itemsMobile : [479,1], // itemsMobile disabled - inherit from itemsTablet option
      slideSpeed : 3500,
       autoPlay: 4000,
       pagination : false,
       stopOnHover : true
  });
 
  // Custom Navigation Events
  $(".next").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev").click(function(){
    owl.trigger('owl.prev');
  })
  
 
});


//owl 2
$(document).ready(function() {
 
  var owl = $("#owl-album2");
 
  owl.owlCarousel({
      items : 4, //10 items above 1000px browser width
      itemsDesktop : [1199,4], //5 items between 1000px and 901px
      itemsDesktopSmall : [979,3], // betweem 900px and 601px
      itemsTablet: [768,2], //2 items between 600 and 0
      itemsMobile : [479,1], // itemsMobile disabled - inherit from itemsTablet option
      slideSpeed : 3500,
       autoPlay: 5000,
       pagination : false,
       stopOnHover : true
  });
 
  // Custom Navigation Events
  $(".next1").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev1").click(function(){
    owl.trigger('owl.prev');
  })
 
 
});

//owl 2
$(document).ready(function() {
 
  var owl = $("#owl-album3");
 
  owl.owlCarousel({
      items : 4, //10 items above 1000px browser width
      itemsDesktop : [1199,4], //5 items between 1000px and 901px
      itemsDesktopSmall : [979,3], // betweem 900px and 601px
      itemsTablet: [768,2], //2 items between 600 and 0
      itemsMobile : [479,1], // itemsMobile disabled - inherit from itemsTablet option
      slideSpeed : 3500,
       autoPlay: 5000,
       pagination : false,
       stopOnHover : true
  });
 
  // Custom Navigation Events
  $(".next1").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev1").click(function(){
    owl.trigger('owl.prev');
  })
 
 
});



</script>
	<script src="{{ asset('public/owl-carousel/owl-carousel.min.js') }}"></script>

    <!--<script src="https://www.landmarkmlp.com/js-plugin/owl.carousel/owl-carousel/owl.carousel.min.js"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js" integrity="sha512-9CWGXFSJ+/X0LWzSRCZFsOPhSfm6jbnL+Mpqo0o8Ke2SYr8rCTqb4/wGm+9n13HtDE1NQpAEOrMecDZw4FXQGg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->

@endsection