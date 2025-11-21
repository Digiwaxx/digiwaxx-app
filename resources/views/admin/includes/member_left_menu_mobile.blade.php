<ul class="rp-menu">
    <li><a href="<?php echo url("Member_dashboard_all_tracks"); ?>"><i class="fa fa-music"></i>MY TRACKS</a></li>
    <li class="msg-btn">
        <a href="<?php echo url("Member_messages"); ?>"><i class="fa fa-envelope-o"></i> MESSAGES
            <?php if ($numMessages > 0) { ?> <span class="bdg"><?php echo $numMessages; ?></span> <?php } ?>
        </a>
    </li>
    <li><a href="<?php echo url("Member_info"); ?>"><i class="fa fa-info-circle"></i>MY INFO</a></li>
    <li>
        <a href="<?php echo url("Member_tracks_archives"); ?>">
            <i class="fa fa-info-circle"></i> ARCHIVES
        </a>
    </li>
    <li>
        <a href="<?php echo url("Member_track_own_archives"); ?>">
            <i class="fa fa-info-circle"></i>MY CRATE
        </a>
    </li>
    <li>
        <a href="<?php echo url("Products"); ?>"><i class="fa fa-shopping-basket"></i> PRODUCTS</a></li>
    <li>
        <a href="<?php echo url("Member_orders"); ?>"><i class="fa fa-truck"></i> ORDERS</a></li>
    <li>
        <a href="<?php echo url("Member_my_digicoins"); ?>"><i class="fa fa-database"></i> MY DIGICOINS</a>
    </li>
</ul>