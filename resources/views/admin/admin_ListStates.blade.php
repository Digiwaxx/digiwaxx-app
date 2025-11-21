

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
         <li class="active">
            <i class="ace-icon fa fa-list list-icon"></i> States
         </li>
      </ul>
      <!-- /.breadcrumb -->
      <div class="nav-search" id="nav-search">
         <form class="form-inline" autocomplete="off">
            <input type="hidden" id="page" value="<?php echo $currentPage; ?>"  />
            <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
            <span class="input-icon">
               <select class="nav-search-input" id="sortBy">
                  <option value="">Select Option</option>
                  <option <?php if(strcmp($sortBy,'state')==0) { ?> selected="selected" <?php } ?> value="state">State</option>
                  <option <?php if(strcmp($sortBy,'country')==0) { ?> selected="selected" <?php } ?> value="country">Country</option>
                  <option <?php if(strcmp($sortBy,'continent')==0) { ?> selected="selected" <?php } ?> value="continent">Continent</option>
               </select>
            </span>
            <label class="hidden-md hidden-sm hidden-xs">Order By</label>
            <span class="input-icon">
               <select class="nav-search-input" id="sortOrder">
                  <option value="">Select Option</option>
                  <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
                  <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
               </select>
            </span>
            <label class="hidden-md hidden-sm hidden-xs">	No. Records</label>
            <span class="input-icon">
               <select class="nav-search-input" id="numRecords">
                  <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
                  <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
                  <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
                  <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
                  <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
               </select>
            </span>
            <span class="input-icon">	<button type="button" value="Sort" onclick="sortData()">Sort</button> </span>
         </form>
      </div>
   </div>
   <!-- /section:basics/content.breadcrumbs -->
   <div class="page-content">
      <div class="row">
         <div class="col-xs-12"><input type="button" value="Add State" data-toggle="modal" data-target="#addCountryBox" class="btn btn-sm btn-primary" /></div>
      </div>
      <!-- PAGE CONTENT BEGINS -->
      <div class="row">
         <div class="col-xs-12">
            <div class="space-10"></div>
            <?php if(isset($alert_class)) 
               { ?>
            <div class="<?php echo $alert_class; ?>">
               <button class="close" data-dismiss="alert">
               <i class="ace-icon fa fa-times"></i>
               </button>
               <?php echo $alert_message; ?>
            </div>
            <?php } ?>
            <div class="table-responsive">
               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th class="center" width="100">
                           S. No.
                        </th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Continent</th>
                        <th width="110">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $i = $start+1;
                        foreach($states['data'] as $state)
                        {
                        
                        ?>
                     <tr>
                        <td class="center"><?php echo $i; ?></span></td>
                        <td id="displayCountry<?php echo $state->stateId;  ?>">
                           <?php
                              echo $state->name; ?>
                        </td>
                        <td id="displayContinent<?php echo $state->stateId;  ?>"><?php echo $state->country; ?></td>
                        <td id="displayContinent<?php echo $state->stateId;  ?>"><?php echo $state->continent; ?></td>
                        <td>
                           <div class="btn-group">
                              <button data-toggle="modal" data-target="#editCountryBox<?php echo $state->stateId; ?>" title="Edit Country" class="btn btn-xs btn-info">
                              <i class="ace-icon fa fa-pencil bigger-120"></i>
                              </button>
                              <button onclick='deleteRecord("states","<?php echo $state->stateId; ?>","Confirm delete <?php echo $state->name; ?> ")' class="btn btn-xs btn-danger">
                              <i class="ace-icon fa fa-trash-o bigger-120"></i>
                              </button>
                           </div>
                           <div id="editCountryBox<?php echo $state->stateId; ?>" class="modal fade in">
                              <div class="modal-dialog">
                                 <div class="modal-content">
                                    <div class="modal-header no-padding">
                                       <div class="table-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                          </button>
                                          <?php echo $state->name; ?> - Edit
                                       </div>
                                    </div>
                                    <div class="modal-body no-padding">
                                       <div class="space-10"></div>
                                       <div class="row">
                                          <form action="" method="post" autocomplete="off" id="addCountryForm">
                                             <div class="col-sm-8 col-sm-offset-2">
                                                <div class="form-group">
                                                   <label for="exampleInputEmail1">Continent</label>
                                                   <select class="form-control" id="continent<?php echo $state->stateId; ?>" onchange="getCountries(this.value,<?php echo $state->stateId; ?>)">
                                                      <option value="0">Select Continent</option>
                                                      <?php foreach($continents as $continent) { ?>
                                                      <option <?php if($state->continentId==$continent->continentId) { ?> selected="selected" <?php } ?> value="<?php echo $continent->continentId; ?>"><?php echo $continent->continent; ?></option>
                                                      <?php } ?>
                                                   </select>
                                                </div>
                                                <div class="form-group">
                                                   <label for="exampleInputEmail1">Country</label>
                                                   <select class="form-control" id="country<?php echo $state->stateId; ?>">
                                                      <?php
                                                         foreach($selectedCountries[$state->stateId]['data'] as $country)
                                                         {
                                                         
                                                         ?> 
                                                      <option value="<?php echo $country->countryId; ?>" <?php if($state->countryId==$country->countryId) { ?> selected="selected" <?php } ?>><?php echo $country->country; ?></option>
                                                      <?php
                                                         }
                                                         
                                                         ?>
                                                   </select>
                                                </div>
                                                <div class="form-group">
                                                   <label for="exampleInputEmail1">State</label>
                                                   <input type="text" class="form-control" id="state<?php echo $state->stateId; ?>" placeholder="State" value="<?php echo $state->name; ?>">
                                                   <span id="editResponse<?php echo $state->stateId;  ?>"></span>
                                                </div>
                                                <button type="button" name="addCountry" class="btn btn-sm btn-primary" onclick="updateState(<?php echo $state->stateId; ?>)">
                                                Update State
                                                </button>
                                             </div>
                                          </form>
                                       </div>
                                       <div class="space-10"></div>
                                    </div>
                                    <div class="modal-footer no-margin-top">
                                       <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                                       <i class="ace-icon fa fa-times"></i>
                                       Close
                                       </button>
                                    </div>
                                 </div>
                                 <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                           </div>
                        </td>
                     </tr>
                     <?php $i++; } ?>
                     <tr>
                        <td colspan="5" valign="middle">
                           <div id="addCountryBox" class="modal fade in">
                              <div class="modal-dialog">
                                 <div class="modal-content">
                                    <div class="modal-header no-padding">
                                       <div class="table-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                          </button>
                                          Add State
                                       </div>
                                    </div>
                                    <div class="modal-body no-padding">
                                       <div class="space-10"></div>
                                       <div class="row">
                                          <form id="addCountryForm">
                                             <div class="col-sm-8 col-sm-offset-2">
                                                <div class="form-group">
                                                   <label for="exampleInputEmail1">Continent</label>
                                                   <select class="form-control" id="continent" name="continent" onchange="getCountries1(this.value)">
                                                      <option value="0">Select Continent</option>
                                                      <?php foreach($continents as $continent) { ?>
                                                      <option value="<?php echo $continent->continentId; ?>"><?php echo $continent->continent; ?></option>
                                                      <?php } ?>
                                                   </select>
                                                </div>
                                                <div class="form-group">
                                                   <label for="exampleInputEmail1">Country</label>
                                                   <select class="form-control" id="country">
                                                      <option value="">Select Country</option>
                                                   </select>
                                                </div>
                                                <div class="form-group">
                                                   <label for="exampleInputEmail1">State</label>
                                                   <input type="text" class="form-control" id="state" placeholder="State">
                                                   <span id="addResponse"></span>
                                                </div>
                                                <button type="button"  onclick="addState()"  class="btn btn-sm btn-primary">Add State</button>
                                             </div>
                                          </form>
                                       </div>
                                       <div class="space-10"></div>
                                    </div>
                                    <div class="modal-footer no-margin-top">
                                       <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                                       <i class="ace-icon fa fa-times"></i>
                                       Close
                                       </button>
                                    </div>
                                 </div>
                                 <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <ul class="pager pager-rounded" style="float:right;">
               <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>
               <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
               <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
               <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
               <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
            </ul>
         </div>
         <!-- /.span -->
      </div>
      <!-- /.row -->
      <!-- PAGE CONTENT ENDS -->
   </div>
   <!-- /.col -->
</div>
</div>

<script>
   function getCountries1(continentId)
   {
   
   
   
   $.ajax({url: "states?getContinentCountries=1&continentId="+continentId, success: function(result){
      
   //                	   row = JSON.parse(result);
   	 var obj = JSON.parse(result);
   	 var count = obj.length; 
      //  var liList = '';
   	 var optionList = ''; //'<option value="">What country do you live in</option>';
   	for (var i=0;i<count;i++) 
   	 {
   	 
   			  
   	   optionList += '<option value="'+obj[i].countryId+'">'+obj[i].country+'</option>';
   	 
   	 }
   	 document.getElementById('country').innerHTML = optionList;
   	 	
                              }});
   
   }
   
   function getCountries(continentId,stateId)
   {
   
   
   
   $.ajax({url: "states?getContinentCountries=1&continentId="+continentId, success: function(result){
      
   //                	   row = JSON.parse(result);
   	 var obj = JSON.parse(result);
   	 var count = obj.length; 
      //  var liList = '';
   	 var optionList = ''; //'<option value="">What country do you live in</option>';
   	for (var i=0;i<count;i++) 
   	 {
   	 
   			  
   	   optionList += '<option value="'+obj[i].countryId+'">'+obj[i].country+'</option>';
   	 
   	 }
   	 document.getElementById('country'+stateId).innerHTML = optionList;
   	 
   	 		
   					
   					/*	if(row.response==1)
   						{
   						responseColor = '#090';  						
                              responseMessage = "Country updated successfully !";
   						document.getElementById('displayCountry'+countryId).innerHTML = country;
   						document.getElementById('displayContinent'+countryId).innerHTML = row.continent;
   						}
   						else
   						{
                           responseColor = '#FF0000';  						
   						responseMessage = "Error occured, please try again !";
   						}
   						*/
   						//document.getElementById('editResponse'+countryId).style.color = responseColor;
   					//	document.getElementById('country'+stateId).innerHTML = responseMessage;
   						
                              }});
   
   }
   
   function addState()
   {
     
   				   var countryId = document.getElementById("country").value;
   				   var continentId = document.getElementById("continent").value;
   				   var state = document.getElementById("state").value;
   				     				   
   				   if(continentId<1)
   				   {
   				     alert("Select a continent");
   					 document.getElementById("continent").focus();
   					 
   				   }
   				   else if(countryId<1)
   				   {
   				     alert("Select a country");
   					 document.getElementById("country").focus();
   					 
   				   }
   				   else 
   				   {
   		  $.ajax({url: "states?add=1&continentId="+continentId+"&countryId="+countryId+"&state="+state, success: function(result){
                        	   row = JSON.parse(result);
   						
   						
   						var responseMessage = '';
   						var responseColor =  '';
   					    if(row.response>0)
   						{
   						responseColor = '#090';  						
                  responseMessage = "State added successfully !";
   						
   						/* document.getElementById("addCountryForm").reset(); */
   						}
   						else
   						{
   						responseColor = '#f50a31';  						
                  responseMessage = "State already exists !";
   						} 
   						
   						document.getElementById('addResponse').style.color = responseColor;
   						document.getElementById('addResponse').innerHTML = responseMessage;
   						
                }});
   						}
   						
   }
   
   function updateState(stateId)
   				{
   				   var countryId = document.getElementById("country"+stateId).value;
   				   var continentId = document.getElementById("continent"+stateId).value;
   				   var state = document.getElementById("state"+stateId).value;
   				   
   				   
   				   if(continentId<1)
   				   {
   				     alert("Select a continent");
   					 document.getElementById("continent"+stateId).focus();
   					 
   				   }
   				   else if(countryId<1)
   				   {
   				     alert("Select a country");
   					 document.getElementById("country"+stateId).focus();
   					 
   				   }
   				   else 
   				   {
   		  $.ajax({url: "states?edit=1&continentId="+continentId+"&countryId="+countryId+"&state="+state+"&stateId="+stateId, success: function(result){
                        	   row = JSON.parse(result);
   						
   						
   						var responseMessage = '';
   						var responseColor =  '';
   					/*
   						if(row.response==1)
   						{
   						responseColor = '#090';  						
                              responseMessage = "State updated successfully !";
   						document.getElementById('displayCountry'+stateId).innerHTML = country;
   						document.getElementById('displayContinent'+stateId).innerHTML = row.continent;
   						}
   						else
   						{
                           responseColor = '#FF0000';  						
   						responseMessage = "Error occured, please try again !";
   						}*/
   						
   						
   						responseColor = '#090';  						
                              responseMessage = "State updated successfully !";
   						
   						document.getElementById('editResponse'+stateId).style.color = responseColor;
   						document.getElementById('editResponse'+stateId).innerHTML = responseMessage;
   						
                              }});
   						}
   						
   				}
   
    // Wait for the DOM to be ready
   $(function() {
   
   $("#addCountryForm").validate();
   
     $("#continent").rules("add", {
           required:true,
   	 messages: {
                  required: "Please enter website"
           }
        });
   
   $("#country").rules("add", {
           required:true,
           messages: {
                  required: "Please enter artist name"
           }
        });
   });
</script>
@endsection

