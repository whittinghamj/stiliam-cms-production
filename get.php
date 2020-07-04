<?php
// session_start();

// includes
include( '/var/www/html/inc/db.php' );
include( '/var/www/html/inc/global_vars.php' );
include( '/var/www/html//inc/functions.php' );

$new_line               = "\n";

$dev                    = get( 'dev' );

// make sure username is set
$username               = get( 'username' );
if( empty( $username ) ) {
    echo '<pre>';
    print_r( $_GET) ;
    die( 'missing username' );
}

// make sure password is set
$password               = get( 'password' );
if( empty( $password ) ) {
    echo '<pre>';
    print_r( $_GET) ;
    die( 'missing password' );
}

// make sure type is set
$type                   = get( 'type' );
if(empty( $type) ) {
    echo '<pre>';
    print_r( $_GET);
    die( 'missing type' );
}

// get the output
$output                 = get( 'output' );
if( $output=='hls' ) {
    $file_output = 'm3u8';
} elseif( $output=='ts' ) {
    $file_output = 'ts';
} else {
    $file_output = 'm3u8';
}

// get the customers account
$query                  = $conn->query( "SELECT * FROM `customers` WHERE `username` = '".$username."' AND `password` = '".$password."' " );
$customer               = $query->fetch( PDO::FETCH_ASSOC );

// sanity check
if( empty( $customer) ) {
    die( "customer not found" );
}

// get the package for this customer
$query                  = $conn->query( "SELECT * FROM `packages` WHERE `id` = '".$customer['package_id']."' " );
$package                = $query->fetch( PDO::FETCH_ASSOC );

// build they array
$customer['bouquet']    = explode( ",", $package['bouquets'] );

if( $dev == 'yes' ) {
    debug( $customer );
    debug( $package );
}

if( $customer['status'] != 'active' ) {
    die( "account status: ".$customer['status'] );
}

// set defaults
$content                            = array();
$master_bouquet                     = array();

// get contents for each bouquet
foreach( $customer['bouquet'] as $bouquet) {
    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    $query                      = $conn->query( "SELECT * FROM `bouquets` WHERE `id` = '".$bouquet."' AND `type` = 'live' " );
    $live_temp_bouquet          = $query->fetch( PDO::FETCH_ASSOC );
    
    // did we find a match
    if( $live_temp_bouquet) {
        // get contents for this bouquet
        $query = $conn->query( "SELECT * FROM `bouquets_content` WHERE `bouquet_id` = '".$live_temp_bouquet['id']."' ORDER BY `order`+0, `content_id`+0 ASC " );
        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

        $temp_bouquet = array();
        foreach( $bouquet_contents as $bouquet_content) {
            $master_bouquet['live_tv'][] = $bouquet_content['content_id'];
        }

        $i = 0;
        if(isset( $master_bouquet['live_tv'] ) ) {
            foreach( $master_bouquet['live_tv'] as $content_id) {
                // get content data
                $sql        = "SELECT `id`,`title`,`category_id`,`icon`,`epg_xml_id` FROM `channels` WHERE `id` = '".$content_id."' ";
                $query      = $conn->query( $sql );
                $stream     = $query->fetch( PDO::FETCH_ASSOC );

                // get category
                $query      = $conn->query( "SELECT `name` FROM `channels_categories` WHERE `id` = '".$stream['category_id']."' " );
                $category   = $query->fetch( PDO::FETCH_ASSOC );

                if(empty( $stream['icon'] ) ) {
                    $logo = '';
                }else{
                    $logo = stripslashes( $stream['icon'] );
                }

                $content['live'][$i]['id']              = $stream['id'];
                $content['live'][$i]['category']        = stripslashes( $category['name'] );
                $content['live'][$i]['name']            = stripslashes( $stream['title'] );
                $content['live'][$i]['epg_xml_id']      = stripslashes( $stream['epg_xml_id'] );
                $content['live'][$i]['logo']            = $logo;

                $i++;
            }
        }
    }

    // remove dupes
    if( isset( $content['live'] ) && is_array( $content['live'] ) ) {
        $content['live'] = super_unique( $content['live'], 'id' );
    }
    
    // --------------------------------------------------
    // 24/7 TV Channels
    // --------------------------------------------------
    $query                      = $conn->query( "SELECT * FROM `bouquets` WHERE `id` = '".$bouquet."' AND `type` = 'channel_247' " );
    $channel_temp_bouquet       = $query->fetch( PDO::FETCH_ASSOC );
    
    // did we find a match
    if( $channel_temp_bouquet) {
        // get contents for this bouquet
        $query = $conn->query( "SELECT * FROM `bouquets_content` WHERE `bouquet_id` = '".$channel_temp_bouquet['id']."' ORDER BY `order`+0,`content_id`+0 ASC  " );
        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

        $temp_bouquet = array();
        foreach( $bouquet_contents as $bouquet_content) {
            $master_bouquet['247_channels'][] = $bouquet_content['content_id'];
        }

        $i = 0;
        if(isset( $master_bouquet['247_channels'] ) ) {
            foreach( $master_bouquet['247_channels'] as $content_id) {
                // get content data
                $sql        = "SELECT `id`,`server_id`,`title`,`poster` FROM `channels_247` WHERE `id` = '".$content_id."' ";
                $query      = $conn->query( $sql );
                $stream     = $query->fetch( PDO::FETCH_ASSOC );

                if(empty( $stream['poster'] ) ) {
                    $logo = '';
                }else{
                    $logo = stripslashes( $stream['poster'] );
                }

                $content['channel'][$i]['id']              = $stream['id'];
                $content['channel'][$i]['name']            = stripslashes( $stream['title'] );
                $content['channel'][$i]['logo']            = $logo;

                $i++;
            }
        }
    }

    // --------------------------------------------------
    // VoD
    // --------------------------------------------------
    $query                      = $conn->query( "SELECT * FROM `bouquets` WHERE `id` = '".$bouquet."' AND `type` = 'vod' " );
    $vod_temp_bouquet           = $query->fetch( PDO::FETCH_ASSOC );
    
    // did we find a match
    if( $vod_temp_bouquet) {
        // get contents for this bouquet
        $query = $conn->query( "SELECT * FROM `bouquets_content` WHERE `bouquet_id` = '".$vod_temp_bouquet['id']."' ORDER BY `order`+0,`content_id`+0 ASC  " );
        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

        $temp_bouquet = array();
        foreach( $bouquet_contents as $bouquet_content) {
            $master_bouquet['vod'][] = $bouquet_content['content_id'];
        }

        $i = 0;
        if(isset( $master_bouquet['vod'] ) ) {
            foreach( $master_bouquet['vod'] as $content_id) {
                // get content data
                $sql        = "SELECT `id`,`server_id`,`title`,`category_id`,`poster` FROM `vod` WHERE `id` = '".$content_id."' ";
                $query      = $conn->query( $sql );
                $stream     = $query->fetch( PDO::FETCH_ASSOC );

                // get category
                $query      = $conn->query( "SELECT `name` FROM `vod_categories` WHERE `id` = '".$stream['category_id']."' " );
                $category   = $query->fetch( PDO::FETCH_ASSOC );

                if(empty( $stream['poster'] ) ) {
                    $logo = '';
                }else{
                    $logo = stripslashes( $stream['poster'] );
                }

                $content['vod'][$i]['id']              = $stream['id'];
                $content['vod'][$i]['category']        = stripslashes( $category['name'] );
                $content['vod'][$i]['name']            = stripslashes( $stream['title'] );
                $content['vod'][$i]['logo']            = $logo;

                $i++;
            }
        }
    }

    // --------------------------------------------------
    // TV Series
    // --------------------------------------------------
    $query                      = $conn->query( "SELECT * FROM `bouquets` WHERE `id` = '".$bouquet."' AND `type` = 'vod_tv' " );
    $series_temp_bouquet        = $query->fetch( PDO::FETCH_ASSOC );
    
    // did we find a match
    if( $series_temp_bouquet) {
        // get contents for this bouquet
        $query = $conn->query( "SELECT * FROM `bouquets_content` WHERE `bouquet_id` = '".$series_temp_bouquet['id']."' ORDER BY `order`+0,`content_id`+0 ASC  " );
        $bouquet_contents = $query->fetchAll( PDO::FETCH_ASSOC );

        $temp_bouquet = array();
        foreach( $bouquet_contents as $bouquet_content) {
            $master_bouquet['series'][] = $bouquet_content['content_id'];
        }

        $i = 0;
        if(isset( $master_bouquet['series'] ) ) {
            foreach( $master_bouquet['series'] as $content_id) {
                // get content data
                $sql        = "SELECT `id`,`server_id`,`title`,`poster` FROM `vod_tv` WHERE `id` = '".$content_id."' ";
                $query      = $conn->query( $sql );
                $stream     = $query->fetch( PDO::FETCH_ASSOC );

                if(empty( $stream['cover_photo'] ) ) {
                    $logo = '';
                }else{
                    $logo = stripslashes( $stream['cover_photo'] );
                }

                // get episodes for this tv series
                $sql            = "SELECT * FROM `vod_tv_files` WHERE `vod_id` = '".$stream['id']."' ORDER BY `season`+0,`episode`+0 ";
                $query          = $conn->query( $sql );
                $series_files   = $query->fetchAll( PDO::FETCH_ASSOC );

                foreach( $series_files as $series_file) {
                    if(!empty( $series_file['poster'] ) ) {
                        $logo = stripslashes( $series_file['poster'] );
                    }

                    $content['series'][$i]['id']              = $series_file['id'];
                    $content['series'][$i]['series_name']     = stripslashes( $stream['title'] );
                    $content['series'][$i]['season']          = stripslashes( $series_file['season'] );
                    $content['series'][$i]['episode']         = stripslashes( $series_file['episode'] );
                    $content['series'][$i]['name']            = stripslashes( $series_file['title'] );
                    $content['series'][$i]['logo']            = $logo;

                    $i++;
                }
            }
        }
    }
}

if( $dev == 'yes' ) {
    debug( $content);
    die();
}

// what type of playlist do they want
if( $type == 'm3u' || $type == 'simple_m3u' ) {
    // Generate text file on the fly
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=playlist.m3u" );

    print "#EXTM3U".$new_line;

    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    if( isset( $content['live'] ) ) {
        foreach( $content['live'] as $live) {
            print '#EXTINF:-1,'.$live['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/live/".$customer['username']."/".$customer['password']."/".$live['id'].'.'.$file_output.$new_line;
        }
    }

    // --------------------------------------------------
    // 24/7 TV Channels
    // --------------------------------------------------
    if( isset( $content['channel'] ) ) {
        foreach( $content['channel'] as $channel) {
            print '#EXTINF:-1,24/7: '.$channel['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/channel/".$customer['username']."/".$customer['password']."/".$channel['id'].'.m3u8'.$new_line;
        }
    }

    // --------------------------------------------------
    // VoD
    // --------------------------------------------------
    if( isset( $content['vod'] ) ) {
        foreach( $content['vod'] as $vod) {
            print '#EXTINF:-1,Movie: '.$vod['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/movie/".$customer['username']."/".$customer['password']."/".$vod['id'].$new_line;
        }
    }

    // --------------------------------------------------
    // TV Series
    // --------------------------------------------------
    if( isset( $content['series'] ) ) {
        foreach( $content['series'] as $series) {
            print '#EXTINF:-1,TV Series: '.$series['series_name'].' '.$series['season'].'x'.$series['episode'].' '.$series['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/series/".$customer['username']."/".$customer['password']."/".$series['id'].$new_line;
        }
    }
}elseif( $type == 'm3u8' || $type == 'm3u_plus' || $type == 'advanced_m3u' ) {
    // Generate text file on the fly
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=playlist.m3u8" );

    print "#EXTM3U".$new_line;

    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    if( isset( $content['live'] ) ) {
        foreach( $content['live'] as $live) {
            if(empty( $live['category'] ) ) {
                $live['category'] = 'Default Category';
            }
            print '#EXTINF:-1 tvg-ID="'.$live['epg_xml_id'].'" tvg-name="'.$live['name'].'" tvg-logo="'.$live['logo'].'" group-title="Live Channels: '.$live['category'].'",'.$live['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/live/".$customer['username']."/".$customer['password']."/".$live['id'].'.'.$file_output.$new_line;
        }
    }

    // --------------------------------------------------
    // 24/7 TV Channels
    // --------------------------------------------------
    if( isset( $content['channel'] ) ) {
        foreach( $content['channel'] as $channel) {
            print '#EXTINF:-1 tvg-ID="'.$channel['name'].'" tvg-name="'.$live['name'].'" tvg-logo="'.$channel['logo'].'" group-title="24/7 TV Channels",'.$channel['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/channel/".$customer['username']."/".$customer['password']."/".$channel['id'].'.m3u8'.$new_line;
        }
    }

    // --------------------------------------------------
    // VoD
    // --------------------------------------------------
    if( isset( $content['vod'] ) ) {
        foreach( $content['vod'] as $vod) {
            if(empty( $vod['category'] ) ) {
                $vod['category'] = 'Default Category';
            }
            print '#EXTINF:-1 tvg-ID="'.$vod['name'].'" tvg-name="'.$vod['name'].'" tvg-logo="'.$vod['logo'].'" group-title="Movies VoD: '.$vod['category'].'",'.$vod['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/movie/".$customer['username']."/".$customer['password']."/".$vod['id'].$new_line;
        }
    }

    // --------------------------------------------------
    // TV Series
    // --------------------------------------------------
    if( isset( $content['series'] ) ) {
        foreach( $content['series'] as $series) {
            print '#EXTINF:-1 tvg-ID="'.$series['series_name'].' '.$series['season'].'x'.$series['episode'].' '.$series['name'].'" tvg-name="'.$series['series_name'].' '.$series['season'].'x'.$series['episode'].' '.$series['name'].'" tvg-logo="'.$series['logo'].'" group-title="TV VoD: '.$series['series_name'].'",'.$series['series_name'].' '.$series['season'].'x'.$series['episode'].' '.$series['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/series/".$customer['username']."/".$customer['password']."/".$series['id'].$new_line;
        }
    }
}elseif( $type == 'enigma' || $type == 'enigma22_script' ) {
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=iptv.sh" );

    $template = file_get_contents( "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/downloads/enigma_autoscript_template.txt" );

    $template = str_replace( '{$CMS_SERVER}', $global_settings['cms_access_url_raw'].":".$global_settings['cms_port'], $template);
    $template = str_replace( '{$USERNAME}', $username, $template);
    $template = str_replace( '{$PASSWORD}', $password, $template);

    print $template;
}elseif( $type == 'enigma22custom_script' ) {
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=iptv.sh" );

    $template = file_get_contents( "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/downloads/enigma_autoscript_template_custom.txt" );

    $template = str_replace( '{$CMS_SERVER}', $global_settings['cms_access_url_raw'].":".$global_settings['cms_port'], $template);
    $template = str_replace( '{$USERNAME}', $username, $template);
    $template = str_replace( '{$PASSWORD}', $password, $template);

    print $template;
}elseif( $type == 'dreambox' || $type == 'dreambox_custom' ) {
    //Generate text file on the fly
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=userbouquet.favourites.tv" );

    print "#NAME IPTV".$new_line;

    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    if( isset( $content['live'] ) ) {
        foreach( $content['live'] as $live) {
            print "#SERVICE 1:0:1:0:0:0:0:0:0:0:http%3A//".$global_settings['cms_access_url_raw']."%3A".$global_settings['cms_port']."/live/".$customer['username']."/".$customer['password']."/".$live['id'].".ts".$new_line;
            print "#DESCRIPTION ".$live['name'].$new_line;
        }
    }

    // --------------------------------------------------
    // 24/7 TV Channels
    // --------------------------------------------------
    if( isset( $content['channel'] ) ) {
        foreach( $content['channel'] as $channel) {
            print "#SERVICE 1:0:1:0:0:0:0:0:0:0:http%3A//".$global_settings['cms_access_url_raw']."%3A".$global_settings['cms_port']."/channel/".$customer['username']."/".$customer['password']."/".$channel['id'].".m3u8".$new_line;
            print "#DESCRIPTION 24/7: ".$channel['name'].$new_line;
        }
    }

    // --------------------------------------------------
    // VoD
    // --------------------------------------------------
    if( isset( $content['vod'] ) ) {
        foreach( $content['vod'] as $vod) {
            print "#SERVICE 1:0:1:0:0:0:0:0:0:0:http%3A//".$global_settings['cms_access_url_raw']."%3A".$global_settings['cms_port']."/movie/".$customer['username']."/".$customer['password']."/".$vod['id'].$new_line;
            print "#DESCRIPTION 24/7: ".$channel['name'].$new_line;
        }
    }
}elseif( $type == 'webtv' ) {
    //Generate text file on the fly
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=webtv list.txt" );

    // demo format
    // Channel name:UK: BBC 1 SD
    // URL:http://iptv.genexnetworks.net:10810/live/zorro83/SijgOLomAxT/42.ts

    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    if( isset( $content['live'] ) ) {
        foreach( $content['live'] as $live) {
            print "Channel name:".$live['name'].$new_line;  
            print "URL:http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/live/".$customer['username']."/".$customer['password']."/".$live['id'].".".$file_output.$new_line;
        }
    }
}elseif( $type == 'octagon' ) {
    //Generate text file on the fly
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=internettv.feed" );

    // demo format
    // [TITLE]
    // UK: BBC 1 SD
    // [URL]
    // http://iptv.genexnetworks.net:10810/live/zorro83/SijgOLomAxT/42.ts
    // [DESCRIPTION]
    // 
    // [TYPE]
    // Live

    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    if( isset( $content['live'] ) ) {
        foreach( $content['live'] as $live) {
            print "[TITLE]".$new_line;
            print "UK: ".$live['name'].$new_line;
            print "[URL]".$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/live/".$customer['username']."/".$customer['password']."/".$live['id'].".".$file_output.$new_line;
            print "[DESCRIPTION]".$new_line;
            print " ".$new_line;
            print "[TYPE]".$new_line;
            print "Live".$new_line;
        }
    }
}elseif( $type == 'dev' ) {
    // Generate text file on the fly
    header( "Content-type: text/plain" );
    header( "Content-Disposition: attachment; filename=playlist.m3u" );

    $new_line = "\n";

    // demo m3u format
    // #EXTM3U
    // #EXTINF:-1,CHANNEL NAME
    // http://link.to.stream

    // debug( $_GET);
    // debug( $customer);

    print "#EXTM3U".$new_line;

    // --------------------------------------------------
    // Live Streams
    // --------------------------------------------------
    if( isset( $content['live'] ) ) {
        foreach( $content['live'] as $live) {
            print '#EXTINF:-1,'.$live['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/live/".$customer['username']."/".$customer['password']."/".$live['id'].'.'.$file_output.$new_line;
        }
    }

    // --------------------------------------------------
    // 24/7 TV Channels
    // --------------------------------------------------
    if( isset( $content['channel'] ) ) {
        foreach( $content['channel'] as $channel) {
            print '#EXTINF:-1,24/7: '.$channel['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/channel/".$customer['username']."/".$customer['password']."/".$channel['id'].'.m3u8'.$new_line;
        }
    }

    // --------------------------------------------------
    // VoD
    // --------------------------------------------------
    if( isset( $content['vod'] ) ) {
        foreach( $content['vod'] as $vod) {
            print '#EXTINF:-1,VoD: '.$vod['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/movie/".$customer['username']."/".$customer['password']."/".$vod['id'].$new_line;
        }
    }

    // --------------------------------------------------
    // TV Series
    // --------------------------------------------------
    if( isset( $content['series'] ) ) {
        foreach( $content['series'] as $series) {
            print '#EXTINF:-1,Series: '.$series['series_name'].' '.$series['season'].'x'.$series['episode'].' '.$series['name'].$new_line;
            print "http://".$global_settings['cms_access_url_raw'].":".$global_settings['cms_port']."/series/".$customer['username']."/".$customer['password']."/".$series['id'].$new_line;
        }
    }
}else{
    echo "unknown playlist type";
}

exit;