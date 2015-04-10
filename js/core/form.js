var Core = Core || {};

(function(Core) {

	"use strict";

	var Form = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			opacity: 0.4,
			animation: 100,
			url: null,
			parent: null,
			disabled: false
		}, selector);
	});

    Form.prototype.render = function() {
        this.update();
    };

    Form.prototype.before = function() {
        this.selector().find(".form-group").animate({
            opacity: this.property("opacity")
        }, this.property("animation"));
        this.property("parent") && this.property("parent").find(".refresh-button").replaceWith(
            $("<img>", {
                src: url("/images/ajax-loader.gif"),
                width: "25px",
                class: "refresh-image"
            })
        );
    };

    Form.prototype.after = function() {
        this.selector().find(".form-group").animate({
            opacity: 1
        }, this.property("animation"));
        this.property("parent") && this.property("parent").find(".refresh-image").replaceWith(
            $("<span>", {
                class: "glyphicon glyphicon-refresh refresh-button",
                style: "font-size: 25px; cursor: pointer"
            })
        );
    };

    Form.prototype.update = function(after) {
        var me = this;
        var form = this.selector();
        if (!this.property("url")) {
            return Core.createMessage({
                message: "Missed 'url' property for AutoForm component"
            });
        }
        var url = this.property("url").substring(
            0, this.property("url").lastIndexOf("/")
        ) + "/getWidget";
        this.before();
        $.get(url, {
            class: form.data("widget"),
            form: form.serialize(),
            id: form.attr("id"),
            model: form.data("form"),
            url: form.attr("action")
        }, function(json) {
            if (!json.status) {
                me.after();
                me.activate();
                return Core.createMessage({
                    message: json.message
                });
            }
            me.selector().replaceWith(
                me.selector($(json["component"]))
            );
            me.selector().find(".form-group").css("opacity",
                me.property("opacity")
            );
            me.after();
            me.activate();
            after && after(me);
        }, "json");
    };

    Form.prototype.activate = function() {
        var me = this;
        this.property("parent") && this.property("parent").find(".refresh-button").click(function() {
            $(this).replaceWith(
                $("<img>", {
                    src: url("/images/ajax-loader.gif"),
                    width: "25px",
                    class: "refresh-image"
                })
            );
            me.update();
        });
    };

    Form.prototype.send = function(after) {
		if (this.property("disabled")) {
			return false;
		}
        this.selector().find(".form-group").removeClass("has-error");
        var form = this.selector();
        if (!this.property("url")) {
            return Core.createMessage({
                message: "Missed 'url' property for AutoForm component"
            });
        }
        var me = this;
        $.post(this.property("url"), {
            model: form.serialize()
        }, function(json) {
            me.after();
            if (!json["status"]) {
                after && after(me, false);
				return Core.postFormErrors(me.selector(), json);
            } else {
                if (me.property("success")) {
                    me.property("success").call(me, json);
                }
                after && after(me, true);
            }
            if (json["message"]) {
                Core.createMessage({
                    type: "success",
                    sign: "ok",
                    message: json["message"]
                });
            }
            $("#" + me.selector().attr("id")).trigger("success", json);
        }, "json").fail(function() {
			after && after(me, false, arguments[2]);
		});
        form.serialize();
        return true;
    };

	Core.createPlugin("form", function(selector, properties) {
		return Core.createObject(new Form(properties, $(selector)), selector, false);
	});

})(Core);

$(document).ready(function() {
	$("[id$='-panel'], [id$='-modal']").each(function(i, item) {
		if (!$(item).find("form").length) {
			return void 0;
		}
		var f = $(item).find("form:eq(0)").form({
			url: $(item).find("form").attr("action"),
			parent: $(item)
		});
		$(item).find("button.btn[type='submit']").click(function() {
			var btn = this;
			var c = function(me, status, msg) {
				$(btn).button("reset");
				if (status) {
					$(item).modal("hide");
				} else if (msg) {
					Core.createMessage({
						message: "Произошла ошибка при отправке запроса. Обратитесь к администратору"
					});
				}
			};
			if (f.form("send", c)) {
				$(this).data("loading-text", "Загрузка ...").button("loading");
			}
		});
	});
});