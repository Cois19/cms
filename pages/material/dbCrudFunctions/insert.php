<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
$response = '';
$que = '';
$qty = '';

function getParts($string, $positions)
{
    $parts = array();

    foreach ($positions as $position) {
        $parts[] = substr($string, 0, $position);
        $string = substr($string, $position);
    }

    return $parts;
}

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $response = 'timeout'; // Set response as "timeout" for session timeout
    $que = 'timeout';
} else {
    include '../../../users/session.php';
    $mode = $_POST['mode'];

    if ($mode == 'inputMaterial') {
        if (!empty($_POST['material']) && !empty($_POST['m_description']) && !empty($_POST['spq_pallet']) && !empty($_POST['spq_box']) && !empty($_POST['spq_inner']) && !empty($_POST['standardissue'])) {
            $material = $_POST['material'];
            $m_description = $_POST['m_description'];
            $spq_pallet = $_POST['spq_pallet'];
            $spq_box = $_POST['spq_box'];
            $spq_inner = $_POST['spq_inner'];
            $standardissue = $_POST['standardissue'];

            // Check if an active period already exists
            $query = "SELECT * FROM mc_materialmaster WHERE material = '$material'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Data already exists, set response as "fail" and retrieve que
                $response = "fail";
                $row = mysqli_fetch_assoc($result);
                $que = $row['que'];
            } else {
                $query1 = "INSERT INTO mc_materialmaster (material, description, spq_pallet, spq_box, spq_inner, standardissue, cd, cp)
                            VALUES ('$material', '$m_description', '$spq_pallet', '$spq_box', '$spq_inner', '$standardissue', CURRENT_TIMESTAMP, '$uid')";
                $result1 = mysqli_query($conn, $query1);

                if ($result1 != 1) {
                    error_log("Error in insert query: " . mysqli_error($conn));
                } else {
                    // Data inserted successfully, set response as "success"
                    $response = "success";

                    // Insert into tlog
                    $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INPUT MATERIAL MASTER', '$material', CURRENT_TIMESTAMP, '$uid')";
                    $result2 = mysqli_query($conn, $query2);

                    // Retrieve que for the newly inserted data
                    // $query3 = "SELECT que FROM tperiod WHERE periodname = '$periodname' and status = 1 LIMIT 1";
                    // $result3 = mysqli_query($conn, $query3);
                    // $row = mysqli_fetch_assoc($result3);
                    // $que = $row['que'];
                }
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'inputTransaction') {
        if (
            !empty($_POST['i_hu']) && !empty($_POST['i_material']) && !empty($_POST['i_fullbox'])
            && !empty($_POST['i_spq']) && !empty($_POST['i_qty']) && !empty($_POST['i_totalqty'])
        ) {
            $i_hu = $_POST['i_hu'];
            $i_material = $_POST['i_material'];
            $i_fullbox = $_POST['i_fullbox'];
            $i_spq = $_POST['i_spq'];
            $i_qty = $_POST['i_qty'];
            $i_lossqty = 0;
            if (!empty($_POST['i_lossqty'])) {
                $i_lossqty = $_POST['i_lossqty'];
            }
            $i_totalqty = $_POST['i_totalqty'];

            $idx_ID = 'ID';
            $idx_DD = date("d");
            $idx_MM = date("m");
            $idx_YY = date("y");
            $i_idx = '';
            $i_idx .= $idx_ID . $idx_YY . $idx_MM . $idx_DD;

            // Fetch the last tag number from the database
            $lastIdxQuery = "SELECT idx FROM mc_transaction ORDER BY que DESC";
            $lastIdxResult = mysqli_query($conn, $lastIdxQuery);
            if ($lastIdxRow = mysqli_fetch_assoc($lastIdxResult)) {
                $lastIdx = $lastIdxRow['idx'];

                // check if the latest idx is today or yesterday
                $lastIdxParts = getParts($lastIdx, array(2, 2, 2, 2, 4));
                $lastIdx_YY = $lastIdxParts[1];
                $lastIdx_MM = $lastIdxParts[2];
                $lastIdx_DD = $lastIdxParts[3];
                $lastIdx_counter = $lastIdxParts[4];

                if ($lastIdx_YY != $idx_YY || $lastIdx_MM != $idx_MM || $lastIdx_DD != $idx_DD) {
                    $i_idx .= '0001'; // reset counter to 0001 if last idx is not today
                } else {
                    $nextIdxCounter = str_pad($lastIdx_counter + 1, 4, '0', STR_PAD_LEFT); // Increment and pad with leading zeros

                    $i_idx .= $nextIdxCounter;
                }
            } else {
                $i_idx .= '0001'; // default if there is no existing idx
            }

            $query1 = "INSERT INTO mc_transaction(idx, type, hu, material, fullbox, qty, lossqty, totalqty, location, cd, cp) 
                    VALUES('$i_idx', 'Prod Store GR', '$i_hu', '$i_material', $i_fullbox, $i_qty, $i_lossqty, $i_totalqty, 'P001', CURRENT_TIMESTAMP, '$uid')";
            $result1 = mysqli_query($conn, $query1);

            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                // Data inserted successfully, set response as "success"
                $response = "success";

                // // Insert into tlog
                // $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT INVENTORY TAG', '$tagno', CURRENT_TIMESTAMP, '$uid')";
                // $result2 = mysqli_query($conn, $query2);

                // // Retrieve que for the newly inserted data
                // $query3 = "SELECT que FROM tinventorytag WHERE tagno = '$tagno' and tperiodque = $period_que LIMIT 1";
                // $result3 = mysqli_query($conn, $query3);
                // $row = mysqli_fetch_assoc($result3);
                // $que = $row['que'];
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'materialTransaction') {
        if (!empty($_POST['que'])) {
            $que = $_POST['que'];

            $idx_ID = 'ID';
            $idx_DD = date("d");
            $idx_MM = date("m");
            $idx_YY = date("y");
            $o_idx = '';
            $o_idx .= $idx_ID . $idx_YY . $idx_MM . $idx_DD;

            // Fetch the last tag number from the database
            $lastIdxQuery = "SELECT idx FROM mc_transaction ORDER BY que DESC LIMIT 1";
            $lastIdxResult = mysqli_query($conn, $lastIdxQuery);

            if ($lastIdxRow = mysqli_fetch_assoc($lastIdxResult)) {
                $lastIdx = $lastIdxRow['idx'];

                // check if the latest idx is today or yesterday
                $lastIdxParts = getParts($lastIdx, array(2, 2, 2, 2, 4));
                $lastIdx_YY = $lastIdxParts[1];
                $lastIdx_MM = $lastIdxParts[2];
                $lastIdx_DD = $lastIdxParts[3];
                $lastIdx_counter = $lastIdxParts[4];

                if ($lastIdx_YY != $idx_YY || $lastIdx_MM != $idx_MM || $lastIdx_DD != $idx_DD) {
                    $lastIdx_counter = 1; // reset counter to 1 if last idx is not today
                }

                // Increment and pad with leading zeros for each row
                $nextIdxCounter = str_pad($lastIdx_counter, 4, '0', STR_PAD_LEFT);

                $query1 = "INSERT INTO mc_transaction(idx, type, doc, wo, s_hu, material, description, batch, uom, d_hu, qty, location, cp)
                            SELECT '$o_idx" . str_pad($lastIdx_counter + 1, 4, '0', STR_PAD_LEFT) . "', 0, mcm.doc, mcm.wo, mcm.s_hu, mcm.material, mcm.description, mcm.batch, mcm.uom, mcm.d_hu, mcm.qty, 'P001', '$uid'
                            FROM mc_materialmaster mcm
                            WHERE mcm.que = '$que'
                            UNION
                            SELECT '$o_idx" . str_pad($lastIdx_counter + 2, 4, '0', STR_PAD_LEFT) . "', 0, mcm.doc, mcm.wo, mcm.s_hu, mcm.material, mcm.description, mcm.batch, mcm.uom, mcm.d_hu, (-1 * mcm.qty), 'W001', '$uid'
                            FROM mc_materialmaster mcm
                            WHERE mcm.que = '$que'";
            }

            // Execute the query1
            $result1 = mysqli_query($conn, $query1);

            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                $updateStatusQuery = "UPDATE mc_materialmaster SET status = 1 WHERE que = '$que'";
                mysqli_query($conn, $updateStatusQuery);
                $response = "success";

                // check if all materials are received
                $getDhuQuery = "SELECT d_hu FROM mc_materialmaster WHERE que = '$que' LIMIT 1";
                $getDhuResult = mysqli_query($conn, $getDhuQuery);
                $dhu = mysqli_fetch_array($getDhuResult)[0];

                $getPendingQtyQuery = "SELECT
                                            mchu.d_hu,
                                            SUM(CASE WHEN mcm.status = 0 THEN 1 ELSE 0 END) AS 'PENDING'
                                        FROM mc_materialmaster mcm JOIN mc_hu mchu ON mcm.d_hu = mchu.d_hu
                                        WHERE mchu.d_hu = '$dhu'
                                        GROUP BY mchu.d_hu
                                        ORDER BY mchu.cd DESC
                                        LIMIT 5000";
                $getPendingQtyResult = mysqli_query($conn, $getPendingQtyQuery);
                $qty = mysqli_fetch_array($getPendingQtyResult)[1];

                // update mc_hu
                if ($qty == 0) {
                    $updateMc_hu = "UPDATE mc_hu SET status = 1 WHERE d_hu = '$dhu'";
                    mysqli_query($conn, $updateMc_hu);
                }

                // // Insert into tlog
                // $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT INVENTORY TAG', '$tagno', CURRENT_TIMESTAMP, '$uid')";
                // $result2 = mysqli_query($conn, $query2);
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } 
}

$responseData = array(
    'status' => $response,
    'que' => $que,
    'qty' => $qty
);

echo json_encode($responseData);

mysqli_close($conn);
