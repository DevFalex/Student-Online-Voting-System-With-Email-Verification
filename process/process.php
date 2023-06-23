<?php
class Process{
	public function __contruct(){

	}
	/** Student Functions **/
	public function addStudent($dao, $data){
		$response = $dao->addStudent($data);
		return $response;
	}
	public function getStudents($dao, $data){
		$response = $dao->getStudents($data);
		return $response;
	}
	public function getStudent($dao, $data){
		$response = $dao->getStudent($data);
		return $response;
	}
	public function updateStudent($dao, $data){
		$response = $dao->updateStudent($data);
		return $response;
	}
	public function deleteStudent($dao, $data){
		$response = $dao->deleteStudent($data);
		return $response;
	}
	/** Course functions **/
	public function addCourse($dao, $data){
		$response = $dao->addCourse($data);
		return $response;
	}
	public function getCourses($dao){
		$response = $dao->getCourses();
		return $response;
	}
	public function getCourse($dao, $data){
		$response = $dao->getCourse($data);
		return $response;
	}
	public function updateCourse($dao, $data){
		$response = $dao->updateCourse($data);
		return $response;
	}
	public function deleteCourse($dao, $data){
		$response = $dao->deleteCourse($data);
		return $response;
	}
	/** Party functions **/
	public function addParty($dao, $data){
		$response = $dao->addParty($data);
		return $response;
	}
	public function getParties($dao){
		$response = $dao->getParties();
		return $response;
	}
	public function updateParty($dao, $data){
		$response = $dao->updateParty($data);
		return $response;
	}
	public function deleteParty($dao, $data){
		$response = $dao->deleteParty($data);
		return $response;
	}
	/** Position Functions **/
	public function addPosition($dao, $data){
		$response = $dao->addPosition($data);
		return $response;
	}
	public function getPositions($dao){
		$response = $dao->getPositions();
		return $response;
	}
	public function updatePosition($dao, $data){
		$response = $dao->updatePosition($data);
		return $response;
	}
	public function deletePosition($dao, $data){
		$response = $dao->deletePosition($data);
		return $response;
	}
	/** Candidates Functions **/
	public function addCandidate($dao, $data){
		$response = $dao->addCandidate($data);
		return $response;
	}
	public function getCandidates($dao, $data){
		$response = $dao->getCandidates($data);
		return $response;
	}
	public function checkCandidate($dao, $data){
		$response = $dao->checkCandidate($data);
		return $response;
	}
	public function editCandidate($dao, $data){
		$response = $dao->editCandidate($data);
		return $response;
	}
	public function deleteCandidate($dao, $data){
		$response = $dao->deleteCandidate($data);
		return $response;
	}
	/** Year Level Functions **/
	public function addYearLevel($dao, $data){
		$response = $dao->addYearLevel($data);
		return $response;
	}
	public function updateYearLevel($dao, $data){
		$response = $dao->updateYearLevel($data);
		return $response;
	}
	public function getYearLevels($dao){
		$response = $dao->getYearLevels();
		return $response;
	}
	public function getYearLevel($dao, $data){
		$response = $dao->getYearLevel($data);
		return $response;
	}
	public function deleteYearLevel($dao, $data){
		$response = $dao->deleteYearLevel($data);
		return $response;
	}
	/** Admin Functions */
	public function fetchAllAdmin($dao){
		$response = $dao->fetchAllAdmin();
		return $response;
	}
	public function addAdmin($dao, $data){
		$response = $dao->addAdmin($data);
		return $response;
	}
	public function deleteAdmin($dao, $data){
		$response = $dao->deleteAdmin($data);
		return $response;
	}
	public function updateAdmin($dao, $data){
		$response = $dao->updateAdmin($data);
		return $response;
	}
	/** Login Function **/
	public function login($dao, $data){
		$response = $dao->login($data);
		return $response;
	}
	/** Vote Function **/
	public function vote($dao, $data){
		$response = $dao->vote($data);
		return $response;
	}
	public function voterLogin($dao, $data){
		$response = $dao->voterLogin($data);
		return $response;
	}
	public function getCounts($dao, $data){
		$response = $dao->getCounts($data);
		return $response;
	}
	/** Register Function **/
	public function addRegStudent($dao, $data){
		$response = $dao->addRegStudent($data);
		return $response;
	}
}
?>