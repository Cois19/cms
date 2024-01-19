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
    <title>Material Master</title>
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
        <?php include '../../modals/material/importMaterialM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Material Master</h2>
        <hr>
        <div class="table-responsive">
            <table id="materialTable" class="table">
                <thead>
                    <tr>
                        <th>DOCUMENT</th>
                        <th>WO</th>
                        <th>D. HANDLING UNIT</th>
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
        function loadMaterialTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'material_ss' },
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
        
        function receiveMaterialDHU(d_hu) {
            var usection = <?php echo json_encode($usection); ?>;
            if (usection !== 'SS' && usection !== 'ALL') {
                alert('You are not authorized.');
                return;
            }
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: {
                    mode: 'receiveMaterialDHU',
                    d_hu: d_hu
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        alert('Good Received');
                        loadMaterialTable();
                    } else if (response.status == 'fail') {
                        alert('Delete Failed');
                    } else if (response.status == 'status') {
                        alert('Some Materials are not received yet!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('failed');
                    }
                }
            });
        }

        var table = $('#materialTable').DataTable({
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
            order: [[7, 'desc']],
            columnDefs: [
                {
                    target: 3,
                    width: '10%'
                },
                {
                    target: 4,
                    width: '10%'
                },
                {
                    target: 5,
                    width: '10%'
                },
                {
                    target: 7,
                    width: '10%'
                }
            ],
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                {
                    data: 6,
                    render: function (data, type, row) {
                        if (row[6] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[6] + '</span>';
                        } else if (row[6] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[6] + '</span>';
                        } else {
                            return row[6];
                        }
                    }
                },
                { data: 7 },
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[2];
                        var status = row[6];
                        var disabledAttribute = status === "GR COMPLETE" ? 'disabled' : '';
                        return '<button type="button" class="btn btn-sm btn-primary mb-1" style="width: 60px;" onClick="loadMaterialReceive(\'' + token + '\')">TRACE</button>' +
                                '<button type="button" class="btn btn-sm btn-success" style="width: 60px;" onClick="receiveMaterialDHU(\'' + token + '\')" ' + disabledAttribute + '>GR</button>';
                    }
                }
            ]
        });

        function loadMaterialReceive(que) {
            var url = 'materialreceive.php?id=' + que;

            window.location.href = url;
        }

        $(document).ready(function () {
            loadMaterialTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>