let alert = $('.alert');
alert.html("");

$("#generate").on('click', function () {
    alert.html("");
    let href = $('#url');
    $.ajax({
        type: "POST",
        data: {'url': href.val()},
        url: "/api",
        success: function (data) {
            if (data.error === false) {
                let url = $('#short');
                url.html("<a href='" + data.href + "' target='_blank'>" + data.href + "</a>");
                href.val("");
                $('#popup').modal('show');
                generateTable();
            } else {
                alert.append(generateAlert('danger', data.message));
            }
        },
        error: function () {
            alert.append(generateAlert('danger', "Something went wrong... Please try again."));
        }
    });
});

$(document).ready(function () {
    generateTable();

    setInterval(function () {
        generateTable();
    }, 30000)
});


function generateAlert(type, message) {
    return "<div class=\"alert alert-" + type + "\" role=\"alert\">\n" +
        message +
        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
        "    <span aria-hidden=\"true\">&times;</span>\n" +
        "</button>" +
        "</div>"
}

function generateTable() {
    let table = "";
    $.ajax({
        type: "GET",
        url: "/api",
        dataType: "JSON",
        success: function (data) {
            if (!Array.isArray(data.data) || data.data.length === 0) {
                table += "<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";
            } else {
                for (let i = 0; i < data.data.length; i++) {
                    let website = $('meta[name="website"]').attr('content');
                    let link = website + "/" + data.data[i].shortUrl;

                    table += "<tr>" +
                        "<td>" + data.data[i].id + "</td>" +
                        "<td><a href='" + data.data[i].url + "' target='_blank'>" + data.data[i].url + "</a></td>" +
                        "<td><a href='" + link + "' target='_blank'>" + data.data[i].shortUrl + "</a></td>" +
                        "<td class='text-center'>" + data.data[i].click + "</td>" +
                        "</tr>";
                }
            }

            $('#table').html(table);
        }
    });
}
