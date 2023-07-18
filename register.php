<?php
include 'database/connect.php';
include 'users/session.php';

if ($utype == 3) {
    header("Location: do_list.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <?php include 'scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php' ?>
    <div class="mb-3"></div>
    <div class="container">

        <!-- Modals -->
        <?php include 'modals/addUserM.php'; ?>
        <?php include 'modals/create.php'; ?>
        <?php include 'modals/edit.php'; ?>
        <?php include 'modals/uploading.php'; ?>
        <?php include 'modals/isn.php'; ?>
        <?php include 'modals/resetM.php'; ?>
        <?php include 'modals/deleteDoM.php'; ?>
        <?php include 'modals/grM.php'; ?>
        <?php include 'modals/changePassM.php'; ?>

        <h2>User Management</h2>
        <hr>
        <button href="#" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-primary mb-3">Add
            User</button>
        <div class="table-responsive">
            <table id="userTable" class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>USER ID</th>
                        <th>NAME</th>
                        <th>COST CENTER</th>
                        <th>EMAIL</th>
                        <th>USER TYPE</th>
                        <th>REMARKS</th>
                        <th colspan="1" rowspan="1"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <?php include 'footer.php' ?>
    </div>

    <script>
        $('#addUserForm').submit(function (e) {
            e.preventDefault(); // Prevent form submission

            // check required fields
            var userid = $('#userid').val();
            var upassword = $('#upassword').val();
            var username = $('#username').val();

            if (userid == null || userid == '' || upassword == null || upassword == '' || username == null || username == '') {
                alert('User ID, Password and Username Cannot be Empty!');
                return;
            }

            // Send form data using Ajax
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/insertUser.php',
                data: $(this).serialize(),
                success: function (response) {
                    if (response == 'success') {
                        $('#addUserForm')[0].reset();
                        $('#addUserModal').modal('hide');
                        loadUserTable();
                    } else if (response == 'duplicate') {
                        alert('User Already Exists!');
                    } else if (response == 'timeout') {
                        window.location.href = '/vsite/cms/users/login.php';
                    } else {
                        alert('Add User Failed');
                    }
                }
            });
        });

        function loadUserTable() {
            $.ajax({
                type: 'POST',
                url: 'dbCrudFunctions/table.php',
                data: { mode: 'user' },
                dataType: 'json',
                success: function (response) {
                    if (response.data) {
                        table.clear().rows.add(response.data).draw();
                    } else {
                        table.clear().draw();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        var table = $('#userTable').DataTable({
            responsive: true,
            // dom: '<"d-flex flex-wrap justify-content-between"B<"d-flex flex-wrap justify-content-between"<"me-3"l>f>>rt<"d-flex flex-wrap justify-content-between"ip>',
            // buttons: [
            //     {
            //         extend: 'collection',
            //         text: 'Export',
            //         buttons: [
            //             'copy',
            //             'excel',
            //             'csv',
            //             'pdf',
            //             'print'
            //         ]
            //     }
            // ],
            // order: [[0, 'desc']],
            // columnDefs: [
            //     {
            //         target: 1,
            //         visible: false,
            //         searchable: false
            //     },
            // ],
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        var startNumber = 1;
                        var currentNumber = meta.row + startNumber;
                        return currentNumber;
                    }
                },
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return 'null';
                    }
                }
            ]
        });

        $(document).ready(function () {
            loadUserTable();
        });

        <?php include 'dbCrudFunctions/bodyScripts.js' ?>
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>