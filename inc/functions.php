<?php

function array_sort( $data ) {
    usort( $data, function( $a, $b ) {
        return $a['name'] <=> $b['name'];
    });
}

function print_all_vars()
{
    $arr = get_defined_vars();
    return debug( $arr );
}

function objectToArray( $object )
{
    if( !is_object( $object ) && !is_array( $object ) ) {
        return $object;
    }
    return array_map( 'objectToArray', ( array ) $object );
}

function search_multi_array( $dataArray, $search_value, $key_to_search )
{
    // This function will search the revisions for a certain value
    // related to the associative key you are looking for.
    $keys = array();
    foreach( $dataArray as $key => $cur_value ) {
        if( $cur_value[$key_to_search] == $search_value ) {
            $keys[] = $key;
        }
    }
    return $keys;
}

function metadata_tmdb_movie( $show )
{
    $url = 'https://api.themoviedb.org/3/search/movie?api_key=61bbced4721538ca21a3c3529cf44323&language=en-US&page=1&&include_adult=false&query='.urlencode( $show );
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_HEADER, false );
    $metadata = curl_exec( $curl );
    curl_close( $curl);

    $metadata = json_decode( $metadata, true );

    return $metadata;
}

function metadata_tmdb_show( $show )
{
    $url = 'https://api.themoviedb.org/3/search/tv?api_key=61bbced4721538ca21a3c3529cf44323&language=en-US&page=1&&include_adult=false&query='.urlencode( $show );
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_HEADER, false );
    $metadata = curl_exec( $curl );
    curl_close( $curl);

    $metadata = json_decode( $metadata, true );

    return $metadata;
}

function metadata_tmdb_episode( $verbose, $show_id, $season, $episode )
{
    if( $verbose == true ) {
        // console_output( "- api call: https://api.themoviedb.org/3/tv/".$show_id."/season/".$season."/episode/".$episode."?api_key=61bbced4721538ca21a3c3529cf44323&language=en-US" );
    }

    $url = 'https://api.themoviedb.org/3/tv/'.$show_id.'/season/'.$season.'/episode/'.$episode.'?api_key=61bbced4721538ca21a3c3529cf44323&language=en-US';
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_HEADER, false );
    $metadata = curl_exec( $curl );
    curl_close( $curl);

    $metadata = json_decode( $metadata, true );

    return $metadata;
}

function get_servers_dropbox( $type )
{
    global $conn, $account_details, $globals, $global_settings;

    $data           = '';
    $query          = $conn->query( "SELECT `id`,`name` FROM `servers` WHERE `type` = '".$type."' ORDER BY `name` " );
    $servers        = $query->fetchAll( PDO::FETCH_ASSOC );
    foreach($servers as $server) {
        $data .= '<option value="'.$server['id'].'">'.$server['name'].'</option>';
    }

    return $data;
}

function get_all_servers()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `servers` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_allowed_ips()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `rtmp_allowed_ips` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_channels()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `channels` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_channel_primary_servers( $id )
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT `server_id` FROM `channels` WHERE `source_channel_id` = '".$id."' AND `type` = 'primary' " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function connections_per_media( $type, $type_id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $time_shift = time() - 15;

    // if $type is empty, get all connections for all media types
    $query = $conn->query( "SELECT `id` FROM `customers_connection_logs` WHERE `type` = '".$type."' AND `type_id` = '".$type_id."' AND `updated` > '".$time_shift."' " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );

    return $data;
}

function get_all_customer_connections( $customer_id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $time_shift = time() - 15;

    // if $type is empty, get all connections for all media types
    $query = $conn->query( "SELECT `id` FROM `customers_connection_logs` WHERE `customer_id` = '".$customer_id."' AND `updated` > '".$time_shift."' " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );

    return $data;
}

function get_all_customers_connections( )
{
    global $conn, $wp, $whmcs, $product_ids;

    $time_shift = time() - 15;

    // if $type is empty, get all connections for all media types
    $query = $conn->query( "SELECT * FROM `customers_connection_logs` WHERE `updated` > '".$time_shift."' " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );

    return $data;
}

function package_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `packages` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    $data['bouquets']    = explode( ",", $data['bouquets'] );

    $data = stripslashes_deep( $data );

    return $data;
}

function channel_category_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `channels_categories` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function vod_category_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `vod_categories` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function bouquet_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `bouquets` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_packages()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `packages` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_epg_sources()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `epg` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_channel_categories()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `channels_categories` ORDER BY `name` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );
    // $data = array_sort( $data );

    return $data;
}

function get_all_vod_categories()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `vod_categories` ORDER BY `name` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_vod_tv_categories()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `vod_tv_categories` ORDER BY `name` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_categories()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `vod_tv_categories` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_banned_ips()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `banned_ips` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_banned_isps()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `banned_isps` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_epg_channel_ids()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `epg_xml_ids` ORDER BY `xml_id` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_assets( $table )
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `".$table."` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_bouquets()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `bouquets` ORDER BY `name` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $count = 0;
    foreach( $data as $bouquet ) {
        $bouquets[$count]       = $bouquet;

        // get total contents
        $sql = "
                SELECT count(`id`) as total_contents 
                FROM `bouquets_content` 
                WHERE `bouquet_id` = '".$bouquet['id']."'  
            ";
        $query                                  = $conn->query($sql);
        $results                                = $query->fetchAll( PDO::FETCH_ASSOC );
        $bouquets[$count]['total_contents']     = $results[0]['total_contents'];
        
        $count++;
    }

    $data           = stripslashes_deep( $bouquets );

    return $data;
}

function get_all_247_channel_monitored_folders()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `channels_247_monitored_folders` ORDER BY `folder` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_vod_monitored_folders()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `vod_monitored_folders` ORDER BY `folder` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_vod_tv_monitored_folders()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `vod_tv_monitored_folders` ORDER BY `folder` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_customers()
{
    global $conn, $account_details, $globals, $global_settings;

    if( $account_details['type'] == 'admin' ) {
        $query          = $conn->query( "SELECT * FROM `customers` ORDER BY `username` " );
    } else {
        $query          = $conn->query( "SELECT * FROM `customers` WHERE `owner_id` = '".$account_details['id']."' ORDER BY `username` " );
    }
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_users()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `users` " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_admins()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `users` WHERE `type` = 'admin' " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_resellers()
{
    global $conn, $account_details, $globals, $global_settings;

    $query          = $conn->query( "SELECT * FROM `users` WHERE `type` = 'reseller' " );
    $data           = $query->fetchAll( PDO::FETCH_ASSOC );

    $data = stripslashes_deep( $data );

    return $data;
}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

function is_addon_installed($table)
{
    global $conn, $account_details, $globals, $global_settings;

    try {
        $result = $conn->query( "SELECT 1 FROM $table LIMIT 1" );
        return TRUE;
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    // return $result !== FALSE;
}

function addons_check()
{
    global $conn, $account_details, $globals, $global_settings;

    $addons = array();
    $query              = $conn->query( "SELECT * FROM `addon_licenses` " );
    $addon_licenses     = $query->fetchAll( PDO::FETCH_ASSOC );
    foreach($addon_licenses as $addon_license) {
        $license_call = @file_get_contents('http://slipstreamiptv.com/addon_license_check.php?license='.$addon_license['license']);
        $license_call = json_decode($license_call, true);

        // set product name into local db
        $update = $conn->exec("UPDATE `addon_licenses` SET `product` = '".$license_call['license']['product']."' WHERE `license` = '".$addon_license['license']."' " );

        if($license_call['status'] == 'error') {
            // there was an error
        }

        // license check for fta streams addon
        if($license_call['license']['product_id'] == 70) {
            $addons[70]['name'] = $license_call['license']['product'];
            $addons[70]['license'] = $addon_license['license'];
            if($license_call['license']['status'] == 'Active') {
                $addons[70]['status'] = 'enable';
            }else{
                $addons[70]['status'] = 'disable';
            }
        }
    }

    return $addons;
}

function ip_in_range($ip, $range)
{
    if (strpos($range, '/') == false) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list($range, $netmask) = explode('/', $range, 2);
    $ip_decimal = ip2long($ip);
    $range_decimal = ip2long($range);
    $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
    $netmask_decimal = ~ $wildcard_decimal;
    return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
}

function super_unique( $array,$key )
{
   $temp_array = [];
   foreach( $array as &$v ) {
       if( !isset( $temp_array[$v[$key]] ) )
       $temp_array[$v[$key]] =& $v;
   }
   $array = array_values( $temp_array );
   return $array;
}
    
function multi_unique( $src )
{
    $output = array_map( "unserialize" , array_unique( array_map( "serialize", $src ) ) );
    return $output;
}

function get_metadata( $name )
{
    $name = trim( $name );

    $data['status']             = 'no_match';
    $data['title']              = $name;
    $data['year']               = '';
    $data['poster']             = '';
    $data['description']        = '';
    $data['genre']              = '';
    $data['runtime']            = '';
    $data['language']           = '';
    $data['rating']             = '';
    $data['imdbid']             = '';
    $data['plot']               = '';

    if(empty($name)) {
        
    }else{
        // try the open movie db for meta data
        $url = 'http://www.omdbapi.com/?apikey=19354e2e&t='.urlencode($name);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $metadata = curl_exec($curl);
        curl_close($curl);

        $metadata = json_decode($metadata, true);

        if($metadata['Response'] == False || $metadata['Response'] == "False") {
            $data['status']         = 'no_match';
        }elseif($metadata['Response'] == True) {
            $data['status']         = 'match';
            $data['title']          = addslashes($metadata['Title']);
            $data['year']           = addslashes($metadata['Year']);
            $data['poster']         = addslashes($metadata['Poster']);
            $data['description']    = addslashes($metadata['Plot']);
            $data['genre']          = addslashes($metadata['Genre']);
            $data['runtime']        = addslashes($metadata['Runtime']);
            $data['language']       = addslashes($metadata['Language']);
            $data['rating']         = addslashes($metadata['Rated']);
            $data['imdbid']         = addslashes($metadata['imdbID']);
            $data['plot']           = addslashes($metadata['Plot']);
        }
    }

    return $data;
}

function get_metadata_by_id( $id )
{
    $id = trim( $id );

    $data['status']             = 'no_match';
    $data['title']              = '';
    $data['year']               = '';
    $data['poster']             = '';
    $data['description']        = '';
    $data['genre']              = '';
    $data['runtime']            = '';
    $data['language']           = '';
    $data['rating']             = '';
    $data['imdbid']             = '';
    $data['plot']               = '';

    // try the open movie db for meta data
    $url = 'http://www.omdbapi.com/?apikey=19354e2e&i='.urlencode($id);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $metadata = curl_exec($curl);
    curl_close($curl);

    $metadata = json_decode($metadata, true);

    if($metadata['Response'] == False || $metadata['Response'] == "False") {
        $data['status']         = 'no_match';
    }elseif($metadata['Response'] == True) {
        $data['status']         = 'match';
        $data['title']          = addslashes($metadata['Title']);
        $data['year']           = addslashes($metadata['Year']);
        $data['poster']         = addslashes($metadata['Poster']);
        $data['description']    = addslashes($metadata['Plot']);
        $data['genre']          = addslashes($metadata['Genre']);
        $data['runtime']        = addslashes($metadata['Runtime']);
        $data['language']       = addslashes($metadata['Language']);
        $data['rating']         = addslashes($metadata['Rated']);
        $data['imdbid']         = addslashes($metadata['imdbID']);
        $data['plot']           = addslashes($metadata['Plot']);
    }

    return $data;
}

function encrypt( $string, $key=5 )
{
    $result = '';
    for($i=0, $k= strlen($string); $i<$k; $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)+ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
}

function decrypt( $string, $key=5 )
{
    $result = '';
    $string = base64_decode($string);
    for($i=0,$k=strlen($string); $i< $k ; $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)-ord($keychar));
        $result.=$char;
    }
    return $result;
}

function is_customer_connection_allowed( $customer_id ) {
    global $conn, $account_details, $globals, $global_settings;

    $time_shift = time() - 15;

    $total_connections = 0;
    $total_live_connections = 0;
    $total_vod_connections = 0;
    $total_247_connections = 0;

    $query = $conn->query( "SELECT * FROM `customers` WHERE `id` = '".$customer_id."' " );
    $customer = $query->fetch( PDO::FETCH_ASSOC );

    $query = $conn->query( "SELECT `id` FROM `streams_connection_logs` WHERE `customer_id` = '".$customer['id']."' AND `timestamp` > '".$time_shift."' " );
    $connection_data = $query->fetchAll( PDO::FETCH_ASSOC );
    $total_live_connections = count($connection_data);

    $query = $conn->query( "SELECT `id` FROM `channel_connection_logs` WHERE `customer_id` = '".$customer['id']."' AND `timestamp` > '".$time_shift."' " );
    $connection_data = $query->fetchAll( PDO::FETCH_ASSOC );
    $total_247_connections = count($connection_data);

    $total_connections = ($total_live_connections + $total_vod_connections + $total_247_connections);

    if($total_connections > $customer['max_connections']) {
        return 'no';
    }else{
        return 'yes';
    }
}

function cf_add_host( $hostname, $domain, $ip_address ) {
    global $cloudflare;

    $cloudflare['hostname']         = $hostname;
    $cloudflare['domain']           = $domain;
    $cloudflare['new_ip']           = $ip_address;
    $quote_single                   = "'";

    if($cloudflare['domain'] == 'slipstreamiptv.com') {
        $cloudflare['zone_id']          = '52d18db9c2d87e6c09195acbabf7266a';
    }

    // slipstreamiptv.com
    if($cloudflare['domain'] == 'akamaihdcdn.com') {
        $cloudflare['zone_id']          = 'fd7faf9b5d7a2858a2178cb3d463afcf';
    }

    // slipdns.com
    if($cloudflare['domain'] == 'slipdns.com') {
        $cloudflare['zone_id']          = '438de119f5768ba2a151a5d813613ebe';
    }

    // streamcdn.org
    if($cloudflare['domain'] == 'streamcdn.org') {
        $cloudflare['zone_id']          = 'cd40b80a1078d35c7ba73494e1f2eecd';
    }

    $data = array(
        'type' => 'A',
        'name' => ''.$cloudflare['hostname'].'',
        'content' => ''.$cloudflare['new_ip'].'',
        'ttl' => 120,
        'priority' => 10,
        'proxied' => false
    );

    $data = json_encode($data);

    $curl_command = 'curl -s -X POST "https://api.cloudflare.com/client/v4/zones/'.$cloudflare['zone_id'].'/dns_records" -H "X-Auth-Email: '.$cloudflare['email'].'" -H "X-Auth-Key: '.$cloudflare['api_key'].'" -H "Content-Type: application/json" --data '.$quote_single.''.$data.''.$quote_single.' ';

    $results = shell_exec($curl_command);

    $results = json_decode($results, true);

    $cloudflare['domain_id'] = $results['result']['id'];

    return $cloudflare;
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function ping( $host ) {
    exec( sprintf( 'ping -c 5 -W 5 %s', escapeshellarg( $host ) ), $res, $rval );
    return $rval === 0;
}

function cors() {
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}" );
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS" );         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}" );

        exit(0);
    }

    // echo "You have CORS!";
}

function get_all_servers_ids()
{
    global $conn, $account_details, $globals, $global_settings;

    $query = $conn->query( "SELECT `id`,`status` FROM `servers` ORDER BY `name` " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );

    return $data;
}

function total_bandwidth()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT `bandwidth_down`,`bandwidth_up`  
        FROM `servers` 
        WHERE `user_id` = '".$_SESSION['account']['id']."' 
        AND `status` = 'online' 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data['bandwidth_down']         = 0;
    $data['bandwidth_up']           = 0;

    if(is_array($results)) {
        foreach($results as $result) {
            $data['bandwidth_down']     = $data['bandwidth_down'] + $result['bandwidth_down'];
            $data['bandwidth_up']       = $data['bandwidth_up'] + $result['bandwidth_up'];
        }

        $data['bandwidth_down']     = number_format($data['bandwidth_down'] / 125, 0);
        $data['bandwidth_up']       = number_format($data['bandwidth_up'] / 125, 0);
    }

    return $data;
}

function total_online_clients()
{
    global $conn, $account_details, $globals, $global_settings;

    $time_shift = time() - 20;
    $sql = "
        SELECT `id` FROM `streams_connection_logs` 
        WHERE `timestamp` >= '".$time_shift."'
        AND customer_id != '0'
    ";
    $query = $conn->query($sql);
    $stream['online_clients'] = $query->fetchAll( PDO::FETCH_ASSOC );
    $stream['total_online_clients'] = count($stream['online_clients']);

    $sql = "
        SELECT `id` FROM `channel_connection_logs` 
        WHERE `timestamp` >= '".$time_shift."'
        AND customer_id != '0'
    ";
    $query = $conn->query($sql);
    $channel['online_clients'] = $query->fetchAll( PDO::FETCH_ASSOC );
    $channel['total_online_clients'] = count($channel['online_clients']);
    
    $total_online = ($stream['total_online_clients'] + $channel['total_online_clients']);

    // $client_data = '';
    // foreach($stream['online_clients'] as $client) {
    //     $client_data .= 'IP: '.$client['client_ip'].'<br>';
    // }

    return $total_online;
}

function total_customers()
{
    global $conn, $account_details, $global_settings;

    if( $account_details['type'] == 'admin' ) {
        $sql = "
            SELECT count(`id`) as total_customers 
            FROM `customers` 
        ";
    } else {
                $sql = "
            SELECT count(`id`) as total_customers 
            FROM `customers` 
            WHERE `owner_id` = '".$_SESSION['account']['id']."' 
        ";
    }
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_customers'];

    return $data;
}

function count_customers( $id )
{
    global $conn, $account_details, $global_settings;

    $sql = "
        SELECT count(`id`) as total_customers 
        FROM `customers` 
        WHERE `owner_id` = '".$id."' 
    ";

    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_customers'];

    return $data;
}

function total_mags()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`mag_id`) as total_mags 
        FROM `mag_devices`  
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_mags'];

    return $data;
}

function total_resellers()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_resellers 
        FROM `resellers` 
        WHERE `user_id` = '".$_SESSION['account']['id']."' 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_resellers'];

    return $data;
}

function total_stream_outputs($id)
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_outputs 
        FROM `streams` 
        WHERE `source_stream_id` = '".$id."' 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_outputs'];

    return $data;
}

function total_servers()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_servers 
        FROM `servers`  
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_servers'];

    return $data;
}

function total_online_servers()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_servers 
        FROM `servers` 
        WHERE `user_id` = '".$_SESSION['account']['id']."' 
        AND `status` = 'online' 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_servers'];

    return $data;
}

function total_offline_servers()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_servers 
        FROM `servers` 
        WHERE `user_id` = '".$_SESSION['account']['id']."' 
        AND `status` = 'offline' 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_servers'];

    return $data;
}

function total_channels()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_channels 
        FROM `channels` 

    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_channels'];

    return $data;
}

function total_channels_status( $status )
{
    global $conn, $account_details, $globals, $global_settings;

    if( $status == 'online' ) {
        $sql = "
            SELECT count(`id`) as totals 
            FROM `channels` 
            WHERE `status` = 'online' 
        ";
    } else {
        $sql = "
        SELECT count(`id`) as totals 
        FROM `channels` 
        WHERE `status` != 'online' 
    ";
    }
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['totals'];

    return $data;
}

function total_channels_247()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_channels_247 
        FROM `channels_247` 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_channels_247'];

    return $data;
}

function total_channels_247_status( $status )
{
    global $conn, $account_details, $globals, $global_settings;

    if( $status == 'online' ) {
        $sql = "
            SELECT count(`id`) as totals 
            FROM `channels_247` 
            WHERE `status` = 'online' 
        ";
    } else {
        $sql = "
        SELECT count(`id`) as totals 
        FROM `channels_247` 
        WHERE `status` != 'online' 
    ";
    }
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['totals'];

    return $data;
}

function total_vod()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_vod 
        FROM `vod` 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_vod'];

    return $data;
}

function total_vod_tv()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_vod_tv 
        FROM `vod_tv` 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_vod_tv'];

    return $data;
}

function total_cdn_streams()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_streams 
        FROM `cdn_streams_servers` 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_streams'];

    return $data;
}

function total_firewall_rules()
{
    global $conn, $account_details, $globals, $global_settings;

    $sql = "
        SELECT count(`id`) as total_firewall_rules 
        FROM `streams_acl_rules` 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetchAll( PDO::FETCH_ASSOC );
    $data       = $results[0]['total_firewall_rules'];

    return $data;
}

function geoip($ip)
{
    global $conn, $account_details, $globals, $global_settings;

    // check for existing lat, lng
    $sql = "
        SELECT `id`,`lat`,`lng`,`country_code`,`country_name`,`region_name`,`city`,`zip_code`,`time_zone` 
        FROM `servers` 
        WHERE `wan_ip_address` = '".$ip."' 
        AND `lat` != '' 
        AND `lng` != '' 
        LIMIT 1 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetch( PDO::FETCH_ASSOC );

    if(isset($results['id'])) {
        $response['latitude']       = $results['lat'];
        $response['longitude']      = $results['lng'];
        $response['country_code']   = $results['country_code'];
        $response['country_name']   = $results['country_name'];
        $response['region_name']    = $results['region_name'];
        $response['city']           = $results['city'];
        $response['zip_code']       = $results['zip_code'];
        $response['time_zone']      = $results['time_zone'];

        return $response;
    }else{
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://freegeoip.app/json/".$ip,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        $err = curl_error($curl);

        curl_close($curl);

        if($err) {
            return "cURL Error #:" . $err;
        }else{
            if($response['latitude'] != 0 && $response['longitude'] != 0) {
                // insert into db for later use
                $update = $conn->exec("UPDATE `servers` SET `lat` = '".$response['latitude']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `lng` = '".$response['longitude']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `country_code` = '".$response['country_code']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `country_name` = '".$response['country_name']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `region_name` = '".$response['region_name']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `city` = '".$response['city']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `zip_code` = '".$response['zip_code']."' WHERE `wan_ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `servers` SET `time_zone` = '".$response['time_zone']."' WHERE `wan_ip_address` = '".$ip."' " );
            }

            return $response;
        }
    }
}

function geoip_all($ip)
{
    global $conn, $account_details, $globals, $global_settings;

    // check for existing lat, lng
    $sql = "
        SELECT `id`,`lat`,`lng`,`country_code`,`country_name`,`region_name`,`city`,`zip_code`,`time_zone` 
        FROM `geoip` 
        WHERE `ip_address` = '".$ip."' 
        AND `lat` != '' 
        AND `lng` != '' 
        LIMIT 1 
    ";
    $query      = $conn->query($sql);
    $results    = $query->fetch( PDO::FETCH_ASSOC );

    if(isset($results['id'])) {
        $response['latitude']       = $results['lat'];
        $response['longitude']      = $results['lng'];
        $response['country_code']   = $results['country_code'];
        $response['country_name']   = $results['country_name'];
        $response['region_name']    = $results['region_name'];
        $response['city']           = $results['city'];
        $response['zip_code']       = $results['zip_code'];
        $response['time_zone']      = $results['time_zone'];

        return $response;
    }else{
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://freegeoip.app/json/".$ip,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        $err = curl_error($curl);

        curl_close($curl);

        if($err) {
            return "cURL Error #:" . $err;
        } else {
            if($response['latitude'] != 0 && $response['longitude'] != 0) {
                // insert into db for later use
                $insert = $conn->exec("INSERT INTO `geoip` 
                    (`ip_address`)
                    VALUE
                    ('".$ip."')
                " );

                $update = $conn->exec("UPDATE `geoip` SET `lat` = '".$response['latitude']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `lng` = '".$response['longitude']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `country_code` = '".$response['country_code']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `country_name` = '".$response['country_name']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `region_name` = '".$response['region_name']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `city` = '".$response['city']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `zip_code` = '".$response['zip_code']."' WHERE `ip_address` = '".$ip."' " );
                $update = $conn->exec("UPDATE `geoip` SET `time_zone` = '".$response['time_zone']."' WHERE `ip_address` = '".$ip."' " );
            }

            return $response;
        }
    }
}

function get_full_url()
{
   $http=isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

   $part=rtrim($_SERVER['SCRIPT_NAME'],basename($_SERVER['SCRIPT_NAME']));

   $domain=$_SERVER['SERVER_NAME'];

   return "$http"."$domain"."$part";
}

function log_add($action, $message = '')
{
	global $conn, $account_details, $globals, $global_settings;

	$message = addslashes($message);

	$insert = $conn->exec("INSERT INTO `user_logs` 
        (`added`,`user_id`,`action`,`message`)
        VALUE
        ('".time()."','".$_SESSION['account']['id']."','".$action."','".$message."')" );

}

function uptime( int $seconds, int $requiredParts = null )
{
    $from     = new \DateTime('@0');
    $to       = new \DateTime("@$seconds" );
    $interval = $from->diff($to);
    $str      = '';

    $parts = [
        'y' => 'y',
        'm' => 'm',
        'd' => 'd',
        'h' => 'h',
        'i' => 'm',
        's' => 's',
    ];

    $includedParts = 0;

    foreach ($parts as $key => $text) {
        if ($requiredParts && $includedParts >= $requiredParts) {
            break;
        }

        $currentPart = $interval->{$key};

        if (empty($currentPart)) {
            continue;
        }

        if (!empty($str)) {
            $str .= ', ';
        }

        $str .= sprintf('%d%s', $currentPart, $text);

        if ($currentPart > 1) {
            // handle plural
            $str .= '';
        }

        $includedParts++;
    }

    return $str;
}

function uptime_old($inputSeconds)
{
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;

    // Extract days
    $days = floor($inputSeconds / $secondsInADay);

    // Extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // Extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // Extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // Format and return
    $timeParts = [];
    $sections = [
        'd' => (int)$days,
        'h' => (int)$hours,
        'm' => (int)$minutes,
        // 's' => (int)$seconds,
    ];

    foreach ($sections as $name => $value) {
        if ($value > 0) {
            $timeParts[] = $value.$name.($value == 1 ? '' : '');
        }
    }

    return implode(', ', $timeParts);
}

function code_to_country($code)
{
    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
}

function code_to_isoa3( $code )
{
	$codes = json_decode('{"BD": "BGD", "BE": "BEL", "BF": "BFA", "BG": "BGR", "BA": "BIH", "BB": "BRB", "WF": "WLF", "BL": "BLM", "BM": "BMU", "BN": "BRN", "BO": "BOL", "BH": "BHR", "BI": "BDI", "BJ": "BEN", "BT": "BTN", "JM": "JAM", "BV": "BVT", "BW": "BWA", "WS": "WSM", "BQ": "BES", "BR": "BRA", "BS": "BHS", "JE": "JEY", "BY": "BLR", "BZ": "BLZ", "RU": "RUS", "RW": "RWA", "RS": "SRB", "TL": "TLS", "RE": "REU", "TM": "TKM", "TJ": "TJK", "RO": "ROU", "TK": "TKL", "GW": "GNB", "GU": "GUM", "GT": "GTM", "GS": "SGS", "GR": "GRC", "GQ": "GNQ", "GP": "GLP", "JP": "JPN", "GY": "GUY", "GG": "GGY", "GF": "GUF", "GE": "GEO", "GD": "GRD", "GB": "GBR", "GA": "GAB", "SV": "SLV", "GN": "GIN", "GM": "GMB", "GL": "GRL", "GI": "GIB", "GH": "GHA", "OM": "OMN", "TN": "TUN", "JO": "JOR", "HR": "HRV", "HT": "HTI", "HU": "HUN", "HK": "HKG", "HN": "HND", "HM": "HMD", "VE": "VEN", "PR": "PRI", "PS": "PSE", "PW": "PLW", "PT": "PRT", "SJ": "SJM", "PY": "PRY", "IQ": "IRQ", "PA": "PAN", "PF": "PYF", "PG": "PNG", "PE": "PER", "PK": "PAK", "PH": "PHL", "PN": "PCN", "PL": "POL", "PM": "SPM", "ZM": "ZMB", "EH": "ESH", "EE": "EST", "EG": "EGY", "ZA": "ZAF", "EC": "ECU", "IT": "ITA", "VN": "VNM", "SB": "SLB", "ET": "ETH", "SO": "SOM", "ZW": "ZWE", "SA": "SAU", "ES": "ESP", "ER": "ERI", "ME": "MNE", "MD": "MDA", "MG": "MDG", "MF": "MAF", "MA": "MAR", "MC": "MCO", "UZ": "UZB", "MM": "MMR", "ML": "MLI", "MO": "MAC", "MN": "MNG", "MH": "MHL", "MK": "MKD", "MU": "MUS", "MT": "MLT", "MW": "MWI", "MV": "MDV", "MQ": "MTQ", "MP": "MNP", "MS": "MSR", "MR": "MRT", "IM": "IMN", "UG": "UGA", "TZ": "TZA", "MY": "MYS", "MX": "MEX", "IL": "ISR", "FR": "FRA", "IO": "IOT", "SH": "SHN", "FI": "FIN", "FJ": "FJI", "FK": "FLK", "FM": "FSM", "FO": "FRO", "NI": "NIC", "NL": "NLD", "NO": "NOR", "NA": "NAM", "VU": "VUT", "NC": "NCL", "NE": "NER", "NF": "NFK", "NG": "NGA", "NZ": "NZL", "NP": "NPL", "NR": "NRU", "NU": "NIU", "CK": "COK", "XK": "XKX", "CI": "CIV", "CH": "CHE", "CO": "COL", "CN": "CHN", "CM": "CMR", "CL": "CHL", "CC": "CCK", "CA": "CAN", "CG": "COG", "CF": "CAF", "CD": "COD", "CZ": "CZE", "CY": "CYP", "CX": "CXR", "CR": "CRI", "CW": "CUW", "CV": "CPV", "CU": "CUB", "SZ": "SWZ", "SY": "SYR", "SX": "SXM", "KG": "KGZ", "KE": "KEN", "SS": "SSD", "SR": "SUR", "KI": "KIR", "KH": "KHM", "KN": "KNA", "KM": "COM", "ST": "STP", "SK": "SVK", "KR": "KOR", "SI": "SVN", "KP": "PRK", "KW": "KWT", "SN": "SEN", "SM": "SMR", "SL": "SLE", "SC": "SYC", "KZ": "KAZ", "KY": "CYM", "SG": "SGP", "SE": "SWE", "SD": "SDN", "DO": "DOM", "DM": "DMA", "DJ": "DJI", "DK": "DNK", "VG": "VGB", "DE": "DEU", "YE": "YEM", "DZ": "DZA", "US": "USA", "UY": "URY", "YT": "MYT", "UM": "UMI", "LB": "LBN", "LC": "LCA", "LA": "LAO", "TV": "TUV", "TW": "TWN", "TT": "TTO", "TR": "TUR", "LK": "LKA", "LI": "LIE", "LV": "LVA", "TO": "TON", "LT": "LTU", "LU": "LUX", "LR": "LBR", "LS": "LSO", "TH": "THA", "TF": "ATF", "TG": "TGO", "TD": "TCD", "TC": "TCA", "LY": "LBY", "VA": "VAT", "VC": "VCT", "AE": "ARE", "AD": "AND", "AG": "ATG", "AF": "AFG", "AI": "AIA", "VI": "VIR", "IS": "ISL", "IR": "IRN", "AM": "ARM", "AL": "ALB", "AO": "AGO", "AQ": "ATA", "AS": "ASM", "AR": "ARG", "AU": "AUS", "AT": "AUT", "AW": "ABW", "IN": "IND", "AX": "ALA", "AZ": "AZE", "IE": "IRL", "ID": "IDN", "UA": "UKR", "QA": "QAT", "MZ": "MOZ"}', true);

	foreach ($codes as $key => $value) {

	    if($key == $code) {
	        return $value;
	    }
	}
}

function account_details( $id )
{
	global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `users` WHERE `id` = '".$id."' " );
    $user = $query->fetch( PDO::FETCH_ASSOC );

	$user['avatar'] = get_gravatar( $user['email'] );

    $user = stripslashes_deep( $user );

	return $user;
}

function get_customer_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `customers` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    if( !empty( $data['email'] ) ) {
        $data['avatar'] = get_gravatar( $data['email'] );
    }

    // get allowed ip addresses
    $query = $conn->query("SELECT * FROM `customers_ips` WHERE `customer_id` = '".$id."' ");
    $data['ip_addresses'] = $query->fetchAll( PDO::FETCH_ASSOC );

    // get mag devices
    $query = $conn->query("SELECT * FROM `mag_devices` WHERE `customer_id` = '".$id."' ");
    $data['mag_devices'] = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_channel_topology_profiles( )
{
    global $conn, $wp, $whmcs, $product_ids;

    // get mag devices
    $query = $conn->query("SELECT * FROM `channels_topology_profiles` ");
    $data = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_channel_topology_profile( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    // get mag devices
    $query = $conn->query("SELECT * FROM `channels_topology_profiles` WHERE `id` = '".$id."' ");
    $data = $query->fetch( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_mag_devices( )
{
    global $conn, $wp, $whmcs, $product_ids;

    // get mag devices
    $query = $conn->query("SELECT * FROM `mag_devices` ");
    $data = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function vod_details( $id )
{
    global $conn, $global_settings, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `vod` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    if( empty( $data['poster'] ) || $data['poster'] == 'N/A' ) {
        $data['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
    }

    // get file locations for this item
    $query = $conn->query("SELECT * FROM `vod_files` WHERE `vod_id` = '".$data['id']."' ");
    $data['file_locations'] = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function vod_tv_details( $id )
{
    global $conn, $global_settings, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `vod_tv` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    if( empty( $data['poster'] ) || $data['poster'] == 'N/A' ) {
        $data['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
    }

    // get file locations for this item
    $query = $conn->query("SELECT * FROM `vod_tv_files` WHERE `vod_id` = '".$data['id']."' ");
    $data['files'] = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function transcoding_profiles_details( )
{
    global $conn, $global_settings, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `transcoding_profiles` ORDER BY `name` " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function transcoding_profile_details( $id )
{
    global $conn, $global_settings, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `transcoding_profiles` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );
    $data['data'] = json_decode( $data['data'], true );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function channels_247_details( $id )
{
    global $conn, $global_settings, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `channels_247` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    if( empty( $data['poster'] ) || $data['poster'] == 'N/A' ) {
        $data['poster'] = 'http://'.$global_settings['cms_ip'].'/img/large_movie_poster.png';
    }

    // get file locations for this item
    $query = $conn->query("SELECT * FROM `channels_247_files` WHERE `vod_id` = '".$data['id']."' ");
    $data['files'] = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_channel_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    // get channel control record
    $query = $conn->query( "SELECT * FROM `channels` WHERE `id` = '".$id."' " );
    $data = $query->fetch( PDO::FETCH_ASSOC );

    // get channel_sources
    $query = $conn->query( "SELECT * FROM `channels_sources` WHERE `channel_id` = '".$id."' " );
    $data['sources'] = $query->fetchAll( PDO::FETCH_ASSOC );

    // get channel_servers
    $query = $conn->query( "SELECT * FROM `channels_servers` WHERE `channel_id` = '".$id."' " );
    $data['servers'] = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_isps( )
{
    global $conn, $wp, $whmcs, $product_ids;

    // get channel control record
    $query = $conn->query( "SELECT * FROM `geoip_isps` ORDER BY `isp_name` " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function get_all_channel_sources( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    // get channel control record
    $query = $conn->query( "SELECT * FROM `channels_sources` WHERE `channel_id` = '".$id."' " );
    $data = $query->fetchAll( PDO::FETCH_ASSOC );
    
    $data = stripslashes_deep( $data );

    return $data;
}

function server_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `servers` WHERE `id` = '".$id."' " );
    $server = $query->fetch( PDO::FETCH_ASSOC );

    $server = stripslashes_deep( $server );

    return $server;
}

function channel_247_details( $id )
{
    global $conn, $wp, $whmcs, $product_ids;

    $query = $conn->query( "SELECT * FROM `channels_247` WHERE `id` = '".$id."' " );
    $channel = $query->fetch( PDO::FETCH_ASSOC );

    $channel = stripslashes_deep( $channel );

    return $channel;
}

function console_output( $data )
{
	$timestamp = date( "Y-m-d H:i:s", time() );
	echo "[" . $timestamp . "] - " . $data . "\n";
}

function json_output( $data )
{
	// $data['timestamp']		= time();
	$data 					= json_encode($data);
	echo $data;
	die();
}

function formatbytes( $size, $precision = 2 )
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   

    // return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    return round(pow(1024, $base - floor($base)), $precision);
}

function filesize_formatted( $path )
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function percentage( $val1, $val2, $precision )
{
    // sanity - remove non-number chars
    $val1 = preg_replace("/[^0-9]/", "", $val1);
    $val2 = preg_replace("/[^0-9]/", "", $val2);

	$division = $val1 / $val2;
	$res = $division * 100;
	$res = round($res, $precision);
	return $res;
}

function get_medication( $medication )
{
    global $conn, $account_details, $globals, $global_settings;

    $bottle_time = time();

    $medication_check = take_medication($medication,$bottle_time);
    if($medication_check != false) {
        $med    = encrypt($medication);
        $update = "INSERT INTO `global_settings`(`config_name`, 'config_value') VALUES(`bGljZW5zZV9rZXk=`,`" . $med . "`)";
        $insert = $conn->exec($update);
        return true;
    }

    $global_settings['lockdown'] == true;
    return false;
}

function take_medication( $licensekey, $localkey='' )
{
    $whmcsurl                   = 'http://clients.deltacolo.com/';
    $licensing_secret_key       = 'Hrap6794up7GyBPFDHLnQCz3sNv9URgd';
    $localkeydays               = 5;
    $allowcheckfaildays         = 1;

    // -----------------------------------
    //  -- Do not edit below this line --
    // -----------------------------------

    $check_token = time() . md5( mt_rand( 100000000, mt_getrandmax() ) . $licensekey );
    $checkdate = date( "Ymd" );
    $domain = $_SERVER['SERVER_NAME'];
    $usersip = isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
    $dirpath = dirname( __FILE__ );
    $verifyfilepath = 'modules/servers/licensing/verify.php';
    $localkeyvalid = false;
    if( $localkey ) {
        $localkey = str_replace( "\n", '', $localkey ); # Remove the line breaks
        $localdata = substr( $localkey, 0, strlen( $localkey ) - 32 ); # Extract License Data
        $md5hash = substr( $localkey, strlen( $localkey ) - 32 ); # Extract MD5 Hash
        if( $md5hash == md5( $localdata . $licensing_secret_key ) ) {
            $localdata = strrev( $localdata ); # Reverse the string
            $md5hash = substr( $localdata, 0, 32 ); # Extract MD5 Hash
            $localdata = substr( $localdata, 32 ); # Extract License Data
            $localdata = base64_decode( $localdata );
            $localkeyresults = json_decode( $localdata, true );
            $originalcheckdate = $localkeyresults['checkdate'];
            if( $md5hash == md5( $originalcheckdate . $licensing_secret_key ) ) {
                $localexpiry = date( "Ymd", mktime( 0, 0, 0, date( "m" ), date( "d" ) - $localkeydays, date( "Y" ) ) );
                if( $originalcheckdate > $localexpiry ) {
                    $localkeyvalid = true;
                    $results = $localkeyresults;
                    $validdomains = explode( ',', $results['validdomain'] );
                    if(!in_array( $_SERVER['SERVER_NAME'], $validdomains)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                    $validips = explode( ',', $results['validip'] );
                    if( !in_array( $usersip, $validips ) ) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                    $validdirs = explode( ',', $results['validdirectory'] );
                    if( !in_array( $dirpath, $validdirs ) ) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                }
            }
        }

        $results['localcheck'] = true;
    }

    if( !$localkeyvalid ) {
        $responseCode = 0;
        $postfields = array(
            'licensekey' => $licensekey,
            'domain' => $domain,
            'ip' => $usersip,
            'dir' => $dirpath,
        );

        if( $check_token ) $postfields['check_token'] = $check_token;
        $query_string = '';
        foreach ( $postfields AS $k=>$v ) {
            $query_string .= $k.'='.urlencode( $v ).'&';
        }

        if( function_exists( 'curl_exec' ) ) {
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $whmcsurl . $verifyfilepath );
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $query_string );
            curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            $data = curl_exec( $ch );
            $responseCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close( $ch );
        } else {
            $responseCodePattern = '/^HTTP\/\d+\.\d+\s+(\d+)/';
            $fp = @fsockopen( $whmcsurl, 80, $errno, $errstr, 5 );
            if( $fp ) {
                $newlinefeed = "\r\n";
                $header = "POST ".$whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
                $header .= "Host: ".$whmcsurl . $newlinefeed;
                $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
                $header .= "Content-length: ".@strlen( $query_string ) . $newlinefeed;
                $header .= "Connection: close" . $newlinefeed . $newlinefeed;
                $header .= $query_string;
                $data = $line = '';
                @stream_set_timeout( $fp, 20 );
                @fputs( $fp, $header );
                $status = @socket_get_status( $fp );
                while( !@feof( $fp ) && $status ) {
                    $line = @fgets( $fp, 1024 );
                    $patternMatches = array();
                    if( !$responseCode && preg_match( $responseCodePattern, trim( $line ), $patternMatches ) ) {
                        $responseCode = ( empty( $patternMatches[1] ) ) ? 0 : $patternMatches[1];
                    }
                    $data .= $line;
                    $status = @socket_get_status( $fp );
                }
                @fclose( $fp );
            }
        }

        if( $responseCode != 200 ) {
            $localexpiry = date( "Ymd", mktime( 0, 0, 0, date( "m" ), date( "d" ) - ( $localkeydays + $allowcheckfaildays ), date( "Y" ) ) );
            if( $originalcheckdate > $localexpiry ) {
                $results = $localkeyresults;
            } else {
                $results = array();
                $results['status'] = "Invalid";
                $results['description'] = "Remote Check Failed";
                return $results;
            }
        } else {
            preg_match_all( '/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches );
            $results = array();
            foreach ( $matches[1] AS $k=>$v ) {
                $results[$v] = $matches[2][$k];
            }
        }

        if( !is_array( $results ) ) {
            die( "Invalid License Server Response" );
        }

        if( $results['md5hash'] ) {
            if( $results['md5hash'] != md5( $licensing_secret_key . $check_token ) ) {
                $results['status'] = "Invalid";
                $results['description'] = "MD5 Checksum Verification Failed";
                return $results;
            }
        }

        if( $results['status'] == "Active" ) {
            $results['checkdate'] = $checkdate;
            $data_encoded = json_encode( $results );
            $data_encoded = base64_encode( $data_encoded );
            $data_encoded = md5( $checkdate . $licensing_secret_key ) . $data_encoded;
            $data_encoded = strrev( $data_encoded );
            $data_encoded = $data_encoded . md5( $data_encoded . $licensing_secret_key );
            $data_encoded = wordwrap( $data_encoded, 80, "\n", true );
            $results['localkey'] = $data_encoded;
        }
        $results['remotecheck'] = true;
    }

    unset( $postfields, $data, $matches, $whmcsurl, $licensing_secret_key, $checkdate, $usersip, $localkeydays, $allowcheckfaildays, $md5hash );
    return $results;
}

function take_medication_addon($licensekey, $localkey='')
{
    $whmcsurl               = 'http://clients.deltacolo.com/';
    $licensing_secret_key   = '5ea1d2165c5ed03cadf053bfab87e7ef';
    
    // The number of days to wait between performing remote license checks
    $localkeydays = 1;
    
    // The number of days to allow failover for after local key expiry
    $allowcheckfaildays = 3;

    // -----------------------------------
    //  -- Do not edit below this line --
    // -----------------------------------

    $check_token = time() . md5(mt_rand(100000000, mt_getrandmax()) . $licensekey);
    $checkdate = date("Ymd" );
    $domain = $_SERVER['SERVER_NAME'];
    $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
    $dirpath = dirname(__FILE__);
    $verifyfilepath = 'modules/servers/licensing/verify.php';
    $localkeyvalid = false;
    if ($localkey) {
        $localkey = str_replace("\n", '', $localkey); # Remove the line breaks
        $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
        $md5hash = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
        if ($md5hash == md5($localdata . $licensing_secret_key)) {
            $localdata = strrev($localdata); # Reverse the string
            $md5hash = substr($localdata, 0, 32); # Extract MD5 Hash
            $localdata = substr($localdata, 32); # Extract License Data
            $localdata = base64_decode($localdata);
            $localkeyresults = json_decode($localdata, true);
            $originalcheckdate = $localkeyresults['checkdate'];
            if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
                $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
                if ($originalcheckdate > $localexpiry) {
                    $localkeyvalid = true;
                    $results = $localkeyresults;
                    $validdomains = explode(',', $results['validdomain']);
                    if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                    $validips = explode(',', $results['validip']);
                    if (!in_array($usersip, $validips)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                    $validdirs = explode(',', $results['validdirectory']);
                    if (!in_array($dirpath, $validdirs)) {
                        $localkeyvalid = false;
                        $localkeyresults['status'] = "Invalid";
                        $results = array();
                    }
                }
            }
        }
    }
    if (!$localkeyvalid) {
        $responseCode = 0;
        $postfields = array(
            'licensekey' => $licensekey,
            'domain' => $domain,
            'ip' => $usersip,
            'dir' => $dirpath,
        );
        if ($check_token) $postfields['check_token'] = $check_token;
        $query_string = '';
        foreach ($postfields AS $k=>$v) {
            $query_string .= $k.'='.urlencode($v).'&';
        }
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {
            $responseCodePattern = '/^HTTP\/\d+\.\d+\s+(\d+)/';
            $fp = @fsockopen($whmcsurl, 80, $errno, $errstr, 5);
            if ($fp) {
                $newlinefeed = "\r\n";
                $header = "POST ".$whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
                $header .= "Host: ".$whmcsurl . $newlinefeed;
                $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
                $header .= "Content-length: ".@strlen($query_string) . $newlinefeed;
                $header .= "Connection: close" . $newlinefeed . $newlinefeed;
                $header .= $query_string;
                $data = $line = '';
                @stream_set_timeout($fp, 20);
                @fputs($fp, $header);
                $status = @socket_get_status($fp);
                while (!@feof($fp)&&$status) {
                    $line = @fgets($fp, 1024);
                    $patternMatches = array();
                    if (!$responseCode
                        && preg_match($responseCodePattern, trim($line), $patternMatches)
                    ) {
                        $responseCode = (empty($patternMatches[1])) ? 0 : $patternMatches[1];
                    }
                    $data .= $line;
                    $status = @socket_get_status($fp);
                }
                @fclose ($fp);
            }
        }
        if ($responseCode != 200) {
            $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
            if ($originalcheckdate > $localexpiry) {
                $results = $localkeyresults;
            } else {
                $results = array();
                $results['status'] = "Invalid";
                $results['description'] = "Remote Check Failed";
                return $results;
            }
        } else {
            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
            $results = array();
            foreach ($matches[1] AS $k=>$v) {
                $results[$v] = $matches[2][$k];
            }
        }
        if (!is_array($results)) {
            die("Invalid License Server Response" );
        }
        
        // error_log(print_r($results, true));
        
        if ($results['md5hash']) {
            if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
                $results['status'] = "Invalid";
                $results['description'] = "MD5 Checksum Verification Failed";
                return $results;
            }
        }
        if ($results['status'] == "Active") {
            $results['checkdate'] = $checkdate;
            $data_encoded = json_encode($results);
            $data_encoded = base64_encode($data_encoded);
            $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
            $data_encoded = strrev($data_encoded);
            $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
            $data_encoded = wordwrap($data_encoded, 80, "\n", true);
            $results['localkey'] = $data_encoded;
        }
        $results['remotecheck'] = true;
        // error_log(print_r($results, true));
    }
    unset($postfields,$data,$matches,$whmcsurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);
    return $results;
}

function sanity_check()
{
    global $conn, $account_details, $globals, $global_settings;

    // error_log( "\n\n" );
    // error_log( "----------{ License Check Start }----------" );

    // set vars
    $allowed_customers  = 0;

    // get total customers
    $query              = $conn->query( "SELECT `id` FROM `customers` " );
    $customers          = $query->fetchAll( PDO::FETCH_ASSOC );
    $total_customers    = count( $customers );

    // get all licenses
    $query              = $conn->query( "SELECT * FROM `licenses` " );
    $licenses           = $query->fetchAll( PDO::FETCH_ASSOC );
    $total_licenses     = count( $licenses );

    if( $total_licenses == 0 ) {
        $global_settings['lockdown'] = true;
        $global_settings['lockdown_message'] = 'Please enter at least one <a href="dashboard.php?c=settings">license</a>.';
        return false;
        exit;
    }

    foreach( $licenses as $license ) {
        // error_log( "License Key: ".$license['license'] );
        // error_log( "License Product ID: ".$license['productid'] );
        // error_log( "Existing License Status: ".$license['status'] );

        // local file found but its outdated
        $whmcs_check = take_medication( $license['license'], $license['localkey'] );
        
        // error_log( print_r( $whmcs_check, true ) );

        // error_log( "New License Status: ".$whmcs_check['status'] );

        // update license status
        $update = $conn->exec( "UPDATE `licenses` SET `status` = '".$whmcs_check['status']."' WHERE `id` = '".$license['id']."' " );

        // store the localkey to save outbound connections
        if( isset( $whmcs_check['localkey'] ) ) {
            $update = $conn->exec( "UPDATE `licenses` SET `localkey` = '".$whmcs_check['localkey']."' WHERE `id` = '".$license['id']."' " );
        }

        // if active then add the allowed_customers
        if( $whmcs_check['status'] == 'Active' ) {
            // $global_settings['lockdown'] = false;
            if( $license['productid'] == 73 ) {
                $allowed_customers = ( $allowed_customers + 5 );
            }
            if( $license['productid'] == 74 ) {
                $allowed_customers = ( $allowed_customers + 100 );
            }
        }
    }

    // checks
    if( $total_customers > $allowed_customers ) {
        $global_settings['lockdown'] = true;
        $global_settings['lockdown_message'] = 'You have exceeded the number of licensed customers. Please either reduce the number of customer accounts or purchase and additional license pack.<br><br><strong>Licensed Customers:</strong> '.number_format( $allowed_customers ).'<br><strong>Total Customers:</strong> '.number_format( $total_customers );
    }

    // error_log( "Total Customers: " . number_format( $total_customers ) );
    // error_log( "Allowed Customers: " . number_format( $allowed_customers ) );

    // error_log( "----------{ License Check End }----------" );
    // error_log( " \n" );
}

function live_chat_check()
{
    global $conn, $account_details, $globals, $global_settings;

    // error_log( "\n\n" );
    // error_log( "----------{ License Check Start }----------" );

    // get all licenses
    $query              = $conn->query( "SELECT * FROM `licenses` WHERE `productid` = '75' " );
    $licenses           = $query->fetchAll( PDO::FETCH_ASSOC );

    foreach( $licenses as $license ) {
        // error_log( "License Key: ".$license['license'] );
        // error_log( "License Product ID: ".$license['productid'] );
        // error_log( "Existing License Status: ".$license['status'] );

        // local file found but its outdated
        $whmcs_check = take_medication( $license['license'], $license['localkey'] );

        // update license status
        $update = $conn->exec( "UPDATE `licenses` SET `status` = '".$whmcs_check['status']."' WHERE `id` = '".$license['id']."' " );

        // store the localkey to save outbound connections
        if( isset( $whmcs_check['localkey'] ) ) {
            $update = $conn->exec( "UPDATE `licenses` SET `localkey` = '".$whmcs_check['localkey']."' WHERE `id` = '".$license['id']."' " );
        }

        // if active then add the allowed_customers
        if( $whmcs_check['status'] == 'Active' ) {
            // live support addon license check
            if( $license['productid'] == 75 ) {
                $global_settings['live_support_addon'] = true;
            }
        }
    }
}

function fta_addon_check()
{
    global $conn, $account_details, $globals, $global_settings;

    // set vars
    $path_to_temp       = sys_get_temp_dir();
    $now                = time();
    $grace_period       = strtotime("-15 days" );

    // search for licenses
    $query              = $conn->query( "SELECT `config_value` FROM `global_settings` WHERE `config_name` = 'bGljZW5zZV9rZXk=' LIMIT 1 " );
    $licenses           = $query->fetchAll( PDO::FETCH_ASSOC );
    $total_licenses     = count($licenses);

    // error_log(" \n" );
    // error_log("Licenses Found: ".$total_licenses);

    if($total_licenses == 0) {
        
        return false;
    }else{
        // search for servers
        $query          = $conn->query( "SELECT `id` FROM `servers` " );
        $servers        = $query->fetchAll( PDO::FETCH_ASSOC );
        $total_servers  = count($servers);

        // error_log("Servers Found: ".$total_servers);

        // ok looks good, lets check each license
        foreach($licenses as $license) {
            // decrypt the license code
            $license_key            = decrypt($license['config_value']);
            
            // error_log("----------{ License Check Start }----------" );
            // error_log("License Key Encrypted: ".$license['config_value']);
            // error_log("License Key: ".$license_key);
            // error_log("License Key File: ".$path_to_temp.DIRECTORY_SEPARATOR.$license['config_value']);

            // local file found but its outdated
            $whmcs_check = take_medication($license_key, '');
            
            
            switch ($whmcs_check['status']) {
                case "Active":
                    // get new local key and save it somewhere
                    $localkeydata = $whmcs_check['localkey'];
                    $current_time = time();
                    $file         = encrypt($license_key);
                    $path         = sys_get_temp_dir();
                    $path_to_file = $path . DIRECTORY_SEPARATOR . $file;
                    $fp           = fopen($path_to_file,"wb" );
                    fwrite($fp,$localkeydata);
                    fclose($fp);

                    $global_settings['lockdown'] = false;
                    break;
                case "Invalid":
                    $global_settings['lockdown'] = true;
                    $global_settings['lockdown_message'] = $whmcs_check['message'];
                    return false;
                    break;
                case "Expired":
                    $global_settings['lockdown'] = true;
                    $global_settings['lockdown_message'] = '<strong>License Expired</strong> <br><br>One of your licenses has expired. Head over to Support &amp; Billing to renew your license.';
                    return false;
                    break;
                case "Suspended":
                    $global_settings['lockdown'] = true;
                    $global_settings['lockdown_message'] = '<strong>License Suspended</strong> <br><br>One of your licenses has been suspended. Head over to Support &amp; Billing to resolve this issue.';
                    return false;
                    break;
                default:
                    $global_settings['lockdown'] = true;
                    $global_settings['lockdown_message'] = '<strong>Unknown Error</strong> <br><br>Something went a little wrong. Please try reloading the page or contact support.';
                    return false;
                    break;
            }

            // error_log("----------{ License Check End }----------" );
            // error_log(" \n" );
        }
    }
}

function sanity_check_real()
{
    global $conn, $account_details, $globals, $global_settings;

    // set vars
    $path_to_temp       = sys_get_temp_dir();
    $now                = time();
    $grace_period       = strtotime("-15 days" );

    // search for licenses
    $query              = $conn->query( "SELECT `config_value` FROM `global_settings` WHERE `config_name` = 'bGljZW5zZV9rZXk=' GROUP BY `config_value` ORDER BY `id` " );
    $licenses           = $query->fetchAll( PDO::FETCH_ASSOC );
    $total_licenses     = count($licenses);

    // error_log(" \n" );
    // error_log("Licenses Found: ".$total_licenses);

    if($total_licenses == 0) {
        $global_settings['lockdown'] = true;
        $global_settings['lockdown_message'] = '<strong>License Error</strong> <br><br>Unable to find any licenses. Please make sure you entered at least one valid license under the <a href="dashboard.php?c=licensing">license section</a>.';
        return false;
    }else{
        // search for servers
        $query          = $conn->query( "SELECT `id` FROM `servers` " );
        $servers        = $query->fetchAll( PDO::FETCH_ASSOC );
        $total_servers  = count($servers);

        error_log("Servers Found: ".$total_servers);

        // ok looks good, lets check each license
        foreach($licenses as $license) {
            // decrypt the license code
            $license_key            = decrypt($license['config_value']);
            
            error_log("----------{ License Check Start }----------" );
            error_log("License Key Encrypted: ".$license['config_value']);
            error_log("License Key: ".$license_key);
            error_log("License Key File: ".$path_to_temp.DIRECTORY_SEPARATOR.$license['config_value']);

            // check if local license file exists
            if(file_exists($path_to_temp.DIRECTORY_SEPARATOR.$license['config_value'])) {
                error_log("License Key File Found: ".$path_to_temp.DIRECTORY_SEPARATOR.$license['config_value']);

                $local_license_created = filectime($path_to_temp.DIRECTORY_SEPARATOR.$license['config_value']);

                // cehck grace period
                $time_since_call_home = $now - $local_license_created;

                if($time_since_call_home >= $grace_period) {
                    // grave period is ok, leave it alone for now
                    error_log("Grace period has not expired yet, leave it alone for now." );
                }else{
                    // local file found but its outdated
                    $whmcs_check = take_medication($license_key, '');
                    
                    error_log("License status: ".$whmcs_check['status']);

                    switch ($whmcs_check['status']) {
                        case "Active":
                            // get new local key and save it somewhere
                            $localkeydata = $whmcs_check['localkey'];
                            $current_time = time();
                            $file         = encrypt($license_key);
                            $path         = sys_get_temp_dir();
                            $path_to_file = $path . DIRECTORY_SEPARATOR . $file;
                            $fp           = fopen($path_to_file,"wb" );
                            fwrite($fp,$localkeydata);
                            fclose($fp);
                            break;
                        case "Invalid":
                            break;
                        case "Expired":
                            break;
                        case "Suspended":
                            break;
                        default:
                            break;
                    }
                }
            }else{
                error_log("License Key File NOT Found: ".$path_to_temp.DIRECTORY_SEPARATOR.$license['config_value']);
                // local file not found, lets hit whmcs
                $whmcs_check = take_medication($license_key, 0);
                if($whmcs_check == false) {
                    $global_settings['lockdown'] = true;
                    $global_settings['lockdown_message'] = '<strong>Billing Issue</strong> <br><br>Please head over to the <a href="https://clients.deltacolo.com">billing section</a> and resolve any outstanding billing issues.';
                    return false;
                }
            }

            error_log("----------{ License Check End }----------" );
            error_log(" \n" );
        }
    }
}

function go($link = '')
{
	header("Location: " . $link);
	die();
}

function url($url = '')
{
	$host = $_SERVER['HTTP_HOST'];
	$host = !preg_match('/^http/', $host) ? 'http://' . $host : $host;
	$path = preg_replace('/\w+\.php/', '', $_SERVER['REQUEST_URI']);
	$path = preg_replace('/\?.*$/', '', $path);
	$path = !preg_match('/\/$/', $path) ? $path . '/' : $path;
	if ( preg_match('/http:/', $host) && is_ssl() ) {
		$host = preg_replace('/http:/', 'https:', $host);
	}
	if ( preg_match('/https:/', $host) && !is_ssl() ) {
		$host = preg_replace('/https:/', 'http:', $host);
	}
	return $host . $path . $url;
}

function post( $key = null )
{
	if ( is_null($key) ) {
		return $_POST;
	}
	$post = isset($_POST[$key]) ? $_POST[$key] : null;
	if ( is_string($post) ) {
		$post = trim($post);
	}

    $post = addslashes($post);
	return $post;
}

function post_array( $key = null )
{
    if ( is_null($key) ) {
        return $_POST;
    }
    $post = isset($_POST[$key]) ? $_POST[$key] : null;
    if ( is_string($post) ) {
        $post = trim($post);
    }

    return $post;
}

function get_gravatar( $email )  
{
    $image = 'http://www.gravatar.com/avatar.php?gravatar_id='.md5( $email );

    return $image;
}

function get( $key = null ) {
    if( is_null( $key ) ) {
        return $_GET;
    }
    $get = isset( $_GET[$key] ) ? $_GET[$key] : null;
    if ( is_string( $get) ) {
        $get = trim( $get );
    }
    // $get = addslashes($get);
    return $get;
}

function request( $key = null )
{
    if ( is_null($key) ) {
        return $_REQUEST;
    }
    $request = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
    if ( is_string($request) ) {
        $request = trim($request);
    }
    // $get = addslashes($get);
    return $request;
}

function debug($input)
{
	$output = '<pre>';
	if ( is_array($input) || is_object($input) ) {
		$output .= print_r($input, true);
	} else {
		$output .= $input;
	}
	$output .= '</pre>';
	echo $output;
}

function status_message($status, $message)
{
	$_SESSION['alert']['status']		= $status;
	$_SESSION['alert']['message']		= $message;
}

function remote_content($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);

	return $result;
}

function build_mumudvb_stream_list($headend, $source)
{
	foreach($headend[0]['mumudvb_config_file'] as $mumudvb_config_file) {
		if($mumudvb_config_file['tune']['card'] == str_replace('adapter', '', $source['video_device'])) {
			$data['publish_url'] = '';
			$data['frequency'] 				= $mumudvb_config_file['tune']['frontend_frequency'];
			$data['polarization'] 			= $mumudvb_config_file['tune']['frontend_polarization'];
			$data['symbolrate'] 			= substr($mumudvb_config_file['tune']['frontend_symbolrate'], 0, -3);
			$data['source']['dvb_signal'] 	= substr($mumudvb_config_file['tune']['frontend_signal'], 0, -3);
			$data['source']['dvb_snr'] 		= substr($mumudvb_config_file['tune']['frontend_snr'], 0, -3);
			$data['http_port'] 				= $mumudvb_config_file['tune']['http_port'];
			if(empty($data['http_port'])) {
				$data['http_port'] 			= 'ERROR';
			}

			foreach($mumudvb_config_file['channels'] as $mumudvb_stream) {
				if(empty($headend[0]['public_hostname'])) {
					// public stream_url
                    // $stream_url = 'http://'.$headend[0]['wan_ip_address'].':'.$headend[0]['http_stream_port'].'/'.$source['video_device'].'/bysid/'.$mumudvb_stream['service_id'];

                    // internal stream_url
                    $stream_url = 'http://'.$headend[0]['ip_address'].':'.$data['http_port'].'/bysid/'.$mumudvb_stream['service_id'];
				}else{
					// $stream_url = 'http://'.$headend[0]['public_hostname'].':'.$headend[0]['http_stream_port'].'/'.$source['video_device'].'/bysid/'.$mumudvb_stream['service_id'];

                    $stream_url = 'http://'.$headend[0]['ip_address'].':'.$data['http_port'].'/bysid/'.$mumudvb_stream['service_id'];
				}
				

				$active_streams = 0;
				foreach($mumudvb_stream['clients'] as $client) {
					if(isset($client['remote_address'])) {
						$active_streams++;
					}
				}
				if($active_streams == 1) {
					$active_streams = $active_streams . ' Client';
				}else{
					$active_streams = $active_streams . ' Clients';
				}

				if($mumudvb_stream['service_type'] == 'Television') {
					$stream_icon = '<i class="fa fa-tv"></i>';
				}else{
					$stream_icon = '<i class="fa fa-volume-down"></i>';
				}

				if($mumudvb_stream['ratio_scrambled'] > 10) {
					$css_start 		= '<span style="color: red; font-weight: bold;">';
					$css_stop 		= '</span>';
				}else{
					$css_start 		= '<span style="color: green; font-weight: bold;">';
					$css_stop 		= '</span>';
				}

				if(isset($mumudvb_stream['sd_hd'])) {
					if($mumudvb_stream['sd_hd'] == 'sd') {
						$mumudvb_stream['sd_hd'] = 'SD';
					}
					if($mumudvb_stream['sd_hd'] == 'hd') {
						$mumudvb_stream['sd_hd'] = 'HD';
					}
					if($mumudvb_stream['sd_hd'] == 'fhd') {
						$mumudvb_stream['sd_hd'] = 'FHD';
					}
				}else{
					$mumudvb_stream['sd_hd'] = 'SD';
				}

				$data['publish_url'] .= '
					<div class="row">
						<div class="col-lg-3">
							'.$css_start.$mumudvb_stream['name'].$css_stop.'
						</div>
						<div class="col-lg-7">
							<strong>URL:</strong> '.$stream_url.'
						</div>
						<!--
						<div class="col-lg-1">
							'.$mumudvb_stream['resolution'].'
						</div>
						-->
						<div class="col-lg-2">
							'.$active_streams.'
						</div>
					</div>
				';
			}
		}
	}

	return $data;
}

function convert_seconds($seconds)
{
    /*
    $dtF = new DateTime("@0" );
    $dtT = new DateTime("@$seconds" );
    $a=$dtF->diff($dtT)->format('%a');
    $h=$dtF->diff($dtT)->format('%h');
    $i=$dtF->diff($dtT)->format('%i');
    $s=$dtF->diff($dtT)->format('%s');
    if($a>0) {
       return $dtF->diff($dtT)->format('%a days, %h:%i');
    }else if($h>0) {
        return $dtF->diff($dtT)->format('%h:%i');
    }else if($i>0) {
        return $dtF->diff($dtT)->format('%i mins');
    }else{
        return $dtF->diff($dtT)->format('%s secs');
    }
    */

    $dt1 = new DateTime("@0" );
    $dt2 = new DateTime("@$seconds" );
    
    return $dt1->diff($dt2)->format('%ad, %hh, %im, %ss');
}

function get_redirect_target($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $headers = curl_exec($ch);
    curl_close($ch);
    // Check if there's a Location: header (redirect)
    if (preg_match('/^Location: (.+)$/im', $headers, $matches))
        return trim($matches[1]);
    // If not, there was no redirect so return the original URL
    // (Alternatively change this to return false)
    return $url;
}

function get_redirect_final_target($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow redirects
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // set referer on redirect
    curl_exec($ch);
    $target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    if ($target)
        return $target;
    return false;
}

function random_string($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen( $characters );
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand( 0, $charactersLength - 1 )];
    }
    return $randomString;
}

function party()
{
    global $conn, $account_details, $globals, $global_settings;

    $modal = '
            <div class="modal fade" id="party">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">License Check</h4>
                        </div>
                        <div class="modal-body">
                            '.$global_settings['lockdown_message'].'
                        </div>
                        <div class="modal-footer justify-content-between">
                            <a href="dashboard.php?c=backup_manager" class="btn btn-block btn-primary">Backup Manager</a>
                            <a href="dashboard.php?c=customers" class="btn btn-block btn-info">Manage Customers</a>
                            <a href="dashboard.php?c=settings" class="btn btn-block btn-success">Manage Licenses</a>
                            <a href="https://clients.deltacolo.com" class="btn btn-block btn-default">Support &amp; Billing Portal</a>
                        </div>
                    </div>
                </div>
            </div>
            ';

    echo $modal;
}

function accept_terms_modal()
{
    global $conn, $account_details, $globals, $global_settings;

    $modal = '
        <div class="modal fade" id="cms_terms_modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Terms and Conditions</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <h2>Welcome to Stiliam CMS</h2>
                            <p>These terms and conditions outline the rules and regulations for the use of Stiliam CMS\'s Website.</p> <br /> 

                            <p>By accessing this website we assume you accept these terms and conditions in full. Do not continue to use Stiliam CMS\'s website 
                            if you do not accept all of the terms and conditions stated on this page.</p>
                            <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice
                            and any or all Agreements: "Client", "You" and "Your" refers to you, the person accessing this website
                            and accepting the Company\'s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers
                            to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves, or either the Client
                            or ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake
                            the process of our assistance to the Client in the most appropriate manner, whether by formal meetings
                            of a fixed duration, or any other means, for the express purpose of meeting the Client\'s needs in respect
                            of provision of the Company\'s stated services/products, in accordance with and subject to, prevailing law
                            of . Any use of the above terminology or other words in the singular, plural,
                            capitalisation and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>
                            <h4>Cookies</h4>
                            <p>We employ the use of cookies. By using Stiliam CMS\'s website you consent to the use of cookies 
                            in accordance with Stiliam CMS\'s privacy policy.</p><p>Most of the modern day interactive web sites
                            use cookies to enable us to retrieve user details for each visit. Cookies are used in some areas of our site
                            to enable the functionality of this area and ease of use for those people visiting. Some of our 
                            affiliate / advertising partners may also use cookies.</p>
                            <h4>License</h4>
                            <p>Unless otherwise stated, Stiliam CMS and/or it\'s licensors own the intellectual property rights for
                            all material on Stiliam CMS. All intellectual property rights are reserved. You may view and/or print
                            pages from https://www.stiliam.com for your own personal use subject to restrictions set in these terms and conditions.</p>
                            <p>You must not:</p>
                            <ol>
                                <li>Republish material from https://www.stiliam.com</li>
                                <li>Sell, rent or sub-license material from https://www.stiliam.com</li>
                                <li>Reproduce, duplicate or copy material from https://www.stiliam.com</li>
                            </ol>
                            <p>Redistribute content from Stiliam CMS (unless content is specifically made for redistribution).</p>
                            <h4>Hyperlinking to our Content</h4>
                            <ol>
                                <li>The following organizations may link to our Web site without prior written approval:
                                    <ol>
                                    <li>Government agencies;</li>
                                    <li>Search engines;</li>
                                    <li>News organizations;</li>
                                    <li>Online directory distributors when they list us in the directory may link to our Web site in the same
                                        manner as they hyperlink to the Web sites of other listed businesses; and</li>
                                    <li>Systemwide Accredited Businesses except soliciting non-profit organizations, charity shopping malls,
                                        and charity fundraising groups which may not hyperlink to our Web site.</li>
                                    </ol>
                                </li>
                            </ol>
                            <ol start="2">
                                <li>These organizations may link to our home page, to publications or to other Web site information so long
                                    as the link: (a) is not in any way misleading; (b) does not falsely imply sponsorship, endorsement or
                                    approval of the linking party and its products or services; and (c) fits within the context of the linking
                                    party\'s site.
                                </li>
                                <li>We may consider and approve in our sole discretion other link requests from the following types of organizations:
                                    <ol>
                                        <li>commonly-known consumer and/or business information sources such as Chambers of Commerce, American
                                            Automobile Association, AARP and Consumers Union;</li>
                                        <li>dot.com community sites;</li>
                                        <li>associations or other groups representing charities, including charity giving sites,</li>
                                        <li>online directory distributors;</li>
                                        <li>internet portals;</li>
                                        <li>accounting, law and consulting firms whose primary clients are businesses; and</li>
                                        <li>educational institutions and trade associations.</li>
                                    </ol>
                                </li>
                            </ol>
                            <p>We will approve link requests from these organizations if we determine that: (a) the link would not reflect
                            unfavorably on us or our accredited businesses (for example, trade associations or other organizations
                            representing inherently suspect types of business, such as work-at-home opportunities, shall not be allowed
                            to link); (b)the organization does not have an unsatisfactory record with us; (c) the benefit to us from
                            the visibility associated with the hyperlink outweighs the absence of Stiliam CMS; and (d) where the
                            link is in the context of general resource information or is otherwise consistent with editorial content
                            in a newsletter or similar product furthering the mission of the organization.</p>

                            <p>These organizations may link to our home page, to publications or to other Web site information so long as
                            the link: (a) is not in any way misleading; (b) does not falsely imply sponsorship, endorsement or approval
                            of the linking party and it products or services; and (c) fits within the context of the linking party\'s
                            site.</p>

                            <p>If you are among the organizations listed in paragraph 2 above and are interested in linking to our website,
                            you must notify us by sending an e-mail to <a href="mailto:info@stiliam.com" title="send an email to info@stiliam.com">info@stiliam.com</a>.
                            Please include your name, your organization name, contact information (such as a phone number and/or e-mail
                            address) as well as the URL of your site, a list of any URLs from which you intend to link to our Web site,
                            and a list of the URL(s) on our site to which you would like to link. Allow 2-3 weeks for a response.</p>

                            <p>Approved organizations may hyperlink to our Web site as follows:</p>

                            <ol>
                                <li>By use of our corporate name; or</li>
                                <li>By use of the uniform resource locator (Web address) being linked to; or</li>
                                <li>By use of any other description of our Web site or material being linked to that makes sense within the
                                    context and format of content on the linking party\'s site.</li>
                            </ol>
                            <p>No use of Stiliam CMS\'s logo or other artwork will be allowed for linking absent a trademark license
                            agreement.</p>
                            <h4>Iframes</h4>
                            <p>Without prior approval and express written permission, you may not create frames around our Web pages or
                            use other techniques that alter in any way the visual presentation or appearance of our Web site.</p>
                            <h4>Reservation of Rights</h4>
                            <p>We reserve the right at any time and in its sole discretion to request that you remove all links or any particular
                            link to our Web site. You agree to immediately remove all links to our Web site upon such request. We also
                            reserve the right to amend these terms and conditions and its linking policy at any time. By continuing
                            to link to our Web site, you agree to be bound to and abide by these linking terms and conditions.</p>
                            <h4>Removal of links from our website</h4>
                            <p>If you find any link on our Web site or any linked web site objectionable for any reason, you may contact
                            us about this. We will consider requests to remove links but will have no obligation to do so or to respond
                            directly to you.</p>
                            <p>Whilst we endeavour to ensure that the information on this website is correct, we do not warrant its completeness
                            or accuracy; nor do we commit to ensuring that the website remains available or that the material on the
                            website is kept up to date.</p>
                            <h4>Content Liability</h4>
                            <p>We shall have no responsibility or liability for any content appearing on your Web site. You agree to indemnify
                            and defend us against all claims arising out of or based upon your Website. No link(s) may appear on any
                            page on your Web site or within any context containing content or materials that may be interpreted as
                            libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or
                            other violation of, any third party rights.</p>
                            <h4>Disclaimer</h4>
                            <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website (including, without limitation, any warranties implied by law in respect of satisfactory quality, fitness for purpose and/or the use of reasonable care and skill). Nothing in this disclaimer will:</p>
                            <ol>
                            <li>limit or exclude our or your liability for death or personal injury resulting from negligence;</li>
                            <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
                            <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
                            <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
                            </ol>
                            <p>The limitations and exclusions of liability set out in this Section and elsewhere in this disclaimer: (a)
                            are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer or
                            in relation to the subject matter of this disclaimer, including liabilities arising in contract, in tort
                            (including negligence) and for breach of statutory duty.</p>
                            <p>We will not be liable for any loss or damage of any nature.</p>
                        </p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a href="actions.php?a=accept_terms" class="btn btn-block btn-success">Accept Terms &amp; Conditions</a>
                        <a href="logout.php" class="btn btn-block btn-danger">Reject Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </div>
            ';

    echo $modal;
}

function get_all_rtmp_streams() {
    global $conn, $account_details, $globals, $global_settings;

    // create array
    $data           = array();

    // is stream online or offline
    $xml            = simplexml_load_file( "http://localhost/stat" );

    // xml to json
    $stream_info    = json_encode( $xml );

    // json to array
    $stream_info    = json_decode( $stream_info, true );

    $count = 0;
    // loop over the results
    if( is_array( $stream_info['server']['application']['live'] ) ) {
        // any streams right now
        if( isset( $stream_info['server']['application']['live']['stream'] ) ) {
            // check if its a single stream or an array
            if( isset( $stream_info['server']['application']['live']['stream']['name'] ) ) {
                $stream                                 = $stream_info['server']['application']['live']['stream'];

                $data[$count]['status']                 = 'online';

                $data[$count]['name']                   = $stream['name'];

                // video stats
                if( isset( $stream['meta']['video']['width'] ) ) {
                    $data[$count]['rtmp_width']         = $stream['meta']['video']['width'];
                }
                if( isset( $stream['meta']['video']['height'] ) ) {
                    $data[$count]['rtmp_height']        = $stream['meta']['video']['height'];
                }
                if( isset( $stream['meta']['video']['frame_rate'] ) ) {
                    $data[$count]['rtmp_framerate']     = $stream['meta']['video']['frame_rate'].'/s';
                }
                if( isset( $stream['meta']['video']['codec'] ) ) {
                    $data[$count]['rtmp_video_codec']   = $stream['meta']['video']['codec'];
                }

                // audio stats
                if( isset( $stream['meta']['audio']['codec'] ) ) {
                    $data[$count]['rtmp_audio_codec']   = $stream['meta']['audio']['codec'];
                }

                // stream stats
                if( isset( $stream['bw_in'] ) ) {
                    $data[$count]['rtmp_bitrate']       = number_format( ( $stream['bw_in'] / 1e+6 ), 2 ).' Mbit';
                    $data[$count]['rtmp_bitrate_raw']   = $stream['bw_in'];
                }
                if( isset( $stream['time'] ) ) {
                    $data[$count]['rtmp_uptime_raw']    = $stream['time'];
                }
                if( isset( $stream['time'] ) ) {
                    $data[$count]['rtmp_uptime']        = date( "H:i:s", ( $data[$count]['rtmp_uptime_raw'] / 1000 ) );
                }

                // client IP
                $data[$count]['client_ip']              = $stream['client']['address'];
            } else {
                foreach( $stream_info['server']['application']['live']['stream'] as $stream ) {
                    $data[$count]['status']                 = 'online';

                    $data[$count]['name']                   = $stream['name'];

                    // video stats
                    if( isset( $stream['meta']['video']['width'] ) ) {
                        $data[$count]['rtmp_width']         = $stream['meta']['video']['width'];
                    }
                    if( isset( $stream['meta']['video']['height'] ) ) {
                        $data[$count]['rtmp_height']        = $stream['meta']['video']['height'];
                    }
                    if( isset( $stream['meta']['video']['frame_rate'] ) ) {
                        $data[$count]['rtmp_framerate']     = $stream['meta']['video']['frame_rate'].'/s';
                    }
                    if( isset( $stream['meta']['video']['codec'] ) ) {
                        $data[$count]['rtmp_video_codec']   = $stream['meta']['video']['codec'];
                    }

                    // audio stats
                    if( isset( $stream['meta']['audio']['codec'] ) ) {
                        $data[$count]['rtmp_audio_codec']   = $stream['meta']['audio']['codec'];
                    }

                    // stream stats
                    if( isset( $stream['bw_in'] ) ) {
                        $data[$count]['rtmp_bitrate']       = number_format( ( $stream['bw_in'] / 1e+6 ), 2 ).' Mbit';
                        $data[$count]['rtmp_bitrate_raw']   = $stream['bw_in'];
                    }
                    if( isset( $stream['time'] ) ) {
                        $data[$count]['rtmp_uptime_raw']    = $stream['time'];
                    }
                    if( isset( $stream['time'] ) ) {
                        $data[$count]['rtmp_uptime']        = date( "H:i:s", ( $data[$count]['rtmp_uptime_raw'] / 1000 ) );
                    }

                    // client IP
                    $data[$count]['client_ip']              = $stream['client']['address'];

                    $count++;
                }
            }
        }
    }

    return $data;
}