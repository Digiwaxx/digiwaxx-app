

@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
<style>
   .mb-2{
   margin-bottom:10px;
   }
</style>
<div class="main-content-inner">
<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
   <script type="text/javascript">
      try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
   </script>
   <ul class="breadcrumb">
      <li class="active">
         <i class="ace-icon fa fa-users user-icon"></i>
         Members
      </li>
   </ul>
   <!-- /.breadcrumb -->
   <!-- #section:basics/content.searchbox -->
   <div class="nav-search" id="nav-search">
      <form class="form-search">
         Display No. Records
         <span class="input-icon">
            <select class="nav-search-input" id="numRecords" onchange="changeNumRecords('<?php echo 'pending_members'; ?>','<?php echo $sortBy; ?>','<?php echo $sortOrder; ?>',this.value)">
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
      <div class="col-xs-12 sortDiv">
         <!-- #section:basics/content.searchbox -->
         <form class="sortForm">
            <input type="hidden" id="page" value="pending_members"  />
            <label >Sort By</label>
            <span class="input-icon">
               <select class="nav-search-input" id="sortBy">
                  <option <?php if(strcmp($sortBy,'firstName')==0) { ?> selected="selected" <?php } ?> value="firstName">First Name</option>
                  <option <?php if(strcmp($sortBy,'lastName')==0) { ?> selected="selected" <?php } ?> value="lastName">Last Name</option>
                  <option <?php if(strcmp($sortBy,'stageName')==0) { ?> selected="selected" <?php } ?> value="stageName">Stage Name</option>
                  <option <?php if(strcmp($sortBy,'email')==0) { ?> selected="selected" <?php } ?> value="email">Email</option>
                  <option <?php if(strcmp($sortBy,'username')==0) { ?> selected="selected" <?php } ?> value="username">Username</option>
                  <option <?php if(strcmp($sortBy,'phone')==0) { ?> selected="selected" <?php } ?> value="phone">Phone</option>
                  <option <?php if(strcmp($sortBy,'city')==0) { ?> selected="selected" <?php } ?> value="city">City</option>
                  <option <?php if(strcmp($sortBy,'state')==0) { ?> selected="selected" <?php } ?> value="state">State</option>
                  <option <?php if(strcmp($sortBy,'registered')==0) { ?> selected="selected" <?php } ?> value="registered">Registered On</option>
                  <option <?php if(strcmp($sortBy,'lastLogin')==0) { ?> selected="selected" <?php } ?> value="lastLogin">Last Login</option>
               </select>
            </span>
            <span class="input-icon">
               <select class="nav-search-input" id="sortOrder">
                  <option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
                  <option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
               </select>
            </span>
            <input type="button" value="Sort" onclick="sortData()" />
         </form>
         <!-- /section:basics/content.searchbox -->
      </div>
      <div class="col-xs-12 searchDiv">
         <form class="searchForm" id="searchForm">
         	<div class="row">
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">First Name</label>
	               <input type="text" class="nav-search-input form-control" id="firstName" name="firstName" value="<?php if(!empty($searchFirstName)) echo $searchFirstName; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">Last Name</label>
	               <input type="text" class="nav-search-input form-control" id="lastName" name="lastName" value="<?php if(!empty($searchLastName)) echo $searchLastName; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">Stage Name</label>
	               <input type="text" class="nav-search-input form-control" id="stageName" name="stageName" value="<?php if(!empty($searchStageName)) echo $searchStageName; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">Email</label>
	               <input type="text" class="nav-search-input form-control" id="email" name="email" value="<?php if(!empty($searchEmail)) echo $searchEmail; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">Username</label>
	               <input type="text" class="nav-search-input form-control" id="username" name="username" value="<?php if(!empty($searchUsername)) echo $searchUsername; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">Phone</label>
	               <input type="text" class="nav-search-input form-control" id="phone" name="phone" value="<?php if(!empty($searchPhone)) echo $searchPhone; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">City</label>
	               <input type="text" class="nav-search-input form-control" id="city" name="city" value="<?php if(!empty($searchCity)) echo $searchCity; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	            	<div class="input-group">
	               <label class="input-group-addon">State</label>

	               <input type="text" class="nav-search-input form-control" id="state" name="state" value="<?php if(!empty($searchState)) echo $searchState; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	               
	               <div class="input-group">
	               	<label class="input-group-addon">Zip</label>
	               <input type="text" class="nav-search-input form-control" id="zip" name="zip" value="<?php if(!empty($searchZip)) echo $searchZip; ?>" />
	           		</div>
	            </div>
	            <div class="col-sm-3">
	               <input type="submit" value="Search" name="search" />
	               <!-- <input type="button" value="Reset" onclick="searchReset()"  /> -->
	               @if($searchFirstName != '' || $searchLastName != '' || $searchStageName != '' || $searchUsername != '' || $searchName != '' ||  $searchZip != '' || $searchState != '' || $searchCity != '' || $searchPhone != '' || $searchEmail != '')	
	               <input type="button" value="Reset" onclick="window.location.href='{{ route('pending_members') }}'" />
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
   <?php if (isset($alert_class) && isset($alert_message) && !empty($alert_message)) {
      ?>
   <div class="<?php echo $alert_class; ?>"> <?php echo $alert_message; ?> <button class="close" data-dismiss="alert">
      <i class="ace-icon fa fa-times"></i>
      </button>
   </div>
   <?php
      } ?>
   <div class="row">
      <form id="tableform">
         <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
               <div class="col-xs-12">
                  <div class="table-responsive">
                     <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <td colspan="9">
                                 <div class="mb-2">
                                    <a title="Delete Member" class="btn btn-xs btn-danger" name="checkDel" value="check_delete" onClick="submitCheck()">
                                    	<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete
                                    </a>
                                    <a title="Approve Member" class="btn btn-xs btn-success" name="membApprove" value="memb_approve" onClick="submitApprove()">
                                   	 	<i class="ace-icon fa fa-align-justify bigger-120"></i> Approve
                                    </a>
                                    <!--                      <a title="Reject Member" class="btn btn-xs btn-danger" name="membReject" value="memb_reject"  onClick="submitReject()">-->
                                    <!--<i class="ace-icon fa fa-align-justify bigger-120"></i> Reject-->
                                    <!--                      </a>-->
                                 </div>
                              </td>
                           </tr>
                           <tr>
                              <th class="center" width="100" style="position: relative;">
                                 <input name="item[]" type="checkbox" class="ace checkAll" style="opacity:1;    display: block;position: absolute;top: 6px;left: 44px;">
                              </th>
                              <th class="center" width="100">
                                 <span>S. No.</span>
                              </th>
                              <th>User Name</th>
                              <th>Name</th>
                              <th>Stage Name</th>
                              <th>Email</th>
                              <th>Member Type</th>
                              <th>Last Log On</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                              $i = $start+1;
                              if (isset($members[0])) {
                              foreach($members as $member)
                              
                              {
                              
                              ?>
                           <tr>
                              <td class="center">
                                 <div class="checkbox">
                                    <label>
                                    <input name="item[]" type="checkbox" class="ace chkitm" value="<?php echo $member->id; ?>">
                                    <span class="lbl"></span>
                                    </label>
                                 </div>
                              </td>
                              <td class="center">
                                 <div class="checkbox">
                                    <label>
                                    <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
                                    </label>
                                 </div>
                              </td>
                              <td>
                                 <?php echo $member->uname;  ?>
                              </td>
                              <td>
                                 <?php echo ucfirst(urldecode($member->fname));  ?>
                              </td>
                              <td>
                                 <?php echo ucfirst(urldecode($member->stagename)); ?>
                              </td>
                              <td><?php echo urldecode($member->email);  ?></td>
                              <td><?php
                                 $memberTypes = array();
                                 if($member->dj_mixer==1) {  $memberTypes[] =  "DJ Mixer"; }
                                 if($member->radio_station==1){ $memberTypes[] = "Radio Station"; }
                                 if($member->record_label==1){ $memberTypes[] = "Record Label"; }
                                 if($member->management==1){ $memberTypes[] = "Management"; }
                                 if($member->clothing_apparel==1){ $memberTypes[] = "Clothing Apparel"; }
                                 if($member->mass_media==1){ $memberTypes[] = "Mass Media"; }
                                 if($member->production_talent==1){ $memberTypes[] ="Production Talent"; }
                                 if($member->promoter==1){ $memberTypes[] = "Promoter"; }
                                 if($member->special_services==1){ $memberTypes[] = "Special Services"; }
                                 
                                 $countMemberTypes = count($memberTypes);						
                                 
                                 if($countMemberTypes==1)
                                 {
                                 echo $memberTypes[0];
                                 }
                                 else if($countMemberTypes>1)
                                 {
                                 echo $memberTypes[0]; 
                                 ?><span class="badge badge-warning"><?php echo $countMemberTypes; ?></span><?php 
                                 }                                 
                                 unset($memberTypes);
                                 
                                   ?>
                              </td>
                              <td><?php 
                                 $dt  = $member->lastlogon;	                                 
                                 $yr=strval(substr($dt,0,4));                                  
                                 $mo=strval(substr($dt,5,2));                                  
                                 $da=strval(substr($dt,8,2));                                  
                                 $hr=strval(substr($dt,11,2));                                  
                                 $mi=strval(substr($dt,14,2));                                  
                                 $se=strval(substr($dt,17,2)); 
                                 
                                 echo date("l M/d/Y h:i A", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";
                                 ?></td>
                              <td>
                                 <div class="btn-group">
                                    <!--<div class="hidden-sm hidden-xs btn-group">-->
                                    <a href="javascript://void(0);" title="Delete Member" onclick="deletePendingMemberRecord('pending_members','<?php echo $member->id; ?>','Confirm delete <?php echo $member->uname; ?> ')" class="btn btn-xs btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                    </a>
                                    <a href="<?php echo url("admin/manage_pending_member?mid=".$member->id); ?>" title="View Member" class="btn btn-xs btn-success">
                                    <i class="ace-icon fa fa-align-justify bigger-120"></i>
                                    </a>
                                 </div>
                              </td>
                           </tr>
                           <?php $i++; 
                              }
                                                          if ($numPages >= 1) { ?>
                           <tr>
                              <td colspan="9">
                                 <input type="hidden" name="actionmanager" id="actionmanager"/>
                                 <?php if ($numPages > 1) { ?>
                                 <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                                    <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')"> << </a></li>
                                    <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>
                                    <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                                    <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>
                                    <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
                                 </ul>
                                 <?php } ?>
                              </td>
                           </tr>
                           <?php }
                              } else {
                                  ?>
                           <tr>
                              <td colspan="8">No Data found.</td>
                           </tr>
                           <?php
                              } ?>
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
      </form>
   </div>
   <!-- /.row -->
</div>
<!-- /.page-content -->
<script>
   function deletePendingMemberRecord(page,did,msg) {
                      if(confirm(msg)) {
                          window.location = "members?mode=pending_members&did="+did;
                  	}
                   }
   
   function submitCheck(){
   if($('.chkitm:checked').length >0){
   var r = confirm('Are you sure want to delete all selected members?');
   if(r){
   $("#actionmanager").val('deleteall');
   $("#tableform").submit();
   }
   }
   else{
   alert("Please select the members to proceed for delete.")
   }
   }
   function submitApprove(){
   if($('.chkitm:checked').length >0){
   var r = confirm('Are you sure want to approve all selected members?');
   if(r){
   $("#actionmanager").val('approveall');
   $("#tableform").submit();
   }
   }
   else{
   alert("Please select the members to proceed for approve.")
   }
   }
   
   function submitReject(){
   if($('.chkitm:checked').length >0){
   var r = confirm('Are you sure want to reject all selected members?');
   if(r){
   $("#actionmanager").val('rejectall');
   $("#tableform").submit();
   }
   }
   else{
   alert("Please select the members to proceed for reject.")
   }
   }
   
   
</script>
@endsection

