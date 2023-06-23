<?php
require '../process/config.php';
include '../shared/head.php';
$config = new Config();
$sql = "select idno, lastname, firstname, middlename, votingcode from tblstudent";
$query = mysqli_query($config->getConnection(), $sql);
$data = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>
<style>
	@media print {
		 @page 
        {
            size: auto;   /* auto is the current printer page size */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        body 
        {
            background-color:#FFFFFF;  
            margin: 0px;  /* the margin on the content before printing */
       }
	}
</style>
<link rel="stylesheet" type="text/css" href="../bootstrap/dist/css/bootstrap.min.css" media="print">
<body onload="window.print();window.close()">
<table class="table table-bordered">
	<thead>
		<tr>  
			<th>ID No.</th>
			<th>Full Name</th>   
			<th>Voting Code</th> 
		</tr>
	</thead>
	<tbody>
<?php foreach($data as $key => $value): ?>
	<tr>
		<td><?php echo $value['idno'] ?></td>
		<td><?php echo $value['lastname'].", ".$value['firstname']." ".$value['middlename'] ?></td>
		<td><?php echo $value['votingcode'] ?></td>
	</tr>
<?php endforeach ?>
	</tbody>
</table> 
</body>
<?php include '../shared/foot.php'; ?> 