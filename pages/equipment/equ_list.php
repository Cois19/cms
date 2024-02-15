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
    <title>Equipment Master List</title>
    <?php include '../../scripts.php' ?>
    <style>
        .container {
            width: 90%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../../navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">

        <!-- Modals -->
        <?php include '../../modals/delivery_order/create.php'; ?>
        <?php include '../../modals/delivery_order/isn.php'; ?>
        <?php include '../../modals/equipment/filterEquListM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>Equipment Master List</h2>
        <hr>
        <button href="#" data-bs-toggle="modal" data-bs-target="#filterEquListModal" class="btn btn-primary mb-3">Filter Equipment</button>
        <div class="table-responsive">
            <table id="equMasterTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>OWNER</th>
                        <th>MODEL</th>
                        <th>LOC</th>
                        <th>STATION</th>
                        <th>DESC</th>
                        <th>DEVICE_ID</th>
                        <th>INT_ASSET</th>
                        <th>TEMP_ASSET</th>
                        <th>CUST_ASSET</th>
                        <th>MAIN_ASSET</th>
                        <th colspan="1" rowspan="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        function loadEquMasterTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: {
                    mode: 'equmaster'
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

        $('#filterEquListForm').submit(function(e) {
            e.preventDefault();

            loadTableWithFilter();
        });

        function loadTableWithFilter() {
            // Call the submitForm function to submit the form
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: $('#filterEquListForm').serialize() + '&mode=equmaster',
                dataType: 'json',
                success: function(response) {
                    $('#filterEquListModal').modal('hide');

                    if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else if (response.status == 'period') {
                        alert(response.message);
                    } else {
                        if (response.data !== null) {
                            table.clear().rows.add(response.data).draw();
                        } else {
                            table.clear().draw();
                        }
                    }

                }
            });
        }

        var table = $('#equMasterTable').DataTable({
            fixedHeader: true,
            responsive: true,
            dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            buttons: [{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }],
            // order: [[0, 'desc']],
            // columnDefs: [{
            //     target: 1,
            //     visible: false,
            //     searchable: false
            // }],
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
                    data: 4
                },
                {
                    data: 5
                },
                {
                    data: 6
                },
                {
                    data: 7
                },
                {
                    data: 8
                },
                {
                    data: 9
                },
                {
                    data: 10
                },
                // Last column is empty
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-sm btn-primary">EDIT</button>';
                    }
                }
            ]

        });

        $(document).ready(function() {
            // loadEquMasterTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>