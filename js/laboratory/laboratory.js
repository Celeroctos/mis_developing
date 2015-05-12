
var AnalyzerQueueManager = function(container) {
	this._container = container;
	this._queue = [];
};

AnalyzerQueueManager.prototype.push = function(id) {
	this._queue.push(id);
};

AnalyzerQueueManager.prototype.clear = function() {
	this._queue = [];
};

var Laboratory_AnalyzerTask_Menu = {
	ready: function() {
		var me = this;
		$("#analyzer-task-viewer").on("click", ".analyzer-task-menu-item", function() {
			if (me.active != null) {
				me.active.removeClass("active");
			}
			me.current = $(this).children("a").attr("data-id");
			me.active = $(this).addClass("active");
			$(".analyzer-task-tab").hide();
			$("#" + $(this).children("a").attr("data-tab")).show();
		});
	},
	current: -1,
	active: null
};

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
				me.drop(this, item.draggable);
			}
		});
		$(".analyzer-task-clear").click(function() {
			var container = $(this).parents(".analyzer-task-tab:eq(0)")
				.find(".analyzer-queue-container");
			container.children("tr[data-id]").each(function(i, tr) {
				me.unlock($(tr).attr("data-id"));
			});
			container.empty().append(
				"<h4 class=\"text-center\">Направления отсутствуют</h4>"
			);
		});
	},
	drop: function(body, item) {
		if (!this.current()) {
			Core.createMessage({
				message: "Анализатор не выбран"
			});
			return false;
		} else if (!item.parent().is("tbody")) {
			return false;
		}
		var tr = item.clone(false);
		this.lock(tr.attr("data-id"));
		tr.find("td:last").remove();
		var container = $(body).find(".analyzer-queue-container:visible").sortable({
			/* sortable config */
		}).append(tr);
		container.children("*:not(tr)").remove();
	},
	createDraggable: function() {
		try {
			$("#laboratory-direction-table tr").draggable("destroy");
		} catch (ignored) {
		}
		$("#laboratory-direction-table tr").draggable({
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
	current: function() {
		if (Laboratory_AnalyzerTask_Menu.current > 0) {
			return this.getQueue(Laboratory_AnalyzerTask_Menu.current);
		} else {
			return null;
		}
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
		$("#laboratory-direction-table > tbody > tr[data-id='"+ id +"']")
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
		var queue = this.current();
		if (queue == null) {
			return Core.createMessage({
				message: "Анализатор не выбран"
			});
		}
		this.lock(id);
		queue.push(id);
	},
	queue: {}
};

$(document).ready(function() {

	Laboratory_AnalyzerTask_Menu.ready();
	Laboratory_AnalyzerQueue_Widget.ready();

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
	});
});
