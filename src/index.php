<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>CTF Challenge</h1>
        <p>Upload your images securely! Can you find the flag?</p>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="file">Choose an image:</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Upload</button>
        </form>
        <div class="hint-section">
            <button onclick="getHint(1)">Hint 1</button>
            <button onclick="getHint(2)">Hint 2</button>
            <button onclick="getHint(3)">Hint 3</button>
            <div id="hint-display"></div>
        </div>
    </div>
    <script>
    function getHint(level) {
        fetch(`hint.php?level=${level}`)
            .then(response => response.json())
            .then(data => {
                const hintDisplay = document.getElementById('hint-display');
                if (data.hint) {
                    hintDisplay.textContent = data.hint;
                } else {
                    hintDisplay.textContent = data.error;
                }
            });
    }
    </script>
</body>
</html>
