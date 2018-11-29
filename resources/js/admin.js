//Important the general js
let general = require('./general');

//Delete a ban
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

//Delete a url
$("#searchResultModal").on("click", ".deleteUrl", function () {
    deleteUrl(this);
});

//Delete a url
$(".deleteUrl").on("click", function () {
    deleteUrl(this);
});


function deleteUrl(btn) {
    let id = $(btn).data('id');
    let href = $(btn).data('href');

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
                $(btn).removeClass("btn-success");
                $(btn).removeClass("btn-danger");

                $(".urlTable").find("tr[data-id='" + id + "']").html("");
                general.setAlert(general.generateAlert("success", data.message));

                if ($('.urlTable > tbody > tr').html().length === 0) {
                    $('.urlTable tbody tr').append("<td colspan='100'>There's no url</td>")
                }

                $('.urlListTable').find("tr[data-id='" + id + "']").remove();

                if ($(".urlListTable > tbody > tr").length === 0) {
                    $(".urlListTable > tbody").append("<tr><td colspan='100'>There's no url</td></tr>");
                }

            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
}

//Submit a form (Bans or Forbidden)
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
        success: function (data) {
            general.setAlert(general.generateAlert("success", data.message));
        }, error: function (data) {
            general.setAlert(general.generateAlert("danger", JSON.parse(data.responseText).message));
        }
    });
});

//Forbidden
$(".deleteForbidden").on("click", function () {
    let id = $(this).data('id');
    let href = $(this).data('href');
    let keyword = $(this).data('keyword');

    let btn = $(this);
    if (confirm("Are you sure you want to delete the keyword [" + keyword + "]?")) {

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

//Admin Search
$('#searchBtn').on('click', function () {
    let search = $('#search');
    general.disableButton($('#searchBtn'));

    $.ajax({
        type: "POST",
        url: "/admin/api/search",
        data: {search: search.val(), _token: $('meta[name="csrf-token"]').attr('content')},
        dataType: "JSON",
        success: function (data) {

            let table = generateTable(data.data, "table-hover", true);
            $('#searchResultTable').html(table);
            $('#searchResultModal').modal('show');
            //Empty the value in the search input box
            search.val("");
        },
        // If the request error
        error: function (data) {
            general.clearAlert();
            if (data.responseText === undefined) {
                general.setAlert(general.generateAlert('danger', "Something went wrong. Please try again :("));
            } else {
                general.setAlert(general.generateAlert('danger', JSON.parse(data.responseText).message));
            }
        },
        //If the request fail
        fail: function (data) {
            general.setAlert(general.generateAlert('warning', JSON.parse(data.responseText).message));
        }
    });
});

/**
 * Generate the table
 *
 * @param data
 * @returns {string}
 */
function generateTable(data, type = "table-hover", modal = false) {
    let table = "";
    if (!Array.isArray(data) || data.length === 0) {
        table += "<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";
    } else {

        table = "<table class=\"table " + type + "\ urlListTable\">" +
            "            <thead>" +
            "            <tr>" +
            "                <th>ID</th>" +
            "                <th>Url</th>" +
            "                <th>Click</th>" +
            "                <th>IP</th>" +
            "                <th></th>" +
            "            </tr>" +
            "            </thead>";

        for (let i = 0; i < data.length; i++) {
            let website = $('meta[name="website"]').attr('content');

            let url = data[i].url;
            if (modal === true && url.length > 50) {
                url = url.substring(0, 50) + "...";
            }

            table += "<tr data-id='" + data[i].id + "'>" +
                "<td>" + data[i].id + "</td>" +
                "<td><a href='" + url + "' target='_blank'>" + url + "</a></td>" +
                "<td>" + data[i].click + "</td>" +
                "<td>" + data[i].ip + "</td>" +
                "<td>" +
                "   <button type=\"button\" data-id='" + data[i].id + "' data-href='" + data[i].deleteUrl + "' class=\"btn btn-warning deleteUrl\">" +
                "       <i class=\"far fa-trash-alt\"></i>" +
                "   </button>" +
                "</td>" +
                "</tr>";
        }

        table += "</table>";
    }

    return table;
}