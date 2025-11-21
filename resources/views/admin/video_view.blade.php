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
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> VIEW NEWS</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<?php 
// 		print_r($news_details);
		foreach($videos as $news_detail){
		    $n_title = $news_detail->title;    
		    $link = $news_detail->video_url;
	
		    $date=$news_detail->created_at;
		    $status=$news_detail->status;
		    
		}
		?>
		<div class="page-content">
			<div class="row">
			   	<div class="profile-user-info profile-user-info-striped">
		        	<div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> TITLE </div>

						<div class="profile-info-value">
							<?php echo $n_title;?>
                        </div>
	            	</div>
	                	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> VIDEO </div>
    
    						<div class="profile-info-value">
    						   <?php 	$output1 = getYoutubeEmbedUrl($link);?>
							            <iframe width="100%" max-width="100%"  height="300" max-height="300" src="<?php echo $output1;?>" frameborder="0" allowfullscreen></iframe>
                            </div>
    	            	</div>
    	 
    	            	 	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> STATUS </div>
    
    						<div class="profile-info-value">
    							<?php if ($status==1){echo ("PUBLISHED");}else{echo ("DRAFT");}?>
                            </div>
    	            	</div>
    	            	 	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> CREATED ON </div>
    
    						<div class="profile-info-value">
    							<?php $date = new DateTime($date);
                                    echo  $result = $date->format('d-M-Y');
                                 ?>
                            </div>
    	            	</div>
	            </div>	
	            <br>
	            <br>
	            <?php $route = route('videos'); ?>
				<form action="<?php echo $route; ?>">
				<button class="btn btn-primary hBack" type="submit">GO BACK</button>
				</form>

				<!-- /.span -->
			</div>
			<!-- /.row -->

			<!-- PAGE CONTENT ENDS -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</div>
<!-- /.page-content -->
@endsection