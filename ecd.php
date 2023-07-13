<?php
include 'database/connect.php';
include 'users/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engineering Checksum Data</title>
    <?php include 'scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php' ?>
    <div class="container">
        <!-- Button trigger create modal -->
        <!-- <div class="mb-4">
            <button type="button" class="btn btn-primary btn-sm my-2 <?php echo $hide ?>" data-bs-toggle="modal"
                data-bs-target="#createModal" style="margin-right: 10px;">
                Create DO
            </button>
            <button type="button" class="btn btn-light btn-sm my-2 <?php echo $hide ?>" data-bs-toggle="modal"
                data-bs-target="#modelModal">
                Add Model
            </button>
        </div> -->

        <?php
        if (isset($_POST['addnew'])) {

            $do = explode(',', $_POST['do']);
        
            $palletid = $do[0];
            $partno  = $do[1];
            $partname = $do[2];
            $dnnumber = $do[3];
            $qty = $do[4];
            $boxcount = $do[5];
            $date = $do[6];
        
            // Check if the same data already exists in ecd and ecd_details tables
            // $query = "SELECT *
            //     FROM dotest
            //     WHERE pallet = ? AND partno = ? AND partname = ?";
            // $stmt = mysqli_prepare($conn, $query);
            // mysqli_stmt_bind_param($stmt, 'sss', $palletid, $partno, $partname);
            // mysqli_stmt_execute($stmt);
            // mysqli_stmt_store_result($stmt);
        
            // if (mysqli_stmt_num_rows($stmt) > 0) {
            //     // Data already exists, show alert and do not execute INSERT query
            //     $insert_result = "Data already exists!";
            // } else {
        
        
            if (strlen($palletid) == 16) {
                $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
                VALUES('$palletid', '$partno', '$partname', '$dnnumber', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$ubadge')";
                $result1 = mysqli_query($conn, $query1);
            } else if (strlen($palletid) == 8) {
                $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
                VALUES('$palletid', '$partno', '$partname', '$palletid', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$ubadge')";
                $result1 = mysqli_query($conn, $query1);
            }
        
            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                echo "<script>$('#createModal').modal('show');</script>";
            }
        
            // }
        
        }
        ?>

        <!-- Modals -->
        <?php include 'modals/create.php'; ?>
        <?php include 'modals/add_model.php'; ?>
        <?php include 'modals/edit.php'; ?>
        <?php include 'modals/uploading.php'; ?>
    </div>
</body>

<?php mysqli_close($conn); ?>

</html>