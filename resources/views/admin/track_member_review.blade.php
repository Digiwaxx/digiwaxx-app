<div class="modal-header no-padding">
	<div class="table-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<span class="white">&times;</span>
		</button>
		<?php echo urldecode($review['data'][0]->fname) . '\'s review'; ?> &nbsp;
		<a href="javascript:void()" role="button" class="btn btn-sm btn-success" onclick="showPopup('<?php echo $tid; ?>','<?php echo $graphId; ?>','<?php echo $val; ?>','<?php echo $label; ?>')"> Back </a>
	</div>
</div>

<div class="modal-body">
	<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
		<tbody>
			<tr>
				<td>Member</td>
				<td><a target="_blank" href="https://app.digiwaxx.com/admin/member_view?mid=<?php echo urldecode($review['data'][0]->member);?>"><?php echo urldecode($review['data'][0]->fname); ?></a></td>
			</tr>
			<!--tr><td>Reviewer rank</td><td><--?php echo urldecode($review['data'][0]->fname); ?></td></tr>
	<tr><td>Member Rating</td><td><--?php echo urldecode($review['data'][0]->fname); ?></td></tr-->
			<tr>
				<td>Track</td>
				<td><?php echo urldecode($review['data'][0]->title); ?></td>
			</tr>
			<!--tr><td>Added</td><td><--?php echo urldecode($review['data'][0]->fname); ?></td></tr-->
			<tr>
				<td>What do you rate this song (on a scale of 1-5, 5 being the best 1 being the worst)?</td>
				<td><?php echo $review['data'][0]->whatrate; ?></td>
			</tr>
			<!--tr><td>Where did you hear this song first?</td><td><--?php echo $review['data'][0]->whereheard; ?></td></tr>
	<tr><td>Do you think this record will go the distance?</td><td><--?php echo $review['data'][0]->godistance; ?></td></tr>
	<tr><td>If I could A&R this record I would..</td><td><--?php echo urldecode($review['data'][0]->fname); ?></td></tr>
	<tr><td>How should the label support this project</td><td><--?php echo $review['data'][0]->labelsupport; ?></td></tr>
	<tr><td>How will you support this project?</td><td><--?php echo $review['data'][0]->howsupport; ?></td></tr> 
	<tr><td>I like this record for...</td><td><--?php echo $review['data'][0]->likerecord; ?></td></tr>
	<tr><td>Do you need this song in another format</td><td><--?php echo $review['data'][0]->anotherformat; ?></td></tr-->
			<tr>
				<td>Additional Comments</td>
				<td><?php echo urldecode($review['data'][0]->additionalcomments); ?></td>
			</tr>

		</tbody>
	</table>
</div>

<div class="modal-footer no-margin-top">
	<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
		<i class="ace-icon fa fa-times"></i>Close</button>
</div>