

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
            	<table style="margin-bottom:10px">
            	<tbody>
            		<tr>
            			<td><div id="operationDatePicker"></div></td>
            		</tr>
            		<tr>
            			<td>
            				<button style="width:100%">Сегодня</button>
            			</td>
            		</tr>
            		<tr>
            			<td>
            				<button style="width:100%">Показать всех пациентов</button>
            			</td>
            		</tr>
            	</tbody>
            	</table>
            
	            <table id="operationsGrid" class="table table-hover table-striped table-bordered">
	            	<thead>
	            		<tr>
	            			<th>ФИО</th>
	            			<th>№ палаты</th>
	            			<th>№ карты</th>
	            			<th>Дата</th>
	            			<th>Статус операции</th>
	            		</tr>
	            	</thead>
	            	<tbody>
	            		<tr>
	            			<td>Данилова Светлана Алексеевна</td>
	            			<td>2</td>
	            			<td>11346/14</td>
	            			<td>28.08.2015</td>
	            			<td>Не проведена</td>
	            		</tr>
	            		<tr>
	            			<td>Мацнева Оксана Евгеньевна</td>
	            			<td>5</td>
	            			<td>11653/14</td>
	            			<td>21.08.2015</td>
	            			<td>Проведена</td>
	            		</tr>		
	            		<tr>
	            			<td>Острягина Марианна Михайловна</td>
	            			<td>5</td>
	            			<td>15881/14</td>
	            			<td>19.08.2015</td>
	            			<td>Проведена</td>
	            		</tr>
	            		<tr>
	            			<td>Шевлягина Ольга Николаевна</td>
	            			<td>1</td>
	            			<td>12253/14</td>
	            			<td>01.09.2015</td>
	            			<td>Не проведена</td>
	            		</tr>              		            		
	            	</tbody>
	            </table>
            	
                
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


<div class="operationsExpanderBody" style="display:none">



		
        <div style="width:100%;padding:10px;height:85px;">
        
			<div class="form-group">
	            <label for="birthday2" class="col-xs-3 control-label required">Дата операции</label>
	            <div id="birthday2-cont" class="col-xs-5 input-group date">
	                <input type="hidden" name="birthday2" placeholder="Формат гггг-мм-дд" class="form-control col-xs-4" id="birthday2" value="2015-08-28">
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
	                        <input type="text" name="day" placeholder="ДД" class="form-control day"/>
	                        <input type="text" name="month" placeholder="ММ" class="form-control month"/>
	                        <input type="text" name="year" placeholder="ГГГГ" class="form-control year"/>
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
	            <div class="col-xs-4">
	            	<button type="button" style="margin-top:22px">Отменить операцию</button>
	            </div>
	        </div>        
        </div>
        
         
       
        <div class="accordion">
		    <div class="accordion-group">
		        <div class="accordion-heading">
		            <a href="#a1"  data-toggle="collapse" class="accordion-toggle" data-placement="right" title=""><strong>План операции</strong></a>
		        </div>
		        <div class="accordion-body collapse" id="a1" style="height: auto;"><div class="accordion-inner"></div></div>
		    </div>
		    <div class="accordion-group">
		        <div class="accordion-heading">
		            <a href="#a2"  data-toggle="collapse" class="accordion-toggle" data-placement="right" title=""><strong>Осмотр анестезиолога</strong></a>
		        </div>
		        <div class="accordion-body collapse" id="a2" style="height: auto;"><div class="accordion-inner"></div></div>
		    </div>
			<div class="accordion-group">
		        <div class="accordion-heading">
		            <a href="#a3"  data-toggle="collapse" class="accordion-toggle" data-placement="right" title=""><strong>Состав бригады</strong></a>
		        </div>
		        <div class="accordion-body collapse" id="a3" style="height: auto;"><div class="accordion-inner"></div></div>
		    </div>
			<div class="accordion-group">
		        <div class="accordion-heading">
		            <a href="#a4"  data-toggle="collapse" class="accordion-toggle" data-placement="right" title=""><strong>Протокол операции</strong></a>
		        </div>
		        <div class="accordion-body collapse" id="a4" style="height: auto;"><div class="accordion-inner"></div></div>
		    </div>		
			<div class="accordion-group">
		        <div class="accordion-heading">
		            <a href="#a5"  data-toggle="collapse" class="accordion-toggle" data-placement="right" title=""><strong>Отчёт анестезиолога</strong></a>
		        </div>
		        <div class="accordion-body collapse" id="a5" style="height: auto;"><div class="accordion-inner"></div></div>
		    </div>		
			<div class="accordion-group">
		        <div class="accordion-heading">
		            <a href="#a6"  data-toggle="collapse" class="accordion-toggle" data-placement="right" title=""><strong>Направление</strong></a>
		        </div>
		        <div class="accordion-body collapse" id="a6" style="height: auto;"><div class="accordion-inner"></div></div>
		    </div>		    	    	    		    
		</div>
	
	<script>InitDateControls();</script>
</div>

