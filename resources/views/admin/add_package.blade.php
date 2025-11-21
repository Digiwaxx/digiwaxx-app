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
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> ADD PACKAGE</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
						<?php $route = route('insert_package'); ?>
						<form method="post" action="<?php echo $route;?>" enctype=multipart/form-data>
							@csrf
							<div class="form-group">
								<label for="package_name">PACKAGE NAME</label>
								<input type="text" class="form-control" id="package_name" name="package_name" aria-describedby="emailHelp" placeholder="Enter Name" required>

							</div>
							<div class="form-group">
								<label for="package_price">PACKAGE PRICE</label>
								<input type="number" class="form-control" id="package_price" name="package_price" aria-describedby="emailHelp" placeholder="Enter Price" required>

							</div>
							<div class="form-group">
								<label for="package_available">PACKAGE AVAILABLE TO</label>
								<select class="form-select form-select-lg mb-3" aria-label="Default select example" name="package_available" id="package_available" required onchange="check_mem()">
								  <option value="" selected>--PLEASE SELECT--</option>    
                                  <option value="1">MEMBER</option>
                                  <option value="2">CLIENT</option>
                                  
                                </select>
	
							</div>
							<div class="form-group">
								<label class="form-check-label" for="package_type">PACKAGE TYPE</label>
								<select class="form-select form-select-lg mb-3" aria-label="Default select example" name="package_type" id="package_type" >
                                  <option value="" selected>--PLEASE SELECT--</option>
                                  <option value="Basic">BASIC</option>
                                  <optin value="Monthly">MONTHLY</optin>
                                  <option value="Half Yearly">HALF YEARLY</option>
                                  <option value="Yearly">YEARLY</option>
                                  
                                </select>

							</div>

    						<div class="form-group dyn_fea" id="features-1" >
    						  
                                     <label for="add_features">ADD PACKAGE FEATURES</label>
                                    
                                   <div class="row">
                                      <div class="col-sm-10" >
                                         <input type="text" id="feature_1" name="features[]" class="form-control" required placeholder="Enter Feature">
                                     </div>
                                  </div>
                      
                                     
                             </div>
                             <div class="form-group" >
                                    <button type="button" id="add_more"class="btn btn-success">ADD MORE FEATURES</button>
                             </div>
                                  
                                  

							<button type="submit" class="btn btn-primary">Submit</button>
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
             
            jQuery("#features-"+x+"").after("<div class='form-group dyn_fea' id='features-"+y+"'><div class='row'><div class='col-sm-10'><input type='text' id='feature_1' name='features[]' placeholder='Enter Feature' class='form-control' required></div><div class='col-sm-2'><button type='button' onclick="+str2+" class='btn btn-danger'>REMOVE</button></div></div></div>");
            });
      });
       
    </script>
    <script>
        function remove_div(id){
            console.log(id);
            
             $("#features-"+id).empty();
            //  $("#features-"+id).remove();
        }
        
        function check_mem(){
            if(jQuery("#package_available").val()=="1"){
                console.log("hello");
                $('#package_type').prop('required', true);
            }
            else{
                console.log("none");
                $('#package_type').prop('required', false);
            }
        }
    </script>

@endsection

