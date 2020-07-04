<?php

error_reporting(E_ALL);
ini_set( 'display_errors', 1) ;
ini_set( 'error_reporting', E_ALL );
ini_set( "memory_limit", -1 );

$path_to_base = realpath( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;
$path_to_lib = $path_to_base . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR;
$path_to_class = $path_to_lib . "classes" . DIRECTORY_SEPARATOR;

$path_to_inc = $path_to_base . "inc" . DIRECTORY_SEPARATOR;
if( file_exists( $path_to_inc . "db.php" ) ) {
    require_once( $path_to_inc . "db.php" );
} else {
    die( "Missing required DB element" );
}

if( file_exists( $path_to_inc . "functions.php" ) ) {
    require_once( $path_to_inc . "functions.php" );
} else {
    die( "Missing my required functions" );
}

if( file_exists( $path_to_inc . "global_vars.php" ) ) {
    require_once( $path_to_inc . "global_vars.php" );
} else {
    die( "Missing my required globals" );
}

$username       = request( 'username' );
if( empty( $username ) ) {
    die( "username is missing" );
}
$password       = request( 'password' );
if( empty( $password ) ) {
    die( "password is missing" );
}
$stream_id      = request( 'streamid' );
$action         = request( 'action' );
$category_id    = request( 'streamid' );

// $remote_ip      = $_SERVER['REMOTE_ADDR'];
// $user_agent     = $_SERVER['HTTP_USER_AGENT'];
// $query_string   = $_SERVER['QUERY_STRING'];

$sql            = "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' ";
$query          = $conn->query( $sql );
$customer       = $query->fetch( PDO::FETCH_ASSOC );

$customer['current_connections'] = 0;

$parse_url = parse_url( $_SERVER['HTTP_HOST'] );
header( 'Content-Type: application/json' );

$output = array();

if( is_array( $customer ) && !empty( $customer ) ) {

    // convert some things for XC compatability
    if( $customer['status'] == 'active' ) {
        $customer['status']             = 'Active';
    }elseif( $customer['status'] == 'expired' ) {
        $customer['status']             = 'Expired';
    }else{
        $customer['status']             = 'Expired';
    }

    // build the bouquet list from the package_id
    $sql            = "SELECT `bouquets` FROM `packages` WHERE `id` = '".$customer['package_id']."' ";
    $query          = $conn->query( $sql );
    $package        = $query->fetch( PDO::FETCH_ASSOC );
    $customer['bouquet'] = $package['bouquets'];

    // $customer['expire_date']            = strtotime( $customer["expire_date"] );
    $customer['current_connections']    = 0;

    //Alright, Lets get the line status.

    //Okay, now, lets parse the URL.

    //Alright, lets get down to business and
    // $action = get( "action" );
    if( $action ) {
        $output = array();
        switch( $action ) {
            case "get_live_categories": // done and working
                $query              = $conn->query( "SELECT * FROM `channels_categories` ORDER BY `name` " );
                $set_category       = $query->fetchAll( PDO::FETCH_ASSOC );
                $live_categories    = [];

                foreach( $set_category as $get_category ) {
                    $output[] = ['category_id' => $get_category['id'], 'category_name' => $get_category['name'], 'parent_id' => 0];
                }
            break;

            case "get_vod_categories": // done and working
                $query              = $conn->query( "SELECT * FROM `vod_categories` ORDER BY `name` " );
                $set_category       = $query->fetchAll( PDO::FETCH_ASSOC );
                $live_categories    = [];

                foreach( $set_category as $get_category ) {
                    $output[] = ['category_id' => $get_category['id'], 'category_name' => $get_category['name'], 'parent_id' => 0];
                }
            break;

            case "get_series_categories": // done and working
                $query              = $conn->query( "SELECT * FROM `vod_tv_categories` ORDER BY `name` " );
                $set_category       = $query->fetchAll( PDO::FETCH_ASSOC );
                $live_categories    = [];

                foreach( $set_category as $get_category ) {
                    $output[] = ['category_id' => $get_category['id'], 'category_name' => $get_category['name'], 'parent_id' => 0];
                }
            break;

            case "get_live_streams": // done and working
                $streams                = array();
                $bouquets_stream_array  = array();

                $line_bouquets          = explode( ",", $customer['bouquet'] );
                $line_bouquets          = array_filter( $line_bouquets );

                foreach( $line_bouquets as $bouquet_id ) {
                    // check if this bouquet is for live tv or not
                    $query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `id` = '".$bouquet_id."' AND `type` = 'live' " );
                    $bouquet_check = $query->fetch( PDO::FETCH_ASSOC );

                    if( $bouquet_check ) {
                        $query = $conn->query( "SELECT `content_id` FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet_id."' ORDER BY `order`+0,`content_id`+0 ASC " );
                        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

                        $temp_bouquet = array();
                        foreach( $bouquet_contents as $bouquet_content ) {
                            $bouquets_stream_array[] = $bouquet_content['content_id'];
                        }
                    }
                }

                $bouquets_stream_array = array_unique( $bouquets_stream_array );

                if( isset( $bouquets_stream_array ) ) {
                    // error_log( print_r( $bouquets_stream_array, true ) );

                    foreach( $bouquets_stream_array as $stream_id ) {
                        if( $stream_id != '' ) {                  
                            $category_id = get( "category_id" );
                            if( !empty( $category_id ) ) {
                                $statement      = " AND category_id = '".$category_id."' ";
                            } else {
                                $statement      = "";
                            }

                            $sql                = "SELECT `id`,`category_id`,`title`,`icon`,`epg_xml_id` FROM `channels` WHERE `id` = '".$stream_id."' ".$statement;
                            $query              = $conn->query( $sql );
                            $stream             = $query->fetch( PDO::FETCH_ASSOC );

                            if( isset( $stream['id'] ) ) {
                                $streams['streams'][] = $stream;
                            }
                        }
                    }

                    $i = 1;
                    $k = 0;

                    $streams['streams'] = array_filter( $streams['streams'] );

                    foreach( $streams['streams'] as $stream_value ) {
                        if( empty( $stream_value['logo'] ) ) {
                            $stream_value['logo'] = '';
                        }

                        $output[$k] = array(
                            'num' => $i,
                            'name' => $stream_value['title'],
                            'stream_type' => 'live',
                            'stream_id' => $stream_value['id'],
                            'stream_icon' => $stream_value['icon'],
                            'epg_channel_id' => $stream_value['epg_xml_id'],
                            'added' => NULL,
                            'category_id' => (string)$stream_value['category_id'],
                            'custom_sid' => '',
                            'tv_archive' => 0,
                            'direct_source' => '',
                            'tv_archive_duration' => 0
                        );

                        $i++;
                        $k++;
                    }
                }
            break;

            case "get_vod_streams": // done and working
                $movies                 = array();
                $movies['movies']       = array();
                
                $vod_bouquets           = explode( ",", $customer['bouquet'] );
                $vod_bouquets           = array_filter( $vod_bouquets );

                foreach( $vod_bouquets as $bouquet_id ) {
                     // check if this bouquet is for live tv or not
                    $query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `id` = '".$bouquet_id."' AND `type` = 'vod' " );
                    $bouquet_check = $query->fetch( PDO::FETCH_ASSOC );

                    if( $bouquet_check ) {
                        $query = $conn->query( "SELECT `content_id` FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet_id."' ORDER BY `order`+0,`content_id`+0 ASC " );
                        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

                        $temp_bouquet = array();
                        foreach( $bouquet_contents as $bouquet_content ) {
                            $bouquets_stream_array[] = $bouquet_content['content_id'];
                        }
                    }
                }

                if( isset( $bouquets_stream_array ) ) {
                    foreach( $bouquets_stream_array as $movie_id ) {  
                        if( $movie_id != '' ) {
                            $category_id = get( "category_id" );
                            if( !empty( $category_id ) ) {
                                $statement      = " AND category_id = '".$category_id."' ";
                            } else {
                                $statement      = "";
                            }

                            $sql        = "SELECT * FROM `vod` WHERE `id` = '".$movie_id."' ".$statement;
                            $query      = $conn->query( $sql );
                            $movie      = $query->fetch( PDO::FETCH_ASSOC );

                            if( isset( $movie['id'] ) ) {
                                // get the file container type
                                $sql                = "SELECT `file_location` FROM `vod_files` WHERE `vod_id` = '".$movie_id."' LIMIT 1";
                                $query              = $conn->query( $sql );
                                $movie['file']      = $query->fetch( PDO::FETCH_ASSOC );
                                $movie['fileinfo']  = pathinfo( $movie['file']['file_location'] );
                                $movie['container'] = $movie['fileinfo']['extension'];

                                $movies['movies'][] = $movie;
                            }
                        }
                    }

                    $i = 1;
                    $k = 0;

                    foreach( $movies['movies'] as $movie_value ) {
                        if( $movie_value['poster'] == 'N/A' ) {
                            $movie_value['poster'] = '';
                        }

                        $output[$k] = array(
                            'num' => $i,
                            'name' => stripslashes( $movie_value['title'] ),
                            'stream_type' => 'movie',
                            'stream_id' => $movie_value['id'],
                            'stream_icon' => $movie_value['poster'],
                            'rating' => '',
                            'rating_5based' => '',
                            'added' => NULL,
                            'category_id' => $movie_value['category_id'],
                            'container_extension' => $movie_value['container'],
                            'custom_sid' => '',
                            'direct_source' => ''
                        );
                        
                        $i++;
                        $k++;
                    }
                }
            break;

            case "get_series": // done and working
                $series                 = array();
                $series['series']       = array();
                
                $vod_tv_bouquets        = explode( ",", $customer['bouquet'] );
                $vod_tv_bouquets        = array_filter( $vod_tv_bouquets );

                foreach( $vod_tv_bouquets as $bouquet_id ) {
                     // check if this bouquet is for live tv or not
                    $query = $conn->query( "SELECT `id` FROM `bouquets` WHERE `id` = '".$bouquet_id."' AND `type` = 'vod_tv' " );
                    $bouquet_check = $query->fetch( PDO::FETCH_ASSOC );

                    if( $bouquet_check ) {
                        $query = $conn->query( "SELECT `content_id` FROM `bouquets_content` WHERE `bouquet_id` = '".$bouquet_id."' ORDER BY `order`+0,`content_id`+0 ASC " );
                        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

                        $temp_bouquet = array();
                        foreach( $bouquet_contents as $bouquet_content ) {
                            $bouquets_stream_array[] = $bouquet_content['content_id'];
                        }
                    }
                }

                if( isset( $bouquets_stream_array ) ) {
                    foreach( $bouquets_stream_array as $tv_id ) {  
                        if( $tv_id != '' ) {
                            $category_id = get( "category_id" );
                            if( !empty( $category_id ) ) {
                                $statement      = " AND category_id = '".$category_id."' ";
                            } else {
                                $statement      = "";
                            }

                            $sql        = "SELECT * FROM `vod_tv` WHERE `id` = '".$tv_id."' ".$statement;
                            $query      = $conn->query( $sql );
                            $tv         = $query->fetch( PDO::FETCH_ASSOC );

                            if( isset( $tv['id'] ) ) {
                                $series['series'][] = $tv;
                            }
                        }
                    }

                    $i = 1;
                    $k = 0;

                    foreach( $series['series'] as $tv_value ) {
                        $output[$k] = [
                            'num' => $i,
                            'name' => $tv_value['title'],
                            'series_id' => $tv_value['id'],
                            'cover' => $tv_value['poster'],
                            'plot' => $tv_value['plot'],
                            'cast' => '',
                            'director' => '',
                            'genre' => $tv_value['genre'],
                            'releaseDate' => $tv_value['year'],
                            'last_modified' => '',
                            'rating' => '',
                            'rating_5based' => '',
                            // 'backdrop_path' => [$serie_value['serie_pic']],
                            'backdrop_path' => [],
                            'youtube_trailer' => '',
                            'episode_run_time' => '',
                            'category_id' => (string)$tv_value['category_id']
                        ];
                        $i++;
                        $k++;
                    }
                }
            break;

            case "get_series_info": // partially working
                $series_id              = get( "series_id" );
                $series_info            = array();
                $episode_array          = array();
                $output['info']         = array();
                $output['episodes']     = array();
                
                $query                  = $conn->query( "SELECT * FROM `vod_tv` WHERE `id` = '".$series_id."' " );
                $set_season             = $query->fetchAll( PDO::FETCH_ASSOC );

                $query                  = $conn->query( "SELECT * FROM `vod_tv_files` WHERE `vod_id` = '".$series_id."' " );
                $set_serie_episode      = $query->fetchAll( PDO::FETCH_ASSOC );

                foreach( $set_serie_episode as $get_serie_episode ) {
                    // get file extension / container
                    $episode['fileinfo']  = pathinfo( $get_serie_episode['file_location'] );
                    $episode['container'] = $episode['fileinfo']['extension'];

                    $episode_array[] = [
                        'id'                  => $get_serie_episode['id'],
                        'episode_num'         => $get_serie_episode['episode'],
                        'title'               => $get_serie_episode['title'],
                        'container_extension' => $episode['container'],
                        'info'                => ['movie_image' => '', 
                                                    'plot' => stripslashes( $get_serie_episode['plot'] ), 
                                                    'releasedate' => $get_serie_episode['release_date'], 
                                                    'rating' => '', 
                                                    'name' => '', 
                                                    'duration_secs' => '', 
                                                    'duration' => ''
                                                ]
                    ];
                }

                foreach( $set_season as $season_key => $season_value ) {
                    // count episodes for this season
                    $query                  = $conn->query( "SELECT `id` FROM `vod_tv_files` WHERE `vod_id` = '".$series_id."' AND `season` = '".($season_key + 1)."' " );
                    $total_episodes         = $query->fetchAll( PDO::FETCH_ASSOC );

                    $output['seasons']['seasons'][]     = ['air_date' => '', 
                                                            'episode_count' => count( $total_episodes ), 
                                                            'id' => $season_value['id'], 
                                                            'name' => 'Season ' . ($season_key + 1), 
                                                            'overview' => '', 
                                                            'season_number' => $season_key + 1, 
                                                            'cover' => $season_value['poster'], 
                                                            'cover_big' => '',
                                                        ];
                    
                    $output['seasons']['info']          = ['name' => $season_value['title'], 
                                                            'cover' => $season_value['poster'], 
                                                            'plot' => stripslashes( $season_value['plot'] ), 
                                                            'cast' => '', 
                                                            'director' => '', 
                                                            'genre' => $season_value['genre'], 
                                                            'releaseDate' => $season_value['year'], 
                                                            'last_modified' => '', 
                                                            'reating' => '', 
                                                            'rating_5based' => '', 
                                                            'backdrop_path' => '',
                                                        ];

                    


                    /*
                    $set_serie_episode_array            = [$_REQUEST['series_id'], $season_key + 1];
                    $set_serie_episode = $conn->query( 'SELECT * FROM cms_serie_episodes WHERE serie_id = ? AND serie_episode_season = ?', $set_serie_episode_array);
                    $episode_array = [];

                    foreach( $set_serie_episode as $get_serie_episode ) {
                        $episode_array[] = [
                            'id'                  => $get_serie_episode['episode_id'],
                            'episode_num'         => $get_serie_episode['serie_episode_number'],
                            'title'               => $get_serie_episode['serie_episode_title'],
                            'container_extension' => $get_serie_episode['serie_episode_extension'],
                            'info'                => ['movie_image' => $season_value['serie_pic'], 
                                                        'plot' => '', 
                                                        'releasedate' => $get_serie_episode['serie_episode_release_date'], 
                                                        'rating' => $get_serie_episode['serie_episode_rating'], 
                                                        'name' => $get_serie_episode['serie_episode_title'], 
                                                        'duration_secs' => '', 
                                                        'duration' => ''
                                                    ],
                            'custom_sid'          => '',
                            'added'               => '',
                            'season'              => $season_key + 1,
                            'direct_source'       => ''
                        ];
                    }
                    */

                    $output['seasons']['episodes'][$season_key + 1] = $episode_array;
                }
            break;

            case 'get_simple_data_table': // not done
                $output['epg_listings'] = array();
            break;

            case 'get_short_epg': // not done
                $output['epg_listings'] = array();
            break;

            case 'get_vod_info': // done and working
                $output['info']         = array();
                $output['movie_data']   = array();
                $vod_id                 = get( "vod_id" );

                if ( !empty( $vod_id ) ) {
                    $query = $conn->query( "SELECT * FROM `vod` WHERE `id` = '".$vod_id."' " );
                    $vod = $query->fetch( PDO::FETCH_ASSOC );
                    
                    // get the file container type
                    $sql                = "SELECT `file_location` FROM `vod_files` WHERE `vod_id` = '".$vod_id."' LIMIT 1";
                    $query              = $conn->query( $sql );
                    $movie['file']      = $query->fetch( PDO::FETCH_ASSOC );
                    $movie['fileinfo']  = pathinfo( $movie['file']['file_location'] );
                    $movie['container'] = $movie['fileinfo']['extension'];

                    // $output['info'] = ipTV_lib::movieProperties($vod_id);
                    $output['info'] = '';
                    $output['movie_data'] = array(
                        'stream_id' => (int) $vod['id'], 
                        'name' => stripslashes( $vod['title'] ), 
                        'added' => '', 
                        'category_id' => $vod['category_id'], 
                        'container_extension' => $movie['container'], 
                        'custom_sid' => '', 
                        'direct_source' => ''
                    );
                }
                break;
        }
    } else {
        //Authentication
        $output['user_info'] = array(
                'username'               => $customer['username'],
                'password'               => $customer['password'],
                'message'                => '',
                'auth'                   => 1,
                'status'                 => $customer['status'],
                'exp_date'               => $customer['expire_date'],
                'is_trial'               => '0',
                'active_cons'            => (string) $customer['current_connections'],
                'created_at'             => $customer['updated'],
                'max_connections'        => (string) $customer['max_connections'],
                'allowed_output_formats' => ['m3u8', 'ts']
            );
        $output['server_info'] = array(
                'url'             => 'http://' . $_SERVER['HTTP_HOST'],
                'port'            => '80',
                'https_port'      => '25463',
                'server_protocol' => 'http',
                'rtmp_port'       => '1935',
                'timezone'        => 'Europe/London',
                'timestamp_now'   => time(),
                'time_now'        => date( 'Y-m-d H:i:s' )
        );
    }
}else{
    // $output['server_info']['sql']       = $sql;
    $output['user_info']['auth']        = 0;
}

json_output( $output );