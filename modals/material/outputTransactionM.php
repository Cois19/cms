<div class="modal fade modal-lg" id="outputTransactionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="outputTransactionForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>New Outgoing</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="o_locationParent">
                        <label for="o_location">Location</label>
                        <select class="form-select" name="o_location" id="o_location"
                            onchange="handleSelectChange(this)">
                            <option id="defaultLocation" disabled selected>Select Location</option>
                            <?php
                            $sql1 = "SELECT code, description FROM mc_location WHERE type = 'OUT'";
                            $locations = mysqli_query($conn, $sql1);
                            while ($location = mysqli_fetch_array($locations, MYSQLI_ASSOC)):
                                ;
                                ?>
                                <option value="<?php echo $location['code']; ?>">
                                    <?php echo $location['code']; ?> -
                                    <?php echo $location['description']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group" id="o_linecodeParent">
                        <label for="o_linecode">Line Code</label>
                        <select class="form-select" name="o_linecode" id="o_linecode" disabled>
                            <option id="defaultLinecode" disabled selected>Select Line Code</option>
                            <?php
                            $sql1 = "SELECT linecode FROM mc_linecode";
                            $areas = mysqli_query($conn, $sql1);
                            while ($area = mysqli_fetch_array($areas, MYSQLI_ASSOC)):
                                ;
                                ?>
                                <option value="<?php echo $area['linecode']; ?>">
                                    <?php echo $area['linecode']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="o_hu">HU</label>
                        <input type="text" class="form-control" name="o_hu" id="o_hu" placeholder="HU">
                    </div>
                    <div class="form-group">
                        <label for="o_material">Material</label>
                        <input type="text" class="form-control" name="o_material" id="o_material"
                            placeholder="Material">
                    </div>
                    <p id="o_materialVerification"></p>
                    <div class="row">
                        <div class="form-group col-5">
                            <label for="o_fullbox">Full Box</label>
                            <input type="number" class="form-control" name="o_fullbox" id="o_fullbox"
                                placeholder="Full Box" disabled>
                        </div>
                        <div class="col-2 row text-center">
                            <div></div>
                            <div class="d-flex justify-content-center align-self-center">X</div>
                        </div>
                        <div class="form-group col-5" id="o_spqParent">
                            <label for="o_spq">SPQ</label>
                            <select class="form-select" name="o_spq" id="o_spq">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="o_qty">Qty</label>
                        <input type="number" class="form-control" name="o_qty" id="o_qty" placeholder="Qty" readonly>
                    </div>
                    <div class="form-group">
                        <label for="o_lossqty">Loss Qty</label>
                        <input type="number" class="form-control" name="o_lossqty" id="o_lossqty"
                            placeholder="Loss Qty">
                    </div>
                    <div class="form-group">
                        <label for="o_totalqty">Total Qty</label>
                        <input type="number" class="form-control mb-2" name="o_totalqty" id="o_totalqty"
                            placeholder="Total Qty" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="outputTransactionSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('[autofocus]').focus();
        });
    });

    function handleSelectChange(selectedElement) {
        $("#o_linecode").val($("#defaultLinecode").val()).trigger("change.select2");

        // Get the other select element
        var linecodeSelect = document.getElementById('o_linecode');

        // Check if the selected location is "L001", then enable the linecode select
        if (selectedElement.value === 'L001') {
            linecodeSelect.disabled = false;
        } else {
            // If the selected location is not "L001", disable the linecode select
            linecodeSelect.disabled = true;
        }
    }

    $("#o_material").keyup(function () {
        // retrieve the selected value
        var selectedMaterial = $(this).val();

        $("#o_fullbox").val('');
        $("#o_qty").val('');
        $("#o_lossqty").val('');
        $("#o_totalqty").val('');

        $.ajax({
            url: 'dbCrudFunctions/checkMaterial.php',
            data: {
                material: selectedMaterial
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {
                    // add the new value
                    $('#o_materialVerification').text('Material Found');
                    $("#o_materialVerification").removeClass("badge text-bg-danger");
                    $("#o_materialVerification").addClass("badge text-bg-success");
                    $("#o_materialVerification").append(' <i class="bi bi-check-lg"></i>');
                    $("#o_spq").html(response.spq);
                    $("#o_fullbox").prop("disabled", false);
                } else if (response.status == 'fail') {
                    // add the new value
                    $('#o_materialVerification').text('Material Not Found');
                    $("#o_materialVerification").removeClass("badge text-bg-success");
                    $("#o_materialVerification").addClass("badge text-bg-danger");
                    $("#o_materialVerification").append(' <i class="bi bi-x-lg"></i>');
                    $("#o_spq").html(response.spq);
                    $("#o_fullbox").prop("disabled", true);
                } else if (response.status == 'timeout') {
                    window.location.href = '/vsite/cms/users/login.php';
                } else {
                    alert('Failed!');
                }
            }
        });
    });

    function updateAndCalculate() {
        // Update i_qty
        var fullBoxValue = parseFloat($('#o_fullbox').val()) || 0;
        var spqValue = parseFloat($('#o_spq').val()) || 0;
        var qtyValue = fullBoxValue * spqValue;
        $('#o_qty').val(qtyValue);

        // Calculate totalQty
        var lossQtyValue = parseFloat($('#o_lossqty').val()) || 0;
        var totalQtyValue = qtyValue + lossQtyValue;
        $('#o_totalqty').val(totalQtyValue);
    }

    function handleQtyChange() {
        // Calculate totalQty
        var qtyValue = parseFloat($('#o_qty').val()) || 0;
        var lossQtyValue = parseFloat($('#o_lossqty').val()) || 0;
        var totalQtyValue = qtyValue + lossQtyValue;
        $('#o_totalqty').val(totalQtyValue);
    }

    // Attach event handlers to trigger updateAndCalculate
    $('#o_fullbox').on('input', updateAndCalculate);
    $('#o_spq').on('change', updateAndCalculate);
    $('#o_qty').on('input', handleQtyChange);
    $('#o_lossqty').on('input', handleQtyChange);

    $('#o_linecode').select2({
        dropdownParent: $('#o_linecodeParent'),
        width: '100%'
    });

    $('#o_location').select2({
        dropdownParent: $('#o_locationParent'),
        width: '100%'
    });
</script>