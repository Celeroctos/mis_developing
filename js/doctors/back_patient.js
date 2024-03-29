var editMedcard = function(cardNumber) {
	$.ajax({
		'url' : globalVariables.baseUrl + '/reception/patient/getmedcarddata',
		'data' : {
			'cardid' : cardNumber
		},
		'cache' : false,
		'dataType' : 'json',
		'type' : 'GET',
		'success' : function(data, textStatus, jqXHR) {
			if(data.success == true) {
				data = data.data.formModel;
				var form = $('#patient-medcard-edit-form');
				for(var i in data) {
					$(form).find('#' + i).val(data[i]);
				}
				$(form).find('#documentGivedate').trigger('change');
				$(form).find('#privilege').trigger('change');
				$('#editMedcardPopup').modal();
			} else {
				$('#errorSearchPopup .modal-body .row p').remove();
				$('#errorSearchPopup .modal-body .row').append('<p>' + data.data + '</p>')
				$('#errorSearchPopup').modal();
			}
		}
	});
};

$(document).ready(function() {
    
    var numCalls = 0; // Одна или две формы вызвались. Делается для того, чтобы не запускать печать два раза
    // Редактирование медкарты
    $("#patient-edit-form").on('success', function(eventObj, ajaxData, status, jqXHR) {
        var ajaxData = $.parseJSON(ajaxData);
        if(ajaxData.success == true) { // Запрос прошёл удачно, закрываем окно для добавления нового кабинета, перезагружаем jqGrid
            $('#successEditPopup').modal({

            });
            if(isThisPrint) {
                if($(".submitEditPatient").length - 1 == numCalls) {
                    // Сбрасываем режим на дефолт
                    isThisPrint = false;
                    numCalls = 0;
                    $('.activeGreeting .print-greeting-link').trigger('print');
                } else {
                    ++numCalls;
                }
            }
            // Вставляем новую запись в список истории
            if(ajaxData.hasOwnProperty('historyDate')) {
                var newDiv = $('<div>');
                $(newDiv).append($('<a>').prop('href', '#' + globalVariables.medcardNumber).attr('class', 'medcard-history-showlink').text(ajaxData.historyDate));
                $('#accordionH .accordion-inner div:first').before(newDiv);
            }
        } else {

        }
    });

    $(".submitEditPatient input").on('click', function() {
        //alert("!");
        //return false;
        
        /* Метод ищет контролы, в которых есть признак обязательного поля
            Для каждого такого контрола 
                1. Проверяем, пустое ли у него значение
                2. Если да
                    3. Добавляем ошибку в поп-ап с ошибками
                    3. Подкрашиваем данный контрол с ошибкой
        */
        var isError = false;
        // Очищаем поп-ап с ошибками
          $('#errorPopup .modal-body .row').html("");
          var controlElements = $($(this).parents('form')[0]).find('div.form-group:not(.submitEditPatient)').has('label span.required');
            for (i=0;i<controlElements.length;i++)
            {
                    // Внутри контейнера с контролом ищу сам контрол
                    var oneControlElement = $(controlElements[i]).find('input[type=text],textarea,select');
                    //console.log(oneControlElement);
                    // Проверим - есть ли данного контрола значение
                    if ($(oneControlElement[0]).val()==''||$(oneControlElement[0]).val()==null) {
                        isError = true;
                        $(oneControlElement[0]).animate({
                                backgroundColor: "rgb(255, 196, 196)"
                        });
                        // В следующий раз, когда значение у контрола поменяется - надо сбросить css-совйсто
                        $(oneControlElement[0]).one('change', function()
                                                    {
                                                        $(this).css('background-color',''); 
                                                    });
                        // И на keydown тоже самое поставим
                        $(oneControlElement[0]).one('keydown', function()
                                                    {
                                                        $(this).css('background-color',''); 
                                                    });
                        // Вытащим метку данного элемента
                        var labelOfControl = ($(controlElements[i]).find('label').text())
                                                .trim();
                        // Если последний символ в строке звёздочка - обрезаем её
                        if (labelOfControl[labelOfControl.length-1]=='*')
                        {
                                labelOfControl = labelOfControl.substring(0,labelOfControl.length-1);                
                        }                        
                        labelOfControl = labelOfControl.trim();
                        // Если последний символ в строке двоеточие - обрезаем его
                        if (labelOfControl[labelOfControl.length-1]==':')
                        {
                                labelOfControl = labelOfControl.substring(0,labelOfControl.length-1);                
                        }
                        // Добавим в поп-ап сообщение из ошибки
                           $('#errorPopup .modal-body .row').append("<p>" +
                                'Поле \"' + labelOfControl + '\" должно быть заполнено'                                    
                                + "</p>")
                    }
            }
            // Если есть ошибки
            if (isError) {
                // Показываем поп-ап с ошибками
                 $('#errorPopup').modal({});
                // Давим событие нажатия клавиши
                return false;
            }
    });
    
    $("#date-cont").on('changeDate', function(e) {
        $('#filterDate').val(e.date.getFullYear() + '-' + (e.date.getMonth() + 1) + '-' + e.date.getDate());
        $('#change-date-form').submit();
    });

    $("#date-cont").trigger("refresh");

    $("#date-cont").on('changeMonth', function(e) {
        $("#date-cont").trigger("refresh", [e.date]);
    });


    $("#date-cont").on('refresh', function(e, date) {
        if(typeof date == 'undefined') {
            var currentDate = $('#filterDate').val();
            var currentDateParts = currentDate.split('-');
        } else {
            var dateObj = new Date(date);
            var currentDateParts = [dateObj.getFullYear(), dateObj.getMonth() + 1, dateObj.getDay() + 1];
        }

        var daysWithPatients = globalVariables.patientsInCalendar;
        for(var i in daysWithPatients) {
            var parts = daysWithPatients[i].patient_day.split('-'); // Год-месяц-день
            if(parseInt(currentDateParts[0]) == parseInt(parts[0]) && parseInt(currentDateParts[1]) == parseInt(parts[1])) {
                $(".day" + parseInt(parts[2])).filter(':not(.new)').filter(':not(.old)').addClass('day-with');
            }
        }
    });
    $('#date-cont').trigger('refresh');

    // Закрытие приёма
    $(document).on('click', '.accept-greeting-link', function(e) {
        console.log(this);
        // Берём id-шник приёма
        var greetingId = $(this).attr('href').substr(1);
        //'/doctors/shedule/acceptcomplete/?id='.$patient['id']
        //console.log(greetingId);
        
        // Дёргаем Ajax
        $.ajax({
            'url' : '/doctors/shedule/acceptcomplete/?id=' + greetingId.toString(),
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                   // Перезагружаем страницу
                   location.reload();
                } else {
                    // Выводим сообщение об ошибке
                     $('#errorPopup .modal-body .row').html("<p>" + data.text + "</p>");
                        $('#errorPopup').modal({

                        });
                }
                console.log(data);
                return;
            }
        });
        
    });
    
    
    $(document).on('click', '.medcard-history-showlink', function(e) {
        $(this).parents('.accordion-inner:eq(0)').find('.active').removeClass('active');
        $(this).parent().addClass('active');
        var medcardId = $(this).attr('href').substr(1);
        var date = $(this).text();
        $('#historyPopup .medcardNumber').text('№ ' + medcardId);
        $('#historyPopup .historyDate').text(date);
        $.ajax({
            'url' : '/doctors/patient/gethistorymedcard',
            'data' : {
                medcardid : medcardId,
                date : date
            },
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                console.log(data);
                if(data.success == 'true') {
                    // Заполняем медкарту-историю значениями
                    var data = data.data;
                   /* var form = $('#historyPopup #patient-edit-form');
                    // Сброс формы
                    $(form)[0].reset();
                    $(form).find('input').val('');
                    for(var i = 0; i < data.length; i++) {
                        var element = $(form).find('#f_history_' + data[i].element_id);
                        if(data[i].type == 3) { // Выпадающий список с множественным выбором
                            data[i].value = $.parseJSON(data[i].value);
                        }
                        element.val(data[i].value);
                    }*/
                    $('#historyPopup .modal-body .row').html(data);
                    $('#historyPopup').modal({

                    });
                }
            }
        });
    });

    $('#historyPopup').on('show.bs.modal', function(e) {
        var deps = filteredDeps;
        for(var i = 0; i < deps.length; i++) {
            var elementValue = $('select[id$="_' + deps[i].elementId + '"]').val();
            changeControlState(deps[i], elementValue, '#historyPopup');
        }
    });

    $('.print-greeting-link').on('click', function(e) {
        $('#noticePopup').modal({});
    });

    var isThisPrint = false;
    // После закрытия окна начинать сохранение медкарты и печать листа приёма
    $('#noticePopup').on('hidden.bs.modal', function(e) {
        isThisPrint = true;

            $('.submitEditPatient input').trigger('click');
    });

    $('#successEditPopup').on('show.bs.modal', function(e) {
        // Если это режим печати, то показывать окно успешности редактирования не надо
        if(isThisPrint) {
            return false;
        }
    });

    $('#successEditPopup').on('hidden.bs.modal', function(e) {
        if(!isThisPrint) {
            $('#printPopup').modal({});
        }
    });

    $('#printPopup .btn-success').on('click', function(e) {
        $('.activeGreeting .print-greeting-link').trigger('print');
        isThisPrint = false;
    });

    // Печать листа приёма, само действие
    $('.print-greeting-link').on('print', function(e) {
        var id = $(this).attr('href').substr(1);
        var printWin = window.open('/doctors/print/printgreeting/?greetingid=' + id,'','width=800,height=600,menubar=no,location=no,resizable=no,scrollbars=yes,status=no');
        printWin.focus();
        return false;
    });

    // Сохранение диагнозов
    $('#submitDiagnosis').on('click', function(e) {
        var choosedPrimary = $.fn['primaryDiagnosisChooser'].getChoosed();
        var choosedSecondary = $.fn['secondaryDiagnosisChooser'].getChoosed();

        var primaryIds = [];
        var secondaryIds = [];
        for(var i = 0; i < choosedPrimary.length; i++) {
            primaryIds.push(choosedPrimary[i].id);
        }
        for(var i = 0; i < choosedSecondary.length; i++) {
            secondaryIds.push(choosedSecondary[i].id);
        }

        $.ajax({
            'url' : '/doctors/patient/savediagnosis',
            'data' : {
                'primary' : $.toJSON(primaryIds),
                'secondary' : $.toJSON(secondaryIds),
                'note' : $('#diagnosisNote').val(),
                'greeting_id' : $('#greetingId').val()
            },
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    $('#successDiagnosisPopup').modal({});
                }
            }
        });
    });

    globalVariables.onlyLikes = 0;
    // Флаг любимых и общих диагнозов
    $('#onlyLikeDiagnosis').click(function(e) {
        if(!$(this).prop('checked')) {
            globalVariables.onlyLikes = 0;
        } else {
            globalVariables.onlyLikes = 1;
        }
    });


    // Это для того, чтобы занести в диагнозы всё то, что было при загрузке страницы: первичные
    (function(choosers) {
        for(var j = 0; j < choosers.length; j++) {
            var chooser = $('#' + choosers[j]);
            if($(chooser).length > 0) {
                var preChoosed = $(chooser).find('.choosed span.item');
                for(var i = 0; i < preChoosed.length; i++) {
                    var id = $(preChoosed[i]).prop('id').substr(1);
                    $.fn[choosers[j]].addChoosed($('<li>').prop('id', 'r' + id).text($(preChoosed[i]).find('span').html()), {
                        'id' : id,
                        'description' : $(preChoosed[i]).find('span').html()
                    }, 1);
                }
            }
        }
    })(['primaryDiagnosisChooser', 'secondaryDiagnosisChooser']);

    // Просмотр медкарты в попапе
    $(document).on('click', '.editMedcard', function() {
		editMedcard($(this).prop('href').substr($(this).prop('href').lastIndexOf('#') + 1));
    });

    // Запрет редактирования карты
    $('#patient-medcard-edit-form .modal-body').find('input, select, button').prop('disabled', true);
    $('#patient-medcard-edit-form .date-control .input-group-addon').remove();

    // Здесь будут храниться ID клонов элементов
    var clones = {

    };

    // Клонирование элементов
    /* Клоны считаются, как clone_xx_yy, где xx - ID аккордеона, yy - порядковый номер клона */
    $(document).on('click', '.accordion-clone-btn', function(e) {
        var prKey = $(this).find('span.pr-key').text();
        var accParent = $(this).parents('.accordion')[0];
        var accClone = $(accParent).clone();
        // Теперь нужно отклонировать элемент. Для этого мы подадим запрос, результатом которого станет категория (кусок дерева)
        $.ajax({
            'url' : '/doctors/patient/cloneelement',
            'data' : {
                'pr_key' : prKey
            },
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    var toggle = $(accParent).find('.accordion-toggle');
                    var body = $(accParent).find('.accordion-body');

                    if(!clones.hasOwnProperty($(accParent).prop('id'))) {
                        clones[$(accParent).prop('id')] = 1;
                    } else {
                        clones[$(accParent).prop('id')]++;
                    }

                    var accId = $(accParent).prop('id');
                    var accNumberId = accId.substr(accId.lastIndexOf('_') + 1);
                    var idCloneCount = clones[$(accParent).prop('id')];

                    var toggleDataParent = $(toggle).data()['parent'];
                    var toggleDataHref = $(toggle).prop('href');
                    $(accClone).find('.accordion-clone-btn:eq(0)')
                        .removeClass('accordion-clone-btn')
                        .addClass('accordion-unclone-btn')
                        .find('span.glyphicon-plus')
                        .removeClass('glyphicon-plus')
                        .addClass('glyphicon-minus');

                    $(accClone).find('.accordion-heading button:not(:eq(0))').remove();

                    $(accClone).prop('id', $(accParent).prop('id') + '_clone_' + accNumberId + '_' + idCloneCount);
                    $(accClone).find('.accordion-body:eq(0)').prop('id', 'collapse_clone_' + accNumberId + '_' + idCloneCount);
                    $(accClone).find('.accordion-heading:eq(0)').attr('data-parent', $(accParent).prop('id') + '_clone_' + accNumberId + '_' + idCloneCount);
                    $(accClone).find('.accordion-heading:eq(0) a').attr('href', '#collapse_clone_' + accNumberId + '_' + idCloneCount);

                    // Дальше пробегаемся по всем вложенным в дерево элементам. Ситуация повторяется: переименовываем
                    var inserts = $(accClone).find('.accordion');
                    for(var i = 0; i < inserts.length; i++) {
                        if(!clones.hasOwnProperty($(inserts[i]).prop('id'))) {
                            clones[$(inserts[i]).prop('id')] = 1;
                        } else {
                            clones[$(inserts[i]).prop('id')]++;
                        }

                        accId = $(inserts[i]).prop('id');
                        accNumberId = accId.substr(accId.lastIndexOf('_') + 1);
                        idCloneCount = clones[$(inserts[i]).prop('id')];

                        $(inserts[i]).prop('id', $(inserts[i]).prop('id') + '_clone_' + accNumberId + '_' + idCloneCount);
                        $(inserts[i]).find('.accordion-body:eq(0)').prop('id', 'collapse_clone_' + accNumberId + '_' + idCloneCount);
                        $(inserts[i]).find('.accordion-heading:eq(0)').attr('data-parent', $(accParent).prop('id') + '_clone_' + accNumberId + '_' + idCloneCount);
                        $(inserts[i]).find('.accordion-heading:eq(0) a').attr('href', '#collapse_clone_' + accNumberId + '_' + idCloneCount);
                    }

                    // Ставим кнопке пришедший первичный ключ
                    $(accClone).find('span.pr-key').text(data.data.pk_key);

                    $(accClone).insertAfter($(accParent));

                    // Теперь переименуем все элементы, согласно изменённым путям
                    var repath = data.data.repath;
                    for(var i in repath) {
                        var undottedPathBefore = i.split('.').join('|');
                        var undottedPathAfter = repath[i].split('.').join('|');
                        // Здесь большое TODO
                        var control = $(accClone).find('[id*="_' + undottedPathBefore + '_"]');
                        if(control.length > 0) {
                            var controlId = $(control).prop('id');
                            var substrFirst = controlId.substr(controlId.lastIndexOf('_'));
                            var tempSubstr = controlId.substr(0, controlId.lastIndexOf('_'));
                            var substrSecond = tempSubstr.substr(0, tempSubstr.lastIndexOf('_') + 1);
                            $(control).prop('id', substrSecond + undottedPathAfter + substrFirst);
                        }
                    }

                    // Теперь надо разобрать зависимость
                    var deps = data.data.dependences;
                    
                    for(var i = 0; i < deps.length; i++) {
                        // По этому пути вынимаем контрол
                        var undottedPath = deps[i].path.split('.').join('|');
                        if(deps[i].dependences.list.length > 0) {
                            filteredDeps.push(deps[i]);
                            (function(select, dep) {
                                $(document).on('change', select, function(e) {
                                    var elementValue = $(select).val();
                                    changeControlState(dep, elementValue, $(select).parents('.accordion:eq(0)'));
                                });
                                $(select).trigger('change');
                            })($('select[id*="_' + undottedPath + '_"]'), deps[i]);
                        }
                    }
                } else {
                    return;
                }
            }
        });
    });

    // UnКлонирование элементов
    $(document).on('click', '.accordion-unclone-btn', function(e) {
        var accParent = $(this).parents('.accordion')[0];
        var prKey = $(this).find('span.pr-key').text();
        $.ajax({
            'url' : '/doctors/patient/uncloneelement',
            'data' : {
                'pr_key' : prKey
            },
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    $(accParent).remove();
                }
            }
        });
    });
    var filteredDeps = [];
    // Зависимости: дефолтные значения
    function checkElementsDependences() {
        if(globalVariables.hasOwnProperty('elementsDependences')) {
            var deps = globalVariables.elementsDependences;
            for(var i = 0; i < deps.length; i++) {
                // По этому пути вынимаем контрол
                var undottedPath = deps[i].path.split('.').join('|');
                if(deps[i].dependences.list.length > 0) {
                    filteredDeps.push(deps[i]);
                    (function(select, dep) {
                        $(select).on('change', function(e) {
                            var elementValue = $(select).val();
                            changeControlState(dep, elementValue, $(select).parents('.accordion:eq(0)'));
                        });
                        $(select).trigger('change');

                    })($('select[id*="_' + undottedPath + '_"]'), deps[i]);
                }
            }
        }
    }
    checkElementsDependences();

    function changeControlState(dep, elementValue, container) {
        for(var j = 0; j < dep.dependences.list.length; j++) {
            if(dep.dependences.list[j].value == elementValue) {
                if(dep.dependences.list[j].action == 1) { // Это "скрыть"
                    if(typeof container == 'undefined') {
                        $('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').hide();
                    } else {
                        $(container).find('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').hide();
                    }
                } else if(dep.dependences.list[j].action == 2) { // Это "показать"
                    if(typeof container == 'undefined') {
                        $('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').show();
                    } else {
                        $(container).find('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').show();
                    }
                }
                // Если значение совпало - то выходим из цикла
                //break;
            }  else {
                // Противоположное действие экшену по дефолту
                if(dep.dependences.list[j].action == 1) { // Это "скрыть"
                    if(typeof container == 'undefined') {
                        $('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').show();
                    } else {
                        $(container).find('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').show();
                    }
                } else if(dep.dependences.list[j].action == 2) { // Это "показать"
                    if(typeof container == 'undefined') {
                        $('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').hide();
                    } else {
                        $(container).find('[id$="_' + dep.dependences.list[j].elementId + '"]').parents('.form-group').hide();
                    }
                }
            }
        }
    }

    $('#templates-choose-form input[type="submit"]').on('click', function(e) {
        var checkboxes = $(this).parents('form').find('input[type="checkbox"]');
        $(this).attr('disabled', true);
        for(var i = 0; i < checkboxes.length; i++) {
            if($(checkboxes[i]).prop('checked')) {
                $(this).attr('value', 'Подождите, приём начинается...');
                $('#templates-choose-form').submit();
                return;
            }
        }
        alert('Вы не выбрали ни одного шаблона для приёма!');
        $(this).attr('disabled', false);
        return false;
    });
});

function getOnlyLikes() {
    return globalVariables.onlyLikes;
}
