<div class="modal fade modal-lg" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="addUserForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><strong>Add User</strong></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="userid">User ID</label>
                        <input type="text" class="form-control" name="userid" id="userid" placeholder="User ID"
                            autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="upassword">Password</label>
                        <input type="password" class="form-control" name="upassword" id="upassword"
                            placeholder="Password" autocomplete="off">
                        <input type="checkbox" onclick="showPass()" class="me-1">Show Password
                    </div>
                    <div class="form-group mb-3">
                        <label for="username">Name</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Name" autocomplete="off">
                    </div>
                    <div class="form-group mb-3">
                        <label for="useremail">Email</label>
                        <input type="email" class="form-control" name="useremail" id="useremail" placeholder="Email">
                    </div>
                    <div class="form-group mb-3" id="ucostParent">
                        <label for="usercost">Cost Center</label>
                        <select type="text" class="form-control" name="usercost" id="usercost"
                            placeholder="Cost Center">
                            <option value="PG18">Pegatron</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="usectionParent">
                        <label for="usection">Section</label>
                        <?php
                        $query = 'SELECT * FROM tsection';
                        $result = mysqli_query($conn, $query);
                        ?>
                        <select type="text" class="form-control" name="usection" id="usection"
                            placeholder="Section">
                            <option value="" disabled selected>-- Select Section --</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?php echo $row['scode'] ?>"><?php echo $row['sname'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="utypeParent">
                        <label for="usertype">User Type</label>
                        <select type="text" class="form-control" name="usertype" id="usertype" placeholder="User Type">
                            <option value="1" <?php echo ($utype != 1) ? 'disabled' : ''; ?>>ADMIN</option>
                            <option value="2">PIC</option>
                            <option value="3" selected>USER</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="remarks">Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Remarks">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="userSubmitBtn" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showPass() {
        var x = document.getElementById("upassword");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
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

        $('#usection').select2({
            dropdownParent: $('#usectionParent'),
            width: '100%'
        });

        $('#usertype').select2({
            dropdownParent: $('#utypeParent'),
            width: '100%'
        });
    });
</script>