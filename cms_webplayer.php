<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>CMS Webplayer</title>
        <meta http-equiv="content-type" content="text/html" ; charset="utf-8" />
        <!-- <meta name="copyright" content="ubaldo@eja.it" /> -->
        <link href="plugins/videojs/video-js.min.css" rel="stylesheet" />
        <link href="plugins/videojs/videojs.vast.vpaid.min.css" rel="stylesheet" />
        <script src="plugins/videojs/video.min.js"></script>
        <script src="plugins/videojs/videojs-http-streaming.min.js"></script>
        <script src="plugins/videojs/videojs_5.vast.vpaid.min.js"></script>
        <style>
            html,
            body {
                background: black;
                width: 100%;
                height: 100%;
                padding: 0;
                margin: 0;
            }
            #stiliamPlayer {
                position: absolute;
                display: none;
            }
        </style>
    </head>
    <body>
        <div><video id="stiliamPlayer" class="video-js vjs-default-skin vjs-big-play-centered" autoplay controls></video></div>
        <script type="text/javascript">
            window.onload = function () {
                videojs("stiliamPlayer").ready(function () {
                    /*
                    this.vastClient({
                        "adTagUrl": "https://syndication.exosrv.com/splash.php?idzone=3726559",
                        "adsCancelTimeout": 10000,
                        "adsEnabled": false
                    });
                    */
                    this.src({
                        src: document.location.search.slice(1),
                        type: "application/x-mpegURL",
                    });
                    this.fill(true);
                    this.responsive(true);

                    document.getElementById("stiliamPlayer").style.display = "block";
                    this.play();
                    this.on("playing", function () {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.open("GET", "/?playing=" + document.location.hash.slice(1), true);
                        xmlhttp.send(null);
                    });
                });
            };
        </script>
    </body>
</html>
