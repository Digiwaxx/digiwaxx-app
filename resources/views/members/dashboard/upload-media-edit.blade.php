@extends('layouts.member_dashboard')
@section('content')
<style>
   .nopadding { padding:0px !important; }
   .amrFile { display:none !important; }
   .form-group { margin-bottom:30px; } 
</style>
<section class="main-dash">
   <aside>@include('layouts.include.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-12">
               <div class="dash-heading">
                  <h2>My Dashboard</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                  <div class="col-lg-12 col-md-12">
                     <?php if(isset($alert_class)) 
                        { ?>
                     <div class="<?php echo $alert_class; ?>">
                        <p><?php echo $alert_message; ?></p>
                     </div>
                     <?php } 
                        $dt = explode(' ',$track['data'][0]->releasedate); 
                        
                        $releaseDate = explode('-',$dt[0]); 
                        
                        
                        
                        
                        
                        ?>
                     <form action="" method="post" enctype="multipart/form-data" id="addTrack">
                        @csrf
                        <?php
                           /*if(strlen($track['data'][0]->imgpage)>3)
                           
                           {
                           
                           
                           
                           $imgSrc = 'ImagesUp/'.$track['data'][0]->imgpage;
                           
                           }
                           
                           else
                           
                           {
                           
                           $imgSrc = 'assets/img/track-logo.png';
                           
                           }*/  
                           
                               ?>
                        <div class="sat-blk f-block">
                           <h1>EDIT TRACK</h1>
                           <!--<div class="mCustomScrollbar" style="">-->
                              <div class="row">
                                 <div class="col-lg-8 col-md-9 col-sm-8">
                                    <div class="form-group">
                                       <input name="artist" id="artist"  class="form-control input"  size="20" placeholder="Artist Name & Features" type="text" value="<?php echo urldecode($track['data'][0]->artist); ?>">
                                    </div>
                                    <div class="form-group">
                                       <input name="title" id="title"  class="form-control input"  size="20" placeholder="Title" type="text" value="<?php echo urldecode($track['data'][0]->title); ?>">
                                    </div>
                                    <div class="form-group">
                                       <input name="producer" id="producer"  class="form-control input"  size="20" placeholder="Producer/Production Company" type="text" value="<?php echo urldecode($track['data'][0]->producers); ?>" >
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-3 col-sm-4">
                                    <div class="form-group">
                                       <label class="btn text-left" style="padding:0;">
                                       <?php 
                                          if(strlen($track['data'][0]->imgpage)>4)
                                          
                                          {
                                          
                                          $artWork =  asset('ImagesUp/'.$track['data'][0]->imgpage);
                                          
                                          }
                                          
                                          else
                                          
                                          {
                                          
                                          $artWork = 'assets/img/upload-artwork.jpg';
                                          
                                          } ?>
                                       <img src="<?php echo $artWork; ?>" class="img-responsive up-ar-img" id="previewImg"> <input id="artWork" name="artWork" style="display: none;" type="file">
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       <input name="trackTime" id="trackTime" class="form-control input"  size="20" placeholder="Track Time" type="text" value="<?php echo $track['data'][0]->time; ?>">
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                       <input type="text" name="bpm" id="bpm" class="form-control" placeholder="BPM" value="<?php echo $track['data'][0]->bpm; ?>" />
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <input name="album" id="album" class="form-control input"  size="20" placeholder="Album Name" type="text" value="<?php echo urldecode($track['data'][0]->album); ?>">
                              </div>
                              <div class="form-group">
                                 <select name="albumType" id="albumType" class="form-control">
                                    <option value="">Album Type</option>
                                    <option <?php if($track['data'][0]->albumType==1) { ?> selected <?php } ?> value="1">Single</option>
                                    <option <?php if($track['data'][0]->albumType==2) { ?> selected <?php } ?> value="2">Album</option>
                                    <option <?php if($track['data'][0]->albumType==3) { ?> selected <?php } ?> value="3">EP</option>
                                    <option <?php if($track['data'][0]->albumType==4) { ?> selected <?php } ?> value="4">Mixtape</option>
                                 </select>
                              </div>
                              <div class="row">
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                       <label>Album Release Date</label>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                       <select name="month" class="form-control">
                                          <option value="0">Month</option>
                                          <option <?php if($releaseDate[1]=='01') { ?> selected="selected" <?php } ?> value="01">January</option>
                                          <option <?php if($releaseDate[1]=='02') { ?> selected="selected" <?php } ?> value="02">Febr</option>
                                          <option <?php if($releaseDate[1]=='03') { ?> selected="selected" <?php } ?> value="03">March</option>
                                          <option <?php if($releaseDate[1]=='04') { ?> selected="selected" <?php } ?> value="04">April</option>
                                          <option <?php if($releaseDate[1]=='05') { ?> selected="selected" <?php } ?> value="05">May</option>
                                          <option <?php if($releaseDate[1]=='06') { ?> selected="selected" <?php } ?> value="06">June</option>
                                          <option <?php if($releaseDate[1]=='07') { ?> selected="selected" <?php } ?> value="07">July</option>
                                          <option <?php if($releaseDate[1]=='08') { ?> selected="selected" <?php } ?> value="08">August</option>
                                          <option <?php if($releaseDate[1]=='09') { ?> selected="selected" <?php } ?> value="09">September</option>
                                          <option <?php if($releaseDate[1]=='10') { ?> selected="selected" <?php } ?> value="10">October</option>
                                          <option <?php if($releaseDate[1]=='11') { ?> selected="selected" <?php } ?> value="11">November</option>
                                          <option <?php if($releaseDate[1]=='12') { ?> selected="selected" <?php } ?> value="12">December</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                       <select name="day" class="form-control">
                                          <option value="0">Day</option>
                                          <option <?php if(strcmp($releaseDate[2],'01')==0) { ?> selected="selected" <?php } ?> value="01">1</option>
                                          <option <?php if(strcmp($releaseDate[2],'02')==0) { ?> selected="selected" <?php } ?> value="02">2</option>
                                          <option <?php if(strcmp($releaseDate[2],'03')==0) { ?> selected="selected" <?php } ?> value="03">3</option>
                                          <option <?php if(strcmp($releaseDate[2],'04')==0) { ?> selected="selected" <?php } ?> value="04">4</option>
                                          <option <?php if(strcmp($releaseDate[2],'05')==0) { ?> selected="selected" <?php } ?> value="05">5</option>
                                          <option <?php if(strcmp($releaseDate[2],'06')==0) { ?> selected="selected" <?php } ?> value="06">6</option>
                                          <option <?php if(strcmp($releaseDate[2],'07')==0) { ?> selected="selected" <?php } ?> value="07">7</option>
                                          <option <?php if(strcmp($releaseDate[2],'08')==0) { ?> selected="selected" <?php } ?> value="08">8</option>
                                          <option <?php if(strcmp($releaseDate[2],'09')==0) { ?> selected="selected" <?php } ?> value="09">9</option>
                                          <option <?php if(strcmp($releaseDate[2],'10')==0) { ?> selected="selected" <?php } ?> value="10">10</option>
                                          <option <?php if(strcmp($releaseDate[2],'11')==0) { ?> selected="selected" <?php } ?> value="11">11</option>
                                          <option <?php if(strcmp($releaseDate[2],'12')==0) { ?> selected="selected" <?php } ?> value="12">12</option>
                                          <option <?php if(strcmp($releaseDate[2],'13')==0) { ?> selected="selected" <?php } ?> value="13">13</option>
                                          <option <?php if(strcmp($releaseDate[2],'14')==0) { ?> selected="selected" <?php } ?> value="14">14</option>
                                          <option <?php if(strcmp($releaseDate[2],'15')==0) { ?> selected="selected" <?php } ?> value="15">15</option>
                                          <option <?php if(strcmp($releaseDate[2],'16')==0) { ?> selected="selected" <?php } ?> value="16">16</option>
                                          <option <?php if(strcmp($releaseDate[2],'17')==0) { ?> selected="selected" <?php } ?> value="17">17</option>
                                          <option <?php if(strcmp($releaseDate[2],'18')==0) { ?> selected="selected" <?php } ?> value="18">18</option>
                                          <option <?php if(strcmp($releaseDate[2],'19')==0) { ?> selected="selected" <?php } ?> value="19">19</option>
                                          <option <?php if(strcmp($releaseDate[2],'20')==0) { ?> selected="selected" <?php } ?> value="20">20</option>
                                          <option <?php if(strcmp($releaseDate[2],'21')==0) { ?> selected="selected" <?php } ?> value="21">21</option>
                                          <option <?php if(strcmp($releaseDate[2],'22')==0) { ?> selected="selected" <?php } ?> value="22">22</option>
                                          <option <?php if(strcmp($releaseDate[2],'23')==0) { ?> selected="selected" <?php } ?> value="23">23</option>
                                          <option <?php if(strcmp($releaseDate[2],'24')==0) { ?> selected="selected" <?php } ?> value="24">24</option>
                                          <option <?php if(strcmp($releaseDate[2],'25')==0) { ?> selected="selected" <?php } ?> value="25">25</option>
                                          <option <?php if(strcmp($releaseDate[2],'26')==0) { ?> selected="selected" <?php } ?> value="26">26</option>
                                          <option <?php if(strcmp($releaseDate[2],'27')==0) { ?> selected="selected" <?php } ?> value="27">27</option>
                                          <option <?php if(strcmp($releaseDate[2],'28')==0) { ?> selected="selected" <?php } ?> value="28">28</option>
                                          <option <?php if(strcmp($releaseDate[2],'29')==0) { ?> selected="selected" <?php } ?> value="29">29</option>
                                          <option <?php if(strcmp($releaseDate[2],'30')==0) { ?> selected="selected" <?php } ?> value="30">30</option>
                                          <option <?php if(strcmp($releaseDate[2],'31')==0) { ?> selected="selected" <?php } ?> value="31">31</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="form-group">
                                       <select name="year" class="form-control">
                                          <option value="0">Year</option>
                                          <?php for($year=2000;$year<=2018;$year++) { ?>
                                          <option <?php if($releaseDate[0]==$year) { ?> selected="selected" <?php } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                          <?php } ?>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <input name="website" id="website"  class="form-control input"  size="20" placeholder="Website Link" type="text" value="<?php echo urldecode($track['data'][0]->link); ?>">
                              </div>
                              <div class="uptrk" id="uptrk1">
                                 <?php $linkDivDisplay1 = 'none'; $linkDivDisplay2 = 'none';
                                    if(strlen($track['data'][0]->link1)>1) { $linkDivDisplay1 = 'block'; }
                                    
                                    if(strlen($track['data'][0]->link2)>1) { $linkDivDisplay2 = 'block'; }
                                
                                    
                                    ?>
                                 <div class="form-group" id="linkDiv1" style="display:<?php echo $linkDivDisplay1; ?>;">
                                    <input name="website1" id="website1" value="<?php  echo $track['data'][0]->link1; ?>" class="form-control input"  size="20" placeholder="Website Link" type="text">
                                 </div>
                                 <div class="form-group" id="linkDiv2" style="display:<?php echo $linkDivDisplay2; ?>;">
                                    <input name="website2" id="website2" value="<?php  echo $track['data'][0]->link2; ?>" class="form-control input"  size="20" placeholder="Website Link" type="text">
                                 </div>
                              </div>
                              <div style="clear:both;"></div>
                              <input type="text" name="numLink" id="numLink" value="1" style="display:none;" size="4"   />
                              <a href="javascript:void()" class="addRemoveLinks" onclick="addLink()"><i class="fa fa-plus-circle"></i>
                              <span>Add</span>
                              </a>
                              <a href="javascript:void()" class="addRemoveLinks" onclick="removeLink()"><i class="fa fa-minus-circle"></i>
                              <span>Remove </span>
                              </a>
                              <div style="clear:both;"></div>
                              <div class="form-group">
                                 <input name="facebookLink" id="facebookLink" class="form-control input" value="<?php  echo $track['data'][0]->facebookLink; ?>" size="20" placeholder="Facebook Link" type="text">
                              </div>
                              <div class="form-group">
                                 <input name="twitterLink" id="twitterLink" class="form-control input" value="<?php  echo $track['data'][0]->twitterLink; ?>" size="20" placeholder="Twitter Link" type="text">
                              </div>
                              <div class="form-group">
                                 <textarea name="trackInfo" id="trackInfo" class="form-control" placeholder="Bonus Track Information" rows="5"><?php echo urldecode($track['data'][0]->moreinfo); ?></textarea>
                              </div>
                              <div class="form-group">
                                 <select name="genre" id="genre" class="form-control" onchange="change_genre(this.value)">
                                    <option value="">Genre</option>
                                    <?php if($genres['numRows']>0) {
                                       foreach($genres['data'] as $genre)
                                       
                                       { ?>
                                    <option <?php if($track['data'][0]->genreId==$genre->genreId) { ?> selected <?php } ?> value="<?php echo $genre->genreId; ?>"><?php echo $genre->genre; ?></option>
                                    <?php } } ?>
                                 </select>
                              </div>
                              <div class="form-group subgenres">
                                 <select name="subGenre" id="subGenre" class="form-control">
                                    <option value="">Sub Genre</option>
                                    <?php if($subGenres['numRows']>0) {
                                       foreach($subGenres['data'] as $genre)
                                       
                                       { ?>
                                    <option <?php if($track['data'][0]->subGenreId==$genre->subGenreId) { ?> selected <?php } ?> value="<?php echo $genre->subGenreId; ?>"><?php echo $genre->subGenre; ?></option>
                                    <?php } } ?>
                                 </select>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="pre-btn">
                                       <input type="submit" name="updateSubmittedTrack" value="Update Track" class="add_track_button">
                                    </div>
                                 </div>
                              </div>
                           <!--</div>-->
                        </div>
                     </form>
                  </div>
               </div>
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
   function change_genre(genreId)
   {
   
   
   $.ajax({url: "member_uploadmedia?getSubGenres=1&genreId="+genreId, success: function(result){   
     
        // console.log(result);
        var obj = JSON.parse(result);   
        var count = obj.length;    
        var liList = '';   
        var optionList = ''; //'<option value="">What country do you live in</option>';
        $('#subGenre').empty();
       for (var i=0;i<count;i++) 
   
        {
   
           
        //  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';            
    
        //   optionList += '<option value="'+obj[i].id+'">'+obj[i].name+'</option>';   
          $('#subGenre').append($("<option></option>").attr("value",obj[i].id).text(obj[i].name));
   
        }
   
        // document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
        // $('.dropdown-menu .inner').html(liList);
   
        // document.getElementById('subGenre').innerHTML = optionList;
        // $('.subGenre').html(optionList);
   
   }});
   
   
   
   }
   
   function removeLink()
   
   {  
   
   var numLink = document.getElementById('numLink').value;
   
   var id = parseInt(numLink)-1;   if(numLink>1)
   
   {
   
   document.getElementById('linkDiv'+id).style.display = 'none';
   
   document.getElementById('numLink').value = id;
   
   }    
   
   }
      
   
   function addLink()
   
   {  
   
   var numLink = document.getElementById('numLink').value;
   
   var newLink = parseInt(numLink)+1;
   
   if(numLink<3)   
   {
   
    document.getElementById('numLink').value = parseInt(numLink)+1;
   
    document.getElementById('linkDiv'+numLink).style.display = 'block';
   
   }
   
   }   
   
   function removePhone()
   
   {
   
   var numVersion = document.getElementById('numVersion').value;   
   var id = parseInt(numVersion)-1;
   
   if(numVersion>1)   
   {
   
   document.getElementById('uptrk').removeChild(document.getElementById('versionDiv'+id));
      document.getElementById('uptrk').removeChild(document.getElementById('imgDiv'+id));   
   document.getElementById('numVersion').value = id;
   
   }  
   }
    function addPhone(filePath)
   
   {
   
   var numVersion = document.getElementById('numVersion').value;
   
   var newVersion = parseInt(numVersion)+1;
   
   
   
   if(numVersion<4)
   
   {
   
   var clearDiv = document.createElement("div");   
       clearDiv.setAttribute("class", "clearfix");  
   
   var div = document.createElement("div");   
       div.setAttribute("class", "form-group");   
       div.setAttribute("id", "versionDiv"+numVersion);   
   
   
   var phoneInput = document.createElement("select");   
    phoneInput.setAttribute("name", "version"+newVersion);   
    phoneInput.setAttribute("id", "version"+newVersion);   
    phoneInput.setAttribute("class", "form-control");   
   var option1 = document.createElement("option");   
    option1.setAttribute("value", "Clean");   
   var text = document.createTextNode("Clean");   
        option1.appendChild(text);   
        phoneInput.appendChild(option1);
   
   var option1 = document.createElement("option");   
    option1.setAttribute("value", "Instrumental");   
   var text = document.createTextNode("Instrumental");   
        option1.appendChild(text);
        phoneInput.appendChild(option1); 
   
   var option1 = document.createElement("option");
          option1.setAttribute("value", "Acapella");   
   var text = document.createTextNode("Acapella");   
        option1.appendChild(text);   
        phoneInput.appendChild(option1);   
   var option1 = document.createElement("option");   
    option1.setAttribute("value", "Dirty");   
   var text = document.createTextNode("Dirty");   
        option1.appendChild(text);   
        phoneInput.appendChild(option1);
   
   document.getElementById('uptrk').appendChild(clearDiv);  
   div.appendChild(phoneInput);      
   // image
   
   var imgDiv = document.createElement("div");   
       imgDiv.setAttribute("class", "form-group col-sm-7");   
       imgDiv.setAttribute("id", "imgDiv"+numVersion);  
   var imgLabel = document.createElement("label");   
      imgLabel.setAttribute("class", "btn nopadding text-left");   
      
   var img = document.createElement("img");   
      img.setAttribute("class", "img-responsive");   
      img.setAttribute("src", filePath);  
   
   var fileInput = document.createElement("input");
   
      fileInput.setAttribute("type", "file");
   
      fileInput.setAttribute("name", "amr"+newVersion);
   
      fileInput.setAttribute("class", "amrFile");
      imgLabel.appendChild(img);     
   
      imgLabel.appendChild(fileInput);  
   
      imgDiv.appendChild(imgLabel);    
   
      document.getElementById('uptrk').appendChild(div);      
      document.getElementById('uptrk').appendChild(imgDiv);  
   
   document.getElementById('numVersion').value = parseInt(numVersion)+1;   
   }
   
   }
   
   // Wait for the DOM to be ready
   
   $(function() {
   
   $("#addTrack").validate();
   
   $("#artWork").rules("add", {   
        required:true,   
        messages: {   
               required: "Please enter Artwork"   
        }
   
     });   
   
   $("#artist").rules("add", {
           required:true,
           messages: {
   
               required: "Please enter artist name"   
        }   
     });
   
   $("#title").rules("add", {   
        required:true,   
        messages: {   
               required: "Please enter title"   
        }
   
     });
   
   
   $("#producer").rules("add", {
   
        required:true,   
        messages: {
   
               required: "Please enter producer"   
        }
   
     });
   $("#trackTime").rules("add", {   
        required:true,   
        messages: {   
               required: "Please enter track time"   
        }
   
     });
      
   $("#bpm").rules("add", {   
        required:true,   
        messages: {   
               required: "Please enter bpm"   
        }   
     });  
     $("#album").rules("add", {   
        required:true,   
        messages: {   
               required: "Please enter album"   
        }   
     });   
     
    //     $("#website").rules("add", {   
    //     required:true,   
    //     messages: {
   
    //           required: "Please enter website"   
    //     }   
    //  });
   
       $("#agree").rules("add", {
   
        required:true,
   
        messages: {
   
               required: "Please agree"   
        }   
     });  
   });
   
   
   
</script>
<script>
   function filePreview(input) {   
   if (input.files && input.files[0]) {   
       var reader = new FileReader();   
       reader.onload = function (e) {   
           //$('#artWork + img').remove();
   
          // $('#artWork').after('<img src="'+e.target.result+'" width="450" height="300"/>');
          document.getElementById('previewImg').style.width = '199px';   
          document.getElementById('previewImg').style.height = '199px';   
          document.getElementById('previewImg').src = e.target.result;
   
           //$('#uploadForm + embed').remove();
   
           //$('#uploadForm').after('<embed src="'+e.target.result+'" width="450" height="300">');
   
       }
   
       reader.readAsDataURL(input.files[0]);
   
   }
   }
   $("#artWork").change(function () {
   filePreview(this);
   
   });
   
</script>
@endsection

