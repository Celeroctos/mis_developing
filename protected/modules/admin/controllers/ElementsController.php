<?php
class ElementsController extends Controller {
    public $layout = 'application.modules.admin.views.layouts.index';

    public function actionView() {
        // Категории
        $categoriesModel = new MedcardCategorie();
        $categories = $categoriesModel->getRows(false);
        $categoriesList = array();
        foreach($categories as $index => $categorie) {
            $categoriesList[$categorie['id']] = $categorie['name'];
        }

        // Справочники
        $guidesModel = new MedcardGuide();
        $guides = $guidesModel->getRows(false);
        $guidesList = array('-1' => 'Нет');
        foreach($guides as $index => $guide) {
            $guidesList[$guide['id']] = $guide['name'];
        }

        $elementModel = new MedcardElement();
        $this->render('elementsView', array(
            'model' => new FormElementAdd(),
            'typesList' => $elementModel->getTypesList(),
            'categoriesList' => $categoriesList,
            'guidesList' => $guidesList
        ));
    }

    public function actionGet() {
        try {
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

            $model = new MedcardElement();
            $num = $model->getRows($filters);

            $totalPages = ceil(count($num) / $rows);
            $start = $page * $rows - $rows;

            $elements = $model->getRows($filters, $sidx, $sord, $start, $rows);
            $typesList = $model->getTypesList();
            foreach($elements as $key => &$element) {
                $temp = $element['type'];
                $element['type_id'] = $temp;
                $element['type'] = $typesList[$element['type']];
                if($element['guide_id'] == null) {
                    $element['guide_id'] = -1;
                }
            }
            echo CJSON::encode(
                array('rows' => $elements,
                    'total' => $totalPages,
                    'records' => count($num))
            );
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionEdit() {
        $model = new FormElementAdd();
        if(isset($_POST['FormElementAdd'])) {
            $model->attributes = $_POST['FormElementAdd'];
            if($model->validate()) {
                $element = MedcardElement::model()->find('id=:id', array(':id' => $_POST['FormElementAdd']['id']));
                $this->addEditModel($element, $model, 'Категория успешно добавлена.');
            } else {
                echo CJSON::encode(array('success' => 'false',
                    'errors' => $model->errors));
            }
        }
    }

    public function actionAdd() {
        $model = new FormElementAdd();
        if(isset($_POST['FormElementAdd'])) {
            $model->attributes = $_POST['FormElementAdd'];
            if($model->validate()) {
                $element = new MedcardElement();
                $this->addEditModel($element, $model, 'Элемент успешно добавлен.');
            } else {
                echo CJSON::encode(array('success' => 'false',
                                         'errors' => $model->errors));
            }
        }

    }

    private function addEditModel($element, $model, $msg) {
        $element->type = $model->type;
        $element->categorie_id = $model->categorieId;
        $element->label = $model->label;
        if($model->guideId != -1) { // Если справочник выбран
            $element->guide_id = $model->guideId;
        }

        if($element->save()) {
            echo CJSON::encode(array('success' => true,
                                    'text' => $msg));
        }
    }

    public function actionDelete($id) {
        try {
            $element = MedcardElement::model()->findByPk($id);
            $element->delete();
            echo CJSON::encode(array('success' => 'true',
                'text' => 'Категория успешно удалена.'));
        } catch(Exception $e) {
            // Это нарушение целостности FK
            echo CJSON::encode(array('success' => 'false',
                'error' => 'На данную запись есть ссылки!'));
        }
    }

    public function actionGetone($id) {
        $model = new MedcardElement();
        $element = $model->getOne($id);
        if($element['guide_id'] == null) {
            $element['guide_id'] = -1;
        }
        echo CJSON::encode(array('success' => true,
                                 'data' => $element)
        );
    }
}

?>