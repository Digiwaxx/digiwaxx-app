<ul class="rp-menu">
    <li><a href="<?php echo url("Member_dashboard_newest_tracks"); ?>"><i class="fa fa-music"></i>Tracks</a></li>
    <li class="msg-btn">
        <span class="tooltip"><i class="fa fa-envelope-o"></i>
            <span class="tooltiptext">Internal chat with users</span>
        </span>
        <a href="<?php echo url("Member_messages"); ?>"><i class="fa fa-envelope"></i> Messages
            <?php if ($numMessages > 0) { ?> <span class="bdg"><?php echo $numMessages; ?></span> <?php } ?>
        </a>
    </li>
    <li><a href="<?php echo url("Member_info"); ?>"><i class="fa fa-user"></i>My Info</a></li>
    <li>
        <span class="tooltip"><i class="fa fa-archive"></i>
            <span class="tooltiptext">My Archives</span>
        </span>
        <a href="<?php echo url("Member_tracks_archives"); ?>">
            Archives
        </a>
    </li>
    
    <li>
        <span class="tooltip"><i class="fa fa-archive"></i>
            <span class="tooltiptext">My Reviewed Archives</span>
        </span>
        <a href="<?php echo url("Member_track_own_archives"); ?>">
            My Crate
        </a>
    </li>
    
    <li>
        <span class="tooltip"><i class="fa fa-shopping-basket"></i>
            <span class="tooltiptext">Products to buy</span>
        </span>
        <a href="<?php echo url("Products"); ?>">Products</a></li>
    <li>
        <span class="tooltip"><i class="fa fa-truck"></i>
            <span class="tooltiptext">Product orders purchased by digicoins</span>
        </span>
        <a href="<?php echo url("Member_orders"); ?>">Orders</a></li>
    <li>
        <span class="tooltip"><i class="fa fa-database"></i>
            <span class="tooltiptext">Digicoins are points, earned by giving feedback to clients or purchased</span>
        </span>
        <a href="<?php echo url("Member_my_digicoins"); ?>">My Digicoins</a></li>
</ul>