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
					<i class="ace-icon fa fa-users user-icon"></i>
					Members
				</li>
				
			</ul><!-- /.breadcrumb -->
			
            <!-- #section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search">
				@csrf
                    <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
                            <option <?php if (strcmp($sortBy, 'firstName') == 0) { ?> selected="selected" <?php } ?> value="firstName">First Name</option>
                            <option <?php if (strcmp($sortBy, 'lastName') == 0) { ?> selected="selected" <?php } ?> value="lastName">Last Name</option>
                            <option <?php if (strcmp($sortBy, 'stageName') == 0) { ?> selected="selected" <?php } ?> value="stageName">Stage Name</option>
                            <option <?php if (strcmp($sortBy, 'email') == 0) { ?> selected="selected" <?php } ?> value="email">Email</option>
                            <option <?php if (strcmp($sortBy, 'username') == 0) { ?> selected="selected" <?php } ?> value="username">Username</option>
                            <option <?php if (strcmp($sortBy, 'phone') == 0) { ?> selected="selected" <?php } ?> value="phone">Phone</option>
                            <option <?php if (strcmp($sortBy, 'city') == 0) { ?> selected="selected" <?php } ?> value="city">City</option>
                            <option <?php if (strcmp($sortBy, 'state') == 0) { ?> selected="selected" <?php } ?> value="state">State</option>
                            <option <?php if (strcmp($sortBy, 'registered') == 0) { ?> selected="selected" <?php } ?> value="registered">Registered On</option>
                            <option <?php if (strcmp($sortBy, 'lastLogin') == 0) { ?> selected="selected" <?php } ?> value="lastLogin">Last Login</option>
                        </select>
                    </span>
                    <label class="hidden-md hidden-sm hidden-xs">Order By</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
                            <option <?php if ($sortOrder == 1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
                            <option <?php if ($sortOrder == 2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
                        </select>
                    </span>
                    <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
                            <option <?php if ($numRecords == 10) { ?> selected="selected" <?php } ?> value="10">10</option>
                            <option <?php if ($numRecords == 30) { ?> selected="selected" <?php } ?> value="30">30</option>
                            <option <?php if ($numRecords == 50) { ?> selected="selected" <?php } ?> value="50">50</option>
                            <option <?php if ($numRecords == 100) { ?> selected="selected" <?php } ?> value="100">100</option>
                            <option <?php if ($numRecords == 500) { ?> selected="selected" <?php } ?> value="500">500</option>
                        </select>
                    </span>
                </form>
            </div><!-- /.nav-search -->
		</div>

		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
            <form class="form-inline searchForm" id="searchForm">
			@csrf
                <div class="row">
                    <div class="col-xs-12 searchDiv">
                        <div class="row">
                            <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
                            <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
                            <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                    <label class="input-group-addon">First Name</label>
                                    <input type="text" class="nav-search-input form-control" id="firstName" name="firstName" value="<?php echo (!empty($searchFirstName)) ? $searchFirstName:''; ?>" />
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                    <label class="input-group-addon">Last Name</label>
                                    <input type="text" class="nav-search-input form-control" id="lastName" name="lastName" value="<?php echo (!empty($searchLastName)) ?$searchLastName : ''; ?>" />
                                </div>
                            </div> 
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                <label class="input-group-addon">Stage Name</label>
                                <input type="text" class="nav-search-input form-control" id="stageName" name="stageName" value="<?php echo (!empty($searchStageName)) ? $searchStageName : ''; ?>" />
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                <label class="input-group-addon">Email</label>
                                <input type="text" class="nav-search-input form-control" id="email" name="email" value="<?php echo (!empty($searchEmail)) ? $searchEmail : ''; ?>" />
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                <label class="input-group-addon">Username</label>
                                <input type="text" class="nav-search-input form-control" id="username" name="username" value="<?php echo (!empty($searchUsername)) ? $searchUsername : ''; ?>" />
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                <label class="input-group-addon">Phone</label>
                                <input type="text" class="nav-search-input form-control" id="phone" name="phone" value="<?php echo (!empty($searchPhone)) ? $searchPhone : ''; ?>" />
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                <label class="input-group-addon">City</label>
                                <input type="text" class="nav-search-input form-control" id="city" name="city" value="<?php echo (!empty($searchCity)) ? $searchCity : ''; ?>" />
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                <label class="input-group-addon">State</label>
                                <input type="text" class="nav-search-input form-control" id="state" name="state" value="<?php echo (!empty($searchState)) ? $searchState : ''; ?>" />
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-4 col-xs-12">
                                <div class="input-group">
                                    <label class="input-group-addon">Zip</label>
                                    <input type="text" class="nav-search-input form-control" id="zip" name="zip" value="<?php echo (!empty($searchZip)) ? $searchZip : ''; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="djMixer" id="djMixer" value="1" <?php echo $searchdjMixer ? 'checked' : ''; ?>>
                                        <span class="lbl"> DJ/Mixer</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="radioStation" id="radioStation" value="1" <?php echo $searchradioStation ? 'checked' : ''; ?>>
                                        <span class="lbl"> Radio Station (Non-DJ/Mixer)</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input class="ace ace-checkbox-2" type="checkbox" name="massMedia" id="massMedia" value="1" <?php echo $searchmassMedia ? 'checked' : ''; ?>>
                                        <span class="lbl"> Mass Media</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="recordLabel" id="recordLabel" value="1" <?php echo $searchrecordLabel ? 'checked' : ''; ?>>
                                        <span class="lbl"> Record Label</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="management" id="management" value="1" <?php echo $searchmanagement ? 'checked' : ''; ?>>
                                        <span class="lbl"> Management</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="clothingApparel" id="clothingApparel" value="1" <?php echo $searchclothingApparel ? 'checked' : ''; ?>>
                                        <span class="lbl"> Clothing/Apparel</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="promoter" id="promoter" value="1" <?php echo $searchpromoter ? 'checked' : ''; ?>>
                                        <span class="lbl"> Promoter</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="specialServices" id="specialServices" value="1" <?php echo $searchspecialServices ? 'checked' : ''; ?>>
                                        <span class="lbl"> Special Services</span>
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="d-block">
                                        <input type="checkbox" class="ace ace-checkbox-2" name="productionTalent" id="productionTalent" value="1" <?php echo $searchproductionTalent ? 'checked' : ''; ?>>
                                        <span class="lbl"> Production/Talent</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-6 mt-3">
                                <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
                                <input type="submit" value="Search" name="search" />
                                <input type="button" value="Reset" onclick="searchReset1()" />
                                <?php $rest_url=route('adminMembersListing'); ?>
                                <input type="hidden" id="reset_url" value="<?php echo $rest_url;?>">
                            </div>
                        </div>
                    </div>
                </div><!-- /.page-header -->
            </form>
            <style>
                .d-block {
                    display: block;
                }
                label.d-block {
                    width: 100%;
                }
                .mt-3 {
                    margin-top: 10px;
                }
            </style>
			<div class="space-6"></div>
			<div class="row">
			   <div class="col-xs-12">
				  <!-- PAGE CONTENT BEGINS -->
				  <div class="row">
					 <div class="col-xs-12">
						<?php
						   if (isset($alert_message)) { ?>
						<div class="<?php echo $alert_class; ?>">
						   <?php echo $alert_message; ?>
						   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						</div>
						<?php } ?>
						<a href="<?php echo url("admin/export-members"); ?>" class="btn btn-info btn-sm" target="_blank" style="margin-bottom:6px; float:right;">
						<span class="glyphicon glyphicon-export"></span> Export
						</a>
						<table id="sample-table-1" class="table table-striped table-bordered table-hover">
						   <thead>
							  <tr>
								 <th class="center" width="60">
									S. No.
								 </th>
								 <th>Name</th>
								 <th class="hidden-sm hidden-xs">Email</th>
								 <th>Membership</th>
								 <th>Package</th>
								 <th class="hidden-sm hidden-xs">Member Type</th>
								 <th class="hidden-md hidden-sm hidden-xs" width="240">Last Log On</th>
								 <th width="190">Action</th>
							  </tr>
						   </thead>
						   <tbody>
							  <?php
							 // dd($members);
								 $i = $start + 1;
								 if ($members['numRows'] > 0) {
									 foreach ($members['data'] as $member) {
								 ?>
							  <tr>
								 <td class="center">
									<input type="checkbox" name="members_ids[]" value="<?php echo $member->id; ?>" />
									<?php echo $i; ?>
								 </td>
								 <td>
									<?php echo ucfirst(urldecode($member->fname));  ?>
								 </td>
								 <td class="hidden-sm hidden-xs"><?php echo urldecode($member->email);  ?></td>
								 <td><?php
									//  echo "ssssssssssss";
									//  print_r($membershipDetails[$member->id]);
									if ($membershipDetails[$member->id]['numRows'] > 0) {
										// echo $membershipDetails[$member->id]['data'][0]->package_Id;
										if ($membershipDetails[$member->id]['data'][0]->package_Id == 3) {
											echo 'Purple';
										} else if ($membershipDetails[$member->id]['data'][0]->package_Id == 2) {
											echo 'Silver';
										} else {
											echo 'Silver';
										}
									} else {
										echo 'Silver';
									}
									// echo $member->uname;  
									?></td>
								<td><?php echo $member->member_package; ?></td>	
								 <td class="hidden-sm hidden-xs"><?php
									$memberTypes = array();
									if ($member->dj_mixer == 1) {
										$memberTypes[] =  "DJ Mixer";
									}
									if ($member->radio_station == 1) {
										$memberTypes[] = "Radio Station";
									}
									if ($member->record_label == 1) {
										$memberTypes[] = "Record Label";
									}
									if ($member->management == 1) {
										$memberTypes[] = "Management";
									}
									if ($member->clothing_apparel == 1) {
										$memberTypes[] = "Clothing Apparel";
									}
									if ($member->mass_media == 1) {
										$memberTypes[] = "Mass Media";
									}
									if ($member->production_talent == 1) {
										$memberTypes[] = "Production Talent";
									}
									if ($member->promoter == 1) {
										$memberTypes[] = "Promoter";
									}
									if ($member->special_services == 1) {
										$memberTypes[] = "Special Services";
									}
									$countMemberTypes = count($memberTypes);
									if ($countMemberTypes == 1) {
										echo $memberTypes[0];
									} else if ($countMemberTypes > 1) {
										echo $memberTypes[0];
									?>
									<span class="badge badge-warning"><?php echo $countMemberTypes; ?></span>
									<?php
									   }
									   unset($memberTypes);
									   ?>
								 </td>
								 <td class="hidden-md hidden-sm hidden-xs"><?php
									$dt  = $member->lastlogon;
									if (strcmp($dt, '0000-00-00 00:00:00') != 0) {
										$yr = strval(substr($dt, 0, 4));
										$mo = strval(substr($dt, 5, 2));
										$da = strval(substr($dt, 8, 2));
										$hr = strval(substr($dt, 11, 2));
										$mi = strval(substr($dt, 14, 2));
										$se = strval(substr($dt, 17, 2));
										echo date("l M/d/Y h:i A", mktime((int)$hr, (int)$mi, 0, (int)$mo, (int)$da, (int)$yr)) . " EST";
									}
									?></td>
								 <td>
									<div class="btn-group">
									   <a href="<?php echo url("admin/member_digicoins?mid=" . $member->id); ?>" title="Digicoins" class="btn btn-xs btn-warning">
									   <i class="ace-icon fa fa-money bigger-120"></i>
									   </a>
									   <a href="<?php echo url("admin/member_view?mid=" . $member->id); ?>" title="View Member" class="btn btn-xs btn-success">
									   <i class="ace-icon fa fa-align-justify bigger-120"></i>
									   </a>
									   <a href="<?php echo url("admin/member_edit?mid=" . $member->id); ?>" title="Edit Member" class="btn btn-xs btn-info">
									   <i class="ace-icon fa fa-pencil bigger-120"></i>
									   </a>
									   <a href="<?php echo url("admin/member_change_password?mid=" . $member->id); ?>" title="Reset Password" class="btn btn-xs btn-info">
									   <i class="ace-icon fa fa-key bigger-120"></i>
									   </a>
									   <button title="Settings" type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#settingsBox<?php echo $member->id; ?>"> <i class="ace-icon fa fa-cog bigger-120"></i>
									   </button>
									   <button title="Delete Member" onclick="deleteRecord('members','<?php echo $member->id; ?>','Confirm delete <?php echo $member->uname; ?> ')" class="btn btn-xs btn-danger">
									   <i class="ace-icon fa fa-trash-o bigger-120"></i>
									   </button>
									</div>
									<!--membership settings-->
									<div id="settingsBox<?php echo $member->id; ?>" class="modal fade in">
									   <div class="modal-dialog">
										  <div class="modal-content">
											 <div class="modal-header no-padding">
												<div class="table-header">
												   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												   </button>
												   <?php echo ucfirst(urldecode($member->fname));  ?> Purchase Membership
												</div>
											 </div>
											 <div class="modal-body no-padding">
												<div class="space-10"></div>
												<div class="row">
												   <div class="col-sm-8 col-sm-offset-2">
													  <div class="form-group" id="addMembershipForm<?php echo $member->id; ?>">
														 <div class="col-sm-9">
															<select class="form-control" id="membershipDuration<?php echo $member->id; ?>">
															   <option value="1">1 Month</option>
															   <option value="4">3 Months</option>
															   <option value="2">6 Months</option>
															   <option value="3">1 Year</option>
															</select>
														 </div>
														 <div class="col-sm-3">
															<input type="submit" onclick="addMembership('<?php echo $member->id; ?>')" class="btn btn-sm btn-warning" />
														 </div>
													  </div>
												   </div>
												   <div class="col-sm-10 col-sm-offset-1" style="margin-top:10px;" id="addMembershipResponse<?php echo $member->id; ?>">
													  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
														 <thead>
															<tr>
															   <th class="text-center" width="100">
																  S. No.
															   </th>
															   <th>Duration</th>
															   <th>Start Date</th>
															   <th>End Date</th>
															   <th class="text-center">Action</th>
															</tr>
														 </thead>
														 <tbody>
															<?php
															   if ($membershipDetails[$member->id]['numRows'] > 0) {
																   $x = 1;
																   foreach ($membershipDetails[$member->id]['data'] as $row) {
															   ?>
															<tr>
															   <td class="text-center"><?php echo $x; ?></td>
															   <td><?php
																  $duration = '';
																  if ($row->duration_Id == 1) {
																	  $duration = '1 Month';
																  } else if ($row->duration_Id == 2) {
																	  $duration = '6 Months';
																  } else if ($row->duration_Id == 3) {
																	  $duration = '1 year';
																  } else if ($row->duration_Id == 4) {
																	  $duration = '3 Months';
																  }
																  echo $duration;
																  ?></td>
															   <td><?php
																  /* $dateTime = explode(' ',$row->subscribed_date_time);
																  $date = explode('-',$dateTime[0]);
																  echo $date = $date[2].'-'.$date[1].'-'.$date[0];
																  */
																  $startDate = explode('-', $row->startDate);
																  echo $startDate = $startDate[2] . '-' . $startDate[1] . '-' . $startDate[0];
																  ?></td>
															   <td><?php
																  $endDate = explode('-', $row->endDate);
																  echo $endDate = $endDate[2] . '-' . $endDate[1] . '-' . $endDate[0];
																  ?></td>
															   <td class="text-center">
																  <button title="Delete Member" onclick="deleteMemberSubsciption('members', <?php echo $row->member_Id; ?>, <?php echo $row->subscription_Id; ?>,'Are you sure to cancel this subscription?')" class="btn btn-xs btn-danger">
																  <i class="ace-icon fa fa-trash-o bigger-120"></i>
																  </button>
															   </td>
															</tr>
															<?php $x++;
															   }
															   } else { ?>
															<tr>
															   <td colspan="5">No Data found.</td>
															</tr>
															<?php } ?>
														 </tbody>
													  </table>
												   </div>
												</div>
												<div class="space-10"></div>
											 </div>
											 <div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
												<i class="ace-icon fa fa-times"></i>
												Close
												</button>
											 </div>
										  </div>
										  <!-- /.modal-content -->
									   </div>
									   <!-- /.modal-dialog -->
									</div>
								 </td>
							  </tr>
							  <?php $i++;
								 }
								 if ($numPages > 1) { ?>
							  <tr>
								 <td colspan="8">
									<ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
									   <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','1')">
										  << </a> 
									   </li>
									   <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo - 1; ?>')">
										  < </a> 
									   </li>
									   <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
									   <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $currentPageNo + 1; ?>')"> > </a></li>
									   <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage . $link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
									</ul>
								 </td>
							  </tr>
							  <?php }
								 } else {
									 ?>
							  <tr>
								 <td colspan="8">No Data found.</td>
							  </tr>
							  <?php
								 } ?>
						   </tbody>
						</table>
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
		</div><!-- /.page-content -->
	</div>
</div>

        <script>
            function deleteMemberSubsciption(page, mid, sid, msg) {
                if (confirm(msg)) {
                    $.ajax({
                        url: "members?mode=deleteMemberSubsciption&mid=" + mid + "&sid=" + sid,
                        success: function(result) {
                            document.getElementById('addMembershipResponse' + mid).innerHTML = result;
                        }
                    });
                }
            }

            function get_selected_data() {
                var sortBy = document.getElementById('sortBy').value;
                var sortOrder = document.getElementById('sortOrder').value;
                var numRecords = document.getElementById('numRecords').value;
                var firstName = document.getElementById('firstName').value;
                var lastName = document.getElementById('lastName').value;
                var stageName = document.getElementById('stageName').value;
                var email = document.getElementById('email').value;
                var username = document.getElementById('username').value;
                var phone = document.getElementById('phone').value;
                var city = document.getElementById('city').value;
                var state = document.getElementById('state').value;
                var zip = document.getElementById('zip').value;
                window.location = "members?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords + "&firstName=" + firstName + "&lastName=" + lastName + "&stageName=" + stageName + "&email=" + email + "&username=" + username + "&phone=" + phone + "&city=" + city + "&state=" + state + "&zip=" + zip;
            }

            function addMembership(memberId) {
                var validity = document.getElementById('membershipDuration' + memberId).value;
                $.ajax({
                    url: "members?memberId=" + memberId + "&validity=" + validity + "&addMembership=1",
                    success: function(result) {
                        /*row = JSON.parse(result);
							var responseMessage = '';
							var responseColor =  '';
						
							if(row.response==1)
							{
							responseColor = '#090';  					
							document.getElementById('addMembershipForm'+memberId).innerHTML = '';
                            }
							else
							{
	                        responseColor = '#FF0000';  						
							}
							
							document.getElementById('addMembershipResponse'+memberId).style.color = responseColor;
							*/
                        document.getElementById('addMembershipResponse' + memberId).innerHTML = result;
                    }
                });
            }
            
            function searchReset1(){
                $('#searchForm input[type=checkbox]').prop('checked', false);
                $('#searchForm').trigger("reset");
                var url= $("#reset_url").val();
                window.location.href= url;
            }
        </script>

@endsection           
	