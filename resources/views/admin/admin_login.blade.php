<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - Digiwaxx Admin</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/bootstrap.css')}}" />
		<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/font-awesome.css')}}" />

		<!-- text fonts -->
		<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace-fonts.css')}}" />

		<!-- ace styles -->
		<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace.css')}}" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" />
		<![endif]-->
		<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace-rtl.css')}}" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout blur-login">
	<?php error_reporting(0); ?>
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
								<!--	<i class="ace-icon fa fa-leaf green"></i>-->
									<span class="red">Digiwaxx</span>
									<span class="white" id="id-text2">Admin</span>
								</h1>
							<!--	<h4 class="blue" id="id-company-text">&copy; Company Name</h4>-->
							</div>

							<div class="space-6"></div>


   <?php
    	if((isset($rememberDetails['numRows'])) && ($rememberDetails['numRows']>0))
		{
		
		  $remember = 1;
		
		  $uname = $rememberDetails['userData'][0]->uname;
		  $pword = $rememberDetails['userData'][0]->pword;
	
		  
		}
		else
		{
		  $remember = 0;
		  $uname = '';
		  $pword = '';
		}
		
		
	if(isset($_GET['mailSent']) || isset($_GET['forgotError']) || isset($_GET['invalidEmail']))
	{
	  $visible1 = '';
	  $visible2 = 'visible';
	}
	else
	{
	  $visible1 = 'visible';
	  $visible2 = '';
	}
		
		?>      

							<div class="position-relative">
								<div id="login-box" class="login-box widget-box no-border <?php echo $visible1; ?>">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Please Enter Your Information
											</h4>

                                            @if (\Session::has('danger'))
                                            <div class="alert alert-danger">
                                                <ul>
                                                    <li>{!! \Session::get('danger') !!}</li>
                                                </ul>
                                            </div>
                                         @endif

										 @if (\Session::has('danger_no_email'))
                                            <div class="alert alert-danger">
                                                <ul>
                                                    <li>{!! \Session::get('danger_no_email') !!}</li>
                                                </ul>
                                            </div>
                                         @endif

										 @if (\Session::has('success_avail_email'))
                                            <div class="alert alert-success">
                                                <ul>
                                                    <li>{!! \Session::get('success_avail_email') !!}</li>
                                                </ul>
                                            </div>
                                         @endif

										 @if (\Session::has('password_changed'))
										<div class="alert alert-success">
										<ul>
											<li>{!! \Session::get('password_changed') !!}</li>
										</ul>
										</div>
										@endif

											<div class="space-6"></div>

											<form action="{{ route('validate_admin_login') }}" method="POST" autocomplete="off">
                                            @csrf
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input required type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email/Username" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
															<i class="ace-icon fa fa-user"></i>

														</span>
														
														@error('email')
															<span class="invalid-feedback" role="alert">
																<strong>{{ $message }}</strong>
															</span>
														 @enderror
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input required type="password" class="form-control  @error('password') is-invalid @enderror" placeholder="Password" name="password" value="<?php echo $pword; ?>" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
														@error('password')
															<span class="invalid-feedback" role="alert">
																<strong>{{ $message }}</strong>
															</span>
														@enderror
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" name="rememberMe"  value="1" class="ace" <?php if($remember==1) { ?> checked="checked" <?php } ?> />
															<span class="lbl"> Remember Me</span>
														</label>

														<button type="submit" name="login" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											

											
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix" style="">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>

											
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border <?php echo $visible2; ?>">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											
											<?php if(isset($alert_message)) { ?><p><?php echo $alert_message; ?></p><?php } ?>
											
											<p>
												Enter your email and to receive instructions
											</p>

											<form action="{{ route('AdminForgetNotification_function') }}" method="post">
											@csrf
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" name="forget_email" required class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button type="submit" name="forgotPassword" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												New User Registration
											</h4>

											<div class="space-6"></div>
											<p> Enter your details to begin: </p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													<label class="block">
														<input type="checkbox" class="ace" />
														<span class="lbl">
															I accept the
															<a href="#">User Agreement</a>
														</span>
													</label>

													<div class="space-24"></div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Reset</span>
														</button>

														<button type="button" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Register</span>

															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Back to login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

							<!--<div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
							</div>-->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='{{ asset('assets_admin/assets/js/jquery.js')}}'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets_admin/assets/js/jquery.mobile.custom.js')}}'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
		</script>
	</body>
</html>
