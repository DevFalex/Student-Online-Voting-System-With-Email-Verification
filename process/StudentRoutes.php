<?php
require '../const.php';
require 'config.php';
require 'process.php';
require 'StudentDao.php';
	$process = new Process();
	$config = new Config();
	$dao = new StudentDao($config->getConnection()); 
if(isset($_FILES['image']['tmp_name']) && isset($_POST['studentID']) && isset($_POST['lastName'])
	&& isset($_POST['firstName']) && isset($_POST['middleName']) && isset($_POST['course']) && empty($_POST['id'])){
	$data = array(
			'image_tmp' => $_FILES['image']['tmp_name'],
			'image_name' => $_FILES['image']['name'],
			'studentID' => $_POST['studentID'],
			'lastName' => $_POST['lastName'],
			'firstName' => $_POST['firstName'],
			'middleName' => $_POST['middleName'],
			'course' => $_POST['course'],
			'yearlevel' => $_POST['yearlevel'],
			'status' => NOT_VOTED
		);
	$response = $process->addStudent($dao, $data);
	echo $response;
} else if(isset($_POST['id'])){
	$data = array(
			'id' => $_POST['id'],
			'studentID' => $_POST['studentID'],
			'lastName' => $_POST['lastName'],
			'firstName' => $_POST['firstName'],
			'middleName' => $_POST['middleName'],
			'course' => $_POST['course'],
			'yearlevel' => $_POST['yearlevel']
		);
	if($_FILES['image']['tmp_name']){
		$data += array(
			'image_tmp' => $_FILES['image']['tmp_name'],
			'image_name' => $_FILES['image']['name']
		);
	}
	$response = $process->updateStudent($dao, $data);
	echo $response;
} else if(isset($_GET['student'])){
	$count=0;
	$response = $process->getStudents($dao, $_GET['election_date']);
	foreach($response as $res){
		if($res['votestatus']==0){
			$response[$count]['votestatus'] = "Has not yet voted";
		} else {
			$response[$count]['votestatus'] = "Done voting";
		}
		$count++;
	}
	echo json_encode($response);
} else if(isset($_GET['id'])){
	$data = $_GET['id'];
	$response = $process->getStudent($dao, $data);
	echo json_encode($response);
} else if(isset($_POST['deleteId'])){
	$data = $_POST['deleteId'];
	$response = $process->deleteStudent($dao, $data);
}
?>