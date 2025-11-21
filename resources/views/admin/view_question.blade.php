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
							<li class="active"><i class="ace-icon fa fa-list list-icon"></i> View Question</li>
						</ul><!-- /.breadcrumb -->
						
						
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
									<div class="col-xs-8">
									
									<?php $question = $questions['data'][0]; ?>
									
									<div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name"> Question </div>

													<div class="profile-info-value">
														<?php echo $question->question; ?>
													</div>
												</div>
												<div class="profile-info-row">
													<div class="profile-info-name"> Type </div>

													<div class="profile-info-value">
														<?php echo $question->type; ?>
													</div>
												</div>
												
												<?php if((strcmp($question->type,'check')==0) || (strcmp($question->type,'radio')==0)) { ?>
												<div class="profile-info-row">
													<div class="profile-info-name"> Options </div>

													<div class="profile-info-value">
														<?php if($options['numRows']>0) { 
														  foreach($options['data'] as $option)
														  {
														    echo $option->answer; echo '<br />';
														  
														  } 
														 } ?>
													</div>
												</div>
												<?php } ?>
									</div>	
									
									<div class="searchForm " id="nav-search" style="float:right; margin-top:10px;">
						  <a href="<?php echo url("admin/store/edit_question?pid=".$_GET['pid']."&qid=".$question->question_id); ?>">Edit Question</a>
						  
						</div>
								
									
									</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
					
					
                    @endsection 