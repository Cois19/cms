<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
require '../vendor/autoload.php'; // Include PhpSpreadsheet autoload file

use PhpOffice\PhpSpreadsheet\IOFactory;

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

    if ($mode == 'importpart') {
        if ($_FILES['partmaster']['error'] === UPLOAD_ERR_OK) {
            $period_que = $_POST['period_que'];
            $tempFile = $_FILES['partmaster']['tmp_name'];

            $spreadsheet = IOFactory::load($tempFile);
            $worksheet = $spreadsheet->getActiveSheet();

            $rowIndex = 0;

            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex++;

                if ($rowIndex <= 1) {
                    continue; // Skip first row
                }

                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $partno = $rowData[0]; // Value in the first column of Excel
                $partdesc = $rowData[1];
                $uom = $rowData[2];
                $account = $rowData[3];
                $model = $rowData[4];

                // Check if the tpn value exists in the tdatamaster table
                $checkSql = "SELECT * FROM tpartmaster WHERE partno = '$partno'";
                $result = mysqli_query($conn, $checkSql);

                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, skip this row
                    continue;
                }

                $insertSql = "INSERT INTO tpartmaster (partno, partdesc, uom, account, model, tperiodque, cp, cd) VALUES ('$partno', '$partdesc', '$uom', '$account', '$model', $period_que, '$uid', CURRENT_TIMESTAMP)";
                $result1 = mysqli_query($conn, $insertSql);

                if ($result1 != 1) {
                    error_log("Error in insert query: " . mysqli_error($conn));
                } else {
                    $response = "success";
                    // Insert into tlog
                    $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT PART MASTER', '$partno', CURRENT_TIMESTAMP, '$uid')";
                    $result2 = mysqli_query($conn, $query2);

                    // Retrieve que for the newly inserted data
                    $query3 = "SELECT que FROM tpartmaster WHERE partno = '$partno' LIMIT 1";
                    $result3 = mysqli_query($conn, $query3);
                    $row = mysqli_fetch_assoc($result3);
                    $que = $row['que'];
                }
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'importarea') {
        if ($_FILES['area']['error'] === UPLOAD_ERR_OK) {
            $period_que = $_POST['period_que'];
            $tempFile = $_FILES['area']['tmp_name'];

            $spreadsheet = IOFactory::load($tempFile);
            $worksheet = $spreadsheet->getActiveSheet();

            $rowIndex = 0;

            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex++;

                if ($rowIndex <= 1) {
                    continue; // Skip first row
                }

                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $areacode = $rowData[0]; // Value in the first column of Excel
                $areaname = $rowData[1];
                $owner = $rowData[2];

                // Check if the tpn value exists in the tdatamaster table
                $checkSql = "SELECT * FROM tarea WHERE areacode = '$areacode'";
                $result = mysqli_query($conn, $checkSql);

                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, skip this row
                    continue;
                }

                $insertSql = "INSERT INTO tarea (areacode, areaname, owner, tperiodque) VALUES ('$areacode', '$areaname', '$owner', $period_que)";
                $result1 = mysqli_query($conn, $insertSql);

                if ($result1 != 1) {
                    error_log("Error in insert query: " . mysqli_error($conn));
                } else {
                    $response = "success";
                    // Insert into tlog
                    $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT AREA', '$areacode', CURRENT_TIMESTAMP, '$uid')";
                    $result2 = mysqli_query($conn, $query2);

                    // Retrieve que for the newly inserted data
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