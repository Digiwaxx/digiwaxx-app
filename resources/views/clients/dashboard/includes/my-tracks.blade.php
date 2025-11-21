<!--album-download-->

<div class="album-d-sec my-tracks-cls">
<?php if(count($rightTracks['data'])>0) { ?>
<h5><i class="fa fa-music"></i> MY TRACKS</h5>
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
			<div class="track-info">
				<p class="atst"><span><?php echo urldecode($track->title); ?></span></p>
				<p class="alb"><?php echo urldecode($track->album); ?></p>
				<p class="up-dt"><?php
					$added = explode(' ', $track->added);
					$added = explode('-', $added[0]);
					echo $added = $added[1].'/'.$added[2].'/'.$added[0];

					?></p>
				<p class="rlb"><?php echo urldecode($track->label); ?></p>
			</div>
			<div class="st-blk">
			<?php if(!empty($trackData)){ ?>
				<div class="st">
					<i class="fa fa-star"></i>
					<span><?php echo $trackData[$track->id]['rating']; ?></span>
				</div>

				<div class="st">
					<i class="fa fa-cloud-download"></i>
					<span><?php echo $trackData[$track->id]['downloads']; ?></span>
				</div>

				<div class="st">
					<i class="fa fa-play-circle"></i>
					<span><?php echo $trackData[$track->id]['plays']; ?></span>
				</div>				
				<?php } ?>
				<div class="st">
					<i class="fa fa-comment"></i>
					<span>0</span>
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