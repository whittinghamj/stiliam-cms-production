<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 */

session_start();
if (file_exists("functions.php")) {
    include_once "functions.php";
    $GlobalTimeFormat = "12";
    if (isset($_COOKIE["settings_array"])) {
        $SettingArray = json_decode($_COOKIE["settings_array"]);
        $SessioStoredUsername = $_SESSION["webTvplayer"]["username"];
        if (isset($SettingArray->{$SessioStoredUsername}) && !empty($SettingArray->{$SessioStoredUsername})) {
            $GlobalTimeFormat = $SettingArray->{$SessioStoredUsername}->timeformat;
        }
    }
    $Formatis = "h:i A";
    if ($GlobalTimeFormat == "24") {
        $Formatis = "H:i";
    }
    if (isset($_POST["action"]) && $_POST["action"] != "") {
        $CurrentTime = $_POST["CurrentTime"];
        $StreamId = $_POST["StreamId"];
        $username = $_SESSION["webTvplayer"]["username"];
        $password = $_SESSION["webTvplayer"]["password"];
        $hostURL = $_POST["hostURL"];
        $ApiLink = $hostURL . "player_api.php?username=" . $username . "&password=" . $password . "&action=get_simple_data_table&stream_id=" . $StreamId;
        $RequestForEpg = webtvpanel_CallApiRequest($ApiLink);
        if (!empty($RequestForEpg) && $RequestForEpg["result"] == "success") {
            $CurrentDate = date("Y:m:d", $CurrentTime);
            if (!empty($RequestForEpg["data"]->epg_listings)) {
                $OnlyDates = array();
                foreach ($RequestForEpg["data"]->epg_listings as $ResVal) {
                    $OnlyDateVar = date("Y:m:d", strtotime($ResVal->start));
                    $ValDate = date("d/m/Y", strtotime($ResVal->start));
                    if ($CurrentDate <= $OnlyDateVar) {
                        $OnlyDates[$OnlyDateVar] = $ValDate;
                    }
                }
                if (!empty($OnlyDates)) {
                    echo "\t    \t<div class=\"panel-heading\">\t\r\n\t    \t\t<ul class=\"nav nav-tabs\">\r\n\t\t    \t";
                    $TotalDates = count($OnlyDates);
                    $Counter = 1;
                    foreach ($OnlyDates as $OnlyDate => $Val) {
                        if ($Counter <= 4) {
                            echo "  \r\n\t                    <li class=\"";
                            echo $Counter == 1 ? "active" : "";
                            echo "\">\r\n\t                    \t<a href=\"#TabNo";
                            echo $Counter;
                            echo "\" data-toggle=\"tab\">\r\n\t                    \t\t";
                            echo $Val;
                            echo "                    \t\t\t\r\n\t                    \t</a>\r\n\t                    </li>\r\n\t\t    \t\t";
                        }
                        $Counter++;
                    }
                    if (4 < $TotalDates) {
                        echo "\t\t    \t\t<li class=\"dropdown\">\r\n\t                    <a href=\"#\" data-toggle=\"dropdown\">More <span class=\"caret\"></span></a>\r\n\t                    <ul class=\"dropdown-menu\" role=\"menu\">\r\n\t                        ";
                        $Counter1 = 1;
                        foreach ($OnlyDates as $OnlyDate => $Val) {
                            if (4 < $Counter1) {
                                echo "\t                \t\t\t\t\t<li><a href=\"#TabNo";
                                echo $Counter1;
                                echo "\" data-toggle=\"tab\">";
                                echo $Val;
                                echo "</a></li>\t\r\n\t                \t\t\t\t\t";
                            }
                            $Counter1++;
                        }
                        echo "\t                    </ul>\r\n\t                </li>\t\r\n\t\t    \t\t";
                    }
                    echo "\t    \t\t</ul>\t\r\n\t    \t</div>\r\n\t    \t<div class=\"panel-body\">\r\n\t            <div class=\"tab-content\">\r\n\t            \t\t";
                    $TabCounter = 1;
                    foreach ($OnlyDates as $OnlyDate => $Val) {
                        echo "\t                    \t\t<div class=\"tab-pane fade customTab ";
                        echo $TabCounter == 1 ? "in active" : "";
                        echo "\" id=\"TabNo";
                        echo $TabCounter;
                        echo "\" >\r\n\t                    \t\t\t";
                        foreach ($RequestForEpg["data"]->epg_listings as $ResVal) {
                            $OnlyDateVal = date("Y:m:d", strtotime($ResVal->start));
                            if ($OnlyDateVal == $OnlyDate) {
                                $ACtiveClass = "";
                                $NowPLaying = "";
                                $StartTimming = strtotime($ResVal->start);
                                $EndTimming = strtotime($ResVal->end);
                                if ($StartTimming <= $CurrentTime && $CurrentTime <= $EndTimming) {
                                    $ACtiveClass = "NowPlayingActive";
                                    $NowPLaying = "(Now Playing)";
                                }
                                echo "\t\t    \t\t\t\t\t\t\t\t\t<div class=\"epginfo ";
                                echo $ACtiveClass;
                                echo "\">\r\n\t\t    \t\t\t\t\t\t\t\t\t\t";
                                echo date($Formatis, $StartTimming);
                                echo "\t\t    \t\t\t\t\t\t\t\t\t\t-\r\n\t\t    \t\t\t\t\t\t\t\t\t\t";
                                echo date($Formatis, $EndTimming);
                                echo "\t\t    \t\t\t\t\t\t\t\t\t\t&nbsp; \r\n\t\t    \t\t\t\t\t\t\t\t\t\t";
                                echo base64_decode($ResVal->title);
                                echo " \r\n\t\t    \t\t\t\t\t\t\t\t\t\t&nbsp;\r\n\t\t    \t\t\t\t\t\t\t\t\t\t";
                                echo $NowPLaying;
                                echo "\t\r\n\t\t    \t\t\t\t\t\t\t\t\t</div>\t\r\n\t\t    \t\t\t\t\t\t\t\t\t";
                            }
                        }
                        echo "\t                    \t\t</div>\r\n\t                    \t";
                        $TabCounter++;
                    }
                    echo "\t\r\n\t            </div>\r\n\t        </div>\t\r\n\t    \t";
                    exit;
                } else {
                    echo "";
                    exit;
                }
            } else {
                echo "";
                exit;
            }
        } else {
            echo "";
            exit;
        }
    }
} else {
    echo "Please verify that functions.php file exists";
    exit;
}

?>