let alert = $('.alert-message');

module.exports = {
    //Initializing the variable
    init: function () {
        let self = this;
        self.clearAlert();
    },

    /**
     * Clear the alert
     */
    clearAlert: function () {
        alert.html("");
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
        //Add alert
        alert.append(message);
    },

    /**
     * Generate the alert at the top of the page
     * @param type
     * @param message
     * @returns {string}
     */
    generateAlert: function (type, message) {
        return "<div class=\"alert alert-" + type + "\" role=\"alert\">" +
            message +
            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" +
            "    <span aria-hidden=\"true\">&times;</span>" +
            "</button>" +
            "</div>";
    }
};