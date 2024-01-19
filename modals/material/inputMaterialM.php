<div class="modal fade modal-lg" id="inputMaterialModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="inputMaterialForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Input Material</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="material">Material</label>
                        <input type="text" class="form-control" name="material" id="material" placeholder="Material" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="m_description">Description</label>
                        <input type="text" class="form-control" name="m_description" id="m_description" placeholder="Description">
                    </div>
                    <div class="form-group mb-3">
                        <label for="spq_pallet">SPQ Pallet</label>
                        <input type="number" class="form-control" name="spq_pallet" id="spq_pallet" placeholder="SPQ Pallet">
                    </div>
                    <div class="form-group mb-3">
                        <label for="spq_box">SPQ Box</label>
                        <input type="number" class="form-control" name="spq_box" id="spq_box" placeholder="SPQ Box">
                    </div>
                    <div class="form-group mb-3">
                        <label for="spq_inner">SPQ Inner</label>
                        <input type="number" class="form-control" name="spq_inner" id="spq_inner" placeholder="SPQ Inner">
                    </div>
                    <div class="form-group mb-3">
                        <label for="standardissue">Standard Issue</label>
                        <input type="number" class="form-control" name="standardissue" id="standardissue" placeholder="Standard Issue">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="inputMaterialSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
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