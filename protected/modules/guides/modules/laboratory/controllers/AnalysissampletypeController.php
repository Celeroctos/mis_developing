<?php

class AnalysisSampleTypeController extends Controller {

    public $layout = 'application.modules.guides.views.layouts.index';

    public function actionView($id) {
        $this->actionIndex();
    }

    public function actionCreate() {

        $model = new AnalysisSampleType('AnalysisSampleType.create');

        if (isset($_POST['AnalysisSampleType'])) {
            $model->attributes = $_POST['AnalysisSampleType'];
            if ($model->save()) {
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        if (isset($_POST['AnalysisSampleType'])) {
            $model->scenario = 'AnalysisSampleType.update';
            $model->attributes = $_POST['AnalysisSampleType'];
            if ($model->save()) {
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('form', array('model' => $model), false, true);
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
        $model = new AnalysisSampleType('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AnalysisSampleType']))
            $model->attributes = $_GET['AnalysisSampleType'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = AnalysisSampleType::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}