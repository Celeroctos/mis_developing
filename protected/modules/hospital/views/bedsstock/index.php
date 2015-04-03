<div id="helperIcon" xmlns="http://www.w3.org/1999/html"></div>
<div class="col-xs-12 row">
    <ul class="nav nav-tabs" id="bedsstockNavbar">
        <li role="navigation" class="active">
            <a href="#bedsstockPatients" aria-controls="bedsstockPatients" role="tab" data-toggle="tab" id="bedsstockPatientsTab">Поступающие пациенты</a>
            <span class="tabmark" id="bedsstockPatientsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#bedsstockExtracts" aria-controls="bedsstockExtracts" role="tab" data-toggle="tab" id="bedsstockExtractsTab">Выписка</a>
            <span class="tabmark" id="bedsstockExtractsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#bedsstockRelocations" aria-controls="bedsstockRelocations" role="tab" data-toggle="tab" id="bedsstockRelocationsTab">Перевод</a>
            <span class="tabmark" id="bedsstockRelocationsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
        <li role="navigation">
            <a href="#bedsstockWards" aria-controls="bedsstockWards" role="tab" data-toggle="tab" id="bedsstockWardsTab">Палаты</a>
            <span class="tabmark" id="bedsstockWardsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
        </li>
    </ul>
</div>
<div class="row col-xs-12 tableBlock">
    <div class="bedssotckTablesCont col-xs-11">
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
            <div role="tabpanel" class="tab-pane" id="wards">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
        </div>
    </div>
</div>
<div class="bedsstockExpanderBody">
    <div class="wrap">
        <div class="cont left">
            <ul class="filter">
                <li>Палаты
                    <ul>
                        <li>
                            <label class="checkbox-inline">
                                <input type="checkbox" id="paidWard">Платные
                            </label>
                        </li>
                        <li>
                            <label class="checkbox-inline">
                                <input type="checkbox" id="notPaidWard">Бесплатные
                            </label>
                        </li>
                    </ul>
                </li>
                <li>Койки
                    <ul>
                        <li>
                            <label class="checkbox-inline">
                                <input type="checkbox" id="paidBeds">Платные
                            </label>
                        </li>
                        <li>
                            <label class="checkbox-inline">
                                <input type="checkbox" id="notPaidBeds">Бесплатные
                            </label>
                        </li>
                    </ul>
                </li>
                <li>Тип палаты
                    <select class="form-control col-xs-3" id="wardType">
                        <option>%any type%</option>
                    </select>
                </li>
            </ul>
            <ul class="wardsList">
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree"><strong class="text-danger">Карантин</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree"><strong class="text-danger">Занята</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
                <li>
                    <h4>Палата №1</h4>
                    <span class="wardType">Обычная</span>
                    <span class="paidType">Платная</span>
                    <span class="numFree">Свободно: <strong>3</strong></span>
                </li>
            </ul>
        </div>
        <div class="cont right">
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
                                <option>%any doctor%</option>
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
                                <option>%any doctor%</option>
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
                                <option>%any doctor%</option>
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