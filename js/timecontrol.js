$(document).ready(function() {
    
       
     // �������� ����������� � ���������� ���
    var TimeControlContainers =
        [
        "#edit-timeBegin-cont",
        "#edit-timeEnd-cont"
        ];
        
    //$('.subcontrol input').val('');
    // ������������ ������� ������� �� ������-������� ��� �������� � �����
    // ������������ ������� ������� �� ������-������� ��� �������� � �����
    function ArrowTimeClickHandler(Target, Control)  {
        // ������ �����
        // ��������� � �� ��� ������ ��������
        var TimeArray = $(Control).find('input.form-control').val().split(":");
        // ���� �� ����� ���
        if (TimeArray.length == 0)  {
            // ��������� ������� ���
            TimeArray.push(new Date().getHours());
        } else {
            // ���������, �������� �� ������ ������� �������� ������ ����
            if (parseInt(TimeArray[0])<0 ||  parseInt(TimeArray[0])>23 ) {
                // ��������� ������ ���
                TimeArray[0] =  (new Date()).getHours();
            }
        }

        // ���� �� ����� �����
        if (TimeArray.length == 1) {
            // ��������� ������� ������
            TimeArray.push(new Date().getMinutes());
        } else {
            // ��������� - ������� �� ������ ���� ��� - ��������� �������
            if (parseInt(TimeArray[1])<0 ||  parseInt(TimeArray[1])>59) {
                // ��������� ������ �����
                TimeArray[1] = new Date().getMinutes();
            }
        }

        // ���� ��-���� ��� ������ ���� ���� - ������ ������ ����
        var StructDate = new Date();
        
        // ������������� ��� � ������, ����� � ���� ������
        StructDate.setHours(TimeArray[0]);
        StructDate.setMinutes(TimeArray[1]);
        
        // � ���������� Date ������������ �����
        // � ����������� �� ������� ������ - ��������� ����� �����

        if ($(Target.currentTarget).hasClass('up-hour-button')) {
            StructDate.setHours(StructDate.getHours() + 1);
        }

        if ($(Target.currentTarget).hasClass('up-minute-button')) {
            StructDate.setMinutes(StructDate.getMinutes() + 1);
        }

        if ($(Target.currentTarget).hasClass('down-hour-button')) {
            StructDate.setHours(StructDate.getHours() - 1);
        }

        if ($(Target.currentTarget).hasClass('down-minute-button')) {
            StructDate.setMinutes(StructDate.getMinutes() - 1);
        }

        // ��������������� ��������� ���� ������� � ���������� � �������
        //console.log(Control);
        $(Control).find('input.form-control:first').val(StructDate.getHours()+ ':' + StructDate.getMinutes());
        $(Control).find('input.form-control:first').trigger('change');
    }


    // ������� �����-���� ��� ��������
    // ������� ������� �����
    (function () {
        // �������� ��� �������� ���

            var Controls = [];
            for (OneControlContainer=0;OneControlContainer<TimeControlContainers.length;OneControlContainer++)
            {
                var ControlSelector = TimeControlContainers[OneControlContainer];
                Controls.push($(ControlSelector)[0]);
            }
            
            //$(TimeControlContainers[OneControlContainer]).find('div.time-control');
            // ���������� ��������� ��������
            for (i = 0; i < Controls.length; i++) {
            // �������� ������ �� ������ �������
            (function (Control) {
                // ����������� ���������� ������� ������� �� ������� ������ ��� ��������
                var btnPrevNext = $(Control).find('.time-ctrl-up-buttons .btn-group button, .time-ctrl-down-buttons .btn-group button');
                var lastNullEntered = false; // ����� ��������� 01, 02...
				$(btnPrevNext).on('click',function (e) {
                    ArrowTimeClickHandler(e, Control);
                });
                // ���������� �� �����������, ���� ���� ����
                var subcontrol = $(Control).find('.subcontrol');
                $(subcontrol).find('input').on('change', function(e) {
                     var container = $(this).parents('.subfields');
                     var hour = $(container).find('input.hour');

                     var allowChange = true;
                     if($.trim($(hour).val()) == '') {
                        allowChange = false;
                     }

                     var minute = $(container).find('input.minute');
                     if($.trim($(minute).val()) == '') {
                        allowChange = false;
                     }


                     // ������ ���� ��� ��� �������� �����������, ���� � ���������� �������� ����� ������
                     if(allowChange) {
                         $(this).parents('.form-group').find('input.form-control:first').trigger('change', [1]);
                     }
                });

               
                
                
                
                $(subcontrol).find('input.hour').on('keyup', function(e) {
                    // ���� ���� ���������, �� ���������� ������� ���������
                    var selected = getSelected(this);
                    if($.trim(selected) != '') {
                        return false;
                    }
					
			if($(this).val().length == 1 && lastNullEntered) { // ������, �������� ����� == 2, ���� ��� �����...
			    lastNullEntered = false;
			    $(this).next().focus();
                            $(this).next().select();
			}

                    if(($(this).val().length == 2 || $(this).val().length == 1) && e.keyCode != 9 && (e.keyCode < 37 || e.keyCode > 40)) {
                        if($(this).val().length == 1) {
                            if(parseInt($(this).val()) < 3) {
                                return false;
                            }
                        }
                        $(this).next().focus();
                        $(this).next().select();
                    }

                    if(e.keyCode == 9 && $.trim($(this).val()) != '') {
                        // ��. ���� �� ���������.
                        $(this).select();
                    }
                }).on('keydown', function(e) {
                    $(this).css('background-color', '#ffffff');
                    // ����
                    if(e.keyCode == 40) {
                        $(this).parents('.subcontrol').find('.time-ctrl-down-buttons .down-hour-button').trigger('click');
                        e.stopPropagation();
                        return false;
                    }
                    // �����
                    if(e.keyCode == 38) {
                        $(this).parents('.subcontrol').find('.time-ctrl-up-buttons .up-hour-button').trigger('click');
                        e.stopPropagation();
                        return false;
                    }

                    var selected = getSelected(this);
                    // �������� �� ������������ ��������� ��� ������
                    if((e.keyCode > 48 && e.keyCode < 58) || (e.keyCode > 96 && e.keyCode < 106)) { // ��� ������ ������� �����, ����
                        // ������� ����, ���� ��� ���� ����������
                        if($.trim(selected) != '') {
                            $(this).val('');
                        }
			// ������ 0-9 - �����
			if(e.keyCode > 95 && e.keyCode < 106) {
                            e.keyCode -= 48;
			}
                        // ��� ���
                        // ��� ����� "01, 02" � ��. parseInt ���� ���� ������. � ��� ���. �� ������ �������, ���� ���� ������� ����.
                        var hour = parseInt('' + $(this).val() + String.fromCharCode(e.keyCode));
                        // �������� - �������� ����� ������ �� 23
                        if(hour>23) {
                            $(this).animate({
                                backgroundColor: "rgb(255, 196, 196)"
                            });
                            return false;
                        }
                    } else {
						// �������� ������� ���� ����������
						if(e.keyCode == 48 || e.keyCode == 96) {
							lastNullEntered = true;
							return false;
						}
                        // ������� ������-�����, tab � backspace ���������, ��������� ������� ����
                        if(e.keyCode != 9 && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 16  && e.keyCode != 48 && e.keyCode != 96 ) {
							$(this).animate({
								backgroundColor: "rgb(255, 196, 196)"
							});
							// ���� ������ ����������
                            return false;
                        }
                    }
                });

                
                $(subcontrol).find('input.minute').on('keyup', function(e) {
                    // ���� ���� ���������, �� ���������� ������� ���������
                    var selected = getSelected(this);
                    if($.trim(selected) != '') {
                        return false;
                    }

			if($(this).val().length == 1 && lastNullEntered) { // ������, �������� ����� == 2, ���� ��� �����...
			    lastNullEntered = false;
                            $(this).next().focus();
                        $(this).next().select();
					}
					
                    if(($(this).val().length == 2 || $(this).val().length == 1) && e.keyCode != 9 && (e.keyCode < 37 || e.keyCode > 40)) {
                        if($(this).val().length == 1) {
			    if(parseInt($(this).val()) < 6) {
                                return false;
                            }
                        }
                        $(this).next().focus();
                        $(this).next().select();
                    }

                    // ���� ���� ���������, �� ���������� ������� ���������
                    var selected = getSelected(this);
                    if($.trim(selected) != '') {
                        return false;
                    }

                    if(e.keyCode == 9 && $.trim($(this).val()) != '') {
                        $(this).select();
                    }
                }).on('keydown', function(e) {
                    $(this).css('background-color', '#ffffff');
                    // ����
                    if(e.keyCode == 40) {
                        $(this).parents('.subcontrol').find('.time-ctrl-down-buttons .down-minute-button').trigger('click');
                        e.stopPropagation();
                        return false;
                    }
                    // �����
                    if(e.keyCode == 38) {
                        $(this).parents('.subcontrol').find('.time-ctrl-up-buttons .up-minute-button').trigger('click');
                        e.stopPropagation();
                        return false;
                    }

                    var selected = getSelected(this);
                    // �������� �� ������������ �������� ����� ������
                    if((e.keyCode > 48 && e.keyCode < 58) || (e.keyCode > 96 && e.keyCode < 106)) { // ��� ������ ������� �����, ����
						// ������� ����, ���� ��� ���� ����������
                        if($.trim(selected) != '') {
                            $(this).val('');
                        }
						// ������ 0-9 - �����
						if(e.keyCode > 95 && e.keyCode < 106) {
							e.keyCode -= 48;
						}
                        // ��� ������
                        var minute = parseInt('' + $(this).val() + String.fromCharCode(e.keyCode));
                        // ������ �� ����� ���� ������ ���� � ������ 59
                        if(!(minute > -1 && minute < 60)) {
                            $(this).animate({
                                backgroundColor: "rgb(255, 196, 196)"
                            });
                            return false;
                        }
                    } else {
						// �������� ������� ���� ����������
						if(e.keyCode == 48 || e.keyCode == 96) {
							lastNullEntered = true;
							return false;
						}
                        // ������� ������-�����, tab � backspace ���������
                        if(e.keyCode != 9 && e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 39 && e.keyCode != 16 && e.keyCode != 48 && e.keyCode != 96) {
                            $(this).animate({
                                backgroundColor: "rgb(255, 196, 196)"
                            });
                            return false;
                        }
                    }
                });
                
            })(Controls[i]);
    
        }
    
    }
    )();

    function getSelected(element) {
        return $(element).selection();
    }
    

        
    // ���� ���
    (function initTimeFields(timeFields) {
        var format = 'h:i';
        for(var i = 0; i < timeFields.length; i++) {
            if($(timeFields[i]).length == 0) {
                continue;
            }
            $(timeFields[i]).datetimepicker({
                language:  'ru',
                format: format,
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0
            });
            
            //continue;
            
            var ctrl = $(timeFields[i]).find('input.form-control:first');
            $(ctrl).on('change', function(e, type){
                console.log('TimeControl Changed');
                var subcontrols = $(this).parents('.form-group').find('.subcontrol');
                if(typeof subcontrols != 'undefined') {
                    var hour = $(subcontrols).find('input.hour');
                    var minute = $(subcontrols).find('input.minute');
                    // �������� type ������� � ���, � ����� ����������� ����� ������: �� ��������� � ����������� ��� ��������.
                    // �� ��� � ���������
                    if(typeof type == 'undefined') {
                       // $(subcontrols).find('input').val('');
                        var currentTime = $(this).val();
                        var parts = currentTime.split(':');
                        $(hour).val(parseInt(parts[0]));
                        $(minute).val(parseInt(parts[1]));
                    } else { // �� ���������� � ���
                        $(this).val(hour.val()+':'+minute.val());
                    }
                }
            });
            if($.trim($(ctrl).val()) != '') {
                $(ctrl).trigger('change');
            }
        }
    })(TimeControlContainers);
});

