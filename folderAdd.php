<?php

// Start session
session_start();

$postData = $userData = array();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Get posted data from session
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}

// Get user data
if(!empty($_GET['id'])){
    include 'DB.class.php';
    $db = new DB();
    $conditions['where'] = array(
        'id' => $_GET['id'],
    );
    $conditions['return_type'] = 'single';
    $userData = $db->getRows('folder_db', $conditions);
}

// Pre-filled data
$userData = !empty($postData)?$postData:$userData;

// Define action
$actionLabel = !empty($_GET['id'])?'EDIT':'ADD';

?>


<!-- Add/Edit form -->
<link rel="stylesheet" type="text/css" href="cssfolders/main.css">
<link rel="stylesheet" type="text/css" href="cssfolders/addedit.css">
<link rel="stylesheet" type="text/css" href="cssfolders/alert.css">
<link rel="stylesheet" type="text/css" href="cssfolders/fonts.css">

<!--  Icons -->
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>


<div class="panel">
    <div class="modal-content">
    <!-- Display status message -->
    <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
    <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
    <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
    <?php } ?>
    <div class="panel-heading heavier"><?php echo $actionLabel; ?> USER <a href="folders.php"> <i class='bx bx-arrow-back'></i></a></div>
        <form method="post" action="folderAction.php" class="form">
            <div class="form-group">
                <label>Folder Name</label>
                <input type="text" class="form-control" name="folder_name" value="<?php echo !empty($userData['folder_name'])?$userData['folder_name']:''; ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo !empty($userData['id'])?$userData['id']:''; ?>">
            <input type="submit" name="folderSubmit" class="input_button bolder" value="CREATE FOLDER"/>
        </form>
    </div>

</div>