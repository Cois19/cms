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

$('#newDoForm').submit(function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: 'dbCrudFunctions/insert.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                $('#newDoForm')[0].reset();
                $('#createModal').modal('hide');
                var url = 'do.php?id=' + response.que;

                window.location.href = url;
            } else if (response.status == 'empty') {
                alert('DO Cannot be Empty!');
            } else if (response.status == 'fail') {
                $('#newDoForm')[0].reset();
                $('#createModal').modal('hide');
                var url = 'do.php?id=' + response.que;

                window.location.href = url;
            } else {
                alert('Failed');
            }


        }
    });
});