<div class="modal fade modal-lg" id="addAreaModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="addAreaForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Import Area</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <!-- <label for="partmaster">Import Part Master</label>
                        <input type="text" class="form-control" name="partmaster" id="partmaster"
                            placeholder="Part Master"> -->
                        Select File: <input type="file" name="area" id="area">
                        <!-- <input type="submit" value="Upload and Parse"> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="addAreaBtn" type="submit" class="btn btn-primary">Submit</button>
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