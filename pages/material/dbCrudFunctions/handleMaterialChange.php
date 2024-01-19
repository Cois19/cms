<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

if (isset($_POST['material'])) {
    $selectedMaterial = $_POST['material'];

    // Perform your database query with the selected material
    $sql = "SELECT spq_pallet, spq_box, spq_inner, standardissue FROM mc_materialmaster WHERE material = '$selectedMaterial'";
    $result = mysqli_query($conn, $sql);

    // Fetch SPQ options and custom strings and return as JSON
    $options = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Customize this part based on your database structure
        $spqOptions = [
            'spq_box' => $row['spq_box'],
            'spq_pallet' => $row['spq_pallet'],
            'spq_inner' => $row['spq_inner'],
            'standardissue' => $row['standardissue']
        ];

        $options[] = [
            'value' => $row['spq_box'],
            'text' => 'SPQ Box : ' . $spqOptions['spq_box']
        ];

        $options[] = [
            'value' => $row['spq_pallet'],
            'text' => 'SPQ Pallet : ' . $spqOptions['spq_pallet']
        ];

        $options[] = [
            'value' => $row['spq_inner'],
            'text' => 'SPQ Inner : ' . $spqOptions['spq_inner']
        ];

        $options[] = [
            'value' => $row['standardissue'],
            'text' => 'Standard Issue: ' . $spqOptions['standardissue']
        ];
    }

    echo json_encode($options);
}


mysqli_close($conn);
?>