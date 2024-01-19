<?php
include '../../database/connect.php';
include '../../users/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <?php include '../../scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include '../../navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">

        <!-- Modals -->
        <?php include '../../modals/delivery_order/create.php'; ?>
        <?php include '../../modals/delivery_order/isn.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>
        <?php include '../../modals/material/inputTransactionM.php'; ?>
        <?php include '../../modals/material/outputTransactionM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Transaction</h2>
        <hr>
        <button href="#" data-bs-toggle="modal" data-bs-target="#inputTransactionModal" class="btn btn-primary mb-3">New
            Incoming</button>
        <button href="#" data-bs-toggle="modal" data-bs-target="#outputTransactionModal"
            class="btn btn-warning mb-3">New Outgoing</button>
        <div class="table-responsive">
            <table id="transactionTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TYPE</th>
                        <th>HU</th>
                        <th>MATERIAL</th>
                        <th>FULL BOX</th>
                        <th>QTY</th>
                        <th>LOSS QTY</th>
                        <th>TOTAL QTY</th>
                        <th>LOCATION</th>
                        <th>LINE CODE</th>
                        <th>CD</th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#inputMaterialForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: $(this).serialize() + '&mode=inputMaterial',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        alert('Input successful');
                        loadtransactionTable();
                    } else if (response.status == 'empty') {
                        alert('Fields cannot be empty!');
                    } else if (response.status == 'fail') {
                        alert('Material already exists!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Failed');
                    }
                }
            });
        });

        // function loadPeriod(que) {
        //     var url = 'period.php?id=' + que;

        //     window.location.href = url;
        // }

        function loadtransactionTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: {
                    mode: 'transaction'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.data) {
                        table.clear().rows.add(response.data).draw();
                    } else {
                        table.clear().draw();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        var table = $('#transactionTable').DataTable({
            // fixedHeader: true,
            responsive: true,
            // dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            // buttons: [{
            //     extend: 'collection',
            //     text: 'Export',
            //     buttons: [
            //         'copy',
            //         'excel',
            //         'csv',
            //         'pdf',
            //         'print'
            //     ]
            // }],
            order: [
                [0, 'desc']
            ],
            // columnDefs: [
            //     {
            //         target: 1,
            //         visible: false,
            //         searchable: false
            //     },
            // ],
            columns: [{
                data: 0
            },
            {
                data: 1
            },
            {
                data: 2
            },
            {
                data: 3
            },
            {
                data: 4
            },
            {
                data: 5
            },
            {
                data: 6
            },
            {
                data: 7
            },
            {
                data: 8
            },
            {
                data: 9
            },
            {
                data: 10
            },
            ]
        });

        $('#inputTransactionForm').submit(function (event) {
            event.preventDefault(); // Prevent form submission

            $.ajax({
                url: 'dbCrudFunctions/insert.php', // PHP script to process the form data
                type: 'POST',
                data: $(this).serialize() + '&mode=inputTransaction',
                dataType: 'json',
                success: function (response) {
                    // Open a new browser tab with the table
                    if (response.status == 'success') {
                        alert('Transaction successful!');
                        $('#inputTransactionForm')[0].reset();
                        $("#i_materialVerification").html('');
                        loadtransactionTable();
                    } else if (response.status == 'empty') {
                        alert('Fields cannot be empty!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('failed');
                    }
                }
            });
        });

        $('#outputTransactionForm').submit(function (event) {
            event.preventDefault(); // Prevent form submission

            // Check if o_linecode is empty
            if ($("#o_location").val() === 'L001') {
                var olinecodeValue = $("#o_linecode").val();
                if (olinecodeValue === null || olinecodeValue === "") {
                    alert('Please select a linecode!');
                    return; // Stop further processing
                }
            }

            $.ajax({
                url: 'dbCrudFunctions/insert.php', // PHP script to process the form data
                type: 'POST',
                data: $(this).serialize() + '&mode=outputTransaction',
                dataType: 'json',
                success: function (response) {
                    // Open a new browser tab with the table
                    if (response.status == 'success') {
                        alert('Transaction successful!');
                        $('#outputTransactionForm')[0].reset();
                        $("#o_location").val($("#defaultLocation").val()).trigger("change.select2");
                        $("#o_materialVerification").html('');
                        loadtransactionTable();
                    } else if (response.status == 'empty') {
                        alert('Fields cannot be empty!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('failed');
                    }
                }
            });
        });

        $(document).ready(function () {
            loadtransactionTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>