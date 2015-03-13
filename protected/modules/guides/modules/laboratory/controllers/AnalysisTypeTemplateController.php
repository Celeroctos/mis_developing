<?php

class AnalysisTypeTemplateController extends Controller
{
    public $layout = 'application.modules.guides.views.layouts.index';

	public function actionView()
	{
        $this->actionIndex();
	}

    public function actionCreate($analysis_type_id) {

        $model = new AnalysisTypeTemplate('analysistypetemplates.create');
        $model->attributes = $analysis_type_id;

        if (isset($_POST['AnalysisTypeTemplate'])) {
            $model->attributes = $_POST['AnalysisTypeTemplate'];
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

        if (isset($_POST['AnalysisTypeTemplate'])) {
            $model->scenario = 'analysistypetemplate.update';
            $model->attributes = $_POST['AnalysisTypeTemplate'];
            if ($model->save()) {
                    echo 'success';
                    Yii::app()->end();
            }
        }
        if (Yii::app()->request->isAjaxRequest)
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
#        $model=new AnalysisType;
        $model = new AnalysisTypeTemplate();
        $model->unsetAttributes();  // clear any default values
       if (isset($_GET['AnalysisTypeTemplate']))
            $model->attributes = $_GET['AnalysisTypeTemplate'];

        $this->render('index', array(
            'model' => $model,
            'dataProvider'=>$model->search(),
            'analysistypesList' => AnalysisType::getAnalysisTypeListData('insert'),
            'analysis_type_id' => $model->analysis_type_id,
        ));
    }


    public function loadModel($id) {
        $model = AnalysisTypeTemplate::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
public function actionCondUpdate()
{
    $analysis_type_id = null;
    $model = new AnalysisTypeTemplate();
    $model->unsetAttributes();  // clear any default values
    if(isset($_POST['SelectType']))
    {
            $model->scenario = 'analysistypetemplate.search';
                $model->analysis_type_id = $_POST['SelectType'];
        }

        $criteria=new CDbCriteria;
        $criteria->with=[['analysis_types'=>['together'=>true, 'joinType'=>'LEFT JOIN']],
        ['analysis_params'=>['together'=>true, 'joinType'=>'LEFT JOIN']]];

/*        if ($model->analysis_type_id)  
            $criteria->compare('analysis_type_id', $model->analysis_type_id);
        else 
            $criteria->compare('analysis_type_id', 0);*/
        
    $dataProvider = new CActiveDataProvider('AnalysisTypeTemplate', [
            'pagination'=>['pageSize'=>15],
            'criteria'=>$criteria,
            'sort'=>[
                    'attributes'=>[
                        'seq_number',
                    ],
                    'defaultOrder'=>[
                            'seq_number'=>CSort::SORT_ASC,
                        ],
            ]
        ]);

    if(Yii::app()->request->isAjaxRequest)
    {
        $this->renderPartial('grid', 
        array(
        'model' => $model,
        'dataProvider'=>$dataProvider, 
        'analysistypesList' => AnalysisType::getAnalysisTypeListData('insert'),
        'analysis_type_id' => $model->analysis_type_id,
        )
        );
        Yii::app()->end();
    }
} 
}
