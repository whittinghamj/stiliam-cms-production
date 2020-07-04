<?php

// default array
$data 					= array();

// vars
$app['basepath']		= '/var/www/html/';
$config 				= file_get_contents( '/var/www/html/config.json' );
$config 				= json_decode( $config, true );

// CORS
header("Access-Control-Allow-Origin: *");

// set vars
$client_ip 						= $_SERVER['REMOTE_ADDR'];
$data['username'] 				= stripslashes( $_GET['username'] );
if( !isset( $_GET['username']) || empty( $data['username'] ) ) {
	die( 'username is missing' );
}
$data['password'] 				= stripslashes( $_GET['password'] );
if( !isset( $_GET['password'] ) || empty( $data['password'] ) ) {
	die( 'password is missing' );
}
$data['channel_id'] 			= stripslashes( $_GET['channel_id'] );
if( !isset( $_GET['channel_id'] ) || empty($data['channel_id'] ) ) {
	die( 'channel_id missing' );
}
$data['extension'] 				= stripslashes( $_GET['extension'] );
if( !isset( $_GET['extension'] ) || empty( $data['extension'] ) ) {
	die( 'extension missing' );
}

// error_log(' ');
// error_log('extension: '.$data['extension']);
if( $data['extension'] == 'm3u' || $data['extension'] == 'm3u8' || empty( $data['extension'] ) ) {
	// error_log('stream_id: '.$data['stream_id']);
} elseif( $data['extension'] == 'ts' ) {
	// error_log('segment_id: '.$data['segment_id']);
}
// error_log('username: '.$data['username']);
// error_log('password: '.$data['password']);
// error_log(' ');

if( $data['extension'] == 'ts' ) {
	// break the ts file for channel_id
	$channel_bits 					= explode( "_", $data['channel_id'] );
	$data['channel_id']				= $channel_bits[2];
	$data['segment_id']				= $channel_bits[3];
}

// error_log( "\n\n API URL: http://".$config['cms']['server']."/api/?c=channel_247_info_client&username=".$data['username']."&password=".$data['password']."&client_ip=".$client_ip."&channel_id=".$data['channel_id']."\n\n" );

$channel_data 					= @file_get_contents( "http://".$config['cms']['server']."/api/?c=channel_247_info_client&username=".$data['username']."&password=".$data['password']."&client_ip=".$client_ip."&channel_id=".$data['channel_id'] );
$channel_data 					= json_decode( $channel_data, true );

// sanity check
if( $channel_data['status'] == 'error' ) {
	echo "cms error: ".$channel_data['message'];
	die();
}

// error_log( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$channel_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel_247&type_id=".$data['channel_id']."&username=".$data['username'] );

// post data to api for active connections log and check if connection is allowed
@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$channel_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel_247&type_id=".$data['channel_id']."&username=".$data['username'] );

$public_ip 						= @file_get_contents( "http://".$config['cms']['server']."/api/ip.php" );
$public_ip 						= json_decode( $public_ip, true );
$public_ip 						= $public_ip['ip'];

if( $public_ip != $channel_data['data']['server']['wan_ip_address'] ){
	header( "Location: http://".$channel_data['data']['server']['wan_ip_address'].":".$channel_data['data']['server']['http_stream_port']."/channel/".$data['username']."/".$data['password']."/".$data['channel_id'].".m3u8", true, 301 );
}else{
	// process m3u8 request
	if( $data['extension'] == 'm3u' || $data['extension'] == 'm3u8' || empty( $data['extension'] ) ) {
		// contact the hub for username / pass check and stream data
		if( $channel_data['status'] == 'error' ) {
			echo "cms error: ".$channel_data['message'];
			die();
		}

		$file = '/var/www/html/play/hls/channel_247_'.$data['channel_id'].'_.m3u8';
		$fp = fopen( $file, 'rb' );

		header( "Content-Type: application/octet-stream" );
		header( "Content-Disposition: attachment; filename=".$data['channel_id'].'.m3u8' );
		header( "Content-Length: " . filesize( $file ) );
		fpassthru( $fp );
	} elseif( $data['extension'] == 'ts' ) {
		// post data to api for active connections log
		// @file_get_contents("http://hub.slipstreamiptv.com/api/?c=stream_connection_log&server_id=".$server_id."&client_ip=".$client_ip."&stream_id=".$stream_id);

		$file = '/var/www/html/play/hls/channel_247_'.$data['channel_id'].'_'.$data['segment_id'].'.ts';
		$fp = fopen( $file, 'rb' );

		if( isset( $_GET['dev'] ) && $_GET['dev'] == 'yes' ) {
			echo '<pre>';
			print_r( $_GET );
			print_r( $data );
			print_r( $stream_data );
		}else{
			header( "Content-Type: application/octet-stream" );
			header( "Content-Disposition: attachment; filename=channel_247_".$data['channel_id'].'_'.$data['segment_id'].'.ts' );
			header( "Content-Length: " . filesize( $file ) );
			fpassthru( $fp );
		
			// if($channel_data['data']['fingerprint'] == 'enable') {
			// 	shell_exec("rm -rf /var/www/html/play/hls/channel_".$data['channel_id'].'_'.$data['segment_id'].'.ts');
			// }
		}
	}
}


// post data to api for active connections log
// @file_get_contents("http://hub.slipstreamiptv.com/api/?c=stream_connection_log&server_id=".$server_id."&client_ip=".$client_ip."&stream_id=".$stream_id);

exit;