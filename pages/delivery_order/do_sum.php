<?php
include '../../database/connect.php';
include '../../users/session.php';

$hide = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Order Summary</title>
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
        <?php include '../../modals/delivery_order/filterTableM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>

        <h2>DO Summary</h2>
        <hr>
        <button id="filterTableBtn" href="#" data-bs-toggle="modal" data-bs-target="#filterTableModal"
            class="btn btn-primary mb-3">Filter Table</button>
        <div class="table-responsive">
            <table id="doSumTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>DO NUMBER</th>
                        <th>ISN</th>
                        <th>PALLET ID</th>
                        <th>PART NO</th>
                        <th>PART NAME</th>
                        <th>MODEL</th>
                        <th>DATE</th>
                        <th>INCOMING</th>
                        <th>PIC</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#filterTableForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: $(this).serialize() + '&mode=sum',
                dataType: 'json',
                success: function (response) {
                    $('#filterTableModal').modal('hide');

                    if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
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

        var table = $('#doSumTable').DataTable({
            fixedHeader: true,
            responsive: true,
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
            // order: [[0, 'desc']],
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        var startNumber = 1;
                        var currentNumber = meta.row + startNumber;
                        return currentNumber;
                    }
                },
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6 },
                { data: 7 },
                { data: 8 },
                {
                    data: 9,
                    render: function (data, type, row) {
                        if (row[9] === "INACTIVE") {
                            return '<span class="badge text-bg-dark fs-6">' + row[9] + '</span>';
                        } else if (row[9] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[9] + '</span>';
                        } else if (row[9] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[9] + '</span>';
                        } else {
                            return row[9];
                        }
                    }
                }
            ]
        });

        $(document).ready(function () {
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>