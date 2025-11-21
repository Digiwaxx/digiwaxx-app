
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
			<li class="active">Edit Logo</li>
		</ul><!-- /.breadcrumb -->

		<!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">		
      <?php if(!empty($logos['data'][0])){?>
		 <h3 class="header smaller lighter">Edit Logo</h3>
					<!-- PAGE CONTENT BEGINS -->
					
						<div class="">
						<?php if(isset($alert_message)) {  ?>
						<div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> </div>
				
						<?php } ?>
						<?php $logo = $logos['data'][0]; ?>
						<form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()">
						@csrf
					
						<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label for="company" class="req-label">Company </label>
								<input type="text" id="company" placeholder="Company" name="company" class="form-control" value="<?php echo urldecode($logo->company); ?>" required />	
							</div>
							
						</div>
						<div class="col-xs-12">
						<div class="form-group">
								<label for="link1">Link</label>
								<input type="text" id="link1" placeholder="Link" name="link" class="form-control" value="<?php echo urldecode($logo->url); ?>" >
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label for="form-field-3">Logo</label>
								<input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif">
							<?php if (is_numeric($logo->pCloudFileID_logo)){	
							   $img= url('/pCloudImgDownload.php?fileID='.$logo->pCloudFileID_logo);?>
                                <img src="{{ $img }}" class="{{ $logo->img }}" width="150"/>
							<?php }
							 else if(!empty($logo->img) && file_exists(base_path('public/Logos/'.$logo->img))){?>	
									 <img src="{{ asset('public/Logos/'.$logo->img)}}" class="{{ $logo->img }}" width="150"/>
							<?php }?>
							</div>
						</div>
						
						
						<div class="space-24"></div>
						
						<div class="col-xs-12">
							<div class="text-right form-actions ">
								<button class="btn-sm btn-info" type="submit" name="updateLogo">
									<i class="ace-icon fa fa-check bigger-110"></i>
									Update Logo
								</button>

								&nbsp; &nbsp; &nbsp;
								<button class="btn btn-sm" type="reset">
									<i class="ace-icon fa fa-undo bigger-110"></i>
									Reset
								</button>
							</div>
						</div>	
						</div><!-- /.span -->
						
						</form>
						
				

					<div class="hr hr-18 dotted hr-double"></div>
				</div>
				
					<!-- PAGE CONTENT ENDS -->
		<?php }else{echo "Invalid logo details.";}?>		
	</div><!-- /.page-content -->
	<script>

function validate(){

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
	alert("Please enter link1!");
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
}


	</script>
    @endsection       