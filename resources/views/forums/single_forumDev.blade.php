

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
                    <h1 class="news_title"><?php echo stripslashes(urldecode($pageTitle)); ?></h1>
                </div>

            </div>
            <div class="col-md-5 col-lg-7 col-sm-12">

            </div>
        </div>
    </div>
</section>

<section class="single-forum-view">
    <div class="container">
        <div class="forum-detail">
            <h4>phpBB 3.3.5 Release</h4>
            <p class="author-info"> by <a href="#">John</a> - Sun Oct 03, 2021 10:02 am</p>
            <div class="forum-body">
                <p>We are pleased to announce the release of phpBB 3.3.5 “Ich bin ein Bertie”. This version is a maintenance release of the 3.3.x branch which introduces a new helper function lang_js() for twig templates and resolves various issues reported in previous versions.</p>
                <p>The full list of changes is available in the changelog file within the docs folder contained in the release package. You can find the key highlights of this release below and a list of all issues fixed on our tracker at <a href="#">https://tracker.phpbb.com/issues/?filter=15890</a></p>
                <p><strong>The packages can be downloaded from our</strong></p>
            </div> 
        </div>
        <div class="forum-actions">
            <form>
                <div class="form-group">
                    <label>Your comment</label>
                    <textarea placeholder="Enter your comment here" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-theme btn-gradient">Submit</button>
                </div>
            </form>
        </div>
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

    $('#comment_post').on('submit',function(e){
        e.preventDefault();
        
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var data= $( this ).serialize();
        var get_url= $('#com_url').val();
        
                $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                data: data
            },
            dataType: 'JSON',

            success: function(data) {
                // $(".writeinfo").append(data.msg);
                if (data == 'success') {
                    location.reload();
                }
            }
        });
        
    });
    
    
</script>

@endsection
