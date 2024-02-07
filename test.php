<!-- <?php phpinfo() ?> -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Data</title>
    <?php include 'scripts.php' ?>
    <style rel="stylesheet" media="print">
        body {
            color: black;
        }
    </style>
</head>

<body>
    <!-- <?php include 'navbar2.php' ?> -->
    <!-- <form method="POST" action="fetch_data.php">
        Shipment List ID: <input type="text" name="shipment_list_id">
        <input type="submit" value="Fetch Data">
    </form> -->

    <div class="text-center" style="margin: 0 auto">
        <?php
        require 'vendor/autoload.php';

        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('P00000278882_999999', $generator::TYPE_CODE_128, 2, 50)) . '">';
        ?>
        <p>HU: P00000278882_999999</p>
        <hr>

        <?php
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('1', $generator::TYPE_CODE_128, 2, 50)) . '">';
        ?>
        <p>HU: 1</p>
        <hr>

        <?php

        use chillerlan\QRCode\{QRCode, QROptions};

        $data = 'P00000278882_999999999';

        // Create QROptions instance and set the desired size
        $options = new QROptions;
        $options->scale = 3;

        // Create QRCode instance with options
        $qrcode = new QRCode;
        $qrcode->setOptions($options);

        // Render and display the QR code
        ?>
        <div style="width: 100px; margin: 0 auto">
            <?php
            echo '<img src="' . $qrcode->render($data) . '" alt="QR Code" />';
            ?>
        </div>
        <p>HU: P00000278882_999999999</p>
    </div>
</body>



</html>