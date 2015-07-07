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
		var btn = this.selector().find(".panel-collapse-button");
		if (btn.children(".glyphicon").length > 0) {
			btn.children(".glyphicon").rotate(360, 500, 'swing', 0);
		} else {
			btn.rotate(360, 500, 'swing', 0);
		}
	};

	Panel.prototype.expand = function() {
		this.selector().find(".panel-body").slideDown(
			this.property("velocity")
		);
		var btn = this.selector().find(".panel-collapse-button");
		if (btn.children(".glyphicon").length > 0) {
			btn.children(".glyphicon").rotate(360, 500, "swing", 0);
		} else {
			btn.rotate(360, 500, "swing", 0);
		}
	};

	Panel.prototype.toggle = function() {
		if (this.selector().find(".panel-body").is(":visible")) {
			this.collapse();
		} else {
			this.expand();
		}
	};

	Panel.prototype.before = function(overlay) {
        overlay = overlay !== void 0 ? overlay : true;
		if (this.selector().data("core-loading")) {
			return void 0;
		}
        if (overlay) {
            this.selector().loading("render");
        }
		var refresh = this.selector().find(".panel-update-button");
		if (!refresh.length) {
			return void 0;
		}
		if (refresh[0].tagName != "SPAN") {
			refresh.children(".glyphicon").rotate(360, 500, "swing");
		} else {
			refresh.rotate(360, 500, "swing");
		}
	};

	Panel.prototype.after = function(success) {
		this.selector().loading("destroy", success);
	};

	Panel.prototype.replace = function(component) {
		this.selector().find(".panel-content").fadeOut("fast", function() {
			$(this).empty().append(component).hide().fadeIn("fast");
		});
	};

	Panel.prototype.update = function(success) {
		var widget, me = this;
		if (!(widget = this.selector().attr("data-widget"))) {
			return void 0;
		}
		if (this.selector().trigger("panel.update") === false) {
			return void 0;
		}
        this.before();
		var content = this.selector().find(".panel-content > *");
		if (content.length == 1 && content.is("table")) {
			this.selector().find(".panel-content > table").table("update", {}, function() {
                me.after();
            }, false);
			this.selector().trigger("panel.updated");
			return void 0;
		}
		var params = $.parseJSON(this.selector().attr("data-attributes"));
		if (params.length !== void 0 && !params.length) {
			params = {};
		}
		Core.loadPanel(this.selector().attr("data-widget"), params, function(component) {
			me.replace(component);
			var back;
			if (me.selector().data("core-loading")) {
				back = me.selector().data("core-loading").back;
			} else {
				back = null;
			}
			if (back) {
				$.when(back).done(function() {
					me.selector().trigger("panel.updated");
				});
			} else {
				me.selector().trigger("panel.updated");
			}
			success && success.call(me, component);
		}).always(function() {
			me.after();
		});
	};

	$.fn.panel = Core.createPlugin("panel", function(selector, properties) {
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