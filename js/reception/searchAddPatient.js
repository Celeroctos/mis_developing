$(document).ready(function() {
    // Поиск по ОМС
    $('#patient-search-submit').click(function(e) {
        var filters = {
            'groupOp' : 'AND',
            'rules' : [
                {
                    'field' : 'oms_number',
                    'op' : 'cn',
                    'data' :  $('#omsNumber').val()
                },
                {
                    'field' : 'first_name',
                    'op' : 'cn',
                    'data' : $('#firstName').val()
                },
                {
                    'field' : 'middle_name',
                    'op' : 'cn',
                    'data' : $('#middleName').val()
                },
                {
                    'field' : 'last_name',
                    'op' : 'cn',
                    'data' : $('#lastName').val()
                },
                {
                    'field' : 'address_reg',
                    'op' : 'cn',
                    'data' : $('#addressReg').val()
                },
                {
                    'field' : 'address',
                    'op': 'cn',
                    'data' : $('#address').val()
                },
                {
                    'field' : 'card_number',
                    'op' : 'cn',
                    'data' : $('#cardNumber').val()
                },
                {
                    'field' : 'serie',
                    'op' : 'cn',
                    'data' : $('#serie').val()
                },
                {
                    'field' : 'docnumber',
                    'op' : 'cn',
                    'data' : $('#docnumber').val()
                },
                {
                    'field' : 'snils',
                    'op' : 'cn',
                    'data' : $('#snils').val()
                },
                {
                    'field' : 'birthday',
                    'op' : 'eq',
                    'data' : $('#birthday').val()
                }
            ]
        };

        // Делаем поиск по ОМС
        $.ajax({
            'url' : '/index.php/reception/patient/search/?filters=' + $.toJSON(filters),
            'cache' : false,
            'dataType' : 'json',
            'type' : 'GET',
            'success' : function(data, textStatus, jqXHR) {
                if(data.success == true) {
                    // Изначально таблицы скрыты
                    $('#withoutCardCont').addClass('no-display');
                    $('#withCardCont').addClass('no-display');

                    if(data.data.with.length == 0 && data.data.without.length == 0) {
                        $('#notFoundPopup').modal({
                        });
                    } else {
                        if(data.data.without.length > 0) {
                            displayAllWithoutCard(data.data.without);
                        }
                        if(data.data.with.length > 0) {
                            displayAllWithCard(data.data.with);
                        }
                    }
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

    // Отобазить таблицу тех, кто без карт
    function displayAllWithoutCard(data) {
        // Заполняем пришедшими данными таблицу тех, кто без карт
        var table = $('#omsSearchWithoutCardResult tbody');
        table.find('tr').remove();
        for(var i = 0; i < data.length; i++) {
            table.append(
                '<tr>' +
                    '<td><a href="#" title="Посмотреть информацию по пациенту">' + data[i].last_name + ' ' + data[i].first_name + ' ' + data[i].middle_name + '</a></td>' +
                    '<td>' + data[i].oms_number + '</td>' +
                    '<td>' +
                        '<a href="http://moniiag.toonftp.ru/index.php/reception/patient/viewadd/?patientid=' + data[i].id + '">' +
                            '<span class="glyphicon glyphicon-plus"></span>' +
                        '</a>' +
                    '</td>' +
                    '<td>' +
                        '<a href="http://moniiag.toonftp.ru/index.php/reception/patient/editomsview/?omsid=' + data[i].id + '">' +
                            '<span class="glyphicon glyphicon-edit"></span>' +
                        '</a>' +
                    '</td>' +
                '</tr>'
            );
        }
        table.parents('div.no-display').removeClass('no-display');
    }

    // Отобразить таблицу тех, кто с картами
    function displayAllWithCard(data) {
        // Заполняем пришедшими данными таблицу тех, кто без карт
        var table = $('#omsSearchWithCardResult tbody');
        table.find('tr').remove();
        for(var i = 0; i < data.length; i++) {
            table.append(
                '<tr>' +
                    '<td><a href="#" title="Посмотреть информацию по пациенту">' + data[i].last_name + ' ' + data[i].first_name + ' ' + data[i].middle_name + '</a></td>' +
                    '<td>' + data[i].oms_number + '</td>' +
                    '<td>' + data[i].reg_date + '</td>' +
                    '<td>' + data[i].card_number + '</td>' +
                    '<td>' +
                        '<a href="http://moniiag.toonftp.ru/index.php/reception/patient/viewadd/?patientid=' + data[i].id + '">' +
                            '<span class="glyphicon glyphicon-plus"></span>' +
                        '</a>' +
                    '</td>' +
                    '<td>' +
                        '<a href="http://moniiag.toonftp.ru/index.php/reception/patient/editcardview/?cardid=' + data[i].card_number + '">' +
                            '<span class="glyphicon glyphicon-edit"></span>' +
                        '</a>' +
                    '</td>' +
                    '<td>' +
                        '<a href="http://moniiag.toonftp.ru/index.php/reception/patient/editomsview/?omsid=' + data[i].id + '">' +
                            '<span class="glyphicon glyphicon-edit"></span>' +
                        '</a>' +
                    '</td>' +
                '</tr>'
            );
        }
        table.parents('div.no-display').removeClass('no-display');
    }

    // Отобразить ошибки формы добавления пациента
    $("#patient-withoutcard-form, #patient-withcard-form, #patient-medcard-edit-form, #patient-oms-edit-form").on('success', function(eventObj, ajaxData, status, jqXHR) {
        var ajaxData = $.parseJSON(ajaxData);
        if(ajaxData.success == 'true') { // Запрос прошёл удачно, закрываем окно для добавления нового предприятия, перезагружаем jqGrid
            if($(this).attr('id') == '#patient-withcard-form' || $(this).attr('id') == '#patient-withoutcard-form') {
                $(this)[0].reset();
            }
            $('#successAddPopup').modal({

            });
        } else {
            // Удаляем предыдущие ошибки
            $('#errorAddPopup .modal-body .row p').remove();
            // Вставляем новые
            for(var i in ajaxData.errors) {
                for(var j = 0; j < ajaxData.errors[i].length; j++) {
                    $('#errorAddPopup .modal-body .row').append("<p>" + ajaxData.errors[i][j] + "</p>")
                }
            }

            $('#errorAddPopup').modal({

            });
        }
    });

    $("#firstName, #lastName, #middleName").on('keyup', function(e) {
        var str = $(this).val();
        if(str != "") {
            $(this).val(str.charAt(0).toUpperCase() + str.substr(1));
        }
    });
});