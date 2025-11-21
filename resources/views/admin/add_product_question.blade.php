

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
               <a href="{{ route('products_lisitng') }}"><i class="ace-icon fa fa-list list-icon"></i> Products</a>
            </li>
            <li class="active">Add Product Question</li>
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
                  <div>
                     <h3 class="header smaller lighter">
                        Question Information
                     </h3>
                     <div class="col-sm-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Question </label>										
                           <textarea id="question" placeholder="Question" name="question" class="form-control"></textarea>
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <div class="form-group">
                           <label class="control-label" for="form-field-1"> Type </label>
                           <select id="type" name="type" class="form-control" onchange="changeType(this.value)">
                              <option value="0">Select</option>
                              <option value="check">Check</option>
                              <option value="radio">Radio</option>
                              <option value="text">Text</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-12 " id="optionsBox">
                        <div class="form-group">
                           <label class="control-label no-padding-right" for="form-field-1"> Options </label>
                           <div id="optionsDiv">
                              <input type="text" placeholder="Option" name="options[]" id="options1" class="form-control optionsInput" />
                           </div>
                           <span class="searchForm" style="float:right; margin-top:10px;">
                           <a href="javascript:void()" onclick="addMore(1)" id="addMoreLink" style="border:none;">add more</a>
                           <a href="javascript:void()" onclick="remove(1)" id="removeLink" class="btn-danger" style="border:none;">remove</a>
                           </span>
                        </div>
                     </div>
                     <div class="col-xs-12">
                        <div class="form-actions text-right">
                           <button class="btn btn-info btn-sm" type="submit" name="addQuestion"> 
                           <i class="ace-icon fa fa-check bigger-110"></i>
                           Add Product Question
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
         <div class="hr hr-18 dotted hr-double"></div>
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
   </div>
   <!-- /.row -->
</div>
<!-- /.page-content -->
<script>
   function changeType(type)
   {
       if(type==='check' || type==='radio')
    {
      document.getElementById('optionsBox').style.display = 'block';
    }
    else 
    {
      document.getElementById('optionsBox').style.display = 'none';
    }
   
   }	
   
   function addMore(id)
   {
       var input = document.createElement("input");
    var idd = parseInt(id)+1;
     
     input.setAttribute("type", "text"); 
     input.setAttribute("placeholder", "Option"); 
     input.setAttribute("name", "options[]"); 
     input.setAttribute("id", "options"+idd); 
     input.setAttribute("class", "form-control optionsInput"); 
     
     document.getElementById("optionsDiv").appendChild(input); 
     document.getElementById("addMoreLink").setAttribute("onClick", "addMore("+idd+")");
     document.getElementById("removeLink").setAttribute("onClick", "remove("+idd+")");
   }
   
   function remove(id)
   {
     if(id>1)
   {
      var idd = parseInt(id)-1;
   
   $("#options"+id).remove();
   
   document.getElementById("addMoreLink").setAttribute("onClick", "addMore("+idd+")");
      document.getElementById("removeLink").setAttribute("onClick", "remove("+idd+")");
   }
   }
   
   function validate()
   {
   	
   var product = document.getElementById('productName');
   var price = document.getElementById('retailPrice');
   
   
   
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
   
   }
</script>
@endsection

