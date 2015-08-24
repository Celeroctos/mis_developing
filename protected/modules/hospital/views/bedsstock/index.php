<div id="helperIcon"></div>
<div id="voiceIcon"></div>
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
            <!-- 
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
             -->
	             <table id='patientsGrid' class="table table-hover table-striped table-bordered">
	             	<thead>
	             		<tr><th>ФИО</th><th>Карта</th><th>Отделение</th><th>Возраст</th><th>Дата госпитализации</th></tr>
	             	</thead>
	             	<tbody>
						<tr><td>Адамчикова Наталья Сергеевна</td><td>19903/14</td><td>Гинекологической эндокринологии</td><td>33 года</td><td>01.09.2015</td></tr>
						<tr><td>Воронкова Татьяна Вячеславовна</td><td>16944/14</td><td>Гинекологическое</td><td>37 лет</td><td>28.08.2015</td></tr>
						<tr><td>Воропанова Елена Алексеевна</td><td>94/14</td><td>Гинекологической эндокринологии</td><td>32 года</td><td>28.08.2015</td></tr>
						<tr><td>Гогун Оксана Анатольевна</td><td>10346/14</td><td>Гинекологической эндокринологии</td><td>35 лет</td><td>26.08.2015</td></tr>
						<tr><td>Гусева Рита Федоровна</td><td>19315/14</td><td>Гинекологическое</td><td>40 лет</td><td>26.08.2015</td></tr>
						<tr><td>Дорошенко Екатерина Владимировна</td><td>8924/14</td><td>Гинекологической эндокринологии</td><td>26 лет</td><td>19.08.2015</td></tr>
						<tr><td>Егорова Марина Сергеевна</td><td>11139/14</td><td>Гинекологической эндокринологии</td><td>31 год</td><td>27.08.2015</td></tr>
						<tr><td>Иншина Екатерина Геннадиевна</td><td>15037/1</td><td>Гинекологической эндокринологии</td><td>32 года</td><td>01.09.2015</td></tr>
						<tr><td>Карпова Викторова Александровна</td><td>19318/14</td><td>Гинекологическое</td><td>38 лет</td><td>27.08.2015</td></tr>
						<tr><td>Крохинская Татьяна Юрьевна</td><td>10026/14</td><td>Гинекологической эндокринологии</td><td>31 год</td><td>20.08.2015</td></tr>
						<tr><td>Митина Зоя Николаевна</td><td>11485/14</td><td>Эндоскопической хирургии</td><td>36 лет</td><td>18.08.2015</td></tr>
						<tr><td>Рябова Валентина Викторовна</td><td>4092/14</td><td>Гинекологическое</td><td>43 года</td><td>17.08.2015</td></tr>
						<tr><td>Сикачева Полина Игоревна</td><td>8144/14</td><td>Эндоскопической хирургии</td><td>28 лет</td><td>17.08.2015</td></tr>
						<tr><td>Терентьева Кристина Борисовна</td><td>6507/15</td><td>Эндоскопической хирургии</td><td>29 лет</td><td>19.08.2015</td></tr>
						<tr><td>Федоркина Оксана Владимировна</td><td>14977/14</td><td>Гинекологической эндокринологии</td><td>31 год</td><td>02.09.2015</td></tr>
						<tr><td>Харламова Ольга Сергеевна</td><td>16838/14</td><td>Гинекологической эндокринологии</td><td>31 год</td><td>27.08.2015</td></tr>
						<tr><td>Черыкаева Надежда Александровна</td><td>15101/14</td><td>Гинекологическое</td><td>36 лет</td><td>31.08.2015</td></tr>
						<tr><td>Шелудякова Ирина Николаевна</td><td>12031/14</td><td>Гинекологической эндокринологии</td><td>36 лет</td><td>25.08.2015</td></tr>
	             	</tbody>
	             </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="extracts">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane" id="relocations">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
            </div>
            <div role="tabpanel" class="tab-pane wardsSettings" id="wards">
                <?php $this->widget('application.modules.hospital.components.widgets.Wards', array(
                    'showSettingsIcon' => true,
                	'demo_free'=>1
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
        	<h3>Терапевтическое отделение. Палата №4 (обычная платная)</h3>
        	<div class="buttons" style="width:200px;margin:0 auto">
	        	<div>
	        		<button type="button" style="width:200px;height:50px;margin:10px;" onclick="$(this).html('Занята').attr('disabled','disabled');return false;">Свободна</button>
	        	</div>        	
	        	<div>
	        		<button type="button" style="width:200px;height:50px;margin:10px;" onclick="return false;">Свободна</button>
	        	</div>
	        	<div>
	        		<button disabled="disabled" type="button" style="width:200px;height:50px;margin:10px;">Занята</button>
	        	</div>
	        	<div>
	        		<button disabled="disabled" type="button" style="width:200px;height:50px;margin:10px;">Занята</button>
	        	</div>        	
        	<!-- 
	        	<table align="center">
	        		<tbody>
	        		
	        			<tr><td><div style="width:200px;height:50px;margin:10px;" onclick="console.debug(this);$(this).val('Занята');$('button',$(this).parent('table')).attr('disabled','disabled');return false;">Свободна</div></td></tr>
 			
	        			<tr><td><button type="button" style="width:200px;height:50px;margin:10px;">Свободна</button></td></tr>
	        			<tr><td><button disabled="disabled" type="button" style="width:200px;height:50px;margin:10px;">Занята</button></td></tr>
	        			<tr><td><button disabled="disabled" type="button" style="width:200px;height:50px;margin:10px;">Занята</button></td></tr>
	        		</tbody>
	        	</table>
	        	-->	        
        	</div>
        </div>
        <!-- 
        <div class="cont left">
            <span class="glyphicon glyphicon-chevron-left back" aria-hidden="true"><span>Назад</span></span>
            <h3>Палата №4 (обычная платная палата)</h3>
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
                        <button class="btn btn-success acceptReserve" type="button">Подтвердить</button>
                    </form>
                    
                </li>
                <li class="list-group-item">
                    <img src="/images/icons/48610.png" title="Койка занята" width="48" height="48" />
                    <p>Занята</p>
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
         -->
    </div>
</div>
<div class="relocationExpanderBody">
    <form class="form-inline relocationForm">
        <div class="form-group">
            <label for="wardId" class="col-xs-5">Выберите отделение для перевода</label>
            <div class="col-xs-7">
                <select class="form-control" id="wardId">
                    <option>%any ward%</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-success acceptRelocation" type="button">Подтвердить</button>
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
                    <button class="btn btn-danger" id="deleteWard">Удалить палату</button>
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
                <div class="form-group col-xs-12 col-sm-offset-3">
                    <button class="btn btn-primary" id="changeWard">Применить</button>
                    <button class="btn btn-success" id="closeWard">Закрыть</button>
                </div>
            </div>
        </div>
    </form>
</div>