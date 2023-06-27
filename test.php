<?php

if(isset($_POST['folder_name'])){
    $db_name = $_POST['folder_name'];
    $db_name = strtolower($db_name);
    $db_name = preg_replace("/[^a-z]/", '', $db_name);


    if($db_name != '') {

        $db_host = "localhost";
        $username = "root";
        $password = "";
        //  Create the database
        $mysqli = new mysqli($db_host, $username, $password);
 
        if ($mysqli->connect_error) {
            throw new Exception("Connect Error ($mysqli->connect_errno) $mysqli->connect_error");
        }
        $query = "CREATE DATABASE `$db_name`";
        $mysqli->query($query);
        if($mysqli->errno) {
            throw new Exception("DATABASE ALREADY EXISTED: $mysqli->error");
            // Catch the error of the database already existing
        }
        echo "Database $db_name created.";
     } else {
        throw new Exception("Invalid name");
     }
}

?>






<?php

$db = mysqli_connect("localhost","root", "", "archiving_thesis");
$sql = "SELECT * FROM archive_db";
$result = mysqli_query($db, $sql);
while($row = mysqli_fetch_array($result)){
    echo "<div id='img_div'>";
        echo "<img src = 'archive_db/".$row['image_file']."'>";
    echo "</div>";
}



?>