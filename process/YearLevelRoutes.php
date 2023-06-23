<?php
require '../const.php';
require 'config.php';
require 'process.php';
require 'YearLevelDao.php';
$process = new Process();
$config = new Config();
$dao = new YearLevelDao($config->getConnection());
if(empty($_POST['yearLevelId']) && isset($_POST['yearLevelInitial']) && isset($_POST['yearLevelName'])){
	$data = array(
		'yearLevelid' => $_POST['yearLevelId'],
		'yearLevelInitial' => $_POST['yearLevelInitial'],
		'yearLevelName' => $_POST['yearLevelName']
	);
	$response = $process->addYearLevel($dao, $data);
	echo $response;
} else if(isset($_GET['yearlvl'])){
	$response = $process->getYearLevels($dao);
	echo json_encode($response);
} else if(isset($_GET['yearLvlId'])){
	$data = $_GET['yearLvlId'];
	$response = $process->getYearLevel($dao, $data);
	echo json_encode($response);
} else if(!empty($_POST['yearLevelId'])){
	$data = array(
		'yearLevelId' => $_POST['yearLevelId'],
		'yearLevelInitial' => $_POST['yearLevelInitial'],
		'yearLevelName' => $_POST['yearLevelName']
	);
	$response = $process->updateYearLevel($dao, $data);
	echo $response;
} else if(isset($_GET['delYrLvl'])){
	$data = $_GET['delYrLvl'];
	$response = $process->deleteYearLevel($dao, $data);
	echo $response;
}
?>