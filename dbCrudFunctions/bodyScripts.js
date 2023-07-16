$('#changePassForm').submit(function (e) {
    e.preventDefault(); // Prevent form submission

    // Send form data using Ajax
    $.ajax({
        type: 'POST',
        url: 'dbCrudFunctions/changePass.php',
        data: $(this).serialize(),
        success: function (response) {
            if (response == 'success') {
                $('#changePassForm')[0].reset();
                $('#changePassModal').modal('hide');
            } else {
                alert("Old Password Doesn't Match");
            }
        }
    });
});