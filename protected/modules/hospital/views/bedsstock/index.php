<div id="helperIcon" xmlns="http://www.w3.org/1999/html"></div>
<div class="col-xs-12 row">
    <ul class="nav nav-tabs" id="bedsstockNavbar">
        <li role="navigation" class="active">
            <a href="#patients" aria-controls="patients" role="tab" data-toggle="tab" id="bedsstockPatientsTab">Поступающие пациенты</a>
            <span class="tabmark" id="bedsstockPatientsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#extracts" aria-controls="extracts" role="tab" data-toggle="tab" id="bedsstockExtractsTab">Выписка</a>
            <span class="tabmark" id="bedsstockExtractsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#relocations" aria-controls="relocations" role="tab" data-toggle="tab" id="bedsstockRelocationsTab">Перевод</a>
            <span class="tabmark" id="bedsstockRelocationsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#wards" aria-controls="wards" role="tab" data-toggle="tab" id="bedsstockWardsTab">Палаты</a>
            <span class="tabmark" id="bedsstockWardsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
    </ul>
</div>
<div class="row col-xs-12 tableBlock">
    <div class="bedsstockTablesCont col-xs-11">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="patients">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane" id="extracts">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane" id="relocations">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane wardsSettings" id="wards">
                <?php $this->widget('application.modules.hospital.components.widgets.Wards', array(
                    'showSettingsIcon' => true
                )); ?>
            </div>
        </div>
    </div>
</div>
<div class="bedsstockExpanderBody">
    <div class="wrap">
        <div class="cont left wardsChoose">
            <?php $this->widget('application.modules.hospital.components.widgets.Wards'); ?>
        </div>
        <div class="cont left">
            <span class="glyphicon glyphicon-chevron-left back" aria-hidden="true"><span>Назад</span></span>
            <h3>Психиатрическое отделение, палата №6 (обычная палата)</h3>
            <ul class="list-group bedsList">
                <li class="list-group-item">
                    <img src="/images/icons/48565.png" title="Занять койку" width="48" height="48" />
                    <a href="#" class="reserveBed">Занять койку</a>
                    <form class="form-inline reserveForm">
                        <div class="form-group">
                            <label for="doctorId">Врач</label>
                            <select class="form-control col-xs-3" id="doctorId">
                                <option value="-1">%any doctor%</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="patientCheckoutDate">Дата выписки</label>
                            <input type="text" class="form-control" id="patientCheckoutDate">
                        </div>
                        <button class="btn btn-success" type="button">Подтвердить</button>
                    </form>
                </li>
                <li class="list-group-item">
                    <img src="/images/icons/48610.png" title="Койка занята" width="48" height="48" />
                </li>
                <li class="list-group-item">
                    <img src="/images/icons/48565.png" title="Занять койку" width="48" height="48" />
                    <a href="#" class="reserveBed">Занять койку</a>
                    <form class="form-inline reserveForm">
                        <div class="form-group">
                            <label for="doctorId">Врач</label>
                            <select class="form-control col-xs-3" id="doctorId">
                                <option value="-1">%any doctor%</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="patientCheckoutDate">Дата выписки</label>
                            <input type="text" class="form-control" id="patientCheckoutDate">
                        </div>
                        <button class="btn btn-success" type="button">Подтвердить</button>
                    </form>
                </li>
                <li class="list-group-item">
                    <img src="/images/icons/48610.png" title="Койка занята" width="48" height="48" />
                </li>
                <li class="list-group-item">
                    <img src="/images/icons/48610.png" title="Койка занята" width="48" height="48" />
                    <a href="#" class="reserveBed">Занять койку</a>
                    <form class="form-inline reserveForm">
                        <div class="form-group">
                            <label for="doctorId">Врач</label>
                            <select class="form-control col-xs-3" id="doctorId">
                                <option value="-1">%any doctor%</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="patientCheckoutDate">Дата выписки</label>
                            <input type="text" class="form-control" id="patientCheckoutDate">
                        </div>
                        <button class="btn btn-success" type="button">Подтвердить</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="relocationExpanderBody">
    <form class="form-inline relocationForm">
        <div class="form-group">
            <label for="wardId">Выберите отделение для перевода</label>
            <select class="form-control col-xs-3" id="wardId">
                <option>%any doctor%</option>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-success" type="button">Подтвердить</button>
        </div>
    </form>
</div>
<div class="settingsFormCont">
    <form class="col-xs-12 settingsForm form-horizontal">
        <div class="row">
            <div class="col-xs-5">
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Платная
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Бесплатная
                    </label>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">№ палаты</label>
                    <div class="col-xs-8">
                        <input type="text" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Тип палаты</label>
                    <div class="col-xs-8">
                        <select class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Пол</label>
                    <div class="col-xs-8">
                        <select class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Кол-во коек</label>
                    <div class="col-xs-8">
                        <input type="text" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Платных</label>
                    <div class="col-xs-8">
                        <input type="text" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Применить</button>
                    <button class="btn btn-success">Закрыть</button>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="form-group">
                    <label class="col-xs-4 control-label">Отделение</label>
                    <div class="col-xs-8">
                        <select class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Статус</label>
                    <div class="col-xs-8">
                        <select class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Врач</label>
                    <div class="col-xs-8">
                        <select class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label">Комментарий</label>
                    <div class="col-xs-8">
                        <textarea class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>