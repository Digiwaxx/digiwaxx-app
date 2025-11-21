
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
			 <a href="<?php echo url("admin/tools/tools"); ?>">
			 <i class="ace-icon fa fa-list list-icon"></i>
			 Dj Tools</a>
		  </li>
		  <li class="active">Edit Dj Tool</li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	   <!--div class="nav-search" id="nav-search">
		  <form class="form-search">
			 <span class="input-icon">
			 <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
			 <i class="ace-icon fa fa-search nav-search-icon"></i>
			 </span>
		  </form>
	   </div-->
	   <!-- /.nav-search -->
	   <!-- /section:basics/content.searchbox -->
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
					  
					   //$track = $audios['data'][0];
					   ?>
				   <form role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()">
				   @csrf
					  
						 <h3 class="header smaller lighter">Edit DJ Tool</h3>
						 <table id="simple-table" class="table  table-bordered table-hover">
							<thead>
							   <tr>
								  <th class="center" width="100">
									 S. No
								  </th>
								  <th>Track</th>
							   </tr>
							</thead>
							<tbody>
							   <?php $i=1; foreach($audios['data'] as $audio) { ?>
							   <tr>
								  <td class="center"> 
									 <?php echo $i; ?>
								  </td>
								  <td>
									 <audio controls="" style="width:100%;">
										<source src="{{ asset('public/tools/'.$audio->track_file) }}" type="audio/mp3"></source>
										Your browser does not support the audio element.
									 </audio>
								  </td>
							   </tr>
							   <?php $i++; } ?>
							</tbody>
						 </table>
						 <div class="row">
							 <div class="col-xs-12">
								 <div class="form-group">
									<label class="control-label" for="form-field-1">Title</label>
									   <input type="text" id="tittle"  name="tittle" class="form-control" value="<?php echo $tools['data'][0]->tool_track_tittle; ?>">
								 </div>
							</div>
							 <div id="audioFiles" class="col-xs-12">
								<div id="audioHtml1" class="form-group">
								   <label class="control-label" for="form-field-1">File</label>
									  <input type="file" id="audio1"  name="audio1" class="form-control form-file">
								</div>
							 </div>
							 <div class="col-sm-2">
								 <a href="javascript:void()" onclick="moreAudio()" class="btn btn-success btn-sm">+</a>
								 <a href="javascript:void()" onclick="removeAudio()" class="btn btn-danger btn-sm">-</a>
							</div>
							 <input type="hidden" id="divId" name="divId" value="1" />
							 <div class="col-xs-12">
							 <div class="form-actions text-right">
								
								   <button class="btn btn-info btn-sm" type="submit" name="updateDjTool"> 
								   <i class="ace-icon fa fa-check bigger-110"></i>
								   Update Track
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
<script>

function validate()
{
  

var client = document.getElementById('client');
var company = document.getElementById('company');
var linkk = document.getElementById('link');
var moreInfo = document.getElementById('moreInfo');
var emailImage = document.getElementById('emailImage');
var pageImage = document.getElementById('pageImage');
var artist = document.getElementById('artist');
var title = document.getElementById('title');
var album = document.getElementById('album');
var time = document.getElementById('time');
var link1 = document.getElementById('link1');
var producers = document.getElementById('producers');

var numericExp = /^[-+]?[0-9]+$/;
var emailExp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

if(client.value.length<1)
{
alert("Please select client!");
client.focus();
return false;
}



if(company.value.length<1)
{
alert("Please enter company!");
company.focus();
return false;
}


if(linkk.value.length<1)
{
alert("Please enter link!");
linkk.focus();
return false;
}

var n = linkk.value.indexOf(".");


if(n<1)
{
alert("Please enter link!");
linkk.focus();
return false;
}


if(moreInfo.value.length<1)
{
alert("Please enter more info.!");
moreInfo.focus();
return false;
}


if(artist.value.length<1)
{
alert("Please enter artist name!");
artist.focus();
return false;
}

if(title.value.length<1)
{
alert("Please enter title!");
title.focus();
return false;
}


if(album.value.length<1)
{
alert("Please enter album!");
album.focus();
return false;
}

if(time.value.length<1)
{
alert("Please enter time!");
time.focus();
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

if(producers.value.length<1)
{
alert("Please enter producers!");
producers.focus();
return false;
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
  smDiv3.setAttribute('class','form-group');
  
   var smDiv4 = document.createElement("div");
  smDiv4.setAttribute('class','');
  
  var label2 = document.createElement("label");
  label2.setAttribute('class','control-label');
 
   var textnode2 = document.createTextNode("File");
   label2.appendChild(textnode2);   
   
   
  var input2 = document.createElement("input");
  input2.setAttribute('type','file');
  input2.setAttribute('name','audio'+divIdPlus);
  input2.setAttribute('id','audio'+divIdPlus);
  input2.setAttribute('class','form-control form-file');
  
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