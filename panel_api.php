<?php
error_reporting(E_ALL);
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );
ini_set( 'memory_limit', -1 );

if( file_exists( "/var/www/html/inc/db.php" )){
    require_once( "/var/www/html/inc/db.php" );
} else {
    die( "Missing required DB element" );
}

if(file_exists( "/var/www/html/inc/functions.php" )){
    require_once( "/var/www/html/inc/functions.php" );
} else {
    die( "Missing my required functions" );
}

if(file_exists( "/var/www/html/inc/global_vars.php" )){
    require_once( "/var/www/html/inc/global_vars.php" );
} else {
    die( "Missing my required globals" );
}

$username       = request( 'username' );
$password       = request( 'password' );
$action         = request( 'action' );

$remote_ip      = $_SERVER['REMOTE_ADDR'];
$user_agent     = $_SERVER['HTTP_USER_AGENT'];
$query_string   = $_SERVER['QUERY_STRING'];

$sql            = "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' ";
$query          = $conn->query( $sql );
$customer       = $query->fetch( PDO::FETCH_ASSOC );

$parse_url = parse_url( $_SERVER['HTTP_HOST'] . '' . $_SERVER['REQUEST_URI'] );
header( 'Content-Type: application/json' );

$output = array();

if( is_array( $customer ) && !empty( $customer ) ) {
    // get categories
    $query                  = $conn->query( "SELECT * FROM `channels_categories` ORDER BY `name` ASC" );
    $live_categories        = $query->fetchAll( PDO::FETCH_ASSOC );

    $query                  = $conn->query( "SELECT * FROM `vod_categories` ORDER BY `name` ASC" );
    $vod_categories         = $query->fetchAll( PDO::FETCH_ASSOC );

    $query                  = $conn->query( "SELECT * FROM `vod_tv_categories` ORDER BY `name` ASC" );
    $vod_tv_categories      = $query->fetchAll( PDO::FETCH_ASSOC );

    // convert some things for XC compatability
    if($customer['status'] == 'active' ){
        $customer['status']             = 'Active';
    }elseif($customer['status'] == 'expired' ){
        $customer['status']             = 'Expired';
    }
    $customer['expire_date']            = $customer["expire_date"];
    $customer['current_connections']    = 0;

    $valid_actions      = array( "get_epg" );
    $usrname            = $username;
    $paswd              = $password;
    // $dbaction           = !empty(dbconnector::$request["action"]) && in_array(dbconnector::$request["action"], $valid_actions) ? dbconnector::$request["action"] : "";
    
    $output = array();
    $output["user_info"] = array();
    switch ($action) {
        case "get_epg":
            json_encode(array());
            exit;
        default:
            // $xurl = empty(dbconnector::$StreamingServers[SERVER_ID]["domain_name"]) ? dbconnector::$StreamingServers[SERVER_ID]["server_ip"] : dbconnector::$StreamingServers[SERVER_ID]["domain_name"];
            
            $output = array();

            $output["server_info"] = array(  "url" => 'http://' . $global_settings['cms_access_url'] );
            $output["user_info"]["username"]                        = $username;
            $output["user_info"]["password"]                        = $password;
            $output["user_info"]["auth"]                            = 1;
            
            if( $customer["status"] != 'Active' ){
                $output["user_info"]["status"]                      = "Disabled";
            } else {
                if( is_null( $customer["expire_date"] ) || time() < $customer['expire_date']) {
                    $output["user_info"]["status"]                  = "Active";
                } else {
                    $output["user_info"]["status"]                  = "Expired";
                }
            }

            $output["user_info"]["exp_date"]                        = $customer["expire_date"];
            $output["user_info"]["is_trial"]                        = 0; // 0=no, 1=yes
            $output["user_info"]["active_cons"]                     = 0; // placeholder for connection count in the future
            $output["user_info"]["created_at"]                      = $customer["updated"];
            $output["user_info"]["max_connections"]                 = $customer["max_connections"];
            $output["user_info"]["allowed_output_formats"]          = array( "m3u8", "ts" );
            $output["available_channels"]                           = array();
            $output['categories']                                   = array();

            foreach( $live_categories as $id => $category ) {
                $output['categories']['live'][] = array( 'category_id' => $category['id'], 'category_name' => $category['name'], 'parent_id' => 0 );
            }
            foreach ($vod_categories as $id => $category ) {
                $output['categories']['vod'][] = array( 'category_id' => $category['id'], 'category_name' => $category['name'], 'parent_id' => 0 );
            }
            foreach( $vod_tv_categories as $id => $category ) {
                $output['categories']['series'][] = array( 'category_id' => $category['id'], 'category_name' => $category['name'], 'parent_id' => 0 );
            }

            $output['available_channels']                           = array();
            
            $live_num = $movie_num = 0;

            // get allowed channels, vod and vod_tv
            $query           = $conn->query( "SELECT * FROM `channels` " );
            $channels        = $query->fetchAll( PDO::FETCH_ASSOC );
            $channels        = stripslashes_deep( $channels );

            foreach( $channels as $channel ) {
                foreach( $live_categories as $category ) {
                    if( $channel['category_id'] == $category['id'] ) {
                        $channel['category_name'] = $category['name'];
                        break;
                    }
                }

                $live_num++;
                $stream_icon = $channel['icon'];
                $tv_archive_duration = 0;
                $output['available_channels'][$channel['id']] = array(
                    'num' => $live_num, 
                    'name' => $channel['title'], 
                    'stream_type' => 'live', 
                    'type_name' => 'Live Streams', 
                    'stream_id' => $channel['id'], 
                    'stream_icon' => $stream_icon, 
                    'epg_channel_id' => $channel['epg_xml_id'], 
                    'added' => '', 
                    'category_name' => $channel['category_name'], 
                    'category_id' => $channel['category_id'], 
                    'series_no' => null, 
                    'live' => 1, 
                    'container_extension' => '', 
                    'custom_sid' => '', 
                    'tv_archive' => 0, 
                    'direct_source' => '', 
                    'tv_archive_duration' => 0
                );
            }
        
    }
}else{
    // $output['server_info']['sql']       = $sql;
    $output['user_info']['auth']        = 0;
}

json_output( $output );

?>