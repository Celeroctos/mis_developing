<?php

class AnalysisTypeController extends Controller
{
    public $layout = 'application.modules.guides.views.layouts.index';

    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate() {

        $model = new AnalysisType('analysistypes.create');

        if (isset($_POST['AnalysisType'])) {
            $model->attributes = $_POST['AnalysisType'];
            if ($model->save()) {
                    echo 'success';
                    Yii::app()->end();
            }
        }
            $this->renderPartial('form', array(
            'model' => $model,
            'analysistypetemplate'=>null,
), false, true);
    }

    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        if (isset($_POST['AnalysisType'])) {
            $model->scenario = 'analysistypes.update';
            $model->attributes = $_POST['AnalysisType'];
            if ($model->save()) {
                    echo 'success';
                    Yii::app()->end();
            }
        }
            $analysistypetemplate = AnalysisTypeTemplate::model()->search($id);
            $this->renderPartial('form', array(
            'model' => $model,
            'analysistypetemplate'=>$analysistypetemplate,
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
        $model = new AnalysisType('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AnalysisType']))
            $model->attributes = $_GET['AnalysisType'];

        $this->render('index', array(
            'model' => $model,
        ));
    }


    public function loadModel($id) {
        $model = AnalysisType::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
