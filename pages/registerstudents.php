
<!DOCTYPE html>
<html>
<head>
	<title>Register To Vote</title>
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
	<meta name="keywords" content="NACOS JABU ELECTION" />
	
	<link href="../src/css/select2.min.css" rel="stylesheet" />
	<!-- Bootstrap 4.0 -->
	<link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.min.css">
	
	<!--/Style-CSS -->
	<link rel="stylesheet" href="../pages/registervotecss.css" type="text/css"/>
	<!--//Style-CSS -->
</head>
<body>
	<!-- /login-section -->

	<section class="w3l-forms-23">
		<div class="forms23-block-hny">
			<div class="wrapper">
				<h1>Register To Vote</h1>
				<div class="d-grid forms23-grids">
					<div class="form23">
						<div class="main-bg">
							<h6 class="sec-one">Register</h6>
							<div class="speci-login first-look">
								<img src="homeimgs/user.png" alt="" class="img-responsive">
							</div>
						</div>
						<div class="bottom-content">
							<div class="form-message"></div>
							<form id="registerForm" method="post" action="../process/RegisterRoutes.php" enctype="multipart/form-data">
								<input type="hidden" class="input-form" name="id" id="id">

								<label for="studentID"><b>Student ID</b></label>
								<input type="text" class="input-form" name="studentID" id="studentID" placeholder="Enter Student ID" required>

								<label for="lastName"><b>Last Name</b></label>
								<input type="text" name="lastName" class="input-form" id="lastName" placeholder="Enter Last Name" required>

								<label for="firstName"><b>First Name</b></label>
								<input type="text" name="firstName" class="input-form" id="firstName" placeholder="Enter First Name" required>

								<label for="middleName"><b>Middle Name</b></label>
								<input type="text" name="middleName" class="input-form" id="middleName" placeholder="Enter Middle Name">

								<label for="email"><b>Email</b></label>
								<input type="email" name="email" class="input-form" aria-describedby="emailHelp" id="email" placeholder="Enter Your Email" autocomplete="on" required>
								<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>

								<label for="course"><b>Course</b></label>
								<select name="course" class="input-form" id="course" required>
									<!-- Add options for courses here -->
								</select>

								<label for="yearlevel"><b>Year Level</b></label>
								<select name="yearlevel" class="input-form" id="yearlevel" required>
									<!-- Add options for year levels here -->
								</select>

								<button type="submit" class="loginhny-btn btn" id="btnSubmit">Register</button>
							</form>
							<div id="output"></div>
							<p>Already have a Voting Code? <a href="../vote/vote-page.php">Vote Now!</a></p>
						</div>
					</div>
				</div>
				<div class="w3l-copy-right text-center">
					<p> DEVELOPED BY <a href="https://www.instagram.com/opdigitalsworld/" target="_blank">OPDIGITALS</a></p>
					<p> &copy; <a href="https://jabu.edu.ng" target="_blank">NACOS JABU CHAPTER </a><?php echo date('Y'); ?></p>
				</div>
			</div>
		</div>
	</section>
	<!-- //Register-section -->

	<!-- Scripts -->
	<script src="../jquery/jquery.min.js"></script>
	<script src="../jquery/popper.js"></script>
	<script src="../jquery/canvasjs.min.js"></script>
	<script src="../src/js/select2.min.js"></script>

	<script type="text/javascript">

		function loadCourse(){
			$.ajax({
				method: 'GET',
				url : '../process/CourseRoutes.php',
				data : {courses: 'g'},
				success : function(e){
					let response = JSON.parse(e);
					$("#course").empty();
					$("#course").append("<option value=''>Select Course</option>");
					$.each(response, function(index, value){
						$("#course").append(
							"<option class='courses' value='"+value['id']+"'>"+value['courseinitial']+"</option>"
						);
					});
				}
			})
		}
		function loadYrLvl(){
			$.ajax({
				method: 'GET',
				url : '../process/YearLevelRoutes.php',
				data: {yearlvl: 'g'},
				success: function(e){
					let response = JSON.parse(e);
					$("#yearlevel").empty();
					$("#yearlevel").append("<option value=''>Select Level</option>");
					$.each(response, function(index, val){
						$("#yearlevel").append(
							"<option value='"+val['id']+"'>"+val['yearlevelinitial'] + "</option>"
						);
					});
				}
			})
		}   
		
		$(document).ready(function() {
			loadCourse();
			loadYrLvl();

			$("#registerForm").on("submit", function(e) {
				e.preventDefault();
				var $this = $(this);

				$("#output").text("");

				let studentID = $("#studentID").val();
				let email = $("#email").val();

				// Perform client-side validation
				var isValid = true;
				if (studentID.length < 8 || studentID.length > 10 || parseInt(studentID.substr(0, 2)) < 19) {
					isValid = false;
					$("#studentID").addClass("error");
				} else {
					$("#studentID").removeClass("error");
				}

				var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
				if (!email.match(emailRegex)) {
					isValid = false;
					$("#email").addClass("error");
				} else {
					$("#email").removeClass("error");
				}
				if (!isValid) {
					return; // Exit if validation failed
				}

				var formData = new FormData($(this)[0]);

				$.ajax({
					type: $this.attr("method"),
					url: $this.attr("action"),
					data: formData,
					processData: false,
					contentType: false,
					cache: false,
					beforeSend: function() {
						$this.attr("disabled", true);
					},
					success: function(response) {
						$this.attr("disabled", false);
						
						if (response.startsWith("Copy code:")) {
							var votingCode = response.substring(10);
							$(".input-form").val('');
							$("#output").text("Registration successful. Your voting code is: " + votingCode);
						} else {
							$("#output").text("Kindly input correct details. Please try again.");
						}
					},
					error: function() {
						$this.attr("disabled", false);
						$("#output").text("Failed to register student. Please try again later.");
					}
				});

			});
			// File validation
		});


	</script>
</body>
</html>