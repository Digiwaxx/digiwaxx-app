
    @extends('admin.admin_dashboard_active_sidebar')
    @section('content')

    <div class="main-content">
    <div class="main-content-inner">
	<!-- #section:basics/content.breadcrumbs -->
	<div class="breadcrumbs" id="breadcrumbs">
		<script type="text/javascript">
			try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
		</script>

		<ul class="breadcrumb">
			<li>
				<a href="{{ url('admin/logos') }}">
				<i class="ace-icon fa fa-list list-icon"></i>
				Logos</a>
			</li>
			<li class="active">View Logo</li>
		</ul><!-- /.breadcrumb -->

		<!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">		
		<div class="space-10"></div>
			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="row">
						<div class="col-xs-12">
						<?php $logo = $logos['data'][0]; ?>
						</div>
						<div class="profile-user-info profile-user-info-striped">
								
							<div class="profile-info-row">
								<div class="profile-info-name"> Company </div>

								<div class="profile-info-value">
									<?php echo urldecode($logo->company); ?>
								</div>
							</div>

							<div class="profile-info-row">
								<div class="profile-info-name"> Link </div>

								<div class="profile-info-value">
												  <?php echo urldecode($logo->url); ?>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Logo </div>

								<div class="profile-info-value">
							<?php if (is_numeric($logo->pCloudFileID_logo)){	
							   $img= url('/pCloudImgDownload.php?fileID='.$logo->pCloudFileID_logo);?>
                                <img src="{{ $img }}" class="{{ $logo->img }}" width="150"/>
							<?php }
							 else if(!empty($logo->img) && file_exists(base_path('public/Logos/'.$logo->img))){?>	
									 <img src="{{ asset('public/Logos/'.$logo->img)}}" class="{{ $logo->img }}" width="150"/>
							<?php }?>
								</div>
							</div>
							
							
						</div>
					</div>
				</div>
			</div><!-- /.row -->
	</div><!-- /.page-content -->
	<script>

		function get_selected_data()
		{
		var sortBy = document.getElementById('sortBy').value;
		var sortOrder = document.getElementById('sortOrder').value;
		var numRecords = document.getElementById('numRecords').value;
		var company = document.getElementById('company').value;

		window.location = "logos?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&company="+company;
		}


	</script>
    @endsection       