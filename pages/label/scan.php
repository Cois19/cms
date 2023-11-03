<?php
include '../../database/connect.php';
include '../../users/session.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Period Details</title>
    <?php include '../../scripts.php' ?>
    <!-- use version 0.20.0 -->
    <!-- <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script> -->
</head>

<body>
    <!-- Navbar -->
    <?php include '../../navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">
        <!-- Modals -->

        <form method="post" id="inputRuleForm" class="col-6">
            <h4>Input Label Rules</h4>
            <hr>
            <div class="form-group mb-3">
                <label for="labelName">Label Name</label>
                <input type="text" class="form-control" id="labelName" placeholder="Label Name">
            </div>
            <div class="form-group mb-3">
                <label for="ruleSeparator">Rule Separator</label>
                <input type="text" class="form-control" id="ruleSeparator" placeholder="Example: , or ; or -">
            </div>
            <div id="additionalFields">
                <button type="button" onclick="newFields()" class="btn btn-secondary mb-3"><i
                        class="bi bi-plus-circle"></i> Add Label Rules</button>
            </div>
            <!-- <div class="form-group mb-3">
                <label for="approvaldate">Approval Date</label>
                <input type="date" class="form-control" id="approvaldate" placeholder="Approval Date">
            </div> -->
            <button id="inputBtn" type="submit" class="btn btn-success">Submit</button>
        </form>

        <?php include '../../footer.php' ?>
    </div>

    <script>
        $(document).ready(function () {
        });

        function newFields() {
            var count = $('#additionalFields .field-set').length + 1; // Get the current number of input fields and add 1
            var html = '<h5>Label Rule ' + count + '</h5>' +
                '<div style="display: flex;" class="field-set">' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="minlength' + count + '">Min Length</label>' +
                '<input type="text" class="form-control" id="minlength' + count + '" placeholder="Example: 3">' +
                '</div>' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="fixedLength' + count + '">Fixed Length</label>' +
                '<input type="text" class="form-control" id="fixedLength' + count + '" placeholder="Example: 3">' +
                '</div>' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="requiredString' + count + '">Required String</label>' +
                '<input type="text" class="form-control" id="requiredString' + count + '" placeholder="Example: ssid:">' +
                '</div>' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="fixedString' + count + '">Fixed String</label>' +
                '<input type="text" class="form-control" id="fixedString' + count + '" placeholder="Example: CM">' +
                '</div>' +
                '<button class="btn btn-secondary align-self-center remove-fieldset" style="height: 50%; margin-top: 8px;" ' + (count == 1 ? 'disabled' : '') + '><i class="bi bi-x-circle"></i></button>' +
                '</div>';
            $('#additionalFields').append(html); // Append the new input field to the additionalPartnumbers div
        }

        $('#additionalFields').on('click', '.remove-fieldset', function () {
            var fieldset = $(this).parent(); // Get the current fieldset
            var index = fieldset.index(); // Get the index of the current fieldset
            var h5 = fieldset.prev('h5'); // Get the preceding h5 element

            // Check if the current fieldset is the bottom-most one
            if (index == $('#additionalFields').children().length - 1) {
                fieldset.remove(); // Remove the current fieldset
                h5.remove(); // Remove the preceding h5 element

                // Check if the preceding fieldset is not the first one
                if (index > 0) {
                    var prevFieldset = $('#additionalFields .field-set').eq(index - 1);

                    // Enable the remove button of the preceding fieldset
                    prevFieldset.find('.remove-fieldset').prop('disabled', false);
                }
            } else {
                alert("Please remove the bottom one first.");
            }
        });

        $('#inputRuleForm').submit(function (event) {
            event.preventDefault(); // Prevent form submission


        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>