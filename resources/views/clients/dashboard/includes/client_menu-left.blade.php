<ul class="menu-default">
	 <li>
	   <a href="{{ url('Client_dashboard') }}"><span class="li-icon"><i class="fas fa-home"></i></span> My Dashboard</a>
	 </li>
      <li><a href="javascript:void(0);" onclick="open_campaign_modal();"><span class="li-icon"><i class="far fa-envelope"></i></span> Start a Campaign</a></li>

	  <li><a href="{{ url('Client_tracks') }}"><span class="li-icon"><i class="fas fa-music"></i></span> Tracks</a></li>

	  <li><a href="{{ url('uploaded_tracks_versions') }}"><span class="li-icon"><i class="fas fa-music"></i></span> Uploaded Track Versions</a></li>    

    <li><a href="{{ url('Client_submit_track') }}"><span class="li-icon"><i class="fa fa-cloud-upload"></i></span>Upload Your Music</a></li>
      
    <li><a href="{{ route('add-article') }}" target="_blank"><span class="li-icon"><i class="far fa fa-newspaper-o"></i></span> Add article</a></li>
    <li><a href="{{ url('Client_messages') }}"><span class="li-icon"><i class="far fa-envelope"></i></span> Messages</a> <?php if(isset($numMessages) && !empty($numMessages)){ ?> <span class="unread-cloud"> <?php echo $numMessages; ?> </span> <?php } ?></li>

    <li><a href="{{ url('Client_label_reps') }}"><span class="li-icon"><i class="fa fa-group"></i></span>My Label Reps</a></li>

    <li><a href="{{ url('Client_info') }}"><span class="li-icon"><i class="far fa-user"></i></span>My Info</a></li>

	  <li><a href="{{ url('Client_my_digicoins') }}"><span class="li-icon"><i class="fas fa-coins"></i></span>My Digicoins</a></li>
	 
	  <li><a href="{{ url('Tag_your_music') }}"><span class="li-icon"><i class="fa fa-info-circle"></i></span>How to Tag Your Music</a></li>

</ul>

<script type="text/javascript">
	
	function open_campaign_modal()
	{     
           
	      $('#alertModal_campaign').modal('show'); 
                      
	}

      function close_campaign_modal()
	{     
           
	      $('#alertModal_campaign').modal('hide'); 
                      
	}
</script>