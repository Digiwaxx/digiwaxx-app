@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {}
            </script>
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo url("admin/submitted_tracks_versions"); ?>">
                        <i class="ace-icon fa fa-list list-icon"></i>
                        Submitted Tracks Versions</a>
                </li>
                <li class="active">Edit Track Versions</li>
            </ul>
            <!-- /.breadcrumb -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="header smaller lighter">
                        Track Information
                    </h3>
                    <!-- PAGE CONTENT BEGINS -->
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
                                <div class="mb-2" style="text-align: right;">
                                    <button class="btn btn-success btn-sm" onclick="approveAllVersions()">Approve All</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteAllVersions()">Delete All</button>
                                </div>
                                <h3 class="header smaller lighter blue">Submitted Track Versions</h3>

                                <table id="simple-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="center" width="100">S. No
                                            </th>
                                            <th class="detail-col" width="150">Version Name</th>
                                            <th class="detail-col">Audio File</th>
                                            <th class="center" width="100">Actions</th>
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
                                                    <td>
                                                        <button class="btn btn-xs btn-success" onclick="approveVersion(<?php echo $item->submitted_version_id;  ?>, 'approve-version')">
                                                            <i class="ace-icon fa fa-check bigger-120"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-danger" onclick="deleteVersion(<?php echo $item->submitted_version_id;  ?>, 'delete-version')">
                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                        </button>
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
            </div>
        </div>
    </div>
</div>

<script>
    function approveVersion(id) {
        if (!confirm("Are you sure you want to approve this version?")) {
            return;
        }
        $('.processing_loader_gif').show();
        $.ajax({
            url: '{{ route("approveVersion") }}',
            method: 'POST',
            data: {
                id: id,
                client_name: client_name,
                contact_email: contact_email,
                track_name: track_name,
                artist: artist,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Approval failed. Please try again later.');
            },
            complete: function() {
                $('.processing_loader_gif').hide();
            }
        });
    }

    function approveAllVersions() {
        if (!confirm("Are you sure you want to approve all versions?")) return;
        $('.processing_loader_gif').show();

        const ids = versionIds;

        $.ajax({
            url: '{{ route("approveAllVersions") }}',
            method: 'POST',
            data: {
                ids: ids,
                client_name: client_name,
                contact_email: contact_email,
                track_name: track_name,
                artist: artist,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Approval failed. Please try again later.');
            },
            complete: function() {
                $('.processing_loader_gif').hide();
            }
        });
    }

    function deleteVersion(id) {
        if (!confirm("Are you sure you want to delete this version?")) {
            return;
        }
        $('.processing_loader_gif').show();
        $.ajax({
            url: '{{ route("deleteVersion") }}',
            method: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Deletion failed. Please try again later.');
            },
            complete: function() {
                $('.processing_loader_gif').hide();
            }
        });
    }

    function deleteAllVersions() {
        if (!confirm("Are you sure you want to delete all versions?")) return;
        $('.processing_loader_gif').show();

        const ids = versionIds;

        $.ajax({
            url: '{{ route("deleteAllVersions") }}',
            method: 'POST',
            data: {
                ids: ids,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Deletion failed. Please try again later.');
            },
            complete: function() {
                $('.processing_loader_gif').hide();
            }
        });
    }
</script>

@endsection