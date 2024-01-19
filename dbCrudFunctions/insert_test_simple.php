<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
// require '../vendor/autoload.php'; // Include PhpSpreadsheet autoload file

// use PhpOffice\PhpExcel\Classes\PHPExcel\IOFactory;
// include '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

$response = '';
$que = '';
$mode = $_POST['mode'];

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $response = 'timeout'; // Set response as "timeout" for session timeout
    $que = 'timeout';
} else {
    include '../users/session.php';

    if ($mode == 'importarea') {
        $areaData = json_decode($_POST['excelData'], true); // Decode the JSON data sent from the client
    
        if (!empty($areaData)) {
            $period_que = $_POST['period_que'];
    
            foreach ($areaData as $rowData) {
                $areacode = $rowData[0]; // Assuming the first column contains 'areacode'
                $areaname = $rowData[1];
                $owner = $rowData[2];
    
                // Check if the area code already exists in the tarea table
                $checkSql = "SELECT * FROM tarea WHERE areacode = '$areacode'";
                $result = mysqli_query($conn, $checkSql);
    
                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, skip this row
                    continue;
                }
    
                // Insert the data into the tarea table
                $insertSql = "INSERT INTO tarea (areacode, areaname, owner, tperiodque) VALUES ('$areacode', '$areaname', '$owner', $period_que)";
                $result1 = mysqli_query($conn, $insertSql);
    
                if ($result1 != 1) {
                    error_log("Error in insert query: " . mysqli_error($conn));
                } else {
                    $response = "success";
                    // Insert into tlog
                    $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT AREA', '$areacode', CURRENT_TIMESTAMP, '$uid')";
                    $result2 = mysqli_query($conn, $query2);
    
                    // Retrieve the 'que' for the newly inserted data
                    $query3 = "SELECT que FROM tarea WHERE areacode = '$areacode' LIMIT 1";
                    $result3 = mysqli_query($conn, $query3);
                    $row = mysqli_fetch_assoc($result3);
                    $que = $row['que'];
                }
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    }
}

$responseData = array(
    'status' => $response,
    'que' => $que
);

echo json_encode($responseData);

mysqli_close($conn);
?>