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

            <li>

                <a href="<?php echo url("admin/tracks"); ?>">

                <i class="ace-icon fa fa-list list-icon"></i>

                Tracks</a>

            </li>

            <li class="active">View Track</li>

        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->

    </div>



    <!-- /section:basics/content.breadcrumbs -->

    <div class="page-content">

<?php if(isset($alert_message)) { ?> <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div><?php } ?>

        <div class="space-10"></div>

        <div class="row">

            <div class="col-xs-12">

                <!-- PAGE CONTENT BEGINS -->

                <div class="row">

                    <div class="col-xs-12">

                    <?php $track = $tracks['data'][0]; ?>

                 

                    <div>

                    

                    

                    <div class="profile-user-info profile-user-info-striped">

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Type </div>



                                    <div class="profile-info-value">

                                        <span class="editable editable-click" id="username">Track</span>

                                    </div>

                                </div>



                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Client </div>



                                    <div class="profile-info-value">

                                        <?php echo urldecode($track->name); ?>

                                    </div>

                                </div>



                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Label /  Company </div>



                                    <div class="profile-info-value">

                                                      <?php echo urldecode($track->label); ?>

                                    </div>

                                </div>



                                <div class="profile-info-row">

                                    <div class="profile-info-name">Website</div>



                                    <div class="profile-info-value">

                                             <?php echo urldecode($track->link); ?>

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name">Website 1 </div>



                                    <div class="profile-info-value">

                                             <?php echo urldecode($track->link1); ?>

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name">Website 2 </div>



                                    <div class="profile-info-value">

                                             <?php echo urldecode($track->link2); ?>

                                    </div>

                                </div>



                                <div class="profile-info-row">

                                    <div class="profile-info-name"> More Info </div>



                                    <div class="profile-info-value">

                                        <?php echo urldecode($track->moreinfo); ?>

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Genre </div>



                                    <div class="profile-info-value">

                                        <?php echo urldecode($track->genre); ?>

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Sub Genre </div>



                                    <div class="profile-info-value">

                                        <?php echo urldecode($track->subGenre); ?>

                                    </div>

                                </div>



                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Available to Members </div>



                                    <div class="profile-info-value">

                                        <?php if($track->active==1) { 

                                    echo 'Yes';

                                     }  else {

                                     

                                     echo 'No';

                                      } ?>

                                    </div>

                                </div>

                                

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name">Reviewable </div>



                                    <div class="profile-info-value">

                                            <?php if($track->review==1) { echo 'Yes'; }

                                    else

                                    {

                                      echo 'No';

                                    } ?>

                                    </div>

                                </div>

                                

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Graphics Complete </div>



                                    <div class="profile-info-value">

                                    <?php if($track->graphicscomplete==1) { echo 'Yes'; }

                                    else { echo 'No'; } ?> 

                                   </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Artist </div>



                                    <div class="profile-info-value">

                                                <?php echo urldecode($track->artist); ?>

                                     </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Featured Artist-1 </div>



                                    <div class="profile-info-value">

                                            <?php echo urldecode($track->featured_artist_1); ?>

                                    </div>

                                </div>
                                
                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Featured Artist-2 </div>



                                    <div class="profile-info-value">

                                            <?php echo urldecode($track->featured_artist_2); ?>

                                    </div>

                                </div>
                                
                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Title </div>



                                    <div class="profile-info-value">

                                            <?php echo urldecode($track->title); ?>

                                    </div>

                                </div>

                                    

                                <?php 

                            $albumType = '';

                            if($track->albumType==1)

                            {

                              $albumType = 'Single';

                            }

                            else if($track->albumType==2)

                            {

                              $albumType = 'Album';

                            }

                            else if($track->albumType==3)

                            {

                              $albumType = 'EP';

                            }

                            else if($track->albumType==4)

                            {

                              $albumType = 'Mixtape';

                            }

                            

                            

                            

                            ?>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Album Type</div>



                                    <div class="profile-info-value">

                                         <?php echo $albumType; ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Album </div>



                                    <div class="profile-info-value">

                                         <?php echo urldecode($track->album); ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name">Time </div>



                                    <div class="profile-info-value">

                                         <?php echo urldecode($track->time); ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name">Bpm </div>



                                    <div class="profile-info-value">

                                         <?php echo $track->bpm; ?>

                                    </div>

                                </div>
                                
                                <div class="profile-info-row">

                                    <div class="profile-info-name">Key</div>



                                    <div class="profile-info-value">

                                         <?php echo $track->songkey; ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Video URL </div>
                                    <div class="profile-info-value">
                                        <?php echo urldecode($track->videoURL); ?>
                                    </div>
                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Facebook </div>



                                    <div class="profile-info-value">

                                        <?php echo $track->facebookLink; ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Twitter </div>



                                    <div class="profile-info-value">

                                        <?php echo $track->twitterLink; ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Instagram </div>



                                    <div class="profile-info-value">

                                        <?php echo $track->instagramLink; ?>

                                    </div>

                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name">Apple Music</div>
                                        <div class="profile-info-value">
                                            <a target="_blank" href="<?php echo $track->applemusicLink; ?>">
                                                <?php echo $track->applemusicLink; ?>
                                            </a>
                                        </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name">Amazon Music</div>

                                        <div class="profile-info-value">
                                            <a target="_blank" href="<?php echo $track->amazonLink; ?>">
                                                <?php echo $track->amazonLink; ?>
                                            </a>
                                        </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name">Spotify</div>
                                        <div class="profile-info-value">
                                            <a target="_blank" href="<?php echo $track->spotifyLink; ?>">
                                                <?php echo $track->spotifyLink; ?>
                                            </a>
                                        </div>
                                </div>

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Producers </div>



                                    <div class="profile-info-value">

                                        <?php echo urldecode($track->producer); ?>

                                    </div>

                                </div>
                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Writer </div>



                                    <div class="profile-info-value">

                                        <?php echo urldecode($track->writer); ?>

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> White Label  </div>



                                    <div class="profile-info-value">

                                      <?php if($track->whitelabel==1) { echo 'Yes'; } 

                                         else { echo 'No'; } ?>

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> E-mail Image </div>



                                    <div class="profile-info-value">
										<?php if(!empty($track->img) && file_exists(base_path('ImagesUp/' .$track->img))){ ?>
                                        <img src="<?php echo asset('ImagesUp/'.$track->img); ?>" width="80" alt="<?php echo urldecode($track->title); ?>" />
										<?php } ?>
                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Page Image </div>



                                    <div class="profile-info-value">
                                        <?php if(!empty($track->pCloudFileID)){
                                            $src=  url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID); ?>
                                             <img src="<?php echo $src; ?>" width="80" alt="<?php echo urldecode($track->title); ?>" />
                                        <?php }
										 else if(!empty($track->imgpage) && file_exists(base_path('ImagesUp/' .$track->imgpage))){ ?>
                                             <img src="<?php echo asset('ImagesUp/'.$track->imgpage); ?>" width="80" alt="<?php echo urldecode($track->title); ?>" />
										<?php } ?>
                                        

                                    </div>

                                </div>

                                

                                <div class="profile-info-row">

                                    <div class="profile-info-name"> Logos </div>



                                    <div class="profile-info-value">

                                        <?php

                                        

                                    if($logos['numRows']>0)

                                    {

                                    

                                        foreach($logos['data'] as $logo)

                                        {
                                        if(!empty($logo->pCloudFileID_logo)){
                                            $src_logo=  url('/pCloudImgDownload.php?fileID='.$logo->pCloudFileID_logo); ?>
                                              <img src="<?php echo $src_logo; ?>" alt="<?php echo urldecode($track->title); ?>" width="80" />
                                      <?php }
                                        
										else if(!empty($logo->img) && file_exists(base_path("Logos/".$logo->img))){
                                         ?>

                                         <img src="<?php echo asset("Logos/".$logo->img); ?>" alt="<?php echo urldecode($track->title); ?>" width="80"/>

                                         

                                         <?php
										}
                                        }

                                    

                                    }

                                        ?>

                                    </div>

                                </div>

                                <?php 
                                $embedlink = $track->embedvideoURL; 
                                if(!empty($embedlink) && preg_match('/<iframe .*?>/', $embedlink)){
                                ?>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Video Embed URL </div>
                                    <div class="profile-info-value">
                                        <?php echo urldecode($embedlink); ?>
                                    </div>
                                </div>
                                <?php 
                                } 

                                ?>
                                
                                
                    </div>

                    

                    
                    <div class="space-24"></div>

                    

                    <h3 class="header smaller lighter blue">Contact Details</h3>

                    

                    

                    <table id="simple-table" class="table  table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>

                                    Name

                                    </th>

                                    <th>Email</th>

                                    

                                    <th>Phone</th>
                                    
                                    <th>Relationship to artist</th>

                                </tr>

                            </thead>



                            <tbody>

                            

                                <tr>

                                    <td class="center"> 

                                       <?php echo $track->contact_name; ?>

                                    </td>



                                    <td class="left">

                                        <?php echo urldecode($track->contact_email); ?>

                                    </td>
                                    
                                    <td class="left">

                                        <?php echo $track->contact_phone; ?>

                                    </td>
                                    
                                        <td class="left">

                                        <?php echo $track->relationship_to_artist; ?>

                                    </td>

                
                           </tr>


                           </tbody>

                        </table>

                    <div class="space-24"></div>

                    

                    <h3 class="header smaller lighter blue">Audio Files</h3>

                    

                    

                    <table id="simple-table" class="table  table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th class="center" width="100">

                                        S. No

                                    </th>

                                    <th class="detail-col" width="150">Version</th>

                                    

                                    <th>Track</th>

                                </tr>

                            </thead>



                            <tbody>

                            <?php $i=1; foreach($audios['data'] as $audio) { ?>

                                <tr>

                                    <td class="center"> 

                                       <?php echo $i; ?>

                                    </td>



                                    <td class="left">

                                        <?php echo urldecode($audio->version); ?>

                                    </td>

                                    <?php $fileid = (int)$audio->location; ?>

                                    <td>

                                        <audio controls="" style="width:100%;">
                        <?php if (strpos($audio->location, '.mp3') !== false) { ?>
                            <source src="<?php echo asset('AUDIO/'.$audio->location); ?>" type="audio/mp3" id="audio-<?php echo $fileid; ?>"></source>
                        <?php } else { $getlink = ''; if(!empty($fileid)){ $getlink = asset('download.php?fileID='.$fileid); } ?>
                            <source src="<?php echo $getlink; ?>" type="audio/mp3" id="audio-<?php echo $fileid; ?>"></source>
                        <?php } ?>
                        Your browser does not support the audio element.

                        </audio>

                                    </td>

                                    

                                </tr>

                                <?php $i++; } ?>



                           </tbody>

                        </table>

                        

                        <a class="btn btn-primary" href="{{route('edit_track', ['id'=>Crypt::encryptString($track->id)]) }}">Manage Track</a>

                        

                    </div>

                    

                    

                        

                        

                        

                        

                    </div><!-- /.span -->

                </div><!-- /.row -->



                <div class="hr hr-18 dotted hr-double"></div>



            

                <!-- PAGE CONTENT ENDS -->

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.page-content -->

    

    <script>

    

    function removeAudio()

    {

    

     var divId = document.getElementById('divId').value;

    

    if(divId>1)

    {

     var divIdMinus = parseInt(divId)-1;

     document.getElementById('divId').value = divIdMinus;

     $("#html"+divId).remove(); 

     }

      

    }

    

    function moreAudio()

    {

    

    

    

     var divId = document.getElementById('divId').value;

     var divIdPlus = parseInt(divId)+1;

     document.getElementById('divId').value = divIdPlus;

     

      var parentDiv = document.createElement("div");

      parentDiv.setAttribute('id','html'+divIdPlus);

     

                          

      var smDiv1 = document.createElement("div");

      smDiv1.setAttribute('class','col-sm-6 form_group');

      

       var smDiv2 = document.createElement("div");

      smDiv2.setAttribute('class','col-sm-9');

      

      var label1 = document.createElement("label");

      label1.setAttribute('class','col-sm-3 control-label no-padding-right');

     

       var textnode1 = document.createTextNode("Version");

       label1.appendChild(textnode1);   

       

       

      var input1 = document.createElement("input");

      input1.setAttribute('type','text');

      input1.setAttribute('name','version'+divIdPlus);

      input1.setAttribute('id','version'+divIdPlus);

      input1.setAttribute('class','col-xs-10 col-sm-10');

      

      smDiv2.appendChild(input1);   

      smDiv1.appendChild(label1);    

      smDiv1.appendChild(smDiv2);

      

      

      

       var smDiv3 = document.createElement("div");

      smDiv3.setAttribute('class','col-sm-6 form_group');

      

       var smDiv4 = document.createElement("div");

      smDiv4.setAttribute('class','col-sm-9');

      

      var label2 = document.createElement("label");

      label2.setAttribute('class','col-sm-3 control-label no-padding-right');

     

       var textnode2 = document.createTextNode("File");

       label2.appendChild(textnode2);   

       

       

      var input2 = document.createElement("input");

      input2.setAttribute('type','file');

      input2.setAttribute('name','audio'+divIdPlus);

      input2.setAttribute('id','audio'+divIdPlus);

      input2.setAttribute('class','col-xs-10 col-sm-10');

      

      smDiv4.appendChild(input2);   

      smDiv3.appendChild(label2);    

      smDiv3.appendChild(smDiv4);  

      

      

      

     parentDiv.appendChild(smDiv1);   

     parentDiv.appendChild(smDiv3);   

     

     

     

      var clearboth = document.createElement("div");

      clearboth.setAttribute('class','clearDiv');

      document.getElementById('audioFiles').appendChild(clearboth);

     

     document.getElementById('audioFiles').appendChild(parentDiv);

     

     

    

      

       

      }  



    

    </script>

@endsection  