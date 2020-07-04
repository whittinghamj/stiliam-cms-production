<?php

$host			= '127.0.0.1';
$db 			= 'cms';
$username 		= 'stiliam';
$password 		= 'stiliam1984';

$dsn			= "mysql:host=$host;dbname=$db";

try{
	$conn = new PDO($dsn, $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
	echo $e->getMessage();
}