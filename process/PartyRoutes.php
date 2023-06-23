<?php
require '../const.php';
require 'config.php';
require 'process.php';
require 'PartyDao.php';
$process = new Process();
$config = new Config();
$dao = new PartyDao($config->getConnection());
if(empty($_POST['partyId']) && isset($_POST['partyInitial']) && isset($_POST['partyName'])){
	$data = array(
		'partyInitial' => $_POST['partyInitial'],
		'partyName' => $_POST['partyName'],
		'election_date' => $_POST['election_date']
	);
	$response = $process->addParty($dao, $data);
	echo json_encode($response);
} else if(isset($_GET['getParties'])){
	$response = $process->getParties($dao);
	echo json_encode($response);
} else if ( !empty($_POST['partyId']) && isset($_POST['partyInitial']) && isset($_POST['partyName']) ) {
	$data = array(
		'id' => $_POST['partyId'],
		'partyInitial' => $_POST['partyInitial'],
		'partyName' => $_POST['partyName'],
		'election_date' => $_POST['election_date']
	);
	$response = $process->updateParty($dao, $data);
	echo json_encode($response);
} else if(isset($_POST['id'])){
	$data = $_POST['id'];
	$response = $process->deleteParty($dao, $data);
	echo $response;
} else if(isset($_GET['election_date_id'])) {
	$sql = "select p.*,ed.* from tblparty as p
				INNER JOIN tbl_election_date as ed ON p.party_election_date_id = ed.election_date_id where p.party_election_date_id = ".($_GET['election_date_id'])."";
		$query = mysqli_query($config->getConnection(), $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		echo json_encode($response);
}
?>