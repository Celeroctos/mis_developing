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
            }).addClass("form-control");
        var g = $("<div>", {
            class: "multiple-control",
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
                }),
				style: "margin-right: 2px"
            })
        ).append(
            $("<button>", {
                class: "btn btn-default multiple-down-button",
                type: "button",
                html: $("<span>", {
                    class: "glyphicon glyphicon-arrow-down"
                }),
				style: "margin-right: 2px"
            })
        ).append(
            $("<button>", {
                class: "btn btn-default multiple-up-button",
                type: "button",
                html: $("<span>", {
                    class: "glyphicon glyphicon-arrow-up"
                }),
				style: "margin-right: 2px"
            })
        ).append(
			$("<button>", {
				class: "btn btn-default multiple-insert-button",
				type: "button",
				html: $("<span>", {
					class: "glyphicon glyphicon-plus"
				})
			}).hide()
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
			var value = $.valHooks["select"].get(this);
			for (var i in value) {
				$(this).find("option[value='" + value[i] + "']").get(0).selected = false;
			}
			me.choose(value, true);
		});
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
			var array = [];
            me.selector().find("select.multiple-value").children("option:not(:hidden)").each(function(i, item) {
				array.push($(item).val());
            });
			me.choose(array);
        });
        this.selector().find(".multiple-up-button").click(function() {
			me.selector().find(".multiple-chosen").each(function(i, item) {
                me.remove($(item).children("div"));
            });
        });
		var link = null;
		this.selector().find(".multiple-value option[value='-3']").each(function(i, opt) {
			(link = $(opt).addClass("multiple-really-not-visible")).parents(".multiple")
				.find(".multiple-insert-button").show();
		});
		this.selector().find(".multiple-control .multiple-insert-button:visible").click(function() {
			applyInsertForSelect.call(me.selector().find(".multiple-value").get(0));
		});
	};

	Multiple.prototype.selected = function(clear) {
		var result = [],
			options = this.selector().find("select[multiple]")[0].options,
			opt;
		for (var i = 0, j = options.length; i < j; i++) {
			opt = options[i];
			if (opt.selected) {
				result.push(opt.value || opt.text);
			}
			if (clear) {
				options[i].selected = false;
			}
		}
		return result;
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

	Multiple.prototype.clear = function() {
		var me = this;
		this.selector().find(".multiple-chosen").each(function(i, w) {
			me.remove($(w).children("div"));
		});
	};

	Multiple.prototype.choose = function(key, slow) {
		var me = this;
		var multiple = this.selector();
		if (typeof key == "string") {
			if (key.trim() !== "") {
				key = $.parseJSON(key);
			} else {
				key = [];
			}
		}
		if (Array.isArray(key)) {
			for (var i in key) {
				this.choose(key[i], slow);
			}
			if (!key.length) {
				me.clear();
			}
			return void 0;
		}
		var value = multiple.find("select.multiple-value")
			.find("option[value='" + key + "']");
		if (slow) {
			value.fadeOut(250);
		} else {
			value.hide();
		}
        var name = value.text();
		if (!name.length) {
			return void 0;
		}
		var r, t;
		t = $("<div>", {
			style: "text-align: left; width: 100%",
			class: "multiple-chosen disable-selection row"
		}).append(
			$("<div>", {
				text: name
			}).data("key", key)
		).append(
			r = $("<span>", {
				class: "glyphicon glyphicon-remove",
				style: "color: #af1010; font-size: 15px; cursor: pointer"
			})
		);
		multiple.find("div.multiple-container").append(t);
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
        $("select[multiple][data-ignore!='multiple']").multiple().bind("style", function() {
            var filter = $(this).multiple("property", "filter");
            var style = $(this).attr("style").split(";");
            var css = {};
            for (var i in style) {
                var link = style[i].trim().split(":");
                if (link.length != 2) {
                    continue;
                }
                var key = link[0];
				if ($.inArray(key, filter)) {
					continue;
				}
                css[key] = link[1].trim();
            }
            $(this).parent(".multiple").css(css);
        });
        $("select[multiple][data-ignore!='multiple'][value!='']").each(function() {
            if ($(this).attr("value") != void 0) {
                $(this).multiple("choose", $(this).attr("value"));
            }
            $(this).removeAttr("value");
        });
		$("select[multiple][data-ignore!='multiple']").each(function() {
			var result = [],
				options = this && this.options,
				opt;
			for (var i = 0, j = options.length; i < j; i++) {
				opt = options[i];
				if (opt.selected) {
					result.push(opt.value || opt.text);
				}
				options[i].selected = false;
			}
			if (result.length > 0) {
				$(this).multiple("choose", result);
			}
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