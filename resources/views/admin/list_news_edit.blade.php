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


			<ul class="breadcrumb">
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> EDIT NEWS</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<?php 
// 	pArr($news_details);
         $p_img='';
		foreach($news_details as $news_detail){
		    
        	    $n_title = $news_detail->title;    
        	    $n_desc = $news_detail->description;
        	    $n_img = $news_detail->Image;
        	    
        	    if(!empty($news_detail->pCloudFileID)){
        	        $p_img=$news_detail->pCloudFileID;
        	    }
		}
		?>
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
						<?php $route = route('news_edit'); ?>
						<form method="post" action="<?php echo $route; ?>" enctype=multipart/form-data id="add_news">
							@csrf
							<input type="hidden" name="news_id" value="<?php echo $news_id ?>"
							<div class="form-group">
								<label for="news_title">Title</label>
								<input type="text" class="form-control" id="news_title" name="news_title" aria-describedby="emailHelp" placeholder="Enter Title" value="<?php echo $n_title ?>">

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
                    					   <textarea class="ckeditor" name="description"><?php echo stripslashes(urldecode($n_desc)); ?></textarea>
                    					   <br />
                    					   <!--<input type="submit"  name="addBannerText" value="Submit" class="btn btn-sm btn-primary"  />-->
                    					<!--</form>-->
                    				 </div>
                    				 <!-- /.span -->
                    			  </div>
							</div>
							<div class="form-group form-check">
								<label class="form-check-label" for="news_image">Featured Image</label>
								<input type="file" class="form-check-input" id="news_image" name="news_image">
							<?php if(!empty($p_img)){?>
							       <?php $src=  url('/pCloudImgDownload.php?fileID='.$p_img);?>
							       		<img id="previewImg" src="<?php if(!empty($src)){echo $src;}else{echo asset('public/images/noimage-avl.jpg');}?>" width="200" height="200" class="img-responsive up-ar-img" />

					    	<?php }else{?>
					            	<img id="previewImg" src="<?php if(!empty($n_img)){echo asset('public/image_news/'.$n_img.'');}else{echo asset('public/images/noimage-avl.jpg');}?>" width="200" height="200" class="img-responsive up-ar-img" />
						 <?php	  } ?>	
								

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
                                   
                                        jQuery("#add_news").submit(function() {
                                            
                                            if(jQuery("#news_title").val().length>0){
                                                if(jQuery("#news_title").val().trim().length==0){
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