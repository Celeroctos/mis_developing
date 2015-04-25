
var TreatmentHeader = {
	ready: function() {
		$("button.treatment-header-rounded:not([data-toggle])").click(function() {
			TreatmentHeader.active && TreatmentHeader.active.removeClass("treatment-header-wrapper-active");
			TreatmentHeader.active = $(this).addClass("treatment-header-wrapper-active");
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
	},
	active: null
};

var TreatmentMedcardEditableViewerModal = {
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
			var forms = [];
			modal.find("form").each(function(i, f) {
				forms.push($(f).serialize());
			});
			Core.resetFormErrors(modal);
			$.post(url("laboratory/direction/register"), forms.join("&"), function(json) {
				if (json["errors"]) {
					Core.postFormErrors(modal, json);
				} else if (!Message.display(json)) {
					return void 0;
				}
				if (json["status"]) {
					$("#treatment-laboratory-medcard-table-panel .table:eq(0)").table("update");
					modal.modal("hide");
					TreatmentDirectionTable.show(json["direction"]);
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

var TreatmentDirectionTable = {
	ready: function() {
		var me = this;
		$(".treatment-table-wrapper").on("click", ".direction-repeat-icon", function() {
			me.repeat($(this).parents("tr:eq(0)").attr("data-id"));
		}).on("click", ".direction-restore-icon", function() {
			me.cancel($(this).parents("tr:eq(0)").attr("data-id"));
		}).on("click", ".direction-show-icon", function() {
			me.show($(this).parents("tr:eq(0)").attr("data-id"), this);
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
	},
	updateByDate: function(table, date, success) {
		var panel = $(table).parents(".panel:eq(0)").panel("before");
		$.get(url("laboratory/direction/getTable"), {
			class: table.attr("data-class"),
			attributes: table.attr("data-attributes"),
			date: date
		}, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			}
			panel.panel("after").panel("replace", json["component"])
				.find(".direction-date").text(date);
			table.table("attr", "date", date);
			success && success(json);
		}, "json");
	},
	createDatePicker: function(element, dates) {
		var me = this;
		$(element).datepicker({
			language: "ru-RU",
			orientation: "top",
			todayBtn: "linked",
			beforeShowDay: function(date) {
				if ($.inArray($.datepicker.formatDate("yy-mm-dd", date), dates) != -1) {
					return "btn btn-success";
				} else {
					return void 0;
				}
			}
		}).on("changeDate", function() {
			var $this = $(this);
			me.updateByDate($(this).parents(".panel:eq(0)").find(".table"), $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")),
				function() {
					$this.datepicker("hide");
				});
		}).parents(".panel:eq(0)").on("panel.updated", function() {
			$(this).find(".direction-date").text($(this).panel("attr", "date"));
		});
	},
	update: function() {
		$(".treatment-table-wrapper .panel").panel("update");
	},
	show: function(id, from) {
		var panel = from ? $(from).parents(".panel:eq(0)") :
			$(".treatment-table-wrapper .panel:eq(0)");
		panel.panel("before");
		Core.Common.loadWidget("AboutDirection", {
			direction: id
		}, function(component) {
			$("#treatment-about-direction-modal").modal().find(".modal-body")
				.empty().append(component);
			panel.panel("after");
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
			$("#treatment-repeat-counts").text(
				json["repeats"]
			);
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
			$("#treatment-repeat-counts").text(
				json["repeats"]
			);
			me.update();
		}, "json");
	}
};

var TreatmentLaboratoryMedcardTable = {
	ready: function() {
		$(document).on("click", "#medcard-search-table-wrapper .direction-register-icon", function() {
			$("#register-direction-modal").cleanup().modal().find("[name='LDirectionForm[medcard_id]']").val(
				$(this).parents("tr:eq(0)").attr("data-id")
			);
		}).on("click", "#medcard-search-table-wrapper .medcard-show-icon", function() {
			var loading = $("#treatment-laboratory-medcard-table-panel")
				.find(".table").loading("render");
			TreatmentAboutMedcard.load($(this).parents("tr:eq(0)").attr("data-id")).always(function() {
				loading.loading("reset");
			});
		});
	}
};

var TreatmentAboutMedcard = {
	ready: function() {
		$(document).on("click", ".direction-creator-cancel", function() {
			$(this).parents(".direction-history-wrapper").find(".nav > li:first > a").tab("show");
			$(this).parents("form:eq(0)").cleanup();
		}).on("click", "#treatment-direction-history-panel .direction-creator-register", function() {
			var f = $(this).parents("form:eq(0)").form("send", function(status) {
				if (status) {
					$(this).panel("update");
				} else {
					f.loading("destroy");
				}
			}).loading("render");
		});
	},
	load: function(id) {
		return Core.Common.loadWidget("MedcardViewer", {
			medcard: id
		}, function(component) {
			$("#show-medcard-modal").modal().find(".modal-body").empty().append(component);
		});
	}
};

var TreatmentAboutDirection = {
	ready: function() {
		$("#treatment-about-direction-modal").on("click", "#open-medcard-button", function() {
			TreatmentAboutMedcard.load($("#treatment-about-direction-medcard-id").val());
		});
	}
};

$(document).ready(function() {
	TreatmentMedcardEditableViewerModal.ready();
	TreatmentHeader.ready();
	TreatmentLaboratoryMedcardTable.ready();
	TreatmentDirectionTable.ready();
	TreatmentAboutMedcard.ready();
	TreatmentAboutDirection.ready();
});