<?php include("config/db_conf.php"); ?>
<?php
flush();
if (isset($_POST["upload"])) {

    $total = count($_FILES['files']['name']);
    $dir = "uploadFiles";
    $filepaths = "";
    if (!file_exists($dir) && !is_dir($dir)) {
        mkdir($dir);
    }
    // Loop through each file
    for ($i = 0; $i < $total; $i++) {

        //Get the temp file path
        $tmpFilePath = $_FILES['files']['tmp_name'][$i];

        //Make sure we have a file path
        if ($tmpFilePath != "") {
            //Setup our new file path

            $newFilePath = "./{$dir}/" . $_FILES['files']['name'][$i];
            //Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                //Handle other code here
                if ($filepaths != '') $filepaths = $filepaths . ',' . $_FILES['files']['name'][$i];
                else $filepaths = $_FILES['files']['name'][$i];
            }
        }
    }
    if (!empty($filepaths) && isset($_POST['upload-id'])) {
        $sql = "insert into files_dir(id, dir) values('{$_POST['upload-id']}', '{$filepaths}')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Files uploaded and added to the DB successfully!')</script>";
        } else {
            echo "<script>alert('Some error occured while adding to the DB!')</script>";
        }
        $_POST = array();
        unset($_POST);
    }
}
?>
<?php
$sql = "select * from files_dir";
$results = mysqli_query($conn, $sql);
$results = (mysqli_fetch_all($results, MYSQLI_ASSOC));

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
    <div class="container mt-5">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="upload-id">Upload ID: </label>
                <input type="text" id="upload-id" name="upload-id" />
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="files" name="files[]" multiple>
                <label class="custom-file-label" for="files">Choose file</label>
            </div>
            <div class="container">
                <input class="btn btn-primary" type="submit" value="upload" name="upload" />
            </div>
        </form>
        <h2>Update Uploads</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>id</td>
                    <td>dir</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['dir'] ?></td>
                        <td><button class="btn btn-primary update" id="<?= $row['id'] ?>" value="update">Update</button< /td>
                        <td><button class="btn btn-secondary download" id="<?= $row['id'] ?>" value="download"><i class="fa fa-download"></i>Download</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(".custom-file-input").change(function() {

            var fileName = '';
            if (this.files.length > 1) {
                for (var i = 0; i < this.files.length; i++) {
                    if (fileName == '') {
                        fileName = this.files[i].name;
                        continue;
                    }
                    fileName += ', ' + this.files[i].name;
                    // alert(this.files.item(i).name); // alternatively
                }
            } else {
                var fileName = $(this).val().split("\\").pop();
            }



            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $(".update").click(function() {
            window.location.href = "/filesup/update.php?update=" + this.id;
        });

        $(".download").click(function() {
            window.open("/filesup/fileDownload.php?download=" + this.id, target="_blank");
        });
    </script>
</body>