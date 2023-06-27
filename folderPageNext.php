<?php
// Include pagination library file
include_once 'Pagination.class.php';

// Include database configuration file
require_once 'dbConfig.php';

// Set some useful configuration
$baseURLFOLDER = 'http://localhost/thesis/folders.php';
$limit = 5;

// Paging limit & offset
$offset = !empty($_GET['page'])?(($_GET['page']-1)*$limit):0;

// Count of all records
$query   = $db->query("SELECT COUNT(*) as rowNum FROM posts");
$result  = $query->fetch_assoc();
$rowCountFolder= $result['rowNum'];

// Initialize pagination class
$pagConfigFolder = array(
    'baseURLFOLDER' => $baseURLFOLDER,
    'totalRowsFolder'=>$rowCountFolder,
    'perPage'=>$limit
);
$paginationFolder =  new Pagination($pagConfigFolder);

// Fetch records based on the offset and limit
$query = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$limit");

if($query->num_rows > 0){
?>
    <!-- Display posts list -->
    <div class="post-list">
    <?php while($row = $query->fetch_assoc()){ ?>
        <div class="list-item">
            <a href="javascript:void(0);"><?php echo $row["title"]; ?></a>
        </div>
    <?php } ?>
    </div>
    
    <!-- Display pagination links -->
    <?php echo $paginationFolder->createFolders(); ?>
<?php } ?>