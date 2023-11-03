<?php
include '../../database/connect.php';
include '../../users/session.php';

$que = $_GET['id'];
$hideIfNot1 = '';

$query4 = "SELECT DATE_FORMAT(tperiod.periodstart, '%Y-%m') AS periodname, tinventorytag.tagno, tinventorytag.partno, tpartmaster.partdesc, tarea.areacode, tarea.areaname, tinventorytag.subloc, tinventorytag.qty, tinventorytag.uom
            FROM tinventorytag JOIN tperiod ON tperiod.que = tinventorytag.tperiodque
            JOIN tpartmaster ON tpartmaster.partno = tinventorytag.partno
            JOIN tarea ON tarea.areacode = tinventorytag.areacode
            WHERE tinventorytag.que = $que";
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
    <title>Inventory Tag</title>
    <?php include '../../scripts.php' ?>
    <link rel="stylesheet" href="../../styles/custom.css">
</head>

<body>
    <h4 class="row text-center"><strong>INVENTORY TAG</strong></h4>
    <div class="container mt-3 border border-dark text-dark">

        <div class="row">
            <div class="col-12 fs-5"><strong>PT. SAT NUSAPERSADA Tbk</strong></div>
        </div>
        <div class="row">
            <div class="col-12 fs-5"><strong>PEGATRON Dept</strong></div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 fs-5"><strong>Period</strong> :
                <?php echo $row4['periodname']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 fs-5"><strong>Tag No</strong> :
                <?php echo $row4['tagno']; ?>
            </div>
        </div>
        <div class="row text-center border-bottom border-dark">
        </div>
        <div class="row fs-5">
            <div class="col-4"><strong>Part No</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['partno']; ?>
            </div>
        </div>
        <div class="row fs-5">
            <div class="col-4"><strong>Part Name</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['partdesc']; ?>
            </div>
        </div>
        <div class="row fs-5">
            <div class="col-4"><strong>Area</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['areacode']; ?> -
                <?php echo $row4['areaname']; ?>
            </div>
        </div>
        <div class="row fs-5">
            <div class="col-4"><strong>Sub Loc</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['subloc']; ?>
            </div>
        </div>
        <div class="row fs-4 p-1 d-flex justify-content-between">
            <div class="col-8 border border-dark"><strong>Qty</strong> :
                <?php echo $row4['qty']; ?>
            </div>
            <div class="col-3 border border-dark"><strong>UOM</strong> :
                <?php echo $row4['uom']; ?>
            </div>
        </div>
        <div class="p-1">
            <div class="row border border-dark">
                <div class="row">
                    <div class="col-4 fs-5" style="height: 100px"><strong>Remark</strong> : </div>
                </div>
            </div>
        </div>
        <div class="row p-1 text-center">
            <div class="col-4 border border-dark">
                <div class="row">
                    <div class="col-12 border-bottom border-dark fs-5">
                        <strong>Prepared</strong>
                    </div>
                    <div class="d-flex align-items-end justify-content-center" style="height: 100px">
                        <strong><em>(Store)</em></strong>
                    </div>
                </div>
            </div>
            <div class="col-4 border border-dark">
                <div class="row">
                    <div class="col-12 border-bottom border-dark fs-5">
                        <strong>Auditor1</strong>
                    </div>
                    <div class="d-flex align-items-end justify-content-center" style="height: 100px">
                        <strong><em>(PTSN)</em></strong>
                    </div>
                </div>
            </div>
            <div class="col-4 border border-dark">
                <div class="row">
                    <div class="col-12 border-bottom border-dark fs-5">
                        <strong>Auditor2</strong>
                    </div>
                    <div class="d-flex align-items-end justify-content-center" style="height: 100px">
                        <strong><em>(PEGATRON)</em></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="row"><em>* Mohon Tulis Nama Lengkap</em></div>

    </div>

    <script>
        $(document).ready(function () {
            window.print();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>