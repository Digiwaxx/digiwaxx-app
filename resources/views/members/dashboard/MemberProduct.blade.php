@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Member Product</h2>
              </div>
            <div class="tabs-section">
				<div class="mem-dblk f-block" style="margin-bottom:40px;">
				   <h1><?php echo strtoupper($products['data'][0]->name); ?></h1>
				   <!--mCustomScrollbar-->
				   <div class="trk-info-blk GSGG" style="height:auto; overflow:hidden;">
					  <div class="row">
						 <?php if(isset($alert_class)) 
							{ ?>
						 <div class="<?php echo $alert_class; ?>">
							<p><?php echo $alert_message; ?></p>
						 </div>
						 <?php } // print_r($formData); ?>
						 <div class="col-lg-5 col-md-5 col-sm-6 col-xs-4">
							<?php
							$ext = pathinfo(base_path('product_images/'.$products['data'][0]->emailimg), PATHINFO_EXTENSION);
							if(in_array($ext, array('jpeg','png','gif'))){
								if (file_exists(base_path('product_images/'.$products['data'][0]->emailimg))){
									$img = asset('product_images/'.$products['data'][0]->emailimg);
								}else{
									$img = asset('public/images/noimage-avl.jpg'); 
								  }
							}else{
								$img = asset('public/images/noimage-avl.jpg'); 
							  }

							?>
							<img src="<?php echo $img; ?>" class="img-responsive" />
						 </div>
						 <div class="col-lg-7 col-md-7 col-sm-6 col-xs-8 <?= $ext; ?>">
						 	<div class="track-info-wrap">
							<h1>PRODUCT INFO</h1>
							<?php
							   $company = '';
							   if($products['data'][0]->company_id==1)
							   {
							   $company = 'Adidas';
							   }
							   else if($products['data'][0]->company_id==2)
							   {
							   $company = 'Coca-Cola';
							   } ?>
							<div class="trk-det">
							   <p class="t1"><label>Company: </label> <span> <?php echo $company; ?> </span></p>
							   <?php if(isset($products['data'][0]->division)){ ?>
							   <p class="t1"><label>Brand Division: </label> <span><?php echo urldecode($products['data'][0]->division); ?></span></p>
							   <?php } ?>
							   <p class="t1"><label>Link: </label> <span><?php echo $products['data'][0]->link; ?></span></p>
							   <p class="t1"><label>Name of the product: </label> <span><?php echo $products['data'][0]->name; ?></span></p>
							   <p class="t1"><label>Model (color/flavor) </label> <span><?php echo $products['data'][0]->model; ?></span></p>
							   <p class="t1"><label>Product Details: </label> <span><?php echo $products['data'][0]->product_details; ?></span></p>
							   <p class="t1"><label>Features/Benefits: </label> <span><?php echo $products['data'][0]->product_technology; ?></span></p>
							   <p class="t1"><label>Gender: </label> <span> <?php echo $products['data'][0]->product_gender; ?> </span></p>
							   <p class="t1"><label>Suggested Price: </label> <span> <?php echo $products['data'][0]->suggested_price; ?> </span></p>
							   <p class="t1"><label>Launch Date: </label> <span> <?php echo $products['data'][0]->launch_date; ?> </span></p>
							</div>
							<!-- trk-det -->
						</div>
						 </div>
					  </div>
					  <div class="rew-trks">
					  </div>
					  <!-- eof rew-trks -->
					  <div class="rew-form">
						 <h1>REVIEW</h1>
						 <?php
							$i = 1;
							
							if($reviewed[$_GET['pid']]['options']['numRows']>0 || $reviewed[$_GET['pid']]['text']['numRows']>0)
							{
							 
							   foreach($questions['data'] as $question)  { ?>
						 <div class="q-item">
							<div class="row">
							   <?php if(strcmp($question->type,'text')==0) { ?>
							   <div class="col-lg-12">
								  <p class="q1"><?php echo $i.'. '.$question->question; ?></p>
								  <div class="form-group">
									 <?php foreach($reviewed[$_GET['pid']]['text']['data'] as $review)
										{
										 if($review->question_id == $question->question_id)
										 {
										  echo $review->answer;
										 }
										} ?>
								  </div>
							   </div>
							   <?php } else if(strcmp($question->type,'radio')==0) { ?>
							   <div class="col-lg-7 col-md-7 col-sm-7">
								  <p class="q1"><?php echo $i.'. '.$question->question; ?></p>
							   </div>
							   <div class="col-lg-5 col-md-5 col-sm-5">
								  <div class="form-group">
									 <?php
										if($answers[$question->question_id]['numRows']>0)
										{
										  foreach($answers[$question->question_id]['data'] as $answer)
											{
											foreach($reviewed[$_GET['pid']]['options']['data'] as $review)
											  {
											 if(($review->question_id == $question->question_id) && ($review->answer_id == $answer->answer_id))
											  {
												?>
									 <div class="radio dja"><label><?php echo $review->answer; ?></label></div>
									 <?php
										}
										}
										}
										} ?>
								  </div>
							   </div>
							   <?php }  else  if(strcmp($question->type,'check')==0) { ?>
							   <div class="col-lg-7 col-md-7 col-sm-7">
								  <p class="q1"><?php echo $i.'. '.$question->question; ?></p>
							   </div>
							   <div class="col-lg-5 col-md-5 col-sm-5">
								  <div class="form-group">
									 <?php
										if($answers[$question->question_id]['numRows']>0)
										{
										 foreach($answers[$question->question_id]['data'] as $answer)
										  {
										  
										   
											foreach($reviewed[$_GET['pid']]['options']['data'] as $review)
											{
											
										   if(($review->question_id == $question->question_id) && ($review->answer_id == $answer->answer_id))
											  {
										?>
									 <div class="radio dja"><label style="padding-left:0px;" class="checkboxclass"><?php echo $review->answer; ?></label></div>
									 <?php
										}
										
										}
										}
										}
										
										?>
								  </div>
							   </div>
							   <?php } ?>
							</div>
						 </div>
						 <!-- eof q-item -->
						 <?php $i++; } 
							}
							else if($questions['numRows']>0)
							{  
								?>
						 <form action="" method="post" id="reviewForm">
						 @csrf
							<?php foreach($questions['data'] as $question)  { ?>
							<div class="q-item">
							   <div class="row">
								  <?php if(strcmp($question->type,'text')==0) { ?>
								  <div class="col-lg-12">
									 <p class="q1"><?php echo $i.'. '.$question->question; ?></p>
									 <div class="form-group">
										<textarea class="form-control" onkeyup="countComments(this.value)" placeholder="" rows="5" name="text[<?php echo $question->question_id; ?>][]" id="comments"></textarea>
										<span id="comment_length">320</span>			
									 </div>
								  </div>
								  <?php } else if(strcmp($question->type,'radio')==0) { ?>
								  <div class="col-lg-7 col-md-7 col-sm-7">
									 <p class="q1"><?php echo $i.'. '.$question->question; ?></p>
								  </div>
								  <div class="col-lg-5 col-md-5 col-sm-5">
									 <div class="form-group">
										<?php
										   if($answers[$question->question_id]['numRows']>0)
										   {
											 foreach($answers[$question->question_id]['data'] as $answer)
											 {
											 ?>
										<div class="radio dja">                                	
										   <label>
										   <input name="radio[<?php echo $question->question_id; ?>][]" id="<?php echo $answer->product_question_answer_id; ?>" value="<?php echo $answer->answer_id; ?>" type="radio">
										   <?php echo $answer->answer; ?>
										   </label>
										</div>
										<?php
										   }
										   
										   }
										   
										   ?>
									 </div>
								  </div>
								  <?php }  else  if(strcmp($question->type,'check')==0) { ?>
								  <div class="col-lg-7 col-md-7 col-sm-7">
									 <p class="q1"><?php echo $i.'. '.$question->question; ?></p>
								  </div>
								  <div class="col-lg-5 col-md-5 col-sm-5">
									 <div class="form-group">
										<?php
										   if($answers[$question->question_id]['numRows']>0)
										   {
											 foreach($answers[$question->question_id]['data'] as $answer)
											 {
											 ?>
										<div class="radio dja">                                	
										   <label style="padding-left:0px;" class="checkboxclass">
										   <input name="check[<?php echo $question->question_id; ?>][]" id="<?php echo $answer->product_question_answer_id; ?>" value="<?php echo $answer->answer_id; ?>" type="checkbox">
										   <?php echo $answer->answer; ?>
										   </label>
										</div>
										<?php
										   }
										   
										   }
										   
										   ?>
									 </div>
								  </div>
								  <?php } ?>
							   </div>
							</div>
							<!-- eof q-item -->
							<?php $i++; } ?>
							<div class="q-item">
							   <div class="form-group">
								  <input name="submitReview" class="login_btn btn bsp" value="SUBMIT" type="submit">
							   </div>
							</div>
						 </form>
						 <?php  }else{ ?>
							No Review Available
						 <?php } ?>
					  </div>
					  <!-- eof rew-form -->
				   </div>
				   <!-- eof trk-info-blk -->
				</div>
				<!-- eof mem-dblk -->
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
     </div>
	 </section>
	 
@endsection