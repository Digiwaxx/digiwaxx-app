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
        <?php
                $my_count=1;
                $package_name="";
                $package_price="";
                $available_to="";
                $package_type = '';
                // dd($package_details);
                foreach($package_details as $value){
                    $package_name=$value->package_name;
                    $package_price=$value->package_price;
                    $package_type=$value->package_type;
                    $available_to=$value->available_to;
                    $package_features=json_decode($value->feature);
        
                } ?>

			<ul class="breadcrumb">
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> EDIT PACKAGE</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
						<?php $route = route('update_package'); ?>
						<form method="post" action="<?php echo $route;?>" enctype=multipart/form-data>
						    <input type="hidden" name="package_id" value="<?php echo $package_id;?>">
						    <input type="hidden" name="available_to" value="<?php echo $available_to;?>">
						   
							@csrf
							<div class="form-group">
								<label for="package_name">PACKAGE NAME</label>
								<input type="text" class="form-control" id="package_name" value="<?php echo $package_name;?>" name="package_name" aria-describedby="emailHelp" placeholder="Enter Name" required>

							</div>
							<div class="form-group">
								<label for="package_price">PACKAGE PRICE</label>
								<input type="number" class="form-control" id="package_price" value="<?php echo $package_price;?>" name="package_price" aria-describedby="emailHelp" placeholder="Enter Price" required>

							</div>
							<?php if($available_to == 2){ ?>
							
							<div class="form-group">
								<label for="package_type">PACKAGE TYPE</label>
								<select class="form-control" id="package_type" name="package_type" aria-describedby="emailHelp" placeholder="Select Type" required>
								    <option value="Monthly" <?php if(!empty($package_type && $package_type == 'Monthly'))echo 'selected'; ?> >Monthly</option>
								    <option value="Half Yearly" <?php if(!empty($package_type && $package_type == 'Half Yearly'))echo 'selected'; ?> >Half Yearly</option>
								    <option value="Yearly" <?php if(!empty($package_type && $package_type == 'Yearly'))echo 'selected'; ?> >Yearly</option>
								    <option value="Basic" <?php if(!empty($package_type && $package_type == 'Basic'))echo 'selected'; ?> >Basic</option>
								    </select
							</div>
							<?php } ?>
							
                        <div class="form-group">
                              <label for="add_features">ADD PACKAGE FEATURES</label>
                        </div>
                       
                        <?php foreach ($package_features as $value){?>
            						<div class="form-group dyn_fea" id="features-<?php echo $my_count;?>" >
            						  
                                           
                                            
                                           <div class="row">
                                              <div class="col-sm-10" >
                                                 <textarea class="form-control" id="feature_1" rows="2" name="features[]" required placeholder="Enter Feature"><?php echo $value;?></textarea>
                                             </div>
                                             <div class="col-sm-2">
                                                 <button type="button" onclick=remove_div('<?php echo $my_count;?>')  class="btn btn-danger">REMOVE</button>
                                             </div>
                                          </div>
                              
                                             
                                     </div>
                           <?php $my_count++;
                                }?>     
                             <div class="form-group" >
                                    <button type="button" id="add_more"class="btn btn-success">ADD MORE FEATURES</button>
                             </div>
                                  
                                  

							<button type="submit" class="btn btn-primary">UPDATE</button>
							<br>
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
<!-- /.page-content -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
    <script>
        jQuery(document).ready(function(){
            
            jQuery("#add_more").click(function(e){
                e.preventDefault();
            
               var x= jQuery('div.dyn_fea').length;
               var y=x+1;
               var str2="remove_div('"+y+"')";
             
            jQuery("#features-"+x+"").after("<div class='form-group dyn_fea' id='features-"+y+"'><div class='row'><div class='col-sm-10'><textarea id='feature_1' name='features[]' placeholder='Enter Feature' class='form-control' rows='2' required></textarea></div><div class='col-sm-2'><button type='button' onclick="+str2+" class='btn btn-danger'>REMOVE</button></div></div></div>");
            });
      });
       
    </script>
    <script>
        function remove_div(id){
            console.log(id);
          if(jQuery('div #feature_1').length==1){
              
              alert("Atleast One Feature Required");
              return false;
          }else{
             $("#features-"+id).empty();
          } 
            //  $("#features-"+id).remove();
        }
        

    </script>

@endsection

