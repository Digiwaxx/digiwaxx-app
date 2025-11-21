
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
			 <i class="ace-icon fa fa-list list-icon"></i> Countries
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <div class="nav-search" id="nav-search">
		  <form class="form-inline" autocomplete="off">
			 <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
			 <span class="input-icon">
				<select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
				   <option <?php if(strcmp($sortBy,'country')==0) { ?> selected="selected" <?php } ?> value="country">Country</option>
				   <option <?php if(strcmp($sortBy,'continent')==0) { ?> selected="selected" <?php } ?> value="continent">Continent</option>
				</select>
			 </span>
			 <label class="hidden-md hidden-sm hidden-xs">Order By</label>
			 <span class="input-icon">
				<select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
				   <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
				   <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
				</select>
			 </span>
			 <label class="hidden-md hidden-sm hidden-xs">	No. Records</label>
			 <span class="input-icon">
				<select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
				   <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
				   <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
				   <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
				   <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
				   <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
				</select>
			 </span>
			 <!--<span class="input-icon">	<button type="button" value="Sort" onclick="sortData()">Sort</button> </span>-->
		  </form>
	   </div>
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="row">
		   <div class="searchDiv">
			  <form class="form-inline searchForm" id="searchForm" action="countries" method="get" autocomplete="off">
				@csrf
				 <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
				 <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
				 <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
				 <div class="col-sm-3 nav-search">
				 	<div class="input-group">
					<label class="input-group-addon" for="productName" >Continent</label>
					<select class="nav-search-input form-control" id="searchContinent" name="searchContinent">
					   <option value="0">Select Continent</option>
					   <?php foreach($continents as $continent) { ?>
					   <option <?php if($continent->continentId==$searchContinent) { ?> selected="selected" <?php } ?> value="<?php echo $continent->continentId; ?>"><?php echo $continent->continent; ?></option>
					   <?php } ?>
					</select>
					</div>
				 </div>
				 <div class="col-sm-3">
				 	<div class="input-group">
					<label class="input-group-addon" for="productName" >Country</label>
					<input type="text" class="nav-search-input form-control" id="searchCountry" name="searchCountry" value="<?php echo $searchCountry; ?>"  />
					</div>
				 </div>
				 <div class="col-sm-3">
				 	<div class="input-group">
					<input type="submit" value="Search" name="search"  />
					@if($searchCountry != '' || $searchContinent != '')	
                     <input type="button" value="Reset" onclick="window.location.href='{{ route('admin_ListCountries') }}'" />
					  @else
					<input type="button" value="Reset" onclick="searchReset()"  />
					 @endif 
					</div>
				 </div>
				 <div class="col-sm-3 text-right">
				   <button type="button" data-toggle="modal" data-target="#addCountryBox" class="btn btn-sm btn-primary">Add Country</button>
				 </div>
			  </form>
		   </div>
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
								 <th>Country</th>
								 <th>Code</th>
								 <th>Continent</th>
								 <th width="110">Action</th>
							  </tr>
						   </thead>
						   <tbody>
							  <?php
								 $i = $start+1;
								 foreach($countries['data'] as $country)
								 {
								 
								 ?>
							  <tr>
								 <td class="center"><?php echo $i; ?></span></td>
								 <td id="displayCountry<?php echo $country->countryId;  ?>"><?php echo $country->country; ?></td>
								 <td id="displayCode<?php echo $country->countryId;  ?>"><?php echo $country->abbr; ?>
									<?php  // echo $country->countryId;  ?>
								 </td>
								 <td id="displayContinent<?php echo $country->countryId;  ?>"><?php echo $country->continent; ?></td>
								 <td>
									<div class="btn-group">
									   <button data-toggle="modal" data-target="#editCountryBox<?php echo $country->countryId; ?>" title="Edit Country" class="btn btn-xs btn-info">
									   <i class="ace-icon fa fa-pencil bigger-120"></i>
									   </button>
									   <button onclick="deleteRecord('countries','<?php echo $country->countryId; ?>','Confirm delete <?php echo $country->country; ?> ')" class="btn btn-xs btn-danger">
									   <i class="ace-icon fa fa-trash-o bigger-120"></i>
									   </button>
									</div>
									<div id="editCountryBox<?php echo $country->countryId; ?>" class="modal fade in">
									   <div class="modal-dialog">
										  <div class="modal-content">
											 <div class="modal-header no-padding">
												<div class="table-header">
												   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												   </button>
												   <?php echo $country->country; ?> - Edit
												</div>
											 </div>
											 <div class="modal-body no-padding">
												<div class="space-10"></div>
												<div class="row">
												   <form action="" method="post" autocomplete="off" id="addCountryForm">
												     @csrf
													  <div class="col-sm-8 col-sm-offset-2">
														 <div class="form-group">
															<label for="exampleInputEmail1">Continent</label>
															<select class="form-control" id="continent<?php echo $country->countryId; ?>">
															   <option value="0">Select Continent</option>
															   <?php foreach($continents as $continent) { ?>
															   <option <?php if($country->continentId==$continent->continentId) { ?> selected="selected" <?php } ?> value="<?php echo $continent->continentId; ?>"><?php echo $continent->continent; ?></option>
															   <?php } ?>
															</select>
														 </div>
														 <div class="form-group">
															<label for="exampleInputEmail1">Country</label>
															<input type="text" class="form-control" id="country<?php echo $country->countryId; ?>" placeholder="Country" value="<?php echo $country->country; ?>">
														 </div>
														 <div class="form-group">
															<label for="exampleInputEmail1">Country Code</label>
															<input type="text" class="form-control" id="country_code<?php echo $country->countryId; ?>" placeholder="Country Code" value="<?php echo $country->abbr; ?>">
															<span id="editResponse<?php echo $country->countryId;  ?>"></span>
														 </div>
														 <button type="button" name="addCountry" class="btn btn-sm btn-primary" onclick="updateCountry(<?php echo $country->countryId; ?>)">Update Country</button>
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
												   Add Country
												</div>
											 </div>
											 <div class="modal-body no-padding">
												<div class="space-10"></div>
												<div class="row">
												   <form action="" method="post" autocomplete="off" id="addCountryForm">
													@csrf
													  <div class="col-sm-8 col-sm-offset-2">
														 <div class="form-group">
															<label for="exampleInputEmail1">Continent</label>
															<select class="form-control" id="continent" name="continent">
															   <option value="0">Select Continent</option>
															   <?php foreach($continents as $continent) { ?>
															   <option value="<?php echo $continent->continentId; ?>"><?php echo $continent->continent; ?></option>
															   <?php } ?>
															</select>
														 </div>
														 <div class="form-group">
															<label for="exampleInputEmail1">Country</label>
															<input type="text" class="form-control" id="country" name="country" placeholder="Country">
														 </div>
														 <div class="form-group">
															<label for="exampleInputEmail1">Country Code</label>
															<input type="text" class="form-control" id="country_code" name="country_code" placeholder="Country">
														 </div>
														 <button type="submit" name="addCountry" class="btn btn-sm btn-primary">Add Country</button>
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
					<?php if($numPages>1) { ?>
						<ul class="pager pager-rounded" style="float:right;">
						   <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>
						   <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
						   <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
						   <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
						   <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
						</ul>
						<?php } ?>
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
	<script>
	function get_selected_data()
	{ 
	 var sortBy = document.getElementById('sortBy').value;
	 var sortOrder = document.getElementById('sortOrder').value;
	 var numRecords = document.getElementById('numRecords').value;
	 var searchContinent = document.getElementById('searchContinent').value;
	 var searchCountry = document.getElementById('searchCountry').value;
	 
	window.location = "countries?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&searchContinent="+searchContinent+"&searchCountry="+searchCountry;
	}

/* function get_search_data()
	{
	alert("button");	
	 var sortBy = document.getElementById('sortBy').value;
	 var sortOrder = document.getElementById('sortOrder').value;
	 var numRecords = document.getElementById('numRecords').value;
	 var searchContinent = document.getElementById('searchContinent').value;
	 var searchCountry = document.getElementById('searchCountry').value;
window.location = "countries?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&searchContinent="+searchContinent+"&searchCountry="+searchCountry+"&search=Search";
	}
	function get_data()
	{
	if (event.keyCode === 13) {
	 var sortBy = document.getElementById('sortBy').value;
	 var sortOrder = document.getElementById('sortOrder').value;
	 var numRecords = document.getElementById('numRecords').value;
	 var searchContinent = document.getElementById('searchContinent').value;
	 var searchCountry = document.getElementById('searchCountry').value;
	
window.location = "countries?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&searchContinent="+searchContinent+"&searchCountry="+searchCountry+"&search=Search";
     }
	}
*/	
	
	function updateCountry(countryId)
	{
	   var country = document.getElementById("country"+countryId).value;
	   var continentId = document.getElementById("continent"+countryId).value;
	   var country_code = document.getElementById("country_code"+countryId).value;
	   
	   
	   if(continentId<1)
	   {
		 alert("Select a continent");
		 document.getElementById("continent"+countryId).focus();
		 
	   }
	   else if(country.length<1)
	   {
		 alert("Enter country name");
		 document.getElementById("country"+countryId).focus();
		 
	   }
	   else 
	   {
		   $.ajax({url: "countries?edit=1&continent="+continentId+"&country="+country+"&countryId="+countryId+"&country_code="+country_code, success: function(result){
		   row = JSON.parse(result);
			
			
			var responseMessage = '';
			var responseColor =  '';
		
			if(row.response==1)
			{
			responseColor = '#090';  						
			responseMessage = "Country updated successfully !";
			document.getElementById('displayCountry'+countryId).innerHTML = country;
			document.getElementById('displayContinent'+countryId).innerHTML = row.continent;
			document.getElementById('displayCode'+countryId).innerHTML = country_code;
			}
			else
			{
			responseColor = '#FF0000';  						
			responseMessage = "Error occured, please try again !";
			}
			
			document.getElementById('editResponse'+countryId).style.color = responseColor;
			document.getElementById('editResponse'+countryId).innerHTML = responseMessage;
			
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