

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
               <i class="ace-icon fa fa-users users-icon"></i>
               <a href="<?php echo url("admin/members"); ?>">Members</a>
            </li>
            <li class="active">Add Multiple Members</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">

               <!-- PAGE CONTENT BEGINS -->
               <?php 
                  if(isset($alertMessage)) { ?>
               <div class="<?php echo $alertClass; ?>"><?php echo $alertMessage; ?>
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               </div>
               <?php } ?>
               <form role="form" action="" method="post" onsubmit="return validate()">
                  @csrf	
                  <div class="row">
                     <div id="audioFiles">
                        <div class="col-xs-12">
                           <h3 class="header smaller lighter">
                              Add Multiple Members
                           </h3>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                        	<div class="form-group">
                           		<label class="control-label" for="form-field-1"> Email </label>                           
                              	<input type="text" id="email1" class="form-control" placeholder="Email" name="email[]">
                           </div>
                        </div>
                     </div>
                     <div class="col-xs-12">
	                     <a href="javascript:void()" onclick="moreAudio()" class="btn btn-success btn-sm">+</a>									
	                     <a href="javascript:void()" onclick="removeAudio()" class="btn btn-danger btn-sm">-</a>									
	                 </div>
                     <input type="hidden" id="divId" name="divId" value="1" />	
                        <div class="col-xs-12">								
                     		<div class="form-actions text-right">
                           <button class="btn btn-info btn-sm" type="submit" name="addMultipleMembers">
                           <i class="ace-icon fa fa-check bigger-110"></i>
                           Add Member
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
            </div>
            <!-- /.span -->
        
   </div>
   <!-- /.row -->
</div>
<!-- /.page-content -->
<script>
   function validate()
   {
   
   var divId = document.getElementById('divId').value;
   var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
   
   
   for(var i = 1; i<=divId; i++)
   {
   
   var email = document.getElementById('email'+i);
   if(!(email.value.match(emailExp)))
   {
   alert("Please enter a valid email address!");
   email.focus();
   return false;
   }
   
   }
   }
   
   function removeAudio()
   {
   
    var divId = document.getElementById('divId').value;
   
   if(divId>1)
   {
    var divIdMinus = parseInt(divId)-1;
    document.getElementById('divId').value = divIdMinus;
    $("#html"+divId).remove(); 
    }
     
   }
   function moreAudio()
   {
    var divId = document.getElementById('divId').value;
    var divIdPlus = parseInt(divId)+1;
    document.getElementById('divId').value = divIdPlus;
    
   //  var parentDiv = document.createElement("div");
   //  parentDiv.setAttribute('id','html'+divIdPlus);
    
     					
     var smDiv1 = document.createElement("div");
     smDiv1.setAttribute('class','col-md-4 col-sm-6 col-xs-12 form-group');
     smDiv1.setAttribute('id','html'+divIdPlus);
     
      var smDiv2 = document.createElement("div");
     smDiv2.setAttribute('class','');
     
     var label1 = document.createElement("label");
     label1.setAttribute('class','control-label');
    
      var textnode1 = document.createTextNode("Email");
      label1.appendChild(textnode1);   
      
      
     var input1 = document.createElement("input");
     input1.setAttribute('type','text');
     input1.setAttribute('name','email[]');
     input1.setAttribute('id','email'+divIdPlus);
     input1.setAttribute('class','form-control');
     input1.setAttribute('placeholder','Email');
     
     smDiv2.appendChild(input1);   
     smDiv1.appendChild(label1);    
     smDiv1.appendChild(smDiv2);
     
   /*  
     
      var smDiv3 = document.createElement("div");
     smDiv3.setAttribute('class','col-sm-6 form_group');
     
      var smDiv4 = document.createElement("div");
     smDiv4.setAttribute('class','col-sm-9');
     
     var label2 = document.createElement("label");
     label2.setAttribute('class','col-sm-3 control-label');
    
      var textnode2 = document.createTextNode("File");
      label2.appendChild(textnode2);   
      
      
     var input2 = document.createElement("input");
     input2.setAttribute('type','file');
     input2.setAttribute('name','audio'+divIdPlus);
     input2.setAttribute('id','audio'+divIdPlus);
     input2.setAttribute('class','col-xs-10 col-sm-10');
     
     smDiv4.appendChild(input2);   
     smDiv3.appendChild(label2);    
     smDiv3.appendChild(smDiv4);  
    */ 
     
   //	 parentDiv.appendChild(smDiv1);   
   //	 parentDiv.appendChild(smDiv3);   
     var clearboth = document.createElement("div");
     clearboth.setAttribute('class','clearDiv');
     document.getElementById('audioFiles').appendChild(clearboth);
    document.getElementById('audioFiles').appendChild(smDiv1);
     }  
</script>
@endsection

