@extends('layouts.app')

<style>
	.banner-charts-page {
		background-image: url('<?php echo url('public/images/banner-chart.png'); ?>');
		padding: 165px 0px;
	}

	.form_loader {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		border: 10px solid #b32672;
		border-radius: 50%;
		border-top: 10px solid #000;
		width: 64px;
		height: 64px;
		-webkit-animation: spin 2s linear infinite;
		animation: spin 2s linear infinite;
		z-index: 999;
	}

	.page-navigate.disabled {
		pointer-events: none;
		cursor: default;
	}
</style>

@section('content')

<!-- <div class="charts header-bottom">
            	<div class="container">                    
						<?php

						//if(!empty($bannerText)){

						//$banner_get_text = $bannerText[0]->bannerText;

						//}
						//else{

						//   $banner_get_text = '';
						// }

						// echo $banner_get_text; 
						?>
                </div>
     </div> --><!-- eof header-bottom -->

<!-- </div> --><!-- eof header -->
<section class="top-banner banner-charts-page">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-sm-12">
				<div class="banner-text">
					<h2> <?php echo stripslashes(urldecode($bannerText[0]->bannerText)); ?></h2>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="charts-block">
	<div class="container">
		<div class="form_loader" style="display:none;"></div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12">
				<div class="td-block">
					<h1>Top Downloads</h1>


					<div class="charts-tab">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="sg-li-cls active"><a href="#td-week" aria-controls="home" role="tab" data-toggle="tab" class="sg-tabs page-navigate">This Week</a></li>
							<li role="presentation" class="sg-li-cls"><a href="#td-month" aria-controls="profile" role="tab" data-toggle="tab" class="sg-tabs page-navigate">This Month</a></li>
							<li role="presentation" class="sg-li-cls"><a href="#td-year" aria-controls="messages" role="tab" data-toggle="tab" class="sg-tabs page-navigate">This Year</a></li>

						</ul>

						<!-- Tab panes -->
						<div class="tab-content" id="tabContent">
							<div role="tabpanel" class="tab-pane active" id="td-week">

								<?php
								if (!empty($numPages1)) {

									$numPages1_get = $numPages1;
								} else {

									$numPages1_get = '';
								}

								if ($numPages1_get > 1) { ?>
									<div class="fby-blk clearfix">
										<div style="float:right;">
											<div class="pgm">
												<?php echo $start1 + 1; ?> - <?php echo $start1 + $limit1; ?> OF <?php echo $num_records1; ?>
											</div>

											<div class="tnav clearfix">
												<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo1 - 1; ?>','1','td-week');" class="page-navigate"><i class="fa  fa-angle-double-left"></i></a></span>
												<span class="num"><?php echo $currentPageNo1; ?></span>
												<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo1 + 1; ?>','1','td-week');" class="page-navigate"><i class="fa  fa-angle-double-right"></i></a></span>
											</div>
										</div>
									</div>
								<?php } ?>

								<?php

								$i = 1;

								if (!empty($weekDownloads)) {
									foreach ($weekDownloads['data'] as $track) {
										if (!empty($track->pCloudFileID)) {
											$trkId = (int)$track->pCloudFileID;

											$img = url('/pCloudImgDownload.php?fileID=' . $trkId);
										} else if (strlen($track->thumb) > 4) {
											if (file_exists(public_path('thumbs/' . $track->thumb))) {
												$img = asset('public/thumbs/' . $track->thumb);
											} else {
												$img = asset('public/images/noimage-avl.jpg');
											}
										} else if (strlen($track->imgpage) > 4) {

											// echo '<div id="testing-data" class="'.$track->imgpage.'" style="display:none"><pre>'.print_r($track).'</pre></div>';
											if (file_exists(public_path('ImagesUp/' . $track->imgpage))) {
												$img = asset('public/ImagesUp/' . $track->imgpage);

											} else {
												$img = asset('public/images/noimage-avl.jpg');
											}
										} else {
											$img = asset('public/images/noimage-avl.jpg');
										}
										?>

										<div class="record">
											<div class="row">
												<div class="col-sm-1 col-1 c1">
													<?php echo $i; ?>
												</div>

												<div class="col-sm-2 col-lg-2 col-3 pr-0 c2">
													<?php if (!empty(Session::get('memberId'))) { ?>
														<a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>"><img class="img-responsive" src="<?php echo $img; ?>"></a>
													<?php } else { ?>
														<span><img class="img-responsive" src="<?php echo $img; ?>"></a>
														<?php } ?>
												</div>

												<div class="col-sm-8 col-lg-8 col-7 c3">
													<p><?php echo stripslashes(urldecode($track->title)); ?></p>
													<p class="alb"><?php echo stripslashes(urldecode($track->artist)); ?></p>
												</div>

												<div class="col-sm-1 col-1 c4 pl-0">
													<?php if ($track->downloads != '') { ?>
														<p><?php //echo $track->downloads; ?></p>
														<?php if (!empty(Session::get('memberId'))) { ?>
															<p class="dwd"><a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>">
																	<i class="fa fa-arrow-circle-o-down"></i></a></p>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div><!-- eof record -->
								<?php $i++;
									}
								} ?>

							</div><!-- eof td-week -->



							<div role="tabpanel" class="tab-pane" id="td-month">


								<?php
								if (!empty($numPages2)) {

									$numPages2_get = $numPages2;
								} else {

									$numPages2_get = '';
								}

								if ($numPages2_get > 1) { ?>
									<div class="fby-blk clearfix">
										<div style="float:right;">
											<div class="pgm">
												<?php echo $start2 + 1; ?> - <?php echo $start2 + $limit2; ?> OF <?php echo $num_records2; ?>
											</div>

											<div class="tnav clearfix">
												<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo2 - 1; ?>','2','td-month');" class="page-navigate"><i class="fa  fa-angle-double-left"></i></a></span>
												<span class="num"><?php echo $currentPageNo2; ?></span>
												<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo2 + 1; ?>','2','td-month');" class="page-navigate"><i class="fa  fa-angle-double-right"></i></a></span>
											</div>
										</div>
									</div>
								<?php } ?>

								<?php $i = 1;
								if (!empty($monthDownloads)) {
									foreach ($monthDownloads['data'] as $track) {
										if (!empty($track->pCloudFileID)) {
											$trkId = (int)$track->pCloudFileID;

											$img = url('/pCloudImgDownload.php?fileID=' . $trkId);
										} else if (strlen($track->thumb) > 4) {
											if (file_exists(public_path('thumbs/' . $track->thumb))) {
												$img = asset('public/thumbs/' . $track->thumb);
											} else {
												$img = asset('public/images/noimage-avl.jpg');
											}
										} else if (strlen($track->imgpage) > 4) {
											if (file_exists(public_path('ImagesUp/' . $track->imgpage))) {
												$img = asset('public/ImagesUp/' . $track->imgpage);
											} else {
												$img = asset('public/images/noimage-avl.jpg');
											}
										} else {
											$img = asset('public/images/noimage-avl.jpg');
										}
								?>
										<div class="record">
											<div class="row">
												<div class="col-sm-1 col-1 c1">
													<?php  echo $i; 
													?>
												</div>

												<div class="col-sm-2 col-lg-2 col-3 pr-0 c2">
													<?php if (!(empty(Session::get('memberId')))) { ?>
														<a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>"><img class="img-responsive" src="<?php echo $img; ?>"></a>
													<?php } else { ?>
														<span><img class="img-responsive" src="<?php echo $img; ?>"></a>
														<?php } ?>
												</div>

												<div class="col-sm-8 col-lg-8 col-7 c3">
													<p><?php echo urldecode($track->title); ?></p>
													<p class="alb"><?php echo stripslashes(urldecode($track->artist)); ?></p>
												</div>

												<div class="col-sm-1 col-1 c4 pl-0">
													<?php if ($track->downloads != '') { ?>
														<p><?php  //echo $track->downloads; 
															?></p>
														<?php if (!(empty(Session::get('memberId')))) { ?>
															<p class="dwd"><a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>">
																	<i class="fa fa-arrow-circle-o-down"></i></a></p>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div><!-- eof record -->

								<?php $i++;
									}
								} ?>

							</div><!-- eof td-month -->



							<div role="tabpanel" class="tab-pane" id="td-year">

								<?php
								if (!empty($numPages3)) {

									$numPages3_get = $numPages3;
								} else {

									$numPages3_get = '';
								}

								if ($numPages3_get > 1) { ?>
									<div class="fby-blk clearfix">
										<div style="float:right;">
											<div class="pgm">
												<?php echo $start3 + 1; ?> - <?php echo $start3 + $limit3; ?> OF <?php echo $num_records3; ?>
											</div>

											<div class="tnav clearfix">
												<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo2 - 1; ?>','3','td-year');" class="page-navigate"><i class="fa  fa-angle-double-left"></i></a></span>
												<span class="num"><?php echo $currentPageNo3; ?></span>
												<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo2 + 1; ?>','3','td-year');" class="page-navigate"><i class="fa  fa-angle-double-right"></i></a></span>
											</div>
										</div>
									</div>
								<?php } ?>

								<?php $i = 1;

								if (!empty($yearDownloads)) {
									foreach ($yearDownloads['data'] as $track) {
										if (!empty($track->pCloudFileID)) {
											$trkId = (int)$track->pCloudFileID;

											$img = url('/pCloudImgDownload.php?fileID=' . $trkId);
										} else if (strlen($track->thumb) > 4) {
											if (file_exists(public_path('thumbs/' . $track->thumb))) {
												$img = asset('public/thumbs/' . $track->thumb);
											} else {
												$img = asset('public/images/noimage-avl.jpg');
											}
										} else if (strlen($track->imgpage) > 4) {
											if (file_exists(public_path('ImagesUp/' . $track->imgpage))) {
												$img = asset('public/ImagesUp/' . $track->imgpage);
											} else {
												$img = asset('public/images/noimage-avl.jpg');
											}
										} else {
											$img = asset('public/images/noimage-avl.jpg');
										}
								?>
										<div class="record">
											<div class="row">
												<div class="col-sm-1 col-1 c1">

													<?php  echo $i; 
													?>
												</div>

												<div class="col-sm-2 col-lg-2 col-3 pr-0 c2">
													<?php if (!(empty(Session::get('memberId')))) { ?>
														<a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>"><img class="img-responsive" src="<?php echo $img; ?>"></a>
													<?php } else { ?>
														<span><img class="img-responsive" src="<?php echo $img; ?>"></a>
														<?php } ?>
												</div>

												<div class="col-sm-8 col-lg-8 col-7 c3">
													<p><?php echo urldecode($track->title); ?></p>
													<p class="alb"><?php echo stripslashes(urldecode($track->artist)); ?></p>
												</div>

												<div class="col-sm-1 col-1 c4 pl-0">
													<?php if ($track->downloads != '') { ?>
														<p><?php  //echo $track->downloads;  
															?></p>
														<?php if (!empty(Session::get('memberId'))) { ?>
															<p class="dwd"><a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>">
																	<i class="fa fa-arrow-circle-o-down"></i></a></p>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div><!-- eof record -->
								<?php $i++;
									}
								} ?>

							</div><!-- eof td-year -->

						</div><!-- eof tab-content -->

					</div><!-- eof charts-tab -->
				</div><!-- eof td-block -->
			</div><!-- eof col -->

			<div class="col-lg-6 col-md-6 col-sm-12">
				<div class="nr-block">
					<h1>New Releases</h1>

					<div class="nr-tab" id="nr-tab">

						<?php
						if (!empty($numPages4)) {

							$numPages4_get = $numPages4;
						} else {

							$numPages4_get = '';
						}
						if ($numPages4_get > 1) { ?>
							<div class="fby-blk clearfix">
								<div style="float:right;">
									<div class="pgm">
										<?php echo $start4 + 1; ?> - <?php echo $start4 + $limit4; ?> OF <?php echo $num_records4; ?>
									</div>

									<div class="tnav clearfix">
										<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo4 - 1; ?>','4','nr-tab');" class="page-navigate"><i class="fa  fa-angle-double-left"></i></a></span>
										<span class="num"><?php echo $currentPageNo4; ?></span>
										<span><a href="javascript:void(0);" onclick="getCharts('<?php echo $currentPageNo4 + 1; ?>','4','nr-tab');" class="page-navigate"><i class="fa  fa-angle-double-right"></i></a></span>
									</div>
								</div>
							</div>
						<?php } ?>

						<?php $i = 1;

						if (!empty($newest)) {
							foreach ($newest['data'] as $track) {
								if (!empty($track->pCloudFileID)) {
									$trkId = (int)$track->pCloudFileID;

									$img = url('/pCloudImgDownload.php?fileID=' . $trkId);
								} else if (strlen($track->thumb) > 4) {
									if (file_exists(public_path('thumbs/' . $track->thumb))) {
										$img = asset('public/thumbs/' . $track->thumb);
									} else {
										$img = asset('public/images/noimage-avl.jpg');
									}
								} else if (strlen($track->imgpage) > 4) {
									if (file_exists(public_path('ImagesUp/' . $track->imgpage))) {
										$img = asset('public/ImagesUp/' . $track->imgpage);
									} else {
										$img = asset('public/images/noimage-avl.jpg');
									}
								} else {
									$img = asset('public/images/noimage-avl.jpg');
								}
						?>
								<div class="record new-releass">
									<div class="row">
										<div class="col-lg-2 col-sm-2 col-3 c2 pr-0">
											<?php if (!empty(Session::get('memberId'))) { ?>
												<a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>"><img class="img-responsive" src="<?php echo $img; ?>"></a>
											<?php } else { ?>
												<span><img class="img-responsive" src="<?php echo $img; ?>"></span>
											<?php } ?>

										</div>

										<div class="col-lg-10 col-sm-10 col-9 c3">
											<p><?php echo urldecode($track->title); ?></p>
											<p class="alb"><?php echo stripslashes(urldecode($track->artist)); ?></p>
										</div>
									</div>
								</div><!-- eof record -->
						<?php $i++;
							}
						} ?>
					</div><!-- eof nr-tab -->
				</div><!-- eof nr-block -->
			</div><!-- eof col -->
		</div><!-- eof row -->
	</div><!-- eof container -->
</div><!-- eof charts-block -->
<script>
	function getCharts(pid, type, divId) {
		$.ajax({
			url: "Charts?page=" + pid + "&type=" + type + "&divId=" + divId,
			beforeSend: function() {
				$(".form_loader").show();
				$('.page-navigate').addClass('disabled');
			},
			success: function(result) {
				$(".form_loader").hide();
				$('.page-navigate').removeClass('disabled');
				document.getElementById(divId).innerHTML = result;
			}
		});
	}

	jQuery(document).ready(function() {

		$(document).on('click', '.sg-tabs', function() {
			var hrefAttr = $(this).attr('href');
			var currentEle = hrefAttr.replace('#', '');

			$('.sg-li-cls').removeClass('active');
			$('#tabContent .tab-pane').removeClass('active');
			$('#' + currentEle).addClass('active');
			$(this).parent('li').addClass("active");
		});
	});
</script>
@endsection