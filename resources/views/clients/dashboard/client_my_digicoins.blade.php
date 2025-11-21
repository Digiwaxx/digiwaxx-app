

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>My Digicoins</h2>
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
                           <!--h5 class="pull-left">MY DIGICOINS</h5-->
                        </div>
                        <div class="fby-blk clearfix">
                           
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
                           <span class="badge btn-success float-right">Available Digicoins : <?php echo $available_digicoins; ?></span>
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
                                    <th class="center" width="60">
                                       S. No.
                                    </th>
                                    <th>Points</th>
                                    <th>Type</th>
                                    <th>Track</th>
                                    <th class="hidden-md hidden-sm hidden-xs" width="100">Date</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php 
                                    $i = $start+1;
                                    
                                    if($digicoins['numRows']>0)
                                    {
                                    foreach($digicoins['data'] as $coin)
                                    {
                                    ?>
                                 <tr>
                                    <td class="center">
                                       <?php echo $i; ?>
                                    </td>
                                    <td><?php echo $coin->points; ?></td>
                                    <td>
                                       <?php 
                                          if(($coin->type_id==6) || ($coin->type_id==7))
                                          
                                          {
                                            echo 'Purchased';
                                          
                                          }
                                          
                                          ?>
                                    </td>
                                    <td><?php 
                                       if($coin->type_id==6)
                                       
                                       {
                                           if($coin->points==50)
                                       
                                           {
                                             echo 'Silver Pakcage | Stripe';
                                           }
                                           else if($coin->points==80)
                                           {
                                             echo 'Gold Pakcage | Stripe';
                                       
                                           }
                                           else if($coin->points==100)
                                           {
                                             echo 'Purple Pakcage | Stripe';
                                       
                                           }
                                       
                                       }
                                       else if($coin->type_id==7)
                                       
                                       {
                                           if($coin->points==50)
                                       
                                           {
                                             echo 'Silver Pakcage | Paypal';
                                       
                                           }
                                           else if($coin->points==80)
                                           {
                                             echo 'Gold Pakcage | Paypal';
                                       
                                           }
                                       
                                           else if($coin->points==100)
                                       
                                           {
                                             echo 'Purple Pakcage | Paypal';                                       
                                           }                                       
                                       }
                                       
                                       ?></td>
                                    <td class="hidden-md hidden-sm hidden-xs"><?php                                             
                                       $dt  = $coin->date_time;
                                       
                                       $yr=strval(substr($dt,0,4)); 
                                       
                                       $mo=strval(substr($dt,5,2)); 
                                       
                                       $da=strval(substr($dt,8,2)); 
                                       
                                       echo $mo.'-'.$da.'-'.$yr;
                                       
                                       ?></td>
                                 </tr>
                                 <?php $i++; } } else { ?>
                                 <tr>
                                    <td colspan="5">No Data found. </td>
                                 </tr>
                                 <?php } ?>
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
<!-- <div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div> -->

<script>    
   function sortBy(type,id)
   {
       var records = document.getElementById('records').value;      
       window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;
   }
   function changeNumRecords(type,id,records)
   {
       window.location = "Client_tracks?sortBy="+type+"&sortOrder="+id+"&records="+records;
   }

   function goToPage(page,pid)

{

   window.location = page+"?page="+pid;

}

</script>
@endsection

