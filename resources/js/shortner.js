import $ from 'jquery';
import general from './general';

let form = $('#shortenForm');
let input = $('#url');
let button = $('#generate');
let result = $('#result');
let shortLink = $('#shortLink');
let copyButton = $('#copyLink');

/**
 * Generate the url through the API
 */
form.on('submit', function (e) {
    e.preventDefault();
    general.clearAlert();

    let url = input.val().trim();

    //If there's no link display an error
    if (url.length === 0) {
        general.setAlert(general.generateAlert('warning', 'You must enter a url before we can generate it for you...'));
        input.trigger('focus');
        return;
    }

    //Most people paste "example.com" without the scheme
    if (!/^[a-z][a-z0-9+.-]*:\/\//i.test(url)) {
        url = 'https://' + url;
    }

    setLoading(true);

    $.ajax({
        type: "POST",
        data: {'url': url},
        url: "/api",
        dataType: "JSON",
        success: function (data) {
            if (data.error === false) {
                shortLink.attr('href', data.href).text(data.href);
                resetCopyButton();
                result.removeClass('d-none');
                input.val("");
            } else {
                general.setAlert(general.generateAlert('danger', data.message));
            }
        },
        error: function (data) {
            general.setAlert(general.generateAlert('danger', general.errorMessage(data)));
        },
        complete: function () {
            setLoading(false);
        }
    });
});

/**
 * Copy the generated link to the clipboard
 */
copyButton.on('click', function () {
    let link = shortLink.attr('href');

    //The clipboard API needs a secure context and can be denied, so fall back to the old way
    let copied = navigator.clipboard && window.isSecureContext
        ? navigator.clipboard.writeText(link).catch(function () {
            return legacyCopy(link);
        })
        : legacyCopy(link);

    copied.then(function () {
        copyButton.removeClass('btn-outline-primary').addClass('btn-success');
        copyButton.find('i').attr('class', 'bi bi-check-lg');
        copyButton.find('span').text('Copied!');
        setTimeout(resetCopyButton, 2000);
    }).catch(function () {
        general.setAlert(general.generateAlert('warning', "We couldn't copy the link. Please copy it manually."));
    });
});

function resetCopyButton() {
    copyButton.removeClass('btn-success').addClass('btn-outline-primary');
    copyButton.find('i').attr('class', 'bi bi-clipboard');
    copyButton.find('span').text('Copy');
}

/**
 * Copy fallback for http:// (navigator.clipboard needs a secure context)
 */
function legacyCopy(text) {
    let field = $('<textarea class="visually-hidden" readonly>').val(text).appendTo('body');
    field[0].select();
    let ok = document.execCommand('copy');
    field.remove();
    return ok ? Promise.resolve() : Promise.reject();
}

function setLoading(loading) {
    button.prop('disabled', loading);
    button.find('.spinner-border').toggleClass('d-none', !loading);
}
