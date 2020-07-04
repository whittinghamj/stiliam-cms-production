<?php

// get system stats
$stats = shell_exec("sh /opt/stiliam-server-monitor/system_stats.sh");

// convert to php array
$stats = json_decode( $stats, true );

// parse
$stats['cpu_speed'] 			= number_format( $stats['cpu_speed'], 2 );
$stats['bandwidth_up'] 			= number_format( ( $stats['bandwidth_up'] / 125 ), 2 );
$stats['bandwidth_down'] 		= number_format( ( $stats['bandwidth_down'] / 125 ), 2 );

// convert to json array
$stats = json_encode( $stats );

// echo stats
echo $stats;

// exit
exit;