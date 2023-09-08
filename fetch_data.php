<?php
// Check if the 'shipment_list_id' parameter is present in the query string
if (isset($_POST['shipment_list_id'])) {
    // Retrieve the 'shipment_list_id' value from the query string
    $shipment_list_id = $_POST['shipment_list_id'];

    // Construct the API URL
    $url = 'http://snws07:8000/api/MES/Ext/GetSMTShipmentDetail';

    // Create an array of data to send in the POST request
    $data = array('shipment_list_id' => $shipment_list_id);

    // Create a context for the POST request
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);

    // Make the POST request and retrieve the response
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        // Error handling if the request fails
        echo 'Error: Unable to fetch data from the API.';
    } else {
        // Decode the JSON response
        $responseData = json_decode($response, true);

        // Extract the data within the 'DATA' field
        $data = json_decode($responseData['DATA'], true);

        // Loop through the shippingNoticeDetails and insert into MySQL table
        foreach ($data[0]['shippingNoticeDetails'] as $shippingNotice) {
            $shippingNoticeId = $shippingNotice['shippingNoticeId'];
            $messageDetailSN = $shippingNotice['messageDetailSN'];
            $partNumber = $shippingNotice['partNumber'];
            $CustomerpartNumber = $shippingNotice['CustomerpartNumber'];
            $CustomerProject = $shippingNotice['CustomerProject'];

            echo $shippingNoticeId . ' ' . $messageDetailSN . ' ' . $partNumber;
            echo '<br>';
        }

    }
} else {
    echo 'Missing shipment_list_id parameter in the query string.';
}
?>
