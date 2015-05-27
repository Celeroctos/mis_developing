var Core = Core || {};

(function(Core) {

	"use strict";

	const SORT_ASC  = 0;
	const SORT_DESC = 1;

	var Table = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			updateDelay: 250
		}, selector);
	});

	Table.prototype.update = function() {
		var config, me = this, table = this.selector();
		if (this.selector().trigger("table.update") === false) {
			return void 0;
		}
		this.before();
		if (config = table.attr("data-config")) {
			config = $.parseJSON(config);
			for (var i in config) {
				this.configure(i, config[i], false);
			}
		}
		config = this.property("config") || {};
		Core.loadTable(table.attr("data-widget"), table.attr("data-provider"), config, function(response) {
			me.after(function() {
				me.selector().empty().append($(response["component"]).children());
				me.selector().attr($(response["component"]).getAttributes());
				me.selector().trigger("table.updated");
			});
		}).fail(function() {
			me.after();
		}, false);
	};

	Table.prototype.configure = function(attribute, properties, strong) {
		var config = this.property("config") || {},
			scope = config[attribute] || {};
		if (strong === void 0) {
			strong = true;
		}
		for (var key in properties) {
			if (scope[key] && !strong) {
				continue;
			}
			scope[key] = properties[key];
		}
		if (!$.isPlainObject(properties)) {
			scope = properties;
		}
		config[attribute] = scope;
		this.property("config", config);
		return this;
	};

	Table.prototype.before = function() {
		var me = this;
		me._before = true;
		setTimeout(function() {
			if (me._before == true) {
				me.selector().loading("render");
			}
		}, 250);
	};

	Table.prototype.after = function(callback) {
		var me = this;
		me._before = false;
		if (this.selector().data("core-loading")) {
			me.selector().loading("destroy", callback);
		} else {
			callback && callback();
		}
	};

	Table.prototype.order = function(key) {
		var params = {};
		if (key.charAt(0) == '-') {
			params[key.substr(1)] = SORT_DESC;
		} else {
			params[key] = SORT_ASC;
		}
		this.configure("sort", {
			defaultOrder: params
		}).update();
	};

	Table.prototype.page = function(page) {
		this.configure("pagination", {
			currentPage: page
		}).update();
	};

	Table.prototype.limit = function(limit) {
		this.configure("pagination", {
			pageSize: limit
		}).update();
	};

	$.fn.table = Core.createPlugin("table", function(selector, properties) {
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