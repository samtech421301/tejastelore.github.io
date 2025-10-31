<?php include "config/db_conf.php" ?>
<?php
if(isset($_GET['download'])) {
    $sql = "SELECT * FROM files_dir WHERE id = " . $_GET['download'];
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $filenames = explode(',',$data['dir']);
        foreach( $filenames as $filename ) {
            $url = "uploadFiles/" . $filename;
            $file_contents = file_get_contents($url);
            header("Content-Type: {mime_content_type($url}");
            header("Content-Disposition: attachment; filename={$filename}");
            echo $file_contents;
        }

    }
}
?>