
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
					<a href="{{ route('admin_listTools') }}">DJ Tools</a>
				</li>
				<li class="active">Add DJ Tool</li>
			</ul><!-- /.breadcrumb -->

			<!-- #section:basics/content.searchbox -->
			<!--div class="nav-search" id="nav-search">
				<form class="form-search">
					<span class="input-icon">
						<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
						<i class="ace-icon fa fa-search nav-search-icon"></i>
					</span>
				</form>
			</div--><!-- /.nav-search -->

			<!-- /section:basics/content.searchbox -->
		</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
				<!-- PAGE CONTENT BEGINS -->
				
					
					
			  <?php if(isset($alert_message)) { ?>
					<div class="<?php echo $alert_class; ?>">
					   <?php echo $alert_message; ?>
					   <button class="close" data-dismiss="alert">
						<i class="ace-icon fa fa-times"></i>
					   </button>
					</div>
			  <?php } ?>									
					
					
					<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
						@csrf
					
					
					<h3 class="header smaller">
					  Add DJ Tool
					</h3>
					<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
						<label for="form-field-1">Tittle </label>
							<input class="form-control" type="text" id="tittle" placeholder="Title" name="tittle" required >
						</div>
					</div>
					<div class="col-xs-12">
						<div id="audioFiles">
							<div id="audioHtml1">
								<label for="form-field-1" accept=".mp3"> File </label>
								<input type="file" id="track_file" name="audio1" accept=".mp3" >
							</div>
						</div>
					</div>
						
					
					
					<div class="space-24"></div>
					
					<div class="col-xs-12">
					<a href="javascript:void()" onclick="moreElement()" class="btn btn-success btn-sm">+</a>
					<a href="javascript:void()" onclick="removeElement()" class="btn btn-danger btn-sm">-</a>
					
					<input type="hidden" id="divId" name="divId" value="1" />
					</div>
					<div class="col-xs-12">
						<div class="clearfix form-actions text-right">
					
							<button class="btn btn-info btn-sm" type="submit" name="addDjTool">
								<i class="ace-icon fa fa-check bigger-110"></i>
								Add DJ Tool
							</button>

							&nbsp; &nbsp; &nbsp;
							<button class="btn btn-sm" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								Reset
							</button>
						</div>
					</div>	
					</div>
				
					
					</form>
						
						
					</div><!-- /.span -->
				</div><!-- /.row -->

				<!-- PAGE CONTENT ENDS -->
	</div><!-- /.page-content -->
	<!-- /.page-content -->
<script>
function removeElement(){
	
	 var divId = document.getElementById('divId').value;

	if(divId>1)
	{
	 var divIdMinus = parseInt(divId)-1;
	 document.getElementById('divId').value = divIdMinus;
	 $("#html"+divId).remove(); 
	 }
  
}
					
function moreElement(){



 var divId = document.getElementById('divId').value;
 var divIdPlus = parseInt(divId)+1;
 document.getElementById('divId').value = divIdPlus;
 
  var parentDiv = document.createElement("div");
  parentDiv.setAttribute('id','html'+divIdPlus);
 
					
/*  var smDiv1 = document.createElement("div");
  smDiv1.setAttribute('class','col-sm-6 form_group');
  
   var smDiv2 = document.createElement("div");
  smDiv2.setAttribute('class','col-sm-9');
  */
/*  var label1 = document.createElement("label");
  label1.setAttribute('class','col-sm-3 control-label no-padding-right');
 
 var textnode1 = document.createTextNode("Version");
   label1.appendChild(textnode1);*/  
   
   
 /* var input1 = document.createElement("select");
  //input1.setAttribute('type','text');
  input1.setAttribute('name','version'+divIdPlus);
  input1.setAttribute('id','version'+divIdPlus);
  input1.setAttribute('class','col-xs-10 col-sm-10');
  
  var option1 = document.createElement("option");
	option1.setAttribute('value','');
	option1.text = "Version";
	input1.add(option1);
	
	var option2 = document.createElement("option");
	option2.setAttribute('value','Clean');
	option2.text = "Clean";
	input1.add(option2);
	
	var option3 = document.createElement("option");
	option3.setAttribute('value','Instrumental');
	option3.text = "Instrumental";
	input1.add(option3);
	
	var option4 = document.createElement("option");
	option4.setAttribute('value','Acapella');
	option4.text = "Acapella";
	input1.add(option4);
	
	var option5 = document.createElement("option");
	option5.setAttribute('value','Dirty');
	option5.text = "Dirty";
	input1.add(option5);*/
  
  
  
  
  
//  smDiv2.appendChild(input1);   
//	  smDiv1.appendChild(label1);    
//	  smDiv1.appendChild(smDiv2);
  
  
  
   var smDiv3 = document.createElement("div");
  smDiv3.setAttribute('class','col-sm-6 form_group');
  
   var smDiv4 = document.createElement("div");
  // smDiv4.setAttribute('class','col-xs-12');
  
  var label2 = document.createElement("label");
  // label2.setAttribute('class','');
 
   var textnode2 = document.createTextNode("File");
   label2.appendChild(textnode2);   
   
   
  var input2 = document.createElement("input");
  input2.setAttribute('type','file');
  input2.setAttribute('name','audio'+divIdPlus);
  input2.setAttribute('id','audio'+divIdPlus);
  // input2.setAttribute('class','col-xs-10 col-sm-10');
  
  smDiv4.appendChild(input2);   

 parentDiv.appendChild(label2);   
  parentDiv.appendChild(smDiv4);   

//  smDiv3.appendChild(label2);    
//  smDiv3.appendChild(smDiv4);  
  
  
  
// parentDiv.appendChild(smDiv1);   
//	 parentDiv.appendChild(smDiv3);   
 
 
 
  var clearboth = document.createElement("div");
  clearboth.setAttribute('class','clearDiv');
  document.getElementById('audioFiles').appendChild(clearboth);
 
 document.getElementById('audioFiles').appendChild(parentDiv);
 
 

  
   
  }  
</script>
    @endsection       