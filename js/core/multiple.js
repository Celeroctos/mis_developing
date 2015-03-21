var Laboratory = Laboratory || {};

(function(Lab) {

	"use strict";

	var Multiple = function(properties, selector) {
		Lab.Component.call(this, properties, {}, selector);
	};

	Lab.extend(Multiple, Lab.Component);

	Multiple.prototype.render = function() {
		return $("<div>", {
			class: "multiple"
		}).append(this.selector().clone().data("lab", this).addClass("multiple-value")).append(
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
	};

	Multiple.prototype.remove = function(key) {
		key.parents(".multiple").find("select.multiple-value").append(
			$("<option>", {
				value: key.data("key"),
				text: key.text()
			})
		);
		key.parent(".multiple-chosen").remove();
	};

	Multiple.prototype.choose = function(key) {
		var multiple = this.selector();
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
			.find("option[value='" + key + "']").remove().text();
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

	$(document).ready(function() {
		$("select[multiple]").multiple();
	});
	$(document).bind("ajaxSuccess", function() {
		$("select[multiple]").multiple();
	});

})(Laboratory);