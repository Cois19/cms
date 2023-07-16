<div class="modal fade modal-lg" id="changePassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="changePassForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Change Password</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="upasswordOld">Old Password</label>
                        <input type="password" class="form-control" name="upasswordOld" id="upasswordOld" placeholder="Old Password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="upasswordNew">New Password</label>
                        <input type="password" class="form-control" name="upasswordNew" id="upasswordNew" placeholder="New Password">
                        <input type="checkbox" onclick="showPassChange()" class="me-1">Show Password
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="passSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showPassChange() {
        var x = document.getElementById("upasswordOld");
        var y = document.getElementById("upasswordNew");
        if (x.type === "password") {
            x.type = "text";
            y.type = "text";
        } else {
            x.type = "password";
            y.type = "password";
        }
    }

    $(document).ready(function () {
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('[autofocus]').focus();
        });

        $('#usercost').select2({
            dropdownParent: $('#ucostParent'),
            width: '100%'
        });

        $('#usertype').select2({
            dropdownParent: $('#utypeParent'),
            width: '100%'
        });
    });
</script>