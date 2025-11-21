
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
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> View Forum Article</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<?php 

         $array = json_decode($fetch_art);
		foreach($array as $news_detail){
		    $n_title = $news_detail->art_title;    
		    $n_desc = $news_detail->art_desc;
		    $n_name = $news_detail->fname;
		    $n_uname=$news_detail->uname;
		    $n_date= $news_detail->art_created_at;
		}
		?>
		<div class="page-content">
			<div class="row">
			    
			    	<div class="profile-user-info profile-user-info-striped">
    		        	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> Title </div>
    
    						<div class="profile-info-value">
    							<?php echo $n_title;?>
                            </div>
    	            	</div>
                    	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> Description </div>
    
    						<div class="profile-info-value">
    							<?php echo $n_desc;?>
                            </div>
    	            	</div>
      		        	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;">Published By</div>
    
    						<div class="profile-info-value">
    							<?php echo urldecode($n_name).' ('.urldecode($n_uname).')';?>
                            </div>
    	            	</div>
    		        	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> Published On </div>
    
    						<div class="profile-info-value">
    							<?php echo $n_date;?>
                            </div>
    	            	</div>
    	            	
	            	</div>

				<br>
				<br>
				<?php $route = route('list_forums'); ?>
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
