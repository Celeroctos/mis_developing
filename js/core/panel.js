var Core = Core || {};

(function(Core) {

	"use strict";

	var Panel = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			velocity: "fast"
		}, selector);
	});

	Panel.prototype.collapse = function() {
		this.selector().find(".panel-body").slideUp(
			this.property("velocity")
		);
		this.selector().find(".panel-collapse-button")
			.rotate(180, 500, 'swing', 0);
	};

	Panel.prototype.expand = function() {
		this.selector().find(".panel-body").slideDown(
			this.property("velocity")
		);
		this.selector().find(".panel-collapse-button")
			.rotate(360, 500, "swing", 180);
	};

	Panel.prototype.toggle = function() {
		if (this.selector().find(".panel-body").is(":visible")) {
			this.collapse();
		} else {
			this.expand();
		}
	};

	Panel.prototype.before = function() {
		this.selector().loading("render").find(".panel-update-button")
			.rotate(360, 500, "swing");
	};

	Panel.prototype.after = function() {
		this.selector().loading("destroy");
	};

	Panel.prototype.update = function() {
		var widget, me = this;
		if (!(widget = this.selector().attr("data-widget"))) {
			return void 0;
		} else if (!window["globalVariables"]["getWidget"]) {
			throw new Error("Layout hasn't declared [globalVariables::getWidget] field via [Widget::createUrl] method");
		}
		this.before();
		var params = $.parseJSON(this.selector().attr("data-attributes"));
		$.get(window["globalVariables"]["getWidget"], $.extend(params, {
			class: this.selector().attr("data-widget")
		}), function(json) {
			if (json["status"]) {
				me.selector().find(".panel-content").fadeOut("fast", function() {
					$(this).empty().append(json["component"]).hide().fadeIn("fast");
				});
			} else {
				$(json["message"]).message();
			}
		}, "json").always(function() {
			me.after();
		});
	};

	Core.createPlugin("panel", function(selector, properties) {
		var t;
		if (!$(selector).hasClass("panel")) {
			if ((t = $(selector).parents(".panel:eq(0)")).length != 0) {
				selector = t.get(0);
			} else {
				return void 0;
			}
		}
		return Core.createObject(new Panel(properties, $(selector)), selector, false);
	});

})(Core);