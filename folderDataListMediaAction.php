<?php


// Start session
session_start();

// Load and initialize database class
require_once 'dataListDb.php';

$db = new DB();

$tblName = 'user_links';

// Set default redirect url
$redirectURLDataLink = 'folderDataList.php';


    if(isset($_POST['userfolderDataMediaSubmit']) && isset($_FILES['image_file'])){

        include 'dataListDb.php';

        echo "<pre>";
        print_r($_FILES['image_file']);
        echo "</pre>";

        
        $img_name = $_FILES['image_file']['name'];
        $img_size = $_FILES['image_file']['size'];
        $tmp_name = $_FILES['image_file']['tmp_name'];
        $error = $_FILES['image_file']['error'];

        if($error === 0){
            if($img_size > 125000){
                $em = "Sorry your file is too large!";
                header("Location: folderDataListMedia.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png");

                if(in_array($img_ex_lc, $allowed_exs)){
                    $new_img_name = uniqid("IMG-", true). '.' .$img_ex_lc;
                    $img_upload_path = 'uploaddatalistfolders/'. $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    //Insert into database

                } else {
                    $em = "You can't upload file of this type!";
                    header("Location: folderDataListMedia.php?error=$em");
                }
            }
        } else {
            $em = "Unknown error occured!";
            header("Location: folderDataListMedia.php?error=$em");
        }

        $image = $new_img_name;
        $url_name   = $_POST['url_name'];
        $category = $_POST['category'];
        $type_name  = 'Multimedia';
        $id     = $_POST['id'];


            // Submitted user data
        $userData = array(
            'image_file' => $image,
            'url_name'  => $url_name,
            'category' => $category,
            'type_name' => $type_name
        );
        
        // Store submitted data into session
        $sessData['postData'] = $userData;
        $sessData['postData']['id'] = $id;
    
    // ID query string
    $idStr = !empty($id)?'?id='.$id:'';

        // If the data is not empty
        if(!empty($image) && !empty($url_name) && !empty($category) && !empty($type_name)){
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
                    $redirectURLDataLink = 'folderDataListMedia.php'.$idStr;
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
                    $redirectURLDataLink = 'folderDataListMedia.php';
                }
            }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'All fields are mandatory, please fill all the fields.';
        
        // Set redirect url
        $redirectURLDataLink = 'folderDataListMedia.php'.$idStr;
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;
    } elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
        // Delete data
        $condition = array('id' => $_GET['id']);
        $delete = $db->delete($tblName, $condition);
        if($delete){
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg']  = 'User data has been deleted successfully.';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg']  = 'Some problem occurred, please try again.';
        }
        
        // Store status into the session
        $_SESSION['sessData'] = $sessData;
    }else {
        header("Location: folderDataListMedia.php");
    }


// Redirect the user
header("Location: ".$redirectURLDataLink);
exit();