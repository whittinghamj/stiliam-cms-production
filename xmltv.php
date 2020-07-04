<?php
ini_set( 'display_errors', 1);
ini_set( 'display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set( 'UTC' );

session_start();

// includes
include( '/var/www/html/inc/db.php' );
include( '/var/www/html/inc/global_vars.php' );
include( '/var/www/html//inc/functions.php' );

// customer check
$username               = get( 'username' );
if( empty( $username ) ) {
    echo '<pre>';
    print_r( $_GET );
    die( 'missing username' );
}

// make sure password is set
$password               = get( 'password' );
if( empty( $password ) ) {
    echo '<pre>';
    print_r( $_GET );
    die( 'missing password' );
}

// find customer
$query                  = $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' " );
$customer               = $query->fetch( PDO::FETCH_ASSOC );

// customer not found
if(empty($customer)) {
    die( "customer not found" );
}

// customer account is not active
if($customer['status'] != 'enabled' ) {
    die( "account status: ".$customer['status']);
}

// build header info
header ( "Content-Type:text/xml" );
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<!DOCTYPE tv SYSTEM "xmltv.dtd">';
echo '<tv generator-info-name="IPTV" generator-info-url="http://'.$global_settings['cms_access_url_raw'].':'.$global_settings['cms_port'].'/">';

// get streams and epg_xml_id
$query 		= $conn->query( "SELECT `id`,`name`,`epg_xml_id` FROM `cms`.`channels` WHERE `epg_xml_id` != '' ORDER BY `id` " );
$channels 	= $query->fetchAll(PDO::FETCH_ASSOC);

foreach( $channels as $channel ) {
	echo '<channel id="'.$channel['epg_xml_id'].'">';
	echo '<display-name>'.$channel['title'].'</display-name>';
	echo '</channel>';

	$program_epg_xml_ids[$channel['id']] = $channel['epg_xml_id'];
}

// get program data
$query 		= $conn->query( "SELECT * FROM `stalker_db`.`epg` ORDER BY `id` " );
$programs 	= $query->fetchAll(PDO::FETCH_ASSOC);

foreach( $programs as $program ) {
	$time 					= str_replace( array( '-', ':', ' ' ), '', $program['time'] );
	$time					= htmlspecialchars( $time . ' +0000' );

	$time_to 				= str_replace( array( '-', ':', ' ' ), '', $program['time_to'] );
	$time_to				= htmlspecialchars( $time_to . ' +0000' );

	$epg_xml_id 			= $program_epg_xml_ids[$program['ch_id']];
	
	$name					= htmlspecialchars( $program['name'] );
	$description			= htmlspecialchars( $program['descr'] );

	// echo '<programme start="'.$time.'" stop="'.$time_to.'" channel="'.$epg_xml_id.'">';
	echo '<programme start="'.$time.'" stop="'.$time_to.'" channel="'.$epg_xml_id.'">';
	echo '<title>'.$name.'</title>';
	echo '<desc>';
	echo $description;
	echo '</desc>';
	echo '</programme>';
}

echo '</tv>';