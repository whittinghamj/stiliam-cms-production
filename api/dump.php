<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL); 

// example using curl
// curl -XPOST --data @data.txt http://dashboard.miningcontrolpanel.com/api/dump.php

$data['version']			= '1.1.9';

include('../inc/db.php');
include('../inc/sessions.php');
$sess = new SessionManager();
session_start();

include('../inc/global_vars.php');
include('../inc/functions.php');

header("Content-Type:application/json; charset=utf-8");

if(empty($_POST)){
	die("no post data available");
}

$data = $_POST['STATUS'];

$data = explode(',', $data);

$data = serialize($data);

$input = mysql_query("INSERT IGNORE INTO `dump` 
		(`data`)
		VALUE
		('".$data."')") or die(mysql_error());
	
$data['status']				= 'success';
$data['message']			= 'data has been dumped';
json_output($data);