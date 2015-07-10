var Core = Core || {};

(function(Core) {

	const SORT_ASC  = 0;
	const SORT_DESC = 1;

    "use strict";

	var Table = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			updateDelay: 250
		}, selector);
	});

	Table.prototype.update = function(config, success, overlay) {
        overlay = overlay !== void 0 ? overlay : true;
		var attr, me = this, table = this.selector();
		if (this.selector().trigger("table.update") === false) {
			return void 0;
		}
        if (overlay == true) {
            this.before();
        }
		if (attr = table.attr("data-config")) {
			attr = $.parseJSON(attr);
			for (var i in attr) {
				this.configure(i, attr[i], false);
			}
		}
		attr = this.property("config") || {};
		attr = $.extend(attr, config);
		Core.loadTable(table.attr("data-widget"), table.attr("data-provider"), attr, function(response) {
            var after = function() {
                me.selector().empty().append($(response).children());
                me.selector().attr($(response).getAttributes());
                me.selector().trigger("table.updated");
                success && success(response);
            };
            if (overlay == true) {
                me.after(after);
            } else {
                after();
            }
		});
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
		this._timer = setTimeout(function() {
			if (me._before == true) {
				me.selector().loading("render");
			}
		}, this.property("updateDelay"));
	};

	Table.prototype.after = function(callback) {
		var me = this;
		me._before = false;
		clearInterval(this._timer);
		if (this.selector().data("core-loading")) {
			me.selector().loading("reset", function() {
				callback && callback();
			});
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
		if (!$(selector).is("table")) {
			var t = $(selector).parents("table");
			if (t.length != 0) {
				selector = t[0];
			} else {
				return void 0;
			}
		}
		return Core.createObject(new Table(properties, $(selector)), selector, false);
	});

})(Core);