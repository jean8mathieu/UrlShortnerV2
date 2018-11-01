//Initializing the variable
let alert = $('.alert');
clearAlert();

/**
 * Generate the url through the API
 */
$("#generate").on('click', function () {
    disableButton($("#generate"));
    alert.html("");
    let href = $('#url');
    $.ajax({
        type: "POST",
        data: {'url': href.val(), 'private': true},
        url: "/api",
        success: function (data) {
            if (data.error === false) {
                let url = $('#short');
                url.html("<a href='" + data.href + "' target='_blank'>" + data.href + "</a>");
                href.val("");
                $('#generatedLinkModal').modal('show');
                getTableData();
            } else {
                clearAlert();
                setAlert(generateAlert('danger', data.message));
            }
        },
        error: function () {
            setAlert(generateAlert('danger', "Something went wrong... Please try again."));
        }
    });
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
 * Generate the alert at the top of the page
 * @param type
 * @param message
 * @returns {string}
 */
function generateAlert(type, message) {
    return "<div class=\"alert alert-" + type + "\" role=\"alert\">" +
        message +
        "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" +
        "    <span aria-hidden=\"true\">&times;</span>" +
        "</button>" +
        "</div>"
}

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
function generateTable(data, type = "table-hover"){
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

            table += "<tr>" +
                "<td>" + data[i].id + "</td>" +
                "<td><a href='" + data[i].url + "' target='_blank'>" + data[i].url + "</a></td>" +
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
    disableButton($('#searchBtn'));

    $.ajax({
        type: "POST",
        url: "/api/search",
        data:{search: search.val(), _token: $('meta[name="csrf-token"]').attr('content')},
        dataType: "JSON",
        success: function (data) {

            let table = generateTable(data.data);
            console.info(JSON.stringify(table));
            $('#searchResultTable').html(table);
            $('#searchResultModal').modal('show');
            //Empty the value in the search input box
            search.val("");
        },
        // If the request error
        error: function (data) {
            clearAlert();
            if (data.responseText === undefined) {
                setAlert(generateAlert('danger', "Something went wrong. Please try again :("));
            } else {
                setAlert(generateAlert('danger', JSON.parse(data.responseText).message));
            }
        },
        //If the request fail
        fail: function (data) {
            setAlert(generateAlert('warning', data.message));
        }
    });
});

/**
 * Clear the alert
 */
function clearAlert(){
    alert.html("");
}

/**
 * Disable the button clicked
 * @param btn
 */
function disableButton(btn){
    btn.attr('disabled', true);
    setTimeout(function(){
        btn.attr('disabled', false);
    }, 1000);
}

/**
 * Set alert on the page
 * @param alert
 */
function setAlert(message) {
    //Add alert
    alert.append(message);

    //Clear the alert after 5 seconds
    setTimeout(function(){
        //clearAlert();
    }, 5000)
}
