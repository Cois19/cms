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
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        // $('#filterTableForm').submit(function (e) {
        //     e.preventDefault(); // Prevent form submission

        //     // Send form data using Ajax
        //     $.ajax({
        //         type: 'POST',
        //         url: 'dbCrudFunctions/table.php',
        //         serverSide: true,
        //         data: $(this).serialize() + '&mode=sum',
        //         dataType: 'json',
        //         success: function (response) {
        //             $('#filterTableModal').modal('hide');

        //             if (response.status == 'timeout') {
        //                 window.location.href = '/vsite/cms/users/login.php';
        //             } else {
        //                 if (response.data !== null) {
        //                     table.clear().rows.add(response.data).draw();
        //                 } else {
        //                     table.clear().draw();
        //                 }
        //             }

        //         }
        //     });
        // });

        $('#filterTableForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission
            table.draw();
        });

        var table = $('#doSumTable').DataTable({
            "lengthMenu": [
                [10, 25, 50, 100], // You can adjust the options as needed
                [10, 25, 50, 100]
            ],
            "dom": '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap me-3"<"remaining-qty me-2"><"scanned-qty">><"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            "buttons": [
                {
                    "extend": 'collection',
                    "text": 'Export',
                    "buttons": [
                        {
                            "extend": 'copy',
                            "exportOptions": {
                                "modifier": {
                                    "page": 'all'
                                }
                            }
                        },
                        {
                            "extend": 'excel',
                            "exportOptions": {
                                "modifier": {
                                    "page": 'all'
                                }
                            }
                        },
                        {
                            "extend": 'csv',
                            "exportOptions": {
                                "modifier": {
                                    "page": 'all'
                                }
                            }
                        },
                        {
                            "extend": 'pdf',
                            "exportOptions": {
                                "modifier": {
                                    "page": 'all'
                                }
                            }
                        },
                        {
                            "extend": 'print',
                            "exportOptions": {
                                "modifier": {
                                    "page": 'all'
                                }
                            }
                        }
                    ]
                }
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "dbCrudFunctions/table.php",
                "type": "POST",
                "data": {
                    "mode": "sum" // Include the additional data here
                }
            },
            "columns": [
                { "data": "no" },
                { "data": "tdono" },
                { "data": "tisn" },
                { "data": "tpid" },
                { "data": "tpno" },
                { "data": "tpname" },
                { "data": "tpmodel" },
                { "data": "tdate" },
                { "data": "cd" },
                { "data": "status" }
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