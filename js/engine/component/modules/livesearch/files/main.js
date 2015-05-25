misEngine.class('component.livesearch', function() {
    return {
        config: {
            name: 'component.livesearch',
            id: null,
            extraparams: [],
            hideEmpty: false,
            placeholderText: '',
            labelText: '',
            canAddValue: false,
            bindedWindowSelector: false,
            beforeWindowShow: function () {
            },
            afterWindowShow: function () {
            },
            displayFunc: {},
            movingFunc: {},
            moving: 0,
            container : null
        },

        choosedElements: [],
        currentElements: [],
        lastUserInput: '',
        current: null,
        mode: 0,
        prevVal: null,
        numRecords: 0,

        inputField: null,
        choosed: null,
        variants: null,
        addValueBtn: null,
        template : null,

        getChoosed: function () {
            return this.choosedElements;
        },

        addChoosed: function (li, rowData, withOutInsert) {
            this.currentElements.push(rowData);
            this.addVariantToChoosed(li, withOutInsert);
        },

        addExtraParam: function (key, value) {
            this.config.extraparams[key] = value;
        },

        deleteExtraParam: function (key) {
            if (this.config.extraparams.hasOwnProperty(key)) {
                delete this.config.extraparams[key];
            }
        },

        getLastUserInput: function () {
            return this.lastUserInput;
        },

        clearAll: function () {
            this.current = null;
            this.choosedElements = [];
            $(chooser).find('.choosed span').remove(); // TODO
            this.inputField.prop('disabled', false);
            if (this.config.hideEmpty) {
                this.choosed.css('display', 'none');
            }
        },

        disable: function () {
            this.inputField.prop('disabled', true);
            if (this.config.canAddValue) {
                $(this.addValueBtn).off('click').css('cursor', 'default');
            }
        },

        enable: function () {
            this.inputField.prop('disabled', false);
            if (this.config.canAddValue) {
                $(this.addValueBtn).css('cursor', 'pointer').on('click', function () {
                    if (this.config.bindedWindowSelector && $(this.config.bindedWindowSelector).length > 0) {
                        if (this.config.beforeWindowShow && typeof this.config.beforeWindowShow == 'function') {
                            this.config.beforeWindowShow(function () {
                                $(this.config.bindedWindowSelector).modal({});
                            });
                        } else {
                            $(this.config.bindedWindowSelector).modal({});
                            if (this.config.afterWindowShow && typeof this.config.afterWindowShow == 'function') {
                                this.config.afterWindowShow();
                            }
                        }
                    }
                });
            }
        },

        addConfigParam: function (param, value) {
            this.config[param] = value;
        },

        makeTemplate: function () {
            if(!this.config.container) {
                misEngine.t('Not exists container for element');
                return false;
            }

            var inputTemplate = $('<input>').prop({
                'type': 'text',
                'class': 'form-control',
                'placeholder': this.config.placeholderText
            });

            this.inputField = inputTemplate;

            if (this.config.canAddValue) {
                inputTemplate = $('<div>').prop({
                    'class': 'input-group'
                }).append(
                    inputTemplate,
                    this.addValueBtn = $('<span>').prop({
                        'class': 'input-group-addon glyphicon glyphicon-plus',
                        'style': 'cursor : default'
                    })
                );
            }

            return $(this.config.container).append(
                $('<div>').prop({
                    'class': 'form-group chooser'
                }).append(
                    $('<label>').prop({
                        'class': 'col-xs-4 control-label'
                    }).text(this.config.label),
                    $('<div>').prop({
                        'class': 'col-xs-6'
                    }).append(
                        inputTemplate,
                        this.variants = $('<ul>').prop({
                            'class': 'variants no-display'
                        }),
                        this.choosed = $('<div>').prop({
                            'class': 'choosed'
                        })
                    )
                )
            );
        },

        bindHandlers: function () {
            this.inputField.val('').on('keydown', $.proxy(function (e) {
                // Arrow Up
                if ($.trim($(e.target).val() != '')) {
                    if (e.keyCode == 38) {
                        this.variants.find('li.active').removeClass('active');
                        if (current == null) {
                            this.variants.find('li:not(.navigation):last').addClass('active');
                            this.current = this.variants.find('li:not(.navigation)').length - 1;
                        } else {
                            if (this.current == 0) {
                                this.current = this.variants.find('li:not(.navigation)').length - 1;
                            } else {
                                --this.current;
                            }
                        }
                        this.mode = 0;
                        this.variants.find('li:eq(' + this.current + ')').addClass('active');
                        if (this.config.hasOwnProperty('displayFunc') && typeof this.config.displayFunc == 'function') {
                            var toDisplay = this.config.displayFunc(this.currentElements[this.current]);
                            //$(chooser).find('input').val(toDisplay);
                        } else {
                            //$(chooser).find('input').val($(chooser).find('.variants li.active').text());
                        }
                    }
                    // Стрелка "Вниз"
                    if (e.keyCode == 40) {
                        this.variants.find('li.active').removeClass('active');
                        if (this.current == null) {
                            this.variants.find('li:first').addClass('active');
                            this.current = 0;
                        } else {
                            if (this.current == this.variants.find('li:not(.navigation)').length - 1) {
                                this.current = 0;
                            } else {
                                ++this.current;
                            }
                        }
                        this.mode = 0;
                        this.variants.find('li:eq(' + this.current + ')').addClass('active');
                        if (this.config.hasOwnProperty('displayFunc') && typeof this.config.displayFunc == 'function') {
                            var toDisplay = this.config.displayFunc(this.currentElements[this.current]);
                            //$(chooser).find('input').val(toDisplay);
                        } else {
                            //$(chooser).find('input').val($(chooser).find('.variants li.active').text());
                        }
                    }

                    // Стрелка влево
                    if (e.keyCode == 37) {
                        this.navPrev();
                        this.searchByField(this.inputField, 1);
                    }
                    // Стрелка вправо
                    if (e.keyCode == 39) {
                        this.navNext();
                        this.searchByField(this.inputField, 1);
                    }

                    // Нажатие Enter переносит в список выбранных
                    if (e.keyCode == 13) {
                        // Если в поле ничего не вбито - переходим в другой контрол
                        wasChangedFocus = false;
                        if ($.trim(this.inputField).val() == '') {
                            wasChangedFocus = true;
                            $.fn.switchFocusToNext();
                        }
                        if (this.current != null) {
                            // Смотрим - если текущая длина больше
                            if (this.config.hasOwnProperty('maxChoosed')) {
                                if (this.getChoosed().length + 1 >= parseInt(this.config.maxChoosed)) {
                                    $.fn.switchFocusToNext();
                                }
                            }
                            this.addVariantToChoosed(this.variants.find('li.active'));
                            this.current = null;
                        } else {
                            // Переводим фокус на следующий элемент
                            if (!wasChangedFocus) {
                                $.fn.switchFocusToNext();
                            }
                        }

                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }

                    // Нажатие бекспейса на последнем символе закроет список
                    if (e.keyCode == 8) {
                        if ($.trim(this.inputField.val()).length == 1) {
                            $(this.variants).hide();
                        }
                    }
                }
            }, this));


            $(this.template).on('click', '.navigation .prev', function(e) {
                this.initPageParam();
                this.navPrev();
                this.searchByField(this.inputField, 1);
                e.stopPropagation();
                return false;
            });

            $(this.template).on('click', '.navigation .next', function(e) {
                this.initPageParam();
                this.navNext();
                this.searchByField(this.inputField, 1);
                e.stopPropagation();
                return false;
            });

            $(this.inputField).on('keyup', $.proxy(function(e) {
                // Нажатие бекспейса
                if(!($(e.target).val().length == 1 && e.keyCode == 8)) {
                    if(e.keyCode != 37 && e.keyCode != 39) {
                        this.initPageParam(1);
                    }
                    this.searchByField(this.inputField);
                }
                if($(e.target).val().length >= 1) {
                    this.mode = 1;
                    if(e.keyCode != 37 && e.keyCode != 39) {
                        this.initPageParam(1);
                    }
                    this.searchByField(this.inputField);
                }
            }, this));

            $(this.addValueBtn).on('click', function(e) {
                if(typeof this.config.bindedWindowSelector != 'undefined' && $(this.config.bindedWindowSelector).length > 0) {
                    if(typeof this.config.beforeWindowShow != 'undefined') {
                        this.config.beforeWindowShow(function() {
                            $(this.config.bindedWindowSelector).modal({});
                        });
                    } else {
                        $(this.config.bindedWindowSelector).modal({});
                        if(typeof this.config.afterWindowShow != 'undefined') {
                            this.config.afterWindowShow();
                        }
                    }
                }
            });

            $(this.variants).on('click', 'span.item span.glyphicon-remove', $.proxy(function(e) {
                // Удаляем из массива предыдущих элементов
                for(var i = 0; i < this.choosedElements.length; i++) {
                    if('r' + $(this.choosedElements[i]).prop('id') == $(this).parent().prop('id')) {
                        this.choosedElements = this.choosedElements.slice(0, i).concat(this.choosedElements.slice(i + 1));
                        break;
                    }
                }
                $(this).parent().remove();
                this.enable();
                if(this.config.hasOwnProperty('afterRemove') && typeof this.config.afterRemove == 'function') {
                    this.config.afterRemove();
                }
                if(this.config.hasOwnProperty('hideEmpty') && this.config.hideEmpty) {
                    if($.fn[$(chooser).attr('id')].getChoosed().length == 0) {
                        $(this.variants).css('display', 'none');
                    } else {
                        $(this.variants).css('display', 'block');
                    }
                }
            }, this));

            $(this.variants).on('click', 'span.item span.glyphicon-arrow-down', $.proxy(function(e) {
                for(var i = 0; i < this.choosedElements.length; i++) {
                    if('r' + $(this.choosedElements[i]).prop('id') == $(this).parent().prop('id')) {
                        // Размножаем это на все контролы, которые описаны в moving
                        var moving = this.config.moving;
                        if(typeof moving != 'undefined') {
                            for(var j = 0; j < moving.length; j++) {
                                $.fn[moving[j]].addChoosed(this.config.movingFunc(choosedElements[i]), choosedElements[i]);
                            }
                            break;
                        }
                    }
                }
            }, this));


            $(this.template).on('click', '.variants li:not(.navigation)', $.proxy(function(e) {
                this.addVariantToChoosed(this);
            }, this));

            function addVariantToChoosed(li, withOutInsert) {
                $(li).parents('ul').hide();
                var id = $(li).prop('id').substr(1);
                var primaryField = choosersConfig[$(chooser).prop('id')].primary;
                for(var i = 0; i < currentElements.length; i++) {
                    if(currentElements[i][primaryField] == id) {
                        // Смотрим, нет ли уже такого элемента в списке. Если есть - добавлять не надо в список выбранных
                        var isFound = false;
                        var foundElement = null;
                        for(var j = 0; j < choosedElements.length; j++) {
                            if(currentElements[i][primaryField] == choosedElements[j][primaryField]) {
                                isFound = true;
                                break;
                            }
                        }
                        // А если найден - повторно добавлять не надо
                        if(!isFound) {
                            lastUserInput = $(chooser).find('input').val();
                            if(withOutInsert != 1) {
                                var span = $('<span>').addClass('item');
                                // Возможность копирования в соседние chooser-ы
                                if(choosersConfig[$(chooser).prop('id')].hasOwnProperty('moving') && choosersConfig[$(chooser).prop('id')]['moving'].length > 0) {
                                    // Посмотрим, все ли элементы есть в наличии
                                    var moving = choosersConfig[$(chooser).prop('id')]['moving'];
                                    var isAllowForMoving = true;
                                    for(var j = 0; j < moving.length; j++) {
                                        if($('#' + moving[j]).length == 0) {
                                            isAllowForMoving = false;
                                            break;
                                        }
                                    }
                                    if(isAllowForMoving) {
                                        var innerSpan = $('<span>').addClass('glyphicon glyphicon-arrow-down');
                                    } else {
                                        var innerSpan = $('<span>').addClass('glyphicon glyphicon-remove');
                                    }
                                } else {
                                    var innerSpan = $('<span>').addClass('glyphicon glyphicon-remove');
                                }
                                $(span).append($(li).text()).append(innerSpan);
                                $(span).prop('id', 'r' + currentElements[i][primaryField]);
                                $(chooser).find('.choosed').append(span);
                            }
                            $(chooser).find('input').val('');
                            prevVal = null;
                            choosedElements.push(currentElements[i]);
                            /* Логика работы: если есть настройка о количестве добавляемых максмально вариантов, то нужно блокировать строку, если количество вариантов достигло максимума */
                            if(choosersConfig[$(chooser).prop('id')].hasOwnProperty('maxChoosed') && choosedElements.length >= choosersConfig[$(chooser).prop('id')].maxChoosed) {

                                // А вот теперь со спокойной совестью блокируем чюзер
                                $.fn[$(chooser).attr('id')].disable();
                            }
                            if(choosersConfig[$(chooser).prop('id')].hasOwnProperty('hideEmpty') && choosersConfig[$(chooser).prop('id')].hideEmpty) {
                                $(chooser).find('.choosed').css('display', 'block');
                            }
                            if(choosersConfig[$(chooser).prop('id')].hasOwnProperty('afterInsert') && typeof choosersConfig[$(chooser).prop('id')].afterInsert == 'function') {
                                choosersConfig[$(chooser).prop('id')].afterInsert(chooser);
                            }
                        } else {
                            // TODO: сделать анимацию на вариант, который уже есть в списке, чтобы показать, что он есть
                        }
                        break;
                    }
                }
            }

        },

        addVariantToChoosed : function(li, withOutInsert) {
            $(li).parents('ul').hide();
            var id = $(li).prop('id').substr(1);
            var primaryField = this.config.primary;
            for(var i = 0; i < this.currentElements.length; i++) {
                if(this.currentElements[i][primaryField] == id) {
                    // Смотрим, нет ли уже такого элемента в списке. Если есть - добавлять не надо в список выбранных
                    var isFound = false;
                    var foundElement = null;
                    for(var j = 0; j < this.choosedElements.length; j++) {
                        if(this.currentElements[i][primaryField] == this.choosedElements[j][primaryField]) {
                            isFound = true;
                            break;
                        }
                    }
                    // А если найден - повторно добавлять не надо
                    if(!isFound) {
                        this.lastUserInput = $(this.inputField).val();
                        if(withOutInsert != 1) {
                            var span = $('<span>').addClass('item');
                            // Возможность копирования в соседние chooser-ы
                            if(this.config.hasOwnProperty('moving') && this.config['moving'].length > 0) {
                                // Посмотрим, все ли элементы есть в наличии
                                var moving = this.config['moving'];
                                var isAllowForMoving = true;
                                for(var j = 0; j < moving.length; j++) {
                                    if($('#' + moving[j]).length == 0) {
                                        isAllowForMoving = false;
                                        break;
                                    }
                                }
                                if(isAllowForMoving) {
                                    var innerSpan = $('<span>').addClass('glyphicon glyphicon-arrow-down');
                                } else {
                                    var innerSpan = $('<span>').addClass('glyphicon glyphicon-remove');
                                }
                            } else {
                                var innerSpan = $('<span>').addClass('glyphicon glyphicon-remove');
                            }
                            $(span).append($(li).text()).append(innerSpan);
                            $(span).prop('id', 'r' + currentElements[i][primaryField]);
                            $(this.variants).append(span);
                        }
                        $(this.inputField).val('');
                        this.prevVal = null;
                        this.choosedElements.push(this.currentElements[i]);
                        /* Логика работы: если есть настройка о количестве добавляемых максмально вариантов, то нужно блокировать строку, если количество вариантов достигло максимума */
                        if(this.config.hasOwnProperty('maxChoosed') && this.choosedElements.length >= this.config.maxChoosed) {

                            // А вот теперь со спокойной совестью блокируем чюзер
                            this.disable();
                        }
                        if(this.config.hasOwnProperty('hideEmpty') && this.config.hideEmpty) {
                            $(this.variants).css('display', 'block');
                        }
                        if(this.config.hasOwnProperty('afterInsert') && typeof this.config.afterInsert == 'function') {
                            this.config.afterInsert(this);
                        }
                    } else {
                        // TODO: сделать анимацию на вариант, который уже есть в списке, чтобы показать, что он есть
                    }
                    break;
                }
            }
        },

        searchByField : function(field, isNavigation) {
            // Смотрим, введено ли что-то в поле по сравнению с тем, что было. Если да - делаем запрос
            if($.trim($(field).val()) != '') {
                if(this.mode == 0) {
                    this.mode = 1;
                    return false;
                }

                // Переводим, если надо, на другой язык, если стоит опция
                if(this.config.hasOwnProperty('alwaysLanguage')) {
                    var fieldValue = $.trim(changeLanguage($(field).val().toLowerCase(), this.config.alwaysLanguage));
                } else {
                    var fieldValue = $.trim($(field).val().toLowerCase());
                }

                if(this.prevVal != $.trim($(field).val()) || isNavigation == 1) {
                    if($(field).val().length > 0) {
                        this.prevVal = $.trim($(field).val());
                    }
                    // Делаем запрос на сторону сервера
                    var url = this.config.url;
                    this.config.filters.rules[0].data = fieldValue;
                    var urlFilters = this.config.filters;
                    var urlJSON = $.toJSON(urlFilters);
                    url += urlJSON;
                    if(this.config.hasOwnProperty('extraparams')) {
                        var extra = $.extend({}, this.config.extraparams);
                        for(var i in extra) {
                            if(typeof extra[i] == 'function') {
                                extra[i] = extra[i]();
                            }
                        }
                    } else {
                        var extra = {};
                    }
                    $(field).css('display', 'inline');
                    var _field = field;

                    var ajaxGif = misEngine.create('component.ajaxloader', {
                        width: 16,
                        height : 16
                    }).generate();

                    $(ajaxGif).css({
                        'position' : 'absolute',
                        'left' : '100%',
                        'top' : '5px'
                    }).insertAfter($(field));

                    $.ajax({
                        'url' : url,
                        'cache' : false,
                        'dataType' : 'json',
                        'data' : extra,
                        'type' : 'GET',
                        'success' : $.proxy(function(data, textStatus, jqXHR) {
                            if(data.success == 'true' || data.success == true) {
                                var rows = data.rows;

                                $(this.template).find('li').remove();

                                this.current = null;
                                this.numRecords = data.records;

                                if(rows.length == 0 || $.trim($(this.inputField).val()) == '') {
                                    $(this.variants).hide();
                                } else {
                                    this.currentElements = [];
                                    // Берём обработчик, чтобы проверить его на непустоту в цикле
                                    for(var i = 0; i < rows.length; i++) {
                                        if (this.config.rowAddHandler) {
                                            this.config.rowAddHandler(this.variants, rows[i]);
                                        }
                                        var field = this.config.primary;
                                        $(this.variants).find('li:eq(' + i + ')').prop('id', 'r' + rows[i][field]);
                                        this.currentElements.push(rows[i]);
                                    }
                                    if(data.records > rows.length) {
                                        $(this.variants).append($('<li>').prop({
                                            'class' : 'navigation'
                                        }).append(
                                            $('<a>').prop({
                                                'href' : '#',
                                                'class' : 'prev'
                                            }).append($('<span>').prop({
                                                'class' : 'glyphicon glyphicon-arrow-left'
                                            })),
                                            $('<a>').prop({
                                                'href' : '#',
                                                'class' : 'next'
                                            }).append($('<span>').prop({
                                                'class' : 'glyphicon glyphicon-arrow-right'
                                            }))
                                        ));
                                    }
                                    $(this.variants).show();
                                }

                                $(_field).focus();

                                $(ajaxGif).remove();
                                // Вызываем обработчик завершения загрузки, если он указан в конфигурации
                                if (this.config.loadCompleteHandler)  {
                                    this.config.loadCompleteHandler(this.config.id);
                                }
                            }
                        }, this)
                    });
                }
            }
        },

        initPageParam: function (reset) {
            if (typeof this.config.extraparams == 'undefined') {
                this.config.extraparams = {};
            }
            if (typeof this.config.extraparams.page == 'undefined' || reset == 1) {
                this.config.extraparams.page = 1;
            }
        },

        navPrev: function () {
            if (this.config.extraparams.page == 1) {
                this.config.extraparams.page = Math.ceil(numRecords / 10);
            } else {
                this.config.extraparams.page--;
            }
        },

        navNext: function () {
            if (this.config.extraparams.page == Math.ceil(numRecords / 10)) {
                this.config.extraparams.page = 1;
            } else {
                this.config.extraparams.page++;
            }
        },

        // Перевод с английского на русский и с русского на английский
        changeLanguage : function(fieldValue, language) {
            var replacer = null;
            if($.trim(language) == 'ru') {
                replacer = {
                    "q":"й", "w":"ц"  , "e":"у" , "r":"к" , "t":"е", "y":"н", "u":"г",
                    "i":"ш", "o":"щ", "p":"з" , "[":"х" , "]":"ъ", "a":"ф", "s":"ы",
                    "d":"в" , "f":"а"  , "g":"п" , "h":"р" , "j":"о", "k":"л", "l":"д",
                    ";":"ж" , "'":"э"  , "z":"я", "x":"ч", "c":"с", "v":"м", "b":"и",
                    "n":"т" , "m":"ь"  , ",":"б" , ".":"ю"
                };
            }
            if($.trim(language) == 'en') {
                replacer = {
                    "й":"q", "ц":"w"  ,"у":"e" , "к":"r" , "е":"t", "н":"y", "г":"u",
                    "ш":"i", "щ":"o", "з":"p" , "х":"[" , "ъ":"]", "ф":"a", "ы":"s",
                    "в":"d" , "а":"f"  , "п":"g" , "р":"h" , "о":"j", "л":"k", "д":"l",
                    "ж":";" , "э":"'"  , "я":"z", "ч":"x", "с":"c", "м":"v", "и":"b",
                    "т":"n" , "ь":"m"  , "б":"," , "ю":"."
                };
            }
            for(var i = 0; i < fieldValue.length; i++){
                if(replacer[fieldValue[i].toLowerCase()] != undefined){
                    if(fieldValue[i] == fieldValue[i].toLowerCase()){
                        replace = replacer[fieldValue[i].toLowerCase()];
                    } else if(fieldValue[i] == fieldValue[i].toUpperCase()){
                        replace = replacer[fieldValue[i].toLowerCase()].toUpperCase();
                    }

                    fieldValue = fieldValue.replace(fieldValue[i], replace);
                }
            }
            return fieldValue;
        },


        init: function (config) {
            if (this.config) {
                this.setConfig(config);
            }
            this.template = this.makeTemplate();
            this.bindHandlers();
            return this;
        }
    };
});