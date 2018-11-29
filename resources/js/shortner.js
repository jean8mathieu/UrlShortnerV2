let general = require('./general');

/**
 * Generate the url through the API
 */
$("#generate").on('click', function () {
    general.disableButton($("#generate"));
    general.clearAlert();
    let href = $('#url');

    //If there's no link display an error
    if(href.val().length === 0) {
        general.setAlert(general.generateAlert('warning', 'You must enter a url before we can generate it for you...'))
    } else {
        $.ajax({
            type: "POST",
            data: {'url': href.val()},
            url: "/api",
            success: function (data) {
                if (data.error === false) {
                    let url = $('#short');
                    url.html("<a href='" + data.href + "' target='_blank'>" + data.href + "</a>");
                    href.val("");
                    $('#generatedLinkModal').modal('show');
                    getTableData();
                } else {
                    general.clearAlert();
                    general.setAlert(general.generateAlert('danger', data.message));
                }
            },
            error: function (data) {
                general.setAlert(general.generateAlert('danger', JSON.parse(data.responseText).message));
            }
        });
    }
});

/**
 * Generate the table and reload it every 30 sec
 */
$(document).ready(function () {
    getTableData();

    setInterval(function () {
        getTableData();
    }, 30000)
});

/**
 * Get the data for the table
 */
function getTableData() {
    $.ajax({
        type: "GET",
        url: "/api",
        dataType: "JSON",
        success: function (data) {
            let table = generateTable(data.data);
            $('#tableResult').html(table);
        }, error: function (data) {

        }
    });
}

/**
 * Generate the table
 *
 * @param data
 * @returns {string}
 */
function generateTable(data, type = "table-hover", modal = false){
    let table = "";
    if (!Array.isArray(data) || data.length === 0) {
        table += "<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";
    } else {

        table = "<table class=\"table " + type +"\">" +
            "            <thead>" +
            "            <tr>" +
            "                <th>ID</th>" +
            "                <th>Url</th>" +
            "                <th>Short</th>" +
            "                <th>Click</th>" +
            "            </tr>" +
            "            </thead>";

        for (let i = 0; i < data.length; i++) {
            let website = $('meta[name="website"]').attr('content');
            let link = website + "/" + data[i].shortUrl;

            let url = data[i].url;
            if (modal === true && url.length > 80) {
                url = url.substring(0,80) + "...";
            }

            table += "<tr>" +
                "<td>" + data[i].id + "</td>" +
                "<td><a href='" + link + "' target='_blank'>" + url + "</a></td>" +
                "<td><a href='" + link + "' target='_blank'>" + data[i].shortUrl + "</a></td>" +
                "<td class='text-center'>" + data[i].click + "</td>" +
                "</tr>";
        }

        table += "</table>";
    }

    return table;
}


$('#searchBtn').on('click', function (){
    let search = $('#search');
    general.disableButton($('#searchBtn'));

    $.ajax({
        type: "POST",
        url: "/api/search",
        data:{search: search.val(), _token: $('meta[name="csrf-token"]').attr('content')},
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


