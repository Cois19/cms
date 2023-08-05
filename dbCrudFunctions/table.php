<?php
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$mode = $_POST['mode'];

if ($mode == 'isn') {
    include '../users/session.php';
    $doId = $_POST['doId'];

    // get tdono and tpn from tdoc table
    $query7 = "SELECT tdono FROM tdoc WHERE que = $doId";
    $result7 = mysqli_query($conn, $query7);
    $row7 = mysqli_fetch_assoc($result7);
    $tdono = $row7['tdono'];

    $query2 = "SELECT tisn.que, tisn.tisn, tisn.tpn, tdoc.tpname, tisn.tmodel FROM tisn JOIN tdoc on tisn.tdono = tdoc.tdono WHERE tisn.tdono = '$tdono' AND tisn.tstatus = 1 ORDER BY que DESC";

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
    include '../users/session.php';
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
        include '../users/session.php';

        $tdono = $_POST['do'];
        $tpno = $_POST['pno'];
        $tpname = $_POST['pna'];
        $start = $_POST['startDate'];
        $end = $_POST['endDate'];
        $tisn = $_POST['isn'];

        // Initialize an empty array to hold the conditions
        $conditions = array();

        if (!empty($tdono)) {
            $conditions[] = "tdoc.tdono = '$tdono'";
        }

        if (!empty($tpno)) {
            $conditions[] = "tdoc.tpno = '$tpno'";
        }

        if (!empty($tpname)) {
            $conditions[] = "tdoc.tpname = '$tpname'";
        }

        if (!empty($tisn)) {
            $conditions[] = "tisn.tisn = '$tisn'";
        }

        // Handle the start and end date conditions separately
        if (!empty($start) && !empty($end)) {
            $conditions[] = "DATE(tisn.cd) BETWEEN DATE('$start') AND DATE('$end')";
        } elseif (!empty($start)) {
            $conditions[] = "DATE(tisn.cd) >= DATE('$start')";
        } elseif (!empty($end)) {
            $conditions[] = "DATE(tisn.cd) <= DATE('$end')";
        }

        $conditions[] = "tisn.tstatus = 1";

        // Join the conditions with AND operator
        $whereClause = implode(' AND ', $conditions);

        $query2 = "SELECT tdoc.tdono, tisn.tisn, tdoc.tpid, tdoc.tpno, tdoc.tpname, tdoc.tpmodel, tdoc.tqty, tdoc.tbxcount, tdoc.tdate, tisn.cd, 
            CASE
                WHEN tdoc.tstatus = 0 THEN 'INACTIVE'
                WHEN tdoc.tstatus = 1 THEN 'ON GOING'
                WHEN tdoc.tstatus = 2 THEN 'GR COMPLETE'
            END as 'status'
            FROM tdoc JOIN tisn on tdoc.tdono = tisn.tdono";

        // Add the WHERE clause if there are conditions
        if (!empty($whereClause)) {
            $query2 .= " WHERE $whereClause";
        }

        $query2 .= " ORDER BY tisn.cd DESC";

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
} else if ($mode == 'user') {
    include '../users/session.php';
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