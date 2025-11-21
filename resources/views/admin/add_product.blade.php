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
            <li>
               <a href="#"><i class="ace-icon fa fa-list list-icon"></i> Products</a>
            </li>
            <li class="active">Add Product</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
         <div class="row">
            <div class="col-xs-12">
               <!-- PAGE CONTENT BEGINS -->
               <?php 
                  if(isset($alert_message))
                  {
                   ?>
               <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
               <?php 
                  }
                  ?>
               <form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()">
                  @csrf	
                  <div class="row">
                     <h3 class="header smaller lighter">
                        Product Information
                     </h3>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Company </label>
                        
                           <select style="color:rgb(169 169 169);" id="company" name="company" class="form-control">
                              <option value="0">Select</option>
                              <?php if($companies['numRows']>0) { 
                                 foreach($companies['data'] as $company) 
                                 { ?>
                              <option value="<?php echo $company->company_id; ?>"><?php echo $company->name; ?></option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Brand / Division </label>
                        
                           <input type="text" id="brand" name="brand" class="form-control" placeholder="Brand / Division" />
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Link </label>
                        
                           <input type="text" id="link" placeholder="Link" name="link" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Product Name </label>
                        
                           <input type="text" id="productName" placeholder="Product Name" name="productName" class="form-control">
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Model ( color / flavor ) </label>
                        
                           <input type="text" id="model" placeholder="Model ( color / flavor )" name="model" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1">Product Details</label>
                        
                           <input type="text" id="productDetails" placeholder="Product Details" name="productDetails" class="form-control">
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Features / Benefits </label>
                        
                           <input type="text" id="benefits" placeholder="Features / Benefits" name="benefits" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Digicoins </label>
                        
                           <input type="number" id="retailPrice" name="retailPrice" placeholder="Digicoins" class="form-control">
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1">Launch Date </label>
                        
                           <input type="date" id="datepicker" name="launchDate" placeholder="Launch Date" class="form-control">
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1">Sex/Gender</label>
                        
                           <select id="gender" name="gender" class="form-control" style="color:rgb(169 169 169);">
                              <option value="both">Both</option>
                              <option value="men">Men</option>
                              <option value="women">Women</option>
                           </select>
                        </div>
                     </div>
                     
                     <div class="col-sm-4">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Available to Members </label>
                        
                           <div class="radio">
                              <label>
                              <input name="active" type="radio" class="ace" value="1">
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="active" type="radio" class="ace" value="0">
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Reviewable </label>
                        
                           <div class="radio">
                              <label>
                              <input name="reviewable" type="radio" class="ace" value="1">
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="reviewable" type="radio" class="ace" value="0">
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-4">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Merch by digiwaxx </label>
                        
                           <div class="radio">
                              <label>
                              <input name="merch_digi" type="radio" class="ace" value="1">
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="merch_digi" type="radio" class="ace" value="0">
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> More Info. </label>
                        
                           <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control"></textarea>
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Image </label>
                        
                           <input type="file" id="emailImage" name="emailImage" class="form-control form-file" />
                        </div>
                     </div>
                     <div class="col-xs-12">
                     	<div class="form-actions text-right">
                        
                           <button class="btn btn-info btn-sm" type="submit" name="addProduct"> 
                           <i class="ace-icon fa fa-check bigger-110"></i>
                           Add Product
                           </button> 
                           &nbsp;
                           <button class="btn btn-sm" type="reset">
                           <i class="ace-icon fa fa-undo bigger-110"></i>
                           Reset
                           </button>
                        </div>
                     </div>
                  </div>
               </form>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
   // $( "#datepicker" ).datepicker({
   // 	inline: true,
   // 	dateFormat: 'mm-dd-yy' 
   // });
   	function validate()
   	{
   			
   	var product = document.getElementById('productName');
   	var price = document.getElementById('retailPrice');
      var emailImage = document.getElementById('emailImage');
   	if(product.value.length<1)
   	{
   	  alert("Please enter Product name !");
   	  product.focus();
   	  return false;
   	}   	
   	if(price.value.length<1)
   	{
   	  alert("Please enter Digicoins !");
   	  price.focus();
   	  return false;
   	} 

      if(emailImage.value.length<1)
   	{
   	  alert("Please Select Image !");
   	  emailImage.focus();
   	  return false;
   	}  	
   	}
</script>
@endsection

