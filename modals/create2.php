<div class="modal fade modal-xl" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><strong>Create New</strong></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3" id="modelParent">
                    <label for="model">Model</label>
                    <select class="form-select" name="model" id="model">
                        <option disabled selected>Select Model</option>
                        <?php
                        // $useridSession = $_SESSION['uid'];
                        // $sql = "SELECT ucost FROM d_user WHERE userid = '$useridSession'";
                        // $ucosts = mysqli_query($conn, $sql);
                        // $ucost = mysqli_fetch_array($ucosts)[0];

                        // $ucost_array = explode("|", $ucost);
                        // foreach ($ucost_array as $value) {
                            $sql1 = "SELECT * FROM d_model WHERE coid = 'PG18'";
                            $models = mysqli_query($conn, $sql1);
                            while ($model = mysqli_fetch_array($models, MYSQLI_ASSOC)):
                                ;
                                ?>
                                <option value="<?php echo $model['mname']; ?>">
                                    <?php echo $model['mname']; ?>
                                </option>
                            <?php endwhile;
                        // }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="sixtypartnumber">60 Main Part Number</label>
                    <input type="text" class="form-control" id="sixtypartnumber" placeholder="60 Main Part Number">
                </div>
                <div id="additionalFields">
                    <button onclick="newFields()" class="btn btn-secondary mb-3"><i class="bi bi-plus-circle"></i> Add Part Number, Location, Checksum</button>
                </div>
                <div id="additionalFieldsAlert" class="alert alert-warning" role="alert">
                    Make sure the amount and order of Part Number, Location and Checksum match!
                    <br>
                    Pastikan jumlah dan urutan Part Number, Location dan Checksum sesuai!
                </div>
                <div class="form-group mb-3">
                    <label for="tpversion">TP Version</label>
                    <input type="text" class="form-control" id="tpversion" placeholder="TP Version">
                </div>
                <div class="form-group mb-3">
                    <label for="burner">Burner Machine</label>
                    <input type="text" class="form-control" id="burner" placeholder="Burner Machine">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="approval">
                    <label class="form-check-label" for="approval">Approval</label>
                </div>
                <div class="form-group mb-3">
                    <label for="approvaldate">Approval Date</label>
                    <input type="date" class="form-control" id="approvaldate" placeholder="Approval Date" disabled>
                </div>
                <div class="form-group mb-3">
                    <label for="verifiedby">Verified by</label>
                    <input type="text" class="form-control" id="verifiedby" placeholder="Verified by">
                </div>
                <div class="form-group mb-3">
                    <label for="c_remarks">Remarks</label>
                    <input type="text" class="form-control" id="c_remarks" placeholder="Remarks">
                </div>
                <div class="form-group mb-3">
                    <label for="att1">Upload 1: (Optional)</label>
                    <input style="display: block;" type="file" id="att1" name="att1">
                </div>
                <div class="form-group mb-3">
                    <label for="att2">Upload 2: (Optional)</label>
                    <input style="display: block;" type="file" id="att2" name="att2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submitBtn" type="button" class="btn btn-primary" onclick="add()">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#additionalFieldsAlert").hide();
    });

    $('#model').select2({
        dropdownParent: $('#modelParent'),
        width: '100%'
    });

    function newFields() {
        var count = $('#additionalFields .field-set').length + 1; // Get the current number of input fields and add 1
        var html = '<div style="display: flex;" class="field-set">' + 
                        '<div class="form-group mb-3" style="margin-right: 8px; width: 30%;">' +
                            '<label for="partnumber' + count + '">Part Number ' + count + '</label>' +
                            '<input type="text" class="form-control" id="partnumber' + count + '" placeholder="Part Number ' + count + '">' +
                        '</div>' +
                        '<div class="form-group mb-3" style="margin-right: 8px; width: 30%;">' +
                            '<label for="location' + count + '">Location ' + count + '</label>' +
                            '<input type="text" class="form-control" id="location' + count + '" placeholder="Location ' + count + '">' +
                        '</div>' +
                        '<div class="form-group mb-3" style="margin-right: 8px; width: 30%;">' +
                            '<label for="checksum' + count + '">Checksum ' + count + '</label>' +
                            '<input type="text" class="form-control" id="checksum' + count + '" placeholder="Checksum ' + count + '">' +
                        '</div>' +
                        '<button class="btn btn-secondary align-self-center remove-fieldset" style="height: 50%; margin-top: 8px;" ' + (count == 1 ? 'disabled' : '') + '><i class="bi bi-x-circle"></i></button>' +
                    '</div>';
        $('#additionalFields').append(html); // Append the new input field to the additionalPartnumbers div
        $("#additionalFieldsAlert").show();
    }

    $('#additionalFields').on('click', '.remove-fieldset', function() {
        var fieldset = $(this).parent(); // Get the current fieldset
        var index = fieldset.index(); // Get the index of the current fieldset

        // Check if the current fieldset is the bottom-most one
        if (index == $('#additionalFields').children().length - 1) {
            fieldset.remove(); // Remove the current fieldset

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
</script>