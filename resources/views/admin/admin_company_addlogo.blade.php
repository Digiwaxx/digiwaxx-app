

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
               <a href="{{ url('admin/logos') }}">
               <i class="ace-icon fa fa-list list-icon"></i>
               Logos</a>
            </li>
            <li class="active">Add Logo</li>
         </ul>
         <!-- /.breadcrumb -->
         <!-- /section:basics/content.searchbox -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
         <!-- PAGE CONTENT BEGINS -->
    		 <h3 class="header smaller lighter">Add Logo</h3>
               <div class="">
                  <?php if(isset($alert_message)) {  ?>
                  <div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> </div>
                  <?php } ?>
                  <form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()">
                     @csrf
                     <div class="row">
                     	<div class="col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="company">Company </label>
                          
                              <input type="text" id="company" placeholder="Company" name="company" value="" class="form-control" required />										
                           </div>
                        </div>
                        <div class="col-xs-12">
                        <div class="form-group">
                           <label class="link1" for="form-field-1">Link </label>
                          
                              <input type="url" id="link1" placeholder="Link" name="link" value="" class="form-control">
                           </div>
                        </div>
                        <div class="col-xs-12">
                        <div class="form-group">
                           <label class="control-label req-label" for="form-field-1">Logo </label>
                          
                              <input type="file" id="image" name="image" class="form-control form-file" required accept="image/png, image/jpeg, image/gif">								
                           </div>
                        </div>
                        <div class="col-xs-12">
                           <div class="text-right form-actions">
                              
                               <button class="btn btn-info btn-sm" type="submit" name="addLogo">
                              <i class="ace-icon fa fa-check bigger-110"></i>
                              Add Logo
                              </button>
								&nbsp;
                              <button class="btn btn-sm btn-reset" type="reset">
                              <i class="ace-icon fa fa-undo bigger-110"></i>
                              Reset
                              </button>
                               
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
           
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</div>
<!-- /.page-content -->
<script>
   function validate(){
   
   	var company = document.getElementById('company');
   	var link1 = document.getElementById('link1');
   
   	if(company.value.length<1)
   	{
   	alert("Please enter company!");
   	company.focus();
   	return false;
   	}
   
   
   	if(link1.value.length<1)
   	{
   	alert("Please enter link!");
   	link1.focus();
   	return false;
   	}
   
   	var n = link1.value.indexOf(".");
   	if(n<1)
   	{
   	alert("Please enter link!");
   	link1.focus();
   	return false;
   	}
   }
</script>
@endsection

