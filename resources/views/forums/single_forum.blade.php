

@extends('layouts.app')
<style>
    .banner {
        background-image: url(<?php echo url('public/images/' . $banner[0]->banner_image); ?>);
        
    }
    .highlight-text{color: #52D0F8};
    .login-link {
             margin: 15px 0;
            }
            .login_here:hover {
                  color: orange;
                }
</style>
@section('content')


<!--banner start--->
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-5 col-sm-12">
                <div class="banner-caption">
                    <h1 class="news_title"><?php //echo stripslashes(urldecode($pageTitle)); ?></h1>
                </div>
            </div>
            <div class="col-md-5 col-lg-7 col-sm-12">

            </div>
        </div>
    </div>
</section>

 <?php 
        $array=json_decode($fetch_view);
        foreach($array as $key=>$value){
            $n_title=$value->art_title;
            $n_desc=$value->art_desc;
            $n_name=$value->fname;
            $n_uname=$value->uname;
            $n_date=$value->art_created_at;
            $n_view=$value->art_views;
        }
        
        ?>
        <section class="single-forum-view">
    <div class="container">
        <div class="forum-detail">
            <h4><?php echo urldecode($n_title); ?></h4>
            <p class="author-info">Published by <span class="highlight-text"> <?php echo urldecode($n_name); ?> - <?php   $date = new DateTime($n_date);
                                                                $result = $date->format('D M d,Y');   echo $result;
                                                                ?></span></p>
            <div class="forum-body">
                <?php echo urldecode($n_desc); ?>
                
            </div> 
            
            <div class="forum-views">
             <?php if(!empty($n_view)){?>  Views - <span class="highlight-text"><?php echo $n_view; ?> &nbsp <i class="ace-icon fa fa-eye bigger-120"></i></span><br><?php }?>
              <?php if(!empty($comment_count)){?>  Total Comments- <span class="highlight-text"><?php echo $comment_count;?> comments </span><?php } ?><br>
             <span id="total_likes" style="<?php if(empty($total_likes)){echo "display:none";}?>"> Total Likes- <span class="highlight-text"><span class="like_number"><?php echo $total_likes;?></span><i class="fa fa-thumbs-up"></i></span></span>
            </div>
        </div>
    <?php if(!empty($id)){ ?>  
     <div class="like-button" style="<?php if(!empty($liked_by_user)){echo "display:none";}?>">
         <?php $like_url=route('like_article'); ?>
         <input type="hidden" id="like_url" value="<?php echo $like_url;?>">
         <button class="btn btn-theme" onclick="article_like('<?php echo $art_id ?>','<?php echo $id; ?>','<?php echo $user_role;?>')">
             <i class="fa fa-thumbs-up"></i>
        </button>
     </div>
      <div class="dislike-button" style="<?php if(empty($liked_by_user)){echo "display:none";}?>">
         <?php $dislike_url=route('dislike_article'); ?>
         <input type="hidden" id="dislike_url" value="<?php echo $dislike_url;?>">
         <button class="btn btn-theme" onclick="article_dislike('<?php echo $art_id ?>','<?php echo $id; ?>')">
             <i class="fa fa-thumbs-down"></i>
        </button>
     </div
            <div class="forum-actions">
             
                     <form id="comment_post">
                            <?php $route_dis = route('add_comment'); ?>
                            <input type="hidden" id="com_url" value="<?php echo  $route_dis; ?>">
                             <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                            <input type="hidden" value="<?php echo $art_id; ?>"  name="art_id">
                            <input type="hidden" value="<?php echo $id; ?>"  name="user_id">
                            <input type="hidden" value="<?php echo $user_role; ?>"  name="user_role">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Post Your Comment</label>
                                <textarea class="form-control" placeholder="Enter your comment here" id="my_comment" name="comment_posted" rows="3" required></textarea>
                            </div>
                            <div class="comment_valid" style="display:none;color:red">Please enter your comment!</div>
                            <div class="notice" style="display:none">Comment posted.</div>
                            <button type="submit" class="btn btn-theme btn-gradient">SUBMIT</button> 
                      </form>
                      
                     
                
            </div>
    <?php }else{?>
           <div class="login-link"> <a href="{{route('login')}}"><span class="highlight-text"><p class="login_here">Please login to like and  post a comment.</p></span></a></div>
   <?php     }?>
    </div>
</section>



<?php if($comment_count > 0) {?>
<section class="other-comments">
    <div class="container">
        <div class="other-comments">
      
            <?php $array=json_decode($comments);
            foreach ($array as $value) { ?>
             <div class="forum-body">
                 <i class="fa fa-comments-o"></i>
                <?php echo urldecode($value->comment_posted); ?>
                
            </div>
                <p class="author-info"> By <span class="highlight-text"><?php echo urldecode($value->fname); ?> - <?php   $date = new DateTime($value->created_at);
                                                                $result = $date->format('D M d,Y');   echo $result;
                                                                ?></span></p>
           
            <hr>
            
            <?php }?>
            
            
        </div>    
        
    </div>
</section>
<?php }?>


<section class="go-back">
    <div class="container">
        <a href="{{route('list_forum')}}" class="btn btn-info"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                        GO BACK</a>
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

 <script type="text/javascript">

    $('#comment_post').on('submit',function(e){
        e.preventDefault();
     
        
        if($('#my_comment').val().length > 0){
            var x=$('#my_comment').val().trim()
            if(x.length == 0){
                $('.comment_valid').show();
                    setTimeout(function(){
                      $('.comment_valid').fadeOut(3000);  
                    }, 3000);
                
                return false;
            }
        }
        
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var data= $( this ).serialize();
        var get_url= $('#com_url').val();
        
       if( !confirm('Are you sure that you want to submit the comment') ) {
            return false;
        }else{
        //   Paste your AJAX script here
                
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
                        
                        $('.notice').show();
                        
                        setTimeout(function(){
                          $('.notice').fadeOut(4000); 
                          location.reload();
                            
                        }, 3000);
                        
                    }
                }
            });
        }

        
    });
    
    //article like
    
    function article_like(art_id,user_id,user_role){
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url=$('#like_url').val();
      
        $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                art_id: art_id,
                user_id:user_id,
                user_role:user_role
            },
            dataType: 'JSON',

            success: function(data) {

              
                    $('.like-button').hide();
                    $('.dislike-button').show();
                    if(data>0){
                        $(".like_number").empty();
                         $(".like_number").text(data);
                        $("#total_likes").show();
                       
                        console.log(data);
                    }
                    else{
                         $("#total_likes").hide();
                        
                    }
                    
                
            }
        });
        
    }
    
    //article dislike
       
    function article_dislike(art_id,user_id){
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url=$('#dislike_url').val();
        $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                art_id: art_id,
                user_id:user_id,
                
            },
            dataType: 'JSON',

            success: function(data) {

              
                     $('.like-button').show();
                    $('.dislike-button').hide();
                      if(data>0){
                         $(".like_number").text(data);
                        $("#total_likes").show();
                        
                        
                    }
                    else{
                         $("#total_likes").hide();
                        
                    }
                
            }
        });
        
    }
    
    
    
</script>

@endsection
