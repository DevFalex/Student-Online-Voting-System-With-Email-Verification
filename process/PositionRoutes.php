<?php
require 'config.php';
require 'process.php';
require 'PositionDao.php';
$process = new Process();
$config = new Config();
$dao = new PositionDao($config->getConnection()); 
if(empty($_POST['positionId']) && isset($_POST['positionName']) && isset($_POST['sortOrder']) && isset($_POST['voteAllowed']) && isset($_POST['allowPerParty'])){
	$data = array(
		'positionName' => $_POST['positionName'],
		'sortOrder' => $_POST['sortOrder'],
		'voteAllowed' => $_POST['voteAllowed'],
		'allowPerParty' => $_POST['allowPerParty']
	);
	$response = $process->addPosition($dao, $data);
	echo json_encode($response);
} else if(isset($_GET['pos'])){
	$response = $process->getPositions($dao);
	echo json_encode($response);
} else if ( !empty($_POST['positionId']) && isset($_POST['positionName']) && isset($_POST['sortOrder']) && isset($_POST['voteAllowed']) && isset($_POST['allowPerParty']) ) {
	$data = array(
		'id' => $_POST['positionId'],
		'positionName' => $_POST['positionName'],
		'sortOrder' => $_POST['sortOrder'],
		'voteAllowed' => $_POST['voteAllowed'],
		'allowPerParty' => $_POST['allowPerParty']
	);
	$response = $process->updatePosition($dao, $data);
	echo json_encode($response);
} else if(isset($_POST['id'])){
	$data = $_POST['id'];
	$response = $process->deletePosition($dao, $data);
	echo $response;
}
?>