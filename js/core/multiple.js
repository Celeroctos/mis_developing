var Laboratory = Laboratory || {};

(function(Lab) {

	"use strict";

	var Multiple = function(properties, selector) {
		Lab.Component.call(this, properties, {
            filter: [
                "height",
                "min-height",
                "max-height",
                "width",
                "min-width",
                "max-width"
            ],
            height: 150,
            multiplier: 2
        }, selector);
	};

	Lab.extend(Multiple, Lab.Component);

	Multiple.prototype.render = function() {
        var s = this.selector().clone().data("lab", this)
            .addClass("multiple-value").css({
                "min-height": this.property("height")
            });
        var g = $("<div>", {
            class: "btn-group multiple-control",
            role: "group",
            style: {
                width: this.selector().width()
            }
        }).append(
            $("<button>", {
                class: "btn btn-default multiple-collapse-button",
                type: "button",
                html: $("<span>", {
                    text: "Развернуть / Свернуть"
                })
            })
        ).append(
            $("<button>", {
                class: "btn btn-default multiple-down-button",
                type: "button",
                html: $("<span>", {
                    class: "glyphicon glyphicon-arrow-down"
                })
            })
        ).append(
            $("<button>", {
                class: "btn btn-default multiple-up-button",
                type: "button",
                html: $("<span>", {
                    class: "glyphicon glyphicon-arrow-up"
                })
            })
        );
		return $("<div>", {
			class: "multiple"
		}).append(s).append(g).append(
			$("<div>", {
				class: "multiple-container form-control"
			})
		);
	};

	Multiple.prototype.activate = function() {
		var me = this;
		this.selector().find("select.multiple-value").change(function() {
			me.choose($.valHooks["select"].get(this));
		});
		this.selector().find(".form-down-button").click(function() {
			$(this).parents(".form-group").find("select.multiple-value").children("option").each(function(i, item) {
				me.choose($(item).val());
			});
		});
		this.selector().find(".form-up-button").click(function() {
			$(this).parents(".form-group").find(".multiple-chosen").each(function(i, item) {
				me.remove($(item).children("div"));
			});
		});
        var heght = false;
        var collapsed = false;
        this.selector().find(".multiple-collapse-button").click(function() {
            var value = me.selector().find(".multiple-value");
            value.animate({
                "min-height": collapsed ? me.property("height") :
                    me.property("height") * me.property("multiplier")
            }, "fast");
            collapsed = !collapsed;
        });
        this.selector().find(".multiple-down-button").click(function() {
            $(this).parents(".form-group").find("select.multiple-value").children("option").each(function(i, item) {
                me.choose($(item).val());
            });
        });
        this.selector().find(".multiple-up-button").click(function() {
            $(this).parents(".form-group").find(".multiple-chosen").each(function(i, item) {
                me.remove($(item).children("div"));
            });
        });
	};

	Multiple.prototype.remove = function(key) {
        key.parents(".multiple").find("option[value='" + key.data("key") + "']").replaceWith(
            $("<option>", {
                value: key.data("key"),
                text: key.text()
            })
        );
		key.parent(".multiple-chosen").remove();
	};

	Multiple.prototype.choose = function(key) {
		var multiple = this.selector();
		if (typeof key == "string") {
			key = $.parseJSON(key);
		}
		if (Array.isArray(key)) {
			for (var i in key) {
				this.choose(key[i]);
			}
			if (!key.length) {
				multiple.find("div.multiple-container").empty();
			}
			return void 0;
		}
        var name = multiple.find("select.multiple-value")
            .find("option[value='" + key + "']").fadeOut("normal").text();
		if (!name.length) {
			return void 0;
		}
		var r, t;
		t = $("<div>", {
			style: "text-align: left; width: 100%",
			class: "multiple-chosen disable-selection"
		}).append(
			$("<div>", {
				text: name,
				style: "text-align: left; width: calc(100% - 15px); float: left"
			}).data("key", key)
		).append(
			r = $("<span>", {
				class: "glyphicon glyphicon-remove",
				style: "color: #af1010; font-size: 15px; cursor: pointer"
			})
		);
		multiple.find("div.multiple-container").append(t);
		var me = this;
		r.click(function() {
			me.remove($(this).parent("div").children("div"));
		});
	};

	$.valHooks["select-multiple"] = {
		container: function(item) {
			return $(item).parent(".multiple").children(".multiple-container");
		},
		set: function(item, list) {
			var multiple = $(item).parents(".multiple");
			var instance = multiple.data("lab");
			if (!instance) {
				instance = $(item).data("lab");
			}
			if (typeof list !== "string") {
				list = JSON.stringify(list);
			}
			if (!list.length || list == "[]") {
				multiple.find(".multiple-chosen div").each(function(i, div) {
					instance.remove($(div));
				});
				instance.choose([]);
			} else {
				instance.choose($.parseJSON(list));
			}
		},
		get: function(item) {
			var list = [];
			this.container(item).find(".multiple-chosen div").each(function(i, c) {
				list.push("" + $(c).data("key"));
			});
			return (list);
		}
	};

	Lab.createMultiple = function(selector, properties) {
		if (!$(selector).hasClass("multiple-value")) {
			return Lab.create(new Multiple(properties, $(selector)), selector, true);
		} else {
			return void 0;
		}
	};

	$.fn.multiple = Lab.createPlugin(
		"createMultiple"
	);

    Lab.ready(function() {
        $("select[multiple]").multiple().bind("style", function() {
            var filter = $(this).multiple("property", "filter");
            var style = $(this).attr("style").split(";");
            var css = {};
            for (var i in style) {
                var link = style[i].trim().split(":");
                if (link.length != 2) {
                    continue;
                }
                var key = link[0];
                if (hasArray(filter, key)) {
                    continue;
                }
                css[key] = link[1].trim();
            }
            $(this).parent(".multiple").css(css);
        });
        $("select[multiple][value!='']").each(function() {
            if ($(this).attr("value") != void 0) {
                $(this).multiple("choose", $(this).attr("value"));
            }
            $(this).attr("value", false);
        });
    });

    (function() {
        var ev = new $.Event('style'),
            orig = $.fn.css;
        $.fn.css = function() {
            var result = orig.apply(this, arguments || []);
            $(this).trigger(ev);
            return result;
        }
    })();

})(Laboratory);