<div id="helperIcon"></div>
<div id="voiceIcon"></div>
<div class="col-xs-12 row">
    <ul class="nav nav-tabs" id="bedsstockNavbar">
        <li role="navigation" class="active">
            <a href="#armd-my-patients" aria-controls="armd-my-patients" role="tab" data-toggle="tab" id="armdMyPatientsTab">Мои пациенты</a>
            <span class="tabmark" id="armdMyPatientsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#armd-patients" aria-controls="armd-patients" role="tab" data-toggle="tab" id="armdPatientsTab">Пациенты</a>
            <span class="tabmark" id="armdPatientsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#armd-operations" aria-controls="armd-operations" role="tab" data-toggle="tab" id="armdOperationsTab">Операции</a>
            <span class="tabmark" id="armdOperationsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
    </ul>
</div>
<div class="row col-xs-12 tableBlock">
    <div class="armdTablesCont col-xs-11">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="armd-my-patients">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane" id="armd-patients">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane" id="armd-operations">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
        </div>
    </div>
</div>
<div class="contentForPatientGrid no-display">
    <ul class="nav nav-tabs balooned" id="patientGridNavbar">
        <li role="navigation" class="active baloon">
            <a href="#armd-show-patient" aria-controls="armd-show-patient" role="tab" data-toggle="tab" id="armdShowPatientTab">Осмотр</a>
            <span class="tabmark" id="armdShowPatientTab">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation" class="baloon">
            <a href="#armd-patient-diagnosis" aria-controls="armd-patient-diagnosis" role="tab" data-toggle="tab" id="armdPatientDiagnosisTab">Диагноз</a>
            <span class="tabmark" id="armdPatientDiagnosisTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation" class="baloon">
            <a href="#armd-patient-purpose" aria-controls="armd-patient-purpose" role="tab" data-toggle="tab" id="armdPatientPurposeTab">Назначение</a>
            <span class="tabmark" id="armdPatientPurposeTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation" class="baloon">
            <a href="#armd-patient-directions" aria-controls="armd-patient-directions" role="tab" data-toggle="tab" id="armdPatientDirectionsTab">Направления</a>
            <span class="tabmark" id="armdPatientDirectionsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation" class="baloon">
            <a href="#armd-patient-tests" aria-controls="armd-patient-tests" role="tab" data-toggle="tab" id="armdPatientTestsTab">Результаты исследования</a>
            <span class="tabmark" id="armdPatientTestsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation" class="baloon">
            <a href="#armd-patient-relocation" aria-controls="armd-patient-relocation" role="tab" data-toggle="tab" id="armdPatientRelocationTab">Выписка / Перевод</a>
            <span class="tabmark" id="armdPatientRelocationTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation" class="baloon">
            <a href="#armd-patient-childbirth" aria-controls="armd-patient-childbirth" role="tab" data-toggle="tab" id="armdPatientChildbirthTab">Ведение родов</a>
            <span class="tabmark" id="armdPatientChildbirthTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="armd-show-patient">
            <ul class="nav nav-tabs desktopTab">
                    <li role="navigation" class="last" >
                       <a href="#"  role="tab" data-toggle="tab" >+</a>
                    </li>
                </ul>
                <div class="tab-content">
                </div>
            </ul>
            <div class="row btnPanel">
                <input type="button" value="Сохранить" class="btn btn-success" id="saveTemplate"/>
                <input type="button" value="Отмена" class="btn btn-primary" id="cancelTemplate" />
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-diagnosis"></div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-purpose">
            <div class="wrap">
                <div class="panel1"></div>
                <div class="panel2">
                    <div class="gridContainer"></div>
                    <h6>Назначение новых</h6>
                    <div class="row btnPanel">
                        <input type="button" value="Медикамента" class="btn btn-success" id="addDrug"/>
                        <input type="button" value="Процедуры" class="btn btn-success" id="addProcedure" />
                    </div>
                </div>
                <div class="panel3">
                    <h5>Новое назначение</h5>
                    <div class="left"></div>
                    <div class="right"></div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-directions">
            4
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-tests">
            5
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-relocation">
            6
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-childbirth">
            7
        </div>
    </div>
</div>