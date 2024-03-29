<?php
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$response = '';
$que = '';
$tpid = '';
$tpno = '';
$mode = $_POST['mode'];

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
    include '../users/session.php';

    if ($mode == 'dorder') {
        if (!empty($_POST['do'])) {
            $do = explode(',', $_POST['do']);

            $palletid = $do[0];
            $partno = $do[1];

            // get partname
            $query4 = "SELECT tpname, tpmodel FROM tdatamaster WHERE tpn = '$partno'";
            $result4 = mysqli_query($conn, $query4);
            $row4 = mysqli_fetch_assoc($result4);
            $partname = $row4['tpname'];
            $tpmodel = $row4['tpmodel'];

            $dnnumber = $do[3];
            $qty = $do[4];
            $boxcount = $do[5];
            $date = $do[6];

            // Check if the same data already exists in tdoc table
            $query = "SELECT que, tpid, tpno FROM tdoc WHERE tpid = '$palletid' AND tpno = '$partno' AND tpname = '$partname' AND tdate = '$date' AND tpmodel = '$tpmodel' AND tstatus != 0 LIMIT 1";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Data already exists, set response as "fail" and retrieve que
                $response = "fail";
                $row = mysqli_fetch_assoc($result);
                $que = $row['que'];
                $tpid = $row['tpid'];
                $tpno = $row['tpno'];
            } else {
                $query1 = '';
                if (strlen($palletid) == 16) { // PTB
                    $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, tstatus, tpmodel, tvendor, tcost, cd, cp) 
                    VALUES('$palletid', '$partno', '$partname', '$dnnumber', '$qty', '$boxcount', '$date', '1', '$tpmodel', 'PTB', '$ucost', CURRENT_TIMESTAMP, '$uid')";
                } else if (strlen($palletid) == 8) { // SMT
                    if (!empty($_POST['shipping'])) {
                        // Retrieve the 'shipment_list_id' value from the query string
                        $shipment_list_id = $_POST['shipping'];

                        // Construct the API URL
                        $url = 'http://snws07:8000/api/MES/Ext/GetSMTShipmentDetail';

                        // Create an array of data to send in the POST request
                        $data = array('shipment_list_id' => $shipment_list_id);

                        // Create a context for the POST request
                        $options = array(
                            'http' => array(
                                'method' => 'POST',
                                'header' => 'Content-type: application/x-www-form-urlencoded',
                                'content' => http_build_query($data)
                            )
                        );
                        $context = stream_context_create($options);

                        // Make the POST request and retrieve the api_response
                        $api_response = file_get_contents($url, false, $context);

                        if ($api_response === false) {
                            // Error handling if the request fails
                            $response = 'api';
                        } else {
                            // Decode the JSON api_response
                            $responseData = json_decode($api_response, true);

                            // Extract the data within the 'DATA' field
                            $data = json_decode($responseData['DATA'], true);

                            // Initialize an empty query string
                            $query7 = "INSERT INTO tshipping (isl, messageDetailSN, partNumber, CustomerProject, palletId, cp) VALUES ";

                            if ($data !== null && isset($data[0]['shippingNoticeDetails'])) {
                                foreach ($data[0]['shippingNoticeDetails'] as $shippingNotice) {
                                    $messageDetailSN = $shippingNotice['messageDetailSN'];
                                    $partNumber = $shippingNotice['partNumber'];
                                    $CustomerProject = $shippingNotice['CustomerProject'];

                                    // Append the values for each row to the query string
                                    $query7 .= "('$shipment_list_id', '$messageDetailSN', '$partNumber', '$CustomerProject', '$palletid', '$uid'),";
                                }

                                // Remove the trailing comma
                                $query7 = rtrim($query7, ",");
                            } else {
                                error_log("Error in ISL Fetching");
                            }

                            // Execute the query
                            $result7 = mysqli_query($conn, $query7);
                        }

                        if ($result7 != 1) {
                            $response = 'islerror';
                        } else {
                            $query9 = "SELECT COUNT(*) FROM tshipping WHERE isl = '$shipment_list_id'";
                            $result9 = mysqli_query($conn, $query9);
                            $numofisl = mysqli_fetch_array($result9)[0];

                            if ($numofisl != $qty) {
                                $response = 'islqty';

                                $query10 = "DELETE FROM tshipping WHERE isl = '$shipment_list_id'";
                                $result10 = mysqli_query($conn, $query10);
                            } else {
                                $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, tstatus, tpmodel, tvendor, tcost, cd, cp) 
                                    VALUES('$palletid', '$partno', '$partname', '$palletid', '$qty', '$boxcount', '$date', '1', '$tpmodel', 'SMT', '$ucost', CURRENT_TIMESTAMP, '$uid')";
                            }
                        }
                    } else {
                        $response = 'emptyshipping';
                    }
                }

                if (!empty($query1)) {
                    $result1 = mysqli_query($conn, $query1);

                    if ($result1 != 1) {
                        error_log("Error in insert query: " . mysqli_error($conn));
                    } else {
                        // Data inserted successfully, set response as "success"
                        $response = "success";

                        // Insert into tlog
                        $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT TDOC', '$palletid', CURRENT_TIMESTAMP, '$uid')";
                        $result2 = mysqli_query($conn, $query2);

                        // Retrieve que for the newly inserted data
                        $query3 = "SELECT que, tpid, tpno FROM tdoc WHERE tpid = '$palletid' AND tpno = '$partno' AND tpname = '$partname' AND tqty = '$qty' AND tdate = '$date' ORDER BY que DESC LIMIT 1";
                        $result3 = mysqli_query($conn, $query3);
                        $row = mysqli_fetch_assoc($result3);
                        $que = $row['que'];
                        $tpid = $row['tpid'];
                        $tpno = $row['tpno'];
                    }
                }
            }
        } else {
            $response = "empty";
        }
    } else if ($mode == 'period') {
        if (!empty($_POST['periodname']) && !empty($_POST['startDate']) && !empty($_POST['endDate']) && !empty($_POST['description']) && !empty($_POST['p_remarks'])) {
            if (strlen($_POST['periodname']) != 5) {
                $response = "length";
                $que = "length";
            } else {
                $periodname = $_POST['periodname'];
                $startDate = $_POST['startDate'];
                $endDate = $_POST['endDate'];
                $description = $_POST['description'];
                $p_remarks = $_POST['p_remarks'];

                // Check if an active period already exists
                $query = "SELECT * FROM tperiod WHERE status = 1";
                $result = mysqli_query($conn, $query);

                // if (mysqli_num_rows($result) > 0) {
                //     // Data already exists, set response as "fail" and retrieve que
                //     $response = "fail";
                //     $row = mysqli_fetch_assoc($result);
                //     $que = $row['que'];
                // } else {
                    $query1 = "INSERT INTO tperiod(periodname, periodstart, periodend, description, remarks, status, cd, cp) 
                    VALUES('$periodname', '$startDate', '$endDate', '$description', '$p_remarks', '1', CURRENT_TIMESTAMP, '$uid')";
                    $result1 = mysqli_query($conn, $query1);

                    if ($result1 != 1) {
                        error_log("Error in insert query: " . mysqli_error($conn));
                    } else {
                        // Data inserted successfully, set response as "success"
                        $response = "success";

                        // Insert into tlog
                        $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT PERIOD', '$periodname', CURRENT_TIMESTAMP, '$uid')";
                        $result2 = mysqli_query($conn, $query2);

                        // Retrieve que for the newly inserted data
                        $query3 = "SELECT que FROM tperiod WHERE periodname = '$periodname' and status = 1 LIMIT 1";
                        $result3 = mysqli_query($conn, $query3);
                        $row = mysqli_fetch_assoc($result3);
                        $que = $row['que'];
                    }
                // }
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'importpart') {
        $partData = json_decode($_POST['excelData'], true); // Decode the JSON data sent from the client

        if (!empty($partData)) {
            $period_que = $_POST['period_que'];
            $firstRowSkipped = false; // Track if the first row has been skipped

            $insertSql = "INSERT INTO tpartmaster (partno, partdesc, uom, account, model, tperiodque, cp, cd) VALUES ";
            foreach ($partData as $rowData) {
                if (!$firstRowSkipped) {
                    // Skip the first row (header row)
                    $firstRowSkipped = true;
                    continue;
                }

                $partno = $rowData[0]; // Assuming the first column contains 'partno'
                $partdesc = $rowData[1];
                $uom = $rowData[2];
                $account = $rowData[3];
                $model = $rowData[4];

                // Check if the part number already exists in the tpartmaster table
                $checkSql = "SELECT * FROM tpartmaster WHERE partno = '$partno' AND tperiodque = $period_que";
                $result = mysqli_query($conn, $checkSql);

                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, skip this row
                    continue;
                }

                // Insert the data into the tpartmaster table
                $insertSql .= "('$partno', '$partdesc', '$uom', '$account', '$model', $period_que, '$uid', CURRENT_TIMESTAMP),";
            }

            // Remove the trailing comma
            $insertSql = rtrim($insertSql, ",");
            $result1 = mysqli_query($conn, $insertSql);

            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                $response = "success";
                // Insert into tlog
                $query2 = "INSERT INTO tlog(tprocess, var1, cd, cp) VALUES('INSERT PART MASTER', '$period_que', CURRENT_TIMESTAMP, '$uid')";
                $result2 = mysqli_query($conn, $query2);

                // Retrieve the 'que' for the newly inserted data
                $query3 = "SELECT que FROM tpartmaster WHERE partno = '$partno' LIMIT 1";
                $result3 = mysqli_query($conn, $query3);
                $row = mysqli_fetch_assoc($result3);
                $que = $row['que'];
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'importsapequipment') {
        $partData = json_decode($_POST['excelData'], true); // Decode the JSON data sent from the client

        if (!empty($partData)) {
            $firstRowSkipped = false; // Track if the first row has been skipped

            $insertSql = "INSERT INTO mc_linecode (linecode, floor) VALUES ";
            // $insertSql = "INSERT INTO tisn_sum (tdono, tisn, tmodel, tstatus, tvendor, tcost, tdoc_que, cp, cd, tpn) VALUES ";
            foreach ($partData as $rowData) {
                if (!$firstRowSkipped) {
                    // Skip the first row (header row)
                    $firstRowSkipped = true;
                    continue;
                }

                $linecode = mysqli_real_escape_string($conn, $rowData[0]); // Assuming the first column contains 'no'
                // $projectcode = $rowData[1]; 
                // $category = $rowData[2];
                // $line = $rowData[3];
                $floor = mysqli_real_escape_string($conn, $rowData[1]);
                // $objecttype = $rowData[5];
                // $descriptiontype = mysqli_real_escape_string($conn, $rowData[2]);
                // $station = $rowData[7];
                // $deviceid = $rowData[8];
                // $consumable = $rowData[9];
                // $qty = $rowData[10];
                // $location = $rowData[11];

                // $tdono = $rowData[0];
                // $tisn = $rowData[1];
                // $tmodel = $rowData[2];
                // $tstatus = $rowData[3];
                // $tvendor = $rowData[4];
                // $tcost = $rowData[5];
                // $tdoc_que = $rowData[6];
                // $cp = $rowData[7];
                // $cd = $rowData[8];
                // $tpn = $rowData[9];

                // Check if the part number already exists in the tpartmaster table
                // $checkSql = "SELECT * FROM tsapequipment WHERE ";
                // $result = mysqli_query($conn, $checkSql);

                // if (mysqli_num_rows($result) > 0) {
                //     // Data already exists, skip this row
                //     continue;
                // }

                // Insert the data into the tpartmaster table
                $insertSql .= "('$linecode', '$floor'),";
                // $insertSql .= "('$tdono', '$tisn', '$tmodel', $tstatus, '$tvendor', '$tcost', $tdoc_que, '$cp', '$cd', '$tpn'),";
            }

            // Remove the trailing comma
            $insertSql = rtrim($insertSql, ",");
            $result1 = mysqli_query($conn, $insertSql);

            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                $response = "success";
                // Insert into tlog
                // $query2 = "INSERT INTO tlog(tprocess, var1, cd, cp) VALUES('INSERT SAP EQUIPMENT', '$period_que', CURRENT_TIMESTAMP, '$uid')";
                // $result2 = mysqli_query($conn, $query2);

                // Retrieve the 'que' for the newly inserted data
                // $query3 = "SELECT que FROM tpartmaster WHERE partno = '$partno' LIMIT 1";
                // $result3 = mysqli_query($conn, $query3);
                // $row = mysqli_fetch_assoc($result3);
                // $que = $row['que'];
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'importarea') {
        $areaData = json_decode($_POST['excelData'], true); // Decode the JSON data sent from the client

        if (!empty($areaData)) {
            $period_que = $_POST['period_que'];
            $firstRowSkipped = false; // Track if the first row has been skipped

            $insertSql = "INSERT INTO tarea (areacode, areaname, owner, tperiodque) VALUES ";
            foreach ($areaData as $rowData) {
                if (!$firstRowSkipped) {
                    // Skip the first row (header row)
                    $firstRowSkipped = true;
                    continue;
                }

                $areacode = $rowData[0]; // Assuming the first column contains 'areacode'
                $areaname = $rowData[1];
                $owner = $rowData[2];

                // Check if the area code already exists in the tarea table
                $checkSql = "SELECT * FROM tarea WHERE areacode = '$areacode' AND tperiodque = $period_que";
                $result = mysqli_query($conn, $checkSql);

                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, skip this row
                    continue;
                }

                // Insert the data into the tarea table
                $insertSql .= "('$areacode', '$areaname', '$owner', $period_que),";
            }

            // Remove the trailing comma
            $insertSql = rtrim($insertSql, ",");
            $result1 = mysqli_query($conn, $insertSql);

            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                $response = "success";
                // Insert into tlog
                $query2 = "INSERT INTO tlog(tprocess, var1, cd, cp) VALUES('INSERT AREA', '$period_que', CURRENT_TIMESTAMP, '$uid')";
                $result2 = mysqli_query($conn, $query2);

                // Retrieve the 'que' for the newly inserted data
                $query3 = "SELECT que FROM tarea WHERE areacode = '$areacode' LIMIT 1";
                $result3 = mysqli_query($conn, $query3);
                $row = mysqli_fetch_assoc($result3);
                $que = $row['que'];
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'inventorytag') {
        if (!empty($_POST['areacode']) && !empty($_POST['tagno']) && !empty($_POST['subloc']) && !empty($_POST['partno']) && !empty($_POST['qty']) && !empty($_POST['uom'])) {
            $period_que = $_POST['period_que'];
            $areacode = $_POST['areacode'];
            $tagno = $_POST['tagno'];
            $subloc = $_POST['subloc'];
            $partno = $_POST['partno'];
            $qty = $_POST['qty'];
            $uom = $_POST['uom'];
            $tagremarks = $_POST['tagremarks'];

            // Check if part no exists
            $query = "SELECT partno FROM tpartmaster WHERE partno = '$partno' AND tperiodque = $period_que";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                // part no doesn't exist
                $response = "fail";
            } else {
                // Check if tag no exists
                $query5 = "SELECT tagno FROM tinventorytag WHERE tagno = '$tagno' AND tperiodque = $period_que";
                $result5 = mysqli_query($conn, $query5);

                if (mysqli_num_rows($result5) > 0) {
                    // tag no doesn't exist
                    $response = "tagnoduplicate";
                } else {
                    $query1 = "INSERT INTO tinventorytag(areacode, tagno, subloc, partno, qty, uom, tag_remarks, tperiodque, cd, cp) 
                    VALUES('$areacode', '$tagno', '$subloc', '$partno', '$qty', '$uom', '$tagremarks', '$period_que', CURRENT_TIMESTAMP, '$uid')";
                    $result1 = mysqli_query($conn, $query1);

                    if ($result1 != 1) {
                        error_log("Error in insert query: " . mysqli_error($conn));
                    } else {
                        // Data inserted successfully, set response as "success"
                        $response = "success";

                        // Insert into tlog
                        $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT INVENTORY TAG', '$tagno', CURRENT_TIMESTAMP, '$uid')";
                        $result2 = mysqli_query($conn, $query2);

                        // Retrieve que for the newly inserted data
                        $query3 = "SELECT que FROM tinventorytag WHERE tagno = '$tagno' and tperiodque = $period_que LIMIT 1";
                        $result3 = mysqli_query($conn, $query3);
                        $row = mysqli_fetch_assoc($result3);
                        $que = $row['que'];
                    }
                }
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    } else if ($mode == 'importmaterial') {
        $materialData = json_decode($_POST['excelData'], true); // Decode the JSON data sent from the client

        if (!empty($materialData)) {
            $firstRowSkipped = false; // Track if the first row has been skipped

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
                    $i_idx .= $lastIdx_YY . $lastIdx_MM . $lastIdx_DD . $lastIdx_counter;
                }
            } else {
                $i_idx .= '0001'; // default if there is no existing idx
            }

            $insertSql = "INSERT INTO mc_materialmaster (doc, wo, s_hu, material, description, batch, uom, d_hu, qty, status, cd, cp) VALUES ";
            $plusW_transactionSql = "INSERT INTO mc_transaction (idx, type, doc, wo, s_hu, material, description, batch, uom, d_hu, qty, location, cp) VALUES ";
            foreach ($materialData as $rowData) {
                if (!$firstRowSkipped) {
                    // Skip the first row (header row)
                    $firstRowSkipped = true;
                    continue;
                }

                $doc = $rowData[0];
                $wo = $rowData[1];
                $s_hu = $rowData[2];
                $material = $rowData[3];
                $description = $rowData[4];
                $batch = $rowData[5];
                $uom = $rowData[6];
                $d_hu = $rowData[7];
                $qty = $rowData[8];

                // Check if $doc is empty
                if (empty($doc) || empty($s_hu) || empty($material) || empty($description) || empty($d_hu) || empty($qty)) {
                    $response = "emptydoc";
                    
                    $responseData = array(
                        'status' => $response
                    );
                    
                    echo json_encode($responseData);

                    exit(); // or return; if this code is inside a function
                }


                // Check if this incoming exists in the mc_materialmaster table
                $checkSql = "SELECT * FROM mc_materialmaster WHERE d_hu = '$d_hu' 
                            AND s_hu = '$s_hu' AND doc = '$doc' AND material = '$material'
                            AND qty = $qty";
                $result = mysqli_query($conn, $checkSql);

                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, skip this row
                    continue;
                }

                // Increment $i_idx for each row
                $nextIdxCounter = str_pad(substr($i_idx, -4) + 1, 4, '0', STR_PAD_LEFT);
                $i_idx = substr($i_idx, 0, 8) . $nextIdxCounter;

                // Insert the data into the mc_materialmaster table
                $insertSql .= "('$doc', '$wo', '$s_hu', '$material', '$description', '$batch', '$uom', '$d_hu', $qty, 0, CURRENT_TIMESTAMP, '$uid'),";
                $plusW_transactionSql .= "('$i_idx', 0, '$doc', '$wo', '$s_hu', '$material', '$description', '$batch', '$uom', '$d_hu', $qty, 'W001', '$uid'),";
            }
            // echo $insertSql;

            // Remove the trailing comma
            $insertSql = rtrim($insertSql, ",");
            $result1 = mysqli_query($conn, $insertSql);
            $plusW_transactionSql = rtrim($plusW_transactionSql, ",");
            // echo $plusW_transactionSql;
            $plusW_transactionResult = mysqli_query($conn, $plusW_transactionSql);

            // Extract unique d_hu & doc values from the inserted data
            $uniqueDhuValues = array_unique(array_column($materialData, 7));
            $uniqueDocValues = array_unique(array_column($materialData, 0));

            $firstRowSkipped1 = false;
            $firstRowSkipped2 = false;

            // Initialize query
            $insertHuSql = '';
            $resultHu = '';
            $insertDocSql = '';
            $resultDoc = '';

            // Insert unique d_hu values into mc_hu
            foreach ($uniqueDhuValues as $uniqueDhu) {
                if (!$firstRowSkipped1) {
                    // Skip the first row (header row)
                    $firstRowSkipped1 = true;
                    continue;
                }

                // Find the corresponding doc for the current unique d_hu
                $key = array_search($uniqueDhu, array_column($materialData, 7)); // 7 is the index of d_hu in $rowData
                $docForDhu = $materialData[$key][0]; // 0 is the index of doc in $rowData

                // Check if the d_hu already exists in mc_hu
                $checkHuSql = "SELECT * FROM mc_hu WHERE d_hu = '$uniqueDhu'";
                $resultCheckHu = mysqli_query($conn, $checkHuSql);

                if (mysqli_num_rows($resultCheckHu) > 0) {
                    // d_hu already exists, skip this row
                    continue;
                }

                // Insert d_hu along with the corresponding doc into mc_hu
                $insertHuSql = "INSERT INTO mc_hu (d_hu, doc, status, cd, cp) VALUES ('$uniqueDhu', '$docForDhu', 0, CURRENT_TIMESTAMP, '$uid')";
                $resultHu = mysqli_query($conn, $insertHuSql);
            }

            // Insert unique doc values into mc_doc
            foreach ($uniqueDocValues as $uniqueDoc) {
                if (!$firstRowSkipped2) {
                    // Skip the first row (header row)
                    $firstRowSkipped2 = true;
                    continue;
                }

                // Check if the doc already exists in mc_doc
                $checkHuSql = "SELECT * FROM mc_doc WHERE doc = '$uniqueDoc'";
                $resultCheckHu = mysqli_query($conn, $checkHuSql);

                if (mysqli_num_rows($resultCheckHu) > 0) {
                    // doc already exists, skip this row
                    continue;
                }

                $insertHuSql = "INSERT INTO mc_doc (doc, status, cd, cp) VALUES ('$uniqueDoc', 0, CURRENT_TIMESTAMP, '$uid')";
                $resultHu = mysqli_query($conn, $insertHuSql);
            }

            if ($result1 != 1 && $resultHu != 1 && $resultDoc != 1 && $plusW_transactionResult != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                $response = "success";

                // Insert into tlog
                $query2 = "INSERT INTO tlog(tprocess, tdata, var1, cd, cp) VALUES('IMPORT MATERIAL MASTER', '$d_hu', '$material', CURRENT_TIMESTAMP, '$uid')";
                $result2 = mysqli_query($conn, $query2);
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
    'tpid' => $tpid,
    'tpno' => $tpno
);

echo json_encode($responseData);

mysqli_close($conn);
