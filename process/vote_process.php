<?php
    session_start();
    require('config.php');
    $config = new Config();
    $conn = $config->getConnection();
    if(isset($_GET['checkIfVoted'])) {
        $data = $_GET['data'];
        $sql = "select s.*, vs.* from tblstudent as s
                left join tblvotestatus as vs ON
                s.id = vs.vote_status_studentid
                where votingcode='".$data."' or idno = '".$data."'";
		$query = mysqli_query($conn, $sql);
        $response2 = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $checker = false;
        foreach($response2 as $res) {
            if($res['vote_status_election_date_id']==$_GET['election_id']) {
                $checker = true;
            }
        }
        if(count($response2) == 0) {
            $response = array(
				"status" => "failed",
				"data" => null,
				"message" => "Voting code does not exists!"
			);
        } else {
            if($checker == false){
				$response = array(
					"status" => "success",
					"data"	=> $data,
					"message" => "You have successfully logged in! You may now vote"
				);
				$_SESSION['status'] = 'success';
                $_SESSION['id'] = $response2[0]['id'];
			} else {
				$response = array(
					"status" => "failed",
					"data"	=> null,
					"message" => "You have already voted!"
				);
			}
        }
        echo json_encode($response);
    } else if(isset($_GET['submitVote'])) {
        $sid=$_SESSION['id'];
        $elecid=$_GET['election_id'];
        $id='';
        $sql='insert into tblvotestatus VALUES(?,?,?)';
        $qry=$conn->prepare($sql);
        $qry->bind_param('iii',$id,$elecid,$sid);
        $qry->execute();
        $qry->close();
        unset($_SESSION['id']);
        unset($_SESSION['status']);
    }
?>