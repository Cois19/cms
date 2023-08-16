<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$mode = $_POST['mode'];

if ($mode == 'period') {
    include '../../../users/session.php';
    $query2 = "SELECT que, periodname, periodstart, periodend, description, remarks, 
                    CASE
                        WHEN status = 0 THEN 'INACTIVE'
                        ELSE 'ACTIVE'
                    END, cd FROM tperiod ORDER BY cd DESC";

    if ($select_result2 = mysqli_query($conn, $query2)) {
        $response1 = array();
        while ($row = mysqli_fetch_assoc($select_result2)) {
            $response1[] = array_values($row);
        }
        if (empty($response1)) {
            $response1 = null;
        }
        $response = array(
            'data' => $response1
        );
    } else {
        $response = array(
            'status' => 500,
            'message' => 'Error: ' . mysqli_error($conn)
        );
    }
} 

echo json_encode($response);

mysqli_close($conn);
?>