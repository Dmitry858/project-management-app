/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/Comments.js":
/*!**********************************!*\
  !*** ./resources/js/Comments.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Comments)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var Comments = /*#__PURE__*/function () {
  function Comments() {
    var _document$querySelect;
    _classCallCheck(this, Comments);
    this.csrf = (_document$querySelect = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.getAttribute('content');
    this.isInited = false;
  }
  _createClass(Comments, [{
    key: "init",
    value: function init() {
      if (this.isInited) return;
      this.bindEvents();
      this.isInited = true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      if (this.isInited) return;
      var addCommentBtn = document.getElementById('add-comment-btn'),
        deleteCommentBtns = document.getElementsByClassName('delete-comment-btn'),
        editCommentBtns = document.getElementsByClassName('edit-comment-btn');
      if (addCommentBtn) {
        addCommentBtn.addEventListener('click', this.addNewComment.bind(this));
      }
      if (deleteCommentBtns.length > 0) {
        var _iterator = _createForOfIteratorHelper(deleteCommentBtns),
          _step;
        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var btn = _step.value;
            btn.addEventListener('click', this.deleteComment.bind(this));
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }
      }
      if (editCommentBtns.length > 0) {
        var _iterator2 = _createForOfIteratorHelper(editCommentBtns),
          _step2;
        try {
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            var _btn = _step2.value;
            _btn.addEventListener('click', this.editComment.bind(this));
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }
      }
    }
  }, {
    key: "addNewComment",
    value: function addNewComment(event) {
      var _this = this;
      event.preventDefault();
      var commentsWrap = document.getElementById('comments-wrap'),
        comment = document.getElementById('comment'),
        error = document.getElementById('add-comment-error');
      if (!commentsWrap || !comment || !error || !this.csrf) return;
      error.innerText = '';
      var value = comment.value.trim();
      if (!value) {
        error.innerText = 'Пожалуйста, добавьте комментарий';
        return;
      }
      var data = {
        'id': comment.dataset.taskId,
        'comment': value
      };
      fetch('/comments/create', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': this.csrf,
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(data)
      }).then(function (response) {
        return response.json();
      }).then(function (result) {
        if (result.status && result.status === 'error') {
          error.innerText = result.text;
        }
        if (result.status && result.status === 'success') {
          var commentNode = _this.getCommentNode(result.result);
          commentsWrap.appendChild(commentNode);
          comment.value = '';
        }
      });
    }
  }, {
    key: "deleteComment",
    value: function deleteComment(event) {
      var isConfirmed = confirm('Подтвердите удаление');
      if (!isConfirmed) return;
      var commentId = event.currentTarget.dataset.id;
      if (!commentId) return;
      var error = event.currentTarget.parentElement.querySelector('.error'),
        commentWrap = event.currentTarget.parentElement.parentElement;
      if (!error || !this.csrf) return;
      error.innerText = '';
      fetch('/comments/' + commentId, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': this.csrf
        }
      }).then(function (response) {
        return response.json();
      }).then(function (result) {
        if (result.status && result.status === 'error') {
          error.innerText = result.text;
        }
        if (result.status && result.status === 'success') {
          commentWrap.remove();
        }
      });
    }
  }, {
    key: "editComment",
    value: function editComment(event) {
      var editIcon = event.currentTarget,
        commentWrap = editIcon.parentElement.parentElement,
        commentText = commentWrap.querySelector('.comment-text'),
        commentId = editIcon.dataset.id;
      if (!commentText || !commentId) return;
      if (commentText.classList.contains('hidden')) {
        var input = commentWrap.querySelector('.edit-comment-input'),
          error = commentWrap.querySelector('.error');
        if (!input || !error) return;
        error.innerText = '';
        var curValue = commentText.innerText,
          newValue = input.value;
        if (curValue === newValue) {
          commentText.classList.remove('hidden');
          editIcon.classList.add('text-gray-400');
          editIcon.classList.remove('text-blue-600');
          input.remove();
          return;
        }
        if (newValue.trim() === '') {
          error.innerText = 'Пожалуйста, добавьте комментарий';
          return;
        }
        var data = {
          'comment_text': newValue
        };
        fetch('/comments/' + commentId, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': this.csrf,
            'Content-Type': 'application/json;charset=utf-8'
          },
          body: JSON.stringify(data)
        }).then(function (response) {
          return response.json();
        }).then(function (result) {
          if (result.status && result.status === 'error') {
            error.innerText = result.text;
          }
          if (result.status && result.status === 'success') {
            commentText.innerText = newValue;
            commentText.classList.remove('hidden');
            editIcon.classList.add('text-gray-400');
            editIcon.classList.remove('text-blue-600');
            input.remove();
          }
        });
      } else {
        var _input = this.getEditCommentInput(commentText.innerText);
        commentWrap.appendChild(_input);
        commentText.classList.add('hidden');
        editIcon.classList.add('text-blue-600');
        editIcon.classList.remove('text-gray-400');
      }
    }
  }, {
    key: "getEditCommentInput",
    value: function getEditCommentInput(value) {
      var input = document.createElement('input');
      input.setAttribute('type', 'text');
      input.setAttribute('value', value);
      input.className = 'edit-comment-input appearance-none block w-full border border-gray-300 rounded py-3 px-4 mt-1 leading-tight focus:outline-none focus:bg-white';
      return input;
    }
  }, {
    key: "getCommentNode",
    value: function getCommentNode(commentObj) {
      var node = document.createElement('div');
      node.className = 'bg-white p-4 mb-3 border border-gray-300 rounded comment-wrap';
      var header = document.createElement('p');
      header.className = 'text-sm text-blue-600';
      header.innerText = commentObj.full_name + ' ';
      var date = document.createElement('span');
      date.className = 'text-xs text-gray-400 ml-1';
      date.innerText = commentObj.datetime;
      header.appendChild(date);
      if (commentObj.editable) {
        header.insertAdjacentHTML('beforeend', this.getEditBtn());
      }
      if (commentObj.deletable) {
        header.insertAdjacentHTML('beforeend', this.getDeleteBtn());
      }
      var error = document.createElement('span');
      error.className = 'error text-red-500 ml-1';
      header.appendChild(error);
      node.appendChild(header);
      var comment = document.createElement('p');
      comment.className = 'comment-text';
      comment.innerText = commentObj.comment_text;
      node.appendChild(comment);
      return node;
    }
  }, {
    key: "getEditBtn",
    value: function getEditBtn() {
      return ' <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-pencil inline-block text-gray-400 mx-1 -mt-1 cursor-pointer edit-comment-btn" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>';
    }
  }, {
    key: "getDeleteBtn",
    value: function getDeleteBtn() {
      return ' <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash inline-block text-gray-400 -mt-1 cursor-pointer delete-comment-btn" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
    }
  }]);
  return Comments;
}();


/***/ }),

/***/ "./resources/js/DeleteItemsGroupHandler.js":
/*!*************************************************!*\
  !*** ./resources/js/DeleteItemsGroupHandler.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ DeleteItemsGroupHandler)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var DeleteItemsGroupHandler = /*#__PURE__*/function () {
  function DeleteItemsGroupHandler() {
    var _document$querySelect;
    _classCallCheck(this, DeleteItemsGroupHandler);
    this.csrf = (_document$querySelect = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.getAttribute('content');
    this.isInited = false;
    this.mainMark = null;
    this.marks = [];
    this.deleteItemsLink = null;
  }
  _createClass(DeleteItemsGroupHandler, [{
    key: "init",
    value: function init() {
      if (this.isInited) return;
      this.mainMark = document.querySelector('input[name="mark-deleted-all"]');
      this.marks = document.querySelectorAll('input[name="mark-deleted"]');
      this.deleteItemsLink = document.getElementById('delete-items-link');
      this.bindEvents();
      this.inactivateAllCheckboxes();
      this.isInited = true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      if (this.isInited || this.marks.length === 0) return;
      if (this.mainMark) {
        this.mainMark.addEventListener('change', this.markAllItems.bind(this));
      }
      if (this.deleteItemsLink) {
        this.deleteItemsLink.addEventListener('click', this.deleteItems.bind(this));
      }
      var _iterator = _createForOfIteratorHelper(this.marks),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var mark = _step.value;
          mark.addEventListener('click', this.controlLinkVisibility.bind(this));
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    }
  }, {
    key: "markAllItems",
    value: function markAllItems(event) {
      var _iterator2 = _createForOfIteratorHelper(this.marks),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var mark = _step2.value;
          if (!mark.disabled) {
            mark.checked = event.currentTarget.checked;
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
      this.controlLinkVisibility();
    }
  }, {
    key: "deleteItems",
    value: function deleteItems(event) {
      event.preventDefault();
      var confirmed = confirm('Подтвердите удаление');
      if (!confirmed || !this.csrf) return;
      var entity = this.getEntity(),
        data = {
          'ids': this.getItemsIds()
        };
      if (!entity || data.ids.length === 0) return;
      var url = '/' + entity + '/delete';
      if (entity === 'roles' || entity === 'stages') {
        url = '/settings' + url;
      }
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': this.csrf,
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(data)
      }).then(function (response) {
        return response.json();
      }).then(function (result) {
        window.location.reload();
      })["catch"](function (e) {
        console.log(e.message);
      });
    }
  }, {
    key: "inactivateAllCheckboxes",
    value: function inactivateAllCheckboxes() {
      if (this.mainMark && this.mainMark.checked) {
        this.mainMark.checked = false;
      }
      if (this.marks.length > 0) {
        var _iterator3 = _createForOfIteratorHelper(this.marks),
          _step3;
        try {
          for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
            var mark = _step3.value;
            if (mark.checked) mark.checked = false;
            var tr = mark.parentElement.parentElement.parentElement;
            if (tr.dataset.entity === 'roles' && tr.dataset.id === '1') {
              mark.setAttribute('disabled', '');
              mark.classList.add('bg-gray-100');
              mark.classList.remove('bg-white');
            }
          }
        } catch (err) {
          _iterator3.e(err);
        } finally {
          _iterator3.f();
        }
      }
    }
  }, {
    key: "controlLinkVisibility",
    value: function controlLinkVisibility() {
      if (this.marks.length === 0 || !this.deleteItemsLink) return;
      var isChecked = false;
      var _iterator4 = _createForOfIteratorHelper(this.marks),
        _step4;
      try {
        for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
          var mark = _step4.value;
          if (mark.checked) isChecked = true;
        }
      } catch (err) {
        _iterator4.e(err);
      } finally {
        _iterator4.f();
      }
      if (isChecked && this.deleteItemsLink.classList.contains('hidden')) {
        this.deleteItemsLink.classList.remove('hidden');
      } else if (!isChecked && !this.deleteItemsLink.classList.contains('hidden')) {
        this.deleteItemsLink.classList.add('hidden');
      }
    }
  }, {
    key: "getEntity",
    value: function getEntity() {
      var tr = document.querySelector('tbody tr');
      return tr.dataset.entity;
    }
  }, {
    key: "getItemsIds",
    value: function getItemsIds() {
      var ids = [];
      var _iterator5 = _createForOfIteratorHelper(this.marks),
        _step5;
      try {
        for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
          var mark = _step5.value;
          if (mark.checked) {
            ids.push(Number(mark.parentElement.parentElement.parentElement.dataset.id));
          }
        }
      } catch (err) {
        _iterator5.e(err);
      } finally {
        _iterator5.f();
      }
      return ids;
    }
  }]);
  return DeleteItemsGroupHandler;
}();


/***/ }),

/***/ "./resources/js/Filters.js":
/*!*********************************!*\
  !*** ./resources/js/Filters.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Filters)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var Filters = /*#__PURE__*/function () {
  function Filters() {
    _classCallCheck(this, Filters);
    this.isInited = false;
  }
  _createClass(Filters, [{
    key: "init",
    value: function init() {
      if (this.isInited) return;
      this.bindEvents();
      this.correctPaginationLinks();
      this.isInited = true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      if (this.isInited) return;
      var applyBtn = document.querySelector('.filter .filter-apply-btn');
      if (!applyBtn) return;
      applyBtn.addEventListener('click', this.applyFilter.bind(this));
    }
  }, {
    key: "applyFilter",
    value: function applyFilter(event) {
      event.preventDefault();
      var formData = new FormData(event.currentTarget.parentElement.parentElement),
        queryString = '';
      var _iterator = _createForOfIteratorHelper(formData.entries()),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var arr = _step.value;
          if (arr[0] === '_token' || arr[1] === '') continue;
          var firstSymbol = queryString === '' ? '?' : '&';
          queryString += firstSymbol + arr[0] + '=' + arr[1];
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      var href = window.location.origin + window.location.pathname;
      if (queryString !== '') {
        href += queryString;
      }
      window.location.href = href;
    }
  }, {
    key: "correctPaginationLinks",
    value: function correctPaginationLinks() {
      var links = document.querySelectorAll('.pagination a');
      var query = window.location.search.substring(1);
      if (links.length === 0 || query === '') return;
      if (query.indexOf('page') >= 0) {
        var arQueryParams = query.split('&');
        arQueryParams.forEach(function (item, index, object) {
          if (item.indexOf('page') >= 0) {
            object.splice(index, 1);
          }
        });
        query = arQueryParams.join('&');
      }
      if (query === '') return;
      var _iterator2 = _createForOfIteratorHelper(links),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var link = _step2.value;
          link.href += '&' + query;
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }
  }]);
  return Filters;
}();


/***/ }),

/***/ "./resources/js/Invitations.js":
/*!*************************************!*\
  !*** ./resources/js/Invitations.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Invitations)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var Invitations = /*#__PURE__*/function () {
  function Invitations() {
    var _document$querySelect;
    _classCallCheck(this, Invitations);
    this.csrf = (_document$querySelect = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.getAttribute('content');
    this.success = document.getElementById('toast-success');
    this.error = document.getElementById('toast-error');
    this.isInited = false;
  }
  _createClass(Invitations, [{
    key: "init",
    value: function init() {
      if (this.isInited) return;
      this.bindEvents();
      this.isInited = true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      if (this.isInited) return;
      var sendBtns = document.getElementsByClassName('send-invitation-btn'),
        closeBtns = document.getElementsByClassName('close-button');
      if (sendBtns.length > 0) {
        var _iterator = _createForOfIteratorHelper(sendBtns),
          _step;
        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var btn = _step.value;
            btn.addEventListener('click', this.sendInvitation.bind(this));
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }
      }
      if (closeBtns.length > 0) {
        var _iterator2 = _createForOfIteratorHelper(closeBtns),
          _step2;
        try {
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            var _btn = _step2.value;
            _btn.addEventListener('click', this.closeToast.bind(this));
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }
      }
    }
  }, {
    key: "sendInvitation",
    value: function sendInvitation(event) {
      var _this = this;
      event.preventDefault();
      if (!this.csrf) return;
      var linkTag = event.currentTarget;
      var url = linkTag.href;
      if (!url) return;
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': this.csrf,
          'Content-Type': 'application/json;charset=utf-8'
        }
      }).then(function (response) {
        return response.json();
      }).then(function (result) {
        if (result.status && result.status === 'error' && _this.error) {
          _this.showToast(_this.error, result.text);
        }
        if (result.status && result.status === 'success' && _this.success) {
          _this.showToast(_this.success, result.text);
          linkTag.classList.add('disabled');
          var id = linkTag.parentElement.parentElement.dataset.id;
          var td = document.getElementById('status-' + id);
          if (td) td.innerText = 'Да';
        }
      })["catch"](function (e) {
        _this.showToast(_this.error, 'Ошибка: ' + e.message);
      });
    }
  }, {
    key: "showToast",
    value: function showToast(node, text) {
      node.querySelector('.toast-text').innerText = text;
      node.classList.remove('hidden');
      setTimeout(function () {
        node.classList.remove('opacity-0');
      }, 50);
      setTimeout(function () {
        node.classList.add('opacity-0');
      }, 2000);
      setTimeout(function () {
        node.classList.add('hidden');
        node.querySelector('.toast-text').innerText = '';
      }, 2150);
    }
  }, {
    key: "closeToast",
    value: function closeToast(event) {
      var node = event.currentTarget.parentElement;
      node.classList.add('opacity-0');
      setTimeout(function () {
        node.classList.add('hidden');
        node.querySelector('.toast-text').innerText = '';
      }, 150);
    }
  }]);
  return Invitations;
}();


/***/ }),

/***/ "./resources/js/Stages.js":
/*!********************************!*\
  !*** ./resources/js/Stages.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Stages)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var Stages = /*#__PURE__*/function () {
  function Stages() {
    var _document$querySelect;
    _classCallCheck(this, Stages);
    this.csrf = (_document$querySelect = document.querySelector('meta[name="csrf-token"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.getAttribute('content');
    this.isInited = false;
    this.stageSelect = null;
  }
  _createClass(Stages, [{
    key: "init",
    value: function init() {
      if (this.isInited) return;
      this.bindEvents();
      this.isInited = true;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      if (this.isInited) return;
      var stageSelect = document.getElementById('stage_id');
      if (stageSelect) {
        this.stageSelect = stageSelect;
        stageSelect.addEventListener('change', this.changeStage.bind(this));
      }
    }
  }, {
    key: "changeStage",
    value: function changeStage(event) {
      event.preventDefault();
      var success = document.getElementById('change-stage-success-message'),
        error = document.getElementById('change-stage-error-message');
      if (!success || !error || !this.csrf) return;
      success.innerText = '';
      error.innerText = '';
      var value = this.stageSelect.value,
        taskId = this.stageSelect.dataset.taskId;
      if (!value || !taskId) return;
      var data = {
        'stage_id': value
      };
      fetch('/change-stage/' + taskId, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': this.csrf,
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(data)
      }).then(function (response) {
        return response.json();
      }).then(function (result) {
        if (result.status && result.status === 'error') {
          error.innerText = result.text;
        }
        if (result.status && result.status === 'success') {
          success.innerText = result.text;
        }
      });
    }
  }]);
  return Stages;
}();


/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Comments__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Comments */ "./resources/js/Comments.js");
/* harmony import */ var _Stages__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Stages */ "./resources/js/Stages.js");
/* harmony import */ var _Invitations__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Invitations */ "./resources/js/Invitations.js");
/* harmony import */ var _Filters__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Filters */ "./resources/js/Filters.js");
/* harmony import */ var _DeleteItemsGroupHandler__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./DeleteItemsGroupHandler */ "./resources/js/DeleteItemsGroupHandler.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }





window.addEventListener('load', function (e) {
  // Toggle dropdown list
  var dropdownTrigger = document.getElementById('user-dropdown-trigger');
  if (dropdownTrigger) {
    dropdownTrigger.addEventListener('click', function (e) {
      document.getElementById('user-dropdown').classList.toggle('invisible');
    });
  }

  // Delete current logo
  var deleteLogoBtn = document.getElementById('delete-logo-btn');
  if (deleteLogoBtn) {
    deleteLogoBtn.addEventListener('click', function (e) {
      e.currentTarget.parentElement.remove();
    });
  }

  // Delete current attachments
  var deleteFileBtns = document.getElementsByClassName('delete-file-btn'),
    deletedFilesInput = document.getElementById('deleted_attachments');
  if (deleteFileBtns.length > 0 && deletedFilesInput) {
    var _iterator = _createForOfIteratorHelper(deleteFileBtns),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var btn = _step.value;
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          var value = deletedFilesInput.value,
            fileId = e.currentTarget.dataset.id;
          if (value === '') {
            value = fileId;
          } else {
            value += ', ' + fileId;
          }
          deletedFilesInput.value = value;
          e.currentTarget.parentElement.remove();
        });
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }

  // Toggle isAllday select
  var isAlldaySelect = document.getElementById('is_allday');
  if (isAlldaySelect) {
    isAlldaySelect.addEventListener('change', function (e) {
      var dateInputs = document.getElementsByClassName('date-input');
      if (dateInputs.length === 0) return;
      var type = e.currentTarget.value === '1' ? 'date' : 'datetime-local';
      var _iterator2 = _createForOfIteratorHelper(dateInputs),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var input = _step2.value;
          input.setAttribute('type', type);
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    });
  }

  // Init Comments class
  var comments = new _Comments__WEBPACK_IMPORTED_MODULE_0__["default"]();
  comments.init();

  // Init Stages class
  var stages = new _Stages__WEBPACK_IMPORTED_MODULE_1__["default"]();
  stages.init();

  // Init Invitations class
  var invitations = new _Invitations__WEBPACK_IMPORTED_MODULE_2__["default"]();
  invitations.init();

  // Init Filters class
  var filters = new _Filters__WEBPACK_IMPORTED_MODULE_3__["default"]();
  filters.init();

  // Init DeleteItemsGroupHandler class
  var handler = new _DeleteItemsGroupHandler__WEBPACK_IMPORTED_MODULE_4__["default"]();
  handler.init();
});

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
  if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
    var dropdowns = document.getElementsByClassName("dropdownlist");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (!openDropdown.classList.contains('invisible')) {
        openDropdown.classList.add('invisible');
      }
    }
  }
};

/***/ }),

/***/ "./resources/css/app.css":
/*!*******************************!*\
  !*** ./resources/css/app.css ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/css/calendar.css":
/*!************************************!*\
  !*** ./resources/css/calendar.css ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"css/calendar": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/calendar","css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	__webpack_require__.O(undefined, ["css/calendar","css/app"], () => (__webpack_require__("./resources/css/app.css")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/calendar","css/app"], () => (__webpack_require__("./resources/css/calendar.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;