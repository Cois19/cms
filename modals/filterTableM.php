<div class="modal fade modal-lg" id="filterTableModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="filterTableForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Filter Table</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="do">DO Number</label>
                        <input type="text" class="form-control" name="do" id="do" placeholder="DO" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="isn">ISN Number</label>
                        <input type="text" class="form-control" name="isn" id="isn" placeholder="ISN" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pno">Part Number</label>
                        <input type="text" class="form-control" name="pno" id="pno" placeholder="Part Number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="pna">Part Name</label>
                        <input type="text" class="form-control" name="pna" id="pna" placeholder="Part Name">
                    </div>
                    <div class="row">
                        <div class="form-group mb-3 col-6">
                            <label for="startDate">Start Date</label>
                            <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date">
                        </div>
                        <div class="form-group mb-3 col-6">
                            <label for="endDate">End Date</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="filterSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
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