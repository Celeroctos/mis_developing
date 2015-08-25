

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
        
     
     <div class="row">
    
    
    
    
<form class="form-horizontal col-xs-12 template-edit-form" role="form" id="yw0" action="/doctors/shedule/editpatient" method="post"><input id="medcardId" class="form-control" name="FormTemplateDefault[medcardId]" type="hidden"><input id="greetingId" class="form-control" name="FormTemplateDefault[greetingId]" type="hidden"><input id="templateName" class="form-control" value="Стационар. Осмотр" name="FormTemplateDefault[templateName]" type="hidden"><input id="templateId" class="form-control" value="71" name="FormTemplateDefault[templateId]" type="hidden">
    <div id="accordion_a71__130_189" class="accordion medcard-accordion">
    <div class="accordion-group">
        <div class="accordion-heading no-display" >
            <a href="#collapse_a71__130_189" data-parent="#accordion_a71__130_189" data-toggle="collapse" class="accordion-toggle">Дневник предрод                                    <div class="accordeonToggleAlt"> (Свернуть)</div>
                            </a>
                    </div>
        <div class="accordion-body in" id="collapse_a71__130_189">
        <div class="accordion-inner">
            <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.5',
                    'dependences' : {"list":[]},
                    'elementId' : '2422'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|5_2422">Состояние</label>        <select id="f__130|5_2422" class="form-control" placeholder="" title="ID 2422, путь 130.5" style="width: 150px;" name="FormTemplateDefault[f130|5_2422]">
<option value="56">Средней тяжести</option>
<option value="57">Тяжелое</option>
<option value="55">Удовлетворительное</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.10',
                    'dependences' : {"list":[]},
                    'elementId' : '2423'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|10_2423">Жалобы:</label>        <textarea id="f__130|10_2423" class="form-control" placeholder="" title="ID 2423, путь 130.10" style="width: 300px;" name="FormTemplateDefault[f130|10_2423]"></textarea>
                            </div>
                        <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.15',
                    'dependences' : {"list":[]},
                    'elementId' : '2424'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|15_2424">АД</label>        <input id="f__130|15_2424" class="form-control" placeholder="" title="ID 2424, путь 130.15" style="width: 100px;" name="FormTemplateDefault[f130|15_2424]" type="text">                <label class="control-label label-after"> мм рт ст</label>
            
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.20',
                    'dependences' : {"list":[]},
                    'elementId' : '2425'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|20_2425">Пульс</label>        <input id="f__130|20_2425" class="form-control" placeholder="" title="ID 2425, путь 130.20" style="width: 100px;" name="FormTemplateDefault[f130|20_2425]" type="text">                <label class="control-label label-after"> в 1 мин.</label>
            
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.25',
                    'dependences' : {"list":[]},
                    'elementId' : '2426'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|25_2426">Дыхание</label>        <select id="f__130|25_2426" class="form-control" placeholder="" title="ID 2426, путь 130.25" style="width: 150px;" name="FormTemplateDefault[f130|25_2426]">
<option value="828">Везикулярное </option>
<option value="830">Жесткое</option>
<option value="829">Ослабленное</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.30',
                    'dependences' : {"list":[]},
                    'elementId' : '2427'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|30_2427">Хрипы</label>        <select id="f__130|30_2427" class="form-control" placeholder="" title="ID 2427, путь 130.30" style="width: 150px;" name="FormTemplateDefault[f130|30_2427]">
<option value="832">Влажные рассеянные </option>
<option value="834">Влажные слева</option>
<option value="833">Влажные справа</option>
<option value="831">Нет</option>
<option value="1644">Сухие при форсированном выдохе</option>
<option value="835">Сухие рассеянные </option>
<option value="837">Сухие слева</option>
<option value="836">Сухие справа</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.35',
                    'dependences' : {"list":[]},
                    'elementId' : '2428'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|35_2428">Кожные&nbsp;покровы&nbsp;и&nbsp;слизистые&nbsp;оболочки</label>                    <select id="f__130|35_2428" class="form-control" placeholder="" title="ID 2428, путь 130.35" style="width: 200px;" name="FormTemplateDefault[f130|35_2428]">
<option value="2962">обычной окраски, чистые</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>                            <button type="button" id="ba__130|35_2428" class="btnAddValue btn btn-default btn-sm" disabled="">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            
                            </div>
                        <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.45',
                    'dependences' : {"list":[]},
                    'elementId' : '2429'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|45_2429">Матка</label>                    <select id="f__130|45_2429" class="form-control" placeholder="" title="ID 2429, путь 130.45" style="width: 250px;" name="FormTemplateDefault[f130|45_2429]">
<option value="2988">возбудима при пальпации</option>
<option value="3330">в физиологическом тонусе</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>                            <button type="button" id="ba__130|45_2429" class="btnAddValue btn btn-default btn-sm" disabled="">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.50',
                    'dependences' : {"list":[]},
                    'elementId' : '2430'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|50_2430">,</label>                    <select id="f__130|50_2430" class="form-control" placeholder="" title="ID 2430, путь 130.50" style="width: 250px;" name="FormTemplateDefault[f130|50_2430]">
<option value="2989">безболезненна во всех отделах</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>                            <button type="button" id="ba__130|50_2430" class="btnAddValue btn btn-default btn-sm" disabled="">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.55',
                    'dependences' : {"list":[]},
                    'elementId' : '2431'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|55_2431">Регулярная&nbsp;родовая&nbsp;деятельность</label>        <input id="f__130|55_2431" class="form-control" placeholder="" title="ID 2431, путь 130.55" style="width: 200px;" name="FormTemplateDefault[f130|55_2431]" type="text">
                            </div>
                        <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.60',
                    'dependences' : {"list":[]},
                    'elementId' : '2432'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|60_2432">Шевеления&nbsp;плода</label>        <select id="f__130|60_2432" class="form-control" placeholder="" title="ID 2432, путь 130.60" style="width: 150px;" name="FormTemplateDefault[f130|60_2432]">
<option value="122">не ощущает </option>
<option value="123">ощущает</option>
<option value="2984">ощущает хорошо</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.65',
                    'dependences' : {"list":[]},
                    'elementId' : '2433'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|65_2433">Положение&nbsp;плода</label>        <select id="f__130|65_2433" class="form-control" placeholder="" title="ID 2433, путь 130.65" style="width: 200px;" name="FormTemplateDefault[f130|65_2433]">
<option value="67">косое</option>
<option value="66">поперечное</option>
<option value="65">продольное</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.70',
                    'dependences' : {"list":[]},
                    'elementId' : '2434'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|70_2434">предлежание</label>        <select id="f__130|70_2434" class="form-control" placeholder="" title="ID 2434, путь 130.70" style="width: 150px;" name="FormTemplateDefault[f130|70_2434]">
<option value="80">головное</option>
<option value="1467">нет</option>
<option value="79">тазовое</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.75',
                    'dependences' : {"list":[]},
                    'elementId' : '2435'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|75_2435">Головка</label>                    <select id="f__130|75_2435" class="form-control" placeholder="" title="ID 2435, путь 130.75" style="width: 200px;" name="FormTemplateDefault[f130|75_2435]">
<option value="1883">над входом в малый таз</option>
<option value="1884">прижата ко входу в малый таз</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>                            <button type="button" id="ba__130|75_2435" class="btnAddValue btn btn-default btn-sm" disabled="">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.80',
                    'dependences' : {"list":[]},
                    'elementId' : '2436'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|80_2436">Сердцебиение</label>        <select id="f__130|80_2436" class="form-control" placeholder="" title="ID 2436, путь 130.80" style="width: 150px;" name="FormTemplateDefault[f130|80_2436]">
<option value="125">не выслушивается</option>
<option value="1471">приглушено, ритмичное</option>
<option value="124">ясное, ритмичное</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.85',
                    'dependences' : {"list":[]},
                    'elementId' : '2437'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|85_2437">ЧСС</label>        <input id="f__130|85_2437" class="form-control" placeholder="" title="ID 2437, путь 130.85" style="width: 100px;" name="FormTemplateDefault[f130|85_2437]" type="text">                <label class="control-label label-after"> в 1 мин</label>
            
                            </div>
                        <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.90',
                    'dependences' : {"list":[]},
                    'elementId' : '2438'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|90_2438">Выделения&nbsp;из&nbsp;половых&nbsp;путей</label>                    <select id="f__130|90_2438" class="form-control" placeholder="" title="ID 2438, путь 130.90" style="width: 300px;" name="FormTemplateDefault[f130|90_2438]">
<option value="2986">подтекают светлые околоплодные воды</option>
<option value="2985">светлые, слизистые</option>
<option value="-3">...</option>
<option value="" selected="selected">Не выбрано</option>
</select>                            <button type="button" id="ba__130|90_2438" class="btnAddValue btn btn-default btn-sm" disabled="">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            
                            </div>
                        <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.95',
                    'dependences' : {"list":[]},
                    'elementId' : '2439'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|95_2439">Физиологические&nbsp;отправления:</label>        <textarea id="f__130|95_2439" class="form-control" placeholder="" title="ID 2439, путь 130.95" style="width: 200px;" name="FormTemplateDefault[f130|95_2439]"></textarea>
                                <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.100',
                    'dependences' : {"list":[]},
                    'elementId' : '2440'
                });
            </script>
                <label class="control-label label-before " for="FormTemplateDefault_f130|100_2440">Отеки:</label>        <select id="f__130|100_2440" class="form-control" placeholder="" multiple="multiple" title="ID 2440, путь 130.100" style="width: 150px;" name="FormTemplateDefault[f130|100_2440][]">
<option value="825">анасарка</option>
<option value="823">голеней</option>
<option value="824">до бедра</option>
<option value="821">кистей</option>
<option value="1436">на лице</option>
<option value="827">нет</option>
<option value="1462">передней брюшной стенки</option>
<option value="822">стоп</option>
<option value="-3">...</option>
</select>
                            </div>
                        <script type="text/javascript">
                globalVariables.elementsDependences.push({
                    'path' : '130.105',
                    'dependences' : {"list":[]},
                    'elementId' : '2441'
                });
            </script>
                            <div class="form-group col-xs-12">
        <label class="control-label label-before " for="FormTemplateDefault_f130|105_2441">Дополнительно:</label>        <textarea id="f__130|105_2441" class="form-control" placeholder="" title="ID 2441, путь 130.105" style="width: 400px;" name="FormTemplateDefault[f130|105_2441]"></textarea>
                            </div>
            </div>
</div>
</div>
</div>
<div class="form-group submitEditPatient">
	<input class="templateContentSave" type="submit" name="yt0" value="Сохранить" id="yt0"></div>

</form>    
    
     
     <table align="center">
     	<tr>
     		<td><button class="btn btn-success">Сохранить</button></td>
     		<td><button class="btn btn-default">Отмена</button></td>
     	</tr>
     </table>     
            
            
	</div>
            
            
            
            
            
        
        
        <!--  
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
            -->
        </div>
        <div role="tabpanel" class="tab-pane" id="armd-patient-diagnosis">
        
        
        
        
        
        
						<form class="form-horizontal col-xs-12" role="form" id="diagnosis-form" action="/doctors/shedule/view" method="post">
                            
                            
                            <div class="form-group">
                                <label for="doctor" class="col-xs-3 control-label">Предварительный клинический диагноз</label>
                                <div class="col-xs-9">
                                    <textarea placeholder="" class="form-control" id="diagnosisNote"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group chooser no-display" id="primaryClinicalDiagnosisChooser">
                                <label for="doctor" class="col-xs-3 control-label">Клинический основной
                                    диагноз:</label>
                                <div class="col-xs-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="clinicalPrimaryDiagnosis" placeholder="Начинайте вводить...">
                                        <span class="input-group-addon glyphicon glyphicon-plus"></span>
                                    </div>
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>
                            <div class="form-group chooser" id="secondaryClinicalDiagnosisChooser">
                                <label for="doctor" class="col-xs-3 control-label"><!--Клинические
                                    диагноз / диагнозы:--></label>
                                <div class="col-xs-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="clinicalSecondaryDiagnosis" placeholder="Начинайте вводить...">
                                        <span class="input-group-addon glyphicon glyphicon-plus"></span>
                                    </div>
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            
							<div class="form-group">
                                <label for="doctor" class="col-xs-3 control-label">Клинический диагноз</label>
                                <div class="col-xs-9">
                                    <textarea placeholder="" class="form-control" id="diagnosisNote">Гестационный сахарный диабет</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group chooser no-display" id="primaryClinicalDiagnosisChooser">
                                <label for="doctor" class="col-xs-3 control-label">Клинический основной
                                    диагноз:</label>
                                <div class="col-xs-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="clinicalPrimaryDiagnosis" placeholder="Начинайте вводить..." >
                                        <span class="input-group-addon glyphicon glyphicon-plus"></span>
                                    </div>
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>
                            <div class="form-group chooser" id="secondaryClinicalDiagnosisChooser">
                                <label for="doctor" class="col-xs-3 control-label"><!--Клинические
                                    диагноз / диагнозы:--></label>
                                <div class="col-xs-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="clinicalSecondaryDiagnosis" placeholder="Начинайте вводить...">
                                        <span class="input-group-addon glyphicon glyphicon-plus"></span>
                                    </div>
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>

                            <div class="form-group chooser" id="primaryDiagnosisChooser">
                            <label for="doctor" class="col-xs-3 control-label">Основной диагноз по МКБ-10:</label>
                                <div class="col-xs-9">
                                    <input type="text" class="form-control" id="doctor" placeholder="Начинайте вводить...">
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>
                            <div class="form-group chooser" id="complicationsDiagnosisChooser">
                                <label for="doctor" class="col-xs-3 control-label">Осложнения основного диагноза по МКБ-10:</label>
                                <div class="col-xs-9">
                                    <input type="text" class="form-control" id="doctor" placeholder="Начинайте вводить...">
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>
                            <div class="form-group chooser" id="secondaryDiagnosisChooser">
                            	<label for="doctor" class="col-xs-3 control-label">Сопутствующие диагнозы по МКБ-10:</label>
                                <div class="col-xs-9">
                                    <input type="text" class="form-control" id="doctor" placeholder="Начинайте вводить...">
                                    <ul class="variants no-display">
                                    </ul>
                                    <div class="choosed">
                                                                            </div>
                                </div>
                            </div>
				</form>
				
				<table align="center">
					<tbody>
						<tr>
						<td><button class="btn btn-success">Сохранить</button></td>
						<td><button class="btn btn-default">Отмена</button></td>
						</tr>
					</tbody>
				</table>        
        
        
        
        
        
        
        
        </div>
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
            <table align="center">
            	<tbody>
            		<tr>
            			<td><button class="btn btn-success">На анализы</button></td>
            			<td><button class="btn btn-success">На обследование</button></td>
            			<td><button class="btn btn-success">На операцию</button></td>
            		</tr>
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

