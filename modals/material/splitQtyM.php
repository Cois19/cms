<div class="modal fade modal-lg" id="splitQtyModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <form method="post" id="splitQtyForm"> -->
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Split Quantity</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newQty">QTY</label>
                        <input type="number" class="form-control" name="newQty" id="newQty" placeholder="QTY" autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="confirmSplitQtyBtn" type="submit" class="btn btn-primary">Submit</button>
                </div>
            <!-- </form> -->
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