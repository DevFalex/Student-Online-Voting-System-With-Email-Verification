<?php
require '../const.php';
require '../session.php';
require 'config.php';
require 'process.php';
require 'VotesDao.php';
$process = new Process();
$config = new Config();
$dao = new VotesDao($config->getConnection());  
if(isset($_POST['candidate'])){
	$data = $_POST['candidate'];
	$data += array('id'=> $_POST['id']); 
	$response = $process->vote($dao, $data);
	echo $response; 
} else if(isset($_POST['voteCode'])){
	$data = $_POST['voteCode'];
	$response = $process->voterLogin($dao, $data);
	echo json_encode($response);
} else if(isset($_GET['count'])){
	$data = $_GET['election_date'];
	$response = $process->getCounts($dao, $data);
	echo json_encode($response);
}
?>