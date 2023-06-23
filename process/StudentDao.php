<?php
class StudentDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function addStudent($data){
		$prefix = md5(date('Y-m-d h:i:s a'));
        $uni = uniqid(substr($prefix, 4, 6),true);
        $suffix = str_shuffle('abcdefghijklmnopqrstuvwxyz');
        $votingcode = strtoupper(substr($suffix , 0 ,3).'-'. substr(str_shuffle(strrev(str_replace(".","",$uni))),0 ,6));
		$sql = "insert into tblstudent (idno, lastname, firstname, middlename, courseid, image, votingcode, votestatus, yearlevelid) value (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('sssssssii',$data['studentID'], 
			$data['lastName'], $data['firstName'], $data['middleName'],
			$data['course'], $data['image_name'], $votingcode, $data['status'], $data['yearlevel']
		);
		if($query->execute()){
			move_uploaded_file($data['image_tmp'], ROOT_DIR.'/imgs/'.$data['image_name']);
			echo "Success";
		} else {
			echo $query->error;
		}
	}
	public function getStudents($data){
		$sql = "select if(1 > 1,0,0) as vote_status_, s.*, c.coursename, y.yearlevelname from tblstudent as s 
				inner join tblcourse as c on c.id=s.courseid
				inner join tblyearlevel as y on y.id = s.yearlevelid";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		$newResponse = array();
		foreach($response as $row) {
			$sql='select count(*) as vote_statusz from tblvotestatus
					where 
					vote_status_studentid='.$row['id'].' and
					vote_status_election_date_id ="'.$_GET['election_date'].'"';
			$query = mysqli_query($this->conn, $sql);
			$responsez = mysqli_fetch_all($query, MYSQLI_ASSOC);
			$row['vote_status_'] = $responsez[0]['vote_statusz'];
			$newResponse[count($newResponse)] =  $row;
		}
		return $newResponse;
	}
	public function getStudent($data){
		$sql = "select id, idno, lastname, firstname, middlename, courseid, image from tblstudent where id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('i', $data);
		$query->execute();
		$query->bind_result($id, $idno, $lastName, $firstName, $middleName, $courseId, $image);
		$query->fetch();
		$response = array(
			'id' => $id,
			'idno' => $idno,
			'lastname' => $lastName,
			'firstname' => $firstName,
			'middlename' => $middleName,
			'courseid' => $courseId,
			'image' => $image
		);
		return $response;
	}
	public function updateStudent($data){ 
		if(isset($data['image_tmp']) && !empty($data['image_tmp'])){
			$sql = "update tblstudent set idno=?, lastname=?, firstname=?, middlename=?, courseid=?, image=?, yearlevelid=? where id=?";
			$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
			$query->bind_param('ssssssii', $data['studentID'], $data['lastName'], $data['firstName'],
				$data['middleName'], $data['course'], $data['image_name'], $data['yearlevel'], $data['id']
			);
			move_uploaded_file($data['image_tmp'], ROOT_DIR.'/imgs/'.$data['image_name']);
		} else {
			$sql = "update tblstudent set idno=?, lastname=?, firstname=?, middlename=?, courseid=?, yearlevelid=? where id=?";
			$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
			$query->bind_param('sssssii', $data['studentID'], $data['lastName'], $data['firstName'],
				$data['middleName'], $data['course'], $data['yearlevel'], $data['id']
			);
		}
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}

	}
	public function deleteStudent($data){
		$sql = "delete from tblstudent where id=?";
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