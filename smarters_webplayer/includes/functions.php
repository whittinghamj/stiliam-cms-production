<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 */

$FileNameExtension = basename(strtok($_SERVER["REQUEST_URI"], "?"));
$fileName = explode("/", $_SERVER["SCRIPT_FILENAME"]);
$activePage = str_replace(".php", "", end($fileName));
$streamData = "";
$configFileCheck = webtvpanel_checkfilepermission("configuration.php");
function webtvpanel_date_sort($a, $b)
{
    if (strtotime($time1) < strtotime($time2)) {
        return 1;
    }
    if (strtotime($time2) < strtotime($time1)) {
        return -1;
    }
    return 0;
}
function webtvpanel_CallApiRequest($ApiLinkIs = "")
{
    $returnData = "0";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ApiLinkIs);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if (curl_exec($ch) === false) {
        return array("result" => "error", "data" => "Invalid Host Url");
    }
    $Result = json_decode(curl_exec($ch));
    if (!empty($Result)) {
        $returnData = $Result;
        return array("result" => "success", "data" => $returnData);
    }
    return array("result" => "error");
}
function webtvpanel_checkFilePermission($fileName = "")
{
    if (file_exists($fileName)) {
        $Permission = substr(sprintf("%o", fileperms($fileName)), -4);
        if ($Permission == "0644" || $Permission == "0755" || $Permission == "0777") {
            return array("result" => "success", "permission" => $Permission);
        }
        return array("result" => "error");
    }
}
function webtvpanel_CheckstreamLine($username = "", $password = "", $hostURL = "")
{
    $returnData = "0";
    $bar = "/";
    if (substr($hostURL, -1) == "/") {
        $bar = "";
    }
    $Servername = $hostURL . $bar;
    $ApiLinkIs = $Servername . "player_api.php?username=" . $username . "&password=" . $password;
    $CallApi = webtvpanel_callapirequest($ApiLinkIs);
    if (!empty($CallApi) && $CallApi["result"] == "success") {
        if (isset($CallApi["data"]->user_info->auth) && $CallApi["data"]->user_info->auth != 0 && $CallApi["data"]->user_info->status == "Active") {
            $returnData = "1";
        }
    } else {
        $returnData = "0";
    }
    return $returnData;
}
function webtvpanel_CheckLicense($licensekey, $localkey = "")
{
    $results["status"] = "Active";
    return $results;
}
function webtvpanel_getLoggedInCategories()
{
    $username = $_SESSION["webTvplayer"]["username"];
    $password = $_SESSION["webTvplayer"]["password"];
    $hostURL = $_SESSION["webTvplayer"]["username"];
}
function getLiveVideoLink($streamID = "", $streamType = "")
{
}
function webtvpanel_starRating($rating = "")
{
    if (is_float($rating)) {
        $floatVal = explode(".", $rating);
        $j = 0;
        for ($i = 0; $i < $floatVal[0]; $i++) {
            $j++;
            echo "<span class=\"fa fa-star\"></span>";
        }
        if (5 <= $floatVal[1] || $floatVal[1] <= 5) {
            $j++;
            echo "<span class=\"fa fa-star-half\"></span>";
        }
        for ($remainigStar = 5 - intval($j); $j < 5; $j++) {
            echo "<span class=\"fa fa-star-o\"></span>";
        }
    } else {
        $j = 0;
        for ($i = 0; $i < $rating; $i++) {
            $j++;
            echo "<span class=\"fa fa-star\"></span>";
        }
        for ($remainigStar = 5 - intval($j); $j < 5; $j++) {
            echo "<span class=\"fa fa-star-o\"></span>";
        }
    }
}
function webtvpanel_checkPlayer()
{
    if (isset($_COOKIE["settings_array"]) && !empty($_COOKIE["settings_array"])) {
        $sessionArray = json_decode($_COOKIE["settings_array"]);
        return $sessionArray;
    }
}
function webtvpanel_baseEncode($Text = "")
{
    $returnData = "";
    if ($Text != "") {
        $returnData = base64_encode($Text);
    }
    return $returnData;
}
function webtvpanel_baseDecode($Text = "")
{
    $returnData = "";
    if ($Text != "") {
        $returnData = base64_decode($Text);
    }
    return $returnData;
}
function webtvpanel_parentcondition($Text = "")
{
    $returnData = 0;
    $parentenable = "";
    $parentpassword = "";
    if (isset($_COOKIE["settings_array"])) {
        $SessionStroedUsername = $_SESSION["webTvplayer"]["username"];
        $SettingArray = json_decode($_COOKIE["settings_array"]);
        if (isset($SettingArray->{$SessionStroedUsername}) && !empty($SettingArray->{$SessionStroedUsername})) {
            $parentenable = $SettingArray->{$SessionStroedUsername}->parentenable;
            $parentpassword = $SettingArray->{$SessionStroedUsername}->parentpassword;
        }
    }
    if ($parentenable == "on" && (webtvpanel_like_match("%adults%", $Text) == 1 || webtvpanel_like_match("%adult%", $Text) == 1 || webtvpanel_like_match("%Adults%", $Text) == 1 || webtvpanel_like_match("%XXX%", $Text) == 1 || webtvpanel_like_match("%Porn%", $Text) == 1 || webtvpanel_like_match("%xxx%", $Text) == 1 || webtvpanel_like_match("%Sexy%", $Text) == 1 || webtvpanel_like_match("%foradults%", $Text) == 1 || webtvpanel_like_match("%ADULTE%", $Text) == 1 || webtvpanel_like_match("%adulte%", $Text) == 1)) {
        $returnData = 1;
    }
    return $returnData;
}
function webtvpanel_like_match($pattern, $subject)
{
    $pattern = str_replace("%", ".*", preg_quote($pattern, "/"));
    return (bool) preg_match("/^" . $pattern . "\$/i", $subject);
}

?>