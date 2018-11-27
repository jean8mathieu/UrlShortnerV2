/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/authentication.js":
/***/ (function(module, exports) {

//Initializing the variable
var alert = $('.alert');
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
    }, 3000);
}

/**
 * Generate the alert at the top of the page
 * @param type
 * @param message
 * @returns {string}
 */
function generateAlert(type, message) {
    return "<div class=\"alert alert-" + type + "\" role=\"alert\">" + message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" + "    <span aria-hidden=\"true\">&times;</span>" + "</button>" + "</div>";
}

$('#validate').on('click', function () {
    clearAlert();
    $('#loginForm').find("*").removeClass('is-invalid');
    var username = $('#username');
    var password = $('#password');

    var error = [];

    if (username.val().length === 0) {
        error.push({ 'username': "Username can't be empty" });
    }

    if (password.val().length === 0) {
        error.push({ 'password': "Password can't be empty" });
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
    var id = $(this).data('id');
    var href = $(this).data('href');

    var btn = $(this);
    if (confirm("Are you sure you want to delete this ban (" + id + ")?")) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            data: { id: id },
            dataType: "JSON",
            url: href,
            success: function success(data) {
                btn.removeClass("btn-success");
                btn.removeClass("btn-danger");

                $('.urlBans[data-ip="' + data.value.ip + '"]').html("<a href='" + data.value.url + "' class='btn btn-danger w-100'><i class='fas fa-user-slash'></i></a>");

                $(".bansTable").find("tr[data-id='" + id + "']").html("");

                setAlert(generateAlert("success", data.message));
            }, error: function error(data) {
                setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});

$(".deleteUrl").on("click", function () {
    var id = $(this).data('id');
    var href = $(this).data('href');

    var btn = $(this);
    if (confirm("Are you sure you want to delete this url (" + id + ")?")) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            data: { id: id },
            dataType: "JSON",
            url: href,
            success: function success(data) {
                btn.removeClass("btn-success");
                btn.removeClass("btn-danger");

                $(".urlTable").find("tr[data-id='" + id + "']").html("");
                setAlert(generateAlert("success", data.message));
            }, error: function error(data) {
                setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});

$(".submitForm").on("click", function () {
    var form = $("form");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var formData = new FormData(form[0]);
    formData.append("_method", form.attr("method"));
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: formData,
        processData: false,
        contentType: false,
        success: function success(data) {
            setAlert(generateAlert("success", data.message));
        }, error: function error(data) {
            setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
        }
    });
});

$(".deleteForbidden").on("click", function () {
    var id = $(this).data('id');
    var href = $(this).data('href');
    var keyword = $(this).data('keyword');

    var btn = $(this);
    if (confirm("Are you sure you want to delete the keyword [" + keyword + "]?")) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            data: { id: id },
            dataType: "JSON",
            url: href,
            success: function success(data) {
                btn.removeClass("btn-success");
                btn.removeClass("btn-danger");

                $(".forbiddenTable").find("tr[data-id='" + id + "']").html("");
                setAlert(generateAlert("success", data.message));
            }, error: function error(data) {
                setAlert(generateAlert("danger", JSON.parse(data.responseText).message));
            }
        });
    }
});

/***/ }),

/***/ "./resources/js/shortner.js":
/***/ (function(module, exports) {

/**
 * Generate the url through the API
 */
$("#generate").on('click', function () {
    disableButton($("#generate"));
    alert.html("");
    var href = $('#url');

    //If there's no link display an error
    if (href.val().length === 0) {
        setAlert(generateAlert('warning', 'You must enter a url before we can generate it for you...'));
    } else {
        $.ajax({
            type: "POST",
            data: { 'url': href.val() },
            url: "/api",
            success: function success(data) {
                if (data.error === false) {
                    var url = $('#short');
                    url.html("<a href='" + data.href + "' target='_blank'>" + data.href + "</a>");
                    href.val("");
                    $('#generatedLinkModal').modal('show');
                    getTableData();
                } else {
                    clearAlert();
                    setAlert(generateAlert('danger', data.message));
                }
            },
            error: function error() {
                setAlert(generateAlert('danger', "Something went wrong... Please try again."));
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
    }, 30000);
});

/**
 * Get the data for the table
 */
function getTableData() {
    $.ajax({
        type: "GET",
        url: "/api",
        dataType: "JSON",
        success: function success(data) {
            var table = generateTable(data.data);
            $('#tableResult').html(table);
        }, error: function error(data) {}
    });
}

/**
 * Generate the table
 *
 * @param data
 * @returns {string}
 */
function generateTable(data) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "table-hover";
    var modal = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

    var table = "";
    if (!Array.isArray(data) || data.length === 0) {
        table += "<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";
    } else {

        table = "<table class=\"table " + type + "\">" + "            <thead>" + "            <tr>" + "                <th>ID</th>" + "                <th>Url</th>" + "                <th>Short</th>" + "                <th>Click</th>" + "            </tr>" + "            </thead>";

        for (var i = 0; i < data.length; i++) {
            var website = $('meta[name="website"]').attr('content');
            var link = website + "/" + data[i].shortUrl;

            var url = data[i].url;
            if (modal === true && url.length > 80) {
                url = url.substring(0, 80) + "...";
            }

            table += "<tr>" + "<td>" + data[i].id + "</td>" + "<td><a href='" + link + "' target='_blank'>" + url + "</a></td>" + "<td><a href='" + link + "' target='_blank'>" + data[i].shortUrl + "</a></td>" + "<td class='text-center'>" + data[i].click + "</td>" + "</tr>";
        }

        table += "</table>";
    }

    return table;
}

$('#searchBtn').on('click', function () {
    var search = $('#search');
    disableButton($('#searchBtn'));

    $.ajax({
        type: "POST",
        url: "/api/search",
        data: { search: search.val(), _token: $('meta[name="csrf-token"]').attr('content') },
        dataType: "JSON",
        success: function success(data) {

            var table = generateTable(data.data, "table-hover", true);
            $('#searchResultTable').html(table);
            $('#searchResultModal').modal('show');
            //Empty the value in the search input box
            search.val("");
        },
        // If the request error
        error: function error(data) {
            clearAlert();
            if (data.responseText === undefined) {
                setAlert(generateAlert('danger', "Something went wrong. Please try again :("));
            } else {
                setAlert(generateAlert('danger', JSON.parse(data.responseText).message));
            }
        },
        //If the request fail
        fail: function fail(data) {
            setAlert(generateAlert('warning', data.message));
        }
    });
});

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/js/shortner.js");
module.exports = __webpack_require__("./resources/js/authentication.js");


/***/ })

/******/ });