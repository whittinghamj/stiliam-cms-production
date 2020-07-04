<?php

die();

// functions
function m3u8( $url ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_ENCODING, '' );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
		'Accept-Encoding: gzip, deflate',
		'Accept-Language: es-ES,es;q=0.9,fr;q=0.8',
		'Cache-Control: max-age=0',
		'Connection: keep-alive',
		'Host: hochu.tv',
		'Referer: http://hochu.tv/',
		'Upgrade-Insecure-Requests: 1',
		'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36'
	));
	$server_output = urldecode( curl_exec( $ch ) );
	curl_close ( $ch );
	return $server_output;
}

function m3u82( $url2, $ideee ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url2 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_ENCODING, '' );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
		'Accept-Encoding: gzip, deflate',
		'Accept-Language: es-ES,es;q=0.9,fr;q=0.8',
		'Connection: keep-alive',
		'Host: cdntvnet.com',
		'Referer: http://hochu.tv/'.$ideee.'.html',
		'Upgrade-Insecure-Requests: 1',
		'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36'
	));
	$server_output = urldecode( curl_exec( $ch ) );
	curl_close ( $ch );
	return $server_output;
}

function search( $string, $start, $end ) {
	$string = " ".$string;
	$ini = strpos( $string,$start );
	if ( $ini == 0) return "";
	$ini += strlen( $start );   
	$len = strpos( $string, $end, $ini ) - $ini;
	return substr( $string, $ini, $len );
}

$data['id'] 			= htmlspecialchars( $_GET['id'] );
$data['url'] 			= m3u8( 'http://hochu.tv/'.$data['id'].'.html' );
$data['php'] 			= search( $data['url'],'cdntvnet.com/', '.php' );
$data['stream'] 		= m3u82( 'http://cdntvnet.com/'.$data['php'].'.php', $data['id']);
$data['streamurl'] 		= search( $data['stream'], 'file:"', '"});' );

error_log( "\n\n".$data['streamurl']."\n" );

// echo '<pre>';
// print_r( $data['streamurl'] );

header( "Location: ".$data['streamurl'], true, 301 );