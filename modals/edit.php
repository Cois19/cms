<div class="modal fade modal-xl" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id='myformedit' class="form-sample" name='myform' action="dbCrudFunctions/update.php" method="POST"
        enctype='multipart/form-data'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Edit</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editForm"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#myformedit').on('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting normally

            var formData = new FormData(this); // Get the form data
            formData.append('mode', 'update');

            $.ajax({
                url: 'dbCrudFunctions/update.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response === 'success with file') {
                        // Update was successful
                        $('#editModal').modal('hide');
                        alert('Update successful!');
                        setTimeout(function () {
                            checkUploadStatus();
                        }, 1000);
                    } else if (response === 'success without file') {
                        // Update was successful
                        $('#editModal').modal('hide');
                        alert('Update successful!');
                        setTimeout(function () {
                            document.location.reload(true);
                        }, 1000);
                    } else {
                        // Update failed
                        alert('Update failed!');
                    }
                },
                error: function () {
                    // The request failed
                    alert('An error occurred while updating the data.');
                }
            });
        });
    });

    $('#e_model').select2({
        dropdownParent: $('#e_modelParent'),
        width: '100%'
    });
</script>