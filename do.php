<?php
include 'database/connect.php';
include 'users/session.php';
// Retrieve the delivery order ID from the URL parameter
$doId = $_GET['id'];
$hide = '';
// TODO: Query the database to fetch the delivery order details based on $doId
// Replace the code below with your actual database query logic

// Example code to simulate fetching the data
$query4 = "SELECT * FROM tdoc WHERE que = $doId AND tstatus = 1";
$result4 = mysqli_query($conn, $query4);
if ($result4 && mysqli_num_rows($result4) > 0) {
    $row4 = mysqli_fetch_assoc($result4);
    $tdono = $row4['tdono'];

} else {
    // Handle the case when the query returns no results
    // For example, display a message or redirect to another page
    $hide = "d-none";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Order</title>
    <?php include 'scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">
        <!-- Modals -->
        <?php include 'modals/create.php'; ?>
        <?php include 'modals/add_model.php'; ?>
        <?php include 'modals/edit.php'; ?>
        <?php include 'modals/uploading.php'; ?>
        <?php include 'modals/isn.php'; ?>
        <?php include 'modals/resetM.php'; ?>
        <?php include 'modals/deleteDoM.php'; ?>

        <div class="card border-dark mb-3 <?php echo $hide ?>" id="doCard">
            <div class="card-header">
                Delivery Order
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Pallet ID</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['tpid']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Part No</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['tpno']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Part Name</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['tpname']; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">DN Number</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['tdono']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Remaining Qty</h5>
                        <p class="card-text mb-2" id="qtyCount">
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Box Count</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['tbxcount']; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Date</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['tdate']; ?>
                        </p>
                    </div>
                </div>
                <a href="#" data-bs-toggle="modal" data-bs-target="#isnModal" class="btn btn-primary">Scan ISN</a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#grModal" class="btn btn-success"
                    style="margin-right: 30px;">GR</a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#resetModal" class="btn btn-danger">Reset ISN</a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#deleteDoModal" class="btn btn-danger">Delete DO</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="isnTable" class="table table-hover <?php echo $hide ?>">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ISN</th>
                        <th>PART NO</th>
                        <th>MODEL</th>
                    </tr>
                </thead>
                <tbody>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $('#newDoForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    $('#newDoForm')[0].reset(); // Reset the form
                    $('#createModal').modal('hide');
                    // Construct the URL for the new page
                    var url = 'do.php?id=' + response.que;

                    // Redirect to the new page
                    window.location.href = url;

                }
            });
        });

        $('#newIsnForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insertISN.php',
                data: $(this).serialize() + '&doId=<?php echo $doId; ?>',
                dataType: 'json',
                success: function (response) {
                    $('#newIsnForm')[0].reset(); // Reset the form
                    updateQtyCount(); // Update the qtyCount after successful ISN insertion
                    loadTable();
                }
            });
        });

        $('#resetBtn').click(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/reset.php',
                data: { tdono: <?php echo "'" . $tdono . "'"; ?> },
                success: function (response) {
                    $('#resetModal').modal('hide');
                    updateQtyCount();
                    loadTable();
                }
            });
        });

        $('#deleteBtn').click(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/delete.php',
                data: { tdono: <?php echo "'" . $tdono . "'"; ?> },
                success: function (response) {
                    $('#deleteDoModal').modal('hide');
                    // Construct the URL for the new page
                    var url = 'ecd.php';

                    // Redirect to the new page
                    window.location.href = url;
                }
            });

        });

        function updateQtyCount() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/updateQtyCount.php',
                data: { doId: <?php echo $doId; ?> },
                dataType: 'json',
                success: function (response) {
                    // Update the qtyCount value on the page
                    $('#qtyCount').text(response);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                    // Handle the error case if needed
                }
            });
        }

        var doId = <?php echo $doId; ?>;

        function loadTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'isn', doId: doId },
                dataType: 'json',
                success: function (response) {
                    // Clear the existing table data and redraw with new data
                    if (response.data) {
                        table.clear().rows.add(response.data).draw();
                    } else {
                        table.clear().draw();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                    // Handle the error case if needed
                }
            });
        }

        var table = $('#isnTable').DataTable({
            // responsive: true,
            dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ]
                }
            ],
            order: [[0, 'desc']],
            columnDefs: [
                {
                    target: 0,
                    visible: false,
                    searchable: false
                },
            ],
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 }
            ]
        });

        $(document).ready(function () {
            updateQtyCount();
            loadTable();
        });


    </script>
</body>

<?php mysqli_close($conn); ?>

</html>