$(document).ready(function() {
    $('.calendar').on('showShedule', function(e, data) {
        var tbody = $(".calendar tbody");
        // Удаляем всё ненужное
        $(tbody).find("tr").remove();
        var isFilled = $(tbody).find("tr").size() > 0;

        var d = new Date(); // определяем текущую дату
        var year = data.data.year; // вычисляем текущий год
        var month = data.data.month - 1; // вычисляем текущий месяц (расхождение с utc в единицу)
        var day = data.data.day; // вычисляем текущее число
        var prevMonth = data.data.month - 2; // предыдущий месяц

        var firstDay = new Date(year, month, 1); // устанавливаем дату первого числа текущего месяца
        var firstWday = firstDay.getDay(); // из нее вычисляем день недели первого числа текущего месяца

        var firstPrevDay = new Date(year, prevMonth, 1);
        var numPrevDays = 32 - new Date(year, prevMonth, 32).getDate();

        var doctorId = data.data.doctorId;

        // Сначала наполняем первую неделю
        var tr = $("<tr>");
        if(firstWday == 0) {
            firstWday = 7;
        }

        var beginPrevDay = numPrevDays - firstWday + 2; // День из предыдущего месяца, который надо нарисовать
        var i = 1;
        for(; i < firstWday; i++) {
            if(!isFilled) {
                $(tr).append($('<td>').addClass('text-muted').text(beginPrevDay));
            }
            beginPrevDay++;
        }
        if((i - 1) % 7 == 0 && i != 0) {
            $(tr).appendTo($(tbody));
            tr = $('<tr>');
        }

        // Строим основной месяц
        var calendar = data.data.calendar;
        for(; i < firstWday + calendar.length; i++) {
            if(!isFilled) {
                var td = $('<td>').text((i - firstWday) + 1);
                $(tr).append($(td));
            }
            // Красим ячейки
            var dayData = calendar[i - firstWday];
            // Выходные
            if(!dayData.worked) {
                //$(td).addClass('')
            } else {
                // Рабочие дни
                if(dayData.numPatients == 0) {
                    $(td).addClass('lightgreen-block')
                }
                if(dayData.numPatients > 0 && dayData.numPatients < dayData.quote) {
                    $(td).addClass('yellow-block')
                }
                if(dayData.numPatients == dayData.quote) {
                    $(td).addClass('red-block')
                }
            }

            if(i % 7 == 0) {
                $(tr).appendTo($(tbody));
                tr = $('<tr>');
            }
        }

        // Строим добавку следующего месяца
        var futureDays = 1;
        for(; i % 7 != 1; i++) {
            $(tr).append($('<td>').addClass('text-muted').text(futureDays));
            futureDays++;
        }

        $(tr).appendTo($(tbody));

        // Получение списка пациентов по дате
        $('.calendar tbody td:not(.text-muted)').on('click', function(e) {
            var day = $(this).text();
            globalVariables.clickedTd = $(this);
            $.ajax({
                'url' : '/index.php/doctors/shedule/getpatientslistbydate/?doctorid=' + doctorId + '&year=' + year + '&month=' + (month + 1) + '&day=' + day,
                'cache' : false,
                'dataType' : 'json',
                'type' : 'GET',
                'success' : function(data, textStatus, jqXHR) {
                    if(data.success == 'true') {
                        $('#sheduleByBusy').trigger('showBusy', [data, textStatus, jqXHR, doctorId, year, month, day]);
                    } else {

                    }
                    return;
                }
            });
        });
    });
});