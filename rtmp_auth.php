<?php
// env
set_time_limit(0);

// security check
if( $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) {
    die;
}

// invludes
include('inc/db.php');
include('inc/global_vars.php');
include('inc/functions.php');

// vars
$stream_key 		= post( 'name' );
$client_ip 			= $_REQUEST['addr'];

// new incoming stream, run ip checks
if( request( 'call' ) == 'publish') {
	$query              = $conn->query( "SELECT * FROM `rtmp_allowed_ips` WHERE `ip_address` = '".$client_ip."' " );
	$match 				= $query->fetch( PDO::FETCH_ASSOC );

	// handle results
	if( isset( $match['id'] ) ) {
		http_response_code(200); # return 201 "allowed"
	} else {
		http_response_code(404); # return 404 "not allowed"
	}
	die();
}

// wrap up a stream that is just finishing
if( request( 'call' ) == 'play_done') {
	http_response_code(200);
    die;
}

// default - deny 
http_response_code(404);
?>
