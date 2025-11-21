
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
			 Help   
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <div class="view">
	    <a href="<?php echo url(route('Help')) ?>" target="_blank" title="Preview Page" class="btn btn-sm btn-primary">VIEW</a>
	</div>
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">

		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			 Help Page Banner
			  </small>
		   </h1>
		</div>
		<div class="row">
		   <div class="col-xs-12">
				 <div class="row">
				 <div class="col-xs-12">
					<form action="" method="post" enctype="multipart/form-data">
						@csrf					   
					   <div class="col-sm-6 form-group">
						  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Banner </label>
						  <div class="col-sm-9">
							 <input type="file" id="banner" name="banner" class="col-xs-10 col-sm-10">
						  </div>
					   </div>
					   <div class="col-sm-6 form-group">
					   <?php
                    	if(!empty($banner[0]->pCloudFileID)){ ?>
                    	  <img src="<?php echo url('/pCloudImgDownload.php?fileID='.$banner[0]->pCloudFileID); ?>" width="300" />
                    	<?php }else if(!empty($banner[0]->banner_image)){ ?>
						  <img src="<?php echo url('public/images/'.$banner[0]->banner_image); ?>" width="300" />
						<?php } ?>
					   </div>
					   <br />
					   <input type="submit"  name="updateImgs" value="Submit" class="btn btn-sm btn-primary"  />
					</form>
				</div>
				</div>
				<div class="hr hr-18 dotted hr-double"></div>
		   </div>
		   <!-- /.col -->
		</div>
	
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Help Banner Text
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
					   <textarea class="ckeditor" name="bannerText"><?php echo stripslashes(urldecode($bannerText[0]->bannerText)); ?></textarea>
					   <br />
					   <input type="submit"  name="updateBannerText" value="Submit" class="btn btn-sm btn-primary" />
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
			  Help page content
			  </small>
		   </h1>
		</div>
		
		<div class="row">
		   <div class="col-xs-12">
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
				 <div class="col-xs-12">
					<?php if(isset($alert_class1)) { ?>
					<div class="<?php echo $alert_class1; ?>">
					   <button class="close" data-dismiss="alert">
					   <i class="ace-icon fa fa-times"></i>
					   </button>
					   <?php echo $alert_message1; ?>
					</div>
					<?php } ?>
					<form action="" method="post">
					@csrf
					   <textarea class="ckeditor" name="content"><?php echo stripslashes(urldecode($content[0]->page_content)); ?></textarea>
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
		<!-- /.row -->
    @endsection       