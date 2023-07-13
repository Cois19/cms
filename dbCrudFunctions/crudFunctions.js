//add new checksum data
function add() {
    var palletid = $('#palletid').val();
    var partno = $('#partno').val();
    var partname = $('#partname').val();
    var dnnumber = $('#dnnumber').val();
    var qty = $('#qty').val();
    var boxcount = $('#boxcount').val();

    if (palletid === "" || palletid === null || partno === "" || partno === null || partname === "" || partname === null ||
        qty === "" || qty === null) {
        alert("All fields must be filled.");
        return;
    }

    // Construct a new FormData object
    var formData = new FormData();
    formData.append('palletid', palletid);
    formData.append('partno', partno);
    formData.append('partname', partname);
    formData.append('dnnumber', dnnumber);
    formData.append('qty', qty);
    formData.append('boxcount', boxcount);

    // Make an AJAX call to the server to upload the data
    $.ajax({
        url: "dbCrudFunctions/insert.php",
        type: "post",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            var response = JSON.parse(data);
            if (response.result === 'Data already exists!') {
                alert(response.result);
                return;
            } else {
                // $('#model').val(null).trigger('change');
                // $('#sixtypartnumber').val('');
                // for (var i = 1; i <= count; i++) {
                //     $('#partnumber' + i).val('');
                //     $('#location' + i).val('');
                //     $('#checksum' + i).val('');
                // }
                // $('#tpversion').val('');
                // $('#approval').prop('checked', false);
                // $('#approvaldate').val('');
                // const approvalDateInput = document.getElementById('approvaldate');
                // approvalDateInput.disabled = true;
                // $('#verifiedby').val('');
                // $('#c_remarks').val('');
                // $('#burner').val('');
                // $('#att1').val('');
                // $('#att2').val('');
                $('#createModal').modal('hide');

                setTimeout(function () {
                    reloadPage();
                }, 1000);
            }
        }
    });
}

//add new model
function addModel() {
    var model = $('#modelName').val();
    var costcenter = $('#costcenter').val();
    var remarks = $('#remarks').val();

    if (model === "" || model === null || costcenter === "" || costcenter === null) {
        alert("Model and Cost Center cannot be empty!");
        return;
    }

    // Construct a new FormData object
    var formData = new FormData();
    formData.append('model', model);
    formData.append('costcenter', costcenter);
    formData.append('remarks', remarks);

    // Make an AJAX call to the server to upload the data
    $.ajax({
        url: "dbCrudFunctions/insertModel.php",
        type: "post",
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.result === 'Data already exists!') {
                alert(response.result);
                return;
            } else {
                $('#modelName').val('');
                $('#costcenter').val(null).trigger('change');
                $('#remarks').val('');
                $('#modelModal').modal('hide');

                setTimeout(function () {
                    reloadPage();
                }, 1000);
            }
        }
    });
}

function update(ecdId, detailsId) {
    $.ajax({
        url: "dbCrudFunctions/update.php",
        type: "post",
        data: { ecdId: ecdId, detailsId: detailsId, mode: 'fetch' },
        success: function (response) {
            $('#editModal').modal('show');
            $('#editForm').html(response);
        }
    });
}

function reloadPage() {
    document.location.reload(true);
}

function checkUploadStatus() {
    $.ajax({
        url: 'dbCrudFunctions/checkFileUploadStatus.php',
        type: 'GET',
        success: function (response) {
            if (response === 'complete') {
                $('#spinnerModal').modal('hide');
                reloadPage();
            } else {
                setTimeout(function () {
                    checkUploadStatus();
                }, 1000);
            }
        }
    });
}

//search checksum
function search() {
    var modelView = $('#modelView').val();
    var sixtypartnumberView = $('#sixtypartnumberView').val();
    var partnumberView = $('#partnumberView').val();

    if (modelView === "" || modelView === null || sixtypartnumberView === "" || sixtypartnumberView === null || partnumberView === "" || partnumberView === null) {
        alert("All fields must be filled.");
        $('#checksumView').text('');
        $('#checksumDetails').hide();
        $('#copyButton').hide();
        return;
    }

    $.ajax({
        url: "dbCrudFunctions/search.php",
        type: "post",
        dataType: "json", // Automatically parses the response as JSON
        data: {
            modelView: modelView,
            sixtypartnumberView: sixtypartnumberView,
            partnumberView: partnumberView
        },
        success: function (response) {
            // Handle first JSON response
            var id1 = response[0];
            if (id1 === "null") {
                alert('No record found.');
                $('#checksumView').text('');
                $('#checksumDetails').hide();
                $('#copyButton').hide();
                return;
            }

            // Handle second JSON response
            var id2 = response[1];
            if (id2 === "null") {
                $('#checksumView').text(id1.checksum);

                $('#idView1').text(id1.ecd_id);
                $('#detailsidView1').text(id1.details_id);
                if (id1.approvaldate === '0000-00-00') {
                    $('#approvaldateView1').text('');
                } else {
                    $('#approvaldateView1').text(new Date(id1.approvaldate).toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' }));
                }
                $('#approvalView1').text(id1.approval === '1' ? 'Yes' : 'No');
                $('#tpversionView1').text(id1.tpversion);
                $('#location1View1').text(id1.location);
                $('#verifiedbyView1').text(id1.verifiedby);
                $('#remarksView1').text(id1.c_remarks);
                $('#burnerView1').text(id1.burner);
                $('#inputView1').text(id1.cp);
                $('#file1View1').html('<a href="attachments/' + encodeURIComponent(id1.att1) + '">' + id1.att1 + '</a>');
                $('#file2View1').html('<a href="attachments/' + encodeURIComponent(id1.att2) + '">' + id1.att2 + '</a>');

                $('#idView2').text(id2.ecd_id);
                $('#detailsidView2').text(id2.details_id);
                $('#approvaldateView2').text('');
                $('#approvalView2').text('');
                $('#tpversionView2').text('');
                $('#location1View2').text('');
                $('#verifiedbyView2').text('');
                $('#remarksView2').text('');
                $('#burnerView2').text('');
                $('#idView2').text('');
                $('#file1View2').html('<a href="attachments/' + encodeURIComponent(id2.att1) + '">' + id2.att1 + '</a>');
                $('#file2View2').html('<a href="attachments/' + encodeURIComponent(id2.att2) + '">' + id2.att2 + '</a>');
                $('#checksumDetails').show();
                $('#copyButton').show();
                return;
            }

            // Display both records' details
            $('#checksumView').text(id1.checksum);

            $('#idView1').text(id1.ecd_id);
            $('#detailsidView1').text(id1.details_id);
            if (id1.approvaldate === '0000-00-00') {
                $('#approvaldateView1').text('');
            } else {
                $('#approvaldateView1').text(new Date(id1.approvaldate).toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' }));
            }
            $('#approvalView1').text(id1.approval === '1' ? 'Yes' : 'No');
            $('#tpversionView1').text(id1.tpversion);
            $('#location1View1').text(id1.location);
            $('#verifiedbyView1').text(id1.verifiedby);
            $('#remarksView1').text(id1.c_remarks);
            $('#burnerView1').text(id1.burner);
            $('#inputView1').text(id1.cp);
            $('#file1View1').html('<a href="attachments/' + encodeURIComponent(id1.att1) + '">' + id1.att1 + '</a>');
            $('#file2View1').html('<a href="attachments/' + encodeURIComponent(id1.att2) + '">' + id1.att2 + '</a>');

            $('#idView2').text(id2.ecd_id);
            $('#detailsidView2').text(id2.details_id);
            if (id2.approvaldate === '0000-00-00') {
                $('#approvaldateView2').text('');
            } else {
                $('#approvaldateView2').text(new Date(id2.approvaldate).toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' }));
            }
            $('#approvalView2').text(id2.approval === '1' ? 'Yes' : 'No');
            $('#tpversionView2').text(id2.tpversion);
            $('#location1View2').text(id2.location);
            $('#verifiedbyView2').text(id2.verifiedby);
            $('#remarksView2').text(id2.c_remarks);
            $('#burnerView2').text(id2.burner);
            $('#inputView2').text(id2.cp);
            $('#file1View2').html('<a href="attachments/' + encodeURIComponent(id2.att1) + '">' + id2.att1 + '</a>');
            $('#file2View2').html('<a href="attachments/' + encodeURIComponent(id2.att2) + '">' + id2.att2 + '</a>');

            $('#checksumDetails').show();
            $('#copyButton').show();
        }
    });

}
