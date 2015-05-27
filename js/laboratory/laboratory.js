
const WEAK_LOCK = 1;
const STRONG_LOCK = 2;

var Laboratory_AnalyzerQueue_Widget = {
	ready: function() {
		var me = this;
		/* $(".laboratory-table-wrapper .panel:eq(0)").on("panel.updated", function() {
			me.createDraggable();
		}); */
		$(document).on("table.updated", "#laboratory-direction-table", function() {
			me.createDraggable();
		});
		$("#analyzer-task-viewer .panel-body").droppable({
			drop: function(e, item) {
				me.drop(item.draggable);
			}
		});
		$(".analyzer-queue-clear-button").click(function() {
			me.clear();
		});
		$(".analyzer-queue-start-button").click(function() {
			me.start();
		});
		this.createDraggable();
	},
	remove: function(id) {
		var container = $(".analyzer-queue-container:visible");
		container.children("li[data-id='"+ id +"']").trigger("mouseleave").remove();
		if (!container.children("li").length) {
			$(".laboratory-tab-container:visible .panel-content").append(
				"<h3 class=\"text-center\">Пусто</h3>"
			).children("h3:not(:first)").remove();
		}
		this.unlock(id);
		this.calculateIndexes(container);
	},
	clear: function() {
		var me = this;
		$(".analyzer-queue-container:visible").children("li").each(function(i, li) {
			me.remove($(li).attr("data-id"));
		});
	},
	calculateIndexes: function(container) {
		container = (container || $(".analyzer-queue-container:visible"));
		var k = 1;
		container.children("li").each(function(i, li) {
			$(li).attr("data-index", k).find(".queue-index").text("№ " + (k++));
		});
	},
	renderItem: function(tr) {
		var me = this;
		var string = tr.children("td:eq(1)").text() + ", В: " +
			tr.children("td:eq(2)").text() + ", Н: "+
			tr.children("td:eq(3)").text();
		if (string.length > 45) {
			string = string.substr(0, 45) + " ...";
		}
		var container = $(".analyzer-queue-container:visible"),
			index = container.children("li").length + 1;
		return $("<a></a>", {
			"class": "col-xs-12"
		}).append($("<div></div>", {
			"class": "col-xs-2 text-left no-padding"
		}).append($("<b></b>", {
			"class": "queue-index",
			"text": "№ " + index
		})).append($("<span></span>", {
			/* "class": "glyphicon glyphicon-sort" */
		}))).append($("<div></div>", {
			"html": string,
			"class": "col-xs-9 text-left no-padding"
		})).append($("<div></div>", {
			"html": $("<span></span>", {
				"class": "glyphicon glyphicon-remove",
				"data-original-title": "Удалить",
				"onmouseenter": "$(this).tooltip('show')",
				"data-placement": "right"
			}).click(function () {
				me.remove($(this).parents("li:eq(0)").attr("data-id"));
			}),
			"class": "col-xs-1 text-right no-padding"
		}));
	},
	drop: function(tr) {
		var me = this;
		if (tr.parents(".analyzer-queue-container").length > 0) {
			return false;
		} else if ($(".analyzer-queue-container:visible").attr("data-locked")) {
			return false;
		}
		this.lock(tr.attr("data-id"));
		var container = $(".analyzer-queue-container:visible"),
			index = container.children("li").length + 1;
		var a = this.renderItem(tr);
		container.append($("<li></li>", {
			"data-id": tr.attr("data-id"),
			"data-index": index,
			"hover": function() {
				var tr =$("#laboratory-direction-table")
					.find("> tbody > tr[data-id='"+ $(this).attr("data-id") +"']");
				$(".laboratory-tr-pointer").css({
					"left": tr.position().left - 20,
					"top": tr.position().top + 205
				}).show();
				tr.addClass("success");
			},
			"mouseleave": function() {
				var tr =$("#laboratory-direction-table")
					.find("> tbody > tr[data-id='"+ $(this).attr("data-id") +"']");
				$(".laboratory-tr-pointer").hide();
				tr.removeClass("success");
			}
		}).append(a));
		container.parent().children("h3").remove();
		container.sortable({
			appendTo: container,
			stop: function() {
				me.calculateIndexes();
			}
		}).disableSelection();
	},
	createDraggable: function() {
		var me = this;
		try {
			$("#laboratory-direction-table tbody > tr").draggable("destroy");
		} catch (ignored) {
		}
		$("#laboratory-direction-table tbody > tr").draggable({
			helper: function() {
				var item = $("<ul></ul>", {
					class: "nav nav-pills nav-stacked analyzer-queue-helper"
				}).append("<li></li>").append(me.renderItem($(this)));
				item.find(".glyphicon").remove();
				return item;
			},
			appendTo: "body"
		});
	},
	lock: function(id, mode) {
		mode = mode || STRONG_LOCK;
		var tr = $("#laboratory-direction-table > tbody > tr[data-id='"+ id +"']")
			.loading("reset").loading({
				image: mode === WEAK_LOCK ? false : url("images/locked59.png"),
				width: 15,
				height: 15,
				depth: 1,
				color: mode === WEAK_LOCK ? "white" : "white",
				opacity: mode === WEAK_LOCK ? 0.10 : 0.5,
				fade: false,
				velocity: 0
			}).loading("render");
		if (mode == WEAK_LOCK || this.locked[id] == WEAK_LOCK) {
			tr.addClass("danger");
		}
		this.locked[id] = mode;
		/* localStorage.setItem("locked", JSON.stringify(this.locked)); */
	},
	unlock: function(id) {
		$("#laboratory-direction-table > tbody > tr[data-id='"+ id +"']").loading("destroy")
			.removeClass("danger");
		delete this.locked[id];
		/* localStorage.setItem("locked", JSON.stringify(this.locked)); */
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
	start: function() {
		var me = this;
		var container = $(".analyzer-queue-container:visible");
		if (!container.children("li").length) {
			return Core.createMessage({
				message: "Не выбраны направления для анализа"
			});
		}
		var panel = container.parents(".panel").loading("render");
		var time = $(".analyzer-task-menu-item.active > a").attr("data-time");
		panel.find(".panel-footer > .progress > .progress-bar").animate({
			width: "100%"
		}, time * 1000, "linear", function() {
			Core.createMessage({
				message: "Ожидаем получения данных с сервера ...",
				sign: "ok",
				type: "info"
			});
		});
		me.await(container);
	},
	await: function(container) {
		var me = this, done = false;
		container = container || $(".analyzer-queue-container:visible");
		container.attr("data-locked", "true");
		var t = function() {
			var directions = [];
			container.children("li:not(.active)").each(function(i, li) {
				directions.push($(li).attr("data-id"));
			});
			Core.sendPost("laboratory/direction/check", {
				directions: directions,
				status: 3 /* STATUS_READY */
			}, function(response) {
				var ready = response["ready"] || [];
				for (var i in ready) {
					container.children("li[data-id='"+ ready[i] +"']").addClass("active");
				}
				if (ready.length > 0) {
					$("#laboratory-direction-table").parents(".panel").panel("update");
				}
				$("#laboratory-ready-counts").text(ready["total"]);
				if (!container.children("li[data-id]:not(.active)").length) {
					Core.createMessage({
						message: "Результаты анализов получены",
						sign: "ok",
						type: "success"
					});
					container.parents(".panel").loading("reset");
					done = true;
					me.clear();
					$("#laboratory-ready-grid-wrapper").children(".panel").panel("update");
					container.parents(".panel").find(".panel-footer > .progress > .progress-bar")
						.stop().animate({ width: "0%" }, 250);
					container.removeAttr("data-locked");
				}
				if (!done) {
					setTimeout(t, 10000);
				}
			}).fail(function() {
				if (!done) {
					setTimeout(t, 10000);
				}
			});
		};
		t();
	},
	panels: {},
	locked: {}
};

var Laboratory_Analyzer_TabMenu = {
	ready: function() {
		var menu = $("#analyzer-tab-menu"),
			directions;
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
			try {
				directions = $.parseJSON($(this).children("a").attr("data-directions"));
			} catch (ignored) {
				directions = [];
			}
			var locked = $.extend(true, {}, Laboratory_AnalyzerQueue_Widget.locked);
			$("#laboratory-direction-table").find("tr[data-id]").each(function(i, tr) {
				var id = $(tr).attr("data-id");
				if ($.inArray(+id, directions) == -1) {
					if (!$(tr).data("core-loading")) {
						Laboratory_AnalyzerQueue_Widget.lock(id, WEAK_LOCK);
					} else {
						$(tr).addClass("danger");
					}
				} else {
					Laboratory_AnalyzerQueue_Widget.unlock(id);
				}
			});
			for (var i in locked) {
				if (locked[i] != WEAK_LOCK && $.inArray(+i, directions) != -1) {
					Laboratory_AnalyzerQueue_Widget.lock(i);
				} else if (locked[i] == STRONG_LOCK && Laboratory_AnalyzerQueue_Widget.locked[i] == WEAK_LOCK) {
					Laboratory_AnalyzerQueue_Widget.lock(i);
				}
			}
		});
		var activate = function() {
			if (/^#\d$/.test(window.location.hash || "")) {
				menu.find("li > a[data-id='"+ window.location.hash.substr(1) +"']")
					.parent("li").trigger("click");
			} else {
				menu.find("li:first-child > a[data-id]")
					.parent("li").trigger("click");
			}
		};
		activate();
		$(".panel").on("panel.updated", function() {
			activate();
		});
		$(document).on("table.updated", "#laboratory-direction-table", function() {
			activate();
		});
		var fetch = function() {
			var menu = $("#analyzer-tab-menu");
			/* We must lock table and panel update to fetch extra tab information */
			$.ajax({
				url: url("laboratory/laboratory/tabs"),
				async: false,
				dataType: "json"
			}).done(function(response) {
				if (!response["status"]) {
					return Core.createMessage({
						message: response["message"]
					});
				}
				var dirs = response["result"];
				for (var i in dirs) {
					menu.find("a[data-id='"+ dirs[i]["id"] +"']").attr("data-directions", dirs[i]["directions"]);
				}
			});
		};
		$(document).on("table.update", "#laboratory-direction-table", fetch);
	}
};

var Laboratory_Clock_Loading = {
	ready: function(duration, steps, success) {
		duration = duration || 2000;
		steps = steps || 12;
		var i = 0;
		var h = function() {
			if (i >= steps) {
				success && success();
				return false;
			}
			$(".clock-container > img:eq(2)").rotate(360 / steps * i + 360 / steps, duration / 8, "linear", 360 / steps * i);
			++i;
			return true;
		};
		var c = function() {
			$(".clock-container > img:eq(1)").rotate(360, {
				duration: duration,
				easing: "linear",
				complete: function() {
					if (h()) { c(); }
				}
			});
		};
		c();
	}
};

$(document).ready(function() {

	Laboratory_AnalyzerQueue_Widget.ready();
	Laboratory_Analyzer_TabMenu.ready();

	$(document).on("barcode.captured", function(e, p) {
		var table = $("#laboratory-direction-table");
		if (!table.data("core-loading")) {
			table.table("before", 1);
		}
		$.get(url("laboratory/direction/test"), {
			id: p.barcode, status: 2 /* LDirection::STATUS_LABORATORY */
		}, function(response) {
			if (!response["status"]) {
				Core.createMessage({
					message: response["message"]
				});
				return void 0;
			} else if (response["message"]) {
				return Core.createMessage({
					message: response["message"],
					type: "success",
					sign: "ok"
				});
			}
			var l = Laboratory_AnalyzerQueue_Widget.locked[p.barcode];
			if (l == STRONG_LOCK) {
				return Core.createMessage({
					message: "Направление уже отправлено на анализатор",
					type: "warning",
					delay: 7000
				});
			} else if (l == WEAK_LOCK) {
				return Core.createMessage({
					message: "Тип анализа направления не доступен для этого анализатора",
					type: "warning",
					delay: 7000
				});
			} else {
				Laboratory_AnalyzerQueue_Widget.send(p.barcode);
			}
		}, "json").always(function() {
			if (table.data("core-loading")) {
				table.table("after");
			}
		});
	});

	/* if (localStorage.getItem("locked") != null) {
		var locked = localStorage.getItem("locked");
		try {
			locked = $.parseJSON(locked);
			for (var i in locked) {
				if (locked[i] == STRONG_LOCK) {
					Laboratory_AnalyzerQueue_Widget.send(i);
				}
			}
			Laboratory_AnalyzerQueue_Widget.locked = locked;
		} catch (ignore) {
		}
	} */
});