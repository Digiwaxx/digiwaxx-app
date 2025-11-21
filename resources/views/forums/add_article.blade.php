@extends('layouts.app')
<style>
    .banner {
        background-image: url(<?php echo url('public/images/' . $banner[0]->banner_image); ?>);
    }
</style>
@section('content')


<!--banner start--->
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-5 col-sm-12">
                <div class="banner-caption">
                   <h1><?php echo stripslashes(urldecode($pageTitle)); ?></h1>
                </div>

            </div>
            <div class="col-md-5 col-lg-7 col-sm-12">

            </div>
        </div>
    </div>
</section>

<section class="below-banner">
    <div class="container">
        
      <form class="form-horizontal" id="add_article" method="post" action="{{route("add-article-details")}}">
          @csrf
          <input type="hidden" value="<?php echo $id; ?> " name="mem_id">
          <input type="hidden" value="<?php echo $user_role; ?>" name="user_role">
            <div class="form-group" style="color:white">
              <label class="control-label col-sm-2" for="article_title"><h4>Article Title</h4></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="article_title" placeholder="Enter title" name="article_title" required>
              </div>
            </div>
            <div class="form-group" style="color:white">
              <label class="control-label col-sm-2" for="article_desc"><h4>Article Description:</h4></label>
              <div class="col-sm-10 ">          
                <script src="{{ asset('assets_admin/assets/ckeditor/ckeditor.js') }}"></script>
            	<script src="{{ asset('assets_admin/assets/ckeditor/samples/js/sample.js') }}"></script>
                <link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/css/samples.css') }}">
                <link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">
                 <textarea class="ckeditor" id="art_desc" name="art_desc" required></textarea>
              </div>
            </div>
                <br>
                <br>
            <div class="form-group">        
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-theme btn-gradient">Submit</button>
              </div>
            </div>
      </form>


    </div>
</section>













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
                            
                            
                            
                            
                            
                              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                                <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
                                <script>
                                 jQuery(document).ready(function(){
                                   
                                        jQuery("#add_article").submit(function() {
                                            
                                            if(jQuery("#article_title").val().length>0){
                                                if(jQuery("#article_title").val().trim().length==0){
                                                    alert("Please enter all the details.");
                                                    return false;
                                                }
                                            }else{
                                            alert("Please enter all the details.");
                                            return false;
                                            }
                                            
                                          
                                        });
                                     
                                 });
                            
                            
                            
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



@endsection