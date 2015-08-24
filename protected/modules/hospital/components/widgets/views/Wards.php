<div class="wardsWidget">
    <ul class="filter">
        <li>Палаты
            <span class="tabmark" id="allWardsTabmark">
                <span class="roundedLabel"></span>
                <span class="roundedLabelText"></span>
            </span>
            <ul>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="paidWard" />Платные<span class="tabmark" id="paidWardsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="notPaidWard" />Бесплатные<span class="tabmark" id="notPaidWardsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
            </ul>
        </li>
        <li>Койки
            <ul>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="paidBeds" />Платные<span class="tabmark" id="paidBedsTabmark">
                        	<div style="display:none">
                            	<span class="roundedLabel"></span>
                            	<span class="roundedLabelText"></span>
                            </div>
                        </span>
                    </label>
                </li>
                <li>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="notPaidBeds" />Бесплатные<span class="tabmark" id="notPaidBedsTabmark">
                            <span class="roundedLabel"></span>
                            <span class="roundedLabelText"></span>
                        </span>
                    </label>
                </li>
            </ul>
        </li>
        <!-- 
        <li>Тип палаты
            <select class="form-control col-xs-3" id="wardType">
                <option value="-1">%any type%</option>
            </select>
        </li>
         -->
        <li>
            <button class="btn btn-success resetFilter">Сбросить фильтр</button>
        </li>
    </ul>
    <div class="row">
        <ul class="wardsList">
            <li>
                <?php if($show_settings_icon) { print '<span class="glyphicon glyphicon-cog settings" title="Настройки"></span>'; } ?>
                <h4>Палата №1</h4>
                <span class="wardType">Обычная</span>
                <span class="paidType">Платная</span>
                <span class="numFree"><strong class="text-danger">Занята</strong></span>
            </li>
            <li>
                <?php if($show_settings_icon) { print '<span class="glyphicon glyphicon-cog settings" title="Настройки"></span>'; } ?>
                <h4>Палата №2</h4>
                <span class="wardType">Обычная</span>
                <span class="paidType">Платная</span>
                <span class="numFree">Свободно: <strong>3</strong></span>
            </li>
            <li>
                <h4>Палата №3</h4>
                <span class="wardType">Обычная</span>
                <span class="paidType">Платная</span>
                <span class="numFree">Свободно: <strong>4</strong></span>
            </li>
            <li>
                <h4>Палата №4</h4>
                <span class="wardType">Обычная</span>
                <span class="paidType">Платная</span>
                <span class="numFree">Свободно: <strong><?php print $this->demo_free?></strong></span>
            </li>
            <li>
                <h4>Палата №5</h4>
                <span class="wardType">Обычная</span>
                <span class="paidType">Платная</span>
                <span class="numFree">Свободно: <strong>2</strong></span>
            </li>
            <li>
                <h4>Палата №6</h4>
                <span class="wardType">Обычная</span>
                <span class="paidType">Платная</span>
                <span class="numFree">Свободно: <strong>2</strong></span>
            </li>
            <li>
                <h4>Палата №7</h4>
                <span class="wardType">Послеоперационная</span>
                <span class="paidType">Бесплатная</span>
                <span class="numFree"><strong class="text-danger">Карантин</strong></span>
            </li>
            <li>
                <h4>Палата №8</h4>
                <span class="wardType">Послеоперационная</span>
                <span class="paidType">Бесплатная</span>
                <span class="numFree">Свободно: <strong>3</strong></span>
            </li>
            <li>
                <h4>Палата №9</h4>
                <span class="wardType">Реанимация</span>
                <span class="paidType">Бесплатная</span>
                <span class="numFree">Свободно: <strong>3</strong></span>
            </li>
            <li>
                <h4>Палата №10</h4>
                <span class="wardType">Детская реанимация</span>
                <span class="paidType">Бесплатная</span>
                <span class="numFree">Свободно: <strong>3</strong></span>
            </li>
            <li>
                <h4>Палата №11</h4>
                <span class="wardType">Предродовая</span>
                <span class="paidType">Бесплатная</span>
                <span class="numFree">Свободно: <strong>3</strong></span>
            </li>
            <li>
                <h4>Палата №12</h4>
                <span class="wardType">Послеродовая</span>
                <span class="paidType">Бесплатная</span>
                <span class="numFree">Свободно: <strong>3</strong></span>
            </li>            
            <li class="new">
                <button class="btn btn-success" title="Добавить новую палату" id="addNewWard">+</button>
            </li>
        </ul>
    </div>
    <div class="bedsEditCont">
        <ul class="list-group bedsSettingsList">
            <li class="list-group-item">
                <img src="/images/icons/48610.png" title="Койка занята: Гогун Оксана Анатольевна" width="48" height="48" />
                <a href="#" class="reservedBed">Гогун Оксана Анатольевна</a>
                <!-- 
                <span class="glyphicon glyphicon-cog bed-settings" title="Настройки" id="b2"></span>
                 -->
            </li>            

            <li class="list-group-item">
                <img src="/images/icons/48565.png" title="" width="48" height="48" />
                <!-- 
                <span class="glyphicon glyphicon-cog bed-settings" title="Настройки" id="b3"></span>
                 -->
            </li>
            <li class="list-group-item">
                <img src="/images/icons/48610.png" title="Койка занята: Трутнева Наталья Владимировна" width="48" height="48" />
                <a href="#" class="reservedBed">Трутнева Наталья Владимировна</a>
                <span class="glyphicon glyphicon-cog bed-settings" title="Настройки" id="b2"></span>
            </li>            
            <li class="list-group-item">
                <img src="/images/icons/48610.png" title="Койка занята: Герасимова Юлия Анатольевна" width="48" height="48" />
                <a href="#" class="reservedBed">Герасимова Юлия Анатольевна</a>
                <span class="glyphicon glyphicon-cog bed-settings" title="Настройки" id="b4"></span>
            </li>
            <li class="list-group-item">
                <span class="glyphicon glyphicon-plus bed-add" title="Добавить койку" id="b5"></span>
            </li>
        </ul>
    </div>
    <div class="bedAddCont">

    </div>
    <div class="bedSettingsFormCont">
        <form class="bedSettingsForm form-horizontal">
            <div class="form-group">
                <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Платная
                </label>
                <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Бесплатная
                </label>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">№ палаты</label>
                <div class="col-xs-8">
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Статус</label>
                <div class="col-xs-8">
                    <select class="form-control"></select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Зарезервировать до</label>
                <div class="col-xs-8">
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Комментарий</label>
                <div class="col-xs-8">
                    <textarea cols="30" rows="10" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-danger deleteBed">Удалить койку</button>
                <button class="btn btn-primary acceptBed">Применить</button>
                <button class="btn btn-success closeBed">Закрыть</button>
            </div>
        </form>
    </div>
    <div class="addPatientFormCont">
        <form class="addPatientForm form-horizontal">
            <div class="form-group">
                <label class="control-label col-xs-4">ФИО</label>
                <div class="col-xs-8">
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Год рождения</label>
                <div class="col-xs-8">
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Дата выписки</label>
                <div class="col-xs-8">
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Врач</label>
                <div class="col-xs-8">
                    <select class="form-control"></select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Зарезервировать до</label>
                <div class="col-xs-8">
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-4">Комментарий</label>
                <div class="col-xs-8">
                    <textarea cols="30" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group col-xs-12">
                <div class="col-xs-2"></div>
                <div class="col-xs-10">
                    <button class="btn btn-warning dischargePatient">Выписать</button>
                    <button class="btn btn-primary acceptPatient">Подтвердить</button>
                    <button class="btn btn-success closePatientForm">Закрыть</button>
                </div>
            </div>
        </form>
    </div>
</div>