	<?php 
// 		print_r($news_details);
    $array=json_decode($forum_det);
	foreach($array as $news_detail){
		   $n_title = $news_detail->art_title;    
		    $n_desc = $news_detail->art_desc;
		    $n_id=$news_detail->art_id;
		    $n_name=$news_detail->fname;
		    $n_uname=$news_detail->uname;
		    $n_email=$news_detail->email;
		    $n_date=$news_detail->art_created_at;
		 
		}
	?>
@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
	<div class="main-content-inner">

		<!-- #section:basics/content.breadcrumbs -->
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try {
					ace.settings.check('breadcrumbs', 'fixed')
				} catch (e) {}
			</script>
			<style>
			.detail-list li {
				margin: 10px 0;
			}
			.highlight-text{color: #52D0F8}
			</style>

			<ul class="breadcrumb">
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> Edit Article</li>
				
			</ul>
			<br>
		
		</div>
		<div class="details" style="color:white">
		 <ul class="detail-list" style="list-style-type:none;margin-left: 10px;">   
		    <li> Published By - <?php echo urldecode($n_name); if(!empty($n_email)){ echo "(".urldecode($n_email).")" ;} ?> </li>
		    
		   
		    <li> Published On - <?php echo urldecode($n_date); ?></li>
		</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->

		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
						<?php $route = route('forum_edit'); ?>
						<form method="post" action="<?php echo $route; ?>" enctype=multipart/form-data id="add_article">
							@csrf
							<input type="hidden" name="forum_id" value="<?php echo $n_id ?>"
							<div class="form-group">
								<label for="news_title">TITLE</label>
								<input type="text" class="form-control" id="art_title" name="edit_art_title" aria-describedby="emailHelp" placeholder="Enter Title" value="<?php echo $n_title ?>">

							</div>
							<div class="form-group">
								<label for="news_description">Description</label>
								<div class="row">
                    				 <div class="col-xs-12">
                    					<?php if(isset($alert_class)) 
                    					   { ?>
                    					<div class="<?php echo $alert_class; ?>">
                    					   <button class="close" data-dismiss="alert">
                    					   <i class="ace-icon fa fa-times"></i>
                    					   </button>
                    					   <?php echo $alert_message; ?>
                    					</div>
                    					<?php } ?>
                    					<script src="{{ asset('assets_admin/assets/ckeditor/ckeditor.js') }}"></script>
                    					<script src="{{ asset('assets_admin/assets/ckeditor/samples/js/sample.js') }}"></script>
                    					<link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/css/samples.css') }}">
                    					<link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">
                    					<!--<form action="" method="post">-->
                    						@csrf
                    					   <textarea class="ckeditor" name="edit_art_desc"><?php echo stripslashes(urldecode($n_desc)); ?></textarea>
                    					   <br />
                    					   <!--<input type="submit"  name="addBannerText" value="Submit" class="btn btn-sm btn-primary"  />-->
                    					<!--</form>-->
                    				 </div>
                    				 <!-- /.span -->
                    			  </div>
							</div>

							<button type="submit" class="btn btn-primary">Submit</button>
							<br>
						</form>
					</div>
				</div>
				<!-- /.span -->
			</div>
			<!-- /.row -->

			<!-- PAGE CONTENT ENDS -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                                <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
                                <script>
                                 jQuery(document).ready(function(){
                                   
                                        jQuery("#add_article").submit(function() {
                                            
                                            if(jQuery("#art_title").val().length>0){
                                                if(jQuery("#art_title").val().trim().length==0){
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

<!-- /.page-content -->
@endsection