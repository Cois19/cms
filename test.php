<!-- <?php phpinfo() ?> -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Data</title>
    <?php include 'scripts.php' ?>
</head>

<body>
    <!-- <?php include 'navbar2.php' ?> -->
    <form method="POST" action="fetch_data.php">
        Shipment List ID: <input type="text" name="shipment_list_id">
        <input type="submit" value="Fetch Data">
    </form>
</body>



</html>