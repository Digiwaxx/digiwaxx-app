
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
			<li class="active">Edit FAQ</li>
		</ul><!-- /.breadcrumb -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	   <div class="space-10"></div>
	  	<div class="card">
			 <!-- PAGE CONTENT BEGINS -->
		
				   <?php if(isset($alert_message)) {  ?>
				   <div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> </div>
				   <?php } ?>
				   <form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()">
					@csrf
					  <div class="row">
					  	 <div class="col-xs-12">
							<div class="form-group">
								<label for="form-field-1">Question</label>
							   <textarea class="form-control" id="question" placeholder="Question" name="question" required ><?php echo $faqs['data'][0]->question; ?></textarea>
							</div>
						 </div>
						 <div class="col-xs-12">
							<div class="form-group">
								<label for="form-field-1">Answer</label>
							   <textarea class="form-control" id="answer" placeholder="Answer" name="answer" required ><?php echo $faqs['data'][0]->answer; ?></textarea>
							</div>
						 </div>
						 <div class="col-xs-12">
						 <div class="form-group">
							<label for="form-field-1">Status</label>
							   <select class="form-control" name="status">
								  <option <?php if($faqs['data'][0]->status==1) { ?> selected="selected" <?php } ?> value="1">Active</option>
								  <option <?php if($faqs['data'][0]->status==0) { ?> selected="selected" <?php } ?> value="0">Inactive</option>
							   </select>
							</div>
						 </div>
						 <div class="space-24"></div>
						 <div style="clear:both;"></div>
						<div class="clearfix col-xs-12">
                        	<div class="text-right form-actions">
							   <button class="btn btn-info btn-sm" type="submit" name="updateFaq">
							   <i class="ace-icon fa fa-check bigger-110"></i>
							   Update FAQ
							   </button>
							   &nbsp; &nbsp; &nbsp;
							   <button class="btn btn-sm" type="reset">
							   <i class="ace-icon fa fa-undo bigger-110"></i>
							   Reset
							   </button>
							</div>
						 </div>
						</div>
				   </form>
			
			 <!-- /.row -->
			 <div class="hr hr-18 dotted hr-double"></div>
			 <!-- PAGE CONTENT ENDS -->
	   </div>
	   <!-- /.card -->
	</div>
	<!-- /.page-content -->
	<!-- /.page-content -->
	<script>
		function validate()
		{

			var company = document.getElementById('company');
			var link1 = document.getElementById('link1');

			if(company.value.length<1)
			{
			alert("Please enter company!");
			company.focus();
			return false;
			}


			if(link1.value.length<1)
			{
			alert("Please enter link!");
			link1.focus();
			return false;
			}

			var n = link1.value.indexOf(".");
			if(n<1)
			{
			alert("Please enter link!");
			link1.focus();
			return false;
			}



			if(image.value.length<1)
			{
			alert("Please upload an image!");
			image.focus();
			return false;
			}


		}
	</script>
    @endsection       