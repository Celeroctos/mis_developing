
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
			Core.resetFormErrors($(this));
			$(this).cleanup();
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
			$(this).parents(".modal").find("select[multiple]").each(function(i, s) {
				$(s).data("clef-keys", $(s).val());
			});
			var me = this;
			$.post(url, str.replace(/&$/, ""), function(json) {
				if (!json["status"]) {
					Core.createMessage({
						message: json["message"]
					});
				} else if (json["message"]) {
					Core.createMessage({
						message: json["message"],
						sign: "ok",
						type: "success"
					});
				}
				$(me).parents(".modal").modal("hide");
			}, "json");
		});
		$("[id^='clef-'][id$='-modal']").each(function(i, m) {
			$(m).find("select[multiple]").each(function(i, s) {
				$(s).data("clef-keys", $(s).val());
			});
		}).on("show.bs.modal", function() {
			$(this).find("select[multiple]").each(function(i, s) {
				console.log($(s).data("clef-keys"));
				$(s).multiple("clear").multiple("choose", $(s).data("clef-keys"));
			});
		});
	},
	create: function(model) {
		var form = $("#register-guide-modal form");
		console.log(this.actions);
		$.post(this.actions.create, model, function(json) {
			if (!json["status"]) {
				return Core.postFormErrors(form, json)
			} else if (json["message"]) {
				Core.createMessage({
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
				return Core.postFormErrors(form, json)
			} else if (json["message"]) {
				Core.createMessage({
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
				return Core.postFormErrors(m, json)
			} else if (json["message"]) {
				Core.createMessage({
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
				Core.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Core.createMessage({
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
	Core.prepareMultiple();
	GuideGridView.ready();
});