<?php

// $array=json_decode($get_article);
// print_r($array);
// die();
?>

@extends('layouts.app')
<style>
    .banner {
        background-image: url(<?php echo url('public/images/' . $banner[0]->banner_image); ?>);
    }
    .highlight-text{color: #52D0F8};
    .comment-link{text-decoration:none;}
</style>
@section('content')


<!--banner start--->
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-5 col-sm-12">
                <div class="banner-caption">
                    <h1 class="news_title"><?php echo stripslashes(urldecode($pageTitle)); ?></h1>
                </div>

            </div>
            <div class="col-md-5 col-lg-7 col-sm-12">

            </div>
        </div>
    </div>
</section>

<section class="below-banner">
    <div class="container">


     
                    
                    <div class="forum_lists">
                        <?php  if(!empty($row_count) && $row_count > 0){
                                $array=json_decode($get_article);
                                foreach($array as $value){?>
                                    <div class="forum-item forum-announcement">
                                        <!--<span class="forum-type"><i class="fa fa-bullhorn"></i></span>-->
                                        <?php 	//$minText = preg_split('/\r\n|\r|\n|\./', $value->art_desc);
                                        
                                        $str1=substr($value->art_desc,0,20);
                                        $arr=explode(" ",$str1);
                                        array_pop($arr);
                                        
                                        ?>
                                        <h5> <a href="{{route("single-forum",['id'=>$value->art_id])}}" title="View Announcement" ><?php echo urldecode($value->art_title); ?></a></h5>
                                        <p class="forum-desc"><?php  //echo preg_replace("/<br\W*?\/>/", "", $minText[0]);; 
                                        // echo $ar;
                                        // die();
                                        $xx= implode(" ",$arr);
                                        echo stripslashes(urldecode($xx));
                                        ?> <a class="comment-link" href="{{route("single-forum",['id'=>$value->art_id])}}">(......Read more)</a></p>
                                        <span class="forum-author">Published By: <span class="highlight-text"><?php echo urldecode($value->fname); ?></span> - <span class="highlight-text"><?php   $date = new DateTime($value->art_created_at);
                                                                $result = $date->format('D M d,Y');   echo $result;
                                                                ?></span></span>
                                       
                                    </div>
                                    <hr>                  
                                    
                              <?php  }
                        
                                }
                        ?>
                        
                    </div>
                    
                       	<?php if ($numPages > 1) { ?>
                    <div class="list-pagination">
                            <div class="pgm">
                                 <?php if ($start +$numRecords<=$num_records){ ?> 
                                    <?php echo $start + 1; ?> - <?php echo $start + $numRecords; ?> OF <?php echo $num_records; ?>
                                    <?php }else{?>
                                    <?php echo $num_records; ?> OF <?php echo $num_records; ?>
                                    
                                    <?php }?>
                                    
                            </div>
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <li class="page-item">
                            <a class="page-link" aria-label="Previous" href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>','<?php echo $urlString; ?>')">
                              <span aria-hidden="true">&laquo;</span>
                              <span class="sr-only">Previous</span>
                            </a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#"><?php echo $currentPageNo; ?></a></li>                         
                          <li class="page-item">
                            <a class="page-link" href="javascript:void(0);" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>','<?php echo $urlString; ?>')" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                              <span class="sr-only">Next</span>
                              
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                    <?php } ?>
 


    </div>
</section>









<div class="join-sec">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 col-sm-12">
			<h3>WHY JOIN DIGIWAXX?</h3>

<div class="join-info">
<p>Digiwaxx Media is the leading platform for digital music promotion in Hip Hop, R&amp;B, Reggae, and Dancehall music.</p>
</div>

<p>Our DJ members consist of Mix Tape DJs, Mobile DJs, Famous DJs, College Radio DJs, Internet Radio DJs, Satellite Radio DJs, Mainstream Radio DJs, and Social Network Influencers.</p>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="btn-follow text-center">
              <a href="https://telegram.com" class="btn-telegram" target="_blank"><i class="fab fa-telegram-plane"></i> Join us on Telegram</a>
              <a href="https://www.facebook.com/digiwaxx" class="btn-facebook" target="_blank"><i class="fa fa-facebook-f"></i> Join us on Facebook</a>
              <!-- <button class="btn btn-gradient" onclick="window.open('', '_blank')"></button>
              <button class="btn btn-gradient" onclick="window.open('', '_blank')"></button> -->
            </div>
          </div>
          <div class="col-md-12">
            <div class="join-btn btn-join text-center">
              <button class="btn btn-gradient" onclick="window.open('https://www.digiwaxxmedia.com/artist-marketing-services/', '_blank')">Discover More Services</button>
              <button class="btn btn-gradient" onclick="window.open('https://www.digiwaxxmedia.com/', '_blank')">Digiwaxx Agency Site </button>
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
                    <h4>DIGIWAXX UPDATES</h4>
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
                            <script type="text/javascript">
                                (function($) {
                                    window.fnames = new Array();
                                    window.ftypes = new Array();
                                    fnames[0] = 'EMAIL';
                                    ftypes[0] = 'email';
                                    fnames[1] = 'FNAME';
                                    ftypes[1] = 'text';
                                    fnames[10] = 'ALIAS';
                                    ftypes[10] = 'text';
                                    fnames[3] = 'COMPANY';
                                    ftypes[3] = 'text';
                                    fnames[4] = 'TWITTER';
                                    ftypes[4] = 'url';
                                    fnames[2] = 'MMERGE2';
                                    ftypes[2] = 'text';
                                }(jQuery));
                                var $mcj = jQuery.noConflict(true);
                            </script>
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

<script>
        function goToPage(page, pid, urlString)
        {
			if(pid > 0){
				var param = '?';
				if (urlString.length > 3)
				{
					param = '&';
				}
				window.location = page + urlString + param + "page=" + pid;
			}
        }
</script>

@endsection
