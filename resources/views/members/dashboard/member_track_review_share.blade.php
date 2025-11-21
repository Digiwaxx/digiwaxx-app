@extends('layouts.member_dashboard')
@section('content')


<style>


#mtrs_submit:hover{
  /* background-color: black; */
  color:grey;
}

.download_link, .download_link:hover
{
  color: #FFF;
  font-weight: bold;
  display: block;
  margin-top: 6px;
  width:111px;
}
#form-toggle
{
  cursor: pointer;
}
h2.logo_headings {
    width: 100%;
    margin-bottom: 10px;
}

.col-auto {
    margin: 6px 10px;
    /*margin:2px;*/
}
.col-auto img {
    height: 55px;
    width: auto;
}


.logos {
	display: flex;
	flex-wrap: wrap;
	margin: 0;
	margin-bottom: 15px;
	justify-content: flex-start;
}
</style>

<?php  //pArr($mp3s);die();

// if($_SERVER['REMOTE_ADDR'] = '223.178.213.145'){

// pArr($mp3s);
// die();
// }

?>

	<section class="main-dash">
		@include('layouts.include.sidebar-left')

        <?php
            $link_text = 'UNLOCK DOWNLOAD';
            $member_session_pkg = Session::get('memberPackage');
            if(isset($member_session_pkg) && $member_session_pkg > 2)
            {
            $link_text = 'WRITE A REVIEW';
            }
            ?>
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            
              <div class="tabs-section">

            <!-- START MIDDLE BLOCK -->
            <div class="col-lg-12 col-md-12">
    <div class="mem-dblk f-block" style="margin-bottom:40px;">
        <h1>DOWNLOAD TRACK</h1>
        <div class="trk-info-blk-share-review">
            <div class="row">
                <div class="col-md-4 col-sm-5 col-xs-12">
                    <?php
                     if(!empty($tracks['data'][0]->pCloudFileID)){
                          $img= url('/pCloudImgDownload.php?fileID='.$tracks['data'][0]->pCloudFileID);
                    }
                    else if (isset($tracks['data'][0]->thumb) && strlen($tracks['data'][0]->thumb) > 4 && file_exists(base_path('thumbs/'.$tracks['data'][0]->thumb))) {
                        $img = asset('thumbs/' . $tracks['data'][0]->thumb);
                    } else if (isset($tracks['data'][0]->imgpage) && strlen($tracks['data'][0]->imgpage) > 4 && file_exists(base_path('ImagesUp/'.$tracks['data'][0]->imgpage))) {
                        $img = asset('ImagesUp/' . $tracks['data'][0]->imgpage);
                    } else {
                        $img = asset('public/images/noimage-avl.jpg');
                    } 
                    ?>
                    <img src="<?php echo $img; ?>" class="img-responsive">
                </div>
                <div class="col-md-8 col-sm-7 col-xs-12">
                    <?php if (isset($alert_class)) { ?>
                        <div class="<?php echo $alert_class; ?>">
                            <p><?php echo $alert_message; ?></p>
                        </div>
                    <?php } // print_r($formData); 
                    ?>
                    <h1>TRACK INFO</h1>
                    <div class="trk-det">
                                     <?php
                                    
                                     if(trim($tracks['data'][0]->artist) !=""){ ?>
                                    <p class="t1"><label>Artist: </label> <span> <?php echo urldecode($tracks['data'][0]->artist); ?> </span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->title) !=""){ ?>
                                    <p class="t1"><label>Title: </label> <span><?php echo urldecode($tracks['data'][0]->title); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->producer) !=""){ ?>
                                    <p class="t1"><label>Producer: </label> <span><?php echo urldecode($tracks['data'][0]->producer); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->writer) !=""){ ?>
                                    <p class="t1"><label>Writer: </label> <span><?php echo urldecode($tracks['data'][0]->writer); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->time) !=""){
                                        $trkTime = trim(urldecode($tracks['data'][0]->time));
                                        $trkTimeArr = explode(':', $trkTime);
                                        
                                        $hrs = '';
                                        $mins = '';
                                        $secs = '';
                                        if(count($trkTimeArr) > 0 && count($trkTimeArr) <=2){
                                            if(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] > 1){
                                                $mins = (int)$trkTimeArr[0].'mins';
                                            }elseif(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] == 1){
                                                $mins = (int)$trkTimeArr[0].'min';
                                            }
                                            
                                            if(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] > 1){
                                                $secs = (int)$trkTimeArr[1].'secs';
                                            }elseif(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] == 1){
                                                $secs = (int)$trkTimeArr[1].'sec';
                                            }
                                        }elseif(count($trkTimeArr) > 0 && count($trkTimeArr) <= 3){
                                            if(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] > 1){
                                                $hrs = (int)$trkTimeArr[0].'hrs';
                                            }elseif(isset($trkTimeArr[0]) && (int)$trkTimeArr[0] == 1){
                                                $hrs = (int)$trkTimeArr[0].'hr';
                                            }
                                            
                                            if(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] > 1){
                                                $mins = (int)$trkTimeArr[1].'mins';
                                            }elseif(isset($trkTimeArr[1]) && (int)$trkTimeArr[1] == 1){
                                                $mins = (int)$trkTimeArr[1].'min';
                                            }
                                            
                                            if(isset($trkTimeArr[2]) && (int)$trkTimeArr[2] > 1){
                                                $secs = (int)$trkTimeArr[2].'secs';
                                            }elseif(isset($trkTimeArr[2]) && (int)$trkTimeArr[2] == 1){
                                                $secs = (int)$trkTimeArr[2].'sec';
                                            }                                          
                                        }
                                        
                                        $displayTrkTime = $hrs.' '.$mins.' '.$secs;
                                    ?>
                                    <p class="t1"><label>Time: </label> <span><?php echo $displayTrkTime; ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->label) !=""){ ?>
                                    <p class="t1"><label>Label </label> <span><?php echo urldecode($tracks['data'][0]->label); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->album) !=""){ ?>
                                    <p class="t1"><label>Album: </label> <span><?php echo urldecode($tracks['data'][0]->album); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->notes) !=""){ ?>
                                    <p class="t1"><label>Caption: </label> <span><?php echo urldecode($tracks['data'][0]->notes); ?></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->link) !=""){ ?>
                                    <p class="t1"><label>Web Link: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->link); ?>"><?php echo urldecode($tracks['data'][0]->link); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->link1) !=""){ ?>
                                    <p class="t1"><label>Web Link1: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->link1); ?>"><?php echo urldecode($tracks['data'][0]->link1); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->link2) !=""){ ?>
                                    <p class="t1"><label>Web Link2: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->link2); ?>"><?php echo urldecode($tracks['data'][0]->link2); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->videoURL) !=""){ ?>
                                    <p class="t1"><label>Video: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->videoURL); ?>"><?php echo urldecode($tracks['data'][0]->videoURL); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->facebookLink) !=""){ ?>
                                    <p class="t1"><label>Facebook: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->facebookLink); ?>"><?php echo urldecode($tracks['data'][0]->facebookLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->twitterLink) !=""){ ?>
                                    <p class="t1"><label>Twitter: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->twitterLink); ?>"><?php echo urldecode($tracks['data'][0]->twitterLink); ?></a> </span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->instagramLink) !=""){ ?>
                                    <p class="t1"><label>Instagram: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->instagramLink); ?>"><?php echo urldecode($tracks['data'][0]->instagramLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->tiktokLink) !=""){ ?>
                                    <p class="t1"><label>Tik Tok: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->tiktokLink); ?>"><?php echo urldecode($tracks['data'][0]->tiktokLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->snapchatLink) !=""){ ?>
                                    <p class="t1"><label>Snapchat: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->snapchatLink); ?>"><?php echo urldecode($tracks['data'][0]->snapchatLink); ?></a></span></p>
                                    <?php } ?>
                                    <?php if(trim($tracks['data'][0]->othersLink) !=""){ ?>
                                    <p class="t1"><label>Others: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->othersLink); ?>"><?php echo urldecode($tracks['data'][0]->othersLink); ?></a></span></p>
                                    <?php } ?>
                                    <p class="t1"><label>Member Points: </label> <span>1 point</span></p>
                                 </div>
                                 <!-- trk-det -->
                </div>
            </div>
           
            
            
            
            
            
            
            
            
            
              <div class="rew-trks">
                <h2>
                    ALL MP3 FILES are zipped & encoded at 192kbps/44.100 kHz/Stereo. Please do not hotlink files.
                </h2>
            <div class="rew-all-trks">    
                <?php $t = 0;
                //($tracks['data'][0]->madeAvailable);
                $myarray1 = array();
                $myarray2 = array();
                $name_zip_arr=array();
            
                if(!empty($mp3s)){
                    
                foreach ($mp3s as $value){
                    foreach ($value['data'] as $mp3) { 
                        
                        
                        // echo '<pre>'; print_r($mp3); echo '</pre>';
                        // die();
        
        
                        if(!empty($_COOKIE['memberPackage'])){
    
                            $memberPackage = $_COOKIE['memberPackage'];
                        }
                        else{
                
                            $memberPackage = '';
                
                        }
                        
                        if ($memberPackage < 2) {
                           
                            // if($mp3->preview==1) {
                    ?>
                            <div class="rtrk-item">
                                <p><?php if(!empty($mp3->title)) echo urldecode($mp3->title); else echo ''; ?> <?php if(!empty($mp3->version)) echo urldecode($mp3->version); else echo ''; ?>

                                    <?php 
                                    if(!empty($mp3->location)) {
                                        
                                        $string='';
                                        
                                        if(!empty($mp3->title)){
                                            $string .=urldecode($mp3->title);
                                            
                                        }
                                        
                                        if(!empty($mp3->version)){
                                            $string .=urldecode($mp3->version);
                                        }
                                        
                                        if(!empty($string)){
                                        
                                            // $string=urldecode($mp3->title);
                                             $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
                                             $down_title= preg_replace('/[^A-Za-z0-9\-]/', '-', $string);
                                        }else{
                                            $down_title="trackdownload";
                                        }
                                        
                                         
                                         
                                         $name_zip='';
                                         if(!empty($mp3->title)){
                                             $name_zip .=urldecode($mp3->title);
                                             
                                         }
                                         if (!empty($mp3->version)){
                                             $name_zip .=urldecode($mp3->version);
                                         }
                                         
                                         if(!empty($name_zip)){
                                            //  $zip_string = str_replace(' ', '-', $name_zip);
                                            $zip_string=preg_replace('/[\s$@_*]+/', '',$name_zip);
                                              $zip_string_name= preg_replace('/[^A-Za-z0-9\-]/', '-',$zip_string );
                                             
                                             
                                             if(!in_array($zip_string_name,$name_zip_arr)){
                                                 $name_zip_arr[]=$zip_string_name;
                                             }
                                             else{
                                                  $name_zip_arr[]=$zip_string_name.$t;
                                             }
                                              
                                         }
                                         else{
                                             $name_zip_arr[]="trackszipdownload".$t;
                                         }
                                         
                                        
                                         
                                         
                                        
                                           


                                        if (strpos($mp3->location, '.mp3') !== false) { ?>
                                            <a class="download_link d1" href="<?php echo url('Download_member_track?track=' . $mp3->location . '&mp3Id=' . $mp3->id . '&trackId=' . $_GET['tid'] .'&title='.$down_title); ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                        <?php 
                                            $myarray1[] = $mp3->location;
                                            
                                        } else { $fileid = (int)$mp3->location; $getlink = '';
                                            if(!empty($fileid)){ $getlink = url('Download_member_track?track='.$fileid.'&mp3Id='.$mp3->id.'&trackId='.$_GET['tid'].'&pcloud=true&title=' .$down_title); } ?>
                                            <a class="download_link d2" href="<?php echo $getlink; ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                        <?php 
                                            $myarray2[] = $mp3->location;
                                        } 
                                        
                                    }?>
                               
                                </p>
                                
                                <audio controls="" style="width:100%;">
                                    <?php 
                                    

                                    // if(!empty($tracks['data'][0]->version)) {


                                        if (strpos($mp3->location, '.mp3') !== false) { ?>
                                            <source src="<?php echo asset('AUDIO/'.$mp3->location); ?>" type="audio/mp3">
                                        <?php } else { $fileid = (int)$mp3->location; $getlink = ''; 
                                            if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                            <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                        <?php }


                                    //} ?>

                                  
                                    Your browser does not support the audio element.
                                </audio>
                                
                            </div><!-- eof rtrk-item -->
                        <?php
                        } else if ($memberPackage >= 2) { ?>
                            <div class="rtrk-item">
                                <p><?php if($mp3->title) echo urldecode($mp3->title); else echo ''; ?> <?php echo urldecode($mp3->version); ?>
                                 <?php    $string=urldecode($mp3->title);
                                         $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
                                         $down_title= preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                                         
                                         $name_zip='';
                                         if(!empty($mp3->title)){
                                             $name_zip .=urldecode($mp3->title);
                                             
                                         }
                                         if (!empty($mp3->version)){
                                             $name_zip .=urldecode($mp3->version);
                                         }
                                         
                                         if(!empty($name_zip)){
                                             $zip_string = str_replace(' ', '', $name_zip);
                                              $zip_string_name= preg_replace('/[^A-Za-z0-9\-]/', '', $zip_string);
                                             
                                              $name_zip_arr[]=$zip_string_name;
                                         }
                                         else{
                                             $name_zip_arr[]="trackszipdownload";
                                         }?>
                                    <?php if (strpos($mp3->location, '.mp3') !== false) { ?>
                                        <a href="<?php echo url('Download_member_track?track=' . $mp3->location . '&mp3Id=' . $mp3->id . '&trackId=' . $_GET['tid'] .'&title='.$down_title); ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a></p>
                                    <?php 
                                        $myarray1[] = $mp3->location;
                                        
                                    } else { $fileid = (int)$mp3->location; $getlink = '';
                                        if(!empty($fileid)){ $getlink = url('Download_member_track?track='.$fileid.'&mp3Id='.$mp3->id.'&trackId='.$_GET['tid'].'&pcloud=true&title=' .$down_title); } ?>
                                        <a class="download_link d3" href="<?php echo $getlink; ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                    <?php 
                                        $myarray2[] = $mp3['data'][0]->location;
                                    } ?>
                                    
                                <audio controls="" style="width:100%;">
                                    <?php if (strpos($mp3->location, '.mp3') !== false) { ?>
                                        <source src="<?php echo asset('AUDIO/'.$mp3->location); ?>" type="audio/mp3">
                                    <?php } else { $fileid = (int)$mp3->location; $getlink = ''; 
                                        if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                        <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                    <?php } ?>
                                    Your browser does not support the audio element.
                                </audio>
                                
                            </div><!-- eof rtrk-item -->
                        <?php } else { ?>
                            <div class="rtrk-item">
                                <p><?php if($mp3->title) echo urldecode($mp3->title); else echo ''; ?> <?php echo urldecode($mp3->version); ?>
                                <?php
                                     $string=urldecode($mp3->title);
                                         $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
                                         $down_title= preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                                         
                                         $name_zip='';
                                         if(!empty($mp3->title)){
                                             $name_zip .=urldecode($mp3->title);
                                             
                                         }
                                         if (!empty($mp3->version)){
                                             $name_zip .=urldecode($mp3->version);
                                         }
                                         
                                         if(!empty($name_zip)){
                                             $zip_string = str_replace(' ', '', $name_zip);
                                              $zip_string_name= preg_replace('/[^A-Za-z0-9\-]/', '', $zip_string);
                                             
                                              $name_zip_arr[]=$zip_string_name;
                                         }
                                         else{
                                             $name_zip_arr[]="trackszipdownload";
                                         }
                                
                                
                                ?>
                                    <?php if (strpos($mp3->location, '.mp3') !== false) { ?>
                                        <a href="<?php echo url('Download_member_track?track=' . $mp3->location . '&mp3Id=' . $mp3->id . '&trackId=' . $_GET['tid'] .'&title='.$down_title); ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a></p>
                                    <?php } else { $fileid = (int)$mp3->location; $getlink = '';
                                        if(!empty($fileid)){ $getlink = url('Download_member_track?track='.$fileid.'&mp3Id='.$mp3->id.'&trackId='.$_GET['tid'].'&pcloud=true&title=' .$down_title); } ?>
                                        <a class="download_link d4" href="<?php echo $getlink; ?>">DOWNLOAD <i class="fa fa-cloud-download"></i></a>
                                    <?php } ?> 
                                    
                                <audio controls="" style="width:100%;">
                                    <?php if (strpos($mp3->location, '.mp3') !== false) { ?>
                                        <source src="<?php echo asset('AUDIO/'.$mp3->location); ?>" type="audio/mp3">
                                    <?php } else { $fileid = (int)$mp3->location; $getlink = ''; 
                                        if(!empty($fileid)){ $getlink = url('download.php?fileID='.$fileid); } ?>
                                        <source src="<?php echo $getlink; ?>" type="audio/mp3">
                                    <?php } ?>
                                    Your browser does not support the audio element.
                                </audio>
                                
                            </div><!-- eof rtrk-item -->
                    <?php } $t++;
                    
                    }
                        
                    
                    }

                }
 
                $mstr1 = implode(',',$myarray1);
                $mstr2 = implode(',',$myarray2);
                $zip_names= implode(',',$name_zip_arr);
                
                
                
                 //pArr($mp3s);die();

// if($_SERVER['REMOTE_ADDR'] = '122.185.217.118'){

// pArr($name_zip_arr);

// }


                ?>
            </div><!-- end of rew-all-trks -->
                <div class="zipfiles">
                    <a style="background: #db378f;padding: 20px;width: 100%;text-align: center;font-size: 26px; font-family: 'Josefin Sans',sans-serif; color: #fff; font-weight: bold; line-height: 16px; text-transform: uppercase; margin-top: 10px; display: inline-block;" href="<?php echo url('zipdownload.php?in='.$mstr1.'&out='.$mstr2.'&trckname='.$zip_names); ?>" class="download_zip">Download Zip <i class="fa fa-cloud-download"></i></a>
                </div>
            </div><!-- eof rew-trks -->
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            <?php if(!isset($_GET['reviewupdated']) || $_GET['reviewupdated'] != '1' || (isset($_GET['reviewAdded']) && $_GET['reviewAdded'] == 1)){ ?>
            <form action="" method="post" id="request_additional_things">
                @csrf
                <input type="hidden" id="request_id" name="request_id" value="<?php echo $review['data'][0]->id; ?>"/>
                    <?php 
                    $req_array = array();
                    if(!empty($review['data'][0]->request_additional_things)){

                        $request_additional_things = $review['data'][0]->request_additional_things; 
                        $req_array = explode(',',$request_additional_things);

                    }
                   
                    ?>
                <div class="q-item">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <b class="q1">Request Additional Things from the Artist</b>
                            <div class="form-group">
                                <div class="radio dja">
                                    <label style="padding-left:0px;" class="checkboxclass">
                                    <input name="additional_things[]" class="optionsRadios1" <?Php if(in_array('Get a drop from the artist',$req_array)) { echo 'checked="checked"'; } ?> value="Get a drop from the artist" type="checkbox"> Get a drop from the artist</label>
                                </div>
                                <div class="radio dja">
                                    <label style="padding-left:0px;" class="checkboxclass">
                                    <input name="additional_things[]" class="optionsRadios1" <?Php if(in_array('Book the artist',$req_array)) { echo 'checked="checked"'; } ?> value="Book the artist" type="checkbox"> Book the artist</label>
                                </div>
                                <div class="radio dja">
                                    <label style="padding-left:0px;" class="checkboxclass">
                                    <input name="additional_things[]" class="optionsRadios1" <?Php if(in_array('Request higher format file',$req_array)) { echo 'checked="checked"'; } ?> value="Request higher format file" type="checkbox"> Request higher format file</label>
                                </div>
                                <div class="radio dja">
                                    <label style="padding-left:0px;" class="checkboxclass">
                                    <input name="additional_things[]" class="optionsRadios1" <?Php if(in_array('Request Vinyl',$req_array)) { echo 'checked="checked"'; } ?> value="Request Vinyl" type="checkbox"> Request Vinyl</label>
                                </div>
                                <div class="radio dja">
                                    <label style="padding-left:0px;" class="checkboxclass">
                                    <input name="additional_things[]" class="optionsRadios1" <?Php if(in_array('Request Merch',$req_array)) { echo 'checked="checked"'; } ?> value="Request Merch" type="checkbox"> Request Merch</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="q-item">
                    <div class="form-group">
                            <input name="additionalThingsReview" class="login_btn btn bsp btn-alt" id= "mtrs_submit" value="Request" type="submit">
                    </div>

                </div>
            </form>   
            <?php } ?>
            <div style="clear:both;"></div>
            <div class="rew-form">
                <h1 class="share_heading">Share on social media to earn more member points</h1>
                <?php
                $comment = urldecode($review['data'][0]->additionalcomments);
                ?>
                <style>
                    #twitter-widget-0 {
                        margin-bottom: -5px;
                    }
                </style>
                <p><?php echo $comment; ?></p>
                <div class="fb-share-button" data-href="<?php echo url("Member_track_review/share?tid=" . $_GET['tid']); ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
                <!--<a href="https://twitter.com/share" class="twitter-share-button" data-show-count="true">Tweet</a>
				<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>				
									-->
                <a href="https://twitter.com/share" class="twitter-share-button" data-text="<?php echo $comment; ?>" data-url="https://www.digiwaxx.io" data-show-count="false">Tweet</a>
                <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div><!-- eof rew-form -->
        </div><!-- eof trk-info-blk-share-review -->
    </div><!-- eof mem-dblk -->
</div><!-- eof middle block -->

              </div>
              <!---tab section end--->
                         
           </div>
         </div>
       </div>
     </div>
	 </section>

 
     <script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<script type="text/javascript">
    /* $(document).ready(function(){
    $('#share_button').click(function(e){
      e.preventDefault();
      FB.ui(
        {
          method: 'feed',
          name: 'This is the content of the "name" field.',
          link: 'URL which you would like to share ',
          picture: �URL of the image which is going to appear as thumbnail image in share dialogbox�,
          caption: 'Caption like which appear as title of the dialog box',
          description: 'Small description of the post',
          message: 'ram'
        }
      );
    });
  });*/
</script>

@endsection

