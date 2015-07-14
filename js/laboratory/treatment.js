
var TreatmentViewHeader = {
	construct: function() {
		$("button.treatment-header-rounded:not([data-toggle])").click(function() {
			TreatmentViewHeader.active && TreatmentViewHeader.active.removeClass("active");
			TreatmentViewHeader.active = $(this).addClass("active");
		});
		this.active = $(".treatment-header").find("button.active");
		if (!this.active.length) {
			this.active = null;
		}
		$("button.treatment-header-rounded[data-tab]").click(function() {
			$("button.treatment-header-rounded[data-tab]").each(function(i, t) {
				$($(t).data("tab")).addClass("no-display");
			});
			$($(this).data("tab")).removeClass("no-display")
				.trigger("change");
		});
		$("#direction-register-modal, #medcard-editable-viewer-modal").on("show.bs.modal", function() {
			Common.cleanup(this);
		});
	},
	active: null
};

var MedcardEditableViewerModal = {
	check: function() {
		if (MedcardEditableViewerModal.copied !== false) {
			$("#medcard-editable-viewer-modal #insert-button").removeProp("disabled");
		} else {
			$("#medcard-editable-viewer-modal #insert-button").prop("disabled", true);
		}
	},
	construct: function() {
		var me = this;
		var modal = $("#medcard-editable-viewer-modal");
		modal.on("show.bs.modal", function() {
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
			MedcardEditableViewerModal.copied = json;
			me.check();
			Laboratory.createMessage({
				message: "Данные скопированы",
				sign: "ok",
				type: "success"
			});
		});
		modal.find("#insert-button").click(function() {
			if (MedcardEditableViewerModal.copied === false) {
				return void 0;
			}
			var json = MedcardEditableViewerModal.copied;
			for (var i in json) {
				var f = $("[id='" + i + "']");
				for (var j in json[i]) {
					f.find($("[id='" + j + "']")).val(json[i][j]);
				}
			}
			Laboratory.createMessage({
				message: "Данные вставлены",
				sign: "ok",
				type: "success"
			});
		});
		modal.find("#clear-button").click(function() {
			Common.cleanup(modal);
			me.check();
			Laboratory.createMessage({
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
			Laboratory.resetFormErrors(modal);
			$.post(url("laboratory/medcard/register"), {
				model: forms
			}, function(json) {
				if (json["errors"]) {
					Laboratory.postFormErrors(modal, json);
				} else if (!Message.display(json)) {
					return void 0;
				}
				console.log(json);
			}, "json");
		});
	},
	load: function(model) {
		var modal = $("#medcard-editable-viewer-modal");
		var put = function(from, key, value) {
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
		for (var i in model) {
			var m = model[i], f, base;
			if ((f = modal.find("#" + i)).length > 0 && f.data("form")) {
				base = $("#" + f.data("form"));
			} else {
				base = modal
			}
			for (var j in m) {
				put(base, j, m[j]);
			}
		}
		modal.find("input[data-laboratory='address']").address("calculate");
		modal.find("span[id='card_number']").text(model["medcard"]["card_number"]);
	},
	copied: false
};

$(document).ready(function() {
	MedcardEditableViewerModal.construct();
	TreatmentViewHeader.construct();
});