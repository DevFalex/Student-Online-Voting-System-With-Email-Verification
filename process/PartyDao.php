<?php
class PartyDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function addParty($data){
		$sql = "insert into tblparty (partyInitial, partyName, party_election_date_id) values (?, ?, ?)";
		$query = $this->conn->prepare($sql);
		$query->bind_param('sss', $data['partyInitial'], $data['partyName'], $data['election_date']);
		if($query->execute()){
			$response = array(
				'status' => 'success',
				'message' => 'Party has been added'
			);
		} else {
			$response = array(
				'status' => 'failed',
				'message' => 'Failed to add party'
			);
		}
		return $response;
	}
	public function getParties(){
		$sql = "select p.*,ed.* from tblparty as p
				INNER JOIN tbl_election_date as ed ON p.party_election_date_id = ed.election_date_id";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		return $response;
	}
	public function updateParty($data){
		$sql = "update tblparty set partyinitial=?, partyname=?, party_election_date_id = ? where id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('sssi', $data['partyInitial'], $data['partyName'], $data['election_date'], $data['id']);
		if($query->execute()){
			$response = array(
				'status' => 'success',
				'message' => 'Party has been updated'
			);
		} else {
			$response = array(
				'status' => 'failed',
				'message' => 'Failed to update party'
			);
		}
		return $response;
	}
	public function deleteParty($data){
		$sql = "delete from tblparty where id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('i', $data);
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}
	}
}
?>