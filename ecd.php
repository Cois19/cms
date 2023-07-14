<?php
include 'database/connect.php';
include 'users/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCAN</title>
    <?php include 'scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">
        <!-- Button trigger create modal -->
        <!-- <div class="mb-4">
            <button type="button" class="btn btn-primary btn-sm my-2 <?php echo $hide ?>" data-bs-toggle="modal"
                data-bs-target="#createModal" style="margin-right: 10px;">
                Create DO
            </button>
            <button type="button" class="btn btn-light btn-sm my-2 <?php echo $hide ?>" data-bs-toggle="modal"
                data-bs-target="#modelModal">
                Add Model
            </button>
        </div> -->

        <!-- Modals -->
        <?php include 'modals/create.php'; ?>
        <?php include 'modals/add_model.php'; ?>
        <?php include 'modals/edit.php'; ?>
        <?php include 'modals/uploading.php'; ?>
        <?php include 'modals/isn.php'; ?>

        <h2>DO List</h2>
        <hr>
        <table id="doTable" class="table">
            <thead>
                <tr>
                    <th>QUE</th>
                    <th>DO NUMBER</th>
                    <th>PALLET ID</th>
                    <th>PART NO</th>
                    <th>PART NAME</th>
                    <th>MODEL</th>
                    <th>QTY</th>
                    <th>BOX COUNT</th>
                    <th>STATUS</th>
                    <th colspan="1" rowspan="1"></th>
                </tr>
            </thead>
            <tbody>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="1" rowspan="1"></td>
            </tbody>
        </table>
    </div>

    <script>
        function loadISN(tdono) {
            var url = 'do.php?id=' + tdono;

            // Redirect to the new page
            window.location.href = url;
        }

        function loadDoTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'do' },
                dataType: 'json',
                success: function (response) {
                    // Clear the existing table data and redraw with new data
                    table.clear().rows.add(response.data).draw();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                    // Handle the error case if needed
                }
            });
        }

        $('#newDoForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    $('#newDoForm')[0].reset(); // Reset the form
                    $('#createModal').modal('hide');
                    // Construct the URL for the new page
                    var url = 'do.php?id=' + response.que;

                    // Redirect to the new page
                    window.location.href = url;

                }
            });
        });

        var table = $('#doTable').DataTable({
            // responsive: true,
            // dom: 'B<"d-flex flex-wrap justify-content-between"lf>rt<"d-flex flex-wrap justify-content-between"ip>',
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
            // order: [[0, 'desc']],
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
                {
                    data: 8,
                    render: function (data, type, row) {

                        return '<td class="'+ row[8] == 'ON GOING' ? 'badge text-bg-warning' '+>'  > + '' + row[8] + '</td>';
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[0];
                        return '<button type="button" class="btn btn-sm btn-primary" onClick="loadISN(\'' + token + '\')">ISN</button>';
                    }
                }
            ]
        });

        $(document).ready(function () {
            $("#doCard").hide();

            loadDoTable();
        });
    </script>

</body>

<?php mysqli_close($conn); ?>

</html>