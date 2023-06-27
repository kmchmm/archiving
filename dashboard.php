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
	<link rel="stylesheet" type="text/css" href="cssfolders/dashboard.css">
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
	<title>Link and Multimedia Archiving</title>
</head>

<body onload="initClock()">
	
	<div class="container">
		<input type="checkbox" id="nav-toggle">
		<div class="sidebar">
			<div class="sidebar-brand lighter">
				<span><i class='bx bxs-component' ></i></span><h1> Link and Media Archiving</h1>
			</div>

			<div class="sidebar-menu bold">
				<ul>
					<li>
						<a href="dashboard.php" class="active"><span><i class='bx bxs-dashboard'></i></span>
						<span>Dashboard</span></a>
						<div class="tooltip-1">Dashboard</div>
					</li> 
					<li>
						<a href="linkmultifolder.php" ><span><i class='bx bx-link'></i></span>
						<span>Links/Media</span></a>
						<div class="tooltip-2">Links/Media</div>
					</li>
					<li>
						<a href="folders.php"><span><i class='bx bxs-folder-open' ></i></span>
						<span>Folders</span></a>
						<div class="tooltip-3">Folders</div>
					</li>
					<li>
						<a href="profile.php"><span><i class='bx bxs-user' ></i></span>
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
						<span class="burgerBtn" id="burgerButton">
							<div class="bars"></div>
						</span>
					</label>

					Dashboard
				</h1>

				<div class="user-wrapper bolder">
					<div class="user-wrapper-img"><img src="images/profile.png"></div>
					<div>
						<h4 class="bolder"> <?php echo $_SESSION["fullname"];?></h4>
						<small class="lighter">Thesis Project Admin</small>
					</div>
				</div>
			</header>

			<main class="dashboard-main">
				<div class="records">
					<div class="record-list">
						<div>
							<h1>Links saved</h1>
							<div>
								<h2>Recently Added</h2>
							</div>
							<div class="record-list-division">
								<?php
									$query ="SELECT * FROM archive_db ORDER BY id DESC LIMIT 1";
									$query_run = mysqli_query($conn, $query);

									if (mysqli_num_rows($query_run) > 0){
										foreach($query_run as $row){
											if($row['type_name'] === 'Link'){
												echo '<a href ="' .$row["url_name"]. '" target="_blank">'. '<span class="upper_td_name">'.$row["url_name"].'</span>'.'</a>';
											} elseif($row['type_name'] === 'Multimedia'){
												echo '<a href ="uploadphotos.php" target="_blank">'. '<span class="td_name">Media Url: '.$row["image_file"].'</span>'.'</a>';
											}
										}
									} elseif (mysqli_num_rows($query_run) == 0){
										echo '<span>No records found!</span>';
									}
								?>
							</div>
						</div>
						<div>
								<span class="record-list-number"><?php echo $pagination->getTotalRows(); ?></span>
						</div>
					</div>
					<div class="record-list">
						<div>
							<h1>Folders saved</h1>
							<div>
								<h2>Recently Added</h2>
							</div>
							<div class="record-list-division">
								<?php
										$queryfolder ="SELECT * FROM folder_db ORDER BY id DESC LIMIT 1";
										$query_runfolder = mysqli_query($conn, $queryfolder);

										if (mysqli_num_rows($query_runfolder) > 0){
											foreach($query_runfolder as $rowfolder){
												echo '<a href ="folderDataList.php" target="_self">'. '<span class="upper_td_name">'.$rowfolder["folder_name"].'</span>'.'</a>';
											}
										} elseif (mysqli_num_rows($query_runfolder) == 0){
											echo '<span>No records found!</span>';
										}
									?>
							</div>
						</div>
						<div>
							<span class="record-list-number"><?php echo $paginationFolder->getTotalRowsFolder(); ?></span>
						</div>
					</div>
					<div class="record-list">
						<div>
							<h1>Last update</h1>
							<div>
								<h2>Recently Updated Links</h2>
							</div>
							<div>
								<?php
									$query ="SELECT * FROM archive_db ORDER BY id DESC LIMIT 1";
									$query_run = mysqli_query($conn, $query);
								
										if (mysqli_num_rows($query_run) > 0){
											foreach($query_run as $row){
												echo '<span class="date_records">Date: '.$row["date"]. '</span>';
										}
									} elseif (mysqli_num_rows($query_run) == 0){
										echo '<span class="date_records">No records found!</span>';
									}
								 
								 ?>
							</div>
							<div>
								<h2>Recently Updated Folders</h2>
							</div>
							<div>
								<?php
									$queryfolder ="SELECT * FROM folder_db ORDER BY id DESC LIMIT 1";
									$query_runfolder = mysqli_query($conn, $queryfolder);
									
										if (mysqli_num_rows($query_runfolder) > 0){
											foreach($query_runfolder as $rowfolder){
												echo '<span class="date_records">Date: '.$rowfolder["date"]. '</span>';
										}
									} elseif (mysqli_num_rows($query_runfolder) == 0){
										echo '<span class="date_records">No records found!</span>';
									}
									
								?>
							</div>
						</div>
					</div>
					<div class="record-list">
						<div class="datetime">
							<div class="date">
								<span id="dayname">Day</span>,
								<span id="month">Month</span>
								<span id="daynum">00</span>,
								<span id="year">Year</span>
							</div>
							<div class="time">
								<span id="hour">00</span>:
								<span id="minutes">00</span>:
								<span id="seconds">00</span>
								<span id="period">AM</span>
							</div>
						</div>
					</div>
				</div><br>
				
				<div class="cards">
					<div class="card-single">
						<div class="upper-card-single">
							<div>
								<h1><a href="linkmultifolder.php">LINKS AND MULTIMEDIA</a></h1>
							</div>
						</div>
						<br>
						<div>
							<form>
								<table class="table">
									<thead>
										<tr>
											<th></th>
											<th colspan="2">URL</th>
											<th>Category</th>
											<th>Type</th>
											<th>Date</th>
											<th >Action</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if(!empty($users)){ $count = 0; 
										foreach($users as $user){ $count++;
									?>
									<tr>
										<td><?php echo $count; ?></td>
										<td colspan="2" class="td_underline">
											<?php
												if($user['type_name'] === 'Link'){
													
											?>
													<a href =" <?php echo $user["url_name"]; ?>" target="_blank"><span class="td_name"> <?php echo $user["url_name"]; ?></span></a>
											<?php } elseif($user['type_name'] === 'Multimedia'){
	
											?>	
													<a href ="uploadphotos.php" target="_blank"><span class="td_name">Media Url: <?php echo $user["image_file"]; ?></span></a>
											<?php }?>
										</td>
										<td><?php echo $user['category']; ?></td>
										<td><?php echo $user['type_name']; ?></td>
										<td><?php echo $user['date'];?></td>
										<td>
											<a href="addEdit.php?id=<?php echo $user['id']; ?>"><i class='bx bx-edit-alt' ></i></a>
											<a href="userAction.php?action_type=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure to delete?')"><i class='bx bxs-trash' ></i></a>
										</td>
									</tr>
									<?php } }else{ ?>
									<tr><td colspan="5">No data found. Please input something!</td></tr>
									<?php } ?>

									</tbody>
									
								</table>
							<!-- Display pagination links -->
							<div class="pagination_adjustments"><?php echo $pagination->createLinksDashboard(); ?></div>
							</form>
						</div>
					</div>

					<div class="card-single">
						<div class="upper-card-single">
							<div>
								<h1><a href="folders.php">FOLDERS</a></h1>
							</div>
						</div>
						<br>
						<div>
							<table class="table">
								<thead>
									<tr>
										<th></th>
										<th>Folder Name</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if(!empty($folders)){ $count = 0; 
										foreach($folders as $folder){ $count++;
								?>
									<tr>
										<td><?php echo $count; ?></td>
										<td class="td_underline"><?php echo '<a href ="folderDataList.php" target="_self">'. '<span class="td_name">'.$folder["folder_name"].'</span>'.'</a>';?></td>
										<td><?php echo $folder['date'];?></td>
										<td>
											<a href="folderDataList.php" target="_self"><i class='bx bxs-data'></i></a>
											<a href="folderAdd.php?id=<?php echo $folder['id']; ?>"><i class='bx bx-edit-alt' ></i></a>
											<a href="folderAction.php?action_type=delete&id=<?php echo $folder['id']; ?>" onclick="return confirm('Are you sure to delete?')"><i class='bx bxs-trash' ></i></a>
										</td>
									</tr>
								<?php } }else{ ?>
									<tr><td colspan="5">No data found...</td></tr>
								<?php } ?>
								</tbody>
							</table>
							<div class="pagination_adjustments"><?php echo $paginationFolder->createLinksDashboardFolder(); ?></div>
						</div>
					</div>
				</div><br>
				<footer class="dash_footer lighter">Created by . Copyright &copy; 2021</footer>
			</main>
		</div>
	</div>
	
<script src="scriptfolders/main.js"></script>
<script src="scriptfolders/dashboard.js"></script>
</body>
</html>