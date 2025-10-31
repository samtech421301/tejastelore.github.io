<?php include("config/db_conf.php"); ?>
<?php
if (isset($_GET["update"])) {
    $id = $_GET["update"];
    $sql = "select * from files_dir where id = {$id}";
    $result = mysqli_query($conn, $sql);
    $fetched_assoc = (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : 'No data found';
    $filenames = explode(',', $fetched_assoc['dir']);
}
?>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<div class="container">

    <div class="container mt-5">
        <div id="update-board">
            <?php if (isset($fetched_assoc)) : ?>
                <form action="" method="post" enctype="multipart/form-data" class="row">
                    <div>
                        <label for="upload-id">Upload ID: </label>
                        <input type="text" id="upload-id" name="upload-id" value=<?= $fetched_assoc['id'] ?> disabled />
                    </div>
                    <div class="custom-file col-md-6">
                        <input type="file" class="form-control" id="files" name="files[]" multiple />
                    </div>
                    <div class="col-md-6 file-btn-collection">
                        <?php if ($filenames > 1) : ?>
                            <?php foreach ($filenames as $filename) : ?>
                                <span class="file-btn-span">
                                    <button class="btn btn-outline-dark" onclick="javascript: window.open('/filesup/uploadFiles/<?= $filename ?>', '_blank')" value='<?=$filename?>'>
                                        <?= substr($filename, 0, 4) . '..' . substr($filename, -5) ?>
                                    </button>
                                    <button type="button" class="btn-close btn-sm" aria-label="Close" value=<?= $filename ?>></button>
                                    &nbsp;
                                </span>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <a href="/filesup/uploadFiles/<?= $filenames ?>" target="_blank"><?= $filenames ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="container">
                        <input type="submit" value="upload" name="upload" />
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    // New file buttons generator after new files are uploaded
    $('.form-control').change(function() {
        let filepaths = '';
        for (var i = 0; i < this.files.length; i++) {
            let btnOpen = "<button class=\"btn btn-outline-dark\">" + this.files[i].name.slice(0, 3) + ".." + this.files[i].name.slice(-5);
            let btnClose = "</button>";
            let xbtn = "<button class=\"btn-close btn-sm\" aria-label=\"Close\"></button>&nbsp;";
            $('.file-btn-collection').append(btnOpen, btnClose, xbtn);
        }

    })

    $(".btn-close").click(function() {
        let fName = $(this).siblings('.btn-outline-dark').attr('value');
        let conf = confirm("Are you sure you want to delete " + fName + "?");
        if (conf) {
            $(this).parent().detach();
            console.log('/filesup/fileHandler.php?id=' + $("#upload-id").val() + '&fileName=' + encodeURIComponent(fName))
            $.ajax({
                type: 'GET',
                url: '/filesup/fileHandler.php?id=' + $("#upload-id").val() + '&fileName=' + encodeURIComponent(fName),
                success: function(data) {
                    // If you want, alert whatever your PHP script outputs
                    alert("deleted successfully!");
                    // $(this).parent().detach();
                },
                error: function(xhr, status, error) {
                    console.error(xhr, error);
                }
            });
        }
    })
</script>