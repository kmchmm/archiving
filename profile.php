<?php

session_start();



if(!isset($_SESSION["user_id"])) {
	header("Location: login.php");
}


?>

<?php
	// Load pagination class
	require_once 'Pagination.class.php';

		// Load and initialize database class
	require_once 'DB.class.php';
	$db = new DB();

	// Page offset and limit
	$perPageLimit = 5;
	$offset = !empty($_GET['page'])?(($_GET['page']-1)*$perPageLimit):0;

		// Get count of the users
	$con = array(
		'return_type' => 'count'
	);
	$rowCount = $db->getRows('archive_db', $con);
	$rowCountFolder = $db->getRows('folder_db', $con);

	// Initialize pagination class
	$pagConfig = array(
		'totalRows' => $rowCount,
		'perPage' => $perPageLimit
	);
	$pagConfigFolder = array(
		'totalRowsFolder' => $rowCountFolder,
		'perPage' => $perPageLimit
	);
	$pagination = new Pagination($pagConfig);
	$paginationFolder = new Pagination($pagConfigFolder);

	// Get users from database
	$con = array(
		'start' => $offset,
		'limit' => $perPageLimit,
		'order_by' => 'id DESC',
	);
	$users = $db->getRows('archive_db', $con);
	$folders = $db->getRows('folder_db', $con);


	include("themefunction.php");
	include("connection.php");
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" type="text/css" href="cssfolders/main.css">
	<link rel="stylesheet" type="text/css" href="cssfolders/fonts.css">
	<link rel="stylesheet" type="text/css" href="cssfolders/profile.css">
	<link id="themeStylesheet" rel="stylesheet" type="text/css" href="cssfolders/<?= getTheme()?>.css">
	<!--  Icons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

	<!-- Fonts -->
	<!-- Montserrat -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">

	<!--  Lato -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
	
	<!-- Poppins -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	
	
	<title>Profile | Link and Multimedia Archiving</title>
</head>

<body>

	<div class="container">
		<input type="checkbox" id="nav-toggle">
		<div class="sidebar">
			<div class="sidebar-brand lighter">
				<span><i class='bx bxs-component' ></i></span><h1> Link and Media Archiving</h1>
			</div>

			<div class="sidebar-menu bold">
				<ul>
					<li>
						<a href="dashboard.php"><span><i class='bx bxs-dashboard'></i></span>
						<span>Dashboard</span></a>
						<div class="tooltip-1">Dashboard</div>
					</li> 
					<li>
						<a href="linkmultifolder.php"><span><i class='bx bx-link'></i></span>
						<span>Links/Media</span></a>
						<div class="tooltip-2">Links/Media</div>
					</li>
					<li>
						<a href="folders.php" ><span><i class='bx bxs-folder-open' ></i></span>
						<span>Folders</span></a>
						<div class="tooltip-3">Folders</div>
					</li>
					<li>
						<a href="profile.php"class="active"><span><i class='bx bxs-user' ></i></span>
						<span>Profile</span></a>
						<div class="tooltip-4">Profile</div>
					</li>
					<li>
						<a href="logout.php"><span><i class='bx bx-log-out' ></i></span>
						<span>Log Out</span></a>
						<div class="tooltip-5">Log Out</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="main-content">
			<header>
				<h1>
					<label for="nav-toggle">
						<span class="burgerBtn">
							<div class="bars"></div>
						</span>
					</label>

					Profile Settings
				</h1>


				<div class="user-wrapper bolder">
					<div class="user-wrapper-img"><img src="images/profile.png"></div>
					<div>
						<h4 class="bolder"><?php echo $_SESSION["fullname"];?></h4>
						<small class="lighter">Thesis Project Admin</small>
					</div>
				</div>
			</header>

			<main>
				<div class="profile-container">
					<div class="upper-profile-part">
						<div>
							<h4>User Profile</h4>
						</div>
						<form method="post" class="profile-select">
							<div class="custom-select">
								<select name="themeSelect" id="themeSelect">
									<option>Themes </option>
									<option value="maintheme" <?= getTheme() === "maintheme" ? "selected" : "" ?> >Main Color</option>
									<option value="pastel" <?= getTheme() === "pastel" ? "selected" : "" ?> >Pastel</option>
									<option value="tecoturg" <?= getTheme() === "tecoturg" ? "selected" : "" ?> >Tecoturg</option>
									<option value="fluorescent" <?= getTheme() === "fluorescent" ? "selected" : "" ?> >Fluorescent</option>
								</select>		
							</div>
							<input type="submit" value="Select Theme">
						</form>
					</div>
					<div class="profile-main">
						<div class="profile-left">
								<img src="images/coverphoto.jpg" alt="credits to the owner" class="cover-image" >
							<div class="profile-picture-circle">
								<span><img src="images/profile.png"><i class='bx bxs-camera' id="photobutton" onclick="photobutton()"></i>
									<div class="photomodal" id="photoModal">
										<div class="photo-modal-content">
											<form action="" method="post">
												<div class="inputBox">
													<label for="photo">Change Photo</label>
													<input type="file" accept="image/*" id="photo" name="photo" required>
												</div>
											</form>
											<button class="photocancel bolder" onclick="photoCancel()">Cancel</button>
										</div>
									</div>
								</span>
								<h5><?php echo $_SESSION["fullname"];?></h5>
							</div>
							<div class="profile-left-p">
								<span class="profile-left-flex-item">Developer</span>
								<span class="profile-left-flex-item">Developer</span>
								<span class="profile-left-flex-item">Developer</span>
							</div>
							<button class="bolder" id="profileButton" onclick="profilebutton()">Edit Profile</button>
								<div class="profilemodal" id="profileModal">
									<div class="profile-modal-content">
										<div class="profile-theme-upper">
											<button class="profilecancel bolder" onclick="profileCancel()">Cancel</button>
											<button class="bolder">Save</button>
										</div>
										<hr>
										<form action="" class="themeform" method="post">
											<div class="inputBox">
												<label for="full_name">Full Name</label><br>
												<input type="text" id="full_name" name="full_name" autocomplete="off" required><br>
											</div>
											<div class="inputBox">
												<label for="email">Email</label><br>
												<input type="email" id="email" name="email" autocomplete="off" disabled required><br>
											</div>
											<div class="inputBox">
												<label for="password">Password</label><br>
												<input type="password" id="password" name="password" autocomplete="off" required><br>
											</div>
											<div class="inputBox">
												<label for="cpassword">Confirm Password</label><br>
												<input type="password" id="cpassword" name="cpassword" autocomplete="off" required><br>
											</div>
										</form>
									</div>
								</div>
						</div>
						<div class="profile-right">
							<div class="profile-progress">
								<h4>PROGRESS THIS WEEK</h4>
								<img src="images/progress.svg" alt="credits to the owner" class="image-right">
								<img src="images/progresspastel.svg" alt="credits to the owner" class="image-right-pastel">
								<img src="images/progresstecoturg.svg" alt="credits to the owner" class="image-right-tecoturg">
								<img src="images/progressfluorescent.svg" alt="credits to the owner" class="image-right-fluorescent">
							</div>
							<div class="profile-progress-words">
								<span class="progress-flex-items">Number of data added 
									<div class="data-box"><span><?php echo $pagination->getTotalRows(); ?></span></div>
								</span>
								<span class="progress-flex-items">Number of folders added
									<div class="folder-box"><span><?php echo $paginationFolder->getTotalRowsFolder(); ?></span></div>
								</span>
							</div>
						</div>
					</div>
					<div class="profile-graphs-container">
						<div class="profile-graphs">
							<div class="upper-profile-graph-content">
								<h4>Data Chart</h4>
								<div class="lower-left">
									<img src="images/linkgraph.svg" alt="credits to the owner" class="image-lower-left">
									<img src="images/linkgraphpastel.svg" alt="credits to the owner" class="image-lower-left-pastel">
									<img src="images/linkgraphtecoturg.svg" alt="credits to the owner" class="image-lower-left-tecoturg">
									<img src="images/linkgraphfluorescent.svg" alt="credits to the owner" class="image-lower-left-fluorescent">
								</div>
							</div>
							<div class="middle-data-graph-content">
								<div class="data-square" style="background-color: #C474C4;"></div>
								<div class="data-square" style="background-color: #C47474;"></div>
								<span class="data-square-l">Number of links</span>
								<span class="data-square-m">Number of media</span>
							</div>
							<div class="lower-profile-graph-content">
								<div class="graph-content">
									
								</div>
							</div>
						</div>
						<div class="profile-graphs">
							<div class="upper-profile-graph-content">
								<h4>Folder Chart</h4>
								<div class="lower-right">
									<img src="images/foldergraph.svg" alt="credits to the owner" class="image-lower-right">
									<img src="images/foldergraphpastel.svg" alt="credits to the owner" class="image-lower-right-pastel">
									<img src="images/foldergraphtecoturg.svg" alt="credits to the owner" class="image-lower-right-tecoturg">
									<img src="images/foldergraphfluorescent.svg" alt="credits to the owner" class="image-lower-right-fluorescent">
								</div>
							</div>
							<div class="middle-folder-graph-content">
								<div class="folder-square" style="background-color: #7C74C4;"></div>
								<span class="folder-square-p">Number of folders</span>
							</div>
							<div class="lower-profile-graph-content">
								<div class="graph-content">

								</div>
							</div>
						</div>
					</div>
					<footer class="dash_footer lighter" style="bottom:0px;">Created by . Copyright &copy; 2021</footer>
				</div>	
			</main>
		</div>
	</div>
	
<script src="scriptfolders/main.js"></script>
<script src="scriptfolders/profile.js"></script>
</body>
</html>