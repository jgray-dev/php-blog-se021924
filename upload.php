<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload new blog post</title>
    <link rel="stylesheet" href="styles.css">
    <script src="main.js"></script>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
</head>
<body style="background-color: #070707; color: white">
<h2>Upload new blog post</h2>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Title:<br>
    <label>
        <input type="text" name="title" id="title" required><br><br>
    </label>
    Content:<br>
    <label>
        <textarea name="content" id="content" rows="10" cols="70" required></textarea><br><br>
    </label>
    <input type="file" name="file" id="file" accept=".png, .jpg, .jpeg, .gif"><br><br>
    <input type="submit" name="submit" value="Submit">
</form>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $month = date('Y-m');
    $targetDir = 'content/' . $month . '/';

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $name = time();

    // Check if a file has been uploaded
    if (!empty($_FILES['file']['name'])) {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, $allowedExtensions)) {
            die("Error: Invalid file type.");
        }

        $tmpPath = $_FILES['file']['tmp_name'];
        $originalName = $_FILES['file']['name'];

        $ext = pathinfo($originalName, PATHINFO_EXTENSION);

        $targetFilePath = $targetDir . $name . '.' . $ext;

        if (move_uploaded_file($tmpPath, $targetFilePath)) {
            echo "Files uploaded successfully.";
        } else {
            echo "Failed to upload files.";
        }
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $postData =  $title . "\n\n" . $content;
    file_put_contents($targetDir . $name . '.txt', $postData);
    exit;
}
?>
<br>
<br>
Do NOT refresh the page after clicking submit. Image uploads may take a while.
</body>
</html>