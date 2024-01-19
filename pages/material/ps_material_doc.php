<?php
include '../../database/connect.php';
include '../../users/session.php';

$allowedSections = ['SS', 'ALL'];

if (!isset($_SESSION['usection']) || !in_array($_SESSION['usection'], $allowedSections)) {
    echo "<script>
            window.alert('You are not authorized to access this page.');
            window.location.href = '/vsite/cms/pages/delivery_order/index.php';
        </script>";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Documents</title>
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
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Material Documents</h2>
        <hr>
        <div class="table-responsive">
            <table id="materialDocTable" class="table">
                <thead>
                    <tr>
                        <th>DOCUMENT</th>
                        <th>TOTAL QTY</th>
                        <th>ON GOING</th>
                        <th>RECEIVED</th>
                        <th>STATUS</th>
                        <th>CD</th>
                        <th colspan="1" rowspan="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        function loadMaterialDocTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'materialDoc' },
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

        var table = $('#materialDocTable').DataTable({
            // fixedHeader: true,
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
            order: [[5, 'desc']],
            // columnDefs: [
            //     {
            //         target: 1,
            //         visible: false,
            //         searchable: false
            //     },
            // ],
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                {
                    data: 4,
                    render: function (data, type, row) {
                        if (row[4] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[4] + '</span>';
                        } else if (row[4] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[4] + '</span>';
                        } else {
                            return row[4];
                        }
                    }
                },
                { data: 5 },
                {
                    data: null,
                    render: function (data, type, row) {
                        var doc = row[0];
                        return '<button type="button" class="btn btn-sm btn-primary" onClick="loadMaterialDhu(\'' + doc + '\')">DETAILS</button>';
                    }
                }
            ]
        });

        function loadMaterialDhu(doc) {
            var url = 'ps_material_dhu.php?doc=' + doc;

            window.location.href = url;
        }

        $(document).ready(function () {
            loadMaterialDocTable();
            $("div.dataTables_filter input").focus();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>