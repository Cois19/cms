<?php
include '../../database/connect.php';
include '../../users/session.php';

$que = $_GET['id'];
$hideIfNot1 = '';

$query4 = "SELECT * FROM tinventorytag WHERE que = $que";
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
    <div class="container mt-3 border border-dark">

        <div class="row">
            <div class="col-7"><strong>PT. SAT NUSAPERSADA Tbk</strong></div>
            <div class="col-2 text-end"><strong>Period</strong> :</div>
            <div class="col-3 ps-0">2019-12</div>
        </div>
        <div class="row">
            <div class="col-7"><strong>PEGATRON Dept</strong></div>
            <div class="col-2 text-end"><strong>Tag No</strong> :

            </div>
            <div class="col-3 ps-0">
                <?php echo $row4['tagno']; ?>
            </div>
        </div>
        <div class="row text-center border-bottom border-dark">
            <h5><strong>INVENTORY TAG</strong></h5>
        </div>
        <div class="row">
            <div class="col-4 text-end"><strong>Part No</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['partno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-4 text-end"><strong>Part Name</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['partno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-4 text-end"><strong>Area</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['partno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-4 text-end"><strong>Sub Loc</strong> : </div>
            <div class="col-8 ps-0">
                <?php echo $row4['partno']; ?>
            </div>
        </div>
        <div class="row p-1 d-flex justify-content-between">
            <div class="col-8 border border-dark"><strong>Quantity</strong> : </div>
            <div class="col-3 border border-dark"><strong>UOM</strong> : </div>
        </div>
        <div class="p-1">
            <div class="row border border-dark">
                <div class="row">
                    <div class="col-2 text-end"><strong>Remark</strong> : </div>
                    <div class="col-8">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
                </div>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
                </div>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            // window.print();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>