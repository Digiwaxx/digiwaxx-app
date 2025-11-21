
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
				<a href="{{ route('admin_listNewsletters') }}">
				<i class="ace-icon fa fa-list list-icon"></i>
				Newsletters</a>
			</li>
			<li class="active">View Newsletter</li>
		</ul><!-- /.breadcrumb -->

		<!-- #section:basics/content.searchbox -->
		<div class="nav-search" id="nav-search">
			
		</div><!-- /.nav-search -->

		<!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
						<div class="page-header">
							
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
									
									
									<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Subject </div>

													<div class="profile-info-value">
													<?php echo $newsletter['data'][0]->subject; ?>	
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Message </div>

													<div class="profile-info-value">
													    <?php echo $newsletter['data'][0]->message; ?>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Sent to Types </div>

													<div class="profile-info-value">
                                                   <?php if($newsletter['data'][0]->type_id==1)
												          {
														    echo 'All subscribers';
														  }
														  else if($newsletter['data'][0]->type_id==2)
												          {
														    echo 'Selected subscribers';
														  }
														  
												    ?>	
                                                
                                                														
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Date </div>

													<div class="profile-info-value">
													<?php // echo $mails['data'][0]->started; 
												
												
													  $dt  = $newsletter['data'][0]->date_time;
			//	echo '<br />';
			$yr=strval(substr($dt,0,4)); 
        	$mo=strval(substr($dt,5,2)); 
        	$da=strval(substr($dt,8,2)); 
        	$hr=strval(substr($dt,11,2)); 
        	$mi=strval(substr($dt,14,2)); 
        	$se=strval(substr($dt,17,2)); 

        	echo date("l M/d/Y h:i A", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";	
													
													
													?>
																											</div>
												</div>
                                                
                                                <div class="profile-info-row">
													<div class="profile-info-name text-muted"> No. subscribers </div>

													<div class="profile-info-value">
													<?php echo $subscribers['numRows']; ?>	
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name text-muted"> Subscribers </div>

													<div class="profile-info-value">
													<?php if($subscribers['numRows']>0) 
													{
													  foreach($subscribers['data'] as $subscriber)
													  {
													  
													    echo $subscriber->email; echo '<br />';
													  }
													}
													
													 ?>	
													</div>
												</div>
												
                                                
											</div>
									
									
										
										
										
										
									</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
    @endsection 