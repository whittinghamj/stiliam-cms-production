<?php

if(isset($_GET['dev']) && $_GET['dev'] == 'yes'){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
}

date_default_timezone_set('UTC');

session_start();

// includes
include('inc/db.php');
include('inc/global_vars.php');
include('inc/functions.php');

$url = get("url");
if(empty($url)){
    // die("url is missing");
}
$url = base64_decode( $url );

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="NodeTent" />
        <meta name="author" content="NodeTent" />
        <title>Web Player</title>
        <!-- Favicon Icon -->
        <link rel="icon" type="image/png" href="admin_webplayer/img/favicon.png" />
        <!-- Bootstrap core CSS-->
        <link href="admin_webplayer/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <!-- Custom fonts for this template-->
        <link href="admin_webplayer/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
        <!-- Custom styles for this template-->
        <link href="admin_webplayer/css/style.css" rel="stylesheet" />
        <!-- Owl Carousel -->
        <link rel="stylesheet" href="admin_webplayer/vendor/owl-carousel/owl.carousel.css" />
        <link rel="stylesheet" href="admin_webplayer/vendor/owl-carousel/owl.theme.css" />
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/clappr.chromecast-plugin/latest/clappr-chromecast-plugin.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/mokoshalb/clappr-ads/ads.js"></script>
        <script type="text/javascript" src="admin_webplayer/js/player-error.js"></script>
    </head>
    <body id="page-top">
        <nav class="navbar navbar-expand navbar-light bg-white static-top osahan-nav sticky-top">
            &nbsp;&nbsp;
            <button class="btn btn-link btn-sm text-secondary order-1 order-sm-0" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            &nbsp;&nbsp;
            <!--<a class="navbar-brand mr-1" href="/"><img class="img-fluid" alt="" src="img/logo.png"></a>-->
            <!--Navbar Search -->
            <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-5 my-2 my-md-0 osahan-navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control search" onkeyup="bait(this)" placeholder="Search for..." />
                    <div class="input-group-append">
                        <button class="btn btn-light sort" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <div id="wrapper">
            <div id="lists">
                <!-- Sidebar -->
                <ul id="list" class="sidebar navbar-nav list">

                </ul>
            </div>
            <div id="content-wrapper">
                <div class="container-fluid pb-0">
                    <div class="top-mobile-search">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="mobile-search">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search for..." class="form-control" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-dark"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="video-block section-padding">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="moko"></div>
                                <script>
                                    var playerElement = document.getElementById("moko");
                                    var player = new Clappr.Player({
                                        source: "http://185.120.4.147:25461/Deltatest2/TV5NFNJXZM/37143?token=HRpZA0JcEw4TVFMGXgYFX1IFWVxQVgdTUQteVgdUUVpfAVRQV1UFVwkXHEEVEUADVgs+UVEWW1UDCQxXGhBCEQNKbgtVFgsWAgcST0QRWwtSRFsJAA1TVAIAClYEHhUSD1wTWBMGAQIBBwBaREkQA08SBEpbVQ05UF1ODVVXFVgITV0OHRZeWm5RVQ4HC1ZEDURRGhkWCBVEGgIKQ15bTkRaWBZDVUVREw8SUFNcAkQbRAJXQFoVFE0aAkZxcBVORF1JFlRaQl1eWxJZRBFBRBtECEtqRgQVQEpdBVtXRUBcGgBAHRZcVUlqUwwIC1cFQw8OVkYWW0QFCBpIFF9YDA9MXhBuRFhQEw8SUVZTAlYFUUNF",
                                        mimeType: 'application/x-mpegURL',
                                        width: '100%',
                                        height: 'calc(100vh - 100px)',
                                        autoPlay: true,
                                        position: 'bottom-right',
                                        mediacontrol: {seekbar: "#00FF00", buttons: "#FFFFFF"},
                                        mute: false,
                                        disableErrorScreen: true, // Disable the internal error screen plugin
                                        plugins: [ChromecastPlugin,ErrorPlugin,LevelSelector],
                                        chromecast: {
                                            appId: "9DFB77C0",
                                            contentType: "video/m3u8",
                                            media: {
                                                type: ChromecastPlugin.None,
                                                title: "IPTV",
                                                subtitle: "IPTV Streaming Service"
                                            }
                                        },
                                        errorPlugin: {
                                            onRetry: function(e) {
                                            }
                                          },
                                        disableVideoTagContextMenu: true,
                                        playbackNotSupportedMessage: 'Your browser is not supported.'
                                    });
                                    player.attachTo(playerElement);
                                </script>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0" />
                </div>
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /#wrapper -->
        <!-- Bootstrap core JavaScript-->
        <script src="admin_webplayer/vendor/jquery/jquery.min.js"></script>
        <script src="admin_webplayer/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="admin_webplayer/vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Owl Carousel -->
        <script src="admin_webplayer/vendor/owl-carousel/owl.carousel.js"></script>
        <script src="admin_webplayer/js/list.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="admin_webplayer/js/main.js"></script>
    </body>
</html>
