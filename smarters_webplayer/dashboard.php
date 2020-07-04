<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 */

include "includes/header.php";
include "includes/sideNav.php";
echo "<style>\r\n.messagePerm\r\n{\r\n    position: absolute;\r\n    width: 70%;\r\n    left: 15%;\r\n}\r\n.dontShow\r\n{\r\n    position: relative;\r\n    bottom: 6px;\r\n    font-size: 11px;\r\n    opacity: 1;\r\n    right: -35px;\r\n    color: #000;\r\n    margin-top: 25px;\r\n}\r\n     \r\n.herelink {\r\n    color: #a039b1;\r\n    font-weight: 600;\r\n}     \r\n</style>\r\n<div class=\"container-fluid\">    \r\n    <div class=\"col-md-10 col-md-offset-1\">\r\n    ";
if ($headerparentcondition == "") {
    echo "        <div class=\"row\">\r\n          <div class=\"col-sm-2\">\r\n          </div>\r\n          <div class=\"col-sm-8\">\r\n            <div class=\"alert alert-warning\" style=\"position: relative;top: 20px;\">\r\n              <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a> \r\n              <strong style=\"font-weight: bold;\">Warning!</strong> \r\n               You don’t have set the PIN for Parental Control . Click here to set it up <a href=\"settings.php\" class=\"herelink\">here</a>\r\n            </div>\r\n          </div>\r\n          <div class=\"col-sm-2\">\r\n          </div>\r\n        </div> \r\n      ";
}
echo "    <div class=\"b-content home\">\r\n    \t<div class=\"col-md-4 col-sm-4 col-xs-12\">\r\n          <a href=\"live.php\">\r\n    \t\t    <div class=\"col-md-12 liveTV rippler rippler-default\">\r\n                <center><img src=\"img/Live_tv.png\"></center>\r\n                <h3 class=\"text-center\">LIVE TV</h3>\r\n            </div>\r\n          </a>\r\n    \t</div>\r\n\r\n    \t<div class=\"col-md-4 col-sm-4 col-xs-12\">\r\n        <a href=\"movies.php\">\r\n    \t\t  <div class=\"col-md-12 movieVOD rippler rippler-default\">\r\n                <center><img src=\"img/on_demand.png\"></center>\r\n                <h3 class=\"text-center\">MOVIES</h3>\r\n          </div>\r\n        </a>\r\n    \t</div>\r\n\r\n    \t<div class=\"col-md-4 col-sm-4 col-xs-12\">\r\n          <a href=\"series.php\">\r\n            <div class=\"col-md-12 tvSeries rippler rippler-default\">\r\n        \t\t\t<center><img src=\"img/series.png\"></center>\r\n        \t\t\t<h3 class=\"text-center\">SERIES</h3>            \r\n            </div>\r\n          </a>\r\n    \t</div>\r\n\r\n        <div class=\"col-md-8 col-sm-12 col-xs-12 Mybtns\">\r\n            <div class=\"col-md-4 col-sm-6 col-xs-12 hide\">\r\n                <a href=\"\" class=\"btn btn-default center-block rippler rippler-default\"><i class=\"fa fa-book\"></i> EPG</a>\r\n            </div>\r\n            <div class=\"col-md-4 col-sm-12 col-xs-12\">\r\n                <a class=\"btn btn-default center-block btn-lg account rippler rippler-default\" id=\"accountModal\"><i class=\"fa fa-info\"></i> ACCOUNT</a>\r\n            </div>\r\n            <div class=\"col-md-4 col-sm-12 col-xs-12\">\r\n                <a class=\"btn btn-default center-block btn-lg account rippler rippler-default\" href=\"catchup.php\"><i class=\"fa fa-clock-o\" ></i> CATCH UP</a>\r\n            </div>\r\n            <div class=\"col-md-4 col-sm-12 col-xs-12\">\r\n                <a href=\"\" class=\"btn btn-default center-block btn-lg logoutBtn rippler rippler-default\"><i class=\"fa fa-sign-out\" style=\"padding-top: 0px;\"></i> LOG OUT</a>\r\n            </div>\r\n        </div>\r\n        ";
$ExpiryData = "";
if ($_SESSION["webTvplayer"]["exp_date"] == "null" || $_SESSION["webTvplayer"]["exp_date"] == "") {
    $ExpiryData = "Unlimited";
} else {
    $ExpiryData = date("F d, Y", $_SESSION["webTvplayer"]["exp_date"]);
}
echo "        <h4 class=\"text-center\" style=\"color: #fff; top: 50px; float:left;width: 100%;position: relative;    text-transform: uppercase;\">Expiration: ";
echo $ExpiryData;
echo "</h4>\r\n    </div>\r\n</div>\r\n</div>\r\n";
include "includes/footer.php";

?>