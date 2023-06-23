<?php
class AdminDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function fetchAllAdmin(){
		$sql = "select * from tblAdmin";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		return $response;
	}
	public function addAdmin($data){
		$sql = "insert into tbladmin values (?,?,?,?,?,?)";
		$query = $this->conn->prepare($sql);
		$pass =hash('SHA256',$data['password']);
		$query->bind_param('isssss', $id, $data['fname'], $data['mname'], $data['lname'], $data['username'], $pass);
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}
	}
	public function deleteAdmin($data){
		$sql = "delete from tbladmin where admin_id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param(
			'i',
			$data
		);
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}
	}
	public function updateAdmin($data){
		$sql = "update tbladmin set fname=?, mname=?, lname=? where admin_id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('sssi', $data['fname'], $data['mname'], $data['lname'], $data['id']);
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}
	}
}
?> 