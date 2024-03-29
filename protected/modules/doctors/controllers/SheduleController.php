<?php
class SheduleController extends Controller {
//    public $layout = 'index';
    public $filterModel = null;
    public $currentPatient = false;
    public $currentSheduleId = false;
    public $currentDoctorId = null;

    /* Календарь */
    public $currentDay = null;
    public $currentYear = null;
    public $currentMonth = null;

    /* */
    public $cabinetsIds = array(); // Сборщик id кабинетов во время формирования расписания
    public $restDays = array(); // Выходные дни для расписания врача вместе с причиной
    public $returnFacts = false; // Возращать факты или нет

    public function actionView() {
        $medcardRecordId = 0;
        if(Yii::app()->user->getState('currentGreetingsDoctor', -1) == -1) {
            $doctorId = Yii::app()->user->doctorId;
            Yii::app()->user->setState('currentGreetingsDoctor', $doctorId);
        } else {
            $doctorId = Yii::app()->user->getState('currentGreetingsDoctor');
        }
        $doctor = Doctor::model()->findByPk($doctorId);

        if(isset($_GET['cardid']) && trim($_GET['cardid']) != '') {

            // Проверим, есть ли такая медкарта вообще
            $medcardFinded = Medcard::model()->findByPk($_GET['cardid']);
            if($medcardFinded != null) {
                $medcardRecordId = MedcardElementForPatient::getMaxRecordId($_GET['cardid']) + 1;
                $this->currentPatient = trim($_GET['cardid']);
                $medcardModel = new Medcard();
                $medcard = $medcardModel->getOne($this->currentPatient);

                // Вычисляем количество лет
                $dateFormatter = new DateFormatterMis($medcard['birthday']);
                $medcard['full_years'] = $dateFormatter->getFullAge();

                $patientController = Yii::app()->createController('reception/patient');
                $addressData = $patientController[0]->getAddressStr($medcard['address'], true);
                $medcard['address'] = $addressData['addressStr'];
            }
            if(isset($_GET['rowid']) && trim($_GET['rowid']) != '') {
                $this->currentSheduleId = trim($_GET['rowid']);
                $greeting = SheduleByDay::model()->findByPk($_GET['rowid']);
                $this->currentDoctorId = $greeting->doctor_id;

                if($greeting != null && $greeting->order_number != null) {
                    $openedTab = 1; // Живая очередь
                } else {
                    $openedTab = 0; // Обычная запись
                }
                // Здесь проверим: если текущий врач не совпадает с врачом пациента, не показывать экран с инфой и выбором шаблонов
                if($greeting->doctor_id != $doctorId) {
                    $this->currentPatient = false;
                    $openedTab = 0;
                }

                if(isset($_POST['templatesList']) && $greeting->doctor_id == $doctorId) {
                    // Шаблоны выбраны. Нужно их обработать.
                    $templatesChoose = 0;
                    // Установленные диагнозы: первичный и сопутствующие. Это может быть просмотр приёма, который уже был, типа
                    $primaryDiagnosis = PatientDiagnosis::model()->findDiagnosis($_GET['rowid'], 0);
                    $secondaryDiagnosis = PatientDiagnosis::model()->findDiagnosis($_GET['rowid'], 1);
                    $complicatingDiagnosis = PatientDiagnosis::model()->findDiagnosis($_GET['rowid'], 2);
                    $primaryClinicalDiagnosis = ClinicalPatientDiagnosis::model()->findDiagnosis($_GET['rowid'], 0);
                    $secondaryClinicalDiagnosis = ClinicalPatientDiagnosis::model()->findDiagnosis($_GET['rowid'], 1);

                    $medcardTemplates = new MedcardTemplate();
                    $referenceTemplatesList =  $medcardTemplates->getTemplatesByEmployee(Yii::app()->user->medworkerId, 1);
                    // Если приём был, то можно вынуть примечание к диагнозам
                    if($greeting != null) {
                        $note = $greeting->note;
                        // Пациента начали принимать, но он не принят: карту можно редактировать. В противном случае редактирование карты должно быть заблокировано
                        if($greeting->is_beginned != 1) {
                            $greeting->is_beginned = 1;
                            $greeting->time_begin = date('h:j');
                            if(!$greeting->save()) {
                                echo CJSON::encode(array('success' => true,
                                    'text' => 'Ошибка сохранения записи.'));
                            }
                        }

                        if($greeting->is_beginned && !$greeting->is_accepted) {
                            $canEditMedcard = 1;
                        } else {
                            $canEditMedcard = 0;
                        }
                    }

                    $templatesListWithTemplateData = array();
                    $currentRequiredDiagnosis = array();
                    foreach($_POST['templatesList'] as $key => $id) {
                        $templModel = MedcardTemplate::model()->findByPk($id);
                        $templatesListWithTemplateData[] = $templModel;
                        $currentRequiredDiagnosis ['t'.$id] = array(
                            'name' => $templModel->name,
                            'isReq' => $templModel->primary_diagnosis
                        );
                    }

                    usort($templatesListWithTemplateData, function($template1, $template2) {
                        if($template1->index > $template2->index) {
                            return 1;
                        } elseif($template1->index < $template2->index) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                    $templatesList = $templatesListWithTemplateData;

                    // Отсортируем шаблончики рекоммендаций
                    usort($referenceTemplatesList, function($template1, $template2) {
                        if($template1['index'] > $template2['index']) {
                            return 1;
                        } elseif($template1['index'] < $template2['index']) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });

                    //var_dump ($templatesList);
                    //exit();
                } elseif($greeting->doctor_id == $doctorId) {
                    $canEditMedcard = 0;
                    $templatesChoose = 1;
                    // Получим должность пользователя
                    $medworkerId = Yii::app()->user->medworkerId;
                    // Получим разрешённые для него шаблоны
                    $medcardTemplates = new MedcardTemplate();
                    $templatesList = $medcardTemplates->getTemplatesByEmployee($medworkerId);

                    // Отсортируем шаблоны по порядку
                    usort($templatesList, function($template1, $template2) {
                        if($template1['index'] > $template2['index']) {
                            return 1;
                        } elseif($template1['index']< $template2['index']) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                    // Нужно получить шаблоны, которые были выбраны раньше в приёме (если были выбраны)
                    $medcardRecordObj = new MedcardRecord();
                    $oldTemplatesForGreeting = $medcardRecordObj->getSavedTemplatesForGreeting($_GET['rowid']);

                    $oldRequiredDiagnosis = array();
                    foreach($oldTemplatesForGreeting as $oneTemplate) {
                        $oldRequiredDiagnosis['t'.$oneTemplate['id']] = array(
                            'name' => $oneTemplate['name'],
                            'isReq' => $oneTemplate['primary_diagnosis']
                        );
                    }
                }



                // Теперь нужно смиксовать в итоговый массив шаблонов массивы текущие и массивы старых приёмов
                if ((isset($currentRequiredDiagnosis)) || ( isset($oldRequiredDiagnosis) ))
                {
                    $requiredDiagnosis = array();
                    // Перебираем текущие шаблоны (выбранные)
                    if (isset($currentRequiredDiagnosis))
                    {
                        // Перебираем старые шаблоны
                        foreach($currentRequiredDiagnosis as $key => $oneTemplate )
                        {
                            $requiredDiagnosis[$key] = $oneTemplate ;
                        }
                    }
                    // Перебираем старые шаблоны
                    if (isset($oldRequiredDiagnosis))
                    {
                        foreach($oldRequiredDiagnosis as $key => $oneTemplate )
                        {
                            if (!isset($requiredDiagnosis[$key]))
                            {
                                $requiredDiagnosis[$key] = $oneTemplate ;
                            }
                        }
                    }

                    // Отсортируем шаблоны по порядку
                    usort($templatesList, function($template1, $template2) {
                        if($template1['index'] > $template2['index']) {
                            return 1;
                        } elseif($template1['index']< $template2['index']) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });

                }

            }
        }
        if(!isset($templatesChoose)) {
            $templatesChoose = 0;
        }

        // Если они не создались, это значит, что диагнозы пустые
        if(!isset($primaryDiagnosis, $secondaryDiagnosis,$primaryClinicalDiagnosis, $secondaryClinicalDiagnosis,$complicatingDiagnosis)) {
            $primaryDiagnosis = array();
            $secondaryDiagnosis = array();
            $primaryClinicalDiagnosis = array();
            $secondaryClinicalDiagnosis = array();
            $complicatingDiagnosis = array();
        }

        $this->filterModel = new FormSheduleFilter();

        $patientsInCalendar = CJSON::encode($this->getDaysWithPatients($doctorId));
        $curDate = $this->getCurrentDate();

        $parts = explode('-', $curDate);
        $curDate = $parts[2].'.'.$parts[1].'.'.$parts[0];

        if(isset($openedTab) && $openedTab == 1) {
            $onlyWaitingLine = 1;
        } else {
            $onlyWaitingLine = 0;
        }



        $timeTable = new Timetable();
        $shedule = $timeTable->getRows(
            array(
                'doctorsIds' => array($doctor['id']),
                'dateBegin' => $curDate,
                'dateEnd' => $curDate
            )
        );

        $patients = null;
        if ( count($shedule)==0 ) {
            $patients = $this->getPatientListWOTimeStamp($doctor['id'], $curDate, false, $onlyWaitingLine);
            $patients = $patients['result'];
        } else {
            $ruleToApply = $this->checkByTimetable($shedule[0], $curDate);
            $patients = $this->getPatientList($doctor['id'], $curDate,$ruleToApply['greetingBegin'] ,$ruleToApply['greetingEnd'], false, $onlyWaitingLine);
            $patients = $patients['result'];
        }

        $doctorComment = CommentOms::getTopComment(isset($medcard) ? $medcard : null);
        $doctorNumberComments = count(CommentOms::getComments(isset($medcard) ? $medcard : null));

        // Список врачей
        $doctorsList = array('-1' => 'Я');
        $filterDoctorForm = new FormFilterDoctor();
        if (Yii::app()->user->checkAccess('canChangeDoctor')) {
            $doctorsListDb = Doctor::model()->getRows(false, 'last_name, first_name', 'asc');
            foreach($doctorsListDb as $value) {
                if($value['last_name'] == null) {
                    $value['middle_name'] = '';
                }
                if($value['tabel_number'] == null) {
                    $value['tabel_number'] = 'отсутствует';
                }

                $doctorsList[(string)$value['id']] = $value['last_name'].' '.$value['first_name'].' '.$value['middle_name'].', '.$value['post'].', '.$value['ward'].', табельный номер '.$value['tabel_number'];
            }
        }

        asort($doctorsList);

        // Режим медсестры: принимающий доктор может не совпадать с реальным
        if(Yii::app()->user->getState('currentGreetingsDoctor', -1) == -1) {
            $userId = Yii::app()->user->id;
            $doctor = User::model()->findByPk($userId);
            $currentDoctorId = $doctor['employee_id'];
        } else {
            $currentDoctorId = Yii::app()->user->getState('currentGreetingsDoctor');
        }
		
        $this->render('index', array(
            'patients' => $patients,
            'patientsInCalendar' => $patientsInCalendar,
            'currentPatient' => $this->currentPatient,
            'currentSheduleId' => $this->currentSheduleId,
            'currentDoctorId' =>  $doctorId,
            'pregnantContent' => '',
            'filterModel' => $this->filterModel,
            'medcard' => isset($medcard) ? $medcard : null,
            'currentDate' => $curDate,
            'year' => $parts[0],
            'month' => $parts[1],
            'day' => $parts[2],
            'addModel' => new FormValueAdd(),
            'addCommentModel' => new FormCommentAdd(),
            'historyPoints' => $this->getHistoryPoints(isset($medcard) ? $medcard : null),
            'doctorComment' => $doctorComment,
            'numberDoctorComments' => $doctorNumberComments,
            'primaryDiagnosis' => $primaryDiagnosis,
            'secondaryDiagnosis' => $secondaryDiagnosis,
            'complicatingDiagnosis' => $complicatingDiagnosis,
            'primaryClinicalDiagnosis' => $primaryClinicalDiagnosis,
            'secondaryClinicalDiagnosis' => $secondaryClinicalDiagnosis,
            'note' => isset($note) ? $note : '',
            'canEditMedcard' => isset($canEditMedcard) ? $canEditMedcard : 0,
            'privilegesList' => $this->getPrivileges(),
            'modelMedcard' => new FormPatientWithCardAdd(),
            'modelOms' => new FormOmsEdit(),
            'currentTime' => date('Y-m-d h:m:s'),
            'templatesChoose' => $templatesChoose,
            'templatesList' => isset($templatesList) ? $templatesList : array(),
            'referenceTemplatesList' => isset($referenceTemplatesList) ? $referenceTemplatesList : array(),
            'greeting' => (isset($greeting)) ? $greeting : null,
            'requiredDiagnosis' => isset($requiredDiagnosis) ? $requiredDiagnosis : array(),
            'medcardRecordId' => $medcardRecordId,
            'templateModel' =>  new  FormTemplateDefault(),
            'openedTab' => isset($openedTab) ? $openedTab : 0,
            'doctorsList' => $doctorsList,
            'modelDoctorFilter' => $filterDoctorForm,
            'currentGreetingsDoctor' => $currentDoctorId
        ));
    }

    public function actionGetParamHistory() {
        if(!Yii::app()->request->getIsAjaxRequest() || !isset($_GET['element']) || !isset($_GET['medcard'])) {
            echo CJSON::encode(array(
                'success' => false
            ));
            exit();
        }

        list($preKey, $prefix, $undottedPath, $elementId) = explode('_', $_GET['element']);
        $dottedPath = implode('.', explode('|', $undottedPath));
        $historyElements = MedcardElementForPatient::model()->findAll(
            'element_id = :element_id
			AND medcard_id = :medcard_id
			AND path = :path
			ORDER BY change_date ASC',
            array(
                ':element_id' => $elementId,
                ':medcard_id' => $_GET['medcard'],
                ':path' => $dottedPath
            )
        );
        $answer = array();
        foreach($historyElements as $element) {
            if($element['greeting_id'] == $_GET['greetingId']) {
                //continue;
            }

            if($element['type'] == 2 || $element['type'] == 3) {
                $value = MedcardGuideValue::model()->findByPk($element['value']);
                if($value != null) {
                    if($value == -1) {
                        $element['value'] = 'Не выбрано';
                    } else {
                        $element['value'] = $value->value;
                    }
                } else {
                    $element['value'] = 'Не выбрано';
                }
            }
            $temp = array(
                'change_date' => $element['change_date'],
                'value' => $element['value'],
                'type' => $element['type']
            );
            $answer[] = $temp;
        }

        echo CJSON::encode(array(
            'success' => true,
            'data' => $answer
        ));
    }

    public function actionGetPrimaryDiagnosis() {
        if(!isset($_GET['greeting_id'])) {
            echo CJSON::encode(array(
                'success' => false,
                'text' => 'Не хватает данных о приёме!'
            ));
            exit();
        }
        $primaryDiagnosis = PatientDiagnosis::model()->findDiagnosis($_GET['greeting_id'], 0);
        echo CJSON::encode(array(
            'success' => true,
            'data' => count($primaryDiagnosis)
        ));
    }

    public function actionUpdatePatientList() {
        $this->filterModel = new FormSheduleFilter();
        // Получим дату, на которую нужны пациенты
        $curDateRaw = $this->getCurrentDate();
        $parts = explode('-', $curDateRaw);
        $curDate = $parts[2].'.'.$parts[1].'.'.$parts[0];
        // Получим доктора
        if(!isset($_POST['currentDoctor']) || $_POST['currentDoctor'] == -1) {
            // Если не занесен в сессию конкретный доктор, то, значит, берём текущего пользователя
            if(Yii::app()->user->getState('currentGreetingsDoctor', -1) == -1 || $_POST['currentDoctor'] == -1) {
                $doctorId = Yii::app()->user->doctorId;
            } else {
                $doctorId = Yii::app()->user->getState('currentGreetingsDoctor');
            }
            $doctor = Doctor::model()->findByPk($doctorId);
        } else {
            $doctor = Doctor::model()->findByPk($_POST['currentDoctor']);
            // Проверка, что такой врач вообще есть
            if($doctor != null) {
                $doctorId = $doctor['id'];
            } else {
                $doctorId = Yii::app()->user->doctorId;
                $doctor = Doctor::model()->findByPk($doctorId);
            }
        }

        Yii::app()->user->setState('currentGreetingsDoctor', $doctorId);

        if(isset($_POST['onlywaitinglist']) && $_POST['onlywaitinglist'] == 1) {
            $onlyWaitingLine = true;
        } else {
            $onlyWaitingLine = false;
        }

        $timeTable = new Timetable();
        $shedule = $timeTable->getRows(
            array(
                'doctorsIds' => array($doctor['id']),
                'dateBegin' => $curDate,
                'dateEnd' => $curDate
            )
        );

        $patients = null;
        if (count($shedule)==0)  {
            $patients = $this->getPatientListWOTimeStamp($doctor['id'], $curDate,false, $onlyWaitingLine);
            $patients = $patients['result'];
        } else {
            $ruleToApply = $this->checkByTimetable($shedule[0], $curDate);
            $patients = $this->getPatientList($doctor['id'], $curDate,$ruleToApply['greetingBegin'],$ruleToApply['greetingEnd'], false, $onlyWaitingLine);
            $patients = $patients['result'];
        }

        // Создадим сам виджет
        $patientsListWidget = $this->createWidget('application.modules.doctors.components.widgets.PatientListWidget');
        $patientsListWidget->filterModel = $this->filterModel;
        $patientsListWidget->isWaitingLine = $onlyWaitingLine;

        // Тащим из поста текущего пациента и текущий приём
        $greeting = false;
        $medcard = false;
        if (isset($_POST['currentPatient'])){
            if ($_POST['currentPatient']!='') {
                $medcard = $_POST['currentPatient'];
            }
        } if (isset($_POST['currentGreeting'])) {
            if ($_POST['currentGreeting']!='') {
                $greeting = $_POST['currentGreeting'];
            }
        }


        if(isset($_POST['onlywaitinglist']) && $_POST['onlywaitinglist'] == 1) {
            $patientsListWidget->isWaitingLine = true;
            $patientsListWidget->tableId = 'doctorWaitingList';
        } else {
            $patientsListWidget->tableId = 'doctorPatientList';
        }

        // Теперь получаем html-ку со списком пациентов
        $result = $patientsListWidget->getPatientList(
            $patients,
            $greeting,
            $medcard,
            $curDateRaw
        );
        ob_end_clean();
        echo CJSON::encode(array(
            'success' => true,
            'data' => $result
        ));
    }


    // Получить список льгот
    private function getPrivileges() {
        // Льготы
        $privilegeModel = new Privilege();
        $privilegesList = array('-1' => 'Нет');

        $privilegesListDb = $privilegeModel->getRows(false);
        foreach($privilegesListDb as $privilege) {
            $privilegesList[$privilege['id']] = $privilege['name'].' (Код '.$privilege['code'].')';
        }
        return $privilegesList;
    }

    public function actionGetHistoryPoints($medcardid) {
        $medcard = Medcard::model()->findByPk($medcardid);
        if($medcard == null) {
            echo CJSON::encode(array('success' => false,
                'error' => 'Не хватает данных для получения точек истории медкарты!'));
            exit();
        }
        $historyPoints = $this->getHistoryPoints($medcard);
        echo CJSON::encode(array('success' => true,
            'data' => $historyPoints));
    }

    // Получить точки истории для медкарты
    private function getHistoryPoints($medcard) {
        if($medcard == null) {
            return array();
        }

        $historyPoints = MedcardElementForPatient::model()->getHistoryPoints($medcard);

        foreach ($historyPoints  as &$historyEl) {
            $historyDateTimeArr = explode(' ', $historyEl['date_change']);
            $historyDateArr= explode('-', $historyDateTimeArr [0]);

            $historyEl['date_change'] =	$historyDateArr[2].'.'
                .$historyDateArr[1].'.'
                .$historyDateArr[0].' '.$historyDateTimeArr[1] ;
        }

        return $historyPoints;
    }

    // Возвращает html c самым позднем комментарием для карты
    public function actionUpdateTopComment()  {

    }

    public function actionGetAllPatientComments($cardId)
    {
        // Прочитаем все комментарии
        // По номеру карточки найдём номер полиса
        $medcard = Medcard::model()->find('card_number = :card',array(':card'=>$cardId));
        $omsNumber = $medcard['policy_id'];

        // По номеру ОМС ищем все комментарии
        $onePatientComments = CommentOms::getCommentsByPoliceId($omsNumber);
        // Обработаем все комментарии
        foreach ($onePatientComments as &$oneComment)
        {
            CommentOms::treatComment($oneComment);
        }

        // Вызовем рендеринг
        $commentsListWidget = $this->createWidget('application.modules.doctors.components.widgets.allCommentsBlock');
        ob_end_clean();
        $result = $commentsListWidget->getCommentsList($onePatientComments);

        echo CJSON::encode(array('success' => true,
            'data' => $result
        ));
    }

    // Получить даты, в которых у врача есть пациенты
    private function getDaysWithPatients($doctorId) {
        $shedule = new SheduleByDay();
        return $shedule->getDaysWithPatients($doctorId);
    }

    public function actionRefreshDaysWithPatients() {
        if(Yii::app()->user->getState('currentGreetingsDoctor', -1) == -1) {
            $userId = Yii::app()->user->id;
            $doctor = User::model()->findByPk($userId);
            $doctorId = $doctor['employee_id'];
        } else {
            $doctorId = Yii::app()->user->getState('currentGreetingsDoctor');
        }

        $patientsInCalendar = CJSON::encode($this->getDaysWithPatients($doctorId));

        echo CJSON::encode(array('success' => true,
            'data' =>  $patientsInCalendar
        ));

    }

    // Получить текущую дату
    private function getCurrentDate() {
        if(!isset($_POST['FormSheduleFilter']['date']) && !isset($_GET['date'])) {
            $date = date('Y-m-d');
            $this->filterModel->date = $date;
        } else {
            if(isset($_POST['FormSheduleFilter'])) {
                $this->filterModel->attributes = $_POST['FormSheduleFilter'];
            } else {
                $this->filterModel->date = $_GET['date'];
            }

            if($this->filterModel->validate()) {
                $date = $this->filterModel->date;
            } else {
                $date = date('Y-m-d');
            }
        }
        return $date;
    }

    private function stepToNextState($historyCategorieElement, $value, $recordId ) {
        $historyCategorieElement['value'] = $value;
        $historyCategorieElement['history_id'] = $historyCategorieElement['history_id'] + 1;
        $historyCategorieElement['is_record'] = 1;
        $historyCategorieElement['record_id'] = $recordId + 1;
        $historyCategorieElement['change_date'] = date('Y-m-d H:i');
    }

    // Редактирование данных пациента
    public function actionPatientEdit(){
        // Метод работает так: Сначала прочитываем из формы ид тех элементов, которые правятся в результате приёма.
        //  Затем с помощью условия WHERE IN и id-шников из формы, они считываются сразу одним запросом,
        //    создаётся из них ассоциативный массив.
        //     После этого перебираются снова поля из формы, для каждого поля выбирается его старое значение из того
        //    ассоциативного массива, который мы создали на первом этапе
        if (!isset($_POST['FormTemplateDefault'])) {
            ob_end_clean();
            echo CJSON::encode(array('success' => false,
                'text' => 'Ошибка запроса.'));
        }

        $master = new TemplateCloneMaster(
            $_POST['FormTemplateDefault']['medcardId'],
            $_POST['FormTemplateDefault']['greetingId'],
            $_POST['FormTemplateDefault']['templateId']
        );
        $master->cloneTemplateElements($_POST['FormTemplateDefault']);

//      $transaction = Yii::app()->db->beginTransaction();

        // Ищем recordId
        $recordId = MedcardElementForPatient::getMaxRecordId(
            $_POST['FormTemplateDefault']['medcardId']
        );
        $user = User::model()->find('id=:id', array(':id' => Yii::app()->user->id));

        $recordRow = new MedcardRecord();
        $recordRow ->doctor_id = $user['employee_id'];
        $recordRow ->medcard_id = $_POST['FormTemplateDefault']['medcardId'];
        $recordRow ->greeting_id = $_POST['FormTemplateDefault']['greetingId'];
        $recordRow ->template_name = $_POST['FormTemplateDefault']['templateName'];
        $recordRow ->template_id = $_POST['FormTemplateDefault']['templateId'];
        $recordRow ->record_id = $recordId+1;
        $recordRow ->record_date =  date('Y-m-d H:i');

        // Для этого перебираем все элементы
        $pathsOfElements = array();

        // Массив соответствия между путями и id-шниками элементов в модели
        $pathsToFields = array();

        $controlsToSave = array(); // - Массив контролов, которые обрабатываем

        // Перебираем весь входной массив, чтобы записать изменения в базу
        $currentDate = date('Y-m-d H:i');
        $answerCurrentDate = false;
        $emptyTemplate = true;
        foreach($_POST['FormTemplateDefault'] as $field => $value) {
            if($field == 'medcardId' || $field == 'greetingId'|| $field == 'templateName' || $field == 'templateId') {
                continue;
            }

            // Если значение у элемента не пустое и не пустой массив - то сбрасываем флаг, что шаблон пустой
            if(is_array($value)) {
                if (count($value)!=0)
                {
                    $emptyTemplate = false;
                }

            } else {
                // Сначала раскодируем из JSON значени (во всяком случае попробуем)
                $decodedObject = CJSON::decode($value);
                // Если мы что-то получили - проверим на пустоту объект
                if (!is_null($decodedObject)){
                    if (count($decodedObject)!=0) {
                        $emptyTemplate = false;
                    }
                } else {
                    // Иначе  надо тупо проверить на не пустоту строку
                    if ($value !='')
                    {
                        $emptyTemplate = false;
                    }
                }
            }

            // Это для выпадающего списка с множественным выбором
            if(is_array($value)) {
                $value = CJSON::encode($value);
            }
            // Проверим, есть ли такое поле вообще
            if(!preg_match('/^f(\d+\|)*\d+_(\d+)$/', $field, $resArr))
            {
                continue;
            }
            // Берём и тупо находим элемент по пути
            // Смотрим: если в историю не занесена категория, то нужно занести с сохранением параметров
            // Находим путь
            $pathWithSeparators = mb_substr($field, 1, mb_strrpos($field, '_') - 1);
            $arrPath =  explode('|', $pathWithSeparators);
            $path = implode('.', $arrPath);

            $pathsToFields[$field] = $path;
            $pathsOfElements[] = $path;
            $controlsToSave[$field] = $value;
        }
        $historyElements = MedcardElementForPatient::model()->getLatestStateOfGreeting
        (
            $_POST['FormTemplateDefault']['greetingId'],
            $pathsOfElements
        );
        $historyElementsPaths = array();

        foreach ($historyElements as $oneHistoryElement) {
            $historyElementsPaths[$oneHistoryElement['path']] = $oneHistoryElement;
        }
        $wasSaved = false;

        foreach($controlsToSave as $field => $value) {
            if(is_array($value)) {
                $value = CJSON::encode($value);
            }
            /** @var $historyCategorieElement MedcardElementForPatient */
            $historyCategorieElement = $historyElementsPaths[$pathsToFields[$field]];
            if ($historyCategorieElement == null) {
                continue;
            }
            $this->stepToNextState($historyCategorieElement, $value, $recordId );
            $answerCurrentDate = true;

            if(!$historyCategorieElement->save()) {
                ob_end_clean();
                echo CJSON::encode(array('success' => true,
                    'text' => 'Ошибка сохранения записи.'));
            } else {
                $wasSaved = true;
            }
        }
        if ($wasSaved) {
            if ($emptyTemplate===true)
            {
                $recordRow->is_empty = 1;
            }
            else
            {
                $recordRow->is_empty = 0;
            }

            $recordRow->save();
        }
        //}
//        $transaction->commit();
        // exit();
        $response = array(
            'success' => true,
            'text' => 'Данные успешно сохранены.'
        );
        ob_end_clean();
        echo CJSON::encode($response);
    }

    private function getHistoryElements($mode = 'one', $data = array()) {
        $conditions = '';
        if(isset($data[':history_id'])) {
            if($conditions == '') {
                $conditions = 'history_id = :history_id';
            } else {
                $conditions .= ' AND history_id = :history_id';
            }
        }

        if(isset($data[':greeting_id'])) {
            if($conditions == '') {
                $conditions = 'greeting_id = :greeting_id';
            } else {
                $conditions .= ' AND greeting_id = :greeting_id';
            }
        }

        if(isset($data[':medcard_id'])) {
            if($conditions == '') {
                $conditions = 'medcard_id = :medcard_id';
            } else {
                $conditions .= ' AND medcard_id = :medcard_id';
            }
        }

        if(isset($data[':path'])) {
            if($conditions == '') {
                $conditions = 'path = :path';
            } else {
                $conditions .= ' AND path = :path';
            }
        }

        if(isset($data[':categorie_id'])) {
            if($conditions == '') {
                $conditions = 'categorie_id = :categorie_id';
            } else {
                $conditions .= ' AND categorie_id = :categorie_id';
            }
        }

        if(isset($data[':element_id'])) {
            if($conditions == '') {
                $conditions = 'element_id != :element_id';
            } else {
                $conditions .= ' AND element_id != :element_id';
            }
        }

        if($mode == 'one') {
            return MedcardElementForPatient::model()->find(
                $conditions,
                $data
            );
        } elseif($mode == 'multiple') {
            return MedcardElementForPatient::model()->findAll(
                $conditions,
                $data
            );
        }
    }

    // Получить пациентов для текущего дня расписания
    public function getCurrentPatients() {
        $date = $this->getCurrentDate();
        $this->filterModel->date = $date;
        $userId = Yii::app()->user->id;
        $doctor = User::model()->findByPk($userId);
        if($doctor == null) {
            //exit('Error!');
        }
        // Выбираем пациентов на обозначенный день
        $sheduleByDay = new SheduleByDay();
        $patients = $sheduleByDay->getRows($date, $doctor['employee_id'], 0);

        return $patients;
    }

    // Начать приём пациента
    public function actionAcceptBegin() {
        $req = new CHttpRequest();
        if(isset($_GET['id']) && trim($_GET['id']) != '') {
            // Записать, что пациент принят
            $sheduleElement = SheduleByDay::model()->findByPk($_GET['id']);
            if($sheduleElement != null) {
                $sheduleElement->is_beginned = 1;
                $sheduleElement->time_begin = date('h:j');
                if(!$sheduleElement->save()) {
                    echo CJSON::encode(array('success' => true,
                        'text' => 'Ошибка сохранения записи.'));
                }
            }
        }

        $req->redirect($_SERVER['HTTP_REFERER']);
    }

    // Закончить приём пациента
    public function actionAcceptComplete() {
        $req = new CHttpRequest();

        if(isset($_GET['id']) && trim($_GET['id']) != '') {
            // Записать, что пациент принят
            $sheduleElement = SheduleByDay::model()->findByPk($_GET['id']);
            if($sheduleElement != null) {
                $sheduleElement->is_accepted = 1;
                $sheduleElement->time_end = date('h:j');
                // Записать статус медкарты: медкарта вернулась обратно в регистратуру
                $medcard = Medcard::model()->findByPk($sheduleElement->medcard_id);
                if($medcard != null) {
                    $medcard->motion = 0; // Сразу в регистратуру: человеческий фактор говорит о том, что связку врач-регистратура можно будет отловить по истории
                    if(!$medcard->save()) {
                        echo CJSON::encode(array('success' => false,
                            'text' => 'Ошибка сохранения статуса медкарты.'));
                        return;
                    }
                }
                if(!$sheduleElement->save()) {
                    echo CJSON::encode(array('success' => false,
                        'text' => 'Ошибка сохранения записи.'));
                    return;
                }
                // Далее сохраняем приём для выгрузки в ТАСУ, если этот приём не записан ещё туда
                $buffer = new TasuGreetingsBuffer();
                $issetBufferedGreeting = $buffer->find('greeting_id = :greeting_id', array(':greeting_id' => $sheduleElement->id));
                if($issetBufferedGreeting == null) {
                    $buffer->greeting_id = $sheduleElement->id;
                    $buffer->import_id = $buffer->getLastImportId();
                    if(!$buffer->save()) {
                        echo CJSON::encode(array('success' => false,
                            'text' => 'Ошибка сохранения буфера выгрузки ТАСУ.'));
                    }
                }
            }
        }
        echo CJSON::encode(array('success' => true,
            'text' => ''));
    }

    // Выдать календарь для записи врача
    // С уклоном на виджет
    public function actionGetCalendar() {
        echo CJSON::encode(
            array('success' => 'true',
                'data' => array(
                    'calendar' => $this->getCalendar(),
                    'day' => $this->currentDay,
                    'month' => $this->currentMonth,
                    'year' => $this->currentYear,
                    'doctorId' => (isset($_GET['doctorid']) && (int)$_GET['doctorid'] != 0) ? (int)$_GET['doctorid'] : false
                )
            )
        );
    }

    private function getSettings() {
        $settings = Setting::model()->findAll('module_id = 1
                                                    AND name IN(\'timePerPatient\',
                                                                \'firstVisit\',
                                                                \'quote\',
                                                                \'shiftType\',
                                                                \'maxInWaitingLine\')');
        $result = array();
        foreach($settings as $setting) {
            $result[$setting['name']] = $setting['value'];
        }
        return $result;
    }

    // Логика выдачи календаря:
    /* Выдаются даты + характеристика дат. Например, количество пациентов на день. */
    public function getCalendar($doctorId = false, $startYear = false, $startMonth = false, $startDay = false, $breakByErrors = true, $onlyWaitingLine = false) {
        $daysToWriteToScreen = 7; // Количество дней, которые мы выводим на экран ( по сути это константа)

        // Конструируем даты начала вывода расписания
        $currentYear = null;
        $currentMonth = null;
        $currentDay = null;
        $settings = $this->getSettings();
        if(isset($_GET['year'])) {
            $currentYear = $_GET['year'];
        } elseif($startYear !== false) {
            $currentYear = $startYear;
        } else {
            $currentYear = date('Y');
        }

        if(isset($_GET['month'])) {
            $currentMonth = $_GET['month'];
        } elseif($startMonth !== false) {
            $currentMonth = $startMonth;
        } else {
            $currentMonth = date('n');
        }

        if(isset($_GET['day'])) {
            $currentDay = $_GET['day'];
        } elseif($startDay !== false) {
            $currentDay = $startDay;
        } else {
            $currentDay = date('j');
        }

        // Берём current-дату и получаем из неё дату начала
        $dateBegin = $currentYear.'-'.$currentMonth.'-'.$currentDay;
        // Получаем дату конца периода
        $dateEnd = strtotime( $dateBegin )+$daysToWriteToScreen*86400;
        $dateEnd= date('Y-m-d', $dateEnd);


        $timeTable = new Timetable();
        $shedule = $timeTable->getRows(
            array(
                'doctorsIds' => array($doctorId),
                'dateBegin' => $dateBegin,
                'dateEnd' => $dateEnd
            )
        );

        $resultArr = array();
        for ($i=0;$i<$daysToWriteToScreen;$i++)
        {

            $currentDate = strtotime( $dateBegin )+$i*86400; // Прибавляем к дате начала i-тое количество дней
            $yearIteration = date('Y', $currentDate);
            $monthIteration = date('m', $currentDate);
            $dayIteration = date('d', $currentDate);


            // Теперь для i-того дня вычисляем все характеристики
            // Берём day из текущей даты
            $resultArr[$i]['day'] = date('d', $currentDate);
            // Added 16.12.2014
            $resultArr[$i]['month'] = date('m', $currentDate);
            $resultArr[$i]['year'] = date('Y', $currentDate);

            // Получаем день недели
            $resultArr[$i]['weekday'] = date('w', $currentDate);

            // Изначально день считается неработчим
            $resultArr[$i]['worked'] = false;
            $resultArr[$i]['numPatients'] = 0;
            $resultArr[$i]['quote'] = 0;
            $resultArr[$i]['allowForWrite'] = 0;
            $resultArr[$i]['primaryGreetings'] = 0;
            $resultArr[$i]['secondaryGreetings'] = 0;
            $resultArr[$i]['restDay'] = true;

            // Сначала чекаем выходные частные дни. Если выходной день есть, то дальше можно не обрабатывать
            // Ищем выходной день
            /*$restDayInDb = SheduleRestDay::model()->find('doctor_id = :doctor_id AND date = :date',
                array(
                    ':doctor_id' => $doctorId,
                    ':date' =>   date('Y-m-d', $currentDate)
                )
            );

            // Дальше не копаем, если есть день выходных
            if($restDayInDb != null) {
                $resultArr[$i]['restDayType'] = $restDayInDb->type;
                $resultArr[$i]['restDayForDoctor'] = true;
                continue;
            }*/

            // Найдём из выбранных графиков такой, под который подпадает данный день
            //$currentDateDate = strtotime( $currentDate );
            $sheduleForDay = null;
            foreach ($shedule as $oneShedule){
                $dateBeginTimetable = strtotime($oneShedule['date_begin']);
                $dateEndTimetable = strtotime($oneShedule['date_end']);

                // Сравниваем - если день лежит внутри промежутка расписания - то ура! мы нашли расписание, которое действует для данного дня
                if (($currentDate >= $dateBeginTimetable) && ($currentDate <= $dateEndTimetable )) {
                    $sheduleForDay = $oneShedule;
                    // Если это расписание, ориентированное не на отпуск, то поищем то, которое является отпуском и не будем выходить из цикла раньше времени
                    $jsonData = CJSON::decode($oneShedule['json_data']);
                    if(count($jsonData['facts']) > 0) {
                        break;
                    }
                }
            }

            // Если расписания на день нет - то и нечего дальше делать, переходим к следующему дню
            if ($sheduleForDay==null) {
                continue;
            }

            $ruleToApply = null;
            $ruleToApply = $this->checkByTimetable($sheduleForDay, date('Y-m-d',$currentDate));
            if ($ruleToApply!=null) {
                if(!isset($ruleToApply['isFact'])) {
                    // Правило найдено
                    $resultArr[$i]['worked'] = true;
                    $resultArr[$i]['restDay'] = false;
                    if(trim($ruleToApply['cabinet']) != '' && array_search($ruleToApply['cabinet'], $this->cabinetsIds) === false) {
                        $this->cabinetsIds[] = $ruleToApply['cabinet'];
                    }
                    $resultArr[$i]['cabinet'] = $ruleToApply['cabinet'];

                    if (isset ($ruleToApply['greetingBegin'])) {
                        $resultArr[$i]['beginTime'] = $ruleToApply['greetingBegin'];
                    }

                    if (isset ($ruleToApply['greetingEnd'])) {
                        $resultArr[$i]['endTime'] = $ruleToApply['greetingEnd'];
                    }

                    // Вставляем лимиты
                    //$resultArr[$i]['limits'] = array();
                    $resultArr[$i]['limits']['callCenter'] = $ruleToApply['limits'][1];
                    $resultArr[$i]['limits']['reception'] = $ruleToApply['limits'][2];
                    $resultArr[$i]['limits']['internet'] = $ruleToApply['limits'][3];
                } else { // Это факт. Через него подвязываем тип и прочее
                    if($onlyWaitingLine) {
                        $resultArr[$i]['worked'] = true;
                        $resultArr[$i]['restDay'] = false;
                    } else {
                        $resultArr[$i]['worked'] = false;
                        $resultArr[$i]['restDay'] = true;
                        $resultArr[$i]['restDayType'] = $ruleToApply['type'];
                        if(isset($ruleToApply['end']) && $ruleToApply['end'] == date('Y-m-d',$currentDate)) {
                            $this->restDays[] = array(
                                'doctor_id' => $doctorId,
                                'type' => $ruleToApply['type'],
                                'date' => date('Y-m-d',$currentDate)
                            );
                        }
                    }
                }
            } else {
                // не найдено
                if($onlyWaitingLine) {
                    $resultArr[$i]['worked'] = true;
                    $resultArr[$i]['restDay'] = false;
                } else {
                    $resultArr[$i]['worked'] = false;
                    $resultArr[$i]['restDay'] = true;
                }
            }

            if ($resultArr[$i]['worked'] == true)  {
                // Более глубокое сканирование: необходимо посмотреть, какие пациенты вообще есть в расписании по данным датам. Может получиться так, что при изменённом расписании потеряются пациенты
                $timeStampCurrent = mktime(0, 0, 0);
                if($currentDate >= $timeStampCurrent) {
                    $numPatients = $this->getPatientList($doctorId, date('Y-m-d',$currentDate),$ruleToApply['greetingBegin'], $ruleToApply['greetingEnd'], true, $onlyWaitingLine);
                    $resultArr[(string)$i]['numPatients'] = count(array_filter($numPatients['result'], function($element) {
                        return $element['id'] != null;
                    }));
                    // Если мест реально меньше, чем квота (у врача укороченная смена, либо текущий день и середина смены, скажем)
                    if($numPatients['numPlaces'] < $settings['quote']) {
                        $resultArr[(string)$i]['quote'] = $numPatients['numPlaces'];
                    } else {
                        $resultArr[(string)$i]['quote'] = $settings['quote'];
                    }
                    $resultArr[(string)$i]['primaryGreetings'] = $numPatients['primaryGreetings'];
                    $resultArr[(string)$i]['secondaryGreetings'] = $numPatients['secondaryGreetings'];
                } else {
                    $resultArr[(string)$i]['quote'] = $settings['quote'];
                    $resultArr[(string)$i]['numPatients'] = 0;
                    $resultArr[(string)$i]['primaryGreetings'] = 0;
                    $resultArr[(string)$i]['secondaryGreetings'] = 0;
                }
                // Квота изменяется вручную: возможно, врач просто не успеет за смену принять квоту человек
                // Если врач работает в этот день, надо посмотреть, не прошедшая ли дата. На прошедшие даты записывать не надо.
                $timeStampPerIteration = mktime(0, 0, 0, $monthIteration, $dayIteration, $yearIteration);
                // Если время итерируемое больше, то на такие числа записывать можно
                if($timeStampCurrent <= $timeStampPerIteration) {
                    $resultArr[(string)$i]['allowForWrite'] = 1;
                } else {
                    $resultArr[(string)$i]['allowForWrite'] = 0;
                }

            }
        }

        return $resultArr;
    }

    // Функция возвращает правило, в котором указано время начала и конца приёма
    private function getTimeTableRule($doctorIds,$dateBegin,$dateEnd)
    {

    }

    public function getDatesLimits() {
        return $this->restDays;
    }

    // Логика выдачи календаря:
    /* Выдаются даты + характеристика дат. Например, количество пациентов на день. */
    public function getCalendar1($doctorId = false, $startYear = false, $startMonth = false, $startDay = false, $breakByErrors = true, $onlyWaitingLine = false) {
        // Выбираем расписание врача
        if((isset($_GET['doctorid']) && (int)$_GET['doctorid'] != 0) || $doctorId !== false) {
            $doctorId = isset($_GET['doctorid']) ? (int)$_GET['doctorid'] : $doctorId;
            // Выбираем настройки расписания
            $settings = $this->getSettings();
            //$shedule = SheduleSetted::model()->findAll('employee_id = :employee_id', array(':employee_id' => $doctorId));
            $shedule = SheduleSetted::getAllForEmployer($doctorId);
            // Здесь проверяем день, месяц, год..
            if(isset($_GET['year'])) {
                $this->currentYear = $_GET['year'];
            } elseif($startYear !== false) {
                $this->currentYear = $startYear;
            } else {
                $this->currentYear = date('Y');
            }

            if(isset($_GET['month'])) {
                $this->currentMonth = $_GET['month'];
            } elseif($startMonth !== false) {
                $this->currentMonth = $startMonth;
            } else {
                $this->currentMonth = date('n');
            }

            if(isset($_GET['day'])) {
                $this->currentDay = $_GET['day'];
            } elseif($startDay !== false) {
                $this->currentDay = $startDay;
            } else {
                $this->currentDay = date('j');
            }

            // Расписание не установлено
            if(count($shedule) == 0 && $breakByErrors) {
                echo CJSON::encode(array('success' => 'false',
                    'data' => 'Запись невозможна: расписание для данного сотрудника не установлено.'));
                exit();
            } elseif(count($shedule) == 0) {
                return array();
            }

            // Количество дней в месяце
            $dayBegin = $startDay !== false ? $startDay : 1;
            // В случае органайзера мы показываем только на неделю вперёд
            if($startDay === false) {
                if($this->currentYear != null && $this->currentMonth != null) {
                    $dayEnd = date('t', strtotime($this->currentYear.'-'.$this->currentMonth));
                } else {
                    $dayEnd = date('t');
                }
            } else {
                $dayEnd = $startDay + 6;
            }

            // Здесь составляем карту расписания на каждый день: разбираем на общее расписание и исключения
            $usual = array();
            $usualData = array();
            $exps = array();
            $expsData = array();

            //var_dump($shedule);
            //exit();
            foreach($shedule as $key => $element) {
                // Обычное расписание
                if($element['type'] == 0) {
                    array_push($usual, $element['weekday']);
                    array_push($usualData, $element);
                }
                // Исключения
                if($element['type'] == 1) {
                    array_push($exps, $element['day']);
                    array_push($expsData, $element);
                }
            }
            // Теперь вынем стабильное расписание выходных
            $restDays = SheduleRest::model()->findAll();
            $restDaysArr = array();
            foreach($restDays as $restDay) {
                $restDaysArr[] = $restDay->day;
            }

            // Теперь вынем все дни, которые являются праздничными: на них тоже нельзя записывать человеков
            $paramDate = $this->currentYear."";
            $restDaysLonely = SheduleRestDay::model()->findAll('doctor_id = :doctor',
                array(
                    ':doctor' => $doctorId
                ));

            $restDaysArrLonely = array();
            foreach($restDaysLonely as $dayLonely) {
                $restDaysArrLonely[] = substr($dayLonely->date,0,10);
            }

            // Теперь смотрим по дням и составляем календарь
            $resultArr = array();
            for($i = $dayBegin; $i <= $dayEnd; $i++) {

                $resultArr[(string)$i - 1] = array();

                // Ведущие нули
                $perMonth = date('t', strtotime($this->currentYear.'-'.($this->currentMonth > 9 ? $this->currentMonth : '0'.$this->currentMonth)));

                if(($i > $perMonth)) {
                    $day = ($i - $perMonth) < 10 ? '0'.($i - $perMonth) : ($i - $perMonth);
                    $month = ($this->currentMonth + 1) < 10 ? '0'.($this->currentMonth + 1) : ($this->currentMonth + 1);
                } else {
                    $day = $i < 10 ? '0'.$i : $i;
                    $month = $this->currentMonth < 10 ? '0'.$this->currentMonth : $this->currentMonth;
                }

                $formatDate =  $this->currentYear.'-'.$month.'-'.$day;
                //var_dump($formatDate);
                $weekday = date('w', strtotime($formatDate));

                // 0 -> 0.. 1 -> 1..
                $resultArr[(string)$i - 1]['weekday'] = $weekday;
                $expsIndex = array_search($formatDate, $exps);

                $usualIndex = SheduleSetted::getIndexWorkingDay($usualData,  $weekday,$formatDate);
                if(($usualIndex !== false && array_search($weekday, $restDaysArr) === false && array_search($formatDate, $restDaysArrLonely) === false) || $expsIndex !== false) {
                    // День существует, врач работает
                    $resultArr[(string)$i - 1]['worked'] = true;
                    $resultArr[(string)$i - 1]['restDay'] = false;

                    // Начало и конец смены
                    if($expsIndex !== false) {
                        $resultArr[(string)$i - 1]['beginTime'] = $expsData[$expsIndex]['time_begin'];
                        $resultArr[(string)$i - 1]['endTime'] = $expsData[$expsIndex]['time_end'];
                    }

                    if($usualIndex !== false) {
                        $resultArr[(string)$i - 1]['beginTime'] = $usualData[$usualIndex]['time_begin'];
                        $resultArr[(string)$i - 1]['endTime'] = $usualData[$usualIndex]['time_end'];
                    }
                    // Дальше, исходя из настроек, смотрим: полностью свободный, частично свободный или полностью занятый день
                    // TODO: в цикле очень плохо делать выборку. 31 выборка максимум за раз.
                    // Более глубокое сканирование: необходимо посмотреть, какие пациенты вообще есть в расписании по данным датам. Может получиться так, что при изменённом расписании потеряются пациенты
                    $timeStampCurrent = mktime(0, 0, 0);
                    if(strtotime($formatDate) >= $timeStampCurrent) {
                        $numPatients = $this->getPatientList($doctorId, $this->currentYear.'-'.$month.'-'.$day, true, $onlyWaitingLine);
                        $resultArr[(string)$i - 1]['numPatients'] = count(array_filter($numPatients['result'], function($element) {
                            return $element['id'] != null;
                        }));
                        // Если мест реально меньше, чем квота (у врача укороченная смена, либо текущий день и середина смены, скажем)
                        if($numPatients['numPlaces'] < $settings['quote']) {
                            $resultArr[(string)$i - 1]['quote'] = $numPatients['numPlaces'];
                        } else {
                            $resultArr[(string)$i - 1]['quote'] = $settings['quote'];
                        }
                        $resultArr[(string)$i - 1]['primaryGreetings'] = $numPatients['primaryGreetings'];
                        $resultArr[(string)$i - 1]['secondaryGreetings'] = $numPatients['secondaryGreetings'];
                    } else {
                        $resultArr[(string)$i - 1]['quote'] = $settings['quote'];
                        $resultArr[(string)$i - 1]['numPatients'] = 0;
                        $resultArr[(string)$i - 1]['primaryGreetings'] = 0;
                        $resultArr[(string)$i - 1]['secondaryGreetings'] = 0;
                    }
                    // Квота изменяется вручную: возможно, врач просто не успеет за смену принять квоту человек
                    // Если врач работает в этот день, надо посмотреть, не прошедшая ли дата. На прошедшие даты записывать не надо.
                    $timeStampPerIteration = mktime(0, 0, 0, $month, $day, $this->currentYear);
                    // Если время итерируемое больше, то на такие числа записывать можно
                    if($timeStampCurrent <= $timeStampPerIteration) {
                        $resultArr[(string)$i - 1]['allowForWrite'] = 1;
                    } else {
                        $resultArr[(string)$i - 1]['allowForWrite'] = 0;
                    }
                } else {
                    // Если это выходной, его тоже нужно помечать
                    // состыкуем дату
                    if(array_search($weekday, $restDaysArr) !== false || array_search($formatDate, $restDaysArrLonely) !== false) {
                        $resultArr[(string)$i - 1]['restDay'] = true;
                    } else {
                        $resultArr[(string)$i - 1]['restDay'] = false;
                    }
                    $resultArr[(string)$i - 1]['worked'] = false;
                    $resultArr[(string)$i - 1]['numPatients'] = 0;
                    $resultArr[(string)$i - 1]['quote'] = 0;
                    $resultArr[(string)$i - 1]['allowForWrite'] = 0;
                    $resultArr[(string)$i - 1]['numPatients'] = 0;
                    $resultArr[(string)$i - 1]['primaryGreetings'] = 0;
                    $resultArr[(string)$i - 1]['secondaryGreetings'] = 0;
                }
                $resultArr[(string)$i - 1]['day'] = $i;
            }

            return $resultArr;
        }
    }

    private function checkByTimetable($timeTable, $dayDate)
    {
        $timeTableObject = new Timetable();
        return $timeTableObject->getRuleFromTimetable($timeTable, $dayDate,  $this->returnFacts);
    }

    public function actionGetPatientsListByDate() {
        if(Yii::app()->user->isGuest) {
            echo CJSON::encode(array('success' => 'false',
                'data' => 'Error!'));
            exit();
        }
        if(!isset($_GET['month'], $_GET['day'], $_GET['year'], $_GET['doctorid'])) {
            echo CJSON::encode(array('success' => 'false',
                'data' => 'Нехватка данных для выборки!'));
            exit();
        }
        $this->currentYear = $_GET['year'];
        $this->currentMonth = $_GET['month'];
        $this->currentDay = $_GET['day'];

        if(isset($_GET['onlywaitingline']) && $_GET['onlywaitingline'] == 1) {
            $onlyWaitingLine = 1;
        } else {
            $onlyWaitingLine = 0;
        }

        $dateToFind = $this->currentYear . '-'.$this->currentMonth.'-'.$this->currentDay;
        $timeTable = new Timetable();

        $shedule = $timeTable->getRows(
            array(
                'doctorsIds' => array($_GET['doctorid']),
                'dateBegin' => $dateToFind,
                'dateEnd' => $dateToFind
            )
        );

        $ruleToApply = $this->checkByTimetable($shedule[0], $dateToFind);
        // 0 - считаем, что на одну дату приходится по одному графику

        $result = $this->getPatientList($_GET['doctorid'], $this->currentYear.'-'.$this->currentMonth.'-'.$this->currentDay, $ruleToApply['greetingBegin'] ,$ruleToApply['greetingEnd'] , true, $onlyWaitingLine);

        $limits = $ruleToApply['limits'];
        if(!$limits) {
            $limits = [[],[],[]];
        }

        echo CJSON::encode(array('success' => 'true',
            'data' => $result['result'],
            'limits' => $limits
        ));
    }

    public function getCabinetsIdsList() {
        return $this->cabinetsIds;
    }

    public function actionChangeGreetingStatus($greetingId=false,$newValue=false)
    {
        // Ищем приём по ИД
        $greetingToEdit = SheduleByDay::model()->findByPk($greetingId);

        // Меняем значение и сохраняем
        $greetingToEdit->greeting_status = $newValue;
        $greetingToEdit->save();
        echo CJSON::encode(array('success' => true,
            'data' => array()));
    }

    // Функция, которая выдаёт пациентов без начального и конечного времени
    private function getPatientListWOTimeStamp($doctorId, $formatDate, $withMediate = true, $onlyWaitingLine = false) {
        $patientsList = array();
        $sheduleByDay = new SheduleByDay();
        $weekday = date('w', strtotime($formatDate)); // День недели (число)
        $needMediate = 1;
        if (!$withMediate);
        $needMediate = true;

        $patients = $sheduleByDay->getRows($formatDate, $doctorId, $needMediate, 0, $onlyWaitingLine);
        //var_dump($patients);
        //exit();
        // Теперь строим список пациентов и свободных ячеек исходя из выборки. Выбираем начало и конец времени по расписанию у данного врача
        $user = User::model()->findByPk(Yii::app()->user->id);
        if($user == null) {
            echo CJSON::encode(array('success' => 'false',
                'data' => 'Ошибка! Неавторизованный пользователь.'));
        }

        $settings = $this->getSettings();
        // Выясняем время работы. Частные дни имеют приоритет по сравнению с обычными
        $choosedType = 0;

        $primaryGreetings = 0;
        $secondaryGreetings = 0;

        $result = array();
        $numRealPatients = 0; // Это для того, чтобы понять, заполнено ли всё
        $parts = explode('-', $formatDate);
        $today = ($parts[0] == date('Y') && $parts[1] == date('n') && $parts[2] == date('j'));
        // Определяем параметры цикла. В случае, если это живая очередь, отсчёт идёт по местам. В случае, если это запись, по времени.

        if($onlyWaitingLine) {
            $increment = 1;
        } else {
            $doctor = Doctor::model()->findByPk($doctorId);
            if($doctor && $doctor->greeting_time_limit) {
                $increment = $doctor->greeting_time_limit * 60;
            } else {
                $increment = $settings['timePerPatient'] * 60;
            }
        }

        // Ищем пациента для такого времени. Если он найден, значит время занято
        foreach($patients as $key => $patient) {
            $timestamp = strtotime($patient['patient_time']);
            // Если пациент опосредованный, для него надо выбрать ФИО
            if($patient['mediate_id'] != null) {
                $mediatePatient = MediatePatient::model()->findByPk($patient['mediate_id']);
                if($mediatePatient != null) {
                    $patient['fio'] = $mediatePatient['last_name'].' '.$mediatePatient['first_name'].' '.$mediatePatient['middle_name'].' (опосредованный)';
                    $patient['greetingStatus'] = $patient['greeting_status'];
                }
            }

            $result[] = array(
                'timeBegin' => date('G:i', strtotime($patient['patient_time'])),
                'timeEnd' => date('G:i', strtotime($patient['patient_time']) + $increment),
                'fio' => $patient['fio'],
                'isAllow' => 0, // Доступно ли время для записи или нет,
                'id' => $patient['id'],
                'type' => $patient['mediate_id'] != null ? 1 : 0,
                'cardNumber' => $patient['card_number'],
                'is_accepted' =>$patient['is_accepted'],
                'is_beginned' =>$patient['is_beginned'],
                'medcard_id' => $patient['card_number'],
                'patient_time' => date('G:i', strtotime($patient['patient_time'])),
                'comment' => $patient['comment'],
                'greetingType' => $patient['greeting_type'],
                'orderNumber' => $patient['order_number'],
                'greetingStatus' => $patient['greeting_status'],
            );
            if($patient['greeting_type'] == 1) {
                $primaryGreetings++;
            }
            if($patient['greeting_type'] == 2) {
                $secondaryGreetings++;
            }
            $isFound = true;
            $numRealPatients++;
        }

        // Если результата нет - выводим пустой список
        if (!isset($result))
        {

            $result = array();
            $numRealPatients = 0;
        }

        return array(
            'result' => $result,
            'allReserved' => $numRealPatients == count($result),
            'numPlaces' => count($result),
            'primaryGreetings' => $primaryGreetings,
            'secondaryGreetings' => $secondaryGreetings
        );
    }

    private function getPatientList($doctorId, $formatDate, $timeBegin, $timeEnd, $withMediate = true, $onlyWaitingLine = false) {
        $patientsList = array();
        $sheduleByDay = new SheduleByDay();
        $weekday = date('w', strtotime($formatDate)); // День недели (число)
        $needMediate = 1;
        if (!$withMediate);
        $needMediate = true;

        $patients = $sheduleByDay->getRows($formatDate, $doctorId, $needMediate, 0, $onlyWaitingLine);

        $settings = $this->getSettings();
        $doctor = Doctor::model()->findByPk($doctorId);
        // Выясняем время работы. Частные дни имеют приоритет по сравнению с обычными
        $choosedType = 0;

        $timestampBegin  = strtotime($timeBegin);
        $timestampEnd  = strtotime($timeEnd);

        $primaryGreetings = 0;
        $secondaryGreetings = 0;

        $result = array();
        $numRealPatients = 0; // Это для того, чтобы понять, заполнено ли всё
        $currentTimestamp = time();
        $parts = explode('-', $formatDate);
        $today = ($parts[0] == date('Y') && $parts[1] == date('n') && $parts[2] == date('j'));
        // Определяем параметры цикла. В случае, если это живая очередь, отсчёт идёт по местам. В случае, если это запись, по времени.

        if($onlyWaitingLine) {
            $beginValue = 0;
            $endValue = $settings['maxInWaitingLine'];
            $increment = 1;
        } else {
            $beginValue = $timestampBegin;
            $endValue = $timestampEnd;
            if($doctor && $doctor->greeting_time_limit) {
                $increment = $doctor->greeting_time_limit * 60;
            } else {
                $increment = $settings['timePerPatient'] * 60;
            }
        }

        for($i = $beginValue; $i < $endValue; $i += $increment) {
            /* if(!$onlyWaitingLine && $currentTimestamp >= $i && $today) {
                continue;
            } */
            // Ищем пациента для такого времени. Если он найден, значит время занято
            $isFound = false;
            foreach($patients as $key => $patient) {
                $timestamp = strtotime($patient['patient_time']);
                if((!$onlyWaitingLine && $timestamp == $i) || ($onlyWaitingLine && $patient['order_number'] == $i + 1)) {
                    // Если пациент опосредованный, для него надо выбрать ФИО
                    if ($patient['mediate_id'] != null) {
                        $mediatePatient = MediatePatient::model()->findByPk($patient['mediate_id']);
                        if ($mediatePatient != null) {
                            $patient['fio'] = $mediatePatient['last_name'] . ' ' . $mediatePatient['first_name'] . ' ' . $mediatePatient['middle_name'] . ' (опосредованный)';
                            $patient['greetingStatus'] = $patient['greeting_status'];
                        }
                    }
                    
                    $medcard=Medcard::model()->findByPk($patient['card_number']);
                    $enterprise=Enterprise::model()->findByPk($medcard->enterprise_id);

                    $result[] = array(
                    	'enterprise'=>$enterprise,
                        'timeBegin' => date('G:i', $i),
                        'timeEnd' => date('G:i', $i + $increment),
                        'fio' => $patient['fio'],
                        'isAllow' => 0, // Доступно ли время для записи или нет,
                        'id' => $patient['id'],
                        'type' => $patient['mediate_id'] != null ? 1 : 0,
                        'cardNumber' => $patient['card_number'],
                        'is_accepted' => $patient['is_accepted'],
                        'is_beginned' => $patient['is_beginned'],
                        'medcard_id' => $patient['card_number'],
                        'patient_time' => date('G:i', $i),
                        'comment' => $patient['comment'],
                        'greetingType' => $patient['greeting_type'],
                        'orderNumber' => $patient['order_number'],
                        'greetingStatus' => $patient['greeting_status'],
                    );

                    if ($patient['greeting_type'] == 1) {
                        $primaryGreetings++;
                    }
                    if ($patient['greeting_type'] == 2) {
                        $secondaryGreetings++;
                    }

                    $isFound = true;
                    $numRealPatients++;
                }
            }

            if(!$isFound) {
                $result[] = array(
                    'timeBegin' => date('G:i', $i),
                    'timeEnd' => date('G:i', $i + $increment),
                    'isAllow' => 1,
                    'fio' => '',
                    'id' => null,
                    'cardNumber' => null,
                    'orderNumber' => $i + 1
                );

            }
        }

        // Если результата нет - выводим пустой список
        if (!isset($result))
        {

            $result = array();
            $numRealPatients = 0;
        }

        return array(
            'result' => $result,
            'allReserved' => $numRealPatients == count($result),
            'numPlaces' => count($result),
            'primaryGreetings' => $primaryGreetings,
            'secondaryGreetings' => $secondaryGreetings
        );
    }

    // Записать пациента на приём
    public function actionWritePatient() {
        //var_dump($_GET);
        //exit();

        $greetingType = $_GET['greeting_type'] ;

        if ($greetingType=='0')
            $greetingType = '1';

        if(!Yii::app()->request->isAjaxRequest) {
            echo "Error!";
            exit();
        }
        if(!isset($_GET['day'], $_GET['month'], $_GET['year'], $_GET['doctor_id'], $_GET['time'], $_GET['mode'])) {
            echo "Error! Not enough data for request.";
            exit();
        }

        $formatDate = $_GET['year'].'-'.$_GET['month'].'-'.$_GET['day'];
        $formatTime = $_GET['time'];
        // Вынимаем элемент расписания для записи кабинета, например
        // Определим день
        $weekday = date('w', strtotime($formatDate));
        $sheduleSetted = SheduleSetted::model()->find('weekday = :weekday AND employee_id = :employee_id AND day IS NULL', array(':weekday' => $weekday, ':employee_id' => $_GET['doctor_id']));

        // Обработка коллизии выбора одного и того же времени у врачей. Либо по месту, либо по времени (зависит от того, живая очередь или нет)
        if(!isset($_GET['order_number'])) { // По наличию этого параметра можно установить, что запрос пошёл от живой очереди
            $issetSheduleElement = SheduleByDay::model()->find('doctor_id = :doctor_id AND patient_day = :patient_day AND patient_time = :patient_time',
                array(
                    ':doctor_id' => $_GET['doctor_id'],
                    ':patient_day' => $formatDate,
                    ':patient_time' => $formatTime
                ));
        } else {
            $issetSheduleElement = SheduleByDay::model()->find('doctor_id = :doctor_id AND patient_day = :patient_day AND order_number = :order_number',
                array(
                    ':doctor_id' => $_GET['doctor_id'],
                    ':patient_day' => $formatDate,
                    ':order_number' => $_GET['order_number']
                ));
        }

        // Коллизия: время уже занято
        if($issetSheduleElement != null) {
            if(!isset($_GET['order_number'])) {
                echo CJSON::encode(array('success' => 'false',
                    'error' => 'Время, на которое Вами записывается пациент, уже занято! Пожалуйста, выберите другое время!'));
            } else {
                echo CJSON::encode(array('success' => 'false',
                    'error' => 'Место, на которое Вами записывается пациент, уже занято! Пожалуйста, выберите другое место!'));
            }
            exit();
        }
        $sheduleElement = new SheduleByDay();
        $sheduleElement->doctor_id = $_GET['doctor_id'];
        $sheduleElement->patient_day = $formatDate;
        $sheduleElement->is_accepted = 0;
        // Время пишется только в случае с пациентами неживой очереди
        if(!isset($_GET['order_number'])) {
            $sheduleElement->patient_time = $formatTime;
        }
        $sheduleElement->greeting_type = $greetingType;
        if(isset($_GET['order_number'])) {
            $sheduleElement->order_number = $_GET['order_number'];
        }

        if($sheduleSetted != null) {
            $sheduleElement->shedule_id = $sheduleSetted->id;
        }
        if($_GET['mode'] == 0) { // Обычная запись
            $sheduleElement->medcard_id = $_GET['card_number'];
            $sheduleElement->mediate_id = null;
            $sheduleElement->comment = $_GET['comment']; // XSS TODO
        } elseif($_GET['mode'] == 1) { // Опосредованная запись
            $sheduleElement->medcard_id = null;
            // Создаём запись опосредованного пациента
            $mediate = new MediatePatient();
            $mediateForm = new FormMediatePatientAdd();
            $mediateForm->attributes = $_GET;
            if ($mediateForm->phone=="+7") $mediateForm->phone = "";
            if(!$mediateForm->validate()) {
                echo CJSON::encode(array('success' => 'false',
                    'errors' =>  $mediateForm->getErrors()));
                exit();
            }
            // Заполняем значениями форму опосредованного пациента
            $mediate->first_name = $mediateForm->firstName;
            $mediate->last_name = $mediateForm->lastName;
            $mediate->middle_name = $mediateForm->middleName;
            $mediate->phone = $mediateForm->phone;
            $sheduleElement->comment = $mediateForm->comment;
            if(!$mediate->save()) {
                echo CJSON::encode(array('success' => 'false',
                    'error' =>  'Не могу сохранить опосредованного пациента в базе!'));
                exit();
            }

            $sheduleElement->mediate_id = $mediate->id;
        }

        if(!$sheduleElement->save()) {
            echo CJSON::encode(array('success' => 'false',
                'data' => 'Не могу записать пациента!'));
            exit();
        }

        if($_GET['mode'] == 0) {
            $writedMedcard = Medcard::model()->findByPk($_GET['card_number']);
            if($writedMedcard != null) {
                $writedOms = Oms::model()->findByPk($writedMedcard->policy_id);
            }
        } else {
            $writedOms = new Oms();
            $writedOms->first_name = $mediateForm->firstName;
            $writedOms->last_name = $mediateForm->lastName;
            $writedOms->middle_name = $mediateForm->middleName;
        }

        $writedDoctor = Doctor::model()->findByPk($_GET['doctor_id']);
        if($writedDoctor != null) {

        }

        $msg = 'Пациент '.$writedOms->last_name.' '.$writedOms->first_name.' '.$writedOms->middle_name.' записан на приём к специалисту '.$writedDoctor->last_name.' '.$writedDoctor->first_name.' '.$writedDoctor->middle_name.' на '.$_GET['day'].'.'.$_GET['month'].' '.$_GET['year'];
        if(!isset($_GET['order_number'])) {
            $msg .= ' '.$_GET['time'].'.';
        } else {
            $msg .= ', в очереди под номером '.$_GET['order_number'].'.';
        }

        // Если есть id отменённого приёма - надо удалить старый приём в таблице (поставить ему delete=1)
        if (isset($_GET['cancelledGreetingId']) && ($_GET['cancelledGreetingId']!=''))
        {
            CancelledGreeting::deleteCancelledGreeting($_GET['cancelledGreetingId']);
        }

        echo CJSON::encode(array('success' => 'true',
            'greetingId' => $sheduleElement->id,
            'data' => $msg));
    }

    private function saveGreetingDataToSession($greeting)
    {
        $maxArraySize = 100;
        if (!isset($_SESSION['unwritedGreetings']))
        {
            $_SESSION['unwritedGreetings'] = array();
        }

        // Проверим - если в сессии больше, чем 10 приёмов - то удалим старые с начала массива
        if (count($_SESSION['unwritedGreetings'])>=$maxArraySize)
        {
            while (count($_SESSION['unwritedGreetings'])>=$maxArraySize)
                array_shift($_SESSION['unwritedGreetings']);
        }

        array_push($_SESSION['unwritedGreetings'], $greeting);
    }

    // Отписать пациента от приёма
    public function actionUnwritePatient() {
        if(!isset($_GET['id'])) {
            echo CJSON::encode(array('success' => 'false',
                'data' => 'Не могу отписать пациента от приёма!'));
            exit();
        }
        $sheduleElement = SheduleByDay::model()->findByPk($_GET['id']);
        if($sheduleElement != null) {
            // Записываем данные об отписываемом приёме в сессию
            $dataToWrite = array (
                'id' => $sheduleElement['id'],
                'comment' => $sheduleElement['comment']
            );
            // Проверим - если приём опосредованный, нужно прочитать данные, которые заносятся
            //     при записи опосредованного пациента
            if ($sheduleElement['mediate_id']!='')
            {
                $mediateData = MediatePatient::model()->findByPk($sheduleElement['mediate_id']);

                $dataToWrite['first_name'] = $mediateData['first_name'];
                $dataToWrite['last_name'] = $mediateData['last_name'];
                $dataToWrite['middle_name'] = $mediateData['middle_name'];
                $dataToWrite['phone'] = $mediateData['phone'];
            }

            // Проверим - если приём начат - никакого удаления!
            if ($sheduleElement->is_beginned!=1)
            {

                $sheduleElement->delete();
                $this->saveGreetingDataToSession($dataToWrite);
                echo CJSON::encode(array('success' => 'true',
                    'data' => 'Пациент успешно отписан!'));
            }
            else
            {
                echo CJSON::encode(array('success' => 'false',
                    'data' => 'Невозможно отменить данный приём, так как он уже начат!'));
            }
            return;
        }

        echo CJSON::encode(array('success' => 'false',
            'data' => 'Какая-то не понятная ошибка. Вы меня сильно озадачили!'));
    }
}