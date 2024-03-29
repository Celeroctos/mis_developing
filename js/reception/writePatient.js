$(document).ready(function() {
    
    $('#greetingDate').val((new Date).getFullYear() + '-' + ((new Date).getMonth() + 1) + '-' + (new Date).getDate());
    $('#greetingDate').trigger('change');

    if($('#diagnosisDistribChooser').length > 0) {
        $.fn['diagnosisDistribChooser'].addExtraParam('medworkerid', -1); // Типа, флаг "все диагнозы"
    }
    // Инициализируем пагинацию для списков
    InitPaginationList('searchWithCardResult','oms_number','desc',updatePatientsList);
    InitPaginationList('searchDoctorsResult','d.middle_name','desc',updateDoctorsList);
    // Поиск пациента
    $('#patient-search-submit').click(function(e) {
		$(this).trigger("begin")
        updatePatientsList();
        return false;

    });

    function getPatientsFilter() {
		/* ticket 10333:
			Если задан только один из этих параметров, то пользователю выдавать сообщение:
			"Недостаточно параметров для поиска" 
		*/
		var counter = 0;
		var check = [
			$.trim($('#docnumber').val()),
			$.trim($('#serie').val())
		].forEach(function(element) {
			if(element != '') {
				counter++;
			}
		});
		if(counter == 1) {
			$('#errorSearchPopup .modal-body .row p').remove();
			$('#errorSearchPopup .modal-body .row').append('<p class="errorText">Недостаточно параметров для поиска!</p>');
			$('#errorSearchPopup').modal({});
			return false;
		}
		
        var Result = {
            'groupOp' : 'AND',
            'rules' : [			
                {
                    'field' : 'oms_number',
                    'op' : 'eq',
                    'data' : $.trim($('#omsNumber').length) > 0 ? $.trim($('#omsNumber').val()) : ''
                },
                {
                    'field' : 'first_name',
                    'op' : 'eq',
                    'data' : $.trim($('#firstName').length) > 0 ? $.trim($('#firstName').val().toUpperCase()) : ''
                },
                {
                    'field' : 'middle_name',
                    'op' : 'eq',
                    'data' :  $.trim($('#middleName').length) > 0 ? $.trim($('#middleName').val().toUpperCase()) : ''
                },
                {
                    'field' : 'last_name',
                    'op' : 'eq',
                    'data' : $.trim($('#lastName').length > 0) ? $.trim($('#lastName').val().toUpperCase()) : ''
                },
                {
                    'field' : 'address_reg_str',
                    'op' : 'cn',
                    'data' : $.trim($('#addressReg').val())
                },
                {
                    'field' : 'address_str',
                    'op': 'cn',
                    'data' : $.trim($('#address').val())
                },
                {
                    'field' : 'card_number',
                    'op' : 'bw',
                    'data' : $.trim($('#cardNumber').val())
                },
                {
                    'field' : 'serie',
                    'op' : 'eq',
                    'data' : $.trim($('#serie').val())
                },
                {
                    'field' : 'docnumber',
                    'op' : 'eq',
                    'data' : $.trim($('#docnumber').val())
                },
                {
                    'field' : 'snils',
                    'op' : 'eq',
                    'data' : $.trim($('#snils').val())
                },
                {
                    'field' : 'birthday',
                    'op' : 'eq',
                    'data' : $('#birthday2').val()
                }
            ]
        };
		if ($('#enterpriseId').val()!=-1){
			Result.rules.push({
				'field' : 'enterprise_id',
				'op' : 'eq',
				'data' : $('#enterpriseId').val() 
			});
		}
        console.debug('filter',Result);
        return Result;
    }
    
    function getDoctorsFilter() {
        var choosed = $.fn['diagnosisDistribChooser'].getChoosed();
        var choosedDiagnosis = [];
        for(var i = 0; i < choosed.length; i++) {
            choosedDiagnosis.push(choosed[i].id);
        }
        // Смотрим на ФИО
        var fio = $('#fio').val();
        var parts = fio.split(' '); // По пробелу. ФИО = Ф_И_О
        var fioFields = [];
        for(var i = 0; i < parts.length; i++) {
            if($.trim(parts[i]) != '') {
                fioFields.push(parts[i]);
            }
        }

        var rules = [
            {
                'field' : 'ward_code',
                'op' : 'eq',
                'data' :  $('#ward').val()
            },
            {
                'field' : 'post_id',
                'op' : 'eq',
                'data' : $('#post').val()
            },

			{
                'field' : 'greeting_type',
                'op' : 'eq',
                'data' : $('#greetingType').val()
            },
            {
                'field' : 'middle_name',
                'op' : 'cn',
                'data' : fioFields.length > 2 ? fioFields[2].toLowerCase() : '' //$('#middleName').val()
            },
            {
                'field' : 'last_name',
                'op' : 'cn',
                'data' : fioFields.length > 0 ? fioFields[0].toLowerCase() : '' //$('#lastName').val()
            },
            {
                'field' : 'first_name',
                'op' : 'cn',
                'data' : fioFields.length > 1 ? fioFields[1].toLowerCase() : '' //$('#firstName').val()
            },
            {
                'field' : 'diagnosis',
                'op' : 'in',
                'data' : choosedDiagnosis
            }
        ];
		if ($('#enterpriseId').val()!=-1){
			rules.push(					{
				'field': 'enterprise_id',
				'op': 'eq',
				'data': $('#enterpriseId').val()
			});			
		}

        if($('#canPregnant').val() == 1) {
            rules.push({
                'field' : 'is_for_pregnants',
                'op' : 'eq',
                'data' : $('#canPregnant').val()
            });
        }

        // Дата не везде есть: на странице записи опосредованных пациентов её нет
        if($('#greetingDate').length > 0) {
            if($('#greetingDateComboChoose').length == 0 || ($('#greetingDateComboChoose').length > 0 && $('#greetingDateComboChoose').val() == 1)) {
                rules.push({
                    'field' : 'greeting_date',
                    'op' : 'eq',
                    'data' : $('#greetingDate').val()
                });
            }
        }
        // Отсеиваем из массива
        var Result ={
            'groupOp' : 'AND',
            'rules' : rules
        };
        return Result;
    }
    
    function updatePatientsList() {
        var filters = getPatientsFilter();
		if(!filters) {
			return -1;
		}
        var PaginationData=getPaginationParameters('searchWithCardResult');
        if (PaginationData!='') {
            PaginationData = '&'+PaginationData;
        }
        // Делаем поиск
        $.ajax({
            'url' : '/reception/patient/search/?withonly=0&filters=' + $.toJSON(filters)+PaginationData,
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    // Изначально таблицы скрыты
                    $('#withoutCardCont').addClass('no-display');

                    if(data.rows.length == 0) {
                        // Проверим: мб, есть пациент с таким полисом просто
                        $.ajax({
                            'url' : '/reception/patient/search/?withoutonly=0&rows=10&page=1&sidx=oms_number&sord=desc&filters=' + $.toJSON(filters),
                            'cache' : false,
                            'dataType' : 'json',
                            'type' : 'GET',
                            'success' : function(data, textStatus, jqXHR) {
                                if(data.success && data.rows.length == 1) {
                                    createWithOms = data.rows[0].id; // Записываем ОМС, с которым снадо создать карту
                                }
                                $('#notFoundPopup').modal({
                                });
                            }
                        });
                    } else {
                        displayAllPatients(data.rows);
                        printPagination('searchWithCardResult',data.total);
                    }
                } else {
                    $('#errorSearchPopup .modal-body .row p').remove();
                    $('#errorSearchPopup .modal-body .row').append('<p>' + data.data + '</p>')
                    $('#errorSearchPopup').modal({

                    });
                }
				$('#patient-search-submit').trigger('end');
                return;
            }
        });
        
    }

    function updateDoctorsList() {
        var filters = getDoctorsFilter();
        var PaginationData = getPaginationParameters('searchDoctorsResult');
        if (PaginationData != '') {
            PaginationData = '&'+PaginationData;
        }

        if(globalVariables.hasOwnProperty('greetingId')) {
            var data = {
                greeting_id : globalVariables.greetingId
            };
        } else {
            var data = {};
        }

        if(globalVariables.hasOwnProperty('greetingId')) {
            $('#lastName, #fio').prop('disabled', true);
        }


        if(globalVariables.hasOwnProperty('beginDate') && globalVariables.beginDate != null && !(globalVariables.resetBeginDate)) {
            data.beginDate = globalVariables.beginDate;
        }

        if(globalVariables.hasOwnProperty('isCallCenter') && globalVariables.isCallCenter == 1) {
            data.is_callcenter = 1;
        }

        if(globalVariables.hasOwnProperty('isWaitingLine') && globalVariables.isWaitingLine == 1) {
            data.onlywaitingline = 1;
        }

        globalVariables.resetBeginDate = true;
        $('.organizer').trigger('resetClickedTime');
        $('.organizer').trigger('resetClickedDay');

        // Делаем поиск
        $.ajax({
            'url' : '/reception/doctors/search/?filters=' + $.toJSON(filters) + PaginationData,
            'cache' : false,
            'dataType' : 'json',
            'data' : data,
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    // Изначально таблицы скрыты
                    $('#withoutCardCont').addClass('no-display');
                    $('#lastName, #fio').prop('disabled', false);

                    if(data.data.length == 0) {
                        $('#notFoundPopup').modal({
                        });
                        $('.organizer, .organizerNav, .organizerH').css({
                            'display' : 'none'
                        });
                    } else {
                        $('.organizer, .organizerNav, .organizerH').css({
                            'display' : 'block'
                        });
                        if(globalVariables.hasOwnProperty('calendarType') && globalVariables.calendarType == 0) {
                            displayAllDoctors(data.data);
                        } else {
                            $('.organizer').trigger('showShedule', [data, textStatus, jqXHR]);
                        }
                        printPagination('searchDoctorsResult',data.total);
                    }
                } else {
                    $('#errorPopup .modal-body .row p').remove();
                    $('#errorPopup .modal-body .row').append('<p>' + data.data + '</p>')
                    $('#errorPopup').modal({

                    });
                }
				$('#doctor-search-submit').trigger('end');
                return;
            }
        });
    }
    
    /*$('#doctor-search-submit').click(function(e) {
		$(this).trigger('begin');
        updateDoctorsList();
        return false;
    });*/

    $(document).on('click', '#doctor-search-submit', function(e) {
        $(this).trigger('begin');
        updateDoctorsList();
        return false;
     });



    // Отобразить таблицу тех, кто с картами
    function displayAllPatients(data) {
        // Проверим - есть ли столбец номер ОМС в таблице
        var printOms = (  $('#searchWithCardResult thead').find('.omsNumberCell').length>0  )
        var table = $('#searchWithCardResult tbody');
        table.find('tr').remove();
        for(var i = 0; i < data.length; i++) {
			data[i].middle_name=data[i].middle_name?data[i].middle_name:'-';
            // В цикле - сначала генерируем контент для строки, дописываем значения столбцов, в зависимости от того, есть
            //    ли соответствующие столбцы
            //   а потом дописываем строку в таблицу
				if(globalVariables.isCallCenter) {
					var content = '<tr>' +
						'<td class="write-patient-cell">' +
							'<a title="Записать пациента" href="http://' + location.host + '/reception/patient/writepatientsteptwo/?cardid=' + data[i].card_number + '&callcenter=1&is_pregnant=' + $('#canPregnant').val() + '">' +
								'<span class="glyphicon glyphicon-dashboard"></span>' +
							'</a>' +
						'</td>';
				} else {
					var content = '<tr>' +
						'<td class="write-patient-cell">' +
							'<a title="Записать пациента" href="http://' + location.host + '/reception/patient/writepatientsteptwo/?cardid=' + data[i].card_number + ((globalVariables.hasOwnProperty('isWaitingLine') && globalVariables.isWaitingLine == 1) ? '&waitingline=1' : '') +'">' +
								'<span class="glyphicon glyphicon-dashboard"></span>' +
							'</a>' +
						'</td>';
				}
				var fio=data[i].last_name + ' ' + data[i].first_name + ' ' + data[i].middle_name;
                content += '<td>' +
                        '<a title="Посмотреть информацию по пациенту" href="#' + data[i].id + '" class="viewHistory">' +
                            fio +
                        '</a>' +
                    '</td>' +
                    '<td>'+
                        (data[i].birthday?data[i].birthday:'') +
                    '</td>' +
                    '<td>' +
                        '<a title="Посмотреть информацию по карте" href="#' + data[i].card_number + '" class="editMedcard">' +
                            data[i].card_number +
                        '</a>' +
                    '</td>';

            if (printOms)
            {
                content +=
                    '<td>' +
                        '<a title="Посмотреть информацию по ОМС" href= "#' + data[i].id + '" class="editOms">' +
                        (data[i].oms_number?data[i].oms_number:'') +
                        '</a>' +
                    '</td>'

            }
            content += '<tr>';
            table.append(content);
        }
        table.parents('div.no-display').removeClass('no-display');
    }

    // Отобразить таблицу тех, кто с картами
    function displayAllDoctors(data) {
        var table = $('#searchDoctorsResult tbody');
        table.find('tr').remove();
        for(var i = 0; i < data.length; i++) {
            /*var cabinetsStr = '';
            for(var j = 0; j < data[i].cabinets.length; j++) {
                if(j > 0) {
                    cabinetsStr += ', ';
                }
                cabinetsStr += '' + data[i].cabinets[j].description + '';
            }*/
            table.append(
                '<tr>' +
                    '<td class="write-patient-cell">' +
                        '<a title="Записать пациента" class="write-patient-link" href="#d' + data[i].id + '">' +
                            '<span class="glyphicon glyphicon-dashboard"></span>' +
                        '</a>' +
                    '</td>' +
                    '<td class="fio-cell">' +
                        '<a title="Посмотреть информацию по врачу" href="#">' +
                            data[i].last_name + ' ' + data[i].first_name + ' ' + data[i].middle_name +
                        '</a>' +
                    '</td>' +
                    '<td>' +
                        ((data[i].post == null) ? '' : data[i].post) +
                    '</td>' +
                    '<td>' +
                        (data[i].ward != null ? data[i].ward : '')  +
                    '</td>' +
                    '<td>' +
                        data[i].cabinet +
                    '</td>' +
                    '<td>' +
                        data[i].nearFree +
                    '</td>' +
                '</tr>'
            );
        }
        table.parents('div.no-display').removeClass('no-display');
    }

    $(document).on('click', '.write-patient-link', function(e, month, year) {
        if(globalVariables.hasOwnProperty('clickedLink')) {
            $(globalVariables.clickedLink).parents('tr').removeClass('success');
        }
        $(this).parents('tr').addClass('success');
        globalVariables.doctorId = $(this).attr('href').substr(2);
        globalVariables.clickedLink = $(this);
        globalVariables.fio = $(this).parents('tr').find('td.fio-cell a').text();
        loadCalendar(month, year);
        return false;
    });

    function loadCalendar(month, year, clicked) {
        $('.busyShedule, .busySheduleHeader').hide();
       // var doctorId = $(link).attr('href').substr(2);
        var doctorId = globalVariables.doctorId;
      //  globalVariables.fio = $(link).parents('tr').find('td.fio-cell a').text();
       // globalVariables.clickedLink = $(link);
        var params = {};

        if(typeof month == 'undefined' || typeof year == 'undefined') {
            $('.busyDate').text(globalVariables.months[(new Date()).getUTCMonth()] + ' ' + (new Date()).getFullYear() + ' г.');
        } else {
            // Ведущий ноль
            if((month + 1) < 10) {
                month = '0' + (month + 1);
            } else {
                month += 1;
            }
            var current = (new Date(year + '-' + month )).getUTCMonth();
            $('.busyDate').text(globalVariables.months[current] + ' ' + year + ' г.');
            params.month = parseInt(month);
            params.year = year;
        }
        // Делаем запрос на информацию и обновляем шаблон календаря
        // Делаем поиск
        params.doctorid = doctorId;
        $.ajax({
            'url' : '/doctors/shedule/getcalendar',
            'data' : params,
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == 'true') {
                    $("#writeShedule").trigger("showShedule", [data, textStatus, jqXHR, clicked])
                    $('.headerBusyCalendar, .busyCalendar').show();
                    $('.busyFio').text(globalVariables.fio);
                } else {
                    $('#errorPopup .modal-body .row p').remove();
                    $('#errorPopup .modal-body .row').append('<p>' + data.data + '</p>')
                    $('#errorPopup').modal({

                    });
                }
                return;
            }
        });
    }

    $('#sheduleByBusy').on('showBusy', function(e, data, textStatus, jqXHR, doctorId, year, month, day) {
        $('.busyDay').text(day + '.' + (month + 1) + '.' + year + ' г.');
        $('.busyFio').text(globalVariables.fio);
        var table = $(this).find('tbody');
        var data = data.data;
        table.find('tr').remove();
        globalVariables.doctorId = doctorId;
        globalVariables.day = day;

        for(var i = 0; i < data.length; i++) {
            if(data[i].isAllow) {
                var str =
                '<tr>' +
                    '<td>' + data[i].timeBegin + ' - ' + data[i].timeEnd + '</td>' +
                    '<td></td>' +
                    '<td class="write-patient-cell">' +
                        '<a class="write-link" href="#' + data[i].timeBegin + '" title="Записать пациента">' +
                            '<span class="glyphicon glyphicon-dashboard"></span>' +
                        '</a>' +
                    '</td>' +
                '</tr>';
            } else {
                if(data[i].type === 0) {
                    var str =
                    '<tr>' +
                        '<td>' + data[i].timeBegin + ' - ' + data[i].timeEnd + '</td>' +
                        '<td>' +
                            '<a href="http://' + location.host + '/reception/patient/editcardview/?cardid=' + data[i].cardNumber + '" title="Посмотреть информацию по пациенту">' +
                                    data[i].fio  +
                            '</a>' +
                        '</td>' +
                        '<td class="write-patient-cell">' +
                            '<a class="unwrite-link" href="#' + data[i].id + '">' +
                                '<span class="glyphicon glyphicon-remove" title="Снять пациента с записи"></span>' +
                            '</a>' +
                        '</td>' +
                    '</tr>';
                } else {
                    var str =
                        '<tr>' +
                            '<td>' + data[i].timeBegin + ' - ' + data[i].timeEnd + '</td>' +
                            '<td>' +
                                data[i].fio  +
                            '</td>' +
                            '<td class="write-patient-cell">' +
                                '<a class="unwrite-link" href="#' + data[i].id + '">' +
                                    '<span class="glyphicon glyphicon-remove" title="Снять пациента с записи"></span>' +
                                '</a>' +
                            '</td>' +
                        '</tr>';
                }
            }

            table.append(str);
        }

        $('.busyShedule, .busySheduleHeader').show();
    });

    $(document).on('click', '.write-link', function(e) {
        writePatient();
    });


    $('#deleteOldGreeting').on('click',
        function()
        {
            greetingId = globalVariables.greetingId;
            unWritePatient(greetingId);
        }

    );


    function writePatientQuery()
    {


        if(!globalVariables.hasOwnProperty('withWindow')) {
            globalVariables.patientTime = $(this).attr('href').substr(1);
        }

        // В зависимости от того, есть ли номер карты или нет, можно судить, запись это опосредованного пациента или нет. Это говорит о том, нужно ли вводить данные о пациенте или нет
        if(typeof globalVariables.cardNumber == 'undefined') {
            // Нужно показать модалку для ввода данных о пациенте
            $('#patientDataPopup').modal({});
            return false;
        } else {
            var params = {
                month : globalVariables.month + 1,
                year : globalVariables.year,
                day : globalVariables.day,
                doctor_id : globalVariables.doctorId,
                mode: 0, // Обычная запись
                time: globalVariables.patientTime,
                card_number: globalVariables.cardNumber,
                greeting_type: $('#greetingType').val(),
                comment: $('#comment').val()
            };
        }

        if(globalVariables.hasOwnProperty('isWaitingLine') && globalVariables.isWaitingLine == 1 && globalVariables.hasOwnProperty('orderNumber')) {
            params.order_number = globalVariables.orderNumber;
        }

        if(globalVariables.hasOwnProperty('cancelledGreeting')) {
            params.cancelledGreetingId = globalVariables.cancelledGreeting;
        }
        $.ajax({
            'url' : '/doctors/shedule/writepatient',
            'data' : params,
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == 'true') {
                    $('#successPopup p').text(data.data);
                    $('#successPopup').modal({

                    });
                    // Перезагружаем календарь
                   /* loadCalendar(globalVariables.month, globalVariables.year, $(globalVariables.clickedTd).prop('id')); */
                    if(!globalVariables.hasOwnProperty('isWaitingLine') || globalVariables.isWaitingLine != 1) {
                        globalVariables.greetingId = data.greetingId;
                    }
                    if($('.organizer').length > 0) {
                        $('.organizer').trigger('resetTriggerByLoad');
                        $('.organizer').trigger('returnDate');
                        globalVariables.resetBeginDate = false;
                        $('.organizer').trigger('reload');
                    } else {
                        loadCalendar(globalVariables.month, globalVariables.year, $(globalVariables.clickedTd).prop('id'));
                    }
                } else {
                    // Удаляем предыдущие ошибки
                    $('#errorPopup .modal-body .row p').remove();
                    // Вставляем новые
                    $('#errorPopup .modal-body .row').append("<p>" + data.error + "</p>");
                    $('#errorPopup').modal({
                    });
                }
                return;
            }
        });
    }


    $('#confirmPopup').on('hidden.bs.modal', function (e)
        {
            if(globalVariables.hasOwnProperty('isMediateWrite') && globalVariables.isMediateWrite == 1) {
                writeMediatePatient();
            } else {
                writePatientQuery();
            }
        }
    );


    function writePatient() {
        if(globalVariables.greetingId != undefined) {
           $('#confirmPopup').modal({});
        }  else {
            writePatientQuery();
        }
        return false;
    }

    $(document).on('click', '.unwrite-link', function(e) {
        id =  $(this).attr('href').substr(1);
        unWritePatient(id);
        return false;
    });

    function unWritePatient(greetingId)
    {
        // Если определён
        var params = {
            id : greetingId
        };
        $.ajax({
            'url' : '/doctors/shedule/unwritepatient',
            'data' : params,
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == 'true') {
                    $('#successPopup p').text(data.data);
                    $('#successPopup').modal({

                    });
                    // Перезагружаем календарь
                    loadCalendar(globalVariables.month, globalVariables.year, $(globalVariables.clickedTd).prop('id'));
                } else {
                    $('#cannotUnwritePopup p').text(data.data);
                    $('#cannotUnwritePopup').modal({

                    });
                }
                return;
            }
        });
    }

    // Подтвердить данные для опосредованного пациента
    $('#submitReservData').on('click', function() {
        if(globalVariables.greetingId != undefined) {
            globalVariables.isMediateWrite = 1;
            $('#confirmPopup').modal({});
        }  else {
            writeMediatePatient();
        }
    });

    function writeMediatePatient() {
        var params = {
            'firstName' : $('#firstName').val(),
            'lastName' : $('#lastName').val(),
            'middleName' : $('#middleName').val(),
            'comment' : $('#comment').val(),
            'greeting_type': $('#greetingType').val(),
            'phone' :  $('#phone').val(),
            'month' : globalVariables.month + 1,
            'year' : globalVariables.year,
            'day' : globalVariables.day,
            'doctor_id' : globalVariables.doctorId,
            'mode' : 1, // Опосредованного пациента запись
            'time' : globalVariables.patientTime
        };
        globalVariables.isMediateWrite = 0; // Это флаг опосредованной записи

        if(globalVariables.hasOwnProperty('cancelledGreeting')) {
            params.cancelledGreetingId = globalVariables.cancelledGreeting;
        }
        $.ajax({
            'url' : '/doctors/shedule/writepatient',
            'data' : params,
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == 'true') {
                    $('#patientDataPopup').modal('hide');
                    $('#successPopup p').text(data.data);
                    $('#successPopup').modal({

                    });

                    // Перезагружаем календарь или органайзер
                    if($('.organizer').length > 0) {
                        globalVariables.greetingId = data.greetingId;
                        // Переписываем данные на новые
                        globalVariables.patientData = {
                            'firstName' : '',
                            'lastName' : '',
                            'middleName' : '',
                            'comment' : '',
                            'phone' : '+7'
                        };

                        $('.organizer').trigger('resetTriggerByLoad');
                        $('.organizer').trigger('returnDate');
                        globalVariables.resetBeginDate = false;
                        $('.organizer').trigger('reload');
                    } else {
                        loadCalendar(globalVariables.month, globalVariables.year, $(globalVariables.clickedTd).prop('id'));
                    }
                } else {
                    // Удаляем предыдущие ошибки
                    $('#errorPopup .modal-body .row p').remove();
                    // Вставляем новые
                    for(var i in data.errors) {
                        for(var j = 0; j < data.errors[i].length; j++) {
                            $('#errorPopup .modal-body .row').append("<p class=\"errorText\">" + data.errors[i][j] + "</p>")
                        }
                    }

                    $('#errorPopup').modal({
                    });
                }
                return;
            }
        });
    }



    /*
    $('#patientDataPopup').on('hidden.bs.modal', function (e) {
        $('#patientDataPopup').find('#firstName, #lastName, #middleName, #comment').val('');
        $('#patientDataPopup').find('#phone').val('+7');
    });
*/


    // Просмотр истории медкарты
    $(document).on('click', '.viewHistory', function() {
        $('#panelOfhistoryMedcard').parent().addClass('no-display');
        var omsId = $(this).attr('href').substr(1);
        $('#viewHistoryMotionPopup .modal-title').text('История движения карты пациента ' + $(this).parents('tr').find('.viewHistory:eq(0)').text() + ' (карта № ' + $(this).parents('tr').find('.cardNumber:eq(0)').text() + ')');
        $('#omsSearchWithCardResult tr').removeClass('active');
        $(this).parents('tr').addClass('active');
        $("#motion-history").jqGrid('setGridParam',{
            'datatype' : 'json',
            'url' : globalVariables.baseUrl + '/reception/patient/gethistorymotion/?omsid=' + omsId
        }).trigger("reloadGrid");
        $('#viewHistoryMotionPopup').modal({});
        return false;
    });

    // Редактирование медкарты в попапе
    $(document).on('click', '.editMedcard', function(e) {
        console.log($(this).prop('href'));
        $.ajax({
            'url' : '/reception/patient/getmedcarddata',
            'data' : {
                'cardid' : $(this).prop('href').substr($(this).prop('href').lastIndexOf('#') + 1)
            },
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    var data = data.data.formModel;
                    var form = $('#patient-medcard-edit-form');
                    for(var i in data) {
                        $(form).find('#' + i).val(data[i]);
                    }

                    $(form).find('#documentGivedate').trigger('change');
                    $(form).find('#privilege').trigger('change');

                    $('#editMedcardPopup').modal({});
                } else {
                    $('#errorSearchPopup .modal-body .row p').remove();
                    $('#errorSearchPopup .modal-body .row').append('<p>' + data.data + '</p>')
                    $('#errorSearchPopup').modal({

                    });
                }
                return;
            }
        });
        return false;
    });

    // Редактирование полиса в попапе
    $(document).on('click', '.editOms', function(e) {
        $.ajax({
            'url' : '/reception/patient/getomsdata',
            'data' : {
                'omsid' : $(this).prop('href').substr($(this).prop('href').lastIndexOf('#') + 1)
            },
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    var data = data.data.formModel;
                    var form = $('#patient-oms-edit-form');
                    for(var i in data) {
                        $(form).find('#' + i).val(data[i]);
                    }

                    $(form).find('#policyGivedate').trigger('change');
                    $(form).find('#birthday').trigger('change');

                    $('#editOmsPopup').modal({});
                } else {
                    $('#errorSearchPopup .modal-body .row p').remove();
                    $('#errorSearchPopup .modal-body .row').append('<p>' + data.data + '</p>')
                    $('#errorSearchPopup').modal({

                    });
                }
                return;
            }
        });
        return false;
    });

    // Отобразить ошибки формы добавления пациента
    $("#patient-medcard-edit-form, #patient-oms-edit-form").on('success', function(eventObj, ajaxData, status, jqXHR) {
        var ajaxData = $.parseJSON(ajaxData);
        if(ajaxData.success == 'true') { // Запрос прошёл удачно, закрываем окно, перезагружаем jqGrid
            $('#editMedcardPopup').modal('hide');
            $('#editOmsPopup').modal('hide');
            $('#patient-search-submit').trigger('click');
        } else {
            // Удаляем предыдущие ошибки
            var popup = $('#errorAddPopup').length > 0 ? $('#errorAddPopup') : $('#errorSearchPopup');
            $(popup).find('.modal-body .row p').remove();
            // Вставляем новые
            for(var i in ajaxData.errors) {
                for(var j = 0; j < ajaxData.errors[i].length; j++) {
                    $(popup).find(' .modal-body .row').append("<p>" + ajaxData.errors[i][j] + "</p>")
                }
            }

            $(popup).css({
                'z-index' : '1051'
            }).modal({});
        }
    });

    var createWithOms = false;
    $('#createCard').on('click', function() {
        if(createWithOms !== false) {
            location.href = '/reception/patient/viewadd/?patientid=' + createWithOms
        } else {
            location.href = '/reception/patient/viewadd';
        }
    });

    /* Дата приёма (любая или не-любая) */
    $('#greetingDateComboChoose').on('change', function(e) {
        // Конкретная
        if($(this).val() == 1) {
            $('#greetingDate').parents('.form-group').removeClass('no-display');
        } else {
            $('#greetingDate').parents('.form-group').addClass('no-display');
        }
    });

    if(globalVariables.hasOwnProperty('greetingId')) {
        $('#lastName').prop('disabled', true);
        $('#doctor-search-submit').trigger('click');
    }

    $('#submitPatient').on('click', function(e) {
        $('#patientDataPopup').modal('hide');
        writePatient();
        $('#patientDataPopup').find('#firstName, #lastName, #middleName, #comment').val('');
        $('#patientDataPopup').find('#phone').val('+7');
    });

    $('#patient-search-form').on('keydown', function(e) {
        if(e.keyCode == 13) {
            $('#patient-search-submit').trigger('click');
            return false;
        }
    });
	
	$('#enterpriseId').on('change',function(e){
		console.debug('enterprise change');
		$('#ward').hide();
		$.ajax({
			url: '/reception/patient/ajaxwards?enterprise_id='+$(e.currentTarget).val(),
			'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if (data.success == true) {
					var data = data.data;
					console.debug(data);
					var options='';
					for (var i=0;i<data.length;i++){
						var item=data[i];
						options+='<option value="'+item.id+'">'+item.name+'</option>';
					}
					console.debug(options);
					$('#ward').html(options);
					$('#ward').show();
				}
            }
		});
	});
});