<?php
class PositionDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function addPosition($data){
		$sql = "insert into tblcandidateposition (positionname, sortorder, votesallowed, allowperparty) values (?, ?, ?, ?)";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('siii', $data['positionName'], $data['sortOrder'], $data['voteAllowed'], $data['allowPerParty']);
		if($query->execute()){
			$response = array(
				'status' => 'success',
				'message' => 'Position Added'
			);
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Failed to Add position'
			);
		}
		return $response;
	}
	public function getPositions(){
		$sql = "select * from tblcandidateposition order by sortorder";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		return $response;
	}
	public function updatePosition($data){
		$sql = "update tblcandidateposition set positionname=?, sortorder=?, votesallowed=?, allowperparty=? where id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('siiii', $data['positionName'], $data['sortOrder'], $data['voteAllowed'], $data['allowPerParty'], $data['id']);
		if($query->execute()){
			$response = array(
				'status' => 'success',
				'message' => 'Position Updated'
			);
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Failed to Update Position'
			);
		}
		return $response;
	}
	public function deletePosition($data){
		$sql = "delete from tblcandidateposition where id=?";
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