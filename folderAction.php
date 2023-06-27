<?php

// Start session
session_start();

// Load and initialize database class
require_once 'DB.class.php';

$db = new DB();

$tblName = 'folder_db';

// Set default redirect url
$redirectURL = 'folders.php';

if(isset($_POST['folder_name'])){

    // Get submitted data
    $db_name = $_POST['folder_name'];
    $db_name = strtolower($db_name);
    $db_name = preg_replace("/[^a-z]/", '', $db_name);
    $id     = $_POST['id'];
    
    // Submitted user data
    $userData = array(
        'folder_name' => $db_name,
    );
    $sessData['postData'] = $userData;
    $sessData['postData']['id'] = $id;           

    $db_host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user_db";
    // 2. Create the database
    $mysqli = new mysqli($db_host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        throw new Exception("Connect Error ($mysqli->connect_errno) $mysqli->connect_error");
    }
    $query = "CREATE TABLE `$db_name`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `url_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `category` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `type_name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active | 0=Inactive',
    PRIMARY KEY (`id`)
    )";
    $mysqli->query($query);
    if($mysqli->errno) {
        throw new Exception("DATABASE ALREADY EXISTED: $mysqli->error");
        // Catch the error of the database already existing
    }
    // Store submitted data into session

    
    // ID query string
    $idStr = !empty($id)?'?id='.$id:'';
    
    // If the data is not empty
    if(!empty($db_name)){
            if(!empty($id)){
                // Update data
                $condition = array('id' => $id);
                $update = $db->update($tblName, $userData, $condition);
                if($update){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'User data has been updated successfully.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'folderAdd.php'.$idStr;
                }
            }else{
                // Insert data
                $insert = $db->insert($tblName, $userData);
                
                if($insert){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'User data has been added successfully.';
                }else{

                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'folderAdd.php';
                }
            }
    }else{

        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'All fields are mandatory, please fill all the fields.';
        
        // Set redirect url
        $redirectURL = 'folderAdd.php'.$idStr;
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;
    }elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
    // Delete data
    $condition = array('id' => $_GET['id']);
    
    $delete = $db->delete($tblName, $condition);
    $queryDelete = "DROP TABLE `$db_name`";
    if($delete){
        $sessData['status']['type'] = 'success';
        $sessData['status']['msg']  = 'User data has been deleted successfully.';
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Some problem occurred, please try again.';
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;
}

// Redirect the user
header("Location: ".$redirectURL);
exit();