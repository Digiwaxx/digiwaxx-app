
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
			 What is Digiwaxx   
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
			  Home page Banner
			  </small>
		   </h1>
		</div>
		
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
					   <textarea class="ckeditor" name="description"><?php echo stripslashes(urldecode($bannerText[0]->bannerText)); ?></textarea>
					   <br />
					   <input type="submit"  name="addBannerText" value="Submit" class="btn btn-sm btn-primary"  />
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
			 
		
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			 Home page content
			  </small>
		   </h1>
		</div>
		<!-- /.page-header -->
		
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
					   <textarea class="ckeditor" name="description1"><?php echo stripslashes(urldecode($bannerText[1]->bannerText)); ?></textarea>
					   <br />
					   <input type="submit"  name="addBannerText1" value="Submit" class="btn btn-sm btn-primary"  />
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
			
		<!-- /.row -->
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Home page Top Links
			  </small>
		   </h1>
		</div>
		<!-- /.page-header -->
	
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
				 <div class="col-xs-12">
					<?php if(isset($alert_class4)) 
					   { ?>
					<div class="<?php echo $alert_class4; ?>">
					   <button class="close" data-dismiss="alert">
					   <i class="ace-icon fa fa-times"></i>
					   </button>
					   <?php echo $alert_message4; ?>
					</div>
					<?php } ?>
					<form action="" method="post">
					  @csrf
					   <div class="row">
					   <div class="col-sm-6 col-xs-12">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1">I'm a DJ Link</label>
							 <input type="text" id="dj_link" name="dj_link" class="form-control"  value="<?php  echo $topLinks[0]->linkHref; ?>">
						  </div>
					   </div>
					   
					   <div class="col-sm-6 col-xs-12">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1">I'm an Artist Link</label>
							 <input type="text" id="artist_link" name="artist_link" class="form-control" value="<?php  echo $topLinks[1]->linkHref; ?>">
						  </div>
					   </div>
					   
					   <div class="col-sm-6 col-xs-12">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1">I'm an Brand Link</label>
							 <input type="text" id="brand_link" name="brand_link" class="form-control" value="<?php  echo $topLinks[2]->linkHref; ?>">
						  </div>
					   </div>
					   <div class="col-xs-12">
						   <input type="submit"  name="updateTopLinks" value="Submit" class="btn btn-sm btn-primary"  />	
						</div>
					</div>
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
		
		
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Home page Links
			  </small>
		   </h1>
		</div>
		<!-- /.page-header -->
	
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
				 <div class="col-xs-12">
					<?php if(isset($alert_class2)) 
					   { ?>
					<div class="<?php echo $alert_class2; ?>">
					   <button class="close" data-dismiss="alert">
					   <i class="ace-icon fa fa-times"></i>
					   </button>
					   <?php echo $alert_message2; ?>
					</div>
					<?php } ?>
					<form action="" method="post">
					  @csrf
					  <div class="row">
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Label  </label>
							 <input type="text" id="label" name="labels[]" placeholder="label" class="form-control" value="<?php  echo $pageLinks[0]->linkLabel; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Link </label>
							 <input type="text" id="link1" name="links[]" class="form-control" placeholder="link" value="<?php  echo $pageLinks[0]->linkHref; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Label  </label>
							 <input type="text" id="label2" name="labels[]" class="form-control" placeholder="label" value="<?php  echo $pageLinks[1]->linkLabel; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Link </label>
							 <input type="text" id="link2" name="links[]" class="form-control" placeholder="link" value="<?php  echo $pageLinks[1]->linkHref; ?>">
						  </div>
					   </div>
					
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Label  </label>
							 <input type="text" id="label3" name="labels[]" class="form-control" placeholder="label" value="<?php  echo $pageLinks[5]->linkLabel; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Link </label>
							 <input type="text" id="link3" name="links[]" class="form-control" placeholder="link" value="<?php  echo $pageLinks[5]->linkHref; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Label  </label>
							 <input type="text" id="label4" name="labels[]" class="form-control" placeholder="label" value="<?php  echo $pageLinks[6]->linkLabel; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Link </label>
							 <input type="text" id="link4" name="links[]" class="form-control" placeholder="link" value="<?php  echo $pageLinks[6]->linkHref; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Label  </label>
							 <input type="text" id="label5" name="labels[]" class="form-control" placeholder="label" value="<?php  echo $pageLinks[7]->linkLabel; ?>">
						  </div>
					   </div>
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Link </label>
							 <input type="text" id="link5" name="links[]" class="form-control" placeholder="link" value="<?php  echo $pageLinks[7]->linkHref; ?>">
						  </div>
					   </div>
					   	<div class="col-xs-12">				
						   <input type="hidden" name="linkIds[]"  value="<?php  echo $pageLinks[0]->linkId; ?>">							
						   <input type="hidden" name="linkIds[]" value="<?php  echo $pageLinks[1]->linkId; ?>">
						   <input type="hidden" name="linkIds[]" value="<?php  echo $pageLinks[5]->linkId; ?>">
						   <input type="hidden" name="linkIds[]" value="<?php  echo $pageLinks[6]->linkId; ?>">
						   <input type="hidden" name="linkIds[]" value="<?php  echo $pageLinks[7]->linkId; ?>">
						   <input type="submit"  name="updateLinks" value="Submit" class="btn btn-sm btn-primary"  />	
						</div>
					</div>
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
			
		
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Logo & Banner
			  
			  </small>
		   </h1>
		</div>
		<!-- /.page-header -->
	
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
				 <div class="col-xs-12">
					<?php if(isset($alert_class3)) { ?>
					<div class="<?php echo $alert_class3; ?>">
					   <button class="close" data-dismiss="alert">
					   <i class="ace-icon fa fa-times"></i>
					   </button>
					   <?php echo $alert_message3; ?>
					</div>
					<?php } ?>
					<form action="" method="post" enctype="multipart/form-data">
						@csrf
						 <div class="row">
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Logo </label>
						  
							 <input type="file" id="logo" name="logo" class="form-control form-file">
							 <!--img class="mt-1" src="<?php //echo url('public/images/'.$logo); ?>" width="300" /-->
							 <img class="mt-1" src="<?php echo url('/pCloudImgDownload.php?fileID='.$logo); ?>" width="300" /> 
						  </div>
					   </div>
					   
					   <div class="col-sm-6">
					   	<div class="form-group">
						  <label class="control-label" for="form-field-1"> Banner </label>
						  
							 <input type="file" id="banner" name="banner" class="form-control form-file">
							 <!--<img class="mt-1" src="<?php //echo url('public/images/'.$banner[0]->banner_image); ?>" width="300" />-->
							 <img class="mt-1" src="<?php echo url('/pCloudImgDownload.php?fileID='.$banner[0]->pCloudFileID); ?>" width="300" /> 
						  </div>
					   </div>
					  
					   <div class="col-xs-12">
					   <input type="submit"  name="updateImgs" value="Submit" class="btn btn-sm btn-primary"  />
						</div>
					</div>
					</form>
				 </div>
				 <!-- /.span -->
			  </div>
			  <!-- /.row -->
			 
    @endsection       