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
/******/ 	__webpack_require__.p = "";
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
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(2);

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(module) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Alert__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Textarea__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Select__ = __webpack_require__(6);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__File__ = __webpack_require__(8);





if (!NodeList.prototype.forEach) {
  NodeList.prototype.forEach = Array.prototype.forEach;
}
if (!HTMLCollection.prototype.forEach) {
  HTMLCollection.prototype.forEach = Array.prototype.forEach;
}

const luma = Object.create(null);

luma.alert = (selector) => {
  if (typeof selector === 'object') {
    const close = selector.querySelector('.alert__close');
    if (close) new __WEBPACK_IMPORTED_MODULE_0__Alert__["a" /* default */](close);
  } else {
    Array.prototype.forEach.call(document.querySelectorAll(selector), (element) => {
      const close = element.querySelector('.alert__close');
      if (close) new __WEBPACK_IMPORTED_MODULE_0__Alert__["a" /* default */](close);
    });
  }
};

luma.textarea = (selector) => {
  if (typeof selector === 'object') {
    __WEBPACK_IMPORTED_MODULE_1__Textarea__["a" /* default */].addInputListener(selector);
  } else {
    Array.prototype.forEach.call(document.querySelectorAll(selector), (element) => {
      __WEBPACK_IMPORTED_MODULE_1__Textarea__["a" /* default */].addInputListener(element);
    });
  }
};

luma.select = (selector) => {
  if (typeof selector === 'object') {
    __WEBPACK_IMPORTED_MODULE_2__Select__["a" /* default */].style(selector);
  } else {
    Array.prototype.forEach.call(document.querySelectorAll(selector), (element) => {
      __WEBPACK_IMPORTED_MODULE_2__Select__["a" /* default */].style(element);
    });
  }
};

luma.file = (selector) => {
  if (typeof selector === 'object') {
    __WEBPACK_IMPORTED_MODULE_3__File__["a" /* default */].addChangeListener(selector);
  } else {
    Array.prototype.forEach.call(document.querySelectorAll(selector), (element) => {
      __WEBPACK_IMPORTED_MODULE_3__File__["a" /* default */].addChangeListener(element);
    });
  }
};

Array.prototype.forEach.call(document.querySelectorAll('[data-dismiss="alert"]'), (element) => { new __WEBPACK_IMPORTED_MODULE_0__Alert__["a" /* default */](element); });
Array.prototype.forEach.call(document.querySelectorAll('[data-resize="textarea"]'), (element) => { __WEBPACK_IMPORTED_MODULE_1__Textarea__["a" /* default */].addInputListener(element); });
Array.prototype.forEach.call(document.querySelectorAll('[data-style="select"]'), (element) => { __WEBPACK_IMPORTED_MODULE_2__Select__["a" /* default */].style(element); });
Array.prototype.forEach.call(document.querySelectorAll('[data-display="file"]'), (element) => { __WEBPACK_IMPORTED_MODULE_3__File__["a" /* default */].addChangeListener(element); });

/**
 * Mobile burger menu button and gesture for toggling sidebar
 */

const closeNav = (sidebar, toggleButton) => {
  toggleButton.classList.remove('is-active');
  sidebar.classList.remove('is-open');
};

const openNav = (sidebar, toggleButton) => {
  toggleButton.classList.add('is-active');
  sidebar.classList.add('is-open');
};

const initMobileMenu = () => {
  const mobileBar = document.querySelector('.nav.is-toggle');
  const toggleButton = mobileBar.querySelector('.hamburger');
  const sidebar = document.querySelector('.nav.is-responsive');

  if (mobileBar && toggleButton && sidebar) {
    toggleButton.addEventListener('click', () => {
      toggleButton.classList.toggle('is-active');
      sidebar.classList.toggle('is-open');
    });

    document.body.addEventListener('click', (e) => {
      if (!mobileBar.contains(e.target) && !sidebar.contains(e.target)) {
        closeNav(sidebar, toggleButton);
      }
    });

    // Toggle sidebar on swipe
    const start = {};
    const end = {};

    document.body.addEventListener('touchstart', function (e) {
      start.x = e.changedTouches[0].clientX;
      start.y = e.changedTouches[0].clientY;
    });

    document.body.addEventListener('touchend', function (e) {
      end.y = e.changedTouches[0].clientY;
      end.x = e.changedTouches[0].clientX;

      const xDiff = end.x - start.x;
      const yDiff = end.y - start.y;

      if (Math.abs(xDiff) > Math.abs(yDiff)) {
        if (xDiff > 0 && start.x <= 80) openNav(sidebar, toggleButton);
        else closeNav(sidebar, toggleButton);
      }
    });
  }
};

initMobileMenu();

module.exports = luma;

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(3)(module)))

/***/ }),
/* 3 */
/***/ (function(module, exports) {

module.exports = function(originalModule) {
	if(!originalModule.webpackPolyfill) {
		var module = Object.create(originalModule);
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
		Object.defineProperty(module, "exports", {
			enumerable: true,
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
class Alert {
  constructor(element) {
    this.element = element;
    this.alert = element.closest('.alert');
    this.attachClickHandler();
  }

  attachClickHandler() {
    const self = this;
    this.element.addEventListener('click', () => {
      self.close();
    });
  }

  close() {
    this.alert.classList.add('is-fading');
    setTimeout(() => { this.alert.remove(); }, 300);
  }
}
/* harmony export (immutable) */ __webpack_exports__["a"] = Alert;



/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
class Textarea {
  static addInputListener(element) {
    element.addEventListener('input', () => {
      Textarea.resizeTextarea(element);
    });
  }

  static resizeTextarea(element) {
    element.style.height = '';
    element.style.height = `${element.scrollHeight}px`;
  }
}
/* harmony export (immutable) */ __webpack_exports__["a"] = Textarea;



/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_styleselect__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_styleselect___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_styleselect__);


class Select {
  static style(select) {
    if (select.children.length > 0) {
      __WEBPACK_IMPORTED_MODULE_0_styleselect___default()(select);
    }
  }
}
/* harmony export (immutable) */ __webpack_exports__["a"] = Select;



/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;// UMD module from From https://github.com/umdjs/umd/blob/master/returnExports.js
// From 'if the module has no dependencies' example.
(function (root, factory) {
    if (true) {
        // AMD. Register as an anonymous module.
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = factory();
    } else {
        // Browser globals (root is window)
        root.styleSelect  = factory();
  	}
}(this, function () {
// End of UMD module

	// Quick aliases and polyfills if needed
	var query = document.querySelector.bind(document);
	var queryAll = document.querySelectorAll.bind(document);

	var KEYCODES = {
		SPACE: 32,
		UP: 38,
		DOWN: 40,
		ENTER: 13
	};

	if ( ! NodeList.prototype.forEach ) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}
	if ( ! HTMLCollection.prototype.forEach ) {
		HTMLCollection.prototype.forEach = Array.prototype.forEach;
	}
	if ( ! Element.prototype.matches ) {
		// See https://developer.mozilla.org/en-US/docs/Web/API/Element.matches
		Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.mozMatchesSelector || Element.prototype.webkitMatchesSelector || Element.prototype.oMatchesSelector
	}

	// IE 9-11 CustomEvent polyfill
	// From https://developer.mozilla.org/en/docs/Web/API/CustomEvent
	var CustomEvent = function( eventName, params ) {
		params = params || { bubbles: false, cancelable: false, detail: undefined };
		var event = document.createEvent( 'CustomEvent' );
		event.initCustomEvent( eventName, params.bubbles, params.cancelable, params.detail );
		return event;
	};
	CustomEvent.prototype = window.Event.prototype;
	window.CustomEvent = CustomEvent;

	// IE10 dataset polyfill
	// From https://gist.githubusercontent.com/brettz9/4093766/raw/ba31a05e7ce21af67c6cafee9b3f439c86e95b01/html5-dataset.js
	if (!document.documentElement.dataset &&
			 // FF is empty while IE gives empty object
			(!Object.getOwnPropertyDescriptor(Element.prototype, 'dataset')  ||
			!Object.getOwnPropertyDescriptor(Element.prototype, 'dataset').get)
		) {
		var propDescriptor = {
			enumerable: true,
			get: function () {
				'use strict';
				var i,
					that = this,
					HTML5_DOMStringMap,
					attrVal, attrName, propName,
					attribute,
					attributes = this.attributes,
					attsLength = attributes.length,
					toUpperCase = function (n0) {
						return n0.charAt(1).toUpperCase();
					},
					getter = function () {
						return this;
					},
					setter = function (attrName, value) {
						return (typeof value !== 'undefined') ?
							this.setAttribute(attrName, value) :
							this.removeAttribute(attrName);
					};
				try { // Simulate DOMStringMap w/accessor support
					// Test setting accessor on normal object
					({}).__defineGetter__('test', function () {});
					HTML5_DOMStringMap = {};
				}
				catch (e1) { // Use a DOM object for IE8
					HTML5_DOMStringMap = document.createElement('div');
				}
				for (i = 0; i < attsLength; i++) {
					attribute = attributes[i];
					// Fix: This test really should allow any XML Name without
					//         colons (and non-uppercase for XHTML)
					if (attribute && attribute.name &&
						(/^data-\w[\w\-]*$/).test(attribute.name)) {
						attrVal = attribute.value;
						attrName = attribute.name;
						// Change to CamelCase
						propName = attrName.substr(5).replace(/-./g, toUpperCase);
						try {
							Object.defineProperty(HTML5_DOMStringMap, propName, {
								enumerable: this.enumerable,
								get: getter.bind(attrVal || ''),
								set: setter.bind(that, attrName)
							});
						}
						catch (e2) { // if accessors are not working
							HTML5_DOMStringMap[propName] = attrVal;
						}
					}
				}
				return HTML5_DOMStringMap;
			}
		};
		try {
			// FF enumerates over element's dataset, but not
			//   Element.prototype.dataset; IE9 iterates over both
			Object.defineProperty(Element.prototype, 'dataset', propDescriptor);
		} catch (e) {
			propDescriptor.enumerable = false; // IE8 does not allow setting to true
			Object.defineProperty(Element.prototype, 'dataset', propDescriptor);
		}
	}

	// Return true if any ancestor matches selector
	// Borrowed from ancestorMatches() from agave.js (MIT)
	var isAncestorOf = function(element, selector, includeSelf) {
		var parent = element.parentNode;
		if ( includeSelf && element.matches(selector) ) {
			return true
		}
		// While parents are 'element' type nodes
		// See https://developer.mozilla.org/en-US/docs/DOM/Node.nodeType
		while ( parent && parent.nodeType && parent.nodeType === 1 ) {
			if ( parent.matches(selector) ) {
				return true
			}
			parent = parent.parentNode;
		}
		return false;
	};


	// Used to match select boxes to their style select partners
	var makeUUID = function(){
		return 'ss-xxxx-xxxx-xxxx-xxxx-xxxx'.replace(/x/g, function (c) {
			var r = Math.random() * 16 | 0, v = c == 'x' ? r : r & 0x3 | 0x8;
			return v.toString(16);
		})
	};


	// The 'styleSelect' main function
	// selector:String - CSS selector for the select box to style
	return function(selector) {

		// Use native selects (which pop up large native UIs to go through the options ) on iOS/Android
		if ( navigator.userAgent.match( /iPad|iPhone|Android/i ) ) {
			return
		}

		var realSelect = typeof selector == 'object' ? selector : query(selector),
			realOptions = realSelect.children,
			selectedIndex = realSelect.selectedIndex,
			uuid = makeUUID(),
			styleSelectHTML = '<div class="style-select" aria-hidden="true" data-ss-uuid="' + uuid + '">';

		// The index of the item that's being highlighted by the mouse or keyboard
		var highlightedOptionIndex;
		var highlightedOptionIndexMax = realOptions.length - 1;

		realSelect.setAttribute('data-ss-uuid', uuid);
		// Even though the element is display: none, a11y users should still see it.
		// According to http://www.w3.org/TR/wai-aria/states_and_properties#aria-hidden
		// some browsers may have bugs with this but future implementation may improve
		realSelect.setAttribute('aria-hidden', "false");

		// Build styled clones of all the real options
		var selectedOptionHTML;
		var optionsHTML = '<div class="ss-dropdown">';
		realOptions.forEach(function(realOption, index){
			var text = realOption.textContent,
				value = realOption.getAttribute('value') || '',
                cssClass = 'ss-option';

			if (index === selectedIndex) {
				// Mark first item as selected-option - this is where we store state for the styled select box
				// aria-hidden=true so screen readers ignore the styles selext box in favor of the real one (which is visible by default)
				selectedOptionHTML = '<div class="ss-selected-option" tabindex="0" data-value="' + value + '">' + text + '</div>'
			}

            if (realOption.disabled) {
                cssClass += ' disabled';
            }

            // Continue building optionsHTML
			optionsHTML += '<div class="' + cssClass + '" data-value="' + value + '">' + text + '</div>';
		});
		optionsHTML += '</div>';
		styleSelectHTML += selectedOptionHTML += optionsHTML += '</div>';
		// And add out styled select just after the real select
		realSelect.insertAdjacentHTML('afterend', styleSelectHTML);

		var styledSelect = query('.style-select[data-ss-uuid="'+uuid+'"]');
		var styleSelectOptions = styledSelect.querySelectorAll('.ss-option');
		var selectedOption = styledSelect.querySelector('.ss-selected-option');

		var changeRealSelectBox = function(newValue, newLabel) {
			// Close styledSelect
			styledSelect.classList.remove('open');

			// Update styled value
			selectedOption.textContent = newLabel;
			selectedOption.dataset.value = newValue;

			// Update the 'tick' that shows the option with the current value
			styleSelectOptions.forEach(function(styleSelectOption){
				if ( styleSelectOption.dataset.value === newValue) {
					styleSelectOption.classList.add('ticked')
				} else {
					styleSelectOption.classList.remove('ticked')
				}
			});

			// Update real select box
			realSelect.value = newValue;

			// Send 'change' event to real select - to trigger any change event listeners
			var changeEvent = new CustomEvent('change');
			realSelect.dispatchEvent(changeEvent);
		};

		// Change real select box when a styled option is clicked
		styleSelectOptions.forEach(function(unused, index){
			var styleSelectOption = styleSelectOptions.item(index);

            if (styleSelectOption.className.match(/\bdisabled\b/)) {
                return;
            }

            styleSelectOption.addEventListener('click', function(ev) {
				var target = ev.target,
					styledSelectBox = target.parentNode.parentNode,
					uuid = styledSelectBox.getAttribute('data-ss-uuid'),
					newValue = target.getAttribute('data-value'),
					newLabel = target.textContent;

				changeRealSelectBox(newValue, newLabel)

			});

			// Tick and highlight the option that's currently in use
			if ( styleSelectOption.dataset.value === realSelect.value ) {
				highlightedOptionIndex = index;
				styleSelectOption.classList.add('ticked');
				styleSelectOption.classList.add('highlighted')
			}

			// Important: we can't use ':hover' as the keyboard and default value can also set the highlight
			styleSelectOption.addEventListener('mouseover', function(ev){
				styleSelectOption.parentNode.childNodes.forEach(function(sibling, index){
					if ( sibling === ev.target ) {
						sibling.classList.add('highlighted');
						highlightedOptionIndex = index;
					} else {
						sibling.classList.remove('highlighted')
					}
				})
			})
		});



		var closeAllStyleSelects = function(exception){
			queryAll('.style-select').forEach(function(styleSelectEl) {
				if ( styleSelectEl !== exception ) {
					styleSelectEl.classList.remove('open');
				}
			});
		};

		var toggleStyledSelect = function(styledSelectBox){
			if ( ! styledSelectBox.classList.contains('open') ) {
				// If we're closed and about to open, close other style selects on the page
				closeAllStyleSelects(styledSelectBox);
			}
			// Then toggle open/close
			styledSelectBox.classList.toggle('open');
		};

		// When a styled select box is clicked
		var styledSelectedOption = query('.style-select[data-ss-uuid="' + uuid + '"] .ss-selected-option');
		styledSelectedOption.addEventListener('click', function(ev) {
			ev.preventDefault();
			ev.stopPropagation();
			toggleStyledSelect(ev.target.parentNode);
		});

		// Keyboard handling
		styledSelectedOption.addEventListener('keydown', function(ev) {
			var styledSelectBox = ev.target.parentNode;

			switch (ev.keyCode) {
				case KEYCODES.SPACE:
					// Space shows and hides styles select boxes
					toggleStyledSelect(styledSelectBox);
					break;
				case KEYCODES.DOWN:
				case KEYCODES.UP:
					// Move the highlight up and down
					if ( ! styledSelectBox.classList.contains('open') ) {
						// If style select is not open, up/down should open it.
						toggleStyledSelect(styledSelectBox);
					} else {
						// If style select is already open, these should change what the highlighted option is
						if ( ev.keyCode === KEYCODES.UP ) {
							// Up arrow moves earlier in list
							if ( highlightedOptionIndex !== 0 ) {
								highlightedOptionIndex = highlightedOptionIndex - 1
							}
						} else {
							// Down arrow moves later in list
							if ( highlightedOptionIndex < highlightedOptionIndexMax ) {
								highlightedOptionIndex = highlightedOptionIndex + 1
							}
						}
						styleSelectOptions.forEach(function(option, index){
							if ( index === highlightedOptionIndex ) {
								option.classList.add('highlighted')
							} else {
								option.classList.remove('highlighted')
							}
						})
					}
					ev.preventDefault();
					ev.stopPropagation();
					break;
				// User has picked an item from the keyboard
				case KEYCODES.ENTER:
					var highlightedOption = styledSelectedOption.parentNode.querySelectorAll('.ss-option')[highlightedOptionIndex],
						newValue = highlightedOption.dataset.value,
						newLabel = highlightedOption.textContent;

					changeRealSelectBox(newValue, newLabel);
					ev.preventDefault();
					ev.stopPropagation();
					break;
			}
		});

		// Clicking outside of the styled select box closes any open styled select boxes
		query('body').addEventListener('click', function(ev){

			if ( ! isAncestorOf(ev.target, '.style-select', true) ) {
				closeAllStyleSelects();
			}
		})

	};

// Close UMD module
}));



/***/ }),
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
class File {
  static addChangeListener(element) {
    element.addEventListener('change', () => {
      File.displayFileName(element);
    });
  }

  static displayFileName(element) {
    if (element.files.length > 0) {
      const fileNameElement = element.parentNode.querySelector('.file__name');
      fileNameElement.innerHTML = element.files[0].name;
    }
  }
}
/* harmony export (immutable) */ __webpack_exports__["a"] = File;



/***/ })
/******/ ]);