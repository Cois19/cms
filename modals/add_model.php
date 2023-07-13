<div class="modal fade modal-xl" id="modelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><strong>Add Model</strong></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="model">Model Name</label>
                    <input type="text" class="form-control" id="modelName" placeholder="Model Name, E.g: UTE7057LGU">
                </div>
                <div class="form-group mb-3" id="costParent">
                    <label for="costcenter">Cost Center</label>
                    <select class="form-select" name="costcenter" id="costcenter">
                        <option value="PG18">
                            PEGATRON
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="remarks">Remarks (Optional)</label>
                    <input type="text" class="form-control" id="remarks" placeholder="Remarks">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submitBtn" type="button" class="btn btn-primary" onclick="addModel()">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#costcenter').select2({
        dropdownParent: $('#costParent'),
        width: '100%'
    });
</script>