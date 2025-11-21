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
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> VIEW ANNOUNCEMENT</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<?php 
// 		print_r($news_details);
		foreach($view_announ as $news_detail){
		    $n_title = $news_detail->ma_title;    
		    $n_desc = $news_detail->ma_description;
		    $n_avail = $news_detail->ma_availability;
		    $n_date= $news_detail->ma_created_on;
		}
		?>
		<div class="page-content">
			<div class="row">

				
				<!----------------->
				<div class="profile-user-info profile-user-info-striped">
		        	<div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> Title </div>

						<div class="profile-info-value">
							<?php echo $n_title;?>
                        </div>
	            	</div>
	            	<div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> Published on </div>

						<div class="profile-info-value">
							<?php echo $n_date;?>
                        </div>
	            	</div>
	            	<div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> Description </div>

						<div class="profile-info-value">
							<?php echo $n_desc;?>
                        </div>
	            	</div>
	            	<div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> Availability </div>

						<div class="profile-info-value">
							<?php if($n_avail=='All'){ echo $n_avail." Members" ;}else{echo 'Selected Members' ;} ?>
                        </div>
	            	</div>
	            	<?php if(!empty($mem_details)){?>
	            	    <div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> Selected Members </div>

						<div class="profile-info-value">
							<?php foreach($mem_details as $key=>$value){
                                   foreach($value as $key1=>$value1){
                                       echo urldecode($value1->fname);
                                       
                                   }
                                  
                                   if(!empty($mem_details[$key+1])){
                                    echo " , ";
                                   }
                                }?>
                        </div>
	            	</div>
	            	<?php }?>
                </div>
                <!------------------------------------------>
				<br>
				<br>
				<?php $route = route('list_announcement'); ?>
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