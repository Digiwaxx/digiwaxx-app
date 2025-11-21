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
		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
						<?php $route = route('addvideo'); ?>
						<form method="post" action="<?php echo $route;?>" enctype=multipart/form-data id="add_video">
							@csrf
							<div class="form-group">
								<label for="news_title">Title</label>
								<input type="text" class="form-control" id="video_title" name="video_title" aria-describedby="video-title" placeholder="Enter Title"required>

							</div>
							<div class="form-group">
								<label for="video_link">Video Link</label>
								<input type="text" class="form-control" id="video_link" name="video_link" aria-describedby="video-link" placeholder="Enter Link"required>

							</div>
		                	<div class="form-group form-check">
								<label class="form-check-label" for="video_status">Status</label>
								<select name="video_status" id="video_status">
								    <option value="0" selected>DRAFT</option>
								    <option value="1">PUBLISH</option>							
								    </select>

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
                                     
                                 });
                            
                            
                            
                            </script>

<!-- /.page-content -->
@endsection