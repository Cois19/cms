<div class="modal fade modal-lg" id="addLabelRulesModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="inputRuleForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Add Label Rules</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" name="model" id="model" placeholder="Model">
                    </div>
                    <div class="form-group mb-3">
                        <label for="labelType">Type</label>
                        <input type="text" class="form-control" name="labelType" id="labelType" placeholder="Type">
                    </div>
                    <div class="form-group mb-3">
                        <label for="ruleSeparator">Rule Separator <span class="text-dark text-opacity-50"><i>(Leave empty if not needed.)</i></span></label>
                        <input type="text" class="form-control" name="ruleSeparator" id="ruleSeparator"
                            placeholder="Example: , or ; or -">
                    </div>
                    <div id="additionalFields">
                        <div class="d-flex align-items-center mb-3">
                            <button type="button" onclick="newFields()" class="btn btn-secondary me-3"><i
                                    class="bi bi-plus-circle"></i> Add Label Rules</button>
                            <span class="text-dark text-opacity-50"><i>(Leave empty if not needed.)</i></span>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="labelRemarks">Remarks</label>
                        <input type="text" class="form-control" name="labelRemarks" id="labelRemarks"
                            placeholder="Remarks">
                    </div>
                    <!-- <div class="form-group mb-3">
                        <label for="approvaldate">Approval Date</label>
                        <input type="date" class="form-control" id="approvaldate" placeholder="Approval Date">
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="inputBtn" type="submit" class="btn btn-success">Submit</button>
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

</script>