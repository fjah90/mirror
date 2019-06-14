webpackJsonp([1],[
/* 0 */,
/* 1 */,
/* 2 */,
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__(69);
var isBuffer = __webpack_require__(140);

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 */
function isStandardBrowserEnv() {
  if (typeof navigator !== 'undefined' && navigator.product === 'ReactNative') {
    return false;
  }
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object') {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = merge(result[key], val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isBuffer: isBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  extend: extend,
  trim: trim
};


/***/ }),
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */,
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */,
/* 20 */,
/* 21 */,
/* 22 */,
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__(3);
var normalizeHeaderName = __webpack_require__(142);

var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__(70);
  } else if (typeof process !== 'undefined') {
    // For node use HTTP adapter
    adapter = __webpack_require__(70);
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(12)))

/***/ }),
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */,
/* 45 */,
/* 46 */,
/* 47 */,
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */,
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */,
/* 67 */,
/* 68 */,
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),
/* 70 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);
var settle = __webpack_require__(143);
var buildURL = __webpack_require__(145);
var parseHeaders = __webpack_require__(146);
var isURLSameOrigin = __webpack_require__(147);
var createError = __webpack_require__(71);
var btoa = (typeof window !== 'undefined' && window.btoa && window.btoa.bind(window)) || __webpack_require__(148);

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();
    var loadEvent = 'onreadystatechange';
    var xDomain = false;

    // For IE 8/9 CORS support
    // Only supports POST and GET calls and doesn't returns the response headers.
    // DON'T do this for testing b/c XMLHttpRequest is mocked, not XDomainRequest.
    if ("development" !== 'test' &&
        typeof window !== 'undefined' &&
        window.XDomainRequest && !('withCredentials' in request) &&
        !isURLSameOrigin(config.url)) {
      request = new window.XDomainRequest();
      loadEvent = 'onload';
      xDomain = true;
      request.onprogress = function handleProgress() {};
      request.ontimeout = function handleTimeout() {};
    }

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password || '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    request.open(config.method.toUpperCase(), buildURL(config.url, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request[loadEvent] = function handleLoad() {
      if (!request || (request.readyState !== 4 && !xDomain)) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        // IE sends 1223 instead of 204 (https://github.com/axios/axios/issues/201)
        status: request.status === 1223 ? 204 : request.status,
        statusText: request.status === 1223 ? 'No Content' : request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config, null, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      reject(createError('timeout of ' + config.timeout + 'ms exceeded', config, 'ECONNABORTED',
        request));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__(149);

      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(config.url)) && config.xsrfCookieName ?
          cookies.read(config.xsrfCookieName) :
          undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (config.withCredentials) {
      request.withCredentials = true;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        // Expected DOMException thrown by browsers not compatible XMLHttpRequest Level 2.
        // But, this can be suppressed for 'json' type as it can be parsed by default 'transformResponse' function.
        if (config.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (requestData === undefined) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),
/* 71 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__(144);

/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, request, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, request, response);
};


/***/ }),
/* 72 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),
/* 73 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),
/* 74 */,
/* 75 */,
/* 76 */,
/* 77 */,
/* 78 */,
/* 79 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(80);
__webpack_require__(165);
module.exports = __webpack_require__(166);


/***/ }),
/* 80 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(81);

/***/ }),
/* 81 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(__webpack_provided_window_dot_jQuery, $) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios__ = __webpack_require__(138);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_axios___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_axios__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue__ = __webpack_require__(74);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_uiv__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__tinymce_tinymce_vue__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_vue_phone_number_input__ = __webpack_require__(78);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_vue_phone_number_input___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_vue_phone_number_input__);
window.$ = __webpack_provided_window_dot_jQuery = __webpack_require__(0);
window.swal = __webpack_require__(31);
__webpack_require__(32);
__webpack_require__(82);
__webpack_require__(83);
window.JSZip = __webpack_require__(33);
var pdfMake = __webpack_require__(60);
var pdfFonts = __webpack_require__(61);
pdfMake.vfs = pdfFonts.pdfMake.vfs;
__webpack_require__(20);
__webpack_require__(15);
__webpack_require__(62);
__webpack_require__(63);
__webpack_require__(64);
__webpack_require__(65);
__webpack_require__(66);
window.objectToFormData = __webpack_require__(67);
__webpack_require__(68);
__webpack_require__(135);
__webpack_require__(136);






// import 'vue-phone-number-input/dist/vue-phone-number-input.css';

window.Vue = __WEBPACK_IMPORTED_MODULE_1_vue___default.a;
window.axios = __WEBPACK_IMPORTED_MODULE_0_axios___default.a;
window.axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest'
};
// window.axios.defaults.headers.common = {
//     'X-CSRF-TOKEN': window.Laravel.csrfToken,
//     'X-Requested-With': 'XMLHttpRequest'
// };

__WEBPACK_IMPORTED_MODULE_1_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_2_uiv__);
__WEBPACK_IMPORTED_MODULE_1_vue___default.a.component('tinymce-editor', __WEBPACK_IMPORTED_MODULE_3__tinymce_tinymce_vue__["default"]);
__WEBPACK_IMPORTED_MODULE_1_vue___default.a.component('select2multags', __webpack_require__(161));
__WEBPACK_IMPORTED_MODULE_1_vue___default.a.component('vue-phone-number-input', __WEBPACK_IMPORTED_MODULE_4_vue_phone_number_input___default.a);

$.extend($.fn.dataTable.defaults, {
  responsive: false,
  lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
  language: {
    "decimal": "",
    "emptyTable": "Sin datos...",
    "info": "Mostrando _START_ hasta _END_ de _TOTAL_ resultados",
    "infoEmpty": "Mostrando 0 hasta 0 de 0 resultados",
    "infoFiltered": "(filtered from _MAX_ total entries)",
    "infoPostFix": "",
    "thousands": ",",
    "lengthMenu": "Mostrar _MENU_ resultados",
    "loadingRecords": "Cargando...",
    "processing": "Un momento...",
    "search": "Buscar:",
    "zeroRecords": "No se encontro resultados",
    "paginate": {
      "first": "Primero",
      "last": "Ultimo",
      "next": "Siguiente",
      "previous": "Anterior"
    },
    "aria": {
      "sortAscending": ": activate to sort column ascending",
      "sortDescending": ": activate to sort column descending"
    }
  }
});
$.fn.dataTable.Responsive.breakpoints = [{ name: 'desktop', width: Infinity }, { name: 'tablet', width: 1024 }, { name: 'fablet', width: 768 }, { name: 'phone', width: 480 }];
/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(0), __webpack_require__(0)))

/***/ }),
/* 82 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {/*
 * metismenu - v1.1.1
 * Easy menu jQuery plugin for Twitter Bootstrap 3
 * https://github.com/onokumus/metisMenu
 *
 * Made by Osman Nuri Okumus
 * Under MIT License
 */
;(function ($, window, document, undefined) {

    var pluginName = "metisMenu",
        defaults = {
        toggle: true,
        doubleTapToGo: false
    };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function init() {

            var $this = this.element,
                $toggle = this.settings.toggle,
                obj = this;

            if (this.isIE() <= 9) {
                $this.find("li.active").has("ul").children("ul").collapse("show");
                $this.find("li").not(".active").has("ul").children("ul").collapse("hide");
            } else {
                $this.find("li.active").has("ul").children("ul").addClass("collapse in");
                $this.find("li").not(".active").has("ul").children("ul").addClass("collapse");
            }

            //add the "doubleTapToGo" class to active items if needed
            if (obj.settings.doubleTapToGo) {
                $this.find("li.active").has("ul").children("a").addClass("doubleTapToGo");
            }

            $this.find("li").has("ul").children("a").on("click" + "." + pluginName, function (e) {
                e.preventDefault();

                //Do we need to enable the double tap
                if (obj.settings.doubleTapToGo) {

                    //if we hit a second time on the link and the href is valid, navigate to that url
                    if (obj.doubleTapToGo($(this)) && $(this).attr("href") !== "#" && $(this).attr("href") !== "") {
                        e.stopPropagation();
                        document.location = $(this).attr("href");
                        return;
                    }
                }

                $(this).parent("li").toggleClass("active").children("ul").collapse("toggle");

                if ($toggle) {
                    $(this).parent("li").siblings().removeClass("active").children("ul.in").collapse("hide");
                }
            });
        },

        isIE: function isIE() {
            //https://gist.github.com/padolsey/527683
            var undef,
                v = 3,
                div = document.createElement("div"),
                all = div.getElementsByTagName("i");

            while (div.innerHTML = "<!--[if gt IE " + ++v + "]><i></i><![endif]-->", all[0]) {
                return v > 4 ? v : undef;
            }
        },

        //Enable the link on the second click.
        doubleTapToGo: function doubleTapToGo(elem) {
            var $this = this.element;

            //if the class "doubleTapToGo" exists, remove it and return
            if (elem.hasClass("doubleTapToGo")) {
                elem.removeClass("doubleTapToGo");
                return true;
            }

            //does not exists, add a new class and return false
            if (elem.parent().children("ul").length) {
                //first remove all other class
                $this.find(".doubleTapToGo").removeClass("doubleTapToGo");
                //add the class on the current element
                elem.addClass("doubleTapToGo");
                return false;
            }
        },

        remove: function remove() {
            this.element.off("." + pluginName);
            this.element.removeData(pluginName);
        }

    };

    $.fn[pluginName] = function (options) {
        this.each(function () {
            var el = $(this);
            if (el.data(pluginName)) {
                el.data(pluginName).remove();
            }
            el.data(pluginName, new Plugin(this, options));
        });
        return this;
    };
})(jQuery, window, document);
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 83 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {//var left_side_width = 240; //Sidebar width in pixels


$(function () {
    //hide menu in small screens
    if ($(window).width() <= 992) {
        $(".wrapper").addClass("hide_menu");
    }
    //Enable sidebar toggle
    $("[data-toggle='offcanvas'].sidebar-toggle").on('click', function (e) {
        e.preventDefault();
        //Toggle Menu
        $(".wrapper").toggleClass("hide_menu");
    });
    //leftmenu init
    $('#menu').metisMenu();
    // INIT popovers
    $("[data-toggle='popover']").popover();
});
/*END DEMO*/
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(0)))

/***/ }),
/* 84 */,
/* 85 */,
/* 86 */,
/* 87 */,
/* 88 */,
/* 89 */,
/* 90 */,
/* 91 */,
/* 92 */,
/* 93 */,
/* 94 */,
/* 95 */,
/* 96 */,
/* 97 */,
/* 98 */,
/* 99 */,
/* 100 */,
/* 101 */,
/* 102 */,
/* 103 */,
/* 104 */,
/* 105 */,
/* 106 */,
/* 107 */,
/* 108 */,
/* 109 */,
/* 110 */,
/* 111 */,
/* 112 */,
/* 113 */,
/* 114 */,
/* 115 */,
/* 116 */,
/* 117 */,
/* 118 */,
/* 119 */,
/* 120 */,
/* 121 */,
/* 122 */,
/* 123 */,
/* 124 */,
/* 125 */,
/* 126 */,
/* 127 */,
/* 128 */,
/* 129 */,
/* 130 */,
/* 131 */,
/* 132 */,
/* 133 */,
/* 134 */,
/* 135 */
/***/ (function(module, exports) {

(function () {
var modern = (function () {
    'use strict';

    var global = tinymce.util.Tools.resolve('tinymce.ThemeManager');

    var global$1 = tinymce.util.Tools.resolve('tinymce.EditorManager');

    var global$2 = tinymce.util.Tools.resolve('tinymce.util.Tools');

    var isBrandingEnabled = function (editor) {
      return editor.getParam('branding', true, 'boolean');
    };
    var hasMenubar = function (editor) {
      return getMenubar(editor) !== false;
    };
    var getMenubar = function (editor) {
      return editor.getParam('menubar');
    };
    var hasStatusbar = function (editor) {
      return editor.getParam('statusbar', true, 'boolean');
    };
    var getToolbarSize = function (editor) {
      return editor.getParam('toolbar_items_size');
    };
    var isReadOnly = function (editor) {
      return editor.getParam('readonly', false, 'boolean');
    };
    var getFixedToolbarContainer = function (editor) {
      return editor.getParam('fixed_toolbar_container');
    };
    var getInlineToolbarPositionHandler = function (editor) {
      return editor.getParam('inline_toolbar_position_handler');
    };
    var getMenu = function (editor) {
      return editor.getParam('menu');
    };
    var getRemovedMenuItems = function (editor) {
      return editor.getParam('removed_menuitems', '');
    };
    var getMinWidth = function (editor) {
      return editor.getParam('min_width', 100, 'number');
    };
    var getMinHeight = function (editor) {
      return editor.getParam('min_height', 100, 'number');
    };
    var getMaxWidth = function (editor) {
      return editor.getParam('max_width', 65535, 'number');
    };
    var getMaxHeight = function (editor) {
      return editor.getParam('max_height', 65535, 'number');
    };
    var isSkinDisabled = function (editor) {
      return editor.settings.skin === false;
    };
    var isInline = function (editor) {
      return editor.getParam('inline', false, 'boolean');
    };
    var getResize = function (editor) {
      var resize = editor.getParam('resize', 'vertical');
      if (resize === false) {
        return 'none';
      } else if (resize === 'both') {
        return 'both';
      } else {
        return 'vertical';
      }
    };
    var getSkinUrl = function (editor) {
      var settings = editor.settings;
      var skin = settings.skin;
      var skinUrl = settings.skin_url;
      if (skin !== false) {
        var skinName = skin ? skin : 'lightgray';
        if (skinUrl) {
          skinUrl = editor.documentBaseURI.toAbsolute(skinUrl);
        } else {
          skinUrl = global$1.baseURL + '/skins/' + skinName;
        }
      }
      return skinUrl;
    };
    var getIndexedToolbars = function (settings, defaultToolbar) {
      var toolbars = [];
      for (var i = 1; i < 10; i++) {
        var toolbar = settings['toolbar' + i];
        if (!toolbar) {
          break;
        }
        toolbars.push(toolbar);
      }
      var mainToolbar = settings.toolbar ? [settings.toolbar] : [defaultToolbar];
      return toolbars.length > 0 ? toolbars : mainToolbar;
    };
    var getToolbars = function (editor) {
      var toolbar = editor.getParam('toolbar');
      var defaultToolbar = 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
      if (toolbar === false) {
        return [];
      } else if (global$2.isArray(toolbar)) {
        return global$2.grep(toolbar, function (toolbar) {
          return toolbar.length > 0;
        });
      } else {
        return getIndexedToolbars(editor.settings, defaultToolbar);
      }
    };

    var global$3 = tinymce.util.Tools.resolve('tinymce.dom.DOMUtils');

    var global$4 = tinymce.util.Tools.resolve('tinymce.ui.Factory');

    var global$5 = tinymce.util.Tools.resolve('tinymce.util.I18n');

    var fireSkinLoaded = function (editor) {
      return editor.fire('SkinLoaded');
    };
    var fireResizeEditor = function (editor) {
      return editor.fire('ResizeEditor');
    };
    var fireBeforeRenderUI = function (editor) {
      return editor.fire('BeforeRenderUI');
    };
    var Events = {
      fireSkinLoaded: fireSkinLoaded,
      fireResizeEditor: fireResizeEditor,
      fireBeforeRenderUI: fireBeforeRenderUI
    };

    var focus = function (panel, type) {
      return function () {
        var item = panel.find(type)[0];
        if (item) {
          item.focus(true);
        }
      };
    };
    var addKeys = function (editor, panel) {
      editor.shortcuts.add('Alt+F9', '', focus(panel, 'menubar'));
      editor.shortcuts.add('Alt+F10,F10', '', focus(panel, 'toolbar'));
      editor.shortcuts.add('Alt+F11', '', focus(panel, 'elementpath'));
      panel.on('cancel', function () {
        editor.focus();
      });
    };
    var A11y = { addKeys: addKeys };

    var global$6 = tinymce.util.Tools.resolve('tinymce.geom.Rect');

    var global$7 = tinymce.util.Tools.resolve('tinymce.util.Delay');

    var noop = function () {
      var args = [];
      for (var _i = 0; _i < arguments.length; _i++) {
        args[_i] = arguments[_i];
      }
    };
    var constant = function (value) {
      return function () {
        return value;
      };
    };
    var never = constant(false);
    var always = constant(true);

    var never$1 = never;
    var always$1 = always;
    var none = function () {
      return NONE;
    };
    var NONE = function () {
      var eq = function (o) {
        return o.isNone();
      };
      var call$$1 = function (thunk) {
        return thunk();
      };
      var id = function (n) {
        return n;
      };
      var noop$$1 = function () {
      };
      var nul = function () {
        return null;
      };
      var undef = function () {
        return undefined;
      };
      var me = {
        fold: function (n, s) {
          return n();
        },
        is: never$1,
        isSome: never$1,
        isNone: always$1,
        getOr: id,
        getOrThunk: call$$1,
        getOrDie: function (msg) {
          throw new Error(msg || 'error: getOrDie called on none.');
        },
        getOrNull: nul,
        getOrUndefined: undef,
        or: id,
        orThunk: call$$1,
        map: none,
        ap: none,
        each: noop$$1,
        bind: none,
        flatten: none,
        exists: never$1,
        forall: always$1,
        filter: none,
        equals: eq,
        equals_: eq,
        toArray: function () {
          return [];
        },
        toString: constant('none()')
      };
      if (Object.freeze)
        Object.freeze(me);
      return me;
    }();
    var some = function (a) {
      var constant_a = function () {
        return a;
      };
      var self = function () {
        return me;
      };
      var map = function (f) {
        return some(f(a));
      };
      var bind = function (f) {
        return f(a);
      };
      var me = {
        fold: function (n, s) {
          return s(a);
        },
        is: function (v) {
          return a === v;
        },
        isSome: always$1,
        isNone: never$1,
        getOr: constant_a,
        getOrThunk: constant_a,
        getOrDie: constant_a,
        getOrNull: constant_a,
        getOrUndefined: constant_a,
        or: self,
        orThunk: self,
        map: map,
        ap: function (optfab) {
          return optfab.fold(none, function (fab) {
            return some(fab(a));
          });
        },
        each: function (f) {
          f(a);
        },
        bind: bind,
        flatten: constant_a,
        exists: bind,
        forall: bind,
        filter: function (f) {
          return f(a) ? me : NONE;
        },
        equals: function (o) {
          return o.is(a);
        },
        equals_: function (o, elementEq) {
          return o.fold(never$1, function (b) {
            return elementEq(a, b);
          });
        },
        toArray: function () {
          return [a];
        },
        toString: function () {
          return 'some(' + a + ')';
        }
      };
      return me;
    };
    var from = function (value) {
      return value === null || value === undefined ? NONE : some(value);
    };
    var Option = {
      some: some,
      none: none,
      from: from
    };

    var getUiContainerDelta = function (ctrl) {
      var uiContainer = getUiContainer(ctrl);
      if (uiContainer && global$3.DOM.getStyle(uiContainer, 'position', true) !== 'static') {
        var containerPos = global$3.DOM.getPos(uiContainer);
        var dx = uiContainer.scrollLeft - containerPos.x;
        var dy = uiContainer.scrollTop - containerPos.y;
        return Option.some({
          x: dx,
          y: dy
        });
      } else {
        return Option.none();
      }
    };
    var setUiContainer = function (editor, ctrl) {
      var uiContainer = global$3.DOM.select(editor.settings.ui_container)[0];
      ctrl.getRoot().uiContainer = uiContainer;
    };
    var getUiContainer = function (ctrl) {
      return ctrl ? ctrl.getRoot().uiContainer : null;
    };
    var inheritUiContainer = function (fromCtrl, toCtrl) {
      return toCtrl.uiContainer = getUiContainer(fromCtrl);
    };
    var UiContainer = {
      getUiContainerDelta: getUiContainerDelta,
      setUiContainer: setUiContainer,
      getUiContainer: getUiContainer,
      inheritUiContainer: inheritUiContainer
    };

    var createToolbar = function (editor, items, size) {
      var toolbarItems = [];
      var buttonGroup;
      if (!items) {
        return;
      }
      global$2.each(items.split(/[ ,]/), function (item) {
        var itemName;
        var bindSelectorChanged = function () {
          var selection = editor.selection;
          if (item.settings.stateSelector) {
            selection.selectorChanged(item.settings.stateSelector, function (state) {
              item.active(state);
            }, true);
          }
          if (item.settings.disabledStateSelector) {
            selection.selectorChanged(item.settings.disabledStateSelector, function (state) {
              item.disabled(state);
            });
          }
        };
        if (item === '|') {
          buttonGroup = null;
        } else {
          if (!buttonGroup) {
            buttonGroup = {
              type: 'buttongroup',
              items: []
            };
            toolbarItems.push(buttonGroup);
          }
          if (editor.buttons[item]) {
            itemName = item;
            item = editor.buttons[itemName];
            if (typeof item === 'function') {
              item = item();
            }
            item.type = item.type || 'button';
            item.size = size;
            item = global$4.create(item);
            buttonGroup.items.push(item);
            if (editor.initialized) {
              bindSelectorChanged();
            } else {
              editor.on('init', bindSelectorChanged);
            }
          }
        }
      });
      return {
        type: 'toolbar',
        layout: 'flow',
        items: toolbarItems
      };
    };
    var createToolbars = function (editor, size) {
      var toolbars = [];
      var addToolbar = function (items) {
        if (items) {
          toolbars.push(createToolbar(editor, items, size));
        }
      };
      global$2.each(getToolbars(editor), function (toolbar) {
        addToolbar(toolbar);
      });
      if (toolbars.length) {
        return {
          type: 'panel',
          layout: 'stack',
          classes: 'toolbar-grp',
          ariaRoot: true,
          ariaRemember: true,
          items: toolbars
        };
      }
    };
    var Toolbar = {
      createToolbar: createToolbar,
      createToolbars: createToolbars
    };

    var DOM = global$3.DOM;
    var toClientRect = function (geomRect) {
      return {
        left: geomRect.x,
        top: geomRect.y,
        width: geomRect.w,
        height: geomRect.h,
        right: geomRect.x + geomRect.w,
        bottom: geomRect.y + geomRect.h
      };
    };
    var hideAllFloatingPanels = function (editor) {
      global$2.each(editor.contextToolbars, function (toolbar) {
        if (toolbar.panel) {
          toolbar.panel.hide();
        }
      });
    };
    var movePanelTo = function (panel, pos) {
      panel.moveTo(pos.left, pos.top);
    };
    var togglePositionClass = function (panel, relPos, predicate) {
      relPos = relPos ? relPos.substr(0, 2) : '';
      global$2.each({
        t: 'down',
        b: 'up'
      }, function (cls, pos) {
        panel.classes.toggle('arrow-' + cls, predicate(pos, relPos.substr(0, 1)));
      });
      global$2.each({
        l: 'left',
        r: 'right'
      }, function (cls, pos) {
        panel.classes.toggle('arrow-' + cls, predicate(pos, relPos.substr(1, 1)));
      });
    };
    var userConstrain = function (handler, x, y, elementRect, contentAreaRect, panelRect) {
      panelRect = toClientRect({
        x: x,
        y: y,
        w: panelRect.w,
        h: panelRect.h
      });
      if (handler) {
        panelRect = handler({
          elementRect: toClientRect(elementRect),
          contentAreaRect: toClientRect(contentAreaRect),
          panelRect: panelRect
        });
      }
      return panelRect;
    };
    var addContextualToolbars = function (editor) {
      var scrollContainer;
      var getContextToolbars = function () {
        return editor.contextToolbars || [];
      };
      var getElementRect = function (elm) {
        var pos, targetRect, root;
        pos = DOM.getPos(editor.getContentAreaContainer());
        targetRect = editor.dom.getRect(elm);
        root = editor.dom.getRoot();
        if (root.nodeName === 'BODY') {
          targetRect.x -= root.ownerDocument.documentElement.scrollLeft || root.scrollLeft;
          targetRect.y -= root.ownerDocument.documentElement.scrollTop || root.scrollTop;
        }
        targetRect.x += pos.x;
        targetRect.y += pos.y;
        return targetRect;
      };
      var reposition = function (match, shouldShow) {
        var relPos, panelRect, elementRect, contentAreaRect, panel, relRect, testPositions, smallElementWidthThreshold;
        var handler = getInlineToolbarPositionHandler(editor);
        if (editor.removed) {
          return;
        }
        if (!match || !match.toolbar.panel) {
          hideAllFloatingPanels(editor);
          return;
        }
        testPositions = [
          'bc-tc',
          'tc-bc',
          'tl-bl',
          'bl-tl',
          'tr-br',
          'br-tr'
        ];
        panel = match.toolbar.panel;
        if (shouldShow) {
          panel.show();
        }
        elementRect = getElementRect(match.element);
        panelRect = DOM.getRect(panel.getEl());
        contentAreaRect = DOM.getRect(editor.getContentAreaContainer() || editor.getBody());
        var delta = UiContainer.getUiContainerDelta(panel).getOr({
          x: 0,
          y: 0
        });
        elementRect.x += delta.x;
        elementRect.y += delta.y;
        panelRect.x += delta.x;
        panelRect.y += delta.y;
        contentAreaRect.x += delta.x;
        contentAreaRect.y += delta.y;
        smallElementWidthThreshold = 25;
        if (DOM.getStyle(match.element, 'display', true) !== 'inline') {
          var clientRect = match.element.getBoundingClientRect();
          elementRect.w = clientRect.width;
          elementRect.h = clientRect.height;
        }
        if (!editor.inline) {
          contentAreaRect.w = editor.getDoc().documentElement.offsetWidth;
        }
        if (editor.selection.controlSelection.isResizable(match.element) && elementRect.w < smallElementWidthThreshold) {
          elementRect = global$6.inflate(elementRect, 0, 8);
        }
        relPos = global$6.findBestRelativePosition(panelRect, elementRect, contentAreaRect, testPositions);
        elementRect = global$6.clamp(elementRect, contentAreaRect);
        if (relPos) {
          relRect = global$6.relativePosition(panelRect, elementRect, relPos);
          movePanelTo(panel, userConstrain(handler, relRect.x, relRect.y, elementRect, contentAreaRect, panelRect));
        } else {
          contentAreaRect.h += panelRect.h;
          elementRect = global$6.intersect(contentAreaRect, elementRect);
          if (elementRect) {
            relPos = global$6.findBestRelativePosition(panelRect, elementRect, contentAreaRect, [
              'bc-tc',
              'bl-tl',
              'br-tr'
            ]);
            if (relPos) {
              relRect = global$6.relativePosition(panelRect, elementRect, relPos);
              movePanelTo(panel, userConstrain(handler, relRect.x, relRect.y, elementRect, contentAreaRect, panelRect));
            } else {
              movePanelTo(panel, userConstrain(handler, elementRect.x, elementRect.y, elementRect, contentAreaRect, panelRect));
            }
          } else {
            panel.hide();
          }
        }
        togglePositionClass(panel, relPos, function (pos1, pos2) {
          return pos1 === pos2;
        });
      };
      var repositionHandler = function (show) {
        return function () {
          var execute = function () {
            if (editor.selection) {
              reposition(findFrontMostMatch(editor.selection.getNode()), show);
            }
          };
          global$7.requestAnimationFrame(execute);
        };
      };
      var bindScrollEvent = function (panel) {
        if (!scrollContainer) {
          var reposition_1 = repositionHandler(true);
          var uiContainer_1 = UiContainer.getUiContainer(panel);
          scrollContainer = editor.selection.getScrollContainer() || editor.getWin();
          DOM.bind(scrollContainer, 'scroll', reposition_1);
          DOM.bind(uiContainer_1, 'scroll', reposition_1);
          editor.on('remove', function () {
            DOM.unbind(scrollContainer, 'scroll', reposition_1);
            DOM.unbind(uiContainer_1, 'scroll', reposition_1);
          });
        }
      };
      var showContextToolbar = function (match) {
        var panel;
        if (match.toolbar.panel) {
          match.toolbar.panel.show();
          reposition(match);
          return;
        }
        panel = global$4.create({
          type: 'floatpanel',
          role: 'dialog',
          classes: 'tinymce tinymce-inline arrow',
          ariaLabel: 'Inline toolbar',
          layout: 'flex',
          direction: 'column',
          align: 'stretch',
          autohide: false,
          autofix: true,
          fixed: true,
          border: 1,
          items: Toolbar.createToolbar(editor, match.toolbar.items),
          oncancel: function () {
            editor.focus();
          }
        });
        UiContainer.setUiContainer(editor, panel);
        bindScrollEvent(panel);
        match.toolbar.panel = panel;
        panel.renderTo().reflow();
        reposition(match);
      };
      var hideAllContextToolbars = function () {
        global$2.each(getContextToolbars(), function (toolbar) {
          if (toolbar.panel) {
            toolbar.panel.hide();
          }
        });
      };
      var findFrontMostMatch = function (targetElm) {
        var i, y, parentsAndSelf;
        var toolbars = getContextToolbars();
        parentsAndSelf = editor.$(targetElm).parents().add(targetElm);
        for (i = parentsAndSelf.length - 1; i >= 0; i--) {
          for (y = toolbars.length - 1; y >= 0; y--) {
            if (toolbars[y].predicate(parentsAndSelf[i])) {
              return {
                toolbar: toolbars[y],
                element: parentsAndSelf[i]
              };
            }
          }
        }
        return null;
      };
      editor.on('click keyup setContent ObjectResized', function (e) {
        if (e.type === 'setcontent' && !e.selection) {
          return;
        }
        global$7.setEditorTimeout(editor, function () {
          var match;
          match = findFrontMostMatch(editor.selection.getNode());
          if (match) {
            hideAllContextToolbars();
            showContextToolbar(match);
          } else {
            hideAllContextToolbars();
          }
        });
      });
      editor.on('blur hide contextmenu', hideAllContextToolbars);
      editor.on('ObjectResizeStart', function () {
        var match = findFrontMostMatch(editor.selection.getNode());
        if (match && match.toolbar.panel) {
          match.toolbar.panel.hide();
        }
      });
      editor.on('ResizeEditor ResizeWindow', repositionHandler(true));
      editor.on('nodeChange', repositionHandler(false));
      editor.on('remove', function () {
        global$2.each(getContextToolbars(), function (toolbar) {
          if (toolbar.panel) {
            toolbar.panel.remove();
          }
        });
        editor.contextToolbars = {};
      });
      editor.shortcuts.add('ctrl+F9', '', function () {
        var match = findFrontMostMatch(editor.selection.getNode());
        if (match && match.toolbar.panel) {
          match.toolbar.panel.items()[0].focus();
        }
      });
    };
    var ContextToolbars = { addContextualToolbars: addContextualToolbars };

    var typeOf = function (x) {
      if (x === null)
        return 'null';
      var t = typeof x;
      if (t === 'object' && Array.prototype.isPrototypeOf(x))
        return 'array';
      if (t === 'object' && String.prototype.isPrototypeOf(x))
        return 'string';
      return t;
    };
    var isType = function (type) {
      return function (value) {
        return typeOf(value) === type;
      };
    };
    var isFunction = isType('function');
    var isNumber = isType('number');

    var rawIndexOf = function () {
      var pIndexOf = Array.prototype.indexOf;
      var fastIndex = function (xs, x) {
        return pIndexOf.call(xs, x);
      };
      var slowIndex = function (xs, x) {
        return slowIndexOf(xs, x);
      };
      return pIndexOf === undefined ? slowIndex : fastIndex;
    }();
    var indexOf = function (xs, x) {
      var r = rawIndexOf(xs, x);
      return r === -1 ? Option.none() : Option.some(r);
    };
    var exists = function (xs, pred) {
      return findIndex(xs, pred).isSome();
    };
    var map = function (xs, f) {
      var len = xs.length;
      var r = new Array(len);
      for (var i = 0; i < len; i++) {
        var x = xs[i];
        r[i] = f(x, i, xs);
      }
      return r;
    };
    var each = function (xs, f) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        f(x, i, xs);
      }
    };
    var filter = function (xs, pred) {
      var r = [];
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        if (pred(x, i, xs)) {
          r.push(x);
        }
      }
      return r;
    };
    var foldl = function (xs, f, acc) {
      each(xs, function (x) {
        acc = f(acc, x);
      });
      return acc;
    };
    var find = function (xs, pred) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        if (pred(x, i, xs)) {
          return Option.some(x);
        }
      }
      return Option.none();
    };
    var findIndex = function (xs, pred) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        if (pred(x, i, xs)) {
          return Option.some(i);
        }
      }
      return Option.none();
    };
    var slowIndexOf = function (xs, x) {
      for (var i = 0, len = xs.length; i < len; ++i) {
        if (xs[i] === x) {
          return i;
        }
      }
      return -1;
    };
    var push = Array.prototype.push;
    var flatten = function (xs) {
      var r = [];
      for (var i = 0, len = xs.length; i < len; ++i) {
        if (!Array.prototype.isPrototypeOf(xs[i]))
          throw new Error('Arr.flatten item ' + i + ' was not an array, input: ' + xs);
        push.apply(r, xs[i]);
      }
      return r;
    };
    var slice = Array.prototype.slice;
    var from$1 = isFunction(Array.from) ? Array.from : function (x) {
      return slice.call(x);
    };

    var defaultMenus = {
      file: {
        title: 'File',
        items: 'newdocument restoredraft | preview | print'
      },
      edit: {
        title: 'Edit',
        items: 'undo redo | cut copy paste pastetext | selectall'
      },
      view: {
        title: 'View',
        items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen'
      },
      insert: {
        title: 'Insert',
        items: 'image link media template codesample inserttable | charmap hr | pagebreak nonbreaking anchor toc | insertdatetime'
      },
      format: {
        title: 'Format',
        items: 'bold italic underline strikethrough superscript subscript codeformat | blockformats align | removeformat'
      },
      tools: {
        title: 'Tools',
        items: 'spellchecker spellcheckerlanguage | a11ycheck code'
      },
      table: { title: 'Table' },
      help: { title: 'Help' }
    };
    var delimiterMenuNamePair = function () {
      return {
        name: '|',
        item: { text: '|' }
      };
    };
    var createMenuNameItemPair = function (name, item) {
      var menuItem = item ? {
        name: name,
        item: item
      } : null;
      return name === '|' ? delimiterMenuNamePair() : menuItem;
    };
    var hasItemName = function (namedMenuItems, name) {
      return findIndex(namedMenuItems, function (namedMenuItem) {
        return namedMenuItem.name === name;
      }).isSome();
    };
    var isSeparator = function (namedMenuItem) {
      return namedMenuItem && namedMenuItem.item.text === '|';
    };
    var cleanupMenu = function (namedMenuItems, removedMenuItems) {
      var menuItemsPass1 = filter(namedMenuItems, function (namedMenuItem) {
        return removedMenuItems.hasOwnProperty(namedMenuItem.name) === false;
      });
      var menuItemsPass2 = filter(menuItemsPass1, function (namedMenuItem, i, namedMenuItems) {
        return !isSeparator(namedMenuItem) || !isSeparator(namedMenuItems[i - 1]);
      });
      return filter(menuItemsPass2, function (namedMenuItem, i, namedMenuItems) {
        return !isSeparator(namedMenuItem) || i > 0 && i < namedMenuItems.length - 1;
      });
    };
    var createMenu = function (editorMenuItems, menus, removedMenuItems, context) {
      var menuButton, menu, namedMenuItems, isUserDefined;
      if (menus) {
        menu = menus[context];
        isUserDefined = true;
      } else {
        menu = defaultMenus[context];
      }
      if (menu) {
        menuButton = { text: menu.title };
        namedMenuItems = [];
        global$2.each((menu.items || '').split(/[ ,]/), function (name) {
          var namedMenuItem = createMenuNameItemPair(name, editorMenuItems[name]);
          if (namedMenuItem) {
            namedMenuItems.push(namedMenuItem);
          }
        });
        if (!isUserDefined) {
          global$2.each(editorMenuItems, function (item, name) {
            if (item.context === context && !hasItemName(namedMenuItems, name)) {
              if (item.separator === 'before') {
                namedMenuItems.push(delimiterMenuNamePair());
              }
              if (item.prependToContext) {
                namedMenuItems.unshift(createMenuNameItemPair(name, item));
              } else {
                namedMenuItems.push(createMenuNameItemPair(name, item));
              }
              if (item.separator === 'after') {
                namedMenuItems.push(delimiterMenuNamePair());
              }
            }
          });
        }
        menuButton.menu = map(cleanupMenu(namedMenuItems, removedMenuItems), function (menuItem) {
          return menuItem.item;
        });
        if (!menuButton.menu.length) {
          return null;
        }
      }
      return menuButton;
    };
    var getDefaultMenubar = function (editor) {
      var name;
      var defaultMenuBar = [];
      var menu = getMenu(editor);
      if (menu) {
        for (name in menu) {
          defaultMenuBar.push(name);
        }
      } else {
        for (name in defaultMenus) {
          defaultMenuBar.push(name);
        }
      }
      return defaultMenuBar;
    };
    var createMenuButtons = function (editor) {
      var menuButtons = [];
      var defaultMenuBar = getDefaultMenubar(editor);
      var removedMenuItems = global$2.makeMap(getRemovedMenuItems(editor).split(/[ ,]/));
      var menubar = getMenubar(editor);
      var enabledMenuNames = typeof menubar === 'string' ? menubar.split(/[ ,]/) : defaultMenuBar;
      for (var i = 0; i < enabledMenuNames.length; i++) {
        var menuItems = enabledMenuNames[i];
        var menu = createMenu(editor.menuItems, getMenu(editor), removedMenuItems, menuItems);
        if (menu) {
          menuButtons.push(menu);
        }
      }
      return menuButtons;
    };
    var Menubar = { createMenuButtons: createMenuButtons };

    var DOM$1 = global$3.DOM;
    var getSize = function (elm) {
      return {
        width: elm.clientWidth,
        height: elm.clientHeight
      };
    };
    var resizeTo = function (editor, width, height) {
      var containerElm, iframeElm, containerSize, iframeSize;
      containerElm = editor.getContainer();
      iframeElm = editor.getContentAreaContainer().firstChild;
      containerSize = getSize(containerElm);
      iframeSize = getSize(iframeElm);
      if (width !== null) {
        width = Math.max(getMinWidth(editor), width);
        width = Math.min(getMaxWidth(editor), width);
        DOM$1.setStyle(containerElm, 'width', width + (containerSize.width - iframeSize.width));
        DOM$1.setStyle(iframeElm, 'width', width);
      }
      height = Math.max(getMinHeight(editor), height);
      height = Math.min(getMaxHeight(editor), height);
      DOM$1.setStyle(iframeElm, 'height', height);
      Events.fireResizeEditor(editor);
    };
    var resizeBy = function (editor, dw, dh) {
      var elm = editor.getContentAreaContainer();
      resizeTo(editor, elm.clientWidth + dw, elm.clientHeight + dh);
    };
    var Resize = {
      resizeTo: resizeTo,
      resizeBy: resizeBy
    };

    var global$8 = tinymce.util.Tools.resolve('tinymce.Env');

    var api = function (elm) {
      return {
        element: function () {
          return elm;
        }
      };
    };
    var trigger = function (sidebar, panel, callbackName) {
      var callback = sidebar.settings[callbackName];
      if (callback) {
        callback(api(panel.getEl('body')));
      }
    };
    var hidePanels = function (name, container, sidebars) {
      global$2.each(sidebars, function (sidebar) {
        var panel = container.items().filter('#' + sidebar.name)[0];
        if (panel && panel.visible() && sidebar.name !== name) {
          trigger(sidebar, panel, 'onhide');
          panel.visible(false);
        }
      });
    };
    var deactivateButtons = function (toolbar) {
      toolbar.items().each(function (ctrl) {
        ctrl.active(false);
      });
    };
    var findSidebar = function (sidebars, name) {
      return global$2.grep(sidebars, function (sidebar) {
        return sidebar.name === name;
      })[0];
    };
    var showPanel = function (editor, name, sidebars) {
      return function (e) {
        var btnCtrl = e.control;
        var container = btnCtrl.parents().filter('panel')[0];
        var panel = container.find('#' + name)[0];
        var sidebar = findSidebar(sidebars, name);
        hidePanels(name, container, sidebars);
        deactivateButtons(btnCtrl.parent());
        if (panel && panel.visible()) {
          trigger(sidebar, panel, 'onhide');
          panel.hide();
          btnCtrl.active(false);
        } else {
          if (panel) {
            panel.show();
            trigger(sidebar, panel, 'onshow');
          } else {
            panel = global$4.create({
              type: 'container',
              name: name,
              layout: 'stack',
              classes: 'sidebar-panel',
              html: ''
            });
            container.prepend(panel);
            trigger(sidebar, panel, 'onrender');
            trigger(sidebar, panel, 'onshow');
          }
          btnCtrl.active(true);
        }
        Events.fireResizeEditor(editor);
      };
    };
    var isModernBrowser = function () {
      return !global$8.ie || global$8.ie >= 11;
    };
    var hasSidebar = function (editor) {
      return isModernBrowser() && editor.sidebars ? editor.sidebars.length > 0 : false;
    };
    var createSidebar = function (editor) {
      var buttons = global$2.map(editor.sidebars, function (sidebar) {
        var settings = sidebar.settings;
        return {
          type: 'button',
          icon: settings.icon,
          image: settings.image,
          tooltip: settings.tooltip,
          onclick: showPanel(editor, sidebar.name, editor.sidebars)
        };
      });
      return {
        type: 'panel',
        name: 'sidebar',
        layout: 'stack',
        classes: 'sidebar',
        items: [{
            type: 'toolbar',
            layout: 'stack',
            classes: 'sidebar-toolbar',
            items: buttons
          }]
      };
    };
    var Sidebar = {
      hasSidebar: hasSidebar,
      createSidebar: createSidebar
    };

    var fireSkinLoaded$1 = function (editor) {
      var done = function () {
        editor._skinLoaded = true;
        Events.fireSkinLoaded(editor);
      };
      return function () {
        if (editor.initialized) {
          done();
        } else {
          editor.on('init', done);
        }
      };
    };
    var SkinLoaded = { fireSkinLoaded: fireSkinLoaded$1 };

    var DOM$2 = global$3.DOM;
    var switchMode = function (panel) {
      return function (e) {
        panel.find('*').disabled(e.mode === 'readonly');
      };
    };
    var editArea = function (border) {
      return {
        type: 'panel',
        name: 'iframe',
        layout: 'stack',
        classes: 'edit-area',
        border: border,
        html: ''
      };
    };
    var editAreaContainer = function (editor) {
      return {
        type: 'panel',
        layout: 'stack',
        classes: 'edit-aria-container',
        border: '1 0 0 0',
        items: [
          editArea('0'),
          Sidebar.createSidebar(editor)
        ]
      };
    };
    var render = function (editor, theme, args) {
      var panel, resizeHandleCtrl, startSize;
      if (isSkinDisabled(editor) === false && args.skinUiCss) {
        DOM$2.styleSheetLoader.load(args.skinUiCss, SkinLoaded.fireSkinLoaded(editor));
      } else {
        SkinLoaded.fireSkinLoaded(editor)();
      }
      panel = theme.panel = global$4.create({
        type: 'panel',
        role: 'application',
        classes: 'tinymce',
        style: 'visibility: hidden',
        layout: 'stack',
        border: 1,
        items: [
          {
            type: 'container',
            classes: 'top-part',
            items: [
              hasMenubar(editor) === false ? null : {
                type: 'menubar',
                border: '0 0 1 0',
                items: Menubar.createMenuButtons(editor)
              },
              Toolbar.createToolbars(editor, getToolbarSize(editor))
            ]
          },
          Sidebar.hasSidebar(editor) ? editAreaContainer(editor) : editArea('1 0 0 0')
        ]
      });
      UiContainer.setUiContainer(editor, panel);
      if (getResize(editor) !== 'none') {
        resizeHandleCtrl = {
          type: 'resizehandle',
          direction: getResize(editor),
          onResizeStart: function () {
            var elm = editor.getContentAreaContainer().firstChild;
            startSize = {
              width: elm.clientWidth,
              height: elm.clientHeight
            };
          },
          onResize: function (e) {
            if (getResize(editor) === 'both') {
              Resize.resizeTo(editor, startSize.width + e.deltaX, startSize.height + e.deltaY);
            } else {
              Resize.resizeTo(editor, null, startSize.height + e.deltaY);
            }
          }
        };
      }
      if (hasStatusbar(editor)) {
        var linkHtml = '<a href="https://www.tiny.cloud/?utm_campaign=editor_referral&amp;utm_medium=poweredby&amp;utm_source=tinymce" rel="noopener" target="_blank" role="presentation" tabindex="-1">Tiny</a>';
        var html = global$5.translate([
          'Powered by {0}',
          linkHtml
        ]);
        var brandingLabel = isBrandingEnabled(editor) ? {
          type: 'label',
          classes: 'branding',
          html: ' ' + html
        } : null;
        panel.add({
          type: 'panel',
          name: 'statusbar',
          classes: 'statusbar',
          layout: 'flow',
          border: '1 0 0 0',
          ariaRoot: true,
          items: [
            {
              type: 'elementpath',
              editor: editor
            },
            resizeHandleCtrl,
            brandingLabel
          ]
        });
      }
      Events.fireBeforeRenderUI(editor);
      editor.on('SwitchMode', switchMode(panel));
      panel.renderBefore(args.targetNode).reflow();
      if (isReadOnly(editor)) {
        editor.setMode('readonly');
      }
      if (args.width) {
        DOM$2.setStyle(panel.getEl(), 'width', args.width);
      }
      editor.on('remove', function () {
        panel.remove();
        panel = null;
      });
      A11y.addKeys(editor, panel);
      ContextToolbars.addContextualToolbars(editor);
      return {
        iframeContainer: panel.find('#iframe')[0].getEl(),
        editorContainer: panel.getEl()
      };
    };
    var Iframe = { render: render };

    var global$9 = tinymce.util.Tools.resolve('tinymce.dom.DomQuery');

    var count = 0;
    var funcs = {
      id: function () {
        return 'mceu_' + count++;
      },
      create: function (name$$1, attrs, children) {
        var elm = document.createElement(name$$1);
        global$3.DOM.setAttribs(elm, attrs);
        if (typeof children === 'string') {
          elm.innerHTML = children;
        } else {
          global$2.each(children, function (child) {
            if (child.nodeType) {
              elm.appendChild(child);
            }
          });
        }
        return elm;
      },
      createFragment: function (html) {
        return global$3.DOM.createFragment(html);
      },
      getWindowSize: function () {
        return global$3.DOM.getViewPort();
      },
      getSize: function (elm) {
        var width, height;
        if (elm.getBoundingClientRect) {
          var rect = elm.getBoundingClientRect();
          width = Math.max(rect.width || rect.right - rect.left, elm.offsetWidth);
          height = Math.max(rect.height || rect.bottom - rect.bottom, elm.offsetHeight);
        } else {
          width = elm.offsetWidth;
          height = elm.offsetHeight;
        }
        return {
          width: width,
          height: height
        };
      },
      getPos: function (elm, root) {
        return global$3.DOM.getPos(elm, root || funcs.getContainer());
      },
      getContainer: function () {
        return global$8.container ? global$8.container : document.body;
      },
      getViewPort: function (win) {
        return global$3.DOM.getViewPort(win);
      },
      get: function (id) {
        return document.getElementById(id);
      },
      addClass: function (elm, cls) {
        return global$3.DOM.addClass(elm, cls);
      },
      removeClass: function (elm, cls) {
        return global$3.DOM.removeClass(elm, cls);
      },
      hasClass: function (elm, cls) {
        return global$3.DOM.hasClass(elm, cls);
      },
      toggleClass: function (elm, cls, state) {
        return global$3.DOM.toggleClass(elm, cls, state);
      },
      css: function (elm, name$$1, value) {
        return global$3.DOM.setStyle(elm, name$$1, value);
      },
      getRuntimeStyle: function (elm, name$$1) {
        return global$3.DOM.getStyle(elm, name$$1, true);
      },
      on: function (target, name$$1, callback, scope) {
        return global$3.DOM.bind(target, name$$1, callback, scope);
      },
      off: function (target, name$$1, callback) {
        return global$3.DOM.unbind(target, name$$1, callback);
      },
      fire: function (target, name$$1, args) {
        return global$3.DOM.fire(target, name$$1, args);
      },
      innerHtml: function (elm, html) {
        global$3.DOM.setHTML(elm, html);
      }
    };

    var isStatic = function (elm) {
      return funcs.getRuntimeStyle(elm, 'position') === 'static';
    };
    var isFixed = function (ctrl) {
      return ctrl.state.get('fixed');
    };
    function calculateRelativePosition(ctrl, targetElm, rel) {
      var ctrlElm, pos, x, y, selfW, selfH, targetW, targetH, viewport, size;
      viewport = getWindowViewPort();
      pos = funcs.getPos(targetElm, UiContainer.getUiContainer(ctrl));
      x = pos.x;
      y = pos.y;
      if (isFixed(ctrl) && isStatic(document.body)) {
        x -= viewport.x;
        y -= viewport.y;
      }
      ctrlElm = ctrl.getEl();
      size = funcs.getSize(ctrlElm);
      selfW = size.width;
      selfH = size.height;
      size = funcs.getSize(targetElm);
      targetW = size.width;
      targetH = size.height;
      rel = (rel || '').split('');
      if (rel[0] === 'b') {
        y += targetH;
      }
      if (rel[1] === 'r') {
        x += targetW;
      }
      if (rel[0] === 'c') {
        y += Math.round(targetH / 2);
      }
      if (rel[1] === 'c') {
        x += Math.round(targetW / 2);
      }
      if (rel[3] === 'b') {
        y -= selfH;
      }
      if (rel[4] === 'r') {
        x -= selfW;
      }
      if (rel[3] === 'c') {
        y -= Math.round(selfH / 2);
      }
      if (rel[4] === 'c') {
        x -= Math.round(selfW / 2);
      }
      return {
        x: x,
        y: y,
        w: selfW,
        h: selfH
      };
    }
    var getUiContainerViewPort = function (customUiContainer) {
      return {
        x: 0,
        y: 0,
        w: customUiContainer.scrollWidth - 1,
        h: customUiContainer.scrollHeight - 1
      };
    };
    var getWindowViewPort = function () {
      var win = window;
      var x = Math.max(win.pageXOffset, document.body.scrollLeft, document.documentElement.scrollLeft);
      var y = Math.max(win.pageYOffset, document.body.scrollTop, document.documentElement.scrollTop);
      var w = win.innerWidth || document.documentElement.clientWidth;
      var h = win.innerHeight || document.documentElement.clientHeight;
      return {
        x: x,
        y: y,
        w: w,
        h: h
      };
    };
    var getViewPortRect = function (ctrl) {
      var customUiContainer = UiContainer.getUiContainer(ctrl);
      return customUiContainer && !isFixed(ctrl) ? getUiContainerViewPort(customUiContainer) : getWindowViewPort();
    };
    var Movable = {
      testMoveRel: function (elm, rels) {
        var viewPortRect = getViewPortRect(this);
        for (var i = 0; i < rels.length; i++) {
          var pos = calculateRelativePosition(this, elm, rels[i]);
          if (isFixed(this)) {
            if (pos.x > 0 && pos.x + pos.w < viewPortRect.w && pos.y > 0 && pos.y + pos.h < viewPortRect.h) {
              return rels[i];
            }
          } else {
            if (pos.x > viewPortRect.x && pos.x + pos.w < viewPortRect.w + viewPortRect.x && pos.y > viewPortRect.y && pos.y + pos.h < viewPortRect.h + viewPortRect.y) {
              return rels[i];
            }
          }
        }
        return rels[0];
      },
      moveRel: function (elm, rel) {
        if (typeof rel !== 'string') {
          rel = this.testMoveRel(elm, rel);
        }
        var pos = calculateRelativePosition(this, elm, rel);
        return this.moveTo(pos.x, pos.y);
      },
      moveBy: function (dx, dy) {
        var self$$1 = this, rect = self$$1.layoutRect();
        self$$1.moveTo(rect.x + dx, rect.y + dy);
        return self$$1;
      },
      moveTo: function (x, y) {
        var self$$1 = this;
        function constrain(value, max, size) {
          if (value < 0) {
            return 0;
          }
          if (value + size > max) {
            value = max - size;
            return value < 0 ? 0 : value;
          }
          return value;
        }
        if (self$$1.settings.constrainToViewport) {
          var viewPortRect = getViewPortRect(this);
          var layoutRect = self$$1.layoutRect();
          x = constrain(x, viewPortRect.w + viewPortRect.x, layoutRect.w);
          y = constrain(y, viewPortRect.h + viewPortRect.y, layoutRect.h);
        }
        var uiContainer = UiContainer.getUiContainer(self$$1);
        if (uiContainer && isStatic(uiContainer) && !isFixed(self$$1)) {
          x -= uiContainer.scrollLeft;
          y -= uiContainer.scrollTop;
        }
        if (uiContainer) {
          x += 1;
          y += 1;
        }
        if (self$$1.state.get('rendered')) {
          self$$1.layoutRect({
            x: x,
            y: y
          }).repaint();
        } else {
          self$$1.settings.x = x;
          self$$1.settings.y = y;
        }
        self$$1.fire('move', {
          x: x,
          y: y
        });
        return self$$1;
      }
    };

    var global$a = tinymce.util.Tools.resolve('tinymce.util.Class');

    var global$b = tinymce.util.Tools.resolve('tinymce.util.EventDispatcher');

    var BoxUtils = {
      parseBox: function (value) {
        var len;
        var radix = 10;
        if (!value) {
          return;
        }
        if (typeof value === 'number') {
          value = value || 0;
          return {
            top: value,
            left: value,
            bottom: value,
            right: value
          };
        }
        value = value.split(' ');
        len = value.length;
        if (len === 1) {
          value[1] = value[2] = value[3] = value[0];
        } else if (len === 2) {
          value[2] = value[0];
          value[3] = value[1];
        } else if (len === 3) {
          value[3] = value[1];
        }
        return {
          top: parseInt(value[0], radix) || 0,
          right: parseInt(value[1], radix) || 0,
          bottom: parseInt(value[2], radix) || 0,
          left: parseInt(value[3], radix) || 0
        };
      },
      measureBox: function (elm, prefix) {
        function getStyle(name) {
          var defaultView = elm.ownerDocument.defaultView;
          if (defaultView) {
            var computedStyle = defaultView.getComputedStyle(elm, null);
            if (computedStyle) {
              name = name.replace(/[A-Z]/g, function (a) {
                return '-' + a;
              });
              return computedStyle.getPropertyValue(name);
            } else {
              return null;
            }
          }
          return elm.currentStyle[name];
        }
        function getSide(name) {
          var val = parseFloat(getStyle(name));
          return isNaN(val) ? 0 : val;
        }
        return {
          top: getSide(prefix + 'TopWidth'),
          right: getSide(prefix + 'RightWidth'),
          bottom: getSide(prefix + 'BottomWidth'),
          left: getSide(prefix + 'LeftWidth')
        };
      }
    };

    function noop$1() {
    }
    function ClassList(onchange) {
      this.cls = [];
      this.cls._map = {};
      this.onchange = onchange || noop$1;
      this.prefix = '';
    }
    global$2.extend(ClassList.prototype, {
      add: function (cls) {
        if (cls && !this.contains(cls)) {
          this.cls._map[cls] = true;
          this.cls.push(cls);
          this._change();
        }
        return this;
      },
      remove: function (cls) {
        if (this.contains(cls)) {
          var i = void 0;
          for (i = 0; i < this.cls.length; i++) {
            if (this.cls[i] === cls) {
              break;
            }
          }
          this.cls.splice(i, 1);
          delete this.cls._map[cls];
          this._change();
        }
        return this;
      },
      toggle: function (cls, state) {
        var curState = this.contains(cls);
        if (curState !== state) {
          if (curState) {
            this.remove(cls);
          } else {
            this.add(cls);
          }
          this._change();
        }
        return this;
      },
      contains: function (cls) {
        return !!this.cls._map[cls];
      },
      _change: function () {
        delete this.clsValue;
        this.onchange.call(this);
      }
    });
    ClassList.prototype.toString = function () {
      var value;
      if (this.clsValue) {
        return this.clsValue;
      }
      value = '';
      for (var i = 0; i < this.cls.length; i++) {
        if (i > 0) {
          value += ' ';
        }
        value += this.prefix + this.cls[i];
      }
      return value;
    };

    function unique(array) {
      var uniqueItems = [];
      var i = array.length, item;
      while (i--) {
        item = array[i];
        if (!item.__checked) {
          uniqueItems.push(item);
          item.__checked = 1;
        }
      }
      i = uniqueItems.length;
      while (i--) {
        delete uniqueItems[i].__checked;
      }
      return uniqueItems;
    }
    var expression = /^([\w\\*]+)?(?:#([\w\-\\]+))?(?:\.([\w\\\.]+))?(?:\[\@?([\w\\]+)([\^\$\*!~]?=)([\w\\]+)\])?(?:\:(.+))?/i;
    var chunker = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g;
    var whiteSpace = /^\s*|\s*$/g;
    var Collection;
    var Selector = global$a.extend({
      init: function (selector) {
        var match = this.match;
        function compileNameFilter(name) {
          if (name) {
            name = name.toLowerCase();
            return function (item) {
              return name === '*' || item.type === name;
            };
          }
        }
        function compileIdFilter(id) {
          if (id) {
            return function (item) {
              return item._name === id;
            };
          }
        }
        function compileClassesFilter(classes) {
          if (classes) {
            classes = classes.split('.');
            return function (item) {
              var i = classes.length;
              while (i--) {
                if (!item.classes.contains(classes[i])) {
                  return false;
                }
              }
              return true;
            };
          }
        }
        function compileAttrFilter(name, cmp, check) {
          if (name) {
            return function (item) {
              var value = item[name] ? item[name]() : '';
              return !cmp ? !!check : cmp === '=' ? value === check : cmp === '*=' ? value.indexOf(check) >= 0 : cmp === '~=' ? (' ' + value + ' ').indexOf(' ' + check + ' ') >= 0 : cmp === '!=' ? value !== check : cmp === '^=' ? value.indexOf(check) === 0 : cmp === '$=' ? value.substr(value.length - check.length) === check : false;
            };
          }
        }
        function compilePsuedoFilter(name) {
          var notSelectors;
          if (name) {
            name = /(?:not\((.+)\))|(.+)/i.exec(name);
            if (!name[1]) {
              name = name[2];
              return function (item, index, length) {
                return name === 'first' ? index === 0 : name === 'last' ? index === length - 1 : name === 'even' ? index % 2 === 0 : name === 'odd' ? index % 2 === 1 : item[name] ? item[name]() : false;
              };
            }
            notSelectors = parseChunks(name[1], []);
            return function (item) {
              return !match(item, notSelectors);
            };
          }
        }
        function compile(selector, filters, direct) {
          var parts;
          function add(filter) {
            if (filter) {
              filters.push(filter);
            }
          }
          parts = expression.exec(selector.replace(whiteSpace, ''));
          add(compileNameFilter(parts[1]));
          add(compileIdFilter(parts[2]));
          add(compileClassesFilter(parts[3]));
          add(compileAttrFilter(parts[4], parts[5], parts[6]));
          add(compilePsuedoFilter(parts[7]));
          filters.pseudo = !!parts[7];
          filters.direct = direct;
          return filters;
        }
        function parseChunks(selector, selectors) {
          var parts = [];
          var extra, matches, i;
          do {
            chunker.exec('');
            matches = chunker.exec(selector);
            if (matches) {
              selector = matches[3];
              parts.push(matches[1]);
              if (matches[2]) {
                extra = matches[3];
                break;
              }
            }
          } while (matches);
          if (extra) {
            parseChunks(extra, selectors);
          }
          selector = [];
          for (i = 0; i < parts.length; i++) {
            if (parts[i] !== '>') {
              selector.push(compile(parts[i], [], parts[i - 1] === '>'));
            }
          }
          selectors.push(selector);
          return selectors;
        }
        this._selectors = parseChunks(selector, []);
      },
      match: function (control, selectors) {
        var i, l, si, sl, selector, fi, fl, filters, index, length, siblings, count, item;
        selectors = selectors || this._selectors;
        for (i = 0, l = selectors.length; i < l; i++) {
          selector = selectors[i];
          sl = selector.length;
          item = control;
          count = 0;
          for (si = sl - 1; si >= 0; si--) {
            filters = selector[si];
            while (item) {
              if (filters.pseudo) {
                siblings = item.parent().items();
                index = length = siblings.length;
                while (index--) {
                  if (siblings[index] === item) {
                    break;
                  }
                }
              }
              for (fi = 0, fl = filters.length; fi < fl; fi++) {
                if (!filters[fi](item, index, length)) {
                  fi = fl + 1;
                  break;
                }
              }
              if (fi === fl) {
                count++;
                break;
              } else {
                if (si === sl - 1) {
                  break;
                }
              }
              item = item.parent();
            }
          }
          if (count === sl) {
            return true;
          }
        }
        return false;
      },
      find: function (container) {
        var matches = [], i, l;
        var selectors = this._selectors;
        function collect(items, selector, index) {
          var i, l, fi, fl, item;
          var filters = selector[index];
          for (i = 0, l = items.length; i < l; i++) {
            item = items[i];
            for (fi = 0, fl = filters.length; fi < fl; fi++) {
              if (!filters[fi](item, i, l)) {
                fi = fl + 1;
                break;
              }
            }
            if (fi === fl) {
              if (index === selector.length - 1) {
                matches.push(item);
              } else {
                if (item.items) {
                  collect(item.items(), selector, index + 1);
                }
              }
            } else if (filters.direct) {
              return;
            }
            if (item.items) {
              collect(item.items(), selector, index);
            }
          }
        }
        if (container.items) {
          for (i = 0, l = selectors.length; i < l; i++) {
            collect(container.items(), selectors[i], 0);
          }
          if (l > 1) {
            matches = unique(matches);
          }
        }
        if (!Collection) {
          Collection = Selector.Collection;
        }
        return new Collection(matches);
      }
    });

    var Collection$1, proto;
    var push$1 = Array.prototype.push, slice$1 = Array.prototype.slice;
    proto = {
      length: 0,
      init: function (items) {
        if (items) {
          this.add(items);
        }
      },
      add: function (items) {
        var self = this;
        if (!global$2.isArray(items)) {
          if (items instanceof Collection$1) {
            self.add(items.toArray());
          } else {
            push$1.call(self, items);
          }
        } else {
          push$1.apply(self, items);
        }
        return self;
      },
      set: function (items) {
        var self = this;
        var len = self.length;
        var i;
        self.length = 0;
        self.add(items);
        for (i = self.length; i < len; i++) {
          delete self[i];
        }
        return self;
      },
      filter: function (selector) {
        var self = this;
        var i, l;
        var matches = [];
        var item, match;
        if (typeof selector === 'string') {
          selector = new Selector(selector);
          match = function (item) {
            return selector.match(item);
          };
        } else {
          match = selector;
        }
        for (i = 0, l = self.length; i < l; i++) {
          item = self[i];
          if (match(item)) {
            matches.push(item);
          }
        }
        return new Collection$1(matches);
      },
      slice: function () {
        return new Collection$1(slice$1.apply(this, arguments));
      },
      eq: function (index) {
        return index === -1 ? this.slice(index) : this.slice(index, +index + 1);
      },
      each: function (callback) {
        global$2.each(this, callback);
        return this;
      },
      toArray: function () {
        return global$2.toArray(this);
      },
      indexOf: function (ctrl) {
        var self = this;
        var i = self.length;
        while (i--) {
          if (self[i] === ctrl) {
            break;
          }
        }
        return i;
      },
      reverse: function () {
        return new Collection$1(global$2.toArray(this).reverse());
      },
      hasClass: function (cls) {
        return this[0] ? this[0].classes.contains(cls) : false;
      },
      prop: function (name, value) {
        var self = this;
        var item;
        if (value !== undefined) {
          self.each(function (item) {
            if (item[name]) {
              item[name](value);
            }
          });
          return self;
        }
        item = self[0];
        if (item && item[name]) {
          return item[name]();
        }
      },
      exec: function (name) {
        var self = this, args = global$2.toArray(arguments).slice(1);
        self.each(function (item) {
          if (item[name]) {
            item[name].apply(item, args);
          }
        });
        return self;
      },
      remove: function () {
        var i = this.length;
        while (i--) {
          this[i].remove();
        }
        return this;
      },
      addClass: function (cls) {
        return this.each(function (item) {
          item.classes.add(cls);
        });
      },
      removeClass: function (cls) {
        return this.each(function (item) {
          item.classes.remove(cls);
        });
      }
    };
    global$2.each('fire on off show hide append prepend before after reflow'.split(' '), function (name) {
      proto[name] = function () {
        var args = global$2.toArray(arguments);
        this.each(function (ctrl) {
          if (name in ctrl) {
            ctrl[name].apply(ctrl, args);
          }
        });
        return this;
      };
    });
    global$2.each('text name disabled active selected checked visible parent value data'.split(' '), function (name) {
      proto[name] = function (value) {
        return this.prop(name, value);
      };
    });
    Collection$1 = global$a.extend(proto);
    Selector.Collection = Collection$1;
    var Collection$2 = Collection$1;

    var Binding = function (settings) {
      this.create = settings.create;
    };
    Binding.create = function (model, name) {
      return new Binding({
        create: function (otherModel, otherName) {
          var bindings;
          var fromSelfToOther = function (e) {
            otherModel.set(otherName, e.value);
          };
          var fromOtherToSelf = function (e) {
            model.set(name, e.value);
          };
          otherModel.on('change:' + otherName, fromOtherToSelf);
          model.on('change:' + name, fromSelfToOther);
          bindings = otherModel._bindings;
          if (!bindings) {
            bindings = otherModel._bindings = [];
            otherModel.on('destroy', function () {
              var i = bindings.length;
              while (i--) {
                bindings[i]();
              }
            });
          }
          bindings.push(function () {
            model.off('change:' + name, fromSelfToOther);
          });
          return model.get(name);
        }
      });
    };

    var global$c = tinymce.util.Tools.resolve('tinymce.util.Observable');

    function isNode(node) {
      return node.nodeType > 0;
    }
    function isEqual(a, b) {
      var k, checked;
      if (a === b) {
        return true;
      }
      if (a === null || b === null) {
        return a === b;
      }
      if (typeof a !== 'object' || typeof b !== 'object') {
        return a === b;
      }
      if (global$2.isArray(b)) {
        if (a.length !== b.length) {
          return false;
        }
        k = a.length;
        while (k--) {
          if (!isEqual(a[k], b[k])) {
            return false;
          }
        }
      }
      if (isNode(a) || isNode(b)) {
        return a === b;
      }
      checked = {};
      for (k in b) {
        if (!isEqual(a[k], b[k])) {
          return false;
        }
        checked[k] = true;
      }
      for (k in a) {
        if (!checked[k] && !isEqual(a[k], b[k])) {
          return false;
        }
      }
      return true;
    }
    var ObservableObject = global$a.extend({
      Mixins: [global$c],
      init: function (data) {
        var name, value;
        data = data || {};
        for (name in data) {
          value = data[name];
          if (value instanceof Binding) {
            data[name] = value.create(this, name);
          }
        }
        this.data = data;
      },
      set: function (name, value) {
        var key, args;
        var oldValue = this.data[name];
        if (value instanceof Binding) {
          value = value.create(this, name);
        }
        if (typeof name === 'object') {
          for (key in name) {
            this.set(key, name[key]);
          }
          return this;
        }
        if (!isEqual(oldValue, value)) {
          this.data[name] = value;
          args = {
            target: this,
            name: name,
            value: value,
            oldValue: oldValue
          };
          this.fire('change:' + name, args);
          this.fire('change', args);
        }
        return this;
      },
      get: function (name) {
        return this.data[name];
      },
      has: function (name) {
        return name in this.data;
      },
      bind: function (name) {
        return Binding.create(this, name);
      },
      destroy: function () {
        this.fire('destroy');
      }
    });

    var dirtyCtrls = {}, animationFrameRequested;
    var ReflowQueue = {
      add: function (ctrl) {
        var parent$$1 = ctrl.parent();
        if (parent$$1) {
          if (!parent$$1._layout || parent$$1._layout.isNative()) {
            return;
          }
          if (!dirtyCtrls[parent$$1._id]) {
            dirtyCtrls[parent$$1._id] = parent$$1;
          }
          if (!animationFrameRequested) {
            animationFrameRequested = true;
            global$7.requestAnimationFrame(function () {
              var id, ctrl;
              animationFrameRequested = false;
              for (id in dirtyCtrls) {
                ctrl = dirtyCtrls[id];
                if (ctrl.state.get('rendered')) {
                  ctrl.reflow();
                }
              }
              dirtyCtrls = {};
            }, document.body);
          }
        }
      },
      remove: function (ctrl) {
        if (dirtyCtrls[ctrl._id]) {
          delete dirtyCtrls[ctrl._id];
        }
      }
    };

    var hasMouseWheelEventSupport = 'onmousewheel' in document;
    var hasWheelEventSupport = false;
    var classPrefix = 'mce-';
    var Control, idCounter = 0;
    var proto$1 = {
      Statics: { classPrefix: classPrefix },
      isRtl: function () {
        return Control.rtl;
      },
      classPrefix: classPrefix,
      init: function (settings) {
        var self$$1 = this;
        var classes, defaultClasses;
        function applyClasses(classes) {
          var i;
          classes = classes.split(' ');
          for (i = 0; i < classes.length; i++) {
            self$$1.classes.add(classes[i]);
          }
        }
        self$$1.settings = settings = global$2.extend({}, self$$1.Defaults, settings);
        self$$1._id = settings.id || 'mceu_' + idCounter++;
        self$$1._aria = { role: settings.role };
        self$$1._elmCache = {};
        self$$1.$ = global$9;
        self$$1.state = new ObservableObject({
          visible: true,
          active: false,
          disabled: false,
          value: ''
        });
        self$$1.data = new ObservableObject(settings.data);
        self$$1.classes = new ClassList(function () {
          if (self$$1.state.get('rendered')) {
            self$$1.getEl().className = this.toString();
          }
        });
        self$$1.classes.prefix = self$$1.classPrefix;
        classes = settings.classes;
        if (classes) {
          if (self$$1.Defaults) {
            defaultClasses = self$$1.Defaults.classes;
            if (defaultClasses && classes !== defaultClasses) {
              applyClasses(defaultClasses);
            }
          }
          applyClasses(classes);
        }
        global$2.each('title text name visible disabled active value'.split(' '), function (name$$1) {
          if (name$$1 in settings) {
            self$$1[name$$1](settings[name$$1]);
          }
        });
        self$$1.on('click', function () {
          if (self$$1.disabled()) {
            return false;
          }
        });
        self$$1.settings = settings;
        self$$1.borderBox = BoxUtils.parseBox(settings.border);
        self$$1.paddingBox = BoxUtils.parseBox(settings.padding);
        self$$1.marginBox = BoxUtils.parseBox(settings.margin);
        if (settings.hidden) {
          self$$1.hide();
        }
      },
      Properties: 'parent,name',
      getContainerElm: function () {
        var uiContainer = UiContainer.getUiContainer(this);
        return uiContainer ? uiContainer : funcs.getContainer();
      },
      getParentCtrl: function (elm) {
        var ctrl;
        var lookup = this.getRoot().controlIdLookup;
        while (elm && lookup) {
          ctrl = lookup[elm.id];
          if (ctrl) {
            break;
          }
          elm = elm.parentNode;
        }
        return ctrl;
      },
      initLayoutRect: function () {
        var self$$1 = this;
        var settings = self$$1.settings;
        var borderBox, layoutRect;
        var elm = self$$1.getEl();
        var width, height, minWidth, minHeight, autoResize;
        var startMinWidth, startMinHeight, initialSize;
        borderBox = self$$1.borderBox = self$$1.borderBox || BoxUtils.measureBox(elm, 'border');
        self$$1.paddingBox = self$$1.paddingBox || BoxUtils.measureBox(elm, 'padding');
        self$$1.marginBox = self$$1.marginBox || BoxUtils.measureBox(elm, 'margin');
        initialSize = funcs.getSize(elm);
        startMinWidth = settings.minWidth;
        startMinHeight = settings.minHeight;
        minWidth = startMinWidth || initialSize.width;
        minHeight = startMinHeight || initialSize.height;
        width = settings.width;
        height = settings.height;
        autoResize = settings.autoResize;
        autoResize = typeof autoResize !== 'undefined' ? autoResize : !width && !height;
        width = width || minWidth;
        height = height || minHeight;
        var deltaW = borderBox.left + borderBox.right;
        var deltaH = borderBox.top + borderBox.bottom;
        var maxW = settings.maxWidth || 65535;
        var maxH = settings.maxHeight || 65535;
        self$$1._layoutRect = layoutRect = {
          x: settings.x || 0,
          y: settings.y || 0,
          w: width,
          h: height,
          deltaW: deltaW,
          deltaH: deltaH,
          contentW: width - deltaW,
          contentH: height - deltaH,
          innerW: width - deltaW,
          innerH: height - deltaH,
          startMinWidth: startMinWidth || 0,
          startMinHeight: startMinHeight || 0,
          minW: Math.min(minWidth, maxW),
          minH: Math.min(minHeight, maxH),
          maxW: maxW,
          maxH: maxH,
          autoResize: autoResize,
          scrollW: 0
        };
        self$$1._lastLayoutRect = {};
        return layoutRect;
      },
      layoutRect: function (newRect) {
        var self$$1 = this;
        var curRect = self$$1._layoutRect, lastLayoutRect, size, deltaWidth, deltaHeight, repaintControls;
        if (!curRect) {
          curRect = self$$1.initLayoutRect();
        }
        if (newRect) {
          deltaWidth = curRect.deltaW;
          deltaHeight = curRect.deltaH;
          if (newRect.x !== undefined) {
            curRect.x = newRect.x;
          }
          if (newRect.y !== undefined) {
            curRect.y = newRect.y;
          }
          if (newRect.minW !== undefined) {
            curRect.minW = newRect.minW;
          }
          if (newRect.minH !== undefined) {
            curRect.minH = newRect.minH;
          }
          size = newRect.w;
          if (size !== undefined) {
            size = size < curRect.minW ? curRect.minW : size;
            size = size > curRect.maxW ? curRect.maxW : size;
            curRect.w = size;
            curRect.innerW = size - deltaWidth;
          }
          size = newRect.h;
          if (size !== undefined) {
            size = size < curRect.minH ? curRect.minH : size;
            size = size > curRect.maxH ? curRect.maxH : size;
            curRect.h = size;
            curRect.innerH = size - deltaHeight;
          }
          size = newRect.innerW;
          if (size !== undefined) {
            size = size < curRect.minW - deltaWidth ? curRect.minW - deltaWidth : size;
            size = size > curRect.maxW - deltaWidth ? curRect.maxW - deltaWidth : size;
            curRect.innerW = size;
            curRect.w = size + deltaWidth;
          }
          size = newRect.innerH;
          if (size !== undefined) {
            size = size < curRect.minH - deltaHeight ? curRect.minH - deltaHeight : size;
            size = size > curRect.maxH - deltaHeight ? curRect.maxH - deltaHeight : size;
            curRect.innerH = size;
            curRect.h = size + deltaHeight;
          }
          if (newRect.contentW !== undefined) {
            curRect.contentW = newRect.contentW;
          }
          if (newRect.contentH !== undefined) {
            curRect.contentH = newRect.contentH;
          }
          lastLayoutRect = self$$1._lastLayoutRect;
          if (lastLayoutRect.x !== curRect.x || lastLayoutRect.y !== curRect.y || lastLayoutRect.w !== curRect.w || lastLayoutRect.h !== curRect.h) {
            repaintControls = Control.repaintControls;
            if (repaintControls) {
              if (repaintControls.map && !repaintControls.map[self$$1._id]) {
                repaintControls.push(self$$1);
                repaintControls.map[self$$1._id] = true;
              }
            }
            lastLayoutRect.x = curRect.x;
            lastLayoutRect.y = curRect.y;
            lastLayoutRect.w = curRect.w;
            lastLayoutRect.h = curRect.h;
          }
          return self$$1;
        }
        return curRect;
      },
      repaint: function () {
        var self$$1 = this;
        var style, bodyStyle, bodyElm, rect, borderBox;
        var borderW, borderH, lastRepaintRect, round, value;
        round = !document.createRange ? Math.round : function (value) {
          return value;
        };
        style = self$$1.getEl().style;
        rect = self$$1._layoutRect;
        lastRepaintRect = self$$1._lastRepaintRect || {};
        borderBox = self$$1.borderBox;
        borderW = borderBox.left + borderBox.right;
        borderH = borderBox.top + borderBox.bottom;
        if (rect.x !== lastRepaintRect.x) {
          style.left = round(rect.x) + 'px';
          lastRepaintRect.x = rect.x;
        }
        if (rect.y !== lastRepaintRect.y) {
          style.top = round(rect.y) + 'px';
          lastRepaintRect.y = rect.y;
        }
        if (rect.w !== lastRepaintRect.w) {
          value = round(rect.w - borderW);
          style.width = (value >= 0 ? value : 0) + 'px';
          lastRepaintRect.w = rect.w;
        }
        if (rect.h !== lastRepaintRect.h) {
          value = round(rect.h - borderH);
          style.height = (value >= 0 ? value : 0) + 'px';
          lastRepaintRect.h = rect.h;
        }
        if (self$$1._hasBody && rect.innerW !== lastRepaintRect.innerW) {
          value = round(rect.innerW);
          bodyElm = self$$1.getEl('body');
          if (bodyElm) {
            bodyStyle = bodyElm.style;
            bodyStyle.width = (value >= 0 ? value : 0) + 'px';
          }
          lastRepaintRect.innerW = rect.innerW;
        }
        if (self$$1._hasBody && rect.innerH !== lastRepaintRect.innerH) {
          value = round(rect.innerH);
          bodyElm = bodyElm || self$$1.getEl('body');
          if (bodyElm) {
            bodyStyle = bodyStyle || bodyElm.style;
            bodyStyle.height = (value >= 0 ? value : 0) + 'px';
          }
          lastRepaintRect.innerH = rect.innerH;
        }
        self$$1._lastRepaintRect = lastRepaintRect;
        self$$1.fire('repaint', {}, false);
      },
      updateLayoutRect: function () {
        var self$$1 = this;
        self$$1.parent()._lastRect = null;
        funcs.css(self$$1.getEl(), {
          width: '',
          height: ''
        });
        self$$1._layoutRect = self$$1._lastRepaintRect = self$$1._lastLayoutRect = null;
        self$$1.initLayoutRect();
      },
      on: function (name$$1, callback) {
        var self$$1 = this;
        function resolveCallbackName(name$$1) {
          var callback, scope;
          if (typeof name$$1 !== 'string') {
            return name$$1;
          }
          return function (e) {
            if (!callback) {
              self$$1.parentsAndSelf().each(function (ctrl) {
                var callbacks = ctrl.settings.callbacks;
                if (callbacks && (callback = callbacks[name$$1])) {
                  scope = ctrl;
                  return false;
                }
              });
            }
            if (!callback) {
              e.action = name$$1;
              this.fire('execute', e);
              return;
            }
            return callback.call(scope, e);
          };
        }
        getEventDispatcher(self$$1).on(name$$1, resolveCallbackName(callback));
        return self$$1;
      },
      off: function (name$$1, callback) {
        getEventDispatcher(this).off(name$$1, callback);
        return this;
      },
      fire: function (name$$1, args, bubble) {
        var self$$1 = this;
        args = args || {};
        if (!args.control) {
          args.control = self$$1;
        }
        args = getEventDispatcher(self$$1).fire(name$$1, args);
        if (bubble !== false && self$$1.parent) {
          var parent$$1 = self$$1.parent();
          while (parent$$1 && !args.isPropagationStopped()) {
            parent$$1.fire(name$$1, args, false);
            parent$$1 = parent$$1.parent();
          }
        }
        return args;
      },
      hasEventListeners: function (name$$1) {
        return getEventDispatcher(this).has(name$$1);
      },
      parents: function (selector) {
        var self$$1 = this;
        var ctrl, parents = new Collection$2();
        for (ctrl = self$$1.parent(); ctrl; ctrl = ctrl.parent()) {
          parents.add(ctrl);
        }
        if (selector) {
          parents = parents.filter(selector);
        }
        return parents;
      },
      parentsAndSelf: function (selector) {
        return new Collection$2(this).add(this.parents(selector));
      },
      next: function () {
        var parentControls = this.parent().items();
        return parentControls[parentControls.indexOf(this) + 1];
      },
      prev: function () {
        var parentControls = this.parent().items();
        return parentControls[parentControls.indexOf(this) - 1];
      },
      innerHtml: function (html) {
        this.$el.html(html);
        return this;
      },
      getEl: function (suffix) {
        var id = suffix ? this._id + '-' + suffix : this._id;
        if (!this._elmCache[id]) {
          this._elmCache[id] = global$9('#' + id)[0];
        }
        return this._elmCache[id];
      },
      show: function () {
        return this.visible(true);
      },
      hide: function () {
        return this.visible(false);
      },
      focus: function () {
        try {
          this.getEl().focus();
        } catch (ex) {
        }
        return this;
      },
      blur: function () {
        this.getEl().blur();
        return this;
      },
      aria: function (name$$1, value) {
        var self$$1 = this, elm = self$$1.getEl(self$$1.ariaTarget);
        if (typeof value === 'undefined') {
          return self$$1._aria[name$$1];
        }
        self$$1._aria[name$$1] = value;
        if (self$$1.state.get('rendered')) {
          elm.setAttribute(name$$1 === 'role' ? name$$1 : 'aria-' + name$$1, value);
        }
        return self$$1;
      },
      encode: function (text, translate) {
        if (translate !== false) {
          text = this.translate(text);
        }
        return (text || '').replace(/[&<>"]/g, function (match) {
          return '&#' + match.charCodeAt(0) + ';';
        });
      },
      translate: function (text) {
        return Control.translate ? Control.translate(text) : text;
      },
      before: function (items) {
        var self$$1 = this, parent$$1 = self$$1.parent();
        if (parent$$1) {
          parent$$1.insert(items, parent$$1.items().indexOf(self$$1), true);
        }
        return self$$1;
      },
      after: function (items) {
        var self$$1 = this, parent$$1 = self$$1.parent();
        if (parent$$1) {
          parent$$1.insert(items, parent$$1.items().indexOf(self$$1));
        }
        return self$$1;
      },
      remove: function () {
        var self$$1 = this;
        var elm = self$$1.getEl();
        var parent$$1 = self$$1.parent();
        var newItems, i;
        if (self$$1.items) {
          var controls = self$$1.items().toArray();
          i = controls.length;
          while (i--) {
            controls[i].remove();
          }
        }
        if (parent$$1 && parent$$1.items) {
          newItems = [];
          parent$$1.items().each(function (item) {
            if (item !== self$$1) {
              newItems.push(item);
            }
          });
          parent$$1.items().set(newItems);
          parent$$1._lastRect = null;
        }
        if (self$$1._eventsRoot && self$$1._eventsRoot === self$$1) {
          global$9(elm).off();
        }
        var lookup = self$$1.getRoot().controlIdLookup;
        if (lookup) {
          delete lookup[self$$1._id];
        }
        if (elm && elm.parentNode) {
          elm.parentNode.removeChild(elm);
        }
        self$$1.state.set('rendered', false);
        self$$1.state.destroy();
        self$$1.fire('remove');
        return self$$1;
      },
      renderBefore: function (elm) {
        global$9(elm).before(this.renderHtml());
        this.postRender();
        return this;
      },
      renderTo: function (elm) {
        global$9(elm || this.getContainerElm()).append(this.renderHtml());
        this.postRender();
        return this;
      },
      preRender: function () {
      },
      render: function () {
      },
      renderHtml: function () {
        return '<div id="' + this._id + '" class="' + this.classes + '"></div>';
      },
      postRender: function () {
        var self$$1 = this;
        var settings = self$$1.settings;
        var elm, box, parent$$1, name$$1, parentEventsRoot;
        self$$1.$el = global$9(self$$1.getEl());
        self$$1.state.set('rendered', true);
        for (name$$1 in settings) {
          if (name$$1.indexOf('on') === 0) {
            self$$1.on(name$$1.substr(2), settings[name$$1]);
          }
        }
        if (self$$1._eventsRoot) {
          for (parent$$1 = self$$1.parent(); !parentEventsRoot && parent$$1; parent$$1 = parent$$1.parent()) {
            parentEventsRoot = parent$$1._eventsRoot;
          }
          if (parentEventsRoot) {
            for (name$$1 in parentEventsRoot._nativeEvents) {
              self$$1._nativeEvents[name$$1] = true;
            }
          }
        }
        bindPendingEvents(self$$1);
        if (settings.style) {
          elm = self$$1.getEl();
          if (elm) {
            elm.setAttribute('style', settings.style);
            elm.style.cssText = settings.style;
          }
        }
        if (self$$1.settings.border) {
          box = self$$1.borderBox;
          self$$1.$el.css({
            'border-top-width': box.top,
            'border-right-width': box.right,
            'border-bottom-width': box.bottom,
            'border-left-width': box.left
          });
        }
        var root = self$$1.getRoot();
        if (!root.controlIdLookup) {
          root.controlIdLookup = {};
        }
        root.controlIdLookup[self$$1._id] = self$$1;
        for (var key in self$$1._aria) {
          self$$1.aria(key, self$$1._aria[key]);
        }
        if (self$$1.state.get('visible') === false) {
          self$$1.getEl().style.display = 'none';
        }
        self$$1.bindStates();
        self$$1.state.on('change:visible', function (e) {
          var state = e.value;
          var parentCtrl;
          if (self$$1.state.get('rendered')) {
            self$$1.getEl().style.display = state === false ? 'none' : '';
            self$$1.getEl().getBoundingClientRect();
          }
          parentCtrl = self$$1.parent();
          if (parentCtrl) {
            parentCtrl._lastRect = null;
          }
          self$$1.fire(state ? 'show' : 'hide');
          ReflowQueue.add(self$$1);
        });
        self$$1.fire('postrender', {}, false);
      },
      bindStates: function () {
      },
      scrollIntoView: function (align) {
        function getOffset(elm, rootElm) {
          var x, y, parent$$1 = elm;
          x = y = 0;
          while (parent$$1 && parent$$1 !== rootElm && parent$$1.nodeType) {
            x += parent$$1.offsetLeft || 0;
            y += parent$$1.offsetTop || 0;
            parent$$1 = parent$$1.offsetParent;
          }
          return {
            x: x,
            y: y
          };
        }
        var elm = this.getEl(), parentElm = elm.parentNode;
        var x, y, width, height, parentWidth, parentHeight;
        var pos = getOffset(elm, parentElm);
        x = pos.x;
        y = pos.y;
        width = elm.offsetWidth;
        height = elm.offsetHeight;
        parentWidth = parentElm.clientWidth;
        parentHeight = parentElm.clientHeight;
        if (align === 'end') {
          x -= parentWidth - width;
          y -= parentHeight - height;
        } else if (align === 'center') {
          x -= parentWidth / 2 - width / 2;
          y -= parentHeight / 2 - height / 2;
        }
        parentElm.scrollLeft = x;
        parentElm.scrollTop = y;
        return this;
      },
      getRoot: function () {
        var ctrl = this, rootControl;
        var parents = [];
        while (ctrl) {
          if (ctrl.rootControl) {
            rootControl = ctrl.rootControl;
            break;
          }
          parents.push(ctrl);
          rootControl = ctrl;
          ctrl = ctrl.parent();
        }
        if (!rootControl) {
          rootControl = this;
        }
        var i = parents.length;
        while (i--) {
          parents[i].rootControl = rootControl;
        }
        return rootControl;
      },
      reflow: function () {
        ReflowQueue.remove(this);
        var parent$$1 = this.parent();
        if (parent$$1 && parent$$1._layout && !parent$$1._layout.isNative()) {
          parent$$1.reflow();
        }
        return this;
      }
    };
    global$2.each('text title visible disabled active value'.split(' '), function (name$$1) {
      proto$1[name$$1] = function (value) {
        if (arguments.length === 0) {
          return this.state.get(name$$1);
        }
        if (typeof value !== 'undefined') {
          this.state.set(name$$1, value);
        }
        return this;
      };
    });
    Control = global$a.extend(proto$1);
    function getEventDispatcher(obj) {
      if (!obj._eventDispatcher) {
        obj._eventDispatcher = new global$b({
          scope: obj,
          toggleEvent: function (name$$1, state) {
            if (state && global$b.isNative(name$$1)) {
              if (!obj._nativeEvents) {
                obj._nativeEvents = {};
              }
              obj._nativeEvents[name$$1] = true;
              if (obj.state.get('rendered')) {
                bindPendingEvents(obj);
              }
            }
          }
        });
      }
      return obj._eventDispatcher;
    }
    function bindPendingEvents(eventCtrl) {
      var i, l, parents, eventRootCtrl, nativeEvents, name$$1;
      function delegate(e) {
        var control = eventCtrl.getParentCtrl(e.target);
        if (control) {
          control.fire(e.type, e);
        }
      }
      function mouseLeaveHandler() {
        var ctrl = eventRootCtrl._lastHoverCtrl;
        if (ctrl) {
          ctrl.fire('mouseleave', { target: ctrl.getEl() });
          ctrl.parents().each(function (ctrl) {
            ctrl.fire('mouseleave', { target: ctrl.getEl() });
          });
          eventRootCtrl._lastHoverCtrl = null;
        }
      }
      function mouseEnterHandler(e) {
        var ctrl = eventCtrl.getParentCtrl(e.target), lastCtrl = eventRootCtrl._lastHoverCtrl, idx = 0, i, parents, lastParents;
        if (ctrl !== lastCtrl) {
          eventRootCtrl._lastHoverCtrl = ctrl;
          parents = ctrl.parents().toArray().reverse();
          parents.push(ctrl);
          if (lastCtrl) {
            lastParents = lastCtrl.parents().toArray().reverse();
            lastParents.push(lastCtrl);
            for (idx = 0; idx < lastParents.length; idx++) {
              if (parents[idx] !== lastParents[idx]) {
                break;
              }
            }
            for (i = lastParents.length - 1; i >= idx; i--) {
              lastCtrl = lastParents[i];
              lastCtrl.fire('mouseleave', { target: lastCtrl.getEl() });
            }
          }
          for (i = idx; i < parents.length; i++) {
            ctrl = parents[i];
            ctrl.fire('mouseenter', { target: ctrl.getEl() });
          }
        }
      }
      function fixWheelEvent(e) {
        e.preventDefault();
        if (e.type === 'mousewheel') {
          e.deltaY = -1 / 40 * e.wheelDelta;
          if (e.wheelDeltaX) {
            e.deltaX = -1 / 40 * e.wheelDeltaX;
          }
        } else {
          e.deltaX = 0;
          e.deltaY = e.detail;
        }
        e = eventCtrl.fire('wheel', e);
      }
      nativeEvents = eventCtrl._nativeEvents;
      if (nativeEvents) {
        parents = eventCtrl.parents().toArray();
        parents.unshift(eventCtrl);
        for (i = 0, l = parents.length; !eventRootCtrl && i < l; i++) {
          eventRootCtrl = parents[i]._eventsRoot;
        }
        if (!eventRootCtrl) {
          eventRootCtrl = parents[parents.length - 1] || eventCtrl;
        }
        eventCtrl._eventsRoot = eventRootCtrl;
        for (l = i, i = 0; i < l; i++) {
          parents[i]._eventsRoot = eventRootCtrl;
        }
        var eventRootDelegates = eventRootCtrl._delegates;
        if (!eventRootDelegates) {
          eventRootDelegates = eventRootCtrl._delegates = {};
        }
        for (name$$1 in nativeEvents) {
          if (!nativeEvents) {
            return false;
          }
          if (name$$1 === 'wheel' && !hasWheelEventSupport) {
            if (hasMouseWheelEventSupport) {
              global$9(eventCtrl.getEl()).on('mousewheel', fixWheelEvent);
            } else {
              global$9(eventCtrl.getEl()).on('DOMMouseScroll', fixWheelEvent);
            }
            continue;
          }
          if (name$$1 === 'mouseenter' || name$$1 === 'mouseleave') {
            if (!eventRootCtrl._hasMouseEnter) {
              global$9(eventRootCtrl.getEl()).on('mouseleave', mouseLeaveHandler).on('mouseover', mouseEnterHandler);
              eventRootCtrl._hasMouseEnter = 1;
            }
          } else if (!eventRootDelegates[name$$1]) {
            global$9(eventRootCtrl.getEl()).on(name$$1, delegate);
            eventRootDelegates[name$$1] = true;
          }
          nativeEvents[name$$1] = false;
        }
      }
    }
    var Control$1 = Control;

    var hasTabstopData = function (elm) {
      return elm.getAttribute('data-mce-tabstop') ? true : false;
    };
    function KeyboardNavigation (settings) {
      var root = settings.root;
      var focusedElement, focusedControl;
      function isElement(node) {
        return node && node.nodeType === 1;
      }
      try {
        focusedElement = document.activeElement;
      } catch (ex) {
        focusedElement = document.body;
      }
      focusedControl = root.getParentCtrl(focusedElement);
      function getRole(elm) {
        elm = elm || focusedElement;
        if (isElement(elm)) {
          return elm.getAttribute('role');
        }
        return null;
      }
      function getParentRole(elm) {
        var role, parent$$1 = elm || focusedElement;
        while (parent$$1 = parent$$1.parentNode) {
          if (role = getRole(parent$$1)) {
            return role;
          }
        }
      }
      function getAriaProp(name$$1) {
        var elm = focusedElement;
        if (isElement(elm)) {
          return elm.getAttribute('aria-' + name$$1);
        }
      }
      function isTextInputElement(elm) {
        var tagName = elm.tagName.toUpperCase();
        return tagName === 'INPUT' || tagName === 'TEXTAREA' || tagName === 'SELECT';
      }
      function canFocus(elm) {
        if (isTextInputElement(elm) && !elm.hidden) {
          return true;
        }
        if (hasTabstopData(elm)) {
          return true;
        }
        if (/^(button|menuitem|checkbox|tab|menuitemcheckbox|option|gridcell|slider)$/.test(getRole(elm))) {
          return true;
        }
        return false;
      }
      function getFocusElements(elm) {
        var elements = [];
        function collect(elm) {
          if (elm.nodeType !== 1 || elm.style.display === 'none' || elm.disabled) {
            return;
          }
          if (canFocus(elm)) {
            elements.push(elm);
          }
          for (var i = 0; i < elm.childNodes.length; i++) {
            collect(elm.childNodes[i]);
          }
        }
        collect(elm || root.getEl());
        return elements;
      }
      function getNavigationRoot(targetControl) {
        var navigationRoot, controls;
        targetControl = targetControl || focusedControl;
        controls = targetControl.parents().toArray();
        controls.unshift(targetControl);
        for (var i = 0; i < controls.length; i++) {
          navigationRoot = controls[i];
          if (navigationRoot.settings.ariaRoot) {
            break;
          }
        }
        return navigationRoot;
      }
      function focusFirst(targetControl) {
        var navigationRoot = getNavigationRoot(targetControl);
        var focusElements = getFocusElements(navigationRoot.getEl());
        if (navigationRoot.settings.ariaRemember && 'lastAriaIndex' in navigationRoot) {
          moveFocusToIndex(navigationRoot.lastAriaIndex, focusElements);
        } else {
          moveFocusToIndex(0, focusElements);
        }
      }
      function moveFocusToIndex(idx, elements) {
        if (idx < 0) {
          idx = elements.length - 1;
        } else if (idx >= elements.length) {
          idx = 0;
        }
        if (elements[idx]) {
          elements[idx].focus();
        }
        return idx;
      }
      function moveFocus(dir, elements) {
        var idx = -1;
        var navigationRoot = getNavigationRoot();
        elements = elements || getFocusElements(navigationRoot.getEl());
        for (var i = 0; i < elements.length; i++) {
          if (elements[i] === focusedElement) {
            idx = i;
          }
        }
        idx += dir;
        navigationRoot.lastAriaIndex = moveFocusToIndex(idx, elements);
      }
      function left() {
        var parentRole = getParentRole();
        if (parentRole === 'tablist') {
          moveFocus(-1, getFocusElements(focusedElement.parentNode));
        } else if (focusedControl.parent().submenu) {
          cancel();
        } else {
          moveFocus(-1);
        }
      }
      function right() {
        var role = getRole(), parentRole = getParentRole();
        if (parentRole === 'tablist') {
          moveFocus(1, getFocusElements(focusedElement.parentNode));
        } else if (role === 'menuitem' && parentRole === 'menu' && getAriaProp('haspopup')) {
          enter();
        } else {
          moveFocus(1);
        }
      }
      function up() {
        moveFocus(-1);
      }
      function down() {
        var role = getRole(), parentRole = getParentRole();
        if (role === 'menuitem' && parentRole === 'menubar') {
          enter();
        } else if (role === 'button' && getAriaProp('haspopup')) {
          enter({ key: 'down' });
        } else {
          moveFocus(1);
        }
      }
      function tab(e) {
        var parentRole = getParentRole();
        if (parentRole === 'tablist') {
          var elm = getFocusElements(focusedControl.getEl('body'))[0];
          if (elm) {
            elm.focus();
          }
        } else {
          moveFocus(e.shiftKey ? -1 : 1);
        }
      }
      function cancel() {
        focusedControl.fire('cancel');
      }
      function enter(aria) {
        aria = aria || {};
        focusedControl.fire('click', {
          target: focusedElement,
          aria: aria
        });
      }
      root.on('keydown', function (e) {
        function handleNonTabOrEscEvent(e, handler) {
          if (isTextInputElement(focusedElement) || hasTabstopData(focusedElement)) {
            return;
          }
          if (getRole(focusedElement) === 'slider') {
            return;
          }
          if (handler(e) !== false) {
            e.preventDefault();
          }
        }
        if (e.isDefaultPrevented()) {
          return;
        }
        switch (e.keyCode) {
        case 37:
          handleNonTabOrEscEvent(e, left);
          break;
        case 39:
          handleNonTabOrEscEvent(e, right);
          break;
        case 38:
          handleNonTabOrEscEvent(e, up);
          break;
        case 40:
          handleNonTabOrEscEvent(e, down);
          break;
        case 27:
          cancel();
          break;
        case 14:
        case 13:
        case 32:
          handleNonTabOrEscEvent(e, enter);
          break;
        case 9:
          tab(e);
          e.preventDefault();
          break;
        }
      });
      root.on('focusin', function (e) {
        focusedElement = e.target;
        focusedControl = e.control;
      });
      return { focusFirst: focusFirst };
    }

    var selectorCache = {};
    var Container = Control$1.extend({
      init: function (settings) {
        var self = this;
        self._super(settings);
        settings = self.settings;
        if (settings.fixed) {
          self.state.set('fixed', true);
        }
        self._items = new Collection$2();
        if (self.isRtl()) {
          self.classes.add('rtl');
        }
        self.bodyClasses = new ClassList(function () {
          if (self.state.get('rendered')) {
            self.getEl('body').className = this.toString();
          }
        });
        self.bodyClasses.prefix = self.classPrefix;
        self.classes.add('container');
        self.bodyClasses.add('container-body');
        if (settings.containerCls) {
          self.classes.add(settings.containerCls);
        }
        self._layout = global$4.create((settings.layout || '') + 'layout');
        if (self.settings.items) {
          self.add(self.settings.items);
        } else {
          self.add(self.render());
        }
        self._hasBody = true;
      },
      items: function () {
        return this._items;
      },
      find: function (selector) {
        selector = selectorCache[selector] = selectorCache[selector] || new Selector(selector);
        return selector.find(this);
      },
      add: function (items) {
        var self = this;
        self.items().add(self.create(items)).parent(self);
        return self;
      },
      focus: function (keyboard) {
        var self = this;
        var focusCtrl, keyboardNav, items;
        if (keyboard) {
          keyboardNav = self.keyboardNav || self.parents().eq(-1)[0].keyboardNav;
          if (keyboardNav) {
            keyboardNav.focusFirst(self);
            return;
          }
        }
        items = self.find('*');
        if (self.statusbar) {
          items.add(self.statusbar.items());
        }
        items.each(function (ctrl) {
          if (ctrl.settings.autofocus) {
            focusCtrl = null;
            return false;
          }
          if (ctrl.canFocus) {
            focusCtrl = focusCtrl || ctrl;
          }
        });
        if (focusCtrl) {
          focusCtrl.focus();
        }
        return self;
      },
      replace: function (oldItem, newItem) {
        var ctrlElm;
        var items = this.items();
        var i = items.length;
        while (i--) {
          if (items[i] === oldItem) {
            items[i] = newItem;
            break;
          }
        }
        if (i >= 0) {
          ctrlElm = newItem.getEl();
          if (ctrlElm) {
            ctrlElm.parentNode.removeChild(ctrlElm);
          }
          ctrlElm = oldItem.getEl();
          if (ctrlElm) {
            ctrlElm.parentNode.removeChild(ctrlElm);
          }
        }
        newItem.parent(this);
      },
      create: function (items) {
        var self = this;
        var settings;
        var ctrlItems = [];
        if (!global$2.isArray(items)) {
          items = [items];
        }
        global$2.each(items, function (item) {
          if (item) {
            if (!(item instanceof Control$1)) {
              if (typeof item === 'string') {
                item = { type: item };
              }
              settings = global$2.extend({}, self.settings.defaults, item);
              item.type = settings.type = settings.type || item.type || self.settings.defaultType || (settings.defaults ? settings.defaults.type : null);
              item = global$4.create(settings);
            }
            ctrlItems.push(item);
          }
        });
        return ctrlItems;
      },
      renderNew: function () {
        var self = this;
        self.items().each(function (ctrl, index) {
          var containerElm;
          ctrl.parent(self);
          if (!ctrl.state.get('rendered')) {
            containerElm = self.getEl('body');
            if (containerElm.hasChildNodes() && index <= containerElm.childNodes.length - 1) {
              global$9(containerElm.childNodes[index]).before(ctrl.renderHtml());
            } else {
              global$9(containerElm).append(ctrl.renderHtml());
            }
            ctrl.postRender();
            ReflowQueue.add(ctrl);
          }
        });
        self._layout.applyClasses(self.items().filter(':visible'));
        self._lastRect = null;
        return self;
      },
      append: function (items) {
        return this.add(items).renderNew();
      },
      prepend: function (items) {
        var self = this;
        self.items().set(self.create(items).concat(self.items().toArray()));
        return self.renderNew();
      },
      insert: function (items, index, before) {
        var self = this;
        var curItems, beforeItems, afterItems;
        items = self.create(items);
        curItems = self.items();
        if (!before && index < curItems.length - 1) {
          index += 1;
        }
        if (index >= 0 && index < curItems.length) {
          beforeItems = curItems.slice(0, index).toArray();
          afterItems = curItems.slice(index).toArray();
          curItems.set(beforeItems.concat(items, afterItems));
        }
        return self.renderNew();
      },
      fromJSON: function (data) {
        var self = this;
        for (var name in data) {
          self.find('#' + name).value(data[name]);
        }
        return self;
      },
      toJSON: function () {
        var self = this, data = {};
        self.find('*').each(function (ctrl) {
          var name = ctrl.name(), value = ctrl.value();
          if (name && typeof value !== 'undefined') {
            data[name] = value;
          }
        });
        return data;
      },
      renderHtml: function () {
        var self = this, layout = self._layout, role = this.settings.role;
        self.preRender();
        layout.preRender(self);
        return '<div id="' + self._id + '" class="' + self.classes + '"' + (role ? ' role="' + this.settings.role + '"' : '') + '>' + '<div id="' + self._id + '-body" class="' + self.bodyClasses + '">' + (self.settings.html || '') + layout.renderHtml(self) + '</div>' + '</div>';
      },
      postRender: function () {
        var self = this;
        var box;
        self.items().exec('postRender');
        self._super();
        self._layout.postRender(self);
        self.state.set('rendered', true);
        if (self.settings.style) {
          self.$el.css(self.settings.style);
        }
        if (self.settings.border) {
          box = self.borderBox;
          self.$el.css({
            'border-top-width': box.top,
            'border-right-width': box.right,
            'border-bottom-width': box.bottom,
            'border-left-width': box.left
          });
        }
        if (!self.parent()) {
          self.keyboardNav = KeyboardNavigation({ root: self });
        }
        return self;
      },
      initLayoutRect: function () {
        var self = this, layoutRect = self._super();
        self._layout.recalc(self);
        return layoutRect;
      },
      recalc: function () {
        var self = this;
        var rect = self._layoutRect;
        var lastRect = self._lastRect;
        if (!lastRect || lastRect.w !== rect.w || lastRect.h !== rect.h) {
          self._layout.recalc(self);
          rect = self.layoutRect();
          self._lastRect = {
            x: rect.x,
            y: rect.y,
            w: rect.w,
            h: rect.h
          };
          return true;
        }
      },
      reflow: function () {
        var i;
        ReflowQueue.remove(this);
        if (this.visible()) {
          Control$1.repaintControls = [];
          Control$1.repaintControls.map = {};
          this.recalc();
          i = Control$1.repaintControls.length;
          while (i--) {
            Control$1.repaintControls[i].repaint();
          }
          if (this.settings.layout !== 'flow' && this.settings.layout !== 'stack') {
            this.repaint();
          }
          Control$1.repaintControls = [];
        }
        return this;
      }
    });

    function getDocumentSize(doc) {
      var documentElement, body, scrollWidth, clientWidth;
      var offsetWidth, scrollHeight, clientHeight, offsetHeight;
      var max = Math.max;
      documentElement = doc.documentElement;
      body = doc.body;
      scrollWidth = max(documentElement.scrollWidth, body.scrollWidth);
      clientWidth = max(documentElement.clientWidth, body.clientWidth);
      offsetWidth = max(documentElement.offsetWidth, body.offsetWidth);
      scrollHeight = max(documentElement.scrollHeight, body.scrollHeight);
      clientHeight = max(documentElement.clientHeight, body.clientHeight);
      offsetHeight = max(documentElement.offsetHeight, body.offsetHeight);
      return {
        width: scrollWidth < offsetWidth ? clientWidth : scrollWidth,
        height: scrollHeight < offsetHeight ? clientHeight : scrollHeight
      };
    }
    function updateWithTouchData(e) {
      var keys, i;
      if (e.changedTouches) {
        keys = 'screenX screenY pageX pageY clientX clientY'.split(' ');
        for (i = 0; i < keys.length; i++) {
          e[keys[i]] = e.changedTouches[0][keys[i]];
        }
      }
    }
    function DragHelper (id, settings) {
      var $eventOverlay;
      var doc = settings.document || document;
      var downButton;
      var start, stop$$1, drag, startX, startY;
      settings = settings || {};
      var handleElement = doc.getElementById(settings.handle || id);
      start = function (e) {
        var docSize = getDocumentSize(doc);
        var handleElm, cursor;
        updateWithTouchData(e);
        e.preventDefault();
        downButton = e.button;
        handleElm = handleElement;
        startX = e.screenX;
        startY = e.screenY;
        if (window.getComputedStyle) {
          cursor = window.getComputedStyle(handleElm, null).getPropertyValue('cursor');
        } else {
          cursor = handleElm.runtimeStyle.cursor;
        }
        $eventOverlay = global$9('<div></div>').css({
          position: 'absolute',
          top: 0,
          left: 0,
          width: docSize.width,
          height: docSize.height,
          zIndex: 2147483647,
          opacity: 0.0001,
          cursor: cursor
        }).appendTo(doc.body);
        global$9(doc).on('mousemove touchmove', drag).on('mouseup touchend', stop$$1);
        settings.start(e);
      };
      drag = function (e) {
        updateWithTouchData(e);
        if (e.button !== downButton) {
          return stop$$1(e);
        }
        e.deltaX = e.screenX - startX;
        e.deltaY = e.screenY - startY;
        e.preventDefault();
        settings.drag(e);
      };
      stop$$1 = function (e) {
        updateWithTouchData(e);
        global$9(doc).off('mousemove touchmove', drag).off('mouseup touchend', stop$$1);
        $eventOverlay.remove();
        if (settings.stop) {
          settings.stop(e);
        }
      };
      this.destroy = function () {
        global$9(handleElement).off();
      };
      global$9(handleElement).on('mousedown touchstart', start);
    }

    var Scrollable = {
      init: function () {
        var self = this;
        self.on('repaint', self.renderScroll);
      },
      renderScroll: function () {
        var self = this, margin = 2;
        function repaintScroll() {
          var hasScrollH, hasScrollV, bodyElm;
          function repaintAxis(axisName, posName, sizeName, contentSizeName, hasScroll, ax) {
            var containerElm, scrollBarElm, scrollThumbElm;
            var containerSize, scrollSize, ratio, rect;
            var posNameLower, sizeNameLower;
            scrollBarElm = self.getEl('scroll' + axisName);
            if (scrollBarElm) {
              posNameLower = posName.toLowerCase();
              sizeNameLower = sizeName.toLowerCase();
              global$9(self.getEl('absend')).css(posNameLower, self.layoutRect()[contentSizeName] - 1);
              if (!hasScroll) {
                global$9(scrollBarElm).css('display', 'none');
                return;
              }
              global$9(scrollBarElm).css('display', 'block');
              containerElm = self.getEl('body');
              scrollThumbElm = self.getEl('scroll' + axisName + 't');
              containerSize = containerElm['client' + sizeName] - margin * 2;
              containerSize -= hasScrollH && hasScrollV ? scrollBarElm['client' + ax] : 0;
              scrollSize = containerElm['scroll' + sizeName];
              ratio = containerSize / scrollSize;
              rect = {};
              rect[posNameLower] = containerElm['offset' + posName] + margin;
              rect[sizeNameLower] = containerSize;
              global$9(scrollBarElm).css(rect);
              rect = {};
              rect[posNameLower] = containerElm['scroll' + posName] * ratio;
              rect[sizeNameLower] = containerSize * ratio;
              global$9(scrollThumbElm).css(rect);
            }
          }
          bodyElm = self.getEl('body');
          hasScrollH = bodyElm.scrollWidth > bodyElm.clientWidth;
          hasScrollV = bodyElm.scrollHeight > bodyElm.clientHeight;
          repaintAxis('h', 'Left', 'Width', 'contentW', hasScrollH, 'Height');
          repaintAxis('v', 'Top', 'Height', 'contentH', hasScrollV, 'Width');
        }
        function addScroll() {
          function addScrollAxis(axisName, posName, sizeName, deltaPosName, ax) {
            var scrollStart;
            var axisId = self._id + '-scroll' + axisName, prefix = self.classPrefix;
            global$9(self.getEl()).append('<div id="' + axisId + '" class="' + prefix + 'scrollbar ' + prefix + 'scrollbar-' + axisName + '">' + '<div id="' + axisId + 't" class="' + prefix + 'scrollbar-thumb"></div>' + '</div>');
            self.draghelper = new DragHelper(axisId + 't', {
              start: function () {
                scrollStart = self.getEl('body')['scroll' + posName];
                global$9('#' + axisId).addClass(prefix + 'active');
              },
              drag: function (e) {
                var ratio, hasScrollH, hasScrollV, containerSize;
                var layoutRect = self.layoutRect();
                hasScrollH = layoutRect.contentW > layoutRect.innerW;
                hasScrollV = layoutRect.contentH > layoutRect.innerH;
                containerSize = self.getEl('body')['client' + sizeName] - margin * 2;
                containerSize -= hasScrollH && hasScrollV ? self.getEl('scroll' + axisName)['client' + ax] : 0;
                ratio = containerSize / self.getEl('body')['scroll' + sizeName];
                self.getEl('body')['scroll' + posName] = scrollStart + e['delta' + deltaPosName] / ratio;
              },
              stop: function () {
                global$9('#' + axisId).removeClass(prefix + 'active');
              }
            });
          }
          self.classes.add('scroll');
          addScrollAxis('v', 'Top', 'Height', 'Y', 'Width');
          addScrollAxis('h', 'Left', 'Width', 'X', 'Height');
        }
        if (self.settings.autoScroll) {
          if (!self._hasScroll) {
            self._hasScroll = true;
            addScroll();
            self.on('wheel', function (e) {
              var bodyEl = self.getEl('body');
              bodyEl.scrollLeft += (e.deltaX || 0) * 10;
              bodyEl.scrollTop += e.deltaY * 10;
              repaintScroll();
            });
            global$9(self.getEl('body')).on('scroll', repaintScroll);
          }
          repaintScroll();
        }
      }
    };

    var Panel = Container.extend({
      Defaults: {
        layout: 'fit',
        containerCls: 'panel'
      },
      Mixins: [Scrollable],
      renderHtml: function () {
        var self = this;
        var layout = self._layout;
        var innerHtml = self.settings.html;
        self.preRender();
        layout.preRender(self);
        if (typeof innerHtml === 'undefined') {
          innerHtml = '<div id="' + self._id + '-body" class="' + self.bodyClasses + '">' + layout.renderHtml(self) + '</div>';
        } else {
          if (typeof innerHtml === 'function') {
            innerHtml = innerHtml.call(self);
          }
          self._hasBody = false;
        }
        return '<div id="' + self._id + '" class="' + self.classes + '" hidefocus="1" tabindex="-1" role="group">' + (self._preBodyHtml || '') + innerHtml + '</div>';
      }
    });

    var Resizable = {
      resizeToContent: function () {
        this._layoutRect.autoResize = true;
        this._lastRect = null;
        this.reflow();
      },
      resizeTo: function (w, h) {
        if (w <= 1 || h <= 1) {
          var rect = funcs.getWindowSize();
          w = w <= 1 ? w * rect.w : w;
          h = h <= 1 ? h * rect.h : h;
        }
        this._layoutRect.autoResize = false;
        return this.layoutRect({
          minW: w,
          minH: h,
          w: w,
          h: h
        }).reflow();
      },
      resizeBy: function (dw, dh) {
        var self = this, rect = self.layoutRect();
        return self.resizeTo(rect.w + dw, rect.h + dh);
      }
    };

    var documentClickHandler, documentScrollHandler, windowResizeHandler;
    var visiblePanels = [];
    var zOrder = [];
    var hasModal;
    function isChildOf(ctrl, parent$$1) {
      while (ctrl) {
        if (ctrl === parent$$1) {
          return true;
        }
        ctrl = ctrl.parent();
      }
    }
    function skipOrHidePanels(e) {
      var i = visiblePanels.length;
      while (i--) {
        var panel = visiblePanels[i], clickCtrl = panel.getParentCtrl(e.target);
        if (panel.settings.autohide) {
          if (clickCtrl) {
            if (isChildOf(clickCtrl, panel) || panel.parent() === clickCtrl) {
              continue;
            }
          }
          e = panel.fire('autohide', { target: e.target });
          if (!e.isDefaultPrevented()) {
            panel.hide();
          }
        }
      }
    }
    function bindDocumentClickHandler() {
      if (!documentClickHandler) {
        documentClickHandler = function (e) {
          if (e.button === 2) {
            return;
          }
          skipOrHidePanels(e);
        };
        global$9(document).on('click touchstart', documentClickHandler);
      }
    }
    function bindDocumentScrollHandler() {
      if (!documentScrollHandler) {
        documentScrollHandler = function () {
          var i;
          i = visiblePanels.length;
          while (i--) {
            repositionPanel(visiblePanels[i]);
          }
        };
        global$9(window).on('scroll', documentScrollHandler);
      }
    }
    function bindWindowResizeHandler() {
      if (!windowResizeHandler) {
        var docElm_1 = document.documentElement;
        var clientWidth_1 = docElm_1.clientWidth, clientHeight_1 = docElm_1.clientHeight;
        windowResizeHandler = function () {
          if (!document.all || clientWidth_1 !== docElm_1.clientWidth || clientHeight_1 !== docElm_1.clientHeight) {
            clientWidth_1 = docElm_1.clientWidth;
            clientHeight_1 = docElm_1.clientHeight;
            FloatPanel.hideAll();
          }
        };
        global$9(window).on('resize', windowResizeHandler);
      }
    }
    function repositionPanel(panel) {
      var scrollY$$1 = funcs.getViewPort().y;
      function toggleFixedChildPanels(fixed, deltaY) {
        var parent$$1;
        for (var i = 0; i < visiblePanels.length; i++) {
          if (visiblePanels[i] !== panel) {
            parent$$1 = visiblePanels[i].parent();
            while (parent$$1 && (parent$$1 = parent$$1.parent())) {
              if (parent$$1 === panel) {
                visiblePanels[i].fixed(fixed).moveBy(0, deltaY).repaint();
              }
            }
          }
        }
      }
      if (panel.settings.autofix) {
        if (!panel.state.get('fixed')) {
          panel._autoFixY = panel.layoutRect().y;
          if (panel._autoFixY < scrollY$$1) {
            panel.fixed(true).layoutRect({ y: 0 }).repaint();
            toggleFixedChildPanels(true, scrollY$$1 - panel._autoFixY);
          }
        } else {
          if (panel._autoFixY > scrollY$$1) {
            panel.fixed(false).layoutRect({ y: panel._autoFixY }).repaint();
            toggleFixedChildPanels(false, panel._autoFixY - scrollY$$1);
          }
        }
      }
    }
    function addRemove(add, ctrl) {
      var i, zIndex = FloatPanel.zIndex || 65535, topModal;
      if (add) {
        zOrder.push(ctrl);
      } else {
        i = zOrder.length;
        while (i--) {
          if (zOrder[i] === ctrl) {
            zOrder.splice(i, 1);
          }
        }
      }
      if (zOrder.length) {
        for (i = 0; i < zOrder.length; i++) {
          if (zOrder[i].modal) {
            zIndex++;
            topModal = zOrder[i];
          }
          zOrder[i].getEl().style.zIndex = zIndex;
          zOrder[i].zIndex = zIndex;
          zIndex++;
        }
      }
      var modalBlockEl = global$9('#' + ctrl.classPrefix + 'modal-block', ctrl.getContainerElm())[0];
      if (topModal) {
        global$9(modalBlockEl).css('z-index', topModal.zIndex - 1);
      } else if (modalBlockEl) {
        modalBlockEl.parentNode.removeChild(modalBlockEl);
        hasModal = false;
      }
      FloatPanel.currentZIndex = zIndex;
    }
    var FloatPanel = Panel.extend({
      Mixins: [
        Movable,
        Resizable
      ],
      init: function (settings) {
        var self$$1 = this;
        self$$1._super(settings);
        self$$1._eventsRoot = self$$1;
        self$$1.classes.add('floatpanel');
        if (settings.autohide) {
          bindDocumentClickHandler();
          bindWindowResizeHandler();
          visiblePanels.push(self$$1);
        }
        if (settings.autofix) {
          bindDocumentScrollHandler();
          self$$1.on('move', function () {
            repositionPanel(this);
          });
        }
        self$$1.on('postrender show', function (e) {
          if (e.control === self$$1) {
            var $modalBlockEl_1;
            var prefix_1 = self$$1.classPrefix;
            if (self$$1.modal && !hasModal) {
              $modalBlockEl_1 = global$9('#' + prefix_1 + 'modal-block', self$$1.getContainerElm());
              if (!$modalBlockEl_1[0]) {
                $modalBlockEl_1 = global$9('<div id="' + prefix_1 + 'modal-block" class="' + prefix_1 + 'reset ' + prefix_1 + 'fade"></div>').appendTo(self$$1.getContainerElm());
              }
              global$7.setTimeout(function () {
                $modalBlockEl_1.addClass(prefix_1 + 'in');
                global$9(self$$1.getEl()).addClass(prefix_1 + 'in');
              });
              hasModal = true;
            }
            addRemove(true, self$$1);
          }
        });
        self$$1.on('show', function () {
          self$$1.parents().each(function (ctrl) {
            if (ctrl.state.get('fixed')) {
              self$$1.fixed(true);
              return false;
            }
          });
        });
        if (settings.popover) {
          self$$1._preBodyHtml = '<div class="' + self$$1.classPrefix + 'arrow"></div>';
          self$$1.classes.add('popover').add('bottom').add(self$$1.isRtl() ? 'end' : 'start');
        }
        self$$1.aria('label', settings.ariaLabel);
        self$$1.aria('labelledby', self$$1._id);
        self$$1.aria('describedby', self$$1.describedBy || self$$1._id + '-none');
      },
      fixed: function (state) {
        var self$$1 = this;
        if (self$$1.state.get('fixed') !== state) {
          if (self$$1.state.get('rendered')) {
            var viewport = funcs.getViewPort();
            if (state) {
              self$$1.layoutRect().y -= viewport.y;
            } else {
              self$$1.layoutRect().y += viewport.y;
            }
          }
          self$$1.classes.toggle('fixed', state);
          self$$1.state.set('fixed', state);
        }
        return self$$1;
      },
      show: function () {
        var self$$1 = this;
        var i;
        var state = self$$1._super();
        i = visiblePanels.length;
        while (i--) {
          if (visiblePanels[i] === self$$1) {
            break;
          }
        }
        if (i === -1) {
          visiblePanels.push(self$$1);
        }
        return state;
      },
      hide: function () {
        removeVisiblePanel(this);
        addRemove(false, this);
        return this._super();
      },
      hideAll: function () {
        FloatPanel.hideAll();
      },
      close: function () {
        var self$$1 = this;
        if (!self$$1.fire('close').isDefaultPrevented()) {
          self$$1.remove();
          addRemove(false, self$$1);
        }
        return self$$1;
      },
      remove: function () {
        removeVisiblePanel(this);
        this._super();
      },
      postRender: function () {
        var self$$1 = this;
        if (self$$1.settings.bodyRole) {
          this.getEl('body').setAttribute('role', self$$1.settings.bodyRole);
        }
        return self$$1._super();
      }
    });
    FloatPanel.hideAll = function () {
      var i = visiblePanels.length;
      while (i--) {
        var panel = visiblePanels[i];
        if (panel && panel.settings.autohide) {
          panel.hide();
          visiblePanels.splice(i, 1);
        }
      }
    };
    function removeVisiblePanel(panel) {
      var i;
      i = visiblePanels.length;
      while (i--) {
        if (visiblePanels[i] === panel) {
          visiblePanels.splice(i, 1);
        }
      }
      i = zOrder.length;
      while (i--) {
        if (zOrder[i] === panel) {
          zOrder.splice(i, 1);
        }
      }
    }

    var isFixed$1 = function (inlineToolbarContainer, editor) {
      return !!(inlineToolbarContainer && !editor.settings.ui_container);
    };
    var render$1 = function (editor, theme, args) {
      var panel, inlineToolbarContainer;
      var DOM = global$3.DOM;
      var fixedToolbarContainer = getFixedToolbarContainer(editor);
      if (fixedToolbarContainer) {
        inlineToolbarContainer = DOM.select(fixedToolbarContainer)[0];
      }
      var reposition = function () {
        if (panel && panel.moveRel && panel.visible() && !panel._fixed) {
          var scrollContainer = editor.selection.getScrollContainer(), body = editor.getBody();
          var deltaX = 0, deltaY = 0;
          if (scrollContainer) {
            var bodyPos = DOM.getPos(body), scrollContainerPos = DOM.getPos(scrollContainer);
            deltaX = Math.max(0, scrollContainerPos.x - bodyPos.x);
            deltaY = Math.max(0, scrollContainerPos.y - bodyPos.y);
          }
          panel.fixed(false).moveRel(body, editor.rtl ? [
            'tr-br',
            'br-tr'
          ] : [
            'tl-bl',
            'bl-tl',
            'tr-br'
          ]).moveBy(deltaX, deltaY);
        }
      };
      var show = function () {
        if (panel) {
          panel.show();
          reposition();
          DOM.addClass(editor.getBody(), 'mce-edit-focus');
        }
      };
      var hide = function () {
        if (panel) {
          panel.hide();
          FloatPanel.hideAll();
          DOM.removeClass(editor.getBody(), 'mce-edit-focus');
        }
      };
      var render = function () {
        if (panel) {
          if (!panel.visible()) {
            show();
          }
          return;
        }
        panel = theme.panel = global$4.create({
          type: inlineToolbarContainer ? 'panel' : 'floatpanel',
          role: 'application',
          classes: 'tinymce tinymce-inline',
          layout: 'flex',
          direction: 'column',
          align: 'stretch',
          autohide: false,
          autofix: true,
          fixed: isFixed$1(inlineToolbarContainer, editor),
          border: 1,
          items: [
            hasMenubar(editor) === false ? null : {
              type: 'menubar',
              border: '0 0 1 0',
              items: Menubar.createMenuButtons(editor)
            },
            Toolbar.createToolbars(editor, getToolbarSize(editor))
          ]
        });
        UiContainer.setUiContainer(editor, panel);
        Events.fireBeforeRenderUI(editor);
        if (inlineToolbarContainer) {
          panel.renderTo(inlineToolbarContainer).reflow();
        } else {
          panel.renderTo().reflow();
        }
        A11y.addKeys(editor, panel);
        show();
        ContextToolbars.addContextualToolbars(editor);
        editor.on('nodeChange', reposition);
        editor.on('ResizeWindow', reposition);
        editor.on('activate', show);
        editor.on('deactivate', hide);
        editor.nodeChanged();
      };
      editor.settings.content_editable = true;
      editor.on('focus', function () {
        if (isSkinDisabled(editor) === false && args.skinUiCss) {
          DOM.styleSheetLoader.load(args.skinUiCss, render, render);
        } else {
          render();
        }
      });
      editor.on('blur hide', hide);
      editor.on('remove', function () {
        if (panel) {
          panel.remove();
          panel = null;
        }
      });
      if (isSkinDisabled(editor) === false && args.skinUiCss) {
        DOM.styleSheetLoader.load(args.skinUiCss, SkinLoaded.fireSkinLoaded(editor));
      } else {
        SkinLoaded.fireSkinLoaded(editor)();
      }
      return {};
    };
    var Inline = { render: render$1 };

    function Throbber (elm, inline) {
      var self = this;
      var state;
      var classPrefix = Control$1.classPrefix;
      var timer;
      self.show = function (time, callback) {
        function render() {
          if (state) {
            global$9(elm).append('<div class="' + classPrefix + 'throbber' + (inline ? ' ' + classPrefix + 'throbber-inline' : '') + '"></div>');
            if (callback) {
              callback();
            }
          }
        }
        self.hide();
        state = true;
        if (time) {
          timer = global$7.setTimeout(render, time);
        } else {
          render();
        }
        return self;
      };
      self.hide = function () {
        var child = elm.lastChild;
        global$7.clearTimeout(timer);
        if (child && child.className.indexOf('throbber') !== -1) {
          child.parentNode.removeChild(child);
        }
        state = false;
        return self;
      };
    }

    var setup = function (editor, theme) {
      var throbber;
      editor.on('ProgressState', function (e) {
        throbber = throbber || new Throbber(theme.panel.getEl('body'));
        if (e.state) {
          throbber.show(e.time);
        } else {
          throbber.hide();
        }
      });
    };
    var ProgressState = { setup: setup };

    var renderUI = function (editor, theme, args) {
      var skinUrl = getSkinUrl(editor);
      if (skinUrl) {
        args.skinUiCss = skinUrl + '/skin.min.css';
        editor.contentCSS.push(skinUrl + '/content' + (editor.inline ? '.inline' : '') + '.min.css');
      }
      ProgressState.setup(editor, theme);
      return isInline(editor) ? Inline.render(editor, theme, args) : Iframe.render(editor, theme, args);
    };
    var Render = { renderUI: renderUI };

    var Tooltip = Control$1.extend({
      Mixins: [Movable],
      Defaults: { classes: 'widget tooltip tooltip-n' },
      renderHtml: function () {
        var self = this, prefix = self.classPrefix;
        return '<div id="' + self._id + '" class="' + self.classes + '" role="presentation">' + '<div class="' + prefix + 'tooltip-arrow"></div>' + '<div class="' + prefix + 'tooltip-inner">' + self.encode(self.state.get('text')) + '</div>' + '</div>';
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:text', function (e) {
          self.getEl().lastChild.innerHTML = self.encode(e.value);
        });
        return self._super();
      },
      repaint: function () {
        var self = this;
        var style, rect;
        style = self.getEl().style;
        rect = self._layoutRect;
        style.left = rect.x + 'px';
        style.top = rect.y + 'px';
        style.zIndex = 65535 + 65535;
      }
    });

    var Widget = Control$1.extend({
      init: function (settings) {
        var self = this;
        self._super(settings);
        settings = self.settings;
        self.canFocus = true;
        if (settings.tooltip && Widget.tooltips !== false) {
          self.on('mouseenter', function (e) {
            var tooltip = self.tooltip().moveTo(-65535);
            if (e.control === self) {
              var rel = tooltip.text(settings.tooltip).show().testMoveRel(self.getEl(), [
                'bc-tc',
                'bc-tl',
                'bc-tr'
              ]);
              tooltip.classes.toggle('tooltip-n', rel === 'bc-tc');
              tooltip.classes.toggle('tooltip-nw', rel === 'bc-tl');
              tooltip.classes.toggle('tooltip-ne', rel === 'bc-tr');
              tooltip.moveRel(self.getEl(), rel);
            } else {
              tooltip.hide();
            }
          });
          self.on('mouseleave mousedown click', function () {
            self.tooltip().remove();
            self._tooltip = null;
          });
        }
        self.aria('label', settings.ariaLabel || settings.tooltip);
      },
      tooltip: function () {
        if (!this._tooltip) {
          this._tooltip = new Tooltip({ type: 'tooltip' });
          UiContainer.inheritUiContainer(this, this._tooltip);
          this._tooltip.renderTo();
        }
        return this._tooltip;
      },
      postRender: function () {
        var self = this, settings = self.settings;
        self._super();
        if (!self.parent() && (settings.width || settings.height)) {
          self.initLayoutRect();
          self.repaint();
        }
        if (settings.autofocus) {
          self.focus();
        }
      },
      bindStates: function () {
        var self = this;
        function disable(state) {
          self.aria('disabled', state);
          self.classes.toggle('disabled', state);
        }
        function active(state) {
          self.aria('pressed', state);
          self.classes.toggle('active', state);
        }
        self.state.on('change:disabled', function (e) {
          disable(e.value);
        });
        self.state.on('change:active', function (e) {
          active(e.value);
        });
        if (self.state.get('disabled')) {
          disable(true);
        }
        if (self.state.get('active')) {
          active(true);
        }
        return self._super();
      },
      remove: function () {
        this._super();
        if (this._tooltip) {
          this._tooltip.remove();
          this._tooltip = null;
        }
      }
    });

    var Progress = Widget.extend({
      Defaults: { value: 0 },
      init: function (settings) {
        var self = this;
        self._super(settings);
        self.classes.add('progress');
        if (!self.settings.filter) {
          self.settings.filter = function (value) {
            return Math.round(value);
          };
        }
      },
      renderHtml: function () {
        var self = this, id = self._id, prefix = this.classPrefix;
        return '<div id="' + id + '" class="' + self.classes + '">' + '<div class="' + prefix + 'bar-container">' + '<div class="' + prefix + 'bar"></div>' + '</div>' + '<div class="' + prefix + 'text">0%</div>' + '</div>';
      },
      postRender: function () {
        var self = this;
        self._super();
        self.value(self.settings.value);
        return self;
      },
      bindStates: function () {
        var self = this;
        function setValue(value) {
          value = self.settings.filter(value);
          self.getEl().lastChild.innerHTML = value + '%';
          self.getEl().firstChild.firstChild.style.width = value + '%';
        }
        self.state.on('change:value', function (e) {
          setValue(e.value);
        });
        setValue(self.state.get('value'));
        return self._super();
      }
    });

    var updateLiveRegion = function (ctx, text) {
      ctx.getEl().lastChild.textContent = text + (ctx.progressBar ? ' ' + ctx.progressBar.value() + '%' : '');
    };
    var Notification = Control$1.extend({
      Mixins: [Movable],
      Defaults: { classes: 'widget notification' },
      init: function (settings) {
        var self = this;
        self._super(settings);
        self.maxWidth = settings.maxWidth;
        if (settings.text) {
          self.text(settings.text);
        }
        if (settings.icon) {
          self.icon = settings.icon;
        }
        if (settings.color) {
          self.color = settings.color;
        }
        if (settings.type) {
          self.classes.add('notification-' + settings.type);
        }
        if (settings.timeout && (settings.timeout < 0 || settings.timeout > 0) && !settings.closeButton) {
          self.closeButton = false;
        } else {
          self.classes.add('has-close');
          self.closeButton = true;
        }
        if (settings.progressBar) {
          self.progressBar = new Progress();
        }
        self.on('click', function (e) {
          if (e.target.className.indexOf(self.classPrefix + 'close') !== -1) {
            self.close();
          }
        });
      },
      renderHtml: function () {
        var self = this;
        var prefix = self.classPrefix;
        var icon = '', closeButton = '', progressBar = '', notificationStyle = '';
        if (self.icon) {
          icon = '<i class="' + prefix + 'ico' + ' ' + prefix + 'i-' + self.icon + '"></i>';
        }
        notificationStyle = ' style="max-width: ' + self.maxWidth + 'px;' + (self.color ? 'background-color: ' + self.color + ';"' : '"');
        if (self.closeButton) {
          closeButton = '<button type="button" class="' + prefix + 'close" aria-hidden="true">\xD7</button>';
        }
        if (self.progressBar) {
          progressBar = self.progressBar.renderHtml();
        }
        return '<div id="' + self._id + '" class="' + self.classes + '"' + notificationStyle + ' role="presentation">' + icon + '<div class="' + prefix + 'notification-inner">' + self.state.get('text') + '</div>' + progressBar + closeButton + '<div style="clip: rect(1px, 1px, 1px, 1px);height: 1px;overflow: hidden;position: absolute;width: 1px;"' + ' aria-live="assertive" aria-relevant="additions" aria-atomic="true"></div>' + '</div>';
      },
      postRender: function () {
        var self = this;
        global$7.setTimeout(function () {
          self.$el.addClass(self.classPrefix + 'in');
          updateLiveRegion(self, self.state.get('text'));
        }, 100);
        return self._super();
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:text', function (e) {
          self.getEl().firstChild.innerHTML = e.value;
          updateLiveRegion(self, e.value);
        });
        if (self.progressBar) {
          self.progressBar.bindStates();
          self.progressBar.state.on('change:value', function (e) {
            updateLiveRegion(self, self.state.get('text'));
          });
        }
        return self._super();
      },
      close: function () {
        var self = this;
        if (!self.fire('close').isDefaultPrevented()) {
          self.remove();
        }
        return self;
      },
      repaint: function () {
        var self = this;
        var style, rect;
        style = self.getEl().style;
        rect = self._layoutRect;
        style.left = rect.x + 'px';
        style.top = rect.y + 'px';
        style.zIndex = 65535 - 1;
      }
    });

    function NotificationManagerImpl (editor) {
      var getEditorContainer = function (editor) {
        return editor.inline ? editor.getElement() : editor.getContentAreaContainer();
      };
      var getContainerWidth = function () {
        var container = getEditorContainer(editor);
        return funcs.getSize(container).width;
      };
      var prePositionNotifications = function (notifications) {
        each(notifications, function (notification) {
          notification.moveTo(0, 0);
        });
      };
      var positionNotifications = function (notifications) {
        if (notifications.length > 0) {
          var firstItem = notifications.slice(0, 1)[0];
          var container = getEditorContainer(editor);
          firstItem.moveRel(container, 'tc-tc');
          each(notifications, function (notification, index) {
            if (index > 0) {
              notification.moveRel(notifications[index - 1].getEl(), 'bc-tc');
            }
          });
        }
      };
      var reposition = function (notifications) {
        prePositionNotifications(notifications);
        positionNotifications(notifications);
      };
      var open = function (args, closeCallback) {
        var extendedArgs = global$2.extend(args, { maxWidth: getContainerWidth() });
        var notif = new Notification(extendedArgs);
        notif.args = extendedArgs;
        if (extendedArgs.timeout > 0) {
          notif.timer = setTimeout(function () {
            notif.close();
            closeCallback();
          }, extendedArgs.timeout);
        }
        notif.on('close', function () {
          closeCallback();
        });
        notif.renderTo();
        return notif;
      };
      var close = function (notification) {
        notification.close();
      };
      var getArgs = function (notification) {
        return notification.args;
      };
      return {
        open: open,
        close: close,
        reposition: reposition,
        getArgs: getArgs
      };
    }

    var windows = [];
    var oldMetaValue = '';
    function toggleFullScreenState(state) {
      var noScaleMetaValue = 'width=device-width,initial-scale=1.0,user-scalable=0,minimum-scale=1.0,maximum-scale=1.0';
      var viewport = global$9('meta[name=viewport]')[0], contentValue;
      if (global$8.overrideViewPort === false) {
        return;
      }
      if (!viewport) {
        viewport = document.createElement('meta');
        viewport.setAttribute('name', 'viewport');
        document.getElementsByTagName('head')[0].appendChild(viewport);
      }
      contentValue = viewport.getAttribute('content');
      if (contentValue && typeof oldMetaValue !== 'undefined') {
        oldMetaValue = contentValue;
      }
      viewport.setAttribute('content', state ? noScaleMetaValue : oldMetaValue);
    }
    function toggleBodyFullScreenClasses(classPrefix, state) {
      if (checkFullscreenWindows() && state === false) {
        global$9([
          document.documentElement,
          document.body
        ]).removeClass(classPrefix + 'fullscreen');
      }
    }
    function checkFullscreenWindows() {
      for (var i = 0; i < windows.length; i++) {
        if (windows[i]._fullscreen) {
          return true;
        }
      }
      return false;
    }
    function handleWindowResize() {
      if (!global$8.desktop) {
        var lastSize_1 = {
          w: window.innerWidth,
          h: window.innerHeight
        };
        global$7.setInterval(function () {
          var w = window.innerWidth, h = window.innerHeight;
          if (lastSize_1.w !== w || lastSize_1.h !== h) {
            lastSize_1 = {
              w: w,
              h: h
            };
            global$9(window).trigger('resize');
          }
        }, 100);
      }
      function reposition() {
        var i;
        var rect = funcs.getWindowSize();
        var layoutRect;
        for (i = 0; i < windows.length; i++) {
          layoutRect = windows[i].layoutRect();
          windows[i].moveTo(windows[i].settings.x || Math.max(0, rect.w / 2 - layoutRect.w / 2), windows[i].settings.y || Math.max(0, rect.h / 2 - layoutRect.h / 2));
        }
      }
      global$9(window).on('resize', reposition);
    }
    var Window$$1 = FloatPanel.extend({
      modal: true,
      Defaults: {
        border: 1,
        layout: 'flex',
        containerCls: 'panel',
        role: 'dialog',
        callbacks: {
          submit: function () {
            this.fire('submit', { data: this.toJSON() });
          },
          close: function () {
            this.close();
          }
        }
      },
      init: function (settings) {
        var self$$1 = this;
        self$$1._super(settings);
        if (self$$1.isRtl()) {
          self$$1.classes.add('rtl');
        }
        self$$1.classes.add('window');
        self$$1.bodyClasses.add('window-body');
        self$$1.state.set('fixed', true);
        if (settings.buttons) {
          self$$1.statusbar = new Panel({
            layout: 'flex',
            border: '1 0 0 0',
            spacing: 3,
            padding: 10,
            align: 'center',
            pack: self$$1.isRtl() ? 'start' : 'end',
            defaults: { type: 'button' },
            items: settings.buttons
          });
          self$$1.statusbar.classes.add('foot');
          self$$1.statusbar.parent(self$$1);
        }
        self$$1.on('click', function (e) {
          var closeClass = self$$1.classPrefix + 'close';
          if (funcs.hasClass(e.target, closeClass) || funcs.hasClass(e.target.parentNode, closeClass)) {
            self$$1.close();
          }
        });
        self$$1.on('cancel', function () {
          self$$1.close();
        });
        self$$1.on('move', function (e) {
          if (e.control === self$$1) {
            FloatPanel.hideAll();
          }
        });
        self$$1.aria('describedby', self$$1.describedBy || self$$1._id + '-none');
        self$$1.aria('label', settings.title);
        self$$1._fullscreen = false;
      },
      recalc: function () {
        var self$$1 = this;
        var statusbar$$1 = self$$1.statusbar;
        var layoutRect, width, x, needsRecalc;
        if (self$$1._fullscreen) {
          self$$1.layoutRect(funcs.getWindowSize());
          self$$1.layoutRect().contentH = self$$1.layoutRect().innerH;
        }
        self$$1._super();
        layoutRect = self$$1.layoutRect();
        if (self$$1.settings.title && !self$$1._fullscreen) {
          width = layoutRect.headerW;
          if (width > layoutRect.w) {
            x = layoutRect.x - Math.max(0, width / 2);
            self$$1.layoutRect({
              w: width,
              x: x
            });
            needsRecalc = true;
          }
        }
        if (statusbar$$1) {
          statusbar$$1.layoutRect({ w: self$$1.layoutRect().innerW }).recalc();
          width = statusbar$$1.layoutRect().minW + layoutRect.deltaW;
          if (width > layoutRect.w) {
            x = layoutRect.x - Math.max(0, width - layoutRect.w);
            self$$1.layoutRect({
              w: width,
              x: x
            });
            needsRecalc = true;
          }
        }
        if (needsRecalc) {
          self$$1.recalc();
        }
      },
      initLayoutRect: function () {
        var self$$1 = this;
        var layoutRect = self$$1._super();
        var deltaH = 0, headEl;
        if (self$$1.settings.title && !self$$1._fullscreen) {
          headEl = self$$1.getEl('head');
          var size = funcs.getSize(headEl);
          layoutRect.headerW = size.width;
          layoutRect.headerH = size.height;
          deltaH += layoutRect.headerH;
        }
        if (self$$1.statusbar) {
          deltaH += self$$1.statusbar.layoutRect().h;
        }
        layoutRect.deltaH += deltaH;
        layoutRect.minH += deltaH;
        layoutRect.h += deltaH;
        var rect = funcs.getWindowSize();
        layoutRect.x = self$$1.settings.x || Math.max(0, rect.w / 2 - layoutRect.w / 2);
        layoutRect.y = self$$1.settings.y || Math.max(0, rect.h / 2 - layoutRect.h / 2);
        return layoutRect;
      },
      renderHtml: function () {
        var self$$1 = this, layout = self$$1._layout, id = self$$1._id, prefix = self$$1.classPrefix;
        var settings = self$$1.settings;
        var headerHtml = '', footerHtml = '', html = settings.html;
        self$$1.preRender();
        layout.preRender(self$$1);
        if (settings.title) {
          headerHtml = '<div id="' + id + '-head" class="' + prefix + 'window-head">' + '<div id="' + id + '-title" class="' + prefix + 'title">' + self$$1.encode(settings.title) + '</div>' + '<div id="' + id + '-dragh" class="' + prefix + 'dragh"></div>' + '<button type="button" class="' + prefix + 'close" aria-hidden="true">' + '<i class="mce-ico mce-i-remove"></i>' + '</button>' + '</div>';
        }
        if (settings.url) {
          html = '<iframe src="' + settings.url + '" tabindex="-1"></iframe>';
        }
        if (typeof html === 'undefined') {
          html = layout.renderHtml(self$$1);
        }
        if (self$$1.statusbar) {
          footerHtml = self$$1.statusbar.renderHtml();
        }
        return '<div id="' + id + '" class="' + self$$1.classes + '" hidefocus="1">' + '<div class="' + self$$1.classPrefix + 'reset" role="application">' + headerHtml + '<div id="' + id + '-body" class="' + self$$1.bodyClasses + '">' + html + '</div>' + footerHtml + '</div>' + '</div>';
      },
      fullscreen: function (state) {
        var self$$1 = this;
        var documentElement = document.documentElement;
        var slowRendering;
        var prefix = self$$1.classPrefix;
        var layoutRect;
        if (state !== self$$1._fullscreen) {
          global$9(window).on('resize', function () {
            var time;
            if (self$$1._fullscreen) {
              if (!slowRendering) {
                time = new Date().getTime();
                var rect = funcs.getWindowSize();
                self$$1.moveTo(0, 0).resizeTo(rect.w, rect.h);
                if (new Date().getTime() - time > 50) {
                  slowRendering = true;
                }
              } else {
                if (!self$$1._timer) {
                  self$$1._timer = global$7.setTimeout(function () {
                    var rect = funcs.getWindowSize();
                    self$$1.moveTo(0, 0).resizeTo(rect.w, rect.h);
                    self$$1._timer = 0;
                  }, 50);
                }
              }
            }
          });
          layoutRect = self$$1.layoutRect();
          self$$1._fullscreen = state;
          if (!state) {
            self$$1.borderBox = BoxUtils.parseBox(self$$1.settings.border);
            self$$1.getEl('head').style.display = '';
            layoutRect.deltaH += layoutRect.headerH;
            global$9([
              documentElement,
              document.body
            ]).removeClass(prefix + 'fullscreen');
            self$$1.classes.remove('fullscreen');
            self$$1.moveTo(self$$1._initial.x, self$$1._initial.y).resizeTo(self$$1._initial.w, self$$1._initial.h);
          } else {
            self$$1._initial = {
              x: layoutRect.x,
              y: layoutRect.y,
              w: layoutRect.w,
              h: layoutRect.h
            };
            self$$1.borderBox = BoxUtils.parseBox('0');
            self$$1.getEl('head').style.display = 'none';
            layoutRect.deltaH -= layoutRect.headerH + 2;
            global$9([
              documentElement,
              document.body
            ]).addClass(prefix + 'fullscreen');
            self$$1.classes.add('fullscreen');
            var rect = funcs.getWindowSize();
            self$$1.moveTo(0, 0).resizeTo(rect.w, rect.h);
          }
        }
        return self$$1.reflow();
      },
      postRender: function () {
        var self$$1 = this;
        var startPos;
        setTimeout(function () {
          self$$1.classes.add('in');
          self$$1.fire('open');
        }, 0);
        self$$1._super();
        if (self$$1.statusbar) {
          self$$1.statusbar.postRender();
        }
        self$$1.focus();
        this.dragHelper = new DragHelper(self$$1._id + '-dragh', {
          start: function () {
            startPos = {
              x: self$$1.layoutRect().x,
              y: self$$1.layoutRect().y
            };
          },
          drag: function (e) {
            self$$1.moveTo(startPos.x + e.deltaX, startPos.y + e.deltaY);
          }
        });
        self$$1.on('submit', function (e) {
          if (!e.isDefaultPrevented()) {
            self$$1.close();
          }
        });
        windows.push(self$$1);
        toggleFullScreenState(true);
      },
      submit: function () {
        return this.fire('submit', { data: this.toJSON() });
      },
      remove: function () {
        var self$$1 = this;
        var i;
        self$$1.dragHelper.destroy();
        self$$1._super();
        if (self$$1.statusbar) {
          this.statusbar.remove();
        }
        toggleBodyFullScreenClasses(self$$1.classPrefix, false);
        i = windows.length;
        while (i--) {
          if (windows[i] === self$$1) {
            windows.splice(i, 1);
          }
        }
        toggleFullScreenState(windows.length > 0);
      },
      getContentWindow: function () {
        var ifr = this.getEl().getElementsByTagName('iframe')[0];
        return ifr ? ifr.contentWindow : null;
      }
    });
    handleWindowResize();

    var MessageBox = Window$$1.extend({
      init: function (settings) {
        settings = {
          border: 1,
          padding: 20,
          layout: 'flex',
          pack: 'center',
          align: 'center',
          containerCls: 'panel',
          autoScroll: true,
          buttons: {
            type: 'button',
            text: 'Ok',
            action: 'ok'
          },
          items: {
            type: 'label',
            multiline: true,
            maxWidth: 500,
            maxHeight: 200
          }
        };
        this._super(settings);
      },
      Statics: {
        OK: 1,
        OK_CANCEL: 2,
        YES_NO: 3,
        YES_NO_CANCEL: 4,
        msgBox: function (settings) {
          var buttons;
          var callback = settings.callback || function () {
          };
          function createButton(text, status$$1, primary) {
            return {
              type: 'button',
              text: text,
              subtype: primary ? 'primary' : '',
              onClick: function (e) {
                e.control.parents()[1].close();
                callback(status$$1);
              }
            };
          }
          switch (settings.buttons) {
          case MessageBox.OK_CANCEL:
            buttons = [
              createButton('Ok', true, true),
              createButton('Cancel', false)
            ];
            break;
          case MessageBox.YES_NO:
          case MessageBox.YES_NO_CANCEL:
            buttons = [
              createButton('Yes', 1, true),
              createButton('No', 0)
            ];
            if (settings.buttons === MessageBox.YES_NO_CANCEL) {
              buttons.push(createButton('Cancel', -1));
            }
            break;
          default:
            buttons = [createButton('Ok', true, true)];
            break;
          }
          return new Window$$1({
            padding: 20,
            x: settings.x,
            y: settings.y,
            minWidth: 300,
            minHeight: 100,
            layout: 'flex',
            pack: 'center',
            align: 'center',
            buttons: buttons,
            title: settings.title,
            role: 'alertdialog',
            items: {
              type: 'label',
              multiline: true,
              maxWidth: 500,
              maxHeight: 200,
              text: settings.text
            },
            onPostRender: function () {
              this.aria('describedby', this.items()[0]._id);
            },
            onClose: settings.onClose,
            onCancel: function () {
              callback(false);
            }
          }).renderTo(document.body).reflow();
        },
        alert: function (settings, callback) {
          if (typeof settings === 'string') {
            settings = { text: settings };
          }
          settings.callback = callback;
          return MessageBox.msgBox(settings);
        },
        confirm: function (settings, callback) {
          if (typeof settings === 'string') {
            settings = { text: settings };
          }
          settings.callback = callback;
          settings.buttons = MessageBox.OK_CANCEL;
          return MessageBox.msgBox(settings);
        }
      }
    });

    function WindowManagerImpl (editor) {
      var open$$1 = function (args, params, closeCallback) {
        var win;
        args.title = args.title || ' ';
        args.url = args.url || args.file;
        if (args.url) {
          args.width = parseInt(args.width || 320, 10);
          args.height = parseInt(args.height || 240, 10);
        }
        if (args.body) {
          args.items = {
            defaults: args.defaults,
            type: args.bodyType || 'form',
            items: args.body,
            data: args.data,
            callbacks: args.commands
          };
        }
        if (!args.url && !args.buttons) {
          args.buttons = [
            {
              text: 'Ok',
              subtype: 'primary',
              onclick: function () {
                win.find('form')[0].submit();
              }
            },
            {
              text: 'Cancel',
              onclick: function () {
                win.close();
              }
            }
          ];
        }
        win = new Window$$1(args);
        win.on('close', function () {
          closeCallback(win);
        });
        if (args.data) {
          win.on('postRender', function () {
            this.find('*').each(function (ctrl) {
              var name$$1 = ctrl.name();
              if (name$$1 in args.data) {
                ctrl.value(args.data[name$$1]);
              }
            });
          });
        }
        win.features = args || {};
        win.params = params || {};
        win = win.renderTo(document.body).reflow();
        return win;
      };
      var alert$$1 = function (message, choiceCallback, closeCallback) {
        var win;
        win = MessageBox.alert(message, function () {
          choiceCallback();
        });
        win.on('close', function () {
          closeCallback(win);
        });
        return win;
      };
      var confirm$$1 = function (message, choiceCallback, closeCallback) {
        var win;
        win = MessageBox.confirm(message, function (state) {
          choiceCallback(state);
        });
        win.on('close', function () {
          closeCallback(win);
        });
        return win;
      };
      var close$$1 = function (window$$1) {
        window$$1.close();
      };
      var getParams = function (window$$1) {
        return window$$1.params;
      };
      var setParams = function (window$$1, params) {
        window$$1.params = params;
      };
      return {
        open: open$$1,
        alert: alert$$1,
        confirm: confirm$$1,
        close: close$$1,
        getParams: getParams,
        setParams: setParams
      };
    }

    var get = function (editor) {
      var renderUI = function (args) {
        return Render.renderUI(editor, this, args);
      };
      var resizeTo = function (w, h) {
        return Resize.resizeTo(editor, w, h);
      };
      var resizeBy = function (dw, dh) {
        return Resize.resizeBy(editor, dw, dh);
      };
      var getNotificationManagerImpl = function () {
        return NotificationManagerImpl(editor);
      };
      var getWindowManagerImpl = function () {
        return WindowManagerImpl(editor);
      };
      return {
        renderUI: renderUI,
        resizeTo: resizeTo,
        resizeBy: resizeBy,
        getNotificationManagerImpl: getNotificationManagerImpl,
        getWindowManagerImpl: getWindowManagerImpl
      };
    };
    var ThemeApi = { get: get };

    var Layout = global$a.extend({
      Defaults: {
        firstControlClass: 'first',
        lastControlClass: 'last'
      },
      init: function (settings) {
        this.settings = global$2.extend({}, this.Defaults, settings);
      },
      preRender: function (container) {
        container.bodyClasses.add(this.settings.containerClass);
      },
      applyClasses: function (items) {
        var self = this;
        var settings = self.settings;
        var firstClass, lastClass, firstItem, lastItem;
        firstClass = settings.firstControlClass;
        lastClass = settings.lastControlClass;
        items.each(function (item) {
          item.classes.remove(firstClass).remove(lastClass).add(settings.controlClass);
          if (item.visible()) {
            if (!firstItem) {
              firstItem = item;
            }
            lastItem = item;
          }
        });
        if (firstItem) {
          firstItem.classes.add(firstClass);
        }
        if (lastItem) {
          lastItem.classes.add(lastClass);
        }
      },
      renderHtml: function (container) {
        var self = this;
        var html = '';
        self.applyClasses(container.items());
        container.items().each(function (item) {
          html += item.renderHtml();
        });
        return html;
      },
      recalc: function () {
      },
      postRender: function () {
      },
      isNative: function () {
        return false;
      }
    });

    var AbsoluteLayout = Layout.extend({
      Defaults: {
        containerClass: 'abs-layout',
        controlClass: 'abs-layout-item'
      },
      recalc: function (container) {
        container.items().filter(':visible').each(function (ctrl) {
          var settings = ctrl.settings;
          ctrl.layoutRect({
            x: settings.x,
            y: settings.y,
            w: settings.w,
            h: settings.h
          });
          if (ctrl.recalc) {
            ctrl.recalc();
          }
        });
      },
      renderHtml: function (container) {
        return '<div id="' + container._id + '-absend" class="' + container.classPrefix + 'abs-end"></div>' + this._super(container);
      }
    });

    var Button = Widget.extend({
      Defaults: {
        classes: 'widget btn',
        role: 'button'
      },
      init: function (settings) {
        var self$$1 = this;
        var size;
        self$$1._super(settings);
        settings = self$$1.settings;
        size = self$$1.settings.size;
        self$$1.on('click mousedown', function (e) {
          e.preventDefault();
        });
        self$$1.on('touchstart', function (e) {
          self$$1.fire('click', e);
          e.preventDefault();
        });
        if (settings.subtype) {
          self$$1.classes.add(settings.subtype);
        }
        if (size) {
          self$$1.classes.add('btn-' + size);
        }
        if (settings.icon) {
          self$$1.icon(settings.icon);
        }
      },
      icon: function (icon) {
        if (!arguments.length) {
          return this.state.get('icon');
        }
        this.state.set('icon', icon);
        return this;
      },
      repaint: function () {
        var btnElm = this.getEl().firstChild;
        var btnStyle;
        if (btnElm) {
          btnStyle = btnElm.style;
          btnStyle.width = btnStyle.height = '100%';
        }
        this._super();
      },
      renderHtml: function () {
        var self$$1 = this, id = self$$1._id, prefix = self$$1.classPrefix;
        var icon = self$$1.state.get('icon'), image;
        var text = self$$1.state.get('text');
        var textHtml = '';
        var ariaPressed;
        var settings = self$$1.settings;
        image = settings.image;
        if (image) {
          icon = 'none';
          if (typeof image !== 'string') {
            image = window.getSelection ? image[0] : image[1];
          }
          image = ' style="background-image: url(\'' + image + '\')"';
        } else {
          image = '';
        }
        if (text) {
          self$$1.classes.add('btn-has-text');
          textHtml = '<span class="' + prefix + 'txt">' + self$$1.encode(text) + '</span>';
        }
        icon = icon ? prefix + 'ico ' + prefix + 'i-' + icon : '';
        ariaPressed = typeof settings.active === 'boolean' ? ' aria-pressed="' + settings.active + '"' : '';
        return '<div id="' + id + '" class="' + self$$1.classes + '" tabindex="-1"' + ariaPressed + '>' + '<button id="' + id + '-button" role="presentation" type="button" tabindex="-1">' + (icon ? '<i class="' + icon + '"' + image + '></i>' : '') + textHtml + '</button>' + '</div>';
      },
      bindStates: function () {
        var self$$1 = this, $ = self$$1.$, textCls = self$$1.classPrefix + 'txt';
        function setButtonText(text) {
          var $span = $('span.' + textCls, self$$1.getEl());
          if (text) {
            if (!$span[0]) {
              $('button:first', self$$1.getEl()).append('<span class="' + textCls + '"></span>');
              $span = $('span.' + textCls, self$$1.getEl());
            }
            $span.html(self$$1.encode(text));
          } else {
            $span.remove();
          }
          self$$1.classes.toggle('btn-has-text', !!text);
        }
        self$$1.state.on('change:text', function (e) {
          setButtonText(e.value);
        });
        self$$1.state.on('change:icon', function (e) {
          var icon = e.value;
          var prefix = self$$1.classPrefix;
          self$$1.settings.icon = icon;
          icon = icon ? prefix + 'ico ' + prefix + 'i-' + self$$1.settings.icon : '';
          var btnElm = self$$1.getEl().firstChild;
          var iconElm = btnElm.getElementsByTagName('i')[0];
          if (icon) {
            if (!iconElm || iconElm !== btnElm.firstChild) {
              iconElm = document.createElement('i');
              btnElm.insertBefore(iconElm, btnElm.firstChild);
            }
            iconElm.className = icon;
          } else if (iconElm) {
            btnElm.removeChild(iconElm);
          }
          setButtonText(self$$1.state.get('text'));
        });
        return self$$1._super();
      }
    });

    var BrowseButton = Button.extend({
      init: function (settings) {
        var self = this;
        settings = global$2.extend({
          text: 'Browse...',
          multiple: false,
          accept: null
        }, settings);
        self._super(settings);
        self.classes.add('browsebutton');
        if (settings.multiple) {
          self.classes.add('multiple');
        }
      },
      postRender: function () {
        var self = this;
        var input = funcs.create('input', {
          type: 'file',
          id: self._id + '-browse',
          accept: self.settings.accept
        });
        self._super();
        global$9(input).on('change', function (e) {
          var files = e.target.files;
          self.value = function () {
            if (!files.length) {
              return null;
            } else if (self.settings.multiple) {
              return files;
            } else {
              return files[0];
            }
          };
          e.preventDefault();
          if (files.length) {
            self.fire('change', e);
          }
        });
        global$9(input).on('click', function (e) {
          e.stopPropagation();
        });
        global$9(self.getEl('button')).on('click', function (e) {
          e.stopPropagation();
          input.click();
        });
        self.getEl().appendChild(input);
      },
      remove: function () {
        global$9(this.getEl('button')).off();
        global$9(this.getEl('input')).off();
        this._super();
      }
    });

    var ButtonGroup = Container.extend({
      Defaults: {
        defaultType: 'button',
        role: 'group'
      },
      renderHtml: function () {
        var self = this, layout = self._layout;
        self.classes.add('btn-group');
        self.preRender();
        layout.preRender(self);
        return '<div id="' + self._id + '" class="' + self.classes + '">' + '<div id="' + self._id + '-body">' + (self.settings.html || '') + layout.renderHtml(self) + '</div>' + '</div>';
      }
    });

    var Checkbox = Widget.extend({
      Defaults: {
        classes: 'checkbox',
        role: 'checkbox',
        checked: false
      },
      init: function (settings) {
        var self$$1 = this;
        self$$1._super(settings);
        self$$1.on('click mousedown', function (e) {
          e.preventDefault();
        });
        self$$1.on('click', function (e) {
          e.preventDefault();
          if (!self$$1.disabled()) {
            self$$1.checked(!self$$1.checked());
          }
        });
        self$$1.checked(self$$1.settings.checked);
      },
      checked: function (state) {
        if (!arguments.length) {
          return this.state.get('checked');
        }
        this.state.set('checked', state);
        return this;
      },
      value: function (state) {
        if (!arguments.length) {
          return this.checked();
        }
        return this.checked(state);
      },
      renderHtml: function () {
        var self$$1 = this, id = self$$1._id, prefix = self$$1.classPrefix;
        return '<div id="' + id + '" class="' + self$$1.classes + '" unselectable="on" aria-labelledby="' + id + '-al" tabindex="-1">' + '<i class="' + prefix + 'ico ' + prefix + 'i-checkbox"></i>' + '<span id="' + id + '-al" class="' + prefix + 'label">' + self$$1.encode(self$$1.state.get('text')) + '</span>' + '</div>';
      },
      bindStates: function () {
        var self$$1 = this;
        function checked(state) {
          self$$1.classes.toggle('checked', state);
          self$$1.aria('checked', state);
        }
        self$$1.state.on('change:text', function (e) {
          self$$1.getEl('al').firstChild.data = self$$1.translate(e.value);
        });
        self$$1.state.on('change:checked change:value', function (e) {
          self$$1.fire('change');
          checked(e.value);
        });
        self$$1.state.on('change:icon', function (e) {
          var icon = e.value;
          var prefix = self$$1.classPrefix;
          if (typeof icon === 'undefined') {
            return self$$1.settings.icon;
          }
          self$$1.settings.icon = icon;
          icon = icon ? prefix + 'ico ' + prefix + 'i-' + self$$1.settings.icon : '';
          var btnElm = self$$1.getEl().firstChild;
          var iconElm = btnElm.getElementsByTagName('i')[0];
          if (icon) {
            if (!iconElm || iconElm !== btnElm.firstChild) {
              iconElm = document.createElement('i');
              btnElm.insertBefore(iconElm, btnElm.firstChild);
            }
            iconElm.className = icon;
          } else if (iconElm) {
            btnElm.removeChild(iconElm);
          }
        });
        if (self$$1.state.get('checked')) {
          checked(true);
        }
        return self$$1._super();
      }
    });

    var global$d = tinymce.util.Tools.resolve('tinymce.util.VK');

    var ComboBox = Widget.extend({
      init: function (settings) {
        var self$$1 = this;
        self$$1._super(settings);
        settings = self$$1.settings;
        self$$1.classes.add('combobox');
        self$$1.subinput = true;
        self$$1.ariaTarget = 'inp';
        settings.menu = settings.menu || settings.values;
        if (settings.menu) {
          settings.icon = 'caret';
        }
        self$$1.on('click', function (e) {
          var elm = e.target;
          var root = self$$1.getEl();
          if (!global$9.contains(root, elm) && elm !== root) {
            return;
          }
          while (elm && elm !== root) {
            if (elm.id && elm.id.indexOf('-open') !== -1) {
              self$$1.fire('action');
              if (settings.menu) {
                self$$1.showMenu();
                if (e.aria) {
                  self$$1.menu.items()[0].focus();
                }
              }
            }
            elm = elm.parentNode;
          }
        });
        self$$1.on('keydown', function (e) {
          var rootControl;
          if (e.keyCode === 13 && e.target.nodeName === 'INPUT') {
            e.preventDefault();
            self$$1.parents().reverse().each(function (ctrl) {
              if (ctrl.toJSON) {
                rootControl = ctrl;
                return false;
              }
            });
            self$$1.fire('submit', { data: rootControl.toJSON() });
          }
        });
        self$$1.on('keyup', function (e) {
          if (e.target.nodeName === 'INPUT') {
            var oldValue = self$$1.state.get('value');
            var newValue = e.target.value;
            if (newValue !== oldValue) {
              self$$1.state.set('value', newValue);
              self$$1.fire('autocomplete', e);
            }
          }
        });
        self$$1.on('mouseover', function (e) {
          var tooltip = self$$1.tooltip().moveTo(-65535);
          if (self$$1.statusLevel() && e.target.className.indexOf(self$$1.classPrefix + 'status') !== -1) {
            var statusMessage = self$$1.statusMessage() || 'Ok';
            var rel = tooltip.text(statusMessage).show().testMoveRel(e.target, [
              'bc-tc',
              'bc-tl',
              'bc-tr'
            ]);
            tooltip.classes.toggle('tooltip-n', rel === 'bc-tc');
            tooltip.classes.toggle('tooltip-nw', rel === 'bc-tl');
            tooltip.classes.toggle('tooltip-ne', rel === 'bc-tr');
            tooltip.moveRel(e.target, rel);
          }
        });
      },
      statusLevel: function (value) {
        if (arguments.length > 0) {
          this.state.set('statusLevel', value);
        }
        return this.state.get('statusLevel');
      },
      statusMessage: function (value) {
        if (arguments.length > 0) {
          this.state.set('statusMessage', value);
        }
        return this.state.get('statusMessage');
      },
      showMenu: function () {
        var self$$1 = this;
        var settings = self$$1.settings;
        var menu;
        if (!self$$1.menu) {
          menu = settings.menu || [];
          if (menu.length) {
            menu = {
              type: 'menu',
              items: menu
            };
          } else {
            menu.type = menu.type || 'menu';
          }
          self$$1.menu = global$4.create(menu).parent(self$$1).renderTo(self$$1.getContainerElm());
          self$$1.fire('createmenu');
          self$$1.menu.reflow();
          self$$1.menu.on('cancel', function (e) {
            if (e.control === self$$1.menu) {
              self$$1.focus();
            }
          });
          self$$1.menu.on('show hide', function (e) {
            e.control.items().each(function (ctrl) {
              ctrl.active(ctrl.value() === self$$1.value());
            });
          }).fire('show');
          self$$1.menu.on('select', function (e) {
            self$$1.value(e.control.value());
          });
          self$$1.on('focusin', function (e) {
            if (e.target.tagName.toUpperCase() === 'INPUT') {
              self$$1.menu.hide();
            }
          });
          self$$1.aria('expanded', true);
        }
        self$$1.menu.show();
        self$$1.menu.layoutRect({ w: self$$1.layoutRect().w });
        self$$1.menu.moveRel(self$$1.getEl(), self$$1.isRtl() ? [
          'br-tr',
          'tr-br'
        ] : [
          'bl-tl',
          'tl-bl'
        ]);
      },
      focus: function () {
        this.getEl('inp').focus();
      },
      repaint: function () {
        var self$$1 = this, elm = self$$1.getEl(), openElm = self$$1.getEl('open'), rect = self$$1.layoutRect();
        var width, lineHeight, innerPadding = 0;
        var inputElm = elm.firstChild;
        if (self$$1.statusLevel() && self$$1.statusLevel() !== 'none') {
          innerPadding = parseInt(funcs.getRuntimeStyle(inputElm, 'padding-right'), 10) - parseInt(funcs.getRuntimeStyle(inputElm, 'padding-left'), 10);
        }
        if (openElm) {
          width = rect.w - funcs.getSize(openElm).width - 10;
        } else {
          width = rect.w - 10;
        }
        var doc = document;
        if (doc.all && (!doc.documentMode || doc.documentMode <= 8)) {
          lineHeight = self$$1.layoutRect().h - 2 + 'px';
        }
        global$9(inputElm).css({
          width: width - innerPadding,
          lineHeight: lineHeight
        });
        self$$1._super();
        return self$$1;
      },
      postRender: function () {
        var self$$1 = this;
        global$9(this.getEl('inp')).on('change', function (e) {
          self$$1.state.set('value', e.target.value);
          self$$1.fire('change', e);
        });
        return self$$1._super();
      },
      renderHtml: function () {
        var self$$1 = this, id = self$$1._id, settings = self$$1.settings, prefix = self$$1.classPrefix;
        var value = self$$1.state.get('value') || '';
        var icon, text, openBtnHtml = '', extraAttrs = '', statusHtml = '';
        if ('spellcheck' in settings) {
          extraAttrs += ' spellcheck="' + settings.spellcheck + '"';
        }
        if (settings.maxLength) {
          extraAttrs += ' maxlength="' + settings.maxLength + '"';
        }
        if (settings.size) {
          extraAttrs += ' size="' + settings.size + '"';
        }
        if (settings.subtype) {
          extraAttrs += ' type="' + settings.subtype + '"';
        }
        statusHtml = '<i id="' + id + '-status" class="mce-status mce-ico" style="display: none"></i>';
        if (self$$1.disabled()) {
          extraAttrs += ' disabled="disabled"';
        }
        icon = settings.icon;
        if (icon && icon !== 'caret') {
          icon = prefix + 'ico ' + prefix + 'i-' + settings.icon;
        }
        text = self$$1.state.get('text');
        if (icon || text) {
          openBtnHtml = '<div id="' + id + '-open" class="' + prefix + 'btn ' + prefix + 'open" tabIndex="-1" role="button">' + '<button id="' + id + '-action" type="button" hidefocus="1" tabindex="-1">' + (icon !== 'caret' ? '<i class="' + icon + '"></i>' : '<i class="' + prefix + 'caret"></i>') + (text ? (icon ? ' ' : '') + text : '') + '</button>' + '</div>';
          self$$1.classes.add('has-open');
        }
        return '<div id="' + id + '" class="' + self$$1.classes + '">' + '<input id="' + id + '-inp" class="' + prefix + 'textbox" value="' + self$$1.encode(value, false) + '" hidefocus="1"' + extraAttrs + ' placeholder="' + self$$1.encode(settings.placeholder) + '" />' + statusHtml + openBtnHtml + '</div>';
      },
      value: function (value) {
        if (arguments.length) {
          this.state.set('value', value);
          return this;
        }
        if (this.state.get('rendered')) {
          this.state.set('value', this.getEl('inp').value);
        }
        return this.state.get('value');
      },
      showAutoComplete: function (items, term) {
        var self$$1 = this;
        if (items.length === 0) {
          self$$1.hideMenu();
          return;
        }
        var insert = function (value, title) {
          return function () {
            self$$1.fire('selectitem', {
              title: title,
              value: value
            });
          };
        };
        if (self$$1.menu) {
          self$$1.menu.items().remove();
        } else {
          self$$1.menu = global$4.create({
            type: 'menu',
            classes: 'combobox-menu',
            layout: 'flow'
          }).parent(self$$1).renderTo();
        }
        global$2.each(items, function (item) {
          self$$1.menu.add({
            text: item.title,
            url: item.previewUrl,
            match: term,
            classes: 'menu-item-ellipsis',
            onclick: insert(item.value, item.title)
          });
        });
        self$$1.menu.renderNew();
        self$$1.hideMenu();
        self$$1.menu.on('cancel', function (e) {
          if (e.control.parent() === self$$1.menu) {
            e.stopPropagation();
            self$$1.focus();
            self$$1.hideMenu();
          }
        });
        self$$1.menu.on('select', function () {
          self$$1.focus();
        });
        var maxW = self$$1.layoutRect().w;
        self$$1.menu.layoutRect({
          w: maxW,
          minW: 0,
          maxW: maxW
        });
        self$$1.menu.repaint();
        self$$1.menu.reflow();
        self$$1.menu.show();
        self$$1.menu.moveRel(self$$1.getEl(), self$$1.isRtl() ? [
          'br-tr',
          'tr-br'
        ] : [
          'bl-tl',
          'tl-bl'
        ]);
      },
      hideMenu: function () {
        if (this.menu) {
          this.menu.hide();
        }
      },
      bindStates: function () {
        var self$$1 = this;
        self$$1.state.on('change:value', function (e) {
          if (self$$1.getEl('inp').value !== e.value) {
            self$$1.getEl('inp').value = e.value;
          }
        });
        self$$1.state.on('change:disabled', function (e) {
          self$$1.getEl('inp').disabled = e.value;
        });
        self$$1.state.on('change:statusLevel', function (e) {
          var statusIconElm = self$$1.getEl('status');
          var prefix = self$$1.classPrefix, value = e.value;
          funcs.css(statusIconElm, 'display', value === 'none' ? 'none' : '');
          funcs.toggleClass(statusIconElm, prefix + 'i-checkmark', value === 'ok');
          funcs.toggleClass(statusIconElm, prefix + 'i-warning', value === 'warn');
          funcs.toggleClass(statusIconElm, prefix + 'i-error', value === 'error');
          self$$1.classes.toggle('has-status', value !== 'none');
          self$$1.repaint();
        });
        funcs.on(self$$1.getEl('status'), 'mouseleave', function () {
          self$$1.tooltip().hide();
        });
        self$$1.on('cancel', function (e) {
          if (self$$1.menu && self$$1.menu.visible()) {
            e.stopPropagation();
            self$$1.hideMenu();
          }
        });
        var focusIdx = function (idx, menu) {
          if (menu && menu.items().length > 0) {
            menu.items().eq(idx)[0].focus();
          }
        };
        self$$1.on('keydown', function (e) {
          var keyCode = e.keyCode;
          if (e.target.nodeName === 'INPUT') {
            if (keyCode === global$d.DOWN) {
              e.preventDefault();
              self$$1.fire('autocomplete');
              focusIdx(0, self$$1.menu);
            } else if (keyCode === global$d.UP) {
              e.preventDefault();
              focusIdx(-1, self$$1.menu);
            }
          }
        });
        return self$$1._super();
      },
      remove: function () {
        global$9(this.getEl('inp')).off();
        if (this.menu) {
          this.menu.remove();
        }
        this._super();
      }
    });

    var ColorBox = ComboBox.extend({
      init: function (settings) {
        var self = this;
        settings.spellcheck = false;
        if (settings.onaction) {
          settings.icon = 'none';
        }
        self._super(settings);
        self.classes.add('colorbox');
        self.on('change keyup postrender', function () {
          self.repaintColor(self.value());
        });
      },
      repaintColor: function (value) {
        var openElm = this.getEl('open');
        var elm = openElm ? openElm.getElementsByTagName('i')[0] : null;
        if (elm) {
          try {
            elm.style.background = value;
          } catch (ex) {
          }
        }
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:value', function (e) {
          if (self.state.get('rendered')) {
            self.repaintColor(e.value);
          }
        });
        return self._super();
      }
    });

    var PanelButton = Button.extend({
      showPanel: function () {
        var self = this, settings = self.settings;
        self.classes.add('opened');
        if (!self.panel) {
          var panelSettings = settings.panel;
          if (panelSettings.type) {
            panelSettings = {
              layout: 'grid',
              items: panelSettings
            };
          }
          panelSettings.role = panelSettings.role || 'dialog';
          panelSettings.popover = true;
          panelSettings.autohide = true;
          panelSettings.ariaRoot = true;
          self.panel = new FloatPanel(panelSettings).on('hide', function () {
            self.classes.remove('opened');
          }).on('cancel', function (e) {
            e.stopPropagation();
            self.focus();
            self.hidePanel();
          }).parent(self).renderTo(self.getContainerElm());
          self.panel.fire('show');
          self.panel.reflow();
        } else {
          self.panel.show();
        }
        var rtlRels = [
          'bc-tc',
          'bc-tl',
          'bc-tr'
        ];
        var ltrRels = [
          'bc-tc',
          'bc-tr',
          'bc-tl',
          'tc-bc',
          'tc-br',
          'tc-bl'
        ];
        var rel = self.panel.testMoveRel(self.getEl(), settings.popoverAlign || (self.isRtl() ? rtlRels : ltrRels));
        self.panel.classes.toggle('start', rel.substr(-1) === 'l');
        self.panel.classes.toggle('end', rel.substr(-1) === 'r');
        var isTop = rel.substr(0, 1) === 't';
        self.panel.classes.toggle('bottom', !isTop);
        self.panel.classes.toggle('top', isTop);
        self.panel.moveRel(self.getEl(), rel);
      },
      hidePanel: function () {
        var self = this;
        if (self.panel) {
          self.panel.hide();
        }
      },
      postRender: function () {
        var self = this;
        self.aria('haspopup', true);
        self.on('click', function (e) {
          if (e.control === self) {
            if (self.panel && self.panel.visible()) {
              self.hidePanel();
            } else {
              self.showPanel();
              self.panel.focus(!!e.aria);
            }
          }
        });
        return self._super();
      },
      remove: function () {
        if (this.panel) {
          this.panel.remove();
          this.panel = null;
        }
        return this._super();
      }
    });

    var DOM$3 = global$3.DOM;
    var ColorButton = PanelButton.extend({
      init: function (settings) {
        this._super(settings);
        this.classes.add('splitbtn');
        this.classes.add('colorbutton');
      },
      color: function (color) {
        if (color) {
          this._color = color;
          this.getEl('preview').style.backgroundColor = color;
          return this;
        }
        return this._color;
      },
      resetColor: function () {
        this._color = null;
        this.getEl('preview').style.backgroundColor = null;
        return this;
      },
      renderHtml: function () {
        var self = this, id = self._id, prefix = self.classPrefix, text = self.state.get('text');
        var icon = self.settings.icon ? prefix + 'ico ' + prefix + 'i-' + self.settings.icon : '';
        var image = self.settings.image ? ' style="background-image: url(\'' + self.settings.image + '\')"' : '';
        var textHtml = '';
        if (text) {
          self.classes.add('btn-has-text');
          textHtml = '<span class="' + prefix + 'txt">' + self.encode(text) + '</span>';
        }
        return '<div id="' + id + '" class="' + self.classes + '" role="button" tabindex="-1" aria-haspopup="true">' + '<button role="presentation" hidefocus="1" type="button" tabindex="-1">' + (icon ? '<i class="' + icon + '"' + image + '></i>' : '') + '<span id="' + id + '-preview" class="' + prefix + 'preview"></span>' + textHtml + '</button>' + '<button type="button" class="' + prefix + 'open" hidefocus="1" tabindex="-1">' + ' <i class="' + prefix + 'caret"></i>' + '</button>' + '</div>';
      },
      postRender: function () {
        var self = this, onClickHandler = self.settings.onclick;
        self.on('click', function (e) {
          if (e.aria && e.aria.key === 'down') {
            return;
          }
          if (e.control === self && !DOM$3.getParent(e.target, '.' + self.classPrefix + 'open')) {
            e.stopImmediatePropagation();
            onClickHandler.call(self, e);
          }
        });
        delete self.settings.onclick;
        return self._super();
      }
    });

    var global$e = tinymce.util.Tools.resolve('tinymce.util.Color');

    var ColorPicker = Widget.extend({
      Defaults: { classes: 'widget colorpicker' },
      init: function (settings) {
        this._super(settings);
      },
      postRender: function () {
        var self = this;
        var color = self.color();
        var hsv, hueRootElm, huePointElm, svRootElm, svPointElm;
        hueRootElm = self.getEl('h');
        huePointElm = self.getEl('hp');
        svRootElm = self.getEl('sv');
        svPointElm = self.getEl('svp');
        function getPos(elm, event) {
          var pos = funcs.getPos(elm);
          var x, y;
          x = event.pageX - pos.x;
          y = event.pageY - pos.y;
          x = Math.max(0, Math.min(x / elm.clientWidth, 1));
          y = Math.max(0, Math.min(y / elm.clientHeight, 1));
          return {
            x: x,
            y: y
          };
        }
        function updateColor(hsv, hueUpdate) {
          var hue = (360 - hsv.h) / 360;
          funcs.css(huePointElm, { top: hue * 100 + '%' });
          if (!hueUpdate) {
            funcs.css(svPointElm, {
              left: hsv.s + '%',
              top: 100 - hsv.v + '%'
            });
          }
          svRootElm.style.background = global$e({
            s: 100,
            v: 100,
            h: hsv.h
          }).toHex();
          self.color().parse({
            s: hsv.s,
            v: hsv.v,
            h: hsv.h
          });
        }
        function updateSaturationAndValue(e) {
          var pos;
          pos = getPos(svRootElm, e);
          hsv.s = pos.x * 100;
          hsv.v = (1 - pos.y) * 100;
          updateColor(hsv);
          self.fire('change');
        }
        function updateHue(e) {
          var pos;
          pos = getPos(hueRootElm, e);
          hsv = color.toHsv();
          hsv.h = (1 - pos.y) * 360;
          updateColor(hsv, true);
          self.fire('change');
        }
        self._repaint = function () {
          hsv = color.toHsv();
          updateColor(hsv);
        };
        self._super();
        self._svdraghelper = new DragHelper(self._id + '-sv', {
          start: updateSaturationAndValue,
          drag: updateSaturationAndValue
        });
        self._hdraghelper = new DragHelper(self._id + '-h', {
          start: updateHue,
          drag: updateHue
        });
        self._repaint();
      },
      rgb: function () {
        return this.color().toRgb();
      },
      value: function (value) {
        var self = this;
        if (arguments.length) {
          self.color().parse(value);
          if (self._rendered) {
            self._repaint();
          }
        } else {
          return self.color().toHex();
        }
      },
      color: function () {
        if (!this._color) {
          this._color = global$e();
        }
        return this._color;
      },
      renderHtml: function () {
        var self = this;
        var id = self._id;
        var prefix = self.classPrefix;
        var hueHtml;
        var stops = '#ff0000,#ff0080,#ff00ff,#8000ff,#0000ff,#0080ff,#00ffff,#00ff80,#00ff00,#80ff00,#ffff00,#ff8000,#ff0000';
        function getOldIeFallbackHtml() {
          var i, l, html = '', gradientPrefix, stopsList;
          gradientPrefix = 'filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=';
          stopsList = stops.split(',');
          for (i = 0, l = stopsList.length - 1; i < l; i++) {
            html += '<div class="' + prefix + 'colorpicker-h-chunk" style="' + 'height:' + 100 / l + '%;' + gradientPrefix + stopsList[i] + ',endColorstr=' + stopsList[i + 1] + ');' + '-ms-' + gradientPrefix + stopsList[i] + ',endColorstr=' + stopsList[i + 1] + ')' + '"></div>';
          }
          return html;
        }
        var gradientCssText = 'background: -ms-linear-gradient(top,' + stops + ');' + 'background: linear-gradient(to bottom,' + stops + ');';
        hueHtml = '<div id="' + id + '-h" class="' + prefix + 'colorpicker-h" style="' + gradientCssText + '">' + getOldIeFallbackHtml() + '<div id="' + id + '-hp" class="' + prefix + 'colorpicker-h-marker"></div>' + '</div>';
        return '<div id="' + id + '" class="' + self.classes + '">' + '<div id="' + id + '-sv" class="' + prefix + 'colorpicker-sv">' + '<div class="' + prefix + 'colorpicker-overlay1">' + '<div class="' + prefix + 'colorpicker-overlay2">' + '<div id="' + id + '-svp" class="' + prefix + 'colorpicker-selector1">' + '<div class="' + prefix + 'colorpicker-selector2"></div>' + '</div>' + '</div>' + '</div>' + '</div>' + hueHtml + '</div>';
      }
    });

    var DropZone = Widget.extend({
      init: function (settings) {
        var self = this;
        settings = global$2.extend({
          height: 100,
          text: 'Drop an image here',
          multiple: false,
          accept: null
        }, settings);
        self._super(settings);
        self.classes.add('dropzone');
        if (settings.multiple) {
          self.classes.add('multiple');
        }
      },
      renderHtml: function () {
        var self = this;
        var attrs, elm;
        var cfg = self.settings;
        attrs = {
          id: self._id,
          hidefocus: '1'
        };
        elm = funcs.create('div', attrs, '<span>' + this.translate(cfg.text) + '</span>');
        if (cfg.height) {
          funcs.css(elm, 'height', cfg.height + 'px');
        }
        if (cfg.width) {
          funcs.css(elm, 'width', cfg.width + 'px');
        }
        elm.className = self.classes;
        return elm.outerHTML;
      },
      postRender: function () {
        var self = this;
        var toggleDragClass = function (e) {
          e.preventDefault();
          self.classes.toggle('dragenter');
          self.getEl().className = self.classes;
        };
        var filter = function (files) {
          var accept = self.settings.accept;
          if (typeof accept !== 'string') {
            return files;
          }
          var re = new RegExp('(' + accept.split(/\s*,\s*/).join('|') + ')$', 'i');
          return global$2.grep(files, function (file) {
            return re.test(file.name);
          });
        };
        self._super();
        self.$el.on('dragover', function (e) {
          e.preventDefault();
        });
        self.$el.on('dragenter', toggleDragClass);
        self.$el.on('dragleave', toggleDragClass);
        self.$el.on('drop', function (e) {
          e.preventDefault();
          if (self.state.get('disabled')) {
            return;
          }
          var files = filter(e.dataTransfer.files);
          self.value = function () {
            if (!files.length) {
              return null;
            } else if (self.settings.multiple) {
              return files;
            } else {
              return files[0];
            }
          };
          if (files.length) {
            self.fire('change', e);
          }
        });
      },
      remove: function () {
        this.$el.off();
        this._super();
      }
    });

    var Path = Widget.extend({
      init: function (settings) {
        var self = this;
        if (!settings.delimiter) {
          settings.delimiter = '\xBB';
        }
        self._super(settings);
        self.classes.add('path');
        self.canFocus = true;
        self.on('click', function (e) {
          var index;
          var target = e.target;
          if (index = target.getAttribute('data-index')) {
            self.fire('select', {
              value: self.row()[index],
              index: index
            });
          }
        });
        self.row(self.settings.row);
      },
      focus: function () {
        var self = this;
        self.getEl().firstChild.focus();
        return self;
      },
      row: function (row) {
        if (!arguments.length) {
          return this.state.get('row');
        }
        this.state.set('row', row);
        return this;
      },
      renderHtml: function () {
        var self = this;
        return '<div id="' + self._id + '" class="' + self.classes + '">' + self._getDataPathHtml(self.state.get('row')) + '</div>';
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:row', function (e) {
          self.innerHtml(self._getDataPathHtml(e.value));
        });
        return self._super();
      },
      _getDataPathHtml: function (data) {
        var self = this;
        var parts = data || [];
        var i, l, html = '';
        var prefix = self.classPrefix;
        for (i = 0, l = parts.length; i < l; i++) {
          html += (i > 0 ? '<div class="' + prefix + 'divider" aria-hidden="true"> ' + self.settings.delimiter + ' </div>' : '') + '<div role="button" class="' + prefix + 'path-item' + (i === l - 1 ? ' ' + prefix + 'last' : '') + '" data-index="' + i + '" tabindex="-1" id="' + self._id + '-' + i + '" aria-level="' + (i + 1) + '">' + parts[i].name + '</div>';
        }
        if (!html) {
          html = '<div class="' + prefix + 'path-item">\xA0</div>';
        }
        return html;
      }
    });

    var ElementPath = Path.extend({
      postRender: function () {
        var self = this, editor = self.settings.editor;
        function isHidden(elm) {
          if (elm.nodeType === 1) {
            if (elm.nodeName === 'BR' || !!elm.getAttribute('data-mce-bogus')) {
              return true;
            }
            if (elm.getAttribute('data-mce-type') === 'bookmark') {
              return true;
            }
          }
          return false;
        }
        if (editor.settings.elementpath !== false) {
          self.on('select', function (e) {
            editor.focus();
            editor.selection.select(this.row()[e.index].element);
            editor.nodeChanged();
          });
          editor.on('nodeChange', function (e) {
            var outParents = [];
            var parents = e.parents;
            var i = parents.length;
            while (i--) {
              if (parents[i].nodeType === 1 && !isHidden(parents[i])) {
                var args = editor.fire('ResolveName', {
                  name: parents[i].nodeName.toLowerCase(),
                  target: parents[i]
                });
                if (!args.isDefaultPrevented()) {
                  outParents.push({
                    name: args.name,
                    element: parents[i]
                  });
                }
                if (args.isPropagationStopped()) {
                  break;
                }
              }
            }
            self.row(outParents);
          });
        }
        return self._super();
      }
    });

    var FormItem = Container.extend({
      Defaults: {
        layout: 'flex',
        align: 'center',
        defaults: { flex: 1 }
      },
      renderHtml: function () {
        var self = this, layout = self._layout, prefix = self.classPrefix;
        self.classes.add('formitem');
        layout.preRender(self);
        return '<div id="' + self._id + '" class="' + self.classes + '" hidefocus="1" tabindex="-1">' + (self.settings.title ? '<div id="' + self._id + '-title" class="' + prefix + 'title">' + self.settings.title + '</div>' : '') + '<div id="' + self._id + '-body" class="' + self.bodyClasses + '">' + (self.settings.html || '') + layout.renderHtml(self) + '</div>' + '</div>';
      }
    });

    var Form = Container.extend({
      Defaults: {
        containerCls: 'form',
        layout: 'flex',
        direction: 'column',
        align: 'stretch',
        flex: 1,
        padding: 15,
        labelGap: 30,
        spacing: 10,
        callbacks: {
          submit: function () {
            this.submit();
          }
        }
      },
      preRender: function () {
        var self = this, items = self.items();
        if (!self.settings.formItemDefaults) {
          self.settings.formItemDefaults = {
            layout: 'flex',
            autoResize: 'overflow',
            defaults: { flex: 1 }
          };
        }
        items.each(function (ctrl) {
          var formItem;
          var label = ctrl.settings.label;
          if (label) {
            formItem = new FormItem(global$2.extend({
              items: {
                type: 'label',
                id: ctrl._id + '-l',
                text: label,
                flex: 0,
                forId: ctrl._id,
                disabled: ctrl.disabled()
              }
            }, self.settings.formItemDefaults));
            formItem.type = 'formitem';
            ctrl.aria('labelledby', ctrl._id + '-l');
            if (typeof ctrl.settings.flex === 'undefined') {
              ctrl.settings.flex = 1;
            }
            self.replace(ctrl, formItem);
            formItem.add(ctrl);
          }
        });
      },
      submit: function () {
        return this.fire('submit', { data: this.toJSON() });
      },
      postRender: function () {
        var self = this;
        self._super();
        self.fromJSON(self.settings.data);
      },
      bindStates: function () {
        var self = this;
        self._super();
        function recalcLabels() {
          var maxLabelWidth = 0;
          var labels = [];
          var i, labelGap, items;
          if (self.settings.labelGapCalc === false) {
            return;
          }
          if (self.settings.labelGapCalc === 'children') {
            items = self.find('formitem');
          } else {
            items = self.items();
          }
          items.filter('formitem').each(function (item) {
            var labelCtrl = item.items()[0], labelWidth = labelCtrl.getEl().clientWidth;
            maxLabelWidth = labelWidth > maxLabelWidth ? labelWidth : maxLabelWidth;
            labels.push(labelCtrl);
          });
          labelGap = self.settings.labelGap || 0;
          i = labels.length;
          while (i--) {
            labels[i].settings.minWidth = maxLabelWidth + labelGap;
          }
        }
        self.on('show', recalcLabels);
        recalcLabels();
      }
    });

    var FieldSet = Form.extend({
      Defaults: {
        containerCls: 'fieldset',
        layout: 'flex',
        direction: 'column',
        align: 'stretch',
        flex: 1,
        padding: '25 15 5 15',
        labelGap: 30,
        spacing: 10,
        border: 1
      },
      renderHtml: function () {
        var self = this, layout = self._layout, prefix = self.classPrefix;
        self.preRender();
        layout.preRender(self);
        return '<fieldset id="' + self._id + '" class="' + self.classes + '" hidefocus="1" tabindex="-1">' + (self.settings.title ? '<legend id="' + self._id + '-title" class="' + prefix + 'fieldset-title">' + self.settings.title + '</legend>' : '') + '<div id="' + self._id + '-body" class="' + self.bodyClasses + '">' + (self.settings.html || '') + layout.renderHtml(self) + '</div>' + '</fieldset>';
      }
    });

    var unique$1 = 0;
    var generate = function (prefix) {
      var date = new Date();
      var time = date.getTime();
      var random = Math.floor(Math.random() * 1000000000);
      unique$1++;
      return prefix + '_' + random + unique$1 + String(time);
    };

    var fromHtml = function (html, scope) {
      var doc = scope || document;
      var div = doc.createElement('div');
      div.innerHTML = html;
      if (!div.hasChildNodes() || div.childNodes.length > 1) {
        console.error('HTML does not have a single root node', html);
        throw 'HTML must have a single root node';
      }
      return fromDom(div.childNodes[0]);
    };
    var fromTag = function (tag, scope) {
      var doc = scope || document;
      var node = doc.createElement(tag);
      return fromDom(node);
    };
    var fromText = function (text, scope) {
      var doc = scope || document;
      var node = doc.createTextNode(text);
      return fromDom(node);
    };
    var fromDom = function (node) {
      if (node === null || node === undefined)
        throw new Error('Node cannot be null or undefined');
      return { dom: constant(node) };
    };
    var fromPoint = function (docElm, x, y) {
      var doc = docElm.dom();
      return Option.from(doc.elementFromPoint(x, y)).map(fromDom);
    };
    var Element$$1 = {
      fromHtml: fromHtml,
      fromTag: fromTag,
      fromText: fromText,
      fromDom: fromDom,
      fromPoint: fromPoint
    };

    var cached = function (f) {
      var called = false;
      var r;
      return function () {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        if (!called) {
          called = true;
          r = f.apply(null, args);
        }
        return r;
      };
    };

    var ATTRIBUTE = Node.ATTRIBUTE_NODE;
    var CDATA_SECTION = Node.CDATA_SECTION_NODE;
    var COMMENT = Node.COMMENT_NODE;
    var DOCUMENT = Node.DOCUMENT_NODE;
    var DOCUMENT_TYPE = Node.DOCUMENT_TYPE_NODE;
    var DOCUMENT_FRAGMENT = Node.DOCUMENT_FRAGMENT_NODE;
    var ELEMENT = Node.ELEMENT_NODE;
    var TEXT = Node.TEXT_NODE;
    var PROCESSING_INSTRUCTION = Node.PROCESSING_INSTRUCTION_NODE;
    var ENTITY_REFERENCE = Node.ENTITY_REFERENCE_NODE;
    var ENTITY = Node.ENTITY_NODE;
    var NOTATION = Node.NOTATION_NODE;

    var Immutable = function () {
      var fields = [];
      for (var _i = 0; _i < arguments.length; _i++) {
        fields[_i] = arguments[_i];
      }
      return function () {
        var values = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          values[_i] = arguments[_i];
        }
        if (fields.length !== values.length) {
          throw new Error('Wrong number of arguments to struct. Expected "[' + fields.length + ']", got ' + values.length + ' arguments');
        }
        var struct = {};
        each(fields, function (name, i) {
          struct[name] = constant(values[i]);
        });
        return struct;
      };
    };

    var Global = typeof window !== 'undefined' ? window : Function('return this;')();

    var path = function (parts, scope) {
      var o = scope !== undefined && scope !== null ? scope : Global;
      for (var i = 0; i < parts.length && o !== undefined && o !== null; ++i)
        o = o[parts[i]];
      return o;
    };
    var resolve = function (p, scope) {
      var parts = p.split('.');
      return path(parts, scope);
    };

    var unsafe = function (name, scope) {
      return resolve(name, scope);
    };
    var getOrDie = function (name, scope) {
      var actual = unsafe(name, scope);
      if (actual === undefined || actual === null)
        throw name + ' not available on this browser';
      return actual;
    };
    var Global$1 = { getOrDie: getOrDie };

    var node = function () {
      var f = Global$1.getOrDie('Node');
      return f;
    };
    var compareDocumentPosition = function (a, b, match) {
      return (a.compareDocumentPosition(b) & match) !== 0;
    };
    var documentPositionPreceding = function (a, b) {
      return compareDocumentPosition(a, b, node().DOCUMENT_POSITION_PRECEDING);
    };
    var documentPositionContainedBy = function (a, b) {
      return compareDocumentPosition(a, b, node().DOCUMENT_POSITION_CONTAINED_BY);
    };
    var Node$1 = {
      documentPositionPreceding: documentPositionPreceding,
      documentPositionContainedBy: documentPositionContainedBy
    };

    var firstMatch = function (regexes, s) {
      for (var i = 0; i < regexes.length; i++) {
        var x = regexes[i];
        if (x.test(s))
          return x;
      }
      return undefined;
    };
    var find$2 = function (regexes, agent) {
      var r = firstMatch(regexes, agent);
      if (!r)
        return {
          major: 0,
          minor: 0
        };
      var group = function (i) {
        return Number(agent.replace(r, '$' + i));
      };
      return nu(group(1), group(2));
    };
    var detect = function (versionRegexes, agent) {
      var cleanedAgent = String(agent).toLowerCase();
      if (versionRegexes.length === 0)
        return unknown();
      return find$2(versionRegexes, cleanedAgent);
    };
    var unknown = function () {
      return nu(0, 0);
    };
    var nu = function (major, minor) {
      return {
        major: major,
        minor: minor
      };
    };
    var Version = {
      nu: nu,
      detect: detect,
      unknown: unknown
    };

    var edge = 'Edge';
    var chrome = 'Chrome';
    var ie = 'IE';
    var opera = 'Opera';
    var firefox = 'Firefox';
    var safari = 'Safari';
    var isBrowser = function (name, current) {
      return function () {
        return current === name;
      };
    };
    var unknown$1 = function () {
      return nu$1({
        current: undefined,
        version: Version.unknown()
      });
    };
    var nu$1 = function (info) {
      var current = info.current;
      var version = info.version;
      return {
        current: current,
        version: version,
        isEdge: isBrowser(edge, current),
        isChrome: isBrowser(chrome, current),
        isIE: isBrowser(ie, current),
        isOpera: isBrowser(opera, current),
        isFirefox: isBrowser(firefox, current),
        isSafari: isBrowser(safari, current)
      };
    };
    var Browser = {
      unknown: unknown$1,
      nu: nu$1,
      edge: constant(edge),
      chrome: constant(chrome),
      ie: constant(ie),
      opera: constant(opera),
      firefox: constant(firefox),
      safari: constant(safari)
    };

    var windows$1 = 'Windows';
    var ios = 'iOS';
    var android = 'Android';
    var linux = 'Linux';
    var osx = 'OSX';
    var solaris = 'Solaris';
    var freebsd = 'FreeBSD';
    var isOS = function (name, current) {
      return function () {
        return current === name;
      };
    };
    var unknown$2 = function () {
      return nu$2({
        current: undefined,
        version: Version.unknown()
      });
    };
    var nu$2 = function (info) {
      var current = info.current;
      var version = info.version;
      return {
        current: current,
        version: version,
        isWindows: isOS(windows$1, current),
        isiOS: isOS(ios, current),
        isAndroid: isOS(android, current),
        isOSX: isOS(osx, current),
        isLinux: isOS(linux, current),
        isSolaris: isOS(solaris, current),
        isFreeBSD: isOS(freebsd, current)
      };
    };
    var OperatingSystem = {
      unknown: unknown$2,
      nu: nu$2,
      windows: constant(windows$1),
      ios: constant(ios),
      android: constant(android),
      linux: constant(linux),
      osx: constant(osx),
      solaris: constant(solaris),
      freebsd: constant(freebsd)
    };

    var DeviceType = function (os, browser, userAgent) {
      var isiPad = os.isiOS() && /ipad/i.test(userAgent) === true;
      var isiPhone = os.isiOS() && !isiPad;
      var isAndroid3 = os.isAndroid() && os.version.major === 3;
      var isAndroid4 = os.isAndroid() && os.version.major === 4;
      var isTablet = isiPad || isAndroid3 || isAndroid4 && /mobile/i.test(userAgent) === true;
      var isTouch = os.isiOS() || os.isAndroid();
      var isPhone = isTouch && !isTablet;
      var iOSwebview = browser.isSafari() && os.isiOS() && /safari/i.test(userAgent) === false;
      return {
        isiPad: constant(isiPad),
        isiPhone: constant(isiPhone),
        isTablet: constant(isTablet),
        isPhone: constant(isPhone),
        isTouch: constant(isTouch),
        isAndroid: os.isAndroid,
        isiOS: os.isiOS,
        isWebView: constant(iOSwebview)
      };
    };

    var detect$1 = function (candidates, userAgent) {
      var agent = String(userAgent).toLowerCase();
      return find(candidates, function (candidate) {
        return candidate.search(agent);
      });
    };
    var detectBrowser = function (browsers, userAgent) {
      return detect$1(browsers, userAgent).map(function (browser) {
        var version = Version.detect(browser.versionRegexes, userAgent);
        return {
          current: browser.name,
          version: version
        };
      });
    };
    var detectOs = function (oses, userAgent) {
      return detect$1(oses, userAgent).map(function (os) {
        var version = Version.detect(os.versionRegexes, userAgent);
        return {
          current: os.name,
          version: version
        };
      });
    };
    var UaString = {
      detectBrowser: detectBrowser,
      detectOs: detectOs
    };

    var contains$1 = function (str, substr) {
      return str.indexOf(substr) !== -1;
    };

    var normalVersionRegex = /.*?version\/\ ?([0-9]+)\.([0-9]+).*/;
    var checkContains = function (target) {
      return function (uastring) {
        return contains$1(uastring, target);
      };
    };
    var browsers = [
      {
        name: 'Edge',
        versionRegexes: [/.*?edge\/ ?([0-9]+)\.([0-9]+)$/],
        search: function (uastring) {
          var monstrosity = contains$1(uastring, 'edge/') && contains$1(uastring, 'chrome') && contains$1(uastring, 'safari') && contains$1(uastring, 'applewebkit');
          return monstrosity;
        }
      },
      {
        name: 'Chrome',
        versionRegexes: [
          /.*?chrome\/([0-9]+)\.([0-9]+).*/,
          normalVersionRegex
        ],
        search: function (uastring) {
          return contains$1(uastring, 'chrome') && !contains$1(uastring, 'chromeframe');
        }
      },
      {
        name: 'IE',
        versionRegexes: [
          /.*?msie\ ?([0-9]+)\.([0-9]+).*/,
          /.*?rv:([0-9]+)\.([0-9]+).*/
        ],
        search: function (uastring) {
          return contains$1(uastring, 'msie') || contains$1(uastring, 'trident');
        }
      },
      {
        name: 'Opera',
        versionRegexes: [
          normalVersionRegex,
          /.*?opera\/([0-9]+)\.([0-9]+).*/
        ],
        search: checkContains('opera')
      },
      {
        name: 'Firefox',
        versionRegexes: [/.*?firefox\/\ ?([0-9]+)\.([0-9]+).*/],
        search: checkContains('firefox')
      },
      {
        name: 'Safari',
        versionRegexes: [
          normalVersionRegex,
          /.*?cpu os ([0-9]+)_([0-9]+).*/
        ],
        search: function (uastring) {
          return (contains$1(uastring, 'safari') || contains$1(uastring, 'mobile/')) && contains$1(uastring, 'applewebkit');
        }
      }
    ];
    var oses = [
      {
        name: 'Windows',
        search: checkContains('win'),
        versionRegexes: [/.*?windows\ nt\ ?([0-9]+)\.([0-9]+).*/]
      },
      {
        name: 'iOS',
        search: function (uastring) {
          return contains$1(uastring, 'iphone') || contains$1(uastring, 'ipad');
        },
        versionRegexes: [
          /.*?version\/\ ?([0-9]+)\.([0-9]+).*/,
          /.*cpu os ([0-9]+)_([0-9]+).*/,
          /.*cpu iphone os ([0-9]+)_([0-9]+).*/
        ]
      },
      {
        name: 'Android',
        search: checkContains('android'),
        versionRegexes: [/.*?android\ ?([0-9]+)\.([0-9]+).*/]
      },
      {
        name: 'OSX',
        search: checkContains('os x'),
        versionRegexes: [/.*?os\ x\ ?([0-9]+)_([0-9]+).*/]
      },
      {
        name: 'Linux',
        search: checkContains('linux'),
        versionRegexes: []
      },
      {
        name: 'Solaris',
        search: checkContains('sunos'),
        versionRegexes: []
      },
      {
        name: 'FreeBSD',
        search: checkContains('freebsd'),
        versionRegexes: []
      }
    ];
    var PlatformInfo = {
      browsers: constant(browsers),
      oses: constant(oses)
    };

    var detect$2 = function (userAgent) {
      var browsers = PlatformInfo.browsers();
      var oses = PlatformInfo.oses();
      var browser = UaString.detectBrowser(browsers, userAgent).fold(Browser.unknown, Browser.nu);
      var os = UaString.detectOs(oses, userAgent).fold(OperatingSystem.unknown, OperatingSystem.nu);
      var deviceType = DeviceType(os, browser, userAgent);
      return {
        browser: browser,
        os: os,
        deviceType: deviceType
      };
    };
    var PlatformDetection = { detect: detect$2 };

    var detect$3 = cached(function () {
      var userAgent = navigator.userAgent;
      return PlatformDetection.detect(userAgent);
    });
    var PlatformDetection$1 = { detect: detect$3 };

    var ELEMENT$1 = ELEMENT;
    var DOCUMENT$1 = DOCUMENT;
    var bypassSelector = function (dom) {
      return dom.nodeType !== ELEMENT$1 && dom.nodeType !== DOCUMENT$1 || dom.childElementCount === 0;
    };
    var all = function (selector, scope) {
      var base = scope === undefined ? document : scope.dom();
      return bypassSelector(base) ? [] : map(base.querySelectorAll(selector), Element$$1.fromDom);
    };
    var one = function (selector, scope) {
      var base = scope === undefined ? document : scope.dom();
      return bypassSelector(base) ? Option.none() : Option.from(base.querySelector(selector)).map(Element$$1.fromDom);
    };

    var regularContains = function (e1, e2) {
      var d1 = e1.dom(), d2 = e2.dom();
      return d1 === d2 ? false : d1.contains(d2);
    };
    var ieContains = function (e1, e2) {
      return Node$1.documentPositionContainedBy(e1.dom(), e2.dom());
    };
    var browser = PlatformDetection$1.detect().browser;
    var contains$2 = browser.isIE() ? ieContains : regularContains;

    var spot = Immutable('element', 'offset');

    var descendants$1 = function (scope, selector) {
      return all(selector, scope);
    };

    var trim$1 = global$2.trim;
    var hasContentEditableState = function (value) {
      return function (node) {
        if (node && node.nodeType === 1) {
          if (node.contentEditable === value) {
            return true;
          }
          if (node.getAttribute('data-mce-contenteditable') === value) {
            return true;
          }
        }
        return false;
      };
    };
    var isContentEditableTrue = hasContentEditableState('true');
    var isContentEditableFalse = hasContentEditableState('false');
    var create = function (type, title, url, level, attach) {
      return {
        type: type,
        title: title,
        url: url,
        level: level,
        attach: attach
      };
    };
    var isChildOfContentEditableTrue = function (node) {
      while (node = node.parentNode) {
        var value = node.contentEditable;
        if (value && value !== 'inherit') {
          return isContentEditableTrue(node);
        }
      }
      return false;
    };
    var select = function (selector, root) {
      return map(descendants$1(Element$$1.fromDom(root), selector), function (element) {
        return element.dom();
      });
    };
    var getElementText = function (elm) {
      return elm.innerText || elm.textContent;
    };
    var getOrGenerateId = function (elm) {
      return elm.id ? elm.id : generate('h');
    };
    var isAnchor = function (elm) {
      return elm && elm.nodeName === 'A' && (elm.id || elm.name);
    };
    var isValidAnchor = function (elm) {
      return isAnchor(elm) && isEditable(elm);
    };
    var isHeader = function (elm) {
      return elm && /^(H[1-6])$/.test(elm.nodeName);
    };
    var isEditable = function (elm) {
      return isChildOfContentEditableTrue(elm) && !isContentEditableFalse(elm);
    };
    var isValidHeader = function (elm) {
      return isHeader(elm) && isEditable(elm);
    };
    var getLevel = function (elm) {
      return isHeader(elm) ? parseInt(elm.nodeName.substr(1), 10) : 0;
    };
    var headerTarget = function (elm) {
      var headerId = getOrGenerateId(elm);
      var attach = function () {
        elm.id = headerId;
      };
      return create('header', getElementText(elm), '#' + headerId, getLevel(elm), attach);
    };
    var anchorTarget = function (elm) {
      var anchorId = elm.id || elm.name;
      var anchorText = getElementText(elm);
      return create('anchor', anchorText ? anchorText : '#' + anchorId, '#' + anchorId, 0, noop);
    };
    var getHeaderTargets = function (elms) {
      return map(filter(elms, isValidHeader), headerTarget);
    };
    var getAnchorTargets = function (elms) {
      return map(filter(elms, isValidAnchor), anchorTarget);
    };
    var getTargetElements = function (elm) {
      var elms = select('h1,h2,h3,h4,h5,h6,a:not([href])', elm);
      return elms;
    };
    var hasTitle = function (target) {
      return trim$1(target.title).length > 0;
    };
    var find$3 = function (elm) {
      var elms = getTargetElements(elm);
      return filter(getHeaderTargets(elms).concat(getAnchorTargets(elms)), hasTitle);
    };
    var LinkTargets = { find: find$3 };

    var getActiveEditor = function () {
      return window.tinymce ? window.tinymce.activeEditor : global$1.activeEditor;
    };
    var history = {};
    var HISTORY_LENGTH = 5;
    var clearHistory = function () {
      history = {};
    };
    var toMenuItem = function (target) {
      return {
        title: target.title,
        value: {
          title: { raw: target.title },
          url: target.url,
          attach: target.attach
        }
      };
    };
    var toMenuItems = function (targets) {
      return global$2.map(targets, toMenuItem);
    };
    var staticMenuItem = function (title, url) {
      return {
        title: title,
        value: {
          title: title,
          url: url,
          attach: noop
        }
      };
    };
    var isUniqueUrl = function (url, targets) {
      var foundTarget = exists(targets, function (target) {
        return target.url === url;
      });
      return !foundTarget;
    };
    var getSetting = function (editorSettings, name, defaultValue) {
      var value = name in editorSettings ? editorSettings[name] : defaultValue;
      return value === false ? null : value;
    };
    var createMenuItems = function (term, targets, fileType, editorSettings) {
      var separator = { title: '-' };
      var fromHistoryMenuItems = function (history) {
        var historyItems = history.hasOwnProperty(fileType) ? history[fileType] : [];
        var uniqueHistory = filter(historyItems, function (url) {
          return isUniqueUrl(url, targets);
        });
        return global$2.map(uniqueHistory, function (url) {
          return {
            title: url,
            value: {
              title: url,
              url: url,
              attach: noop
            }
          };
        });
      };
      var fromMenuItems = function (type) {
        var filteredTargets = filter(targets, function (target) {
          return target.type === type;
        });
        return toMenuItems(filteredTargets);
      };
      var anchorMenuItems = function () {
        var anchorMenuItems = fromMenuItems('anchor');
        var topAnchor = getSetting(editorSettings, 'anchor_top', '#top');
        var bottomAchor = getSetting(editorSettings, 'anchor_bottom', '#bottom');
        if (topAnchor !== null) {
          anchorMenuItems.unshift(staticMenuItem('<top>', topAnchor));
        }
        if (bottomAchor !== null) {
          anchorMenuItems.push(staticMenuItem('<bottom>', bottomAchor));
        }
        return anchorMenuItems;
      };
      var join = function (items) {
        return foldl(items, function (a, b) {
          var bothEmpty = a.length === 0 || b.length === 0;
          return bothEmpty ? a.concat(b) : a.concat(separator, b);
        }, []);
      };
      if (editorSettings.typeahead_urls === false) {
        return [];
      }
      return fileType === 'file' ? join([
        filterByQuery(term, fromHistoryMenuItems(history)),
        filterByQuery(term, fromMenuItems('header')),
        filterByQuery(term, anchorMenuItems())
      ]) : filterByQuery(term, fromHistoryMenuItems(history));
    };
    var addToHistory = function (url, fileType) {
      var items = history[fileType];
      if (!/^https?/.test(url)) {
        return;
      }
      if (items) {
        if (indexOf(items, url).isNone()) {
          history[fileType] = items.slice(0, HISTORY_LENGTH).concat(url);
        }
      } else {
        history[fileType] = [url];
      }
    };
    var filterByQuery = function (term, menuItems) {
      var lowerCaseTerm = term.toLowerCase();
      var result = global$2.grep(menuItems, function (item) {
        return item.title.toLowerCase().indexOf(lowerCaseTerm) !== -1;
      });
      return result.length === 1 && result[0].title === term ? [] : result;
    };
    var getTitle = function (linkDetails) {
      var title = linkDetails.title;
      return title.raw ? title.raw : title;
    };
    var setupAutoCompleteHandler = function (ctrl, editorSettings, bodyElm, fileType) {
      var autocomplete = function (term) {
        var linkTargets = LinkTargets.find(bodyElm);
        var menuItems = createMenuItems(term, linkTargets, fileType, editorSettings);
        ctrl.showAutoComplete(menuItems, term);
      };
      ctrl.on('autocomplete', function () {
        autocomplete(ctrl.value());
      });
      ctrl.on('selectitem', function (e) {
        var linkDetails = e.value;
        ctrl.value(linkDetails.url);
        var title = getTitle(linkDetails);
        if (fileType === 'image') {
          ctrl.fire('change', {
            meta: {
              alt: title,
              attach: linkDetails.attach
            }
          });
        } else {
          ctrl.fire('change', {
            meta: {
              text: title,
              attach: linkDetails.attach
            }
          });
        }
        ctrl.focus();
      });
      ctrl.on('click', function (e) {
        if (ctrl.value().length === 0 && e.target.nodeName === 'INPUT') {
          autocomplete('');
        }
      });
      ctrl.on('PostRender', function () {
        ctrl.getRoot().on('submit', function (e) {
          if (!e.isDefaultPrevented()) {
            addToHistory(ctrl.value(), fileType);
          }
        });
      });
    };
    var statusToUiState = function (result) {
      var status = result.status, message = result.message;
      if (status === 'valid') {
        return {
          status: 'ok',
          message: message
        };
      } else if (status === 'unknown') {
        return {
          status: 'warn',
          message: message
        };
      } else if (status === 'invalid') {
        return {
          status: 'warn',
          message: message
        };
      } else {
        return {
          status: 'none',
          message: ''
        };
      }
    };
    var setupLinkValidatorHandler = function (ctrl, editorSettings, fileType) {
      var validatorHandler = editorSettings.filepicker_validator_handler;
      if (validatorHandler) {
        var validateUrl_1 = function (url) {
          if (url.length === 0) {
            ctrl.statusLevel('none');
            return;
          }
          validatorHandler({
            url: url,
            type: fileType
          }, function (result) {
            var uiState = statusToUiState(result);
            ctrl.statusMessage(uiState.message);
            ctrl.statusLevel(uiState.status);
          });
        };
        ctrl.state.on('change:value', function (e) {
          validateUrl_1(e.value);
        });
      }
    };
    var FilePicker = ComboBox.extend({
      Statics: { clearHistory: clearHistory },
      init: function (settings) {
        var self = this, editor = getActiveEditor(), editorSettings = editor.settings;
        var actionCallback, fileBrowserCallback, fileBrowserCallbackTypes;
        var fileType = settings.filetype;
        settings.spellcheck = false;
        fileBrowserCallbackTypes = editorSettings.file_picker_types || editorSettings.file_browser_callback_types;
        if (fileBrowserCallbackTypes) {
          fileBrowserCallbackTypes = global$2.makeMap(fileBrowserCallbackTypes, /[, ]/);
        }
        if (!fileBrowserCallbackTypes || fileBrowserCallbackTypes[fileType]) {
          fileBrowserCallback = editorSettings.file_picker_callback;
          if (fileBrowserCallback && (!fileBrowserCallbackTypes || fileBrowserCallbackTypes[fileType])) {
            actionCallback = function () {
              var meta = self.fire('beforecall').meta;
              meta = global$2.extend({ filetype: fileType }, meta);
              fileBrowserCallback.call(editor, function (value, meta) {
                self.value(value).fire('change', { meta: meta });
              }, self.value(), meta);
            };
          } else {
            fileBrowserCallback = editorSettings.file_browser_callback;
            if (fileBrowserCallback && (!fileBrowserCallbackTypes || fileBrowserCallbackTypes[fileType])) {
              actionCallback = function () {
                fileBrowserCallback(self.getEl('inp').id, self.value(), fileType, window);
              };
            }
          }
        }
        if (actionCallback) {
          settings.icon = 'browse';
          settings.onaction = actionCallback;
        }
        self._super(settings);
        self.classes.add('filepicker');
        setupAutoCompleteHandler(self, editorSettings, editor.getBody(), fileType);
        setupLinkValidatorHandler(self, editorSettings, fileType);
      }
    });

    var FitLayout = AbsoluteLayout.extend({
      recalc: function (container) {
        var contLayoutRect = container.layoutRect(), paddingBox = container.paddingBox;
        container.items().filter(':visible').each(function (ctrl) {
          ctrl.layoutRect({
            x: paddingBox.left,
            y: paddingBox.top,
            w: contLayoutRect.innerW - paddingBox.right - paddingBox.left,
            h: contLayoutRect.innerH - paddingBox.top - paddingBox.bottom
          });
          if (ctrl.recalc) {
            ctrl.recalc();
          }
        });
      }
    });

    var FlexLayout = AbsoluteLayout.extend({
      recalc: function (container) {
        var i, l, items, contLayoutRect, contPaddingBox, contSettings, align, pack, spacing, totalFlex, availableSpace, direction;
        var ctrl, ctrlLayoutRect, ctrlSettings, flex;
        var maxSizeItems = [];
        var size, maxSize, ratio, rect, pos, maxAlignEndPos;
        var sizeName, minSizeName, posName, maxSizeName, beforeName, innerSizeName, deltaSizeName, contentSizeName;
        var alignAxisName, alignInnerSizeName, alignSizeName, alignMinSizeName, alignBeforeName, alignAfterName;
        var alignDeltaSizeName, alignContentSizeName;
        var max = Math.max, min = Math.min;
        items = container.items().filter(':visible');
        contLayoutRect = container.layoutRect();
        contPaddingBox = container.paddingBox;
        contSettings = container.settings;
        direction = container.isRtl() ? contSettings.direction || 'row-reversed' : contSettings.direction;
        align = contSettings.align;
        pack = container.isRtl() ? contSettings.pack || 'end' : contSettings.pack;
        spacing = contSettings.spacing || 0;
        if (direction === 'row-reversed' || direction === 'column-reverse') {
          items = items.set(items.toArray().reverse());
          direction = direction.split('-')[0];
        }
        if (direction === 'column') {
          posName = 'y';
          sizeName = 'h';
          minSizeName = 'minH';
          maxSizeName = 'maxH';
          innerSizeName = 'innerH';
          beforeName = 'top';
          deltaSizeName = 'deltaH';
          contentSizeName = 'contentH';
          alignBeforeName = 'left';
          alignSizeName = 'w';
          alignAxisName = 'x';
          alignInnerSizeName = 'innerW';
          alignMinSizeName = 'minW';
          alignAfterName = 'right';
          alignDeltaSizeName = 'deltaW';
          alignContentSizeName = 'contentW';
        } else {
          posName = 'x';
          sizeName = 'w';
          minSizeName = 'minW';
          maxSizeName = 'maxW';
          innerSizeName = 'innerW';
          beforeName = 'left';
          deltaSizeName = 'deltaW';
          contentSizeName = 'contentW';
          alignBeforeName = 'top';
          alignSizeName = 'h';
          alignAxisName = 'y';
          alignInnerSizeName = 'innerH';
          alignMinSizeName = 'minH';
          alignAfterName = 'bottom';
          alignDeltaSizeName = 'deltaH';
          alignContentSizeName = 'contentH';
        }
        availableSpace = contLayoutRect[innerSizeName] - contPaddingBox[beforeName] - contPaddingBox[beforeName];
        maxAlignEndPos = totalFlex = 0;
        for (i = 0, l = items.length; i < l; i++) {
          ctrl = items[i];
          ctrlLayoutRect = ctrl.layoutRect();
          ctrlSettings = ctrl.settings;
          flex = ctrlSettings.flex;
          availableSpace -= i < l - 1 ? spacing : 0;
          if (flex > 0) {
            totalFlex += flex;
            if (ctrlLayoutRect[maxSizeName]) {
              maxSizeItems.push(ctrl);
            }
            ctrlLayoutRect.flex = flex;
          }
          availableSpace -= ctrlLayoutRect[minSizeName];
          size = contPaddingBox[alignBeforeName] + ctrlLayoutRect[alignMinSizeName] + contPaddingBox[alignAfterName];
          if (size > maxAlignEndPos) {
            maxAlignEndPos = size;
          }
        }
        rect = {};
        if (availableSpace < 0) {
          rect[minSizeName] = contLayoutRect[minSizeName] - availableSpace + contLayoutRect[deltaSizeName];
        } else {
          rect[minSizeName] = contLayoutRect[innerSizeName] - availableSpace + contLayoutRect[deltaSizeName];
        }
        rect[alignMinSizeName] = maxAlignEndPos + contLayoutRect[alignDeltaSizeName];
        rect[contentSizeName] = contLayoutRect[innerSizeName] - availableSpace;
        rect[alignContentSizeName] = maxAlignEndPos;
        rect.minW = min(rect.minW, contLayoutRect.maxW);
        rect.minH = min(rect.minH, contLayoutRect.maxH);
        rect.minW = max(rect.minW, contLayoutRect.startMinWidth);
        rect.minH = max(rect.minH, contLayoutRect.startMinHeight);
        if (contLayoutRect.autoResize && (rect.minW !== contLayoutRect.minW || rect.minH !== contLayoutRect.minH)) {
          rect.w = rect.minW;
          rect.h = rect.minH;
          container.layoutRect(rect);
          this.recalc(container);
          if (container._lastRect === null) {
            var parentCtrl = container.parent();
            if (parentCtrl) {
              parentCtrl._lastRect = null;
              parentCtrl.recalc();
            }
          }
          return;
        }
        ratio = availableSpace / totalFlex;
        for (i = 0, l = maxSizeItems.length; i < l; i++) {
          ctrl = maxSizeItems[i];
          ctrlLayoutRect = ctrl.layoutRect();
          maxSize = ctrlLayoutRect[maxSizeName];
          size = ctrlLayoutRect[minSizeName] + ctrlLayoutRect.flex * ratio;
          if (size > maxSize) {
            availableSpace -= ctrlLayoutRect[maxSizeName] - ctrlLayoutRect[minSizeName];
            totalFlex -= ctrlLayoutRect.flex;
            ctrlLayoutRect.flex = 0;
            ctrlLayoutRect.maxFlexSize = maxSize;
          } else {
            ctrlLayoutRect.maxFlexSize = 0;
          }
        }
        ratio = availableSpace / totalFlex;
        pos = contPaddingBox[beforeName];
        rect = {};
        if (totalFlex === 0) {
          if (pack === 'end') {
            pos = availableSpace + contPaddingBox[beforeName];
          } else if (pack === 'center') {
            pos = Math.round(contLayoutRect[innerSizeName] / 2 - (contLayoutRect[innerSizeName] - availableSpace) / 2) + contPaddingBox[beforeName];
            if (pos < 0) {
              pos = contPaddingBox[beforeName];
            }
          } else if (pack === 'justify') {
            pos = contPaddingBox[beforeName];
            spacing = Math.floor(availableSpace / (items.length - 1));
          }
        }
        rect[alignAxisName] = contPaddingBox[alignBeforeName];
        for (i = 0, l = items.length; i < l; i++) {
          ctrl = items[i];
          ctrlLayoutRect = ctrl.layoutRect();
          size = ctrlLayoutRect.maxFlexSize || ctrlLayoutRect[minSizeName];
          if (align === 'center') {
            rect[alignAxisName] = Math.round(contLayoutRect[alignInnerSizeName] / 2 - ctrlLayoutRect[alignSizeName] / 2);
          } else if (align === 'stretch') {
            rect[alignSizeName] = max(ctrlLayoutRect[alignMinSizeName] || 0, contLayoutRect[alignInnerSizeName] - contPaddingBox[alignBeforeName] - contPaddingBox[alignAfterName]);
            rect[alignAxisName] = contPaddingBox[alignBeforeName];
          } else if (align === 'end') {
            rect[alignAxisName] = contLayoutRect[alignInnerSizeName] - ctrlLayoutRect[alignSizeName] - contPaddingBox.top;
          }
          if (ctrlLayoutRect.flex > 0) {
            size += ctrlLayoutRect.flex * ratio;
          }
          rect[sizeName] = size;
          rect[posName] = pos;
          ctrl.layoutRect(rect);
          if (ctrl.recalc) {
            ctrl.recalc();
          }
          pos += size + spacing;
        }
      }
    });

    var FlowLayout = Layout.extend({
      Defaults: {
        containerClass: 'flow-layout',
        controlClass: 'flow-layout-item',
        endClass: 'break'
      },
      recalc: function (container) {
        container.items().filter(':visible').each(function (ctrl) {
          if (ctrl.recalc) {
            ctrl.recalc();
          }
        });
      },
      isNative: function () {
        return true;
      }
    });

    var descendant$1 = function (scope, selector) {
      return one(selector, scope);
    };

    var toggleFormat = function (editor, fmt) {
      return function () {
        editor.execCommand('mceToggleFormat', false, fmt);
      };
    };
    var addFormatChangedListener = function (editor, name, changed) {
      var handler = function (state) {
        changed(state, name);
      };
      if (editor.formatter) {
        editor.formatter.formatChanged(name, handler);
      } else {
        editor.on('init', function () {
          editor.formatter.formatChanged(name, handler);
        });
      }
    };
    var postRenderFormatToggle = function (editor, name) {
      return function (e) {
        addFormatChangedListener(editor, name, function (state) {
          e.control.active(state);
        });
      };
    };

    var register = function (editor) {
      var alignFormats = [
        'alignleft',
        'aligncenter',
        'alignright',
        'alignjustify'
      ];
      var defaultAlign = 'alignleft';
      var alignMenuItems = [
        {
          text: 'Left',
          icon: 'alignleft',
          onclick: toggleFormat(editor, 'alignleft')
        },
        {
          text: 'Center',
          icon: 'aligncenter',
          onclick: toggleFormat(editor, 'aligncenter')
        },
        {
          text: 'Right',
          icon: 'alignright',
          onclick: toggleFormat(editor, 'alignright')
        },
        {
          text: 'Justify',
          icon: 'alignjustify',
          onclick: toggleFormat(editor, 'alignjustify')
        }
      ];
      editor.addMenuItem('align', {
        text: 'Align',
        menu: alignMenuItems
      });
      editor.addButton('align', {
        type: 'menubutton',
        icon: defaultAlign,
        menu: alignMenuItems,
        onShowMenu: function (e) {
          var menu = e.control.menu;
          global$2.each(alignFormats, function (formatName, idx) {
            menu.items().eq(idx).each(function (item) {
              return item.active(editor.formatter.match(formatName));
            });
          });
        },
        onPostRender: function (e) {
          var ctrl = e.control;
          global$2.each(alignFormats, function (formatName, idx) {
            addFormatChangedListener(editor, formatName, function (state) {
              ctrl.icon(defaultAlign);
              if (state) {
                ctrl.icon(formatName);
              }
            });
          });
        }
      });
      global$2.each({
        alignleft: [
          'Align left',
          'JustifyLeft'
        ],
        aligncenter: [
          'Align center',
          'JustifyCenter'
        ],
        alignright: [
          'Align right',
          'JustifyRight'
        ],
        alignjustify: [
          'Justify',
          'JustifyFull'
        ],
        alignnone: [
          'No alignment',
          'JustifyNone'
        ]
      }, function (item, name) {
        editor.addButton(name, {
          active: false,
          tooltip: item[0],
          cmd: item[1],
          onPostRender: postRenderFormatToggle(editor, name)
        });
      });
    };
    var Align = { register: register };

    var getFirstFont = function (fontFamily) {
      return fontFamily ? fontFamily.split(',')[0] : '';
    };
    var findMatchingValue = function (items, fontFamily) {
      var font = fontFamily ? fontFamily.toLowerCase() : '';
      var value;
      global$2.each(items, function (item) {
        if (item.value.toLowerCase() === font) {
          value = item.value;
        }
      });
      global$2.each(items, function (item) {
        if (!value && getFirstFont(item.value).toLowerCase() === getFirstFont(font).toLowerCase()) {
          value = item.value;
        }
      });
      return value;
    };
    var createFontNameListBoxChangeHandler = function (editor, items) {
      return function () {
        var self = this;
        self.state.set('value', null);
        editor.on('init nodeChange', function (e) {
          var fontFamily = editor.queryCommandValue('FontName');
          var match = findMatchingValue(items, fontFamily);
          self.value(match ? match : null);
          if (!match && fontFamily) {
            self.text(getFirstFont(fontFamily));
          }
        });
      };
    };
    var createFormats = function (formats) {
      formats = formats.replace(/;$/, '').split(';');
      var i = formats.length;
      while (i--) {
        formats[i] = formats[i].split('=');
      }
      return formats;
    };
    var getFontItems = function (editor) {
      var defaultFontsFormats = 'Andale Mono=andale mono,monospace;' + 'Arial=arial,helvetica,sans-serif;' + 'Arial Black=arial black,sans-serif;' + 'Book Antiqua=book antiqua,palatino,serif;' + 'Comic Sans MS=comic sans ms,sans-serif;' + 'Courier New=courier new,courier,monospace;' + 'Georgia=georgia,palatino,serif;' + 'Helvetica=helvetica,arial,sans-serif;' + 'Impact=impact,sans-serif;' + 'Symbol=symbol;' + 'Tahoma=tahoma,arial,helvetica,sans-serif;' + 'Terminal=terminal,monaco,monospace;' + 'Times New Roman=times new roman,times,serif;' + 'Trebuchet MS=trebuchet ms,geneva,sans-serif;' + 'Verdana=verdana,geneva,sans-serif;' + 'Webdings=webdings;' + 'Wingdings=wingdings,zapf dingbats';
      var fonts = createFormats(editor.settings.font_formats || defaultFontsFormats);
      return global$2.map(fonts, function (font) {
        return {
          text: { raw: font[0] },
          value: font[1],
          textStyle: font[1].indexOf('dings') === -1 ? 'font-family:' + font[1] : ''
        };
      });
    };
    var registerButtons = function (editor) {
      editor.addButton('fontselect', function () {
        var items = getFontItems(editor);
        return {
          type: 'listbox',
          text: 'Font Family',
          tooltip: 'Font Family',
          values: items,
          fixedWidth: true,
          onPostRender: createFontNameListBoxChangeHandler(editor, items),
          onselect: function (e) {
            if (e.control.settings.value) {
              editor.execCommand('FontName', false, e.control.settings.value);
            }
          }
        };
      });
    };
    var register$1 = function (editor) {
      registerButtons(editor);
    };
    var FontSelect = { register: register$1 };

    var round = function (number, precision) {
      var factor = Math.pow(10, precision);
      return Math.round(number * factor) / factor;
    };
    var toPt = function (fontSize, precision) {
      if (/[0-9.]+px$/.test(fontSize)) {
        return round(parseInt(fontSize, 10) * 72 / 96, precision || 0) + 'pt';
      }
      return fontSize;
    };
    var findMatchingValue$1 = function (items, pt, px) {
      var value;
      global$2.each(items, function (item) {
        if (item.value === px) {
          value = px;
        } else if (item.value === pt) {
          value = pt;
        }
      });
      return value;
    };
    var createFontSizeListBoxChangeHandler = function (editor, items) {
      return function () {
        var self = this;
        editor.on('init nodeChange', function (e) {
          var px, pt, precision, match;
          px = editor.queryCommandValue('FontSize');
          if (px) {
            for (precision = 3; !match && precision >= 0; precision--) {
              pt = toPt(px, precision);
              match = findMatchingValue$1(items, pt, px);
            }
          }
          self.value(match ? match : null);
          if (!match) {
            self.text(pt);
          }
        });
      };
    };
    var getFontSizeItems = function (editor) {
      var defaultFontsizeFormats = '8pt 10pt 12pt 14pt 18pt 24pt 36pt';
      var fontsizeFormats = editor.settings.fontsize_formats || defaultFontsizeFormats;
      return global$2.map(fontsizeFormats.split(' '), function (item) {
        var text = item, value = item;
        var values = item.split('=');
        if (values.length > 1) {
          text = values[0];
          value = values[1];
        }
        return {
          text: text,
          value: value
        };
      });
    };
    var registerButtons$1 = function (editor) {
      editor.addButton('fontsizeselect', function () {
        var items = getFontSizeItems(editor);
        return {
          type: 'listbox',
          text: 'Font Sizes',
          tooltip: 'Font Sizes',
          values: items,
          fixedWidth: true,
          onPostRender: createFontSizeListBoxChangeHandler(editor, items),
          onclick: function (e) {
            if (e.control.settings.value) {
              editor.execCommand('FontSize', false, e.control.settings.value);
            }
          }
        };
      });
    };
    var register$2 = function (editor) {
      registerButtons$1(editor);
    };
    var FontSizeSelect = { register: register$2 };

    var hideMenuObjects = function (editor, menu) {
      var count = menu.length;
      global$2.each(menu, function (item) {
        if (item.menu) {
          item.hidden = hideMenuObjects(editor, item.menu) === 0;
        }
        var formatName = item.format;
        if (formatName) {
          item.hidden = !editor.formatter.canApply(formatName);
        }
        if (item.hidden) {
          count--;
        }
      });
      return count;
    };
    var hideFormatMenuItems = function (editor, menu) {
      var count = menu.items().length;
      menu.items().each(function (item) {
        if (item.menu) {
          item.visible(hideFormatMenuItems(editor, item.menu) > 0);
        }
        if (!item.menu && item.settings.menu) {
          item.visible(hideMenuObjects(editor, item.settings.menu) > 0);
        }
        var formatName = item.settings.format;
        if (formatName) {
          item.visible(editor.formatter.canApply(formatName));
        }
        if (!item.visible()) {
          count--;
        }
      });
      return count;
    };
    var createFormatMenu = function (editor) {
      var count = 0;
      var newFormats = [];
      var defaultStyleFormats = [
        {
          title: 'Headings',
          items: [
            {
              title: 'Heading 1',
              format: 'h1'
            },
            {
              title: 'Heading 2',
              format: 'h2'
            },
            {
              title: 'Heading 3',
              format: 'h3'
            },
            {
              title: 'Heading 4',
              format: 'h4'
            },
            {
              title: 'Heading 5',
              format: 'h5'
            },
            {
              title: 'Heading 6',
              format: 'h6'
            }
          ]
        },
        {
          title: 'Inline',
          items: [
            {
              title: 'Bold',
              icon: 'bold',
              format: 'bold'
            },
            {
              title: 'Italic',
              icon: 'italic',
              format: 'italic'
            },
            {
              title: 'Underline',
              icon: 'underline',
              format: 'underline'
            },
            {
              title: 'Strikethrough',
              icon: 'strikethrough',
              format: 'strikethrough'
            },
            {
              title: 'Superscript',
              icon: 'superscript',
              format: 'superscript'
            },
            {
              title: 'Subscript',
              icon: 'subscript',
              format: 'subscript'
            },
            {
              title: 'Code',
              icon: 'code',
              format: 'code'
            }
          ]
        },
        {
          title: 'Blocks',
          items: [
            {
              title: 'Paragraph',
              format: 'p'
            },
            {
              title: 'Blockquote',
              format: 'blockquote'
            },
            {
              title: 'Div',
              format: 'div'
            },
            {
              title: 'Pre',
              format: 'pre'
            }
          ]
        },
        {
          title: 'Alignment',
          items: [
            {
              title: 'Left',
              icon: 'alignleft',
              format: 'alignleft'
            },
            {
              title: 'Center',
              icon: 'aligncenter',
              format: 'aligncenter'
            },
            {
              title: 'Right',
              icon: 'alignright',
              format: 'alignright'
            },
            {
              title: 'Justify',
              icon: 'alignjustify',
              format: 'alignjustify'
            }
          ]
        }
      ];
      var createMenu = function (formats) {
        var menu = [];
        if (!formats) {
          return;
        }
        global$2.each(formats, function (format) {
          var menuItem = {
            text: format.title,
            icon: format.icon
          };
          if (format.items) {
            menuItem.menu = createMenu(format.items);
          } else {
            var formatName = format.format || 'custom' + count++;
            if (!format.format) {
              format.name = formatName;
              newFormats.push(format);
            }
            menuItem.format = formatName;
            menuItem.cmd = format.cmd;
          }
          menu.push(menuItem);
        });
        return menu;
      };
      var createStylesMenu = function () {
        var menu;
        if (editor.settings.style_formats_merge) {
          if (editor.settings.style_formats) {
            menu = createMenu(defaultStyleFormats.concat(editor.settings.style_formats));
          } else {
            menu = createMenu(defaultStyleFormats);
          }
        } else {
          menu = createMenu(editor.settings.style_formats || defaultStyleFormats);
        }
        return menu;
      };
      editor.on('init', function () {
        global$2.each(newFormats, function (format) {
          editor.formatter.register(format.name, format);
        });
      });
      return {
        type: 'menu',
        items: createStylesMenu(),
        onPostRender: function (e) {
          editor.fire('renderFormatsMenu', { control: e.control });
        },
        itemDefaults: {
          preview: true,
          textStyle: function () {
            if (this.settings.format) {
              return editor.formatter.getCssText(this.settings.format);
            }
          },
          onPostRender: function () {
            var self = this;
            self.parent().on('show', function () {
              var formatName, command;
              formatName = self.settings.format;
              if (formatName) {
                self.disabled(!editor.formatter.canApply(formatName));
                self.active(editor.formatter.match(formatName));
              }
              command = self.settings.cmd;
              if (command) {
                self.active(editor.queryCommandState(command));
              }
            });
          },
          onclick: function () {
            if (this.settings.format) {
              toggleFormat(editor, this.settings.format)();
            }
            if (this.settings.cmd) {
              editor.execCommand(this.settings.cmd);
            }
          }
        }
      };
    };
    var registerMenuItems = function (editor, formatMenu) {
      editor.addMenuItem('formats', {
        text: 'Formats',
        menu: formatMenu
      });
    };
    var registerButtons$2 = function (editor, formatMenu) {
      editor.addButton('styleselect', {
        type: 'menubutton',
        text: 'Formats',
        menu: formatMenu,
        onShowMenu: function () {
          if (editor.settings.style_formats_autohide) {
            hideFormatMenuItems(editor, this.menu);
          }
        }
      });
    };
    var register$3 = function (editor) {
      var formatMenu = createFormatMenu(editor);
      registerMenuItems(editor, formatMenu);
      registerButtons$2(editor, formatMenu);
    };
    var Formats = { register: register$3 };

    var defaultBlocks = 'Paragraph=p;' + 'Heading 1=h1;' + 'Heading 2=h2;' + 'Heading 3=h3;' + 'Heading 4=h4;' + 'Heading 5=h5;' + 'Heading 6=h6;' + 'Preformatted=pre';
    var createFormats$1 = function (formats) {
      formats = formats.replace(/;$/, '').split(';');
      var i = formats.length;
      while (i--) {
        formats[i] = formats[i].split('=');
      }
      return formats;
    };
    var createListBoxChangeHandler = function (editor, items, formatName) {
      return function () {
        var self = this;
        editor.on('nodeChange', function (e) {
          var formatter = editor.formatter;
          var value = null;
          global$2.each(e.parents, function (node) {
            global$2.each(items, function (item) {
              if (formatName) {
                if (formatter.matchNode(node, formatName, { value: item.value })) {
                  value = item.value;
                }
              } else {
                if (formatter.matchNode(node, item.value)) {
                  value = item.value;
                }
              }
              if (value) {
                return false;
              }
            });
            if (value) {
              return false;
            }
          });
          self.value(value);
        });
      };
    };
    var lazyFormatSelectBoxItems = function (editor, blocks) {
      return function () {
        var items = [];
        global$2.each(blocks, function (block) {
          items.push({
            text: block[0],
            value: block[1],
            textStyle: function () {
              return editor.formatter.getCssText(block[1]);
            }
          });
        });
        return {
          type: 'listbox',
          text: blocks[0][0],
          values: items,
          fixedWidth: true,
          onselect: function (e) {
            if (e.control) {
              var fmt = e.control.value();
              toggleFormat(editor, fmt)();
            }
          },
          onPostRender: createListBoxChangeHandler(editor, items)
        };
      };
    };
    var buildMenuItems = function (editor, blocks) {
      return global$2.map(blocks, function (block) {
        return {
          text: block[0],
          onclick: toggleFormat(editor, block[1]),
          textStyle: function () {
            return editor.formatter.getCssText(block[1]);
          }
        };
      });
    };
    var register$4 = function (editor) {
      var blocks = createFormats$1(editor.settings.block_formats || defaultBlocks);
      editor.addMenuItem('blockformats', {
        text: 'Blocks',
        menu: buildMenuItems(editor, blocks)
      });
      editor.addButton('formatselect', lazyFormatSelectBoxItems(editor, blocks));
    };
    var FormatSelect = { register: register$4 };

    var createCustomMenuItems = function (editor, names) {
      var items, nameList;
      if (typeof names === 'string') {
        nameList = names.split(' ');
      } else if (global$2.isArray(names)) {
        return flatten(global$2.map(names, function (names) {
          return createCustomMenuItems(editor, names);
        }));
      }
      items = global$2.grep(nameList, function (name) {
        return name === '|' || name in editor.menuItems;
      });
      return global$2.map(items, function (name) {
        return name === '|' ? { text: '-' } : editor.menuItems[name];
      });
    };
    var isSeparator$1 = function (menuItem) {
      return menuItem && menuItem.text === '-';
    };
    var trimMenuItems = function (menuItems) {
      var menuItems2 = filter(menuItems, function (menuItem, i, menuItems) {
        return !isSeparator$1(menuItem) || !isSeparator$1(menuItems[i - 1]);
      });
      return filter(menuItems2, function (menuItem, i, menuItems) {
        return !isSeparator$1(menuItem) || i > 0 && i < menuItems.length - 1;
      });
    };
    var createContextMenuItems = function (editor, context) {
      var outputMenuItems = [{ text: '-' }];
      var menuItems = global$2.grep(editor.menuItems, function (menuItem) {
        return menuItem.context === context;
      });
      global$2.each(menuItems, function (menuItem) {
        if (menuItem.separator === 'before') {
          outputMenuItems.push({ text: '|' });
        }
        if (menuItem.prependToContext) {
          outputMenuItems.unshift(menuItem);
        } else {
          outputMenuItems.push(menuItem);
        }
        if (menuItem.separator === 'after') {
          outputMenuItems.push({ text: '|' });
        }
      });
      return outputMenuItems;
    };
    var createInsertMenu = function (editor) {
      var insertButtonItems = editor.settings.insert_button_items;
      if (insertButtonItems) {
        return trimMenuItems(createCustomMenuItems(editor, insertButtonItems));
      } else {
        return trimMenuItems(createContextMenuItems(editor, 'insert'));
      }
    };
    var registerButtons$3 = function (editor) {
      editor.addButton('insert', {
        type: 'menubutton',
        icon: 'insert',
        menu: [],
        oncreatemenu: function () {
          this.menu.add(createInsertMenu(editor));
          this.menu.renderNew();
        }
      });
    };
    var register$5 = function (editor) {
      registerButtons$3(editor);
    };
    var InsertButton = { register: register$5 };

    var registerFormatButtons = function (editor) {
      global$2.each({
        bold: 'Bold',
        italic: 'Italic',
        underline: 'Underline',
        strikethrough: 'Strikethrough',
        subscript: 'Subscript',
        superscript: 'Superscript'
      }, function (text, name) {
        editor.addButton(name, {
          active: false,
          tooltip: text,
          onPostRender: postRenderFormatToggle(editor, name),
          onclick: toggleFormat(editor, name)
        });
      });
    };
    var registerCommandButtons = function (editor) {
      global$2.each({
        outdent: [
          'Decrease indent',
          'Outdent'
        ],
        indent: [
          'Increase indent',
          'Indent'
        ],
        cut: [
          'Cut',
          'Cut'
        ],
        copy: [
          'Copy',
          'Copy'
        ],
        paste: [
          'Paste',
          'Paste'
        ],
        help: [
          'Help',
          'mceHelp'
        ],
        selectall: [
          'Select all',
          'SelectAll'
        ],
        visualaid: [
          'Visual aids',
          'mceToggleVisualAid'
        ],
        newdocument: [
          'New document',
          'mceNewDocument'
        ],
        removeformat: [
          'Clear formatting',
          'RemoveFormat'
        ],
        remove: [
          'Remove',
          'Delete'
        ]
      }, function (item, name) {
        editor.addButton(name, {
          tooltip: item[0],
          cmd: item[1]
        });
      });
    };
    var registerCommandToggleButtons = function (editor) {
      global$2.each({
        blockquote: [
          'Blockquote',
          'mceBlockQuote'
        ],
        subscript: [
          'Subscript',
          'Subscript'
        ],
        superscript: [
          'Superscript',
          'Superscript'
        ]
      }, function (item, name) {
        editor.addButton(name, {
          active: false,
          tooltip: item[0],
          cmd: item[1],
          onPostRender: postRenderFormatToggle(editor, name)
        });
      });
    };
    var registerButtons$4 = function (editor) {
      registerFormatButtons(editor);
      registerCommandButtons(editor);
      registerCommandToggleButtons(editor);
    };
    var registerMenuItems$1 = function (editor) {
      global$2.each({
        bold: [
          'Bold',
          'Bold',
          'Meta+B'
        ],
        italic: [
          'Italic',
          'Italic',
          'Meta+I'
        ],
        underline: [
          'Underline',
          'Underline',
          'Meta+U'
        ],
        strikethrough: [
          'Strikethrough',
          'Strikethrough'
        ],
        subscript: [
          'Subscript',
          'Subscript'
        ],
        superscript: [
          'Superscript',
          'Superscript'
        ],
        removeformat: [
          'Clear formatting',
          'RemoveFormat'
        ],
        newdocument: [
          'New document',
          'mceNewDocument'
        ],
        cut: [
          'Cut',
          'Cut',
          'Meta+X'
        ],
        copy: [
          'Copy',
          'Copy',
          'Meta+C'
        ],
        paste: [
          'Paste',
          'Paste',
          'Meta+V'
        ],
        selectall: [
          'Select all',
          'SelectAll',
          'Meta+A'
        ]
      }, function (item, name) {
        editor.addMenuItem(name, {
          text: item[0],
          icon: name,
          shortcut: item[2],
          cmd: item[1]
        });
      });
      editor.addMenuItem('codeformat', {
        text: 'Code',
        icon: 'code',
        onclick: toggleFormat(editor, 'code')
      });
    };
    var register$6 = function (editor) {
      registerButtons$4(editor);
      registerMenuItems$1(editor);
    };
    var SimpleControls = { register: register$6 };

    var toggleUndoRedoState = function (editor, type) {
      return function () {
        var self = this;
        var checkState = function () {
          var typeFn = type === 'redo' ? 'hasRedo' : 'hasUndo';
          return editor.undoManager ? editor.undoManager[typeFn]() : false;
        };
        self.disabled(!checkState());
        editor.on('Undo Redo AddUndo TypingUndo ClearUndos SwitchMode', function () {
          self.disabled(editor.readonly || !checkState());
        });
      };
    };
    var registerMenuItems$2 = function (editor) {
      editor.addMenuItem('undo', {
        text: 'Undo',
        icon: 'undo',
        shortcut: 'Meta+Z',
        onPostRender: toggleUndoRedoState(editor, 'undo'),
        cmd: 'undo'
      });
      editor.addMenuItem('redo', {
        text: 'Redo',
        icon: 'redo',
        shortcut: 'Meta+Y',
        onPostRender: toggleUndoRedoState(editor, 'redo'),
        cmd: 'redo'
      });
    };
    var registerButtons$5 = function (editor) {
      editor.addButton('undo', {
        tooltip: 'Undo',
        onPostRender: toggleUndoRedoState(editor, 'undo'),
        cmd: 'undo'
      });
      editor.addButton('redo', {
        tooltip: 'Redo',
        onPostRender: toggleUndoRedoState(editor, 'redo'),
        cmd: 'redo'
      });
    };
    var register$7 = function (editor) {
      registerMenuItems$2(editor);
      registerButtons$5(editor);
    };
    var UndoRedo = { register: register$7 };

    var toggleVisualAidState = function (editor) {
      return function () {
        var self = this;
        editor.on('VisualAid', function (e) {
          self.active(e.hasVisual);
        });
        self.active(editor.hasVisual);
      };
    };
    var registerMenuItems$3 = function (editor) {
      editor.addMenuItem('visualaid', {
        text: 'Visual aids',
        selectable: true,
        onPostRender: toggleVisualAidState(editor),
        cmd: 'mceToggleVisualAid'
      });
    };
    var register$8 = function (editor) {
      registerMenuItems$3(editor);
    };
    var VisualAid = { register: register$8 };

    var setupEnvironment = function () {
      Widget.tooltips = !global$8.iOS;
      Control$1.translate = function (text) {
        return global$1.translate(text);
      };
    };
    var setupUiContainer = function (editor) {
      if (editor.settings.ui_container) {
        global$8.container = descendant$1(Element$$1.fromDom(document.body), editor.settings.ui_container).fold(constant(null), function (elm) {
          return elm.dom();
        });
      }
    };
    var setupRtlMode = function (editor) {
      if (editor.rtl) {
        Control$1.rtl = true;
      }
    };
    var setupHideFloatPanels = function (editor) {
      editor.on('mousedown progressstate', function () {
        FloatPanel.hideAll();
      });
    };
    var setup$1 = function (editor) {
      setupRtlMode(editor);
      setupHideFloatPanels(editor);
      setupUiContainer(editor);
      setupEnvironment();
      FormatSelect.register(editor);
      Align.register(editor);
      SimpleControls.register(editor);
      UndoRedo.register(editor);
      FontSizeSelect.register(editor);
      FontSelect.register(editor);
      Formats.register(editor);
      VisualAid.register(editor);
      InsertButton.register(editor);
    };
    var FormatControls = { setup: setup$1 };

    var GridLayout = AbsoluteLayout.extend({
      recalc: function (container) {
        var settings, rows, cols, items, contLayoutRect, width, height, rect, ctrlLayoutRect, ctrl, x, y, posX, posY, ctrlSettings, contPaddingBox, align, spacingH, spacingV, alignH, alignV, maxX, maxY;
        var colWidths = [];
        var rowHeights = [];
        var ctrlMinWidth, ctrlMinHeight, availableWidth, availableHeight, reverseRows, idx;
        settings = container.settings;
        items = container.items().filter(':visible');
        contLayoutRect = container.layoutRect();
        cols = settings.columns || Math.ceil(Math.sqrt(items.length));
        rows = Math.ceil(items.length / cols);
        spacingH = settings.spacingH || settings.spacing || 0;
        spacingV = settings.spacingV || settings.spacing || 0;
        alignH = settings.alignH || settings.align;
        alignV = settings.alignV || settings.align;
        contPaddingBox = container.paddingBox;
        reverseRows = 'reverseRows' in settings ? settings.reverseRows : container.isRtl();
        if (alignH && typeof alignH === 'string') {
          alignH = [alignH];
        }
        if (alignV && typeof alignV === 'string') {
          alignV = [alignV];
        }
        for (x = 0; x < cols; x++) {
          colWidths.push(0);
        }
        for (y = 0; y < rows; y++) {
          rowHeights.push(0);
        }
        for (y = 0; y < rows; y++) {
          for (x = 0; x < cols; x++) {
            ctrl = items[y * cols + x];
            if (!ctrl) {
              break;
            }
            ctrlLayoutRect = ctrl.layoutRect();
            ctrlMinWidth = ctrlLayoutRect.minW;
            ctrlMinHeight = ctrlLayoutRect.minH;
            colWidths[x] = ctrlMinWidth > colWidths[x] ? ctrlMinWidth : colWidths[x];
            rowHeights[y] = ctrlMinHeight > rowHeights[y] ? ctrlMinHeight : rowHeights[y];
          }
        }
        availableWidth = contLayoutRect.innerW - contPaddingBox.left - contPaddingBox.right;
        for (maxX = 0, x = 0; x < cols; x++) {
          maxX += colWidths[x] + (x > 0 ? spacingH : 0);
          availableWidth -= (x > 0 ? spacingH : 0) + colWidths[x];
        }
        availableHeight = contLayoutRect.innerH - contPaddingBox.top - contPaddingBox.bottom;
        for (maxY = 0, y = 0; y < rows; y++) {
          maxY += rowHeights[y] + (y > 0 ? spacingV : 0);
          availableHeight -= (y > 0 ? spacingV : 0) + rowHeights[y];
        }
        maxX += contPaddingBox.left + contPaddingBox.right;
        maxY += contPaddingBox.top + contPaddingBox.bottom;
        rect = {};
        rect.minW = maxX + (contLayoutRect.w - contLayoutRect.innerW);
        rect.minH = maxY + (contLayoutRect.h - contLayoutRect.innerH);
        rect.contentW = rect.minW - contLayoutRect.deltaW;
        rect.contentH = rect.minH - contLayoutRect.deltaH;
        rect.minW = Math.min(rect.minW, contLayoutRect.maxW);
        rect.minH = Math.min(rect.minH, contLayoutRect.maxH);
        rect.minW = Math.max(rect.minW, contLayoutRect.startMinWidth);
        rect.minH = Math.max(rect.minH, contLayoutRect.startMinHeight);
        if (contLayoutRect.autoResize && (rect.minW !== contLayoutRect.minW || rect.minH !== contLayoutRect.minH)) {
          rect.w = rect.minW;
          rect.h = rect.minH;
          container.layoutRect(rect);
          this.recalc(container);
          if (container._lastRect === null) {
            var parentCtrl = container.parent();
            if (parentCtrl) {
              parentCtrl._lastRect = null;
              parentCtrl.recalc();
            }
          }
          return;
        }
        if (contLayoutRect.autoResize) {
          rect = container.layoutRect(rect);
          rect.contentW = rect.minW - contLayoutRect.deltaW;
          rect.contentH = rect.minH - contLayoutRect.deltaH;
        }
        var flexV;
        if (settings.packV === 'start') {
          flexV = 0;
        } else {
          flexV = availableHeight > 0 ? Math.floor(availableHeight / rows) : 0;
        }
        var totalFlex = 0;
        var flexWidths = settings.flexWidths;
        if (flexWidths) {
          for (x = 0; x < flexWidths.length; x++) {
            totalFlex += flexWidths[x];
          }
        } else {
          totalFlex = cols;
        }
        var ratio = availableWidth / totalFlex;
        for (x = 0; x < cols; x++) {
          colWidths[x] += flexWidths ? flexWidths[x] * ratio : ratio;
        }
        posY = contPaddingBox.top;
        for (y = 0; y < rows; y++) {
          posX = contPaddingBox.left;
          height = rowHeights[y] + flexV;
          for (x = 0; x < cols; x++) {
            if (reverseRows) {
              idx = y * cols + cols - 1 - x;
            } else {
              idx = y * cols + x;
            }
            ctrl = items[idx];
            if (!ctrl) {
              break;
            }
            ctrlSettings = ctrl.settings;
            ctrlLayoutRect = ctrl.layoutRect();
            width = Math.max(colWidths[x], ctrlLayoutRect.startMinWidth);
            ctrlLayoutRect.x = posX;
            ctrlLayoutRect.y = posY;
            align = ctrlSettings.alignH || (alignH ? alignH[x] || alignH[0] : null);
            if (align === 'center') {
              ctrlLayoutRect.x = posX + width / 2 - ctrlLayoutRect.w / 2;
            } else if (align === 'right') {
              ctrlLayoutRect.x = posX + width - ctrlLayoutRect.w;
            } else if (align === 'stretch') {
              ctrlLayoutRect.w = width;
            }
            align = ctrlSettings.alignV || (alignV ? alignV[x] || alignV[0] : null);
            if (align === 'center') {
              ctrlLayoutRect.y = posY + height / 2 - ctrlLayoutRect.h / 2;
            } else if (align === 'bottom') {
              ctrlLayoutRect.y = posY + height - ctrlLayoutRect.h;
            } else if (align === 'stretch') {
              ctrlLayoutRect.h = height;
            }
            ctrl.layoutRect(ctrlLayoutRect);
            posX += width + spacingH;
            if (ctrl.recalc) {
              ctrl.recalc();
            }
          }
          posY += height + spacingV;
        }
      }
    });

    var Iframe$1 = Widget.extend({
      renderHtml: function () {
        var self = this;
        self.classes.add('iframe');
        self.canFocus = false;
        return '<iframe id="' + self._id + '" class="' + self.classes + '" tabindex="-1" src="' + (self.settings.url || 'javascript:\'\'') + '" frameborder="0"></iframe>';
      },
      src: function (src) {
        this.getEl().src = src;
      },
      html: function (html, callback) {
        var self = this, body = this.getEl().contentWindow.document.body;
        if (!body) {
          global$7.setTimeout(function () {
            self.html(html);
          });
        } else {
          body.innerHTML = html;
          if (callback) {
            callback();
          }
        }
        return this;
      }
    });

    var InfoBox = Widget.extend({
      init: function (settings) {
        var self = this;
        self._super(settings);
        self.classes.add('widget').add('infobox');
        self.canFocus = false;
      },
      severity: function (level) {
        this.classes.remove('error');
        this.classes.remove('warning');
        this.classes.remove('success');
        this.classes.add(level);
      },
      help: function (state) {
        this.state.set('help', state);
      },
      renderHtml: function () {
        var self = this, prefix = self.classPrefix;
        return '<div id="' + self._id + '" class="' + self.classes + '">' + '<div id="' + self._id + '-body">' + self.encode(self.state.get('text')) + '<button role="button" tabindex="-1">' + '<i class="' + prefix + 'ico ' + prefix + 'i-help"></i>' + '</button>' + '</div>' + '</div>';
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:text', function (e) {
          self.getEl('body').firstChild.data = self.encode(e.value);
          if (self.state.get('rendered')) {
            self.updateLayoutRect();
          }
        });
        self.state.on('change:help', function (e) {
          self.classes.toggle('has-help', e.value);
          if (self.state.get('rendered')) {
            self.updateLayoutRect();
          }
        });
        return self._super();
      }
    });

    var Label = Widget.extend({
      init: function (settings) {
        var self = this;
        self._super(settings);
        self.classes.add('widget').add('label');
        self.canFocus = false;
        if (settings.multiline) {
          self.classes.add('autoscroll');
        }
        if (settings.strong) {
          self.classes.add('strong');
        }
      },
      initLayoutRect: function () {
        var self = this, layoutRect = self._super();
        if (self.settings.multiline) {
          var size = funcs.getSize(self.getEl());
          if (size.width > layoutRect.maxW) {
            layoutRect.minW = layoutRect.maxW;
            self.classes.add('multiline');
          }
          self.getEl().style.width = layoutRect.minW + 'px';
          layoutRect.startMinH = layoutRect.h = layoutRect.minH = Math.min(layoutRect.maxH, funcs.getSize(self.getEl()).height);
        }
        return layoutRect;
      },
      repaint: function () {
        var self = this;
        if (!self.settings.multiline) {
          self.getEl().style.lineHeight = self.layoutRect().h + 'px';
        }
        return self._super();
      },
      severity: function (level) {
        this.classes.remove('error');
        this.classes.remove('warning');
        this.classes.remove('success');
        this.classes.add(level);
      },
      renderHtml: function () {
        var self = this;
        var targetCtrl, forName, forId = self.settings.forId;
        var text = self.settings.html ? self.settings.html : self.encode(self.state.get('text'));
        if (!forId && (forName = self.settings.forName)) {
          targetCtrl = self.getRoot().find('#' + forName)[0];
          if (targetCtrl) {
            forId = targetCtrl._id;
          }
        }
        if (forId) {
          return '<label id="' + self._id + '" class="' + self.classes + '"' + (forId ? ' for="' + forId + '"' : '') + '>' + text + '</label>';
        }
        return '<span id="' + self._id + '" class="' + self.classes + '">' + text + '</span>';
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:text', function (e) {
          self.innerHtml(self.encode(e.value));
          if (self.state.get('rendered')) {
            self.updateLayoutRect();
          }
        });
        return self._super();
      }
    });

    var Toolbar$1 = Container.extend({
      Defaults: {
        role: 'toolbar',
        layout: 'flow'
      },
      init: function (settings) {
        var self = this;
        self._super(settings);
        self.classes.add('toolbar');
      },
      postRender: function () {
        var self = this;
        self.items().each(function (ctrl) {
          ctrl.classes.add('toolbar-item');
        });
        return self._super();
      }
    });

    var MenuBar = Toolbar$1.extend({
      Defaults: {
        role: 'menubar',
        containerCls: 'menubar',
        ariaRoot: true,
        defaults: { type: 'menubutton' }
      }
    });

    function isChildOf$1(node, parent$$1) {
      while (node) {
        if (parent$$1 === node) {
          return true;
        }
        node = node.parentNode;
      }
      return false;
    }
    var MenuButton = Button.extend({
      init: function (settings) {
        var self$$1 = this;
        self$$1._renderOpen = true;
        self$$1._super(settings);
        settings = self$$1.settings;
        self$$1.classes.add('menubtn');
        if (settings.fixedWidth) {
          self$$1.classes.add('fixed-width');
        }
        self$$1.aria('haspopup', true);
        self$$1.state.set('menu', settings.menu || self$$1.render());
      },
      showMenu: function (toggle) {
        var self$$1 = this;
        var menu;
        if (self$$1.menu && self$$1.menu.visible() && toggle !== false) {
          return self$$1.hideMenu();
        }
        if (!self$$1.menu) {
          menu = self$$1.state.get('menu') || [];
          self$$1.classes.add('opened');
          if (menu.length) {
            menu = {
              type: 'menu',
              animate: true,
              items: menu
            };
          } else {
            menu.type = menu.type || 'menu';
            menu.animate = true;
          }
          if (!menu.renderTo) {
            self$$1.menu = global$4.create(menu).parent(self$$1).renderTo();
          } else {
            self$$1.menu = menu.parent(self$$1).show().renderTo();
          }
          self$$1.fire('createmenu');
          self$$1.menu.reflow();
          self$$1.menu.on('cancel', function (e) {
            if (e.control.parent() === self$$1.menu) {
              e.stopPropagation();
              self$$1.focus();
              self$$1.hideMenu();
            }
          });
          self$$1.menu.on('select', function () {
            self$$1.focus();
          });
          self$$1.menu.on('show hide', function (e) {
            if (e.type === 'hide' && e.control.parent() === self$$1) {
              self$$1.classes.remove('opened-under');
            }
            if (e.control === self$$1.menu) {
              self$$1.activeMenu(e.type === 'show');
              self$$1.classes.toggle('opened', e.type === 'show');
            }
            self$$1.aria('expanded', e.type === 'show');
          }).fire('show');
        }
        self$$1.menu.show();
        self$$1.menu.layoutRect({ w: self$$1.layoutRect().w });
        self$$1.menu.repaint();
        self$$1.menu.moveRel(self$$1.getEl(), self$$1.isRtl() ? [
          'br-tr',
          'tr-br'
        ] : [
          'bl-tl',
          'tl-bl'
        ]);
        var menuLayoutRect = self$$1.menu.layoutRect();
        var selfBottom = self$$1.$el.offset().top + self$$1.layoutRect().h;
        if (selfBottom > menuLayoutRect.y && selfBottom < menuLayoutRect.y + menuLayoutRect.h) {
          self$$1.classes.add('opened-under');
        }
        self$$1.fire('showmenu');
      },
      hideMenu: function () {
        var self$$1 = this;
        if (self$$1.menu) {
          self$$1.menu.items().each(function (item) {
            if (item.hideMenu) {
              item.hideMenu();
            }
          });
          self$$1.menu.hide();
        }
      },
      activeMenu: function (state) {
        this.classes.toggle('active', state);
      },
      renderHtml: function () {
        var self$$1 = this, id = self$$1._id, prefix = self$$1.classPrefix;
        var icon = self$$1.settings.icon, image;
        var text = self$$1.state.get('text');
        var textHtml = '';
        image = self$$1.settings.image;
        if (image) {
          icon = 'none';
          if (typeof image !== 'string') {
            image = window.getSelection ? image[0] : image[1];
          }
          image = ' style="background-image: url(\'' + image + '\')"';
        } else {
          image = '';
        }
        if (text) {
          self$$1.classes.add('btn-has-text');
          textHtml = '<span class="' + prefix + 'txt">' + self$$1.encode(text) + '</span>';
        }
        icon = self$$1.settings.icon ? prefix + 'ico ' + prefix + 'i-' + icon : '';
        self$$1.aria('role', self$$1.parent() instanceof MenuBar ? 'menuitem' : 'button');
        return '<div id="' + id + '" class="' + self$$1.classes + '" tabindex="-1" aria-labelledby="' + id + '">' + '<button id="' + id + '-open" role="presentation" type="button" tabindex="-1">' + (icon ? '<i class="' + icon + '"' + image + '></i>' : '') + textHtml + ' <i class="' + prefix + 'caret"></i>' + '</button>' + '</div>';
      },
      postRender: function () {
        var self$$1 = this;
        self$$1.on('click', function (e) {
          if (e.control === self$$1 && isChildOf$1(e.target, self$$1.getEl())) {
            self$$1.focus();
            self$$1.showMenu(!e.aria);
            if (e.aria) {
              self$$1.menu.items().filter(':visible')[0].focus();
            }
          }
        });
        self$$1.on('mouseenter', function (e) {
          var overCtrl = e.control;
          var parent$$1 = self$$1.parent();
          var hasVisibleSiblingMenu;
          if (overCtrl && parent$$1 && overCtrl instanceof MenuButton && overCtrl.parent() === parent$$1) {
            parent$$1.items().filter('MenuButton').each(function (ctrl) {
              if (ctrl.hideMenu && ctrl !== overCtrl) {
                if (ctrl.menu && ctrl.menu.visible()) {
                  hasVisibleSiblingMenu = true;
                }
                ctrl.hideMenu();
              }
            });
            if (hasVisibleSiblingMenu) {
              overCtrl.focus();
              overCtrl.showMenu();
            }
          }
        });
        return self$$1._super();
      },
      bindStates: function () {
        var self$$1 = this;
        self$$1.state.on('change:menu', function () {
          if (self$$1.menu) {
            self$$1.menu.remove();
          }
          self$$1.menu = null;
        });
        return self$$1._super();
      },
      remove: function () {
        this._super();
        if (this.menu) {
          this.menu.remove();
        }
      }
    });

    var Menu = FloatPanel.extend({
      Defaults: {
        defaultType: 'menuitem',
        border: 1,
        layout: 'stack',
        role: 'application',
        bodyRole: 'menu',
        ariaRoot: true
      },
      init: function (settings) {
        var self = this;
        settings.autohide = true;
        settings.constrainToViewport = true;
        if (typeof settings.items === 'function') {
          settings.itemsFactory = settings.items;
          settings.items = [];
        }
        if (settings.itemDefaults) {
          var items = settings.items;
          var i = items.length;
          while (i--) {
            items[i] = global$2.extend({}, settings.itemDefaults, items[i]);
          }
        }
        self._super(settings);
        self.classes.add('menu');
        if (settings.animate && global$8.ie !== 11) {
          self.classes.add('animate');
        }
      },
      repaint: function () {
        this.classes.toggle('menu-align', true);
        this._super();
        this.getEl().style.height = '';
        this.getEl('body').style.height = '';
        return this;
      },
      cancel: function () {
        var self = this;
        self.hideAll();
        self.fire('select');
      },
      load: function () {
        var self = this;
        var time, factory;
        function hideThrobber() {
          if (self.throbber) {
            self.throbber.hide();
            self.throbber = null;
          }
        }
        factory = self.settings.itemsFactory;
        if (!factory) {
          return;
        }
        if (!self.throbber) {
          self.throbber = new Throbber(self.getEl('body'), true);
          if (self.items().length === 0) {
            self.throbber.show();
            self.fire('loading');
          } else {
            self.throbber.show(100, function () {
              self.items().remove();
              self.fire('loading');
            });
          }
          self.on('hide close', hideThrobber);
        }
        self.requestTime = time = new Date().getTime();
        self.settings.itemsFactory(function (items) {
          if (items.length === 0) {
            self.hide();
            return;
          }
          if (self.requestTime !== time) {
            return;
          }
          self.getEl().style.width = '';
          self.getEl('body').style.width = '';
          hideThrobber();
          self.items().remove();
          self.getEl('body').innerHTML = '';
          self.add(items);
          self.renderNew();
          self.fire('loaded');
        });
      },
      hideAll: function () {
        var self = this;
        this.find('menuitem').exec('hideMenu');
        return self._super();
      },
      preRender: function () {
        var self = this;
        self.items().each(function (ctrl) {
          var settings = ctrl.settings;
          if (settings.icon || settings.image || settings.selectable) {
            self._hasIcons = true;
            return false;
          }
        });
        if (self.settings.itemsFactory) {
          self.on('postrender', function () {
            if (self.settings.itemsFactory) {
              self.load();
            }
          });
        }
        self.on('show hide', function (e) {
          if (e.control === self) {
            if (e.type === 'show') {
              global$7.setTimeout(function () {
                self.classes.add('in');
              }, 0);
            } else {
              self.classes.remove('in');
            }
          }
        });
        return self._super();
      }
    });

    var ListBox = MenuButton.extend({
      init: function (settings) {
        var self = this;
        var values, selected, selectedText, lastItemCtrl;
        function setSelected(menuValues) {
          for (var i = 0; i < menuValues.length; i++) {
            selected = menuValues[i].selected || settings.value === menuValues[i].value;
            if (selected) {
              selectedText = selectedText || menuValues[i].text;
              self.state.set('value', menuValues[i].value);
              return true;
            }
            if (menuValues[i].menu) {
              if (setSelected(menuValues[i].menu)) {
                return true;
              }
            }
          }
        }
        self._super(settings);
        settings = self.settings;
        self._values = values = settings.values;
        if (values) {
          if (typeof settings.value !== 'undefined') {
            setSelected(values);
          }
          if (!selected && values.length > 0) {
            selectedText = values[0].text;
            self.state.set('value', values[0].value);
          }
          self.state.set('menu', values);
        }
        self.state.set('text', settings.text || selectedText);
        self.classes.add('listbox');
        self.on('select', function (e) {
          var ctrl = e.control;
          if (lastItemCtrl) {
            e.lastControl = lastItemCtrl;
          }
          if (settings.multiple) {
            ctrl.active(!ctrl.active());
          } else {
            self.value(e.control.value());
          }
          lastItemCtrl = ctrl;
        });
      },
      value: function (value) {
        if (arguments.length === 0) {
          return this.state.get('value');
        }
        if (typeof value === 'undefined') {
          return this;
        }
        function valueExists(values) {
          return exists(values, function (a) {
            return a.menu ? valueExists(a.menu) : a.value === value;
          });
        }
        if (this.settings.values) {
          if (valueExists(this.settings.values)) {
            this.state.set('value', value);
          } else if (value === null) {
            this.state.set('value', null);
          }
        } else {
          this.state.set('value', value);
        }
        return this;
      },
      bindStates: function () {
        var self = this;
        function activateMenuItemsByValue(menu, value) {
          if (menu instanceof Menu) {
            menu.items().each(function (ctrl) {
              if (!ctrl.hasMenus()) {
                ctrl.active(ctrl.value() === value);
              }
            });
          }
        }
        function getSelectedItem(menuValues, value) {
          var selectedItem;
          if (!menuValues) {
            return;
          }
          for (var i = 0; i < menuValues.length; i++) {
            if (menuValues[i].value === value) {
              return menuValues[i];
            }
            if (menuValues[i].menu) {
              selectedItem = getSelectedItem(menuValues[i].menu, value);
              if (selectedItem) {
                return selectedItem;
              }
            }
          }
        }
        self.on('show', function (e) {
          activateMenuItemsByValue(e.control, self.value());
        });
        self.state.on('change:value', function (e) {
          var selectedItem = getSelectedItem(self.state.get('menu'), e.value);
          if (selectedItem) {
            self.text(selectedItem.text);
          } else {
            self.text(self.settings.text);
          }
        });
        return self._super();
      }
    });

    var toggleTextStyle = function (ctrl, state) {
      var textStyle = ctrl._textStyle;
      if (textStyle) {
        var textElm = ctrl.getEl('text');
        textElm.setAttribute('style', textStyle);
        if (state) {
          textElm.style.color = '';
          textElm.style.backgroundColor = '';
        }
      }
    };
    var MenuItem = Widget.extend({
      Defaults: {
        border: 0,
        role: 'menuitem'
      },
      init: function (settings) {
        var self = this;
        var text;
        self._super(settings);
        settings = self.settings;
        self.classes.add('menu-item');
        if (settings.menu) {
          self.classes.add('menu-item-expand');
        }
        if (settings.preview) {
          self.classes.add('menu-item-preview');
        }
        text = self.state.get('text');
        if (text === '-' || text === '|') {
          self.classes.add('menu-item-sep');
          self.aria('role', 'separator');
          self.state.set('text', '-');
        }
        if (settings.selectable) {
          self.aria('role', 'menuitemcheckbox');
          self.classes.add('menu-item-checkbox');
          settings.icon = 'selected';
        }
        if (!settings.preview && !settings.selectable) {
          self.classes.add('menu-item-normal');
        }
        self.on('mousedown', function (e) {
          e.preventDefault();
        });
        if (settings.menu && !settings.ariaHideMenu) {
          self.aria('haspopup', true);
        }
      },
      hasMenus: function () {
        return !!this.settings.menu;
      },
      showMenu: function () {
        var self = this;
        var settings = self.settings;
        var menu;
        var parent = self.parent();
        parent.items().each(function (ctrl) {
          if (ctrl !== self) {
            ctrl.hideMenu();
          }
        });
        if (settings.menu) {
          menu = self.menu;
          if (!menu) {
            menu = settings.menu;
            if (menu.length) {
              menu = {
                type: 'menu',
                items: menu
              };
            } else {
              menu.type = menu.type || 'menu';
            }
            if (parent.settings.itemDefaults) {
              menu.itemDefaults = parent.settings.itemDefaults;
            }
            menu = self.menu = global$4.create(menu).parent(self).renderTo();
            menu.reflow();
            menu.on('cancel', function (e) {
              e.stopPropagation();
              self.focus();
              menu.hide();
            });
            menu.on('show hide', function (e) {
              if (e.control.items) {
                e.control.items().each(function (ctrl) {
                  ctrl.active(ctrl.settings.selected);
                });
              }
            }).fire('show');
            menu.on('hide', function (e) {
              if (e.control === menu) {
                self.classes.remove('selected');
              }
            });
            menu.submenu = true;
          } else {
            menu.show();
          }
          menu._parentMenu = parent;
          menu.classes.add('menu-sub');
          var rel = menu.testMoveRel(self.getEl(), self.isRtl() ? [
            'tl-tr',
            'bl-br',
            'tr-tl',
            'br-bl'
          ] : [
            'tr-tl',
            'br-bl',
            'tl-tr',
            'bl-br'
          ]);
          menu.moveRel(self.getEl(), rel);
          menu.rel = rel;
          rel = 'menu-sub-' + rel;
          menu.classes.remove(menu._lastRel).add(rel);
          menu._lastRel = rel;
          self.classes.add('selected');
          self.aria('expanded', true);
        }
      },
      hideMenu: function () {
        var self = this;
        if (self.menu) {
          self.menu.items().each(function (item) {
            if (item.hideMenu) {
              item.hideMenu();
            }
          });
          self.menu.hide();
          self.aria('expanded', false);
        }
        return self;
      },
      renderHtml: function () {
        var self = this;
        var id = self._id;
        var settings = self.settings;
        var prefix = self.classPrefix;
        var text = self.state.get('text');
        var icon = self.settings.icon, image = '', shortcut = settings.shortcut;
        var url = self.encode(settings.url), iconHtml = '';
        function convertShortcut(shortcut) {
          var i, value, replace = {};
          if (global$8.mac) {
            replace = {
              alt: '&#x2325;',
              ctrl: '&#x2318;',
              shift: '&#x21E7;',
              meta: '&#x2318;'
            };
          } else {
            replace = { meta: 'Ctrl' };
          }
          shortcut = shortcut.split('+');
          for (i = 0; i < shortcut.length; i++) {
            value = replace[shortcut[i].toLowerCase()];
            if (value) {
              shortcut[i] = value;
            }
          }
          return shortcut.join('+');
        }
        function escapeRegExp(str) {
          return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
        function markMatches(text) {
          var match = settings.match || '';
          return match ? text.replace(new RegExp(escapeRegExp(match), 'gi'), function (match) {
            return '!mce~match[' + match + ']mce~match!';
          }) : text;
        }
        function boldMatches(text) {
          return text.replace(new RegExp(escapeRegExp('!mce~match['), 'g'), '<b>').replace(new RegExp(escapeRegExp(']mce~match!'), 'g'), '</b>');
        }
        if (icon) {
          self.parent().classes.add('menu-has-icons');
        }
        if (settings.image) {
          image = ' style="background-image: url(\'' + settings.image + '\')"';
        }
        if (shortcut) {
          shortcut = convertShortcut(shortcut);
        }
        icon = prefix + 'ico ' + prefix + 'i-' + (self.settings.icon || 'none');
        iconHtml = text !== '-' ? '<i class="' + icon + '"' + image + '></i>\xA0' : '';
        text = boldMatches(self.encode(markMatches(text)));
        url = boldMatches(self.encode(markMatches(url)));
        return '<div id="' + id + '" class="' + self.classes + '" tabindex="-1">' + iconHtml + (text !== '-' ? '<span id="' + id + '-text" class="' + prefix + 'text">' + text + '</span>' : '') + (shortcut ? '<div id="' + id + '-shortcut" class="' + prefix + 'menu-shortcut">' + shortcut + '</div>' : '') + (settings.menu ? '<div class="' + prefix + 'caret"></div>' : '') + (url ? '<div class="' + prefix + 'menu-item-link">' + url + '</div>' : '') + '</div>';
      },
      postRender: function () {
        var self = this, settings = self.settings;
        var textStyle = settings.textStyle;
        if (typeof textStyle === 'function') {
          textStyle = textStyle.call(this);
        }
        if (textStyle) {
          var textElm = self.getEl('text');
          if (textElm) {
            textElm.setAttribute('style', textStyle);
            self._textStyle = textStyle;
          }
        }
        self.on('mouseenter click', function (e) {
          if (e.control === self) {
            if (!settings.menu && e.type === 'click') {
              self.fire('select');
              global$7.requestAnimationFrame(function () {
                self.parent().hideAll();
              });
            } else {
              self.showMenu();
              if (e.aria) {
                self.menu.focus(true);
              }
            }
          }
        });
        self._super();
        return self;
      },
      hover: function () {
        var self = this;
        self.parent().items().each(function (ctrl) {
          ctrl.classes.remove('selected');
        });
        self.classes.toggle('selected', true);
        return self;
      },
      active: function (state) {
        toggleTextStyle(this, state);
        if (typeof state !== 'undefined') {
          this.aria('checked', state);
        }
        return this._super(state);
      },
      remove: function () {
        this._super();
        if (this.menu) {
          this.menu.remove();
        }
      }
    });

    var Radio = Checkbox.extend({
      Defaults: {
        classes: 'radio',
        role: 'radio'
      }
    });

    var ResizeHandle = Widget.extend({
      renderHtml: function () {
        var self = this, prefix = self.classPrefix;
        self.classes.add('resizehandle');
        if (self.settings.direction === 'both') {
          self.classes.add('resizehandle-both');
        }
        self.canFocus = false;
        return '<div id="' + self._id + '" class="' + self.classes + '">' + '<i class="' + prefix + 'ico ' + prefix + 'i-resize"></i>' + '</div>';
      },
      postRender: function () {
        var self = this;
        self._super();
        self.resizeDragHelper = new DragHelper(this._id, {
          start: function () {
            self.fire('ResizeStart');
          },
          drag: function (e) {
            if (self.settings.direction !== 'both') {
              e.deltaX = 0;
            }
            self.fire('Resize', e);
          },
          stop: function () {
            self.fire('ResizeEnd');
          }
        });
      },
      remove: function () {
        if (this.resizeDragHelper) {
          this.resizeDragHelper.destroy();
        }
        return this._super();
      }
    });

    function createOptions(options) {
      var strOptions = '';
      if (options) {
        for (var i = 0; i < options.length; i++) {
          strOptions += '<option value="' + options[i] + '">' + options[i] + '</option>';
        }
      }
      return strOptions;
    }
    var SelectBox = Widget.extend({
      Defaults: {
        classes: 'selectbox',
        role: 'selectbox',
        options: []
      },
      init: function (settings) {
        var self = this;
        self._super(settings);
        if (self.settings.size) {
          self.size = self.settings.size;
        }
        if (self.settings.options) {
          self._options = self.settings.options;
        }
        self.on('keydown', function (e) {
          var rootControl;
          if (e.keyCode === 13) {
            e.preventDefault();
            self.parents().reverse().each(function (ctrl) {
              if (ctrl.toJSON) {
                rootControl = ctrl;
                return false;
              }
            });
            self.fire('submit', { data: rootControl.toJSON() });
          }
        });
      },
      options: function (state) {
        if (!arguments.length) {
          return this.state.get('options');
        }
        this.state.set('options', state);
        return this;
      },
      renderHtml: function () {
        var self = this;
        var options, size = '';
        options = createOptions(self._options);
        if (self.size) {
          size = ' size = "' + self.size + '"';
        }
        return '<select id="' + self._id + '" class="' + self.classes + '"' + size + '>' + options + '</select>';
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:options', function (e) {
          self.getEl().innerHTML = createOptions(e.value);
        });
        return self._super();
      }
    });

    function constrain(value, minVal, maxVal) {
      if (value < minVal) {
        value = minVal;
      }
      if (value > maxVal) {
        value = maxVal;
      }
      return value;
    }
    function setAriaProp(el, name, value) {
      el.setAttribute('aria-' + name, value);
    }
    function updateSliderHandle(ctrl, value) {
      var maxHandlePos, shortSizeName, sizeName, stylePosName, styleValue, handleEl;
      if (ctrl.settings.orientation === 'v') {
        stylePosName = 'top';
        sizeName = 'height';
        shortSizeName = 'h';
      } else {
        stylePosName = 'left';
        sizeName = 'width';
        shortSizeName = 'w';
      }
      handleEl = ctrl.getEl('handle');
      maxHandlePos = (ctrl.layoutRect()[shortSizeName] || 100) - funcs.getSize(handleEl)[sizeName];
      styleValue = maxHandlePos * ((value - ctrl._minValue) / (ctrl._maxValue - ctrl._minValue)) + 'px';
      handleEl.style[stylePosName] = styleValue;
      handleEl.style.height = ctrl.layoutRect().h + 'px';
      setAriaProp(handleEl, 'valuenow', value);
      setAriaProp(handleEl, 'valuetext', '' + ctrl.settings.previewFilter(value));
      setAriaProp(handleEl, 'valuemin', ctrl._minValue);
      setAriaProp(handleEl, 'valuemax', ctrl._maxValue);
    }
    var Slider = Widget.extend({
      init: function (settings) {
        var self = this;
        if (!settings.previewFilter) {
          settings.previewFilter = function (value) {
            return Math.round(value * 100) / 100;
          };
        }
        self._super(settings);
        self.classes.add('slider');
        if (settings.orientation === 'v') {
          self.classes.add('vertical');
        }
        self._minValue = isNumber(settings.minValue) ? settings.minValue : 0;
        self._maxValue = isNumber(settings.maxValue) ? settings.maxValue : 100;
        self._initValue = self.state.get('value');
      },
      renderHtml: function () {
        var self = this, id = self._id, prefix = self.classPrefix;
        return '<div id="' + id + '" class="' + self.classes + '">' + '<div id="' + id + '-handle" class="' + prefix + 'slider-handle" role="slider" tabindex="-1"></div>' + '</div>';
      },
      reset: function () {
        this.value(this._initValue).repaint();
      },
      postRender: function () {
        var self = this;
        var minValue, maxValue, screenCordName, stylePosName, sizeName, shortSizeName;
        function toFraction(min, max, val) {
          return (val + min) / (max - min);
        }
        function fromFraction(min, max, val) {
          return val * (max - min) - min;
        }
        function handleKeyboard(minValue, maxValue) {
          function alter(delta) {
            var value;
            value = self.value();
            value = fromFraction(minValue, maxValue, toFraction(minValue, maxValue, value) + delta * 0.05);
            value = constrain(value, minValue, maxValue);
            self.value(value);
            self.fire('dragstart', { value: value });
            self.fire('drag', { value: value });
            self.fire('dragend', { value: value });
          }
          self.on('keydown', function (e) {
            switch (e.keyCode) {
            case 37:
            case 38:
              alter(-1);
              break;
            case 39:
            case 40:
              alter(1);
              break;
            }
          });
        }
        function handleDrag(minValue, maxValue, handleEl) {
          var startPos, startHandlePos, maxHandlePos, handlePos, value;
          self._dragHelper = new DragHelper(self._id, {
            handle: self._id + '-handle',
            start: function (e) {
              startPos = e[screenCordName];
              startHandlePos = parseInt(self.getEl('handle').style[stylePosName], 10);
              maxHandlePos = (self.layoutRect()[shortSizeName] || 100) - funcs.getSize(handleEl)[sizeName];
              self.fire('dragstart', { value: value });
            },
            drag: function (e) {
              var delta = e[screenCordName] - startPos;
              handlePos = constrain(startHandlePos + delta, 0, maxHandlePos);
              handleEl.style[stylePosName] = handlePos + 'px';
              value = minValue + handlePos / maxHandlePos * (maxValue - minValue);
              self.value(value);
              self.tooltip().text('' + self.settings.previewFilter(value)).show().moveRel(handleEl, 'bc tc');
              self.fire('drag', { value: value });
            },
            stop: function () {
              self.tooltip().hide();
              self.fire('dragend', { value: value });
            }
          });
        }
        minValue = self._minValue;
        maxValue = self._maxValue;
        if (self.settings.orientation === 'v') {
          screenCordName = 'screenY';
          stylePosName = 'top';
          sizeName = 'height';
          shortSizeName = 'h';
        } else {
          screenCordName = 'screenX';
          stylePosName = 'left';
          sizeName = 'width';
          shortSizeName = 'w';
        }
        self._super();
        handleKeyboard(minValue, maxValue);
        handleDrag(minValue, maxValue, self.getEl('handle'));
      },
      repaint: function () {
        this._super();
        updateSliderHandle(this, this.value());
      },
      bindStates: function () {
        var self = this;
        self.state.on('change:value', function (e) {
          updateSliderHandle(self, e.value);
        });
        return self._super();
      }
    });

    var Spacer = Widget.extend({
      renderHtml: function () {
        var self = this;
        self.classes.add('spacer');
        self.canFocus = false;
        return '<div id="' + self._id + '" class="' + self.classes + '"></div>';
      }
    });

    var SplitButton = MenuButton.extend({
      Defaults: {
        classes: 'widget btn splitbtn',
        role: 'button'
      },
      repaint: function () {
        var self$$1 = this;
        var elm = self$$1.getEl();
        var rect = self$$1.layoutRect();
        var mainButtonElm, menuButtonElm;
        self$$1._super();
        mainButtonElm = elm.firstChild;
        menuButtonElm = elm.lastChild;
        global$9(mainButtonElm).css({
          width: rect.w - funcs.getSize(menuButtonElm).width,
          height: rect.h - 2
        });
        global$9(menuButtonElm).css({ height: rect.h - 2 });
        return self$$1;
      },
      activeMenu: function (state) {
        var self$$1 = this;
        global$9(self$$1.getEl().lastChild).toggleClass(self$$1.classPrefix + 'active', state);
      },
      renderHtml: function () {
        var self$$1 = this;
        var id = self$$1._id;
        var prefix = self$$1.classPrefix;
        var image;
        var icon = self$$1.state.get('icon');
        var text = self$$1.state.get('text');
        var settings = self$$1.settings;
        var textHtml = '', ariaPressed;
        image = settings.image;
        if (image) {
          icon = 'none';
          if (typeof image !== 'string') {
            image = window.getSelection ? image[0] : image[1];
          }
          image = ' style="background-image: url(\'' + image + '\')"';
        } else {
          image = '';
        }
        icon = settings.icon ? prefix + 'ico ' + prefix + 'i-' + icon : '';
        if (text) {
          self$$1.classes.add('btn-has-text');
          textHtml = '<span class="' + prefix + 'txt">' + self$$1.encode(text) + '</span>';
        }
        ariaPressed = typeof settings.active === 'boolean' ? ' aria-pressed="' + settings.active + '"' : '';
        return '<div id="' + id + '" class="' + self$$1.classes + '" role="button"' + ariaPressed + ' tabindex="-1">' + '<button type="button" hidefocus="1" tabindex="-1">' + (icon ? '<i class="' + icon + '"' + image + '></i>' : '') + textHtml + '</button>' + '<button type="button" class="' + prefix + 'open" hidefocus="1" tabindex="-1">' + (self$$1._menuBtnText ? (icon ? '\xA0' : '') + self$$1._menuBtnText : '') + ' <i class="' + prefix + 'caret"></i>' + '</button>' + '</div>';
      },
      postRender: function () {
        var self$$1 = this, onClickHandler = self$$1.settings.onclick;
        self$$1.on('click', function (e) {
          var node = e.target;
          if (e.control === this) {
            while (node) {
              if (e.aria && e.aria.key !== 'down' || node.nodeName === 'BUTTON' && node.className.indexOf('open') === -1) {
                e.stopImmediatePropagation();
                if (onClickHandler) {
                  onClickHandler.call(this, e);
                }
                return;
              }
              node = node.parentNode;
            }
          }
        });
        delete self$$1.settings.onclick;
        return self$$1._super();
      }
    });

    var StackLayout = FlowLayout.extend({
      Defaults: {
        containerClass: 'stack-layout',
        controlClass: 'stack-layout-item',
        endClass: 'break'
      },
      isNative: function () {
        return true;
      }
    });

    var TabPanel = Panel.extend({
      Defaults: {
        layout: 'absolute',
        defaults: { type: 'panel' }
      },
      activateTab: function (idx) {
        var activeTabElm;
        if (this.activeTabId) {
          activeTabElm = this.getEl(this.activeTabId);
          global$9(activeTabElm).removeClass(this.classPrefix + 'active');
          activeTabElm.setAttribute('aria-selected', 'false');
        }
        this.activeTabId = 't' + idx;
        activeTabElm = this.getEl('t' + idx);
        activeTabElm.setAttribute('aria-selected', 'true');
        global$9(activeTabElm).addClass(this.classPrefix + 'active');
        this.items()[idx].show().fire('showtab');
        this.reflow();
        this.items().each(function (item, i) {
          if (idx !== i) {
            item.hide();
          }
        });
      },
      renderHtml: function () {
        var self = this;
        var layout = self._layout;
        var tabsHtml = '';
        var prefix = self.classPrefix;
        self.preRender();
        layout.preRender(self);
        self.items().each(function (ctrl, i) {
          var id = self._id + '-t' + i;
          ctrl.aria('role', 'tabpanel');
          ctrl.aria('labelledby', id);
          tabsHtml += '<div id="' + id + '" class="' + prefix + 'tab" ' + 'unselectable="on" role="tab" aria-controls="' + ctrl._id + '" aria-selected="false" tabIndex="-1">' + self.encode(ctrl.settings.title) + '</div>';
        });
        return '<div id="' + self._id + '" class="' + self.classes + '" hidefocus="1" tabindex="-1">' + '<div id="' + self._id + '-head" class="' + prefix + 'tabs" role="tablist">' + tabsHtml + '</div>' + '<div id="' + self._id + '-body" class="' + self.bodyClasses + '">' + layout.renderHtml(self) + '</div>' + '</div>';
      },
      postRender: function () {
        var self = this;
        self._super();
        self.settings.activeTab = self.settings.activeTab || 0;
        self.activateTab(self.settings.activeTab);
        this.on('click', function (e) {
          var targetParent = e.target.parentNode;
          if (targetParent && targetParent.id === self._id + '-head') {
            var i = targetParent.childNodes.length;
            while (i--) {
              if (targetParent.childNodes[i] === e.target) {
                self.activateTab(i);
              }
            }
          }
        });
      },
      initLayoutRect: function () {
        var self = this;
        var rect, minW, minH;
        minW = funcs.getSize(self.getEl('head')).width;
        minW = minW < 0 ? 0 : minW;
        minH = 0;
        self.items().each(function (item) {
          minW = Math.max(minW, item.layoutRect().minW);
          minH = Math.max(minH, item.layoutRect().minH);
        });
        self.items().each(function (ctrl) {
          ctrl.settings.x = 0;
          ctrl.settings.y = 0;
          ctrl.settings.w = minW;
          ctrl.settings.h = minH;
          ctrl.layoutRect({
            x: 0,
            y: 0,
            w: minW,
            h: minH
          });
        });
        var headH = funcs.getSize(self.getEl('head')).height;
        self.settings.minWidth = minW;
        self.settings.minHeight = minH + headH;
        rect = self._super();
        rect.deltaH += headH;
        rect.innerH = rect.h - rect.deltaH;
        return rect;
      }
    });

    var TextBox = Widget.extend({
      init: function (settings) {
        var self$$1 = this;
        self$$1._super(settings);
        self$$1.classes.add('textbox');
        if (settings.multiline) {
          self$$1.classes.add('multiline');
        } else {
          self$$1.on('keydown', function (e) {
            var rootControl;
            if (e.keyCode === 13) {
              e.preventDefault();
              self$$1.parents().reverse().each(function (ctrl) {
                if (ctrl.toJSON) {
                  rootControl = ctrl;
                  return false;
                }
              });
              self$$1.fire('submit', { data: rootControl.toJSON() });
            }
          });
          self$$1.on('keyup', function (e) {
            self$$1.state.set('value', e.target.value);
          });
        }
      },
      repaint: function () {
        var self$$1 = this;
        var style, rect, borderBox, borderW, borderH = 0, lastRepaintRect;
        style = self$$1.getEl().style;
        rect = self$$1._layoutRect;
        lastRepaintRect = self$$1._lastRepaintRect || {};
        var doc = document;
        if (!self$$1.settings.multiline && doc.all && (!doc.documentMode || doc.documentMode <= 8)) {
          style.lineHeight = rect.h - borderH + 'px';
        }
        borderBox = self$$1.borderBox;
        borderW = borderBox.left + borderBox.right + 8;
        borderH = borderBox.top + borderBox.bottom + (self$$1.settings.multiline ? 8 : 0);
        if (rect.x !== lastRepaintRect.x) {
          style.left = rect.x + 'px';
          lastRepaintRect.x = rect.x;
        }
        if (rect.y !== lastRepaintRect.y) {
          style.top = rect.y + 'px';
          lastRepaintRect.y = rect.y;
        }
        if (rect.w !== lastRepaintRect.w) {
          style.width = rect.w - borderW + 'px';
          lastRepaintRect.w = rect.w;
        }
        if (rect.h !== lastRepaintRect.h) {
          style.height = rect.h - borderH + 'px';
          lastRepaintRect.h = rect.h;
        }
        self$$1._lastRepaintRect = lastRepaintRect;
        self$$1.fire('repaint', {}, false);
        return self$$1;
      },
      renderHtml: function () {
        var self$$1 = this;
        var settings = self$$1.settings;
        var attrs, elm;
        attrs = {
          id: self$$1._id,
          hidefocus: '1'
        };
        global$2.each([
          'rows',
          'spellcheck',
          'maxLength',
          'size',
          'readonly',
          'min',
          'max',
          'step',
          'list',
          'pattern',
          'placeholder',
          'required',
          'multiple'
        ], function (name$$1) {
          attrs[name$$1] = settings[name$$1];
        });
        if (self$$1.disabled()) {
          attrs.disabled = 'disabled';
        }
        if (settings.subtype) {
          attrs.type = settings.subtype;
        }
        elm = funcs.create(settings.multiline ? 'textarea' : 'input', attrs);
        elm.value = self$$1.state.get('value');
        elm.className = self$$1.classes.toString();
        return elm.outerHTML;
      },
      value: function (value) {
        if (arguments.length) {
          this.state.set('value', value);
          return this;
        }
        if (this.state.get('rendered')) {
          this.state.set('value', this.getEl().value);
        }
        return this.state.get('value');
      },
      postRender: function () {
        var self$$1 = this;
        self$$1.getEl().value = self$$1.state.get('value');
        self$$1._super();
        self$$1.$el.on('change', function (e) {
          self$$1.state.set('value', e.target.value);
          self$$1.fire('change', e);
        });
      },
      bindStates: function () {
        var self$$1 = this;
        self$$1.state.on('change:value', function (e) {
          if (self$$1.getEl().value !== e.value) {
            self$$1.getEl().value = e.value;
          }
        });
        self$$1.state.on('change:disabled', function (e) {
          self$$1.getEl().disabled = e.value;
        });
        return self$$1._super();
      },
      remove: function () {
        this.$el.off();
        this._super();
      }
    });

    var getApi = function () {
      return {
        Selector: Selector,
        Collection: Collection$2,
        ReflowQueue: ReflowQueue,
        Control: Control$1,
        Factory: global$4,
        KeyboardNavigation: KeyboardNavigation,
        Container: Container,
        DragHelper: DragHelper,
        Scrollable: Scrollable,
        Panel: Panel,
        Movable: Movable,
        Resizable: Resizable,
        FloatPanel: FloatPanel,
        Window: Window$$1,
        MessageBox: MessageBox,
        Tooltip: Tooltip,
        Widget: Widget,
        Progress: Progress,
        Notification: Notification,
        Layout: Layout,
        AbsoluteLayout: AbsoluteLayout,
        Button: Button,
        ButtonGroup: ButtonGroup,
        Checkbox: Checkbox,
        ComboBox: ComboBox,
        ColorBox: ColorBox,
        PanelButton: PanelButton,
        ColorButton: ColorButton,
        ColorPicker: ColorPicker,
        Path: Path,
        ElementPath: ElementPath,
        FormItem: FormItem,
        Form: Form,
        FieldSet: FieldSet,
        FilePicker: FilePicker,
        FitLayout: FitLayout,
        FlexLayout: FlexLayout,
        FlowLayout: FlowLayout,
        FormatControls: FormatControls,
        GridLayout: GridLayout,
        Iframe: Iframe$1,
        InfoBox: InfoBox,
        Label: Label,
        Toolbar: Toolbar$1,
        MenuBar: MenuBar,
        MenuButton: MenuButton,
        MenuItem: MenuItem,
        Throbber: Throbber,
        Menu: Menu,
        ListBox: ListBox,
        Radio: Radio,
        ResizeHandle: ResizeHandle,
        SelectBox: SelectBox,
        Slider: Slider,
        Spacer: Spacer,
        SplitButton: SplitButton,
        StackLayout: StackLayout,
        TabPanel: TabPanel,
        TextBox: TextBox,
        DropZone: DropZone,
        BrowseButton: BrowseButton
      };
    };
    var appendTo = function (target) {
      if (target.ui) {
        global$2.each(getApi(), function (ref, key) {
          target.ui[key] = ref;
        });
      } else {
        target.ui = getApi();
      }
    };
    var registerToFactory = function () {
      global$2.each(getApi(), function (ref, key) {
        global$4.add(key, ref);
      });
    };
    var Api = {
      appendTo: appendTo,
      registerToFactory: registerToFactory
    };

    Api.registerToFactory();
    Api.appendTo(window.tinymce ? window.tinymce : {});
    global.add('modern', function (editor) {
      FormatControls.setup(editor);
      return ThemeApi.get(editor);
    });
    function Theme () {
    }

    return Theme;

}());
})();


/***/ }),
/* 136 */
/***/ (function(module, exports, __webpack_require__) {

// Exports the "lists" plugin for usage with module loaders
// Usage:
//   CommonJS:
//     require('tinymce/plugins/lists')
//   ES2015:
//     import 'tinymce/plugins/lists'
__webpack_require__(137);

/***/ }),
/* 137 */
/***/ (function(module, exports) {

(function () {
var lists = (function () {
    'use strict';

    var global = tinymce.util.Tools.resolve('tinymce.PluginManager');

    var global$1 = tinymce.util.Tools.resolve('tinymce.dom.RangeUtils');

    var global$2 = tinymce.util.Tools.resolve('tinymce.dom.TreeWalker');

    var global$3 = tinymce.util.Tools.resolve('tinymce.util.VK');

    var global$4 = tinymce.util.Tools.resolve('tinymce.dom.BookmarkManager');

    var global$5 = tinymce.util.Tools.resolve('tinymce.util.Tools');

    var global$6 = tinymce.util.Tools.resolve('tinymce.dom.DOMUtils');

    var isTextNode = function (node) {
      return node && node.nodeType === 3;
    };
    var isListNode = function (node) {
      return node && /^(OL|UL|DL)$/.test(node.nodeName);
    };
    var isOlUlNode = function (node) {
      return node && /^(OL|UL)$/.test(node.nodeName);
    };
    var isListItemNode = function (node) {
      return node && /^(LI|DT|DD)$/.test(node.nodeName);
    };
    var isDlItemNode = function (node) {
      return node && /^(DT|DD)$/.test(node.nodeName);
    };
    var isTableCellNode = function (node) {
      return node && /^(TH|TD)$/.test(node.nodeName);
    };
    var isBr = function (node) {
      return node && node.nodeName === 'BR';
    };
    var isFirstChild = function (node) {
      return node.parentNode.firstChild === node;
    };
    var isLastChild = function (node) {
      return node.parentNode.lastChild === node;
    };
    var isTextBlock = function (editor, node) {
      return node && !!editor.schema.getTextBlockElements()[node.nodeName];
    };
    var isBlock = function (node, blockElements) {
      return node && node.nodeName in blockElements;
    };
    var isBogusBr = function (dom, node) {
      if (!isBr(node)) {
        return false;
      }
      if (dom.isBlock(node.nextSibling) && !isBr(node.previousSibling)) {
        return true;
      }
      return false;
    };
    var isEmpty = function (dom, elm, keepBookmarks) {
      var empty = dom.isEmpty(elm);
      if (keepBookmarks && dom.select('span[data-mce-type=bookmark]', elm).length > 0) {
        return false;
      }
      return empty;
    };
    var isChildOfBody = function (dom, elm) {
      return dom.isChildOf(elm, dom.getRoot());
    };
    var NodeType = {
      isTextNode: isTextNode,
      isListNode: isListNode,
      isOlUlNode: isOlUlNode,
      isDlItemNode: isDlItemNode,
      isListItemNode: isListItemNode,
      isTableCellNode: isTableCellNode,
      isBr: isBr,
      isFirstChild: isFirstChild,
      isLastChild: isLastChild,
      isTextBlock: isTextBlock,
      isBlock: isBlock,
      isBogusBr: isBogusBr,
      isEmpty: isEmpty,
      isChildOfBody: isChildOfBody
    };

    var getNormalizedPoint = function (container, offset) {
      if (NodeType.isTextNode(container)) {
        return {
          container: container,
          offset: offset
        };
      }
      var node = global$1.getNode(container, offset);
      if (NodeType.isTextNode(node)) {
        return {
          container: node,
          offset: offset >= container.childNodes.length ? node.data.length : 0
        };
      } else if (node.previousSibling && NodeType.isTextNode(node.previousSibling)) {
        return {
          container: node.previousSibling,
          offset: node.previousSibling.data.length
        };
      } else if (node.nextSibling && NodeType.isTextNode(node.nextSibling)) {
        return {
          container: node.nextSibling,
          offset: 0
        };
      }
      return {
        container: container,
        offset: offset
      };
    };
    var normalizeRange = function (rng) {
      var outRng = rng.cloneRange();
      var rangeStart = getNormalizedPoint(rng.startContainer, rng.startOffset);
      outRng.setStart(rangeStart.container, rangeStart.offset);
      var rangeEnd = getNormalizedPoint(rng.endContainer, rng.endOffset);
      outRng.setEnd(rangeEnd.container, rangeEnd.offset);
      return outRng;
    };
    var Range = {
      getNormalizedPoint: getNormalizedPoint,
      normalizeRange: normalizeRange
    };

    var DOM = global$6.DOM;
    var createBookmark = function (rng) {
      var bookmark = {};
      var setupEndPoint = function (start) {
        var offsetNode, container, offset;
        container = rng[start ? 'startContainer' : 'endContainer'];
        offset = rng[start ? 'startOffset' : 'endOffset'];
        if (container.nodeType === 1) {
          offsetNode = DOM.create('span', { 'data-mce-type': 'bookmark' });
          if (container.hasChildNodes()) {
            offset = Math.min(offset, container.childNodes.length - 1);
            if (start) {
              container.insertBefore(offsetNode, container.childNodes[offset]);
            } else {
              DOM.insertAfter(offsetNode, container.childNodes[offset]);
            }
          } else {
            container.appendChild(offsetNode);
          }
          container = offsetNode;
          offset = 0;
        }
        bookmark[start ? 'startContainer' : 'endContainer'] = container;
        bookmark[start ? 'startOffset' : 'endOffset'] = offset;
      };
      setupEndPoint(true);
      if (!rng.collapsed) {
        setupEndPoint();
      }
      return bookmark;
    };
    var resolveBookmark = function (bookmark) {
      function restoreEndPoint(start) {
        var container, offset, node;
        var nodeIndex = function (container) {
          var node = container.parentNode.firstChild, idx = 0;
          while (node) {
            if (node === container) {
              return idx;
            }
            if (node.nodeType !== 1 || node.getAttribute('data-mce-type') !== 'bookmark') {
              idx++;
            }
            node = node.nextSibling;
          }
          return -1;
        };
        container = node = bookmark[start ? 'startContainer' : 'endContainer'];
        offset = bookmark[start ? 'startOffset' : 'endOffset'];
        if (!container) {
          return;
        }
        if (container.nodeType === 1) {
          offset = nodeIndex(container);
          container = container.parentNode;
          DOM.remove(node);
          if (!container.hasChildNodes() && DOM.isBlock(container)) {
            container.appendChild(DOM.create('br'));
          }
        }
        bookmark[start ? 'startContainer' : 'endContainer'] = container;
        bookmark[start ? 'startOffset' : 'endOffset'] = offset;
      }
      restoreEndPoint(true);
      restoreEndPoint();
      var rng = DOM.createRng();
      rng.setStart(bookmark.startContainer, bookmark.startOffset);
      if (bookmark.endContainer) {
        rng.setEnd(bookmark.endContainer, bookmark.endOffset);
      }
      return Range.normalizeRange(rng);
    };
    var Bookmark = {
      createBookmark: createBookmark,
      resolveBookmark: resolveBookmark
    };

    var constant = function (value) {
      return function () {
        return value;
      };
    };
    function curry(fn) {
      var initialArgs = [];
      for (var _i = 1; _i < arguments.length; _i++) {
        initialArgs[_i - 1] = arguments[_i];
      }
      return function () {
        var restArgs = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          restArgs[_i] = arguments[_i];
        }
        var all = initialArgs.concat(restArgs);
        return fn.apply(null, all);
      };
    }
    var not = function (f) {
      return function () {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        return !f.apply(null, args);
      };
    };
    var never = constant(false);
    var always = constant(true);

    var never$1 = never;
    var always$1 = always;
    var none = function () {
      return NONE;
    };
    var NONE = function () {
      var eq = function (o) {
        return o.isNone();
      };
      var call$$1 = function (thunk) {
        return thunk();
      };
      var id = function (n) {
        return n;
      };
      var noop$$1 = function () {
      };
      var nul = function () {
        return null;
      };
      var undef = function () {
        return undefined;
      };
      var me = {
        fold: function (n, s) {
          return n();
        },
        is: never$1,
        isSome: never$1,
        isNone: always$1,
        getOr: id,
        getOrThunk: call$$1,
        getOrDie: function (msg) {
          throw new Error(msg || 'error: getOrDie called on none.');
        },
        getOrNull: nul,
        getOrUndefined: undef,
        or: id,
        orThunk: call$$1,
        map: none,
        ap: none,
        each: noop$$1,
        bind: none,
        flatten: none,
        exists: never$1,
        forall: always$1,
        filter: none,
        equals: eq,
        equals_: eq,
        toArray: function () {
          return [];
        },
        toString: constant('none()')
      };
      if (Object.freeze)
        Object.freeze(me);
      return me;
    }();
    var some = function (a) {
      var constant_a = function () {
        return a;
      };
      var self = function () {
        return me;
      };
      var map = function (f) {
        return some(f(a));
      };
      var bind = function (f) {
        return f(a);
      };
      var me = {
        fold: function (n, s) {
          return s(a);
        },
        is: function (v) {
          return a === v;
        },
        isSome: always$1,
        isNone: never$1,
        getOr: constant_a,
        getOrThunk: constant_a,
        getOrDie: constant_a,
        getOrNull: constant_a,
        getOrUndefined: constant_a,
        or: self,
        orThunk: self,
        map: map,
        ap: function (optfab) {
          return optfab.fold(none, function (fab) {
            return some(fab(a));
          });
        },
        each: function (f) {
          f(a);
        },
        bind: bind,
        flatten: constant_a,
        exists: bind,
        forall: bind,
        filter: function (f) {
          return f(a) ? me : NONE;
        },
        equals: function (o) {
          return o.is(a);
        },
        equals_: function (o, elementEq) {
          return o.fold(never$1, function (b) {
            return elementEq(a, b);
          });
        },
        toArray: function () {
          return [a];
        },
        toString: function () {
          return 'some(' + a + ')';
        }
      };
      return me;
    };
    var from = function (value) {
      return value === null || value === undefined ? NONE : some(value);
    };
    var Option = {
      some: some,
      none: none,
      from: from
    };

    var typeOf = function (x) {
      if (x === null)
        return 'null';
      var t = typeof x;
      if (t === 'object' && Array.prototype.isPrototypeOf(x))
        return 'array';
      if (t === 'object' && String.prototype.isPrototypeOf(x))
        return 'string';
      return t;
    };
    var isType = function (type) {
      return function (value) {
        return typeOf(value) === type;
      };
    };
    var isString = isType('string');
    var isBoolean = isType('boolean');
    var isFunction = isType('function');
    var isNumber = isType('number');

    var map = function (xs, f) {
      var len = xs.length;
      var r = new Array(len);
      for (var i = 0; i < len; i++) {
        var x = xs[i];
        r[i] = f(x, i, xs);
      }
      return r;
    };
    var each = function (xs, f) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        f(x, i, xs);
      }
    };
    var filter = function (xs, pred) {
      var r = [];
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        if (pred(x, i, xs)) {
          r.push(x);
        }
      }
      return r;
    };
    var groupBy = function (xs, f) {
      if (xs.length === 0) {
        return [];
      } else {
        var wasType = f(xs[0]);
        var r = [];
        var group = [];
        for (var i = 0, len = xs.length; i < len; i++) {
          var x = xs[i];
          var type = f(x);
          if (type !== wasType) {
            r.push(group);
            group = [];
          }
          wasType = type;
          group.push(x);
        }
        if (group.length !== 0) {
          r.push(group);
        }
        return r;
      }
    };
    var foldl = function (xs, f, acc) {
      each(xs, function (x) {
        acc = f(acc, x);
      });
      return acc;
    };
    var find = function (xs, pred) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        if (pred(x, i, xs)) {
          return Option.some(x);
        }
      }
      return Option.none();
    };
    var push = Array.prototype.push;
    var flatten = function (xs) {
      var r = [];
      for (var i = 0, len = xs.length; i < len; ++i) {
        if (!Array.prototype.isPrototypeOf(xs[i]))
          throw new Error('Arr.flatten item ' + i + ' was not an array, input: ' + xs);
        push.apply(r, xs[i]);
      }
      return r;
    };
    var bind = function (xs, f) {
      var output = map(xs, f);
      return flatten(output);
    };
    var slice = Array.prototype.slice;
    var reverse = function (xs) {
      var r = slice.call(xs, 0);
      r.reverse();
      return r;
    };
    var head = function (xs) {
      return xs.length === 0 ? Option.none() : Option.some(xs[0]);
    };
    var last = function (xs) {
      return xs.length === 0 ? Option.none() : Option.some(xs[xs.length - 1]);
    };
    var from$1 = isFunction(Array.from) ? Array.from : function (x) {
      return slice.call(x);
    };

    var Global = typeof window !== 'undefined' ? window : Function('return this;')();

    var path = function (parts, scope) {
      var o = scope !== undefined && scope !== null ? scope : Global;
      for (var i = 0; i < parts.length && o !== undefined && o !== null; ++i)
        o = o[parts[i]];
      return o;
    };
    var resolve = function (p, scope) {
      var parts = p.split('.');
      return path(parts, scope);
    };

    var unsafe = function (name, scope) {
      return resolve(name, scope);
    };
    var getOrDie = function (name, scope) {
      var actual = unsafe(name, scope);
      if (actual === undefined || actual === null)
        throw name + ' not available on this browser';
      return actual;
    };
    var Global$1 = { getOrDie: getOrDie };

    var htmlElement = function (scope) {
      return Global$1.getOrDie('HTMLElement', scope);
    };
    var isPrototypeOf = function (x) {
      var scope = resolve('ownerDocument.defaultView', x);
      return htmlElement(scope).prototype.isPrototypeOf(x);
    };
    var HTMLElement = { isPrototypeOf: isPrototypeOf };

    var global$7 = tinymce.util.Tools.resolve('tinymce.dom.DomQuery');

    var getParentList = function (editor) {
      var selectionStart = editor.selection.getStart(true);
      return editor.dom.getParent(selectionStart, 'OL,UL,DL', getClosestListRootElm(editor, selectionStart));
    };
    var isParentListSelected = function (parentList, selectedBlocks) {
      return parentList && selectedBlocks.length === 1 && selectedBlocks[0] === parentList;
    };
    var findSubLists = function (parentList) {
      return global$5.grep(parentList.querySelectorAll('ol,ul,dl'), function (elm) {
        return NodeType.isListNode(elm);
      });
    };
    var getSelectedSubLists = function (editor) {
      var parentList = getParentList(editor);
      var selectedBlocks = editor.selection.getSelectedBlocks();
      if (isParentListSelected(parentList, selectedBlocks)) {
        return findSubLists(parentList);
      } else {
        return global$5.grep(selectedBlocks, function (elm) {
          return NodeType.isListNode(elm) && parentList !== elm;
        });
      }
    };
    var findParentListItemsNodes = function (editor, elms) {
      var listItemsElms = global$5.map(elms, function (elm) {
        var parentLi = editor.dom.getParent(elm, 'li,dd,dt', getClosestListRootElm(editor, elm));
        return parentLi ? parentLi : elm;
      });
      return global$7.unique(listItemsElms);
    };
    var getSelectedListItems = function (editor) {
      var selectedBlocks = editor.selection.getSelectedBlocks();
      return global$5.grep(findParentListItemsNodes(editor, selectedBlocks), function (block) {
        return NodeType.isListItemNode(block);
      });
    };
    var getSelectedDlItems = function (editor) {
      return filter(getSelectedListItems(editor), NodeType.isDlItemNode);
    };
    var getClosestListRootElm = function (editor, elm) {
      var parentTableCell = editor.dom.getParents(elm, 'TD,TH');
      var root = parentTableCell.length > 0 ? parentTableCell[0] : editor.getBody();
      return root;
    };
    var findLastParentListNode = function (editor, elm) {
      var parentLists = editor.dom.getParents(elm, 'ol,ul', getClosestListRootElm(editor, elm));
      return last(parentLists);
    };
    var getSelectedLists = function (editor) {
      var firstList = findLastParentListNode(editor, editor.selection.getStart());
      var subsequentLists = filter(editor.selection.getSelectedBlocks(), NodeType.isOlUlNode);
      return firstList.toArray().concat(subsequentLists);
    };
    var getSelectedListRoots = function (editor) {
      var selectedLists = getSelectedLists(editor);
      return getUniqueListRoots(editor, selectedLists);
    };
    var getUniqueListRoots = function (editor, lists) {
      var listRoots = map(lists, function (list) {
        return findLastParentListNode(editor, list).getOr(list);
      });
      return global$7.unique(listRoots);
    };
    var isList = function (editor) {
      var list = getParentList(editor);
      return HTMLElement.isPrototypeOf(list);
    };
    var Selection = {
      isList: isList,
      getParentList: getParentList,
      getSelectedSubLists: getSelectedSubLists,
      getSelectedListItems: getSelectedListItems,
      getClosestListRootElm: getClosestListRootElm,
      getSelectedDlItems: getSelectedDlItems,
      getSelectedListRoots: getSelectedListRoots
    };

    var node = function () {
      var f = Global$1.getOrDie('Node');
      return f;
    };
    var compareDocumentPosition = function (a, b, match) {
      return (a.compareDocumentPosition(b) & match) !== 0;
    };
    var documentPositionPreceding = function (a, b) {
      return compareDocumentPosition(a, b, node().DOCUMENT_POSITION_PRECEDING);
    };
    var documentPositionContainedBy = function (a, b) {
      return compareDocumentPosition(a, b, node().DOCUMENT_POSITION_CONTAINED_BY);
    };
    var Node$1 = {
      documentPositionPreceding: documentPositionPreceding,
      documentPositionContainedBy: documentPositionContainedBy
    };

    var cached = function (f) {
      var called = false;
      var r;
      return function () {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        if (!called) {
          called = true;
          r = f.apply(null, args);
        }
        return r;
      };
    };

    var firstMatch = function (regexes, s) {
      for (var i = 0; i < regexes.length; i++) {
        var x = regexes[i];
        if (x.test(s))
          return x;
      }
      return undefined;
    };
    var find$1 = function (regexes, agent) {
      var r = firstMatch(regexes, agent);
      if (!r)
        return {
          major: 0,
          minor: 0
        };
      var group = function (i) {
        return Number(agent.replace(r, '$' + i));
      };
      return nu(group(1), group(2));
    };
    var detect = function (versionRegexes, agent) {
      var cleanedAgent = String(agent).toLowerCase();
      if (versionRegexes.length === 0)
        return unknown();
      return find$1(versionRegexes, cleanedAgent);
    };
    var unknown = function () {
      return nu(0, 0);
    };
    var nu = function (major, minor) {
      return {
        major: major,
        minor: minor
      };
    };
    var Version = {
      nu: nu,
      detect: detect,
      unknown: unknown
    };

    var edge = 'Edge';
    var chrome = 'Chrome';
    var ie = 'IE';
    var opera = 'Opera';
    var firefox = 'Firefox';
    var safari = 'Safari';
    var isBrowser = function (name, current) {
      return function () {
        return current === name;
      };
    };
    var unknown$1 = function () {
      return nu$1({
        current: undefined,
        version: Version.unknown()
      });
    };
    var nu$1 = function (info) {
      var current = info.current;
      var version = info.version;
      return {
        current: current,
        version: version,
        isEdge: isBrowser(edge, current),
        isChrome: isBrowser(chrome, current),
        isIE: isBrowser(ie, current),
        isOpera: isBrowser(opera, current),
        isFirefox: isBrowser(firefox, current),
        isSafari: isBrowser(safari, current)
      };
    };
    var Browser = {
      unknown: unknown$1,
      nu: nu$1,
      edge: constant(edge),
      chrome: constant(chrome),
      ie: constant(ie),
      opera: constant(opera),
      firefox: constant(firefox),
      safari: constant(safari)
    };

    var windows = 'Windows';
    var ios = 'iOS';
    var android = 'Android';
    var linux = 'Linux';
    var osx = 'OSX';
    var solaris = 'Solaris';
    var freebsd = 'FreeBSD';
    var isOS = function (name, current) {
      return function () {
        return current === name;
      };
    };
    var unknown$2 = function () {
      return nu$2({
        current: undefined,
        version: Version.unknown()
      });
    };
    var nu$2 = function (info) {
      var current = info.current;
      var version = info.version;
      return {
        current: current,
        version: version,
        isWindows: isOS(windows, current),
        isiOS: isOS(ios, current),
        isAndroid: isOS(android, current),
        isOSX: isOS(osx, current),
        isLinux: isOS(linux, current),
        isSolaris: isOS(solaris, current),
        isFreeBSD: isOS(freebsd, current)
      };
    };
    var OperatingSystem = {
      unknown: unknown$2,
      nu: nu$2,
      windows: constant(windows),
      ios: constant(ios),
      android: constant(android),
      linux: constant(linux),
      osx: constant(osx),
      solaris: constant(solaris),
      freebsd: constant(freebsd)
    };

    var DeviceType = function (os, browser, userAgent) {
      var isiPad = os.isiOS() && /ipad/i.test(userAgent) === true;
      var isiPhone = os.isiOS() && !isiPad;
      var isAndroid3 = os.isAndroid() && os.version.major === 3;
      var isAndroid4 = os.isAndroid() && os.version.major === 4;
      var isTablet = isiPad || isAndroid3 || isAndroid4 && /mobile/i.test(userAgent) === true;
      var isTouch = os.isiOS() || os.isAndroid();
      var isPhone = isTouch && !isTablet;
      var iOSwebview = browser.isSafari() && os.isiOS() && /safari/i.test(userAgent) === false;
      return {
        isiPad: constant(isiPad),
        isiPhone: constant(isiPhone),
        isTablet: constant(isTablet),
        isPhone: constant(isPhone),
        isTouch: constant(isTouch),
        isAndroid: os.isAndroid,
        isiOS: os.isiOS,
        isWebView: constant(iOSwebview)
      };
    };

    var detect$1 = function (candidates, userAgent) {
      var agent = String(userAgent).toLowerCase();
      return find(candidates, function (candidate) {
        return candidate.search(agent);
      });
    };
    var detectBrowser = function (browsers, userAgent) {
      return detect$1(browsers, userAgent).map(function (browser) {
        var version = Version.detect(browser.versionRegexes, userAgent);
        return {
          current: browser.name,
          version: version
        };
      });
    };
    var detectOs = function (oses, userAgent) {
      return detect$1(oses, userAgent).map(function (os) {
        var version = Version.detect(os.versionRegexes, userAgent);
        return {
          current: os.name,
          version: version
        };
      });
    };
    var UaString = {
      detectBrowser: detectBrowser,
      detectOs: detectOs
    };

    var contains$1 = function (str, substr) {
      return str.indexOf(substr) !== -1;
    };

    var normalVersionRegex = /.*?version\/\ ?([0-9]+)\.([0-9]+).*/;
    var checkContains = function (target) {
      return function (uastring) {
        return contains$1(uastring, target);
      };
    };
    var browsers = [
      {
        name: 'Edge',
        versionRegexes: [/.*?edge\/ ?([0-9]+)\.([0-9]+)$/],
        search: function (uastring) {
          var monstrosity = contains$1(uastring, 'edge/') && contains$1(uastring, 'chrome') && contains$1(uastring, 'safari') && contains$1(uastring, 'applewebkit');
          return monstrosity;
        }
      },
      {
        name: 'Chrome',
        versionRegexes: [
          /.*?chrome\/([0-9]+)\.([0-9]+).*/,
          normalVersionRegex
        ],
        search: function (uastring) {
          return contains$1(uastring, 'chrome') && !contains$1(uastring, 'chromeframe');
        }
      },
      {
        name: 'IE',
        versionRegexes: [
          /.*?msie\ ?([0-9]+)\.([0-9]+).*/,
          /.*?rv:([0-9]+)\.([0-9]+).*/
        ],
        search: function (uastring) {
          return contains$1(uastring, 'msie') || contains$1(uastring, 'trident');
        }
      },
      {
        name: 'Opera',
        versionRegexes: [
          normalVersionRegex,
          /.*?opera\/([0-9]+)\.([0-9]+).*/
        ],
        search: checkContains('opera')
      },
      {
        name: 'Firefox',
        versionRegexes: [/.*?firefox\/\ ?([0-9]+)\.([0-9]+).*/],
        search: checkContains('firefox')
      },
      {
        name: 'Safari',
        versionRegexes: [
          normalVersionRegex,
          /.*?cpu os ([0-9]+)_([0-9]+).*/
        ],
        search: function (uastring) {
          return (contains$1(uastring, 'safari') || contains$1(uastring, 'mobile/')) && contains$1(uastring, 'applewebkit');
        }
      }
    ];
    var oses = [
      {
        name: 'Windows',
        search: checkContains('win'),
        versionRegexes: [/.*?windows\ nt\ ?([0-9]+)\.([0-9]+).*/]
      },
      {
        name: 'iOS',
        search: function (uastring) {
          return contains$1(uastring, 'iphone') || contains$1(uastring, 'ipad');
        },
        versionRegexes: [
          /.*?version\/\ ?([0-9]+)\.([0-9]+).*/,
          /.*cpu os ([0-9]+)_([0-9]+).*/,
          /.*cpu iphone os ([0-9]+)_([0-9]+).*/
        ]
      },
      {
        name: 'Android',
        search: checkContains('android'),
        versionRegexes: [/.*?android\ ?([0-9]+)\.([0-9]+).*/]
      },
      {
        name: 'OSX',
        search: checkContains('os x'),
        versionRegexes: [/.*?os\ x\ ?([0-9]+)_([0-9]+).*/]
      },
      {
        name: 'Linux',
        search: checkContains('linux'),
        versionRegexes: []
      },
      {
        name: 'Solaris',
        search: checkContains('sunos'),
        versionRegexes: []
      },
      {
        name: 'FreeBSD',
        search: checkContains('freebsd'),
        versionRegexes: []
      }
    ];
    var PlatformInfo = {
      browsers: constant(browsers),
      oses: constant(oses)
    };

    var detect$2 = function (userAgent) {
      var browsers = PlatformInfo.browsers();
      var oses = PlatformInfo.oses();
      var browser = UaString.detectBrowser(browsers, userAgent).fold(Browser.unknown, Browser.nu);
      var os = UaString.detectOs(oses, userAgent).fold(OperatingSystem.unknown, OperatingSystem.nu);
      var deviceType = DeviceType(os, browser, userAgent);
      return {
        browser: browser,
        os: os,
        deviceType: deviceType
      };
    };
    var PlatformDetection = { detect: detect$2 };

    var detect$3 = cached(function () {
      var userAgent = navigator.userAgent;
      return PlatformDetection.detect(userAgent);
    });
    var PlatformDetection$1 = { detect: detect$3 };

    var fromHtml = function (html, scope) {
      var doc = scope || document;
      var div = doc.createElement('div');
      div.innerHTML = html;
      if (!div.hasChildNodes() || div.childNodes.length > 1) {
        console.error('HTML does not have a single root node', html);
        throw 'HTML must have a single root node';
      }
      return fromDom(div.childNodes[0]);
    };
    var fromTag = function (tag, scope) {
      var doc = scope || document;
      var node = doc.createElement(tag);
      return fromDom(node);
    };
    var fromText = function (text, scope) {
      var doc = scope || document;
      var node = doc.createTextNode(text);
      return fromDom(node);
    };
    var fromDom = function (node) {
      if (node === null || node === undefined)
        throw new Error('Node cannot be null or undefined');
      return { dom: constant(node) };
    };
    var fromPoint = function (docElm, x, y) {
      var doc = docElm.dom();
      return Option.from(doc.elementFromPoint(x, y)).map(fromDom);
    };
    var Element$$1 = {
      fromHtml: fromHtml,
      fromTag: fromTag,
      fromText: fromText,
      fromDom: fromDom,
      fromPoint: fromPoint
    };

    var ATTRIBUTE = Node.ATTRIBUTE_NODE;
    var CDATA_SECTION = Node.CDATA_SECTION_NODE;
    var COMMENT = Node.COMMENT_NODE;
    var DOCUMENT = Node.DOCUMENT_NODE;
    var DOCUMENT_TYPE = Node.DOCUMENT_TYPE_NODE;
    var DOCUMENT_FRAGMENT = Node.DOCUMENT_FRAGMENT_NODE;
    var ELEMENT = Node.ELEMENT_NODE;
    var TEXT = Node.TEXT_NODE;
    var PROCESSING_INSTRUCTION = Node.PROCESSING_INSTRUCTION_NODE;
    var ENTITY_REFERENCE = Node.ENTITY_REFERENCE_NODE;
    var ENTITY = Node.ENTITY_NODE;
    var NOTATION = Node.NOTATION_NODE;

    var ELEMENT$1 = ELEMENT;
    var is = function (element, selector) {
      var elem = element.dom();
      if (elem.nodeType !== ELEMENT$1)
        return false;
      else if (elem.matches !== undefined)
        return elem.matches(selector);
      else if (elem.msMatchesSelector !== undefined)
        return elem.msMatchesSelector(selector);
      else if (elem.webkitMatchesSelector !== undefined)
        return elem.webkitMatchesSelector(selector);
      else if (elem.mozMatchesSelector !== undefined)
        return elem.mozMatchesSelector(selector);
      else
        throw new Error('Browser lacks native selectors');
    };

    var eq = function (e1, e2) {
      return e1.dom() === e2.dom();
    };
    var regularContains = function (e1, e2) {
      var d1 = e1.dom(), d2 = e2.dom();
      return d1 === d2 ? false : d1.contains(d2);
    };
    var ieContains = function (e1, e2) {
      return Node$1.documentPositionContainedBy(e1.dom(), e2.dom());
    };
    var browser = PlatformDetection$1.detect().browser;
    var contains$2 = browser.isIE() ? ieContains : regularContains;
    var is$1 = is;

    var keys = Object.keys;
    var each$1 = function (obj, f) {
      var props = keys(obj);
      for (var k = 0, len = props.length; k < len; k++) {
        var i = props[k];
        var x = obj[i];
        f(x, i, obj);
      }
    };

    var name = function (element) {
      var r = element.dom().nodeName;
      return r.toLowerCase();
    };

    var rawSet = function (dom, key, value$$1) {
      if (isString(value$$1) || isBoolean(value$$1) || isNumber(value$$1)) {
        dom.setAttribute(key, value$$1 + '');
      } else {
        console.error('Invalid call to Attr.set. Key ', key, ':: Value ', value$$1, ':: Element ', dom);
        throw new Error('Attribute value was not simple');
      }
    };
    var setAll = function (element, attrs) {
      var dom = element.dom();
      each$1(attrs, function (v, k) {
        rawSet(dom, k, v);
      });
    };
    var clone = function (element) {
      return foldl(element.dom().attributes, function (acc, attr) {
        acc[attr.name] = attr.value;
        return acc;
      }, {});
    };

    var Immutable = function () {
      var fields = [];
      for (var _i = 0; _i < arguments.length; _i++) {
        fields[_i] = arguments[_i];
      }
      return function () {
        var values = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          values[_i] = arguments[_i];
        }
        if (fields.length !== values.length) {
          throw new Error('Wrong number of arguments to struct. Expected "[' + fields.length + ']", got ' + values.length + ' arguments');
        }
        var struct = {};
        each(fields, function (name, i) {
          struct[name] = constant(values[i]);
        });
        return struct;
      };
    };

    var parent = function (element) {
      var dom = element.dom();
      return Option.from(dom.parentNode).map(Element$$1.fromDom);
    };
    var children = function (element) {
      var dom = element.dom();
      return map(dom.childNodes, Element$$1.fromDom);
    };
    var child = function (element, index) {
      var children = element.dom().childNodes;
      return Option.from(children[index]).map(Element$$1.fromDom);
    };
    var firstChild = function (element) {
      return child(element, 0);
    };
    var lastChild = function (element) {
      return child(element, element.dom().childNodes.length - 1);
    };
    var spot = Immutable('element', 'offset');

    var before = function (marker, element) {
      var parent$$1 = parent(marker);
      parent$$1.each(function (v) {
        v.dom().insertBefore(element.dom(), marker.dom());
      });
    };
    var append = function (parent$$1, element) {
      parent$$1.dom().appendChild(element.dom());
    };

    var before$1 = function (marker, elements) {
      each(elements, function (x) {
        before(marker, x);
      });
    };
    var append$1 = function (parent, elements) {
      each(elements, function (x) {
        append(parent, x);
      });
    };

    var remove$1 = function (element) {
      var dom = element.dom();
      if (dom.parentNode !== null)
        dom.parentNode.removeChild(dom);
    };

    var clone$1 = function (original, deep) {
      return Element$$1.fromDom(original.dom().cloneNode(deep));
    };
    var deep = function (original) {
      return clone$1(original, true);
    };
    var shallowAs = function (original, tag) {
      var nu = Element$$1.fromTag(tag);
      var attributes = clone(original);
      setAll(nu, attributes);
      return nu;
    };
    var mutate = function (original, tag) {
      var nu = shallowAs(original, tag);
      before(original, nu);
      var children$$1 = children(original);
      append$1(nu, children$$1);
      remove$1(original);
      return nu;
    };

    var global$8 = tinymce.util.Tools.resolve('tinymce.Env');

    var DOM$1 = global$6.DOM;
    var createNewTextBlock = function (editor, contentNode, blockName) {
      var node, textBlock;
      var fragment = DOM$1.createFragment();
      var hasContentNode;
      var blockElements = editor.schema.getBlockElements();
      if (editor.settings.forced_root_block) {
        blockName = blockName || editor.settings.forced_root_block;
      }
      if (blockName) {
        textBlock = DOM$1.create(blockName);
        if (textBlock.tagName === editor.settings.forced_root_block) {
          DOM$1.setAttribs(textBlock, editor.settings.forced_root_block_attrs);
        }
        if (!NodeType.isBlock(contentNode.firstChild, blockElements)) {
          fragment.appendChild(textBlock);
        }
      }
      if (contentNode) {
        while (node = contentNode.firstChild) {
          var nodeName = node.nodeName;
          if (!hasContentNode && (nodeName !== 'SPAN' || node.getAttribute('data-mce-type') !== 'bookmark')) {
            hasContentNode = true;
          }
          if (NodeType.isBlock(node, blockElements)) {
            fragment.appendChild(node);
            textBlock = null;
          } else {
            if (blockName) {
              if (!textBlock) {
                textBlock = DOM$1.create(blockName);
                fragment.appendChild(textBlock);
              }
              textBlock.appendChild(node);
            } else {
              fragment.appendChild(node);
            }
          }
        }
      }
      if (!editor.settings.forced_root_block) {
        fragment.appendChild(DOM$1.create('br'));
      } else {
        if (!hasContentNode && (!global$8.ie || global$8.ie > 10)) {
          textBlock.appendChild(DOM$1.create('br', { 'data-mce-bogus': '1' }));
        }
      }
      return fragment;
    };
    var TextBlock = { createNewTextBlock: createNewTextBlock };

    var DOM$2 = global$6.DOM;
    var splitList = function (editor, ul, li, newBlock) {
      var tmpRng, fragment, bookmarks, node;
      var removeAndKeepBookmarks = function (targetNode) {
        global$5.each(bookmarks, function (node) {
          targetNode.parentNode.insertBefore(node, li.parentNode);
        });
        DOM$2.remove(targetNode);
      };
      bookmarks = DOM$2.select('span[data-mce-type="bookmark"]', ul);
      newBlock = newBlock || TextBlock.createNewTextBlock(editor, li);
      tmpRng = DOM$2.createRng();
      tmpRng.setStartAfter(li);
      tmpRng.setEndAfter(ul);
      fragment = tmpRng.extractContents();
      for (node = fragment.firstChild; node; node = node.firstChild) {
        if (node.nodeName === 'LI' && editor.dom.isEmpty(node)) {
          DOM$2.remove(node);
          break;
        }
      }
      if (!editor.dom.isEmpty(fragment)) {
        DOM$2.insertAfter(fragment, ul);
      }
      DOM$2.insertAfter(newBlock, ul);
      if (NodeType.isEmpty(editor.dom, li.parentNode)) {
        removeAndKeepBookmarks(li.parentNode);
      }
      DOM$2.remove(li);
      if (NodeType.isEmpty(editor.dom, ul)) {
        DOM$2.remove(ul);
      }
    };
    var SplitList = { splitList: splitList };

    var liftN = function (arr, f) {
      var r = [];
      for (var i = 0; i < arr.length; i++) {
        var x = arr[i];
        if (x.isSome()) {
          r.push(x.getOrDie());
        } else {
          return Option.none();
        }
      }
      return Option.some(f.apply(null, r));
    };

    var fromElements = function (elements, scope) {
      var doc = scope || document;
      var fragment = doc.createDocumentFragment();
      each(elements, function (element) {
        fragment.appendChild(element.dom());
      });
      return Element$$1.fromDom(fragment);
    };

    var isSupported = function (dom) {
      return dom.style !== undefined;
    };

    var internalSet = function (dom, property, value$$1) {
      if (!isString(value$$1)) {
        console.error('Invalid call to CSS.set. Property ', property, ':: Value ', value$$1, ':: Element ', dom);
        throw new Error('CSS value must be a string: ' + value$$1);
      }
      if (isSupported(dom))
        dom.style.setProperty(property, value$$1);
    };
    var set$1 = function (element, property, value$$1) {
      var dom = element.dom();
      internalSet(dom, property, value$$1);
    };

    var createSection = function (scope, listType) {
      var section = {
        list: Element$$1.fromTag(listType, scope),
        item: Element$$1.fromTag('li', scope)
      };
      append(section.list, section.item);
      return section;
    };
    var joinSections = function (parent, appendor) {
      append(parent.item, appendor.list);
    };
    var createJoinedSections = function (scope, length, listType) {
      var sections = [];
      var _loop_1 = function (i) {
        var newSection = createSection(scope, listType);
        last(sections).each(function (lastSection) {
          return joinSections(lastSection, newSection);
        });
        sections.push(newSection);
      };
      for (var i = 0; i < length; i++) {
        _loop_1(i);
      }
      return sections;
    };
    var normalizeSection = function (section, entry) {
      if (name(section.list).toUpperCase() !== entry.listType) {
        section.list = mutate(section.list, entry.listType);
      }
      setAll(section.list, entry.listAttributes);
    };
    var createItem = function (scope, attr, content) {
      var item = Element$$1.fromTag('li', scope);
      setAll(item, attr);
      append$1(item, content);
      return item;
    };
    var setItem = function (section, item) {
      append(section.list, item);
      section.item = item;
    };
    var writeShallow = function (scope, outline, entry) {
      var newOutline = outline.slice(0, entry.depth);
      last(newOutline).each(function (section) {
        setItem(section, createItem(scope, entry.itemAttributes, entry.content));
        normalizeSection(section, entry);
      });
      return newOutline;
    };
    var populateSections = function (sections, entry) {
      last(sections).each(function (section) {
        setAll(section.list, entry.listAttributes);
        setAll(section.item, entry.itemAttributes);
        append$1(section.item, entry.content);
      });
      for (var i = 0; i < sections.length - 1; i++) {
        set$1(sections[i].item, 'list-style-type', 'none');
      }
    };
    var writeDeep = function (scope, outline, entry) {
      var newSections = createJoinedSections(scope, entry.depth - outline.length, entry.listType);
      populateSections(newSections, entry);
      liftN([
        last(outline),
        head(newSections)
      ], joinSections);
      return outline.concat(newSections);
    };
    var composeList = function (scope, entries) {
      var outline = foldl(entries, function (outline, entry) {
        return entry.depth > outline.length ? writeDeep(scope, outline, entry) : writeShallow(scope, outline, entry);
      }, []);
      return head(outline).map(function (section) {
        return section.list;
      });
    };

    var isIndented = function (entry) {
      return entry.depth > 0;
    };
    var isSelected = function (entry) {
      return entry.isSelected;
    };

    var indentEntry = function (indentation, entry) {
      switch (indentation) {
      case 'Indent':
        entry.depth++;
        break;
      case 'Outdent':
        entry.depth--;
        break;
      case 'Flatten':
        entry.depth = 0;
      }
    };

    var hasOwnProperty$1 = Object.prototype.hasOwnProperty;
    var shallow$1 = function (old, nu) {
      return nu;
    };
    var baseMerge = function (merger) {
      return function () {
        var objects = new Array(arguments.length);
        for (var i = 0; i < objects.length; i++)
          objects[i] = arguments[i];
        if (objects.length === 0)
          throw new Error('Can\'t merge zero objects');
        var ret = {};
        for (var j = 0; j < objects.length; j++) {
          var curObject = objects[j];
          for (var key in curObject)
            if (hasOwnProperty$1.call(curObject, key)) {
              ret[key] = merger(ret[key], curObject[key]);
            }
        }
        return ret;
      };
    };
    var merge = baseMerge(shallow$1);

    var assimilateEntry = function (adherent, source) {
      adherent.listType = source.listType;
      adherent.listAttributes = merge({}, source.listAttributes);
    };
    var normalizeShallow = function (outline, entry) {
      var matchingEntryDepth = entry.depth - 1;
      outline[matchingEntryDepth].each(function (matchingEntry) {
        return assimilateEntry(entry, matchingEntry);
      });
      var newOutline = outline.slice(0, matchingEntryDepth);
      newOutline.push(Option.some(entry));
      return newOutline;
    };
    var normalizeDeep = function (outline, entry) {
      var newOutline = outline.slice(0);
      var diff = entry.depth - outline.length;
      for (var i = 1; i < diff; i++) {
        newOutline.push(Option.none());
      }
      newOutline.push(Option.some(entry));
      return newOutline;
    };
    var normalizeEntries = function (entries) {
      foldl(entries, function (outline, entry) {
        return entry.depth > outline.length ? normalizeDeep(outline, entry) : normalizeShallow(outline, entry);
      }, []);
    };

    var Cell = function (initial) {
      var value = initial;
      var get = function () {
        return value;
      };
      var set = function (v) {
        value = v;
      };
      var clone = function () {
        return Cell(get());
      };
      return {
        get: get,
        set: set,
        clone: clone
      };
    };

    var ListType;
    (function (ListType) {
      ListType['OL'] = 'OL';
      ListType['UL'] = 'UL';
      ListType['DL'] = 'DL';
    }(ListType || (ListType = {})));
    var getListType = function (list) {
      switch (name(list)) {
      case 'ol':
        return Option.some(ListType.OL);
      case 'ul':
        return Option.some(ListType.UL);
      case 'dl':
        return Option.some(ListType.DL);
      default:
        return Option.none();
      }
    };
    var isList$1 = function (el) {
      return is$1(el, 'OL,UL,DL');
    };

    var hasFirstChildList = function (li) {
      return firstChild(li).map(isList$1).getOr(false);
    };
    var hasLastChildList = function (li) {
      return lastChild(li).map(isList$1).getOr(false);
    };

    var getItemContent = function (li) {
      var childNodes = children(li);
      var contentLength = childNodes.length + (hasLastChildList(li) ? -1 : 0);
      return map(childNodes.slice(0, contentLength), deep);
    };
    var createEntry = function (li, depth, isSelected) {
      var list = parent(li);
      return {
        depth: depth,
        isSelected: isSelected,
        content: getItemContent(li),
        listType: list.bind(getListType).getOr(ListType.OL),
        listAttributes: list.map(clone).getOr({}),
        itemAttributes: clone(li)
      };
    };
    var parseItem = function (depth, itemSelection, selectionState, item) {
      var curriedParseList = curry(parseList, depth, itemSelection, selectionState);
      var updateSelectionState = function (itemRange) {
        return itemSelection.each(function (selection) {
          if (eq(itemRange === 'Start' ? selection.start : selection.end, item)) {
            selectionState.set(itemRange === 'Start');
          }
        });
      };
      return firstChild(item).filter(isList$1).fold(function () {
        updateSelectionState('Start');
        var fromCurrentItem = createEntry(item, depth, selectionState.get());
        updateSelectionState('End');
        var fromChildList = lastChild(item).filter(isList$1).map(curriedParseList).getOr([]);
        return [fromCurrentItem].concat(fromChildList);
      }, curriedParseList);
    };
    var parseList = function (depth, itemSelection, selectionState, list) {
      var newDepth = depth + 1;
      return bind(children(list), function (child$$1) {
        return isList$1(child$$1) ? parseList(newDepth, itemSelection, selectionState, child$$1) : parseItem(newDepth, itemSelection, selectionState, child$$1);
      });
    };
    var parseLists = function (lists, itemSelection) {
      var selectionState = Cell(false);
      var initialDepth = 0;
      return map(lists, function (list) {
        return {
          entries: parseList(initialDepth, itemSelection, selectionState, list),
          sourceList: list
        };
      });
    };

    var outdentedComposer = function (editor, entries) {
      return map(entries, function (entry) {
        var content = fromElements(entry.content);
        return Element$$1.fromDom(TextBlock.createNewTextBlock(editor, content.dom()));
      });
    };
    var indentedComposer = function (editor, entries) {
      normalizeEntries(entries);
      return composeList(editor.contentDocument, entries).toArray();
    };
    var composeEntries = function (editor, entries) {
      return bind(groupBy(entries, isIndented), function (entries) {
        var groupIsIndented = head(entries).map(isIndented).getOr(false);
        return groupIsIndented ? indentedComposer(editor, entries) : outdentedComposer(editor, entries);
      });
    };
    var indentSelectedEntries = function (entries, indentation) {
      each(filter(entries, isSelected), function (entry) {
        return indentEntry(indentation, entry);
      });
    };
    var getItemSelection = function (editor) {
      var selectedListItems = map(Selection.getSelectedListItems(editor), Element$$1.fromDom);
      return liftN([
        find(selectedListItems, not(hasFirstChildList)),
        find(reverse(selectedListItems), not(hasFirstChildList))
      ], function (start, end) {
        return {
          start: start,
          end: end
        };
      });
    };
    var listsIndentation = function (editor, lists, indentation) {
      var parsedLists = parseLists(lists, getItemSelection(editor));
      each(parsedLists, function (entrySet) {
        indentSelectedEntries(entrySet.entries, indentation);
        before$1(entrySet.sourceList, composeEntries(editor, entrySet.entries));
        remove$1(entrySet.sourceList);
      });
    };

    var outdentDlItem = function (editor, item) {
      if (is$1(item, 'DD')) {
        mutate(item, 'DT');
      } else if (is$1(item, 'DT')) {
        parent(item).each(function (dl) {
          return SplitList.splitList(editor, dl.dom(), item.dom());
        });
      }
    };
    var indentDlItem = function (item) {
      if (is$1(item, 'DT')) {
        mutate(item, 'DD');
      }
    };
    var dlIndentation = function (editor, indentation, dlItems) {
      if (indentation === 'Indent') {
        each(dlItems, indentDlItem);
      } else {
        each(dlItems, function (item) {
          return outdentDlItem(editor, item);
        });
      }
    };
    var selectionIndentation = function (editor, indentation) {
      var dlItems = map(Selection.getSelectedDlItems(editor), Element$$1.fromDom);
      var lists = map(Selection.getSelectedListRoots(editor), Element$$1.fromDom);
      if (dlItems.length || lists.length) {
        var bookmark = editor.selection.getBookmark();
        dlIndentation(editor, indentation, dlItems);
        listsIndentation(editor, lists, indentation);
        editor.selection.moveToBookmark(bookmark);
        editor.selection.setRng(Range.normalizeRange(editor.selection.getRng()));
        editor.nodeChanged();
      }
    };
    var indentListSelection = function (editor) {
      selectionIndentation(editor, 'Indent');
    };
    var outdentListSelection = function (editor) {
      selectionIndentation(editor, 'Outdent');
    };
    var flattenListSelection = function (editor) {
      selectionIndentation(editor, 'Flatten');
    };

    var updateListStyle = function (dom, el, detail) {
      var type = detail['list-style-type'] ? detail['list-style-type'] : null;
      dom.setStyle(el, 'list-style-type', type);
    };
    var setAttribs = function (elm, attrs) {
      global$5.each(attrs, function (value, key) {
        elm.setAttribute(key, value);
      });
    };
    var updateListAttrs = function (dom, el, detail) {
      setAttribs(el, detail['list-attributes']);
      global$5.each(dom.select('li', el), function (li) {
        setAttribs(li, detail['list-item-attributes']);
      });
    };
    var updateListWithDetails = function (dom, el, detail) {
      updateListStyle(dom, el, detail);
      updateListAttrs(dom, el, detail);
    };
    var removeStyles = function (dom, element, styles) {
      global$5.each(styles, function (style) {
        var _a;
        return dom.setStyle(element, (_a = {}, _a[style] = '', _a));
      });
    };
    var getEndPointNode = function (editor, rng, start, root) {
      var container, offset;
      container = rng[start ? 'startContainer' : 'endContainer'];
      offset = rng[start ? 'startOffset' : 'endOffset'];
      if (container.nodeType === 1) {
        container = container.childNodes[Math.min(offset, container.childNodes.length - 1)] || container;
      }
      if (!start && NodeType.isBr(container.nextSibling)) {
        container = container.nextSibling;
      }
      while (container.parentNode !== root) {
        if (NodeType.isTextBlock(editor, container)) {
          return container;
        }
        if (/^(TD|TH)$/.test(container.parentNode.nodeName)) {
          return container;
        }
        container = container.parentNode;
      }
      return container;
    };
    var getSelectedTextBlocks = function (editor, rng, root) {
      var textBlocks = [], dom = editor.dom;
      var startNode = getEndPointNode(editor, rng, true, root);
      var endNode = getEndPointNode(editor, rng, false, root);
      var block;
      var siblings = [];
      for (var node = startNode; node; node = node.nextSibling) {
        siblings.push(node);
        if (node === endNode) {
          break;
        }
      }
      global$5.each(siblings, function (node) {
        if (NodeType.isTextBlock(editor, node)) {
          textBlocks.push(node);
          block = null;
          return;
        }
        if (dom.isBlock(node) || NodeType.isBr(node)) {
          if (NodeType.isBr(node)) {
            dom.remove(node);
          }
          block = null;
          return;
        }
        var nextSibling = node.nextSibling;
        if (global$4.isBookmarkNode(node)) {
          if (NodeType.isTextBlock(editor, nextSibling) || !nextSibling && node.parentNode === root) {
            block = null;
            return;
          }
        }
        if (!block) {
          block = dom.create('p');
          node.parentNode.insertBefore(block, node);
          textBlocks.push(block);
        }
        block.appendChild(node);
      });
      return textBlocks;
    };
    var hasCompatibleStyle = function (dom, sib, detail) {
      var sibStyle = dom.getStyle(sib, 'list-style-type');
      var detailStyle = detail ? detail['list-style-type'] : '';
      detailStyle = detailStyle === null ? '' : detailStyle;
      return sibStyle === detailStyle;
    };
    var applyList = function (editor, listName, detail) {
      if (detail === void 0) {
        detail = {};
      }
      var rng = editor.selection.getRng(true);
      var bookmark;
      var listItemName = 'LI';
      var root = Selection.getClosestListRootElm(editor, editor.selection.getStart(true));
      var dom = editor.dom;
      if (dom.getContentEditable(editor.selection.getNode()) === 'false') {
        return;
      }
      listName = listName.toUpperCase();
      if (listName === 'DL') {
        listItemName = 'DT';
      }
      bookmark = Bookmark.createBookmark(rng);
      global$5.each(getSelectedTextBlocks(editor, rng, root), function (block) {
        var listBlock, sibling;
        sibling = block.previousSibling;
        if (sibling && NodeType.isListNode(sibling) && sibling.nodeName === listName && hasCompatibleStyle(dom, sibling, detail)) {
          listBlock = sibling;
          block = dom.rename(block, listItemName);
          sibling.appendChild(block);
        } else {
          listBlock = dom.create(listName);
          block.parentNode.insertBefore(listBlock, block);
          listBlock.appendChild(block);
          block = dom.rename(block, listItemName);
        }
        removeStyles(dom, block, [
          'margin',
          'margin-right',
          'margin-bottom',
          'margin-left',
          'margin-top',
          'padding',
          'padding-right',
          'padding-bottom',
          'padding-left',
          'padding-top'
        ]);
        updateListWithDetails(dom, listBlock, detail);
        mergeWithAdjacentLists(editor.dom, listBlock);
      });
      editor.selection.setRng(Bookmark.resolveBookmark(bookmark));
    };
    var isValidLists = function (list1, list2) {
      return list1 && list2 && NodeType.isListNode(list1) && list1.nodeName === list2.nodeName;
    };
    var hasSameListStyle = function (dom, list1, list2) {
      var targetStyle = dom.getStyle(list1, 'list-style-type', true);
      var style = dom.getStyle(list2, 'list-style-type', true);
      return targetStyle === style;
    };
    var hasSameClasses = function (elm1, elm2) {
      return elm1.className === elm2.className;
    };
    var shouldMerge = function (dom, list1, list2) {
      return isValidLists(list1, list2) && hasSameListStyle(dom, list1, list2) && hasSameClasses(list1, list2);
    };
    var mergeWithAdjacentLists = function (dom, listBlock) {
      var sibling, node;
      sibling = listBlock.nextSibling;
      if (shouldMerge(dom, listBlock, sibling)) {
        while (node = sibling.firstChild) {
          listBlock.appendChild(node);
        }
        dom.remove(sibling);
      }
      sibling = listBlock.previousSibling;
      if (shouldMerge(dom, listBlock, sibling)) {
        while (node = sibling.lastChild) {
          listBlock.insertBefore(node, listBlock.firstChild);
        }
        dom.remove(sibling);
      }
    };
    var updateList = function (dom, list, listName, detail) {
      if (list.nodeName !== listName) {
        var newList = dom.rename(list, listName);
        updateListWithDetails(dom, newList, detail);
      } else {
        updateListWithDetails(dom, list, detail);
      }
    };
    var toggleMultipleLists = function (editor, parentList, lists, listName, detail) {
      if (parentList.nodeName === listName && !hasListStyleDetail(detail)) {
        flattenListSelection(editor);
      } else {
        var bookmark = Bookmark.createBookmark(editor.selection.getRng(true));
        global$5.each([parentList].concat(lists), function (elm) {
          updateList(editor.dom, elm, listName, detail);
        });
        editor.selection.setRng(Bookmark.resolveBookmark(bookmark));
      }
    };
    var hasListStyleDetail = function (detail) {
      return 'list-style-type' in detail;
    };
    var toggleSingleList = function (editor, parentList, listName, detail) {
      if (parentList === editor.getBody()) {
        return;
      }
      if (parentList) {
        if (parentList.nodeName === listName && !hasListStyleDetail(detail)) {
          flattenListSelection(editor);
        } else {
          var bookmark = Bookmark.createBookmark(editor.selection.getRng(true));
          updateListWithDetails(editor.dom, parentList, detail);
          mergeWithAdjacentLists(editor.dom, editor.dom.rename(parentList, listName));
          editor.selection.setRng(Bookmark.resolveBookmark(bookmark));
        }
      } else {
        applyList(editor, listName, detail);
      }
    };
    var toggleList = function (editor, listName, detail) {
      var parentList = Selection.getParentList(editor);
      var selectedSubLists = Selection.getSelectedSubLists(editor);
      detail = detail ? detail : {};
      if (parentList && selectedSubLists.length > 0) {
        toggleMultipleLists(editor, parentList, selectedSubLists, listName, detail);
      } else {
        toggleSingleList(editor, parentList, listName, detail);
      }
    };
    var ToggleList = {
      toggleList: toggleList,
      mergeWithAdjacentLists: mergeWithAdjacentLists
    };

    var DOM$3 = global$6.DOM;
    var normalizeList = function (dom, ul) {
      var sibling;
      var parentNode = ul.parentNode;
      if (parentNode.nodeName === 'LI' && parentNode.firstChild === ul) {
        sibling = parentNode.previousSibling;
        if (sibling && sibling.nodeName === 'LI') {
          sibling.appendChild(ul);
          if (NodeType.isEmpty(dom, parentNode)) {
            DOM$3.remove(parentNode);
          }
        } else {
          DOM$3.setStyle(parentNode, 'listStyleType', 'none');
        }
      }
      if (NodeType.isListNode(parentNode)) {
        sibling = parentNode.previousSibling;
        if (sibling && sibling.nodeName === 'LI') {
          sibling.appendChild(ul);
        }
      }
    };
    var normalizeLists = function (dom, element) {
      global$5.each(global$5.grep(dom.select('ol,ul', element)), function (ul) {
        normalizeList(dom, ul);
      });
    };
    var NormalizeLists = {
      normalizeList: normalizeList,
      normalizeLists: normalizeLists
    };

    var findNextCaretContainer = function (editor, rng, isForward, root) {
      var node = rng.startContainer;
      var offset = rng.startOffset;
      var nonEmptyBlocks, walker;
      if (node.nodeType === 3 && (isForward ? offset < node.data.length : offset > 0)) {
        return node;
      }
      nonEmptyBlocks = editor.schema.getNonEmptyElements();
      if (node.nodeType === 1) {
        node = global$1.getNode(node, offset);
      }
      walker = new global$2(node, root);
      if (isForward) {
        if (NodeType.isBogusBr(editor.dom, node)) {
          walker.next();
        }
      }
      while (node = walker[isForward ? 'next' : 'prev2']()) {
        if (node.nodeName === 'LI' && !node.hasChildNodes()) {
          return node;
        }
        if (nonEmptyBlocks[node.nodeName]) {
          return node;
        }
        if (node.nodeType === 3 && node.data.length > 0) {
          return node;
        }
      }
    };
    var hasOnlyOneBlockChild = function (dom, elm) {
      var childNodes = elm.childNodes;
      return childNodes.length === 1 && !NodeType.isListNode(childNodes[0]) && dom.isBlock(childNodes[0]);
    };
    var unwrapSingleBlockChild = function (dom, elm) {
      if (hasOnlyOneBlockChild(dom, elm)) {
        dom.remove(elm.firstChild, true);
      }
    };
    var moveChildren = function (dom, fromElm, toElm) {
      var node, targetElm;
      targetElm = hasOnlyOneBlockChild(dom, toElm) ? toElm.firstChild : toElm;
      unwrapSingleBlockChild(dom, fromElm);
      if (!NodeType.isEmpty(dom, fromElm, true)) {
        while (node = fromElm.firstChild) {
          targetElm.appendChild(node);
        }
      }
    };
    var mergeLiElements = function (dom, fromElm, toElm) {
      var node, listNode;
      var ul = fromElm.parentNode;
      if (!NodeType.isChildOfBody(dom, fromElm) || !NodeType.isChildOfBody(dom, toElm)) {
        return;
      }
      if (NodeType.isListNode(toElm.lastChild)) {
        listNode = toElm.lastChild;
      }
      if (ul === toElm.lastChild) {
        if (NodeType.isBr(ul.previousSibling)) {
          dom.remove(ul.previousSibling);
        }
      }
      node = toElm.lastChild;
      if (node && NodeType.isBr(node) && fromElm.hasChildNodes()) {
        dom.remove(node);
      }
      if (NodeType.isEmpty(dom, toElm, true)) {
        dom.$(toElm).empty();
      }
      moveChildren(dom, fromElm, toElm);
      if (listNode) {
        toElm.appendChild(listNode);
      }
      dom.remove(fromElm);
      if (NodeType.isEmpty(dom, ul) && ul !== dom.getRoot()) {
        dom.remove(ul);
      }
    };
    var mergeIntoEmptyLi = function (editor, fromLi, toLi) {
      editor.dom.$(toLi).empty();
      mergeLiElements(editor.dom, fromLi, toLi);
      editor.selection.setCursorLocation(toLi);
    };
    var mergeForward = function (editor, rng, fromLi, toLi) {
      var dom = editor.dom;
      if (dom.isEmpty(toLi)) {
        mergeIntoEmptyLi(editor, fromLi, toLi);
      } else {
        var bookmark = Bookmark.createBookmark(rng);
        mergeLiElements(dom, fromLi, toLi);
        editor.selection.setRng(Bookmark.resolveBookmark(bookmark));
      }
    };
    var mergeBackward = function (editor, rng, fromLi, toLi) {
      var bookmark = Bookmark.createBookmark(rng);
      mergeLiElements(editor.dom, fromLi, toLi);
      var resolvedBookmark = Bookmark.resolveBookmark(bookmark);
      editor.selection.setRng(resolvedBookmark);
    };
    var backspaceDeleteFromListToListCaret = function (editor, isForward) {
      var dom = editor.dom, selection = editor.selection;
      var selectionStartElm = selection.getStart();
      var root = Selection.getClosestListRootElm(editor, selectionStartElm);
      var li = dom.getParent(selection.getStart(), 'LI', root);
      var ul, rng, otherLi;
      if (li) {
        ul = li.parentNode;
        if (ul === editor.getBody() && NodeType.isEmpty(dom, ul)) {
          return true;
        }
        rng = Range.normalizeRange(selection.getRng(true));
        otherLi = dom.getParent(findNextCaretContainer(editor, rng, isForward, root), 'LI', root);
        if (otherLi && otherLi !== li) {
          if (isForward) {
            mergeForward(editor, rng, otherLi, li);
          } else {
            mergeBackward(editor, rng, li, otherLi);
          }
          return true;
        } else if (!otherLi) {
          if (!isForward) {
            flattenListSelection(editor);
            return true;
          }
        }
      }
      return false;
    };
    var removeBlock = function (dom, block, root) {
      var parentBlock = dom.getParent(block.parentNode, dom.isBlock, root);
      dom.remove(block);
      if (parentBlock && dom.isEmpty(parentBlock)) {
        dom.remove(parentBlock);
      }
    };
    var backspaceDeleteIntoListCaret = function (editor, isForward) {
      var dom = editor.dom;
      var selectionStartElm = editor.selection.getStart();
      var root = Selection.getClosestListRootElm(editor, selectionStartElm);
      var block = dom.getParent(selectionStartElm, dom.isBlock, root);
      if (block && dom.isEmpty(block)) {
        var rng = Range.normalizeRange(editor.selection.getRng(true));
        var otherLi_1 = dom.getParent(findNextCaretContainer(editor, rng, isForward, root), 'LI', root);
        if (otherLi_1) {
          editor.undoManager.transact(function () {
            removeBlock(dom, block, root);
            ToggleList.mergeWithAdjacentLists(dom, otherLi_1.parentNode);
            editor.selection.select(otherLi_1, true);
            editor.selection.collapse(isForward);
          });
          return true;
        }
      }
      return false;
    };
    var backspaceDeleteCaret = function (editor, isForward) {
      return backspaceDeleteFromListToListCaret(editor, isForward) || backspaceDeleteIntoListCaret(editor, isForward);
    };
    var backspaceDeleteRange = function (editor) {
      var selectionStartElm = editor.selection.getStart();
      var root = Selection.getClosestListRootElm(editor, selectionStartElm);
      var startListParent = editor.dom.getParent(selectionStartElm, 'LI,DT,DD', root);
      if (startListParent || Selection.getSelectedListItems(editor).length > 0) {
        editor.undoManager.transact(function () {
          editor.execCommand('Delete');
          NormalizeLists.normalizeLists(editor.dom, editor.getBody());
        });
        return true;
      }
      return false;
    };
    var backspaceDelete = function (editor, isForward) {
      return editor.selection.isCollapsed() ? backspaceDeleteCaret(editor, isForward) : backspaceDeleteRange(editor);
    };
    var setup = function (editor) {
      editor.on('keydown', function (e) {
        if (e.keyCode === global$3.BACKSPACE) {
          if (backspaceDelete(editor, false)) {
            e.preventDefault();
          }
        } else if (e.keyCode === global$3.DELETE) {
          if (backspaceDelete(editor, true)) {
            e.preventDefault();
          }
        }
      });
    };
    var Delete = {
      setup: setup,
      backspaceDelete: backspaceDelete
    };

    var get$3 = function (editor) {
      return {
        backspaceDelete: function (isForward) {
          Delete.backspaceDelete(editor, isForward);
        }
      };
    };
    var Api = { get: get$3 };

    var queryListCommandState = function (editor, listName) {
      return function () {
        var parentList = editor.dom.getParent(editor.selection.getStart(), 'UL,OL,DL');
        return parentList && parentList.nodeName === listName;
      };
    };
    var register = function (editor) {
      editor.on('BeforeExecCommand', function (e) {
        var cmd = e.command.toLowerCase();
        if (cmd === 'indent') {
          indentListSelection(editor);
        } else if (cmd === 'outdent') {
          outdentListSelection(editor);
        }
      });
      editor.addCommand('InsertUnorderedList', function (ui, detail) {
        ToggleList.toggleList(editor, 'UL', detail);
      });
      editor.addCommand('InsertOrderedList', function (ui, detail) {
        ToggleList.toggleList(editor, 'OL', detail);
      });
      editor.addCommand('InsertDefinitionList', function (ui, detail) {
        ToggleList.toggleList(editor, 'DL', detail);
      });
      editor.addCommand('RemoveList', function () {
        flattenListSelection(editor);
      });
      editor.addQueryStateHandler('InsertUnorderedList', queryListCommandState(editor, 'UL'));
      editor.addQueryStateHandler('InsertOrderedList', queryListCommandState(editor, 'OL'));
      editor.addQueryStateHandler('InsertDefinitionList', queryListCommandState(editor, 'DL'));
    };
    var Commands = { register: register };

    var shouldIndentOnTab = function (editor) {
      return editor.getParam('lists_indent_on_tab', true);
    };
    var Settings = { shouldIndentOnTab: shouldIndentOnTab };

    var setupTabKey = function (editor) {
      editor.on('keydown', function (e) {
        if (e.keyCode !== global$3.TAB || global$3.metaKeyPressed(e)) {
          return;
        }
        if (Selection.isList(editor)) {
          e.preventDefault();
          editor.undoManager.transact(function () {
            if (e.shiftKey) {
              outdentListSelection(editor);
            } else {
              indentListSelection(editor);
            }
          });
        }
      });
    };
    var setup$1 = function (editor) {
      if (Settings.shouldIndentOnTab(editor)) {
        setupTabKey(editor);
      }
      Delete.setup(editor);
    };
    var Keyboard = { setup: setup$1 };

    var findIndex$2 = function (list, predicate) {
      for (var index = 0; index < list.length; index++) {
        var element = list[index];
        if (predicate(element)) {
          return index;
        }
      }
      return -1;
    };
    var listState = function (editor, listName) {
      return function (e) {
        var ctrl = e.control;
        editor.on('NodeChange', function (e) {
          var tableCellIndex = findIndex$2(e.parents, NodeType.isTableCellNode);
          var parents = tableCellIndex !== -1 ? e.parents.slice(0, tableCellIndex) : e.parents;
          var lists = global$5.grep(parents, NodeType.isListNode);
          ctrl.active(lists.length > 0 && lists[0].nodeName === listName);
        });
      };
    };
    var register$1 = function (editor) {
      var hasPlugin = function (editor, plugin) {
        var plugins = editor.settings.plugins ? editor.settings.plugins : '';
        return global$5.inArray(plugins.split(/[ ,]/), plugin) !== -1;
      };
      if (!hasPlugin(editor, 'advlist')) {
        editor.addButton('numlist', {
          active: false,
          title: 'Numbered list',
          cmd: 'InsertOrderedList',
          onPostRender: listState(editor, 'OL')
        });
        editor.addButton('bullist', {
          active: false,
          title: 'Bullet list',
          cmd: 'InsertUnorderedList',
          onPostRender: listState(editor, 'UL')
        });
      }
      editor.addButton('indent', {
        icon: 'indent',
        title: 'Increase indent',
        cmd: 'Indent'
      });
    };
    var Buttons = { register: register$1 };

    global.add('lists', function (editor) {
      Keyboard.setup(editor);
      Buttons.register(editor);
      Commands.register(editor);
      return Api.get(editor);
    });
    function Plugin () {
    }

    return Plugin;

}());
})();


/***/ }),
/* 138 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(139);

/***/ }),
/* 139 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);
var bind = __webpack_require__(69);
var Axios = __webpack_require__(141);
var defaults = __webpack_require__(30);

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(utils.merge(defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__(73);
axios.CancelToken = __webpack_require__(155);
axios.isCancel = __webpack_require__(72);

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__(156);

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),
/* 140 */
/***/ (function(module, exports) {

/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <https://feross.org>
 * @license  MIT
 */

// The _isBuffer check is for Safari 5-7 support, because it's missing
// Object.prototype.constructor. Remove this eventually
module.exports = function (obj) {
  return obj != null && (isBuffer(obj) || isSlowBuffer(obj) || !!obj._isBuffer)
}

function isBuffer (obj) {
  return !!obj.constructor && typeof obj.constructor.isBuffer === 'function' && obj.constructor.isBuffer(obj)
}

// For Node v0.10 support. Remove this eventually.
function isSlowBuffer (obj) {
  return typeof obj.readFloatLE === 'function' && typeof obj.slice === 'function' && isBuffer(obj.slice(0, 0))
}


/***/ }),
/* 141 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var defaults = __webpack_require__(30);
var utils = __webpack_require__(3);
var InterceptorManager = __webpack_require__(150);
var dispatchRequest = __webpack_require__(151);

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = utils.merge({
      url: arguments[0]
    }, arguments[1]);
  }

  config = utils.merge(defaults, {method: 'get'}, this.defaults, config);
  config.method = config.method.toLowerCase();

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),
/* 142 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),
/* 143 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__(71);

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  // Note: status is not exposed by XDomainRequest
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response.request,
      response
    ));
  }
};


/***/ }),
/* 144 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, request, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }
  error.request = request;
  error.response = response;
  return error;
};


/***/ }),
/* 145 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

function encode(val) {
  return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      } else {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),
/* 146 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

// Headers whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
var ignoreDuplicateOf = [
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
];

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      if (parsed[key] && ignoreDuplicateOf.indexOf(key) >= 0) {
        return;
      }
      if (key === 'set-cookie') {
        parsed[key] = (parsed[key] ? parsed[key] : []).concat([val]);
      } else {
        parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
      }
    }
  });

  return parsed;
};


/***/ }),
/* 147 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
  (function standardBrowserEnv() {
    var msie = /(msie|trident)/i.test(navigator.userAgent);
    var urlParsingNode = document.createElement('a');
    var originURL;

    /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
    function resolveURL(url) {
      var href = url;

      if (msie) {
        // IE needs attribute set twice to normalize properties
        urlParsingNode.setAttribute('href', href);
        href = urlParsingNode.href;
      }

      urlParsingNode.setAttribute('href', href);

      // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
      return {
        href: urlParsingNode.href,
        protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
        host: urlParsingNode.host,
        search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
        hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
        hostname: urlParsingNode.hostname,
        port: urlParsingNode.port,
        pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
                  urlParsingNode.pathname :
                  '/' + urlParsingNode.pathname
      };
    }

    originURL = resolveURL(window.location.href);

    /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
    return function isURLSameOrigin(requestURL) {
      var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
      return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
    };
  })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return function isURLSameOrigin() {
      return true;
    };
  })()
);


/***/ }),
/* 148 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// btoa polyfill for IE<10 courtesy https://github.com/davidchambers/Base64.js

var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

function E() {
  this.message = 'String contains an invalid character';
}
E.prototype = new Error;
E.prototype.code = 5;
E.prototype.name = 'InvalidCharacterError';

function btoa(input) {
  var str = String(input);
  var output = '';
  for (
    // initialize result and counter
    var block, charCode, idx = 0, map = chars;
    // if the next str index does not exist:
    //   change the mapping table to "="
    //   check if d has no fractional digits
    str.charAt(idx | 0) || (map = '=', idx % 1);
    // "8 - idx % 1 * 8" generates the sequence 2, 4, 6, 8
    output += map.charAt(63 & block >> 8 - idx % 1 * 8)
  ) {
    charCode = str.charCodeAt(idx += 3 / 4);
    if (charCode > 0xFF) {
      throw new E();
    }
    block = block << 8 | charCode;
  }
  return output;
}

module.exports = btoa;


/***/ }),
/* 149 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
  (function standardBrowserEnv() {
    return {
      write: function write(name, value, expires, path, domain, secure) {
        var cookie = [];
        cookie.push(name + '=' + encodeURIComponent(value));

        if (utils.isNumber(expires)) {
          cookie.push('expires=' + new Date(expires).toGMTString());
        }

        if (utils.isString(path)) {
          cookie.push('path=' + path);
        }

        if (utils.isString(domain)) {
          cookie.push('domain=' + domain);
        }

        if (secure === true) {
          cookie.push('secure');
        }

        document.cookie = cookie.join('; ');
      },

      read: function read(name) {
        var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
        return (match ? decodeURIComponent(match[3]) : null);
      },

      remove: function remove(name) {
        this.write(name, '', Date.now() - 86400000);
      }
    };
  })() :

  // Non standard browser env (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return {
      write: function write() {},
      read: function read() { return null; },
      remove: function remove() {}
    };
  })()
);


/***/ }),
/* 150 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),
/* 151 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);
var transformData = __webpack_require__(152);
var isCancel = __webpack_require__(72);
var defaults = __webpack_require__(30);
var isAbsoluteURL = __webpack_require__(153);
var combineURLs = __webpack_require__(154);

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Support baseURL config
  if (config.baseURL && !isAbsoluteURL(config.url)) {
    config.url = combineURLs(config.baseURL, config.url);
  }

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers || {}
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),
/* 152 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(3);

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),
/* 153 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),
/* 154 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
};


/***/ }),
/* 155 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__(73);

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),
/* 156 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),
/* 157 */,
/* 158 */,
/* 159 */,
/* 160 */,
/* 161 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(162)
/* script */
var __vue_script__ = __webpack_require__(163)
/* template */
var __vue_template__ = __webpack_require__(164)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/select2multags.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3aa514be", Component.options)
  } else {
    hotAPI.reload("data-v-3aa514be", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 162 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 163 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function($) {function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['options', 'value'],
  template: '#select2-template',
  mounted: function mounted() {
    var vm = this;
    $(this.$el)
    // init select2
    .select2({ data: this.options, multiple: true, tags: true }).val(this.value).trigger('change')
    // emit event on change.
    .on('change', function () {
      vm.$emit('input', $(this).val());
    });
  },
  watch: {
    value: function value(_value) {
      //revisar que los valores de input sean diferentes de los ya en en select
      if ([].concat(_toConsumableArray(_value)).sort().join(",") !== [].concat(_toConsumableArray($(this.$el).val())).sort().join(",")) $(this.$el).val(_value).trigger('change');
    },
    options: function options(_options) {
      // update options
      $(this.$el).empty().select2({ data: _options, multiple: true, tags: true });
    }
  },
  destroyed: function destroyed() {
    $(this.$el).off().select2('destroy');
  }
});
/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(0)))

/***/ }),
/* 164 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("select", [_vm._t("default")], 2)
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-3aa514be", module.exports)
  }
}

/***/ }),
/* 165 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 166 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
],[79]);