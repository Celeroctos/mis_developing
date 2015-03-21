<?php

class MachineController extends Controller {

    public $layout = 'application.modules.guides.views.layouts.index';
    public $defaultAction = 'index';

    public function actionView() {
        $this->actionIndex();
    }

    public function actionCreate() {

        $model = new Machine('machines.create');

        if (isset($_POST['Machine'])) {
            $model->attributes = $_POST['Machine'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    echo 'success';
                    Yii::app()->end();
                } else {
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }
        $analyzertypeList = AnalyzerType::getAnalyzerTypeListData('insert');
        $this->renderPartial('form', array(
            'model' => $model,
            'analyzertypeList' => $analyzertypeList,
                ), false, true);
    }

    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        if (isset($_POST['Machine'])) {
            $model->scenario = 'machines.update';
            $model->attributes = $_POST['Machine'];
            if ($model->save()) {
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('form', array(
            'model' => $model,
            'analyzertypeList' => AnalyzerType::getAnalyzerTypeListData('insert'),
                ), false, true);
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Machine('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Machine']))
            $model->attributes = $_GET['Machine'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Machine::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
