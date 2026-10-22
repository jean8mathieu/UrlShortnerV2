//Important the general js
import $ from 'jquery';
import { Modal, Tab } from 'bootstrap';
import general from './general';

//Delete a ban
$(document).on("click", ".deleteBan", function () {
    let btn = $(this);
    let id = btn.data('id');
    let ip = btn.data('ip');
    let href = btn.data('href');

    if (confirm("Are you sure you want to delete the ban for " + ip + "?")) {
        $.ajax({
            type: "DELETE",
            data: {id: id},
            dataType: "JSON",
            url: href,
            success: function (data) {
                //Every url row from that IP can be banned again
                $('.urlBans[data-ip="' + data.value.ip + '"]').html(
                    $('<a class="btn btn-sm btn-outline-danger"><i class="bi bi-person-slash"></i></a>')
                        .attr('href', data.value.url)
                        .attr('title', 'Ban ' + data.value.ip)
                        .attr('aria-label', 'Ban ' + data.value.ip)
                );

                removeRows($(".bansTable"), "tr[data-ban-id='" + id + "']", "There's currently nobody banned");
                updateCount('.bansCount', -1);

                general.setAlert(general.generateAlert("success", data.message));
            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", general.errorMessage(data)));
            }
        });
    }
});

//Delete a url (dashboard tables and search results)
$(document).on("click", ".deleteUrl", function () {
    let btn = $(this);
    let id = btn.data('id');
    let href = btn.data('href');

    if (confirm("Are you sure you want to delete this url (" + id + ")?")) {
        $.ajax({
            type: "DELETE",
            data: {id: id},
            dataType: "JSON",
            url: href,
            success: function (data) {
                $(".urlTable, .urlListTable").each(function () {
                    removeRows($(this), "tr[data-url-id='" + id + "']", "There's no url");
                });
                general.setAlert(general.generateAlert("success", data.message));
            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", general.errorMessage(data)));
            }
        });
    }
});

//Submit a form (Bans or Forbidden)
$(".ajax-form").on("submit", function (e) {
    e.preventDefault();
    general.clearAlert();

    let form = $(this);
    let button = form.find(".submitForm");
    general.disableButton(button);

    let formData = new FormData(form[0]);
    formData.append("_method", form.attr("method"));
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            general.setAlert(general.generateAlert(data.error ? "danger" : "success", data.message));

            //Ready for the next one when creating
            if (!data.error && form.attr("method") === "POST") {
                form[0].reset();
            }
        }, error: function (data) {
            general.setAlert(general.generateAlert("danger", general.errorMessage(data)));
        }
    });
});

//Forbidden
$(document).on("click", ".deleteForbidden", function () {
    let btn = $(this);
    let id = btn.data('id');
    let href = btn.data('href');
    let keyword = btn.data('keyword');

    if (confirm("Are you sure you want to delete the keyword [" + keyword + "]?")) {
        $.ajax({
            type: "DELETE",
            data: {id: id},
            dataType: "JSON",
            url: href,
            success: function (data) {
                removeRows($(".forbiddenTable"), "tr[data-keyword-id='" + id + "']", "There's currently no keyword");
                updateCount('.forbiddenCount', -1);
                general.setAlert(general.generateAlert("success", data.message));
            }, error: function (data) {
                general.setAlert(general.generateAlert("danger", general.errorMessage(data)));
            }
        });
    }
});

//Admin Search
$('#searchForm').on('submit', function (e) {
    e.preventDefault();
    general.clearAlert();

    let search = $('#search');
    general.disableButton($('#searchBtn'));

    $.ajax({
        type: "POST",
        url: "/admin/api/search",
        data: {search: search.val()},
        dataType: "JSON",
        success: function (data) {
            $('#searchResultTable').html(generateTable(data.data));
            Modal.getOrCreateInstance('#searchResultModal').show();
            //Empty the value in the search input box
            search.val("");
        },
        // If the request error
        error: function (data) {
            general.setAlert(general.generateAlert('danger', general.errorMessage(data)));
        }
    });
});

//Remember the open tab so pagination, reloads and "Back to dashboard" links land on it
$('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    history.replaceState(null, "", $(e.target).data('bs-target'));
});

if (location.hash) {
    let tab = $('button[data-bs-toggle="tab"][data-bs-target="' + location.hash + '"]');
    if (tab.length) {
        Tab.getOrCreateInstance(tab[0]).show();
    }
}

/**
 * Remove rows from a table and show the empty message when nothing is left
 */
function removeRows(table, selector, emptyMessage) {
    table.find(selector).remove();

    let body = table.find("tbody");
    if (body.length && body.children("tr").length === 0) {
        body.append(
            $("<tr class='empty-row'>").append(
                $("<td colspan='100' class='text-center text-body-secondary py-4'>").text(emptyMessage)
            )
        );
    }
}

function updateCount(selector, delta) {
    $(selector).text(Math.max(0, parseInt($(selector).first().text(), 10) + delta));
}

/**
 * Generate the search result table
 *
 * @param data
 * @returns {jQuery}
 */
function generateTable(data) {
    if (!Array.isArray(data) || data.length === 0) {
        return $("<p class='text-center text-body-secondary p-4 mb-0'>").text("There's currently no data to display...");
    }

    let body = $("<tbody>");

    for (let i = 0; i < data.length; i++) {
        let url = data[i].url;
        let link = general.isSafeLink(url)
            ? $("<a target='_blank' rel='noopener noreferrer' class='d-block text-truncate'>").attr('href', url)
            : $("<span class='d-block text-truncate text-danger'>");
        link.text(url).attr('title', url);

        body.append(
            $("<tr>").attr('data-url-id', data[i].id).append(
                $("<td class='ps-3 text-body-secondary'>").text(data[i].id),
                $("<td>").append($("<div class='url-cell'>").append(link)),
                $("<td class='text-end'>").text(data[i].click),
                $("<td class='font-monospace small'>").text(data[i].ip),
                $("<td class='text-end pe-3'>").append(
                    $("<button type='button' class='btn btn-sm btn-outline-secondary deleteUrl' title='Delete url'>")
                        .attr('data-id', data[i].id)
                        .attr('data-href', data[i].deleteUrl)
                        .append("<i class='bi bi-trash3'></i>")
                )
            )
        );
    }

    return $("<div class='table-responsive'>").append(
        $("<table class='table table-hover mb-0 urlListTable'>").append(
            "<thead><tr><th class='ps-3'>ID</th><th>Url</th><th class='text-end'>Click</th><th>IP</th><th></th></tr></thead>",
            body
        )
    );
}
