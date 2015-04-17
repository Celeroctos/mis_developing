<?php

class DirectionController extends Controller2 {

	/**
	 * Default view action
	 */
    public function actionView() {
        $this->render("view");
    }

	/**
	 * That action will load widget with direction
	 * register with just appended LIS medcard id
	 *
	 * @in (GET):
	 *  + medcard - Medcard identification number from
	 * laboratory module
	 *
	 * @out (JSON):
	 *  + [message] - Response message with error or success
	 *  + [component] - Widget component for
	 *  + status - Response status, true or false
	 *
	 * @throws Exception
	 */
	public function actionLoadWidget() {
		try {
			if (($id = Yii::app()->getRequest()->getQuery("medcard")) == null) {
				throw new CException("Can't resolve \"medcard\" identification number as query parameter");
			}
			if (LMedcard::model()->findByPk($id) == null) {
				throw new CException("Unresolved laboratory's medcard identification number \"{$id}\"");
			}
			$widget = $this->getWidget("AutoForm", [
				"model" => new LDirectionForm("laboratory.treatment.register", [
					"medcard_id" => $id
				])
			]);
			$this->leave([
				"component" => $widget,
				"status" => true
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * Register some form's values in database, it will automatically
	 * fetch model from $_POST["model"], decode it, build it's FormModel
	 * object and save into database. But you must override
	 * Controller2::getModel and return instance of controller's model else
	 * it will throw an exception
	 *
	 * @in (POST):
	 *  + model - String with serialized client form via $("form").serialize(), if you're
	 * 		using Modal or Panel widgets that it will automatically find button with
	 * 		submit type and send ajax request
	 * @out (JSON):
	 *  + message - Message with status
	 *  + status - True if everything ok
	 *
	 * @see Controller2::getModel
	 * @see Modal
	 * @see Panel
	 */
	public function actionRegister() {
		try {
			$directionForm = $this->requireModel("LDirectionForm", [
				"comment",
				"analysis_type_id",
				"treatment_room_employee_id",
				"laboratory_employee_id",
				"history",
				"ward_id"
			]);
			if (!$directionForm->{"medcard_id"}) {
				$this->registerMedcardForDirection($patient, $medcard);
			} else {
				$medcard = LMedcard::model()->findByPk($directionForm->{"medcard_id"});
			}
			$direction = LDirection::loadFromModel($directionForm, [
				"barcode" => BarcodeGenerator::getGenerator()->generate(),
				"enterprise_id" => $medcard->{"enterprise_id"},
				"medcard_id" => $medcard->{"id"},
				"sender_id" => Yii::app()->{"user"}->{"getState"}("doctorId"),
				"status" => 1,
			]);
			if (!$direction->save(true)) {
				throw new CException("Can't register direction in database");
			}
			$this->leave([
				"message" => "Данные медкарты были успешно сохранены"
			]);
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	private function registerMedcardForDirection(&$patient, &$medcard) {
		$patientForm = $this->requireModel("LPatientForm", [
			"surname", "name", "patronymic", "sex", "birthday"
		]);
		$medcardForm = $this->requireModel("LMedcardForm", [
			"mis_medcard", "card_number", "enterprise_id"
		]);
		$registerAddressForm = $this->requireModel("AddressForm_1", null, "/_\\d+$/");
		$addressForm = $this->requireModel("AddressForm_2", null, "/_\\d+$/");
		if ($this->hasValidationErrors()) {
			$this->postValidationError();
		}
		$transaction = Yii::app()->getDb()->beginTransaction();
		try {
			$address = LAddress::loadFromModel($addressForm);
			$registerAddress = LAddress::loadFromModel($registerAddressForm);
			if (!$address->save(true)) {
				throw new CException("Can't register patient address in database");
			}
			if (!$registerAddress->save(true)) {
				throw new CException("Can't register patient register address in database");
			}
			$patient = LPatient::loadFromModel($patientForm, [
				"address_id" => $address->{"id"},
				"register_address_id" => $registerAddress->{"id"},
				"passport_id" => null,
				"policy_id" => null
			]);
			if (!$patient->save(true)) {
				throw new CException("Can't register patient in database");
			}
			$medcard = LMedcard::loadFromModel($medcardForm, [
				"sender_id" => Yii::app()->{"user"}->{"getState"}("doctorId"),
				"patient_id" => $patient->{"id"}
			]);
			if (!$medcard->save(true)) {
				throw new CException("Can't register medcard in database");
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			$this->exception($e);
		}
	}

	/**
	 * That action sets direction status to 4, which means that
	 * analysis should be repeated
	 *
	 * @in (POST):
	 *  + id - direction's identification number
	 * @out (JSON):
	 *  + [message] - Response message
	 *  + status - Action result status
	 *
	 * @throws Exception
	 */
	public function actionRepeat() {
		try {
			$r = LDirection::model()->updateByPk(Yii::app()->getRequest()->getPost("id"), [
				"status" => LDirection::STATUS_SAMPLE_REPEAT
			]);
			if (!$r) {
				$this->error("Произошла ошибка при обновлении данных. Направление не было отправлено на повторный забор образца");
			} else {
				$this->success("Направление отправлено на повторный забор образца");
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
	 * That action sets direction status to 1, which means that
	 * analysis should be repeated
	 *
	 * @in (POST):
	 *  + id - direction's identification number
	 * @out (JSON):
	 *  + [message] - Response message
	 *  + status - Action result status
	 *
	 * @throws Exception
	 */
	public function actionRestore() {
		try {
			$r = LDirection::model()->updateByPk(Yii::app()->getRequest()->getPost("id"), [
				"status" => LDirection::STATUS_JUST_CREATED
			]);
			if (!$r) {
				$this->error("Произошла ошибка при обновлении данных. Направление не было установлено как новое");
			} else {
				$this->success("Направление восстановлено как новое");
			}
		} catch (Exception $e) {
			$this->exception($e);
		}
	}

	/**
     * Override that method to return controller's model
     * @return ActiveRecord - Controller's model instance
     */
    public function getModel() {
        return new LDirection();
    }
}