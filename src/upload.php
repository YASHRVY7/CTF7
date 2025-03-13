<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("<p class='error'>Error uploading file!</p>");
    }

    // Get file extension - modified to handle double extensions
    $filename = $file['name'];
    $ext = '';
    
    // Check if file ends with .jpg
    if (substr(strtolower($filename), -4) === '.jpg') {
        $ext = 'jpg';
    } else {
        die("<p class='error'>Invalid file extension!</p>");
    }

    // Move file to uploads directory with original name to preserve double extension
    $upload_dir = __DIR__ . '/uploads/';
    $file_path = $upload_dir . basename($filename);

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        echo "<p class='success'>File uploaded! View it here: <a href='uploads/" . htmlspecialchars($filename) . "'>" . htmlspecialchars($filename) . "</a></p>";
    } else {
        echo "<p class='error'>Failed to move uploaded file.</p>";
    }
} else {
    echo "<p class='error'>No file uploaded.</p>";
}
?>
<link rel="stylesheet" href="style.css">
