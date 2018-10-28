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
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(1);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

var alert = $('.alert');
alert.html("");

$("#generate").on('click', function () {
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
                $('#popup').modal('show');
                generateTable();
            } else {
                alert.append(generateAlert('danger', data.message));
            }
        },
        error: function error() {
            alert.append(generateAlert('danger', "Something went wrong... Please try again."));
        }
    });
});

$(document).ready(function () {
    generateTable();

    setInterval(function () {
        generateTable();
    }, 30000);
});

function generateAlert(type, message) {
    return "<div class=\"alert alert-" + type + "\" role=\"alert\">\n" + message + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" + "    <span aria-hidden=\"true\">&times;</span>\n" + "</button>" + "</div>";
}

function generateTable() {
    var table = "";
    $.ajax({
        type: "GET",
        url: "/api",
        dataType: "JSON",
        success: function success(data) {
            if (!Array.isArray(data.data) || data.data.length === 0) {
                table += "<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";
            } else {
                for (var i = 0; i < data.data.length; i++) {
                    var website = $('meta[name="website"]').attr('content');
                    var link = website + "/" + data.data[i].shortUrl;

                    table += "<tr>" + "<td>" + data.data[i].id + "</td>" + "<td><a href='" + data.data[i].url + "' target='_blank'>" + data.data[i].url + "</a></td>" + "<td><a href='" + link + "' target='_blank'>" + data.data[i].shortUrl + "</a></td>" + "<td class='text-center'>" + data.data[i].click + "</td>" + "</tr>";
                }
            }

            $('#table').html(table);
        }
    });
}

/***/ })
/******/ ]);