<?php

namespace laboratory\controllers;

use Analyzer;
use ControllerEx;
use Exception;
use Laboratory_AnalysisResult;
use Laboratory_Direction;
use Laboratory_Form_AnalysisResult;
use Yii;

class LaboratoryController extends ControllerEx {

	public function actionView() {
		return $this->render('view', [
			'analyzers' => $this->getAnalyzerTabs()
		]);
	}

	public function actionTabs() {
		try {
			$analyzers = $this->getAnalyzerTabs();
			$result = [];
			foreach ($analyzers as $i => $analyzer) {
				$result[] = [
					'id' => $analyzer['data-id'],
					'directions' => $analyzer['data-directions']
				];
			}
			$this->leave([
				'result' => $result
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function actionConfirm() {
        $transaction = Yii::app()->getDb()->beginTransaction();
		try {
			$form = new Laboratory_Form_AnalysisResult();
			$form->setAttributes(Yii::app()->getRequest()->getPost('Laboratory_Form_AnalysisResult'));
			if (!$form->validate()) {
				$this->postErrors($form->getErrors());
			}
			$total = 0;
			foreach ($form->result as $id => $result) {
				$total += Laboratory_AnalysisResult::model()->updateByPk($id, [
					'val' => $result
				]);
			}
			Laboratory_Direction::model()->updateByPk($form->id, [
				'status' => Laboratory_Direction::STATUS_CLOSED
			]);
			if ($total < count($form->result)) {
                $transaction->rollback();
				$this->error('Произошла ошибка. Данные не были обновлены');
			} else {
                $transaction->commit();
				$this->success('Данные сохранены, направление закрыто');
			}
		} catch (Exception $e) {
            $transaction->rollback();
			$this->exception($e);
		}
	}

	public function getAnalyzerTabs() {
		$analyzers = Analyzer::model()->listTabs();
		if (empty($analyzers)) {
            return [ 'empty' => [
                'label' => 'Нет доступных анализаторов',
                'disabled' => 'true'
            ] ];
		} else {
            return $analyzers;
        }
	}
}