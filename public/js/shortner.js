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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/shortner.js":
/***/ (function(module, exports) {

//Initializing the variable
var alert = $('.alert');
clearAlert();

/**
 * Generate the url through the API
 */
$("#generate").on('click', function () {
    disableButton($("#generate"));
    alert.html("");
    var href = $('#url');
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
 * Generate the alert at the top of the page
 * @param type
 * @param message
 * @returns {string}
 */
function generateAlert(type, message) {
    return "<div class=\"alert alert-" + type + "\" role=\"alert\">" + message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" + "    <span aria-hidden=\"true\">&times;</span>" + "</button>" + "</div>";
}

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

    var table = "";
    if (!Array.isArray(data) || data.length === 0) {
        table += "<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";
    } else {

        table = "<table class=\"table " + type + "\">" + "            <thead>" + "            <tr>" + "                <th>ID</th>" + "                <th>Url</th>" + "                <th>Short</th>" + "                <th>Click</th>" + "            </tr>" + "            </thead>";

        for (var i = 0; i < data.length; i++) {
            var website = $('meta[name="website"]').attr('content');
            var link = website + "/" + data[i].shortUrl;

            table += "<tr>" + "<td>" + data[i].id + "</td>" + "<td><a href='" + data[i].url + "' target='_blank'>" + data[i].url + "</a></td>" + "<td><a href='" + link + "' target='_blank'>" + data[i].shortUrl + "</a></td>" + "<td class='text-center'>" + data[i].click + "</td>" + "</tr>";
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

            var table = generateTable(data.data);
            console.info(JSON.stringify(table));
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
 * @param alert
 */
function setAlert(message) {
    //Add alert
    alert.append(message);

    //Clear the alert after 5 seconds
    setTimeout(function () {
        //clearAlert();
    }, 5000);
}

/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/shortner.js");


/***/ })

/******/ });