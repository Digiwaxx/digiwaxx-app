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
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> VIEW NEWS</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<?php 
// 	print_r($news_details);
    $p_img='';
    $n_title='';
    $n_desc='';
    $n_img='';
    $price='';
    $date='';
    $status='';
    $p_img='';
		foreach($sneaker as $news_detail){
		    $n_title = $news_detail->name;    
		    $n_desc = $news_detail->description;
		    $n_img = $news_detail->img_path;
		    $price=$news_detail->price;
		    $date=$news_detail->created_on;
		    $status=$news_detail->status;
		    if(!empty($news_detail->pCloudFileID)){
        	        $p_img=$news_detail->pCloudFileID;
        	  }
 
		    
		}
		?>
		<div class="page-content">
			<div class="row">
			   	<div class="profile-user-info profile-user-info-striped">
		        	<div class="profile-info-row">
						<div class="profile-info-name" style=" text-align: center;"> NAME </div>

						<div class="profile-info-value">
							<?php echo $n_title;?>
                        </div>
	            	</div>
	                	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> DESCRIPTION </div>
    
    						<div class="profile-info-value">
    							<?php echo stripslashes(urldecode($n_desc));?>
                            </div>
    	            	</div>
    	            	<?php if(!empty($n_img)){?>
    	            	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> IMAGE </div>
    
    						<div class="profile-info-value">
    			     		<?php
						
								if(!empty($p_img)){?>
							       <?php $src=  url('/pCloudImgDownload.php?fileID='.$p_img);?>
							       	<img id="previewImg" src="<?php if(!empty($src)){echo $src;}else{echo asset('public/images/noimage-avl.jpg');}?>" width="200" height="200" class="img-responsive up-ar-img" />

					    	<?php }
								else if(!empty($n_img) && file_exists(base_path('public/image_sneaker/'.$n_img))){ 
								$path = asset('public/image_sneaker/'.$n_img);?>
								<img id="previewImg" src="<?php echo $path ?>" width="200" height="200" class="img-responsive up-ar-img">
								<?php }else{ ?>
								<?php  $artWork =  asset('public/images/noimage-avl.jpg');?>
    								
    								<img src="<?php echo $artWork; ?>"  height="50" width="50" alt="album" />
								<?php } ?>
                            </div>
    	            	</div>
    	            <?php }?>
    	             	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> PRICE </div>
    
    						<div class="profile-info-value">
    						   $ <?php echo $price;?>
                            </div>
    	            	</div>
    	            	 	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> STATUS </div>
    
    						<div class="profile-info-value">
    							<?php if ($status==1){echo ("PUBLISHED");}else{echo ("DRAFT");}?>
                            </div>
    	            	</div>
    	            	 	<div class="profile-info-row">
    						<div class="profile-info-name" style=" text-align: center;"> CREATED ON </div>
    
    						<div class="profile-info-value">
    							<?php $date = new DateTime($date);
                                    echo  $result = $date->format('d-M-Y');
                                 ?>
                            </div>
    	            	</div>
	            </div>	
	            <br>
	            <br>
	            <?php $route = route('sneaker'); ?>
				<form action="<?php echo $route; ?>">
				<button class="btn btn-primary hBack" type="submit">GO BACK</button>
				</form>

				<!-- /.span -->
			</div>
			<!-- /.row -->

			<!-- PAGE CONTENT ENDS -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</div>
<!-- /.page-content -->
@endsection