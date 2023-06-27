<?php

include 'connection.php';

session_start();

error_reporting(0);

if(isset($_SESSION["user_id"])) {
	header("Location: dashboard.php");
}


if (isset($_POST["signup"])){
	$full_name = mysqli_real_escape_string($conn, $_POST["signup_full_name"]);
	$email = mysqli_real_escape_string($conn, $_POST["signup_email"]);
	$password = mysqli_real_escape_string($conn, md5($_POST["signup_password"]));
	$cpassword = mysqli_real_escape_string($conn, md5($_POST["signup_cpassword"]));

	$check_email = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE email='$email'"));

	if($password !== $cpassword) {
		echo "<script>alert('Password did not match!');</script>";
	} elseif ($check_email > 0){
		echo "<script>alert('Email already exists!');</script>";
	} else {
		$sql = "INSERT INTO users (full_name, email, password) VALUES('$full_name', '$email', '$password')";
		$result = mysqli_query($conn, $sql);
		if ($result){
			$_POST["signup_full_name"] = "";
			$_POST["signup_email"] = "";
			$_POST["signup_password"] = "";
			$_POST["signup_cpassword"] = "";
			echo "<script>alert('User registered successfully!');</script>";
		} else {
			echo "<script>alert('User registerion failed!');</script>";
		}
	}
}


if (isset($_POST["signin"])){
	$full_name = mysqli_real_escape_string($conn, $_POST["signup_full_name"]);
	$email = mysqli_real_escape_string($conn, $_POST["email"]);
	$password = mysqli_real_escape_string($conn, md5($_POST["password"]));

	$check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
	if (mysqli_num_rows($check_email) > 0){
		$row = mysqli_fetch_assoc($check_email);
		$_SESSION["user_id"] = $row['id'];
		$_SESSION["fullname"] = $row['full_name'];
		header("Location: dashboard.php");
	} else {
		echo "<script>alert('Incorrect details! Please try again.');</script>";
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- <link rel="stylesheet" type="text/css" href="cssfolders/main.css"> -->
	<link rel="stylesheet" type="text/css" href="cssfolders/fonts.css">
	<link rel="stylesheet" type="text/css" href="cssfolders/logreg.css">
	
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    

	<script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
	<!-- Poppins -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	
	<title>Log In to Link and Multimedia Archiving</title>
</head>

<body>
	<div class="container_reg-log">
		<div class="forms-container">
			<div class="signin-signup">
				<form class="sign-in-form" method="post" id="signin-form">
					<h2 class="title">Sign in</h2>
					<div class="input-field">
						<i class="fas fa-envelope"></i>
						<input type="text" name="email" placeholder="Email" value="<?php echo $_POST['email']; ?>" autocomplete="off">
					</div>
					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name= "password" placeholder="Password" value="<?php echo $_POST['password']; ?>" autocomplete="off">
					</div>
					<input class="btn solid bold" type="submit" name="signin" value="Log In">

					<p class="social-text">Or Sign in with social platforms</p>
					<div class="social-media">
						<a href="#" class="social-icon">
							<i class="fab fa-facebook-f"></i>
						</a>
						<a href="#" class="social-icon">
							<i class="fab fa-twitter"></i>
						</a>
						<a href="#" class="social-icon">
							<i class="fab fa-google"></i>
						</a>
						<a href="#" class="social-icon">
							<i class="fab fa-linkedin-in"></i>
						</a>
					</div>
				</form>
		
				
				<form class="sign-up-form" method="post" id="signup-form">
					<h2 class="title">Sign up</h2>
					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="signup_full_name" placeholder="Full Name" value="<?php echo $_POST["signup_full_name"]; ?>" autocomplete="off" required>
					</div>
					<div class="input-field">
						<i class="fas fa-envelope"></i>
						<input type="text" name="signup_email" placeholder="Email" value="<?php echo $_POST["signup_email"]; ?>" autocomplete="off" required>
					</div>
					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name= "signup_password" placeholder="Password" value="<?php echo $_POST["signup_password"]; ?>" autocomplete="off" required>
					</div>
					<div class="input-field">
						<i class="fas fa-unlock"></i>
						<input type="password" name= "signup_cpassword" placeholder="Confirm password" value="<?php echo $_POST["signup_cpassword"]; ?>" autocomplete="off" required>
					</div> 
					<input class="btn solid bold" type="submit" name="signup" value="Sign up">
				</form>
			</div>
		</div>
		
		<div class="panels-container">
			<div class="panel left-panel">
				<div class="content">
					<h3 class="bold">New here?</h3>
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus non, quis libero cumque sunt quas! Possimus sint eum eligendi reiciendis numquam libero perferendis quisquam? Dolores magnam consectetur distinctio dignissimos deserunt.</p>
					<button class="btn transparent bold" id="sign-up-btn">Sign up</button>
				</div>
				<img src="images/logreglogo.svg" class="image" alt="credits to the owner">
			</div>
			<div class="panel right-panel">
				<div class="content">
					<h3 class="bold">Already have an account?</h3>
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit animi nesciunt tempora quibusdam enim architecto qui sed quisquam similique minus! Odit ex dolores harum amet atque quo consequatur earum voluptates.</p>
					<button class="btn transparent bold" id="sign-in-btn">Sign in</button>
				</div>
				<img src="images/logreglogo2.svg" class="image" alt="">
			</div>
		</div>
	</div>
<script src="scriptfolders/logreg.js"></script>
</body>
</html>