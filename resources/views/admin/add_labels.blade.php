

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
            <i class="ace-icon fa fa-list list-icon"></i>
            <a href="{{ route('admin_labels_listing') }}">Labels</a>
         </li>
         <li class="active">Add Label</li>
      </ul>
      <!-- /.breadcrumb -->
   </div>
   <!-- /section:basics/content.breadcrumbs -->
   <div class="page-content">
      <div class="space-10"></div>
      <div class="row">
         <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?php if(isset($alert_message)) {  ?>
            <div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> 
            </div>
            <?php } ?> 
            <form role="form" action="{{ route('submit_add_label') }}" method="post" id="addLabel" style="color:white;">
               @csrf
               <div class="row">
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label req-label" for="form-field-1">  Client </label>
                        <select required id="client" name="client" class="form-control">
                           <option value="">Select Client</option>
                           <?php foreach($clients['data'] as $client) { ?>
                           <option value="<?php echo $client->id; ?>"><?php echo urldecode($client->name); ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label req-label" for="form-field-1">  Title </label>
                        <input required type="text" id="title" placeholder="Title" name="title" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Name </label>
                        <input required type="text" id="name" placeholder="Name" name="name" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Email </label>
                        <input required type="email" id="email" placeholder="Email" name="email[]" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label" for="form-field-1"> Alternate Email 1</label>
                        <input type="email" id="email" placeholder="Email" name="email[]" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label" for="form-field-1"> Alternate Email 2</label>
                        <input type="email" id="email" placeholder="Email" name="email[]" class="form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label req-label" for="form-field-1"> Mobile </label>
                        <input required  type="number" id="mobile" placeholder="Mobile" name="mobile" class="input-number form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label" for="form-field-1"> Phone </label>
                        <input type="number" id="phone" placeholder="Phone" name="phone[]" class="input-number form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <div class="form-group">
                        <label class="control-label" for="form-field-1"> Alternate Phone 1 </label>
                        <input type="number" id="phone" placeholder="Phone" name="phone[]" class="input-number form-control">
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
	              <div class="form-group">
	                     <label class="control-label" for="form-field-1"> Alternate Phone 2 </label>
	                     <input type="number" id="phone" placeholder="Phone" name="phone[]" class="input-number form-control">
	                  </div>
	               </div>

               <div class="col-sm-12 col-xs-12">
                  <div class="form-actions text-right">
                     <button class="btn btn-info btn-sm" type="submit" name="addLabel">
                     <i class="ace-icon fa fa-check bigger-110"></i>
                     Add Label
                     </button>
                     &nbsp; 
                     <button class="btn btn-reset btn-sm" type="reset">
                     <i class="ace-icon fa fa-undo bigger-110"></i>
                     Reset
                     </button>
                  </div>
               </div>
               </div>
         </form>
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</div>
</div>
</div>
<!-- /.page-content -->
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<script>
   // Wait for the DOM to be ready
   $(function() {
   
   $("#addLabel").validate();
   
   $("#title").rules("add", {
          required:true,
          messages: {
                 required: "Please enter name."
          }
       });
   
   $("#name").rules("add", {
          required:true,
          messages: {
                 required: "Please enter username"
          }
       });
    
    $("#email").rules("add", {
          required:true,
    email:true,
          messages: {
                 required: "Please enter a valid email id."
          }
       });
    	  
   });
   
</script>
@endsection

