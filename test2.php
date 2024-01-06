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
    <form method="post" id="addSAPEquipmentForm" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <!-- <label for="partmaster">Import Part Master</label>
                        <input type="text" class="form-control" name="partmaster" id="partmaster"
                            placeholder="Part Master"> -->
                Select File: <input type="file" name="sapequipment" id="sapequipment">
                <!-- <input type="submit" value="Upload and Parse"> -->
        </div>
            <button id="addSAPEquipmentBtn" type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>
<script>
    $('#addSAPEquipmentForm').submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData();
            formData.append('sapequipment', $('#sapequipment')[0].files[0]); // Add the file input
            formData.append('mode', 'importsapequipment');

            var file = $('#sapequipment')[0].files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var data = e.target.result;
                    var workbook = XLSX.read(data, { type: 'binary' });

                    // Assuming the first sheet is the one you want to work with
                    var sheetName = workbook.SheetNames[0];
                    var sheet = workbook.Sheets[sheetName];

                    // Parse the sheet's data (you may want to iterate through rows and columns)
                    var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                    // Now you can send the JSON data to the server using AJAX
                    formData.append('excelData', JSON.stringify(jsonData)); // Add the parsed data to formData

                    $.ajax({
                        url: '/vsite/cms/dbCrudFunctions/insert.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 'success') {
                                // Handle success
                                alert('Import successful');
                            } else if (response.status == 'empty') {
                                alert('Please Select a File!');
                            } else if (response.status == 'fail') {
                                // Handle failure
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
                };

                reader.readAsBinaryString(file);
            } else {
                alert('Please Select a File!');
            }
        });
</script>

</html>