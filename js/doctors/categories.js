$(document).ready(function() {
    $('button[id^=ba]').filter('[id*=history]').prop('disabled', true);
    $('button[id^=ba]').filter(':not([id*=history])').on('click', function(e) {
        var elementId = $(this).attr('id').substr($(this).attr('id').lastIndexOf('_') + 1);
        $('#controlId').val(elementId);
        globalVariables.elementId = elementId;
        globalVariables.domElement = $(this).attr('id');
        $('#addValuePopup').modal({
        });
    });

    $("#add-value-form").on('success', function(eventObj, ajaxData, status, jqXHR) {
        var ajaxData = $.parseJSON(ajaxData);
        if(ajaxData.success == 'true') { // Запрос прошёл удачно, закрываем окно для добавления нового предприятия, перезагружаем jqGrid
            $('#addValuePopup').modal('hide');
            $("#add-value-form")[0].reset(); // Сбрасываем форму
            $(globalVariables.domElement).find('option:first').before('<option value="' + ajaxData.id + '">' + ajaxData.display + '</option>');
        } else {
            // Удаляем предыдущие ошибки
            $('#errorPopup .modal-body .row p').remove();
            // Вставляем новые
            // Только одна ошибка...
            if(ajaxData.hasOwnProperty('error')) {
                $('#errorPopup .modal-body .row').append("<p>" + ajaxData.error + "</p>")
            } else {
                for(var i in ajaxData.errors) {
                    for(var j = 0; j < ajaxData.errors[i].length; j++) {
                        $('#errorPopup .modal-body .row').append("<p>" + ajaxData.errors[i][j] + "</p>")
                    }
                }
            }

            $('#errorPopup').modal({
            });
        }
    });
});