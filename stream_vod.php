<?php

// default array
$data 					= array();

// vars
$app['basepath']		= '/var/www/html/';
$config 				= file_get_contents( '/var/www/html/config.json' );
$config 				= json_decode( $config, true );

$anti_flood 			= 0;

// set vars
$client_ip 						= $_SERVER['REMOTE_ADDR'];
$data['username'] 				= stripslashes( $_GET['username'] );
if(!isset($_GET['username']) || empty($data['username'])) {
	echo '<pre>';
	print_r($_GET);
	die('username is missing');
}
$data['password'] 				= stripslashes( $_GET['password'] );
if(!isset($_GET['password']) || empty($data['password'])) {
	echo '<pre>';
	print_r($_GET);
	die('password is missing');
}
$data['vod_id'] 				= stripslashes( $_GET['vod_id'] );
if(!isset($_GET['vod_id']) || empty($data['vod_id'])) {
	echo '<pre>';
	print_r($_GET);
	die('vod_id missing');
}

// contact the hub for username / pass check and series data
// error_log(" ");
// error_log("http://".$config['cms']['server']."/api/?c=vod_info_client&username=".$data['username']."&password=".$data['password']."&vod_id=".$data['vod_id'] );
// error_log(" ");	

$vod_data 						= @file_get_contents("http://".$config['cms']['server']."/api/?c=vod_info_client&username=".$data['username']."&password=".$data['password']."&client_ip=".$client_ip."&vod_id=".$data['vod_id'] );
$vod_data 						= json_decode($vod_data, true);

if($vod_data['status'] == 'error'){
	echo "cms error: ".$vod_data['message'];
	// echo "<pre>";
	// print_r($_GET);
	// print_r($vod_data);
	// echo "<pre>";
	die();
}

$public_ip 						= @file_get_contents("http://".$config['cms']['server']."/api/ip.php");
$public_ip 						= json_decode($public_ip, true);
$public_ip 						= $public_ip['ip'];

if($public_ip != $vod_data['data']['server']['wan_ip_address']){
	header("Location: http://".$vod_data['data']['server']['wan_ip_address'].":".$vod_data['data']['server']['http_stream_port']."/movie/".$data['username']."/".$data['password']."/".$data['vod_id'], true, 301);
}else{
	if(!file_exists($vod_data['data']['file_location'])) {
		header('HTTP/1.0 404 Not Found');
		echo '<pre>';
		print_r($vod_data);
		die("local file not found");
	}

	// Clears the cache and prevent unwanted output
	ob_clean();
	@ini_set('error_reporting', E_ALL & ~ E_NOTICE);
	@ini_set('zlib.output_compression', 'Off');

	$file = $vod_data['data']['file_location']; // The media file's location
	$mime = "application/octet-stream"; // The MIME type of the file, this should be replaced with your own.
	$size = filesize($file); // The size of the file

	// Send the content type header
	header('Content-type: ' . $mime);

	// Check if it's a HTTP range request
	if(isset($_SERVER['HTTP_RANGE'])){
	    // Parse the range header to get the byte offset
	    $ranges = array_map(
	        'intval', // Parse the parts into integer
	        explode(
	            '-', // The range separator
	            substr($_SERVER['HTTP_RANGE'], 6) // Skip the `bytes=` part of the header
	        )
	    );

	    // If the last range param is empty, it means the EOF (End of File)
	    if(!$ranges[1]){
	        $ranges[1] = $size - 1;
	    }

	    // Send the appropriate headers
	    header('HTTP/1.1 206 Partial Content');
	    header('Accept-Ranges: bytes');
	    header('Content-Length: ' . ($ranges[1] - $ranges[0])); // The size of the range

	    // Send the ranges we offered
	    header(
	        sprintf(
	            'Content-Range: bytes %d-%d/%d', // The header format
	            $ranges[0], // The start range
	            $ranges[1], // The end range
	            $size // Total size of the file
	        )
	    );

	    // It's time to output the file
	    $f = fopen($file, 'rb'); // Open the file in binary mode
	    $chunkSize = 8192; // The size of each chunk to output

	    // Seek to the requested start range
	    fseek($f, $ranges[0] );

	    // Start outputting the data
	    while(true){
	        // Check if we have outputted all the data requested
	        if(ftell($f) >= $ranges[1]){
	            break;
	        }

	        // flood protection
	        if ($anti_flood % 1000 != 0){

	        }else{
	        	// post data for connection log
	        	// error_log( "\n\nlogging vod - range\n\n" );
	        	// error_log( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$vod_data['data']['server']['id']."&client_ip=".$client_ip."&type=vod&type_id=".$data['vod_id']."&username=".$data['username'] );
	        	@file_get_contents("http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$vod_data['data']['server']['id']."&client_ip=".$client_ip."&type=vod&type_id=".$data['vod_id']."&username=".$data['username'] );
	        }

	        // Output the data
	        echo fread($f, $chunkSize);

	        $anti_flood++;

	        // Flush the buffer immediately
	        @ob_flush();
	        flush();
	    }
	}else{
		// post data for connection log
    	// error_log( "\n\nlogging vod - out of range\n\n" );
    	// error_log( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$vod_data['data']['server']['id']."&client_ip=".$client_ip."&type=vod&type_id=".$data['vod_id']."&username=".$data['username'] );
    	@file_get_contents("http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$vod_data['data']['server']['id']."&client_ip=".$client_ip."&type=vod&type_id=".$data['vod_id']."&username=".$data['username'] );

	    // It's not a range request, output the file anyway
	    header('Content-Length: ' . $size);

	    // Read the file
	    @readfile($file);

	    // and flush the buffer
	    @ob_flush();
	    flush();
	}
}

exit;