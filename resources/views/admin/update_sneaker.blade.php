@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
	<div class="main-content-inner">

		<!-- #section:basics/content.breadcrumbs -->
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try {
					ace.settings.check('breadcrumbs', 'fixed')
				} catch (e) {}
			</script>


			<ul class="breadcrumb">
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i>Update SNEAKER</li>
			</ul>
		</div>
		
		<?php //pArr($sneaker);
		
		  $name='';
		  $id='';
		  $desc='';
		  $price='';
		  $status='';
		  $img='';
		 $p_img='';
		?>
		<?php foreach ($sneaker as $value){
		    $name=$value->name;
		    $id=$value->id;
		    $desc=$value->description;
		    $price=$value->price;
		    $status=$value->status;
		    $img=$value->img_path;
		    if(!empty($value->pCloudFileID)){
        	        $p_img=$value->pCloudFileID;
        	  }
 
		}
		?>
		
		
		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
		    <?php if(!empty($result)){?>
		    <div class="<?php echo $class;?>" role="alert">
              <?php echo $result;?>
            </div>
            <?php }?>
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
					   
						<?php $route = route('sneaker_edit'); ?>
						<form method="post" action="<?php echo $route;?>" enctype=multipart/form-data id="add_sneaker">
							@csrf
							
							<input type ="hidden" name="sneaker_id" value="<?php echo $id;?>">
							<div class="form-group">
								<label for="news_title">Name</label>
								<input type="text" class="form-control" id="news_title" name="sneaker_title" aria-describedby="emailHelp" placeholder="Enter Title"required value="<?php if(!empty($name)){echo $name;}?>">

							</div>
							<div class="form-group">
								<label for="news_description">Description</label>
								<div class="row">
                    				 <div class="col-xs-12">
                    					<?php if(isset($alert_class)) 
                    					   { ?>
                    					<div class="<?php echo $alert_class; ?>">
                    					   <button class="close" data-dismiss="alert">
                    					   <i class="ace-icon fa fa-times"></i>
                    					   </button>
                    					   <?php echo $alert_message; ?>
                    					</div>
                    					<?php } ?>
                    					<script src="{{ asset('assets_admin/assets/ckeditor/ckeditor.js') }}"></script>
                    					<script src="{{ asset('assets_admin/assets/ckeditor/samples/js/sample.js') }}"></script>
                    					<link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/css/samples.css') }}">
                    					<link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">
                    					<!--<form action="" method="post">-->
                    						@csrf
                    					   <textarea class="ckeditor" name="description"><?php if(!empty($desc)){echo stripslashes(urldecode($desc));} ?></textarea>
                    					   <br />
                    					   <!--<input type="submit"  name="addBannerText" value="Submit" class="btn btn-sm btn-primary"  />-->
                    					<!--</form>-->
                    				 </div>
                    				 <!-- /.span -->
                    			  </div>
							</div>
							<div class="form-group form-check">
								<label class="form-check-label" for="news_image">Sneaker Image</label>
								<input type="file" class="form-check-input" id="sneaker_img" name="sneaker_img">
                               <?php 
                                if(!empty($p_img)){?>
							       <?php $src=  url('/pCloudImgDownload.php?fileID='.$p_img);?>
							       	<img id="previewImg" src="<?php if(!empty($src)){echo $src;}else{echo asset('public/images/noimage-avl.jpg');}?>" width="200" height="200" class="img-responsive up-ar-img" />

					    	<?php }
							    else if(!empty($img)){ ?>
							<?php	$path = asset('public/image_sneaker/'.$img);?>
								<img id="previewImg" src="<?php echo $path ?>" class="img-responsive up-ar-img"  width="200" height="200">
								<?php }else{ ?>
								<?php $artWork = asset('product_images/default1.png');?>
								
								<img src="<?php echo $artWork; ?>" width="200" height="200" />
								
								<?php } ?>
							</div>
							
							<div class="form-group form-check">
								<label class="form-check-label" for="sneaker_price">Sneaker Price</label>
								<input type="number" class="form-check-input" id="sneaker_price" name="sneaker_price" value="<?php if(!empty($price)){echo $price;}?>">

							</div>
							
	                       	<div class="form-group form-check">
								<label class="form-check-label" for="sneaker_status">Status</label>
								<select name="sneaker_status" id="sneaker_status">
								    <option value="0" <?php if($status==0){echo "selected";}?>>DRAFT</option>
								    <option value="1" <?php if($status==1){echo "selected";}?>>PUBLISH</option>							
								    </select>

							</div>
							
							
							<button type="submit" class="btn btn-primary">Submit</button>
							<br><br>
							<a href="{{route('sneaker')}}"><button type="button" class="btn btn-warning">BACK</button></a>
							
							

						</form>
					</div>
				</div>
				<!-- /.span -->
			</div>
			<!-- /.row -->

			<!-- PAGE CONTENT ENDS -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                                <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
                                <script>
                                 jQuery(document).ready(function(){
                                   
                                        jQuery("#add_sneaker").submit(function() {
                                            
                                            if(jQuery("#news_title").val().length > 0){
                                                if(jQuery("#news_title").val().trim().length==0){
                                                    alert("Please enter all the details.");
                                                    return false;
                                                }
                                                
                                                else if(parseInt (jQuery("#sneaker_price").val())<=0 || jQuery("#sneaker_price").val()=='' ){
                                                    alert("Please enter amount greater than 0.");
                                                    return false;
                                                }
                                            }else{
                                            alert("Please enter all the details.");
                                            return false;
                                            }
                                            
                                          
                                        });
                                     
                                 });
                            
                            
                            
                            </script>

<!-- /.page-content -->
@endsection