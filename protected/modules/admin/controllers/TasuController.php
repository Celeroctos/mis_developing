<?php
class TasuController extends Controller {
    public $layout = 'application.modules.admin.views.layouts.index';
    public $answer = array();
    public $tableSchema = 'mis';
    // Просмотр страницы интеграции с ТАСУ
    public function actionView() {
        if(isset($_GET['iframe'])) {
            $this->layout = 'application.modules.admin.views.layouts.empty';
            $this->render('empty', array());
        } else {
            // Смотрим, какие таблицы есть в базе
            $sql = "SELECT t.schemaname, c.relname, d.description
                    FROM pg_class c
                    join pg_description d on d.objoid = c.oid
                    join pg_attribute a on a.attrelid = c.oid
                    join pg_tables t on t.tablename = c.relname
                    WHERE t.schemaname = '".$this->tableSchema."'
                        and d.objsubid = 0
                        and a.attname = 'tableoid'";
            $command = Yii::app()->db->createCommand($sql);
            $tables = $command->queryAll();
            $this->answer['model'] = new FormTableModel();
            $tablesArr = array('-1' => 'Не выбрано');
            foreach($tables as $table) {
                $tablesArr[$table['relname']] = $table['relname'].' ('.$table['description'].')';
            }
            $this->answer['tables'] = $tablesArr;
            // Модель полей база-поле импортируемого документа
            $this->answer['modelTasuImportField'] = new FormTasuImportField();

            // Теперь смотрим список файлов
            $filesList = $this->getTasuFilesList();
            $this->answer['filesList'] = $filesList;

            // Модели шаблонов полей и ключа
            $this->answer['modelAddFieldTemplate'] = new FormTasuFieldTemplate();
            $this->answer['modelAddKeyTemplate'] = new FormTasuKeyTemplate();
            $this->answer['fieldsTemplatesList'] =  array();
            $this->answer['keysTemplatesList'] = array();
            $this->render('view', $this->answer);
        }
    }

    public function actionReloadFilesList() {
        echo CJSON::encode(
            array('success' => true,
                  'data' => $this->getTasuFilesList()
            )
        );
    }

    public function getTasuFilesList() {
        if(file_exists(getcwd().'/uploads/tasu')) {
            // Получим список всех файлов в директории
            $files = scandir(getcwd().'/uploads/tasu');
            // Первые два файла отрезаем: это директории
            $files = array_slice($files, 2);
            // Теперь ищем по базе реальные имена файлов
            $result = array();
            foreach($files as &$file) {
                $row = FileModel::model()->find('path = :path', array(':path' => '/uploads/tasu/'.$file));
                if($row != null && file_exists(getcwd().$row->path)) {
                    // Фильтруем: если файл не CSV, то пропускаем
                    if(strtolower(substr($row->path, strrpos($row->path, '.') + 1)) != 'csv') {
                        continue;
                    }
                    $result[] = array(
                        'realName' => $row->filename,
                        'icon' => 'csv.png',
                        'id' => $row->id
                    );
                }
            }
            return $result;
        } else {
            return array();
        }
    }

    // Загрузка ОМС
    public function actionUploadOms() {
        // Файлы есть, будем грузить
        if(Yii::app()->user->isGuest) {
            exit('Недостаточно прав для выполнения операции.');
        }
        if(count($_FILES) > 0) {
            ini_set('max_execution_time', 0);
            $uploadedFile = CUploadedFile::getInstanceByName('uploadedFile');

            // Создадим директории, если не существуют
            if(!file_exists(getcwd().'/uploads')) {
                mkdir(getcwd().'/uploads');
            }
            if(!file_exists(getcwd().'/uploads/tasu')) {
                mkdir(getcwd().'/uploads/tasu');
            }
            $filenameGenered = md5(date('Y-m-d h:m:s').$_FILES['uploadedFile']['name']);
            $path = '/uploads/tasu/'.$filenameGenered.'.'.$uploadedFile->getExtensionName();

            $fileModel = new FileModel();
            $fileModel->owner_id = Yii::app()->user->id;
            $fileModel->type = 1; // Файл для ТАСУ
            $fileModel->filename = $uploadedFile->getName();
            $fileModel->path = $path;
            if(!$fileModel->save()) {
                // Файл не начат качаться
                echo CJSON::encode(array(
                        'success' => false,
                        'error' => 'Невозможно сохранить файл в базе!'
                    )
                );
            }

            $uploadedFile->saveAs(getcwd().$path);
            $_SESSION['uploadedFile'] = getcwd().$path; // Файл закачан, глупый фикс
            $req = new CHttpRequest();
            if(!isset($_SERVER['HTTP_REFERER'])) {
                echo "";
                exit();
            } else {
                $req->redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function actionGetUploadProgressInfo() {
        if(isset($_SESSION[ini_get('session.upload_progress.prefix').'upfile'])) {
            $fileInfo = $_SESSION[ini_get('session.upload_progress.prefix').'upfile'];
            echo CJSON::encode(array('success' => true,
                    'data' => array(
                        'uploaded' => $fileInfo['bytes_processed'],
                        'filesize' => $fileInfo['content_length'],
                        'done' => $fileInfo['done']
                    )
                )
            );
            exit();
        } elseif(isset($_SESSION['uploadedFile']) && file_exists($_SESSION['uploadedFile'])) { // Временный фикс, пока нельзя отследить по-нормальному загрузку файлов
            unset($_SESSION['uploadedFile']);

            // Файл закачан полностью
            echo CJSON::encode(array(
                    'success' => true,
                    'data' => array(
                        'uploaded' => 100,
                        'filesize' => 100
                    )
                )
            );
        } else {
            // Файл не начат качаться
            echo CJSON::encode(array(
                    'success' => true,
                    'data' => array(
                        'uploaded' => 0,
                        'filesize' => 100
                    )
                )
            );
        }
    }

    public function actionGetCsvFields() {
        if(!isset($_GET['tasufile'])) {
            echo CJSON::encode(array(
                    'success' => false,
                    'data' => array(
                    )
                )
            );
            exit();
        }

        $fileFields = $this->getTasuCsvFileHeaders($_GET['tasufile']);
        echo CJSON::encode(array(
                'success' => true,
                'data' => array(
                    'fileFields' => $fileFields
                )
            )
        );
    }

    public function actionGetTableFields() {
        if(!isset($_GET['table'])) {
            echo CJSON::encode(array(
                    'success' => false,
                    'data' => array(
                    )
                )
            );
            exit();
        }

        $columns = $this->getTableColumns($_GET['table']);
        $dbFields = array();

        foreach($columns as $column) {
            $dbFields[$column['column_name']] = $column['column_name'];
        }
        // Выясняем реальное имя файла
        $file = FileModel::model()->findByPk($_GET['tasufile']);
        if($file != null) {
            $fileFields = $this->getTasuCsvFileHeaders($file['path']);
            echo CJSON::encode(array(
                    'success' => true,
                    'data' => array(
                        'dbFields' => $dbFields,
                        'fileFields' => $fileFields
                    )
                )
            );
        } else {
            echo CJSON::encode(array(
                    'success' => false,
                    'error' => 'Невозможно найти файл в базе!'
                )
            );
        }
    }

    private function getTableColumns($table, $objSubId = false) {
        /*$sql = "SELECT t.schemaname, c.relname, d.description, a.attname, d.objsubid, a.attnum
                    FROM pg_class c
                    join pg_description d on d.objoid = c.oid
                    join pg_attribute a on a.attrelid = c.oid
                    join pg_tables t on t.tablename = c.relname
                    WHERE  c.relname = '".$table."'
                           and t.schemaname = '".$this->tableSchema."'
                           and a.attstattarget = -1 "; */
       /* $sql = "SELECT
                     a.attname, d.description
                FROM
                    pg_catalog.pg_attribute a
                    INNER JOIN pg_catalog.pg_class c on a.attrelid = c.oid
                    INNER JOIN pg_description d on d.objoid = c.oid
                WHERE
                    c.relname = '".$table."'
                    and a.attnum > 0
                    and a.attisdropped = false"; */
      /*  if($objSubId === false) {
            $sql .= "and d.objsubid > 0";
        } else {
            $sql .= "and d.attnum = ".$objSubId; // Выборка наименования столбцов
        } */
        $sql = "SELECT *
                FROM information_schema.columns
                WHERE information_schema.columns.table_name = '".$table."'";

        $command = Yii::app()->db->createCommand($sql);
        $columns = $command->queryAll();
        return $columns;
    }

    public function getTasuCsvFileHeaders($filePath) {
        // Создадим директории, если не существуют
        if(!file_exists(getcwd().'/uploads')) {
            mkdir(getcwd().'/uploads');
        }
        if(!file_exists(getcwd().'/uploads/tasu')) {
            mkdir(getcwd().'/uploads/tasu');
        }
        if(!file_exists(getcwd().$filePath)) {
            return array();
        } else {
            $file = fopen(getcwd().$filePath, 'r');
            // Смотрим заголовки
            $firstRow = fgetcsv($file);
            return $firstRow;
            fclose($file);
        }
    }

    public function actionAddFieldTemplate() {
        $model = new FormTasuFieldTemplate();
        if(isset($_POST['FormTasuFieldTemplate'])) {
            $model->attributes = $_POST['FormTasuFieldTemplate'];
            if($model->validate()) {
                $template = new TasuFieldsTemplate();
                $template->name = $model->name;
                $template->template = $model->template;
                $template->table = $model->table;
                if(!$template->save()) {
                    echo CJSON::encode(array('success' => false,
                                             'errors' => $model->errors));
                } else {
                    echo CJSON::encode(array('success' => true,
                                             'data' => array()));
                }
            } else {
                echo CJSON::encode(array('success' => false,
                                         'errors' => $model->errors));
            }
        }
    }

    public function actionAddKeyTemplate() {
        $model = new FormTasuKeyTemplate();
        if(isset($_POST['FormTasuKeyTemplate'])) {
            $model->attributes = $_POST['FormTasuKeyTemplate'];
            if($model->validate()) {
                $template = new TasuKeysTemplate();
                $template->name = $model->name;
                $template->template = $model->template;
                $template->table = $model->table;
                if(!$template->save()) {
                    echo CJSON::encode(array('success' => false,
                        'errors' => $model->errors));
                } else {
                    echo CJSON::encode(array('success' => true,
                                             'data' => array()));
                }
            } else {
                echo CJSON::encode(array('success' => false,
                                         'errors' => $model->errors));
            }
        }
    }

    public function actionGetFieldsTemplates() {
        if(!isset($_GET['table'])) {
            echo CJSON::encode(array('success' => false,
                                     'data' => array()));
            exit();
        }

        echo CJSON::encode(array('success' => true,
                                 'data' => TasuFieldsTemplate::model()->findAll('t.table = :table', array(':table' => $_GET['table']))));
    }

    public function actionGetKeysTemplates() {
        if(!isset($_GET['table'])) {
            echo CJSON::encode(array('success' => false,
                                     'data' => array()));
            exit();
        }
        echo CJSON::encode(array('success' => true,
                                 'data' => TasuKeysTemplate::model()->findAll('t.table = :table', array(':table' => $_GET['table']))));
    }

    public function actionGetOneKeyTemplate() {
        if(!isset($_GET['id'])) {
            echo CJSON::encode(array('success' => false,
                                     'data' => array()));
            exit();
        }
        echo CJSON::encode(array('success' => true,
                                 'data' => TasuKeysTemplate::model()->findByPk($_GET['id'])));
    }

    public function actionGetOneFieldTemplate() {
        if(!isset($_GET['id'])) {
            echo CJSON::encode(array('success' => false,
                                     'data' => array()));
            exit();
        }
        echo CJSON::encode(array('success' => true,
                                 'data' => TasuFieldsTemplate::model()->findByPk($_GET['id'])));
    }

    public function actionDeleteFieldsTemplate($id) {
        try {
            $template = TasuFieldsTemplate::model()->findByPk($id);
            $template->delete();
            echo CJSON::encode(array('success' => true,
                                     'data' => 'Шаблон успешно удалён.'));
        } catch(Exception $e) {
            // Это нарушение целостности FK
            echo CJSON::encode(array('success' => false,
                                     'error' => 'На данную запись есть ссылки!'));
        }
    }

    public function actionDeleteKeysTemplate($id) {
        try {
            $template = TasuKeysTemplate::model()->findByPk($id);
            $template->delete();
            echo CJSON::encode(array('success' => true,
                                     'data' => 'Шаблон успешно удалён.'));
        } catch(Exception $e) {
            // Это нарушение целостности FK
            echo CJSON::encode(array('success' => false,
                                     'error' => 'На данную запись есть ссылки!'));
        }
    }

    // Проверяем есть ли файл на диске и в базе
    private function checkDbFile($fileFromDb)
    {
        if($fileFromDb == null) {
            echo CJSON::encode(array('success' => false,
                                     'error' => 'Такого файла не существует.'));
            exit();
        }
        if(!(file_exists(getcwd().$fileFromDb->path))) {
            echo CJSON::encode(array('success' => false,
                                     'error' => 'Такого файла нет на диске'));
            exit();
        }
    }
    
    // Считает кол-во строк в файле CSV
    private function countRowFile($file)
    {
        $result = 0;
        while(!feof($file)) {
            $row = fgets($file);
            $result ++;
        }
        return $result;
    }
    
    /*
    public function actionImport($table, $fields, $key, $file, $per_query, $rows_num, $rows_numall, $rows_accepted, $rows_discarded, $rows_error, $processed) {
        
         
        // Вынимаем файл из базы
        $fileFromDb = FileModel::model()->findByPk($file);
        // Проверим наличие файла на диске и в базе
        $this->checkDbFile($fileFromDb);

        $csvHeaders = $this->getTasuCsvFileHeaders($fileFromDb->path);

        $filesize = filesize(getcwd().$fileFromDb->path);
        $file = fopen(getcwd().$fileFromDb->path, 'r');

        // Если кол-во строк нулевое - посчитаем общее количество строк
        if($rows_numall == 0) {
          $this->countRowFile($file);
        }

        fseek($file, $processed); // Это позволяет читать файл с того места, где закончили

        // Расписываем фильтры: формировать sql-запрос можно один раз
        $fields = CJSON::decode($fields);
        $key = CJSON::decode($key);
        // Формирование происходит в два этапа. Сначала расписывается ключ
      
        $Context = new TasuImportContext($fields,$table,$key);
       
    
        $count = 0;
        $headerFlag = false; // Флаг о том, что заголовки прочитаны
        while(!feof($file)) {
            if($rows_num == 0 && $headerFlag === false) {
                $currentRow = fgets($file); // Это строка заголовков
                $headerFlag = true;
                continue;
            }
            if($count < $per_query) {
                $currentRow = fgets($file);
                $processed += mb_strlen($currentRow);
                
                $Context->onBeforeRowTreating($currentRow);
                // Делим строку в CSV в массив
                
                
                $command = Yii::app()->db->createCommand($Context->getSqlCopy());
                var_dump($Context->getSqlCopy());
               // $elements = @$command->queryAll(true, array(), true);
                //var_dump($elements);
                //exit();
                if($elements === false) { // Строка с ошибкой
                    $rows_error++;
                } elseif(count($elements) > 0) {
                    // Строка есть, пропускать-не импортировать
                    //   TODO: Сделать обновление строки
                    //  Перебираем обновляемые поля текущей записи и добавляемой строки и сравниваем.
                    //  Если хотя бы значение одного поля в файле не равно хотя бы одному полю в базе -
                    //    выполняем update
                    
                    
                    
                    $rows_discarded++;
                } else {
                    $rows_accepted++;
                    // TODO: здесь сделать вставку в таблицу новых данных
                    //$query = $sqlInsert.' '.$sqlInsertFields.' '.$sqlInsertPlaceholdersCopy;
                    $query = $Context->getInsertSql();
                    var_dump($Context->getInsertSql());
                  //  $command = Yii::app()->db->createCommand($query);
                    // Пытаемся выполнить команду
                    try
                    {
                       // $result = @$command->execute(array(), true);
                        if($result === false)
                        {
                            // Запрос не выполнен, т.к. строка не вставлена. Это ошибка
                            $rows_error++;
                        }
                    }
                    catch (Exception $e)
                    {
                        // Если произошло исключение - значит строка не вставлена
                        $rows_error++;
                    }
                    
                
                    
                    
                }
                // После того, как запрос прошёл, сбросить аргументы на то, что было
                $data = $tempArr;
                $rows_num++;
                $count++;
                $command = Yii::app()->db->createCommand($sql, $data);
            } else {
                break;
            }
        }
        fclose($file);
        echo CJSON::encode(array('success' => true,
                                 'data' => array(
                                     'rowsNumAll' => $rows_numall,
                                     'rowsNum' => $rows_num,
                                     'rowsError' => $rows_error,
                                     'rowsAccepted' => $rows_accepted,
                                     'rowsDiscarded' => $rows_discarded,
                                     'filesize' => $filesize,
                                     'processed' => $processed // Кол-во обработанных байт
                                 )));
    }*/
    
    // Сама процедура импорта
    public function actionImport($table, $fields, $key, $file, $per_query, $rows_num, $rows_numall, $rows_accepted, $rows_discarded, $rows_error, $processed) {
        // Вынимаем файл из базы
        $fileFromDb = FileModel::model()->findByPk($file);
        // Проверим наличие файла на диске и в базе
        $this->checkDbFile($fileFromDb);

        $csvHeaders = $this->getTasuCsvFileHeaders($fileFromDb->path);

        $filesize = filesize(getcwd().$fileFromDb->path);
        $file = fopen(getcwd().$fileFromDb->path, 'r');

        // Если кол-во строк нулевое - посчитаем общее количество строк
        if($rows_numall == 0) {
          $this->countRowFile($file);
        }

        fseek($file, $processed); // Это позволяет читать файл с того места, где закончили

        // Расписываем фильтры: формировать sql-запрос можно один раз
        $fields = CJSON::decode($fields);
        $key = CJSON::decode($key);
        // Формирование происходит в два этапа. Сначала расписывается ключ
        $sql = 'SELECT * FROM mis.'.$table.' t WHERE ';
        $sqlWhere = ''; // Строка для предложения Where
        $sqlInsert = 'INSERT INTO mis.'.$table.' ';
        $sqlInsertFields = '(';
        $sqlInsertPlaceholders = 'VALUES(';
        $data = array();
        $updateFieldValues = array();
        
        foreach($fields as $obj) {
            $sqlWhere .= 't.'.$obj['dbField'].' = :'.$obj['dbField'].' AND ';
            $data[':'.$obj['dbField']] = $obj['tasuField']; // Пока нет данных, то ставим соответствие номер поля в CSV
            $sqlInsertFields .= $obj['dbField'].',';
            $sqlInsertPlaceholders .= ':'.$obj['dbField'].',';
            $updateFieldValues[$obj['dbField']] = '';
        }
        $sqlWhere = mb_substr($sqlWhere, 0, mb_strlen($sqlWhere) - 5);
        $sqlInsertFields = mb_substr($sqlInsertFields, 0, mb_strlen($sqlInsertFields) - 1);
        $sqlInsertFields .= ')';
        $sqlInsertPlaceholders = mb_substr($sqlInsertPlaceholders, 0, mb_strlen($sqlInsertPlaceholders) - 1);
        $sqlInsertPlaceholders .= ')';

        $count = 0;
        $headerFlag = false; // Флаг о том, что заголовки прочитаны
        while(!feof($file)) {
            if($rows_num == 0 && $headerFlag === false) {
                $currentRow = fgets($file); // Это строка заголовков
                $headerFlag = true;
                continue;
            }
            if($count < $per_query) {
                $currentRow = fgets($file);
                $processed += mb_strlen($currentRow);
                // Делим строку в CSV в массив
                $csvArr = explode(',', $currentRow);
                $tempArr = $data;
                $sqlCopy = $sql.$sqlWhere;
                $sqlInsertPlaceholdersCopy = $sqlInsertPlaceholders;
                foreach($data as $key => &$field) {
                    $field = $csvArr[$field];
                    // Заменяем в запросе
                   // $sqlCopy = str_replace($key, "'".str_replace("'","''",mb_convert_encoding($field, "UTF-8"))."'", $sqlCopy);
                    //$sqlInsertPlaceholdersCopy  = str_replace($key, "'".str_replace("'","''",mb_convert_encoding($field, "UTF-8"))."'", $sqlInsertPlaceholdersCopy);
                    $sqlCopy = str_replace($key, "'".str_replace("'","''",$field)."'", $sqlCopy);
                    $sqlInsertPlaceholdersCopy  = str_replace($key, "'".str_replace("'","''",$field)."'", $sqlInsertPlaceholdersCopy);
                }

                $command = Yii::app()->db->createCommand($sqlCopy);
                $elements = @$command->queryAll(true, array(), true);
                //var_dump($elements);
                //exit();
                if($elements === false) { // Строка с ошибкой
                    $rows_error++;
                } elseif(count($elements) > 0) {
                    // Строка есть, пропускать-не импортировать
                    //   TODO: Сделать обновление строки
                    //  Перебираем обновляемые поля текущей записи и добавляемой строки и сравниваем.
                    //  Если хотя бы значение одного поля в файле не равно хотя бы одному полю в базе -
                    //    выполняем update
                    
                    
                    
                    $rows_discarded++;
                } else {
                    $rows_accepted++;
                    // TODO: здесь сделать вставку в таблицу новых данных
                    $query = $sqlInsert.' '.$sqlInsertFields.' '.$sqlInsertPlaceholdersCopy;
                    $command = Yii::app()->db->createCommand($query);
                    // Пытаемся выполнить команду
                    try
                    {
                        $result = @$command->execute(array(), true);
                        if($result === false)
                        {
                            // Запрос не выполнен, т.к. строка не вставлена. Это ошибка
                            $rows_error++;
                        }
                    }
                    catch (Exception $e)
                    {
                        // Если произошло исключение - значит строка не вставлена
                        $rows_error++;
                    }
                    
                }
                // После того, как запрос прошёл, сбросить аргументы на то, что было
                $data = $tempArr;
                $rows_num++;
                $count++;
                $command = Yii::app()->db->createCommand($sql, $data);
            } else {
                break;
            }
        }
        fclose($file);
        echo CJSON::encode(array('success' => true,
                                 'data' => array(
                                     'rowsNumAll' => $rows_numall,
                                     'rowsNum' => $rows_num,
                                     'rowsError' => $rows_error,
                                     'rowsAccepted' => $rows_accepted,
                                     'rowsDiscarded' => $rows_discarded,
                                     'filesize' => $filesize,
                                     'processed' => $processed // Кол-во обработанных байт
                                 )));
    }


    public function actionViewIn() {
		//$tasuTap = new TasuTap();
		
        $this->render('viewin', array(
            'modelAdd' => new FormTasuBufferAdd()
        ));
    }

    // Получить все приёмы для импорта в ТАСУ для последнего задания
    public function actionGetBufferGreetings() {
        $rows = $_GET['rows'];
        $page = $_GET['page'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        // Фильтры поиска
        if(isset($_GET['filters']) && trim($_GET['filters']) != '') {
            $filters = CJSON::decode($_GET['filters']);
        } else {
            $filters = false;
        }

        $model = new TasuGreetingsBuffer();
        $num = $model->getLastBuffer($filters);

        $totalPages = ceil(count($num) / $rows);
        $start = $page * $rows - $rows;

        $buffer = $model->getLastBuffer($filters, $sidx, $sord, $start, $rows);
        foreach($buffer as &$element) {
            $parts = explode('-', $element['patient_day']);
            $element['patient_day'] = $parts[2].'.'.$parts[1].'.'.$parts[0];
            if($element['is_beginned'] == null && $element['is_accepted'] == null) {
                $element['status'] = 'Не начат';
            } elseif($element['is_beginned'] == 1 && $element['is_accepted'] == null) {
                $element['status'] = 'Начат, идёт';
            } elseif($element['is_beginned'] == 1 && $element['is_accepted'] == 1) {
                $element['status'] = 'Окончен';
            } else {
                $element['status'] = 'Неизвестно';
            }
        }

        echo CJSON::encode(array(
            'success' => true,
            'rows' => $buffer,
            'total' => $totalPages,
            'records' => count($num)));
    }

    public function actionGetBufferHistoryGreetings() {
        $rows = $_GET['rows'];
        $page = $_GET['page'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        // Фильтры поиска
        if(isset($_GET['filters']) && trim($_GET['filters']) != '') {
            $filters = CJSON::decode($_GET['filters']);
        } else {
            $filters = false;
        }

        $model = new TasuGreetingsBufferHistory();
        $num = $model->getRows($filters);

        $totalPages = ceil(count($num) / $rows);
        $start = $page * $rows - $rows;

        $buffer = $model->getRows($filters, $sidx, $sord, $start, $rows);
        echo CJSON::encode(array(
            'success' => true,
            'rows' => $buffer,
            'total' => $totalPages,
            'records' => count($num)));
    }

    /* Получить все приёмы, которые не занесены в буфер */
    public function actionGetNotBufferedGreetings() {
        $notBuffered = TasuGreetingsBuffer::model()->getAllNotBuffered();
        $forCombo = array();
        foreach($notBuffered as &$element) {
            $parts = explode('-', $element['patient_day']);
            $forCombo[$element['id']] = '№'.$element['id'].', пациент '.$element['patient_fio'].', врач '.$element['doctor_fio'].', дата приёма - '.$parts[2].'.'.$parts[1].'.'.$parts[0]; // TODO
        }
        echo CJSON::encode(array(
            'success' => true,
            'data' => $forCombo
        ));
    }

    /* Добавить приём к буферу */
    public function actionAddGreetingToBuffer() {
        if(isset($_POST['FormTasuBufferAdd'], $_POST['FormTasuBufferAdd']['greetingId'])) {
            // Проверим, есть ли такой приём, и, если есть добавим в буфер
            $issetGreeting = SheduleByDay::model()->findByPk($_POST['FormTasuBufferAdd']['greetingId']);
            if($issetGreeting != null) {
                $buffer = new TasuGreetingsBuffer();
                $buffer->greeting_id = $issetGreeting->id;
                $buffer->import_id = $buffer->getLastImportId();
                if(!$buffer->save()) {
                    echo CJSON::encode(array('success' => false,
                                             'text' => 'Ошибка сохранения буфера выгрузки ТАСУ.'));
                }
            }
            echo CJSON::encode(array(
                'success' => true,
                'data' => array()
            ));
        }
    }

    /* Удалить элемент из выгрузки */
    public function actionDeleteFromBuffer() {
        if(!isset($_GET['id'])) {
            echo CJSON::encode(array(
                'success' => false,
                'error' => 'Не задан элемент для удаления!'
            ));
            exit();
        }
        TasuGreetingsBuffer::model()->deleteByPk($_GET['id']);
        echo CJSON::encode(array(
            'success' => true,
            'data' => 'Приём успешно удалён из очереди для выгрузки.'
        ));
    }

    /* Очистка всего буфера */
    public function actionClearBuffer() {
        TasuGreetingsBuffer::model()->deleteAll();
        echo CJSON::encode(array(
            'success' => true,
            'data' => 'Буфер успешно очищен.'
        ));
    }

    /* Добавление всех возможных приёмов */
    public function actionAddAllGreetings() {
        $notBuffered = TasuGreetingsBuffer::model()->getAllNotBuffered();
        $lastImportId = null;
        foreach($notBuffered as $key => $element) {
            $buffer = new TasuGreetingsBuffer();
            if($lastImportId == null) {
                $lastImportId = $buffer->getLastImportId();
            }
            $buffer->greeting_id = $element['id'];
            $buffer->import_id = $lastImportId;
            if(!$buffer->save()) {
                echo CJSON::encode(array(
                    'success' => false,
                    'data' => 'Невозможно добавить приём в буфер выгрузки!'
                ));
                exit();
            }
        }

        echo CJSON::encode(array(
            'success' => true,
            'data' => 'Успешно сформирован список приёмов на выгрузку!'
        ));
    }

    public function actionImportGreetings() {
        // Делаем выборку чанка приёмов с целью последующей выгрузки
        $currentGreeting = isset($_GET['currentGreeting']) ? $_GET['currentGreeting'] : false;
        $limit = isset($_GET['rowsPerQuery']) ? $_GET['rowsPerQuery'] : false;
        $sord = 'asc';
        $sidx = 'id';
        $start = 0;

        $buffer = TasuGreetingsBuffer::model()->getLastBuffer(false, $sidx, $sord, $start, $limit, $currentGreeting);
        $logs = array();
        // Смотрим буфер
        foreach($buffer as $element) {
            $bufferGreetingModel = TasuGreetingsBuffer::model()->findByPk($element['id']);
            if($bufferGreetingModel != null) {
                $bufferGreetingModel->status = 1;
                if(!$bufferGreetingModel->save()) {
                    $logs[] = 'Невозможно изменить статус приёма в буфере '
                } else {

                }
            }
        }
        echo CJSON::encode(array(
            'success' => true,
            'data' => array(
                'processed' => 50,
                'lastGreetingId' => 1,
                'totalRows' => 1005,
                'logs' => array('Сообщение!')
            )
        ));
    }
}
?>