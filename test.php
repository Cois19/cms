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

    <?php
    function getParts($string, $positions){
        $parts = array();
    
        foreach ($positions as $position){
            $parts[] = substr($string, 0, $position);
            $string = substr($string, $position);
        }
    
        return $parts;
    }

    $parts = getParts('ID2401010001', array(2,2,2,2,4));
    print_r($parts);
    echo $parts[0];
    echo $parts[1];
    echo $parts[2];
    echo $parts[3];
    echo $parts[4];

    ?>
</body>



</html>