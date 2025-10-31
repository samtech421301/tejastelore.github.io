<?php include "config/db_conf.php" ?>
<?php 
if (isset($_GET["id"])) {
  // Do something with $data here
  $sql = "SELECT dir FROM files_dir where id = " . $_GET['id'];
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $fileList = explode(",",$row['dir']);
  $upload_dir = './uploadFiles/';
  // header('Content-type: application/json');
  // echo json_encode($fileList);
  $newFilePaths = '';
  foreach (array_diff($fileList, array($_GET['fileName'])) as $fn) {
    $newFilePaths !== '' ? $newFilePaths .= ',' . $fn : $newFilePaths .= $fn;
  }
  echo $newFilePaths . "<br />" ;
  $sql = "UPDATE files_dir SET dir = '" . $newFilePaths . "' WHERE id = " . $_GET['id'];
  $result = mysqli_query($conn, $sql);
  if(file_exists($upload_dir . $_GET['fileName']) && $result) {
    unlink($upload_dir . $_GET['fileName']);
    echo 'SUCCESS: file removed from db and server';
  } else {
    echo 'file removed from database not from server';
  }
} 
?>

