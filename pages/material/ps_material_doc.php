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

$updateMc_doc = "UPDATE mc_doc mcd
                    SET mcd.status = 1
                    WHERE mcd.doc IN (
                        SELECT mcd.doc
                        FROM (
                            SELECT 
                                mchu.doc,
                                COUNT(mchu.d_hu) AS 'TOTAL QTY',
                                SUM(CASE WHEN mchu.status = 0 THEN 1 ELSE 0 END) AS 'PENDING',
                                SUM(CASE WHEN mchu.status = 1 THEN 1 ELSE 0 END) AS 'RECEIVED'
                            FROM mc_hu mchu
                            GROUP BY mchu.doc
                        ) AS subquery
                        WHERE subquery.doc = mcd.doc AND subquery.PENDING = 0)";
mysqli_query($conn, $updateMc_doc);
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

        <div class="d-flex">
            <form method="post" id="scanDhuForm" class="me-3">
                <div class="form-group mb-3" style="width: 200px;">
                    <label for="scanDhu">DHU :</label>
                    <input type="text" class="form-control" name="scanDhu" id="scanDhu" placeholder="DHU" autofocus>
                </div>
            </form>
            <form method="post" id="scanDocForm">
                <div class="form-group mb-3" style="width: 200px;">
                    <label for="scanDoc">DOC :</label>
                    <input type="text" class="form-control" name="scanDoc" id="scanDoc" placeholder="DOC">
                </div>
            </form>
        </div>

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
                data: {
                    mode: 'materialDoc'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.data) {
                        table.clear().rows.add(response.data).draw();
                    } else {
                        table.clear().draw();
                    }
                },
                error: function(xhr, status, error) {
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
            order: [
                [5, 'desc']
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
                    data: 4,
                    render: function(data, type, row) {
                        if (row[4] === "ON GOING") {
                            return '<span class="badge text-bg-warning fs-6">' + row[4] + '</span>';
                        } else if (row[4] === "GR COMPLETE") {
                            return '<span class="badge text-bg-success fs-6">' + row[4] + '</span>';
                        } else {
                            return row[4];
                        }
                    }
                },
                {
                    data: 5
                },
                {
                    data: null,
                    render: function(data, type, row) {
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

        $('#scanDhuForm').submit(function(e) {
            e.preventDefault();

            var scanDhuValue = $('#scanDhu').val();
            loadMaterialDetails(scanDhuValue);
            $('#scanDhuForm')[0].reset();
            $('#scanDhu').focus();
        });

        function loadMaterialDetails(dhu) {
            var url = 'ps_material_details.php?dhu=' + dhu;

            window.location.href = url;
        }

        $('#scanDocForm').submit(function(e) {
            e.preventDefault();

            var scanDocValue = $('#scanDoc').val();
            loadMaterialDhu(scanDocValue);
            $('#scanDocForm')[0].reset();
            $('#scanDoc').focus();
        });

        $(document).ready(function() {
            loadMaterialDocTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>