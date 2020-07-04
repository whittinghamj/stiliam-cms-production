<?php

if( isset( $_GET['dev'] ) && $_GET['dev'] == 'yes' ) {
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

// geoip
require( '/var/www/html/MaxMind-DB-Reader-php/autoload.php' );
use MaxMind\Db\Reader;
$geoip = new Reader( '/var/www/html/GeoLite2-City.mmdb' );
$geoisp = new Reader( '/var/www/html/GeoIP2-ISP.mmdb' );

// start timer for page loaded var
$time = microtime();
$time = explode( ' ', $time );
$time = $time[1] + $time[0];
$start = $time;

// check is account id is set, if not then assume user is not logged in correctly and redirect to login page
if( empty( $_SESSION['account']['id'] ) ) {
    // status_message( 'danger', 'Login Session Timeout' );
    go( 'index.php?c=session_timeout' );
}

// get account details for logged in user
$account_details = account_details( $_SESSION['account']['id'] );

// default password sanity check
if( $account_details['password'] == 'admin' ) {
    status_message( 'danger', "Default password detected. Change it ASAP." );
}

## handle the theme
if( $account_details['theme'] == 'light' ) {

} else {

}

// set some global vars for use later
$globals['totals']['customers']             = total_customers();
$globals['totals']['servers']               = total_servers();
$globals['totals']['channels']              = total_channels();
$globals['totals']['online_channels']       = total_channels_status( 'online' );
$globals['totals']['offline_channels']      = total_channels_status( 'offline' );
$globals['totals']['channels_247']          = total_channels_247();
$globals['totals']['online_channels_247']   = total_channels_247_status( 'online' );
$globals['totals']['offline_channels_247']  = total_channels_247_status( 'offline' );
$globals['totals']['vod']                   = total_vod();
$globals['totals']['vod_tv']                = total_vod_tv();

// global sanity checks
if( get( 'c' ) == 'settings' || get( 'c' ) == 'customers' || get( 'c' ) == 'customer' || get( 'c' ) == 'backup_manager') {
    
} else {
    sanity_check();
}

// paid live chat addon license check
live_chat_check();

?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php echo $site['title']; ?></title>

        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- jQuery UI -->
        <script src="plugins/jquery-ui/jquery-ui.min.js"></script>

        <!-- loading overlay -->
        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

        <!-- icons -->
        <link rel="apple-touch-icon" sizes="57x57" href="img/apple-icon-57x57.png?v=3">
        <link rel="apple-touch-icon" sizes="60x60" href="img/apple-icon-60x60.png?v=3">
        <link rel="apple-touch-icon" sizes="72x72" href="img/apple-icon-72x72.png?v=3">
        <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon-76x76.png?v=3">
        <link rel="apple-touch-icon" sizes="114x114" href="img/apple-icon-114x114.png?v=3">
        <link rel="apple-touch-icon" sizes="120x120" href="img/apple-icon-120x120.png?v=3">
        <link rel="apple-touch-icon" sizes="144x144" href="img/apple-icon-144x144.png?v=3">
        <link rel="apple-touch-icon" sizes="152x152" href="img/apple-icon-152x152.png?v=3">
        <link rel="apple-touch-icon" sizes="180x180" href="img/apple-icon-180x180.png?v=3">
        <link rel="icon" type="image/png" sizes="192x192"  href="img/android-icon-192x192.png?v=3">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png?v=3">
        <link rel="icon" type="image/png" sizes="96x96" href="img/favicon-96x96.png?v=3">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png?v=3">
        <link rel="manifest" href="img/manifest.json?v=2">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css" />

        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />

        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

        <!-- Select2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

        <!-- jQuery UI -->
        <link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />

        <!-- SnackBar Notifications -->
        <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />

        <!-- datepicker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        
        <!-- Ekko Lightbox -->
        <link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.css">

        <!-- OrgChart -->
        <link rel="stylesheet" href="OrgChart/dist/css/jquery.orgchart.min.css">
        <style type="text/css">
            .orgchart { background: #fff; }
            .orgchart td.left, .orgchart td.right, .orgchart td.top { border-color: #aaa; }
            .orgchart td>.down { background-color: #aaa; }
            .orgchart .middle-level .title { background-color: #006699; }
            .orgchart .middle-level .content { border-color: #006699; }
            .orgchart .product-dept .title { background-color: #009933; }
            .orgchart .product-dept .content { border-color: #009933; }
            .orgchart .rd-dept .title { background-color: #993366; }
            .orgchart .rd-dept .content { border-color: #993366; }
            .orgchart .pipeline1 .title { background-color: #996633; }
            .orgchart .pipeline1 .content { border-color: #996633; }
            .orgchart .frontend1 .title { background-color: #cc0066; }
            .orgchart .frontend1 .content { border-color: #cc0066; }
            .orgchart .oci { display: none; }
        </style>

        <!-- customer css -->
        <style>
            .primary_server_background { background-color: #006699; }
            .secondary_server_background { background-color: #009933; }

            .ellipsis {
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .nowrap {
                display: block;
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden
            }

            .hide-text {
                text-indent: 100%;
                white-space: nowrap;
                overflow: hidden;
            }

            .sortable tr {
                cursor: pointer;
            }
        </style>

        <!--
            <link rel="stylesheet" href="dist/css/skins/skin-midnight.css">
            <link rel="stylesheet" href="css/midnight-modals.css">
            <link rel="stylesheet" href="css/midnight-datatables.css">
        -->
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <!--
                        <li class="nav-item d-none d-sm-inline-block">
                            <a href="index3.html" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item d-none d-sm-inline-block">
                            <a href="#" class="nav-link">Contact</a>
                        </li>
                    -->
                </ul>

                <!-- SEARCH FORM -->
                <!--
                    <form class="form-inline ml-3">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" />
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                -->

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo $account_details['avatar']; ?>" class="user-image img-circle elevation-2" alt="User Image" />
                            <span class="d-none d-md-inline"><?php echo $account_details['username']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <!-- User image -->
                            <li class="user-header bg-primary">
                                <img src="<?php echo $account_details['avatar']; ?>" class="img-circle elevation-2" alt="User Image" />

                                <?php if( !empty( $account_details['first_name'] ) || !empty( $account_details['last_name'] ) ) { ?>
                                    <p>
                                        <?php echo $account_details['first_name'].' '.$account_details['last_name']; ?>
                                    </p>
                                <?php } ?>
                            </li>
                            <!-- Menu Body -->
                            <!--
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </div>
                                </li>
                            -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <a href="?c=my_profile" class="btn btn-default btn-flat">Profile</a>
                                <a href="logout.php" class="btn btn-default btn-flat float-right">Sign out</a>
                            </li>
                        </ul>
                    </li>

                    <!--
                        <li class="nav-item">
                            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                                <i class="fas fa-th-large"></i>
                            </a>
                        </li>
                    -->
                </ul>

                <!--
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i class="fas fa-th-large"></i></a>
                        </li>
                    </ul>
                -->
            </nav>

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="index3.html" class="brand-link">
                    <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" /> -->
                    <img src="img/stiliam_logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
                    <span class="brand-text font-weight-light"><?php echo $site['title']; ?></span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <?php if($account_details['email'] == 'jamie.whittingham@gmail.com') { ?>
                                <?php 
                                    if( get( 'c' ) == 'staging' ) { 
                                        $menu['active']     = 'active';
                                        $menu['tree']       = 'menu-open';
                                    } else {
                                        $menu['active']     = '';
                                        $menu['tree']       = '';
                                    }
                                ?>
                                <li class="nav-item">
                                    <a href="?c=staging" class="nav-link <?php echo $menu['active']; ?>">
                                        <i class="nav-icon fas fa-cogs"></i>
                                        <p>
                                            Staging
                                        </p>
                                    </a>
                                </li>

                                <?php 
                                    if( get( 'c' ) == 'deploy' ) { 
                                        $menu['active']     = 'active';
                                        $menu['tree']       = 'menu-open';
                                    } else {
                                        $menu['active']     = '';
                                        $menu['tree']       = '';
                                    }
                                ?>
                                <li class="nav-item">
                                    <a href="?c=deploy" class="nav-link <?php echo $menu['active']; ?>">
                                        <i class="nav-icon fas fa-indent"></i>
                                        <p>
                                            Deploy
                                        </p>
                                    </a>
                                </li>

                                <hr>
                            <?php } ?>

                            <?php 
                                if( get( 'c' ) == '' || get( 'c' ) == 'dashboard' ) { 
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item">
                                <a href="?c=dashboard" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            <hr>

                            <?php 
                                if( get( 'c' ) == 'channels_247' || get( 'c' ) == 'channels_247_item' ) { 
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item">
                                <a href="?c=channels_247" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-eye"></i>
                                    <p>
                                        24/7 Channels
                                    </p>
                                </a>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'bouquets' || get( 'c' ) == 'bouquet' || get( 'c' ) == 'customers' || get( 'c' ) == 'customer' || get( 'c' ) == 'mag_devices' || get( 'c' ) == 'packages' || get( 'c' ) == 'package' ) {
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item has-treeview <?php echo $menu['tree']; ?>">
                                <a href="#" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Customers
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="?c=bouquets" class="nav-link <?php if( get( 'c' ) == 'bouquets' || get( 'c' ) == 'bouquet' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Bouquets</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=customers" class="nav-link <?php if( get( 'c' ) == 'customers' || get( 'c' ) == 'customer' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Customers</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=mag_devices" class="nav-link <?php if( get( 'c' ) == 'mag_devices' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>MAG Devices</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=packages" class="nav-link <?php if( get( 'c' ) == 'packages' || get( 'c' ) == 'package' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Packages</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'channels' || get( 'c' ) == 'channel' || get( 'c' ) == 'channel_categories' || get( 'c' ) == 'channel_category' || get( 'c' ) == 'epg_sources' || get( 'c' ) == 'epg_source' || get( 'c' ) == 'channel_icons' || get( 'c' ) == 'channel_topology_profiles' || get( 'c' ) == 'channel_topology_profile' || get( 'c' ) == 'transcoding_profiles' || get( 'c' ) == 'transcoding_profile' ) {
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item has-treeview <?php echo $menu['tree']; ?>">
                                <a href="#" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-tv"></i>
                                    <p>
                                        Live Channels
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="?c=channel_categories" class="nav-link <?php if( get( 'c' ) == 'channel_categories' || get( 'c' ) == 'channel_category' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=epg_sources" class="nav-link <?php if( get( 'c' ) == 'epg_sources' || get( 'c' ) == 'epg_source' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>EPG Sources</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=channel_icons" class="nav-link <?php if( get( 'c' ) == 'channel_icons' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Icons</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=channels" class="nav-link <?php if( get( 'c' ) == 'channels' || get( 'c' ) == 'channel' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Live Channels</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=channel_topology_profiles" class="nav-link <?php if( get( 'c' ) == 'channel_topology_profiles' || get( 'c' ) == 'channel_topology_profile' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Channel Topology Profiles</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=transcoding_profiles" class="nav-link <?php if( get( 'c' ) == 'transcoding_profiles' || get( 'c' ) == 'transcoding_profile' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Transcoding Profiles</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'vod' || get( 'c' ) == 'vod_item' || get( 'c' ) == 'vod_categories' || get( 'c' ) == 'vod_category' ) {
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item has-treeview <?php echo $menu['tree']; ?>">
                                <a href="#" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-video"></i>
                                    <p>
                                        Movies VoD
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="?c=vod_categories" class="nav-link <?php if( get( 'c' ) == 'vod_categories' || get( 'c' ) == 'vod_category' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=vod" class="nav-link <?php if( get( 'c' ) == 'vod' || get( 'c' ) == 'vod_item' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Movies VoD</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'vod_tv' || get( 'c' ) == 'vod_tv_item' || get( 'c' ) == 'vod_tv_categories' || get( 'c' ) == 'vod_tv_category' ) {
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item has-treeview <?php echo $menu['tree']; ?>">
                                <a href="#" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-tablet-alt"></i>
                                    <p>
                                        TV VoD
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="?c=vod_tv_categories" class="nav-link <?php if( get( 'c' ) == 'vod_tv_categories' || get( 'c' ) == 'vod_tv_category' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=vod_tv" class="nav-link <?php if( get( 'c' ) == 'vod_tv' || get( 'c' ) == 'vod_tv_item' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>TV VoD</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'servers' || get( 'c' ) == 'server' ) { 
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item">
                                <a href="?c=servers" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-server"></i>
                                    <p>
                                        Servers
                                    </p>
                                </a>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'users' || get( 'c' ) == 'user' ) { 
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item">
                                <a href="?c=users" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Users &amp; Resellers
                                    </p>
                                </a>
                            </li>

                            <hr>

                            <?php 
                                if( get( 'c' ) == 'monitored_folders' || get( 'c' ) == 'open_connections' || get( 'c' ) == 'playlist_manager' || get( 'c' ) == 'playlist' || get( 'c' ) == 'banned_ips' || get( 'c' ) == 'banned_isps' || get( 'c' ) == 'rtmp_management' ) {
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item has-treeview <?php echo $menu['tree']; ?>">
                                <a href="#" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-crosshairs"></i>
                                    <p>
                                        Misc
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="?c=banned_ips" class="nav-link <?php if( get( 'c' ) == 'banned_ips' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Banned IPs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=banned_isps" class="nav-link <?php if( get( 'c' ) == 'banned_isps' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Banned ISPs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=monitored_folders" class="nav-link <?php if( get( 'c' ) == 'monitored_folders' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Monitored Folders</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=open_connections" class="nav-link <?php if( get( 'c' ) == 'open_connections' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Open Connections</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=playlist_manager" class="nav-link <?php if( get( 'c' ) == 'playlist_manager' || get( 'c' ) == 'playlist' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Playlist / M3U Manager</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=rtmp_management" class="nav-link <?php if( get( 'c' ) == 'rtmp_management' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>RTMP Management</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <?php 
                                if( get( 'c' ) == 'settings' || get( 'c' ) == 'backup_manager' ) { 
                                    $menu['active']     = 'active';
                                    $menu['tree']       = 'menu-open';
                                } else {
                                    $menu['active']     = '';
                                    $menu['tree']       = '';
                                }
                            ?>
                            <li class="nav-item has-treeview <?php echo $menu['tree']; ?>">
                                <a href="#" class="nav-link <?php echo $menu['active']; ?>">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        Settings
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="?c=backup_manager" class="nav-link <?php if( get( 'c' ) == 'backup_manager' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Backup Manager</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="?c=settings" class="nav-link <?php if( get( 'c' ) == 'settings' ) { echo 'active'; } ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Settings &amp; Licenses</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <?php
                $c = get( 'c' );
                switch ($c) {
                    // backup_manager
                    case "backup_manager":
                        backup_manager();
                        break;

                    // banned_ips
                    case "banned_ips":
                        banned_ips();
                        break;

                    // banned_isps
                    case "banned_isps":
                        banned_isps();
                        break;

                    // bouquets
                    case "bouquets":
                        bouquets();
                        break;

                    // bouquet
                    case "bouquet":
                        bouquet();
                        break;

                    // channels_247
                    case "channels_247":
                        channels_247();
                        break;

                    // channels_247_item
                    case "channels_247_item":
                        channels_247_item();
                        break;

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

                    // channel_icons
                    case "channel_icons":
                        channel_icons();
                        break;

                    // channel_topology_profiles
                    case "channel_topology_profiles":
                        channel_topology_profiles();
                        break;

                    // channel_topology_profile
                    case "channel_topology_profile":
                        channel_topology_profile();
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

                    // deploy
                    case "deploy":
                        deploy();
                        break;

                    // epg_sources
                    case "epg_sources":
                        epg_sources();
                        break;

                    // epg_source
                    case "epg_source":
                        epg_source();
                        break;

                    // license
                    case "license":
                        license();
                        break;

                    // mag_devices
                    case "mag_devices":
                        mag_devices();
                        break;

                    // monitored_folders
                    case "monitored_folders":
                        monitored_folders();
                        break;

                    // my_profile
                    case "my_profile":
                        my_profile();
                        break;

                    // open_connections
                    case "open_connections":
                        open_connections();
                        break;

                    // packages
                    case "packages":
                        packages();
                        break;

                    // package
                    case "package":
                        package();
                        break;

                    // playlist_manager
                    case "playlist_manager":
                        playlist_manager();
                        break;

                    // playlist
                    case "playlist":
                        playlist();
                        break;

                    // rtmp_management
                    case "rtmp_management":
                        rtmp_management();
                        break;

                    // staging
                    case "staging":
                        staging();
                        break;

                    // servers
                    case "servers":
                        servers();
                        break;

                    // server
                    case "server":
                        server();
                        break;

                    // settings
                    case "settings":
                        settings();
                        break;

                    // transcoding_profiles
                    case "transcoding_profiles":
                        transcoding_profiles();
                        break;

                    // transcoding_profile
                    case "transcoding_profile":
                        transcoding_profile();
                        break;

                    // template
                    case "template":
                        template();
                        break;

                    // users
                    case "users":
                        users();
                        break;

                    // user
                    case "user":
                        user();
                        break;

                    // vod
                    case "vod":
                        vod();
                        break;

                    // vod_item
                    case "vod_item":
                        vod_item();
                        break;

                    // vod_categories
                    case "vod_categories":
                        vod_categories();
                        break;

                    // vod_category
                    case "vod_category":
                        vod_category();
                        break;

                    // vod_tv
                    case "vod_tv":
                        vod_tv();
                        break;

                    // vod_tv_item
                    case "vod_tv_item":
                        vod_tv_item();
                        break;

                    // vod_tv_categories
                    case "vod_tv_categories":
                        vod_tv_categories();
                        break;

                    // vod_tv_category
                    case "vod_tv_category":
                        vod_tv_category();
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

            <?php function backup_manager() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $backups = glob( '/opt/stiliam-backups/*.gz' ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Backup Manager</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Backup Manager</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Backup Manager</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <a href="actions.php?a=backup_now" class="btn btn-primary btn-xs" onclick="overlay_show_until_reload();">Backup Now</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $backups[0] ) ) { ?>
                                                Database backups are stored on the main CMS server under the folder '/opt/stiliam-backups/'. You can upload, download and restore backups found in the folder. Please be aware that restoring a backup will result in the loss of all data added, updated or removed since the backup you wish to restore was created. A daily backup will be created at 01:00 GMT everyday.

                                                <br><br>

                                                <table id="table_backups" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="no-sort">Filename</th>
                                                            <th width="200px" class="no-sort">Created</th>
                                                            <th width="100px" class="no-sort">Size</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $backups as $backup ) {

                                                                $filename = str_replace( "/opt/stiliam-backups/", "", $backup );
                                                                $filesize = filesize( "/opt/stiliam-backups/".$filename );
                                                                $filesize = formatSizeUnits( $filesize );
                                                                $filedate = date( "F d Y H:i:s", filemtime( "/opt/stiliam-backups/".$filename ) );

                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$filename.'
                                                                        </td>
                                                                        <td>
                                                                            '.$filedate.'
                                                                        </td>
                                                                        <td>
                                                                            '.$filesize.'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="actions.php?a=backup_download&file='.$filename.'"><span class="btn btn-block btn-info btn-xs">Download</span></a>
                                                                                    <a class="dropdown-item" href="actions.php?a=backup_restore&file='.$filename.'"><span class="btn btn-block btn-success btn-xs" onclick="return confirm(\'This will overwrite all current data!!! \n\nAre you sure?\' )">Restore</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=backup_delete&file='.$filename.'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                You currently have no backups stored on the CMS server.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=bouquet_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="bouquet_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Bouquet</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: Live TV" required>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="type">Type</label>
                                        <select id="type" name="type" class="form-control form-control-sm">
                                            <option value="channel_247">24/7 Channels</option>
                                            <option value="live">Live Channels</option>
                                            <option value="vod">Movies VoD</option>
                                            <option value="vod_tv">TV VoD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function banned_ips() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $banned_ips = get_banned_ips(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Banned IPs</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Banned IPs</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Banned IPs</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#banned_ip_add_modal"><i class="fas fa-plus"></i> Add Banned IP</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $banned_ips[0]['id'] ) ) { ?>
                                                <table id="table_banned_ips" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="150px">IP</th>
                                                            <th class="no-sort">Reason</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $banned_ips as $banned_ip ) {
                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$banned_ip['ip_address'].'
                                                                        </td>
                                                                        <td>
                                                                            '.stripslashes( $banned_ip['reason'] ).'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a href="actions.php?a=banned_ip_delete&id='.$banned_ip['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                You currently have no banned IP addresses.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=banned_ip_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="banned_ip_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Banned IP</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="ip_address">IP Address</label>
                                        <input type="text" id="ip_address" name="ip_address" class="form-control form-control-sm" placeholder="example: 1.2.3.4" required>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="reason">Reason</label>
                                        <input type="text" id="reason" name="reason" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function banned_isps() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $banned_isps  = get_banned_isps(); ?>
                <?php $isp_names    = get_all_isps(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Banned ISPs</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Banned ISPs</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Banned ISPs</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#banned_isp_add_modal"><i class="fas fa-plus"></i> Add Banned ISP</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $banned_isps[0]['id'] ) ) { ?>
                                                <table id="table_banned_isps" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="300px">ISP Name</th>
                                                            <th class="no-sort">Reason</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $banned_isps as $banned_isp ) {
                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$banned_isp['isp_name'].'
                                                                        </td>
                                                                        <td>
                                                                            '.stripslashes( $banned_isp['reason'] ).'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a href="actions.php?a=banned_isp_delete&id='.$banned_isp['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                You currently have no banned ISPs.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=banned_isp_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="banned_isp_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Banned ISP</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="isp_name">ISP Name</label>
                                        <select class="form-control form-control-sm select2" id="isp_name" name="isp_name">
                                            <?php foreach( $isp_names as $isp_name ) { ?>
                                                <?php if( !empty( $isp_name['isp_name'] ) ) { ?>
                                                    <?php $isp_name['isp_name'] = str_replace( '"', '', $isp_name['isp_name'] ); ?>
                                                    <option value="<?php echo $isp_name['isp_name']; ?>"><?php echo stripslashes( $isp_name['isp_name'] ); ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- 
                                        <div class="form-group row mb-4">
                                            <label for="isp_name">ISP Name</label>
                                            <input type="text" id="isp_name" name="isp_name" class="form-control form-control-sm" placeholder="example: Virgin Media" required>
                                        </div>
                                    -->
                                    <div class="form-group row mb-4">
                                        <label for="reason">Reason</label>
                                        <input type="text" id="reason" name="reason" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function bouquets() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $bouquets = get_all_bouquets(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Bouquets</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Bouquets</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Bouquets</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#bouquet_add_modal"><i class="fas fa-plus"></i> Add Bouquet</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $bouquets[0]['id'] ) ) { ?>
                                                <table id="table_bouquets" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="150px" class="no-sort">Total Contents</th>
                                                            <th width="150px" class="no-sort">Type</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $bouquets as $bouquet ) {
                                                                // set type
                                                                if( $bouquet['type'] == 'channel_247' ) {
                                                                    $bouquet['type_tag'] = '<span class="btn btn-block btn-secondary btn-xs">24/7 Channels</span>';
                                                                } elseif( $bouquet['type'] == 'live' ) {
                                                                    $bouquet['type_tag'] = '<span class="btn btn-block btn-secondary btn-xs">Live Channels</span>';
                                                                } elseif( $bouquet['type'] == 'vod' ) {
                                                                    $bouquet['type_tag'] = '<span class="btn btn-block btn-secondary btn-xs">Movies VoD</span>';
                                                                } elseif( $bouquet['type'] == 'vod_tv' ) {
                                                                    $bouquet['type_tag'] = '<span class="btn btn-block btn-secondary btn-xs">TV VoD</span>';
                                                                } else {
                                                                    $bouquet['type_tag'] = '<span class="btn btn-block btn-secondary btn-xs">'.$bouquet['type'].'</span>';
                                                                }

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$bouquet['name'].'
                                                                        </td>
                                                                        <td>
                                                                            '.number_format( $bouquet['total_contents'] ).'
                                                                        </td>
                                                                        <td>
                                                                            '.$bouquet['type_tag'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=bouquet&id='.$bouquet['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=bouquet_delete&id='.$bouquet['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                You need to add at least one bouquet and then assign it to a <a href="?c=packages">Package</a> so that your customers can access content.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=bouquet_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="bouquet_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Bouquet</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: Live TV" required>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="type">Type</label>
                                        <select id="type" name="type" class="form-control form-control-sm">
                                            <option value="channel_247">24/7 Channels</option>
                                            <option value="live">Live Channels</option>
                                            <option value="vod">Movies VoD</option>
                                            <option value="vod_tv">TV VoD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function bouquet() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php
                    $bouquet      = bouquet_details( get( 'id' ) );

                    $bouquet['streams'] = array();

                    $query = $conn->query( "SELECT `content_id` FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' ORDER BY `order`, `content_id` ASC " );
                    $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

                    foreach($bouquet_contents as $bouquet_content) {
                        $bouquet['streams'][] = $bouquet_content['content_id'];
                    }
                
                    if( $bouquet['type'] == 'live' ) {
                        $query = $conn->query( "SELECT `id`, `title` FROM `channels` ORDER BY `title` " );
                        $streams = $query->fetchAll( PDO::FETCH_ASSOC );
                    }

                    if( $bouquet['type'] == 'channel_247' ) {
                        $query = $conn->query( "SELECT `id`, `title` FROM `channels_247` ORDER BY `title` " );
                        $streams = $query->fetchAll( PDO::FETCH_ASSOC );
                    }

                    if( $bouquet['type'] == 'vod' ) {
                        $query = $conn->query( "SELECT `id`, `title` FROM `vod` ORDER BY `title` " );
                        $streams = $query->fetchAll( PDO::FETCH_ASSOC );
                    }

                    if( $bouquet['type'] == 'vod_tv' ) {
                        $query = $conn->query( "SELECT `id`, `title` FROM `vod_tv` ORDER BY `title` " );
                        $streams = $query->fetchAll( PDO::FETCH_ASSOC );
                    }
                ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Bouquet</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=bouquets">Bouquets</a></li>
                                        <li class="breadcrumb-item active">Bouquet</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="actions.php?a=bouquet_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit Bouquet</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="first_name">Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?php echo $bouquet['name']; ?>" placeholder="example: Live TV">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=bouquets" class="btn btn-default">Back</a>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-6">
                                    <form action="actions.php?a=bouquet_content_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Bouquet Content</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row mb-4">
                                                            <select id="contents" name="contents[]" size="20" class="duallistbox" multiple="multiple">
                                                                <?php foreach( $streams as $stream )  { ?>
                                                                    <?php if( !in_array( $stream['id'], $bouquet['streams'] ) ) { ?>
                                                                        <option value="<?php echo $stream['id']; ?>"><?php echo stripslashes( $stream['title'] ) ?></option>
                                                                    <?php } else { ?>
                                                                        <option value="<?php echo $stream['id']; ?>" selected><?php echo stripslashes( $stream['title'] ) ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=bouquets" class="btn btn-default">Back</a>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-6">
                                    <form action="actions.php?a=bouquet_content_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Bouquet Order</h4>
                                            </div>
                                            <div class="card-body">
                                                <?php if( empty( $bouquet['streams'] ) ) { ?>
                                                    Please add at least one item to this bouquet.
                                                <?php } else { ?>
                                                    To change the order of the contents in this bouquet, drag and drop the line items into the desired order. Changes are saved in real time.

                                                    <br><br>

                                                    <table id="table_bouquet_content" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="no-sort" style="white-space: nowrap;">Title</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="row_position">
                                                            <?php
                                                                foreach($bouquet['streams'] as $key => $value) {
                                                                    if(!empty($value)) {
                                                                        $key = array_search( $value, array_column( $streams, 'id' ) );
                                                                        
                                                                        echo '
                                                                            <tr id="'.$streams[$key]['id'].'">
                                                                                <td>
                                                                                    '.stripslashes( $streams[$key]['title'] ).'
                                                                                </td>
                                                                            </tr>
                                                                        ';
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function channels() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $channels             = get_all_channels(); ?>
                <?php $servers              = get_all_servers(); ?>
                <?php $channel_categories   = get_all_channel_categories(); ?>
                <?php $bouquets             = get_all_bouquets(); ?>
                <?php $topology_profiles    = get_channel_topology_profiles(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Live Channels</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Live Channels</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <form id="channel_update_multi" action="actions.php?a=channels_multi_options" method="post">
                                <span id="multi_options" class="d-none">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Options</h4>
                                                    
                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#template_modal"><i class="fas fa-plus"></i> </button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group row mb-4">
                                                                <label for="multi_options_action">Action</label>
                                                                <select id="multi_options_action" name="multi_options_action" class="form-control form-control-sm" onchange="multi_options_select(this.value);">
                                                                    <option value="">Select an action</option>
                                                                    <option value="start">Start Selected Live Channels</option>
                                                                    <option value="restart">Restart Selected Live Channels</option>
                                                                    <option value="stop">Stop Selected Live Channels</option>
                                                                    <option value="add_to_bouquet">Add to Bouquet</option>
                                                                    <option value="change_category">Change Category</option>
                                                                    <option value="change_user_agent">Change User Agent</option>
                                                                    <option value="change_channel_topology">Change Channel Topology</option>
                                                                    <option value="enable_ondemand">Enable OnDemand</option>
                                                                    <option value="disable_ondemand">Disable OnDemand</option>
                                                                    <option value="enable_always_on">Set to Always On / Restream</option>
                                                                    <option value="enable_pass_thru">Set to Direct to Source</option>
                                                                    <optgroup label="Caution">
                                                                        <option value="delete">Delete Selected Live Channels</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_add_to_bouquet" class="form-group row mb-4 d-none">
                                                                <label for="bouquet_id">New Bouquet</label>
                                                                <select id="bouquet_id" name="bouquet_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $bouquets as $bouquet ) {
                                                                            if( $bouquet['type'] == 'live' ) {
                                                                                echo '<option value="'.$bouquet['id'].'">'.$bouquet['name'].'</option>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_change_category" class="form-group row mb-4 d-none">
                                                                <label for="category_id">New Category</label>
                                                                <select id="category_id" name="category_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $channel_categories as $channel_category ) {
                                                                            echo '<option value="'.$channel_category['id'].'">'.$channel_category['name'].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_change_channel_topology" class="form-group row mb-4 d-none">
                                                                <label for="profile_id">New Channel Topology Profile</label>
                                                                <select id="profile_id" name="profile_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $topology_profiles as $topology_profile ) {
                                                                            echo '<option value="'.$topology_profile['id'].'">'.$topology_profile['name'].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_change_user_agent" class="form-group row mb-4 d-none">
                                                                <label for="user_agent">New User Agent</label>
                                                                <input type="text" id="user_agent" name="user_agent" class="form-control form-control-sm" placeholder="example: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:77.0) Gecko/20100101 Firefox/77.0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm( 'Are you sure?' )">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Live Channels</h4>

                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <a href="actions.php?a=channels_start_all" class="btn btn-success btn-xs" onclick="return confirm( 'This action can take over 20+ minutes to complete depending on the number of channels and server specifications. \nWould you like to continue?' )"><i class="fas fa-play"></i> Start All</a>
                                                        <a href="actions.php?a=channels_stop_all" class="btn btn-danger btn-xs" onclick="return confirm( 'This action can take over 20+ minutes to complete depending on the number of channels and server specifications. \nWould you like to continue?' )"><i class="fas fa-stop"></i> Stop All</a>
                                                    </div>

                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#channel_import_modal"><i class="fas fa-plus"></i> Import Live Channel</button>
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#channel_add_modal"><i class="fas fa-plus"></i> Add Live Channel</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( count( $channels ) == 0 ) { ?>
                                                    You currently do not have any Live Channels added.
                                                <?php } else { ?>
                                                    <table id="table_channels" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th width="1px" class="no-sort">
                                                                    <input type="checkbox" id="checkAll" onclick="show_multi_options();">
                                                                </th>
                                                                <th width="50px" class="no-sort"></th>
                                                                <th>Title</th>
                                                                <th>Stats</th>
                                                                <th width="1px" class="no-sort">OnDemand</th>
                                                                <th width="1px">Status</th>
                                                                <th width="1px" class="no-sort"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach( $channels as $channel ) {
                                                                    // get channel_sources for webplayer
                                                                    $channel_sources          = get_all_channel_sources( $channel['id'] );

                                                                    // get the caregory for this channel
                                                                    foreach( $channel_categories as $channel_category ) {
                                                                        if( $channel['category_id'] == $channel_category['id'] ) {
                                                                            break;
                                                                        }
                                                                    }

                                                                    if( $channel['method'] == 'restream' ) {
                                                                        $channel['method_tag'] = '<span class="badge outline-badge-success badge" style="width: 100%;">Restream</span>';
                                                                    } elseif( $channel['method'] == 'direct' ) {
                                                                        $channel['method_tag'] = '<span class="badge outline-badge-primary" style="width: 100%;">Direct</span>';
                                                                    } else {
                                                                        $channel['method_tag'] = '<span class="badge outline-badge-info" style="width: 100%;">'.ucfirst( $channel['method'] ).'</span>';
                                                                    }

                                                                    if( $channel['method'] == 'restream' ) {
                                                                        if( $channel['ondemand'] == 'yes' ) {
                                                                            $channel['ondemand_tag'] = '<span class="badge outline-badge-primary">On Demand</span>';
                                                                        } elseif( $channel['ondemand'] == 'no' ) {
                                                                            $channel['ondemand_tag'] = '<span class="badge outline-badge-success">Always On</span>';
                                                                        } else {
                                                                            $channel['ondemand_tag'] = '<span class="badge outline-badge-info">'.ucfirst( $channel['ondemand'] ).'</span>';
                                                                        }
                                                                    } else {
                                                                        $channel['ondemand_tag'] = '';
                                                                    }

                                                                    if( $channel['method'] == 'restream' ) {
                                                                        if( $channel['status'] == 'online' ) {
                                                                            $channel['status_tag'] = '<span class="badge outline-badge-success">Active</span>';
                                                                        } elseif( $channel['status'] == 'starting' ) {
                                                                            $channel['status_tag'] = '<span class="badge outline-badge-warning">Starting</span>';
                                                                        } elseif( $channel['status'] == 'stopping' ) {
                                                                            $channel['status_tag'] = '<span class="badge outline-badge-warning">Stopping</span>';
                                                                        } elseif( $channel['status'] == 'offline' ) {
                                                                            $channel['status_tag'] = '<span class="badge outline-badge-danger">Offline</span>';
                                                                        } elseif( $channel['status'] == 'source_offline' ) {
                                                                            $channel['status_tag'] = '<span class="badge outline-badge-danger">Source Offline</span>';
                                                                        } else {
                                                                            $channel['status_tag'] = '<span class="badge outline-badge-info">'.ucfirst( $channel['status'] ).'</span>';
                                                                        }
                                                                    } else {
                                                                        $channel['status_tag'] = '';
                                                                    }

                                                                    // get current connections
                                                                    $channel['connections'] = count( connections_per_media( 'channel', $channel['id'] ) );

                                                                    // table css
                                                                    if( $channel['status'] == 'online' || $channel['method'] == 'direct' ) {
                                                                        $table_css = 'success';
                                                                    } elseif( $channel['status'] == 'offline' ) {
                                                                        $table_css = 'danger';
                                                                    } else {
                                                                        $table_css = 'warning';
                                                                    }

                                                                    // get first primary for some stats
                                                                    if( $channel['status'] == 'online' ) {
                                                                        $query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".$channel['id']."' AND `type` = 'secondary' AND `status` = 'online' " );
                                                                        $channel_server = $query->fetch( PDO::FETCH_ASSOC );
                                                                        if( isset( $channel_server['id'] ) ) {
                                                                            // convert json to array
                                                                            $channel_server['stats'] = json_decode( $channel_server['stats'], true );
                                                                            // calculate bandwidth
                                                                            $channel_server['stats']['bitrate'] = number_format( ( $channel_server['stats']['bitrate'] / 1e+6), 2).' Mbit';
                                                                        }
                                                                    }

                                                                    // output
                                                                    echo '
                                                                        <tr class="table-'.$table_css.'">
                                                                            <td>
                                                                                <input type="checkbox" class="chk" id="checkbox_'.$channel['id'].'" name="channel_ids[]" value="'.$channel['id'].'" onclick="show_multi_options();">
                                                                            </td>
                                                                            <td valign="middle">
                                                                                <center>
                                                                                    '.( !empty( $channel['icon'])?'<img data-src="actions.php?a=http_proxy&remote_file='.base64_encode( $channel['icon'] ).'" loading="lazy" class="lazyload" alt="'.$channel['title'].' Icon" width="50px">' : '' ).'
                                                                                </center>
                                                                            </td>
                                                                            <td>
                                                                                <strong>Name: </strong> '.stripslashes( $channel['title'] ).' <br>
                                                                                <strong>Category: </strong> '.stripslashes( $channel_category['name'] ).'
                                                                            </td>
                                                                            <td>
                                                                                '.( $channel['method'] == 'restream' && $channel['status'] == 'online' && isset( $channel_server['id'] ) ? '
                                                                                    <div class="row">
                                                                                        <div class="col-lg-3">
                                                                                            <strong>Uptime:</strong> '.( !empty( $channel['uptime'] )?uptime( $channel['uptime'] ):'' ).' <br>
                                                                                            <strong>Connections:</strong> '.number_format( $channel['connections'] ).'
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <strong>Bitrate:</strong> '.( !empty( $channel_server['stats']['bitrate'] ) ? $channel_server['stats']['bitrate']:'' ).' <br>
                                                                                            <strong>FPS:</strong> '.round( $channel_server['stats']['fps'] ).'
                                                                                        </div>
                                                                                        <div class="col-lg-3">
                                                                                            <strong>Video Codec:</strong> '.strtoupper( $channel_server['stats']['video_codec'] ).' <br>
                                                                                            <strong>Audio Codec:</strong> '.strtoupper( $channel_server['stats']['audio_codec'] ).'
                                                                                        </div>
                                                                                    </div>
                                                                                ' : '' ).'
                                                                            </td>
                                                                            <td>
                                                                                '.( $channel['ondemand'] == 'no' ? '<a style="color: white;" class="btn btn-block btn-danger btn-xs">No</a>' : '<a style="color: white;" class="btn btn-block btn-success btn-xs">Yes</a>' ).'
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group-prepend">
                                                                                    '.( $channel['method'] == 'restream' && $channel['status'] == 'online' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-success btn-xs dropdown-toggle" data-toggle="dropdown">Online</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $channel['method'] == 'restream' && $channel['status'] == 'starting' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Starting</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $channel['method'] == 'restream' && $channel['status'] == 'stopping' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Stopping</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $channel['method'] == 'restream' && $channel['status'] == 'offline' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">Offline</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $channel['method'] == 'restream' && $channel['status'] == 'restarting' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Restarting</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $channel['method'] == 'direct' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-info btn-xs dropdown-toggle" data-toggle="dropdown">Direct</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    
                                                                                    <div class="dropdown-menu">
                                                                                        '.( $channel['method'] == 'restream' ? '
                                                                                            '.( $channel['status'] == 'online' ? 
                                                                                                '<a class="dropdown-item" href="actions.php?a=channel_restart&id='.$channel['id'].'" onclick="return confirm( \'Are you sure?\' )"><span class="btn btn-block btn-warning btn-xs">Restart</span></a>
                                                                                                <a class="dropdown-item" href="actions.php?a=channel_stop&id='.$channel['id'].'" onclick="return confirm( \'Are you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Stop</span></a>' : '' 
                                                                                            ).'
                                                                                            '.( $channel['status'] == 'offline' ? '<a class="dropdown-item" href="actions.php?a=channel_start&id='.$channel['id'].'"><span class="btn btn-block btn-success btn-xs">Start</span></a>' : '' 
                                                                                            ).'
                                                                                            '.( $channel['status'] == 'starting' || $channel['status'] == 'stopping' || $channel['status'] == 'restarting' ? '<a class="dropdown-item" href="actions.php?a=channel_stop&id='.$channel['id'].'" onclick="return confirm( \'Are you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Stop</span></a>' : '' 
                                                                                            ).'
                                                                                        ' : '<a class="dropdown-item">No options available</a>' ).'
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <div class="input-group-prepend">
                                                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                        Actions
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <a class="dropdown-item" data-toggle="modal" data-target="#webplayer_modal" onclick="set_webplayer_source( \''.$channel_sources[0]['source'].'\' )"><span class="btn btn-block btn-secondary btn-xs">Webplayer</span></a>
                                                                                        <a class="dropdown-item" href="?c=channel&id='.$channel['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a href="actions.php?a=channel_delete&id='.$channel['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=channel_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="channel_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Live Channel</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="title">Title</label>
                                        <input type="text" id="title" name="title" class="form-control form-control-sm" placeholder="example: BBC One HD" required>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="category_id">Category</label>
                                        <select class="form-control form-control-sm" id="category_id" name="category_id">
                                            <?php foreach( $channel_categories as $channel_category ) { ?>
                                                <option value="<?php echo $channel_category['id']; ?>"><?php echo stripslashes( $channel_category['name'] ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="icon">Channel Icon URL</label>
                                        <input type="text" class="form-control form-control-sm" id="icon" name="icon" placeholder="http://domain.com/icons/channel_id.png">
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="epg_xml_id">EPG ID</label>
                                        <input type="text" class="form-control form-control-sm" id="epg_xml_id" name="epg_xml_id">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="actions.php?a=channel_import" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="modal fade" id="channel_import_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Import Live Channel</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="category_id">Select Playlist</label>
                                        <input type="file" class="form-control form-control-sm" name="fileToUpload" id="fileToUpload" accept=".m3u,.m3u8" required>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="category_id">Category</label>
                                        <select class="form-control form-control-sm" id="category_id" name="category_id">
                                            <?php foreach( $channel_categories as $channel_category ) { ?>
                                                <option value="<?php echo $channel_category['id']; ?>"><?php echo stripslashes( $channel_category['name'] ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group row">
                                        <label for="channel_topology">Input Server</label>
                                        <select class="form-control form-control-sm" id="primary_server_id" name="primary_server_id">
                                            <?php foreach( $servers as $server ) { ?>
                                                <?php if( $server['type'] == 'streamer' ) { ?>
                                                    <option value="<?php echo $server['id']; ?>"><?php echo stripslashes( $server['name'] ); ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group row">
                                        <label for="channel_topology">Output Server</label>
                                        <select class="form-control form-control-sm" id="secondary_server_id" name="secondary_server_id">
                                            <?php foreach( $servers as $server ) { ?>
                                                <?php if( $server['type'] == 'streamer' ) { ?>
                                                    <option value="<?php echo $server['id']; ?>"><?php echo stripslashes( $server['name'] ); ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="auto_start">Auto Start Channels</label>
                                        <select class="form-control form-control-sm" id="auto_start" name="auto_start">
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function channel() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $channel                  = get_channel_details( get( 'id' ) ); ?>
                <?php $channel_sources          = get_all_channel_sources( get( 'id' ) ); ?>
                <?php $servers                  = get_all_servers( ); ?>

                <?php $transcoding_profiles     = transcoding_profiles_details(); ?>
                <?php $epg_xml_ids              = get_all_epg_channel_ids(); ?>

                <?php
                    if( !empty( $channel['topology'] ) ) {
                        $topology   = unserialize( $channel['topology'] );
                    } else {
                        $topology   = array();
                    }

                    $primary_servers = $servers;
                    foreach( $primary_servers as $server_key => $server_value ) {
                        foreach( $topology as $topology_server ) {
                            if( $topology_server['server_id'] == $server_value['id'] ) {
                                unset( $primary_servers[$server_key] );
                            }
                        }
                    }

                    $secondary_servers = $servers;
                    foreach( $secondary_servers as $server_key => $server_value ) {
                        foreach( $topology as $topology_server ) {
                            if( $topology_server['server_id'] == $server_value['id'] ) {
                                unset( $secondary_servers[$server_key] );
                            }
                        }
                    }
                ?>

                <?php $channel_categories   = get_all_channel_categories(); ?>

                <?php
                    $query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".get( 'id' )."' " );
                    $channel_servers = $query->fetchAll( PDO::FETCH_ASSOC );
                ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Channel</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=channels">Live Channels</a></li>
                                        <li class="breadcrumb-item active">Channel</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <?php if( $channel['stream'] == 'yes' && isset( $channel_servers[0] ) ) { ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Channel Stats</h4>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <table id="table_channel_server_stats" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th width="1px" class="no-sort">Status</th>
                                                                <th class="no-sort">Server</th>
                                                                <th width="1px" class="no-sort">Type</th>
                                                                <th width="1px" class="no-sort">Resolution</th>
                                                                <th width="1px" class="no-sort">Bitrate</th>
                                                                <th width="1px" class="no-sort">Video</th>
                                                                <th width="1px" class="no-sort">Speed</th>
                                                                <th width="1px" class="no-sort">FPS</th>
                                                                <th width="1px" class="no-sort">Aspect</th>
                                                                <th width="1px" class="no-sort">Audio</th>
                                                                <th width="1px" class="no-sort">Uptime</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach( $channel_servers as $channel_server ) {
                                                                    $channel_server['stats'] = json_decode( $channel_server['stats'], true );

                                                                    // server type
                                                                    if( $channel_server['type'] == 'primary' ) {
                                                                        foreach( $servers as $server ) {
                                                                            if( $channel_server['primary_server_id'] == $server['id'] ) {
                                                                                $channel_server['server'] = $server;
                                                                                break;
                                                                            }
                                                                        }
                                                                    } elseif( $channel_server['type'] == 'secondary' ) {
                                                                        foreach( $servers as $server ) {
                                                                            if( $channel_server['secondary_server_id'] == $server['id'] ) {
                                                                                $channel_server['server'] = $server;
                                                                                break;
                                                                            }
                                                                        }
                                                                    }

                                                                     // table css
                                                                    if( $channel_server['status'] == 'online' ) {
                                                                        $table_css = 'success';
                                                                    } elseif( $channel_server['status'] == 'offline' ) {
                                                                        $table_css = 'danger';
                                                                    } else {
                                                                        $table_css = 'warning';
                                                                    }

                                                                    // calculate bandwidth
                                                                    if( isset( $channel_server['stats']['bitrate'] ) ) {
                                                                        $channel_server['stats']['bitrate']         = number_format( ( $channel_server['stats']['bitrate'] / 1e+6), 2).' Mbit';
                                                                        $channel_server['stats']['uptime']          = uptime( $channel_server['stats']['uptime'] );
                                                                    } else {
                                                                        $channel_server['stats']['bitrate']         = '';
                                                                        $channel_server['stats']['video_codec']     = '';
                                                                        $channel_server['stats']['audio_codec']     = '';
                                                                        $channel_server['stats']['speed']           = '';
                                                                        $channel_server['stats']['fps']             = '';
                                                                        $channel_server['stats']['aspect_ratio']    = '';
                                                                        $channel_server['stats']['uptime']          = '';
                                                                    }

                                                                    if( $channel_server['type'] == 'primary' ) {
                                                                        $channel_server_type = 'Input Server';
                                                                    } elseif( $channel_server['type'] == 'secondary' ) {
                                                                        $channel_server_type = 'Output Server';
                                                                    }

                                                                    // resolution
                                                                    if( isset( $channel_server['stats']['height'] ) && !empty( $channel_server['stats']['height'] ) ) {
                                                                        $channel_server['stats']['resolution']          = $channel_server['stats']['height'].'p';
                                                                    } else {
                                                                        $channel_server['stats']['resolution']          = '';
                                                                    }

                                                                    // output
                                                                    echo '
                                                                        <tr class="table-'.$table_css.'">
                                                                            <td>
                                                                                '.( $channel_server['status'] == 'online' ? 
                                                                                    '<a style="color: white;" class="btn btn-block btn-success btn-xs">Online</a>' : 
                                                                                    '' 
                                                                                ).'
                                                                                '.( $channel_server['status'] == 'offline' ? 
                                                                                    '<a style="color: white;" class="btn btn-block btn-danger btn-xs">Offline</a>' : 
                                                                                    '' 
                                                                                ).'
                                                                                '.( $channel_server['status'] == 'starting' ? 
                                                                                    '<a style="color: white;" class="btn btn-block btn-warning btn-xs">Starting</a>' : 
                                                                                    '' 
                                                                                ).'
                                                                                '.( $channel_server['status'] == 'stoping' ? 
                                                                                    '<a style="color: white;" class="btn btn-block btn-warning btn-xs">Stopping</a>' : 
                                                                                    '' 
                                                                                ).'
                                                                                '.( $channel_server['status'] == 'restarting' ? 
                                                                                    '<a style="color: white;" class="btn btn-block btn-warning btn-xs">Restarting</a>' : 
                                                                                    '' 
                                                                                ).'
                                                                            </td>
                                                                            <td>
                                                                                '.$channel_server['server']['name'].'
                                                                            </td>
                                                                            <td>
                                                                                <span class="right badge badge-info">'.$channel_server_type.'</span>
                                                                            </td>
                                                                            '.( $channel_server['status'] == 'online' ?
                                                                                '
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.$channel_server['stats']['resolution'].'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.$channel_server['stats']['bitrate'].'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.strtoupper( str_replace( array( '_', '-' ), ' ', $channel_server['stats']['video_codec'] ) ).'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.$channel_server['stats']['speed'].'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.round( $channel_server['stats']['fps'] ).'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.$channel_server['stats']['aspect_ratio'].'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.strtoupper( str_replace( array( '_', '-' ), ' ', $channel_server['stats']['audio_codec'] ) ).'</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="right badge badge-info">'.$channel_server['stats']['uptime'].'</span>
                                                                                    </td>
                                                                                ' : '
                                                                                    <td colspan="9">
                                                                                    </td>
                                                                                ' ).'
                                                                        </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <form action="actions.php?a=channel_update" method="post" class="">
                                            <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                            
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit Channel</h4>

                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <?php if( $channel['method'] == 'restream' ) { ?>
                                                                <?php if( $channel['status'] == 'offline' ) { ?>
                                                                    <a href="actions.php?a=channel_start&id=<?php echo $channel['id']; ?>" class="btn btn-success btn-xs"><i class="fas fa-play"></i> Start</a>
                                                                <?php } else { ?>
                                                                    <a class="btn btn-default btn-xs"><i class="fas fa-play"></i> Start</a>
                                                                <?php } ?>

                                                                <?php if( $channel['status'] == 'online' ) { ?>
                                                                    <a href="actions.php?a=channel_stop&id=<?php echo $channel['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm( 'Are you sure?' )" style="color: white;"><i class="fas fa-stop"></i> Stop</a>
                                                                <?php } else { ?>
                                                                    <a class="btn btn-default btn-xs"><i class="fas fa-stop"></i> Stop</a>
                                                                <?php } ?>

                                                                <?php if( $channel['status'] == 'online' ) { ?>
                                                                    <a href="actions.php?a=channel_restart&id=<?php echo $channel['id']; ?>" class="btn btn-warning btn-xs" onclick="return confirm( 'Are you sure?' )"><i class="fas fa-sync"></i> Restart</a>
                                                                <?php } else { ?>
                                                                    <a class="btn btn-default btn-xs"><i class="fas fa-sync"></i> Restart</a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="title">Title</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" class="form-control form-control-sm" id="title" name="title" value="<?php echo $channel['title']; ?>" placeholder="Channel 1" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="icon">Channel Icon URL</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" class="form-control form-control-sm" id="icon" name="icon" value="<?php echo $channel['icon']; ?>" placeholder="example: http://domain.com/icons/channel_id.png">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="category_id">Category</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="category_id" name="category_id" class="form-control form-control-sm">
                                                                        <?php if( is_array( $channel_categories ) && isset( $channel_categories[0] ) ) { foreach( $channel_categories as $channel_category ) { ?>
                                                                            <option value="<?php echo $channel_category['id']; ?>" <?php if( $channel_category['id'] == $channel['category_id'] ) { echo 'selected'; } ?> >
                                                                                <?php echo $channel_category['name']; ?>
                                                                            </option>
                                                                        <?php } } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            
                                                        <div class="col-lg-6">
                                                            <label for="epg_xml_id">EPG ID</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="epg_xml_id" name="epg_xml_id" class="form-control form-control-sm select2">
                                                                        <option value="" <?php if( $channel['epg_xml_id'] == '' ) { echo 'selected'; } ?> >No EPG</option>
                                                                        <?php if( is_array( $epg_xml_ids ) && isset( $epg_xml_ids[0] ) ) { foreach( $epg_xml_ids as $epg_xml_id ) { ?>
                                                                            <option value="<?php echo $epg_xml_id['xml_id']; ?>" <?php if( $epg_xml_id['xml_id'] == $channel['epg_xml_id'] ) { echo 'selected'; } ?> >
                                                                                <?php echo $epg_xml_id['xml_id']; ?>
                                                                            </option>
                                                                        <?php } } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="method">Transport Type</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="method" name="method" class="form-control form-control-sm" onchange="direct_or_restream(this);">
                                                                        <option value="restream" <?php if($channel['method'] == 'restream' ) { echo 'selected'; } ?> >Restream via CMS</option>
                                                                        <option value="direct" <?php if($channel['method'] == 'direct' ) { echo 'selected'; } ?> >Direct to Source</option>
                                                                    </select>
                                                                    <!-- <small>Should Stiliam process the incoming stream and distribute it to your customers or should we pass the source URL directly to the customer.</small> -->
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="method">OnDemand</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="ondemand" name="ondemand" class="form-control form-control-sm">
                                                                        <option value="no" <?php if( $channel['ondemand'] == 'no' ) { echo 'selected'; } ?> >No</option>
                                                                        <option value="yes" <?php if( $channel['ondemand'] == 'yes' ) { echo 'selected'; } ?> >Yes</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- direct to source options -->
                                                    <span id="direct_options" class="<?php if( $channel['method'] == 'restream' ) { echo "d-none"; } ?>">
                                                        This channel is configured to pass requests directly to the source. This means all connection requests bypass the Stiliam CMS and each customer makes their own connection. This is only recommended for public sources that can be found freely online by anyone. Any internal, proprietary or paid sources should not be set as Direct to Source to protect the security of your source.
                                                    </span>

                                                    <span id="restream_options" class="<?php if( $channel['method'] == 'direct' ) { echo "d-none"; } ?>">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <label for="user_agent">User Agent</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <input type="text" class="form-control form-control-sm" id="user_agent" name="user_agent" value="<?php echo $channel['user_agent']; ?>" placeholder="example: Stiliam CMS">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <label for="ffmpeg_re">Video Read Native</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="ffmpeg_re" name="ffmpeg_re" class="form-control form-control-sm">
                                                                            <option value="yes" <?php if($channel['ffmpeg_re'] == 'yes' ) { echo 'selected'; } ?> >Yes</option>
                                                                            <option value="no" <?php if($channel['ffmpeg_re'] == 'no' ) { echo 'selected'; } ?> >No</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label for="transcoding_profile_id">Transcode Profile</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="transcoding_profile_id" name="transcoding_profile_id" class="form-control form-control-sm">
                                                                            <option <?php if( $channel['transcoding_profile_id'] == '' ) { echo"selected"; } ?> value="">No Transcoding</option>
                                                                            <?php foreach( $transcoding_profiles as $transcoding_profile ) { ?>
                                                                                <option <?php if( $channel['transcoding_profile_id'] == $transcoding_profile['id'] ) { echo"selected"; } ?> value="<?php echo $transcoding_profile['id']; ?>"><?php echo $transcoding_profile['name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>

                                                                        <small>Transcoding applies to Input Servers only. Output Servers will copy / restream from their assigned Input Server.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="?c=channels" class="btn btn-default">Back</a>
                                                    <a href="actions.php?a=channel_delete&id=<?php echo $channel['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Channel Sources</h4>
                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#channel_source_add_modal"><i class="fas fa-plus"></i> Add Source</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( !isset( $channel_sources[0]['id'] ) ) { ?>
                                                    You need to add at least one source for this channel before streaming can be started.
                                                <?php } else { ?>
                                                    To change the order of your Channel Sources, drag and drop the line items into the desired order. Changes are saved in real time but a channel restart will be needed for changes to take effect. 
                                                    <table id="table_channel_sources" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="no-sort" style="white-space: nowrap;">Source</th>
                                                                <th width="1px" class="no-sort"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="row_position">
                                                            <?php
                                                                usort( $channel_sources, function( $a, $b ) {
                                                                    return $a['order'] <=> $b['order'];
                                                                });

                                                                foreach( $channel_sources as $channel_source ) {
                                                                    // output
                                                                    echo '
                                                                        <tr id="'.$channel_source['id'].'">
                                                                            <td>
                                                                                <input type="text" class="form-control form-control-sm" id="source[]" name="source[]" value="'.stripslashes( $channel_source['source'] ).'" onClick="this.setSelectionRange(0, this.value.length)" readonly>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <div class="input-group-prepend">
                                                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                        Actions
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <a href="actions.php?a=channel_source_delete&id='.$channel_source['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <span id="topology_options" class="<?php if( $channel['method'] == 'direct' ) { echo "d-none"; } ?>">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Channel Topology</h4>
                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#channel_topology_add_primary_modal"><i class="fas fa-plus"></i> Add Input Server</button>
                                                            <?php
                                                                foreach( $topology as $topology_bit ) {
                                                                    if( $topology_bit['type'] == 'primary' ) {
                                                                        echo '<button type="button" class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#channel_topology_add_secondary_modal"><i class="fas fa-plus"></i> Add Output Server</button>';
                                                                        break;
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div id="chart-container" style="text-align: center; width: 100%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=channel_source_add" method="post" class="form-horizontal">
                    <input type="hidden" id="channel_id" name="channel_id" value="<?php echo get( 'id' ); ?>">
                    <div class="modal fade" id="channel_source_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Source</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="source">Source URL</label>
                                        <input type="text" class="form-control form-control-sm" id="source" name="source" placeholder="http://domain.com/channel/stream.m3u8" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="actions.php?a=channel_topology_add&type=primary" method="post" class="forms-sample">
                    <input type="hidden" id="channel_id" name="channel_id" value="<?php echo get( 'id' ); ?>">

                    <div class="modal fade" id="channel_topology_add_primary_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Input Server</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    This server will directly connect to the source. You can have multiple Input servers connecting to the same source if you desire but each new Input server will make its own connection to the source. You will need to add at least one Output server next as customers are not permitted to connect to any Input server directly.
                                    <hr>
                                    <div class="form-group row mb-4">
                                        <label for="primary_server_id">Select Input Server</label>
                                        <select class="form-control form-control-sm" id="serveprimary_server_idr_id" name="primary_server_id">
                                            <?php foreach( $primary_servers as $server ) { ?>
                                                <?php if( $server['type'] == 'streamer' ) { ?>
                                                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="actions.php?a=channel_topology_add&type=secondary" method="post" class="forms-sample">
                    <input type="hidden" id="channel_id" name="channel_id" value="<?php echo get( 'id' ); ?>">

                    <div class="modal fade" id="channel_topology_add_secondary_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Output Server</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Output servers connect to an Input server for this channel and not the source directly. This will build a load balancer setup for your channel distribution. You can add multiple Output servers to add additional load balancing capacity.
                                    <hr>
                                    <div class="form-group row mb-4">
                                        <label for="primary_server_id">Select Input Server</label>
                                        <select class="form-control form-control-sm" id="primary_server_id" name="primary_server_id">
                                            <?php foreach( $topology as $server ) { ?>
                                                <?php if( $server['type'] == 'primary' ) { ?>
                                                    <option value="<?php echo $server['server_id']; ?>"><?php echo $server['title']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <?php foreach( $secondary_servers as $server ) { ?>
                                            <?php if( $server['type'] == 'streamer' ) { ?>
                                                <?php $remaining_secondary_servers[] = $server; ?>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if( isset( $remaining_secondary_servers ) && !empty( $remaining_secondary_servers ) ) { ?>
                                            <label for="secondary_server_id">Select Output Server</label>
                                            <select class="form-control form-control-sm" id="secondary_server_id" name="secondary_server_id">
                                                <?php foreach( $remaining_secondary_servers as $server ) { ?>
                                                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            No available Output servers.
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function channel_categories() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $channel_categories = get_all_channel_categories(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Live Channel Categories</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Live Channel Categories</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Live Channel Categories</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#category_add_modal"><i class="fas fa-plus"></i> Add Category</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $channel_categories[0]['id'] ) ) { ?>
                                                <table id="table_channel_categories" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $channel_categories as $channel_category ) {
                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$channel_category['name'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=channel_category&id='.$channel_category['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    '.( $channel_category['id'] != '1' ? '<div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=channel_category_delete&id='.$channel_category['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>' : '' ).'
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                Please add at least one category.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=channel_category_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="category_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Live Channel Category</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="example: UK FTA Channels" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function channel_category() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $channel_category      = channel_category_details( get( 'id' ) ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Live Channel Category</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=channel_categories">Live Channel Categories</a></li>
                                        <li class="breadcrumb-item active">Live Channel Category</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="actions.php?a=channel_category_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit Live Channel Category</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="name">Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?php echo $channel_category['name']; ?>" placeholder="example: UK FTA Channels">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=channel_categories" class="btn btn-default">Back</a>
                                                <?php if( $channel_category != 1 ) { ?><a href="actions.php?a=channel_category_delete&id=<?php echo $channel_category['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a><?php } ?>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function channel_topology_profiles() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $profiles      = get_channel_topology_profiles(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Channel Topology Profiles</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Channel Topology Profiles</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Channel Topology Profiles</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#channel_topology_profile_add_modal"><i class="fas fa-plus"></i> Add Channel Topology Profile</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $profiles[0]['id'] ) ) { ?>
                                                <table id="table_channel_topology_profiles" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach($profiles as $profile) {
                                                                $profile_data = json_decode( $profile['data'], true );

                                                                // output                                                            
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$profile['name'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=channel_topology_profile&id='.$profile['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=channel_topology_profile_delete&id='.$profile['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                Add your first Channel Topology Profile to get started by clicking Add Channel Topology Profile.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=channel_topology_profile_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="channel_topology_profile_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Channel Topology Profile</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: Profile 1" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function channel_topology_profile() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $profile                  = get_channel_topology_profile( get( 'id' ) ); ?>
                
                <?php $servers                  = get_all_servers( ); ?>

                <?php
                    if( !empty( $profile['data'] ) ) {
                        $topology   = unserialize( $profile['data'] );
                    } else {
                        $topology   = array();
                    }

                    $primary_servers = $servers;
                    foreach( $primary_servers as $server_key => $server_value ) {
                        foreach( $topology as $topology_server ) {
                            if( $topology_server['server_id'] == $server_value['id'] ) {
                                unset( $primary_servers[$server_key] );
                            }
                        }
                    }

                    $secondary_servers = $servers;
                    foreach( $secondary_servers as $server_key => $server_value ) {
                        foreach( $topology as $topology_server ) {
                            if( $topology_server['server_id'] == $server_value['id'] ) {
                                unset( $secondary_servers[$server_key] );
                            }
                        }
                    }
                ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Channel Topology Profiles</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=channels">Channel Topology Profiles</a></li>
                                        <li class="breadcrumb-item active">Channel Topology Profile</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-3">
                                    <form action="actions.php?a=channel_topology_profile_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit Profile</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="first_name">Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?php echo $profile['name']; ?>" placeholder="example: Profile 1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=channels_topology_profiles" class="btn btn-default">Back</a>
                                                <a href="actions.php?a=channel_topology_profile_delete&id=<?php echo $profile['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Channel Topology Profile</h4>
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#channel_topology_add_primary_modal"><i class="fas fa-plus"></i> Add Input Server</button>
                                                    <?php
                                                        foreach( $topology as $topology_bit ) {
                                                            if( $topology_bit['type'] == 'primary' ) {
                                                                echo '<button type="button" class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#channel_topology_add_secondary_modal"><i class="fas fa-plus"></i> Add Output Server</button>';
                                                                break;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <div id="chart-container" style="text-align: center; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=channel_topology_profile_add_asset&type=primary" method="post" class="forms-sample">
                    <input type="hidden" id="profile_id" name="profile_id" value="<?php echo get( 'id' ); ?>">

                    <div class="modal fade" id="channel_topology_add_primary_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Input Server</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    This server will directly connect to the source. You can have multiple Input servers connecting to the same source if you desire but each new Input server will make its own connection to the source. You will need to add at least one Output server next as customers are not permitted to connect to any Input server directly.
                                    <hr>
                                    <div class="form-group row mb-4">
                                        <label for="primary_server_id">Select Input Server</label>
                                        <select class="form-control form-control-sm" id="serveprimary_server_idr_id" name="primary_server_id">
                                            <?php foreach( $primary_servers as $server ) { ?>
                                                <?php if( $server['type'] == 'streamer' ) { ?>
                                                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="actions.php?a=channel_topology_profile_add_asset&type=secondary" method="post" class="forms-sample">
                    <input type="hidden" id="profile_id" name="profile_id" value="<?php echo get( 'id' ); ?>">

                    <div class="modal fade" id="channel_topology_add_secondary_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Output Server</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Output servers connect to an Input server for this channel and not the source directly. This will build a load balancer setup for your channel distribution. You can add multiple Output servers to add additional load balancing capacity.
                                    <hr>
                                    <div class="form-group row mb-4">
                                        <label for="primary_server_id">Select Input Server</label>
                                        <select class="form-control form-control-sm" id="primary_server_id" name="primary_server_id">
                                            <?php foreach( $topology as $server ) { ?>
                                                <?php if( $server['type'] == 'primary' ) { ?>
                                                    <option value="<?php echo $server['server_id']; ?>"><?php echo $server['title']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <?php foreach( $secondary_servers as $server ) { ?>
                                            <?php if( $server['type'] == 'streamer' ) { ?>
                                                <?php $remaining_secondary_servers[] = $server; ?>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if( isset( $remaining_secondary_servers ) && !empty( $remaining_secondary_servers ) ) { ?>
                                            <label for="secondary_server_id">Select Output Server</label>
                                            <select class="form-control form-control-sm" id="secondary_server_id" name="secondary_server_id">
                                                <?php foreach( $remaining_secondary_servers as $server ) { ?>
                                                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            No available Output servers.
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function channels_247() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php
                    $query = $conn->query( "SELECT * FROM `channels_247` ORDER BY `title` " );
                    $vods = $query->fetchAll( PDO::FETCH_ASSOC );
                    $count = 1;
                ?>

                <?php $servers              = get_all_servers(); ?>
                <?php $bouquets             = get_all_bouquets(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">24/7 Channels</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">24/7 Channels</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <form id="channel_update_multi" action="actions.php?a=channels_247_multi_options" method="post">
                                <span id="multi_options" class="d-none">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Options</h4>
                                                    
                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#template_modal"><i class="fas fa-plus"></i> </button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group row mb-4">
                                                                <label for="multi_options_action">Action</label>
                                                                <select id="multi_options_action" name="multi_options_action" class="form-control form-control-sm" onchange="multi_options_247_select(this.value);">
                                                                    <option value="">Select an action</option>
                                                                    <option value="start">Start Selected 24/7 Channels</option>
                                                                    <option value="stop">Stop Selected 24/7 Channels</option>
                                                                    <option value="add_to_bouquet">Add to Bouquet</option>
                                                                    <optgroup label="Caution">
                                                                        <option value="delete">Delete Selected 24/7 Channels</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_add_to_bouquet" class="form-group row mb-4 d-none">
                                                                <label for="bouquet_id">New Bouquet</label>
                                                                <select id="bouquet_id" name="bouquet_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $bouquets as $bouquet ) {
                                                                            if( $bouquet['type'] == 'channel_247' ) {
                                                                                echo '<option value="'.$bouquet['id'].'">'.$bouquet['name'].'</option>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm( 'Are you sure?' )">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">24/7 Channels</h4>

                                                <div class="card-tools">
                                                    <?php if( count( $vods ) != 0 ) { ?>
                                                        <div class="btn-group">
                                                            <a href="actions.php?a=channels_247_delete_all" class="btn btn-danger btn-xs" onclick="return confirm( 'Are you sure?' )"><i class="fas fa-trash"></i> Delete All</a>
                                                        </div>

                                                        <div class="btn-group">
                                                            <a href="actions.php?a=channels_247_start_stop_all&action=start" class="btn btn-success btn-xs" onclick="return confirm( 'This action could take upto 20+ minutes to complete depending on the size of your library. \nWould you like to continue?' )"><i class="fas fa-play"></i> Start All</a>
                                                            <a href="actions.php?a=channels_247_start_stop_all&action=stop" class="btn btn-danger btn-xs" onclick="return confirm( 'This action could take upto 20+ minutes to complete depending on the size of your library. \nWould you like to continue?' )"><i class="fas fa-stop"></i> Stop All</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( count( $vods ) == 0 ) { ?>
                                                    To create a 24/7 Channel you can head over to <a href="?c=vod_tv">TV VoD</a> and select which TV box sets you want to also use as a 24/7 Channel. 
                                                <?php } else { ?>
                                                    <table id="table_channels_247" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th width="1px" class="no-sort">
                                                                    <input type="checkbox" id="checkAll" onclick="show_multi_options();">
                                                                </th>
                                                                <th>Title</th>
                                                                <th width="1px" class="no-sort">Episodes</th>
                                                                <th width="" class="no-sort">Server</th>
                                                                <th width="100px" class="no-sort">Uptime</th>
                                                                <th width="1px">Status</th>
                                                                <th width="1px" class="no-sort"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach( $vods as $vod ) {
                                                                    // set default poster if needed
                                                                    if( empty( $vod['poster'] ) || $vod['poster'] == 'N/A' ) {
                                                                        $vod['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
                                                                    }

                                                                    // clean up the title
                                                                    $vod['title'] = stripslashes( $vod['title'] );
                                                                    $vod['title'] = str_replace( array( '.mp4', 'mp4', '.mkv', 'mkv', ), '', $vod['title'] );

                                                                    // sanity check for year
                                                                    if( empty( $vod['year'] ) ) {
                                                                        $vod['year'] = '&nbsp;';
                                                                    }

                                                                    // get total episodes for this show
                                                                    $sql            = "SELECT count(`id`) as total_episodes FROM `channels_247_files` WHERE `vod_id` = '".$vod['id']."' ";
                                                                    $query          = $conn->query( $sql );
                                                                    $results        = $query->fetchAll( PDO::FETCH_ASSOC );
                                                                    $total_episodes = $results[0]['total_episodes'];

                                                                    // match or no match
                                                                    if( empty( $vod['imdbid'] ) ) {
                                                                        $match = '<span class="btn btn-block btn-danger btn-xs">No Match</span>';
                                                                    } else {
                                                                        $match = '<span class="btn btn-block btn-success btn-xs">Match</span>';
                                                                    }

                                                                    // streaming_status
                                                                    if( $vod['status'] == 'online' ) {
                                                                        $streaming_status = '<span class="btn btn-block btn-success btn-xs">Online</span>';
                                                                    } elseif( $vod['status'] == 'starting' ) {
                                                                        $streaming_status = '<span class="btn btn-block btn-warning btn-xs">Starting</span>';
                                                                    } elseif( $vod['status'] == 'stoping' ) {
                                                                        $streaming_status = '<span class="btn btn-block btn-warning btn-xs">Stopping</span>';
                                                                    } elseif( $vod['status'] == 'offline' ) {
                                                                        $streaming_status = '<span class="btn btn-block btn-danger btn-xs">Offline</span>';
                                                                    } else {
                                                                        $streaming_status = '<span class="btn btn-block btn-warning btn-xs">'.$vod['status'].'</span>';
                                                                    }

                                                                    // get the server for this show
                                                                    foreach( $servers as $server ) {
                                                                        if( $vod['server_id'] == $server['id'] ) {
                                                                            $vod['server'] = $server;
                                                                            break;
                                                                        }
                                                                    }

                                                                    // table css
                                                                    if( $vod['status'] == 'online' ) {
                                                                        $table_css = 'success';
                                                                    } elseif( $vod['status'] == 'offline' ) {
                                                                        $table_css = 'danger';
                                                                    } else {
                                                                        $table_css = 'warning';
                                                                    }

                                                                    //output
                                                                    echo '
                                                                        <tr class="table-'.$table_css.'">
                                                                            <td>
                                                                                <input type="checkbox" class="chk" id="checkbox_'.$vod['id'].'" name="channel_ids[]" value="'.$vod['id'].'" onclick="show_multi_options();">
                                                                            </td>
                                                                            <td>
                                                                                '.$vod['title'].'
                                                                            </td>
                                                                            <td>
                                                                                '.number_format( $total_episodes ).'
                                                                            </td>
                                                                            <td>
                                                                                '.$vod['server']['name'].'
                                                                            </td>
                                                                            <td>
                                                                                '.uptime( $vod['uptime'] ).'
                                                                            </td>
                                                                            <td>
                                                                                <div class="input-group-prepend">
                                                                                    '.( $vod['status'] == 'online' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-success btn-xs dropdown-toggle" data-toggle="dropdown">Online</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $vod['status'] == 'starting' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Starting</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $vod['status'] == 'stopping' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Stopping</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    '.( $vod['status'] == 'offline' ? 
                                                                                        '<a style="color: white;" class="btn btn-block btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">Offline</a>' : 
                                                                                        '' 
                                                                                    ).'
                                                                                    <div class="dropdown-menu">
                                                                                        '.( $vod['status'] == 'online' ? 
                                                                                            '<a class="dropdown-item" href="actions.php?a=channels_247_start_stop&id='.$vod['id'].'&action=stop"><span class="btn btn-block btn-danger btn-xs">Stop</span></a>' : '' 
                                                                                        ).'
                                                                                        '.( $vod['status'] == 'offline' ? '<a class="dropdown-item" href="actions.php?a=channels_247_start_stop&id='.$vod['id'].'&action=start"><span class="btn btn-block btn-success btn-xs">Start</span></a>' : '' 
                                                                                        ).'
                                                                                        '.( $vod['status'] == 'starting' || $vod['status'] == 'stopping' ? '<a class="dropdown-item" href="actions.php?a=channels_247_start_stop&id='.$vod['id'].'&action=stop"><span class="btn btn-block btn-danger btn-xs">Stop</span></a>' : '' 
                                                                                        ).'
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <div class="input-group-prepend">
                                                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                        Actions
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <a class="dropdown-item" href="?c=channels_247_item&id='.$vod['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a href="actions.php?a=channel_247_delete&id='.$vod['id'].'" class="dropdown-item" onclick="return confirm(\'If this item is found again on the next folder scan then it will reappear. You must delete the actual file(s) to stop it appearing here again the future. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function channels_247_item() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod     = channels_247_details( get( 'id' ) ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">24/7 Channel</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=channels_247">24/7 Channels</a></li>
                                        <li class="breadcrumb-item active">24/7 Channel</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <img src="actions.php?a=http_proxy&remote_file=<?php echo base64_encode( $vod['poster'] ); ?>" width="100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="col-lg-12">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-content-below-1-tab" data-toggle="pill" href="#custom-content-below-1" role="tab" aria-controls="custom-content-below-1" aria-selected="true">Metadata</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-content-below-2-tab" data-toggle="pill" href="#custom-content-below-2" role="tab" aria-controls="custom-content-below-2" aria-selected="false">Seasons &amp; Episodes</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade show active" id="custom-content-below-1" role="tabpanel" aria-labelledby="custom-content-below-1-tab">
                                                <form action="actions.php?a=channel_247_update" method="post" class="">
                                                    <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                                    
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="card-title">Edit 24/7 Channel</h4>

                                                            <div class="card-tools">
                                                                <div class="btn-group">
                                                                    <a href="actions.php?a=channel_247_imdb_search&id=<?php echo $vod['id']; ?>" class="btn btn-info btn-xs">Search for Metadata</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body table-responsive">
                                                            <div class="form-group row mb-4">
                                                                <label for="title">Title</label>
                                                                <input type="text" class="form-control form-control-sm" id="title" name="title" value="<?php echo $vod['title']; ?>" placeholder="example: 24" required>
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="poster">Poster URL</label>
                                                                <input type="text" class="form-control form-control-sm" id="poster" name="poster" value="<?php echo $vod['poster']; ?>" placeholder="example: https://i.pinimg.com/originals/e3/9a/3d/e39a3d7e7b55c4ccf3331386a1653af9.jpg">
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <a href="?c=channels_247" class="btn btn-default">Back</a>
                                                            <a href="actions.php?a=vod_tv_delete&id=<?php echo $vod['id']; ?>" class="btn btn-danger" onclick="return confirm( 'If this item is found again on the next folder scan then it will reappear. You must delete the actual file(s) to stop it appearing here again the future. \nAre you sure?' )">Delete</a>
                                                            <button type="submit" class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade show" id="custom-content-below-2" role="tabpanel" aria-labelledby="custom-content-below-2-tab">
                                                <?php 
                                                    // get show seasons
                                                    $seasons_and_episodes_table = '';
                                                    $query = $conn->query( "SELECT `season` FROM `channels_247_files` WHERE `vod_id` = '".$vod['id']."' GROUP BY `season` ORDER BY `season` " );
                                                    $vod_seasons = $query->fetchAll( PDO::FETCH_ASSOC );

                                                    if( is_array( $vod_seasons ) ) {
                                                        foreach( $vod_seasons as $vod_season ) {
                                                            $seasons_and_episodes_table .= '
                                                            <h5>Season '.$vod_season['season'].'</h5>
                                                            <table class="table table-bordered table-striped" style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="1px" class="text-center">Episode</th>
                                                                        <th>Title</th>
                                                                    </tr>
                                                                </thead>
                                                                    <tbody>
                                                            ';

                                                            // get season episodes
                                                            $query = $conn->query( "SELECT `id`, `title`, `episode` FROM `channels_247_files` WHERE `vod_id` = '".$vod['id']."' AND `season` = '".$vod_season['season']."' GROUP BY `episode` ORDER BY `episode` ASC" );
                                                            $vod_season_episodes = $query->fetchAll( PDO::FETCH_ASSOC );
                                                            if( is_array( $vod_season_episodes ) ) {
                                                                foreach( $vod_season_episodes as $vod_season_episode ) {
                                                                    $seasons_and_episodes_table .= '
                                                                        <tr>
                                                                            <td>'.stripslashes( $vod_season_episode['episode'] ).'</td>
                                                                            <td>'.stripslashes( $vod_season_episode['title'] ).'</td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            }

                                                            $seasons_and_episodes_table .= '
                                                                    </tbody>
                                                                </table>

                                                                <br><br>
                                                            ';
                                                        }
                                                    }
                                                ?>

                                                <div class="card">
                                                    <div class="card-body table-responsive">
                                                        <?php echo $seasons_and_episodes_table; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function channel_icons() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Channel Icons</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Channel Icons</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Channel Icons</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#channel_icon_add_modal"><i class="fas fa-plus"></i> Upload Channel Icons</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table id="table_channel_icons" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="50px" class="no-sort">Preview</th>
                                                        <th>Name</th>
                                                        <th class="no-sort">Stats</th>
                                                        <th class="no-sort">Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $query = $conn->query( "SELECT * FROM `channels_icons` ORDER BY `filename` " );
                                                        if($query !== FALSE) {
                                                            $channel_icons = $query->fetchAll( PDO::FETCH_ASSOC );
                                                            foreach( $channel_icons as $channel_icon ) {
                                                                // build icon_url
                                                                $icon_url = 'http://'.$global_settings['cms_access_url'].'/content/channel_icons/'.$channel_icon['filename'];

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            <img data-src="actions.php?a=http_proxy&remote_file='.base64_encode( $icon_url ).'" loading="lazy" class="lazyload" alt="'.$channel_icon['filename'].' Icon" width="50px">
                                                                        </td>
                                                                        <td>
                                                                            '.$channel_icon['filename'].'
                                                                        </td>
                                                                        <td>
                                                                            <strong>Size:</strong> '.$channel_icon['filesize'].' <br>
                                                                            <strong>Dimensions:</strong> '.$channel_icon['width'].'px x '.$channel_icon['height'].'px
                                                                        </td>
                                                                        <td>
                                                                            <code>http://'.$global_settings['cms_access_url'].'/content/channel_icons/'.$channel_icon['filename'].'</code>
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
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=channel_icon_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="channel_icon_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Upload Channel Icons</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Select one or more PNG / JPEG files to upload to the CMS server. These will be indexed and be available within a few minutes of uploading.
                                    <br><br>
                                    <div id="upload">
                                        <div class="fileContainer">
                                            <input id="myfiles" type="file" name="myfiles[]" multiple="multiple" accept="image/*"/>
                                        </div>
                                    </div>
                                    <div id="loadedfiles">

                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function customers() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $customers    = get_all_customers(); ?>
                <?php $packages     = get_all_packages(); ?>
                <?php $owners       = get_all_users(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Customers</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Customers</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Customers</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#customer_add_modal"><i class="fas fa-plus"></i> Add Customer</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $customers[0]['id'] ) ) { ?>
                                                <table id="table_customers" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Package</th>
                                                            <th>Owner</th>
                                                            <th width="1px" class="no-sort">Conns</th>
                                                            <th width="1px" class="no-sort">Status</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $customers as $customer ) {
                                                                // set status
                                                                if( $customer['status'] == 'active' ) {
                                                                    $customer['status_tag'] = '<span class="btn btn-block btn-success btn-xs">Active</span>';
                                                                } elseif( $customer['status'] == 'suspended' ) {
                                                                    $customer['status_tag'] = '<span class="btn btn-block btn-warning btn-xs">Suspended</span>';
                                                                } elseif( $customer['status'] == 'terminated' ) {
                                                                    $customer['status_tag'] = '<span class="btn btn-block btn-danger btn-xs">Terminated</span>';
                                                                } elseif( $customer['status'] == 'expired' ) {
                                                                    $customer['status_tag'] = '<span class="btn btn-block btn-warning btn-xs">Expired</span>';
                                                                } else {
                                                                    $customer['status_tag'] = '<span class="btn btn-block btn-info btn-xs">'.ucfirst( $customer['status'] ).'</span>';
                                                                }

                                                                // get the package for this customer
                                                                foreach( $packages as $package ) {
                                                                    if( $customer['package_id'] == $package['id'] ) {
                                                                        $customer['package_name'] = $package['name'];
                                                                        break;
                                                                    }
                                                                }

                                                                // get the owner for this customer
                                                                foreach( $owners as $owner ) {
                                                                    if( $customer['owner_id'] == $owner['id'] ) {
                                                                        break;
                                                                    }
                                                                }

                                                                // get total active connections
                                                                $customer['connections']  = get_all_customer_connections( $customer['id'] );
                                                                $customer['active_connections'] = count( $customer['connections'] );

                                                                // customer expire date
                                                                if( empty( $customer['expire_date'] ) || $customer['expire_date'] == 0 || is_null( $customer['expire_date'] ) ) {
                                                                    $customer['expire_date'] == 'Never';
                                                                }

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            <strong>Username:</strong> '.stripslashes( $customer['username'] ).' <br>
                                                                            <strong>Name:</strong> '.stripslashes( $customer['first_name'].' '.$customer['last_name'] ).'
                                                                        </td>
                                                                        <td>
                                                                            <strong>Name:</strong> '.$customer['package_name'].' <br>
                                                                            <strong>Expire Date:</strong> '.date( "Y-m-d", $customer['expire_date'] ).' <br>
                                                                        </td>
                                                                        <td>
                                                                            '.( !empty( $owner['first_name'] ) ? $owner['first_name']:'' ).' '.( !empty( $owner['last_name'] ) ? $owner['last_name']:'' ).' ( '.$owner['username'].' )
                                                                        </td>
                                                                        <td>
                                                                            '.number_format( $customer['active_connections'] ).' / '.number_format( $customer['max_connections'] ).'
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group-prepend">
                                                                                '.( $customer['status'] == 'active' ? '<a class="btn btn-block btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="color: white;">Active</a>' : '' ).'
                                                                                '.( $customer['status'] == 'suspended' ? '<a class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Suspended</a>' : '' ).'
                                                                                '.( $customer['status'] == 'terminated' ? '<a class="btn btn-block btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" style="color: white;">Terminated</a>' : '' ).'
                                                                                '.( $customer['status'] == 'expired' ? '<a class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Expired</a>' : '' ).'
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="actions.php?a=customer_status&id='.$customer['id'].'&status=active"><span class="btn btn-block btn-success btn-xs">Active</span></a>
                                                                                    <a class="dropdown-item" href="actions.php?a=customer_status&id='.$customer['id'].'&status=suspended"><span class="btn btn-block btn-warning btn-xs">Suspend</span></a>
                                                                                    <a class="dropdown-item" href="actions.php?a=customer_status&id='.$customer['id'].'&status=terminated"><span class="btn btn-block btn-danger btn-xs">Terminate</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    '.( $customer['status'] == 'active' ? '<a class="dropdown-item" onclick="download_playlist(\''.$customer['username'].'\', \''.$customer['password'].'\' );"><span class="btn btn-block btn-success btn-xs">Download Playlist</span></a>' : '' ).'
                                                                                    <a class="dropdown-item" href="?c=customer&id='.$customer['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=customer_delete&id='.$customer['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                Add your first Customer to get started by clicking Add Customer.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=customer_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="customer_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Customer</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Leave blank for auto generation.">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form class="form-horizontal">
                    <div class="modal fade downloadModal" role="dialog" aria-labelledby="downloadLabel" aria-hidden="true" style="display: none;" data-username="" data-password="">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="downloadModal">Download Playlist</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <select id="download_type" class="form-control form-control-sm select2">
                                            <option value="">Select an playlist format: </option>
                                            <optgroup label="VLC m3u">
                                                <option value="type=m3u&amp;output=hls">VLC m3u - HLS </option>
                                                <option value="type=m3u&amp;output=mpegts">VLC m3u - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="VLC m3u With Options">
                                                <option value="type=m3u_plus&amp;output=hls">VLC m3u With Options - HLS </option>
                                                <option value="type=m3u_plus&amp;output=mpegts">VLC m3u With Options - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="DreamBox OE 2.0">
                                                <option value="type=dreambox&amp;output=hls">DreamBox OE 2.0 - HLS </option>
                                                <option value="type=dreambox&amp;output=mpegts">DreamBox OE 2.0 - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="WebTV List">
                                                <option value="type=webtv&amp;output=hls">WebTV List - HLS </option>
                                                <option value="type=webtv&amp;output=mpegts">WebTV List - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="Octagon">
                                                <option value="type=octagon&amp;output=hls">Octagon - HLS </option>
                                                <option value="type=octagon&amp;output=mpegts">Octagon - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="Enigma 2 OE 2.0 Auto Script">
                                                <option data-text="wget -O /etc/enigma2/iptv.sh {DEVICE_LINK} && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh" value="type=enigma22_script&amp;output=hls">Enigma 2 OE 2.0 Auto Script - HLS </option>
                                                <option data-text="wget -O /etc/enigma2/iptv.sh {DEVICE_LINK} && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh" value="type=enigma22_script&amp;output=mpegts">Enigma 2 OE 2.0 Auto Script - MPEGTS</option>
                                            </optgroup>
                                            
                                            
                                            <optgroup label="Octagon"><option value="type=octagon&amp;output=hls">Octagon - HLS </option>
                                                <option value="type=octagon&amp;output=mpegts">Octagon - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="Starlive v3/StarSat HD6060/AZclass"><option value="type=starlivev3&amp;output=hls">Starlive v3/StarSat HD6060/AZclass - HLS </option>
                                                <option value="type=starlivev3&amp;output=mpegts">Starlive v3/StarSat HD6060/AZclass - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="MediaStar / StarLive v4"><option value="type=mediastar&amp;output=hls">MediaStar / StarLive v4 - HLS </option>
                                                <option value="type=mediastar&amp;output=mpegts">MediaStar / StarLive v4 - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="Enigma 2 OE 1.6 Auto Script">
                                                <option data-text="wget -O /etc/enigma2/iptv.sh {DEVICE_LINK} && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh" value="type=enigma216_script&amp;output=hls">Enigma 2 OE 1.6 Auto Script - HLS </option>
                                                <option data-text="wget -O /etc/enigma2/iptv.sh {DEVICE_LINK} && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh" value="type=enigma216_script&amp;output=mpegts">Enigma 2 OE 1.6 Auto Script - MPEGTS</option>
                                            </optgroup>
                                            <optgroup label="Enigma 2 OE 2.0 Auto Script">
                                                <option data-text="wget -O /etc/enigma2/iptv.sh {DEVICE_LINK} && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh" value="type=enigma22_script&amp;output=hls">Enigma 2 OE 2.0 Auto Script - HLS </option>
                                                <option data-text="wget -O /etc/enigma2/iptv.sh {DEVICE_LINK} && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh" value="type=enigma22_script&amp;output=mpegts">Enigma 2 OE 2.0 Auto Script - MPEGTS</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-12" style="margin-top:10px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" onClick="this.select();" id="download_url" value="">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-warning btn-xs" onClick="copyDownload();"><i class="fa fa-copy"></i></button>
                                                <button type="button" id="download_button" class="btn btn-info btn-xs" onClick="doDownload();" disabled><i class="fa fa-download"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function customer() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $customer     = get_customer_details( get( 'id' ) ); ?>
                <?php $packages     = get_all_packages(); ?>
                <?php $owners       = get_all_users(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Customer</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=customers">Customers</a></li>
                                        <li class="breadcrumb-item active">Customer</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <form action="actions.php?a=customer_update" method="post" class="">
                                            <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                            <input type="hidden" id="existing_username" name="existing_username" value="<?php echo $customer['username']; ?>">
                                            
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit Customer</h4>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <label for="status">Status</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="status" name="status" class="form-control form-control-sm select2">
                                                                        <option value="active" <?php if( $customer['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
                                                                        <option value="suspended" <?php if( $customer['status'] == 'suspended' ) { echo 'selected'; } ?> >Suspended</option>
                                                                        <option value="terminated" <?php if( $customer['status'] == 'terminated' ) { echo 'selected'; } ?> >Terminated</option>
                                                                        <option value="expired" <?php if( $customer['status'] == 'expired' ) { echo 'selected'; } ?> >Expired</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <label for="owner_id">Reseller / Owner</label>
                                                            <select id="owner_id" name="owner_id" class="form-control form-control-sm select2">
                                                                <?php if( is_array( $owners ) && isset( $owners[0] ) ) { foreach( $owners as $owner ) { ?>
                                                                    <option value="<?php echo $owner['id']; ?>" <?php if($owner['id'] == $customer['owner_id']) { echo 'selected'; } ?> >
                                                                        <?php echo stripslashes( $owner['username'].' | '.$owner['email'].' | '.$owner['first_name'].' | '.$owner['last_name']); ?>
                                                                    </option>
                                                                <?php } } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="first_name">First Name</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="first_name" name="first_name" class="form-control form-control-sm" value="<?php echo $customer['first_name']; ?>" placeholder="example: John">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="last_name">Last Name</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="last_name" name="last_name" class="form-control form-control-sm" value="<?php echo $customer['last_name']; ?>" placeholder="example: Doe">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row mb-4">
                                                        <label for="email">Email Address</label>
                                                        <input type="text" id="email" name="email" class="form-control form-control-sm" value="<?php echo $customer['email']; ?>" placeholder="example: johnsmith@gmail.com">
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="username">Username</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="username" name="username" class="form-control form-control-sm" value="<?php echo $customer['username']; ?>" placeholder="example: john_doe">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="password">Password</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="password" name="password" class="form-control form-control-sm" value="<?php echo $customer['password']; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="max_connections">Max Connections</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="max_connections" name="max_connections" class="form-control form-control-sm" value="<?php echo $customer['max_connections']; ?>" placeholder="example: 1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="credits">Credits</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="credits" name="credits" class="form-control form-control-sm" value="<?php echo $customer['credits']; ?>" placeholder="example: 100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="package_id">Package</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="package_id" name="package_id" class="form-control form-control-sm select2">
                                                                        <?php if( is_array( $packages ) && isset( $packages[0] ) ) { foreach( $packages as $package ) { ?>
                                                                            <option value="<?php echo $package['id']; ?>" <?php if($package['id'] == $customer['package_id']) { echo 'selected'; } ?> >
                                                                                <?php echo $package['name']; ?>
                                                                            </option>
                                                                        <?php } } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="expire_date">Expire Date</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="dateTimeFlatpickr" name="expire_date" class="form-control form-control-sm flatpickr flatpickr-input active" value="<?php echo date( "Y-m-d", $customer['expire_date'] ) ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if( $account_details['type'] == 'admin' ) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label for="notes">Admin Notes</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <textarea id="notes" name="notes" class="form-control form-control-sm" rows="5"><?php echo $customer['notes']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="reseller_notes">Reseller Notes</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <textarea id="reseller_notes" name="reseller_notes" class="form-control form-control-sm" rows="5"><?php echo $customer['reseller_notes']; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="?c=customers" class="btn btn-default">Back</a>
                                                    <a href="actions.php?a=customer_delete&id=<?php echo $customer['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Allowed IP Addresses</h4>
                                                
                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#customer_add_ip_modal"><i class="fas fa-plus"></i> Add IP Address</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( isset( $customer['ip_addresses'][0] ) && is_array( $customer['ip_addresses'] ) ) { ?>
                                                    <table id="noconfig" class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>IP Address</th>
                                                                <th width="1px" class="no-sort"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach($customer['ip_addresses'] as $customer_ip) {
                                                                    echo '
                                                                        <tr>
                                                                            <td>
                                                                                '.$customer_ip['ip_address'].'
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <div class="input-group-prepend">
                                                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                        Actions
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <a href="actions.php?a=customer_ip_delete&id='.$customer_ip['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    If you would like to lock this customer account to certain IP addresses then please add them, otherwise any IP address will be allowed to access this account.
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Download Playlist</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="form-group row mb-4">
                                                    <!-- <label for="email">Player Type</label> -->
                                                    <select id="line_type" name="line_type" class="form-control form-control-sm select2" onchange="line_type(this.value);">
                                                        <option>Select an playlist format</option>
                                                        <optgroup label="VLC m3u">
                                                            <option value="m3u_hls">HLS</option>
                                                            <option value="m3u_ts">TS</option>
                                                        </optgroup>
                                                        <optgroup label="VLC m3u8 with Options">
                                                            <option value="m3u8_hls">HLS</option>
                                                            <option value="m3u8_ts">TS</option>
                                                        </optgroup>
                                                        <optgroup label="DreamBox OE 2.0">
                                                            <option value="dreambox_hls">HLS</option>
                                                            <option value="dreambox_ts">TS</option>
                                                        </optgroup>
                                                        <optgroup label="WebTV">
                                                            <option value="webtv_hls">HLS</option>
                                                            <option value="webtv_ts">TS</option>
                                                        </optgroup>
                                                        <optgroup label="Octagon">
                                                            <option value="octagon_hls">HLS</option>
                                                            <option value="octagon_ts">TS</option>
                                                        </optgroup>
                                                        <optgroup label="Enigma 2 OE 2.0 Autp Script">
                                                            <option value="enigma22_hls">HLS</option>
                                                            <option value="enigma22_ts">TS</option>
                                                        </optgroup>
                                                        <!--
                                                            <optgroup label="Enigma 2 with Redirect By-pass">
                                                                <option value="enigma22custom_hls">HLS</option>
                                                                <option value="enigma22custom_ts">TS</option>
                                                            </optgroup>
                                                        -->
                                                    </select>
                                                </div>

                                                <div class="form-group row mb-4">
                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="m3u_hls" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=m3u&output=hls">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="m3u_ts" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=m3u&output=ts">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="m3u8_hls" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=m3u_plus&output=hls">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="m3u8_ts" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=m3u_plus&output=ts">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="dreambox_hls" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=dreambox&output=hls">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="dreambox_ts" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=dreambox&output=ts">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="webtv_hls" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=webtv&output=hls">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="webtv_ts" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=webtv&output=ts">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="octagon_hls" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=octagon&output=hls">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="octagon_ts" onClick="this.select();" value="http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username'];?>&password=<?php echo $customer['password'];?>&type=octagon&output=ts">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="enigma22_hls" onClick="this.select();" value="wget -O /etc/enigma2/iptv.sh 'http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username']."&password=".$customer['password'];?>&type=enigma22_script&output=hls' && chmod 777 /etc/enigma2/iptv.sh && ">

                                                    <input type="text" class="form-control form-control-sm" style="display: none;" id="enigma22_ts" onClick="this.select();" value="wget -O /etc/enigma2/iptv.sh 'http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username']."&password=".$customer['password'];?>&type=enigma22_script&output=ts' && chmod 777 /etc/enigma2/iptv.sh && ">

                                                    <!--
                                                        <input type="text" class="form-control form-control-sm" style="display: none;" id="enigma22custom_hls" onClick="this.select();" value="wget -O /etc/enigma2/iptv.sh 'http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username']."&password=".$customer['password'];?>&type=enigma22custom_script&output=hls' && chmod 777 /etc/enigma2/iptv.sh && ">

                                                        <input type="text" class="form-control form-control-sm" style="display: none;" id="enigma22custom_ts" onClick="this.select();" value="wget -O /etc/enigma2/iptv.sh 'http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=<?php echo $customer['username']."&password=".$customer['password'];?>&type=enigma22custom_script&output=ts' && chmod 777 /etc/enigma2/iptv.sh && ">
                                                    -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Linked MAG Devices</h4>
                                                
                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#customer_add_mag_modal"><i class="fas fa-plus"></i> Add MAG Device</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( isset( $customer['mag_devices'][0] ) && is_array( $customer['mag_devices'] ) ) { ?>
                                                    <table id="noconfig" class="table table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>MAC Address</th>
                                                                <th width="1px" class="no-sort"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach( $customer['mag_devices'] as $mag_device ) {
                                                                    $mag_device['mac'] = base64_decode( $mag_device['mac'] );
                                                                    echo '
                                                                        <tr>
                                                                            <td>
                                                                                '.$mag_device['name'].'
                                                                            </td>
                                                                            <td>
                                                                                '.$mag_device['mac'].'
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <div class="input-group-prepend">
                                                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                        Actions
                                                                                    </button>
                                                                                    <div class="dropdown-menu">
                                                                                        <a href="actions.php?a=customer_mag_delete&id='.$mag_device['mag_id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    No linked MAG Devices found.
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=customer_ip_add" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">

                    <div class="modal fade" id="customer_add_ip_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add IP Address</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="ip_address">IP Address</label>
                                        <input type="text" id="ip_address" name="ip_address" class="form-control form-control-sm" placeholder="example: 123.123.123.123" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="actions.php?a=customer_mag_add" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">

                    <div class="modal fade" id="customer_add_mag_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add MAG Device</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: Living Room TV">
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="mac_address">MAC Address</label>
                                        <input type="text" id="mac_address" name="mac_address" class="form-control form-control-sm" placeholder="example: 00:0A:95:9D:68:16" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function dashboard() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <?php $connections                  = get_all_customers_connections(); ?>
                <?php $servers                      = get_all_servers(); ?>
                <?php $customers                    = get_all_customers(); ?>

                <?php $content['channels_247']      = get_all_assets( 'channels_247' ); ?>
                <?php $content['channels']          = get_all_assets( 'channels' ); ?>
                <?php $content['vod']               = get_all_assets( 'vod' ); ?>
                <?php $content['vod_tv']            = get_all_assets( 'vod_tv' ); ?>

                <?php
                    // online customers
                    foreach( $connections as $connection ) {
                        $online_customers[] = $connection['customer_id'];
                    }

                    // sanity check
                    if( isset( $online_customers[0] ) ) {
                        $total_online_customers = array_unique( $online_customers );
                        $total_online_customers = count( $total_online_customers );
                    } else {
                        $total_online_customers = 0;
                    }
                ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Dashboard</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">dashboard</a></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=servers" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-server"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Servers</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['servers'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=customers" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Customers</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['customers'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=channels_247" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-eye"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">24/7 Channels</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['channels_247'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=channels" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-tv"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Live Channels</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['channels'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=vod" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-video"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Movies VoD</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['vod'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=vod_tv" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-tablet-alt"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">TV VoD</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['vod_tv'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=open_connections" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-plug"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Open Connection</span>
                                                <span class="info-box-number"><?php echo number_format( count( $connections ) ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=open_connections" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-user"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Online Customers</span>
                                                <span class="info-box-number"><?php echo number_format( $total_online_customers ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=channels" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-play"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Online Live Channels</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['online_channels'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=channels" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-stop"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Offline Live Channels</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['offline_channels'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=channels_247" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-play"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Online 24/7 Channels</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['online_channels_247'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-2 col-sm-6 col-12">
                                    <a href="?c=channels_247" style="color: black;">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fa fa-stop"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Offline 24/7 Channels</span>
                                                <span class="info-box-number"><?php echo number_format( $globals['totals']['offline_channels_247'] ); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-2 col-xs-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><?php echo number_format( $globals['totals']['servers'] ); ?></h3>
                                            <p>Servers</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-server"></i>
                                        </div>
                                        <?php if($_SESSION['account']['type'] == 'admin' ) { ?>
                                            <a href="?c=servers" class="small-box-footer">View all <i class="fa fa-arrow-circle-right"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-xs-6">
                                    <div class="small-box bg-maroon">
                                        <div class="inner">
                                            <h3><?php echo number_format( $globals['totals']['customers'] ); ?></h3>
                                            <p>Customers</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <?php if($_SESSION['account']['type'] == 'admin' ) { ?>
                                            <a href="?c=customers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-xs-6">
                                    <div class="small-box bg-light-blue">
                                        <div class="inner">
                                            <h3><?php echo number_format( $globals['totals']['channels_247'] ); ?></h3>
                                            <p>24/7 Channels</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-eye"></i>
                                        </div>
                                        <?php if($_SESSION['account']['type'] == 'admin' ) { ?>
                                            <a href="?c=channels_247" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-xs-6">
                                    <div class="small-box bg-blue">
                                        <div class="inner">
                                            <h3><?php echo number_format( $globals['totals']['channels'] ); ?></h3>
                                            <p>Live Channels</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-tv"></i>
                                        </div>
                                        <?php if($_SESSION['account']['type'] == 'admin' ) { ?>
                                            <a href="?c=channels" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3><?php echo number_format( $globals['totals']['vod'] ); ?></h3>
                                            <p>Movies VoD</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-video"></i>
                                        </div>
                                        <?php if($_SESSION['account']['type'] == 'admin' ) { ?>
                                            <a href="?c=vod" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-xs-6">
                                    <div class="small-box bg-teal">
                                        <div class="inner">
                                            <h3><?php echo number_format( $globals['totals']['vod_tv'] ); ?></h3>
                                            <p>TV VoD</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-tablet-alt"></i>
                                        </div>
                                        <?php if($_SESSION['account']['type'] == 'admin' ) { ?>
                                            <a href="?c=vod_tv" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function deploy() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Deploy Preperations</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Deploy Preperations</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Deploy Preperations</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <a href="actions.php?a=prepare_to_deploy" class="dropdown-item" onclick="overlay_show_until_reload();"><span class="btn btn-block btn-danger btn-xs">Prepare to Deploy</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=template" method="post" class="form-horizontal">
                    <div class="modal fade" id="template_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Template Modal</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Leave blank for auto generation.">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function epg_sources() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $epg_sources = get_all_epg_sources(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">EPG Sources</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">EPG Sources</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">EPG Sources</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#epg_source_add_modal"><i class="fas fa-plus"></i> Add EPG Source</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $epg_sources[0]['id'] ) ) { ?>
                                                <table id="table_epg_sources" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th class="no-sort">Source</th>
                                                            <th width="200px" class="no-sort">Updated</th>
                                                            <th width="200px" class="no-sort">Offset</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $epg_sources as $epg_source ) {
                                                                if( $epg_source['updated'] == NULL || empty( $epg_source['updated'] ) ) {
                                                                    $epg_source['updated'] = 'Never';
                                                                } else {
                                                                    $epg_source['updated'] = date( "Y-m-d", $epg_source['updated'] );
                                                                }

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$epg_source['name'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$epg_source['source'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$epg_source['updated'].'
                                                                        </td>
                                                                        <td>
                                                                            +'.$epg_source['time_offset'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a href="actions.php?a=epg_source_delete&id='.$epg_source['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                To provide Electronic Programming Guide data to your customers, please add at least one source.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=epg_source_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="epg_source_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add EPG Source</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="example: UK FTA TV Guide">
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="source">EPG Source</label>
                                        <input type="text" class="form-control form-control-sm" id="source" name="source" placeholder="example: http://domain.com/EPG/guide.xml">
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="days_keep">Days to Keep</label>
                                        <input type="text" class="form-control form-control-sm" id="days_keep" name="days_keep" placeholder="example: 7">
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="time_offset">Time Offset</label>
                                        <input type="text" class="form-control form-control-sm" id="time_offset" name="time_offset" placeholder="example: 0000">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function monitored_folders() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <?php
                    $folders                        = array();

                    // $query                          = $conn->query( "SELECT * FROM `channels_247_monitored_folders` " );
                    // $folders['247_channels']        = $query->fetchAll( PDO::FETCH_ASSOC );

                    $query                          = $conn->query( "SELECT * FROM `vod_monitored_folders` " );
                    $folders['vod']                 = $query->fetchAll( PDO::FETCH_ASSOC );

                    $query                          = $conn->query( "SELECT * FROM `vod_tv_monitored_folders` " );
                    $folders['vod_tv']              = $query->fetchAll( PDO::FETCH_ASSOC );
                ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Monitored Folders</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Monitored Folders</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Monitored Folders</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#folder_add_modal"><i class="fas fa-plus"></i> Add Folder</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-1-tab" data-toggle="pill" href="#custom-content-below-1" role="tab" aria-controls="custom-content-below-1" aria-selected="false">Movies VoD</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-2-tab" data-toggle="pill" href="#custom-content-below-2" role="tab" aria-controls="custom-content-below-2" aria-selected="false">Tv VoD</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade" id="custom-content-below-1" role="tabpanel" aria-labelledby="custom-content-below-1-tab">
                                                    <?php if( isset( $folders['vod'][0] ) ) { ?>
                                                        <table id="table_monitored_folder_vod" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="200px">Server</th>
                                                                    <th>Folder</th>
                                                                    <th width="1px" class="no-sort"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    foreach( $folders['vod'] as $folder ) {
                                                                        $server = server_details( $folder['server_id'] );

                                                                        //output
                                                                        echo '
                                                                            <tr>
                                                                                <td>
                                                                                    '.$server['name'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.$folder['folder'].'
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                            Actions
                                                                                        </button>
                                                                                        <div class="dropdown-menu">
                                                                                            <a href="actions.php?a=vod_monitored_folder_delete&id='.$folder['id'].'" class="dropdown-item" onclick="return confirm(\'This will remove a monitored folder but will not remove items that have already been added to your library. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        ';
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } else { ?>
                                                        <br> You need to add at least one Monitored Folder for Movies VoD for more options to become available.
                                                    <?php } ?>
                                                </div>
                                                <div class="tab-pane fade" id="custom-content-below-2" role="tabpanel" aria-labelledby="custom-content-below-2-tab">
                                                    <?php if( isset( $folders['vod_tv'][0] ) ) { ?>
                                                        <table id="table_monitored_folder_vod_tv" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="200px">Server</th>
                                                                    <th>Folder</th>
                                                                    <th width="1px" class="no-sort"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    foreach( $folders['vod_tv'] as $folder ) {
                                                                        $server = server_details( $folder['server_id'] );

                                                                        //output
                                                                        echo '
                                                                            <tr>
                                                                                <td>
                                                                                    '.$server['name'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.$folder['folder'].'
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                            Actions
                                                                                        </button>
                                                                                        <div class="dropdown-menu">
                                                                                            <a href="actions.php?a=vod_tv_monitored_folder_delete&id='.$folder['id'].'" class="dropdown-item" onclick="return confirm(\'This will remove a monitored folder but will not remove items that have already been added to your library. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        ';
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } else { ?>
                                                        <br> You need to add at least one Monitored Folder for TV VoD for more options to become available.
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=monitored_folder_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="folder_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Monitored Folders</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="folder_type">Media Type</label>
                                        <select class="form-control form-control-sm" id="folder_type" name="folder_type">
                                            <option value="vod">Movies VoD</option>
                                            <option value="vod_tv">TV VoD</option>
                                        </select>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="server_id">Select Server</label>
                                        <select class="form-control form-control-sm" id="server_id" name="server_id">
                                            <option value="0" readonly>Select a server</option>
                                            <?php echo get_servers_dropbox( 'vod' ); ?>
                                        </select>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="name">Folder Path</label>
                                        <input type="text" class="form-control form-control-sm" id="folder" name="folder" placeholder="example: /media/movies/" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function mag_devices() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $customers = get_all_customers(); ?>
                <?php $mag_devices = get_all_mag_devices(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">MAG Devices</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">MAG Devices</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">MAG Devices</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#mag_add_modal"><i class="fas fa-plus"></i> Add MAG Device</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $mag_devices[0] ) ) { ?>
                                                <table id="table_mag_devices" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th width="150px" class="no-sort">MAC Address</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $mag_devices as $mag_device ) {
                                                                $mag_device['mac'] = base64_decode( $mag_device['mac'] );

                                                                foreach( $customers as $customer ) {
                                                                    if( $mag_device['customer_id'] == $customer['id'] ) {
                                                                        $mag_device['customer'] = $customer;
                                                                        break;
                                                                    }
                                                                }

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            <strong>Username:</strong> '.$mag_device['customer']['username'].' <br>
                                                                            <strong>Name:</strong> '.$mag_device['customer']['first_name'].' '.$mag_device['customer']['last_name'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$mag_device['mac'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=customer&id='.$mag_device['customer']['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=customer_mag_delete&id='.$mag_device['mag_id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                You currently don't have any MAG Devices added.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=customer_mag_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="mag_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add MAG Device</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="customer_id">Assign to Customer</label>
                                        <select id="id" name="id" class="form-control form-control-sm select2">
                                            <?php foreach( $customers as $customer ) { ?>
                                                <option value="<?php echo $customer['id']; ?>">
                                                    <?php echo $customer['username'].' | '.$customer['first_name'].' '.$customer['last_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: Living Room TV">
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="mac_address">MAC Address</label>
                                        <input type="text" id="mac_address" name="mac_address" class="form-control form-control-sm" placeholder="example: 00:0A:95:9D:68:16" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function my_profile() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">My Profile</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">My Profile</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <form action="actions.php?a=profile_update" method="post" class="">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit Profile</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="first_name">First Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="first_name" name="first_name" class="form-control form-control-sm" value="<?php echo $account_details['first_name']; ?>" placeholder="example: John" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="last_name">Last Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="last_name" name="last_name" class="form-control form-control-sm" value="<?php echo $account_details['last_name']; ?>" placeholder="example: Doe" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="email">Email</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="email" name="email" class="form-control form-control-sm" value="<?php echo $account_details['email']; ?>" placeholder="example: joe.bloggs@gmail.com" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="username">Username</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="username" name="username" class="form-control form-control-sm" value="<?php echo $account_details['username']; ?>" placeholder="example: joe_bloggs" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="password1">Password</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="password" id="password1" name="password1" class="form-control form-control-sm" value="">
                                                                <small>Leave blank to keep existing password.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="password2">Confirm Password</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="password" id="password2" name="password2" class="form-control form-control-sm" value="">
                                                                <small>Leave blank to keep existing password.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=customers" class="btn btn-default">Back</a>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function open_connections() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $connections                  = get_all_customers_connections(); ?>
                <?php $servers                      = get_all_servers(); ?>
                <?php $customers                    = get_all_customers(); ?>

                <?php $content['channels_247']      = get_all_assets( 'channels_247' ); ?>
                <?php $content['channels']          = get_all_assets( 'channels' ); ?>
                <?php $content['vod']               = get_all_assets( 'vod' ); ?>
                <?php $content['vod_tv']            = get_all_assets( 'vod_tv' ); ?>

                <?php
                    // online customers
                    foreach( $connections as $connection ) {
                        $online_customers[] = $connection['customer_id'];
                    }

                    // sanity check
                    if( isset( $online_customers[0] ) ) {
                        $total_online_customers = array_unique( $online_customers );
                        $total_online_customers = count( $total_online_customers );
                    } else {
                        $total_online_customers = 0;
                    }
                ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Open Connections</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=servers">Servers</a></li>
                                        <li class="breadcrumb-item active">Open Connections</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fa fa-plug"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Open Connections</span>
                                            <span class="info-box-number"><?php echo number_format( count( $connections ) ); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fa fa-user"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Users</span>
                                            <span class="info-box-number"><?php echo number_format( $total_online_customers ); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Live Connections</h4>

                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <!--
                                                        <a href="actions.php?a=channels_247_start_stop_all&action=stop" class="btn btn-danger btn-xs" onclick="return confirm( 'This action can take over 20+ minutes to complete depending on the size of your library. \nWould you like to continue?' )"><i class="fas fa-stop"></i> Stop All</a>
                                                    -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( count( $connections ) == 0 ) { ?>
                                                There are currently no customers connected to any servers.
                                            <?php } else { ?>
                                                <table id="table_open_connections" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="1px">ID</th>
                                                            <th>Customer</th>
                                                            <th width="">Content</th>
                                                            <th width="100px" class="no-sort">Server</th>
                                                            <th width="225px">IP</th>
                                                            <th width="150px">Uptime</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $connections as $connection ) {
                                                                // get the customer for this asset
                                                                foreach( $customers as $customer ) {
                                                                    if( $connection['customer_id'] == $customer['id'] ) {
                                                                        $connection['customer'] = $customer;
                                                                        break;
                                                                    }
                                                                }

                                                                // get the server for this asset
                                                                foreach( $servers as $server ) {
                                                                    if( $connection['server_id'] == $server['id'] ) {
                                                                        $connection['server'] = $server;
                                                                        break;
                                                                    }
                                                                }

                                                                // convert type to human readable
                                                                if( $connection['type'] == 'vod' ) {
                                                                    $connection_type = 'Movie VoD';
                                                                    foreach( $content['vod'] as $vod ) {
                                                                        if( $connection['type_id'] == $vod['id'] ) {
                                                                            $connection['content'] = $vod;
                                                                            break;
                                                                        }
                                                                    }
                                                                } elseif( $connection['type'] == 'vod_tv' ) {
                                                                    $connection_type = 'TV VoD';
                                                                    foreach( $content['vod_tv'] as $vod_tv ) {
                                                                        if( $connection['type_id'] == $vod_tv['id'] ) {
                                                                            $connection['content'] = $vod_tv;
                                                                            break;
                                                                        }
                                                                    }
                                                                } elseif( $connection['type'] == 'channel' ) {
                                                                    $connection_type = 'Live Channel';
                                                                    foreach( $content['channels'] as $channel ) {
                                                                        if( $connection['type_id'] == $channel['id'] ) {
                                                                            $connection['content'] = $channel;
                                                                            break;
                                                                        }
                                                                    }
                                                                } elseif( $connection['type'] == 'channel_247' ) {
                                                                    $connection_type = '24/7 Channel';
                                                                    foreach( $content['channels_247'] as $channel_247 ) {
                                                                        if( $connection['type_id'] == $channel_247['id'] ) {
                                                                            $connection['content'] = $channel_247;
                                                                            break;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $connection_type = 'Unknown';
                                                                }

                                                                // calculate connection uptime
                                                                $connection['uptime'] = uptime( ( $connection['updated'] - $connection['created'] ) );

                                                                // geoip
                                                                $record = $geoip->get( $connection['client_ip'] );
                                                                $record = objectToArray( $record );

                                                                $isp = $geoisp->get( $connection['client_ip'] );
                                                                $isp = objectToArray( $isp );

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$connection['id'].'
                                                                        </td>
                                                                        <td>
                                                                            <strong>Username:</strong> '.stripslashes( $connection['customer']['username'] ).' <br>
                                                                            <strong>Name:</strong> '.stripslashes( $connection['customer']['first_name'].' '.$connection['customer']['last_name'] ).'
                                                                        </td>
                                                                        <td>
                                                                            '.$connection_type.': '.$connection['content']['title'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$connection['server']['name'].'
                                                                        </td>
                                                                        <td>
                                                                            '.stripslashes( $connection['client_ip'] ).' <br>
                                                                            <img src="img/flags/'.$record['country']['iso_code'].'.png" alt="">
                                                                            '.$isp['isp'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$connection['uptime'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function packages() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $packages = get_all_packages(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Packages</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Packages</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Packages</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#package_add_modal"><i class="fas fa-plus"></i> Add Package</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table id="table_packages" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th width="1px" class="no-sort"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach( $packages as $package ) {
                                                            echo '
                                                                <tr>
                                                                    <td>
                                                                        '.$package['name'].'
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="?c=package&id='.$package['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                '.( $package['id'] != '1' ? '<div class="dropdown-divider"></div>
                                                                                <a href="actions.php?a=package_delete&id='.$package['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>' : '' ).'
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            ';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=package_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="package_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Package</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: Live TV" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function package() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $package      = package_details( get( 'id' ) ); ?>
                <?php $bouquets     = get_all_bouquets(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Package</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=packages">Packages</a></li>
                                        <li class="breadcrumb-item active">Package</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form action="actions.php?a=package_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit Package</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="first_name">Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?php echo $package['name']; ?>" placeholder="example: Package 1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="is_trial">Trial Period?</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <select class="form-control form-control-sm" id="is_trial" name="is_trial">
                                                                    <option value="yes" <?php if( $package['is_trial'] == 'yes' ) { echo'selected'; } ?> >Yes</option>
                                                                    <option value="no" <?php if( $package['is_trial'] == 'no' ) { echo'selected'; } ?> >No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="credits">Credits / Cost</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" class="form-control form-control-sm" id="credits" name="credits" value="<?php echo $package['credits']; ?>" placeholder="example: 10">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="trial_duration">Trial Duration</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <select class="form-control form-control-sm" id="trial_duration" name="trial_duration">
                                                                    <option value="1" <?php if( $package['trial_duration'] == '1' ) { echo'selected'; } ?> >1 Day</option>
                                                                    <option value="2" <?php if( $package['trial_duration'] == '2' ) { echo'selected'; } ?> >2 Days</option>
                                                                    <option value="3" <?php if( $package['trial_duration'] == '3' ) { echo'selected'; } ?> >3 Days</option>
                                                                    <option value="4" <?php if( $package['trial_duration'] == '4' ) { echo'selected'; } ?> >4 Days</option>
                                                                    <option value="5" <?php if( $package['trial_duration'] == '5' ) { echo'selected'; } ?> >5 Days</option>
                                                                    <option value="6" <?php if( $package['trial_duration'] == '6' ) { echo'selected'; } ?> >6 Days</option>
                                                                    <option value="7" <?php if( $package['trial_duration'] == '7' ) { echo'selected'; } ?> >7 Days</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="official_duration">Billing Period</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <select class="form-control form-control-sm" id="official_duration" name="official_duration">
                                                                    <option value="1" <?php if( $package['official_duration'] == '1' ) { echo'selected'; } ?> >1 Day</option>
                                                                    <option value="7" <?php if( $package['official_duration'] == '7' ) { echo'selected'; } ?> >1 Week</option>
                                                                    <option value="30" <?php if( $package['official_duration'] == '30' ) { echo'selected'; } ?> >1 Month</option>
                                                                    <option value="90" <?php if( $package['official_duration'] == '90' ) { echo'selected'; } ?> >3 Months</option>
                                                                    <option value="180" <?php if( $package['official_duration'] == '180' ) { echo'selected'; } ?> >6 Months</option>
                                                                    <option value="365" <?php if( $package['official_duration'] == '365' ) { echo'selected'; } ?> >1 Year</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=packages" class="btn btn-default">Back</a>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-6">
                                    <form action="actions.php?a=package_content_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Package Content</h4>
                                            </div>
                                            <div class="card-body">
                                                <?php if( isset( $bouquets[0]['id'] ) ) { ?>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group row mb-4">
                                                                <select id="contents" name="contents[]" size="20" class="duallistbox" multiple="multiple">
                                                                    <?php foreach( $bouquets as $bouquet ) { ?>
                                                                        <?php if( in_array( $bouquet['id'], $package['bouquets'] ) ) { ?>
                                                                            <option value="<?php echo $bouquet['id']; ?>" selected><?php echo $bouquet['name'] ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $bouquet['id']; ?>"><?php echo $bouquet['name'] ?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    You need to add at least one <a href="?c=bouquets">Bouquet</a> and then you can assign it to a package.
                                                <?php } ?>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function playlist_manager() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <?php
                    $query = $conn->query( "SELECT * FROM `playlist_manager` " );
                    $playlists = $query->fetchAll( PDO::FETCH_ASSOC );
                ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Playlist / M3U Manager</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Playlist / M3U Manager</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Playlist / M3U Manager</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#fta_playlist_add_modal"><i class="fas fa-plus"></i> Add FTA Playlists</button>
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#playlist_add_modal"><i class="fas fa-plus"></i> Upload Playlist</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $playlists[0] ) ) { ?>
                                                <table id="table_playlists" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="1px" class="no-sort">Filename</th>
                                                            <th width="1px" class="no-sort">Total</th>
                                                            <th width="1px" class="no-sort">Inspector</th>
                                                            <th width="1px" class="no-sort">Updated</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $playlists as $playlist ) {
                                                                // parse json to array
                                                                $playlist['data'] = json_decode( $playlist['data'], true );

                                                                // count total items
                                                                if( is_array( $playlist['data'] ) ) {
                                                                    $playlist['total_items'] = count( $playlist['data'] );
                                                                    // $playlist['total_items'] = number_format( $playlist['total_items'] );
                                                                } else {
                                                                    $playlist['total_items'] = '<span class="right badge badge-danger">Error</span';
                                                                }
                                                                // build icon_url
                                                                $playlist['url'] = 'http://'.$global_settings['cms_access_url'].'/playlist_manager/'.$playlist['file'];

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$playlist['name'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$playlist['file'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$playlist['total_items'].'
                                                                        </td>
                                                                        <td>
                                                                            '.( $playlist['inspector'] == 'enable' ? 
                                                                                '<a style="color: white;" class="btn btn-block btn-success btn-xs">Enabled</a>' : 
                                                                                '<a style="color: white;" class="btn btn-block btn-danger btn-xs">Disabled</a>' 
                                                                            ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( $playlist['inspector'] == 'enable' ? 
                                                                                ( !empty( $playlist['updated'] ) ? date("Y-m-d", $playlist['updated'] ) : 'Never' ) :
                                                                                '' 
                                                                            ).'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="http://dev.stiliam.com/admin_webplayer/webplayer.php?url=http://dev.stiliam.com/playlist_manager/'.$playlist['file'].'" target="_blank"><span class="btn btn-block btn-secondary btn-xs">Webplayer</span></a>
                                                                                    '.( $playlist['inspector'] == 'disable' ? '<a class="dropdown-item" href="actions.php?a=playlist_inspector&id='.$playlist['id'].'&status=enable"><span class="btn btn-block btn-success btn-xs" onclick="return confirm(\'Are you sure?\')">Enable Inspector</span></a>' : '<a class="dropdown-item" href="actions.php?a=playlist_inspector&id='.$playlist['id'].'&status=disable"><span class="btn btn-block btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')">Disable Inspector</span></a>' ).'
                                                                                    
                                                                                    <a class="dropdown-item" href="?c=playlist&id='.$playlist['id'].'"><span class="btn btn-block btn-info btn-xs">View</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=playlist_delete&id='.$playlist['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                You need to upload a playlist for more options to become available.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=playlist_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="playlist_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Upload Playlist</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Select a m3u / m3u8 playlist to upload.
                                    <br><br>
                                    <div class="form-group row mb-4">
                                        <label for="playlist_name">Name</label>
                                        <input type="text" id="playlist_name" name="playlist_name" class="form-control form-control-sm" placeholder="example: Awesome Playlist">
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="fileContainer">
                                            <input id="myfiles" type="file" name="myfiles[]" accept=".m3u,.m3u8">
                                        </div>
                                    </div>
                                    <div id="loadedfiles">

                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="actions.php?a=fta_playlist_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="fta_playlist_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Free To Air Playlists</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    By opting to proceed, Stiliam will upload several FTA playlists to your CMS. These playlists have been sourced from the public internet domain. Stiliam does not guarantee the availability nor stability of these sources, channels or their content. Some channels may by GEO locked based upon your servers location. 
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function playlist() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <?php
                    $id                 = get( 'id' );
                    $query              = $conn->query( "SELECT * FROM `playlist_manager` WHERE `id` = '".$id."' " );
                    $playlist           = $query->fetch( PDO::FETCH_ASSOC );
                    
                    if( $playlist['inspector'] == 'disable' ) {
                        $playlist['data']   = json_decode( $playlist['data'], true );
                    } else {
                        $query              = $conn->query( "SELECT * FROM `playlist_manager_content` WHERE `playlist_id` = '".$id."' " );
                        $playlist['data']   = $query->fetchAll( PDO::FETCH_ASSOC );
                    }
                ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Playlist / M3U Manager - <?php echo $playlist['name']; ?></h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Playlist / M3U Manager</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Playlist / M3U Manager</h4>

                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <?php if( $playlist['inspector'] == 'enable' ) { ?>
                                                        <a href="actions.php?a=playlist_inspector&id=<?php echo $playlist['id']; ?>&status=disable" class="btn btn-danger btn-xs"></i> Disable Inspector</a>
                                                    <?php } else { ?>
                                                        <a href="actions.php?a=playlist_inspector&id=<?php echo $playlist['id']; ?>&status=enable" class="btn btn-success btn-xs"></i> Enable Inspector</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( $playlist['inspector'] == 'disable' ) { ?>
                                                <table id="table_playlist" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Group</th>
                                                        <th>Title</th>
                                                        <th width="50px" class="no-sort">EPG ID</th>
                                                        <th class="no-sort">URL</th>
                                                        <th width="1px" class="no-sort"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach( $playlist['data'] as $data ) {
                                                            // convert to json for adding to live channels
                                                            $json = json_encode( $data );
                                                            $json = base64_encode( $json );

                                                            // output
                                                            echo '
                                                                <tr>
                                                                    <td>
                                                                        '.( isset( $data['group-title'] ) ? $data['group-title'] : '' ).'
                                                                    </td>
                                                                    <td>
                                                                        '.( isset( $data['tvg-name'] ) ? $data['tvg-name'] : '' ).'
                                                                    </td>
                                                                    <td>
                                                                        '.( isset( $data['tvg-id'] ) ? $data['tvg-id'] : '' ).'
                                                                    </td>
                                                                    <td>
                                                                        '.( isset( $data['media'] ) ? '
                                                                            <input type="text" class="form-control form-control-sm" id="source" name="source" value="'.$data['media'].'" onClick="this.setSelectionRange(0, this.value.length)" readonly>
                                                                            <span class="d-none">'.$data['media'].'</span>
                                                                            ' : 'No URL' ).'

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" data-toggle="modal" data-target="#webplayer_modal" onclick="set_webplayer_source( \''.$data['media'].'\' )"><span class="btn btn-block btn-secondary btn-xs">Webplayer</span></a>

                                                                                <a class="dropdown-item" href="actions.php?a=add_playlist_item_to_live_channels&data='.$json.'"><span class="btn btn-block btn-info btn-xs">Add to Live Channels</span></a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            ';
                                                        }
                                                    ?>
                                                </tbody>
                                                </table>
                                            <?php } else { ?>
                                                <p>Playlist Inspector will check the status of each item in the background every 24 hours. This may take a while depending on the size of this playlist, check back soon if you do not see any results yet.</p>
                                                <table id="table_playlist" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Group</th>
                                                            <th>Title</th>
                                                            <th class="no-sort">URL</th>
                                                            <th class="no-sort">Stats</th>
                                                            <th width="1px" class="no-sort">Checked</th>
                                                            <th width="1px" class="no-sort">Status</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $playlist['data'] as $data ) {
                                                                // convert to json for adding to live channels
                                                                $json = json_encode( $data );
                                                                $json = base64_encode( $json );

                                                                if( $data['status'] == 'online' ) {
                                                                    // parse stats
                                                                    if( !empty( $data['stats'] ) ) {
                                                                        $data['stats'] = json_decode( $data['stats'], true );
                                                                    }

                                                                    // build stats for human use
                                                                    if( isset( $data['stats']['streams'] ) && is_array( $data['stats']['streams'] ) ) {
                                                                        foreach( $data['stats']['streams'] as $stat ) {
                                                                            if( $stat['codec_type'] == 'video' ) {
                                                                                $data['stats']['video'] = $stat;
                                                                            }

                                                                            if( $stat['codec_type'] == 'audio' ) {
                                                                                $data['stats']['audio'] = $stat;
                                                                            }
                                                                        }
                                                                    }

                                                                    // resolution
                                                                    if( isset( $data['stats']['video']['height'] ) && !empty( $data['stats']['video']['height'] ) ) {
                                                                        $data['stats']['video']['resolution']          = $data['stats']['video']['height'].'p';
                                                                    } else {
                                                                        $data['stats']['video']['resolution']          = 'Unknown';
                                                                    }
                                                                }

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.( isset( $data['group-title'] ) ? $data['group-title'] : '' ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( isset( $data['tvg-name'] ) ? $data['tvg-name'] : '' ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( isset( $data['media'] ) ? '
                                                                            <input type="text" class="form-control form-control-sm" id="source" name="source" value="'.$data['media'].'" onClick="this.setSelectionRange(0, this.value.length)" readonly>
                                                                            <span class="d-none">'.$data['media'].'</span>
                                                                            ' : 'No URL' ).'
                                                                        </td>
                                                                        '.( $data['status'] == 'online' ?
                                                                            '
                                                                            <td>
                                                                                '.( isset( $data['stats']['video']['resolution'] ) ? '<span class="right badge badge-info">'.$data['stats']['video']['resolution'].'</span>' : '' ).'
                                                                                '.( isset( $data['stats']['video']['codec_name'] ) ? '<span class="right badge badge-info">'.strtoupper( $data['stats']['video']['codec_name'] ).'</span>' : '' ).'
                                                                                '.( isset( $data['stats']['audio']['codec_name'] ) ? '<span class="right badge badge-info">'.strtoupper( $data['stats']['audio']['codec_name'] ).'</span>' : '' ).'
                                                                            </td>
                                                                            ' :
                                                                            '<td></td>'
                                                                        ).'
                                                                        <td>
                                                                            '.( !empty( $data['updated'] ) ? date("Y-m-d", $data['updated'] ) : 'Never' ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( $data['status'] == 'pending' ? 
                                                                                '<a style="color: white;" class="btn btn-block btn-warning btn-xs">Pending</a>' : 
                                                                                '' 
                                                                            ).'
                                                                            '.( $data['status'] == 'online' ? 
                                                                                '<a style="color: white;" class="btn btn-block btn-success btn-xs">Online</a>' : 
                                                                                '' 
                                                                            ).'
                                                                            '.( $data['status'] == 'offline' ? 
                                                                                '<a style="color: white;" class="btn btn-block btn-danger btn-xs">Offline</a>' : 
                                                                                '' 
                                                                            ).'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" data-toggle="modal" data-target="#webplayer_modal" onclick="set_webplayer_source( \''.$data['media'].'\' )"><span class="btn btn-block btn-secondary btn-xs">Webplayer</span></a>

                                                                                    <a class="dropdown-item" href="actions.php?a=add_playlist_item_to_live_channels&data='.$json.'"><span class="btn btn-block btn-info btn-xs">Add to Live Channels</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                        </div>
                                        <div class="card-footer">
                                            <a href="?c=playlist_manager" class="btn btn-default">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function rtmp_management() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $allowed_ips      = get_all_allowed_ips(); ?>
                <?php $rtmp_streams     = get_all_rtmp_streams(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">RTMP Management</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">RTMP Management</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">RTMP Streams</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $rtmp_streams[0] ) ) { ?>
                                                <table id="table_rtmp_streams" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="1px">Name</th>
                                                            <th class="no-sort">URL</th>
                                                            <th width="1px" class="no-sort">Framerate</th>
                                                            <th class="no-sort">V Codec</th>
                                                            <th class="no-sort">A Codec</th>
                                                            <th width="75px" class="no-sort">Bitrate</th>
                                                            <th width="1px" class="no-sort">Uptime</th>
                                                            <th width="1px" class="no-sort">Source</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $rtmp_streams as $rtmp_stream ) {
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$rtmp_stream['name'].'
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control form-control-sm" id="source" name="source" value="http://'.$global_settings['cms_access_url'].'/play/rtmp_streams/'.$rtmp_stream['name'].'.m3u8" onClick="this.setSelectionRange(0, this.value.length)" readonly>
                                                                            <span class="d-none">http://'.$global_settings['cms_access_url'].'/play/rtmp_streams/'.$rtmp_stream['name'].'.m3u8</span>
                                                                        </td>
                                                                        <td>
                                                                            '.$rtmp_stream['rtmp_framerate'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$rtmp_stream['rtmp_video_codec'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$rtmp_stream['rtmp_audio_codec'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$rtmp_stream['rtmp_bitrate'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$rtmp_stream['rtmp_uptime'].'
                                                                        </td>
                                                                         <td>
                                                                            '.$rtmp_stream['client_ip'].'
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                There are no RTMP streams available at this time.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Allowed IPs</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#allowed_ip_add_modal"><i class="fas fa-plus"></i> Add Allowed IP</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table id="table_allowed_ips" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="150px">IP Address</th>
                                                        <th class="no-sort">Notes</th>
                                                        <th width="1px" class="no-sort"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach( $allowed_ips as $allowed_ip ) {
                                                            echo '
                                                                <tr>
                                                                    <td>
                                                                        '.$allowed_ip['ip_address'].'
                                                                    </td>
                                                                    <td>
                                                                        '.$allowed_ip['notes'].'
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a href="actions.php?a=allowed_ip_delete&id='.$allowed_ip['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            ';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=allowed_ip_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="allowed_ip_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Allowed IP</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="ip_address">IP Address</label>
                                        <input type="text" id="ip_address" name="ip_address" class="form-control form-control-sm" placeholder="example: 1.2.3.4" required>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="notes">Notes</label>
                                        <input type="text" id="notes" name="notes" class="form-control form-control-sm" placeholder="example: Home IP Encoder" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function servers() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Servers</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Servers</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Servers</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#server_add_modal"><i class="fas fa-plus"></i> Add Server</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table id="table_servers" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Server</th>
                                                        <th width="225px">IP</th>
                                                        <th width="150px">Uptime</th>
                                                        <th width="100px" class="no-sort">Status</th>
                                                        <th width="1px" class="no-sort"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $query = $conn->query( "SELECT * FROM `servers` " );
                                                        $servers = $query->fetchAll( PDO::FETCH_ASSOC );
                                                        
                                                        foreach( $servers as $server ) {
                                                            if( $server['type'] == 'cms' ) {
                                                                $server_type = 'CMS Server';
                                                            } elseif( $server['type'] == 'streamer' ) {
                                                                $server_type = 'Live TV Channel Server';
                                                            } elseif( $server['type'] == 'transcoder' ) {
                                                                $server_type = 'Transcoding Server';
                                                            } elseif( $server['type'] == 'middleware' ) {
                                                                $server_type = 'Middleware Server';
                                                            } elseif( $server['type'] == 'vod' ) {
                                                                $server_type = '24/7 Channel / VoD Server';
                                                            } else {
                                                                $server_type = ucfirst( $server['type'] );
                                                            }

                                                            if( $server['status'] == 'online' ) {
                                                                $server_status = '<span class="btn btn-block btn-success btn-xs">Online</span>';
                                                            } elseif( $server['status'] == 'pending' ) {
                                                                $server_status = '<span class="btn btn-block btn-warning btn-xs">Pending</span>';
                                                            } elseif( $server['status'] == 'installing' ) {
                                                                $server_status = '<span class="btn btn-block btn-info btn-xs">Installing: '.$server['install_progress'].'%</span>';
                                                            } elseif( $server['status'] == 'offline' ) {
                                                                $server_status = '<span class="btn btn-block btn-danger btn-xs">Offline</span>';
                                                            } else {
                                                                $server_status = '<span class="btn btn-block btn-danger btn-xs">'.ucfirst( $server['status'] ).'</span>';
                                                            }

                                                            if( $server['wan_ip_address'] == '0.0.0.0' ) {
                                                                $server['wan_ip_address'] = '-';
                                                            }

                                                            if( $server['status'] != 'online' ) {
                                                                $server['uptime'] = '-';
                                                            } else {
                                                                $server['uptime'] = uptime( $server['uptime'] );
                                                            }

                                                            // geoip
                                                            $record = $geoip->get( $server['wan_ip_address'] );
                                                            $record = objectToArray( $record );

                                                            $isp = $geoisp->get( $server['wan_ip_address'] );
                                                            $isp = objectToArray( $isp );
                                                            
                                                            // echo 'City: '.$record['city']['names']['en'].'<br>';
                                                            // echo 'Country: '.$record['country']['names']['en'].'<br>';
                                                            // echo 'Country ISO: '.$record['country']['iso_code'].'<br>';

                                                            // output
                                                            echo '
                                                                <tr>
                                                                    <td>
                                                                        <strong>Name:</strong> '.stripslashes( $server['name'] ).' <br>
                                                                        <strong>Type:</strong> '.$server_type.'
                                                                    </td>
                                                                    <td>
                                                                        '.stripslashes( $server['wan_ip_address'] ).' <br>
                                                                        <img src="img/flags/'.$record['country']['iso_code'].'.png" alt="">
                                                                        '.$isp['isp'].'
                                                                    </td>
                                                                    <td>
                                                                        '.$server['uptime'].'
                                                                    </td>
                                                                    <td>
                                                                        '.$server_status.'
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="?c=server&id='.$server['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                '.( $server['type'] != 'cms' ? '<div class="dropdown-divider"></div>
                                                                                <a href="actions.php?a=server_delete&id='.$server['id'].'" class="dropdown-item" onclick="return confirm(\'This action cannot be undone and will require a full reinstall to use this server again with the Stiliam platform. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>' : '' ).'
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            ';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=server_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="server_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Server</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: server01" required>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="server_type">Server Type</label>
                                        <select class="form-control form-control-sm" id="server_type" name="server_type">
                                            <option value="streamer">Live TV Channel Server</option>
                                            <option value="middleware">Middleware Server</option>
                                            <option value="transcoder">Transcoding Server</option>
                                            <option value="vod">24/7 Channel / VoD Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function server() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $server = server_details( get( 'id' ) ); ?>

                <?php
                    // geoip
                    $geoip_lookup = $geoip->get( $server['wan_ip_address'] );
                    $geoip_lookup = objectToArray( $geoip_lookup );

                    $isp_lookup = $geoisp->get( $server['wan_ip_address'] );
                    $isp_lookup = objectToArray( $isp_lookup );
                ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Server</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=servers">Servers</a></li>
                                        <li class="breadcrumb-item active">Server</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <?php if( $server['status'] == 'pending' || $server['status'] == 'pending_install' ) { ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Install Guide</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="input-group">
                                                    <input type="text" id="download_url" onClick="this.select();" class="form-control form-control-sm"  value="bash <(curl -s -L http://stiliam.com/downloads/installer.sh) <?php echo $global_settings['cms_ip']; ?> <?php echo $server['uuid']; ?>">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-warning btn-xs" onClick="copyDownload();"><i class="fa fa-copy"></i></button>
                                                    </div>
                                                </div>
                                                <br>
                                                Copy &amp; Paste the above install code into a SSH terminal window connected to your new server. The server should be a fresh install without any other software or packages installed. Other preinstalled software could result in a failed installation. 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif( $server['status'] == 'installing' ) { ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Install Progress</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <span id="installing">
                                                    <div class="progress progress-xl br-30">
                                                        <div id="server_install_progress" class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">0%</div>
                                                    </div>

                                                    <br>

                                                    This page will reload once the installation process has finished. Do <strong>NOT</strong> close your SSH terminal session until the installation process has completed. <br>
                                                    <br>
                                                    Please Note: The install process will appear the hang around 79% and will take 10-15 minutes to proceed to 80%, this is normal and due to the CUDA utilities being downloaded and installed on this server. <br>
                                                    <br>
                                                    If you want to check the actual status of the installer then please follow these instructions. <br>
                                                    <br>
                                                    1. Open a new SSH connection to the server. <br>
                                                    2. Run the following command. <br>
                                                    3. <code>tail -f /opt/stiliam_installer.log 2> >(grep -v truncated >&2)</code>
                                                </span>
                                                <span id="waiting_for_checkin" class="d-none">
                                                    The installation process has now finished and we are waiting for the server to make its first checkin.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <?php if( $server['status'] != 'online' ) { ?>
                                                <div class="content_blur_overlay">
                                                    <button class="btn btn-danger">Unavailable</button>
                                                </div>
                                            <?php } ?>
                                            <div id="cpu_usage_gage" class="gauge <?php if( $server['status'] != 'online' ) { echo 'content_blur'; } ?>"></div>
                                        </div>
                                        <div class="col-lg-2">
                                            <?php if( $server['status'] != 'online' ) { ?>
                                                <div class="content_blur_overlay">
                                                    <button class="btn btn-danger">Unavailable</button>
                                                </div>
                                            <?php } ?>
                                            <div id="ram_usage_gage" class="gauge <?php if( $server['status'] != 'online' ) { echo 'content_blur'; } ?>"></div>
                                        </div>
                                        <div class="col-lg-2">
                                            <?php if( $server['status'] != 'online' ) { ?>
                                                <div class="content_blur_overlay">
                                                    <button class="btn btn-danger">Unavailable</button>
                                                </div>
                                            <?php } ?>
                                            <div id="disk_usage_gage" class="gauge <?php if( $server['status'] != 'online' ) { echo 'content_blur'; } ?>"></div>
                                        </div>
                                        <div class="col-lg-2">
                                            <?php if( $server['status'] != 'online' ) { ?>
                                                <div class="content_blur_overlay">
                                                    <button class="btn btn-danger">Unavailable</button>
                                                </div>
                                            <?php } ?>
                                            <div id="bandwidth_down_gage" class="gauge <?php if( $server['status'] != 'online' ) { echo 'content_blur'; } ?>"></div>
                                        </div>
                                        <div class="col-lg-2">
                                            <?php if( $server['status'] != 'online' ) { ?>
                                                <div class="content_blur_overlay">
                                                    <button class="btn btn-danger">Unavailable</button>
                                                </div>
                                            <?php } ?>
                                            <div id="bandwidth_up_gage" class="gauge <?php if( $server['status'] != 'online' ) { echo 'content_blur'; } ?>"></div>
                                        </div>
                                        <div class="col-lg-2">
                                            <!-- <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#server_ssh_modal" onclick="new_server_ssh_iframe()">SSH Terminal</button> -->
                                            <button class="btn btn-info btn-block" data-toggle="modal" data-target="#server_speedtest_modal">Speedtest</button>
                                            <?php if( $server['status'] == 'online' ) { ?>
                                                <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#server_reboot_modal">Reboot Server</button>
                                            <?php } else { ?>
                                                <button class="btn btn-warning btn-block" onclick="alert( 'You cannot reboot the server at this time.' )">Reboot Server</button>
                                            <?php } ?>
                                            <?php if( $server['type'] != 'cms' ) { ?>
                                                <a href="actions.php?a=server_delete&id=<?php echo $server['id']; ?>" class="btn btn-danger btn-block" onclick="return confirm( 'This action cannot be undone and will require a full reinstall to use this server again with the Stiliam platform. \nAre you sure?' )">Delete Server</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <form action="actions.php?a=server_update" method="post" class="forms-sample">
                                    <input type="hidden" id="id" name="id" value="<?php echo $server['id']; ?>">
                                    
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit Server</h4>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="form-group row mb-4">
                                                        <label for="name">Server Name</label>
                                                        <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php echo $server['name']; ?>" placeholder="example: Streaming Server 1" required>
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="public_hostname">Server Hostname</label>
                                                        <input type="text" class="form-control form-control-sm" id="public_hostname" name="public_hostname" value="<?php echo $server['public_hostname']; ?>" placeholder="example: server02.domain.com">
                                                    </div>

                                                    <?php if( $server['type'] != 'cms' ) { ?>
                                                        <!--
                                                            <div class="form-group row mb-4">
                                                                <label for="http_stream_port">Server Streaming Port</label>
                                                                <input type="text" class="form-control form-control-sm" id="http_stream_port" name="http_stream_port" value="<?php echo $server['http_stream_port']; ?>" placeholder="80" required>
                                                            </div>
                                                        -->
                                                        <input type="hidden" id="http_stream_port" name="http_stream_port" value="80">
                                                    <?php } else { ?>
                                                        <input type="hidden" id="http_stream_port" name="http_stream_port" value="80">
                                                    <?php } ?>

                                                    <div class="form-group row mb-4">
                                                        <label for="connection_speed">Connection Speed (Mbit)</label>
                                                        <input type="number" class="form-control form-control-sm" id="connection_speed" name="connection_speed" value="<?php echo $server['connection_speed']; ?>" placeholder="example: 1000" required>
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="notes">Notes</label>
                                                        <textarea class="form-control form-control-sm" id="notes" name="notes" rows="5"><?php echo $server['notes']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="?c=servers" class="btn btn-default">Back</a>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-8">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Server Location</h4>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div id="world-map-markers" style="height: 500px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- speedtest modal code -->
                <div class="modal fade" id="server_speedtest_modal" tabindex="-1" role="dialog" aria-labelledby="server_speedtest_modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <?php if( $server['status'] == 'online' ) { ?>
                                    <iframe src="http://<?php echo $server['wan_ip_address']; ?>:<?php echo $server['http_stream_port']; ?>/speedtest/progressbar.html" width="100%" height="500px" frameborder="0" scrolling="no" style="overflow: hidden;"></iframe>
                                <?php } else { ?>
                                    Server is currently offline.
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- reboot modal code -->
                <div class="modal fade" id="server_reboot_modal" tabindex="-1" role="dialog" aria-labelledby="server_reboot_modalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <center>
                                    <h5>Reboot this server now?</h5>
                                    <?php if( $server['type'] == 'cms' ) { ?>
                                        You are about to reboot the main CMS server. This can render your CMS unavailable for up to 5 minutes depending on your server's boot time.
                                    <?php } ?>
                                </center>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                                <a href="actions.php?a=job_add&job=reboot&id=<?php echo $server['id']; ?>" class="btn btn-primary">Reboot Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ssh terminal modal code -->
                <div class="modal fade" id="server_ssh_modal" tabindex="-1" role="dialog" aria-labelledby="server_ssh_modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <iframe id="server_ssh_iframe" src="" width="100%" height="500px" frameborder="0" scrolling="no" style="overflow: hidden;"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function settings() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Settings &amp; Licenses</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Settings &amp; Licenses</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4">
                                    <form action="actions.php?a=update_settings" method="post" class="forms-sample">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Settings</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="form-group row mb-4">
                                                    <label for="cms_name">CMS Display Name</label>
                                                    <input type="text" class="form-control form-control-sm" id="cms_name" name="cms_name" value="<?php echo $global_settings['cms_name']; ?>" placeholder="example: Stiliam CMS" required>
                                                </div>

                                                <div class="form-group row mb-4">
                                                    <label for="cms_ip">CMS IP Address</label>
                                                    <input type="text" class="form-control form-control-sm" id="cms_ip" name="cms_ip" value="<?php echo $global_settings['cms_ip']; ?>" placeholder="example: 1.2.3.4" required>
                                                </div>

                                                <div class="form-group row mb-4">
                                                    <label for="cms_domain_name">CMS Domain</label>
                                                    <input type="text" class="form-control form-control-sm" id="cms_domain_name" name="cms_domain_name" value="<?php echo $global_settings['cms_domain_name']; ?>" placeholder="example: example.com">
                                                </div>

                                                <div class="form-group row mb-4">
                                                    <label for="master_token">Security Token</label>
                                                    <input type="text" class="form-control form-control-sm" id="master_token" name="master_token" value="<?php echo $global_settings['master_token']; ?>" required>
                                                </div>

                                                <div class="form-group row mb-4">
                                                    <label for="master_token">Ministra Admin Password</label>
                                                    <input type="text" class="form-control form-control-sm" id="ministrapassword" name="ministrapassword" value="<?php echo $global_settings['ministrapassword']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-8">
                                    <form action="actions.php?a=license_update" method="post" class="forms-sample">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Licenses</h4>

                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#license_add_modal"><i class="fas fa-plus"></i> Add License</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <table id="table_licenses" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="180px" class="no-sort">Key</th>
                                                            <th class="no-sort">Product</th>
                                                            <th width="75px" class="no-sort">Expires</th>
                                                            <th width="100px" class="no-sort">Status</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $query = $conn->query( "SELECT * FROM `licenses` " );
                                                            $licenses = $query->fetchAll( PDO::FETCH_ASSOC );
                                                            foreach( $licenses as $license ) {
                                                                // set status
                                                                if( $license['status'] == 'Active' ) {
                                                                    $license_status = '<span class="btn btn-block btn-success btn-xs">'.$license['status'].'</span>';
                                                                } elseif( $license['status'] == 'Suspended' ) {
                                                                    $license_status = '<span class="btn btn-block btn-warning btn-xs">'.$license['status'].'</span>';
                                                                } elseif( $license['status'] == 'Terminated' ) {
                                                                    $license_status = '<span class="btn btn-block btn-danger btn-xs">'.$license['status'].'</span>';
                                                                } elseif( $license['status'] == 'Expired' ) {
                                                                    $license_status = '<span class="btn btn-block btn-danger btn-xs">'.$license['status'].'</span>';
                                                                } elseif( $license['status'] == 'Invalid' ) {
                                                                    $license_status = '<span class="btn btn-block btn-danger btn-xs">'.$license['status'].'</span>';
                                                                } elseif( $license['status'] == 'pending' ) {
                                                                    $license_status = '<span class="btn btn-block btn-danger btn-xs">Pending</span>';
                                                                } else {
                                                                    $license_status = '<span class="btn btn-block btn-danger btn-xs">'.ucfirst( $license['status'] ).' > Invalid Response</span>';
                                                                }

                                                                if( $license['nextduedate'] == '0000-00-00' ) {
                                                                    $license['nextduedate'] = 'Never';
                                                                }

                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$license['license'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$license['productname'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$license['nextduedate'].'
                                                                        </td>
                                                                        <td>
                                                                            '.$license_status.'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a href="actions.php?a=license_delete&id='.$license['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=license_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="license_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add License</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="license">License Key</label>
                                        <input type="text" id="license" name="license" class="form-control form-control-sm" placeholder="example: Stiliam-fu47dEF4Gti39flr" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function staging() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Sandbox</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Sandbox</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">webplayer</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <button type="button" class="btn btn-primary btn-xs" onclick="overlay_show();"><i class="fas fa-plus"></i> open webplayer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">RTMP Stats</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php
                                                // get rtmp stats
                                                $xml = simplexml_load_file( "http://localhost/stat" );

                                                // xml to json
                                                $stream_info    = json_encode( $xml );

                                                // json to array
                                                $stream_info    = json_decode( $stream_info, true );

                                                debug( $stream_info );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">$account_details</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php debug( $account_details ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">$conn</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php debug( $conn ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">$global_settings</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php debug( $global_settings ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">$globals</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php debug( $globals ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">$site</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php debug( $site ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">GeoIP</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php 
                                                $record = $geoip->get( $_SERVER['REMOTE_ADDR'] );
                                                
                                                $record = objectToArray( $record );
                                                
                                                echo 'City: '.$record['city']['names']['en'].'<br>';
                                                echo 'Country: '.$record['country']['names']['en'].'<br>';
                                                echo 'Country ISO: '.$record['country']['iso_code'].'<br>';

                                                debug( $record );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function template() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Template</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Starter Page</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Template</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#template_modal"><i class="fas fa-plus"></i> </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=template" method="post" class="form-horizontal">
                    <div class="modal fade" id="template_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Template Modal</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Leave blank for auto generation.">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function transcoding_profiles() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <?php
                    $sql = " SELECT * FROM `transcoding_profiles` ";
                    $query = $conn->query( $sql );
                    $profiles = $query->fetchAll( PDO::FETCH_ASSOC );
                ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Transcoding Profiles</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Transcoding Profiles</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Transcoding Profiles</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#transcoding_profile_add_modal"><i class="fas fa-plus"></i> Add Transcoding Profile</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $profiles[0]['id'] ) ) { ?>
                                                <table id="table_transcoding_profiles" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="1px" class="no-sort">Type</th>
                                                            <th width="1px" class="no-sort">Hardware</th>
                                                            <th width="100px" class="no-sort">Video Codec</th>
                                                            <th width="1px" class="no-sort">Res</th>
                                                            <th width="1px" class="no-sort">Bitrate</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach($profiles as $profile) {
                                                                $profile_data = json_decode( $profile['data'], true );

                                                                if( $profile_data['cpu_gpu'] == 'copy' ) {
                                                                    $type = 'Copy Source';
                                                                }else{
                                                                    $type = 'Transcode';
                                                                }

                                                                if( $profile_data['cpu_gpu'] == 'copy' ) {
                                                                    $hardware = 'N/A';
                                                                }else{
                                                                    $hardware = strtoupper( $profile_data['cpu_gpu'] );
                                                                }
                                                            
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$profile['name'].'
                                                                        </td>
                                                                        <td>
                                                                            <span class="right badge badge-info">'.$type.'</span>
                                                                        </td>
                                                                        <td>
                                                                            '.( $profile_data['cpu_gpu'] == 'cpu' ? '<span class="right badge badge-warning">CPU Transcode</span>' : '' ).'
                                                                            '.( $profile_data['cpu_gpu'] == 'gpu' ? '<span class="right badge badge-success">GPU Transcode</span>' : '' ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( $profile_data['cpu_gpu'] != 'copy' ? '<span class="right badge badge-info">'.strtoupper( $profile_data['video_codec'] ).'</span>' : '' ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( $profile_data['cpu_gpu'] != 'copy' ? '<span class="right badge badge-info">'.strtoupper( $profile_data['screen_resolution'] ).'</span>' : '' ).'
                                                                        </td>
                                                                        <td>
                                                                            '.( $profile_data['cpu_gpu'] != 'copy' ? '<span class="right badge badge-info">'.number_format( ( $profile_data['bitrate'] * 1024 / 1e+6 ), 2 ).' Mbit </span>' : '' ).'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=transcoding_profile&id='.$profile['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=transcoding_profile_delete&id='.$profile['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                Add your first Transcoding Profile to get started by clicking Add Transcoding Profile.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=transcoding_profile_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="transcoding_profile_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Transcoding Profile</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm" placeholder="example: CPU Transcoding Profile" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function transcoding_profile() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $profile = transcoding_profile_details( get( 'id' ) ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Transcoding Profile</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=transcoding_profiles">Transcoding Profiles</a></li>
                                        <li class="breadcrumb-item active">Transcoding Profile</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <form action="actions.php?a=transcoding_profile_update" method="post" class="">
                                            <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                            
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit Transcoding Profile</h4>

                                                    <!--
                                                        <div class="card-tools">
                                                            <div class="btn-group">
                                                                <a href="actions.php?a=restart_transcoding_profile_streams&id=<?php echo get( 'id' ); ?>" class="btn btn-warning btn-xs" onclick="return confirm( 'This will restart all Live Channels currently using this Transcoding Profile. \nAre you sure?' )"><i class="fas fa-sync"></i> Restart</a>
                                                            </div>
                                                        </div>
                                                    -->
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label for="name">Name</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php echo $profile['name']; ?>" placeholder="example: CPU Transcoding Profile" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <label for="cpu_gpu">Video Transcode</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="cpu_gpu" name="data[cpu_gpu]" class="form-control form-control-sm" onchange="channel_set_transcode_type( this );">
                                                                        <option <?php if( $profile['data']['cpu_gpu'] == 'copy' ) { echo"selected"; } ?> value="copy">Copy Source</option>
                                                                        <option <?php if( $profile['data']['cpu_gpu'] == 'cpu' ) { echo"selected"; } ?> value="cpu">CPU / Processor</option>
                                                                        <option <?php if( $profile['data']['cpu_gpu'] == 'gpu' ) { echo"selected"; } ?> value="gpu">GPU / NVIDIA</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-lg-4">
                                                            <span id="transcoding_audio_options_select" class="<?php if( $profile['data']['cpu_gpu'] == 'copy' ) { echo 'd-none'; } ?>">
                                                                <label for="audio_codec">Audio Transcode</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="audio_codec" name="data[audio_codec]" class="form-control form-control-sm" onchange="channel_set_transcode_audio( this );">
                                                                            <option <?php if( $profile['data']['audio_codec'] == 'copy' ) { echo"selected"; } ?> value="copy">Copy Source</option>
                                                                            <option <?php if( $profile['data']['audio_codec'] == 'aac' ) { echo"selected"; } ?> value="aac">AAC</option>
                                                                            <option <?php if( $profile['data']['audio_codec'] == 'libfdk_aac' ) { echo"selected"; } ?> value="libfdk_aac">LibFDK AAC</option>
                                                                            <option <?php if( $profile['data']['audio_codec'] == 'ac3' ) { echo"selected"; } ?> value="ac3">AC3</option>
                                                                            <option <?php if( $profile['data']['audio_codec'] == 'mp2' ) { echo"selected"; } ?> value="mp2">MP2</option>
                                                                            <option <?php if( $profile['data']['audio_codec'] == 'mp3' ) { echo"selected"; } ?> value="mp3">MP3</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <span id="transcoding_cpu_options" class="<?php if( $profile['data']['cpu_gpu'] != 'cpu' ) { echo 'd-none'; } ?>">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <label for="cpu_video_codec">Video Codec</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="cpu_video_codec" name="data[cpu_video_codec]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['video_codec'] == 'libx264' ) { echo"selected"; } ?> value="libx264">H.264 (libx264)</option>
                                                                            <option <?php if( $profile['data']['video_codec'] == 'libx265' ) { echo"selected"; } ?> value="libx265">H.265 (libx265)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <label for="framerate">Video Framrate</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="framerate" name="data[framerate]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['framerate'] == '' || $profile['data']['framerate'] == '0' ) { echo"selected"; } ?> value="">Copy Source</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '24' ) { echo"selected"; } ?> value="">24 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '25' ) { echo"selected"; } ?> value="">25 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '30' ) { echo"selected"; } ?> value="">30 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '48' ) { echo"selected"; } ?> value="">48 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '50' ) { echo"selected"; } ?> value="">50 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '60' ) { echo"selected"; } ?> value="">60 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '72' ) { echo"selected"; } ?> value="">72 FPS (experimental)</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '100' ) { echo"selected"; } ?> value="">100 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '120' ) { echo"selected"; } ?> value="">120 FPS</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <label for="preset">Video Preset</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="preset" name="data[preset]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['preset'] == '0' ) { echo"selected"; } ?> value="0">Default</option>
                                                                            <option <?php if( $profile['data']['preset'] == '1' ) { echo"selected"; } ?> value="1">Slow</option>
                                                                            <option <?php if( $profile['data']['preset'] == '2' ) { echo"selected"; } ?> value="2">Medium</option>
                                                                            <option <?php if( $profile['data']['preset'] == '3' ) { echo"selected"; } ?> value="3">Fast</option>

                                                                            <option <?php if( $profile['data']['preset'] == '4' ) { echo"selected"; } ?> value="4">High Performance</option>
                                                                            <option <?php if( $profile['data']['preset'] == '5' ) { echo"selected"; } ?> value="5">High Quality</option>
                                                                            <!-- <option <?php if( $profile['data']['preset'] == '6' ) { echo"selected"; } ?> value="6">bd</option> -->
                                                                            <option <?php if( $profile['data']['preset'] == '7' ) { echo"selected"; } ?> value="7">Low Latency</option>
                                                                            <option <?php if( $profile['data']['preset'] == '8' ) { echo"selected"; } ?> value="8">Low Latency High Quality</option>
                                                                            <option <?php if( $profile['data']['preset'] == '9' ) { echo"selected"; } ?> value="9">Low Latency High Performance</option>
                                                                            <option <?php if( $profile['data']['preset'] == '10' ) { echo"selected"; } ?> value="10">Lossless</option>
                                                                            <option <?php if( $profile['data']['preset'] == '11' ) { echo"selected"; } ?> value="11">Lossless High Quality</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        
                                                            <div class="col-lg-3">
                                                                <label for="profile">Video Profile</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="profile" name="data[profile]" class="form-control form-control-sm">
                                                                            <optgroup label="H.264 Profiles">
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx264' && $profile['data']['profile'] == 'baseline' ) { echo"selected"; } ?> value="baseline">Baseline</option>
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx264' && $profile['data']['profile'] == 'main' ) { echo"selected"; } ?> value="main">Main</option>
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx264' && $profile['data']['profile'] == 'high' ) { echo"selected"; } ?> value="high">High</option>
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx264' && $profile['data']['profile'] == 'high444p' ) { echo"selected"; } ?> value="high444p">High444p</option>
                                                                            </optgroup>
                                                                            <optgroup label="H.265 Profiles">
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx265' && $profile['data']['profile'] == 'main' ) { echo"selected"; } ?> value="main">Main</option>
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx265' && $profile['data']['profile'] == 'main10' ) { echo"selected"; } ?> value="main10">Main10</option>
                                                                                <option <?php if( $profile['data']['video_codec'] == 'libx265' && $profile['data']['profile'] == 'rext' ) { echo"selected"; } ?> value="rext">Rext</option>
                                                                            </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <label for="screen_resolution">Video Resolution</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="screen_resolution" name="data[screen_resolution]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['screen_resolution'] == 'copy' ) { echo"selected"; } ?> value="copy">Copy Source</option>
                                                                            <!--
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1920x1080' ) { echo"selected"; } ?> value="1920x1080">1920x1080</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1680x1056' ) { echo"selected"; } ?> value="1680x1056">1680x1056</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1280x720' ) { echo"selected"; } ?> value="1280x720">1280x720</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1024x576' ) { echo"selected"; } ?> value="1024x576">1024x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '850x480' ) { echo"selected"; } ?> value="850x480">850x480</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x576' ) { echo"selected"; } ?> value="720x576">720x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x540' ) { echo"selected"; } ?> value="720x540">720x540</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x480' ) { echo"selected"; } ?> value="720x480">720x480</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x404' ) { echo"selected"; } ?> value="720x404">720x404</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '704x576' ) { echo"selected"; } ?> value="704x576">704x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '640x480' ) { echo"selected"; } ?> value="640x480">640x480</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '640x360' ) { echo"selected"; } ?> value="640x360">640x360</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '320x240' ) { echo"selected"; } ?> value="320x240">320x240</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1600x1200' ) { echo"selected"; } ?> value="1600x1200">1600x1200</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1280x960' ) { echo"selected"; } ?> value="1280x960">1280x960</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1152x864' ) { echo"selected"; } ?> value="1152x864">1152x864</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1024x768' ) { echo"selected"; } ?> value="1024x768">1024x768</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '800x600' ) { echo"selected"; } ?> value="800x600">800x600</option>
                                                                            --> 

                                                                            <option <?php if( $profile['data']['screen_resolution'] == '3840x2160' ) { echo"selected"; } ?> value="7680x4320">8k - 7680x4320</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '3840x2160' ) { echo"selected"; } ?> value="4096x2160">4K - 4096x2160</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '3840x2160' ) { echo"selected"; } ?> value="3840x2160">4K - 3840x2160</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1920x1080' ) { echo"selected"; } ?> value="1920x1080">FHD - 1920x1080</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1280x720' ) { echo"selected"; } ?> value="1280x720">HD - 1280x720</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '768x576' ) { echo"selected"; } ?> value="768x576">SD - 768x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '640x480' ) { echo"selected"; } ?> value="640x480">SD - 640x480</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        
                                                            <div class="col-lg-6">
                                                                <label for="bitrate">Video Bitrate (k)</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="bitrate" name="data[bitrate]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['bitrate'] == '1024' ) { echo"selected"; } ?> value="1024">1 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '2048' ) { echo"selected"; } ?> value="2048">2 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '3072' ) { echo"selected"; } ?> value="3072">3 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '4096' ) { echo"selected"; } ?> value="4096">4 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '5120' ) { echo"selected"; } ?> value="5120">5 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '6144' ) { echo"selected"; } ?> value="6144">6 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '7168' ) { echo"selected"; } ?> value="7168">7 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '8192' ) { echo"selected"; } ?> value="8192">8 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '9216' ) { echo"selected"; } ?> value="9216">9 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '10240' ) { echo"selected"; } ?> value="10240">10 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '11264' ) { echo"selected"; } ?> value="11264">11 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '12288' ) { echo"selected"; } ?> value="12288">12 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '13312' ) { echo"selected"; } ?> value="13312">13 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '14336' ) { echo"selected"; } ?> value="14336">14 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '15360' ) { echo"selected"; } ?> value="15360">15 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '16384' ) { echo"selected"; } ?> value="16384">16 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '17408' ) { echo"selected"; } ?> value="17408">17 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '18432' ) { echo"selected"; } ?> value="18432">18 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '19456' ) { echo"selected"; } ?> value="19456">19 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '20480' ) { echo"selected"; } ?> value="20480">20 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '21504' ) { echo"selected"; } ?> value="21504">21 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '22528' ) { echo"selected"; } ?> value="22528">22 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '23552' ) { echo"selected"; } ?> value="23552">23 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '24576' ) { echo"selected"; } ?> value="24576">24 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '25600' ) { echo"selected"; } ?> value="25600">25 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '26624' ) { echo"selected"; } ?> value="26624">26 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '27648' ) { echo"selected"; } ?> value="27648">27 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '28672' ) { echo"selected"; } ?> value="28672">28 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '29696' ) { echo"selected"; } ?> value="29696">29 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '30720' ) { echo"selected"; } ?> value="30720">30 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '31744' ) { echo"selected"; } ?> value="31744">31 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '32768' ) { echo"selected"; } ?> value="32768">32 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '33792' ) { echo"selected"; } ?> value="33792">33 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '34816' ) { echo"selected"; } ?> value="34816">34 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '35840' ) { echo"selected"; } ?> value="35840">35 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '36864' ) { echo"selected"; } ?> value="36864">36 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '37888' ) { echo"selected"; } ?> value="37888">37 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '38912' ) { echo"selected"; } ?> value="38912">38 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '39936' ) { echo"selected"; } ?> value="39936">39 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '40960' ) { echo"selected"; } ?> value="40960">40 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '41984' ) { echo"selected"; } ?> value="41984">41 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '43008' ) { echo"selected"; } ?> value="43008">42 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '44032' ) { echo"selected"; } ?> value="44032">43 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '45056' ) { echo"selected"; } ?> value="45056">44 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '46080' ) { echo"selected"; } ?> value="46080">45 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '47104' ) { echo"selected"; } ?> value="47104">46 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '48128' ) { echo"selected"; } ?> value="48128">47 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '49152' ) { echo"selected"; } ?> value="49152">48 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '50176' ) { echo"selected"; } ?> value="50176">49 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '51200' ) { echo"selected"; } ?> value="51200">50 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '52224' ) { echo"selected"; } ?> value="52224">51 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '53248' ) { echo"selected"; } ?> value="53248">52 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '54272' ) { echo"selected"; } ?> value="54272">53 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '55296' ) { echo"selected"; } ?> value="55296">54 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '56320' ) { echo"selected"; } ?> value="56320">55 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '57344' ) { echo"selected"; } ?> value="57344">56 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '58368' ) { echo"selected"; } ?> value="58368">57 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '59392' ) { echo"selected"; } ?> value="59392">58 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '60416' ) { echo"selected"; } ?> value="60416">59 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '61440' ) { echo"selected"; } ?> value="61440">60 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '62464' ) { echo"selected"; } ?> value="62464">61 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '63488' ) { echo"selected"; } ?> value="63488">62 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '64512' ) { echo"selected"; } ?> value="64512">63 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '65536' ) { echo"selected"; } ?> value="65536">64 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '66560' ) { echo"selected"; } ?> value="66560">65 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '67584' ) { echo"selected"; } ?> value="67584">66 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '68608' ) { echo"selected"; } ?> value="68608">67 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '69632' ) { echo"selected"; } ?> value="69632">68 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '70656' ) { echo"selected"; } ?> value="70656">69 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '71680' ) { echo"selected"; } ?> value="71680">70 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '72704' ) { echo"selected"; } ?> value="72704">71 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '73728' ) { echo"selected"; } ?> value="73728">72 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '74752' ) { echo"selected"; } ?> value="74752">73 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '75776' ) { echo"selected"; } ?> value="75776">74 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '76800' ) { echo"selected"; } ?> value="76800">75 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '77824' ) { echo"selected"; } ?> value="77824">76 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '78848' ) { echo"selected"; } ?> value="78848">77 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '79872' ) { echo"selected"; } ?> value="79872">78 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '80896' ) { echo"selected"; } ?> value="80896">79 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '81920' ) { echo"selected"; } ?> value="81920">80 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '82944' ) { echo"selected"; } ?> value="82944">81 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '83968' ) { echo"selected"; } ?> value="83968">82 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '84992' ) { echo"selected"; } ?> value="84992">83 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '86016' ) { echo"selected"; } ?> value="86016">84 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '87040' ) { echo"selected"; } ?> value="87040">85 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '88064' ) { echo"selected"; } ?> value="88064">86 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '89088' ) { echo"selected"; } ?> value="89088">87 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '90112' ) { echo"selected"; } ?> value="90112">88 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '91136' ) { echo"selected"; } ?> value="91136">89 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '92160' ) { echo"selected"; } ?> value="92160">90 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '93184' ) { echo"selected"; } ?> value="93184">91 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '94208' ) { echo"selected"; } ?> value="94208">92 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '95232' ) { echo"selected"; } ?> value="95232">93 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '96256' ) { echo"selected"; } ?> value="96256">94 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '97280' ) { echo"selected"; } ?> value="97280">95 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '98304' ) { echo"selected"; } ?> value="98304">96 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '99328' ) { echo"selected"; } ?> value="99328">97 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '100352' ) { echo"selected"; } ?> value="100352">98 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '101376' ) { echo"selected"; } ?> value="101376">99 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '102400' ) { echo"selected"; } ?> value="102400">100 Mbit</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>

                                                    <span id="transcoding_gpu_options" class="<?php if( $profile['data']['cpu_gpu'] != 'gpu' ) { echo 'd-none'; } ?>">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <label for="cpu_video_codec">Video Codec</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="gpu_video_codec" name="data[gpu_video_codec]" class="form-control form-control-sm">
                                                                            <option <?php if($profile['data']['video_codec']=='h264_nvenc' ){echo"selected";} ?> value="h264_nvenc">H.264 (h264_nvenc)</option>
                                                                            <option <?php if($profile['data']['video_codec']=='hevc_nvenc' ){echo"selected";} ?> value="hevc_nvenc">H.265 (hevc_nvenc)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <label for="framerate">Video Framrate</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="framerate" name="data[framerate]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['framerate'] == '' || $profile['data']['framerate'] == '0' ) { echo"selected"; } ?> value="">Copy Source</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '24' ) { echo"selected"; } ?> value="">24 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '25' ) { echo"selected"; } ?> value="">25 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '30' ) { echo"selected"; } ?> value="">30 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '48' ) { echo"selected"; } ?> value="">48 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '50' ) { echo"selected"; } ?> value="">50 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '60' ) { echo"selected"; } ?> value="">60 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '72' ) { echo"selected"; } ?> value="">72 FPS (experimental)</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '100' ) { echo"selected"; } ?> value="">100 FPS</option>
                                                                            <option <?php if( $profile['data']['framerate'] == '120' ) { echo"selected"; } ?> value="">120 FPS</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3">
                                                                <label for="screen_resolution">Video Resolution</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="screen_resolution" name="data[screen_resolution]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['screen_resolution'] == 'copy' ) { echo"selected"; } ?> value="copy">Copy Source</option>
                                                                            <!--
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1920x1080' ) { echo"selected"; } ?> value="1920x1080">1920x1080</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1680x1056' ) { echo"selected"; } ?> value="1680x1056">1680x1056</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1280x720' ) { echo"selected"; } ?> value="1280x720">1280x720</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1024x576' ) { echo"selected"; } ?> value="1024x576">1024x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '850x480' ) { echo"selected"; } ?> value="850x480">850x480</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x576' ) { echo"selected"; } ?> value="720x576">720x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x540' ) { echo"selected"; } ?> value="720x540">720x540</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x480' ) { echo"selected"; } ?> value="720x480">720x480</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '720x404' ) { echo"selected"; } ?> value="720x404">720x404</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '704x576' ) { echo"selected"; } ?> value="704x576">704x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '640x480' ) { echo"selected"; } ?> value="640x480">640x480</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '640x360' ) { echo"selected"; } ?> value="640x360">640x360</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '320x240' ) { echo"selected"; } ?> value="320x240">320x240</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1600x1200' ) { echo"selected"; } ?> value="1600x1200">1600x1200</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1280x960' ) { echo"selected"; } ?> value="1280x960">1280x960</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1152x864' ) { echo"selected"; } ?> value="1152x864">1152x864</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1024x768' ) { echo"selected"; } ?> value="1024x768">1024x768</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '800x600' ) { echo"selected"; } ?> value="800x600">800x600</option>
                                                                            --> 

                                                                            <option <?php if( $profile['data']['screen_resolution'] == '3840x2160' ) { echo"selected"; } ?> value="7680x4320">8k - 7680x4320</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '3840x2160' ) { echo"selected"; } ?> value="4096x2160">4K - 4096x2160</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '3840x2160' ) { echo"selected"; } ?> value="3840x2160">4K - 3840x2160</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1920x1080' ) { echo"selected"; } ?> value="1920x1080">FHD - 1920x1080</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '1280x720' ) { echo"selected"; } ?> value="1280x720">HD - 1280x720</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '768x576' ) { echo"selected"; } ?> value="768x576">SD - 768x576</option>
                                                                            <option <?php if( $profile['data']['screen_resolution'] == '640x480' ) { echo"selected"; } ?> value="640x480">SD - 640x480</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        
                                                            <div class="col-lg-3">
                                                                <label for="bitrate">Video Bitrate (k)</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="bitrate" name="data[bitrate]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['bitrate'] == '1024' ) { echo"selected"; } ?> value="1024">1 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '2048' ) { echo"selected"; } ?> value="2048">2 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '3072' ) { echo"selected"; } ?> value="3072">3 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '4096' ) { echo"selected"; } ?> value="4096">4 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '5120' ) { echo"selected"; } ?> value="5120">5 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '6144' ) { echo"selected"; } ?> value="6144">6 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '7168' ) { echo"selected"; } ?> value="7168">7 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '8192' ) { echo"selected"; } ?> value="8192">8 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '9216' ) { echo"selected"; } ?> value="9216">9 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '10240' ) { echo"selected"; } ?> value="10240">10 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '11264' ) { echo"selected"; } ?> value="11264">11 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '12288' ) { echo"selected"; } ?> value="12288">12 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '13312' ) { echo"selected"; } ?> value="13312">13 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '14336' ) { echo"selected"; } ?> value="14336">14 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '15360' ) { echo"selected"; } ?> value="15360">15 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '16384' ) { echo"selected"; } ?> value="16384">16 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '17408' ) { echo"selected"; } ?> value="17408">17 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '18432' ) { echo"selected"; } ?> value="18432">18 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '19456' ) { echo"selected"; } ?> value="19456">19 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '20480' ) { echo"selected"; } ?> value="20480">20 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '21504' ) { echo"selected"; } ?> value="21504">21 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '22528' ) { echo"selected"; } ?> value="22528">22 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '23552' ) { echo"selected"; } ?> value="23552">23 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '24576' ) { echo"selected"; } ?> value="24576">24 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '25600' ) { echo"selected"; } ?> value="25600">25 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '26624' ) { echo"selected"; } ?> value="26624">26 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '27648' ) { echo"selected"; } ?> value="27648">27 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '28672' ) { echo"selected"; } ?> value="28672">28 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '29696' ) { echo"selected"; } ?> value="29696">29 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '30720' ) { echo"selected"; } ?> value="30720">30 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '31744' ) { echo"selected"; } ?> value="31744">31 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '32768' ) { echo"selected"; } ?> value="32768">32 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '33792' ) { echo"selected"; } ?> value="33792">33 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '34816' ) { echo"selected"; } ?> value="34816">34 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '35840' ) { echo"selected"; } ?> value="35840">35 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '36864' ) { echo"selected"; } ?> value="36864">36 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '37888' ) { echo"selected"; } ?> value="37888">37 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '38912' ) { echo"selected"; } ?> value="38912">38 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '39936' ) { echo"selected"; } ?> value="39936">39 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '40960' ) { echo"selected"; } ?> value="40960">40 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '41984' ) { echo"selected"; } ?> value="41984">41 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '43008' ) { echo"selected"; } ?> value="43008">42 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '44032' ) { echo"selected"; } ?> value="44032">43 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '45056' ) { echo"selected"; } ?> value="45056">44 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '46080' ) { echo"selected"; } ?> value="46080">45 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '47104' ) { echo"selected"; } ?> value="47104">46 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '48128' ) { echo"selected"; } ?> value="48128">47 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '49152' ) { echo"selected"; } ?> value="49152">48 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '50176' ) { echo"selected"; } ?> value="50176">49 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '51200' ) { echo"selected"; } ?> value="51200">50 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '52224' ) { echo"selected"; } ?> value="52224">51 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '53248' ) { echo"selected"; } ?> value="53248">52 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '54272' ) { echo"selected"; } ?> value="54272">53 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '55296' ) { echo"selected"; } ?> value="55296">54 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '56320' ) { echo"selected"; } ?> value="56320">55 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '57344' ) { echo"selected"; } ?> value="57344">56 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '58368' ) { echo"selected"; } ?> value="58368">57 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '59392' ) { echo"selected"; } ?> value="59392">58 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '60416' ) { echo"selected"; } ?> value="60416">59 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '61440' ) { echo"selected"; } ?> value="61440">60 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '62464' ) { echo"selected"; } ?> value="62464">61 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '63488' ) { echo"selected"; } ?> value="63488">62 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '64512' ) { echo"selected"; } ?> value="64512">63 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '65536' ) { echo"selected"; } ?> value="65536">64 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '66560' ) { echo"selected"; } ?> value="66560">65 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '67584' ) { echo"selected"; } ?> value="67584">66 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '68608' ) { echo"selected"; } ?> value="68608">67 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '69632' ) { echo"selected"; } ?> value="69632">68 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '70656' ) { echo"selected"; } ?> value="70656">69 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '71680' ) { echo"selected"; } ?> value="71680">70 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '72704' ) { echo"selected"; } ?> value="72704">71 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '73728' ) { echo"selected"; } ?> value="73728">72 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '74752' ) { echo"selected"; } ?> value="74752">73 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '75776' ) { echo"selected"; } ?> value="75776">74 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '76800' ) { echo"selected"; } ?> value="76800">75 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '77824' ) { echo"selected"; } ?> value="77824">76 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '78848' ) { echo"selected"; } ?> value="78848">77 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '79872' ) { echo"selected"; } ?> value="79872">78 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '80896' ) { echo"selected"; } ?> value="80896">79 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '81920' ) { echo"selected"; } ?> value="81920">80 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '82944' ) { echo"selected"; } ?> value="82944">81 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '83968' ) { echo"selected"; } ?> value="83968">82 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '84992' ) { echo"selected"; } ?> value="84992">83 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '86016' ) { echo"selected"; } ?> value="86016">84 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '87040' ) { echo"selected"; } ?> value="87040">85 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '88064' ) { echo"selected"; } ?> value="88064">86 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '89088' ) { echo"selected"; } ?> value="89088">87 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '90112' ) { echo"selected"; } ?> value="90112">88 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '91136' ) { echo"selected"; } ?> value="91136">89 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '92160' ) { echo"selected"; } ?> value="92160">90 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '93184' ) { echo"selected"; } ?> value="93184">91 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '94208' ) { echo"selected"; } ?> value="94208">92 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '95232' ) { echo"selected"; } ?> value="95232">93 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '96256' ) { echo"selected"; } ?> value="96256">94 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '97280' ) { echo"selected"; } ?> value="97280">95 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '98304' ) { echo"selected"; } ?> value="98304">96 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '99328' ) { echo"selected"; } ?> value="99328">97 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '100352' ) { echo"selected"; } ?> value="100352">98 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '101376' ) { echo"selected"; } ?> value="101376">99 Mbit</option>
                                                                            <option <?php if( $profile['data']['bitrate'] == '102400' ) { echo"selected"; } ?> value="102400">100 Mbit</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>

                                                    <span id="transcoding_audio_options" class="<?php if( !isset( $profile['data']['audio_codec'] ) ||  $profile['data']['audio_codec'] == 'copy' ||  $profile['data']['cpu_gpu'] == 'copy' ) { echo 'd-none'; } ?>">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <label for="audio_bitrate">Audio Bitrate (k)</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <select id="audio_bitrate" name="data[audio_bitrate]" class="form-control form-control-sm">
                                                                            <option <?php if( $profile['data']['audio_bitrate'] == 64 ) { echo"selected"; } ?> value="64">64 kbps</option>
                                                                            <option <?php if( $profile['data']['audio_bitrate'] == 96 ) { echo"selected"; } ?> value="96">96 kpbs</option>
                                                                            <option <?php if( $profile['data']['audio_bitrate'] == 128 ) { echo"selected"; } ?> value="128">128 kpbs</option>
                                                                            <option <?php if( $profile['data']['audio_bitrate'] == 160 ) { echo"selected"; } ?> value="160">160 kpbs</option>
                                                                            <option <?php if( $profile['data']['audio_bitrate'] == 192 ) { echo"selected"; } ?> value="192">192 kpbs</option>
                                                                            <option <?php if( $profile['data']['audio_bitrate'] == 256 ) { echo"selected"; } ?> value="256">256 kpbs</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <label for="audio_sample_rate">Audio Sample Rate</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <input type="text" class="form-control form-control-sm" id="audio_sample_rate" name="data[audio_sample_rate]" value="<?php echo $profile['data']['audio_sample_rate']; ?>" placeholder="44100">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <label for="ac">Audio Channels</label>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col">
                                                                        <input type="text" class="form-control form-control-sm" id="ac" name="data[ac]" value="<?php echo $profile['data']['ac']; ?>" placeholder="2">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="?c=transcoding_profiles" class="btn btn-default">Back</a>
                                                    <a href="actions.php?a=transcoding_profile_delete&id=<?php echo $profile['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function users() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>
                
                <?php $users = get_all_users(); ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Users &amp; Resellers</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Users &amp; Resellers</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Users &amp; Resellers</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#user_add_modal"><i class="fas fa-plus"></i> Add User</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table id="table_users" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="">Account</th>
                                                        <th class=""></th>
                                                        <th class="no-sort"></th>
                                                        <th width="1px" class="no-sort">Status</th>
                                                        <th width="1px" class="no-sort"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach( $users as $user ) {
                                                            if( $user['type'] == 'admin' ) {
                                                                $account_type = 'Admin';
                                                            } elseif( $user['type'] == 'reseller' ) {
                                                                $account_type = 'Reseller';
                                                            } else {
                                                                $account_type = ucfirst( $user['type'] );
                                                            }

                                                            $user['total_customers'] = count_customers( $user['id'] );

                                                            // output
                                                            echo '
                                                                <tr>
                                                                    <td>
                                                                        <strong>Username:</strong> '.stripslashes( $user['username'] ).' <br>
                                                                        <strong>Type:</strong> '.ucfirst( $user['type'] ).'
                                                                    </td>
                                                                    <td>
                                                                        <strong>Name:</strong> '.stripslashes( $user['first_name'].' '.$user['last_name'] ).' <br>
                                                                        <strong>Email:</strong> '.stripslashes( $user['email'] ).'
                                                                    </td>
                                                                    <td>
                                                                        <strong>Credits:</strong> '.number_format( $user['credits'] ).' <br>
                                                                        <strong>Customers:</strong> '.number_format( $user['total_customers'] ).'
                                                                    </td>
                                                                    <td>
                                                                        <div class="input-group-prepend">
                                                                            '.( $user['status'] == 'active' ? '<a class="btn btn-block btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="color: white;">Active</a>' : '' ).'
                                                                            '.( $user['status'] == 'suspended' ? '<a class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Suspended</a>' : '' ).'
                                                                            '.( $user['status'] == 'terminated' ? '<a class="btn btn-block btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" style="color: white;">Terminated</a>' : '' ).'
                                                                            '.( $user['status'] == 'expired' ? '<a class="btn btn-block btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">Expired</a>' : '' ).'
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="actions.php?a=user_status&id='.$user['id'].'&status=active"><span class="btn btn-block btn-success btn-xs">Active</span></a>
                                                                                <a class="dropdown-item" href="actions.php?a=user_status&id='.$user['id'].'&status=suspended"><span class="btn btn-block btn-warning btn-xs">Suspend</span></a>
                                                                                <a class="dropdown-item" href="actions.php?a=user_status&id='.$user['id'].'&status=terminated"><span class="btn btn-block btn-danger btn-xs">Terminate</span></a>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="?c=user&id='.$user['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                '.( $user['id'] != $account_details['id'] ? '<div class="dropdown-divider"></div>
                                                                                <a href="actions.php?a=user_delete&id='.$user['id'].'" class="dropdown-item" onclick="return confirm(\'This will delete the user and all their customers will me moved under the primary admin account. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>' : '' ).'
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            ';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=user_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="user_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add User</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control form-control-sm" id="username" name="username" required>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="type">Account Type</label>
                                        <select class="form-control form-control-sm select2" id="type" name="type">
                                            <option value="admin">Admin</option>
                                            <option value="reseller">Reseller</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function user() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $user = account_details( get( 'id' ) ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">User</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=users">Users &amp; Resellers</a></li>
                                        <li class="breadcrumb-item active">User</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <form action="actions.php?a=user_update" method="post" class="">
                                            <input type="hidden" id="id" name="id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" id="existing_username" name="existing_username" value="<?php echo $user['username']; ?>">
                                            
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit Customer</h4>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <label for="status">Status</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <select id="status" name="status" class="form-control form-control-sm select2">
                                                                        <option value="active" <?php if( $user['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
                                                                        <option value="suspended" <?php if( $user['status'] == 'suspended' ) { echo 'selected'; } ?> >Suspended</option>
                                                                        <option value="terminated" <?php if( $user['status'] == 'terminated' ) { echo 'selected'; } ?> >Terminated</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <label for="type">Account Type</label>
                                                            <select class="form-control form-control-sm" id="type" name="type">
                                                                <option value="admin" <?php if( $user['type'] == 'admin' ) { echo'selected'; } ?> >Admin</option>
                                                                <option value="reseller" <?php if( $user['type'] == 'reseller' ) { echo'selected'; } ?> >Reseller</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="first_name">First Name</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="first_name" name="first_name" class="form-control form-control-sm" value="<?php echo $user['first_name']; ?>" placeholder="example: John">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="last_name">Last Name</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="last_name" name="last_name" class="form-control form-control-sm" value="<?php echo $user['last_name']; ?>" placeholder="example: Doe">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="email">Email Address</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="email" name="email" class="form-control form-control-sm" value="<?php echo $user['email']; ?>" placeholder="example: johnsmith@gmail.com">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="username">Username</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="username" name="username" class="form-control form-control-sm" value="<?php echo $user['username']; ?>" placeholder="example: john_doe">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="password">Password</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="password" name="password" class="form-control form-control-sm" value="<?php echo $user['password']; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="credits">Credits</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <input type="text" id="credits" name="credits" class="form-control form-control-sm" value="<?php echo $user['credits']; ?>" placeholder="example: 100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="notes">Admin Notes</label>
                                                            <div class="form-group row mb-4">
                                                                <div class="col">
                                                                    <textarea id="notes" name="notes" class="form-control form-control-sm" rows="5"><?php echo $user['notes']; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="?c=customers" class="btn btn-default">Back</a>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Recent Activity</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <!-- activity timeline -->
                                                <div class="timeline">
                                                    <?php
                                                        $query  = $conn->query( "SELECT * FROM `user_logs` WHERE `user_id` = '".$user['id']."' ORDER BY `id` DESC LIMIT 50" );
                                                        $logs   = $query->fetchAll( PDO::FETCH_ASSOC );
                                                        foreach( $logs as $log ) {
                                                            if( $log['action'] == 'login' ) {
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
                                                                <div class="time-label">
                                                                    <span class="bg-default">'.date( "y-m-d H:i", $log['added'] ).'</span>
                                                                </div>

                                                                <div>
                                                                    <i class="fas fa-user bg-'.$log_color.'"></i>
                                                                    <div class="timeline-item">
                                                                        <div class="timeline-body">
                                                                            '.$log['message'].'
                                                                        </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function vod() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php
                    $query = $conn->query( "SELECT `id`, `title`, `genre`, `year`, `imdbid`, `match`, `poster` FROM `vod` ORDER BY `title` " );
                    $vods = $query->fetchAll( PDO::FETCH_ASSOC );
                    $count = 1;
                ?>

                <?php $vod_categories           = get_all_vod_categories(); ?>
                <?php $bouquets                 = get_all_bouquets(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Movies VoD</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Movies VoD</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <form id="vod_tv_update_multi" action="actions.php?a=vod_multi_options" method="post">
                                <span id="multi_options" class="d-none">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Options</h4>
                                                    
                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#template_modal"><i class="fas fa-plus"></i> </button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group row mb-4">
                                                                <label for="multi_options_action">Action</label>
                                                                <select id="multi_options_action" name="multi_options_action" class="form-control form-control-sm" onchange="multi_vod_options_select(this.value);">
                                                                    <option value="">Select an action</option>
                                                                    <option value="add_to_bouquet">Add to Bouquet</option>
                                                                    <option value="change_category">Change Category</option>
                                                                    </optgroup>
                                                                    <optgroup label="Caution">
                                                                        <option value="delete">Delete Selected Movie VoDs</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_change_category" class="form-group row mb-4 d-none">
                                                                <label for="category_id">New Category</label>
                                                                <select id="category_id" name="category_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $vod_categories as $vod_category ) {
                                                                            echo '<option value="'.$vod_category['id'].'">'.$vod_category['name'].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_add_to_bouquet" class="form-group row mb-4 d-none">
                                                                <label for="bouquet_id">New Bouquet</label>
                                                                <select id="bouquet_id" name="bouquet_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $bouquets as $bouquet ) {
                                                                            if( $bouquet['type'] == 'vod' ) {
                                                                                echo '<option value="'.$bouquet['id'].'">'.$bouquet['name'].'</option>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm( 'Are you sure?' )">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Movies VoD</h4>

                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <a href="actions.php?a=vod_delete_all" class="btn btn-danger btn-xs" onclick="return confirm( 'Are you sure?' )"><i class="fas fa-trash"></i> Delete All</a>
                                                    </div>

                                                    <div class="btn-group">
                                                        <a href="actions.php?a=user_gallary_view_update&type=gallary" class="btn btn-<?php if( $account_details['gallary_view'] == 'yes' ) { echo 'primary'; } else { echo 'default'; } ?> btn-xs"><i class="fas fa-th"></i> </a>
                                                        <a href="actions.php?a=user_gallary_view_update&type=list" class="btn btn-<?php if( $account_details['gallary_view'] == 'no' ) { echo 'primary'; } else { echo 'default'; } ?> btn-xs"><i class="fas fa-list"></i> </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( count( $vods ) == 0 ) { ?>
                                                    You need to add a <a href="?c=monitored_folders">Monitored Folder</a> and wait for the initial scan to complete. Content will start to appear here after the scan starts.
                                                <?php } else { ?>
                                                    <?php if( $account_details['gallary_view'] == 'yes' ) { ?>
                                                        <div>
                                                            <!--
                                                                <div class="btn-group w-100 mb-2">
                                                                    <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All items </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="1"> Category 1 (WHITE) </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="2"> Category 2 (BLACK) </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="3"> Category 3 (COLORED) </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="4"> Category 4 (COLORED, BLACK) </a>
                                                                </div>
                                                            -->
                                                            <div class="mb-2">
                                                                <!-- <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> Shuffle items </a> -->
                                                                <div class="float-right">
                                                                    <input type="text" name="search" class="" placeholder="Search..." data-search> <br><br>
                                                                    <!--
                                                                        <select class="custom-select" style="width: auto;" data-sortOrder>
                                                                            <option value="index"> Sort by Position </option>
                                                                            <option value="sortData"> Sort by Custom Data </option>
                                                                        </select>
                                                                        <div class="btn-group">
                                                                            <a class="btn btn-default" href="javascript:void(0)" data-sortAsc> Ascending </a>
                                                                            <a class="btn btn-default" href="javascript:void(0)" data-sortDesc> Descending </a>
                                                                        </div>
                                                                    -->
                                                                </div>
                                                            </div>
                                                            <div class="filter-container p-0 row">
                                                                <?php
                                                                    foreach($vods as $vod) {
                                                                        // set default poster if needed
                                                                        if( empty( $vod['poster'] ) || $vod['poster'] == 'N/A' ) {
                                                                            $vod['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
                                                                        }

                                                                        // clean up the title
                                                                        $vod['title'] = stripslashes( $vod['title'] );
                                                                        $vod['title'] = str_replace( array( '.mp4', 'mp4', '.mkv', 'mkv', ), '', $vod['title'] );

                                                                        // sanity check for year
                                                                        if( empty( $vod['year'] ) ) {
                                                                            $vod['year'] = '&nbsp;';
                                                                        }

                                                                        // output
                                                                        echo '
                                                                            <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                                                                                <!-- <a href="?c=vod_item&id='.$vod['id'].'" data-toggle="lightbox" data-title="'.$vod['title'].'"> -->
                                                                                <a href="?c=vod_item&id='.$vod['id'].'" title="'.$vod['title'].'">
                                                                                    '.( $count < 24 ? 
                                                                                        '<img src="actions.php?a=http_proxy&remote_file='.base64_encode( $vod['poster'] ).'" loading="lazy" class="lazyload img-fluid mb-2" alt="'.$vod['title'].' Poster" 
                                                                                    style="height: 300px; width: 300px;">' 
                                                                                        : 
                                                                                        '<img data-src="actions.php?a=http_proxy&remote_file='.base64_encode( $vod['poster']) .'" loading="lazy" class="lazyload img-fluid mb-2" alt="'.$vod['title'].' Poster" 
                                                                                    style="height: 300px; width: 300px;">' 
                                                                                        ).'
                                                                                    '.( $vod['match'] == 'no' ? '<div class="ribbon-wrapper ribbon-lg"><div class="ribbon bg-danger text">No IMDB Match</div></div>' : '<div class="ribbon-wrapper ribbon-lg"><div class="ribbon bg-success text">IMDB Match</div></div>' ).'
                                                                                    
                                                                                    <span style="text-indent: 100%; overflow: hidden; display: none;">'.$vod['title'].'</span>
                                                                                </a>
                                                                            </div>
                                                                        ';

                                                                        $count++;
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <table id="table_vod" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="1px" class="no-sort">
                                                                        <input type="checkbox" id="checkAll" onclick="show_multi_options();">
                                                                    </th>
                                                                    <th>Title</th>
                                                                    <th class="nowrap" style="white-space: nowrap;">Genre</th>
                                                                    <th width="1px">Year</th>
                                                                    <th width="60px">IMDB</th>
                                                                    <th width="1px" class="no-sort"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    foreach( $vods as $vod ) {
                                                                        // set default poster if needed
                                                                        if( empty( $vod['poster'] ) || $vod['poster'] == 'N/A' ) {
                                                                            $vod['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
                                                                        }

                                                                        // clean up the title
                                                                        $vod['title'] = stripslashes( $vod['title'] );
                                                                        $vod['title'] = str_replace( array( '.mp4', 'mp4', '.mkv', 'mkv', ), '', $vod['title'] );

                                                                        // sanity check for year
                                                                        if( empty( $vod['year'] ) ) {
                                                                            $vod['year'] = '&nbsp;';
                                                                        }

                                                                        // match or no match
                                                                        if( $vod['match'] == 'no' ) {
                                                                            $match = '<span class="btn btn-block btn-danger btn-xs">No Match</span>';
                                                                        } else {
                                                                            $match = '<span class="btn btn-block btn-success btn-xs">Match</span>';
                                                                        }

                                                                        echo '
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="checkbox" class="chk" id="checkbox_'.$vod['id'].'" name="vod_ids[]" value="'.$vod['id'].'" onclick="show_multi_options();">
                                                                                </td>
                                                                                <td>
                                                                                    '.$vod['title'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.$vod['genre'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.$vod['year'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.$match.'
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                            Actions
                                                                                        </button>
                                                                                        <div class="dropdown-menu">
                                                                                            <a class="dropdown-item" href="?c=vod_item&id='.$vod['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                            <div class="dropdown-divider"></div>
                                                                                            <a href="actions.php?a=vod_delete&id='.$vod['id'].'" class="dropdown-item" onclick="return confirm(\'If this item is found again on the next folder scan then it will reappear. You must delete the actual file(s) to stop it appearing here again the future. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        ';
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function vod_categories() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod_categories = get_all_vod_categories(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Movies VoD Categories</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Movies VoD Categories</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Movies VoD Categories</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#category_add_modal"><i class="fas fa-plus"></i> Add Category</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $vod_categories[0]['id'] ) ) { ?>
                                                <table id="table_vod_categories" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $vod_categories as $vod_category ) {
                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$vod_category['name'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=vod_category&id='.$vod_category['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    '.( $vod_category['id'] != '1' ? '<div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=vod_category_delete&id='.$vod_category['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>' : '' ).'
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                Please add at least one category.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=vod_category_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="category_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Movies VoD Category</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="example: Action Movies" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function vod_category() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod_category      = vod_category_details( get( 'id' ) ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">VoD Category</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=vod_categories">VoD Categories</a></li>
                                        <li class="breadcrumb-item active">VoD Category</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="actions.php?a=vod_category_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit VoD Category</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="name">Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?php echo $vod_category['name']; ?>" placeholder="example: Action Movies">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=vod_categories" class="btn btn-default">Back</a>
                                                <?php if( $vod_category != 1 ) { ?><a href="actions.php?a=vod_category_delete&id=<?php echo $vod_category['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a><?php } ?>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function vod_item() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod     = vod_details( get( 'id' ) ); ?>
                <?php $vod_categories = get_all_vod_categories(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Movie VoD</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=vod">Movies VoD</a></li>
                                        <li class="breadcrumb-item active">Movie</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <img src="actions.php?a=http_proxy&remote_file=<?php echo base64_encode( $vod['poster'] ); ?>" width="100%">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">File Locations</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php foreach( $vod['file_locations'] as $file_location ) { ?>
                                                    <div class="form-group row mb-4">
                                                        <input type="text" class="form-control form-control-sm" value="<?php echo $file_location['file_location']; ?>" readonly>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="col-lg-12">
                                        <form action="actions.php?a=vod_update" method="post" class="">
                                            <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                            
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit Movie</h4>

                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <a href="actions.php?a=vod_imdb_search&id=<?php echo $vod['id']; ?>" class="btn btn-info btn-xs">Search for Metadata</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="form-group row mb-4">
                                                        <label for="imdbid">IMDB ID</label>
                                                        <input type="text" class="form-control form-control-sm" id="imdbid" name="imdbid" value="<?php echo $vod['imdbid']; ?>" placeholder="example: tt0057115">
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="title">Title</label>
                                                        <input type="text" class="form-control form-control-sm" id="title" name="title" value="<?php echo $vod['title']; ?>" placeholder="example: The Great Escape" required>
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="category_id">Category</label>
                                                        <select id="category_id" name="category_id" class="form-control form-control-sm">
                                                            <?php if( is_array( $vod_categories ) && isset( $vod_categories[0] ) ) { foreach( $vod_categories as $vod_category ) { ?>
                                                                <option value="<?php echo $vod_category['id']; ?>" <?php if( $vod_category['id'] == $vod['category_id'] ) { echo 'selected'; } ?> >
                                                                    <?php echo $vod_category['name']; ?>
                                                                </option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="genre">Genre</label>
                                                        <input type="text" class="form-control form-control-sm" id="genre" name="genre" value="<?php echo $vod['genre']; ?>" placeholder="example: Action, History">
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="year">Release Year</label>
                                                        <input type="text" class="form-control form-control-sm" id="year" name="year" value="<?php echo $vod['year']; ?>" placeholder="example: 1963">
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="runtime">Runtime</label>
                                                        <input type="text" class="form-control form-control-sm" id="runtime" name="runtime" value="<?php echo $vod['runtime']; ?>" placeholder="example: 193 minutes">
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="language">Language</label>
                                                        <input type="text" class="form-control form-control-sm" id="language" name="language" value="<?php echo $vod['language']; ?>" placeholder="example: English">
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="plot">Plot</label>
                                                        <textarea id="plot" name="plot" class="form-control form-control-sm" rows="5"><?php echo $vod['plot']; ?></textarea>
                                                    </div>

                                                    <div class="form-group row mb-4">
                                                        <label for="poster">Poster URL</label>
                                                        <input type="text" class="form-control form-control-sm" id="poster" name="poster" value="<?php echo $vod['poster']; ?>" placeholder="example: https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRqLw5iijCoI-qNFAZYoxguHnUfDPC8v4-HadTOe8G3tXxp1ZGE">
                                                    </div>


                                                </div>
                                                <div class="card-footer">
                                                    <a href="?c=vod" class="btn btn-default">Back</a>
                                                    <a href="actions.php?a=vod_delete&id=<?php echo $vod['id']; ?>" class="btn btn-danger" onclick="return confirm( 'If this item is found again on the next folder scan then it will reappear. You must delete the actual file(s) to stop it appearing here again the future. \nAre you sure?' )">Delete</a>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function vod_tv() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php
                    $query = $conn->query( "SELECT `id`, `title`, `year`, `imdbid`, `match`, `poster` FROM `vod_tv` ORDER BY `title` " );
                    $vods = $query->fetchAll( PDO::FETCH_ASSOC );
                    $count = 1;
                ?>

                <?php $vod_tv_categories        = get_all_vod_tv_categories(); ?>
                <?php $bouquets                 = get_all_bouquets(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">TV VoD</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">TV VoD</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <form id="vod_tv_update_multi" action="actions.php?a=vod_tv_multi_options" method="post">
                                <span id="multi_options" class="d-none">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Options</h4>
                                                    
                                                    <div class="card-tools">
                                                        <div class="btn-group">
                                                            <!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#template_modal"><i class="fas fa-plus"></i> </button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body table-responsive">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group row mb-4">
                                                                <label for="multi_options_action">Action</label>
                                                                <select id="multi_options_action" name="multi_options_action" class="form-control form-control-sm" onchange="multi_vod_tv_options_select(this.value);">
                                                                    <option value="">Select an action</option>
                                                                    <option value="add_to_bouquet">Add to Bouquet</option>
                                                                    <option value="change_category">Change Category</option>
                                                                    <option value="create_247_channels">Create 24/7 Channels</option>
                                                                    </optgroup>
                                                                    <optgroup label="Caution">
                                                                        <option value="delete">Delete Selected TV VoDs</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_change_category" class="form-group row mb-4 d-none">
                                                                <label for="category_id">New Category</label>
                                                                <select id="category_id" name="category_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $vod_tv_categories as $vod_tv_category ) {
                                                                            echo '<option value="'.$vod_tv_category['id'].'">'.$vod_tv_category['name'].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div id="multi_options_add_to_bouquet" class="form-group row mb-4 d-none">
                                                                <label for="bouquet_id">New Bouquet</label>
                                                                <select id="bouquet_id" name="bouquet_id" class="form-control form-control-sm select2">
                                                                    <?php
                                                                        foreach( $bouquets as $bouquet ) {
                                                                            if( $bouquet['type'] == 'vod_tv' ) {
                                                                                echo '<option value="'.$bouquet['id'].'">'.$bouquet['name'].'</option>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm( 'Are you sure?' )">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">TV VoD</h4>

                                                <div class="card-tools">
                                                    <div class="btn-group">
                                                        <a href="actions.php?a=vod_tv_delete_all" class="btn btn-danger btn-xs" onclick="return confirm( 'Are you sure?' )"><i class="fas fa-trash"></i> Delete All</a>
                                                    </div>

                                                    <div class="btn-group">
                                                        <a href="actions.php?a=user_gallary_view_update&type=gallary" class="btn btn-<?php if( $account_details['gallary_view'] == 'yes' ) { echo 'primary'; } else { echo 'default'; } ?> btn-xs"><i class="fas fa-th"></i> </a>
                                                        <a href="actions.php?a=user_gallary_view_update&type=list" class="btn btn-<?php if( $account_details['gallary_view'] == 'no' ) { echo 'primary'; } else { echo 'default'; } ?> btn-xs"><i class="fas fa-list"></i> </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <?php if( count( $vods ) == 0 ) { ?>
                                                    You need to add a <a href="?c=monitored_folders">Monitored Folder</a> and wait for the initial scan to complete. Content will start to appear here after the scan starts.
                                                <?php } else { ?>
                                                    <?php if( $account_details['gallary_view'] == 'yes' ) { ?>
                                                        <div>
                                                            <!--
                                                                <div class="btn-group w-100 mb-2">
                                                                    <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All items </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="1"> Category 1 (WHITE) </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="2"> Category 2 (BLACK) </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="3"> Category 3 (COLORED) </a>
                                                                    <a class="btn btn-info" href="javascript:void(0)" data-filter="4"> Category 4 (COLORED, BLACK) </a>
                                                                </div>
                                                            -->
                                                            <div class="mb-2">
                                                                <!-- <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> Shuffle items </a> -->
                                                                <div class="float-right">
                                                                    <input type="text" name="search" class="" placeholder="Search..." data-search> <br><br>
                                                                    <!--
                                                                        <select class="custom-select" style="width: auto;" data-sortOrder>
                                                                            <option value="index"> Sort by Position </option>
                                                                            <option value="sortData"> Sort by Custom Data </option>
                                                                        </select>
                                                                        <div class="btn-group">
                                                                            <a class="btn btn-default" href="javascript:void(0)" data-sortAsc> Ascending </a>
                                                                            <a class="btn btn-default" href="javascript:void(0)" data-sortDesc> Descending </a>
                                                                        </div>
                                                                    -->
                                                                </div>
                                                            </div>
                                                            <div class="filter-container p-0 row">
                                                                <?php
                                                                    foreach($vods as $vod) {
                                                                        // set default poster if needed
                                                                        if( empty( $vod['poster'] ) || $vod['poster'] == 'N/A' ) {
                                                                            $vod['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
                                                                        }

                                                                        // clean up the title
                                                                        $vod['title'] = stripslashes( $vod['title'] );
                                                                        $vod['title'] = str_replace( array( '.mp4', 'mp4', '.mkv', 'mkv', ), '', $vod['title'] );

                                                                        // sanity check for year
                                                                        if( empty( $vod['year'] ) ) {
                                                                            $vod['year'] = '&nbsp;';
                                                                        }

                                                                        // output
                                                                        echo '
                                                                            <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                                                                                <!-- <a href="?c=vod_item&id='.$vod['id'].'" data-toggle="lightbox" data-title="'.$vod['title'].'"> -->
                                                                                <a href="?c=vod_tv_item&id='.$vod['id'].'" title="'.$vod['title'].'">
                                                                                    '.( $count < 24 ? 
                                                                                        '<img src="actions.php?a=http_proxy&remote_file='.base64_encode( $vod['poster'] ).'" loading="lazy" class="lazyload img-fluid mb-2" alt="'.$vod['title'].' Poster" 
                                                                                    style="height: 300px; width: 300px;">' 
                                                                                        : 
                                                                                        '<img data-src="actions.php?a=http_proxy&remote_file='.base64_encode( $vod['poster'] ).'" loading="lazy" class="lazyload img-fluid mb-2" alt="'.$vod['title'].' Poster" 
                                                                                    style="height: 300px; width: 300px;">' 
                                                                                        ).'
                                                                                    '.( $vod['match'] == 'no' ? '<div class="ribbon-wrapper ribbon-lg"><div class="ribbon bg-danger text">No IMDB Match</div></div>' : '<div class="ribbon-wrapper ribbon-lg"><div class="ribbon bg-success text">IMDB Match</div></div>' ).'
                                                                                    
                                                                                    <span style="text-indent: 100%; overflow: hidden; display: none;">'.$vod['title'].'</span>
                                                                                </a>
                                                                            </div>
                                                                        ';

                                                                        $count++;
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <table id="table_vod_tv" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="1px" class="no-sort">
                                                                        <input type="checkbox" id="checkAll" onclick="show_multi_options();">
                                                                    </th>
                                                                    <th>Title</th>
                                                                    <th width="1px" class="no-sort">Year</th>
                                                                    <th width="1px" class="no-sort">Episodes</th>
                                                                    <th width="60px" class="no-sort">IMDB</th>
                                                                    <th width="1px" class="no-sort"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    foreach( $vods as $vod ) {
                                                                        // set default poster if needed
                                                                        if( empty( $vod['poster'] ) || $vod['poster'] == 'N/A' ) {
                                                                            $vod['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
                                                                        }

                                                                        // clean up the title
                                                                        $vod['title'] = stripslashes( $vod['title'] );
                                                                        $vod['title'] = str_replace( array( '.mp4', 'mp4', '.mkv', 'mkv', ), '', $vod['title'] );

                                                                        // sanity check for year
                                                                        if( empty( $vod['year'] ) ) {
                                                                            $vod['year'] = '&nbsp;';
                                                                        }

                                                                        // get total episodes for this show
                                                                        $sql            = "SELECT count(`id`) as total_episodes FROM `vod_tv_files` WHERE `vod_id` = '".$vod['id']."' ";
                                                                        $query          = $conn->query( $sql );
                                                                        $results        = $query->fetchAll( PDO::FETCH_ASSOC );
                                                                        $total_episodes = $results[0]['total_episodes'];

                                                                        // match or no match
                                                                        if( $vod['match'] == 'no' ) {
                                                                            $match = '<span class="btn btn-block btn-danger btn-xs">No Match</span>';
                                                                        } else {
                                                                            $match = '<span class="btn btn-block btn-success btn-xs">Match</span>';
                                                                        }

                                                                        echo '
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="checkbox" class="chk" id="checkbox_'.$vod['id'].'" name="vod_ids[]" value="'.$vod['id'].'" onclick="show_multi_options();">
                                                                                </td>
                                                                                <td>
                                                                                    '.$vod['title'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.$vod['year'].'
                                                                                </td>
                                                                                <td>
                                                                                    '.number_format( $total_episodes ).'
                                                                                </td>
                                                                                <td>
                                                                                    '.$match.'
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                            Actions
                                                                                        </button>
                                                                                        <div class="dropdown-menu">
                                                                                            <a class="dropdown-item" href="?c=vod_tv_item&id='.$vod['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                            <div class="dropdown-divider"></div>
                                                                                            <a href="actions.php?a=vod_tv_delete&id='.$vod['id'].'" class="dropdown-item" onclick="return confirm(\'If this item is found again on the next folder scan then it will reappear. You must delete the actual file(s) to stop it appearing here again the future. \nAre you sure?\' )"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        ';
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function vod_tv_categories() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod_tv_categories = get_all_vod_tv_categories(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">TV VoD Categories</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">TV VoD Categories</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">TV VoD Categories</h4>
                                            
                                            <div class="card-tools">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#category_add_modal"><i class="fas fa-plus"></i> Add Category</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <?php if( isset( $vod_tv_categories[0]['id'] ) ) { ?>
                                                <table id="table_vod_tv_categories" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th width="1px" class="no-sort"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach( $vod_tv_categories as $vod_tv_category ) {
                                                                // output
                                                                echo '
                                                                    <tr>
                                                                        <td>
                                                                            '.$vod_tv_category['name'].'
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                                    Actions
                                                                                </button>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item" href="?c=vod_tv_category&id='.$vod_tv_category['id'].'"><span class="btn btn-block btn-info btn-xs">Edit</span></a>
                                                                                    '.( $vod_tv_category['id'] != '1' ? '<div class="dropdown-divider"></div>
                                                                                    <a href="actions.php?a=vod_tv_category_delete&id='.$vod_tv_category['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\')"><span class="btn btn-block btn-danger btn-xs">Delete</span></a>' : '' ).'
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                ';
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php } else { ?>
                                                Please add at least one category.
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="actions.php?a=vod_tv_category_add" method="post" class="form-horizontal">
                    <div class="modal fade" id="category_add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add TV VoD Category</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row mb-4">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="example: Action Movies" required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

            <?php function vod_tv_category() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod_tv_category      = vod_tv_category_details( get( 'id' ) ); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">TV VoD Category</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=vod_tv_categories">TV VoD Categories</a></li>
                                        <li class="breadcrumb-item active">TV VoD Category</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="actions.php?a=vod_tv_category_update" method="post" class="">
                                        <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Edit TV VoD Category</h4>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="name">Name</label>
                                                        <div class="form-group row mb-4">
                                                            <div class="col">
                                                                <input type="text" id="name" name="name" class="form-control form-control-sm" value="<?php echo $vod_tv_category['name']; ?>" placeholder="example: Action TV Series">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="?c=vod_tv_categories" class="btn btn-default">Back</a>
                                                <?php if( $vod_tv_category != 1 ) { ?><a href="actions.php?a=vod_tv_category_delete&id=<?php echo $vod_tv_category['id']; ?>" class="btn btn-danger" onclick="return confirm( 'Are you sure?' )">Delete</a><?php } ?>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php function vod_tv_item() { ?>
                <?php global $conn, $globals, $global_settings, $account_details, $site, $geoip, $geoisp; ?>

                <?php $vod     = vod_tv_details( get( 'id' ) ); ?>
                <?php $vod_tv_categories = get_all_vod_tv_categories(); ?>
                
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">TV VoD</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="?c=dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="?c=vod_tv">TV VoD</a></li>
                                        <li class="breadcrumb-item active">TV</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <img src="actions.php?a=http_proxy&remote_file=<?php echo base64_encode( $vod['poster'] ); ?>" width="100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="col-lg-12">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-content-below-1-tab" data-toggle="pill" href="#custom-content-below-1" role="tab" aria-controls="custom-content-below-1" aria-selected="true">Metadata</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-content-below-2-tab" data-toggle="pill" href="#custom-content-below-2" role="tab" aria-controls="custom-content-below-2" aria-selected="false">Seasons &amp; Episodes</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade show active" id="custom-content-below-1" role="tabpanel" aria-labelledby="custom-content-below-1-tab">
                                                <form action="actions.php?a=vod_tv_update" method="post" class="">
                                                    <input type="hidden" id="id" name="id" value="<?php echo get( 'id' ); ?>">
                                                    
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="card-title">Edit TV Show</h4>

                                                            <div class="card-tools">
                                                                <div class="btn-group">
                                                                    <a href="actions.php?a=vod_tv_imdb_search&id=<?php echo $vod['id']; ?>" class="btn btn-info btn-xs">Search for Metadata</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body table-responsive">
                                                            <div class="form-group row mb-4">
                                                                <label for="imdbid">IMDB ID</label>
                                                                <input type="text" class="form-control form-control-sm" id="imdbid" name="imdbid" value="<?php echo $vod['imdbid']; ?>" placeholder="example: tt0057115" required>
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="title">Title</label>
                                                                <input type="text" class="form-control form-control-sm" id="title" name="title" value="<?php echo $vod['title']; ?>" placeholder="example: 24" required>
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="category_id">Category</label>
                                                                <select id="category_id" name="category_id" class="form-control form-control-sm">
                                                                    <?php if( is_array( $vod_tv_categories ) && isset( $vod_tv_categories[0] ) ) { foreach( $vod_tv_categories as $vod_tv_category ) { ?>
                                                                        <option value="<?php echo $vod_tv_category['id']; ?>" <?php if( $vod_tv_category['id'] == $vod['category_id'] ) { echo 'selected'; } ?> >
                                                                            <?php echo $vod_tv_category['name']; ?>
                                                                        </option>
                                                                    <?php } } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="genre">Genre</label>
                                                                <input type="text" class="form-control form-control-sm" id="genre" name="genre" value="<?php echo $vod['genre']; ?>" placeholder="example: Action">
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="year">Release Year</label>
                                                                <input type="text" class="form-control form-control-sm" id="year" name="year" value="<?php echo $vod['year']; ?>" placeholder="example: 2001">
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="runtime">Runtime</label>
                                                                <input type="text" class="form-control form-control-sm" id="runtime" name="runtime" value="<?php echo $vod['runtime']; ?>" placeholder="example: 43 minutes">
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="language">Language</label>
                                                                <input type="text" class="form-control form-control-sm" id="language" name="language" value="<?php echo $vod['language']; ?>" placeholder="example: English">
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="plot">Plot</label>
                                                                <textarea id="plot" name="plot" class="form-control form-control-sm" rows="5"><?php echo $vod['plot']; ?></textarea>
                                                            </div>

                                                            <div class="form-group row mb-4">
                                                                <label for="poster">Poster URL</label>
                                                                <input type="text" class="form-control form-control-sm" id="poster" name="poster" value="<?php echo $vod['poster']; ?>" placeholder="example: https://i.pinimg.com/originals/e3/9a/3d/e39a3d7e7b55c4ccf3331386a1653af9.jpg">
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <a href="?c=vod_tv" class="btn btn-default">Back</a>
                                                            <a href="actions.php?a=vod_tv_delete&id=<?php echo $vod['id']; ?>" class="btn btn-danger" onclick="return confirm( 'If this item is found again on the next folder scan then it will reappear. You must delete the actual file(s) to stop it appearing here again the future. \nAre you sure?' )">Delete</a>
                                                            <button type="submit" class="btn btn-success">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade show" id="custom-content-below-2" role="tabpanel" aria-labelledby="custom-content-below-2-tab">
                                                <?php 
                                                    // get show seasons
                                                    $seasons_and_episodes_table = '';
                                                    $query = $conn->query( "SELECT `season` FROM `vod_tv_files` WHERE `vod_id` = '".$vod['id']."' GROUP BY `season` ORDER BY `season` " );
                                                    $vod_seasons = $query->fetchAll( PDO::FETCH_ASSOC );

                                                    if( is_array( $vod_seasons ) ) {
                                                        foreach( $vod_seasons as $vod_season ) {
                                                            $seasons_and_episodes_table .= '
                                                            <h5>Season '.$vod_season['season'].'</h5>
                                                            <table class="table table-bordered table-striped" style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="1px" class="text-center">Episode</th>
                                                                        <th>Title</th>
                                                                        <th>Aired</th>
                                                                        <th>Runtime</th>
                                                                    </tr>
                                                                </thead>
                                                                    <tbody>
                                                            ';

                                                            // get season episodes
                                                            $query = $conn->query( "SELECT `id`, `title`, `episode`, `release_date`, `runtime`, `match` FROM `vod_tv_files` WHERE `vod_id` = '".$vod['id']."' AND `season` = '".$vod_season['season']."' GROUP BY `episode` ORDER BY `episode` ASC" );
                                                            $vod_season_episodes = $query->fetchAll( PDO::FETCH_ASSOC );
                                                            if( is_array( $vod_season_episodes ) ) {
                                                                foreach( $vod_season_episodes as $vod_season_episode ) {
                                                                    $seasons_and_episodes_table .= '
                                                                        <tr>
                                                                            <td>'.stripslashes( $vod_season_episode['episode'] ).'</td>
                                                                            <td>'.stripslashes( $vod_season_episode['title'] ).'</td>
                                                                            <td>'.stripslashes( $vod_season_episode['release_date'] ).'</td>
                                                                            <td>'.stripslashes( $vod_season_episode['runtime'] ).'</td>
                                                                        </tr>
                                                                    ';
                                                                }
                                                            }

                                                            $seasons_and_episodes_table .= '
                                                                    </tbody>
                                                                </table>

                                                                <br><br>
                                                            ';
                                                        }
                                                    }
                                                ?>

                                                <div class="card">
                                                    <div class="card-body table-responsive">
                                                        <?php echo $seasons_and_episodes_table; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
                <div class="p-3">
                    <h5>Title</h5>
                    <p>Sidebar content</p>
                </div>
            </aside>

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="float-right d-none d-sm-inline">
                     <?php
                        $time = microtime();
                        $time = explode( ' ', $time );
                        $time = $time[1] + $time[0];
                        $finish = $time;
                        $total_time = round( ( $finish - $start) , 4 );
                    ?>
                    Page generated in <?php echo $total_time; ?> seconds.
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy; 2019-<?php echo date( "Y", time() ); ?> <a href="https://stiliam.com">Stiliam CMS</a>.</strong> All rights reserved. | Version 1 alpha
            </footer>
        </div>

        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>

        <!-- DataTables -->
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

        <!-- Select2 -->
        <script src="plugins/select2/js/select2.full.min.js"></script>

        <!-- Bootstrap4 Duallistbox -->
        <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

        <!-- Snackbar Notifications -->
        <script src="plugins/notification/snackbar/snackbar.min.js"></script>

        <!-- JustGage -->
        <script src="plugins/justgage/raphael-2.1.4.min.js"></script>
        <script src="plugins/justgage/justgage.js"></script>

        <!-- InputMask -->
        <script src="plugins/moment/moment.min.js"></script>
        <script src="plugins/inputmask/jquery.inputmask.min.js"></script>

        <!-- datetimepicker -->
        <script src="plugins/datetimepicker/jquery.datetimepicker.js"></script>

        <!-- Ekko Lightbox -->
        <script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>

        <!-- Filterizr-->
        <script src="plugins/filterizr/jquery.filterizr.min.js"></script>

        <!-- global js -->
        <script>
            function overlay_show(){
                $.LoadingOverlay( 'show' );

                setTimeout(function(){
                    overlay_hide();
                }, 3000);
            }

            function overlay_show_until_reload(){
                $.LoadingOverlay( 'show' );
            }

            function overlay_hide(){
                $.LoadingOverlay("hide");
            }

            $( '#checkAll' ).change(function () {
                $( '.chk' ).prop( 'checked', this.checked);
                $( '#multi_options_show' ).removeClass("hidden");
            });

            $(".chk").change(function () {
                if ($(".chk:checked").length == $(".chk").length) {
                    $( '#checkAll' ).prop( 'checked', 'checked' );
                } else {
                    $( '#checkAll' ).prop( 'checked', false);
                }
            });

            // enable select2
            $( 'select.select2' ).select2( {width: '100%'} );
            // $( 'select' ).select2( { width: '100%' } );
            
            // data tables > noconfig
            $(function () {
                $( '#noconfig' ).DataTable({
                    "order": [[ 0, "asc" ]],
                    "responsive": true,
                    "columnDefs": [{
                        "targets"  : 'no-sort',
                        "orderable": false,
                    }],
                    "language": {
                        "emptyTable": "No data found."
                    },
                    "paging": false,
                    "processing": false,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    search: {
                       search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                    }
                });
            });

            // image lazyload
            let script = document.createElement( "script" );
            script.async = true;
            script.src =
                "https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.8/lazysizes.min.js";
            document.body.appendChild(script);

            function show_multi_options() {
                $( '#multi_options' ).removeClass( "d-none" );
            }

            // set the web player iframe src for new player
            function set_webplayer_source( url ){
                console.log( 'URL: '+url );
                var el = document.getElementById( 'webplayer_iframe' );
                el.src = 'cms_webplayer.php?' + url;
            }

            // reset webplayer source when modal is closed
            $( document ).on( 'hidden.bs.modal', function (e) {
                if( e.target.id === 'webplayer_modal' ) {
                    var el = document.getElementById( 'webplayer_iframe' );
                    el.src = ''; 
                } 
            });

            // show hide content depending on selected multi option selected - live channels
            function multi_options_select( val ){
                $( '#multi_options_add_to_bouquet' ).addClass( 'd-none' );
                $( '#multi_options_change_category' ).addClass( 'd-none' );
                $( '#multi_options_change_user_agent' ).addClass( 'd-none' );
                $( '#multi_options_change_channel_topology' ).addClass( 'd-none' );

                if( val == 'add_to_bouquet' ){
                    $( '#multi_options_add_to_bouquet' ).removeClass( 'd-none' );
                }

                if( val == 'change_category' ){
                    $( '#multi_options_change_category' ).removeClass( 'd-none' );
                }

                if( val == 'change_user_agent' ){
                    $( '#multi_options_change_user_agent' ).removeClass( 'd-none' );
                }

                if( val == 'change_channel_topology' ){
                    $( '#multi_options_change_channel_topology' ).removeClass( 'd-none' );
                }
            }

            // show hide content depending on selected multi option selected - 247 channels
            function multi_options_247_select( val ){
                $( '#multi_options_add_to_bouquet' ).addClass( 'd-none' );

                if( val == 'add_to_bouquet' ){
                    $( '#multi_options_add_to_bouquet' ).removeClass( 'd-none' );
                }
            }

            // show hide content depending on selected multi option selected - tv vod
            function multi_vod_tv_options_select( val ){
                $( '#multi_options_change_category' ).addClass( 'd-none' );
                $( '#multi_options_add_to_bouquet' ).addClass( 'd-none' );

                if( val == 'change_category' ){
                    $( '#multi_options_change_category' ).removeClass( 'd-none' );
                }

                if( val == 'add_to_bouquet' ){
                    $( '#multi_options_add_to_bouquet' ).removeClass( 'd-none' );
                }
            }

            // show hide content depending on selected multi option selected - movie vod
            function multi_vod_options_select( val ){
                $( '#multi_options_change_category' ).addClass( 'd-none' );
                $( '#multi_options_add_to_bouquet' ).addClass( 'd-none' );

                if( val == 'change_category' ){
                    $( '#multi_options_change_category' ).removeClass( 'd-none' );
                }

                if( val == 'add_to_bouquet' ){
                    $( '#multi_options_add_to_bouquet' ).removeClass( 'd-none' );
                }
            }
        </script>

        <?php if( $global_settings['lockdown'] == true ) { ?>
            <?php party(); ?>
            <script>
                $( window).on( 'load',function() {
                    $( '#party' ).modal(
                        { 
                            backdrop: 'static', 
                            keyboard: false, 
                        }
                    );

                    $( "#party" ).css( {
                        background:"rgb(0, 0, 0)",
                        opacity: ".50 !important",
                        filter: "Alpha(Opacity=50)",
                    } );
                } );
            </script>
        <?php } ?>

        <?php if( $global_settings['cms_terms_accepted'] == 'no' ) { ?>
            <?php accept_terms_modal(); ?>
            <script>
                $( window).on( 'load',function() {
                    $( '#cms_terms_modal' ).modal(
                        { 
                            backdrop: 'static', 
                            keyboard: false, 
                        }
                    );

                    $( "#cms_terms_modal" ).css( {
                        background:"rgb(0, 0, 0)",
                        opacity: ".50 !important",
                        filter: "Alpha(Opacity=50)",
                    } );
                } );
            </script>
        <?php } ?>

        <?php if( !empty($_SESSION['alert']['status'] ) ) { ?>
            <script>
                /*
                    document.getElementById( 'status_message' ).innerHTML = '<div class="col-lg-12 col-12 layout-spacing"><div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> mb-4" role="alert"><?php echo $_SESSION['alert']['message']; ?></div></div>';
                    setTimeout(function() {
                        $( '#status_message' ).fadeOut( 'fast' );
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

        <?php if( get( 'c' ) == 'backup_manager' ) { ?>
            <script>
                // data tables > table_backups
                $(function () {
                    $( '#table_backups' ).DataTable({
                        // "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No backups found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'banned_ips' ) { ?>
            <script>
                // data tables > table_banned_ips
                $(function () {
                    $( '#table_banned_ips' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Banned IPs found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'banned_isps' ) { ?>
            <script>
                // data tables > table_banned_isps
                $(function () {
                    $( '#table_banned_isps' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Banned ISPs found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'bouquets' ) { ?>
            <script>
                // data tables > table_bouquets
                $(function () {
                    $( '#table_bouquets' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No bouquets found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'bouquet' ) { ?>
            <script>
                // Bootstrap Duallistbox
                $( '.duallistbox' ).bootstrapDualListbox()

                // auto save order when row is moved
                $( ".row_position" ).sortable({
                    delay: 150,
                    stop: function() {
                        var selectedData = new Array();
                        $( '.row_position>tr' ).each( function() {
                            selectedData.push( $( this ).attr( "id" ) );
                        });
                        updateOrder( selectedData );
                    }
                });

                function updateOrder( data ) {
                    $.ajax({
                        url:"actions.php?a=bouquet_streams_order_update&id=<?php echo get( 'id' ); ?>",
                        type:'post',
                        data:{position:data},
                        success:function() {
                            // alert( 'your change successfully saved' );
                        }
                    })
                }

                // data tables > bouquet_content
                $(function () {
                    $( '#table_bouquet_content' ).DataTable({
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No bouquet contents found."
                        },
                        "paging": false,
                        "processing": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": false,
                        "info": false,
                        "autoWidth": true,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channels' ) { ?>
            <script>
                // data tables > table_channels
                $(function () {
                    $( '#table_channels' ).DataTable({
                        "order": [[ 1, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Live Channels found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channel' ) { ?>
            <script>
                // auto save order when row is moved
                $( ".row_position" ).sortable({
                    delay: 150,
                    stop: function() {
                        var selectedData = new Array();
                        $( '.row_position>tr' ).each( function() {
                            selectedData.push( $( this ).attr( "id" ) );
                        });
                        updateOrder( selectedData );
                    }
                });

                function updateOrder( data ) {
                    $.ajax({
                        url:"actions.php?a=channel_sources_order_update&id=<?php echo get( 'id' ); ?>",
                        type:'post',
                        data:{position:data},
                        success:function() {
                            // alert( 'your change successfully saved' );
                        }
                    })
                }

                // data tables > table_channel_sources
                $(function () {
                    $( '#table_channel_sources' ).DataTable({
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No sources found."
                        },
                        "paging": false,
                        "processing": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": false,
                        "info": false,
                        "autoWidth": true,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });

                // data tables > table_channel_server_stats
                $(function () {
                    $( '#table_channel_server_stats' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": false,
                        "processing": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": false,
                        "info": false,
                        "autoWidth": true,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>

            <?php $servers = get_all_servers(); ?>

            <?php 
                $sql = "
                    SELECT `topology` 
                    FROM `channels` 
                    WHERE `id` = '".get( 'id' )."' 
                ";
                $query      = $conn->query($sql);
                $data       = $query->fetch( PDO::FETCH_ASSOC );
                if( !empty( $data['topology'] ) ) {
                    $topology   = unserialize( $data['topology'] );
                }

                if( isset( $topology ) ) {
                    $json = '';
                    foreach( $topology as $server ) {
                        // find primary
                        if( $server['type'] == 'primary' ) {
                            // write out the primary server line
                            $json .= "{ 'name': '".$server['name']."', 'title': '".$server['title']." <a href=\"actions.php?a=channel_topology_delete&&channel_id=".get( 'id' )."&server_id=".$server['server_id']."&type=primary\" style=\"color: red;\">[ X ]</a>', 'className': '".$server['class']."', 'server_id': '".$server['server_id']."', ";

                            // placeholder for any secondaries
                            $json .= "'children': [";

                            // find this find this primaries secondaries
                            foreach( $topology as $sub_server ) {
                                // only look for secondaries
                                if( $sub_server['type'] == 'secondary' && $sub_server['parent'] == $server['server_id'] ) {
                                    // secondary found
                                    $json .= "{ 'name': '".$sub_server['name']."', 'title': '".$sub_server['title']." <a href=\"actions.php?a=channel_topology_delete&channel_id=".get( 'id' )."&server_id=".$sub_server['server_id']."&type=secondary\" style=\"color: red;\">[ X ]</a>', 'className': '".$sub_server['class']."', 'server_id': '".$sub_server['server_id']."' },";
                                }
                            }

                            // clear secondaries
                            unset( $sub_server );

                            // close any secondaries
                            $json .= "] ";

                            // close this primary server line
                            $json .= "}, ";
                        }
                    }
                } else {
                    $json = '';
                }
            ?>

            <script type="text/javascript" src="OrgChart/dist/js/jquery.orgchart.min.js"></script>

            <script type="text/javascript">
                $(function () {
                    var datascource = {
                        'name': "Channel Source",
                        'title': '',
                        'children': [
                            <?php echo $json; ?>
                        ]
                    };

                    $( "#chart-container" ).orgchart({
                        data: datascource,
                        nodeContent: "title",
                        exportButton: false,
                    });
                });

                $( '.oci' ).removeClass( 'oci' ).removeClass( 'oci-leader' ).removeClass( 'symbol' );

                function direct_or_restream(selectObject) {
                    var method = selectObject.value; 

                    if ( method == 'direct' ) {
                        $( "#direct_options" ).removeClass( "d-none" );
                        $( "#restream_options" ).addClass( "d-none" );
                        $( "#topology_options" ).addClass( "d-none" );
                    } else {
                        $( "#direct_options" ).addClass( "d-none" );
                        $( "#restream_options" ).removeClass( "d-none" );
                        $( "#topology_options" ).removeClass( "d-none" );
                    }
                }
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channels_247' ) { ?>
            <script>
                // data tables > table_channels_247
                $(function () {
                    $( '#table_channels_247' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No 24/7 Channels found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channel_icons' ) { ?>
            <script>
                $(document).ready(function() {
                    $( '#myfiles' ).on( "change", function() {
                        var myfiles = document.getElementById( "myfiles" );
                        var files = myfiles.files;
                        var data = new FormData();

                        for (i = 0; i < files.length; i++) {
                            data.append( 'file' + i, files[i]);
                        }

                        $.ajax({
                            url: 'actions.php?a=channel_icon_add', 
                            type: 'POST',
                            contentType: false,
                            data: data,
                            processData: false,
                            cache: false
                        }).done(function(msg) {
                            $( "#loadedfiles" ).append(msg);
                            <?php status_message( 'success', "Channel Icon has been uploaded." ); ?>
                            setTimeout( function() { location.reload(); }, 3000 );
                        });
                    });
                });

                // data tables > table_channel_icons
                $(function () {
                    $( '#table_channel_icons' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No icons found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channel_categories' ) { ?>
            <script>
                // data tables > table_channel_categories
                $(function () {
                    $( '#table_channel_categories' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Live Channel Categories found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channel_topology_profiles' ) { ?>
            <script>
                // data tables > table_channel_topology_profiles
                $(function () {
                    $( '#table_channel_topology_profiles' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No profiles found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'channel_topology_profile' ) { ?>
            <?php $servers = get_all_servers(); ?>

            <?php 
                $sql = "
                    SELECT * 
                    FROM `channels_topology_profiles` 
                    WHERE `id` = '".get( 'id' )."' 
                ";
                $query      = $conn->query($sql);
                $data       = $query->fetch( PDO::FETCH_ASSOC );
                if( !empty( $data['data'] ) ) {
                    $topology   = unserialize( $data['data'] );
                }

                if( isset( $topology ) ) {
                    $json = '';
                    foreach( $topology as $server ) {
                        // find primary
                        if( $server['type'] == 'primary' ) {
                            // write out the primary server line
                            $json .= "{ 'name': '".$server['name']."', 'title': '".$server['title']." <a href=\"actions.php?a=channel_topology_profile_delete_asset&&profile_id=".get( 'id' )."&server_id=".$server['server_id']."&type=primary\" style=\"color: red;\">[ X ]</a>', 'className': '".$server['class']."', 'server_id': '".$server['server_id']."', ";

                            // placeholder for any secondaries
                            $json .= "'children': [";

                            // find this find this primaries secondaries
                            foreach( $topology as $sub_server ) {
                                // only look for secondaries
                                if( $sub_server['type'] == 'secondary' && $sub_server['parent'] == $server['server_id'] ) {
                                    // secondary found
                                    $json .= "{ 'name': '".$sub_server['name']."', 'title': '".$sub_server['title']." <a href=\"actions.php?a=channel_topology_profile_delete_asset&profile_id=".get( 'id' )."&server_id=".$sub_server['server_id']."&type=secondary\" style=\"color: red;\">[ X ]</a>', 'className': '".$sub_server['class']."', 'server_id': '".$sub_server['server_id']."' },";
                                }
                            }

                            // clear secondaries
                            unset( $sub_server );

                            // close any secondaries
                            $json .= "] ";

                            // close this primary server line
                            $json .= "}, ";
                        }
                    }
                } else {
                    $json = '';
                }
            ?>

            <script type="text/javascript" src="OrgChart/dist/js/jquery.orgchart.min.js"></script>

            <script type="text/javascript">
                $(function () {
                    var datascource = {
                        'name': "Channel Source",
                        'title': '',
                        'children': [
                            <?php echo $json; ?>
                        ]
                    };

                    $( "#chart-container" ).orgchart({
                        data: datascource,
                        nodeContent: "title",
                        exportButton: false,
                    });
                });

                $( '.oci' ).removeClass( 'oci' ).removeClass( 'oci-leader' ).removeClass( 'symbol' );
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'customers' ) { ?>
            <script>
                function download_playlist( username, password ) {
                    $( "#download_url" ).val( "" );
                    $( "#download_type" ).val( "" );
                    $( "#download_button" ).attr( "disabled", true );
                    $( '.downloadModal' ).data( 'username', username );
                    $( '.downloadModal' ).data( 'password', password );
                    $( '.downloadModal' ).modal( 'show' );
                }

                $( "#download_type" ).change( function() {
                    if( $( "#download_type" ).val().length > 0) {
                        rText = "http://<?php echo $global_settings['cms_access_url_raw'];?>:<?php echo $global_settings['cms_port'];?>/get.php?username=" + $( '.downloadModal' ).data( 'username' ) + "&password=" + $( '.downloadModal' ).data( 'password' ) + "&" + decodeURIComponent($( '.downloadModal select' ).val());
                        if ($( "#download_type" ).find( ':selected' ).data( 'text' )) {
                            rText = $( "#download_type" ).find( ':selected' ).data( 'text' ).replace( "{DEVICE_LINK}", '"' + rText + '"' );
                            $( "#download_button" ).attr( "disabled", true );
                        } else {
                            $( "#download_button" ).attr( "disabled", false );
                        }
                        $( "#download_url" ).val( rText) ;
                    } else {
                        $( "#download_url" ).val( "" );
                    }
                });
                function doDownload() {
                    if ($( "#download_url" ).val().length > 0) {
                        window.open($( "#download_url" ).val());
                    }
                }
                function copyDownload() {
                    $( "#download_url" ).select();
                    document.execCommand( "copy" );
                }

                // data tables > table_customers
                $(function () {
                    $( '#table_customers' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No customers found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'customer' ) { ?>
            <script>
                function line_type( val ) {
                    $( '#m3u_hls' ).css( 'display', 'none' );
                    $( '#m3u_ts' ).css( 'display', 'none' );
                    $( '#m3u8_hls' ).css( 'display', 'none' );
                    $( '#m3u8_ts' ).css( 'display', 'none' );
                    $( '#dreambox_hls' ).css( 'display', 'none' );
                    $( '#dreambox_ts' ).css( 'display', 'none' );
                    $( '#webtv_hls' ).css( 'display', 'none' );
                    $( '#webtv_ts' ).css( 'display', 'none' );
                    $( '#octagon_hls' ).css( 'display', 'none' );
                    $( '#octagon_ts' ).css( 'display', 'none' );
                    $( '#enigma22_hls' ).css( 'display', 'none' );
                    $( '#enigma22_ts' ).css( 'display', 'none' );
                    $( '#enigma22custom_hls' ).css( 'display', 'none' );
                    $( '#enigma22custom_ts' ).css( 'display', 'none' );

                    if( val == 'm3u_hls' ) {
                        $( '#m3u_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'm3u_ts' ) {
                        $( '#m3u_ts' ).css( 'display', 'block' );
                    }

                    if( val == 'm3u8_hls' ) {
                        $( '#m3u8_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'm3u8_ts' ) {
                        $( '#m3u8_ts' ).css( 'display', 'block' );
                    }

                    if( val == 'dreambox_hls' ) {
                        $( '#dreambox_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'dreambox_ts' ) {
                        $( '#dreambox_ts' ).css( 'display', 'block' );
                    }

                    if( val == 'webtv_hls' ) {
                        $( '#webtv_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'webtv_ts' ) {
                        $( '#webtv_ts' ).css( 'display', 'block' );
                    }

                    if( val == 'octagon_hls' ) {
                        $( '#octagon_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'octagon_ts' ) {
                        $( '#octagon_ts' ).css( 'display', 'block' );
                    }

                    if( val == 'enigma22_hls' ) {
                        $( '#enigma22_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'enigma22_ts' ) {
                        $( '#enigma22_ts' ).css( 'display', 'block' );
                    }

                    if( val == 'enigma22custom_hls' ) {
                        $( '#enigma22custom_hls' ).css( 'display', 'block' );
                    }
                    if( val == 'enigma22custom_ts' ) {
                        $( '#enigma22custom_ts' ).css( 'display', 'block' );
                    }
                }

                var f2 = flatpickr(document.getElementById( 'dateTimeFlatpickr' ), {
                    enableTime: false,
                    dateFormat: "Y-m-d",
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'epg_sources' ) { ?>
            <script>
                // data tables > table_epg_sources
                $(function () {
                    $( '#table_epg_sources' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No EPG Sources found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'mag_devices' ) { ?>
            <script>
                // data tables > table_mag_devices
                $(function () {
                    $( '#table_mag_devices' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [50, 100, 500, 1000],
                        "pageLength": 50,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'monitored_folders' ) { ?>
            <script>
                // data tables > table_monitored_folder_247_channels
                $(function () {
                    $( '#table_monitored_folder_247_channels' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });

                // data tables > table_monitored_folder_vod
                $(function () {
                    $( '#table_monitored_folder_vod' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });

                // data tables > table_monitored_folder_vod_tv
                $(function () {
                    $( '#table_monitored_folder_vod_tv' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'open_connections' ) { ?>
            <script>
                // data tables > table_open_connections
                $(function () {
                    $( '#table_open_connections' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Connections found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'packages' ) { ?>
            <script>
                // data tables > table_packages
                $(function () {
                    $( '#table_packages' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No packages found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'package' ) { ?>
            <script>
                // Bootstrap Duallistbox
                $( '.duallistbox' ).bootstrapDualListbox()
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'playlist_manager' ) { ?>
            <script>
                $(document).ready(function() {
                    $( '#myfiles' ).on( "change", function() {
                        overlay_show();
                        var playlist_name = $( '#playlist_name' ).val();
                        var myfiles = document.getElementById( "myfiles" );
                        var files = myfiles.files;
                        var data = new FormData();

                        for (i = 0; i < files.length; i++) {
                            data.append( 'file' + i, files[i]);
                        }

                        $.ajax({
                            url: 'actions.php?a=playlist_add&playlist_name='+playlist_name, 
                            type: 'POST',
                            contentType: false,
                            data: data,
                            processData: false,
                            cache: false
                        }).done(function(msg) {
                            $( "#loadedfiles" ).append(msg);
                            <?php // status_message( 'success', "Playlist has been uploaded." ); ?>
                            setTimeout( function() { location.reload(); }, 3000 );
                        });
                    });
                });

                // data tables > table_playlists
                $(function () {
                    $( '#table_playlists' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'playlist' ) { ?>
            <script>
                // data tables > table_channel_icons
                $(function () {
                    $( '#table_playlist' ).DataTable({
                        "order": [[ 1, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [50, 100, 500, 1000],
                        "pageLength": 50,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'rtmp_management' ) { ?>
            <script>
                // data tables > table_allowed_ips
                $(function () {
                    $( '#table_allowed_ips' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [50, 100, 500, 1000],
                        "pageLength": 50,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });

                // data tables > table_rtmp_streams
                $(function () {
                    $( '#table_rtmp_streams' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No data found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [50, 100, 500, 1000],
                        "pageLength": 50,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'sandbox' ) { ?>
            <script>
                // set the web player iframe src for new player
                function set_webplayer_source( url ){
                    console.log( 'URL: '+url );
                    var el = document.getElementById( 'webplayer_iframe' );
                    el.src = 'cms_webplayer.php?' + url;
                }

                // reset webplayer source when modal is closed
                $( document ).on( 'hidden.bs.modal', function (e) {
                    if( e.target.id === 'webplayer_modal' ) {
                        var el = document.getElementById( 'webplayer_iframe' );
                        el.src = ''; 
                    } 
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'settings' ) { ?>
            <script>
                // data tables > table_licenses
                $(function () {
                    $( '#table_licenses' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No licenses found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'servers' ) { ?>
            <script>
                // data tables > table_servers
                $(function () {
                    $( '#table_servers' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No servers found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'server' ) { ?>
            <?php $server = server_details( get( 'id' ) ); ?>

            <?php
                // geoip
                $geoip_lookup = $geoip->get( $server['wan_ip_address'] );
                $geoip_lookup = objectToArray( $geoip_lookup );
            ?>

            <!-- jvectormap  -->
            <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
            <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

            <script>
                /* jVector Maps
               * ------------
               * Create a world map with markers
               */

                $('#world-map-markers').vectorMap( {
                    map: 'world_mill_en', normalizeFunction: 'polynomial', hoverOpacity: 0.7, hoverColor: false, backgroundColor: 'transparent', regionStyle: {
                        initial: {
                            fill: 'rgba(210, 214, 222, 1)', 'fill-opacity': 1, stroke: 'none', 'stroke-width': 0, 'stroke-opacity': 1
                        }, 
                        hover: {
                            'fill-opacity': 0.7, cursor: 'pointer'
                        }, 
                        selected: {
                            fill: 'yellow'
                        }, 
                        selectedHover: {}
                    }, 
                    markerStyle: {
                        initial: {
                            fill: '#00a65a', stroke: '#111'
                        }
                    }, 
                    markers : [
                        { latLng: [<?php echo $geoip_lookup['location']['latitude']; ?>, <?php echo $geoip_lookup['location']['longitude']; ?>], name: '<?php echo $server['name']; ?>' },
                    ]
                }

                );

                // copy server install code
                function copyDownload() {
                    $( "#download_url" ).select();
                    document.execCommand( "copy" );
                }
            </script>

            <?php if( $server['status'] == 'pending' ) { ?>
                <script>
                    var isWorking = false;
                    function get_install_state() {
                        if( isWorking ) return;
                        isWorking = true;
                        $.ajax({
                            url: 'actions.php?a=server_install_progress&id=<?php echo $server['id']; ?>',
                            success: function( progress ) {
                                console.log( 'Progress: ' + progress + '%' );

                                if( progress > '0' ) {
                                    location.reload( true );
                                }

                                // release for next update process
                                isWorking = false;
                            },
                            cache: false
                        });
                    }

                    setInterval( get_install_state, 10000 );
                </script>
            <?php } ?>

            <?php if( $server['status'] == 'installing' ) { ?>
                <script>
                    var isWorking = false;
                    function get_install_progress() {
                        if( isWorking ) return;
                        isWorking = true;
                        $.ajax({
                            url: 'actions.php?a=server_install_progress&id=<?php echo $server['id']; ?>',
                            success: function( progress ) {
                                console.log( 'Progress: ' + progress + '%' );

                                if( progress == '100' ) {
                                    location.reload( true );
                                } else {
                                    $( '#server_install_progress' ).css( 'width', progress + '%' );
                                    $( '#server_install_progress' ).html(progress + '%' );
                                }

                                // release for next update process
                                isWorking = false;
                            },
                            cache: false
                        });
                    }

                    setInterval( get_install_progress, 1000 );
                </script>
            <?php } ?>

            <script>
                // set the iframe source for a new terminal connection
                function new_server_ssh_iframe() {
                    var el = document.getElementById( 'server_ssh_iframe' );
                    el.src = 'http://<?php echo $server['wan_ip_address']; ?>:4200/';
                }

                // reset iframe source when modal is closed
                $( "#server_ssh_modal" ).on( "hidden.bs.modal", function () {
                    var el = document.getElementById( 'server_ssh_iframe' );
                    el.src = ''; 
                });

                // set vars for stats gauges
                var cpu_usage_gage, ram_usage_gage, bandwidth_down_gage, bandwidth_up_gage;

                // cpu gage
                var cpu_usage_gage = new JustGage({
                    id: "cpu_usage_gage",
                    value: 0,
                    min: 0,
                    max: 100,
                    title: "CPU Usage",
                    symbol : "%",
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
                    relativeGaugeSize: true
                });

                // update system_stats data gauges
                <?php if( $server['status'] == 'online' ) { ?>
                    var isWorking = false;
                    function update_system_stats() {
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

        <?php if( get( 'c' ) == 'transcoding_profiles' ) { ?>
            <script>
                // data tables > table_transcoding_profiles
                $(function () {
                    $( '#table_transcoding_profiles' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No profiles found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'transcoding_profile' ) { ?>
            <script>
                function channel_set_transcode_type( selectObject ) {
                    var transcode_type = selectObject.value; 

                    // hide everything, its just easier and less complicated
                    $( "#transcoding_cpu_options" ).addClass( "d-none" );
                    $( "#transcoding_gpu_options" ).addClass( "d-none" );
                    $( "#transcode_options" ).addClass( "d-none" );

                    if( transcode_type == 'copy' ) {
                        $( "#transcoding_cpu_options" ).addClass( "d-none" );
                        $( "#transcoding_gpu_options" ).addClass( "d-none" );
                        $( "#transcode_options" ).addClass( "d-none" );
                        $( "#transcoding_audio_options_select" ).addClass( "d-none" );
                        $( "#transcoding_audio_options" ).addClass( "d-none" );
                    }
                    if( transcode_type == 'cpu' ) {
                        $( "#transcoding_cpu_options" ).removeClass( "d-none" );
                        $( "#transcode_options" ).removeClass( "d-none" );
                        $( "#transcoding_audio_options_select" ).removeClass( "d-none" );
                        $( "#transcoding_audio_options" ).removeClass( "d-none" );
                    }
                    if( transcode_type == 'gpu' ) {
                        $( "#transcoding_gpu_options" ).removeClass( "d-none" );
                        $( "#transcode_options" ).removeClass( "d-none" );
                        $( "#transcoding_audio_options_select" ).removeClass( "d-none" );
                        $( "#transcoding_audio_options" ).removeClass( "d-none" );
                    }
                }

                function channel_set_transcode_audio( selectObject ) {
                    var audio_codec = selectObject.value; 

                    if( audio_codec == 'copy' ) {
                        $( "#transcoding_audio_options" ).addClass( "d-none" );
                    }
                    if( audio_codec != 'copy' ) {
                        $( "#transcoding_audio_options" ).removeClass( "d-none" );
                    }
                }
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'users' ) { ?>
            <script>
                // data tables > table_users
                $(function () {
                    $( '#table_users' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No users found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'vod' ) { ?>
            <script>
                $(function () {
                    $(document).on( "click", '[data-toggle="lightbox"]', function (event) {
                        event.preventDefault();
                        $(this).ekkoLightbox({
                            alwaysShowClose: true,
                        });
                    });

                    $( ".filter-container" ).filterizr({ gutterPixels: 3 });
                    $( ".btn[data-filter]" ).on( "click", function () {
                        $( ".btn[data-filter]" ).removeClass( "active" );
                        $(this).addClass( "active" );
                    });
                });

                // data tables > table_vod
                $(function () {
                    $( '#table_vod' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Movies found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });

                // gallary search function
                $(function () {
                    $( '.filter' ).filterizr({
                        option
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'vod_tv' ) { ?>
            <script>
                $(function () {
                    $(document).on( "click", '[data-toggle="lightbox"]', function (event) {
                        event.preventDefault();
                        $(this).ekkoLightbox({
                            alwaysShowClose: true,
                        });
                    });

                    $( ".filter-container" ).filterizr({ gutterPixels: 3 });
                    $( ".btn[data-filter]" ).on( "click", function () {
                        $( ".btn[data-filter]" ).removeClass( "active" );
                        $(this).addClass( "active" );
                    });
                });

                // data tables > table_vod_tv
                $(function () {
                    $( '#table_vod_tv' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No TV Shows found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });

                // gallary search function
                $(function () {
                    $( '.filter' ).filterizr({
                        option
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'vod_categories' ) { ?>
            <script>
                // data tables > table_vod_categories
                $(function () {
                    $( '#table_vod_categories' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No Movies VoD Categories found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( get( 'c' ) == 'vod_tv_categories' ) { ?>
            <script>
                // data tables > table_vod_tv_categories
                $(function () {
                    $( '#table_vod_tv_categories' ).DataTable({
                        "order": [[ 0, "asc" ]],
                        "responsive": true,
                        "columnDefs": [{
                            "targets"  : 'no-sort',
                            "orderable": false,
                        }],
                        "language": {
                            "emptyTable": "No TV VoD Categories found."
                        },
                        "paging": true,
                        "processing": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "lengthMenu": [25, 50, 100, 500, 1000],
                        "pageLength": 25,
                        search: {
                           search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                        }
                    });
                });
            </script>
        <?php } ?>

        <?php if( $global_settings['live_support_addon'] == true ) { ?>
            <script type="text/javascript">
                var Tawk_API = Tawk_API || {};
                Tawk_API.visitor = {
                    name : '<?php echo $account_details['first_name'].' '.$account_details['last_name']; ?>',
                    email : '<?php echo $account_details['email']; ?>',
                    hash : '',
                    cms_url : 'http://<?php echo $global_settings['cms_access_url']; ?>',
                };
                Tawk_LoadStart = new Date();
                (function () {
                    var s1 = document.createElement( "script" ),
                        s0 = document.getElementsByTagName( "script" )[0];
                    s1.async = true;
                    s1.src = "https://embed.tawk.to/5edf10bc4a7c6258179a3413/default";
                    s1.charset = "UTF-8";
                    s1.setAttribute( "crossorigin", "*" );
                    s0.parentNode.insertBefore(s1, s0);
                })();
            </script>
        <?php } ?>

        <form method="post" class="form-horizontal">
            <div class="modal fade" id="webplayer_modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">CMS Webplayer</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <iframe id="webplayer_iframe" width="100%" height="600px" src="" class="player-frame" allowfullscreen></iframe>

                                <div class="player-frame-wrapper-ratio"></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            // disable the overlay
            $.LoadingOverlay("hide");
        </script>
    </body>
</html>
