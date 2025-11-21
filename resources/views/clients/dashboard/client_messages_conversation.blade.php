

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
                     <!--h5>MESSAGES</h5-->
                     <?php //pArr($memberInfo); die();?> 
                     <h2>YOUR CONVERSATION WITH <?php echo strtoupper($memberInfo['uname']); ?> </h2>
                     <?php  
                     
                        if(is_numeric($memberInfo['image'])){
                            $imgSrc=url('/pCloudImgDownload.php?fileID='.$memberInfo['image']);
                        }
                        else if($memberInfo['image'] && file_exists(base_path('member_images/'.$mid.'/'.$memberInfo['image'])))  
                        {
                        
                        $imgSrc = asset('member_images/'.$mid.'/'.$memberInfo['image']);
                        
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
                                 <?php echo $memberInfo['uname']; ?> <br>             
                              </div>
                           </div>
                           <div class="col-lg-9 col-md-9 col-sm-9">
                              <div class="smsg mCustomScrollbar">
                                 <div class="form-group clearfix">
                                    <textarea class="form-control" name="message" id="message" placeholder="Enter message" rows="5"></textarea>
                                    <input type="hidden" name="mid" id="mid" value="<?php echo $mid; ?>"  />
                                    <input  type="button" onclick="sendMessage()" class="login_btn btn ems" value="SEND MESSAGE">   
                                 </div>
                                 <div id="displayMessages">
                                    <?php
                                       $mons = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
                                       
                                                           if(isset($conversation['numRows']) && $conversation['numRows']>0)
                                       
                                                           {
                                                             foreach($conversation['data'] as $message)
                                       
                                                             {
                                                                   if(!(in_array($message->messageId,$archiveMsgs)))
                                                                { 
                                                             if($message->senderType==1 && $message->senderId==Session::get('clientId'))
                                                             {
                                                               $class = 'tmsg';
                                                             }
                                                             else
                                                             {
                                                               $class = 'fmsg';
                                                             }
                                                             ?>
                                    <div class="<?php echo $class; ?>">
                                       <p><?php echo stripcslashes($message->message); ?></p>
                                       <?php 
                                          $dateTime =  explode(' ',$message->dateTime); 
                                          $date =  explode('-',$dateTime[0]);
                                          $date1 = $mons[$date[1]].' ';
                                          $date2 = $date[2].', '.$date[0]; 
                                          $displayDate = $date1.$date2;
                                          ?>
                                       <div class="dt"><?php echo $displayDate; ?>
                                          <?php if(in_array($message->messageId,$starMsgs)) 
                                             {
                                                $starClass = 'goldStar';
                                             }
                                             else
                                             {
                                             $starClass = 'silverStar';
                                             }
                                             
                                             ?>         
                                          <span style="float:right; margin-left:20px;">
                                          <span id="star<?php echo $message->messageId; ?>" class="<?php echo $starClass; ?>"  onclick="markStar('<?php echo $message->messageId; ?>')">
                                          <i class="fa fa-star" aria-hidden="true"></i>
                                          </span>
                                          <span id="archive<?php echo $message->messageId; ?>" class="inactiveArchive" onclick="makeArchive('<?php echo $message->messageId; ?>')">     
                                          <i class="fa fa-archive" aria-hidden="true"></i>
                                          </span>                             
                                          </span>
                                       </div>
                                       <div class="shape"></div>
                                    </div>
                                    <?php 
                                       }
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
<div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div>
<script>
   window.onload = function() {
   
       
   
       setInterval(getMsgs, 1000);
   
       }
   
       
   
       //setInterval(getMsgs()},3000);
   
       
   
       function getMsgs()
   
       {
   
       var mid = document.getElementById('mid').value;
   
   $.ajax({url: "Client_messages_conversation?getConversation=1&mid="+mid, success: function(result){
   
   
   
       document.getElementById("displayMessages").innerHTML = result;
   
   }});
   
       }
   
       
   
       function makeArchive(messageId)
   
       {
   
       var className = document.getElementById('archive'+messageId).className;
       if(className==='activeArchive')
   
       {
         document.getElementById('archive'+messageId).className = "inactiveArchive";
   
       }
       else
   
       {    
         document.getElementById('archive'+messageId).className = "activeArchive";
   
       }
       var mid = document.getElementById('mid').value;
   
        $.ajax({url: "Client_messages_conversation?messageId="+messageId+"&archive=1&mid="+mid, success: function(result){
        }});
   
       }
       function markStar(messageId)
   
       {
       var className = document.getElementById('star'+messageId).className;
   
       if(className==='goldStar')
   
       {
         document.getElementById('star'+messageId).className = "silverStar";
   
       }
       else
   
       {    
         document.getElementById('star'+messageId).className = "goldStar";
   
       }
        var mid = document.getElementById('mid').value;
   
        $.ajax({url: "Client_messages_conversation?messageId="+messageId+"&star=1&mid="+mid, success: function(result){
            }});
   
       }
   
       function sendMessage()
   
       {
       var message = document.getElementById('message').value;
       var mid = document.getElementById('mid').value;
       document.getElementById('message').value = '';
       document.getElementById('message').focus();
       if(message.length>0)
   
       {
       $.ajax({url: "Client_messages_conversation?message="+message+"&mid="+mid, success: function(result){
   
       var obj = JSON.parse(result);
   
       //document.getElementById("demo").innerHTML = myobj.name;
   
       if(obj.response>0)
   
       {
         var msgDiv = document.createElement("div");
         msgDiv.setAttribute("class", "tmsg");
         var dtDiv = document.createElement("div");
   
         dtDiv.setAttribute("class", "dt");
         var shapeDiv = document.createElement("div");
   
         shapeDiv.setAttribute("class", "shape");
     
   
         var node = document.createElement("p");
         var textnode = document.createTextNode(message);
         node.appendChild(textnode);   
         msgDiv.appendChild(node);
         var textnode1 = document.createTextNode(obj.dt);   
         dtDiv.appendChild(textnode1);   
         msgDiv.appendChild(dtDiv);
         msgDiv.appendChild(shapeDiv);
         $("#displayMessages").prepend(msgDiv);
   
       }
   
       else
   
       {
   
       }
   
   }});
   
       }
    }
       
</script>
@endsection

