var Core = Core || {};

(function(Core) {

	"use strict";

	/**
	 * Компонент - "ComboBox с множественным выбором", аналог select[multiple], позволяет
	 * создавать графический элемент с полями и их значениями для совершения множественного
	 * выбора из списка предсталвенных элементов
	 *
	 * Аттрибуты:
	 *  + [filter] - массив со списком наименований CSS полей, которые
	 *  	будут проигнорированы при обработе CSS-HOOK. Это сделано для
	 *  	того, чтобы перехватывать события изменения стилей, которые
	 *  	применяются для объекта select[multiple] и применять их для
	 *  	глобального объекта (объекты из этого списка не будут учитываться)
	 *  + [height] - Высота списка, а именно select[multiple], которая будет
	 *  	испольоваться по умолчанию
	 *  + [multiplier] - объект имеет кнопку "Развернуть/Свернуть", при нажатии
	 *  	на которую увеличивается или умеьншается размер элемент select[multiple],
	 *  	этот коэффициент умножается на height для получения второй высоты
	 *
	 * @param {{}} properties - Аттрибуты компонента, который устанавливаются после
	 * 		вызова метода $("select[multiple]").multiple({});
	 * @param {jQuery|Boolean} selector - Селектор компонента, который
	 * 		используется по умолчанию (в данном случае это select[multiple])
	 * @constructor
	 */
	var Multiple = Core.createComponent(function(properties, selector) {
		/* Инициализация родительского компонента */
		Core.Component.call(this, properties, {
			filter: [
				"height",
				"min-height",
				"max-height",
				"width",
				"min-width",
				"max-width"
			],
			height: 150,
			multiplier: 2
		}, selector);
		/* Клонируем старый элемент и сохраняем со всеми
		 установленными данными и текщуими событиями */
		this.native = selector.clone(true);
		this.choosen = [];
	});

	/**
	 * Рендерим компонент Multiple вместе с базовым списком элементов,
	 * панелью управления и панелью с выбранными элементами
	 *
	 * Структура:
	 *  + div.multiple
	 *  + + select[multiple]
	 *  + + div.multiple-control
	 *  + + + button.multiple-collapse-button
	 *  + + + button.multiple-down-button
	 *  + + + button.multiple-up-button
	 *  + + + button.multiple-insert-button
	 *  + + div.multiple-container
	 *
	 * @returns {jQuery}
	 */
	Multiple.prototype.render = function() {
        var s = this.selector().clone().data(this.getDataAttribute(), this)
            .addClass("multiple-value").css({
                "min-height": this.property("height")
            }).addClass("form-control");
        var g = $("<div>", {
            class: "multiple-control",
            role: "group",
            style: {
                width: this.selector().width()
            }
        }).append(
            $("<button>", {
                class: "btn btn-default multiple-collapse-button",
                type: "button",
                html: $("<span>", {
                    text: "Развернуть / Свернуть"
                }),
				style: "margin-right: 2px"
            })
        ).append(
            $("<button>", {
                class: "btn btn-default multiple-down-button",
                type: "button",
                html: $("<span>", {
                    class: "glyphicon glyphicon-arrow-down"
                }),
				style: "margin-right: 2px"
            })
        ).append(
            $("<button>", {
                class: "btn btn-default multiple-up-button",
                type: "button",
                html: $("<span>", {
                    class: "glyphicon glyphicon-arrow-up"
                }),
				style: "margin-right: 2px"
            })
        ).append(
			$("<button>", {
				class: "btn btn-default multiple-insert-button",
				type: "button",
				html: $("<span>", {
					class: "glyphicon glyphicon-plus"
				})
			}).hide()
		);
		return $("<div>", {
			class: "multiple"
		}).css({
			width: s.width()
		}).append(s).append(g).append(
			$("<div>", {
				class: "multiple-container form-control"
			})
		);
	};

	/**
	 * Данный метод уничтожает компонент и возвращает
	 * его в первоначальному состоянию, пример [$("select[multiple]").multiple("destroy")]
	 */
	Multiple.prototype.destroy = function() {
		this.selector().replaceWith(this.native);
	};

	/**
	 * Данный метод отвечает за активацию компонента, т.е
	 * вешает на него обработчики событий
	 */
	Multiple.prototype.activate = function() {
		var me = this;
		/* Обработка события на выбор элемента из списка
		элементов для выбора (select[multiple]). Для получения
		списка элементов используется нативный метод для
		обычного выпадающего списка */
		this.selector().find("select.multiple-value").change(function() {
			var value = $.valHooks["select"].get(this);
			for (var i in value) {
				$(this).find("option[value='" + value[i] + "']").prop("disabled", true).get(0).selected = false;
			}
			me.choose(value, true);
		});
		/* Обработка события на нажатие клавиши "Развернуть/Свернуть", которая
		разворачивает или сворачивает список элементов через метод $.animate */
        var collapsed = false;
        this.selector().find(".multiple-collapse-button").click(function() {
            var value = me.selector().find(".multiple-value");
            value.animate({
                "min-height": collapsed ? me.property("height") :
                    me.property("height") * me.property("multiplier")
            }, "fast");
            collapsed = !collapsed;
        });
		/* Обработка события на выборо всех элементов из списка. Осуществляется
		обход всех видимых элементов и строится массив из значений полей */
        this.selector().find(".multiple-down-button").click(function() {
			var array = [];
            me.selector().find("select.multiple-value").children("option:not(:hidden)").each(function(i, item) {
				array.push($(item).val());
            });
			me.choose(array);
			/* Fix #13590 - 9 */
			me.selector().find("select[multiple]").trigger("change");
        });
		/* Действие аналогично вышеописанному, но все элементы
		отмечаются как невыбранные */
        this.selector().find(".multiple-up-button").click(function() {
			me.selector().find(".multiple-chosen").each(function(i, item) {
                me.remove($(item).children("div"));
            });
        });
		/* Достаточно кривой фикс для элемента с значением -3, потому что в приеме
		врача оно означает добавление нового элемента в справочник, поэтому, если
		мы встречаем этот элемент, то мы должны спрятать его (уже можно удалить) и
		делаем видимой клавишу для добавления элемента в справочник
		 */
		this.selector().find(".multiple-value option[value='-3']").each(function(i, opt) {
			$(opt).addClass("multiple-really-not-visible").parents(".multiple")
				.find(".multiple-insert-button").show();
		});
		/* Событие обрабатывает действие при добавлении нового элемента в справочник,
		она находит опцию со значением -3 и вызывает событие для обработки
		 */
		this.selector().find(".multiple-control .multiple-insert-button:visible").click(function() {
			var t; applyInsertForSelect.call(t = me.selector().find(".multiple-value").get(0), t);
		});
	};

	/**
	 * Возвращает массив с выбранными элементами, именно с теми
	 * выбранными, которые были отмечаны так, как для старого select[multiple]
	 * через зажатую клавишу Ctrl, вызывается через $("#id").multiple("selected")
	 *
	 * @param {Boolean} [clear] - Если true, то после генерации списка все
	 * 		жэлементы будут сброшены (снова станут неотмеченными)
	 * @returns {Array} - Массив с выбранными элементами
	 */
	Multiple.prototype.selected = function(clear) {
		var result = [],
			options = this.selector().find("select[multiple]")[0].options,
			opt;
		for (var i = 0, j = options.length; i < j; i++) {
			opt = options[i];
			if (opt.selected && opt.value != -3) {
				result.push(opt.value || opt.text);
			}
			if (clear) {
				options[i].selected = false;
			}
		}
		return result;
	};

	/**
	 * Удалить элемент из списка, но не просто элемент, а именно
	 * контейнер div с информацией об элементе, поэтому ручной
	 * вызов этого метода не имеет смысла
	 *
	 * @param {jQuery} key - Контейнер с выбранным элементом
	 */
	Multiple.prototype.remove = function(key) {
		/* Заменяем старый элемент option на новый, потому что
		элемент уже является отмеченным и будет иметь флаг selected */
        key.parents(".multiple").find("option[value='" + key.data("key") + "']").replaceWith(
            $("<option>", {
                value: key.data("key"),
                text: key.text()
            })
        );
		delete this.choosen[key.data("key")];
		/* Удаляем элемент из списка выбранных */
		key.parent(".multiple-chosen").remove();
		/* Fix #13590 - 9 */
		this.selector().find("select[multiple]").trigger("change");
	};

	/**
	 * Очистить все выбранные элементы. Можно также вызывать
	 * через плагин jQuery - $("#id").multiple("clear")
	 */
	Multiple.prototype.clear = function() {
		var me = this;
		this.selector().find(".multiple-chosen").each(function(i, w) {
			me.remove($(w).children("div"));
		});
	};

	/**
	 * Выбирает элемент или массив с элементами, принимает одно
	 * значение, массив значений или JSON строк с массивом значений
	 *
	 * Внимание! Если массив пустой или строка будет пустой, то
	 * будут удалены все выбранные элементы! Так было сделано, не
	 * помню для чего, но что-то такое было
	 *
	 * @param {Array|String} key - Значение для добавления или массив
	 * 		с значениями
	 * @param {Boolean} [slow] - Прятать элемент быстро или
	 * 		медленно. Используется для выбора, чтобы избежать
	 * 		двойное нажатие на один и тот же элемент
	 */
	Multiple.prototype.choose = function(key, slow) {
		var me = this;
		var multiple = this.selector();
		if (typeof key == "string") {
			if (key.trim() !== "") {
				key = $.parseJSON(key);
			} else {
				key = [];
			}
		}
		if (Array.isArray(key)) {
			for (var i in key) {
				this.choose(key[i], slow);
			}
			if (!key.length) {
				me.clear();
			}
			return void 0;
		}
		if (key in this.choosen) {
			return void 0;
		} else {
			this.choosen[key] = true;
		}
		var value = multiple.find("select.multiple-value")
			.find("option[value='" + key + "']");
		if (slow) {
			value.fadeOut(250);
		} else {
			value.hide();
		}
        var name = value.text();
		if (!name.length) {
			return void 0;
		}
		var r, t;
		t = $("<div>", {
			style: "text-align: left; width: 100%",
			class: "multiple-chosen disable-selection row"
		}).append(
			$("<div>", {
				text: name
			}).data("key", key)
		).append(
			r = $("<span>", {
				class: "glyphicon glyphicon-remove",
				style: "color: #af1010; font-size: 15px; cursor: pointer"
			})
		);
		multiple.find("div.multiple-container").append(t);
		r.click(function() {
			me.remove($(this).parent("div").children("div"));
		});
	};

	$.valHooks["select-multiple"] = {

		/**
		 * Возвращает контейнер с элементами для некоторого
		 * потомка класса multiple
		 *
		 * @param {jQuery|HTMLElement} item - Дочерний элемент, от
		 * 		котрого будет начинаться поиск
		 * @returns {jQuery} - Элемент jQuery с контейнером
		 */
		container: function(item) {
			return $(item).parent(".multiple").children(".multiple-container");
		},

		/**
		 * Устанавливает список значений для некоторого
		 * элемента select[multiple]
		 *
		 * @param {HTMLElement} item - Объект select[multiple]
		 * @param {Array|String} list - Значение, которое нужно
		 * 		установить или массив значений
		 */
		set: function(item, list) {
			if ($(item).data("core-multiple") == void 0) {
				return $.valHooks["select"].set(item, list);
			}
			var multiple = $(item).parents(".multiple");
			if (typeof list !== "string") {
				list = JSON.stringify(list);
			} else if (list == null) {
				list = "[]";
			}
			if (list.length) {
				$(item).multiple("choose", $.parseJSON(list));
			} else {
				$(item).multiple("clear");
			}
		},

		/**
		 * Возвращает массив значений для некоторого
		 * элемента select[multiple]
		 *
		 * @param {HTMLElement} item - Элемент select[multiple]
		 * @returns {Array} - Массив выбранных элементов (значений)
		 */
		get: function(item) {
			var list = [];
			if ($(item).data("core-multiple") == void 0) {
				return $.valHooks["select"].get(item);
			}
			this.container(item).find(".multiple-chosen div").each(function(i, c) {
				list.push("" + $(c).data("key"));
			});
			return list.length > 0 ? list : null;
		}
	};

	Core.createPlugin("multiple", function(selector, properties) {
		if (!$(selector).hasClass("multiple-value")) {
			return Core.createObject(new Multiple(properties, $(selector)), selector, true);
		} else {
			return void 0;
		}
	});

    $(document).ready(function() {
		/* Создаем событие на обработку изменения стиля элемента
		select[multiple], которые потом парсим и применяем родительскому
		элементу с классом multiple */
		var f;
        $("select[multiple][data-ignore!='multiple']").multiple().on("style", f = function(e) {
			if (e.target.tagName !== "SELECT") {
				return void 0;
			}
            var filter = $(this).multiple("property", "filter");
			var style;
			if ($(this).attr("style")) {
				style = $(this).attr("style").split(";");
			} else {
				style = [];
			}
            var css = {};
            for (var i in style) {
                var link = style[i].trim().split(":");
                if (link.length != 2) {
                    continue;
                }
                var key = link[0];
				if ($.inArray(key, filter) !== -1) {
					continue;
				}
                css[key] = link[1].trim();
            }
            $(this).parent(".multiple").css(css);
        }).on("hide", function(e) {
			if (e.target.tagName === "SELECT") {
				$(this).parents(".multiple").hide();
			}
		}).on("show", function(e) {
			if (e.target.tagName === "SELECT") {
				$(this).parents(".multiple").show();
			}
		}).trigger("style");
		/* Обходим все элементы, которые уже имеют установленные значения в
		атрибуте value, вытаскиваем их них значения (обычно - массив JSON) и
		добавляем в компонент, после чего удалем поле value */
        $("select[multiple][data-ignore!='multiple'][value!='']").each(function() {
            if ($(this).attr("value") != void 0) {
                $(this).multiple("choose", $(this).attr("value"));
            }
            $(this).removeAttr("value");
        });
		/* Обходим все элементы, которые имеют отмеченные поля через зажатую
		клавишу Ctrl, получаем их и добавляем в компонент, разумеется, учитываем,
		что если массив пустой, то все поля будут удалены */
		$("select[multiple][data-ignore!='multiple']").each(function() {
			var result = $(this).multiple("selected", true);
			if (result.length > 0) {
				$(this).multiple("choose", result);
			}
			// #13553 - Hot! Hot! Hot!
			this.onhide = function(e) {
				if (e.target.tagName === "SELECT") {
					$(this).parents(".multiple").hide();
				}
			};
			this.onshow = function(e) {
				if (e.target.tagName === "SELECT") {
					$(this).parents(".multiple").show();
				}
			};
			this.onstyle = function(e) {
				if (e.target.tagName === "SELECT") {
					f.call(this, e);
				}
			};
		});
		/* Обходим все множественный списки со стилями и применяем их для
		нового родительского элемента */
		$("select[multiple][data-ignore!='multiple'][style]").each(function() {
			$(this).parents(".multiple").attr("style", $(this).attr("style"));
		});
    });

})(Core);

(function($) {
	$.each(['show', 'hide'], function (i, ev) {
		var el = $.fn[ev];
		$.fn[ev] = function() {
			for (var i = 0; i < this.length; i++) {
				if (this[i].tagName == "SELECT") {
					$(this[i]).trigger(ev);
				}
			}
			return el.apply(this, arguments);
		};
	});
})(jQuery);