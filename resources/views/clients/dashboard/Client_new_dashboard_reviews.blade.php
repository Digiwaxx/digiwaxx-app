<div class="widget-main no-padding">
    <div class="dialogs" style="max-height: 500px; overflow-y: auto;">
        <?php
        if ($tracksReviews['numRowsMonth']) {
            foreach ($tracksReviews['MonthData'] as $monthly_reviews) {
                // check profile image
                // if (!empty($weekly_reviews->pCloudFileID_mem_image)) {
                // 	$memberAvatar = url('/pCloudImgDownload.php?fileID=' . $weekly_reviews->pCloudFileID_mem_image);
                // } else {
                // 	$memberAvatar = url('/assets_admin/assets/avatars/avatar2.png');
                // }

                $addedDate = (new DateTime($monthly_reviews->added))->setTime(0, 0);
                $currentTime = (new DateTime())->setTime(0, 0);
                $daysDifference = $currentTime->diff($addedDate)->days;
                if ($daysDifference == 0) {
                    $timeAgo = 'Today';
                } elseif ($daysDifference == 1) {
                    $timeAgo = 'Yesterday';
                } else {
                    $timeAgo = $daysDifference . ' days ago';
                }


        ?>
                <div class="itemdiv dialogdiv">
                    <div class="user">
                        <img alt="Digiwaxx Logo" src="{{ asset('assets/img/profile-pic.png')}}" />
                    </div>

                    <div class="body">
                        <div class="time">
                            <i class="ace-icon fa fa-clock-o"></i>
                            <span class="green"><?php echo $timeAgo; ?></span>
                        </div>

                        <div class="text"><?php echo urldecode($monthly_reviews->additionalcomments); ?></div>
                        <div class="name"><span>DJ: </span>
                            <a target="_blank" href="{{ url('/Client_track_review_member?memberId=' . $monthly_reviews->member_id) }}"><?php echo $monthly_reviews->fname . ' ' . $monthly_reviews->lname; ?></a> | <span>Track: </span> <a target="_blank" href="{{ url('/Client_track_review?tId=' . $monthly_reviews->track_id) }}"><?php echo urldecode($monthly_reviews->title); ?></a>
                        </div>
                    </div>
                </div>
            <?php }
            if ($numPages > 1) {
            ?>
                <div class="row">
                    <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                        <?php if ($currentPageNo !== $numPages) {
                        ?>
                            <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void(0);" <?php if ($firstPageLink !== 'disabled') { ?> onclick="goToCommentsPage('<?php echo $currentPage; ?>','1')" <?php } ?>>
                                    << </a>
                            </li>
                            <li class="<?php echo $preLink; ?>"><a href="javascript:void(0);" <?php if ($firstPageLink !== 'disabled') { ?> onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>')" <?php } ?>>
                                    < </a>
                            </li>
                        <?php }

                        ?>
                        <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp; </li>
                        <?php if ($currentPageNo !== $numPages) {

                        ?>
                            <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>')"> > </a></li>
                            <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToCommentsPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            <?php }
        } else { ?>
            <div class="itemdiv dialogdiv">
                <div class="user">
                    <img alt="Digiwaxx Logo" src="{{ asset('assets/img/profile-pic.png')}}" />
                </div>
                <div class="body">
                    <div class="text">No reviews found on your submitted tracks for this month.</div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>