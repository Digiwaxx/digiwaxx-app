@extends('layouts.member_dashboard')
@section('content')
<title>Untitled Document</title>
<style>
.trk-info-blk .rew-trks .rtrk-item p a {
	font-family: "Josefin Sans",sans-serif;
	font-size: 14px;
	color: #00dbff;
	font-weight: 700;
	line-height: 16px;
	padding-left: 20px;
}
.trk-info-blk{
    height: 900px;
    overflow: hidden;

} 
.subs-message {
  margin-top: 60px;
 
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
    <div class="mem-dblk f-block">
        <h1>DOWNLOAD TRACK</h1>
        <div class="trk-info-blk" style="height:auto; overflow:hidden;">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                    <?php
                     if(!empty($tracks['data'][0]->pCloudFileID)){
                         $img= url('/pCloudImgDownload.php?fileID='.$tracks['data'][0]->pCloudFileID);
                    }
                    else if (isset($tracks['data'][0]->thumb) && strlen($tracks['data'][0]->thumb) > 4 && file_exists(base_path('thumbs/'.$tracks['data'][0]->thumb))) {
                        $img = asset('thumbs/' . $tracks['data'][0]->thumb);
                    } else if (strlen($tracks['data'][0]->imgpage) > 4 && file_exists(base_path('ImagesUp/'.$tracks['data'][0]->imgpage))) {
                        $img = asset('ImagesUp/' . $tracks['data'][0]->imgpage);
                    } else {
                        $img = asset('public/images/noimage-avl.jpg');
                    }

                    if(!empty($img)){


                        if (file_exists(base_path('ImagesUp/'.$tracks['data'][0]->imgpage))){
                                                                                  
                          $img_get = $img;  
                    
                        }
                       else if (file_exists(base_path('thumbs/'.$tracks['data'][0]->thumb))){
                                                                                    
                          $img_get = $img;  
                    
                        }
                        else{
                          $img_get = asset('public/images/noimage-avl.jpg'); 
                        }
                  
                      }
                  
                      else{
                  
                        $img_get = asset('public/images/noimage-avl.jpg');
                      }
                    ?>
                    <img src="<?php echo $img_get; ?>" class="img-responsive">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                    <h1>TRACK INFO</h1>
                    <div class="trk-det">
                        <p class="t1"><label>Artist: </label> <span> <?php echo urldecode($tracks['data'][0]->artist); ?> </span></p>
                        <p class="t1"><label>Title: </label> <span><?php echo urldecode($tracks['data'][0]->title); ?></span></p>
                        <p class="t1"><label>Producer: </label>
                            <span>
                                <?php if (isset($tracks['data'][0]->producer)) {
                                    echo urldecode($tracks['data'][0]->producer);
                                } ?>
                            </span>
                        </p>
                        <p class="t1"><label>Time: </label> <span><?php if($tracks['data'][0]->time)echo urldecode($tracks['data'][0]->time); else echo ''; ?></span></p>
                        <p class="t1"><label>Label </label> <span><?php echo urldecode($tracks['data'][0]->label); ?></span></p>
                        <p class="t1"><label>Album: </label> <span><?php echo urldecode($tracks['data'][0]->album); ?></span></p>
                        <p class="t1"><label>Link: </label> <span>
                                <a href="#"><?php if (isset($tracks['data'][0]->link)) {
                                                echo urldecode($tracks['data'][0]->link);
                                            } ?></a>
                            </span></p>
                        <p class="t1"><label>Video: </label><span>
                            <?php if (isset($tracks['data'][0]->videoURL)) {?>
                                       <a href="<?php echo urldecode($tracks['data'][0]->videoURL); ?>" target="_blank">
                                            <?php if (isset($tracks['data'][0]->videoURL)) {
                                                echo urldecode($tracks['data'][0]->videoURL);
                                            } ?>
                                      </a>  
                            <?php }?>  
                            </span></p>
							
						
						<?php if(trim($tracks['data'][0]->applemusicLink) !=""){ ?>
							<p class="t1"><label> Apple Music: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->applemusicLink); ?>"><?php echo urldecode($tracks['data'][0]->applemusicLink); ?></a></span></p>
						<?php } ?>
						
						<?php if(trim($tracks['data'][0]->amazonLink) !=""){ ?>
							<p class="t1"><label> Amazon: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->amazonLink); ?>"><?php echo urldecode($tracks['data'][0]->amazonLink); ?></a></span></p>
						<?php } ?>
						<?php if(trim($tracks['data'][0]->spotifyLink) !=""){ ?>
							<p class="t1"><label> Spotify: </label> <span><a target="_blank" href="<?php echo urldecode($tracks['data'][0]->spotifyLink); ?>"><?php echo urldecode($tracks['data'][0]->spotifyLink); ?></a></span></p>
						<?php } ?>
						
						
                        <!--  <p class="t1"><label>Label contact info: </label> <span>Matt Grant <a href="#">faithlovesexrecords@gmail.com</a></span></p>-->
                        <p class="t1"><label>Member Points: </label> <span>1 point</span></p>
                    </div><!-- trk-det -->
                </div>
            </div>
            
             <?php if(!empty($tracks['data'][0]->embedvideoURL) && preg_match('/<iframe .*?>/', $tracks['data'][0]->embedvideoURL)){ ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center mt-4">
                       <?php echo $tracks['data'][0]->embedvideoURL; ?>
                    </div>
                </div>
            </div>
            <?php } ?>           
       
                
            <?php if(!empty($member_package) && $member_package!==7){?>
            <div class="subs-message">
                <div class="alert alert-info">
                  <strong><a href="{{route('members_view_package')}}" class="alert-link">Buy</a></strong> a subscription to download music.
                </div>
            </div>    
            <?php 
            
            }else{
            ?>
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
                                         
                                        
                                         
                                    $destinationPath =  base_path('/AUDIO/');
                                    $checkFilePath = $destinationPath.$mp3->location;     
                                        
                                           


                                        if (strpos($mp3->location, '.mp3') !== false && file_exists($checkFilePath)) { ?>
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


                                        if (strpos($mp3->location, '.mp3') !== false && file_exists($checkFilePath)) { ?>
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
            <?php }?>
       
        </div><!-- eof trk-info-blk -->
    </div><!-- eof mem-dblk -->
</div><!-- eof middle block -->

              </div>
              <!---tab section end--->
                         
           </div>
         </div>
       </div>
     </div>
	 </section>

 
<!--removed scripts-->

@endsection

