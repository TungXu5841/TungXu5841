/*!
 * V-Elements - Live page builder
 * Copyright 2019-2021 WebshopWorks.com & Elementor.com
 */

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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 182);
/******/ })
/************************************************************************/
/******/ ({

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});
var userAgent = navigator.userAgent;

exports.default = {
	webkit: -1 !== userAgent.indexOf('AppleWebKit'),
	firefox: -1 !== userAgent.indexOf('Firefox'),
	ie: /Trident|MSIE/.test(userAgent),
	edge: -1 !== userAgent.indexOf('Edge'),
	mac: -1 !== userAgent.indexOf('Macintosh')
};

/***/ }),

/***/ 13:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Handles managing all events for whatever you plug it into. Priorities for hooks are based on lowest to highest in
 * that, lowest priority hooks are fired first.
 */

var EventManager = function EventManager() {
	var slice = Array.prototype.slice,
		MethodsAvailable;

	/**
  * Contains the hooks that get registered with this EventManager. The array for storage utilizes a "flat"
  * object literal such that looking up the hook utilizes the native object literal hash.
  */
	var STORAGE = {
		actions: {},
		filters: {}
	};

	/**
  * Removes the specified hook by resetting the value of it.
  *
  * @param type Type of hook, either 'actions' or 'filters'
  * @param hook The hook (namespace.identifier) to remove
  *
  * @private
  */
	function _removeHook(type, hook, callback, context) {
		var handlers, handler, i;

		if (!STORAGE[type][hook]) {
			return;
		}
		if (!callback) {
			STORAGE[type][hook] = [];
		} else {
			handlers = STORAGE[type][hook];
			if (!context) {
				for (i = handlers.length; i--;) {
					if (handlers[i].callback === callback) {
						handlers.splice(i, 1);
					}
				}
			} else {
				for (i = handlers.length; i--;) {
					handler = handlers[i];
					if (handler.callback === callback && handler.context === context) {
						handlers.splice(i, 1);
					}
				}
			}
		}
	}

	/**
  * Use an insert sort for keeping our hooks organized based on priority. This function is ridiculously faster
  * than bubble sort, etc: http://jsperf.com/javascript-sort
  *
  * @param hooks The custom array containing all of the appropriate hooks to perform an insert sort on.
  * @private
  */
	function _hookInsertSort(hooks) {
		var tmpHook, j, prevHook;
		for (var i = 1, len = hooks.length; i < len; i++) {
			tmpHook = hooks[i];
			j = i;
			while ((prevHook = hooks[j - 1]) && prevHook.priority > tmpHook.priority) {
				hooks[j] = hooks[j - 1];
				--j;
			}
			hooks[j] = tmpHook;
		}

		return hooks;
	}

	/**
  * Adds the hook to the appropriate storage container
  *
  * @param type 'actions' or 'filters'
  * @param hook The hook (namespace.identifier) to add to our event manager
  * @param callback The function that will be called when the hook is executed.
  * @param priority The priority of this hook. Must be an integer.
  * @param [context] A value to be used for this
  * @private
  */
	function _addHook(type, hook, callback, priority, context) {
		var hookObject = {
			callback: callback,
			priority: priority,
			context: context
		};

		// Utilize 'prop itself' : http://jsperf.com/hasownproperty-vs-in-vs-undefined/19
		var hooks = STORAGE[type][hook];
		if (hooks) {
			// TEMP FIX BUG
			var hasSameCallback = false;
			jQuery.each(hooks, function () {
				if (this.callback === callback) {
					hasSameCallback = true;
					return false;
				}
			});

			if (hasSameCallback) {
				return;
			}
			// END TEMP FIX BUG

			hooks.push(hookObject);
			hooks = _hookInsertSort(hooks);
		} else {
			hooks = [hookObject];
		}

		STORAGE[type][hook] = hooks;
	}

	/**
  * Runs the specified hook. If it is an action, the value is not modified but if it is a filter, it is.
  *
  * @param type 'actions' or 'filters'
  * @param hook The hook ( namespace.identifier ) to be ran.
  * @param args Arguments to pass to the action/filter. If it's a filter, args is actually a single parameter.
  * @private
  */
	function _runHook(type, hook, args) {
		var handlers = STORAGE[type][hook],
			i,
			len;

		if (!handlers) {
			return 'filters' === type ? args[0] : false;
		}

		len = handlers.length;
		if ('filters' === type) {
			for (i = 0; i < len; i++) {
				args[0] = handlers[i].callback.apply(handlers[i].context, args);
			}
		} else {
			for (i = 0; i < len; i++) {
				handlers[i].callback.apply(handlers[i].context, args);
			}
		}

		return 'filters' === type ? args[0] : true;
	}

	/**
  * Adds an action to the event manager.
  *
  * @param action Must contain namespace.identifier
  * @param callback Must be a valid callback function before this action is added
  * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
  * @param [context] Supply a value to be used for this
  */
	function addAction(action, callback, priority, context) {
		if ('string' === typeof action && 'function' === typeof callback) {
			priority = parseInt(priority || 10, 10);
			_addHook('actions', action, callback, priority, context);
		}

		return MethodsAvailable;
	}

	/**
  * Performs an action if it exists. You can pass as many arguments as you want to this function; the only rule is
  * that the first argument must always be the action.
  */
	function doAction() /* action, arg1, arg2, ... */{
		var args = slice.call(arguments);
		var action = args.shift();

		if ('string' === typeof action) {
			_runHook('actions', action, args);
		}

		return MethodsAvailable;
	}

	/**
  * Removes the specified action if it contains a namespace.identifier & exists.
  *
  * @param action The action to remove
  * @param [callback] Callback function to remove
  */
	function removeAction(action, callback) {
		if ('string' === typeof action) {
			_removeHook('actions', action, callback);
		}

		return MethodsAvailable;
	}

	/**
  * Adds a filter to the event manager.
  *
  * @param filter Must contain namespace.identifier
  * @param callback Must be a valid callback function before this action is added
  * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
  * @param [context] Supply a value to be used for this
  */
	function addFilter(filter, callback, priority, context) {
		if ('string' === typeof filter && 'function' === typeof callback) {
			priority = parseInt(priority || 10, 10);
			_addHook('filters', filter, callback, priority, context);
		}

		return MethodsAvailable;
	}

	/**
  * Performs a filter if it exists. You should only ever pass 1 argument to be filtered. The only rule is that
  * the first argument must always be the filter.
  */
	function applyFilters() /* filter, filtered arg, arg2, ... */{
		var args = slice.call(arguments);
		var filter = args.shift();

		if ('string' === typeof filter) {
			return _runHook('filters', filter, args);
		}

		return MethodsAvailable;
	}

	/**
  * Removes the specified filter if it contains a namespace.identifier & exists.
  *
  * @param filter The action to remove
  * @param [callback] Callback function to remove
  */
	function removeFilter(filter, callback) {
		if ('string' === typeof filter) {
			_removeHook('filters', filter, callback);
		}

		return MethodsAvailable;
	}

	/**
  * Maintain a reference to the object scope so our public methods never get confusing.
  */
	MethodsAvailable = {
		removeFilter: removeFilter,
		applyFilters: applyFilters,
		addFilter: addFilter,
		removeAction: removeAction,
		doAction: doAction,
		addAction: addAction
	};

	// return all of the publicly available methods
	return MethodsAvailable;
};

module.exports = EventManager;

/***/ }),

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

var $ = jQuery;

window.Sticky = function( element, userSettings ) {
	var $element,
		isSticky = false,
		isFollowingParent = false,
		isReachedEffectsPoint = false,
		elements = {},
		settings;

	var defaultSettings = {
		to: 'top',
		offset: 0,
		effectsOffset: 0,
		parent: false,
		classes: {
			sticky: 'sticky',
			stickyActive: 'sticky-active',
			stickyEffects: 'sticky-effects',
			spacer: 'sticky-spacer',
		},
	};

	var initElements = function() {
		$element = $( element ).addClass( settings.classes.sticky );

		elements.$window = $( window );

		if ( settings.parent ) {
			if ( 'parent' === settings.parent ) {
				elements.$parent = $element.parent();
			} else {
				elements.$parent = $element.closest( settings.parent );
			}
		}
	};

	var initSettings = function() {
		settings = jQuery.extend( true, defaultSettings, userSettings );
	};

	var bindEvents = function() {
		elements.$window.on( {
			scroll: onWindowScroll,
			resize: onWindowResize,
		} );
	};

	var unbindEvents = function() {
		elements.$window
			.off( 'scroll', onWindowScroll )
			.off( 'resize', onWindowResize );
	};

	var init = function() {
		initSettings();

		initElements();

		bindEvents();

		checkPosition();
	};

	var backupCSS = function( $elementBackupCSS, backupState, properties ) {
		var css = {},
			elementStyle = $elementBackupCSS[ 0 ].style;

		properties.forEach( function( property ) {
			css[ property ] = undefined !== elementStyle[ property ] ? elementStyle[ property ] : '';
		} );

		$elementBackupCSS.data( 'css-backup-' + backupState, css );
	};

	var getCSSBackup = function( $elementCSSBackup, backupState ) {
		return $elementCSSBackup.data( 'css-backup-' + backupState );
	};

	var addSpacer = function() {
		elements.$spacer = $element.clone()
			.addClass( settings.classes.spacer )
			.css( {
				visibility: 'hidden',
				transition: 'none',
				animation: 'none',
			} );

		$element.after( elements.$spacer );
	};

	var removeSpacer = function() {
		elements.$spacer.remove();
	};

	var stickElement = function() {
		backupCSS( $element, 'unsticky', [ 'position', 'width', 'margin-top', 'margin-bottom', 'top', 'bottom' ] );

		var css = {
			position: 'fixed',
			width: getElementOuterSize( $element, 'width' ),
			marginTop: 0,
			marginBottom: 0,
		};

		css[ settings.to ] = settings.offset;

		css[ 'top' === settings.to ? 'bottom' : 'top' ] = '';

		$element
			.css( css )
			.addClass( settings.classes.stickyActive );
	};

	var unstickElement = function() {
		$element
			.css( getCSSBackup( $element, 'unsticky' ) )
			.removeClass( settings.classes.stickyActive );
	};

	var followParent = function() {
		backupCSS( elements.$parent, 'childNotFollowing', [ 'position' ] );

		elements.$parent.css( 'position', 'relative' );

		backupCSS( $element, 'notFollowing', [ 'position', 'top', 'bottom' ] );

		var css = {
			position: 'absolute',
		};

		css[ settings.to ] = '';

		css[ 'top' === settings.to ? 'bottom' : 'top' ] = 0;

		$element.css( css );

		isFollowingParent = true;
	};

	var unfollowParent = function() {
		elements.$parent.css( getCSSBackup( elements.$parent, 'childNotFollowing' ) );

		$element.css( getCSSBackup( $element, 'notFollowing' ) );

		isFollowingParent = false;
	};

	var getElementOuterSize = function( $elementOuterSize, dimension, includeMargins ) {
		var computedStyle = getComputedStyle( $elementOuterSize[ 0 ] ),
			elementSize = parseFloat( computedStyle[ dimension ] ),
			sides = 'height' === dimension ? [ 'top', 'bottom' ] : [ 'left', 'right' ],
			propertiesToAdd = [];

		if ( 'border-box' !== computedStyle.boxSizing ) {
			propertiesToAdd.push( 'border', 'padding' );
		}

		if ( includeMargins ) {
			propertiesToAdd.push( 'margin' );
		}

		propertiesToAdd.forEach( function( property ) {
			sides.forEach( function( side ) {
				elementSize += parseFloat( computedStyle[ property + '-' + side ] );
			} );
		} );

		return elementSize;
	};

	var getElementViewportOffset = function( $elementViewportOffset ) {
		var windowScrollTop = elements.$window.scrollTop(),
			elementHeight = getElementOuterSize( $elementViewportOffset, 'height' ),
			viewportHeight = innerHeight,
			elementOffsetFromTop = $elementViewportOffset.offset().top,
			distanceFromTop = elementOffsetFromTop - windowScrollTop,
			topFromBottom = distanceFromTop - viewportHeight;

		return {
			top: {
				fromTop: distanceFromTop,
				fromBottom: topFromBottom,
			},
			bottom: {
				fromTop: distanceFromTop + elementHeight,
				fromBottom: topFromBottom + elementHeight,
			},
		};
	};

	var stick = function() {
		addSpacer();

		stickElement();

		isSticky = true;

		$element.trigger( 'sticky:stick' );
	};

	var unstick = function() {
		unstickElement();

		removeSpacer();

		isSticky = false;

		$element.trigger( 'sticky:unstick' );
	};

	var checkParent = function() {
		var elementOffset = getElementViewportOffset( $element ),
			isTop = 'top' === settings.to;

		if ( isFollowingParent ) {
			var isNeedUnfollowing = isTop ? elementOffset.top.fromTop > settings.offset : elementOffset.bottom.fromBottom < -settings.offset;

			if ( isNeedUnfollowing ) {
				unfollowParent();
			}
		} else {
			var parentOffset = getElementViewportOffset( elements.$parent ),
				parentStyle = getComputedStyle( elements.$parent[ 0 ] ),
				borderWidthToDecrease = parseFloat( parentStyle[ isTop ? 'borderBottomWidth' : 'borderTopWidth' ] ),
				parentViewportDistance = isTop ? parentOffset.bottom.fromTop - borderWidthToDecrease : parentOffset.top.fromBottom + borderWidthToDecrease,
				isNeedFollowing = isTop ? parentViewportDistance <= elementOffset.bottom.fromTop : parentViewportDistance >= elementOffset.top.fromBottom;

			if ( isNeedFollowing ) {
				followParent();
			}
		}
	};

	var checkEffectsPoint = function( distanceFromTriggerPoint ) {
		if ( isReachedEffectsPoint && -distanceFromTriggerPoint < settings.effectsOffset ) {
			$element.removeClass( settings.classes.stickyEffects );

			isReachedEffectsPoint = false;
		} else if ( ! isReachedEffectsPoint && -distanceFromTriggerPoint >= settings.effectsOffset ) {
			$element.addClass( settings.classes.stickyEffects );

			isReachedEffectsPoint = true;
		}
	};

	var checkPosition = function() {
		var offset = settings.offset,
			distanceFromTriggerPoint;

		if ( isSticky ) {
			var spacerViewportOffset = getElementViewportOffset( elements.$spacer );

			distanceFromTriggerPoint = 'top' === settings.to ? spacerViewportOffset.top.fromTop - offset : -spacerViewportOffset.bottom.fromBottom - offset;

			if ( settings.parent ) {
				checkParent();
			}

			if ( distanceFromTriggerPoint > 0 ) {
				unstick();
			}
		} else {
			var elementViewportOffset = getElementViewportOffset( $element );

			distanceFromTriggerPoint = 'top' === settings.to ? elementViewportOffset.top.fromTop - offset : -elementViewportOffset.bottom.fromBottom - offset;

			if ( distanceFromTriggerPoint <= 0 ) {
				stick();

				if ( settings.parent ) {
					checkParent();
				}
			}
		}

		checkEffectsPoint( distanceFromTriggerPoint );
	};

	var onWindowScroll = function() {
		checkPosition();
	};

	var onWindowResize = function() {
		if ( ! isSticky ) {
			return;
		}

		unstickElement();

		stickElement();
	};

	this.destroy = function() {
		if ( isSticky ) {
			unstick();
		}

		unbindEvents();

		$element.removeClass( settings.classes.sticky );
	};

	init();
};

$.fn.sticky = function( settings ) {
	var isCommand = 'string' === typeof settings;

	this.each( function() {
		var $this = $( this );

		if ( ! isCommand ) {
			$this.data( 'sticky', new Sticky( this, settings ) );

			return;
		}

		var instance = $this.data( 'sticky' );

		if ( ! instance ) {
			throw Error( 'Trying to perform the `' + settings + '` method prior to initialization' );
		}

		if ( ! instance[ settings ] ) {
			throw ReferenceError( 'Method `' + settings + '` not found in sticky instance' );
		}

		instance[ settings ].apply( instance, Array.prototype.slice.call( arguments, 1 ) );

		if ( 'destroy' === settings ) {
			$this.removeData( 'sticky' );
		}
	} );

	return this;
};

var StickyHandler = elementorModules.frontend.handlers.Base.extend({

	bindEvents: function bindEvents() {
		ceFrontend.addListenerOnce(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
	},

	unbindEvents: function unbindEvents() {
		ceFrontend.removeListeners(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
	},

	isActive: function isActive() {
		return undefined !== this.$element.data('sticky');
	},

	activate: function activate() {
		var elementSettings = this.getElementSettings(),
			stickyOptions = {
			to: elementSettings.sticky,
			offset: elementSettings.sticky_offset,
			effectsOffset: elementSettings.sticky_effects_offset,
			classes: {
				sticky: 'elementor-sticky',
				stickyActive: 'elementor-sticky--active elementor-section--handles-inside',
				stickyEffects: 'elementor-sticky--effects',
				spacer: 'elementor-sticky__spacer'
			}
		},
			$wpAdminBar = ceFrontend.elements.$wpAdminBar;

		if (elementSettings.sticky_parent) {
			stickyOptions.parent = '.elementor-widget-wrap';
		}

		if ($wpAdminBar.length && 'top' === elementSettings.sticky && 'fixed' === $wpAdminBar.css('position')) {
			stickyOptions.offset += $wpAdminBar.height();
		}

		this.$element.sticky(stickyOptions);
	},

	deactivate: function deactivate() {
		if (!this.isActive()) {
			return;
		}

		this.$element.sticky('destroy');
	},

	run: function run(refresh) {
		if (!this.getElementSettings('sticky')) {
			this.deactivate();

			return;
		}

		var currentDeviceMode = ceFrontend.getCurrentDeviceMode(),
			activeDevices = this.getElementSettings('sticky_on');

		if (-1 !== activeDevices.indexOf(currentDeviceMode)) {
			if (true === refresh) {
				this.reactivate();
			} else if (!this.isActive()) {
				this.activate();
			}
		} else {
			this.deactivate();
		}
	},

	reactivate: function reactivate() {
		this.deactivate();

		this.activate();
	},

	onElementChange: function onElementChange(settingKey) {
		if (-1 !== ['sticky', 'sticky_on'].indexOf(settingKey)) {
			this.run(true);
		}

		if (-1 !== ['sticky_offset', 'sticky_effects_offset', 'sticky_parent'].indexOf(settingKey)) {
			this.reactivate();
		}
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.run();
	},

	onDestroy: function onDestroy() {
		elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);

		this.deactivate();
	}
});

module.exports = function ($scope) {
	new StickyHandler({ $element: $scope });
};

/***/ }),

/***/ 15:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_elementorModules$Mod) {
	_inherits(_class, _elementorModules$Mod);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'get',
		value: function get(key, options) {
			options = options || {};

			var storage = void 0;

			try {
				storage = options.session ? sessionStorage : localStorage;
			} catch (e) {
				return key ? undefined : {};
			}

			var elementorStorage = storage.getItem('elementor');

			if (elementorStorage) {
				elementorStorage = JSON.parse(elementorStorage);
			} else {
				elementorStorage = {};
			}

			if (!elementorStorage.__expiration) {
				elementorStorage.__expiration = {};
			}

			var expiration = elementorStorage.__expiration;

			var expirationToCheck = [];

			if (key) {
				if (expiration[key]) {
					expirationToCheck = [key];
				}
			} else {
				expirationToCheck = Object.keys(expiration);
			}

			var entryExpired = false;

			expirationToCheck.forEach(function (expirationKey) {
				if (new Date(expiration[expirationKey]) < new Date()) {
					delete elementorStorage[expirationKey];

					delete expiration[expirationKey];

					entryExpired = true;
				}
			});

			if (entryExpired) {
				this.save(elementorStorage, options.session);
			}

			if (key) {
				return elementorStorage[key];
			}

			return elementorStorage;
		}
	}, {
		key: 'set',
		value: function set(key, value, options) {
			options = options || {};

			var elementorStorage = this.get(null, options);

			elementorStorage[key] = value;

			if (options.lifetimeInSeconds) {
				var date = new Date();

				date.setTime(date.getTime() + options.lifetimeInSeconds * 1000);

				elementorStorage.__expiration[key] = date.getTime();
			}

			this.save(elementorStorage, options.session);
		}
	}, {
		key: 'save',
		value: function save(object, session) {
			var storage = void 0;

			try {
				storage = session ? sessionStorage : localStorage;
			} catch (e) {
				return;
			}

			storage.setItem('elementor', JSON.stringify(object));
		}
	}]);

	return _class;
}(elementorModules.Module);

exports.default = _class;

/***/ }),

/***/ 16:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _environment = __webpack_require__(1);

var _environment2 = _interopRequireDefault(_environment);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var HotKeys = function () {
	function HotKeys() {
		_classCallCheck(this, HotKeys);

		this.hotKeysHandlers = {};
	}

	_createClass(HotKeys, [{
		key: 'applyHotKey',
		value: function applyHotKey(event) {
			var handlers = this.hotKeysHandlers[event.which];

			if (!handlers) {
				return;
			}

			jQuery.each(handlers, function (key, handler) {
				if (handler.isWorthHandling && !handler.isWorthHandling(event)) {
					return;
				}

				// Fix for some keyboard sources that consider alt key as ctrl key
				if (!handler.allowAltKey && event.altKey) {
					return;
				}

				event.preventDefault();

				handler.handle(event);
			});
		}
	}, {
		key: 'isControlEvent',
		value: function isControlEvent(event) {
			return event[_environment2.default.mac ? 'metaKey' : 'ctrlKey'];
		}
	}, {
		key: 'addHotKeyHandler',
		value: function addHotKeyHandler(keyCode, handlerName, handler) {
			if (!this.hotKeysHandlers[keyCode]) {
				this.hotKeysHandlers[keyCode] = {};
			}

			this.hotKeysHandlers[keyCode][handlerName] = handler;
		}
	}, {
		key: 'bindListener',
		value: function bindListener($listener) {
			$listener.on('keydown', this.applyHotKey.bind(this));
		}
	}]);

	return HotKeys;
}();

exports.default = HotKeys;

/***/ }),

/***/ 17:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_elementorModules$Vie) {
	_inherits(_class, _elementorModules$Vie);

	function _class() {
		_classCallCheck(this, _class);

		return _possibleConstructorReturn(this, (_class.__proto__ || Object.getPrototypeOf(_class)).apply(this, arguments));
	}

	_createClass(_class, [{
		key: 'getDefaultSettings',
		value: function getDefaultSettings() {
			return {
				selectors: {
					elements: '.elementor-element',
					nestedDocumentElements: '.elementor .elementor-element'
				},
				classes: {
					editMode: 'elementor-edit-mode'
				}
			};
		}
	}, {
		key: 'getDefaultElements',
		value: function getDefaultElements() {
			var selectors = this.getSettings('selectors');

			return {
				$elements: this.$element.find(selectors.elements).not(this.$element.find(selectors.nestedDocumentElements))
			};
		}
	}, {
		key: 'getDocumentSettings',
		value: function getDocumentSettings(setting) {
			var elementSettings = void 0;

			if (this.isEdit) {
				elementSettings = {};

				var settings = elementor.settings.page.model;

				jQuery.each(settings.getActiveControls(), function (controlKey) {
					elementSettings[controlKey] = settings.attributes[controlKey];
				});
			} else {
				elementSettings = this.$element.data('elementor-settings') || {};
			}

			return this.getItems(elementSettings, setting);
		}
	}, {
		key: 'runElementsHandlers',
		value: function runElementsHandlers() {
			this.elements.$elements.each(function (index, element) {
				return ceFrontend.elementsHandler.runReadyTrigger(element);
			});
		}
	}, {
		key: 'onInit',
		value: function onInit() {
			this.$element = this.getSettings('$element');

			_get(_class.prototype.__proto__ || Object.getPrototypeOf(_class.prototype), 'onInit', this).call(this);

			this.isEdit = this.$element.hasClass(this.getSettings('classes.editMode'));

			if (this.isEdit) {
				elementor.settings.page.model.on('change', this.onSettingsChange.bind(this));
			} else {
				this.runElementsHandlers();
			}
		}
	}, {
		key: 'onSettingsChange',
		value: function onSettingsChange() {}
	}]);

	return _class;
}(elementorModules.ViewModule);

exports.default = _class;

/***/ }),

/***/ 18:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = elementorModules.frontend.handlers.Base.extend({
	$activeContent: null,

	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				tabTitle: '.elementor-nav-item',
				tabContent: '.elementor-tab-content'
			},
			classes: {
				active: 'elementor-active'
			},
			showTabFn: 'show',
			hideTabFn: 'hide',
			toggleSelf: true,
			hidePrevious: true,
			autoExpand: true
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$tabTitles: this.findElement(selectors.tabTitle),
			$tabContents: this.findElement(selectors.tabContent)
		};
	},

	activateDefaultTab: function activateDefaultTab() {
		var settings = this.getSettings();

		if (!settings.autoExpand || 'editor' === settings.autoExpand && !this.isEdit) {
			return;
		}

		var defaultActiveTab = this.getEditSettings('activeItemIndex') || 1,
			originalToggleMethods = {
			showTabFn: settings.showTabFn,
			hideTabFn: settings.hideTabFn
		};

		// Toggle tabs without animation to avoid jumping
		this.setSettings({
			showTabFn: 'show',
			hideTabFn: 'hide'
		});

		this.changeActiveTab(defaultActiveTab);

		// Return back original toggle effects
		this.setSettings(originalToggleMethods);
	},

	deactivateActiveTab: function deactivateActiveTab(tabIndex) {
		var settings = this.getSettings(),
			activeClass = settings.classes.active,
			activeFilter = tabIndex ? '[data-tab="' + tabIndex + '"]' : '.' + activeClass,
			$activeTitle = this.elements.$tabTitles.filter(activeFilter),
			$activeContent = this.elements.$tabContents.filter(activeFilter);

		$activeTitle.add($activeContent).removeClass(activeClass);

		$activeContent[settings.hideTabFn]();
	},

	activateTab: function activateTab(tabIndex) {
		var settings = this.getSettings(),
			activeClass = settings.classes.active,
			$requestedTitle = this.elements.$tabTitles.filter('[data-tab="' + tabIndex + '"]'),
			$requestedContent = this.elements.$tabContents.filter('[data-tab="' + tabIndex + '"]');

		$requestedTitle.add($requestedContent).addClass(activeClass);

		$requestedContent[settings.showTabFn]();
	},

	isActiveTab: function isActiveTab(tabIndex) {
		return this.elements.$tabTitles.filter('[data-tab="' + tabIndex + '"]').hasClass(this.getSettings('classes.active'));
	},

	bindEvents: function bindEvents() {
		var _this = this;

		this.elements.$tabTitles.on({
			keydown: function keydown(event) {
				if ('Enter' === event.key) {
					event.preventDefault();

					_this.changeActiveTab(event.currentTarget.getAttribute('data-tab'));
				}
			},
			click: function click(event) {
				event.preventDefault();

				_this.changeActiveTab(event.currentTarget.getAttribute('data-tab'));
			}
		});
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.activateDefaultTab();
	},

	onEditSettingsChange: function onEditSettingsChange(propertyName) {
		if ('activeItemIndex' === propertyName) {
			this.activateDefaultTab();
		}
	},

	changeActiveTab: function changeActiveTab(tabIndex) {
		var isActiveTab = this.isActiveTab(tabIndex),
			settings = this.getSettings();

		if ((settings.toggleSelf || !isActiveTab) && settings.hidePrevious) {
			this.deactivateActiveTab();
		}

		if (!settings.hidePrevious && isActiveTab) {
			this.deactivateActiveTab(tabIndex);
		}

		if (!isActiveTab) {
			this.activateTab(tabIndex);
		}
	}
});

/***/ }),

/***/ 100:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Countdown = function( $countdown, endTime, actions, $expireMessage, $ ) {
	var timeInterval,
		elements = {
			$daysSpan: $countdown.find( '.elementor-countdown-days' ),
			$hoursSpan: $countdown.find( '.elementor-countdown-hours' ),
			$minutesSpan: $countdown.find( '.elementor-countdown-minutes' ),
			$secondsSpan: $countdown.find( '.elementor-countdown-seconds' )
		};

	var updateClock = function() {
		var timeRemaining = Countdown.getTimeRemaining( endTime );

		$.each( timeRemaining.parts, function( timePart ) {
			var $element = elements[ '$' + timePart + 'Span' ],
				partValue = this.toString();

			if ( 1 === partValue.length ) {
				partValue = 0 + partValue;
			}

			if ( $element.length ) {
				$element.text( partValue );
			}
		} );

		if ( timeRemaining.total <= 0 ) {
			clearInterval( timeInterval );
			runActions();
		}
	};

	var initializeClock = function() {
		timeInterval = setInterval( updateClock, 1000 );

		updateClock();
	};

	var runActions = function() {
		$countdown.trigger( 'countdown_expire', $countdown );

		if ( !actions ) {
			return;
		}

		actions.forEach( function(action) {
			switch ( action.type ) {
				case 'hide':
					$countdown.hide();
					break;

				case 'redirect':
					if ( action.redirect_url && !ceFrontend.isEditMode() ) {
						action.redirect_is_external
							? window.open(action.redirect_url)
							: window.location.href = action.redirect_url
						;
					}
					break;

				case 'message':
					$expireMessage.show();
					break;
			}
		} );
	};

	initializeClock();
};

Countdown.getTimeRemaining = function( endTime ) {
	var timeRemaining = endTime - new Date(),
		seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
		minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
		hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
		days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

	if ( days < 0 || hours < 0 || minutes < 0 ) {
		seconds = minutes = hours = days = 0;
	}

	return {
		total: timeRemaining,
		parts: {
			days: days,
			hours: hours,
			minutes: minutes,
			seconds: seconds
		}
	};
};

module.exports = function( $scope, $ ) {
	var $element = $scope.find( '.elementor-countdown-wrapper' ),
		date = new Date( $element.data( 'date' ) * 1000 ),
		actions = $element.data( 'expire-actions' ),
		$expireMessage = $scope.find( '.elementor-countdown-expire--message' );

	new Countdown( $element, date, actions, $expireMessage, $ );
};

/***/ }),

/***/ 123:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var AnimatedHeadlineHandler = elementorModules.frontend.handlers.Base.extend({
	svgPaths: {
		circle: ['M325,18C228.7-8.3,118.5,8.3,78,21C22.4,38.4,4.6,54.6,5.6,77.6c1.4,32.4,52.2,54,142.6,63.7 c66.2,7.1,212.2,7.5,273.5-8.3c64.4-16.6,104.3-57.6,33.8-98.2C386.7-4.9,179.4-1.4,126.3,20.7'],
		underline_zigzag: ['M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9'],
		x: ['M497.4,23.9C301.6,40,155.9,80.6,4,144.4', 'M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7'],
		strikethrough: ['M3,75h493.5'],
		curly: ['M3,146.1c17.1-8.8,33.5-17.8,51.4-17.8c15.6,0,17.1,18.1,30.2,18.1c22.9,0,36-18.6,53.9-18.6 c17.1,0,21.3,18.5,37.5,18.5c21.3,0,31.8-18.6,49-18.6c22.1,0,18.8,18.8,36.8,18.8c18.8,0,37.5-18.6,49-18.6c20.4,0,17.1,19,36.8,19 c22.9,0,36.8-20.6,54.7-18.6c17.7,1.4,7.1,19.5,33.5,18.8c17.1,0,47.2-6.5,61.1-15.6'],
		diagonal: ['M13.5,15.5c131,13.7,289.3,55.5,475,125.5'],
		double: ['M8.4,143.1c14.2-8,97.6-8.8,200.6-9.2c122.3-0.4,287.5,7.2,287.5,7.2', 'M8,19.4c72.3-5.3,162-7.8,216-7.8c54,0,136.2,0,267,7.8'],
		double_underline: ['M5,125.4c30.5-3.8,137.9-7.6,177.3-7.6c117.2,0,252.2,4.7,312.7,7.6', 'M26.9,143.8c55.1-6.1,126-6.3,162.2-6.1c46.5,0.2,203.9,3.2,268.9,6.4'],
		underline: ['M7.7,145.6C109,125,299.9,116.2,401,121.3c42.1,2.2,87.6,11.8,87.3,25.7']
	},

	getDefaultSettings: function getDefaultSettings() {
		var settings = {
			animationDelay: 2500,
			//letters effect
			lettersDelay: 50,
			//typing effect
			typeLettersDelay: 150,
			selectionDuration: 500,
			//clip effect
			revealDuration: 600,
			revealAnimationDelay: 1500
		};

		settings.typeAnimationDelay = settings.selectionDuration + 800;

		settings.selectors = {
			headline: '.elementor-headline',
			dynamicWrapper: '.elementor-headline-dynamic-wrapper'
		};

		settings.classes = {
			dynamicText: 'elementor-headline-dynamic-text',
			dynamicLetter: 'elementor-headline-dynamic-letter',
			textActive: 'elementor-headline-text-active',
			textInactive: 'elementor-headline-text-inactive',
			letters: 'elementor-headline-letters',
			animationIn: 'elementor-headline-animation-in',
			typeSelected: 'elementor-headline-typing-selected'
		};

		return settings;
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$headline: this.$element.find(selectors.headline),
			$dynamicWrapper: this.$element.find(selectors.dynamicWrapper)
		};
	},

	getNextWord: function getNextWord($word) {
		return $word.is(':last-child') ? $word.parent().children().eq(0) : $word.next();
	},

	switchWord: function switchWord($oldWord, $newWord) {
		$oldWord.removeClass('elementor-headline-text-active').addClass('elementor-headline-text-inactive');

		$newWord.removeClass('elementor-headline-text-inactive').addClass('elementor-headline-text-active');
	},

	singleLetters: function singleLetters() {
		var classes = this.getSettings('classes');

		this.elements.$dynamicText.each(function () {
			var $word = jQuery(this),
			    letters = $word.text().split(''),
			    isActive = $word.hasClass(classes.textActive);

			$word.empty();

			letters.forEach(function (letter) {
				var $letter = jQuery('<span>', { class: classes.dynamicLetter }).text(letter);

				if (isActive) {
					$letter.addClass(classes.animationIn);
				}

				$word.append($letter);
			});

			$word.css('opacity', 1);
		});
	},

	showLetter: function showLetter($letter, $word, bool, duration) {
		var self = this,
		    classes = this.getSettings('classes');

		$letter.addClass(classes.animationIn);

		if (!$letter.is(':last-child')) {
			setTimeout(function () {
				self.showLetter($letter.next(), $word, bool, duration);
			}, duration);
		} else if (!bool) {
			setTimeout(function () {
				self.hideWord($word);
			}, self.getSettings('animationDelay'));
		}
	},

	hideLetter: function hideLetter($letter, $word, bool, duration) {
		var self = this,
		    settings = this.getSettings();

		$letter.removeClass(settings.classes.animationIn);

		if (!$letter.is(':last-child')) {
			setTimeout(function () {
				self.hideLetter($letter.next(), $word, bool, duration);
			}, duration);
		} else if (bool) {
			setTimeout(function () {
				self.hideWord(self.getNextWord($word));
			}, self.getSettings('animationDelay'));
		}
	},

	showWord: function showWord($word, $duration) {
		var self = this,
		    settings = self.getSettings(),
		    animationType = self.getElementSettings('animation_type');

		if ('typing' === animationType) {
			self.showLetter($word.find('.' + settings.classes.dynamicLetter).eq(0), $word, false, $duration);

			$word.addClass(settings.classes.textActive).removeClass(settings.classes.textInactive);
		} else if ('clip' === animationType) {
			self.elements.$dynamicWrapper.animate({ width: $word.width() + 10 }, settings.revealDuration, function () {
				setTimeout(function () {
					self.hideWord($word);
				}, settings.revealAnimationDelay);
			});
		}
	},

	hideWord: function hideWord($word) {
		var self = this,
		    settings = self.getSettings(),
		    classes = settings.classes,
		    letterSelector = '.' + classes.dynamicLetter,
		    animationType = self.getElementSettings('animation_type'),
		    nextWord = self.getNextWord($word);

		if ('typing' === animationType) {
			self.elements.$dynamicWrapper.addClass(classes.typeSelected);

			setTimeout(function () {
				self.elements.$dynamicWrapper.removeClass(classes.typeSelected);

				$word.addClass(settings.classes.textInactive).removeClass(classes.textActive).children(letterSelector).removeClass(classes.animationIn);
			}, settings.selectionDuration);
			setTimeout(function () {
				self.showWord(nextWord, settings.typeLettersDelay);
			}, settings.typeAnimationDelay);
		} else if (self.elements.$headline.hasClass(classes.letters)) {
			var bool = $word.children(letterSelector).length >= nextWord.children(letterSelector).length;

			self.hideLetter($word.find(letterSelector).eq(0), $word, bool, settings.lettersDelay);

			self.showLetter(nextWord.find(letterSelector).eq(0), nextWord, bool, settings.lettersDelay);
		} else if ('clip' === animationType) {
			self.elements.$dynamicWrapper.animate({ width: '2px' }, settings.revealDuration, function () {
				self.switchWord($word, nextWord);
				self.showWord(nextWord);
			});
		} else {
			self.switchWord($word, nextWord);

			setTimeout(function () {
				self.hideWord(nextWord);
			}, settings.animationDelay);
		}
	},

	animateHeadline: function animateHeadline() {
		var self = this,
		    animationType = self.getElementSettings('animation_type'),
		    $dynamicWrapper = self.elements.$dynamicWrapper;

		if ('clip' === animationType) {
			$dynamicWrapper.width($dynamicWrapper.width() + 10);
		} else if ('typing' !== animationType) {
			//assign to .elementor-headline-dynamic-wrapper the width of its longest word
			var width = 0;

			self.elements.$dynamicText.each(function () {
				var wordWidth = jQuery(this).width();

				if (wordWidth > width) {
					width = wordWidth;
				}
			});

			$dynamicWrapper.css('width', width);
		}

		//trigger animation
		setTimeout(function () {
			self.hideWord(self.elements.$dynamicText.eq(0));
		}, self.getSettings('animationDelay'));
	},

	getSvgPaths: function getSvgPaths(pathName) {
		var pathsInfo = this.svgPaths[pathName],
		    $paths = jQuery();

		pathsInfo.forEach(function (pathInfo) {
			$paths = $paths.add(jQuery('<path>', { d: pathInfo }));
		});

		return $paths;
	},

	fillWords: function fillWords() {
		var elementSettings = this.getElementSettings(),
		    classes = this.getSettings('classes'),
		    $dynamicWrapper = this.elements.$dynamicWrapper;

		if ('rotate' === elementSettings.headline_style) {
			var rotatingText = (elementSettings.rotating_text || '').split('\n');

			rotatingText.forEach(function (word, index) {
				var $dynamicText = jQuery('<span>', { class: classes.dynamicText }).html(word.replace(/ /g, '&nbsp;'));

				if (!index) {
					$dynamicText.addClass(classes.textActive);
				}

				$dynamicWrapper.append($dynamicText);
			});
		} else {
			var $dynamicText = jQuery('<span>', { class: classes.dynamicText + ' ' + classes.textActive }).text(elementSettings.highlighted_text),
			    $svg = jQuery('<svg>', {
				xmlns: 'http://www.w3.org/2000/svg',
				viewBox: '0 0 500 150',
				preserveAspectRatio: 'none'
			}).html(this.getSvgPaths(elementSettings.marker));

			$dynamicWrapper.append($dynamicText, $svg[0].outerHTML);
		}

		this.elements.$dynamicText = $dynamicWrapper.children('.' + classes.dynamicText);
	},

	rotateHeadline: function rotateHeadline() {
		var settings = this.getSettings();

		//insert <span> for each letter of a changing word
		if (this.elements.$headline.hasClass(settings.classes.letters)) {
			this.singleLetters();
		}

		//initialise headline animation
		this.animateHeadline();
	},

	initHeadline: function initHeadline() {
		if ('rotate' === this.getElementSettings('headline_style')) {
			this.rotateHeadline();
		}
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.fillWords();

		this.initHeadline();
	}
});

module.exports = function( $scope, $ ) {
	// elementorFrontend.hooks.addAction('frontend/element_ready/animated-headline.default', __webpack_require__(97));
	new AnimatedHeadlineHandler({ $element: $scope });
};

/***/ }),
/***/ 124:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ShoppingCart = elementorModules.frontend.handlers.Base.extend({

	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				container: '.elementor-cart__container',
				toggle: '.elementor-cart__toggle .elementor-button',
				closeButton: '.elementor-cart__close-button'
			},
			classes: {
				isShown: 'elementor-cart--shown',
				lightbox: 'elementor-lightbox',
				isHidden: 'elementor-cart-hidden'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors'),
			elements = {};

		elements.$container = this.$element.find(selectors.container);
		elements.$toggle = this.$element.find(selectors.toggle);
		elements.$closeButton = this.$element.find(selectors.closeButton);

		return elements;
	},

	bindEvents: function bindEvents() {
		var self = this,
			$ = jQuery,
			$container = self.elements.$container,
			$closeButton = self.elements.$closeButton,
			classes = this.getSettings('classes');

		// Activate topbar mode on click
		self.elements.$toggle.on('click', function (event) {
			if (!self.elements.$toggle.hasClass(classes.isHidden)) {
				event.preventDefault();
				$container.toggleClass(classes.isShown);
			}
		});

		// Deactivate topbar mode on click or on esc.
		$container.on('click', function (event) {
			if ($container.hasClass(classes.isShown) && $container[0] === event.target) {
				$container.removeClass(classes.isShown);
			}
		});

		$closeButton.on('click', function () {
			$container.removeClass(classes.isShown);
		});

		ceFrontend.elements.$document.keyup(function (event) {
			var ESC_KEY = 27;

			if (ESC_KEY === event.keyCode) {
				if ($container.hasClass(classes.isShown)) {
					$container.click();
				}
			}
		});

		$container.on('click', '.elementor-cart__product-remove a', function (event) {
			var dataset = $(this).data();
				dataset.linkAction = 'delete-from-cart';

			$(this).closest('.elementor-cart__product').addClass('ce-disabled');

			event.preventDefault();

			$.ajax({
				url: this.href,
				method: 'POST',
				dataType: 'json',
				data: {
					ajax: 1,
					action: 'update',
				},
			}).then(function (resp) {
				if (!resp.success || !resp.cart) {
					return;
				}

				prestashop.emit('updateCart', {
					reason: dataset,
					resp: resp,
				});
			}).fail(function (resp) {
				prestashop.emit('handleError', {
					eventType: 'updateProductInCart',
					resp: resp,
					cartAction: dataset.linkAction,
				});
			});
		});

		prestashop.on('updateCart', function(data) {
			if (!data || !data.resp || !data.resp.cart) {
				return;
			}
			var cart = data.resp.cart,
				gift = $container.find('.elementor-cart__products').data('gift'),
				$products = $();

			// Show ps_shoppingcart modal on update
			if (self.getElementSettings('action_show_modal') && 'add-to-cart' === data.reason.linkAction && !data.resp.hasError) {
				ShoppingCart.xhr && ShoppingCart.xhr.abort();
				ShoppingCart.xhr = $.post(
					self.getElementSettings('modal_url'),
					{
						ajax: true,
						action: 'addToCartModal',
						id_product: data.reason.idProduct,
						id_product_attribute: data.reason.idProductAttribute,
						id_customization: data.reason.idCustomization,
					},
					function (resp) {
						$('#blockcart-modal').remove();
						$(document.body).append(resp.modal).children('#blockcart-modal').modal();
					},
					'json'
				);
			}

			// Update toggle
			self.elements.$toggle.find('.elementor-button-text')
				.html(cart['subtotals']['products']['value'])
			;
			self.elements.$toggle.find('.elementor-button-icon')
				.attr('data-counter', cart['products_count'])
				.data('counter', cart['products_count'])
			;
			// Update products
			cart.products.forEach(function (product) {
				var $prod = $(
						'<div class="elementor-cart__product">' +
							'<div class="elementor-cart__product-image"/>' +
							'<div class="elementor-cart__product-name">' +
								'<div class="elementor-cart__product-attrs"/>' +
							'</div>' +
							'<div class="elementor-cart__product-price"/>' +
							'<div class="elementor-cart__product-remove ceicon-times"/>' +
						'</div>'
					),
					$attrs = $prod.find('.elementor-cart__product-attrs');

				$('<img>').appendTo($prod.find('.elementor-cart__product-image')).attr({
					src: product['cover']['bySize']['cart_default']['url'],
					alt: product['name'],
				});
				$('<a>').prependTo($prod.find('.elementor-cart__product-name'))
					.attr('href', product['url'])
					.html(product['name'])
				;
				// Add product attributes
				for (var label in product['attributes']) {
					$('<div class="elementor-cart__product-attr">').html(
						'<span class="elementor-cart__product-attr-label">' + label + ':</span> ' +
						'<span class="elementor-cart__product-attr-value">' + product['attributes'][label] + '</span>'
					).appendTo($attrs);
				}
				// Add product customizations
				product['customizations'].forEach(function (customization) {
					customization.fields.forEach(function (field) {
						$('<div class="elementor-cart__product-attr">').html(
							'<span class="elementor-cart__product-attr-label">' + field['label'] + ':</span> ' +
							'<span class="elementor-cart__product-attr-value">' +
								('image' === field['type'] ? $('<img>').attr('src', field['image']['small']['url'])[0].outerHTML : field['text']) +
							'</span>'
						).appendTo($attrs);
					});
				});
				$prod.find('.elementor-cart__product-price').html(
					'<span class="elementor-cart__product-quantity">' + product['quantity'] + '</span> &times; ' + (product['is_gift'] ? gift : product['price']) + ' '
				).append(product['has_discount'] ? $('<del>').html(product['regular_price']) : []);

				$('<a>').appendTo($prod.find('.elementor-cart__product-remove')).attr({
					href: product['remove_from_cart_url'],
					rel: 'nofollow',
					'data-id-product': product['id_product'],
					'data-id-product-attribute': product['id_product_attribute'],
					'data-id-customization': product['id_customization'],
				}).data({
					'idProduct': product['id_product'],
					'idProductAttribute': product['id_product_attribute'],
					'idCustomization': product['id_customization'],
				});
				$products.push($prod[0]);
			});
			// Update cart
			$container.find('.elementor-cart__products')
				.empty()
				.append($products)
			;
			$container.find('.elementor-cart__empty-message')
				.toggleClass('elementor-hidden', !!cart['products_count'])
			;
			$container.find('.elementor-cart__summary').html(
				'<div class="elementor-cart__summary-label">' + cart['summary_string'] + '</div>' +
				'<div class="elementor-cart__summary-value">' + cart['subtotals']['products']['value'] + '</div>' +
				'<span class="elementor-cart__summary-label">' + cart['subtotals']['shipping']['label'] + '</span>' +
				'<span class="elementor-cart__summary-value">' + cart['subtotals']['shipping']['value'] + '</span>' +
				'<strong class="elementor-cart__summary-label">' + cart['totals']['total']['label'] + '</strong>' +
				'<strong class="elementor-cart__summary-value">' + cart['totals']['total']['value'] + '</strong>'
			);
			$container.find('.elementor-alert-warning')
				.toggleClass('elementor-hidden', !cart['minimalPurchaseRequired'])
				.html('<span class="elementor-alert-description">' + cart['minimalPurchaseRequired'] + '</span>');
			;
			$container.find('.elementor-button--checkout')
				.toggleClass('ce-disabled', cart['minimalPurchaseRequired'] || !cart['products_count'])
			;

			// Open shopping cart after updated
			if (self.getElementSettings('action_open_cart')) {
				self.elements.$container.hasClass(classes.isShown) || self.elements.$toggle.triggerHandler('click');
			}
		});
	}
});

module.exports = function ($scope) {
	new ShoppingCart({ $element: $scope });
};

/***/ }),

/***/ 182:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _documentsManager = __webpack_require__(183);

var _documentsManager2 = _interopRequireDefault(_documentsManager);

var _hotKeys = __webpack_require__(16);

var _hotKeys2 = _interopRequireDefault(_hotKeys);

var _storage = __webpack_require__(15);

var _storage2 = _interopRequireDefault(_storage);

var _environment = __webpack_require__(1);

var _environment2 = _interopRequireDefault(_environment);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /* global ceFrontendConfig */


var EventManager = __webpack_require__(13),
	ElementsHandler = __webpack_require__(184),
	YouTubeModule = __webpack_require__(196),
	AnchorsModule = __webpack_require__(197),
	LightboxModule = __webpack_require__(198);

var Frontend = function (_elementorModules$Vie) {
	_inherits(Frontend, _elementorModules$Vie);

	function Frontend() {
		var _ref;

		_classCallCheck(this, Frontend);

		for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
			args[_key] = arguments[_key];
		}

		var _this = _possibleConstructorReturn(this, (_ref = Frontend.__proto__ || Object.getPrototypeOf(Frontend)).call.apply(_ref, [this].concat(args)));

		_this.config = ceFrontendConfig;
		return _this;
	}

	// TODO: BC since 2.5.0


	_createClass(Frontend, [{
		key: 'getDefaultSettings',
		value: function getDefaultSettings() {
			return {
				selectors: {
					elementor: '.elementor',
					adminBar: '#wpadminbar'
				},
				classes: {
					ie: 'elementor-msie'
				}
			};
		}
	}, {
		key: 'getDefaultElements',
		value: function getDefaultElements() {
			var defaultElements = {
				window: window,
				$window: jQuery(window),
				$document: jQuery(document),
				$head: jQuery(document.head),
				$body: jQuery(document.body),
				$deviceMode: jQuery('<span>', { id: 'elementor-device-mode', class: 'elementor-screen-only' })
			};
			defaultElements.$body.append(defaultElements.$deviceMode);

			return defaultElements;
		}
	}, {
		key: 'bindEvents',
		value: function bindEvents() {
			var _this2 = this;

			this.elements.$window.on('resize', function () {
				return _this2.setDeviceModeData();
			});
		}

		/**
   * @deprecated 2.4.0 Use just `this.elements` instead
   */

	}, {
		key: 'getElements',
		value: function getElements(elementName) {
			return this.getItems(this.elements, elementName);
		}

		/**
   * @deprecated 2.4.0 This method was never in use
   */

	}, {
		key: 'getPageSettings',
		value: function getPageSettings(settingName) {
			var settingsObject = this.isEditMode() ? elementor.settings.page.model.attributes : this.config.settings.page;

			return this.getItems(settingsObject, settingName);
		}
	}, {
		key: 'getGeneralSettings',
		value: function getGeneralSettings(settingName) {
			var settingsObject = this.isEditMode() ? elementor.settings.general.model.attributes : this.config.settings.general;

			return this.getItems(settingsObject, settingName);
		}
	}, {
		key: 'getCurrentDeviceMode',
		value: function getCurrentDeviceMode() {
			return getComputedStyle(this.elements.$deviceMode[0], ':after').content.replace(/"/g, '');
		}
	}, {
		key: 'getCurrentDeviceSetting',
		value: function getCurrentDeviceSetting(settings, settingKey) {
			var devices = ['desktop', 'tablet', 'mobile'],
				currentDeviceMode = ceFrontend.getCurrentDeviceMode();

			var currentDeviceIndex = devices.indexOf(currentDeviceMode);

			while (currentDeviceIndex > 0) {
				var currentDevice = devices[currentDeviceIndex],
					fullSettingKey = settingKey + '_' + currentDevice,
					deviceValue = settings[fullSettingKey];

				if (deviceValue) {
					return deviceValue;
				}

				currentDeviceIndex--;
			}

			return settings[settingKey];
		}
	}, {
		key: 'isEditMode',
		value: function isEditMode() {
			return this.config.environmentMode.edit;
		}
	}, {
		key: 'isWPPreviewMode',
		value: function isWPPreviewMode() {
			return this.config.environmentMode.wpPreview;
		}
	}, {
		key: 'initDialogsManager',
		value: function initDialogsManager() {
			var dialogsManager = void 0;

			this.getDialogsManager = function () {
				if (!dialogsManager) {
					dialogsManager = new DialogsManager.Instance();
				}

				return dialogsManager;
			};
		}
	}, {
		key: 'initHotKeys',
		value: function initHotKeys() {
			this.hotKeys = new _hotKeys2.default();

			this.hotKeys.bindListener(this.elements.$window);
		}
	}, {
		key: 'initOnReadyComponents',
		value: function initOnReadyComponents() {
			this.utils = {
				youtube: new YouTubeModule(),
				anchors: new AnchorsModule(),
				lightbox: new LightboxModule()
			};

			// TODO: BC since 2.4.0
			this.modules = {
				StretchElement: elementorModules.frontend.tools.StretchElement,
				Masonry: elementorModules.utils.Masonry
			};

			this.elementsHandler = new ElementsHandler(jQuery);

			this.documentsManager = new _documentsManager2.default();

			this.trigger('components:init');
		}
	}, {
		key: 'initOnReadyElements',
		value: function initOnReadyElements() {
			this.elements.$wpAdminBar = this.elements.$document.find(this.getSettings('selectors.adminBar'));
		}
	}, {
		key: 'addIeCompatibility',
		value: function addIeCompatibility() {
			var el = document.createElement('div'),
				supportsGrid = 'string' === typeof el.style.grid;

			if (!_environment2.default.ie && supportsGrid) {
				return;
			}

			this.elements.$body.addClass(this.getSettings('classes.ie'));

			var msieCss = '<link rel="stylesheet" id="elementor-frontend-css-msie" href="' + this.config.urls.assets + 'css/frontend-msie.min.css?' + this.config.version + '" type="text/css" />';

			this.elements.$body.append(msieCss);
		}
	}, {
		key: 'setDeviceModeData',
		value: function setDeviceModeData() {
			this.elements.$body.attr('data-elementor-device-mode', this.getCurrentDeviceMode());
		}
	}, {
		key: 'addListenerOnce',
		value: function addListenerOnce(listenerID, event, callback, to) {
			if (!to) {
				to = this.elements.$window;
			}

			if (!this.isEditMode()) {
				to.on(event, callback);

				return;
			}

			this.removeListeners(listenerID, event, to);

			if (to instanceof jQuery) {
				var eventNS = event + '.' + listenerID;

				to.on(eventNS, callback);
			} else {
				to.on(event, callback, listenerID);
			}
		}
	}, {
		key: 'removeListeners',
		value: function removeListeners(listenerID, event, callback, from) {
			if (!from) {
				from = this.elements.$window;
			}

			if (from instanceof jQuery) {
				var eventNS = event + '.' + listenerID;

				from.off(eventNS, callback);
			} else {
				from.off(event, callback, listenerID);
			}
		}

		// Based on underscore function

	}, {
		key: 'debounce',
		value: function debounce(func, wait) {
			var timeout = void 0;

			return function () {
				var context = this,
					args = arguments;

				var later = function later() {
					timeout = null;

					func.apply(context, args);
				};

				var callNow = !timeout;

				clearTimeout(timeout);

				timeout = setTimeout(later, wait);

				if (callNow) {
					func.apply(context, args);
				}
			};
		}
	}, {
		key: 'waypoint',
		value: function waypoint($element, callback, options) {
			var defaultOptions = {
				offset: '100%',
				triggerOnce: true
			};

			options = jQuery.extend(defaultOptions, options);

			var correctCallback = function correctCallback() {
				var element = this.element || this,
					result = callback.apply(element, arguments);

				// If is Waypoint new API and is frontend
				if (options.triggerOnce && this.destroy) {
					this.destroy();
				}

				return result;
			};

			return $element.elementorWaypoint
				? $element.elementorWaypoint(correctCallback, options)
				: $element.waypoint(correctCallback, options)
			;
		}
	}, {
		key: 'muteMigrationTraces',
		value: function muteMigrationTraces() {
			jQuery.migrateMute = true;

			jQuery.migrateTrace = false;
		}
	}, {
		key: 'init',
		value: function init() {
			this.hooks = new EventManager();

			this.storage = new _storage2.default();

			this.addIeCompatibility();

			this.setDeviceModeData();

			this.initDialogsManager();

			if (this.isEditMode()) {
				this.muteMigrationTraces();
			}

			// Keep this line before `initOnReadyComponents` call
			this.elements.$window.trigger('elementor/frontend/init');

			if (!this.isEditMode()) {
				this.initHotKeys();
			}

			this.initOnReadyElements();

			this.initOnReadyComponents();
		}
	}, {
		key: 'Module',
		get: function get() {
			// if ( this.isEditMode() ) {
			// 	parent.elementorCommon.helpers.deprecatedMethod( 'ceFrontend.Module', '2.5.0', 'elementorModules.frontend.handlers.Base' );
			// }

			return elementorModules.frontend.handlers.Base;
		}
	}]);

	return Frontend;
}(elementorModules.ViewModule);

window.ceFrontend = new Frontend();

if (!ceFrontend.isEditMode()) {
	jQuery(function () {
		return ceFrontend.init();
	});
}

/***/ }),

/***/ 183:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _document = __webpack_require__(17);

var _document2 = _interopRequireDefault(_document);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var _class = function (_elementorModules$Vie) {
	_inherits(_class, _elementorModules$Vie);

	function _class() {
		var _ref;

		_classCallCheck(this, _class);

		for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
			args[_key] = arguments[_key];
		}

		var _this = _possibleConstructorReturn(this, (_ref = _class.__proto__ || Object.getPrototypeOf(_class)).call.apply(_ref, [this].concat(args)));

		_this.documents = {};

		_this.initDocumentClasses();

		_this.attachDocumentsClasses();
		return _this;
	}

	_createClass(_class, [{
		key: 'getDefaultSettings',
		value: function getDefaultSettings() {
			return {
				selectors: {
					document: '.elementor'
				}
			};
		}
	}, {
		key: 'getDefaultElements',
		value: function getDefaultElements() {
			var selectors = this.getSettings('selectors');

			return {
				$documents: jQuery(selectors.document)
			};
		}
	}, {
		key: 'initDocumentClasses',
		value: function initDocumentClasses() {
			this.documentClasses = {
				base: _document2.default
			};

			ceFrontend.hooks.doAction('elementor/frontend/documents-manager/init-classes', this);
		}
	}, {
		key: 'addDocumentClass',
		value: function addDocumentClass(documentType, documentClass) {
			this.documentClasses[documentType] = documentClass;
		}
	}, {
		key: 'attachDocumentsClasses',
		value: function attachDocumentsClasses() {
			var _this2 = this;

			this.elements.$documents.each(function (index, document) {
				return _this2.attachDocumentClass(jQuery(document));
			});
		}
	}, {
		key: 'attachDocumentClass',
		value: function attachDocumentClass($document) {
			var documentData = $document.data(),
				documentID = documentData.elementorId,
				documentType = documentData.elementorType,
				DocumentClass = this.documentClasses[documentType] || this.documentClasses.base;

			this.documents[documentID] = new DocumentClass({
				$element: $document,
				id: documentID
			});
		}
	}]);

	return _class;
}(elementorModules.ViewModule);

exports.default = _class;

/***/ }),

/***/ 184:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function ($) {
	var self = this;

	// element-type.skin-type
	var handlers = {
		// Elements
		section: __webpack_require__(185),

		// Widgets
		'accordion.default': __webpack_require__(186),
		'alert.default': __webpack_require__(187),
		'counter.default': __webpack_require__(188),
		'countdown.default': __webpack_require__(100),
		'animated-headline.default': __webpack_require__(123),
		'shopping-cart.default': __webpack_require__(124),
		'progress.default': __webpack_require__(189),
		'tabs.default': __webpack_require__(190),
		'toggle.default': __webpack_require__(191),
		'video.default': __webpack_require__(192),
		'image-carousel.default': __webpack_require__(193),
		'testimonial-carousel.default': __webpack_require__(193),
		'product-carousel.default': __webpack_require__(193),
		'v-smartblog.default': __webpack_require__(193),
		'trustedshops-reviews.default': __webpack_require__(193),
		'text-editor.default': __webpack_require__(194),
		'contact-form.default': __webpack_require__(200),
		'email-subscription.default': __webpack_require__(200),
		'slideshow.default': __webpack_require__(201),
		'product-tab.default': __webpack_require__(202),
		'promo.default': __webpack_require__(203),
		'product-sale.default': __webpack_require__(204),
	};

	var handlersInstances = {};

	var addGlobalHandlers = function addGlobalHandlers() {
		ceFrontend.hooks.addAction('frontend/element_ready/global', __webpack_require__(195));
	};

	var addElementsHandlers = function addElementsHandlers() {
		$.each(handlers, function (elementName, funcCallback) {
			ceFrontend.hooks.addAction('frontend/element_ready/' + elementName, funcCallback);
			if(elementName == 'product-sale.default'){
				ceFrontend.hooks.addAction('frontend/element_ready/product-sale.default', __webpack_require__(193));
			}
		});
		// Sticky
		ceFrontend.hooks.addAction('frontend/element_ready/section', __webpack_require__(10));
		ceFrontend.hooks.addAction('frontend/element_ready/widget', __webpack_require__(10));
	};

	var init = function init() {
		self.initHandlers();
	};

	this.initHandlers = function () {
		addGlobalHandlers();

		addElementsHandlers();
	};

	this.addHandler = function (HandlerClass, options) {
		var elementID = options.$element.data('model-cid');

		var handlerID = void 0;

		// If element is in edit mode
		if (elementID) {
			handlerID = HandlerClass.prototype.getConstructorID();

			if (!handlersInstances[elementID]) {
				handlersInstances[elementID] = {};
			}

			var oldHandler = handlersInstances[elementID][handlerID];

			if (oldHandler) {
				oldHandler.onDestroy();
			}
		}

		var newHandler = new HandlerClass(options);

		if (elementID) {
			handlersInstances[elementID][handlerID] = newHandler;
		}
	};

	this.getHandlers = function (handlerName) {
		if (handlerName) {
			return handlers[handlerName];
		}

		return handlers;
	};

	this.runReadyTrigger = function (scope) {
		// Initializing the `$scope` as frontend jQuery instance
		var $scope = jQuery(scope),
			elementType = $scope.attr('data-element_type');

		if (!elementType) {
			return;
		}

		ceFrontend.hooks.doAction('frontend/element_ready/global', $scope, $);

		ceFrontend.hooks.doAction('frontend/element_ready/' + elementType, $scope, $);

		if ('widget' === elementType) {
			ceFrontend.hooks.doAction('frontend/element_ready/' + $scope.attr('data-widget_type'), $scope, $);
		}
	};

	init();
};

/***/ }),

/***/ 185:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var BackgroundVideo = elementorModules.frontend.handlers.Base.extend({
	player: null,

	isYTVideo: null,

	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				backgroundVideoContainer: '.elementor-background-video-container',
				backgroundVideoEmbed: '.elementor-background-video-embed',
				backgroundVideoHosted: '.elementor-background-video-hosted'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors'),
			elements = {
			$backgroundVideoContainer: this.$element.find(selectors.backgroundVideoContainer)
		};

		elements.$backgroundVideoEmbed = elements.$backgroundVideoContainer.children(selectors.backgroundVideoEmbed);

		elements.$backgroundVideoHosted = elements.$backgroundVideoContainer.children(selectors.backgroundVideoHosted);

		return elements;
	},

	calcVideosSize: function calcVideosSize() {
		var containerWidth = this.elements.$backgroundVideoContainer.outerWidth(),
			containerHeight = this.elements.$backgroundVideoContainer.outerHeight(),
			aspectRatioSetting = '16:9',
			//TEMP
		aspectRatioArray = aspectRatioSetting.split(':'),
			aspectRatio = aspectRatioArray[0] / aspectRatioArray[1],
			ratioWidth = containerWidth / aspectRatio,
			ratioHeight = containerHeight * aspectRatio,
			isWidthFixed = containerWidth / containerHeight > aspectRatio;

		return {
			width: isWidthFixed ? containerWidth : ratioHeight,
			height: isWidthFixed ? ratioWidth : containerHeight
		};
	},

	changeVideoSize: function changeVideoSize() {
		var $video = this.isYTVideo ? jQuery(this.player.getIframe()) : this.elements.$backgroundVideoHosted,
			size = this.calcVideosSize();

		$video.width(size.width).height(size.height);
	},

	startVideoLoop: function startVideoLoop() {
		var self = this;

		// If the section has been removed
		if (!self.player.getIframe().contentWindow) {
			return;
		}

		var elementSettings = self.getElementSettings(),
			startPoint = elementSettings.background_video_start || 0,
			endPoint = elementSettings.background_video_end;

		self.player.seekTo(startPoint);

		if (endPoint) {
			var durationToEnd = endPoint - startPoint + 1;

			setTimeout(function () {
				self.startVideoLoop();
			}, durationToEnd * 1000);
		}
	},

	prepareYTVideo: function prepareYTVideo(YT, videoID) {
		var self = this,
			$backgroundVideoContainer = self.elements.$backgroundVideoContainer,
			elementSettings = self.getElementSettings(),
			startStateCode = YT.PlayerState.PLAYING;

		// Since version 67, Chrome doesn't fire the `PLAYING` state at start time
		if (window.chrome) {
			startStateCode = YT.PlayerState.UNSTARTED;
		}

		$backgroundVideoContainer.addClass('elementor-loading elementor-invisible');

		self.player = new YT.Player(self.elements.$backgroundVideoEmbed[0], {
			videoId: videoID,
			events: {
				onReady: function onReady() {
					self.player.mute();

					self.changeVideoSize();

					self.startVideoLoop();

					self.player.playVideo();
				},
				onStateChange: function onStateChange(event) {
					switch (event.data) {
						case startStateCode:
							$backgroundVideoContainer.removeClass('elementor-invisible elementor-loading');

							break;
						case YT.PlayerState.ENDED:
							self.player.seekTo(elementSettings.background_video_start || 0);
					}
				}
			},
			playerVars: {
				controls: 0,
				rel: 0
			}
		});
	},

	activate: function activate() {
		var self = this,
			videoLink = self.getElementSettings('background_video_link'),
			videoID = ceFrontend.utils.youtube.getYoutubeIDFromURL(videoLink);

		self.isYTVideo = !!videoID;

		if (videoID) {
			ceFrontend.utils.youtube.onYoutubeApiReady(function (YT) {
				setTimeout(function () {
					self.prepareYTVideo(YT, videoID);
				}, 1);
			});
		} else {
			self.elements.$backgroundVideoHosted.attr('src', videoLink).one('canplay', self.changeVideoSize);
		}

		ceFrontend.elements.$window.on('resize', self.changeVideoSize);
	},

	deactivate: function deactivate() {
		if (this.isYTVideo && this.player.getIframe()) {
			this.player.destroy();
		} else {
			this.elements.$backgroundVideoHosted.removeAttr('src');
		}

		ceFrontend.elements.$window.off('resize', this.changeVideoSize);
	},

	run: function run() {
		var elementSettings = this.getElementSettings();

		if ('video' === elementSettings.background_background && elementSettings.background_video_link) {
			this.activate();
		} else {
			this.deactivate();
		}
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.run();
	},

	onElementChange: function onElementChange(propertyName) {
		if ('background_background' === propertyName) {
			this.run();
		}
	}
});

var StretchedSection = elementorModules.frontend.handlers.Base.extend({

	stretchElement: null,

	bindEvents: function bindEvents() {
		var handlerID = this.getUniqueHandlerID();

		ceFrontend.addListenerOnce(handlerID, 'resize', this.stretch);

		ceFrontend.addListenerOnce(handlerID, 'sticky:stick', this.stretch, this.$element);

		ceFrontend.addListenerOnce(handlerID, 'sticky:unstick', this.stretch, this.$element);
	},

	unbindEvents: function unbindEvents() {
		ceFrontend.removeListeners(this.getUniqueHandlerID(), 'resize', this.stretch);
	},

	initStretch: function initStretch() {
		this.stretchElement = new elementorModules.frontend.tools.StretchElement({
			element: this.$element,
			selectors: {
				container: this.getStretchContainer()
			}
		});
	},

	getStretchContainer: function getStretchContainer() {
		return ceFrontend.getGeneralSettings('elementor_stretched_section_container') || document.documentElement;
	},

	stretch: function stretch() {
		if (!this.getElementSettings('stretch_section')) {
			return;
		}

		this.stretchElement.stretch();
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.initStretch();

		this.stretch();
	},

	onElementChange: function onElementChange(propertyName) {
		if ('stretch_section' === propertyName) {
			if (this.getElementSettings('stretch_section')) {
				this.stretch();
			} else {
				this.stretchElement.reset();
			}
		}
	},

	onGeneralSettingsChange: function onGeneralSettingsChange(changed) {
		if ('elementor_stretched_section_container' in changed) {
			this.stretchElement.setSettings('selectors.container', this.getStretchContainer());

			this.stretch();
		}
	}
});

var Shapes = elementorModules.frontend.handlers.Base.extend({

	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				container: '> .elementor-shape-%s'
			},
			svgURL: ceFrontend.config.urls.assets + 'img/shapes/'
		};
	},

	getDefaultElements: function getDefaultElements() {
		var elements = {},
			selectors = this.getSettings('selectors');

		elements.$topContainer = this.$element.find(selectors.container.replace('%s', 'top'));

		elements.$bottomContainer = this.$element.find(selectors.container.replace('%s', 'bottom'));

		return elements;
	},

	getSvgURL: function getSvgURL(shapeType, fileName) {
		var svgURL = this.getSettings('svgURL') + fileName + '.svg';
		if (elementor.config.additional_shapes && shapeType in elementor.config.additional_shapes) {
			svgURL = elementor.config.additional_shapes[shapeType];
		}
		return svgURL;
	},


	buildSVG: function buildSVG(side) {
		var self = this,
			baseSettingKey = 'shape_divider_' + side,
			shapeType = self.getElementSettings(baseSettingKey),
			$svgContainer = this.elements['$' + side + 'Container'];

		$svgContainer.attr('data-shape', shapeType);

		if (!shapeType) {
			$svgContainer.empty(); // Shape-divider set to 'none'
			return;
		}

		var fileName = shapeType;

		if (self.getElementSettings(baseSettingKey + '_negative')) {
			fileName += '-negative';
		}

		var svgURL = self.getSvgURL(shapeType, fileName);

		jQuery.get(svgURL, function (data) {
			$svgContainer.empty().append(data.childNodes[0]);
		});

		this.setNegative(side);
	},

	setNegative: function setNegative(side) {
		this.elements['$' + side + 'Container'].attr('data-negative', !!this.getElementSettings('shape_divider_' + side + '_negative'));
	},

	onInit: function onInit() {
		var self = this;

		elementorModules.frontend.handlers.Base.prototype.onInit.apply(self, arguments);

		['top', 'bottom'].forEach(function (side) {
			if (self.getElementSettings('shape_divider_' + side)) {
				self.buildSVG(side);
			}
		});
	},

	onElementChange: function onElementChange(propertyName) {
		var shapeChange = propertyName.match(/^shape_divider_(top|bottom)$/);

		if (shapeChange) {
			this.buildSVG(shapeChange[1]);

			return;
		}

		var negativeChange = propertyName.match(/^shape_divider_(top|bottom)_negative$/);

		if (negativeChange) {
			this.buildSVG(negativeChange[1]);

			this.setNegative(negativeChange[1]);
		}
	}
});

var HandlesPosition = elementorModules.frontend.handlers.Base.extend({

	isFirstSection: function isFirstSection() {
		return this.$element.is('.elementor-edit-mode .elementor-top-section:first');
	},

	isOverflowHidden: function isOverflowHidden() {
		return 'hidden' === this.$element.css('overflow');
	},

	getOffset: function getOffset() {
		if ('body' === elementor.config.document.container) {
			return this.$element.offset().top;
		}

		var $container = jQuery(elementor.config.document.container);
		return this.$element.offset().top - $container.offset().top;
	},

	setHandlesPosition: function setHandlesPosition() {
		var isOverflowHidden = this.isOverflowHidden();

		if (!isOverflowHidden && !this.isFirstSection()) {
			return;
		}

		var offset = isOverflowHidden ? 0 : this.getOffset(),
			$handlesElement = this.$element.find('> .elementor-element-overlay > .elementor-editor-section-settings'),
			insideHandleClass = 'elementor-section--handles-inside';

		if (offset < 25) {
			this.$element.addClass(insideHandleClass);

			if (offset < -5) {
				$handlesElement.css('top', -offset);
			} else {
				$handlesElement.css('top', '');
			}
		} else {
			this.$element.removeClass(insideHandleClass);
		}
	},

	onInit: function onInit() {
		this.setHandlesPosition();

		this.$element.on('mouseenter', this.setHandlesPosition);
	}
});

module.exports = function ($scope) {
	if (ceFrontend.isEditMode() || $scope.hasClass('elementor-section-stretched')) {
		ceFrontend.elementsHandler.addHandler(StretchedSection, { $element: $scope });
	}

	if (ceFrontend.isEditMode()) {
		ceFrontend.elementsHandler.addHandler(Shapes, { $element: $scope });
		ceFrontend.elementsHandler.addHandler(HandlesPosition, { $element: $scope });
	}

	ceFrontend.elementsHandler.addHandler(BackgroundVideo, { $element: $scope });
};

/***/ }),

/***/ 186:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TabsModule = __webpack_require__(18);

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(TabsModule, {
		$element: $scope,
		showTabFn: 'slideDown',
		hideTabFn: 'slideUp'
	});
};

/***/ }),

/***/ 187:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function ($scope, $) {
	$scope.find('.elementor-alert-dismiss').on('click', function () {
		$(this).parent().fadeOut();
	});
};

/***/ }),

/***/ 188:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function ($scope, $) {
	ceFrontend.waypoint($scope.find('.elementor-counter-number'), function () {
		var $number = $(this),
			data = $number.data();

		var decimalDigits = data.toValue.toString().match(/\.(.*)/);

		if (decimalDigits) {
			data.rounding = decimalDigits[1].length;
		}

		$number.numerator(data);
	});
};

/***/ }),

/***/ 189:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function ($scope, $) {
	ceFrontend.waypoint($scope.find('.elementor-progress-bar'), function () {
		var $progressbar = $(this);

		$progressbar.css('width', $progressbar.data('max') + '%');
	});
};

/***/ }),

/***/ 190:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TabsModule = __webpack_require__(18);

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(TabsModule, {
		$element: $scope,
		toggleSelf: false
	});
};

/***/ }),

/***/ 191:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TabsModule = __webpack_require__(18);

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(TabsModule, {
		$element: $scope,
		showTabFn: 'slideDown',
		hideTabFn: 'slideUp',
		hidePrevious: false,
		autoExpand: 'editor'
	});
};

/***/ }),

/***/ 192:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var VideoModule = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				imageOverlay: '.elementor-custom-embed-image-overlay',
				video: '.elementor-video',
				videoIframe: '.elementor-video-iframe'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$imageOverlay: this.$element.find(selectors.imageOverlay),
			$video: this.$element.find(selectors.video),
			$videoIframe: this.$element.find(selectors.videoIframe)
		};
	},

	getLightBox: function getLightBox() {
		return ceFrontend.utils.lightbox;
	},

	handleVideo: function handleVideo() {
		if (!this.getElementSettings('lightbox')) {
			this.elements.$imageOverlay.remove();

			this.playVideo();
		}
	},

	playVideo: function playVideo() {
		if (this.elements.$video.length) {
			this.elements.$video[0].play();

			return;
		}

		var $videoIframe = this.elements.$videoIframe,
			lazyLoad = $videoIframe.data('lazy-load');

		if (lazyLoad) {
			$videoIframe.attr('src', lazyLoad);
		}

		var newSourceUrl = $videoIframe[0].src.replace('&autoplay=0', '');

		$videoIframe[0].src = newSourceUrl + '&autoplay=1';
	},

	animateVideo: function animateVideo() {
		this.getLightBox().setEntranceAnimation(this.getCurrentDeviceSetting('lightbox_content_animation'));
	},

	handleAspectRatio: function handleAspectRatio() {
		this.getLightBox().setVideoAspectRatio(this.getElementSettings('aspect_ratio'));
	},

	bindEvents: function bindEvents() {
		this.elements.$imageOverlay.on('click', this.handleVideo);
	},

	onElementChange: function onElementChange(propertyName) {
		if (0 === propertyName.indexOf('lightbox_content_animation')) {
			this.animateVideo();

			return;
		}

		var isLightBoxEnabled = this.getElementSettings('lightbox');

		if ('lightbox' === propertyName && !isLightBoxEnabled) {
			this.getLightBox().getModal().hide();

			return;
		}

		if ('aspect_ratio' === propertyName && isLightBoxEnabled) {
			this.handleAspectRatio();
		}
	}
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(VideoModule, { $element: $scope });
};

/***/ }),

/***/ 193:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var ImageCarouselHandler = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				carousel: '.elementor-image-carousel , .elementor-block-carousel'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$carousel: this.$element.find(selectors.carousel)
		};
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		var elementCarousel = this.elements.$carousel;
		elementCarousel.on('init', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-slide.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-slide.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-slide.slick-active').eq(slideToShow).addClass('last-active');
		});

		var elementSettings = this.getElementSettings(),
			slidesToShow = +elementSettings.slides_to_show || elementSettings.default_slides_desktop,
			isSingleSlide = 1 === slidesToShow,
			centerPadding = elementSettings.center_padding && elementSettings.center_padding.size + '',
			centerPaddingTablet = elementSettings.center_padding_tablet && elementSettings.center_padding_tablet.size + '',
			centerPaddingMobile = elementSettings.center_padding_mobile && elementSettings.center_padding_mobile.size + '',
			breakpoints = ceFrontend.config.breakpoints;

		var slickOptions = {
			touchThreshold: 100,
			slidesToShow: slidesToShow,
			slidesToScroll: +elementSettings.slides_to_scroll || 1,
			swipeToSlide: !elementSettings.slides_to_scroll,
			variableWidth: 'yes' === elementSettings.variable_width,
			centerMode: 'yes' === elementSettings.center_mode,
			centerPadding: centerPadding ? centerPadding + elementSettings.center_padding.unit : void 0,
			autoplay: 'yes' === elementSettings.autoplay,
			autoplaySpeed: elementSettings.autoplay_speed,
			infinite: 'yes' === elementSettings.infinite,
			pauseOnHover: 'yes' === elementSettings.pause_on_hover,
			speed: elementSettings.speed,
			arrows: -1 !== ['arrows', 'both'].indexOf(elementSettings.navigation),
			dots: -1 !== ['dots', 'both'].indexOf(elementSettings.navigation),
			rtl: 'rtl' === elementSettings.direction,
			responsive: [{
				breakpoint: breakpoints.lg,
				settings: {
					centerPadding: centerPaddingTablet ? centerPaddingTablet + elementSettings.center_padding_tablet.unit : void 0,
					slidesToShow: +elementSettings.slides_to_show_tablet || elementSettings.default_slides_tablet,
					slidesToScroll: +elementSettings.slides_to_scroll_tablet || 1,
					swipeToSlide: !elementSettings.slides_to_scroll_tablet,
					autoplay: 'yes' === elementSettings.autoplay_tablet,
					infinite: elementSettings.infinite_tablet ? 'yes' === elementSettings.infinite_tablet : void 0
				}
			}, {
				breakpoint: breakpoints.md,
				settings: {
					centerPadding: centerPaddingMobile ? centerPaddingMobile + elementSettings.center_padding_mobile.unit : (
						centerPaddingTablet ? centerPaddingTablet + elementSettings.center_padding_tablet.unit : void 0
					),
					slidesToShow: +elementSettings.slides_to_show_mobile || elementSettings.default_slides_mobile,
					slidesToScroll: +elementSettings.slides_to_scroll_mobile || 1,
					swipeToSlide: !elementSettings.slides_to_scroll_mobile,
					autoplay: 'yes' === elementSettings.autoplay_mobile,
					infinite: elementSettings.infinite_mobile ? 'yes' === elementSettings.infinite_mobile : void 0
				}
			}, {
				breakpoint: 320,
				settings: {
					centerPadding: centerPaddingMobile ? centerPaddingMobile + elementSettings.center_padding_mobile.unit : (
						centerPaddingTablet ? centerPaddingTablet + elementSettings.center_padding_tablet.unit : void 0
					),
					slidesToShow: 1,
					slidesToScroll: 1,
					swipeToSlide: !elementSettings.slides_to_scroll_mobile,
					autoplay: 'yes' === elementSettings.autoplay_mobile,
					infinite: elementSettings.infinite_mobile ? 'yes' === elementSettings.infinite_mobile : void 0
				}
			}]
		};

		if (isSingleSlide) {
			slickOptions.fade = 'fade' === elementSettings.effect;
		}

		this.elements.$carousel.slick(slickOptions);

		elementCarousel.on('afterChange', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-slide.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-slide.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-slide.slick-active').eq(slideToShow).addClass('last-active');
		});
	}
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(ImageCarouselHandler, { $element: $scope });
};

/***/ }),

/***/ 194:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var TextEditor = elementorModules.frontend.handlers.Base.extend({
	dropCapLetter: '',

	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				paragraph: 'p:first'
			},
			classes: {
				dropCap: 'elementor-drop-cap',
				dropCapLetter: 'elementor-drop-cap-letter'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors'),
			classes = this.getSettings('classes'),
			$dropCap = jQuery('<span>', { class: classes.dropCap }),
			$dropCapLetter = jQuery('<span>', { class: classes.dropCapLetter });

		$dropCap.append($dropCapLetter);

		return {
			$paragraph: this.$element.find(selectors.paragraph),
			$dropCap: $dropCap,
			$dropCapLetter: $dropCapLetter
		};
	},

	wrapDropCap: function wrapDropCap() {
		var isDropCapEnabled = this.getElementSettings('drop_cap');

		if (!isDropCapEnabled) {
			// If there is an old drop cap inside the paragraph
			if (this.dropCapLetter) {
				this.elements.$dropCap.remove();

				this.elements.$paragraph.prepend(this.dropCapLetter);

				this.dropCapLetter = '';
			}

			return;
		}

		var $paragraph = this.elements.$paragraph;

		if (!$paragraph.length) {
			return;
		}

		var paragraphContent = $paragraph.html().replace(/&nbsp;/g, ' '),
			firstLetterMatch = paragraphContent.match(/^ *([^ ] ?)/);

		if (!firstLetterMatch) {
			return;
		}

		var firstLetter = firstLetterMatch[1],
			trimmedFirstLetter = firstLetter.trim();

		// Don't apply drop cap when the content starting with an HTML tag
		if ('<' === trimmedFirstLetter) {
			return;
		}

		this.dropCapLetter = firstLetter;

		this.elements.$dropCapLetter.text(trimmedFirstLetter);

		var restoredParagraphContent = paragraphContent.slice(firstLetter.length).replace(/^ */, function (match) {
			return new Array(match.length + 1).join('&nbsp;');
		});

		$paragraph.html(restoredParagraphContent).prepend(this.elements.$dropCap);
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		this.wrapDropCap();
	},

	onElementChange: function onElementChange(propertyName) {
		if ('drop_cap' === propertyName) {
			this.wrapDropCap();
		}
	}
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(TextEditor, { $element: $scope });
};

/***/ }),

/***/ 195:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var GlobalHandler = elementorModules.frontend.handlers.Base.extend({
	getWidgetType: function getWidgetType() {
		return 'global';
	},
	animate: function animate() {
		var $element = this.$element,
			animation = this.getAnimation();

		if ('none' === animation) {
			$element.removeClass('elementor-invisible');
			return;
		}

		var elementSettings = this.getElementSettings(),
			animationDelay = elementSettings._animation_delay || elementSettings.animation_delay || 0;

		$element.removeClass(animation);

		if (this.currentAnimation) {
			$element.removeClass(this.currentAnimation);
		}

		this.currentAnimation = animation;

		setTimeout(function () {
			$element.removeClass('elementor-invisible').addClass('animated ' + animation);
		}, animationDelay);
	},
	getAnimation: function getAnimation() {
		return this.getCurrentDeviceSetting('animation') || this.getCurrentDeviceSetting('_animation');
	},
	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		if (this.getAnimation()) {
			ceFrontend.waypoint(this.$element, this.animate.bind(this));
		}
	},
	onElementChange: function onElementChange(propertyName) {
		if (/^_?animation/.test(propertyName)) {
			this.animate();
		}
	}
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(GlobalHandler, { $element: $scope });
};

/***/ }),

/***/ 196:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = elementorModules.ViewModule.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			isInserted: false,
			APISrc: 'https://www.youtube.com/iframe_api',
			selectors: {
				firstScript: 'script:first'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		return {
			$firstScript: jQuery(this.getSettings('selectors.firstScript'))
		};
	},

	insertYTAPI: function insertYTAPI() {
		this.setSettings('isInserted', true);

		this.elements.$firstScript.before(jQuery('<script>', { src: this.getSettings('APISrc') }));
	},

	onYoutubeApiReady: function onYoutubeApiReady(callback) {
		var self = this;

		if (!self.getSettings('IsInserted')) {
			self.insertYTAPI();
		}

		if (window.YT && YT.loaded) {
			callback(YT);
		} else {
			// If not ready check again by timeout..
			setTimeout(function () {
				self.onYoutubeApiReady(callback);
			}, 350);
		}
	},

	getYoutubeIDFromURL: function getYoutubeIDFromURL(url) {
		var videoIDParts = url.match(/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^?&"'>]+)/);

		return videoIDParts && videoIDParts[1];
	}
});

/***/ }),

/***/ 197:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = elementorModules.ViewModule.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			scrollDuration: 500,
			selectors: {
				links: 'a[href*="#"]',
				targets: '.elementor-element, .elementor-menu-anchor',
				scrollable: 'html, body'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var $ = jQuery,
			selectors = this.getSettings('selectors');

		return {
			$scrollable: $(selectors.scrollable)
		};
	},

	bindEvents: function bindEvents() {
		ceFrontend.elements.$document.on('click', this.getSettings('selectors.links'), this.handleAnchorLinks);
	},

	handleAnchorLinks: function handleAnchorLinks(event) {
		var clickedLink = event.currentTarget,
			isSamePathname = location.pathname === clickedLink.pathname,
			isSameHostname = location.hostname === clickedLink.hostname,
			$anchor;

		if (!isSameHostname || !isSamePathname || clickedLink.hash.length < 2) {
			return;
		}

		try {
			$anchor = jQuery(clickedLink.hash).filter(this.getSettings('selectors.targets'));
		} catch (e) {
			return;
		}

		if (!$anchor.length) {
			return;
		}

		var scrollTop = $anchor.offset().top,
			$wpAdminBar = ceFrontend.elements.$wpAdminBar,
			$activeStickies = jQuery('.elementor-section.elementor-sticky--active'),
			maxStickyHeight = 0;

		if ($wpAdminBar.length > 0) {
			scrollTop -= $wpAdminBar.height();
		}

		// Offset height of tallest sticky
		if ($activeStickies.length > 0) {
			maxStickyHeight = Math.max.apply(null, $activeStickies.map(function () {
				return jQuery(this).outerHeight();
			}).get());

			scrollTop -= maxStickyHeight;
		}

		event.preventDefault();

		scrollTop = ceFrontend.hooks.applyFilters('frontend/handlers/menu_anchor/scroll_top_distance', scrollTop);

		this.elements.$scrollable.animate({
			scrollTop: scrollTop
		}, this.getSettings('scrollDuration'), 'linear');
	},

	onInit: function onInit() {
		elementorModules.ViewModule.prototype.onInit.apply(this, arguments);

		this.bindEvents();
	}
});

/***/ }),

/***/ 198:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = elementorModules.ViewModule.extend({
	oldAspectRatio: null,

	oldAnimation: null,

	swiper: null,

	getDefaultSettings: function getDefaultSettings() {
		return {
			classes: {
				aspectRatio: 'elementor-aspect-ratio-%s',
				item: 'elementor-lightbox-item',
				image: 'elementor-lightbox-image',
				videoContainer: 'elementor-video-container',
				videoWrapper: 'elementor-fit-aspect-ratio',
				playButton: 'elementor-custom-embed-play',
				playButtonIcon: 'fa',
				playing: 'elementor-playing',
				hidden: 'elementor-hidden',
				invisible: 'elementor-invisible',
				preventClose: 'elementor-lightbox-prevent-close',
				slideshow: {
					container: 'swiper-container',
					slidesWrapper: 'swiper-wrapper',
					prevButton: 'elementor-swiper-button elementor-swiper-button-prev',
					nextButton: 'elementor-swiper-button elementor-swiper-button-next',
					prevButtonIcon: 'fa fa-angle-left',
					nextButtonIcon: 'fa fa-angle-right',
					slide: 'swiper-slide'
				}
			},
			selectors: {
				links: 'a, [data-elementor-lightbox]',
				slideshow: {
					activeSlide: '.swiper-slide-active',
					prevSlide: '.swiper-slide-prev',
					nextSlide: '.swiper-slide-next'
				}
			},
			modalOptions: {
				id: 'elementor-lightbox',
				entranceAnimation: 'zoomIn',
				videoAspectRatio: 169,
				position: {
					enable: false
				}
			}
		};
	},

	getModal: function getModal() {
		if (!module.exports.modal) {
			this.initModal();
		}

		return module.exports.modal;
	},

	initModal: function initModal() {
		var modal = module.exports.modal = ceFrontend.getDialogsManager().createWidget('lightbox', {
			className: 'elementor-lightbox',
			closeButton: true,
			closeButtonClass: 'ceicon-close',
			selectors: {
				preventClose: '.' + this.getSettings('classes.preventClose')
			},
			hide: {
				onClick: true
			}
		});

		modal.on('hide', function () {
			modal.setMessage('');
		});
	},

	showModal: function showModal(options) {
		var self = this,
			defaultOptions = self.getDefaultSettings().modalOptions;

		self.setSettings('modalOptions', jQuery.extend(defaultOptions, options.modalOptions));

		var modal = self.getModal();

		modal.setID(self.getSettings('modalOptions.id'));

		modal.onShow = function () {
			DialogsManager.getWidgetType('lightbox').prototype.onShow.apply(modal, arguments);

			self.setEntranceAnimation();
		};

		modal.onHide = function () {
			DialogsManager.getWidgetType('lightbox').prototype.onHide.apply(modal, arguments);

			modal.getElements('message').removeClass('animated');
		};

		switch (options.type) {
			case 'image':
				self.setImageContent(options.url);

				break;
			case 'video':
				self.setVideoContent(options);

				break;
			case 'slideshow':
				self.setSlideshowContent(options.slideshow);

				break;
			default:
				self.setHTMLContent(options.html);
		}

		modal.show();
	},

	setHTMLContent: function setHTMLContent(html) {
		this.getModal().setMessage(html);
	},

	setImageContent: function setImageContent(imageURL) {
		var self = this,
			classes = self.getSettings('classes'),
			$item = jQuery('<div>', { class: classes.item }),
			$image = jQuery('<img>', { src: imageURL, class: classes.image + ' ' + classes.preventClose });

		$item.append($image);

		self.getModal().setMessage($item);
	},

	setVideoContent: function setVideoContent(options) {
		var classes = this.getSettings('classes'),
			$videoContainer = jQuery('<div>', { class: classes.videoContainer }),
			$videoWrapper = jQuery('<div>', { class: classes.videoWrapper }),
			$videoElement,
			modal = this.getModal();

		if ('hosted' === options.videoType) {
			var videoParams = jQuery.extend({ src: options.url, autoplay: '' }, options.videoParams);

			$videoElement = jQuery('<video>', videoParams);
		} else {
			var videoURL = options.url.replace('&autoplay=0', '') + '&autoplay=1';

			$videoElement = jQuery('<iframe>', { src: videoURL, allowfullscreen: 1 });
		}

		$videoContainer.append($videoWrapper);

		$videoWrapper.append($videoElement);

		modal.setMessage($videoContainer);

		this.setVideoAspectRatio();

		var onHideMethod = modal.onHide;

		modal.onHide = function () {
			onHideMethod();

			modal.getElements('message').removeClass('elementor-fit-aspect-ratio');
		};
	},

	setSlideshowContent: function setSlideshowContent(options) {
		var $ = jQuery,
			self = this,
			classes = self.getSettings('classes'),
			slideshowClasses = classes.slideshow,
			$container = $('<div>', { class: slideshowClasses.container }),
			$slidesWrapper = $('<div>', { class: slideshowClasses.slidesWrapper }),
			$prevButton = $('<div>', { class: slideshowClasses.prevButton + ' ' + classes.preventClose }).html($('<i>', { class: slideshowClasses.prevButtonIcon })),
			$nextButton = $('<div>', { class: slideshowClasses.nextButton + ' ' + classes.preventClose }).html($('<i>', { class: slideshowClasses.nextButtonIcon }));

		options.slides.forEach(function (slide) {
			var slideClass = slideshowClasses.slide + ' ' + classes.item;

			if (slide.video) {
				slideClass += ' ' + classes.video;
			}

			var $slide = $('<div>', { class: slideClass });

			if (slide.video) {
				$slide.attr('data-elementor-slideshow-video', slide.video);

				var $playIcon = $('<div>', { class: classes.playButton }).html($('<i>', { class: classes.playButtonIcon }));

				$slide.append($playIcon);
			} else {
				var $zoomContainer = $('<div>', { class: 'swiper-zoom-container' }),
					$slideImage = $('<img>', { class: classes.image + ' ' + classes.preventClose, src: slide.image });

				$zoomContainer.append($slideImage);

				$slide.append($zoomContainer);
			}

			$slidesWrapper.append($slide);
		});

		$container.append($slidesWrapper, $prevButton, $nextButton);

		var modal = self.getModal();

		modal.setMessage($container);

		var onShowMethod = modal.onShow;

		modal.onShow = function () {
			onShowMethod();

			var swiperOptions = {
				navigation: {
					prevEl: $prevButton,
					nextEl: $nextButton
				},
				pagination: {
					clickable: true
				},
				on: {
					slideChangeTransitionEnd: self.onSlideChange
				},
				grabCursor: true,
				runCallbacksOnInit: false,
				loop: true,
				keyboard: true
			};

			if (options.swiper) {
				$.extend(swiperOptions, options.swiper);
			}

			self.swiper = new Swiper($container, swiperOptions);

			self.setVideoAspectRatio();

			self.playSlideVideo();
		};
	},

	setVideoAspectRatio: function setVideoAspectRatio(aspectRatio) {
		aspectRatio = aspectRatio || this.getSettings('modalOptions.videoAspectRatio');

		var $widgetContent = this.getModal().getElements('widgetContent'),
			oldAspectRatio = this.oldAspectRatio,
			aspectRatioClass = this.getSettings('classes.aspectRatio');

		this.oldAspectRatio = aspectRatio;

		if (oldAspectRatio) {
			$widgetContent.removeClass(aspectRatioClass.replace('%s', oldAspectRatio));
		}

		if (aspectRatio) {
			$widgetContent.addClass(aspectRatioClass.replace('%s', aspectRatio));
		}
	},

	getSlide: function getSlide(slideState) {
		return jQuery(this.swiper.slides).filter(this.getSettings('selectors.slideshow.' + slideState + 'Slide'));
	},

	playSlideVideo: function playSlideVideo() {
		var $activeSlide = this.getSlide('active'),
			videoURL = $activeSlide.data('elementor-slideshow-video');

		if (!videoURL) {
			return;
		}

		var classes = this.getSettings('classes'),
			$videoContainer = jQuery('<div>', { class: classes.videoContainer + ' ' + classes.invisible }),
			$videoWrapper = jQuery('<div>', { class: classes.videoWrapper }),
			$videoFrame = jQuery('<iframe>', { src: videoURL }),
			$playIcon = $activeSlide.children('.' + classes.playButton);

		$videoContainer.append($videoWrapper);

		$videoWrapper.append($videoFrame);

		$activeSlide.append($videoContainer);

		$playIcon.addClass(classes.playing).removeClass(classes.hidden);

		$videoFrame.on('load', function () {
			$playIcon.addClass(classes.hidden);

			$videoContainer.removeClass(classes.invisible);
		});
	},

	setEntranceAnimation: function setEntranceAnimation(animation) {
		animation = animation || ceFrontend.getCurrentDeviceSetting(this.getSettings('modalOptions'), 'entranceAnimation');

		var $widgetMessage = this.getModal().getElements('message');

		if (this.oldAnimation) {
			$widgetMessage.removeClass(this.oldAnimation);
		}

		this.oldAnimation = animation;

		if (animation) {
			$widgetMessage.addClass('animated ' + animation);
		}
	},

	isLightboxLink: function isLightboxLink(element) {
		if ('A' === element.tagName && (element.hasAttribute('download') || !/\.(png|jpe?g|gif|svg)(\?.*)?$/i.test(element.href))) {
			return false;
		}

		var generalOpenInLightbox = +ceFrontend.getGeneralSettings('elementor_global_image_lightbox'),
			currentLinkOpenInLightbox = element.dataset.elementorOpenLightbox;

		return 'yes' === currentLinkOpenInLightbox || generalOpenInLightbox && 'no' !== currentLinkOpenInLightbox;
	},

	openLink: function openLink(event) {
		var element = event.currentTarget,
			$target = jQuery(event.target),
			editMode = ceFrontend.isEditMode(),
			isClickInsideElementor = !!$target.closest('#elementor').length;

		if (!this.isLightboxLink(element)) {
			if (editMode && isClickInsideElementor) {
				event.preventDefault();
			}

			return;
		}

		event.preventDefault();

		if (editMode && !ceFrontend.getGeneralSettings('elementor_enable_lightbox_in_editor')) {
			return;
		}

		var lightboxData = {};

		if (element.dataset.elementorLightbox) {
			lightboxData = JSON.parse(element.dataset.elementorLightbox);
		}

		if (lightboxData.type && 'slideshow' !== lightboxData.type) {
			this.showModal(lightboxData);

			return;
		}

		if (!element.dataset.elementorLightboxSlideshow) {
			this.showModal({
				type: 'image',
				url: element.href
			});

			return;
		}

		var slideshowID = element.dataset.elementorLightboxSlideshow;

		var $allSlideshowLinks = jQuery(this.getSettings('selectors.links')).filter(function () {
			return slideshowID === this.dataset.elementorLightboxSlideshow;
		});

		var slides = [],
			uniqueLinks = {};

		$allSlideshowLinks.each(function () {
			var slideVideo = this.dataset.elementorLightboxVideo,
				uniqueID = slideVideo || this.href;

			if (uniqueLinks[uniqueID]) {
				return;
			}

			uniqueLinks[uniqueID] = true;

			var slideIndex = this.dataset.elementorLightboxIndex;

			if (undefined === slideIndex) {
				slideIndex = $allSlideshowLinks.index(this);
			}

			var slideData = {
				image: this.href,
				index: slideIndex
			};

			if (slideVideo) {
				slideData.video = slideVideo;
			}

			slides.push(slideData);
		});

		slides.sort(function (a, b) {
			return a.index - b.index;
		});

		var initialSlide = element.dataset.elementorLightboxIndex;

		if (undefined === initialSlide) {
			initialSlide = $allSlideshowLinks.index(element);
		}

		this.showModal({
			type: 'slideshow',
			modalOptions: {
				id: 'elementor-lightbox-slideshow-' + slideshowID
			},
			slideshow: {
				slides: slides,
				swiper: {
					initialSlide: +initialSlide
				}
			}
		});
	},

	bindEvents: function bindEvents() {
		ceFrontend.elements.$document.on('click', this.getSettings('selectors.links'), this.openLink);
	},

	onSlideChange: function onSlideChange() {
		this.getSlide('prev').add(this.getSlide('next')).add(this.getSlide('active')).find('.' + this.getSettings('classes.videoWrapper')).remove();

		this.playSlideVideo();
	}
});

/***/ }),

/***/ 200:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var WidgetAjaxForm = (function(c){return(c.constructor.prototype=c).constructor})({
	constructor: function WidgetAjaxForm( $element ) {
		this.$element = $element;

		this.settings = {
			selectors: {
				form: 'form',
				submitButton: '[type="submit"]'
			}
		};

		this.elements = {};
		this.elements.$form = this.$element.find( this.settings.selectors.form );
		this.elements.$submitButton = this.elements.$form.find( this.settings.selectors.submitButton );

		this.bindEvents();
	},

	bindEvents: function() {
		this.elements.$form.on( 'submit', $.proxy( this, 'handleSubmit' ) );
	},

	beforeSend: function() {
		var $form = this.elements.$form;

		$form
			.animate( { opacity: '0.45' }, 500 )
			.addClass( 'elementor-form-waiting' )
		;
		$form.find( '.elementor-message' ).remove();
		$form.find( '.elementor-error' ).removeClass( 'elementor-error' );

		$form
			.find( 'div.elementor-field-group' )
			.removeClass( 'error' )
			.find( 'span.elementor-form-help-inline' )
			.remove()
			.end()
			.find( ':input' ).attr( 'aria-invalid', 'false' )
		;
		this.elements.$submitButton
			.attr( 'disabled', 'disabled' )
			.find( '> span' )
			.prepend( '<span class="elementor-button-text elementor-form-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' )
		;
	},

	getFormData: function() {
		var formData = new FormData( this.elements.$form[0] );
		formData.append(
			this.elements.$submitButton[0].name,
			this.elements.$submitButton[0].value
		);
		return formData;
	},

	onSuccess: function( response, status ) {
		var $form = this.elements.$form,
			insertMethod = $form.data( 'msg' ) === 'before' ? 'prepend' : 'append';

		this.elements.$submitButton
			.removeAttr( 'disabled' )
			.find( '.elementor-form-spinner' )
			.remove()
		;
		$form
			.animate( { opacity: '1' }, 100 )
			.removeClass( 'elementor-form-waiting' )
		;

		if ( response.success ) {
			var message = $form.data( 'success' ) || response.success;

			$form.trigger( 'submit_success', response );
			$form.trigger( 'form_destruct', response );
			$form.trigger( 'reset' );

			$form[insertMethod]( '<div class="elementor-message elementor-message-success" role="alert">' + message + '</div>' );
		} else {
			var message = $form.data( 'error' ) || response.errors && response.errors.join( '<br>' ) || 'Unknown error';

			$form[insertMethod]( '<div class="elementor-message elementor-message-danger" role="alert">' + message + '</div>' );
		}
	},

	onError: function( xhr, desc ) {
		var $form = this.elements.$form;

		$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + desc + '</div>' );

		this.elements.$submitButton
			.html( this.elements.$submitButton.text() )
			.removeAttr( 'disabled' )
		;
		$form
			.animate( {
				opacity: '1'
			}, 100 )
			.removeClass( 'elementor-form-waiting' )
		;
		$form.trigger( 'error' );
	},

	handleSubmit: function( event ) {
		var $form = this.elements.$form;

		event.preventDefault();

		if ( $form.hasClass( 'elementor-form-waiting' ) ) {
			return false;
		}

		this.beforeSend();

		$.ajax( {
			url: $form.attr('action'),
			type: 'POST',
			dataType: 'json',
			data: this.getFormData(),
			processData: false,
			contentType: false,
			success: $.proxy( this, 'onSuccess' ),
			error: $.proxy( this, 'onError' )
		} );
	}
});

module.exports = function( $scope, $ ) {
	new WidgetAjaxForm( $scope );
};

/***/ }),

/***/ 201:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var SlideshowCarouselHandler = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				carousel: '.elementor-slideshow'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$carousel: this.$element.find(selectors.carousel)
		};
	},

	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		var elementSettings = this.getElementSettings(),
			centerPadding = elementSettings.center_padding && elementSettings.center_padding.size + '',
			centerPaddingTablet = elementSettings.center_padding_tablet && elementSettings.center_padding_tablet.size + '',
			centerPaddingMobile = elementSettings.center_padding_mobile && elementSettings.center_padding_mobile.size + '',
			breakpoints = ceFrontend.config.breakpoints;

		var slickOptions = {
			touchThreshold: 100,
			slidesToShow: 1,
			slidesToScroll: 1,
			swipeToSlide: 1,
			variableWidth: 'yes' === elementSettings.variable_width,
			centerMode: 'yes' === elementSettings.center_mode,
			centerPadding: centerPadding ? centerPadding + elementSettings.center_padding.unit : void 0,
			autoplay: 'yes' === elementSettings.autoplay,
			autoplaySpeed: elementSettings.autoplay_speed,
			infinite: 'yes' === elementSettings.infinite,
			pauseOnHover: 'yes' === elementSettings.pause_on_hover,
			speed: elementSettings.speed,
			arrows: -1 !== ['arrows', 'both'].indexOf(elementSettings.navigation),
			dots: -1 !== ['dots', 'both'].indexOf(elementSettings.navigation),
			rtl: 'rtl' === elementSettings.direction,
			fade: true,
		};

		this.elements.$carousel.slick(slickOptions);
	}
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(SlideshowCarouselHandler, { $element: $scope });
};

/***/ }),

/***/ 202:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var AjaxTabHandler = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				carousel: '.elementor-block-carousel'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$carousel: this.$element.find(selectors.carousel)
		};
	},

	onInit: function onInit() {
		var _this = this;
		
		var tabWidget = this.$element.find('.product-tabs-widget'),
			tabTitle = tabWidget.find('.nav-tabs'),
			tabContent = tabWidget.find('.tab-content');

		_this.initSlider(tabWidget.find('.elementor-block-carousel'));	

		var cache = [];	
		if( tabContent.find('.tab-pane').length = 1 ) {
			var first_tab_id = tabTitle.find('.nav-item').eq(0).data('id');
	        cache[first_tab_id] = tabContent.find('.tab-pane').html()
	    }
	    var height = tabContent.find('.tab-pane').eq(0).height();
		tabTitle.find('.nav-item').on('click' , function(e){
			e.preventDefault();
	        var $this = $(this),
	            idTab = $this.data('id'),
	            tabData = $('#tab-pane-' + idTab).data('tab_content');

	        tabTitle.find('.nav-item .nav-link').removeClass('active');
	        tabContent.find('.tab-pane').removeClass('active');
	        $('#tab-pane-'+ idTab).addClass('active');
	        $this.find('.nav-link').addClass('active');

	        if(!tabWidget.data('ajax')){
	        	return;
	        }
	        _this.loadTab(tabData , $this, idTab, tabContent , cache, height, function(data) {
	            if( data ) {
	                tabContent.find('#tab-pane-' + idTab).append(data.html);
	                _this.initSlider($('#tab-pane-'+ idTab));
	            }
	        });
			
		})	
	},
	initSlider: function initSlider($target){
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		$target.on('init', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-slide.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-slide.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-slide.slick-active').eq(slideToShow).addClass('last-active');
		});

		var elementSettings = this.getElementSettings(),
			slidesToShow = +elementSettings.slides_to_show || elementSettings.default_slides_desktop,
			isSingleSlide = 1 === slidesToShow,
			centerPadding = elementSettings.center_padding && elementSettings.center_padding.size + '',
			centerPaddingTablet = elementSettings.center_padding_tablet && elementSettings.center_padding_tablet.size + '',
			centerPaddingMobile = elementSettings.center_padding_mobile && elementSettings.center_padding_mobile.size + '',
			breakpoints = ceFrontend.config.breakpoints;

		var slickOptions = {
			touchThreshold: 100,
			slidesToShow: slidesToShow,
			slidesToScroll: +elementSettings.slides_to_scroll || 1,
			swipeToSlide: !elementSettings.slides_to_scroll,
			variableWidth: 'yes' === elementSettings.variable_width,
			centerMode: 'yes' === elementSettings.center_mode,
			centerPadding: centerPadding ? centerPadding + elementSettings.center_padding.unit : void 0,
			autoplay: 'yes' === elementSettings.autoplay,
			autoplaySpeed: elementSettings.autoplay_speed,
			infinite: 'yes' === elementSettings.infinite,
			pauseOnHover: 'yes' === elementSettings.pause_on_hover,
			speed: elementSettings.speed,
			arrows: -1 !== ['arrows', 'both'].indexOf(elementSettings.navigation),
			dots: -1 !== ['dots', 'both'].indexOf(elementSettings.navigation),
			rtl: 'rtl' === elementSettings.direction,
			responsive: [{
				breakpoint: breakpoints.lg,
				settings: {
					centerPadding: centerPaddingTablet ? centerPaddingTablet + elementSettings.center_padding_tablet.unit : void 0,
					slidesToShow: +elementSettings.slides_to_show_tablet || elementSettings.default_slides_tablet,
					slidesToScroll: +elementSettings.slides_to_scroll_tablet || 1,
					swipeToSlide: !elementSettings.slides_to_scroll_tablet,
					autoplay: 'yes' === elementSettings.autoplay_tablet,
					infinite: elementSettings.infinite_tablet ? 'yes' === elementSettings.infinite_tablet : void 0
				}
			}, {
				breakpoint: breakpoints.md,
				settings: {
					centerPadding: centerPaddingMobile ? centerPaddingMobile + elementSettings.center_padding_mobile.unit : (
						centerPaddingTablet ? centerPaddingTablet + elementSettings.center_padding_tablet.unit : void 0
					),
					slidesToShow: +elementSettings.slides_to_show_mobile || elementSettings.default_slides_mobile,
					slidesToScroll: +elementSettings.slides_to_scroll_mobile || 1,
					swipeToSlide: !elementSettings.slides_to_scroll_mobile,
					autoplay: 'yes' === elementSettings.autoplay_mobile,
					infinite: elementSettings.infinite_mobile ? 'yes' === elementSettings.infinite_mobile : void 0
				}
			}, {
				breakpoint: 320,
				settings: {
					centerPadding: centerPaddingMobile ? centerPaddingMobile + elementSettings.center_padding_mobile.unit : (
						centerPaddingTablet ? centerPaddingTablet + elementSettings.center_padding_tablet.unit : void 0
					),
					slidesToShow: 1,
					slidesToScroll: 1,
					swipeToSlide: !elementSettings.slides_to_scroll_mobile,
					autoplay: 'yes' === elementSettings.autoplay_mobile,
					infinite: elementSettings.infinite_mobile ? 'yes' === elementSettings.infinite_mobile : void 0
				}
			}]
		};

		if (isSingleSlide) {
			slickOptions.fade = 'fade' === elementSettings.effect;
		}

		$target.slick(slickOptions);

		$target.on('afterChange', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-slide.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-slide.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-slide.slick-active').eq(slideToShow).addClass('last-active');
		});
	},
	loadTab: function loadTab(tabData, $this, idTab, tabs, cache , height, callback){
		if( cache[idTab] ) {
	        return;
	    } else {
	        tabs.append('<div class="tab-loading" style="height:'+ height +'px"></div>');
	    };
		$.ajax({
	        url: ceFrontend.config.urls.front_ajax,
	        data: {
	            'action': 'tabProducts',
				'tabData' : tabData,
				'idTab' : idTab,
	        },
	        dataType: 'json',
	        method: 'POST',
	        success: function(data) {
	        	cache[idTab] = data;
	        	callback(data);
	        },
	        error: function(data) {
	            console.log('Ajax error');
	        },
	        complete: function() {
	            tabs.find('.tab-loading').remove();
	        },
	    });
	}
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(AjaxTabHandler, { $element: $scope });
};

/***/ }),
/***/ 203:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var PromoHandler = elementorModules.frontend.handlers.Base.extend({
	getDefaultSettings: function getDefaultSettings() {
		return {
			selectors: {
				carousel: '.promo-widget'
			}
		};
	},

	getDefaultElements: function getDefaultElements() {
		var selectors = this.getSettings('selectors');

		return {
			$carousel: this.$element.find(selectors.carousel)
		};
	},
	onInit: function onInit() {
		var _this = this,
			promo = this.$element.find('.promo-widget'),
			id = 'promo-' + this.$element.data('id'),
			closeBtn = this.$element.find('.promo-close-btn'),
			cookieTime = closeBtn.data('close_time');
		_this.initSlider(promo.find('.elementor-block-carousel'));
		closeBtn.on('click', function(){
			promo.slideUp();
			_this.setCookie(id, 1, cookieTime)
		})
	},
	setCookie: function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    },
	initSlider: function initSlider($target){
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

		var elementSettings = this.getElementSettings();

		var slickOptions = {
			touchThreshold: 100,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: 'yes' === elementSettings.autoplay,
			autoplaySpeed: elementSettings.autoplay_speed,
			infinite: true,
			pauseOnHover: true,
			speed: elementSettings.speed,
			arrows: -1 !== ['arrows', 'both'].indexOf(elementSettings.navigation),
			dots: -1 !== ['dots', 'both'].indexOf(elementSettings.navigation),
		};

		$target.slick(slickOptions);
	},
});

module.exports = function ($scope) {
	ceFrontend.elementsHandler.addHandler(PromoHandler, { $element: $scope });
};

/***/ }),
/***/ 204:
/***/ (function(module, exports, __webpack_require__) {

	"use strict";

	var SaleHandler = elementorModules.frontend.handlers.Base.extend({
		getDefaultSettings: function getDefaultSettings() {
			return {
				selectors: {
					carousel: '.product-sale-widget'
				}
			};
		},
	
		getDefaultElements: function getDefaultElements() {
			var selectors = this.getSettings('selectors');
	
			return {
				$carousel: this.$element.find(selectors.carousel)
			};
		},
		onInit: function onInit() {
			var _this = this,
				saleWidget = this.$element.find('.product-sale-widget'),
				countDownSelector = saleWidget.find('.specific-prices-timer');
			
			_this.initCountDown(countDownSelector);	
		},
		initCountDown: function initCountDown($target){
			var date_y = $target.attr('data-date-y'),
				date_m = $target.attr('data-date-m'),
				date_d = $target.attr('data-date-d'),
				date_h = $target.attr('data-date-h'),
				date_mi= $target.attr('data-date-mi'),
				date_s = $target.attr('data-date-s');

			$target.countdown({
				until: new Date(date_y,date_m-1,date_d,date_h,date_mi,date_s),
				labels: ['Years', 'Months', 'Weeks', vectheme.cd_days_text, vectheme.cd_hours_text, vectheme.cd_mins_text, vectheme.cd_secs_text],
				labels1: ['Year', 'Month', 'Week', vectheme.cd_day_text, vectheme.cd_hour_text, vectheme.cd_min_text, vectheme.cd_sec_text],
			});
		},
		
	});
	
	module.exports = function ($scope) {
		ceFrontend.elementsHandler.addHandler(SaleHandler, { $element: $scope });
	};

/***/ }),
/******/ });

// Quick View
$('html').on('click.ce', '.elementor-quick-view', function(e) {
	e.preventDefault();

	if (window.prestashop && prestashop.emit) {
		prestashop.emit('clickQuickView', {
			dataset: $(this).closest('.elementor-product-miniature').data()
		});
	} else {
		this.rel = $(this).addClass('quick-view').closest('a').prop('href');
	}
});

$(function ceReady() {
	// Fix for category description
	$('#js-product-list-header').attr('id', 'product-list-header');
});



