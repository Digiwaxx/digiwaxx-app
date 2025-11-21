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
                            <li class="active">Edit Client</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
					    <?php if(!empty($clients['data'][0])){
					    	//echo '<pre>';print_r($clients);die();
					    	?>
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
									
									
									<form class="form-horizontal" action="" method="post">
                                        @csrf
									<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name"> Username </div>

													<div class="profile-info-value">
													
														<?php echo urldecode($client->uname); ?>
														
					<a class="btn btn-success btn-xs" href="<?php echo url("admin/client_change_password?cid=".$_GET['cid']); ?>" style="margin-left:10px;"> Change Password </a> 
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Email </div>

													<div class="profile-info-value">
													
														<?php echo urldecode($client->email); ?>
													</div>
												</div>
												
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Edited  </div>

													<div class="profile-info-value">
													
														<?php 
														if($client->editedby>0)
														{
														$editedOn = explode(' ',$client->edited);
														$editedDate = explode('-',$editedOn[0]);
														$editedDate = $editedDate[1].'-'.$editedDate[2].'-'.$editedDate[0];
														if(!empty($adminInfo[0]->name)){
															echo 'Edited by '.$adminInfo[0]->name.', on '.$editedDate; 

														}
														else{

															echo 'Edited by Unknown '.$editedDate; 
														}
														
														}
														?>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Company </div>

													<div class="profile-info-value">
													<input type="text" name="companyName" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->name); ?>" />
														
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Contact Name</div>

													<div class="profile-info-value">
														<input type="text" name="name" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->ccontact); ?>" />
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Phone </div>

													<div class="profile-info-value">
														<input type="text" name="phone" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->phone); ?>" />
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Mobile No.</div>

													<div class="profile-info-value">
														<input type="text" name="mobileNo" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->mobile); ?>" />
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Address1 </div>

													<div class="profile-info-value">
														<input type="text" name="address1" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->address1); ?>" />
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Address2 </div>

													<div class="profile-info-value">
														<input type="text" name="address2" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->address2); ?>" />
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> City </div>

													<div class="profile-info-value">
														<input type="text" name="city" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->city); ?>" />
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> State </div>

													<div class="profile-info-value">
														<input type="text" name="state" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->state); ?>" />
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Country </div>

													<div class="profile-info-value">
														<input type="text" name="country" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->country); ?>" />
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Zip </div>

													<div class="profile-info-value">
														<input type="text" name="zip" class="col-xs-4 col-sm-4" value="<?php echo $client->zip; ?>" />
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Website </div>

													<div class="profile-info-value">
														<input type="text" name="website" class="col-xs-4 col-sm-4" value="<?php echo urldecode($client->website); ?>" />
													</div>
												</div>

												<div class="profile-info-row">
													<div class="profile-info-name"> Can receive track review feedback emails? </div>

													<div class="profile-info-value">
														<input value="1" type="checkbox" id="activate_track_review_feedback" name="activate_track_review_feedback" <?php echo (!empty($client->trackReviewEmailsActivated) && $client->trackReviewEmailsActivated == 1) ? 'checked':''; ?> >
													</div>
												</div>												
												<div class="profile-info-row">
													<div class="profile-info-name">  </div>

													<div class="profile-info-value">
														<input type="submit"  class="btn btn-sm btn-info" name="update" value="Update Client" />
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
						<?php }else{echo "Client doesn't exists.";}?>
					</div><!-- /.page-content -->
                    @endsection