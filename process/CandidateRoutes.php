<?php
require '../const.php';
require 'config.php';
require 'process.php';
require 'CandidateDao.php';
$process = new Process();
$config = new Config();
$dao = new CandidateDao($config->getConnection()); 
if( empty($_POST['canId']) && isset($_POST['student']) && isset($_POST['party']) && isset($_POST['pos']) ){
	$data = array(
		'idno' => $_POST['student'],
		'partyId' => $_POST['party'],
		'posId' => $_POST['pos']
	);
	$response = $process->addCandidate($dao, $data);
	echo json_encode($response);
} else if(isset($_GET['candidate'])){
	$data = isset($_GET['election_id']) ? $_GET['election_id'] : '';
	$response = $process->getCandidates($dao, $data);
	echo json_encode($response);
} else if(isset($_GET['checkCandidate'])){
	$data = array('elecid' => !empty($_GET['election_date_id']) ? $_GET['election_date_id'] : 0);
	$response = $process->checkCandidate($dao, $data);
	echo $response;
} else if( !empty($_POST['canId']) && isset($_POST['student']) && 
	isset($_POST['party']) && isset($_POST['pos']) ){
	$data = array(
		'canId' => $_POST['canId'],
		'idno' => $_POST['student'],
		'partyId' => $_POST['party'],
		'posId' => $_POST['pos']
	);
	$response = $process->editCandidate($dao, $data);
	echo json_encode($response);
} else if( isset($_POST['id']) ){
	$data = $_POST['id'];
	$response = $process->deleteCandidate($dao, $data);
	echo $response;
}
?>