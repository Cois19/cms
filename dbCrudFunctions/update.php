<?php 
    session_start();
    include '../database/connect.php';
    include '../users/session.php';
    date_default_timezone_set("Asia/Jakarta");

    $mode = $_POST['mode'];
    if ($mode == 'update') {
        $ecdId = $_POST['e_ecd_id'];
        $detailsId = $_POST['e_details_id'];
        $model = $_POST['e_model'];
        $sixtypartnumber = $_POST['e_sixtypartnumber'];
        $partnumber = $_POST['e_partnumber'];
        $location = $_POST['e_location'];
        $checksum = $_POST['e_checksum'];
        $tpversion = $_POST['e_tpversion'];
        $burner = $_POST['e_burner'];
        $approval = isset($_POST['e_approval']) ? 1 : 0;
        $approvaldate = $_POST['e_approvaldate'];
        $verifiedby = $_POST['e_verifiedby'];
        $c_remarks = $_POST['e_c_remarks'];

        $att1_name = '';
        $att2_name = '';

        // Check if att1 file has been uploaded
        if (isset($_FILES['e_att1']) && $_FILES['e_att1']['error'] === UPLOAD_ERR_OK) {
            $att1_name = $_FILES['e_att1']['name'];
            $att1_tmp_name = $_FILES['e_att1']['tmp_name'];
            $att1_type = $_FILES['e_att1']['type'];
            $att1_size = $_FILES['e_att1']['size'];

            // Generate a unique ID
            $unique_id = uniqid();

            // Set the attachment path to "../attachments/"
            $attachments_dir = '../attachments/';

            // Add the '-' after the file name and then the unique ID
            $att1_extension = pathinfo($att1_name, PATHINFO_EXTENSION);
            $att1_name_without_extension = pathinfo($att1_name, PATHINFO_FILENAME);
            $att1_name = $att1_name_without_extension . '-' . $unique_id . '.' . $att1_extension;

            $att1_path = $attachments_dir . $att1_name;

            // Move the uploaded file to the attachments directory
            if (move_uploaded_file($att1_tmp_name, $att1_path)) {
                $att1_name = mysqli_real_escape_string($conn, $att1_name);
                // After moving the uploaded file
                $_SESSION['file_upload_status'] = 'complete';
            } else {
                error_log("Error in moving att1 file");
            }
        }

        // Check if att2 file has been uploaded
        if (isset($_FILES['e_att2']) && $_FILES['e_att2']['error'] === UPLOAD_ERR_OK) {
            $att2_name = $_FILES['e_att2']['name'];
            $att2_tmp_name = $_FILES['e_att2']['tmp_name'];
            $att2_type = $_FILES['e_att2']['type'];
            $att2_size = $_FILES['e_att2']['size'];

            // Generate a unique ID
            $unique_id = uniqid();

            // Set the attachment path to "../attachments/"
            $attachments_dir = '../attachments/';

            // Add the '-' after the file name and then the unique ID
            $att2_extension = pathinfo($att2_name, PATHINFO_EXTENSION);
            $att2_name_without_extension = pathinfo($att2_name, PATHINFO_FILENAME);
            $att2_name = $att2_name_without_extension . '-' . $unique_id . '.' . $att2_extension;

            $att2_path = $attachments_dir . $att2_name;

            // Move the uploaded file to the attachments directory
            if (move_uploaded_file($att2_tmp_name, $att2_path)) {
                $att2_name = mysqli_real_escape_string($conn, $att2_name);
                // After moving the uploaded file
                $_SESSION['file_upload_status'] = 'complete';
            } else {
                error_log("Error in moving att2 file");
            }
        }

        // Perform the database update query using the updated values
        $query = "UPDATE ecd SET 
                model = '$model',
                sixtypartnumber = '$sixtypartnumber',
                tpversion = '$tpversion',
                approval = '$approval',
                approvaldate = '$approvaldate',
                verifiedby = '$verifiedby',
                c_remarks = '$c_remarks',
                burner = '$burner'";

        if (!empty($att1_name)) {
            $query .= ", att1 = '$att1_name'";
        }

        if (!empty($att2_name)) {
            $query .= ", att2 = '$att2_name'";
        }

        $query .= " WHERE ecd_id = $ecdId";

        $query2 = "UPDATE ecd_details
                    SET partnumber = '$partnumber',
                    location = '$location',
                    checksum = '$checksum',
                    sixtypartnumber = '$sixtypartnumber'
                WHERE details_id = $detailsId";

        // Execute the update queries
        $result1 = mysqli_query($conn, $query);
        $result2 = mysqli_query($conn, $query2);

        // Check if the update was successful
        if ($result1 && $result2) {
            // Update successful
            // You can redirect the user or show a success message here
            if (!empty($att1_name) || !empty($att2_name)) {
                echo "success with file";
            } else {
                echo "success without file";
            }
        } else {
            // Update failed
            // You can redirect the user or show an error message here
            echo "failed";
        }
        exit;
    } 
?>

<!DOCTYPE html>
<html lang="en">
</head>

<body>
    <?php
    if (isset($_POST['mode'])) {
        $mode = $_POST['mode'];
        if ($mode == 'fetch') {
            $ecdid = $_POST['ecdId'];
            $detailsid = $_POST['detailsId'];
            $query = "SELECT ecd.*, ecd_details.* FROM ecd JOIN ecd_details on ecd.ecd_id = ecd_details.ecd_id WHERE ecd.ecd_id = $ecdid and ecd_details.details_id = $detailsid";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $ecd_id = $row['ecd_id'];
                $details_id = $row['details_id'];
                $model = $row['model'];
                $sixtypartnumber = $row['sixtypartnumber'];
                $tpversion = $row['tpversion'];
                $approval = $row['approval'];
                $approvaldate = $row['approvaldate'];
                $verifiedby = $row['verifiedby'];
                $c_remarks = $row['c_remarks'];
                $burner = $row['burner'];
                $partnumber = $row['partnumber'];
                $location = $row['location'];
                $checksum = $row['checksum'];
                $att1 = $row['att1'];
                $att2 = $row['att2'];
            }
            ?>

            <div class="form-group mb-3 d-none">
                <label for="e_ecd_id">ECD ID</label>
                <input type="text" class="form-control" name="e_ecd_id" id="e_ecd_id" placeholder="ID" value="<?php echo $ecd_id ?>" readonly>
            </div>
            <div class="form-group mb-3 d-none">
                <label for="e_details_id">ID</label>
                <input type="text" class="form-control" name="e_details_id" id="e_details_id" placeholder="ID" value="<?php echo $details_id ?>" readonly>
            </div>
            <div class="form-group mb-3" id="e_modelParent">
                <label for="e_model">Model</label>
                <select class="form-select" name="e_model" id="e_model">
                    <option disabled>Select Model</option>
                    <?php
                    $query2 = "SELECT * FROM d_model WHERE coid = 'PG18'";
                    $result2 = mysqli_query($conn, $query2);

                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $selected = ($row2['mname'] == $model) ? "selected" : ""; ?>
                        <option value="<?php echo $row2['mname'] ?>" <?php echo $selected ?>><?php echo $row2['mname'] ?></option>;
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="e_sixtypartnumber">60 Main Part Number</label>
                <input type="text" class="form-control" name="e_sixtypartnumber" id="e_sixtypartnumber" placeholder="60 Main Part Number"
                    value="<?php echo $sixtypartnumber ?>">
            </div>
            <div class="field-set row">
                <div class="form-group mb-3 col-md-4">
                    <label for="e_partnumber">Part Number</label>
                    <input type="text" class="form-control" name="e_partnumber" id="e_partnumber" placeholder="Part Number"
                        value="<?php echo $partnumber ?>">
                </div>
                <div class="form-group mb-3 col-md-4">
                    <label for="e_location">Location</label>
                    <input type="text" class="form-control" name="e_location" id="e_location" placeholder="Location"
                        value="<?php echo $location ?>">
                </div>
                <div class="form-group mb-3 col-md-4">
                    <label for="e_checksum">Checksum</label>
                    <input type="text" class="form-control" name="e_checksum" id="e_checksum" placeholder="Checksum"
                        value="<?php echo $checksum ?>">
                </div>
            </div>
            <div id="additionalFieldsAlert2" class="alert alert-warning" role="alert">
                Make sure the amount and order of Part Number, Location and Checksum match!
                <br>
                Pastikan jumlah dan urutan Part Number, Location dan Checksum sesuai!
            </div>
            <div class="form-group mb-3">
                <label for="e_tpversion">TP Version</label>
                <input type="text" class="form-control" name="e_tpversion" id="e_tpversion" placeholder="TP Version"
                    value="<?php echo $tpversion ?>">
            </div>
            <div class="form-group mb-3">
                <label for="e_burner">Burner Machine</label>
                <input type="text" class="form-control" name="e_burner" id="e_burner" placeholder="Burner Machine"
                    value="<?php echo $burner ?>">
            </div>
            <div class="form-check mb-3">
                <?php $checked = ($approval == '1') ? "checked" : ""; ?>
                <input class="form-check-input" type="checkbox" value="" name="e_approval" id="e_approval" <?php echo $checked ?>>
                <label class="form-check-label" for="e_approval">Approval</label>
            </div>
            <div class="form-group mb-3">
                <?php $enabled = ($approval == '1') ? "" : "readonly"; ?>
                <label for="e_approvaldate">Approval Date</label>
                <input type="date" class="form-control" name="e_approvaldate" id="e_approvaldate" placeholder="Approval Date"
                    value="<?php echo $approvaldate ?>" <?php echo $enabled ?>>
            </div>
            <div class="form-group mb-3">
                <label for="e_verifiedby">Verified by</label>
                <input type="text" class="form-control" name="e_verifiedby" id="e_verifiedby" placeholder="Verified by"
                    value="<?php echo $verifiedby ?>">
            </div>
            <div class="form-group mb-3">
                <label for="e_c_remarks">Remarks</label>
                <input type="text" class="form-control" name="e_c_remarks" id="e_c_remarks" placeholder="Remarks" value="<?php echo $c_remarks ?>">
            </div>
            <div class="form-group mb-3">
                <label for="e_att1">Upload 1: (Uploading a new file <strong>WILL DELETE</strong> the old file) (MAX 2 MB)</label>
                <input style="display: block;" type="file" id="e_att1" name="e_att1">
                <?php
                if (!empty($att1)) {
                    $att1EncodedFilename = rawurlencode($att1);
                    $downloadUrl = 'attachments/' . $att1EncodedFilename;
                    $encodedDownloadUrl = htmlspecialchars($downloadUrl, ENT_QUOTES, 'UTF-8');
                    echo '<a href="' . $encodedDownloadUrl . '">'.$att1.'</a>';
                }
                ?>
            </div>
            <div class="form-group mb-3">
                <label for="e_att2">Upload 2: (Uploading a new file <strong>WILL DELETE</strong> the old file) (MAX 2 MB)</label>
                <input style="display: block;" type="file" id="e_att2" name="e_att2">
                <?php
                if (!empty($att2)) {
                    $att2EncodedFilename = rawurlencode($att2);
                    $downloadUrl = 'attachments/' . $att2EncodedFilename;
                    $encodedDownloadUrl = htmlspecialchars($downloadUrl, ENT_QUOTES, 'UTF-8');
                    echo '<a href="' . $encodedDownloadUrl . '">'.$att2.'</a>';
                }
                ?>
            </div>
            <?php
        } 
    }



    mysqli_close($conn);
    ?>
    <script>
        $(document).ready(function () {
            $('#e_model').select2({
                dropdownParent: $('#e_modelParent'),
                width: '100%'
            });
        });
    </script>
</body>

</html>

