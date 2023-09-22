<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
ini_set('memory_limit', '256M');

$mode = $_POST['mode'];

if ($mode == 'isn') {
    include '../../../users/session.php';
    $doId = $_POST['doId'];
    $doStatus = $_POST['doStatus'];

    if ($doStatus == 1) {
        $query2 = "SELECT tisn.que, tisn.tisn, tisn.tpn, tdoc.tpname, tisn.tmodel FROM tisn JOIN tdoc on tisn.tdoc_que = tdoc.que WHERE tisn.tdoc_que = $doId AND tisn.tstatus = 1 ORDER BY que DESC";
    } else if ($doStatus != 1) {
        $query2 = "SELECT tisn_sum.que, tisn_sum.tisn, tisn_sum.tpn, tdoc.tpname, tisn_sum.tmodel FROM tisn_sum JOIN tdoc on tisn_sum.tdoc_que = tdoc.que WHERE tisn_sum.tdoc_que = $doId AND tisn_sum.tstatus = 1 ORDER BY que DESC";
    }

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
    include '../../../users/session.php';
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
        include '../../../users/session.php';

        $tdono = $_POST['do'];
        $tisn = $_POST['isn'];
        $tpid = $_POST['pid'];
        $tpno = $_POST['pno'];
        $tpname = $_POST['pna'];
        $cp = $_POST['pic'];
        $start = $_POST['startDate'];
        $end = $_POST['endDate'];

        // Initialize an empty array to hold the conditions
        $conditions = array();

        if (!empty($tdono)) {
            $conditions[] = "tdoc.tdono = '$tdono'";
        }

        if (!empty($tisn)) {
            $conditions[] = "tisn_sum.tisn = '$tisn'";
        }

        if (!empty($tpid)) {
            $conditions[] = "tdoc.tpid = '$tpid'";
        }

        if (!empty($tpno)) {
            $conditions[] = "tdoc.tpno = '$tpno'";
        }

        if (!empty($tpname)) {
            $conditions[] = "tdoc.tpname = '$tpname'";
        }

        if (!empty($cp)) {
            $conditions[] = "tisn_sum.cp = '$cp'";
        }

        // Handle the start and end date conditions separately
        if (!empty($start) && !empty($end)) {
            $conditions[] = "DATE(tisn_sum.cd) BETWEEN DATE('$start') AND DATE('$end')";
        } elseif (!empty($start)) {
            $conditions[] = "DATE(tisn_sum.cd) >= DATE('$start')";
        } elseif (!empty($end)) {
            $conditions[] = "DATE(tisn_sum.cd) <= DATE('$end')";
        }

        $conditions[] = "tisn_sum.tstatus = 1 AND tdoc.tstatus != 0";

        // Join the conditions with AND operator
        $whereClause = implode(' AND ', $conditions);

        $query2 = "SELECT tdoc.tdono, tisn_sum.tisn, tdoc.tpid, tdoc.tpno, tdoc.tpname, tdoc.tpmodel, tdoc.tdate, tisn_sum.cd, tuser.uname,
                    CASE
                        WHEN tdoc.tstatus = 0 THEN 'INACTIVE'
                        WHEN tdoc.tstatus = 1 THEN 'ON GOING'
                        WHEN tdoc.tstatus = 2 THEN 'GR COMPLETE'
                    END as 'status'
                    FROM tdoc JOIN tisn_sum on tdoc.que = tisn_sum.tdoc_que
                    JOIN tuser ON tisn_sum.cp = tuser.uid";

        // Add the WHERE clause if there are conditions
        if (!empty($whereClause)) {
            $query2 .= " WHERE $whereClause";
        }

        $query2 .= " ORDER BY tisn_sum.cd DESC";

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
} else if ($mode == 'isl') {
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

        $isl = $_POST['isl'];
        $isl_isn = $_POST['isl_isn'];
        $isl_pno = $_POST['isl_pno'];
        $model = $_POST['model'];
        $isl_pid = $_POST['isl_pid'];

        // Initialize an empty array to hold the conditions
        $conditions = array();

        if (!empty($isl)) {
            $conditions[] = "tshipping.isl = '$isl'";
        }

        if (!empty($isl_isn)) {
            $conditions[] = "tshipping.messageDetailSN = '$isl_isn'";
        }

        if (!empty($isl_pno)) {
            $conditions[] = "tshipping.partNumber = '$isl_pno'";
        }

        if (!empty($model)) {
            $conditions[] = "tshipping.CustomerProject = '$tpmodelno'";
        }

        if (!empty($isl_pid)) {
            $conditions[] = "tshipping.palletId = '$isl_pid'";
        }

        // Join the conditions with AND operator
        $whereClause = implode(' AND ', $conditions);

        $query2 = "SELECT isl, messageDetailSN, partNumber, CustomerProject, palletId FROM tshipping";

        // Add the WHERE clause if there are conditions
        if (!empty($whereClause)) {
            $query2 .= " WHERE $whereClause";
        }

        $query2 .= " ORDER BY que DESC";

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
    include '../../../users/session.php';
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

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>