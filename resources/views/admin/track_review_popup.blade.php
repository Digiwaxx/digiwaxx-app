<div class="page-content">
<div class="space-10"></div>

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12">
			<?php $review = $reviews['data'][0]; ?>									
			<div>												
						

			<div class="profile-info-row">
				<div class="profile-info-name">WHAT'S REALLY GOOD? (Give your additional comments 320 Character Limit).  </div>

				<div class="profile-info-value">
					 <?php echo urldecode($review->additionalcomments); ?>
				</div>
			</div>
			</div>
				
				
			</div><!--final-->			
				
			</div><!-- /.span -->
		</div><!-- /.row -->

		<div class="hr hr-18 dotted hr-double"></div>

	
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->