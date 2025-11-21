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
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> <?php echo $pageTitle;?></li>
			</ul>
		</div>
		
		
		<?php foreach ($videos as $value){
		    $name=$value->title;
		    $id=$value->id;
		    $link=$value->video_url;
		    $status=$value->status;
		    
 
		}
		?>
		
		
		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
		    <?php if(!empty($result)){?>
		    <div class="<?php echo $class;?>" role="alert">
              <?php echo $result;?>
            </div>
            <?php }?>
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
					   
						<?php $route = route('video_edit'); ?>
						<form method="post" action="<?php echo $route;?>" enctype=multipart/form-data id="add_video">
							@csrf
							
							<input type ="hidden" name="video_id" value="<?php echo $id;?>">
							<div class="form-group">
								<label for="video_title">Title</label>
								<input type="text" class="form-control" id="video_title" name="video_title" aria-describedby="video-title" placeholder="Enter Title"required value="<?php if(!empty($name)){echo $name;}?>">

							</div>
			

						<div class="form-group">
								<label for="video_link">Video Link</label>
								<input type="text" class="form-control" id="video_link" name="video_link" aria-describedby="video-link" placeholder="Enter Link"required value="<?php if(!empty($link)){echo $link;}?>">

							</div>
							<div class="form-group">
							   <?php 	$output1 = getYoutubeEmbedUrl($link);?>
							            <iframe width="100%" max-width="100%"  height="300" max-height="300" src="<?php echo $output1;?>" frameborder="0" allowfullscreen></iframe>

							</div>
							
	                       	<div class="form-group form-check">
								<label class="form-check-label" for="video_status">Status</label>
								<select name="video_status" id="video_status">
								    <option value="0" <?php if($status==0){echo "selected";}?>>DRAFT</option>
								    <option value="1" <?php if($status==1){echo "selected";}?>>PUBLISH</option>							
								    </select>

							</div>
							
							
							<button type="submit" class="btn btn-primary">Submit</button>
							<br><br>
							<a href="{{route('videos')}}"><button type="button" class="btn btn-warning">BACK</button></a>
							
							

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
                                
                                       jQuery("#add_video").submit(function() {
                                            
                                            if(jQuery("#video_title").val().length>0){
                                                if(jQuery("#video_title").val().trim().length==0){
                                                    alert("Please enter all the details.");
                                                    return false;
                                                }
                                            }else{
                                            alert("Please enter all the details.");
                                            return false;
                                            }
                                            
                                           if(jQuery("#video_link").val().length>0){
                                                if(jQuery("#video_link").val().trim().length==0){
                                                    alert("Please enter all the details.");
                                                    return false;
                                                }
                                            }else{
                                            alert("Please enter all the details.");
                                            return false;
                                            }
                                            
                                          
                                        });
                            
                            
                            </script>

<!-- /.page-content -->
@endsection