<?php
include 'database/connect.php';
include 'users/session.php';

$hide = '';
// TODO: Query the database to fetch the delivery order details based on $doId
// Replace the code below with your actual database query logic

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
        <?php include 'modals/grM.php'; ?>
        <?php include 'modals/filterTableM.php'; ?>


        <h2>DO Summary</h2>
        <hr>
        <button id="filterTableBtn" href="#" data-bs-toggle="modal" data-bs-target="#filterTableModal" class="btn btn-primary">Filter Table</button>
        <div class="table-responsive">
            <table id="doSumTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>DO NUMBER</th>
                        <th>PALLET ID</th>
                        <th>PART NO</th>
                        <th>PART NAME</th>
                        <th>MODEL</th>
                        <th>QTY</th>
                        <th>INCOMING</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
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

        $('#filterTableForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: $(this).serialize() + '&doId="sum"' + '&doId="sum"',
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

        $('#grConfirm').click(function () {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/gr.php',
                data: { doId: <?php echo $doId; ?> },
                success: function (response) {
                    $('#grModal').modal('hide');
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
                success: function (response) {
                    // Update the qtyCount value on the page
                    $('#qtyCount').text(response);
                    if (response == 0) {
                        document.getElementById("scanIsnBtn").disabled = true;
                        document.getElementById("grBtn").disabled = false;
                        $('#isnModal').modal('hide');
                    }
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