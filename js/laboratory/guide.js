
var GuideColumnEditor = {
	construct: function() {
		$(document).on("click", ".guide-remove-column", function() {
			var me = $(this).parent("a").parent("div");
			me.slideUp("normal", function() {
				me.remove();
			});
		});
		$(document).on("click", "#guide-append-column", function() {
			if ($(this).children(".glyphicon-plus").length) {
				GuideColumnEditor.add($(this));
			}
		});
	},
	before: function(button) {
		button.find("span").replaceWith($("<img>", {
			width: 15,
			src: url("/images/ajax-loader.gif")
		}));
	},
	after: function(button) {
		button.find("img").replaceWith($("<span>", {
			class: "glyphicon glyphicon-plus"
		}));
	},
	render: function(component) {
		$(component.find("#guide_id").parents(".form-group")[0])
			.addClass("hidden");
		var p;
		$(".column-container").append(
			p = $("<div>", { class: "guide-column-handle" }).append(
				$("<a>", { href: "javascript:void(0)" }).append(
					$("<span>", {
						class: "glyphicon glyphicon-remove guide-remove-column",
						style: "color: #af1010"
					})
				)
			).append(component).append("<hr>")
		);
		p.hide().slideDown("normal");
	},
	add: function(button) {
		GuideColumnEditor.before(button);
		$.get(url("/laboratory/guide/getWidget"), {
			class: "Form",
			model: "LGuideColumnForm",
			form: { guide_id: GuideColumnEditor.id }
		}, function(json) {
			GuideColumnEditor.after(button);
			if (!json.status) {
				return Core.createMessage({
					message: json["message"]
				});
			}
			GuideColumnEditor.render($(json["component"]));
		}, "json");
	},
	id: -1
};

var GuideTableViewer = {
	defaults: function() {
		var panel = $("#guide-edit-panel");
		panel.find(".panel-content").slideUp("normal", function() {
			$(this).empty().append(
				$("<h4>", {
					text: "Не выбран справочник",
					style: "text-align: center"
				})
			);
			$(this).hide().slideDown("normal");
		});
		panel.find("#guide-panel-button-group").fadeOut("fast");
	},
	sortable: function() {
		$(".column-container").sortable().disableSelection();
	},
	calculate: function() {
		var index = 1;
		$(".column-container").find("input[type='number']#position").each(function(i, it) {
			$(it).val(index++);
		});
	},
	load: function(id) {
		if (!id || id < 0) {
			return this.defaults();
		}
		GuideColumnEditor.id = id;
		$.get(url("/laboratory/guide/getWidget"), {
			class: "GuideColumnEditor",
			form: { id: id },
			model: "LGuideForm"
		}, function(json) {
			if (!Message.display(json)) {
				return void 0;
			}
			var component = $(json["component"]);
			$(component.find("#guide_id").parents(".form-group")[0]).addClass("hidden");
			$("#guide-edit-panel .panel-content").slideUp("normal", function() {
				$(this).empty().append(component);
				component.find("select#type").each(function(i, d) {
					DropDown.change.call(d, false);
				});
				GuideTableViewer.calculate();
				$(this).hide().slideDown("normal", function() {
					$("#guide-panel-button-group").removeClass("hidden").hide().fadeIn("fast", function() {
						GuideTableViewer.sortable();
					});
				});
			});
		}, "json");
		$("#guide-panel-button-group").addClass("hidden");
		$("#guide-edit-panel .panel-content").empty().append($("<div>", {
				style: "width: 100%; text-align: center"
			}).append($("<img>", { src: url("/images/ajax-loader.gif") }))
		);
	},
	remove: function(id) {
		if (id == GuideColumnEditor.id) {
			this.defaults();
		}
		$.post(url("/laboratory/guide/delete"), {
			id: id
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
			GuideTableViewer.update();
		}, "json");
	},
	save: function() {
		this.calculate();
		var panel = $("#guide-edit-panel");
		panel.find(".form-group").removeClass("has-error");
		var serialized = [];
		panel.find("form").each(function(i, form) {
			serialized.push($(form).serialize());
		});
		$.post(url("/laboratory/guide/update"), {
			model: serialized
		}, function(json) {
			if (!json["status"]) {
				return Core.postFormErrors(panel, json);
			} else {
				Message.display(json);
			}
			GuideTableViewer.update();
			GuideTableViewer.defaults();
		}, "json");
	},
	construct: function() {
		$("#guide-register-form").on("success", function() {
			GuideTableViewer.update();
		});
		$(document).on("click", "#guide-table tbody tr td:not(:last-child)", function() {
			GuideTableViewer.load($(this).parent("tr").data("id"));
		});
		$(document).on("click", ".table-edit", function() {
			var id = $($(this).parents("tr")[0]).data("id");
			$("#guide-edit-values-modal").modal();
			$("#guide-edit-values-modal .modal-body .row").empty().append(
				$("<div>", { style: "width: 100%; text-align: center" }).append($("<img>", {
					src: url("/images/ajax-loader.gif")
				}))
			);
			$.get(url("/laboratory/guide/getWidget"), {
				class: "GuideValueEditor",
				guide_id: id
			}, function(json) {
				if (!Message.display(json)) {
					return void 0;
				}
				$("#guide-edit-values-modal .modal-body .row").empty().append(
					$(json["component"])
				);
			}, "json");
			GuideValueEditor.guideId = id;
		});
		$(document).on("click", ".table-remove", function() {
			GuideTableViewer.remove($($(this).parents("tr")[0]).data("id"));
		});
		$("#guide-edit-panel #panel-update").click(function() {
			GuideTableViewer.save();
		});
		$("#guide-edit-panel #panel-cancel").click(function() {
			GuideTableViewer.defaults();
		});
	},
	refresh: function(component) {
		$("#guide-table").fadeOut("fast", function() {
			var t;
			$("#guide-table").replaceWith(
				t = component
			);
			t.hide().fadeIn("fast");
		});
	},
	update: function() {
		$.get(url("/laboratory/guide/getWidget"), {
			class: "GuideTable"
		}, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			}
			GuideTableViewer.refresh($(json["component"]));
		}, "json");
	},
	success: false
};

var GuideValueEditor = {
	reset: function(tr) {
		tr.find("select").each(function(i, f) {
			if ($(f).hasClass("multiple-value")) {
				$(f).val("");
			} else {
				$(f).val(-1);
				if (!$(f).val()) {
					$(f).val(0);
				}
			}
		});
		tr.find("input, textarea").val("");
		tr.removeAttr("data-id");
		tr.find("td").each(function(i, f) {
			$(f).removeAttr("data-id", null);
		});
	},
	construct: function() {
		$(document).on("click", "#guide-edit-add-fields", function() {
			var item = $(this).parents(".guide-values-container").find("tr:last");
			var tr = item.clone(true);
			GuideValueEditor.reset(tr);
			item.parent().append(tr);
			tr.hide().slideDown("slow");
		});
		$(document).on("click", ".guide-values-container .remove", function() {
			var tr = $(this).parent("td").parent("tr");
			if (tr.parent("tbody").children().length == 1) {
				tr.remove();
				$("#guide-edit-values-modal #register").trigger("click");
				$("#guide-edit-values-modal").modal("hide");
			} else {
				tr.remove();
			}
		});
		$("#guide-edit-values-modal").on("click", "#register", function() {
			var data = [];
			$(".guide-values-container tbody tr").each(function(i, tr) {
				var row = [];
				$(tr).find("input, select, textarea").each(function(i, item) {
					row.push({
						position: $(this).parents("td").data("position"),
						id: $(this).parents("td").data("id"),
						value: $(item).val()
					});
				});
				data.push({
					id: $(tr).data("id"),
					model: row
				});
			});
			$.post(url("/laboratory/guide/apply"), {
				guide_id: GuideValueEditor.guideId,
				data: data
			}, function(json) {
				if (!Message.display(json)) {
					return void 0;
				}
				$("#guide-edit-values-modal").modal("hide");
			}, "json");
		});
	},
	guideId: null
};