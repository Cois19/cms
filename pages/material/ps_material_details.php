<?php
include '../../database/connect.php';
include '../../users/session.php';

$allowedSections = ['SS', 'ALL'];

if (!isset($_SESSION['usection']) || !in_array($_SESSION['usection'], $allowedSections)) {
    echo "<script>
            window.alert('You are not authorized to access this page.');
            window.location.href = '/vsite/cms/pages/material/ms_material_doc.php';
        </script>";
    exit();
}

// $doc = $_GET['doc'];
$dhu = $_GET['dhu'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Details</title>
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
        <?php include '../../modals/material/splitQtyM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <div class="d-flex justify-content-between">
            <h2>Material Details</h2>
            <button style="margin-left: 10px" class="btn btn-danger"
                onclick="location.href='javascript:history.back()'">BACK</button>
        </div>

        <hr>

        <?php
        $dhuQuery = "SELECT
                        mchu.d_hu,
                        mcm.wo,
                        mcm.doc,
                        COUNT(mcm.d_hu) AS 'TOTAL QTY',
                        SUM(CASE WHEN mcm.status = 0 THEN 1 ELSE 0 END) AS 'PENDING',
                        SUM(CASE WHEN mcm.status = 1 THEN 1 ELSE 0 END) AS 'RECEIVED',
                        CASE
                            WHEN mchu.`status` = 0 THEN 'ON GOING'
                            WHEN mchu.`status` = 1 THEN 'GR COMPLETE'
                            ELSE 'UNKNOWN'
                        END AS 'STATUS',
                        mchu.cd
                    FROM mc_materialmaster mcm JOIN mc_hu mchu ON mcm.d_hu = mchu.d_hu
                    WHERE mchu.d_hu = '$dhu'
                    GROUP BY mchu.d_hu
                    ORDER BY mchu.cd DESC
                    LIMIT 5000";
        $dhuResult = mysqli_query($conn, $dhuQuery);
        $row4 = mysqli_fetch_assoc($dhuResult);
        ?>
        <div class="card border-dark mb-3" id="doCard">
            <div class="card-header">
                Handling Unit Details
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">D. Handling Unit / WO / Doc</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['d_hu']; ?> / <?php echo $row4['wo']; ?> / <?php echo $row4['doc']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Status</h5>
                        <p class="card-text mb-2">
                            <span
                                class="badge fs-6 <?php echo ($row4['STATUS'] === 'ON GOING') ? 'text-bg-warning' : (($row4['STATUS'] === 'GR COMPLETE') ? 'text-bg-success' : ''); ?>">
                                <?php echo $row4['STATUS']; ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Issue Date</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['cd']; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Total Material Qty</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['TOTAL QTY']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Pending</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['PENDING']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Received</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['RECEIVED']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="materialDetailsTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>SPLIT ID</th>
                        <th>S. HANDLING UNIT</th>
                        <th>MATERIAL</th>
                        <th>DESCRIPTION</th>
                        <th>BATCH</th>
                        <th>QTY</th>
                        <th>UOM</th>
                        <th>STATUS</th>
                        <th>CD</th>
                        <th>RECEIVER</th>
                        <th colspan="1" rowspan="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        function loadMaterialDetailsTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: {
                    mode: 'ps_materialDetails',
                    dhu: '<?php echo $dhu; ?>'
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

                        if (response.qty == 0) {
                            window.location.reload();
                        }
                        loadMaterialDetailsTable();
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
            $("div.dataTables_filter input").focus();
        }

        var table = $('#materialDetailsTable').DataTable({
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
            order: [[9, 'desc']],
            columnDefs: [
                {
                    target: 9,
                    width: '10%'
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
                {
                    data: 8,
                    render: function (data, type, row) {
                        if (row[8] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[8] + '</span>';
                        } else if (row[8] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[8] + '</span>';
                        } else {
                            return row[8];
                        }
                    }
                },
                { data: 9 },
                { data: 10 },
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[0];
                        var status = row[8];
                        var disabledAttribute = status === "GR COMPLETE" ? 'disabled' : '';
                        return '<button type="button" class="btn btn-sm btn-primary mb-2" style="width: 50px" onClick="receiveMaterial(\'' + token + '\')" ' + disabledAttribute + '>GR</button>';
                    }
                }
            ]
        });

        $(document).ready(function () {
            loadMaterialDetailsTable();
            $("div.dataTables_filter input").focus();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>