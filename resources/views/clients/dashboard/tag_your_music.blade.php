

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>How to tag your music</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  
                     <div class="mtk-blk f-block">
                        <div class="stk-btn clearfix">
                          
                        </div>
                        <div class="mtf clearfix"></div>
                        <div style="clear:both;"></div>
                        <div class="mtk-list">
                           <h6 class="mb-3 mt-4"><strong>How to Tag your songs in iTunes</strong></h6>
                           <p class="mb-2">1.  Select Multiple songs in iTunes.</p>
                           <p class="mb-2">2. Right click on selected files and choose Get Info.</p>
                           <p class="mb-2">3. Add tags to selected songs.</p>
                           <p class="mb-2">4. Select the song that you want to add tags.</p>
                           <p class="mb-2">5. Step 2: Add Tags to the song.</p>
                           <h6 class="mb-3 mt-4"><strong>How to tag using groove for windows 10</strong></h6>
                           <p>Open Groove. Click on My Music.
                              Under "My Music," use the "Filter" menu, and select Only on this device option.
                              Right-click the album with the tracks you want to update and click Edit info option.
                              In the "Edit Album Info" tab there's a lot of information you can edit, including basic information like album title, artist, and genre. You can even click the pencil button to update the album thumbnail image.
                           </p>
                           <p>
                              Additionally, within this experience, you can edit song titles and artist information and reorganize tracks if they're in the wrong order.
                              Quick Tip: Turning on the Show advanced options allows you to change the song soft title.
                           </p>
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
<!-- /21741445840/336x280>
<div id='div-gpt-ad-1539597853871-0' style='height:133px; width:240px;'>
   <script>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1539597853871-0'); });
   </script>
</div-->
<script>
   function tagTrack(tid)
   {
   
     var tag = document.getElementById('tag'+tid).value;
     $.ajax({url: "Tag_your_music?saveTag=1&tid="+tid+"&tag="+tag, success: function(result){
   
       document.getElementById('tagresponse'+tid).innerHTML = 'TAG added successfully !';
       document.getElementById('tagspan'+tid).innerHTML = tag;
   
         // var obj = JSON.parse(result);          
         // alert(result);          
         // alert(obj.status);   
     /*  var count = obj.length;    
          var liList = '';   
          var optionList = ''; //'<option value="">What country do you live in</option>';   
         for (var i=0;i<count;i++)    
          {
           liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';        
            optionList += '<option value="'+obj[i].id+'">'+obj[i].name+'</option>';   
          }
          document.getElementsByClassName('filter-option pull-left')[5].innerHTML = 'Sub Genre';    
          document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;   
          document.getElementById('subGenre').innerHTML = optionList;
   */
          
   
     }});
      }
         
         function sortBy(type,id)   
         {
                var records = document.getElementById('records').value;    
             window.location = "Tag_your_music?sortBy="+type+"&sortOrder="+id+"&records="+records;   
         }
   
         function changeNumRecords(type,id,records)   
         {   
             window.location = "Tag_your_music?sortBy="+type+"&sortOrder="+id+"&records="+records;   
         }
   
</script>
@endsection

