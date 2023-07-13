<?php
include 'database/connect.php';
include 'users/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCAN</title>
    <?php include 'scripts.php' ?>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php' ?>
    <div class="container">
        <!-- Button trigger create modal -->
        <!-- <div class="mb-4">
            <button type="button" class="btn btn-primary btn-sm my-2 <?php echo $hide ?>" data-bs-toggle="modal"
                data-bs-target="#createModal" style="margin-right: 10px;">
                Create DO
            </button>
            <button type="button" class="btn btn-light btn-sm my-2 <?php echo $hide ?>" data-bs-toggle="modal"
                data-bs-target="#modelModal">
                Add Model
            </button>
        </div> -->



        <!-- Modals -->
        <?php include 'modals/create.php'; ?>
        <?php include 'modals/add_model.php'; ?>
        <?php include 'modals/edit.php'; ?>
        <?php include 'modals/uploading.php'; ?>
    </div>

    <script>
        $(document).ready(function () {
            $('#newDoForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission

                // Send form data using Ajax
                $.ajax({
                    type: 'POST',
                    url: 'dbCrudFunctions/insert.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response == 'success') {
                            $('#newDoForm')[0].reset(); // Reset the form
                            $('#createModal').modal('hide');
                        } else if (response == 'fail') {

                        }
                    }
                });
            });
        });
    </script>
</body>

<?php mysqli_close($conn); ?>

</html>