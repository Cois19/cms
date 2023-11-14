<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$que = $_POST['que'];
$status = '';
$data = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';
    $areaCode = $_POST['areaCode'];

    $query = "SELECT periodname FROM tperiod WHERE que = $que";
    if ($select_result = mysqli_query($conn, $query)) {
        $status = 'success';

        $row = mysqli_fetch_assoc($select_result);
        $period = $row['periodname'];

        $data .= $period . '-' . $areaCode . '-';
        
        // Fetch the last tag number from the database
        $lastTagNoQuery = "SELECT tagno FROM tinventorytag WHERE tperiodque = $que ORDER BY que DESC LIMIT 1";
        $lastTagNoResult = mysqli_query($conn, $lastTagNoQuery);
        if ($lastTagNoRow = mysqli_fetch_assoc($lastTagNoResult)) {
            $lastTagNo = $lastTagNoRow['tagno'];
            $tagNumber = (int)substr($lastTagNo, -4); // Extract the last 4 digits as integer
            $nextTagNumber = str_pad($tagNumber + 1, 4, '0', STR_PAD_LEFT); // Increment and pad with leading zeros
            $data .= $nextTagNumber;
        } else {
            $data .= '0001'; // Default if no existing tag numbers
        }
    }
}

$responseData = array(
    'status' => $status,
    'data' => $data
);

echo json_encode($responseData);

mysqli_close($conn);
?>