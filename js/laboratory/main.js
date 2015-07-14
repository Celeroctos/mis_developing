var Laboratory_Menu_Treatment = {
	ready: function() {
        var me = this, tabs = [
            "laboratory-container-direction-active",
            "laboratory-container-direction-repeat"
        ];
        for (var i in tabs) {
            $(".nav > li > a[data-tab='"+ tabs[i] +"']").click(function() {
                /* me.updatePanel($(this).attr("data-tab")); */
            });
        }
        $(".nav > li > a[data-tab='laboratory-container-direction-create']").click(function() {
            if (!$(this).parent().hasClass("active")) {
                Laboratory_Widget_PatientCreator.cleanup($("#" + $(this).attr("data-tab")));
            }
        });
	},
    updatePanel: function(tab) {
        setTimeout(function() {
            $("#" + tab).find(".panel:eq(0)").panel("update");
        }, 0);
    }
};

var Laboratory_Widget_PatientCreator = {
	ready: function() {
        var me = this;
        $(".laboratory-wrapper-patient-creator").each(function(i, w) {
            me.prepare($(w))
        });
        $("#treatment-document-control-wrapper").find("label[data-target]").click(function() {
			$($(this).attr("data-target")).slideToggle("normal");
			if ($(this).children("input[type='checkbox']").prop("checked")) {
				$(this).children(".glyphicon").rotate(360, 350, "swing", 180);
			} else {
				$(this).children(".glyphicon").rotate(180, 350, "swing", 0);
			}
		});
	},
    prepare: function(wrapper) {
        var me = this;
        wrapper.find(".laboratory-button-save-patient").click(function() {
            var wrapper = $(this).parents(".laboratory-wrapper-patient-creator");
            Core.resetFormErrors(wrapper);
            var forms = [];
            wrapper.loading({
                width: 100, height: 100
            }).loading("render");
            wrapper.find("form").each(function(i, f) {
                forms.push($(f).serialize());
            });
            $.post(url("laboratory/direction/register"), forms.join("&"), function(json) {
                if (json["errors"]) {
                    Core.postFormErrors(wrapper, json);
                } else {
                    if (!json["status"]) {
                        return Core.createMessage({
                            message: json["message"]
                        });
                    } else if (json["message"]) {
                        Core.createMessage({
                            type: json["type"] || "success",
                            sign: "ok",
                            message: json["message"]
                        });
                    }
                }
                if (json["status"]) {
                    Laboratory_DirectionTable_Widget.show(json["direction"]);
                    me.cleanup(wrapper);
                }
            }, "json").always(function() {
                wrapper.loading("reset");
            });
        });
    },
	load: function(model) {
		var wrapper = $(".laboratory-wrapper-patient-creator:visible");
		var put = function(from, key, value) {
			if (key == "card_number") {
				return void 0;
			}
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
		var forms = {
			medcard_id: "form[data-form='Laboratory_Form_Medcard']",
			patient_id: "form[data-form='Laboratory_Form_Patient']"
		};
		for (var i in model) {
			var m = model[i], f, base;
			if (i in forms && (f = wrapper.find(forms[i])).length) {
				base = f;
			} else if ((f = wrapper.find("#" + i)).length > 0 && f.data("form")) {
				base = $("#" + f.data("form"));
			} else {
				base = wrapper;
			}
			for (var j in m) {
				put(base, j, m[j]);
			}
		}
		wrapper.find("input[data-laboratory='address']").address("calculate");
	},
    cleanup: function(wrapper) {
        var panel = null;
        setTimeout(function() {
            panel = wrapper.find(".laboratory-panel-patient-creator-medcard").loading({
                image: url("images/loader.gif"),
                width: 40,
                height: 40
            }).loading("render");
        }, 0);
        Core.sendQuery("laboratory/medcard/generate", {}, function(response) {
            wrapper.find("[id='card_number']").val(response["number"]);
        }).always(function() {
            if (panel != null) {
                panel.loading("reset");
            }
        });
        wrapper.cleanup().find("select#analysis_type_id")
            .trigger("change");
    }
};

var Laboratory_Widget_PatientEditor = {
    ready: function() {
        var wrapper = $(".medcard-viewer:visible > .panel:eq(0)");
        $("#laboratory-modal-patient-editor-save-button").click(function() {
            var $this = $(this);
            $this.parents(".modal-content:eq(0)").loading({ width: 75, height: 75 })
                .loading("render");
            $this.parents(".modal:eq(0)").find("form:eq(0)").form({
                success: function() {
                    setTimeout(function() {
                        wrapper.panel("update");
                    }, 0);
                    $this.parents(".modal:eq(0)").modal("hide");
                }
            }).form("send").always(function() {
                $this.parents(".modal-content:eq(0)").loading("reset");
            });
        });
    },
    load: function(id) {
        var wrapper = $(".medcard-viewer:visible > .panel:eq(0)").panel("before"),
            modal = $("#laboratory-modal-patient-editor");
        return Core.loadWidget('Laboratory_Widget_PatientEditor', {
            patient: id
        }, function(response) {
            modal.modal("show").find(".modal-body > .row > div").html(response);
        }).always(function() {
            wrapper.panel("after");
        });
    }
};

var Laboratory_Widget_MedcardSearch = {
	ready: function() {
		var me = this, ajax = null;
		$(document).on("click", "[id='medcard-search-button']", function() {
			me.search($(this).parents(".medcard-search-wrapper:eq(0)"));
		});
		$("#medcard-search-table-wrapper").on("click", ".pagination li:not(:disabled)", function() {
			me.reset();
		});
        var allowedKeys = [ 13 ];
        $(".medcard-search-handler").keydown(function(e) {
            if (ajax != null) {
                ajax.abort();
            }
            /* String.fromCharCode(e.keyCode).match(/[a-zA-Z0-9]/ig) */
            if ($.inArray(e.keyCode, allowedKeys) != -1) {
                ajax = me.search($(this).parents('.medcard-search-wrapper:eq(0)'));
            }
        });
	},
	search: function(wrapper) {
		wrapper.find("#medcard-search-button").button("loading");
		var table = wrapper.find("table");
		var data = wrapper.find("#medcard-search-form").serialize() + "&" +
			wrapper.find("#medcard-range-form").serialize() + "&provider=" + table.attr("data-provider");
		data += "&config=" + encodeURIComponent(table.attr("data-config"));
		return Core.sendPost("laboratory/medcard/search", data, function(json) {
			table.replaceWith($(json["component"]));
			Core.createMessage({
				message: "Таблица обновлена",
				sign: "ok",
				type: "success",
				delay: 2000
			});
		}, "json").always(function() {
			wrapper.find("#medcard-search-button").button("reset");
		});
	},
	click: function(id) {
		var me = this;
		me.active && me.active.removeClass("medcard-table-active");
		me.active = $(this).addClass("medcard-table-active");
		if (me.active) {
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

var Laboratory_Modal_MedcardSearch = {
	ready: function() {
		var me = this;
		var modal = $("#laboratory-modal-medcard-search");
		modal.on("show.bs.modal", function() {
			$(this).find("button#load").prop("disabled", "disabled");
		});
		modal.on("click", "tr[data-id]", function() {
			me.id = $(this).data("id");
            $(this).parents("table:eq(0)").find(".laboratory-active-tr")
                .removeClass("laboratory-active-tr");
			$(modal.find("#load")[0]).text("Открыть (" + me.id + ")").removeProp("disabled");
            $(this).addClass("laboratory-active-tr");
		});
		modal.find("#load").click(function() {
			if (!me.id) {
				return void 0;
			}
			var text = $(this).text();
			var btn = $(this).button("loading");
			$.get(url("laboratory/medcard/load"), {
				number: me.id
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
				Laboratory_Widget_PatientCreator.load(json["model"]);
				modal.modal("hide");
			}, "json")
				.always(function() {
					btn.button("reset").text(text);
				}).fail(function() {
					Core.createMessage({
						message: "Произошла ошибка при отправке запроса. Обратитесь к администратору"
					});
				})
		});
	},
    prepare: function(wrapper) {
    },
	id: null
};

var Laboratory_DirectionTable_Widget = {
	ready: function() {
		var me = this;
		$(document).on("click", ".direction-repeat-icon", function() {
			me.repeat($(this).parents("tr:eq(0)").attr("data-id"));
		}).on("click", ".direction-restore-icon", function() {
			me.cancel($(this).parents("tr:eq(0)").attr("data-id"));
		}).on("click", ".direction-show-icon", function() {
			me.show($(this).parents("tr:eq(0)").attr("data-id"), this);
		}).on("click", ".direction-send-icon", function() {
			Laboratory_AnalyzerQueue_Widget && Laboratory_AnalyzerQueue_Widget.send(
				$(this).parents("tr:eq(0)").attr("data-id"), $(this)
			);
		}).on("click", ".direction-remove-icon", function() {
			Laboratory_AnalyzerQueue_Widget && Laboratory_AnalyzerQueue_Widget.remove(
				$(this).parents("tr:eq(0)").attr("data-id"), $(this)
			);
		}).on("click", ".direction-result-icon", function() {
			Laboratory_AnalysisResult_Widget && Laboratory_AnalysisResult_Widget.open(
				$(this).parents("tr:eq(0)").attr("data-id"), $(this)
			);
		})
		$(document).on("dblclick", "table > tbody > tr[data-id]", function() {
			if ($(this).parents("table:eq(0)").find(".direction-show-icon").length > 0) {
				me.show($(this).attr("data-id"));
			}
		});
		$(".panel-date-button").each(function(i, p) {
			var dates = $(p).parents(".panel:eq(0)").find(".table:eq(0)").attr("data-dates");
			if (dates) {
				dates = $.parseJSON(dates);
			} else {
				dates = [];
			}
			me.createDatePicker(p, dates);
		});
		$(".panel-today-button").click(function() {
			me.updateByDate($(this).parents(".panel:eq(0)").find(".table:eq(0)"), $(this).attr("data-date"));
		});
		$("#direction-register-modal, #laboratory-modal-patient-creator").on("show.bs.modal", function() {
			Core.Common.cleanup(this);
		});
		$("#laboratory-container-direction-active > .panel").on("panel.updated", function() {
			setTimeout(function() {
				me.refreshDatePicker()
			}, 250);
		});
	},
	refreshDatePicker: function(dates) {
		var me = this;
		$(".panel-date-button").each(function(i, p) {
			dates = dates || $(p).parents(".panel:eq(0)")
				.find(".table:eq(0)")
				.attr("data-dates");
			try {
				if (typeof dates == "string") {
					dates = $.parseJSON(dates);
				}
			} catch (ignored) {
			}
			me.createDatePicker(p, dates || []);
		});
	},
	updateByDate: function(table, date, success) {
		this._lastDate = date;
		table.table("update", { date: date }, success);
	},
	createDatePicker: function(element, dates) {
		var me = this;
		if ($(element).data("datepicker")) {
			$(element).datepicker("remove");
		}
		var handler = function () {
			var t = $.datepicker.formatDate("yy-mm-dd", $(element).datepicker("getDate"));
			if (t.length > 0) {
				$(".direction-date").text(t);
			}
		};
		$(element).datepicker({
			language: "ru-RU",
			orientation: "top",
			todayBtn: "linked",
			container: $(element).parents(".panel:eq(0)"),
			beforeShowDay: function(date) {
				if (me._lastDate == date) {
					return "active";
				} else if ($.inArray($.datepicker.formatDate("yy-mm-dd", date), dates) != -1) {
					return "treatment-marked-day";
				} else {
					return void 0;
				}
			}
		}).unbind("changeDate").on("changeDate", function () {
			var $this = $(this);
			me.updateByDate($(this).parents(".panel:eq(0)").find(".table"), $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")),
				function () {
					$(element).parents(".panel:eq(0)").trigger("panel.updated");
				});
            setTimeout(function() { $this.datepicker("hide"); }, 100);
		}).parents(".panel:eq(0)").unbind("panel.updated", handler)
			.bind("panel.updated", handler);
	},
	update: function(success) {
        $(".laboratory-table-wrapper > div > .panel:visible").each(function(i, p) {
            $(p).panel("update");
        });
        success && success();
	},
	show: function(id, from) {
		var panel = from ? $(from).parents(".panel:eq(0)") :
			$(".table-wrapper .panel:eq(0)");
		if (panel.length > 0) {
			panel.panel("before");
		} else {
			panel = $(from).parents('table').loading("render");
		}
		Core.loadWidget("Laboratory_Widget_AboutDirection", {
			direction: id
		}, function (component) {
			$("#laboratory-modal-about-direction").modal().find(".modal-body")
				.empty().append(component);
		}).always(function() {
			if (panel.length > 0) {
				if (panel.is("table")) {
					panel.loading("reset");
				} else {
					panel.panel("after");
				}
			}
		}).fail(function() {
			Core.createMessage({
				message: "Невозможно открыть направление"
			});
		});
	},
	repeat: function(id) {
		var me = this;
		$.post(url("laboratory/direction/repeat"), {
			id: id
		}, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Core.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			$("#treatment-repeat-counts").text(json["repeats"]);
			me.refreshDatePicker(json["dates"]);
			me.update();
            var table = $("tr[data-id='"+ id +"']").parents(".panel:eq(0)");
            table.panel("update");
		}, "json");
	},
	cancel: function(id) {
		var me = this;
		$.post(url("laboratory/direction/restore"), {
			id: id
		}, function(json) {
			if (!json["status"]) {
				return Core.createMessage({
					message: json["message"]
				});
			} else if (json["message"]) {
				Core.createMessage({
					message: json["message"],
					sign: "ok",
					type: "success"
				});
			}
			$("#treatment-repeat-counts").text(json["repeats"]);
			me.refreshDatePicker(json["dates"]);
			me.update();
		}, "json");
	},
	_lastDate: null
};

var Laboratory_Medcard_Table = {
	ready: function() {
		$(document).on("click", ".direction-register-icon", function() {
			$("#laboratory-modal-direction-creator").cleanup().modal().find("[name='Laboratory_Form_DirectionEx[medcard_id]']").val(
				$(this).parents("tr:eq(0)").attr("data-id")
			);
		}).on("click", ".medcard-show-icon", function() {
			var loading = $(this).parents(".panel:eq(0)");
			if (loading.length > 0) {
				loading.panel("before")
			} else {
                loading = $(this).parents(".table:eq(0)").loading("render");
            }
			Laboratory_Widget_AboutMedcard.load($(this).parents("tr:eq(0)").attr("data-id")).always(function() {
                if (loading.is(".panel")) {
                    loading.panel("after");
                } else {
                    loading.loading("reset");
                }
			});
		});
	}
};

var Laboratory_Widget_AboutMedcard = {
	ready: function() {
		$(document).on("click", ".direction-creator-cancel", function() {
			$(this).parents(".direction-history-wrapper").find(".nav > li:first > a").tab("show");
			$(this).parents("form:eq(0)").cleanup();
		}).on("click", ".direction-creator-register", function() {
			var f = $(this).parents(".direction-creator-wrapper").children("form");
            var loading = $(this).parents(".panel:eq(0)");
            if (!loading.length) {
                loading = f;
            }
            loading.loading("render");
			f.form("send", function(status) {
				if (status) { $(this).panel("update"); }
			}).always(function() {
                loading.loading("destroy");
			});
		}).on("click", ".laboratory-about-medcard-panel-edit-button", function() {
            Laboratory_Widget_PatientEditor.load($(this).parents(".medcard-viewer:eq(0)")
                .find("#laboratory-about-medcard-patient-id").val()
            );
        });
	},
	load: function(id) {
		return Core.loadWidget("Laboratory_Widget_AboutMedcard", {
			medcard: id
		}, function(component) {
			$("#laboratory-modal-about-medcard").modal().find(".modal-body").empty().append(component);
		});
	}
};

var Laboratory_AboutDirection_Widget = {
	ready: function() {
		$("#laboratory-modal-about-direction").on("click", "#open-medcard-button", function() {
			Laboratory_Widget_AboutMedcard.load($("#treatment-about-direction-medcard-id").val());
		}).on("click", "#print-barcode-button", function() {
			Laboratory_Printer.print('.barcode-wrapper')
		}).on("click", "#send-to-laboratory-button", function() {
			var me = $(this);
			me.parents(".panel:eq(0)").loading("render");
			me.parents(".about-direction").find(".direction-info-wrapper").form({
				success: function(response) {
					Laboratory_DirectionTable_Widget.refreshDatePicker(response["dates"]);
					$("#treatment-repeat-counts").text(response["repeats"]);
                    Laboratory_DirectionTable_Widget.update();
                    setTimeout(function() {
                        $("#laboratory-modal-about-direction").modal("hide");
                    }, 1000);
				}
			}).form("send").always(function() {
				me.parents(".panel").loading("reset");
			});
		}).on("shown.bs.modal", function() {
			/* $(this).find("#treatment-direction-history-panel").panel("collapse"); */
		}).on("change", "[name='Laboratory_Form_AboutDirection[sample_type_id]']", function() {
			/* if ($(this).val() != -1) {
				$("#send-to-laboratory-button").prop("disabled", false);
			} else {
				$("#send-to-laboratory-button").prop("disabled", true);
			} */
		});
	},
	register: function(form) {
	}
};

var Laboratory_DirectionCreator_Modal = {
	ready: function() {
		$("#treatment-laboratory-modal-direction-creator-save-button").click(function() {
			var modal = $(this).parents(".modal");
			modal.find(".modal-content").loading("render");
			$(this).parents(".modal").find("form").form("send", function(status) {
				if (status) {
					$(this).parents('.modal').modal("hide");
					Laboratory_DirectionTable_Widget.update();
				}
			}).always(function() {
				modal.find(".modal-content").loading("reset");
			});
		});
		$("#laboratory-modal-direction-creator").on("show.bs.modal", function() {
			$(this).find("[name='Laboratory_Form_DirectionEx[analysis_type_id]']").trigger("change");
		});
	}
};

var Laboratory_DirectionFormEx_Form = {
	ready: function() {
		$(document).on("change", "[name='Laboratory_Form_DirectionEx[analysis_type_id]']", function() {
			var me = $(this), form = me.parents("form:eq(0)");
			if (!me.val() || me.val() == -1) {
				var c = form.find("[name='Laboratory_Form_DirectionEx[analysis_parameters]']");
				if (!c.length) {
					c = form.find(".analysis-type-params-wrapper");
				}
				c.parents(".form-group:eq(0)").addClass("hidden");
				c.children().remove();
				return void 0;
			}
			me.loading({ width: 15, height: 15 }).loading("render");
			Core.sendQuery("laboratory/direction/params", {
				id: me.val()
			}, function(response) {
				var c = form.find("[name='Laboratory_Form_DirectionEx[analysis_parameters]']");
				if (!c.length) {
					c = form.find(".analysis-type-params-wrapper");
				}
				c.parents(".form-group:eq(0)").removeClass("hidden");
				c.replaceWith(response["component"]);
			}).always(function() {
				me.loading("reset");
			});
		});
		$(document).on("change", "[name='Laboratory_Form_DirectionEx[pregnant]']", function() {
			var fields = [
				"[name='Laboratory_Form_DirectionEx[gestational_age]']",
				"[name='Laboratory_Form_DirectionEx[menstruation_cycle]']"
			];
			if ($(this).val() == 0) {
				$(fields.join(",")).prop("disabled", true).each(function(i, s) {
					$(s).val($(s).children("option:eq(0)").attr("value"));
				});
			} else {
				$(fields.join(",")).prop("disabled", false);
			}
		});
	}
};

var Laboratory_Popover_DirectionSearch = {
	ready: function() {
        var me = this;
		$("body").on("click", ".direction-search-button", function() {
            me.search($(this));
		}).on("click", ".direction-search-cancel-button", function() {
            me.cancel($(this));
        }).on("keydown", "#direction-search-form", function(e) {
            if (e.keyCode == 13) {
                me.search($(".direction-search-button"));
            }
        });
	},
    search: function(btn) {
        var popover = btn.parents(".popover-content:eq(0)"),
            form = $("#direction-search-form");
        var panel = $(".panel[data-widget='"+ form.find("#widget").val() +"']:eq(0)")
            .panel("before");
        form.form({
            success: function(response) {
                panel.panel("replace", response);
            }
        }).form("send").always(function() {
            panel.panel("after");
        });
        $(".panel-search-button").popover("hide");
    },
    cancel: function(btn) {
        var popover = btn.parents(".popover-content:eq(0)"),
            form = $("#direction-search-form");
        var panel = $(".panel[data-widget='"+ form.find("#widget").val() +"']:eq(0)")
            .panel("before");
        var except = [ "widget", "config", "provider" ];
        form.find("input, select, textarea").each(function(i, w) {
            var $w = $(w);
            if (except.indexOf($w.attr("id")) == -1) {
                if ($w.is("select")) {
                    $w.val("-1");
                } else {
                    $w.val("");
                }
            }
        });
        form.form({
            success: function(response) {
                panel.panel("replace", response);
            }
        }).form("send").always(function() {
            panel.panel("after");
        });
        $(".panel-search-button").popover("hide");
    }
};

var Laboratory_Printer = {
	print: function(selector) {
		this.popup($(selector).clone().html());
	},
	popup: function(data) {
		var win = window.open('', 'my div', 'width=800,height=600');
		win.document.write('<html><head><title></title>');
		win.document.write('</head><body >');
		win.document.write(data);
		win.document.write('</body></html>');
		win.document.close();
		win.focus();
		win.print();
		win.close();
	}
};

const BARCODE_RESET_INTERVAL = 200;
const BARCODE_SEQUENCE_LENGTH = 8;

var Laboratory_BarcodeReader = {
	ready: function() {
		var me = this;
		$(document).on("keydown", function(e) {
			if (!(e.keyCode >= 48 && e.keyCode <= 57) &&
				!(e.keyCode >= 97 && e.keyCode <= 105)
			) {
				return void 0;
			}
			me.input(e.keyCode);
		});
	},
	input: function(code) {
		var me = this;
		if (me.timer == -1) {
			me.timer = setTimeout(function() {
				me.reset();
			}, BARCODE_RESET_INTERVAL);
			me.time = new Date().getMilliseconds();
		}
		if (code >= 48 && code <= 57) {
			me.sequence.push(code - 48);
		} else {
			me.sequence.push(code - 97);
		}
		if (me.sequence.length == BARCODE_SEQUENCE_LENGTH) {
			this.finalize(parseInt(this.sequence.join("")));
		}
	},
	finalize: function(sequence) {
		this.last = sequence;
		console.log("captured barcode sequence: " + this.last );
		console.log("elapsed time: " + (new Date().getMilliseconds() - this.time));
		this.reset();
		$(document).trigger("barcode.captured", {
			barcode: this.last
		});
	},
	reset: function() {
		console.log("timer is reset");
		if (this.timer >= 0) {
			clearInterval(this.timer);
		}
		this.timer = -1;
		this.sequence = [];
	},
	timer: -1,
	sequence: [],
	last: -1
};

var Laboratory_TabMenu_Widget = {
	ready: function() {
		$("ul.nav[id*=tabmenu]").find(" > li").click(function() {
            var menu = $(this).parents("ul:eq(0)");
            menu.find(" > li > a[data-tab]").each(function(i, a) {
				$("#" + $(a).attr("data-tab")).hide();
			});
			$("#" + $(this).children("a").attr("data-tab")).show();
			menu.find(" > li").removeClass("active");
			$(this).addClass("active");
		});
	}
};

$(document).ready(function() {

	Laboratory_Menu_Treatment.ready();
	Laboratory_Widget_PatientCreator.ready();
    Laboratory_Widget_PatientEditor.ready();
	Laboratory_Widget_MedcardSearch.ready();
	Laboratory_Modal_MedcardSearch.ready();
	Laboratory_Medcard_Table.ready();
	Laboratory_DirectionTable_Widget.ready();
	Laboratory_Widget_AboutMedcard.ready();
	Laboratory_AboutDirection_Widget.ready();
	Laboratory_DirectionCreator_Modal.ready();
	Laboratory_DirectionFormEx_Form.ready();
	Laboratory_Popover_DirectionSearch.ready();
	Laboratory_BarcodeReader.ready();
	Laboratory_TabMenu_Widget.ready();

	/* fix for modal window backdrop */
	$(document).on("show.bs.modal", ".modal", function(e) {
        var total = $('.modal:visible').length;
		if (!$(e.target).hasClass("modal")) {
			return void 0;
		}
		var depth = 1140 + (10 * total),
            offset = total * 75;
		$(this).css('z-index', depth).children()
            .css('margin-top', offset);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', depth - 1).addClass('modal-stack');
		}, 0);
	});

    /* fix for popover hide with disabled animation */
    $("body").on("hidden.bs.popover", function() {
        $('.popover:not(.in)').hide().detach();
    });

	$("[data-toggle='popover']").popover();

    var modals = [
        "#laboratory-modal-about-direction",
        "#laboratory-modal-medcard-search"
    ];

    for (var i in modals) {
        $(modals[i]).on("show.bs.modal", function() {
            var me = this;
            setTimeout(function() {
                $("#" + $(me).attr("id")).stop().animate({ scrollTop: 0 }, 250, 'swing');
            }, 100);
        });
    }
});
