$(document).ready(function () {

    $.fn['timetableEditor'] =
    {
        startAdding: function()
        {
            openEditor();
            initEditor();
            editorBlock = $('#edititngSheduleArea');
            initHandlers(editorBlock);
        },
        startEditing: function(timeTableToEdit)
        {
            openEditor();
            initEditor(timeTableToEdit);
            editorBlock = $('#edititngSheduleArea');
            initHandlers(editorBlock);
        },
        addRowInEditor: function()
        {
            addOneRowRule();
        }
    }

    // ��������� �������� (���������� ���� � ���)
    function openEditor()
    {
        $('.addingNewSheduleContainer').addClass('no-display');
        editingTemplate = $('#timetableTemplates #timetableEditing');
        // ������ ���� �������� � ���������� editingTemplate � ����
        editorBlock = $('#edititngSheduleArea');
        $(editorBlock).removeClass('no-display');

        $(editorBlock).html(   $(editingTemplate[0]).html()   );
    }

    function initHandlers( editorBlock)
    {
        // � ������ tr-�� ��������� ������� ������� ��������
        $(editorBlock).find('.oneRowRuleTimetable:eq(0) td.deleteTD').empty();

        $(editorBlock).find('.sourceSelect').trigger('change');

        // ������ ��������� ��� ������ "��� ������"
        tableRow = $(editorBlock).find('.oneRowRuleTimetable:eq(0)');
        initRowHandlers(tableRow);

        InitOneDateControl(  $(editorBlock).find('.sheduleBeginDateTime-cont'))   ;
        InitOneDateControl(  $(editorBlock).find('.sheduleEndDateTime-cont'))   ;

    }

    function initEditor(timeTableToEdit)
    {
        // ���� �������� - ���� ��������� ��� ������� �� ������� � ��������� �� ��������������
        //   ����� - ���� ������� ������ ������ ������� �
        if (timeTableToEdit==undefined)
        {

        }
        else
        {

        }
    }

    function initEditorEmpty()
    {

    }

    function initEditorWithData(timeTableToEdit)
    {

    }

    function addOneRowRule()
    {
        // �������:
        //      1. ���� �� ������� �������
        //      2. ��������� � ����� ������� � ����������
        //      3. � ������� "��������������" ������ rowspan �� +1
        templateRow = $('#timetableTemplates .oneRowRuleTimetable').clone();
        buttonsRow = $('#edititngSheduleArea tr.addRuleButtons');

        // ������������� ������� � ������
        initRowHandlers(templateRow);

        // � ����������� ������ ��������� ������� "factsTD"
        $(templateRow).find('.factsTD ').remove();

        templateRow.insertBefore(buttonsRow);

        factsTD = $('#edititngSheduleArea tr.oneRowRuleTimetable:eq(0) td.factsTD');
        $(factsTD).attr('rowspan',   $('#edititngSheduleArea tr.oneRowRuleTimetable').length);
        $('#edititngSheduleArea').find('.sourceSelect').trigger('change');


    }

    // ������ ��������� ������ ������ "��� ������"
    function initRowHandlers(tableRow)
    {

        InitOneDateControl(   $(tableRow).find('.daysTD .date-timetable')  );
        InitOneTimeControl(   $(tableRow).find('.hoursOfWorkTD .workingHourBeginTime')  );
        InitOneTimeControl(   $(tableRow).find('.hoursOfWorkTD .workingHourEndTime')  );
        InitOneTimeControl(   $(tableRow).find('.hoursOfGreetingTD .greetingHourBeginTime')  );
        InitOneTimeControl(   $(tableRow).find('.hoursOfGreetingTD .greetingHourEndTime')  );

        // ������ ����� �� �����
        for (i=0;i<3;i++)
        {
            limitBlock = $(tableRow).find('.limitTD .limitBlock'+ (i+1).toString());
            //
            timeBeginComtrol = $(limitBlock).find( '.limitTime'+ (i+1).toString());
            timeEndComtrol = $(limitBlock).find( '.limitTime'+ (i+1).toString()+'End');
            // ������ ��������
            InitOneTimeControl(   timeBeginComtrol );
            InitOneTimeControl(  timeEndComtrol );
        }
        // ������ ��������� ������ � ����� �������� �������

    }
});