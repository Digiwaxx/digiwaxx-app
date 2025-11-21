
<?php 

?>

<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="Static &amp; Dynamic Tables" />
	<link rel="icon" href="{{ asset('public/images/icon.png') }}" type="image/gif" sizes="16x16">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/bootstrap.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/font-awesome.css')}}" />
    <!-- page specific plugin styles -->
    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace-fonts.css')}}" />
    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('assets_admin/assets/css/ace.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
	<link rel="stylesheet" href="{{ asset('assets_admin/assets/css/custom_admin.css')}}" />
    
    
    <!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
    <!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    <script src="{{ asset('assets_admin/assets/js/ace-extra.js')}}"></script>
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
		<style>
		.processing_loader_gif {
            display: none;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            bottom:0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,.3);
            z-index: 9999;
            text-align: center;
        }
        
        .processing_loader_gif img {
            max-width: 80px;
            margin: auto;
            top: 45%;
            position: fixed;
        }
		</style>
        <link rel="stylesheet" href="{{ URL::to('public/assets/css/add_audio_files.css') }}">
        <link rel="stylesheet" href="{{ URL::to('public/assets/css/blueimp-gallery.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload.css') }}">
        <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload-ui.css') }}">
        <noscript>
            <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload-noscript.css') }}">
        </noscript>
        <noscript>
            <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload-ui-noscript.css') }}">
        </noscript>
        
        
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('public/css/jquery-ui.css') }}">
        <!-- Scripts -->
        <script src="{{ asset('public/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/js/jquery-ui.js') }}"></script>

</head>

<body class="no-skin">
    <?php error_reporting(0); ?>
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default">
        <script type="text/javascript">
            try {
                ace.settings.check('navbar', 'fixed')
            } catch (e) {}
        </script>
        <div class="navbar-container" id="navbar-container">
            <!-- #section:basics/sidebar.mobile.toggle -->
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- /section:basics/sidebar.mobile.toggle -->
            <div class="navbar-header pull-left">
                <?php 
    		         $get_logo='';
    		      
    		         $logo_details = DB::table('website_logo')->where('logo_id',1)->first();
    		         if(!empty($logo_details) && !empty($logo_details->pCloudFileID)){
    		           $get_logo = $logo_details->pCloudFileID;
    		         }
		         
		        ?> 
                <!-- #section:basics/navbar.layout.brand -->
                <a href="{{ route('admin_dashboard') }}" class="navbar-brand">  
                <?php if(!empty($get_logo)){?>
					       <img src="<?php echo url('/pCloudImgDownload.php?fileID='.$get_logo); ?>" class="img-admin-logo" style="max-width:200px">
				<?php	 }else{?>
					       <img src="{{ asset('assets/img/logo.png')}}" class="img-admin-logo" style="max-width:200px"/>   
				<?php    }?>
				    
                  
                </a>
                <!-- /section:basics/navbar.layout.brand -->
                <!-- #section:basics/navbar.toggle -->
                <!-- /section:basics/navbar.toggle -->
            </div>
            <!-- #section:basics/navbar.dropdown -->
            <input type="hidden" name="web_app_url" id="web_app_url" value="{{ env('APP_URL') }}">
            <div class="navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">
                    <!-- #section:basics/navbar.user_menu -->
                    <li class="">
                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            <span class="user-info user-info-d">
                                <small>Welcome,</small>
                                {{ $welcome_name }}
                            </span>
                            <i class="ace-icon fa fa-user user-info-icon-m"></i>
                            <i class="ace-icon fa fa-caret-down"></i>

                        </a>
                        <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-caret dropdown-close">
                            <!--<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>
								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>
								<li class="divider"></li>-->
                                <li class="user-info-m">
                                    <small>Welcome,</small>
                                    {{ $welcome_name }}
                                </li>
                            <li>
                                <a href="{{ route('admin_logout') }}">
                                    <i class="ace-icon fa fa-power-off"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- /section:basics/navbar.user_menu -->
                </ul>
            </div>
            <!-- /section:basics/navbar.dropdown -->
        </div><!-- /.navbar-container -->
    </div>
    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
        <script type="text/javascript">
            try {
                ace.settings.check('main-container', 'fixed')
            } catch (e) {}
        </script>
        <!-- #section:basics/sidebar -->
        <div id="sidebar" class="sidebar responsive">
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {}
            </script>
            <?php
            $loadedPage = explode('/', $_SERVER['REDIRECT_URL']);
            $count = count($loadedPage) - 1;
            $loadedPage = $loadedPage[$count];
            $menuPages = array("dashboard");
            if (in_array($loadedPage, $menuPages)) {
                $menuClass = 'active open';
            } else {
                $menuClass = '';
            }
            // access modules
            $selected_modules = array();
            // $selected_modules = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14');
           
            if ($user_role == 1) {
                $selected_modules = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14','15','16');
            }
            else {
                if ($access['numRows'] > 0) {
                    foreach ($access['data'] as $module) {
                        $selected_modules[] = $module->moduleId;
                    }
                }
            }
            ?>
            <ul class="nav nav-list">
                <?php
                if (in_array(3, $selected_modules)) {
                    $menuPages = array("add_track");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_add_track') }}" class="" data-updatedUri="{{ route('add_track_new') }}">
                            <i class="menu-icon fa fa-music"></i>
                            <span class="menu-text"> Add Track </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php }     //	$loadedPage = "members";
                
                
                if (in_array(3, $selected_modules)) {
                    $menuPages = array("album_add");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_add_album') }}" class="">
                            <i class="menu-icon fa fa-headphones"></i>
                            <span class="menu-text"> Add An Album </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php } 
                
                $menuPages = array("admins", "add_admin");
                if (in_array($loadedPage, $menuPages)) {
                    $menuClass = 'active open';
                } else {
                    $menuClass = '';
                }
                ?>
                <li class="<?php echo $menuClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-user"></i>
                        <span class="menu-text"> Admins </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'admins') == 0) {
                            $subMenuClass = 'active';
                        }
                        ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('admin_listing') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                View
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'add_admin') == 0) {
                            $subMenuClass = 'active';
                        }
                        if ($user_role == 1) {
                        ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_admin') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Admin
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php
                
                if (in_array(2, $selected_modules)) {
                    //	$loadedPage = "members"
                    $menuPages = array("members", "add_member", "member_view", "pending_members", "manage_pending_member", "add_multiple_member", "member_edit", "member_payments", "member_payment_view", "member_digicoins", "member_change_password");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    } ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-users"></i>
                            <span class="menu-text"> Members </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("members", "member_view", "member_edit");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('adminMembersListing') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_member') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_member') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Member
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_multiple_member') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_multiple_member') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Multiple Members
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'member_payments') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('member_payments') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Payments
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('pending_members') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Not verified
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php   }
                
                if (in_array(1, $selected_modules)) {
                    $menuPages = array("clients", "add_client", "client_view", "client_edit", "pending_clients", "manage_pending_client", "client_payments", "client_payment_view", "client_change_password");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-users"></i>
                            <span class="menu-text"> Clients </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("clients", "client_view", "client_edit", "client_change_password");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_clients_listing') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_client') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_client') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Client
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("pending_clients", "manage_pending_client");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('pending_clients') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Pending requests
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("client_payments", "client_payment_view");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('client_payments') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Payments
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php }                
                
                $menuClass = '';
                if (in_array(3, $selected_modules)) {
                    //  $loadedPage = "members";
                    $menuPages = array("tracks", "add_track", "add_track1", "add_project","manage_releasetype","add_track2", "submitted_tracks", "submitted_track_edit", "track_edit", "track_view", "track_manage_mp3", "track_review", "track_label_reps_manage", "track_add_contact", "export_tracks");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-music"></i>
                            <span class="menu-text"> Tracks </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("tracks","top_streaming", "top_priority", "track_edit", "track_view", "track_manage_mp3", "track_review", "track_label_reps_manage", "track_add_contact", "albums", "album_edit");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_tracks_listing') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_track') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_add_track') }}" data-updatedUri="{{ route('add_track_new') }}" >
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Track(Old)
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_track_new') }}" data-updatedUri="{{ route('add_track_new') }}" >
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Track(Updated)
                                </a>
                                <b class="arrow"></b>
                            </li>                            
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'submitted_tracks') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('submitted_tracks') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Submitted Tracks
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'submitted_tracks_versions') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('submitted_tracks_versions') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Submitted Tracks Versions
                                </a>
                                <b class="arrow"></b>
                            </li>
                            
                            
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'top_streaming') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('top_streaming') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Top Streaming Tracks
                                </a>
                                <b class="arrow"></b>
                            </li>
                            
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'top_priority') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('top_priority') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Top Priority Tracks
                                </a>
                                <b class="arrow"></b>
                            </li>
                            
                            
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'export_tracks') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('export_tracks') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Export Tracks
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'manage_releasetype') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                          <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('manage_releasetype') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Manage Releasetype
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php   }
                    $menuClass = '';
                ?>                
                <li class="<?php echo $menuClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon  fa fa-rocket"></i>
                        <span class="menu-text">Manage Packages </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'admins') == 0) {
                            $subMenuClass = 'active';
                        }
                        ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('member_packages') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Member Packages
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('client_packages') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Client Packages
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('user_packages_details') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                User Payments Details
                            </a>
                            <b class="arrow"></b>
                        </li>
                        
                    </ul>
                </li>
                   <li class="<?php echo $menuClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-bullhorn"></i>
                        <span class="menu-text"> Announcements </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'admins') == 0) {
                            $subMenuClass = 'active';
                        }
                        ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('list_announcement') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                View
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'add_admin') == 0) {
                            $subMenuClass = 'active';
                        }
                        if ($user_role == 1) {
                        ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_announcement_view') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Announcements
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                     <li class="<?php echo $menuClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon  fa fa-newspaper-o"></i>
                        <span class="menu-text"> Articles </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'admins') == 0) {
                            $subMenuClass = 'active';
                        }
                        ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('list_forums') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                View
                            </a>
                            <b class="arrow"></b>
                        </li>
                        
                    </ul>
                </li>
                <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa fa-archive"></i>
                            <span class="menu-text"> Sneakers </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("store", "edit_product", "product_review_options", "product_review_report");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('sneaker') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_product') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_sneaker') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Sneaker
                                </a>
                                <b class="arrow"></b>
                            </li>
                       
                        </ul>
                    </li>
                       <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-video-camera"></i>
                            <span class="menu-text"> Videos </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("store", "edit_product", "product_review_options", "product_review_report");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('videos') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_product') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_video') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Video
                                </a>
                                <b class="arrow"></b>
                            </li>
                       
                        </ul>
                    </li>
                <li class="<?php echo $menuClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-user"></i>
                        <span class="menu-text"> News </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'admins') == 0) {
                            $subMenuClass = 'active';
                        }
                        ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('list_news') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                View
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'add_admin') == 0) {
                            $subMenuClass = 'active';
                        }
                        if ($user_role == 1) {
                        ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_news_view') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add News
                                </a>
                                <b class="arrow"></b>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php
                if (in_array(6, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("labels", "label_add", "label_view");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-tags"></i>
                            <span class="menu-text"> Labels </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("labels", "label_view");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_labels_listing') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'label_add') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_add_labels') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Label
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php  }
                if (in_array(5, $selected_modules)) {
                    //	$loadedPage = "logos"
                    $menuPages = array("logos", "logo_add", "logo_view", "logo_edit");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-calendar"></i>
                            <span class="menu-text"> Logos </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("logos", "logo_view", "logo_edit");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_listCompanyLogos') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'logo_add') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_addCompanyLogo') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Logo
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php }
                if (in_array(10, $selected_modules)) {
                    //	$loadedPage = "genres"
                    $menuPages = array("genres");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_listGenres') }}" class="">
                            <i class="menu-icon fa fa-inbox"></i>
                            <span class="menu-text"> Genres </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php }
                 if (in_array(16, $selected_modules)) {
                    //	$loadedPage = "genres"
                    $menuPages = array("requests");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('additionalRequests') }}" class="">
                            <i class="menu-icon fa fa-bell"></i>
                            <span class="menu-text"> Additional Requests </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php }
                if (in_array(3, $selected_modules)) {
                    $menuPages = array("albums", "album_edit", "album_add");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                <li class="<?php echo $menuClass; ?>">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-headphones"></i>
                        <span class="menu-text"> Albums </span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <?php
                        $subMenuClass = '';
                        $possiblePages = array("albums", "album_edit");
                        if (in_array($loadedPage, $possiblePages)) {
                            $subMenuClass = 'active';
                        }
                        ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('admin_albums_listing') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                View
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <?php
                        $subMenuClass = '';
                        if (strcmp($loadedPage, 'album_add') == 0) {
                            $subMenuClass = 'active';
                        } ?>
                        <li class="<?php echo $subMenuClass; ?>">
                            <a href="{{ route('admin_add_album') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Add Album
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
                <?php }

                if (in_array(4, $selected_modules)) {
                    //	$loadedPage = "dj tools";
                    $menuPages = array("tools", "add_tool", "edit_tool");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-sliders"></i>
                            <span class="menu-text"> DJ Tools </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("tools", "edit_tool");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_listTools') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_tool') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_addDjTool') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add DJ Tool
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php  }
                if (in_array(7, $selected_modules)) {
                    // $loadedPage = "members";
                    $menuPages = array("mails", "send_mail", "mail_view");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-envelope"></i>
                            <span class="menu-text"> Mails </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("mails", "mail_view");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_listMails') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'send_mail') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_sendMail') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Send Mail
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php  }
                if (in_array(8, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("subscribers", "newsletters", "send_newsletter", "newsletter_view");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-users user-icon"></i>
                            <span class="menu-text"> Subscribers </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("subscribers");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_listSubscribers') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("newsletters", "newsletter_view");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_listNewsletters') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Newsletters
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'send_newsletter') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <!--a href="{{ route('admin_sendNewsletter') }}"-->
                                <a href="{{ route('admin_sendNewsletter') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Send Newsletter
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php    }
                if (in_array(13, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("faqs", "faq_add", "faq_edit", "faq_view");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-question-circle"></i>
                            <span class="menu-text"> FAQs </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("faqs", "faq_edit", "faq_view");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_listFaqs') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("faq_add");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('admin_addNewFaq') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add FAQ
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php    }
                if (in_array(13, $selected_modules)) {
                    $menuPages = array("admin_staff_selected");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
				<a href="{{ route('admin_ListStaffSelected') }}">
                            <i class="menu-icon fa fa-users user-icon"></i>
                            <span class="menu-text"> Staff Selection </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php }
                if (in_array(14, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("digicoins");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('Digicoins') }}">
                            <i class="menu-icon fa fa-database"></i>
                            <span class="menu-text"> Digicoins </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php    }
                if (in_array(11, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("store", "add_product", "edit_product", "product_review_options", "product_review_report", "product_digicoins", "product_orders", "view_question", "edit_question");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa fa-archive"></i>
                            <span class="menu-text"> Products </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            $subMenuClass = '';
                            $possiblePages = array("store", "edit_product", "product_review_options", "product_review_report");
                            if (in_array($loadedPage, $possiblePages)) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('products_lisitng') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'add_product') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('add_product') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Product
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'product_orders') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('product_orders') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Orders
                                </a>
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                <?php    }
                if (in_array(12, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("countries");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_ListCountries') }}">
                            <i class="menu-icon fa fa-globe"></i>
                            <span class="menu-text"> Countries </span>
                        </a>
                    </li>
                    <?php    //	$loadedPage = "members";
                    $menuPages = array("states");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                    ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_ListStates') }}">
                            <i class="menu-icon fa fa-map-marker"></i>
                            <span class="menu-text"> States </span>
                        </a>
                    </li>
                <?php }
                if (in_array(9, $selected_modules)) {
                    //	$loadedPage = "members";
                    $menuPages = array("pages", "what_is_digiwaxx", "promote_your_project", "charts", "digiwaxx_radio", "contact_us", "press_page", "clients_page", "wall_of_scratch", "what_we_do", "free_promo", "events", "testimonials", "why_join", "sponsor_advertise", "im_dj");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }
                ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-file-o"></i>
                            <span class="menu-text">
                                Website Pages
                                <!-- #section:basics/sidebar.layout.badge -->
                                <!--<span class="badge badge-primary">15</span>-->
                                <!-- /section:basics/sidebar.layout.badge -->
                            </span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            <?php
                            /*	$subMenuClass = '';
						if(strcmp($loadedPage,'pages')==0)
								{
								   $subMenuClass = 'active';
								}
								*/
                            ?>
                            <!--	<li class="<?php // echo $subMenuClass; 
                                                ?>">
								<a href="<?php // echo base_url("pages"); 
                                            ?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Home page
								</a>
								<b class="arrow"></b>
							</li>-->
                            <?php
                            $subMenuClass = '';
                            if (strcmp($loadedPage, 'what_is_digiwaxx') == 0) {
                                $subMenuClass = 'active';
                            }
                            ?>
                            <li class="<?php echo $subMenuClass; ?>">
                                <a href="{{ route('view_website_pages') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    View
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageWhatIsDigiwaxx') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Home page-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php
                            // $subMenuClass = '';
                            // if (strcmp($loadedPage, 'promote_your_project') == 0) {
                            //     $subMenuClass = 'active';
                            // }
                            ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PagePromoteYourProject') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Promote Your Project-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php
                            // $subMenuClass = '';
                            // if (strcmp($loadedPage, 'charts') == 0) {
                            //     $subMenuClass = 'active';
                            // }
                            ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageCharts') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Charts-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php
                            // $subMenuClass = '';
                            // if (strcmp($loadedPage, 'digiwaxx_radio') == 0) {
                            //     $subMenuClass = 'active';
                            // }
                            ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageDigiwaxxRadio') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Digiwaxx Radio-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php
                            // $subMenuClass = '';
                            // if (strcmp($loadedPage, 'contact_us') == 0) {
                            //     $subMenuClass = 'active';
                            // } 
                            ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageContactUs') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Contact Us-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            </li>
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'press_page') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PagePress') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Press Page-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'clients_page') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageClientsPage') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Clients Page-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'wall_of_scratch') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageWallOfScratch') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Wall Of Scratch-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'what_we_do') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageWhatWeDo') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        What We Do-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'free_promo') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageFreePromo') }}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Free Promo-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'events') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageEvents')}}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Events-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'testimonials') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageTestimonials')}}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Testimonials-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'why_join') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageWhyJoin')}}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Why Join-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'sponsor_advertise') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageSponsorAdvert')}}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Sponsor Advertise-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'help') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageHelp')}}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        Help-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                            <?php $subMenuClass = '';
                            if (strcmp($loadedPage, 'im_dj') == 0) {
                                $subMenuClass = 'active';
                            } ?>
                            <!--<li class="<?php echo $subMenuClass; ?>">-->
                            <!--    <a href="{{ route('admin_PageImDj')}}">-->
                            <!--        <i class="menu-icon fa fa-caret-right"></i>-->
                            <!--        I'm a DJ-->
                            <!--    </a>-->
                            <!--    <b class="arrow"></b>-->
                            <!--</li>-->
                        </ul>
                    </li>
                    <?php $menuPages = array("seo");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    }    ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_ListSEO') }}">
                            <i class="menu-icon fa fa-desktop"></i>
                            <span class="menu-text"> SEO </span>
                        </a>
                    </li>
                    <?php
                    $menuPages = array("youtube");
                    if (in_array($loadedPage, $menuPages)) {
                        $menuClass = 'active open';
                    } else {
                        $menuClass = '';
                    } ?>
                    <li class="<?php echo $menuClass; ?>">
                        <a href="{{ route('admin_ListYoutube') }}" class="">
                            <i class="menu-icon fa fa-youtube-play"></i>
                            <span class="menu-text"> Youtube </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php } ?>
            </ul><!-- /.nav-list -->
            <!-- #section:basics/sidebar.layout.minimize -->
            <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
            </div>
            <!-- /section:basics/sidebar.layout.minimize -->
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'collapsed')
                } catch (e) {}
            </script>
        </div>
        <!-- /section:basics/sidebar -->