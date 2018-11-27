let general = require('./general');

$(".deleteBan").on("click", function () {
    let id = $(this).data('id');
    let href = $(this).data('href');

    let btn = $(this);
    if (confirm("Are you sure you want to delete this ban (" + id + ")?")) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            data: {id: id},
            dataType: "JSON",
            url: href,
            success: function (data) {
                btn.removeClass("btn-success");
                btn.removeClass("btn-danger");

                $('.urlBans[data-ip="' + data.value.ip + '"]')
                    .html("<a href='" + data.value.url + "' class='btn btn-danger w-100'><i class='fas fa-user-slash'></i></a>");

                $(".bansTable").find("tr[data-id='" + id + "']").html("");

                general.setAlert(general.generateAlert("success", data.message));
            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});

$(".deleteUrl").on("click", function () {
    let id = $(this).data('id');
    let href = $(this).data('href');

    let btn = $(this);
    if (confirm("Are you sure you want to delete this url (" + id + ")?")) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            data: {id: id},
            dataType: "JSON",
            url: href,
            success: function (data) {
                btn.removeClass("btn-success");
                btn.removeClass("btn-danger");

                $(".urlTable").find("tr[data-id='" + id + "']").html("");
                general.setAlert(general.generateAlert("success", data.message));

                if($('.urlTable > tbody > tr').length === 0) {
                    $('.urlTable tbody tr').append("<td colspan='100'>There's no url</td>")
                }

                $('.urlListTable').find("tr[data-id='" + id + "']").html("");

                if($('.urlListTable > tbody > tr').length === 0) {
                    $('.urlListTable tbody tr').append("<td colspan='100'>There's no url</td>")
                }
            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});

$(".submitForm").on("click", function () {
    let form = $("form");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let formData = new FormData(form[0]);
    formData.append("_method", form.attr("method"));
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
            general.setAlert(general.generateAlert("success", data.message));
        }, error: function(data){
            general.setAlert(general.generateAlert("danger", JSON.parse(data.responseText).message));
        }
    });
});

$(".deleteForbidden").on("click", function(){
    let id = $(this).data('id');
    let href = $(this).data('href');
    let keyword = $(this).data('keyword');

    let btn = $(this);
    if (confirm("Are you sure you want to delete the keyword [" + keyword +"]?")) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            data: {id: id},
            dataType: "JSON",
            url: href,
            success: function (data) {
                btn.removeClass("btn-success");
                btn.removeClass("btn-danger");

                $(".forbiddenTable").find("tr[data-id='" + id + "']").html("");
                general.setAlert(general.generateAlert("success", data.message));
            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});
