<?php
include '../../database/connect.php';
include '../../users/session.php';

$allowedSections = ['MS', 'ALL'];

if (!isset($_SESSION['usection']) || !in_array($_SESSION['usection'], $allowedSections)) {
    echo "<script>
            window.alert('You are not authorized to access this page.');
            window.location.href = '/vsite/cms/pages/delivery_order/index.php';
        </script>";
    exit();
}

$doc = $_GET['doc'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Handling Units</title>
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

        <div class="d-flex justify-content-between">
            <h2>Material Destination Handling Units</h2>
            <button style="margin-left: 10px" class="btn btn-danger"
                onclick="location.href='/vsite/cms/pages/material/ms_material_doc.php'">BACK</button>
        </div>
        <hr>

        <?php
        $documentQuery = "SELECT
                            mchu.doc,
                            COUNT(mchu.d_hu) AS 'TOTAL QTY',
                            SUM(CASE WHEN mchu.status = 0 THEN 1 ELSE 0 END) AS 'PENDING',
                            SUM(CASE WHEN mchu.status = 1 THEN 1 ELSE 0 END) AS 'RECEIVED',
                            CASE
                                WHEN mcd.`status` = 0 THEN 'ON GOING'
                                WHEN mcd.`status` = 1 THEN 'GR COMPLETE'
                                ELSE 'UNKNOWN'
                            END AS 'STATUS',
                            mcd.cd
                        FROM mc_hu mchu JOIN mc_doc mcd ON mchu.doc = mcd.doc
                        WHERE mchu.doc = '$doc'
                        GROUP BY mcd.doc
                        ORDER BY mcd.cd DESC
                        LIMIT 5000";
        $documentResult = mysqli_query($conn, $documentQuery);
        $row4 = mysqli_fetch_assoc($documentResult);
        ?>
        <div class="card border-dark mb-3" id="doCard">
            <div class="card-header">
                Document Details
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Document</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['doc']; ?>
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
                        <h5 class="card-title">Total Dest. Handling Unit Qty</h5>
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
            <table id="materialDhuTable" class="table">
                <thead>
                    <tr>
                        <th>DOCUMENT</th>
                        <th>D. HANDLING UNIT</th>
                        <th>WO</th>
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
        function loadMaterialDhuTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'materialDhu' },
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

        var table = $('#materialDhuTable').DataTable({
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
                        var doc = row[0];
                        var dhu = row[1];
                        return '<button type="button" class="btn btn-sm btn-primary" onClick="loadMaterialDetails(\'' + doc + '\', \'' + dhu + '\')">DETAILS</button>';
                    }
                }
            ]
        });

        function loadMaterialDetails(doc, dhu) {
            var url = 'ms_material_details.php?doc=' + doc + '&dhu=' + dhu;

            window.location.href = url;
        }

        $(document).ready(function () {
            loadMaterialDhuTable();
            $("div.dataTables_filter input").focus();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>