<?php

class MedcardController extends ControllerEx {

	/**
	 * Render page with medcards
	 */
    public function actionNew() {
		if (!$number = Yii::app()->getRequest()->getQuery('n')) {
			$number = Laboratory_CardNumberGenerator::getGenerator()->generate();
		}
        $this->render("new", [
			"number" => $number
		]);
    }

	/**
	 * That action will load full information about medcard with
	 * patient's addresses
	 *
	 * @in (POST):
	 *  + number - Medcard number
	 * @out (JSON):
	 *  + model - Model with full information about medcard
	 *  + status - True on success
	 *  + [message] - Message with response
	 */
	public function actionLoad() {
		try {
			$row = Laboratory_MedcardEx::model()->fetchInformationLaboratoryLike($this->get('number'));
			if ($row == null) {
				throw new CException('Unresolved medcard number "'. $this->get('number') .'"');
			}
			$this->leave([
				'model' => $row
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Search action, which accepts array with search serialized form
	 * models (Laboratory_Form_MedcardSearch + Laboratory_Form_AnalysisSearch). That action will
	 * fetch form's values and build search condition form form model
	 * and return Table widget with medcards
	 *
	 * @in (POST):
	 *  + model - Array with serialized forms (string)
	 * @out (JSON):
	 *  + [component] - Component with new rendered table with medcards
	 *  + status - False if form validation failed or true on success
	 */
	public function actionSearch() {
		try {
			$medcard = $this->requirePost('Laboratory_Form_MedcardSearch');
			$provider = $this->requirePost('provider');
			$config = Yii::app()->getRequest()->getQuery('config', []);
			$criteria = new CDbCriteria();
			$like = [
				'card_number',
				'surname',
				'name',
				'patronymic',
			];
			$compare = [];
			foreach ($medcard as $key => $value) {
				if ($value == -1 || empty($value)) {
					continue;
				}
				if (in_array($key, $like)) {
					$criteria->addSearchCondition("lower($key)", mb_strtolower($value, 'UTF-8'));
				} else {
					$compare[$key] = $value;
				}
			}
			if (count($compare) > 0) {
				$criteria->addColumnCondition($compare);
			}
			if (!empty($criteria->condition)) {
				$config += [
					'condition' => [
						'condition' => $criteria->condition,
						'params' => $criteria->params,
					]
				];
			}
			$this->leave([
				'component' => $this->getWidget('GridTable', [
					'provider' => new $provider($config)
				])
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * That action will generate card number for laboratory
	 *
	 * @out (JSON):
	 *  + [number] - Just generated card number for laboratory
	 *  + status - Response status, true or false
	 *  + [message] - Message with response
	 *
	 * @throws Exception
	 */
	public function actionGenerate() {
		try {
			$this->leave([
				'message' => 'Номер карты сгенерирован',
				'number' => Laboratory_CardNumberGenerator::getGenerator()->generate()
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
     * Override that method to return controller's model
     * @return ActiveRecord - Controller's model instance
     */
    public function getModel() {
        return new Laboratory_Medcard();
    }
}