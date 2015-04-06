var ConfirmDelete = {
    construct: function() {
        $(document).on("click", ".confirm-delete", function(e) {
            if (ConfirmDelete.lock) {
                return void 0;
            }
            ConfirmDelete.item = $(e.target);
            $("#confirm-delete-modal").modal();
            e.stopImmediatePropagation();
            return false;
        });
        $("#confirm-delete-button").click(function() {
            ConfirmDelete.lock = true;
            if (ConfirmDelete.item != null) {
                ConfirmDelete.item.trigger("click");
            }
            setTimeout(function() {
                ConfirmDelete.lock = false;
            }, 250);
        });
    },
    item: null,
    lock: false
};

var Common = {
	cleanup: function(component) {
		$(component).find("input, textarea").val("");
		$(component).find("select:not([multiple])").each(function(i, item) {
			$(item).val($(item).find("option:eq(0)").val());
		});
		$(component).find("select[multiple]").val("");
		$(component).find(".form-group").removeClass("has-error");
	}
};

var Panel = {
    construct: function() {
        $(document).on("click", ".collapse-button", function() {
            var me = $(this);
            var body = $(me.parents(".panel")[0]).children(".panel-body");
            if ($(this).hasClass("glyphicon-chevron-up")) {
                body.slideUp("normal", function() {
                    me.removeClass("glyphicon-chevron-up")
                        .addClass("glyphicon-chevron-down");
                });
            } else {
                body.slideDown("normal", function() {
                    me.removeClass("glyphicon-collapse-down")
                        .addClass("glyphicon-chevron-up");
                });
            }
        });
    }
};

var Table = {
	fetch: function(me, parameters) {
		var table = $(me).parents(".table[data-class]");
		$.get(url("/laboratory/medcard/getWidget"), $.extend(parameters, {
			class: table.data("class"),
			condition: table.data("condition"),
			params: table.data("parameters")
		}), function(json) {
			if (!Message.display(json)) {
				return void 0;
			}
			$(me).parents(".table[data-class]").replaceWith(
				$(json["component"])
			);
		}, "json");
	},
	order: function(row) {
		var parameters = {};
		if ($(this).find(".glyphicon-chevron-down").length) {
			parameters["desc"] = true;
		}
		parameters["sort"] = row;
		Table.fetch(this, parameters);
	},
	page: function(page) {
		var td = $(this).parents(".table[data-class]").find("tr:first-child td .glyphicon").parents("td");
		var parameters = {};
		if (td.find(".glyphicon-chevron-up").length) {
			parameters["desc"] = true;
		}
		parameters["sort"] = td.data("key");
		parameters["page"] = page;
		Table.fetch(this, parameters);
	}
};

var DropDown = {
    change: function(animate, update) {
        const DELAY = 100;
        if (animate === undefined) {
            animate = true;
        }
        if (update === undefined) {
            update = true;
        }
        var hide = function(group) {
            if (!group.hasClass("hidden")) {
                if (animate) {
                    group.slideUp(DELAY, function() {
                        $(this).addClass("hidden");
                    });
                } else {
                    group.addClass("hidden");
                }
            }
        };
        var show = function(group) {
            if (group.hasClass("hidden")) {
                if (animate) {
                    group.removeClass("hidden").hide().slideDown(DELAY);
                } else {
                    group.removeClass("hidden");
                }
            }
        };
        var toggle = function(group, it, wait) {
            setTimeout(function() {
                if (it.val() == "dropdown" || it.val() == "multiple") {
                    show(group);
                } else {
                    hide(group);
                }
            }, wait)
        };
        var group = function(that, id) {
            return $(that).parents("form").find("#" + id).parents(".form-group");
        };
        if ($(this).attr("id") == "type" && !$(this).attr("data-update")) {
            var fields = [
                "lis_guide_id",
                "display_id"
            ];
            if ($(this).val() != "dropdown" && $(this).val() != "multiple") {
                fields = fields.reverse();
            }
            var i;
            for (i in fields) {
                toggle(group(this, fields[i]), $(this), i * DELAY);
            }
            if (update && $(this).attr("data-update") && !this.disable) {
                var me = this;
                setTimeout(function () {
                    DropDown.update($(me));
                }, i * DELAY);
            }
        } else if (update && $(this).attr("data-update") && !this.disable) {
            DropDown.update($(this));
        }
    },
    update: function(select, after) {
        var f;
        var form = $(select.parents("form")[0]);
        if (!form.data("lab")) {
            f = Laboratory.createForm(form[0], {
                url: url("/laboratory/guide/getWidget")
            });
        } else {
            f = form.data("lab");
        }
        f.update(after);
    },
    disable: false
};

var Message = {
    display: function(json) {
        if (!json["status"]) {
			Laboratory.createMessage({
				message: json["message"]
			});
            return false
        } else if (json["message"]) {
            Laboratory.createMessage({
                type: "success",
                sign: "ok",
                message: json["message"]
            });
        }
        return true;
    }
};

var MedcardSearch = {
	construct: function() {
		$("[id='medcard-search-button']").click(function() {
			MedcardSearch.search();
		});
		$("#medcard-edit-button").click(function() {
			MedcardSearch.edit();
		});
		$("#medcard-search-table-wrapper").on("click", ".pagination li:not(:disabled)", function() {
			MedcardSearch.reset();
		});
	},
	edit: function(number) {
		if (!(number = number || this.id)) {
			return void 0;
		}
		$.get(url("/reception/patient/getMedcardData"), {
			cardId: number
		}, function(data) {
			if(data.success == true) {
				data = data.data["formModel"];
				var form = $('#patient-medcard-edit-form');
				$('#patient-medcard-edit-modal').modal();
				for(var i in data) {
					$(form).find('#' + i).val(data[i]);
				}
			} else {
				$('#errorSearchPopup .modal-body .row p').remove();
				$('#errorSearchPopup .modal-body .row').append('<p>' + data.data + '</p>')
				$('#errorSearchPopup').modal();
			}
		}, "json");
	},
	search: function() {
		$("#medcard-search-button").button("loading");
		var data = $("#medcard-search-form").serialize() + "&" +
			$("#medcard-range-form").serialize();
		$.post(url("/laboratory/medcard/search"), data, function(json) {
			if (!Message.display(json)) {
				return void 0;
			}
			$("#medcard-table").replaceWith(
				$(json["component"])
			);
			Laboratory.createMessage({
				message: "Таблица обновлена",
				sign: "ok",
				type: "success",
				delay: 2000
			});
		}, "json").always(function() {
			$("#medcard-search-button").button("reset");
		});
	},
	click: function(tr, id) {
		this.active && this.active.removeClass("medcard-table-active");
		this.active = $(tr).addClass("medcard-table-active");
		if (this.active) {
			$("#medcard-edit-button").removeClass("disabled");
		}
		this.id = id;
	},
	reset: function() {
		this.active = this.id = null;
		$("#medcard-edit-button").addClass("disabled");
	},
	active: null,
	id: null
};

var LogoutButton = {
	construct: function() {
		var form = $("#logout-form");
		$(".logout-button").click(function() {
			$.get(form.attr("action"), form.serialize(), function(json) {
				if (json.success) {
					window.location.href = url("");
				}
			}, "json");
		});
	}
};

var MedcardSearchModal = {
	ready: function(selector) {
		var modal = $(selector);
		modal.on("show.bs.modal", function() {
			$(this).find("#load").prop("disabled", "disabled");
		});
		modal.on("click", "#medcard-table tr[data-id]", function() {
			MedcardSearchModal.id = $(this).data("id");
			$(modal.find("#load")[0]).text("Открыть (" + MedcardSearchModal.id + ")").removeProp("disabled");
		});
		modal.find("#load").click(function() {
			if (!MedcardSearchModal.id) {
				return void 0;
			}
			var text = $(this).text();
			var btn = $(this).button("loading");
			$.get(url("laboratory/medcard/load"), {
				number: MedcardSearchModal.id
			}, function(json) {
				if (!Message.display(json)) {
					return void 0;
				}
				MedcardEditableViewerModal.load(json["model"]);
				modal.modal("hide");
			}, "json")
				.always(function() {
					btn.button("reset").text(text);
				}).fail(function() {
					Laboratory.createMessage({
						message: "Произошла ошибка при отправке запроса. Обратитесь к администратору"
					});
				})
		});
	},
	construct: function() {
		this.ready("#mis-medcard-search-modal");
		this.ready("#lis-medcard-search-modal");
	},
	id: null
};

$(document).ready(function() {
	ConfirmDelete.construct();
	Panel.construct();
	MedcardSearch.construct();
	LogoutButton.construct();
	MedcardSearchModal.construct();
});