

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
            <li class="active">Edit Product</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
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
                  
                  
                  $data = $product['data'][0];	?>
               <form role="form" action="" method="post" enctype="multipart/form-data">
                  @csrf	
                  <div class="row">
                  	<div class="col-xs-12">
	                     <h3 class="header smaller lighter">
	                        Product Information
	                     </h3>
	                 </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Company </label>
                        
                           <select id="company" name="company" class="form-control">
                              <option value="0">Select</option>
                              <?php if($companies['numRows']>0) { 
                                 foreach($companies['data'] as $company) 
                                 { ?>
                              <option value="<?php echo $company->company_id; ?>" <?php if($data->company_id==$company->company_id) { ?> selected="selected" <?php } ?>><?php echo $company->name; ?></option>
                              <?php } } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Brand / Division </label>
                        
                           <input type="text" id="brand" name="brand" class="form-control" placeholder="Brand / Division" value="<?php echo $data->division; ?>" />
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Link </label>
                        
                           <input type="text" id="link" placeholder="Link" name="link" class="form-control" value="<?php echo $data->link; ?>">
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Product Name </label>
                        
                           <input type="text" id="productName" placeholder="Product Name" name="productName" class="form-control" value="<?php echo $data->name; ?>" >
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Model ( color / flavor ) </label>
                        
                           <input type="text" id="model" placeholder="Model ( color / flavor )" name="model" class="form-control" value="<?php echo $data->model; ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1">Product Details</label>
                        
                           <input type="text" id="productDetails" placeholder="Product Details" name="productDetails" class="form-control" value="<?php echo $data->product_details; ?>" />
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Features / Benefits </label>
                        
                           <input type="text" id="benefits" placeholder="Features / Benefits" name="benefits" class="form-control" value="<?php echo $data->product_technology; ?>" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1">Sex/Gender</label>
                        
                           <select id="gender" name="gender" class="form-control">
                              <option value="both" <?php if(strcmp($data->product_gender,'both')==0) { ?> selected="selected" <?php } ?>>Both</option>
                              <option value="men" <?php if(strcmp($data->product_gender,'men')==0) { ?> selected="selected" <?php } ?>>Men</option>
                              <option value="women" <?php if(strcmp($data->product_gender,'women')==0) { ?> selected="selected" <?php } ?>>Women</option>
                           </select>
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Available to Members </label>
                        
                           <div class="radio">
                              <label>
                              <input name="active" type="radio" class="ace" value="1" <?php if($data->active==1) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="active" type="radio" class="ace" value="0" <?php if($data->active==0) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Reviewable </label>
                        
                           <div class="radio">
                              <label>
                              <input name="reviewable" type="radio" class="ace" value="1" <?php if($data->review==1) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="reviewable" type="radio" class="ace" value="0" <?php if($data->review==0) { ?> checked="checked" <?php } ?> />
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-6">
                     	<div class="form-group">
                        <label class="control-label" for="form-field-1"> Merch by digiwaxx </label>
                        
                           <div class="radio">
                              <label>
                              <input name="merch_digi" type="radio" class="ace" value="1" <?php if(!empty($product->merch_status)){ if($data->merch_status==1) { ?> checked="checked" <?php }} ?>>
                              <span class="lbl"> Yes </span>
                              </label>
                              <label>
                              <input name="merch_digi" type="radio" class="ace" value="0" <?php if(!empty($product->merch_status)){ if($data->merch_status==0) { ?> checked="checked" <?php } }?>>
                              <span class="lbl"> No</span>
                              </label>
                           </div>
                        </div>
                     </div>
                     
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> More Info. </label>
                        
                           <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control"><?php echo $data->moreinfo; ?></textarea>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                        <label class="control-label" for="form-field-1"> Image </label>
                        
                           <input type="file" id="emailImage" name="emailImage" class="form-file form-control" />
                           <?php $artWork = asset('product_images/'.$data->emailimg); ?>
                           <img src="<?php echo $artWork; ?>" width="80" height="80" />
                        </div>
                     </div>
                     <div class="col-xs-12">
                     	<div class="form-actions text-right">                        
                           <button class="btn btn-info btn-sm" type="submit" name="updateProduct"> 
                           <i class="ace-icon fa fa-check bigger-110"></i>
                           Update Product
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
@endsection
