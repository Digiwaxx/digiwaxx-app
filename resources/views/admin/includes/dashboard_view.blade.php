<div class="main-content">
	<div class="main-content-inner">
		<!-- #section:basics/content.breadcrumbs -->
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try {
					ace.settings.check('breadcrumbs', 'fixed')
				} catch (e) {}
			</script>

			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Dashboard</li>
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
			<!-- #section:settings.box -->
			<div class="ace-settings-container" id="ace-settings-container">
				<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
					<i class="ace-icon fa fa-cog bigger-130"></i>
				</div>

				<div class="ace-settings-box clearfix" id="ace-settings-box">
					<div class="pull-left width-50">
						<!-- #section:settings.skins -->
						<div class="ace-settings-item">
							<div class="pull-left">
								<select id="skin-colorpicker" class="hide">
									<option data-skin="no-skin" value="#438EB9">#438EB9</option>
									<option data-skin="skin-1" value="#222A2D">#222A2D</option>
									<option data-skin="skin-2" value="#C6487E">#C6487E</option>
									<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
								</select>
							</div>
							<span>&nbsp; Choose Skin</span>
						</div>

						<!-- /section:settings.skins -->

						<!-- #section:settings.navbar -->
						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
						</div>

						<!-- /section:settings.navbar -->

						<!-- #section:settings.sidebar -->
						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
						</div>

						<!-- /section:settings.sidebar -->

						<!-- #section:settings.breadcrumbs -->
						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
						</div>

						<!-- /section:settings.breadcrumbs -->

						<!-- #section:settings.rtl -->
						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
						</div>

						<!-- /section:settings.rtl -->

						<!-- #section:settings.container -->
						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
							<label class="lbl" for="ace-settings-add-container">
								Inside
								<b>.container</b>
							</label>
						</div>

						<!-- /section:settings.container -->
					</div><!-- /.pull-left -->

					<div class="pull-left width-50">
						<!-- #section:basics/sidebar.options -->
						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
							<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
						</div>

						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
							<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
						</div>

						<div class="ace-settings-item">
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
							<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
						</div>

						<!-- /section:basics/sidebar.options -->
					</div><!-- /.pull-left -->
				</div><!-- /.ace-settings-box -->
			</div><!-- /.ace-settings-container -->

			<!-- /section:settings.box -->
			<div class="page-header">
				<h1>
					Dashboard
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						overview &amp; stats
					</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>
						</button>

						<i class="ace-icon fa fa-check green"></i>

						Welcome to
						<strong class="green">
							Ace
							<small>(v1.3.2)</small>
						</strong>,
						the lightweight, feature-rich and easy to use admin template.
					</div>

					<div class="row">
						<div class="space-6"></div>

						<div class="col-xs-12 infobox-container">
							<!-- #section:pages/dashboard.infobox -->
							<div class="infobox infobox-green">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-comments"></i>
								</div>

								<div class="infobox-data">
									<span class="infobox-data-number">32</span>
									<div class="infobox-content">comments + 2 reviews</div>
								</div>

								<!-- #section:pages/dashboard.infobox.stat -->
								<div class="stat stat-success">8%</div>

								<!-- /section:pages/dashboard.infobox.stat -->
							</div>

							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-twitter"></i>
								</div>

								<div class="infobox-data">
									<span class="infobox-data-number">11</span>
									<div class="infobox-content">new followers</div>
								</div>

								<div class="badge badge-success">
									+32%
									<i class="ace-icon fa fa-arrow-up"></i>
								</div>
							</div>

							<div class="infobox infobox-pink">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-shopping-cart"></i>
								</div>

								<div class="infobox-data">
									<span class="infobox-data-number">8</span>
									<div class="infobox-content">new orders</div>
								</div>
								<div class="stat stat-important">4%</div>
							</div>

							<div class="infobox infobox-red">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-flask"></i>
								</div>

								<div class="infobox-data">
									<span class="infobox-data-number">7</span>
									<div class="infobox-content">experiments</div>
								</div>
							</div>

							<div class="infobox infobox-orange2">
								<!-- #section:pages/dashboard.infobox.sparkline -->
								<div class="infobox-chart">
									<span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
								</div>

								<!-- /section:pages/dashboard.infobox.sparkline -->
								<div class="infobox-data">
									<span class="infobox-data-number">6,251</span>
									<div class="infobox-content">pageviews</div>
								</div>

								<div class="badge badge-success">
									7.2%
									<i class="ace-icon fa fa-arrow-up"></i>
								</div>
							</div>

							<div class="infobox infobox-blue2">
								<div class="infobox-progress">
									<!-- #section:pages/dashboard.infobox.easypiechart -->
									<div class="easy-pie-chart percentage" data-percent="42" data-size="46">
										<span class="percent">42</span>%
									</div>

									<!-- /section:pages/dashboard.infobox.easypiechart -->
								</div>

								<div class="infobox-data">
									<span class="infobox-text">traffic used</span>

									<div class="infobox-content">
										<span class="bigger-110">~</span>
										58GB remaining
									</div>
								</div>
							</div>

							<!-- /section:pages/dashboard.infobox -->
							<div class="space-6"></div>

							<!-- #section:pages/dashboard.infobox.dark -->
							<div class="infobox infobox-green infobox-small infobox-dark">
								<div class="infobox-progress">
									<!-- #section:pages/dashboard.infobox.easypiechart -->
									<div class="easy-pie-chart percentage" data-percent="61" data-size="39">
										<span class="percent">61</span>%
									</div>

									<!-- /section:pages/dashboard.infobox.easypiechart -->
								</div>

								<div class="infobox-data">
									<div class="infobox-content">Task</div>
									<div class="infobox-content">Completion</div>
								</div>
							</div>

							<div class="infobox infobox-blue infobox-small infobox-dark">
								<!-- #section:pages/dashboard.infobox.sparkline -->
								<div class="infobox-chart">
									<span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
								</div>

								<!-- /section:pages/dashboard.infobox.sparkline -->
								<div class="infobox-data">
									<div class="infobox-content">Earnings</div>
									<div class="infobox-content">$32,000</div>
								</div>
							</div>

							<div class="infobox infobox-grey infobox-small infobox-dark">
								<div class="infobox-icon">
									<i class="ace-icon fa fa-download"></i>
								</div>

								<div class="infobox-data">
									<div class="infobox-content">Downloads</div>
									<div class="infobox-content">1,205</div>
								</div>
							</div>

							<!-- /section:pages/dashboard.infobox.dark -->
						</div>

						<div class="vspace-12-sm"></div>
						<div class="col-xs-12 users-stats">
							<div class="widget-box">
								<div class="widget-header widget-header-flat widget-header-small">
									<h5 class="widget-title">
										<i class="ace-icon fa fa-users"></i>
										Dj Members
									</h5>
								</div>
								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->approved_members); ?></span>
											<div class="infobox-content">All Approved</div>
										</div>
									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->unapproved_members); ?></span>
											<div class="infobox-content">All pending approval</div>
										</div>
									</div>

									<div class="infobox infobox-blue">
										<div class="infobox-icon d-none">
											<i class="ace-icon fa fa-shopping-cart"></i>
										</div>
										<div class="infobox-data d-none">
											<span class="infobox-data-number">12</span>
											<div class="infobox-content">New orders</div>
										</div>
									</div>
								</div>

								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_members['numRows']) ?></span>
											<div class="infobox-content">Registered this week</div>
										</div>
									</div>

									<div class="infobox infobox-purple">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->week_approved_members); ?></span>
											<div class="infobox-content">Approved this week</div>
										</div>
									</div>

									<div class="infobox infobox-orange">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>

										<div class="infobox-data">
											<?php $pending_approval_week_member = $dashboard_members['numRows'] - $dashboard_track_and_user_stats['data'][0]->week_approved_members; ?>
											<span class="infobox-data-number"><?php echo number_format($pending_approval_week_member); ?></span>
											<div class="infobox-content">Pending approval this week</div>
										</div>
									</div>
								</div>

								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->month_register_members); ?></span>
											<div class="infobox-content">Registered this month</div>
										</div>
									</div>

									<div class="infobox infobox-purple">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->month_approved_members); ?></span>
											<div class="infobox-content">Approved this month</div>
										</div>
									</div>

									<div class="infobox infobox-orange">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>

										<div class="infobox-data">
											<?php $pending_approval_month_member = $dashboard_track_and_user_stats['data'][0]->month_register_members - $dashboard_track_and_user_stats['data'][0]->month_approved_members; ?>
											<span class="infobox-data-number"><?php echo number_format($pending_approval_month_member); ?></span>
											<div class="infobox-content">Pending approval this month</div>
										</div>
									</div>
								</div>
							</div><!-- /.widget-box -->
						</div><!-- /.col -->

						<div class="col-xs-12 users-stats">
							<div class="widget-box">
								<div class="widget-header widget-header-flat widget-header-small">
									<h5 class="widget-title">
										<i class="ace-icon fa fa-users"></i>
										Artists/Promoters
									</h5>
								</div>
								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->approved_clients); ?></span>
											<div class="infobox-content">All approved</div>
										</div>
									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->unapproved_clients); ?></span>
											<div class="infobox-content">All pending approval</div>
										</div>
									</div>

									<div class="infobox infobox-blue">
										<div class="infobox-icon d-none">
											<i class="ace-icon fa fa-shopping-cart"></i>
										</div>
										<div class="infobox-data d-none">
											<span class="infobox-data-number">8</span>
											<div class="infobox-content">New Orders</div>
										</div>
									</div>
								</div>

								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_clients['numRows']) ?></span>
											<div class="infobox-content">Registered this week</div>
										</div>
									</div>

									<div class="infobox infobox-purple">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->week_approved_clients); ?></span>
											<div class="infobox-content">Approved this week</div>
										</div>
									</div>

									<div class="infobox infobox-orange">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>

										<div class="infobox-data">
											<?php $pending_approval_week_clients = $dashboard_clients['numRows'] - $dashboard_track_and_user_stats['data'][0]->week_approved_clients; ?>
											<span class="infobox-data-number"><?php echo number_format($pending_approval_week_clients); ?></span>
											<div class="infobox-content">Pending approval this week</div>
										</div>
									</div>
								</div>

								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->month_register_clients); ?></span>
											<div class="infobox-content">Registered this month</div>
										</div>
									</div>

									<div class="infobox infobox-purple">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->month_approved_clients) ?></span>
											<div class="infobox-content">Approved this month</div>
										</div>
									</div>

									<div class="infobox infobox-orange">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>

										<div class="infobox-data">
											<?php $pending_approval_month_clients = $dashboard_track_and_user_stats['data'][0]->month_register_clients - $dashboard_track_and_user_stats['data'][0]->month_approved_clients; ?>
											<span class="infobox-data-number"><?php echo number_format($pending_approval_month_clients); ?></span>
											<div class="infobox-content">Pending approval this month</div>
										</div>
									</div>
								</div>
							</div><!-- /.widget-box -->
						</div><!-- /.col -->
					</div><!-- /.row -->

					<div class="row">
						<div class="vspace-12-sm"></div>
						<div class="col-xs-12">
							<div class="widget-box">
								<div class="widget-header widget-header-flat widget-header-small">
									<h5 class="widget-title">
										<i class="ace-icon fa fa-music"></i>
										Tracks Stats
									</h5>
								</div>
								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-music"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->total_tracks); ?></span>
											<div class="infobox-content"><a href="{{ url('/admin/tracks') }}">All Tracks</a></div>
										</div>
									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-music"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->priority_tracks); ?></span>
											<div class="infobox-content"><a href="{{ url('/admin/top_priority') }}">Priority Tracks</a></div>
										</div>
									</div>

									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-music"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->approved_tracks); ?></span>
											<div class="infobox-content"><a href="{{ url('/admin/tracks') }}">Approved Tracks</a></div>
										</div>
									</div>
								</div>
								<div class="col-xs-12 infobox-container users-container">
									<div class="infobox infobox-orange">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-music"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->total_tracks_submitted); ?></span>
											<div class="infobox-content"><a href="{{ url('/admin/submitted_tracks') }}">All Submitted Tracks</a></div>
										</div>
									</div>

									<div class="infobox infobox-purple">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-music"></i>
										</div>
										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo number_format($dashboard_track_and_user_stats['data'][0]->total_top_streaming_tracks); ?></span>
											<div class="infobox-content"><a href="{{ url('/admin/top_streaming') }}">Top Streaming Tracks</a></div>
										</div>
									</div>

									<div class="infobox">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-music"></i>
										</div>

										<div class="infobox-data">
											<?php $draft_tracks = $dashboard_track_and_user_stats['data'][0]->total_tracks - $dashboard_track_and_user_stats['data'][0]->approved_tracks; ?>
											<span class="infobox-data-number"><?php echo number_format($draft_tracks); ?></span>
											<div class="infobox-content"><a href="{{ url('/admin/tracks?status=draft') }}">Draft Tracks</a></div>
										</div>
									</div>
								</div>
							</div><!-- /.widget-box -->
						</div>
					</div>

					<!-- #section:custom/extra.hr -->
					<div class="hr hr12 hr-dotted"></div>

					<!-- /section:custom/extra.hr -->
					<div class="row">
						<div class="col-sm-6">
							<div class="widget-box transparent" id="recent-box">
								<div class="widget-header">
									<h4 class="widget-title lighter smaller">
										<i class="ace-icon fa fa-users blue middle"></i>Recent Registered Users
									</h4>

									<div class="widget-toolbar no-border">
										<ul class="nav nav-tabs" id="recent-tab">
											<li class="active">
												<a data-toggle="tab" href="#newMemberTab">Members</a>
											</li>

											<li>
												<a data-toggle="tab" href="#newClientTab">Clients</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-4">
										<div class="tab-content padding-8">

											<div id="newMemberTab" class="tab-pane active">
												<!-- #section:pages/dashboard.members -->
												<div class="clearfix">
													<?php
													if ($dashboard_members['numRows'] > 0) {
														foreach ($dashboard_members['data'] as $member) {
															if (!empty($member->pCloudFileID_mem_image)) {
																$memberImageUrl = url('/pCloudImgDownload.php?fileID=' . $member->pCloudFileID_mem_image);
															} else {
																$memberImageUrl = url('/assets_admin/assets/avatars/avatar2.png');
															}

															$addedDate = (new DateTime($member->added))->setTime(0, 0);
															$currentTime = (new DateTime())->setTime(0, 0);
															$daysDifference = $currentTime->diff($addedDate)->days;
															if ($daysDifference == 0) {
																$timeAgo = 'Today';
															} elseif ($daysDifference == 1) {
																$timeAgo = 'Yesterday';
															} else {
																$timeAgo = $daysDifference . ' days ago';
															}
													?>
															<div class="itemdiv memberdiv">
																<div class="user">
																	<img alt="Avatar" src="<?php echo $memberImageUrl; ?>" />
																</div>

																<div class="body">
																	<div class="name">
																		<a target="_blank" href="{{ url('/admin/member_view?mid=' . $member->id) }}">
																			<?php if (empty($member->fname) && empty($member->lname)) {
																				echo "Guest";
																			} else {
																				echo $member->fname . ' ' . $member->lname;
																			} ?></a>
																	</div>

																	<div class="time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span class="green"><?php echo $timeAgo; ?></span>
																	</div>

																	<div>
																		<?php if ($member->active == 0) { ?>
																			<span class="label label-warning label-sm">pending</span>
																		<?php } else { ?>
																			<span class="label label-success label-sm arrowed-in">approved</span>
																		<?php } ?>

																		<!-- <div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-angle-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a href="#" class="tooltip-success" data-rel="tooltip" title="Approve">
																				<span class="green">
																					<i class="ace-icon fa fa-check bigger-110"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject">
																				<span class="orange">
																					<i class="ace-icon fa fa-times bigger-110"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="ace-icon fa fa-trash-o bigger-110"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																	</div> -->
																	</div>
																</div>
															</div>
														<?php }
													} else { ?>
														<div class="no-data text-center">No new members joined this week.</div>
													<?php } ?>
												</div>

												<div class="space-4"></div>

												<div class="center">
													<i class="ace-icon fa fa-users fa-2x blue middle"></i>
													&nbsp;
													<a href="{{ url('/admin/members') }}" class="btn btn-sm btn-white btn-info">
														See all members &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>
											</div><!-- /.#member-tab -->

											<div id="newClientTab" class="tab-pane">
												<div class="clearfix">
													<?php
													if ($dashboard_clients['numRows'] > 0) {
														foreach ($dashboard_clients['data'] as $clients) {
															if (!empty($clients->pCloudFileID_mem_image)) {
																$clientImageUrl = url('/pCloudImgDownload.php?fileID=' . $clients->pCloudFileID_mem_image);
															} else {
																$clientImageUrl = url('/assets_admin/assets/avatars/avatar2.png');
															}

															$addedDate = (new DateTime($clients->added))->setTime(0, 0);
															$currentTime = (new DateTime())->setTime(0, 0);
															$daysDifference = $currentTime->diff($addedDate)->days;
															if ($daysDifference == 0) {
																$timeAgo = 'Today';
															} elseif ($daysDifference == 1) {
																$timeAgo = 'Yesterday';
															} else {
																$timeAgo = $daysDifference . ' days ago';
															}

													?>
															<div class="itemdiv memberdiv">
																<div class="user">
																	<img alt="Avatar" src="<?php echo $clientImageUrl; ?>" />
																</div>

																<div class="body">
																	<div class="name">
																		<a target="_blank" href="{{ url('/admin/client_view?cid=' . $clients->id) }}">
																			<?php if (!empty($clients->name)) {
																				echo urldecode($clients->name);
																			} else {
																				echo "Guest";
																			} ?></a>
																	</div>

																	<div class="time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span class="green"><?php echo $timeAgo; ?></span>
																	</div>

																	<div>
																		<?php if ($clients->active == 0) { ?>
																			<span class="label label-warning label-sm">Pending</span>
																		<?php } else { ?>
																			<span class="label label-success label-sm arrowed-in">Approved</span>
																		<?php } ?>

																		<!-- <div class="inline position-relative">
																			<button class="btn btn-minier btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown" data-position="auto">
																				<i class="ace-icon fa fa-angle-down icon-only bigger-120"></i>
																			</button>

																			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																				<li>
																					<a href="#" class="tooltip-success" data-rel="tooltip" title="Approve">
																						<span class="green">
																							<i class="ace-icon fa fa-check bigger-110"></i>
																						</span>
																					</a>
																				</li>

																				<li>
																					<a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject">
																						<span class="orange">
																							<i class="ace-icon fa fa-times bigger-110"></i>
																						</span>
																					</a>
																				</li>

																				<li>
																					<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																						<span class="red">
																							<i class="ace-icon fa fa-trash-o bigger-110"></i>
																						</span>
																					</a>
																				</li>
																			</ul>
																		</div> -->
																	</div>
																</div>
															</div>
														<?php }
													} else { ?>
														<div class="no-data text-center">No new clients joined this week.</div>
													<?php } ?>
												</div>

												<div class="space-4"></div>

												<div class="center">
													<i class="ace-icon fa fa-users fa-2x blue middle"></i>
													&nbsp;
													<a href="{{ url('/admin/clients') }}" class="btn btn-sm btn-white btn-info">
														See all clients &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>

											</div>
										</div>
									</div><!-- /.widget-main -->
								</div><!-- /.widget-body -->
							</div><!-- /.widget-box -->
						</div><!-- /.col -->

						<div class="col-sm-6">
							<div class="widget-box transparent" id="recent-box">
								<div class="widget-header">
									<h4 class="widget-title lighter smaller">
										<i class="ace-icon fa fa-users green middle"></i>Recent Active Users
									</h4>

									<div class="widget-toolbar no-border">
										<ul class="nav nav-tabs" id="recent-tab">
											<li class="active">
												<a data-toggle="tab" href="#activeMemberTab">Members</a>
											</li>

											<li>
												<a data-toggle="tab" href="#activeClientTab">Clients</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-4">
										<div class="tab-content padding-8">

											<div id="activeMemberTab" class="tab-pane active">
												<!-- #section:pages/dashboard.members -->
												<div class="clearfix">
													<?php
													if ($dashboard_recent_active_members['numRows'] > 0) {
														foreach ($dashboard_recent_active_members['data'] as $active_member) {
															if (!empty($active_member->pCloudFileID_mem_image)) {
																// $memberImageUrl = url('/pCloudImgDownload.php?fileID=' . $active_member->pCloudFileID_mem_image);
																$memberImageUrl = asset('public/images/profile-pic.png');
															} else {
																// $memberImageUrl = url('/assets_admin/assets/avatars/avatar2.png');
																$memberImageUrl = asset('public/images/profile-pic.png');
															}

															$addedTime = new DateTime($active_member->lastlogon);
															$currentTime = new DateTime();
															$interval = $addedTime->diff($currentTime);

															$minutesDifference = $interval->h * 60 + $interval->i;
															$hoursDifference = $interval->h;

															if ($minutesDifference <= 1) {
																$timeAgo = "Just Now";
															} elseif ($minutesDifference < 60) {
																$timeAgo = "$minutesDifference minutes ago";
															} else {
																$timeAgo = $hoursDifference == 1 ? '1 hour ago' : "$hoursDifference hours ago";
															}


													?>
															<div class="itemdiv memberdiv">
																<div class="user">
																	<img alt="Avatar" src="<?php echo $memberImageUrl; ?>" />
																</div>

																<div class="body">
																	<div class="name">
																		<a target="_blank" href="{{ url('/admin/member_view?mid=' . $active_member->id) }}">
																			<?php if (empty($active_member->fname) && empty($active_member->lname)) {
																				echo "Guest";
																			} else {
																				echo $active_member->fname . ' ' . $active_member->lname;
																			} ?></a>
																	</div>

																	<div class="time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span class="green"><?php echo $timeAgo; ?></span>
																	</div>
																</div>
															</div>
														<?php }
													} else { ?>
														<div class="no-data text-center">No recent active members.</div>
													<?php } ?>
												</div>

												<div class="space-4"></div>

												<div class="center">
													<i class="ace-icon fa fa-users fa-2x green middle"></i>
													&nbsp;
													<a href="{{ url('/admin/members') }}" class="btn btn-sm btn-white btn-info">
														See all members &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>
											</div><!-- /.#member-tab -->

											<div id="activeClientTab" class="tab-pane">
												<div class="clearfix">
													<?php
													if ($dashboard_recent_active_clients['numRows'] > 0) {
														foreach ($dashboard_recent_active_clients['data'] as $active_clients) {
															if (!empty($active_clients->pCloudFileID_mem_image)) {
																// $clientImageUrl = url('/pCloudImgDownload.php?fileID=' . $active_clients->pCloudFileID_mem_image);
																$clientImageUrl = url('/assets_admin/assets/avatars/avatar2.png');
															} else {
																$clientImageUrl = url('/assets_admin/assets/avatars/avatar2.png');
															}

															$addedTime = new DateTime($active_clients->lastlogon);
															$currentTime = new DateTime();
															$interval = $addedTime->diff($currentTime);

															$minutesDifference = $interval->h * 60 + $interval->i;
															$hoursDifference = $interval->h;

															if ($minutesDifference <= 1) {
																$timeAgo = "Just Now";
															} elseif ($minutesDifference < 60) {
																$timeAgo = "$minutesDifference minutes ago";
															} else {
																$timeAgo = $hoursDifference == 1 ? '1 hour ago' : "$hoursDifference hours ago";
															}
													?>
															<div class="itemdiv memberdiv">
																<div class="user">
																	<img alt="Avatar" src="<?php echo $clientImageUrl; ?>" />
																</div>

																<div class="body">
																	<div class="name">
																		<a target="_blank" href="{{ url('/admin/client_view?cid=' . $active_clients->id) }}">
																			<?php if (!empty($active_clients->name)) {
																				echo urldecode($active_clients->name);
																			} else {
																				echo "Guest";
																			} ?></a>
																	</div>

																	<div class="time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span class="green"><?php echo $timeAgo; ?></span>
																	</div>
																</div>
															</div>
														<?php }
													} else { ?>
														<div class="no-data text-center">No recent active clients.</div>
													<?php } ?>
												</div>

												<div class="space-4"></div>

												<div class="center">
													<i class="ace-icon fa fa-users fa-2x green middle"></i>

													&nbsp;
													<a href="{{ url('/admin/clients') }}" class="btn btn-sm btn-white btn-info">
														See all clients &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>
											</div>
										</div>
									</div><!-- /.widget-main -->
								</div><!-- /.widget-body -->
							</div><!-- /.widget-box -->
						</div><!-- /.col -->
					</div><!-- /.row -->

					<div class="hr hr12 hr-dotted"></div>

					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<div class="widget-box transparent" id="recent-box">
								<div class="widget-header">
									<h4 class="widget-title lighter smaller">
										<i class="ace-icon fa fa-music purple"></i>TRACKS
									</h4>

									<div class="widget-toolbar no-border">
										<ul class="nav nav-tabs" id="recent-tab">
											<li class="active">
												<a data-toggle="tab" href="#submitted-tab">Submitted</a>
											</li>

											<li>
												<a data-toggle="tab" href="#priority-tab">Top Priority</a>
											</li>

											<li>
												<a data-toggle="tab" href="#streaming-tab">Top Streaming</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-4">
										<div class="tab-content padding-8">
											<div id="submitted-tab" class="tab-pane active">
												<div class="clearfix">
													<table id="sample-table-1" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Title</th>
																<th>Artist</th>
																<th>Submitted By</th>
																<th>Submitted On</th>
															</tr>
														</thead>
														<tbody>
															<?php
															if ($dashboard_submitted_tracks['numRows']) {
																foreach ($dashboard_submitted_tracks['data'] as $submitted_tracks) {
																	$submission_date = date('d-M-Y', strtotime($submitted_tracks->added));
															?>
																	<tr>
																		<td>
																			<a target="_blank" href="{{ url('/admin/submitted_track_edit?tid=' . $submitted_tracks->id); }}">
																				<?php echo $submitted_tracks->title; ?>
																			</a>
																		</td>
																		<td>
																			<?php echo $submitted_tracks->artist; ?>
																		</td>
																		<td>
																			<a target="_blank" href="{{ url('/admin/client_view?cid=' . $submitted_tracks->client_id) }}">
																				<?php echo $submitted_tracks->client; ?>
																			</a>
																		</td>
																		<td>
																			<?php echo $submission_date; ?>
																		</td>
																	</tr>
																<?php }
															} else { ?>
																<tr>
																	<td colspan="4" class="text-center">No submitted tracks found for this month.</td>
																</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<div class="center">
													<i class="ace-icon fa fa-music fa-2x purple middle"></i> &nbsp;
													<a href="{{ url('/admin/submitted_tracks') }}" class="btn btn-sm btn-white btn-info">
														See all Submitted Tracks &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>
											</div>

											<div id="priority-tab" class="tab-pane">
												<div class="clearfix">
													<table id="sample-table-1" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Title</th>
																<th>Artist</th>
																<th>Submitted By</th>
																<th>Submitted On</th>
															</tr>
														</thead>
														<tbody>
															<?php
															if ($dashboard_top_priority_tracks['numRows']) {
																foreach ($dashboard_top_priority_tracks['data'] as $priority_tracks) {
																	$submission_date = date('d-M-Y', strtotime($priority_tracks->added));
															?>
																	<tr>
																		<td>
																			<a target="_blank" href="{{ url('/admin/track_view?tid=' . $priority_tracks->track_id); }}">
																				<?php echo urldecode($priority_tracks->title); ?>
																			</a>
																		</td>
																		<td>
																			<?php echo urldecode($priority_tracks->artist); ?>
																		</td>
																		<td>
																			<a target="_blank" href="{{ url('/admin/client_view?cid=' . $priority_tracks->client_id) }}">
																				<?php echo urldecode($priority_tracks->name); ?>
																			</a>
																		</td>
																		<td>
																			<?php echo $submission_date; ?>
																		</td>
																	</tr>
																<?php }
															} else { ?>
																<tr>
																	<td colspan="4" class="text-center">No priority tracks found.</td>
																</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<div class="center">
													<i class="ace-icon fa fa-music fa-2x purple middle"></i> &nbsp;
													<a href="{{ url('/admin/top_priority') }}" class="btn btn-sm btn-white btn-info">
														See all Top Priority Tracks &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>
											</div>

											<div id="streaming-tab" class="tab-pane">
												<div class="clearfix">
													<table id="sample-table-1" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Title</th>
																<th>Artist</th>
																<th>Submitted By</th>
																<th>Submitted On</th>
															</tr>
														</thead>
														<tbody>
															<?php
															if ($dashboard_top_streaming_tracks['numRows']) {
																foreach ($dashboard_top_streaming_tracks['data'] as $streaming_tracks) {
																	$submission_date = date('d-M-Y', strtotime($streaming_tracks->added));
															?>
																	<tr>
																		<td>
																			<a target="_blank" href="{{ url('/admin/track_view?tid=' . $streaming_tracks->track_id); }}">
																				<?php echo urldecode($streaming_tracks->title); ?>
																			</a>
																		</td>
																		<td>
																			<?php echo urldecode($streaming_tracks->artist); ?>
																		</td>
																		<td>
																			<a target="_blank" href="{{ url('/admin/client_view?cid=' . $streaming_tracks->client_id) }}">
																				<?php echo urldecode($streaming_tracks->name); ?>
																			</a>
																		</td>
																		<td>
																			<?php echo $submission_date; ?>
																		</td>
																	</tr>
																<?php }
															} else { ?>
																<tr>
																	<td colspan="4" class="text-center">No top streaming tracks found.</td>
																</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
												<div class="center">
													<i class="ace-icon fa fa-music fa-2x purple middle"></i> &nbsp;
													<a href="{{ url('/admin/top_streaming') }}" class="btn btn-sm btn-white btn-info">
														See all Top Streaming Tracks &nbsp;
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-xs-12">
							<div class="widget-box">
								<div class="widget-header">
									<h4 class="widget-title lighter smaller">
										<i class="ace-icon fa fa-comment blue"></i>
										Weekly Track Reviews
									</h4>
								</div>
								<div class="widget-body">
									<div class="widget-main no-padding">
										<div class="dialogs" style="max-height: 500px; overflow-y: auto;">
											<?php
											if ($dashboard_weekly_tracks_reviews['numRows']) {
												foreach ($dashboard_weekly_tracks_reviews['data'] as $weekly_reviews) {
													// check profile image
													// if (!empty($weekly_reviews->pCloudFileID_mem_image)) {
													// 	$memberAvatar = url('/pCloudImgDownload.php?fileID=' . $weekly_reviews->pCloudFileID_mem_image);
													// } else {
													// 	$memberAvatar = url('/assets_admin/assets/avatars/avatar2.png');
													// }

													$addedDate = (new DateTime($weekly_reviews->added))->setTime(0, 0);
													$currentTime = (new DateTime())->setTime(0, 0);
													$daysDifference = $currentTime->diff($addedDate)->days;
													if ($daysDifference == 0) {
														$timeAgo = 'Today';
													} elseif ($daysDifference == 1) {
														$timeAgo = 'Yesterday';
													} else {
														$timeAgo = $daysDifference . ' days ago';
													}


											?>
													<div class="itemdiv dialogdiv">
														<div class="user">
															<img alt="Digiwaxx Logo" src="{{ asset('assets/img/profile-pic.png')}}" />
														</div>

														<div class="body">
															<div class="time">
																<i class="ace-icon fa fa-clock-o"></i>
																<span class="green"><?php echo $timeAgo; ?></span>
															</div>

															<div class="text"><?php echo urldecode($weekly_reviews->additionalcomments); ?></div>
															<div class="name"><span>DJ: </span>
																<a target="_blank" href="{{ url('/admin/member_view?mid=' . $weekly_reviews->member) }}"><?php echo $weekly_reviews->fname . ' ' . $weekly_reviews->lname; ?></a> | <span>Track: </span> <a target="_blank" href="{{ url('/admin/track_review?tid=' . $weekly_reviews->track); }}"><?php echo urldecode($weekly_reviews->title); ?></a>
															</div>
														</div>
													</div>
												<?php }
											} else { ?>
												<div class="itemdiv dialogdiv">
													<div class="user">
														<img alt="Digiwaxx Logo" src="{{ asset('assets/img/profile-pic.png')}}" />
													</div>
													<div class="body">
														<div class="text">No reviews found on tracks for this week.</div>
													</div>
												</div>
											<?php } ?>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- /.row -->
					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->