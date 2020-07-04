<?php
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
   </head>
   <body class="login-main-body">
      <section class="login-main-wrapper">
         <div class="container-fluid pl-0 pr-0">
            <div class="row no-gutters">
               <div class="col-md-12 p-5 bg-white full-height">
                  <div class="login-main-left">
                     <div class="text-center mb-5 login-main-left-header pt-4">
                        <img src="img/favicon.png" class="img-fluid" alt="LOGO">
                        <h5 class="mt-3 mb-3">Welcome to Moko IPTV Player</h5>
                        <p>It is a long established fact that a reader <br> will be distracted by the readable.</p>
                     </div>
                      <form method="post" action="watch.php" enctype="multipart/form-data">
                        <div class="form-group">
                           <label>IPTV Playlist URL</label>
                           <input type="text" name="iptv" id="iptv" class="form-control" style="color: white;" placeholder="https://xxxxxxx.com/username/password.m3u">
                        </div>
                        <strong>OR</strong><br><br>
                        <div class="form-group">
                           <label>IPTV File</label>
                           <input type="file" name="iptvfile" id="iptvfile" class="form-control">
                        </div>
                        <div class="mt-4">
                           <div class="row">
                              <div class="col-12">
                                 <button type="submit" name="submit" class="btn btn-outline-primary btn-block btn-lg">Watch</button>
                              </div>
                           </div>
                        </div>
                     <div class="text-center mt-5">
                       <!-- <p class="light-gray">Don’t have an account? <a href="register.html">Sign Up</a></p> -->
                     </div>
                      </form>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Bootstrap core JavaScript-->
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- Core plugin JavaScript-->
      <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
      <!-- Owl Carousel -->
      <script src="vendor/owl-carousel/owl.carousel.js"></script>
      <!-- Custom scripts for all pages-->
      <script src="js/main.js"></script>
   </body>
</html>
