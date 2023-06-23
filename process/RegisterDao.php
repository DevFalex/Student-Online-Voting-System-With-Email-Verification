<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegisterDao{
	private $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	
	public function addRegStudent($data){
		
		$result = mysqli_query($this->conn,"SELECT * FROM tblstudent WHERE idno='" . $data['studentID'] . "' OR email='" . $data['email'] . "'");
		$row= mysqli_num_rows($result);
		if($row < 1)
		{
			$prefix = md5(date('Y-m-d h:i:s a'));
			$uni = uniqid(substr($prefix, 4, 6),true);
			$suffix = str_shuffle('abcdefghijklmnopqrstuvwxyz');
			$votingcode = strtoupper(substr($suffix , 0 ,3).'-'. substr(str_shuffle(strrev(str_replace(".","",$uni))),0 ,6));
			$sql = "INSERT into tblstudent (idno, lastname, firstname, middlename, email, courseid, votingcode, votestatus, yearlevelid) value (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$query = $this->conn->prepare($sql) or die(mysqli_error($this->conn));
			$query->bind_param('sssssssii', $data['studentID'], $data['lastName'], $data['firstName'], $data['middleName'], $data['email'],
				$data['course'], $votingcode, $data['status'], $data['yearlevel']
			);
			
			// ... previous code ...
			require "../PHPMailer/PHPMailer.php";
			require "../PHPMailer/Exception.php";
			require "../PHPMailer/SMTP.php";

			// Send email to the registered student
			$mail = new PHPMailer(true);
			$mail->isSMTP();  // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;  // Enable SMTP authentication
			$mail->Username = 'shoppyfoxstore@gmail.com';  // SMTP username
			$mail->Password = 'qkjqyszncsodpydb';  // SMTP password
			$mail->SMTPSecure = 'tls';  // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;  // TCP port to connect to

			$mail->setFrom('shoppyfoxstore@gmail.com', 'NACOS JABU ELECTORIAL COMMITEE');
			$mail->addAddress($data['email']);  // Add recipient email address

			$mail->isHTML(true);  // Set email format to HTML
			$mail->Subject = 'Voting Code';
			$mail->Body = 'Your voting code is: ' . $votingcode;

			if ($query->execute()) {
				$mail->send();
				echo "Copy code: " .$votingcode;

			} 
			// ... remaining code ...			
		}
	}
}
?>