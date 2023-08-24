<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
ini_set('memory_limit', '256M');

$mode = $_POST['mode'];

if ($mode == 'isn') {
    include '../../../users/session.php';
    $doId = $_POST['doId'];

    // get tpid from tdoc table
    $query7 = "SELECT tpid FROM tdoc WHERE que = $doId";
    $result7 = mysqli_query($conn, $query7);
    $row7 = mysqli_fetch_assoc($result7);
    $tpid = $row7['tpid'];

    $query2 = "SELECT tisn.que, tisn.tisn, tisn.tpn, tdoc.tpname, tisn.tmodel FROM tisn JOIN tdoc on tisn.tdoc_que = tdoc.que WHERE tisn.tdoc_que = $doId AND tisn.tstatus = 1 ORDER BY que DESC";

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

        // Define your query
        $query = "SELECT (@row_number:=@row_number + 1) AS no, tdoc.tdono, tisn.tisn, tdoc.tpid, tdoc.tpno, tdoc.tpname, tdoc.tpmodel, tdoc.tdate, tisn.cd,
                    CASE
                        WHEN tdoc.tstatus = 0 THEN 'INACTIVE'
                        WHEN tdoc.tstatus = 1 THEN 'ON GOING'
                        WHEN tdoc.tstatus = 2 THEN 'GR COMPLETE'
                    END as 'status'
                    FROM tdoc JOIN tisn on tdoc.que = tisn.tdoc_que
                    CROSS JOIN (SELECT @row_number := 0) AS init
                    WHERE tisn.tstatus = 1 AND tdoc.tstatus != 0";


        // Implement filtering, if needed
        if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
            $searchValue = $_POST['search']['value'];
            $query .= " AND (tdoc.tdono LIKE '%$searchValue%' OR tisn.tisn LIKE '%$searchValue%' OR tdoc.tpid LIKE '%$searchValue%' OR tdoc.tpno LIKE '%$searchValue%' OR tdoc.tpname LIKE '%$searchValue%' OR tdoc.tpmodel LIKE '%$searchValue%' OR tdoc.tdate LIKE '%$searchValue%' OR tisn.cd LIKE '%$searchValue%' OR tdoc.tstatus LIKE '%$searchValue%')";
        }

        // Implement ordering
        $orderColumnIndex = $_POST['order'][0]['column'];
        $orderDirection = $_POST['order'][0]['dir'];
        $orderColumn = ['no', 'tdono', 'tisn', 'tpid', 'tpno', 'tpname', 'tpmodel', 'tdate', 'cd', 'tstatus'][$orderColumnIndex];
        $query .= " ORDER BY $orderColumn $orderDirection";

        // Implement paging
        $start = $_POST['start'];
        $length = $_POST['length'];
        $query .= " LIMIT $start, $length";

        // echo ($query);
        // var_dump($query);
        // Execute the query and fetch data
        $result = mysqli_query($conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        // Get the total count before filtering
        $totalCountQuery = "SELECT COUNT(*) as count FROM tdoc JOIN tisn on tdoc.que = tisn.tdoc_que WHERE tisn.tstatus = 1";
        $totalCountResult = mysqli_query($conn, $totalCountQuery);
        $totalCountRow = mysqli_fetch_assoc($totalCountResult);
        $totalRecords = $totalCountRow['count'];

        // Get the total count after filtering
        $totalFilteredQuery = "SELECT COUNT(*) as count FROM tdoc JOIN tisn on tdoc.que = tisn.tdoc_que WHERE tisn.tstatus = 1";
        if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
            $totalFilteredQuery .= " AND (tdoc.tdono LIKE '%$searchValue%' OR tisn.tisn LIKE '%$searchValue%' OR tdoc.tpid LIKE '%$searchValue%' OR tdoc.tpno LIKE '%$searchValue%' OR tdoc.tpname LIKE '%$searchValue%' OR tdoc.tpmodel LIKE '%$searchValue%' OR tdoc.tdate LIKE '%$searchValue%' OR tisn.cd LIKE '%$searchValue%' OR tdoc.tstatus LIKE '%$searchValue%')";
        }
        $totalFilteredResult = mysqli_query($conn, $totalFilteredQuery);
        $totalFilteredRow = mysqli_fetch_assoc($totalFilteredResult);
        $totalFilteredRecords = $totalFilteredRow['count'];

        // Prepare data for DataTables
        $response = [
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFilteredRecords,
            "data" => $data,
            "query" => $query
        ];
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