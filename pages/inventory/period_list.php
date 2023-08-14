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
    <title>Delivery Order List</title>
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

        <h2>DO List</h2>
        <hr>
        <div class="table-responsive">
            <table id="periodTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>QUE</th>
                        <th>DO NUMBER</th>
                        <th>PALLET ID</th>
                        <th>PART NO</th>
                        <th>PART NAME</th>
                        <th>MODEL</th>
                        <th>QTY</th>
                        <th>INCOMING</th>
                        <th>STATUS</th>
                        <th colspan="1" rowspan="1"></th>
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
                        <td></td>
                        <td colspan="1" rowspan="1"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        function loadISN(tdono) {
            var url = 'do.php?id=' + tdono;

            window.location.href = url;
        }

        function loadDoTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'do' },
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

        var table = $('#doTable').DataTable({
            fixedHeader: true,
            responsive: true,
            // dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            // buttons: [
            //     {
            //         extend: 'collection',
            //         text: 'Export',
            //         buttons: [
            //             'copy',
            //             'excel',
            //             'csv',
            //             'pdf',
            //             'print'
            //         ]
            //     }
            // ],
            // order: [[0, 'desc']],
            columnDefs: [
                {
                    target: 1,
                    visible: false,
                    searchable: false
                },
            ],
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
                {
                    data: 8,
                    render: function (data, type, row) {
                        if (row[8] === "INACTIVE") {
                            return '<span class="badge text-bg-dark fs-6">' + row[8] + '</span>';
                        } else if (row[8] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[8] + '</span>';
                        } else if (row[8] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[8] + '</span>';
                        } else {
                            return row[8];
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[0];
                        return '<button type="button" class="btn btn-sm btn-primary" onClick="loadISN(\'' + token + '\')">ACTION</button>';
                    }
                }
            ]
        });

        $(document).ready(function () {
            loadDoTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>