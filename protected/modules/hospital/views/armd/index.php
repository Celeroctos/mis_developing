

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
            
            
            <table id="myPatientsGrid" class="table table-hover table-striped table-bordered">
            	<thead>
            		<tr><th>ФИО</th><th>Карта</th><th>Палата</th><th>Диагноз</th><th></th></tr>
            	</thead>
            	<tbody>
            	<?php 
            	$checkbox='<input disabled="disabled" type="checkbox"/>';
            	$checkbox2='<input disabled="disabled" checked="checked" type="checkbox"/>';
            	?>
            		<tr><td>Данилова Светлана Алексеевна</td><td>11346/14</td><td>2</td><td>Диабетическая нефропатия</td>
            		<td><?php echo $checkbox ?></td></tr>
            		<tr><td>Киселева Ульяна Алексеевна</td><td>10307/14</td><td>4</td><td>Гестационный сахарный диабет</td>
            		<td><?php echo $checkbox ?></td></tr>
            		<tr><td>Лихачева Нелли Романовна</td><td>21583/14</td><td>4</td><td>Аутоимунный тиреодит</td>
            		<td><?php echo $checkbox ?></td></tr>
            		<tr><td>Мацнева Оксана Евгеньевна</td><td>11653/14</td><td>5</td><td>Аутоимунный тиреодит</td>
            		<td><?php echo $checkbox ?></td></tr>
            		<tr><td>Острягина Марианна Михайловна</td><td>15881/14</td><td>5</td><td>Гестационный сахарный диабет</td>
            		<td><?php echo $checkbox ?></td></tr>
            		<tr><td>Потрясова Кристина Александровна</td><td>11608/14</td><td>6</td><td>Гестационный сахарный диабет</td>
            		<td><?php echo $checkbox2 ?></td></tr>
            		<tr><td>Работягова Светлана Анатольевна</td><td>16777/14</td><td>1</td><td>Гестационный сахарный диабет</td>
            		<td><?php echo $checkbox2 ?></td></tr>
            		<tr><td>Шевлягина Ольга Николаевна</td><td>12253/14</td><td>1</td><td>Диабетическая нефропатия</td>
            		<td><?php echo $checkbox2 ?></td></tr>
            	</tbody>
            </table>
            
            
            <!-- 
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader2.gif" width="256" height="30" alt="Загружается..." class="ajaxLoader" />
             -->
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="max-width:1100px;width:99%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Киселева Ульяна Алексеевна</h4>
      </div>
      <div class="modal-body">



<div class="contentForPatientGrid">
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
            <a href="#armd-patient-tests" aria-controls="armd-patient-tests" role="tab" data-toggle="tab" id="armdPatientTestsTab">Результаты обследования</a>
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
	
			<div id="patientPurposeList">
				<table  class="table table-hover table-striped table-bordered" style="margin-top:1px;">
	            		<tbody>
	            			<tr><td style="width:100px">31.07.2015</td><td>Тироксин</td></tr>
	            			<tr onclick="$('#patientPurposeList').hide();$('#patientPurposeExpander').show();"><td style="width:100px">31.07.2015</td><td>Левемир</td></tr>
	            	</tbody>
	            </table>  
				<table align="center">
					<thead>
						<tr><td colspan="2" align="center"><b>Назначение новых</b></td></tr>
					</thead>
					<tbody>
					<tr>
						<td><input type="button" value="Медикаментов" class="btn btn-success" id="addDrug"/></td>
						<td><input type="button" value="Процедур" class="btn btn-success" id="addProcedure" /></td>
					</tr>
					</tbody>
				</table>
            </div>
            
            <div id="patientPurposeExpander" class="no-display" >
            	<h3>Левемир</h3>
            	<table style="width:100%">
            		<tr><td style="width:60%">
            		
            			<table>
            				<tr><th style="width:150px">Медикамент:</th><td>Левемир</td></tr>
            				<tr><th>Способ применения:</th><td>2 раза в день</td></tr>
            				<tr><th>Дозировка:</th><td>10 ЕД на ночь, 6 ЕД утром</td></tr>
            				<tr><th>Выполнять до:</th><td>11.05.2015</td></tr>
            			</table>
            	
						</td>
						<td style="width:40%">
							<b>Комментарий:</b>
							<textarea style="width:100%;height:100px">
							
							</textarea>						
						</td>
					</tr>
				</table>
				<table align="center" style="margin-top:10px">
					<tr>
						<td><button class="btn btn-danger">Отменить рецепт</button></td>
						<td><button class="btn btn-success">Изменить рецепт</button></td>
						<td><button class="btn btn-default" onclick="$('#patientPurposeExpander').hide();$('#patientPurposeList').show();">Закрыть</button></td>
					</tr>
				</table>

				
				
				            	
            	
            	
            	
            	
            
            </div>  


                
			
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-directions">
            <table class="table table-hover table-striped table-bordered" style="margin-top:1px;">
            	<tbody>
            		<tr><td style="width:100px">29.07.2015</td><td>Биохимический анализ крови</td></tr>
            		<tr><td style="width:100px">29.07.2015</td><td>Общий анализ мочи</td></tr>
            		<tr><td style="width:100px">30.07.2015</td><td>Глюкоза плазмы натощак</td></tr>
            		<tr><td style="width:100px">30.07.2015</td><td>Глюкоза плазмы через 1 час после еды</td></tr>
            	</tbody>
            </table>            
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-tests">
            <table class="table table-hover table-striped table-bordered" style="margin-top:1px;">
            	<tbody>
            		<tr><td style="width:100px">29.07.2015</td><td>Биохимический анализ крови</td></tr>
            		<tr><td style="width:100px">29.07.2015</td><td>Общий анализ мочи</td></tr>
            	</tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-relocation">
            <div style="margin-top:20px">
            	<table style="width:100%">
            		<tr>
            			<td style="width:50%" align="center"><button style="width:100%;max-width:220px" class="btn btn-success">Выписка</button></td>
            			<td style="width:50%" align="center"><button style="width:100%;max-width:220px" class="btn btn-success">Перевод</button></td>
            		</tr>
            	</table>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-childbirth">
			<div style="margin-top:20px">
            	<table style="width:100%">
            		<tr>
            			<td style="width:50%" align="center"><button style="width:100%;max-width:220px" class="btn btn-success">Естественные роды</button></td>
            			<td style="width:50%" align="center"><button style="width:100%;max-width:220px" class="btn btn-success">Оперативное вмешательство</button></td>
            		</tr>
            	</table>
            </div>
        </div>
    </div>
</div>






      </div>
<!--       
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
 -->      
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

