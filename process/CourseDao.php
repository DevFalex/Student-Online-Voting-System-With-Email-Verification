<?php
class CourseDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function addCourse($data){
		$sql = "insert into tblcourse (courseinitial, coursename) values (?,?)";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ss', $data['courseInitial'], $data['courseName']);
		if($query->execute()){
			$response = "Success";
		} else {
			$response = "Failed";
		}
		return $response;
	}
	public function getCourses(){
		$sql = "select * from tblcourse";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		return $response;
	}
	public function getCourse($data){
		$sql = "select * from tblcourse where id=?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('i', $data);
		$query->execute();
		$query->bind_result($id, $courseInitial, $courseName);
		$query->fetch();
		$response = array('id'=>$id, 'courseinitial'=>$courseInitial, 'coursename'=>$courseName);
		return $response;
	}
	public function updateCourse($data){
		$sql = "update tblcourse set courseinitial=?, coursename=? where id=?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ssi', $data['courseInitial'], $data['courseName'], $data['courseId']);
		if($query->execute()){
			$response = "Success";
		} else {
			$response = "Failed";
		}
		return $response;
	}
	public function deleteCourse($data){
		$sql = "delete from tblcourse where id=?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('i', $data);
		if($query->execute()){
			$response = "Success";
		} else {
			$response = "Failed";
		}
		return $response;
	}
}
?>