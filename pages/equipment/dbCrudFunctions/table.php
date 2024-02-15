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
} else if ($mode == 'equmaster') {
    if (!isset($_SESSION)) {
        session_start();
    }
    $inactive_timeout = 900; // 15 minutes in seconds
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
        session_unset(); // Unset all session variables if needed
        session_destroy(); // Destroy the session if needed

        $response = array(
            'status' => 'timeout',
            'message' => 'timeout'
        );
    } else {
        include '../../../users/session.php';

        $t_owner = $_POST['t_owner'];

        // Initialize an empty array to hold the conditions
        $conditions = array();

        if (!empty($t_owner)) {
            $conditions[] = "owner = '$t_owner'";
        }

        // Join the conditions with AND operator
        $whereClause = implode(' AND ', $conditions);

        $query2 = "SELECT que, owner, model, location, station, description, device_id, int_asset, temp_asset, cust_asset, main_asset
                        FROM equ_master";

        // Add the WHERE clause if there are conditions
        if (!empty($whereClause)) {
            $query2 .= " WHERE $whereClause";
        }

        $query2 .= " ORDER BY cd DESC";
        // echo $query2;

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
}

echo json_encode($response);

mysqli_close($conn);
