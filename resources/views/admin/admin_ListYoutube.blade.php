
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
		  <li class="active">
			 <i class="ace-icon fa fa-list list-icon"></i>
			 Youtube
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <div class="nav-search" id="nav-search"></div>
	   <!-- /.nav-search -->
	   <!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="row">
		   <!--<div class="col-xs-12 searchDiv">-->
		   <div class="col-sm-6">	
			  <form class="form-inline searchForm" id="searchForm" action="" method="post" autocomplete="off">
				@csrf
				 					 
					<span>You Tube Link 1</span>
					<input type="text" class="nav-search-input" id="youtube" name="youtube" value="<?php echo $youtube[0]->youtube; ?>" />
					<input type="submit" value="Update" name="updateYoutube" />
				 
			   </form>
			 </div>
			 
			 <div class="col-sm-6">
			     <form class="form-inline searchForm" id="searchForm" action="" method="post" autocomplete="off">
			    	@csrf
				 					
					<span>You Tube Link 12</span>
					<input type="text" class="nav-search-input" id="youtube" name="youtube2" value="<?php echo $youtube[1]->youtube; ?>" />
				<input type="submit" value="Update" name="updateYoutube2" />
				 
			   </form>
			     
			 </div>
		   <!--</div>-->
		</div>
	   <div class="row">
		   <div class="col-xs-12">
			  <!-- PAGE CONTENT BEGINS -->
			  <div class="row">
			      	<?php 
					   if(isset($alert_message)) {					   
						?>
    			      	<div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
    					   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    					</div>
    				<?php } ?>	
				 <div class="col-xs-6">
				
				
					
					  <?php	$output1 = getYoutubeEmbedUrl($youtube[0]->youtube);?>
					<iframe width="420" height="315" src="<?php echo $output1; ?>"></iframe> 
				 </div>
				 	 <div class="col-xs-6">
					<?php 
					   if(isset($alert_message)) {					   
						?>
					<?php } ?>
					  <?php	$output2 = getYoutubeEmbedUrl($youtube[1]->youtube);?>
					<iframe width="420" height="315" src="<?php echo $output2; ?>"></iframe> 
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