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
    <title>ISL Search</title>
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
        <?php include '../../modals/delivery_order/isn.php'; ?>
        <?php include '../../modals/delivery_order/resetM.php'; ?>
        <?php include '../../modals/delivery_order/deleteDoM.php'; ?>
        <?php include '../../modals/delivery_order/grM.php'; ?>
        <?php include '../../modals/delivery_order/filterIslTableM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>

        <h2>ISL Search</h2>
        <hr>
        <button id="filterIslTableBtn" href="#" data-bs-toggle="modal" data-bs-target="#filterIslTableModal"
            class="btn btn-primary mb-3">Filter Table</button>
        <h5>DO Quantity : <span id="doQty"></span></h5>
        <div class="table-responsive">
            <table id="islTable" class="table">
                <thead>
                    <tr>
                        <th>ISL</th>
                        <th>ISN</th>
                        <th>PART NO</th>
                        <th>MODEL</th>
                        <th>PALLET</th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#filterIslTableForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: $(this).serialize() + '&mode=isl',
                dataType: 'json',
                success: function (response) {
                    $('#filterIslTableModal').modal('hide');

                    if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: 'dbCrudFunctions/getDoQty.php',
                            data: { isl: response.data[0][0] },
                            success: function (qty) {
                                $('#doQty').text(qty);
                            }
                        })
                        if (response.data !== null) {
                            table.clear().rows.add(response.data).draw();
                        } else {
                            table.clear().draw();
                        }
                    }
                }
            });
        });

        var table = $('#islTable').DataTable({
            fixedHeader: true,
            responsive: true,
            dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ]
                }
            ],
            // order: [[0, 'desc']],
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 }
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