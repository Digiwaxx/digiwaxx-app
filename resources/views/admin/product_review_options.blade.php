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
							<li class="active"><i class="ace-icon fa fa-list list-icon"></i> Products</li>
						</ul><!-- /.breadcrumb -->
						
						
						<!-- #section:basics/content.searchbox -->
						<div class="nav-search searchForm " id="nav-search">
						  <a href="<?php echo url("admin/store/add_product_question?pid=".$_GET['pid']); ?>">Add Question</a>
						  
						</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
					
					<div class="row">
					
						
							<div class="space-10"></div>
							
							
							
						</div><!-- /.page-header -->
						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
									<?php 
									 if(isset($alert_message))
									 { 
									  ?>
									  
									  
									  <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
									  <?php 
									 
									 } 
									
									?>
										<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th class="center" width="80">S. No.</th>
													<th width="100" class="hidden-sm hidden-xs">Type</th>
													<th>Question</th>
													<th width="100">Action</th>
												</tr>
											</thead>

											<tbody>
											
											<?php 

                                            if(!empty($start)){

                                                $start = $start;
                                            }
                                            else{

                                                $start = 0;
                                            }
                                            
										$i = $start+1;
										if($questions['numRows']>0) {
										foreach($questions['data'] as $question)
										{
										
											   ?>
											 <tr>
													<td class="center"><?php echo $i; ?></td>
													<td class="hidden-sm hidden-xs"><?php echo $question->type; ?></td>
													<td><?php echo $question->question; ?></td>
													<td>
							<div class="btn-group">
															
	                        <a href="<?php echo url("admin/store/view_question?pid=".$_GET['pid']."&qid=".$question->question_id); ?>" title="View Question" class="btn btn-xs btn-success">
							   <i class="fa fa-eye bigger-120" aria-hidden="true"></i>
							</a>
							
							<a href="<?php echo url("admin/store/edit_question?pid=".$_GET['pid']."&qid=".$question->question_id); ?>" title="Edit Question" class="btn btn-xs btn-primary">
							   <i class="fa fa-pencil bigger-120" aria-hidden="true"></i>
							</a>
							
							
							 <button onclick="deleteRecord1('product_review_options?pid=<?php echo $_GET['pid']; ?>&','<?php  echo $question->question_id; ?>','Confirm delete <?php  echo $question->question; ?> ')" title="Delete Question" class="btn btn-xs btn-danger">
	 	<i class="ace-icon fa fa-trash-o bigger-120"></i>
	</button>


							</div>
							</td>
											</tr>
												<?php $i++; } } else { ?>
					   <tr><td colspan="6">No Data found.</td></tr>
					  <?php } ?>
                   </tbody>
				</table>
									</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
                    @endsection 
					
					