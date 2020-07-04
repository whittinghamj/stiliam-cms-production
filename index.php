<?php
session_start();
include ('inc/db.php');
include ('inc/global_vars.php');
include ('inc/functions.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php echo $site['title']; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- icons -->
        <link rel="apple-touch-icon" sizes="57x57" href="img/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="img/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="img/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="img/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="img/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="img/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="img/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="img/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="img/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="img/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
        <link rel="manifest" href="img/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />

        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css" />

        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css" />

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <img src="img/stiliam_logo.png" alt="Logo" class="img-circle" width="25%" style="opacity: 0.8;" />
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign In Required</p>

                    <form action="login.php" method="post">
                        <div class="input-group mb-3">
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="dist/js/adminlte.min.js"></script>

        <script>
            $(function() { $('[name="username"]').focus(); });
        </script>

        <!-- background image -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js"></script>

        <script>
            $.backstretch([
                // "img/background_1.jpg",
                "img/background_2.jpg",
            ], {duration: 5000, fade: 750});
        </script>
    </body>
</html>
