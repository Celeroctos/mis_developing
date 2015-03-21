<?php

class AnalysisTypeSampleController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'application.modules.guides.views.layouts.index';

    public function actionAdd($id) {

        $model = new AnalysisTypeSample;
        $model->analysis_type_id = ['analysis_type_id' => $id];
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $model = new AnalysisTypeSample;

        if (isset($_POST['AnalysisTypeSample'])) {
            $model->attributes = $_POST['AnalysisTypeSample'];
            $analysistype = Yii::app()->session['analysis_type_id'];
            $model->attributes = ['analysis_type_id' => $analysistype];
            if ($model->save()) {
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        if (isset($_POST['AnalysisTypeSample'])) {
            $model->attributes = $_POST['AnalysisTypeSample'];
            $analysistype = Yii::app()->session['analysis_type_id'];
            $model->attributes = ['analysis_type_id' => $analysistype];
            if ($model->save()) {
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
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
     * Manages all models.
     */
    public function actionIndex() {
        $model = new AnalysisTypeSample('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AnalysisTypeSample'])) {
            $model->attributes = $_GET['AnalysisTypeSample'];
            Yii::app()->session['analysis_type_id'] = $model->attributes['analysis_type_id'];
        }
        $analysistype = Yii::app()->session['analysis_type_id'];
        $model->attributes = ['analysis_type_id' => $analysistype];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = AnalysisTypeSample::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
