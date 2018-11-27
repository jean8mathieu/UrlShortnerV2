//Initializing the variable
let alert = $('.alert');
clearAlert();


/**
 * Clear the alert
 */
function clearAlert() {
    alert.html("");
}

/**
 * Disable the button clicked
 * @param btn
 */
function disableButton(btn) {
    btn.attr('disabled', true);
    setTimeout(function () {
        btn.attr('disabled', false);
    }, 1000);
}

/**
 * Set alert on the page
 * @param message
 */
function setAlert(message) {
    //Add alert
    alert.append(message);

    //Clear the alert after 5 seconds
    setTimeout(function () {
        clearAlert();
    }, 3000)
}

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


$('#validate').on('click', function () {
    clearAlert();
    $('#loginForm').find("*").removeClass('is-invalid');
    let username = $('#username');
    let password = $('#password');

    let error = [];

    if (username.val().length === 0) {
        error.push({'username': "Username can't be empty"});
    }

    if (password.val().length === 0) {
        error.push({'password': "Password can't be empty"});
    }


    if (error.length > 0) {
        error.forEach(function (value) {
            if (value['username'] !== undefined) {
                username.addClass('is-invalid');
                setAlert(generateAlert("danger", value['username']));
            }

            if (value['password'] !== undefined) {
                password.addClass('is-invalid');
                setAlert(generateAlert("danger", value['password']));
            }


        });
    }
});

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

                setAlert(generateAlert("success", data.message));
            }, error: function (data) {
                setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
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
                setAlert(generateAlert("success", data.message));
            }, error: function (data) {
                setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
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
            setAlert(generateAlert("success", data.message));
        }, error: function(data){
            setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
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
                setAlert(generateAlert("success", data.message));
            }, error: function (data) {
                setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});
