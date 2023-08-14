<div class="modal fade modal-lg" id="addPeriodModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="addPeriodForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Add Period</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="periodname">Period Name</label>
                        <input type="text" class="form-control" name="periodname" id="periodname" placeholder="Period Name" autofocus>
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
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="Description">
                    </div>
                    <div class="form-group mb-3">
                        <label for="p_remarks">Remarks</label>
                        <input type="text" class="form-control" name="p_remarks" id="p_remarks" placeholder="Remarks">
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