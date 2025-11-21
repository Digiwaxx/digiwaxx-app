

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
               <a href="<?php echo url("admin/tracks"); ?>">
               <i class="ace-icon fa fa-list list-icon"></i>
               Tracks</a>
            </li>
            <li class="active">Manage Release Types</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
         <!-- PAGE CONTENT BEGINS -->
         <div class="row">
            <div class="col-xs-12">
               <h3 class="header smaller lighter">
                  Manage Release Types
               </h3>
            </div>
            <div class="col-xs-12">
               <?php if(isset($alert_class)) 
                  { ?>
               <div class="<?php echo $alert_class; ?>">
                  <button class="close" data-dismiss="alert">
                  <i class="ace-icon fa fa-times"></i>
                  </button>
                  <?php echo $alert_message; ?>
               </div>
               <?php } ?>
               <a href="<?php echo url('admin/add_releasetype'); ?>" class="btn btn-alt btn-sm mb-2">Add Release Type</a>
               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th class="center" width="60">
                           S. No.
                        </th>
                        <th>Release Title</th>
                        <th>Status</th>
                        <!-- <th width="140">Action</th> -->
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $i=1;
                        foreach($releasetype as $release)
                        {
                        
                        ?>
                     <tr>
                        <td class="center">
                           <?php echo $i; ?>
                        </td>
                        <td>
                           <?php echo $release->release_name ; ?>
                        </td>
                        <td class="">
                           <?php echo $release->status;  ?>
                        </td>
                        <!-- <td>
                           <div class="btn-group">
                           
                           	
                           <a href="<?php echo url('admin/edit_releasetype?tid='.$release->id); ?>" title="Edit Track" class="btn btn-xs btn-info">
                           <i class="ace-icon fa fa-pencil bigger-120"></i>
                           </a>
                           	
                           </div>
                           </td> -->
                     </tr>
                     <?php $i++; } ?>
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
@endsection

