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
    <title>Inventory Tag Summary</title>
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

        <h2>Summary</h2>
        <hr>
        <form method="post" id="filterForm">
            <div class="row">
                <div class="col-2">
                    <label for="periodFilter"><strong>Period :</strong></label>
                    <div id="periodFilterParent">
                        <select class="form-select" name="periodFilter" id="periodFilter">
                            <option disabled selected>Select Period</option>
                            <?php
                            $sql1 = "SELECT que, periodname FROM tperiod ORDER BY que DESC";
                            $periods = mysqli_query($conn, $sql1);
                            while ($period = mysqli_fetch_array($periods, MYSQLI_ASSOC)):
                                ;
                                ?>
                                <option value="<?php echo $period['que']; ?>">
                                    <?php echo $period['periodname']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <label for="periodFilter"><strong>Area :</strong></label>
                    <div id="areaFilterParent">
                        <select class="form-select" name="areaFilter" id="areaFilter">
                            <option disabled selected>Select Area</option>
                            <option value="">ALL</option>
                            <?php
                            $sql1 = "SELECT areaname FROM tarea ORDER BY que";
                            $periods = mysqli_query($conn, $sql1);
                            while ($period = mysqli_fetch_array($periods, MYSQLI_ASSOC)):
                                ;
                                ?>
                                <option value="<?php echo $period['areaname']; ?>">
                                    <?php echo $period['areaname']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <table id="reportingTable" class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TAG NO</th>
                    <th>OWNER</th>
                    <th>AREA CODE</th>
                    <th>AREA NAME</th>
                    <th>SUB LOC</th>
                    <th>ACCOUNT</th>
                    <th>MODEL</th>
                    <th>PART NO</th>
                    <th>PART DESC</th>
                    <th>QTY</th>
                    <th>UOM</th>
                    <th>DATE</th>
                </tr>
            </thead>
        </table>
    </div>
    <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#periodFilter, #areaFilter').on('change', function () {
            // Call the submitForm function to submit the form
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: $('#filterForm').serialize() + '&mode=reporting',
                dataType: 'json',
                success: function (response) {
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
        });

        function loadReportingTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'reporting' },
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

        var table = $('#reportingTable').DataTable({
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
            columnDefs: [
                {
                    target: 12,
                    visible: false,
                    searchable: false
                },
            ],
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        var startNumber = 1;
                        var currentNumber = meta.row + startNumber;
                        return currentNumber;
                    }
                },
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
                { data: 10 },
                { data: 11 }
            ]
        });

        $('#periodFilter').select2({
            dropdownParent: $('#periodFilterParent'),
            width: '100%'
        });

        $('#areaFilter').select2({
            dropdownParent: $('#areaFilterParent'),
            width: '100%',
        });

        $(document).ready(function () {
            // loadReportingTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>