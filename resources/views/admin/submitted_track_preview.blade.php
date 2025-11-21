<?php
if ($result['numRows'] > 0) { ?>

        <div class="col-xs-12 col-sm-12">
                <div class="col-xs-3 col-sm-3">
                        <?php //echo '<pre>'; //print_r($result['data'][0]); die();
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
                                        <div class="profile-info-name"> Client </div>

                                        <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->uname); ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name"> Artist </div>

                                        <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->artist); ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name"> Title </div>

                                        <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->title); ?> </div>
                                </div>


                                <?php
                                $albumType = '';
                                if ($result['data'][0]->albumType == 1) {
                                        $albumType = 'Single';
                                } else if ($result['data'][0]->albumType == 2) {
                                        $albumType = 'Album';
                                } else if ($result['data'][0]->albumType == 3) {
                                        $albumType = 'EP';
                                } else if ($result['data'][0]->albumType == 4) {
                                        $albumType = 'Mixtape';
                                }
                                ?>


                                <div class="profile-info-row">
                                        <div class="profile-info-name"> Release Type </div>

                                        <div class="profile-info-value">
                                                <?php echo $albumType; ?> </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name"> Album </div>

                                        <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->album); ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Time </div>

                                        <div class="profile-info-value">
                                                <?php echo $result['data'][0]->time; ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Bpm </div>

                                        <div class="profile-info-value">
                                                <?php echo $result['data'][0]->bpm; ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Genre </div>

                                        <div class="profile-info-value">
                                                <?php echo $result['data'][0]->genre; ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Contact Email </div>

                                        <div class="profile-info-value">
                                                <?php echo $result['data'][0]->contact_email; ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Sub Genre </div>

                                        <div class="profile-info-value">
                                                <?php echo $result['data'][0]->subGenre; ?> </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Link</div>

                                        <div class="profile-info-value"><?php echo urldecode($result['data'][0]->link); ?>
                                        </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Link 1</div>

                                        <div class="profile-info-value"><?php echo urldecode($result['data'][0]->link1); ?>
                                        </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Link 2</div>

                                        <div class="profile-info-value"><?php echo urldecode($result['data'][0]->link2); ?>
                                        </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name">Facebook</div>

                                        <div class="profile-info-value"><?php echo $result['data'][0]->facebookLink; ?>
                                        </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name">Twitter</div>

                                        <div class="profile-info-value"><?php echo $result['data'][0]->twitterLink; ?>
                                        </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name">Instagram</div>

                                        <div class="profile-info-value"><?php echo $result['data'][0]->instagramLink; ?>
                                        </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Apple Music</div>

                                        <div class="profile-info-value"><?php echo $result['data'][0]->applemusicLink; ?>
                                        </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Amazon Music</div>

                                        <div class="profile-info-value"><?php echo $result['data'][0]->amazonLink; ?>
                                        </div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name">Spotify</div>

                                        <div class="profile-info-value"><?php echo $result['data'][0]->spotifyLink; ?>
                                        </div>
                                </div>

                                <div class="profile-info-row">
                                        <div class="profile-info-name"> Producers </div>
                                        <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->producers); ?></div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name"> More Info </div>
                                        <div class="profile-info-value">
                                                <?php echo urldecode($result['data'][0]->moreinfo); ?></div>
                                </div>
                                <div class="profile-info-row">
                                        <div class="profile-info-name"> Release Date </div>
                                        <div class="profile-info-value">
                                                <?php $dt = $result['data'][0]->releasedate;
                                                if (strcmp($dt, '0000-00-00 00:00:00') != 0) {
                                                        $dt = explode(' ', $dt);
                                                        $dt = explode('-', $dt[0]);
                                                        echo $dt[1] . '-' . $dt[2] . '-' . $dt[0];
                                                } ?>
                                        </div>
                                </div>
                        </div>
                        <div class="space-24"></div>
                </div>

                <div style="clear:both;"></div>
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
                                <?php if (strlen($result['data'][0]->amr1) > 4) { ?>
                                        <tr>
                                                <td class="center">1</td>
                                                <td class="left"><?php echo $result['data'][0]->version1; ?></td>
                                                <?php // echo $result['data'][0]->amr1;
                                                ?>
                                                <td>
                                                        <audio controls="" style="width:100%;">
                                                                <?php if (strpos($result['data'][0]->amr1, '.mp3') !== false) { ?>
                                                                        <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr1); ?>">
                                                                <?php
                                                                } else {
                                                                        $fileid = (int)$result['data'][0]->amr1;
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
                                } ?>
                                <?php if (strlen($result['data'][0]->amr2) > 4) { ?>
                                        <tr>
                                                <td class="center">2</td>
                                                <td class="left"><?php echo $result['data'][0]->version2; ?></td>
                                                <?php // echo $result['data'][0]->amr2;
                                                ?>
                                                <td>
                                                        <audio controls="" style="width:100%;">
                                                                <?php if (strpos($result['data'][0]->amr2, '.mp3') !== false) { ?>
                                                                        <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr2); ?>">
                                                                <?php
                                                                } else {
                                                                        $fileid = (int)$result['data'][0]->amr2;
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
                                } ?>
                                <?php if (strlen($result['data'][0]->amr3) > 4) { ?>
                                        <tr>
                                                <td class="center">3</td>
                                                <td class="left"><?php echo $result['data'][0]->version3; ?></td>
                                                <?php // echo $result['data'][0]->amr3;
                                                ?>
                                                <td>
                                                        <audio controls="" style="width:100%;">
                                                                <?php if (strpos($result['data'][0]->amr3, '.mp3') !== false) { ?>
                                                                        <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr3); ?>">
                                                                <?php
                                                                } else {
                                                                        $fileid = (int)$result['data'][0]->amr3;
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
                                } ?>
                                <?php if (strlen($result['data'][0]->amr4) > 4) { ?>
                                        <tr>
                                                <td class="center">4</td>
                                                <td class="left"><?php echo $result['data'][0]->version4; ?></td>
                                                <?php // echo $result['data'][0]->amr4;
                                                ?>
                                                <td>
                                                        <audio controls="" style="width:100%;">
                                                                <?php if (strpos($result['data'][0]->amr4, '.mp3') !== false) { ?>
                                                                        <source src="<?php echo asset("AUDIO/" . $result['data'][0]->amr4); ?>">
                                                                <?php
                                                                } else {
                                                                        $fileid = (int)$result['data'][0]->amr4;
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
                                } ?>
                        </tbody>
                </table>
        </div>
        <div style="clear:both;"></div>
<?php
} else {
        echo '<h3>No data found.</h3>';
}
?>