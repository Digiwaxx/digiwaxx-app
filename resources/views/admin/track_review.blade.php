@extends('admin.admin_dashboard_active_sidebar')
@section('content')
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
					<a href="<?php echo url("admin/tracks"); ?>">
						<i class="ace-icon fa fa-list list-icon"></i>
						Tracks</a>
				</li>
				<li class="active">Track Review</li>
			</ul><!-- /.breadcrumb -->


			<!-- /section:basics/content.searchbox -->
		</div>

		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="row">
						<div class="col-xs-12">
							<?php $track = $tracks['data'][0];
							if (!empty($track)) {
								$rating = 0;
								$averageRating = 0;
								foreach ($reviews['data'] as $review) {

									$rating += $review->whatrate;
								}

								if ($reviews['numRows'] != 0) {

									$averageRating = $rating / $reviews['numRows'];
									$averageRating = number_format($averageRating, 2, '.', '');
								}



							?>
								<h3></h3>
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> Track </div>

										<div class="profile-info-value">
											<?php echo urldecode($track->artist) . ' - ' . urldecode($track->title); ?>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> No. Reviews </div>

										<div class="profile-info-value">
											<?php echo $reviews['numRows']; ?>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> Average Rating </div>

										<div class="profile-info-value">
											<?php echo $averageRating; ?>
										</div>
									</div>

								</div>
							<?php } ?>

							<!--	
										-->

							<div class="space-10"></div>

							<div class="col-sm-12">
								<div class="table-header">
									Track Downloads
								</div>
								<table id="simple-table" class="table  table-bordered table-hover">
									<thead>
										<tr>
											<th class="center" width="20">
												S. No
											</th>
											<th class="detail-col" width="150">Version</th>
											<th class="detail-col" width="80">Downloads</th>
											<th class="detail-col" width="80">No. Plays</th>
											<th class="detail-col" width="100">Weekly Downloads</th>
											<th class="detail-col" width="100">Monthly Downloads</th>
											<th class="detail-col" width="100">Yearly Downloads</th>

										</tr>
									</thead>

									<tbody>
										<?php

										$i = 1;
										foreach ($audios['data'] as $audio) {

											$trackVersion = trim(urldecode($audio->version));
										?>

											<tr>
												<td class="center">
													<?php echo $i; ?>
												</td>

												<td class="left">
													<?php echo $trackVersion; ?>
												</td>

												<td class="left">
													<?php echo $audio->downloads; ?>
												</td>

												<td>
													<?php echo $audio->num_plays; ?>
												</td>

												<td>
													<?php echo $periodicdownloads[$trackVersion]['weekly_downloads']; ?>
												</td>

												<td>
													<?php echo $periodicdownloads[$trackVersion]['monthly_downloads']; ?>
												</td>

												<td>
													<?php echo $periodicdownloads[$trackVersion]['yearly_downloads']; ?>
												</td>

											</tr>
										<?php $i++;
										} ?>
									</tbody>
								</table>
							</div>


							<!-- graph 1-->
							<div style="clear:both;"></div>
							<?php

							$numRating = 0;
							$whatYouThink = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
							$whatYouThinkAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
							foreach ($reviews['data'] as $review) {

								// $rating += $review->whatrate;

								if ($review->whatrate == "5") {
									$whatYouThink[5]++;
									$numRating++;
								} else  if ($review->whatrate == "4") {
									$whatYouThink[4]++;
									$numRating++;
								} else  if ($review->whatrate == "3") {
									$whatYouThink[3]++;
									$numRating++;
								} else  if ($review->whatrate == "2") {
									$whatYouThink[2]++;
									$numRating++;
								} else  if ($review->whatrate == "1") {
									$whatYouThink[1]++;
									$numRating++;
								}
							}


							if ($numRating != 0) {

								$whatYouThinkAvg[1] = ($whatYouThink[1] / $numRating) * 100;
								$whatYouThinkAvg[1] = number_format($whatYouThinkAvg[1], 2, '.', '');

								$whatYouThinkAvg[2] = ($whatYouThink[2] / $numRating) * 100;
								$whatYouThinkAvg[2] = number_format($whatYouThinkAvg[2], 2, '.', '');

								$whatYouThinkAvg[3] = ($whatYouThink[3] / $numRating) * 100;
								$whatYouThinkAvg[3] = number_format($whatYouThinkAvg[3], 2, '.', '');

								$whatYouThinkAvg[4] = ($whatYouThink[4] / $numRating) * 100;
								$whatYouThinkAvg[4] = number_format($whatYouThinkAvg[4], 2, '.', '');

								$whatYouThinkAvg[5] = ($whatYouThink[5] / $numRating) * 100;
								$whatYouThinkAvg[5] = number_format($whatYouThinkAvg[5], 2, '.', '');
							}


							if (max($whatYouThinkAvg[1], $whatYouThinkAvg[2], $whatYouThinkAvg[3], $whatYouThinkAvg[4], $whatYouThinkAvg[5]) > 0) {


							?>

								<div class="col-sm-9">
									<div id="whatYouThinkDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','1','5','5')">
											5 (<span class="red"><?php echo $whatYouThink[5]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','1','4','4')">
											4 (<span class="red"><?php echo $whatYouThink[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','1','3','3')">
											3 (<span class="red"><?php echo $whatYouThink[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','1','2','2')">
											2 (<span class="red"><?php echo $whatYouThink[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','1','1','1')">
											1 (<span class="red"><?php echo $whatYouThink[1]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="whatYouThinkDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 2-->
							<div style="clear:both;"></div>
							<?php

							$numWhereHeard = 0;
							$whereHeard = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
							$whereHeardAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->whereheard, "digital_waxx_music_service") == 0 || strcmp($review->whereheard, "Digital Waxx") == 0) {
									$whereHeard[1]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "commercial_radio") == 0 || strcmp($review->whereheard, "Radio") == 0) {
									$whereHeard[2]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "satellite_radio") == 0 || strcmp($review->whereheard, "Satellite Radio") == 0) {
									$whereHeard[3]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "college_radio") == 0 || strcmp($review->whereheard, "College Radio") == 0) {
									$whereHeard[4]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "mixtape") == 0 || strcmp($review->whereheard, "Mixtape") == 0) {
									$whereHeard[5]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "club") == 0 || strcmp($review->whereheard, "Club") == 0) {
									$whereHeard[6]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "internet") == 0 || strcmp($review->whereheard, "Internet") == 0) {
									$whereHeard[7]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "video") == 0 || strcmp($review->whereheard, "Video") == 0) {
									$whereHeard[8]++;
									$numWhereHeard++;
								} else if (strcmp($review->whereheard, "Other") == 0) {
									$whereHeard[9]++;
									$numWhereHeard++;
								}
							}

							/*$x=1;
									foreach($whereHeard as $wh)
									{
									
									  if($wh>1)
									  {
									  
									    $whereHeardPer[$x] = $wh;
									  }
									  else
									  {
									    $whereHeardPer[$x] = 1.5;
									  }
									$x++;
									}
									*/




							$numWhereHeard;

							if ($numWhereHeard != 0) {

								$whereHeardAvg[1] = ($whereHeard[1] / $numWhereHeard) * 100;
								$whereHeardAvg[1] = number_format($whereHeardAvg[1], 2, '.', '');

								$whereHeardAvg[2] = ($whereHeard[2] / $numWhereHeard) * 100;
								$whereHeardAvg[2] = number_format($whereHeardAvg[2], 2, '.', '');

								$whereHeardAvg[3] = ($whereHeard[3] / $numWhereHeard) * 100;
								$whereHeardAvg[3] = number_format($whereHeardAvg[3], 2, '.', '');

								$whereHeardAvg[4] = ($whereHeard[4] / $numWhereHeard) * 100;
								$whereHeardAvg[4] = number_format($whereHeardAvg[4], 2, '.', '');

								$whereHeardAvg[5] = ($whereHeard[5] / $numWhereHeard) * 100;
								$whereHeardAvg[5] = number_format($whereHeardAvg[5], 2, '.', '');

								$whereHeardAvg[6] = ($whereHeard[6] / $numWhereHeard) * 100;
								$whereHeardAvg[6] = number_format($whereHeardAvg[6], 2, '.', '');

								$whereHeardAvg[7] = ($whereHeard[7] / $numWhereHeard) * 100;
								$whereHeardAvg[7] = number_format($whereHeardAvg[7], 2, '.', '');


								$whereHeardAvg[8] = ($whereHeard[8] / $numWhereHeard) * 100;
								$whereHeardAvg[8] = number_format($whereHeardAvg[8], 2, '.', '');

								$whereHeardAvg[9] = ($whereHeard[9] / $numWhereHeard) * 100;
								$whereHeardAvg[9] = number_format($whereHeardAvg[9], 2, '.', '');
							}




							if (max($whereHeardAvg[1], $whereHeardAvg[2], $whereHeardAvg[3], $whereHeardAvg[4], $whereHeardAvg[5], $whereHeardAvg[6], $whereHeardAvg[7], $whereHeardAvg[8], $whereHeardAvg[9]) > 0) {
							?>
								<div class="col-sm-9">
									<div id="whereHeardDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','digital_waxx_music_service-OR-Digital Waxx','Digital Waxx')">Digital Waxx (<span class="red"><?php echo $whereHeard[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','commercial_radio-OR-Radio','Radio')">Radio (<span class="red"><?php echo $whereHeard[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','satellite_radio-OR-Satellite Radio','Satellite Radio')">Satellite Radio (<span class="red"><?php echo $whereHeard[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','college_radio-OR-College Radio','College Radio')">College Radio (<span class="red"><?php echo $whereHeard[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','mixtape-OR-Mixtape','Mixtape')">Mixtape (<span class="red"><?php echo $whereHeard[5]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','club-OR-Club','Club')">Club (<span class="red"><?php echo $whereHeard[6]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','internet-OR-Internet','Internet')">Internet (<span class="red"><?php echo $whereHeard[7]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','video-OR-Video','Video')">Video (<span class="red"><?php echo $whereHeard[8]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','2','other-OR-Other','Other')">Other (<span class="red"><?php echo $whereHeard[9]; ?></span>)</a><br />
									</p>


								</div>
							<?php } else { ?> <div id="whereHeardDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 3-->
							<div style="clear:both;"></div>
							<?php

							$numAlreadyHave = 0;
							$alreadyHave = array(1 => 0, 2 => 0);
							$alreadyHaveAvg = array(1 => 0, 2 => 0);
							foreach ($reviews['data'] as $review) {
								if (strcmp($review->alreadyhave, "yes") == 0) {
									$alreadyHave[1]++;
									$numAlreadyHave++;
								} else if (strcmp($review->alreadyhave, "no") == 0) {
									$alreadyHave[2]++;
									$numAlreadyHave++;
								}
							}

							if ($numAlreadyHave != 0) {

								$numAlreadyHave;
								$alreadyHaveAvg[1] = ($alreadyHave[1] / $numAlreadyHave) * 100;
								$alreadyHaveAvg[1] = number_format($alreadyHaveAvg[1], 2, '.', '');

								$alreadyHaveAvg[2] = ($alreadyHave[2] / $numAlreadyHave) * 100;
								$alreadyHaveAvg[2] = number_format($alreadyHaveAvg[2], 2, '.', '');
							}




							if (max($alreadyHaveAvg[1], $alreadyHaveAvg[2]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="alreadyHaveDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','3','yes','Yes')">
											Yes (<span class="red"><?php echo $alreadyHave[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','3','no','No')">
											No (<span class="red"><?php echo $alreadyHave[2]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="alreadyHaveDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 4-->
							<div style="clear:both;"></div>
							<?php

							$numWillPlay = 0;
							$willPlay = array(1 => 0, 2 => 0);
							$willPlayAvg = array(1 => 0, 2 => 0);
							foreach ($reviews['data'] as $review) {
								if (strcmp($review->willplay, "yes") == 0) {
									$willPlay[1]++;
									$numWillPlay++;
								} else if (strcmp($review->willplay, "no") == 0) {
									$willPlay[2]++;
									$numWillPlay++;
								}
							}

							if ($numWillPlay != 0) {

								$numWillPlay;
								$willPlayAvg[1] = ($willPlay[1] / $numWillPlay) * 100;
								$willPlayAvg[1] = number_format($willPlayAvg[1], 2, '.', '');

								$willPlayAvg[2] = ($willPlay[2] / $numWillPlay) * 100;
								$willPlayAvg[2] = number_format($willPlayAvg[2], 2, '.', '');
							}



							if (max($willPlayAvg[1], $willPlayAvg[2]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="willPlayDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','4','yes','Yes')">
											Yes (<span class="red"><?php echo $willPlay[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','4','no','No')">
											No (<span class="red"><?php echo $willPlay[2]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="willPlayDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 5-->
							<div style="clear:both;"></div>
							<?php

							$numHowSoon = 0;
							$howSoon = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
							$howSoonAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
							foreach ($reviews['data'] as $review) {
								if (strcmp($review->howsoon, "Immediately") == 0) {
									$howSoon[1]++;
									$numHowSoon++;
								} else if (strcmp($review->howsoon, "Next Week") == 0) {
									$howSoon[2]++;
									$numHowSoon++;
								} else if (strcmp($review->howsoon, "In Two Weeks") == 0) {
									$howSoon[3]++;
									$numHowSoon++;
								} else if (strcmp($review->howsoon, "One Month") == 0) {
									$howSoon[4]++;
									$numHowSoon++;
								} else if (strcmp($review->howsoon, "Never") == 0) {
									$howSoon[5]++;
									$numHowSoon++;
								} else if (strcmp($review->howsoon, "0") == 0) {
									$howSoon[6]++;
									$numHowSoon++;
								}
							}

							if ($numHowSoon != 0) {


								$numHowSoon;
								$howSoonAvg[1] = ($howSoon[1] / $numHowSoon) * 100;
								$howSoonAvg[1] = number_format($howSoonAvg[1], 2, '.', '');

								$howSoonAvg[2] = ($howSoon[2] / $numHowSoon) * 100;
								$howSoonAvg[2] = number_format($howSoonAvg[2], 2, '.', '');

								$howSoonAvg[3] = ($howSoon[3] / $numHowSoon) * 100;
								$howSoonAvg[3] = number_format($howSoonAvg[3], 2, '.', '');

								$howSoonAvg[4] = ($howSoon[4] / $numHowSoon) * 100;
								$howSoonAvg[4] = number_format($howSoonAvg[4], 2, '.', '');

								$howSoonAvg[5] = ($howSoon[5] / $numHowSoon) * 100;
								$howSoonAvg[5] = number_format($howSoonAvg[5], 2, '.', '');

								$howSoonAvg[6] = ($howSoon[6] / $numHowSoon) * 100;
								$howSoonAvg[6] = number_format($howSoonAvg[6], 2, '.', '');
							}



							if (max($howSoonAvg[1], $howSoonAvg[2], $howSoonAvg[3], $howSoonAvg[4], $howSoonAvg[5], $howSoonAvg[6]) > 0) {	?>
								<div class="col-sm-9">
									<div id="howSoonDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','5','Immediately','Immediately')">
											Immediately (<span class="red"><?php echo $howSoon[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','5','Next Week','Next Week')">
											Next Week (<span class="red"><?php echo $howSoon[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','5','In Two Weeks','In Two Weeks')">
											In Two Weeks (<span class="red"><?php echo $howSoon[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','5','One Month','One Month')">
											One Month (<span class="red"><?php echo $howSoon[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','5','Never','Never')">
											Never (<span class="red"><?php echo $howSoon[5]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','5','0','Does Not Apply To Me')">
											Does Not Apply To Me (<span class="red"><?php echo $howSoon[6]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="howSoonDiv" style="display:none;"></div> <?php  } ?>


							<!-- graph 6-->
							<div style="clear:both;"></div>
							<?php

							$numHowManyPlays = 0;
							$howManyPlays = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
							$howManyPlaysAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->howmanyplays, "1-3") == 0) {
									$howManyPlays[1]++;
									$numHowManyPlays++;
								} else if (strcmp($review->howmanyplays, "4-7") == 0) {
									$howManyPlays[2]++;
									$numHowManyPlays++;
								} else if (strcmp($review->howmanyplays, "7-10") == 0) {
									$howManyPlays[3]++;
									$numHowManyPlays++;
								} else if (strcmp($review->howmanyplays, "None") == 0) {
									$howManyPlays[4]++;
									$numHowManyPlays++;
								} else if (strcmp($review->howmanyplays, "0") == 0) {
									$howManyPlays[5]++;
									$numHowManyPlays++;
								}
							}

							if ($numHowManyPlays != 0) {

								$howManyPlaysAvg[1] = ($howManyPlays[1] / $numHowManyPlays) * 100;
								$howManyPlaysAvg[1] = number_format($howManyPlaysAvg[1], 2, '.', '');

								$howManyPlaysAvg[2] = ($howManyPlays[2] / $numHowManyPlays) * 100;
								$howManyPlaysAvg[2] = number_format($howManyPlaysAvg[2], 2, '.', '');

								$howManyPlaysAvg[3] = ($howManyPlays[3] / $numHowManyPlays) * 100;
								$howManyPlaysAvg[3] = number_format($howManyPlaysAvg[3], 2, '.', '');

								$howManyPlaysAvg[4] = ($howManyPlays[4] / $numHowManyPlays) * 100;
								$howManyPlaysAvg[4] = number_format($howManyPlaysAvg[4], 2, '.', '');

								$howManyPlaysAvg[5] = ($howManyPlays[5] / $numHowManyPlays) * 100;
								$howManyPlaysAvg[5] = number_format($howManyPlaysAvg[5], 2, '.', '');
							}


							if (max($howManyPlaysAvg[1], $howManyPlaysAvg[2], $howManyPlaysAvg[3], $howManyPlaysAvg[4], $howManyPlaysAvg[5]) > 0) {


							?>
								<div class="col-sm-9">
									<div id="howManyPlaysDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','6','1-3','1-3')">
											1-3 (<span class="red"><?php echo $howManyPlays[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','6','4-7','4-7')">
											4-7 (<span class="red"><?php echo $howManyPlays[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','6','7-10','7-10')">
											7-10 (<span class="red"><?php echo $howManyPlays[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','6','None','None')">
											None (<span class="red"><?php echo $howManyPlays[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','6','0','Does Not Apply To Me')">
											Does Not Apply To Me (<span class="red"><?php echo $howManyPlays[5]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="howManyPlaysDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 7 check if else statement, multiple answers or not -->

							<div style="clear:both;"></div>
							<?php

							$numFormats = 0;
							$formats = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
							$formatsAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
							foreach ($reviews['data'] as $review) {

								if ($review->formats_comradio == 1) {
									$formats[1]++;
									$numFormats++;
								}
								if ($review->formats_satradio == 1) {
									$formats[2]++;
									$numFormats++;
								}
								if ($review->formats_colradio == 1) {
									$formats[3]++;
									$numFormats++;
								}
								if ($review->formats_internet == 1) {
									$formats[4]++;
									$numFormats++;
								}
								if ($review->formats_clubs == 1) {
									$formats[5]++;
									$numFormats++;
								}
								if ($review->formats_mixtapes == 1) {
									$formats[6]++;
									$numFormats++;
								}
								if ($review->formats_musicvideo == 1) {
									$formats[7]++;
									$numFormats++;
								}
							}

							if ($numFormats != 0) {

								$formatsAvg[1] = ($formats[1] / $numFormats) * 100;
								$formatsAvg[1] = number_format($formatsAvg[1], 2, '.', '');

								$formatsAvg[2] = ($formats[2] / $numFormats) * 100;
								$formatsAvg[2] = number_format($formatsAvg[2], 2, '.', '');

								$formatsAvg[3] = ($formats[3] / $numFormats) * 100;
								$formatsAvg[3] = number_format($formatsAvg[3], 2, '.', '');

								$formatsAvg[4] = ($formats[4] / $numFormats) * 100;
								$formatsAvg[4] = number_format($formatsAvg[4], 2, '.', '');

								$formatsAvg[5] = ($formats[5] / $numFormats) * 100;
								$formatsAvg[5] = number_format($formatsAvg[5], 2, '.', '');

								$formatsAvg[6] = ($formats[6] / $numFormats) * 100;
								$formatsAvg[6] = number_format($formatsAvg[6], 2, '.', '');

								$formatsAvg[7] = ($formats[7] / $numFormats) * 100;
								$formatsAvg[7] = number_format($formatsAvg[7], 2, '.', '');
							}




							if (max($formatsAvg[1], $formatsAvg[2], $formatsAvg[3], $formatsAvg[4], $formatsAvg[5], $formatsAvg[6], $formatsAvg[7]) > 0) {
							?>
								<div class="col-sm-9">
									<div id="formatsDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_comradio','Commercial Radio')">
											Commercial Radio (<span class="red"><?php echo $formats[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_satradio','Satellite Radio')">
											Satellite Radio (<span class="red"><?php echo $formats[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_colradio','College Radio')">
											College Radio (<span class="red"><?php echo $formats[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_internet','Internet')">
											Internet (<span class="red"><?php echo $formats[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_clubs','Clubs')">
											Clubs (<span class="red"><?php echo $formats[5]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_mixtapes','Mix Tapes')">
											Mix Tapes (<span class="red"><?php echo $formats[6]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','7','formats_musicvideo','Music Videos')">
											Music Videos (<span class="red"><?php echo $formats[7]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="formatsDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 8 should be tested for no value -->

							<div style="clear:both;"></div>
							<?php

							$numGoDistance = 0;
							$goDistance = array(1 => 0, 2 => 0);
							$goDistanceAvg = array(1 => 0, 2 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->godistance, 'yes') == 0) {
									$goDistance[1]++;
									$numGoDistance++;
								} else if (strcmp($review->godistance, 'no') == 0) {
									$goDistance[2]++;
									$numGoDistance++;
								}
							}


							if ($numGoDistance != 0) {

								$goDistanceAvg[1] = ($goDistance[1] / $numGoDistance) * 100;
								$goDistanceAvg[1] = number_format($goDistanceAvg[1], 2, '.', '');

								$goDistanceAvg[2] = ($goDistance[2] / $numGoDistance) * 100;
								$goDistanceAvg[2] = number_format($goDistanceAvg[2], 2, '.', '');
							}




							if (max($goDistanceAvg[1], $goDistanceAvg[2]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="goDistanceDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','8','yes','Yes')">
											Yes (<span class="red"><?php echo $goDistance[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','8','no','No')">
											No (<span class="red"><?php echo $goDistance[2]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="goDistanceDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 9 -->

							<div style="clear:both;"></div>
							<?php

							$numLabelSupport = 0;
							$labelSupport = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
							$labelSupportAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->labelsupport, 'market_visits') == 0) {
									$labelSupport[1]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'more_street_marketing') == 0) {
									$labelSupport[2]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'interview_on_my_show_or_station') == 0) {
									$labelSupport[3]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'a_show_in_my_market') == 0) {
									$labelSupport[4]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'do_a_regional_remix') == 0) {
									$labelSupport[5]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'other') == 0) {
									$labelSupport[6]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'scrap_the_project') == 0) {
									$labelSupport[7]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'shoot_a_video') == 0) {
									$labelSupport[8]++;
									$numLabelSupport++;
								} else if (strcmp($review->labelsupport, 'nothing') == 0) {
									$labelSupport[9]++;
									$numLabelSupport++;
								}


								// print_r($reviews);
								//  echo $review->labelsupport; echo '<br />';



							}



							if ($numLabelSupport != 0) {

								$numLabelSupport;

								$labelSupportAvg[1] = ($labelSupport[1] / $numLabelSupport) * 100;
								$labelSupportAvg[1] = number_format($labelSupportAvg[1], 2, '.', '');

								$labelSupportAvg[2] = ($labelSupport[2] / $numLabelSupport) * 100;
								$labelSupportAvg[2] = number_format($labelSupportAvg[2], 2, '.', '');

								$labelSupportAvg[3] = ($labelSupport[3] / $numLabelSupport) * 100;
								$labelSupportAvg[3] = number_format($labelSupportAvg[3], 2, '.', '');

								$labelSupportAvg[4] = ($labelSupport[4] / $numLabelSupport) * 100;
								$labelSupportAvg[4] = number_format($labelSupportAvg[4], 2, '.', '');

								$labelSupportAvg[5] = ($labelSupport[5] / $numLabelSupport) * 100;
								$labelSupportAvg[5] = number_format($labelSupportAvg[5], 2, '.', '');

								$labelSupportAvg[6] = ($labelSupport[6] / $numLabelSupport) * 100;
								$labelSupportAvg[6] = number_format($labelSupportAvg[6], 2, '.', '');

								$labelSupportAvg[7] = ($labelSupport[7] / $numLabelSupport) * 100;
								$labelSupportAvg[7] = number_format($labelSupportAvg[7], 2, '.', '');

								$labelSupportAvg[8] = ($labelSupport[8] / $numLabelSupport) * 100;
								$labelSupportAvg[8] = number_format($labelSupportAvg[8], 2, '.', '');

								$labelSupportAvg[9] = ($labelSupport[9] / $numLabelSupport) * 100;
								$labelSupportAvg[9] = number_format($labelSupportAvg[9], 2, '.', '');
							}


							if (max($labelSupportAvg[1], $labelSupportAvg[2], $labelSupportAvg[3], $labelSupportAvg[4], $labelSupportAvg[5], $labelSupportAvg[6], $labelSupportAvg[7], $labelSupportAvg[8], $labelSupportAvg[9]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="labelSupportDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','market_visits','Market visits')">
											Market visits (<span class="red"><?php echo $labelSupport[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','more_street_marketing','More street marketing')">
											More street marketing (<span class="red"><?php echo $labelSupport[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','interview_on_my_show_or_station','Interview on my show or station')">
											Interview on my show or station (<span class="red"><?php echo $labelSupport[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','a_show_in_my_market','Local live performance')">
											Local live performance (<span class="red"><?php echo $labelSupport[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','do_a_regional_remix','Do a regional remix')">
											Do a regional remix (<span class="red"><?php echo $labelSupport[5]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','other','Other')">
											Other (<span class="red"><?php echo $labelSupport[6]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','scrap_the_project','Scrap the project')">
											Scrap the project (<span class="red"><?php echo $labelSupport[7]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','shoot_a_video','Shoot a video')">
											Shoot a video (<span class="red"><?php echo $labelSupport[8]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','9','nothing','Nothing')">
											Nothing (<span class="red"><?php echo $labelSupport[9]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="labelSupportDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 10 -->

							<div style="clear:both;"></div>
							<?php

							$numHowSupport = 0;
							$howSupport = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
							$howSupportAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->howsupport, 'play_it') == 0) {
									$howSupport[1]++;
									$numHowSupport++;
								} else if (strcmp($review->howsupport, 'nothing_i_wont_support_it') == 0) {
									$howSupport[2]++;
									$numHowSupport++;
								} else if (strcmp($review->howsupport, 'remix_it') == 0) {
									$howSupport[3]++;
									$numHowSupport++;
								} else if (strcmp($review->howsupport, 'does_not_apply_to_me') == 0) {
									$howSupport[4]++;
									$numHowSupport++;
								}
							}




							if ($numHowSupport != 0) {

								$howSupportAvg[1] = ($howSupport[1] / $numHowSupport) * 100;
								$howSupportAvg[1] = number_format($howSupportAvg[1], 2, '.', '');

								$howSupportAvg[2] = ($howSupport[2] / $numHowSupport) * 100;
								$howSupportAvg[2] = number_format($howSupportAvg[2], 2, '.', '');

								$howSupportAvg[3] = ($howSupport[3] / $numHowSupport) * 100;
								$howSupportAvg[3] = number_format($howSupportAvg[3], 2, '.', '');

								$howSupportAvg[4] = ($howSupport[4] / $numHowSupport) * 100;
								$howSupportAvg[4] = number_format($howSupportAvg[4], 2, '.', '');
							}



							if (max($howSupportAvg[1], $howSupportAvg[2], $howSupportAvg[3], $howSupportAvg[4]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="howSupportDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','10','play_it','Play it')">
											Play it (<span class="red"><?php echo $howSupport[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','10','nothing_i_wont_support_it','Nothing, I will not support it')">
											Nothing, I will not support it (<span class="red"><?php echo $howSupport[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','10','remix_it','Remix it')">
											Remix it(<span class="red"><?php echo $howSupport[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','10','does_not_apply_to_me','Does not apply to me')">
											Does not apply to me (<span class="red"><?php echo $howSupport[4]; ?></span>)</a>
									</p>


								</div>
							<?php } else { ?> <div id="howSupportDiv" style="display:none;"></div> <?php  } ?>


							<!-- graph 11 -->

							<div style="clear:both;"></div>
							<?php

							$numLikeRecord = 0;
							$likeRecord = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
							$likeRecordAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->likerecord, 'flow') == 0) {
									$likeRecord[1]++;
									$numLikeRecord++;
								} else if (strcmp($review->likerecord, 'the_lyrics') == 0) {
									$likeRecord[2]++;
									$numLikeRecord++;
								} else if (strcmp($review->likerecord, 'production') == 0) {
									$likeRecord[3]++;
									$likeRecord++;
								} else if (strcmp($review->likerecord, 'hook_or_chorus') == 0) {
									$likeRecord[4]++;
									$numLikeRecord++;
								} else if (strcmp($review->likerecord, 'overall_sound_or_style') == 0) {
									$likeRecord[5]++;
									$numLikeRecord++;
								} else if (strcmp($review->likerecord, 'nada') == 0) {
									$likeRecord[6]++;
									$numLikeRecord++;
								}
							}




							if ($numLikeRecord != 0) {

								$likeRecordAvg[1] = ($likeRecord[1] / $numLikeRecord) * 100;
								$likeRecordAvg[1] = number_format($likeRecordAvg[1], 2, '.', '');

								$likeRecordAvg[2] = ($likeRecord[2] / $numLikeRecord) * 100;
								$likeRecordAvg[2] = number_format($likeRecordAvg[2], 2, '.', '');

								$likeRecordAvg[3] = ($likeRecord[3] / $numLikeRecord) * 100;
								$likeRecordAvg[3] = number_format($likeRecordAvg[3], 2, '.', '');

								$likeRecordAvg[4] = ($likeRecord[4] / $numLikeRecord) * 100;
								$likeRecordAvg[4] = number_format($likeRecordAvg[4], 2, '.', '');

								$likeRecordAvg[5] = ($likeRecord[5] / $numLikeRecord) * 100;
								$likeRecordAvg[5] = number_format($likeRecordAvg[5], 2, '.', '');

								$likeRecordAvg[6] = ($likeRecord[6] / $numLikeRecord) * 100;
								$likeRecordAvg[6] = number_format($likeRecordAvg[6], 2, '.', '');
							}


							if (max($likeRecordAvg[1], $likeRecordAvg[2], $likeRecordAvg[3], $likeRecordAvg[4], $likeRecordAvg[5], $likeRecordAvg[6]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="likeRecordDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','11','flow','Flow')">
											Flow (<span class="red"><?php echo $likeRecord[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','11','the_lyrics','The lyrics')">
											The lyrics (<span class="red"><?php echo $likeRecord[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','11','production','Production')">
											Production (<span class="red"><?php echo $likeRecord[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','11','hook_or_chorus','Hook or chorus')">
											Hook or chorus (<span class="red"><?php echo $likeRecord[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','11','overall_sound_or_style','Overall sound or style')">Overall sound or style (<span class="red"><?php echo $likeRecord[5]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','11','nada','Nada')">
											Nada(<span class="red"><?php echo $likeRecord[6]; ?></span>)</a><br />

									</p>


								</div>
							<?php } else { ?> <div id="likeRecordDiv" style="display:none;"></div> <?php  } ?>

							<!-- graph 12 -->

							<div style="clear:both;"></div>
							<?php

							$numAnotherFormat = 0;
							$anotherFormat = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
							$anotherFormatAvg = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
							foreach ($reviews['data'] as $review) {

								if (strcmp($review->anotherformat, 'No') == 0) {
									$anotherFormat[1]++;
									$numAnotherFormat++;
								} else if (strcmp($review->anotherformat, 'CD') == 0) {
									$anotherFormat[2]++;
									$numAnotherFormat++;
								} else if (strcmp($review->anotherformat, 'Vinyl') == 0) {
									$anotherFormat[3]++;
									$numAnotherFormat++;
								} else if (strcmp($review->anotherformat, 'Higher Quality File') == 0) {
									$anotherFormat[4]++;
									$numAnotherFormat++;
								} else if (strcmp($review->anotherformat, '0') == 0) {
									$anotherFormat[5]++;
									$numAnotherFormat++;
								}
							}


							if ($numAnotherFormat != 0) {

								$anotherFormatAvg[1] = ($anotherFormat[1] / $numAnotherFormat) * 100;
								$anotherFormatAvg[1] = number_format($anotherFormatAvg[1], 2, '.', '');

								$anotherFormatAvg[2] = ($anotherFormat[2] / $numAnotherFormat) * 100;
								$anotherFormatAvg[2] = number_format($anotherFormatAvg[2], 2, '.', '');

								$anotherFormatAvg[3] = ($anotherFormat[3] / $numAnotherFormat) * 100;
								$anotherFormatAvg[3] = number_format($anotherFormatAvg[3], 2, '.', '');

								$anotherFormatAvg[4] = ($anotherFormat[4] / $numAnotherFormat) * 100;
								$anotherFormatAvg[4] = number_format($anotherFormatAvg[4], 2, '.', '');

								$anotherFormatAvg[5] = ($anotherFormat[5] / $numAnotherFormat) * 100;
								$anotherFormatAvg[5] = number_format($anotherFormatAvg[5], 2, '.', '');
							}



							if (max($anotherFormatAvg[1], $anotherFormatAvg[2], $anotherFormatAvg[3], $anotherFormatAvg[4], $anotherFormatAvg[5]) > 0) {

							?>
								<div class="col-sm-9">
									<div id="anotherFormatDiv" style="width: 100%; height:400px;"></div>
								</div>
								<div class="col-sm-3">

									<div class="space-20"></div>
									<p>View Members who Chose: </p>

									<p>
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','12','No','No')">
											No (<span class="red"><?php echo $anotherFormat[1]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','12','CD','CD')">
											CD (<span class="red"><?php echo $anotherFormat[2]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','12','Vinyl','Vinyl')">
											Vinyl (<span class="red"><?php echo $anotherFormat[3]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','12','Higher Quality File','Higher quality file')">
											Higher quality file (<span class="red"><?php echo $anotherFormat[4]; ?></span>)</a><br />
										<a href="javascript:void()" role="button" class="green" onclick="showPopup('<?php echo $track->id; ?>','12','0','Does not apply to me')">Does not apply to me (<span class="red"><?php echo $anotherFormat[5]; ?></span>)</a>

									</p>
								</div>
							<?php } else { ?> <div id="anotherFormatDiv" style="display:none;"></div> <?php  } ?>
						</div><!-- /.span -->
					</div><!-- /.row -->
					<div style="clear:both;"></div>
					<h1>Comments</h1>
					<div id="commentsDiv">

						<div class="row">
							<ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
								<?php if ($currentPageNo !== $numPages) {

								?>
									<li class="<?php echo $firstPageLink; ?>"><a href="javascript:void(0);" <?php if ($firstPageLink !== 'disabled') { ?> onclick="goToCommentsPage('<?php echo $currentPage; ?>','1')" <?php } ?>>
											<< </a>
									</li>
									<li class="<?php echo $preLink; ?>"><a href="javascript:void(0);" <?php if ($firstPageLink !== 'disabled') { ?> onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>')" <?php } ?>>
											< </a>
									</li>
								<?php }

								?>
								<li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
								<?php if ($currentPageNo !== $numPages) {

								?>
									<li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>')"> > </a></li>
									<li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
								<?php

								}
								//echo '__YSYS';die('__');
								?>
							</ul>
						</div>

						<?php $i = 1;
						$divCount = 1;


						foreach ($comments['data'] as $review) {


							if ($divCount == 1) {  ?> <div class="row"> <?php }

														?> <div class="col-sm-6" style="float:left;"> <?php
																				$stagename = urldecode($review->stagename);
																				$city = urldecode($review->city);
																				$state = urldecode($review->state);

																				$comment = urldecode($review->additionalcomments); ?>


									<!-- Modal -->
									<div id="reviews<?php echo $review->id; ?>" class="modal fade" role="dialog">
										<div class="modal-dialog" style="width:80%;">

											<!-- Modal content-->
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title"><?php echo $stagename; ?> Review</h4>
												</div>
												<div class="modal-body mCustomScrollbar" id="reviewsBody<?php echo $review->id; ?>">

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												</div>
											</div>

										</div>
									</div>

									<?php echo $i . '.'; ?> <b><a href="member_view?mid=<?php echo $review->member; ?>" target="_blank"><?php echo $stagename; ?></a> <?php echo $city . ', ' . $state; ?></b><br>


									<div style="margin-left:19px; color:#e43e99;" id="commentsDiv<?php echo $review->id; ?>">
										<?php if (strlen($comment) < 1) { ?>
											<i><a href="javascript:void(0);" style="color:#b32f85;">"No Comments"</a></i>
										<?php } else { ?>
											<i><a href="javascript:void(0);" style="color:#b32f85;" onclick="open_review('<?php echo $review->id; ?>')">"<?php echo $comment; ?>"</a>
												<a href="javascript:void(0);" onclick="remove_comment('<?php echo $review->id; ?>','<?php echo $stagename; ?>')" title="Remove this comment">
													<i style="padding-left:10px; color:#edd2e2;" class="fa fa-trash" aria-hidden="true"></i>
												</a>
												<?php if($review->is_approved == '0') { ?>
												<a href="javascript:void(0);" onclick="approve_comment('<?php echo $review->id; ?>','<?php echo $stagename; ?>')" title="Approve this comment">
													<i style="padding-left:10px; color:#edd2e2;" class="fa fa-thumbs-up" aria-hidden="true"></i>
												</a>
												<?php }else{ ?>
												<a href="javascript:void(0);" onclick="unapprove_comment('<?php echo $review->id; ?>','<?php echo $stagename; ?>')" title="Unapprove this comment">
													<i style="padding-left:10px; color:#edd2e2;" class="fa fa-thumbs-down" aria-hidden="true"></i>
												</a>
												<?php } ?>
											</i>
										<?php } ?>


										<!--<a href="#"><i class="fa fa-times" aria-hidden="true"></i> this comment</a>-->
									</div> <?php echo '<br><br>';
											$i++;

											?>
								</div> <?php

										if ($divCount == 2) {
											echo '</div>';
											$divCount = 1;
										} else {
											$divCount++;
										}
									}

										?>
								</div> <!--comments div-->

								<div class="hr hr-18 dotted hr-double"></div>


								<div id="membersList" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content" id="membersListContent">

										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>

								<!-- PAGE CONTENT ENDS -->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.page-content -->

			<script>
				function removeAudio() {

					var divId = document.getElementById('divId').value;

					if (divId > 1) {
						var divIdMinus = parseInt(divId) - 1;
						document.getElementById('divId').value = divIdMinus;
						$("#html" + divId).remove();
					}

				}

				function moreAudio() {



					var divId = document.getElementById('divId').value;
					var divIdPlus = parseInt(divId) + 1;
					document.getElementById('divId').value = divIdPlus;

					var parentDiv = document.createElement("div");
					parentDiv.setAttribute('id', 'html' + divIdPlus);


					var smDiv1 = document.createElement("div");
					smDiv1.setAttribute('class', 'col-sm-6 form_group');

					var smDiv2 = document.createElement("div");
					smDiv2.setAttribute('class', 'col-sm-9');

					var label1 = document.createElement("label");
					label1.setAttribute('class', 'col-sm-3 control-label no-padding-right');

					var textnode1 = document.createTextNode("Version");
					label1.appendChild(textnode1);


					var input1 = document.createElement("input");
					input1.setAttribute('type', 'text');
					input1.setAttribute('name', 'version' + divIdPlus);
					input1.setAttribute('id', 'version' + divIdPlus);
					input1.setAttribute('class', 'col-xs-10 col-sm-10');

					smDiv2.appendChild(input1);
					smDiv1.appendChild(label1);
					smDiv1.appendChild(smDiv2);



					var smDiv3 = document.createElement("div");
					smDiv3.setAttribute('class', 'col-sm-6 form_group');

					var smDiv4 = document.createElement("div");
					smDiv4.setAttribute('class', 'col-sm-9');

					var label2 = document.createElement("label");
					label2.setAttribute('class', 'col-sm-3 control-label no-padding-right');

					var textnode2 = document.createTextNode("File");
					label2.appendChild(textnode2);


					var input2 = document.createElement("input");
					input2.setAttribute('type', 'file');
					input2.setAttribute('name', 'audio' + divIdPlus);
					input2.setAttribute('id', 'audio' + divIdPlus);
					input2.setAttribute('class', 'col-xs-10 col-sm-10');

					smDiv4.appendChild(input2);
					smDiv3.appendChild(label2);
					smDiv3.appendChild(smDiv4);



					parentDiv.appendChild(smDiv1);
					parentDiv.appendChild(smDiv3);



					var clearboth = document.createElement("div");
					clearboth.setAttribute('class', 'clearDiv');
					document.getElementById('audioFiles').appendChild(clearboth);

					document.getElementById('audioFiles').appendChild(parentDiv);





				}
			</script>


			<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



			<script type="text/javascript">
				google.charts.load("current", {
					packages: ["corechart"]
				});

				google.charts.setOnLoadCallback(drawChart1);
				google.charts.setOnLoadCallback(drawChart2);
				google.charts.setOnLoadCallback(drawChart3);
				google.charts.setOnLoadCallback(drawChart4);
				google.charts.setOnLoadCallback(drawChart5);
				google.charts.setOnLoadCallback(drawChart6);
				google.charts.setOnLoadCallback(drawChart7);
				google.charts.setOnLoadCallback(drawChart8);
				google.charts.setOnLoadCallback(drawChart9);
				google.charts.setOnLoadCallback(drawChart10);
				google.charts.setOnLoadCallback(drawChart11);
				google.charts.setOnLoadCallback(drawChart12);




				function drawChart1() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' 5 - <?php echo $whatYouThinkAvg[5] . ' %'; ?>', <?php echo $whatYouThink[5]; ?>],
						[' 4 - <?php echo $whatYouThinkAvg[4] . ' %'; ?>', <?php echo $whatYouThink[4]; ?>],
						[' 3 - <?php echo $whatYouThinkAvg[3] . ' %'; ?>', <?php echo $whatYouThink[3]; ?>],
						[' 2 - <?php echo $whatYouThinkAvg[2] . ' %'; ?>', <?php echo $whatYouThink[2]; ?>],
						[' 1 - <?php echo $whatYouThinkAvg[1] . ' %'; ?>', <?php echo $whatYouThink[1]; ?>]
					]);

					var options = {
						title: 'WHAT DO YOU THINK ABOUT THIS SONG?',
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('whatYouThinkDiv'));
					chart.draw(data, options);
				}

				function drawChart2() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Digital Waxx - <?php echo $whereHeardAvg[1] . ' %'; ?>', <?php echo $whereHeard[1]; ?>],
						[' Radio - <?php echo $whereHeardAvg[2] . ' %'; ?>', <?php echo $whereHeard[2]; ?>],
						[' Satellite Radio - <?php echo $whereHeardAvg[3] . ' %'; ?>', <?php echo $whereHeard[3]; ?>],
						[' College Radio - <?php echo $whereHeardAvg[4] . ' %'; ?>', <?php echo $whereHeard[4]; ?>],
						[' Mixtape - <?php echo $whereHeardAvg[5] . ' %'; ?>', <?php echo $whereHeard[5]; ?>],
						[' Club  - <?php echo $whereHeardAvg[6] . ' %'; ?>', <?php echo $whereHeard[6]; ?>],
						[' Internet - <?php echo $whereHeardAvg[7] . ' %'; ?>', <?php echo $whereHeard[7]; ?>],
						[' Video - <?php echo $whereHeardAvg[8] . ' %'; ?>', <?php echo $whereHeard[8]; ?>],
						[' Other - <?php echo $whereHeardAvg[9] . ' %'; ?>', <?php echo $whereHeard[9]; ?>]
					]);

					var options = {
						title: 'WHERE DID YOU HEAR THIS SONG FIRST?',
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('whereHeardDiv'));
					chart.draw(data, options);
				}

				function drawChart3() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Yes - <?php echo $alreadyHaveAvg[1] . ' %'; ?>', <?php echo $alreadyHave[1]; ?>],
						[' No - <?php echo $alreadyHaveAvg[2] . ' %'; ?>', <?php echo $alreadyHave[2]; ?>]
					]);

					var options = {
						title: 'DO YOU ALREADY HAVE THIS SONG?',
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('alreadyHaveDiv'));
					chart.draw(data, options);
				}

				function drawChart4() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Yes - <?php echo $willPlayAvg[1] . ' %'; ?>', <?php echo $willPlay[1]; ?>],
						[' No - <?php echo $willPlayAvg[2] . ' %'; ?>', <?php echo $willPlay[2]; ?>]
					]);

					var options = {
						title: 'WILL YOU PLAY THIS SONG?',
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('willPlayDiv'));
					chart.draw(data, options);
				}


				function drawChart5() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Immediately - <?php echo $howSoonAvg[1] . ' %'; ?>', <?php echo $howSoon[1]; ?>],
						[' Next Week - <?php echo $howSoonAvg[2] . ' %'; ?>', <?php echo $howSoon[2]; ?>],
						[' In Two Weeks - <?php echo $howSoonAvg[3] . ' %'; ?>', <?php echo $howSoon[3]; ?>],
						[' One Month - <?php echo $howSoonAvg[4] . ' %'; ?>', <?php echo $howSoon[4]; ?>],
						[' Never - <?php echo $howSoonAvg[5] . ' %'; ?>', <?php echo $howSoon[5]; ?>],
						[' Does Not Apply To Me - <?php echo $howSoonAvg[6] . ' %'; ?>', <?php echo $howSoon[6]; ?>]
					]);

					var options = {
						title: "HOW SOON DO YOU THINK YOU'LL PLAY THIS SONG?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('howSoonDiv'));
					chart.draw(data, options);
				}



				function drawChart6() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' 1-3 - <?php echo $howManyPlaysAvg[1] . ' %'; ?>', <?php echo $howManyPlays[1]; ?>],
						[' 4-7 - <?php echo $howManyPlaysAvg[2] . ' %'; ?>', <?php echo $howManyPlays[2]; ?>],
						[' 7-10 - <?php echo $howManyPlaysAvg[3] . ' %'; ?>', <?php echo $howManyPlays[3]; ?>],
						[' None - <?php echo $howManyPlaysAvg[4] . ' %'; ?>', <?php echo $howManyPlays[4]; ?>],
						[' Does Not Apply To Me - <?php echo $howManyPlaysAvg[5] . ' %'; ?>', <?php echo $howManyPlays[5]; ?>]
					]);

					var options = {
						title: "HOW MANY TIMES WILL YOU PLAY THIS SONG (per week)?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('howManyPlaysDiv'));
					chart.draw(data, options);
				}

				function drawChart7() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Commercial Radio - <?php echo $formatsAvg[1] . ' %'; ?>', <?php echo $formats[1]; ?>],
						[' Satellite Radio - <?php echo $formatsAvg[2] . ' %'; ?>', <?php echo $formats[2]; ?>],
						[' College Radio - <?php echo $formatsAvg[3] . ' %'; ?>', <?php echo $formats[3]; ?>],
						[' Internet - <?php echo $formatsAvg[4] . ' %'; ?>', <?php echo $formats[4]; ?>],
						[' Clubs - <?php echo $formatsAvg[5] . ' %'; ?>', <?php echo $formats[5]; ?>],
						[' Mix Tapes - <?php echo $formatsAvg[6] . ' %'; ?>', <?php echo $formats[6]; ?>],
						[' Music Videos - <?php echo $formatsAvg[7] . ' %'; ?>', <?php echo $formats[7]; ?>]
					]);

					var options = {
						title: "WHAT FORMATS DO YOU THINK WILL HELP BREAK THIS SONG IN YOUR MARKET(check all that apply)?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('formatsDiv'));
					chart.draw(data, options);
				}

				function drawChart8() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Yes - <?php echo $goDistanceAvg[1] . ' %'; ?>', <?php echo $goDistance[1]; ?>],
						[' No - <?php echo $goDistanceAvg[2] . ' %'; ?>', <?php echo $goDistance[2]; ?>]
					]);

					var options = {
						title: "DO YOU THINK THIS RECORD WILL GET ANY SUPPORT?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,

					};

					var chart = new google.visualization.PieChart(document.getElementById('goDistanceDiv'));
					chart.draw(data, options);
				}

				function drawChart9() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Market Visits - <?php echo $labelSupportAvg[1] . ' %'; ?>', <?php echo $labelSupport[1]; ?>],
						[' More Street Marketing - <?php echo $labelSupportAvg[2] . ' %'; ?>', <?php echo $labelSupport[2]; ?>],
						[' Interview on My Show or Station - <?php echo $labelSupportAvg[3] . ' %'; ?>', <?php echo $labelSupport[3]; ?>],
						[' Local Live Performance - <?php echo $labelSupportAvg[4] . ' %'; ?>', <?php echo $labelSupport[4]; ?>],
						[' Do a Regional Remix - <?php echo $labelSupportAvg[5] . ' %'; ?>)', <?php echo $labelSupport[5]; ?>],
						[' Other - <?php echo $labelSupportAvg[6] . ' %'; ?>', <?php echo $labelSupport[6]; ?>],
						[' Scrap the Project - <?php echo $labelSupportAvg[7] . ' %'; ?>', <?php echo $labelSupport[7]; ?>],
						[' Shoot a Video - <?php echo $labelSupportAvg[8] . ' %'; ?>', <?php echo $labelSupport[8]; ?>],
						[' Nothing - <?php echo $labelSupportAvg[9] . ' %'; ?>', <?php echo $labelSupport[9]; ?>]
					]);

					var options = {
						title: "HOW SHOULD THE LABEL SUPPORT THIS PROJECT?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('labelSupportDiv'));
					chart.draw(data, options);
				}


				function drawChart10() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Play It - <?php echo $howSupportAvg[1] . ' %'; ?>', <?php echo $howSupport[1]; ?>],
						[' Nothing, I Will Not Support It - <?php echo $howSupportAvg[2] . ' %'; ?>', <?php echo $howSupport[2]; ?>],
						[' Remix It - <?php echo $howSupportAvg[3] . ' %'; ?>', <?php echo $howSupport[3]; ?>],
						[' Does Not Apply To Me - <?php echo $howSupportAvg[4] . ' %'; ?>', <?php echo $howSupport[4]; ?>]
					]);

					var options = {
						title: "HOW WILL YOU SUPPORT THIS PROJECT?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('howSupportDiv'));
					chart.draw(data, options);
				}

				function drawChart11() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' Flow - <?php echo $likeRecordAvg[1] . ' %'; ?>', <?php echo $likeRecord[1]; ?>],
						[' The lyrics - <?php echo $likeRecordAvg[2] . ' %'; ?>', <?php echo $likeRecord[2]; ?>],
						[' Production - <?php echo $likeRecordAvg[3] . ' %'; ?>', <?php echo $likeRecord[3]; ?>],
						[' Hook or chorus - <?php echo $likeRecordAvg[4] . ' %'; ?>', <?php echo $likeRecord[4]; ?>],
						[' Overall sound or style - <?php echo $likeRecordAvg[5] . ' %'; ?>', <?php echo $likeRecord[5]; ?>],
						[' Nada - <?php echo $likeRecordAvg[6] . ' %'; ?>', <?php echo $likeRecord[6]; ?>]
					]);

					var options = {
						title: "WHAT DO YOU LIKE MOST ABOUT THIS RECORD?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('likeRecordDiv'));
					chart.draw(data, options);
				}

				function drawChart12() {
					var data = google.visualization.arrayToDataTable([
						['Task', 'Hours per Day'],
						[' No - <?php echo $anotherFormatAvg[1] . ' %'; ?>', <?php echo $anotherFormat[1]; ?>],
						[' CD - <?php echo $anotherFormatAvg[2] . ' %'; ?>', <?php echo $anotherFormat[2]; ?>],
						[' Vinyl - <?php echo $anotherFormatAvg[3] . ' %'; ?>', <?php echo $anotherFormat[3]; ?>],
						[' Higher quality file - <?php echo $anotherFormatAvg[4] . ' %'; ?>', <?php echo $anotherFormat[4]; ?>],
						[' Does not apply to me - <?php echo $anotherFormatAvg[5] . ' %'; ?>', <?php echo $anotherFormat[5]; ?>]
					]);

					var options = {
						title: "DO YOU WANT THIS SONG IN ANOTHER FORMAT?",
						is3D: true,
						backgroundColor: 'none',
						titleTextStyle: {
							color: '#4c8fbd'
						},
						legendTextStyle: {
							color: '#4c8fbd'
						},
						sliceVisibilityThreshold: .001,
					};

					var chart = new google.visualization.PieChart(document.getElementById('anotherFormatDiv'));
					chart.draw(data, options);
				}






				function showPopup(tid, graphId, val, label) {



					$.ajax({
						url: "track_review_members_list?tid=" + tid + "&graphId=" + graphId + "&label=" + label + "&val=" + val,
						success: function(result) {

							$("#membersListContent").html(result);
							$('#membersList').modal('show');
						}
					});
				}

				function showMemberReview(rid, tid, graphId, val, label) {
					$.ajax({
						url: "track_member_review?rid=" + rid + "&tid=" + tid + "&graphId=" + graphId + "&label=" + label + "&val=" + val,
						success: function(result) {

							$("#membersListContent").html(result);
							$('#membersList').modal('show');
						}
					});
				}

				function goToCommentsPage(page, pid)

				{

					//     window.location = ;

					var xhttp = new XMLHttpRequest();

					xhttp.onreadystatechange = function() {

						if (this.readyState == 4 && this.status == 200) {

							document.getElementById("commentsDiv").innerHTML =

								this.responseText;

						}

					};

					xhttp.open("GET", page + "&commentPage=" + pid, true);

					xhttp.send();

				}

				function open_review(reviewId) {
					$.ajax({
						url: "track_review?reviewId=" + reviewId + "&popup=1",
						success: function(result) {
							document.getElementById('reviewsBody' + reviewId).innerHTML = result;
						}
					});
					$('#reviews' + reviewId).modal('show');
				}

				function remove_comment(commentId, stagename) {

					if (confirm("Do you want to remove " + stagename + " comment?")) {

						$.ajax({
							url: "track_review?commentId=" + commentId + "&removeComment=1",
							success: function(result) {

								var obj = JSON.parse(result);
								var count = obj.length;

								if (obj.status == 1) {
									document.getElementById('commentsDiv' + commentId).innerHTML = '<i>"No Comments"</i>';
								}
							}
						});
					}



				}

				function approve_comment(commentId, stagename) {

					if (confirm("Do you want to approve " + stagename + " comment?")) {
						$('.processing_loader_gif').show();
						$.ajax({
							url: "track_review?commentId=" + commentId + "&approveComment=1",
							success: function(result) {
								$('.processing_loader_gif').hide();
								var obj = JSON.parse(result);
								var count = obj.length;

								if (obj.status == 1) {
									alert('Track comment approved.');
									location.reload();
									//document.getElementById('commentsDiv' + commentId).innerHTML = '<i>"No Comments"</i>';
								}
							}
						});
					}
				}
				function unapprove_comment(commentId, stagename) {

					if (confirm("Do you want to unapprove " + stagename + " comment?")) {
						$('.processing_loader_gif').show();
						$.ajax({
							url: "track_review?commentId=" + commentId + "&unapproveComment=1",
							success: function(result) {
								$('.processing_loader_gif').hide();
								var obj = JSON.parse(result);
								var count = obj.length;

								if (obj.status == 1) {
									alert('Track comment unapproved.');
									location.reload();
									//document.getElementById('commentsDiv' + commentId).innerHTML = '<i>"No Comments"</i>';
								}
							}
						});
					}
				}
			</script>
			@endsection