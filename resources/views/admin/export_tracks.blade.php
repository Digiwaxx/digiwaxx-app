

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
         <i class="ace-icon fa fa-list list-icon"></i>
         Export Tracks
      </li>
   </ul>
   <!-- /.breadcrumb -->
   <!-- #section:basics/content.searchbox -->
   <!-- /.nav-search -->
   <!-- /section:basics/content.searchbox -->
</div>
<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
   <div class="row">
        <div class="col-xs-12">
               <h3 class="header smaller lighter">
                  Export Tracks
               </h3>
            </div>
      <div class="searchDiv">
         <form class="form-inline searchForm" id="searchForm" autocomplete="off">
            <div class="col-sm-3 col-xs-12">
                <div class="input-group">
                   <span class="input-group-addon">From</span>                          
                   <input type="date" class="nav-search-input form-control" id="fromDate" name="fromDate" value="<?php echo $searchFromDate; ?>" />
               </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="input-group">
                   <span class="input-group-addon">To</span>
                   <input type="date" class="nav-search-input form-control" id="toDate" name="toDate" value="<?php echo $searchToDate; ?>" />
               </div>
            </div>
            <div class="col-sm-3 col-xs-12">
               <label class="hidden-lg hidden-md hidden-sm hidden-xs"></label>
               <input type="submit" value="Search" name="search" />                         
               <!-- <input type="button" value="Reset" onclick="searchReset()"  /> -->
               <!-- <input type="button" value="Reset" onclick="window.location.href='{{ route('export_tracks') }}'" /> -->
               @if($searchFromDate != '' || $searchToDate != '')    
               <input type="button" value="Reset" onclick="window.location.href='{{ route('export_tracks') }}'" />
               @else
               <input type="reset" value="Reset" id="reset_filter_id" style="border: 1px solid rgb(111 179 224); border-radius: 4px !important; background-color: rgb(111 179 224); border-color: rgb(111 179 224);
                  color: rgb(255 255 255); line-height: 28px; width: 70px;"/>
               @endif 
            </div>
         </form>
      </div>
   </div>
   <!-- /.page-header -->
   <div class="space-10"></div>
   <div class="row">
      <div class="col-xs-12">
         <!-- PAGE CONTENT BEGINS -->
         <div class="row">
            <div class="col-xs-12">
               <?php 
                  if(isset($alert_message))                  
                  {                  
                  ?>
               <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
               <?php 
                  }
                  ?>
               <?php if(isset($tracks)) { ?>
               <!-- <a href="<?php echo url("admin/download_tracks_data"); ?>" class="btn btn-info btn-sm" style="margin-bottom:6px; float:right;">
                  <span class="glyphicon glyphicon-export"></span> Export
                  </a> -->
               <a href="{{ route('sample_file') }}" class="btn btn-info btn-sm" style="margin-bottom:6px; float:right;">
               <span class="glyphicon glyphicon-export"></span> Export
               </a>
               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th class="center" width="60">
                           S. No.
                        </th>
                        <th>Artwork</th>
                        <th class="hidden-xs">Artist</th>
                        <th class="hidden-xs">Title</th>
                        <th class="hidden-xs">Album</th>
                        <th>Client</th>
                        <!--<th class="hidden-md hidden-sm hidden-xs">Label</th>
                           <th class="hidden-md hidden-sm hidden-xs">Time</th>
                           
                           -->
                        <th class="hidden-md hidden-sm hidden-xs">Added On</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        if(!empty($start ))  {
                        
                            $start = $start ;
                        } 
                        else{
                            $start = 0;
                        }
                        
                        $i = $start+1;                        
                        foreach($tracks as $track)                        
                        {
                        ?>
                     <tr>
                        <td class="center">
                           <?php echo $i; ?>                                    
                        </td>
                        <td>
                           <?php 
                              //echo $track->product_name;  
                              
                              $artWork = asset('ImagesUp/'.$track->imgpage);
                              ?>
                           <img src="<?php echo $artWork; ?>" width="50" height="56" />
                        </td>
                        <td class="hidden-xs"><?php echo urldecode($track->artist);  ?></td>
                        <td class="hidden-xs"><?php echo urldecode($track->title); ?></td>
                        <td class="hidden-xs">
                           <?php echo ucfirst(urldecode($track->album));  ?>
                        </td>
                        <td><?php echo urldecode($track->uname);  ?></td>
                        <?php // echo urldecode($track->label);  ?>
                        <?php  // echo ucfirst(urldecode($track->time)); ?>
                        <td class="hidden-md hidden-sm hidden-xs"><?php $addedOn = $track->added;
                           $addedOn = explode(' ',$track->added);                           
                           $addedDate =  explode('-',$addedOn[0]);                           
                           $addedDate = $addedDate[2].'-'.$addedDate[1].'-'.$addedDate[0];                           
                           echo $addedDate;
                           ?></td>
                     </tr>
                     <?php $i++; } ?>
                  </tbody>
               </table>
               <?php } ?>
            </div>
            <!-- /.span -->
         </div>
         <!-- /.row -->
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
   var artist = document.getElementById('artist').value;   
   var title = document.getElementById('title').value;   
   var label = document.getElementById('label').value;   
   var album = document.getElementById('album').value;   
   var producer = document.getElementById('producer').value;   
   var client = document.getElementById('client').value;   
   
   window.location = "tracks?sortBy="+sortBy+"&sortOrder="+sortOrder+"&numRecords="+numRecords+"&artist="+artist+"&title="+title+"&label="+label+"&album="+album+"&producer="+producer+"&client="+client;   
       }
   function selectStaff(trackId)   
   {
           var status = 0;
           if(document.getElementById('staff'+trackId).checked == true)   
           {
               status = 1;   
           }
   
           else   
           {   
               status = 0;   
           }
   
           $.ajax({url: "tracks?choosen=1&section=1&trackId="+trackId+"&status="+status, success: function(result){
   
            row = JSON.parse(result);   
               var responseMessage = '';   
               var responseColor =  '';
               if(row.response==1)   
               {   
               responseColor = '#090';
               }   
               else   
               {
               responseColor = '#FF0000';
               }
   
               document.getElementById('staffResponse'+trackId).style.color = responseColor;                  
               document.getElementById('staffResponse'+trackId).innerHTML = row.message;   
               
   
               }});
   
   }
   
   function selectYou(trackId)   
   {
           var status = 0;  
           
           if(document.getElementById('you'+trackId).checked == true)   
           {   
               status = 1;   
           }   
           else   
           {   
               status = 0;
           }
           $.ajax({url: "tracks?choosen=1&section=2&trackId="+trackId+"&status="+status, success: function(result){
               row = JSON.parse(result);   
               var responseMessage = '';   
               var responseColor =  '';   
               if(row.response==1)   
               {   
               responseColor = '#090';
               }   
               else   
               {   
               responseColor = '#FF0000';
               }
               
                  document.getElementById('youResponse'+trackId).style.color = responseColor;   
               document.getElementById('youResponse'+trackId).innerHTML = row.message;
   
               }});
   }
</script>
@endsection

