<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("<p class='error'>Error uploading file!</p>");
    }

    // Get file extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    // Weak validation: only checks extension
    if (!in_array($ext, $allowed)) {
        die("<p class='error'>Only image files (.jpg, .jpeg, .png, .gif) are allowed!</p>");
    }

    // Move file to uploads directory
    $upload_dir = __DIR__ . '/uploads/';
    $file_path = $upload_dir . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $file_path);

    echo "<p class='success'>File uploaded! View it here: <a href='uploads/" . htmlspecialchars($file['name']) . "'>" . htmlspecialchars($file['name']) . "</a></p>";
} else {
    echo "<p class='error'>No file uploaded.</p>";
}
?>
<link rel="stylesheet" href="style.css">
