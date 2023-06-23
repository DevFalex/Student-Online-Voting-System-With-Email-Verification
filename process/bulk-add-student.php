<?php 
    require 'config.php';
    $config = new Config();
    $conn = $config->getConnection();

    $IDNoz = $_POST['IDNo'];
    $LastName = $_POST['LastName'];
    $FirstName = $_POST['FirstName'];
    $MiddleName = $_POST['MiddleName'];
    $Course = $_POST['Course'];
    $voting_code = $_POST['voting_code_'];
    $YearLevel = $_POST['YearLevel'];
    $img = '';
    $vote_status = '';
    $row=0;
    foreach($IDNoz as $IDNO) {
        $sql = 'insert into tblstudent values(?,?,?,?,?,?,?,?,?,?)';
        $qry=$conn->prepare($sql);
        $qry->bind_param('issssisssi',
            $id,
            $IDNO,
            $LastName[$row],
            $FirstName[$row],
            $MiddleName[$row],
            $Course[$row],
            $img,
            $voting_code[$row],
            $vote_status,
            $YearLevel[$row]);
        if($qry->execute()) {
            echo'nc';
        } else {
            echo $qry->error;
        }
        $row = $row +1;
    }
?>