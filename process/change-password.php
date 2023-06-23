<?php
    session_start();
    require 'config.php';
    $config = new Config();
    $conn = $config->getConnection();   
    $newPassword =hash('SHA256',$_POST['newPassword']);
    $oldPassword =hash('SHA256',$_POST['oldPassword']);
    if($_SESSION['data']['password']==$oldPassword) {
        $sql = "update tbladmin set password = ? where admin_id=?";
		$query = $conn->prepare($sql);
		$query->bind_param('si', $newPassword, $_SESSION['data']['id']);
		if($query->execute()){
			echo "success";
		} else {
			echo "failed";
        }
    } else {
        echo 'failed';
    }
?>