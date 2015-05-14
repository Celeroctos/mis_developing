
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
	},
	clear: function() {
		var me = this,
			container = $(".laboratory-tab-container:visible .analyzer-queue-container");
		container.find("tr[data-id]").each(function(i, tr) {
			console.log($(tr).attr("data-id"));
			me.unlock($(tr).attr("data-id"));
		});
		container.empty();
		$(".laboratory-tab-container:visible > div:eq(1) .panel-content").append(
			"<h3 class=\"text-center\">Пусто</h3>"
		).children("h3:not(:first)").remove();
	},
	drop: function(item) {
		if (!item.parent().is("tbody")) {
			return false;
		}
		var tr = item.clone(false);
		this.lock(tr.attr("data-id"));
		tr.find("td:last").remove();
		var container = $(".laboratory-tab-container:visible .analyzer-queue-container")
			.sortable({
			/* sortable config */
			}).append(tr);
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
	getQueue: function(id) {
		if (!this.queue.hasOwnProperty(id)) {
			return this.queue[id] = new AnalyzerQueueManager(
				this.getContainer(id)
			);
		} else {
			return this.queue[id];
		}
	},
	getContainer: function(id) {
		var tab = $(".analyzer-task-menu-item > a[data-id='"+ id +"'][data-tab]");
		if (!tab.length) {
			throw new Error("Unresolved tab identification number ("+ id +")");
		}
		var pane = $("#" + tab.attr("data-tab"));
		if (!pane.length) {
			throw new Error("Unresolved tab pane identification number ("+ tab.attr("data-tab") +")");
		}
		return pane.find(".analyzer-queue-container:eq(0)");
	},
	lock: function(id) {
		$(".laboratory-tab-container:not(:first) table > tbody > tr[data-id='"+ id +"']")
			.loading({
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
		var tr = $("#laboratory-direction-table > tbody tr[data-id='"+ id +"']");
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
	queue: {}
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
		});
	}
};

$(document).ready(function() {

	Laboratory_AnalyzerQueue_Widget.ready();
	Laboratory_Analyzer_TabMenu.ready();

	$(document).on("barcode.captured", function(e, p) {
		var tr = $("#laboratory-direction-table").find("tr[data-id='"+ p.barcode +"']");
		if (!tr.length) {
			setTimeout(function() {
				Laboratory_DirectionTable_Widget.show(p.barcode);
			}, 1000);
			return Core.createMessage({
				message: "Направление с номером ("+ p.barcode +") не направлялось в лабораторию",
				delay: 5000
			});
		}
		Laboratory_AnalyzerQueue_Widget.send(p.barcode);
	});
});
