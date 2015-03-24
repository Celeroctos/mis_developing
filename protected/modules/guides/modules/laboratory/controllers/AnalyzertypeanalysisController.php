<?php

class AnalyzerTypeAnalysisController extends Controller {

    public $layout = 'application.modules.guides.views.layouts.index';

    public function actionView() {
        $this->actionIndex();
    }

    public function actionAdd($id) {

        $model = new AnalyzerTypeAnalysis('analyzertypeanalysiss.create');
        $model->analyser_type_id = ['analyser_type_id' => $id];
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    public function actionCreate() {

        $model = new AnalyzerTypeAnalysis('analyzertypeanalysiss.create');

        if (isset($_POST['AnalyzerTypeAnalysis'])) {
            $model->attributes = $_POST['AnalyzerTypeAnalysis'];
            $analysertype = Yii::app()->session['analyser_type_id'];
            $model->attributes = ['analyser_type_id' => $analysertype];
            if ($model->save()) {
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        if (isset($_POST['AnalyzerTypeAnalysis'])) {
            $model->scenario = 'analyzertypeanalysiss.update';
            $model->attributes = $_POST['AnalyzerTypeAnalysis'];
        $analysertype = Yii::app()->session['analyser_type_id'];
        $model->attributes = ['analyser_type_id' => $analysertype];
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
        $model = new AnalyzerTypeAnalysis('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AnalyzerTypeAnalysis'])) {
            $model->attributes = $_GET['AnalyzerTypeAnalysis'];
            Yii::app()->session['analyser_type_id'] = $model->attributes['analyser_type_id'];
        }
        $analysertype = Yii::app()->session['analyser_type_id'];
        $model->attributes = ['analyser_type_id' => $analysertype];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = AnalyzerTypeAnalysis::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
