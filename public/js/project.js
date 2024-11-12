/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/project.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var baseUri = "".concat(window.location.protocol, "//").concat(window.location.hostname);
if (window.location.port) {
  baseUri += ":".concat(window.location.port);
}
var inputprojectId = null;
var userList = [];
var modalType = "";
var inputUserId = null;
var inputMeasurementPointId = null;
var inputMeasurementPoint = null;
var noise_meter_data = [];
var concentrator_data = [];
var valueMap = {
  Residential: {
    mon_sat_7am_7pm_leq5min: 90.0,
    mon_sat_7pm_10pm_leq5min: 70.0,
    mon_sat_10pm_12am_leq5min: 55.0,
    mon_sat_12am_7am_leq5min: 55.0,
    sun_ph_7am_7pm_leq5min: 75.0,
    sun_ph_7pm_10pm_leq5min: 65.0,
    sun_ph_10pm_12am_leq5min: 55.0,
    sun_ph_12am_7am_leq5min: 55.0,
    mon_sat_7am_7pm_leq12hr: 75.0,
    mon_sat_7pm_10pm_leq12hr: 65.0,
    mon_sat_10pm_12am_leq12hr: 55.0,
    mon_sat_12am_7am_leq12hr: 55.0,
    sun_ph_7am_7pm_leq12hr: 75.0,
    sun_ph_7pm_10pm_leq12hr: 140.0,
    sun_ph_10pm_12am_leq12hr: 140.0,
    sun_ph_12am_7am_leq12hr: 140.0
  },
  "Hospital/Schools": {
    mon_sat_7am_7pm_leq5min: 75.0,
    mon_sat_7pm_10pm_leq5min: 55.0,
    mon_sat_10pm_12am_leq5min: 55.0,
    mon_sat_12am_7am_leq5min: 55.0,
    sun_ph_7am_7pm_leq5min: 75.0,
    sun_ph_7pm_10pm_leq5min: 55.0,
    sun_ph_10pm_12am_leq5min: 55.0,
    sun_ph_12am_7am_leq5min: 55.0,
    mon_sat_7am_7pm_leq12hr: 60.0,
    mon_sat_7pm_10pm_leq12hr: 50.0,
    mon_sat_10pm_12am_leq12hr: 50.0,
    mon_sat_12am_7am_leq12hr: 50.0,
    sun_ph_7am_7pm_leq12hr: 60.0,
    sun_ph_7pm_10pm_leq12hr: 50.0,
    sun_ph_10pm_12am_leq12hr: 50.0,
    sun_ph_12am_7am_leq12hr: 50.0
  },
  Others: {
    mon_sat_7am_7pm_leq5min: 90.0,
    mon_sat_7pm_10pm_leq5min: 70.0,
    mon_sat_10pm_12am_leq5min: 70.0,
    mon_sat_12am_7am_leq5min: 70.0,
    sun_ph_7am_7pm_leq5min: 90.0,
    sun_ph_7pm_10pm_leq5min: 70.0,
    sun_ph_10pm_12am_leq5min: 70.0,
    sun_ph_12am_7am_leq5min: 70.0,
    mon_sat_7am_7pm_leq12hr: 75.0,
    mon_sat_7pm_10pm_leq12hr: 65.0,
    mon_sat_10pm_12am_leq12hr: 65.0,
    mon_sat_12am_7am_leq12hr: 65.0,
    sun_ph_7am_7pm_leq12hr: 75.0,
    sun_ph_7pm_10pm_leq12hr: 65.0,
    sun_ph_10pm_12am_leq12hr: 65.0,
    sun_ph_12am_7am_leq12hr: 65.0
  }
};
function toggle_soundLimits() {
  var soundlimit = document.getElementById("advanced_sound_limits");
  soundlimit.hidden ? soundlimit.hidden = false : soundlimit.hidden = true;
}
function populate_soundLimits(event) {
  var reset_defaults = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  if (event && event.preventDefault) {
    event.preventDefault();
  }
  var inputmonsat7am7pmleq5 = document.getElementById("inputmonsat7am7pmleq5");
  var inputmonsat7pm10pmleq5 = document.getElementById("inputmonsat7pm10pmleq5");
  var inputmonsat10pm12amleq5 = document.getElementById("inputmonsat10pm12amleq5");
  var inputmonsat12am7amleq5 = document.getElementById("inputmonsat12am7amleq5");
  var inputmonsat7am7pmleq12 = document.getElementById("inputmonsat7am7pmleq12");
  var inputmonsat7pm10pmleq12 = document.getElementById("inputmonsat7pm10pmleq12");
  var inputmonsat10pm12amleq12 = document.getElementById("inputmonsat10pm12amleq12");
  var inputmonsat12am7amleq12 = document.getElementById("inputmonsat12am7amleq12");
  var inputsunph7am7pmleq5 = document.getElementById("inputsunph7am7pmleq5");
  var inputsunph7pm10pmleq5 = document.getElementById("inputsunph7pm10pmleq5");
  var inputsunph10pm12amleq5 = document.getElementById("inputsunph10pm12amleq5");
  var inputsunph12am7amleq5 = document.getElementById("inputsunph12am7amleq5");
  var inputsunph7am7pmleq12 = document.getElementById("inputsunph7am7pmleq12");
  var inputsunph7pm10pmleq12 = document.getElementById("inputsunph7pm10pmleq12");
  var inputsunph10pm12amleq12 = document.getElementById("inputsunph10pm12amleq12");
  var inputsunph12am7amleq12 = document.getElementById("inputsunph12am7amleq12");
  if (modalType == "create" || reset_defaults) {
    var category = document.getElementById("selectCategory").value;
    inputmonsat7am7pmleq5.value = valueMap[category].mon_sat_7am_7pm_leq5min;
    inputmonsat7pm10pmleq5.value = valueMap[category].mon_sat_7pm_10pm_leq5min;
    inputmonsat10pm12amleq5.value = valueMap[category].mon_sat_10pm_12am_leq5min;
    inputmonsat12am7amleq5.value = valueMap[category].mon_sat_12am_7am_leq5min;
    inputmonsat7am7pmleq12.value = valueMap[category].mon_sat_7am_7pm_leq12hr;
    inputmonsat7pm10pmleq12.value = valueMap[category].mon_sat_7pm_10pm_leq12hr;
    inputmonsat10pm12amleq12.value = valueMap[category].mon_sat_10pm_12am_leq12hr;
    inputmonsat12am7amleq12.value = valueMap[category].mon_sat_12am_7am_leq12hr;
    inputsunph7am7pmleq5.value = valueMap[category].sun_ph_7am_7pm_leq5min;
    inputsunph7pm10pmleq5.value = valueMap[category].sun_ph_7pm_10pm_leq5min;
    inputsunph10pm12amleq5.value = valueMap[category].sun_ph_10pm_12am_leq5min;
    inputsunph12am7amleq5.value = valueMap[category].sun_ph_12am_7am_leq5min;
    inputsunph7am7pmleq12.value = valueMap[category].sun_ph_7am_7pm_leq12hr;
    inputsunph7pm10pmleq12.value = valueMap[category].sun_ph_7pm_10pm_leq12hr;
    inputsunph10pm12amleq12.value = valueMap[category].sun_ph_10pm_12am_leq12hr;
    inputsunph12am7amleq12.value = valueMap[category].sun_ph_12am_7am_leq12hr;
  } else if (modalType == "update") {
    inputmonsat7am7pmleq5.value = inputMeasurementPoint.soundLimit.mon_sat_7am_7pm_leq5min;
    inputmonsat7pm10pmleq5.value = inputMeasurementPoint.soundLimit.mon_sat_7pm_10pm_leq5min;
    inputmonsat10pm12amleq5.value = inputMeasurementPoint.soundLimit.mon_sat_10pm_12am_leq5min;
    inputmonsat12am7amleq5.value = inputMeasurementPoint.soundLimit.mon_sat_12am_7am_leq5min;
    inputmonsat7am7pmleq12.value = inputMeasurementPoint.soundLimit.mon_sat_7am_7pm_leq12hr;
    inputmonsat7pm10pmleq12.value = inputMeasurementPoint.soundLimit.mon_sat_7pm_10pm_leq12hr;
    inputmonsat10pm12amleq12.value = inputMeasurementPoint.soundLimit.mon_sat_10pm_12am_leq12hr;
    inputmonsat12am7amleq12.value = inputMeasurementPoint.soundLimit.mon_sat_12am_7am_leq12hr;
    inputsunph7am7pmleq5.value = inputMeasurementPoint.soundLimit.sun_ph_7am_7pm_leq5min;
    inputsunph7pm10pmleq5.value = inputMeasurementPoint.soundLimit.sun_ph_7pm_10pm_leq5min;
    inputsunph10pm12amleq5.value = inputMeasurementPoint.soundLimit.sun_ph_10pm_12am_leq5min;
    inputsunph12am7amleq5.value = inputMeasurementPoint.soundLimit.sun_ph_12am_7am_leq5min;
    inputsunph7am7pmleq12.value = inputMeasurementPoint.soundLimit.sun_ph_7am_7pm_leq12hr;
    inputsunph7pm10pmleq12.value = inputMeasurementPoint.soundLimit.sun_ph_7pm_10pm_leq12hr;
    inputsunph10pm12amleq12.value = inputMeasurementPoint.soundLimit.sun_ph_10pm_12am_leq12hr;
    inputsunph12am7amleq12.value = inputMeasurementPoint.soundLimit.sun_ph_12am_7am_leq12hr;
  }
}
function create_empty_option(select, text) {
  var defaultOption = document.createElement("option");
  defaultOption.textContent = text;
  defaultOption.selected = true;
  defaultOption.disabled = true;
  select.appendChild(defaultOption);
}
function populateConcentrator() {
  console.log("called");
  var selectConcentrator;
  var defaultConcentrator;
  selectConcentrator = document.getElementById("selectConcentrator");
  selectConcentrator.innerHTML = "";
  if (modalType === "update") {
    defaultConcentrator = concentrator_data[0];
    document.getElementById("existing_device_id").textContent = defaultConcentrator.device_id ? "".concat(defaultConcentrator.device_id, " | ").concat(defaultConcentrator.concentrator_label) : "None Linked";
    if (!defaultConcentrator.device_id) {
      create_empty_option(selectConcentrator, "Choose Concentrator...");
    }
  } else {
    create_empty_option(selectConcentrator, "Choose Concentrator...");
  }
  var url = "".concat(baseUri, "/concentrators/");
  fetch(url).then(function (response) {
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    return response.json();
  }).then(function (data) {
    console.log(data);
    data = data.concentrators;

    // Create options from fetched data
    data.forEach(function (concentrator) {
      var option = document.createElement("option");
      option.value = concentrator.id;
      option.textContent = concentrator.device_id + " | " + concentrator.concentrator_label;
      if (defaultConcentrator && concentrator.id == defaultConcentrator.concentrator_id) {
        option.selected = true;
      }
      selectConcentrator.appendChild(option);
    });
  })["catch"](function (error) {
    console.error("Error fetching data:", error);
  });
}
function populateNoiseMeter() {
  var selectNoiseMeter;
  var defaultNoiseMeter;
  selectNoiseMeter = document.getElementById("selectNoiseMeter");
  selectNoiseMeter.innerHTML = "";
  if (modalType == "update") {
    defaultNoiseMeter = noise_meter_data[0];
    document.getElementById("existing_serial").textContent = defaultNoiseMeter.serial_number ? "".concat(defaultNoiseMeter.serial_number, " | ").concat(defaultNoiseMeter.noise_meter_label) : "None linked";
    if (!defaultNoiseMeter.serial_number) {
      create_empty_option(selectNoiseMeter, "Choose Noise Meter...");
    }
  } else {
    create_empty_option(selectNoiseMeter, "Choose Noise Meter...");
  }
  var url = "".concat(baseUri, "/noise_meters");
  fetch(url).then(function (response) {
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    return response.json();
  }).then(function (data) {
    data = data.noise_meters;
    data.forEach(function (noise_meter) {
      var option = document.createElement("option");
      option.value = noise_meter.id;
      option.textContent = noise_meter.serial_number + " | " + noise_meter.noise_meter_label;
      if (defaultNoiseMeter && noise_meter.id == defaultNoiseMeter.noise_meter_id) {
        option.selected = true;
      }
      selectNoiseMeter.appendChild(option);
    });
  })["catch"](function (error) {
    console.error("Error fetching data:", error);
  });
}
function populateSelects() {
  console.log("LAKSJD");
  populateConcentrator();
  populateNoiseMeter();
}
function set_contact_table() {
  var contactTable = new Tabulator("#contacts_table", {
    layout: "fitColumns",
    data: window.contacts,
    placeholder: "No linked Contacts",
    selectable: 1,
    columns: [{
      formatter: "rowSelection",
      titleFormatter: "rowSelection",
      hozAlign: "center",
      headerSort: false,
      frozen: true,
      width: 30
    }, {
      title: "Name",
      field: "contact_person_name",
      headerSort: false,
      minWidth: 100
    }, {
      title: "Designation",
      field: "designation",
      headerSort: false,
      minWidth: 100
    }, {
      title: "Email",
      field: "email",
      headerSort: false,
      minWidth: 100
    }, {
      title: "SMS",
      field: "phone_number",
      headerSort: false,
      minWidth: 100
    }]
  });
  contactTable.on("rowSelectionChanged", function (data, rows) {
    contactTableRowChanged(data);
  });
}
function fetch_contact_data() {
  var inputName = document.getElementById("inputName");
  var inputDesignation = document.getElementById("inputDesignation");
  var inputEmail = document.getElementById("inputEmail");
  var inputPhoneNumber = document.getElementById("inputPhoneNumber");
  var inputContactProjectID = document.getElementById("inputContactProjectID");
  inputContactProjectID.value = inputprojectId;
  if (modalType == "create") {
    inputName.value = null;
    inputDesignation.value = null;
    inputEmail.value = null;
    inputPhoneNumber.value = null;
  } else if (modalType == "update") {
    inputName.value = window.selectedContact.contact_person_name;
    inputDesignation.value = window.selectedContact.designation;
    inputEmail.value = window.selectedContact.email;
    inputPhoneNumber.value = window.selectedContact.phone_number;
  }
}
function manage_measurement_point_columns() {
  if (window.admin) {
    return [{
      formatter: "rowSelection",
      titleFormatter: "rowSelection",
      hozAlign: "center",
      headerSort: false,
      frozen: true,
      width: 30
    }, {
      title: "Point Name",
      field: "point_name",
      minWidth: 100,
      headerFilter: "input",
      frozen: true
    }, {
      title: "Point Location",
      field: "device_location",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100
    }, {
      title: "Concentrator Serial",
      field: "device_id",
      headerFilter: "input",
      minWidth: 100
    }, {
      title: "Concentrator Battery Voltage",
      field: "battery_voltage",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100
    }, {
      title: "Concentrator CSQ",
      field: "concentrator_csq",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100
    }, {
      title: "Last Concentrator Communication",
      field: "last_communication_packet_sent",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100
    }, {
      title: "Noise Serial",
      field: "serial_number",
      minWidth: 100,
      headerFilter: "input"
    }, {
      title: "Data Status",
      field: "data_status",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100,
      formatter: "tickCross"
    }];
  } else {
    return [{
      formatter: "rowSelection",
      titleFormatter: "rowSelection",
      hozAlign: "center",
      headerSort: false,
      frozen: true,
      width: 30
    }, {
      title: "Point Name",
      field: "point_name",
      minWidth: 100,
      headerFilter: "input",
      frozen: true
    }, {
      title: "Point Location",
      field: "device_location",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100
    }, {
      title: "Noise Serial",
      field: "serial_number",
      minWidth: 100,
      headerFilter: "input"
    }, {
      title: "Data Status",
      field: "data_status",
      headerSort: false,
      headerFilter: "input",
      minWidth: 100,
      formatter: "tickCross"
    }];
  }
}
function set_measurement_point_table(measurementPoint_data) {
  document.getElementById("measurement_point_pages").innerHTML = "";
  var measurementPointTable = new Tabulator("#measurement_point_table", {
    layout: "fitColumns",
    data: measurementPoint_data,
    placeholder: "No Linked Measurement Points",
    paginationSize: 20,
    pagination: "local",
    paginationCounter: "rows",
    paginationElement: document.getElementById("measurement_point_pages"),
    selectable: 1,
    columns: manage_measurement_point_columns()
  });
  measurementPointTable.on("rowClick", function (e, row) {
    window.location.href = "/measurement_point/" + row.getIndex();
  });
  measurementPointTable.on("rowSelectionChanged", function (data, rows) {
    table_row_changed(data);
  });
}
function contactTableRowChanged(data) {
  if (data && data.length > 0) {
    document.getElementById("editContactButton").disabled = false;
    document.getElementById("deleteContactButton").disabled = false;
    window.selectedContactid = data[0].id;
    window.selectedContact = data[0];
  } else {
    document.getElementById("editContactButton").disabled = true;
    document.getElementById("deleteContactButton").disabled = true;
  }
}
function table_row_changed(data) {
  if (data && data.length > 0) {
    document.getElementById("editButton").disabled = false;
    document.getElementById("deleteButton").disabled = false;
    inputMeasurementPointId = data[0].id;
    inputMeasurementPoint = data[0];
  } else {
    document.getElementById("editButton").disabled = true;
    document.getElementById("deleteButton").disabled = true;
  }
}
function fetch_measurement_point_data() {
  var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  noise_meter_data = [];
  concentrator_data = [];
  var pointName = document.getElementById("inputPointName");
  var remarks = document.getElementById("inputRemarks");
  var device_location = document.getElementById("inputDeviceLocation");
  var category = document.getElementById("category");
  document.getElementById("error_message").innerHTML = "";
  if (data) {
    pointName.value = data.point_name;
    remarks.value = data.remarks;
    device_location.value = data.device_location;
    category.innerHTML = data.category;
    concentrator_data.push({
      concentrator_id: data.concentrator_id,
      concentrator_label: data.concentrator_label,
      device_id: data.device_id
    });
    noise_meter_data.push({
      noise_meter_id: data.noise_meter_id,
      noise_meter_label: data.noise_meter_label,
      serial_number: data.serial_number
    });
    document.getElementById("existing_devices").hidden = false;
    document.getElementById("existing_category").hidden = false;
    document.getElementById("advanced_sound_limits").hidden = true;
  } else {
    pointName.value = null;
    remarks.value = null;
    device_location.value = null;
    category.innerHTML = null;
    concentrator_data.push({
      concentrator_id: null,
      concentrator_label: null,
      device_id: null
    });
    noise_meter_data.push({
      noise_meter_id: null,
      noise_meter_label: null,
      serial_number: null
    });
    document.getElementById("existing_devices").hidden = true;
    document.getElementById("existing_category").hidden = true;
    document.getElementById("advanced_sound_limits").hidden = true;
  }
}
function getProjectId() {
  inputprojectId = document.getElementById("inputprojectId").value;
}
function get_measurement_point_data() {
  fetch("".concat(baseUri, "/measurement_points/").concat(inputprojectId), {
    method: "get",
    headers: {
      "Content-type": "application/json; charset=UTF-8",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    }
  }).then(function (response) {
    if (!response.ok) {
      return response.text().then(function (text) {
        throw new Error(text);
      });
    }
    return response.json();
  }).then(function (json) {
    var measurementPoint_data = json.measurement_point;
    set_measurement_point_table(measurementPoint_data);
  })["catch"](function (error) {
    console.log(error);
  });
}
function update_sound_limits(_x) {
  return _update_sound_limits.apply(this, arguments);
}
function _update_sound_limits() {
  _update_sound_limits = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee(formDataJson) {
    var csrfToken;
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
          return _context.abrupt("return", fetch("".concat(baseUri, "/soundlimits/").concat(inputMeasurementPoint.soundLimit.id), {
            method: "PATCH",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              "Content-Type": "application/json",
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(formDataJson)
          }).then(function (response) {
            if (!response.ok) {
              throw new Error("Network response was not ok " + response.statusText);
            }
            closeModal("measurementPointModal");
          }));
        case 2:
        case "end":
          return _context.stop();
      }
    }, _callee);
  }));
  return _update_sound_limits.apply(this, arguments);
}
function create_sound_limits(_x2) {
  return _create_sound_limits.apply(this, arguments);
}
function _create_sound_limits() {
  _create_sound_limits = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2(formDataJson) {
    var csrfToken;
    return _regeneratorRuntime().wrap(function _callee2$(_context2) {
      while (1) switch (_context2.prev = _context2.next) {
        case 0:
          csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
          return _context2.abrupt("return", fetch("".concat(baseUri, "/soundlimits"), {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              "Content-Type": "application/json",
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(formDataJson)
          }).then(function (response) {
            if (response.status == 422) {
              response.json().then(function (errorData) {
                document.getElementById("error_message").innerHTML = errorData["Unprocessable Entity"];
              });
            } else {
              closeModal("measurementPointModal");
            }
          }));
        case 2:
        case "end":
          return _context2.stop();
      }
    }, _callee2);
  }));
  return _create_sound_limits.apply(this, arguments);
}
function handle_create_measurement_point(_x3) {
  return _handle_create_measurement_point.apply(this, arguments);
}
function _handle_create_measurement_point() {
  _handle_create_measurement_point = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee4(confirmation) {
    var csrfToken, form, formData, formDataJson;
    return _regeneratorRuntime().wrap(function _callee4$(_context4) {
      while (1) switch (_context4.prev = _context4.next) {
        case 0:
          csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
          form = document.getElementById("measurement_point_form");
          formData = new FormData(form);
          formDataJson = {};
          formData.forEach(function (value, key) {
            formDataJson[key] = value;
          });
          formDataJson["confirmation"] = confirmation;
          return _context4.abrupt("return", fetch("".concat(baseUri, "/measurement_point"), {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              "Content-Type": "application/json",
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(formDataJson)
          }).then(function (response) {
            if (response.status == 422) {
              response.json().then(function (errorData) {
                if (errorData["Unprocessable Entity"]["concentrator"] || errorData["Unprocessable Entity"]["noise_meter"]) {
                  if (errorData["Unprocessable Entity"]["concentrator"]) {
                    message += errorData["Unprocessable Entity"]["concentrator"]["concentrator_label"] + "\t";
                  }
                  if (errorData["Unprocessable Entity"]["noise_meter"]) {
                    message += errorData["Unprocessable Entity"]["noise_meter"]["noise_meter_label"] + "\t";
                  }
                  document.getElementById("devicesSpan").innerHTML = message;
                  openSecondModal("measurementPointModal", "confirmationModal");
                }
                document.getElementById("error_message").innerHTML = errorData["Unprocessable Entity"];
              });
            } else {
              response.json().then(/*#__PURE__*/function () {
                var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee3(json) {
                  return _regeneratorRuntime().wrap(function _callee3$(_context3) {
                    while (1) switch (_context3.prev = _context3.next) {
                      case 0:
                        formDataJson["measurement_point_id"] = json.measurement_point["id"];
                        _context3.next = 3;
                        return create_sound_limits(formDataJson);
                      case 3:
                        return _context3.abrupt("return", _context3.sent);
                      case 4:
                      case "end":
                        return _context3.stop();
                    }
                  }, _callee3);
                }));
                return function (_x9) {
                  return _ref.apply(this, arguments);
                };
              }());
            }
          })["catch"](function (error) {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
          }));
        case 7:
        case "end":
          return _context4.stop();
      }
    }, _callee4);
  }));
  return _handle_create_measurement_point.apply(this, arguments);
}
function handle_measurement_point_update(_x4) {
  return _handle_measurement_point_update.apply(this, arguments);
}
function _handle_measurement_point_update() {
  _handle_measurement_point_update = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee5(confirmation) {
    var csrfToken, form, formData, formDataJson;
    return _regeneratorRuntime().wrap(function _callee5$(_context5) {
      while (1) switch (_context5.prev = _context5.next) {
        case 0:
          csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
          form = document.getElementById("measurement_point_form");
          formData = new FormData(form);
          formDataJson = {};
          formData.forEach(function (value, key) {
            formDataJson[key] = value;
          });
          formDataJson["confirmation"] = confirmation;
          return _context5.abrupt("return", fetch("".concat(baseUri, "/measurement_points/").concat(inputMeasurementPointId), {
            method: "PATCH",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              "Content-Type": "application/json",
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(formDataJson)
          }).then(function (response) {
            if (response.status == 422) {
              response.json().then(function (errorData) {
                if (errorData["Unprocessable Entity"]["concentrator"] || errorData["Unprocessable Entity"]["noise_meter"]) {
                  var message = "";
                  if (errorData["Unprocessable Entity"]["concentrator"]) {
                    message += errorData["Unprocessable Entity"]["concentrator"]["concentrator_label"] + " | ";
                  }
                  if (errorData["Unprocessable Entity"]["noise_meter"]) {
                    message += errorData["Unprocessable Entity"]["noise_meter"]["noise_meter_label"] + " | ";
                  }
                  document.getElementById("devicesSpan").innerHTML = message;
                  openSecondModal("measurementPointModal", "confirmationModal");
                }
                document.getElementById("error_message").innerHTML = errorData["Unprocessable Entity"];
              });
            } else {
              console.log("in fetch");
              update_sound_limits(formDataJson);
            }
          })["catch"](function (error) {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
          }));
        case 7:
        case "end":
          return _context5.stop();
      }
    }, _callee5);
  }));
  return _handle_measurement_point_update.apply(this, arguments);
}
function handleContactSubmit() {
  modalType == "create" ? handleCreateContact() : handleUpdateContact();
  location.reload();
}
function handleUpdateContact() {
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
  var form = document.getElementById("contact_form");
  var formData = new FormData(form);
  var formDataJson = {};
  formData.forEach(function (value, key) {
    formDataJson[key] = value;
  });
  fetch("".concat(baseUri, "/contacts/").concat(window.selectedContactid), {
    method: "PATCH",
    headers: {
      "X-CSRF-TOKEN": csrfToken,
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest"
    },
    body: JSON.stringify(formDataJson)
  }).then(function (response) {
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    closeModal("contactModal");
  })["catch"](function (error) {
    console.error("Error:", error);
    alert("There was an error: " + error.message);
  });
  return false;
}
function handleCreateContact() {
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
  var form = document.getElementById("contact_form");
  var formData = new FormData(form);
  var formDataJson = {};
  formData.forEach(function (value, key) {
    formDataJson[key] = value;
  });
  fetch("".concat(baseUri, "/contacts/"), {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrfToken,
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest"
    },
    body: JSON.stringify(formDataJson)
  }).then(function (response) {
    if (!response.ok) {
      throw new Error("Network response was not ok " + response.statusText);
    }
    closeModal("contactModal");
  })["catch"](function (error) {
    console.error("Error:", error);
    alert("There was an error: " + error.message);
  });
  return false;
}
function handleMeasurementPointDelete(_x5) {
  return _handleMeasurementPointDelete.apply(this, arguments);
}
function _handleMeasurementPointDelete() {
  _handleMeasurementPointDelete = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee6(csrfToken) {
    return _regeneratorRuntime().wrap(function _callee6$(_context6) {
      while (1) switch (_context6.prev = _context6.next) {
        case 0:
          return _context6.abrupt("return", fetch("".concat(baseUri, "/measurement_points/").concat(inputMeasurementPointId), {
            method: "DELETE",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest"
            }
          }).then(function (response) {
            if (!response.ok) {
              console.log("Error:", response);
              throw new Error("Network response was not ok");
            }
            return response.json();
          }).then(function (data) {
            console.log("Success:", data);
            closeModal("deleteConfirmationModal");
          })["catch"](function (error) {
            console.error("Error:", error);
          }));
        case 1:
        case "end":
          return _context6.stop();
      }
    }, _callee6);
  }));
  return _handleMeasurementPointDelete.apply(this, arguments);
}
function handleContactDelete(_x6) {
  return _handleContactDelete.apply(this, arguments);
}
function _handleContactDelete() {
  _handleContactDelete = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee7(csrfToken) {
    return _regeneratorRuntime().wrap(function _callee7$(_context7) {
      while (1) switch (_context7.prev = _context7.next) {
        case 0:
          return _context7.abrupt("return", fetch("".concat(baseUri, "/contacts/").concat(window.selectedContactid), {
            method: "DELETE",
            headers: {
              "X-CSRF-TOKEN": csrfToken,
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest"
            }
          }).then(function (response) {
            if (!response.ok) {
              console.log("Error:", response);
              throw new Error("Network response was not ok");
            }
            return response.json();
          }).then(function (data) {
            console.log("Success:", data);
            closeModal("deleteConfirmationModal");
            location.reload();
          })["catch"](function (error) {
            console.error("Error:", error);
          }));
        case 1:
        case "end":
          return _context7.stop();
      }
    }, _callee7);
  }));
  return _handleContactDelete.apply(this, arguments);
}
function handleDelete(_x7) {
  return _handleDelete.apply(this, arguments);
}
function _handleDelete() {
  _handleDelete = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee8(event) {
    var csrfToken, confirmation, error;
    return _regeneratorRuntime().wrap(function _callee8$(_context8) {
      while (1) switch (_context8.prev = _context8.next) {
        case 0:
          console.log("in here");
          _context8.prev = 1;
          if (event) {
            event.preventDefault();
          }
          csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
          confirmation = document.getElementById("inputDeleteConfirmation").value;
          console.log(confirmation);
          console.log("in here deep");
          if (!(confirmation == "DELETE")) {
            _context8.next = 18;
            break;
          }
          if (!(window.deleteType == "measurementPoints")) {
            _context8.next = 13;
            break;
          }
          _context8.next = 11;
          return handleMeasurementPointDelete(csrfToken);
        case 11:
          _context8.next = 16;
          break;
        case 13:
          if (!(window.deleteType == "contact")) {
            _context8.next = 16;
            break;
          }
          _context8.next = 16;
          return handleContactDelete(csrfToken);
        case 16:
          _context8.next = 23;
          break;
        case 18:
          console.log("here");
          console.log(document.getElementById("deleteConfirmationError").checkVisibility());
          error = document.getElementById("deleteConfirmationError");
          error.hidden = false;
          console.log(document.getElementById("deleteConfirmationError").checkVisibility());
        case 23:
          _context8.next = 28;
          break;
        case 25:
          _context8.prev = 25;
          _context8.t0 = _context8["catch"](1);
          console.log(_context8.t0);
        case 28:
          _context8.prev = 28;
          get_measurement_point_data();
          return _context8.finish(28);
        case 31:
        case "end":
          return _context8.stop();
      }
    }, _callee8, null, [[1, 25, 28, 31]]);
  }));
  return _handleDelete.apply(this, arguments);
}
function handle_measurementpoint_submit() {
  return _handle_measurementpoint_submit.apply(this, arguments);
}
function _handle_measurementpoint_submit() {
  _handle_measurementpoint_submit = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee9() {
    var confirmation,
      _args9 = arguments;
    return _regeneratorRuntime().wrap(function _callee9$(_context9) {
      while (1) switch (_context9.prev = _context9.next) {
        case 0:
          confirmation = _args9.length > 0 && _args9[0] !== undefined ? _args9[0] : false;
          _context9.prev = 1;
          if (!(modalType == "update")) {
            _context9.next = 7;
            break;
          }
          _context9.next = 5;
          return handle_measurement_point_update(confirmation);
        case 5:
          _context9.next = 9;
          break;
        case 7:
          _context9.next = 9;
          return handle_create_measurement_point(confirmation);
        case 9:
          _context9.next = 14;
          break;
        case 11:
          _context9.prev = 11;
          _context9.t0 = _context9["catch"](1);
          console.log(_context9.t0);
        case 14:
          _context9.prev = 14;
          get_measurement_point_data();
          return _context9.finish(14);
        case 17:
        case "end":
          return _context9.stop();
      }
    }, _callee9, null, [[1, 11, 14, 17]]);
  }));
  return _handle_measurementpoint_submit.apply(this, arguments);
}
function openModal(modalName) {
  var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  if (modalName == "measurementPointModal") {
    if (type == "create") {
      modalType = "create";
      fetch_measurement_point_data();
      populateSelects();
      populate_soundLimits(null);
    } else if (type == "update") {
      modalType = "update";
      fetch_measurement_point_data(inputMeasurementPoint);
      if (window.admin) {
        console.log(window.admin);
        populateSelects();
      }
      populate_soundLimits(null);
    }
  } else if (modalName == "contactModal") {
    if (type == "create") {
      modalType = "create";
    } else if (type = "update") {
      modalType = "update";
    }
    fetch_contact_data();
  } else if (modalName == "deleteConfirmationModal") {
    document.getElementById("deleteConfirmationError").hidden = true;
    type == "contact" ? window.deleteType = "contact" : window.deleteType = "measurementPoints";
  }
  var modal = new bootstrap.Modal(document.getElementById(modalName));
  modal.toggle();
}
function closeModal(modal) {
  // Close the modal
  var modalElement = document.getElementById(modal);
  var modalInstance = bootstrap.Modal.getInstance(modalElement);
  modalInstance.hide();
}
function check_contact_max() {
  if (window.contacts.length >= window.project.sms_count) {
    document.getElementById("createContactButton").disabled = true;
  }
}
function handleConfirmationSubmit(_x8) {
  return _handleConfirmationSubmit.apply(this, arguments);
}
function _handleConfirmationSubmit() {
  _handleConfirmationSubmit = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee10(event) {
    var csrfToken, confirmation, error;
    return _regeneratorRuntime().wrap(function _callee10$(_context10) {
      while (1) switch (_context10.prev = _context10.next) {
        case 0:
          console.log("here");
          _context10.prev = 1;
          if (event) {
            event.preventDefault();
          }
          csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
          confirmation = document.getElementById("inputContinueConfirmation").value;
          if (!(confirmation == "YES")) {
            _context10.next = 11;
            break;
          }
          _context10.next = 8;
          return handle_measurementpoint_submit(true);
        case 8:
          location.reload();
          _context10.next = 13;
          break;
        case 11:
          error = document.getElementById("confirmationError");
          error.hidden = false;
        case 13:
          _context10.next = 18;
          break;
        case 15:
          _context10.prev = 15;
          _context10.t0 = _context10["catch"](1);
          console.log(_context10.t0);
        case 18:
          _context10.prev = 18;
          get_measurement_point_data();
          return _context10.finish(18);
        case 21:
        case "end":
          return _context10.stop();
      }
    }, _callee10, null, [[1, 15, 18, 21]]);
  }));
  return _handleConfirmationSubmit.apply(this, arguments);
}
function openSecondModal(initialModal, newModal) {
  if (newModal == "confirmationModal") {
    document.getElementById("confirmationError").hidden = true;
  }
  var firstModalEl = document.getElementById(initialModal);
  var firstModal = bootstrap.Modal.getInstance(firstModalEl);
  firstModal.hide();
  firstModalEl.addEventListener("hidden.bs.modal", function () {
    var secondModal = new bootstrap.Modal(document.getElementById(newModal));
    secondModal.show();
    document.getElementById(newModal).addEventListener("hidden.bs.modal", function () {
      firstModal.show();
    }, {
      once: true
    });
  }, {
    once: true
  });
}
window.handle_measurement_point_update = handle_measurement_point_update;
window.handle_create_measurement_point = handle_create_measurement_point;
window.handleDelete = handleDelete;
window.openModal = openModal;
window.handle_measurementpoint_submit = handle_measurementpoint_submit;
window.populate_soundLimits = populate_soundLimits;
window.toggle_soundLimits = toggle_soundLimits;
window.handleContactSubmit = handleContactSubmit;
window.handleConfirmationSubmit = handleConfirmationSubmit;
getProjectId();
get_measurement_point_data();
set_contact_table();
check_contact_max();
/******/ })()
;