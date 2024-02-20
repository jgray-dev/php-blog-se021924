<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>jgray.cc - blog</title>
    <link rel="stylesheet" href="styles.css">
    <script src="main.js"></script>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
</head>
<body style="background-color: #070707; color: white">

<?php
ob_start(); // Start output buffering

$contentDirectories = 'content';
$allFilesAndDirectories = scandir($contentDirectories);
$filesAndDirectories = array_diff($allFilesAndDirectories, array('.', '..'));
$directoriesOnly = array_filter($filesAndDirectories, function($file) use ($contentDirectories) {
    return is_dir($contentDirectories . '/' . $file);
});

// Sort directories in reverse order to display the most recent posts at the top
rsort($directoriesOnly);
?>

<div class="nav">
    <?php foreach ($directoriesOnly as $dir): ?>
        <?php
        $filename = pathinfo($dir, PATHINFO_FILENAME);
        $link = '#' . strtolower(str_replace(' ', '', $dir));
        ?>
        
        <a href="<?php echo $link; ?>" class="gradient"><?php echo $dir; ?></a>
    <?php endforeach; ?>
</div>

<br>
<br>
<?php foreach ($directoriesOnly as $dir): ?>
    <?php
    $dirName = pathinfo($dir, PATHINFO_FILENAME);
    $postFiles = glob($contentDirectories . '/' . $dir . '/*.{png,jpg,jpeg,gif,txt}', GLOB_BRACE);

    // Separate text files and image files
    $textFiles = array_filter($postFiles, function($file) {
        return pathinfo($file, PATHINFO_EXTENSION) == 'txt';
    });
    $imageFiles = array_diff($postFiles, $textFiles);

    // Sort the files in reverse order based on their modification time
    usort($textFiles, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    usort($imageFiles, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    ?>
    
    <div id="<?php echo strtolower(str_replace(' ', '', $dirName))?>" class="section">
        <h1 class="gradient"><?php echo $dirName ?></h1>
        
        <div class="post-grid">
            <?php foreach ($textFiles as $postFile): ?>
                <div class="post-container">
                    <?php
                    $postData = file_get_contents($postFile);
                    $postDataParts = explode("\n\n", $postData);
                    $title = trim($postDataParts[0]);
                    $content = $postDataParts[1];

                    // Get the file modification time
                    $timestamp = filemtime($postFile);

                    // Create a DateTime object from the timestamp
                    $datetime = new DateTime("@$timestamp");

                    // Set the time zone to MST
                    $datetime->setTimezone(new DateTimeZone('America/Denver'));

                    // Format the date and time
                    $formattedTimestamp = $datetime->format('h:i A m/d');

                    echo '<div style="display: flex; align-items: baseline; justify-content: center;">';
                    echo '<h2 class="gradient-title">' . $title . '</h2>';
                    echo '<p style="margin-left: 10px;">' . $formattedTimestamp . '</p>';
                    echo '</div>';
                    echo '<p>' . $content . '</p>';

                    // Display the image that has the same name as the text file
                    $imageName = pathinfo($postFile, PATHINFO_FILENAME);
                    foreach ($imageFiles as $imageFile) {
                        if (pathinfo($imageFile, PATHINFO_FILENAME) == $imageName) {
                            echo '<img src="' . $imageFile . '" alt="Image" width="425px" height="auto">';
                            break;
                        }
                    }
                    echo '<br><br><br><br><br>';
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
<div class="devoliolink">
    <a href="/devolio">Check out my developer portfolio</a>
</div>
<div class="section">
    <a href="upload.php" target="_blank" class="upload-button" style="color: #171717">Upload</a></div>
</body>
</html>

<?php
ob_end_flush(); // End output buffering and send output to client
?>




