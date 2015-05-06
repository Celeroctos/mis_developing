var Core = Core || {};

(function(Core) {

	"use strict";

	var Table = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			updateDelay: 250
		}, selector);
	});

	Table.prototype.update = function(parameters) {
		var me = this, table = this.selector();
		this.before();
		var data = $.extend({
			class: table.attr("data-widget"),
			currentPage: this.property("currentPage"),
			orderBy: this.property("orderBy"),
			pageLimit: this.property("pageLimit"),
			searchCriteria: this.property("searchCriteria")
		}, parameters || {});
		var params = $.parseJSON(this.selector().attr("data-attributes"));
		$.get(this.selector().data("url"), $.extend(params, data), function(json) {
			if (!Message.display(json)) {
				return void 0;
			}
			me.after();
			me.selector().replaceWith(
				$(json["component"]).data(me.getDataAttribute(), me)
			);
		}, "json").fail(function() {
			me.after();
		});
	};

	Table.prototype.before = function() {
		var me = this;
		setTimeout(function() {
			me.selector().loading("render");
		}, this.property("updateDelay"));
		this.selector().trigger("table.update");
	};

	Table.prototype.after = function() {
		if (this.selector().data("core-loading")) {
			this.selector().loading("destroy");
		}
		this.selector().trigger("table.updated");
	};

	Table.prototype.find = function(condition) {
		this.property("searchCriteria", condition);
		this.update();
	};

	Table.prototype.fetch = function(properties) {
		for (var key in properties) {
			this.property(key, properties[key]);
		}
		this.update();
	};

	Table.prototype.order = function(key) {
		var order, g, match;
		if ((g = this.selector().find(".table-order")).length > 0) {
			if (g.hasClass("table-order-desc")) {
				order = g.parents("td").data("key") + " desc";
			} else {
				order = g.parents("td").data("key");
			}
			match = order.split(" ");
			if (key == match[0]) {
				if (match[1] == "desc") {
					order = match[0];
				} else {
					order = match[0] + " desc";
				}
			} else {
				order = key;
			}
		} else {
			order = key;
		}
		this.fetch({
			orderBy: order
		});
	};

	Table.prototype.page = function(page) {
		this.fetch({
			currentPage: +page
		});
	};

	Table.prototype.limit = function(limit) {
		this.fetch({
			pageLimit: +limit
		});
	};

	Core.createPlugin("table", function(selector, properties) {
		var t;
		if ($(selector).get(0).tagName != "TABLE") {
			if ((t = $(selector).parents("table")).length != 0) {
				selector = t.get(0);
			} else {
				return void 0;
			}
		}
		return Core.createObject(new Table(properties, $(selector)), selector, false);
	});

})(Core);