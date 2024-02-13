<?php
include '../../database/connect.php';
include '../../users/session.php';

$allowedSections = ['MS', 'ALL'];

if (!isset($_SESSION['usection']) || !in_array($_SESSION['usection'], $allowedSections)) {
    echo "<script>
            window.alert('You are not authorized to access this page.');
            window.location.href = '/vsite/cms/pages/material/ps_material_dhu.php';
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

$updateMc_doc_whenPendingIsZero = "UPDATE mc_doc mcd
                                    SET mcd.status = 0
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
                                        WHERE subquery.doc = mcd.doc AND subquery.PENDING != 0)";

$updateMc_hu = "UPDATE mc_hu AS mchu
                JOIN (
                    SELECT
                        mcm.d_hu,
                        SUM(CASE WHEN mcm.status = 0 THEN 1 ELSE 0 END) AS 'PENDING'
                    FROM mc_materialmaster mcm
                    GROUP BY mcm.d_hu
                ) AS pending_counts ON mchu.d_hu = pending_counts.d_hu
                SET mchu.status = 0
                WHERE pending_counts.PENDING != 0";

mysqli_query($conn, $updateMc_doc);
mysqli_query($conn, $updateMc_doc_whenPendingIsZero);
mysqli_query($conn, $updateMc_hu);
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
        <?php include '../../modals/material/importMaterialM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Material Documents</h2>
        <hr>
        <button id="importMaterialModalBtn" href="#" data-bs-toggle="modal" data-bs-target="#importMaterialModal" class="btn btn-success mb-3">Import Material</button>
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
        $('#importMaterialForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData();
            formData.append('material', $('#material')[0].files[0]); // Add the file input
            formData.append('mode', 'importmaterial');

            var file = $('#material')[0].files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var data = e.target.result;
                    var workbook = XLSX.read(data, {
                        type: 'binary'
                    });

                    // Assuming the first sheet is the one you want to work with
                    var sheetName = workbook.SheetNames[0];
                    var sheet = workbook.Sheets[sheetName];

                    // Parse the sheet's data (you may want to iterate through rows and columns)
                    var jsonData = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });

                    // Now you can send the JSON data to the server using AJAX
                    formData.append('excelData', JSON.stringify(jsonData)); // Add the parsed data to formData

                    $.ajax({
                        url: '/vsite/cms/dbCrudFunctions/insert.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                alert('Import successful');
                                loadMaterialDocTable();
                            } else if (response.status == 'empty') {
                                alert('Please select a file!');
                            } else if (response.status == 'emptydoc') {
                                alert('Some data is empty! Please check the file again.');
                            } else if (response.status == 'fail') {
                                alert('Material already exists!');
                            } else if (response.status == 'timeout') {
                                window.location.href = '/vsite/cms/users/login.php';
                            } else {
                                alert('Failed');
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.error('AJAX error:', textStatus, errorThrown);
                            console.log('Response:', xhr.responseText);
                        }
                    });
                };

                reader.readAsBinaryString(file);
            } else {
                alert('Please Select a File!');
            }
        });

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
            var url = 'ms_material_dhu.php?doc=' + doc;

            window.location.href = url;
        }

        $(document).ready(function() {
            loadMaterialDocTable();
            $("div.dataTables_filter input").focus();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>