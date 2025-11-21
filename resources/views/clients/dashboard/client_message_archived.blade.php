

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>Messages</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  <div class="msg-con-blk f-block">                    
					<h2>YOUR CONVERSATION WITH <?php echo strtoupper($memberUname); ?> </h2>
                     <div class="msgs">
                         
						 <?php 
						   if(is_numeric($memberImage)){
                              $imgSrc=url('/pCloudImgDownload.php?fileID='.$memberImage);
                           }
						   else if(isset($memberImage) && strlen($memberImage)>4 && file_exists(base_path('member_images/'.$memberId.'/'.$memberImage))) 
						   {
						   
							$imgSrc = asset('member_images/'.$memberId.'/'.$memberImage);
				// 			$imgSrc = asset('assets/img/msg-avatar.png');
						   
						   } 
						   
						   else
						   
						   {
						   
							 $imgSrc = asset('assets/img/profile-pic.png');
						   
						   }  ?>
                     <div class="msg-con">
                        <div class="row">
                           <div class="col-lg-3 col-md-3 col-sm-3">
                              <div class="avatar">
                                 <img src="<?php echo $imgSrc; ?>"> <br>
                                 <?php echo $memberUname; ?> <br>             
                              </div>
                           </div>
                                    <div class="col-lg-9 col-md-9 col-sm-9">

                                    	<div class="smsg mCustomScrollbar">

											

											<div id="displayMessages">

											<?php

			$mons = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');



											if(isset($conversation['numRows']) && $conversation['numRows']>0)

											{

											

											  foreach($conversation['data'] as $message)

											  {

											  

											  

											  if($message->senderType==1 && $message->senderId==$sessClientID)

											  {

											    $class = 'tmsg';

											  }

											  else

											  {

											    $class = 'fmsg';

											  }

											  

											  

											  ?>

											  

											  

											  <div class="<?php echo $class; ?>">

                                            	<p><?php echo $message->message; ?></p>

                                                

												

												<?php 

												$dateTime =  explode(' ',$message->dateTime); 

												$date =  explode('-',$dateTime[0]);

												$date1 = $mons[$date[1]].' ';

												$date2 = $date[2].', '.$date[0]; 

												$displayDate = $date1.$date2;

												

												

												?>

                                                <div class="dt"><?php echo $displayDate; ?></div>

                                                <div class="shape"></div>

                                            </div>

											

											<?php 

											  

											  

											  }

											

											}

											

											

											?>

											

											

                                            

                                            </div>

                                            

                                        </div>

                                    </div>
                        </div>
                     </div>
                     <!-- eof msg-con -->
                     </div>
                     <!-- eof msgs-->
                  </div>
               </div>
               <!-- eof middle block -->                
            </div>
            <div class="col-xl-3 col-12">
               @include('clients.dashboard.includes.my-tracks')
            </div>
         </div>
      </div>
   </div>
</section>
<!--script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
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
<!-- /21741445840/336x280 >
<div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div-->
@endsection

