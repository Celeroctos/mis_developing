
var Laboratory_Treatment_Header = {
	ready: function() {
		var me = this;
		$("button.treatment-header-rounded:not([data-target])").click(function() {
			me.active && me.active.removeClass("treatment-header-wrapper-active");
			me.active = $(this).addClass("treatment-header-wrapper-active");
		});
		this.active = $(".treatment-header").find(".treatment-header-wrapper-active");
		if (!this.active.length) {
			this.active = null;
		}
		$("button.treatment-header-rounded[data-tab]").click(function() {
			$("button.treatment-header-rounded[data-tab]").each(function(i, t) {
				$($(t).data("tab")).addClass("no-display");
			});
			$($(this).data("tab")).removeClass("no-display")
				.trigger("change");
			/* $(".panel[data-widget]").panel("update"); */
		});
		$("#header-register-direction-button").click(function() {
			var me = $(this);
			me.loading({
				image: url("images/ajax-loader.gif"),
				width: 30,
				height: 30
			}).loading("render");
			Core.sendQuery("laboratory/medcard/generate", {}, function(response) {
				$("#laboratory-medcard-number, span[id='card_number']").val(response["number"]);
				$(me.attr("data-target")).modal("show");
			}).always(function() {
				me.loading("reset");
			});
		});
	},
	active: null
};

var Laboratory_MedcardEditableViewer_Modal = {
	check: function() {
		if (this.copied !== false) {
			$("#medcard-editable-viewer-modal #insert-button").removeProp("disabled");
		} else {
			$("#medcard-editable-viewer-modal #insert-button").prop("disabled", true);
		}
	},
	ready: function() {
		var me = this, modal = $("#medcard-editable-viewer-modal"), v;
		modal.on("show.bs.modal", function() {
			modal.find("input#card_number").val(v = $("#laboratory-medcard-number").val());
			modal.find("span#card_number").text(v);
			me.check();
			$("#medcard-editable-viewer-modal select#analysis_type_id").trigger("change");
		}).on("shown.bs.modal", function() {
			$("#medcard-editable-viewer-modal select#analysis_type_id").trigger("change");
		});
		modal.find("#copy-button").click(function() {
			var json = [];
			modal.find("form").each(function(i, f) {
				var j = {};
				$(f).find("input, select, textarea").each(function(i, it) {
					j[$(it).attr("id")] = $(it).val();
				});
				json[$(f).attr("id")] = j;
			});
			me.copied = json;
			me.check();
			Core.createMessage({
				message: "Данные скопированы",
				sign: "ok",
				type: "success"
			});
		});
		modal.find("#insert-button").click(function() {
			if (me.copied === false) {
				return void 0;
			}
			var json = me.copied;
			for (var i in json) {
				var f = $("[id='" + i + "']");
				for (var j in json[i]) {
					f.find($("[id='" + j + "']")).val(json[i][j]);
				}
			}
			Core.createMessage({
				message: "Данные вставлены",
				sign: "ok",
				type: "success"
			});
		});
		modal.find("#clear-button").click(function() {
			Core.Common.cleanup(modal);
			me.check();
			Core.createMessage({
				message: "Данные очищены",
				sign: "ok",
				type: "success"
			});
		});
		modal.find("#save-button").click(function() {
			Core.resetFormErrors(modal);
			var forms = [];
			modal.find("form").each(function(i, f) {
				forms.push($(f).serialize());
			});
			$.post(url("laboratory/direction/register"), forms.join("&"), function(json) {
				if (json["errors"]) {
					Core.postFormErrors(modal, json);
				} else {
					if (!json["status"]) {
						return Core.createMessage({
							message: json["message"]
						});
					} else if (json["message"]) {
						Core.createMessage({
							type: "success",
							sign: "ok",
							message: json["message"]
						});
					}
				}
				if (json["status"]) {
					$("#treatment-laboratory-medcard-table-panel .table:eq(0)").table("update");
					modal.modal("hide");
					Laboratory_DirectionTable_Widget.show(json["direction"]);
				}
			}, "json");
		});
		modal.find("#treatment-document-control-wrapper label[data-target]").click(function() {
			$($(this).attr("data-target")).slideToggle("normal");
			if ($(this).children("input[type='checkbox']").prop("checked")) {
				$(this).children(".glyphicon").rotate(360, 350, "swing", 180);
			} else {
				$(this).children(".glyphicon").rotate(180, 350, "swing", 0);
			}
		});
	},
	load: function(model) {
		var modal = $("#medcard-editable-viewer-modal");
		Core.Common.cleanup(modal);
		var put = function(from, key, value) {
			if (key == "card_number") {
				return void 0;
			}
			var offset = 0, chances = [
				value, -1, 0, 1
			];
			from.find("[id='" + key + "']").each(function(i, item) {
				do {
					$(item).val(chances[offset]);
					if ($(item)[0].tagName != "SELECT" || ++offset > chances.length) {
						break;
					}
				} while ($(item).val() === null);
			});
		};
		var forms = {
			medcard_id: "form[data-form='LMedcardForm']",
			patient_id: "form[data-form='LPatientForm']"
		};
		for (var i in model) {
			var m = model[i], f, base;
			if (i in forms && (f = modal.find(forms[i])).length) {
				base = f;
			} else if ((f = modal.find("#" + i)).length > 0 && f.data("form")) {
				base = $("#" + f.data("form"));
			} else {
				base = modal.find(".modal-body");
			}
			for (var j in m) {
				put(base, j, m[j]);
			}
		}
		modal.find("input[data-laboratory='address']").address("calculate");
	},
	copied: false
};

var Laboratory_MedcardSearch_Widget = {
	ready: function() {
		var me = this;
		$(document).on("click", "[id='medcard-search-button']", function() {
			me.search($(this).parents(".medcard-search-wrapper:eq(0)"));
		});
		$("#medcard-search-table-wrapper").on("click", ".pagination li:not(:disabled)", function() {
			me.reset();
		});
	},
	search: function(wrapper) {
		wrapper.find("#medcard-search-button").button("loading");
		var table = wrapper.find("table");
		var data = wrapper.find("#medcard-search-form").serialize() + "&" +
			wrapper.find("#medcard-range-form").serialize() + "&provider=" + table.attr("data-provider");
		data += "&config=" + encodeURIComponent(table.attr("data-config"));
		Core.sendPost("laboratory/medcard/search", data, function(json) {
			table.replaceWith($(json["component"]));
			Core.createMessage({
				message: "Таблица обновлена",
				sign: "ok",
				type: "success",
				delay: 2000
			});
		}, "json").always(function() {
			wrapper.find("#medcard-search-button").button("reset");
		});
	},
	click: function(id) {
		var me = this;
		me.active && me.active.removeClass("medcard-table-active");
		me.active = $(this).addClass("medcard-table-active");
		if (me.active) {
			$("#medcard-edit-button").removeClass("disabled");
		}
		this.id = id;
	},
	reset: function() {
		this.active = this.id = null;
		$("#medcard-edit-button").addClass("disabled");
	},
	active: null,
	id: null
};

var Laboratory_MedcardSearch_Modal = {
	ready: function() {
		var me = this;
		var modal = $("#mis-medcard-search-modal");
		modal.on("show.bs.modal", function() {
			$(this).find("#load").prop("disabled", "disabled");
		});
		modal.on("click", "tr[data-id]", function() {
			me.id = $(this).data("id");
			$(modal.find("#load")[0]).text("Открыть (" + me.id + ")").removeProp("disabled");
		});
		modal.find("#load").click(function() {
			if (!me.id) {
				return void 0;
			}
			var text = $(this).text();
			var btn = $(this).button("loading");
			$.get(url("laboratory/medcard/load"), {
				number: me.id
			}, function(json) {
				if (!json["status"]) {
					return Core.createMessage({
						message: json["message"]
					});
				} else if (json["message"]) {
					Core.createMessage({
						type: "success",
						sign: "ok",
						message: json["message"]
					});
				}
				Laboratory_MedcardEditableViewer_Modal.load(json["model"]);
				modal.modal("hide");
			}, "json")
				.always(function() {
					btn.button("reset").text(text);
				}).fail(function() {
					Core.createMessage({
						message: "Произошла ошибка при отправке запроса. Обратитесь к администратору"
					});
				})
		});
	},
	id: null
};

var Laboratory_DirectionTable_Widget = {
	ready: function() {
		var me = this;
		$(document).on("click", ".direction-repeat-icon", function() {
			me.repeat($(this).parents("tr:eq(0)").attr("data-id"));
		}).on("click", ".direction-restore-icon", function() {
			me.cancel($(this).parents("tr:eq(0)").attr("data-id"));
		}).on("click", ".direction-show-icon", function() {
			me.show($(this).parents("tr:eq(0)").attr("data-id"), this);
		}).on("click", ".direction-send-icon", function() {
			Laboratory_AnalyzerQueue_Widget && Laboratory_AnalyzerQueue_Widget.send(
				$(this).parents("tr:eq(0)").attr("data-id"), $(this)
			);
		}).on("click", ".direction-remove-icon", function() {
			Laboratory_AnalyzerQueue_Widget && Laboratory_AnalyzerQueue_Widget.remove(
				$(this).parents("tr:eq(0)").attr("data-id"), $(this)
			);
		}).on("click", ".direction-result-icon", function() {
			Laboratory_AnalysisResult_Widget && Laboratory_AnalysisResult_Widget.open(
				$(this).parents("tr:eq(0)").attr("data-id"), $(this)
			);
		})
		$(document).on("dblclick", "table > tbody > tr[data-id]", function() {
			if ($(this).parents("table:eq(0)").find(".direction-show-icon").length > 0) {
				me.show($(this).attr("data-id"));
			}
		});
		$(".panel-date-button").each(function(i, p) {
			var dates = $(p).parents(".panel:eq(0)").find(".table:eq(0)").attr("data-dates");
			if (dates) {
				dates = $.parseJSON(dates);
			} else {
				dates = [];
			}
			me.createDatePicker(p, dates);
		});
		$(".panel-today-button").click(function() {
			me.updateByDate($(this).parents(".panel:eq(0)").find(".table:eq(0)"), $(this).attr("data-date"));
		});
		$("#direction-register-modal, #medcard-editable-viewer-modal").on("show.bs.modal", function() {
			Core.Common.cleanup(this);
		});
		$("#treatment-direction-grid-wrapper > .panel").on("panel.updated", function() {
			setTimeout(function() {
				me.refreshDatePicker()
			}, 250);
		});
	},
	refreshDatePicker: function(dates) {
		var me = this;
		$(".panel-date-button").each(function(i, p) {
			dates = dates || $(p).parents(".panel:eq(0)")
				.find(".table:eq(0)")
				.attr("data-dates");
			try {
				if (typeof dates == "string") {
					dates = $.parseJSON(dates);
				}
			} catch (ignored) {
			}
			me.createDatePicker(p, dates || []);
		});
	},
	updateByDate: function(table, date, success) {
		this._lastDate = date;
		table.table("update", { date: date }, success);
	},
	createDatePicker: function(element, dates) {
		var me = this;
		if ($(element).data("datepicker")) {
			$(element).datepicker("remove");
		}
		var handler = function () {
			var t = $.datepicker.formatDate("yy-mm-dd", $(element).datepicker("getDate"));
			if (t.length > 0) {
				$(".direction-date").text(t);
			}
		};
		$(element).datepicker({
			language: "ru-RU",
			orientation: "top",
			todayBtn: "linked",
			container: $(element).parents(".panel:eq(0)"),
			beforeShowDay: function(date) {
				if (me._lastDate == date) {
					return "active";
				} else if ($.inArray($.datepicker.formatDate("yy-mm-dd", date), dates) != -1) {
					return "treatment-marked-day";
				} else {
					return void 0;
				}
			}
		}).unbind("changeDate").on("changeDate", function () {
			var $this = $(this);
			me.updateByDate($(this).parents(".panel:eq(0)").find(".table"), $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")),
				function () {
					$(element).parents(".panel:eq(0)").trigger("panel.updated");
					$this.datepicker("hide");
				});
		}).parents(".panel:eq(0)").unbind("panel.updated", handler)
			.bind("panel.updated", handler);
	},
	update: function(success) {
		var panel = $(".treatment-table-wrapper .panel");
		if (!panel.length) {
			panel = $(".laboratory-table-wrapper .panel");
		}
		panel.panel("update", success);
	},
	show: function(id, from) {
		var panel = from ? $(from).parents(".panel:eq(0)") :
			$(".table-wrapper .panel:eq(0)");
		if (panel.length > 0) {
			panel.panel("before");
		} else {
			panel = $(from).parents('table').loading("render");
		}
		Core.loadWidget("AboutDirection", {
			direction: id
		}, function (component) {
			$("#treatment-about-direction-modal").modal().find(".modal-body")
				.empty().append(component);
		}).always(function() {
			if (panel.length > 0) {
				if (panel.is("table")) {
					panel.loading("reset");
				} else {
					panel.panel("after");
				}
			}
		}).fail(function() {
			Core.createMessage({
				message: "Невозможно открыть направление"
			});
		});
	},
	repeat: function(id) {
		var me = this;
		$.post(url("laboratory/direction/repeat"), {
			id: id
		}, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Core.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			$("#treatment-repeat-counts").text(json["repeats"]);
			me.refreshDatePicker(json["dates"]);
			me.update();
		}, "json");
	},
	cancel: function(id) {
		var me = this;
		$.post(url("laboratory/direction/restore"), {
			id: id
		}, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Core.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			$("#treatment-repeat-counts").text(json["repeats"]);
			me.refreshDatePicker(json["dates"]);
			me.update();
		}, "json");
	},
	_lastDate: null
};

var Laboratory_Medcard_Table = {
	ready: function() {
		$(document).on("click", ".direction-register-icon", function() {
			$("#register-direction-modal").cleanup().modal().find("[name='LDirectionFormEx[medcard_id]']").val(
				$(this).parents("tr:eq(0)").attr("data-id")
			);
		}).on("click", ".medcard-show-icon", function() {
			var loading = $(this).parents("table");
			if (loading.length > 0) {
				loading.loading("render")
			}
			Laboratory_AboutMedcard_Widget.load($(this).parents("tr:eq(0)").attr("data-id")).always(function() {
				if (loading.length > 0) {
					loading.loading("reset");
				}
			});
		});
	}
};

var Laboratory_AboutMedcard_Widget = {
	ready: function() {
		$(document).on("click", ".direction-creator-cancel", function() {
			$(this).parents(".direction-history-wrapper").find(".nav > li:first > a").tab("show");
			$(this).parents("form:eq(0)").cleanup();
		}).on("click", ".direction-creator-register", function() {
			var f = $(this).parents(".direction-creator-wrapper").children("form");
			f.loading("render").form("send", function(status) {
				if (status) { $(this).panel("update"); }
			}).always(function() {
				f.loading("destroy");
			});
		});
	},
	load: function(id) {
		return Core.loadWidget("AboutMedcard", {
			medcard: id
		}, function(component) {
			$("#show-medcard-modal").modal().find(".modal-body").empty().append(component);
		});
	}
};

var Laboratory_AboutDirection_Widget = {
	ready: function() {
		$("#treatment-about-direction-modal").on("click", "#open-medcard-button", function() {
			Laboratory_AboutMedcard_Widget.load($("#treatment-about-direction-medcard-id").val());
		}).on("click", "#print-barcode-button", function() {
			Laboratory_Printer.print('.barcode-wrapper')
		}).on("click", "#send-to-laboratory-button", function() {
			var me = $(this);
			me.parents(".panel").loading("render");
			me.parents(".about-direction").find(".direction-info-wrapper").form({
				success: function(response) {
					Laboratory_DirectionTable_Widget.refreshDatePicker(response["dates"]);
					$("#treatment-repeat-counts").text(response["repeats"]);
					Laboratory_DirectionTable_Widget.update();
					$("#treatment-about-direction-modal").modal("hide");
				}
			}).form("send").always(function() {
				me.parents(".panel").loading("reset");
			});
		}).on("shown.bs.modal", function() {
			/* $(this).find("#treatment-direction-history-panel").panel("collapse"); */
		}).on("change", "[name='LAboutDirectionForm[sample_type_id]']", function() {
			/* if ($(this).val() != -1) {
				$("#send-to-laboratory-button").prop("disabled", false);
			} else {
				$("#send-to-laboratory-button").prop("disabled", true);
			} */
		});
	},
	register: function(form) {
	}
};

var Laboratory_DirectionCreator_Modal = {
	ready: function() {
		$("#treatment-register-direction-modal-save-button").click(function() {
			var modal = $(this).parents(".modal");
			modal.find(".modal-content").loading("render");
			$(this).parents(".modal").find("form").form("send", function(status) {
				if (status) {
					$(this).parents('.modal').modal("hide");
					Laboratory_DirectionTable_Widget.update();
				}
			}).always(function() {
				modal.find(".modal-content").loading("reset");
			});
		});
		$("#register-direction-modal").on("show.bs.modal", function() {
			$(this).find("[name='LDirectionFormEx[analysis_type_id]']").trigger("change");
		});
	}
};

var Laboratory_DirectionFormEx_Form = {
	ready: function() {
		$(document).on("change", "[name='LDirectionFormEx[analysis_type_id]']", function() {
			var me = $(this), form = me.parents("form:eq(0)");
			if (!me.val() || me.val() == -1) {
				var c = form.find("[name='LDirectionFormEx[analysis_parameters]']");
				if (!c.length) {
					c = form.find(".analysis-type-params-wrapper");
				}
				c.parents(".form-group:eq(0)").addClass("hidden");
				c.children().remove();
				return void 0;
			}
			me.loading({ width: 100, height: 15 }).loading("render");
			Core.sendQuery("laboratory/direction/params", {
				id: me.val()
			}, function(response) {
				var c = form.find("[name='LDirectionFormEx[analysis_parameters]']");
				if (!c.length) {
					c = form.find(".analysis-type-params-wrapper");
				}
				c.parents(".form-group:eq(0)").removeClass("hidden");
				c.replaceWith(response["component"]);
			}).always(function() {
				me.loading("reset");
			});
		});
		$(document).on("change","[name='LDirectionFormEx[pregnant]']", function() {
			var fields = [
				"[name='LDirectionFormEx[gestational_age]']",
				"[name='LDirectionFormEx[menstruation_cycle]']"
			];
			if ($(this).val() == 0) {
				$(fields.join(",")).prop("disabled", true).each(function(i, s) {
					$(s).val($(s).children("option:eq(0)").attr("value"));
				});
			} else {
				$(fields.join(",")).prop("disabled", false);
			}
		});
	}
};

var Laboratory_DirectionSearchForm_Popover = {
	ready: function() {
		$("body").on("click", ".direction-search-button", function() {
			$("#direction-search-form").form({
				success: function(response) {
					$(".panel[data-widget='"+ this.find("#widget").val() +"']:eq(0)").panel("replace", response);
				}
			}).form("send");
			$(".panel-search-button").popover("hide");
		});
		$("button[data-tab]").click(function() {
			$(".panel-search-button").popover("hide");
		});
	}
};

var Laboratory_Printer = {
	print: function(selector) {
		this.popup($(selector).clone().html());
	},
	popup: function(data) {
		var win = window.open('', 'my div', 'width=800,height=600');
		win.document.write('<html><head><title></title>');
		win.document.write('</head><body >');
		win.document.write(data);
		win.document.write('</body></html>');
		win.document.close();
		win.focus();
		win.print();
		win.close();
	}
};

const BARCODE_RESET_INTERVAL = 200;
const BARCODE_SEQUENCE_LENGTH = 8;

var Laboratory_BarcodeReader = {
	ready: function() {
		var me = this;
		$(document).on("keydown", function(e) {
			if (!(e.keyCode >= 48 && e.keyCode <= 57) &&
				!(e.keyCode >= 97 && e.keyCode <= 105)
			) {
				return void 0;
			}
			me.input(e.keyCode);
		});
	},
	input: function(code) {
		var me = this;
		if (me.timer == -1) {
			me.timer = setTimeout(function() {
				me.reset();
			}, BARCODE_RESET_INTERVAL);
			me.time = new Date().getMilliseconds();
		}
		if (code >= 48 && code <= 57) {
			me.sequence.push(code - 48);
		} else {
			me.sequence.push(code - 97);
		}
		if (me.sequence.length == BARCODE_SEQUENCE_LENGTH) {
			this.finalize(parseInt(this.sequence.join("")));
		}
	},
	finalize: function(sequence) {
		this.last = sequence;
		console.log("captured barcode sequence: " + this.last );
		console.log("elapsed time: " + (new Date().getMilliseconds() - this.time));
		this.reset();
		$(document).trigger("barcode.captured", {
			barcode: this.last
		});
	},
	reset: function() {
		console.log("timer is reset");
		if (this.timer >= 0) {
			clearInterval(this.timer);
		}
		this.timer = -1;
		this.sequence = [];
	},
	timer: -1,
	sequence: [],
	last: -1
};

var Laboratory_TabMenu_Widget = {
	ready: function() {
		var menu = $("ul.nav[id*=tabmenu]");
		menu.find("a[data-tab]:not(:first)").each(function(i, a) {
			$("#" + $(a).attr("data-tab")).hide();
		});
		menu.find("li").click(function() {
			menu.find("a[data-tab]").each(function(i, a) {
				$("#" + $(a).attr("data-tab")).hide();
			});
			$("#" + $(this).children("a").attr("data-tab")).show();
			menu.find("li").removeClass("active");
			$(this).addClass("active");
		});
	}
};

$(document).ready(function() {

	Laboratory_Treatment_Header.ready();
	Laboratory_MedcardEditableViewer_Modal.ready();
	Laboratory_MedcardSearch_Widget.ready();
	Laboratory_MedcardSearch_Modal.ready();
	Laboratory_Medcard_Table.ready();
	Laboratory_DirectionTable_Widget.ready();
	Laboratory_AboutMedcard_Widget.ready();
	Laboratory_AboutDirection_Widget.ready();
	Laboratory_DirectionCreator_Modal.ready();
	Laboratory_DirectionFormEx_Form.ready();
	Laboratory_DirectionSearchForm_Popover.ready();
	Laboratory_BarcodeReader.ready();
	Laboratory_TabMenu_Widget.ready();

	// fix for modal window backdrop
	$(document).on("show.bs.modal", ".modal", function(e) {
		if (!$(e.target).hasClass("modal")) {
			return void 0;
		}
		var depth = 1140 + (10 * $('.modal:visible').length);
		$(this).css('z-index', depth);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', depth - 1).addClass('modal-stack');
		}, 0);
	}).on("show.bs.modal", ".modal", function() {
		$(this).animate({ scrollTop: 0 }, 'slow');
	});

	$('[data-toggle="popover"]').popover()
});
