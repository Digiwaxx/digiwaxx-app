

@extends('layouts.client_dashboard')
@section('content')
<style>
   .btn.btn-info{flex: none;border: 0}
</style>
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>Submitted Track Versions</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                     <?php if(isset($alert_class)) 
                        { ?>
                     <div class="<?php echo $alert_class; ?>">
                        <p><?php echo $alert_message; ?></p>
                     </div>
                     <?php } // print_r($formData); ?>
                     <div class="mtk-blk f-block">
                        <div class="stk-btn clearfix">
                           
                        </div>
                        <!-- eof fby-blk -->
                        <div style="clear:both;"></div>
                        <style>
                           th { background:#B32F85; } 
                        </style>
                        <div class="mtk-list mCustomScrollbar">
                           <table id="sample-table-1" class="table table-bordered my-digicoins">
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
                                 if(!empty($submitted_tracks) && count($submitted_tracks) > 0){
                                    foreach ($submitted_tracks as $track) {

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
                                             <a href="<?php echo url('view_uploaded_tracks_versions?tid='.$track->id); ?>" title="View Submitted Track Versions" class="btn btn-info">
                                             <i class="ace-icon fa fa-eye bigger-120"></i>
                                             </a>
                                          </div>
                                          </td>
                                       </tr>
                                    <?php

                                     $i++;

                                    }
                                 
                                  ?>
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
                        <!-- eof mtk-list -->
                     </div>
                  <!-- eof middle block -->
               </div>
            </div>
            <div class="col-xl-3 col-12">
               @include('clients.dashboard.includes.my-tracks')
            </div>
         </div>
      </div>
   </div>
</section>

<script>

function goToPage(page, pid){
   var param = '?';
   window.location = page + param + "page=" + pid;
}

</script>
@endsection

