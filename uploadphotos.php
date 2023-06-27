
<?php

session_start();

include("connection.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="cssfolders/uploadphotos.css">
    <link rel="stylesheet" type="text/css" href="cssfolders/main.css">
    <link rel="stylesheet" type="text/css" href="cssfolders/fonts.css">
    <title>Image</title>
</head>
<body>
    <div class="image_container">
            <?php
                $sql = "SELECT * FROM archive_db ORDER BY id DESC";
                $res = mysqli_query($conn, $sql);

                if(mysqli_num_rows($res) > 0 ){
                    while($images = mysqli_fetch_assoc($res)){?>

                    <?php 
                        if(empty($images['image_file'])){
                            echo '<div class="images" style="display:none;"></div>';
                    ?>
                    <?php } else { ?>
                    <div class="images">
                        <img src="uploads/<?=$images['image_file']?>">
                        <div class="bolder"><?=$images['url_name']?></div>
                    </div>
                        <?php }?>
                <?php }}?>
        </div>
			<footer class="dash_footer lighter" style="bottom:0px;">Created by . Copyright &copy; 2021</footer>
		</div>	
    </div>
</body>
</html>