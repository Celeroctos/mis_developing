var Laboratory = Laboratory || {};

(function(Lab) {

	"use strict";

	var Address = function(properties, selector) {
		Lab.Component.call(this, properties, {
			list: {
				"region_name": {
					"label": "Регион / Город",
					"prefix": "",
					"style": "width: 100%"
				},
				"street_name": {
					"label": "Улица",
					"prefix": "ул",
					"style": "width: 100%"
				},
				"district_name": {
					"label": "Район",
					"prefix": "р",
					"style": "width: 100%"
				},
				"house_number": {
					"label": "Дом",
					"prefix": "д",
					"style": "width: 33%"
				},
				"flat_number": {
					"label": "Квартира",
					"prefix": "кв",
					"style": "width: 34%"
				},
				"post_index": {
					"label": "Индекс",
					"prefix": "п/и",
					"style": "width: 33%"
				}
			}
		}, selector);
	};

	Lab.extend(Address, Lab.Component);

	Address.prototype.render = function() {
		var p;
		var s = this.selector()
			.clone()
			.data("lab", this)
			.prop("readonly", true)
			.attr("aria-describedBy", "address-addon-" + count)
			.addClass("address-input");
		var h = $("<div>", {
			class: "input-group"
		}).append(s).append(
			$("<span>", {
				class: "input-group-addon address-edit-button",
				id: "address-addon-" + count
			}).append(
				$("<span>", {
					class: "glyphicon glyphicon-home"
				})
			)
		);
		var c = $("<div>", {
			class: "laboratory-address"
		}).append(h).append(p = $("<div>", {
			class: "panel panel-default address-body"
		}).append(
			$("<div>", {
				class: "panel-body"
			}).append(this.form())
		));
		p.hide();
		return c;
	};

	Address.prototype.form = function() {
		var id;
		if (this.selector().data("form")) {
			id = this.selector().data("form");
		} else {
			this.selector().attr("data-form", id = "form-" + count)
		}
		var c = $("<form>", {
			class: "address-container",
			id: id
		}).append($("<input>", {
			type: "hidden"
		}));
		var list = this.property("list");
		for (var i in list) {
			$("<input>", {
				placeholder: list[i]["label"],
				type: "text",
				class: "form-control col-xs-6",
				id: i,
				name: "AddressForm[" + i + "]",
				style: list[i]["style"]
			}).appendTo(c);
		}
		return c;
	};

	Address.prototype.calculate = function() {
		var b = this.selector().find(".panel-body");
		var t = "";
		var list = this.property("list");
		for (var i in list) {
			var v = b.find("#" + i).val();
			if (v == '') {
				continue;
			}
			t += list[i]["prefix"] + (list[i]["prefix"].length > 0 ? ". " : "") + v + ", ";
		}
		this.selector().find(".address-input").val(
			t.replace(/, $/, '')
		);
	};

	Address.prototype.serialize = function() {
		return this.selector().find("form").serialize();
	};

	Address.prototype.activate = function() {
		var me = this;
		this.selector().find(".address-edit-button").click(function() {
			me.toggle();
		});
		this.selector().find("input[data-laboratory]").dblclick(function() {
			me.toggle();
		});
		this.selector().find(".panel-body input").keyup(function() {
			me.calculate();
		});
		this.selector().find(".panel-body input").change(function() {
			me.calculate();
		});
	};

	Address.prototype.toggle = function(after) {
		if (this.selector().find(".address-body").css("display") == "none") {
			return this.open(after);
		} else {
			return this.close(after);
		}
	};

	Address.prototype.open = function(after) {
		return this.selector().find(".address-body").slideDown("fast", after);
	};

	Address.prototype.close = function(after) {
		return this.selector().find(".address-body").slideUp("fast", after);
	};

	Lab.createAddress = function(selector, properties) {
		if ($(selector).parent().parent().hasClass("laboratory-address")) {
			return void 0;
		}
		++count;
		return Lab.create(new Address(properties, $(selector)), selector, true);
	};

	$.fn.address = Lab.createPlugin(
		"createAddress"
	);

	Lab.ready(function() {
		$("[data-laboratory='address']").address();
	});

	var count = 0;

})(Laboratory);