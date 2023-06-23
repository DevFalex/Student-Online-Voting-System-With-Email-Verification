<?php
class LoginDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function login($data){
		$sql = "select * from tbladmin where username=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('s', $data['userName']);
		$query->execute();
		$query->bind_result($id, $fname, $mname, $lname, $username, $password);
		$query->store_result();
		$query->fetch();
		if($query->num_rows() > 0){
			if($data['password'] === $password){
				$_SESSION['data'] = array(
					'id' => $id,
					'fname' => $fname,
					'mname' => $mname,
					'lname' => $lname,
					'user' => $username,
					'password' => $password
				);
				echo "admin.php";
			} else {
				echo "error";
			}
		} else {
			echo "Username";
		}
	}
}
?>