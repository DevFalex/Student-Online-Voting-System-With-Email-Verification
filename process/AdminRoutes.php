<?php
require 'config.php';
require 'process.php';
require 'AdminDao.php';
$process = new Process();
$config = new Config();
$dao = new AdminDao($config->getConnection());   
if(isset($_POST['request'])) {
	if($_POST['request']==='fetchAll'){
		$response = $process->fetchAllAdmin($dao);
		echo json_encode($response);
	} else if( $_POST['request'] ==='add' ) {
		$data = array(
			'fname' => ucwords($_POST['firstName']),
			'lname' => ucwords($_POST['lastName']),
			'mname' => ucwords($_POST['middleName']),
			'username' => $_POST['userName'],
			'password' => $_POST['password']
		);
		$response = $process->addAdmin($dao, $data);
		echo $response;
	} else if( $_POST['request'] ==='update' ) { 
		$data = array(
			'fname' => ucwords($_POST['firstName']),
			'lname' => ucwords($_POST['lastName']),
			'mname' => ucwords($_POST['middleName']),
			'id' => $_POST['admin_id']
		);
		$response = $process->updateAdmin($dao, $data);
		echo $response;
	}
}else{
	if(isset($_POST['delete'])){ 
		$data = $_POST['delete'];
		$response = $process->deleteAdmin($dao, $data);
		echo $response;
	}
}

?>