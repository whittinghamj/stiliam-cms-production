<?php
require_once 'parser.php';
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="NodeTent">
      <meta name="author" content="NodeTent">
      <title><?=$title?></title>
      <!-- Favicon Icon -->
      <link rel="icon" type="image/png" href="img/favicon.png">
      <!-- Bootstrap core CSS-->
      <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Custom fonts for this template-->
      <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
      <!-- Custom styles for this template-->
      <link href="css/style.css" rel="stylesheet">
      <!-- Owl Carousel -->
      <link rel="stylesheet" href="vendor/owl-carousel/owl.carousel.css">
      <link rel="stylesheet" href="vendor/owl-carousel/owl.theme.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/clappr.chromecast-plugin/latest/clappr-chromecast-plugin.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/mokoshalb/clappr-ads/ads.js"></script>
       <script type="text/javascript" src="js/player-error.js"></script>
   </head>
   <body id="page-top">
      <nav class="navbar navbar-expand navbar-light bg-white static-top osahan-nav sticky-top">
         &nbsp;&nbsp;
         <button class="btn btn-link btn-sm text-secondary order-1 order-sm-0" id="sidebarToggle">
         <i class="fas fa-bars"></i>
         </button> &nbsp;&nbsp;
         <!--<a class="navbar-brand mr-1" href="/"><img class="img-fluid" alt="" src="img/logo.png"></a>-->
          <!--Navbar Search -->
         <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-5 my-2 my-md-0 osahan-navbar-search">
            <div class="input-group">
               <input type="text" class="form-control search" onkeyup="bait(this)" placeholder="Search for...">
               <div class="input-group-append">
                  <button class="btn btn-light sort" type="button">
                  <i class="fas fa-search"></i>
                  </button>
               </div>
            </div>
         </div>
         <!-- Navbar -->
         <ul class="navbar-nav ml-auto ml-md-0 osahan-right-navbar">
            <li class="nav-item mx-1">
               <a class="nav-link" href="<?=$site_url?>">
               <i class="fas fa-plus-circle fa-fw"></i>
               Upload New Playlist
               </a>
            </li>
         </ul>
      </nav>
      <div id="wrapper">
          <div id="lists">
         <!-- Sidebar -->
         <ul id="list" class="sidebar navbar-nav list">
<?php foreach ($items as $item) {?>
            <li class="nav-item">
               <a class="channel nav-link" data-value="<?php echo $item['tvmedia']; ?>" href="javascript:void(0);"><?php echo $item['tvtitle']; ?></a>
            </li>
<?php } ?>
         </ul>
         </div>
         <div id="content-wrapper">
            <div class="container-fluid pb-0">
               <div class="top-mobile-search">
                  <div class="row">
                     <div class="col-md-12">
                        <form class="mobile-search">
                           <div class="input-group">
                             <input type="text" placeholder="Search for..." class="form-control">
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
                                    source: "<?=$item['tvmedia'];?>",
                                    mimeType: 'application/x-mpegURL',
                                    width: '100%',
                                    height: 'calc(100vh - 100px)',
                                    autoPlay: false,
                                    watermark: "<?=$watermarkImage?>",
                                    watermarkLink: "<?=$watermarkLink?>",
                                    position: 'bottom-right',
                                    mediacontrol: {seekbar: "#00FF00", buttons: "#FFFFFF"},
                                    mute: false,
                                    gaAccount: "<?=$gaCode?>",
                                    disableErrorScreen: true, // Disable the internal error screen plugin
                                    plugins: [ChromecastPlugin,ErrorPlugin,LevelSelector,ClapprAds],
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
                                    ads: {
                                        preRoll: {
                                            src: '<?=$prerollAds?>',
                                            skip: true,
                                            timeout: <?=$prerollTime?>,
                                            link: '<?=$prerollLink?>'
                                        },
                                        text: {
                                            wait: 'Wait % seconds...',
                                            skip: 'Skip >>>'
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
               <hr class="mt-0">
            </div>
         </div>
         <!-- /.content-wrapper -->
      </div>
      <!-- /#wrapper -->
      <!-- Bootstrap core JavaScript-->
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- Core plugin JavaScript-->
      <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
      <!-- Owl Carousel -->
      <script src="vendor/owl-carousel/owl.carousel.js"></script>
      <script src="js/list.min.js"></script>
      <!-- Custom scripts for all pages-->
      <script src="js/main.js"></script>
   </body>
</html>
