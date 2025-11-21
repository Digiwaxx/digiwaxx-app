@extends('layouts.client_dashboard')

@section('content')
<section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		<div class="container">
			<div class="row">
				<div class="col-xl-9 col-12">
					<div class="dash-heading">
						<h2>My Dashboard</h2>
						<a href="<?php echo url("Client_submit_track_edit?tId=" . $_GET['tId']); ?>">
							<button type="button" class="prvw_trck_btn">EDIT TRACK INFO</button>
						</a>
					</div>
					<div class="tabs-section">
						<div class="col-lg-12 col-md-12">
							<div class=" f-block trk-info-blk">
								<h1>SUBMIT A TRACK - PREVIEW</h1>
								<div class="col-lg-3 col-md-3 col-sm-3">
									<div>
										<?php
										if (!empty($track['data'][0]->pCloudFileID)) {
											$imgSrc =  url('/pCloudImgDownload.php?fileID=' . $track['data'][0]->pCloudFileID);
										} else if (strlen($track['data'][0]->imgpage) > 3) {
											$imgSrc = 'ImagesUp/' . $track['data'][0]->imgpage;
										} else {

											$imgSrc = 'assets/img/track-logo.png';
										}
										?>
										<img src="<?php echo $imgSrc; ?>" class="img-responsive ar-fsize">
									</div>
									<div style="clear:both;"></div>
								</div>
								<div class="col-lg-9 col-md-9 col-sm-9">
									<h1 class="tinfo">TRACK INFO</h1>
									<div class="trk-det">
										<p class="t1"><label>Artist: </label> <span> <?php echo urldecode($track['data'][0]->artist); ?></span></p>
										<p class="t1"><label>Title: </label> <span><?php echo urldecode($track['data'][0]->title); ?></span></p>
										<?php
										$albumType = '';

										if ($track['data'][0]->albumType == 1) {

											$albumType = 'Single';
										} else if ($track['data'][0]->albumType == 2) {

											$albumType = 'Album';
										} else if ($track['data'][0]->albumType == 3) {

											$albumType = 'EP';
										} else if ($track['data'][0]->albumType == 4) {

											$albumType = 'Mixtape';
										} ?>

										<p class="t1"><label>Album Type: </label> <span><?php echo $albumType; ?></span></p>

										<p class="t1"><label>Album: </label> <span><?php echo urldecode($track['data'][0]->album); ?></span></p>

										<p class="t1"><label>Contact Email: </label> <span><?php echo urldecode($track['data'][0]->contact_email); ?></span></p>

										<p class="t1"><label>Time: </label> <span><?php echo urldecode($track['data'][0]->time); ?></span></p>

										<p class="t1"><label>BPM: </label> <span><?php echo urldecode($track['data'][0]->bpm); ?></span></p>

										<?php if (!empty($track['data'][0]->releasedate)) { ?>
											<p class="t1"><label>Release Date: </label>
												<span>
												<?php $dt = explode(' ', $track['data'][0]->releasedate);
												$date = explode('-', $dt[0]);
												echo $date = $date[1] . '-' . $date[2] . '-' . $date[0];
											}  ?>
												</span>
											</p>

											<p class="t1"><label>Label: </label> <span><?php echo urldecode($track['data'][0]->label); ?></span></p>

											<p class="t1"><label>Website: </label> <span> <?php echo urldecode($track['data'][0]->link); ?></span></p>

											<?php if (strlen($track['data'][0]->link1) > 0) { ?>

												<p class="t1"><label>Website1 : </label> <span> <?php echo urldecode($track['data'][0]->link1); ?></span></p>

											<?php }
											if (strlen($track['data'][0]->link2) > 0) { ?>

												<p class="t1"><label>Website2 : </label> <span> <?php echo urldecode($track['data'][0]->link2); ?></span></p>

											<?php } ?>

											<p class="t1"><label>Producer: </label> <span><?php echo urldecode($track['data'][0]->producers); ?></span></p>

											<p class="t1"><label>Genre: </label> <span><?php echo $track['data'][0]->genre; ?></span></p>

											<p class="t1"><label>Sub Genre: </label> <span><?php echo $track['data'][0]->subGenre; ?></span></p>

											<p class="t1"><label>Facebook: </label> <span><?php echo $track['data'][0]->facebookLink; ?></span></p>

											<p class="t1"><label>Twitter: </label> <span><?php echo $track['data'][0]->twitterLink; ?></span></p>

											<p class="t1"><label>Instagram: </label> <span><?php echo $track['data'][0]->instagramLink; ?></span></p>

											<p class="t1"><label>Video URL: </label> <span><?php echo urldecode($track['data'][0]->videoURL); ?></span></p>

											<p class="t1"><label>More information: </label> <span><?php echo urldecode($track['data'][0]->moreinfo); ?></span></p>

									</div>
								</div>

								<div class="col-lg-12 col-md-12 col-sm-12">

									<div style="margin:40px 20px;">
										<div class="form-group">
											<div class="dash-heading">
												<h2>Audio Files</h2>
											</div>
											<?php
											$audio = (object) $track['data'][0];
											$i = 1;
											?>

											<table id="simple-table" class="table table-bordered table-hover">
												<thead>
													<tr>
														<th class="center" width="100">S. No</th>
														<th class="center">Track</th>
													</tr>
												</thead>
												<tbody>
													<?php
													for ($j = 1; $j <= 6; $j++) {
														$key = "amr$j";
														if (!empty($audio->$key)) { ?>
															<tr>
																<td class="center"><?php echo $i; ?></td>
																<td>
																	<audio controls style="width:100%;">
																		<?php if (strpos($audio->$key, '.mp3') !== false) { ?>
																			<source src="<?php echo asset('AUDIO/' . $audio->$key); ?>" type="audio/mp3">
																		<?php } else {
																			$fileid = (int) $audio->$key;
																			$getlink = !empty($fileid) ? url('download.php?fileID=' . $fileid) : ''; ?>
																			<source src="<?php echo $getlink; ?>" type="audio/mp3">
																		<?php } ?>
																		Your browser does not support the audio element.
																	</audio>
																</td>
															</tr>
													<?php
															$i++;
														}
													}
													?>
												</tbody>
											</table>
										</div>

										<div style="clear:both;"></div>
										<div class="satp-blk" style="margin-top:20px;">
											<div class="help-text">
												Happy with what you see? <br> Click on the submit button below!
											</div>
											<div class="form-group">
												<form action="" method="post">
													@csrf
													<input name="confirmPreview" class="bsp prvw_trck_btn" value="SUBMIT" type="submit">
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- eof middle block -->
				<div class="col-xl-3 col-12">
					@include('clients.dashboard.includes.my-tracks')
				</div>
			</div>
		</div>
	</div>
</section>


<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
	var googletag = googletag || {};
	googletag.cmd = googletag.cmd || [];
</script>

<script>
	googletag.cmd.push(function() {
		googletag.defineSlot('/21741445840/336x280', [240, 133], 'div-gpt-ad-1539597853871-0').addService(googletag.pubads());
		googletag.pubads().enableSingleRequest();
		googletag.enableServices();
	});
</script>


<!-- /21741445840/336x280 -->
<div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
	<script>
		googletag.cmd.push(function() {
			googletag.display('div-gpt-ad-1539597853871-0');
		});
	</script>
</div>



@endsection