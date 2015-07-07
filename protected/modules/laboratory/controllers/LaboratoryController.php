<?php

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
				$this->error('Данные не были обновлены');
			} else {
				$this->success('Данные сохранены, направление закрыто');
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	public function getAnalyzerTabs() {
		$analyzers = Analyzer::model()->listTabs();
		if (count($analyzers) != 0) {
			return $analyzers;
		};
		return $analyzers + [
			'empty' => [
				'label' => 'Нет доступных анализаторов',
				'disabled' => 'true'
			]
		];
	}

	public function actionHistory() {
		return $this->render('history');
	}

	/**
	 * Override that method to return controller's model
	 * @return ActiveRecord - Controller's model instance
	 */
	public function getModel() {
		return null;
	}
}