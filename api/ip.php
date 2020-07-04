<?php

# include('/var/www/html/inc/global_vars.php');
include('/var/www/html/inc/functions.php');

header("Content-Type:application/json; charset=utf-8");

$data['ip'] = $_SERVER['REMOTE_ADDR'];

json_output( $data );