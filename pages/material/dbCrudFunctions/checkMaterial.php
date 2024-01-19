<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$status = '';
$spq = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';
    $material = $_POST['material'];

    $query = "SELECT material FROM mc_materialmaster WHERE material = '$material'";
    $select_result = mysqli_query($conn, $query);
    if (mysqli_num_rows($select_result) > 0) {
        $status = 'success';

        $query2 = "SELECT spq_box, spq_pallet, spq_inner, standardissue FROM mc_materialmaster WHERE material = '$material'";
        $select_result2 = mysqli_query($conn, $query2);
        $row2 = mysqli_fetch_assoc($select_result2);

        $query3 = "SELECT spq_box, spq_pallet, spq_inner, standardissue FROM mc_materialmaster WHERE material = '$material'";
        $result3 = mysqli_query($conn, $query3);
        while ($row3 = mysqli_fetch_assoc($result3)) {
            $spq .= '<option value="' . $row3["spq_box"] . '">
                            ' . $row3["spq_box"] . ' - SPQ Box
                        </option>
                        <option value="' . $row3["spq_pallet"] . '">
                            ' . $row3["spq_pallet"] . ' - SPQ Pallet
                        </option>
                        <option value="' . $row3["spq_inner"] . '">
                            ' . $row3["spq_inner"] . ' - SPQ Inner
                        </option>
                        <option value="' . $row3["standardissue"] . '">
                            ' . $row3["standardissue"] . ' - Standard Issue
                        </option>';
        }
    } else {
        $status = 'fail';
    }
}

$responseData = array(
    'status' => $status,
    'spq' => $spq
);

echo json_encode($responseData);

mysqli_close($conn);
?>