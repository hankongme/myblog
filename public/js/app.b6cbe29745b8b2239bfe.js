/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
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
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/axios/index.js":
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./node_modules/axios/lib/axios.js");

/***/ }),

/***/ "./node_modules/axios/lib/adapters/xhr.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var settle = __webpack_require__("./node_modules/axios/lib/core/settle.js");
var buildURL = __webpack_require__("./node_modules/axios/lib/helpers/buildURL.js");
var parseHeaders = __webpack_require__("./node_modules/axios/lib/helpers/parseHeaders.js");
var isURLSameOrigin = __webpack_require__("./node_modules/axios/lib/helpers/isURLSameOrigin.js");
var createError = __webpack_require__("./node_modules/axios/lib/core/createError.js");
var btoa = (typeof window !== 'undefined' && window.btoa && window.btoa.bind(window)) || __webpack_require__("./node_modules/axios/lib/helpers/btoa.js");

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
        // IE sends 1223 instead of 204 (https://github.com/mzabriskie/axios/issues/201)
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
      reject(createError('Network Error', config));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      reject(createError('timeout of ' + config.timeout + 'ms exceeded', config, 'ECONNABORTED'));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__("./node_modules/axios/lib/helpers/cookies.js");

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
        if (request.responseType !== 'json') {
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

/***/ "./node_modules/axios/lib/axios.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var bind = __webpack_require__("./node_modules/axios/lib/helpers/bind.js");
var Axios = __webpack_require__("./node_modules/axios/lib/core/Axios.js");
var defaults = __webpack_require__("./node_modules/axios/lib/defaults.js");

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
axios.Cancel = __webpack_require__("./node_modules/axios/lib/cancel/Cancel.js");
axios.CancelToken = __webpack_require__("./node_modules/axios/lib/cancel/CancelToken.js");
axios.isCancel = __webpack_require__("./node_modules/axios/lib/cancel/isCancel.js");

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__("./node_modules/axios/lib/helpers/spread.js");

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),

/***/ "./node_modules/axios/lib/cancel/Cancel.js":
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

/***/ "./node_modules/axios/lib/cancel/CancelToken.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__("./node_modules/axios/lib/cancel/Cancel.js");

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

/***/ "./node_modules/axios/lib/cancel/isCancel.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/Axios.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var defaults = __webpack_require__("./node_modules/axios/lib/defaults.js");
var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var InterceptorManager = __webpack_require__("./node_modules/axios/lib/core/InterceptorManager.js");
var dispatchRequest = __webpack_require__("./node_modules/axios/lib/core/dispatchRequest.js");
var isAbsoluteURL = __webpack_require__("./node_modules/axios/lib/helpers/isAbsoluteURL.js");
var combineURLs = __webpack_require__("./node_modules/axios/lib/helpers/combineURLs.js");

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

  config = utils.merge(defaults, this.defaults, { method: 'get' }, config);

  // Support baseURL config
  if (config.baseURL && !isAbsoluteURL(config.url)) {
    config.url = combineURLs(config.baseURL, config.url);
  }

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
utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
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

/***/ "./node_modules/axios/lib/core/InterceptorManager.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

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

/***/ "./node_modules/axios/lib/core/createError.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__("./node_modules/axios/lib/core/enhanceError.js");

/**
 * Create an Error with the specified message, config, error code, and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 @ @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, response);
};


/***/ }),

/***/ "./node_modules/axios/lib/core/dispatchRequest.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var transformData = __webpack_require__("./node_modules/axios/lib/core/transformData.js");
var isCancel = __webpack_require__("./node_modules/axios/lib/cancel/isCancel.js");
var defaults = __webpack_require__("./node_modules/axios/lib/defaults.js");

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

/***/ "./node_modules/axios/lib/core/enhanceError.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 @ @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }
  error.response = response;
  return error;
};


/***/ }),

/***/ "./node_modules/axios/lib/core/settle.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__("./node_modules/axios/lib/core/createError.js");

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
      response
    ));
  }
};


/***/ }),

/***/ "./node_modules/axios/lib/core/transformData.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

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

/***/ "./node_modules/axios/lib/defaults.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__("./node_modules/axios/lib/utils.js");
var normalizeHeaderName = __webpack_require__("./node_modules/axios/lib/helpers/normalizeHeaderName.js");

var PROTECTION_PREFIX = /^\)\]\}',?\n/;
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
    adapter = __webpack_require__("./node_modules/axios/lib/adapters/xhr.js");
  } else if (typeof process !== 'undefined') {
    // For node use HTTP adapter
    adapter = __webpack_require__("./node_modules/axios/lib/adapters/xhr.js");
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
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
      data = data.replace(PROTECTION_PREFIX, '');
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

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

utils.forEach(['delete', 'get', 'head'], function forEachMehtodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__("./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/axios/lib/helpers/bind.js":
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

/***/ "./node_modules/axios/lib/helpers/btoa.js":
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

/***/ "./node_modules/axios/lib/helpers/buildURL.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

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
      }

      if (!utils.isArray(val)) {
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

/***/ "./node_modules/axios/lib/helpers/combineURLs.js":
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
  return baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '');
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/cookies.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

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

/***/ "./node_modules/axios/lib/helpers/isAbsoluteURL.js":
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

/***/ "./node_modules/axios/lib/helpers/isURLSameOrigin.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

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

/***/ "./node_modules/axios/lib/helpers/normalizeHeaderName.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseHeaders.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__("./node_modules/axios/lib/utils.js");

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
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
};


/***/ }),

/***/ "./node_modules/axios/lib/helpers/spread.js":
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

/***/ "./node_modules/axios/lib/utils.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__("./node_modules/axios/lib/helpers/bind.js");

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
 *  typeof document.createElement -> undefined
 */
function isStandardBrowserEnv() {
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined' &&
    typeof document.createElement === 'function'
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
  if (typeof obj !== 'object' && !isArray(obj)) {
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

/***/ "./node_modules/bootstrap/dist/js/bootstrap.js":
/***/ (function(module, exports) {

/*!
 * Bootstrap v4.0.0-alpha.6 (https://getbootstrap.com)
 * Copyright 2011-2017 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */

if (typeof jQuery === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuery. jQuery must be included before Bootstrap\'s JavaScript.')
}

+function ($) {
  var version = $.fn.jquery.split(' ')[0].split('.')
  if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1) || (version[0] >= 4)) {
    throw new Error('Bootstrap\'s JavaScript requires at least jQuery v1.9.1 but less than v4.0.0')
  }
}(jQuery);


+function () {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): util.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Util = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Private TransitionEnd Helpers
   * ------------------------------------------------------------------------
   */

  var transition = false;

  var MAX_UID = 1000000;

  var TransitionEndEvent = {
    WebkitTransition: 'webkitTransitionEnd',
    MozTransition: 'transitionend',
    OTransition: 'oTransitionEnd otransitionend',
    transition: 'transitionend'
  };

  // shoutout AngusCroll (https://goo.gl/pxwQGp)
  function toType(obj) {
    return {}.toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase();
  }

  function isElement(obj) {
    return (obj[0] || obj).nodeType;
  }

  function getSpecialTransitionEndEvent() {
    return {
      bindType: transition.end,
      delegateType: transition.end,
      handle: function handle(event) {
        if ($(event.target).is(this)) {
          return event.handleObj.handler.apply(this, arguments); // eslint-disable-line prefer-rest-params
        }
        return undefined;
      }
    };
  }

  function transitionEndTest() {
    if (window.QUnit) {
      return false;
    }

    var el = document.createElement('bootstrap');

    for (var name in TransitionEndEvent) {
      if (el.style[name] !== undefined) {
        return {
          end: TransitionEndEvent[name]
        };
      }
    }

    return false;
  }

  function transitionEndEmulator(duration) {
    var _this = this;

    var called = false;

    $(this).one(Util.TRANSITION_END, function () {
      called = true;
    });

    setTimeout(function () {
      if (!called) {
        Util.triggerTransitionEnd(_this);
      }
    }, duration);

    return this;
  }

  function setTransitionEndSupport() {
    transition = transitionEndTest();

    $.fn.emulateTransitionEnd = transitionEndEmulator;

    if (Util.supportsTransitionEnd()) {
      $.event.special[Util.TRANSITION_END] = getSpecialTransitionEndEvent();
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Public Util Api
   * --------------------------------------------------------------------------
   */

  var Util = {

    TRANSITION_END: 'bsTransitionEnd',

    getUID: function getUID(prefix) {
      do {
        // eslint-disable-next-line no-bitwise
        prefix += ~~(Math.random() * MAX_UID); // "~~" acts like a faster Math.floor() here
      } while (document.getElementById(prefix));
      return prefix;
    },
    getSelectorFromElement: function getSelectorFromElement(element) {
      var selector = element.getAttribute('data-target');

      if (!selector) {
        selector = element.getAttribute('href') || '';
        selector = /^#[a-z]/i.test(selector) ? selector : null;
      }

      return selector;
    },
    reflow: function reflow(element) {
      return element.offsetHeight;
    },
    triggerTransitionEnd: function triggerTransitionEnd(element) {
      $(element).trigger(transition.end);
    },
    supportsTransitionEnd: function supportsTransitionEnd() {
      return Boolean(transition);
    },
    typeCheckConfig: function typeCheckConfig(componentName, config, configTypes) {
      for (var property in configTypes) {
        if (configTypes.hasOwnProperty(property)) {
          var expectedTypes = configTypes[property];
          var value = config[property];
          var valueType = value && isElement(value) ? 'element' : toType(value);

          if (!new RegExp(expectedTypes).test(valueType)) {
            throw new Error(componentName.toUpperCase() + ': ' + ('Option "' + property + '" provided type "' + valueType + '" ') + ('but expected type "' + expectedTypes + '".'));
          }
        }
      }
    }
  };

  setTransitionEndSupport();

  return Util;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): alert.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Alert = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'alert';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.alert';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var TRANSITION_DURATION = 150;

  var Selector = {
    DISMISS: '[data-dismiss="alert"]'
  };

  var Event = {
    CLOSE: 'close' + EVENT_KEY,
    CLOSED: 'closed' + EVENT_KEY,
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    ALERT: 'alert',
    FADE: 'fade',
    SHOW: 'show'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Alert = function () {
    function Alert(element) {
      _classCallCheck(this, Alert);

      this._element = element;
    }

    // getters

    // public

    Alert.prototype.close = function close(element) {
      element = element || this._element;

      var rootElement = this._getRootElement(element);
      var customEvent = this._triggerCloseEvent(rootElement);

      if (customEvent.isDefaultPrevented()) {
        return;
      }

      this._removeElement(rootElement);
    };

    Alert.prototype.dispose = function dispose() {
      $.removeData(this._element, DATA_KEY);
      this._element = null;
    };

    // private

    Alert.prototype._getRootElement = function _getRootElement(element) {
      var selector = Util.getSelectorFromElement(element);
      var parent = false;

      if (selector) {
        parent = $(selector)[0];
      }

      if (!parent) {
        parent = $(element).closest('.' + ClassName.ALERT)[0];
      }

      return parent;
    };

    Alert.prototype._triggerCloseEvent = function _triggerCloseEvent(element) {
      var closeEvent = $.Event(Event.CLOSE);

      $(element).trigger(closeEvent);
      return closeEvent;
    };

    Alert.prototype._removeElement = function _removeElement(element) {
      var _this2 = this;

      $(element).removeClass(ClassName.SHOW);

      if (!Util.supportsTransitionEnd() || !$(element).hasClass(ClassName.FADE)) {
        this._destroyElement(element);
        return;
      }

      $(element).one(Util.TRANSITION_END, function (event) {
        return _this2._destroyElement(element, event);
      }).emulateTransitionEnd(TRANSITION_DURATION);
    };

    Alert.prototype._destroyElement = function _destroyElement(element) {
      $(element).detach().trigger(Event.CLOSED).remove();
    };

    // static

    Alert._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var $element = $(this);
        var data = $element.data(DATA_KEY);

        if (!data) {
          data = new Alert(this);
          $element.data(DATA_KEY, data);
        }

        if (config === 'close') {
          data[config](this);
        }
      });
    };

    Alert._handleDismiss = function _handleDismiss(alertInstance) {
      return function (event) {
        if (event) {
          event.preventDefault();
        }

        alertInstance.close(this);
      };
    };

    _createClass(Alert, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }]);

    return Alert;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DISMISS, Alert._handleDismiss(new Alert()));

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Alert._jQueryInterface;
  $.fn[NAME].Constructor = Alert;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Alert._jQueryInterface;
  };

  return Alert;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): button.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Button = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'button';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.button';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];

  var ClassName = {
    ACTIVE: 'active',
    BUTTON: 'btn',
    FOCUS: 'focus'
  };

  var Selector = {
    DATA_TOGGLE_CARROT: '[data-toggle^="button"]',
    DATA_TOGGLE: '[data-toggle="buttons"]',
    INPUT: 'input',
    ACTIVE: '.active',
    BUTTON: '.btn'
  };

  var Event = {
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY,
    FOCUS_BLUR_DATA_API: 'focus' + EVENT_KEY + DATA_API_KEY + ' ' + ('blur' + EVENT_KEY + DATA_API_KEY)
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Button = function () {
    function Button(element) {
      _classCallCheck(this, Button);

      this._element = element;
    }

    // getters

    // public

    Button.prototype.toggle = function toggle() {
      var triggerChangeEvent = true;
      var rootElement = $(this._element).closest(Selector.DATA_TOGGLE)[0];

      if (rootElement) {
        var input = $(this._element).find(Selector.INPUT)[0];

        if (input) {
          if (input.type === 'radio') {
            if (input.checked && $(this._element).hasClass(ClassName.ACTIVE)) {
              triggerChangeEvent = false;
            } else {
              var activeElement = $(rootElement).find(Selector.ACTIVE)[0];

              if (activeElement) {
                $(activeElement).removeClass(ClassName.ACTIVE);
              }
            }
          }

          if (triggerChangeEvent) {
            input.checked = !$(this._element).hasClass(ClassName.ACTIVE);
            $(input).trigger('change');
          }

          input.focus();
        }
      }

      this._element.setAttribute('aria-pressed', !$(this._element).hasClass(ClassName.ACTIVE));

      if (triggerChangeEvent) {
        $(this._element).toggleClass(ClassName.ACTIVE);
      }
    };

    Button.prototype.dispose = function dispose() {
      $.removeData(this._element, DATA_KEY);
      this._element = null;
    };

    // static

    Button._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);

        if (!data) {
          data = new Button(this);
          $(this).data(DATA_KEY, data);
        }

        if (config === 'toggle') {
          data[config]();
        }
      });
    };

    _createClass(Button, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }]);

    return Button;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE_CARROT, function (event) {
    event.preventDefault();

    var button = event.target;

    if (!$(button).hasClass(ClassName.BUTTON)) {
      button = $(button).closest(Selector.BUTTON);
    }

    Button._jQueryInterface.call($(button), 'toggle');
  }).on(Event.FOCUS_BLUR_DATA_API, Selector.DATA_TOGGLE_CARROT, function (event) {
    var button = $(event.target).closest(Selector.BUTTON)[0];
    $(button).toggleClass(ClassName.FOCUS, /^focus(in)?$/.test(event.type));
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Button._jQueryInterface;
  $.fn[NAME].Constructor = Button;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Button._jQueryInterface;
  };

  return Button;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): carousel.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Carousel = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'carousel';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.carousel';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var TRANSITION_DURATION = 600;
  var ARROW_LEFT_KEYCODE = 37; // KeyboardEvent.which value for left arrow key
  var ARROW_RIGHT_KEYCODE = 39; // KeyboardEvent.which value for right arrow key

  var Default = {
    interval: 5000,
    keyboard: true,
    slide: false,
    pause: 'hover',
    wrap: true
  };

  var DefaultType = {
    interval: '(number|boolean)',
    keyboard: 'boolean',
    slide: '(boolean|string)',
    pause: '(string|boolean)',
    wrap: 'boolean'
  };

  var Direction = {
    NEXT: 'next',
    PREV: 'prev',
    LEFT: 'left',
    RIGHT: 'right'
  };

  var Event = {
    SLIDE: 'slide' + EVENT_KEY,
    SLID: 'slid' + EVENT_KEY,
    KEYDOWN: 'keydown' + EVENT_KEY,
    MOUSEENTER: 'mouseenter' + EVENT_KEY,
    MOUSELEAVE: 'mouseleave' + EVENT_KEY,
    LOAD_DATA_API: 'load' + EVENT_KEY + DATA_API_KEY,
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    CAROUSEL: 'carousel',
    ACTIVE: 'active',
    SLIDE: 'slide',
    RIGHT: 'carousel-item-right',
    LEFT: 'carousel-item-left',
    NEXT: 'carousel-item-next',
    PREV: 'carousel-item-prev',
    ITEM: 'carousel-item'
  };

  var Selector = {
    ACTIVE: '.active',
    ACTIVE_ITEM: '.active.carousel-item',
    ITEM: '.carousel-item',
    NEXT_PREV: '.carousel-item-next, .carousel-item-prev',
    INDICATORS: '.carousel-indicators',
    DATA_SLIDE: '[data-slide], [data-slide-to]',
    DATA_RIDE: '[data-ride="carousel"]'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Carousel = function () {
    function Carousel(element, config) {
      _classCallCheck(this, Carousel);

      this._items = null;
      this._interval = null;
      this._activeElement = null;

      this._isPaused = false;
      this._isSliding = false;

      this._config = this._getConfig(config);
      this._element = $(element)[0];
      this._indicatorsElement = $(this._element).find(Selector.INDICATORS)[0];

      this._addEventListeners();
    }

    // getters

    // public

    Carousel.prototype.next = function next() {
      if (this._isSliding) {
        throw new Error('Carousel is sliding');
      }
      this._slide(Direction.NEXT);
    };

    Carousel.prototype.nextWhenVisible = function nextWhenVisible() {
      // Don't call next when the page isn't visible
      if (!document.hidden) {
        this.next();
      }
    };

    Carousel.prototype.prev = function prev() {
      if (this._isSliding) {
        throw new Error('Carousel is sliding');
      }
      this._slide(Direction.PREVIOUS);
    };

    Carousel.prototype.pause = function pause(event) {
      if (!event) {
        this._isPaused = true;
      }

      if ($(this._element).find(Selector.NEXT_PREV)[0] && Util.supportsTransitionEnd()) {
        Util.triggerTransitionEnd(this._element);
        this.cycle(true);
      }

      clearInterval(this._interval);
      this._interval = null;
    };

    Carousel.prototype.cycle = function cycle(event) {
      if (!event) {
        this._isPaused = false;
      }

      if (this._interval) {
        clearInterval(this._interval);
        this._interval = null;
      }

      if (this._config.interval && !this._isPaused) {
        this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval);
      }
    };

    Carousel.prototype.to = function to(index) {
      var _this3 = this;

      this._activeElement = $(this._element).find(Selector.ACTIVE_ITEM)[0];

      var activeIndex = this._getItemIndex(this._activeElement);

      if (index > this._items.length - 1 || index < 0) {
        return;
      }

      if (this._isSliding) {
        $(this._element).one(Event.SLID, function () {
          return _this3.to(index);
        });
        return;
      }

      if (activeIndex === index) {
        this.pause();
        this.cycle();
        return;
      }

      var direction = index > activeIndex ? Direction.NEXT : Direction.PREVIOUS;

      this._slide(direction, this._items[index]);
    };

    Carousel.prototype.dispose = function dispose() {
      $(this._element).off(EVENT_KEY);
      $.removeData(this._element, DATA_KEY);

      this._items = null;
      this._config = null;
      this._element = null;
      this._interval = null;
      this._isPaused = null;
      this._isSliding = null;
      this._activeElement = null;
      this._indicatorsElement = null;
    };

    // private

    Carousel.prototype._getConfig = function _getConfig(config) {
      config = $.extend({}, Default, config);
      Util.typeCheckConfig(NAME, config, DefaultType);
      return config;
    };

    Carousel.prototype._addEventListeners = function _addEventListeners() {
      var _this4 = this;

      if (this._config.keyboard) {
        $(this._element).on(Event.KEYDOWN, function (event) {
          return _this4._keydown(event);
        });
      }

      if (this._config.pause === 'hover' && !('ontouchstart' in document.documentElement)) {
        $(this._element).on(Event.MOUSEENTER, function (event) {
          return _this4.pause(event);
        }).on(Event.MOUSELEAVE, function (event) {
          return _this4.cycle(event);
        });
      }
    };

    Carousel.prototype._keydown = function _keydown(event) {
      if (/input|textarea/i.test(event.target.tagName)) {
        return;
      }

      switch (event.which) {
        case ARROW_LEFT_KEYCODE:
          event.preventDefault();
          this.prev();
          break;
        case ARROW_RIGHT_KEYCODE:
          event.preventDefault();
          this.next();
          break;
        default:
          return;
      }
    };

    Carousel.prototype._getItemIndex = function _getItemIndex(element) {
      this._items = $.makeArray($(element).parent().find(Selector.ITEM));
      return this._items.indexOf(element);
    };

    Carousel.prototype._getItemByDirection = function _getItemByDirection(direction, activeElement) {
      var isNextDirection = direction === Direction.NEXT;
      var isPrevDirection = direction === Direction.PREVIOUS;
      var activeIndex = this._getItemIndex(activeElement);
      var lastItemIndex = this._items.length - 1;
      var isGoingToWrap = isPrevDirection && activeIndex === 0 || isNextDirection && activeIndex === lastItemIndex;

      if (isGoingToWrap && !this._config.wrap) {
        return activeElement;
      }

      var delta = direction === Direction.PREVIOUS ? -1 : 1;
      var itemIndex = (activeIndex + delta) % this._items.length;

      return itemIndex === -1 ? this._items[this._items.length - 1] : this._items[itemIndex];
    };

    Carousel.prototype._triggerSlideEvent = function _triggerSlideEvent(relatedTarget, eventDirectionName) {
      var slideEvent = $.Event(Event.SLIDE, {
        relatedTarget: relatedTarget,
        direction: eventDirectionName
      });

      $(this._element).trigger(slideEvent);

      return slideEvent;
    };

    Carousel.prototype._setActiveIndicatorElement = function _setActiveIndicatorElement(element) {
      if (this._indicatorsElement) {
        $(this._indicatorsElement).find(Selector.ACTIVE).removeClass(ClassName.ACTIVE);

        var nextIndicator = this._indicatorsElement.children[this._getItemIndex(element)];

        if (nextIndicator) {
          $(nextIndicator).addClass(ClassName.ACTIVE);
        }
      }
    };

    Carousel.prototype._slide = function _slide(direction, element) {
      var _this5 = this;

      var activeElement = $(this._element).find(Selector.ACTIVE_ITEM)[0];
      var nextElement = element || activeElement && this._getItemByDirection(direction, activeElement);

      var isCycling = Boolean(this._interval);

      var directionalClassName = void 0;
      var orderClassName = void 0;
      var eventDirectionName = void 0;

      if (direction === Direction.NEXT) {
        directionalClassName = ClassName.LEFT;
        orderClassName = ClassName.NEXT;
        eventDirectionName = Direction.LEFT;
      } else {
        directionalClassName = ClassName.RIGHT;
        orderClassName = ClassName.PREV;
        eventDirectionName = Direction.RIGHT;
      }

      if (nextElement && $(nextElement).hasClass(ClassName.ACTIVE)) {
        this._isSliding = false;
        return;
      }

      var slideEvent = this._triggerSlideEvent(nextElement, eventDirectionName);
      if (slideEvent.isDefaultPrevented()) {
        return;
      }

      if (!activeElement || !nextElement) {
        // some weirdness is happening, so we bail
        return;
      }

      this._isSliding = true;

      if (isCycling) {
        this.pause();
      }

      this._setActiveIndicatorElement(nextElement);

      var slidEvent = $.Event(Event.SLID, {
        relatedTarget: nextElement,
        direction: eventDirectionName
      });

      if (Util.supportsTransitionEnd() && $(this._element).hasClass(ClassName.SLIDE)) {

        $(nextElement).addClass(orderClassName);

        Util.reflow(nextElement);

        $(activeElement).addClass(directionalClassName);
        $(nextElement).addClass(directionalClassName);

        $(activeElement).one(Util.TRANSITION_END, function () {
          $(nextElement).removeClass(directionalClassName + ' ' + orderClassName).addClass(ClassName.ACTIVE);

          $(activeElement).removeClass(ClassName.ACTIVE + ' ' + orderClassName + ' ' + directionalClassName);

          _this5._isSliding = false;

          setTimeout(function () {
            return $(_this5._element).trigger(slidEvent);
          }, 0);
        }).emulateTransitionEnd(TRANSITION_DURATION);
      } else {
        $(activeElement).removeClass(ClassName.ACTIVE);
        $(nextElement).addClass(ClassName.ACTIVE);

        this._isSliding = false;
        $(this._element).trigger(slidEvent);
      }

      if (isCycling) {
        this.cycle();
      }
    };

    // static

    Carousel._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);
        var _config = $.extend({}, Default, $(this).data());

        if ((typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object') {
          $.extend(_config, config);
        }

        var action = typeof config === 'string' ? config : _config.slide;

        if (!data) {
          data = new Carousel(this, _config);
          $(this).data(DATA_KEY, data);
        }

        if (typeof config === 'number') {
          data.to(config);
        } else if (typeof action === 'string') {
          if (data[action] === undefined) {
            throw new Error('No method named "' + action + '"');
          }
          data[action]();
        } else if (_config.interval) {
          data.pause();
          data.cycle();
        }
      });
    };

    Carousel._dataApiClickHandler = function _dataApiClickHandler(event) {
      var selector = Util.getSelectorFromElement(this);

      if (!selector) {
        return;
      }

      var target = $(selector)[0];

      if (!target || !$(target).hasClass(ClassName.CAROUSEL)) {
        return;
      }

      var config = $.extend({}, $(target).data(), $(this).data());
      var slideIndex = this.getAttribute('data-slide-to');

      if (slideIndex) {
        config.interval = false;
      }

      Carousel._jQueryInterface.call($(target), config);

      if (slideIndex) {
        $(target).data(DATA_KEY).to(slideIndex);
      }

      event.preventDefault();
    };

    _createClass(Carousel, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }, {
      key: 'Default',
      get: function get() {
        return Default;
      }
    }]);

    return Carousel;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DATA_SLIDE, Carousel._dataApiClickHandler);

  $(window).on(Event.LOAD_DATA_API, function () {
    $(Selector.DATA_RIDE).each(function () {
      var $carousel = $(this);
      Carousel._jQueryInterface.call($carousel, $carousel.data());
    });
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Carousel._jQueryInterface;
  $.fn[NAME].Constructor = Carousel;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Carousel._jQueryInterface;
  };

  return Carousel;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): collapse.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Collapse = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'collapse';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.collapse';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var TRANSITION_DURATION = 600;

  var Default = {
    toggle: true,
    parent: ''
  };

  var DefaultType = {
    toggle: 'boolean',
    parent: 'string'
  };

  var Event = {
    SHOW: 'show' + EVENT_KEY,
    SHOWN: 'shown' + EVENT_KEY,
    HIDE: 'hide' + EVENT_KEY,
    HIDDEN: 'hidden' + EVENT_KEY,
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    SHOW: 'show',
    COLLAPSE: 'collapse',
    COLLAPSING: 'collapsing',
    COLLAPSED: 'collapsed'
  };

  var Dimension = {
    WIDTH: 'width',
    HEIGHT: 'height'
  };

  var Selector = {
    ACTIVES: '.card > .show, .card > .collapsing',
    DATA_TOGGLE: '[data-toggle="collapse"]'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Collapse = function () {
    function Collapse(element, config) {
      _classCallCheck(this, Collapse);

      this._isTransitioning = false;
      this._element = element;
      this._config = this._getConfig(config);
      this._triggerArray = $.makeArray($('[data-toggle="collapse"][href="#' + element.id + '"],' + ('[data-toggle="collapse"][data-target="#' + element.id + '"]')));

      this._parent = this._config.parent ? this._getParent() : null;

      if (!this._config.parent) {
        this._addAriaAndCollapsedClass(this._element, this._triggerArray);
      }

      if (this._config.toggle) {
        this.toggle();
      }
    }

    // getters

    // public

    Collapse.prototype.toggle = function toggle() {
      if ($(this._element).hasClass(ClassName.SHOW)) {
        this.hide();
      } else {
        this.show();
      }
    };

    Collapse.prototype.show = function show() {
      var _this6 = this;

      if (this._isTransitioning) {
        throw new Error('Collapse is transitioning');
      }

      if ($(this._element).hasClass(ClassName.SHOW)) {
        return;
      }

      var actives = void 0;
      var activesData = void 0;

      if (this._parent) {
        actives = $.makeArray($(this._parent).find(Selector.ACTIVES));
        if (!actives.length) {
          actives = null;
        }
      }

      if (actives) {
        activesData = $(actives).data(DATA_KEY);
        if (activesData && activesData._isTransitioning) {
          return;
        }
      }

      var startEvent = $.Event(Event.SHOW);
      $(this._element).trigger(startEvent);
      if (startEvent.isDefaultPrevented()) {
        return;
      }

      if (actives) {
        Collapse._jQueryInterface.call($(actives), 'hide');
        if (!activesData) {
          $(actives).data(DATA_KEY, null);
        }
      }

      var dimension = this._getDimension();

      $(this._element).removeClass(ClassName.COLLAPSE).addClass(ClassName.COLLAPSING);

      this._element.style[dimension] = 0;
      this._element.setAttribute('aria-expanded', true);

      if (this._triggerArray.length) {
        $(this._triggerArray).removeClass(ClassName.COLLAPSED).attr('aria-expanded', true);
      }

      this.setTransitioning(true);

      var complete = function complete() {
        $(_this6._element).removeClass(ClassName.COLLAPSING).addClass(ClassName.COLLAPSE).addClass(ClassName.SHOW);

        _this6._element.style[dimension] = '';

        _this6.setTransitioning(false);

        $(_this6._element).trigger(Event.SHOWN);
      };

      if (!Util.supportsTransitionEnd()) {
        complete();
        return;
      }

      var capitalizedDimension = dimension[0].toUpperCase() + dimension.slice(1);
      var scrollSize = 'scroll' + capitalizedDimension;

      $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(TRANSITION_DURATION);

      this._element.style[dimension] = this._element[scrollSize] + 'px';
    };

    Collapse.prototype.hide = function hide() {
      var _this7 = this;

      if (this._isTransitioning) {
        throw new Error('Collapse is transitioning');
      }

      if (!$(this._element).hasClass(ClassName.SHOW)) {
        return;
      }

      var startEvent = $.Event(Event.HIDE);
      $(this._element).trigger(startEvent);
      if (startEvent.isDefaultPrevented()) {
        return;
      }

      var dimension = this._getDimension();
      var offsetDimension = dimension === Dimension.WIDTH ? 'offsetWidth' : 'offsetHeight';

      this._element.style[dimension] = this._element[offsetDimension] + 'px';

      Util.reflow(this._element);

      $(this._element).addClass(ClassName.COLLAPSING).removeClass(ClassName.COLLAPSE).removeClass(ClassName.SHOW);

      this._element.setAttribute('aria-expanded', false);

      if (this._triggerArray.length) {
        $(this._triggerArray).addClass(ClassName.COLLAPSED).attr('aria-expanded', false);
      }

      this.setTransitioning(true);

      var complete = function complete() {
        _this7.setTransitioning(false);
        $(_this7._element).removeClass(ClassName.COLLAPSING).addClass(ClassName.COLLAPSE).trigger(Event.HIDDEN);
      };

      this._element.style[dimension] = '';

      if (!Util.supportsTransitionEnd()) {
        complete();
        return;
      }

      $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(TRANSITION_DURATION);
    };

    Collapse.prototype.setTransitioning = function setTransitioning(isTransitioning) {
      this._isTransitioning = isTransitioning;
    };

    Collapse.prototype.dispose = function dispose() {
      $.removeData(this._element, DATA_KEY);

      this._config = null;
      this._parent = null;
      this._element = null;
      this._triggerArray = null;
      this._isTransitioning = null;
    };

    // private

    Collapse.prototype._getConfig = function _getConfig(config) {
      config = $.extend({}, Default, config);
      config.toggle = Boolean(config.toggle); // coerce string values
      Util.typeCheckConfig(NAME, config, DefaultType);
      return config;
    };

    Collapse.prototype._getDimension = function _getDimension() {
      var hasWidth = $(this._element).hasClass(Dimension.WIDTH);
      return hasWidth ? Dimension.WIDTH : Dimension.HEIGHT;
    };

    Collapse.prototype._getParent = function _getParent() {
      var _this8 = this;

      var parent = $(this._config.parent)[0];
      var selector = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]';

      $(parent).find(selector).each(function (i, element) {
        _this8._addAriaAndCollapsedClass(Collapse._getTargetFromElement(element), [element]);
      });

      return parent;
    };

    Collapse.prototype._addAriaAndCollapsedClass = function _addAriaAndCollapsedClass(element, triggerArray) {
      if (element) {
        var isOpen = $(element).hasClass(ClassName.SHOW);
        element.setAttribute('aria-expanded', isOpen);

        if (triggerArray.length) {
          $(triggerArray).toggleClass(ClassName.COLLAPSED, !isOpen).attr('aria-expanded', isOpen);
        }
      }
    };

    // static

    Collapse._getTargetFromElement = function _getTargetFromElement(element) {
      var selector = Util.getSelectorFromElement(element);
      return selector ? $(selector)[0] : null;
    };

    Collapse._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var $this = $(this);
        var data = $this.data(DATA_KEY);
        var _config = $.extend({}, Default, $this.data(), (typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object' && config);

        if (!data && _config.toggle && /show|hide/.test(config)) {
          _config.toggle = false;
        }

        if (!data) {
          data = new Collapse(this, _config);
          $this.data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config]();
        }
      });
    };

    _createClass(Collapse, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }, {
      key: 'Default',
      get: function get() {
        return Default;
      }
    }]);

    return Collapse;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, function (event) {
    event.preventDefault();

    var target = Collapse._getTargetFromElement(this);
    var data = $(target).data(DATA_KEY);
    var config = data ? 'toggle' : $(this).data();

    Collapse._jQueryInterface.call($(target), config);
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Collapse._jQueryInterface;
  $.fn[NAME].Constructor = Collapse;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Collapse._jQueryInterface;
  };

  return Collapse;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): dropdown.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Dropdown = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'dropdown';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.dropdown';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var ESCAPE_KEYCODE = 27; // KeyboardEvent.which value for Escape (Esc) key
  var ARROW_UP_KEYCODE = 38; // KeyboardEvent.which value for up arrow key
  var ARROW_DOWN_KEYCODE = 40; // KeyboardEvent.which value for down arrow key
  var RIGHT_MOUSE_BUTTON_WHICH = 3; // MouseEvent.which value for the right button (assuming a right-handed mouse)

  var Event = {
    HIDE: 'hide' + EVENT_KEY,
    HIDDEN: 'hidden' + EVENT_KEY,
    SHOW: 'show' + EVENT_KEY,
    SHOWN: 'shown' + EVENT_KEY,
    CLICK: 'click' + EVENT_KEY,
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY,
    FOCUSIN_DATA_API: 'focusin' + EVENT_KEY + DATA_API_KEY,
    KEYDOWN_DATA_API: 'keydown' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    BACKDROP: 'dropdown-backdrop',
    DISABLED: 'disabled',
    SHOW: 'show'
  };

  var Selector = {
    BACKDROP: '.dropdown-backdrop',
    DATA_TOGGLE: '[data-toggle="dropdown"]',
    FORM_CHILD: '.dropdown form',
    ROLE_MENU: '[role="menu"]',
    ROLE_LISTBOX: '[role="listbox"]',
    NAVBAR_NAV: '.navbar-nav',
    VISIBLE_ITEMS: '[role="menu"] li:not(.disabled) a, ' + '[role="listbox"] li:not(.disabled) a'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Dropdown = function () {
    function Dropdown(element) {
      _classCallCheck(this, Dropdown);

      this._element = element;

      this._addEventListeners();
    }

    // getters

    // public

    Dropdown.prototype.toggle = function toggle() {
      if (this.disabled || $(this).hasClass(ClassName.DISABLED)) {
        return false;
      }

      var parent = Dropdown._getParentFromElement(this);
      var isActive = $(parent).hasClass(ClassName.SHOW);

      Dropdown._clearMenus();

      if (isActive) {
        return false;
      }

      if ('ontouchstart' in document.documentElement && !$(parent).closest(Selector.NAVBAR_NAV).length) {

        // if mobile we use a backdrop because click events don't delegate
        var dropdown = document.createElement('div');
        dropdown.className = ClassName.BACKDROP;
        $(dropdown).insertBefore(this);
        $(dropdown).on('click', Dropdown._clearMenus);
      }

      var relatedTarget = {
        relatedTarget: this
      };
      var showEvent = $.Event(Event.SHOW, relatedTarget);

      $(parent).trigger(showEvent);

      if (showEvent.isDefaultPrevented()) {
        return false;
      }

      this.focus();
      this.setAttribute('aria-expanded', true);

      $(parent).toggleClass(ClassName.SHOW);
      $(parent).trigger($.Event(Event.SHOWN, relatedTarget));

      return false;
    };

    Dropdown.prototype.dispose = function dispose() {
      $.removeData(this._element, DATA_KEY);
      $(this._element).off(EVENT_KEY);
      this._element = null;
    };

    // private

    Dropdown.prototype._addEventListeners = function _addEventListeners() {
      $(this._element).on(Event.CLICK, this.toggle);
    };

    // static

    Dropdown._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);

        if (!data) {
          data = new Dropdown(this);
          $(this).data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config].call(this);
        }
      });
    };

    Dropdown._clearMenus = function _clearMenus(event) {
      if (event && event.which === RIGHT_MOUSE_BUTTON_WHICH) {
        return;
      }

      var backdrop = $(Selector.BACKDROP)[0];
      if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
      }

      var toggles = $.makeArray($(Selector.DATA_TOGGLE));

      for (var i = 0; i < toggles.length; i++) {
        var parent = Dropdown._getParentFromElement(toggles[i]);
        var relatedTarget = {
          relatedTarget: toggles[i]
        };

        if (!$(parent).hasClass(ClassName.SHOW)) {
          continue;
        }

        if (event && (event.type === 'click' && /input|textarea/i.test(event.target.tagName) || event.type === 'focusin') && $.contains(parent, event.target)) {
          continue;
        }

        var hideEvent = $.Event(Event.HIDE, relatedTarget);
        $(parent).trigger(hideEvent);
        if (hideEvent.isDefaultPrevented()) {
          continue;
        }

        toggles[i].setAttribute('aria-expanded', 'false');

        $(parent).removeClass(ClassName.SHOW).trigger($.Event(Event.HIDDEN, relatedTarget));
      }
    };

    Dropdown._getParentFromElement = function _getParentFromElement(element) {
      var parent = void 0;
      var selector = Util.getSelectorFromElement(element);

      if (selector) {
        parent = $(selector)[0];
      }

      return parent || element.parentNode;
    };

    Dropdown._dataApiKeydownHandler = function _dataApiKeydownHandler(event) {
      if (!/(38|40|27|32)/.test(event.which) || /input|textarea/i.test(event.target.tagName)) {
        return;
      }

      event.preventDefault();
      event.stopPropagation();

      if (this.disabled || $(this).hasClass(ClassName.DISABLED)) {
        return;
      }

      var parent = Dropdown._getParentFromElement(this);
      var isActive = $(parent).hasClass(ClassName.SHOW);

      if (!isActive && event.which !== ESCAPE_KEYCODE || isActive && event.which === ESCAPE_KEYCODE) {

        if (event.which === ESCAPE_KEYCODE) {
          var toggle = $(parent).find(Selector.DATA_TOGGLE)[0];
          $(toggle).trigger('focus');
        }

        $(this).trigger('click');
        return;
      }

      var items = $(parent).find(Selector.VISIBLE_ITEMS).get();

      if (!items.length) {
        return;
      }

      var index = items.indexOf(event.target);

      if (event.which === ARROW_UP_KEYCODE && index > 0) {
        // up
        index--;
      }

      if (event.which === ARROW_DOWN_KEYCODE && index < items.length - 1) {
        // down
        index++;
      }

      if (index < 0) {
        index = 0;
      }

      items[index].focus();
    };

    _createClass(Dropdown, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }]);

    return Dropdown;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.KEYDOWN_DATA_API, Selector.DATA_TOGGLE, Dropdown._dataApiKeydownHandler).on(Event.KEYDOWN_DATA_API, Selector.ROLE_MENU, Dropdown._dataApiKeydownHandler).on(Event.KEYDOWN_DATA_API, Selector.ROLE_LISTBOX, Dropdown._dataApiKeydownHandler).on(Event.CLICK_DATA_API + ' ' + Event.FOCUSIN_DATA_API, Dropdown._clearMenus).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, Dropdown.prototype.toggle).on(Event.CLICK_DATA_API, Selector.FORM_CHILD, function (e) {
    e.stopPropagation();
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Dropdown._jQueryInterface;
  $.fn[NAME].Constructor = Dropdown;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Dropdown._jQueryInterface;
  };

  return Dropdown;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): modal.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Modal = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'modal';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.modal';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var TRANSITION_DURATION = 300;
  var BACKDROP_TRANSITION_DURATION = 150;
  var ESCAPE_KEYCODE = 27; // KeyboardEvent.which value for Escape (Esc) key

  var Default = {
    backdrop: true,
    keyboard: true,
    focus: true,
    show: true
  };

  var DefaultType = {
    backdrop: '(boolean|string)',
    keyboard: 'boolean',
    focus: 'boolean',
    show: 'boolean'
  };

  var Event = {
    HIDE: 'hide' + EVENT_KEY,
    HIDDEN: 'hidden' + EVENT_KEY,
    SHOW: 'show' + EVENT_KEY,
    SHOWN: 'shown' + EVENT_KEY,
    FOCUSIN: 'focusin' + EVENT_KEY,
    RESIZE: 'resize' + EVENT_KEY,
    CLICK_DISMISS: 'click.dismiss' + EVENT_KEY,
    KEYDOWN_DISMISS: 'keydown.dismiss' + EVENT_KEY,
    MOUSEUP_DISMISS: 'mouseup.dismiss' + EVENT_KEY,
    MOUSEDOWN_DISMISS: 'mousedown.dismiss' + EVENT_KEY,
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    SCROLLBAR_MEASURER: 'modal-scrollbar-measure',
    BACKDROP: 'modal-backdrop',
    OPEN: 'modal-open',
    FADE: 'fade',
    SHOW: 'show'
  };

  var Selector = {
    DIALOG: '.modal-dialog',
    DATA_TOGGLE: '[data-toggle="modal"]',
    DATA_DISMISS: '[data-dismiss="modal"]',
    FIXED_CONTENT: '.fixed-top, .fixed-bottom, .is-fixed, .sticky-top'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Modal = function () {
    function Modal(element, config) {
      _classCallCheck(this, Modal);

      this._config = this._getConfig(config);
      this._element = element;
      this._dialog = $(element).find(Selector.DIALOG)[0];
      this._backdrop = null;
      this._isShown = false;
      this._isBodyOverflowing = false;
      this._ignoreBackdropClick = false;
      this._isTransitioning = false;
      this._originalBodyPadding = 0;
      this._scrollbarWidth = 0;
    }

    // getters

    // public

    Modal.prototype.toggle = function toggle(relatedTarget) {
      return this._isShown ? this.hide() : this.show(relatedTarget);
    };

    Modal.prototype.show = function show(relatedTarget) {
      var _this9 = this;

      if (this._isTransitioning) {
        throw new Error('Modal is transitioning');
      }

      if (Util.supportsTransitionEnd() && $(this._element).hasClass(ClassName.FADE)) {
        this._isTransitioning = true;
      }
      var showEvent = $.Event(Event.SHOW, {
        relatedTarget: relatedTarget
      });

      $(this._element).trigger(showEvent);

      if (this._isShown || showEvent.isDefaultPrevented()) {
        return;
      }

      this._isShown = true;

      this._checkScrollbar();
      this._setScrollbar();

      $(document.body).addClass(ClassName.OPEN);

      this._setEscapeEvent();
      this._setResizeEvent();

      $(this._element).on(Event.CLICK_DISMISS, Selector.DATA_DISMISS, function (event) {
        return _this9.hide(event);
      });

      $(this._dialog).on(Event.MOUSEDOWN_DISMISS, function () {
        $(_this9._element).one(Event.MOUSEUP_DISMISS, function (event) {
          if ($(event.target).is(_this9._element)) {
            _this9._ignoreBackdropClick = true;
          }
        });
      });

      this._showBackdrop(function () {
        return _this9._showElement(relatedTarget);
      });
    };

    Modal.prototype.hide = function hide(event) {
      var _this10 = this;

      if (event) {
        event.preventDefault();
      }

      if (this._isTransitioning) {
        throw new Error('Modal is transitioning');
      }

      var transition = Util.supportsTransitionEnd() && $(this._element).hasClass(ClassName.FADE);
      if (transition) {
        this._isTransitioning = true;
      }

      var hideEvent = $.Event(Event.HIDE);
      $(this._element).trigger(hideEvent);

      if (!this._isShown || hideEvent.isDefaultPrevented()) {
        return;
      }

      this._isShown = false;

      this._setEscapeEvent();
      this._setResizeEvent();

      $(document).off(Event.FOCUSIN);

      $(this._element).removeClass(ClassName.SHOW);

      $(this._element).off(Event.CLICK_DISMISS);
      $(this._dialog).off(Event.MOUSEDOWN_DISMISS);

      if (transition) {
        $(this._element).one(Util.TRANSITION_END, function (event) {
          return _this10._hideModal(event);
        }).emulateTransitionEnd(TRANSITION_DURATION);
      } else {
        this._hideModal();
      }
    };

    Modal.prototype.dispose = function dispose() {
      $.removeData(this._element, DATA_KEY);

      $(window, document, this._element, this._backdrop).off(EVENT_KEY);

      this._config = null;
      this._element = null;
      this._dialog = null;
      this._backdrop = null;
      this._isShown = null;
      this._isBodyOverflowing = null;
      this._ignoreBackdropClick = null;
      this._originalBodyPadding = null;
      this._scrollbarWidth = null;
    };

    // private

    Modal.prototype._getConfig = function _getConfig(config) {
      config = $.extend({}, Default, config);
      Util.typeCheckConfig(NAME, config, DefaultType);
      return config;
    };

    Modal.prototype._showElement = function _showElement(relatedTarget) {
      var _this11 = this;

      var transition = Util.supportsTransitionEnd() && $(this._element).hasClass(ClassName.FADE);

      if (!this._element.parentNode || this._element.parentNode.nodeType !== Node.ELEMENT_NODE) {
        // don't move modals dom position
        document.body.appendChild(this._element);
      }

      this._element.style.display = 'block';
      this._element.removeAttribute('aria-hidden');
      this._element.scrollTop = 0;

      if (transition) {
        Util.reflow(this._element);
      }

      $(this._element).addClass(ClassName.SHOW);

      if (this._config.focus) {
        this._enforceFocus();
      }

      var shownEvent = $.Event(Event.SHOWN, {
        relatedTarget: relatedTarget
      });

      var transitionComplete = function transitionComplete() {
        if (_this11._config.focus) {
          _this11._element.focus();
        }
        _this11._isTransitioning = false;
        $(_this11._element).trigger(shownEvent);
      };

      if (transition) {
        $(this._dialog).one(Util.TRANSITION_END, transitionComplete).emulateTransitionEnd(TRANSITION_DURATION);
      } else {
        transitionComplete();
      }
    };

    Modal.prototype._enforceFocus = function _enforceFocus() {
      var _this12 = this;

      $(document).off(Event.FOCUSIN) // guard against infinite focus loop
      .on(Event.FOCUSIN, function (event) {
        if (document !== event.target && _this12._element !== event.target && !$(_this12._element).has(event.target).length) {
          _this12._element.focus();
        }
      });
    };

    Modal.prototype._setEscapeEvent = function _setEscapeEvent() {
      var _this13 = this;

      if (this._isShown && this._config.keyboard) {
        $(this._element).on(Event.KEYDOWN_DISMISS, function (event) {
          if (event.which === ESCAPE_KEYCODE) {
            _this13.hide();
          }
        });
      } else if (!this._isShown) {
        $(this._element).off(Event.KEYDOWN_DISMISS);
      }
    };

    Modal.prototype._setResizeEvent = function _setResizeEvent() {
      var _this14 = this;

      if (this._isShown) {
        $(window).on(Event.RESIZE, function (event) {
          return _this14._handleUpdate(event);
        });
      } else {
        $(window).off(Event.RESIZE);
      }
    };

    Modal.prototype._hideModal = function _hideModal() {
      var _this15 = this;

      this._element.style.display = 'none';
      this._element.setAttribute('aria-hidden', 'true');
      this._isTransitioning = false;
      this._showBackdrop(function () {
        $(document.body).removeClass(ClassName.OPEN);
        _this15._resetAdjustments();
        _this15._resetScrollbar();
        $(_this15._element).trigger(Event.HIDDEN);
      });
    };

    Modal.prototype._removeBackdrop = function _removeBackdrop() {
      if (this._backdrop) {
        $(this._backdrop).remove();
        this._backdrop = null;
      }
    };

    Modal.prototype._showBackdrop = function _showBackdrop(callback) {
      var _this16 = this;

      var animate = $(this._element).hasClass(ClassName.FADE) ? ClassName.FADE : '';

      if (this._isShown && this._config.backdrop) {
        var doAnimate = Util.supportsTransitionEnd() && animate;

        this._backdrop = document.createElement('div');
        this._backdrop.className = ClassName.BACKDROP;

        if (animate) {
          $(this._backdrop).addClass(animate);
        }

        $(this._backdrop).appendTo(document.body);

        $(this._element).on(Event.CLICK_DISMISS, function (event) {
          if (_this16._ignoreBackdropClick) {
            _this16._ignoreBackdropClick = false;
            return;
          }
          if (event.target !== event.currentTarget) {
            return;
          }
          if (_this16._config.backdrop === 'static') {
            _this16._element.focus();
          } else {
            _this16.hide();
          }
        });

        if (doAnimate) {
          Util.reflow(this._backdrop);
        }

        $(this._backdrop).addClass(ClassName.SHOW);

        if (!callback) {
          return;
        }

        if (!doAnimate) {
          callback();
          return;
        }

        $(this._backdrop).one(Util.TRANSITION_END, callback).emulateTransitionEnd(BACKDROP_TRANSITION_DURATION);
      } else if (!this._isShown && this._backdrop) {
        $(this._backdrop).removeClass(ClassName.SHOW);

        var callbackRemove = function callbackRemove() {
          _this16._removeBackdrop();
          if (callback) {
            callback();
          }
        };

        if (Util.supportsTransitionEnd() && $(this._element).hasClass(ClassName.FADE)) {
          $(this._backdrop).one(Util.TRANSITION_END, callbackRemove).emulateTransitionEnd(BACKDROP_TRANSITION_DURATION);
        } else {
          callbackRemove();
        }
      } else if (callback) {
        callback();
      }
    };

    // ----------------------------------------------------------------------
    // the following methods are used to handle overflowing modals
    // todo (fat): these should probably be refactored out of modal.js
    // ----------------------------------------------------------------------

    Modal.prototype._handleUpdate = function _handleUpdate() {
      this._adjustDialog();
    };

    Modal.prototype._adjustDialog = function _adjustDialog() {
      var isModalOverflowing = this._element.scrollHeight > document.documentElement.clientHeight;

      if (!this._isBodyOverflowing && isModalOverflowing) {
        this._element.style.paddingLeft = this._scrollbarWidth + 'px';
      }

      if (this._isBodyOverflowing && !isModalOverflowing) {
        this._element.style.paddingRight = this._scrollbarWidth + 'px';
      }
    };

    Modal.prototype._resetAdjustments = function _resetAdjustments() {
      this._element.style.paddingLeft = '';
      this._element.style.paddingRight = '';
    };

    Modal.prototype._checkScrollbar = function _checkScrollbar() {
      this._isBodyOverflowing = document.body.clientWidth < window.innerWidth;
      this._scrollbarWidth = this._getScrollbarWidth();
    };

    Modal.prototype._setScrollbar = function _setScrollbar() {
      var bodyPadding = parseInt($(Selector.FIXED_CONTENT).css('padding-right') || 0, 10);

      this._originalBodyPadding = document.body.style.paddingRight || '';

      if (this._isBodyOverflowing) {
        document.body.style.paddingRight = bodyPadding + this._scrollbarWidth + 'px';
      }
    };

    Modal.prototype._resetScrollbar = function _resetScrollbar() {
      document.body.style.paddingRight = this._originalBodyPadding;
    };

    Modal.prototype._getScrollbarWidth = function _getScrollbarWidth() {
      // thx d.walsh
      var scrollDiv = document.createElement('div');
      scrollDiv.className = ClassName.SCROLLBAR_MEASURER;
      document.body.appendChild(scrollDiv);
      var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
      document.body.removeChild(scrollDiv);
      return scrollbarWidth;
    };

    // static

    Modal._jQueryInterface = function _jQueryInterface(config, relatedTarget) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);
        var _config = $.extend({}, Modal.Default, $(this).data(), (typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object' && config);

        if (!data) {
          data = new Modal(this, _config);
          $(this).data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config](relatedTarget);
        } else if (_config.show) {
          data.show(relatedTarget);
        }
      });
    };

    _createClass(Modal, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }, {
      key: 'Default',
      get: function get() {
        return Default;
      }
    }]);

    return Modal;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, function (event) {
    var _this17 = this;

    var target = void 0;
    var selector = Util.getSelectorFromElement(this);

    if (selector) {
      target = $(selector)[0];
    }

    var config = $(target).data(DATA_KEY) ? 'toggle' : $.extend({}, $(target).data(), $(this).data());

    if (this.tagName === 'A' || this.tagName === 'AREA') {
      event.preventDefault();
    }

    var $target = $(target).one(Event.SHOW, function (showEvent) {
      if (showEvent.isDefaultPrevented()) {
        // only register focus restorer if modal will actually get shown
        return;
      }

      $target.one(Event.HIDDEN, function () {
        if ($(_this17).is(':visible')) {
          _this17.focus();
        }
      });
    });

    Modal._jQueryInterface.call($(target), config, this);
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Modal._jQueryInterface;
  $.fn[NAME].Constructor = Modal;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Modal._jQueryInterface;
  };

  return Modal;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): scrollspy.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var ScrollSpy = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'scrollspy';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.scrollspy';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];

  var Default = {
    offset: 10,
    method: 'auto',
    target: ''
  };

  var DefaultType = {
    offset: 'number',
    method: 'string',
    target: '(string|element)'
  };

  var Event = {
    ACTIVATE: 'activate' + EVENT_KEY,
    SCROLL: 'scroll' + EVENT_KEY,
    LOAD_DATA_API: 'load' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    DROPDOWN_ITEM: 'dropdown-item',
    DROPDOWN_MENU: 'dropdown-menu',
    NAV_LINK: 'nav-link',
    NAV: 'nav',
    ACTIVE: 'active'
  };

  var Selector = {
    DATA_SPY: '[data-spy="scroll"]',
    ACTIVE: '.active',
    LIST_ITEM: '.list-item',
    LI: 'li',
    LI_DROPDOWN: 'li.dropdown',
    NAV_LINKS: '.nav-link',
    DROPDOWN: '.dropdown',
    DROPDOWN_ITEMS: '.dropdown-item',
    DROPDOWN_TOGGLE: '.dropdown-toggle'
  };

  var OffsetMethod = {
    OFFSET: 'offset',
    POSITION: 'position'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var ScrollSpy = function () {
    function ScrollSpy(element, config) {
      var _this18 = this;

      _classCallCheck(this, ScrollSpy);

      this._element = element;
      this._scrollElement = element.tagName === 'BODY' ? window : element;
      this._config = this._getConfig(config);
      this._selector = this._config.target + ' ' + Selector.NAV_LINKS + ',' + (this._config.target + ' ' + Selector.DROPDOWN_ITEMS);
      this._offsets = [];
      this._targets = [];
      this._activeTarget = null;
      this._scrollHeight = 0;

      $(this._scrollElement).on(Event.SCROLL, function (event) {
        return _this18._process(event);
      });

      this.refresh();
      this._process();
    }

    // getters

    // public

    ScrollSpy.prototype.refresh = function refresh() {
      var _this19 = this;

      var autoMethod = this._scrollElement !== this._scrollElement.window ? OffsetMethod.POSITION : OffsetMethod.OFFSET;

      var offsetMethod = this._config.method === 'auto' ? autoMethod : this._config.method;

      var offsetBase = offsetMethod === OffsetMethod.POSITION ? this._getScrollTop() : 0;

      this._offsets = [];
      this._targets = [];

      this._scrollHeight = this._getScrollHeight();

      var targets = $.makeArray($(this._selector));

      targets.map(function (element) {
        var target = void 0;
        var targetSelector = Util.getSelectorFromElement(element);

        if (targetSelector) {
          target = $(targetSelector)[0];
        }

        if (target && (target.offsetWidth || target.offsetHeight)) {
          // todo (fat): remove sketch reliance on jQuery position/offset
          return [$(target)[offsetMethod]().top + offsetBase, targetSelector];
        }
        return null;
      }).filter(function (item) {
        return item;
      }).sort(function (a, b) {
        return a[0] - b[0];
      }).forEach(function (item) {
        _this19._offsets.push(item[0]);
        _this19._targets.push(item[1]);
      });
    };

    ScrollSpy.prototype.dispose = function dispose() {
      $.removeData(this._element, DATA_KEY);
      $(this._scrollElement).off(EVENT_KEY);

      this._element = null;
      this._scrollElement = null;
      this._config = null;
      this._selector = null;
      this._offsets = null;
      this._targets = null;
      this._activeTarget = null;
      this._scrollHeight = null;
    };

    // private

    ScrollSpy.prototype._getConfig = function _getConfig(config) {
      config = $.extend({}, Default, config);

      if (typeof config.target !== 'string') {
        var id = $(config.target).attr('id');
        if (!id) {
          id = Util.getUID(NAME);
          $(config.target).attr('id', id);
        }
        config.target = '#' + id;
      }

      Util.typeCheckConfig(NAME, config, DefaultType);

      return config;
    };

    ScrollSpy.prototype._getScrollTop = function _getScrollTop() {
      return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop;
    };

    ScrollSpy.prototype._getScrollHeight = function _getScrollHeight() {
      return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
    };

    ScrollSpy.prototype._getOffsetHeight = function _getOffsetHeight() {
      return this._scrollElement === window ? window.innerHeight : this._scrollElement.offsetHeight;
    };

    ScrollSpy.prototype._process = function _process() {
      var scrollTop = this._getScrollTop() + this._config.offset;
      var scrollHeight = this._getScrollHeight();
      var maxScroll = this._config.offset + scrollHeight - this._getOffsetHeight();

      if (this._scrollHeight !== scrollHeight) {
        this.refresh();
      }

      if (scrollTop >= maxScroll) {
        var target = this._targets[this._targets.length - 1];

        if (this._activeTarget !== target) {
          this._activate(target);
        }
        return;
      }

      if (this._activeTarget && scrollTop < this._offsets[0] && this._offsets[0] > 0) {
        this._activeTarget = null;
        this._clear();
        return;
      }

      for (var i = this._offsets.length; i--;) {
        var isActiveTarget = this._activeTarget !== this._targets[i] && scrollTop >= this._offsets[i] && (this._offsets[i + 1] === undefined || scrollTop < this._offsets[i + 1]);

        if (isActiveTarget) {
          this._activate(this._targets[i]);
        }
      }
    };

    ScrollSpy.prototype._activate = function _activate(target) {
      this._activeTarget = target;

      this._clear();

      var queries = this._selector.split(',');
      queries = queries.map(function (selector) {
        return selector + '[data-target="' + target + '"],' + (selector + '[href="' + target + '"]');
      });

      var $link = $(queries.join(','));

      if ($link.hasClass(ClassName.DROPDOWN_ITEM)) {
        $link.closest(Selector.DROPDOWN).find(Selector.DROPDOWN_TOGGLE).addClass(ClassName.ACTIVE);
        $link.addClass(ClassName.ACTIVE);
      } else {
        // todo (fat) this is kinda sus...
        // recursively add actives to tested nav-links
        $link.parents(Selector.LI).find('> ' + Selector.NAV_LINKS).addClass(ClassName.ACTIVE);
      }

      $(this._scrollElement).trigger(Event.ACTIVATE, {
        relatedTarget: target
      });
    };

    ScrollSpy.prototype._clear = function _clear() {
      $(this._selector).filter(Selector.ACTIVE).removeClass(ClassName.ACTIVE);
    };

    // static

    ScrollSpy._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);
        var _config = (typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object' && config;

        if (!data) {
          data = new ScrollSpy(this, _config);
          $(this).data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config]();
        }
      });
    };

    _createClass(ScrollSpy, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }, {
      key: 'Default',
      get: function get() {
        return Default;
      }
    }]);

    return ScrollSpy;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(window).on(Event.LOAD_DATA_API, function () {
    var scrollSpys = $.makeArray($(Selector.DATA_SPY));

    for (var i = scrollSpys.length; i--;) {
      var $spy = $(scrollSpys[i]);
      ScrollSpy._jQueryInterface.call($spy, $spy.data());
    }
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = ScrollSpy._jQueryInterface;
  $.fn[NAME].Constructor = ScrollSpy;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return ScrollSpy._jQueryInterface;
  };

  return ScrollSpy;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): tab.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Tab = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'tab';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.tab';
  var EVENT_KEY = '.' + DATA_KEY;
  var DATA_API_KEY = '.data-api';
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var TRANSITION_DURATION = 150;

  var Event = {
    HIDE: 'hide' + EVENT_KEY,
    HIDDEN: 'hidden' + EVENT_KEY,
    SHOW: 'show' + EVENT_KEY,
    SHOWN: 'shown' + EVENT_KEY,
    CLICK_DATA_API: 'click' + EVENT_KEY + DATA_API_KEY
  };

  var ClassName = {
    DROPDOWN_MENU: 'dropdown-menu',
    ACTIVE: 'active',
    DISABLED: 'disabled',
    FADE: 'fade',
    SHOW: 'show'
  };

  var Selector = {
    A: 'a',
    LI: 'li',
    DROPDOWN: '.dropdown',
    LIST: 'ul:not(.dropdown-menu), ol:not(.dropdown-menu), nav:not(.dropdown-menu)',
    FADE_CHILD: '> .nav-item .fade, > .fade',
    ACTIVE: '.active',
    ACTIVE_CHILD: '> .nav-item > .active, > .active',
    DATA_TOGGLE: '[data-toggle="tab"], [data-toggle="pill"]',
    DROPDOWN_TOGGLE: '.dropdown-toggle',
    DROPDOWN_ACTIVE_CHILD: '> .dropdown-menu .active'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Tab = function () {
    function Tab(element) {
      _classCallCheck(this, Tab);

      this._element = element;
    }

    // getters

    // public

    Tab.prototype.show = function show() {
      var _this20 = this;

      if (this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && $(this._element).hasClass(ClassName.ACTIVE) || $(this._element).hasClass(ClassName.DISABLED)) {
        return;
      }

      var target = void 0;
      var previous = void 0;
      var listElement = $(this._element).closest(Selector.LIST)[0];
      var selector = Util.getSelectorFromElement(this._element);

      if (listElement) {
        previous = $.makeArray($(listElement).find(Selector.ACTIVE));
        previous = previous[previous.length - 1];
      }

      var hideEvent = $.Event(Event.HIDE, {
        relatedTarget: this._element
      });

      var showEvent = $.Event(Event.SHOW, {
        relatedTarget: previous
      });

      if (previous) {
        $(previous).trigger(hideEvent);
      }

      $(this._element).trigger(showEvent);

      if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) {
        return;
      }

      if (selector) {
        target = $(selector)[0];
      }

      this._activate(this._element, listElement);

      var complete = function complete() {
        var hiddenEvent = $.Event(Event.HIDDEN, {
          relatedTarget: _this20._element
        });

        var shownEvent = $.Event(Event.SHOWN, {
          relatedTarget: previous
        });

        $(previous).trigger(hiddenEvent);
        $(_this20._element).trigger(shownEvent);
      };

      if (target) {
        this._activate(target, target.parentNode, complete);
      } else {
        complete();
      }
    };

    Tab.prototype.dispose = function dispose() {
      $.removeClass(this._element, DATA_KEY);
      this._element = null;
    };

    // private

    Tab.prototype._activate = function _activate(element, container, callback) {
      var _this21 = this;

      var active = $(container).find(Selector.ACTIVE_CHILD)[0];
      var isTransitioning = callback && Util.supportsTransitionEnd() && (active && $(active).hasClass(ClassName.FADE) || Boolean($(container).find(Selector.FADE_CHILD)[0]));

      var complete = function complete() {
        return _this21._transitionComplete(element, active, isTransitioning, callback);
      };

      if (active && isTransitioning) {
        $(active).one(Util.TRANSITION_END, complete).emulateTransitionEnd(TRANSITION_DURATION);
      } else {
        complete();
      }

      if (active) {
        $(active).removeClass(ClassName.SHOW);
      }
    };

    Tab.prototype._transitionComplete = function _transitionComplete(element, active, isTransitioning, callback) {
      if (active) {
        $(active).removeClass(ClassName.ACTIVE);

        var dropdownChild = $(active.parentNode).find(Selector.DROPDOWN_ACTIVE_CHILD)[0];

        if (dropdownChild) {
          $(dropdownChild).removeClass(ClassName.ACTIVE);
        }

        active.setAttribute('aria-expanded', false);
      }

      $(element).addClass(ClassName.ACTIVE);
      element.setAttribute('aria-expanded', true);

      if (isTransitioning) {
        Util.reflow(element);
        $(element).addClass(ClassName.SHOW);
      } else {
        $(element).removeClass(ClassName.FADE);
      }

      if (element.parentNode && $(element.parentNode).hasClass(ClassName.DROPDOWN_MENU)) {

        var dropdownElement = $(element).closest(Selector.DROPDOWN)[0];
        if (dropdownElement) {
          $(dropdownElement).find(Selector.DROPDOWN_TOGGLE).addClass(ClassName.ACTIVE);
        }

        element.setAttribute('aria-expanded', true);
      }

      if (callback) {
        callback();
      }
    };

    // static

    Tab._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var $this = $(this);
        var data = $this.data(DATA_KEY);

        if (!data) {
          data = new Tab(this);
          $this.data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config]();
        }
      });
    };

    _createClass(Tab, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }]);

    return Tab;
  }();

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, function (event) {
    event.preventDefault();
    Tab._jQueryInterface.call($(this), 'show');
  });

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Tab._jQueryInterface;
  $.fn[NAME].Constructor = Tab;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Tab._jQueryInterface;
  };

  return Tab;
}(jQuery);

/* global Tether */

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): tooltip.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Tooltip = function ($) {

  /**
   * Check for Tether dependency
   * Tether - http://tether.io/
   */
  if (typeof Tether === 'undefined') {
    throw new Error('Bootstrap tooltips require Tether (http://tether.io/)');
  }

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'tooltip';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.tooltip';
  var EVENT_KEY = '.' + DATA_KEY;
  var JQUERY_NO_CONFLICT = $.fn[NAME];
  var TRANSITION_DURATION = 150;
  var CLASS_PREFIX = 'bs-tether';

  var Default = {
    animation: true,
    template: '<div class="tooltip" role="tooltip">' + '<div class="tooltip-inner"></div></div>',
    trigger: 'hover focus',
    title: '',
    delay: 0,
    html: false,
    selector: false,
    placement: 'top',
    offset: '0 0',
    constraints: [],
    container: false
  };

  var DefaultType = {
    animation: 'boolean',
    template: 'string',
    title: '(string|element|function)',
    trigger: 'string',
    delay: '(number|object)',
    html: 'boolean',
    selector: '(string|boolean)',
    placement: '(string|function)',
    offset: 'string',
    constraints: 'array',
    container: '(string|element|boolean)'
  };

  var AttachmentMap = {
    TOP: 'bottom center',
    RIGHT: 'middle left',
    BOTTOM: 'top center',
    LEFT: 'middle right'
  };

  var HoverState = {
    SHOW: 'show',
    OUT: 'out'
  };

  var Event = {
    HIDE: 'hide' + EVENT_KEY,
    HIDDEN: 'hidden' + EVENT_KEY,
    SHOW: 'show' + EVENT_KEY,
    SHOWN: 'shown' + EVENT_KEY,
    INSERTED: 'inserted' + EVENT_KEY,
    CLICK: 'click' + EVENT_KEY,
    FOCUSIN: 'focusin' + EVENT_KEY,
    FOCUSOUT: 'focusout' + EVENT_KEY,
    MOUSEENTER: 'mouseenter' + EVENT_KEY,
    MOUSELEAVE: 'mouseleave' + EVENT_KEY
  };

  var ClassName = {
    FADE: 'fade',
    SHOW: 'show'
  };

  var Selector = {
    TOOLTIP: '.tooltip',
    TOOLTIP_INNER: '.tooltip-inner'
  };

  var TetherClass = {
    element: false,
    enabled: false
  };

  var Trigger = {
    HOVER: 'hover',
    FOCUS: 'focus',
    CLICK: 'click',
    MANUAL: 'manual'
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Tooltip = function () {
    function Tooltip(element, config) {
      _classCallCheck(this, Tooltip);

      // private
      this._isEnabled = true;
      this._timeout = 0;
      this._hoverState = '';
      this._activeTrigger = {};
      this._isTransitioning = false;
      this._tether = null;

      // protected
      this.element = element;
      this.config = this._getConfig(config);
      this.tip = null;

      this._setListeners();
    }

    // getters

    // public

    Tooltip.prototype.enable = function enable() {
      this._isEnabled = true;
    };

    Tooltip.prototype.disable = function disable() {
      this._isEnabled = false;
    };

    Tooltip.prototype.toggleEnabled = function toggleEnabled() {
      this._isEnabled = !this._isEnabled;
    };

    Tooltip.prototype.toggle = function toggle(event) {
      if (event) {
        var dataKey = this.constructor.DATA_KEY;
        var context = $(event.currentTarget).data(dataKey);

        if (!context) {
          context = new this.constructor(event.currentTarget, this._getDelegateConfig());
          $(event.currentTarget).data(dataKey, context);
        }

        context._activeTrigger.click = !context._activeTrigger.click;

        if (context._isWithActiveTrigger()) {
          context._enter(null, context);
        } else {
          context._leave(null, context);
        }
      } else {

        if ($(this.getTipElement()).hasClass(ClassName.SHOW)) {
          this._leave(null, this);
          return;
        }

        this._enter(null, this);
      }
    };

    Tooltip.prototype.dispose = function dispose() {
      clearTimeout(this._timeout);

      this.cleanupTether();

      $.removeData(this.element, this.constructor.DATA_KEY);

      $(this.element).off(this.constructor.EVENT_KEY);
      $(this.element).closest('.modal').off('hide.bs.modal');

      if (this.tip) {
        $(this.tip).remove();
      }

      this._isEnabled = null;
      this._timeout = null;
      this._hoverState = null;
      this._activeTrigger = null;
      this._tether = null;

      this.element = null;
      this.config = null;
      this.tip = null;
    };

    Tooltip.prototype.show = function show() {
      var _this22 = this;

      if ($(this.element).css('display') === 'none') {
        throw new Error('Please use show on visible elements');
      }

      var showEvent = $.Event(this.constructor.Event.SHOW);
      if (this.isWithContent() && this._isEnabled) {
        if (this._isTransitioning) {
          throw new Error('Tooltip is transitioning');
        }
        $(this.element).trigger(showEvent);

        var isInTheDom = $.contains(this.element.ownerDocument.documentElement, this.element);

        if (showEvent.isDefaultPrevented() || !isInTheDom) {
          return;
        }

        var tip = this.getTipElement();
        var tipId = Util.getUID(this.constructor.NAME);

        tip.setAttribute('id', tipId);
        this.element.setAttribute('aria-describedby', tipId);

        this.setContent();

        if (this.config.animation) {
          $(tip).addClass(ClassName.FADE);
        }

        var placement = typeof this.config.placement === 'function' ? this.config.placement.call(this, tip, this.element) : this.config.placement;

        var attachment = this._getAttachment(placement);

        var container = this.config.container === false ? document.body : $(this.config.container);

        $(tip).data(this.constructor.DATA_KEY, this).appendTo(container);

        $(this.element).trigger(this.constructor.Event.INSERTED);

        this._tether = new Tether({
          attachment: attachment,
          element: tip,
          target: this.element,
          classes: TetherClass,
          classPrefix: CLASS_PREFIX,
          offset: this.config.offset,
          constraints: this.config.constraints,
          addTargetClasses: false
        });

        Util.reflow(tip);
        this._tether.position();

        $(tip).addClass(ClassName.SHOW);

        var complete = function complete() {
          var prevHoverState = _this22._hoverState;
          _this22._hoverState = null;
          _this22._isTransitioning = false;

          $(_this22.element).trigger(_this22.constructor.Event.SHOWN);

          if (prevHoverState === HoverState.OUT) {
            _this22._leave(null, _this22);
          }
        };

        if (Util.supportsTransitionEnd() && $(this.tip).hasClass(ClassName.FADE)) {
          this._isTransitioning = true;
          $(this.tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(Tooltip._TRANSITION_DURATION);
          return;
        }

        complete();
      }
    };

    Tooltip.prototype.hide = function hide(callback) {
      var _this23 = this;

      var tip = this.getTipElement();
      var hideEvent = $.Event(this.constructor.Event.HIDE);
      if (this._isTransitioning) {
        throw new Error('Tooltip is transitioning');
      }
      var complete = function complete() {
        if (_this23._hoverState !== HoverState.SHOW && tip.parentNode) {
          tip.parentNode.removeChild(tip);
        }

        _this23.element.removeAttribute('aria-describedby');
        $(_this23.element).trigger(_this23.constructor.Event.HIDDEN);
        _this23._isTransitioning = false;
        _this23.cleanupTether();

        if (callback) {
          callback();
        }
      };

      $(this.element).trigger(hideEvent);

      if (hideEvent.isDefaultPrevented()) {
        return;
      }

      $(tip).removeClass(ClassName.SHOW);

      this._activeTrigger[Trigger.CLICK] = false;
      this._activeTrigger[Trigger.FOCUS] = false;
      this._activeTrigger[Trigger.HOVER] = false;

      if (Util.supportsTransitionEnd() && $(this.tip).hasClass(ClassName.FADE)) {
        this._isTransitioning = true;
        $(tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(TRANSITION_DURATION);
      } else {
        complete();
      }

      this._hoverState = '';
    };

    // protected

    Tooltip.prototype.isWithContent = function isWithContent() {
      return Boolean(this.getTitle());
    };

    Tooltip.prototype.getTipElement = function getTipElement() {
      return this.tip = this.tip || $(this.config.template)[0];
    };

    Tooltip.prototype.setContent = function setContent() {
      var $tip = $(this.getTipElement());

      this.setElementContent($tip.find(Selector.TOOLTIP_INNER), this.getTitle());

      $tip.removeClass(ClassName.FADE + ' ' + ClassName.SHOW);

      this.cleanupTether();
    };

    Tooltip.prototype.setElementContent = function setElementContent($element, content) {
      var html = this.config.html;
      if ((typeof content === 'undefined' ? 'undefined' : _typeof(content)) === 'object' && (content.nodeType || content.jquery)) {
        // content is a DOM node or a jQuery
        if (html) {
          if (!$(content).parent().is($element)) {
            $element.empty().append(content);
          }
        } else {
          $element.text($(content).text());
        }
      } else {
        $element[html ? 'html' : 'text'](content);
      }
    };

    Tooltip.prototype.getTitle = function getTitle() {
      var title = this.element.getAttribute('data-original-title');

      if (!title) {
        title = typeof this.config.title === 'function' ? this.config.title.call(this.element) : this.config.title;
      }

      return title;
    };

    Tooltip.prototype.cleanupTether = function cleanupTether() {
      if (this._tether) {
        this._tether.destroy();
      }
    };

    // private

    Tooltip.prototype._getAttachment = function _getAttachment(placement) {
      return AttachmentMap[placement.toUpperCase()];
    };

    Tooltip.prototype._setListeners = function _setListeners() {
      var _this24 = this;

      var triggers = this.config.trigger.split(' ');

      triggers.forEach(function (trigger) {
        if (trigger === 'click') {
          $(_this24.element).on(_this24.constructor.Event.CLICK, _this24.config.selector, function (event) {
            return _this24.toggle(event);
          });
        } else if (trigger !== Trigger.MANUAL) {
          var eventIn = trigger === Trigger.HOVER ? _this24.constructor.Event.MOUSEENTER : _this24.constructor.Event.FOCUSIN;
          var eventOut = trigger === Trigger.HOVER ? _this24.constructor.Event.MOUSELEAVE : _this24.constructor.Event.FOCUSOUT;

          $(_this24.element).on(eventIn, _this24.config.selector, function (event) {
            return _this24._enter(event);
          }).on(eventOut, _this24.config.selector, function (event) {
            return _this24._leave(event);
          });
        }

        $(_this24.element).closest('.modal').on('hide.bs.modal', function () {
          return _this24.hide();
        });
      });

      if (this.config.selector) {
        this.config = $.extend({}, this.config, {
          trigger: 'manual',
          selector: ''
        });
      } else {
        this._fixTitle();
      }
    };

    Tooltip.prototype._fixTitle = function _fixTitle() {
      var titleType = _typeof(this.element.getAttribute('data-original-title'));
      if (this.element.getAttribute('title') || titleType !== 'string') {
        this.element.setAttribute('data-original-title', this.element.getAttribute('title') || '');
        this.element.setAttribute('title', '');
      }
    };

    Tooltip.prototype._enter = function _enter(event, context) {
      var dataKey = this.constructor.DATA_KEY;

      context = context || $(event.currentTarget).data(dataKey);

      if (!context) {
        context = new this.constructor(event.currentTarget, this._getDelegateConfig());
        $(event.currentTarget).data(dataKey, context);
      }

      if (event) {
        context._activeTrigger[event.type === 'focusin' ? Trigger.FOCUS : Trigger.HOVER] = true;
      }

      if ($(context.getTipElement()).hasClass(ClassName.SHOW) || context._hoverState === HoverState.SHOW) {
        context._hoverState = HoverState.SHOW;
        return;
      }

      clearTimeout(context._timeout);

      context._hoverState = HoverState.SHOW;

      if (!context.config.delay || !context.config.delay.show) {
        context.show();
        return;
      }

      context._timeout = setTimeout(function () {
        if (context._hoverState === HoverState.SHOW) {
          context.show();
        }
      }, context.config.delay.show);
    };

    Tooltip.prototype._leave = function _leave(event, context) {
      var dataKey = this.constructor.DATA_KEY;

      context = context || $(event.currentTarget).data(dataKey);

      if (!context) {
        context = new this.constructor(event.currentTarget, this._getDelegateConfig());
        $(event.currentTarget).data(dataKey, context);
      }

      if (event) {
        context._activeTrigger[event.type === 'focusout' ? Trigger.FOCUS : Trigger.HOVER] = false;
      }

      if (context._isWithActiveTrigger()) {
        return;
      }

      clearTimeout(context._timeout);

      context._hoverState = HoverState.OUT;

      if (!context.config.delay || !context.config.delay.hide) {
        context.hide();
        return;
      }

      context._timeout = setTimeout(function () {
        if (context._hoverState === HoverState.OUT) {
          context.hide();
        }
      }, context.config.delay.hide);
    };

    Tooltip.prototype._isWithActiveTrigger = function _isWithActiveTrigger() {
      for (var trigger in this._activeTrigger) {
        if (this._activeTrigger[trigger]) {
          return true;
        }
      }

      return false;
    };

    Tooltip.prototype._getConfig = function _getConfig(config) {
      config = $.extend({}, this.constructor.Default, $(this.element).data(), config);

      if (config.delay && typeof config.delay === 'number') {
        config.delay = {
          show: config.delay,
          hide: config.delay
        };
      }

      Util.typeCheckConfig(NAME, config, this.constructor.DefaultType);

      return config;
    };

    Tooltip.prototype._getDelegateConfig = function _getDelegateConfig() {
      var config = {};

      if (this.config) {
        for (var key in this.config) {
          if (this.constructor.Default[key] !== this.config[key]) {
            config[key] = this.config[key];
          }
        }
      }

      return config;
    };

    // static

    Tooltip._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);
        var _config = (typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object' && config;

        if (!data && /dispose|hide/.test(config)) {
          return;
        }

        if (!data) {
          data = new Tooltip(this, _config);
          $(this).data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config]();
        }
      });
    };

    _createClass(Tooltip, null, [{
      key: 'VERSION',
      get: function get() {
        return VERSION;
      }
    }, {
      key: 'Default',
      get: function get() {
        return Default;
      }
    }, {
      key: 'NAME',
      get: function get() {
        return NAME;
      }
    }, {
      key: 'DATA_KEY',
      get: function get() {
        return DATA_KEY;
      }
    }, {
      key: 'Event',
      get: function get() {
        return Event;
      }
    }, {
      key: 'EVENT_KEY',
      get: function get() {
        return EVENT_KEY;
      }
    }, {
      key: 'DefaultType',
      get: function get() {
        return DefaultType;
      }
    }]);

    return Tooltip;
  }();

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Tooltip._jQueryInterface;
  $.fn[NAME].Constructor = Tooltip;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Tooltip._jQueryInterface;
  };

  return Tooltip;
}(jQuery);

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.0.0-alpha.6): popover.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

var Popover = function ($) {

  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  var NAME = 'popover';
  var VERSION = '4.0.0-alpha.6';
  var DATA_KEY = 'bs.popover';
  var EVENT_KEY = '.' + DATA_KEY;
  var JQUERY_NO_CONFLICT = $.fn[NAME];

  var Default = $.extend({}, Tooltip.Default, {
    placement: 'right',
    trigger: 'click',
    content: '',
    template: '<div class="popover" role="tooltip">' + '<h3 class="popover-title"></h3>' + '<div class="popover-content"></div></div>'
  });

  var DefaultType = $.extend({}, Tooltip.DefaultType, {
    content: '(string|element|function)'
  });

  var ClassName = {
    FADE: 'fade',
    SHOW: 'show'
  };

  var Selector = {
    TITLE: '.popover-title',
    CONTENT: '.popover-content'
  };

  var Event = {
    HIDE: 'hide' + EVENT_KEY,
    HIDDEN: 'hidden' + EVENT_KEY,
    SHOW: 'show' + EVENT_KEY,
    SHOWN: 'shown' + EVENT_KEY,
    INSERTED: 'inserted' + EVENT_KEY,
    CLICK: 'click' + EVENT_KEY,
    FOCUSIN: 'focusin' + EVENT_KEY,
    FOCUSOUT: 'focusout' + EVENT_KEY,
    MOUSEENTER: 'mouseenter' + EVENT_KEY,
    MOUSELEAVE: 'mouseleave' + EVENT_KEY
  };

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  var Popover = function (_Tooltip) {
    _inherits(Popover, _Tooltip);

    function Popover() {
      _classCallCheck(this, Popover);

      return _possibleConstructorReturn(this, _Tooltip.apply(this, arguments));
    }

    // overrides

    Popover.prototype.isWithContent = function isWithContent() {
      return this.getTitle() || this._getContent();
    };

    Popover.prototype.getTipElement = function getTipElement() {
      return this.tip = this.tip || $(this.config.template)[0];
    };

    Popover.prototype.setContent = function setContent() {
      var $tip = $(this.getTipElement());

      // we use append for html objects to maintain js events
      this.setElementContent($tip.find(Selector.TITLE), this.getTitle());
      this.setElementContent($tip.find(Selector.CONTENT), this._getContent());

      $tip.removeClass(ClassName.FADE + ' ' + ClassName.SHOW);

      this.cleanupTether();
    };

    // private

    Popover.prototype._getContent = function _getContent() {
      return this.element.getAttribute('data-content') || (typeof this.config.content === 'function' ? this.config.content.call(this.element) : this.config.content);
    };

    // static

    Popover._jQueryInterface = function _jQueryInterface(config) {
      return this.each(function () {
        var data = $(this).data(DATA_KEY);
        var _config = (typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object' ? config : null;

        if (!data && /destroy|hide/.test(config)) {
          return;
        }

        if (!data) {
          data = new Popover(this, _config);
          $(this).data(DATA_KEY, data);
        }

        if (typeof config === 'string') {
          if (data[config] === undefined) {
            throw new Error('No method named "' + config + '"');
          }
          data[config]();
        }
      });
    };

    _createClass(Popover, null, [{
      key: 'VERSION',


      // getters

      get: function get() {
        return VERSION;
      }
    }, {
      key: 'Default',
      get: function get() {
        return Default;
      }
    }, {
      key: 'NAME',
      get: function get() {
        return NAME;
      }
    }, {
      key: 'DATA_KEY',
      get: function get() {
        return DATA_KEY;
      }
    }, {
      key: 'Event',
      get: function get() {
        return Event;
      }
    }, {
      key: 'EVENT_KEY',
      get: function get() {
        return EVENT_KEY;
      }
    }, {
      key: 'DefaultType',
      get: function get() {
        return DefaultType;
      }
    }]);

    return Popover;
  }(Tooltip);

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Popover._jQueryInterface;
  $.fn[NAME].Constructor = Popover;
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return Popover._jQueryInterface;
  };

  return Popover;
}(jQuery);

}();


/***/ }),

/***/ "./node_modules/process/browser.js":
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/webpack/buildin/amd-options.js":
/***/ (function(module, exports) {

/* WEBPACK VAR INJECTION */(function(__webpack_amd_options__) {/* globals __webpack_amd_options__ */
module.exports = __webpack_amd_options__;

/* WEBPACK VAR INJECTION */}.call(exports, {}))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./resources/assets/js/app.js":
/***/ (function(module, exports, __webpack_require__) {


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

__webpack_require__("./resources/assets/js/base.js");

/***/ }),

/***/ "./resources/assets/js/base.js":
/***/ (function(module, exports, __webpack_require__) {


/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.$ = window.jQuery = __webpack_require__("./resources/assets/js/jquery.min.js");
  __webpack_require__("./resources/assets/js/blog/popper.min.js");
  __webpack_require__("./node_modules/bootstrap/dist/js/bootstrap.js");
  __webpack_require__("./resources/assets/js/blog/custom.js");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = __webpack_require__("./node_modules/axios/index.js");

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

var token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

/***/ }),

/***/ "./resources/assets/js/blog/custom.js":
/***/ (function(module, exports) {

(function () {
  $(window).scroll(function () {
    var top = $(document).scrollTop();
    $('.splash').css({
      'background-position': '0px -' + (top / 3).toFixed(2) + 'px'
    });
    if (top > 50) $('#home > .navbar').removeClass('navbar-transparent');else $('#home > .navbar').addClass('navbar-transparent');
  });

  $("a[href='#']").click(function (e) {
    e.preventDefault();
  });

  var $button = $("<div id='source-button' class='btn btn-primary btn-xs'>&lt; &gt;</div>").click(function () {
    var html = $(this).parent().html();
    html = cleanSource(html);
    $("#source-modal pre").text(html);
    $("#source-modal").modal();
  });

  $('.bs-component [data-toggle="popover"]').popover();
  $('.bs-component [data-toggle="tooltip"]').tooltip();

  $(".bs-component").hover(function () {
    $(this).append($button);
    $button.show();
  }, function () {
    $button.hide();
  });

  function cleanSource(html) {
    html = html.replace(/×/g, "&times;").replace(/«/g, "&laquo;").replace(/»/g, "&raquo;").replace(/←/g, "&larr;").replace(/→/g, "&rarr;");

    var lines = html.split(/\n/);

    lines.shift();
    lines.splice(-1, 1);

    var indentSize = lines[0].length - lines[0].trim().length,
        re = new RegExp(" {" + indentSize + "}");

    lines = lines.map(function (line) {
      if (line.match(re)) {
        line = line.substring(indentSize);
      }

      return line;
    });

    lines = lines.join("\n");

    return lines;
  }
})();

/***/ }),

/***/ "./resources/assets/js/blog/popper.min.js":
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/*
 Copyright (C) Federico Zivolo 2017
 Distributed under the MIT License (license terms are at http://opensource.org/licenses/MIT).
 */(function (e, t) {
  'object' == ( false ? 'undefined' : _typeof(exports)) && 'undefined' != typeof module ? module.exports = t() :  true ? !(__WEBPACK_AMD_DEFINE_FACTORY__ = (t),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : e.Popper = t();
})(this, function () {
  'use strict';
  function e(e) {
    return e && '[object Function]' === {}.toString.call(e);
  }function t(e, t) {
    if (1 !== e.nodeType) return [];var o = window.getComputedStyle(e, null);return t ? o[t] : o;
  }function o(e) {
    return 'HTML' === e.nodeName ? e : e.parentNode || e.host;
  }function n(e) {
    if (!e || -1 !== ['HTML', 'BODY', '#document'].indexOf(e.nodeName)) return window.document.body;var i = t(e),
        r = i.overflow,
        p = i.overflowX,
        s = i.overflowY;return (/(auto|scroll)/.test(r + s + p) ? e : n(o(e))
    );
  }function r(e) {
    var o = e && e.offsetParent,
        i = o && o.nodeName;return i && 'BODY' !== i && 'HTML' !== i ? -1 !== ['TD', 'TABLE'].indexOf(o.nodeName) && 'static' === t(o, 'position') ? r(o) : o : window.document.documentElement;
  }function p(e) {
    var t = e.nodeName;return 'BODY' !== t && ('HTML' === t || r(e.firstElementChild) === e);
  }function s(e) {
    return null === e.parentNode ? e : s(e.parentNode);
  }function d(e, t) {
    if (!e || !e.nodeType || !t || !t.nodeType) return window.document.documentElement;var o = e.compareDocumentPosition(t) & Node.DOCUMENT_POSITION_FOLLOWING,
        i = o ? e : t,
        n = o ? t : e,
        a = document.createRange();a.setStart(i, 0), a.setEnd(n, 0);var l = a.commonAncestorContainer;if (e !== l && t !== l || i.contains(n)) return p(l) ? l : r(l);var f = s(e);return f.host ? d(f.host, t) : d(e, s(t).host);
  }function a(e) {
    var t = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : 'top',
        o = 'top' === t ? 'scrollTop' : 'scrollLeft',
        i = e.nodeName;if ('BODY' === i || 'HTML' === i) {
      var n = window.document.documentElement,
          r = window.document.scrollingElement || n;return r[o];
    }return e[o];
  }function l(e, t) {
    var o = 2 < arguments.length && void 0 !== arguments[2] && arguments[2],
        i = a(t, 'top'),
        n = a(t, 'left'),
        r = o ? -1 : 1;return e.top += i * r, e.bottom += i * r, e.left += n * r, e.right += n * r, e;
  }function f(e, t) {
    var o = 'x' === t ? 'Left' : 'Top',
        i = 'Left' == o ? 'Right' : 'Bottom';return +e['border' + o + 'Width'].split('px')[0] + +e['border' + i + 'Width'].split('px')[0];
  }function m(e, t, o, i) {
    return X(t['offset' + e], t['scroll' + e], o['client' + e], o['offset' + e], o['scroll' + e], ne() ? o['offset' + e] + i['margin' + ('Height' === e ? 'Top' : 'Left')] + i['margin' + ('Height' === e ? 'Bottom' : 'Right')] : 0);
  }function c() {
    var e = window.document.body,
        t = window.document.documentElement,
        o = ne() && window.getComputedStyle(t);return { height: m('Height', e, t, o), width: m('Width', e, t, o) };
  }function h(e) {
    return de({}, e, { right: e.left + e.width, bottom: e.top + e.height });
  }function g(e) {
    var o = {};if (ne()) try {
      o = e.getBoundingClientRect();var i = a(e, 'top'),
          n = a(e, 'left');o.top += i, o.left += n, o.bottom += i, o.right += n;
    } catch (e) {} else o = e.getBoundingClientRect();var r = { left: o.left, top: o.top, width: o.right - o.left, height: o.bottom - o.top },
        p = 'HTML' === e.nodeName ? c() : {},
        s = p.width || e.clientWidth || r.right - r.left,
        d = p.height || e.clientHeight || r.bottom - r.top,
        l = e.offsetWidth - s,
        m = e.offsetHeight - d;if (l || m) {
      var g = t(e);l -= f(g, 'x'), m -= f(g, 'y'), r.width -= l, r.height -= m;
    }return h(r);
  }function u(e, o) {
    var i = ne(),
        r = 'HTML' === o.nodeName,
        p = g(e),
        s = g(o),
        d = n(e),
        a = t(o),
        f = +a.borderTopWidth.split('px')[0],
        m = +a.borderLeftWidth.split('px')[0],
        c = h({ top: p.top - s.top - f, left: p.left - s.left - m, width: p.width, height: p.height });if (c.marginTop = 0, c.marginLeft = 0, !i && r) {
      var u = +a.marginTop.split('px')[0],
          b = +a.marginLeft.split('px')[0];c.top -= f - u, c.bottom -= f - u, c.left -= m - b, c.right -= m - b, c.marginTop = u, c.marginLeft = b;
    }return (i ? o.contains(d) : o === d && 'BODY' !== d.nodeName) && (c = l(c, o)), c;
  }function b(e) {
    var t = window.document.documentElement,
        o = u(e, t),
        i = X(t.clientWidth, window.innerWidth || 0),
        n = X(t.clientHeight, window.innerHeight || 0),
        r = a(t),
        p = a(t, 'left'),
        s = { top: r - o.top + o.marginTop, left: p - o.left + o.marginLeft, width: i, height: n };return h(s);
  }function y(e) {
    var i = e.nodeName;return 'BODY' === i || 'HTML' === i ? !1 : 'fixed' === t(e, 'position') || y(o(e));
  }function w(e, t, i, r) {
    var p = { top: 0, left: 0 },
        s = d(e, t);if ('viewport' === r) p = b(s);else {
      var a;'scrollParent' === r ? (a = n(o(e)), 'BODY' === a.nodeName && (a = window.document.documentElement)) : 'window' === r ? a = window.document.documentElement : a = r;var l = u(a, s);if ('HTML' === a.nodeName && !y(s)) {
        var f = c(),
            m = f.height,
            h = f.width;p.top += l.top - l.marginTop, p.bottom = m + l.top, p.left += l.left - l.marginLeft, p.right = h + l.left;
      } else p = l;
    }return p.left += i, p.top += i, p.right -= i, p.bottom -= i, p;
  }function E(e) {
    var t = e.width,
        o = e.height;return t * o;
  }function v(e, t, o, i, n) {
    var r = 5 < arguments.length && void 0 !== arguments[5] ? arguments[5] : 0;if (-1 === e.indexOf('auto')) return e;var p = w(o, i, r, n),
        s = { top: { width: p.width, height: t.top - p.top }, right: { width: p.right - t.right, height: p.height }, bottom: { width: p.width, height: p.bottom - t.bottom }, left: { width: t.left - p.left, height: p.height } },
        d = Object.keys(s).map(function (e) {
      return de({ key: e }, s[e], { area: E(s[e]) });
    }).sort(function (e, t) {
      return t.area - e.area;
    }),
        a = d.filter(function (e) {
      var t = e.width,
          i = e.height;return t >= o.clientWidth && i >= o.clientHeight;
    }),
        l = 0 < a.length ? a[0].key : d[0].key,
        f = e.split('-')[1];return l + (f ? '-' + f : '');
  }function x(e, t, o) {
    var i = d(t, o);return u(o, i);
  }function O(e) {
    var t = window.getComputedStyle(e),
        o = parseFloat(t.marginTop) + parseFloat(t.marginBottom),
        i = parseFloat(t.marginLeft) + parseFloat(t.marginRight),
        n = { width: e.offsetWidth + i, height: e.offsetHeight + o };return n;
  }function L(e) {
    var t = { left: 'right', right: 'left', bottom: 'top', top: 'bottom' };return e.replace(/left|right|bottom|top/g, function (e) {
      return t[e];
    });
  }function S(e, t, o) {
    o = o.split('-')[0];var i = O(e),
        n = { width: i.width, height: i.height },
        r = -1 !== ['right', 'left'].indexOf(o),
        p = r ? 'top' : 'left',
        s = r ? 'left' : 'top',
        d = r ? 'height' : 'width',
        a = r ? 'width' : 'height';return n[p] = t[p] + t[d] / 2 - i[d] / 2, n[s] = o === s ? t[s] - i[a] : t[L(s)], n;
  }function T(e, t) {
    return Array.prototype.find ? e.find(t) : e.filter(t)[0];
  }function C(e, t, o) {
    if (Array.prototype.findIndex) return e.findIndex(function (e) {
      return e[t] === o;
    });var i = T(e, function (e) {
      return e[t] === o;
    });return e.indexOf(i);
  }function N(t, o, i) {
    var n = void 0 === i ? t : t.slice(0, C(t, 'name', i));return n.forEach(function (t) {
      t.function && console.warn('`modifier.function` is deprecated, use `modifier.fn`!');var i = t.function || t.fn;t.enabled && e(i) && (o.offsets.popper = h(o.offsets.popper), o.offsets.reference = h(o.offsets.reference), o = i(o, t));
    }), o;
  }function k() {
    if (!this.state.isDestroyed) {
      var e = { instance: this, styles: {}, arrowStyles: {}, attributes: {}, flipped: !1, offsets: {} };e.offsets.reference = x(this.state, this.popper, this.reference), e.placement = v(this.options.placement, e.offsets.reference, this.popper, this.reference, this.options.modifiers.flip.boundariesElement, this.options.modifiers.flip.padding), e.originalPlacement = e.placement, e.offsets.popper = S(this.popper, e.offsets.reference, e.placement), e.offsets.popper.position = 'absolute', e = N(this.modifiers, e), this.state.isCreated ? this.options.onUpdate(e) : (this.state.isCreated = !0, this.options.onCreate(e));
    }
  }function W(e, t) {
    return e.some(function (e) {
      var o = e.name,
          i = e.enabled;return i && o === t;
    });
  }function B(e) {
    for (var t = [!1, 'ms', 'Webkit', 'Moz', 'O'], o = e.charAt(0).toUpperCase() + e.slice(1), n = 0; n < t.length - 1; n++) {
      var i = t[n],
          r = i ? '' + i + o : e;if ('undefined' != typeof window.document.body.style[r]) return r;
    }return null;
  }function P() {
    return this.state.isDestroyed = !0, W(this.modifiers, 'applyStyle') && (this.popper.removeAttribute('x-placement'), this.popper.style.left = '', this.popper.style.position = '', this.popper.style.top = '', this.popper.style[B('transform')] = ''), this.disableEventListeners(), this.options.removeOnDestroy && this.popper.parentNode.removeChild(this.popper), this;
  }function D(e, t, o, i) {
    var r = 'BODY' === e.nodeName,
        p = r ? window : e;p.addEventListener(t, o, { passive: !0 }), r || D(n(p.parentNode), t, o, i), i.push(p);
  }function H(e, t, o, i) {
    o.updateBound = i, window.addEventListener('resize', o.updateBound, { passive: !0 });var r = n(e);return D(r, 'scroll', o.updateBound, o.scrollParents), o.scrollElement = r, o.eventsEnabled = !0, o;
  }function A() {
    this.state.eventsEnabled || (this.state = H(this.reference, this.options, this.state, this.scheduleUpdate));
  }function M(e, t) {
    return window.removeEventListener('resize', t.updateBound), t.scrollParents.forEach(function (e) {
      e.removeEventListener('scroll', t.updateBound);
    }), t.updateBound = null, t.scrollParents = [], t.scrollElement = null, t.eventsEnabled = !1, t;
  }function I() {
    this.state.eventsEnabled && (window.cancelAnimationFrame(this.scheduleUpdate), this.state = M(this.reference, this.state));
  }function R(e) {
    return '' !== e && !isNaN(parseFloat(e)) && isFinite(e);
  }function U(e, t) {
    Object.keys(t).forEach(function (o) {
      var i = '';-1 !== ['width', 'height', 'top', 'right', 'bottom', 'left'].indexOf(o) && R(t[o]) && (i = 'px'), e.style[o] = t[o] + i;
    });
  }function Y(e, t) {
    Object.keys(t).forEach(function (o) {
      var i = t[o];!1 === i ? e.removeAttribute(o) : e.setAttribute(o, t[o]);
    });
  }function F(e, t, o) {
    var i = T(e, function (e) {
      var o = e.name;return o === t;
    }),
        n = !!i && e.some(function (e) {
      return e.name === o && e.enabled && e.order < i.order;
    });if (!n) {
      var r = '`' + t + '`';console.warn('`' + o + '`' + ' modifier is required by ' + r + ' modifier in order to work, be sure to include it before ' + r + '!');
    }return n;
  }function j(e) {
    return 'end' === e ? 'start' : 'start' === e ? 'end' : e;
  }function K(e) {
    var t = 1 < arguments.length && void 0 !== arguments[1] && arguments[1],
        o = le.indexOf(e),
        i = le.slice(o + 1).concat(le.slice(0, o));return t ? i.reverse() : i;
  }function q(e, t, o, i) {
    var n = e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),
        r = +n[1],
        p = n[2];if (!r) return e;if (0 === p.indexOf('%')) {
      var s;switch (p) {case '%p':
          s = o;break;case '%':case '%r':default:
          s = i;}var d = h(s);return d[t] / 100 * r;
    }if ('vh' === p || 'vw' === p) {
      var a;return a = 'vh' === p ? X(document.documentElement.clientHeight, window.innerHeight || 0) : X(document.documentElement.clientWidth, window.innerWidth || 0), a / 100 * r;
    }return r;
  }function G(e, t, o, i) {
    var n = [0, 0],
        r = -1 !== ['right', 'left'].indexOf(i),
        p = e.split(/(\+|\-)/).map(function (e) {
      return e.trim();
    }),
        s = p.indexOf(T(p, function (e) {
      return -1 !== e.search(/,|\s/);
    }));p[s] && -1 === p[s].indexOf(',') && console.warn('Offsets separated by white space(s) are deprecated, use a comma (,) instead.');var d = /\s*,\s*|\s+/,
        a = -1 === s ? [p] : [p.slice(0, s).concat([p[s].split(d)[0]]), [p[s].split(d)[1]].concat(p.slice(s + 1))];return a = a.map(function (e, i) {
      var n = (1 === i ? !r : r) ? 'height' : 'width',
          p = !1;return e.reduce(function (e, t) {
        return '' === e[e.length - 1] && -1 !== ['+', '-'].indexOf(t) ? (e[e.length - 1] = t, p = !0, e) : p ? (e[e.length - 1] += t, p = !1, e) : e.concat(t);
      }, []).map(function (e) {
        return q(e, n, t, o);
      });
    }), a.forEach(function (e, t) {
      e.forEach(function (o, i) {
        R(o) && (n[t] += o * ('-' === e[i - 1] ? -1 : 1));
      });
    }), n;
  }function z(e, t) {
    var o,
        i = t.offset,
        n = e.placement,
        r = e.offsets,
        p = r.popper,
        s = r.reference,
        d = n.split('-')[0];return o = R(+i) ? [+i, 0] : G(i, p, s, d), 'left' === d ? (p.top += o[0], p.left -= o[1]) : 'right' === d ? (p.top += o[0], p.left += o[1]) : 'top' === d ? (p.left += o[0], p.top -= o[1]) : 'bottom' === d && (p.left += o[0], p.top += o[1]), e.popper = p, e;
  }for (var V = Math.min, _ = Math.floor, X = Math.max, Q = ['native code', '[object MutationObserverConstructor]'], J = function J(e) {
    return Q.some(function (t) {
      return -1 < (e || '').toString().indexOf(t);
    });
  }, Z = 'undefined' != typeof window, $ = ['Edge', 'Trident', 'Firefox'], ee = 0, te = 0; te < $.length; te += 1) {
    if (Z && 0 <= navigator.userAgent.indexOf($[te])) {
      ee = 1;break;
    }
  }var i,
      oe = Z && J(window.MutationObserver),
      ie = oe ? function (e) {
    var t = !1,
        o = 0,
        i = document.createElement('span'),
        n = new MutationObserver(function () {
      e(), t = !1;
    });return n.observe(i, { attributes: !0 }), function () {
      t || (t = !0, i.setAttribute('x-index', o), ++o);
    };
  } : function (e) {
    var t = !1;return function () {
      t || (t = !0, setTimeout(function () {
        t = !1, e();
      }, ee));
    };
  },
      ne = function ne() {
    return void 0 == i && (i = -1 !== navigator.appVersion.indexOf('MSIE 10')), i;
  },
      re = function re(e, t) {
    if (!(e instanceof t)) throw new TypeError('Cannot call a class as a function');
  },
      pe = function () {
    function e(e, t) {
      for (var o, n = 0; n < t.length; n++) {
        o = t[n], o.enumerable = o.enumerable || !1, o.configurable = !0, 'value' in o && (o.writable = !0), Object.defineProperty(e, o.key, o);
      }
    }return function (t, o, i) {
      return o && e(t.prototype, o), i && e(t, i), t;
    };
  }(),
      se = function se(e, t, o) {
    return t in e ? Object.defineProperty(e, t, { value: o, enumerable: !0, configurable: !0, writable: !0 }) : e[t] = o, e;
  },
      de = Object.assign || function (e) {
    for (var t, o = 1; o < arguments.length; o++) {
      for (var i in t = arguments[o], t) {
        Object.prototype.hasOwnProperty.call(t, i) && (e[i] = t[i]);
      }
    }return e;
  },
      ae = ['auto-start', 'auto', 'auto-end', 'top-start', 'top', 'top-end', 'right-start', 'right', 'right-end', 'bottom-end', 'bottom', 'bottom-start', 'left-end', 'left', 'left-start'],
      le = ae.slice(3),
      fe = { FLIP: 'flip', CLOCKWISE: 'clockwise', COUNTERCLOCKWISE: 'counterclockwise' },
      me = function () {
    function t(o, i) {
      var n = this,
          r = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : {};re(this, t), this.scheduleUpdate = function () {
        return requestAnimationFrame(n.update);
      }, this.update = ie(this.update.bind(this)), this.options = de({}, t.Defaults, r), this.state = { isDestroyed: !1, isCreated: !1, scrollParents: [] }, this.reference = o.jquery ? o[0] : o, this.popper = i.jquery ? i[0] : i, this.options.modifiers = {}, Object.keys(de({}, t.Defaults.modifiers, r.modifiers)).forEach(function (e) {
        n.options.modifiers[e] = de({}, t.Defaults.modifiers[e] || {}, r.modifiers ? r.modifiers[e] : {});
      }), this.modifiers = Object.keys(this.options.modifiers).map(function (e) {
        return de({ name: e }, n.options.modifiers[e]);
      }).sort(function (e, t) {
        return e.order - t.order;
      }), this.modifiers.forEach(function (t) {
        t.enabled && e(t.onLoad) && t.onLoad(n.reference, n.popper, n.options, t, n.state);
      }), this.update();var p = this.options.eventsEnabled;p && this.enableEventListeners(), this.state.eventsEnabled = p;
    }return pe(t, [{ key: 'update', value: function value() {
        return k.call(this);
      } }, { key: 'destroy', value: function value() {
        return P.call(this);
      } }, { key: 'enableEventListeners', value: function value() {
        return A.call(this);
      } }, { key: 'disableEventListeners', value: function value() {
        return I.call(this);
      } }]), t;
  }();return me.Utils = ('undefined' == typeof window ? global : window).PopperUtils, me.placements = ae, me.Defaults = { placement: 'bottom', eventsEnabled: !0, removeOnDestroy: !1, onCreate: function onCreate() {}, onUpdate: function onUpdate() {}, modifiers: { shift: { order: 100, enabled: !0, fn: function fn(e) {
          var t = e.placement,
              o = t.split('-')[0],
              i = t.split('-')[1];if (i) {
            var n = e.offsets,
                r = n.reference,
                p = n.popper,
                s = -1 !== ['bottom', 'top'].indexOf(o),
                d = s ? 'left' : 'top',
                a = s ? 'width' : 'height',
                l = { start: se({}, d, r[d]), end: se({}, d, r[d] + r[a] - p[a]) };e.offsets.popper = de({}, p, l[i]);
          }return e;
        } }, offset: { order: 200, enabled: !0, fn: z, offset: 0 }, preventOverflow: { order: 300, enabled: !0, fn: function fn(e, t) {
          var o = t.boundariesElement || r(e.instance.popper);e.instance.reference === o && (o = r(o));var i = w(e.instance.popper, e.instance.reference, t.padding, o);t.boundaries = i;var n = t.priority,
              p = e.offsets.popper,
              s = { primary: function primary(e) {
              var o = p[e];return p[e] < i[e] && !t.escapeWithReference && (o = X(p[e], i[e])), se({}, e, o);
            }, secondary: function secondary(e) {
              var o = 'right' === e ? 'left' : 'top',
                  n = p[o];return p[e] > i[e] && !t.escapeWithReference && (n = V(p[o], i[e] - ('right' === e ? p.width : p.height))), se({}, o, n);
            } };return n.forEach(function (e) {
            var t = -1 === ['left', 'top'].indexOf(e) ? 'secondary' : 'primary';p = de({}, p, s[t](e));
          }), e.offsets.popper = p, e;
        }, priority: ['left', 'right', 'top', 'bottom'], padding: 5, boundariesElement: 'scrollParent' }, keepTogether: { order: 400, enabled: !0, fn: function fn(e) {
          var t = e.offsets,
              o = t.popper,
              i = t.reference,
              n = e.placement.split('-')[0],
              r = _,
              p = -1 !== ['top', 'bottom'].indexOf(n),
              s = p ? 'right' : 'bottom',
              d = p ? 'left' : 'top',
              a = p ? 'width' : 'height';return o[s] < r(i[d]) && (e.offsets.popper[d] = r(i[d]) - o[a]), o[d] > r(i[s]) && (e.offsets.popper[d] = r(i[s])), e;
        } }, arrow: { order: 500, enabled: !0, fn: function fn(e, o) {
          if (!F(e.instance.modifiers, 'arrow', 'keepTogether')) return e;var i = o.element;if ('string' == typeof i) {
            if (i = e.instance.popper.querySelector(i), !i) return e;
          } else if (!e.instance.popper.contains(i)) return console.warn('WARNING: `arrow.element` must be child of its popper element!'), e;var n = e.placement.split('-')[0],
              r = e.offsets,
              p = r.popper,
              s = r.reference,
              d = -1 !== ['left', 'right'].indexOf(n),
              a = d ? 'height' : 'width',
              l = d ? 'Top' : 'Left',
              f = l.toLowerCase(),
              m = d ? 'left' : 'top',
              c = d ? 'bottom' : 'right',
              g = O(i)[a];s[c] - g < p[f] && (e.offsets.popper[f] -= p[f] - (s[c] - g)), s[f] + g > p[c] && (e.offsets.popper[f] += s[f] + g - p[c]);var u = s[f] + s[a] / 2 - g / 2,
              b = t(e.instance.popper, 'margin' + l).replace('px', ''),
              y = u - h(e.offsets.popper)[f] - b;return y = X(V(p[a] - g, y), 0), e.arrowElement = i, e.offsets.arrow = {}, e.offsets.arrow[f] = Math.round(y), e.offsets.arrow[m] = '', e;
        }, element: '[x-arrow]' }, flip: { order: 600, enabled: !0, fn: function fn(e, t) {
          if (W(e.instance.modifiers, 'inner')) return e;if (e.flipped && e.placement === e.originalPlacement) return e;var o = w(e.instance.popper, e.instance.reference, t.padding, t.boundariesElement),
              i = e.placement.split('-')[0],
              n = L(i),
              r = e.placement.split('-')[1] || '',
              p = [];switch (t.behavior) {case fe.FLIP:
              p = [i, n];break;case fe.CLOCKWISE:
              p = K(i);break;case fe.COUNTERCLOCKWISE:
              p = K(i, !0);break;default:
              p = t.behavior;}return p.forEach(function (s, d) {
            if (i !== s || p.length === d + 1) return e;i = e.placement.split('-')[0], n = L(i);var a = e.offsets.popper,
                l = e.offsets.reference,
                f = _,
                m = 'left' === i && f(a.right) > f(l.left) || 'right' === i && f(a.left) < f(l.right) || 'top' === i && f(a.bottom) > f(l.top) || 'bottom' === i && f(a.top) < f(l.bottom),
                c = f(a.left) < f(o.left),
                h = f(a.right) > f(o.right),
                g = f(a.top) < f(o.top),
                u = f(a.bottom) > f(o.bottom),
                b = 'left' === i && c || 'right' === i && h || 'top' === i && g || 'bottom' === i && u,
                y = -1 !== ['top', 'bottom'].indexOf(i),
                w = !!t.flipVariations && (y && 'start' === r && c || y && 'end' === r && h || !y && 'start' === r && g || !y && 'end' === r && u);(m || b || w) && (e.flipped = !0, (m || b) && (i = p[d + 1]), w && (r = j(r)), e.placement = i + (r ? '-' + r : ''), e.offsets.popper = de({}, e.offsets.popper, S(e.instance.popper, e.offsets.reference, e.placement)), e = N(e.instance.modifiers, e, 'flip'));
          }), e;
        }, behavior: 'flip', padding: 5, boundariesElement: 'viewport' }, inner: { order: 700, enabled: !1, fn: function fn(e) {
          var t = e.placement,
              o = t.split('-')[0],
              i = e.offsets,
              n = i.popper,
              r = i.reference,
              p = -1 !== ['left', 'right'].indexOf(o),
              s = -1 === ['top', 'left'].indexOf(o);return n[p ? 'left' : 'top'] = r[o] - (s ? n[p ? 'width' : 'height'] : 0), e.placement = L(t), e.offsets.popper = h(n), e;
        } }, hide: { order: 800, enabled: !0, fn: function fn(e) {
          if (!F(e.instance.modifiers, 'hide', 'preventOverflow')) return e;var t = e.offsets.reference,
              o = T(e.instance.modifiers, function (e) {
            return 'preventOverflow' === e.name;
          }).boundaries;if (t.bottom < o.top || t.left > o.right || t.top > o.bottom || t.right < o.left) {
            if (!0 === e.hide) return e;e.hide = !0, e.attributes['x-out-of-boundaries'] = '';
          } else {
            if (!1 === e.hide) return e;e.hide = !1, e.attributes['x-out-of-boundaries'] = !1;
          }return e;
        } }, computeStyle: { order: 850, enabled: !0, fn: function fn(e, t) {
          var o = t.x,
              i = t.y,
              n = e.offsets.popper,
              p = T(e.instance.modifiers, function (e) {
            return 'applyStyle' === e.name;
          }).gpuAcceleration;void 0 !== p && console.warn('WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!');var s,
              d,
              a = void 0 === p ? t.gpuAcceleration : p,
              l = r(e.instance.popper),
              f = g(l),
              m = { position: n.position },
              c = { left: _(n.left), top: _(n.top), bottom: _(n.bottom), right: _(n.right) },
              h = 'bottom' === o ? 'top' : 'bottom',
              u = 'right' === i ? 'left' : 'right',
              b = B('transform');if (d = 'bottom' == h ? -f.height + c.bottom : c.top, s = 'right' == u ? -f.width + c.right : c.left, a && b) m[b] = 'translate3d(' + s + 'px, ' + d + 'px, 0)', m[h] = 0, m[u] = 0, m.willChange = 'transform';else {
            var y = 'bottom' == h ? -1 : 1,
                w = 'right' == u ? -1 : 1;m[h] = d * y, m[u] = s * w, m.willChange = h + ', ' + u;
          }var E = { "x-placement": e.placement };return e.attributes = de({}, E, e.attributes), e.styles = de({}, m, e.styles), e.arrowStyles = de({}, e.offsets.arrow, e.arrowStyles), e;
        }, gpuAcceleration: !0, x: 'bottom', y: 'right' }, applyStyle: { order: 900, enabled: !0, fn: function fn(e) {
          return U(e.instance.popper, e.styles), Y(e.instance.popper, e.attributes), e.arrowElement && Object.keys(e.arrowStyles).length && U(e.arrowElement, e.arrowStyles), e;
        }, onLoad: function onLoad(e, t, o, i, n) {
          var r = x(n, t, e),
              p = v(o.placement, r, t, e, o.modifiers.flip.boundariesElement, o.modifiers.flip.padding);return t.setAttribute('x-placement', p), U(t, { position: 'absolute' }), o;
        }, gpuAcceleration: void 0 } } }, me;
});
//# sourceMappingURL=popper.min.js.map
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__("./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./resources/assets/js/jquery.min.js":
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/*! jQuery v3.2.1 | (c) JS Foundation and other contributors | jquery.org/license */
!function (a, b) {
  "use strict";
  "object" == ( false ? "undefined" : _typeof(module)) && "object" == _typeof(module.exports) ? module.exports = a.document ? b(a, !0) : function (a) {
    if (!a.document) throw new Error("jQuery requires a window with a document");return b(a);
  } : b(a);
}("undefined" != typeof window ? window : this, function (a, b) {
  "use strict";
  var c = [],
      d = a.document,
      e = Object.getPrototypeOf,
      f = c.slice,
      g = c.concat,
      h = c.push,
      i = c.indexOf,
      j = {},
      k = j.toString,
      l = j.hasOwnProperty,
      m = l.toString,
      n = m.call(Object),
      o = {};function p(a, b) {
    b = b || d;var c = b.createElement("script");c.text = a, b.head.appendChild(c).parentNode.removeChild(c);
  }var q = "3.2.1",
      r = function r(a, b) {
    return new r.fn.init(a, b);
  },
      s = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
      t = /^-ms-/,
      u = /-([a-z])/g,
      v = function v(a, b) {
    return b.toUpperCase();
  };r.fn = r.prototype = { jquery: q, constructor: r, length: 0, toArray: function toArray() {
      return f.call(this);
    }, get: function get(a) {
      return null == a ? f.call(this) : a < 0 ? this[a + this.length] : this[a];
    }, pushStack: function pushStack(a) {
      var b = r.merge(this.constructor(), a);return b.prevObject = this, b;
    }, each: function each(a) {
      return r.each(this, a);
    }, map: function map(a) {
      return this.pushStack(r.map(this, function (b, c) {
        return a.call(b, c, b);
      }));
    }, slice: function slice() {
      return this.pushStack(f.apply(this, arguments));
    }, first: function first() {
      return this.eq(0);
    }, last: function last() {
      return this.eq(-1);
    }, eq: function eq(a) {
      var b = this.length,
          c = +a + (a < 0 ? b : 0);return this.pushStack(c >= 0 && c < b ? [this[c]] : []);
    }, end: function end() {
      return this.prevObject || this.constructor();
    }, push: h, sort: c.sort, splice: c.splice }, r.extend = r.fn.extend = function () {
    var a,
        b,
        c,
        d,
        e,
        f,
        g = arguments[0] || {},
        h = 1,
        i = arguments.length,
        j = !1;for ("boolean" == typeof g && (j = g, g = arguments[h] || {}, h++), "object" == (typeof g === "undefined" ? "undefined" : _typeof(g)) || r.isFunction(g) || (g = {}), h === i && (g = this, h--); h < i; h++) {
      if (null != (a = arguments[h])) for (b in a) {
        c = g[b], d = a[b], g !== d && (j && d && (r.isPlainObject(d) || (e = Array.isArray(d))) ? (e ? (e = !1, f = c && Array.isArray(c) ? c : []) : f = c && r.isPlainObject(c) ? c : {}, g[b] = r.extend(j, f, d)) : void 0 !== d && (g[b] = d));
      }
    }return g;
  }, r.extend({ expando: "jQuery" + (q + Math.random()).replace(/\D/g, ""), isReady: !0, error: function error(a) {
      throw new Error(a);
    }, noop: function noop() {}, isFunction: function isFunction(a) {
      return "function" === r.type(a);
    }, isWindow: function isWindow(a) {
      return null != a && a === a.window;
    }, isNumeric: function isNumeric(a) {
      var b = r.type(a);return ("number" === b || "string" === b) && !isNaN(a - parseFloat(a));
    }, isPlainObject: function isPlainObject(a) {
      var b, c;return !(!a || "[object Object]" !== k.call(a)) && (!(b = e(a)) || (c = l.call(b, "constructor") && b.constructor, "function" == typeof c && m.call(c) === n));
    }, isEmptyObject: function isEmptyObject(a) {
      var b;for (b in a) {
        return !1;
      }return !0;
    }, type: function type(a) {
      return null == a ? a + "" : "object" == (typeof a === "undefined" ? "undefined" : _typeof(a)) || "function" == typeof a ? j[k.call(a)] || "object" : typeof a === "undefined" ? "undefined" : _typeof(a);
    }, globalEval: function globalEval(a) {
      p(a);
    }, camelCase: function camelCase(a) {
      return a.replace(t, "ms-").replace(u, v);
    }, each: function each(a, b) {
      var c,
          d = 0;if (w(a)) {
        for (c = a.length; d < c; d++) {
          if (b.call(a[d], d, a[d]) === !1) break;
        }
      } else for (d in a) {
        if (b.call(a[d], d, a[d]) === !1) break;
      }return a;
    }, trim: function trim(a) {
      return null == a ? "" : (a + "").replace(s, "");
    }, makeArray: function makeArray(a, b) {
      var c = b || [];return null != a && (w(Object(a)) ? r.merge(c, "string" == typeof a ? [a] : a) : h.call(c, a)), c;
    }, inArray: function inArray(a, b, c) {
      return null == b ? -1 : i.call(b, a, c);
    }, merge: function merge(a, b) {
      for (var c = +b.length, d = 0, e = a.length; d < c; d++) {
        a[e++] = b[d];
      }return a.length = e, a;
    }, grep: function grep(a, b, c) {
      for (var d, e = [], f = 0, g = a.length, h = !c; f < g; f++) {
        d = !b(a[f], f), d !== h && e.push(a[f]);
      }return e;
    }, map: function map(a, b, c) {
      var d,
          e,
          f = 0,
          h = [];if (w(a)) for (d = a.length; f < d; f++) {
        e = b(a[f], f, c), null != e && h.push(e);
      } else for (f in a) {
        e = b(a[f], f, c), null != e && h.push(e);
      }return g.apply([], h);
    }, guid: 1, proxy: function proxy(a, b) {
      var c, d, e;if ("string" == typeof b && (c = a[b], b = a, a = c), r.isFunction(a)) return d = f.call(arguments, 2), e = function e() {
        return a.apply(b || this, d.concat(f.call(arguments)));
      }, e.guid = a.guid = a.guid || r.guid++, e;
    }, now: Date.now, support: o }), "function" == typeof Symbol && (r.fn[Symbol.iterator] = c[Symbol.iterator]), r.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (a, b) {
    j["[object " + b + "]"] = b.toLowerCase();
  });function w(a) {
    var b = !!a && "length" in a && a.length,
        c = r.type(a);return "function" !== c && !r.isWindow(a) && ("array" === c || 0 === b || "number" == typeof b && b > 0 && b - 1 in a);
  }var x = function (a) {
    var b,
        c,
        d,
        e,
        f,
        g,
        h,
        i,
        j,
        k,
        l,
        m,
        n,
        o,
        p,
        q,
        r,
        s,
        t,
        u = "sizzle" + 1 * new Date(),
        v = a.document,
        w = 0,
        x = 0,
        y = ha(),
        z = ha(),
        A = ha(),
        B = function B(a, b) {
      return a === b && (l = !0), 0;
    },
        C = {}.hasOwnProperty,
        D = [],
        E = D.pop,
        F = D.push,
        G = D.push,
        H = D.slice,
        I = function I(a, b) {
      for (var c = 0, d = a.length; c < d; c++) {
        if (a[c] === b) return c;
      }return -1;
    },
        J = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
        K = "[\\x20\\t\\r\\n\\f]",
        L = "(?:\\\\.|[\\w-]|[^\0-\\xa0])+",
        M = "\\[" + K + "*(" + L + ")(?:" + K + "*([*^$|!~]?=)" + K + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + L + "))|)" + K + "*\\]",
        N = ":(" + L + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + M + ")*)|.*)\\)|)",
        O = new RegExp(K + "+", "g"),
        P = new RegExp("^" + K + "+|((?:^|[^\\\\])(?:\\\\.)*)" + K + "+$", "g"),
        Q = new RegExp("^" + K + "*," + K + "*"),
        R = new RegExp("^" + K + "*([>+~]|" + K + ")" + K + "*"),
        S = new RegExp("=" + K + "*([^\\]'\"]*?)" + K + "*\\]", "g"),
        T = new RegExp(N),
        U = new RegExp("^" + L + "$"),
        V = { ID: new RegExp("^#(" + L + ")"), CLASS: new RegExp("^\\.(" + L + ")"), TAG: new RegExp("^(" + L + "|[*])"), ATTR: new RegExp("^" + M), PSEUDO: new RegExp("^" + N), CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + K + "*(even|odd|(([+-]|)(\\d*)n|)" + K + "*(?:([+-]|)" + K + "*(\\d+)|))" + K + "*\\)|)", "i"), bool: new RegExp("^(?:" + J + ")$", "i"), needsContext: new RegExp("^" + K + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + K + "*((?:-\\d)?\\d*)" + K + "*\\)|)(?=[^-]|$)", "i") },
        W = /^(?:input|select|textarea|button)$/i,
        X = /^h\d$/i,
        Y = /^[^{]+\{\s*\[native \w/,
        Z = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
        $ = /[+~]/,
        _ = new RegExp("\\\\([\\da-f]{1,6}" + K + "?|(" + K + ")|.)", "ig"),
        aa = function aa(a, b, c) {
      var d = "0x" + b - 65536;return d !== d || c ? b : d < 0 ? String.fromCharCode(d + 65536) : String.fromCharCode(d >> 10 | 55296, 1023 & d | 56320);
    },
        ba = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
        ca = function ca(a, b) {
      return b ? "\0" === a ? "\uFFFD" : a.slice(0, -1) + "\\" + a.charCodeAt(a.length - 1).toString(16) + " " : "\\" + a;
    },
        da = function da() {
      m();
    },
        ea = ta(function (a) {
      return a.disabled === !0 && ("form" in a || "label" in a);
    }, { dir: "parentNode", next: "legend" });try {
      G.apply(D = H.call(v.childNodes), v.childNodes), D[v.childNodes.length].nodeType;
    } catch (fa) {
      G = { apply: D.length ? function (a, b) {
          F.apply(a, H.call(b));
        } : function (a, b) {
          var c = a.length,
              d = 0;while (a[c++] = b[d++]) {}a.length = c - 1;
        } };
    }function ga(a, b, d, e) {
      var f,
          h,
          j,
          k,
          l,
          o,
          r,
          s = b && b.ownerDocument,
          w = b ? b.nodeType : 9;if (d = d || [], "string" != typeof a || !a || 1 !== w && 9 !== w && 11 !== w) return d;if (!e && ((b ? b.ownerDocument || b : v) !== n && m(b), b = b || n, p)) {
        if (11 !== w && (l = Z.exec(a))) if (f = l[1]) {
          if (9 === w) {
            if (!(j = b.getElementById(f))) return d;if (j.id === f) return d.push(j), d;
          } else if (s && (j = s.getElementById(f)) && t(b, j) && j.id === f) return d.push(j), d;
        } else {
          if (l[2]) return G.apply(d, b.getElementsByTagName(a)), d;if ((f = l[3]) && c.getElementsByClassName && b.getElementsByClassName) return G.apply(d, b.getElementsByClassName(f)), d;
        }if (c.qsa && !A[a + " "] && (!q || !q.test(a))) {
          if (1 !== w) s = b, r = a;else if ("object" !== b.nodeName.toLowerCase()) {
            (k = b.getAttribute("id")) ? k = k.replace(ba, ca) : b.setAttribute("id", k = u), o = g(a), h = o.length;while (h--) {
              o[h] = "#" + k + " " + sa(o[h]);
            }r = o.join(","), s = $.test(a) && qa(b.parentNode) || b;
          }if (r) try {
            return G.apply(d, s.querySelectorAll(r)), d;
          } catch (x) {} finally {
            k === u && b.removeAttribute("id");
          }
        }
      }return i(a.replace(P, "$1"), b, d, e);
    }function ha() {
      var a = [];function b(c, e) {
        return a.push(c + " ") > d.cacheLength && delete b[a.shift()], b[c + " "] = e;
      }return b;
    }function ia(a) {
      return a[u] = !0, a;
    }function ja(a) {
      var b = n.createElement("fieldset");try {
        return !!a(b);
      } catch (c) {
        return !1;
      } finally {
        b.parentNode && b.parentNode.removeChild(b), b = null;
      }
    }function ka(a, b) {
      var c = a.split("|"),
          e = c.length;while (e--) {
        d.attrHandle[c[e]] = b;
      }
    }function la(a, b) {
      var c = b && a,
          d = c && 1 === a.nodeType && 1 === b.nodeType && a.sourceIndex - b.sourceIndex;if (d) return d;if (c) while (c = c.nextSibling) {
        if (c === b) return -1;
      }return a ? 1 : -1;
    }function ma(a) {
      return function (b) {
        var c = b.nodeName.toLowerCase();return "input" === c && b.type === a;
      };
    }function na(a) {
      return function (b) {
        var c = b.nodeName.toLowerCase();return ("input" === c || "button" === c) && b.type === a;
      };
    }function oa(a) {
      return function (b) {
        return "form" in b ? b.parentNode && b.disabled === !1 ? "label" in b ? "label" in b.parentNode ? b.parentNode.disabled === a : b.disabled === a : b.isDisabled === a || b.isDisabled !== !a && ea(b) === a : b.disabled === a : "label" in b && b.disabled === a;
      };
    }function pa(a) {
      return ia(function (b) {
        return b = +b, ia(function (c, d) {
          var e,
              f = a([], c.length, b),
              g = f.length;while (g--) {
            c[e = f[g]] && (c[e] = !(d[e] = c[e]));
          }
        });
      });
    }function qa(a) {
      return a && "undefined" != typeof a.getElementsByTagName && a;
    }c = ga.support = {}, f = ga.isXML = function (a) {
      var b = a && (a.ownerDocument || a).documentElement;return !!b && "HTML" !== b.nodeName;
    }, m = ga.setDocument = function (a) {
      var b,
          e,
          g = a ? a.ownerDocument || a : v;return g !== n && 9 === g.nodeType && g.documentElement ? (n = g, o = n.documentElement, p = !f(n), v !== n && (e = n.defaultView) && e.top !== e && (e.addEventListener ? e.addEventListener("unload", da, !1) : e.attachEvent && e.attachEvent("onunload", da)), c.attributes = ja(function (a) {
        return a.className = "i", !a.getAttribute("className");
      }), c.getElementsByTagName = ja(function (a) {
        return a.appendChild(n.createComment("")), !a.getElementsByTagName("*").length;
      }), c.getElementsByClassName = Y.test(n.getElementsByClassName), c.getById = ja(function (a) {
        return o.appendChild(a).id = u, !n.getElementsByName || !n.getElementsByName(u).length;
      }), c.getById ? (d.filter.ID = function (a) {
        var b = a.replace(_, aa);return function (a) {
          return a.getAttribute("id") === b;
        };
      }, d.find.ID = function (a, b) {
        if ("undefined" != typeof b.getElementById && p) {
          var c = b.getElementById(a);return c ? [c] : [];
        }
      }) : (d.filter.ID = function (a) {
        var b = a.replace(_, aa);return function (a) {
          var c = "undefined" != typeof a.getAttributeNode && a.getAttributeNode("id");return c && c.value === b;
        };
      }, d.find.ID = function (a, b) {
        if ("undefined" != typeof b.getElementById && p) {
          var c,
              d,
              e,
              f = b.getElementById(a);if (f) {
            if (c = f.getAttributeNode("id"), c && c.value === a) return [f];e = b.getElementsByName(a), d = 0;while (f = e[d++]) {
              if (c = f.getAttributeNode("id"), c && c.value === a) return [f];
            }
          }return [];
        }
      }), d.find.TAG = c.getElementsByTagName ? function (a, b) {
        return "undefined" != typeof b.getElementsByTagName ? b.getElementsByTagName(a) : c.qsa ? b.querySelectorAll(a) : void 0;
      } : function (a, b) {
        var c,
            d = [],
            e = 0,
            f = b.getElementsByTagName(a);if ("*" === a) {
          while (c = f[e++]) {
            1 === c.nodeType && d.push(c);
          }return d;
        }return f;
      }, d.find.CLASS = c.getElementsByClassName && function (a, b) {
        if ("undefined" != typeof b.getElementsByClassName && p) return b.getElementsByClassName(a);
      }, r = [], q = [], (c.qsa = Y.test(n.querySelectorAll)) && (ja(function (a) {
        o.appendChild(a).innerHTML = "<a id='" + u + "'></a><select id='" + u + "-\r\\' msallowcapture=''><option selected=''></option></select>", a.querySelectorAll("[msallowcapture^='']").length && q.push("[*^$]=" + K + "*(?:''|\"\")"), a.querySelectorAll("[selected]").length || q.push("\\[" + K + "*(?:value|" + J + ")"), a.querySelectorAll("[id~=" + u + "-]").length || q.push("~="), a.querySelectorAll(":checked").length || q.push(":checked"), a.querySelectorAll("a#" + u + "+*").length || q.push(".#.+[+~]");
      }), ja(function (a) {
        a.innerHTML = "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";var b = n.createElement("input");b.setAttribute("type", "hidden"), a.appendChild(b).setAttribute("name", "D"), a.querySelectorAll("[name=d]").length && q.push("name" + K + "*[*^$|!~]?="), 2 !== a.querySelectorAll(":enabled").length && q.push(":enabled", ":disabled"), o.appendChild(a).disabled = !0, 2 !== a.querySelectorAll(":disabled").length && q.push(":enabled", ":disabled"), a.querySelectorAll("*,:x"), q.push(",.*:");
      })), (c.matchesSelector = Y.test(s = o.matches || o.webkitMatchesSelector || o.mozMatchesSelector || o.oMatchesSelector || o.msMatchesSelector)) && ja(function (a) {
        c.disconnectedMatch = s.call(a, "*"), s.call(a, "[s!='']:x"), r.push("!=", N);
      }), q = q.length && new RegExp(q.join("|")), r = r.length && new RegExp(r.join("|")), b = Y.test(o.compareDocumentPosition), t = b || Y.test(o.contains) ? function (a, b) {
        var c = 9 === a.nodeType ? a.documentElement : a,
            d = b && b.parentNode;return a === d || !(!d || 1 !== d.nodeType || !(c.contains ? c.contains(d) : a.compareDocumentPosition && 16 & a.compareDocumentPosition(d)));
      } : function (a, b) {
        if (b) while (b = b.parentNode) {
          if (b === a) return !0;
        }return !1;
      }, B = b ? function (a, b) {
        if (a === b) return l = !0, 0;var d = !a.compareDocumentPosition - !b.compareDocumentPosition;return d ? d : (d = (a.ownerDocument || a) === (b.ownerDocument || b) ? a.compareDocumentPosition(b) : 1, 1 & d || !c.sortDetached && b.compareDocumentPosition(a) === d ? a === n || a.ownerDocument === v && t(v, a) ? -1 : b === n || b.ownerDocument === v && t(v, b) ? 1 : k ? I(k, a) - I(k, b) : 0 : 4 & d ? -1 : 1);
      } : function (a, b) {
        if (a === b) return l = !0, 0;var c,
            d = 0,
            e = a.parentNode,
            f = b.parentNode,
            g = [a],
            h = [b];if (!e || !f) return a === n ? -1 : b === n ? 1 : e ? -1 : f ? 1 : k ? I(k, a) - I(k, b) : 0;if (e === f) return la(a, b);c = a;while (c = c.parentNode) {
          g.unshift(c);
        }c = b;while (c = c.parentNode) {
          h.unshift(c);
        }while (g[d] === h[d]) {
          d++;
        }return d ? la(g[d], h[d]) : g[d] === v ? -1 : h[d] === v ? 1 : 0;
      }, n) : n;
    }, ga.matches = function (a, b) {
      return ga(a, null, null, b);
    }, ga.matchesSelector = function (a, b) {
      if ((a.ownerDocument || a) !== n && m(a), b = b.replace(S, "='$1']"), c.matchesSelector && p && !A[b + " "] && (!r || !r.test(b)) && (!q || !q.test(b))) try {
        var d = s.call(a, b);if (d || c.disconnectedMatch || a.document && 11 !== a.document.nodeType) return d;
      } catch (e) {}return ga(b, n, null, [a]).length > 0;
    }, ga.contains = function (a, b) {
      return (a.ownerDocument || a) !== n && m(a), t(a, b);
    }, ga.attr = function (a, b) {
      (a.ownerDocument || a) !== n && m(a);var e = d.attrHandle[b.toLowerCase()],
          f = e && C.call(d.attrHandle, b.toLowerCase()) ? e(a, b, !p) : void 0;return void 0 !== f ? f : c.attributes || !p ? a.getAttribute(b) : (f = a.getAttributeNode(b)) && f.specified ? f.value : null;
    }, ga.escape = function (a) {
      return (a + "").replace(ba, ca);
    }, ga.error = function (a) {
      throw new Error("Syntax error, unrecognized expression: " + a);
    }, ga.uniqueSort = function (a) {
      var b,
          d = [],
          e = 0,
          f = 0;if (l = !c.detectDuplicates, k = !c.sortStable && a.slice(0), a.sort(B), l) {
        while (b = a[f++]) {
          b === a[f] && (e = d.push(f));
        }while (e--) {
          a.splice(d[e], 1);
        }
      }return k = null, a;
    }, e = ga.getText = function (a) {
      var b,
          c = "",
          d = 0,
          f = a.nodeType;if (f) {
        if (1 === f || 9 === f || 11 === f) {
          if ("string" == typeof a.textContent) return a.textContent;for (a = a.firstChild; a; a = a.nextSibling) {
            c += e(a);
          }
        } else if (3 === f || 4 === f) return a.nodeValue;
      } else while (b = a[d++]) {
        c += e(b);
      }return c;
    }, d = ga.selectors = { cacheLength: 50, createPseudo: ia, match: V, attrHandle: {}, find: {}, relative: { ">": { dir: "parentNode", first: !0 }, " ": { dir: "parentNode" }, "+": { dir: "previousSibling", first: !0 }, "~": { dir: "previousSibling" } }, preFilter: { ATTR: function ATTR(a) {
          return a[1] = a[1].replace(_, aa), a[3] = (a[3] || a[4] || a[5] || "").replace(_, aa), "~=" === a[2] && (a[3] = " " + a[3] + " "), a.slice(0, 4);
        }, CHILD: function CHILD(a) {
          return a[1] = a[1].toLowerCase(), "nth" === a[1].slice(0, 3) ? (a[3] || ga.error(a[0]), a[4] = +(a[4] ? a[5] + (a[6] || 1) : 2 * ("even" === a[3] || "odd" === a[3])), a[5] = +(a[7] + a[8] || "odd" === a[3])) : a[3] && ga.error(a[0]), a;
        }, PSEUDO: function PSEUDO(a) {
          var b,
              c = !a[6] && a[2];return V.CHILD.test(a[0]) ? null : (a[3] ? a[2] = a[4] || a[5] || "" : c && T.test(c) && (b = g(c, !0)) && (b = c.indexOf(")", c.length - b) - c.length) && (a[0] = a[0].slice(0, b), a[2] = c.slice(0, b)), a.slice(0, 3));
        } }, filter: { TAG: function TAG(a) {
          var b = a.replace(_, aa).toLowerCase();return "*" === a ? function () {
            return !0;
          } : function (a) {
            return a.nodeName && a.nodeName.toLowerCase() === b;
          };
        }, CLASS: function CLASS(a) {
          var b = y[a + " "];return b || (b = new RegExp("(^|" + K + ")" + a + "(" + K + "|$)")) && y(a, function (a) {
            return b.test("string" == typeof a.className && a.className || "undefined" != typeof a.getAttribute && a.getAttribute("class") || "");
          });
        }, ATTR: function ATTR(a, b, c) {
          return function (d) {
            var e = ga.attr(d, a);return null == e ? "!=" === b : !b || (e += "", "=" === b ? e === c : "!=" === b ? e !== c : "^=" === b ? c && 0 === e.indexOf(c) : "*=" === b ? c && e.indexOf(c) > -1 : "$=" === b ? c && e.slice(-c.length) === c : "~=" === b ? (" " + e.replace(O, " ") + " ").indexOf(c) > -1 : "|=" === b && (e === c || e.slice(0, c.length + 1) === c + "-"));
          };
        }, CHILD: function CHILD(a, b, c, d, e) {
          var f = "nth" !== a.slice(0, 3),
              g = "last" !== a.slice(-4),
              h = "of-type" === b;return 1 === d && 0 === e ? function (a) {
            return !!a.parentNode;
          } : function (b, c, i) {
            var j,
                k,
                l,
                m,
                n,
                o,
                p = f !== g ? "nextSibling" : "previousSibling",
                q = b.parentNode,
                r = h && b.nodeName.toLowerCase(),
                s = !i && !h,
                t = !1;if (q) {
              if (f) {
                while (p) {
                  m = b;while (m = m[p]) {
                    if (h ? m.nodeName.toLowerCase() === r : 1 === m.nodeType) return !1;
                  }o = p = "only" === a && !o && "nextSibling";
                }return !0;
              }if (o = [g ? q.firstChild : q.lastChild], g && s) {
                m = q, l = m[u] || (m[u] = {}), k = l[m.uniqueID] || (l[m.uniqueID] = {}), j = k[a] || [], n = j[0] === w && j[1], t = n && j[2], m = n && q.childNodes[n];while (m = ++n && m && m[p] || (t = n = 0) || o.pop()) {
                  if (1 === m.nodeType && ++t && m === b) {
                    k[a] = [w, n, t];break;
                  }
                }
              } else if (s && (m = b, l = m[u] || (m[u] = {}), k = l[m.uniqueID] || (l[m.uniqueID] = {}), j = k[a] || [], n = j[0] === w && j[1], t = n), t === !1) while (m = ++n && m && m[p] || (t = n = 0) || o.pop()) {
                if ((h ? m.nodeName.toLowerCase() === r : 1 === m.nodeType) && ++t && (s && (l = m[u] || (m[u] = {}), k = l[m.uniqueID] || (l[m.uniqueID] = {}), k[a] = [w, t]), m === b)) break;
              }return t -= e, t === d || t % d === 0 && t / d >= 0;
            }
          };
        }, PSEUDO: function PSEUDO(a, b) {
          var c,
              e = d.pseudos[a] || d.setFilters[a.toLowerCase()] || ga.error("unsupported pseudo: " + a);return e[u] ? e(b) : e.length > 1 ? (c = [a, a, "", b], d.setFilters.hasOwnProperty(a.toLowerCase()) ? ia(function (a, c) {
            var d,
                f = e(a, b),
                g = f.length;while (g--) {
              d = I(a, f[g]), a[d] = !(c[d] = f[g]);
            }
          }) : function (a) {
            return e(a, 0, c);
          }) : e;
        } }, pseudos: { not: ia(function (a) {
          var b = [],
              c = [],
              d = h(a.replace(P, "$1"));return d[u] ? ia(function (a, b, c, e) {
            var f,
                g = d(a, null, e, []),
                h = a.length;while (h--) {
              (f = g[h]) && (a[h] = !(b[h] = f));
            }
          }) : function (a, e, f) {
            return b[0] = a, d(b, null, f, c), b[0] = null, !c.pop();
          };
        }), has: ia(function (a) {
          return function (b) {
            return ga(a, b).length > 0;
          };
        }), contains: ia(function (a) {
          return a = a.replace(_, aa), function (b) {
            return (b.textContent || b.innerText || e(b)).indexOf(a) > -1;
          };
        }), lang: ia(function (a) {
          return U.test(a || "") || ga.error("unsupported lang: " + a), a = a.replace(_, aa).toLowerCase(), function (b) {
            var c;do {
              if (c = p ? b.lang : b.getAttribute("xml:lang") || b.getAttribute("lang")) return c = c.toLowerCase(), c === a || 0 === c.indexOf(a + "-");
            } while ((b = b.parentNode) && 1 === b.nodeType);return !1;
          };
        }), target: function target(b) {
          var c = a.location && a.location.hash;return c && c.slice(1) === b.id;
        }, root: function root(a) {
          return a === o;
        }, focus: function focus(a) {
          return a === n.activeElement && (!n.hasFocus || n.hasFocus()) && !!(a.type || a.href || ~a.tabIndex);
        }, enabled: oa(!1), disabled: oa(!0), checked: function checked(a) {
          var b = a.nodeName.toLowerCase();return "input" === b && !!a.checked || "option" === b && !!a.selected;
        }, selected: function selected(a) {
          return a.parentNode && a.parentNode.selectedIndex, a.selected === !0;
        }, empty: function empty(a) {
          for (a = a.firstChild; a; a = a.nextSibling) {
            if (a.nodeType < 6) return !1;
          }return !0;
        }, parent: function parent(a) {
          return !d.pseudos.empty(a);
        }, header: function header(a) {
          return X.test(a.nodeName);
        }, input: function input(a) {
          return W.test(a.nodeName);
        }, button: function button(a) {
          var b = a.nodeName.toLowerCase();return "input" === b && "button" === a.type || "button" === b;
        }, text: function text(a) {
          var b;return "input" === a.nodeName.toLowerCase() && "text" === a.type && (null == (b = a.getAttribute("type")) || "text" === b.toLowerCase());
        }, first: pa(function () {
          return [0];
        }), last: pa(function (a, b) {
          return [b - 1];
        }), eq: pa(function (a, b, c) {
          return [c < 0 ? c + b : c];
        }), even: pa(function (a, b) {
          for (var c = 0; c < b; c += 2) {
            a.push(c);
          }return a;
        }), odd: pa(function (a, b) {
          for (var c = 1; c < b; c += 2) {
            a.push(c);
          }return a;
        }), lt: pa(function (a, b, c) {
          for (var d = c < 0 ? c + b : c; --d >= 0;) {
            a.push(d);
          }return a;
        }), gt: pa(function (a, b, c) {
          for (var d = c < 0 ? c + b : c; ++d < b;) {
            a.push(d);
          }return a;
        }) } }, d.pseudos.nth = d.pseudos.eq;for (b in { radio: !0, checkbox: !0, file: !0, password: !0, image: !0 }) {
      d.pseudos[b] = ma(b);
    }for (b in { submit: !0, reset: !0 }) {
      d.pseudos[b] = na(b);
    }function ra() {}ra.prototype = d.filters = d.pseudos, d.setFilters = new ra(), g = ga.tokenize = function (a, b) {
      var c,
          e,
          f,
          g,
          h,
          i,
          j,
          k = z[a + " "];if (k) return b ? 0 : k.slice(0);h = a, i = [], j = d.preFilter;while (h) {
        c && !(e = Q.exec(h)) || (e && (h = h.slice(e[0].length) || h), i.push(f = [])), c = !1, (e = R.exec(h)) && (c = e.shift(), f.push({ value: c, type: e[0].replace(P, " ") }), h = h.slice(c.length));for (g in d.filter) {
          !(e = V[g].exec(h)) || j[g] && !(e = j[g](e)) || (c = e.shift(), f.push({ value: c, type: g, matches: e }), h = h.slice(c.length));
        }if (!c) break;
      }return b ? h.length : h ? ga.error(a) : z(a, i).slice(0);
    };function sa(a) {
      for (var b = 0, c = a.length, d = ""; b < c; b++) {
        d += a[b].value;
      }return d;
    }function ta(a, b, c) {
      var d = b.dir,
          e = b.next,
          f = e || d,
          g = c && "parentNode" === f,
          h = x++;return b.first ? function (b, c, e) {
        while (b = b[d]) {
          if (1 === b.nodeType || g) return a(b, c, e);
        }return !1;
      } : function (b, c, i) {
        var j,
            k,
            l,
            m = [w, h];if (i) {
          while (b = b[d]) {
            if ((1 === b.nodeType || g) && a(b, c, i)) return !0;
          }
        } else while (b = b[d]) {
          if (1 === b.nodeType || g) if (l = b[u] || (b[u] = {}), k = l[b.uniqueID] || (l[b.uniqueID] = {}), e && e === b.nodeName.toLowerCase()) b = b[d] || b;else {
            if ((j = k[f]) && j[0] === w && j[1] === h) return m[2] = j[2];if (k[f] = m, m[2] = a(b, c, i)) return !0;
          }
        }return !1;
      };
    }function ua(a) {
      return a.length > 1 ? function (b, c, d) {
        var e = a.length;while (e--) {
          if (!a[e](b, c, d)) return !1;
        }return !0;
      } : a[0];
    }function va(a, b, c) {
      for (var d = 0, e = b.length; d < e; d++) {
        ga(a, b[d], c);
      }return c;
    }function wa(a, b, c, d, e) {
      for (var f, g = [], h = 0, i = a.length, j = null != b; h < i; h++) {
        (f = a[h]) && (c && !c(f, d, e) || (g.push(f), j && b.push(h)));
      }return g;
    }function xa(a, b, c, d, e, f) {
      return d && !d[u] && (d = xa(d)), e && !e[u] && (e = xa(e, f)), ia(function (f, g, h, i) {
        var j,
            k,
            l,
            m = [],
            n = [],
            o = g.length,
            p = f || va(b || "*", h.nodeType ? [h] : h, []),
            q = !a || !f && b ? p : wa(p, m, a, h, i),
            r = c ? e || (f ? a : o || d) ? [] : g : q;if (c && c(q, r, h, i), d) {
          j = wa(r, n), d(j, [], h, i), k = j.length;while (k--) {
            (l = j[k]) && (r[n[k]] = !(q[n[k]] = l));
          }
        }if (f) {
          if (e || a) {
            if (e) {
              j = [], k = r.length;while (k--) {
                (l = r[k]) && j.push(q[k] = l);
              }e(null, r = [], j, i);
            }k = r.length;while (k--) {
              (l = r[k]) && (j = e ? I(f, l) : m[k]) > -1 && (f[j] = !(g[j] = l));
            }
          }
        } else r = wa(r === g ? r.splice(o, r.length) : r), e ? e(null, g, r, i) : G.apply(g, r);
      });
    }function ya(a) {
      for (var b, c, e, f = a.length, g = d.relative[a[0].type], h = g || d.relative[" "], i = g ? 1 : 0, k = ta(function (a) {
        return a === b;
      }, h, !0), l = ta(function (a) {
        return I(b, a) > -1;
      }, h, !0), m = [function (a, c, d) {
        var e = !g && (d || c !== j) || ((b = c).nodeType ? k(a, c, d) : l(a, c, d));return b = null, e;
      }]; i < f; i++) {
        if (c = d.relative[a[i].type]) m = [ta(ua(m), c)];else {
          if (c = d.filter[a[i].type].apply(null, a[i].matches), c[u]) {
            for (e = ++i; e < f; e++) {
              if (d.relative[a[e].type]) break;
            }return xa(i > 1 && ua(m), i > 1 && sa(a.slice(0, i - 1).concat({ value: " " === a[i - 2].type ? "*" : "" })).replace(P, "$1"), c, i < e && ya(a.slice(i, e)), e < f && ya(a = a.slice(e)), e < f && sa(a));
          }m.push(c);
        }
      }return ua(m);
    }function za(a, b) {
      var c = b.length > 0,
          e = a.length > 0,
          f = function f(_f, g, h, i, k) {
        var l,
            o,
            q,
            r = 0,
            s = "0",
            t = _f && [],
            u = [],
            v = j,
            x = _f || e && d.find.TAG("*", k),
            y = w += null == v ? 1 : Math.random() || .1,
            z = x.length;for (k && (j = g === n || g || k); s !== z && null != (l = x[s]); s++) {
          if (e && l) {
            o = 0, g || l.ownerDocument === n || (m(l), h = !p);while (q = a[o++]) {
              if (q(l, g || n, h)) {
                i.push(l);break;
              }
            }k && (w = y);
          }c && ((l = !q && l) && r--, _f && t.push(l));
        }if (r += s, c && s !== r) {
          o = 0;while (q = b[o++]) {
            q(t, u, g, h);
          }if (_f) {
            if (r > 0) while (s--) {
              t[s] || u[s] || (u[s] = E.call(i));
            }u = wa(u);
          }G.apply(i, u), k && !_f && u.length > 0 && r + b.length > 1 && ga.uniqueSort(i);
        }return k && (w = y, j = v), t;
      };return c ? ia(f) : f;
    }return h = ga.compile = function (a, b) {
      var c,
          d = [],
          e = [],
          f = A[a + " "];if (!f) {
        b || (b = g(a)), c = b.length;while (c--) {
          f = ya(b[c]), f[u] ? d.push(f) : e.push(f);
        }f = A(a, za(e, d)), f.selector = a;
      }return f;
    }, i = ga.select = function (a, b, c, e) {
      var f,
          i,
          j,
          k,
          l,
          m = "function" == typeof a && a,
          n = !e && g(a = m.selector || a);if (c = c || [], 1 === n.length) {
        if (i = n[0] = n[0].slice(0), i.length > 2 && "ID" === (j = i[0]).type && 9 === b.nodeType && p && d.relative[i[1].type]) {
          if (b = (d.find.ID(j.matches[0].replace(_, aa), b) || [])[0], !b) return c;m && (b = b.parentNode), a = a.slice(i.shift().value.length);
        }f = V.needsContext.test(a) ? 0 : i.length;while (f--) {
          if (j = i[f], d.relative[k = j.type]) break;if ((l = d.find[k]) && (e = l(j.matches[0].replace(_, aa), $.test(i[0].type) && qa(b.parentNode) || b))) {
            if (i.splice(f, 1), a = e.length && sa(i), !a) return G.apply(c, e), c;break;
          }
        }
      }return (m || h(a, n))(e, b, !p, c, !b || $.test(a) && qa(b.parentNode) || b), c;
    }, c.sortStable = u.split("").sort(B).join("") === u, c.detectDuplicates = !!l, m(), c.sortDetached = ja(function (a) {
      return 1 & a.compareDocumentPosition(n.createElement("fieldset"));
    }), ja(function (a) {
      return a.innerHTML = "<a href='#'></a>", "#" === a.firstChild.getAttribute("href");
    }) || ka("type|href|height|width", function (a, b, c) {
      if (!c) return a.getAttribute(b, "type" === b.toLowerCase() ? 1 : 2);
    }), c.attributes && ja(function (a) {
      return a.innerHTML = "<input/>", a.firstChild.setAttribute("value", ""), "" === a.firstChild.getAttribute("value");
    }) || ka("value", function (a, b, c) {
      if (!c && "input" === a.nodeName.toLowerCase()) return a.defaultValue;
    }), ja(function (a) {
      return null == a.getAttribute("disabled");
    }) || ka(J, function (a, b, c) {
      var d;if (!c) return a[b] === !0 ? b.toLowerCase() : (d = a.getAttributeNode(b)) && d.specified ? d.value : null;
    }), ga;
  }(a);r.find = x, r.expr = x.selectors, r.expr[":"] = r.expr.pseudos, r.uniqueSort = r.unique = x.uniqueSort, r.text = x.getText, r.isXMLDoc = x.isXML, r.contains = x.contains, r.escapeSelector = x.escape;var y = function y(a, b, c) {
    var d = [],
        e = void 0 !== c;while ((a = a[b]) && 9 !== a.nodeType) {
      if (1 === a.nodeType) {
        if (e && r(a).is(c)) break;d.push(a);
      }
    }return d;
  },
      z = function z(a, b) {
    for (var c = []; a; a = a.nextSibling) {
      1 === a.nodeType && a !== b && c.push(a);
    }return c;
  },
      A = r.expr.match.needsContext;function B(a, b) {
    return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase();
  }var C = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i,
      D = /^.[^:#\[\.,]*$/;function E(a, b, c) {
    return r.isFunction(b) ? r.grep(a, function (a, d) {
      return !!b.call(a, d, a) !== c;
    }) : b.nodeType ? r.grep(a, function (a) {
      return a === b !== c;
    }) : "string" != typeof b ? r.grep(a, function (a) {
      return i.call(b, a) > -1 !== c;
    }) : D.test(b) ? r.filter(b, a, c) : (b = r.filter(b, a), r.grep(a, function (a) {
      return i.call(b, a) > -1 !== c && 1 === a.nodeType;
    }));
  }r.filter = function (a, b, c) {
    var d = b[0];return c && (a = ":not(" + a + ")"), 1 === b.length && 1 === d.nodeType ? r.find.matchesSelector(d, a) ? [d] : [] : r.find.matches(a, r.grep(b, function (a) {
      return 1 === a.nodeType;
    }));
  }, r.fn.extend({ find: function find(a) {
      var b,
          c,
          d = this.length,
          e = this;if ("string" != typeof a) return this.pushStack(r(a).filter(function () {
        for (b = 0; b < d; b++) {
          if (r.contains(e[b], this)) return !0;
        }
      }));for (c = this.pushStack([]), b = 0; b < d; b++) {
        r.find(a, e[b], c);
      }return d > 1 ? r.uniqueSort(c) : c;
    }, filter: function filter(a) {
      return this.pushStack(E(this, a || [], !1));
    }, not: function not(a) {
      return this.pushStack(E(this, a || [], !0));
    }, is: function is(a) {
      return !!E(this, "string" == typeof a && A.test(a) ? r(a) : a || [], !1).length;
    } });var F,
      G = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/,
      H = r.fn.init = function (a, b, c) {
    var e, f;if (!a) return this;if (c = c || F, "string" == typeof a) {
      if (e = "<" === a[0] && ">" === a[a.length - 1] && a.length >= 3 ? [null, a, null] : G.exec(a), !e || !e[1] && b) return !b || b.jquery ? (b || c).find(a) : this.constructor(b).find(a);if (e[1]) {
        if (b = b instanceof r ? b[0] : b, r.merge(this, r.parseHTML(e[1], b && b.nodeType ? b.ownerDocument || b : d, !0)), C.test(e[1]) && r.isPlainObject(b)) for (e in b) {
          r.isFunction(this[e]) ? this[e](b[e]) : this.attr(e, b[e]);
        }return this;
      }return f = d.getElementById(e[2]), f && (this[0] = f, this.length = 1), this;
    }return a.nodeType ? (this[0] = a, this.length = 1, this) : r.isFunction(a) ? void 0 !== c.ready ? c.ready(a) : a(r) : r.makeArray(a, this);
  };H.prototype = r.fn, F = r(d);var I = /^(?:parents|prev(?:Until|All))/,
      J = { children: !0, contents: !0, next: !0, prev: !0 };r.fn.extend({ has: function has(a) {
      var b = r(a, this),
          c = b.length;return this.filter(function () {
        for (var a = 0; a < c; a++) {
          if (r.contains(this, b[a])) return !0;
        }
      });
    }, closest: function closest(a, b) {
      var c,
          d = 0,
          e = this.length,
          f = [],
          g = "string" != typeof a && r(a);if (!A.test(a)) for (; d < e; d++) {
        for (c = this[d]; c && c !== b; c = c.parentNode) {
          if (c.nodeType < 11 && (g ? g.index(c) > -1 : 1 === c.nodeType && r.find.matchesSelector(c, a))) {
            f.push(c);break;
          }
        }
      }return this.pushStack(f.length > 1 ? r.uniqueSort(f) : f);
    }, index: function index(a) {
      return a ? "string" == typeof a ? i.call(r(a), this[0]) : i.call(this, a.jquery ? a[0] : a) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
    }, add: function add(a, b) {
      return this.pushStack(r.uniqueSort(r.merge(this.get(), r(a, b))));
    }, addBack: function addBack(a) {
      return this.add(null == a ? this.prevObject : this.prevObject.filter(a));
    } });function K(a, b) {
    while ((a = a[b]) && 1 !== a.nodeType) {}return a;
  }r.each({ parent: function parent(a) {
      var b = a.parentNode;return b && 11 !== b.nodeType ? b : null;
    }, parents: function parents(a) {
      return y(a, "parentNode");
    }, parentsUntil: function parentsUntil(a, b, c) {
      return y(a, "parentNode", c);
    }, next: function next(a) {
      return K(a, "nextSibling");
    }, prev: function prev(a) {
      return K(a, "previousSibling");
    }, nextAll: function nextAll(a) {
      return y(a, "nextSibling");
    }, prevAll: function prevAll(a) {
      return y(a, "previousSibling");
    }, nextUntil: function nextUntil(a, b, c) {
      return y(a, "nextSibling", c);
    }, prevUntil: function prevUntil(a, b, c) {
      return y(a, "previousSibling", c);
    }, siblings: function siblings(a) {
      return z((a.parentNode || {}).firstChild, a);
    }, children: function children(a) {
      return z(a.firstChild);
    }, contents: function contents(a) {
      return B(a, "iframe") ? a.contentDocument : (B(a, "template") && (a = a.content || a), r.merge([], a.childNodes));
    } }, function (a, b) {
    r.fn[a] = function (c, d) {
      var e = r.map(this, b, c);return "Until" !== a.slice(-5) && (d = c), d && "string" == typeof d && (e = r.filter(d, e)), this.length > 1 && (J[a] || r.uniqueSort(e), I.test(a) && e.reverse()), this.pushStack(e);
    };
  });var L = /[^\x20\t\r\n\f]+/g;function M(a) {
    var b = {};return r.each(a.match(L) || [], function (a, c) {
      b[c] = !0;
    }), b;
  }r.Callbacks = function (a) {
    a = "string" == typeof a ? M(a) : r.extend({}, a);var b,
        c,
        d,
        e,
        f = [],
        g = [],
        h = -1,
        i = function i() {
      for (e = e || a.once, d = b = !0; g.length; h = -1) {
        c = g.shift();while (++h < f.length) {
          f[h].apply(c[0], c[1]) === !1 && a.stopOnFalse && (h = f.length, c = !1);
        }
      }a.memory || (c = !1), b = !1, e && (f = c ? [] : "");
    },
        j = { add: function add() {
        return f && (c && !b && (h = f.length - 1, g.push(c)), function d(b) {
          r.each(b, function (b, c) {
            r.isFunction(c) ? a.unique && j.has(c) || f.push(c) : c && c.length && "string" !== r.type(c) && d(c);
          });
        }(arguments), c && !b && i()), this;
      }, remove: function remove() {
        return r.each(arguments, function (a, b) {
          var c;while ((c = r.inArray(b, f, c)) > -1) {
            f.splice(c, 1), c <= h && h--;
          }
        }), this;
      }, has: function has(a) {
        return a ? r.inArray(a, f) > -1 : f.length > 0;
      }, empty: function empty() {
        return f && (f = []), this;
      }, disable: function disable() {
        return e = g = [], f = c = "", this;
      }, disabled: function disabled() {
        return !f;
      }, lock: function lock() {
        return e = g = [], c || b || (f = c = ""), this;
      }, locked: function locked() {
        return !!e;
      }, fireWith: function fireWith(a, c) {
        return e || (c = c || [], c = [a, c.slice ? c.slice() : c], g.push(c), b || i()), this;
      }, fire: function fire() {
        return j.fireWith(this, arguments), this;
      }, fired: function fired() {
        return !!d;
      } };return j;
  };function N(a) {
    return a;
  }function O(a) {
    throw a;
  }function P(a, b, c, d) {
    var e;try {
      a && r.isFunction(e = a.promise) ? e.call(a).done(b).fail(c) : a && r.isFunction(e = a.then) ? e.call(a, b, c) : b.apply(void 0, [a].slice(d));
    } catch (a) {
      c.apply(void 0, [a]);
    }
  }r.extend({ Deferred: function Deferred(b) {
      var c = [["notify", "progress", r.Callbacks("memory"), r.Callbacks("memory"), 2], ["resolve", "done", r.Callbacks("once memory"), r.Callbacks("once memory"), 0, "resolved"], ["reject", "fail", r.Callbacks("once memory"), r.Callbacks("once memory"), 1, "rejected"]],
          d = "pending",
          e = { state: function state() {
          return d;
        }, always: function always() {
          return f.done(arguments).fail(arguments), this;
        }, "catch": function _catch(a) {
          return e.then(null, a);
        }, pipe: function pipe() {
          var a = arguments;return r.Deferred(function (b) {
            r.each(c, function (c, d) {
              var e = r.isFunction(a[d[4]]) && a[d[4]];f[d[1]](function () {
                var a = e && e.apply(this, arguments);a && r.isFunction(a.promise) ? a.promise().progress(b.notify).done(b.resolve).fail(b.reject) : b[d[0] + "With"](this, e ? [a] : arguments);
              });
            }), a = null;
          }).promise();
        }, then: function then(b, d, e) {
          var f = 0;function g(b, c, d, e) {
            return function () {
              var h = this,
                  i = arguments,
                  j = function j() {
                var a, j;if (!(b < f)) {
                  if (a = d.apply(h, i), a === c.promise()) throw new TypeError("Thenable self-resolution");j = a && ("object" == (typeof a === "undefined" ? "undefined" : _typeof(a)) || "function" == typeof a) && a.then, r.isFunction(j) ? e ? j.call(a, g(f, c, N, e), g(f, c, O, e)) : (f++, j.call(a, g(f, c, N, e), g(f, c, O, e), g(f, c, N, c.notifyWith))) : (d !== N && (h = void 0, i = [a]), (e || c.resolveWith)(h, i));
                }
              },
                  k = e ? j : function () {
                try {
                  j();
                } catch (a) {
                  r.Deferred.exceptionHook && r.Deferred.exceptionHook(a, k.stackTrace), b + 1 >= f && (d !== O && (h = void 0, i = [a]), c.rejectWith(h, i));
                }
              };b ? k() : (r.Deferred.getStackHook && (k.stackTrace = r.Deferred.getStackHook()), a.setTimeout(k));
            };
          }return r.Deferred(function (a) {
            c[0][3].add(g(0, a, r.isFunction(e) ? e : N, a.notifyWith)), c[1][3].add(g(0, a, r.isFunction(b) ? b : N)), c[2][3].add(g(0, a, r.isFunction(d) ? d : O));
          }).promise();
        }, promise: function promise(a) {
          return null != a ? r.extend(a, e) : e;
        } },
          f = {};return r.each(c, function (a, b) {
        var g = b[2],
            h = b[5];e[b[1]] = g.add, h && g.add(function () {
          d = h;
        }, c[3 - a][2].disable, c[0][2].lock), g.add(b[3].fire), f[b[0]] = function () {
          return f[b[0] + "With"](this === f ? void 0 : this, arguments), this;
        }, f[b[0] + "With"] = g.fireWith;
      }), e.promise(f), b && b.call(f, f), f;
    }, when: function when(a) {
      var b = arguments.length,
          c = b,
          d = Array(c),
          e = f.call(arguments),
          g = r.Deferred(),
          h = function h(a) {
        return function (c) {
          d[a] = this, e[a] = arguments.length > 1 ? f.call(arguments) : c, --b || g.resolveWith(d, e);
        };
      };if (b <= 1 && (P(a, g.done(h(c)).resolve, g.reject, !b), "pending" === g.state() || r.isFunction(e[c] && e[c].then))) return g.then();while (c--) {
        P(e[c], h(c), g.reject);
      }return g.promise();
    } });var Q = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;r.Deferred.exceptionHook = function (b, c) {
    a.console && a.console.warn && b && Q.test(b.name) && a.console.warn("jQuery.Deferred exception: " + b.message, b.stack, c);
  }, r.readyException = function (b) {
    a.setTimeout(function () {
      throw b;
    });
  };var R = r.Deferred();r.fn.ready = function (a) {
    return R.then(a)["catch"](function (a) {
      r.readyException(a);
    }), this;
  }, r.extend({ isReady: !1, readyWait: 1, ready: function ready(a) {
      (a === !0 ? --r.readyWait : r.isReady) || (r.isReady = !0, a !== !0 && --r.readyWait > 0 || R.resolveWith(d, [r]));
    } }), r.ready.then = R.then;function S() {
    d.removeEventListener("DOMContentLoaded", S), a.removeEventListener("load", S), r.ready();
  }"complete" === d.readyState || "loading" !== d.readyState && !d.documentElement.doScroll ? a.setTimeout(r.ready) : (d.addEventListener("DOMContentLoaded", S), a.addEventListener("load", S));var T = function T(a, b, c, d, e, f, g) {
    var h = 0,
        i = a.length,
        j = null == c;if ("object" === r.type(c)) {
      e = !0;for (h in c) {
        T(a, b, h, c[h], !0, f, g);
      }
    } else if (void 0 !== d && (e = !0, r.isFunction(d) || (g = !0), j && (g ? (b.call(a, d), b = null) : (j = b, b = function b(a, _b, c) {
      return j.call(r(a), c);
    })), b)) for (; h < i; h++) {
      b(a[h], c, g ? d : d.call(a[h], h, b(a[h], c)));
    }return e ? a : j ? b.call(a) : i ? b(a[0], c) : f;
  },
      U = function U(a) {
    return 1 === a.nodeType || 9 === a.nodeType || !+a.nodeType;
  };function V() {
    this.expando = r.expando + V.uid++;
  }V.uid = 1, V.prototype = { cache: function cache(a) {
      var b = a[this.expando];return b || (b = {}, U(a) && (a.nodeType ? a[this.expando] = b : Object.defineProperty(a, this.expando, { value: b, configurable: !0 }))), b;
    }, set: function set(a, b, c) {
      var d,
          e = this.cache(a);if ("string" == typeof b) e[r.camelCase(b)] = c;else for (d in b) {
        e[r.camelCase(d)] = b[d];
      }return e;
    }, get: function get(a, b) {
      return void 0 === b ? this.cache(a) : a[this.expando] && a[this.expando][r.camelCase(b)];
    }, access: function access(a, b, c) {
      return void 0 === b || b && "string" == typeof b && void 0 === c ? this.get(a, b) : (this.set(a, b, c), void 0 !== c ? c : b);
    }, remove: function remove(a, b) {
      var c,
          d = a[this.expando];if (void 0 !== d) {
        if (void 0 !== b) {
          Array.isArray(b) ? b = b.map(r.camelCase) : (b = r.camelCase(b), b = b in d ? [b] : b.match(L) || []), c = b.length;while (c--) {
            delete d[b[c]];
          }
        }(void 0 === b || r.isEmptyObject(d)) && (a.nodeType ? a[this.expando] = void 0 : delete a[this.expando]);
      }
    }, hasData: function hasData(a) {
      var b = a[this.expando];return void 0 !== b && !r.isEmptyObject(b);
    } };var W = new V(),
      X = new V(),
      Y = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
      Z = /[A-Z]/g;function $(a) {
    return "true" === a || "false" !== a && ("null" === a ? null : a === +a + "" ? +a : Y.test(a) ? JSON.parse(a) : a);
  }function _(a, b, c) {
    var d;if (void 0 === c && 1 === a.nodeType) if (d = "data-" + b.replace(Z, "-$&").toLowerCase(), c = a.getAttribute(d), "string" == typeof c) {
      try {
        c = $(c);
      } catch (e) {}X.set(a, b, c);
    } else c = void 0;return c;
  }r.extend({ hasData: function hasData(a) {
      return X.hasData(a) || W.hasData(a);
    }, data: function data(a, b, c) {
      return X.access(a, b, c);
    }, removeData: function removeData(a, b) {
      X.remove(a, b);
    }, _data: function _data(a, b, c) {
      return W.access(a, b, c);
    }, _removeData: function _removeData(a, b) {
      W.remove(a, b);
    } }), r.fn.extend({ data: function data(a, b) {
      var c,
          d,
          e,
          f = this[0],
          g = f && f.attributes;if (void 0 === a) {
        if (this.length && (e = X.get(f), 1 === f.nodeType && !W.get(f, "hasDataAttrs"))) {
          c = g.length;while (c--) {
            g[c] && (d = g[c].name, 0 === d.indexOf("data-") && (d = r.camelCase(d.slice(5)), _(f, d, e[d])));
          }W.set(f, "hasDataAttrs", !0);
        }return e;
      }return "object" == (typeof a === "undefined" ? "undefined" : _typeof(a)) ? this.each(function () {
        X.set(this, a);
      }) : T(this, function (b) {
        var c;if (f && void 0 === b) {
          if (c = X.get(f, a), void 0 !== c) return c;if (c = _(f, a), void 0 !== c) return c;
        } else this.each(function () {
          X.set(this, a, b);
        });
      }, null, b, arguments.length > 1, null, !0);
    }, removeData: function removeData(a) {
      return this.each(function () {
        X.remove(this, a);
      });
    } }), r.extend({ queue: function queue(a, b, c) {
      var d;if (a) return b = (b || "fx") + "queue", d = W.get(a, b), c && (!d || Array.isArray(c) ? d = W.access(a, b, r.makeArray(c)) : d.push(c)), d || [];
    }, dequeue: function dequeue(a, b) {
      b = b || "fx";var c = r.queue(a, b),
          d = c.length,
          e = c.shift(),
          f = r._queueHooks(a, b),
          g = function g() {
        r.dequeue(a, b);
      };"inprogress" === e && (e = c.shift(), d--), e && ("fx" === b && c.unshift("inprogress"), delete f.stop, e.call(a, g, f)), !d && f && f.empty.fire();
    }, _queueHooks: function _queueHooks(a, b) {
      var c = b + "queueHooks";return W.get(a, c) || W.access(a, c, { empty: r.Callbacks("once memory").add(function () {
          W.remove(a, [b + "queue", c]);
        }) });
    } }), r.fn.extend({ queue: function queue(a, b) {
      var c = 2;return "string" != typeof a && (b = a, a = "fx", c--), arguments.length < c ? r.queue(this[0], a) : void 0 === b ? this : this.each(function () {
        var c = r.queue(this, a, b);r._queueHooks(this, a), "fx" === a && "inprogress" !== c[0] && r.dequeue(this, a);
      });
    }, dequeue: function dequeue(a) {
      return this.each(function () {
        r.dequeue(this, a);
      });
    }, clearQueue: function clearQueue(a) {
      return this.queue(a || "fx", []);
    }, promise: function promise(a, b) {
      var c,
          d = 1,
          e = r.Deferred(),
          f = this,
          g = this.length,
          h = function h() {
        --d || e.resolveWith(f, [f]);
      };"string" != typeof a && (b = a, a = void 0), a = a || "fx";while (g--) {
        c = W.get(f[g], a + "queueHooks"), c && c.empty && (d++, c.empty.add(h));
      }return h(), e.promise(b);
    } });var aa = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
      ba = new RegExp("^(?:([+-])=|)(" + aa + ")([a-z%]*)$", "i"),
      ca = ["Top", "Right", "Bottom", "Left"],
      da = function da(a, b) {
    return a = b || a, "none" === a.style.display || "" === a.style.display && r.contains(a.ownerDocument, a) && "none" === r.css(a, "display");
  },
      ea = function ea(a, b, c, d) {
    var e,
        f,
        g = {};for (f in b) {
      g[f] = a.style[f], a.style[f] = b[f];
    }e = c.apply(a, d || []);for (f in b) {
      a.style[f] = g[f];
    }return e;
  };function fa(a, b, c, d) {
    var e,
        f = 1,
        g = 20,
        h = d ? function () {
      return d.cur();
    } : function () {
      return r.css(a, b, "");
    },
        i = h(),
        j = c && c[3] || (r.cssNumber[b] ? "" : "px"),
        k = (r.cssNumber[b] || "px" !== j && +i) && ba.exec(r.css(a, b));if (k && k[3] !== j) {
      j = j || k[3], c = c || [], k = +i || 1;do {
        f = f || ".5", k /= f, r.style(a, b, k + j);
      } while (f !== (f = h() / i) && 1 !== f && --g);
    }return c && (k = +k || +i || 0, e = c[1] ? k + (c[1] + 1) * c[2] : +c[2], d && (d.unit = j, d.start = k, d.end = e)), e;
  }var ga = {};function ha(a) {
    var b,
        c = a.ownerDocument,
        d = a.nodeName,
        e = ga[d];return e ? e : (b = c.body.appendChild(c.createElement(d)), e = r.css(b, "display"), b.parentNode.removeChild(b), "none" === e && (e = "block"), ga[d] = e, e);
  }function ia(a, b) {
    for (var c, d, e = [], f = 0, g = a.length; f < g; f++) {
      d = a[f], d.style && (c = d.style.display, b ? ("none" === c && (e[f] = W.get(d, "display") || null, e[f] || (d.style.display = "")), "" === d.style.display && da(d) && (e[f] = ha(d))) : "none" !== c && (e[f] = "none", W.set(d, "display", c)));
    }for (f = 0; f < g; f++) {
      null != e[f] && (a[f].style.display = e[f]);
    }return a;
  }r.fn.extend({ show: function show() {
      return ia(this, !0);
    }, hide: function hide() {
      return ia(this);
    }, toggle: function toggle(a) {
      return "boolean" == typeof a ? a ? this.show() : this.hide() : this.each(function () {
        da(this) ? r(this).show() : r(this).hide();
      });
    } });var ja = /^(?:checkbox|radio)$/i,
      ka = /<([a-z][^\/\0>\x20\t\r\n\f]+)/i,
      la = /^$|\/(?:java|ecma)script/i,
      ma = { option: [1, "<select multiple='multiple'>", "</select>"], thead: [1, "<table>", "</table>"], col: [2, "<table><colgroup>", "</colgroup></table>"], tr: [2, "<table><tbody>", "</tbody></table>"], td: [3, "<table><tbody><tr>", "</tr></tbody></table>"], _default: [0, "", ""] };ma.optgroup = ma.option, ma.tbody = ma.tfoot = ma.colgroup = ma.caption = ma.thead, ma.th = ma.td;function na(a, b) {
    var c;return c = "undefined" != typeof a.getElementsByTagName ? a.getElementsByTagName(b || "*") : "undefined" != typeof a.querySelectorAll ? a.querySelectorAll(b || "*") : [], void 0 === b || b && B(a, b) ? r.merge([a], c) : c;
  }function oa(a, b) {
    for (var c = 0, d = a.length; c < d; c++) {
      W.set(a[c], "globalEval", !b || W.get(b[c], "globalEval"));
    }
  }var pa = /<|&#?\w+;/;function qa(a, b, c, d, e) {
    for (var f, g, h, i, j, k, l = b.createDocumentFragment(), m = [], n = 0, o = a.length; n < o; n++) {
      if (f = a[n], f || 0 === f) if ("object" === r.type(f)) r.merge(m, f.nodeType ? [f] : f);else if (pa.test(f)) {
        g = g || l.appendChild(b.createElement("div")), h = (ka.exec(f) || ["", ""])[1].toLowerCase(), i = ma[h] || ma._default, g.innerHTML = i[1] + r.htmlPrefilter(f) + i[2], k = i[0];while (k--) {
          g = g.lastChild;
        }r.merge(m, g.childNodes), g = l.firstChild, g.textContent = "";
      } else m.push(b.createTextNode(f));
    }l.textContent = "", n = 0;while (f = m[n++]) {
      if (d && r.inArray(f, d) > -1) e && e.push(f);else if (j = r.contains(f.ownerDocument, f), g = na(l.appendChild(f), "script"), j && oa(g), c) {
        k = 0;while (f = g[k++]) {
          la.test(f.type || "") && c.push(f);
        }
      }
    }return l;
  }!function () {
    var a = d.createDocumentFragment(),
        b = a.appendChild(d.createElement("div")),
        c = d.createElement("input");c.setAttribute("type", "radio"), c.setAttribute("checked", "checked"), c.setAttribute("name", "t"), b.appendChild(c), o.checkClone = b.cloneNode(!0).cloneNode(!0).lastChild.checked, b.innerHTML = "<textarea>x</textarea>", o.noCloneChecked = !!b.cloneNode(!0).lastChild.defaultValue;
  }();var ra = d.documentElement,
      sa = /^key/,
      ta = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
      ua = /^([^.]*)(?:\.(.+)|)/;function va() {
    return !0;
  }function wa() {
    return !1;
  }function xa() {
    try {
      return d.activeElement;
    } catch (a) {}
  }function ya(a, b, c, d, e, f) {
    var g, h;if ("object" == (typeof b === "undefined" ? "undefined" : _typeof(b))) {
      "string" != typeof c && (d = d || c, c = void 0);for (h in b) {
        ya(a, h, c, d, b[h], f);
      }return a;
    }if (null == d && null == e ? (e = c, d = c = void 0) : null == e && ("string" == typeof c ? (e = d, d = void 0) : (e = d, d = c, c = void 0)), e === !1) e = wa;else if (!e) return a;return 1 === f && (g = e, e = function e(a) {
      return r().off(a), g.apply(this, arguments);
    }, e.guid = g.guid || (g.guid = r.guid++)), a.each(function () {
      r.event.add(this, b, e, d, c);
    });
  }r.event = { global: {}, add: function add(a, b, c, d, e) {
      var f,
          g,
          h,
          i,
          j,
          k,
          l,
          m,
          n,
          o,
          p,
          q = W.get(a);if (q) {
        c.handler && (f = c, c = f.handler, e = f.selector), e && r.find.matchesSelector(ra, e), c.guid || (c.guid = r.guid++), (i = q.events) || (i = q.events = {}), (g = q.handle) || (g = q.handle = function (b) {
          return "undefined" != typeof r && r.event.triggered !== b.type ? r.event.dispatch.apply(a, arguments) : void 0;
        }), b = (b || "").match(L) || [""], j = b.length;while (j--) {
          h = ua.exec(b[j]) || [], n = p = h[1], o = (h[2] || "").split(".").sort(), n && (l = r.event.special[n] || {}, n = (e ? l.delegateType : l.bindType) || n, l = r.event.special[n] || {}, k = r.extend({ type: n, origType: p, data: d, handler: c, guid: c.guid, selector: e, needsContext: e && r.expr.match.needsContext.test(e), namespace: o.join(".") }, f), (m = i[n]) || (m = i[n] = [], m.delegateCount = 0, l.setup && l.setup.call(a, d, o, g) !== !1 || a.addEventListener && a.addEventListener(n, g)), l.add && (l.add.call(a, k), k.handler.guid || (k.handler.guid = c.guid)), e ? m.splice(m.delegateCount++, 0, k) : m.push(k), r.event.global[n] = !0);
        }
      }
    }, remove: function remove(a, b, c, d, e) {
      var f,
          g,
          h,
          i,
          j,
          k,
          l,
          m,
          n,
          o,
          p,
          q = W.hasData(a) && W.get(a);if (q && (i = q.events)) {
        b = (b || "").match(L) || [""], j = b.length;while (j--) {
          if (h = ua.exec(b[j]) || [], n = p = h[1], o = (h[2] || "").split(".").sort(), n) {
            l = r.event.special[n] || {}, n = (d ? l.delegateType : l.bindType) || n, m = i[n] || [], h = h[2] && new RegExp("(^|\\.)" + o.join("\\.(?:.*\\.|)") + "(\\.|$)"), g = f = m.length;while (f--) {
              k = m[f], !e && p !== k.origType || c && c.guid !== k.guid || h && !h.test(k.namespace) || d && d !== k.selector && ("**" !== d || !k.selector) || (m.splice(f, 1), k.selector && m.delegateCount--, l.remove && l.remove.call(a, k));
            }g && !m.length && (l.teardown && l.teardown.call(a, o, q.handle) !== !1 || r.removeEvent(a, n, q.handle), delete i[n]);
          } else for (n in i) {
            r.event.remove(a, n + b[j], c, d, !0);
          }
        }r.isEmptyObject(i) && W.remove(a, "handle events");
      }
    }, dispatch: function dispatch(a) {
      var b = r.event.fix(a),
          c,
          d,
          e,
          f,
          g,
          h,
          i = new Array(arguments.length),
          j = (W.get(this, "events") || {})[b.type] || [],
          k = r.event.special[b.type] || {};for (i[0] = b, c = 1; c < arguments.length; c++) {
        i[c] = arguments[c];
      }if (b.delegateTarget = this, !k.preDispatch || k.preDispatch.call(this, b) !== !1) {
        h = r.event.handlers.call(this, b, j), c = 0;while ((f = h[c++]) && !b.isPropagationStopped()) {
          b.currentTarget = f.elem, d = 0;while ((g = f.handlers[d++]) && !b.isImmediatePropagationStopped()) {
            b.rnamespace && !b.rnamespace.test(g.namespace) || (b.handleObj = g, b.data = g.data, e = ((r.event.special[g.origType] || {}).handle || g.handler).apply(f.elem, i), void 0 !== e && (b.result = e) === !1 && (b.preventDefault(), b.stopPropagation()));
          }
        }return k.postDispatch && k.postDispatch.call(this, b), b.result;
      }
    }, handlers: function handlers(a, b) {
      var c,
          d,
          e,
          f,
          g,
          h = [],
          i = b.delegateCount,
          j = a.target;if (i && j.nodeType && !("click" === a.type && a.button >= 1)) for (; j !== this; j = j.parentNode || this) {
        if (1 === j.nodeType && ("click" !== a.type || j.disabled !== !0)) {
          for (f = [], g = {}, c = 0; c < i; c++) {
            d = b[c], e = d.selector + " ", void 0 === g[e] && (g[e] = d.needsContext ? r(e, this).index(j) > -1 : r.find(e, this, null, [j]).length), g[e] && f.push(d);
          }f.length && h.push({ elem: j, handlers: f });
        }
      }return j = this, i < b.length && h.push({ elem: j, handlers: b.slice(i) }), h;
    }, addProp: function addProp(a, b) {
      Object.defineProperty(r.Event.prototype, a, { enumerable: !0, configurable: !0, get: r.isFunction(b) ? function () {
          if (this.originalEvent) return b(this.originalEvent);
        } : function () {
          if (this.originalEvent) return this.originalEvent[a];
        }, set: function set(b) {
          Object.defineProperty(this, a, { enumerable: !0, configurable: !0, writable: !0, value: b });
        } });
    }, fix: function fix(a) {
      return a[r.expando] ? a : new r.Event(a);
    }, special: { load: { noBubble: !0 }, focus: { trigger: function trigger() {
          if (this !== xa() && this.focus) return this.focus(), !1;
        }, delegateType: "focusin" }, blur: { trigger: function trigger() {
          if (this === xa() && this.blur) return this.blur(), !1;
        }, delegateType: "focusout" }, click: { trigger: function trigger() {
          if ("checkbox" === this.type && this.click && B(this, "input")) return this.click(), !1;
        }, _default: function _default(a) {
          return B(a.target, "a");
        } }, beforeunload: { postDispatch: function postDispatch(a) {
          void 0 !== a.result && a.originalEvent && (a.originalEvent.returnValue = a.result);
        } } } }, r.removeEvent = function (a, b, c) {
    a.removeEventListener && a.removeEventListener(b, c);
  }, r.Event = function (a, b) {
    return this instanceof r.Event ? (a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || void 0 === a.defaultPrevented && a.returnValue === !1 ? va : wa, this.target = a.target && 3 === a.target.nodeType ? a.target.parentNode : a.target, this.currentTarget = a.currentTarget, this.relatedTarget = a.relatedTarget) : this.type = a, b && r.extend(this, b), this.timeStamp = a && a.timeStamp || r.now(), void (this[r.expando] = !0)) : new r.Event(a, b);
  }, r.Event.prototype = { constructor: r.Event, isDefaultPrevented: wa, isPropagationStopped: wa, isImmediatePropagationStopped: wa, isSimulated: !1, preventDefault: function preventDefault() {
      var a = this.originalEvent;this.isDefaultPrevented = va, a && !this.isSimulated && a.preventDefault();
    }, stopPropagation: function stopPropagation() {
      var a = this.originalEvent;this.isPropagationStopped = va, a && !this.isSimulated && a.stopPropagation();
    }, stopImmediatePropagation: function stopImmediatePropagation() {
      var a = this.originalEvent;this.isImmediatePropagationStopped = va, a && !this.isSimulated && a.stopImmediatePropagation(), this.stopPropagation();
    } }, r.each({ altKey: !0, bubbles: !0, cancelable: !0, changedTouches: !0, ctrlKey: !0, detail: !0, eventPhase: !0, metaKey: !0, pageX: !0, pageY: !0, shiftKey: !0, view: !0, "char": !0, charCode: !0, key: !0, keyCode: !0, button: !0, buttons: !0, clientX: !0, clientY: !0, offsetX: !0, offsetY: !0, pointerId: !0, pointerType: !0, screenX: !0, screenY: !0, targetTouches: !0, toElement: !0, touches: !0, which: function which(a) {
      var b = a.button;return null == a.which && sa.test(a.type) ? null != a.charCode ? a.charCode : a.keyCode : !a.which && void 0 !== b && ta.test(a.type) ? 1 & b ? 1 : 2 & b ? 3 : 4 & b ? 2 : 0 : a.which;
    } }, r.event.addProp), r.each({ mouseenter: "mouseover", mouseleave: "mouseout", pointerenter: "pointerover", pointerleave: "pointerout" }, function (a, b) {
    r.event.special[a] = { delegateType: b, bindType: b, handle: function handle(a) {
        var c,
            d = this,
            e = a.relatedTarget,
            f = a.handleObj;return e && (e === d || r.contains(d, e)) || (a.type = f.origType, c = f.handler.apply(this, arguments), a.type = b), c;
      } };
  }), r.fn.extend({ on: function on(a, b, c, d) {
      return ya(this, a, b, c, d);
    }, one: function one(a, b, c, d) {
      return ya(this, a, b, c, d, 1);
    }, off: function off(a, b, c) {
      var d, e;if (a && a.preventDefault && a.handleObj) return d = a.handleObj, r(a.delegateTarget).off(d.namespace ? d.origType + "." + d.namespace : d.origType, d.selector, d.handler), this;if ("object" == (typeof a === "undefined" ? "undefined" : _typeof(a))) {
        for (e in a) {
          this.off(e, b, a[e]);
        }return this;
      }return b !== !1 && "function" != typeof b || (c = b, b = void 0), c === !1 && (c = wa), this.each(function () {
        r.event.remove(this, a, c, b);
      });
    } });var za = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,
      Aa = /<script|<style|<link/i,
      Ba = /checked\s*(?:[^=]|=\s*.checked.)/i,
      Ca = /^true\/(.*)/,
      Da = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function Ea(a, b) {
    return B(a, "table") && B(11 !== b.nodeType ? b : b.firstChild, "tr") ? r(">tbody", a)[0] || a : a;
  }function Fa(a) {
    return a.type = (null !== a.getAttribute("type")) + "/" + a.type, a;
  }function Ga(a) {
    var b = Ca.exec(a.type);return b ? a.type = b[1] : a.removeAttribute("type"), a;
  }function Ha(a, b) {
    var c, d, e, f, g, h, i, j;if (1 === b.nodeType) {
      if (W.hasData(a) && (f = W.access(a), g = W.set(b, f), j = f.events)) {
        delete g.handle, g.events = {};for (e in j) {
          for (c = 0, d = j[e].length; c < d; c++) {
            r.event.add(b, e, j[e][c]);
          }
        }
      }X.hasData(a) && (h = X.access(a), i = r.extend({}, h), X.set(b, i));
    }
  }function Ia(a, b) {
    var c = b.nodeName.toLowerCase();"input" === c && ja.test(a.type) ? b.checked = a.checked : "input" !== c && "textarea" !== c || (b.defaultValue = a.defaultValue);
  }function Ja(a, b, c, d) {
    b = g.apply([], b);var e,
        f,
        h,
        i,
        j,
        k,
        l = 0,
        m = a.length,
        n = m - 1,
        q = b[0],
        s = r.isFunction(q);if (s || m > 1 && "string" == typeof q && !o.checkClone && Ba.test(q)) return a.each(function (e) {
      var f = a.eq(e);s && (b[0] = q.call(this, e, f.html())), Ja(f, b, c, d);
    });if (m && (e = qa(b, a[0].ownerDocument, !1, a, d), f = e.firstChild, 1 === e.childNodes.length && (e = f), f || d)) {
      for (h = r.map(na(e, "script"), Fa), i = h.length; l < m; l++) {
        j = e, l !== n && (j = r.clone(j, !0, !0), i && r.merge(h, na(j, "script"))), c.call(a[l], j, l);
      }if (i) for (k = h[h.length - 1].ownerDocument, r.map(h, Ga), l = 0; l < i; l++) {
        j = h[l], la.test(j.type || "") && !W.access(j, "globalEval") && r.contains(k, j) && (j.src ? r._evalUrl && r._evalUrl(j.src) : p(j.textContent.replace(Da, ""), k));
      }
    }return a;
  }function Ka(a, b, c) {
    for (var d, e = b ? r.filter(b, a) : a, f = 0; null != (d = e[f]); f++) {
      c || 1 !== d.nodeType || r.cleanData(na(d)), d.parentNode && (c && r.contains(d.ownerDocument, d) && oa(na(d, "script")), d.parentNode.removeChild(d));
    }return a;
  }r.extend({ htmlPrefilter: function htmlPrefilter(a) {
      return a.replace(za, "<$1></$2>");
    }, clone: function clone(a, b, c) {
      var d,
          e,
          f,
          g,
          h = a.cloneNode(!0),
          i = r.contains(a.ownerDocument, a);if (!(o.noCloneChecked || 1 !== a.nodeType && 11 !== a.nodeType || r.isXMLDoc(a))) for (g = na(h), f = na(a), d = 0, e = f.length; d < e; d++) {
        Ia(f[d], g[d]);
      }if (b) if (c) for (f = f || na(a), g = g || na(h), d = 0, e = f.length; d < e; d++) {
        Ha(f[d], g[d]);
      } else Ha(a, h);return g = na(h, "script"), g.length > 0 && oa(g, !i && na(a, "script")), h;
    }, cleanData: function cleanData(a) {
      for (var b, c, d, e = r.event.special, f = 0; void 0 !== (c = a[f]); f++) {
        if (U(c)) {
          if (b = c[W.expando]) {
            if (b.events) for (d in b.events) {
              e[d] ? r.event.remove(c, d) : r.removeEvent(c, d, b.handle);
            }c[W.expando] = void 0;
          }c[X.expando] && (c[X.expando] = void 0);
        }
      }
    } }), r.fn.extend({ detach: function detach(a) {
      return Ka(this, a, !0);
    }, remove: function remove(a) {
      return Ka(this, a);
    }, text: function text(a) {
      return T(this, function (a) {
        return void 0 === a ? r.text(this) : this.empty().each(function () {
          1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = a);
        });
      }, null, a, arguments.length);
    }, append: function append() {
      return Ja(this, arguments, function (a) {
        if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
          var b = Ea(this, a);b.appendChild(a);
        }
      });
    }, prepend: function prepend() {
      return Ja(this, arguments, function (a) {
        if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
          var b = Ea(this, a);b.insertBefore(a, b.firstChild);
        }
      });
    }, before: function before() {
      return Ja(this, arguments, function (a) {
        this.parentNode && this.parentNode.insertBefore(a, this);
      });
    }, after: function after() {
      return Ja(this, arguments, function (a) {
        this.parentNode && this.parentNode.insertBefore(a, this.nextSibling);
      });
    }, empty: function empty() {
      for (var a, b = 0; null != (a = this[b]); b++) {
        1 === a.nodeType && (r.cleanData(na(a, !1)), a.textContent = "");
      }return this;
    }, clone: function clone(a, b) {
      return a = null != a && a, b = null == b ? a : b, this.map(function () {
        return r.clone(this, a, b);
      });
    }, html: function html(a) {
      return T(this, function (a) {
        var b = this[0] || {},
            c = 0,
            d = this.length;if (void 0 === a && 1 === b.nodeType) return b.innerHTML;if ("string" == typeof a && !Aa.test(a) && !ma[(ka.exec(a) || ["", ""])[1].toLowerCase()]) {
          a = r.htmlPrefilter(a);try {
            for (; c < d; c++) {
              b = this[c] || {}, 1 === b.nodeType && (r.cleanData(na(b, !1)), b.innerHTML = a);
            }b = 0;
          } catch (e) {}
        }b && this.empty().append(a);
      }, null, a, arguments.length);
    }, replaceWith: function replaceWith() {
      var a = [];return Ja(this, arguments, function (b) {
        var c = this.parentNode;r.inArray(this, a) < 0 && (r.cleanData(na(this)), c && c.replaceChild(b, this));
      }, a);
    } }), r.each({ appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith" }, function (a, b) {
    r.fn[a] = function (a) {
      for (var c, d = [], e = r(a), f = e.length - 1, g = 0; g <= f; g++) {
        c = g === f ? this : this.clone(!0), r(e[g])[b](c), h.apply(d, c.get());
      }return this.pushStack(d);
    };
  });var La = /^margin/,
      Ma = new RegExp("^(" + aa + ")(?!px)[a-z%]+$", "i"),
      Na = function Na(b) {
    var c = b.ownerDocument.defaultView;return c && c.opener || (c = a), c.getComputedStyle(b);
  };!function () {
    function b() {
      if (i) {
        i.style.cssText = "box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%", i.innerHTML = "", ra.appendChild(h);var b = a.getComputedStyle(i);c = "1%" !== b.top, g = "2px" === b.marginLeft, e = "4px" === b.width, i.style.marginRight = "50%", f = "4px" === b.marginRight, ra.removeChild(h), i = null;
      }
    }var c,
        e,
        f,
        g,
        h = d.createElement("div"),
        i = d.createElement("div");i.style && (i.style.backgroundClip = "content-box", i.cloneNode(!0).style.backgroundClip = "", o.clearCloneStyle = "content-box" === i.style.backgroundClip, h.style.cssText = "border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute", h.appendChild(i), r.extend(o, { pixelPosition: function pixelPosition() {
        return b(), c;
      }, boxSizingReliable: function boxSizingReliable() {
        return b(), e;
      }, pixelMarginRight: function pixelMarginRight() {
        return b(), f;
      }, reliableMarginLeft: function reliableMarginLeft() {
        return b(), g;
      } }));
  }();function Oa(a, b, c) {
    var d,
        e,
        f,
        g,
        h = a.style;return c = c || Na(a), c && (g = c.getPropertyValue(b) || c[b], "" !== g || r.contains(a.ownerDocument, a) || (g = r.style(a, b)), !o.pixelMarginRight() && Ma.test(g) && La.test(b) && (d = h.width, e = h.minWidth, f = h.maxWidth, h.minWidth = h.maxWidth = h.width = g, g = c.width, h.width = d, h.minWidth = e, h.maxWidth = f)), void 0 !== g ? g + "" : g;
  }function Pa(a, b) {
    return { get: function get() {
        return a() ? void delete this.get : (this.get = b).apply(this, arguments);
      } };
  }var Qa = /^(none|table(?!-c[ea]).+)/,
      Ra = /^--/,
      Sa = { position: "absolute", visibility: "hidden", display: "block" },
      Ta = { letterSpacing: "0", fontWeight: "400" },
      Ua = ["Webkit", "Moz", "ms"],
      Va = d.createElement("div").style;function Wa(a) {
    if (a in Va) return a;var b = a[0].toUpperCase() + a.slice(1),
        c = Ua.length;while (c--) {
      if (a = Ua[c] + b, a in Va) return a;
    }
  }function Xa(a) {
    var b = r.cssProps[a];return b || (b = r.cssProps[a] = Wa(a) || a), b;
  }function Ya(a, b, c) {
    var d = ba.exec(b);return d ? Math.max(0, d[2] - (c || 0)) + (d[3] || "px") : b;
  }function Za(a, b, c, d, e) {
    var f,
        g = 0;for (f = c === (d ? "border" : "content") ? 4 : "width" === b ? 1 : 0; f < 4; f += 2) {
      "margin" === c && (g += r.css(a, c + ca[f], !0, e)), d ? ("content" === c && (g -= r.css(a, "padding" + ca[f], !0, e)), "margin" !== c && (g -= r.css(a, "border" + ca[f] + "Width", !0, e))) : (g += r.css(a, "padding" + ca[f], !0, e), "padding" !== c && (g += r.css(a, "border" + ca[f] + "Width", !0, e)));
    }return g;
  }function $a(a, b, c) {
    var d,
        e = Na(a),
        f = Oa(a, b, e),
        g = "border-box" === r.css(a, "boxSizing", !1, e);return Ma.test(f) ? f : (d = g && (o.boxSizingReliable() || f === a.style[b]), "auto" === f && (f = a["offset" + b[0].toUpperCase() + b.slice(1)]), f = parseFloat(f) || 0, f + Za(a, b, c || (g ? "border" : "content"), d, e) + "px");
  }r.extend({ cssHooks: { opacity: { get: function get(a, b) {
          if (b) {
            var c = Oa(a, "opacity");return "" === c ? "1" : c;
          }
        } } }, cssNumber: { animationIterationCount: !0, columnCount: !0, fillOpacity: !0, flexGrow: !0, flexShrink: !0, fontWeight: !0, lineHeight: !0, opacity: !0, order: !0, orphans: !0, widows: !0, zIndex: !0, zoom: !0 }, cssProps: { "float": "cssFloat" }, style: function style(a, b, c, d) {
      if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style) {
        var e,
            f,
            g,
            h = r.camelCase(b),
            i = Ra.test(b),
            j = a.style;return i || (b = Xa(h)), g = r.cssHooks[b] || r.cssHooks[h], void 0 === c ? g && "get" in g && void 0 !== (e = g.get(a, !1, d)) ? e : j[b] : (f = typeof c === "undefined" ? "undefined" : _typeof(c), "string" === f && (e = ba.exec(c)) && e[1] && (c = fa(a, b, e), f = "number"), null != c && c === c && ("number" === f && (c += e && e[3] || (r.cssNumber[h] ? "" : "px")), o.clearCloneStyle || "" !== c || 0 !== b.indexOf("background") || (j[b] = "inherit"), g && "set" in g && void 0 === (c = g.set(a, c, d)) || (i ? j.setProperty(b, c) : j[b] = c)), void 0);
      }
    }, css: function css(a, b, c, d) {
      var e,
          f,
          g,
          h = r.camelCase(b),
          i = Ra.test(b);return i || (b = Xa(h)), g = r.cssHooks[b] || r.cssHooks[h], g && "get" in g && (e = g.get(a, !0, c)), void 0 === e && (e = Oa(a, b, d)), "normal" === e && b in Ta && (e = Ta[b]), "" === c || c ? (f = parseFloat(e), c === !0 || isFinite(f) ? f || 0 : e) : e;
    } }), r.each(["height", "width"], function (a, b) {
    r.cssHooks[b] = { get: function get(a, c, d) {
        if (c) return !Qa.test(r.css(a, "display")) || a.getClientRects().length && a.getBoundingClientRect().width ? $a(a, b, d) : ea(a, Sa, function () {
          return $a(a, b, d);
        });
      }, set: function set(a, c, d) {
        var e,
            f = d && Na(a),
            g = d && Za(a, b, d, "border-box" === r.css(a, "boxSizing", !1, f), f);return g && (e = ba.exec(c)) && "px" !== (e[3] || "px") && (a.style[b] = c, c = r.css(a, b)), Ya(a, c, g);
      } };
  }), r.cssHooks.marginLeft = Pa(o.reliableMarginLeft, function (a, b) {
    if (b) return (parseFloat(Oa(a, "marginLeft")) || a.getBoundingClientRect().left - ea(a, { marginLeft: 0 }, function () {
      return a.getBoundingClientRect().left;
    })) + "px";
  }), r.each({ margin: "", padding: "", border: "Width" }, function (a, b) {
    r.cssHooks[a + b] = { expand: function expand(c) {
        for (var d = 0, e = {}, f = "string" == typeof c ? c.split(" ") : [c]; d < 4; d++) {
          e[a + ca[d] + b] = f[d] || f[d - 2] || f[0];
        }return e;
      } }, La.test(a) || (r.cssHooks[a + b].set = Ya);
  }), r.fn.extend({ css: function css(a, b) {
      return T(this, function (a, b, c) {
        var d,
            e,
            f = {},
            g = 0;if (Array.isArray(b)) {
          for (d = Na(a), e = b.length; g < e; g++) {
            f[b[g]] = r.css(a, b[g], !1, d);
          }return f;
        }return void 0 !== c ? r.style(a, b, c) : r.css(a, b);
      }, a, b, arguments.length > 1);
    } });function _a(a, b, c, d, e) {
    return new _a.prototype.init(a, b, c, d, e);
  }r.Tween = _a, _a.prototype = { constructor: _a, init: function init(a, b, c, d, e, f) {
      this.elem = a, this.prop = c, this.easing = e || r.easing._default, this.options = b, this.start = this.now = this.cur(), this.end = d, this.unit = f || (r.cssNumber[c] ? "" : "px");
    }, cur: function cur() {
      var a = _a.propHooks[this.prop];return a && a.get ? a.get(this) : _a.propHooks._default.get(this);
    }, run: function run(a) {
      var b,
          c = _a.propHooks[this.prop];return this.options.duration ? this.pos = b = r.easing[this.easing](a, this.options.duration * a, 0, 1, this.options.duration) : this.pos = b = a, this.now = (this.end - this.start) * b + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), c && c.set ? c.set(this) : _a.propHooks._default.set(this), this;
    } }, _a.prototype.init.prototype = _a.prototype, _a.propHooks = { _default: { get: function get(a) {
        var b;return 1 !== a.elem.nodeType || null != a.elem[a.prop] && null == a.elem.style[a.prop] ? a.elem[a.prop] : (b = r.css(a.elem, a.prop, ""), b && "auto" !== b ? b : 0);
      }, set: function set(a) {
        r.fx.step[a.prop] ? r.fx.step[a.prop](a) : 1 !== a.elem.nodeType || null == a.elem.style[r.cssProps[a.prop]] && !r.cssHooks[a.prop] ? a.elem[a.prop] = a.now : r.style(a.elem, a.prop, a.now + a.unit);
      } } }, _a.propHooks.scrollTop = _a.propHooks.scrollLeft = { set: function set(a) {
      a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now);
    } }, r.easing = { linear: function linear(a) {
      return a;
    }, swing: function swing(a) {
      return .5 - Math.cos(a * Math.PI) / 2;
    }, _default: "swing" }, r.fx = _a.prototype.init, r.fx.step = {};var ab,
      bb,
      cb = /^(?:toggle|show|hide)$/,
      db = /queueHooks$/;function eb() {
    bb && (d.hidden === !1 && a.requestAnimationFrame ? a.requestAnimationFrame(eb) : a.setTimeout(eb, r.fx.interval), r.fx.tick());
  }function fb() {
    return a.setTimeout(function () {
      ab = void 0;
    }), ab = r.now();
  }function gb(a, b) {
    var c,
        d = 0,
        e = { height: a };for (b = b ? 1 : 0; d < 4; d += 2 - b) {
      c = ca[d], e["margin" + c] = e["padding" + c] = a;
    }return b && (e.opacity = e.width = a), e;
  }function hb(a, b, c) {
    for (var d, e = (kb.tweeners[b] || []).concat(kb.tweeners["*"]), f = 0, g = e.length; f < g; f++) {
      if (d = e[f].call(c, b, a)) return d;
    }
  }function ib(a, b, c) {
    var d,
        e,
        f,
        g,
        h,
        i,
        j,
        k,
        l = "width" in b || "height" in b,
        m = this,
        n = {},
        o = a.style,
        p = a.nodeType && da(a),
        q = W.get(a, "fxshow");c.queue || (g = r._queueHooks(a, "fx"), null == g.unqueued && (g.unqueued = 0, h = g.empty.fire, g.empty.fire = function () {
      g.unqueued || h();
    }), g.unqueued++, m.always(function () {
      m.always(function () {
        g.unqueued--, r.queue(a, "fx").length || g.empty.fire();
      });
    }));for (d in b) {
      if (e = b[d], cb.test(e)) {
        if (delete b[d], f = f || "toggle" === e, e === (p ? "hide" : "show")) {
          if ("show" !== e || !q || void 0 === q[d]) continue;p = !0;
        }n[d] = q && q[d] || r.style(a, d);
      }
    }if (i = !r.isEmptyObject(b), i || !r.isEmptyObject(n)) {
      l && 1 === a.nodeType && (c.overflow = [o.overflow, o.overflowX, o.overflowY], j = q && q.display, null == j && (j = W.get(a, "display")), k = r.css(a, "display"), "none" === k && (j ? k = j : (ia([a], !0), j = a.style.display || j, k = r.css(a, "display"), ia([a]))), ("inline" === k || "inline-block" === k && null != j) && "none" === r.css(a, "float") && (i || (m.done(function () {
        o.display = j;
      }), null == j && (k = o.display, j = "none" === k ? "" : k)), o.display = "inline-block")), c.overflow && (o.overflow = "hidden", m.always(function () {
        o.overflow = c.overflow[0], o.overflowX = c.overflow[1], o.overflowY = c.overflow[2];
      })), i = !1;for (d in n) {
        i || (q ? "hidden" in q && (p = q.hidden) : q = W.access(a, "fxshow", { display: j }), f && (q.hidden = !p), p && ia([a], !0), m.done(function () {
          p || ia([a]), W.remove(a, "fxshow");for (d in n) {
            r.style(a, d, n[d]);
          }
        })), i = hb(p ? q[d] : 0, d, m), d in q || (q[d] = i.start, p && (i.end = i.start, i.start = 0));
      }
    }
  }function jb(a, b) {
    var c, d, e, f, g;for (c in a) {
      if (d = r.camelCase(c), e = b[d], f = a[c], Array.isArray(f) && (e = f[1], f = a[c] = f[0]), c !== d && (a[d] = f, delete a[c]), g = r.cssHooks[d], g && "expand" in g) {
        f = g.expand(f), delete a[d];for (c in f) {
          c in a || (a[c] = f[c], b[c] = e);
        }
      } else b[d] = e;
    }
  }function kb(a, b, c) {
    var d,
        e,
        f = 0,
        g = kb.prefilters.length,
        h = r.Deferred().always(function () {
      delete i.elem;
    }),
        i = function i() {
      if (e) return !1;for (var b = ab || fb(), c = Math.max(0, j.startTime + j.duration - b), d = c / j.duration || 0, f = 1 - d, g = 0, i = j.tweens.length; g < i; g++) {
        j.tweens[g].run(f);
      }return h.notifyWith(a, [j, f, c]), f < 1 && i ? c : (i || h.notifyWith(a, [j, 1, 0]), h.resolveWith(a, [j]), !1);
    },
        j = h.promise({ elem: a, props: r.extend({}, b), opts: r.extend(!0, { specialEasing: {}, easing: r.easing._default }, c), originalProperties: b, originalOptions: c, startTime: ab || fb(), duration: c.duration, tweens: [], createTween: function createTween(b, c) {
        var d = r.Tween(a, j.opts, b, c, j.opts.specialEasing[b] || j.opts.easing);return j.tweens.push(d), d;
      }, stop: function stop(b) {
        var c = 0,
            d = b ? j.tweens.length : 0;if (e) return this;for (e = !0; c < d; c++) {
          j.tweens[c].run(1);
        }return b ? (h.notifyWith(a, [j, 1, 0]), h.resolveWith(a, [j, b])) : h.rejectWith(a, [j, b]), this;
      } }),
        k = j.props;for (jb(k, j.opts.specialEasing); f < g; f++) {
      if (d = kb.prefilters[f].call(j, a, k, j.opts)) return r.isFunction(d.stop) && (r._queueHooks(j.elem, j.opts.queue).stop = r.proxy(d.stop, d)), d;
    }return r.map(k, hb, j), r.isFunction(j.opts.start) && j.opts.start.call(a, j), j.progress(j.opts.progress).done(j.opts.done, j.opts.complete).fail(j.opts.fail).always(j.opts.always), r.fx.timer(r.extend(i, { elem: a, anim: j, queue: j.opts.queue })), j;
  }r.Animation = r.extend(kb, { tweeners: { "*": [function (a, b) {
        var c = this.createTween(a, b);return fa(c.elem, a, ba.exec(b), c), c;
      }] }, tweener: function tweener(a, b) {
      r.isFunction(a) ? (b = a, a = ["*"]) : a = a.match(L);for (var c, d = 0, e = a.length; d < e; d++) {
        c = a[d], kb.tweeners[c] = kb.tweeners[c] || [], kb.tweeners[c].unshift(b);
      }
    }, prefilters: [ib], prefilter: function prefilter(a, b) {
      b ? kb.prefilters.unshift(a) : kb.prefilters.push(a);
    } }), r.speed = function (a, b, c) {
    var d = a && "object" == (typeof a === "undefined" ? "undefined" : _typeof(a)) ? r.extend({}, a) : { complete: c || !c && b || r.isFunction(a) && a, duration: a, easing: c && b || b && !r.isFunction(b) && b };return r.fx.off ? d.duration = 0 : "number" != typeof d.duration && (d.duration in r.fx.speeds ? d.duration = r.fx.speeds[d.duration] : d.duration = r.fx.speeds._default), null != d.queue && d.queue !== !0 || (d.queue = "fx"), d.old = d.complete, d.complete = function () {
      r.isFunction(d.old) && d.old.call(this), d.queue && r.dequeue(this, d.queue);
    }, d;
  }, r.fn.extend({ fadeTo: function fadeTo(a, b, c, d) {
      return this.filter(da).css("opacity", 0).show().end().animate({ opacity: b }, a, c, d);
    }, animate: function animate(a, b, c, d) {
      var e = r.isEmptyObject(a),
          f = r.speed(b, c, d),
          g = function g() {
        var b = kb(this, r.extend({}, a), f);(e || W.get(this, "finish")) && b.stop(!0);
      };return g.finish = g, e || f.queue === !1 ? this.each(g) : this.queue(f.queue, g);
    }, stop: function stop(a, b, c) {
      var d = function d(a) {
        var b = a.stop;delete a.stop, b(c);
      };return "string" != typeof a && (c = b, b = a, a = void 0), b && a !== !1 && this.queue(a || "fx", []), this.each(function () {
        var b = !0,
            e = null != a && a + "queueHooks",
            f = r.timers,
            g = W.get(this);if (e) g[e] && g[e].stop && d(g[e]);else for (e in g) {
          g[e] && g[e].stop && db.test(e) && d(g[e]);
        }for (e = f.length; e--;) {
          f[e].elem !== this || null != a && f[e].queue !== a || (f[e].anim.stop(c), b = !1, f.splice(e, 1));
        }!b && c || r.dequeue(this, a);
      });
    }, finish: function finish(a) {
      return a !== !1 && (a = a || "fx"), this.each(function () {
        var b,
            c = W.get(this),
            d = c[a + "queue"],
            e = c[a + "queueHooks"],
            f = r.timers,
            g = d ? d.length : 0;for (c.finish = !0, r.queue(this, a, []), e && e.stop && e.stop.call(this, !0), b = f.length; b--;) {
          f[b].elem === this && f[b].queue === a && (f[b].anim.stop(!0), f.splice(b, 1));
        }for (b = 0; b < g; b++) {
          d[b] && d[b].finish && d[b].finish.call(this);
        }delete c.finish;
      });
    } }), r.each(["toggle", "show", "hide"], function (a, b) {
    var c = r.fn[b];r.fn[b] = function (a, d, e) {
      return null == a || "boolean" == typeof a ? c.apply(this, arguments) : this.animate(gb(b, !0), a, d, e);
    };
  }), r.each({ slideDown: gb("show"), slideUp: gb("hide"), slideToggle: gb("toggle"), fadeIn: { opacity: "show" }, fadeOut: { opacity: "hide" }, fadeToggle: { opacity: "toggle" } }, function (a, b) {
    r.fn[a] = function (a, c, d) {
      return this.animate(b, a, c, d);
    };
  }), r.timers = [], r.fx.tick = function () {
    var a,
        b = 0,
        c = r.timers;for (ab = r.now(); b < c.length; b++) {
      a = c[b], a() || c[b] !== a || c.splice(b--, 1);
    }c.length || r.fx.stop(), ab = void 0;
  }, r.fx.timer = function (a) {
    r.timers.push(a), r.fx.start();
  }, r.fx.interval = 13, r.fx.start = function () {
    bb || (bb = !0, eb());
  }, r.fx.stop = function () {
    bb = null;
  }, r.fx.speeds = { slow: 600, fast: 200, _default: 400 }, r.fn.delay = function (b, c) {
    return b = r.fx ? r.fx.speeds[b] || b : b, c = c || "fx", this.queue(c, function (c, d) {
      var e = a.setTimeout(c, b);d.stop = function () {
        a.clearTimeout(e);
      };
    });
  }, function () {
    var a = d.createElement("input"),
        b = d.createElement("select"),
        c = b.appendChild(d.createElement("option"));a.type = "checkbox", o.checkOn = "" !== a.value, o.optSelected = c.selected, a = d.createElement("input"), a.value = "t", a.type = "radio", o.radioValue = "t" === a.value;
  }();var lb,
      mb = r.expr.attrHandle;r.fn.extend({ attr: function attr(a, b) {
      return T(this, r.attr, a, b, arguments.length > 1);
    }, removeAttr: function removeAttr(a) {
      return this.each(function () {
        r.removeAttr(this, a);
      });
    } }), r.extend({ attr: function attr(a, b, c) {
      var d,
          e,
          f = a.nodeType;if (3 !== f && 8 !== f && 2 !== f) return "undefined" == typeof a.getAttribute ? r.prop(a, b, c) : (1 === f && r.isXMLDoc(a) || (e = r.attrHooks[b.toLowerCase()] || (r.expr.match.bool.test(b) ? lb : void 0)), void 0 !== c ? null === c ? void r.removeAttr(a, b) : e && "set" in e && void 0 !== (d = e.set(a, c, b)) ? d : (a.setAttribute(b, c + ""), c) : e && "get" in e && null !== (d = e.get(a, b)) ? d : (d = r.find.attr(a, b), null == d ? void 0 : d));
    }, attrHooks: { type: { set: function set(a, b) {
          if (!o.radioValue && "radio" === b && B(a, "input")) {
            var c = a.value;return a.setAttribute("type", b), c && (a.value = c), b;
          }
        } } }, removeAttr: function removeAttr(a, b) {
      var c,
          d = 0,
          e = b && b.match(L);if (e && 1 === a.nodeType) while (c = e[d++]) {
        a.removeAttribute(c);
      }
    } }), lb = { set: function set(a, b, c) {
      return b === !1 ? r.removeAttr(a, c) : a.setAttribute(c, c), c;
    } }, r.each(r.expr.match.bool.source.match(/\w+/g), function (a, b) {
    var c = mb[b] || r.find.attr;mb[b] = function (a, b, d) {
      var e,
          f,
          g = b.toLowerCase();return d || (f = mb[g], mb[g] = e, e = null != c(a, b, d) ? g : null, mb[g] = f), e;
    };
  });var nb = /^(?:input|select|textarea|button)$/i,
      ob = /^(?:a|area)$/i;r.fn.extend({ prop: function prop(a, b) {
      return T(this, r.prop, a, b, arguments.length > 1);
    }, removeProp: function removeProp(a) {
      return this.each(function () {
        delete this[r.propFix[a] || a];
      });
    } }), r.extend({ prop: function prop(a, b, c) {
      var d,
          e,
          f = a.nodeType;if (3 !== f && 8 !== f && 2 !== f) return 1 === f && r.isXMLDoc(a) || (b = r.propFix[b] || b, e = r.propHooks[b]), void 0 !== c ? e && "set" in e && void 0 !== (d = e.set(a, c, b)) ? d : a[b] = c : e && "get" in e && null !== (d = e.get(a, b)) ? d : a[b];
    }, propHooks: { tabIndex: { get: function get(a) {
          var b = r.find.attr(a, "tabindex");return b ? parseInt(b, 10) : nb.test(a.nodeName) || ob.test(a.nodeName) && a.href ? 0 : -1;
        } } }, propFix: { "for": "htmlFor", "class": "className" } }), o.optSelected || (r.propHooks.selected = { get: function get(a) {
      var b = a.parentNode;return b && b.parentNode && b.parentNode.selectedIndex, null;
    }, set: function set(a) {
      var b = a.parentNode;b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex);
    } }), r.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
    r.propFix[this.toLowerCase()] = this;
  });function pb(a) {
    var b = a.match(L) || [];return b.join(" ");
  }function qb(a) {
    return a.getAttribute && a.getAttribute("class") || "";
  }r.fn.extend({ addClass: function addClass(a) {
      var b,
          c,
          d,
          e,
          f,
          g,
          h,
          i = 0;if (r.isFunction(a)) return this.each(function (b) {
        r(this).addClass(a.call(this, b, qb(this)));
      });if ("string" == typeof a && a) {
        b = a.match(L) || [];while (c = this[i++]) {
          if (e = qb(c), d = 1 === c.nodeType && " " + pb(e) + " ") {
            g = 0;while (f = b[g++]) {
              d.indexOf(" " + f + " ") < 0 && (d += f + " ");
            }h = pb(d), e !== h && c.setAttribute("class", h);
          }
        }
      }return this;
    }, removeClass: function removeClass(a) {
      var b,
          c,
          d,
          e,
          f,
          g,
          h,
          i = 0;if (r.isFunction(a)) return this.each(function (b) {
        r(this).removeClass(a.call(this, b, qb(this)));
      });if (!arguments.length) return this.attr("class", "");if ("string" == typeof a && a) {
        b = a.match(L) || [];while (c = this[i++]) {
          if (e = qb(c), d = 1 === c.nodeType && " " + pb(e) + " ") {
            g = 0;while (f = b[g++]) {
              while (d.indexOf(" " + f + " ") > -1) {
                d = d.replace(" " + f + " ", " ");
              }
            }h = pb(d), e !== h && c.setAttribute("class", h);
          }
        }
      }return this;
    }, toggleClass: function toggleClass(a, b) {
      var c = typeof a === "undefined" ? "undefined" : _typeof(a);return "boolean" == typeof b && "string" === c ? b ? this.addClass(a) : this.removeClass(a) : r.isFunction(a) ? this.each(function (c) {
        r(this).toggleClass(a.call(this, c, qb(this), b), b);
      }) : this.each(function () {
        var b, d, e, f;if ("string" === c) {
          d = 0, e = r(this), f = a.match(L) || [];while (b = f[d++]) {
            e.hasClass(b) ? e.removeClass(b) : e.addClass(b);
          }
        } else void 0 !== a && "boolean" !== c || (b = qb(this), b && W.set(this, "__className__", b), this.setAttribute && this.setAttribute("class", b || a === !1 ? "" : W.get(this, "__className__") || ""));
      });
    }, hasClass: function hasClass(a) {
      var b,
          c,
          d = 0;b = " " + a + " ";while (c = this[d++]) {
        if (1 === c.nodeType && (" " + pb(qb(c)) + " ").indexOf(b) > -1) return !0;
      }return !1;
    } });var rb = /\r/g;r.fn.extend({ val: function val(a) {
      var b,
          c,
          d,
          e = this[0];{
        if (arguments.length) return d = r.isFunction(a), this.each(function (c) {
          var e;1 === this.nodeType && (e = d ? a.call(this, c, r(this).val()) : a, null == e ? e = "" : "number" == typeof e ? e += "" : Array.isArray(e) && (e = r.map(e, function (a) {
            return null == a ? "" : a + "";
          })), b = r.valHooks[this.type] || r.valHooks[this.nodeName.toLowerCase()], b && "set" in b && void 0 !== b.set(this, e, "value") || (this.value = e));
        });if (e) return b = r.valHooks[e.type] || r.valHooks[e.nodeName.toLowerCase()], b && "get" in b && void 0 !== (c = b.get(e, "value")) ? c : (c = e.value, "string" == typeof c ? c.replace(rb, "") : null == c ? "" : c);
      }
    } }), r.extend({ valHooks: { option: { get: function get(a) {
          var b = r.find.attr(a, "value");return null != b ? b : pb(r.text(a));
        } }, select: { get: function get(a) {
          var b,
              c,
              d,
              e = a.options,
              f = a.selectedIndex,
              g = "select-one" === a.type,
              h = g ? null : [],
              i = g ? f + 1 : e.length;for (d = f < 0 ? i : g ? f : 0; d < i; d++) {
            if (c = e[d], (c.selected || d === f) && !c.disabled && (!c.parentNode.disabled || !B(c.parentNode, "optgroup"))) {
              if (b = r(c).val(), g) return b;h.push(b);
            }
          }return h;
        }, set: function set(a, b) {
          var c,
              d,
              e = a.options,
              f = r.makeArray(b),
              g = e.length;while (g--) {
            d = e[g], (d.selected = r.inArray(r.valHooks.option.get(d), f) > -1) && (c = !0);
          }return c || (a.selectedIndex = -1), f;
        } } } }), r.each(["radio", "checkbox"], function () {
    r.valHooks[this] = { set: function set(a, b) {
        if (Array.isArray(b)) return a.checked = r.inArray(r(a).val(), b) > -1;
      } }, o.checkOn || (r.valHooks[this].get = function (a) {
      return null === a.getAttribute("value") ? "on" : a.value;
    });
  });var sb = /^(?:focusinfocus|focusoutblur)$/;r.extend(r.event, { trigger: function trigger(b, c, e, f) {
      var g,
          h,
          i,
          j,
          k,
          m,
          n,
          o = [e || d],
          p = l.call(b, "type") ? b.type : b,
          q = l.call(b, "namespace") ? b.namespace.split(".") : [];if (h = i = e = e || d, 3 !== e.nodeType && 8 !== e.nodeType && !sb.test(p + r.event.triggered) && (p.indexOf(".") > -1 && (q = p.split("."), p = q.shift(), q.sort()), k = p.indexOf(":") < 0 && "on" + p, b = b[r.expando] ? b : new r.Event(p, "object" == (typeof b === "undefined" ? "undefined" : _typeof(b)) && b), b.isTrigger = f ? 2 : 3, b.namespace = q.join("."), b.rnamespace = b.namespace ? new RegExp("(^|\\.)" + q.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, b.result = void 0, b.target || (b.target = e), c = null == c ? [b] : r.makeArray(c, [b]), n = r.event.special[p] || {}, f || !n.trigger || n.trigger.apply(e, c) !== !1)) {
        if (!f && !n.noBubble && !r.isWindow(e)) {
          for (j = n.delegateType || p, sb.test(j + p) || (h = h.parentNode); h; h = h.parentNode) {
            o.push(h), i = h;
          }i === (e.ownerDocument || d) && o.push(i.defaultView || i.parentWindow || a);
        }g = 0;while ((h = o[g++]) && !b.isPropagationStopped()) {
          b.type = g > 1 ? j : n.bindType || p, m = (W.get(h, "events") || {})[b.type] && W.get(h, "handle"), m && m.apply(h, c), m = k && h[k], m && m.apply && U(h) && (b.result = m.apply(h, c), b.result === !1 && b.preventDefault());
        }return b.type = p, f || b.isDefaultPrevented() || n._default && n._default.apply(o.pop(), c) !== !1 || !U(e) || k && r.isFunction(e[p]) && !r.isWindow(e) && (i = e[k], i && (e[k] = null), r.event.triggered = p, e[p](), r.event.triggered = void 0, i && (e[k] = i)), b.result;
      }
    }, simulate: function simulate(a, b, c) {
      var d = r.extend(new r.Event(), c, { type: a, isSimulated: !0 });r.event.trigger(d, null, b);
    } }), r.fn.extend({ trigger: function trigger(a, b) {
      return this.each(function () {
        r.event.trigger(a, b, this);
      });
    }, triggerHandler: function triggerHandler(a, b) {
      var c = this[0];if (c) return r.event.trigger(a, b, c, !0);
    } }), r.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (a, b) {
    r.fn[b] = function (a, c) {
      return arguments.length > 0 ? this.on(b, null, a, c) : this.trigger(b);
    };
  }), r.fn.extend({ hover: function hover(a, b) {
      return this.mouseenter(a).mouseleave(b || a);
    } }), o.focusin = "onfocusin" in a, o.focusin || r.each({ focus: "focusin", blur: "focusout" }, function (a, b) {
    var c = function c(a) {
      r.event.simulate(b, a.target, r.event.fix(a));
    };r.event.special[b] = { setup: function setup() {
        var d = this.ownerDocument || this,
            e = W.access(d, b);e || d.addEventListener(a, c, !0), W.access(d, b, (e || 0) + 1);
      }, teardown: function teardown() {
        var d = this.ownerDocument || this,
            e = W.access(d, b) - 1;e ? W.access(d, b, e) : (d.removeEventListener(a, c, !0), W.remove(d, b));
      } };
  });var tb = a.location,
      ub = r.now(),
      vb = /\?/;r.parseXML = function (b) {
    var c;if (!b || "string" != typeof b) return null;try {
      c = new a.DOMParser().parseFromString(b, "text/xml");
    } catch (d) {
      c = void 0;
    }return c && !c.getElementsByTagName("parsererror").length || r.error("Invalid XML: " + b), c;
  };var wb = /\[\]$/,
      xb = /\r?\n/g,
      yb = /^(?:submit|button|image|reset|file)$/i,
      zb = /^(?:input|select|textarea|keygen)/i;function Ab(a, b, c, d) {
    var e;if (Array.isArray(b)) r.each(b, function (b, e) {
      c || wb.test(a) ? d(a, e) : Ab(a + "[" + ("object" == (typeof e === "undefined" ? "undefined" : _typeof(e)) && null != e ? b : "") + "]", e, c, d);
    });else if (c || "object" !== r.type(b)) d(a, b);else for (e in b) {
      Ab(a + "[" + e + "]", b[e], c, d);
    }
  }r.param = function (a, b) {
    var c,
        d = [],
        e = function e(a, b) {
      var c = r.isFunction(b) ? b() : b;d[d.length] = encodeURIComponent(a) + "=" + encodeURIComponent(null == c ? "" : c);
    };if (Array.isArray(a) || a.jquery && !r.isPlainObject(a)) r.each(a, function () {
      e(this.name, this.value);
    });else for (c in a) {
      Ab(c, a[c], b, e);
    }return d.join("&");
  }, r.fn.extend({ serialize: function serialize() {
      return r.param(this.serializeArray());
    }, serializeArray: function serializeArray() {
      return this.map(function () {
        var a = r.prop(this, "elements");return a ? r.makeArray(a) : this;
      }).filter(function () {
        var a = this.type;return this.name && !r(this).is(":disabled") && zb.test(this.nodeName) && !yb.test(a) && (this.checked || !ja.test(a));
      }).map(function (a, b) {
        var c = r(this).val();return null == c ? null : Array.isArray(c) ? r.map(c, function (a) {
          return { name: b.name, value: a.replace(xb, "\r\n") };
        }) : { name: b.name, value: c.replace(xb, "\r\n") };
      }).get();
    } });var Bb = /%20/g,
      Cb = /#.*$/,
      Db = /([?&])_=[^&]*/,
      Eb = /^(.*?):[ \t]*([^\r\n]*)$/gm,
      Fb = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
      Gb = /^(?:GET|HEAD)$/,
      Hb = /^\/\//,
      Ib = {},
      Jb = {},
      Kb = "*/".concat("*"),
      Lb = d.createElement("a");Lb.href = tb.href;function Mb(a) {
    return function (b, c) {
      "string" != typeof b && (c = b, b = "*");var d,
          e = 0,
          f = b.toLowerCase().match(L) || [];if (r.isFunction(c)) while (d = f[e++]) {
        "+" === d[0] ? (d = d.slice(1) || "*", (a[d] = a[d] || []).unshift(c)) : (a[d] = a[d] || []).push(c);
      }
    };
  }function Nb(a, b, c, d) {
    var e = {},
        f = a === Jb;function g(h) {
      var i;return e[h] = !0, r.each(a[h] || [], function (a, h) {
        var j = h(b, c, d);return "string" != typeof j || f || e[j] ? f ? !(i = j) : void 0 : (b.dataTypes.unshift(j), g(j), !1);
      }), i;
    }return g(b.dataTypes[0]) || !e["*"] && g("*");
  }function Ob(a, b) {
    var c,
        d,
        e = r.ajaxSettings.flatOptions || {};for (c in b) {
      void 0 !== b[c] && ((e[c] ? a : d || (d = {}))[c] = b[c]);
    }return d && r.extend(!0, a, d), a;
  }function Pb(a, b, c) {
    var d,
        e,
        f,
        g,
        h = a.contents,
        i = a.dataTypes;while ("*" === i[0]) {
      i.shift(), void 0 === d && (d = a.mimeType || b.getResponseHeader("Content-Type"));
    }if (d) for (e in h) {
      if (h[e] && h[e].test(d)) {
        i.unshift(e);break;
      }
    }if (i[0] in c) f = i[0];else {
      for (e in c) {
        if (!i[0] || a.converters[e + " " + i[0]]) {
          f = e;break;
        }g || (g = e);
      }f = f || g;
    }if (f) return f !== i[0] && i.unshift(f), c[f];
  }function Qb(a, b, c, d) {
    var e,
        f,
        g,
        h,
        i,
        j = {},
        k = a.dataTypes.slice();if (k[1]) for (g in a.converters) {
      j[g.toLowerCase()] = a.converters[g];
    }f = k.shift();while (f) {
      if (a.responseFields[f] && (c[a.responseFields[f]] = b), !i && d && a.dataFilter && (b = a.dataFilter(b, a.dataType)), i = f, f = k.shift()) if ("*" === f) f = i;else if ("*" !== i && i !== f) {
        if (g = j[i + " " + f] || j["* " + f], !g) for (e in j) {
          if (h = e.split(" "), h[1] === f && (g = j[i + " " + h[0]] || j["* " + h[0]])) {
            g === !0 ? g = j[e] : j[e] !== !0 && (f = h[0], k.unshift(h[1]));break;
          }
        }if (g !== !0) if (g && a["throws"]) b = g(b);else try {
          b = g(b);
        } catch (l) {
          return { state: "parsererror", error: g ? l : "No conversion from " + i + " to " + f };
        }
      }
    }return { state: "success", data: b };
  }r.extend({ active: 0, lastModified: {}, etag: {}, ajaxSettings: { url: tb.href, type: "GET", isLocal: Fb.test(tb.protocol), global: !0, processData: !0, async: !0, contentType: "application/x-www-form-urlencoded; charset=UTF-8", accepts: { "*": Kb, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript" }, contents: { xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/ }, responseFields: { xml: "responseXML", text: "responseText", json: "responseJSON" }, converters: { "* text": String, "text html": !0, "text json": JSON.parse, "text xml": r.parseXML }, flatOptions: { url: !0, context: !0 } }, ajaxSetup: function ajaxSetup(a, b) {
      return b ? Ob(Ob(a, r.ajaxSettings), b) : Ob(r.ajaxSettings, a);
    }, ajaxPrefilter: Mb(Ib), ajaxTransport: Mb(Jb), ajax: function ajax(b, c) {
      "object" == (typeof b === "undefined" ? "undefined" : _typeof(b)) && (c = b, b = void 0), c = c || {};var e,
          f,
          g,
          h,
          i,
          j,
          k,
          l,
          m,
          n,
          o = r.ajaxSetup({}, c),
          p = o.context || o,
          q = o.context && (p.nodeType || p.jquery) ? r(p) : r.event,
          s = r.Deferred(),
          t = r.Callbacks("once memory"),
          u = o.statusCode || {},
          v = {},
          w = {},
          x = "canceled",
          y = { readyState: 0, getResponseHeader: function getResponseHeader(a) {
          var b;if (k) {
            if (!h) {
              h = {};while (b = Eb.exec(g)) {
                h[b[1].toLowerCase()] = b[2];
              }
            }b = h[a.toLowerCase()];
          }return null == b ? null : b;
        }, getAllResponseHeaders: function getAllResponseHeaders() {
          return k ? g : null;
        }, setRequestHeader: function setRequestHeader(a, b) {
          return null == k && (a = w[a.toLowerCase()] = w[a.toLowerCase()] || a, v[a] = b), this;
        }, overrideMimeType: function overrideMimeType(a) {
          return null == k && (o.mimeType = a), this;
        }, statusCode: function statusCode(a) {
          var b;if (a) if (k) y.always(a[y.status]);else for (b in a) {
            u[b] = [u[b], a[b]];
          }return this;
        }, abort: function abort(a) {
          var b = a || x;return e && e.abort(b), A(0, b), this;
        } };if (s.promise(y), o.url = ((b || o.url || tb.href) + "").replace(Hb, tb.protocol + "//"), o.type = c.method || c.type || o.method || o.type, o.dataTypes = (o.dataType || "*").toLowerCase().match(L) || [""], null == o.crossDomain) {
        j = d.createElement("a");try {
          j.href = o.url, j.href = j.href, o.crossDomain = Lb.protocol + "//" + Lb.host != j.protocol + "//" + j.host;
        } catch (z) {
          o.crossDomain = !0;
        }
      }if (o.data && o.processData && "string" != typeof o.data && (o.data = r.param(o.data, o.traditional)), Nb(Ib, o, c, y), k) return y;l = r.event && o.global, l && 0 === r.active++ && r.event.trigger("ajaxStart"), o.type = o.type.toUpperCase(), o.hasContent = !Gb.test(o.type), f = o.url.replace(Cb, ""), o.hasContent ? o.data && o.processData && 0 === (o.contentType || "").indexOf("application/x-www-form-urlencoded") && (o.data = o.data.replace(Bb, "+")) : (n = o.url.slice(f.length), o.data && (f += (vb.test(f) ? "&" : "?") + o.data, delete o.data), o.cache === !1 && (f = f.replace(Db, "$1"), n = (vb.test(f) ? "&" : "?") + "_=" + ub++ + n), o.url = f + n), o.ifModified && (r.lastModified[f] && y.setRequestHeader("If-Modified-Since", r.lastModified[f]), r.etag[f] && y.setRequestHeader("If-None-Match", r.etag[f])), (o.data && o.hasContent && o.contentType !== !1 || c.contentType) && y.setRequestHeader("Content-Type", o.contentType), y.setRequestHeader("Accept", o.dataTypes[0] && o.accepts[o.dataTypes[0]] ? o.accepts[o.dataTypes[0]] + ("*" !== o.dataTypes[0] ? ", " + Kb + "; q=0.01" : "") : o.accepts["*"]);for (m in o.headers) {
        y.setRequestHeader(m, o.headers[m]);
      }if (o.beforeSend && (o.beforeSend.call(p, y, o) === !1 || k)) return y.abort();if (x = "abort", t.add(o.complete), y.done(o.success), y.fail(o.error), e = Nb(Jb, o, c, y)) {
        if (y.readyState = 1, l && q.trigger("ajaxSend", [y, o]), k) return y;o.async && o.timeout > 0 && (i = a.setTimeout(function () {
          y.abort("timeout");
        }, o.timeout));try {
          k = !1, e.send(v, A);
        } catch (z) {
          if (k) throw z;A(-1, z);
        }
      } else A(-1, "No Transport");function A(b, c, d, h) {
        var j,
            m,
            n,
            v,
            w,
            x = c;k || (k = !0, i && a.clearTimeout(i), e = void 0, g = h || "", y.readyState = b > 0 ? 4 : 0, j = b >= 200 && b < 300 || 304 === b, d && (v = Pb(o, y, d)), v = Qb(o, v, y, j), j ? (o.ifModified && (w = y.getResponseHeader("Last-Modified"), w && (r.lastModified[f] = w), w = y.getResponseHeader("etag"), w && (r.etag[f] = w)), 204 === b || "HEAD" === o.type ? x = "nocontent" : 304 === b ? x = "notmodified" : (x = v.state, m = v.data, n = v.error, j = !n)) : (n = x, !b && x || (x = "error", b < 0 && (b = 0))), y.status = b, y.statusText = (c || x) + "", j ? s.resolveWith(p, [m, x, y]) : s.rejectWith(p, [y, x, n]), y.statusCode(u), u = void 0, l && q.trigger(j ? "ajaxSuccess" : "ajaxError", [y, o, j ? m : n]), t.fireWith(p, [y, x]), l && (q.trigger("ajaxComplete", [y, o]), --r.active || r.event.trigger("ajaxStop")));
      }return y;
    }, getJSON: function getJSON(a, b, c) {
      return r.get(a, b, c, "json");
    }, getScript: function getScript(a, b) {
      return r.get(a, void 0, b, "script");
    } }), r.each(["get", "post"], function (a, b) {
    r[b] = function (a, c, d, e) {
      return r.isFunction(c) && (e = e || d, d = c, c = void 0), r.ajax(r.extend({ url: a, type: b, dataType: e, data: c, success: d }, r.isPlainObject(a) && a));
    };
  }), r._evalUrl = function (a) {
    return r.ajax({ url: a, type: "GET", dataType: "script", cache: !0, async: !1, global: !1, "throws": !0 });
  }, r.fn.extend({ wrapAll: function wrapAll(a) {
      var b;return this[0] && (r.isFunction(a) && (a = a.call(this[0])), b = r(a, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && b.insertBefore(this[0]), b.map(function () {
        var a = this;while (a.firstElementChild) {
          a = a.firstElementChild;
        }return a;
      }).append(this)), this;
    }, wrapInner: function wrapInner(a) {
      return r.isFunction(a) ? this.each(function (b) {
        r(this).wrapInner(a.call(this, b));
      }) : this.each(function () {
        var b = r(this),
            c = b.contents();c.length ? c.wrapAll(a) : b.append(a);
      });
    }, wrap: function wrap(a) {
      var b = r.isFunction(a);return this.each(function (c) {
        r(this).wrapAll(b ? a.call(this, c) : a);
      });
    }, unwrap: function unwrap(a) {
      return this.parent(a).not("body").each(function () {
        r(this).replaceWith(this.childNodes);
      }), this;
    } }), r.expr.pseudos.hidden = function (a) {
    return !r.expr.pseudos.visible(a);
  }, r.expr.pseudos.visible = function (a) {
    return !!(a.offsetWidth || a.offsetHeight || a.getClientRects().length);
  }, r.ajaxSettings.xhr = function () {
    try {
      return new a.XMLHttpRequest();
    } catch (b) {}
  };var Rb = { 0: 200, 1223: 204 },
      Sb = r.ajaxSettings.xhr();o.cors = !!Sb && "withCredentials" in Sb, o.ajax = Sb = !!Sb, r.ajaxTransport(function (b) {
    var _c, d;if (o.cors || Sb && !b.crossDomain) return { send: function send(e, f) {
        var g,
            h = b.xhr();if (h.open(b.type, b.url, b.async, b.username, b.password), b.xhrFields) for (g in b.xhrFields) {
          h[g] = b.xhrFields[g];
        }b.mimeType && h.overrideMimeType && h.overrideMimeType(b.mimeType), b.crossDomain || e["X-Requested-With"] || (e["X-Requested-With"] = "XMLHttpRequest");for (g in e) {
          h.setRequestHeader(g, e[g]);
        }_c = function c(a) {
          return function () {
            _c && (_c = d = h.onload = h.onerror = h.onabort = h.onreadystatechange = null, "abort" === a ? h.abort() : "error" === a ? "number" != typeof h.status ? f(0, "error") : f(h.status, h.statusText) : f(Rb[h.status] || h.status, h.statusText, "text" !== (h.responseType || "text") || "string" != typeof h.responseText ? { binary: h.response } : { text: h.responseText }, h.getAllResponseHeaders()));
          };
        }, h.onload = _c(), d = h.onerror = _c("error"), void 0 !== h.onabort ? h.onabort = d : h.onreadystatechange = function () {
          4 === h.readyState && a.setTimeout(function () {
            _c && d();
          });
        }, _c = _c("abort");try {
          h.send(b.hasContent && b.data || null);
        } catch (i) {
          if (_c) throw i;
        }
      }, abort: function abort() {
        _c && _c();
      } };
  }), r.ajaxPrefilter(function (a) {
    a.crossDomain && (a.contents.script = !1);
  }), r.ajaxSetup({ accepts: { script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript" }, contents: { script: /\b(?:java|ecma)script\b/ }, converters: { "text script": function textScript(a) {
        return r.globalEval(a), a;
      } } }), r.ajaxPrefilter("script", function (a) {
    void 0 === a.cache && (a.cache = !1), a.crossDomain && (a.type = "GET");
  }), r.ajaxTransport("script", function (a) {
    if (a.crossDomain) {
      var b, _c2;return { send: function send(e, f) {
          b = r("<script>").prop({ charset: a.scriptCharset, src: a.url }).on("load error", _c2 = function c(a) {
            b.remove(), _c2 = null, a && f("error" === a.type ? 404 : 200, a.type);
          }), d.head.appendChild(b[0]);
        }, abort: function abort() {
          _c2 && _c2();
        } };
    }
  });var Tb = [],
      Ub = /(=)\?(?=&|$)|\?\?/;r.ajaxSetup({ jsonp: "callback", jsonpCallback: function jsonpCallback() {
      var a = Tb.pop() || r.expando + "_" + ub++;return this[a] = !0, a;
    } }), r.ajaxPrefilter("json jsonp", function (b, c, d) {
    var e,
        f,
        g,
        h = b.jsonp !== !1 && (Ub.test(b.url) ? "url" : "string" == typeof b.data && 0 === (b.contentType || "").indexOf("application/x-www-form-urlencoded") && Ub.test(b.data) && "data");if (h || "jsonp" === b.dataTypes[0]) return e = b.jsonpCallback = r.isFunction(b.jsonpCallback) ? b.jsonpCallback() : b.jsonpCallback, h ? b[h] = b[h].replace(Ub, "$1" + e) : b.jsonp !== !1 && (b.url += (vb.test(b.url) ? "&" : "?") + b.jsonp + "=" + e), b.converters["script json"] = function () {
      return g || r.error(e + " was not called"), g[0];
    }, b.dataTypes[0] = "json", f = a[e], a[e] = function () {
      g = arguments;
    }, d.always(function () {
      void 0 === f ? r(a).removeProp(e) : a[e] = f, b[e] && (b.jsonpCallback = c.jsonpCallback, Tb.push(e)), g && r.isFunction(f) && f(g[0]), g = f = void 0;
    }), "script";
  }), o.createHTMLDocument = function () {
    var a = d.implementation.createHTMLDocument("").body;return a.innerHTML = "<form></form><form></form>", 2 === a.childNodes.length;
  }(), r.parseHTML = function (a, b, c) {
    if ("string" != typeof a) return [];"boolean" == typeof b && (c = b, b = !1);var e, f, g;return b || (o.createHTMLDocument ? (b = d.implementation.createHTMLDocument(""), e = b.createElement("base"), e.href = d.location.href, b.head.appendChild(e)) : b = d), f = C.exec(a), g = !c && [], f ? [b.createElement(f[1])] : (f = qa([a], b, g), g && g.length && r(g).remove(), r.merge([], f.childNodes));
  }, r.fn.load = function (a, b, c) {
    var d,
        e,
        f,
        g = this,
        h = a.indexOf(" ");return h > -1 && (d = pb(a.slice(h)), a = a.slice(0, h)), r.isFunction(b) ? (c = b, b = void 0) : b && "object" == (typeof b === "undefined" ? "undefined" : _typeof(b)) && (e = "POST"), g.length > 0 && r.ajax({ url: a, type: e || "GET", dataType: "html", data: b }).done(function (a) {
      f = arguments, g.html(d ? r("<div>").append(r.parseHTML(a)).find(d) : a);
    }).always(c && function (a, b) {
      g.each(function () {
        c.apply(this, f || [a.responseText, b, a]);
      });
    }), this;
  }, r.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (a, b) {
    r.fn[b] = function (a) {
      return this.on(b, a);
    };
  }), r.expr.pseudos.animated = function (a) {
    return r.grep(r.timers, function (b) {
      return a === b.elem;
    }).length;
  }, r.offset = { setOffset: function setOffset(a, b, c) {
      var d,
          e,
          f,
          g,
          h,
          i,
          j,
          k = r.css(a, "position"),
          l = r(a),
          m = {};"static" === k && (a.style.position = "relative"), h = l.offset(), f = r.css(a, "top"), i = r.css(a, "left"), j = ("absolute" === k || "fixed" === k) && (f + i).indexOf("auto") > -1, j ? (d = l.position(), g = d.top, e = d.left) : (g = parseFloat(f) || 0, e = parseFloat(i) || 0), r.isFunction(b) && (b = b.call(a, c, r.extend({}, h))), null != b.top && (m.top = b.top - h.top + g), null != b.left && (m.left = b.left - h.left + e), "using" in b ? b.using.call(a, m) : l.css(m);
    } }, r.fn.extend({ offset: function offset(a) {
      if (arguments.length) return void 0 === a ? this : this.each(function (b) {
        r.offset.setOffset(this, a, b);
      });var b,
          c,
          d,
          e,
          f = this[0];if (f) return f.getClientRects().length ? (d = f.getBoundingClientRect(), b = f.ownerDocument, c = b.documentElement, e = b.defaultView, { top: d.top + e.pageYOffset - c.clientTop, left: d.left + e.pageXOffset - c.clientLeft }) : { top: 0, left: 0 };
    }, position: function position() {
      if (this[0]) {
        var a,
            b,
            c = this[0],
            d = { top: 0, left: 0 };return "fixed" === r.css(c, "position") ? b = c.getBoundingClientRect() : (a = this.offsetParent(), b = this.offset(), B(a[0], "html") || (d = a.offset()), d = { top: d.top + r.css(a[0], "borderTopWidth", !0), left: d.left + r.css(a[0], "borderLeftWidth", !0) }), { top: b.top - d.top - r.css(c, "marginTop", !0), left: b.left - d.left - r.css(c, "marginLeft", !0) };
      }
    }, offsetParent: function offsetParent() {
      return this.map(function () {
        var a = this.offsetParent;while (a && "static" === r.css(a, "position")) {
          a = a.offsetParent;
        }return a || ra;
      });
    } }), r.each({ scrollLeft: "pageXOffset", scrollTop: "pageYOffset" }, function (a, b) {
    var c = "pageYOffset" === b;r.fn[a] = function (d) {
      return T(this, function (a, d, e) {
        var f;return r.isWindow(a) ? f = a : 9 === a.nodeType && (f = a.defaultView), void 0 === e ? f ? f[b] : a[d] : void (f ? f.scrollTo(c ? f.pageXOffset : e, c ? e : f.pageYOffset) : a[d] = e);
      }, a, d, arguments.length);
    };
  }), r.each(["top", "left"], function (a, b) {
    r.cssHooks[b] = Pa(o.pixelPosition, function (a, c) {
      if (c) return c = Oa(a, b), Ma.test(c) ? r(a).position()[b] + "px" : c;
    });
  }), r.each({ Height: "height", Width: "width" }, function (a, b) {
    r.each({ padding: "inner" + a, content: b, "": "outer" + a }, function (c, d) {
      r.fn[d] = function (e, f) {
        var g = arguments.length && (c || "boolean" != typeof e),
            h = c || (e === !0 || f === !0 ? "margin" : "border");return T(this, function (b, c, e) {
          var f;return r.isWindow(b) ? 0 === d.indexOf("outer") ? b["inner" + a] : b.document.documentElement["client" + a] : 9 === b.nodeType ? (f = b.documentElement, Math.max(b.body["scroll" + a], f["scroll" + a], b.body["offset" + a], f["offset" + a], f["client" + a])) : void 0 === e ? r.css(b, c, h) : r.style(b, c, e, h);
        }, b, g ? e : void 0, g);
      };
    });
  }), r.fn.extend({ bind: function bind(a, b, c) {
      return this.on(a, null, b, c);
    }, unbind: function unbind(a, b) {
      return this.off(a, null, b);
    }, delegate: function delegate(a, b, c, d) {
      return this.on(b, a, c, d);
    }, undelegate: function undelegate(a, b, c) {
      return 1 === arguments.length ? this.off(a, "**") : this.off(b, a || "**", c);
    } }), r.holdReady = function (a) {
    a ? r.readyWait++ : r.ready(!0);
  }, r.isArray = Array.isArray, r.parseJSON = JSON.parse, r.nodeName = B, "function" == "function" && __webpack_require__("./node_modules/webpack/buildin/amd-options.js") && !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = function () {
    return r;
  }.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));var Vb = a.jQuery,
      Wb = a.$;return r.noConflict = function (b) {
    return a.$ === r && (a.$ = Wb), b && a.jQuery === r && (a.jQuery = Vb), r;
  }, b || (a.jQuery = a.$ = r), r;
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__("./node_modules/webpack/buildin/module.js")(module)))

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/assets/js/app.js");
module.exports = __webpack_require__("./resources/assets/sass/app.scss");


/***/ })

/******/ });