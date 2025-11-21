

@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
<style>
   .mb-2{
   margin-bottom:10px;
   }
   .mt-2{
   margin-top:10px;
   }
</style>
<div class="main-content-inner">
<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
   <script type="text/javascript">
      try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
   </script>
   <ul class="breadcrumb">
      <li>
         <i class="ace-icon fa fa-users users-icon"></i>
         <a href="<?php echo url('admin/clients'); ?>">Clients</a>
      </li>
      <li class="active">Pending Client Requests</li>
   </ul>
   <!-- /.breadcrumb -->
   <!-- #section:basics/content.searchbox -->
   <div class="nav-search" id="nav-search">
      <form class="form-search">
         <label class="hidden-md hidden-sm hidden-xs">Sort By</label>
         <span class="input-icon">
            <select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
               <option <?php if(strcmp($sortBy,'name')==0) { ?> selected="selected" <?php } ?> value="name">Name</option>
               <option <?php if(strcmp($sortBy,'username')==0) { ?> selected="selected" <?php } ?> value="username">Username</option>
               <option <?php if(strcmp($sortBy,'added')==0) { ?> selected="selected" <?php } ?> value="added">Added</option>
               <option <?php if(strcmp($sortBy,'company')==0) { ?> selected="selected" <?php } ?> value="company">Company</option>
               <option <?php if(strcmp($sortBy,'city')==0) { ?> selected="selected" <?php } ?> value="city">City</option>
               <option <?php if(strcmp($sortBy,'state')==0) { ?> selected="selected" <?php } ?> value="state">State</option>
               <option <?php if(strcmp($sortBy,'zip')==0) { ?> selected="selected" <?php } ?> value="zip">Zip</option>
               <option <?php if(strcmp($sortBy,'email')==0) { ?> selected="selected" <?php } ?> value="email">Email</option>
               <option <?php if(strcmp($sortBy,'phone')==0) { ?> selected="selected" <?php } ?> value="phone">Phone</option>
            </select>
         </span>
         <span class="input-icon">
            <label class="hidden-md hidden-sm hidden-xs"> Order By</label>
            <select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
               <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
               <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
            </select>
         </span>
         <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
         <span class="input-icon">
            <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
               <option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
               <option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
               <option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="500">50</option>
               <option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
               <option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
            </select>
         </span>
      </form>
   </div>
   <!-- /.nav-search -->
   <!-- /section:basics/content.searchbox -->
</div>
<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
   <div class="row">
      <div class="col-xs-12 searchDiv">
         <form class="form-inline searchForm" id="searchForm" method="get">
            <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
            <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
            <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
            <div class="row">
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">Company</span>
               <input type="text" class="nav-search-input form-control" id="company" name="company" value="<?php echo $searchCompany; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">Username</span>
               <input type="text" class="nav-search-input form-control" id="username" name="username" value="<?php echo $searchUsername; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">Name </span>
               <input type="text" class="nav-search-input form-control" id="name" name="name" value="<?php echo $searchName; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">Email</span>
               <input type="text" class="nav-search-input form-control" id="email" name="email" value="<?php echo $searchEmail; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">Phone</span>
               <input type="text" class="nav-search-input form-control" id="phone" name="phone" value="<?php echo $searchPhone; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">City</span>
               <input type="text" class="nav-search-input form-control" id="city" name="city" value="<?php echo $searchCity; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">State</span>
               <input type="text" class="nav-search-input form-control" id="state" name="state" value="<?php echo $searchState; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
            	<div class="input-group">
               <span class="input-group-addon">Zip</span>
               <input type="text" class="nav-search-input form-control" id="zip" name="zip" value="<?php echo $searchZip; ?>" />
           		</div>
            </div>
            <div class="col-sm-3 col-xs-6">
               <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
               <input type="submit" value="Search" name="search" />
               <!-- <input type="button" value="Reset" onclick="searchReset()"  /> -->
               @if($searchUsername != '' || $searchName != '' || $searchCompany != ''  || $searchZip != '' || $searchState != '' || $searchCity != '' || $searchPhone != '' || $searchEmail != '')	
               <input type="button" value="Reset" onclick="window.location.href='{{ route('pending_clients') }}'" />
               @else
               <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                  color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
               @endif 
            </div>
        </div>
         </form>
      </div>
   </div>
   <!-- /.page-header -->
   <?php
      if(  isset($_GET['multipledeclined'] )  || isset($_GET['multipleaccepted'] ) ||  isset($_GET['multipledeleted'] ) ){ ?>
   <div class="alert alert-success mt-2">
      <?php if(  isset($_GET['multipledeclined'] )){ echo "Clients rejected successfully"; }  ?>
      <?php if(  isset($_GET['multipleaccepted'] )){ echo "Clients accepted successfully"; }  ?>
      <?php if(  isset($_GET['multipledeleted'] )){ echo "Clients deleted successfully"; }  ?>
   </div>
   <?php } ?>
   <div class="space-6"></div>
   <tr>
      <td colspan="9">
         <div class="mb-2">
            <a title="Delete Member" class="btn btn-xs btn-danger" name="checkDel" value="check_delete" onClick="submitCheck()">
            <i class="ace-icon fa fa-trash-o bigger-120"></i> Delete
            </a>
            <a title="Approve Member" class="btn btn-xs btn-success" name="membApprove" value="memb_approve" onClick="submitApprove()">
            <i class="ace-icon fa fa-align-justify bigger-120"></i> Approve
            </a>
            <a title="Reject Member" class="btn btn-xs btn-danger" name="membReject" value="memb_reject"  onClick="submitReject()">
            <i class="ace-icon fa fa-align-justify bigger-120"></i> Reject
            </a>
         </div>
      </td>
   </tr>
   <div class="row">
      <div class="col-xs-12">
         <!-- PAGE CONTENT BEGINS -->
         <div class="row">
            <div class="col-xs-12">
               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th></th>
                        <th class="center" width="100">
                           S. No.
                        </th>
                        <th>User Name</th>
                        <th class="hidden-xs">Company</th>
                        <th class="hidden-sm hidden-xs">Contact Name</th>
                        <th class="hidden-md hidden-sm hidden-xs">Email</th>
                        <th class="hidden-md hidden-sm hidden-xs">Confirmed</th>
                        <th width="110">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $i = $start+1;
                        if($clients['numRows']>0)
                        {
                        foreach($clients['data'] as $client)
                        {
                        
                        ?>
                     <tr>
                        <td><input name="item[]" type="checkbox" class="chkitm" value="<?php echo $client->id; ?>"></td>
                        <td class="center">
                           <?php echo $i; ?>									
                        </td>
                        <td>
                           <?php echo $client->uname; ?>
                        </td>
                        <td class="hidden-xs">
                           <?php echo ucfirst(urldecode($client->name));  ?>
                        </td>
                        <td class="hidden-sm hidden-xs">
                           <?php echo ucfirst(urldecode($client->ccontact)); ?>
                        </td>
                        <td class="hidden-md hidden-sm hidden-xs"><?php echo urldecode($client->email);  ?></td>
                        <td>
                           <?php  if($client->confirmEmail==1) { echo 'Confirmed'; } else {  echo 'No'; }  ?>
                        </td>
                        <td>
                           <div class="btn-group">
                              <a title="Approve Client" href="<?php echo url("admin/manage_pending_client?cid=".$client->id); ?>" class="btn btn-xs btn-success">
                              <i class="ace-icon fa fa-align-justify bigger-120"></i>
                              </a><a title="View Client" href="<?php echo url("admin/client_view?cid=".$client->id); ?>" class="btn btn-xs btn-success">
                              <i class="fa fa-eye bigger-120"></i>
                              </a>
                           </div>
                        </td>
                     </tr>
                     <?php $i++; } if($numPages>1) { ?>
                     <tr>
                        <td colspan="7">
                           <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                              <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>
                              <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                              <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                              <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                              <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>
                           </ul>
                        </td>
                     </tr>
                     <?php } } else { ?>
                     <tr>
                        <td colspan="7">No Data found.</td>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
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
   function get_selected_data()
   {
                  	 var sortBy = document.getElementById('sortBy').value;
                  	 var sortOrder = document.getElementById('sortOrder').value;
                  	 var numRecords = document.getElementById('numRecords').value;
                  	 var company = document.getElementById('company').value;
                  	 var username = document.getElementById('username').value;
                  	 var name = document.getElementById('name').value;
                  	 var email = document.getElementById('email').value;
                  	 var phone = document.getElementById('phone').value;
                  	 var city = document.getElementById('city').value;
                  	 var state = document.getElementById('state').value;
                  	 var zip = document.getElementById('zip').value;
                  	 
                  	window.location = "pending_clients?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&username="+username+"&name="+name+"&company="+company+"&email="+email+"&phone="+phone+"&city="+city+"&state="+state+"&zip="+zip;
   }
   
   function submitCheck(){
        var list=[];
   		if($('.chkitm:checked').size() >0){
   			var r = confirm('Are you sure want to delete all selected clients?');
   			if(r){
   				
   				$('input.chkitm').each(function () {
                                     var sThisVal = (this.checked ? $(this).val() : "");
                                     if(sThisVal){
                                          list.push(sThisVal);
                                     }
                                });
   				console.log(list.join());
   				window.location = 'manage_pending_client?deleteIds='+list.join();
   			}
   		}
   		else{
   			alert("Please select the clients to proceed for delete.")
   		}
   }
   	function submitApprove(){
   	    var list=[];
   		if($('.chkitm:checked').size() >0){
   			var r = confirm('Are you sure want to approve all selected clients?');
   			if(r){
   				
   					$('input.chkitm').each(function () {
                                             var sThisVal = (this.checked ? $(this).val() : "");
                                             if(sThisVal){
                                                  list.push(sThisVal);
                                             }
                                        });
      								// 	console.log(list.join());
   				        window.location = 'manage_pending_client?acceptIds='+list.join();
   			}
   		}
   		else{
   			alert("Please select the clients to proceed for approve.")
   		}
   	}   	
   	function submitReject(){
   	    var list=[];
   		if($('.chkitm:checked').size() >0){
   			var r = confirm('Are you sure want to reject all selected clients?');
   			if(r){
   					$('input.chkitm').each(function () {
                     var sThisVal = (this.checked ? $(this).val() : "");
                     if(sThisVal){
                      list.push(sThisVal);
                         }
                    });
						window.location = 'manage_pending_client?declineIds='+list.join();
   			}
   		}
   		else{
   			alert("Please select the clients to proceed for reject.")
   		}
   	}
   
</script>
@endsection

