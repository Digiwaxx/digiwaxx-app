
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
			 <a href="{{ route('admin_listNewsletters') }}">
			 <i class="ace-icon fa fa-list list-icon"></i>
			 Newsletters</a>
		  </li>
		  <li class="active">Send Newsletter</li>
	   </ul>
	   <!-- /.breadcrumb -->
	   <!-- #section:basics/content.searchbox -->
	   <div class="nav-search" id="nav-search">
	   </div>
	   <!-- /.nav-search -->
	   <!-- /section:basics/content.searchbox -->
	</div>
    <!-- /section:basics/content.breadcrumbs -->
	<div class="page-content">
	 
	   <!-- /.page-header -->
	   <div class="row">
		  <div class="col-xs-12">
			 <!-- PAGE CONTENT BEGINS -->
				   <?php if(isset($alertMessage)) { ?>
				   <div class="<?php echo $alertClass; ?>">
					  <?php echo $alertMessage; ?>
				   </div>
				   <?php } ?>
				   <form class="" role="form" action="" method="post" onsubmit="return validate()" autocomplete="off">
						@csrf
					  <div class="row">
					  	<div class="col-xs-12">
							 <div class="form-group">
								<label class="control-label" for="form-field-1">Subject</label>
								<input type="text" id="subject" name="subject" class="form-control">
							 </div>
						</div>
						<div class="col-xs-12">
							 <div class="form-group">
								<label class="control-label" for="form-field-1">Send To</label>
								   <div class="radio">
									  <label>
									  <input name="sendTo" id="sendTo1" class="ace" type="radio" value="1" onClick="selectSendTo(this.value)" required >
									  <span class="lbl">All Subscribers</span>
									  </label>
								   </div>
								   <div class="radio">
									  <label>
									  <input name="sendTo" id="sendTo2" class="ace" type="radio" value="2" onClick="selectSendTo(this.value)"> 
									  <span class="lbl">Selected Subscribers</span>
									  </label>
								   </div>
							 </div>
						 </div>
						 <div class="col-xs-12">
							 <div class="form-group" id="selectSubscribers" style="display:none;">
								<label class="control-label" for="form-field-1">Select Subscribers</label>
								   <input type="hidden" id="track" name="track"  />
								   <input type="text" id="trackSearch" onkeyup="getList(this.value)" class="form-control" onfocus="getList(this.value)" onMouseOver="showList()" onMouseOut="removeList()" />
								   
								   <div style="position:relative;">
									  <div onMouseOver="showList()" onMouseOut="removeList()"  id="searchListDisplay" class="w-100" style=" position:absolute; background:#000; padding:10px; padding-right:0px; top:0px; z-index:100; display:none;">
										 <div style="max-height:200px; overflow-y:scroll;">
											<ul id="searchList" style="list-style:none; margin:0px;">
											   <li>Loading ...</li>
											</ul> 
										 </div>
									  </div>
								   </div>
								   <ul id="selectedList" style="list-style:none; margin:0px;"></ul>
							 </div>
						</div>
						<div class="col-xs-12">
						 <div class="form-group">
							<label class="control-label" for="form-field-1">Message</label>
							   <textarea id="message" name="message" class="form-control"></textarea>
						 </div>
						</div>
						 
						 <div class="col-xs-12">
						 	<div class="form-actions text-right">
							   <button class="btn btn-info btn-sm" type="submit" name="sendNewsletter">
							   <i class="ace-icon fa fa-check bigger-110"></i>
							   Send Newsletter
							   </button>
							   &nbsp;
							   <button class="btn btn-sm news-reset-btn" type="reset">
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
			 
			 <!-- PAGE CONTENT ENDS -->
		  </div>
		  <!-- /.col -->
	   </div>
	   <!-- /.row -->
	</div>
	<!-- /.page-content -->
	<script>
		function remove_subscriber(id) {
			$("#li" + id).remove();
		}

		function removeList() {
			document.getElementById('searchListDisplay').style.display = 'none';
		}

		function showList() {
			document.getElementById('searchListDisplay').style.display = 'block';
		}

		function selectItem(id, title) {
			name = 'li' + id;
			if(document.getElementById(name) == null) {
				$("#selectedList").append("<li id="+ name + "><input type='hidden' name='selectedSubscribers[]' value='" + id + "' /><a href='javascript:void()' onclick='remove_subscriber(" + id + ")' class='remove-subscriber'><i class='fa fa-times' aria-hidden='true'></i></a> " + title + "</li>");
			}else{
				alert(title +' already Added');
			}

			// document.getElementById('track').value = id;
			//	 document.getElementById('selectedList').innerHTML = title;
			document.getElementById('searchListDisplay').style.display = 'none';


		}


		function getList(searchKey) {

			var output = '';
			$.ajax({
				url: "send_newsletter?searchKey=" + searchKey + "&subscriberSearch=1",
				success: function(result) {

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

			subject = document.getElementById('subject');
			sendTo1 = document.getElementById('sendTo1');
			sendTo2 = document.getElementById('sendTo2');
			//	track = document.getElementById('track');
			message = document.getElementById('message');


			if (subject.value.length < 1) {
				alert("Enter subject");
				subject.focus();
				return false;
			}

			if (sendTo1.checked == true) {

			} else if (sendTo2.checked == true) {
				/*
								if(multipleEmails.value.length<1)
								{
								   alert("Enter email id");
								   return false;
								}*/

			} else {
				alert("Select send to option");
				return false;

			}




			if (message.value.length < 1) {
				alert("Enter message");
				message.focus();
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
			document.getElementById('selectSubscribers').style.display = 'none';
			if (val == 2) {
				document.getElementById('selectSubscribers').style.display = 'block';
			}


		}
	</script>
    @endsection 