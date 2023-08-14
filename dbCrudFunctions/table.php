<?php
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$mode = $_POST['mode'];

if ($mode == 'user') {
    include '../users/session.php';
    $query2 = "SELECT uid, uname, ucost, usection, uemail, 
                CASE
                    WHEN utype = 1 THEN 'ADMIN'
                    WHEN utype = 2 THEN 'PIC'
                    WHEN utype = 3 THEN 'USER'
                END as utype, remarks FROM tuser ORDER BY que DESC";

    if ($select_result2 = mysqli_query($conn, $query2)) {
        $response1 = array();
        while ($row = mysqli_fetch_assoc($select_result2)) {
            $response1[] = array_values($row);
        }

        if (empty($response1)) {
            $response = array(
                'data' => null
            );
        } else {
            $response = array(
                'data' => $response1
            );
        }
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