<?php
include '../../database/connect.php';
include '../../users/session.php';

$que = $_GET['id'];
$hideIfNot1 = '';

$query4 = "SELECT * FROM tperiod WHERE que = $que";
$result4 = mysqli_query($conn, $query4);
if ($result4 && mysqli_num_rows($result4) > 0) {
    $row4 = mysqli_fetch_assoc($result4);
    // $tdono = $row4['tdono'];

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
    <title>Period Details</title>
    <?php include '../../scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include '../../navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">
        <!-- Modals -->
        <?php include '../../modals/delivery_order/create.php'; ?>
        <?php include '../../modals/changePassM.php'; ?>
        <?php include '../../modals/inventory/addPeriodM.php'; ?>
        <?php include '../../modals/inventory/deactivatePeriodM.php'; ?>
        <?php include '../../modals/inventory/addPartMasterM.php'; ?>
        <?php include '../../modals/inventory/addAreaM.php'; ?>

        <div class="card border-dark mb-3" id="doCard">
            <div class="card-header">
                Period
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Period Name</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['periodname']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Start</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['periodstart']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">End</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['periodend']; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Description</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['description']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Remarks</h5>
                        <p class="card-text mb-2">
                            <?php echo $row4['remarks']; ?>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5 class="card-title">Status</h5>
                        <?php
                        if ($row4['status'] == '0') {
                            echo '<p class="card-text mb-2 fw-bold text-success">INACTIVE</p>';
                        } else if ($row4['status'] == '1') {
                            echo '<p class="card-text mb-2 fw-bold text-danger">ACTIVE</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="btn-group mb-3 mb-lg-0 <?php echo $hideIfNot1 ?>">
                        <button href="#" data-bs-toggle="modal" data-bs-target="#addPartMasterModal"
                            class="btn btn-primary">Import Part</button>
                        <button href="#" data-bs-toggle="modal" data-bs-target="#addAreaModal"
                            class="btn btn-primary">Import Area</button>
                    </div>
                    <div class="btn-group <?php echo $hideIfNot1 ?>">
                        <button href="#" data-bs-toggle="modal" data-bs-target="#deactivatePeriodModal"
                            class="btn btn-danger" <?php echo ($utype != 1) ? 'disabled' : ''; ?>>Deactivate
                            Period</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <form method="post" id="addPeriodForm" class="col-6">
                <h4>Inventory Form</h4>
                <hr>
                <div class="form-group mb-3" id="areacodeParent">
                    <label for="areacode">Area Code</label>
                    <select class="form-select" name="areacode" id="areacode">
                        <option disabled selected>Select Area Code</option>
                        <?php
                        $sql1 = "SELECT areacode FROM tarea";
                        $areas = mysqli_query($conn, $sql1);
                        while ($area = mysqli_fetch_array($areas, MYSQLI_ASSOC)):
                            ;
                            ?>
                            <option value="<?php echo $area['areacode']; ?>">
                                <?php echo $area['areacode']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="tagno">Tag No</label>
                    <input type="text" class="form-control" name="tagno" id="tagno" placeholder="Tag No">
                </div>
                <div class="form-group mb-3">
                    <label for="subloc">Sub Location</label>
                    <input type="text" class="form-control" name="subloc" id="subloc" placeholder="Sub Location">
                </div>
                <div class="form-group mb-3">
                    <label for="partno">Part No</label>
                    <input type="text" class="form-control" name="partno" id="partno" placeholder="Part No">
                </div>
                <p id="partNoVerification"></p>
                <div class="form-group mb-3">
                    <label for="qty">Quantity</label>
                    <input type="number" class="form-control" name="qty" id="qty" placeholder="Quantity">
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="filterSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <?php include '../../footer.php' ?>
    </div>

    <script>
        $('#deletePeriodBtn').click(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/delete.php',
                data: { que: <?php echo "'" . $que . "'"; ?> },
                success: function (response) {
                    if (response == 'success') {
                        $('#deactivatePeriodModal').modal('hide');
                        var url = 'period_list.php';

                        window.location.href = url;
                    } else if (response == 'fail') {
                        alert('Reset Failed');
                    } else if (response == 'unauthorized') {
                        alert('You are not authorized');
                    } else if (response == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    }
                }
            });
        });

        $('#addPartMasterForm').submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData();
            formData.append('partmaster', $('#partmaster')[0].files[0]); // Add the file input
            formData.append('mode', 'importpart');
            formData.append('period_que', '<?php echo $que; ?>');

            $.ajax({
                url: '/vsite/cms/dbCrudFunctions/insert.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        // $('#addPeriodForm').reset();
                        // $('#addPeriodModal').modal('hide');
                        // var url = '/vsite/cms/pages/inventory/period.php?id=' + response.que;

                        // window.location.href = url;
                        alert('good');
                    } else if (response.status == 'empty') {
                        alert('Please Select a File!');
                    } else if (response.status == 'fail') {
                        // $('#addPeriodForm').reset();
                        // $('#createModal').modal('hide');
                        // var url = '/vsite/cms/pages/inventory/period.php?id=' + response.que;

                        // window.location.href = url;
                        alert('Part Already Exists!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Failed');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    console.log('Response:', xhr.responseText);
                }
            });
        });

        $('#addAreaForm').submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData();
            formData.append('area', $('#area')[0].files[0]); // Add the file input
            formData.append('mode', 'importarea');
            formData.append('period_que', '<?php echo $que; ?>');

            $.ajax({
                url: '/vsite/cms/dbCrudFunctions/insert.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        // $('#addPeriodForm').reset();
                        // $('#addPeriodModal').modal('hide');
                        // var url = '/vsite/cms/pages/inventory/period.php?id=' + response.que;

                        // window.location.href = url;
                        alert('good');
                    } else if (response.status == 'empty') {
                        alert('Please Select a File!');
                    } else if (response.status == 'fail') {
                        // $('#addPeriodForm').reset();
                        // $('#createModal').modal('hide');
                        // var url = '/vsite/cms/pages/inventory/period.php?id=' + response.que;

                        // window.location.href = url;
                        alert('Part Already Exists!');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Failed');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    console.log('Response:', xhr.responseText);
                }
            });
        });

        $('#areacode').on('change', function () {
            // retrieve the selected value
            var selectedAreaCode = $(this).val();

            // make an AJAX request to fetch the sixtypartnumbers for the selected model
            $.ajax({
                url: 'dbCrudFunctions/getTagNo.php',
                data: {
                    areaCode: selectedAreaCode,
                    que: <?php echo "'" . $que . "'"; ?>
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        // clear the previous value
                        $('#tagno').empty();

                        // add the new value
                        $('#tagno').val(response.data);
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Failed!');
                    }
                }
            });
        });

        $("#partno").keyup(function () {
            // retrieve the selected value
            var selectedPartNo = $(this).val();

            // make an AJAX request to fetch the sixtypartnumbers for the selected model
            $.ajax({
                url: 'dbCrudFunctions/checkPartNo.php',
                data: {
                    partNo: selectedPartNo
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'fail') {
                        // add the new value
                        $('#partNoVerification').text('PART NO EXISTS!');
                        $("#partNoVerification").removeClass("badge text-bg-danger");
                        $("#partNoVerification").addClass("badge text-bg-success");
                        $("#partNoVerification").append(' <i class="bi bi-check-lg"></i>');
                    } else if (response.status == 'success') {
                        // add the new value
                        $('#partNoVerification').text('PART NO DOESN\'T EXIST!');
                        $("#partNoVerification").removeClass("badge text-bg-success");
                        $("#partNoVerification").addClass("badge text-bg-danger");
                        $("#partNoVerification").append(' <i class="bi bi-x-lg"></i>');
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Failed!');
                    }
                }
            });
        });

        $('#areacode').select2({
            dropdownParent: $('#areacodeParent'),
            width: '100%'
        });

        $(document).ready(function () {
            // updateQtyCount();
            // loadTable();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>