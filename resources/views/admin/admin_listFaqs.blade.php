
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
			 <i class="ace-icon fa fa-list list-icon"></i> FAQs
		  </li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <div class="nav-search" id="nav-search">
		  <form class="form-inline" autocomplete="off">
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
		  <div class="col-xs-12">
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
							<th class="center">
							   S. No.
							</th>
							<th>Question</th>
							<th>Answer</th>
							<th width="110">Status</th>
							<th width="150">Action</th>
						 </tr>
					  </thead>
					  <tbody>
						 <?php
							$i = $start+1;
							foreach($faqs['data'] as $faq)
							{
							
							?>
						 <tr>
							<td ><?php echo $i; ?></span></td>
							<td><?php $question = substr($faq->question,0,30); echo $question; ?>
							<td><?php $answer = substr($faq->answer,0,30); echo $answer; ?></td>
							<td><?php if($faq->status==1) { echo 'Active'; }  else  { echo 'Inactive'; } ?></td>
							<td>
							   <div class="btn-group">
								  <a href="faq_view?fid=<?php echo $faq->faq_id; ?>" title="View FAQ" class="btn btn-xs btn-success">
								  <i class="ace-icon fa fa-list bigger-120"></i>
								  </a>
								  <a href="faq_edit?fid=<?php echo $faq->faq_id; ?>" title="Edit FAQ" class="btn btn-xs btn-info">
								  <i class="ace-icon fa fa-pencil bigger-120"></i>
								  </a>
								  <button onclick="deleteRecord('faqs','<?php echo $faq->faq_id; ?>','Confirm delete <?php echo $question; ?> ')" class="btn btn-xs btn-danger" title="Delete FAQ">
								  <i class="ace-icon fa fa-trash-o bigger-120"></i>
								  </button>
							   </div>
							</td>
						 </tr>
						 <?php $i++; } if($numPages>1) { ?>
						 <tr>
							<td colspan="5" valign="middle">
							   <ul class="pager pager-rounded" style="float:right;">
								  <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>
								  <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
								  <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
								  <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
								  <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
							   </ul>
							</td>
						 </tr>
						 <?php } ?>
					  </tbody>
				   </table>
				</div>
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
		function get_selected_data() {
			var sortOrder = document.getElementById('sortOrder').value;
			var numRecords = document.getElementById('numRecords').value;

			window.location = "faqs?sortOrder=" + sortOrder + "&numRecords=" + numRecords;
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

		function updateCountry(countryId) {
			var country = document.getElementById("country" + countryId).value;
			var continentId = document.getElementById("continent" + countryId).value;
			var country_code = document.getElementById("country_code" + countryId).value;


			if (continentId < 1) {
				alert("Select a continent");
				document.getElementById("continent" + countryId).focus();

			} else if (country.length < 1) {
				alert("Enter country name");
				document.getElementById("country" + countryId).focus();

			} else {
				$.ajax({
					url: "countries?edit=1&continentId=" + continentId + "&country=" + country + "&countryId=" + countryId + "&country_code=" + country_code,
					success: function (result) {
						row = JSON.parse(result);


						var responseMessage = '';
						var responseColor = '';

						if (row.response == 1) {
							responseColor = '#090';
							responseMessage = "Country updated successfully !";
							document.getElementById('displayCountry' + countryId).innerHTML = country;
							document.getElementById('displayContinent' + countryId).innerHTML = row.continent;
							document.getElementById('displayCode' + countryId).innerHTML = country_code;
						} else {
							responseColor = '#FF0000';
							responseMessage = "Error occured, please try again !";
						}

						document.getElementById('editResponse' + countryId).style.color = responseColor;
						document.getElementById('editResponse' + countryId).innerHTML = responseMessage;

					}
				});
			}


		}

		// Wait for the DOM to be ready
/* 		$(function () {

			$("#addCountryForm").validate();

			$("#continent").rules("add", {
				required: true,
				messages: {
					required: "Please enter website"
				}
			});

			$("#country").rules("add", {
				required: true,
				messages: {
					required: "Please enter artist name"
				}
			});
		}); */
	</script>
    @endsection       