<?php
// session_start();

error_reporting(E_ALL);
ini_set( 'display_errors', 1);
ini_set( 'error_reporting', E_ALL);

// $data['version']			= '1.0.0';

include( '/var/www/html/inc/db.php' );
include( '/var/www/html/inc/global_vars.php' );
include( '/var/www/html/inc/functions.php' );

// geoip
require( '/var/www/html/MaxMind-DB-Reader-php/autoload.php' );
use MaxMind\Db\Reader;
$geoip = new Reader( '/var/www/html/GeoLite2-City.mmdb' );
$geoisp = new Reader( '/var/www/html/GeoIP2-ISP.mmdb' );

header( "Content-Type:application/json; charset=utf-8" );

$c = get( 'c' );
switch ($c) {

	// get_servers
	case "get_servers":
		get_servers();
		break;

	// customer connection log
	case "customer_connection_log":
		customer_connection_log();
		break;

	// channel connection log
	case "channel_connection_log":
		channel_connection_log();
		break;

	// channel_247 connection log
	case "channel_247_connection_log":
		channel_247_connection_log();
		break;

	// vod_tv connection log
	case "vod_tv_connection_log":
		vod_tv_connection_log();
		break;

	// vod connection log
	case "vod_connection_log":
		vod_connection_log();
		break;
		
	// stream progress
	case "channel_progress":
		channel_progress();
		break;

	// force_status_change
	case "force_status_change":
		force_status_change();
		break;

	// installer_progress
	case "installer_progress":
		installer_progress();
		break;

	// checkin
	case "checkin":
		checkin();
		break;

	// firewall_rules
	case "firewall_rules":
		firewall_rules();
		break;

	// server
	case "server":
		server();
		break;

	// stream_out_server_info
	case "stream_out_server_info":
		stream_out_server_info();
		break;

	// server_stats_api
	case "server_stats_api":
		server_stats_api();
		break;

	// jobs
	case "jobs":
		jobs();
		break;

	case "job_complete":
		job_complete();
		break;

	case "channel_status_update":
		channel_status_update();
		break;

	case "channel_247_status_update":
		channel_247_status_update();
		break;

	// stream
	case "stream":
		stream();
		break;

	// stream_info
	case "stream_info":
		stream_info();
		break;

	// stream_info_fingerprint
	case "stream_info_fingerprint":
		stream_info_fingerprint();
		break;

	// stream_info_live
	case "stream_info_live":
		stream_info_live();
		break;

	// channel_info_client
	case "channel_info_client":
		channel_info_client();
		break;

	// stream_info_server
	case "stream_info_server":
		stream_info_server();
		break;

	// vod_tv_info_client
	case "vod_tv_info_client":
		vod_tv_info_client();
		break;

	// vod_info_client
	case "vod_info_client":
		vod_info_client();
		break;

	// vod_info_server
	case "vod_info_server":
		vod_info_server();
		break;

	// channel_247_info_client
	case "channel_247_info_client":
		channel_247_info_client();
		break;

	// channel_info_fingerprint
	case "channel_info_fingerprint":
		channel_info_fingerprint();
		break;

	// get transcoding_profile
	case "transcoding_profile":
		transcoding_profile();
		break;

	// mag_device_api
	case "mag_device_api":
		mag_device_api();
		break;

	// vod_add
	case "vod_add":
		vod_add();
		break;

	// vod_tv_add
	case "vod_tv_add":
		vod_tv_add();
		break;

	// vod_tv_episode_add
	case "vod_tv_episode_add":
		vod_tv_episode_add();
		break;

	// channels_247_add
	case "channels_247_add":
		channels_247_add();
		break;

	// channels_247_episode_add
	case "channels_247_episode_add":
		channels_247_episode_add();
		break;

	// channel_info
	case "channel_info":
		channel_info();
		break;

	// home
	default:
		home();
		break;
}
       
function home()
{
	global $site;
	$data['status']				= 'success';
	$data['message']			= 'you have successfully connected to the API. now try a few other commands to pull / push additional data.';
	json_output( $data );
}

function get_servers()
{
	global $conn, $global_settings, $geoip, $geoisp;

	// vars
	$server_uuid 	= get( 'server_uuid' );

	// sanity checks
	if( empty( $server_uuid ) || !isset( $server_uuid ) ) {
		die( 'missing server_uuid' );
	}

	// check server_uuid as basic security check
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );

	if( !isset( $server['id'] ) ) {
		die( 'access denied' );
	}

	// get all servers
	$query = $conn->query( "SELECT `id`,`name`,`wan_ip_address`,`http_stream_port` FROM `servers` " );
	$data = $query->fetchAll( PDO::FETCH_ASSOC );
	
	json_output( $data );
}

function customer_connection_log()
{
	global $conn, $global_settings, $geoip, $geoisp;

	// vars
	$time_shift 		= time() - 30;
	$server_id 			= get( 'server_id' );
	$client_ip 			= get( 'client_ip' );
	$type 				= get( 'type' );
	$type_id 			= get( 'type_id' );
	$username 			= get( 'username' );

	// sanity checks
	if( empty( $server_id ) || !isset( $server_id ) ) {
		die( 'missing server_id' );
	}
	if( empty( $client_ip ) || !isset( $client_ip ) ) {
		die( 'missing client_ip' );
	}
	if( empty( $type ) || !isset( $type ) ) {
		die( 'missing type' );
	}
	if( empty( $type_id ) || !isset( $type_id ) ) {
		die( 'missing type_id' );
	}
	if( empty( $username ) || !isset( $username ) ) {
		die( 'missing username' );
	}

	// get customer details
	$query = $conn->query( "SELECT `id` FROM `customers` WHERE `username` = '".$username."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	// check if record already exists and update if found or create new record if no match found
	$query = $conn->query( "SELECT `id` FROM `customers_connection_logs` WHERE `updated` > '".$time_shift."' AND `type` = '".$type."' AND `type_id` = '".$type_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' " );
	$result = $query->fetch( PDO::FETCH_ASSOC );
	if(isset($result['id'] ) ) {
		// update existing record
		$update = $conn->exec( "UPDATE `customers_connection_logs` SET `updated` = '".time()."' WHERE `id` = '".$result['id']."' " );
	}else{
		// add new record
		$insert = $conn->exec( "INSERT INTO `customers_connection_logs` 
	        (`created`,`updated`,`server_id`,`type`,`type_id`,`client_ip`,`customer_id`)
	        VALUE
	        ( '".time()."',
	        '".time()."',
	        '".$server_id."',
	        '".$type."',
	        '".$type_id."',
	        '".$client_ip."',
	        '".$customer['id']."'
	    )" );
	}
	
	$data = array();
	json_output( $data );
}

function channel_connection_log()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_id 		= get( 'server_id' );
	$client_ip 		= get( 'client_ip' );
	$stream_id 		= get( 'stream_id' );
	$username 		= get( 'username' );
	// $stream_name 	= get( 'stream_name' );

	if( empty( $server_id ) ) {
		$query = $conn->query( "SELECT `server_id` FROM `streams` WHERE `id` = '".$stream_id."' " );
		$stream = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $stream['server_id'];
	}

	// get customer details
	$query = $conn->query( "SELECT `id` FROM `customers` WHERE `username` = '".$username."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	// check it record already exists and update if found or create new record if no match found
	$query = $conn->query( "SELECT * FROM `streams_connection_logs` WHERE `stream_id` = '".$stream_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' " );
	$result = $query->fetch( PDO::FETCH_ASSOC );
	if(isset($result['id'] ) ) {
		// update existing record
		$update = $conn->exec( "UPDATE `streams_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' " );
	}else{
		// add new record
		$insert = $conn->exec( "INSERT INTO `streams_connection_logs` 
	        (`timestamp`,`server_id`,`client_ip`,`stream_id`,`customer_id`)
	        VALUE
	        ( '".time()."','".$server_id."','".$client_ip."','".$stream_id."','".$customer['id']."')" );
	}
	
	$data = array();
	json_output( $data );
}

function channel_247_connection_log()
{
	global $conn, $global_settings, $geoip, $geoisp;

	// set default
	$output = array();

	$server_id 		= get( 'server_id' );
	$client_ip 		= get( 'client_ip' );
	$channel_id 	= get( 'channel_id' );
	$username 		= get( 'username' );
	// $stream_name 	= get( 'stream_name' );

	if( empty( $server_id ) ) {
		$query = $conn->query( "SELECT `server_id` FROM `channels` WHERE `id` = '".$channel_id."' " );
		$stream = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $stream['server_id'];
	}

	// get customer details
	$query = $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	// check it record already exists and update if found or create new record if no match found
	$query = $conn->query( "SELECT * FROM `customers_connection_logs` WHERE `channel_id` = '".$channel_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' " );
	$result = $query->fetch( PDO::FETCH_ASSOC );
	if(isset($result['id'] ) ) {
		// update existing record
		$update = $conn->exec( "UPDATE `customers_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' " );
	}else{
		// add new record
		$insert = $conn->exec( "INSERT INTO `customers_connection_logs` 
	        (`timestamp`,`server_id`,`client_ip`,`channel_id`,`customer_id`)
	        VALUE
	        ( '".time()."','".$server_id."','".$client_ip."','".$channel_id."','".$customer['id']."')" );
	}
	
	if(is_customer_connection_allowed($customer['id']) == true) {
		$output['status']			= 'success';
		$output['message']			= '';
	}else{
		$output['status']			= 'failed';
		$output['message']			= 'customer max_connections reached';
	}
	
	json_output( $output );
	die();
}

function vod_connection_log()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_id 		= get( 'server_id' );
	$client_ip 		= get( 'client_ip' );
	$vod_id 		= get( 'vod_id' );
	$username 		= get( 'username' );
	// $stream_name 	= get( 'stream_name' );

	if( empty( $server_id ) ) {
		$query = $conn->query( "SELECT `server_id` FROM `vod` WHERE `id` = '".$vod_id."' " );
		$vod = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $vod['server_id'];
	}

	// get customer details
	$query = $conn->query( "SELECT `id` FROM `customers` WHERE `username` = '".$username."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	// check it record already exists and update if found or create new record if no match found
	$query = $conn->query( "SELECT * FROM `vod_connection_logs` WHERE `vod_id` = '".$vod_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' " );
	$result = $query->fetch( PDO::FETCH_ASSOC );
	if(isset($result['id'] ) ) {
		// update existing record
		$update = $conn->exec( "UPDATE `vod_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' " );
	}else{
		// add new record
		$insert = $conn->exec( "INSERT INTO `vod_connection_logs` 
	        (`timestamp`,`server_id`,`client_ip`,`vod_id`,`customer_id`)
	        VALUE
	        ( '".time()."','".$server_id."','".$client_ip."','".$vod_id."','".$customer['id']."')" );
	}
	
	$data = array();
	json_output( $data );
}

function vod_tv_connection_log()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_id 		= get( 'server_id' );
	$client_ip 		= get( 'client_ip' );
	$series_id 		= get( 'series_id' );
	$username 		= get( 'username' );
	// $stream_name 	= get( 'stream_name' );

	if( empty( $server_id ) ) {
		$query = $conn->query( "SELECT `server_id` FROM `tv_series_files` WHERE `id` = '".$series_id."' " );
		$vod = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $vod['server_id'];
	}

	// get customer details
	$query = $conn->query( "SELECT `id` FROM `customers` WHERE `username` = '".$username."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	// check it record already exists and update if found or create new record if no match found
	$query = $conn->query( "SELECT * FROM `series_connection_logs` WHERE `series_id` = '".$series_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' " );
	$result = $query->fetch( PDO::FETCH_ASSOC );
	if(isset($result['id'] ) ) {
		// update existing record
		$update = $conn->exec( "UPDATE `series_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' " );
	}else{
		// add new record
		$insert = $conn->exec( "INSERT INTO `series_connection_logs` 
	        (`timestamp`,`server_id`,`client_ip`,`series_id`,`customer_id`)
	        VALUE
	        ( '".time()."','".$server_id."','".$client_ip."','".$series_id."','".$customer['id']."')" );
	}
	
	$data = array();
	json_output( $data );
}

function force_status_change()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$uuid								= get( 'uuid' );
	$status								= get( 'status' );

	if( empty( $uuid ) ) {
		$output['status']				= 'error';
		$output['message']				= 'missing uuid.';
		json_output( $output );
		die();
	}

	if( empty( $status ) ) {
		$output['status']				= 'error';
		$output['message']				= 'missing status.';
		json_output( $output );
		die();
	}

	$update = $conn->exec( "UPDATE `servers` SET `status` = '".$status."' 				WHERE `uuid` = '".$uuid."' " );

	// summary output
	$output['status']		= 'success';
	
	json_output( $output );
	die();
}

function installer_progress()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$uuid								= get( 'uuid' );
	$progress							= get( 'progress' );

	if( empty( $uuid ) ) {
		$output['status']				= 'error';
		$output['message']				= 'missing uuid.';
		json_output( $output );
		die();
	}

	if( empty( $progress ) ) {
		$output['status']				= 'error';
		$output['message']				= 'missing progress.';
		json_output( $output );
		die();
	}

	$update = $conn->exec( "UPDATE `servers` SET `install_progress` = '".$progress."' 				WHERE `uuid` = '".$uuid."' " );

	// summary output
	$output['status']		= 'success';
	
	json_output( $output );
	die();
}

function checkin()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$data['post']					= $_POST;

	// error_log( "Incoming data from: " . $data['post']['ip_address']);

	if( empty( $data['post']['uuid'] ) ) {
		$output['status']				= 'error';
		$output['message']				= 'missing uuid.';
		json_output( $output );
		die();
	}

	if( empty( $data['post']['ip_address'] ) ) {
		$output['status']				= 'error';
		$output['message']				= 'missing ip address.';
		json_output( $output );
		die();
	}

	// get the server_id
	$query 					= $conn->query( "SELECT `id`,`name`,`user_id` FROM `servers` WHERE `uuid` = '".$data['post']['uuid']."' " );
	$server 				= $query->fetch( PDO::FETCH_ASSOC );
	$server_id 				= $server['id'];
	$output['server_id'] 	= $server_id;

	// add useage for server graphs
	/*
	$insert = $conn->exec( "INSERT INTO `server_stats_history` 
        (`added`,`user_id`,`server_id`,`bandwidth_up`,`bandwidth_down`, `cpu_usage`,`ram_usage`)
        VALUE
        ( '".time()."000', '".$server['user_id']."', '".$server['id']."', '".$data['post']['bandwidth_up']."','".$data['post']['bandwidth_down']."', '".str_replace( '%','',$data['post']['cpu_usage'])."', '".str_replace( '%','',$data['post']['ram_usage'])."')" );
        */

	// log the checkin
	$insert = $conn->exec( "INSERT INTO `servers_logs` 
        (`server_id`,`added`, `message`)
        VALUE
        ( '".$server_id."','".time()."', '".$server['name']." checked in.')" );

	$output['event_logged'] = true;

	// update server core stats
	$update = $conn->exec( "UPDATE `servers` SET `updated` = '".time()."' 										WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `status` = 'online' 											WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `uptime` = '".$data['post']['uptime']."' 						WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `os_version` = '".$data['post']['os_version']."' 				WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `ip_address` = '".$data['post']['ip_address']."' 				WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `wan_ip_address` = '".$_SERVER['REMOTE_ADDR']."' 				WHERE `uuid` = '".$data['post']['uuid']."' " );
	
	if(isset($data['post']['cpu_usage']) && !empty($data['post']['cpu_usage'] ) ) {
		$update = $conn->exec( "UPDATE `servers` SET `cpu_usage` = '".$data['post']['cpu_usage']."' 				WHERE `uuid` = '".$data['post']['uuid']."' " );
	}
	if(isset($data['post']['ram_usage']) && !empty($data['post']['ram_usage'] ) ) {
		$update = $conn->exec( "UPDATE `servers` SET `ram_usage` = '".$data['post']['ram_usage']."' 				WHERE `uuid` = '".$data['post']['uuid']."' " );
	}
	if(isset($data['post']['disk_usage']) && !empty($data['post']['disk_usage'] ) ) {
		$update = $conn->exec( "UPDATE `servers` SET `disk_usage` = '".$data['post']['disk_usage']."' 			WHERE `uuid` = '".$data['post']['uuid']."' " );
	}
	
	$update = $conn->exec( "UPDATE `servers` SET `bandwidth_down` = '".$data['post']['bandwidth_down']."' 		WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `bandwidth_up` = '".$data['post']['bandwidth_up']."' 			WHERE `uuid` = '".$data['post']['uuid']."' " );

	$update = $conn->exec( "UPDATE `servers` SET `cpu_model` = '".ltrim($data['post']['cpu_model'])."' 			WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `cpu_cores` = '".$data['post']['cpu_cores']."' 					WHERE `uuid` = '".$data['post']['uuid']."' " );
	$update = $conn->exec( "UPDATE `servers` SET `cpu_speed` = '".$data['post']['cpu_speed']."' 					WHERE `uuid` = '".$data['post']['uuid']."' " );

	$update = $conn->exec( "UPDATE `servers` SET `ram_total` = '".$data['post']['ram_total']."' 					WHERE `uuid` = '".$data['post']['uuid']."' " );

	$update = $conn->exec( "UPDATE `servers` SET `kernel` = '".$data['post']['kernel']."' 						WHERE `uuid` = '".$data['post']['uuid']."' " );

	$update = $conn->exec( "UPDATE `servers` SET `active_connections` = '".$data['post']['active_connections']."' WHERE `uuid` = '".$data['post']['uuid']."' " );

	if(!empty($data['post']['gpu_stats'] ) ) {
		$update = $conn->exec( "UPDATE `servers` SET `gpu_stats` = '".json_encode($data['post']['gpu_stats'])."' WHERE `uuid` = '".$data['post']['uuid']."' " );
	}else{
		$update = $conn->exec( "UPDATE `servers` SET `gpu_stats` = '' WHERE `uuid` = '".$data['post']['uuid']."' " );
	}
	
	$output['server_data'] = 'updated';

	// summary output
	$output['status']				= 'success';
	$output['message']				= 'server has been updated.';
	$output['post_data']			= $data['post'];
	
	json_output( $output );
	die();
}

function jobs()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_uuid = $_GET['uuid'];

	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server_found = $query->rowCount();
	if($server_found != 0) {
		$server = $query->fetch( PDO::FETCH_ASSOC );

		$query = $conn->query( "SELECT * FROM `jobs` WHERE `server_id` = '".$server['id']."' AND `status` = 'pending' LIMIT 500" );
		$jobs_found = $query->rowCount();
		if($jobs_found != 0) {
			$jobs = $query->fetchALL( PDO::FETCH_ASSOC );

			$jobs[0]['job'] = json_decode($jobs[0]['job'], true);

			if(isset($jobs[1] ) ) {
				$jobs[1]['job'] = json_decode($jobs[1]['job'], true);
			}
			if(isset($jobs[2] ) ) {
				$jobs[2]['job'] = json_decode($jobs[2]['job'], true);
			}
			if(isset($jobs[3] ) ) {
				$jobs[3]['job'] = json_decode($jobs[3]['job'], true);
			}
			if(isset($jobs[4] ) ) {
				$jobs[4]['job'] = json_decode($jobs[4]['job'], true);
			}
		}else{
			$jobs = array();
		}

		json_output($jobs);
		die();
	}
}

function job_complete()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$job_id = $_GET['id'];

	$update = $conn->exec( "UPDATE `jobs` SET `status` = 'complete' WHERE `id` = '".$job_id."' " );

	$output['status']			= 'success';
	$output['message']			= 'job marked as completed.';
	
	json_output( $output );
	die();
}

function server()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$output 		= array();

	$server_uuid 	= get( 'server_uuid' );

	$stream_id 		= get( 'stream_id' );

	// $server_uuid = $_GET['server_uuid'];

	if( empty( $server_uuid ) ) {
		die( 'missing server_uuid' );
	}

	header( "Content-Type:application/json; charset=utf-8" );

	$query = $conn->query( "SELECT * FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	if($query !== FALSE) {
		$server = $query->fetch( PDO::FETCH_ASSOC );

		$output = $server;
		
		// convert seconds to human readable format
		$output['uptime'] 						= uptime($server['uptime']);

		// get vod monitored folders
		$query = $conn->query( "SELECT * FROM `vod_monitored_folders` WHERE `server_id` = '".$server['id']."' " );
		if($query !== FALSE) {
			$output['vod_monitored_folders'] = $query->fetchAll( PDO::FETCH_ASSOC );
			$output['total_vod_monitored_folders'] = count($output['vod_monitored_folders']);

			// get existing vod so no dupe omdb check
			$query = $conn->query( "SELECT `file_location` FROM `vod_files` WHERE `server_id` = '".$server['id']."' " );
			$output['vod_files'] = $query->fetchAll( PDO::FETCH_ASSOC );
		}else{
			$output['total_vod_monitored_folders'] = 0;
		}

		// get vod tv monitored folders
		$query = $conn->query( "SELECT * FROM `vod_tv_monitored_folders` WHERE `server_id` = '".$server['id']."' " );
		if($query !== FALSE) {
			$output['vod_tv_monitored_folders'] = $query->fetchAll( PDO::FETCH_ASSOC );
			$output['total_vod_tv_monitored_folders'] = count($output['vod_tv_monitored_folders']);

			// get existing vod so no dupe omdb check
			$query = $conn->query( "SELECT `file_location` FROM `vod_tv_files` WHERE `server_id` = '".$server['id']."' " );
			$output['vod_tv_files'] = $query->fetchAll( PDO::FETCH_ASSOC );
		}else{
			$output['total_tv_vod_monitored_folders'] = 0;
		}

		// get 247 channels monitored folders
		$query = $conn->query( "SELECT * FROM `channels_247_monitored_folders` WHERE `server_id` = '".$server['id']."' " );
		if($query !== FALSE) {
			$output['channels_247_monitored_folders'] = $query->fetchAll( PDO::FETCH_ASSOC );
			$output['total_channels_247_monitored_folders'] = count($output['channels_247_monitored_folders']);

			// get existing vod so no dupe omdb check
			$query = $conn->query( "SELECT `file_location` FROM `channels_247_files` WHERE `server_id` = '".$server['id']."' " );
			$output['channels_247_files'] = $query->fetchAll( PDO::FETCH_ASSOC );
		}else{
			$output['total_channels_247_monitored_folders'] = 0;
		}

		// get 247 channels to stream
		$query = $conn->query("SELECT `id`,`title`,`stream` FROM `channels_247` WHERE `server_id` = '".$server['id']."' ORDER BY `title` ");
		if($query !== FALSE) {
			$channels_247 = $query->fetchAll( PDO::FETCH_ASSOC );
			// $raw_streams = $query->fetchAll( PDO::FETCH_ASSOC );

			$output['total_channels_247'] = count($channels_247);

			// get media files for this series
			$channel_247_count = 0;
			foreach($channels_247 as $channel_247) {
				$output['channels_247'][$channel_247_count]['id'] 						= $channel_247['id'];
				$output['channels_247'][$channel_247_count]['title'] 					= stripslashes($channel_247['title']);
				$output['channels_247'][$channel_247_count]['stream'] 					= $channel_247['stream'];

				// get the files for this show
				$query = $conn->query("SELECT `id`,`file_location` FROM `channels_247_files` WHERE `vod_id` = '".$channel_247['id']."' ORDER BY `season`,`episode` ");
				$channel_247_files = $query->fetchAll( PDO::FETCH_ASSOC );

				$channel_247_file_count = 0;
				foreach($channel_247_files as $channel_247_file) {
					$output['channels_247'][$channel_247_count]['files'][$channel_247_file_count]['id']			= $channel_247_file['id'];
					$output['channels_247'][$channel_247_count]['files'][$channel_247_file_count]['file']		= $channel_247_file['file_location'];

					$channel_247_file_count++;
				}
				
				$channel_247_count++;
			}
		}else{
			$output['total_channels'] = 0;
		}

		// get channels
		$query = $conn->query("SELECT * FROM `channels` ");
		if($query !== FALSE) {
			$channels = $query->fetchAll( PDO::FETCH_ASSOC );

			$output['total_channels'] = count( $channels );

			// add linkded datasets
			$channel_count = 0;
			foreach( $channels as $channel ) {
				$output['channels'][$channel_count] = $channel;

				// get channel sources
				$query = $conn->query("SELECT `source` FROM `channels_sources` WHERE `channel_id` = '".$channel['id']."' ORDER BY `order` ");
				$output['channels'][$channel_count]['sources'] = $query->fetchAll( PDO::FETCH_ASSOC );

				$channel_count++;
			}
		}else{
			$output['total_channels'] = 0;
		}

		json_output( $output );
	}
}

function channel_status_update()
{
	global $conn, $global_settings, $geoip, $geoisp;

	header( "Content-Type:application/json; charset=utf-8" );

	$channel_id 			= get( 'id' );
	$server_id 				= get( 'server_id' );
	$server_type 			= get( 'server_type' );
	$channel_status 		= get( 'status' );
	$channel_stats 			= base64_decode( get( 'channel_stats') );

	// update channel details
	$update = $conn->exec( "UPDATE `channels` SET `updated` = '".time()."' WHERE `id` = '".$channel_id."' " );	
	$update = $conn->exec( "UPDATE `channels` SET `status` = '".$channel_status."' WHERE `id` = '".$channel_id."' " );

	if( $channel_status == 'online' ) {
		if( isset( $_GET['pid'] ) && !empty( $_GET['pid'] ) ) {
			$channel_pid 			= get( 'pid' );
			$channel_pid 			= preg_replace( "/[^0-9]/", "", $channel_pid);
			$update = $conn->exec( "UPDATE `channels` SET `running_pid` = '".$channel_pid."' WHERE `id` = '".$channel_id."' " );
		}

		$channel_uptime				= get( 'uptime' );
		$channel_fps				= get( 'fps' );
		$channel_speed				= get( 'speed' );

		if( isset( $_GET['resolution_w'] ) && !empty( $_GET['resolution_w'] ) ) {
			$channel_width 			= get( 'resolution_w' );
			$update = $conn->exec( "UPDATE `channels` SET `probe_width` = '".$channel_width."' WHERE `id` = '".$channel_id."' " );
		}

		if( isset( $_GET['resolution_h'] ) && !empty( $_GET['resolution_h'] ) ) {
			$channel_height 			= get( 'resolution_h' );
			$update = $conn->exec( "UPDATE `channels` SET `probe_height` = '".$channel_height."' WHERE `id` = '".$channel_id."' " );
		}

		if( isset( $_GET['bitrate'] ) && !empty( $_GET['bitrate'] ) ) {
			$channel_bitrate 		= get( 'bitrate' );
			$update = $conn->exec( "UPDATE `channels` SET `probe_bitrate` = '".$channel_bitrate."' WHERE `id` = '".$channel_id."' " );
		}

		if( isset( $_GET['aspect_ratio'] ) && !empty( $_GET['aspect_ratio'] ) ) {
			$channel_aspect_ratio 	= get( 'aspect_ratio' );
			$update = $conn->exec( "UPDATE `channels` SET `probe_aspect_ratio` = '".$channel_aspect_ratio."' WHERE `id` = '".$channel_id."' " );
		}

		if( isset( $_GET['video_codec'] ) && !empty( $_GET['video_codec'] ) ) {
			$channel_video_codec 	= get( 'video_codec' );
			$update = $conn->exec( "UPDATE `channels` SET `probe_video_codec` = '".$channel_video_codec."' WHERE `id` = '".$channel_id."' " );
		}

		if( isset( $_GET['audio_codec'] ) && !empty( $_GET['audio_codec'] ) ) {
			$channel_audio_codec 	= get( 'audio_codec' );
			$update = $conn->exec( "UPDATE `channels` SET `probe_audio_codec` = '".$channel_audio_codec."' WHERE `id` = '".$channel_id."' " );
		}

		$update = $conn->exec( "UPDATE `channels` SET `uptime` = '".$channel_uptime."' 						WHERE `id` = '".$channel_id."' " );
		$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'none' 								WHERE `id` = '".$channel_id."' " );
		$update = $conn->exec( "UPDATE `channels` SET `fps` = '".$channel_fps."' 							WHERE `id` = '".$channel_id."' " );
		$update = $conn->exec( "UPDATE `channels` SET `speed` = '".$channel_speed."' 						WHERE `id` = '".$channel_id."' " );

		// update the settings for this channel / server combo
		if( $server_type == 'primary' ) {
			$update = $conn->exec( "UPDATE `channels_servers` SET `status` = '".$channel_status."' WHERE `type` = 'primary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."'  " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `updated` = '".time()."' WHERE `type` = 'primary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."'  " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `uptime` = '".$channel_uptime."' WHERE `type` = 'primary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."'  " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '".$channel_stats."' WHERE `type` = 'primary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."'  " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `running_pid` = '".$channel_pid."' WHERE `type` = 'primary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."'  " );
		} elseif( $server_type == 'secondary' ) {
			$update = $conn->exec( "UPDATE `channels_servers` SET `status` = '".$channel_status."' WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `secondary_server_id` = '".$server_id."' " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `updated` = '".time()."' WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `secondary_server_id` = '".$server_id."' " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `uptime` = '".$channel_uptime."' WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `secondary_server_id` = '".$server_id."' " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '".$channel_stats."' WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `secondary_server_id` = '".$server_id."' " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `running_pid` = '".$channel_pid."' WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `secondary_server_id` = '".$server_id."' " );
		}
	}

	$output['status']			= 'success';
	$output['message']			= 'channel updated';
	
	json_output( $output );
}

function cdn_channel_status_update()
{
	global $conn, $global_settings, $geoip, $geoisp;

	error_log($_SERVER['REMOTE_ADDR'] . ' is posting cdn_channel_status_update' );

	$stream_id 			= $_GET['id'];
	$server_id 			= $_GET['server_id'];
	$stream_pid 		= $_GET['pid'];
	$stream_uptime		= $_GET['uptime'];
	$stream_status		= $_GET['status'];

	// break uptime from minutes into seconds for handling later
	$time_bits 		= explode( ':', $stream_uptime);
	$minutes 		= $time_bits[0];
	$seconds		= ($minutes * 60);

	$update = $conn->exec( "UPDATE `cdn_streams_servers` SET `running_pid` = '".$stream_pid."' WHERE `stream_id` = '".$stream_id."' AND `server_id` = '".$server_id."' " );
	$update = $conn->exec( "UPDATE `cdn_streams_servers` SET `status` = '".$stream_status."' WHERE `stream_id` = '".$stream_id."' AND `server_id` = '".$server_id."' " );

	$output['status']			= 'success';
	$output['message']			= 'stream updated';
	
	json_output( $output );
	die();
}

function channel_progress()
{
	global $conn, $global_settings, $geoip, $geoisp;

	/*
	header( "Content-Type:application/json; charset=utf-8" );
	*/

	$data['stream_id']				= $_GET['stream_id'];
	$data['post']					= $_POST;

	$insert = $conn->exec( "INSERT INTO `channel_progress` 
        (`timestamp`,`stream_id`,`data`)
        VALUE
        ( '".time()."','".$data['stream_id']."','".json_encode($data['post'])."')" );

	die();

	/*
	json_output( $data );
	

	$req = '';
	foreach($_POST as $key => $value) {
		$req .= $key.$value;
	}

	file_put_contents( '/var/www/html/logs'.$_GET['stream_id'].'_progress.log', $req);
	*/
}

function stream()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_id = $_GET['server_id'];
	$stream_id = $_GET['stream_id'];
	$remote_ip = $_GET['remote_ip'];

	$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' AND `server_id` = '".$server_id."' " );
	$stream_found = $query->rowCount();
	if($stream_found != 0) {
		$stream = $query->fetch( PDO::FETCH_ASSOC );

		$query = $conn->query( "SELECT * FROM `streams_acl_rules` WHERE `server_id` = '".$server_id."' AND `ip_address` = '".$remote_ip."' " );
		$acl_found = $query->rowCount();
		if($acl_found == 0) {
			$data['status'] = 'error';
			$data['message'] = $remote_ip.' acl fail';
		}else{
			$query = $conn->query( "SELECT `wan_ip_address`,`status`,`http_stream_port` FROM `servers` WHERE `id` = '".$server_id."' " );
			$server = $query->fetch( PDO::FETCH_ASSOC );

			if($server['status'] == 'online') {
				$stream['output_options'] = json_decode($stream['output_options']);
				
				$data['status'] = 'success';
				$data['server'] = $server;
				$data['stream'] = $stream;
			}else{
				$data['status'] = 'error';
				$data['message'] = 'server is offline';
			}
		}
	}else{
		$data['status'] = 'error';
		$data['message'] = 'stream not found';
	}

	json_output( $data );
}

function stream_info_live()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_uuid = $_GET['server_uuid'];
	$query = $conn->query( "SELECT * FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );

	$count = 0;
	$query = $conn->query( "SELECT * FROM `streams` WHERE `server_id` = '".$server['id']."'  " );
	$streams_found = $query->rowCount();
	if($streams_found != 0) {
		$streams = $query->fetchAll( PDO::FETCH_ASSOC );

		$data['status'] = 'success';

		foreach($streams as $stream) {
			$data['data'][$count]['id']						= $stream['id'];
			$data['data'][$count]['enable']					= $stream['enable'];
			$data['data'][$count]['stream_type']			= $stream['stream_type'];
			$data['data'][$count]['name'] 					= stripcslashes($stream['name']);
			$data['data'][$count]['pid'] 					= $stream['running_pid'];
			$data['data'][$count]['transcode_hardware'] 	= $stream['cpu_gpu'];
			if($stream['cpu_gpu'] == 'gpu') {
				$data['data'][$count]['gpu'] 				= $stream['gpu'];
			}

			$count++;
		}
	}else{
		$data['status'] = 'error';
		$data['message'] = 'stream not found';
	}

	json_output( $data );
}

function stream_info()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$stream_id = stripslashes($_GET['stream_id']);
	$server_id = stripslashes($_GET['server_id']);
	
	$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' AND `server_id` = '".$server_id."' " );
	$streams_found = $query->rowCount();
	if($streams_found != 0) {
		$stream = $query->fetch( PDO::FETCH_ASSOC );

		$data['status'] = 'success';
		$data['data']	= $stream;
	}else{
		$data['status'] = 'error';
		$data['message'] = 'stream not found';
	}

	json_output( $data );
}

function stream_info_fingerprint()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$stream_id = stripslashes($_GET['stream_id']);
	
	if(!empty($stream_id ) ) {
		$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' " );
		$streams_found = $query->rowCount();
		if($streams_found != 0) {
			$stream = $query->fetch( PDO::FETCH_ASSOC );

			$data['status'] = 'success';
			$data['data']	= $stream;
		}else{
			$data['status'] = 'error';
			$data['message'] = 'stream not found';
		}
	}else{
		$data = '';
	}

	json_output( $data );
}

function firewall_rules()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$data = array();

	$server_uuid = stripslashes($_GET['server_uuid']);
	
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server_found = $query->rowCount();
	if($server_found != 0) {
		$server = $query->fetch( PDO::FETCH_ASSOC );

		// find acl rules
		$query = $conn->query( "SELECT * FROM `streams_acl_rules` WHERE `server_id` = '".$server['id']."' " );
		$firewall_rules = $query->fetchAll( PDO::FETCH_ASSOC );

		// build json array
		foreach($firewall_rules as $firewall_rule) {
			$data[] = $firewall_rule['ip_address'];
		}
	}else{
		$data['status'] = 'error';
		$data['message'] = 'server not found';
	}

	json_output( $data );
}

function channel_info_client()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$username 		= get( 'username' );
	if( empty( $username ) ) {
		$data['status'] = 'error';
		$data['message'] = 'username is missing.';

		json_output( $data );
	}

	$password 		= get( 'password' );
	if( empty( $password ) ) {
		$data['status'] = 'error';
		$data['message'] = 'password is missing.';

		json_output( $data );
	}

	$client_ip 		= get( 'client_ip' );
	if( empty( $client_ip ) ) {
		$data['status'] = 'error';
		$data['message'] = 'client_ip is missing.';

		json_output( $data );
	}

	$stream_id 		= get( 'stream_id' );
	if( empty( $stream_id ) ) {
		$data['status'] = 'error';
		$data['message'] = 'stream_id is missing.';

		json_output( $data );
	}

	// check if ip is banned
	$query 			= $conn->query( "SELECT `id` FROM `banned_ips` WHERE `ip_address` = '".$client_ip."' " );
	$banned_ip 		= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_ip['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned ip found';

		json_output( $data );
	}

	// check if isp is banned
	$isp = $geoisp->get( $client_ip );
    $isp = objectToArray( $isp );
	$query 			= $conn->query( "SELECT `id` FROM `banned_isps` WHERE `isp_name` = '".$isp['isp']."' " );
	$banned_isp 	= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_isp['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned isp found';

		json_output( $data );
	}

	// check if username and password are valid
	$query 			= $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' " );
	$customer 		= $query->fetch( PDO::FETCH_ASSOC );

	if( empty( $customer ) ) {
		$data['status'] = 'error';
		$data['message'] = 'customer not found or invalid login details.';

		json_output( $data );
	}

	if($customer['status'] != 'active') {
		$data['status'] = 'error';
		$data['message'] = 'customer status: '.$customer['status'];

		json_output( $data );
	}

	// check for customer ip
	$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."'  " );
	$ip_lock 		= $query->fetchAll( PDO::FETCH_ASSOC );

	if(!empty($ip_lock ) ) {
		// customer is ip locked, lets check the client_ip against the customers_ips table
		$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."' AND `ip_address` = '".$client_ip."' " );
		$ip_data 		= $query->fetch( PDO::FETCH_ASSOC );

		if( empty( $ip_data ) ) {
			$data['status'] = 'error';
			$data['message'] = $client_ip.' is not allowed for this customer account';

			json_output( $data );
		}
	}

	// get channel data
	$query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$stream_id."' " );
	$channel_found = $query->rowCount();
	if($channel_found != 0) {
		$channel = $query->fetch( PDO::FETCH_ASSOC );

		// handle restream vs direct to source
		if( $channel['method'] == 'restream' ) {
			// get the secondaries for this channel that are currently online
			$time_shift = time() - 300;
			$query = $conn->query( "SELECT * FROM `channels_customer_server_assignments` WHERE `channel_id` = '".$stream_id."' AND `customer_id` = '".$customer['id']."' AND added > '".$time_shift."' ORDER BY `id` DESC LIMIT 1" );
			$existing_channel_servers = $query->fetch( PDO::FETCH_ASSOC );
			if( isset( $existing_channel_servers['id'] ) ) {
				$query = $conn->query( "SELECT * FROM `channels_servers` WHERE `type` = 'secondary' AND `channel_id` = '".$stream_id."' AND `secondary_server_id` = '".$existing_channel_servers['server_id']."' " );
				$channel['secondary'] = $query->fetch( PDO::FETCH_ASSOC );
			} else {
				$query = $conn->query( "SELECT * FROM `channels_servers` WHERE `type` = 'secondary' AND `channel_id` = '".$stream_id."' " );
				$channel_servers = $query->fetchAll( PDO::FETCH_ASSOC );

				$channel['random_secondary'] = array_rand( $channel_servers, 1);

				$channel['secondary'] 	= $channel_servers[$channel['random_secondary']];

				// store the assignment
				$insert = $conn->exec( "INSERT INTO `channels_customer_server_assignments` 
			        (`added`,`server_id`,`channel_id`,`customer_id`)
			        VALUE
			        ('".time()."',
			        '".$channel['secondary']['secondary_server_id']."',
			        '".$stream_id."',
			        '".$customer['id']."'
			    )" );
			}

			// get lb streams details
			$query 					= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$channel['secondary']['secondary_server_id']."' " );
			$channel['server'] 		= $query->fetch( PDO::FETCH_ASSOC );
		} else {
			$query = $conn->query( "SELECT * FROM `channels_sources` WHERE `channel_id` = '".$stream_id."' ORDER BY `order` LIMIT 1" );
			$channel['source'] = $query->fetch( PDO::FETCH_ASSOC );
		}

		$data['status'] 		= 'success';
		$data['data']			= $channel;
	}else{
		$data['status'] 		= 'error';
		$data['message'] 		= 'channel not found';
	}

	json_output( $data );
}

function stream_info_server()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$client_ip 			= get( 'client_ip' );
	if( empty( $client_ip ) ) {
		$data['status'] = 'error';
		$data['message'] = 'client_ip is missing.';
		json_output( $data );
	}

	$stream_id 			= get( 'stream_id' );
	if( empty( $stream_id ) ) {
		$data['status'] = 'error';
		$data['message'] = 'stream_id is missing.';
		json_output( $data );
	}

	$stream_type 		= get( 'stream_type' );
	if( empty( $stream_type ) ) {
		$data['status'] = 'error';
		$data['message'] = 'stream_type is missing.';
		json_output( $data );
	}

	$token 				= get( 'token' );
	if( empty( $token ) ) {
		$data['status'] = 'error';
		$data['message'] = 'token is missing.';
		json_output( $data );
	}

	if($token != $global_settings['master_token']) {
		$data['status'] = 'error';
		$data['message'] = 'invalid token.';
		json_output( $data );
	}

	// get live content
	if($stream_type == 'live' ) {
		$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' " );
		$streams_found = $query->rowCount();
		if($streams_found != 0) {
			$stream = $query->fetch( PDO::FETCH_ASSOC );

			// check it record already exists and update if found or create new record if no match found
			$query = $conn->query( "SELECT * FROM `streams_connection_logs` WHERE `stream_id` = '".$stream_id."' AND `client_ip` = '".$client_ip."' " );
			$result = $query->fetch( PDO::FETCH_ASSOC );
			if(isset($result['id'] ) ) {
				// update existing record
				$update = $conn->exec( "UPDATE `streams_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' " );
			}else{
				$insert = $conn->exec( "INSERT INTO `streams_connection_logs` 
			        (`timestamp`,`server_id`,`client_ip`,`stream_id`,`customer_id`)
			        VALUE
			        ( '".time()."','".$stream['server_id']."','".$client_ip."','".$stream_id."','0')" );
			}

			// get input info
			$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream['source_stream_id']."' " );
			$stream['input'] = $query->fetch( PDO::FETCH_ASSOC );

			// find outputs for this input
			$query = $conn->query( "SELECT * FROM `streams` WHERE `source_stream_id` = '".$stream['source_stream_id']."' " );
			$stream['input']['output'] = $query->fetchAll( PDO::FETCH_ASSOC );

			// get node details
			$query 					= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$stream['server_id']."' " );
			$stream['server'] 		= $query->fetch( PDO::FETCH_ASSOC );
			$stream['server']['id']	= $stream['server_id'];

			$data['status'] 		= 'success';
			$data['data']			= $stream;
		}else{
			$data['status'] 		= 'error';
			$data['message'] 		= 'stream not found';
		}
	}

	// get channel content
	if($stream_type == 'channel' ) {
		$query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$stream_id."' " );
		$streams_found = $query->rowCount();
		if($streams_found != 0) {
			$stream = $query->fetch( PDO::FETCH_ASSOC );

			// check it record already exists and update if found or create new record if no match found
			$query = $conn->query( "SELECT * FROM `streams_connection_logs` WHERE `stream_id` = '".$stream_id."' AND `client_ip` = '".$client_ip."' " );
			$result = $query->fetch( PDO::FETCH_ASSOC );
			if(isset($result['id'] ) ) {
				// update existing record
				$update = $conn->exec( "UPDATE `streams_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' " );
			}else{
				$insert = $conn->exec( "INSERT INTO `streams_connection_logs` 
			        (`timestamp`,`server_id`,`client_ip`,`stream_id`,`customer_id`)
			        VALUE
			        ( '".time()."','".$stream['server_id']."','".$client_ip."','".$stream_id."','0')" );
			}

			// get input info
			// $query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream['source_stream_id']."' " );
			// $stream['input'] = $query->fetch( PDO::FETCH_ASSOC );

			// find outputs for this input
			// $query = $conn->query( "SELECT * FROM `streams` WHERE `source_stream_id` = '".$stream['source_stream_id']."' " );
			// $stream['input']['output'] = $query->fetchAll( PDO::FETCH_ASSOC );

			// get node details
			$query 					= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$stream['server_id']."' " );
			$stream['server'] 		= $query->fetch( PDO::FETCH_ASSOC );
			$stream['server']['id']	= $stream['server_id'];

			$data['status'] 		= 'success';
			$data['data']			= $stream;
		}else{
			$data['status'] 		= 'error';
			$data['message'] 		= 'stream not found';
		}
	}

	json_output( $data );
}

function server_stats_api()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_id 	= $_GET['server_id'];
	$metric 	= $_GET['metric'];

	// $query = $conn->query( "SELECT `added` AS `0`, `".$metric."` AS `1` FROM `server_stats_history` WHERE `user_id` = '".$_SESSION['account']['id']."' AND `server_id` = '".$server_id."' " );
	$query = $conn->query( "SELECT `added` AS `0`, `".$metric."` AS `1` FROM `server_stats_history` WHERE `server_id` = '".$server_id."' " );
	$stats = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	if($metric == 'bandwidth_up' || $metric == 'bandwidth_down' ) {
		foreach($stats as $stat) {
			$data[$count][0] = $stat[0];
			$data[$count][1] = number_format($stat[1] / 125, 0);
			$count++;
		}
	}else{
		$data = $stats;
	}

	$json = json_encode($data, JSON_NUMERIC_CHECK);

	echo $json;
}

function stream_out_server_info()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_id 	= $_GET['server_id'];
	$password 	= $_GET['password'];

	if( empty( $_GET['password']) || $password != '137213921984' ) {
		$data['status'] = 'error';
		$data['message'] = 'incorrect password';
		json_output( $data );
	}

	header( "Content-Type:application/json; charset=utf-8" );

	$query = $conn->query( "SELECT `id`,`wan_ip_address`,`public_hostname`,`http_stream_port` FROM `servers` WHERE `id` = '".$server_id."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );

	$data['id'] 				= $server['id'];
	$data['wan_ip_address'] 	= $server['wan_ip_address'];
	$data['public_hostname'] 	= $server['public_hostname'];
	$data['http_stream_port'] 	= $server['http_stream_port'];

	json_output( $data );
}

function vod_tv_info_client()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$username 		= get( 'username' );
	$password 		= get( 'password' );
	$series_id 		= stripslashes($_GET['series_id']);
	$client_ip 		= get( 'client_ip' );

	// check if ip is banned
	$query 			= $conn->query( "SELECT `id` FROM `banned_ips` WHERE `ip_address` = '".$client_ip."' " );
	$banned_ip 		= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_ip['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned ip found';

		json_output( $data );
	}

	// check if isp is banned
	$isp = $geoisp->get( $client_ip );
    $isp = objectToArray( $isp );
	$query 			= $conn->query( "SELECT `id` FROM `banned_isps` WHERE `isp_name` = '".$isp['isp']."' " );
	$banned_isp 	= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_isp['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned isp found';

		json_output( $data );
	}

	// check if username and password are valid
	$query 		= $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' " );
	$customer 	= $query->fetch( PDO::FETCH_ASSOC );

	if( empty( $customer ) ) {
		// header( 'HTTP/1.0 403 Forbidden' );
		// die( "customer not found" );
		
		$data['status'] = 'error';
		$data['message'] = 'customer not found or invalid login details.';

		json_output( $data );
	}

	if($customer['status'] != 'active') {
		// header( 'HTTP/1.0 403 Forbidden' );
		// die( "account status: ".$customer['status']);

		$data['status'] = 'error';
		$data['message'] = 'customer status: '.$customer['status'];

		json_output( $data );
	}

	// check for customer ip
	$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."'  " );
	$ip_lock 		= $query->fetchAll( PDO::FETCH_ASSOC );

	if(!empty($ip_lock ) ) {
		// customer is ip locked, lets check the client_ip against the customers_ips table
		$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."' AND `ip_address` = '".$client_ip."' " );
		$ip_data 		= $query->fetch( PDO::FETCH_ASSOC );

		if( empty( $ip_data ) ) {
			$data['status'] = 'error';
			$data['message'] = $client_ip.' is not allowed for this customer account';

			json_output( $data );
		}
	}
	
	// get series data
	$query = $conn->query( "SELECT * FROM `vod_tv_files` WHERE `id` = '".$series_id."' " );
	$series_found = $query->rowCount();
	if($series_found != 0) {
		$series = $query->fetch( PDO::FETCH_ASSOC );

		// get server info
		$query 						= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$series['server_id']."' " );
		$series['server']			= $query->fetch( PDO::FETCH_ASSOC );
		$series['server']['id']		= $series['server_id'];

		$data['status'] = 'success';
		$data['data']	= $series;
	}else{
		$data['status'] = 'error';
		$data['message'] = 'series not found';
	}

	json_output( $data );
}

function vod_info_client()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$username 		= get( 'username' );
	$password 		= get( 'password' );
	$vod_id 		= stripslashes($_GET['vod_id']);
	$client_ip 		= get( 'client_ip' );

	// check if ip is banned
	$query 			= $conn->query( "SELECT `id` FROM `banned_ips` WHERE `ip_address` = '".$client_ip."' " );
	$banned_ip 		= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_ip['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned ip found';

		json_output( $data );
	}

	// check if isp is banned
	$isp = $geoisp->get( $client_ip );
    $isp = objectToArray( $isp );
	$query 			= $conn->query( "SELECT `id` FROM `banned_isps` WHERE `isp_name` = '".$isp['isp']."' " );
	$banned_isp 	= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_isp['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned isp found';

		json_output( $data );
	}

	// check if username and password are valid
	$query = $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	if( empty( $customer ) ) {
		// header( 'HTTP/1.0 403 Forbidden' );
		// die( "customer not found" );
		
		$data['status'] = 'error';
		$data['message'] = 'customer not found or invalid login details.';

		json_output( $data );
	}

	if($customer['status'] != 'active') {
		// header( 'HTTP/1.0 403 Forbidden' );
		// die( "account status: ".$customer['status']);

		$data['status'] = 'error';
		$data['message'] = 'customer status: '.$customer['status'];

		json_output( $data );
	}

	// check for customer ip
	$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."'  " );
	$ip_lock 		= $query->fetchAll( PDO::FETCH_ASSOC );

	if(!empty($ip_lock ) ) {
		// customer is ip locked, lets check the client_ip against the customers_ips table
		$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."' AND `ip_address` = '".$client_ip."' " );
		$ip_data 		= $query->fetch( PDO::FETCH_ASSOC );

		if( empty( $ip_data ) ) {
			$data['status'] = 'error';
			$data['message'] = $client_ip.' is not allowed for this customer account';

			json_output( $data );
		}
	}
	
	// get series data
	$query = $conn->query( "SELECT * FROM `vod` WHERE `id` = '".$vod_id."' " );
	$vod_found = $query->rowCount();
	if($vod_found != 0) {
		$vod = $query->fetch( PDO::FETCH_ASSOC );

		// get the file info
		$query = $conn->query( "SELECT * FROM `vod_files` WHERE `vod_id` = '".$vod_id."' " );
		$vod_files = $query->fetchAll( PDO::FETCH_ASSOC );
		$vod['file_location'] = $vod_files[0]['file_location'];

		// get server info
		$query 					= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$vod['server_id']."' " );
		$vod['server']			= $query->fetch( PDO::FETCH_ASSOC );
		$vod['server']['id']	= $vod['server_id'];

		$data['status'] 		= 'success';
		$data['data']			= $vod;
	}else{
		$data['status'] 		= 'error';
		$data['message'] 		= 'vod not found';
	}

	json_output( $data );
}

function vod_info_server()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$server_ip 			= get( 'server_ip' );
	$data['server_ip']	= $server_ip;
	$token 				= get( 'token' );
	$data['token']		= $token;
	$vod_id 			= stripslashes($_GET['vod_id']);

	// does the token match
	$query 			= $conn->query( "SELECT `id` FROM `global_settings` WHERE `config_name` = 'master_token' AND `config_value` = '".$token."' " );
	$token_match 	= $query->fetch( PDO::FETCH_ASSOC );

	if( !isset( $token_match['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'unauthorized token';

		json_output( $data );
	}

	// check if server_ip is one of ours
	$query 			= $conn->query( "SELECT `id` FROM `servers` WHERE `wan_ip_address` = '".$server_ip."' " );
	$our_server 	= $query->fetch( PDO::FETCH_ASSOC );

	if( !isset( $our_server['id'] ) ) {
		// $data['status'] = 'error';
		// $data['message'] = 'unauthorized server ip';

		// json_output( $data );
	}
	
	// get vod data
	$query = $conn->query( "SELECT * FROM `vod` WHERE `id` = '".$vod_id."' " );
	$vod_found = $query->rowCount();
	if($vod_found != 0) {
		$vod = $query->fetch( PDO::FETCH_ASSOC );

		// get the file info
		$query = $conn->query( "SELECT * FROM `vod_files` WHERE `vod_id` = '".$vod_id."' " );
		$vod_files = $query->fetchAll( PDO::FETCH_ASSOC );
		$vod['file_location'] = $vod_files[0]['file_location'];

		// get server info
		$query 					= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$vod['server_id']."' " );
		$vod['server']			= $query->fetch( PDO::FETCH_ASSOC );
		$vod['server']['id']	= $vod['server_id'];

		$data['status'] 		= 'success';
		$data['data']			= $vod;
	}else{
		$data['status'] 		= 'error';
		$data['message'] 		= 'vod not found';
	}

	json_output( $data );
}

function channel_247_info_client()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$username 		= get( 'username' );
	$password 		= get( 'password' );
	$channel_id 	= get( 'channel_id' );
	$client_ip 		= get( 'client_ip' );

	// check if ip is banned
	$query 			= $conn->query( "SELECT `id` FROM `banned_ips` WHERE `ip_address` = '".$client_ip."' " );
	$banned_ip 		= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_ip['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned ip found';

		json_output( $data );
	}

	// check if isp is banned
	$isp = $geoisp->get( $client_ip );
    $isp = objectToArray( $isp );
	$query 			= $conn->query( "SELECT `id` FROM `banned_isps` WHERE `isp_name` = '".$isp['isp']."' " );
	$banned_isp 	= $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $banned_isp['id'] ) ) {
		$data['status'] = 'error';
		$data['message'] = 'banned isp found';

		json_output( $data );
	}

	// check if username and password are valid
	$query = $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	if( empty( $customer ) ) {
		// header( 'HTTP/1.0 403 Forbidden' );
		// die( "customer not found" );
		
		$data['status'] = 'error';
		$data['message'] = 'customer not found or invalid login details.';

		json_output( $data );
	}

	if($customer['status'] != 'active') {
		// header( 'HTTP/1.0 403 Forbidden' );
		// die( "account status: ".$customer['status']);

		$data['status'] = 'error';
		$data['message'] = 'customer status: '.$customer['status'];

		json_output( $data );
	}

	// check for customer ip
	$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."'  " );
	$ip_lock 		= $query->fetchAll( PDO::FETCH_ASSOC );

	if( !empty( $ip_lock ) ) {
		// customer is ip locked, lets check the client_ip against the customers_ips table
		$query 			= $conn->query( "SELECT `id` FROM `customers_ips` WHERE `customer_id` = '".$customer['id']."' AND `ip_address` = '".$client_ip."' " );
		$ip_data 		= $query->fetch( PDO::FETCH_ASSOC );

		if( empty( $ip_data ) ) {
			$data['status'] = 'error';
			$data['message'] = $client_ip.' is not allowed for this customer account';

			json_output( $data );
		}
	}

	// check if max connections already reached or not
	$customer['current_connections'] = get_all_customer_connections( $customer['id'] );
	
	// error_log( "\n\n" );
	// error_log( count( $customer['current_connections'] ).' / '.$customer['max_connections'] );
	// error_log( "\n\n" );

	if( count( $customer['current_connections'] ) > $customer['max_connections'] ) {
		$data['status'] = 'error';
		$data['message'] = 'too many connections: '.count( $customer['current_connections'] ).' / '.$customer['max_connections'];

		json_output( $data );
	}
	
	// get channel data
	$query = $conn->query( "SELECT * FROM `channels_247` WHERE `id` = '".$channel_id."' " );
	$channel_found = $query->rowCount();
	if($channel_found != 0) {
		$channel = $query->fetch( PDO::FETCH_ASSOC );

		// get server info
		$query 						= $conn->query( "SELECT `id`,`wan_ip_address`,`http_stream_port` FROM `servers` WHERE `id` = '".$channel['server_id']."' " );
		$channel['server'] 			= $query->fetch( PDO::FETCH_ASSOC );
		$channel['server']['id']	= $channel['server_id'];
		
		$data['status'] = 'success';
		$data['data']	= $channel;
	}else{
		$data['status'] = 'error';
		$data['message'] = 'channel not found';
	}

	json_output( $data );
}

function channel_247_status_update()
{
	global $conn, $global_settings, $geoip, $geoisp;

	header( "Content-Type:application/json; charset=utf-8" );

	// error_log($_SERVER['REMOTE_ADDR'] . ' is posting channel_status_update' );

	$id 			= get( 'id' );
	$status 		= get( 'status' );
	$uptime 		= get( 'uptime' );

	$update = $conn->exec( "UPDATE `channels_247` SET `status` = '".$status."' 			WHERE `id` = '".$id."' " );

	if($status == 'online' ) {
		$update = $conn->exec( "UPDATE `channels_247` SET `uptime` = '".$uptime."' 		WHERE `id` = '".$id."' " );
	}else{
		$update = $conn->exec( "UPDATE `channels_247` SET `uptime` = '0' 				WHERE `id` = '".$id."' " );
	}

	$output['status']			= 'success';
	$output['message']			= 'channel_247 updated';
	
	json_output( $output );
	die();
}

function channel_info_fingerprint()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$channel_id = stripslashes($_GET['channel_id']);
	
	$query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$channel_id."' " );
	$channel_found = $query->rowCount();
	if($channel_found != 0) {
		$channel = $query->fetch( PDO::FETCH_ASSOC );

		$data['status'] = 'success';
		$data['data']	= $channel;
	}else{
		$data['status'] = 'error';
		$data['message'] = 'stream not found';
	}

	json_output( $data );
}

function roku_device_update()
{
	global $conn, $global_settings, $geoip, $geoisp;

	header( "Content-Type:application/json; charset=utf-8" );

	$device_id 				= get( 'device_id' );
	if( empty( $device_id ) ) {
		die( "device_id missing" );
	}

	$update 				= $conn->exec( "UPDATE `roku_devices` SET `updated` = '".time()."' WHERE `id` = '".$device_id."' " );

	$device_status 			= $_GET['status'];
	$update 				= $conn->exec( "UPDATE `roku_devices` SET `status` = '".$device_status."' WHERE `id` = '".$device_id."' " );

	if($device_status == 'online' ) {
		$device_uptime 		= $_GET['uptime'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `uptime` = '".$device_uptime."' WHERE `id` = '".$device_id."' " );

		$device_serial 		= $_GET['serial_number'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `serial_number` = '".$device_serial."' WHERE `id` = '".$device_id."' " );

		$device_uuid 		= $device_id;
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `device_uuid` = '".$device_uuid."' WHERE `id` = '".$device_id."' " );

		$device_model_name 	= $_GET['model_name'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `model_name` = '".$device_model_name."' WHERE `id` = '".$device_id."' " );

		$device_model_num 	= $_GET['model_number'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `model_number` = '".$device_model_num."' WHERE `id` = '".$device_id."' " );

		$device_wifi_mac 	= $_GET['wifi_mac'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `wifi_mac` = '".$device_wifi_mac."' WHERE `id` = '".$device_id."' " );

		$device_eth_mac 	= $_GET['ethernet_mac'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `ethernet_mac` = '".$device_eth_mac."' WHERE `id` = '".$device_id."' " );

		$device_net_type 	= $_GET['network_type'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `network_type` = '".$device_net_type."' WHERE `id` = '".$device_id."' " );

		$device_software 	= $_GET['software_version'];
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `software_version` = '".$device_software."' WHERE `id` = '".$device_id."' " );
	}else{
		$update 			= $conn->exec( "UPDATE `roku_devices` SET `uptime` = '0' WHERE `id` = '".$device_id."' " );
	}

	$output['status']			= 'success';
	$output['message']			= 'device updated';
	
	json_output( $output );
	die();
}

function transcoding_profile()
{
	global $conn, $global_settings, $geoip, $geoisp;

	header( "Content-Type:application/json; charset=utf-8" );

	$data['id']						= stripslashes($_GET['id']);

	if( empty( $data['id'] ) ) {
		$output['status']			= 'error';
		$output['message']			= 'missing id.';
		json_output( $output );
		die();
	}

	// get transcoding_profile
	$query 		= $conn->query( "SELECT * FROM `transcoding_profiles` WHERE `id` = '".$data['id']."' " );
	$data 		= $query->fetch( PDO::FETCH_ASSOC );
	
	$data['data']		= json_decode($data['data'], true);

	json_output( $data );
	die();
}

function vod_add_stiliam_old()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$full_path 		= base64_decode( get( 'full_path' ) );
	$full_path 		= addslashes( $full_path );
	
	// get the filename from the path
	$file 			= basename( $full_path );

	// get the file extension
	$file_ext 		= pathinfo( $file, PATHINFO_EXTENSION );

	// create fallback name
	$fallback_name	= str_replace( ".".$file_ext, "", $file );

	// get server 
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );
	$server_id = $server['id'];

	// did we find the server
	if( isset( $server ) && isset( $server['id'] ) ) {
		// check if we already have this file
		$query = $conn->query( "SELECT `id` FROM `vod_files` WHERE `file_location` = '".addslashes( $full_path )."' " );
		$existing_file = $query->fetch( PDO::FETCH_ASSOC );
		if( !isset( $existing_file['id'] ) ) {
			// file_ext sanity check
			if( in_array( $file_ext, $allowed_files ) ) {
				$file_new 		= str_replace( array( ' ','_','-' ), '.', $file );
				
				$pattern = '/[a-zA-Z0-9\.]+\.[0-9]{4}\./';
				preg_match( $pattern, $file_new, $matches );
				if( isset( $matches[0] ) ) {
					$item_name = substr( str_replace( '.', ' ', $matches[0] ), 0, -6 );
				} else {
					$item_name = $file_new;
				}
				$metadata = get_metadata( $item_name );

				// do we already have this imdbid asset, if so add file_location, if not - create a new asset
				if( !empty( $metadata['imdbid'] ) ) {
					if( !empty( $metadata['poster'] ) && $metadata['poster'] != 'N/A' ) {
						// save the image to the cms server
						$local_filename = basename( $metadata['poster'] );
						$remote_content = file_get_contents( $metadata['poster'] );
						file_put_contents( '/var/www/html/content/imdb_media/'.$local_filename, $remote_content );

						$metadata['poster'] = 'http://'.$global_settings['cms_access_url_raw'].'/content/imdb_media/'.$local_filename;
					}

					$query = $conn->query( "SELECT `id` FROM `vod` WHERE `imdbid` = '".$metadata['imdbid']."' " );
					$existing_movie = $query->fetch( PDO::FETCH_ASSOC );
					if( isset( $existing_movie['id'] ) ) {
						$vod_id = $existing_movie['id'];
					} else {
						// add new record
						$insert = $conn->exec( "INSERT INTO `vod` 
					        (`server_id`,`imdbid`,`title`,`description`,`poster`,`year`,`genre`,`runtime`,`language`,`plot`)
					        VALUE
					        (
						        '".$server_id."',
						        '".$metadata['imdbid']."',
						        '".( empty( $metadata['title'] )? addslashes( $item_name ) : addslashes( $metadata['title'] ) )."',
						        '".$metadata['description']."',
						        '".$metadata['poster']."',
						        '".$metadata['year']."',
						        '".$metadata['genre']."',
						        '".$metadata['runtime']."',
						        '".$metadata['language']."',
						        '".$metadata['plot']."'
						    )" );
						
						$vod_id = $conn->lastInsertId();
					}
				} else {
					// create a record anyways as we could not find a match
					$insert = $conn->exec( "INSERT INTO `vod` 
				        (`server_id`,`imdbid`,`title`,`description`,`poster`,`year`,`genre`,`runtime`,`language`,`plot`)
				        VALUE
				        (
					        '".$server_id."',
					        '".$metadata['imdbid']."',
					        '".( empty( $metadata['title'] )? addslashes( $item_name ) : addslashes( $metadata['title'] ) )."',
					        '".$metadata['description']."',
					        '".$metadata['poster']."',
					        '".$metadata['year']."',
					        '".$metadata['genre']."',
					        '".$metadata['runtime']."',
					        '".$metadata['language']."',
					        '".$metadata['plot']."'
					    )" );
					
					$vod_id = $conn->lastInsertId();
				}

				$insert = $conn->exec( "INSERT INTO `vod_files` 
			        (`server_id`,`vod_id`,`file_location`)
			        VALUE
			        (
				        '".$server_id."',
				        '".$vod_id."',
				        '".addslashes( $full_path )."'
				    )" );
			}
		}
		$data['status'] 	= 'success';
	} else {
		$data['status'] 	= 'error';
		$data['message'] 	= 'server not found';
	}
	
	json_output( $data );
}

function vod_add()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$full_path 		= base64_decode( get( 'full_path' ) );
	$full_path 		= addslashes( $full_path );
	
	// get the filename from the path
	$file 			= basename( $full_path );

	// get the file extension
	$file_ext 		= pathinfo( $file, PATHINFO_EXTENSION );

	// create fallback name
	$fallback_name	= str_replace( ".".$file_ext, "", $file );

	// get server 
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );
	$server_id = $server['id'];

	// did we find the server
	if( isset( $server ) && isset( $server['id'] ) ) {
		// check if we already have this file
		$query = $conn->query( "SELECT `id` FROM `vod_files` WHERE `file_location` = '".addslashes( $full_path )."' " );
		$existing_file = $query->fetch( PDO::FETCH_ASSOC );
		if( !isset( $existing_file['id'] ) ) {
			// file_ext sanity check
			if( in_array( $file_ext, $allowed_files ) ) {
				$file_new 		= str_replace( array( ' ','_','-' ), '.', $file );
				
				$pattern = '/[a-zA-Z0-9\.]+\.[0-9]{4}\./';
				preg_match( $pattern, $file_new, $matches );
				if( isset( $matches[0] ) ) {
					$item_name = substr( str_replace( '.', ' ', $matches[0] ), 0, -6 );
				} else {
					$item_name = $file_new;
				}
				
				// insert into the db
				$insert = $conn->exec( "INSERT INTO `vod` 
			        (`server_id`,`title`)
			        VALUE
			        (
				        '".$server_id."',
				        '".addslashes( $item_name )."'
				    )" );
				
				$vod_id = $conn->lastInsertId();

				$insert = $conn->exec( "INSERT INTO `vod_files` 
			        (`server_id`,`vod_id`,`file_location`)
			        VALUE
			        (
				        '".$server_id."',
				        '".$vod_id."',
				        '".addslashes( $full_path )."'
				    )" );
			}
		}
		$data['status'] 	= 'success';
	} else {
		$data['status'] 	= 'error';
		$data['message'] 	= 'server not found';
	}
	
	json_output( $data );
}

function vod_tv_add_stilliam_old()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$show 			= base64_decode( get( 'show' ) );
	$show_hash 		= addslashes( get( 'show' ) );

	// get server 
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );
	$server_id = $server['id'];

	// did we find the server
	if( isset( $server ) && isset( $server['id'] ) ) {
		// remove funky symbols
		// $show = str_replace( array( '(', ')', '[', ']', '{', '}' ), '', $show );

		if( preg_match( "(\([^0-9]*\d+[^0-9]*\))", $show, $result ) ) {
			$show 	= str_replace( $result[0], '', $show );
			$show 	= str_replace( array( '(', ')' ), '', $show );
			$show 	= trim( $show );
		}

		// get the show metadata
		$metadata = get_metadata( $show );

		if( !empty( $metadata['poster'] ) && $metadata['poster'] != 'N/A' ) {
			// save the image to the cms server
			$local_filename = basename( $metadata['poster'] );
			$remote_content = file_get_contents( $metadata['poster'] );
			file_put_contents( '/var/www/html/content/imdb_media/'.$local_filename, $remote_content );

			$metadata['poster'] = 'http://'.$global_settings['cms_access_url_raw'].'/content/imdb_media/'.$local_filename;
		}

		// do we already have this imdbid asset, if so add file_location, if not - create a new asset
		$query = $conn->query( "SELECT `id` FROM `vod_tv` WHERE `show_hash` = '".$show_hash."' " );
		$existing_tv = $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $existing_tv['id'] ) ) {
			$vod_tv_id = $existing_tv['id'];
		} else {
			// add new record
			$insert = $conn->exec( "INSERT INTO `vod_tv` 
		        (`server_id`,`imdbid`,`title`,`description`,`poster`,`year`,`genre`,`runtime`,`language`,`plot`, `match`,`show_hash`)
		        VALUE
		        (
			        '".$server_id."',
			        '".$metadata['imdbid']."',
			        '".( empty( $metadata['title'] )? addslashes( $show ) : addslashes( $metadata['title'] ) )."',
			        '".$metadata['description']."',
			        '".$metadata['poster']."',
			        '".$metadata['year']."',
			        '".$metadata['genre']."',
			        '".$metadata['runtime']."',
			        '".$metadata['language']."',
			        '".$metadata['plot']."',
			        '".( empty( $metadata['title'] )? 'no' : 'yes' )."',
			        '".$show_hash."'
			    )" );
			
			$vod_tv_id = $conn->lastInsertId();
		}

		$data['status'] 	= 'success';
		$data['vod_tv_id'] 	= $vod_tv_id;
		$data['metadata']	= $metadata;
	} else {
		$data['status'] 	= 'error';
		$data['message'] 	= 'server not found';
	}

	json_output( $data );
}

function vod_tv_add()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$show 			= base64_decode( get( 'show' ) );
	$show_hash 		= addslashes( get( 'show' ) );

	// get server 
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );
	$server_id = $server['id'];

	// did we find the server
	if( isset( $server ) && isset( $server['id'] ) ) {
		// remove funky symbols
		// $show = str_replace( array( '(', ')', '[', ']', '{', '}' ), '', $show );

		if( preg_match( "(\([^0-9]*\d+[^0-9]*\))", $show, $result ) ) {
			$show 	= str_replace( $result[0], '', $show );
			$show 	= str_replace( array( '(', ')' ), '', $show );
			$show 	= trim( $show );
		}

		// do we already have this imdbid asset, if so add file_location, if not - create a new asset
		$query = $conn->query( "SELECT `id` FROM `vod_tv` WHERE `show_hash` = '".$show_hash."' " );
		$existing_tv = $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $existing_tv['id'] ) ) {
			$vod_tv_id = $existing_tv['id'];
		} else {
			// add new record
			$insert = $conn->exec( "INSERT INTO `vod_tv` 
		        (`server_id`,`title`,`match`,`show_hash`)
		        VALUE
		        (
			        '".$server_id."',
			        '".addslashes( $show )."',
			        'no',
			        '".$show_hash."'
			    )" );
			
			$vod_tv_id = $conn->lastInsertId();
		}

		$data['status'] 	= 'success';
		$data['vod_tv_id'] 	= $vod_tv_id;
		// $data['metadata']	= $metadata;
	} else {
		$data['status'] 	= 'error';
		$data['message'] 	= 'server not found';
	}

	json_output( $data );
}

function vod_tv_episode_add()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$vod_tv_id 		= get( 'vod_tv_id' );
	$episode 		= base64_decode( get( 'episode' ) );

	// get the filename from the path
	$file 			= basename( $episode );

	// get the file extension
	$file_ext 		= pathinfo( $file, PATHINFO_EXTENSION );

	// do we already have this file
	$query = $conn->query( "SELECT `id` FROM `vod_tv_files` WHERE `file_location` = '".addslashes( $episode )."' " );
	$existing_episode = $query->fetch( PDO::FETCH_ASSOC );
	if( !isset( $existing_episode['id'] ) ) {
		// get server 
		$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
		$server = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $server['id'];

		// get the tv show
		$query = $conn->query( "SELECT `id`,`title`,`imdbid` FROM `vod_tv` WHERE `id` = '".$vod_tv_id."' " );
		$tv_show = $query->fetch( PDO::FETCH_ASSOC );

		// did we find the server
		if( isset( $server ) && isset( $server['id'] ) ) {
			// add the record to the db
			$show['file_location'] 	= $episode;
			$file 					= basename( $episode );

			$insert = $conn->exec( "INSERT INTO `vod_tv_files` 
		        (`server_id`,`vod_id`,`file_location`,`title`,`match`,`preg_match`)
		        VALUE
		        (
			        '".$server_id."',
			        '".$vod_tv_id."',
			        '".addslashes( $show['file_location'] )."',
			        '".addslashes( $file )."',
			        'no',
			        '0'
			    )" );

			$data['status'] 	= 'success';
			$data['meta']		= $show;
		} else {
			$data['status'] 	= 'error';
			$data['message'] 	= 'server not found';
		}
	} else {
		$data['status'] 	= 'success';
		$data['message'] 	= 'episode already exists';
	}

	json_output( $data );
}

function channels_247_add()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$show 			= base64_decode( get( 'show' ) );
	$show_hash 		= addslashes( get( 'show' ) );

	// get server 
	$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );
	$server_id = $server['id'];

	// did we find the server
	if( isset( $server ) && isset( $server['id'] ) ) {
		// remove funky symbols
		// $show = str_replace( array( '(', ')', '[', ']', '{', '}' ), '', $show );

		if( preg_match( "(\([^0-9]*\d+[^0-9]*\))", $show, $result ) ) {
			$show 	= str_replace( $result[0], '', $show );
			$show 	= str_replace( array( '(', ')' ), '', $show );
			$show 	= trim( $show );
		}

		// do we already have this imdbid asset, if so add file_location, if not - create a new asset
		$query = $conn->query( "SELECT `id` FROM `channels_247` WHERE `show_hash` = '".$show_hash."' " );
		$existing_tv = $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $existing_tv['id'] ) ) {
			$vod_tv_id = $existing_tv['id'];
		} else {
			// add new record
			$insert = $conn->exec( "INSERT INTO `channels_247` 
		        (`server_id`,`title`,`match`,`show_hash`)
		        VALUE
		        (
			        '".$server_id."',
			        '".$metadata['imdbid']."',
			        '".addslashes( $show )."',
			        'no',
			        '".$show_hash."'
			    )" );
			
			$vod_tv_id = $conn->lastInsertId();
		}

		$data['status'] 	= 'success';
		$data['vod_tv_id'] 	= $vod_tv_id;
		// $data['metadata']	= $metadata;
	} else {
		$data['status'] 	= 'error';
		$data['message'] 	= 'server not found';
	}

	json_output( $data );
}

function channels_247_episode_add()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	$server_uuid 	= get( 'server_uuid' );
	$vod_tv_id 		= get( 'vod_tv_id' );
	$episode 		= base64_decode( get( 'episode' ) );

	// get the filename from the path
	$file 			= basename( $episode );

	// get the file extension
	$file_ext 		= pathinfo( $file, PATHINFO_EXTENSION );

	// do we already have this file
	$query = $conn->query( "SELECT `id` FROM `channels_247_files` WHERE `file_location` = '".addslashes( $episode )."' " );
	$existing_episode = $query->fetch( PDO::FETCH_ASSOC );
	if( !isset( $existing_episode['id'] ) ) {
		// get server 
		$query = $conn->query( "SELECT `id` FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
		$server = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $server['id'];

		// get the tv show
		$query = $conn->query( "SELECT `id`,`title`,`imdbid` FROM `channels_247` WHERE `id` = '".$vod_tv_id."' " );
		$tv_show = $query->fetch( PDO::FETCH_ASSOC );

		// did we find the server
		if( isset( $server ) && isset( $server['id'] ) ) {
			// add the record to the db
			$show['file_location'] 	= $episode;
			$file 					= basename( $episode );

			$insert = $conn->exec( "INSERT INTO `vod_tv_files` 
		        (`server_id`,`vod_id`,`file_location`,`title`,`match`,`preg_match`)
		        VALUE
		        (
			        '".$server_id."',
			        '".$vod_tv_id."',
			        '".addslashes( $show['file_location'] )."',
			        '".addslashes( $file )."',
			        'no',
			        '0'
			    )" );

			$data['status'] 	= 'success';
			$data['meta']		= $show;
		} else {
			$data['status'] 	= 'error';
			$data['message'] 	= 'server not found';
		}
	} else {
		$data['status'] 	= 'success';
		$data['message'] 	= 'episode already exists';
	}

	json_output( $data );
}

function channel_info()
{
	global $conn, $global_settings, $geoip, $geoisp;

	$output 		= array();

	$server_uuid 	= get( 'server_uuid' );

	$channel_id 	= get( 'channel_id' );

	// $server_uuid = $_GET['server_uuid'];

	if( empty( $server_uuid ) ) {
		die( 'missing server_uuid' );
	}

	header( "Content-Type:application/json; charset=utf-8" );

	// get server data
	$query = $conn->query( "SELECT * FROM `servers` WHERE `uuid` = '".$server_uuid."' " );
	$server = $query->fetch( PDO::FETCH_ASSOC );

	$output['server'] = $server;
	
	// get channel data
	$query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$channel_id."' " );
	$output['channel'] = $query->fetch( PDO::FETCH_ASSOC );

	// get channel sources
	$query = $conn->query("SELECT `source` FROM `channels_sources` WHERE `channel_id` = '".$channel_id."' ");
	$output['channel']['sources'] = $query->fetchAll( PDO::FETCH_ASSOC );

	// get transcoding profiles
	$query = $conn->query( "SELECT * FROM `transcoding_profiles` WHERE `id` = '".$output['channel']['transcoding_profile_id']."' " );
	$output['transcoding_profile'] = $query->fetch( PDO::FETCH_ASSOC );
	$output['transcoding_profile'] = json_decode( $output['transcoding_profile']['data'], true );
						
	json_output( $output );
}
