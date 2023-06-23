<?php
    require 'config.php';
    $config = new Config();
    $conn = $config->getConnection();
    $election_id = mysqli_real_escape_string($conn, isset($_GET['election_id']) ? $_GET['election_id'] : '' );
    $election_date = mysqli_real_escape_string($conn, isset($_GET['election_date']) ? $_GET['election_date'] : '' );
    if(isset($_GET['get'])) {
        $sql = "select * from tbl_election_date order by election_date desc";
		$query = mysqli_query($conn, $sql);
		$response = mysqli_fetch_all($query, MYSQLI_ASSOC);
		echo json_encode($response);
    } else if(isset($_GET['add'])) {
        $sql='insert into tbl_election_date VALUES(?,?)';
        $qry = $conn->prepare($sql);
        $id='';
        $qry->bind_param('is',$id, $election_date);
        if($qry->execute()) {
            reset_student_code($conn);
            echo 'success';
        } else {
            echo 'failed';
        }
    } else if(isset($_GET['update'])) {
        $sql='update tbl_election_date SET election_date = ? where election_date_id = ?';
        $qry = $conn->prepare($sql);
        $qry->bind_param('si', $election_date, $election_id);
        if($qry->execute()) {
            echo 'success';
        } else {
            echo 'failed';
        }
    } else if(isset($_GET['delete'])) {
        $sql="delete from tbl_election_date where election_date_id = ?";
        $qry=$conn->prepare($sql);
        $qry->bind_param('i', $election_id);
        if($qry->execute()) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }

    function reset_student_code($conn) {
        $sql = "select id,idno from tblstudent";
		$query = mysqli_query($conn, $sql);
        $response = mysqli_fetch_all($query, MYSQLI_ASSOC);
        
        foreach($response as $row) {
            $prefix = md5($row['idno'].date('Y-m-d h:i:s a'));
            $uni = uniqid(substr($prefix, 4, 6),true);
            $suffix = str_shuffle('abcdefghijklmnopqrstuvwxyz');
            $votingcode = strtoupper(substr($suffix , 0 ,3).'-'. substr(str_shuffle(strrev(str_replace(".","",$uni))),0 ,6));
            $sql = 'update tblstudent set votingcode = ? where id = ?';
            $qry= $conn->prepare($sql);
            $qry->bind_param('si',$votingcode, $row['id']);
            $qry->execute();
        }
    }
?>