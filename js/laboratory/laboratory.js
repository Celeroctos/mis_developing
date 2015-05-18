
var Laboratory_AnalyzerQueue_Widget = {
	ready: function() {
		var me = this;
		$(".laboratory-table-wrapper .panel:eq(0)").on("panel.updated", function() {
			me.createDraggable();
		});
		$(".laboratory-table-wrapper .table").on("table.updated", function() {
			me.createDraggable();
		});
		this.createDraggable();
		$("#analyzer-task-viewer .panel-body").droppable({
			drop: function(e, item) {
				me.drop(item.draggable);
			}
		});
		$(".analyzer-queue-clear-button").click(function() {
			me.clear();
		});
		var panels = {};
		$(".panel").on("panel.update", function() {
			var list = [];
			$(this).find("tr[data-id]").each(function(i, tr) {
				if ($(tr).data("core-loading")) {
					list.push($(tr).attr("data-id"));
				}
			});
			panels[$(this).attr("id")] = list;
		});
		$(".panel").on("panel.updated", function() {
			for (var i in panels) {
				for (var j in panels[i]) {
					$("#" + i).find("tr[data-id='"+ panels[i][j] +"']");
				}
			}
		});
	},
	remove: function(id) {
		var container = $(".laboratory-tab-container:visible .analyzer-queue-container");
		this.unlock(id);
		container.find("tr[data-id='"+ id +"']").remove();
		if (!container.find("tr[data-id]").length) {
			$(".laboratory-tab-container:visible > div:eq(1) .panel-content").append(
				"<h3 class=\"text-center\">Пусто</h3>"
			).children("h3:not(:first)").remove();
		}
	},
	clear: function() {
		var me = this;
		$(".laboratory-tab-container:visible .analyzer-queue-container").find("tr[data-id]").each(function(i, tr) {
			me.remove($(tr).attr("data-id"));
		});
	},
	drop: function(item) {
		if (!item.parent().is("tbody")) {
			return false;
		}
		var tr = item.clone(false);
		this.lock(tr.attr("data-id"));
		tr.find("td:last").replaceWith(
			$("<td>", {
				"width": "15px"
			}).append($("<span>", {
				"class": "glyphicon glyphicon-remove panel-control-button direction-remove-icon",
				"data-original-title": "Удалить",
				"onmouseenter": "$(this).tooltip('show')",
				"data-placement": "left"
			}))
		);
		var container = $(".laboratory-tab-container:visible .analyzer-queue-container")
			.sortable({
			/* sortable config */
			});
		if (container.find("tr[data-id='"+ tr.attr("data-id") +"']").length > 0) {
			return Core.createMessage({
				message: "Направление с номером ("+ tr.attr("data-id") +") уже стоит в очереди на анализ"
			});
		} else {
			container.append(tr);
		}
		container.parent().children("h3").remove();
	},
	createDraggable: function() {
		try {
			$("#laboratory-direction-table tbody > tr").draggable("destroy");
		} catch (ignored) {
		}
		$("#laboratory-direction-table tbody > tr").draggable({
			helper: function() {
				var item = $(this).clone(false).css({
					"background-color": "whitesmoke",
					"border": "1px solid lightgray"
				});
				item.find("td:last").remove();
				item.find("td").css({
					"padding": "10px",
					"border": "1px solid lightgray"
				});
				return item;
			},
			appendTo: "body"
		});
	},
	lock: function(id) {
		$(".laboratory-tab-container:not(:first) table:not(.analyzer-queue-container) > tbody > tr[data-id='"+ id +"']")
			.loading("reset").loading({
				image: url("images/locked59.png"),
				width: 15,
				height: 15,
				depth: 1
			}).loading("render");
	},
	unlock: function(id) {
		$("#laboratory-direction-table > tbody > tr[data-id='"+ id +"']").loading("destroy");
	},
	send: function(id) {
		var tr = $(".table:not(:first):visible[id='laboratory-direction-table'] > tbody > tr[data-id='"+ id +"']");
		if (!tr.length) {
			return Core.createMessage({
				message: "Направление с номером ("+ id +") не направлялось в лабораторию"
			});
		} else if (tr.data("core-loading")) {
			return Core.createMessage({
				message: "Направление с номером ("+ id +") уже стоит в очереди на анализ"
			});
		}
		this.drop(tr);
	},
	panels: {}
};

var Laboratory_Analyzer_TabMenu = {
	ready: function() {
		var menu = $("#analyzer-tab-menu"),
			me = this;
		menu.find(".analyzer-task-menu-item:not(.disabled)").click(function() {
			menu.find(".analyzer-task-menu-item.active").removeClass("active");
			$(this).addClass("active");
			$(".laboratory-tab-container").hide();
			$("#" + $(this).children().attr("data-tab")).show();
			if ($(this).children("a").attr("data-id")) {
				window.location.hash = $(this).children("a").attr("data-id");
			} else {
				window.location.hash = "";
			}
		});
		if (/^#\d$/.test(window.location.hash || "")) {
			var a = menu.find("li > a[data-id='"+ window.location.hash.substr(1) +"']");
			a.parent("li").trigger("click");
		}
	}
};

$(document).ready(function() {

	Laboratory_AnalyzerQueue_Widget.ready();
	Laboratory_Analyzer_TabMenu.ready();

	$(document).on("barcode.captured", function(e, p) {
		var table = $("#laboratory-direction-table").table("before", 1);
		$.get(url("laboratory/direction/test"), {
			id: p.barcode, status: 2 /* LDirection::STATUS_LABORATORY */
		}, function(response) {
			if (!response["status"]) {
				Core.createMessage({
					message: response["message"]
				});
				Laboratory_DirectionTable_Widget.show(p.barcode);
				return void 0;
			} else if (response["message"]) {
				return Core.createMessage({
					message: response["message"],
					type: "success",
					sign: "ok"
				});
			}
			Laboratory_AnalyzerQueue_Widget.send(p.barcode);
		}, "json").always(function() {
			table.table("after");
		});
	});
});
