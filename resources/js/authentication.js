let general = require('./general');

$('#validate').on('click', function () {
    general.clearAlert();
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
                general.setAlert(general.generateAlert("danger", value['username']));
            }

            if (value['password'] !== undefined) {
                password.addClass('is-invalid');
                general.setAlert(general.generateAlert("danger", value['password']));
            }


        });
    }
});