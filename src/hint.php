<?php
session_start();

$hints = [
    1 => "Check the MIME type bypass techniques",
    2 => "The flag is hidden deeper than you think",
    3 => "Multiple encodings might be involved"
];

if (isset($_GET['level']) && isset($hints[$_GET['level']])) {
    echo json_encode(['hint' => $hints[$_GET['level']]]);
} else {
    echo json_encode(['error' => 'Invalid hint level']);
} 