<?php
include '../../database/connect.php';
include '../../users/session.php';

if ($utype == 3) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance</title>
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
        <?php include '../../modals/delivery_order/isn.php'; ?>
        <?php include '../../modals/delivery_order/resetM.php'; ?>
        <?php include '../../modals/delivery_order/deleteDoM.php'; ?>
        <?php include '../../modals/delivery_order/grM.php'; ?>
        <?php include '../../modals/delivery_order/filterIslTableM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Daily Performance</h2>
        <hr>
        <div id="data-container">
            <!-- Data will be dynamically inserted here -->
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $(document).ready(function () {
            fetchData();
        });

        function fetchData() {
            $.ajax({
                url: 'dbCrudFunctions/getPicScans.php', // PHP script to fetch data from the database
                method: 'POST',
                dataType: 'json',
                success: function (data) {
                    // Clear existing data
                    $('#data-container').empty();

                    // Iterate through data and create columns
                    for (var i = 0; i < data.length; i++) {
                        if (i % 4 === 0) {
                            // Create a new row for every 4th item
                            $('#data-container').append('<div class="row">');
                        }
                        var column = '<div class="col-md-3 mb-3 text-center">' +
                            '<span>Total ISN :</span>' +
                            '<h2>' + data[i].total_scan + '</h2>' +
                            '<h5>' + data[i].uname + '</h5>' +
                            '</div>';
                        $('#data-container .row:last-child').append(column);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(status + ': ' + error);
                }
            });
        }

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>