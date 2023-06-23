<?php
require 'config.php';
require 'process.php';
require 'CourseDao.php'; 
$config = new Config();
$process = new Process($config);
$dao = new CourseDao($config->getConnection()); 
if(isset($_POST['courseInitial']) && isset($_POST['courseName']) && empty($_POST['courseId'])){
	$data = array(
		"courseInitial" => $_POST['courseInitial'], 
		"courseName" => $_POST['courseName']
	);
	$response = $process->addCourse($dao, $data);
	echo $response;
} else if(isset($_GET['courses'])){ 
	$response = $process->getCourses($dao);
	echo json_encode($response);
} else if(isset($_GET['courseId'])){
	$data = $_GET['courseId'];
	$response = $process->getCourse($dao, $data);
	echo json_encode($response);
} else if(isset($_POST['courseId']) && isset($_POST['courseInitial']) && isset($_POST['courseName'])){
	$data = array(
		'courseId'=>$_POST['courseId'], 
		"courseInitial" => $_POST['courseInitial'], 
		"courseName" => $_POST['courseName']
	);
	$response = $process->updateCourse($dao, $data);
	echo $response;
}
if(isset($_POST['deleteCourseId'])){
	$data = $_POST['deleteCourseId'];
	$response = $process->deleteCourse($dao, $data);
	echo $response;
}
?>