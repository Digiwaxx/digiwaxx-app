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
								<i class="ace-icon fa fa-users users-icon"></i>
								<a href="<?php echo url('admin/clients'); ?>">Clients</a>
							</li>
                            <li class="active">Client Change Password</li>
						</ul><!-- /.breadcrumb -->

					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<div class="space-10"></div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
									
									
									<?php if(isset($alert_message))
									{
									?>
									<div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> <button class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button></div>
									
									<?php 
									
									} ?>
							
									<div>
										<?php $client = $clients['data'][0]; ?>
									
									
									<form class="form-horizontal" action="" method="post" onsubmit="return validate()">
                                    @csrf
									<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name"> Username </div>

													<div class="profile-info-value">
													
														<?php echo urldecode($client->uname); ?>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Email </div>

													<div class="profile-info-value">
													
														<?php echo urldecode($client->email); ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Password </div>

													<div class="profile-info-value">
													<input type="text" id="password" class="col-xs-4 col-sm-4" name="password" />
													
													<div style="margin-top:5px; margin-left:10px; float:left;">
				<a class="btn btn-success btn-xs" href="javascript:void()" onclick="randomPassword(8)" style="float:left;"> Generate Password </a> 
				<a class="btn btn-danger btn-xs" href="javascript:void()" id="hideKey" onclick="hidePassword(1)" style="display:none; float:left; margin-left:10px;"> Hide </a> 
						</div>
							
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Confirm Password</div>

													<div class="profile-info-value">
														<input type="text" name="password1" class="col-xs-4 col-sm-4" id="password1" />
														
														
													
						
						
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name">  </div>

													<div class="profile-info-value">
														<input type="submit"  class="btn btn-sm btn-info" name="changePassword" value="Change Password" />
													</div>
												</div>
												
												
												
												
								
								     </div>
								   </form>
									
							
							</div>
									
									
										
										
										
										
									</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
		
		<script>
				
				function validate()
				{
				
				var password =  document.getElementById('password');
				var password1 =  document.getElementById('password1');
				
				
				if(password.value.length<8)
				{
				
				 alert("Password length should minimum of 8 characters.");
				 password.focus();
				 return false;
				}
				
				if(password.value!==password1.value)
				{
				
				 alert("Password and confirm password are not same.");
				 password1.focus();
				 return false;
				}
				
				
				
				}
					
					 function hidePassword(id)
	 {
	 
	   if(id==1)
	   {
	    document.getElementById('password').setAttribute('type','password');
	    document.getElementById('password1').setAttribute('type','password');	
		document.getElementById('hideKey').innerHTML = 'View';	
		document.getElementById('hideKey').setAttribute('onClick','hidePassword(2)');	
		}
		else if(id==2)
		{
	    document.getElementById('password').setAttribute('type','text');
	    document.getElementById('password1').setAttribute('type','text');	
		document.getElementById('hideKey').innerHTML = 'Hide';	
		document.getElementById('hideKey').setAttribute('onClick','hidePassword(1)');		
		}
		
		
		
	
	 }
	 
	 function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }


    document.getElementById('password').setAttribute('type','text');
	document.getElementById('password1').setAttribute('type','text');	
	 
	document.getElementById('password').value = pass;
	document.getElementById('password1').value = pass;
	
	document.getElementById('hideKey').innerHTML = 'Hide';
	document.getElementById('hideKey').style.display = 'block';
	//document.getElementById('hideKey').setAttribute('onClick','hidePassword(1)');		
	document.getElementById('password').focus();
//	alert(pass);
   // return pass;
}

</script>


@endsection  