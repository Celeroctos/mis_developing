
var Laboratory_AnalyzerTask_Menu = {
	ready: function() {
		var me = this;
		$("#analyzer-task-viewer").on("click", ".analyzer-task-menu-item", function() {
			if (me.active != null) {
				me.active.removeClass("active");
			}
			me.active = $(this).addClass("active");
			$(".analyzer-task-tab").hide();
			console.log($("#" + $(this).children("a").attr("data-tab")).show());
		});
	},
	active: null
};

$(document).ready(function() {

	Laboratory_AnalyzerTask_Menu.ready();

	$(document).on("barcode.captured", function(e, p) {
		var tr = $("#laboratory-direction-table").find("tr[data-id='"+ p.barcode +"']");
		if (!tr.length) {
			setTimeout(function() {
				Laboratory_DirectionTable_Widget.show(p.barcode);
			}, 1000);
			return Core.createMessage({
				message: "Направление с номером ("+ p.barcode +") не направлялось в лабораторию",
				delay: 5000,
				type: "danger"
			});
		}
	});
});
