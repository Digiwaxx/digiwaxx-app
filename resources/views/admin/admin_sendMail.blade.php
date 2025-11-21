
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
			 <a href="{{ route('admin_listMails') }}">
			 <i class="ace-icon fa fa-list list-icon"></i>
			 Mails</a>
		  </li>
		  <li class="active">Send Mail</li>
	   </ul>
	   <div class="nav-search" id="nav-search">
		  <form class="form-search">
			 <span class="input-icon">
			 <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
			 <i class="ace-icon fa fa-search nav-search-icon"></i>
			 </span>
		  </form>
	   </div>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	  
	   <!-- /.nav-search -->
	   <!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	 <!--   <div class="page-header">
	   	 
	   </div> -->
	   <!-- /.page-header -->

			 <!-- PAGE CONTENT BEGINS -->
				   <?php if(isset($alert_message)) { ?>
				   <div class="<?php echo $alert_class; ?>">
					  <?php echo $alert_message; ?>
				   </div>
				   <?php } ?>
				   <form role="form" action="" method="post" onsubmit="return validate()" autocomplete="off">
						@csrf
					  <div class="row">
					  	<div class="col-xs-12">
						 <div class="form-group">
							<label for="form-field-1"> Send To </label>
							<div class="row">
								<div class="col-sm-6 col-xs-12">
								   <div class="radio">
									  <label>
									  <input name="sendTo" id="sendTo1" class="ace" type="radio" value="1" onClick="selectSendTo(this.value)" required>
									  <span class="lbl"> All registered members</span>
									  </label>
								   </div>
							   <!--	<div class="radio">
								  <label>
									<input name="sendTo" class="ace" type="radio" value="2" onClick="selectSendTo(this.value)">
									<span class="lbl"> Mobile 2 way addresses only</span>
								  </label>
								  </div>
								  -->
								</div>
								<div class="col-sm-6 col-xs-12">
							   <div class="radio">
								  <label>
								  <input name="sendTo" id="sendTo3" class="ace" type="radio" value="3" onClick="selectSendTo(this.value)"> 
								  <span class="lbl"> Select E-mail addresses</span>
								  </label>
							   </div>
							</div>
							</div>
						 </div>
						</div>
						 <div class="col-xs-12">
							 <div class="form-group" id="extraMobile" style="display:none;">
								<label for="form-field-1"> Top 5 tracks </label>
							    <input type="text" class="form-control" id="tracks" name="tracks" >
							</div>
						 </div>
						 <div class="col-xs-12">
							 <div class="form-group" id="extraEmail" style="display:none;">
							   <label for="form-field-1"> Email </label>
							
							   <textarea class="form-control" type="text" id="multipleEmails" name="multipleEmails"></textarea>
							</div>
						 </div>
						<div class="col-xs-12">
						 	<div class="form-group">
							<label for="form-field-1"> Types </label>
								<div class="row">
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="typesAll" id="typesAll" onClick="allTypes()" class="ace" type="checkbox">
										  <span class="lbl"> All</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="dj_mixer" class="ace" type="checkbox" value="1" id="type1">
										  <span class="lbl"> DJ/Mixer)</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="radio_station" class="ace" type="checkbox" value="1" id="type2">
										  <span class="lbl"> Radio Station (Non-DJ/Mixer)</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="mass_media" class="ace" type="checkbox" value="1" id="type3">
										  <span class="lbl"> Mass Media</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="record_label" class="ace" type="checkbox" value="1" id="type4">
										  <span class="lbl"> Record Label</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="management" class="ace" type="checkbox" value="1" id="type5">
										  <span class="lbl"> Management</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="clothing_apparel" class="ace" type="checkbox" value="1" id="type6">
										  <span class="lbl"> Clothing/Apparel</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="promoter" class="ace" type="checkbox" value="1" id="type7">
										  <span class="lbl"> Promoter</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="special_services" class="ace" type="checkbox" value="1" id="type8">
										  <span class="lbl"> Special Services</span>
										  </label>
									   </div>
									</div>
								   <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
								   	<div class="checkbox">
										  <label>
										  <input name="production_talent" class="ace" type="checkbox" value="1" id="type9">
										  <span class="lbl"> Production/Talent</span>
										  </label>
									   </div>
									</div>
								</div>
							</div>
						 </div>
						<div class="col-xs-12 col-sm-6">
						 	<div class="form-group">
							<label for="form-field-1"> Track </label>
							
							   <input type="hidden" id="track" name="track"  />
							   <input class="form-control" type="text" id="trackSearch" onkeyup="getList(this.value)" onfocus="getList(this.value)" onMouseOver="showList()" onMouseOut="removeList()" />
							  
							   <div style="clear:both;"></div>
							   <div style="position:relative;">
								  <div onMouseOver="showList()" onMouseOut="removeList()"  id="searchListDisplay" style=" position:absolute; background:#000; padding:10px; padding-right:0px; top:0px; z-index:100; display:none;width: 100%">
									 <div style="max-height:200px; overflow-y:scroll;">
										<ul id="searchList" style="list-style:none; margin:0px;">
										   <li>Loading ...</li>
										</ul>
									 </div>
								  </div>
							   </div>
							   <div style="clear:both;"></div>
							   <!--<span> * marked are not sent </span>-->
							</div>
						 </div>
						 <div class="col-xs-12 col-sm-6">
						 	<div class="form-group">
								<label for="form-field-1"> Template </label>
							   <select class="form-control" id="template" name="template">
								  <option value="0">Select Template</option>
								  <?php 
									 foreach($templates as $template) {  ?>
								  <option value="<?php echo $template->id; ?>">
									 <?php echo $template->name; ?>
								  </option>
								  <?php }  ?>
							   </select>
							</div>
						 </div>
						 <div class="col-xs-12">
							 <div class="form-group">
								<label for="form-field-1"> Message </label>
							   	<textarea class="form-control" id="message" name="message"></textarea>
							</div>
						 </div>
						 
						 <div class="col-xs-12">
						 	<div class="clearfix form-actions text-right">
							   <button class="btn btn-info btn-sm" type="submit" name="sendMail">
							   <i class="ace-icon fa fa-check bigger-110"></i>
							   Send Mailout
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
			 <!-- PAGE CONTENT ENDS -->
		</div>
	</div>
	</div>
	<!-- /.page-content -->
<script>

	function removeList() {
		document.getElementById('searchListDisplay').style.display = 'none';
	}

	function showList() {
		document.getElementById('searchListDisplay').style.display = 'block';
	}

	function selectItem(id, title) {
		document.getElementById('track').value = id;
		document.getElementById('trackSearch').value = title;
		document.getElementById('searchListDisplay').style.display = 'none';
	}


	function getList(searchKey) {

		var output = '';
		$.ajax({
			url: "send_mail?searchKey=" + searchKey + "&trackSearch=1",
			success: function (result) {

				var json_obj = $.parseJSON(result);

				for (var i in json_obj) {
					var abc = "'" + json_obj[i].id + "','" + json_obj[i].title + "'";
					output += '<li><a href="javascript:void()" onclick="selectItem(' + abc + ')">' + json_obj[i].title + '</a></li>';
				}

				document.getElementById('searchList').innerHTML = output;
				document.getElementById('searchListDisplay').style.display = 'block';


			}
		});

	}

	function validate() {

		sendTo1 = document.getElementById('sendTo1');
		sendTo3 = document.getElementById('sendTo3');
		multipleEmails = document.getElementById('multipleEmails');
		track = document.getElementById('track');
		template = document.getElementById('template');


		if (sendTo1.checked == true) {

		} else if (sendTo3.checked == true) {

			if (multipleEmails.value.length < 1) {
				alert("Enter email id");
				return false;
			}

		}


		if (track.value < 1) {
			alert("Select track");
			track.focus();
			return false;
		}

		if (template.value < 1) {
			alert("Select template");
			template.focus();
			return false;
		}


	}


	function allTypes() {

		if (document.getElementById('typesAll').checked) {


			document.getElementById('type1').checked = 'checked';
			document.getElementById('type2').checked = 'checked';
			document.getElementById('type3').checked = 'checked';
			document.getElementById('type4').checked = 'checked';
			document.getElementById('type5').checked = 'checked';
			document.getElementById('type6').checked = 'checked';
			document.getElementById('type7').checked = 'checked';
			document.getElementById('type8').checked = 'checked';
			document.getElementById('type9').checked = 'checked';
		} else {
			document.getElementById('type1').checked = '';
			document.getElementById('type2').checked = '';
			document.getElementById('type3').checked = '';
			document.getElementById('type4').checked = '';
			document.getElementById('type5').checked = '';
			document.getElementById('type6').checked = '';
			document.getElementById('type7').checked = '';
			document.getElementById('type8').checked = '';
			document.getElementById('type9').checked = '';
		}

	}


	function selectSendTo(val) {

		document.getElementById('extraMobile').style.display = 'none';
		document.getElementById('extraEmail').style.display = 'none';
		if (val == 2) {
			document.getElementById('extraMobile').style.display = 'block';
		} else if (val == 3) {
			document.getElementById('extraEmail').style.display = 'block';

		}

	}

</script>
    @endsection       