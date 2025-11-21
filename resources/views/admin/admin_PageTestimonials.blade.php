
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
			 Testimonials   
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <div class="view">
	    <a href="<?php echo url(route('testimonials')) ?>" target="_blank" title="Preview Page" class="btn btn-sm btn-primary">VIEW</a>
	</div>
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Testimonials content
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
					   <textarea class="ckeditor" name="content"><?php echo stripslashes($content[0]->page_content); ?></textarea>
					   <br />
					   <input type="submit"  name="addPageText" value="Submit" class="btn btn-sm btn-primary"  />
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
    @endsection       