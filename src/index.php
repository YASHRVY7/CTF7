<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageUploader</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>ImageUploader</h1>
        <p>Upload your images securely!</p>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="file">Choose an image:</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>