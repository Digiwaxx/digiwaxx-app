
@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>My Label Reps</h2>
               </div>
               <div class="tabs-section">
                  <div class="mlr-blk f-block">
                    <div class="row mb-3">
                        <div class="col-6">
                        
                         </div>
                         <div class="col-6 text-right">
                        
                             <a href="<?php echo url("Client_add_label_reps"); ?>" class="btn btn-alt">Add Label Rep</a>
                         </div>

                         <div class="fby-blk clearfix">
                           <div style="clear:both;"></div>
                           <?php if($numPages>1) { ?>
                           <div class="pgm">
                              <?php echo $start+1; ?> - <?php echo $start+$numRecords; ?> OF <?php echo $num_records; ?>
                           </div>
                           <div class="tnav clearfix">
                              <span><a href="javascript:void()" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>
                              <span class="num"><?php echo $currentPageNo; ?></span>
                              <span><a href="javascript:void()" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
                           </div>
                           <?php } ?>
                        </div>
                        <!-- eof fby-blk -->
                     </div>
                     <?php 
                        if($reps['numRows']>0)
                        
                        {                        
                        foreach($reps['data'] as $rep) {
                        
                        ?>
                     <div class="mlr clearfix">
                        <p class="lb"><i class="fa fa-group"></i> <?php echo $title = urldecode($rep->title); echo " - ".urldecode($rep->name); ?></p>
                        <p class="elink"><a href="<?php echo url("Client_edit_label_reps?repId=".$rep->id); ?>">EDIT</a> / 
                           <a href="javascript:void()" onclick="deleteRecord('<?php echo $title; ?>','<?php echo $rep->id; ?>')">DELETE</a>
                        </p>
                     </div>
                     <?php } 
                        }  else { ?>
                     <div class="mlr clearfix">
                        <p class="lb" style="width:auto;"><i class="fa fa-group"></i>No, label reps added yet !</p>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-12">
               @include('clients.dashboard.includes.my-tracks')
            </div>
         </div>
      </div>
   </div>
</section>


<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
   var googletag = googletag || {};
   googletag.cmd = googletag.cmd || [];
</script>
<script>
   googletag.cmd.push(function() {
     googletag.defineSlot('/21741445840/336x280', [240, 133], 'div-gpt-ad-1539597853871-0').addService(googletag.pubads());
     googletag.pubads().enableSingleRequest();
     googletag.enableServices();
   });
</script>
<!-- /21741445840/336x280 -->

<!-- advertisement code starts -->
<!-- <div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div> -->
<!-- advertisement code ends -->


<script>
   function deleteRecord(title,did)
   
   {
     if(confirm("Do you want to delete "+title))
   
     {
       window.location = "Client_label_reps?did="+did;
   
     }
   }

   function goToPage(page,pid)

{

   window.location = page+"?page="+pid;

}
</script>
@endsection

