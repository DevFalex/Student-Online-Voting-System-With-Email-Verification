<?php 
require '../const.php';
require 'config.php';
require 'process.php';
require 'RegisterDao.php';
	$process = new Process();
	$config = new Config();
	$dao = new RegisterDao($config->getConnection());
if(isset($_POST['studentID']) && isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['middleName']) && isset($_POST['email']) && isset($_POST['course']) && empty($_POST['id'])){
	$data = array(
			
			'studentID' => $_POST['studentID'],
			'lastName' => $_POST['lastName'],
			'firstName' => $_POST['firstName'],
			'middleName' => $_POST['middleName'],
			'email' => $_POST['email'],
			'course' => $_POST['course'],
			'yearlevel' => $_POST['yearlevel'],
			'status' => NOT_VOTED
		);
	$response = $process->addRegStudent($dao, $data);
	echo $response;
}
else{
	echo "failed";
}
?>