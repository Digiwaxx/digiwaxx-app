@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Track Archives</h2>
              </div>
            <div class="tabs-section"> 
			
			<div class="mem-dblk">			   
			   <div class="hidden-lg d-none" style="margin-top:-38px;">Audio track archives</div>
			   <?php if(isset($welcomeMsg)) { ?>
			   <div class="alert alert-primary alert-dismissable">
				  <?php echo $welcomeMsg; ?>
			   </div>
			   <?php } ?>
			   <div class="sby-blk clearfix">
					 <form class="form-inline" action="">
						<div class="row">
							<div class="col-md-5 col-sm-6">
						   <select class="form-control" id="searchYear" name="searchYear">
							  <?php for($i=2017;$i>2000;$i--)   { ?>
							  <option <?php if($searchYear==$i) { ?> selected="selected" <?php } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							  <?php } ?>
						   </select>
						 </div>
						 <div class="col-md-5 col-sm-6">
						   <select class="form-control" id="searchMonth" name="searchMonth">
							  <option value="0">Month</option>
							  <option <?php if(strcmp($searchMonth,'01')==0) { ?> selected="selected" <?php } ?> value="01">January</option>
							  <option <?php if(strcmp($searchMonth,'02')==0) { ?> selected="selected" <?php } ?> value="02">February</option>
							  <option <?php if(strcmp($searchMonth,'03')==0) { ?> selected="selected" <?php } ?> value="03">March</option>
							  <option <?php if(strcmp($searchMonth,'04')==0) { ?> selected="selected" <?php } ?> value="04">April</option>
							  <option <?php if(strcmp($searchMonth,'05')==0) { ?> selected="selected" <?php } ?> value="05">May</option>
							  <option <?php if(strcmp($searchMonth,'06')==0) { ?> selected="selected" <?php } ?> value="06">June</option>
							  <option <?php if(strcmp($searchMonth,'07')==0) { ?> selected="selected" <?php } ?> value="07">July</option>
							  <option <?php if(strcmp($searchMonth,'08')==0) { ?> selected="selected" <?php } ?> value="08">August</option>
							  <option <?php if(strcmp($searchMonth,'09')==0) { ?> selected="selected" <?php } ?> value="09">September</option>
							  <option <?php if(strcmp($searchMonth,'10')==0) { ?> selected="selected" <?php } ?> value="10">October</option>
							  <option <?php if(strcmp($searchMonth,'11')==0) { ?> selected="selected" <?php } ?> value="11">November</option>
							  <option <?php if(strcmp($searchMonth,'12')==0) { ?> selected="selected" <?php } ?> value="12">December</option>
						   </select>
						   <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
						   <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
						 </div>
						 <div class="col-md-2 col-sm-12 col-12">
						 	 <button type="submit" name="search" class="btn btn-theme btn-gradient">Search</button>
						 </div>
						</div>
						
					 </form>
				  <?php 
					 $sortClass = '';
					 $orderByAsc = 'inline';
					 $orderByDesc = 'none';
					 $orderById = 2;
					 
					 if(strcmp($sortBy,'song')==0)
					 {
						$sortClass = 'active';
						if($sortOrder==2)
						{
						  $orderByAsc = 'none';
						  $orderByDesc = 'inline';
						 $orderById = 1; 
						}  
					 } ?>
				  <a href="javascript:void()" onClick="sortBy('<?php echo $currentPage; ?>','song','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">  
				  SONG 
				  <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i> 
				  <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
				  </a>
				  <?php 
					 $sortClass = '';
					 $orderByAsc = 'inline';
					 $orderByDesc = 'none';
					 $orderById = 2;
					 
					 if(strcmp($sortBy,'artist')==0)
					 {
						$sortClass = 'active';
						if($sortOrder==2)
						{
						  $orderByAsc = 'none';
						  $orderByDesc = 'inline';
						 $orderById = 1; 
						}  
					 } ?>
				  <a href="javascript:void()" onClick="sortBy('<?php echo $currentPage; ?>','artist','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">                                    ARTIST 
				  <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i> 
				  <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
				  </a>
				  <?php 
					 $sortClass = '';
					 $orderByAsc = 'inline';
					 $orderByDesc = 'none';
					 $orderById = 2;
					 
					 if(strcmp($sortBy,'album')==0)
					 {
						$sortClass = 'active';
						if($sortOrder==2)
						{
						  $orderByAsc = 'none';
						  $orderByDesc = 'inline';
						 $orderById = 1; 
						}  
					 } ?>
				  <a href="javascript:void()" onClick="sortBy('<?php echo $currentPage; ?>','album','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">                                    ALBUM 
				  <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i> 
				  <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
				  </a>
				  <?php 
					 $sortClass = '';
					 $orderByAsc = 'inline';
					 $orderByDesc = 'none';
					 $orderById = 2;
					 
					 if(strcmp($sortBy,'label')==0)
					 {
						$sortClass = 'active';
						if($sortOrder==2)
						{
						  $orderByAsc = 'none'; 
						  $orderByDesc = 'inline';
						 $orderById = 1; 
						}  
					 } ?>
				  <a href="javascript:void()" onClick="sortBy('<?php echo $currentPage; ?>','label','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">                                    LABEL 
				  <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i> 
				  <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
				  </a>
				  <?php 
					 $sortClass = '';
					 $orderByAsc = 'inline';
					 $orderByDesc = 'none';
					 $orderById = 2;
					 
					 if(strcmp($sortBy,'date')==0)
					 {
						$sortClass = 'active';
						if($sortOrder==2)
						{
						  $orderByAsc = 'none';
						  $orderByDesc = 'inline';
						 $orderById = 1; 
						}  
					 } ?>
				  <a href="javascript:void()" onClick="sortBy('<?php echo $currentPage; ?>','date','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">                                    DATE 
				  <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i> 
				  <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
				  </a>
				  <?php 
					 $sortClass = '';
					 $orderByAsc = 'inline';
					 $orderByDesc = 'none';
					 $orderById = 2;
					 
					 if(strcmp($sortBy,'bpm')==0)
					 {
						$sortClass = 'active';
						if($sortOrder==2)
						{
						  $orderByAsc = 'none';
						  $orderByDesc = 'inline';
						 $orderById = 1; 
						}  
					 } ?>
				  <a href="javascript:void()" onClick="sortBy('<?php echo $currentPage; ?>','bpm','<?php echo $orderById; ?>','<?php echo $urlSortString; ?>')" class="ats pull-left <?php echo $sortClass; ?>">                                    BPM 
				  <i class="fa fa-caret-up fup" style="display:<?php echo $orderByAsc; ?>"></i> 
				  <i class="fa fa-caret-down fdn" style="display:<?php echo $orderByDesc; ?>"></i>
				  </a>
			   </div>
			   <!-- eof sby-blk -->
			   <div class="fby-blk clearfix">
				  <div style="clear:both;"></div>
				  <div class="nop">
					 <select class="rfn-sb npg selectpicker" onChange="changeNumRecords('<?php echo $currentPage; ?>','<?php echo $urlRecordString; ?>',this.value)">
						<option <?php if($numRecords==10) {  ?> selected="selected" <?php } ?> value="10">10 PER PAGE</option>
						<option <?php if($numRecords==20) {  ?> selected="selected" <?php } ?> value="20">20 PER PAGE</option>
						<option <?php if($numRecords==30) {  ?> selected="selected" <?php } ?> value="30">30 PER PAGE</option>
						<option <?php if($numRecords==40) {  ?> selected="selected" <?php } ?> value="40">40 PER PAGE</option>
						<option <?php if($numRecords==50) {  ?> selected="selected" <?php } ?> value="50">50 PER PAGE</option>
					 </select>
				  </div>
				  <!-- eof nop -->
				  <?php if($numPages>1) { ?>
				  <div class="pgm">
					 <?php echo $start+1; ?> - <?php echo $start+$numRecords; ?> OF <?php echo $num_records; ?>
				  </div>
				  <div class="tnav clearfix">
					 <span><a href="javascript:void();" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo-1; ?>','<?php echo $urlString; ?>')" style="<?php if(($currentPageNo-1) == 0){ echo 'cursor:not-allowed'; }?>" ><i class="fa  fa-angle-double-left"></i></a></span>
					 <span class="num"><?php echo $currentPageNo; ?></span>
					 <span><a href="javascript:void();" onClick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo+1; ?>','<?php echo $urlString; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>
				  </div>
				  <?php } ?>
			   </div>
			   <!-- eof fby-blk -->
			   <div class="mtop-list mCustomScrollbar">
				  <?php
					 if($tracks['numRows']>0)
					 {
					 
					  foreach($tracks['data'] as $track) 
					 { ?>
				  <div class="item">
					 <div class="row">
						<div class="col-lg-1 col-md-2 col-sm-2 col-xs-4">
						   <p style="position:relative;">
							  <?php
								 if($mp3s[$track->id]['numRows']>0)
								 {
								     
									 if (!empty($track->imgpage) && file_exists(base_path("ImagesUp/".$track->imgpage))){
										$img = asset('ImagesUp/'.$track->imgpage);  
									 }else{
										$img = asset('public/images/noimage-avl.jpg');
									 }
									 
									   $trackLocation = $mp3s[$track->id]['data'][0]->location;
                                                                $fileid = (int) $trackLocation;
                                                                if (strpos($trackLocation, '.mp3') !== false) {
                                                                    $trackLink =url('Download_member_track?track=' . $trackLocation . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&title=' . $track->title);
                                                                } else if ((int) $fileid) {
                                                                    $trackLink =url('Download_member_track?track=' . $fileid . '&mp3Id=' . $track->id . '&trackId=' . $mp3s[$track->id]['data'][0]->id . '&pcloud=true');
                                                                }else {
                                                                    $trackLink = 'http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3';
                                                                }
									 
										?>
							  <a href="javascript:void();" onClick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','<?php echo $trackLink; ?>','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','<?php  echo $img; ?>','<?php echo $track->id; ?>')">
							  <img src="<?php  echo $img; ?>" width="60" height="60">
							  <img class="playButton" src="assets/img/play-btn.png">
							  </a>
							  <?php
								 } else { ?>
							  <a href="javascript:void();" onClick="changeTrack('<?php echo urldecode($track->title); ?>','<?php echo urldecode($track->artist); ?>','http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3','http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg','http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png')"><img src="<?php echo url('assets/img/play-btn.png'); ?>"></a>
							  <?php } ?>
						   </p>
						</div>
						<div class="col-lg-5 col-md-3 col-sm-3 col-xs-6">
						   <p><?php echo urldecode($track->title); ?></p>
						   <p><span class="text-dim">Artist:</span> <?php echo urldecode($track->artist); ?></p>
						   <p><span class="text-dim">Album:</span> <?php echo urldecode($track->album); ?></p>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
						   <p><?php echo urldecode($track->label); ?></p>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
						   <p><?php
							  /* $addedOn = explode(' ',$track->added);
									   $addedDate = explode('-',$addedOn[0]);
									echo $addedDate = $addedDate[1].'/'.$addedDate[2].'/'.$addedDate[0];
							  */
							   ?></p>
						</div>
						<div class="col-lg-2 col-md-1 col-sm-1 col-xs-3">
						   <p><?php echo (!empty($track->bpm)) ? $track->bpm : ''; ?></p>
						</div>
					 </div>
					 <!-- eof row -->
				  </div>
				  <!-- eof item -->
				  <?php } } else { ?>
				  <h2>No Data found.</h2>
				  <?php } ?>
			   </div>
			   <!-- eof mtop-list -->
			</div>
			<!-- eof mem-dblk -->
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
     </div>
	 </section>
		<script>
		
		function searchTracks(page,urlString)
		{
		
		var param = '?';
	   if(urlString.length>3)
	   {
	   
		 param = '&';
	   }
		  var bpm = document.getElementById('bpm').value;
		  var version = document.getElementById('version').value;
		  var genre = document.getElementById('genre').value;
		  window.location = page+urlString+param+"bpm="+bpm+"&version="+version+"&genre="+genre+"&search=yes";
		
		}
        
		
		</script>	 
@endsection