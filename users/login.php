<?php
session_start();
include '../database/connect.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == "yes") {
    header("location:../do_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/bootstrap/css/select2.css" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/style.css">
    <script src="../assets/bootstrap/jquery/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/select2.js"></script>
    <script src="../assets/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/bootstrap-icons-1.10.3/bootstrap-icons.css">
    <script src="../dbCrudFunctions/crudFunctions.js"></script>
    <style>
        /* source-sans-3-regular - latin */
        @font-face {
            font-display: swap;
            font-family: 'Source Sans 3';
            font-style: normal;
            font-weight: 400;
            src: url('assets/bootstrap/fonts/source-sans-3-v9-latin-regular.woff2') format('woff2');
        }
    </style>
</head>

<body class="text-center">
    <!-- <main class="form-signin m-auto col-sm-12 col-lg-6"> -->
    <img src="../assets/pic/logo ptsn.jpg" alt="logo ptsn" style="width: 250px">
    <div class="card m-auto col-10 col-lg-4">
        <div class="card-body">
            <form id="login-form" method="post">

                <h1 class="h3 mb-3">Welcome to CMS</h1>

                <div class="form-floating mb-2 mx-auto" style="width: 75%;">
                    <input type="text" class="form-control" id="userid" placeholder="123456">
                    <label for="floatingInput">User ID</label>
                </div>
                <div class="form-floating mb-3 mx-auto" style="width: 75%;">
                    <input type="password" class="form-control" id="password" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>

                <button class="btn btn-primary" type="submit">Sign in</button>
                <div id="error-message"></div>
            </form>
        </div>
    </div>
    <!-- </main> -->
</body>

<script>
    $(document).ready(function () {
        $("#login-form").submit(function (event) {
            event.preventDefault();
            var userid = $("#userid").val();
            var password = $("#password").val();
            $.ajax({
                url: "log.php",
                method: "POST",
                data: { userid: userid, password: password },
                success: function (data) {
                    if (data === "success") {
                        window.location.href = "../do_list.php";
                    } else {
                        $("#error-message").html("Invalid username or password.");
                    }
                }
            });
        });
    });
</script>

</html>


<?php
mysqli_close($conn);
?>