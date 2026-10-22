import $ from 'jquery';

const icons = {
    success: 'bi-check-circle-fill',
    danger: 'bi-exclamation-octagon-fill',
    warning: 'bi-exclamation-triangle-fill',
    info: 'bi-info-circle-fill'
};

export default {
    /**
     * Clear the alert
     */
    clearAlert: function () {
        $('.alert-message').html("");
    },

    /**
     * Disable the button clicked
     * @param btn
     */
    disableButton: function (btn) {
        btn.attr('disabled', true);
        setTimeout(function () {
            btn.attr('disabled', false);
        }, 1000);
    },

    /**
     * Set alert on the page
     * @param message
     */
    setAlert: function (message) {
        $('.alert-message').append(message);
    },

    /**
     * Generate the alert at the top of the page
     * @param type
     * @param message
     * @returns {jQuery}
     */
    generateAlert: function (type, message) {
        return $('<div class="alert alert-dismissible fade show d-flex align-items-center gap-2" role="alert">')
            .addClass('alert-' + type)
            .append($('<i class="bi flex-shrink-0">').addClass(icons[type] || icons.info))
            .append($('<div>').text(message))
            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
    },

    /**
     * Get the error message out of a failed ajax request
     * @param xhr
     * @returns {string}
     */
    errorMessage: function (xhr) {
        try {
            return JSON.parse(xhr.responseText).message || "Something went wrong. Please try again :(";
        } catch (e) {
            return "Something went wrong. Please try again :(";
        }
    },

    /**
     * Only let http(s) urls become clickable links
     * @param url
     * @returns {boolean}
     */
    isSafeLink: function (url) {
        return /^https?:\/\//i.test(url);
    }
};
