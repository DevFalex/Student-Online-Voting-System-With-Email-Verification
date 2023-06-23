<?php
class YearLevelDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	public function addYearLevel($data){
		$sql = "insert into tblyearlevel (yearlevelinitial, yearlevelname) values (?, ?)";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ss', $data['yearLevelInitial'], $data['yearLevelName']);
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}
	}
	public function updateYearLevel($data){
		$sql = "update tblyearlevel set yearlevelinitial=?, yearlevelname=? where id=?";
		$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
		$query->bind_param('ssi', $data['yearLevelInitial'], $data['yearLevelName'], $data['yearLevelId']);
		if($query->execute()){
			echo "Success";
		} else {
			echo "Failed";
		}
	}
	public function getYearLevels(){
		$sql = "select y.*, count(s.yearlevelid) as cnt from tblyearlevel as y left join tblstudent as s on s.yearlevelid = y.id GROUP by y.id";
		$query = mysqli_query($this->conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		return $response;
	}
	public function getYearLevel($data){
		$sql = "select * from tblyearlevel where id=?";
		$query = $this->conn->prepare($sql);
		$query->bind_param('i', $data);
		$query->execute();
		$query->bind_result($yrId, $yrIni, $yrName);
		$query->fetch();
		$response = array(
			'yearlevelId' => $yrId,
			'yearLevelInitial' => $yrIni,
			'yearLevelName' => $yrName
		);
		return $response;
	}
	public function deleteYearLevel($data){
		$sql = "delete from tblyearlevel where id=?";
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