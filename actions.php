<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('UTC' );

session_start();

include( "inc/db.php" );
include( "inc/global_vars.php" );
include( "inc/functions.php" );

// security check
if( empty( $_SESSION['account']['id'] ) ) {
    // status_message('danger', 'Login Session Timeout' );
    go( 'index.php?c=session_timeout' );
}

$account_details = account_details( $_SESSION['account']['id'] );

$a = get( 'a' );

switch ($a)
{
	// test
	case "test":
		test();
		break;

	// prepare_to_deploy
	case "prepare_to_deploy":
		prepare_to_deploy();
		break;

	// add_playlist_item_to_live_channels
	case "add_playlist_item_to_live_channels":
		add_playlist_item_to_live_channels();
		break;

	// http_proxy
	case "http_proxy":
		http_proxy();
		break;

	// banned ips
	case "banned_ip_add":
		banned_ip_add();
		break;

	case "banned_ip_delete":
		banned_ip_delete();
		break;

	// banned isps
	case "banned_isp_add":
		banned_isp_add();
		break;

	case "banned_isp_delete":
		banned_isp_delete();
		break;


	// update_settings
	case "update_settings":
		update_settings();
		break;

	case "accept_terms":
		accept_terms();
		break;


	// server functions
	case "ajax_servers":
		ajax_servers();
		break;

	case "ajax_server":
		ajax_server();
		break;

	case "server_add":
		server_add();
		break;

	case "server_update":
		server_update();
		break;

	case "server_install_progress":
		server_install_progress();
		break;

	case "server_delete":
		server_delete();
		break;


	// source functions
	case "ajax_sources_video":
		ajax_sources_video();
		break;

	case "ajax_sources_audio":
		ajax_sources_audio();
		break;
		
	case "ajax_source_video":
		ajax_source_video();
		break;

	case "ajax_source_audio":
		ajax_source_audio();
		break;

	case "source_update":
		source_update();
		break;

	case "source_stop":
		source_stop();
		break;

	case "source_start":
		source_start();
		break;

	case "source_scan":
		source_scan();
		break;


	// customer functions
	case "customer_add":
		customer_add();
		break;

	case "customer_update":
		customer_update();
		break;

	case "customer_status":
		customer_status();
		break;

	case "customer_multi_options":
		customer_multi_options();
		break;

	case "customer_delete":
		customer_delete();
		break;

	case "customer_add_credit":
		customer_add_credit();
		break;


	// mag functions
	case "customer_mag_add":
		customer_mag_add();
		break;
	
	case "customer_mag_update":
		customer_mag_update();
		break;
	
	case "customer_mag_delete":
		customer_mag_delete();
		break;


	// epg functions
	case "epg_source_add":
		epg_source_add();
		break;
	
	case "epg_source_update":
		epg_source_update();
		break;
	
	case "epg_source_delete":
		epg_source_delete();
		break;

	case "force_epg_update":
		force_epg_update();
		break;
	

	// user functions
	case "user_add":
		user_add();
		break;

	case "user_update":
		user_update();
		break;

	case "user_gallary_view_update":
		user_gallary_view_update();
		break;

	case "user_status":
		user_status();
		break;

	case "user_set_theme":
		user_set_theme();
		break;

	case "user_delete":
		user_delete();
		break;

	case "user_status":
		user_status();
		break;


	// channel functions
	case "streams_restart_all":
		streams_restart_all();
		break;

	case "channels_stop_all":
		channels_stop_all();
		break;

	case "channels_start_all":
		channels_start_all();
		break;

	case "ajax_streams_list":
		ajax_streams_list();
		break;

	case "ajax_streams_list_test":
		ajax_streams_list_test();
		break;

	case "ajax_stream":
		ajax_stream();
		break;

	case "channel_add":
		channel_add();
		break;

	case "channel_import":
		channel_import();
		break;

	case "channel_update":
		channel_update();
		break;

	case "channel_source_add":
		channel_source_add();
		break;

	case "channel_source_delete":
		channel_source_delete();
		break;

	case "channel_sources_order_update":
		channel_sources_order_update();
		break;

	case "channel_topology_profile_add":
		channel_topology_profile_add();
		break;

	case "channel_topology_profile_update":
		channel_topology_profile_update();
		break;

	case "channel_topology_profile_delete":
		channel_topology_profile_delete();
		break;

	case "channel_topology_profile_add_asset":
		channel_topology_profile_add_asset();
		break;

	case "channel_topology_profile_delete_asset":
		channel_topology_profile_delete_asset();
		break;

	case "channel_topology_add":
		channel_topology_add();
		break;

	case "channel_topology_delete":
		channel_topology_delete();
		break;

	case "channel_icon_add":
		channel_icon_add();
		break;

	case "stream_source_update":
		stream_source_update();
		break;

	case "stream_update_fingerprint":
		stream_update_fingerprint();
		break;

	case "channel_restart":
		channel_restart();
		break;

	case "channel_start":
		channel_start();
		break;

	case "channel_stop":
		channel_stop();
		break;

	case "stream_add_output":
		stream_add_output();
		break;

	case "channels_multi_options":
		channels_multi_options();
		break;

	case "channels_247_multi_options":
		channels_247_multi_options();
		break;

	case "vod_tv_multi_options":
		vod_tv_multi_options();
		break;

	case "vod_multi_options":
		vod_multi_options();
		break;

	case "cdn_stream_start":
		cdn_stream_start();
		break;

	case "cdn_stream_stop":
		cdn_stream_stop();
		break;

	case "stream_enable_format":
		stream_enable_format();
		break;

	case "stream_disable_format":
		stream_disable_format();
		break;

	case "export_m3u":
		export_m3u();
		break;

	case "import_streams":
		import_streams();
		break;

	case "inspect_m3u":
		inspect_m3u();
		break;

	case "inspect_m3u_encoded":
		inspect_m3u_encoded();
		break;

	case "inspect_remote_playlist":
		inspect_remote_playlist();
		break;

	case "channel_delete":
		channel_delete();
		break;

	case "bulk_update_sources":
		bulk_update_sources();
		break;


	// channels_247 functions
	case "channels_247_monitored_folder_add":
		channels_247_monitored_folder_add();
		break;

	case "channels_247_monitored_folder_delete":
		channels_247_monitored_folder_delete();
		break;

	case "channel_247_update":
		channel_247_update();
		break;

	case "channel_247_delete":
		channel_247_delete();
		break;

	case "channels_247_delete_all":
		channels_247_delete_all();
		break;

	case "channels_247_start_stop":
		channels_247_start_stop();
		break;

	case "channels_247_start_stop_all":
		channels_247_start_stop_all();
		break;

	case "channel_247_imdb_search":
		channel_247_imdb_search();
		break;


	// vod functions
	case "vod_monitored_folder_add":
		vod_monitored_folder_add();
		break;

	case "vod_monitored_folder_delete":
		vod_monitored_folder_delete();
		break;

	case "vod_tv_monitored_folder_add":
		vod_tv_monitored_folder_add();
		break;

	case "vod_tv_monitored_folder_delete":
		vod_tv_monitored_folder_delete();
		break;

	case "vod_add":
		vod_add();
		break;

	case "vod_update":
		vod_update();
		break;

	case "vod_imdb_search":
		vod_imdb_search();
		break;

	case "vod_tv_update":
		vod_tv_update();
		break;

	case "vod_tv_imdb_search":
		vod_tv_imdb_search();
		break;

	case "vod_tv_delete":
		vod_tv_delete();
		break;

	case "vod_delete":
		vod_delete();
		break;

	case "vod_delete_all":
		vod_delete_all();
		break;

	case "vod_tv_delete_all":
		vod_tv_delete_all();
		break;


	// channel categories
	case "channel_category_add":
		channel_category_add();
		break;

	case "channel_category_update":
		channel_category_update();
		break;

	case "channel_category_delete":
		channel_category_delete();
		break;


	// bouquets
	case "bouquet_add":
		bouquet_add();
		break;

	case "bouquet_update":
		bouquet_update();
		break;

	case "bouquet_delete":
		bouquet_delete();
		break;

	case "bouquet_content_update":
		bouquet_content_update();
		break;

	case "bouquet_streams_order_update":
		bouquet_streams_order_update();
		break;


	// transcoding profiles
	case "transcoding_profile_add":
		transcoding_profile_add();
		break;

	case "transcoding_profile_update":
		transcoding_profile_update();
		break;

	case "transcoding_profile_delete":
		transcoding_profile_delete();
		break;

	case "restart_transcoding_profile_channels":
		restart_transcoding_profile_channels();
		break;

	// misc
	case "analyse_stream":
		analyse_stream();
		break;

	case "ajax_logs":
		ajax_logs();
		break;

	case "monitored_folder_add":
		monitored_folder_add();
		break;

	case "playlist_add":
		playlist_add();
		break;

	case "playlist_delete":
		playlist_delete();
		break;

	case "fta_playlist_add":
		fta_playlist_add();
		break;

	case "playlist_inspector":
		playlist_inspector();
		break;


	// jobs
	case "job_add":
		job_add();
		break;


	// acl_rules
	case "acl_rule_add":
		acl_rule_add();
		break;

	case "acl_rule_update":
		acl_rule_update();
		break;

	case "acl_rule_delete":
		acl_rule_delete();
		break;


	// dns_add
	case "dns_add":
		dns_add();
		break;

	// dns_update
	case "dns_update":
		dns_update();
		break;

	// dns_delete
	case "dns_delete":
		dns_delete();
		break;


	// remote playlists
	case "remote_playlist_add":
		remote_playlist_add();
		break;

	case "remote_playlist_update":
		remote_playlist_update();
		break;

	case "remote_playlist_delete":
		remote_playlist_delete();
		break;


	// playlist_checker
	case "playlist_checker":
		playlist_checker();
		break;

	case "ajax_stream_checker":
		ajax_stream_checker();
		break;


	// xc_import
	case "xc_import":
		xc_import();
		break;

	case "xc_import_delete":
		xc_import_delete();
		break;

	// update my profile for the loggin in user
	case "profile_update":
		profile_update();
		break;

	// update account settings for the specified user
	case "user_settings":
		user_settings();
		break;

	// reset_account
	case "reset_account":
		reset_account();
		break;

	// get customer line
	case "ajax_customer_line":
		ajax_customer_line();
		break;

	// get customer lines
	case "ajax_customer_lines":
		ajax_customer_lines();
		break;

	// ajax_http_proxy
	case "ajax_http_proxy":
		ajax_http_proxy();
		break;

	case "license_add":
		license_add();
		break;

	case "license_delete":
		license_delete();
		break;

	case "addon_license_add":
		addon_license_add();
		break;

	case "addon_license_delete":
		addon_license_delete();
		break;
	

	// backup manager
	case "backup_now":
		backup_now();
		break;

	case "backup_restore":
		backup_restore();
		break;

	case "backup_delete":
		backup_delete();
		break;

	case "backup_download":
		backup_download();
		break;

	// rtmp management
	case "allowed_ip_add":
		allowed_ip_add();
		break;

	case "allowed_ip_delete":
		allowed_ip_delete();
		break;

	// packages
	case "package_add":
		package_add();
		break;

	case "package_update":
		package_update();
		break;

	case "package_delete":
		package_delete();
		break;

	case "package_content_update":
		package_content_update();
		break;


	// vod categories
	case "vod_category_add":
		vod_category_add();
		break;

	case "vod_category_update":
		vod_category_update();
		break;

	case "vod_category_delete":
		vod_category_delete();
		break;

	// vod tv categories
	case "vod_tv_category_add":
		vod_tv_category_add();
		break;

	case "vod_tv_category_update":
		vod_tv_category_update();
		break;

	case "vod_tv_category_delete":
		vod_tv_category_delete();
		break;


	// customer ips
	case "customer_ip_add":
		customer_ip_add();
		break;

	case "customer_ip_delete":
		customer_ip_delete();
		break;


	// grab_metadata
	case "grab_metadata":
		grab_metadata();
		break;

	case "backup_upload":
		backup_upload();
		break;

	// delete_all
	case "delete_all":
		delete_all();
		break;

	case "ajax_fta_streams":
		ajax_fta_streams();
		break;

	case "addon_install":
		addon_install();
		break;

// default		
	default:
		home();
		break;
}

function home() {
	die('access denied to function name ' . $_GET['a'] );
}

function test() {
	echo exec('whoami' );
	echo "<hr>";
	echo '<h3>$_SESSION</h3>';
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_POST</h3>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_GET</h3>';
	echo '<pre>';
	print_r($_GET);
	echo '</pre>';
	echo '<hr>';

	$json = '"{\"cmd\":\"hts       1952 28.1  2.7 3573956 445384 ?      Ssl  Mar14 878:22 /usr/bin/tvheadend -f -u hts -g video\",\"pid\":\"1952\",\"uptime\":\"878:22\",\"playlist\":\"#EXTM3U\n#EXTINF:-1 tvg-id=\"c6b36ed00191cc357390175faa9c02ce\",BT Sport 1 HD\nhttp://localhost:9981/stream/channelid/1349432262?ticket=E7311857FB5AF3096B32C7969EC43FA1D5BE4DB8&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"075a033c0feb7e38f00ac80b5398732f\",BT Sport ESPN HD\nhttp://localhost:9981/stream/channelid/1006852615?ticket=2333D14D30F12F7BE3749F2ACB9173FB96FC8D1A&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"8c6980463aa059c8d1b2eb8d3779a15f\",Eurosport 5HD\nhttp://localhost:9981/stream/channelid/1182820748?ticket=16F55C2A94DAF6B11A666E134B0AC4850422CBDC&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"96c64f32569c1016b0a345c150d96cdc\",Freesports HD\nhttp://localhost:9981/stream/channelid/844088982?ticket=B505BEAE6456C56AAB32DA0EDB24F9C74EDFE7AE&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"dd801366afb7860974c87df65568089a\",GOLD HD\nhttp://localhost:9981/stream/channelid/1712554205?ticket=702BD4F98C4E194CD63071FE6AE6C215321C312F&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"c60ccc45c80074df2201e541b3f9f672\",Liverpool FC TV\nhttp://localhost:9981/stream/channelid/1171000518?ticket=B70A5B91C67EFA7C0B13B0CF155B1B6B9DCE1A1D&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"3de40017906acfcc92055f30bbbadf63\",National GeographicHD\nhttp://localhost:9981/stream/channelid/385934397?ticket=79FDE7970DFBB44BCC5C40F00F8D29A48E063FBF&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"8a7082bf75c3899f43bf58bb10d27c96\",NHK World-Japan\nhttp://localhost:9981/stream/channelid/1065513098?ticket=E6731CBB28A727CDB05A7FE0DAC3FC1162AE0F16&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"25915bf3fc4575f4d066bb684b2d9939\",Old Channel 4HD\nhttp://localhost:9981/stream/channelid/1935380773?ticket=B53AF43FD2B9E4EDD361C98EBA6495E4AE0DBAFA&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"56735d2153c8c491b8f1c8fcada1b8cd\",Premier 1 HD\nhttp://localhost:9981/stream/channelid/559772502?ticket=E60F7E7F4E1EF6F720DE948513EDF9ED742BB314&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"e0bc8bcb250dc0106d07460a5758e314\",Sky 5* Movies HD\nhttp://localhost:9981/stream/channelid/1267449056?ticket=2233E71895378FC7F27BFB9008F8443FF67826F0&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"544a5694b9d27353cbeb00bd7ed2c0eb\",Sky Aliens HD\nhttp://localhost:9981/stream/channelid/341199444?ticket=6AEA7309F0596E2874D31C0E209FE074334A7C0C&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"cf3a9bacb0d2a94ab0ae5daf688f5b1d\",Sky Disney HD\nhttp://localhost:9981/stream/channelid/748370639?ticket=FC6B4EEFDAA713DA5AC241FC4CA321D696FBFAEB&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"665cd49dd0cd0fbe2f047630e12f4f22\",Sky One HD\nhttp://localhost:9981/stream/channelid/500456550?ticket=ED70D5526470C5D6E110860E6C93B88A31E6FF66&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"9d21d2b830da02b4c6f8becd36788478\",Sky Premiere HD\nhttp://localhost:9981/stream/channelid/953295261?ticket=435C144696B09DBA64481086DD47A6762A895FDC&profile=webtv-h264-aac-matroska\n#EXTINF:-1 tvg-id=\"dab63f70df9ef5c69ff172af785c080c\",vm0 - 682.75 - dvb-c/682.75MHz/{PMT:37}\nhttp://localhost:9981/stream/channelid/1883223770?ticket=6B9327EA5E6258642EFB3E9181974897F7AB847B&profile=webtv-h264-aac-matroska\n\",\"streams\":[{\"name\":\"BT Sport 1 HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/1349432262\"},{\"name\":\"BT Sport ESPN HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/1006852615\"},{\"name\":\"Eurosport 5HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/1182820748\"},{\"name\":\"Freesports HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/844088982\"},{\"name\":\"GOLD HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/1712554205\"},{\"name\":\"Liverpool FC TV\",\"stream_url\":\"http://localhost:9981/stream/channelid/1171000518\"},{\"name\":\"National GeographicHD\",\"stream_url\":\"http://localhost:9981/stream/channelid/385934397\"},{\"name\":\"NHK World-Japan\",\"stream_url\":\"http://localhost:9981/stream/channelid/1065513098\"},{\"name\":\"Old Channel 4HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/1935380773\"},{\"name\":\"Premier 1 HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/559772502\"},{\"name\":\"Sky 5* Movies HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/1267449056\"},{\"name\":\"Sky Aliens HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/341199444\"},{\"name\":\"Sky Disney HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/748370639\"},{\"name\":\"Sky One HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/500456550\"},{\"name\":\"Sky Premiere HD\",\"stream_url\":\"http://localhost:9981/stream/channelid/953295261\"},{\"name\":\"vm0 - 682.75 - dvb-c/682.75MHz/{PMT:37}\",\"stream_url\":\"http://localhost:9981/stream/channelid/1883223770\"}]}"';

	$data = json_decode($json, true);
	$data = json_decode($data, true);

	echo '<pre>';
	print_r($data);
}

function update_settings()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$cms_domain_name 	= post( 'cms_domain_name' );
	$cms_domain_name 	= addslashes( $cms_domain_name);
	$cms_domain_name 	= trim( $cms_domain_name);

	$cms_ip 			= post( 'cms_ip' );
	$cms_ip 			= addslashes( $cms_ip);
	$cms_ip 			= trim( $cms_ip);

	$cms_name 			= post( 'cms_name' );
	$cms_name 			= addslashes( $cms_name);
	$cms_name 			= trim( $cms_name);
	if(empty($cms_name) ) {
		$cms_name = 'Stiliam CMS';
	}

	$master_token 		= post( 'master_token' );
	$master_token 		= addslashes( $master_token);
	$master_token 		= trim( $master_token);
	$master_token 		= preg_replace( "/[^a-zA-Z0-9]+/", "", $master_token);
	if(empty($master_token) ) {
		$master_token = md5(time() );
	}

	$ministrapassword	= post( 'ministrapassword' );

	// check if the domain points to the cms ip
	if( !empty( $cms_domain_name ) ) {
		$domain_to_ip = gethostbyname( $cms_domain_name );
		if( $cms_ip != $domain_to_ip ) {
			status_message( "danger", "CMS Domain does not point to CMS IP. Changes have been discarded." );
	    	go( $_SERVER['HTTP_REFERER'] );
		}
	}

	$update = $conn->exec( "UPDATE `global_settings` SET `config_value` = '".$cms_domain_name."' 	WHERE `config_name` = 'cms_domain_name' " );
	$update = $conn->exec( "UPDATE `global_settings` SET `config_value` = '".$cms_ip."' 			WHERE `config_name` = 'cms_ip' " );
	$update = $conn->exec( "UPDATE `global_settings` SET `config_value` = '".$cms_name."' 			WHERE `config_name` = 'cms_name' " );
	$update = $conn->exec( "UPDATE `global_settings` SET `config_value` = '".$master_token."' 		WHERE `config_name` = 'master_token' " );
	$update = $conn->exec( "UPDATE `global_settings` SET `config_value` = '".$ministrapassword."' 	WHERE `config_name` = 'ministrapassword' " );

	$update = $conn->exec( "UPDATE `stalker_db`.`administrators` SET pass=MD5('$ministrapassword') WHERE `login` = 'admin' " );

	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';

    // log_add( "[".$name."] has been updated." );
    status_message( "success", "Global settings have been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function ajax_servers()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	header( "Content-Type:application/json; charset=utf-8" );

	$query = $conn->query( "SELECT * FROM `servers` WHERE `user_id` = '".$_SESSION['account']['id']."' " );
	if( $query !== FALSE) {
		$headends = $query->fetchAll( PDO::FETCH_ASSOC );

		$count = 0;

		foreach($headends as $headend) {
			$output[$count] = $headend;
			
			// convert seconds to human readable format
			$output[$count]['uptime'] = uptime($headend['uptime'] );

			// convert bandwidth to mbit
			$output[$count]['bandwidth_down'] 		= number_format($headend['bandwidth_down'] / 125, 0);
			$output[$count]['bandwidth_up'] 		= number_format($headend['bandwidth_up'] / 125, 0);

			// get source details
			$query = $conn->query( "SELECT * FROM `capture_devices` WHERE `server_id` = '".$headend['id']."' ORDER BY `name` ASC" );
			if( $query !== FALSE) {
				$output[$count]['sources'] = $query->fetchAll( PDO::FETCH_ASSOC );
				$output[$count]['total_sources'] = count($output[$count]['sources'] );
			}else{
				$output[$count]['total_sources'] = 0;
			}

			$count++;
		}

		$json = json_encode($output);

		echo $json;
	}
}

function ajax_server()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	header( "Content-Type:application/json; charset=utf-8" );

	$server_id = get( 'server_id' );

	$output = array();

	$query = $conn->query( "SELECT * FROM `servers` WHERE `id` = '".$server_id."' " );
	if( $query !== FALSE) {
		$headends = $query->fetchAll( PDO::FETCH_ASSOC );

		$count = 0;

		foreach($headends as $headend) {
			$output[$count] = $headend;
			
			// convert seconds to human readable format
			$output[$count]['uptime'] = uptime($headend['uptime'] );

			// get source details
			$query = $conn->query( "SELECT * FROM `capture_devices` WHERE `server_id` = '".$headend['id']."' ORDER BY `video_device` ASC" );
			if( $query !== FALSE) {
				$output[$count]['sources'] = $query->fetchAll( PDO::FETCH_ASSOC );
				$output[$count]['total_sources'] = count($output[$count]['sources'] );
			}else{
				$output[$count]['total_sources'] = 0;
			}

			$output[$count]['nginx_stats'] = json_decode($headend['nginx_stats'], true);

			$output[$count]['astra_config_file'] = json_decode($headend['astra_config_file'], true);

			$output[$count]['gpu_stats'] = json_decode($headend['gpu_stats'], true);

			if(file_exists($output[$count]['mumudvb_config_file']) ) {
				$output[$count]['mumudvb_config_file'] = json_decode(file_get_contents($output[$count]['mumudvb_config_file']), true);
			}

			if(file_exists($output[$count]['tvheadend_config_file']) ) {
				$output[$count]['tvheadend_config_file'] = json_decode(file_get_contents($output[$count]['tvheadend_config_file']), true);
			}

			$count++;
		}

		$json = json_encode($output);

		echo $json;
	}
}

function server_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$uuid 					= md5( time() );

	$name 					= post( 'name' );
	$type 					= post( 'server_type' );

	if(empty($name) ) {
		$name = 'Server';
	}

	$insert = $conn->exec( "INSERT INTO `servers` 
        (`user_id`,`uuid`,`name`,`type`,`http_stream_port`)
        VALUE
        ('".$_SESSION['account']['id']."', '".$uuid."','".$name."','".$type."','".$global_settings['cms_port']."')" );
    
    $server_id = $conn->lastInsertId();

    log_add( 'server_add', 'Server: '.$server_id.' / "'.$name.'" was deployed.' );

	status_message( "success", "Server has been deployed." );
	go( 'dashboard.php?c=server&id='.$server_id.'&showcode=true' );
}

function server_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	// sanity check
	if( $_SESSION['account']['type'] == 'admin' ) {
		$server_id 			= post( 'id' );
		$name 				= post( 'name' );
		$http_stream_port	= post( 'http_stream_port' );
		$public_hostname	= post( 'public_hostname' );
		$connection_speed	= post( 'connection_speed' );
		$notes				= post( 'notes' );

		$update = $conn->exec( "UPDATE `servers` SET `name` = '".$name."' 								WHERE `id` = '".$server_id."' " );
		$update = $conn->exec( "UPDATE `servers` SET `http_stream_port` = '".$http_stream_port."' 		WHERE `id` = '".$server_id."' " );
		$update = $conn->exec( "UPDATE `servers` SET `public_hostname` = '".$public_hostname."' 		WHERE `id` = '".$server_id."' " );
		$update = $conn->exec( "UPDATE `servers` SET `connection_speed` = '".$connection_speed."' 		WHERE `id` = '".$server_id."' " );
		$update = $conn->exec( "UPDATE `servers` SET `notes` = '".$notes."' 							WHERE `id` = '".$server_id."' " );

		log_add( 'server_update', 'Server: '.$server_id.' / '.$name.' was updated.' );

	    status_message( "success", "Server has been updated." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function server_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	// sanity check
	if( $_SESSION['account']['type'] == 'admin' ) {
		$server_id = get( 'id' );

		$server = server_details( $server_id );
		
		// define tables to delete from
		$tables = array('servers_logs','jobs','channels_247','channels_247_files','channels_247_monitored_folders','customers_connection_logs','vod','vod_files','vod_monitored_folders','vod_tv','vod_tv_files','vod_tv_monitored_folders' );

		// loop through working tables
		foreach ($tables as $table) {
			$query = $conn->query( "DELETE FROM `".$table."` WHERE `server_id` = '".$server_id."' " );
		}

		// delete primary record
		$delete = $conn->query( "DELETE FROM `servers` WHERE `id` = '".$server_id."' " );

		// log and wrap up
		// log_add( "Server Deleted:" );
    	status_message( "success", "Server has been deleted." );
	
    	log_add( 'server_delete', 'Server: '.$server_id.' / "'.$server['name'].'" was deleted.' );
	}

	go( 'dashboard.php?c=servers' );
}

function ajax_sources_audio()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$server_id = $_GET['server_id'];

	header( "Content-Type:application/json; charset=utf-8" );

	$query = $conn->query( "SELECT * FROM `capture_devices_audio` WHERE `server_id` = '".$server_id."' " );
	if( $query !== FALSE) {
		$sources = $query->fetchAll( PDO::FETCH_ASSOC );

		json_output($sources);
	}
}

function ajax_source_video()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$source_id = $_GET['source_id'];

	header( "Content-Type:application/json; charset=utf-8" );

	$query = $conn->query( "SELECT * FROM `capture_devices` WHERE `id` = '".$source_id."' " );
	if( $query !== FALSE) {
		$sources = $query->fetchAll( PDO::FETCH_ASSOC );

		json_output($sources);
	}
}

function ajax_streams_list()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$user_id = $_SESSION['account']['id'];

	header( "Content-Type:application/json; charset=utf-8" );

	function find_outputs($array, $key, $value) {
	    $results = array();

	    if(is_array($array) ) {
	        if (isset( $array[$key]) && $array[$key] == $value) {
	            $results[] = $array;
	        }

	        foreach($array as $subarray) {
	            $results = array_merge($results, find_outputs($subarray, $key, $value) );
	        }
	    }

	    return $results;
	}

	// get headend info
	$query = $conn->query( "SELECT `id`,`name`,`wan_ip_address`,`status` FROM `servers` WHERE `user_id` = '".$user_id."' " );
	$headends = $query->fetchAll( PDO::FETCH_ASSOC );

	// get stream categories
	$query = $conn->query( "SELECT `id`,`name` FROM `stream_categories` WHERE `user_id` = '".$user_id."' " );
	$categories = $query->fetchAll( PDO::FETCH_ASSOC );

	// handle source_domain filter

	if(empty($_GET['source_domain']) ) {
		$source_domain_filter = '';
	}else{
		$source_domain_filter = "AND `source` LIKE '%".$_GET['source_domain']."%' ";
	}

	// get streams for this user
	if(empty($_GET['server_id']) || $_GET['server_id'] == 0) {
		$query = $conn->query( "SELECT `id`,`status`,`ondemand`,`enable`,`server_id`,`source`,`logo`,`name`,`stream_uptime`,`category_id`,`probe_bitrate`,`probe_video_codec`,`probe_audio_codec`,`probe_aspect_ratio`,`fps`,`speed`,`source_type`,`direct` FROM `streams` WHERE `user_id` = '".$user_id."' AND `stream_type` = 'input' " . $source_domain_filter);
		$streams_in = $query->fetchAll( PDO::FETCH_ASSOC );

		$query = $conn->query( "SELECT `id`,`status`,`ondemand`,`enable`,`server_id`,`source_stream_id`,`source_server_id`,`name`,`stream_uptime`,`fps`,`speed`,`probe_bitrate`,`probe_video_codec`,`probe_audio_codec` FROM `streams` WHERE `user_id` = '".$user_id."' AND `stream_type` = 'output' " . $source_domain_filter);
		$streams_out = $query->fetchAll( PDO::FETCH_ASSOC );
	}else{
		$query = $conn->query( "SELECT `id`,`status`,`ondemand`,`enable`,`server_id`,`source`,`logo`,`name`,`stream_uptime`,`category_id`,`probe_bitrate`,`probe_video_codec`,`probe_audio_codec`,`probe_aspect_ratio`,`fps`,`speed`,`source_type`,`direct` FROM `streams` WHERE `user_id` = '".$user_id."' AND `server_id` = '".$_GET['server_id']."' AND `stream_type` = 'input' " . $source_domain_filter);
		$streams_in = $query->fetchAll( PDO::FETCH_ASSOC );

		$query = $conn->query( "SELECT `id`,`status`,`ondemand`,`enable`,`server_id`,`source_stream_id`,`source_server_id`,`name`,`stream_uptime`,`fps`,`speed`,`probe_bitrate`,`probe_video_codec`,`probe_audio_codec` FROM `streams` WHERE `user_id` = '".$user_id."' AND `server_id` = '".$_GET['server_id']."' AND `stream_type` = 'output' " . $source_domain_filter);
		$streams_out = $query->fetchAll( PDO::FETCH_ASSOC );
	}

	if(get( 'dev') == 'yes' ) {
		$dev['query_1'] = $query;
	}

	if( $query !== FALSE) {
		$count = 0;

		foreach($streams_in as $stream) {
			$output[$count] 					= $stream;

			$output[$count]['checkbox']			= '<center><input type="checkbox" class="chk" id="checkbox_'.$stream['id'].'" name="stream_ids[]" value="'.$stream['id'].'" onclick="multi_options();"></center>';

			if(empty($stream['logo']) ) {
				$output[$count]['logo'] 		= '';
			}else{
				// $output[$count]['logo'] 		= '<center><img src="'.$stream['logo'].'" height="25px" alt="'.stripslashes($stream['name']).'"></center>';
				$output[$count]['logo'] 		= '';
			}

			$output[$count]['name'] 			= stripslashes($stream['name'] );

			// get headend data
			foreach($headends as $headend) {
				if( $headend['id'] == $stream['server_id']) {
					$output[$count]['server_name']			= stripslashes($headend['name'] );
					break;
				}
			}

			// convert seconds to human readable time
			if(empty($stream['stream_uptime']) ) {
				$output[$count]['stream_uptime'] 			= '00:00';
			}else{
				$output[$count]['stream_uptime'] 			= $stream['stream_uptime'];
			}

			$time_shift = time() - 20;

			$output[$count]['category_name'] = '';
			if(is_array($categories) ) {
				foreach($categories as $category) {
					if( $category['id'] == $stream['category_id']) {
						$output[$count]['category_name'] = $category['name'];
						break;
					}
				}
			}else{
				$output[$count]['category_name'] = '';
			}

			// $output[$count]['watermark_type']		= ucfirst($stream['watermark_type'] );
			// $output[$count]['fingerprint']			= ucfirst($stream['fingerprint'] );
			$output[$count]['watermark_type']		= '';
			$output[$count]['fingerprint']			= '';

			if( $stream['source_type'] == 'direct' ) {
				$output[$count]['source_type']	= '<center><i class="fas fa-tv" title="Direct Source"><span class="hidden">Direct</span></i></center>';
			}elseif( $stream['source_type'] == 'restream' ) {
				$output[$count]['source_type']	= '<center><i class="fas fa-sitemap" title="Restream Source"><span class="hidden">Restream</span></i></center>';
			}elseif( $stream['source_type'] == 'cdn' ) {
				$output[$count]['source_type']	= '<center><i class="fas fa-server" title="CDN Source"><span class="hidden">CDN</span></i></center>';
			}

			if( $stream['direct'] == 'yes' ) {
				$output[$count]['source_type']	= '<center><i class="fas fa-share" title="Direct to Source"><span class="hidden">Direct</span></i></center>';
			}

			// set some visual array items
			if( $stream['status'] == 'online') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-green full-width">Online</small>';
		    }elseif( $headend['status'] == 'offline' && $stream['status'] == 'offline') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-red full-width">Offline</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'offline' && $stream['ondemand'] == 'no') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-orange full-width">Starting</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'offline' && $stream['ondemand'] == 'yes') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-blue full-width">OnDemand</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'source_offline') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-purple full-width">Source Offline</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }elseif( $stream['enable'] == 'no' && $stream['status'] == 'offline') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-red full-width">Stopped</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }elseif( $stream['status'] == 'analysing') {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-orange full-width">Analysing</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }else{
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-blue full-width">UNKNOWN</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }

		    if( $output[$count]['direct'] == 'yes' ) {
		    	$output[$count]['visual_stream_status'] = '<small class="label bg-maroon full-width">Direct to Source</small>';
		    	$output[$count]['fps']					= '';
		    	$output[$count]['stream_uptime']		= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['speed']				= '';
		    	$output[$count]['probe_bitrate']		= '';
		    	$output[$count]['probe_video_codec']	= '';
		    	$output[$count]['probe_audio_codec']	= '';
		    	$output[$count]['probe_aspect_ratio']	= '';
		    }

		    $output[$count]['visual_source_stream_start'] 	= '';
		    $output[$count]['visual_source_stream_stop']	= '';
		    $output[$count]['visual_source_stream_restart']	= '';

		    if( $stream['enable'] == 'no' ) {
				$output[$count]['visual_source_stream_start']	= '
				<a title="Start Stream" class="btn btn-success btn-xs btn-flat" href="actions.php?a=stream_start&stream_id='.$stream['id'].'">Start</i></a>';
		    }elseif( $stream['enable'] == 'yes' && $stream['ondemand'] == 'yes' ) {
		    	
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'offline' ) {
		    	$output[$count]['visual_source_stream_start']	= '
				<a title="Start Stream" class="btn btn-success btn-xs btn-flat" href="actions.php?a=stream_start&stream_id='.$stream['id'].'">Start</i></a>';

		    	$output[$count]['visual_source_stream_stop'] 	= '
		    	<a title="Stop Stream" class="btn btn-danger btn-xs btn-flat" onclick="return confirm(\'Please allow up to 60 seconds for stream to stop.\')" href="actions.php?a=stream_stop&stream_id='.$stream['id'].'">Stop</a>';

		    	$output[$count]['visual_source_stream_restart'] = '
		    	<a title="Restart Stream" class="btn btn-warning btn-xs btn-flat" href="actions.php?a=stream_restart&stream_id='.$stream['id'].'">Restart</i></a>';
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'source_offline' ) {
		    	$output[$count]['visual_source_stream_stop'] 	= '
		    	<a title="Stop Stream" class="btn btn-danger btn-xs btn-flat" onclick="return confirm(\'Please allow up to 60 seconds for stream to stop.\')" href="actions.php?a=stream_stop&stream_id='.$stream['id'].'">Stop</a>';

		    	$output[$count]['visual_source_stream_restart'] = '
		    	<a title="Restart Stream" class="btn btn-warning btn-xs btn-flat" href="actions.php?a=stream_restart&stream_id='.$stream['id'].'">Restart</i></a>';
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'online' ) {
		    	$output[$count]['visual_source_stream_stop'] 	= '
		    	<a title="Stop Stream" class="btn btn-danger btn-xs btn-flat" onclick="return confirm(\'Please allow up to 60 seconds for stream to stop.\')" href="actions.php?a=stream_stop&stream_id='.$stream['id'].'">Stop</a>';

		    	$output[$count]['visual_source_stream_restart'] = '
		    	<a title="Restart Stream" class="btn btn-warning btn-xs btn-flat" href="actions.php?a=stream_restart&stream_id='.$stream['id'].'">Restart</i></a>';
		    }elseif( $stream['enable'] == 'yes' && $stream['status'] == 'analysing' ) {
		    	$output[$count]['visual_source_stream_stop'] 	= '
		    	<a title="Stop Stream" class="btn btn-danger btn-xs btn-flat" onclick="return confirm(\'Please allow up to 60 seconds for stream to stop.\')" href="actions.php?a=stream_stop&stream_id='.$stream['id'].'">Stop</a>';

		    }

		    if( $headend['status'] == 'offline' ) {
		    	// $output[$count]['visual_source_stream_start'] 	= '';
		    	$output[$count]['visual_source_stream_stop']	= '';
		    	$output[$count]['visual_source_stream_restart']	= '';
		    }

		    if( $stream['direct'] == 'yes' ) {
		    	$output[$count]['visual_source_stream_start'] 	= '';
		    	$output[$count]['visual_source_stream_stop']	= '';
		    	$output[$count]['visual_source_stream_restart']	= '';
		    }

		    $output[$count]['visual_source_stream_edit'] ='<a title="Edit Stream" class="btn btn-info btn-xs btn-flat" href="dashboard.php?c=stream&stream_id='.$stream['id'].'">Edit</a>';
		    $output[$count]['visual_source_stream_delete'] ='<a title="Delete Stream" class="btn btn-danger btn-xs btn-flat" href="actions.php?a=stream_delete&stream_id='.$stream['id'].'" onclick="return confirm(\'Are you sure?\')">Delete</a>';

		    // convert bits to megabit for bitrate
		    if( $stream['status'] == 'online' ) {
		    	if(is_numeric($stream['probe_bitrate']) ) {
		    		$output[$count]['bitrate'] 			= number_format(($stream['probe_bitrate'] / 1e+6), 2).' Mbit';
		    	}else{
		    		$output[$count]['bitrate'] 			= '0 Mbit';
		    	}
		    	$output[$count]['probe_video_codec'] 	= strtoupper( $stream['probe_video_codec'] );
		    	$output[$count]['probe_audio_codec'] 	= strtoupper( $stream['probe_audio_codec'] );
		    }else{
		    	$output[$count]['bitrate'] 				= '';
		    	$output[$count]['probe_video_codec'] 	= '';
		    	$output[$count]['probe_audio_codec'] 	= '';
		    }

		    if( $stream['direct'] == 'yes' ) {
		    	$output[$count]['bitrate'] 				= '';
		    	$output[$count]['probe_video_codec'] 	= '';
		    	$output[$count]['probe_audio_codec'] 	= '';
		    }

			$output[$count]['output_streams'] 			= '';
			$output_stream['output_streams'] 			= '';

			// echo "Found " . array_search( "#0000cc", $data);

			$output_streams = find_outputs($streams_out, 'source_stream_id', $stream['id'] );
			
			if(is_array($output_streams) ) {
				// number of output streams
				$output[$count]['total_output_streams'] 				= count($output_streams);
				$output[$count]['total_outputs']						= $output[$count]['total_output_streams'];

				foreach($output_streams as $output_stream) {
					$output[$count]['output_streams'] .= '<tr>';

					// get headend data
					foreach($headends as $headend) {
						if( $headend['id'] == $output_stream['server_id']) {
							$output_stream['server_name']				= stripslashes($headend['name'] );
							break;
						}
					}

					if(empty($output_stream['server_name']) ) {
						$output_stream['server_name'] = 'Main Server';
					}

					$output_stream['visual_output_stream_status'] 		= '';
					$output_stream['web_player']						= '';
					if( $output_stream['status'] == 'online' ) {
						$output_stream['web_player']					= '<button title="WebPlayer" type="button" class="btn btn-primary btn-flat btn-xs" data-toggle="modal" data-target="#web_player" onclick="new_web_player_iframe_source('.$output_stream['id'].')"><i class="fa fa-tv" aria-hidden="true"></i></button>';
						$output_stream['visual_output_stream_status'] 	= '<small class="label bg-green full-width">Online</small>';
					}elseif( $headend['status'] == 'offline' && $output_stream['status'] == 'offline') {
				    	$output_stream['visual_output_stream_status'] 	= '<small class="label bg-red full-width">Offline</small>';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';
				    }elseif( $output_stream['enable'] == 'yes' && $output_stream['status'] == 'offline' && $stream['ondemand'] == 'no') {
				    	$output_stream['visual_output_stream_status'] 	= '<small class="label bg-orange full-width">Restarting</small>';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';
				    }elseif( $output_stream['enable'] == 'yes' && $output_stream['status'] == 'offline' && $stream['ondemand'] == 'yes' ) {
				    	$output_stream['visual_output_stream_status'] 	= '<small class="label bg-blue full-width">OnDemand</small>';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';
				    }elseif( $output_stream['enable'] == 'no' && $output_stream['status'] == 'offline') {
				    	$output_stream['visual_output_stream_status'] 	= '<small class="label bg-red full-width">Stopped</small>';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';
				    }elseif( $output_stream['status'] == 'analysing') {
				    	$output_stream['visual_output_stream_status'] 	= '<small class="label bg-orange full-width">Analysing</small>';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';
				    }else{
				    	$output_stream['visual_output_stream_status'] 	= '<small class="label bg-blue full-width">UNKNOWN</small>';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';
				    }

				    if( $stream['direct'] == 'yes' ) {
				    	$output_stream['visual_output_stream_status'] 	= '';
				    	$output_stream['fps']							= '';
				    	$output_stream['stream_uptime']					= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['speed']							= '';
				    	$output_stream['probe_bitrate']					= '';
				    	$output_stream['probe_video_codec']				= '';
				    	$output_stream['probe_audio_codec']				= '';
				    	$output_stream['probe_aspect_ratio']			= '';

				    	$output_stream['visual_output_stream_start'] 	= '';
				    	$output_stream['visual_output_stream_stop']		= '';
				    	$output_stream['visual_output_stream_restart']	= '';

				    	$output_stream['visual_output_stream_edit']		= '';
				    	$output_stream['visual_output_stream_delete']	= '';

				    	$output[$count]['output_streams'] .= '<td colspan="15">This is a Direct to source configured stream. All output options have been disabled.</td>';

						$output[$count]['output_streams'] .= '</tr>';

				    }else{

					    $output_stream['visual_output_stream_start'] 	= '';
					    $output_stream['visual_output_stream_stop']		= '';
					    $output_stream['visual_output_stream_restart']	= '';

					    $output_stream['visual_output_stream_edit'] ='<a title="Edit Stream" class="btn btn-info btn-xs btn-flat" href="dashboard.php?c=stream&stream_id='.$output_stream['id'].'">Edit</a>';
					    $output_stream['visual_output_stream_delete'] ='<a title="Delete Stream" class="btn btn-danger btn-xs btn-flat" href="actions.php?a=stream_delete&stream_id='.$output_stream['id'].'" onclick="return confirm(\'Are you sure?\')">Delete</a>';

					    $output[$count]['output_streams'] .= '<td width="1px" style="white-space: nowrap;"><strong>Status:</strong></td>';
						$output[$count]['output_streams'] .= '<td width="1px">'.$output_stream['visual_output_stream_status'].'</td>';

						$output[$count]['output_streams'] .= '<td width="1px" style="white-space: nowrap;"><strong>Name:</strong></td>';
						$output[$count]['output_streams'] .= '<td>'.stripslashes($output_stream['name']).'</td>';

						$output[$count]['output_streams'] .= '<td></td>';
						$output[$count]['output_streams'] .= '<td></td>';

						if( $output_stream['status'] == 'online' ) {
							// get online clients for this stream
							$time_shift = time() - 60;
							$query = $conn->query( "SELECT `id` FROM `streams_connection_logs` WHERE `stream_id` = '".$output_stream['id']."' AND `timestamp` > '".$time_shift."' GROUP BY 'client_ip' " );
							$output_stream['online_clients'] = $query->fetchAll( PDO::FETCH_ASSOC );
							$output_stream['total_online_clients'] = count($output_stream['online_clients'] );
							// $output_stream['total_online_clients'] = 0;

							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td width="1px" style="white-space: nowrap;"><strong>FPS:</strong></td>';
							$output[$count]['output_streams'] .= '<td width="1px">'.(empty($output_stream['fps'])?$stream['fps']:stripslashes($output_stream['fps']) ).'</td>';
							$output[$count]['output_streams'] .= '<td width="1px" style="white-space: nowrap;"><strong>Bitrate:</strong></td>';
							if(is_numeric($output_stream['probe_bitrate']) ) {
								$output[$count]['output_streams'] .= '<td>'.number_format(($output_stream['probe_bitrate'] / 1e+6), 2).' Mbit</td>';
							}else{
								$output[$count]['output_streams'] .= '<td>N/A</td>';
							}
							$output[$count]['output_streams'] .= '<td width="1px" style="white-space: nowrap;"><strong>Conn:</strong></td>';
							$output[$count]['output_streams'] .= '<td>'.$output_stream['total_online_clients'].'</td>';
						}else{
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
							$output[$count]['output_streams'] .= '<td></td>';
						}

						$output[$count]['output_streams'] .= '
						<td width="150px" class="text-right">'.$output_stream['web_player'].''.$output_stream['visual_output_stream_start'].''.$output_stream['visual_output_stream_stop'].''.$output_stream['visual_output_stream_restart'].''.$output_stream['visual_output_stream_edit'].''.$output_stream['visual_output_stream_delete'].'</td>';

						$output[$count]['output_streams'] .= '</tr>';
					}
				}
			}

			$count++;
		}

		// $json_out = json_encode(array_values($your_array_here) );

		// $output = array_values($output);
		// $data['data'] = $output;

		if(isset( $output) ) {
			$data['data'] = array_values($output);
		}else{
			$data['data'] = array();
		}

		if(get( 'dev') == 'yes' ) {
			$data['dev'] = $dev;
		}

		json_output( $data );
	}
}

function job_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 				= get( 'id' );
	$server 			= server_details( get( 'id' ) );
	$job 				= get( 'job' );

	if( $job == 'reboot') {
		$data['action'] = 'reboot';
		$data['command'] = '/sbin/reboot';
		// example: {"action":"kill_pid","command":"kill -9 12748"}
	}

	$insert = $conn->exec( "INSERT INTO `jobs` 
        (`server_id`,`job`)
        VALUE
        ('".$id."','".json_encode($data)."')" );
    
    if( $job == 'reboot') {
		log_add( 'server_reboot', 'Server: '.$id.' / "'.$server['name'].'" was rebooted.' );
		status_message( "success", "The server will reboot shortly." );
	}

    go( $_SERVER['HTTP_REFERER'] );
}

function ajax_logs()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	header( "Content-Type:application/json; charset=utf-8" );

	$query = $conn->query( "SELECT * FROM `logs` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `id` DESC" );
	if( $query !== FALSE) {
	    $logs = $query->fetchAll( PDO::FETCH_ASSOC );
	    $count = 0;
		foreach($logs as $log) {
			$output[$count]['added'] = date( "M, jS Y H:i:s", $log['added'] );
			$output[$count]['message'] = stripslashes($log['message'] );
			$count++;
		}
	} else {
	   $output = '';
	}

	$json = json_encode($output);

	echo $json;
}

function source_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$source_id 						= $_POST['source_id'];

	$data['source_id']				= $_POST['source_id'];
	$data['name'] 					= post( 'name' );
	$data['enable']					= addslashes( $_POST['enable'] );
	$data['video_codec'] 			= addslashes( $_POST['video_codec'] );
	$data['framerate_in'] 			= addslashes( $_POST['framerate_in'] );
	$data['framerate_out'] 			= addslashes( $_POST['framerate_out'] );
	$data['screen_resolution'] 		= addslashes( $_POST['screen_resolution'] );
	$data['audio_device'] 			= addslashes( $_POST['audio_device'] );
	$data['audio_codec'] 			= addslashes( $_POST['audio_codec'] );
	$data['audio_bitrate'] 			= addslashes( $_POST['audio_bitrate'] );
	$data['audio_sample_rate'] 		= addslashes( $_POST['audio_sample_rate'] );
	$data['bitrate'] 				= addslashes( $_POST['bitrate'] );
	$data['output_type'] 			= addslashes( $_POST['output_type'] );
	$data['rtmp_server'] 			= addslashes( $_POST['rtmp_server'] );
	$data['watermark_type'] 		= addslashes( $_POST['watermark_type'] );
	$data['watermark_image'] 		= addslashes( $_POST['watermark_image'] );
	$data['rtmp_server'] 			= addslashes( $_POST['rtmp_server'] );
	$data['rtmp_server'] 			= addslashes( $_POST['rtmp_server'] );

	foreach($data as $key => $value) {
		// echo $key . " -> " . $value . '<br>';
		$update = $conn->exec( "UPDATE `capture_devices` SET `".$key."` = '".$value."' WHERE `id` = '".$source_id."' " );
	}
	
    // log_add( "[".$_POST['video_device']."] has been updated." );
    status_message( "success", "[".$_POST['video_device']."] has been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function source_stop()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$source_id = get( 'source_id' );

	$update = $conn->exec( "UPDATE `capture_devices` SET `enable` = 'no' WHERE `id` = '".$source_id."' " );
	
    // log_add( "Streaming has been stopped." );
}

function source_start()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$source_id = get( 'source_id' );

	$update = $conn->exec( "UPDATE `capture_devices` SET `enable` = 'yes' WHERE `id` = '".$source_id."' " );
	
    // log_add( "Streaming has been started." );
}

function source_scan()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$source_id = $_GET['source_id'];

	$query = $conn->query( "SELECT * FROM `capture_devices` WHERE `id` = '".$source_id."' " );
	$source = $query->fetchAll( PDO::FETCH_ASSOC );

	// confirm this is a dvb card
	if( $source[0]['type'] == 'dvb_card') {
		// $query = $conn->query( "SELECT * FROM `servers` WHERE `id` = '".$source[0]['server_id']."' " );
		// $headend = $query->fetchAll( PDO::FETCH_ASSOC );

		$adapter_name_short = str_replace('adapter', '', $source[0]['video_device'] );

		$job['action'] = 'source_scan';
		if( $source[0]['dvb_type'] == 'dvbt') {
			$job['command'] = 'w_scan -X -a'.$adapter_name_short.' -c GB > /root/slipstream/node/config/channel_scan/'.$source[0]['video_device'].'.conf';
		}
		$job['source'] = $source[0]['video_device'];

		$job = json_encode($job);

		$insert = $conn->exec( "INSERT INTO `jobs` 
	        (`server_id`,`job`)
	        VALUE
	        ('".$source[0]['server_id']."','".$job."')" );

		// log_add( "Channel scan has been started." );
	}else{
		// log_add( "ERROR: This device is not a valid DVB card." );
	}
}

function ajax_stream()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	header( "Content-Type:application/json; charset=utf-8" );

	$stream_id = get( 'stream_id' );

	$output = array();

	$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' " );
	if( $query !== FALSE) {
		$streams = $query->fetchAll( PDO::FETCH_ASSOC );

		$count = 0;

		foreach($streams as $stream) {
			$output[$count] = $stream;
			
			// $output[$count]['output_options'] = json_decode($stream['output_options'], true);

			$count++;
		}

		$json = json_encode($output);

		echo $json;
	}
}

function channel_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 					= post( 'id' );
	$title 					= post( 'title' );
	$icon					= post( 'icon' );
	$user_agent				= post( 'user_agent' );
	$category_id			= post( 'category_id' );
	$epg_xml_id				= post( 'epg_xml_id' );
	$ffmpeg_re				= post( 'ffmpeg_re' );
	$method 				= post( 'method' );
	$ondemand 				= post( 'ondemand' );
	$cpu_gpu 				= post( 'cpu_gpu' );
	$transcoding_profile_id	= post( 'transcoding_profile_id' );

	$update = $conn->exec( "UPDATE `channels` SET `title` = '".$title."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `icon` = '".$icon."' 											WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `user_agent` = '".$user_agent."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `category_id` = '".$category_id."' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `epg_xml_id` = '".$epg_xml_id."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `ffmpeg_re` = '".$ffmpeg_re."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `method` = '".$method."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `ondemand` = '".$ondemand."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `transcoding_profile_id` = '".$transcoding_profile_id."' 		WHERE `id` = '".$id."' " );

    log_add( 'channel_update', 'Channel: '.$id.' / "'.$title.'" was updated.' );

    status_message( "success", "Live Channel changes have been saved." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_sources_order_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$channel_id 		= get( 'id' );

	$positions 			= post_array( 'position' );

	$order = 0;
	foreach( $positions as $position ) {
		$update = $conn->exec( "UPDATE `channels_sources` SET `order` = '".$order."' WHERE `id` = '".$position."' " );
		$order++;
	}
}

function channel_update_fingerprint()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$stream_id 						= addslashes( $_POST['stream_id'] );

	// fingerprint options
	$data['fingerprint']			= addslashes( $_POST['fingerprint'] );
	$data['fingerprint_type']		= addslashes( $_POST['fingerprint_type'] );
	$data['fingerprint_text']		= addslashes( $_POST['fingerprint_text'] );
	$data['fingerprint_fontsize']	= addslashes( $_POST['fingerprint_fontsize'] );
	$data['fingerprint_color']		= addslashes( $_POST['fingerprint_color'] );
	$data['fingerprint_location']	= addslashes( $_POST['fingerprint_location'] );

	$update = $conn->exec( "UPDATE `streams` SET `fingerprint` = '".$data['fingerprint']."' WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `streams` SET `fingerprint_type` = '".$data['fingerprint_type']."' WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `streams` SET `fingerprint_text` = '".$data['fingerprint_text']."' WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `streams` SET `fingerprint_color` = '".$data['fingerprint_color']."' WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `streams` SET `fingerprint_fontsize` = '".$data['fingerprint_fontsize']."' WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `streams` SET `fingerprint_location` = '".$data['fingerprint_location']."' WHERE `id` = '".$id."' " );

    // log_add( "Stream changes have been saved." );
    status_message( "success", "Stream changes have been saved." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_start()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );
	$channel = get_channel_details( $id );

	// sanity checks
	if( !isset( $channel['sources'][0] ) ) {
		status_message( "danger", "Cannot start Live Channel, missing Channel Source(s)." );
    	go( $_SERVER['HTTP_REFERER'] );
	}
	if( $channel['topology'] == 'a:0:{}' || $channel['topology'] == '' ) {
		status_message( "danger", "Cannot start Live Channel, missing Channel Topology." );
    	go( $_SERVER['HTTP_REFERER'] );
	}

	$channel_secondary_found = false;
	foreach( $channel['servers'] as $channel_server ) {
		if( $channel_server['type'] == 'secondary' ) {
			$channel_secondary_found = true;
			break;
		}
	}
	if( $channel_secondary_found == false ) {
		status_message( "danger", "Cannot start Live Channel, missing Channel Output Server." );
    	go( $_SERVER['HTTP_REFERER'] );
	}

	// start channel
	$update = $conn->exec( "UPDATE `channels` SET `stream` = 'yes' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `status` = 'starting' 				WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `fps` = '' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `speed` = '' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `pending_changes` = 'no' 				WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'analysing' 			WHERE `id` = '".$id."' " );

	$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'starting' 		WHERE `channel_id` = '".$id."' " );

    status_message( "success", "Live Channel has been started shortly." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_stop()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$update = $conn->exec( "UPDATE `channels` SET `stream` = 'no' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `status` = 'stopping' 				WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `fps` = '' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `speed` = '' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `pending_changes` = 'no' 				WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'none'	 				WHERE `id` = '".$id."' " );
	
	$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'stopping' 		WHERE `channel_id` = '".$id."' " );

	status_message( "success", "Live Channel has been stopped shortly." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_restart()
{
	global $conn, $site;

	$id = get( 'id' );

	$query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$id."' " );
	$channel = $query->fetch( PDO::FETCH_ASSOC );

	$query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".$id."' " );
	$channel['servers'] = $query->fetchAll( PDO::FETCH_ASSOC );

	foreach( $channel['servers'] as $channel_server ) {
		if( $channel_server['running_pid'] != 0) {
			$job['action'] = 'kill_pid';
			$job['command'] = 'kill -9 '.$channel_server['running_pid'];

			// add the job
			if( $channel_server['type'] == 'primary' ) {
				$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['primary_server_id']."','".json_encode($job)."')" );
			} elseif( $channel_server['type'] == 'secondary' ) {
				$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['secondary_server_id']."','".json_encode($job)."')" );
			}
		}
	}

	$update = $conn->exec( "UPDATE `channels` SET `status` = 'restarting' 				WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `fps` = '' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `speed` = '' 							WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `pending_changes` = 'no' 				WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'analysing' 			WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'restarting' 		WHERE `channel_id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '' 					WHERE `channel_id` = '".$id."' " );
		
	status_message( "success", "Live Channel will restart shortly." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$rand 				= md5( rand( 00000, 99999 ).time() );
	
	$title 				= post( 'title' );

	$icon 				= post( 'icon' );

	$category_id 		= post( 'category_id' );

	$epg_xml_id			= post( 'epg_xml_id' );

	// add control channel
	$insert = $conn->exec( "INSERT INTO `channels` 
        (`title`,`job_status`,`ffmpeg_re`,`icon`,`category_id`,`epg_xml_id`)
        VALUE
        ('".$title."',
        'none',
        'no',
        '".$icon."',
        '".$category_id."',
        '".$epg_xml_id."'
    )" );

    $channel_id = $conn->lastInsertId();
    
    log_add( 'channel_add', 'Channel: '.$channel_id.' / "'.addslashes( $title ).'" was added.' );

	status_message( "success", "Live Channel has been added." );
	go( 'dashboard.php?c=channel&id='.$channel_id );
}

function channel_import()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$category_id				= post( 'category_id' );
	$primary_server_id			= post( 'primary_server_id' );
	$secondary_server_id		= post( 'secondary_server_id' );
	$auto_start 				= post( 'auto_start' );

	$target_dir 				= "uploads/";
	$target_file 				= $target_dir . basename( $_FILES["fileToUpload"]["name"] );
	$imageFileType 				= strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
	
	if( isset( $_FILES["fileToUpload"]["tmp_name"] ) ) {
		if( move_uploaded_file( $_FILES["fileToUpload"]["tmp_name"], $target_file ) ) {
	    	// upload confirmed, process it

			// parse the playlist
	        $m3ufile = file_get_contents( $target_file );

			$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$))/';
			$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';

			preg_match_all( $re, $m3ufile, $matches );

			$i = 1;

			$items = array();

			foreach( $matches[0] as $list ) {	   
				preg_match( $re, $list, $matchList );

			   	$mediaURL = preg_replace( "/[\n\r]/", "", $matchList[3] );
			   	$mediaURL = preg_replace( '/\s+/', '', $mediaURL );

			   	$newdata =  array (
			    	//'ATTRIBUTE' => $matchList[2],
			    	'id' => $i++,
			    	'tvg-name' => $matchList[2],
			    	'media' => $mediaURL
			    );
			    
			    preg_match_all( $attributes, $list, $matches, PREG_SET_ORDER );
			    
			    foreach( $matches as $match ) {
					$newdata[$match[1]] = $match[2];
			    }

			    if( empty( $newdata['tvg-name'] ) ) {
					// $newdata['tvg-name'] = str_replace( "'", '', $matchList[2] );
			    	$temp_name = $matchList[2];
			    	$temp_name = str_replace("'", '', $temp_name );
			    	$temp_name = str_replace('"', "", $temp_name );
					$newdata['tvg-name'] = $temp_name;
				}

				if( isset( $newdata['tvg-id'] ) ) {
					if( $newdata['tvg-id'] == 'id N/A' || $newdata['tvg-id'] == 'N/A' || $newdata['tvg-id'] == ' ') {
						$newdata['tvg-id'] = '';
					}
				}

				$items[] = $newdata;
			}

			// did we find any matches
			if( is_array( $items ) && isset( $items[0] ) ) {
				foreach( $items as $item ){
					// add control channel
					$insert = $conn->exec( "INSERT INTO `channels` 
				        (`title`,`job_status`,`ffmpeg_re`,`icon`,`category_id`,`epg_xml_id`,`stream`,`status`)
				        VALUE
				        ('".$item['tvg-name']."',
				        'none',
				        'no',
				        '".$item['tvg-logo']."',
				        '".$category_id."',
				        '".$item['tvg-id']."',
				        '".$auto_start."',
				        '".( $auto_start == 'yes' ? 'starting' : 'offline' )."'
				    )" );

				    $channel_id = $conn->lastInsertId();

				    // add channel source
					$insert = $conn->exec( "INSERT INTO `channels_sources` 
				        (`channel_id`,`source`)
				        VALUE
				        ('".$channel_id."',
				        '".$item['media']."'
				    )" );

					// add channel servers
					$server = server_details( $primary_server_id );

					// add primary topology item
					$topology[0]['parent'] 		= 0;
					$topology[0]['type'] 		= 'primary';
					$topology[0]['name'] 		= 'Input Server';
					$topology[0]['title']		= $server['name'];
					$topology[0]['class'] 		= 'middle-level';
					$topology[0]['server_id'] 	= $primary_server_id;

					// add a record for stats for this stream
					$insert = $conn->exec( "INSERT IGNORE INTO `channels_servers` (`type`, `channel_id`, `primary_server_id`, `status`) VALUE ('primary', '".$channel_id."', '".$primary_server_id."', '".( $auto_start == 'yes' ? 'starting' : 'offline' )."')" );
				    
			    	// add secondary topology
			    	$secondary_server_id = post( 'secondary_server_id' );

			    	// get the server details
					$server = server_details( $secondary_server_id );

			    	// add primary topology item
					$topology[1]['parent'] 		= $primary_server_id;
					$topology[1]['type'] 		= 'secondary';
					$topology[1]['name'] 		= 'Output Server';
					$topology[1]['title']		= $server['name'];
					$topology[1]['class'] 		= 'product-dept';
					$topology[1]['server_id'] 	= $secondary_server_id;

					// add a record for stats for this stream
					$insert = $conn->exec( "INSERT IGNORE INTO `channels_servers` (`type`, `channel_id`, `primary_server_id`, `secondary_server_id`, `status`) VALUE ('secondary', '".$channel_id."', '".$primary_server_id."', '".$secondary_server_id."', '".( $auto_start == 'yes' ? 'starting' : 'offline' )."')" );

				    // save the new topology
				    $update = $conn->exec( "UPDATE `channels` SET `topology` = '".serialize( $topology )."' WHERE `id` = '".$channel_id."' " );

				    log_add( 'channel_add', 'Channel: '.$channel_id.' / "'.addslashes( $item['tvg-name'] ).'" was added.' );
				}

				status_message( "success", "Live Channel Import successful." );
			} else {
				status_message( "danger", "Live Channel Import failed, no playlist items found." );
			}
	  	} else {
	    	status_message( "danger", "Live Channel Import upload failed." );
	  	}
	} else {
		status_message( "danger", "Please select a file to import." );
	}

	status_message( "success", "Live Channel has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_source_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$channel_id 		= post( 'channel_id' );
	$source 			= post( 'source' );
	
	if( empty( $source ) ) {
		status_message( "danger", "You did not enter a channel source." );
    	go( $_SERVER['HTTP_REFERER'] );
	}

	// add channel source
	$insert = $conn->exec( "INSERT INTO `channels_sources` 
        (`channel_id`,`source`)
        VALUE
        ('".$channel_id."',
        '".$source."'
    )" );
    
	// log_add( "Stream has been added." );
	status_message( "success", "Live Channel Source has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_source_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `channels_sources` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
    status_message( "success", "Live Channel Source has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_topology_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$topology 					= array();
	$type 						= get( 'type' );
	$channel_id 				= post( 'channel_id' );
	$primary_server_id 			= post( 'primary_server_id' );

	// get the existing topology
	$sql = "
        SELECT `topology` 
        FROM `channels` 
        WHERE `id` = '".$channel_id."' 
    ";
    $query      = $conn->query($sql);
    $data    	= $query->fetch( PDO::FETCH_ASSOC );
    if( !empty( $data['topology'] ) ) {
	    $topology 	= unserialize( $data['topology'] );
	}

	// work with the incoming data
	if( $type == 'primary' ) {
		// get the server details
		$server = server_details( $primary_server_id );

		// add primary topology item
		$new_topology[0]['parent'] 		= 0;
		$new_topology[0]['type'] 		= 'primary';
		$new_topology[0]['name'] 		= 'Input Server';
		$new_topology[0]['title']		= $server['name'];
		$new_topology[0]['class'] 		= 'middle-level';
		$new_topology[0]['server_id'] 	= $primary_server_id;

		// add a record for stats for this stream
		$insert = $conn->exec( "INSERT IGNORE INTO `channels_servers` (`type`, `channel_id`, `primary_server_id`) VALUE ('primary', '".$channel_id."', '".$primary_server_id."')" );
    } elseif( $type == 'secondary' ) {
    	// add secondary topology
    	$secondary_server_id 			= post( 'secondary_server_id' );

    	// get the server details
		$server = server_details( $secondary_server_id );

    	// add primary topology item
		$new_topology[0]['parent'] 		= $primary_server_id;
		$new_topology[0]['type'] 		= 'secondary';
		$new_topology[0]['name'] 		= 'Output Server';
		$new_topology[0]['title']		= $server['name'];
		$new_topology[0]['class'] 		= 'product-dept';
		$new_topology[0]['server_id'] 	= $secondary_server_id;

		// add a record for stats for this stream
		$insert = $conn->exec( "INSERT IGNORE INTO `channels_servers` (`type`, `channel_id`, `primary_server_id`, `secondary_server_id`) VALUE ('secondary', '".$channel_id."', '".$primary_server_id."', '".$secondary_server_id."')" );
    }

    // put it all back together
    $combined = array_merge( $topology, $new_topology );

    // save the new topology
    $update = $conn->exec( "UPDATE `channels` SET `topology` = '".serialize( $combined )."' WHERE `id` = '".$channel_id."' " );

	status_message( "success", "Live Channel Topology has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_topology_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$channel_id 					= get( 'channel_id' );
	$server_id 						= get( 'server_id' );
	$type 							= get( 'type' );

	$sql = "
        SELECT `topology` 
        FROM `channels` 
        WHERE `id` = '".$channel_id."' 
    ";
    $query      = $conn->query($sql);
    $data    	= $query->fetch( PDO::FETCH_ASSOC );
    $topology 	= unserialize( $data['topology'] );

	if( $type == 'primary' ) {
		// remove secondaries
		foreach( $topology as $server ) {
			// search for the server
    		$keys = search_multi_array( $topology, $server_id, 'parent' );

    		// sanity check
    		if( isset( $keys ) ) {
    			foreach( $keys as $key => $value ) {
    				unset( $topology[$value] );
    			}
    		}
    	}

    	// remove primary
		foreach( $topology as $server ) {
			// search for the server
    		$keys = search_multi_array( $topology, $server_id, 'server_id' );

    		// sanity check
    		if( isset( $keys ) ) {
    			foreach( $keys as $key => $value ) {
    				unset( $topology[$value] );
    			}
    		}
    	}

    	// remove channels_servers entries
    	$delete = $conn->exec( "DELETE FROM `channels_servers` WHERE `type` = 'primary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."' " );
    	$delete = $conn->exec( "DELETE FROM `channels_servers` WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `primary_server_id` = '".$server_id."' " );
    } elseif( $type == 'secondary' ) {
    	// remove secondary
    	foreach( $topology as $server ) {
    		// search for the server
    		$keys = search_multi_array( $topology, $server_id, 'server_id' );

    		// sanity check
    		if( isset( $keys ) ) {
    			foreach( $keys as $key => $value ) {
    				unset( $topology[$value] );
    			}
    		}
    	}

    	// remove channels_servers entry
    	$delete = $conn->exec( "DELETE FROM `channels_servers` WHERE `type` = 'secondary' AND `channel_id` = '".$channel_id."' AND `secondary_server_id` = '".$server_id."' " );

    	// remove server_id > channel_id > customer_id entry
    	$delete = $conn->exec( "DELETE FROM `channels_customer_server_assignments` WHERE `server_id` = '".$server_id."' AND `channel_id` = '".$channel_id."' " );
    }

    // save the new topology
    $update = $conn->exec( "UPDATE `channels` SET `topology` = '".serialize( $topology )."' WHERE `id` = '".$channel_id."' " );

	status_message( "success", "Live Channel Topology has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_topology_profile_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 					= get( 'id' );

	$delete = $conn->query( "DELETE FROM `channels_topology_profiles` WHERE `id` = '".$id."' " );

	status_message( "success", "Channel Topology Profile has been deleted." );
	go( "dashboard.php?c=channel_topology_profiles" );
}

function channel_topology_profile_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 					= post( 'id' );
	$name 					= post( 'name' );

	$update = $conn->exec( "UPDATE `channels_topology_profiles` SET `name` = '".$name."' 										WHERE `id` = '".$id."' " );

    status_message( "success", "Channel Topology Profile have been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_topology_profile_add_asset()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$topology 					= array();
	$type 						= get( 'type' );
	$profile_id 				= post( 'profile_id' );
	$primary_server_id 			= post( 'primary_server_id' );

	// get the existing topology
	$sql = "
        SELECT * 
        FROM `channels_topology_profiles` 
        WHERE `id` = '".$profile_id."' 
    ";
    $query      = $conn->query($sql);
    $data    	= $query->fetch( PDO::FETCH_ASSOC );
    if( !empty( $data['data'] ) ) {
	    $topology 	= unserialize( $data['data'] );
	}

	// work with the incoming data
	if( $type == 'primary' ) {
		// get the server details
		$server = server_details( $primary_server_id );

		// add primary topology item
		$new_topology[0]['parent'] 		= 0;
		$new_topology[0]['type'] 		= 'primary';
		$new_topology[0]['name'] 		= 'Input Server';
		$new_topology[0]['title']		= $server['name'];
		$new_topology[0]['class'] 		= 'middle-level';
		$new_topology[0]['server_id'] 	= $primary_server_id;
    } elseif( $type == 'secondary' ) {
    	// add secondary topology
    	$secondary_server_id 			= post( 'secondary_server_id' );

    	// get the server details
		$server = server_details( $secondary_server_id );

    	// add primary topology item
		$new_topology[0]['parent'] 		= $primary_server_id;
		$new_topology[0]['type'] 		= 'secondary';
		$new_topology[0]['name'] 		= 'Output Server';
		$new_topology[0]['title']		= $server['name'];
		$new_topology[0]['class'] 		= 'product-dept';
		$new_topology[0]['server_id'] 	= $secondary_server_id;
    }

    // put it all back together
    $combined = array_merge( $topology, $new_topology );

    // save the new topology
    $update = $conn->exec( "UPDATE `channels_topology_profiles` SET `data` = '".serialize( $combined )."' WHERE `id` = '".$profile_id."' " );

	status_message( "success", "Channel Topology Profile has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_topology_profile_delete_asset()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$profile_id 					= get( 'profile_id' );
	$server_id 						= get( 'server_id' );
	$type 							= get( 'type' );

	$sql = "
        SELECT * 
        FROM `channels_topology_profiles` 
        WHERE `id` = '".$profile_id."' 
    ";
    $query      = $conn->query($sql);
    $data    	= $query->fetch( PDO::FETCH_ASSOC );
    $topology 	= unserialize( $data['data'] );

	if( $type == 'primary' ) {
		// remove secondaries
		foreach( $topology as $server ) {
			// search for the server
    		$keys = search_multi_array( $topology, $server_id, 'parent' );

    		// sanity check
    		if( isset( $keys ) ) {
    			foreach( $keys as $key => $value ) {
    				unset( $topology[$value] );
    			}
    		}
    	}

    	// remove primary
		foreach( $topology as $server ) {
			// search for the server
    		$keys = search_multi_array( $topology, $server_id, 'server_id' );

    		// sanity check
    		if( isset( $keys ) ) {
    			foreach( $keys as $key => $value ) {
    				unset( $topology[$value] );
    			}
    		}
    	}
    } elseif( $type == 'secondary' ) {
    	// remove secondary
    	foreach( $topology as $server ) {
    		// search for the server
    		$keys = search_multi_array( $topology, $server_id, 'server_id' );

    		// sanity check
    		if( isset( $keys ) ) {
    			foreach( $keys as $key => $value ) {
    				unset( $topology[$value] );
    			}
    		}
    	}
    }

    // save the new topology
    $update = $conn->exec( "UPDATE `channels_topology_profiles` SET `data` = '".serialize( $topology )."' WHERE `id` = '".$profile_id."' " );

	status_message( "success", "Channel Topology Profile has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_icon_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$path = "content/channel_icons/";
    
    foreach( $_FILES as $key ) {
        if( $key['error'] == UPLOAD_ERR_OK ){
            $name = $key['name'];
            $temp = $key['tmp_name'];
            $size = ($key['size'] / 1000)."KB";
            move_uploaded_file( $temp, $path . $name );
            echo '
                <div>
                    <h12><strong>File Name:</strong> '.$name.' - <font color="green">Uploaded</font></h2><br />
                </div>
                ';

            // insert into db
            $insert = $conn->exec( "INSERT IGNORE INTO `channels_icons` (`filename`) VALUE ('".$name."')" );
        }else{
            echo $key['error'];
        }
    }
}

function import_channels()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	debug( $_POST );
	die();

	$server_id			= addslashes( $_POST['server'] );
	if( $server_id == 0) {
		$server_id = 1;
	}

	$category_id		= addslashes( $_POST['category_id'] );

	$ffmpeg_re			= $_POST['ffmpeg_re'];

	// handle file upload
	exec( "sudo mkdir -p /var/www/html/m3u_uploads/" );
	exec( "sudo chmod 777 /var/www/html/m3u_uploads/" );

	$target_dir = "m3u_uploads/";
	$target_file = $target_dir . $_SESSION['account']['id'].'-'.str_replace(' ', '_', basename($_FILES["m3u_file"]["name"]) );
	$uploadOk = 1;
	$file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION) );
	
	// check if file already exists
	/*
	if (file_exists($target_file) ) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	*/

	// check file size
	/*
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	*/

	// allow certain file formats
	if( $file_type != "m3u" && $file_type != "m3u8" && $file_type != "txt" ) {
		status_message( "danger", "Sorry, only .m3u and .m3u8 files are allowed." );
		go( $_SERVER['HTTP_REFERER'] );
	    $uploadOk = 0;
	}

	// check if $uploadOk is set to 0 by an error
	if( $uploadOk == 0) {
	    status_message( "danger", "Sorry, there was an error uploading your file." );
		go( $_SERVER['HTTP_REFERER'] );
	// if everything is ok, try to upload file
	}else{
	    if(move_uploaded_file($_FILES["m3u_file"]["tmp_name"], $target_file) ) {
	        // echo "The file ". $_SESSION['account']['id'].'-'.basename( $_FILES["m3u_file"]["name"]). " has been uploaded.";
	    }else{
	    	status_message( "danger", "Sorry, there was an error uploading your file." );
			go( $_SERVER['HTTP_REFERER'] );
	    }
	}

  	// read the uploaded m3u into an array
  	error_log( "----- M3U URL" );
  	error_log( "http://".$global_settings['cms_access_url']."/actions.php?a=inspect_m3u&url=http://".$global_settings['cms_access_url']."/m3u_uploads/".$_SESSION['account']['id'].'-'.str_replace(' ', '_',basename( $_FILES["m3u_file"]["name"]) ));
  	error_log( "----- M3U URL" );
  	$streams_raw 		= @file_get_contents( "http://".$global_settings['cms_access_url']."/actions.php?a=inspect_m3u&url=http://".$global_settings['cms_access_url']."/m3u_uploads/".$_SESSION['account']['id'].'-'.str_replace(' ', '_',basename( $_FILES["m3u_file"]["name"]) ));
  	$streams 			= json_decode($streams_raw, true);
	if(is_array($streams) ) {
		foreach($streams as $stream) {
			$rand 				= md5(rand(00000,99999).time() );
			
			$name 				= addslashes( $stream['title'] );
			$name 				= str_replace(array('"', "'",';'), '', $name);
			$name 				= trim( $name);

			$source 			= addslashes( $stream['url'] );
			$source 			= trim( $source);
			$source 			= str_replace(' ', '', $source);

			if(!isset( $stream['tvlogo']) || empty($stream['tvlogo']) ) {
				$stream['tvlogo'] = '';
			}

			$insert = $conn->exec( "INSERT INTO `streams` 
		        (`user_id`,`server_id`,`stream_type`,`name`,`enable`,`source`,`cpu_gpu`,`job_status`,`ffmpeg_re`,`logo`,`category_id`)
		        VALUE
		        ('".$_SESSION['account']['id']."',
		        '".$server_id."',
		        'input',
		        '".$name."',
		        'no',
		        '".$source."',
		        'cpu',
		        'analysing',
		        '".$ffmpeg_re."',
		        '".$stream['tvlogo']."',
		        '".$category_id."'
		    )" );

		    $stream_id = $conn->lastInsertId();

		    // add output stream
		    $insert = $conn->exec( "INSERT INTO `streams` 
		        (`user_id`,`enable`,`server_id`,`stream_type`,`name`,`source_server_id`,`source_stream_id`,`logo`,`category_id`)
		        VALUE
		        ('".$_SESSION['account']['id']."',
		        'yes',
		        '".$server_id."',
		        'output',
		        '".$name."',
		        '".$server_id."',
		        '".$stream_id."',
		        '".$stream['tvlogo']."',
		        '".$category_id."'
		    )" );
	    }

	    // remove upload file
	    // shell_exec( "rm -rf " . $target_file);

		// log_add( "Streams has been imported." );
		status_message( "success", "All streams have been imported." );
	}else{
		status_message( "danger", "No streams found in the uploaded file or something else went wrong." );
	}
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// is this stream an input or output stream
	$query = $conn->query( "SELECT `id`,`title` FROM `channels` WHERE `id` = '".$id."' " );
	$channel = $query->fetch( PDO::FETCH_ASSOC );

	// get pids to kill so we dont have zombies
	$query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".$id."' " );
	$channel['servers'] = $query->fetchAll( PDO::FETCH_ASSOC );

	foreach( $channel['servers'] as $channel_server ) {
		if( $channel_server['running_pid'] != 0) {
			$job['action'] = 'kill_pid';
			$job['command'] = 'kill -9 '.$channel_server['running_pid'];

			// add the job
			if( $channel_server['type'] == 'primary' ) {
				$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['primary_server_id']."','".json_encode($job)."')" );
			} elseif( $channel_server['type'] == 'secondary' ) {
				$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['secondary_server_id']."','".json_encode($job)."')" );
			}
		}
	}

	// delete the channel
	$delete = $conn->query( "DELETE FROM `channels` WHERE `id` = '".$id."' " );
	$delete = $conn->query( "DELETE FROM `channels_servers` WHERE `channel_id` = '".$id."' " );
	$delete = $conn->query( "DELETE FROM `channels_sources` WHERE `channel_id` = '".$id."' " );

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'live' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$id."' " );
	}

	// delete from stalker
	// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '".$id."' " );

	log_add( 'channel_delete', 'Channel: '.$id.' / "'.addslashes( $channel['title'] ).'" was deleted.' );

	status_message( "success", "Live Channel has been deleted." );
	go( 'dashboard.php?c=channels' );
}

function inspect_m3u()
{
	header('Content-Type: application/json' );

	$url = $_GET["url"];

	if(isset( $url) ) {
		$m3ufile =@file_get_contents($url);
	}else{
	  	//$m3ufile =@file_get_contents('http://pastebin.com/raw/t1mBJ2Yi' );
	  	$m3ufile =@file_get_contents('https://raw.githubusercontent.com/onigetoc/iptv-playlists/master/general/tv/us.m3u' );
	}

	//$m3ufile = str_replace('tvg-', 'tvg_', $m3ufile);
	$m3ufile = str_replace('group-title', 'tvgroup', $m3ufile);
	$m3ufile = str_replace( "tvg-", "tv", $m3ufile);

	//$re = '/#(EXTINF|EXTM3U):(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$) )/';
	$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$) )/';
	$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';

	preg_match_all($re, $m3ufile, $matches);

	$i = 1;

	$items = array();

	 foreach($matches[0] as $list) {
	 	 
	   preg_match($re, $list, $matchList);

	   $mediaURL = preg_replace( "/[\n\r]/","",$matchList[3] );
	   $mediaURL = preg_replace('/\s+/', '', $mediaURL);   

	   $newdata =  array (
	    'id' => $i++,
	    'title' => $matchList[2],
	    'url' => $mediaURL
	    );
	    
	    preg_match_all($attributes, $list, $matches, PREG_SET_ORDER);
	    
	    foreach ($matches as $match) {
	       $newdata[$match[1]] = $match[2];
	    }

		 $items[] = $newdata;    
	 }

	echo json_encode($items);
}

function inspect_m3u_encoded()
{
	header('Content-Type: application/json' );

	$items = array();

	$url = base64_decode($_GET["url"] );

	if(isset( $url) ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$m3ufile = curl_exec($ch);
		curl_close($ch);

		if( $m3ufile) {
			//$m3ufile = str_replace('tvg-', 'tvg_', $m3ufile);
			$m3ufile = str_replace('group-title', 'tvgroup', $m3ufile);
			$m3ufile = str_replace( "tvg-", "tv", $m3ufile);

			//$re = '/#(EXTINF|EXTM3U):(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$) )/';
			$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$) )/';
			$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';

			preg_match_all($re, $m3ufile, $matches);

			$i = 1;

			$items = array();

			foreach($matches[0] as $list) {
			 	 
			   	preg_match($re, $list, $matchList);

			   	$mediaURL = preg_replace( "/[\n\r]/","",$matchList[3] );
			   	$mediaURL = preg_replace('/\s+/', '', $mediaURL);   

			   	$newdata =  array (
			    	'id' => $i++,
					'title' => $matchList[2],
			    	'url' => $mediaURL
			    );
			    
			    preg_match_all($attributes, $list, $matches, PREG_SET_ORDER);
			    
			    foreach ($matches as $match) {
			    	$newdata[$match[1]] = $match[2];
			    }

				$items[] = $newdata;    
			}
		}else{
			$items['status'] = 'invalid file or 404';
		}
	}else{
		$items['status'] = 'url is missing'; 
	}

	json_output($items);
}

function inspect_remote_playlist()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	header('Content-Type: application/json' );

	$items = '';

	$playlist_id = $_GET["id"];

	$query = $conn->query( "SELECT * FROM `remote_playlists` WHERE `id` = '".$playlist_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	if( $query !== FALSE) {
		$playlist = $query->fetch( PDO::FETCH_ASSOC );
	}

	if(isset( $playlist['url']) ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$playlist['url'] );
		$m3ufile = curl_exec($ch);
		curl_close($ch);

		if( $m3ufile) {
			//$m3ufile = str_replace('tvg-', 'tvg_', $m3ufile);
			$m3ufile = str_replace('group-title', 'tvgroup', $m3ufile);
			$m3ufile = str_replace( "tvg-", "tv", $m3ufile);

			//$re = '/#(EXTINF|EXTM3U):(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$) )/';
			$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$) )/';
			$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';

			preg_match_all($re, $m3ufile, $matches);

			$i = 1;

			$items = array();

			foreach($matches[0] as $list) {
			 	 
			   	preg_match($re, $list, $matchList);

			   	$mediaURL = preg_replace( "/[\n\r]/","",$matchList[3] );
			   	$mediaURL = preg_replace('/\s+/', '', $mediaURL);   

			   	$newdata =  array (
			    	'id' => $i++,
					'title' => $matchList[2],
			    	'url' => $mediaURL
			    );
			    
			    preg_match_all($attributes, $list, $matches, PREG_SET_ORDER);
			    
			    foreach ($matches as $match) {
			    	$newdata[$match[1]] = $match[2];
			    }

				$items[] = $newdata;    
			}
		}else{
			$items['status'] = 'invalid file or 404';
		}
	}else{
		$items['status'] = 'url is missing'; 
	}

	json_output($items);
}

function analyse_stream()
{
	header( "Content-Type:application/json; charset=utf-8" );

	$url = trim( $_GET['url'] );
	$url = str_replace(' ', '', $url);

	if(empty($url) ) {
		$data[0]['status'] 						= 'missing url';
	}else{
		$data[0]['url']							= $url;
		$data[0]['url_bits']					= parse_url($url);

		// add host > ip to firewall
		$data[0]['url_bits']['ip_address'] 		= gethostbyname($data[0]['url_bits']['host'] );
		$data[0]['firewall_cmd']				= "sudo csf -a " . $data[0]['url_bits']['ip_address'];
		// $firewall 							= exec( "/usr/bin/sudo -u root -s /usr/sbin/csf -a " . $data['url_bits']['ip_address'] );
		// $data[0]['firewall_reply']			= $firewall;

		// test the stream
		$data[0]['results'] 					= shell_exec( "/etc/ffmpeg/ffprobe -v quiet -print_format json -show_format -show_streams '".$url."'" );
		$data[0]['results'] 					= json_decode($data[0]['results'], true);

		if(isset( $data[0]['results']['streams']) ) {
			$data[0]['status'] = 'online';

			// lets grab a screenshot
			$random_img = md5($url);
			$data[0]['screenshot_path'] = "/home2/slipstream/public_html/hub/screenshots/".$random_img.".png";
			$data[0]['screenshot_url'] = "http://".$global_settings['cms_access_url']."/screenshots/".$random_img.".png";
			$screenshot = shell_exec( "/etc/ffmpeg/ffmpeg -y -i '".$url."' -f image2 -vframes 1 /home2/slipstream/public_html/hub/screenshots/".$random_img.".png" );
			
			$count = 1;
			foreach($data[0]['results']['streams'] as $stream) {
				if( $stream['codec_type'] == 'video') {
					$data[0]['stream_data'][0] = $stream;
				}
			}

			foreach($data[0]['results']['streams'] as $stream) {
				if( $stream['codec_type'] == 'audio') {
					$data[0]['stream_data'][$count] = $stream;
					$count++;
				}
			}
		}elseif(!isset( $data[0]['results']['streams']) ) {
			$data[0]['status'] = 'offline';
		}else{
			$data[0]['status'] = 'unknown';
		}
	}

	json_output( $data );
}

function cdn_stream_start()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$server_id = get( 'server_id' );
	$stream_id = get( 'stream_id' );

	$uuid = md5($server_id.$stream_id);

	// add to db
	$insert = $conn->exec( "INSERT INTO `cdn_streams_servers` 
        (`id`,`user_id`,`server_id`,`stream_id`)
        VALUE
        (
        '".$uuid."',
        '".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$stream_id."'
    )" );

    // log_add( "Stream has been added." );
    status_message( "success", "Stream has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function cdn_stream_stop()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$stream_id = get( 'stream_id' );
	$server_id = get( 'server_id' );

	// get the pid to kill
	$query = $conn->query( "SELECT * FROM `cdn_streams_servers` WHERE `stream_id` = '".$stream_id."' AND `server_id` = '".$server_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$stream = $query->fetchAll( PDO::FETCH_ASSOC );

	// set the stream to die by pid
	// example: // example: {"action":"kill_pid","command":"kill -9 12748"}
	$job['action'] = 'kill_pid';
	$job['command'] = 'kill -9 '.$stream[0]['running_pid'];

	// add the job
	$insert = $conn->exec( "INSERT INTO `jobs` 
        (`server_id`,`job`)
        VALUE
        ('".$server_id."','".json_encode($job)."')" );

	$update = $conn->exec( "DELETE FROM `cdn_streams_servers` WHERE `stream_id` = '".$stream_id."' AND `server_id` = '".$server_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    // log_add( "Streaming has been stopped." );
    status_message( "success", "Stream has been stopped." );
	go( $_SERVER['HTTP_REFERER'] );
}

function acl_rule_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$server_id 		= post( 'server_id' );
	$ip_address 	= post( 'ip_address' );
	$comment 		= addslashes(  post( 'comment' ) );

	// add to db
	$insert = $conn->exec( "INSERT INTO `streams_acl_rules` 
        (`user_id`,`server_id`,`ip_address`,`comment`)
        VALUE
        (
        '".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$ip_address."',
        '".$comment."'
    )" );

    // log_add( "Firewall rule has been added." );
    status_message( 'success' , "Firewall rule has been added." );
	go(  $_SERVER['HTTP_REFERER'] );
}

function acl_rule_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$rule_id = get( 'rule_id' );

	$update = $conn->exec( "DELETE FROM `streams_acl_rules` WHERE `id` = '".$rule_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    // log_add( "Firewall rule has been deleted." );
    status_message( "success", "Firewall rule has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function stream_enable_format()
{
	global $conn, $site;

	$stream_id 			= get( 'stream_id' );
	$stream_format 		= get( 'stream_format' );

	$stream_raw 		=@file_get_contents($site['url']."actions.php?a=ajax_stream&stream_id=".$stream_id);
	$stream 			= json_decode($stream_raw, true);

	// echo 'stream format: '.$stream_format.' <br>';
	// echo 'existing stream format: '.$stream[0]['output_options'][$stream_format]['enable'].' <br>';

	$stream[0]['output_options'][$stream_format]['enable'] = 'yes';
	// echo 'new stream format: '.$stream[0]['output_options'][$stream_format]['enable'].' <br>';

	$stream[0]['output_options'] = json_encode($stream[0]['output_options'] );
	// echo '<pre>';
	// print_r($stream);

	$update = $conn->exec( "UPDATE `streams` SET `output_options` = '".$stream[0]['output_options']."' WHERE `id` = '".$id."' " );

	// add job to kill current stream and let new settings take effect
	/*
	$job['action'] = 'kill_pid';
	$job['command'] = 'kill -9 '.$stream[0]['running_pid'];

	// add the job
	$insert = $conn->exec( "INSERT INTO `jobs` 
        (`server_id`,`job`)
        VALUE
        ('".$stream[0]['server_id']."','".json_encode($job)."')" );
	*/
	
	// log_add( "Stream format updated." );
    status_message( "success", "Changes have been saved." );
	go( $_SERVER['HTTP_REFERER'] );
}

function stream_disable_format()
{
	global $conn, $site;

	$stream_id 			= get( 'stream_id' );
	$stream_format 		= get( 'stream_format' );

	$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' " );
	$stream = $query->fetchAll( PDO::FETCH_ASSOC );

	// echo 'stream format: '.$stream_format.' <br>';
	// echo 'existing stream format: '.$stream[0]['output_options'][$stream_format]['enable'].' <br>';

	$stream[0]['output_options'][$stream_format]['enable'] = 'no';
	$stream[0]['output_options'][$stream_format]['status'] = 'offline';
	// echo 'new stream format: '.$stream[0]['output_options'][$stream_format]['enable'].' <br>';

	$stream[0]['output_options'] = json_encode($stream[0]['output_options'] );
	// echo '<pre>';
	// print_r($stream);

	$update = $conn->exec( "UPDATE `streams` SET `output_options` = '".$stream[0]['output_options']."' WHERE `id` = '".$id."' " );

	// add job to kill current stream and let new settings take effect
	/*
	$job['action'] = 'kill_pid';
	$job['command'] = 'kill -9 '.$stream[0]['running_pid'];

	// add the job
	$insert = $conn->exec( "INSERT INTO `jobs` 
        (`server_id`,`job`)
        VALUE
        ('".$stream[0]['server_id']."','".json_encode($job)."')" );

	*/

	// log_add( "Stream format updated." );
    status_message( "success", "Changes have been saved." );
	go( $_SERVER['HTTP_REFERER'] );
}

function streams_restart_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$data['action'] = 'streams_restart_all';
	$data['command'] = '';

	$headends = get_all_servers_ids();

	foreach($headends as $headend) {
		if( $headend['status'] == 'online') {
			$insert = $conn->exec( "INSERT INTO `jobs` 
	        (`server_id`,`job`)
	        VALUE
	        ('".$headend['id']."','".json_encode($data)."')" );
		}
	}

	$update = $conn->exec( "UPDATE `streams` SET `job_status` = 'restarting' " );
	$update = $conn->exec( "UPDATE `streams` SET `uptime` = '' " );
	$update = $conn->exec( "UPDATE `streams` SET `fps` = '' " );
	$update = $conn->exec( "UPDATE `streams` SET `speed` = '' " );
    
    // log_add( "Restarting all streams." );
	status_message( "success", "All Channels will be restarted shortly." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channels_start_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$update = $conn->exec( "UPDATE `channels` SET `stream` = 'yes' WHERE `stream` != 'yes' " );
	$update = $conn->exec( "UPDATE `channels` SET `status` = 'starting' WHERE `status` = 'offline' " );

	$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'starting' WHERE `status` = 'offline' " );
    
    // log_add( "Restarting all streams." );
	status_message( "success", "All Live Channels will start shortly." );
    go( $_SERVER['HTTP_REFERER'] );
}

function export_m3u()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	//Generate text file on the fly
	header( "Content-type: text/plain" );
	header( "Content-Disposition: attachment; filename=playlist.m3u" );

	$new_line = "\n";

	// demo m3u format
	// #EXTM3U
	// #EXTINF:-1,CHANNEL NAME
	// http://link.to.stream

	print "#EXTM3U".$new_line;

	// build $streams
	$query = $conn->query( "SELECT * FROM `streams` WHERE `stream_type` = 'output' AND `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC" );
	$streams = $query->fetchAll( PDO::FETCH_ASSOC );

	foreach($streams as $stream) {
		// get stream data for each headend
		// $query = $conn->query( "SELECT * FROM `servers` WHERE `id` = '".$stream['server_id']."' " );
		// $stream['headend'] = $query->fetchAll( PDO::FETCH_ASSOC );

		print "#EXTINF:-1,".strtoupper( $stream['stream_type'])." SOURCE - ".stripslashes($stream['name']).$new_line;
		print "http://".$global_settings['cms_access_url']."/streams/".$stream['server_id']."/".$stream['id'].$new_line;
	}
}

function profile_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$first_name 			= post( 'first_name' );
	$last_name 				= post( 'last_name' );
	$email 					= post( 'email' );
	// $theme 					= post('theme' );
	$username 				= post( 'username' );
	$password1 				= post( 'password1' );
	$password2 				= post( 'password2' );

	status_message( "success", "Your changes have been saved." );

	$update = $conn->exec( "UPDATE `users` SET `first_name` = '".$first_name."' 			WHERE `id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `last_name` = '".$last_name."' 				WHERE `id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `email` = '".$email."' 						WHERE `id` = '".$_SESSION['account']['id']."' " );
	// $update = $conn->exec( "UPDATE `users` SET `theme` = '".$theme."' 						WHERE `id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `username` = '".$username."' 				WHERE `id` = '".$_SESSION['account']['id']."' " );

	if( !empty( post( 'password1' ) ) && !empty( post( 'password2' ) ) ) {
		if( $password1 == $password2 ) {
			$update = $conn->exec( "UPDATE `users` SET `password` = '".$password1."' WHERE `id` = '".$_SESSION['account']['id']."' " );
		}else{
			status_message( "danger", "Passwords do not match, please try again." );
		}
	}

    go( $_SERVER['HTTP_REFERER'] );
}

function user_settings()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	// sanity check
	if( $_SESSION['account']['type'] == 'admin' ) {
		$id 					= get( 'id' );
		$first_name 			= post( 'first_name' );
		$last_name 				= post( 'last_name' );
		$email 					= post( 'email' );
		$theme 					= post('theme' );
		$username 				= post( 'username' );
		$password1 				= post( 'password1' );
		$password2 				= post( 'password2' );

		status_message( "success", "Your changes have been saved." );

		$update = $conn->exec( "UPDATE `users` SET `first_name` = '".$first_name."' 			WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `last_name` = '".$last_name."' 				WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `email` = '".$email."' 						WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `theme` = '".$theme."' 						WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `username` = '".$username."' 				WHERE `id` = '".$id."' " );

		if( !empty( $password ) && !empty( $password2 ) ) {
			if( $password1 == $password2 ) {
				$update = $conn->exec( "UPDATE `users` SET `password` = '".$password1."' WHERE `id` = '".$id."' " );
			}else{
				status_message( "danger", "Passwords do not match, please try again." );
			}
		}
	}

    go( $_SERVER['HTTP_REFERER'] );
}

function customer_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
		
	$username 			= post( 'username' );

	if( empty( $username ) ) {
		$username 		= md5( md5( time() ) );
	}

	$password 			= md5( md5( rand( 00000,99999 ) ) );

	$expire_date 		= strtotime('+1 month', time() );

	$insert = $conn->exec( "INSERT INTO `customers` 
        (`owner_id`,`updated`,`username`,`password`,`expire_date`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".time()."',
        '".$username."',
        '".$password."',
        '".$expire_date."'
    )" );

    $customer_id = $conn->lastInsertId();

    log_add( 'customer_add', 'Customer: '.$customer_id.' / "'.$username.'" was added.' );

	status_message( "success", "Customer account has been added." );
	go( "dashboard.php?c=customer&id=".$customer_id);
}

function customer_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 				= post( 'id' );

	$owner_id 			= post( 'owner_id' );

	$status 			= post( 'status' );

	$first_name 		= post( 'first_name' );
	$first_name 		= preg_replace( "/[^a-zA-Z0-9]+/", "", $first_name);

	$last_name 			= post( 'last_name' );
	$last_name 			= preg_replace( "/[^a-zA-Z0-9]+/", "", $last_name);

	$email 				= post( 'email' );
	$email 				= trim( $email);

	$existing_username	= post( 'existing_username' );

	$username			= post( 'username' );
	$username 			= preg_replace( "/[^a-zA-Z0-9]+/", "", $username);

	$password 			= post( 'password' );
	$password 			= preg_replace( "/[^a-zA-Z0-9]+/", "", $password);

	$max_connections 	= post( 'max_connections' );
	$max_connections 	= preg_replace('/\D/', '', $max_connections);
	if( empty( $max_connections ) ) {
		$max_connections = 1;
	}

	$expire_date 		= post( 'expire_date' );
	$expire_date 		= strtotime( $expire_date );
	
	$notes 				= post( 'notes' );

	$reseller_notes 	= post( 'reseller_notes' );

	// $reseller_notes 	= addslashes( $_POST['reseller_notes'] );
	// $reseller_notes 	= trim( $reseller_notes);

	$package_id			= post( "package_id" );

	$credits			= post( 'credits' );
	$credits 			= preg_replace('/\D/', '', $credits);
	if( empty( $credits ) ) {
		$credits = 0;
	}

	// check if username is already in use
	if( $existing_username != $username) {
		$query 							= $conn->query( "SELECT `id` FROM `customers` WHERE `username` = '".$username."' " );
		$existing_username 				= $query->fetch( PDO::FETCH_ASSOC );
		if(!empty($existing_username) ) {
			status_message( "danger", $username." is already in use." );
		}else{
			$update = $conn->exec( "UPDATE `customers` SET `status` = '".$status."' 						WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `owner_id` = '".$owner_id."' 					WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `first_name` = '".$first_name."'					WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `last_name` = '".$last_name."' 					WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `email` = '".$email."' 							WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `username` = '".$username."' 					WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `password` = '".$password."' 					WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `expire_date` = '".$expire_date."' 				WHERE `id` = '".$id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `max_connections` = '".$max_connections."' 		WHERE `id` = '".$id."' " );
			
			if( $_SESSION['account']['type'] == 'admin' ) {
				$update = $conn->exec( "UPDATE `customers` SET `notes` = '".$notes."' 						WHERE `id` = '".$id."' " );
			}
			
			$update = $conn->exec( "UPDATE `customers` SET `reseller_notes` = '".$reseller_notes."' 		WHERE `id` = '".$id."' " );
			// $update = $conn->exec( "UPDATE `customers` SET `bouquet` = '".$bouquets."' 						WHERE `id` = '".$id."' " );
			
			if( $_SESSION['account']['type'] == 'admin' ) {
				$update = $conn->exec( "UPDATE `customers` SET `owner_id` = '".$owner_id."' 				WHERE `id` = '".$id."' " );
			}
			
			$update = $conn->exec( "UPDATE `customers` SET `credits` = '".$credits."' 						WHERE `id` = '".$id."' " );

			$update = $conn->exec( "UPDATE `customers` SET `package_id` = '".$package_id."' 				WHERE `id` = '".$id."' " );
			
			status_message( "success", "Customer account has been updated." );
		}
	}else{
		$update = $conn->exec( "UPDATE `customers` SET `status` = '".$status."' 						WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `owner_id` = '".$owner_id."' 					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `first_name` = '".$first_name."'					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `last_name` = '".$last_name."' 					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `email` = '".$email."' 							WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `username` = '".$username."' 					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `password` = '".$password."' 					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `expire_date` = '".$expire_date."' 				WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `customers` SET `max_connections` = '".$max_connections."' 		WHERE `id` = '".$id."' " );
		
		if( $_SESSION['account']['type'] == 'admin' ) {
			$update = $conn->exec( "UPDATE `customers` SET `notes` = '".$notes."' 						WHERE `id` = '".$id."' " );
		}
		
		$update = $conn->exec( "UPDATE `customers` SET `reseller_notes` = '".$reseller_notes."' 		WHERE `id` = '".$id."' " );
		// $update = $conn->exec( "UPDATE `customers` SET `bouquet` = '".$bouquets."' 						WHERE `id` = '".$id."' " );
		
		if( $_SESSION['account']['type'] == 'admin' ) {
			$update = $conn->exec( "UPDATE `customers` SET `owner_id` = '".$owner_id."' 				WHERE `id` = '".$id."' " );
		}
		
		$update = $conn->exec( "UPDATE `customers` SET `credits` = '".$credits."' 						WHERE `id` = '".$id."' " );

		$update = $conn->exec( "UPDATE `customers` SET `package_id` = '".$package_id."' 				WHERE `id` = '".$id."' " );

		status_message( "success", "Customer account has been updated." );
	}
	log_add( 'customer_update', 'Customer: '.$id.' / "'.$username.'" was updated.' );
	go( $_SERVER['HTTP_REFERER'] );
}

function customer_status()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 				= get( 'id' );

	$status 			= get( 'status' );

	$update = $conn->exec( "UPDATE `customers` SET `status` = '".$status."' WHERE `id` = '".$id."' " );
			
	status_message( "success", "Customer status has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function customer_multi_options()
{
	global $conn, $site;

	$action = post( 'multi_options_action' );

	$customer_ids = $_POST['customer_ids'];

	if( $action == 'disable' ) {
		foreach($customer_ids as $customer_id)
		{
			$update = $conn->exec( "UPDATE `customers` SET `status` = 'disable' 					WHERE `id` = '".$customer_id."' " );
		}

		status_message( "success", "Selected customers have been disabled." );
	}
	if( $action == 'enable' ) {
		foreach($customer_ids as $customer_id)
		{
			$update = $conn->exec( "UPDATE `customers` SET `status` = 'enabled' 					WHERE `id` = '".$customer_id."' " );
		}

		status_message( "success", "Selected customers have been enabled." );
	}
	if( $action == 'delete' ) {
		foreach($customer_ids as $customer_id)
		{
			$update = $conn->exec( "DELETE FROM `customers` 										WHERE `id` = '".$customer_id."' " );
		}

		status_message( "success", "Selected customers have been deleted." );
	}
	if( $action == 'change_package' ) {
		$package_id = post( "package_id" );

		// get the package bouquets
		$query 					= $conn->query( "SELECT * FROM `packages` WHERE `id` = '".$package_id."' " );
		$package 				= $query->fetch( PDO::FETCH_ASSOC );
		$bouquets 				= $package['bouquets'];

		foreach($customer_ids as $customer_id)
		{
			$update = $conn->exec( "UPDATE `customers` SET `bouquet` = '".$bouquets."' 			WHERE `id` = '".$customer_id."' " );
		}

		status_message( "success", "Selected customers have been updated with the new package / bouquets." );
	}
	if( $action == 'change_expire_date' ) {
		$expire_date = post( "expire_date" );
		foreach($customer_ids as $customer_id)
		{
			$update = $conn->exec( "UPDATE `customers` SET `expire_date` = '".$expire_date."' 	WHERE `id` = '".$customer_id."' " );
			$update = $conn->exec( "UPDATE `customers` SET `status` = 'enabled' 					WHERE `id` = '".$customer_id."' " );
		}

		status_message( "success", "Selected customers have been enabled." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function customer_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );
	$customer = get_customer_details( $id );

	$delete = $conn->exec( "DELETE FROM `customers` WHERE `id` = '".$id."' " );
	// $delete = $conn->exec( "DELETE FROM `stalker_db`,`users` WHERE `id` = '".$customer_id."' " );
	
    log_add( 'customer_delete', 'Customer: '.$id.' / "'.$customer['username'].'" was deleted.' );
    status_message( "success", "Customer account has been deleted." );
	go( 'dashboard.php?c=customers' );
}

function transcoding_profile_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$name 				= post( 'name' );

	$data 				= '{"cpu_gpu":"copy","video_codec":"libx264","cpu_video_codec":"libx264","gpu":"0","gpu_video_codec":"h264_nvenc","surfaces":"10","framerate":"","preset":"0","profile":"baseline","screen_resolution":"copy","bitrate":"5120","audio_codec":"copy","audio_bitrate":"128","audio_sample_rate":"44100","ac":"2","fingerprint":"disable","fingerprint_type":"static_text","fingerprint_text":"","fingerprint_fontsize":"","fingerprint_color":"white","fingerprint_location":"top_left"}';

	$insert = $conn->exec( "INSERT INTO `transcoding_profiles` 
        (`name`,`data`)
        VALUE
        ('".$name."',
        '".$data."'
    )" );

    $profile_id = $conn->lastInsertId();
    
    log_add( 'transcoding_profile_add', 'Transcoding Profile: "'.$name.'" was added.' );
	status_message( "success", "Transcoding Profile has been created." );
	go( 'dashboard.php?c=transcoding_profile&id='.$profile_id);
}

function transcoding_profile_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 				= post( 'id' );
	$name 				= post( 'name' );

	$data 				= $_POST['data'];

	if( $data['cpu_gpu'] == 'cpu') {
		$data['video_codec'] 		= $data['cpu_video_codec'];
	}
	if( $data['cpu_gpu'] == 'gpu') {
		$data['video_codec'] 		= $data['gpu_video_codec'];
	}

	$data 							= json_encode( $data );

	$update = $conn->exec( "UPDATE `transcoding_profiles` SET `name` = '".$name."'	WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `transcoding_profiles` SET `data` = '".$data."'	WHERE `id` = '".$id."' " );

	log_add( 'transcoding_profile_update', 'Transcoding Profile: "'.$name.'" was updated.' );
	status_message( "success", "Transcoding Profile has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function transcoding_profile_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$update = $conn->exec( "UPDATE `channels` SET `transcoding_profile_id` = '0' WHERE `transcoding_profile_id` = '".$id."' " );
	$update = $conn->exec( "DELETE FROM `transcoding_profiles` WHERE `id` = '".$id."' " );
	
    // log_add( 'transcoding_profile_delete', 'Transcoding Profile: "'.$name.'" was deleted.' );
    status_message( "success", "Transcoding Profile has been deleted. Please restart Live Channels that were using this profile for changes to take effect." );
	go( 'dashboard.php?c=transcoding_profiles' );
}

function restart_transcoding_profile_channels()
{
	global $conn, $site;

	$id 				= get( 'id' );

	// get the profile data
	$query 				= $conn->query( "SELECT * FROM `transcoding_profiles` WHERE `id` = '".$profile_id."' " );
	$profile 			= $query->fetch( PDO::FETCH_ASSOC );
	$profile_data		= json_decode( $profile['data'], true );

	// get channel ids and running pids
	$query 				= $conn->query( "SELECT `id`,`running_pid` FROM `channels` WHERE `transcoding_profile_id` = '".$profile_id."' " );
	$channels 			= $query->fetchAll( PDO::FETCH_ASSOC );

	foreach( $channels as $channel ) {
			// build the kill command
			$job['action'] = 'kill_pid';
			$job['command'] = 'kill -9 '.$channel['running_pid'];

			// add the job to kill the stream ready for restart
			$insert = $conn->exec( "INSERT INTO `jobs` 
		        (`server_id`,`job`)
		        VALUE
		        ('".$stream[0]['server_id']."','".json_encode($job)."')" );

			// update settings for this stream
			$update = $conn->exec( "UPDATE `streams` SET `user_agent` = '".$profile_data['user_agent']."' 								WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `ffmpeg_re` = '".$profile_data['ffmpeg_re']."' 									WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `cpu_gpu` = '".$profile_data['cpu_gpu']."' 										WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `video_codec` = '".$profile_data['video_codec']."' 								WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `gpu` = '".$profile_data['gpu']."' 												WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `surfaces` = '".$profile_data['surfaces']."' 									WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `framerate` = '".$profile_data['framerate']."' 									WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `preset` = '".$profile_data['preset']."' 										WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `profile` = '".$profile_data['profile']."' 										WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `screen_resolution` = '".$profile_data['screen_resolution']."' 					WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `bitrate` = '".$profile_data['bitrate']."' 										WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `audio_codec` = '".$profile_data['audio_codec']."' 								WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `audio_bitrate` = '".$profile_data['audio_bitrate']."' 							WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `audio_sample_rate` = '".$profile_data['audio_sample_rate']."' 					WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `ac` = '".$profile_data['ac']."' 												WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fingerprint` = '".$profile_data['fingerprint']."' 								WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fingerprint_type` = '".$profile_data['fingerprint_type']."' 					WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fingerprint_text` = '".$profile_data['fingerprint_text']."' 					WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fingerprint_fontsize` = '".$profile_data['fingerprint_fontsize']."' 			WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fingerprint_color` = '".$profile_data['fingerprint_color']."' 					WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fingerprint_location` = '".$profile_data['fingerprint_location']."' 			WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );

			// set some stream settings to default values
			$update = $conn->exec( "UPDATE `streams` SET `enable` = 'yes' 			WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `status` = 'offline' 		WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fps` = '' 					WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `speed` = '' 				WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `stream_uptime` = '' 		WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `pending_changes` = 'no' 	WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `job_status` = 'analysing' 	WHERE `id` = '".$stream_id['id']."' AND `user_id` = '".$_SESSION['account']['id']."' " );

			/*
			$update = $conn->exec( "UPDATE `streams` SET `enable` = 'yes' 			WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `status` = 'offline' 		WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `fps` = '' 					WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `speed` = '' 				WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `stream_uptime` = '' 		WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `pending_changes` = 'no' 	WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			$update = $conn->exec( "UPDATE `streams` SET `job_status` = 'analysing' 	WHERE `source_stream_id` = '".$stream_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
			*/
		}

	status_message( "success", "Streams will restart shortly." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_category_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$name 				= post( 'name' );

	$insert = $conn->exec( "INSERT INTO `channels_categories` 
        (`name`)
        VALUE
        ('".$name."'
    )" );
    
	log_add( 'channel_category_add', 'Channel Category: "'.$name.'" was added.' );
	status_message( "success", "Live Channel Category has been added." );
	go( "dashboard.php?c=channel_categories" );
}

function channel_category_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 		= post( 'id' );
	$name 		= post( 'name' );

	// reset category_id back to default
	$update = $conn->query( "UPDATE `channels_categories` SET `name` = '".$name."' WHERE `id` = '".$id."' " );

	status_message( "success", "Live Channel Category has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_category_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// reset category_id back to default
	$update = $conn->query( "UPDATE `channels` SET `category_id` = '1' WHERE `category_id` = '".$id."' " );

	// delete primary record
	$delete = $conn->query( "DELETE FROM `channels_categories` WHERE `id` = '".$id."' " );

	log_add( 'channel_category_delete', 'Channel Category: "'.$name.'" was deleted.' );
	status_message( "success", "Live Channel Category has been deleted." );
	go( 'dashboard.php?c=channel_categories' );
}

function vod_monitored_folder_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$server_id			= post( 'server_id' );
	if(empty( $server_id) || $server_id == 0) {

		status_message( "danger", "You must select a server." );
		go( $_SERVER['HTTP_REFERER'] );
	}
	
	$folder 			= post( 'folder' );

	// add input stream
	$insert = $conn->exec( "INSERT INTO `vod_monitored_folders` 
        (`user_id`,`server_id`,`folder`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$folder."'
    )" );

    $server = server_details( $server_id );

    log_add( 'vod_monitored_folder_add', 'Movie VoD Monitored: "'.$folder.'" was added on server "'.$server['name'].'"' );
    
	// log_add( "Video on Demand has been added." );
	status_message( "success", "Movie VoD Monitored Folder has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_monitored_folder_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `vod_monitored_folders` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
	status_message( "success", "Movie VoD Monitored Folder has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_monitored_folder_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$server_id			= post( 'server_id' );
	if(empty( $server_id) || $server_id == 0) {

		status_message( "danger", "You must select a server." );
		go( $_SERVER['HTTP_REFERER'] );
	}
	
	$folder 			= post( 'folder' );

	// add input stream
	$insert = $conn->exec( "INSERT INTO `vod_tv_monitored_folders` 
        (`user_id`,`server_id`,`folder`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$folder."'
    )" );

    $server = server_details( $server_id );

    log_add( 'vod_tv_monitored_folder_add', 'TV VoD Monitored: "'.$folder.'" was added on server "'.$server['name'].'"' );
    
	// log_add( "Video on Demand has been added." );
	status_message( "success", "TV VoD Monitored Folder has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_monitored_folder_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `vod_tv_monitored_folders` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
	status_message( "success", "TV VoD Monitored Folder has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$server_id			= addslashes( $_POST['server_id'] );
	if(empty($_POST['server_id']) || $_POST['server_id'] == 0) {

		status_message( "danger", "You must select a server." );
		go( $_SERVER['HTTP_REFERER'] );
	}
	
	$name 				= post( 'name' );
	$name 				= trim( $name);

	// try the open movie db for meta data
	$url = 'http://www.omdbapi.com/?apikey=19354e2e&t='.urlencode($name);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$metadata = curl_exec($curl);
	curl_close($curl);

	$metadata = json_decode($metadata, true);

	if( $metadata['Response'] == False) {
		$year 			= '';
		$cover_photo	= '';
		$description	= '';
		$genre 			= '';
		$runtime 		= '';
		$language 		= '';
	}elseif( $metadata['Response'] == True) {
		$year 			= addslashes( $metadata['Year'] );
		$cover_photo	= addslashes( $metadata['Poster'] );
		$description	= addslashes( $metadata['Plot'] );
		$genre 			= addslashes( $metadata['Genre'] );
		$runtime 		= addslashes( $metadata['Runtime'] );
		$language 		= addslashes( $metadata['Language'] );
	}

	// add input stream
	$insert = $conn->exec( "INSERT INTO `vod` 
        (`user_id`,`server_id`,`name`,`year`,`cover_photo`,`description`,`genre`,`runtime`,`language`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$name."',
        '".$year."',
        '".$cover_photo."',
        '".$description."',
        '".$genre."',
        '".$runtime."',
        '".$language."'
    )" );

    $vod_id = $conn->lastInsertId();
    
	// log_add( "Video on Demand has been added." );
	status_message( "success", "Movie VoD has been added." );
	go( 'dashboard.php?c=vod_edit&id='.$vod_id);
}

function vod_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 							= post( 'id' );
	$data['category_id'] 			= post( 'category_id' );
	$data['imdbid'] 				= post( 'imdbid' );
	$data['title'] 					= post( 'title' );
	$data['genre'] 					= post( 'genre' );
	$data['year'] 					= post( 'year' );
	$data['runtime'] 				= post( 'runtime' );
	$data['language'] 				= post( 'language' );
	$data['plot'] 					= post( 'plot' );
	$data['poster'] 				= post( 'poster' );
	
	$update = $conn->exec( "UPDATE `vod` SET `category_id` = '".$data['category_id']."' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `imdbid` = '".$data['imdbid']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `title` = '".$data['title']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `plot` = '".$data['plot']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `poster` = '".$data['poster']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `year` = '".$data['year']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `genre` = '".$data['genre']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `runtime` = '".$data['runtime']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `language` = '".$data['language']."' 								WHERE `id` = '".$id."' " );

    // log_add( "Video on Demand changes have been saved." );
    status_message( "success", "Movie VoD changes have been saved." );
    go( $_SERVER['HTTP_REFERER'] );
}

function vod_imdb_search()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );
	
	// get imdbid
	$vod = vod_details( get( 'id' ) );

	$metadata = get_metadata_by_id( $vod['imdbid'] );

	if( !empty( $metadata['poster'] ) ) {
		// save the image to the cms server
		$local_filename = basename( $metadata['poster'] );
		$remote_content = file_get_contents( $metadata['poster'] );
		file_put_contents( '/var/www/html/content/imdb_media/'.$local_filename, $remote_content );

		$metadata['poster'] = 'http://'.$global_settings['cms_access_url_raw'].'/content/imdb_media/'.$local_filename;
	}
	
	$update = $conn->exec( "UPDATE `vod` SET `title` = '".$metadata['title']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `plot` = '".$metadata['plot']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `poster` = '".$metadata['poster']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `year` = '".$metadata['year']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `genre` = '".$metadata['genre']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `runtime` = '".$metadata['runtime']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod` SET `language` = '".$metadata['language']."' 								WHERE `id` = '".$id."' " );

    // log_add( "Video on Demand changes have been saved." );
    status_message( "success", "Movie VoD metadata has been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_imdb_search()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );
	
	// get imdbid
	$vod = vod_tv_details( get( 'id' ) );

	$metadata = get_metadata_by_id( $vod['imdbid'] );

	if( !empty( $metadata['poster'] ) ) {
		// save the image to the cms server
		$local_filename = basename( $metadata['poster'] );
		$remote_content = file_get_contents( $metadata['poster'] );
		file_put_contents( '/var/www/html/content/imdb_media/'.$local_filename, $remote_content );

		$metadata['poster'] = 'http://'.$global_settings['cms_access_url_raw'].'/content/imdb_media/'.$local_filename;
	}
	
	$update = $conn->exec( "UPDATE `vod_tv` SET `title` = '".$metadata['title']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `plot` = '".$metadata['plot']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `poster` = '".$metadata['poster']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `year` = '".$metadata['year']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `genre` = '".$metadata['genre']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `runtime` = '".$metadata['runtime']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `language` = '".$metadata['language']."' 								WHERE `id` = '".$id."' " );

    // log_add( "Video on Demand changes have been saved." );
    status_message( "success", "TV VoD metadata has been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_247_imdb_search()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );
	
	// get imdbid
	$vod = channels_247_details( get( 'id' ) );

	$metadata = get_metadata_by_id( $vod['imdbid'] );

	if( !empty( $metadata['poster'] ) ) {
		// save the image to the cms server
		$local_filename = basename( $metadata['poster'] );
		$remote_content = file_get_contents( $metadata['poster'] );
		file_put_contents( '/var/www/html/content/imdb_media/'.$local_filename, $remote_content );

		$metadata['poster'] = 'http://'.$global_settings['cms_access_url_raw'].'/content/imdb_media/'.$local_filename;
	}
	
	$update = $conn->exec( "UPDATE `channels_247` SET `title` = '".$metadata['title']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `plot` = '".$metadata['plot']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `poster` = '".$metadata['poster']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `year` = '".$metadata['year']."' 										WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `genre` = '".$metadata['genre']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `runtime` = '".$metadata['runtime']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `language` = '".$metadata['language']."' 								WHERE `id` = '".$id."' " );

    // log_add( "Video on Demand changes have been saved." );
    status_message( "success", "24/7 Channel metadata has been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 							= post( 'id' );
	$data['category_id'] 			= post( 'category_id' );
	$data['imdbid'] 				= post( 'imdbid' );
	$data['title'] 					= post( 'title' );
	$data['genre'] 					= post( 'genre' );
	$data['year'] 					= post( 'year' );
	$data['runtime'] 				= post( 'runtime' );
	$data['language'] 				= post( 'language' );
	$data['plot'] 					= post( 'plot' );
	$data['poster'] 				= post( 'poster' );
	
	$update = $conn->exec( "UPDATE `vod_tv` SET `category_id` = '".$data['category_id']."' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `imdbid` = '".$data['imdbid']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `title` = '".$data['title']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `plot` = '".$data['plot']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `poster` = '".$data['poster']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `year` = '".$data['year']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `genre` = '".$data['genre']."' 									WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `runtime` = '".$data['runtime']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `vod_tv` SET `language` = '".$data['language']."' 							WHERE `id` = '".$id."' " );

    // log_add( "Video on Demand changes have been saved." );
    status_message( "success", "TV VoD changes have been saved." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_247_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 							= post( 'id' );
	$data['category_id'] 			= '1';
	$data['title'] 					= post( 'title' );
	$data['poster'] 				= post( 'poster' );
	
	$update = $conn->exec( "UPDATE `channels_247` SET `category_id` = '".$data['category_id']."' 					WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `title` = '".$data['title']."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `channels_247` SET `poster` = '".$data['poster']."' 								WHERE `id` = '".$id."' " );

    // log_add( "Video on Demand changes have been saved." );
    status_message( "success", "24/7 Channel changes have been saved." );
    go( $_SERVER['HTTP_REFERER'] );
}

function vod_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'vod' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$id."' " );
	}

	$delete = $conn->exec( "DELETE FROM `vod` WHERE `id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `vod_files` WHERE `vod_id` = '".$id."' " );
	
	// remove from bouquets_content
	// $delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `content_id` = '".$vod_id."' " );

    // log_add( "Video on Demand has been deleted." );
    status_message( "success", "Movie VoD has been deleted." );
	go( 'dashboard.php?c=vod' );
}

function vod_tv_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'vod_tv' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$id."' " );
	}

	$delete = $conn->exec( "DELETE FROM `vod_tv` WHERE `id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `vod_tv_files` WHERE `vod_id` = '".$id."' " );
	
	// remove from bouquets_content
	// $delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `content_id` = '".$vod_id."' " );

    // log_add( "Video on Demand has been deleted." );
    status_message( "success", "TV VoD has been deleted." );
	go( 'dashboard.php?c=vod_tv' );
}

function old_channel_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$server_id			= addslashes( $_POST['server_id'] );
	if(empty($_POST['server_id']) || $_POST['server_id'] == 0) {

		status_message( "danger", "You must select a server." );
		go( $_SERVER['HTTP_REFERER'] );
	}

	$type 					= get( 'type' );

	$name 					= get( 'name' );
	
	$folder 				= addslashes( $_POST['folder'] );

	$folder 				= str_replace(array('"', "'",'\\','!','@','#','$','%','^','&','*','(',')','=','+',';',':','|','[',']','{','}','`','~',' '), '', $folder);

	// more sanity checks
	if(strpos($folder, '/etc/') !== false) {
	    status_message( "danger", "Security Alert: Dont try to scan the /etc/ folder" );
    	go( $_SERVER['HTTP_REFERER'] );
	}
	if(strpos($folder, '/root/') !== false) {
	    status_message( "danger", "Security Alert: Dont try to scan the /root/ folder" );
    	go( $_SERVER['HTTP_REFERER'] );
	}
	if(strpos($folder, '/tmp/') !== false) {
	    status_message( "danger", "Security Alert: Dont try to scan the /tmp/ folder" );
    	go( $_SERVER['HTTP_REFERER'] );
	}

	if( $type == 'multi' ) {
		// lets scan the folder
		$sql = "
	        SELECT `id`,`wan_ip_address`,`http_stream_port` 
	        FROM `servers` 
	        WHERE `id` = '".$server_id."' 
	        AND `user_id` = '".$_SESSION['account']['id']."' 
	    ";
	    $query      = $conn->query($sql);
	    $headend    = $query->fetch( PDO::FETCH_ASSOC );

	    $folder_scan =@file_get_contents('http://'.$headend['wan_ip_address'].':'.$headend['http_stream_port'].'/scan_folder_folders.php?passcode=1372&folder_path='.$folder);

	    $folder_scan = json_decode($folder_scan, true);

	    if(isset( $folder_scan[0]) ) {
	    	foreach($folder_scan as $key => $value) {

				$full_path 				= addslashes( $value);

				// check to see if we are already watching this folder on this server
				$query = $conn->query( "SELECT `id` FROM `channels` WHERE `server_id` = '".$server_id."' AND `watch_folder` = '".$full_path."' " );
				$channel = $query->fetch( PDO::FETCH_ASSOC );

				if(!isset( $channel['id']) ) {
					// break to get folder for name
					$path_bits 				= explode( "/", $full_path);

					$reverse_path_bits 		= array_reverse($path_bits);

					$name 					= $reverse_path_bits[0];
					$name 					= str_replace(array( ".","-","_" ), " ", $name);
					$name 					= trim( $name);

					$metadata 				= get_metadata($name);

					// set meta defaults
					$year 			= '';
					$cover_photo	= '';
					$description	= '';
					$genre 			= '';
					$runtime 		= '';
					$language 		= '';

					if(isset( $metadata) && $metadata['status'] == 'match' ) {
						if(isset( $metadata['name']) && !empty($metadata['name']) ) {
							$name         		  	= addslashes( $metadata['name'] );
						}
						if(isset( $metadata['year']) && !empty($metadata['year']) ) {
					        $year           		= addslashes( $metadata['year'] );
				        }
				        if(isset( $metadata['cover_photo']) && !empty($metadata['cover_photo']) ) {
				        	$cover_photo    		= addslashes( $metadata['cover_photo'] );
				        }
				        if(isset( $metadata['description']) && !empty($metadata['description']) ) {
				        	$description    		= addslashes( $metadata['description'] );
				        }
				        if(isset( $metadata['genre']) && !empty($metadata['genre']) ) {
				        	$genre          		= addslashes( $metadata['genre'] );
				        }
				        if(isset( $metadata['runtime']) && !empty($metadata['runtime']) ) {
				        	$run        			= addslashes( $metadata['runtime'] );
				        }
				        if(isset( $metadata['language']) && !empty($metadata['language']) ) {
				        	$lang       			= addslashes( $metadata['language'] );
				        }
					}

					// add 
					$insert = $conn->exec( "INSERT INTO `channels` 
				        (`user_id`,`server_id`,`name`,`cover_photo`,`description`,`watch_folder`)
				        VALUE
				        ('".$_SESSION['account']['id']."',
				        '".$server_id."',
				        '".$name."',
				        '".$cover_photo."',
				        '".$description."',
				        '".$full_path."'
				    )" );
				}
			}

			// log_add( "Folder scan complete and media files added." );
			status_message( "success", "Folder scan complete." );
		}else{
			// log_add( "Folder scan complete but no media files were found." );
			status_message( "warning", "Folder scan complete but no contents were found." );
		}
	}else{
		$full_path = $folder;
		// check to see if we are already watching this folder on this server
		$query = $conn->query( "SELECT `id` FROM `channels` WHERE `server_id` = '".$server_id."' AND `watch_folder` = '".$full_path."' " );
		$channel = $query->fetch( PDO::FETCH_ASSOC );

		if(!isset( $channel['id']) ) {
			// break to get folder for name
			$path_bits 				= explode( "/", $full_path);

			$reverse_path_bits 		= array_reverse($path_bits);

			$name 					= $reverse_path_bits[0];
			$name 					= str_replace(array( ".","-","_" ), " ", $name);
			$name 					= trim( $name);

			$metadata 				= get_metadata($name);

			// set meta defaults
			$year 			= '';
			$cover_photo	= '';
			$description	= '';
			$genre 			= '';
			$runtime 		= '';
			$language 		= '';

			if(isset( $metadata) && $metadata['status'] == 'match' ) {
				if(isset( $metadata['name']) && !empty($metadata['name']) ) {
					$name         		  	= addslashes( $metadata['name'] );
				}
				if(isset( $metadata['year']) && !empty($metadata['year']) ) {
			        $year           		= addslashes( $metadata['year'] );
		        }
		        if(isset( $metadata['cover_photo']) && !empty($metadata['cover_photo']) ) {
		        	$cover_photo    		= addslashes( $metadata['cover_photo'] );
		        }
		        if(isset( $metadata['description']) && !empty($metadata['description']) ) {
		        	$description    		= addslashes( $metadata['description'] );
		        }
		        if(isset( $metadata['genre']) && !empty($metadata['genre']) ) {
		        	$genre          		= addslashes( $metadata['genre'] );
		        }
		        if(isset( $metadata['runtime']) && !empty($metadata['runtime']) ) {
		        	$run        			= addslashes( $metadata['runtime'] );
		        }
		        if(isset( $metadata['language']) && !empty($metadata['language']) ) {
		        	$lang       			= addslashes( $metadata['language'] );
		        }
			}

			// add 
			$insert = $conn->exec( "INSERT INTO `channels` 
		        (`user_id`,`server_id`,`name`,`cover_photo`,`description`,`watch_folder`)
		        VALUE
		        ('".$_SESSION['account']['id']."',
		        '".$server_id."',
		        '".$name."',
		        '".$cover_photo."',
		        '".$description."',
		        '".$full_path."'
		    )" );
		}
	}
    
    go( $_SERVER['HTTP_REFERER'] );
}

function old_channel_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 								= addslashes( $_POST['id'] );

	// $data['server_id'] 				= addslashes( $_POST['server_id'] );

	$data['name'] 						= post( 'name' );
	$data['name']						= trim( $data['name'] );

	$data['description'] 				= addslashes( $_POST['description'] );
	$data['description']				= trim( $data['description'] );

	$data['cover_photo'] 				= addslashes( $_POST['cover_photo'] );
	$data['cover_photo']				= trim( $data['cover_photo'] );

	$data['transcoding_profile_id']		= post( 'transcoding_profile_id' );
	
	$update = $conn->exec( "UPDATE `channels` SET `name` = '".$data['name']."' 											WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `channels` SET `description` = '".$data['description']."' 							WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `channels` SET `cover_photo` = '".$data['cover_photo']."' 							WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `channels` SET `transcoding_profile_id` = '".$data['transcoding_profile_id']."' 		WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    // log_add( "Channel changes have been saved." );
    status_message( "success", "24/7 TV Channel changes have been saved." );
    go( $_SERVER['HTTP_REFERER'] );
}

function old_channel_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// get all episodes to delete from bouquets_content
	$query = $conn->query( "SELECT `id` FROM `channels_files` WHERE `channel_id` = '".$id."' " );
	$channel_files = $query->fetchAll( PDO::FETCH_ASSOC );
	
	// remove from bouquets_content
	foreach($channel_files as $channel_file) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `content_id` = '".$channel_file['id']."' " );
	}

	$delete = $conn->exec( "DELETE FROM `channels_files` WHERE `channel_id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$delete = $conn->exec( "DELETE FROM `channels` WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
	// delete from stalker
	$delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '247".$id."' " );

    // log_add( "Channel has been deleted." );
    status_message( "success", "Channel has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_episode_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$server_id			= addslashes( $_POST['server_id'] );

	$id					= addslashes( $_POST['id'] );
	
	$name 				= post( 'name' );
	$name 				= trim( $name);

	$file_location 		= addslashes( $_POST['file_location'] );
	$file_location 		= trim( $file_location);

	// get next number in the order
	$query = $conn->query( "SELECT `order` FROM `channels_files` WHERE `channel_id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' ORDER BY `order` DESC LIMIT 1" );
	$bits = $query->fetch( PDO::FETCH_ASSOC );
	if(isset( $bits['order']) ) {
		$next_order = ($bits['order'] + 1);
	}else{
		$next_order = 0;
	}
		
	// add input stream
	$insert = $conn->exec( "INSERT IGNORE INTO `channels_files` 
        (`user_id`,`server_id`,`channel_id`,`name`,`file_location`,`order`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$id."',
        '".$name."',
        '".$file_location."',
        '".$next_order."'
    )" );
    
	// log_add( "Episode has been added." );
	status_message( "success", "Episode has been added." );
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_episode_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `content_id` = '".$id."' " );

	$delete = $conn->exec( "DELETE FROM `channels_files` WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    // log_add( "Channel Episode has been deleted." );
    status_message( "success", "Channel Episode has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_episode_delete_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// get all episodes to delete from bouquets_content
	$query = $conn->query( "SELECT `id` FROM `channels_files` WHERE `channel_id` = '".$id."' " );
	$channel_files = $query->fetchAll( PDO::FETCH_ASSOC );
	
	// remove from bouquets_content
	foreach($channel_files as $channel_file) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `content_id` = '".$channel_file['id']."' " );
	}

	$delete = $conn->exec( "DELETE FROM `channels_files` WHERE `channel_id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    status_message( "success", "All Channel Episodes have been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_episode_scan_folder()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$server_id			= addslashes( $_POST['server_id'] );

	$id					= addslashes( $_POST['id'] );
	
	$folder_path 		= $_POST['folder_path'];
	$folder_path 		= trim( $folder_path);

	// sanity checks
	$folder_path 		= str_replace(array('"', "'",'\\','!','@','#','$','%','^','&','*','(',')','=','+',';',':','|','[',']','{','}','`','~',' '), '', $folder_path);

	// more sanity checks
	if(strpos($folder_path, '/etc/') !== false) {
	    status_message( "danger", "Security Alert: Dont try to scan the /etc/ folder" );
    	go( $_SERVER['HTTP_REFERER'] );
	}
	if(strpos($folder_path, '/root/') !== false) {
	    status_message( "danger", "Security Alert: Dont try to scan the /root/ folder" );
    	go( $_SERVER['HTTP_REFERER'] );
	}
	if(strpos($folder_path, '/tmp/') !== false) {
	    status_message( "danger", "Security Alert: Dont try to scan the /tmp/ folder" );
    	go( $_SERVER['HTTP_REFERER'] );
	}

	// lets scan the folder
	$sql = "
        SELECT `id`,`wan_ip_address`,`http_stream_port` 
        FROM `servers` 
        WHERE `id` = '".$server_id."' 
        AND `user_id` = '".$_SESSION['account']['id']."' 
    ";
    $query      = $conn->query($sql);
    $headend    = $query->fetch( PDO::FETCH_ASSOC );

    $folder_scan =@file_get_contents('http://'.$headend['wan_ip_address'].':'.$headend['http_stream_port'].'/scan_folder_files.php?passcode=1372&folder_path='.$folder_path);

    $folder_scan = json_decode($folder_scan, true);

    if(isset( $folder_scan[0]) ) {
    	foreach($folder_scan as $key => $value) {

    		$name 				= $key;
			$file_location 		= addslashes( $value);

			// get next number in the order
			$query = $conn->query( "SELECT `order` FROM `channels_files` WHERE `channel_id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' ORDER BY `order` DESC LIMIT 1" );
			$bits = $query->fetch( PDO::FETCH_ASSOC );
			if(isset( $bits['order']) ) {
				$next_order = ($bits['order'] + 1);
			}else{
				$next_order = 0;
			}
				
			// add input stream
			$insert = $conn->exec( "INSERT IGNORE INTO `channels_files` 
		        (`user_id`,`server_id`,`channel_id`,`name`,`file_location`,`order`)
		        VALUE
		        ('".$_SESSION['account']['id']."',
		        '".$server_id."',
		        '".$id."',
		        '".$name."',
		        '".$file_location."',
		        '".$next_order."'
		    )" );
		}

		// log_add( "Folder scan complete and media files added." );
		status_message( "success", "Folder scan complete and media files added." );
	}else{
		// log_add( "Folder scan complete but no media files were found." );
		status_message( "warning", "Folder scan complete but no media files were found." );
	}
	
    go( $_SERVER['HTTP_REFERER'] );
}

function channel_update_order()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	foreach($_POST['name'] as $key => $value) {
		$update = $conn->exec( "UPDATE `channels_files` SET `name` = '".addslashes( $value)."' WHERE `id` = '".$key."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	}

	foreach($_POST['file_location'] as $key => $value) {
		$update = $conn->exec( "UPDATE `channels_files` SET `file_location` = '".addslashes( $value)."' WHERE `id` = '".$key."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	}

	// log_add( 'channel_update', 'Channel: '.$channel_id.' / "'.$name.'" was added.' );

    status_message( "success", "Channel episodes have been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channels_247_start_stop()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 		= get( 'id' );
	$action 	= get( 'action' );

	if( $action == 'start' ) {
		$update = $conn->exec( "UPDATE `channels_247` SET `stream` = 'yes' WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `channels_247` SET `status` = 'starting' WHERE `id` = '".$id."' " );
		status_message( "success", "24/7 Channel will start shortly." );

		$channel = channel_247_details( $id );
		log_add( 'channels_247_start', '24/7 Channel: "'.$channel['title'].'" was started.' );
	}
	
	if( $action == 'stop' ) {
		$update = $conn->exec( "UPDATE `channels_247` SET `stream` = 'no' WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `channels_247` SET `status` = 'stopping' WHERE `id` = '".$id."' " );
		status_message( "success", "24/7 Channel will stop shortly." );

		$channel = channel_247_details( $id );
		log_add( 'channels_247_stop', '24/7 Channel: "'.$channel['title'].'" was stopped.' );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function channels_247_start_stop_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 		= get( 'id' );
	$action 	= get( 'action' );

	if( $action == 'start' ) {
		$update = $conn->exec( "UPDATE `channels_247` SET `stream` = 'yes' WHERE `stream` = 'no' " );
		$update = $conn->exec( "UPDATE `channels_247` SET `status` = 'starting' WHERE `status` != 'online' " );
		status_message( "success", "All 24/7 Channel will start shortly." );

		log_add( 'channels_247_all_start', 'All 24/7 Channels will start shortly.' );
	}
	
	if( $action == 'stop' ) {
		$update = $conn->exec( "UPDATE `channels_247` SET `stream` = 'no' WHERE `stream` = 'yes' " );
		$update = $conn->exec( "UPDATE `channels_247` SET `status` = 'stopping' " );
		status_message( "success", "All 24/7 Channel will stop shortly." );

		log_add( 'channels_247_all_stop', 'All 24/7 Channels will stop shortly.' );
	}
	go( $_SERVER['HTTP_REFERER'] );
}

function dns_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
		
	$server_id 			= addslashes( $_POST['server_id'] );
	$query 				= $conn->query( "SELECT `wan_ip_address` FROM `servers` WHERE `id` = '".$server_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$server 			= $query->fetch( PDO::FETCH_ASSOC );

	$hostname 			= addslashes( $_POST['hostname'] );
	$hostname 			= trim( $hostname);
	$hostname 			= str_replace(array('.', ' ', '_'), '-', $hostname);

	$domain 			= addslashes( $_POST['domain'] );

	// check if hostname already in use
	$query = $conn->query( "SELECT `id` FROM `addon_dns` WHERE `hostname` = '".$hostname."' AND `domain` = '".$domain."' " );
	$existing_record = $query->fetch( PDO::FETCH_ASSOC );
	if(isset( $existing_record['id']) ) {
		status_message( "danger", "DNS Host is already taken." );
		go( $_SERVER['HTTP_REFERER'] );
		die();
	}

	$cloudflare 		= cf_add_host($hostname, $domain, $server['wan_ip_address'] );
	
	debug($_POST);
	debug($cloudflare);
	$insert = $conn->exec( "INSERT INTO `addon_dns` 
        (`user_id`,`server_id`,`hostname`,`domain`,`cf_domain_id`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$hostname."',
        '".$domain."',
        '".$cloudflare['domain_id']."'
    )" );

    $record_id = $conn->lastInsertId();

	// log_add( "DNS Host has been added." );
	status_message( "success", "DNS Host has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function dns_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$customer_id 		= addslashes( $_POST['customer_id'] );
	$status 			= addslashes( $_POST['status'] );
	$first_name 		= addslashes( $_POST['first_name'] );
	$last_name 			= addslashes( $_POST['last_name'] );
	$email 				= addslashes( $_POST['email'] );
	$username			= addslashes( $_POST['username'] );
	$password 			= addslashes( $_POST['password'] );
	$max_connections 	= addslashes( $_POST['max_connections'] );
	$notes 				= addslashes( $_POST['notes'] );
	
	$update = $conn->exec( "UPDATE `customers` SET `status` = '".$status."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `first_name` = '".$first_name."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `last_name` = '".$last_name."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `email` = '".$email."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `username` = '".$username."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `password` = '".$password."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `max_connections` = '".$max_connections."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `notes` = '".$notes."' WHERE `id` = '".$customer_id."' " );

	// log_add( "Customer has been updated." );
	status_message( "success", "Customer account has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function dns_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$update = $conn->exec( "DELETE FROM `addon_dns` WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    // log_add( "DNS record has been deleted." );
    status_message( "success", "DNS record has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channels_multi_options()
{
	global $conn, $site;

	$action 		= post( 'multi_options_action' );

	$channel_ids 	= $_POST['channel_ids'];

	if( $action == 'start' ) {
		foreach( $channel_ids as $channel_id ) {
			// start channel
			$update = $conn->exec( "UPDATE `channels` SET `stream` = 'yes' 						WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `status` = 'starting' 				WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `fps` = '' 							WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `speed` = '' 							WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 						WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `pending_changes` = 'no' 				WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'analysing' 			WHERE `id` = '".$channel_id."' " );

			$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'starting' 		WHERE `channel_id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels will start shortly." );
	}

	if( $action == 'stop' ) {
		foreach( $channel_ids as $channel_id ) {
			// stop channel
			$update = $conn->exec( "UPDATE `channels` SET `stream` = 'no' 						WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			$update = $conn->exec( "UPDATE `channels` SET `status` = 'stopping' 				WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			$update = $conn->exec( "UPDATE `channels` SET `fps` = '' 							WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			$update = $conn->exec( "UPDATE `channels` SET `speed` = '' 							WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 						WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			$update = $conn->exec( "UPDATE `channels` SET `pending_changes` = 'no' 				WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'none'	 				WHERE `id` = '".$channel_id."' AND `stream` != 'no'" );
			
			$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'stopping' 		WHERE `channel_id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels will stop shortly." );
	}

	if( $action == 'restart' ) {
		foreach( $channel_ids as $channel_id ) {
			$query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$channel_id."' " );
			$channel = $query->fetch( PDO::FETCH_ASSOC );

			$query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".$channel_id."' " );
			$channel['servers'] = $query->fetchAll( PDO::FETCH_ASSOC );

			foreach( $channel['servers'] as $channel_server ) {
				if( $channel_server['running_pid'] != 0) {
					$job['action'] = 'kill_pid';
					$job['command'] = 'kill -9 '.$channel_server['running_pid'];

					// add the job
					if( $channel_server['type'] == 'primary' ) {
						$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['primary_server_id']."','".json_encode($job)."')" );
					} elseif( $channel_server['type'] == 'secondary' ) {
						$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['secondary_server_id']."','".json_encode($job)."')" );
					}
				}
			}

			$update = $conn->exec( "UPDATE `channels` SET `status` = 'restarting' 				WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `fps` = '' 							WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `speed` = '' 							WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 						WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `pending_changes` = 'no' 				WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels` SET `job_status` = 'analysing' 			WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'restarting' 		WHERE `channel_id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '' 					WHERE `channel_id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels will restart shortly." );
	}

	if( $action == 'delete' ) {
		foreach( $channel_ids as $channel_id ) {
			$query = $conn->query( "SELECT `id`,`title` FROM `channels` WHERE `id` = '".$channel_id."' " );
			$channel = $query->fetch( PDO::FETCH_ASSOC );

			// get pids to kill so we dont have zombies
			$query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".$channel_id."' " );
			$channel['servers'] = $query->fetchAll( PDO::FETCH_ASSOC );

			foreach( $channel['servers'] as $channel_server ) {
				if( $channel_server['running_pid'] != 0) {
					$job['action'] = 'kill_pid';
					$job['command'] = 'kill -9 '.$channel_server['running_pid'];

					// add the job
					if( $channel_server['type'] == 'primary' ) {
						$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['primary_server_id']."','".json_encode($job)."')" );
					} elseif( $channel_server['type'] == 'secondary' ) {
						$insert = $conn->exec( "INSERT INTO `jobs` (`server_id`,`job`) VALUE ('".$channel_server['secondary_server_id']."','".json_encode($job)."')" );
					}
				}
			}

			// delete the channel
			$delete = $conn->query( "DELETE FROM `channels` WHERE `id` = '".$channel_id."' " );
			$delete = $conn->query( "DELETE FROM `channels_servers` WHERE `channel_id` = '".$channel_id."' " );
			$delete = $conn->query( "DELETE FROM `channels_sources` WHERE `channel_id` = '".$channel_id."' " );

			// find bouquets to filter for content to be removed
			$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'live' " );
			$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

			// loop over bouquets and delete for this vod item
			foreach( $bouquets as $bouquet ) {
				$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$channel_id."' " );
			}

			// delete from stalker
			// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '".$id."' " );
		}

		status_message( "success", "Selected Live Channels have been deleted." );
	}

	if( $action == 'add_to_bouquet' ) {

		$bouquet_id = post( 'bouquet_id' );

		foreach( $channel_ids as $channel_id ) {
			$insert = $conn->exec( "INSERT IGNORE INTO `bouquets_content` 
		        (`bouquet_id`,`content_id`)
		        VALUE
		        ('".$bouquet_id."',
		        '".$channel_id."'
		    )" );
		}

		status_message( "success", "Selected Live Channels have been added to the selected bouquet." );
	}

	if( $action == 'change_category' ) {

		$category_id = post( 'category_id' );

		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `category_id` = '".$category_id."' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels Category has been updated." );
	}

	if( $action == 'change_user_agent' ) {

		$user_agent = post( 'user_agent' );

		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `user_agent` = '".$user_agent."' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels User Agent has been updated." );
	}

	if( $action == 'change_channel_topology' ) {

		$profile_id = post( 'profile_id' );
		$profile    = get_channel_topology_profile( $profile_id );
		$topology   = unserialize( $profile['data'] );

		debug( $profile );
		debug( $topology );

		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `topology` = '".$profile['data']."' WHERE `id` = '".$channel_id."' " );

			$delete = $conn->exec( "DELETE FROM `channels_servers` WHERE `channel_id` = '".$channel_id."' " );
		}

		die();

		status_message( "success", "Selected Live Channels Topology has been updated and channels will restart shortly." );
	}

	if( $action == 'enable_ondemand' ) {
		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `ondemand` = 'yes' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels have been set to OnDemand." );
	}

	if( $action == 'disable_ondemand' ) {
		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `ondemand` = 'no' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels have been set to Always-On." );
	}

	if( $action == 'enable_always_on' ) {
		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `method` = 'restream' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels have been set to Restream via CMS." );
	}

	if( $action == 'enable_pass_thru' ) {
		foreach( $channel_ids as $channel_id ) {
			$update = $conn->exec( "UPDATE `channels` SET `method` = 'direct' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected Live Channels have been set to Direct to Source." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function channels_247_multi_options()
{
	global $conn, $site;

	$action 		= post( 'multi_options_action' );

	$channel_ids 	= $_POST['channel_ids'];

	if( $action == 'start' ) {
		foreach( $channel_ids as $channel_id ) {
			// start channel
			$update = $conn->exec( "UPDATE `channels_247` SET `stream` = 'yes' WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels_247` SET `status` = 'starting' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected 24/7 Channels will start shortly." );
	}

	if( $action == 'stop' ) {
		foreach( $channel_ids as $channel_id ) {
			// start channel
			$update = $conn->exec( "UPDATE `channels_247` SET `stream` = 'no' WHERE `id` = '".$channel_id."' " );
			$update = $conn->exec( "UPDATE `channels_247` SET `stopping` = 'starting' WHERE `id` = '".$channel_id."' " );
		}

		status_message( "success", "Selected 24/7 Channels will stop shortly." );
	}

	if( $action == 'delete' ) {
		foreach( $channel_ids as $channel_id ) {
			// delete the channel
			$delete = $conn->query( "DELETE FROM `channels_247` WHERE `id` = '".$channel_id."' " );
			$delete = $conn->query( "DELETE FROM `channels_247_files` WHERE `vod_id` = '".$channel_id."' " );

			// find bouquets to filter for content to be removed
			$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'channel_247' " );
			$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

			// loop over bouquets and delete for this vod item
			foreach( $bouquets as $bouquet ) {
				$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$channel_id."' " );
			}

			// delete from stalker
			// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '".$id."' " );
		}

		status_message( "success", "Selected 24/7 Channels have been deleted." );
	}

	if( $action == 'add_to_bouquet' ) {

		$bouquet_id = post( 'bouquet_id' );

		foreach( $channel_ids as $channel_id ) {
			$insert = $conn->exec( "INSERT IGNORE INTO `bouquets_content` 
		        (`bouquet_id`,`content_id`)
		        VALUE
		        ('".$bouquet_id."',
		        '".$channel_id."'
		    )" );
		}

		status_message( "success", "Selected 24/7 Channels have been added to the selected bouquet." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function vod_multi_options()
{
	global $conn, $site;

	$action 		= post( 'multi_options_action' );

	$vod_ids 		= $_POST['vod_ids'];

	if( $action == 'delete' ) {
		foreach( $vod_ids as $vod_id ) {
			// delete the vod
			$delete = $conn->query( "DELETE FROM `vod` WHERE `id` = '".$vod_id."' " );
			$delete = $conn->query( "DELETE FROM `vod_files` WHERE `vod_id` = '".$vod_id."' " );

			// find bouquets to filter for content to be removed
			$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'vod' " );
			$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

			// loop over bouquets and delete for this vod item
			foreach( $bouquets as $bouquet ) {
				$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$vod_id."' " );
			}

			// delete from stalker
			// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '".$id."' " );
		}

		status_message( "success", "Selected Movie VoDs have been deleted." );
	}

	if( $action == 'change_category' ) {

		$category_id = post( 'category_id' );

		foreach( $vod_ids as $vod_id ) {
			$update = $conn->exec( "UPDATE `vod` SET `category_id` = '".$category_id."' WHERE `id` = '".$vod_id."' " );
		}

		status_message( "success", "Selected Movie VoDs Category has been updated." );
	}

	if( $action == 'add_to_bouquet' ) {

		$bouquet_id = post( 'bouquet_id' );

		foreach( $vod_ids as $vod_id ) {
			$insert = $conn->exec( "INSERT IGNORE INTO `bouquets_content` 
		        (`bouquet_id`,`content_id`)
		        VALUE
		        ('".$bouquet_id."',
		        '".$vod_id."'
		    )" );
		}

		status_message( "success", "Selected Movie VoDs have been added to the selected bouquet." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_multi_options()
{
	global $conn, $site;

	$action 		= post( 'multi_options_action' );

	$vod_ids 		= $_POST['vod_ids'];

	if( $action == 'delete' ) {
		foreach( $vod_ids as $vod_id ) {
			// delete the vod
			$delete = $conn->query( "DELETE FROM `vod_tv` WHERE `id` = '".$vod_id."' " );
			$delete = $conn->query( "DELETE FROM `vod_tv_files` WHERE `vod_id` = '".$vod_id."' " );

			// find bouquets to filter for content to be removed
			$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'vod_tv' " );
			$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

			// loop over bouquets and delete for this vod item
			foreach( $bouquets as $bouquet ) {
				$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$vod_id."' " );
			}

			// delete from stalker
			// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '".$id."' " );
		}

		status_message( "success", "Selected TV VoDs have been deleted." );
	}

	if( $action == 'create_247_channels' ) {
		foreach( $vod_ids as $vod_id ) {
			// remove if already exists to remove dupes
			$delete = $conn->query( "DELETE FROM `channels_247` WHERE `id` = '".$vod_id."' " );
			$delete = $conn->query( "DELETE FROM `channels_247_files` WHERE `vod_id` = '".$vod_id."' " );

			// get main vod_tv dataset
			$query          	= $conn->query( "SELECT * FROM `vod_tv` WHERE `id` = '".$vod_id."' " );
		    $vod           		= $query->fetchAll( PDO::FETCH_ASSOC );
		    $vod 				= stripslashes_deep( $vod );

		    // create the channels_247 dataset
		    foreach( $vod as $vod_data ) {
		    	$insert = $conn->exec( "INSERT INTO `channels_247` 
			        (`id`,`server_id`,`title`,`poster`,`category_id`,`show_hash`)
			        VALUE
			        ('".$vod_data['id']."',
			        '".$vod_data['server_id']."',
			        '".addslashes( $vod_data['title'] )."',
			        '".$vod_data['poster']."',
			        '1',
			        '".$vod_data['show_hash']."'
			    )" );
		    }

		    // get vod_tv_files for this dataset
			$query          	= $conn->query( "SELECT * FROM `vod_tv_files` WHERE `vod_id` = '".$vod_id."' " );
		    $vod['episodes']   	= $query->fetchAll( PDO::FETCH_ASSOC );
		    $vod['episodes'] 	= stripslashes_deep( $vod['episodes'] );

		    // add the episodes for this show
		    foreach( $vod['episodes'] as $episode ) {
		    	$insert = $conn->exec( "INSERT INTO `channels_247_files` 
			        (`id`,`server_id`,`vod_id`,`title`,`season`,`episode`,`file_location`)
			        VALUE
			        ('".$episode['id']."',
			        '".$vod_data['server_id']."',
			        '".$vod_data['id']."',
			        '".addslashes( $episode['title'] )."',
			        '".$episode['season']."',
			        '".$episode['episode']."',
			        '".addslashes( $episode['file_location'] )."'
			    )" );
		    }
		}

		status_message( "success", "Selected TV VoDs have been deleted." );
	}

	if( $action == 'change_category' ) {

		$category_id = post( 'category_id' );

		foreach( $vod_ids as $vod_id ) {
			$update = $conn->exec( "UPDATE `vod_tv` SET `category_id` = '".$category_id."' WHERE `id` = '".$vod_id."' " );
		}

		status_message( "success", "Selected TV VoDs Category has been updated." );
	}

	if( $action == 'add_to_bouquet' ) {

		$bouquet_id = post( 'bouquet_id' );

		foreach( $vod_ids as $vod_id ) {
			$insert = $conn->exec( "INSERT IGNORE INTO `bouquets_content` 
		        (`bouquet_id`,`content_id`)
		        VALUE
		        ('".$bouquet_id."',
		        '".$vod_id."'
		    )" );
		}

		status_message( "success", "Selected TV VoDs have been added to the selected bouquet." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function channels_stop_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$update = $conn->exec( "UPDATE `channels` SET `stream` = 'no' " );
	$update = $conn->exec( "UPDATE `channels` SET `status` = 'offline' " );
	$update = $conn->exec( "UPDATE `channels` SET `uptime` = '' " );

	$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'offline' " );

	status_message( "success", "All Live Channels will stop shortly." );
    go( $_SERVER['HTTP_REFERER'] );
}

function bulk_update_sources()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$old_source_url = post( 'old_source_url' );
	$new_source_url = post( 'new_source_url' );

	error_log( "\n\n Bulk Updating Sources \n\n" );
	error_log( "\n\n old_source_url = ".$old_source_url."\n\n" );
	error_log( "\n\n new_source_url = ".$new_source_url."\n\n" );
	error_log( "\n\n SQL = UPDATE `streams` SET `source` = REPLACE(`source`, '".$old_source_url."', '".$new_source_url."') WHERE `user_id` = '1' \n\n" );

	$update = $conn->exec( "UPDATE `streams` SET `source` = REPLACE(`source`, '".$old_source_url."', '".$new_source_url."') WHERE `user_id` = '".$_SESSION['account']['id']."' " );

	status_message( "success", "Source URLs have been updated." );
    go( $_SERVER['HTTP_REFERER'] );
}

function remote_playlist_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$name 				= post( 'name' );
	$name 				= trim( $name);

	$url 				= addslashes( $_POST['url'] );
	$url 				= trim( $url);

	$insert = $conn->exec( "INSERT INTO `remote_playlists` 
        (`user_id`,`name`,`url`)
        VALUE
        ('".$_SESSION['account']['id']."',
        '".$name."',
        '".$url."'
    )" );

    $record_id = $conn->lastInsertId();

	status_message( "success", "Remote Playlist has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function remote_playlist_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	// log_add( "Customer has been updated." );
	status_message( "success", "Remote Playlist has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function remote_playlist_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$update = $conn->exec( "DELETE FROM `remote_playlists` WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    status_message( "success", "Remote Playlist has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function roku_device_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$server_id 			= addslashes( $_POST['server'] );

	$device_brand 		= addslashes( $_POST['device_brand'] );

	$app 				= addslashes( $_POST['app'] );

	$name 				= post( 'name' );
	$name 				= trim( $name);

	$ip_address 		= addslashes( $_POST['ip_address'] );
	$ip_address 		= trim( $ip_address);

	$time 				= time();

	$insert = $conn->exec( "INSERT INTO `roku_devices` 
        (`updated`,`user_id`,`server_id`,`device_brand`,`name`,`ip_address`,`status`,`app`)
        VALUE
        ('".$time."',
        '".$_SESSION['account']['id']."',
        '".$server_id."',
        '".$device_brand."',
        '".$name."',
        '".$ip_address."',
        'pending_adoption',
        '".$app."',
    )" );

    $record_id = $conn->lastInsertId();

	status_message( "success", "Roku Device has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function roku_device_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$device_id 			= addslashes( $_POST['device_id'] );
	$server_id 			= addslashes( $_POST['server_id'] );
	$device_brand 		= addslashes( $_POST['device_brand'] );
	$app 				= addslashes( $_POST['app'] );
	$name 				= post( 'name' );
	$name 				= trim( $name);
	$ip_address 		= addslashes( $_POST['ip_address'] );
	$channel			= addslashes( $_POST['channel'] );
	
	$update = $conn->exec( "UPDATE `roku_devices` SET `server_id` = '".$server_id."' 			WHERE `id` = '".$device_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `roku_devices` SET `device_brand` = '".$device_id."' 			WHERE `id` = '".$device_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `roku_devices` SET `name` = '".$name."' 						WHERE `id` = '".$device_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `roku_devices` SET `ip_address` = '".$ip_address."' 			WHERE `id` = '".$device_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `roku_devices` SET `app` = '".$app."'			 				WHERE `id` = '".$device_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	$update = $conn->exec( "UPDATE `roku_devices` SET `channel` = '".$channel."' 				WHERE `id` = '".$device_id."' AND `user_id` = '".$_SESSION['account']['id']."' " );

	status_message( "success", "Roku Device has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function roku_device_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$update = $conn->exec( "DELETE FROM `roku_devices` WHERE `id` = '".$id."' AND `user_id` = '".$_SESSION['account']['id']."' " );
	
    status_message( "success", "Roku Device has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function playlist_checker()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$url 				= addslashes( $_POST['playlist_url'] );
	$url 				= trim( $url);

	$url 				= base64_encode($url);

	status_message( "success", "Playlist will be checked in real-time." );
	go( "dashboard.php?c=playlist_checker_results&url=".$url);
}

function ajax_stream_checker()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	header( "Content-Type:application/json; charset=utf-8" );

	$url 						= addslashes( $_GET['url'] );
	$url 						= trim( $url);

	$data['encoded_url'] 		= $url;

	$url 						= base64_decode($url);

	$data['url'] 				= $url;

	$probe_command				= 'timeout 15 ffprobe -v quiet -print_format json -show_format -show_streams "'.$url.'" ';

	$data['probe_command'] 		= $probe_command;

	$stream_info 				= shell_exec( $probe_command);

	$stream_info 				= json_decode($stream_info, true);

	if(is_array($stream_info['streams']) ) {
		$data['status'] = 'online';
	}else{
		$data['status'] = 'offline';
	}

	json_output( $data );
}

function user_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$username 			= post( 'username' );
	$type 				= post( 'type' );
	$rand 				= rand( 0000000000, 9999999999 );
	$password 			= random_string( 16 );

	$insert = $conn->exec( "INSERT INTO `users` 
        (`type`,`username`,`parent`,`password`)
        VALUE
        ('".$type."',
        '".$username."',
        '".$_SESSION['account']['id']."',
        '".$password."'
    )" );

    $user_id = $conn->lastInsertId();

    log_add( 'user_add', 'Account: '.$user_id.' / "'.$username.'" was created.' );

	status_message( "success", "User account has been added." );
	go( 'dashboard.php?c=user&id='.$user_id );
}

function user_set_theme()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 				= $_SESSION['account']['id'];
	$theme 				= get( 'theme' );

	$update = $conn->exec( "UPDATE `users` SET `theme` = '".$theme."' 					WHERE `id` = '".$id."' " );

	status_message( "success", "Theme has been updated." );

	go( $_SERVER['HTTP_REFERER'] );
}

function user_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	// sanity check
	if( $_SESSION['account']['type'] == 'admin' ) {
		$id 				= post( 'id' );
		$type 				= post( 'type' );
		$first_name 		= post( 'first_name' );
		$last_name 			= post( 'last_name' );
		$email 				= post( 'email' );
		$username 			= post( 'username' );
		$password1 			= post( 'password1' );
		$password2 			= post( 'password2' );
		$credits 			= post( 'credits' );
		$notes 				= post( 'notes' );

		$update = $conn->exec( "UPDATE `users` SET `type` = '".$type."' 					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `first_name` = '".$first_name."' 		WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `last_name` = '".$last_name."' 			WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `email` = '".$email."' 					WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `username` = '".$username."' 			WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `credits` = '".$credits."' 				WHERE `id` = '".$id."' " );
		$update = $conn->exec( "UPDATE `users` SET `notes` = '".$notes."' 					WHERE `id` = '".$id."' " );

		if( !empty( $password1 ) && !empty( $password2 ) ) {
			if( $password1 == $password2 ) {
				$update = $conn->exec( "UPDATE `users` SET `password` = '".$password1."' WHERE `id` = '".$id."' " );
			}else{
				status_message( "danger", "Passwords do not match, please try again." );
			}
		}

		log_add( 'user_update', 'Account: '.$id.' / "'.$username.'" was updated.' );

		status_message( "success", "User account has been updated." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function user_gallary_view_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 				= $account_details['id'];
	$type 				= get( 'type' );

	if( $type == 'gallary' ) {
		$gallary = 'yes';
	} else {
		$gallary = 'no';
	}

	$update = $conn->exec( "UPDATE `users` SET `gallary_view` = '".$gallary."' 					WHERE `id` = '".$id."' " );
	
	status_message( "success", "User Gallary View has been updated." );

	go( $_SERVER['HTTP_REFERER'] );
}

function user_delete()
{
	global $conn, $account_details, $global_settings;

	// sanity check
	if( $_SESSION['account']['type'] == 'admin' ) {

		$id = get( 'id' );

		// cannot delete your own account
		if( $id == $_SESSION['account']['id'] ) {
			status_message( "danger", "You cannot delete your own account." );
		} elseif( $id == 1 ) { 
			status_message( "danger", "You cannot delete the primary admin account." );
		} else {
			$user = account_details( $id );

			$update = $conn->exec( "UPDATE `customers` SET `owner_id` = '1' WHERE `owner_id` = '".$id."' " );
			$delete = $conn->exec( "DELETE FROM `users` WHERE `id` = '".$id."' " );

			log_add( 'user_delete', 'Account: '.$id.' / "'.$user['username'].'" was deleted.' );

		    status_message( "success", "User account has been deleted." );
		}
	}

	go( 'dashboard.php?c=users' );
}

function user_status()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 				= get( 'id' );

	$status 			= get( 'status' );

	$update = $conn->exec( "UPDATE `users` SET `status` = '".$status."' WHERE `id` = '".$id."' " );
			
	status_message( "success", "User status has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}


function xc_import() {
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$user_id 					= $_SESSION['account']['id'];

	$fileName = $_FILES["file1"]["name"]; // The file name
	
	$fileName = str_replace('"', '', $fileName);
	$fileName = str_replace( "'", '', $fileName);
	$fileName = str_replace(' ', '_', $fileName);
	$fileName = str_replace(array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '+', ';', ':', '\\', '|', '~', '`', ',', '<', '>', '/', '?', '§', '±',), '', $fileName);
	// $fileName = $fileName . '.' . $fileExt;
	
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if (!$fileTmpLoc) { // if file not chosen
		echo "Please select a file to upload.";
	}
	
	// check if folder exists for customer, if not create it and continue
	if (!file_exists('xc_uploads/'.$user_id) && !is_dir('xc_uploads/'.$user_id) ) {
		exec( "sudo mkdir -p /var/www/html/portal/xc_uploads" );
		exec( "sudo mkdir -p /var/www/html/portal/xc_uploads/".$user_id);
		exec( "chmod 777 /var/www/html/portal/xc_uploads/".$user_id);
	} 
	
	// handle the uploaded file
	if(move_uploaded_file($fileTmpLoc, "xc_uploads/".$user_id."/".$fileName) ) {

		// save import job for later
		$insert = $conn->exec( "INSERT INTO `xc_import_jobs` 
	        (`user_id`,`status`,`filename`)
	        VALUE
	        ('".$_SESSION['account']['id']."',
	        'pending',
	        '".$fileName."'
	    )" );

		// check for compressed files
		if( $fileType == 'zip' ) {

		}

		// report
		echo "<font color='#18B117'><b>Import job has been added. Import will process shortly.</b></font>";
	}else{
		echo "ERROR: Oops, something went very wrong. Please try again or contact support for more help.";
		exit();
	}	
}

function reset_account()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$user_id			= $_SESSION['account']['id'];
	$type 				= $_GET['type'];
	if(empty($type) ) {
		status_message( "danger", "Missing var, please contact support." );
	}else{
		if( $type == 'account' || $type == 'streams' ) {
			$purge = $conn->exec( "DELETE FROM `streams` WHERE `user_id` = '".$user_id."' " );
		}

		if( $type == 'account' || $type == 'customers' ) {
			$purge = $conn->exec( "DELETE FROM `customers` WHERE `user_id` = '".$user_id."' " );
		}

		if( $type == 'account' || $type == 'packages' ) {
			$purge = $conn->exec( "DELETE FROM `packages` WHERE `user_id` = '".$user_id."' " );
		}

		if( $type == 'account' || $type == 'bouquets' ) {
			$purge = $conn->exec( "DELETE FROM `bouquets` WHERE `user_id` = '".$user_id."' " );
		}

		if( $type == 'account' || $type == 'resellers' ) {
			$purge = $conn->exec( "DELETE FROM `resellers` WHERE `user_id` = '".$user_id."' " );
		}

		if( $type == 'account' || $type == 'mag_devices' ) {
			$purge = $conn->exec( "DELETE FROM `mag_devices` WHERE `user_id` = '".$user_id."' " );
		}

		status_message( "success", "Reset complete. Please reboot all your servers." );
	}
	go( $_SERVER['HTTP_REFERER'] );
}

function banned_ip_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$ip_address 		= post( 'ip_address' );
	$reason 			= post( 'reason' );

	$insert = $conn->exec( "INSERT INTO `banned_ips` 
        (`ip_address`,`reason`)
        VALUE
        ('".$ip_address."',
        '".addslashes( $reason )."'
    )" );
    
	// log_add( "Stream Category has been added." );
	status_message( "success", "Banned IP has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function banned_ip_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->query( "DELETE FROM `banned_ips` WHERE `id` = '".$id."' " );

	status_message( "success", "Banned IP has been deleted." );
	// return user to previous page
	go( $_SERVER['HTTP_REFERER'] );
}

function banned_isp_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$isp_name 			= post( 'isp_name' );
	$reason 			= post( 'reason' );

	$insert = $conn->exec( "INSERT INTO `banned_isps` 
        (`isp_name`,`reason`)
        VALUE
        ('".$isp_name."',
        '".addslashes( $reason )."'
    )" );
    
	// log_add( "Stream Category has been added." );
	status_message( "success", "Banned ISP has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function banned_isp_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->query( "DELETE FROM `banned_isps` WHERE `id` = '".$id."' " );

	status_message( "success", "Banned IPS has been deleted." );
	// return user to previous page
	go( $_SERVER['HTTP_REFERER'] );
}

function bouquet_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$name 				= post( 'name' );
	$type 				= post( 'type' );

	$insert = $conn->exec( "INSERT INTO `bouquets` 
        (`name`,`type`)
        VALUE
        ('".$name."',
        '".$type."'
    )" );

    $bouquet_id = $conn->lastInsertId();
    
	// log_add( "Stream Category has been added." );
	status_message( "success", "Bouquet has been added." );
	go( "dashboard.php?c=bouquet&id=".$bouquet_id);
}

function bouquet_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 				= post( 'id' );
	$name 				= post( 'name' );
		
	$update = $conn->exec( "UPDATE `bouquets` SET `name` = '".$name."' WHERE `id` = '".$id."' " );

	status_message( "success", "Bouquet has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function bouquet_content_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$bouquet_id 				= post( 'id' );
	
	if( isset( $_POST['contents'] ) ) {
		$streams 		= $_POST['contents'];
	}else{
		$streams 		= array();
	}

	// get existing bouquet contents
	$existing_contents = array();
	$query = $conn->query( "SELECT * FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet_id."' " );
	$bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );
	foreach($bouquet_contents as $bouquet_content) {
		$existing_contents[] = $bouquet_content['content_id'];
	}

	foreach($streams as $stream) {
		$insert = $conn->exec( "INSERT IGNORE INTO `bouquets_content` 
	        (`bouquet_id`,`content_id`)
	        VALUE
	        ('".$bouquet_id."',
	        '".$stream."'
	    )" );
	}

	// compare arrays to remove ones we dont want
	$contents_diffs = array_diff( $existing_contents,$streams );
	foreach( $contents_diffs as $contents_diff ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet_id."' AND `content_id` = '".$contents_diff."' " );
	}

	status_message( "success", "Bouquet contents have been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function bouquet_streams_order_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$bouquet_id 		= get( 'id' );

	$positions 			= post_array( 'position' );

	$order = 0;
	foreach( $positions as $position ) {
		$update = $conn->exec( "UPDATE `bouquets_content` SET `order` = '".$order."' WHERE `bouquet_id` = '".$bouquet_id."' AND `content_id` = '".$position."' " );
		$order++;
	}

	// echo $position;
}

function bouquet_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$bouquet = bouquet_details( get( 'id' ) );

	// get all packages, find and delete the bouquet from them
	$query          = $conn->query( "SELECT * FROM `packages` " );
    $packages       = $query->fetchAll( PDO::FETCH_ASSOC );

    // loop over the packages
    foreach( $packages as $package ) {
    	$package['bouquets']    = explode( ",", $package['bouquets'] );

    	$array_position = array_search( $id, $package['bouquets'] );
    	unset( $package['bouquets'][$array_position] );
    	$package['bouquets'] 	= implode( ",", $package['bouquets'] );

    	// put the boquets back
    	$update = $conn->query( "UPDATE `packages` SET `bouquets` = '".$package['bouquets']."'  WHERE `id` = '".$package['id']."' " );
    }

	// delete primary record
	$delete = $conn->query( "DELETE FROM `bouquets` WHERE `id` = '".$id."' " );

	log_add( 'bouquet_delete', 'Bouquet: '.$id.' / "'.$bouquet['name'].'" was deleted.' );

	status_message( "success", "Bouquet has been deleted." );
	// return user to previous page
	go( $_SERVER['HTTP_REFERER'] );
}

function ajax_customer_line()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$customer_id = get( 'customer_id' );

	$query = $conn->query( "SELECT `username`,`password` FROM `customers` WHERE `id` = '".$customer_id."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	$content = '';

	if(!empty($customer['username']) ) {
		$content .= '<div class="row">';
		$content .= '<div class="col-lg-12">';
		$content .= '<div class="form-group">';
		$content .= '<label class="col-md-3 control-label" for="simple_m3u">M3U</label>';
		$content .= '<div class="col-md-9">';
		$content .= '<input type="text" class="form-control" value="http://'.$global_settings['cms_access_url'].'/customers/'.$customer['username'].'/'.$customer['password'].'/simple_m3u">';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';

		$content .= '<div class="row">';
		$content .= '<div class="col-lg-12">';
		$content .= '<div class="form-group">';
		$content .= '<label class="col-md-3 control-label" for="advanced_m3u">M3U with Options</label>';
		$content .= '<div class="col-md-9">';
		$content .= '<input type="text" class="form-control" value="http://'.$global_settings['cms_access_url'].'/customers/'.$customer['username'].'/'.$customer['password'].'/advanced_m3u">';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';

		$content .= '<div class="row">';
		$content .= '<div class="col-lg-12">';
		$content .= '<div class="form-group">';
		$content .= '<label class="col-md-3 control-label" for="enigma">Enigma 2.0 Autscript - HLS</label>';
		$content .= '<div class="col-md-9">';
		$content .= '<input type="text" class="form-control" value="wget -O /etc/enigma2/iptv.sh \'http://'.$global_settings['cms_access_url'].'/customers/'.$customer['username'].'/'.$customer['password'].'/enigma\' && chmod 777 /etc/enigma2/iptv.sh && /etc/enigma2/iptv.sh">';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';

		$content .= '<div class="row">';
		$content .= '<div class="col-lg-12">';
		$content .= '<div class="form-group">';
		$content .= '<label class="col-md-3 control-label" for="enigma">DreamBox OE 2.0 Autscript</label>';
		$content .= '<div class="col-md-9">';
		$content .= '<input type="text" class="form-control" value="http://'.$global_settings['cms_access_url'].'/customers/'.$customer['username'].'/'.$customer['password'].'/dreambox">';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';

		$content .= '<div class="row">';
		$content .= '<div class="col-lg-12">';
		$content .= '<div class="form-group">';
		$content .= '<label class="col-md-3 control-label" for="enigma">WebTV</label>';
		$content .= '<div class="col-md-9">';
		$content .= '<input type="text" class="form-control" value="http://'.$global_settings['cms_access_url'].'/customers/'.$customer['username'].'/'.$customer['password'].'/webtv">';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';


		$content .= '<div class="row">';
		$content .= '<div class="col-lg-12">';
		$content .= '<div class="form-group">';
		$content .= '<label class="col-md-3 control-label" for="enigma">Octogan</label>';
		$content .= '<div class="col-md-9">';
		$content .= '<input type="text" class="form-control" value="http://'.$global_settings['cms_access_url'].'/customers/'.$customer['username'].'/'.$customer['password'].'/octogan">';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
	}else{
		$content .= 'Customer not found.';
	}

	echo $content;
}

function ajax_customer_lines()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$time_shift 	= time() - 20;

	$user_id 		= $_SESSION['account']['id'];

	header( "Content-Type:application/json; charset=utf-8" );

	function find_outputs($array, $key, $value) {
	    $results = array();

	    if (is_array($array) ) {
	        if (isset( $array[$key]) && $array[$key] == $value) {
	            $results[] = $array;
	        }

	        foreach ($array as $subarray) {
	            $results = array_merge($results, find_outputs($subarray, $key, $value) );
	        }
	    }

	    return $results;
	}

	// get customers
	if( $_SESSION['account']['type'] == 'admin' ) {
		$query = $conn->query( "SELECT `id`,`status`,`username`,`password`,`first_name`,`last_name`,`credits`,`email`,`expire_date`,`max_connections`,`reseller_id`,`notes`,`reseller_notes` FROM `customers` " );
		$customers = $query->fetchAll( PDO::FETCH_ASSOC );
	}else{
		$query = $conn->query( "SELECT `id`,`status`,`username`,`password`,`first_name`,`last_name`,`credits`,`email`,`expire_date`,`max_connections`,`reseller_id`,`notes`,`reseller_notes` FROM `customers` WHERE `reseller_id` = '".$_SESSION['account']['id']."' " );
		$customers = $query->fetchAll( PDO::FETCH_ASSOC );
	}

	// get all connection logs
	$query 							= $conn->query( "SELECT `id`,`customer_id` FROM `streams_connection_logs` WHERE `timestamp` > '".$time_shift."' " );
	$temp_connections_streams 		= $query->fetchAll( PDO::FETCH_ASSOC );
	$query 							= $conn->query( "SELECT `id`,`customer_id` FROM `channel_connection_logs` WHERE `timestamp` > '".$time_shift."' " );
	$temp_connections_channels 		= $query->fetchAll( PDO::FETCH_ASSOC );
	$query 							= $conn->query( "SELECT `id`,`customer_id` FROM `vod_connection_logs` WHERE `timestamp` > '".$time_shift."' " );
	$temp_connections_vod 			= $query->fetchAll( PDO::FETCH_ASSOC );
	$query 							= $conn->query( "SELECT `id`,`customer_id` FROM `series_connection_logs` WHERE `timestamp` > '".$time_shift."' " );
	$temp_connections_series 		= $query->fetchAll( PDO::FETCH_ASSOC );

	$connections = array_merge($temp_connections_streams, $temp_connections_channels, $temp_connections_vod, $temp_connections_series);

	// get resellers
	$query = $conn->query( "SELECT `id`,`email`,`username`,`first_name`,`last_name` FROM `resellers` " );
	$resellers = $query->fetchAll( PDO::FETCH_ASSOC );

	if( $query !== FALSE) {
		$count = 0;

		foreach($customers as $customer) {
			$output[$count] 								= $customer;
			$output[$count]['checkbox']						= '<center><input type="checkbox" class="chk" id="checkbox_'.$customer['id'].'" name="customer_ids[]" value="'.$customer['id'].'" onclick="multi_options();"></center>';
			
			if( $customer['status'] == 'enabled') {
				$output[$count]['status'] 					= '<span class="label label-success full-width" style="width: 100%;">Enabled</span>';
			}elseif( $customer['status'] == 'disabled') {
				$output[$count]['status']					= '<span class="label label-danger full-width" style="width: 100%;">Disabled</span>';
			}elseif( $customer['status'] == 'expired') {
				$output[$count]['status'] 					= '<span class="label label-danger full-width" style="width: 100%;">Expired</span>';
			}else{
				$output[$count]['status'] 					= '<span class="label label-warning full-width" style="width: 100%;">'.ucfirst($customer['status']).'</span>';
			}

			$output[$count]['username'] 					= stripslashes($customer['username']) . ' <span class="hidden"><br>'.stripslashes($customer['notes']).'</span>';

			$output[$count]['full_name']					= stripslashes( $customer['first_name'].' '.$customer['last_name'] );

			if( $customer['expire_date'] == '1970-01-01' ) {
				$output[$count]['expire_date']				= 'Unlimited';
			}else{
				$output[$count]['expire_date'] 				= $customer['expire_date'];
			}

			$used_connections = 0;
			foreach($connections as $connection) {
				if( $connection['customer_id'] == $customer['id']) {
					$used_connections++;
				}
			}

			$output[$count]['connections'] 					= $used_connections . ' / ' . $customer['max_connections'];

			// get reseller info
			$output[$count]['owner'] 						= 'Main Account';
			foreach($resellers as $reseller) {
				if( $reseller['id'] == $customer['reseller_id']) {
					if(!empty($reseller['first_name']) ) {
						$output[$count]['owner']				= stripslashes($reseller['first_name']).' '.stripslashes($reseller['last_name'] );
					}elseif(!empty($reseller['email']) ) {
						$output[$count]['owner']				= stripslashes($reseller['email'] );
					}else{
						$output[$count]['owner']				= stripslashes($reseller['username'] );
					}
					break;
				}
			}

			$output[$count]['actions'] 						= '
				<button title="Customer Line / Playlist Download" type="button" class="btn btn-primary btn-flat btn-xs" data-toggle="modal" data-target="#customer_line" onclick="get_customer_line('.$customer['id'].')"><i class="fa fa-download" aria-hidden="true"></i></button>
				
				<a title="View / Edit" class="btn btn-info btn-flat btn-xs" href="dashboard.php?c=customer&customer_id='.$customer['id'].'"><i class="fa fa-gears"></i></a>

				<a title="Delete" class="btn btn-danger btn-flat btn-xs" onclick="return confirm(\'Are you sure?\')" href="actions.php?a=customer_delete&customer_id='.$customer['id'].'"><i class="fa fa-times"></i></a>';

			$output[$count]['admin_notes']					= '<span class="">'.stripslashes($customer['notes']).'</span>';
			$output[$count]['admin_notes_hidden']			= '<span class="hidden">'.stripslashes($customer['notes']).'</span>';

			$output[$count]['reseller_notes']				= '<span class="">'.stripslashes($customer['reseller_notes']).'</span>';
			$output[$count]['reseller_notes_hidden']		= '<span class="hidden">'.stripslashes($customer['reseller_notes']).'</span>';

			// $output[$count]['source_m3u'] 		= 'http://'.$global_settings['cms_access_url_raw'].':'.$global_settings['cms_port'].'/get.php?username='.$customer['username'].'&password='.$customer['password'].'&type=m3u&output=ts';
			// $output[$count]['source_m3u8'] 		= 'http://'.$global_settings['cms_access_url_raw'].':'.$global_settings['cms_port'].'/get.php?username='.$customer['username'].'&password='.$customer['password'].'&type=m3u_plus&output=ts';
			// $output[$count]['source_dreambox'] 	= 'http://'.$global_settings['cms_access_url_raw'].':'.$global_settings['cms_port'].'/get.php?username='.$customer['username'].'&password='.$customer['password'].'&type=dreambox&output=ts';
			// $output[$count]['source_webtv'] 	= 'http://'.$global_settings['cms_access_url_raw'].':'.$global_settings['cms_port'].'/get.php?username='.$customer['username'].'&password='.$customer['password'].'&type=webtv&output=ts';
			// $output[$count]['source_octagon'] 	= 'http://'.$global_settings['cms_access_url_raw'].':'.$global_settings['cms_port'].'/get.php?username='.$customer['username'].'&password='.$customer['password'].'&type=octagan&output=ts';
			// $output[$count]['source_enigma_autoscript'] 	= "wget -O /etc/enigma2/iptv.sh 'http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/get.php?username=".$customer['username']."&password=".$customer['password']."&type=enigma22_script&output=ts' && chmod 777 /etc/enigma2/iptv.sh && ";

			$count++;
		}

		// $json_out = json_encode(array_values($your_array_here) );

		// $output = array_values($output);
		// $data['data'] = $output;

		if(isset( $output) ) {
			$data['data'] = array_values($output);
		}else{
			$data['data'] = array();
		}

		if(get( 'dev') == 'yes' ) {
			$data['dev'] = $dev;
		}

		json_output( $data );
	}
}

function ajax_http_proxy()
{
	$data 			= '';
	$ip_address 	= get( 'ip_address' );
	$port 			= get( 'port' );

	$url = 'http://'.$ip_address.':'.$port.'/system_stats.php';
	$data = @file_get_contents( $url );

	echo $data;
}

function http_proxy()
{
	$data 			= '';
	$remote_file 	= get( 'remote_file' );
	$remote_file 	= base64_decode( $remote_file );

	$data 			= @file_get_contents( $remote_file );

	echo $data;
}

function accept_terms()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$update = $conn->exec( "UPDATE `global_settings` SET `config_value` = 'yes' WHERE `config_name` = 'cms_terms_accepted' " );

	status_message( "success", "Terms &amp; Conditions accepted." );
    go( $_SERVER['HTTP_REFERER'] );
}

function license_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$license 				= post( 'license' );
	$license 				= trim( $license);

	// check for dupes
	$query          		= $conn->query( "SELECT `id` FROM `licenses` WHERE `license` = '".$license."' " );
    $existing_license       = $query->fetch( PDO::FETCH_ASSOC );
    if( isset( $existing_license['id'] ) ) {
    	status_message( "danger", "Duplicate license found." );
    	go( $_SERVER['HTTP_REFERER'] );
    } else {
    	// get the license stats
		$license_stats 			= take_medication( $license );

		$insert = $conn->exec( "INSERT INTO `licenses` 
	        (`license`)
	        VALUE
	        ('".$license."')" );

		$license_id = $conn->lastInsertId();

		// set the status
		$update = $conn->exec( "UPDATE `licenses` SET `status` = '".$license_stats['status']."' 								WHERE `id` = '".$license_id."' " );

		if( isset( $license_stats['registeredname']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `registeredname` = '".$license_stats['registeredname']."' 			WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['email']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `email` = '".$license_stats['email']."' 								WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['serviceid']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `serviceid` = '".$license_stats['serviceid']."' 						WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['productid']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `productid` = '".$license_stats['productid']."' 						WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['productname']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `productname` = '".$license_stats['productname']."' 					WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['regdate']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `regdate` = '".$license_stats['regdate']."' 							WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['nextduedate']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `nextduedate` = '".$license_stats['nextduedate']."' 					WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['billingcycle']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `billingcycle` = '".$license_stats['billingcycle']."' 				WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['validdomain']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `validdomain` = '".$license_stats['validdomain']."' 					WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['validip']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `validip` = '".$license_stats['validip']."' 							WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['validdirectory']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `validdirectory` = '".$license_stats['validdirectory']."' 			WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['md5hash']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `md5hash` = '".$license_stats['md5hash']."' 							WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['validdirectory']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `validdirectory` = '".$license_stats['validdirectory']."' 			WHERE `id` = '".$license_id."' " );
		}
		if( isset( $license_stats['localkey']) ) {
			$update = $conn->exec( "UPDATE `licenses` SET `localkey` = '".$license_stats['localkey']."' 						WHERE `id` = '".$license_id."' " );
		}
    }
    
	// log_add( "Stream Category has been added." );
	status_message( "success", "License has been added." );
    go( $_SERVER['HTTP_REFERER'] );
}

function license_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->query( "DELETE FROM `licenses` WHERE `id` = '".$id."' " );
	
	status_message( "success", "License has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function addon_license_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$license 				= post( 'license' );
	$license 				= trim( $license);

	error_log($license);

	$insert = $conn->exec( "INSERT INTO `addon_licenses` 
        (`product`,`license`)
        VALUE
        ('','".$license."')" );
    
	// log_add( "Stream Category has been added." );
	status_message( "success", "Addon License '".$license."' has been added." );
    go( $_SERVER['HTTP_REFERER'] );
}

function addon_license_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$license = get( 'license' );

	if(empty($license) ) {
		status_message( "danger", "License was not present." );
	}else{
		$delete = $conn->query( "DELETE FROM `addon_licenses` WHERE `id` = '".$license."' " );
		status_message( "success", "Addon License has been deleted." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function xc_import_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->query( "DELETE FROM `xc_import_jobs` WHERE `id` = '".$id."' " );
	status_message( "success", "Xtream-Codes Import has been deleted." );

	go( $_SERVER['HTTP_REFERER'] );
}

function customer_mag_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
		
	$mac_address 		= post( 'mac_address' );

	// sanity check
	if( false === filter_var( post( 'mac_address' ), FILTER_VALIDATE_MAC ) ) {
	    status_message( "danger", "Invalid MAC address given." );
	} else {
		$mac_address 		= base64_encode( $mac_address );

		$name 				= post( 'name' ); 
		$customer_id 		= post( 'id' );

		// check if mac is already in use
		$query = $conn->query( "SELECT `mag_id` FROM `mag_devices` WHERE `mac` = '".$mac_address."' " );
		$existing_mag = $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $existing_mag['mag_id'] ) ) {
			status_message( "danger", "MAC address is already linked to an account." );
		}else{
			$insert = $conn->exec( "INSERT INTO `mag_devices` 
		        (`customer_id`,`name`,`mac`)
		        VALUE
		        ('".$customer_id."',
		        '".$name."',
		        '".$mac_address."'
		    )" );

			status_message( "success", "MAG Device has been added." );
		}
	}
	go( $_SERVER['HTTP_REFERER'] );
}

function customer_mag_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$mag_id 			= post( 'mag_id' );

	$mac_address 		= addslashes( $_POST['mac_address'] );
	$mac_address 		= trim( $mac_address);
	$mac_address 		= base64_encode($mac_address);

	$customer_id 		= addslashes( $_POST['customer_id'] );
	$customer_id 		= trim( $customer_id);

	$update = $conn->exec( "UPDATE `mag_devices` SET `customer_id` = '".$customer_id."' 			WHERE `mag_id` = '".$mag_id."' " );
	$update = $conn->exec( "UPDATE `mag_devices` SET `mac` = '".$mac_address."' 					WHERE `mag_id` = '".$mag_id."' " );

	status_message( "success", "MAG Device has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function customer_mag_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$mag_id = get( 'id' );

	$update = $conn->exec( "DELETE FROM `mag_devices` WHERE `mag_id` = '".$mag_id."' " );
	
    // log_add( "Customer account has been deleted." );
    status_message( "success", "MAG Device has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function backup_now()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$date = date( "Y-m-d_H:i", time() );
	
	exec( "mkdir -p /opt/stiliam-backups" );
	exec( "sudo chmod 777 /opt/stiliam-backups" );

	shell_exec( "mysqldump -u stiliam -pstiliam1984 cms | gzip > /opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" );

	if( file_exists( "/opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" ) ) {
		status_message( "success", "Database backup has been created." );
	}else{
		status_message( "danger", "Database backup has failed." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function backup_restore()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$filename = get( "file" );

	exec( "sudo gunzip -k /opt/stiliam-backups/".$filename );

	$filename = str_replace( ".gz", "", $filename );
	
	exec( "sudo mysql -ustiliam -pstiliam1984 cms < /opt/stiliam-backups/".$filename );

	exec( "sudo rm -rf /opt/stiliam-backups/".$filename );

	log_add( 'backup_restore', 'CMS was stored from the backup file named '.$filename );

	status_message( "success", "Database backup has been restored, reboot your CMS now." );
	go( $_SERVER['HTTP_REFERER'] );
}

function backup_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$filename = get( "file" );

	if( file_exists( "/opt/stiliam-backups/".$filename ) ) {
		shell_exec( "sudo rm -rf /opt/stiliam-backups/".$filename );
		status_message( "success", "Database backup has been deleted." );
	}else{
		status_message( "danger", "Database backup has failed to delete." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function backup_download()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$filename = get( "file" );

	if( file_exists( "/opt/stiliam-backups/".$filename ) ) {
	    header( "Content-Description: File Transfer" );
	    header( "Content-Type: application/octet-stream" );
	    header( "Content-Disposition: attachment; filename=".$filename );
	    header( "Expires: 0" );
	    header( "Cache-Control: must-revalidate" );
	    header( "Pragma: public" );
	    header( "Content-Length: ".filesize( "/opt/stiliam-backups/".$filename ) );
	    ob_clean();
	    flush();
	    readfile( "/opt/stiliam-backups/".$filename);
	    exit;
	}
}

function allowed_ip_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
		
	$ip_address 			= post( 'ip_address' );
	$notes 					= post( 'notes' );

	$insert = $conn->exec( "INSERT INTO `rtmp_allowed_ips` 
        (`ip_address`,`notes`)
        VALUE
        ('".$ip_address."', '".$notes."'
    )" );

	status_message( "success", "Allowed IP has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function allowed_ip_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `rtmp_allowed_ips` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
    status_message( "success", "Allowed IP has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function package_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
		
	$name 				= post( 'name' );

	$insert = $conn->exec( "INSERT INTO `packages` 
        (`name`)
        VALUE
        ('".$name."'
    )" );

    $package_id = $conn->lastInsertId();

	status_message( "success", "Package has been added." );
	go( "dashboard.php?c=package&id=".$package_id);
}

function package_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 				= post( 'id' );
	$name 				= post( 'name' );
	$credits 			= post( "credits" );
	$official_duration 	= post( "official_duration" );
	$is_trial 			= post( "is_trial" );
	$trial_duration 	= post( "trial_duration" );

	$update = $conn->exec( "UPDATE `packages` SET `name` = '".$name."' 								WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `packages` SET `credits` = '".$credits."' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `packages` SET `official_duration` = '".$official_duration."' 	WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `packages` SET `is_trial` = '".$is_trial."' 						WHERE `id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `packages` SET `trial_duration` = '".$trial_duration."' 			WHERE `id` = '".$id."' " );

	status_message( "success", "Package has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function package_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$update = $conn->exec( "UPDATE `customers` SET `package_id` = '1' WHERE `package_id` = '".$id."' " );

	$delete = $conn->exec( "DELETE FROM `packages` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
    status_message( "success", "Package has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function package_content_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 		= post( 'id' );
	
	if( isset( $_POST['contents'] ) ) {
		$contents 		= $_POST['contents'];
		$contents 		= implode( ',', $contents );

		$update = $conn->exec( "UPDATE `packages` SET `bouquets` = '".$contents."' WHERE `id` = '".$id."' " );
	}

	status_message( "success", "Package contents have been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_category_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$name 				= post( 'name' );

	$insert = $conn->exec( "INSERT INTO `vod_categories` (`name`) VALUE ('".$name."' )" );
    
	status_message( "success", "Movies VoD Category has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_category_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 		= post( 'id' );
	$name 		= post( 'name' );

	// reset category_id back to default
	$update = $conn->query( "UPDATE `vod_categories` SET `name` = '".$name."' WHERE `id` = '".$id."' " );

	status_message( "success", "Movies VoD Category has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_category_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// reset category_id back to default
	$query = $conn->query( "UPDATE `vod` SET `category_id` = '1' WHERE `category_id` = '".$id."' " );

	// delete primary record
	$query = $conn->query( "DELETE FROM `vod_categories` WHERE `id` = '".$id."' " );

	status_message( "success", "Movies VoD Category has been deleted." );
	go( 'dashboard.php?c=vod_categories' );
}

function vod_tv_category_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$name 				= post( 'name' );

	$insert = $conn->exec( "INSERT INTO `vod_tv_categories` (`name`) VALUE ('".$name."' )" );
    
	status_message( "success", "TV VoD Category has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_category_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 		= post( 'id' );
	$name 		= post( 'name' );

	// reset category_id back to default
	$update = $conn->query( "UPDATE `vod_tv_categories` SET `name` = '".$name."' WHERE `id` = '".$id."' " );

	status_message( "success", "TV VoD Category has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_category_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// reset category_id back to default
	$query = $conn->query( "UPDATE `vod_tv` SET `category_id` = '1' WHERE `category_id` = '".$id."' " );

	// delete primary record
	$query = $conn->query( "DELETE FROM `vod_tv_categories` WHERE `id` = '".$id."' " );

	status_message( "success", "TV VoD Category has been deleted." );
	go( 'dashboard.php?c=vod_tv_categories' );
}

function customer_ip_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$id 			= post( 'id' );
	$ip_address 	= post( 'ip_address' );

	$insert = $conn->exec( "INSERT INTO `customers_ips` 
        (`customer_id`,`ip_address`)
        VALUE
        ('".$id."',
        '".$ip_address."'
    )" );
    
	status_message( "success", "Customer IP Address has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function customer_ip_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$query = $conn->query( "DELETE FROM `customers_ips` WHERE `id` = '".$id."' " );

	status_message( "success", "Customer IP Address has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function tv_series_episode_delete_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	// get all episodes to delete from bouquets_content
	$query = $conn->query( "SELECT `id` FROM `tv_series_files` WHERE `tv_series_id` = '".$id."' " );
	$tv_series_files = $query->fetchAll( PDO::FETCH_ASSOC );
	
	// remove from bouquets_content
	foreach($tv_series_files as $tv_series_file) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `content_id` = '".$tv_series_file['id']."' " );
	}

	$delete = $conn->exec( "DELETE FROM `tv_series_files` WHERE `tv_series_id` = '".$id."' " );
	
    status_message( "success", "All Series Episodes have been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function grab_metadata() {
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id 					= get( 'id' );
	$type 					= get( 'type' );

	// get data for media type
	if( $type == 'tv_series' ) {
		$query 				= $conn->query( "SELECT `name` FROM `tv_series` WHERE `id` = '".$id."' " );
		$media 				= $query->fetch( PDO::FETCH_ASSOC );

		// sanity check
		if(empty($media) ) {
			status_message( "danger", "Unable to find the TV Series, something went really wrong. " );
		}else{
			$media['name']		= stripslashes($media['name'] );

			// query omdbapi.com for metadata
			$metadata 				= get_metadata($media['name'] );
			if( $metadata['status'] == 'match' ) {
				if(isset( $metadata['Title']) ) {
		        	$name           		= addslashes( $metadata['name'] );
		        }
		        if(isset( $metadata['description']) ) {
		        	$description       		= addslashes( $metadata['description'] );
		        }
				if(isset( $metadata['cover_photo']) ) {
		        	$cover_photo           	= addslashes( $metadata['cover_photo'] );
		        }
		        if(isset( $metadata['Rated']) ) {
		        	$rating           		= addslashes( $metadata['Rated'] );
		        }

		        // update the metadata
		        $update = $conn->exec( "UPDATE `tv_series` SET `description` = '".$description."' 		WHERE `id` = '".$id."' " );
		        $update = $conn->exec( "UPDATE `tv_series` SET `cover_photo` = '".$cover_photo."' 		WHERE `id` = '".$id."' " );
		        $update = $conn->exec( "UPDATE `tv_series` SET `rating` = '".$rating."' 					WHERE `id` = '".$id."' " );

		        status_message( "success", "metadata for '".$media['name']."' has been updated" );
			}else{
				status_message( "danger", "Unable to find metadata for '".$media['name']."'" );
			}
		}
	}

	if( $type == '247_channel' ) {
		$query 				= $conn->query( "SELECT `name` FROM `channels` WHERE `id` = '".$id."' " );
		$media 				= $query->fetch( PDO::FETCH_ASSOC );

		// sanity check
		if(empty($media) ) {
			status_message( "danger", "Unable to find the 24/7 channel, something went really wrong. " );
		}else{
			$media['name']		= stripslashes($media['name'] );

			// query omdbapi.com for metadata
			$metadata 				= get_metadata($media['name'] );
			if( $metadata['status'] == 'match' ) {
				if(isset( $metadata['Title']) ) {
		        	$name           		= addslashes( $metadata['name'] );
		        }
		        if(isset( $metadata['description']) ) {
		        	$description       		= addslashes( $metadata['description'] );
		        }
				if(isset( $metadata['cover_photo']) ) {
		        	$cover_photo           	= addslashes( $metadata['cover_photo'] );
		        }

		        // update the metadata
		        $update = $conn->exec( "UPDATE `channels` SET `description` = '".$description."' 		WHERE `id` = '".$id."'; " );
		        $update = $conn->exec( "UPDATE `channels` SET `cover_photo` = '".$cover_photo."' 		WHERE `id` = '".$id."'; " );

		        status_message( "success", "metadata for '".$media['name']."' has been updated" );
			}else{
				status_message( "danger", "Unable to find metadata for '".$media['name']."'" );
			}
		}
	}
	
	go( $_SERVER['HTTP_REFERER'] );
}

function delete_all()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$type = get( 'type' );

	if( $type == 'channels' ) {
		$truncate = $conn->exec( "TRUNCATE TABLE channels_files;" );
		$truncate = $conn->exec( "TRUNCATE TABLE channels;" );
    	status_message( "success", "All Channels have been deleted." );
    }
    if( $type == 'vod' ) {
		$truncate = $conn->exec( "TRUNCATE TABLE vod;" );
    	status_message( "success", "All Video on Demand movies have been deleted." );
    }
    if( $type == 'tv_series' ) {
		$truncate = $conn->exec( "TRUNCATE TABLE tv_series;" );
		$truncate = $conn->exec( "TRUNCATE TABLE tv_series_files;" );
    	status_message( "success", "All TV Series have been deleted." );
    }
    if( $type == 'streams' ) {
		$truncate = $conn->exec( "TRUNCATE TABLE streams;" );
    	status_message( "success", "All Streams have been deleted." );
    }
	go( $_SERVER['HTTP_REFERER'] );
}

function stream_source_add() {
	global $conn, $site;

	$stream_id 			= post( 'stream_id' );
	$new_source_url 	= post( 'source_url' );

	// get existing source urls
	$query = $conn->query( "SELECT `source` FROM `streams` WHERE `id` = '".$stream_id."' " );
	$stream = $query->fetch( PDO::FETCH_ASSOC );

	$sources = explode( ",", $stream['source'] );

	$sources[] = $new_source_url;

	$sources_array = implode( ",", $sources);

	$update = $conn->exec( "UPDATE `streams` SET `source` = '".$sources_array."' 		WHERE `id` = '".$stream_id."' " );

	status_message( "success", "Stream source has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function stream_source_update() {
	global $conn, $site;

	$stream_id 			= post( 'stream_id' );
	$sources 			= post( 'sources' );
	$sources 			= array_filter($sources);
	$sources 			= implode( ",", $sources);

	$update = $conn->exec( "UPDATE `streams` SET `source` = '".$sources."' WHERE `id` = '".$stream_id."' " );

	status_message( "success", "Stream sources have been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function epg_source_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
		
	$name 				= post( 'name');
	$source 			= post( 'source');
	$days_keep 			= post( 'days_keep');
	if( empty( $days_keep ) ) {
		$days_keep = 7;
	}
	$time_offset 		= post( 'time_offset');
	if( empty( $time_offset ) ) {
		$time_offset = '0000';
	}

	$etag 				= '"'.random_string( 8 ).'-'.random_string( 6 ).'"';

	// check if epg source is already added
	$query = $conn->query( "SELECT `id` FROM `epg` WHERE `source` = '".$source."' " );
	$existing_epg_source = $query->fetch( PDO::FETCH_ASSOC );
	if(isset( $existing_epg_source['id']) ) {
		status_message( "danger", "EPG Source '".$source."' is a duplicate." );
	}else{
		$insert = $conn->exec( "INSERT INTO `epg` 
	        (`name`,`source`,`time_offset`,`days_keep`,`etag`)
	        VALUE
	        ('".$name."',
	        '".$source."',
	        '".$time_offset."',
	        '".$days_keep."',
	        '".$etag."'
	    )" );

	    $epg_source_id = $conn->lastInsertId();

		status_message( "success", "EPG Source has been added." );
	}
	go( $_SERVER['HTTP_REFERER'] );
}

function epg_source_update()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	status_message( "success", "EPG Source has been updated." );
	go( $_SERVER['HTTP_REFERER'] );
}

function epg_source_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `epg_xml_ids` WHERE `epg_source_id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `epg` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
    status_message( "success", "EPG Source has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function force_epg_update()
{
	shell_exec( 'sudo sh /var/www/html/portal/scripts/ministra_epg_update.sh' );

	status_message( "success", "Force EPG complete." );
	go( $_SERVER['HTTP_REFERER'] );
}

function backup_upload()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$user_id 					= $_SESSION['account']['id'];

	$fileName = $_FILES["file1"]["name"]; // The file name
	
	$fileName = str_replace('"', '', $fileName);
	$fileName = str_replace( "'", '', $fileName);
	$fileName = str_replace(' ', '_', $fileName);
	$fileName = str_replace(array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '+', ';', ':', '\\', '|', '~', '`', ',', '<', '>', '/', '?', '§', '±',), '', $fileName);
	// $fileName = $fileName . '.' . $fileExt;
	
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if (!$fileTmpLoc) { // if file not chosen
		echo "Please select a file to upload.";
	}
	
	// check if folder exists for customer, if not create it and continue
	exec( "sudo mkdir -p /opt/stiliam-backups" );
	
	// handle the uploaded file
	if(move_uploaded_file($fileTmpLoc, "/opt/stiliam-backups/".$fileName) ) {

		// report
		echo "<font color='#18B117'><b>Upload Complete.</b></font>";
	}else{
		echo "ERROR: Oops, something went very wrong. Please try again or contact support for more help.";
		exit();
	}	
}

function addon_install()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$addon = get( 'addon' );

	if( $addon == 'live_fta_streams' ) {
		shell_exec( "sudo wget -O /tmp/fta_streams.sql http://slipstreamiptv.com/downloads/addon_live_fta_streams.sql" );
		shell_exec( "sudo mysql -uslipstream -padmin1372 slipstream_cms < /tmp/fta_streams.sql" );

		$delete = $conn->query( "DELETE FROM `addon_fta_streams` WHERE `status` = 'stream_not_found' " );
	}

	status_message( "success", "Addon installed." );
	go( $_SERVER['HTTP_REFERER'] );
}

function ajax_fta_streams()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$count = 0;

	header( "Content-Type:application/json; charset=utf-8" );

	$addons = addons_check();

	if(isset( $addons[70]) && $addons[70]['status'] == 'enable' ) {
		// lets get the good stuff
		$query 			= $conn->query( "SELECT * FROM `addon_fta_streams` " );
		$fta_streams 	= $query->fetchAll( PDO::FETCH_ASSOC );

		$count = 0;

		foreach($fta_streams as $fta_stream) {
			$output[$count] 							= $fta_stream;

			if(empty($output[$count]['quality']) ) {
				$output[$count]['quality']				= 'N/A';
			}else{
				$output[$count]['quality'] 				= strtoupper( $output[$count]['quality'] );
			}

			if(empty($output[$count]['fps']) ) {
				$output[$count]['fps']					= 'N/A';
			}else{
				$output[$count]['fps'] 					= strtoupper( $output[$count]['fps'] );
			}

			if(empty($output[$count]['audio_codec']) ) {
				$output[$count]['audio_codec']			= 'N/A';
			}else{
				$output[$count]['audio_codec'] 			= strtoupper( $output[$count]['audio_codec'] );
			}

			if(empty($output[$count]['video_codec']) ) {
				$output[$count]['video_codec']			= 'N/A';
			}else{
				$output[$count]['video_codec'] 			= strtoupper( $output[$count]['video_codec'] );
			}

			$output[$count]['actions']					= '<button title="Watch Stream" type="button" class="btn btn-primary btn-flat btn-xs" data-toggle="modal" data-target="#web_player" onclick="new_web_player_iframe_source_fta('.$fta_stream['id'].')"><i class="fa fa-tv" aria-hidden="true"></i></button>';
			
			$count++;
		}
	}

	if(isset( $output) ) {
		$data['data'] = array_values($output);
	}else{
		$data['data'] = array();
	}

	if(get( 'dev') == 'yes' ) {
		$data['dev'] = $dev;
	}

	json_output( $data );
}

function customer_add_credit()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$customer_id 		= get( 'customer_id' );
	$credits 			= get( 'credits' );

	if( $credits == 0 ) {
		status_message( "danger", "Please select the amount of credits to assign to this customer." );
		go( $_SERVER['HTTP_REFERER'] );
	}

	// check reseller current balance
	if( $_SESSION['account']['type'] == 'reseller') {
        $query 		= $conn->query( "SELECT `credits` FROM `resellers` WHERE `id` = '".$_SESSION['account']['id']."' " );
        $reseller 	= $query->fetch( PDO::FETCH_ASSOC );

        if( $reseller['credits'] >= $credits ) {
        	$new_reseller_balance = ( $reseller['credits'] - $credits );
        	$update = $conn->exec( "UPDATE `resellers` SET `credits` = '".$new_reseller_balance."' 	WHERE `id` = '".$_SESSION['account']['id']."' " );
        } else {
        	status_message( "danger", "You don't have enough credits to perform this action. Please purchase additional credits." );
			go( $_SERVER['HTTP_REFERER'] );
        }
    }

	// get existing credits
	$query = $conn->query( "SELECT `credits` FROM `customers` WHERE `id` = '".$customer_id."' " );
	$customer = $query->fetch( PDO::FETCH_ASSOC );

	$new_customer_balance = ( $customer['credits'] + $credits );

	$update = $conn->exec( "UPDATE `customers` SET `credits` = '".$new_customer_balance."' 	WHERE `id` = '".$customer_id."' " );
	
    // log_add( "Customer account has been deleted." );
    status_message( "success", "Credits have been added to customers account." );
	go( $_SERVER['HTTP_REFERER'] );
}

function server_install_progress()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$query = $conn->query( "SELECT `install_progress` FROM `servers` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    $progress = $data['install_progress'];

    echo $progress;
}

function monitored_folder_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$folder_type 		= post( 'folder_type' );
	$server_id			= post( 'server_id' );
	if(empty( $server_id) || $server_id == 0) {

		status_message( "danger", "You must select a server." );
		go( $_SERVER['HTTP_REFERER'] );
	}
	
	$folder 			= post( 'folder' );

	// add input stream
	$insert = $conn->exec( "INSERT INTO `".$folder_type."_monitored_folders` 
        (`server_id`,`folder`)
        VALUE
        ('".$server_id."',
        '".$folder."'
    )" );

    $server = server_details( $server_id );

    if( $folder_type == 'channels_247' ) {
    	log_add( 'channels_247_monitored_folder_add', '24/7 Channel Monitored Monitored: "'.$folder.'" was added on server "'.$server['name'].'"' );
    	status_message( "success", "24/7 Channels Monitored Folder has been added." );
    }
    if( $folder_type == 'vod' ) {
    	log_add( 'vod_monitored_folder_add', 'Movies VoD Monitored Monitored: "'.$folder.'" was added on server "'.$server['name'].'"' );
    	status_message( "success", "24/7 Channels Monitored Folder has been added." );
    }
    if( $folder_type == 'vod_tv' ) {
    	log_add( 'vod_tv_monitored_folder_add', 'TV VoD Monitored Monitored: "'.$folder.'" was added on server "'.$server['name'].'"' );
    	status_message( "success", "24/7 Channels Monitored Folder has been added." );
    }
    
	go( $_SERVER['HTTP_REFERER'] );
}

function channels_247_monitored_folder_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `channels_247_monitored_folders` WHERE `id` = '".$id."' " );
	
    // log_add( "Customer account has been deleted." );
	status_message( "success", "24/7 Channels Monitored Folder has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_247_delete()
{
	global $conn, $global_settings;

	$id = get( 'id' );

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'channel_247' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' AND `content_id` = '".$id."' " );
	}

	$delete = $conn->exec( "DELETE FROM `channels_247_files` WHERE `vod_id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `channels_247` WHERE `id` = '".$id."' " );
	
	// delete from stalker
	// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '247".$id."' " );

    // log_add("Channel has been deleted." );
    status_message( 'success',"24/7 Channel has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channels_247_delete_all()
{
	global $conn, $global_settings;

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'channel_247' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' " );
	}

	$delete = $conn->exec( "DELETE FROM `channels_247_files` " );
	$delete = $conn->exec( "DELETE FROM `channels_247` " );
	
	// delete from stalker
	// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '247".$id."' " );

    // log_add("Channel has been deleted." );
    status_message( 'success',"All 24/7 Channels have been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_delete_all()
{
	global $conn, $global_settings;

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'vod' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' " );
	}

	$delete = $conn->exec( "DELETE FROM `vod_files` " );
	$delete = $conn->exec( "DELETE FROM `vod` " );
	
	// delete from stalker
	// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '247".$id."' " );

    // log_add("Channel has been deleted." );
    status_message( 'success',"All Movies VoD have been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function vod_tv_delete_all()
{
	global $conn, $global_settings;

	// find bouquets to filter for content to be removed
	$query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `type` = 'vod_tv' " );
	$bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over bouquets and delete for this vod item
	foreach( $bouquets as $bouquet ) {
		$delete = $conn->exec( "DELETE FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' " );
	}

	$delete = $conn->exec( "DELETE FROM `vod_tv_files` " );
	$delete = $conn->exec( "DELETE FROM `vod_tv` " );
	
	// delete from stalker
	// $delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '247".$id."' " );

    // log_add("Channel has been deleted." );
    status_message( 'success',"All TV VoD have been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function playlist_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$playlist_name = get( 'playlist_name' );

	$path = "playlist_manager/";
    
    foreach( $_FILES as $key ) {
        if( $key['error'] == UPLOAD_ERR_OK ){
            $name = $key['name'];
            $temp = $key['tmp_name'];
            $size = ($key['size'] / 1000)."KB";
            move_uploaded_file( $temp, $path . $name );
            echo '
                <div>
                    <h12><strong>File Name:</strong> '.$name.' - <font color="green">Uploaded</font></h2><br />
                </div>
			';
            
            // parse the playlist
            $m3ufile = file_get_contents( $path . $name );

			$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$))/';
			$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';

			preg_match_all( $re, $m3ufile, $matches );

			$i = 1;

			$items = array();

			foreach( $matches[0] as $list ) {	   
				preg_match( $re, $list, $matchList );

			   	$mediaURL = preg_replace( "/[\n\r]/", "", $matchList[3] );
			   	$mediaURL = preg_replace( '/\s+/', '', $mediaURL );

			   	$newdata =  array (
			    	//'ATTRIBUTE' => $matchList[2],
			    	'id' => $i++,
			    	'tvg-name' => $matchList[2],
			    	'media' => $mediaURL
			    );
			    
			    preg_match_all( $attributes, $list, $matches, PREG_SET_ORDER );
			    
			    foreach( $matches as $match ) {
					$newdata[$match[1]] = $match[2];
			    }

			    if( empty( $newdata['tvg-name'] ) ) {
					// $newdata['tvg-name'] = str_replace( "'", '', $matchList[2] );
			    	$temp_name = $matchList[2];
			    	$temp_name = str_replace("'", '', $temp_name );
			    	$temp_name = str_replace('"', "", $temp_name );
					$newdata['tvg-name'] = $temp_name;
				}

				if( isset( $newdata['tvg-id'] ) ) {
					if( $newdata['tvg-id'] == 'id N/A' || $newdata['tvg-id'] == 'N/A' || $newdata['tvg-id'] == ' ') {
						$newdata['tvg-id'] = '';
					}
				}

				$items[] = $newdata;
			}

			$data = json_encode( $items );
            
            // insert into db
            $insert = $conn->exec( "INSERT IGNORE INTO `playlist_manager` (`name`,`file`,`data`) VALUE ('".$playlist_name."','".$name."','".$data."')" );
        }else{
            echo $key['error'];
        }
    }
}

function playlist_delete()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );

	$delete = $conn->exec( "DELETE FROM `playlist_manager` WHERE `id` = '".$id."' " );
	
    status_message( "success", "Playlist has been deleted." );
	go( $_SERVER['HTTP_REFERER'] );
}

function playlist_inspector()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	$id = get( 'id' );
	$status = get( 'status' );

	$update = $conn->exec( "UPDATE `playlist_manager` SET `inspector` = '".$status."' WHERE `id` = '".$id."' " );

	if( $status == 'enable' ) {	
	    status_message( "success", "Playlist Inspector has been enabled." );
	} else {
		$update = $conn->exec( "UPDATE `playlist_manager` SET `updated` = '' WHERE `id` = '".$id."' " );
		$delete = $conn->exec( "DELETE FROM `playlist_manager_content` WHERE `playlist_id` = '".$id."' " );

		status_message( "success", "Playlist Inspector has been disabled." );
	}

	go( $_SERVER['HTTP_REFERER'] );
}

function fta_playlist_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	// set playlists
	$playlists[0]['name'] 	= 'FTA Argentina Channels';
	$playlists[0]['url'] 	= 'https://iptv-org.github.io/iptv/countries/ar.m3u';
	$playlists[1]['name'] 	= 'FTA Austrailia Channels';
	$playlists[1]['url'] 	= 'https://iptv-org.github.io/iptv/countries/au.m3u';
	$playlists[2]['name'] 	= 'FTA Brazil Channels';
	$playlists[2]['url'] 	= 'https://iptv-org.github.io/iptv/countries/br.m3u';
	$playlists[3]['name'] 	= 'FTA Canada Channels';
	$playlists[3]['url'] 	= 'https://iptv-org.github.io/iptv/countries/ca.m3u';
	$playlists[4]['name'] 	= 'FTA China Channels';
	$playlists[4]['url'] 	= 'https://iptv-org.github.io/iptv/countries/cn.m3u';
	$playlists[5]['name'] 	= 'FTA France Channels';
	$playlists[5]['url'] 	= 'https://iptv-org.github.io/iptv/countries/fr.m3u';
	$playlists[6]['name'] 	= 'FTA Germany Channels';
	$playlists[6]['url'] 	= 'https://iptv-org.github.io/iptv/countries/de.m3u';
	$playlists[7]['name'] 	= 'FTA India Channels';
	$playlists[7]['url'] 	= 'https://iptv-org.github.io/iptv/countries/in.m3u';
	$playlists[8]['name'] 	= 'FTA Italy Channels';
	$playlists[8]['url'] 	= 'https://iptv-org.github.io/iptv/countries/it.m3u';
	$playlists[9]['name'] 	= 'FTA Mexico Channels';
	$playlists[9]['url'] 	= 'https://iptv-org.github.io/iptv/countries/mx.m3u';
	$playlists[10]['name'] 	= 'FTA Netherlands Channels';
	$playlists[10]['url'] 	= 'https://iptv-org.github.io/iptv/countries/nl.m3u';
	$playlists[11]['name'] 	= 'FTA Pakistan Channels';
	$playlists[11]['url'] 	= 'https://iptv-org.github.io/iptv/countries/pk.m3u';
	$playlists[12]['name'] 	= 'FTA Russia Channels';
	$playlists[12]['url'] 	= 'https://iptv-org.github.io/iptv/countries/ru.m3u';
	$playlists[13]['name'] 	= 'FTA Spain Channels';
	$playlists[13]['url'] 	= 'https://iptv-org.github.io/iptv/countries/es.m3u';
	$playlists[14]['name'] 	= 'FTA Ukraine Channels';
	$playlists[14]['url'] 	= 'https://iptv-org.github.io/iptv/countries/ua.m3u';
	$playlists[15]['name'] 	= 'FTA United Arab Emirates Channels';
	$playlists[15]['url'] 	= 'https://iptv-org.github.io/iptv/countries/ae.m3u';
	$playlists[16]['name'] 	= 'FTA United Kingdom Channels';
	$playlists[16]['url'] 	= 'https://iptv-org.github.io/iptv/countries/uk.m3u';
	$playlists[17]['name'] 	= 'FTA United States Channels';
	$playlists[17]['url'] 	= 'https://iptv-org.github.io/iptv/countries/us.m3u';

	// regex
	$re = '/#EXTINF:(.+?)[,]\s?(.+?)[\r\n]+?((?:https?|rtmp):\/\/(?:\S*?\.\S*?)(?:[\s)\[\]{};"\'<]|\.\s|$))/';
	$attributes = '/([a-zA-Z0-9\-]+?)="([^"]*)"/';

	// loop over playlists
	foreach( $playlists as $playlist ) {
		// parse the playlist
       	$m3ufile = @file_get_contents( $playlist['url'] );

       	if( !empty( $m3ufile ) ) {
			preg_match_all( $re, $m3ufile, $matches );

			$i = 1;

			$items = array();

			foreach( $matches[0] as $list ) {	   
				preg_match( $re, $list, $matchList );

			   	$mediaURL = preg_replace( "/[\n\r]/", "", $matchList[3] );
			   	$mediaURL = preg_replace( '/\s+/', '', $mediaURL );

			   	$newdata =  array (
			    	//'ATTRIBUTE' => $matchList[2],
			    	'id' => $i++,
			    	'tvg-name' => $matchList[2],
			    	'media' => $mediaURL
			    );
			    
			    preg_match_all( $attributes, $list, $matches, PREG_SET_ORDER );
			    
			    foreach( $matches as $match ) {
					$newdata[$match[1]] = $match[2];
			    }

			    if( empty( $newdata['tvg-name'] ) ) {
					// $newdata['tvg-name'] = str_replace( "'", '', $matchList[2] );
			    	$temp_name = $matchList[2];
			    	$temp_name = str_replace("'", '', $temp_name );
			    	$temp_name = str_replace('"', "", $temp_name );
					$newdata['tvg-name'] = $temp_name;
				}

				$items[] = $newdata;
			}

			$data = json_encode( $items );
	        
	        // insert into db
	        $insert = $conn->exec( "INSERT IGNORE INTO `playlist_manager` (`name`,`file`,`data`) VALUE ('".$playlist['name']."','N/A','".$data."')" );
	    }
	}

	status_message( "success", "FTA Playlists have been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function add_playlist_item_to_live_channels()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$data 				= get( 'data' );
	$data 				= base64_decode( $data );
	$data 				= json_decode( $data, true );

	$title 				= addslashes( $data['tvg-name'] );

	$icon 				= addslashes( $data['tvg-logo'] );

	$category_id 		= 1;

	$epg_xml_id			= addslashes( $data['tvg-id'] );

	// add control channel
	$insert = $conn->exec( "INSERT INTO `channels` 
        (`title`,`job_status`,`ffmpeg_re`,`icon`,`category_id`,`epg_xml_id`)
        VALUE
        ('".$title."',
        'none',
        'no',
        '".$icon."',
        '".$category_id."',
        '".$epg_xml_id."'
    )" );

    $channel_id = $conn->lastInsertId();

    // add channel source
	$insert = $conn->exec( "INSERT INTO `channels_sources` 
        (`channel_id`,`source`)
        VALUE
        ('".$channel_id."',
        '".$data['media']."'
    )" );
    
    log_add( 'channel_add', 'Channel: '.$channel_id.' / "'.addslashes( $title ).'" was added.' );

	status_message( "success", "Live Channel has been added." );
	go( $_SERVER['HTTP_REFERER'] );
}

function channel_topology_profile_add()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;
	
	$name 				= post( 'name' );

	$insert = $conn->exec( "INSERT INTO `channels_topology_profiles` 
        (`name`)
        VALUE
        ('".$name."'
    )" );

    $id = $conn->lastInsertId();
    
	log_add( 'channel_topology_profile_add', 'Channel Topology Profile: "'.$name.'" was added.' );
	status_message( "success", "Channel Topology Profile has been added." );
	go( "dashboard.php?c=channel_topology_profile&id=".$id );
}

function prepare_to_deploy()
{
	global $whmcs, $site, $conn, $account_details, $global_settings;

	// make a backup that we will use to restore after the deploy prep
	$date = date( "Y-m-d_H:i", time() );
	
	exec( "mkdir -p /opt/stiliam-backups" );
	exec( "sudo chmod 777 /opt/stiliam-backups" );

	// backup now
	shell_exec( "mysqldump -u stiliam -pstiliam1984 cms | gzip > /opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" );
	
	if( !file_exists( "/opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" ) ) {
		status_message( "danger", "Database backup has failed." );
		go( $_SERVER['HTTP_REFERER'] );
	}

	// prepare the database
	$truncate = $conn->exec( "TRUNCATE TABLE `bouquets` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `bouquets_content` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_247` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_247_files` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_247_monitored_folders` " );
	$truncate = $conn->exec( "DELETE FROM `channels_categories` WHERE `id` != '1'  " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_customer_server_assignments` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_servers` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_sources` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_streams` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `customers` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `customers_connection_logs` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `customers_ips` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `epg` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `epg_xml_ids` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `jobs` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `licenses` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `logs` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `mag_devices` " );
	$truncate = $conn->exec( "DELETE FROM `packages` WHERE `id` != '1'  " );
	$truncate = $conn->exec( "TRUNCATE TABLE `playlist_manager` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `playlist_manager_content` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `rtmp_allowed_ips` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `servers` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `servers_logs` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `servers_stats_history` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `channels_topology_profiles` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `transcoding_profiles` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `user_logs` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `servers_logs` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `user_sessions` " );
	$truncate = $conn->exec( "DELETE FROM `users` WHERE `id` != '1'  " );
	$truncate = $conn->exec( "UPDATE `users` SET `first_name` = '' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `last_name` = '' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `username` = 'admin' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `password` = 'admin' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `email` = '' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `notes` = 'Default admin account.' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `credits` = '1000000' WHERE `id` = '1' " );
	$truncate = $conn->exec( "UPDATE `users` SET `gallary_view` = 'no' WHERE `id` = '1' " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_categories` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_files` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_monitored_folders` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_series_files` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_tv` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_tv_categories` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_tv_files` " );
	$truncate = $conn->exec( "TRUNCATE TABLE `vod_tv_monitored_folders` " );

	$truncate = $conn->exec( "UPDATE `global_settings` SET `config_value` = '' WHERE `config_name` = 'cms_domain_name' " );
	$truncate = $conn->exec( "UPDATE `global_settings` SET `config_value` = 'no' WHERE `config_name` = 'cms_terms_accepted' " );

	// prepare the database.sql file for deployment
	shell_exec( "sudo mysqldump -u stiliam -pstiliam1984 cms > /opt/stiliam-backups/database.sql" );
	shell_exec( "sudo cp /opt/stiliam-backups/database.sql /var/www/html/setup_files/database.sql" );

	// restore the backup we made before starting
	exec( "sudo gunzip -k /opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" );
	
	exec( "sudo mysql -ustiliam -pstiliam1984 cms < /opt/stiliam-backups/".$date."_stiliam_cms.sql" );

	exec( "sudo rm -rf /opt/stiliam-backups/".$date."_stiliam_cms.sql" );

	status_message( "success", "Deployment Preparation Complete" );
	go( $_SERVER['HTTP_REFERER'] );
}