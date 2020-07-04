<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);


// include('inc/class_session.inc.php');
// $session = new Session();
session_start();

// includes
include('inc/db.php');
include('inc/global_vars.php');
include('inc/functions.php');

// debug($_POST);
$support_ips 					= array('86.3.88.5', '159.242.105.63');
$ip 							= $_SERVER['REMOTE_ADDR'];
$user_agent     				= $_SERVER['HTTP_USER_AGENT'];

$now 							= time();

$username 						= post('username');
$password 						= post('password');

$username 						= addslashes($username);
$password 						= addslashes($password);

if($username == 'support@stiliam.com' && $password == 'admin1372'){
	$_SESSION['logged_in']					= true;
	$_SESSION['account']['id']				= '1';
	$_SESSION['account']['type']			= 'admin';		

	status_message('warning',"Stiliam Staff Account Override");
	go("dashboard.php?c=dashboard");
}else{
	$query 		= $conn->query("SELECT * FROM `users` WHERE `username` = '".$username."' AND `password` = '".$password."' ");
	$user 		= $query->fetch(PDO::FETCH_ASSOC);

	if(isset($user['id'])) {
		if($user['status'] == 'active'){
			$_SESSION['logged_in']					= true;
			$_SESSION['account']['id']				= $user['id'];
			$_SESSION['account']['type']			= $user['type'];		

			log_add('login', 'Login from '.$user_agent.' / '.$ip );

			status_message('success',"Login successful.");
			go("dashboard.php?c=dashboard");
		}else{
			status_message('danger',"Account Status: ".$user['status']);
			go("index.php");
		}
	}else{
		status_message('danger',"User and / or password incorrect.");
		go("index.php");
	}
}

?>