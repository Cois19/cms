<?php
include '../../database/connect.php';
include '../../users/session.php';

$que = $_GET['id'];
$model = $_GET['model'];
$hideIfNot1 = '';

$query4 = "SELECT * FROM tlabelrules WHERE que = $que";
$result4 = mysqli_query($conn, $query4);
if ($result4 && mysqli_num_rows($result4) > 0) {
    $row4 = mysqli_fetch_assoc($result4);

    // if ($row4['tstatus'] != 1) {
    //     $hideIfNot1 = "d-none";
    // }
} else {

    // header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Scan</title>
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
        <?php include '../../modals/delivery_order/resetM.php'; ?>
        <?php include '../../modals/delivery_order/deleteDoM.php'; ?>
        <?php include '../../modals/delivery_order/grM.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/delivery_order/scanCompleteM.php'; ?>
        <?php include '../../modals/delivery_order/deleteISNM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>
        <?php include '../../modals/loadingSpinnerM.php'; ?>
        <?php include '../../modals/isnDownloadCompleteM.php'; ?>
        <?php include '../../modals/label/scanLabelM.php'; ?>

        <div class="card border-dark mb-3" id="doCard">
            <div class="card-header">
                Label Rules
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <h5 class="card-title">Model</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['model']; ?>
                        </p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <h5 class="card-title">Separator</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['ruleseparator']; ?>
                        </p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <h5 class="card-title">Remarks</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['remarks']; ?>
                        </p>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <h5 class="card-title">CD</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['cd']; ?>
                        </p>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="btn-group mb-3 mb-lg-0 <?php echo $hideIfNot1 ?>">
                        <button id="scanLabelBtn" href="#" data-bs-toggle="modal" data-bs-target="#scanLabelModal"
                            class="btn btn-primary">Scan
                            Label</button>
                    </div>
                    <!-- <div class="btn-group <?php echo $hideIfNot1 ?>">
                        <button href="#" data-bs-toggle="modal" data-bs-target="#resetModal" class="btn btn-danger"
                            <?php echo ($utype == 3) ? 'disabled' : ''; ?>>Reset
                            ISN</button>
                        <button href="#" data-bs-toggle="modal" data-bs-target="#deleteDoModal" class="btn btn-danger"
                            <?php echo ($utype != 1) ? 'disabled' : ''; ?>>Delete
                            DO</button>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="labelTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>QUE</th>
                        <th>MODEL</th>
                        <th>TYPE</th>
                        <th>LABEL</th>
                        <th>CD</th>
                        <th colspan="1" rowspan="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#newLabelForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: $(this).serialize() + '&mode=labelscan' + '&ruleQue=<?php echo $que; ?>',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        playPassSound();
                        $('#newLabelForm')[0].reset();
                        $('#label').focus();
                        // updateQtyCount();
                        // loadTable();
                    // } else if (response.status == 'fail') {
                    //     playFailSound(function () {
                    //         playDuplicateSound();
                    //     });
                    //     $('#newLabelForm')[0].reset();
                    //     $('#isn').focus();
                    // } else if (response.status == 'empty') {
                    //     alert('ISN Cannot be Empty!');
                    // } else if (response.status == 'timeout') {
                    //     window.location.href = '/vsite/cms/users/login.php';
                    // } else if (response.status == 'length') {
                    //     playFailSound();
                    //     $('#newLabelForm')[0].reset();
                    //     $('#isn').focus();
                    // } else if (response.status == 'wrongisn') {
                    //     playFailSound(function () {
                    //         playInvalidIsnSound();
                    //     });
                    //     $('#newLabelForm')[0].reset();
                    //     $('#isn').focus();
                    } else {
                        alert('Failed');
                    }
                }
            });
        });

        var passSound = new Audio('/vsite/cms/assets/audio/pass.mp3');
        var failSound = new Audio('/vsite/cms/assets/audio/fail.mp3');
        var duplicateSound = new Audio('/vsite/cms/assets/audio/duplicate.mp3');
        var dnCompleteSound = new Audio('/vsite/cms/assets/audio/dn complete.mp3');
        var wrongPnSound = new Audio('/vsite/cms/assets/audio/wrong pn.mp3');
        var invalidIsn = new Audio('/vsite/cms/assets/audio/invalid isn.mp3');

        function playSound(sound, callback) {
            var clone = sound.cloneNode(true);
            clone.play();
            clone.onended = function () {
                if (typeof callback === 'function') {
                    callback();
                }
            };
        }

        function playPassSound(callback) {
            playSound(passSound, callback);
        }

        function playFailSound(callback) {
            playSound(failSound, callback);
        }

        function playDuplicateSound() {
            playSound(duplicateSound);
        }

        function playDnCompleteSound() {
            playSound(dnCompleteSound);
        }

        function playWrongPnSound() {
            playSound(wrongPnSound);
        }

        function playInvalidIsnSound() {
            playSound(invalidIsn);
        }

        function loadTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'label', model: '<?php echo $model; ?>' },
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

        var table = $('#labelTable').DataTable({
            fixedHeader: true,
            responsive: true,
            dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap me-3"<"remaining-qty me-2"><"scanned-qty">><"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
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
            order: [[0, 'desc']],
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
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[0];
                        return '<button type="button" class="btn btn-sm btn-warning" onClick="deleteISN(\'' + token + '\')">DELETE</button>';
                    }
                }
            ]
        });

        $(document).ready(function () {
            // updateQtyCount();
            loadTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>