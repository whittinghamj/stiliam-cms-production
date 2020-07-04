<?php

if( isset( $_GET['dev'] ) && $_GET['dev'] == 'yes' ){
    error_reporting( E_ALL );
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
}

date_default_timezone_set( 'UTC' );

session_start();

// includes
include( 'inc/db.php' );
include( 'inc/global_vars.php' );
include( 'inc/functions.php' );

// start timer for page loaded var
$time = microtime();
$time = explode( ' ', $time );
$time = $time[1] + $time[0];
$start = $time;

// check is account id is set, if not then assume user is not logged in correctly and redirect to login page
if( empty( $_SESSION['account']['id'] ) ) {
    // status_message('danger', 'Login Session Timeout');
    go( 'index.php?c=session_timeout' );
}

// get account details for logged in user
$account_details = account_details( $_SESSION['account']['id'] );

if( $account_details['password'] == 'admin'){
    status_message( 'danger', "Default password detected. Change it ASAP." );
}

## handle the theme
if( $account_details['theme'] == 'light' ) {
    $theme_css_file                 = 'css/vertical-layout-light/style.css';
    $theme_text_color               = 'text-dark';
    $high_charts_css                = '';
    $high_charts_text_color         = 'black';
} else {
    $theme_css_file                 = 'css/vertical-layout-dark/style.css';
    $theme_text_color               = 'text-light';
    $high_charts_css                = '<link rel="stylesheet" href="css/server_stats.css"/>';
    $high_charts_text_color         = 'white';
}

// set some global vars for use later
#$globals['customers']                       = total_customers();
#$globals['mags']                            = total_mags();
#$globals['resellers']                       = total_resellers();
#$globals['servers']['total']                = total_servers();
#$globals['servers']['online']               = total_online_servers();
#$globals['servers']['offline']              = total_offline_servers();
#$globals['servers']['total_bandwidth']      = total_bandwidth();
#$globals['streams']['total']                = total_streams();
#$globals['vod']['total']                    = total_vod();
#$globals['tv_series']['total']              = total_tv_series();
#$globals['channels']['total']               = total_channels();
#$globals['cdn_streams']['total']            = total_cdn_streams();
#$globals['clients']['total']                = total_online_clients();
#$globals['firewall_rules']['total']         = total_firewall_rules();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo $site['title']; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/scrollspyNav.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="plugins/font-icons/fontawesome/css/fontawesome.css">

    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="assets/css/elements/alert.css">

    <link href="assets/css/components/timeline/custom-timeline.css" rel="stylesheet" type="text/css" />

    <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/custom_dt_custom.css">

    <link href="assets/css/forms/theme-checkbox-radio.css" rel="stylesheet" type="text/css" />

    <link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/apps/contacts.css" rel="stylesheet" type="text/css" />

    <style>
        .feather-icon .icon-section {
            padding: 30px;
        }
        .feather-icon .icon-section h4 {
            color: #bfc9d4;
            font-size: 17px;
            font-weight: 600;
            margin: 0;
            margin-bottom: 16px;
        }
        .feather-icon .icon-content-container {
            padding: 0 16px;
            width: 86%;
            margin: 0 auto;
            border: 1px solid #191e3a;
            border-radius: 6px;
        }
        .feather-icon .icon-section p.fs-text {
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        .feather-icon .icon-container { cursor: pointer; }
        .feather-icon .icon-container svg {
            color: #bfc9d4;
            margin-right: 6px;
            vertical-align: middle;
            width: 20px;
            height: 20px;
            fill: rgba(0, 23, 55, 0.08);
        }
        .feather-icon .icon-container:hover svg {
            color: #888ea8;
        }
        .feather-icon .icon-container span { display: none; }
        .feather-icon .icon-container:hover span { color: #888ea8; }
        .feather-icon .icon-link {
            color: #888ea8;
            font-weight: 600;
            font-size: 14px;
        }

        /*FAB*/
        .fontawesome .icon-section {
            padding: 30px;
        }
        .fontawesome .icon-section h4 {
            color: #bfc9d4;
            font-size: 17px;
            font-weight: 600;
            margin: 0;
            margin-bottom: 16px;
        }
        .fontawesome .icon-content-container {
            padding: 0 16px;
            width: 86%;
            margin: 0 auto;
            border: 1px solid #191e3a;
            border-radius: 6px;
        }
        .fontawesome .icon-section p.fs-text {
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        .fontawesome .icon-container { cursor: pointer; }
        .fontawesome .icon-container i {
            font-size: 20px;
            color: #bfc9d4;
            vertical-align: middle;
            margin-right: 10px;
        }
        .fontawesome .icon-container:hover i { color: #888ea8; }
        .fontawesome .icon-container span { color: #888ea8; display: none; }
        .fontawesome .icon-container:hover span { color: #888ea8; }
        .fontawesome .icon-link {
            color: #888ea8;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <style>
        .layout-px-spacing {
            min-height: calc(100vh - 166px)!important;
        }
    </style>
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
</head>

<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="?c=dashboard">
                        <img src="assets/img/90x90.jpg" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="?c=dashboard" class="nav-link"> Stiliam </a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-auto">
                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="<?php echo $account_details['avatar']; ?>" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a href="?c=my_profile" class="">
                                    <i data-feather="user"></i><span class="icon-name"> My Profile</span>
                                </a>
                            </div>
                            <div class="dropdown-item">
                                <a href="apps_mailbox.html" class="">
                                    <i data-feather="inbox"></i><span class="icon-name"> Inbox</span>
                                </a>
                            </div>
                            <div class="dropdown-item">
                                <a href="lockscreen.php" class="">
                                    <i data-feather="lock"></i><span class="icon-name"> Lock Screen</span>
                                </a>
                            </div>
                            <div class="dropdown-item">
                                <a href="logout.php" class="">
                                    <i data-feather="log-out"></i><span class="icon-name"> Log Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i data-feather="home"></i>
            </a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol id="dynamic_breadcrumbs" class="breadcrumb"></ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">
            
            <nav id="sidebar">
                <div class="shadow-bottom"></div>

                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <?php if( get('c') == '' || get('c') == 'dashboard' || get('c') == 'home' ){
                        $menu_highlight     = 'true';
                        $menu_active        = 'true';
                        $menu_show          = 'show';
                    } else {
                        $menu_highlight     = 'false';
                        $menu_active        = 'false';
                        $menu_show          = 'hide';
                    } ?>
                    <li class="menu">
                        <a href="?c=dashboard" data-active="<?php echo $menu_active; ?>" aria-expanded="<?php echo $menu_highlight; ?>" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="home"></i><span class="icon-name"> Dashboard</span>
                            </div>
                        </a>
                    </li>
                    
                    <?php if( get('c') == 'global_settings' || get('c') == 'license' ){
                        $menu_highlight     = 'true';
                        $menu_active        = 'true';
                        $menu_show          = 'show';
                    } else {
                        $menu_highlight     = 'false';
                        $menu_active        = 'false';
                        $menu_show          = 'hide';
                    } ?>
                    <li class="menu">
                        <a href="#menu_cms_settings" data-active="<?php echo $menu_active; ?>" data-toggle="collapse" aria-expanded="<?php echo $menu_highlight; ?>" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="settings"></i><span class="icon-name"> CMS Settings</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse <?php echo $menu_show; ?>" id="menu_cms_settings" data-parent="#accordionExample" style="">
                            <li class="<?php if( get('c') == 'global_settings' ) { echo 'active'; } ?>">
                                <a href="?c=global_settings"> Global Settings </a>
                            </li>
                            <li class="<?php if( get('c') == 'license' ) { echo 'active'; } ?>">
                                <a href="?c=license"> License </a>
                            </li>
                        </ul>
                    </li>

                    <hr>

                    <?php if( get('c') == 'channels' || get('c') == 'channel' || get('c') == 'channel_categories' || get('c') == 'channel_category' || get('c') == 'epg_sources' || get('c') == 'epg_source' ){
                        $menu_highlight     = 'true';
                        $menu_active        = 'true';
                        $menu_show          = 'show';
                    } else {
                        $menu_highlight     = 'false';
                        $menu_active        = 'false';
                        $menu_show          = 'hide';
                    } ?>
                    <li class="menu">
                        <a href="#menu_channels" data-active="<?php echo $menu_active; ?>" data-toggle="collapse" aria-expanded="<?php echo $menu_highlight; ?>" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="tv"></i><span class="icon-name"> Live TV</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse <?php echo $menu_show; ?>" id="menu_channels" data-parent="#accordionExample" style="">
                            <li class="<?php if( get('c') == 'channel_categories' || get('c') == 'channel_category' ) { echo 'active'; } ?>">
                                <a href="?c=channel_categories"> Categories </a>
                            </li>
                            <li class="<?php if( get('c') == 'channels' || get('c') == 'channel' ) { echo 'active'; } ?>">
                                <a href="?c=channels"> Channels </a>
                            </li>
                            <li class="<?php if( get('c') == 'epg_sources' || get('c') == 'epg_source' ) { echo 'active'; } ?>">
                                <a href="?c=epg_sources"> EPG Sources </a>
                            </li>
                        </ul>
                    </li>

                    <?php if( get('c') == 'customers' || get('c') == 'customer' ){
                        $menu_highlight     = 'true';
                        $menu_active        = 'true';
                    } else {
                        $menu_highlight     = 'false';
                        $menu_active        = 'false';
                    } ?>
                    <li class="menu">
                        <a href="?c=customers" data-active="<?php echo $menu_active; ?>" aria-expanded="<?php echo $menu_highlight; ?>" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="users"></i><span class="icon-name"> Customers</span>
                            </div>
                        </a>
                    </li>

                    <?php if( get('c') == 'servers' || get('c') == 'server' ){
                        $menu_highlight     = 'true';
                        $menu_active        = 'true';
                    } else {
                        $menu_highlight     = 'false';
                        $menu_active        = 'false';
                    } ?>
                    <li class="menu">
                        <a href="?c=servers" data-active="<?php echo $menu_active; ?>" aria-expanded="<?php echo $menu_highlight; ?>" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="server"></i><span class="icon-name"> Servers</span>
                            </div>
                        </a>
                    </li>

                    <?php if( get('c') == 'users' || get('c') == 'user' ){
                        $menu_highlight     = 'true';
                        $menu_active        = 'true';
                    } else {
                        $menu_highlight     = 'false';
                        $menu_active        = 'false';
                    } ?>
                    <li class="menu">
                        <a href="?c=users" data-active="<?php echo $menu_active; ?>" aria-expanded="<?php echo $menu_highlight; ?>" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="user"></i><span class="icon-name"> Users / Resellers</span>
                            </div>
                        </a>
                    </li>

                    <!--
                        <li class="menu">
                            <a href="#submenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                    <span> Menu 3</span>
                                </div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                            <ul class="collapse submenu list-unstyled" id="submenu2" data-parent="#accordionExample">
                                <li>
                                    <a href="javascript:void(0);"> Submenu 1 </a>
                                </li>
                                <li>
                                    <a href="#sm2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Submenu 2 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                    <ul class="collapse list-unstyled sub-submenu" id="sm2" data-parent="#submenu2"> 
                                        <li>
                                            <a href="javascript:void(0);"> Sub-Submenu 1 </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"> Sub-Submenu 2 </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"> Sub-Submenu 3 </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    -->
                </ul>
            </nav>
        </div>
        <!--  END SIDEBAR  -->
        
        <!--  BEGIN CONTENT PART  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <?php
                        $c = get('c');
                        switch ($c){
                            // channels
                            case "channels":
                                channels();
                                break;

                            // channel
                            case "channel":
                                channel();
                                break;

                            // channel_categories
                            case "channel_categories":
                                channel_categories();
                                break;

                            // channel_category
                            case "channel_category":
                                channel_category();
                                break;

                            // customers
                            case "customers":
                                customers();
                                break;

                            // customer
                            case "customer":
                                customer();
                                break;

                            // dashboard
                            case "dashboard":
                                dashboard();
                                break;

                            // epg_sources
                            case "epg_sources":
                                epg_sources();
                                break;

                            // epg_source
                            case "epg_source":
                                epg_source();
                                break;

                            // global_settings
                            case "global_settings":
                                global_settings();
                                break;

                            // license
                            case "license":
                                license();
                                break;

                            // my_profile
                            case "my_profile":
                                my_profile();
                                break;

                            // sandbox
                            case "sandbox":
                                sandbox();
                                break;

                            // servers
                            case "servers":
                                servers();
                                break;

                            // server
                            case "server":
                                server();
                                break;

                            // users
                            case "users":
                                users();
                                break;

                            // user
                            case "user":
                                user();
                                break;

                            // default
                            default:
                                if( $account_details['type'] == 'admin' ) {
                                    dashboard();
                                } else {
                                    customers();
                                }
                                break;
                        }
                    ?>

                    <?php function channels() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Channels</span></li>';
                        </script>
                    <?php } ?>

                    <?php function channel() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>Channels</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Channel</span></li>';
                        </script>
                    <?php } ?>

                    <?php function channel_categories() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Channel Categories</span></li>';
                        </script>
                    <?php } ?>

                    <?php function channel_category() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>Channel Categories</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Channel Category</span></li>';
                        </script>
                    <?php } ?>

                    <?php function customers() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Customers</span></li>';
                        </script>
                    <?php } ?>

                    <?php function customer() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>Customers</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Customer</span></li>';
                        </script>
                    <?php } ?>

                    <?php function dashboard() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item active"><span>Dashboard</span></li>';
                        </script>

                        <div class="col-xs-12 col-lg-12 col-12 layout-spacing">
                            <div class="row widget-statistic">
                                <div class="col-xs-3 col-lg-3 col-12">
                                    <div class="widget widget-one_hybrid widget-followers">
                                        <div class="widget-heading">
                                            <div class="w-icon">
                                                <i data-feather="server"></i>
                                            </div>
                                            <p class="w-value"><?php echo total_servers(); ?></p>
                                            <h5 class="">Servers</h5>
                                        </div>
                                        <div class="widget-content">    
                                            <div class="w-chart">
                                                <div id="hybrid_followers"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-lg-3 col-12">
                                    <div class="widget widget-one_hybrid widget-referral">
                                        <div class="widget-heading">
                                            <div class="w-icon">
                                                <i data-feather="users"></i>
                                            </div>
                                            <p class="w-value"><?php echo number_format( total_customers() ); ?></p>
                                            <h5 class="">Customers</h5>
                                        </div>
                                        <div class="widget-content">    
                                            <div class="w-chart">
                                                <div id="hybrid_followers1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-lg-3 col-12">
                                    <div class="widget widget-one_hybrid widget-engagement">
                                        <div class="widget-heading">
                                            <div class="w-icon">
                                                <i data-feather="tv"></i>
                                            </div>
                                            <p class="w-value"><?php echo number_format( total_channels() ); ?></p>
                                            <h5 class="">TV Channels</h5>
                                        </div>
                                        <div class="widget-content">    
                                            <div class="w-chart">
                                                <div id="hybrid_followers3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-lg-3 col-12">
                                    <div class="widget widget-one_hybrid widget-engagement">
                                        <div class="widget-heading">
                                            <div class="w-icon">
                                                <i data-feather="film"></i>
                                            </div>
                                            <p class="w-value"><?php echo number_format( total_vod() ); ?></p>
                                            <h5 class="">Video on Demand</h5>
                                        </div>
                                        <div class="widget-content">    
                                            <div class="w-chart">
                                                <div id="hybrid_followers3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php } ?>

                    <?php function epg_sources() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>EPG Sources</span></li>';
                        </script>
                    <?php } ?>

                    <?php function epg_source() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>EPG Sources</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>EPG Source</span></li>';
                        </script>
                    <?php } ?>

                    <?php function global_settings() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>CMS Settings</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Global Settings</span></li>';
                        </script>
                    <?php } ?>

                    <?php function license() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>CMS Settings</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>License</span></li>';
                        </script>
                    <?php } ?>

                    <?php function my_profile() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>My Profile</span></li>';
                        </script>

                        <div class="col-lg-12 col-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>My Profile</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <form action="actions.php?a=my_profile" method="post">
                                        <div class="form-group mb-4">
                                            <label for="theme">Theme</label>
                                            <select class="form-control" id="theme" name="theme">
                                                <option value="light" <?php if( $account_details['theme']=='light' ){ echo'selected'; } ?> >Light</option>
                                                <option value="dark" <?php if( $account_details['theme']=='dark' ){ echo'selected'; } ?> >Dark</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="first_name" class="<?php echo $theme_text_color; ?>">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $account_details['first_name']; ?>" placeholder="example: John" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="last_name" class="<?php echo $theme_text_color; ?>">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $account_details['last_name']; ?>" placeholder="example: Smith" required>
                                        </div>
                                        
                                        <div class="form-group mb-4">
                                            <label for="email" class="<?php echo $theme_text_color; ?>">Email Address</label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $account_details['email']; ?>" placeholder="example: johnsmith@gmail.com" required data-inputmask="'alias': 'email'">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="username" class="<?php echo $theme_text_color; ?>">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $account_details['username']; ?>" placeholder="example: johnsmith_admin" required>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="password1" class="<?php echo $theme_text_color; ?>">Password</label>
                                            <input type="password" class="form-control" id="password1" name="password1" placeholder="Leave blank and current password will not be changed." >
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="password2" class="<?php echo $theme_text_color; ?>">Confirm Password</label>
                                            <input type="password" class="form-control" id="password2" name="password2" placeholder="Leave blank and current password will not be changed." >
                                        </div>

                                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php function sandbox() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Sandbox</span></li>';
                        </script>

                        <div class="col-lg-12 col-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <div class="container-fluid error-content">
                                        <h4><strong>$_GET</strong></h4>
                                        <?php debug($_GET); ?>
                                            
                                        <h4><strong>$_POST</strong></h4>
                                        <?php debug($_POST); ?>
                                            
                                        <h4><strong>$_SESSION</strong></h4>
                                        <?php debug($_SESSION); ?>
                                            
                                        <h4><strong>$account_details</strong></h4>
                                        <?php debug($account_details); ?>

                                        <h4><strong>$global_settings</strong></h4>
                                        <?php debug($global_settings); ?>

                                        <h4><strong>$globals</strong></h4>
                                        <?php debug($globals); ?>

                                        <h4><strong>$addon_licenses</strong></h4>
                                        <?php debug($addon_licenses); ?>

                                        <h4><strong>$addons</strong></h4>
                                        <?php debug($addons); ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php } ?>

                    <?php function servers() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Servers</span></li>';
                        </script>

                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <div class="calendar-upper-section">
                                        <div class="row">
                                            <div class="col-md-8 col-12">
                                                <h4>Servers</h4>
                                            </div>                                                
                                            <div class="col-md-4 col-12">
                                                <form action="javascript:void(0);" class="form-horizontal mt-md-0 mt-3 text-md-right text-center">
                                                    <button class="btn btn-primary" data-toggle="modal" data-target="#server_add_modal"><i data-feather="plus"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <table id="zero-config" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="<?php echo $theme_text_color; ?>">Name</th>
                                                <th class="<?php echo $theme_text_color; ?>">IP</th>
                                                <th width="200px" class="<?php echo $theme_text_color; ?>">Type</th>
                                                <th width="1px" class="<?php echo $theme_text_color; ?>">Status</th>
                                                <th width="150px" class="no-content"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $time_shift = time() - 10;
                                                $query = $conn->query("SELECT * FROM `servers` ");
                                                if($query !== FALSE) {
                                                    $servers = $query->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach($servers as $server) {
                                                        if($server['type'] == 'cms') {
                                                            $server_type = '<div class="btn btn-outline-success btn-block">CMS Server</div>';
                                                        }
                                                        if($server['type'] == 'streamer') {
                                                            $server_type = '<div class="btn btn-outline-success btn-block">Streaming Server</div>';
                                                        }
                                                        if($server['type'] == 'transcoder') {
                                                            $server_type = '<div class="btn btn-outline-success btn-block">Transcoding Server</div>';
                                                        }
                                                        if($server['type'] == 'middleware') {
                                                            $server_type = '<div class="btn btn-outline-success btn-block">Middleware Server</div>';
                                                        }
                                                        if($server['type'] == 'vod') {
                                                            $server_type = '<div class="btn btn-outline-success btn-block">VoD Server</div>';
                                                        }

                                                        if($server['status'] == 'online') {
                                                            $server_status = '<div class="btn btn-outline-success btn-block">Online</div>';
                                                        }elseif($server['status'] == 'pending') {
                                                            $server_status = '<div class="btn btn-outline-warning btn-block">Pending</div>';
                                                        }elseif($server['status'] == 'installed') {
                                                            $server_status = '<div class="btn btn-outline-info btn-block">Installing</div>';
                                                        }elseif($server['status'] == 'offline') {
                                                            $server_status = '<div class="btn btn-outline-danger btn-block">Offline</div>';
                                                        }

                                                        if( $server['wan_ip_address'] == '0.0.0.0' ) {
                                                            $server['wan_ip_address'] = '-';
                                                        }

                                                        echo '
                                                            <tr>
                                                                <td>
                                                                    '.stripslashes( $server['name'] ).'
                                                                </td>
                                                                <td>
                                                                    '.stripslashes( $server['wan_ip_address'] ).'
                                                                </td>
                                                                <td>
                                                                    '.$server_type.'
                                                                </td>
                                                                <td>
                                                                    '.$server_status.'
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="?c=server&id='.$server['id'].'" class="btn btn-outline-primary"><i data-feather="eye"></i></a>
                                                                    
                                                                    '.( $server['type'] != 'cms' ? 
                                                                        '<a href="actions.php?a=server_delete&id='.$server['id'].'" class="btn btn-outline-danger" onclick="return confirm(\'This action cannot be undone and require a full server reinstall to use this server again with the Stiliam platform. \nAre you sure?\')"><i data-feather="trash"></i></a>':
                                                                        '<button class="btn btn-outline-danger" onclick="alert(\'You cannot delete this server.\')"><i data-feather="trash"></i></button>' 
                                                                    ).'

                                                                </td>
                                                            </tr>
                                                        ';
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php function server() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>Servers</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Server</span></li>';
                        </script>
                    <?php } ?>

                    <?php function users() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>Users</span></li>';
                        </script>

                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <div class="calendar-upper-section">
                                        <div class="row">
                                            <div class="col-md-8 col-12">
                                                <h4>Users / Resellers</h4>
                                            </div>                                                
                                            <div class="col-md-4 col-12">
                                                <form action="javascript:void(0);" class="form-horizontal mt-md-0 mt-3 text-md-right text-center">
                                                    <button class="btn btn-primary" data-toggle="modal" data-target="#user_add_modal"><i data-feather="plus"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
                                    <table id="zero-config" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="">ID</th>
                                                <th class="">Type</th>
                                                <th class="">Username</th>
                                                <th class="">Email</th>
                                                <th class="">Credits</th>
                                                <th class="">Status</th>
                                                <th class="no-content"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $time_shift = time() - 10;
                                                $query = $conn->query("SELECT * FROM `users` ");
                                                if($query !== FALSE) {
                                                    $users = $query->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach($users as $user) {
                                                        if($user['type'] == 'admin') {
                                                            $account_type = '<div class="badge badge-outline-success">Admin</div>';
                                                        }else{
                                                            $account_type = '<div class="badge badge-outline-primary">'.ucfirst( $user['type'] ).'</div>';
                                                        }

                                                        if($user['status'] == 'active') {
                                                            $account_status = '<div class="badge badge-outline-success">Active</div>';
                                                        }elseif($user['status'] == 'suspended') {
                                                            $account_status = '<div class="badge badge-outline-warning">Suspended</div>';
                                                        }elseif($user['status'] == 'terminated') {
                                                            $account_status = '<div class="badge badge-outline-danger">Terminated</div>';
                                                        }

                                                        echo '
                                                            <tr>
                                                                <td width="1px">
                                                                    '.$user['id'].'
                                                                </td>
                                                                <td>
                                                                    '.$account_type.'
                                                                </td>
                                                                <td>
                                                                    '.stripslashes( $user['username'] ).'
                                                                </td>
                                                                <td>
                                                                    '.stripslashes( $user['email'] ).'
                                                                </td>
                                                                <td>
                                                                    '.number_format( $user['credits'] ).'
                                                                </td>
                                                                <td>
                                                                    '.$account_status.'
                                                                </td>

                                                                <td>
                                                                    <a href="?c=user&id='.$user['id'].'" class="btn btn-outline-primary"><i data-feather="eye"></i></a>
                                                                    
                                                                    <a href="actions.php?a=user_delete&id='.$user['id'].'" onclick="return confirm(\'This will delete the user and all their customers. \nAre you sure?\')" class="btn btn-outline-danger"><i data-feather="x-circle"></i></a>
                                                                </td>
                                                            </tr>
                                                        ';
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php function user() { ?>
                        <?php global $conn, $global_settings, $account_details, $site, $theme_text_color; ?>

                        <script>
                            document.getElementById('dynamic_breadcrumbs').innerHTML = '<li class="breadcrumb-item"><span>Dashboard</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item"><span>Users</span></li>';
                            document.getElementById('dynamic_breadcrumbs').innerHTML += '<li class="breadcrumb-item active"><span>User</span></li>';
                        </script>

                        <?php $user = account_details( get( 'id' ) ); ?>

                        <?php if( !isset($user) || !isset($user['id']) ) { ?>
                            <div class="col-lg-12 col-12 layout-spacing">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content widget-content-area">
                                        <div class="container-fluid error-content">
                                            <div class="">
                                                <h1 class="error-number">404</h1>
                                                <p class="mini-text">Ooops!</p>
                                                <p class="error-text mb-4 mt-1">The page you requested was not found!</p>
                                                <a href="?c=users" class="btn btn-primary mt-5">Go Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-6 col-12 layout-spacing">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-header">                                
                                        <div class="row">
                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                <h4>User: <?php echo $user['username']; ?> </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content widget-content-area">
                                        <form action="actions.php?a=user_update&id=<?php echo $user['id']; ?>" method="post">
                                            <div class="form-group mb-4">
                                                <label for="type" class="<?php echo $theme_text_color; ?>">Account Type</label>
                                                <select class="form-control" id="type" name="type">
                                                    <option value="admin" <?php if( $user['type']=='admin' ){ echo'selected'; } ?> >Admin</option>
                                                    <option value="reseller" <?php if( $user['type']=='reseller' ){ echo'selected'; } ?> >Reseller</option>
                                                </select>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="first_name" class="<?php echo $theme_text_color; ?>">First Name</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" placeholder="example: John" required>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="last_name" class="<?php echo $theme_text_color; ?>">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" placeholder="example: Smith" required>
                                            </div>
                                            
                                            <div class="form-group mb-4">
                                                <label for="email" class="<?php echo $theme_text_color; ?>">Email Address</label>
                                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" placeholder="example: johnsmith@gmail.com" required data-inputmask="'alias': 'email'">
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="username" class="<?php echo $theme_text_color; ?>">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" placeholder="example: johnsmith_admin" required>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="password1" class="<?php echo $theme_text_color; ?>">Password</label>
                                                <input type="password" class="form-control" id="password1" name="password1" placeholder="Leave blank and current password will not be changed." >
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="password2" class="<?php echo $theme_text_color; ?>">Confirm Password</label>
                                                <input type="password" class="form-control" id="password2" name="password2" placeholder="Leave blank and current password will not be changed." >
                                            </div>

                                            <div class="form-group">
                                                <label for="credits" class="<?php echo $theme_text_color; ?>">Credits</label>
                                                <input type="text" class="form-control" id="credits" name="credits" value="<?php echo $user['credits']; ?>" placeholder="0" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="notes" class="<?php echo $theme_text_color; ?>">Notes</label>
                                                <textarea class="form-control" id="notes" name="notes" rows="5"><?php echo $user['notes']; ?></textarea>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="theme">Theme</label>
                                                <select class="form-control" id="theme" name="theme">
                                                    <option value="light" <?php if( $user['theme']=='light' ){ echo'selected'; } ?> >Light</option>
                                                    <option value="dark" <?php if( $user['theme']=='dark' ){ echo'selected'; } ?> >Dark</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-success mr-2 text-right">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div id="timelineBasic" class="col-lg-6 layout-spacing">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-header">
                                        <div class="row">
                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                <h4>Recent Activities</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content widget-content-area pb-1">
                                        <div class="mt-container mx-auto">
                                            <div class="timeline-line">
                                                <?php
                                                    $query  = $conn->query("SELECT * FROM `user_logs` WHERE `user_id` = '".$user['id']."' ORDER BY `id` DESC LIMIT 50");
                                                    $logs   = $query->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach( $logs as $log ) {
                                                        if( $log['action'] == 'login') {
                                                            $log_color = 'success';
                                                        } elseif( $log['action'] == 'logout' ) {
                                                            $log_color = 'danger';
                                                        } elseif( $log['action'] == 'server_delete' ) {
                                                            $log_color = 'danger';
                                                        } elseif( $log['action'] == 'user_delete' ) {
                                                            $log_color = 'danger';
                                                        } else {
                                                            $log_color = 'primary';
                                                        }
                                                        echo '
                                                            <div class="item-timeline">
                                                                <div class="t-dot t-dot-'.$log_color.'"></div>
                                                                <div class="t-text">
                                                                    <p>'.$log['message'].'</p>
                                                                    <p class="t-meta-time">'.date( "y-m-d h:i", $log['added'] ).'</p>
                                                                </div>
                                                            </div>
                                                        ';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © Copyright © <?php echo date( "Y", time() ); ?> <a href="https://www.stiliam.com/" target="_blank">Stiliam</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <?php
                        $time = microtime();
                        $time = explode(' ', $time);
                        $time = $time[1] + $time[0];
                        $finish = $time;
                        $total_time = round(($finish - $start), 4);
                    ?>
                    <p class="">Page generated in <?php echo $total_time; ?> seconds.</p>
                </div>
            </div>
        </div>
        <!--  END CONTENT PART  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>

    <script src="assets/js/custom.js"></script>

    <script src="plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>

    <script src="plugins/notification/snackbar/snackbar.min.js"></script>

    <script src="plugins/table/datatable/datatables.js"></script>

    <script src="plugins/table/datatable/button-ext/dataTables.buttons.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <?php if(!empty($_SESSION['alert']['status'])){ ?>
        <script>
            /*
                document.getElementById('status_message').innerHTML = '<div class="col-lg-12 col-12 layout-spacing"><div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> mb-4" role="alert"><?php echo $_SESSION['alert']['message']; ?></div></div>';
                setTimeout(function() {
                    $('#status_message').fadeOut('fast');
                }, 5000);
            */

            Snackbar.show({
                text: '<?php echo $_SESSION['alert']['message']; ?>',
                pos: 'top-center',
                showAction: false,
                duration: 5000,
                <?php if( $_SESSION['alert']['status'] == 'primary' ) { ?>
                    actionTextColor: '#fff',
                    backgroundColor: '#1b55e2'
                <?php } ?>
                <?php if( $_SESSION['alert']['status'] == 'info' ) { ?>
                    actionTextColor: '#fff',
                    backgroundColor: '#2196f3'
                <?php } ?>
                <?php if( $_SESSION['alert']['status'] == 'success' ) { ?>
                    actionTextColor: '#fff',
                    backgroundColor: '#8dbf42'
                <?php } ?>
                <?php if( $_SESSION['alert']['status'] == 'warning' ) { ?>
                    actionTextColor: '#fff',
                    backgroundColor: '#e2a03f'
                <?php } ?>
                <?php if( $_SESSION['alert']['status'] == 'danger' ) { ?>
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                <?php } ?>
                <?php if( $_SESSION['alert']['status'] == 'dark' ) { ?>
                    actionTextColor: '#fff',
                    backgroundColor: '#3b3f5c'
                <?php } ?>
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php } ?>

    <?php if( get('c') == 'channels' || get('c') == 'channel' ) { ?>
        <script>
           
        </script>
    <?php } ?>

    <?php if( get('c') == 'customers' || get('c') == 'customer' ) { ?>
        <script>
            
        </script>
    <?php } ?>

    <?php if( get('c') == '' || get('c') == 'dashboard' ) { ?>
        <script>
            
        </script>
    <?php } ?>

    <?php if( get('c') == 'epg_sources' || get('c') == 'epg_source' ) { ?>
        <script>
            
        </script>
    <?php } ?>

    <?php if( get('c') == 'sandbox' ) { ?>
        <script>
            
        </script>
    <?php } ?>


    <?php if( get('c') == 'servers' ) { ?>
        <!-- modal code -->
        <form action="actions.php?a=server_add" method="post" class="forms-sample">
            <div class="modal fade" id="server_add_modal" tabindex="-1" role="dialog" aria-labelledby="user_add_modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="user_add_modalLabel">Add Server</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <i data-feather="times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="<?php echo $theme_text_color; ?>">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            $('#zero-config').DataTable({
                "oLanguage": {
                    "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                   "sLengthMenu": "Results :  _MENU_",
                },
                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 7 
            });
        </script>
    <?php } ?>

    <?php if( get('c') == 'server' ) { ?>
        <?php $server = server_details( get( 'id' ) ); ?>
        <script id="source" language="javascript" type="text/javascript">
            var cpu_usage_gage, ram_usage_gage, bandwidth_down_gage, bandwidth_up_gage;

            // cpu gage
            var cpu_usage_gage = new JustGage({
                id: "cpu_usage_gage",
                value: 0,
                min: 0,
                max: 100,
                title: "CPU Usage",
                symbol : "%",
                labelFontColor: '<?php echo $high_charts_text_color; ?>',
                valueFontColor: '<?php echo $high_charts_text_color; ?>',
                relativeGaugeSize: true,
            });

            // ram gage
            var ram_usage_gage = new JustGage({
                id: "ram_usage_gage",
                value: 0,
                min: 0,
                max: 100,
                title: "RAM Usage",
                symbol : "%",
                labelFontColor: '<?php echo $high_charts_text_color; ?>',
                valueFontColor: '<?php echo $high_charts_text_color; ?>',
                relativeGaugeSize: true
            });

            // disk gage
            var disk_usage_gage = new JustGage({
                id: "disk_usage_gage",
                value: 0,
                min: 0,
                max: 100,
                title: "Disk Usage",
                symbol : "%",
                labelFontColor: '<?php echo $high_charts_text_color; ?>',
                valueFontColor: '<?php echo $high_charts_text_color; ?>',
                relativeGaugeSize: true
            });

            // bandwidth down gage
            var bandwidth_down_gage = new JustGage({
                id: "bandwidth_down_gage",
                value: 0,
                min: 0,
                max: <?php echo $server['connection_speed']; ?>,
                title: "Bandwidth In",
                label: "MBit",
                labelFontColor: '<?php echo $high_charts_text_color; ?>',
                valueFontColor: '<?php echo $high_charts_text_color; ?>',
                relativeGaugeSize: true
            });

            // bandwidth up gage
            var bandwidth_up_gage = new JustGage({
                id: "bandwidth_up_gage",
                value: 0,
                min: 0,
                max: <?php echo $server['connection_speed']; ?>,
                title: "Bandwidth Out",
                label: "MBit",
                labelFontColor: '<?php echo $high_charts_text_color; ?>',
                valueFontColor: '<?php echo $high_charts_text_color; ?>',
                relativeGaugeSize: true
            });

            // update system_stats data gauges
            <?php if( $server['status'] == 'online' ) { ?>
                var isWorking = false;
                function update_system_stats(){
                    if(isWorking) return;
                    isWorking = true;
                    $.ajax({
                        url: 'actions.php?a=ajax_http_proxy&ip_address=<?php echo $server['wan_ip_address']; ?>&port=<?php echo $server['http_stream_port']; ?>',
                        success: function(data) {
                            // parse
                            var dataset = JSON.parse(data);
                            
                            // refresh gauges
                            cpu_usage_gage.refresh(dataset['cpu_usage']);
                            ram_usage_gage.refresh(dataset['ram_usage']);
                            disk_usage_gage.refresh(dataset['disk_usage']);
                            bandwidth_up_gage.refresh(dataset['bandwidth_up']);
                            bandwidth_down_gage.refresh(dataset['bandwidth_down']);

                            // release for next update process
                            isWorking = false;
                        },
                        cache: false
                    });
                }

                setInterval(update_system_stats,1000);
            <?php } ?>
        </script>
    <?php } ?>

    <?php if( get('c') == 'users' ) { ?>
        <!-- modal code -->
        <form action="actions.php?a=user_add" method="post" class="forms-sample">
            <div class="modal fade" id="user_add_modal" tabindex="-1" role="dialog" aria-labelledby="user_add_modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="user_add_modalLabel">Add User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <i data-feather="times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="username" class="<?php echo $theme_text_color; ?>">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="type" class="<?php echo $theme_text_color; ?>">Account Type:</label>
                                <select class="form-control form-control-lg select2" id="type" name="type">
                                    <option value="admin">Admin</option>
                                    <option value="reseller">Reseller</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            $('#zero-config').DataTable({
                "oLanguage": {
                    "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                   "sLengthMenu": "Results :  _MENU_",
                },
                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 7 
            });
        </script>
    <?php } ?>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>
</html>