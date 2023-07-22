<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="scannerForm">
        <label for="scannerInput">Scan Barcode:</label>
        <input type="text" id="scannerInput" name="barcode" autofocus>
        <input type="submit" value="Submit">
    </form>
    <div id="result"></div>
</body>
<script>
    // Function to handle form submission
    function submitForm(event) {
        event.preventDefault(); // Prevent the default form submission behavior

        const barcode = $("#scannerInput").val();

        $.ajax({
            type: "POST",
            url: "submit.php",
            data: { barcode: barcode },
            success: function (response) {
                $("#result").html(response);
                $("#scannerForm")[0].reset(); // Clear the form input after successful submission
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

    // Add event listener to the form
    $("#scannerForm").submit(submitForm);
</script>

</html>