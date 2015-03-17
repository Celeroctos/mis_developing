<?php

class AnalysisTypeParamController extends Controller {

    public $layout = 'application.modules.guides.views.layouts.index';

    /* 	public function actionView()
      {
      $this->actionIndex();
      } */

    public function actionAdd($id) {

        $model = new AnalysisTypeParam('analysistypeparam.create');
        $model->analysis_type_id = ['analysis_type_id' => $id];
        $this->renderPartial('form', array('model' => $model), false, true);
        ;
    }

    public function actionCreate() {

        $model = new AnalysisTypeParam('analysistypeparam.create');
        if (isset($_POST['AnalysisTypeParam'])) {
            $model->attributes = $_POST['AnalysisTypeParam'];
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    echo 'success';
                    Yii::app()->end();
                }
            }
        }
        $this->renderPartial('form', array('model' => $model), false, true);
    }

    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        if (isset($_POST['AnalysisTypeParam'])) {
            $model->scenario = 'analysistypeparam.update';
            $model->attributes = $_POST['AnalysisTypeParam'];
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
        $model = new AnalysisTypeParam('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AnalysisTypeParam'])) {
            $model->attributes = $_GET['AnalysisTypeParam'];
            Yii::app()->session['analysis_type_id'] = $model->attributes['analysis_type_id'];
        }
        $analysistype = Yii::app()->session['analysis_type_id'];
        $model->attributes = ['analysis_type_id' => $analysistype];

        $this->render('index', array(
            'model' => $model, 'analysis_type_id' => $model->attributes['analysis_type_id'],
        ));
    }

    public function loadModel($id) {
        $model = AnalysisTypeParam::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
