<?php
include '../../database/connect.php';
include '../../users/session.php';

$que = $_GET['id'];
$hideIfNot1 = '';

$query4 = "SELECT * FROM tperiod WHERE que = $que";
$result4 = mysqli_query($conn, $query4);
if ($result4 && mysqli_num_rows($result4) > 0) {
    $row4 = mysqli_fetch_assoc($result4);
    // $tdono = $row4['tdono'];

    // if ($row4['tstatus'] != 1) {
    //     $hideIfNot1 = "d-none";
    // }
} else {

    // header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Period Details</title>
    <?php include '../../scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include '../../navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">
        <!-- Modals -->
        <?php include '../../modals/delivery_order/create.php'; ?>
        <?php include '../../modals/edit.php'; ?>
        <?php include '../../modals/uploading.php'; ?>
        <?php include '../../modals/delivery_order/isn.php'; ?>
        <?php include '../../modals/delivery_order/resetM.php'; ?>
        <?php include '../../modals/delivery_order/deleteDoM.php'; ?>
        <?php include '../../modals/delivery_order/grM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/delivery_order/scanCompleteM.php'; ?>
        <?php include '../../modals/delivery_order/deleteISNM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>

        <div class="card border-dark mb-3" id="doCard">
            <div class="card-header">
                Period
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Period Name</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['periodname']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Start</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['periodstart']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">End</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['periodend']; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Description</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['description']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Remarks</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['remarks']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Status</h5>
                        <?php
                        if ($row4['status'] == '0') {
                            echo '<p class="card-text mb-2 fw-bold text-success">INACTIVE</p>';
                        } else if ($row4['status'] == '1') {
                            echo '<p class="card-text mb-2 fw-bold text-danger">ACTIVE</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="btn-group mb-3 mb-lg-0 <?php echo $hideIfNot1 ?>">
                        <button id="scanIsnBtn" href="#" data-bs-toggle="modal" data-bs-target="#importPartModal"
                            class="btn btn-primary">Import Part</button>
                        <!-- <button id="grBtn" href="#" data-bs-toggle="modal" data-bs-target="#grModal"
                            class="btn btn-success" disabled>Good Received</button> -->
                    </div>
                    <div class="btn-group <?php echo $hideIfNot1 ?>">
                        <!-- <button href="#" data-bs-toggle="modal" data-bs-target="#resetModal" class="btn btn-danger"
                            <?php echo ($utype == 3) ? 'disabled' : ''; ?>>Reset
                            ISN</button> -->
                        <button href="#" data-bs-toggle="modal" data-bs-target="#deactivatePeriodModal" class="btn btn-danger"
                            <?php echo ($utype != 1) ? 'disabled' : ''; ?>>Deactivate Period</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>

        $(document).ready(function () {
            // updateQtyCount();
            // loadTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>