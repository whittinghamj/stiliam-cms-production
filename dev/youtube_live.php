<?PHP
$searchquery = $_GET["searchquery"];

ini_set("user_agent","facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)");

function get_data( $url ) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, "facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)" );
    curl_setopt( $ch, CURLOPT_REFERER, "http://facebook.com" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    $data = curl_exec( $ch );
    curl_close( $ch );
    return $data;
}

$string = get_data( 'https://www.youtube.com/results?search_query=' . $searchquery );
preg_match_all( '/(data-context-item-id.*data*)/',$string,$matches, PREG_PATTERN_ORDER );
$var1=$matches[1][0];
$var1 = substr( $var1, 22,20 );
$var1 = strtok( $var1, '"' );
$var2 = get_data( "https://www.youtube.com/watch?v=" . $var1 );

preg_match_all( '/(hlsManifestUrl.*m3u8)/',$var2,$matches, PREG_PATTERN_ORDER );

$var1 = $matches[1][0];

$var1 = substr( $var1, 19 );

$var1 = str_replace( "\/", "/", $var1 );
#Quality Settings
/* 96=1920x1080, 95=1280x720, 94=854x480, 93=640x360 */
$man = get_data( $var1 );
preg_match_all( '/(https:\/.*\/94\/.*index.m3u8)/U',$man,$matches, PREG_PATTERN_ORDER );
$var2 = $matches[1][0];
header( "Content-type: application/vnd.apple.mpegurl" );
header( "Location: $var2" );
// echo $var2;
?>