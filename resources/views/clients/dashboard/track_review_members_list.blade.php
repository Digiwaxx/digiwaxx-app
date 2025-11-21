@extends('layouts.client_dashboard')

@section('content')

<!-- <style>

 

.nopadding { padding:0px !important; }

.amrFile { display:none !important; }

.form-group { margin-bottom:30px; } 



</style>  -->
 <section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		 <div class="container">
			<div class="row">
			<div class="col-12">
            <div class="dash-heading">
                <h2>My Dashboard</h2>
              </div>
            <div class="tabs-section">

                
                <!-- start middle -->

                </div><!-- eof header -->
       <div class="client-block after-login">
       
       		       <!-- MY RECORD POOL MENU, NOTIFICATIONS AND MY TRACKS FOR SMALLER DEVICES -->
       
       		<div class="container">
								<div class="row">
									<div class="col-xs-12">
											<?php // echo $pageTitle; ?>
									<div>
												<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name" style="width:20%;">S. No.</div>

													<div class="profile-info-value">
														 Member
													</div>
												</div>
													<?php 
													
													if($members['numRows']>0)
													{
													
													$i=1; foreach($members['data'] as $member) { ?>
												

                                                <div class="profile-info-row">

                                                    <div class="profile-info-name" style="width:20%;"><?php echo $i; ?>
                                                    </div>



                                                    <div class="profile-info-value">

                                                        <a target="_blank" href="<?php echo url('Client_track_review_member?memberId=' . $member->member); ?>" role="button"
                                                            class="green">

                                                            <?php if(isset($member->stagename) && !empty($member->stagename)) { echo urldecode($member->stagename); }else{ echo urldecode($member->fname); } ?>

                                                        </a>
                                                        
                                                        <a class="btn btn-theme btn-gradient client-connect-member" target="_blank" href="<?php echo url('Client_messages_conversation?mid=' . $member->member); ?>">Connect with Dj</a>

                                                    </div>

                                                </div>
								
														<?php $i++; } 
														
														} else { ?>
														
														<div class="profile-info-row">
													<div class="profile-info-name" style="width:20%;"></div>

													<div class="profile-info-value">
	                                                    No Data found.							
													</div>
												</div>
								
														<?php } ?>

														
													</div>
													
													
													
													
													
													
														</div><!-- /.span -->
								</div><!-- /.row -->

							
							
							</div>
							</div>
							</div>




                <!--  end middle -->


				<!--include('clients.dashboard.includes.my-tracks')-->
				
			</div>
		</div>
	</div>
	</div>
	</div>
		
 </section>


@endsection