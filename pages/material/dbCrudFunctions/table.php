<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$mode = $_POST['mode'];

if ($mode == 'materialDoc') {
    include '../../../users/session.php';
    $query2 = "SELECT
                    mchu.doc,
                    COUNT(mchu.d_hu) AS 'TOTAL QTY',
                    SUM(CASE WHEN mchu.status = 0 THEN 1 ELSE 0 END) AS 'PENDING',
                    SUM(CASE WHEN mchu.status = 1 THEN 1 ELSE 0 END) AS 'RECEIVED',
                    CASE
                        WHEN mcd.`status` = 0 THEN 'ON GOING'
                        WHEN mcd.`status` = 1 THEN 'GR COMPLETE'
                        ELSE 'UNKNOWN'
                    END AS 'STATUS',
                    mcd.cd
                FROM mc_hu mchu JOIN mc_doc mcd ON mchu.doc = mcd.doc
                GROUP BY mcd.doc
                ORDER BY mcd.cd DESC
                LIMIT 5000";

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
} else if ($mode == 'materialDhu') {
    include '../../../users/session.php';
    $newdoc = $_POST['newdoc'];
    $query2 = "SELECT
                    mchu.d_hu,
                    mchu.doc,
                    mcm.wo,
                    COUNT(mcm.d_hu) AS 'TOTAL QTY',
                    SUM(CASE WHEN mcm.status = 0 THEN 1 ELSE 0 END) AS 'PENDING',
                    SUM(CASE WHEN mcm.status = 1 THEN 1 ELSE 0 END) AS 'RECEIVED',
                    CASE
                        WHEN mchu.`status` = 0 THEN 'ON GOING'
                        WHEN mchu.`status` = 1 THEN 'GR COMPLETE'
                        ELSE 'UNKNOWN'
                    END AS 'STATUS',
                    mchu.cd
                FROM mc_materialmaster mcm JOIN mc_hu mchu ON mcm.d_hu = mchu.d_hu
                WHERE mcm.doc = '$newdoc'
                GROUP BY mchu.d_hu
                ORDER BY mchu.cd DESC
                LIMIT 5000";

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
} else if ($mode == 'ps_materialDhu') {
    include '../../../users/session.php';
    $newdoc = $_POST['newdoc'];
    $query2 = "SELECT
                    mchu.d_hu,
                    mchu.doc,
                    mcm.wo,
                    COUNT(mcm.d_hu) AS 'TOTAL QTY',
                    SUM(CASE WHEN mcm.status = 0 THEN 1 ELSE 0 END) AS 'PENDING',
                    SUM(CASE WHEN mcm.status = 1 THEN 1 ELSE 0 END) AS 'RECEIVED',
                    CASE
                        WHEN mchu.`status` = 0 THEN 'ON GOING'
                        WHEN mchu.`status` = 1 THEN 'GR COMPLETE'
                        ELSE 'UNKNOWN'
                    END AS 'STATUS',
                    mchu.cd
                FROM mc_materialmaster mcm JOIN mc_hu mchu ON mcm.d_hu = mchu.d_hu
                WHERE mcm.doc = '$newdoc'
                GROUP BY mchu.d_hu
                ORDER BY mchu.cd DESC
                LIMIT 5000";

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
} else if ($mode == 'materialDetails') {
    $dhu = $_POST['dhu'];
    include '../../../users/session.php';
    $query2 = "SELECT que, split_id, s_hu, material, `description`, batch, qty, uom,
                    CASE
                        WHEN `status` = 0 THEN 'ON GOING'
                        WHEN `status` = 1 THEN 'GR COMPLETE'
                        ELSE 'UNKNOWN'
                    END AS 'STATUS',
                    cd,
                    receiver
                FROM mc_materialmaster
                WHERE d_hu = '$dhu'
                ORDER BY cd DESC;";

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
} else if ($mode == 'ps_materialDetails') {
    $dhu = $_POST['dhu'];
    include '../../../users/session.php';
    $query2 = "SELECT que, split_id, s_hu, material, `description`, batch, qty, uom,
                    CASE
                        WHEN `status` = 0 THEN 'ON GOING'
                        WHEN `status` = 1 THEN 'GR COMPLETE'
                        ELSE 'UNKNOWN'
                    END AS 'STATUS',
                    cd,
                    receiver
                FROM mc_materialmaster
                WHERE d_hu = '$dhu'
                ORDER BY cd DESC;";

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
} else if ($mode == 'transaction') {
    include '../../../users/session.php';
    $query2 = "SELECT idx, type, hu, material, fullbox, qty, lossqty, totalqty, location, linecode, cd FROM mc_transaction LIMIT 5000";

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
} else if ($mode == 'transactionReport') {
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

        if (
            !empty($_POST['material']) || !empty($_POST['location'])
            || !empty($_POST['startDate']) || !empty($_POST['endDate'])
        ) {
            $material = $_POST['material'];
            $location = $_POST['location'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];

            // Initialize an empty array to hold the conditions
            $conditions = array();

            if (!empty($material)) {
                $conditions[] = "material = '$material'";
            }

            if (!empty($location)) {
                $conditions[] = "location = '$location'";
            }

            // Handle the start and end date conditions separately
            if (!empty($startDate) && !empty($endDate)) {
                $conditions[] = "DATE(cd) BETWEEN DATE('$startDate') AND DATE('$endDate')";
            } elseif (!empty($startDate)) {
                $conditions[] = "DATE(cd) >= DATE('$startDate')";
            } elseif (!empty($endDate)) {
                $conditions[] = "DATE(cd) <= DATE('$endDate')";
            }

            // Join the conditions with AND operator
            $whereClause = implode(' AND ', $conditions);

            $query2 = "SELECT idx, type, doc, wo, s_hu, material, description, batch, uom, d_hu, qty, location, cd FROM mc_transaction";

            // Add the WHERE clause if there are conditions
            if (!empty($whereClause)) {
                $query2 .= " WHERE $whereClause";
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
        } else {
            $response = array(
                'status' => 'empty',
                'message' => 'empty'
            );
        }
    }
}


echo json_encode($response);

mysqli_close($conn);
