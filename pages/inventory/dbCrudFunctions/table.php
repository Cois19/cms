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
} else if ($mode == 'reporting') {
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

        if (!isset($_POST['periodFilter'])) {
            $response = array(
                'status' => 'period',
                'message' => 'Please Select Period!'
            );
        } else {
            $period = $_POST['periodFilter'];

            if (isset($_POST['areaFilter'])) {
                $area = $_POST['areaFilter'];
            }

            // Initialize an empty array to hold the conditions
            $conditions = array();

            if (!empty($period)) {
                $conditions[] = "tinventorytag.tperiodque = $period";
            }

            if (!empty($area)) {
                $conditions[] = "tarea.areaname = '$area'";
            }

            // Join the conditions with AND operator
            $whereClause = implode(' AND ', $conditions);

            $query2 = "SELECT DISTINCT tinventorytag.que, tinventorytag.tagno, tarea.owner, tinventorytag.areacode, tarea.areaname, 
                            tinventorytag.subloc, tpartmaster.account, tpartmaster.model, tpartmaster.partno, 
                            tpartmaster.partdesc, tinventorytag.qty, tinventorytag.uom, tinventorytag.cd
                        FROM tarea JOIN tinventorytag ON tinventorytag.areacode = tarea.areacode
                        JOIN tpartmaster ON tpartmaster.partno = tinventorytag.partno";

            // Add the WHERE clause if there are conditions
            if (!empty($whereClause)) {
                $query2 .= " WHERE $whereClause AND tinventorytag.tperiodque = $period AND tpartmaster.tperiodque = $period AND tarea.tperiodque = $period";
            }

            $query2 .= " ORDER BY tinventorytag.que DESC";
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
}

echo json_encode($response);

mysqli_close($conn);
?>