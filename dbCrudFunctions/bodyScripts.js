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
            } else if (response == 'timeout') {
                window.location.href = '/vsite/cms/users/login.php';
            } else {
                alert("Old Password Doesn't Match");
            }
        }
    });
});

$('#doSubmitBtn').click(function (e) {
    $('#spinnerModal').modal('show');
    setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: '/vsite/cms/dbCrudFunctions/insert.php',
            data: $('#newDoForm').serialize() + '&mode=dorder',
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {
                    $('#spinnerModal').modal('hide');
                    $('#newDoForm')[0].reset();
                    $('#createModal').modal('hide');
                    var url = '/vsite/cms/pages/delivery_order/do.php?id=' + response.que +
                        '&pid=' + response.tpid +
                        '&pno=' + response.tpno;

                    window.location.href = url;
                } else if (response.status == 'empty') {
                    $('#spinnerModal').modal('hide');
                    alert('DO Cannot be Empty!');
                    $('#do').focus();
                } else if (response.status == 'fail') {
                    $('#spinnerModal').modal('hide');
                    $('#newDoForm')[0].reset();
                    $('#createModal').modal('hide');
                    var url = '/vsite/cms/pages/delivery_order/do.php?id=' + response.que +
                        '&pid=' + response.tpid +
                        '&pno=' + response.tpno;

                    window.location.href = url;
                } else if (response.status == 'timeout') {
                    $('#spinnerModal').modal('hide');
                    window.location.href = '/vsite/cms/users/login.php';
                } else if (response.status == 'emptyshipping') {
                    $('#spinnerModal').modal('hide');
                    alert('Shipping Field Cannot Be Empty!');
                } else {
                    $('#spinnerModal').modal('hide');
                    alert('Failed');
                }
            }
        });
    }, 500);
});

$('#addPeriodForm').submit(function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: '/vsite/cms/dbCrudFunctions/insert_test.php',
        data: $(this).serialize() + '&mode=period',
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                // $('#addPeriodForm').reset();
                // $('#addPeriodModal').modal('hide');
                var url = '/vsite/cms/pages/inventory/period.php?id=' + response.que;

                window.location.href = url;
                // alert('good');
            } else if (response.status == 'empty') {
                alert('Period Fields Cannot be Empty!');
                $('#period').focus();
            } else if (response.status == 'length') {
                alert('Period Name Should Have 5 Characters!');
            } else if (response.status == 'fail') {
                // $('#addPeriodForm').reset();
                // $('#createModal').modal('hide');
                // var url = '/vsite/cms/pages/inventory/period.php?id=' + response.que;

                // window.location.href = url;
                alert('Active Period in Session!');
            } else if (response.status == 'timeout') {
                window.location.href = '/vsite/cms/users/login.php';
            } else {
                alert('Failed');
            }


        }
    });
});