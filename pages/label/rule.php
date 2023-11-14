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
    <title>Label Check</title>
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
        <?php include '../../modals/label/addLabelRulesM.php' ?>
        
        <h2>Label Checking</h2>
        <hr>
        <button class="btn btn-primary mb-3" type="button" href="#" data-bs-toggle="modal" data-bs-target="#addLabelRulesModal"><i class="bi bi-plus-circle"></i> Add Label Rules</button>
        <div class="table-responsive">
            <table id="rulesTable" class="table">
                <thead>
                    <tr>
                        <th>QUE</th>
                        <th>MODEL</th>
                        <th>TYPE</th>
                        <th>SEPARATOR</th>
                        <th>RULES</th>
                        <th>REMARKS</th>
                        <th>CD</th>
                        <th colspan="1" rowspan="1"></th>
                    </tr>
                </thead>
            </table>
        </div>

        <?php include '../../footer.php' ?>
    </div>

    <script>
        $(document).ready(function () {
            loadRulesTable();
        });

        function newFields() {
            var count = $('#additionalFields .field-set').length + 1; // Get the current number of input fields and add 1
            var html = '<h5>Label Rule ' + count + '</h5>' +
                '<div style="display: flex;" class="field-set">' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="fixedLength' + count + '">Fixed Length</label>' +
                '<input type="text" class="form-control" name="fixedLength' + count + '" id="fixedLength' + count + '" placeholder="Example: 3">' +
                '</div>' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="requiredString' + count + '">Required String</label>' +
                '<input type="text" class="form-control" name="requiredString' + count + '" id="requiredString' + count + '" placeholder="Example: ssid:">' +
                '</div>' +
                '<div class="form-group mb-3" style="margin-right: 8px; width: 22%;">' +
                '<label for="fixedString' + count + '">Fixed String</label>' +
                '<input type="text" class="form-control" name="fixedString' + count + '" id="fixedString' + count + '" placeholder="Example: CM">' +
                '</div>' +
                '<button type="button" class="btn btn-secondary align-self-center remove-fieldset" style="height: 50%; margin-top: 8px;" ' + (count == 1 ? 'disabled' : '') + '><i class="bi bi-x-circle"></i></button>' +
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

        $('#inputRuleForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insert.php',
                data: $(this).serialize() + '&mode=labelrule',
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        // alert('ok');
                        location.reload();
                    } else if (response.status == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else if (response.status == 'queryfail') {
                        alert('Query Failed');
                    } else {
                        alert('Something Unexpected Happened');
                    }
                }
            });
        });

        function loadRulesTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'rules' },
                dataType: 'json',
                success: function (response) {
                    if (response.data) {
                        table.clear().rows.add(response.data).draw();
                    } else {
                        table.clear().draw();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        var table = $('#rulesTable').DataTable({
            fixedHeader: true,
            responsive: true,
            // dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            // buttons: [
            //     {
            //         extend: 'collection',
            //         text: 'Export',
            //         buttons: [
            //             'copy',
            //             'excel',
            //             'csv',
            //             'pdf',
            //             'print'
            //         ]
            //     }
            // ],
            // order: [[0, 'desc']],
            columnDefs: [
                {
                    target: 0,
                    visible: false,
                    searchable: false
                }
            ],
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6 },
                {
                    data: null,
                    render: function (data, type, row) {
                        var token = row[0];
                        var href = '/vsite/cms/pages/label/scan.php?id=' + token;
                        return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTagModal" onClick="editmodal(\'' + token + '\')">EDIT</button>' +
                            '<a href="' + href + '" target="_blank"><button type="button" class="btn btn-sm btn-success" href="">SCAN</button></a></div>';
                    }
                }
            ]
        });

        <?php include '../../dbCrudFunctions/bodyScripts.js' ?>
    </script>
    <?php include '../../styles/tableOverride.php' ?>
</body>

<?php mysqli_close($conn); ?>

</html>