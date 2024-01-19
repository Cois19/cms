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

$d_hu = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Receive</title>
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

        <h2>Material Receive</h2>
        <hr>
        <div class="table-responsive">
            <table id="materialReceiveTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>DOCUMENT</th>
                        <th>WO</th>
                        <th>S. HANDLING UNIT</th>
                        <th>MATERIAL</th>
                        <th>DESCRIPTION</th>
                        <th>BATCH</th>
                        <th>UOM</th>
                        <th>D. HANDLING UNIT</th>
                        <th>QTY</th>
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
        function loadMaterialReceiveTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: {
                    mode: 'materialreceive',
                    d_hu: '<?php echo $d_hu ?>'
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

        function receiveMaterial(que) {
            var usection = <?php echo json_encode($usection); ?>;
            if (usection !== 'SS' && usection !== 'ALL') {
                alert('You are not authorized.');
                return;
            }
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: {
                    mode: 'materialTransaction',
                    que: que
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        alert('Good Received');
                        loadMaterialReceiveTable();
                    } else if (response.status == 'fail') {
                        alert('Delete Failed');
                    } else if (response.status == 'unauthorized') {
                        alert('Ask Admin Level User to Delete.');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('failed');
                    }
                }
            });
        }

        var table = $('#materialReceiveTable').DataTable({
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
            order: [[11, 'desc']],
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
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6 },
                { data: 7 },
                { data: 8 },
                { data: 9 },
                {
                    data: 10,
                    render: function (data, type, row) {
                        if (row[10] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[10] + '</span>';
                        } else if (row[10] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[10] + '</span>';
                        } else {
                            return row[10];
                        }
                    }
                },
                { data: 11 },
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[0];
                        var status = row[10];
                        var disabledAttribute = status === "GR COMPLETE" ? 'disabled' : '';
                        return '<button type="button" class="btn btn-sm btn-primary" onClick="receiveMaterial(\'' + token + '\')" ' + disabledAttribute + '>GR</button>';
                    }
                }
            ]
        });

        $(document).ready(function () {
            loadMaterialReceiveTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>