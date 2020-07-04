<?php

function decrypt_local( $string, $key=5 )
{
    $result = '';
    $string = base64_decode( $string );
    for($i=0,$k=strlen( $string); $i< $k ; $i++ ) {
        $char = substr( $string, $i, 1 );
        $keychar = substr( $key, ( $i % strlen( $key ) )-1, 1 );
        $char = chr( ord( $char)-ord( $keychar ) );
        $result.=$char;
    }
    return $result;
}

$config['version']								= '1';

// site vars
$site['url']									= 'http://127.0.0.1:80/';
$site['title']									= 'Stiliam CMS';
$site['copyright']								= 'Written by Stiliam.com.';

// logo name vars
$site['name_long']								= 'Stiliam<b>CMS</b>';
$site['name_short']								= '<b>CMS</b>';

// get settings table contents
$query = $conn->query("SELECT `config_name`,`config_value` FROM `global_settings` ");
$global_settings_temp = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($global_settings_temp as $bits){
	$global_settings[$bits['config_name']] 		= $bits['config_value'];
}

if(empty($global_settings['cms_domain_name'])){
	$site['url'] 								= "http://".$global_settings['cms_ip'].":".$global_settings['cms_port'];
	$global_settings['cms_access_url'] 			= $global_settings['cms_ip'].":".$global_settings['cms_port'];
	$global_settings['cms_access_url_raw'] 		= $global_settings['cms_ip'];
}else{
	$site['url'] 								= "http://".$global_settings['cms_domain_name'].":".$global_settings['cms_port'];
	$global_settings['cms_access_url'] 			= $global_settings['cms_domain_name'].":".$global_settings['cms_port'];
	$global_settings['cms_access_url_raw'] 		= $global_settings['cms_domain_name'];
}

if($global_settings['cms_name'] != 'Stiliam CMS'){
	$site['title'] 								= $global_settings['cms_name'];
	$site['name_long'] 							= $global_settings['cms_name'];
	$site['name_short'] 						= $global_settings['cms_name'];
}

$global_settings['lockdown'] 					= false;
$global_settings['lockdown_message']			= '';

$global_settings['live_support_addon'] 			= false;
