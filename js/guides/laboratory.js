var GuideGridView = {
	ready: function() {
		var grid = $(".grid-view");
		this.actions = {
			create: grid.data("create-action"),
			update: grid.data("update-action"),
			load: grid.data("load-action"),
			delete: grid.data("delete-action")
		};
		$("#register-guide-modal").on("show.bs.modal", function() {
			Laboratory.resetFormErrors($(this));
			Laboratory.getCommon().cleanup($(this));
		});
		var me = this;
		$("#register-guide-modal #register-button").click(function() {
			var str = "";
			$(this).parents(".modal").find("form").each(function(i, f) {
				str += $(f).serialize() + "&";
			});
			me.create(str.replace(/&$/, ""));
		});
		$("#update-guide-modal #update-button").click(function() {
			var str = "";
			$(this).parents(".modal").find("form").each(function(i, f) {
				str += $(f).serialize() + "&";
			});
			me.update(str.replace(/&$/, ""));
		});
		$(".grid-view .update-button").click(function() {
			me.load($(this).parents("tr[data-id]").data("id"));
		});
		$(".grid-view .delete-button").click(function() {
			me.drop($(this).parents("tr[data-id]").data("id"));
		});
		$("[id='clef-save-button']").click(function() {
			var url = $(this).data("url");
			if (!url) {
				throw new Error("Lost url for clef save button");
			}
			var str = "";
			$(this).parents(".modal").find("form").each(function(i, f) {
				str += $(f).serialize() + "&";
			});
			var me = this;
			$.post(url, str.replace(/&$/, ""), function(json) {
				if (!json["status"]) {
					Laboratory.createMessage({
						message: json["message"]
					});
				} else if (json["message"]) {
					Laboratory.createMessage({
						message: json["message"],
						sign: "ok",
						type: "success"
					});
				}
				$(me).parents(".modal").modal("hide");
			}, "json");
		});
	},
	create: function(model) {
		var form = $("#register-guide-modal form");
		$.post(this.actions.create, model, function(json) {
			if (!json["status"]) {
				return Laboratory.postFormErrors(form, json)
			} else if (json["message"]) {
				Laboratory.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			form.parents(".modal").on("hidden.bs.modal", function() {
				window.location.reload(false);
			}).modal("hide");
		}, "json");
	},
	update: function(model) {
		var form = $("#update-guide-modal form");
		$.post(this.actions.update, model, function(json) {
			if (!json["status"]) {
				return Laboratory.postFormErrors(form, json)
			} else if (json["message"]) {
				Laboratory.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			form.parents(".modal").on("hidden.bs.modal", function() {
				window.location.reload(false);
			}).modal("hide");
		}, "json");
	},
	load: function(id) {
		var m = $("#update-guide-modal");
		$.get(this.actions.load, { id: id }, function(json) {
			if (!json["status"]) {
				return Laboratory.postFormErrors(m, json)
			} else if (json["message"]) {
				Laboratory.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			var model = json["model"] || {};
			for (var i in model) {
				m.find("[id='" + i + "']").val(model[i]);
			}
			m.modal("show")
		}, "json");
	},
	drop: function(id) {
		$.post(this.actions.delete, { id: id }, function(json) {
			if (!json["status"]) {
				Laboratory.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Laboratory.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			window.location.reload(false);
		}, "json");
	},
	actions: {}
};

$(document).ready(function() {
	GuideGridView.ready();
});