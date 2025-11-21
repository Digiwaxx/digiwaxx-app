

@extends('layouts.client_dashboard')
@section('content')
<section class="main-dash">
   <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
   <div class="dash-container">
      <div class="container">
         <div class="row">
            <div class="col-xl-9 col-12">
               <div class="dash-heading">
                  <h2>Submitted Track Versions</h2>
               </div>
               <div class="tabs-section">
                  <!-- START MIDDLE BLOCK -->
                     <?php if(isset($alert_class)) 
                        { ?>
                     <div class="<?php echo $alert_class; ?>">
                        <p><?php echo $alert_message; ?></p>
                     </div>
                     <?php } // print_r($formData); ?>
                     <div class="mtk-blk f-block">
                        <div class="stk-btn clearfix">
                           
                        </div>
                        <!-- eof fby-blk -->
                        <div style="clear:both;"></div>
                        <style>
                           th { background:#B32F85; } 
                        </style>
                        <div class="mtk-list mCustomScrollbar">
                    <div class="row">
                        <?php
                        if ($result['numRows'] > 0) { ?>
                            <div class="col-xs-12 col-sm-12">
                                <div class="col-xs-3 col-sm-3">
                                    <?php
                                    if (!empty($result['data'][0]->pCloudFileID)) {
                                        $imgSrc = url('/pCloudImgDownload.php?fileID=' . $result['data'][0]->pCloudFileID);
                                    } else if (strlen($result['data'][0]->imgpage) > 4) {
                                        $imgSrc = asset("ImagesUp/" . $result['data'][0]->imgpage);
                                    } else {
                                        $imgSrc = asset("assets/img/upload-artwork.jpg");
                                    } ?>
                                    <img src="<?php echo $imgSrc; ?>" width="200" height="200">
                                </div>

                                <div class="col-xs-9 col-sm-9">
                                    <div class="profile-user-info profile-user-info-striped">
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Track Title </div>
                                            <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->title); ?>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Submitted By</div>
                                            <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->uname); ?>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Artist Name</div>
                                            <div class="profile-info-value">
                                                <?php
                                                if (!empty($result['data'][0]->artist)) {
                                                    echo urldecode($result['data'][0]->artist);
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Contact Email </div>
                                            <div class="profile-info-value">
                                                <?php $contact_email = $result['data'][0]->contact_email; ?>
                                                <?php if (!empty($contact_email)) { ?>
                                                    <a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a>
                                                <?php } else {
                                                    echo "-";
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-24"></div>
                                </div>

                                <div style="clear:both;"></div>
                                <h3 class="header smaller lighter blue">Submitted Track Versions</h3>

                                <table id="simple-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="center" width="100">S. No
                                            </th>
                                            <th class="detail-col" width="150">Version Name</th>
                                            <th class="detail-col">Audio File</th>                                            
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $version_ids = [];
                                        if (!empty($result['data'])) {
                                            foreach ($result['data'] as $index => $item) {
                                                $version_ids[] = $item->submitted_version_id;
                                        ?>
                                                <tr id="row-<?php echo $item->submitted_version_id; ?>">
                                                    <td class="center"><?php echo $index + 1; ?></td>

                                                    <td><?php echo urldecode($item->version_name); ?></td>

                                                    <td>
                                                        <audio controls="" style="width:100%;">
                                                            <?php if (strpos($item->pcloud_fileId, '.mp3') !== false) { ?>
                                                                <source src="<?php echo asset("AUDIO/" . $item->pcloud_fileId); ?>">
                                                            <?php
                                                            } else {
                                                                $fileid = (int)$item->pcloud_fileId;
                                                                $getlink = '';
                                                                if (!empty($fileid)) {
                                                                    $getlink = url('download.php?fileID=' . $fileid);
                                                                } ?>
                                                                <source src="<?php echo $getlink; ?>">
                                                            <?php
                                                            } ?>
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="4" class="center">No new submitted versions available</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div style="clear:both;"></div>
                            <script>
                                const versionIds = <?php echo json_encode($version_ids); ?>;
                                const client_name = "<?php echo urldecode($result['data'][0]->uname); ?>";
                                const contact_email = "<?php echo $contact_email; ?>";
                                const track_name = "<?php echo urldecode($result['data'][0]->title); ?>";
                                const artist = "<?php echo urldecode($result['data'][0]->artist); ?>";
                            </script>
                        <?php
                        } else {
                            header("location: " . url("admin/submitted_tracks_versions"));
                        }
                        ?>
                    </div>
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

<script>

function goToPage(page, pid){
   var param = '?';
   window.location = page + param + "page=" + pid;
}

</script>
@endsection

