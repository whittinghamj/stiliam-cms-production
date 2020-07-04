<?php

// default array
$data 					= array();

// vars
$app['basepath']		= '/var/www/html/';
$config 				= @file_get_contents( '/var/www/html/config.json' );
$config 				= json_decode( $config, true );

$data 							= array();

// set vars
$client_ip 						= $_SERVER['REMOTE_ADDR'];

$data['username'] 				= stripslashes( $_GET['username'] );
if( !isset( $_GET['username'] ) || empty( $data['username'] ) ) {
	die('username is missing');
}
// $data['username']				= str_replace("live/", "", $data['username'] );

$data['password'] 				= stripslashes( $_GET['password'] );
if( !isset( $_GET['password'] ) || empty( $data['password'] ) ) {
	die('password is missing');
}

$data['stream_id'] 				= stripslashes( $_GET['stream_id'] );
if( !isset( $_GET['stream_id'] ) || empty( $data['stream_id'] ) ) {
	die('stream_id missing');
}
$type_id 						= stripslashes( $_GET['stream_id'] );

$data['segment_id']				= '';

$data['extension'] 				= stripslashes( $_GET['extension'] );
if( !isset( $_GET['extension'] ) || empty( $data['extension'] ) ) {
	// die('extension missing');
}

if( $data['extension'] == 'm3u' || $data['extension'] == 'm3u8' || empty( $data['extension'] ) ) {
	// error_log('stream_id: '.$data['stream_id'] );
}elseif( $data['extension'] == 'ts') {
	// error_log('segment_id: '.$data['segment_id'] );
}

if( strpos( $data['stream_id'], '_') !== false ) {
    if( $data['extension'] == 'ts') {
		// break the ts file for channel_id
		$stream_bits 					= explode( "_", $data['stream_id'] );
		$data['stream_id']				= $stream_bits[0];
		$data['segment_id']				= $stream_bits[1];
	}
}

// error_log( "\n\n API URL: http://".$config['cms']['server']."/api/?c=channel_info_client&username=".$data['username']."&password=".$data['password']."&client_ip=".$client_ip."&stream_id=".$data['stream_id']."\n\n" );

// get stream data
$stream_data 						= @file_get_contents( "http://".$config['cms']['server']."/api/?c=channel_info_client&username=".$data['username']."&password=".$data['password']."&client_ip=".$client_ip."&stream_id=".$data['stream_id'] );
$stream_data 						= json_decode( $stream_data, true );

// cms error check
if( $stream_data['status'] == 'error') {
	echo "cms error: ".$stream_data['message'];
	die();
}

// error_log( "\n\n".$stream_data['data']['method']."\n\n" );

// direct to source check
if( $stream_data['data']['method'] == 'direct') {
	header( "Location: ".$stream_data['data']['source']['source'], true, 301 );
	die();
}

$public_ip 						= @file_get_contents( "http://".$config['cms']['server']."/api/ip.php" );
$public_ip 						= json_decode( $public_ip, true ) ;
$public_ip 						= $public_ip['ip'];

if( $public_ip != $stream_data['data']['server']['wan_ip_address'] ) {
	// error_log(" ");
	// error_log("Remote Stream: http://".$stream_data['data']['server']['wan_ip_address'].":".$stream_data['data']['server']['http_stream_port']."/live/".$data['username']."/".$data['password']."/".$data['stream_id'].".m3u8");
	// error_log(" ");

	// echo "http://".$stream_data['data']['server']['wan_ip_address'].":".$stream_data['data']['server']['http_stream_port']."/live/".$data['username']."/".$data['password']."/".$data['stream_id'].".m3u8";

	
	header( "Location: http://".$stream_data['data']['server']['wan_ip_address'].":".$stream_data['data']['server']['http_stream_port']."/live/".$data['username']."/".$data['password']."/".$data['stream_id'].".".$data['extension'], true, 301 );
}else{
	
	// error_log( "\n\n" );
	// error_log( "Log Connection: http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$data['stream_id']."&username=".$data['username'] );
	// error_log( "\n\n" );
	// post connection log
	@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$data['stream_id']."&username=".$data['username'] );

	// if is a .ts stream or a hls stream being requested
	if( $data['extension'] == 'ts' && empty( $data['segment_id'] ) ) {
		
		// post connection log
		@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$data['stream_id']."&username=".$data['username'] );

		header("Content-Type: video/mp2t");
		ob_end_flush();
		
		$folder = '/var/www/html/play/hls/';
	    $files = array();
	    // $file = $folder . '/' . $stream_id . '_.m3u8';
	    $username = $data['username'];
	    $stream_id = $data['stream_id'];
	    $file = '/var/www/html/play/hls/'.$data['stream_id'].'_.m3u8';

	    if( file_exists( $file ) && preg_match_all( "/(.*?).ts/", @file_get_contents( $file), $data ) ) {
	        $files = $data[0];
	    }

	    foreach( $files as $file ) {
	        if( !file_exists( $folder . $file) ) {
	            exit();
	        }
	        readfile( $folder . $file );
	    }

	    preg_match( "/_(.*)\\./", array_pop( $files ), $clean );
	    $segment = $clean[1];
	    $try = 0;

	    while(( $try <= 16) && file_exists( $folder . '/' . $stream_id . '_.m3u8') ) {
	        $next = sprintf( $stream_id . "_%d.ts", $segment + 1);
	        $nextnext = sprintf( $stream_id . "_%d.ts", $segment + 2);
	        if (!file_exists( $folder . $next) ) {
	            sleep(1);
	            $try++;
	            continue;
	        }

	        $try = 0;
	        $fopen = fopen( $folder . $next, "r");
	        while (( $try <= 16) && !file_exists( $folder . $nextnext) ) {
	            $line = stream_get_line( $fopen, 4096);
	            if (empty( $line) ) {
	                sleep(1);
	                ++$try;
	                continue;
	            }
	            echo $line;
	            $t = 0;
	        }
	        echo stream_get_line( $fopen, filesize( $folder . $next) - ftell( $fopen) );
	        fclose( $fopen);
	        $try = 0;
	        $segment++;

	        // post connection log
			@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$type_id."&username=".$username );
		}
	}elseif( $data['extension'] == 'm3u' || $data['extension'] == 'm3u8' || empty( $data['extension'] ) || empty( $data['segment_id'] ) ) {
		
		// post connection log
		@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$data['stream_id']."&username=".$data['username'] );

		$file = '/var/www/html/play/hls/'.$data['stream_id'].'_.m3u8';
		// ondemand check
		if(!file_exists( $file) && $stream_data['data']['ondemand'] == 'yes') {
			// start input stream
			exec("sudo mkdir -p /var/www/html/logs/");
			exec("sudo chmod 777 /opt/slipstream/logs/*");
			exec("sudo chmod 777 /var/www/html/logs/*");

			// start on-demand
			$active_stream_ids = array( $data['stream_id'] );

			// configure user_agent
			if(empty( $stream_data['data']['input']['user_agent'] ) ) {
				$user_agent = 'SlipStreamIPTV';
			}else{
				$user_agent = trim( $stream_data['data']['input']['user_agent'] );
			}

			// build ffmpeg command
			$cmd =  "ffmpeg ";
			$cmd .=  "-y ";
			if( $stream_data['data']['input']['ffmpeg_re'] == 'yes') {
				$cmd .= "-re ";
			}
			$cmd .= "-hide_banner -loglevel info -err_detect ignore_err ";
			$cmd .= "-user_agent '".$user_agent."' ";
			$cmd .= "-probesize 10000000 ";
			$cmd .= "-analyzeduration 10000000 ";
			$cmd .= "-progress /var/www/html/logs/".$stream_data['data']['input']['id'].".log ";

			// check for youtube streams
			if(parse_url( $stream_data['data']['input']['source'], PHP_URL_HOST) == 'youtube.com' || parse_url( $stream_data['data']['input']['source'], PHP_URL_HOST) == 'https://www.youtube.com' || parse_url( $stream_data['data']['input']['source'], PHP_URL_HOST) == 'www.youtube.com' || parse_url( $stream_data['data']['input']['source'], PHP_URL_HOST) == 'mobil.youtube.com' || parse_url( $stream_data['data']['input']['source'], PHP_URL_HOST) == 'youtu.be') {
				
				$stream_data['data']['input']['source'] = shell_exec("youtube-dl --geo-bypass -f best --get-url ".$data['source'] );
				$stream_data['data']['input']['source'] = trim( $stream_data['data']['input']['source'] );

				$cmd .= "-start_at_zero ";
				$cmd .= "-copyts ";
				$cmd .= "-vsync 0 ";
				$cmd .= "-correct_ts_overflow 0 ";
				$cmd .= "-avoid_negative_ts disabled ";
				$cmd .= "-max_interleave_delta 0 ";
				$cmd .= "-fflags nobuffer ";
				$cmd .= "-i '".$stream_data['data']['input']['source']."' ";

			}else{
				$cmd .= "-vsync 0 ";
				$cmd .= "-copytb 1 ";
				$cmd .= "-fflags nobuffer ";
				// $cmd .= "-overrun_nonfatal 1 ";
				$cmd .= "-recv_buffer_size 67108864 ";
				$cmd .= "-i '".$stream_data['data']['input']['source']."' ";
				// $cmd .= "-g 100 ";
				// $cmd .= "-sc_threshold 0 ";
				// $cmd .= "-sn ";
				// $cmd .= "-dn ";
				// $cmd .= "-flags -global_header ";
			}

			// find outputs for this input stream
			foreach( $stream_data['data']['input']['output'] as $output_stream) {
				if( $output_stream['enable'] == 'yes') {
					// add active_stream_ids for tracking / stats
					$active_stream_ids[] = $output_stream['id'];

					if( $output_stream['transcoding_profile_id'] > 0) {
						// profile needed, lets download it
						$transcoding_profile 					= @file_get_contents("http://".$config['hub']['server']."/api/?c=transcoding_profile&id=".$output_stream['transcoding_profile_id'] );
						$transcoding_profile 					= json_decode( $transcoding_profile, true);

						$output_stream['user_agent']					= $transcoding_profile['data']['user_agent'];
						$output_stream['ffmpeg_re']						= $transcoding_profile['data']['ffmpeg_re'];
						$output_stream['cpu_gpu']						= $transcoding_profile['data']['cpu_gpu'];
						$output_stream['video_codec']					= $transcoding_profile['data']['video_codec'];
						$output_stream['gpu']							= $transcoding_profile['data']['gpu'];
						$output_stream['surfaces']						= $transcoding_profile['data']['surfaces'];
						$output_stream['framerate']						= $transcoding_profile['data']['framerate'];
						$output_stream['preset']						= $transcoding_profile['data']['preset'];
						$output_stream['profile']						= $transcoding_profile['data']['profile'];
						$output_stream['screen_resolution']				= $transcoding_profile['data']['screen_resolution'];
						$output_stream['bitrate']						= $transcoding_profile['data']['bitrate'];
						$output_stream['audio_codec']					= $transcoding_profile['data']['audio_codec'];
						$output_stream['audio_bitrate']					= $transcoding_profile['data']['audio_bitrate'];
						$output_stream['audio_sample_rate']				= $transcoding_profile['data']['audio_sample_rate'];
						$output_stream['ac']							= $transcoding_profile['data']['ac'];
						$output_stream['fingerprint']					= $transcoding_profile['data']['fingerprint'];
						$output_stream['fingerprint_type']				= $transcoding_profile['data']['fingerprint_type'];
						$output_stream['fingerprint_text']				= $transcoding_profile['data']['fingerprint_text'];
						$output_stream['fingerprint_fontsize']			= $transcoding_profile['data']['fingerprint_fontsize'];
						$output_stream['fingerprint_color']				= $transcoding_profile['data']['fingerprint_color'];
						$output_stream['fingerprint_location']			= $transcoding_profile['data']['fingerprint_location'];
					}

					// configure output details
					$output = '';

					// set max_bitrate
					$output_stream['max_bitrate'] = $output_stream['bitrate'];

					// break out screen resolution
					$screen_resolution = explode('x', $output_stream['screen_resolution'] );

					if(empty( $output_stream['ac'] ) ) {
						$output_stream['ac'] = 2;
					}

					if(empty( $output_stream['preset'] ) ) {
						$output_stream['preset'] = '2';
					}else{
						$output_stream['preset'] = $output_stream['preset'];
					}

					if(empty( $output_stream['profile'] ) ) {
						$output_stream['profile'] = 'baseline';
					}else{
						$output_stream['profile'] = $output_stream['profile'];
					}

					if(empty( $output_stream['aspect'] ) ) {
						$output_stream['aspect'] = '16:9';
					}

					// -preset fast -profile:v baseline -tune zerolatency

					// get rid of NAR language stream
					$output .= "-map a -map -m:language:NAR -map 0:v? ";

					// build ffmpeg command
					if( $output_stream['cpu_gpu'] == 'copy') {
						$output .= "-c:v 'copy' ";
						$output .= "-c:a 'copy' ";
					}elseif( $output_stream['cpu_gpu'] == 'cpu') {
						// video options
						// $output .= "{FINGERPRINT}";
						$output .= "-c:v '".$output_stream['video_codec']."' ";
						$output .= "-b:v '".$output_stream['bitrate']."k' ";
						$output .= "-minrate '".$output_stream['bitrate']."k' ";
						$output .= "-maxrate:v '".$output_stream['bitrate']."k' ";
						$output .= "-bufsize '".$output_stream['bitrate']."k' ";
						if( $output_stream['screen_resolution'] != 'copy') {
							$output .= "-s '".$output_stream['screen_resolution']."' ";
						}
						$output .= "-preset:v '".$output_stream['preset']."' ";
						$output .= "-profile:v '".$output_stream['profile']."' ";
						$output .= "-tune zerolatency ";
						if(!empty( $output_stream['framerate'] ) && $output_stream['framerate'] != 0) {
							$output .= "-r '".$output_stream['framerate']."' ";
						}
						$output .= "-g 100 ";
					}elseif( $output_stream['cpu_gpu'] == 'gpu') {
						// video options
						// $output .= "{FINGERPRINT}";
						if( $output_stream['dehash'] == 'enable') {
							$dehash_padding 							= explode('-', $output_stream['dehash_padding'] );
							$output_stream['dehash_padding']			= '';
							$output_stream['dehash_padding']			= $dehash_padding;

							$dehash_scale 								= explode('-', $output_stream['dehash_scale'] );
							$output_stream['dehash_scale']				= '';
							$output_stream['dehash_scale']				= $dehash_scale;

							$output .='-filter_complex "';
							$output .= "[0:v]";
							$output .= "hwdownload,";
							$output .= "format=pix_fmts=nv12";
							$output .= "[format:0]; ";
		
							$output .= "[format:0]";
							$output .= "cvdelogo=filename=/root/slipstream/node/dehash_images/bt_sport_1_hd_1080.png:";
							$output .= "buffer_queue_size=".$output_stream['dehash_buffer_queue_size'].":";
							$output .= "detect_interval=".$output_stream['dehash_dedect_interval'].":";
							$output .= "score_min=".$output_stream['dehash_score_min'].":";
							$output .= "scale_min=".$output_stream['dehash_scale'][0].":";
							$output .= "scale_max=".$output_stream['dehash_scale'][1].":";
							$output .= "padding_left=".$output_stream['dehash_padding'][0].":";
							$output .= "padding_right=".$output_stream['dehash_padding'][1].":";
							$output .= "padding_top=".$output_stream['dehash_padding'][2].":";
							$output .= "padding_bottom=".$output_stream['dehash_padding'][3]."";
							$output .= "[cvdelogo]; ";
		
							$output .= "[cvdelogo]";
							$output .= "split=outputs=1";
							$output .= "[hwupload:0]; ";
		
							$output .= "[hwupload:0]";
							$output .= "hwupload=extra_hw_frames=1";
							$output .= '[map:0:v]" ';
							$output .= "-map [map:0:v] ";
						}

						$output .= "-c:v '".$output_stream['video_codec']."' ";
						// $output .= "-filter_complex scale_npp=-1:".$screen_resolution[1]." ";
						$output .= "-g 100 ";
						$output .= "-b:v '".$output_stream['bitrate']."k' ";
						$output .= "-minrate '".$output_stream['bitrate']."k' ";
						$output .= "-maxrate '".$output_stream['max_bitrate']."k' ";
						$output .= "-bufsize '".$output_stream['bitrate']."k' ";
						$output .= "-strict -2 ";
						if(!empty( $output_stream['framerate'] ) && $output_stream['framerate'] != 0) {
							$output .= "-r '".$output_stream['framerate']."' ";
						}
						$output .= "-profile:v '".$output_stream['profile']."' ";
						$output .= "-tune zerolatency ";
						// $output .= "-level 4.1 ";
						$output .= "-max_muxing_queue_size 512 ";
					}

					// audio options
					$output .= "-acodec '".$output_stream['audio_codec']."' ";
					if( $output_stream['audio_codec'] != 'copy') {
						$output .= "-ac '".$output_stream['ac']."' ";
						$output .= "-ar '".$output_stream['audio_sample_rate']."' ";
						$output .= "-b:a '".$output_stream['audio_bitrate']."k' ";
					}

					// metadata title
					$metadata['title'] = stripslashes( $output_stream['name'] );
					$metadata['title'] = str_replace(array('"', "'", ':'), '', $metadata['title'] );
					// $output .= "-metadata title='".$metadata['title']."' ";

					// hls options
					$output .= "-sc_threshold 0 ";
					$output .= "-sn ";
					$output .= "-dn ";
					$output .= "-flags -global_header ";
					$output .= "-hls_time 4 ";
					$output .= "-hls_list_size 4 ";
					$output .= "-hls_flags delete_segments ";
					$output .= "-f hls /var/www/html/play/hls/".$output_stream['id']."_.m3u8 ";

					// add the output
					$cmd .= $output;
				}

				// add logging
				// $cmd .= " -hide_banner -loglevel info -err_detect ignore_err ";
			}

			// clean up old files
			foreach( $active_stream_ids as $active_stream_id) {
				exec('sudo rm -rf /var/www/html/play/hls/'.$active_stream_id."_*");
			}

			// probe the source
			$stream['test_results'] 	= shell_exec("timeout 30 ffprobe -v quiet -print_format json -show_format -show_streams '".$stream_data['data']['input']['source']."' ");
			$stream['test_results'] 	= json_decode( $stream['test_results'], true);

			// source is active
			if(isset( $stream['test_results']['streams'] ) ) {
				foreach( $stream['test_results']['streams'] as $stream) {
					if(isset( $stream['codec_type'] ) && $stream['codec_type']=='video') {
						if( $stream['codec_name'] == 'h264') {
							$decoder = 'h264_cuvid';
						}
						if( $stream['codec_name'] == 'h265' || $stream['codec_name'] == 'hevc' || $stream['codec_name'] == 'hevc_cuvid') {
							$decoder = 'hevc_cuvid';
						}
						if( $stream['codec_name'] == 'mpeg2video') {
							$decoder = 'mpeg2_cuvid';
						}
					}
				}
				
				// get the pid of the newly started FFMPEG
				$pid = shell_exec('sudo ' . $cmd . ' > /opt/slipstream/logs/'.$stream_data['data']['input']['id'].'.log 2>&1 & echo $!');

				// loop over input / output ids to get stats
				foreach( $active_stream_ids as $active_stream_id) {
					// post stream data
					$stream_update = @file_get_contents("http://".$config['hub']['server']."/api/?c=stream_status_update&id=".$active_stream_id."&status=online&pid=".$pid);
				}

				while(!file_exists( $file) ) {						
					sleep(1);
				}

				$fp = fopen( $file, 'rb');

				header("Content-Type: application/octet-stream");
				header("Content-Disposition: attachment; filename=".$data['stream_id'].'.m3u8');
				header("Content-Length: " . filesize( $file) );
				fpassthru( $fp);
			}else{
				// post stream data
				foreach( $active_stream_ids as $active_stream_id) {
					$stream_update = @file_get_contents("http://".$config['hub']['server']."/api/?c=stream_status_update&id=".$active_stream_id."&status=source_offline");
				}
			}

		}else{
			$fp = fopen( $file, 'rb');

			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$data['stream_id'].'.m3u8');
			header("Content-Length: " . filesize( $file) );
			fpassthru( $fp);
		}
	}elseif( $data['extension'] == 'ts') {
		
		// post connection log
		@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$data['stream_id']."&username=".$data['username'] );

		$file = '/var/www/html/play/hls/'.$data['stream_id'].'_'.$data['segment_id'].'.ts';
		if(file_exists( $file) ) {
			$fp = fopen( $file, 'rb');

			if(isset( $_GET['dev'] ) && $_GET['dev'] == 'yes') {
				echo '<pre>';
				print_r( $_GET);
				print_r( $data);
				print_r( $stream_data);
			}else{
				// error_log( "\n\n44 ".$log."\n\n" ); $log++;

				// post connection log
				@file_get_contents( "http://".$config['cms']['server']."/api/?c=customer_connection_log&server_id=".$stream_data['data']['server']['id']."&client_ip=".$client_ip."&type=channel&type_id=".$data['stream_id']."&username=".$data['username'] );

				header("Content-Type: application/octet-stream");
				header("Content-Disposition: attachment; filename=".$data['stream_id'].'_'.$data['segment_id'].'.ts');
				header("Content-Length: " . filesize( $file) );
				fpassthru( $fp);
			}
		}else{
			die( $file." does not exist");
		}
	}
}

// post data to api for active connections log
// @file_get_contents("http://hub.slipstreamiptv.com/api/?c=stream_connection_log&server_id=".$server_id."&client_ip=".$client_ip."&stream_id=".$data['stream_id'] );

exit;