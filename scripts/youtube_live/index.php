<?php
ob_start();
error_reporting(0);
set_time_limit(0);
date_default_timezone_set( "Europe/London" );

// vars
$chid = get( 'id' );
$quality = get( 'quality' );
if( empty( $quality ) ) {
    $quality = 95;
} elseif( $quality == 'fhd') {
    $quality = 96;
} elseif( $quality == 'hd') {
    $quality = 95;
} elseif( $quality == 'sd') {
    $quality = 94;
}

// quality settings
// 96 = 1920x1080
// 95 = 1280x720
// 94 = 854x480
// 94 = 854x480
// 93 = 640x360
$regex = '/(https:\/.*\/'.$quality.'\/.*index.m3u8)/U';

// functions
function get_data( $url ) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36" );
    curl_setopt( $ch, CURLOPT_REFERER, "https://www.youtube.com/" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    $data = curl_exec( $ch );
    curl_close( $ch );
    return $data;
}

function get( $key = null ) {
    if ( is_null($key) ) {
        return $_GET;
    }
    $get = isset($_GET[$key]) ? $_GET[$key] : null;
    if ( is_string($get) ) {
        $get = trim($get);
    }
    return $get;
}

if( empty( $chid ) ) {
    die( 'id is missing' );
} else {
    if( empty( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS'] === 'off' ) {
        $protocol = 'http://';
    } else {
        $protocol = 'https://';
    }

    header( 'Access-Control-Allow-Origin: *' );
    header( 'Content-type: application/json' );

    $string = get_data( 'https://www.youtube.com/watch?v='.$chid );

    $manifest_url = '/,\\\\"hlsManifestUrl\\\\":\\\\"(.*?)\\\\"/m';
    preg_match_all( $manifest_url, $string, $matches, PREG_PATTERN_ORDER, 0 );

    $var1 = $matches[1][0];
    $var1 = str_replace( "\/", "/", $var1 );

    $man = get_data( $var1);

    preg_match_all( $regex, $man, $matches, PREG_PATTERN_ORDER );
    $stream_link = $matches[1][0];
    header( "Content-type: application/vnd.apple.mpegurl" );
    header( "Location: $stream_link" );
}

ob_end_flush();
?>