<?php
// session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

// $data['version']			= '1.0.0';

include('/var/www/html/portal/inc/db.php');
include('/var/www/html/portal/inc/global_vars.php');
include('/var/www/html/portal/inc/functions.php');

header("Content-Type:application/json; charset=utf-8");

if(isset($Controller_Action) && !empty($Controller_Action)){
    $c = $Controller_Action;
} else {
    $c = get("c");
}
switch ($c){

    // stream connection log
    case "stream_connection_log":
        stream_connection_log();
        break;

    // stream progress
    case "stream_progress":
        stream_progress();
        break;

    // checkin
    case "checkin":
        checkin();
        break;

    // firewall_rules
    case "firewall_rules":
        firewall_rules();
        break;

    // headed
    case "headend":
        headend();
        break;

    // stream_out_headend_info
    case "stream_out_headend_info":
        stream_out_headend_info();
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

    case "stream_status_update":
        stream_status_update();
        break;

    case "cdn_stream_status_update":
        cdn_stream_status_update();
        break;

    case "channel_status_update":
        channel_status_update();
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

    // stream_info_client
    case "stream_info_client":
        stream_info_client();
        break;

    // series_info_client
    case "series_info_client":
        series_info_client();
        break;

    // vod_info_client
    case "vod_info_client":
        vod_info_client();
        break;

    // channel_info_client
    case "channel_info_client":
        channel_info_client();
        break;

    // channel_info_fingerprint
    case "channel_info_fingerprint":
        channel_info_fingerprint();
        break;

    // channel connection log
    case "channel_connection_log":
        channel_connection_log();
        break;

    // roku device update
    case "roku_device_update":
        roku_device_update();
        break;

    // get transcoding_profile
    case "transcoding_profile":
        transcoding_profile();
        break;

    // mag_device_api
    case "mag_device_api":
        mag_device_api();
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
    json_output($data);
}

function stream_connection_log()
{
    global $conn;

    $server_id 		= get('server_id');
    $client_ip 		= get('client_ip');
    $stream_id 		= get('stream_id');
    $username 		= get('username');
    // $stream_name 	= get('stream_name');

    if(empty($server_id)){
        $query = $conn->query("SELECT `server_id` FROM `streams` WHERE `id` = '".$stream_id."' ");
        $stream = $query->fetch(PDO::FETCH_ASSOC);
        $server_id = $stream['server_id'];
    }

    // get customer details
    $query = $conn->query("SELECT * FROM `customers` WHERE `username` = '".$username."' ");
    $customer = $query->fetch(PDO::FETCH_ASSOC);

    // check it record already exists and update if found or create new record if no match found
    $query = $conn->query("SELECT * FROM `streams_connection_logs` WHERE `stream_id` = '".$stream_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' ");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if(isset($result['id'])) {
        // update existing record
        $update = $conn->exec("UPDATE `streams_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' ");
    }else{
        // add new record
        $insert = $conn->exec("INSERT INTO `streams_connection_logs` 
	        (`timestamp`,`server_id`,`client_ip`,`stream_id`,`customer_id`)
	        VALUE
	        ('".time()."','".$server_id."','".$client_ip."','".$stream_id."','".$customer['id']."')");
    }
}

function checkin()
{
    global $conn;

    $data['post']					= $_POST;

    // error_log("Incoming data from: " . $data['post']['ip_address']);

    if(empty($data['post']['uuid'])) {
        $output['status']				= 'error';
        $output['message']			= 'missing uuid.';
        json_output($output);
        die();
    }

    if(empty($data['post']['ip_address'])) {
        $output['status']				= 'error';
        $output['message']			= 'missing ip address.';
        json_output($output);
        die();
    }

    // get the server_id
    $query = $conn->query("SELECT `id`,`name`,`user_id` FROM `headend_servers` WHERE `uuid` = '".$data['post']['uuid']."' ");
    $headend = $query->fetch(PDO::FETCH_ASSOC);
    $server_id = $headend['id'];
    $output['server_id'] = $server_id;

    // add useage for server graphs
    /*
    $insert = $conn->exec("INSERT INTO `headend_stats_history`
        (`added`,`user_id`,`server_id`,`bandwidth_up`,`bandwidth_down`, `cpu_usage`,`ram_usage`)
        VALUE
        ('".time()."000', '".$headend['user_id']."', '".$headend['id']."', '".$data['post']['bandwidth_up']."','".$data['post']['bandwidth_down']."', '".str_replace('%','',$data['post']['cpu_usage'])."', '".str_replace('%','',$data['post']['ram_usage'])."')");
        */

    // log the checkin
    $insert = $conn->exec("INSERT INTO `headend_server_logs` 
        (`server_id`,`added`, `message`)
        VALUE
        ('".$server_id."','".time()."', '".$headend['name']." checked in.')");

    $output['event_logged'] = true;

    // update server core stats
    $update = $conn->exec("UPDATE `headend_servers` SET `updated` = '".time()."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `status` = 'online' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `uptime` = '".$data['post']['uptime']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `os_version` = '".$data['post']['os_version']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `ip_address` = '".$data['post']['ip_address']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `wan_ip_address` = '".$_SERVER['REMOTE_ADDR']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `cpu_usage` = '".$data['post']['cpu_usage']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `ram_usage` = '".$data['post']['ram_usage']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `disk_usage` = '".$data['post']['disk_usage']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `bandwidth_down` = '".$data['post']['bandwidth_down']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `bandwidth_up` = '".$data['post']['bandwidth_up']."' WHERE `uuid` = '".$data['post']['uuid']."' ");

    $update = $conn->exec("UPDATE `headend_servers` SET `cpu_model` = '".ltrim($data['post']['cpu_model'])."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `cpu_cores` = '".$data['post']['cpu_cores']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    $update = $conn->exec("UPDATE `headend_servers` SET `cpu_speed` = '".$data['post']['cpu_speed']."' WHERE `uuid` = '".$data['post']['uuid']."' ");

    $update = $conn->exec("UPDATE `headend_servers` SET `ram_total` = '".$data['post']['ram_total']."' WHERE `uuid` = '".$data['post']['uuid']."' ");

    $update = $conn->exec("UPDATE `headend_servers` SET `kernel` = '".$data['post']['kernel']."' WHERE `uuid` = '".$data['post']['uuid']."' ");

    $update = $conn->exec("UPDATE `headend_servers` SET `active_connections` = '".$data['post']['active_connections']."' WHERE `uuid` = '".$data['post']['uuid']."' ");

    if(!empty($data['post']['astra_config_file'])){
        $update = $conn->exec("UPDATE `headend_servers` SET `astra_config_file` = '".$data['post']['astra_config_file']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }
    $update = $conn->exec("UPDATE `headend_servers` SET `nginx_stats` = '".$data['post']['nginx_stats']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    if(!empty($data['post']['astra_license'])){
        $update = $conn->exec("UPDATE `headend_servers` SET `astra_license` = '".$data['post']['astra_license']."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }

    if(!empty($data['post']['gpu_stats'])){
        $update = $conn->exec("UPDATE `headend_servers` SET `gpu_stats` = '".json_encode($data['post']['gpu_stats'])."' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }else{
        $update = $conn->exec("UPDATE `headend_servers` SET `gpu_stats` = '' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }

    if(isset($data['post']['mumudvb_config_file']) && !empty($data['post']['mumudvb_config_file'])) {
        // store json array for quicker acessing for clients later
        file_put_contents('/data/wwwroot/defaul//config/'.$headend['id'].'_mumudvb.conf', $data['post']['mumudvb_config_file']);

        $update = $conn->exec("UPDATE `headend_servers` SET `mumudvb_config_file` = '/var/www/html/portal/config/".$headend['id']."_mumudvb.conf' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }else{
        $update = $conn->exec("UPDATE `headend_servers` SET `mumudvb_config_file` = 'no_data' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }

    if(isset($data['post']['tvheadend_config_file']) && !empty($data['post']['tvheadend_config_file'])) {
        // store json array for quicker acessing for clients later
        file_put_contents('/var/www/html/portal/config/'.$headend['id'].'_tvheadend.conf', $data['post']['tvheadend_config_file']);

        $update = $conn->exec("UPDATE `headend_servers` SET `tvheadend_config_file` = '/var/www/html/portal/config/".$headend['id']."_tvheadend.conf' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }else{
        $update = $conn->exec("UPDATE `headend_servers` SET `tvheadend_config_file` = 'no_data' WHERE `uuid` = '".$data['post']['uuid']."' ");
    }

    // parse nginx_stats for streams
    $nginx_stats = json_decode($data['post']['nginx_stats'], true);
    if(isset($nginx_stats['server']['application']['live']['stream'])) {
        // check if only one or multiple streams
        if(isset($nginx_stats['server']['application']['live']['stream'][0])) {
            // multi streams found
            foreach($nginx_stats['server']['application']['live']['stream'] as $stream) {
                // set stream status
                if(isset($stream['name']) && !empty($stream['name'])) {
                    // $update = $conn->exec("UPDATE `streams` SET `updated` = '".time()."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                    // $update = $conn->exec("UPDATE `streams` SET `status` = 'online' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                    $update = $conn->exec("UPDATE `streams` SET `stream_bitrate` = '".$stream['bw_in']."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                    $update = $conn->exec("UPDATE `streams` SET `stream_uptime` = '".$stream['time']."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                }
            }
        }else{
            // single stream found
            $stream = $nginx_stats['server']['application']['live']['stream'];
            if(isset($stream['name']) && !empty($stream['name'])) {
                // $update = $conn->exec("UPDATE `streams` SET `updated` = '".time()."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                // $update = $conn->exec("UPDATE `streams` SET `status` = 'online' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                $update = $conn->exec("UPDATE `streams` SET `stream_bitrate` = '".$stream['bw_in']."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                $update = $conn->exec("UPDATE `streams` SET `stream_uptime` = '".$stream['time']."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
            }
        }


        /*
        foreach($nginx_stats['server']['application']['live']['stream'] as $stream) {
            // set stream status
            if(isset($stream['name']) && !empty($stream['name'])) {
                $update = $conn->exec("UPDATE `streams` SET `updated` = '".time()."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                $update = $conn->exec("UPDATE `streams` SET `status` = 'online' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                $update = $conn->exec("UPDATE `streams` SET `stream_bitrate` = '".$stream['bw_in']."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
                $update = $conn->exec("UPDATE `streams` SET `stream_uptime` = '".$stream['time']."' WHERE `server_id` = '".$server_id."' AND `rtmp_server` LIKE '%".$stream['name']."' ");
            }
        }
        */
    }

    $output['headend_data'] = 'updated';

    // work with video_sources
    if(isset($data['post']['video_sources']) && is_array($data['post']['video_sources'])) {
        foreach($data['post']['video_sources'] as $source) {
            $query = $conn->query("SELECT * FROM `capture_devices` WHERE `server_id` = '".$server_id."' AND `video_device` = '".$source['video_device']."' ");
            $source_found = $query->rowCount();
            if($source_found != 0) {
                $existing_source = $query->fetch(PDO::FETCH_ASSOC);
                $source['id'] = $existing_source['id'];
                $output['source_id'] = $source['id'];

                $source['running_pid'] = preg_replace("/[^0-9]/", "", $source['running_pid']);

                $update = $conn->exec("UPDATE `capture_devices` SET `updated` = '".time()."' WHERE `id` = '".$source['id']."' ");
                $update = $conn->exec("UPDATE `capture_devices` SET `status` = '".$source['status']."' WHERE `id` = '".$source['id']."' ");
                $update = $conn->exec("UPDATE `capture_devices` SET `type` = '".$source['type']."' WHERE `id` = '".$source['id']."' ");
                $update = $conn->exec("UPDATE `capture_devices` SET `name` = '".$source['name']."' WHERE `id` = '".$source['id']."' ");
                $update = $conn->exec("UPDATE `capture_devices` SET `running_command` = '".$source['running_command']."' WHERE `id` = '".$source['id']."' ");

                if(isset($source['running_pid']) && !empty($source['running_pid'])){
                    $update = $conn->exec("UPDATE `capture_devices` SET `running_pid` = '".$source['running_pid']."' WHERE `id` = '".$source['id']."' ");
                }else{
                    $update = $conn->exec("UPDATE `capture_devices` SET `running_pid` = NULL WHERE `id` = '".$source['id']."' ");
                }

                if($source['status'] == 'available') {
                    $update = $conn->exec("UPDATE `capture_devices` SET `stream_uptime` = NULL WHERE `id` = '".$source['id']."' ");
                }else{
                    $update = $conn->exec("UPDATE `capture_devices` SET `stream_uptime` = '".$source['stream_uptime']."' WHERE `id` = '".$source['id']."' ");
                }

                $update = $conn->exec("UPDATE `capture_devices` SET `used_by` = '".$source['used_by']."' WHERE `id` = '".$source['id']."' ");

                if(isset($source['dvb_signal']) && !empty($source['dvb_signal'])) {
                    $update = $conn->exec("UPDATE `capture_devices` SET `dvb_signal` = '".$source['dvb_signal']."' WHERE `id` = '".$source['id']."' ");
                }else{
                    $update = $conn->exec("UPDATE `capture_devices` SET `dvb_signal` = NULL WHERE `id` = '".$source['id']."' ");
                }

                if(isset($source['dvb_snr']) && !empty($source['dvb_snr'])) {
                    $update = $conn->exec("UPDATE `capture_devices` SET `dvb_snr` = '".$source['dvb_snr']."' WHERE `id` = '".$source['id']."' ");
                }else{
                    $update = $conn->exec("UPDATE `capture_devices` SET `dvb_snr` = NULL WHERE `id` = '".$source['id']."' ");
                }

                if(isset($source['dvb_type']) && !empty($source['dvb_type'])) {
                    $update = $conn->exec("UPDATE `capture_devices` SET `dvb_type` = '".$source['dvb_type']."' WHERE `id` = '".$source['id']."' ");
                }else{
                    $update = $conn->exec("UPDATE `capture_devices` SET `dvb_type` = NULL WHERE `id` = '".$source['id']."' ");
                }
            }else{
                // source not found, lets add it
                $insert = $conn->exec("INSERT INTO `capture_devices` 
			        (`server_id`,`name`, `video_device`, `status`, `type`)
			        VALUE
			        ('".$server_id."','".$source['name']."', '".$source['video_device']."', '".$source['status']."', '".$source['type']."')");

                $output['source_id'] = $conn->lastInsertId();
            }
        }
    }else{
        // $update = $conn->exec("UPDATE `capture_devices` SET `status` = 'missing' WHERE `server_id` = '".$server_id."' ");
        // $update = $conn->exec("UPDATE `capture_devices` SET `used_by` = NULL WHERE `server_id` = '".$server_id."' ");
        // $update = $conn->exec("UPDATE `capture_devices` SET `running_pid` = NULL WHERE `server_id` = '".$server_id."' ");
        // $update = $conn->exec("UPDATE `capture_devices` SET `running_command` = NULL WHERE `server_id` = '".$server_id."' ");
        // $update = $conn->exec("UPDATE `capture_devices` SET `stream_uptime` = NULL WHERE `server_id` = '".$server_id."' ");
    }

    $output['video_source_data'] = 'updated';

    // work with audio_sources
    if(isset($data['post']['audio_sources']) && is_array($data['post']['audio_sources'])) {
        foreach($data['post']['audio_sources'] as $audio_device) {
            $query = $conn->query("SELECT * FROM `capture_devices_audio` WHERE `server_id` = '".$server_id."' AND `audio_device` = '".$audio_device."' ");
            $source_found = $query->rowCount();
            if($source_found != 0) {

            }else{
                // source not found, lets add it
                $insert = $conn->exec("INSERT INTO `capture_devices_audio` 
			        (`server_id`,`audio_device`)
			        VALUE
			        ('".$server_id."','".$audio_device."')");
            }
        }
    }else{
        // $update = $conn->exec("UPDATE `capture_devices` SET `status` = 'missing' WHERE `server_id` = '".$server_id."' ");
    }

    $output['audio_source_data'] = 'updated';

    // work with dvb_channels
    unset($source);
    /*
    if(isset($data['post']['dvb_channels'])) {
        foreach($data['post']['dvb_channels'] as $channel) {
            // get source data
            $query = $conn->query("SELECT 'dvb_type' FROM `capture_devices` WHERE `server_id` = '".$server_id."' AND `video_device` = '".$channel['adapter']."' ");
            $source = $query->fetchALL(PDO::FETCH_ASSOC);

            $query = $conn->query("SELECT * FROM `channels` WHERE `server_id` = '".$server_id."' AND `channel_name` = '".$channel['name']."' AND `video_device` = '".$channel['adapter']."' ");
            $channel_found = $query->rowCount();
            if($channel_found != 0) {
                $existing_channel = $query->fetch(PDO::FETCH_ASSOC);

                $update = $conn->exec("UPDATE `channels` SET `freq` = '".$channel['freq']."' WHERE `id` = '".$existing_channel['id']."' ");
                $update = $conn->exec("UPDATE `channels` SET `service_id` = '".$channel['service_id']."' WHERE `id` = '".$existing_channel['id']."' ");
            }else{
                $insert = $conn->exec("INSERT INTO `channels`
                    (`server_id`,`channel_name`, `video_device`, `type`, `freq`, `service_id`, `channel_type`)
                    VALUE
                    ('".$server_id."','".$channel['name']."', '".$channel['adapter']."', '".$source[0]['dvb_type']."', '".$channel['freq']."', '".$channel['service_id']."', '".$channel['channel_type']."')");
            }
        }

        $output['dvb_channel_data'] = 'updated';
    }else{
        $output['dvb_channel_data'] = 'not_set';
    }
    */

    // summary output
    $output['status']				= 'success';
    $output['message']				= 'server has been updated.';
    $output['post_data']			= $data['post'];

    json_output($output);
    die();
}

function jobs()
{
    global $conn;

    $server_uuid = $_GET['uuid'];

    $query = $conn->query("SELECT `id` FROM `headend_servers` WHERE `uuid` = '".$server_uuid."' ");
    $headend_found = $query->rowCount();
    if($headend_found != 0) {
        $headend = $query->fetch(PDO::FETCH_ASSOC);

        $query = $conn->query("SELECT * FROM `jobs` WHERE `server_id` = '".$headend['id']."' AND `status` = 'pending' LIMIT 5");
        $jobs_found = $query->rowCount();
        if($jobs_found != 0) {
            $jobs = $query->fetchALL(PDO::FETCH_ASSOC);

            $jobs[0]['job'] = json_decode($jobs[0]['job'], true);

            if(isset($jobs[1])){
                $jobs[1]['job'] = json_decode($jobs[1]['job'], true);
            }
            if(isset($jobs[2])){
                $jobs[2]['job'] = json_decode($jobs[2]['job'], true);
            }
            if(isset($jobs[3])){
                $jobs[3]['job'] = json_decode($jobs[3]['job'], true);
            }
            if(isset($jobs[4])){
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
    global $conn;

    $job_id = $_GET['id'];

    $update = $conn->exec("UPDATE `jobs` SET `status` = 'complete' WHERE `id` = '".$job_id."' ");

    $output['status']			= 'success';
    $output['message']			= 'job marked as completed.';

    json_output($output);
    die();
}

function headend()
{
    global $conn;

    $output = array();

    $server_uuid = get('server_uuid');

    // $server_uuid = $_GET['server_uuid'];

    if(empty($server_uuid)){
        die('missing server_uuid');
    }

    header("Content-Type:application/json; charset=utf-8");

    $query = $conn->query("SELECT * FROM `headend_servers` WHERE `uuid` = '".$server_uuid."' ");
    if($query !== FALSE) {
        $headends = $query->fetchAll(PDO::FETCH_ASSOC);

        $count = 0;

        foreach($headends as $headend) {
            $output[$count] = $headend;

            // convert seconds to human readable format
            $output[$count]['uptime'] = uptime($headend['uptime']);

            $output[$count]['nginx_stats'] = json_decode($headend['nginx_stats'], true);

            $output[$count]['astra_config_file'] = json_decode($headend['astra_config_file'], true);

            $output[$count]['astra_license'] = json_decode($headend['astra_license'], true);

            if(file_exists($output[$count]['mumudvb_config_file'])){
                $output[$count]['mumudvb_config_file'] = json_decode(file_get_contents($output[$count]['mumudvb_config_file']), true);
            }

            if(file_exists($output[$count]['tvheadend_config_file'])){
                $output[$count]['tvheadend_config_file'] = json_decode(file_get_contents($output[$count]['tvheadend_config_file']), true);
            }

            // get source details
            $query = $conn->query("SELECT * FROM `capture_devices` WHERE `server_id` = '".$headend['id']."' ");
            if($query !== FALSE) {
                $output[$count]['sources'] = $query->fetchAll(PDO::FETCH_ASSOC);
                $output[$count]['total_sources'] = count($output[$count]['sources']);
            }else{
                $output[$count]['total_sources'] = 0;
            }

            // get streams to transcode
            $query = $conn->query("SELECT * FROM `streams` WHERE `server_id` = '".$headend['id']."' ");
            if($query !== FALSE) {
                $output[$count]['streams'] = $query->fetchAll(PDO::FETCH_ASSOC);
                // $raw_streams = $query->fetchAll(PDO::FETCH_ASSOC);

                $count_stream = 0;
                $output[$count]['total_streams'] = count($output[$count]['streams']);

                // loop over streams and update for transcoding profiles
            }else{
                $output[$count]['total_streams'] = 0;
            }

            // get cdn streams to stream
            /*$query = $conn->query("SELECT * FROM `cdn_streams_servers` WHERE `server_id` = '".$headend['id']."' ");
            if($query !== FALSE) {
                $output[$count]['cdn_streams_links'] = $query->fetchAll(PDO::FETCH_ASSOC);

                $count_1 = 0;
                foreach($output[$count]['cdn_streams_links'] as $link) {
                    $query = $conn->query("SELECT * FROM `cdn_streams` WHERE `id` = '".$link['stream_id']."' ");
                    $stream = $query->fetchAll(PDO::FETCH_ASSOC);

                    $output[$count]['cdn_streams'][$count_1] = $stream[0];
                    $count_1++;
                }
                $output[$count]['total_cdn_streams'] = count($output[$count]['streams']);
            }else{
                $output[$count]['total_cdn_streams'] = 0;
            }
            */
            $output[$count]['total_cdn_streams'] = 0;

            // get channels to transcode
            $query = $conn->query("SELECT * FROM `channels` WHERE `server_id` = '".$headend['id']."' ORDER BY `name` ");
            if($query !== FALSE) {
                $channels = $query->fetchAll(PDO::FETCH_ASSOC);
                // $raw_streams = $query->fetchAll(PDO::FETCH_ASSOC);

                $output[$count]['total_channels'] = count($channels);

                // get media files for this series
                $channel_count = 0;
                foreach($channels as $channel) {
                    $output[$count]['channels'][$channel_count]['id'] 			= $channel['id'];
                    $output[$count]['channels'][$channel_count]['name'] 		= stripslashes($channel['name']);
                    $output[$count]['channels'][$channel_count]['enable'] 		= $channel['enable'];
                    $output[$count]['channels'][$channel_count]['server_id'] 	= $channel['server_id'];
                    $output[$count]['channels'][$channel_count]['cover_photo'] 	= $channel['cover_photo'];

                    $query = $conn->query("SELECT * FROM `channels_files` WHERE `channel_id` = '".$channel['id']."' ORDER BY `order` ");
                    $channel_files = $query->fetchAll(PDO::FETCH_ASSOC);

                    $channel_file_count = 0;
                    foreach($channel_files as $channel_file) {
                        $output[$count]['channels'][$channel_count]['files'][$channel_file_count]['id']			= $channel_file['id'];
                        $output[$count]['channels'][$channel_count]['files'][$channel_file_count]['name']		= stripslashes($channel_file['name']);
                        $output[$count]['channels'][$channel_count]['files'][$channel_file_count]['file']		= $channel_file['file_location'];

                        $channel_file_count++;
                    }

                    $channel_count++;
                }
            }else{
                $output[$count]['total_channels'] = 0;
            }

            // get roku devices
            $query = $conn->query("SELECT * FROM `roku_devices` WHERE `server_id` = '".$headend['id']."' ");
            if($query !== FALSE) {
                $output[$count]['roku_devices'] = $query->fetchAll(PDO::FETCH_ASSOC);
                // $raw_streams = $query->fetchAll(PDO::FETCH_ASSOC);

                $count_devices = 0;

                $output[$count]['total_roku_devices'] = count($output[$count]['roku_devices']);
            }else{
                $output[$count]['total_roku_devices'] = 0;
            }
            $count++;
        }

        $json = json_encode($output);

        echo $json;
    }
}

function stream_status_update()
{
    global $conn;

    header("Content-Type:application/json; charset=utf-8");

    // error_log($_SERVER['REMOTE_ADDR'] . ' is posting stream_status_update');

    $stream_id 			= $_GET['id'];
    $update = $conn->exec("UPDATE `streams` SET `updated` = '".time()."' WHERE `id` = '".$stream_id."' ");

    $stream_status 		= $_GET['status'];
    $update = $conn->exec("UPDATE `streams` SET `status` = '".$stream_status."' WHERE `id` = '".$stream_id."' ");

    if($stream_status == 'online'){
        if(isset($_GET['pid']) && !empty($_GET['pid'])){
            $stream_pid 			= $_GET['pid'];
            $stream_pid 			= preg_replace("/[^0-9]/", "", $stream_pid);
            $update = $conn->exec("UPDATE `streams` SET `running_pid` = '".$stream_pid."' WHERE `id` = '".$stream_id."' ");
        }

        $stream_uptime			= $_GET['uptime'];
        $stream_fps				= $_GET['fps'];
        $stream_speed			= $_GET['speed'];

        if(isset($_GET['resolution_w']) && !empty($_GET['resolution_w'])){
            $stream_width 			= $_GET['resolution_w'];
            $update = $conn->exec("UPDATE `streams` SET `probe_width` = '".$stream_width."' WHERE `id` = '".$stream_id."' ");
        }

        if(isset($_GET['resolution_h']) && !empty($_GET['resolution_h'])){
            $stream_height 			= $_GET['resolution_h'];
            $update = $conn->exec("UPDATE `streams` SET `probe_height` = '".$stream_height."' WHERE `id` = '".$stream_id."' ");
        }

        if(isset($_GET['bitrate']) && !empty($_GET['bitrate'])){
            $stream_bitrate 		= $_GET['bitrate'];
            $update = $conn->exec("UPDATE `streams` SET `probe_bitrate` = '".$stream_bitrate."' WHERE `id` = '".$stream_id."' ");
        }

        if(isset($_GET['aspect_ratio']) && !empty($_GET['aspect_ratio'])){
            $stream_aspect_ratio 	= $_GET['aspect_ratio'];
            $update = $conn->exec("UPDATE `streams` SET `probe_aspect_ratio` = '".$stream_aspect_ratio."' WHERE `id` = '".$stream_id."' ");
        }

        if(isset($_GET['video_codec']) && !empty($_GET['video_codec'])){
            $stream_video_codec 	= $_GET['video_codec'];
            $update = $conn->exec("UPDATE `streams` SET `probe_video_codec` = '".$stream_video_codec."' WHERE `id` = '".$stream_id."' ");
        }

        if(isset($_GET['audio_codec']) && !empty($_GET['audio_codec'])){
            $stream_audio_codec 	= $_GET['audio_codec'];
            $update = $conn->exec("UPDATE `streams` SET `probe_audio_codec` = '".$stream_audio_codec."' WHERE `id` = '".$stream_id."' ");
        }

        $update = $conn->exec("UPDATE `streams` SET `updated` = '".time()."' WHERE `id` = '".$stream_id."' ");
        $update = $conn->exec("UPDATE `streams` SET `stream_uptime` = '".$stream_uptime."' WHERE `id` = '".$stream_id."' ");
        $update = $conn->exec("UPDATE `streams` SET `job_status` = 'none' WHERE `id` = '".$stream_id."' ");
        $update = $conn->exec("UPDATE `streams` SET `fps` = '".$stream_fps."' WHERE `id` = '".$stream_id."' ");
        $update = $conn->exec("UPDATE `streams` SET `speed` = '".$stream_speed."' WHERE `id` = '".$stream_id."' ");



    }

    $output['status']			= 'success';
    $output['message']			= 'stream updated';

    json_output($output);
    die();
}

function cdn_stream_status_update()
{
    global $conn;

    error_log($_SERVER['REMOTE_ADDR'] . ' is posting cdn_stream_status_update');

    $stream_id 			= $_GET['id'];
    $server_id 			= $_GET['server_id'];
    $stream_pid 		= $_GET['pid'];
    $stream_uptime		= $_GET['uptime'];
    $stream_status		= $_GET['status'];

    // break uptime from minutes into seconds for handling later
    $time_bits 		= explode(':', $stream_uptime);
    $minutes 		= $time_bits[0];
    $seconds		= ($minutes * 60);

    $update = $conn->exec("UPDATE `cdn_streams_servers` SET `running_pid` = '".$stream_pid."' WHERE `stream_id` = '".$stream_id."' AND `server_id` = '".$server_id."' ");
    $update = $conn->exec("UPDATE `cdn_streams_servers` SET `status` = '".$stream_status."' WHERE `stream_id` = '".$stream_id."' AND `server_id` = '".$server_id."' ");

    $output['status']			= 'success';
    $output['message']			= 'stream updated';

    json_output($output);
    die();
}

function stream_progress()
{
    global $conn;

    /*
    header("Content-Type:application/json; charset=utf-8");
    */

    $data['stream_id']				= $_GET['stream_id'];
    $data['post']					= $_POST;

    $insert = $conn->exec("INSERT INTO `stream_progress` 
        (`timestamp`,`stream_id`,`data`)
        VALUE
        ('".time()."','".$data['stream_id']."','".json_encode($data['post'])."')");

    die();

    /*
    json_output($data);


    $req = '';
    foreach($_POST as $key => $value){
        $req .= $key.$value;
    }

    file_put_contents('/var/www/html/portal/logs'.$_GET['stream_id'].'_progress.log', $req);
    */
}

function stream()
{
    global $conn;

    $server_id = $_GET['server_id'];
    $stream_id = $_GET['stream_id'];
    $remote_ip = $_GET['remote_ip'];

    $query = $conn->query("SELECT * FROM `streams` WHERE `id` = '".$stream_id."' AND `server_id` = '".$server_id."' ");
    $stream_found = $query->rowCount();
    if($stream_found != 0) {
        $stream = $query->fetch(PDO::FETCH_ASSOC);

        $query = $conn->query("SELECT * FROM `streams_acl_rules` WHERE `server_id` = '".$server_id."' AND `ip_address` = '".$remote_ip."' ");
        $acl_found = $query->rowCount();
        if($acl_found == 0) {
            $data['status'] = 'error';
            $data['message'] = $remote_ip.' acl fail';
        }else{
            $query = $conn->query("SELECT `wan_ip_address`,`status`,`http_stream_port` FROM `headend_servers` WHERE `id` = '".$server_id."' ");
            $headend = $query->fetch(PDO::FETCH_ASSOC);

            if($headend['status'] == 'online') {
                $stream['output_options'] = json_decode($stream['output_options']);

                $data['status'] = 'success';
                $data['headend'] = $headend;
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

    json_output($data);
}

function stream_info_live()
{
    global $conn;

    $server_uuid = $_GET['server_uuid'];
    $query = $conn->query("SELECT * FROM `headend_servers` WHERE `uuid` = '".$server_uuid."' ");
    $headend = $query->fetch(PDO::FETCH_ASSOC);

    $count = 0;
    $query = $conn->query("SELECT * FROM `streams` WHERE `server_id` = '".$headend['id']."'  ");
    $streams_found = $query->rowCount();
    if($streams_found != 0) {
        $streams = $query->fetchAll(PDO::FETCH_ASSOC);

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

    json_output($data);
}

function stream_info()
{
    global $conn;

    $stream_id = stripslashes($_GET['stream_id']);
    $server_id = stripslashes($_GET['server_id']);

    $query = $conn->query("SELECT * FROM `streams` WHERE `id` = '".$stream_id."' AND `server_id` = '".$server_id."' ");
    $streams_found = $query->rowCount();
    if($streams_found != 0) {
        $stream = $query->fetch(PDO::FETCH_ASSOC);

        $data['status'] = 'success';
        $data['data']	= $stream;
    }else{
        $data['status'] = 'error';
        $data['message'] = 'stream not found';
    }

    json_output($data);
}

function stream_info_fingerprint()
{
    global $conn;

    $stream_id = stripslashes($_GET['stream_id']);

    if(!empty($stream_id)){
        $query = $conn->query("SELECT * FROM `streams` WHERE `id` = '".$stream_id."' ");
        $streams_found = $query->rowCount();
        if($streams_found != 0) {
            $stream = $query->fetch(PDO::FETCH_ASSOC);

            $data['status'] = 'success';
            $data['data']	= $stream;
        }else{
            $data['status'] = 'error';
            $data['message'] = 'stream not found';
        }
    }else{
        $data = '';
    }

    json_output($data);
}

function firewall_rules()
{
    global $conn;

    $data = array();

    $server_uuid = stripslashes($_GET['server_uuid']);

    $query = $conn->query("SELECT `id` FROM `headend_servers` WHERE `uuid` = '".$server_uuid."' ");
    $headend_found = $query->rowCount();
    if($headend_found != 0) {
        $headend = $query->fetch(PDO::FETCH_ASSOC);

        // find acl rules
        $query = $conn->query("SELECT * FROM `streams_acl_rules` WHERE `server_id` = '".$headend['id']."' ");
        $firewall_rules = $query->fetchAll(PDO::FETCH_ASSOC);

        // build json array
        foreach($firewall_rules as $firewall_rule){
            $data[] = $firewall_rule['ip_address'];
        }
    }else{
        $data['status'] = 'error';
        $data['message'] = 'server not found';
    }

    json_output($data);
}

function stream_info_client()
{
    global $conn;

    $username = stripslashes($_GET['username']);
    $password = stripslashes($_GET['password']);
    $stream_id = stripslashes($_GET['stream_id']);

    // check if username and password are valid
    $query = $conn->query("SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' ");
    $customer = $query->fetch(PDO::FETCH_ASSOC);

    if(empty($customer)) {
        // header('HTTP/1.0 403 Forbidden');
        // die("customer not found");

        $data['status'] = 'error';
        $data['message'] = 'customer not found or invalid login details.';

        json_output($data);
    }

    if($customer['status'] != 'enabled') {
        // header('HTTP/1.0 403 Forbidden');
        // die("account status: ".$customer['status']);

        $data['status'] = 'error';
        $data['message'] = 'customer status: '.$customer['status'];

        json_output($data);
    }

    // get stream data
    $query = $conn->query("SELECT * FROM `streams` WHERE `id` = '".$stream_id."' ");
    $streams_found = $query->rowCount();
    if($streams_found != 0) {
        $stream = $query->fetch(PDO::FETCH_ASSOC);

        $data['status'] = 'success';
        $data['data']	= $stream;
    }else{
        $data['status'] = 'error';
        $data['message'] = 'stream not found';
    }

    json_output($data);
}


function server_stats_api()
{
    global $conn;

    $server_id 	= $_GET['server_id'];
    $metric 	= $_GET['metric'];

    // $query = $conn->query("SELECT `added` AS `0`, `".$metric."` AS `1` FROM `headend_stats_history` WHERE `user_id` = '".$_SESSION['account']['id']."' AND `server_id` = '".$server_id."' ");
    $query = $conn->query("SELECT `added` AS `0`, `".$metric."` AS `1` FROM `headend_stats_history` WHERE `server_id` = '".$server_id."' ");
    $stats = $query->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;

    if($metric == 'bandwidth_up' || $metric == 'bandwidth_down'){
        foreach($stats as $stat){
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

function stream_out_headend_info()
{
    global $conn;

    $server_id 	= $_GET['server_id'];
    $password 	= $_GET['password'];

    if(empty($_GET['password']) || $password != '137213921984'){
        $data['status'] = 'error';
        $data['message'] = 'incorrect password';
        json_output($data);
    }

    header("Content-Type:application/json; charset=utf-8");

    $query = $conn->query("SELECT * FROM `headend_servers` WHERE `id` = '".$server_id."' ");
    $headend = $query->fetch(PDO::FETCH_ASSOC);

    $data['id'] 				= $headend['id'];
    $data['wan_ip_address'] 	= $headend['wan_ip_address'];
    $data['public_hostname'] 	= $headend['public_hostname'];
    $data['http_stream_port'] 	= $headend['http_stream_port'];

    json_output($data);
}

function series_info_client()
{
    global $conn;

    $username = stripslashes($_GET['username']);
    $password = stripslashes($_GET['password']);
    $series_id = stripslashes($_GET['series_id']);

    // check if username and password are valid
    $query = $conn->query("SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' ");
    $customer = $query->fetch(PDO::FETCH_ASSOC);

    if(empty($customer)) {
        // header('HTTP/1.0 403 Forbidden');
        // die("customer not found");

        $data['status'] = 'error';
        $data['message'] = 'customer not found or invalid login details.';

        json_output($data);
    }

    if($customer['status'] != 'enabled') {
        // header('HTTP/1.0 403 Forbidden');
        // die("account status: ".$customer['status']);

        $data['status'] = 'error';
        $data['message'] = 'customer status: '.$customer['status'];

        json_output($data);
    }

    // get series data
    $query = $conn->query("SELECT * FROM `tv_series_files` WHERE `id` = '".$series_id."' ");
    $series_found = $query->rowCount();
    if($series_found != 0) {
        $series = $query->fetch(PDO::FETCH_ASSOC);

        // get headend info
        $query = $conn->query("SELECT `wan_ip_address`,`http_stream_port` FROM `headend_servers` WHERE `id` = '".$series['server_id']."' ");
        $series['headend'] = $query->fetch(PDO::FETCH_ASSOC);

        $data['status'] = 'success';
        $data['data']	= $series;
    }else{
        $data['status'] = 'error';
        $data['message'] = 'series not found';
    }

    json_output($data);
}

function vod_info_client()
{
    global $conn;

    $username = stripslashes($_GET['username']);
    $password = stripslashes($_GET['password']);
    $vod_id = stripslashes($_GET['vod_id']);

    // check if username and password are valid
    $query = $conn->query("SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' ");
    $customer = $query->fetch(PDO::FETCH_ASSOC);

    if(empty($customer)) {
        // header('HTTP/1.0 403 Forbidden');
        // die("customer not found");

        $data['status'] = 'error';
        $data['message'] = 'customer not found or invalid login details.';

        json_output($data);
    }

    if($customer['status'] != 'enabled') {
        // header('HTTP/1.0 403 Forbidden');
        // die("account status: ".$customer['status']);

        $data['status'] = 'error';
        $data['message'] = 'customer status: '.$customer['status'];

        json_output($data);
    }

    // get series data
    $query = $conn->query("SELECT * FROM `vod` WHERE `id` = '".$vod_id."' ");
    $vod_found = $query->rowCount();
    if($vod_found != 0) {
        $vod = $query->fetch(PDO::FETCH_ASSOC);

        // get headend info
        $query = $conn->query("SELECT `wan_ip_address`,`http_stream_port` FROM `headend_servers` WHERE `id` = '".$vod['server_id']."' ");
        $vod['headend'] = $query->fetch(PDO::FETCH_ASSOC);

        $data['status'] = 'success';
        $data['data']	= $vod;
    }else{
        $data['status'] = 'error';
        $data['message'] = 'vod not found';
    }

    json_output($data);
}

function channel_info_client()
{
    global $conn;

    $username = stripslashes($_GET['username']);
    $password = stripslashes($_GET['password']);
    $channel_id = stripslashes($_GET['channel_id']);

    // check if username and password are valid
    $query = $conn->query("SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' ");
    $customer = $query->fetch(PDO::FETCH_ASSOC);

    if(empty($customer)) {
        // header('HTTP/1.0 403 Forbidden');
        // die("customer not found");

        $data['status'] = 'error';
        $data['message'] = 'customer not found or invalid login details.';

        json_output($data);
    }

    if($customer['status'] != 'enabled') {
        // header('HTTP/1.0 403 Forbidden');
        // die("account status: ".$customer['status']);

        $data['status'] = 'error';
        $data['message'] = 'customer status: '.$customer['status'];

        json_output($data);
    }

    // get channel data
    $query = $conn->query("SELECT * FROM `channels` WHERE `id` = '".$channel_id."' ");
    $channel_found = $query->rowCount();
    if($channel_found != 0) {
        $channel = $query->fetch(PDO::FETCH_ASSOC);

        // get headend info
        $query = $conn->query("SELECT `wan_ip_address`,`http_stream_port` FROM `headend_servers` WHERE `id` = '".$channel['server_id']."' ");
        $channel['headend'] = $query->fetch(PDO::FETCH_ASSOC);

        $data['status'] = 'success';
        $data['data']	= $channel;
    }else{
        $data['status'] = 'error';
        $data['message'] = 'channel not found';
    }

    json_output($data);
}

function channel_status_update()
{
    global $conn;

    header("Content-Type:application/json; charset=utf-8");

    // error_log($_SERVER['REMOTE_ADDR'] . ' is posting stream_status_update');

    $channel_id 			= get('id');

    $status 				= get('status');
    $update = $conn->exec("UPDATE `channels` SET `status` = '".$status."' WHERE `id` = '".$channel_id."' ");

    if($status == 'online'){
        $uptime 				= get('uptime');
        $update = $conn->exec("UPDATE `channels` SET `uptime` = '".$uptime."' WHERE `id` = '".$channel_id."' ");
    }else{
        $update = $conn->exec("UPDATE `channels` SET `uptime` = '00:00' WHERE `id` = '".$channel_id."' ");
    }

    $output['status']			= 'success';
    $output['message']			= 'channel updated';

    json_output($output);
    die();
}

function channel_connection_log()
{
    global $conn;

    $output = array();

    $server_id 		= get('server_id');
    $client_ip 		= get('client_ip');
    $channel_id 	= get('channel_id');
    $username 		= get('username');
    // $stream_name 	= get('stream_name');

    if(empty($server_id)){
        $query = $conn->query("SELECT `server_id` FROM `channels` WHERE `id` = '".$channel_id."' ");
        $stream = $query->fetch(PDO::FETCH_ASSOC);
        $server_id = $stream['server_id'];
    }

    // get customer details
    $query = $conn->query("SELECT * FROM `customers` WHERE `username` = '".$username."' ");
    $customer = $query->fetch(PDO::FETCH_ASSOC);

    // check it record already exists and update if found or create new record if no match found
    $query = $conn->query("SELECT * FROM `channel_connection_logs` WHERE `channel_id` = '".$channel_id."' AND `customer_id` = '".$customer['id']."' AND `client_ip` = '".$client_ip."' ");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if(isset($result['id'])) {
        // update existing record
        $update = $conn->exec("UPDATE `channel_connection_logs` SET `timestamp` = '".time()."' WHERE `id` = '".$result['id']."' ");
    }else{
        // add new record
        $insert = $conn->exec("INSERT INTO `channel_connection_logs` 
	        (`timestamp`,`server_id`,`client_ip`,`channel_id`,`customer_id`)
	        VALUE
	        ('".time()."','".$server_id."','".$client_ip."','".$channel_id."','".$customer['id']."')");
    }

    if(is_customer_connection_allowed($customer['id']) == true) {
        $output['status']			= 'success';
        $output['message']			= '';
    }else{
        $output['status']			= 'failed';
        $output['message']			= 'customer max_connections reached';
    }

    json_output($output);
    die();
}

function channel_info_fingerprint()
{
    global $conn;

    $channel_id = stripslashes($_GET['channel_id']);

    $query = $conn->query("SELECT * FROM `channels` WHERE `id` = '".$channel_id."' ");
    $channel_found = $query->rowCount();
    if($channel_found != 0) {
        $channel = $query->fetch(PDO::FETCH_ASSOC);

        $data['status'] = 'success';
        $data['data']	= $channel;
    }else{
        $data['status'] = 'error';
        $data['message'] = 'stream not found';
    }

    json_output($data);
}

function roku_device_update()
{
    global $conn;

    header("Content-Type:application/json; charset=utf-8");

    $device_id 				= get('device_id');
    if(empty($device_id)){
        die("device_id missing");
    }

    $update 				= $conn->exec("UPDATE `roku_devices` SET `updated` = '".time()."' WHERE `id` = '".$device_id."' ");

    $device_status 			= $_GET['status'];
    $update 				= $conn->exec("UPDATE `roku_devices` SET `status` = '".$device_status."' WHERE `id` = '".$device_id."' ");

    if($device_status == 'online'){
        $device_uptime 		= $_GET['uptime'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `uptime` = '".$device_uptime."' WHERE `id` = '".$device_id."' ");

        $device_serial 		= $_GET['serial_number'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `serial_number` = '".$device_serial."' WHERE `id` = '".$device_id."' ");

        $device_uuid 		= $device_id;
        $update 			= $conn->exec("UPDATE `roku_devices` SET `device_uuid` = '".$device_uuid."' WHERE `id` = '".$device_id."' ");

        $device_model_name 	= $_GET['model_name'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `model_name` = '".$device_model_name."' WHERE `id` = '".$device_id."' ");

        $device_model_num 	= $_GET['model_number'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `model_number` = '".$device_model_num."' WHERE `id` = '".$device_id."' ");

        $device_wifi_mac 	= $_GET['wifi_mac'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `wifi_mac` = '".$device_wifi_mac."' WHERE `id` = '".$device_id."' ");

        $device_eth_mac 	= $_GET['ethernet_mac'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `ethernet_mac` = '".$device_eth_mac."' WHERE `id` = '".$device_id."' ");

        $device_net_type 	= $_GET['network_type'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `network_type` = '".$device_net_type."' WHERE `id` = '".$device_id."' ");

        $device_software 	= $_GET['software_version'];
        $update 			= $conn->exec("UPDATE `roku_devices` SET `software_version` = '".$device_software."' WHERE `id` = '".$device_id."' ");
    }else{
        $update 			= $conn->exec("UPDATE `roku_devices` SET `uptime` = '0' WHERE `id` = '".$device_id."' ");
    }

    $output['status']			= 'success';
    $output['message']			= 'device updated';

    json_output($output);
    die();
}

function transcoding_profile()
{
    global $conn;

    header("Content-Type:application/json; charset=utf-8");

    $data['id']						= stripslashes($_GET['id']);

    if(empty($data['id'])) {
        $output['status']			= 'error';
        $output['message']			= 'missing id.';
        json_output($output);
        die();
    }

    // get transcoding_profile
    $query 		= $conn->query("SELECT * FROM `transcoding_profiles` WHERE `id` = '".$data['id']."' ");
    $data 		= $query->fetch(PDO::FETCH_ASSOC);

    $data['data']		= json_decode($data['data'], true);

    json_output($data);
    die();
}

