<?php
class CandidateDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function addCandidate($data){
		$sql = "select count(candidatepositionid) as cnt from tblcandidate where partyid = ? and candidatepositionid = ?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ii', $data['partyId'], $data['posId']);
		$query->execute();
		$query->bind_result($cnt);
		$query->fetch();
		$query->close();
		$sql = "select allowperparty from tblcandidateposition where id=?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('i', $data['posId']);
		$query->execute();
		$query->bind_result($allowed);
		$query->fetch();
		$query->close(); 
		if($allowed > $cnt){ 
			$sql = "insert into tblcandidate (studentid, partyid, candidatepositionid) values (?, ?, ?)";
			$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
			$query->bind_param('iii', $data['idno'], $data['partyId'], $data['posId']);  
			if($query->execute()){
				$response = array(
					"status" => "success",
					"message" => "Candidate Added"
				);
			} else {
				$response = array(
					"status" => "error",
					"message" => "Failed to Add Candidate"
				);
			}
		} else{
			$response = array(
				"status" => "error",
				"message" => "Number of participants in this positions exceeds the number allowed"
			);
		}
		return $response; 
	}
	public function getCandidates($data){
		$sql = '';
		if(empty($data)) {
			$sql = "select can.id, st.id as s_id, st.idno, st.lastname, st.image, st.firstname, st.middlename, p.partyname, p.id as p_id, c.id as c_id, c.positionname, c.votesallowed,
			ed.* 
			from tblcandidate as
			can inner join tblstudent as st on st.id=can.studentid inner 
			join tblparty as p on p.id = can.partyid inner join tblcandidateposition as c on c.id = can.candidatepositionid
			inner join tbl_election_date as ed ON p.party_election_date_id = ed.election_date_id 
			order by ed.election_date desc, c.sortorder";
		} else {
			$sql = "select can.id, st.id as s_id, st.idno,
					st.lastname, st.image, st.firstname,
					st.middlename, p.partyname, p.id as p_id, c.id as c_id, c.positionname, c.votesallowed,
					ed.* 
					from tblparty as p INNER JOIN tblcandidate as
					can ON p.id = can.partyid inner join tblstudent as st on st.id=can.studentid
					inner join tblcandidateposition as c on c.id = can.candidatepositionid
					inner join tbl_election_date as ed ON p.party_election_date_id = ed.election_date_id 
					where p.party_election_date_id =  ".mysqli_real_escape_string($this->conn, $data)."
					order by ed.election_date desc, c.sortorder";
		}
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC); 
		return $response;
	}
	public function checkCandidate($data){
		$sql = "select s.* from tblstudent as s where s.id not in
		(select c.studentid from tblparty as p INNER JOIN tblcandidate as c ON
		p.id = c.partyid where p.party_election_date_id=".$data['elecid'].")";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC); 
		return json_encode($response);
	}
	public function editCandidate($data){
		$sql = "select count(candidatepositionid) as cnt from tblcandidate where partyid = ? and candidatepositionid = ?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ii', $data['partyId'], $data['posId']);
		$query->execute();
		$query->bind_result($cnt);
		$query->fetch();
		$query->close();
		$sql = "select allowperparty from tblcandidateposition where id=?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('i', $data['posId']);
		$query->execute();
		$query->bind_result($allowed);
		$query->fetch();
		$query->close(); 
		if($allowed > $cnt){ 
			$sql = "update tblcandidate set studentid=?, partyid=?, candidatepositionid=? where id=?";
			$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
			$query->bind_param('iiii', $data['idno'], $data['partyId'], $data['posId'], $data['canId']);  
			if($query->execute()){
				$response = array(
					"status" => "success",
					"message" => "Candidate Updated"
				);
			} else {
				$response = array(
					"status" => "error",
					"message" => "Failed to Update Candidate"
				); 
			}
		} else{
			$response = array(
				"status" => "error",
				"message" => "Number of participants in this positions exceeds the number allowed"
			);
		}
		return $response;
	}
	public function deleteCandidate($data){
		$sql = "DELETE from tblcandidate WHERE id=?";
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