<?php
// checkFileUploadStatus.php

session_start();

// Check if the file upload is complete
if (isset($_SESSION['file_upload_status']) && $_SESSION['file_upload_status'] === 'complete') {
    echo 'complete';
} else {
    echo 'incomplete';
}
?>
