<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
$response = '';
$que = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $response = 'timeout'; // Set response as "timeout" for session timeout
    $que = 'timeout';
} else {
    include '../../../users/session.php';

    // Handle the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mode = $_POST['mode'];

        if ($mode == 'labelrule') {
            $model = $_POST["model"];
            $labelType = $_POST["labelType"];
            $ruleSeparator = $_POST["ruleSeparator"];
            $labelRemarks = $_POST["labelRemarks"];

            // Create an array to hold label rules
            $labelRules = [];

            for ($i = 1; isset($_POST["fixedLength$i"]); $i++) {
                $fixedLength = $_POST["fixedLength$i"];
                $requiredString = $_POST["requiredString$i"];
                $fixedString = $_POST["fixedString$i"];

                // Create an object to represent each label rule with a unique key
                $labelRule = [
                    "fixedLength" => $fixedLength,
                    "requiredString" => $requiredString,
                    "fixedString" => $fixedString,
                ];

                // Add the label rule object to the labelRules array with a unique key
                $labelRules["labelRule$i"] = $labelRule;
            }

            // Convert the labelRules array to a JSON string
            $labelRulesJson = json_encode($labelRules);

            // Insert the data into the MySQL table
            $query = "INSERT INTO tlabelrules (model, type, ruleseparator, labelrules, remarks, cd, cp) VALUES ('$model', '$labelType', '$ruleSeparator', '$labelRulesJson', '$labelRemarks', CURRENT_TIMESTAMP(), '$uid')";
            $result = mysqli_query($conn, $query);
            // echo $query;

            if ($result) {
                $response = 'success';
            } else {
                $response = 'queryfail';
            }
        } else if ($mode == 'labelscan') {
            $ruleQue = $_POST['ruleQue'];

            $query2 = "SELECT ruleseparator FROM tlabelrules WHERE que = $ruleQue";
            $result2 = mysqli_query($conn, $query2);
            $separator = mysqli_fetch_assoc($result2)[0];

            // echo $query2;

            // $query2 = 'INSERT INTO tlabel(value) VALUES("test")';
            // $result2 = mysqli_query($conn, $query2);

            if ($result2) {
                $response = 'success';
            } else {
                $response = 'queryfail';
            }
            
        }
    } else {
        $response = 'fail';
    }

}

$responseData = array(
    'status' => $response,
    'que' => $que
);

echo json_encode($responseData);

mysqli_close($conn);
?>