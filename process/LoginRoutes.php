<?php
require_once "../session.php";
require 'config.php';
require 'process.php';
require 'LoginDao.php';
$process = new Process();
$config = new Config();
$dao = new LoginDao($config->getConnection());
if(isset($_POST['username'])){
	$data = array(
		'userName' => $_POST['username'],
		'password' => hash('sha256', $_POST['password'])
	);
	$response = $process->login($dao, $data);
	echo $response;
}
?>