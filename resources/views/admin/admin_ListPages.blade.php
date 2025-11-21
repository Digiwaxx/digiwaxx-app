
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
			 Home Page   
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Home page content
			  </small>
		   </h1>
		</div>
		
		<div class="row">
		   <div class="col-xs-12">
			  <!-- PAGE CONTENT BEGINS -->
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
					<form action="" method="post">
						@csrf
					   <textarea class="ckeditor" name="description"><?php echo $bannerText[0]->bannerText; ?></textarea>
					   <br />
					   <input type="submit"  name="addBannerText" value="Submit" class="btn btn-sm btn-primary"  />
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
			  <div class="hr hr-18 dotted hr-double"></div>
			  <!-- PAGE CONTENT ENDS -->
		   </div>
		   <!-- /.col -->
		</div>
		
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Home page content 1
			  </small>
		   </h1>
		</div>
		<!-- /.page-header -->
		<div class="row">
		   <div class="col-xs-12">
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
				 <div class="col-xs-12">
					<?php if(isset($alert_class1)) 
					   { ?>
					<div class="<?php echo $alert_class1; ?>">
					   <button class="close" data-dismiss="alert">
					   <i class="ace-icon fa fa-times"></i>
					   </button>
					   <?php echo $alert_message1; ?>
					</div>
					<?php } ?>
					<form action="" method="post">
						@csrf
					   <textarea class="ckeditor" name="description1"><?php echo $content1[0]->bannerText; ?></textarea>
					   <br />
					   <input type="submit"  name="addBannerText1" value="Submit" class="btn btn-sm btn-primary"  />
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
			  <div class="hr hr-18 dotted hr-double"></div>
			  <!-- PAGE CONTENT ENDS -->
		   </div>
		   <!-- /.col -->
		</div>
		<!-- /.row -->
		<!-- /.row -->
    @endsection       