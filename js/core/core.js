var Core = Core || {};

(function(Core) {

    "use strict";

    /**
     * Extend child class with parent
     * @param child {function} - Child class
     * @param parent {function} - Parent class
     * @returns {function} - Child class
     */
    Core.extend = function(child, parent) {
        var F = function() {};
        F.prototype = parent.prototype;
        child.prototype = new F();
        child.prototype.constructor = child;
        child.superclass = parent.prototype;
        return child;
    };

    /**
     * Construct component
	 * @param properties {{}} - Object with properties
     * @param [defaults] {{}|null|undefined} - Default component's properties
     * @param [selector] {jQuery|null|undefined} - Component's selector or nothing
     * @constructor
     */
    var Component = function(properties, defaults, selector) {
		if (typeof (this._name = properties["<plugin>"]) !== "string") {
			throw new Error("Can't resolve component's plugin name as string, found \"" + typeof this._name + "\"");
		}
        this._properties = $.extend(
            defaults || {}, properties || {}
        );
        this._selector = selector || this.render();
    };

	/**
	 * That method returns name of data attribute for
	 * current component
	 * @returns {string} - Attribute name
	 */
	Component.prototype.getDataAttribute = function() {
		if (!this._name) {
			throw new Error("Component hasn't been registered as jQuery plugin");
		} else {
			return this._name;
		}
	};

	/**
	 * Get value of attribute by it's key or set it's value
	 * @param {String} key - Value's key to set/get
	 * @param {*} [value] - Value to set
	 * @returns {*} - Value associated with that key
	 */
	Component.prototype.attr = function(key, value) {
		if (!this._attributes) {
			this._attributes = this.selector().attr("data-attributes") || "{}";
			this._attributes = $.parseJSON(this._attributes);
		}
		if (arguments.length > 1) {
			this._attributes[key] = value;
			this.selector().attr("data-attributes", this._attributes);
		}
		return this._attributes[key];
	};

    /**
     * Override that method to return jquery item
     */
    Component.prototype.render = function() {
        throw new Error("Component/render() : Not-Implemented");
    };

    /**
     * Override that method to activate just created jQuery item
     */
    Component.prototype.activate = function() {
        /* Ignored */
    };

    /**
     * Override that method to provide some actions before update
     */
    Component.prototype.before = function() {
        /* Ignored */
    };

    /**
     * Override that method to provide some actions after update
     */
    Component.prototype.after = function() {
        /* Ignored */
    };

    /**
     * Set/Get component's jquery selector
     * @param [selector] {jQuery} - New jquery to set
     * @returns {jQuery} - Component's jquery
     */
    Component.prototype.selector = function(selector) {
        if (arguments.length > 0 && selector !== void 0) {
			if (!(selector instanceof jQuery)) {
				throw new Error("Selector must be an instance of jQuery object");
			}
            if (!selector.data(this.getDataAttribute())) {
                selector.data(this.getDataAttribute(), this);
            }
            this._selector = selector;
        }
        return this._selector;
    };

    /**
     * Get/Set some property
     * @param key {string} - Property key
     * @param value  {*} - Property value
     * @returns {*} - New or old property's value
     */
    Component.prototype.property = function(key, value) {
        if (arguments.length > 1) {
            this._properties[key] = value;
        }
        return this._properties[key];
    };

    /**
     * Override that method to destroy you component or
     * it will simply remove selector
     */
    Component.prototype.destroy = function() {
		this.selector().data(this.getDataAttribute(), false);
    };

    /**
     * Update method, will remove all selector, render
     * new, activate it and append to previous parent
     */
    Component.prototype.update = function() {
        this.before();
        this.selector().replaceWith(
            this.selector(this.render())
        );
        this.after();
        this.activate();
    };

    /**
     * Sub-Component class, use it to declare sub component, that instance
     * won't be rendered automatically, you shall manually invoke render method
     * @param component {Component} - Parent component
     * @param [selector] {jQuery} - jQuery's selector or null
     * @constructor
     */
    var SubComponent = function(component, selector) {
        this.component = function() {
            return component;
        };
        Component.call(this, {}, {}, selector || true);
    };

    Core.extend(SubComponent, Component);

    /**
     * That method will fetch properties values from
     * parent's component
     * @param key {String} - Property name
     * @param value {*} - Property value
     */
    SubComponent.prototype.property = function(key, value) {
        return this.component().property.apply(this.component(), arguments);
    };

	/**
	 * Common class with static helper methods
	 * @constructor
	 */
	var Common = function() {
	};

	/**
	 * Cleanup component's value and all errors or warnings
	 * @static
	 * @param component
	 */
	Common.cleanup = function(component) {
		$(component).find(".form-group")
			.removeClass("has-error")
			.removeClass("has-warning")
			.removeClass("has-success");
		$(component).find("select:not([multiple])[data-cleanup!='false']").each(function(i, item) {
			$(item).val($(item).find("option:eq(0)").val());
		});
		var filters = [ "input[type!='button'][type!='submit']", "textarea", "select[multiple]" ];
		for (var i in filters) {
			filters[i] += "[data-cleanup!='false']";
		}
		$(component).find(filters.join(",")).val("");
	};

	/**
	 * Get url to get widget component
	 * @returns {String} - Path to widget action
	 * @static
	 */
	Common.getWidget = function() {
		return window["globalVariables"]["getWidget"];
	};

	/**
	 * Load widget
	 * @param {String} widget - Name of widget's class
	 * @param {{}} [attributes] - Widget's parameters
	 * @param {Function} [success] - Callback after fetch
	 */
	Common.loadWidget = function(widget, attributes, success) {
		return $.get(this.getWidget(), $.extend(attributes, {
			class: widget
		}), function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			} else {
				success && success(json["component"], json);
			}
		}, "json");
	};

	Core.postFormErrors = function(where, json) {
		var html = $("<ul>");
		for (var i in json["errors"] || []) {
			where.find("[name='" + i + "']").parents(".form-group").addClass("has-error");
			where.find("[id='" + i + "']").parents(".form-group").addClass("has-error");
			for (var j in json["errors"][i]) {
				$("<li>", {
					text: json["errors"][i][j]
				}).appendTo(html);
			}
		}
		return Core.createMessage({
			message: json["message"] + html.html(),
			delay: 10000
		});
	};

	var sendAjax = function(method, href, data, success) {
		return $[method](url(href), data, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Core.createMessage({
					message: json["message"],
					type: "success",
					sign: "ok"
				});
			}
			success && success(json);
		}, "json").fail(function() {
			return Core.createMessage({
				message: "Произошла ошибка при обработке запроса. Обратитесь к администратору"
			});
		});
	};

	Core.sendQuery = function(href, data, success) {
		return sendAjax("get", href, data, success);
	};

	Core.sendPost = function(href, data, success) {
		return sendAjax("post", href, data, success);
	};

	Core.resetFormErrors = function(where) {
		$(where).find(".form-group").removeClass("has-error");
	};

	Core.Component = Component;
	Core.SubComponent = SubComponent;
	Core.Common = Common;

	/**
	 * Create new component, which extends basic
	 * Component class
	 * @param component {Function} - New component class
	 * @returns {Function} - Same component instance
	 */
	Core.createComponent = function(component) {
		return Core.extend(component, Component);
	};

	/**
	 * Create new sub-component, which extends basic
	 * SubComponent class
	 * @param component {Function} - New component class
	 * @returns {Function} - Same component instance
	 */
	Core.createSubComponent = function(component) {
		return Core.extend(component, SubComponent);
	};

    /**
     * Create new component's instance and render to DOM
     * @param component {Component|Object} - Component's instance
     * @param selector {HTMLElement|string} - Parent's selector
     * @param [update] {Boolean} - Update component or not (default yes)
     */
    Core.createObject = function(component, selector, update) {
        $(selector).data(component.getDataAttribute(), component).append(
            component.selector()
        );
        if (update !== false) {
            component.update();
        } else {
            component.activate();
        }
        return component;
    };

	/**
	 * Create plugin for component
	 * @param plugin {String} - Name of jQuery plugin {@see Component#getDataAttribute}
	 * @param func {Function} - Function that registers component
	 * @static
	 */
	Core.createPlugin = function(plugin, func) {
		var attr = "core-" + plugin;
		var register = function(me, options, args, ret) {
			var r, a = [], s;
			for (var i in args) {
				if (i > 0) {
					a.push(args[i]);
				}
			}
			if (options !== void 0 && typeof options == "string") {
				var c = me.data(attr);
				if (!c) {
					if (!(c = register(me, {}, [], true))) {
						throw new Error("Component hasn't been initialized, create it first");
					}
					me.data(attr, c);
				}
				if (c[options] == void 0) {
					throw new Error("That component doesn't implement method \"" + options + "\"");
				}
				if ((r = c[options].apply(c, a)) !== void 0) {
					return r;
				}
			} else {
				/* if (!!me.data(attr)) {
					return void 0;
				} */
				if (typeof me != "function") {
					if (me.length) {
						s = me[0];
					} else {
						s = me.selector;
					}
					r = func(s, $.extend(options, {
						"<plugin>": attr
					}));
				} else {
					r = func($.extend(options, {
						"<plugin>": attr
					}));
				}
				if (ret) {
					return r;
				}
			}
			return void 0;
		};
		return $.fn[plugin] = function(options) {
			var t;
			var args = arguments || [];
			if (this.length > 1) {
				var r = [];
				this.each(function(it, me) {
					if ((t = register($(me), options, args)) !== void 0) {
						r.push(t);
					}
				});
				if (r.length > 0) {
					return r;
				}
			} else {
				if ((t = register($(this), options, args)) !== void 0) {
					return t;
				}
			}
			return this;
		};
	};

	/**
	 * Create callback on ready event, which will be invoked
	 * after DOM load and any success ajax request
	 * @param func {Function} - Function to execute
	 */
	Core.ready = function(func) {
		$(document).ready(func);
		$(document).bind("ajaxSuccess", func);
	};

    /**
     * Generate url based on Yii's base url
     * @param url {String} - Relative url
     * @returns {String} - Absolute url
     */
    window.url = function(url) {
		if (url.charAt(0) != "/") {
			url = "/" + url;
		}
        return window["globalVariables"]["baseUrl"] + url;
    };

	window.serialize = function(obj, prefix) {
		var str = [];
		for(var p in obj) {
			if (obj.hasOwnProperty(p)) {
				var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
				str.push(typeof v == "object" ?
					serialize(v, k) :
				encodeURIComponent(k) + "=" + encodeURIComponent(v));
			}
		}
		return str.join("&");
	};

	$.fn.update = function() {
		return this.each(function() {
			var widget, params, me = this;
			if (!(widget = $(this).attr("data-widget")) || !(params = $(this).attr("data-attributes"))) {
				return void 0;
			} else if (!Core.Common.getWidget()) {
				throw new Error("Layout hasn't declared [globalVariables::getWidget] field via [Widget::createUrl] method");
			}
			$(this).loading();
			params = $.parseJSON(params);
			$.get(Core.Common.getWidget(), $.extend(params, {
				class: widget
			}), function(json) {
				if (json["status"]) {
					$(me).fadeOut("fast", function() {
						$(this).empty().append(json["component"]).hide().fadeIn("fast");
					});
				} else {
					$(json["message"]).message();
				}
			}, "json").always(function() {
				$(me).loading("reset");
			});
		});
	};

	$.fn.cleanup = function() {
		return this.each(function(i, e) {
			Core.Common.cleanup(e);
		});
	};

	$.fn.rotate = function(angle, duration, easing, deg, complete) {
		var args = $.speed(duration || 350, easing || "swing", deg || 0, complete);
		var step = args.step;
		deg = deg || 0;
		return this.each(function(i, e) {
			args.complete = $.proxy(args.complete, e);
			args.step = function(now) {
				$.style(e, 'transform', 'rotate(' + now + 'deg)');
				if (step) {
					return step.apply(e, arguments);
				}
			};
			$({ deg: deg }).animate({ deg: angle }, args);
		});
	};

})(Core);

/*
$(document).ready(function() {
$("input[data-regexp][type='text']").each(function(i, item) {
	var regexp = new RegExp($(item).data("regexp"));
	$(item).keydown(function(e) {
		console.log($(item).val());
		console.log(regexp.test($(item).val()));
	});
});
});
var isStrValid = function(str) {
return ((str.match(/[^\d^.]/) === null)
&& (str.replace(/\d+\.?\d?\d?/, "") === ""));
};

var node = dojo.byId("txt");
dojo.connect(node, "onkeyup", function() {
if (!isStrValid(node.value)) {
node.value = node.value.substring(0, node.value.length-1);
}
});
* */