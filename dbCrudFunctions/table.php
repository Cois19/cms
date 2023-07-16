<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$mode = $_POST['mode'];

if ($mode == 'isn') {
    $doId = $_POST['doId'];

    // get tdono and tpn from tdoc table
    $query7 = "SELECT tdono FROM tdoc WHERE que = $doId";
    $result7 = mysqli_query($conn, $query7);
    $row7 = mysqli_fetch_assoc($result7);
    $tdono = $row7['tdono'];

    $query2 = "SELECT que, tisn, tpn, tmodel FROM tisn WHERE tdono = '$tdono' AND tstatus = 1 ORDER BY que DESC";

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
} else if ($mode == 'do') {
    $query2 = "SELECT que, tdono, tpid, tpno, tpname, tpmodel, tqty, cd, 
                CASE
                    WHEN tstatus = 0 THEN 'INACTIVE'
                    WHEN tstatus = 1 THEN 'ON GOING'
                    WHEN tstatus = 2 THEN 'GR COMPLETE'
                END as 'status'
                FROM tdoc ORDER BY que DESC";

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
} else if ($mode == 'sum') {
    $tdono = $_POST['do'];
    $tpno = $_POST['pno'];
    $tpname = $_POST['pna'];
    $start = $_POST['startDate'];
    $end = $_POST['endDate'];

    $query2 = "SELECT que, tdono, tpid, tpno, tpname, tpmodel, tqty, cd, 
                CASE
                    WHEN tstatus = 0 THEN 'INACTIVE'
                    WHEN tstatus = 1 THEN 'ON GOING'
                    WHEN tstatus = 2 THEN 'GR COMPLETE'
                END as 'status'
                FROM tdoc 
                WHERE tdono = '$tdono' AND tpno = '$tpno' AND tpname = '$tpname' AND cd BETWEEN DATE('$start') AND DATE('$end')
                ORDER BY que DESC";


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

} else if ($mode == 'user') {
    $query2 = "SELECT uid, uname, ucost, uemail, 
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