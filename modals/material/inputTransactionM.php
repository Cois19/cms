<div class="modal fade modal-lg" id="inputTransactionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="inputTransactionForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>New Incoming</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="i_hu">HU</label>
                        <input type="text" class="form-control" name="i_hu" id="i_hu" placeholder="HU" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="i_material">Material</label>
                        <input type="text" class="form-control" name="i_material" id="i_material"
                            placeholder="Material">
                    </div>
                    <p id="i_materialVerification"></p>
                    <div class="row">
                        <div class="form-group col-5">
                            <label for="i_fullbox">Full Box</label>
                            <input type="number" class="form-control" name="i_fullbox" id="i_fullbox"
                                placeholder="Full Box" disabled>
                        </div>
                        <div class="col-2 row text-center">
                            <div></div>
                            <div class="d-flex justify-content-center align-self-center">X</div>
                        </div>
                        <div class="form-group col-5" id="i_spqParent">
                            <label for="i_spq">SPQ</label>
                            <select class="form-select" name="i_spq" id="i_spq">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="i_qty">Qty</label>
                        <input type="number" class="form-control" name="i_qty" id="i_qty" placeholder="Qty" readonly>
                    </div>
                    <div class="form-group">
                        <label for="i_lossqty">Loss Qty</label>
                        <input type="number" class="form-control" name="i_lossqty" id="i_lossqty"
                            placeholder="Loss Qty">
                    </div>
                    <div class="form-group">
                        <label for="i_totalqty">Total Qty</label>
                        <input type="number" class="form-control mb-2" name="i_totalqty" id="i_totalqty"
                            placeholder="Total Qty" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="inputTransactionSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
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

    $("#i_material").keyup(function () {
        // retrieve the selected value
        var selectedMaterial = $(this).val();

        $("#i_fullbox").val('');
        $("#i_qty").val('');
        $("#i_lossqty").val('');
        $("#i_totalqty").val('');

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
                    $('#i_materialVerification').text('Material Found');
                    $("#i_materialVerification").removeClass("badge text-bg-danger");
                    $("#i_materialVerification").addClass("badge text-bg-success");
                    $("#i_materialVerification").append(' <i class="bi bi-check-lg"></i>');
                    $("#i_spq").html(response.spq);
                    $("#i_fullbox").prop("disabled", false);
                } else if (response.status == 'fail') {
                    // add the new value
                    $('#i_materialVerification').text('Material Not Found');
                    $("#i_materialVerification").removeClass("badge text-bg-success");
                    $("#i_materialVerification").addClass("badge text-bg-danger");
                    $("#i_materialVerification").append(' <i class="bi bi-x-lg"></i>');
                    $("#i_spq").html(response.spq);
                    $("#i_fullbox").prop("disabled", true);
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
        var fullBoxValue = parseFloat($('#i_fullbox').val()) || 0;
        var spqValue = parseFloat($('#i_spq').val()) || 0;
        var qtyValue = fullBoxValue * spqValue;
        $('#i_qty').val(qtyValue);

        // Calculate totalQty
        var lossQtyValue = parseFloat($('#i_lossqty').val()) || 0;
        var totalQtyValue = qtyValue + lossQtyValue;
        $('#i_totalqty').val(totalQtyValue);
    }

    function handleQtyChange() {
        // Calculate totalQty
        var qtyValue = parseFloat($('#i_qty').val()) || 0;
        var lossQtyValue = parseFloat($('#i_lossqty').val()) || 0;
        var totalQtyValue = qtyValue + lossQtyValue;
        $('#i_totalqty').val(totalQtyValue);
    }

    // Attach event handlers to trigger updateAndCalculate
    $('#i_fullbox').on('input', updateAndCalculate);
    $('#i_spq').on('change', updateAndCalculate);
    $('#i_qty').on('input', handleQtyChange);
    $('#i_lossqty').on('input', handleQtyChange);
</script>