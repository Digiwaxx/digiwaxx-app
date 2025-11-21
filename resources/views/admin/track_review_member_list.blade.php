
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>
													</button>
													<?php echo $pageTitle; ?>
												</div>
											</div>

											<div class="modal-body" style="height:400px; overflow-y:scroll;">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
														
															<th>S. No.</th>
															<th>Member</th>

															
														</tr>
													</thead>

													<tbody>
													<?php 
													
													if($members['numRows']>0)
													{
													
													$i=1; foreach($members['data'] as $member) { ?>
														<tr>
															<td><?php echo $i; ?></td>
						<td>
							<a href="javascript:void()" role="button" class="green" onclick="showMemberReview('<?php echo $member->id; ?>','<?php echo $tid; ?>','<?php echo $graphId; ?>','<?php echo $val; ?>','<?php echo $label; ?>')">
							  <?php echo urldecode($member->stagename); ?>
						  </a>
					   </td>
														</tr>
														<?php $i++; } 
														
														} else { ?>
														
														<tr><td colspan="2"> No data found.</td></tr>
														<?php } ?>

														
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

											</div>
									