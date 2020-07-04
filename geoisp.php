<?php
include( 'inc/functions.php' );
require( '/var/www/html/MaxMind-DB-Reader-php/autoload.php' );

use MaxMind\Db\Reader;
$geoip = new Reader( '/var/www/html/GeoIP2-ISP.mmdb' );

$record = $geoip->get( $_GET['ip'] );
$record = objectToArray( $record );

echo $record;