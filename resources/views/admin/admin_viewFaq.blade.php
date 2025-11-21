
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
				<i class="ace-icon fa fa-list list-icon"></i>
				<a href="<?php echo url("admin/faqs"); ?>">FAQs</a>
			</li>
			<li class="active">View FAQ</li>
		</ul><!-- /.breadcrumb -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	
	  <div class="space-10"></div>
		 <div class="card">
				<!-- PAGE CONTENT BEGINS -->
					<div class="profile-user-info profile-user-info-striped">
						<div class="profile-info-row">
							<div class="profile-info-name text-muted">Question</div>

							<div class="profile-info-value">
								<?php echo $faqs['data'][0]->question; ?>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name text-muted">Answer</div>

							<div class="profile-info-value">
								<?php echo $faqs['data'][0]->answer; ?>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name text-muted">Status</div>

							<div class="profile-info-value">
								 <?php if($faqs['data'][0]->status==1) { echo 'Active'; } else { echo 'Inactive'; } ?>
							</div>
						</div>
					 </div>
				<div class="hr hr-18 dotted hr-double"></div>
				<!-- PAGE CONTENT ENDS -->
		</div><!-- /.card -->
	</div><!-- /.page-content -->
    @endsection       