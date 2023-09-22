<div class="modal fade modal-lg" id="filterIslTableModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="filterIslTableForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Filter Table</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="isl">ISL</label>
                        <input type="text" class="form-control" name="isl" id="isl" placeholder="ISL" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="isl_isn">ISN Number</label>
                        <input type="text" class="form-control" name="isl_isn" id="isl_isn" placeholder="ISN">
                    </div>
                    <div class="form-group mb-3">
                        <label for="isl_pno">Part Number</label>
                        <input type="text" class="form-control" name="isl_pno" id="isl_pno" placeholder="Part Number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" name="model" id="model" placeholder="Model">
                    </div>
                    <div class="form-group mb-3">
                        <label for="isl_pid">Pallet ID</label>
                        <input type="text" class="form-control" name="isl_pid" id="isl_pid" placeholder="Pallet ID">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="filterIslSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
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