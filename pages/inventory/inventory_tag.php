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
    <div class="mb-3"></div>
    <div class="container">

        <div class="col-8 border">
            <div class="row">
                <div class="col-7">PT. SAT NUSAPERSADA Tbk</div>
                <div class="col-5 p-0">Period : 2019-12</div>
            </div>
            <div class="row">
                <div class="col-7">PEGATRON Dept</div>
                <div class="col-5 p-0">Tag No :
                    <?php echo $row4['tagno']; ?>
                </div>
            </div>
            <div class="col-12 text-center">
                INVENTORY TAG
            </div>
            <hr>
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