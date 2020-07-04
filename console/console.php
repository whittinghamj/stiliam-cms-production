<?php

include('/var/www/html/inc/db.php');
include('/var/www/html/inc/global_vars.php');
include('/var/www/html/inc/functions.php');
include('/var/www/html/inc/php_colors.php');

date_default_timezone_set('UTC');

$colors = new Colors();

// get script options
$shortopts  = "";
$shortopts .= "f:"; 	// Required value
$shortopts .= "v::";	// Optional value
$shortopts .= "abc";	// These options do not accept values

$longopts  = array(
    "required:",    	// Required value
    "optional::",   	// Optional value
    "option",       	// No value
    "opt",          	// No value
    "verbose::",    	// Optional 0 = now | 1 = yes
    "action::",    		// Optional value
);
$script_options = getopt($shortopts, $longopts);

// set dev mode
if( isset( $script_options['verbose'] ) && $script_options['verbose'] == 1 ) {
	$verbose = true;
} else {
	$verbose = false;
}

// sanity check
if( !isset( $script_options['action'] ) || empty( $script_options['action'] ) ) {
	console_output( 'example usage: php -q console.php --action=task' );
}

// get the config file
$config = file_get_contents('/var/www/html/config.json');
$config = json_decode($config, true);

$task = $argv[1];

function killlock()
{
    global $lockfile;
	exec( "rm -rf $lockfile" );
}

if( $script_options['action'] == 'cron_manager')
{
	if( $verbose == true ) {
		console_output( "Stiliam CMS Cron Manager" );
	}

	if( $verbose == true ) {
		console_output( "Loading Module: checkin" );
	}
	shell_exec('php -q /var/www/html/console/console.php --action=checkin > /tmp/cron.node_checks.log');

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=node_checks' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: node checks" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=node_checks > /tmp/cron.node_checks.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: node checks" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=channels_checks' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: channels checks" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=channels_checks > /tmp/cron.channels_checks.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: channels checks" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=customer_checks' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: customer checks" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=customer_checks > /tmp/cron.customer_checks.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: customer checks" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=stream_ondemand_check' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: live stream ondemand check" );
		}
		// shell_exec('php -q /var/www/html/console/console.php --action=stream_ondemand_check > /tmp/cron.stream_ondemand_check.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: live stream ondemand check" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=xc_imports' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: xtream-codes importer" );
		}
		// shell_exec('php -q /var/www/html/console/console.php --action=xc_imports > /tmp/cron.xc_imports.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: xtream-codes importer" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=stalker_sync' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: stalker / ministra sync" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=stalker_sync > /tmp/cron.stalker_sync.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: stalker / ministra sync" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=totals' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: total calc for cms stats" );
		}
		// shell_exec('php -q /var/www/html/console/console.php --action=totals > /tmp/cron.totals.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: total calc for cms stats" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=channel_icon_scan' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: channel icon scan" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=channel_icon_scan > /tmp/cron.channel_icon_scan.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: channel icon scan" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=epg_checks' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: epg checks" );
		}
		// shell_exec('php -q /var/www/html/console/console.php --action=epg_checks > /tmp/cron.epg_checks.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: epg checks" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=playlist_inspector' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: playlist inspector" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=playlist_inspector > /tmp/cron.playlist_inspector.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: playlist inspector" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=vod_metadata_lookup' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: movie vod meta lookup" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=vod_metadata_lookup > /tmp/cron.vod_metadata_lookup.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: movie vod meta lookup" );
		}
	}

	$check = shell_exec( "ps aux | grep '/var/www/html/console/console.php --action=vod_tv_metadata_lookup' | grep -v 'grep' | grep -v '/bin/sh' | wc -l" );
	if( $check == 0) {
		if( $verbose == true ) {
			console_output( "Loading Module: tv vod meta lookup" );
		}
		shell_exec('php -q /var/www/html/console/console.php --action=vod_tv_metadata_lookup > /tmp/cron.vod_tv_metadata_lookup.log');
	} else {
		if( $verbose == true ) {
			console_output( "Skipping Module: tv vod meta lookup" );
		}
	}
	
	if( $verbose == true ) {
		console_output( "Finished." );
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'checkin')
{
	// get basic system stats
	$data 				= exec('sudo sh /opt/stiliam-server-monitor/system_stats.sh');
	$data 				= json_decode($data, true);
	
	$data['uuid']		= $config['uuid'];
	$data['cpu_usage'] 	= str_replace("%", "", $data['cpu_usage'] );
	$data['cpu_usage'] 	= number_format($data['cpu_usage'], 2);

	$data['ram_usage'] 	= str_replace("%", "", $data['ram_usage'] );
	$data['ram_usage'] 	= number_format($data['ram_usage'], 2);

	$data['disk_usage'] = str_replace("%", "", $data['disk_usage'] );
	$data['disk_usage'] = number_format($data['disk_usage'], 2);

	$data['os_version'] = exec('cat /etc/os-release | grep PRETTY | sed "s/PRETTY_NAME=//g" | sed "s/\"//g"');

	if( $verbose == true ) {
		console_output("Node Checkin");
		console_output("Remote Server: " . $config['cms']['server'] );
		console_output("Local IP: " . $data['ip_address'] );
		console_output("Local UUID: " . $config['uuid'] );
	}

	// get GPU data if available
	$has_gpu	= exec("sudo lspci | grep NVIDIA | wc -l");
	if($has_gpu > 0) {
		$xml 		= simplexml_load_string(shell_exec('nvidia-smi -q -x'));
		
		$json 		= json_encode($xml);

		$gpu_stats 	= json_decode($json, true);

		// print_r($gpu_stats);

		$stats['driver_version'] 	= $gpu_stats['driver_version'];
		$stats['cuda_version'] 		= $gpu_stats['cuda_version'];
		
		$count = 0;

		if(isset($gpu_stats['gpu'][0] )) {
			foreach($gpu_stats['gpu'] as $gpu_stat) {
				$stats['gpu'][$count]['id'] 				= $count;
				$stats['gpu'][$count]['uuid'] 				= $gpu_stat['uuid'];
				$stats['gpu'][$count]['gpu_name'] 			= $gpu_stat['product_name'];
				
				if( $verbose == true ) {
					console_output("GPU Found: ".$gpu_stat['product_name'] );
				}

				$stats['gpu'][$count]['fan_speed'] 			= $gpu_stat['fan_speed'];

				$stats['gpu'][$count]['gpu_temp'] 			= $gpu_stat['temperature']['gpu_temp'];

				$stats['gpu'][$count]['graphics_clock'] 	= $gpu_stat['clocks']['graphics_clock'];
				$stats['gpu'][$count]['sm_clock']		 	= $gpu_stat['clocks']['sm_clock'];
				$stats['gpu'][$count]['mem_clock'] 			= $gpu_stat['clocks']['mem_clock'];
				$stats['gpu'][$count]['video_clock'] 		= $gpu_stat['clocks']['video_clock'];

				$stats['gpu'][$count]['total_ram'] 			= $gpu_stat['fb_memory_usage']['total'];
				$stats['gpu'][$count]['used_ram'] 			= $gpu_stat['fb_memory_usage']['used'];
				$stats['gpu'][$count]['free_ram'] 			= $gpu_stat['fb_memory_usage']['free'];

				$stats['gpu'][$count]['gpu_util'] 			= $gpu_stat['utilization']['gpu_util'];

				$stats['gpu'][$count]['processes']			= $gpu_stat['processes']['process_info'];

				$count++;
			}
		}else{
			$stats['gpu'][$count]['id'] 				= $count;
			$stats['gpu'][$count]['uuid'] 				= $gpu_stats['gpu']['uuid'];
			$stats['gpu'][$count]['gpu_name'] 			= $gpu_stats['gpu']['product_name'];
			
			if( $verbose == true ) {
				console_output("GPU Found: ".$gpu_stats['gpu']['product_name'] );
			}
			$stats['gpu'][$count]['fan_speed'] 			= $gpu_stats['gpu']['fan_speed'];

			$stats['gpu'][$count]['gpu_temp'] 			= $gpu_stats['gpu']['temperature']['gpu_temp'];

			$stats['gpu'][$count]['graphics_clock'] 	= $gpu_stats['gpu']['clocks']['graphics_clock'];
			$stats['gpu'][$count]['sm_clock']		 	= $gpu_stats['gpu']['clocks']['sm_clock'];
			$stats['gpu'][$count]['mem_clock'] 			= $gpu_stats['gpu']['clocks']['mem_clock'];
			$stats['gpu'][$count]['video_clock'] 		= $gpu_stats['gpu']['clocks']['video_clock'];

			$stats['gpu'][$count]['total_ram'] 			= $gpu_stats['gpu']['fb_memory_usage']['total'];
			$stats['gpu'][$count]['used_ram'] 			= $gpu_stats['gpu']['fb_memory_usage']['used'];
			$stats['gpu'][$count]['free_ram'] 			= $gpu_stats['gpu']['fb_memory_usage']['free'];

			$stats['gpu'][$count]['gpu_util'] 			= $gpu_stats['gpu']['utilization']['gpu_util'];

			if(isset($gpu_stats['gpu']['processes']['process_info'] )) {
				$stats['gpu'][$count]['processes']			= $gpu_stats['gpu']['processes']['process_info'];
			}else{
				$stats['gpu'][$count]['processes']			= '';
			}
		}

		$data['gpu_stats'] = $stats;
	}

	// post data
	if( $verbose == true ) {
		console_output("Posting to CMS Server.");
	}

	$url = "http://".$config['cms']['server']."/api/?c=checkin";
	
	if( $verbose == true ) {
		console_output("URL: ".$url);
	}

	$ch = curl_init($url);
	$postString = http_build_query($data, '', '&');
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-length:' . strlen($postString)));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	
	curl_close($ch);

	// console_output("Server Reply: " . $response);
	
	if( $verbose == true ) {
		console_output("Finished.");
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'jobs')
{
	if( $verbose == true ) {
		console_output("Checking for CMS jobs.");
		console_output("API Endpoint: http://".$config['cms']['server']."/api/?c=jobs&uuid=".$config['uuid'] );
	}

	$jobs 		= @file_get_contents("http://".$config['cms']['server']."/api/?c=jobs&uuid=".$config['uuid'] );
	$jobs		= json_decode($jobs, true);

	if(is_array($jobs) && isset($jobs[0] )) {
		// print_r($jobs);

		foreach($jobs as $job) {
			if($job['job']['action'] == 'reboot') {
				if( $verbose == true ) {
					console_output("Reboot job found, server will reboot in 10 seconds.");
				}

				@file_get_contents("http://".$config['cms']['server']."/api/?c=job_complete&id=".$job['id'] );
				
				sleep(10);
				exec("sudo /sbin/shutdown -r now");
			}

			if($job['job']['action'] == 'kill_pid') {
				if( $verbose == true ) {
					console_output("Kill PID job found.");
				}

				@file_get_contents("http://".$config['cms']['server']."/portal/api/?c=job_complete&id=".$job['id'] );
				
				sleep(1);
				exec($job['job']['command'] );
			}

			if($job['job']['action'] == 'streams_restart_all') {
				if( $verbose == true ) {
					console_output("Restart All Streams job found.");
				}

				// stop ffmpeg from running
				exec("sudo killall ffmpeg");
				exec("sudo killall ffmpeg");
				exec("sudo killall ffmpeg");
				exec("sudo killall ffmpeg");
				exec("sudo killall ffmpeg");

				exec("sudo pkill ffmpeg");
				exec("sudo pkill ffmpeg");
				exec("sudo pkill ffmpeg");
				exec("sudo pkill ffmpeg");
				exec("sudo pkill ffmpeg");

				// remove all hls stream files.
				exec("sudo rm -rf /var/www/html/play/hls/*");

				// report job complete
				@file_get_contents("http://".$config['cms']['server']."/portal/api/?c=job_complete&id=".$job['id'] );
			}
		}
	}else{
		if( $verbose == true ) {
			console_output("No pending jobs.");
		}
	}

	if( $verbose == true ) {
		console_output("Finished.");
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'channel_icon_scan')
{
	if( $verbose == true ) {
		console_output("Channel Icon Manager");
	}

	foreach (glob("/var/www/html/content/channel_icons/*.png") as $filename) {
	    // echo "$filename size " . filesize($filename) . "\n";

	    $filename_short     = str_replace('/var/www/html/content/channel_icons/', '', $filename);
	    $filesize           = filesize($filename);
	    $filesize           = formatSizeUnits($filesize);

	    list($width, $height, $type, $attr) = getimagesize($filename);

	    // sanity check
	    $query = $conn->query("SELECT * FROM `channels_icons` WHERE `filename` = '".$filename_short."' ");
        $existing_icon = $query->fetch( PDO::FETCH_ASSOC );
        if( !isset( $existing_icon['id'] ) ) {
		    $insert = $conn->exec( "INSERT IGNORE INTO `channels_icons` 
			        (`filename`,`width`,`height`,`type`,`filesize`)
			        VALUE
			        ('".$filename_short."','".$width."','".$height."','".$type."','".$filesize."')" );

		    if( $verbose == true ) {
				console_output("Added: ".$filename_short);
			}
		} else {
			$update = $conn->exec( "UPDATE `channels_icons` SET `width` = '".$width."' 						WHERE `id` = '".$existing_icon['id']."' " );
			$update = $conn->exec( "UPDATE `channels_icons` SET `height` = '".$height."' 					WHERE `id` = '".$existing_icon['id']."' " );
			$update = $conn->exec( "UPDATE `channels_icons` SET `type` = '".$type."' 						WHERE `id` = '".$existing_icon['id']."' " );
			$update = $conn->exec( "UPDATE `channels_icons` SET `filesize` = '".$filesize."' 				WHERE `id` = '".$existing_icon['id']."' " );

			if( $verbose == true ) {
				console_output("Updated: ".$filename_short);
			}
		}
	}

	if( $verbose == true ) {
		console_output("Finished.");
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'vod_metadata_lookup')
{
	if( $verbose == true ) {
		console_output("Movies VoD metadata update.");
	}

	// set allowed file formats
	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	// get a list of all tv shows
	$query 		= $conn->query( "SELECT * FROM `vod` ORDER BY `title` " );
	$movies 	= $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over shows
	foreach( $movies as $movie ) {
		// regex the title to remove brackets and year for searching
		if( preg_match( "(\([^0-9]*\d+[^0-9]*\))", $movie['title'], $result ) ) {
			$movie['search_title'] 	= str_replace( $result[0], '', $movie['title'] );
			$movie['search_title'] 	= str_replace( array( '(', ')' ), '', $movie['search_title'] );
			$movie['search_title'] 	= trim( $movie['search_title'] );
		} else {
			$movie['search_title'] 	= $movie['title'];
		}

		if( $verbose == true ) {
			console_output( "Movie: ".stripslashes( $movie['title'] ) );
			console_output( "Movie (search): ".stripslashes( $movie['search_title'] ) );
		}

		// lets get tmdb data for this show
		$tmdb = metadata_tmdb_movie( $movie['search_title'] );

		// did we match the show
		if( $tmdb['total_results'] > 0 ) {
			if( $verbose == true ) {
				console_output( "themoviedb.org - movie id: ".$tmdb['results'][0]['id'] );
				console_output( "themoviedb.org - movie name: ".$tmdb['results'][0]['title'] );
			}

			// update the show data
			$release_date_bits 				= explode( '-', $tmdb['results'][0]['release_date'] );
			$tmdb['results'][0]['year'] 	= $release_date_bits[0];

			// $update = $conn->exec( "UPDATE `vod_tv` SET `title` = '".addslashes( $tmdb['results'][0]['original_name'] )."' 								WHERE `id` = '".$movie['id']."' " );
			$update = $conn->exec( "UPDATE `vod` SET `description` = '".addslashes( $tmdb['results'][0]['overview'] )."' 									WHERE `id` = '".$movie['id']."' " );
			$update = $conn->exec( "UPDATE `vod` SET `plot` = '".addslashes( $tmdb['results'][0]['overview'] )."' 											WHERE `id` = '".$movie['id']."' " );
			$update = $conn->exec( "UPDATE `vod` SET `year` = '".addslashes( $tmdb['results'][0]['year'] )."' 												WHERE `id` = '".$movie['id']."' " );
			$update = $conn->exec( "UPDATE `vod` SET `poster` = 'https://image.tmdb.org/t/p/w500".addslashes( $tmdb['results'][0]['poster_path'] )."' 		WHERE `id` = '".$movie['id']."' " );
			$update = $conn->exec( "UPDATE `vod` SET `match` = 'yes' WHERE `id` = '".$movie['id']."' " );
		} else {
			if( $verbose == true ) {
				console_output( "- themoviedb.org - movie not found" );
				console_output( "--------------------------------------------------" );
			}
		}

		if( $verbose == true ) {
			console_output( " " );
		}
	}

	if( $verbose == true ) {
		console_output("Finished.");
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'vod_tv_metadata_lookup')
{
	if( $verbose == true ) {
		console_output("TV VoD metadata update.");
	}

	// set allowed file formats
	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	// get a list of all tv shows
	$query 		= $conn->query( "SELECT * FROM `vod_tv` ORDER BY `title` " );
	$shows 		= $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over shows
	foreach( $shows as $show ) {
		// regex the title to remove brackets and year for searching
		if( preg_match( "(\([^0-9]*\d+[^0-9]*\))", $show['title'], $result ) ) {
			$show['search_title'] 	= str_replace( $result[0], '', $show['title'] );
			$show['search_title'] 	= str_replace( array( '(', ')' ), '', $show['search_title'] );
			$show['search_title'] 	= trim( $show['search_title'] );
		} else {
			$show['search_title'] 	= $show['title'];
		}

		if( $verbose == true ) {
			console_output( "TV Show: ".stripslashes( $show['title'] ) );
			console_output( "TV Show (search): ".stripslashes( $show['search_title'] ) );
		}

		// lets get tmdb data for this show
		$tmdb = metadata_tmdb_show( $show['search_title'] );

		// did we match the show
		if( $tmdb['total_results'] > 0 ) {
			if( $verbose == true ) {
				console_output( "themoviedb.org - show id: ".$tmdb['results'][0]['id'] );
				console_output( "themoviedb.org - show name: ".$tmdb['results'][0]['name'] );
			}

			// update the show data
			// format the air_date
			$temp_date = date_create( $tmdb['results'][0]['first_air_date'] );
			$tmdb['results'][0]['first_air_date'] = date_format( $temp_date, "Y" );

			// $update = $conn->exec( "UPDATE `vod_tv` SET `title` = '".addslashes( $tmdb['results'][0]['original_name'] )."' 										WHERE `id` = '".$show['id']."' " );
			$update = $conn->exec( "UPDATE `vod_tv` SET `description` = '".addslashes( $tmdb['results'][0]['overview'] )."' 									WHERE `id` = '".$show['id']."' " );
			$update = $conn->exec( "UPDATE `vod_tv` SET `plot` = '".addslashes( $tmdb['results'][0]['overview'] )."' 											WHERE `id` = '".$show['id']."' " );
			$update = $conn->exec( "UPDATE `vod_tv` SET `year` = '".addslashes( $tmdb['results'][0]['first_air_date'] )."' 										WHERE `id` = '".$show['id']."' " );
			$update = $conn->exec( "UPDATE `vod_tv` SET `poster` = 'https://image.tmdb.org/t/p/w500".addslashes( $tmdb['results'][0]['poster_path'] )."' 		WHERE `id` = '".$show['id']."' " );
			$update = $conn->exec( "UPDATE `vod_tv` SET `match` = 'yes' WHERE `id` = '".$show['id']."' " );

			// unset temp_date for the next run
			unset( $temp_date );

			// get a list of episodes for this show that have not yet matched
			$query 		= $conn->query( "SELECT * FROM `vod_tv_files` WHERE `vod_id` = '".$show['id']."' AND `match` = 'no' " );
			$episodes 	= $query->fetchAll( PDO::FETCH_ASSOC );

			// loop over episodes
			foreach( $episodes as $episode ) {
				// get the filename from the path
				$file 			= basename( $episode['file_location'] );

				if( $verbose == true ) {
					console_output( "- Filename: ".$file );
				}

				// get the file extension
				$file_ext 		= pathinfo( $file, PATHINFO_EXTENSION );

				// try and find the season and episode numbers
				$episode['new']['preg_match'] = 0;

				// find season and episode data from $file
				if( preg_match( "#S([0-9]{2})E([0-9]{2})#msui", $file, $result ) ) {
					$episode['new']['season'] 	= $result[1];
					$episode['new']['episode'] 	= $result[2];
					if( preg_match( "#(.*)S".$result[1]."E".$result[2]."#msui", $file, $result2 ) ) {
						// $episode['new']['name'] = ucwords( trim( str_replace(".", " ", $result2[1] ) ) );
					}
					$episode['new']['preg_match'] = 1;
				} elseif( preg_match( "#([0-9]{1,2})x([0-9]{2})#", $file, $result ) ) {
					$episode['new']['season'] 	= $result[1];
					$episode['new']['episode'] 	= $result[2];
					if(preg_match( "#(.*)".$result[1]."x".$result[2]."#", $file, $result2 ) ) {
						// $episode['new']['name'] = ucwords( trim( str_replace( ".", " ", $result2[1] ) ) );
					}
					$episode['new']['preg_match'] = 2;
				} elseif( preg_match_all( "#[. ]([0-9] )([0-9]{2})[. ]#", $file, $result, PREG_SET_ORDER ) ) {
					$result = end( $result );
					$episode['new']['season'] = ($result[1]<10 ? "0".$result[1] : $result[1] );
					$episode['new']['episode'] = $result[2];
					if (preg_match( "#(.*)".$result[1].$episode['new']['episode']."#", $file, $result2 ) ) {
						// $episode['new']['name'] = ucwords( trim( str_replace( ".", " ", $result2[1] ) ) );
					}
					$episode['new']['preg_match'] = 3;
				}

				// did we match season and episode
				if( isset( $episode['new']['season'] ) && isset( $episode['new']['episode'] ) ) {
					if( $verbose == true ) {
						console_output( "- Season: ".$episode['new']['season'] );
						console_output( "- Episode: ".$episode['new']['episode'] );
					}

					// update season and episode numbers
					$update = $conn->exec( "UPDATE `vod_tv_files` SET `season` = '".$episode['new']['season']."' WHERE `id` = '".$episode['id']."' " );
					$update = $conn->exec( "UPDATE `vod_tv_files` SET `episode` = '".$episode['new']['episode']."' WHERE `id` = '".$episode['id']."' " );

					// lets find info for this season
					$tmdb_episode = metadata_tmdb_episode( $verbose, $tmdb['results'][0]['id'], $episode['new']['season'], $episode['new']['episode'] );

					// sanity check
					if( !isset( $tmdb_episode['id'] ) ) {
						if( $verbose == true ) {
							console_output( "- themoviedb.org - no episode match found" );
						}
					} else {
						// format the air_date
						$temp_date = date_create( $tmdb_episode['air_date'] );
						$tmdb_episode['air_date'] = date_format( $temp_date, "d M Y" );

						// update episode data
						$update = $conn->exec( "UPDATE `vod_tv_files` SET `title` = '".addslashes( $tmdb_episode['name'] )."' 					WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `vod_tv_files` SET `release_date` = '".$tmdb_episode['air_date']."' 						WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `vod_tv_files` SET `plot` = '".addslashes( $tmdb_episode['overview'] )."' 				WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `vod_tv_files` SET `preg_match` = '".$episode['new']['preg_match']."' 					WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `vod_tv_files` SET `match` = 'yes' 														WHERE `id` = '".$episode['id']."' " );
						
						if( $verbose == true ) {
							console_output( "- themoviedb.org - episode name: ".$tmdb_episode['name'] );
							console_output( "- themoviedb.org - episode aired: ".$tmdb_episode['air_date'] );
						}
					}

					// anti flood, sleep for 1 second
					sleep( 1 );
				} else {
					if( $verbose == true ) {
						console_output( "- PREG_MATCH ERROR: Unable to locate season and episode numbers from file name" );
					}
				}

				if( $verbose == true ) {
					console_output( "--------------------------------------------------" );
				}
			}
		} else {
			if( $verbose == true ) {
				console_output( "- themoviedb.org - show not found" );
				console_output( "--------------------------------------------------" );
			}
		}

		if( $verbose == true ) {
			console_output( " " );
		}
	}

	if( $verbose == true ) {
		console_output("Finished.");
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'channels_247_metadata_lookup')
{
	if( $verbose == true ) {
		console_output("24/7 Channels metadata update.");
	}

	// set allowed file formats
	$allowed_files 	= array( 'mk4','mkv','mp4','flv','avi','mpeg','ts' );

	// get a list of all tv shows
	$query 		= $conn->query( "SELECT * FROM `channels_247` ORDER BY `title` " );
	$shows 		= $query->fetchAll( PDO::FETCH_ASSOC );

	// loop over shows
	foreach( $shows as $show ) {
		// regex the title to remove brackets and year for searching
		if( preg_match( "(\([^0-9]*\d+[^0-9]*\))", $show['title'], $result ) ) {
			$show['search_title'] 	= str_replace( $result[0], '', $show['title'] );
			$show['search_title'] 	= str_replace( array( '(', ')' ), '', $show['search_title'] );
			$show['search_title'] 	= trim( $show['search_title'] );
		} else {
			$show['search_title'] 	= $show['title'];
		}

		if( $verbose == true ) {
			console_output( "TV Show: ".stripslashes( $show['title'] ) );
			console_output( "TV Show (search): ".stripslashes( $show['search_title'] ) );
		}

		// lets get tmdb data for this show
		$tmdb = metadata_tmdb_show( $show['search_title'] );

		// did we match the show
		if( $tmdb['total_results'] > 0 ) {
			if( $verbose == true ) {
				console_output( "themoviedb.org - show id: ".$tmdb['results'][0]['id'] );
				console_output( "themoviedb.org - show name: ".$tmdb['results'][0]['name'] );
			}

			// update show if it was not matched and we just found something now
			if( $show['match'] == 'no' ) {
				// update the unmatched show
				// format the air_date
				$temp_date = date_create( $tmdb['results'][0]['first_air_date'] );
				$tmdb['results'][0]['first_air_date'] = date_format( $temp_date, "Y" );

				// $update = $conn->exec( "UPDATE `channels_247` SET `title` = '".addslashes( $tmdb['results'][0]['original_name'] )."' 									WHERE `id` = '".$show['id']."' " );
				$update = $conn->exec( "UPDATE `channels_247` SET `description` = '".addslashes( $tmdb['results'][0]['overview'] )."' 									WHERE `id` = '".$show['id']."' " );
				$update = $conn->exec( "UPDATE `channels_247` SET `plot` = '".addslashes( $tmdb['results'][0]['overview'] )."' 											WHERE `id` = '".$show['id']."' " );
				$update = $conn->exec( "UPDATE `channels_247` SET `year` = '".addslashes( $tmdb['results'][0]['first_air_date'] )."' 									WHERE `id` = '".$show['id']."' " );
				$update = $conn->exec( "UPDATE `channels_247` SET `poster` = 'https://image.tmdb.org/t/p/w500".addslashes( $tmdb['results'][0]['poster_path'] )."' 		WHERE `id` = '".$show['id']."' " );
				$update = $conn->exec( "UPDATE `channels_247` SET `match` = 'yes' 																						WHERE `id` = '".$show['id']."' " );

				// unset temp_date for the next run
				unset( $temp_date );
			}

			// get a list of episodes for this show that have not yet matched
			$query 		= $conn->query( "SELECT * FROM `channels_247_files` WHERE `vod_id` = '".$show['id']."' AND `match` = 'no' " );
			$episodes 	= $query->fetchAll( PDO::FETCH_ASSOC );

			// loop over episodes
			foreach( $episodes as $episode ) {
				// get the filename from the path
				$file 			= basename( $episode['file_location'] );

				if( $verbose == true ) {
					console_output( "- Filename: ".$file );
				}

				// get the file extension
				$file_ext 		= pathinfo( $file, PATHINFO_EXTENSION );

				// try and find the season and episode numbers
				$episode['new']['preg_match'] = 0;

				// find season and episode data from $file
				if( preg_match( "#S([0-9]{2})E([0-9]{2})#msui", $file, $result ) ) {
					$episode['new']['season'] 	= $result[1];
					$episode['new']['episode'] 	= $result[2];
					if( preg_match( "#(.*)S".$result[1]."E".$result[2]."#msui", $file, $result2 ) ) {
						// $episode['new']['name'] = ucwords( trim( str_replace(".", " ", $result2[1] ) ) );
					}
					$episode['new']['preg_match'] = 1;
				} elseif( preg_match( "#([0-9]{1,2})x([0-9]{2})#", $file, $result ) ) {
					$episode['new']['season'] 	= $result[1];
					$episode['new']['episode'] 	= $result[2];
					if(preg_match( "#(.*)".$result[1]."x".$result[2]."#", $file, $result2 ) ) {
						// $episode['new']['name'] = ucwords( trim( str_replace( ".", " ", $result2[1] ) ) );
					}
					$episode['new']['preg_match'] = 2;
				} elseif( preg_match_all( "#[. ]([0-9] )([0-9]{2})[. ]#", $file, $result, PREG_SET_ORDER ) ) {
					$result = end( $result );
					$episode['new']['season'] = ($result[1]<10 ? "0".$result[1] : $result[1] );
					$episode['new']['episode'] = $result[2];
					if (preg_match( "#(.*)".$result[1].$episode['new']['episode']."#", $file, $result2 ) ) {
						// $episode['new']['name'] = ucwords( trim( str_replace( ".", " ", $result2[1] ) ) );
					}
					$episode['new']['preg_match'] = 3;
				}

				// did we match season and episode
				if( isset( $episode['new']['season'] ) && isset( $episode['new']['episode'] ) ) {
					if( $verbose == true ) {
						console_output( "- Season: ".$episode['new']['season'] );
						console_output( "- Episode: ".$episode['new']['episode'] );
					}

					// lets find info for this season
					$tmdb_episode = metadata_tmdb_episode( $verbose, $tmdb['results'][0]['id'], $episode['new']['season'], $episode['new']['episode'] );

					// sanity check
					if( !isset( $tmdb_episode['id'] ) ) {
						if( $verbose == true ) {
							console_output( "- themoviedb.org - no episode match found" );
						}
					} else {
						// format the air_date
						$temp_date = date_create( $tmdb_episode['air_date'] );
						$tmdb_episode['air_date'] = date_format( $temp_date, "d M Y" );

						// update episode data
						$update = $conn->exec( "UPDATE `channels_247_files` SET `title` = '".addslashes( $tmdb_episode['name'] )."' 					WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `channels_247_files` SET `release_date` = '".$tmdb_episode['air_date']."' 						WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `channels_247_files` SET `plot` = '".addslashes( $tmdb_episode['overview'] )."' 					WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `channels_247_files` SET `preg_match` = '".$episode['new']['preg_match']."' 						WHERE `id` = '".$episode['id']."' " );
						$update = $conn->exec( "UPDATE `channels_247_files` SET `match` = 'yes' 														WHERE `id` = '".$episode['id']."' " );
						
						if( $verbose == true ) {
							console_output( "- themoviedb.org - episode name: ".$tmdb_episode['name'] );
							console_output( "- themoviedb.org - episode aired: ".$tmdb_episode['air_date'] );
						}
					}

					// anti flood, sleep for 1 second
					sleep( 1 );
				} else {
					if( $verbose == true ) {
						console_output( "- PREG_MATCH ERROR: Unable to locate season and episode numbers from file name" );
					}
				}

				if( $verbose == true ) {
					console_output( "--------------------------------------------------" );
				}
			}
		} else {
			if( $verbose == true ) {
				console_output( "- themoviedb.org - show not found" );
				console_output( "--------------------------------------------------" );
			}
		}

		if( $verbose == true ) {
			console_output( " " );
		}
	}

	if( $verbose == true ) {
		console_output("Finished.");
		echo "\n\n";
	}

	killlock();
}

if( $script_options['action'] == 'node_checks')
{
	if( $verbose == true ) {
		console_output( "Checking nodes for online / offline status." );
	}

	$query = $conn->query( "SELECT `id`,`updated`,`name`,`status` FROM `servers` WHERE `status` != 'pending' AND `status` != 'installing' " );
	$servers = $query->fetchAll( PDO::FETCH_ASSOC );
	foreach( $servers as $server ) {
		$time_diff = ( time() - $server['updated'] );
		if( $time_diff > 70 ) {
			if( $verbose == true ) {
				console_output( "Server '".stripslashes( $server['name'] )."' appears offline." );
			}
			$update = $conn->exec( "UPDATE `servers` SET `status` = 'offline' 			WHERE `id` = '".$server['id']."' " );

			// $update = $conn->exec( "UPDATE `channels` SET `status` = 'offline' 			WHERE `server_id` = '".$server['id']."' " );
			
			// $update = $conn->exec( "UPDATE `channels` SET `uptime` = '' 				WHERE `server_id` = '".$server['id']."' " );

			// set channels_247 to offline
			$update = $conn->exec( "UPDATE `channels_247` SET `status` = 'offline' 		WHERE `server_id` = '".$server['id']."' " );
			$update = $conn->exec( "UPDATE `channels_247` SET `uptime` = '' 			WHERE `server_id` = '".$server['id']."' " );
		} else {
			if( $verbose == true ) {
				console_output( "Server '".stripslashes( $server['name'] )."' appears online." );
			}
		}
	}
	console_output( "Finished." );
}

if( $script_options['action'] == 'channels_checks')
{
	if( $verbose == true ) {
		console_output( "Checking channels for online / offline status." );
	}

	$runs = 1;
	
	// check based upon channel status
	$query = $conn->query( "SELECT * FROM `channels` " );
	$channels = $query->fetchAll( PDO::FETCH_ASSOC );

    $count 				= count( $channels );

    foreach( $channels as $channel ) {
    	if( $channel['status'] == 'offline' ) {
    		$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'offline'		WHERE `channel_id` = '".$channel['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `uptime` = '' 			WHERE `channel_id` = '".$channel['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '' 				WHERE `channel_id` = '".$channel['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `running_pid` = '0' 		WHERE `channel_id` = '".$channel['id']."' " );
    	}
    }

    // check based upon server status
	$query = $conn->query( "SELECT * FROM `servers` " );
	$servers = $query->fetchAll( PDO::FETCH_ASSOC );

    $count 				= count( $servers );

    foreach( $servers as $server ) {
    	if( $server['status'] == 'offline' ) {
    		$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'offline' 		WHERE `type` = 'primary' AND `primary_server_id` = '".$server['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `uptime` = '' 			WHERE `type` = 'primary' AND `primary_server_id` = '".$server['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '' 				WHERE `type` = 'primary' AND `primary_server_id` = '".$server['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `running_pid` = '0' 		WHERE `type` = 'primary' AND `primary_server_id` = '".$server['id']."' " );

    		$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'offline' 		WHERE `type` = 'secondary' AND `secondary_server_id` = '".$server['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `uptime` = '' 			WHERE `type` = 'secondary' AND `secondary_server_id` = '".$server['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `stats` = '' 				WHERE `type` = 'secondary' AND `secondary_server_id` = '".$server['id']."' " );
    		$update = $conn->exec( "UPDATE `channels_servers` SET `running_pid` = '0' 		WHERE `type` = 'secondary' AND `secondary_server_id` = '".$server['id']."' " );
    	}
    }

    // check based upon last updated
    $query = $conn->query( "SELECT * FROM `channels` WHERE `status` = 'online' " );
	$channels = $query->fetchAll( PDO::FETCH_ASSOC );

    foreach( $channels as $channel ) {
    	$time_diff = ( time() - $channel['updated'] );
		if( $time_diff > 120 ) {
    		$update = $conn->exec( "UPDATE `channels_servers` SET `status` = 'offline' 		WHERE `channel_id` = '".$channel['id']."' " );

    		$update = $conn->exec( "UPDATE `channels` SET `status` = 'offline' 				WHERE `id` = '".$channel['id']."' " );
    	}
    }

    if( $verbose == true ) {
		console_output( "Finished." );
	}
}

if( $script_options['action'] == 'epg_checks')
{
	if( $verbose == true ) {
		console_output( "Check EPG sources." );
	}
	
	// get all epg sources
	$query = $conn->query( "SELECT * FROM `epg` " );
	$epg_sources = $query->fetchAll( PDO::FETCH_ASSOC );

	foreach( $epg_sources as $epg_source ) {
		if( $verbose == true ) {
			console_output( "==============================" );
			console_output( "- EPG Name: ".$epg_source['name'] );
			console_output( "- EPG Source: ".$epg_source['source'] );
		}

	    $file_ext = pathinfo( $epg_source['source'], PATHINFO_EXTENSION );
		if( $file_ext == 'gz' ) {
			// download and extract source file
			$epg_content = shell_exec( "wget -qO- ".$epg_source['source']." | gunzip" );
		} elseif( $file_ext == 'xz' ) {
			// download and extract source file
			$epg_content = shell_exec( "wget -qO- ".$epg_source['source']." | unxz -c" );
		}

		if( !empty( $epg_content ) ) {
			if( $verbose == true ) {
				console_output( "- EPG Source Status: online" );
			}

			$input_lines 		= mb_convert_encoding( $epg_content, "UTF-8", "ISO-8859-1" );

			preg_match_all( '/<channel id="(.*)">\s+<display-name(.*)>(.*)<\/display-name>/', $input_lines, $output_array );

			$xml_ids 		= $output_array[1];
			$xml_langs 		= $output_array[2];
			$xml_names 		= $output_array[3];

			// $delete = $conn->query( "DELETE FROM `epg_xml_ids` " );

			foreach( $xml_ids as $key => $value ) {

				$channel_id 		= $value;
				$channel_name 		= $xml_names[$key];
				$channel_lang 		= $xml_langs[$key];

				$channel_lang 		= str_replace( "lang=", "", $channel_lang );
				$channel_lang 		= str_replace( '"', "", $channel_lang );

				if( $verbose == true ) {
					console_output( "-> Adding Channel ID: ".$channel_name );
				}

				$insert = $conn->exec( "INSERT IGNORE INTO `epg_xml_ids` 
			        (`epg_source_id`,`xml_id`,`xml_name`,`xml_language`)
			        VALUE
			        ('".$epg_source['id']."', '".$channel_id."', '".$channel_name."','".$channel_lang."')" );
			}

			if( $verbose == true ) {
				console_output( "==============================" );
			}
		} else {
			if( $verbose == true ) {
				console_output( "- EPG Source Status: offline" );
			}
		}

		$update = $conn->exec( "UPDATE `epg` SET `updated` = '".time()."' WHERE `id` = '".$epg_source['id']."' " );
	}

    if( $verbose == true ) {
		console_output( "Finished." );
	}
}

if( $script_options['action'] == 'playlist_inspector')
{
	if( $verbose == true ) {
		console_output( "Running Playlist Inspector" );
	}

	// calc one day is seconds
	$one_day = 86400;
	
	// get all playlists with inspector enabled
	$query = $conn->query( "SELECT * FROM `playlist_manager` WHERE `inspector` = 'enable' " );
	$playlists = $query->fetchAll( PDO::FETCH_ASSOC );

	foreach( $playlists as $playlist ) {
		// flood control
		if( !empty( $playlist['updated'] ) ) {
			$checkpoint = ( $playlist['updated'] + 86400 );
		} else {
			$checkpoint = 0;
		}

		if( $checkpoint < time() || $checkpoint == 0 ) {
			if( $verbose == true ) {
				console_output( "==============================" );
				console_output( "- Playlist Name: ".$playlist['name'] );
			}

			// get content for this playlist
			$query = $conn->query( "SELECT * FROM `playlist_manager_content` WHERE `playlist_id` = '".$playlist['id']."' " );
			$playlist['data'] = $query->fetchAll( PDO::FETCH_ASSOC );

			if( !isset( $playlist['data'][0] ) ) {
				if( $verbose == true ) {
					console_output( "- -> Building Datasets" );
				}
				$query      = $conn->query( "SELECT * FROM `playlist_manager` WHERE `id` = '".$playlist['id']."' " );
			    $temp_playlist   = $query->fetch( PDO::FETCH_ASSOC );
			    $temp_playlist['data'] = json_decode( $temp_playlist['data'], true );
			    foreach( $temp_playlist['data'] as $data ) {
			    	if( !empty( $data['media'] ) ) {
				    	$insert = $conn->exec( "INSERT INTO `playlist_manager_content` 
					        (`playlist_id`,`tvg-name`,`group-title`,`tvg-id`,`tvg-logo`,`media`)
					        VALUE
					        ('".$playlist['id']."',
					        '".( !empty( $data['tvg-name'] ) ? $data['tvg-name'] : '' )."',
					        '".( !empty( $data['group-title'] ) ? $data['group-title'] : '' )."',
					        '".( !empty( $data['tvg-id'] ) ? $data['tvg-id'] : '' )."',
					        '".( !empty( $data['tvg-logo'] ) ? $data['tvg-logo'] : '' )."',
					        '".( !empty( $data['media'] ) ? $data['media'] : '' )."'
					    )" );
				    }
			    }

			    $query = $conn->query( "SELECT * FROM `playlist_manager_content` WHERE `playlist_id` = '".$playlist['id']."' " );
				$playlist['data'] = $query->fetchAll( PDO::FETCH_ASSOC );
			} else {
				if( $verbose == true ) {
					console_output( "- - > Polling Datasets" );
				}
			}

			foreach( $playlist['data'] as $data ) {
				// parse ffprobe checks
				$probe = shell_exec( "timeout 30 ffprobe -v quiet -print_format json -show_format -show_streams -analyzeduration 128000 -probesize 128000 '".$data['media']."' " );
				
				// decode json array
				$probe = json_decode( $probe, true );

				// process results
				if( isset( $probe['streams'] ) ) {
					// save the results
					$update = $conn->exec( "UPDATE `playlist_manager_content` SET `stats` = '".json_encode( $probe )."' WHERE `id` = '".$data['id']."' " );
					
					$update = $conn->exec( "UPDATE `playlist_manager_content` SET `status` = 'online' WHERE `id` = '".$data['id']."' " );

					if( $verbose == true ) {
						console_output( "- -> Item Name: ".$data['tvg-name'].' - '.$colors->getColoredString( "ONLINE", "black", "green" ) );
					}
				} else {
					$update = $conn->exec( "UPDATE `playlist_manager_content` SET `status` = 'offline' WHERE `id` = '".$data['id']."' " );

					if( $verbose == true ) {
						console_output( "- -> Item Name: ".$data['tvg-name'].' - '.$colors->getColoredString( "OFFLINE", "black", "red" ) );
					}
				}

				$update = $conn->exec( "UPDATE `playlist_manager_content` SET `updated` = '".time()."' WHERE `id` = '".$data['id']."' " );
			}

			if( $verbose == true ) {
				console_output( "==============================" );
			}

			$update = $conn->exec( "UPDATE `playlist_manager` SET `updated` = '".time()."' WHERE `id` = '".$playlist['id']."' " );
		} else {
			if( $verbose == true ) {
				console_output( "==============================" );
				console_output( "- Playlist Name: ".$playlist['name'] );
				console_output( "- Flood Control Triggered" );
				console_output( "==============================" );
			}
		}
	}

    if( $verbose == true ) {
		console_output( "Finished." );
	}
}

if( $script_options['action'] == 'customer_checks')
{
	if( $verbose == true ) {
		console_output( "Checking customers for various things." );
	}

	$now = time();

	// get packages for costs
	$query = $conn->query( "SELECT * FROM `packages` " );
	$packages = $query->fetchAll( PDO::FETCH_ASSOC );

	// get customers
	$query = $conn->query( "SELECT * FROM `customers` " );
	$customers = $query->fetchAll( PDO::FETCH_ASSOC );
	
	foreach($customers as $customer) {
		$expire_date = $customer['expire_date'] ;

		if( time() > $expire_date ) {
			// check if customer has credits to renew

			// get package cost
			foreach( $packages as $package ) {
				if( $customer['package_id'] == $package['id'] ) {
					$package_cost = $package['credits'];
					break;
				}
			} 
			
			if( $customer['credits'] >= $package_cost ) {
				// customer has credits, pay the package cost and leave / mark enabled and deduct credits from the customer
				$update = $conn->exec( "UPDATE `customers` SET `status` = 'active' WHERE `id` = '".$customer['id']."' " );

				// new expire date, 1 month
				$new_expire_date = strtotime( "+1 month", time() );
				$update = $conn->exec( "UPDATE `customers` SET `expire_date` = '".$new_expire_date."' WHERE `id` = '".$customer['id']."' " );

				$new_customer_balance = ( $customer['credits'] - $package_cost );

				$update = $conn->exec( "UPDATE `customers` SET `credits` = '".$new_customer_balance."' WHERE `id` = '".$customer['id']."' " );

				console_output( "Customer: ".$customer['username']." expired and had enough credits, updating records." );
			} else {
				// customer account expired, update it
	        	$update = $conn->exec( "UPDATE `customers` SET `status` = 'expired' WHERE `id` = '".$customer['id']."' " );

	        	console_output( "Customer: ".$customer['username']." has expired but didnt have enough credits, updating records." );
			}
	    }
	}
	console_output( "Finished." );
}

if( $script_options['action'] == 'backup_manager')
{
	if( $verbose == true ) {
		console_output( "Running Backup Manager" );
	}

	$date = date( "Y-m-d_h:i", time() );
	
	exec( "mkdir -p /opt/stiliam-backups" );
	exec( "sudo chmod 777 /opt/stiliam-backups" );

	shell_exec( "mysqldump -u stiliam -pstiliam1984 cms | gzip > /opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" );

	if( file_exists( "/opt/stiliam-backups/".$date."_stiliam_cms.sql.gz" ) ) {
		if( $verbose == true ) {
			console_output( "Backup created." );
		}
	}else{
		if( $verbose == true ) {
			console_output( "Backup not created." );
		}
	}

	if( $verbose == true ) {
		console_output( "Finished." );
	}
}

if( $task == 'stream_checks_process')
{
	$stream_id = $argv[2];

	$now = time();

	$query = $conn->query( "SELECT * FROM `streams` WHERE `id` = '".$stream_id."' " );
	$streams = $query->fetchAll( PDO::FETCH_ASSOC );
	foreach($streams as $stream) {
		$stream['output_options'] = json_decode($stream['output_options'], true); 

		// get headend data for this stream
		$query = $conn->query( "SELECT * FROM `headend_servers` WHERE `id` = '".$stream['server_id']."' " );
		$stream['headend'] = $query->fetchAll( PDO::FETCH_ASSOC );
		
		foreach($stream['output_options'] as $key => $output_options) {
			// build stream_url
			$screen_resolution = explode('x', $output_options['screen_resolution'] );

			if( $stream['headend'][0]['output_type'] == 'rtmp') {
				$stream['stream_url'] = 'http://'.$stream['headend'][0]['wan_ip_address'].':'.$stream['headend'][0]['http_stream_port'].'/hls/'.$stream['publish_name'].'/index.m3u8';
			} else {
				$stream['stream_url'] = 'http://'.$stream['headend'][0]['wan_ip_address'].':'.$stream['headend'][0]['http_stream_port'].'/play/hls/'.$stream['publish_name'].'_'.$screen_resolution[1].'/index.m3u8';
			}

			// make sure the IP is in the firewall for outgoing connections
			shell_exec( "sudo csf -a " . $stream['headend'][0]['wan_ip_address'] );

			// console_output( "Testing URL: " . $stream['stream_url'] );

			$stream['test_results'] = shell_exec( "/etc/ffmpeg/ffprobe -v quiet -print_format json -show_format -show_streams ".$stream['stream_url'] );
			
			$stream['test_results'] = json_decode($stream['test_results'], true);

			// update status
			if( isset($stream['test_results']['streams'] )) {
				console_output( "Stream: '".stripslashes( $stream['name'] . ' ' . strtoupper($key))."' appears ".$colors->getColoredString("online.", "green", "black"));
				$output_options['status'] = 'online';
			}elseif( !isset($stream['test_results']['streams'] )) {
				console_output( "Stream: '".stripslashes( $stream['name'] . ' ' . strtoupper($key))."' appears ".$colors->getColoredString("offline.", "red", "black"));
				$output_options['status'] = 'offline';
			} else {
				console_output( "Stream: '".stripslashes( $stream['name'] . ' ' . strtoupper($key))."' status =  ".$colors->getColoredString("UNKNOWN.", "blue", "black"));
				$output_options['status'] = 'unknown';

				print_r($stream['test_results'] );
			}

			$save_results[$key] = $output_options;
		}

		$update = $conn->exec( "UPDATE `streams` SET `output_options` = '".json_encode($save_results)."' WHERE `id` = '".$stream['id']."' " );

	}
}

if( $task == 'stream_ondemand_check' )
{
	$time_shift = time() - 120;

	console_output( "Checking on-demand channels for activity." );

	$query = $conn->query( "SELECT `id`,`server_id`,`running_pid`,`name` FROM `streams` WHERE `stream_type` = 'input' AND `enable` = 'yes' AND `ondemand` = 'yes' " );
	$input_streams = $query->fetchAll( PDO::FETCH_ASSOC );
	
	foreach($input_streams as $input_stream) {

		console_output( "Input Stream: " . stripslashes( $input_stream['name'] ));

		// get output streams
		$query = $conn->query( "SELECT `id` FROM `streams` WHERE `source_stream_id` = '".$input_stream['id']."' " );
		$output_streams = $query->fetchAll( PDO::FETCH_ASSOC );

		$viewers = 0;

		foreach($output_streams as $output_stream) {
			$query = $conn->query( "SELECT `id` FROM `streams_connection_logs` WHERE `stream_id` = '".$output_stream['id']."' AND `timestamp` > '".$time_shift."' GROUP BY 'client_ip' " );
			$online_clients = $query->fetchAll( PDO::FETCH_ASSOC );
			$viewers = $viewers + count($online_clients);
		}

		if( $viewers == 0) {
			console_output( " - " . $viewers . " viewers, stopping." );

			$job['action'] = 'kill_pid';
			$job['command'] = 'kill -9 '.$input_stream['running_pid'];

			// add the job
			$insert = $conn->exec( "INSERT INTO `jobs` 
		        (`server_id`,`job`)
		        VALUE
		        ('".$input_stream['server_id']."','".json_encode($job)."')" );
		} else {
			console_output( " - " . $viewers . " viewers, skipping." );
		}
	}
}

if( $task == 'xc_imports' )
{
	console_output( "Xtream-Codes Import Manager." );
	$now = time();

	$query = $conn->query( "SELECT * FROM `slipstream_cms`.`xc_import_jobs` WHERE `status` = 'pending' LIMIT 1" );
	$import = $query->fetch( PDO::FETCH_ASSOC );

	if( !empty($import)) {

		$user_id = $import['user_id'];
		
		console_output( "Starting Import Job: ".$import['id'] );
		console_output( "User: ".$import['user_id'] );
		console_output( "Filename: /var/www/html/xc_uploads/".$user_id."/".$import['filename'] );
		
		$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'importing' WHERE `id` = '".$import['id']."' " );

		// sanity checks
		if( !file_exists("/var/www/html/xc_uploads/".$user_id."/".$import['filename'] )) {
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Unable to find file.' WHERE `id` = '".$import['id']."' " );
			console_output( "File does not exist or we cannot read it." );
			die();
		}

		// remove database and create it again
		$delete = $conn->exec( "DROP DATABASE IF EXISTS `slipstream_xc_staging`;" );
		$delete = $conn->exec( "CREATE DATABASE `slipstream_xc_staging`;" );

		// parse out the files to import
		exec( "sed -n -e '/DROP TABLE.*`streams`/,/UNLOCK TABLES/p' /var/www/html/xc_uploads/".$user_id."/".$import['filename']." > /var/www/html/xc_uploads/".$user_id."/streams.sql" );
		exec( "sed -n -e '/DROP TABLE.*`users`/,/UNLOCK TABLES/p' /var/www/html/xc_uploads/".$user_id."/".$import['filename']." > /var/www/html/xc_uploads/".$user_id."/users.sql" );
		exec( "sed -n -e '/DROP TABLE.*`reg_users`/,/UNLOCK TABLES/p' /var/www/html/xc_uploads/".$user_id."/".$import['filename']." > /var/www/html/xc_uploads/".$user_id."/reg_users.sql" );
		exec( "sed -n -e '/DROP TABLE.*`packages`/,/UNLOCK TABLES/p' /var/www/html/xc_uploads/".$user_id."/".$import['filename']." > /var/www/html/xc_uploads/".$user_id."/packages.sql" );
		exec( "sed -n -e '/DROP TABLE.*`bouquets`/,/UNLOCK TABLES/p' /var/www/html/xc_uploads/".$user_id."/".$import['filename']." > /var/www/html/xc_uploads/".$user_id."/bouquets.sql" );
		exec( "sed -n -e '/DROP TABLE.*`mag_devices`/,/UNLOCK TABLES/p' /var/www/html/xc_uploads/".$user_id."/".$import['filename']." > /var/www/html/xc_uploads/".$user_id."/mag_devices.sql" );
		
		// import DB files
		console_output( "Importing Xtream-Codes SQL Dump files." );
		exec( "(/usr/bin/mysql -u slipstream -padmin1372 -hlocalhost slipstream_xc_staging < /var/www/html/xc_uploads/".$user_id."/streams.sql) 2>&1", $output, $result);
		exec( "(/usr/bin/mysql -u slipstream -padmin1372 -hlocalhost slipstream_xc_staging < /var/www/html/xc_uploads/".$user_id."/users.sql) 2>&1", $output, $result);
		exec( "(/usr/bin/mysql -u slipstream -padmin1372 -hlocalhost slipstream_xc_staging < /var/www/html/xc_uploads/".$user_id."/reg_users.sql) 2>&1", $output, $result);
		exec( "(/usr/bin/mysql -u slipstream -padmin1372 -hlocalhost slipstream_xc_staging < /var/www/html/xc_uploads/".$user_id."/packages.sql) 2>&1", $output, $result);
		exec( "(/usr/bin/mysql -u slipstream -padmin1372 -hlocalhost slipstream_xc_staging < /var/www/html/xc_uploads/".$user_id."/bouquets.sql) 2>&1", $output, $result);
		exec( "(/usr/bin/mysql -u slipstream -padmin1372 -hlocalhost slipstream_xc_staging < /var/www/html/xc_uploads/".$user_id."/mag_devices.sql) 2>&1", $output, $result);

		// more sanity checks
		try {
        	$result = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`streams` LIMIT 1" );
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        $update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Your backup does not contain a streams table. Try making a new backup and uploading it again. Do NOT trim anything but LOG files.' WHERE `id` = '".$import['id']."' " );
			console_output( "missing streams table." );
			die();
	    }
	    try {
        	$result = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`users` LIMIT 1" );
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        $update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Your backup does not contain a users table. Try making a new backup and uploading it again. Do NOT trim anything but LOG files.' WHERE `id` = '".$import['id']."' " );
			console_output( "missing users table." );
			die();
	    }
	    try {
        	$result = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`reg_users` LIMIT 1" );
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        $update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Your backup does not contain a reg_users table. Try making a new backup and uploading it again. Do NOT trim anything but LOG files.' WHERE `id` = '".$import['id']."' " );
			console_output( "missing reg_users table." );
			die();
	    }
	    try {
        	$result = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`bouquets` LIMIT 1" );
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        $update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Your backup does not contain a bouquets table. Try making a new backup and uploading it again. Do NOT trim anything but LOG files.' WHERE `id` = '".$import['id']."' " );
			console_output( "missing bouquets table." );
			die();
	    }
	    /*
	    try {
        	$result = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`mag_devices` LIMIT 1" );
	    } catch (Exception $e) {
	        // We got an exception == table not found
	        $update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Your backup does not contain a mag_devices table. Try making a new backup and uploading it again. Do NOT trim anything but LOG files.' WHERE `id` = '".$import['id']."' " );
			console_output( "missing mag_devices table." );
			die();
	    }
	    */

		// get first server id
		$query = $conn->query( "SELECT `id` FROM `slipstream_cms`.`headend_servers` WHERE `user_id` = '".$user_id."' LIMIT 1" );
		$server = $query->fetch( PDO::FETCH_ASSOC );
		$server_id = $server['id'];
		if( empty($server_id)) {
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'error' WHERE `id` = '".$import['id']."' " );
			$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `error_message` = 'Please add your first server first.' WHERE `id` = '".$import['id']."' " );
			console_output( "Please add your first server first." );
			die();
		}

		// convert xc streams to ss streams
		$query = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`streams` WHERE `type` = '1' " );
		$xc_streams = $query->fetchAll( PDO::FETCH_ASSOC );

		console_output( "Migrating: ".number_format(count($xc_streams))." streams" );

		foreach($xc_streams as $xc_stream) {
			$rand 				= md5(rand(00000,99999).time());

			$name 				= addslashes($xc_stream['stream_display_name'] );
			$name 				= trim($name);

			$source 			= stripslashes( $xc_stream['stream_source'] );
			$source 			= str_replace(array("[", "]"), "", $source);
			$source 			= explode(",", $source);

			if( is_array($source)) {
				$source = $source[0];
			} else {
				$source = $source;
			}

			$source 			= str_replace('"', "", $source);
			$source 			= addslashes($source);

			$ffmpeg_re			= 'no';

			$logo 				= addslashes($xc_stream['stream_icon'] );

		    // add input stream
			$insert = $conn->exec( "INSERT INTO `slipstream_cms`.`streams` 
		        (`user_id`,`server_id`,`stream_type`,`name`,`enable`,`source`,`cpu_gpu`,`job_status`,`ffmpeg_re`,`logo`,`epg_xml_id`)
		        VALUE
		        ('".$user_id."',
		        '".$server_id."',
		        'input',
		        '".$name."',
		        'no',
		        '".$source."',
		        'cpu',
		        'analysing',
		        '".$ffmpeg_re."',
		        '".$logo."',
		        '".$xc_stream['channel_id']."'
		    )" );

		    $stream_id = $conn->lastInsertId();

		    // add output stream
		    $insert = $conn->exec( "INSERT INTO `slipstream_cms`.`streams` 
		        (`user_id`,`enable`,`server_id`,`stream_type`,`name`,`source_server_id`,`source_stream_id`,`old_xc_id`,`logo`,`epg_xml_id`)
		        VALUE
		        ('".$user_id."',
		        'no',
		        '".$server_id."',
		        'output',
		        '".$name."',
		        '".$server_id."',
		        '".$stream_id."',
		        '".$xc_stream['id']."',
		        '".$logo."',
		        '".$xc_stream['channel_id']."'
		    )" );

			echo ".";
		}
		echo "\n";

		// convert xc packages to ss packages
		$query = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`packages` " );
		$xc_packages = $query->fetchAll( PDO::FETCH_ASSOC );

		console_output( "Migrating: ".number_format(count($xc_packages))." packages" );

		foreach($xc_packages as $xc_package) {
			$xc_package['bouquets'] = str_replace(array("[","]"), "", $xc_package['bouquets'] );

			$insert = $conn->exec( "INSERT INTO `slipstream_cms`.`packages` 
		        (`user_id`,`name`,`is_trial`,`credits`,`trial_duration`,`official_duration`,`bouquets`,`old_xc_id`)
		        VALUE
		        ('".$user_id."',
		        '".addslashes($xc_package['package_name'] )."',
		        '".addslashes($xc_package['is_trial'] )."',
		        '".addslashes($xc_package['official_credits'] )."',
		        '".addslashes($xc_package['trial_duration'] )."',
		        '".addslashes($xc_package['official_duration'] )."',
		        '".addslashes($xc_package['bouquets'] )."',
		        '".addslashes($xc_package['id'] )."'
		    )" );

		    echo ".";
		}
		echo "\n";

		// convert xc bouquet to ss packages
		$query = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`bouquets` " );
		$xc_bouquets = $query->fetchAll( PDO::FETCH_ASSOC );

		console_output( "Migrating: ".number_format(count($xc_bouquets))." bouquets" );

		foreach($xc_bouquets as $xc_bouquet) {
			$xc_bouquet['streams'] = str_replace(array("[","]",'"'), "", $xc_bouquet['bouquet_channels'] );
			
			$old_streams = explode(",", $xc_bouquet['streams'] );

			foreach($old_streams as $old_stream) {
				$query = $conn->query( "SELECT `id` FROM `slipstream_cms`.`streams` WHERE `user_id` = '".$user_id."' AND `old_xc_id` = '".$old_stream."' " );
				$temp_stream = $query->fetch( PDO::FETCH_ASSOC );
				$new_streams[] = $temp_stream['id'];
			}

			$xc_bouquet['streams'] = implode(",", $new_streams);

			$insert = $conn->exec( "INSERT IGNORE INTO `slipstream_cms`.`bouquets` 
		        (`user_id`,`name`,`streams`,`old_xc_id`)
		        VALUE
		        ('".$user_id."',
		        '".addslashes($xc_bouquet['bouquet_name'] )."',
		        '".addslashes($xc_bouquet['streams'] )."',
		        '".addslashes($xc_bouquet['id'] )."'
		    )" );

		    echo ".";
		}
		echo "\n";

		// convert xc users to ss customers
		$query = $conn->query( "SELECT * FROM `slipstream_xc_staging`.`reg_users` " );
		$xc_reg_users = $query->fetchAll( PDO::FETCH_ASSOC );

		console_output( "Migrating: ".number_format(count($xc_reg_users))." resellers" );

		foreach($xc_reg_users as $xc_reg_user) {
			if( $xc_reg_user['status'] == 1) {
				$xc_reg_user['status'] = 'enabled';
			} else {
				$xc_reg_user['status'] = 'disable';
			}

			$password = md5(time().rand(0,9));

			$insert = $conn->exec( "INSERT INTO `slipstream_cms`.`resellers` 
		        (`status`,`updated`,`user_id`,`group_id`,`email`,`username`,`password`,`credits`)
		        VALUE
		        ('".addslashes($xc_reg_user['status'] )."',
		        '".time()."',
		        '".$user_id."',
		        '4',
		        '".addslashes($xc_reg_user['email'] )."',
		        '".addslashes($xc_reg_user['username'] )."',
		        '".$password."',
		        '".addslashes($xc_reg_user['credits'] )."'
		    )" );

		    echo ".";
		}
		echo "\n";

		// convert xc users to ss customers
		$query = $conn->query( "SELECT `id`,`exp_date`,`created_by`,`username`,`password`,`bouquet`,`max_connections`,`admin_notes`,`reseller_notes` FROM `slipstream_xc_staging`.`users` " );
		$xc_users = $query->fetchAll( PDO::FETCH_ASSOC );

		console_output( "Migrating: ".number_format(count($xc_users))." customers" );

		foreach($xc_users as $xc_user) {
			$xc_user_exp_date = date("Y-m-d", $xc_user['exp_date'] );

			if( $xc_user['exp_date'] < time()) {
				$customer_status = 'expired';
			} else {
				$customer_status = 'enabled';
			}

			$old_owner = $xc_user['created_by'];

			$query = $conn->query( "SELECT `username` FROM `slipstream_xc_staging`.`reg_users` WHERE `id` = '".$old_owner."' " );
			$xc_old_owner = $query->fetch( PDO::FETCH_ASSOC );
			$new_owner_username = $xc_old_owner['username'];

			$query = $conn->query( "SELECT `id` FROM `slipstream_cms`.`resellers` WHERE `user_id` = '".$user_id."' AND `username` = '".$new_owner_username."' " );
			$new_owner = $query->fetch( PDO::FETCH_ASSOC );
			$reseller_id = $new_owner['id'];

			$xc_user['bouquet'] = str_replace(array("[","]", '"'), "", $xc_user['bouquet'] );

			$old_bouquets = explode(",", $xc_user['bouquet'] );

			foreach($old_bouquets as $old_bouquet) {
				$query = $conn->query( "SELECT `id` FROM `slipstream_cms`.`bouquets` WHERE `user_id` = '".$user_id."' AND `old_xc_id` = '".$old_bouquet."' " );
				$temp_bouquet = $query->fetch( PDO::FETCH_ASSOC );
				$new_bouquets[] = $temp_bouquet['id'];
			}

			$xc_user['bouquet'] = implode(",", $new_bouquets);

			$insert = $conn->exec( "INSERT IGNORE INTO `slipstream_cms`.`customers` 
		        (`status`,`user_id`,`reseller_id`,`username`,`password`,`expire_date`,`live_content`,`bouquet`,`max_connections`,`notes`,`reseller_notes`,`old_xc_id`)
		        VALUE
		        ('".$customer_status."',
		        '".$user_id."',
		        '".$reseller_id."',
		        '".addslashes($xc_user['username'] )."',
		        '".addslashes($xc_user['password'] )."',
		        '".$xc_user_exp_date."',
		        'on',
		        '".$xc_user['bouquet']."',
		        '".$xc_user['max_connections']."',
		        '".addslashes($xc_user['admin_notes'] )."',
		        '".addslashes($xc_user['reseller_notes'] )."',
		       	'".$xc_user['id']."'
		    )" );

		    unset($old_bouquets);
		    unset($new_bouquets);
		
		    echo ".";
		}
		echo "\n";

		// convert mag_devices to ss customers
		$query = $conn->query( "SELECT `user_id`,`mag_id`,`mac`,`bright`,`aspect` FROM `slipstream_xc_staging`.`mag_devices` " );
		$xc_mags = $query->fetchAll( PDO::FETCH_ASSOC );

		console_output( "Migrating: ".number_format(count($xc_mags))." mag_devices" );

		foreach($xc_mags as $xc_mag) {
			// old customer_id
			$old_customer_id = $xc_mag['user_id'];

			// get new customer_id
			$query = $conn->query( "SELECT `id` FROM `slipstream_cms`.`customers` WHERE `user_id` = '".$user_id."' AND `old_xc_id` = '".$old_customer_id."' " );
			$customer = $query->fetch( PDO::FETCH_ASSOC );

			if( empty($customer)) {
				$customer['id'] = 0;
			}

			$insert = $conn->exec( "INSERT INTO `slipstream_cms`.`mag_devices` 
		        (`user_id`,`customer_id`,`mac`,`aspect`,`old_xc_id`)
		        VALUE
		        ('".$user_id."',
		        '".$customer['id']."',
		        '".$xc_mag['mac']."',
		        '".addslashes($xc_mag['aspect'] )."',
		        '".addslashes($xc_mag['mag_id'] )."'
		    )" );

		    echo ".";
		}
		echo "\n";

		// remove files

		$update = $conn->exec( "UPDATE `slipstream_cms`.`xc_import_jobs` SET `status` = 'complete' WHERE `id` = '".$import['id']."' " );
	}

	console_output( "Finished." );
}

if( $task == 'xc_imports_mac_fix' )
{
	console_output( "Xtream-Codes Import Manager - MAG MAC Fix." );

	// convert mag_devices to ss customers
	$query 		= $conn->query( "SELECT `mag_id`,`mac` FROM `slipstream_xc_staging`.`mag_devices` " );
	$xc_mags 	= $query->fetchAll( PDO::FETCH_ASSOC );

	console_output( "Updating: ".number_format(count($xc_mags))." mag_devices" );

	foreach($xc_mags as $xc_mag) {
		$old_mag_id = $xc_mag['mag_id'];

		/*
		if( base64_decode($xc_mag['mac'], true)) {
			$update = $conn->exec( "UPDATE `slipstream_cms`.`mag_devices` SET `mac` = '".$xc_mag['mac']."' WHERE `old_xc_id` = '".$old_customer_id."' " );
		} else {
		    $update = $conn->exec( "UPDATE `slipstream_cms`.`mag_devices` SET `mac` = '".base64_encode($xc_mag['mac'] )."' WHERE `old_xc_id` = '".$old_customer_id."' " );
		}
		*/

		// console_output( "UPDATE `slipstream_cms`.`mag_devices` SET `mac` = '".$xc_mag['mac']."' WHERE `old_xc_id` = '".$old_mag_id."';" );

		$update = $conn->exec( "UPDATE `slipstream_cms`.`mag_devices` SET `mac` = '".$xc_mag['mac']."' WHERE `old_xc_id` = '".$old_mag_id."' " );
	    echo ".";
	}

	echo "\n";

	console_output( "Finished." );
}

if( $script_options['action'] == 'stalker_sync')
{
	if( $verbose == true ) {
		console_output( "Ministra Sync" );
	}

	$streaming_servers 		= get_all_servers();

	// channel_categories > tv_genre
	$query 					= $conn->query( "SELECT * FROM `cms`.`channels_categories` ORDER BY `id` " );
	$channel_categories 	= $query->fetchAll( PDO::FETCH_ASSOC );
	$channel_categories 	= stripslashes_deep( $channel_categories );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $channel_categories ) )." channel_categories" );
	}

	// 24/7 channel category override
	$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`tv_genre` WHERE `title` = '24/7 Channels' LIMIT 1" );
	$existing_category 		= $query->fetch( PDO::FETCH_ASSOC );

	if( !isset( $existing_category['id'] ) ) {
		$insert = $conn->exec( "INSERT INTO `stalker_db`.`tv_genre` 
	        (`id`,`title`,`number`,`modified`,`censored`)
	        VALUE
	        ('999999',
	        '24/7 Channels',
	        '999999',
	        '2020-01-01 00:00:00',
	        '0'
	    )" );
	}

	foreach( $channel_categories as $channel_category ) {
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`tv_genre` WHERE `id` = '".$channel_category['id']."' LIMIT 1" );
		$existing_category 		= $query->fetch( PDO::FETCH_ASSOC );

		if( !isset($existing_category['id'] )) {
			$insert = $conn->exec( "INSERT INTO `stalker_db`.`tv_genre` 
		        (`id`,`title`,`number`,`modified`,`censored`)
		        VALUE
		        ('".$channel_category['id']."',
		        '".addslashes( $channel_category['name'] )."',
		        '".$channel_category['id']."',
		        '2020-01-01 00:00:00',
		        '0'
		    )" );
		} else {
			$update = $conn->exec( "UPDATE `stalker_db`.`tv_genre` SET `title` = '".addslashes( $channel_category['name'] )."' WHERE `id` = '".$channel_category['id']."' " );
		}
	}

	if( $verbose == true ) {
		console_output( " " );
		console_output( "======================================================================" );
		console_output( " " );
	}

	// packages > tarrif_plan
	$query 					= $conn->query( "SELECT * FROM `cms`.`packages` ORDER BY `id` " );
	$packages 				= $query->fetchAll( PDO::FETCH_ASSOC );
	$packages 				= stripslashes_deep( $packages );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $packages ) )." packages to tariffs" );
	}

	foreach( $packages as $package ) {
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`tariff_plan` WHERE `id` = '".$package['id']."' LIMIT 1" );
		$existing_tarrif 		= $query->fetch( PDO::FETCH_ASSOC );

		if( !isset( $existing_tarrif['id'] ) ) {
		    $insert = $conn->exec( "INSERT INTO `stalker_db`.`tariff_plan` 
		        (`id`,`external_id`,`name`,`user_default`,`days_to_expires`)
		        VALUE
		        ('".$package['id']."',
		        '',
		        '".addslashes( $package['name'] )."',
		        '0',
		        '0'
		    )" );
		} else {
			$update = $conn->exec( "UPDATE `stalker_db`.`tariff_plan` SET `name` = '".addslashes( $package['name'] )."' WHERE `id` = '".$package['id']."' " );
		}

		// convert bouquets to package_in_plan
		$delete = $conn->exec( "DELETE FROM `stalker_db`.`package_in_plan` WHERE `package_id` = '".$package['id']."' " );
		
		$bouquets = explode(",", $package['bouquets'] );
		$bouquets = array_filter( $bouquets );

		foreach($bouquets as $bouquet) {
			$insert = $conn->exec( "INSERT INTO `stalker_db`.`package_in_plan` 
		        (`package_id`,`plan_id`,`optional`,`modified`)
		        VALUE
		        ('".$bouquet."',
		        '".$package['id']."',
		        '0',
		        '2020-01-01 00:00:00'
		    )" );
		}
	}

	if( $verbose == true ) {
		console_output( " " );
		console_output( "======================================================================" );
		console_output( " " );
	}

	// channels > itv, ch_links
	$query 					= $conn->query( "SELECT * FROM `cms`.`channels` ORDER BY `id` " );
	$channels 				= $query->fetchAll( PDO::FETCH_ASSOC );
	$channels 				= stripslashes_deep( $channels );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $channels ) )." channels to channels" );
	}

	foreach( $channels as $channel ) {
		if( $verbose == true ) {
			console_output( "Name: ".$channel['title'] );
		}

		// account for no category
		if( empty( $channel['category_id'] ) ) {
			$channel['category_id'] = 1;
		}

		// build internal source urls from output servers
		// $query 					= $conn->query( "SELECT `secondary_server_id` FROM `cms`.`channels_servers` WHERE `channel_id` = '".$channel['id']."' AND `type` = 'secondary' AND `status` = 'online' ORDER BY `id` " );
		$query 					= $conn->query( "SELECT `secondary_server_id` FROM `cms`.`channels_servers` WHERE `channel_id` = '".$channel['id']."' AND `type` = 'secondary' ORDER BY `id` " );
		$channel_output_servers = $query->fetchAll( PDO::FETCH_ASSOC );

		foreach( $channel_output_servers as $channel_output_server ){
			foreach( $streaming_servers as $streaming_server ) {
				if( $channel_output_server['secondary_server_id'] == $streaming_server['id'] ) {
					$channel_servers[] = $streaming_server;
					break;
				}
			}
		}

		$channel['source']		= "http://".$channel_servers[0]['wan_ip_address'].":".$channel_servers[0]['http_stream_port']."/play/hls/".$channel['id']."_.m3u8?token=".$global_settings['master_token'];

		// check for existing stream
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`itv` WHERE `id` = '".$channel['id']."' LIMIT 1" );
		$existing_channel 		= $query->fetch( PDO::FETCH_ASSOC );
		if( !isset( $existing_channel['id'] ) ) {
			if( $verbose == true ) {
				console_output( " - New channel found" );
			}

			$insert = $conn->exec( "INSERT IGNORE INTO `stalker_db`.`itv` 
		        (`id`,`name`,`number`,`censored`,`cmd`,`tv_genre_id`,`service_id`,`modified`,`xmltv_id`)
		        VALUE
		        ('".$channel['id']."',
		        '".addslashes( $channel['title'] )."',
		        '".$channel['id']."',
		        '0',
		        '".$channel['source']."',
		        '".$channel['category_id']."',
		        '',
		        '2020-01-01 00:00:00',
		        '".$channel['epg_xml_id']."'
		    )" );
		} else {
			if( $verbose == true ) {
				console_output( " - Existing channel" );
			}

			$update = $conn->exec( "UPDATE `stalker_db`.`itv` SET `name` = '".addslashes( $channel['title'] )."' 		WHERE `id` = '".$channel['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`itv` SET `cmd` = '".$channel['source']."' 						WHERE `id` = '".$channel['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`itv` SET `tv_genre_id` = '".$channel['category_id']."' 		WHERE `id` = '".$channel['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`itv` SET `xmltv_id` = '".$channel['epg_xml_id']."' 			WHERE `id` = '".$channel['id']."' " );
		}

		// does the channel_source for this channel exist?
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`ch_links` WHERE `ch_id` = '".$channel['id']."' LIMIT 1" );
		$existing_channel_link 	= $query->fetch( PDO::FETCH_ASSOC );

		if( $verbose == true ) {
			console_output( " Output Servers: ".count( $channel_servers ) );
		}

		if( !isset( $existing_channel_link['id'] ) ) {
			foreach( $channel_servers as $channel_server ) {
				// build the secondary server source url
				$channel['source']		= "http://".$channel_server['wan_ip_address'].":".$channel_server['http_stream_port']."/play/hls/".$channel['id']."_.m3u8?token=".$global_settings['master_token'];

			    $insert = $conn->exec( "INSERT INTO `stalker_db`.`ch_links` 
			        (`ch_id`,`url`,`status`,`changed`)
			        VALUE
			        ('".$channel['id']."',
			        '".$channel['source']."',
			        '1',
			        '2020-01-01 00:00:00'
			    )" );
			}
		} else {
			foreach( $channel_servers as $channel_server ) {
				// build the secondary server source url
				$channel['source']		= "http://".$channel_server['wan_ip_address'].":".$channel_server['http_stream_port']."/play/hls/".$channel['id']."_.m3u8?token=".$global_settings['master_token'];

				$update = $conn->exec( "UPDATE `stalker_db`.`ch_links` SET `url` = '".$channel['source']."' WHERE `ch_id` = '".$channel['id']."' " );
			}
		}

		unset( $channel_servers );
	}

	// get stalker_db channels to compare for removing
	$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`itv` ORDER BY `id` " );
	$temp_stalker_channels 	= $query->fetchAll( PDO::FETCH_ASSOC );
	foreach( $temp_stalker_channels as $temp_stalker_channel ) {
		$stalker_channels[] = $temp_stalker_channel['id'];
	}

	// format cms streams for array_diff
	foreach( $channels as $channel ) {
		$stiliam_channels[] = $channel['id'];
	}

	if( $verbose == true ) {
		console_output( " " );
		console_output( "======================================================================" );
		console_output( " " );
	}

	// 24/7 channels > itv, ch_links
	$query 					= $conn->query( "SELECT * FROM `cms`.`channels_247` ORDER BY `id` " );
	$channels 				= $query->fetchAll( PDO::FETCH_ASSOC );
	$channels 				= stripslashes_deep( $channels );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $channels ) )." 24/7 channels to channels" );
	}

	foreach( $channels as $channel ) {
		if( $verbose == true ) {
			console_output( "Name: ".$channel['title'] );
		}

		// find server for this stream
		$query 					= $conn->query( "SELECT `wan_ip_address`,`http_stream_port` FROM `cms`.`servers` WHERE `id` = '".$channel['server_id']."' " );
		$channel_server 		= $query->fetch( PDO::FETCH_ASSOC );

		// build internal source url
		$channel['source']		= "http://".$channel_server['wan_ip_address'].":".$channel_server['http_stream_port']."/play/hls/channel_247_".$channel['id']."_.m3u8?token=".$global_settings['master_token'];

		// check for existing channel
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`itv` WHERE `id` = '247".$channel['id']."' LIMIT 1" );
		$existing_channel 		= $query->fetch( PDO::FETCH_ASSOC );
		if( !isset( $existing_channel['id'] ) ) {
			if( $verbose == true ) {
				console_output( " - New channel found" );
			}

			$insert = $conn->exec( "INSERT IGNORE INTO `stalker_db`.`itv` 
		        (`id`,`name`,`number`,`censored`,`cmd`,`tv_genre_id`,`service_id`,`modified`)
		        VALUE
		        ('247".$channel['id']."',
		        '24/7 Channel: ".addslashes( $channel['title'] )."',
		        '247".$channel['id']."',
		        '0',
		        '".$channel['source']."',
		        '999999',
		        '',
		        '2020-01-01 00:00:00'
		    )" );
		} else {
			if( $verbose == true ) {
				console_output( " - Existing channel" );
			}

			$update = $conn->exec( "UPDATE `stalker_db`.`itv` SET `name` = '24/7 Channel: ".addslashes( $channel['title'] )."' WHERE `id` = '247".$channel['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`itv` SET `cmd` = '".$channel['source']."' WHERE `id` = '247".$channel['id']."' " );
		}

		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`ch_links` WHERE `ch_id` = '247".$channel['id']."' LIMIT 1" );
		$existing_channel_link 	= $query->fetch( PDO::FETCH_ASSOC );

		if( !isset( $existing_channel_link['id'] ) ) {
		    $insert = $conn->exec( "INSERT INTO `stalker_db`.`ch_links` 
		        (`ch_id`,`url`,`status`,`changed`)
		        VALUE
		        ('247".$channel['id']."',
		        '".$channel['source']."',
		        '1',
		        '2020-01-01 00:00:00'
		    )" );
		} else {
			$update = $conn->exec( "UPDATE `stalker_db`.`ch_links` SET `url` = '".$channel['source']."' 			WHERE `ch_id` = '247".$channel['id']."' " );
		}
	}

	// format cms channels for array_diff
	foreach( $channels as $channel ) {
		$stiliam_channels[] = '247'.$channel['id'];
	}

	// compare arrays to remove ones we dont want
	$contents_diffs = array_diff( $stalker_channels, $stiliam_channels );
	foreach( $contents_diffs as $contents_diff ) {
		$delete = $conn->exec( "DELETE FROM `stalker_db`.`itv` WHERE `id` = '".$contents_diff."' " );
	}

	if( $verbose == true ) {
		console_output( " " );
		console_output( "======================================================================" );
		console_output( " " );
	}

	// bouquets > services_package
	$query 					= $conn->query( "SELECT * FROM `cms`.`bouquets` ORDER BY `id` " );
	$bouquets 				= $query->fetchAll( PDO::FETCH_ASSOC );
	$bouquets 				= stripslashes_deep( $bouquets );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $bouquets ) )." bouquets to services_package" );
	}

	foreach( $bouquets as $bouquet ) {
		if( $verbose == true ) {
			console_output( "Name: ".$bouquet['name'] );
		}

		// convert ss to ministra bouqet / package type
		if( $bouquet['type'] == 'live' ) {
			$bouquet_type = 'tv';
		}elseif( $bouquet['type'] == 'vod' ) {
			$bouquet_type = 'video';
		}elseif( $bouquet['type'] == 'channel_247' ) {
			$bouquet_type = 'tv';
		} else {
			$bouquet_type = 'tv';
		}

		$delete = $conn->exec( "DELETE FROM `stalker_db`.`services_package`		 	WHERE `id` = '".$bouquet['id']."' " );
		$delete = $conn->exec( "DELETE FROM `stalker_db`.`service_in_package` 		WHERE `package_id` = '".$bouquet['id']."' " );
		// $delete = $conn->exec( "DELETE FROM `stalker_db`.`package_in_plan` 		WHERE `package_id` = '".$bouquet['id']."' " );

		$insert = $conn->exec( "INSERT INTO `stalker_db`.`services_package` 
	        (`id`,`name`,`type`,`all_services`,`service_type`,`rent_duration`,`price`)
	        VALUE
	        ('".$bouquet['id']."',
	        '".addslashes( $bouquet['name'] )."',
	        '".$bouquet_type."',
	        '1',
	        'periodic',
	        '0',
	        '0.00'
	    )" );

		// console_output( " - Channels to Package:" . $bouquet['streams'] );

		// get bouquets_contents and to service_in_package
		$query 					= $conn->query( "SELECT * FROM `cms`.`bouquets_content` WHERE `bouquet_id` = '".$bouquet['id']."' " );
		$bouquets_contents 		= $query->fetchAll( PDO::FETCH_ASSOC );

	    if( is_array( $bouquets_contents ) && !empty( $bouquets_contents ) ) {
	    	foreach( $bouquets_contents as $bouquets_content ) {
	    		$insert = $conn->exec( "INSERT INTO `stalker_db`.`service_in_package` 
			         (`service_id`,`package_id`,`type`,`modified`,`options`)
			        VALUE
			        ('".$bouquets_content['content_id']."',
			        '".$bouquet['id']."',
			        '".$bouquet_type."',
			        '2020-01-01 00:00:00',
			        '{}'
			    )" );
	    	}
	    }		    
	}

	if( $verbose == true ) {
		console_output( " " );
		console_output( "======================================================================" );
		console_output( " " );
	}

	// customers > users
	$query 					= $conn->query( "SELECT * FROM `cms`.`customers` ORDER BY `id` " );
	$customers 				= $query->fetchAll( PDO::FETCH_ASSOC );
	$customers 				= stripslashes_deep( $customers );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $customers ) )." customers to users" );
	}

	foreach( $customers as $customer ) {
		// generate stalker compatible password
		$customer['stalker_password'] 		= md5( md5 ( $customer['password'] ).$customer['id'] );

		// set customer status for stalker
		if( $customer['status'] == 'active' ) {
			$customer['status'] = 0;
		} else {
			$customer['status'] = 1;
		}

		// see if customer has a mag assigned to it
		$query 					= $conn->query( "SELECT `mac` FROM `cms`.`mag_devices` WHERE `customer_id` = '".$customer['id']."' LIMIT 1" );
		$customer_mag 			= $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $customer_mag['mac'] ) ) {
			$customer['mac'] = base64_decode( $customer_mag['mac'] );
		} else {
			$customer['mac'] = '';
		}

		// check for existing stalker user
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`users` WHERE `id` = '".$customer['id']."' LIMIT 1" );
		$existing_customer 		= $query->fetch( PDO::FETCH_ASSOC );

		if( !isset( $existing_customer['id'] ) ) {
			if( $verbose == true ) {
				console_output( " - New customer: ".$customer['username'] ." | Password: " . $customer['stalker_password'] );
			}

			$insert = $conn->exec( "INSERT INTO `stalker_db`.`users` 
		        (`id`, `name`, `sname`, `pass`, `parent_password`, `bright`, `contrast`, `saturation`, `aspect`, `video_out`, `volume`, `playback_buffer_bytes`, `playback_buffer_size`, `audio_out`, `mac`, `ip`, `ls`, `version`, `lang`, `locale`, `city_id`, `status`, `hd`, `main_notify`, `fav_itv_on`, `now_playing_start`, `now_playing_type`, `now_playing_content`, `additional_services_on`, `time_last_play_tv`, `time_last_play_video`, `operator_id`, `storage_name`, `hd_content`, `image_version`, `last_change_status`, `last_start`, `last_active`, `keep_alive`, `playback_limit`, `screensaver_delay`, `phone`, `tv_quality`, `fname`, `login`, `password`, `stb_type`, `serial_number`, `num_banks`, `tariff_plan_id`, `comment`, `now_playing_link_id`, `now_playing_streamer_id`, `just_started`, `last_watchdog`, `created`, `country`, `access_token`, `plasma_saving`, `device_id`, `ts_enabled`, `ts_enable_icon`, `ts_path`, `ts_max_length`, `ts_buffer_use`, `ts_action_on_exit`, `ts_delay`, `video_clock`, `device_id2`, `verified`, `hdmi_event_reaction`, `pri_audio_lang`, `sec_audio_lang`, `pri_subtitle_lang`, `sec_subtitle_lang`, `subtitle_color`, `subtitle_size`, `show_after_loading`, `play_in_preview_by_ok`, `hw_version`, `openweathermap_city_id`, `theme`, `settings_password`, `expire_billing_date`, `reseller_id`, `account_balance`, `client_type`, `hw_version_2`, `blocked`, `units`, `tariff_expired_date`, `tariff_id_instead_expired`, `activation_code_auto_issue`)
					VALUES
					('".$customer['id']."',
						'',
						'',
						'',
						'0000',
						'200',
						'127',
						'127',
						'16',
						'',
						'100',
						'0',
						'0',
						'1',
						'".$customer['mac']."',
						'',
						'',
						'',
						'',
						'',
						'0',
						'".$customer['status']."',
						'0',
						'1',
						'0',
						'0000-00-00 00:00:00',
						'0',
						'',
						'1',
						'0000-00-00 00:00:00',
						'0000-00-00 00:00:00',
						'0',
						'',
						'0',
						'',
						'0000-00-00 00:00:00',
						'0000-00-00 00:00:00',
						'0000-00-00 00:00:00',
						'0000-00-00 00:00:00',
						'5',
						'10',
						'',
						'high',
						'".addslashes( $customer['username'] )."',
						'".addslashes( $customer['username'] )."',
						'".addslashes( $customer['stalker_password'] )."',
						'',
						'',
						'0',
						'1',
						'',
						'0',
						'0',
						'0',
						'0000-00-00 00:00:00',
						'2019-10-27 00:05:55',
						'GB',
						'',
						'0',
						'',
						'0',
						'1',
						'',
						'3600',
						'cyclic',
						'no_save',
						'on_pause',
						'Off',
						'',
						'0',
						NULL,
						'',
						'',
						'',
						'',
						'16777215',
						'20',
						'',
						NULL,
						'',
						'0',
						'',
						'0000',
						'0000-00-00 00:00:00',
						'0',
						'',
						'',
						'',
						'0',
						'metric',
						'0000-00-00 00:00:00',
						'1',
						'0'
		    )" );
		} else {
			if( $verbose == true ) {
				console_output( " - Existing customer: ".$customer['username'] ." | Password: ".$customer['stalker_password']);
			}

			$update = $conn->exec( "UPDATE `stalker_db`.`users` SET `fname` = '".addslashes( $customer['username'] )."' 	WHERE `id` = '".$customer['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`users` SET `login` = '".addslashes( $customer['username'] )."' 	WHERE `id` = '".$customer['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`users` SET `password` = '".addslashes( $customer['stalker_password'] )."' 		WHERE `id` = '".$customer['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`users` SET `mac` = '".$customer['mac']."' 							WHERE `id` = '".$customer['id']."' " );
			$update = $conn->exec( "UPDATE `stalker_db`.`users` SET `status` = '".$customer['status']."' 					WHERE `id` = '".$customer['id']."' " );
		}

		// build the stalker / ministra itv_subscription

		// get package of this customer
		$query                      = $conn->query( "SELECT * FROM `packages` WHERE `id` = '".$customer['package_id']."' " );
    	$customer_package          	= $query->fetch( PDO::FETCH_ASSOC );
    	$customer_bouquets 			= explode(",", $customer_package['bouquets'] );

		$customer_channels 			= array();
		$customer_channels_count 	= 0;

		foreach( $customer_bouquets as $customer_bouquet ) {
			// check bouquet type
			$query 					= $conn->query( "SELECT * FROM `cms`.`bouquets` WHERE `id` = '".$customer_bouquet."' " );
			$bouquet_type 			= $query->fetch( PDO::FETCH_ASSOC );
			if( $verbose == true ) {
				console_output( "Bouquet: ".$customer_bouquet." | ".$bouquet_type['type'] );
			}

			// get streams for this bouqeut
			$query 					= $conn->query( "SELECT * FROM `cms`.`bouquets_content` WHERE `bouquet_id` = '".$customer_bouquet."' " );
			$bouquets_contents 		= $query->fetchAll( PDO::FETCH_ASSOC );
			// console_output( "Bouquet Contents: ".print_r($bouquets_contents));

			foreach( $bouquets_contents as $bouquets_content ) {
				if( $verbose == true ) {
					console_output( "Adding Content ID: ".$bouquets_content['content_id'] );
				}

				if( $bouquet_type['type'] == 'live' ) {
					$customer_channels[] = $bouquets_content['content_id'];
				}elseif( $bouquet_type['type'] == 'channel_247' ) {
					$customer_channels[] = '247'.$bouquets_content['content_id'];
				}
				// $customer_channels[$customer_channels_count] = $bouquets_content['content_id'];
			}

			$customer_channels_count++;
		}

		if( $verbose == true ) {
			console_output( "Customer Stream Subscription" );
		}

		// debug( $customer_channels );

		// filter dupes
		// $customer_channels = array_filter($customer_channels);

		// serialize for ministra
		$customer_channels = serialize( $customer_channels );

		// base64 encode
		$customer_channels = base64_encode( $customer_channels );

		// check for existing supscription
		$query 					= $conn->query( "SELECT `id` FROM `stalker_db`.`itv_subscription` WHERE `uid` = '".$customer['id']."' LIMIT 1" );
		$existing_subscription 	= $query->fetch( PDO::FETCH_ASSOC );

		if( !isset( $existing_subscription['id'] ) ) {
			if( $verbose == true ) {
				console_output( " - New customer subscription" );
			}

			$insert = $conn->exec( "INSERT INTO `stalker_db`.`itv_subscription` 
		         (`uid`,`sub_ch`,`bonus_ch`,`addtime`)
		        VALUE
		        ('".$customer['id']."',
		        '".$customer_channels."',
		        '',
		        '2020-01-01 00:00:00'
		    )" );
		} else {
			if( $verbose == true ) {
				console_output( " - Existing customer subscription" );
			}

			$update = $conn->exec( "UPDATE `stalker_db`.`itv_subscription` SET `sub_ch` = '".$customer_channels."' WHERE `uid` = '".$customer['id']."' " );
		}

		unset( $customer_channels );
	}

	if( $verbose == true ) {
		console_output( " " );
		console_output( "======================================================================" );
		console_output( " " );
	}

	// epg
	$query 					= $conn->query( "SELECT * FROM `cms`.`epg` ORDER BY `id` " );
	$epg_sources 			= $query->fetchAll( PDO::FETCH_ASSOC );
	$epg_sources 			= stripslashes_deep( $epg_sources );

	if( $verbose == true ) {
		console_output( "Syncing: ".number_format( count( $epg_sources ) )." epg sources" );
	}

	foreach( $epg_sources as $epg_source ) {
		if( $verbose == true ) {
			console_output( "Name: ".$epg_source['name'] );
		}

		$insert = $conn->exec( "INSERT IGNORE INTO `stalker_db`.`epg_setting` 
	        (`id`,`uri`,`etag`,`status`,`lang_code`)
	        VALUE
	        ('".$epg_source['id']."',
	        '".$epg_source['source']."',
	        '".$epg_source['etag']."',
	        '1',
	        'en'
	    )" ); 
	}

	if( $verbose == true ) {
		console_output( "Finished." );
	}
}

if( $task == 'totals' )
{
	console_output( "Count totals for various tables." );
	
	// episodes per 24/7 channel
	$query 		= $conn->query( "SELECT `id`,`name` FROM `channels`; " );
	$channels 	= $query->fetchAll( PDO::FETCH_ASSOC );

	foreach($channels as $channel) {
		// count episodes in this seriess
		$query = $conn->query( "SELECT `id` FROM `channels_files` WHERE `channel_id` = '".$channel['id']."' " );
		$channel_files = $query->fetchAll( PDO::FETCH_ASSOC );
		$total_episodes = count($channel_files);

		$update = $conn->exec( "UPDATE `channels` SET `total_episodes` = '".$total_episodes."' WHERE `id` = '".$channel['id']."' " );

		console_output( "24/7 TV Channel: " . $channel['name'] . " | Episodes: " . $total_episodes);
	}

	// episodes per tv series
	$query 		= $conn->query( "SELECT `id`,`name` FROM `tv_series`; " );
	$tv_shows 	= $query->fetchAll( PDO::FETCH_ASSOC );

	foreach($tv_shows as $tv_show) {
		// count episodes in this series
		$query = $conn->query( "SELECT `id` FROM `tv_series_files` WHERE `tv_series_id` = '".$tv_show['id']."' " );
		$channel_files = $query->fetchAll( PDO::FETCH_ASSOC );
		$total_episodes = count($channel_files);

		$update = $conn->exec( "UPDATE `tv_series` SET `total_episodes` = '".$total_episodes."' WHERE `id` = '".$tv_show['id']."' " );

		console_output( "TV Series: " . $tv_show['name'] . " | Episodes: " . $total_episodes);
	}

	console_output( "Finished." );
}
