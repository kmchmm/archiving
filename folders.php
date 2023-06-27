<?php

session_start();



if(!isset($_SESSION["user_id"])) {
	header("Location: login.php");
}

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Load pagination class
require_once 'Pagination.class.php';

// Load and initialize database class
require_once 'DB.class.php';
$db = new DB();

// Page offset and limit
$perPageLimit = 7;
$offset = !empty($_GET['page'])?(($_GET['page']-1)*$perPageLimit):0;

// Get search keyword
$searchKeyword = !empty($_GET['sq'])?$_GET['sq']:'';
$searchStr = !empty($searchKeyword)?'?sq='.$searchKeyword:'';

// Search DB query
$searchArr = '';
if(!empty($searchKeyword)){
    $searchArr = array('folder_name' => $searchKeyword);
}

// Get count of the users
$con = array(
    'like_or' => $searchArr,
    'return_type' => 'count'
);
$rowCountFolder = $db->getRows('folder_db', $con);

// Initialize pagination class
$pagConfigFolder = array(
    'baseURLFOLDER' => 'folders.php'.$searchStr,
    'totalRowsFolder' => $rowCountFolder,
    'perPage' => $perPageLimit
);
$paginationFolder = new Pagination($pagConfigFolder);

// Get users from database
$con = array(
    'like_or' => $searchArr,
    'start' => $offset,
    'limit' => $perPageLimit,
    'order_by' => 'id DESC',
);
$users = $db->getRows('folder_db', $con);


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
	<link rel="stylesheet" type="text/css" href="cssfolders/folder.css">
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
	
	<title>Folders | Link and Multimedia Archiving</title>
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
						<a href="linkmultifolder.php" ><span><i class='bx bx-link'></i></span>
						<span>Links/Media</span></a>
						<div class="tooltip-2">Links/Media</div>
					</li>
					<li>
						<a href="folders.php" class="active"><span><i class='bx bxs-folder-open' ></i></span>
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
						<span class="burgerBtn">
							<div class="bars"></div>
						</span>
					</label>

					Folders
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
				<div class="folder-container">
					<h2>Saved Folders</h2>
					<div class="folderbutton">
						<a href="folderAdd.php" class="input_button bolder" id="addLinks">CREATE FOLDER</a>
					</div>
						<form>
							<div id="search-wrapper">
								<input type="text" name="sq" id="sq" placeholder="Search">
								<span><button class="btn-default" type="submit"> <i class='bx bx-search'></i></button></span>
							</div>
						</form>
						<div class="folder-commands">
							<label class="select_all">Select All
							<input type="checkbox" id="folderSelectAll" name="folderselectall">
							<span class="checkmark"></span>
							</label>
							<span><i class='bx bxs-trash deleteall'></i></span>
						</div>

					<div class="folder-wrapper">
						<table class="table" id="itemList">
							<thead>
								<tr>
									<th></th>
									<th colspan="3">Folder Name</th>
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
										<td colspan="3" class="td_underline"><?php echo '<a href ="folderDataList.php" target="_self">'. '<span class="td_name">'.$user["folder_name"].'</span>'.'</a>';?></td>
										<td><?php echo $user['date'];?></td>
										<td>
											<a href="folderDataList.php" target="_self"><i class='bx bxs-data'></i></a>
											<a href="folderAdd.php?id=<?php echo $user['id']; ?>"><i class='bx bx-edit-alt' ></i></a>
											<a href="folderAction.php?action_type=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure to delete?')"><i class='bx bxs-trash' ></i></a>
										</td>
									</tr>
								<?php } }else{ ?>
									<tr><td colspan="5">No data found...</td></tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="pagination_adjustments"><?php echo $paginationFolder->createFolders(); ?></div>
					</div>
				</div>
				<footer class="dash_footer lighter">Created by . Copyright &copy; 2021</footer>
			</main>
		</div>
	</div>
	
<script src="scriptfolders/main.js"></script>
<script src="scriptfolders/folder.js"></script>
</body>
</html>