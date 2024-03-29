<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-json.js" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jqGrid/src/i18n/grid.locale-ru.js" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jqGrid/js/jquery.jqGrid.min.js" ></script>
<!--<script type="text/javascript" src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/js/datecontrol.js" ></script>-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/reception/rewriting.js" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/reception/motionHistory.js" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/reception/searchAddPatient.js" ></script>
<h4>Пациенты, которых необходимо перезаписать</h4>
<div class="row">
    <form method="post" id="reception-shedule-form" role="form" class="form-horizontal col-xs-12">
        <div class="col-xs-5">
            <div class="form-group">
                <label class="col-xs-2 control-label required" for="greetingDate">Дата</label>
                <div class="col-xs-3 input-group date date-control" id="greetingDate-cont">
                    <input type="hidden" id="greetingDate" class="form-control col-xs-4" placeholder="Формат гггг-мм-дд" name="birthday">
				  <span class="input-group-addon">
					  <span class="glyphicon-calendar glyphicon">
					  </span>
				  </span>
                    <div class="subcontrol">
                        <div class="date-ctrl-up-buttons">
                            <div class="btn-group">
                                <button class="btn btn-default btn-xs glyphicon-arrow-up glyphicon up-day-button" tabindex="-1" type="button"></button>
                                <button class="btn btn-default btn-xs glyphicon-arrow-up glyphicon month-button up-month-button" tabindex="-1" type="button"></button>
                                <button class="btn btn-default btn-xs glyphicon-arrow-up glyphicon year-button up-year-button" tabindex="-1" type="button"></button>
                            </div>
                        </div>
                        <div class="form-inline subfields">
                            <input type="text" class="form-control day" placeholder="ДД" name="day">
                            <input type="text" class="form-control month" placeholder="ММ" name="month">
                            <input type="text" class="form-control year" placeholder="ГГГГ" name="year">
                        </div>
                        <div class="date-ctrl-down-buttons">
                            <div class="btn-group">
                                <button class="btn btn-default btn-xs glyphicon-arrow-down glyphicon down-day-button" tabindex="-1" type="button"></button>
                                <button class="btn btn-default btn-xs glyphicon-arrow-down glyphicon month-button down-month-button" tabindex="-1" type="button"></button>
                                <button class="btn btn-default btn-xs glyphicon-arrow-down glyphicon year-button down-year-button" tabindex="-1" type="button"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" id="sheduleViewSubmit">Посмотреть пациентов</button>
                <!--<input type="button" class="btn btn-success" name="print-submit" value="Печать" id="print-submit" disabled="disabled">-->
            </div>
        </div>

        <div class="col-xs-7">
            <div class="form-group">
                <label for="doctorCombo" class="col-xs-4 control-label required">Для врачей</label>
                <div class="col-xs-6">
                    <select name="doctorCombo" class="form-control" id="doctorCombo">
                        <option value="0" selected="selected">Всех</option>
                        <option value="1">Указать конкретных</option>
                    </select>
                </div>
            </div>
            <div class="form-group chooser no-display" id="doctorChooser">
                <label for="doctor" class="col-xs-4 control-label">Врач (Enter - добавить)</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" autofocus id="doctor" placeholder="ФИО врача">
                    <ul class="variants no-display">
                    </ul>
                    <div class="choosed">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="patientCombo" class="col-xs-4 control-label required">Для пациентов</label>
                <div class="col-xs-6">
                    <select name="patientCombo" class="form-control" id="patientCombo">
                        <option value="0" selected="selected">Всех</option>
                        <option value="1">Указать конкретных</option>
                    </select>
                </div>
            </div>
            <div class="form-group chooser no-display" id="cancelledPatientChooser">
                <label for="patient" class="col-xs-4 control-label">Обычный пациент (Enter - добавить)</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" autofocus id="patient" placeholder="ФИО пациента">
                    <ul class="variants no-display">
                    </ul>
                    <div class="choosed">
                    </div>
                </div>
            </div>
            <div class="form-group chooser no-display" id="mediateChooser">
                <label for="mediatePatient" class="col-xs-4 control-label">Опосредованный пациент (Enter - добавить)</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" autofocus id="mediatePatient" placeholder="ФИО пациента">
                    <ul class="variants no-display">
                    </ul>
                    <div class="choosed">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-xs-8 control-label required">Только опосредованные пациенты (без ЭМК)</label>
                <div class="col-xs-4">
                    <input type="checkbox" name="status" id="status">
                </div>
            </div>
        </div>
    </form>
</div class="row">
<h4 id="sheduleInfoH4"></h4>
<div class="row">
    <table id="sheduleTable" class="col-xs-11">
        <thead>
        <tr class="header">
            <td>Врач</td>
            <td>Пациент</td>
            <td>Контактный телефон</td>
            <td>Комментарий</td>
            <td>Время</td>
            <td>Номер карты</td>
            <td>История движения</td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<h4 id="writingLineInfoH4"></h4>
<div class="row">
    <table id="writingLineTable" class="col-xs-11">
        <thead>
        <tr class="header">
            <td>Врач</td>
            <td>Пациент</td>
            <td>Контактный телефон</td>
            <td>Комментарий</td>
            <td>Дата</td>
            <td>Номер карты</td>
            <td>История движения</td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<div class="modal fade error-popup" id="errorPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ошибка!</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="acceptGreetingPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Найти реального пациента к опосредованному</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Найдите реального пациента по предъявленным им документам, либо <?php echo CHtml::link('создайте новую запись', array('/reception/patient/viewadd'), array('target' => '_blank')); ?>
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'patient-search-form',
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl.'/reception/patient/search'),
                            'htmlOptions' => array(
                                'class' => 'form-horizontal col-xs-12',
                                'role' => 'form'
                            )
                        ));
                        ?>
                    <div class="form-group">
                        <label for="omsNumber" class="col-xs-2 control-label">Номер полиса ОМС</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" autofocus id="omsNumber" placeholder="ОМС" title="Номер полиса ОМС может состоять из цифр и пробелов">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="serie" class="col-xs-2 control-label">Серия документа</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="serie" placeholder="Серия документа">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="docnumber" class="col-xs-2 control-label">Номер документа</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="docnumber" placeholder="Номер документа" title="Номер документа может состоять из цифр">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="col-xs-2 control-label">Фамилия</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="lastName" placeholder="Фамилия">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstName" class="col-xs-2 control-label">Имя</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="firstName" placeholder="Имя">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="middleName" class="col-xs-2 control-label">Отчество</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="middleName" placeholder="Отчество">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber" class="col-xs-2 control-label">Номер карты</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="cardNumber" placeholder="Номер карты">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="birthday2" class="col-xs-2 control-label required">Дата рождения</label>
                        <div id="birthday2-cont" class="col-xs-3 input-group date">
                            <input type="hidden" name="birthday2" placeholder="Формат гггг-мм-дд" class="form-control col-xs-4" id="birthday2">
                            <span class="input-group-addon">
                                <span class="glyphicon-calendar glyphicon">
                                </span>
                            </span>
                            <div class="subcontrol">
                                <div class="date-ctrl-up-buttons">
                                    <div class="btn-group">
                                        <button type="button" tabindex="-1" class="btn btn-default btn-xs glyphicon-arrow-up glyphicon up-day-button"></button>
                                        <button type="button" tabindex="-1" class="btn btn-default btn-xs glyphicon-arrow-up glyphicon month-button up-month-button"></button>
                                        <button type="button" tabindex="-1" class="btn btn-default btn-xs glyphicon-arrow-up glyphicon year-button up-year-button" ></button>
                                    </div>
                                </div>
                                <div class="form-inline subfields">
                                    <input type="text" name="day" placeholder="ДД" class="form-control day">
                                    <input type="text" name="month" placeholder="ММ" class="form-control month">
                                    <input type="text" name="year" placeholder="ГГГГ" class="form-control year">
                                </div>
                                <div class="date-ctrl-down-buttons">
                                    <div class="btn-group">
                                        <button type="button" tabindex="-1" class="btn btn-default btn-xs glyphicon-arrow-down glyphicon down-day-button"></button>
                                        <button type="button" tabindex="-1" class="btn btn-default btn-xs glyphicon-arrow-down glyphicon month-button down-month-button"></button>
                                        <button type="button" tabindex="-1" class="btn btn-default btn-xs glyphicon-arrow-down glyphicon year-button down-year-button" ></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="button" id="patient-search-submit" value="Найти" name="patient-search-submit" class="btn btn-success">
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
                <div class="row no-display" id="withoutCardCont">
                    <h5>Найденные пациенты без карт:</h5>
                    <div class="col-xs-8 borderedBox">
                        <table class="table table-condensed table-hover" id="omsSearchWithoutCardResult">
                            <thead>
                            <tr class="header">
                                <td></td>
                                <td>
                                    ФИО
                                </td>
                                <td>
                                    Номер полиса ОМС
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="row no-display">
                        <ul class="pagination content-pagination">
                        </ul>
                    </div>
                </div>
                <div class="row no-display" id="withCardCont">
                    <h5>Найденные пациенты с картами:</h5>
                    <div class="col-xs-11 borderedBox">
                        <table class="table table-condensed table-hover" id="omsSearchWithCardResult">
                            <thead>
                            <tr class="header">
                                <td></td>
                                <td>
                                    ФИО
                                </td>
                                <td>
                                    Год регистрации карты
                                </td>
                                <td>
                                    Номер полиса ОМС
                                </td>
                                <td>
                                    Номер карты
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="row no-display">
                        <ul class="pagination content-pagination">
                        </ul>
                    </div>
                </div>
                <div class="row no-display" id="mediateSubmit-cont">
                    <div class="form-group">
                        <input type="button" id="mediate-attach-submit" value="Сопоставить опосредованного пациента с существующими данными" name="mediate-attach-submit" class="btn btn-success disabled">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="notFoundPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Сообщение</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>По введённым поисковым критериям не найдено ни одного пациента. Вы можете ввести новые данные о пациенте, нажав на кнопку ниже, либо вернуться к поиску.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="createNewPatientBtn">Завести нового пациента</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">Вернуться в поиск</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="mediateOkPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Сообщение</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Пациент успешно сопоставлен с существующей картой!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="errorSearchPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ошибка!</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade error-popup" id="viewHistoryMotionPopup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">История движения карты пациента</h4>
            </div>
            <div class="modal-body">
                <span id="oms-id" class="no-display"></span>
                <div id="cardMotionHistory" class="row">
                    <table id="motion-history"></table>
                    <div id="motion-historyPager"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>