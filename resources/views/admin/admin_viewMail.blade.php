
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
			 <a href="{{ route('admin_listMails') }}">
			 <i class="ace-icon fa fa-list list-icon"></i>
			 Mails</a>
		  </li>
		  <li class="active">View Mail</li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	   <!--div class="nav-search" id="nav-search">
		  <form class="form-search">
			 <span class="input-icon">
			 <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
			 <i class="ace-icon fa fa-search nav-search-icon"></i>
			 </span>
		  </form>
	   </div-->
	   <!-- /.nav-search -->
	   <!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	   <div class="page-header">
	   </div>
	   <!-- /.page-header -->
	   <div class="row">
		  <div class="col-xs-12">
			 <!-- PAGE CONTENT BEGINS -->
				   <div class="profile-user-info profile-user-info-striped">
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> Track </div>
						 <div class="profile-info-value">
							<?php echo urldecode($mails['track'][0]->title); ?>	
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> Subject </div>
						 <div class="profile-info-value">
							<?php echo urldecode($mails['data'][0]->subject); ?>
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted">Complete in percentage </div>
						 <div class="profile-info-value">
							<?php  
							   if(strcmp($mails['data'][0]->ended,"0000-00-00 00:00:00")!=0) 
							   { 
									echo "100% Complete";
							   } ?>
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> Started </div>
						 <div class="profile-info-value">
							<?php // echo $mails['data'][0]->started; 
							 if(isset($mails['data'][0]->started)){
							 $dt  = $mails['data'][0]->started;
							   //	echo '<br />';
							   $yr=strval(substr($dt,0,4)); 
							   $mo=strval(substr($dt,5,2)); 
							   $da=strval(substr($dt,8,2)); 
							   $hr=strval(substr($dt,11,2)); 
							   $mi=strval(substr($dt,14,2)); 
							   $se=strval(substr($dt,17,2)); 
							   
							   echo date("l M/d/Y h:i A", mktime ((int)$hr,(int)$mi,0,(int)$mo,(int)$da,(int)$yr))." EST";	
							   
							  } 
							   ?>
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> Ended </div>
						 <div class="profile-info-value">
							<?php // echo $mails['data'][0]->ended; 
							   if(isset($mails['data'][0]->ended)){
							   $dt  = $mails['data'][0]->ended;
							   //	echo '<br />';
							   $yr=strval(substr($dt,0,4)); 
							   $mo=strval(substr($dt,5,2)); 
							   $da=strval(substr($dt,8,2)); 
							   $hr=strval(substr($dt,11,2)); 
							   $mi=strval(substr($dt,14,2)); 
							   $se=strval(substr($dt,17,2)); 
							   
							   echo date("l M/d/Y h:i A", mktime ((int)$hr,(int)$mi,0,(int)$mo,(int)$da,(int)$yr))." EST";
								}
							   
							   
							   ?>
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> Sent to Types </div>
						 <div class="profile-info-value">
							<?php echo $mails['data'][0]->types; ?>	
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> No. of mails assigned to send </div>
						 <div class="profile-info-value">
							<?php echo $mails['data'][0]->nummails; ?>	
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> No. mails sent </div>
						 <div class="profile-info-value">
							<?php echo $mails['mailsSent']; ?>	
						 </div>
					  </div>
					  <div class="profile-info-row">
						 <div class="profile-info-name text-muted"> Message </div>
						 <div class="profile-info-value">
							<?php echo urldecode($mails['data'][0]->message); ?>
						 </div>
					  </div>
				   </div>
				   <!--?php  $picked = $mails['numReceived']; ?>	
				   <php  $notPicked = $mails['mailsSent']-$mails['numReceived']; ?-->	
				   <div id="graphDiv"></div>
				</div>
				<!-- /.span -->
			 </div>
			 <!-- /.row -->
			 
			 <!-- PAGE CONTENT ENDS -->
		  </div>
		  <!-- /.col -->
	   </div>
	   <!-- /.row -->
	</div>
	<!-- /.page-content -->
    @endsection       