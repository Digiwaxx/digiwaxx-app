@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
   <div class="main-content-inner">
      <!-- #section:basics/content.breadcrumbs -->
      <div class="breadcrumbs" id="breadcrumbs">
         <script type="text/javascript">
            try {
               ace.settings.check('breadcrumbs', 'fixed')
            } catch (e) {}
         </script>
         <ul class="breadcrumb">
            <li>
               <a href="<?php echo url("admin/tracks"); ?>">
                  <i class="ace-icon fa fa-list list-icon"></i>
                  Tracks</a>
            </li>
            <li class="active">Submitted Tracks Versions</li>
         </ul>
         <!-- /.breadcrumb -->
      </div>
      <!-- /section:basics/content.breadcrumbs -->
      <div class="page-content">
         <!-- PAGE CONTENT BEGINS -->
         <div class="row">
            <div class="col-xs-12">
               <h3 class="header smaller lighter">
                  Submitted Tracks Versions
               </h3>
               <?php if (isset($alert_class)) { ?>
                  <div class="<?php echo $alert_class; ?>">
                     <button class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                     </button>
                     <?php echo $alert_message; ?>
                  </div>
               <?php } ?>
               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th class="center" width="60">S. No.</th>
                        <th>Track Title</th>
                        <th class="hidden-xs">Submitted By Client</th>
                        <th class="hidden-md hidden-sm hidden-xs">Created On</th>
                        <th width="140">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $i = $start + 1;
                     if(!empty($tracks)  && count($tracks) > 0){
                     foreach ($tracks as $track) {

                     ?>
                        <tr>
                           <td class="center">
                              <?php echo $i; ?>
                           </td>
                           <td>
                              <?php echo $title = urldecode($track->title);
                              ?>
                           </td>
                           <td class=" hidden-xs">
                              <?php if (!empty($track->uname)) {
                                 echo urldecode($track->uname);
                              } ?>
                           </td>
                           <td class="hidden-md hidden-sm hidden-xs">
                              <?php
                              $date = new DateTime($track->created_at);
                              $result = $date->format('d-M-Y');
                              echo $result;

                              ?></td>
                           <td>
                              <!-- Modal -->
                              <div id="trackPreview<?php echo $track->id; ?>" class="modal fade" role="dialog">
                                 <div class="modal-dialog" style="width:80%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                       </div>
                                       <div class="modal-body" id="trackData<?php echo $track->id; ?>">
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="btn-group">
                              <!-- <button onclick="previewTrackVersions('<?php // echo htmlspecialchars(url('admin/submitted_tracks_versions')); ?>', '<?php // echo htmlspecialchars($track->id); ?>', 'preview')" title="Preview Track Versions" class="btn btn-xs btn-success">
                              <i class="ace-icon fa fa-eye bigger-120"></i>
                              </button> -->
                              <a href="<?php echo url('admin/submitted_tracks_versions_edit?tid='.$track->id); ?>" title="Edit Submitted Track Versions" class="btn btn-xs btn-info" target="_blank">
                              <i class="ace-icon fa fa-pencil bigger-120"></i>
                              </a>
                              <!-- <button onclick="approveTrackVersions('<?php //echo $track->id; ?>','<?php //echo $title; ?>')" title="Approve Track Versions" class="btn btn-xs btn-success">
                              <i class="ace-icon fa fa-check bigger-120"></i>
                              </button> -->
                              <!-- <button onclick="deleteRecord('submitted_tracks_versions','<?php // echo $track->id; ?>','Confirm delete <?php // echo $track->title; ?> ')" class="btn btn-xs btn-danger">
                              <i class="ace-icon fa fa-trash-o bigger-120"></i>
                              </button> -->
                           </div>
                           </td>
                        </tr>
                     <?php $i++;
                     } ?>
                     <tr>
                        <td colspan="9">
                           <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                              <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','1')">
                                    << </a>
                              </li>
                              <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>')">
                                    < </a>
                              </li>
                              <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
                              <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>')"> > </a></li>
                              <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
                           </ul>
                        </td>
                     </tr>
                  <?php }else{
                        echo '<tr><td colspan="5" style="text-align:center">Sorry! Nothing found.</td></tr>';
                   } ?>                     
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
   function approveTrackVersions(trackSubId, title) {
      if (confirm("Confirm approval " + title)) {
         window.location = "submitted_tracks_versions?approveVersionsTrackId=" + trackSubId;
      }
   }

   function previewTrackVersions(urlString, trackSubId, action) {
      $('.processing_loader_gif').show();
      $.ajax({
         url: urlString + "?trackSubId=" + trackSubId + "&action=" + action + "&modalView=1",
         success: function(result) {

            $("#trackData" + trackSubId).html(result);
            $('#trackPreview' + trackSubId).modal('show');
            $('.processing_loader_gif').hide();
         }
      });

   }
</script>
@endsection