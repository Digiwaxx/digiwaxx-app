<div class="col-lg-3 d-md-none d-sm-none d-none d-lg-none d-xl-block">
	<div class="client-rb">
		<div class="ntf-block" style="display:block;">
			<h1><i class="fa fa-flag"></i>MY TRACKS</h1>
			<div class="ntf-lst-blk">
			<?php if(count($rightTracks['data'])>0) { ?>
	<ul class="ul-mytraks">
<?php 
	$i=1;
	foreach ($rightTracks['data'] as $track)
	 {  
		
		if(strlen($track->thumb)>4)
		{
			if (file_exists(public_path('thumbs/'.$track->thumb))){
				$src = asset('public/thumbs/'.$track->thumb);  
			 }else{
				$src = asset('public/images/noimage-avl.jpg'); 
			 }							
		}
		else
		{
			if (file_exists(public_path('ImagesUp/'.$track->imgpage))){
				$src = asset('public/ImagesUp/'.$track->imgpage);  
			 }else{
				$src = asset('public/images/noimage-avl.jpg'); 
			 }					 
		 }
		 ?>
		<li class="trk">
			<p class="atst"><a href="#"><?php echo urldecode($track->title); ?></a></p>
			<p class="alb"><?php echo urldecode($track->album); ?></p>
			<p class="up-dt"><?php
				$added = explode(' ', $track->added);
				$added = explode('-', $added[0]);
				echo $added = $added[1].'/'.$added[2].'/'.$added[0];

				?></p>
			<p class="rlb"><?php echo urldecode($track->label); ?></p>
			<div class="clearfix st-blk">
				<div class="st">
					<i class="fa fa-star"></i>
					<span><?php echo $trackData[$track->id]['rating']; ?></span>
				</div>

				<div class="st">
					<i class="fa fa-cloud-download"></i>
					<span><?php echo $trackData[$track->id]['downloads']; ?></span>
				</div>

				<div class="st">
					<i class="fa fa-comment"></i>
					<span>0</span>
				</div>

				<div class="st">
					<i class="fa fa-play-circle"></i>
					<span><?php echo $trackData[$track->id]['plays']; ?></span>
				</div>

				<div class="st">
					<i class="fa fa-share-alt"></i>
					<span>0</span>
				</div>

			</div><!-- eof st-blk-->
		 </li>
		<!-- eof trk -->
	<?php } $i++; } ?>
</ul>
			</div>
		</div><!-- eof ntf-block-->

		<div class="mts-block" style="display:none;">
			<h1><i class="fa fa-music"></i>MY TRACKS</h1>
			<div class="mts-lst-blk">
				<ul class="mts-list">
					<li class="trk">
						<p class="atst">Puff Daddy & The Family ft. Pharrell</p>
						<p class="alb">Finna Get Loose</p>
						<p class="up-dt">2015/10/20</p>
						<p class="rlb">Bad Boy Records</p>
						<div class="clearfix st-blk">
							<div class="st">
								<i class="fa fa-star"></i>
								<span>4</span>
							</div>

							<div class="st">
								<i class="fa fa-cloud-download"></i>
								<span>21</span>
							</div>

							<div class="st">
								<i class="fa fa-comment"></i>
								<span>2</span>
							</div>

							<div class="st">
								<i class="fa fa-play-circle"></i>
								<span>31</span>
							</div>

							<div class="st">
								<i class="fa fa-share-alt"></i>
								<span>3</span>
							</div>

						</div><!-- eof st-blk -->
					</li><!-- eof trk -->


					<li class="trk">
						<p class="atst">Chronixx</p>
						<p class="alb">Eternal Fire</p>
						<p class="up-dt">2015/10/20</p>
						<p class="rlb">Zinc Fence Records</p>
						<div class="clearfix st-blk">
							<div class="st">
								<i class="fa fa-star"></i>
								<span>4</span>
							</div>

							<div class="st">
								<i class="fa fa-cloud-download"></i>
								<span>21</span>
							</div>

							<div class="st">
								<i class="fa fa-comment"></i>
								<span>2</span>
							</div>

							<div class="st">
								<i class="fa fa-play-circle"></i>
								<span>31</span>
							</div>

							<div class="st">
								<i class="fa fa-share-alt"></i>
								<span>3</span>
							</div>

						</div><!-- eof st-blk -->
					</li><!-- eof trk -->


					<li class="trk">
						<p class="atst">Curren$y feat. August Alsina & Lil� Wayne</p>
						<p class="alb">Bottom of the Bottle</p>
						<p class="up-dt">2015/10/20</p>
						<p class="rlb">Bad Boy Records</p>
						<div class="clearfix st-blk">
							<div class="st">
								<i class="fa fa-star"></i>
								<span>4</span>
							</div>

							<div class="st">
								<i class="fa fa-cloud-download"></i>
								<span>21</span>
							</div>

							<div class="st">
								<i class="fa fa-comment"></i>
								<span>2</span>
							</div>

							<div class="st">
								<i class="fa fa-play-circle"></i>
								<span>31</span>
							</div>

							<div class="st">
								<i class="fa fa-share-alt"></i>
								<span>3</span>
							</div>

						</div><!-- eof st-blk -->
					</li><!-- eof trk -->


					<li class="trk">
						<p class="atst">Puff Daddy & The Family ft. Pharrell</p>
						<p class="alb">Finna Get Loose</p>
						<p class="up-dt">2015/10/20</p>
						<p class="rlb">Bad Boy Records</p>
						<div class="clearfix st-blk">
							<div class="st">
								<i class="fa fa-star"></i>
								<span>4</span>
							</div>

							<div class="st">
								<i class="fa fa-cloud-download"></i>
								<span>21</span>
							</div>

							<div class="st">
								<i class="fa fa-comment"></i>
								<span>2</span>
							</div>

							<div class="st">
								<i class="fa fa-play-circle"></i>
								<span>31</span>
							</div>

							<div class="st">
								<i class="fa fa-share-alt"></i>
								<span>3</span>
							</div>

						</div><!-- eof st-blk -->
					</li><!-- eof trk -->


				</ul>
			</div>
		</div><!-- eof mts-block-->


		<div class="d-app">
			<h2>YOUR RECORD POOL</h2>
			<h1>EVERYWHERE</h1>

			<div class="lg"><img src="{{ asset('public/images/logo.png') }}" class="img-fluid"></div>

			<div class="d-btn">
				<span>APP Coming soon</span>
			</div>


		</div><!-- eof d-app -->

	</div><!-- eof client-rb -->
</div><!-- eof right block -->

<!--- Download App Code for Smaller Devices -->

<!-- <div class="hidden col-md-12  col-sm-12 col-xs-12">
	<div class="d-app">
		<h2>YOUR RECORD POOL</h2>
		<h1>EVERYWHERE</h1>

		<div class="lg"><img src="{{ asset('public/images/logo.png') }}"></div>

		<div class="d-btn">
			<span>APP Coming soon</span>
		</div>


		<div class="f-social">
			<a href="https://twitter.com/Digiwaxx"><i class="fa fa-twitter"></i></a>
			<a href="https://www.facebook.com/digiwaxx"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="https://www.instagram.com/digiwaxx"><i class="fa fa-instagram"></i></a>
			<a href="https://www.linkedin.com/company/digiwaxx-media"><i class="fa fa-linkedin"></i></a>
		</div>

	</div> --><!-- eof d-app -->


<!-- </div> -->
<!--- eof of download app -->