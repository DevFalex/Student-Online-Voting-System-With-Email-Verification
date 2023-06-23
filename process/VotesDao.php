<?php
class VotesDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function vote($data){
		$count = 0;   
		$id = $data['id'];
		unset($data['id']);
		$data = array_values($data);
		$sql = "insert into tblvotes (candidateid) values (?)"; 
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn)); 
		foreach($data as $key => $value){
			$val = (int)$value;
			$query->bind_param('i', $val);
			if($query->execute()){
				$count++;
			} else {
				echo $query->error;
			} 
		} 
		mysqli_query($this->conn, "update tblstudent set votestatus=".VOTED." where id=".$id);
		if($count == count($data)){
			echo "Success";
			unset($_SESSION['status']); 
		} else {
			echo "Failed";
		}
	}
	public function voterLogin($data){
		$sql = "select idno,id,votestatus from tblstudent where votingcode=? or idno = ?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ss', $data, $data);
		$query->execute();
		$query->bind_result($idno, $id, $votingstatus);
		$query->store_result();
		$query->fetch(); 
		if($query->num_rows() >= 1) {
			if($votingstatus == 0){
				$response = array(
					"status" => "success",
					"data"	=> $id,
					"message" => "You have successfully logged in! You may now vote"
				);
				$_SESSION['status'] = 'success';
				$_SESSION['id'] = $id;
			} else {
				$response = array(
					"status" => "failed",
					"data"	=> null,
					"message" => "You have already voted!"
				);
			}
		} else {
			$response = array(
				"status" => "failed",
				"data" => null,
				"message" => "Voting code does not exists!"
			);
		}
		return $response;
	}
	public function getCounts($data){
		$data = mysqli_real_escape_string($this->conn, $data);
		$sql = "select c.id as candidateid, s.id as studentid, s.image, s.firstname, s.middlename, s.lastname, pos.positionname, part.partyname
			from tblparty as part
			inner join tblcandidate as c on  part.id = c.partyid
			inner join tblstudent as s on s.id = c.studentid
			inner join tblcandidateposition as pos on c.candidatepositionid = pos.id
			where party_election_date_id = '".$data."'";
		$query = mysqli_query($this->conn, $sql);
		$candidates = mysqli_fetch_all($query, MYSQLI_ASSOC);
		$query->close(); 
		$sql = "select count(*) as individualVote from tblvotes where candidateid = ?";
		$query = $this->conn->prepare($sql); 
		foreach($candidates as $key => $candidate){
			$query->bind_param('i', $candidate['candidateid']) or die(mysqli_error($this->conn));
			$query->execute();
			$query->bind_result($count);
			while($query->fetch()){
				$candidates[$key]['vote_count'] = $count;
			} 
		}
		$query->close();
		$sql = "select can.candidatepositionid, can.id as canId , pos.id as posId , pos.positionname,pos.allowperparty
		from tblparty as p LEFT JOIN tblcandidate as can ON p.id = can.partyid 
		right join tblcandidateposition as pos on can.candidatepositionid = pos.id where p.party_election_date_id = '".$data."'
		order by pos.sortorder";
		$query = mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
		$candidatesposition = mysqli_fetch_all($query, MYSQLI_ASSOC); 
		$query->close();
		$sql = "select count(*) as positionVote, can.candidatepositionid, can.studentid from tblvotes as v
				left join tblcandidate as can on can.id = v.candidateid
				left join tblcandidateposition as pos on pos.id = can.candidatepositionid
				left join tblparty as p on can.partyid = p.id
				where v.candidateid = ? and pos.id = ? and p.party_election_date_id = ?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$positionVotes = array();
		foreach($candidatesposition as $key => $value){
			$query->bind_param  ('iii', $value['canId'], $value['posId'], $data);
			$query->execute();
 			$query->bind_result($count, $canId, $studentId);
			while($query->fetch()){ 
				$positionVotes[] =array("positionname" => $value['positionname'],"student_id" => $studentId, "pos_count" => $count, "allowPerParty" => $value['allowperparty']);  
			}
		} 
		$sql = "select count(*) student_count ,
				(select count(*) from tblvotestatus as v LEFT JOIN tblstudent as s ON v.vote_status_studentid = s.id
				where vote_status_election_date_id = '".$data."') as voted,
				(select count(*) from tblstudent as s where s.id not in (select v.vote_status_studentid from tblvotestatus as v
				where vote_status_election_date_id = '".$data."')) as not_voted
				from tblstudent";
		/* $sql = "select count(*) as not_voted, (select count(*) from tblstudent as s INNER JOIN
				tblvotestatus as vs ON s.id = vs.vote_status_studentid ) as voted, 
				(select count(*) from tblstudent) as student_count  from tblstudent where id not in (select v.vote_status_studentid from tblvotestatus as v where v)";*/
		$query = mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
		$vote_status = mysqli_fetch_all($query, MYSQLI_ASSOC);
		$query->close(); 
		$response = array_merge(array("vote_status"=>$vote_status), array("individual_count"=>$candidates), array("position_count" => $positionVotes));
		return $response;
	}
}
?>