//Initializing the variable
let alert = $('.alert');
clearAlert();


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
 * @param message
 */
function setAlert(message) {
    //Add alert
    alert.append(message);

    //Clear the alert after 5 seconds
    setTimeout(function(){
        //clearAlert();
    }, 5000)
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


$('#validate').on('click', function(){
    clearAlert();
    $('#loginForm').find("*").removeClass('is-invalid');
    let username = $('#username');
    let password = $('#password');

    let error = [];

    if(username.val().length === 0) {
        error.push({'username': "Username can't be empty"});
    }

    if(password.val().length === 0) {
        error.push({'password': "Password can't be empty"});
    }



    if (error.length > 0) {
        error.forEach(function(value){
            if(value['username'] !== undefined) {
                username.addClass('is-invalid');
                setAlert(generateAlert("danger", value['username']));
            }

            if(value['password'] !== undefined) {
                password.addClass('is-invalid');
                setAlert(generateAlert("danger", value['password']));
            }


        });
    }
});
