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
        <?php include '../../modals/material/inputMaterialM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Material Master</h2>
        <hr>
        <button href="#" data-bs-toggle="modal" data-bs-target="#inputMaterialModal" class="btn btn-success mb-3">Input
            Material</button>
        <button id="importMaterialModalBtn" href="#" data-bs-toggle="modal" data-bs-target="#importMaterialModal"
            class="btn btn-success mb-3">Import Material</button>
        <div class="table-responsive">
            <table id="materialTable" class="table">
                <thead>
                    <tr>
                        <th>MATERIAL</th>
                        <th>DESCRIPTION</th>
                        <th>SPQ PALLET</th>
                        <th>SPQ BOX</th>
                        <th>SPQ INNER</th>
                        <th>STANDARD ISSUE</th>
                        <th>CD</th>
                        <!-- <th colspan="1" rowspan="1"></th> -->
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#inputMaterialForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: $(this).serialize() + '&mode=inputMaterial',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        alert('Input successful');
                        loadMaterialTable();
                    } else if (response.status == 'empty') {
                        alert('Fields cannot be empty!');
                    } else if (response.status == 'fail') {
                        alert('Material already exists!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Failed');
                    }
                }
            });
        });

        $('#importMaterialForm').submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData();
            formData.append('material', $('#material')[0].files[0]); // Add the file input
            formData.append('mode', 'importmaterial');

            var file = $('#material')[0].files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var data = e.target.result;
                    var workbook = XLSX.read(data, { type: 'binary' });

                    // Assuming the first sheet is the one you want to work with
                    var sheetName = workbook.SheetNames[0];
                    var sheet = workbook.Sheets[sheetName];

                    // Parse the sheet's data (you may want to iterate through rows and columns)
                    var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                    // Now you can send the JSON data to the server using AJAX
                    formData.append('excelData', JSON.stringify(jsonData)); // Add the parsed data to formData

                    $.ajax({
                        url: '/vsite/cms/dbCrudFunctions/insert.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 'success') {
                                // Handle success
                                alert('Import successful');
                                loadMaterialTable();
                            } else if (response.status == 'empty') {
                                alert('Please select a file!');
                            } else if (response.status == 'fail') {
                                // Handle failure
                                alert('Material already exists!');
                            } else if (response.status == 'timeout') {
                                window.location.href = '/vsite/cms/users/login.php';
                            } else {
                                alert('Material already exists!');
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
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
        
        function loadMaterialTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'material' },
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
            order: [[6, 'desc']],
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
                { data: 4 },
                { data: 5 },
                { data: 6 }
            ]
        });

        function loadMaterialTable1() {
            new DataTable('#materialTable', {
                ajax: 'dbCrudFunctions/serversidetable.php',
                processing: true,
                serverSide: true,
                order: [[6, 'desc']]
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
                // lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        }

        $(document).ready(function () {
            // loadMaterialTable();
            loadMaterialTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>