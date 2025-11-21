
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
			 I'm a DJ   
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <div class="view">
	    <a href="<?php echo url(route('ImDj')) ?>" target="_blank" title="Preview Page" class="btn btn-sm btn-primary">VIEW</a>
	</div>
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="page-header">
		   <h1>
			  <small>
			  <i class="ace-icon fa fa-angle-double-right"></i>
			  Page Links
			  </small>
		   </h1>
		</div>
		<!-- /.page-header -->
		
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
					<form action="" method="post">
						@csrf
					   <div class="col-sm-9 form-group">
						  <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Digital Waxx services for DJs/Members </label>
						  <div class="col-sm-8">
							 <input type="text" id="services_djs" name="links[]" class="col-xs-10 col-sm-10"  value="<?php  echo $pageLinks[0]->linkHref; ?>">
						  </div>
					   </div>
					   <div style="clear:both;"></div>
					   <div class="col-sm-9 form-group">
						  <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Digital Waxx Record Pool </label>
						  <div class="col-sm-8">
							 <input type="text" id="record_pool" name="links[]" class="col-xs-10 col-sm-10"  value="<?php  echo $pageLinks[1]->linkHref; ?>">
						  </div>
					   </div>
					   <div style="clear:both;"></div>
					   <input type="hidden" name="linkIds[]"  value="<?php  echo $pageLinks[0]->linkId; ?>">							
					   <input type="hidden" name="linkIds[]" value="<?php  echo $pageLinks[1]->linkId; ?>">
					   <input type="submit"  name="updateLinks" value="Submit" class="btn btn-sm btn-primary"  />	
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