var Core = Core || {};

(function(Core) {

	"use strict";

	var Panel = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			velocity: "slow"
		}, selector);
	});

	Panel.prototype.collapse = function() {
		this.selector().find(".panel-body").slideUp(
			this.property("velocity")
		);
	};

	Panel.prototype.expand = function() {
		this.selector().find(".panel-body").slideDown(
			this.property("velocity")
		);
	};

	Panel.prototype.toggle = function() {
		this.selector().find(".panel-body").slideToggle(
			this.property("velocity")
		);
	};

	Panel.prototype.before = function() {
		this.selector().loading({});
	};

	Panel.prototype.after = function() {
		this.selector().loading("reset");
	};

	Panel.prototype.update = function() {
		var widget;
		if (!(widget = this.selector().attr("data-widget"))) {
			//return void 0;
		}
		this.before();

		//this.after();
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