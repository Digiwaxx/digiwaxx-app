
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
			 SEO
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="row">
		   <div class="col-xs-12">
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
				 <div class="col-xs-6">
					<?php if(isset($alert_message)) { ?>
					<div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
					   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					</div>
					<?php } ?>
					<form class="form-horizontal" role="form" id="addAdmin" action="" method="post" autocomplete="off" enctype="multipart/form-data">
						@csrf
					   <div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Page </label>
							 <div class="col-sm-9 nav-search">
								<select type="text" id="page_id" name="page_id" class="col-xs-10 col-sm-10" onChange="get_page_info(this.value)">
								   <option <?php if($meta[0]->page_id==4) { ?> selected="selected" <?php } ?> value="4">Charts</option>
								   <option <?php if($meta[0]->page_id==8) { ?> selected="selected" <?php } ?> value="8">Clients Page</option>
								   <option <?php if($meta[0]->page_id==6) { ?> selected="selected" <?php } ?> value="6">Contact Us</option>
								   <option <?php if($meta[0]->page_id==5) { ?> selected="selected" <?php } ?> value="5">Digiwaxx Radio</option>
								   <option <?php if($meta[0]->page_id==12) { ?> selected="selected" <?php } ?> value="12">Events</option>
								   <option <?php if($meta[0]->page_id==11) { ?> selected="selected" <?php } ?> value="11">Free Promo</option>
								   <option <?php if($meta[0]->page_id==1) { ?> selected="selected" <?php } ?> value="1">Home Page</option>
								   <option <?php if($meta[0]->page_id==7) { ?> selected="selected" <?php } ?> value="7">Press Page</option>
								   <option <?php if($meta[0]->page_id==3) { ?> selected="selected" <?php } ?> value="3">Promote Your Project</option>
								   <option <?php if($meta[0]->page_id==15) { ?> selected="selected" <?php } ?> value="15">Sponsor & Advertise</option>
								   <option <?php if($meta[0]->page_id==13) { ?> selected="selected" <?php } ?> value="13">Testimonials</option>
								   <option <?php if($meta[0]->page_id==9) { ?> selected="selected" <?php } ?> value="9">Wall of Scratch</option>
								   <option <?php if($meta[0]->page_id==2) { ?> selected="selected" <?php } ?> value="2">What is Digiwaxx</option>
								   <option <?php if($meta[0]->page_id==10) { ?> selected="selected" <?php } ?> value="10">What We Do</option>
								   <option <?php if($meta[0]->page_id==14) { ?> selected="selected" <?php } ?> value="14">Why Join</option>
								</select>
							 </div>
						  </div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page Tittle </label>
							 <div class="col-sm-9">
								<input type="text" id="meta_tittle" placeholder="Page Title" name="meta_tittle" class="col-xs-10 col-sm-10" value="<?php echo $meta[0]->meta_tittle; ?>">
							 </div>
						  </div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta Keywords </label>
							 <div class="col-sm-9">
								<textarea id="meta_keywords" placeholder="Meta Keywords" name="meta_keywords" class="col-xs-10 col-sm-10"><?php echo $meta[0]->meta_keywords; ?></textarea>
							 </div>
						  </div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta Description </label>
							 <div class="col-sm-9">
								<textarea id="meta_description" placeholder="Meta Description" name="meta_description" class="col-xs-10 col-sm-10"><?php echo $meta[0]->meta_description; ?></textarea>
							 </div>
						  </div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Facebook Tittle </label>
							 <div class="col-sm-9">
								<input type="text" id="fb_title" placeholder="Facebook Title" name="fb_title" class="col-xs-10 col-sm-10" value="<?php echo $meta[0]->fb_title; ?>">
							 </div>
						  </div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Facebook Description </label>
							 <div class="col-sm-9">
								<textarea id="fb_description" placeholder="Facebook Description" name="fb_description" class="col-xs-10 col-sm-10"><?php echo $meta[0]->fb_description; ?></textarea>
							 </div>
						  </div>
						  <div class="form-group">
							 <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Facebook Image </label>
							 <div class="col-sm-9">
								<input type="file" id="fb_image" name="fb_image" class="col-xs-10 col-sm-10" />
							 </div>
						  </div>
						  <div class="space-24"></div>
						  <div style="clear:both;"></div>
						  <div class="clearfix form-actions">
							 <div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info btn-sm" type="submit" name="update_meta">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Update 
								</button>
							 </div>
						  </div>
					   </div>
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
<script type="text/javascript">
		
 function get_page_info(id)
  {
  
 $.ajax({url: "seo?meta=1&page_id="+id, success: function(result){
 			
			 var obj = JSON.parse(result);

			document.getElementById('meta_tittle').value  = obj.tittle;
			document.getElementById('meta_keywords').innerHTML = obj.keywords;
			document.getElementById('meta_description').innerHTML = obj.description;
			document.getElementById('fb_title').value = obj.fb_title;
			document.getElementById('fb_description').innerHTML = obj.fb_description;
	}});		
  }
  </script>
    @endsection       