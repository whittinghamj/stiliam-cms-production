<?php
function endsWith($haystack, $needle){
    $length = strlen($needle);
    if($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

if(isset($_POST["iptv"]) && !empty($_POST["iptv"])) {
    $url = $_POST["iptv"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $m3ufile = curl_exec($ch);
    curl_close($ch);
} elseif (is_uploaded_file($_FILES['iptvfile']['tmp_name'])){
    $m3ufile = file_get_contents($_FILES['iptvfile']['tmp_name']);
} else {
    header("Location: index.php");
    exit;
}

$m3ufile = str_replace('group-title', 'tvgroup', $m3ufile);
$m3ufile = str_replace('.ts', '.m3u8', $m3ufile);
$m3ufile = str_replace("tvg-", "tv", $m3ufile);
$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$))/';
$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';
preg_match_all($re, $m3ufile, $matches);
$i = 1;
$items = array();
foreach($matches[0] as $list){
    preg_match($re, $list, $matchList);
    $mediaURL = preg_replace("/[\n\r]/","",$matchList[3]);
    $mediaURL = preg_replace('/\s+/', '', $mediaURL);
    if(!endsWith($mediaURL, '.m3u8')){
        $mediaURL = $mediaURL.'.m3u8';
        $mediaURL = explode("/",$mediaURL);
        $mediaURL[2] = $mediaURL[2].'/live';
        $mediaURL = implode("/", $mediaURL);
    }
    $newdata =  array (
        'id' => $i++,
        'tvtitle' => $matchList[2],
        'tvmedia' => $mediaURL
    );
    preg_match_all($attributes, $list, $matches, PREG_SET_ORDER);
    foreach ($matches as $match){
        $newdata[$match[1]] = $match[2];
    }
    $items[] = $newdata;
}
?>