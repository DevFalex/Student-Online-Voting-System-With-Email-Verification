<?php 

$config = new Config();
$conn = $config->getConnection();
$sql = "select courseinitial from tblcourse";
$query = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Register To Vote</title>
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
	<meta name="keywords" content="JABU SUE ELECTION, ELECTION IN JABU" />
		
	<!-- Sweetalert 2 CSS -->
	<link rel="stylesheet" href="../src/plugins/sweetalert2/sweetalert2.min.css">
	<link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.min.css">
	
	<!--/Style-CSS -->
	<link rel="stylesheet" href="registervotecss.css" type="text/css" media="all" />
	<!--//Style-CSS -->
	<style>
		.alert{

			padding:10px;
			background-color: #408000;
			color: white;
			margin-bottom: 15px;

		}
		
	</style>
</head>
<body>
	<!-- /login-section -->

	<section class="w3l-forms-23">
		<div class="forms23-block-hny">
			<div class="wrapper">
				<h1>Register To Vote</h1>
				<!-- if logo is image enable this   
					<a class="logo" href="index.html">
					  <img src="image-path" alt="Your logo" title="Your logo" style="height:35px;" />
					</a> 
				-->
				<div class="d-grid forms23-grids">
					<div class="form23">
						<div class="main-bg">
							<h6 class="sec-one">Register</h6>
							<div class="speci-login first-look">
								<img src="homeimgs/user.png" alt="" class="img-responsive">
							</div>
						</div>
						<div class="bottom-content">
							<div class="alert flex">
									<input type="text" class="input-form" value="Hello World!" id="copy">
									<button type="button" class="btn-secondary" onclick="copyFunction()">Copy Code</button>
							</div>
							
							
							<p>Already have a Voting Code? <a href="../vote/vote-page.php">Vote Now!</a></p>
						</div>
					</div>
				</div>
				<div class="w3l-copy-right text-center">
					<p> Donated by Department of Compter Science JABU</p>
						<p> &copy; <a href="https://jabu.edu.ng/" target="_blank">JABU SUE </a><?php echo date('Y'); ?></p>
				</div>
			</div>
		</div>
	</section>
	<!-- //Register-section -->
	
	<!-- Must put our javascript files here to fast the page loading -->
	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="../jquery/jquery.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="../jquery/popper.js"></script>
	<!-- Sweetalert2 JS -->
	<script src="../src/plugins/sweetalert2/sweetalert2.min.js"></script>
	

	
<script type="text/javascript">

function copyFunction(){
	var copyText = document.getElementById("copy");
	copyText.select();
	copyText.setSelectionRange(0,99999);
	navigator.clipboard.writeText(copyText.value);

	//alert("copied!: " + copyText.value);
}

$(document).ready(function() {
	alert("working");

});

</script>
</body>
</html>