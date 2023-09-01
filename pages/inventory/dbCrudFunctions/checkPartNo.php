<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$status = '';
$uom = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';
    $partNo = $_POST['partNo'];
    $period_que = $_POST['que'];

    $query = "SELECT partno FROM tpartmaster WHERE partno = '$partNo' AND tperiodque = $period_que";
    $select_result = mysqli_query($conn, $query);
    if (mysqli_num_rows($select_result) > 0) {
        $status = 'success';

        $query2 = "SELECT uom FROM tpartmaster WHERE partno = '$partNo' AND tperiodque = $period_que";
        $select_result2 = mysqli_query($conn, $query2);
        $row2 = mysqli_fetch_assoc($select_result2);

        if ($row2['uom'] == 'PC') {
            $uom = '<option value="' . $row2["uom"] . '">
                        ' . $row2["uom"] . '
                    </option>';
        } else {
            $uom .= '<option disabled selected>Select UOM</option>';

            $query3 = "SELECT DISTINCT uom FROM tpartmaster";
            $result3 = mysqli_query($conn, $query3);
            while ($row3 = mysqli_fetch_assoc($result3)) {
                $selected = $row3['uom'] == $row2['uom'] ? 'selected' : '';
                $uom .= '<option value="' . $row3["uom"] . '" ' . $selected . '>
                            ' . $row3["uom"] . '
                        </option>';
            }
        }
    } else {
        $status = 'fail';
    }
}

$responseData = array(
    'status' => $status,
    'uom' => $uom
);

echo json_encode($responseData);

mysqli_close($conn);
?>