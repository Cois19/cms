<?php
include '../../database/connect.php';
include '../../users/session.php';

$que = $_GET['id'];

$query = "SELECT split_id, material, description, qty, uom, d_hu FROM mc_materialmaster WHERE que = $que";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$split_id = $row['split_id'];
$material = $row['material'];
$description = $row['description'];
$qty = $row['qty'];
$uom = $row['uom'];
$d_hu = $row['d_hu'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Split Label</title>
    <?php include '../../scripts.php' ?>
    <link rel="stylesheet" href="../../styles/custom.css" media="print">
</head>

<body>
    <div class="text-center" style="margin: 0 auto">
        <?php
        require '../../vendor/autoload.php';

        use chillerlan\QRCode\{QRCode, QROptions};

        // Create QROptions instance and set the desired size
        // $options = new QROptions;
        // $options->scale = 3;

        // Create QRCode instance with options
        $d_huQR = new QRCode;
        $split_idQR = new QRCode;

        // Render and display the QR code
        ?>
        <div style="width: 100px; margin: 0 auto">
            <?php
            echo '<img src="' . $d_huQR->render($d_hu) . '" alt="HU QR Code" />';
            ?>
        </div>
        <p>HU: <strong><?php echo $d_hu ?></strong></p>

        <div style="width: 100px; margin: 0 auto">
            <?php
            echo '<img src="' . $split_idQR->render($split_id) . '" alt="SPLIT ID QR Code" />';
            ?>
        </div>
        <p>SPLIT ID: <strong><?php echo $split_id ?></strong></p>
        <p>PART NO: <strong><?php echo $material ?></strong></p>
        <p>PART NAME: <strong><?php echo $description ?></strong></p>
        <div class="d-flex"></div>
        <p>QTY: <strong><?php echo $qty ?></strong> UOM: <strong><?php echo $uom ?></strong></p>
    </div>

    <script>
        $(document).ready(function() {
            window.print();
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>