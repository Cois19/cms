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
    <title>Transaction Report</title>
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
        <?php include '../../modals/material/filterTransactionTableM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Transaction Report</h2>
        <hr>
        <button href="#" data-bs-toggle="modal" data-bs-target="#filterTransactionTableModal"
            class="btn btn-primary mb-3">Generate Report</button>
        <div class="table-responsive">
            <table id="transactionReportTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TYPE</th>
                        <th>DOC</th>
                        <th>WO</th>
                        <th>S. HANDLING UNIT</th>
                        <th>MATERIAL</th>
                        <th>DESCRIPTION</th>
                        <th>BATCH</th>
                        <th>UOM</th>
                        <th>D. HANDLING UNIT</th>
                        <th>QTY</th>
                        <th>LOCATION</th>
                        <th>CD</th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#filterTransactionTableForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: $(this).serialize() + '&mode=transactionReport',
                dataType: 'json',
                success: function (response) {
                    $('#filterTransactionTableModal').modal('hide');

                    if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else if (response.status == 'empty') {
                        alert('Please insert at least one field.');
                    } else {
                        if (response.data !== null) {
                            table.clear().rows.add(response.data).draw();
                        } else {
                            table.clear().draw();
                        }
                    }
                }
            });
        });

        var table = $('#transactionReportTable').DataTable({
            // fixedHeader: true,
            responsive: true,
            dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            buttons: [{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }],
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
            {
                data: 11
            },
            {
                data: 12
            }
            ]
        });

        $(document).ready(function () {
            // loadtransactionTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>