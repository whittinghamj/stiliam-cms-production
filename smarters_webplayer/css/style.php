<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 */

header("Content-type: text/css");
print_r($_COOKIE["username"]);
$unameCookie = $_COOKIE["username"];
$primaryColor = $_COOKIE["settings_array"]->{$unameCookie}->primaryColor;
$secondaryColor = $_COOKIE["settings_array"]->{$unameCookie}->secondryColor;
print_r($_COOKIE["settings_array"]->{$unameCookie});
echo "\r\n.navb-fixid {\r\n\tposition: fixed;\r\n\tbackground-color: ";
echo $primaryColor;
echo ";\r\n}\r\n\r\n.fav .add-fav\r\n{\r\n\tbackground: ";
echo $secondaryColor;
echo " url(../img/fav.png) 0px 0px no-repeat;\r\n}\r\n    \r\n.ts-content .column.seasons ul li.active, .ts-content .column.episodes ul li.active {\r\n\tborder-left: solid 3px ";
echo $secondaryColor;
echo ";\r\n}\r\n\r\n.channel-list ul li:hover{\r\n    background: ";
echo $secondaryColor;
echo ";\r\n}\r\n\r\n.playlist ul li a:hover{\r\n\tcolor: ";
echo $secondaryColor;
echo ";\r\n}\r\n\r\n.btn-ghost:hover{\r\n    background: ";
echo $secondaryColor;
echo ";\r\n    border-color: transparent;\r\n}\r\n\r\n.nav.navbar-nav.navbar-left.main-icon li:hover {\r\n    background-color:  ";
echo $secondaryColor;
echo ";\r\n    transition: 0.3s;\r\n    \r\n}\r\n\r\n.channel-list ul li.playingChanel {\r\n    background: ";
echo $secondaryColor;
echo ";\r\n}\r\n\r\n.r-li:hover  {\r\n    transition: 0.3s;\r\n    /*border-bottom: solid 2px ";
echo $secondaryColor;
echo " !important*/;\r\n}\r\n\r\n.main-icon li.active a {\r\n\tborder-bottom: solid 2px ";
echo $secondaryColor;
echo ";\r\n}\r\n\r\n.cat-toggle {\r\n\tbackground: ";
echo $secondaryColor;
echo ";\r\n}";

?>